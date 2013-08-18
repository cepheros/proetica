<?php
class SobreNosController extends Zend_Controller_Action
{

	public function init()
	{
	    $this->_helper->layout()->setLayout('layoutpages');
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		$db = new Pages_Model_Content_Basicos();
		$data = $db->fetchRow("page = 'about'");
		if($data->estilo == 1){
		    $this->_helper->layout()->setLayout('layoutpages');
		}else{
		    $this->_helper->layout()->setLayout('layoutgreen');
		}
		$this->view->content = $data;
	}

	public function faqAction(){
	    
	}
	
	public function quemAction(){
	    
	}

}

