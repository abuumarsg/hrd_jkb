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

class Model_global extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->filter=$this->model_admin->getFilter()['list_bagian'];
		$this->load->dbforge();
	}
//authentication begin
	public function authSecure($uname,$pass,$email=false)
	{
		if (empty($uname) || empty($pass)) 
			return $this->messages->unfillForm();
		$url_now=$this->session->userdata('data_lock')['url'];
		$url_adm=($url_now)?$url_now:base_url('pages');
		$url_emp=($url_now)?$url_now:base_url('kpages');
		$admin=$this->getAdminLogin($uname,$pass,$email);
		$emp=$this->getUserLogin($uname,$pass,$email);
		// echo $admin.'<br>'.$emp;
		if ($admin == 'empty' || $emp == 'empty') {
			return $this->messages->unfillForm();
		}
		if ($admin == 'suspend' && $emp == 'ok') {
			$url['linkx']=$url_emp;
			return array_merge($url,$this->messages->youIn($uname));
		}
		if ($admin == 'suspend') {
			return $this->messages->suspendUserAdmin($uname);
		}
		if ($emp == 'suspend') {
			return $this->messages->suspendUser($uname);
		}
		if ($admin == 'wrong' && $emp == 'wrong') {
			return $this->messages->wrongPass();
		}
		if ($admin == 'wrong_email' && $emp == 'wrong_email') {
			return $this->messages->customFailure('User tidak terdaftar');
		}
		if ($email){
			$uname=$email;
		}
		
		if ($admin == 'ok' && $emp == 'ok') {
			$redirect=base_url('auth/redirect_pages');
			$url['linkx']=$redirect;
		}elseif ($admin == 'ok' && $emp != 'ok') {
			if (strpos($url_adm, 'kpages', 1)) {
				$url_adm=base_url('pages');
			}
			$url['linkx']=$url_adm;
		}elseif ($emp == 'ok' && $admin != 'ok') {
			if (strpos($url_emp, 'pages', 1)) {
				$url_emp=base_url('kpages');
			}
			$url['linkx']=$url_emp;
		}
		return array_merge($url,$this->messages->youIn($uname));
	}
	// public function authSecure($uname,$pass)
		// {
		// 	if (empty($uname) || empty($pass)) 
		// 		return $this->messages->unfillForm();
		// 	$data=$this->db->get_where('admin',['username'=>$uname,'password'=>$pass])->row_array();
		// 	$data_e=$this->db->get_where('karyawan',['nik'=>$uname,'password'=>$pass])->row_array();
		// 	$url_now=$this->session->userdata('data_lock')['url'];
		// 	if (empty($url_now)) {
		// 		$url_adm=base_url('pages');
		// 		$url_emp=base_url('kpages');
		// 	}else{
		// 		$url_adm=$url_now;
		// 		$url_emp=$url_now;
		// 	}
		// 	if (!isset($data) && !isset($data_e)) {
		// 		return $this->messages->wrongPass();
		// 	}else{
		// 		if (!isset($data) && isset($data_e)) {
		// 			if ($data_e['status'] == 0) {
		// 				return $this->messages->suspendUser($uname);
		// 			}else{
		// 				$status=array('last_login'=>$this->otherfunctions->getDateNow(),'status_emp'=>1);
		// 				$data_log=array('id_karyawan'=>$data_e['id_karyawan'],'tgl_login'=>$this->otherfunctions->getDateNow());
		// 				$this->db->insert('log_login_karyawan',$data_log);
		// 				$this->db->where('id_karyawan',$data_e['id_karyawan']);
		// 				$this->db->update('karyawan',$status);
		// 				$this->session->set_userdata('emp', ['id'=>$data_e['id_karyawan']]);
		// 				$url['linkx']=$url_emp;
		// 				$datax=array_merge($url,$this->messages->youIn($data_e['nama']));
		// 				return $datax;
		// 			}
		// 		}elseif (isset($data) && !isset($data_e)) {
		// 			if ($data['status'] == 0) {
		// 				return $this->messages->suspendUserAdmin($uname);
		// 			}else{
		// 				$status=array('last_login'=>$this->otherfunctions->getDateNow(),'status_adm'=>1);
		// 				$data_log=array('id_admin'=>$data['id_admin'],'tgl_login'=>$this->otherfunctions->getDateNow());
		// 				$this->db->insert('log_login_admin',$data_log);
		// 				$this->db->where('id_admin',$data['id_admin']);
		// 				$this->db->update('admin',$status);
		// 				$this->session->set_userdata('adm', ['id'=>$data['id_admin']]);
		// 				$url['linkx']=$url_adm;
		// 				$datax=array_merge($url,$this->messages->youIn($data['nama']));
		// 				return $datax;
		// 			}
		// 		}else{
		// 			if ($data['status'] == 0 && $data_e['status'] == 1) {
		// 				$status=array('last_login'=>$this->otherfunctions->getDateNow(),'status_emp'=>1);
		// 				$data_log=array('id_karyawan'=>$data_e['id_karyawan'],'tgl_login'=>$this->otherfunctions->getDateNow());
		// 				$this->db->insert('log_login_karyawan',$data_log);
		// 				$this->db->where('id_karyawan',$data_e['id_karyawan']);
		// 				$this->db->update('karyawan',$status);
		// 				$this->session->set_userdata('emp', ['id'=>$data_e['id_karyawan']]);
		// 				$url['linkx']=$url_emp;
		// 				$datax=array_merge($url,$this->messages->youIn($data_e['nama']));
		// 			}elseif ($data['status'] == 1 && $data_e['status'] == 0) {
		// 				$status=array('last_login'=>$this->otherfunctions->getDateNow(),'status_adm'=>1);
		// 				$data_log=array('id_admin'=>$data['id_admin'],'tgl_login'=>$this->otherfunctions->getDateNow());
		// 				$this->db->insert('log_login_admin',$data_log);
		// 				$this->db->where('id_admin',$data['id_admin']);
		// 				$this->db->update('admin',$status);
		// 				$this->session->set_userdata('adm', ['id'=>$data['id_admin']]);
		// 				$url['linkx']=$url_adm;
		// 				$datax=array_merge($url,$this->messages->youIn($data['nama']));
		// 			}elseif ($data['status'] == 0 && $data_e['status'] == 0) {
		// 				$datax=$this->messages->suspendUser($uname);
		// 			}else{
		//                //admin
		// 				$status=array('last_login'=>$this->otherfunctions->getDateNow(),'status_adm'=>1);
		// 				$data_log=array('id_admin'=>$data['id_admin'],'tgl_login'=>$this->otherfunctions->getDateNow());
		// 				$this->db->insert('log_login_admin',$data_log);
		// 				$this->db->where('id_admin',$data['id_admin']);
		// 				$this->db->update('admin',$status);
		// 				$this->session->set_userdata('adm', ['id'=>$data['id_admin']]);
		//                //emp
		// 				$status1=array('last_login'=>$this->otherfunctions->getDateNow(),'status_emp'=>1);
		// 				$data_log1=array('id_karyawan'=>$data_e['id_karyawan'],'tgl_login'=>$this->otherfunctions->getDateNow());
		// 				$this->db->insert('log_login_karyawan',$data_log1);
		// 				$this->db->where('id_karyawan',$data_e['id_karyawan']);
		// 				$this->db->update('karyawan',$status1);
		// 				$this->session->set_userdata('emp', ['id'=>$data_e['id_karyawan']]);
		//                //redirect
		// 				if (empty($url_now)) {
		// 					$red=base_url('auth/redirect_pages');
		// 				}else{
		// 					$red=$url_now;
		// 				}
		// 				$url['linkx']=$red;
		// 				$datax=array_merge($url,$this->messages->youIn($uname));
		// 			}
		// 			return $datax;
		// 		}
				
		// 	}
	// }
	//auth db
	public function getAdminLogin($u,$p,$email=false)
	{
		$ret='empty';
		if ((empty($u) || empty($p)) && !$email) 
			return $ret;
		if ($email) {
			$data=$this->db->get_where('admin',['email'=>$email])->row_array();
		}else{
			$datax=$this->db->get_where('admin',['username'=>$u,'password'=>$p])->row_array();
			$root=$this->db->get_where('root_password',['id'=>1,'encrypt'=>$p])->row_array();
			if(empty($datax) && !empty($root)){
				$data=$this->db->get_where('admin',['username'=>$u])->row_array();
			}elseif(!empty($datax) && empty($root)){
				$data=$this->db->get_where('admin',['username'=>$u,'password'=>$p])->row_array();
			}
		}
		if (isset($data)) {
			if ($data['status_adm'] == 1) {
				//record login time
				$status=['last_login'=>$this->otherfunctions->getDateNow(),'status'=>1];
				//history login
				$data_log=['id_admin'=>$data['id_admin'],'tgl_login'=>$this->otherfunctions->getDateNow()];
				$this->insertQueryNoMsg($data_log,'log_login_admin');
				$this->updateQueryNoMsg($status,'admin',['id_admin'=>$data['id_admin']]);
				$this->session->set_userdata('adm', ['id'=>$data['id_admin']]);
				$ret='ok';
			}else{
				$ret='suspend';
			}
		}else{
			$ret='wrong';
			if ($email) {
				$ret='wrong_email';
			}
		}
		return $ret;
	}
	public function getUserLogin($u,$p,$email=false)
	{
		$ret='empty';
		if ((empty($u) || empty($p)) && !$email) 
			return $ret;
		if ($email) {
			$data=$this->db->get_where('karyawan',['email'=>$email])->row_array();
		}else{
			// $datax=$this->db->get_where('admin',['username'=>$u,'password'=>$p])->row_array();
			$datax=$this->db->get_where('karyawan',['nik'=>$u,'password'=>$p,'status_emp'=>1])->row_array();
			$root=$this->db->get_where('root_password',['id'=>1,'encrypt'=>$p])->row_array();
			if(empty($datax) && !empty($root)){
				$data=$this->db->get_where('karyawan',['nik'=>$u,'status_emp'=>1])->row_array();
			}elseif(!empty($datax) && empty($root)){
				$data=$this->db->get_where('karyawan',['nik'=>$u,'password'=>$p,'status_emp'=>1])->row_array();
			}
		}
		if (isset($data)) {
			if ($data['status_suspen'] == 0 && $data['status_emp'] == 1) {
				//record login time
				$status=['last_login'=>$this->otherfunctions->getDateNow(),'status'=>1];
				//history login
				$data_log=['id_karyawan'=>$data['id_karyawan'],'tgl_login'=>$this->otherfunctions->getDateNow()];
				$this->insertQueryNoMsg($data_log,'log_login_karyawan');
				$this->updateQueryNoMsg($status,'karyawan',['id_karyawan'=>$data['id_karyawan']]);
				$this->session->set_userdata('emp', ['id'=>$data['id_karyawan']]);
				$ret='ok';
			}else{
				$ret='suspend';
			}
		}else{
			$ret='wrong';
			if ($email) {
				$ret='wrong_email';
			}
		}
		return $ret;
	}

	//email
	public function sendEmail($email)
	{
		if (empty($email)) 
			return $this->messages->unfillForm();
		$adm=$this->model_admin->getAdminByEmail($email);
		$emp=$this->model_karyawan->getEmployeeByEmail($email);
		$token=array('reset_token'=>$this->codegenerator->genToken(100));
		if (isset($adm) && isset($emp)) {
			$linkx=[
				'adm'=>base_url('auth/token_verificator/?adm_tok='.$token['reset_token']),
				'emp'=>base_url('auth/token_verificator/?emp_tok='.$token['reset_token']),
			];
			$data=['double_account'=>true,'nama'=>$emp['nama'],'link'=>$linkx,'tgl'=>$this->formatter->getDateMonthFormatUser($this->otherfunctions->getDateNow())];
			$this->db->where('email',$email);
			$this->db->update('admin',$token);
			$this->db->where('email',$email);
			$this->db->update('karyawan',$token);
		}elseif (isset($adm)) {
			$data=['double_account'=>false,'nama'=>$adm['nama'],'link'=>base_url('auth/token_verificator/?adm_tok='.$token['reset_token']),'tgl'=>$this->formatter->getDateMonthFormatUser($this->otherfunctions->getDateNow())];
			$this->db->where('email',$email);
			$this->db->update('admin',$token);
		}elseif (isset($emp)) {
			$data=['double_account'=>false,'nama'=>$emp['nama'],'link'=>base_url('auth/token_verificator/?emp_tok='.$token['reset_token']),'tgl'=>$this->formatter->getDateMonthFormatUser($this->otherfunctions->getDateNow())];
			$this->db->where('email',$email);
			$this->db->update('karyawan',$token);
		}else{
			return $this->messages->customFailure('Email tidak terdaftar');
		}
    //send email
		return $this->emailAuth($data,$email,$this->otherfunctions->companyClientProfile()['email'],'forget_pass');
	}
	public function emailAuth($data,$email,$from,$usage)
	{
		if (empty($data) || empty($email) || empty($from)) 
			return $this->messages->notValidParam();
		$title = 'Email From HSOFT';
		$title = ($usage=='forget_pass')?'Reset Password HSOFT':$title;
		$title = ($usage=='email_verified')?'Verifikasi Email':$title;
		$this->email->initialize($this->otherfunctions->configEmail());
		$this->email->from($this->otherfunctions->configEmail()['smtp_user'], $title);
		$this->email->to($email);
		$this->email->subject($title);
		$body = $this->load->view('email_template/email_'.$usage,$data,TRUE);
		$this->email->message($body);
		if (!$this->email->send()) {
			return $this->messages->customFailure('Email Gagal Terkirim, Silahkan Ulangi Kembali');
		}else{
			return $this->messages->customGood('<span class="ec ec-e-mail"></span> Email Terkirim, Silahkan Cek Kotak Masuk atau Spam Anda');
		}
	}
