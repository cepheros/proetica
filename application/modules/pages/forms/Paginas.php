<?php
class Pages_Form_Paginas extends System_Form_Formdecorator{



	public function init()
	{
		 
	}
	
    public function page(){
        $this->setName('pages');
        $this->setAttrib( 'class', 'form-horizontal' );
        $this->setMethod('POST');
         
         
        $id_registro = new Zend_Form_Element_Hidden('id_registro');
        $this->addElement($id_registro);
        
        $page = new Zend_Form_Element_Hidden('page');
        $this->addElement($page);
        
        $titulopagina = new Zend_Form_Element_Text('titulopagina');
        $titulopagina->setLabel('Título da Página: *')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->setAttrib('title', 'Informe um nome para o quiz')
        ->setAttrib('class', 'span12 required')
        ->setAttrib("placeholder","Nome")
        ->addErrorMessage("Nome do quiz deve ser informado");
        $this->addElement($titulopagina);
        
        $smalltitle = new Zend_Form_Element_Text('smalltitle');
        $smalltitle->setLabel('SubTitulo da Página: *')
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->setAttrib('title', 'Informe um nome para o quiz')
        ->setAttrib('class', 'span12 required')
        ->setAttrib("placeholder","Nome")
        ->addErrorMessage("Nome do quiz deve ser informado");
        $this->addElement($smalltitle);
        
        $estilo = new Zend_Form_Element_Select("estilo");
        $estilo->setLabel('Estilo de Cores:')->setRequired(true)
        ->addErrorMessage("Informe o tipo de estoque")
        ->setRequired(true)
        ->addValidator("NotEmpty")
        ->setAttrib('class', 'required')
        ->setMultiOptions(array('1'=>"Azul",'2'=>"Verde","3"=>"Vermelho"));
        $this->addElement($estilo);
        
        $caixadestaque = new Zend_Form_Element_Select("caixadestaque");
        $caixadestaque->setLabel('Caixa de Destaque:')->setRequired(true)
        ->addErrorMessage("Informe o tipo de estoque")
        ->setRequired(true)
        ->addValidator("NotEmpty")
        ->setAttrib('class', 'required')
        ->setMultiOptions(array('1'=>"Azul",'2'=>"Verde","3"=>"Vermelho"));
        $this->addElement($caixadestaque);
        
        $mostraopnioes = new Zend_Form_Element_Radio("mostraopinioes");
        
        $mostraclientes = new Zend_Form_Element_Radio("mostraclientes");
        
        
        
        $conteudo = new Zend_Form_Element_Textarea('conteudo');
        $conteudo->setLabel("Página:")
        ->setRequired(true)
        ->setAttrib('rows', '25')
        ->setAttrib("style", "width:100%");
        $this->addElement($conteudo);
        
        
        
        
    }
	
}