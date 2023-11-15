<?php
/**
* 
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_admin extends CI_Model
{
	function __construct()
    {
        parent::__construct();
		$this->load->model('model_master');
		$this->filter_admin=[];
		if ($this->session->has_userdata('adm')) {
			$id_adm = $this->session->userdata('adm')['id'];
			$data_admin=$this->getAdmin($id_adm);
			if (isset($data_admin[0])) {
				$this->filter_admin=[
					'list_filter_bagian'=>$this->otherfunctions->getParseOneLevelVar($data_admin[0]->list_filter_bagian),
					'filter_status'=>$this->model_master->checkAccessFilter($data_admin[0]->list_access),
					'kode_bagian'=>$data_admin[0]->kode_bagian,
				];
			}
		}
    }
	public function getFilter()
	{
		$pack=['list_bagian'=>null];
		if (count($this->filter_admin) > 0){
			if (isset($this->filter_admin['filter_status']) && isset($this->filter_admin['list_filter_bagian']) && isset($this->filter_admin['kode_bagian'])) {
				if ($this->filter_admin['filter_status'] && !empty($this->filter_admin['list_filter_bagian'])) {
					$pack['list_bagian']=$this->filter_admin['list_filter_bagian'];
				}elseif ($this->filter_admin['filter_status'] && empty($this->filter_admin['list_filter_bagian'])) {
					$pack['list_bagian']=$this->filter_admin['kode_bagian'];
				}
			}
		}
		return $pack;
	}
	public function getListAdmin()
	{
		$where=['a.id_admin !='=>1];
		$this->db->select('a.*,b.nama as nama_group');
		$this->db->from('admin AS a');
		$this->db->join('master_user_group AS b', 'b.id_group = a.id_group', 'left'); 
		$this->db->order_by('update_date','DESC');
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAdmin($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_group,d.list_access,d.list_bagian as list_filter_bagian,jbt.kode_bagian,emp.id_karyawan, d.list_bagian');
		$this->db->from('admin AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'inner'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'inner'); 
		$this->db->join('master_user_group AS d', 'd.id_group = a.id_group', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jbt.kode_bagian', 'left');
		$this->db->where('a.id_admin',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAdminRowArray($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_group,d.list_access,d.list_bagian as list_filter_bagian,jbt.kode_bagian');
		$this->db->from('admin AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'inner'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'inner'); 
		$this->db->join('master_user_group AS d', 'd.id_group = a.id_group', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jbt.kode_bagian', 'left');
		$this->db->where('a.id_admin',$id); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getAdminAllActive($id_admin=false)
	{
		$this->db->select('adm.*,jbt.nama as nama_jabatan');
		$this->db->from('admin AS adm');
		$this->db->join('karyawan AS kar', 'adm.id_karyawan = kar.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'kar.jabatan = jbt.kode_jabatan', 'left');
		$this->db->where('status_adm',1);
		if($id_admin){
			$this->db->where('id_admin !=',1);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAdminForSelect2()
	{
		$data=$this->getAdminAllActive(true);
		$pack=[];
		foreach ($data as $d) {
			$pack[$d->id_admin]=$d->nama.((!empty($d->nama_jabatan)) ? ' ('.$d->nama_jabatan.')' : '');
		}
		return $pack;
	}
	public function getLogLogin($id){
		if (empty($id)) 
			return null;
		return $this->db->get_where('log_login_admin',array('id_admin'=>$id))->result();
	}
	public function getAdminWhere($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_group,jbt.kode_bagian as kode_bagian');
		$this->db->from('admin AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'inner'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'inner'); 
		$this->db->join('master_user_group AS d', 'd.id_group = a.id_group', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jbt.kode_bagian', 'left');

		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAdminWhere2($where = null, $row = false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_group,jbt.kode_bagian as kode_bagian');
		$this->db->from('admin AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'inner'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'inner'); 
		$this->db->join('master_user_group AS d', 'd.id_group = a.id_group', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jbt.kode_bagian', 'left');
		if(!empty($where)){ $this->db->where($where); }
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}			
		return $query;
	}
    function list_admin(){
    	return $this->db->query("SELECT * FROM admin WHERE id_admin != '1'")->result();
    }
	function adm_cek($un,$pass){
		return $this->db->get_where('admin',array('username'=>$un,'password'=>$pass))->row_array();
	}
	function avl($un,$em){
		return $this->db->query('SELECT id_admin FROM admin WHERE username = ? OR email = ?',array($un,$em))->row_array();
	}
	function adm($id){
		return $this->db->get_where('admin',array('id_admin'=>$id))->row_array();
	}
	function forget_email($em){
		return $this->db->get_where('admin',array('email'=>$em))->row_array();
	}
	function log_login($id){
		return $this->db->get_where('log_login_admin',array('id_admin'=>$id))->result();
	}
	function list_adm($id){
		return $this->db->query('SELECT * FROM admin WHERE id_admin != ?', array($id))->result();
	}
	function ver($id,$tok){
		return $this->db->query('SELECT * FROM admin WHERE id_admin = ? AND email_token = ?',array($id,$tok))->row_array();
	}
	function res($t){
		return $this->db->get_where('admin',array('reset_token'=>$t))->row_array();
	}
	public function getAdminName($id)
	{
		$data=$this->db->get_where('admin',array('id_admin'=>$id))->row_array();
		if (isset($data)) {
			return $data['nama'];
		}else{
			return null;
		}
	}
	public function getAdminById($id)
	{
		return $this->db->get_where('admin',array('id_admin'=>$id))->row_array();
	}
	public function getAdminByEmail($email)
	{
		return $this->db->get_where('admin',array('email'=>$email))->row_array();
	}
	public function getAdminByToken($token)
	{
		return $this->db->get_where('admin',array('reset_token'=>$token))->row_array();
	}
	public function getListAdminActive()
	{
		return $this->model_global->listUserActiveRecord('admin','id_admin','nama','admin');
	}
}