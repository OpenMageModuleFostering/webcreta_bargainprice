<?php
class Webcreta_Bargainprice_Block_Adminhtml_Bargainprice_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('bargainpriceGrid');
      $this->setDefaultSort('bargainprice_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('bargainprice/bargainprice')->getCollection();
      $this->setCollection($collection);
	
      return parent::_prepareCollection();
  }
  
  protected function _prepareColumns()
  {
      $this->addColumn('bargainprice_id', array(
          'header'    => Mage::helper('bargainprice')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'bargainprice_id',
      ));

      $this->addColumn('customer_name', array(
          'header'    => Mage::helper('bargainprice')->__('Sender Name'),
          'align'     =>'left',
          'index'     => 'customer_name',
      ));

	$this->addColumn('product_name', array(
          'header'    => Mage::helper('bargainprice')->__('Product Name'),
          'align'     =>'left',
          'index'     => 'product_name',
      ));

	$this->addColumn('product_price', array(
          'header'    => Mage::helper('bargainprice')->__('Product Price'),
          'align'     =>'left',
          'index'     => 'product_price',
      ));	  
		
	$this->addColumn('new_price', array(
          'header'    => Mage::helper('bargainprice')->__('Customer Price'),
          'align'     =>'left',
          'index'     => 'new_price',
      ));

	$this->addColumn('owner_bid', array(
          'header'    => Mage::helper('bargainprice')->__('Final Price'),
          'align'     =>'left',
          'index'     => 'owner_bid',
	 
      ));
	
      $this->addColumn('status_customer', array(

          'header'    => Mage::helper('bargainprice')->__('Customer Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status_customer',
          'type'      => 'options',
          'options'   => array(

	      0 =>'Pending',
		
              1 => 'Approved',

              2 => 'Waiting',
	
	      3 => 'Rejected',		

          ),
	));

	$this->addColumn('status_owner', array(

          'header'    => Mage::helper('bargainprice')->__('Owner Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status_owner',
          'type'      => 'options',
          'options'   => array(

	      0 =>'Pending',
		
              1 => 'Approved',

              2 => 'Waiting',
	
	      3 => 'Rejected',		

          ),
      ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('bargainprice')->__('Action'),
				'align'	    =>  'center',
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('bargainprice')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('bargainprice')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('bargainprice')->__('XML'));
	  
      return parent::_prepareColumns();
  }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
