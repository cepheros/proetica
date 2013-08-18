<?php 
class Pages_QuizController extends Zend_Controller_Action{


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
	
	public function novoAction(){
	    $form = new Pages_Form_Quiz();
	    $form->basicos();
	    $this->view->form = $form;
	    
	    if ($this->_request->isPost()) {
	    	$formdata = $this->_request->getPost();
	    	if ($form->isValid($formdata)) {
	    	      	    
	    	
	    	    unset($formdata['submit']);
	    	    $db = new Pages_Model_Quiz_Basicos();
	    	    $id = $db->insert($formdata);
	    	    $this->log->log("Novo Quiz: {$this->userInfo->nomecompleto} ID: {$id} ",Zend_Log::INFO);
	    	    $this->_redirect("/pages/quiz/abrir/id/$id/");
	    	    
	    	    }else{
	    	    
	    	    	$error =  $form->getMessages();
	    	    	$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');
	    	    
	    	    	foreach($error as $erro){
	    	    		$messages[] = Functions_Messages::renderAlert($erro[0],'info');
	    	    	}
	    	    	
	    	    
	    	    	$this->view->AlertMessage = $messages;
	    	    	$form->populate($formdata);
	    	    
	    	    
	    	    }
	    	    	
	    	    }
	    	    
	    	
	}
	
	public function abrirAction(){
	    $id = $this->_getParam("id");
	    $db = new Pages_Model_Quiz_Basicos();
	    $dados = $db->fetchRow("id_registro = '$id'")->toArray();
	     $form = new Pages_Form_Quiz();
	    $form->basicos();
	    $form->populate($dados);
	    $this->view->form = $form;
	    
	    if ($this->_request->isPost()) {
	    	$formdata = $this->_request->getPost();
	    	if ($form->isValid($formdata)) {
	    		 
	    
	    		unset($formdata['submit']);
	    		$db = new Pages_Model_Quiz_Basicos();
	    		$id = $db->update($formdata,"id_registro = '{$formdata['id_registro']}'");
	    		$this->log->log("Novo Quiz: {$this->userInfo->nomecompleto} ID: {$id} ",Zend_Log::INFO);
	    		$messages[] = Functions_Messages::renderAlert("Cadastro Editado",'success');
	    
	    	}else{
	    
	    		$error =  $form->getMessages();
	    		$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');
	    
	    		foreach($error as $erro){
	    			$messages[] = Functions_Messages::renderAlert($erro[0],'info');
	    		}
	    		   		
	    
	    		$this->view->AlertMessage = $messages;
	    		$form->populate($formdata);
	    
	    
	    	}
	    		
	    }
	    
	}
	
	
	public function deletarAction(){
	    
	    $id = $this->_getParam("id");
	    $db = new Pages_Model_Quiz_Basicos();
	    $dados = $db->fetchRow("id_registro = '$id'")->toArray();
	    $form = new Pages_Form_Quiz();
	    $form->basicos();
	    $form->populate($dados);
	    $this->view->form = $form;
	     
	    if ($this->_request->isPost()) {
	    	$formdata = $this->_request->getPost();
	    	if ($form->isValid($formdata)) {
	    
	    	  
	    		unset($formdata['submit']);
	    		$db = new Pages_Model_Quiz_Basicos();
	    		$db->delete("id_registro = '{$formdata['id_registro']}'");
	    		$this->_redirect("/pages/quiz/listar");	    	  
	    	}else{
	    	  
	    		$error =  $form->getMessages();
	    		$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');
	    	  
	    		foreach($error as $erro){
	    			$messages[] = Functions_Messages::renderAlert($erro[0],'info');
	    		}
	    
	    	  
	    		$this->view->AlertMessage = $messages;
	    		$form->populate($formdata);
	    	  
	    	  
	    	}
	    	 
	    }
	    
	   
	    
	}
	
	
	public function listarAction(){
	    $db = new Pages_Model_Quiz_Basicos();
	    $dados = $db->fetchAll();
	    $this->view->dados = $dados;
	    
	}
	
	
	public function addQuestionAction(){
	    $id_quiz = $this->_getParam("quiz");
	    $nomequiz = Pages_Model_Quiz_Basicos::getNome($id_quiz);
	    $db = new Pages_Model_Quiz_Perguntas();
	    $dados = $db->fetchAll("id_quiz = '$id_quiz'");
	    $this->view->id_quiz = $id_quiz;
	    $this->view->nomequiz = $nomequiz;
	    $this->view->perquntas = $dados;
	    
	    $dadosquiz = array('id_quiz'=>$id_quiz);	    
	    $form = new Pages_Form_Quiz();
	    $form->pergunta();
	    $form->populate($dadosquiz);
	    $this->view->form = $form;
	    
	    
	    
	}
	
	public function getquestionAction(){
	    $this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender();
	    
	    $id = $this->_getParam("id");
	    $db = new Pages_Model_Quiz_Perguntas();
	    $dados = $db->fetchRow("id_registro = '$id'")->toArray();
	    
	    
	    $db2 = new Pages_Model_Quiz_Respostas();
	    $dadosresp = $db2->fetchAll("id_pergunta = '$id'")->toArray();
	    
	    $dados['respostas'] = $dadosresp;
	    
	    echo json_encode($dados);
   
	}
	
	
	
	public function salvaPerguntaAction(){
	    $this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender();
	    if ($this->_request->isPost()) {
	    	$formdata = $this->_request->getPost();
	    	$db = new Pages_Model_Quiz_Perguntas();
	    	$db2 = new Pages_Model_Quiz_Respostas();
	    	
	    	if($formdata['id_registro'] == 0 || $formdata['id_registro'] == ''){
	    	    $savedata['id_quiz'] = $formdata['id_quiz'];
	    	    $savedata['pergunta'] = $formdata['pergunta'];
	    	    $savedata['msg_correto'] = $formdata['msg_correto'];
	    	    $id_pergunta = $db->insert($savedata);
	    	    for ($i=0;$i<=count($formdata['resposta']);$i++){
	    	        if($formdata['resposta'][$i] <> ''){
	    	            $saveq['id_pergunta'] = $id_pergunta;
	    	    		$saveq['resposta'] = $formdata['resposta'][$i];
	    	    		$saveq['correct'] = $formdata['correta'][$i];
	    	            $db2->insert($saveq);
	    	    		$saveq = '';
	    	    	}
	    	        }
	    	    }
	    	    
	    	}else{
	    	    $savedata['id_quiz'] = $formdata['id_quiz'];
	    	    $savedata['pergunta'] = $formdata['pergunta'];
	    	    $savedata['msg_correto'] = $formdata['msg_correto'];
	    	    $id_pergunta = $formdata['id_registro'];
	    	    $db->update($savedata,"id_registro = '{$formdata['id_registro']}'");
	    	    $db2->delete("id_pergunta = '{$formdata['id_registro']}'");

	    	    for ($i=0;$i<=count($formdata['resposta']);$i++){
	    	    	if($formdata['resposta'][$i] <> ''){
	    	    		$saveq['id_pergunta'] = $id_pergunta;
	    	    		$saveq['resposta'] = $formdata['resposta'][$i];
	    	    		$saveq['correct'] = $formdata['correta'][$i];
	    	            $db2->insert($saveq);
	    	    		$saveq = '';
	    	    	}
	    	    }
	    	    
	    	}
	    	
	    	$this->_redirect("/pages/quiz/add-question/quiz/{$formdata['id_quiz']}");
	}
	    
	    

	
	}
