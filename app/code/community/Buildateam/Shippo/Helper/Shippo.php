<?php

class Buildateam_Shippo_Helper_Shippo extends Mage_Core_Helper_Abstract
{
    public function getRates(Mage_Shipping_Model_Rate_Request $request)
    {
        // Replace <API-KEY> with your credentials
        # From goshippo.com > API > API Keys > Private Auth Token
        //$devPrivateAuthToken = '167263307a16e8b1c51fbf4b055decc85b683678';   # DEV
        Shippo::setApiKey( Mage::helper('buildateam_shippo')->getPrivateAuthToken() );

        /*
        // example fromAddress
        $fromAddress = array(
            'object_purpose' => 'PURCHASE',
            'name' => 'Mr Hippo"',
            'company' => 'Shippo',
            'street1' => '215 Clayton St.',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94117',
            'country' => 'US',
            'phone' => '+1 555 341 9393',
            'email' => 'mr-hippo@goshipppo.com'
        );


        // example fromAddress
        $toAddress = array(
            'object_purpose' => 'PURCHASE',
            'name' => 'Ms Hippo"',
            'company' => 'San Diego Zoo"',
            'street1' => '2920 Zoo Drive"',
            'city' => 'San Diego',
            'state' => 'CA',
            'zip' => '92101',
            'country' => 'US',
            'phone' => '+1 555 341 9393',
            'email' => 'ms-hippo@goshipppo.com'
        );

        // example parcel
        $parcel = array(
            'length'=> '5',
            'width'=> '5',
            'height'=> '5',
            'distance_unit'=> 'in',
            'weight'=> '2',
            'mass_unit'=> 'lb',
        );
        */

        $fromAddress = $this->prepareFromAddress();
        $mageShippingAddress = Mage::helper('buildateam_shippo/carrier')->getShippingAddressFromRateRequest($request);
        $toAddress = $this->prepareToAddress($mageShippingAddress);
        Mage::helper('buildateam_shippo')->log('1 toAddress');
        Mage::helper('buildateam_shippo')->log($toAddress);
        $parcel = $this->prepareParcel($request);

        // example Shipment object
        $shipment = Shippo_Shipment::create(array(
            'object_purpose' => 'PURCHASE',
            'address_from'   => $fromAddress,
            'address_to'     => $toAddress,
            'parcel'         => $parcel,
            'async'          => false
        ));

        // Select the rate you want to purchase.
        // We simply select the first rate in this example.
        //$rate = $shipment["rates_list"][0];

        Mage::helper('buildateam_shippo')->log('4 get rates');
        if ( isset($shipment['rates_list']) ) {
            if ( count($shipment['rates_list']) ) {
                //Mage::log( $shipment['rates_list'] );
                $handling = Mage::helper('buildateam_shippo')->getHandling();
                foreach($shipment['rates_list'] as &$rate)
                {
                    $rate['amount'] += $handling;
                }


                Mage::helper('buildateam_shippo')->log(count($shipment['rates_list']) . ' rates received');


                return $shipment['rates_list'];
            } else {
                Mage::helper('buildateam_shippo')->log('ERROR: NO RATES !!!');
            }
        } else {
            Mage::helper('buildateam_shippo')->log('ERROR: NO RATES !!!');
        }


        return null;
    }

    public function prepareFromAddress()
    {
        $regionId = Mage::helper('buildateam_shippo')->getFromRegion();
        $region = Mage::getModel('directory/region')->load( $regionId );

        $fromAddress = array(
            'object_purpose' => 'PURCHASE',
            'name'    => Mage::helper('buildateam_shippo')->getFromName(),
            'company' => Mage::helper('buildateam_shippo')->getFromCompany(),
            'street1' => Mage::helper('buildateam_shippo')->getFromStreet(),
            'city'    => Mage::helper('buildateam_shippo')->getFromCity(),
            'state'   => $region->getCode(),
            'zip'     => Mage::helper('buildateam_shippo')->getFromPostcode(),
            'country' => Mage::helper('buildateam_shippo')->getFromCountry(),
            'phone'   => Mage::helper('buildateam_shippo')->getFromPhone(),
            'email'   => Mage::helper('buildateam_shippo')->getFromEmail()
        );

        return $fromAddress;
    }

    public function prepareToAddress($mageShippingAddress)
    {
        $street = is_array($mageShippingAddress->getStreet()) ? current($mageShippingAddress->getStreet()) : $mageShippingAddress->getStreet();
        $state = '';
        if ( $regionId = $mageShippingAddress->getRegionId() )
        {
            $region = Mage::getModel('directory/region')->load( $regionId );
            $state = $region->getCode();
        }

        $name = trim($mageShippingAddress->getFirstname() . ' ' . $mageShippingAddress->getLastname());
        $name = empty($name) ? 'Customer' : $name;

        $email = trim($mageShippingAddress->getEmail());
        $email = empty($email) ? 'example@example.com' : $email;

        // example fromAddress
        $toAddress = array(
            'object_purpose' => 'PURCHASE',
            'name'    => $name,
            'company' => $mageShippingAddress->getCompany(),   # optional
            'street1' => $street,
            'city'    => $mageShippingAddress->getCity(),
            'state'   => $state,
            'zip'     => $mageShippingAddress->getPostcode(),
            'country' => $mageShippingAddress->getCountryId(),
            'phone'   => $mageShippingAddress->getTelephone(), # optional
            'email'   => $email,
        );

        return $toAddress;
    }

    protected function _prepareParcel($items)
    {
        $parcel = new Buildateam_Shippo_Model_Parcel();
        //$parcel->add(array(1, 5, 2), 1);
        //$parcel->add(array(2, 4, 3), 2);
        //$parcel->add(array(7, 1, 1), 1);

        foreach ($items as $item) {
            if ($item->getParentItem()) {
                continue;
            }
            if ($item->getProduct()->isVirtual()) {
                continue;
            }

            if ($item->getProduct()->getVisibility() != Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) {
                $product = Mage::getModel('catalog/product')->load( $item->getProduct()->getEntityId() );
                $sDimensions = $product->getData('dimensions_lwh');

                if ( $parcel->validateDimensionsString($sDimensions) ) {
                    $dimensions = $parcel->getDimensionsFromString($sDimensions);

                    Mage::helper('buildateam_shippo')->log('2 Parcel add');
                    Mage::helper('buildateam_shippo')->log('2 qty: ' . $item->getQty());
                    Mage::helper('buildateam_shippo')->log($dimensions);

                    $parcel->add($dimensions, $item->getQty());
                }
            }
        }

        return $parcel;
    }

    public function prepareParcel(Mage_Shipping_Model_Rate_Request $request)
    {
        $parcel = $this->_prepareParcel( $request->getAllItems() );

        $parcel = array(
            'length'        => $parcel->getLength(),
            'width'         => $parcel->getWidth(),
            'height'        => $parcel->getHeight(),
            'distance_unit' => Mage::helper('buildateam_shippo')->getSizeUnit(),
            'weight'        => $request->getData('package_weight'),
            'mass_unit'     => Mage::helper('buildateam_shippo')->getWeightUnit(),
        );

        Mage::helper('buildateam_shippo')->log('3 Parcel');
        Mage::helper('buildateam_shippo')->log($parcel);

        return $parcel;
    }
}