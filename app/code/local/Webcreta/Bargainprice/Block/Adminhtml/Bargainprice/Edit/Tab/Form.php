<?php
class Webcreta_Bargainprice_Block_Adminhtml_Bargainprice_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
	  
      $fieldset = $form->addFieldset('bargainprice_form', array('legend'=>Mage::helper('bargainprice')->__('Item information')));
     
      $fieldset->addField('customer_name', 'text', array(
          'label'     => Mage::helper('bargainprice')->__('Sender Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'customer_name',
      ));

       $fieldset->addField('customer_email', 'text', array(
          'label'     => Mage::helper('bargainprice')->__('Email'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'customer_email',
      ));

       $fieldset->addField('product_sku', 'text', array(
          'label'     => Mage::helper('bargainprice')->__('Product Sku'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'product_sku',
      ));

       $fieldset->addField('product_name', 'text', array(
          'label'     => Mage::helper('bargainprice')->__('Product Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'product_name',
      ));

       $fieldset->addField('new_price', 'text', array(
          'label'     => Mage::helper('bargainprice')->__('New Price'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'new_price',
      ));

	$fieldset->addField('message', 'editor', array(
          
          'label'     => Mage::helper('bargainprice')->__('Message'),
          'title'     => Mage::helper('bargainprice')->__('Message'),
          'style'     => 'width:450px; height:200px;',           
          'required'  => true,
		  'name'      => 'message',
      ));
	  
      if ( Mage::getSingleton('adminhtml/session')->getPriceBargainData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPriceBargainData());
          Mage::getSingleton('adminhtml/session')->setPriceBargainData(null);
      } elseif ( Mage::registry('bargainprice_data') ) {
          $form->setValues(Mage::registry('bargainprice_data')->getData());
      }
      return parent::_prepareForm();
  }
}
