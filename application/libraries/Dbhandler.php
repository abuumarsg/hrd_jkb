<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
    * Code From GFEACORP.
    * Web Developer
    * @author      Galeh Fatma Eko Ardiansa S.Kom
    * @type        Library
    * @package     jkb/Dbhandler
    * @copyright   Copyright (c) 2019 GFEACORP
    * @version     1.0, 31 Dec 2019
    * Email        galeh.fatma@gmail.com
    * Phone        (+62) 85852924304
    * ==========// HAK CIPTA DILINDUNGI! //==========
*/


class Dbhandler
{
    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    //========================================== BLOCK CHANGE ==========================================//
    public function readTable($text,$command)
    {
        if (empty($text) || empty($command)) 
            return null;
        $length_command=strlen($command);
        $position_command=strpos($command,$text);
        $table=substr($text, ($position_command+$length_command));
        $position_kurung=strpos($table,' (');
        $table=substr($table,0, $position_kurung);
        $table=str_replace('`','',$table);
        return $table;
    }
    public function readColumn($text,$command,$table)
    {
        if (empty($text) || empty($command) || empty($table)) 
            return null;
        $command=$command." `".$table."` (";
        $length_command=strlen($command);
        $position_command=strpos($command,$text);
        $text=substr($text, ($position_command+$length_command));
        $position_kurung=strpos($text,') ');
        $text=substr($text,0, $position_kurung);
        $text=explode(', ',$text);
        $column=[];
        if (isset($text)) {
            foreach ($text as $col) {
                $col=str_replace('`','',$col);
                array_push($column,$col);
            }
        }
        return $column;
    }
    public function readValues($text,$command)
    {
        if (empty($text) || empty($command)) 
            return null;
        $length_command=strlen($command);
        $position_command=strpos($text,$command);
        $text=substr($text, ($position_command+$length_command));
        $text=str_replace(')','',$text);
        $text=explode(', ',$text);
        $values=[];
        if (isset($text)) {
            foreach ($text as $col) {
                $col=str_replace("'",'',$col);
                array_push($values,$col);
            }
        }
        return $values;
    }
    public function getExplodeFromInsert($text)
    {
        $ret=null;
        if (empty($text))
            return null;
        $ret=rtrim($text, "\n;");
        $ret=explode(';',$ret);
        $command="INSERT INTO ";
        $pack=[];
        if (isset($ret)) {
            foreach ($ret as $sql) {
                $pos = strpos($sql,'INSERT INTO');
                if($pos !== false){
                    $sql=ltrim($sql);
                    $table=$this->readTable($sql,$command);
                    $column=$this->readColumn($sql,$command,$table);
                    $values=$this->readValues($sql,") VALUES (");
                    $data=[];
                    $id_key=false;
                    $val_key=false;
                    if (isset($column) && isset($values)) {
                        foreach ($column as $key => $cols) {
                            if (isset($values[$key])) {
                                if ($key==0) {
                                    $id_key=$cols;
                                    $val_key=$values[$key];
                                    $cols=$key;
                                }
                                $data[$cols]=$values[$key];                             
                            }
                        }
                    }
                    $pack[]=['table'=>$table,'data'=>$data,'id_primary'=>$id_key,'value_primary'=>$val_key];
                }
            }
        }
        return $pack;
    }
}