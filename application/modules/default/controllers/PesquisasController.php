<?php
class PesquisasController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
		$this->_helper->layout()->setLayout('layoutpages');
	}

	public function indexAction()
	{
	    
	    $db = new Pages_Model_Quiz_Basicos();
	    $dados = $db->fetchAll();
	    $this->view->quiz = $dados; 
	
	}
	
	
	public function abrirAction(){
	    $id = $this->_getParam("id");
	    $this->view->id = $id;
	    
	    $this->view->id = $id;
	    $db1 = new Pages_Model_Quiz_Basicos();
	    $basicos = $db1->fetchRow("id_registro = '$id'")->toArray();
	    
	    $db2 = new Pages_Model_Quiz_Perguntas();
	    $perguntas = $db2->fetchAll("id_quiz = '$id'")->toArray();
	    
	    $db3 = new Pages_Model_Quiz_Respostas();
	    
	    $retorno = '';
	    $retorno['info']['name'] =  $basicos['nomequiz'];
	    $retorno['info']['main'] = $basicos['maintext'];
	    $retorno['info']['results'] = $basicos['resultado'];
	    $retorno['info']['level1'] = $basicos['level1'];
	    $retorno['info']['level2'] = $basicos['level2'];
	    $retorno['info']['level3'] = $basicos['level3'];
	    $retorno['info']['level4'] = $basicos['level4'];
	    $retorno['info']['level5'] = $basicos['level5'];
	    $retorno['questions'] =  array();
	    
	    foreach($perguntas as $question){
	    	$resp = $db3->fetchAll("id_pergunta = '{$question['id_registro']}'")->toArray();
	    	$data['q'] = $question['pergunta'] ;
	    	$respa = array();
	    	foreach($resp as $res){
	    		$respaa['option'] = $res['resposta'];
	    		if($res['correct'] == '1'){
	    			$respaa['correct']= true;
	    		}else{
	    			$respaa['correct']= FALSE;
	    		}
	    		array_push($respa, $respaa);
	    	}
	    	$data['a'] =  $respa;
	    	$data['correct'] = $question['msg_correto'];
	    	$data['incorrect'] = $question['msg_errado'];
	    
	    	array_push($retorno['questions'], $data);
	    }
	    
	    
	    $this->view->quiz =  json_encode($retorno);
	    
	}
	
	public function getQuizAction(){
	    $this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender();
	    
	 
	}
	


}
