<?php
class Webcreta_Bargainprice_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }

    public function loadformAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }

    public function subformAction()
    {
	$params = Mage::app()->getRequest()->getParams();

	$valid_price = is_numeric($params['new_price']);

	if($valid_price == true):

	$data = array('customer_id'=>$params['customer_id'],'customer_name'=>$params['customer_name'],'customer_email'=>$params['customer_email'],'product_id'=>$params['product_id'],'product_sku'=>$params['product_sku'],'product_name'=>$params['product_name'],'product_price'=>$params['product_price'],'new_price'=>$params['new_price'],'message'=>$params['new_message']);
	$model = Mage::getModel('bargainprice/bargainprice');
	$model->setData($data);
	$model->save();
		if($model->save()):
		
			$emailTemplate  = Mage::getModel('core/email_template')
		                        ->loadDefault('bargainprice_email_template');                               
		        $emailTemplateVariables = array();
		        $emailTemplateVariables['customer_name'] = $params['customer_name'];
		        $emailTemplateVariables['customer_email'] = $params['customer_email'];
		        $emailTemplateVariables['product'] = $params['product_name'];
		        $emailTemplateVariables['product_price'] = $params['product_price'];
		        $emailTemplateVariables['new_price'] = $params['new_price'];
			$emailTemplateVariables['content'] = $params['new_message'];
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
		        $emailTemplate->setSenderName($params['customer_name']);
		        $emailTemplate->setSenderEmail($params['customer_email']);
		        $emailTemplate->setTemplateSubject('New Price Request by Customer ('.$params['customer_name'].')');
		
			$emailTemplate->send(Mage::getStoreConfig('trans_email/ident_general/email'),Mage::getStoreConfig('trans_email/ident_general/name'), $emailTemplateVariables);  
	
	 		Mage::getSingleton('core/session')->addSuccess('Success! Your Bargain price has been submitted. We will contact you through email. ');

		else:
		
			Mage::getSingleton('core/session')->addError('Error! Something wrong Happend,Please Try Again Later. ');

		endif;	
		
	else:

		Mage::getSingleton('core/session')->addError('Error! Please Enter Valid Price ,Do not use Price Symbol. ');

	endif;	
	
    }

	public function customeracceptAction(){

	echo	$couponid=$this->getRequest()->getPost('coupon_id');
		
		$ownerbid=Mage::getModel('bargainprice/bargainprice')->getCollection()
						->addFieldToFilter('bargainprice_id',$couponid)
						->addFieldToSelect('*');
		
		$record=Mage::getModel('bargainprice/bargainprice')->load($couponid);
		foreach($ownerbid as $finalbid):
			
				 $customer_status=$finalbid['status_customer'];
				 $discount = $finalbid['discount_code'];
				 $final = $finalbid['owner_bid'];
				
				endforeach;	
			
			if(empty($discount)):
				
				if(($customer_status == 0 || $customer_status == 3) && !empty($final)):
			
					$record->setData('status_customer',1);
					$record->save();
					if($record->save()):
						 Mage::getSingleton('core/session')->addSuccess('Success! You Approved Request Successfully. ');
					else:
						Mage::getSingleton('core/session')->addError('Error! Something wrong Happend,Please Try Again Later. ');

					endif;
				endif;
			endif;

	}

	public function customerrejectAction(){

		
		$couponid = $this->getRequest()->getPost('coupon_id');

		$ownerbid = Mage::getModel('bargainprice/bargainprice')->getCollection()
							->addFieldToFilter('bargainprice_id',$couponid)
							->addFieldToSelect('*');
		
	  	$record = Mage::getModel('bargainprice/bargainprice')->load($couponid);
		
			foreach($ownerbid as $finalbid):
			
					 $customer_status=$finalbid['status_customer'];
					 $discount = $finalbid['discount_code'];
				
			endforeach;	

			if(empty($discount)):
				if($customer_status == 0):
		
					$record->setData('status_customer',3);
					$record->save();

					if($record->save()):
						 Mage::getSingleton('core/session')->addSuccess('Success! You Rejected Request Successfully. ');
					else:
						Mage::getSingleton('core/session')->addError('Error! Something wrong Happend,Please Try Again Later. ');

					endif;	
				endif;
			endif;
	}
}
