<?php 
class Pages_ContentController extends Zend_Controller_Action{


	public $log;
	public $configs;
	public $cache;
	public $userInfo;
	
	public function init(){
	
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/');
		}
		
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
		$this->userInfo = Zend_Auth::getInstance()->getStorage()->read();
	
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();
	
	}
	
	public function indexAction(){
	    
	}
	
	
	public function aboutAction(){
	    $db = new Pages_Model_Content_Basicos();
	    $data = $db->fetchRow("page = 'about'")->toArray();
	    $form = new Pages_Form_Paginas();
	    $form->page();
	    $form->populate($data);
	    $this->view->form = $form;
	    if ($this->_request->isPost()) {
	    	$formdata = $this->_request->getPost();
	    	if ($form->isValid($formdata)) {
        		unset($formdata['submit']);
        		$db->update($formdata, "id_registro = '{$formdata['id_registro']}'");
        		$messages[] = Functions_Messages::renderAlert("Página Atualizada com Sucesso",'info');
	    	}
	    }
	        
	    $this->view->AlertMessage = $messages;
	}
	
		
	
}