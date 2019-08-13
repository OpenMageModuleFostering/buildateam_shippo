<?php

class Buildateam_Shippo_Model_Parcel extends Varien_Object
{
    protected $_dimensions = array(0, 0, 0);  // Height,  Width,  Length

    public function add(array $dimensions, $qty)
    {
        if ( $this->validateDimensionsArray($dimensions) ) {
            sort($dimensions);                // 1  5  9
            sort($this->_dimensions);

            $this->_dimensions[2]= max($this->_dimensions[2], $dimensions[2]);       // Length
            $this->_dimensions[1]= max($this->_dimensions[1], $dimensions[1]);       // Width
            $this->_dimensions[0]= $this->_dimensions[0] + ($dimensions[0] * $qty);  // Height

        } else {
            throw new Exception('Dimensions are invalid: ' . print_r($dimensions, 1));
        }
    }

    public function validateDimensionsString($str)
    {
        $pattern = '/([\.\d])+x([\.\d])+x([\.\d])+/';
        $result = preg_match($pattern, $str, $match);
        if ( $match )
        if ( count($match) ) {
            $sDimensions = $match[0];
            $aDimensions = explode('x', $sDimensions);

            $isValid = $this->validateDimensionsArray($aDimensions);
            return $isValid;
        }

        return false;
    }

    public function validateDimensionsArray(array $aDimensions)
    {
        if ( is_array($aDimensions) )
        if ( count($aDimensions) == 3 )
        if ( @array_walk($aDimensions, 'is_numeric') ) {

            return true;
        }

        return false;
    }

    public function getDimensionsFromString($str)
    {
        $dimensions = explode('x', $str);
        $dimensions = array_map('floatval', $dimensions);
        return $dimensions;
    }

    public function getLength()
    {
        return $this->_dimensions[2];
    }

    public function getWidth()
    {
        return $this->_dimensions[1];
    }

    public function getHeight()
    {
        return $this->_dimensions[0];
    }
}