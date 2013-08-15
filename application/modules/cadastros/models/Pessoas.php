<?php
class Cadastros_Model_Pessoas extends Zend_Db_Table_Abstract{
	protected $_name = 'tblpessoas_basicos';
	protected $_primary = 'id_registro';
	

	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomefantasia'];
		}
		return $rdepto;

	}

	
	public static function getNomeEmpresa($id){
		$db = new Cadastros_Model_Pessoas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->razaosocial;
	}


	public static function renderCombo($tipo = false,$idcliente = false){
		$db = new Cadastros_Model_Pessoas();
		if(!$tipo){
		$dados = $db->fetchAll()->toArray();
		}elseif($tipo){
			$dados = $db->fetchAll("tipocadastro = '$tipo'")->toArray();
		}elseif($idcliente){
			$dados = $db->fetchAll("id_pessoa = '$id_cliente'")->toArray();
		}
		
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomefantasia'];
		}
		return $rdepto;

	}
	
	
	public static function getCadastro($id){
		$db = new Cadastros_Model_Pessoas();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		$dados['dataabertura'] = Functions_Datas::MyDateTime($dados['dataabertura']);
		return $dados;
		
	}
	
	



}
