<?php
class Webcreta_Bargainprice_Block_Adminhtml_Bargainprice_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'bargainprice';
        $this->_controller = 'adminhtml_bargainprice';
       
        $this->_updateButton('save', 'label', Mage::helper('bargainprice')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('bargainprice')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -200);
		
	$req_id = $this->getRequest()->getParam('id');

		$this->_addButton('approve', array(
		   'label'     => Mage::helper('adminhtml')->__('Approve Request'),
		   'onclick'   => 'setLocation(\''.$this->getUrl('*/*/approve/id/'.$req_id). '\')',
		    'class'     => 'save',
		), -100,1,'header');

	$req_id = $this->getRequest()->getParam('id');
	$cancel_url = $this->getUrl('*/*/cancel/id/'.$req_id);

        $this->_addButton('cancel', array(
           'label'     => Mage::helper('adminhtml')->__('Final Price'),
			'onclick' => 'openMyPopup(\''.$cancel_url.'\')',
            'class'     => 'save',
        ), -100,5,'header');


	$this->_addButton('reject', array(
           'label'     => Mage::helper('adminhtml')->__('Reject'),
          'onclick'   => 'setLocation(\''.$this->getUrl('*/*/reject/id/'.$req_id). '\')',
            'class'     => 'cancel',
        ), -100,9,'header');

        $this->_formScripts[] = "function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
	function openMyPopup(cancel_url) {
        var url = cancel_url ;
        if ($('browser_window') && typeof(Windows) != 'undefined') {
            Windows.focus('browser_window');
            return;
        }
        var dialogWindow = Dialog.info(null, {
            closable:true,
            resizable:false,
            draggable:true,
            className:'magento',
            windowClassName:'popup-window',
            title:'Last Possible Price',
            top:50,
            width:800,
            height:400,
            zIndex:1000,
            recenterAuto:false,
            hideEffect:Element.hide,
            showEffect:Element.show,
            id:'browser_window',
            url:url,
        });
    	}
	function closePopup() {
        Windows.close('browser_window');
    	}
	"; 
    }

    public function getHeaderText()
    {
        if( Mage::registry('bargainprice_data') && Mage::registry('bargainprice_data')->getId() ) {
            return Mage::helper('bargainprice')->__("Edit Item '%d'", $this->htmlEscape(Mage::registry('bargainprice_data')->getId()));
        } else {
            return Mage::helper('bargainprice')->__('Add Item');
        }
    }
	
	protected function _prepareLayout()
    {
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled() && ($block = $this->getLayout()->getBlock('head'))) {
            $block->setCanLoadTinyMce(true);
        }
        parent::_prepareLayout();
    } 
}
