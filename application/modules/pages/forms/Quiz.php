<?php
class Pages_Form_Quiz extends System_Form_Formdecorator{



	public function init()
	{
	  
	}
	
	public function basicos(){
	    
	    $this->setName('quiz');
	    $this->setAttrib( 'class', 'form-horizontal' );
	    $this->setMethod('POST');
	    
	    
	    $id_registro = new Zend_Form_Element_Hidden('id_registro');
	    $this->addElement($id_registro);
	    
	    $nomequiz = new Zend_Form_Element_Text('nomequiz');
	    $nomequiz->setLabel('Nome do Quiz: *')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Informe um nome para o quiz')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","Nome")
	    ->addErrorMessage("Nome do quiz deve ser informado");
	    $this->addElement($nomequiz);
	    
	    $maintext = new Zend_Form_Element_Textarea('maintext');
	    $maintext->setLabel("Texto Explicativo:")
	    ->setRequired(false)
	    ->setAttrib('rows', '5')
	    ->setAttrib("style", "width:100%")
	    ->addFilter('StripTags');
	    $this->addElement($maintext);
	    
	    $resultado = new Zend_Form_Element_Textarea('resultado');
	    $resultado->setLabel("Texto do Resultado:")
	    ->setRequired(false)
	    ->setAttrib('rows', '5')
	    ->setAttrib("style", "width:100%")
	    ->addFilter('StripTags');
	    $this->addElement($resultado);
	    
	    $quizdescription = new Zend_Form_Element_Textarea('quizdescription');
	    $quizdescription->setLabel("Descritivo do Quiz:")
	    ->setRequired(false)
	    ->setAttrib('rows', '5')
	    ->setAttrib("style", "width:100%")
	    ->addFilter('StripTags');
	    $this->addElement($quizdescription);
	    
	    $level1 = new Zend_Form_Element_Text('level1');
	    $level1->setLabel('100% de Acerto:')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Informe o texto para 100% de acertos')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","")
	    ->addErrorMessage("Texto deve ser informado");
	    $this->addElement($level1);
	    
	    $level2 = new Zend_Form_Element_Text('level2');
	    $level2->setLabel('80% de Acerto: *')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Informe o texto para até 80% de acertos')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","")
	    ->addErrorMessage("Texto deve ser informado");
	    $this->addElement($level2);
	    
	    $level3 = new Zend_Form_Element_Text('level3');
	    $level3->setLabel('60% de Acerto: *')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Informe o texto para até 60% de acertos')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","")
	    ->addErrorMessage("Texto deve ser informado");
	    $this->addElement($level3);
	    
	    $level4 = new Zend_Form_Element_Text('level4');
	    $level4->setLabel('40% de Acerto: *')
	   ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Informe o texto para ate 40% de acertos')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","")
	    ->addErrorMessage("Texto deve ser informado");
	    $this->addElement($level4);
	    
	    $level5 = new Zend_Form_Element_Text('level5');
	    $level5->setLabel('20% de acertos: *')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Informe o texto para até 20% de acertos')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","")
	    ->addErrorMessage("Texto deve ser informado");
	    $this->addElement($level5);
	    
	  
	}
	
	
	public function pergunta(){
	    
	    $id_registro = new Zend_Form_Element_Hidden('id_registro');
	    $this->addElement($id_registro);
	    
	    $id_quiz = new Zend_Form_Element_Hidden('id_quiz');
	    $this->addElement($id_quiz);
	    
	    $pergunta = new Zend_Form_Element_Text('pergunta');
	    $pergunta->setLabel('Pergunta: *')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Qual a pergunta?')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","Nome")
	    ->addErrorMessage("Pergunta deve ser informada");
	    $this->addElement($pergunta);
	    
	    
	    $msg_correto = new Zend_Form_Element_Text('msg_correto');
	    $msg_correto->setLabel('Mensagem de Acerto: *')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Qual a mensagem')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","Nome")
	    ->addErrorMessage("Mensagem deve ser informada");
	    $this->addElement($msg_correto);
	    
	    
	    $msg_errado = new Zend_Form_Element_Text('msg_errado');
	    $msg_errado->setLabel('Mensagem de Erro: *')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Qual a mensagem')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","Nome")
	    ->addErrorMessage("Mensagem deve ser informada");
	    $this->addElement($msg_errado);
	    
	}
	
	public function resposta(){
	    
	    $id_registro = new Zend_Form_Element_Hidden('id_registro');
	    $this->addElement($id_registro);
	     
	    $id_pergunta = new Zend_Form_Element_Hidden('id_pergunta');
	    $this->addElement($id_pergunta);
	     
	    $resposta = new Zend_Form_Element_Text('resposta');
	    $resposta->setLabel('Resposta: *')
	    ->setRequired(true)
	    ->addFilter('StripTags')
	    ->addFilter('StringTrim')
	    ->addValidator('NotEmpty')
	    ->setAttrib('title', 'Qual a resposta?')
	    ->setAttrib('class', 'span12 required')
	    ->setAttrib("placeholder","Nome")
	    ->addErrorMessage("Pergunta deve ser informada");
	    $this->addElement($resposta);
	    
	    $correct = new Zend_Form_Element_Select("correct");
	    $correct->setLabel('Resposta Correta?:')->setRequired(true)
	    ->addErrorMessage("Informe o tipo de estoque")
	    ->setRequired(true)
	    ->addValidator("NotEmpty")
	    ->setAttrib('class', 'required')
	    ->setMultiOptions(array('1'=>"Sim",'0'=>"Não"));
	    $this->addElement($correct);
	     
	    
	}

}