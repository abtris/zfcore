<?php
/**
 * @package ##PACKAGE##
 * @version ##VERSION##
 */
/**
 * @package ##PACKAGE##
 */ 
class ZFCore_Form_Decorator_Multiselectable extends Zend_Form_Decorator_Abstract
{
    /**
     * render
     * @param  $content
     * @return html
     */
	public function render($content)
	{

		$view = $this->getElement()->getView();
		$view->headScript()
			->appendFile($view->baseUrl().'/js/multiselectable.js')
			->appendScript(
		 		'$(document).ready(function() {' .
					'$(\'.'.$this->getElement()->getAttrib('class').'\').multiselectable();' .
		        '}); '
		 	);
		$view->headLink()->appendStylesheet($view->baseUrl().'/css/multiselectable.css');
		return $content;
	}

}
