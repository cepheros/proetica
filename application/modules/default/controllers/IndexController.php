<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout()->setLayout('layoutpages');
    }

    public function indexAction()
    {
        // action body
     //   echo "MErda";
    }
    
    public function cadastroAction(){
        
    }
    
       public function logoutAction() {
			$auth = Zend_Auth::getInstance();
			$auth->clearIdentity();
    	$this->_redirect('/default/index');
		}
		
		public function denyAction(){
			if($userInfo = Zend_Auth::getInstance()->getStorage()->read()){
				$log = Zend_Registry::get('log');
				$configs = Zend_Registry::get('configs');
				$this->view->SysName = $configs->Leader->SysName;
			}else{
				$log = Zend_Registry::get('log');
				$configs = Zend_Registry::get('configs');
				$agent = getenv("HTTP_USER_AGENT");
				if (preg_match("/MSIE/i", $agent)) {
					$this->_redirect('/default/index/browser-error');
						
				}
				$this->_helper->layout()->setLayout('layoutpages');
				$this->_helper->viewRenderer->setRender('deny');
				$this->view->SysName = $configs->Leader->SysName;
				$this->view->LoginError = true;
			}
		
		}


}

