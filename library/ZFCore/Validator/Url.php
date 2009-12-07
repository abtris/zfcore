<?php
/**
 * @package ##PACKAGE##
 * @version ##VERSION##
 */
/**
 * include Zend_Validate_Abtract
 */
require_once 'Zend/Validate/Abstract.php';
/**
 * Description of UrlValidator   
 * @package ##PACKAGE##
 */
class ZFCore_Validator_Url extends Zend_Validate_Abstract {
    //put your code here
    const INVALID = 'invalid';
    const INVALID_HOSTNAME = 'invalid hostname';
    /**
     * messages
     */
    protected $_messageTemplates = array(
        self::INVALID   => "'%value%' is not a valid URL.",
        self::INVALID_HOSTNAME => "'%value%' is not a valid HOSTNAME in URL",
    );
    /**
     * Valid?
     * @param $value
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        $uri = Zend_Uri::factory($value);
        $uriSchema = $uri->getScheme();

        try {
            $uri->valid();
        } catch (Exception $e) {
            $this->_error(self::INVALID);
            return false;
        }
        
        $validatorHostname = new Zend_Validate_Hostname(
                array(
                    'allow' => Zend_Validate_Hostname::ALLOW_DNS,
                    'idn'   => true,
                    'tld'   => false
                )
            );

        $protocol =  !empty($uriSchema) ? $uriSchema.'://': '';
        $urlHostname = str_replace($protocol, "", $value);
        if (!$validatorHostname->isValid($urlHostname)) {
            $this->_error(self::INVALID_HOSTNAME);
            return false;
        }
        
        return true;
    }
}

