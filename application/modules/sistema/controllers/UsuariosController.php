<?php
class Sistema_UsuariosController extends Zend_Controller_Action{
	
	
	public $log;
	public $configs;
	public $cache;
	public $typePessoa = "1";
	
	
	public function init(){
	
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/');
		}
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();
	
	}
	
	public function indexAction(){
		
		
	
	}
	
	public function novoAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$form = new Sistema_Form_Usuarios();
		$form->cadastro();
		$this->view->form = $form;		
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['id_registro']);
				unset($formData['password2']);
				$departamentos = $formData['departamentos'];
				unset($formData['departamentos']);
				$formData['password'] = md5($formData['password']);
				$formData['passdate'] = date('Y-m-d H:i:s');
				$formData['recoverpass'] = 1;
				try{
					$db = new System_Model_Users();
					$id = $db->insert($formData);
					$soap = new Zend_Soap_Client($this->configs->Leader->SoapServer . "/systemusers?wsdl",array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_2,'location'=> $this->configs->Leader->SoapServer . "/systemusers"));
					$soap->useradd($this->configs->Leader->Cliente->codigo , $this->configs->Leader->SoapKey, $formData);
					$this->log->log("Novo Cadastro de Usuario Efetuado por: {$userInfo->nomecompleto} Cliente: {$formData['nomecompleto']} ",Zend_Log::INFO);
					
					$db2 = new System_Model_UsersDeptos();
					foreach ($departamentos as $key=>$value){
					    $db2->insert(array("id_user"=>$id,"id_depto"=>$value));					    
					}
					
					$this->_redirect("sistema/usuarios/usuario/id/$id");
					
					
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro de usuario {$formData['nomecompleto']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a solicitação:<br>Erro: {$e->getMessage()}",'erro'));
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
	}
	
	public function listarAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$db = new System_Model_Users();
		$dados = $db->fetchAll()->toArray();
		$this->view->dados = $dados;	
		
		
		
	}
	
	public function usuarioAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam("id");
		$db = new System_Model_Users();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$data['departamentos'] = System_Model_UsersDeptos::getUserDeptos($id);
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$form = new Sistema_Form_Usuarios();
		$form->cadastro();
		$form->populate($data);
		$this->view->form = $form;
		
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['password2']);
				$formData['password'] = md5($formData['password']);
				$formData['passdate'] = date('Y-m-d H:i:s');
				$formData['recoverpass'] = 1;
				$departamentos = $formData['departamentos'];
				unset($formData['departamentos']);
				
				try{
					
					$id = $db->update($formData,"id_registro = '{$formData['id_registro']}'");
					$soap = new Zend_Soap_Client($this->configs->Leader->SoapServer . "/systemusers?wsdl",array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_2,'location'=> $this->configs->Leader->SoapServer . "/systemusers"));
					$soap->userupdate($this->configs->Leader->Cliente->codigo , $this->configs->Leader->SoapKey, $formData);
					$this->log->log("Novo Cadastro de Usuario Atualizado por: {$userInfo->nomecompleto} Cliente: {$formData['nomecompleto']} ",Zend_Log::INFO);
					$db2 = new System_Model_UsersDeptos();
					$db2->delete("id_user = '{$formData['id_registro']}'");
					foreach ($departamentos as $key=>$value){
						$db2->insert(array("id_user"=>$id,"id_depto"=>$value));
					}
					
					$this->_redirect("sistema/usuarios/usuario/id/{$formData['id_registro']}");
					
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro de usuario {$formData['nomecompleto']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a solicitação:<br>Erro: {$e->getMessage()}",'erro'));
					print_r($formData);
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
		
		
	}
	
	
	public function removerAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam("id");
		$db = new System_Model_Users();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$this->view->dados = $data;
			
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$form = new Sistema_Form_Usuarios();
		$form->cadastro();
		$form->populate($data);
		$this->view->form = $form;
		
	}
	
	public function confirmRemoverAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam("id");
		$db = new System_Model_Users();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$db->delete("id_registro = '$id'");
		$this->log->log("Cadastro de Usuario Removido por: {$userInfo->nomecompleto} Cliente: {$data['nomecompleto']} ",Zend_Log::INFO);
		$this->_redirect("sistema/usuarios/listar");
			
		
		
	}
	
	
		
	
	
	
}