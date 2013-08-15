<?php
class ContatoController extends Zend_Controller_Action
{

	public function init()
	{
	    $this->_helper->layout()->setLayout('layoutpages');
		/* Initialize action controller here */
	    $this->log = Zend_Registry::get('log');
	    $this->configs = Zend_Registry::get('configs');
	    $this->cache = Zend_Registry::get('cache');
	}

	public function indexAction()
	{
		// action body
		//   echo "MErda";
		
	    
	    
	    if ($this->_request->isPost()) {
	    	$form = $this->_request->getPost();
	    	if(!$form['atribuidoa']){
	    		$form['atribuidoa'] = '0';
	    	}
	    	
	    	$protocolo = Functions_Tickets::gerenateProtocol();
	    		
	    	$dados['protocolo'] = $protocolo;
	    	$dados['id_empresa'] = "1";
	    	$dados['solicitante'] = '0';
	    	$dados['nomesolicitante'] = $form['txtName'];
	    	$dados['emailsolicitante'] = $form['txtEmail'];
	    	$dados['celularsolicitante'] = '';
	    	$dados['departamento'] = Crm_Model_TicketsDeptos::getRegistroPadrao();
	    	$dados['atribuidoa'] = '0';
	    	$dados['tipoticket'] = '1';
	    	$dados['prioridadeticket'] = '2';
	    	$dados['assuntoticket'] = $form['txtSubject'];
	    	$dados['dadosticket'] = $form['txtText'];
	    	$dados['dateopen'] = date("Y-m-d H:i:s");
	    	$dados['datefirstreply'] =  null;
	    	$dados['datelastreply'] = null;
	    	$dados['dateclosed'] = null;
	    	$dados['datereopen'] = null;
	    	$dados['statusticket'] = '1';
	    	$dados['staffopen'] = '0';
	    	$dados['stafffirstreply'] = 0;
	    	$dados['stafflastreply'] = 0;
	    	$dados['flagticket'] = 0;
	    	$dados['datedue'] = Functions_Datas::inverteData(Functions_Datas::SomaData(date('d/m/Y'), Crm_Model_TicketsPrioridades::getDueTipo($form['prioridadeticket'])));
	    	$tickets = new Crm_Model_TicketsBasicos();
	    	$id = $tickets->insert($dados);
	    	$this->log->log("Ticket Criado {$form['protocolo']}, usuário: {$userInfo->nomecompleto} ",Zend_Log::INFO);
	    		
	    
	    		$this->log->log("Enviando Email",Zend_Log::INFO);
	    		$mail = new Functions_Tickets;
	    		$mail->sendMail($id);
	    		
	    		$this->view->protocol = $protocolo;
	    		$this->view->send = true; 
	    
	    }
	       		
	    
	}


}

