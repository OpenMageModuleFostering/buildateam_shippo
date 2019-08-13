<?php

class Buildateam_Shippo_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    const CODE = 'buildateam_shippo'; // for static access

    const EXPEDITED = 'expedited';
    const OVERNIGHT = 'overnight';

    protected $_code = self::CODE;

    /**
     * Returns allowed methods.
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        $helper = Mage::helper('buildateam_shippo');
        return array(
            self::EXPEDITED => $helper->__('Expedited'),
            self::OVERNIGHT => $helper->__('Overnight'),
        );
    }

    /**
     * We have no tracking.
     *
     * @return bool
     */
    public function isTrackingAvailable()
    {
        return false;
    }

    /**
     * Collects shipping rates.
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return bool|Mage_Shipping_Model_Rate_Result|null
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        /** @var Mage_Shipping_Model_Rate_Result $result */
        $result = Mage::getModel('shipping/rate_result');

        try {
            $aRates = Mage::helper('buildateam_shippo/shippo')->getRates($request);

            if ( $aRates )
            foreach($aRates as $aRate) {
                $rate = $this->_prepareRate($aRate);
                $result->append($rate);
            }

        } catch (Exception $e) {
            Mage::helper('buildateam_shippo')->log( $e->getMessage() );
        }

        //$result->append($this->_getExpeditedRate());
        //$result->append($this->_getOvernightRate());

        return $result;
    }

    protected function _prepareRate($aRate)
    {
        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);//($aRate['servicelevel_token']);
        $rate->setCarrierTitle($aRate['provider']);
        $rate->setMethod($aRate['servicelevel_token']);
        $rate->setMethodTitle($aRate['servicelevel_name']);
        $rate->setPrice($aRate['amount']);
        $rate->setCost(0);

        return $rate;
    }

    protected function _getExpeditedRate()
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod(self::EXPEDITED);
        $rate->setMethodTitle('Expedited');
        $cost = $this->getDefaultHelper()->getExpeditedMethodCost();
        $rate->setPrice($cost);
        $rate->setCost(0);

        return $rate;
    }

    protected function _getOvernightRate()
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod(self::OVERNIGHT);
        $rate->setMethodTitle('Overnight');
        $cost = $this->getDefaultHelper()->getOvernightMethodCost();
        $rate->setPrice($cost);
        $rate->setCost(0);

        return $rate;
    }

    /**
     * Returns default helper.
     *
     * @return buildateam_shippo_Helper_Data
     */
    public function getDefaultHelper()
    {
        return Mage::helper('buildateam_shippo');
    }
}