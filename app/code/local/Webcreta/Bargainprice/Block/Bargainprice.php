<?php
class Webcreta_Bargainprice_Block_Bargainprice extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getBannerSlider()     
    { 
        if (!$this->hasData('bargainprice')) {
            $this->setData('bargainprice', Mage::registry('bargainprice'));
        }
        return $this->getData('bargainprice'); 
    }
}
