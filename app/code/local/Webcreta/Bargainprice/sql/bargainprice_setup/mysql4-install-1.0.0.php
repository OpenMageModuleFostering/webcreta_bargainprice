<?php
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('bargainprice')};
CREATE TABLE {$this->getTable('bargainprice')} (
  `bargainprice_id` int(11) unsigned NOT NULL auto_increment,
  `customer_id` varchar(255) NOT NULL default '',
  `customer_name` varchar(255) NOT NULL default '',
  `customer_email` varchar(255) NOT NULL default '',
  `product_id` varchar(255) NOT NULL default '',
  `product_sku` varchar(55) NOT NULL default '',
  `product_name` varchar(25) NOT NULL,
  `product_price` varchar(25) NOT NULL,
  `new_price` varchar(25) NOT NULL,
  `owner_bid` varchar(255) NOT NULL default '',
  `action` int(10) NOT NULL ,
  `discount_code` varchar(25)  NULL,
  `message` varchar(255) NULL,
  `status_owner` int(10) NOT NULL,
  `status_customer` int(10) NOT NULL,
  PRIMARY KEY (`bargainprice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 

?>













