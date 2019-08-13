<?php

class Buildateam_Shippo_Block_Adminhtml_Frontend_Region_Updater
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_idCountry = 'carriers_buildateam_shippo_from_country';

    protected $_idRegion = 'carriers_buildateam_shippo_from_region';

    public function getCountryElementId()
    {
        return $this->_idCountry;
    }

    public function getRegionElementId()
    {
        return $this->_idRegion;
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = parent::_getElementHtml($element);

        $idCountry = $this->getCountryElementId();
        $idRegion = $this->getRegionElementId();

        $js = '<script type="text/javascript">
               var updater = new RegionUpdater("' . $idCountry . '", "none", "' . $idRegion . '", %s, "nullify");
               if(updater.lastCountryId) {
                   var tmpRegionId = $("' . $idRegion . '").value;
                   var tmpCountryId = updater.lastCountryId;
                   updater.lastCountryId=false;
                   updater.update();
                   updater.lastCountryId = tmpCountryId;
                   $("' . $idRegion .'").value = tmpRegionId;
               } else {
                   updater.update();
               }
               </script>';

        $html .= sprintf($js, $this->helper('directory')->getRegionJson());
        return $html;
    }
}



