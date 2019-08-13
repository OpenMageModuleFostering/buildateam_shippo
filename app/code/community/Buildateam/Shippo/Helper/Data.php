<?php

class Buildateam_Shippo_Helper_Data extends Mage_Core_Helper_Abstract
{
    const LOG_FILE = 'buildateam_shippo.log';

    const PATH_ECONOMY_DESCRIPTION = 'carriers/buildateam_shippo/economy_description';
    const PATH_EXPEDITED_DESCRIPTION = 'carriers/buildateam_shippo/expedited_description';
    const PATH_OVERNIGHT_DESCRIPTION = 'carriers/buildateam_shippo/overnight_description';
    const PATH_EXPEDITED_METHOD_COST = 'carriers/buildateam_shippo/expedited_cost';
    const PATH_OVERNIGHT_METHOD_COST = 'carriers/buildateam_shippo/overnight_cost';
    const NOTIFY_EMAIL_RECIPIENT     = 'carriers/buildateam_shippo/notify_email_recipient';

    const PATH_EMAIL_DELAY_TIME = 'buildateam_shippo/general/email_delay';
    const PATH_EMAIL_DELAY_ENABLED = 'buildateam_shippo/general/enable';


    const PATH_FROM_PRIVATE_AUTH_TOKEN = 'carriers/buildateam_shippo/private_auth_token';

    const PATH_FROM_NAME     = 'carriers/buildateam_shippo/from_name';
    const PATH_FROM_COMPANY  = 'carriers/buildateam_shippo/from_company';
    const PATH_FROM_COUNTRY  = 'carriers/buildateam_shippo/from_country';
    const PATH_FROM_REGION   = 'carriers/buildateam_shippo/from_region';
    const PATH_FROM_POSTCODE = 'carriers/buildateam_shippo/from_postcode';
    const PATH_FROM_CITY     = 'carriers/buildateam_shippo/from_city';
    const PATH_FROM_STREET   = 'carriers/buildateam_shippo/from_street';
    const PATH_FROM_PHONE    = 'carriers/buildateam_shippo/from_phone';
    const PATH_FROM_EMAIL    = 'carriers/buildateam_shippo/from_email';

    const PATH_WEIGHT_UNIT   = 'carriers/buildateam_shippo/weight_unit';
    const PATH_SIZE_UNIT     = 'carriers/buildateam_shippo/size_unit';
    const PATH_HANDLING      = 'carriers/buildateam_shippo/shipping_handling';


    protected static $methods = array(
        'tablerate_bestway' => self::PATH_ECONOMY_DESCRIPTION,
        'buildateam_shippo_expedited' => self::PATH_EXPEDITED_DESCRIPTION,
        'buildateam_shippo_overnight' => self::PATH_OVERNIGHT_DESCRIPTION,
    );


    public function getPrivateAuthToken($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_PRIVATE_AUTH_TOKEN, $store);
    }

    public function getFromName($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_NAME, $store);
    }

    public function getFromCompany($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_COMPANY, $store);
    }

    public function getFromCountry($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_COUNTRY, $store);
    }

    public function getFromRegion($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_REGION, $store);
    }

    public function getFromPostcode($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_POSTCODE, $store);
    }

    public function getFromCity($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_CITY, $store);
    }

    public function getFromStreet($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_STREET, $store);
    }

    public function getFromPhone($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_PHONE, $store);
    }

    public function getFromEmail($store = null)
    {
        return Mage::getStoreConfig(self::PATH_FROM_EMAIL, $store);
    }


    public function getWeightUnit($store = null)
    {
        return Mage::getStoreConfig(self::PATH_WEIGHT_UNIT, $store);
    }

    public function getSizeUnit($store = null)
    {
        return Mage::getStoreConfig(self::PATH_SIZE_UNIT, $store);
    }

    public function getHandling($store = null)
    {
        return Mage::getStoreConfig(self::PATH_HANDLING, $store);
    }

    /**
     * Returns economy shipping method description.
     *
     * @param null $store
     * @return mixed
     */
    public function getEconomyDescription($store = null)
    {
        return Mage::getStoreConfig(self::PATH_ECONOMY_DESCRIPTION, $store);
    }

    /**
     * Returns expedited shipping method description.
     *
     * @param null $store
     * @return mixed
     */
    public function getExpeditedDescription($store = null)
    {
        return Mage::getStoreConfig(self::PATH_EXPEDITED_DESCRIPTION, $store);
    }

    /**
     * Returns overnight shipping method description.
     *
     * @param null $store
     * @return mixed
     */
    public function getOvernightDescription($store = null)
    {
        return Mage::getStoreConfig(self::PATH_OVERNIGHT_DESCRIPTION, $store);
    }

    /**
     * Returns exppedited shipping method cost.
     *
     * @param null $store
     * @return float
     */
    public function getExpeditedMethodCost($store = null)
    {
        return (float)Mage::getStoreConfig(self::PATH_EXPEDITED_METHOD_COST, $store);
    }

    /**
     * Returns overnight shipping method cost.
     *
     * @param null $store
     * @return float
     */
    public function getOvernightMethodCost($store = null)
    {
        return (float)Mage::getStoreConfig(self::PATH_OVERNIGHT_METHOD_COST, $store);
    }

    /**
     * Checks if we can show description.
     *
     * @param array $rates
     * @return bool
     */
    public function canShowDescription($rates)
    {
        if (!is_array($rates)) {
            return false;
        }
        return array_key_exists(Buildateam_Shippo_Model_Carrier::CODE, $rates);
    }

    /**
     * Returns description for shipping method.
     *
     * @param string $code
     * @return string|null
     */
    public function getDescription($code)
    {
        if (!array_key_exists($code, self::$methods)) {
            return;
        }

        return Mage::getStoreConfig(self::$methods[$code]);
    }

    /**
     * Returns notify email recipient
     *
     * @param null $store
     * @return string|null
     */
    public function getEmailRecipient($store = null)
    {
        return (string)Mage::getStoreConfig(self::NOTIFY_EMAIL_RECIPIENT, $store);
    }


    /**
     * Returns shipping email delay time (when we create it using SOAP request).
     *
     * @param null $store
     * @return float
     */
    public function getEmailDelayTime($store = null)
    {
        return (int)Mage::getStoreConfig(self::PATH_EMAIL_DELAY_TIME, $store);
    }

    /**
     * Returns if delay is enabled for shipping confirmation emails (when we create shipment using SOAP request)
     *
     * @param null $store
     * @return bool
     */
    public function getEmailDelayEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::PATH_EMAIL_DELAY_ENABLED, $store);
    }

    public function log($message)
    {
        Mage::log($message, null, self::LOG_FILE);
    }
}