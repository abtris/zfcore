<?php
/**
 * @package ##PACKAGE##
 * @version ##VERSION## 
 */
/**
 * @package ##PACKAGE##
 */
class ZFCore_Controller_Plugin_Ssl extends Zend_Controller_Plugin_Abstract 
{
        /**
         * Check the application.ini file for security settings.
         * If the url requires being secured, r ebuild a secure url
         * and redirect.
         *
         * @param Zend_Controller_Request_Abstract $request
         * @return void
         * @author Travis Boudreaux
         */
        public function preDispatch(Zend_Controller_Request_Abstract $request) 
		{

                $shouldSecureUrl = false;
                $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
                $this->options = $bootstrap->getOption('modules');

                if (APPLICATION_ENVIRONMENT == ENV_PRODUCTION ) {

                        //check configuration file for one of three require_ssl directives
                        //secure an entire module with modules.module_name.require_ssl = true
                        //secure an entire controller with modules.module_name.controller_name.require_ssl = true
                        //secure an action with modules.module_name.controller_name.action_name.require_ssl = true
                        if ($this->options['modules'][$request->module]['require_ssl'] ||
                                        $this->options['modules'][$request->module][$request->controller]['require_ssl'] ||
                                        $this->options['modules'][$request->module][$request->controller][$request->action]['require_ssl'] ){

                                $shouldSecureUrl = true;

                        }

                        if ($shouldSecureUrl)   {

                                $this->_secureUrl($request);

                        }
                }
        }

        /**
         * Check the request to see if it is secure.  If it isn't
         * rebuild a secure url, redirect and exit.
         *
         * @param Zend_Controller_Request_Abstract $request
         * @return void
         * @author Travis Boudreaux
         */
        protected function _secureUrl( Zend_Controller_Request_Abstract $request)
		{

                $server = $request->getServer();
                $hostname = $server['HTTP_HOST'];

                if (!$request->isSecure()) {
                        //url scheme is not secure so we rebuild url with secureScheme
                        $url = Zend_Controller_Request_Http::SCHEME_HTTPS . "://" . $hostname . $request->getPathInfo();

                        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
						$redirector->setGoToUrl($url);
						$redirector->redirectAndExit();
				}
        }
}
