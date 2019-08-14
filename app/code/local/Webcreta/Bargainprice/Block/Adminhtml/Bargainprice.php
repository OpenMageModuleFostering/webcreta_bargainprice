<?php
class Webcreta_Bargainprice_Block_Adminhtml_Bargainprice extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_bargainprice';
    $this->_blockGroup = 'bargainprice';
    $this->_headerText = Mage::helper('bargainprice')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('bargainprice')->__('Add Item');
    parent::__construct();
    $this->removeButton('add');
  }
}
