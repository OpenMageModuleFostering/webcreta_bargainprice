<?php
class Webcreta_Bargainprice_AccountpageController extends Mage_Core_Controller_Front_Action {

    protected function _getSession() {
        return Mage::getSingleton('customer/session');
    }

    public function preDispatch() {
        parent::preDispatch();
            if (!Mage::getSingleton('customer/session')->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
        }
    }

    public function indexAction() { 
        $this->loadLayout();
        $this->renderLayout();

    }

}

