<?php
class Pages_Model_Content_Basicos extends Zend_Db_Table_Abstract
{

    protected $_name = 'tblpages_basicos';
    protected $_primary = 'id_registro';
    
    

    public function getCombo(){
    	$dados = $this->fetchAll()->toArray();
    	$rdepto[''] = '- Selecione -';
    	foreach($dados as $depto){
    		$rdepto[$depto['id_registro']] = $depto['nomequiz'];
    	}
    	return $rdepto;
    
    }
    
  
    
    public static function getNome($id){
    	$db = new Pages_Model_Content_Basicos();
    	$dados = $db->fetchRow("id_registro = '$id'");
    	return $dados->titulopagina;
    }
    
    
    public static function renderCombo(){
    	$db = new Pages_Model_Content_Basicos();
    	$dados = $db->fetchAll()->toArray();
    	$rdepto[''] = '- Selecione -';
    	foreach($dados as $depto){
    		$rdepto[$depto['id_registro']] = $depto['titulopagina'];
    	}
    	return $rdepto;
    
    }
    
}