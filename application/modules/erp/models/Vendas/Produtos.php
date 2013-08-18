<?php
class Erp_Model_Vendas_Produtos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblvendas_produtos';
	protected $_primary = 'id_registro';

	/**
	 * Total Pedido
	 * @param int $id ID do pedido 
	 * @param number $format, numero de casas decimais
	 * @return string
	 */
	public static function totalPedido($id, $format = 2){
		$dados = new Erp_Model_Vendas_Produtos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblvendas_produtos'),array('SUM(a.vl_total) as quantidade'))
		->where("id_venda = '$id'");
			
		$rs = $return->query();
	
		$dados = $rs->fetchAll();
	
		$estoqueatual = $dados[0]['quantidade'];
		if(!$estoqueatual){
			$estoqueatual = '0';
		}
	
		if($format > 0){
			$estoqueatual = number_format($estoqueatual,$format,',','');
		}
	
		return $estoqueatual;
	}
	
}