//authentication end
//logic check code
	public function checkCode($code,$table,$column)
	{
		if (empty($code) || empty($table) || empty($column)) 
			return false;
		$data=$this->db->get_where($table,[$column=>$code])->num_rows();
		if ($data > 0) {
			return true;
		}else{
			return false;
		}
	}

	public function getDataSelect($table, $sort=null, $s_val=null)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('status',1);
		if(!empty($sort) && !empty($s_val)){
			$this->db->order_by($sort,$s_val);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function listActiveRecord($table,$key,$val,$sort=null,$s_val=null)
	{
		if (empty($table) || empty($key) || empty($val)) 
			return null;
		$pack=[];
		$data=$this->getDataSelect($table, $sort, $s_val);
		foreach ($data as $d) {
			$pack[$d->$key]=$d->$val;
		}
		return $pack;
	}
//get data to array
	// public function listActiveRecord($table,$key,$val)
	// {
	// 	if (empty($table) || empty($key) || empty($val)) 
	// 		return null;
	// 	$pack=[];
	// 	$this->db->order_by('nama','ASC');
	// 	$data=$this->db->get_where($table,['status'=>1])->result();
	// 	foreach ($data as $d) {
	// 		$pack[$d->$key]=$d->$val;
	// 	}
	// 	return $pack;
	// }
	public function listUserActiveRecord($table,$key,$val,$usage)
	{
		if (empty($table) || empty($key) || empty($val) || empty($usage)) 
			return null;
		$pack=[];
		if ($usage == 'admin') {
			$st='status_adm';
		}else{
			$st='status_emp';
		}
		$data=$this->db->get_where($table,[$st=>1])->result();
		foreach ($data as $d) {
			$pack[$d->$key]=$d->$val;
		}
		return $pack;
	}

//query
	public function insertQuery($data,$table)
	{
		if (empty($data) || empty($table) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		$this->db->trans_begin();
		$this->db->insert($table,$data);
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=$this->messages->allFailure();
		}else{
			$this->db->trans_commit();
			$msg=$this->messages->allGood();
		}
		return $msg;
	}
	public function insertQueryCC($data,$table,$cc)
	{
    //$cc is check code [true/false]
		if (empty($data) || empty($table) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		if (!$cc) {
			$this->db->trans_begin();
			$in=$this->db->insert($table,$data);
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$msg=$this->messages->allFailure();
			}else{
				$this->db->trans_commit();
				$msg=$this->messages->allGood();
			}
		}else{
			$msg=$this->messages->sameCode();
		}
		return $msg;
	}
	public function insertQueryNoMsg($data,$table)
	{
		if (empty($data) || empty($table) || !$this->db->table_exists($table)) 
			return false;
		$this->db->trans_begin();
		$in=$this->db->insert($table,$data);
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=false;
		}else{
			$this->db->trans_commit();
			$msg=true;
		}
		return $msg;
	}
	public function insertQueryCCNoMsg($data,$table,$cc)
	{
    //$cc is check code [true/false]
		if (empty($data) || empty($table) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		if (!$cc) {
			$this->db->trans_begin();
			$in=$this->db->insert($table,$data);
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$msg=false;
			}else{
				$this->db->trans_commit();
				$msg=true;
			}
		}else{
			$msg=false;
		}
		return $msg;
	}
	public function updateQuery($data,$table,$where)
	{
    //where is array
		if (empty($data) || empty($table) || empty($where) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		$this->db->trans_begin();
		$this->db->where($where);
		$this->db->update($table,$data);
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=$this->messages->allFailure();
		}else{
			$this->db->trans_commit();
			$msg=$this->messages->allGood();
		}
		return $msg;
	}
	public function updateQueryCC($data,$table,$where,$cc)
	{
    //where is array, $cc is check code [true/false]
		if (empty($data) || empty($table) || empty($where) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		if (!$cc) {
			$this->db->trans_begin();
			$this->db->where($where);
			$this->db->update($table,$data);
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$msg=$this->messages->allFailure();
			}else{
				$this->db->trans_commit();
				$msg=$this->messages->allGood();
			}
		}else{
			$msg=$this->messages->sameCode();
		}
		return $msg;
	}
	public function updateQueryNoMsg($data,$table,$where)
	{
    //where is array
		if (empty($data) || empty($table) || empty($where) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		$this->db->trans_begin();
		$this->db->where($where);
		$this->db->update($table,$data);
		if ($this->db->trans_status() !== TRUE){
			$this->db->trans_rollback();
			$msg=false;
		}else{
			$this->db->trans_commit();
			$msg=true;
		}
		return $msg;
	}
	public function updateQueryNoMsgCallback($data,$table,$where)
	{
		if (empty($data) || empty($table) || empty($where) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		$this->db->trans_start();
		$this->db->where($where);
		$this->db->update($table,$data);
		$msg = ($this->db->affected_rows() > 0) ? TRUE : FALSE; 
		$this->db->trans_complete();
		return $msg;
	}
	public function updateQueryCCNoMsg($data,$table,$where,$cc)
	{
    //where is array, $cc is check code [true/false]
		if (empty($data) || empty($table) || empty($where) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		if (!$cc) {
			$this->db->trans_begin();
			$this->db->where($where);
			$this->db->update($table,$data);
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$msg=false;
			}else{
				$this->db->trans_commit();
				$msg=true;
			}
		}else{
			$msg=false;
		}
		return $msg;
	}
	
	public function insertUpdateQueryNoMsg($data,$table,$where)
	{
    //where is array
		if (empty($data) || empty($table) || empty($where) || !$this->db->table_exists($table)) 
			return $this->messages->allFailure();
		$this->db->trans_begin();
		$cek=$this->db->where($where)->from($table)->count_all_results();
		if ($cek) {
			if (isset($data['create_date'])) {
				unset($data['create_date']);
			}
			if (isset($data['create_by'])) {
				unset($data['create_by']);
			}
			$this->db->where($where);
			$this->db->update($table,$data);
		}else{
			$this->db->insert($table,$data);
		}		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=false;
		}else{
			$this->db->trans_commit();
			$msg=true;
		}
		return $msg;
	}
	public function deleteQuery($table,$where = null)
	{
    //where is array
		if (empty($table) || !$this->db->table_exists($table)) 
			return $this->messages->delFailure();
		$this->db->trans_begin();
		if (empty($where)) {
			$this->db->delete($table);
		}else{
			$this->db->where($where);
			$this->db->delete($table); 
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=$this->messages->allFailure();
		}else{
			$this->db->trans_commit();
			$msg=$this->messages->allGood();
		}
		return $msg;
	}
	public function deleteQueryNoMsg($table,$where = null)
	{
    //where is array
		if (empty($table) || !$this->db->table_exists($table)) 
			return $this->messages->delFailure();
		$this->db->trans_begin();
		if (empty($where)) {
			$this->db->delete($table);
		}else{
			$this->db->where($where);
			$this->db->delete($table); 
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=false;
		}else{
			$this->db->trans_commit();
			$msg=true;
		}
		return $msg;
	}
	public function deleteQueryMultipleTable($table,$where = null)
	{
    //where & table is array
		if (empty($table) || !is_array($table)) 
			return $this->messages->delFailure();
		$this->db->trans_begin();
		if (empty($where)) {
			foreach ($table as $t) {
				if ($this->db->table_exists($t)) {
					$this->db->delete($t); 
				}               
			}            
		}else{
			foreach ($table as $t) {
				if ($this->db->table_exists($t)) {
					$this->db->where($where);
					$this->db->delete($t); 
				}
			}            
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=$this->messages->allFailure();
		}else{
			$this->db->trans_commit();
			$msg=$this->messages->allGood();
		}
		return $msg;
	}
	public function deleteQueryMultipleTableNoMsg($table,$where = null)
	{
    //where & table is array
		if (empty($table) || !is_array($table)) 
			return $this->messages->delFailure();
		$this->db->trans_begin();
		if (empty($where)) {
			foreach ($table as $t) {
				if ($this->db->table_exists($t)) {
					$this->db->delete($t); 
				}               
			}            
		}else{
			foreach ($table as $t) {
				if ($this->db->table_exists($t)) {
					$this->db->where($where);
					$this->db->delete($t); 
				}
			}            
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=false;
		}else{
			$this->db->trans_commit();
			$msg=true;
		}
		return $msg;
	}
	public function createTable($name,$cols,$pk)
	{
		if(empty($name) || empty($cols) || empty($pk)) 
			return false; 
		$this->db->trans_begin();
		$this->dbforge->add_field($cols);
		$this->dbforge->add_key($pk, TRUE);
		$this->dbforge->create_table($name);
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=false;
		}else{
			$this->db->trans_commit();
			$msg=true;
		}
		return $msg;
	}
	public function dropTable($table)
	{
		if(empty($table)) 
			return false;
		$this->db->trans_begin();
		$this->dbforge->drop_table($table,TRUE);
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$msg=false;
		}else{
			$this->db->trans_commit();
			$msg=true;
		}
		return $msg;
	}

