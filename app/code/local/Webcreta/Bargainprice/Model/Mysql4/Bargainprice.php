<?php
class Webcreta_Bargainprice_Model_Mysql4_Bargainprice extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('bargainprice/bargainprice', 'bargainprice_id');
    }
}
