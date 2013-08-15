<?php
class Functions_Email{
	
	protected $mail;
	public $log;
	public $configs;
	public $cache;
	public $userInfo;
	public $baseURL;
	
	
	public function __construct(){
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
		$this->userInfo = Zend_Auth::getInstance()->getStorage()->read();	

		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		$this->baseURL = $protocol . "://" . $_SERVER['HTTP_HOST'];
		
	}
	
	
	public function sendMail($to,$message,$subject,$from = false){
	//	print_r($to);
		if(!$from){
			$from['deptomailserver'] = $this->configs->Mail->Server;
			$from['deptoemail']= $this->configs->Mail->User;
			$from['deptomailpassword'] = $this->configs->Mail->Pass;
			$from['deptomailserverport'] = $this->configs->Mail->Port;		
			$from['nomedepto'] = System_Model_Empresas::getNomeFantasiaEmpresa(System_Model_Empresas::getEmpresaPadrao());
		}
		
		$transport = new Zend_Mail_Transport_Smtp($from['deptomailserver'],array(
				'auth'=>'login',
				'username'=>$from['deptoemail'],
				'password'=>$from['deptomailpassword'],
				'port'=>$from['deptomailserverport']
		));
		$mail = new Zend_Mail();
		$mail->setBodyHtml(utf8_decode($message));
		$mail->setBodyText(strip_tags(utf8_decode($message)));
		$mail->setFrom($from['deptoemail'],$from['nomedepto'])
		->addTo($to['email'],$to['nomecontato'])
		->setSubject(utf8_decode($subject));
		$mail->send($transport);
		
	}
			
		
		
	public static function mailNewOs($id){
		$mail = new Functions_Email;
		$osdata = new Crm_Model_Os_Basicos();
		$dadosOS = $osdata->fetchRow("id_registro = '$id'");
		$message = System_Model_MensagensSistema::getMensagem(System_Model_SysConfigs::getConfig("DefaultMailNewOS"));
		$replaces = array('{CODIGO_OS}',
						  '{CLIENTE_OS}',
						  '{CONTATO_OS}',
						  '{ABERTURA_OS}',
						  '{LINK_CONSULTA}',
						  '{USUARIO_ABERTURA}',
						  '{STATUS_OS}',
						  '{TIPO_OS}',
						  '{EMPRESA}'
						  		
						   );
		$toReplate = array($dadosOS->cod_os,
				           Cadastros_Model_Pessoas::getNomeEmpresa($dadosOS->id_cliente),
						   Cadastros_Model_Contatos::getNomeContato($dadosOS->id_contato),
						   Functions_Datas::MyDateTime($dadosOS->dataabertura,true),
						   $mail->baseURL .'/consulta/os/cod/'.$dadosOS->accesshash,
				           System_Model_Users::whoIs($dadosOS->user_open),
						   Crm_Model_Os_Status::getNomeTipo($dadosOS->status_os),
						   Crm_Model_Os_Tipos::getNomeTipo($dadosOS->tipo_os),
						   System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa)
						   );
		
		$sendMessage = str_replace($replaces, $toReplate, $message->htmlmensagem);
		$sendto = Cadastros_Model_Contatos::getCadastro($dadosOS->id_contato);
		
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		
		$from['deptomailserver'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServer");
		$from['deptoemail']=  System_Model_SysConfigs::getConfig("SysOSDefaultMailUser");
		$from['deptomailpassword'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailPassword");
		$from['deptomailserverport'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServerSendPort");
		$from['nomedepto'] = System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa);
		
		$mail->sendMail($sendto,$sendMessage,$subject,$from);
		
						
			
		
	}
	
	
	public static function mailUpdateOs($id){
		$mail = new Functions_Email;
		$osdata = new Crm_Model_Os_Basicos();
		$dadosOS = $osdata->fetchRow("id_registro = '$id'");
		$message = System_Model_MensagensSistema::getMensagem(System_Model_SysConfigs::getConfig("DefaultMailUpdateOS"));
		$replaces = array('{CODIGO_OS}',
				'{CLIENTE_OS}',
				'{CONTATO_OS}',
				'{ABERTURA_OS}',
				'{LINK_CONSULTA}',
				'{USUARIO_ABERTURA}',
				'{STATUS_OS}',
				'{TIPO_OS}',
				'{EMPRESA}',
				'{USUARIO_ATUALIZA}',
				'{DATA_ATUALIZACAO}'
				
	
		);
		$toReplate = array($dadosOS->cod_os,
				Cadastros_Model_Pessoas::getNomeEmpresa($dadosOS->id_cliente),
				Cadastros_Model_Contatos::getNomeContato($dadosOS->id_contato),
				Functions_Datas::MyDateTime($dadosOS->dataabertura,true),
				$mail->baseURL .'/consulta/os/cod/'.$dadosOS->accesshash,
				System_Model_Users::whoIs($dadosOS->user_open),
				Crm_Model_Os_Status::getNomeTipo($dadosOS->status_os),
				Crm_Model_Os_Tipos::getNomeTipo($dadosOS->tipo_os),
				System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa),
				System_Model_Users::whoIs($dadosOS->user_lastupdate),
				Functions_Datas::MyDateTime($dadosOS->datelastupdate,true),
		);
	
		$sendMessage = str_replace($replaces, $toReplate, $message->htmlmensagem);
		$sendto = Cadastros_Model_Contatos::getCadastro($dadosOS->id_contato);
	
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		
		
		$from['deptomailserver'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServer");
		$from['deptoemail']=  System_Model_SysConfigs::getConfig("SysOSDefaultMailUser");
		$from['deptomailpassword'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailPassword");
		$from['deptomailserverport'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServerSendPort");
		$from['nomedepto'] = System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa);
	
		$mail->sendMail($sendto,$sendMessage,$subject,$from);
	
	
			
	
	}
	
	
}