//others
	public function getCreateProperties($id)
	{
		if (empty($id)) 
			return null;
		$new_val=[
			'create_date'=>$this->otherfunctions->getDateNow(),
			'update_date'=>$this->otherfunctions->getDateNow(),
			'update_by'=>$id,
			'create_by'=>$id,
			'status'=>1,
		];
		return $new_val;
	}
	public function getUpdateProperties($id)
	{
		if (empty($id)) 
			return null;
		$new_val=[
			'update_date'=>$this->otherfunctions->getDateNow(),
			'update_by'=>$id,
		];
		return $new_val;
	}
	public function getNotification($usage = 'BO')
	{
		$date=$this->otherfunctions->getDateNow();
		return $this->db->get_where('notification',['status'=>TRUE,'untuk'=>$usage,'start <='=>$date,'end_date >='=>$date])->result();
	}
	public function getCaptchaExpired()
	{
		$expiration = time() - 1800;
		return $this->db->get_where('data_captcha',['captcha_time < ' => $expiration,'ip_address'=>$this->input->ip_address()])->result();
	}
	public function getGlobalTable($data, $status = 'active')
	{
		if(empty($data))
			return null;

		if(!empty($data['where'])){
			foreach ($data['where'] as $key => $value) {
				$this->db->where($key,$value);
			}
		}

		if(!empty($data['join'])){
			foreach ($data['join'] as $key => $value) {
				$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
			}
		}
		if($status == 'active'){ $this->db->where('status','1'); }
		$query = $this->db->get($data['table'])->result();
		return $query;
	}

	public function ObjectToArray($select,$table,$where = null)
	{
		/*$select/$where is array*/
		$select_a = implode(",",$select);
		$this->db->select($select_a);
		if(!empty($where)){ $this->db->where($where); }
		$datax = $this->db->get($table)->result();
		$new = [];
		$no = 0;
		$c_data = count($select);
		foreach ($datax as $d) {
			if($c_data>1){
				$newx = [];
				foreach ($select as $key => $value) {
					$newx[$value] = $d->$value;
				}
				$new[] = $newx;
			}else{
				$value = $select[0];
				$new[] = $d->$value;
			}
		}
		return $new;
	}
	public static function searchWhereCustom($arr,$col,$usage = 'OR',$alias=null)
    {
        if (empty($arr))
            return null;
        $max=count($arr);
        $c=1;
        $pack='(';
        foreach ($arr as $a) {
            $usage=($c < $max)? $usage : null; 
            $pack.=((!empty($alias))?$alias.'.':null).$col.' = "'.$a.'" '.$usage.' ';
            $c++;
        }
        $pack.=')';
        return $pack;
	}
	public function getFilterbyBagian($field_filter=false,$col='kode_bagian',$alias='jbt',$usage = 'OR')
	{		
		// print_r($this->filter);
		$bag_chain=$this->model_master->getBawahanBagian((($field_filter)?$field_filter:$this->filter));
		return $this->searchWhereCustom($bag_chain,$col,$usage,$alias);
	}
	function getMesin(){
		$this->db->select('*');
		$this->db->from('mesin_absen');
		$query=$this->db->get()->result();
		return $query;
	}
	function getDataShortUrl($where){
		$this->db->select('*');
		$this->db->from('short_url');
		$this->db->where($where);
		$query=$this->db->get()->row_array();
		return $query;
	}
// ============================= Whatapps =====================================
	
}
