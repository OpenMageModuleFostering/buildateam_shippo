<?php

class Buildateam_Shippo_Helper_Carrier extends Mage_Core_Helper_Abstract
{
    public function getQuoteFromRateRequest(Mage_Shipping_Model_Rate_Request $request)
    {
        if ( count($request->getAllItems()) )
        {
            $firstItem = current($request->getAllItems());
            $quote = $firstItem->getQuote();
            return $quote;
        }

        return null;
    }

    public function getShippingAddressFromRateRequest(Mage_Shipping_Model_Rate_Request $request)
    {
        if ( $quote = $this->getQuoteFromRateRequest($request) )
        {
            $shippingAddress = $quote->getShippingAddress();
            return $shippingAddress;
        }

        return null;
    }
}