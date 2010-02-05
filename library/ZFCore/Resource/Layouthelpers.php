<?php
/**
 *  @version ##VERSION##
 *  @package ##PACKAGE##
 *
 */
/**
 * @package ##PACKAGE##
 * 
 * Layout helper
 *
 * [production]
 * ; ...
 * pluginPaths.ZFCore_Resource = "ZFCore/Resource"
 * resources.layouthelpers.doctype = "HTML5"
 * resources.layouthelpers.title = "New Website"
 * resources.layouthelpers.title_separator = " &emdash; "
 */
class ZFCore_Resource_Layouthelpers 
    extends Zend_Application_Resource_ResourceAbstract
    {
	/**
	 *  @var array $_options
	 */
        protected $_options = array(
                'doctype'         => 'XHTML1_STRICT',
                'title'           => 'Site Title',
                'title_separator' => ' | ',
                );
	/**
	 * init
	 */
	public function init()
        {
                $bootstrap = $this->getBootstrap();
                $bootstrap->bootstrap('View');
                $view = $bootstrap->getResource('View');

		$options = $this->getOptions();
                                                               
		$view->doctype($options['doctype']);
                $view->headTitle()->setSeparator($options['title_separator'])
                                  ->append($options['title']);
                                                                                                              
	}
    }
