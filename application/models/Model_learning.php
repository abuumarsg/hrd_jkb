<?php
/**
* 
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_learning extends CI_Model
{
	function __construct()
    {
        parent::__construct();
        $this->date = $this->otherfunctions->getDateNow();
    }
	public function getListMateriLearning($where=null,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, (select count(*) from learn_soal cnt where cnt.kode_materi = a.kode) as jumlah_soal, (select count(*) from learn_soal_project prj where prj.kode_materi = a.kode) as jumlah_soal_project, (select count(*) from learn_file_materi xx where xx.kode_materi = a.kode) as jumlah_materi');
		$this->db->from('learn_materi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('learn_soal AS d', 'd.kode_materi = a.kode', 'left');
		$this->db->join('learn_file_materi AS e', 'e.kode_materi = a.kode', 'left');
		$this->db->join('learn_soal_project AS f', 'f.kode_materi = a.kode', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->group_by('a.id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListSoalLearning($where=null,$row=false, $order='a.id desc', $limit = null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, (select count(*) from learn_soal cnt where cnt.kode_materi = a.kode) as jumlah_soal, e.nama as nama_materi');
		$this->db->from('learn_soal AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('learn_materi AS e', 'a.kode_materi = e.kode', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($limit)){
			$this->db->limit($limit);
		}
		$this->db->order_by($order);
		$this->db->group_by('a.id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getKodeSoalLearning($where=null,$row=false, $order='a.id desc', $limit = null)
	{
		$this->db->select('kode');
		$this->db->from('learn_soal');
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($limit)){
			$this->db->limit($limit);
		}
		$this->db->order_by($order);
		$this->db->group_by('id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListSoalProjectLearning($where=null,$row=false, $order='a.id desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, (select count(*) from learn_soal cnt where cnt.kode_materi = a.kode) as jumlah_soal, e.nama as nama_materi');
		$this->db->from('learn_soal_project AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('learn_materi AS e', 'a.kode_materi = e.kode', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->order_by($order);
		$this->db->group_by('a.id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListFileMateriLearning($where=null,$row=false, $order='a.id desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, e.nama as nama_materi');
		$this->db->from('learn_file_materi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('learn_materi AS e', 'a.kode_materi = e.kode', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->order_by($order);
		$this->db->group_by('a.id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListLearningKaryawan($where=null,$row=false, $order='a.id desc', $whereArray = null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, e.nama as nama_materi, emp.nama as nama_karyawan, emp.no_hp, jbt.nama as nama_jabatan, i.nama as nama_bagian, j.nama as nama_loker, (select count(*) from learn_soal cnt where cnt.kode_materi = e.kode) as jumlah_soal, (select count(*) from learn_soal_project prj where prj.kode_materi = e.kode) as jumlah_soal_project, (select count(*) from learn_file_materi xx where xx.kode_materi = e.kode) as jumlah_materi, e.waktu');
		$this->db->from('learn_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('learn_materi AS e', 'a.kode_materi = e.kode', 'left');
		$this->db->join('karyawan AS emp', 'a.id_karyawan = emp.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_bagian AS i', 'i.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS j', 'j.kode_loker = emp.loker', 'left');
		$this->db->join('learn_soal AS d', 'd.kode_materi = e.kode', 'left');
		$this->db->join('learn_file_materi AS ex', 'ex.kode_materi = e.kode', 'left');
		$this->db->join('learn_soal_project AS f', 'f.kode_materi = e.kode', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($whereArray)){
			$this->db->where($whereArray);
		}
		$this->db->order_by($order);
		$this->db->group_by('a.id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getDataKeikutsertaan($where=null,$row=false, $order='a.id desc', $limit = null)
	{
		$this->db->select('a.*,e.nama as nama_materi');
		$this->db->from('learn_keikutsertaan AS a');
		$this->db->join('learn_materi AS e', 'a.kode_materi = e.kode', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($limit)){
			$this->db->limit($limit);
		}
		$this->db->order_by($order);
		$this->db->group_by('a.id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getMaxIDKeikutsertaan($where=null)
	{
		$this->db->select('a.*,e.nama as nama_materi');
		$this->db->from('learn_keikutsertaan AS a');
		$this->db->join('learn_materi AS e', 'a.kode_materi = e.kode', 'left');
		$this->db->where("a.id = (SELECT max(id) FROM learn_keikutsertaan x WHERE x.id = a.id)");
		if(!empty($where)){
			$this->db->where($where);
		}
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getJawabanEmp($where=null,$row=false, $order='a.id desc', $limit = null)
	{
		$this->db->select('a.*,e.nama as nama_materi');
		$this->db->from('learn_jawaban_karyawan AS a');
		$this->db->join('learn_soal AS d', 'd.kode = a.kode_soal', 'left');
		$this->db->join('learn_materi AS e', 'e.kode = d.kode_materi', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($limit)){
			$this->db->limit($limit);
		}
		$this->db->order_by($order);
		$this->db->group_by('a.id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getJawabanProjectEmp($where=null,$row=false, $order='a.id desc', $limit = null)
	{
		$this->db->select('a.*, d.soal_project, e.nama as nama_materi,(select count(*) from learn_soal_project prj where prj.kode_materi = e.kode) as jumlah_soal_project');
		$this->db->from('learn_jawaban_project AS a');
		$this->db->join('learn_soal_project AS d', 'd.kode = a.kode_project', 'left');
		$this->db->join('learn_materi AS e', 'e.kode = d.kode_materi', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($limit)){
			$this->db->limit($limit);
		}
		$this->db->order_by($order);
		$this->db->group_by('a.id');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
}
