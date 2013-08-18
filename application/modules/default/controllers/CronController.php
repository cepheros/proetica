<?php

class CronController extends Zend_Controller_Action{
	
	public function init(){
	
	
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
		$this->view->parameters = $this->_request->getParams();
	
		$this->DocsPath = $this->configs->SYS->DocsPath;
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
	}
	
	
	public function indexAction(){
		$cron = new Functions_Cron;
		$cron->emailTicketCron();
		$cron->osMailCron();
	}
	
	
	
	
}