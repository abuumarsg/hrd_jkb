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

    /*
    USAGE
    variabel $data bertipe array
    $data=[
        'post'=>'nama form post',
        'table'=>'nama table untuk upload',
        'column'=>'nama kolom file directory {tabel}', 
        'where'=>['kondisi database'],
        'usage'=>'insert or update',
        'allow_null'=>TRUE/FALSE, //optional
        'default_dir'=>'directory default if null', //optional
        'otherdata'=>['semua data selain post image'],
    ];

    */
class Filehandler{
    
    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->date=$this->CI->otherfunctions->getDateNow();
    }

    public function index()
    {
        $this->redirect('not_found');
    }
    public function ruleFile($usage='image',$val=null,$newName=null)
    {
        
        if ($usage == 'image') {
            if ($val == 'berita') {
                $properties['upload_path']      = './asset/img/berita';
            }elseif($val == 'struktur') {
                $properties['upload_path']      = './asset/img/struktur';
            }else{
                $properties['upload_path']          = './asset/img/user-photo';
            }
            $properties['allowed_types']        = 'gif|jpg|png|jpeg|JPEG';
            $properties['max_size']             = 1000;
            $properties['max_width']            = 1024;
            $properties['max_height']           = 768;
        }
        if ($usage == 'doc') {
            $properties['upload_path']          = './asset/upload/document';
            $properties['allowed_types']        = 'doc|docx';
            $properties['max_size']             = 3000;
        }
        if ($usage == 'pdf') {
            $properties['upload_path']          = './asset/upload/pdf';
            $properties['allowed_types']        = 'pdf';
            $properties['max_size']             = 3000;
        }
        if ($usage == 'restore') {
            $properties['upload_path']          = './application/document/restore_db';
            $properties['allowed_types']        = 'sql';
            // $properties['allowed_types']        = 'gif|jpg|png|jpeg|JPEG';
        }
        if ($usage == 'self_learning') {
            $properties['upload_path']          = './asset/file/self_learning';
            $properties['allowed_types']        = 'mp4|pptx|pdf|jpg|jpeg|png|xlsx|docx';
            $properties['file_name']            = $newName;
        }
        return $properties;
    } 
    public function doDb($data,$usage ='insert',$table)
    {
        $ret=$this->CI->messages->allFailure();
        if (!isset($data)){
            $ret=$this->CI->messages->customFailure('Fatal Error!, Harap Hubungi Administrator');
        }
        if ($usage == 'insert') {
            $ret=$this->CI->model_global->insertQuery($data['data'],$table);
        }else{
            $ret=$this->CI->model_global->updateQuery($data['data'],$table,$data['where']);
        }
        return $ret;
    }
    public function doUpload($data,$usage='image',$val=null,$newName=null)
    {
        //data is array
        $ret=$this->CI->messages->allFailure();
        if (!isset($data)){
            $ret=$this->CI->messages->customFailure('Fatal Error!, Harap Hubungi Administrator');
        }        
        $config=$this->ruleFile($usage,$val,$newName);    
        $this->CI->load->library('upload', $config);
        $upload=$this->CI->upload->do_upload($data['post']);
        $dt=$this->CI->upload->data();
        $dir='asset/img/user-photo/';
        if ($usage == 'doc') {
            $dir='asset/upload/document/';
        }elseif ($usage == 'pdf') {
            $dir='asset/upload/pdf/';
        }elseif ($val == 'berita') {
            $dir='asset/img/berita/';
        }elseif ($val == 'struktur') {
            $dir='asset/img/struktur/';
        }elseif ($usage == 'restore') {
            $dir='application/document/restore_db/';
        }elseif ($usage == 'self_learning') {
            $dir='asset/file/self_learning/';
        }
        $file=(empty($newName) ? $dir.$dt['file_name'] : $dir.$newName.$dt['file_ext']);
        $db=[$data['column']=>$file];
        // echo '<pre>';
        // print_r($dt);
        if (!$upload){
            //error handling
            if ($usage == 'image') {
                if ($dt['is_image'] == false) {
                    $ret = $this->CI->messages->customFailure('Type File harus *.gif, *.jpg, *.jpeg, *.png');
                }elseif ($dt['file_size'] > $config['max_size'] ) {
                    $ret = $this->CI->messages->customFailure('Ukuran Foto harus berukuran KURANG DARI 1 MB');
                }else{
                    $ret = $this->CI->messages->customFailure('Foto tidak bisa diupload, silahkan ganti file foto yang lain');
                }
            }elseif ($usage == 'doc') {
                $ext=['.doc','docx'];
                if (!in_array($dt['file_ext'],$ext)) {
                    $ret = $this->CI->messages->customFailure('Type File harus *.doc, *.docx');
                }elseif ($dt['file_size'] > $config['max_size'] ) {
                    $ret = $this->CI->messages->customFailure('Ukuran File harus berukuran KURANG DARI 3 MB');
                }else{
                    $ret = $this->CI->messages->customFailure('File tidak bisa diupload, silahkan ganti file dokumen yang lain');
                }
            }elseif ($usage == 'pdf') {
                $ext=['.pdf'];
                if (!in_array($dt['file_ext'],$ext)) {
                    $ret = $this->CI->messages->customFailure('Type File harus *.pdf');
                }elseif ($dt['file_size'] > $config['max_size'] ) {
                    $ret = $this->CI->messages->customFailure('Ukuran File harus berukuran KURANG DARI 3 MB');
                }else{
                    $ret = $this->CI->messages->customFailure('File tidak bisa diupload, silahkan ganti file dokumen yang lain');
                }
            }elseif ($usage == 'restore') {
                $ext=['sql'];
                if (!in_array($dt['file_ext'],$ext)) {
                    $ret = $this->CI->messages->customFailure('Type File harus *.sql');
                // }elseif ($dt['file_size'] > $config['max_size'] ) {
                //     $ret = $this->CI->messages->customFailure('Ukuran File harus berukuran KURANG DARI 3 MB');
                }else{
                    $ret = $this->CI->messages->customFailure('File tidak bisa diupload, silahkan ganti file dokumen yang lain');
                }
            }elseif ($usage == 'self_learning') {
                $ext=['mp4', 'pptx', 'pdf', 'jpg', 'jpeg', 'png', 'xlsx', 'docx'];
                if (!in_array($dt['file_ext'],$ext)) {
                    $ret = $this->CI->messages->customFailure('Type File harus *.mp4, *.pptx, *.pdf, *.jpg, *.jpeg, *.png, *.xlsx, *.docx');
                }else{
                    $ret = $this->CI->messages->customFailure('File tidak bisa diupload, silahkan ganti file dokumen yang lain');
                }
            }
            //allow null
            if (isset($data['allow_null']) && ($dt['file_name'] == '' || $dt['file_type'] == '')) {
                if ($data['allow_null'] == TRUE && (isset($data['default_dir']))) {
                    $db=[$data['column']=>$data['default_dir']];
                    $data_db['data']=array_merge($data['otherdata'],$db);
                    if ($data['usage'] == 'update') {
                        $data_db['where']=$data['where'];
                    }
                    $ret=$this->doDb($data_db,$data['usage'],$data['table']);
                }
            }
            //bypass update
            if ($data['usage'] == 'update' && ($dt['file_name'] == '' || $dt['file_type'] == '') && !isset($data['allow_null'])) {
                $data_db=['data'=>$data['otherdata'],'where'=>$data['where']];
                $ret=$this->doDb($data_db,$data['usage'],$data['table']);
            }             
        }else{
            if ($dt['file_name'] == '' || $dt['file_type'] == '') {
                $data_db['data']=$data['otherdata'];
            }else{
                $data_db['data']=array_merge($data['otherdata'],$db);
            }
            if(isset($data['unlink']) && $data['unlink'] == 'yes'){
                $d_old=$this->CI->db->get_where($data['table'],$data['where'])->row_array();
                if($d_old[$data['column']]!=null && $d_old[$data['column']] != $file){
                    unlink($d_old[$data['column']]);
                }
            }
            if ($data['usage'] == 'update') {
                $data_db['where']=$data['where'];
            }
            $ret=$this->doDb($data_db,$data['usage'],$data['table']);
        }        
        return $ret;
    }
    public function doDownload($dir)
    {
        if (empty($dir))
            return false;
        $this->CI->load->helper('download');
        force_download($dir,NULL);
    }
}