<?php
	class Webcreta_Bargainprice_Adminhtml_BargainpriceController extends Mage_Adminhtml_Controller_Action
	{
		protected function _initAction() {
			$this->loadLayout()
			->_setActiveMenu('bargainprice/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			
			return $this;
		}   
		
		public function indexAction() {
			$this->_initAction()
			->renderLayout();
		}
		
		public function approveAction() {
			
			$req_id = $this->getRequest()->getParam('id');
			$req_model = Mage::getModel('bargainprice/bargainprice')->load($req_id);
			$req_data = $req_model->getData();
			$sku = $req_data['product_sku'];
			$product_price = str_replace("$", "", $req_data['product_price']);
			
			if($req_data['status_customer'] == 3):
			
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error! Customer Already Rejected Your Amount /Request '));
			
			elseif($req_data['status_owner'] == 1):
			
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error! You Already Approved This Request.. '));
			
			else:
			
			$new_price0 = $req_data['new_price'];
			$new_price1 = $req_data['owner_bid'];
			
			if($new_price0 >$new_price1 ):
			$req_model->setData('owner_bid',$new_price0);
			$req_model->save();
			$discount_amount = $product_price - $new_price0;
			else:
			
			$discount_amount = $product_price - $new_price1; 
			endif;
			
			$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
			$uniqueId = Mage::helper('core')->getRandomString(16); 
			
			$rule = Mage::getModel('salesrule/rule');
			
			$rule->setName('Discount Request from Customer by new price')                                             
			->setDescription('Auto Generated')
			->setFromDate(date('Y-m-d'))
			->setCouponType(2)
			->setCouponCode($uniqueId)
			->setUsesPerCustomer(1)
			->setUsesPerCoupon(1)
			->setCustomerGroupIds($customerGroupIds)
			->setIsActive(1)
			->setConditionsSerialized('')
			->setActionsSerialized('')
			->setStopRulesProcessing(0)
			->setIsAdvanced(1)
			->setProductIds('')
			->setSortOrder(0)
			->setSimpleAction('by_fixed')
			->setDiscountAmount($discount_amount)
			->setDiscountQty(0)
			->setDiscountStep(0)
			->setSimpleFreeShipping('0')
			->setApplyToShipping('0')
			->setIsRss(0)
			->setWebsiteIds(array(1));
			
			$productFoundCondition = Mage::getModel('salesrule/rule_condition_product_found')
			->setType('salesrule/rule_condition_product_found')
			->setValue(1)             
			->setAggregator('all');   
			
			$attributeSetCondition = Mage::getModel('salesrule/rule_condition_product')
			->setType('salesrule/rule_condition_product')
			->setAttribute('sku')
			->setOperator('==')
			->setValue($sku);
			
			$productFoundCondition->addCondition($attributeSetCondition);
			
			$rule->getConditions()->addCondition($productFoundCondition);
			
			$rule->getActions()->addCondition($attributeSetCondition);
			
			$rule->save();
			
			$newpriceId = $this->getRequest()->getParam('id');
			
			$req_model ->setStatusOwner('1')
			->save();
			
			$req_model ->setStatusCustomer('1')
			->save();

			$req_model->setData('discount_code',$uniqueId);
			$req_model->save();
			
			$sender_name = Mage::getStoreConfig('trans_email/ident_general/name');
			$sender_email = Mage::getStoreConfig('trans_email/ident_general/email');
			
			$currency_symbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
			
			$emailTemplate  = Mage::getModel('core/email_template')
			->loadDefault('bargainprice_approve_email_template');                               
			$emailTemplateVariables = array();
			$emailTemplateVariables['sender_name'] = $sender_name ;
			$emailTemplateVariables['customer_name'] = $req_data['customer_name'] ;
			$emailTemplateVariables['customer_email'] = $req_data['customer_email'] ;
			$emailTemplateVariables['sender_email'] = $sender_email ;
			$emailTemplateVariables['product'] = $req_data['product_name'];
			$emailTemplateVariables['product_price'] = $req_data['product_price'];
			if($req_data['owner_bid']){
			   $emailTemplateVariables['new_price'] = $currency_symbol.$req_data['owner_bid'];
			}else{
			   $emailTemplateVariables['new_price'] = $currency_symbol.$req_data['new_price'];
			}
			$emailTemplateVariables['coupon_code'] = $uniqueId;
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
			$emailTemplate->setSenderName($sender_name);
			$emailTemplate->setSenderEmail($sender_email);
			$emailTemplate->setTemplateSubject('Your Price Request has been approved for Product('.$req_data['product_name'].')');
			
			$emailTemplate->send($req_data['customer_email'],$req_data['customer_name'], $emailTemplateVariables); 
			
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Request has been approved'));
			
			endif;
			
			$this->_redirect('*/*/');
			
		}
		
		public function cancelAction() {
			$this->loadLayout();
	        $this->renderLayout();
		}
		
		public function cancelformAction() {
			
			$req_id = $this->getRequest()->getParam('id');
			$req_model = Mage::getModel('bargainprice/bargainprice')
			->load($req_id);
			$cancel_form_data = Mage::app()->getRequest()->getParams();
			$bargain_price = $cancel_form_data['minimum_price'];
			$valid_price = is_numeric($cancel_form_data['minimum_price']);
			if($req_model -> getStatusOwner()==1):
			
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error! This Price Has Been Approved ,You can not Change it Again '));
			
			elseif($req_model -> getStatusCustomer() == 3):
			
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error! Customer Alredy Denied This Request.. '));
			
			else:
			if ($valid_price == false):
			
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error! Please Enter Valid Price, Do not use Price Symbol. '));
			
			else :	
			$req_model->setStatusOwner('2')
			->save();
			$req_data = $req_model->getData();
			
			$currency_symbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
			
			$sender_name = Mage::getStoreConfig('trans_email/ident_general/name');
			$sender_email = Mage::getStoreConfig('trans_email/ident_general/email');
			
			$emailTemplate  = Mage::getModel('core/email_template')
			->loadDefault('bargainprice_finalprice_email_template');                               
			$emailTemplateVariables = array();
			$emailTemplateVariables['sender_name'] = $sender_name ;
			$emailTemplateVariables['customer_name'] = $req_data['customer_name'] ;
			$emailTemplateVariables['sender_email'] = $sender_email ;
			$emailTemplateVariables['product'] = $req_data['product_name'];
			$emailTemplateVariables['product_price'] = $req_data['product_price'];
			$emailTemplateVariables['new_price'] = $req_data['new_price'];
			$emailTemplateVariables['minimum_price'] = $currency_symbol.$cancel_form_data['minimum_price'];
			$emailTemplateVariables['message'] = $cancel_form_data['message'];
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
			$emailTemplate->setSenderName($sender_name);
			$emailTemplate->setSenderEmail($sender_email);
			$emailTemplate->setTemplateSubject('This Could be Our Last Price For Product('.$req_data['product_name'].')');
			
			$emailTemplate->send($req_data['customer_email'],$req_data['customer_name'], $emailTemplateVariables); 
			
			$ownerbid=Mage::getModel('bargainprice/bargainprice')->getCollection()
			->addFieldToFilter('bargainprice_id',$req_id)
			->addFieldToSelect('*');
			
			$record=Mage::getModel('bargainprice/bargainprice')->load($req_id);
			
			foreach($ownerbid as $finalbid):
			
			$owner_bid=$finalbid['owner_bid'];
			
			endforeach;	
			
			if(empty($owner_bid)):
			
			$record->setData('owner_bid',$cancel_form_data['minimum_price']);
			$record->save();
			
			endif;
			
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Success! Last Possible Price Has been Sent..'));
			endif;
			endif;
			
		}
		
		public function rejectAction(){
			
			$req_id = $this->getRequest()->getParam('id');
			$req_model = Mage::getModel('bargainprice/bargainprice')
			->load($req_id);
			
			if($req_model -> getStatusOwner()==1):
			
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error! Sorry. You already Approved This Request You can not Reject it Now. '));
			
			elseif ($req_model -> getStatusOwner()==3):
			
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error! You Aready Rejected This Request... '));	
			
			else: 
			$req_model->setStatusOwner('3')
			->save();
			
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Success! Request Has Been Successfully Rejected'));
			
			endif;
			$this->_redirect('*/*/');
		}
		
		public function editAction() {
			$id     = $this->getRequest()->getParam('id');
			$model  = Mage::getModel('bargainprice/bargainprice')->load($id);
			
			if ($model->getId() || $id == 0) {
				$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
				if (!empty($data)) {
					$model->setData($data);
				}
				
				Mage::register('bargainprice_data', $model);
				
				$this->loadLayout();
				$this->_setActiveMenu('bargainprice/items');
				
				$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
				$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
				
				$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
				
				$this->_addContent($this->getLayout()->createBlock('bargainprice/adminhtml_bargainprice_edit'))
				->_addLeft($this->getLayout()->createBlock('bargainprice/adminhtml_bargainprice_edit_tabs'));
				
				$this->renderLayout();
				} else {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bargainprice')->__('Item does not exist'));
				$this->_redirect('*/*/');
			}
		}
		
		public function newAction() {
			$this->_forward('edit');
		}
		
		public function saveAction() {
			if ($data = $this->getRequest()->getPost()) {
				
				$model = Mage::getModel('bargainprice/bargainprice');		
				$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
				
				try {
					if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
						$model->setCreatedTime(now())
						->setUpdateTime(now());
						} else {
						$model->setUpdateTime(now());
					}	
					
					$model->save();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bargainprice')->__('Item was successfully saved'));
					Mage::getSingleton('adminhtml/session')->setFormData(false);
					
					if ($this->getRequest()->getParam('back')) {
						$this->_redirect('*/*/edit', array('id' => $model->getId()));
						return;
					}
					$this->_redirect('*/*/');
					return;
					} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					Mage::getSingleton('adminhtml/session')->setFormData($data);
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
					return;
				}
			}
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bargainprice')->__('Unable to find item to save'));
			$this->_redirect('*/*/');
		}
		
		public function deleteAction() {
			if( $this->getRequest()->getParam('id') > 0 ) {
				
				try {
					$model = Mage::getModel('bargainprice/bargainprice');
					
					$model->setId($this->getRequest()->getParam('id'))
					->delete();
					
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
					$this->_redirect('*/*/');
					} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				}
			}
			$this->_redirect('*/*/');
		}
		
		public function massDeleteAction() {
			$newpriceIds = $this->getRequest()->getParam('bargainprice');
			if(!is_array($newpriceIds)) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
				} else {
				try {
					foreach ($newpriceIds as $newpriceId) {
						$bargainprice = Mage::getModel('bargainprice/bargainprice')->load($newpriceId);
						$bargainprice->delete();
					}
					Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
					'Total of %d record(s) were successfully deleted', count($newpriceIds)
                    )
					);
					} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				}
			}
			$this->_redirect('*/*/index');
		}
		
		public function massStatusAction()
		{
			$newpriceIds = $this->getRequest()->getParam('bargainprice');
			if(!is_array($newpriceIds)) {
				Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
				} else {
				try {
					foreach ($newpriceIds as $newpriceId) {
						$bargainprice = Mage::getSingleton('bargainprice/bargainprice')
                        ->load($newpriceId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
					}
					$this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($newpriceIds))
					);
					} catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
			$this->_redirect('*/*/index');
		}
		
		public function exportCsvAction()
		{
			$fileName   = 'bargainprice.csv';
			$content    = $this->getLayout()->createBlock('bargainprice/adminhtml_bargainprice_grid')
            ->getCsv();
			
			$this->_sendUploadResponse($fileName, $content);
		}
		
		public function exportXmlAction()
		{
			$fileName   = 'bargainprice.xml';
			$content    = $this->getLayout()->createBlock('bargainprice/adminhtml_bargainprice_grid')
            ->getXml();
			
			$this->_sendUploadResponse($fileName, $content);
		}
		
		protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
		{
			$response = $this->getResponse();
			$response->setHeader('HTTP/1.1 200 OK','');
			$response->setHeader('Pragma', 'public', true);
			$response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
			$response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
			$response->setHeader('Last-Modified', date('r'));
			$response->setHeader('Accept-Ranges', 'bytes');
			$response->setHeader('Content-Length', strlen($content));
			$response->setHeader('Content-type', $contentType);
			$response->setBody($content);
			$response->sendResponse();
			die;
		}
	}
