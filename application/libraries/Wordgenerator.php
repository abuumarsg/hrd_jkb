<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Otherfunctions
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */
require_once APPPATH."/libraries/PHPWord.php";
class Wordgenerator extends PHPWord{
    
    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->word=new PHPWord();
        $this->dir=FCPATH;
    }

    public function index()
    {
        $this->redirect('not_found');
    }
    public function convertHtml($html)
    {
        if (empty($html)) 
            return null;
        $html=str_replace('<br>'," <w:br/> ",$html);
        $html=str_replace('<b>',"",$html);
        $html=str_replace('</b>',"",$html);
        $html=str_replace('<p>',"",$html);
        $html=str_replace('</p>',"",$html);
        $html=str_replace('</div>',"",$html);
        return $html;
    } 
    public function genDoc($val,$doc)
    {
        if (!isset($val)) 
            return false;
        header('Content-Type: application/octet-stream');
        $document = $this->word->loadTemplate($this->dir.$doc);
        foreach ($val['data'] as $k_v => $v_v) {
            // $v_v=(strpos($k_v, '_html') > 0)?($this->convertHtml($v_v)):preg_replace('/&/',"DAN",$v_v); 
            // $v_v=(strpos($k_v, '_html') > 0)?(htmlspecialchars(trim(strip_tags($v_v)), ENT_COMPAT, 'UTF-8')):preg_replace('/&/',"DAN",$v_v);
            $v_v=preg_replace('/&/',"DAN",$v_v);         
            $document->setValue('{'.$k_v.'}', $v_v);
        }
        $temp_file = 'temp_doc.docx';
        $document->save($temp_file); 
        header("Content-Disposition: attachment; Filename=\"{$val['name_file']}.docx\"");
        // readfile($temp_file);
        echo file_get_contents($temp_file);
        unlink($temp_file);
    }
}