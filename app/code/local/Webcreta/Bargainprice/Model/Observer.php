<?php
	class Webcreta_Bargainprice_Model_Observer
	{
		public function __contruct()
	    {
			
		}
		
		public function removeCoupon(Varien_Event_Observer $observer)
		{	
			$orders = Mage::getModel('sales/order')->getCollection()
			->setOrder('created_at','DESC')
			->setPageSize(1)
			->setCurPage(1);
			
			$orderId = $orders->getFirstItem()->getEntityId();
			
			$order_id = $orderId;
			
			$items = Mage::getModel('sales/order_item')
			->getCollection()
			->addFieldToFilter('order_id',array('eq'=>$order_id))
			->addFieldToSelect('*');
			
			foreach($items as $item){
				
				if($item->getAppliedRuleIds() == '')continue;
				
				foreach(explode(",",$item->getAppliedRuleIds()) as $ruleID){        
					
					$rule_model = Mage::getModel('salesrule/rule')
					->getCollection()
					->addFieldToFilter('rule_id',$ruleID)
					->getData();
					
					if ($rule_model['name']=='Discount Request from Customer by price bargain button')
					{
						$rule_model->delete();
					}
				}
			}
		}
	}
	
