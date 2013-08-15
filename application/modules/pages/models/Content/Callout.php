<?php
class Pages_Model_Content_Basicos extends Zend_Db_Table_Abstract
{

    protected $_name = 'tblpages_callout';
    protected $_primary = 'id_registro';

    
    public static function renderCallOut($id){
        $db = new Pages_Model_Content_Basicos;
        if($id <> 0){
            $data = $db->fetchRow("id_registro = '$id'");
            $callout = "<div class=\"vertical-space1\"></div>
                        <div class=\"callout\">
                        <a class=\"callurl\" href=\"{$data->callouturl}\">{$data->buttontext}</a>
                        <h4>{$data->titulo}</h4>
                        <p style=\"text-align: justify;\">{$data->descritivo}</p>
                        </div>";
                   
        }else{
            $callout = '';
        }
        
        return $callout;
    }
    
    
}