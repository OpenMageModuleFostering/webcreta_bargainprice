<?php
class Webcreta_Bargainprice_Model_Mysql4_Bargainprice_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bargainprice/bargainprice');
    }
}
