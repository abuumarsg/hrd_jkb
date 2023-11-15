<?php
/**
* 
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_setting extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }
	function concept_data($nt){
		return $this->db->get($nt)->result();
	}
	function concept_data_s($nt,$idj){
		return $this->db->get_where($nt,array('id_jabatan'=>$idj))->result();
	}
	function concept_s($nt,$idj,$kode){
		return $this->db->get_where($nt,array('id_jabatan'=>$idj,'kode_indikator'=>$kode))->row_array();
	}
	function cek_setting($kode){
		return $this->db->get_where('concept_kpi',array('kode_c_kpi'=>$kode))->row_array();
	}
	function tb($tb){
		return $this->db->get($tb)->result();	
	}
	//sikap
	function attd_concept($k){
		return $this->db->get_where('concept_sikap',array('kode_c_sikap'=>$k))->row_array();
	}
	function table_attd($tb){
		return $this->db->get($tb)->result();
	}
}