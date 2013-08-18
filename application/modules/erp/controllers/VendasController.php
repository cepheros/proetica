<?php
class Erp_VendasController extends Zend_Controller_Action{

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
		$this->view->ultimosPedidos = Erp_Model_Vendas_Basicos::getLastPedidos(9);
	
	}
	
	public function novoPedidoAction(){
		$form = new Erp_Form_Vendas();
		$form->basicos();
		$form->populate(array('tipo_inspecao'=>Erp_Model_Vendas_TiposInspecao::getRegistroPadrao(),
							  'tipo_pedido'=>Erp_Model_Vendas_TiposPedido::getRegistroPadrao()	
		));
		
		$this->view->form = $form;
		
		
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			if ($form->isValid($formdata)) {
				unset($formdata['nomepessoa']);
				unset($formdata['nomevendedor']);
				unset($formdata['submit']);
				if($formdata['entrega_de'] <> ''){
				$formdata['entrega_de'] = Functions_Datas::inverteData($formdata['entrega_de']);
				}else{
					$formdata['entrega_de'] = null;
				}
				if($formdata['entrega_ate'] <> ''){
					$formdata['entrega_ate'] = Functions_Datas::inverteData($formdata['entrega_ate']);
				}else{
					$formdata['entrega_ate'] = null;
				}
				if($formdata['datainspecao'] <> ''){
					$formdata['datainspecao'] = Functions_Datas::inverteData($formdata['datainspecao']);
				}else{
					$formdata['datainspecao'] = null;
				}
				
				
				if($formdata['agendamento_entrega'] <> ''){
					$formdata['agendamento_entrega'] = Functions_Datas::inverteData($formdata['agendamento_entrega']) ." ".$formdata['agendamento_entrega_hora'].":00" ;
				}else{
					$formdata['agendamento_entrega'] = NULL;
				}
				if($formdata['id_vendedor'] == ''){
					$formdata['id_vendedor'] = 0;
				}
				if($formdata['comissao'] == ''){
					$formdata['comissao'] = 0;
				}
				unset($formdata['agendamento_entrega_hora']);
				$formdata['comissao'] = str_replace(",", ".", $formdata['comissao']);
				$formdata['datacriado'] = date("Y-m-d H:i:s");
				$formdata['usermake'] = $this->userInfo->id_registro;
				$formdata['alteracoes'] = 0;							
				$db = new Erp_Model_Vendas_Basicos();
				$id = $db->insert($formdata);
				$this->log->log("Novo Pedido de vendas: {$this->userInfo->nomecompleto} ID: {$id} ",Zend_Log::INFO);
				$this->_redirect("/erp/vendas/abrir-pedido/id/$id/acao/novo");
				
			}else{
				
				$error =  $form->getMessages();
				$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');
				
				foreach($error as $erro){
					$messages[] = Functions_Messages::renderAlert($erro[0],'info');
				}
				$this->_redirect("cadastros/produtos/abrir/id/$id/acao/novo");
				
				$this->view->AlertMessage = $messages;
				$form->populate($formdata);
				
				
			}
			
		}
		
		
		
		
	}
	
	public function listarPedidosAction(){
		
	}
	
	public function abrirPedidoAction(){
		
		$form = new Erp_Form_Vendas();
		$form->basicos();
				
		$id = $this->_getParam('id');
		$action = $this->_getParam('acao');
		if($action == 'novo'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Pedido de venda cadastrado",'sucesso'));
		}
		
		if($action == 'update'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Pedido de venda atualizado com sucesso!",'sucesso'));
		}
		
		$db = new Erp_Model_Vendas_Basicos();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$data['nomepessoa'] = Cadastros_Model_Pessoas::getNomeEmpresa($data['id_pessoa']);		
		$data['nomevendedor'] = Cadastros_Model_Pessoas::getNomeEmpresa($data['id_vendedor']);
		if($data['entrega_de'] <> ''){
		$data['entrega_de'] = Functions_Datas::MyDateTime($data['entrega_de']);
		}
		if($data['entrega_ate'] <> ''){
		$data['entrega_ate'] = Functions_Datas::MyDateTime($data['entrega_ate']);
		}
		if($data['datainspecao'] <> ''){
		$data['datainspecao'] = Functions_Datas::MyDateTime($data['datainspecao']);
		}
		if($data['agendamento_entrega'] <> ''){
		$data['agendamento_entrega_ok'] = $data['agendamento_entrega'];
		$data['agendamento_entrega_hora'] = date('H:i',strtotime($data['agendamento_entrega']));
		$data['agendamento_entrega'] = Functions_Datas::MyDateTime($data['agendamento_entrega']);
		}else{
			$data['agendamento_entrega_ok'] = '';
			$data['agendamento_entrega_hora'] = '';
			$data['agendamento_entrega'] = '';
		}
		
		$form->populate($data);
		$this->view->form = $form;
		$this->view->basicosData = $data;
		
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			if ($form->isValid($formdata)) {
				unset($formdata['nomepessoa']);
				unset($formdata['nomevendedor']);
				unset($formdata['submit']);
			if($formdata['entrega_de'] <> ''){
				$formdata['entrega_de'] = Functions_Datas::inverteData($formdata['entrega_de']);
				}else{
					$formdata['entrega_de'] = null;
				}
				if($formdata['entrega_ate'] <> ''){
					$formdata['entrega_ate'] = Functions_Datas::inverteData($formdata['entrega_ate']);
				}else{
					$formdata['entrega_ate'] = null;
				}
				if($formdata['datainspecao'] <> ''){
					$formdata['datainspecao'] = Functions_Datas::inverteData($formdata['datainspecao']);
				}else{
					$formdata['datainspecao'] = null;
				}
				
				
				if($formdata['agendamento_entrega'] <> ''){
					$formdata['agendamento_entrega'] = Functions_Datas::inverteData($formdata['agendamento_entrega']) ." ".$formdata['agendamento_entrega_hora'].":00" ;
				}else{
					$formdata['agendamento_entrega'] = NULL;
				}
				if($formdata['id_vendedor'] == ''){
					$formdata['id_vendedor'] = 0;
				}
				if($formdata['comissao'] == ''){
					$formdata['comissao'] = 0;
				}
				unset($formdata['agendamento_entrega_hora']);
				$formdata['comissao'] = str_replace(",", ".", $formdata['comissao']);
				$formdata['datalastup'] = date("Y-m-d H:i:s");
				$formdata['userup'] = $this->userInfo->id_registro;
				$formdata['alteracoes'] = $data['alteracoes'] + 1;
				$db = new Erp_Model_Vendas_Basicos();
				$db->update($formdata,"id_registro = '{$formdata['id_registro']}'");
				$this->log->log("Novo Pedido de vendas: {$this->userInfo->nomecompleto} ID: {$formdata['id_registro']} ",Zend_Log::INFO);
				$this->_redirect("/erp/vendas/abrir-pedido/id/{$formdata['id_registro']}/acao/update");
		
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
	
	public function deletarPedidoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new Erp_Model_Vendas_Basicos();
		$id = $_POST['id'];
		$data = $db->delete("id_registro = '$id'");		
	}
	
	
	public function liberarProducaoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new Erp_Model_Vendas_Basicos();
		$id = $_POST['id'];
		$data = $db->update(array("liberaprod"=>1,
								  'userlibera'=>$this->userInfo->id_registro,
								  'datalibera'=>date('Y-m-d H:i:s')
								  ),"id_registro = '$id'");
	}
	
 	public function addProdutoPedidoAction(){
 		$this->_helper->layout->disableLayout();
 		$this->_helper->viewRenderer->setNoRender();
 			if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			$db = new Erp_Model_Vendas_Produtos();
			$formdata['qtdproduto'] =  str_replace(",", ".",$formdata['qtdproduto']);
			$formdata['valorun'] =  str_replace(",", ".",$formdata['valorun']);
			if($formdata['comissaoprod'] <> ''){
			$formdata['comissaoprod'] = str_replace(",", ".",$formdata['comissaoprod']);
			}else{
				$formdata['comissaoprod'] = 0;
			}
			$data['id_venda'] = $formdata['id_venda'];
			$data['id_produto'] = $formdata['id_produto'];
			$data['quantidade'] = $formdata['qtdproduto'];
			$data['vl_unitario'] = $formdata['valorun'];
			$data['vl_total'] = $formdata['valorun'] * $formdata['qtdproduto'] ;			
			$data['comissao'] = $formdata['comissaoprod'];
			$data['qtd_faturada'] = 0;
			$data['qtd_afaturar'] = $formdata['qtdproduto'];
			$data['adicional_1'] = $formdata['adicional_1'];
			$data['adicional_2'] = $formdata['adicional_2'];
			$data['adicional_3'] = $formdata['adicional_3'];
			if($formdata['id_registro'] == 0 || $formdata['id_registro'] == ''){
				$data['useradd'] = $this->userInfo->id_registro;
				$data['dateadd'] = date('Y-m-d H:i:s');
				$db->insert($data);
			}else{
				$data['useralter'] = $this->userInfo->id_registro;
				$data['datealter'] = date('Y-m-d H:i:s');
				$db->update($data,"id_registro = '{$formdata['id_registro']}'");
			}
			
 			}
 	}
	
	public function removeProdutoPedidoAction(){
		

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new Erp_Model_Vendas_Produtos();
		$id = $_POST['id'];
		$db->delete("id_registro = '$id'");
		
	}
	
	public function getProdutoPedidoAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new Erp_Model_Vendas_Produtos();
		$id = $_POST['id'];
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$data['nomeproduto'] = Cadastros_Model_Produtos::getNomeProduto($data['id_produto']);
		$data['quantidade'] = number_format($data['quantidade'],2,',','');
		$data['vl_unitario'] = number_format($data['vl_unitario'],2,',','');
		$data['vl_total'] = number_format($data['vl_total'],2,',','');
		$data['comissao'] = number_format($data['comissao'],2,',','');
		$data['qtd_faturada'] = number_format($data['qtd_faturada'],2,',','');
		$data['qtd_afaturar'] = number_format($data['qtd_afaturar'],2,',','');
	    echo json_encode($data);
	
	}
	
	
	public function calendarPedidosAction(){
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblvendas_basicos'),array('a.id_registro','a.agendamento_entrega','a.liberaprod', 'a.pedido_cliente','a.alteracoes','b.descritivo','c.razaosocial','c.cnpj'))
			->join(array('b'=>'tblapoio_tipodepedido'),'b.id_registro = a.tipo_pedido',array())
			->join(array('c'=>'tblpessoas_basicos'), 'c.id_registro = a.id_pessoa',array())
			->where("a.agendamento_entrega is not null");
			$rs = $return->query();
			$dados = $rs->fetchAll();
			$retorno = array();
			foreach($dados as $data){
				
				$t = strtotime($data['agendamento_entrega']);
				$retorno[] = array('id' => $data['id_registro'],
								   'title' => "({$data['id_registro']}) {$data['razaosocial']}",
								   'start' => date("Y-m-d H:i:s",strtotime($data['agendamento_entrega'])),
								   'end'=>    date("Y-m-d H:i:s",strtotime("+30 minutes",$t)),
								   'allDay'=>false,
								   'url' => "/erp/vendas/abrir-pedido/id/{$data['id_registro']}",
								   'color'=>'#aedb97',
								   'textColor'=>'#3d641b'
					 			);
			}
			
			echo json_encode($retorno);
		
	}
	
	
	

	
	
}