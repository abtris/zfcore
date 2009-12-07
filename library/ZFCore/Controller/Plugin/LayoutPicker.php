<?php
/**
 * @version ##VERSION##
 * @package ##PACKAGE##
 */
/**
 * Description of LayoutPicker
 * @package ##PACKAGE##
 */
class ZFCore_Controller_Plugin_LayoutPicker extends Zend_Controller_Plugin_Abstract
{
    /**
     * preDispatch
     * @param $request Zend_Controller_Request_Abstract
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
	    Zend_Layout::getMvcInstance()->setLayout($request->getModuleName());
    }

}
