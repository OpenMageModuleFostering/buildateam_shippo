<?php

class Buildateam_Shippo_TestController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getPrivateAuthToken() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromName() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromCompany() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromCountry() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromRegion() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromPostcode() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromCity() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromStreet() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromPhone() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getFromEmail() );

        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getWeightUnit() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getSizeUnit() );
        echo '<br/>';
        var_dump( Mage::helper('buildateam_shippo')->getHandling() );


    }

}
