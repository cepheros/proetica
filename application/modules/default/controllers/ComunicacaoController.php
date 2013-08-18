<?php
class ComunicacaoController extends Zend_Controller_Action
{

	public function init()
	{
	    $this->_helper->layout()->setLayout('layoutpages');
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		// action body
		//   echo "MErda";
	}

	public function padraoAction(){
	    $db = new Default_Model_TiposChamados();
	    $data = $db->fetchAll("tpchamado = '1'");
	    $this->view->tipos = $data;
	    
	}
	
	public function ambientalAction(){
	    $this->_helper->layout()->setLayout('layoutgreen');
	    
	    $db = new Default_Model_TiposChamados();
	    $data = $db->fetchAll("tpchamado = '2'");
	    $this->view->tipos = $data;
	    
	}
	
	public function criarAction(){
	    $erro = $this->_getParam('erro');
	    
	    if($erro == 'check'){
	        $this->view->erro = true;
	        $id = $this->_getParam('id');
	        
	        $db = new Default_Model_TiposChamados();
	        $data = $db->fetchRow("id_registro = '$id'");
	        if($data->tpchamado == '2'){
	        	$this->_helper->layout()->setLayout('layoutgreen');
	        }
	        $this->view->data = $data;
	        
	        
	    }
	    
	    
	    if ($this->_request->isPost()) {
	    	$form = $this->_request->getPost();
	    	
	    	$db = new Default_Model_TiposChamados();
	    	$data = $db->fetchRow("id_registro = '{$form['idchamado']}'");
	    	if($data->tpchamado == '2'){
	    	    $this->_helper->layout()->setLayout('layoutgreen');
	    	}
	    	$this->view->data = $data;
	    	
	    }
	    
	}
	
	public function novoAction(){
	    
	    if ($this->_request->isPost()) {
	    	$form = $this->_request->getPost();
	    
	    	$db = new Default_Model_TiposChamados();
	    	$data = $db->fetchRow("id_registro = '{$form['idchamado']}'");
	    	$this->view->data = $data;
	    	$this->view->id_empresa = $form['id_empresa'];
	    	if($data->tpchamado == '2'){
	    		$this->_helper->layout()->setLayout('layoutgreen');
	    	}
	    	
	    	if($form['checktermos'] <> '1'){
	    	    $this->_redirect("/default/comunicacao/criar/erro/check/id/{$data->id_registro}");
	    	}
	    
	    }
	    
	}
	
	public function salvarAction(){
	    if ($this->_request->isPost()) {
	    	$form = $this->_request->getPost();
	    	 
	    	$db = new Default_Model_TiposChamados();
	    	$data = $db->fetchRow("id_registro = '{$form['id_chamado']}'");
	    	$this->view->data = $data;
	    	$this->view->id_empresa = $form['id_empresa'];
	    	if($data->tpchamado == '2'){
	    		$this->_helper->layout()->setLayout('layoutgreen');
	    	}
	    	
	    	$password = Functions_Tickets::gerPassword();
	    	$datasave = new Crm_Model_Chamados_Abertura();
	    	$form['dataabertura'] = date('Y-m-d H:i:s');
	    	$form['protocolo'] = Functions_Tickets::gerenateProtocol();
	    	$form['password'] = $password;
	    	$form['hashpwd'] = md5($password);
	       	$id = $datasave->insert($form);
	       	
	       	$this->view->form = $form;
	       	$this->view->password = $password;
	    	
	    
	    	
	    	
	    	
	    
	    	}
	    	 
	    }
	    
	    
	    public function consultaAction(){
	        
	        
	        if ($this->_request->isPost()) {
	        	$form = $this->_request->getPost();
	        	$this->view->consulta = true;
	        	$db = new Crm_Model_Chamados_Abertura();
	        	$pwd = md5(strtoupper($form['senha']));
	        	$data = $db->fetchRow("protocolo = '{$form['protocolo']}' and hashpwd = '$pwd' ");
	        	if($data){
	        	    $this->view->data = $data;
	        	    
	        	}else{
	        	    $this->view->info = "Protocolo não localizado";
	        	}
	        	
	        	
	        }
	        
	    }


}