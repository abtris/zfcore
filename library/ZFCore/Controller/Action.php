<?php
/**
 * ZFCore_Controller_Action
 * @version ##VERSION##
 * @package ##PACKAGE##
 */
/**
 * Controller
 * @package ##PACKAGE##
 * @author prskavecl
 */
class ZFCore_Controller_Action extends Zend_Controller_Action {
    
    public function init()
    {
      	// Logger
        $registry = Zend_Registry::getInstance();
        $this->logger = $registry->get('logger');
        $this->logger->setEventItem('module', $this->getRequest()->getModuleName());
        $this->logger->setEventItem('controller', $this->getRequest()->getControllerName());
        $this->loggerApp = $registry->get('loggerApp');
        $this->loggerApp->setEventItem('controller', $this->getRequest()->getControllerName());
        $this->loggerApp->setEventItem('username', isset($_SESSION['userData']['username']) ? $_SESSION['userData']['username'] : "anonym");
        // Flashmessenger
        $this->_flashMessenger = $this->_helper->FlashMessenger;
        // Gettext
        $this->t = Zend_Registry::get('Zend_Translate');
        $this->view->t = $this->t;

    }

    public function postDispatch()
    {
        // Flashmessenger
        $this->view->messages = $this->_flashMessenger->getMessages();
    }
}
