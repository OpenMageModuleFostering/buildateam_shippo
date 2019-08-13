<?php

class Buildateam_Shippo_Model_System_Config_Source_Weight
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'lb', 'label'=>Mage::helper('adminhtml')->__('Pounds')),
            array('value' => 'kg', 'label'=>Mage::helper('adminhtml')->__('Kilograms')),
        );
    }

    public function toArray()
    {
        return array(
            'lb' => Mage::helper('adminhtml')->__('Pounds'),
            'kg' => Mage::helper('adminhtml')->__('Kilograms'),
        );
    }

}
