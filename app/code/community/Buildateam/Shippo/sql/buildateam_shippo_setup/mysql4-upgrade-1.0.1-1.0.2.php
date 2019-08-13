<?php

$installer = $this;

$installer->startSetup();

$objCatalogEavSetup = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');

$objCatalogEavSetup->addAttribute(
    "catalog_product",      // Entity the new attribute is supposed to be added to
    "dimensions_lwh", // attribute code
    array(
        "type"             => "varchar",
        "label"            => "Dimensions",
        "note"             => "Length, Width and Height. Example: 15.5x7.5x5",
        "input"            => "text",
        "frontend_class"   => "validate-dimentions-lwh",
        "global"           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        // Dont know if this is really necessary, but it makes sure
        // the attribute is created as a system attribute:
        'required'         => false,
        "user_defined"     => false,
        //"apply_to"       => Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
        'apply_to'         => implode(',', array(
            Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
            Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
        )),
        'sort_order'       => 9
    )
);

$installer->endSetup();
