<?php
class Soap_Cadastro{
	
	
	public $configs;
	
	public function __construct(){
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
	
	}
	
	/**
	 * Solicita os dados de cadastro de um id
	 *
	 * @param string $client Codigo do cliente leader
	 * @param string $code Codigo de Acesso a API
	 * @param int $id ID do cadastro que deve ser pesquisado
	 * @return array $dados Dados do Cadastro localizado
	 */
	
	public function getCadastro($client,$code,$id){
		$cliente = new Cadastros_Model_Outros();
		$dados = $cliente->fetchRow("id_pessoa = '$client'");
		if($dados->chavesoap <> $code){
			return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
		}else{
			$db = new Cadastros_Model_Pessoas();
			$dados = $db->fetchRow("id_registro = '$id'")->toArray();
			return $dados;
		}
		
	}
	
	
	
	
}