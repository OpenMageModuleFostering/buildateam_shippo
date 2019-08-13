<?php

class Buildateam_Shippo_Model_System_Config_Source_Size
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'in', 'label'=>Mage::helper('adminhtml')->__('Inches')),
            array('value' => 'cm', 'label'=>Mage::helper('adminhtml')->__('Centimeters')),
        );
    }

    public function toArray()
    {
        return array(
            'in' => Mage::helper('adminhtml')->__('Inches'),
            'cm' => Mage::helper('adminhtml')->__('Centimeters'),
        );
    }

}
