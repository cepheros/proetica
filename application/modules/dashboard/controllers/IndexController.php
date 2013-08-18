<?php
class Dashboard_IndexController extends Zend_Controller_Action{
	
	public $log;
	public $configs;
	public $cache;
	
	
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
		$this->_helper->layout()->setLayout('dashboard');
		
	}
	
	
	public function indexAction(){
		$message = '';
		
		$this->view->UserInfo = Zend_Auth::getInstance()->getStorage()->read();
		
		$db = new System_Model_Empresas();
		$dadosempresas = $db->fetchAll();
		$dbnf = new System_Model_EmpresasNF();
		if($dadosempresas){
			foreach($dadosempresas as $empresa){
				$dadosnf = $dbnf->fetchRow("id_empresa = '{$empresa->id_registro}'");
				if($dadosnf->validadecertificado){
				$validade = Functions_Datas::MyDateTime($dadosnf->validadecertificado);
				$time1 = strtotime(date('Y-m-d'));
				$time2 = strtotime($dadosnf->validadecertificado);
				if($time2 < $time1){
					$message[] = Functions_Messages::renderAlert("<strong>O Certificado Digital da empresa {$empresa->nomefantasia} esta vencido, providencie a renovação</strong>",'erro');
				}else{
								
					$Val2 = strtotime(Functions_Datas::inverteData(Functions_Datas::SubtraiData($validade, 45)));
					if($Val2 <= $time1){
						$message[] = Functions_Messages::renderAlert("<strong>O Certificado Digital da empresa {$empresa->nomefantasia} vai vencer em {$validade}, providencie a renovação</strong>");
					}
				}
				}else{
					$message[] = Functions_Messages::renderAlert("<strong>A empresa {$empresa->nomefantasia} não possui um certificado digital para emissão de Notas Fiscais</strong>",'erro');
				}
			}
			
		}elseif(!$dadosempresas->toArray()){
			$message[] = Functions_Messages::renderAlert("<strong>Voce deve cadastrar uma empresa no sistema!</strong>",'erro');
		}
		if(!System_Model_Empresas::getEmpresaPadrao()){
			$message[] = Functions_Messages::renderAlert("<strong><a href=\"/sistema/empresas\">Voce deve cadastrar uma empresa no sistema e torna-la padrão!</a></strong>",'erro');
		}
		
		
		$this->view->AlertSysMessage = $message;
		
	}
	

	
}