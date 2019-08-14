<?php
class Webcreta_Bargainprice_Model_Bargainprice extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bargainprice/bargainprice');
    }
}
