<?php
class Webcreta_Bargainprice_Block_Customer extends Mage_Customer_Block_Account_Dashboard  {

     public function getBargainprice()     
     { 
        if (!$this->hasData('bargainprice')) {
            $this->setData('bargainprice', Mage::registry('bargainprice'));
        }
        return $this->getData('bargainprice');
        
    }
}


