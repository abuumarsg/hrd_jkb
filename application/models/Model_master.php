<?php
/**
* 
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_master extends CI_Model
{
	function __construct()
    {
        parent::__construct();
        $this->date = $this->otherfunctions->getDateNow();
    }
    //fix code
    //===SETTING BEGIN===//
    //Setting Bobot Sikap
    public function getListBobotSikap()
	{
		$this->db->order_by('id_bobot','ASC');
		$query=$this->db->get('master_bobot_sikap')->result();
		return $query;
	}
	public function getBobotSikap($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_bobot_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_bobot',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListBobotSikapActive()
	{
		return $this->model_global->listActiveRecord('master_bobot_sikap','kode_bobot','nama');
	}
	public function checkBobotSikapCode($code)
	{
		return $this->model_global->checkCode($code,'master_bobot_sikap','kode_bobot');
	}
	public function getBobotSikapKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_bobot_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('kode_bobot',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}

    //--------------------------------------------------------------------------------------------------------------//
	//Setting Konversi
	public function getListKonversiNilai()
	{
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get('master_konversi_nilai')->result();
		return $query;
	}
	public function getKonversiNilai($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_nilai AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_konversi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Tanggal Update Informasi Karyawan
	public function getListTglUpdate()
	{
		return $this->db->get('master_tgl_update_data')->result();
	}
	public function getTglUpdate($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_tgl_update_data AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_date',$id); 
		$query=$this->db->get()->result();
		return $query;
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Setting Hak Akses
	public function getListAccess($active=false)
	{
		if($active){
			$this->db->where('status',1); 
		}		
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get('master_access')->result();
		return $query;
	}
	public function getAccess($id){
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_access AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_access',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	
	public function checkAccessCode($code)
	{
		return $this->model_global->checkCode($code,'master_access','kode_access');
	}
	public function checkAccessFilter($access_list_id)
	{
		$ret=false;
		if (empty($access_list_id)) 
			return $ret;
		$list=$this->otherfunctions->getParseOneLevelVar($access_list_id);
		if (isset($list)) {
			foreach ($list as $e) {
				$acc=$this->getAccess($e);				
				if (isset($acc)) {
					foreach ($acc as $d) {
						$d->kode_access=strtoupper($d->kode_access);
						if ($d->kode_access == 'FTR' || $d->kode_access == 'FILTER'  || $d->kode_access == 'FLTR'  || $d->kode_access == 'FILTER DATA') {
							$ret=true;
						}
					}
				}
			}
		}
		return $ret;
	}
//======================================= EMAIL VALIDASI LEMBUR ================================================================
	public function getAccessValLembur($code_val){
		return $this->db->get_where('master_access',['kode_access'=>$code_val])->row_array();
	}
	public function getListUserGroupValLembur()
	{
		return $this->db->get_where('master_user_group',['status'=>'1'])->result();
	}
	public function getListAdminValLembur($id_user_group)
	{
		return $this->db->get_where('admin',['id_group'=>$id_user_group])->result();
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Setting User Group
	public function getListUserGroup()
	{
		$this->db->select('a.*');
		$this->db->from('master_user_group a');
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getUserGroupOne($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_user_group AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_group',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getUserGroup($id){
		return $this->db->get_where('master_user_group',array('id_group'=>$id))->row_array();
	}
	// public function getAccessFromUserGroup($id_group)
	// {
	// 	$pack=[];
	// 	if (empty($id_group)) 
	// 		return $pack;
	// 	$user_group=$this->getUserGroup($id_group);
	// 	if (!isset($user_group)) 
	// 		return $pack;
	// 	$ex=$this->otherfunctions->getParseOneLevelVar($user_group['list_access']);
	// 	if (!isset($ex)) 
	// 		return $pack;
	// 	foreach ($ex as $e) {
	// 		$acc=$this->getAccess($e);			
	// 		if (isset($acc)) {
	// 			foreach ($acc as $d) {
	// 				array_push($pack,$d->kode_access);
	// 			}
	// 		}
	// 	}
	// 	return $pack;
	// }
	//--------------------------------------------------------------------------------------------------------------//
	//Setting Menu Management 
	public function getListMenu()
	{
		$where=['a.id_menu !='=>0];
		$this->db->select('a.*,b.nama as parent_name,');
		$this->db->from('master_menu AS a');
		$this->db->join('master_menu AS b', 'b.id_menu = a.parent', 'inner'); 
		$this->db->order_by('a.create_date','DESC');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getMenu($id){
		return $this->db->get_where('master_menu',array('id_menu'=>$id,'status'=>1,'id_menu !='=>0))->row_array();
	}
	public function getAllMenubyId($id){
		$where=['a.id_menu'=>$id];
		$this->db->select('a.*,b.nama as parent_name,c.nama as nama_buat, d.nama as nama_update');
		$this->db->from('master_menu AS a');
		$this->db->join('master_menu AS b', 'b.id_menu = a.parent', 'inner'); 
		$this->db->join('admin AS c', 'c.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS d', 'd.id_admin = a.update_by', 'left'); 
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListMenuActive()
	{
		$this->db->order_by('sequence','ASC');
		$query=$this->db->get_where('master_menu',['status'=>1,'id_menu !='=>0])->result();
		return $query;
	}
	//Setting Menu User Admin Management 
	public function getListMenuUser()
	{
		$where=['a.id_menu !='=>0];
		$this->db->select('a.*,b.nama as parent_name,');
		$this->db->from('master_menu_user AS a');
		$this->db->join('master_menu_user AS b', 'b.id_menu = a.parent', 'inner'); 
		$this->db->order_by('a.create_date','DESC');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getMenuUser($id){
		return $this->db->get_where('master_menu_user',array('id_menu'=>$id,'status'=>1,'id_menu !='=>0))->row_array();
	}
	public function getAllMenuUserbyId($id){
		$where=['a.id_menu'=>$id];
		$this->db->select('a.*,b.nama as parent_name,c.nama as nama_buat, d.nama as nama_update');
		$this->db->from('master_menu_user AS a');
		$this->db->join('master_menu_user AS b', 'b.id_menu = a.parent', 'inner'); 
		$this->db->join('admin AS c', 'c.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS d', 'd.id_admin = a.update_by', 'left'); 
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListMenuUserActive()
	{
		$this->db->order_by('sequence','ASC');
		$query=$this->db->get_where('master_menu_user',['status'=>1,'id_menu !='=>0])->result();
		return $query;
	}
	public function getListUserMenuActive()
	{
		$this->db->order_by('sequence','ASC');
		$query=$this->db->get_where('master_menu_user',['status'=>1,'id_menu !='=>0])->result();
		return $query;
	}
//--------------------------------------------------------------------------------------------------------------//
//Setting User Group FO
	public function getListUserGroupUser()
	{
		$this->db->select('a.*');
		$this->db->from('master_user_group_user a');
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getUserGroupOneUser($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_user_group_user AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_group',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
// --------------------------------------------------------------------------------------------------------------------//
// Hak Akses User Group User
	public function getUserGroupUser($id){
		return $this->db->get_where('master_user_group_user',array('id_group'=>$id))->row_array();
	}
	// public function getAccessUser($id){
	// 	$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
	// 	$this->db->from('master_access AS a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
	// 	$this->db->where('a.id_access',$id); 
	// 	$query=$this->db->get()->result();
	// 	return $query;
	// }

	//--------------------------------------------------------------------------------------------------------------//
	//Setting Notifikasi
	public function getListNotif()
	{
		$this->db->select('a.*,b.nama as creator'); 
		$this->db->from('notification AS a');
		$this->db->join('admin AS b', 'a.create_by = b.id_admin', 'left');
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getNotif($id,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('notification AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_notif',$id);
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getNotifAdmin()
	{
		$where=['status'=>1,'start <='=>$this->date,'end_date >='=>$this->date,'untuk'=>'ADM'];
		$query=$this->db->get_where('notification',$where)->result();
		return $query;
	}
	public function getNotifEmployee()
	{
		$where=['status'=>1,'start <='=>$this->date,'end_date >='=>$this->date,'untuk'=>'FO'];
		$query=$this->db->get_where('notification',$where)->result();
		return $query;
	}
	public function checkNotifCode($code)
	{
		return $this->model_global->checkCode($code,'notification','kode_notif');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Setting Root Password
	public function getRootPassword($id)
	{
		return $this->db->get_where('root_password',['id'=>$id])->result();
	}
	public function getRootPasswordRow()
	{
		return $this->db->get_where('root_password',['id'=>1])->row_array();
	}
	//----------------------------------------------------------------------------------------------------------------//
	//Setting Company
	public function getDataCompany()
	{
		return $this->db->get_where('data_company_profile',['id'=>1])->row_array();
	}
	//--------------------------------------------------------------------------------------------------------------------//
	//Berita
	public function getListBerita()
	{
		$this->db->select('a.*,b.nama as nama_kategori');
		$this->db->from('data_berita as a'); 
		$this->db->join('master_kategori_berita AS b', 'b.id_kategori = a.kategori', 'left'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getBerita($id){
		$where=['a.id_berita'=>$id];
		$this->db->select('a.*,c.nama as nama_buat, d.nama as nama_update,e.nama as nama_kategori');
		$this->db->from('data_berita AS a');
		$this->db->join('admin AS c', 'c.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS d', 'd.id_admin = a.update_by', 'left'); 
		$this->db->join('master_kategori_berita AS e', 'e.id_kategori = a.kategori', 'left'); 
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListBeritaFO()
	{
		$this->db->select('a.*,b.nama as nama_kategori');
		$this->db->from('data_berita as a'); 
		$this->db->join('master_kategori_berita AS b', 'b.id_kategori = a.kategori', 'left'); 
		$this->db->order_by('create_date','DESC');
		$this->db->where('a.status',1);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListBeritaFOID($id)
	{
		$this->db->select('a.*,b.nama as nama_kategori');
		$this->db->from('data_berita as a'); 
		$this->db->join('master_kategori_berita AS b', 'b.id_kategori = a.kategori', 'left'); 
		$this->db->order_by('create_date','DESC');
		$this->db->where('a.status',1);
		$this->db->where('a.id_berita',$id);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListTanggalBeritaFO()
	{
		$sql_tanggal = "SELECT DISTINCT MONTHNAME(tgl_posting) as bulan, YEAR(tgl_posting) as tahun FROM data_berita order by tgl_posting DESC";
		$query = $this->db->query($sql_tanggal)->result();
		return $query;
	}
	public function jumlahTanggalBerita($bulan,$tahun)
	{
		$this->db->where('MONTHNAME(tgl_posting)', $bulan);
		$this->db->where('YEAR(tgl_posting)', $tahun);
		$this->db->where('status',1);
		$query=$this->db->get('data_berita');
		return $query;
	}
	public function dataBeritaPagination($limit, $start)
	{
		$this->db->select('a.*,b.nama as nama_kategori');
		$this->db->from('data_berita as a'); 
		$this->db->join('master_kategori_berita AS b', 'b.id_kategori = a.kategori', 'left'); 
		$this->db->order_by('create_date','DESC');
		$this->db->where('a.status',1);
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();
	}
	function beritaAktif(){
		return $this->db->query("SELECT * FROM data_berita WHERE status = 1 ORDER BY create_date DESC")->result();
	}
	//----------------------------------------------------------------------------------------------------------------//
	//Struktur Organisasi
	public function getListStruktur()
	{
		$this->db->select('a.*,b.nama as nama_lokasi');
		$this->db->from('data_struktur as a'); 
		$this->db->join('master_loker AS b', 'b.kode_loker = a.lokasi', 'left'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListStrukturFO()
	{
		$this->db->select('a.*,b.nama as nama_lokasi');
		$this->db->from('data_struktur as a'); 
		$this->db->join('master_loker AS b', 'b.kode_loker = a.lokasi', 'left'); 
		$this->db->order_by('create_date','DESC');
		$this->db->where('a.status',1);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getStruktur($id){
		$where=['a.id'=>$id];
		$this->db->select('a.*,c.nama as nama_buat, d.nama as nama_update,e.nama as nama_lokasi');
		$this->db->from('data_struktur AS a');
		$this->db->join('admin AS c', 'c.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS d', 'd.id_admin = a.update_by', 'left'); 
		$this->db->join('master_loker AS e', 'e.kode_loker = a.lokasi', 'left'); 
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//General Setting
	public function getGeneralSetting($kode)
	{
		return $this->db->get_where('general_settings',['kode'=>$kode])->row_array();
	}
    //===SETTING END===//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA KARYAWAN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Level Struktur
	public function getListLevelStruktur($active=true,$val=null)
	{
		$wh_view=[];
		if($val != null){
			$wh_view=['squence >'=>2];
		}
		$where=array_merge(['id_level_struktur !='=>1],$wh_view);
		$this->db->where($where);
		if($active){
			$this->db->where('status',1);
			$this->db->order_by('squence','DESC');
		}else{
			$this->db->order_by('update_date','DESC');
		}		
		$query=$this->db->get('master_level_struktur')->result();
		return $query;
	}
	public function getLevelStruktur($id)
	{		
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_level_struktur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_level_struktur',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkLevelStrukturCode($code)
	{
		return $this->model_global->checkCode($code,'master_level_struktur','kode_level_struktur');
	}
	public function getListLevelStrukturActive()
	{
		return $this->model_global->listActiveRecord('master_level_struktur','kode_level_struktur','nama');
	}
	public function getLevelSelect2Filter($level)
	{
		$levelAdmin = [];
		$data=$this->getListLevelJabatan();
		foreach($data as $d){
			if($level == '2' || $level == '3'){
				if($d->sequence >= 6){
					$levelAdmin[$d->kode_level_jabatan]=$d->nama;
				}
			}else{
				$levelAdmin[$d->kode_level_jabatan]=$d->nama;
			}
		}
		$pack=[];
		if(!empty($levelAdmin )){
			foreach ($levelAdmin as $kode_level_jabatan =>$nama) {
				$pack[$kode_level_jabatan]=$nama;
			}
		}
		return $pack;
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Level Jabatan
	public function getListLevelJabatan()
	{
		$where=['a.id_level_jabatan !='=>1,];
		$this->db->select('a.*,b.nama as nama_level_struktur,b.squence as sequence');
		$this->db->from('master_level_jabatan AS a');
		$this->db->where($where);
		$this->db->join('master_level_struktur AS b', 'b.kode_level_struktur = a.kode_level_struktur', 'left'); 
		$this->db->order_by('a.update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLevelJabatan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_level_struktur');
		$this->db->from('master_level_jabatan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_level_struktur AS d', 'd.kode_level_struktur = a.kode_level_struktur', 'left'); 
		$this->db->where('a.id_level_jabatan',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListLevelJabatanActive()
	{
		return $this->model_global->listActiveRecord('master_level_jabatan','kode_level_jabatan','nama');
	}
	public function checkLevelJabatanCode($code)
	{
		return $this->model_global->checkCode($code,'master_level_jabatan','kode_level_jabatan');
	}
	public function getJabatanEmployee($filter=0)
	{
		$this->db->select('a.*,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan, lok.nama as nama_loker');
		// $this->db->select('a.*,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan');
		$this->db->from('master_jabatan AS a'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'left');
		$this->db->join('karyawan as emp','emp.jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = emp.loker', 'left');
		$this->db->where('a.id_jabatan != ',1); 
		$this->db->where('a.id_jabatan != ',2); 
		$this->db->where('a.id_jabatan != ',3); 
		$this->db->where('a.status',1);
		if (!empty($filter)) {
		 	$this->db->where('a.kode_bagian',$filter);
		} 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		$pack=[];
		foreach ($query as $q) {
			$pack[$q->kode_jabatan]=$q->nama.' ('.$q->nama_bagian.') - ('.$q->nama_loker.')';
			// $pack[$q->kode_jabatan]=$q->nama.' ('.$q->nama_bagian.')';
		}
		return $pack;
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Bagian
	public function getListBagian($active=false)
	{
		$where=['id_bagian !='=>1, 'id_bagian != '=>2];
		$this->db->select('a.*,b.nama as nama_level_struktur,lok.nama as nama_loker');
		$this->db->from('master_bagian AS a');
		$this->db->join('master_level_struktur AS b', 'a.kode_level_struktur = b.kode_level_struktur', 'left'); 
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left'); 
		$this->db->where($where); 
		if ($active) {
			$this->db->where('a.status',1); 
			$this->db->order_by('nama','ASC');
		}else{
			$this->db->order_by('a.create_date','DESC');
		}
		$query=$this->db->get()->result();
		return $query;
		// $where=['a.id_bagian !='=>1, 'a.id_bagian != '=>2];
		// $this->db->select('a.*,b.nama as nama_level_struktur,c.nama as nama_loker,ats.nama as nama_atasan,ats_l.nama as nama_loker_atasan');
		// $this->db->from('master_bagian AS a');
		// $this->db->join('master_level_struktur AS b', 'a.kode_level_struktur = b.kode_level_struktur', 'left'); 
		// $this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		// $this->db->join('master_bagian AS ats', 'ats.kode_bagian = a.atasan', 'left');
		// $this->db->join('master_loker AS ats_l', 'ats_l.kode_loker = ats.kode_loker', 'left');  
		// $this->db->where($where); 
		// if($active){
		// 	$this->db->where('a.status',1);
		// 	$this->db->order_by('a.nama','ASC');
		// }else{
		// 	$this->db->order_by('a.create_date','DESC');
		// }
		// $query=$this->db->get()->result();
		// return $query;
		
	}
	public function getBagian($id, $where = null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_level_struktur,e.nama as nama_loker');
		$this->db->from('master_bagian AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_level_struktur AS d', 'd.kode_level_struktur = a.kode_level_struktur', 'left'); 
		$this->db->join('master_loker AS e', 'e.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('karyawan AS g', 'g.jabatan = f.kode_jabatan', 'left');
		if(!empty($id)){ $this->db->where('a.id_bagian',$id); } 
		if(!empty($where)){
			$this->db->where($where);
			$this->db->group_by('a.kode_bagian');
		}
		$query=$this->db->get()->result();
		return $query;
		// $this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_level_struktur,e.nama as nama_loker,ats.nama as nama_atasan,ats_l.nama as nama_loker_atasan');
		// $this->db->from('master_bagian AS a');
		// $this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		// $this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		// $this->db->join('master_level_struktur AS d', 'd.kode_level_struktur = a.kode_level_struktur', 'left'); 
		// $this->db->join('master_loker AS e', 'e.kode_loker = a.kode_loker', 'left');
		// $this->db->join('master_bagian AS ats', 'ats.kode_bagian = a.atasan', 'left'); 
		// $this->db->join('master_loker AS ats_l', 'ats_l.kode_loker = ats.kode_loker', 'left');  
		// if(!empty($id)){ $this->db->where('a.id_bagian',$id); } 
		// if(!empty($where)){ $this->db->where($where); }
		// $query=$this->db->get()->result();
		// return $query;
	}
	public function getBagianRow($id, $where = null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_level_struktur,e.nama as nama_loker,lok.nama as nama_lokasi');
		$this->db->from('master_bagian AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_level_struktur AS d', 'd.kode_level_struktur = a.kode_level_struktur', 'left'); 
		$this->db->join('master_loker AS e', 'e.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('karyawan AS emp', 'emp.jabatan = f.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS lok', 'lok.kode_loker = emp.loker', 'left');
		if(!empty($id)){ $this->db->where('a.id_bagian',$id); } 
		if(!empty($where)){ $this->db->where($where); }
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getBagianKode($kode)
	{
		$this->db->select('a.*,lok.nama as nama_loker');
		$this->db->from('master_bagian AS a');
		$this->db->join('master_loker AS lok', 'a.kode_loker = lok.kode_loker', 'left');
		$this->db->where('a.kode_bagian',$kode);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListBagianActive()
	{
		return $this->model_global->listActiveRecord('master_bagian','kode_bagian','nama');
	}
	public function checkBagianCode($code)
	{
		return $this->model_global->checkCode($code,'master_bagian','kode_bagian');
	}
	//======================================================= FOR FILTER DATA =====================================
	public function getFilterBagianSelect2($id=null,$all=null)
    {
		$pack=[];
        if (!empty($all)){
			$pack['all'] = 'Pilih Semua';
		}
        if (empty($id)){
			$data = $this->getListBagian(true);
			if (isset($data)){
				foreach ($data as $d){
					$pack[$d->kode_bagian]=$d->nama.(($d->nama_loker)?' ('.$d->nama_loker.')':null);
				}
			}
		}else{
			if (is_array($id)) {
				foreach ($id as $idb) {
					// $data=$this->getBawahanBagian($idb);
					// foreach ($data as $d) {
						// $bag=$this->getBagian(null,['kode_bagian'=>$d]);
						$bag=$this->getBagian(null,['a.kode_bagian'=>$idb]);
						if (isset($bag[0])){
							$pack[$bag[0]->kode_bagian]=$bag[0]->nama.(($bag[0]->nama_loker)?' ('.$bag[0]->nama_loker.')':null);
						}
					// }
				}
			}else{
				// $data=$this->getBawahanBagian($id);
				// foreach ($data as $d) {
					// $bag=$this->getBagian(null,['kode_bagian'=>$d]);
					$bag=$this->getBagian(null,['a.kode_bagian'=>$id]);
					if (isset($bag[0])){
						$pack[$bag[0]->kode_bagian]=$bag[0]->nama.(($bag[0]->nama_loker)?' ('.$bag[0]->nama_loker.')':null);
					}
				// }
			}
		}         
        return $pack;
    }
	public function getBawahanBagian($id)
    {
        if (empty($id))
            return null;
        $pack=[];
        if (is_array($id)) {
            foreach ($id as $idb) {
                // $jbt=$this->getJabatanBagian($idb);
                $jbt=$this->getJabatanPerBagian($idb);        
                if ($jbt != '') {
                    if (isset($jbt)) {
                        foreach ($jbt as $j) {
							// $d_jbt=$this->getJabatanKodeRow($j);
							$d_jbt=$this->getJabatanKodeRow($j->kode_jabatan);
							if(isset($d_jbt)){
								$pack[]=$d_jbt['kode_bagian'];
							}
                        } 
                    }            
                }
            }
			array_merge($pack,$id);
        }else {
            // $jbt=$this->getJabatanBagian($id);
			$jbt=$this->getJabatanPerBagian($id); 
            if ($jbt != '') {
                if (isset($jbt)) {
                    foreach ($jbt as $j) {
						// $d_jbt=$this->getJabatanKodeRow($j);
                        $d_jbt=$this->getJabatanKodeRow($j->kode_jabatan);
						if(isset($d_jbt)){
							$pack[]=$d_jbt['kode_bagian'];
						}
                    } 
                }            
            }
			array_push($pack,$id);
		}
        return array_filter(array_unique($pack));
	}
	public function getJabatanBagian($idb)
    {
        if (empty($idb)) 
            return null;
        $datx=$this->getListJabatanFilter(true);
        $res = $this->getListJabatanWhere(['a.kode_bagian'=>$idb],null,1);
        $bag=[];
        foreach ($res as $r) {
            $bag[]=$this->getBawahanFilter($datx,$r->kode_jabatan);
        }
        $new=implode(';',$bag);
        $new=explode(';',$new);
        return array_filter(array_unique($new));
    }
	public function getJabatanBagianWhere($where)
    {
        if (empty($where)) 
            return null;
        $datx=$this->getListJabatanFilter(true);
        $res = $this->getListJabatanWhere($where,null,1);
        $bag=[];
        foreach ($res as $r) {
            $bag[]=$this->getBawahanFilter($datx,$r->kode_jabatan);
        }
        $new=implode(';',$bag);
        $new=explode(';',$new);
        return array_filter(array_unique($new));
    }
	public function getBawahanFilter($data,$id)
    {
        if (empty($data)) 
            return null;
        $arr=$id;
        foreach ($data as $v) {
            if ($v->atasan == $id) {
                if ($this->cek_bawahan($data,$v->kode_jabatan)){
                    $arr.=';'.$this->getBawahanFilter($data,$v->kode_jabatan);
                }else{
                    $arr.=';'.$v->kode_jabatan;
                }
            }
        }
        return $arr;
    }
    public function cek_bawahan($data,$id)
    {
        foreach ($data as $d) {
            if ($d->atasan == $id){
				return true;       
			}else{
				break;
			}     
        }
        return false;
	}
	public function getFilterJabatanSelect2($id_b)
    {
		$pack=[];
        if (empty($id_b)) { 
			$data=$this->getListJabatan();
			if (isset($data)){
				foreach ($data as $dt){
					$loker=(($dt->nama_lokasi)?' - ('.$dt->nama_lokasi.')':null);
					$pack[$dt->kode_jabatan]=$dt->nama.(($dt->nama_bagian)?' ('.$dt->nama_bagian.')':null).$loker;
				}
			}
		}else{
			if (is_array($id_b)) {
				foreach ($id_b as $idb) {
					// $data_b=$this->getBawahanBagian($idb);
					// if (isset($data_b)) {
					// 	foreach ($data_b as $id) {
							// $jbt=$this->getJabatanBagian($id);
							$jbt=$this->getJabatanPerBagian($idb);
							if (isset($jbt)) {
								foreach ($jbt as $j) {
									// $jabatan=$this->getJabatanKodeRow($j);
                        			$jabatan=$this->getJabatanKodeRow($j->kode_jabatan);
									if (isset($jabatan)) {
										$loker=(($jabatan['nama_lokasi'])?' - ('.$jabatan['nama_lokasi'].')':null);
										$pack[$jabatan['kode_jabatan']]=$jabatan['nama'].(($jabatan['nama_bagian'])?' ('.$jabatan['nama_bagian'].')':null).$loker;
									}                            
								} 
							}
					// 	}
					// }
				}
			}else{
				// $data_b=$this->getBawahanBagian($id_b);
				// if (isset($data_b)) {
				// 	foreach ($data_b as $id) {
						// $jbt=$this->getJabatanBagian($id);
						$jbt=$this->getJabatanPerBagian($idb);
						if (isset($jbt)) {
							foreach ($jbt as $j) {
								// $jabatan=$this->getJabatanKodeRow($j);
								$jabatan=$this->getJabatanKodeRow($j->kode_jabatan);
								if (isset($jabatan)) {
									$loker=(($jabatan['nama_lokasi'])?' - ('.$jabatan['nama_lokasi'].')':null);
									$pack[$jabatan['kode_jabatan']]=$jabatan['nama'].(($jabatan['nama_bagian'])?' ('.$jabatan['nama_bagian'].')':null).$loker;
								}                            
							} 
						}
				// 	}
				// }
			} 
		}           
        return $pack;
    }
	// ============================================ END FILTER DATA ================================================
    public function getJabatanPerBagian($kode_bagian)
    {
		$this->db->select('kode_jabatan');
		$this->db->from('master_jabatan');
		$this->db->where('status',1); 
		$this->db->where('kode_bagian',$kode_bagian); 
		$query=$this->db->get()->result();
		return $query;
    }
	public function getJabatanBagianKar($idb)
    {
        if (empty($idb)) 
            return null;
        $datx=$this->getListJabatanFilter(true);
        $res = $this->getListJabatanWhere(['a.kode_bagian'=>$idb],null,1);
        $bag=[];
        foreach ($res as $r) {
            $bag[]=$r->kode_jabatan;
        }
        $new=implode(';',$bag);
        $new=explode(';',$new);
        return array_filter(array_unique($new));
    }
	//--------------------------------------------------------------------------------------------------------------//
	//Master Jabatan
	public function getListJabatan($tipe=null,$select=null)
	{
		$where=['a.id_jabatan !='=>1, 'a.id_jabatan != '=>2, 'a.id_jabatan != '=>3];
		$this->db->select('a.*,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan,g.nama as nama_loker,i.nama as nama_loker_atasan,j.nama as nama_user_group,lok.nama as nama_lokasi');
		$this->db->from('master_jabatan AS a'); 
		$this->db->join('karyawan AS emp', 'emp.jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS lok', 'lok.kode_loker = emp.loker', 'left');
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'inner');
		$this->db->join('master_loker AS g', 'g.kode_loker = d.kode_loker', 'left');
		$this->db->join('master_bagian AS h', 'h.kode_bagian = f.kode_bagian', 'left');
		$this->db->join('master_loker AS i', 'i.kode_loker = h.kode_loker', 'left');
		$this->db->join('master_user_group_user AS j', 'j.id_group = a.id_group_user', 'left');
		if($tipe=='tipe_jabatan'){
			$this->db->where('a.tipe_jabatan','GOL1');
		}
		if($select=='select'){
			$this->db->order_by('nama','ASC');
		}
		if($select==null || $tipe==null){
			$this->db->order_by('update_date','DESC');
		}
		$this->db->where($where); 
		$this->db->group_by('a.id_jabatan'); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getJabatan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan,g.nama as nama_loker,i.nama as nama_loker_atasan,j.nama as nama_user_group');
		$this->db->from('master_jabatan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'inner'); 
		$this->db->join('master_loker AS g', 'g.kode_loker = d.kode_loker', 'left'); 
		$this->db->join('master_bagian AS h', 'h.kode_bagian = f.kode_bagian', 'left');
		$this->db->join('master_loker AS i', 'i.kode_loker = h.kode_loker', 'left');
		$this->db->join('master_user_group_user AS j', 'j.id_group = a.id_group_user', 'left');
		$this->db->where('a.id_jabatan',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getJabatanKodeRow($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan,f.kode_jabatan as kode_atasan,d.kode_bagian, lok.nama as nama_lokasi');
		$this->db->from('master_jabatan AS a');
		$this->db->join('karyawan AS emp', 'emp.jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS lok', 'lok.kode_loker = emp.loker', 'left');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'inner'); 
		$this->db->where('a.kode_jabatan',$kode); 
		$query=$this->db->get()->row_array();
		// $this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan');
		// $this->db->from('master_jabatan AS a');
		// $this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		// $this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		// $this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		// $this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		// $this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'left'); 
		// $this->db->where('a.kode_jabatan',$kode); 
		// $query=$this->db->get()->row_array();
		return $query;
	}
	public function getJabatanWhere($where,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan,f.kode_jabatan as kode_atasan,d.kode_bagian, lok.nama as nama_lokasi');
		$this->db->from('master_jabatan AS a');
		$this->db->join('karyawan AS emp', 'emp.jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS lok', 'lok.kode_loker = emp.loker', 'left');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'inner'); 
		$this->db->where($where); 
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListJabatanActive()
	{
		return $this->model_global->listActiveRecord('master_jabatan','kode_jabatan','nama');
	}
	public function checkJabatanCode($code)
	{
		return $this->model_global->checkCode($code,'master_jabatan','kode_jabatan');
	}
	public function getBagianForSelect2()
	{
		$data=$this->getListBagian();
		$pack=[];
		foreach ($data as $d) {
			$pack[$d->kode_bagian]=$d->nama.((!empty($d->nama_loker)) ? ' ('.$d->nama_loker.')' : '');
		}
		return $pack;
	}
	public function getAtasanForSelect2()
	{
		$data=$this->getListJabatan('tipe_jabatan');
		$pack=[];
		foreach ($data as $d) {
			$pack[$d->kode_jabatan]=$d->nama.((!empty($d->nama_loker)) ? ' ('.$d->nama_loker.')' : '');
		}
		return $pack;
	}
	public function getRefreshBagian()
	{
		$pack=$this->getAtasanForSelect2();
		if (isset($pack[null])) {
			unset($pack[null]);
		}
		return $pack;
	}
	public function getJabatanForSelect2()
	{
		$data=$this->getListJabatan(null,'select');
		$pack=[];
		foreach ($data as $d) {
			$pack[$d->kode_jabatan]=$d->nama.((!empty($d->nama_loker)) ? ' ('.$d->nama_loker.')' : '');
		}
		return $pack;
	}
	public function getJabatanBawahan($jabatan){
		return $this->db->get_where('master_jabatan',array('atasan'=>$jabatan))->result();
	}
	public function getListJabatanFilter($active = false)
	{
		$this->db->select('a.*,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan,g.nama as nama_user_group');
		$this->db->from('master_jabatan AS a'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'left');
		$this->db->join('master_user_group_user AS g', 'g.id_group = a.id_group_user', 'left');
		$this->db->where('a.id_jabatan != ',1); 
		$this->db->where('a.id_jabatan != ',2); 
		$this->db->where('a.id_jabatan != ',3); 
		if ($active){
			$this->db->where('a.status',1);
			$this->db->order_by('a.nama','ASC');
		}else{
			$this->db->order_by('a.update_date','DESC');
		}		
		$query=$this->db->get()->result();
		return $query;
	}
    public function getJabatanAll()
    {
		$this->db->select('*');
		$this->db->from('master_jabatan');
		$this->db->where('status',1); 
		$query=$this->db->get()->result();
		return $query;
    }
	public function getDataBawahan($data, $kode_jabatan)
    {
        if (empty($data))
            return null;
		$arr=$kode_jabatan;
        foreach ($data as $v) {
            if ($v->atasan == $kode_jabatan) {
                if ($this->bawah($data,$v->kode_jabatan)){
                    $arr.=';'.$this->getDataBawahan($data,$v->kode_jabatan);
                }else{
                    $arr.=';'.$v->kode_jabatan;
                }
            }
        }
        return $arr;
    }
    public function bawah($data,$kode_jabatan)
    {
        foreach ($data as $d) {
            if ($d->atasan == $kode_jabatan)
                return true;            
        }
        return false;
    }
	public function getAtasan($kode)
	{
		$query=$this->getJabatanKodeRow($kode);
		return $query['atasan'];
	}
	public function getBawahan($kode)
	{
		$query=$this->db->get_where('master_jabatan',['atasan'=>$kode])->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Lokasi Kerja
	public function getListLoker()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('master_loker')->result();
		return $query;
	}
	public function getLoker($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_loker AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_loker',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListLokerActive()
	{
		return $this->model_global->listActiveRecord('master_loker','kode_loker','nama');
	}
	public function checkLokerCode($code)
	{
		return $this->model_global->checkCode($code,'master_loker','kode_loker');
	}
	public function getLokerKodeArray($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_loker AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('kode_loker',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getLokerLike($like)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_loker AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.status','1');
		$this->db->like('a.kode_loker',$like); 
		$query=$this->db->get()->row_array();
		return $query;
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Status Karyawan
	public function getListStatusKaryawan()
	{
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get('master_status_karyawan')->result();
		return $query;
	}
	public function getStatusKaryawan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_status_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');  
		$this->db->where('id_status_karyawan',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListStatusKaryawanActive()
	{
		return $this->model_global->listActiveRecord('master_status_karyawan','kode_status','nama');
	}
	public function checkStatusKaryawanCode($code)
	{
		return $this->model_global->checkCode($code,'master_status_karyawan','kode_status');
	}

//-------------------------------- MASTER GRADE ------------------------------------------------------------------------------//
//Master Induk Grade
	public function getListIndukGrade()
	{
		$this->db->select('a.*,(select count(*) from master_grade cnt where cnt.kode_induk_grade = a.kode_induk_grade) as jum');
		$this->db->from('master_induk_grade AS a');
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getIndukGrade($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_induk_grade AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where('id_induk_grade',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkIndukGradeCode($code)
	{
		return $this->model_global->checkCode($code,'master_induk_grade','kode_induk_grade');
	}
	// public function getListGradeKode($kode)
	// {
	// 	$this->db->select('a.*,b.nama as nama_dokumen,c.nama as nama_loker,d.nama as nama_induk_grade');
	// 	$this->db->from('master_grade AS a');
	// 	$this->db->join('master_dokumen AS b', 'a.kode_dokumen = b.kode_dokumen', 'left'); 
	// 	$this->db->join('master_loker AS c', 'a.kode_loker = c.kode_loker', 'left');
	// 	$this->db->join('master_induk_grade AS d', 'd.kode_induk_grade = a.kode_induk_grade', 'left');
	// 	$this->db->where('d.kode_induk_grade',$kode);
	// 	$this->db->order_by('create_date','DESC');
	// 	$query=$this->db->get()->result();
	// 	return $query;
	// }
	public function getListGradeKode($kode, $usage = 'induk_grade')
	{
		$this->db->select('a.*,b.nama as nama_dokumen, c.nama as nama_loker,d.nama as nama_induk_grade');
		$this->db->from('master_grade AS a');
		$this->db->join('master_dokumen AS b', 'a.kode_dokumen = b.kode_dokumen', 'left'); 
		$this->db->join('master_loker AS c', 'a.kode_loker = c.kode_loker', 'left');
		$this->db->join('master_induk_grade AS d', 'd.kode_induk_grade = a.kode_induk_grade', 'left');
		if($usage == 'induk_grade'){
			$this->db->where('d.kode_induk_grade',$kode);
		}elseif($usage == 'grade'){
			$this->db->where('a.kode_grade',$kode);
		}
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	//Master Grade
	public function getListGrade($order=null)
	{
		$this->db->select('a.*,b.nama as nama_dokumen,c.nama as nama_loker,d.nama as nama_induk_grade');
		$this->db->from('master_grade AS a');
		$this->db->join('master_dokumen AS b', 'a.kode_dokumen = b.kode_dokumen', 'left'); 
		$this->db->join('master_loker AS c', 'a.kode_loker = c.kode_loker', 'left'); 
		$this->db->join('master_induk_grade AS d', 'd.kode_induk_grade = a.kode_induk_grade', 'left');
		if($order==null){
			$this->db->order_by('create_date','DESC');
		}else{
			$this->db->order_by($order['kolom'],$order['value']);
			$this->db->order_by($order['kolom2'],$order['value']);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getGrade($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_dokumen,e.nama as nama_loker,f.nama as nama_induk_grade');
		$this->db->from('master_grade AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_dokumen AS d', 'd.kode_dokumen = a.kode_dokumen', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_induk_grade AS f', 'f.kode_induk_grade = a.kode_induk_grade', 'left');
		$this->db->where('id_grade',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getGradeKode($kode)
	{
		$this->db->select('nama');
		$this->db->from('master_grade');
		$this->db->where('kode_grade',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListGradeActive()
	{
		return $this->model_global->listActiveRecord('master_grade','kode_grade','nama');
	}
	public function checkGradeCode($code)
	{
		return $this->model_global->checkCode($code,'master_grade','kode_grade');
	}
	public function getMasterGradeForSelect2()
	{
		$data=$this->getMasterGradeAllActive();
		$pack=[];
		foreach ($data as $d) {
			$pack[$d->kode_grade]=$d->nama.((!empty($d->nama_loker)) ? ' ('.$d->nama_loker.')' : '');
		}
		return $pack;
	}
	public function getMasterGradeAllActive()
	{
		$this->db->select('a.*,b.nama as nama_loker');
		$this->db->from('master_grade AS a');
		$this->db->join('master_loker AS b', 'a.kode_loker = b.kode_loker', 'left');
		$this->db->where('a.status',1);
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkDataGrade($where)
	{
		if (empty($where))
			return false;
		$this->db->where($where);
		$query=$this->db->get('master_grade')->num_rows();
		if ($query > 0) {
			return true;
		}else{
			return false;
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Sistem Penggajian
	public function getListSistemPenggajian()
	{
		// $where=['id_master_penggajian !='=>1];
		$this->db->select('*');
		$this->db->from('master_sistem_penggajian');
		// $this->db->where($where); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getSistemPenggajian($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_sistem_penggajian AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_master_penggajian',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListSistemPenggajianActive()
	{
		return $this->model_global->listActiveRecord('master_sistem_penggajian','kode_master_penggajian','nama');
	}
	public function checkSistemPenggajianCode($code)
	{
		return $this->model_global->checkCode($code,'master_sistem_penggajian','kode_master_penggajian');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Mutasi Promosi Demosi
	public function getListMutasiKaryawan()
	{
		$this->db->select('a.*,b.nama as nama_dokumen');
		$this->db->from('master_mutasi AS a');
		$this->db->join('master_dokumen AS b', 'a.kode_dokumen = b.kode_dokumen', 'left'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getMutasiKaryawan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_dokumen');
		$this->db->from('master_mutasi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_dokumen AS d', 'd.kode_dokumen = a.kode_dokumen', 'left'); 
		$this->db->where('id_m_mutasi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListMutasiKaryawanActive()
	{
		return $this->model_global->listActiveRecord('master_mutasi','kode_mutasi','nama');
	}
	public function checkMutasiKaryawanCode($code)
	{
		return $this->model_global->checkCode($code,'master_mutasi','kode_mutasi');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Daftar RS
	public function getListDaftarRS()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('master_daftar_rs')->result();
		return $query;
	}
	public function getDaftarRS($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_daftar_rs AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_master_rs',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListDaftarRSActive()
	{
		return $this->model_global->listActiveRecord('master_daftar_rs','kode_master_rs','nama');
	}
	public function checkDaftarRSCode($code)
	{
		return $this->model_global->checkCode($code,'master_daftar_rs','kode_master_rs');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Kategori Kecelakaan
	public function getListKategoriKecelakaan()
	{
		// $this->db->order_by('update_date','DESC');
		// $query=$this->db->get('master_kategori_kecelakaan')->result();
		// return $query;
		$this->db->select('a.*,b.nama as nama_dokumen');
		$this->db->from('master_kategori_kecelakaan AS a');
		$this->db->join('master_dokumen AS b', 'a.kode_dokumen = b.kode_dokumen', 'left'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKategoriKecelakaan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_dokumen');
		$this->db->from('master_kategori_kecelakaan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_dokumen AS d', 'd.kode_dokumen = a.kode_dokumen', 'left');  
		$this->db->where('id_kategori_kecelakaan',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListKategoriKecelakaanActive()
	{
		return $this->model_global->listActiveRecord('master_kategori_kecelakaan','kode_kategori_kecelakaan','nama');
	}
	public function checkKategoriKecelakaanCode($code)
	{
		return $this->model_global->checkCode($code,'master_kategori_kecelakaan','kode_kategori_kecelakaan');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Surat Peringatan
	public function getListSuratPeringatan()
	{
		$this->db->select('a.*,b.nama as nama_dokumen');
		$this->db->from('master_surat_peringatan AS a');
		$this->db->join('master_dokumen AS b', 'a.kode_dokumen = b.kode_dokumen', 'left'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getSuratPeringatan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_dokumen');
		$this->db->from('master_surat_peringatan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_dokumen AS d', 'd.kode_dokumen = a.kode_dokumen', 'left');   
		$this->db->where('id_sp',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListSuratPeringatanActive()
	{
		return $this->model_global->listActiveRecord('master_surat_peringatan','kode_sp','nama');
	}
	public function checkSuratPeringatanCode($code)
	{
		return $this->model_global->checkCode($code,'master_surat_peringatan','kode_sp');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Surat Perjanjian
	public function getListSuratPerjanjian()
	{
		$this->db->select('a.*,b.nama as nama_dokumen,c.nama as nama_status_karyawan');
		$this->db->from('master_surat_perjanjian AS a');
		$this->db->join('master_dokumen AS b', 'a.kode_dokumen = b.kode_dokumen', 'left'); 
		$this->db->join('master_status_karyawan AS c', 'a.kode_status = c.kode_status', 'left');
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getSuratPerjanjian($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_dokumen,e.nama as nama_status_karyawan');
		$this->db->from('master_surat_perjanjian AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_dokumen AS d', 'd.kode_dokumen = a.kode_dokumen', 'left');   
		$this->db->join('master_status_karyawan AS e', 'a.kode_status = e.kode_status', 'left');
		$this->db->where('id_perjanjian',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListSuratPerjanjianActive()
	{
		return $this->model_global->listActiveRecord('master_surat_perjanjian','kode_perjanjian','nama');
	}
	public function checkSuratPerjanjianCode($code)
	{
		return $this->model_global->checkCode($code,'master_surat_perjanjian','kode_perjanjian');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Kelompok Shift
	public function getListKelompokShift()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('master_kelompok_shift')->result();
		return $query;
	}
	public function getKelompokShift($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_kelompok_shift AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_shift',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListKelompokShiftActive()
	{
		return $this->model_global->listActiveRecord('master_kelompok_shift','kode_shift','nama');
	}
	public function checkKelompokShiftCode($code)
	{
		return $this->model_global->checkCode($code,'master_kelompok_shift','kode_shift');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Bank
	public function getListBank()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('master_bank')->result();
		return $query;
	}
	public function getBank($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_bank AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_bank',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListBankActive()
	{
		return $this->model_global->listActiveRecord('master_bank','kode','nama');
	}
	public function checkBankCode($code)
	{
		return $this->model_global->checkCode($code,'master_bank','kode');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Alasan Keluar
	public function getListAlasanKeluar()
	{
		$this->db->select('a.*,b.nama as nama_dokumen,c.nama as nama_dokumen_keterangan');
		$this->db->from('master_alasan_keluar AS a');
		$this->db->join('master_dokumen AS b', 'a.kode_dokumen = b.kode_dokumen', 'left'); 
		$this->db->join('master_dokumen AS c', 'a.dokumen_keterangan = c.kode_dokumen', 'left'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAlasanKeluar($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_dokumen,e.nama as nama_dokumen_keterangan');
		$this->db->from('master_alasan_keluar AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_dokumen AS d', 'd.kode_dokumen = a.kode_dokumen', 'left');
		$this->db->join('master_dokumen AS e', 'a.dokumen_keterangan = e.kode_dokumen', 'left');
		$this->db->where('id_alasan_keluar',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAlasanKeluarKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_dokumen,e.nama as nama_dokumen_keterangan');
		$this->db->from('master_alasan_keluar AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_dokumen AS d', 'd.kode_dokumen = a.kode_dokumen', 'left');
		$this->db->join('master_dokumen AS e', 'a.dokumen_keterangan = e.kode_dokumen', 'left');
		$this->db->where('kode_alasan_keluar',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListAlasanKeluarActive()
	{
		return $this->model_global->listActiveRecord('master_alasan_keluar','kode_alasan_keluar','nama');
	}
	public function checkAlasanKeluarCode($code)
	{
		return $this->model_global->checkCode($code,'master_alasan_keluar','kode_alasan_keluar');
	}

	//===MASTER DATA KARYAWAN END===//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA ABSENSI BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Ijin Cuti
	public function getListMasterIzin()
	{
		$this->db->select('a.*,b.nama as nama_master_penggajian,c.nama as nama_dokumen');
		$this->db->from('master_izin AS a');
		$this->db->join('master_sistem_penggajian AS b', 'a.kode_master_penggajian = b.kode_master_penggajian', 'left'); 
		$this->db->join('master_dokumen AS c', 'a.kode_dokumen = c.kode_dokumen', 'left'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getMasterIzin($id = null, $where = null, $row = null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_sistem_penggajian,e.nama as nama_dokumen');
		$this->db->from('master_izin AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_sistem_penggajian AS d', 'a.kode_master_penggajian = d.kode_master_penggajian', 'left'); 
		$this->db->join('master_dokumen AS e', 'a.kode_dokumen = e.kode_dokumen', 'left');   
		if(!empty($id)){ $this->db->where('id_master_izin',$id); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($row)){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListMasterIzinActive()
	{
		return $this->model_global->listActiveRecord('master_izin','kode_master_izin','nama');
	}
	public function checkMasterIzinCode($code)
	{
		return $this->model_global->checkCode($code,'master_izin','kode_master_izin');
	}
	public function getMasterIzinForSelect2()
	{
		$data=$this->getListMasterIzin();
		$pack=[];
		foreach ($data as $d) {
			$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis);
			$pack[$d->kode_master_izin]=$d->nama.((!empty($d->jenis)) ? ' ('.$nama_jenis.')' : '');
		}
		return $pack;
	}
	public function getMasterIzinJenis($kode)
	{
		$this->db->select('*');
		$this->db->from('master_izin');
		if(!empty($kode)){ $this->db->where('kode_master_izin',$kode); }
		$query=$this->db->get()->row_array();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Shift
	public function getListMasterShift()
	{
		$this->db->select('*');
		$this->db->from('master_shift'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getMasterShift($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_shift AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');  
		$this->db->where('id_master_shift',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListMasterShiftActive()
	{
		return $this->model_global->listActiveRecord('master_shift','kode_master_shift','nama');
	}
	public function checkMasterShiftCode($code)
	{
		return $this->model_global->checkCode($code,'master_shift','kode_master_shift');
	}
	public function getMasterShiftKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_shift AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');  
		$this->db->where('kode_master_shift',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListMasterShiftAk($custom = null)
	{
		$this->db->select('*');
		$this->db->from('master_shift'); 
		$this->db->order_by('create_date','DESC');
		$this->db->where('status',1); 
		if($custom != null){
			$this->db->where('kode_master_shift !=','CSTM');
		}
		$query=$this->db->get()->result();
		return $query;
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Hari Libur
	public function getListHariLibur()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('master_hari_libur')->result();
		return $query;
	}
	public function getHariLibur($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_hari_libur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_hari_libur',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getHariLiburTanggal($tanggal)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_hari_libur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('YEAR(a.tanggal)', $tanggal);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListHariLiburActive()
	{
		return $this->model_global->listActiveRecord('master_hari_libur','kode_hari_libur','nama');
	}
	public function checkHariLiburCode($code)
	{
		return $this->model_global->checkCode($code,'master_hari_libur','kode_hari_libur');
	}
	public function getHariLiburKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_hari_libur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('kode_hari_libur',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function cekHariLibur($val,$usage)
	{
		// $usage is date/kode
		if(empty($val) || empty($usage))
			return null;

		$new_val = null;
		$new_usage = ($usage == 'date') ? 'tanggal' : 'kode_hari_libur';
		$data =  $this->getListHariLibur();
		foreach ($data as $d) {
			if($val == $d->$new_usage){
				$new_val = $d->nama;
			}
		}
		return $new_val;
	}
	public function cekHariLiburActive($val,$usage)
	{
		// $usage is date/kode
		if(empty($val) || empty($usage))
			return null;

		$new_val = null;
		$new_usage = ($usage == 'date') ? 'tanggal' : 'kode_hari_libur';
		$data =  $this->getListHariLibur();
		foreach ($data as $d) {
			if($val == $d->$new_usage && $d->status == '1'){
				$new_val = $d->nama;
			}
		}
		return $new_val;
	}
	public function cekHariLiburDate($date)
	{ 
		$this->db->select('*');
		$this->db->from('master_hari_libur');
		$this->db->where('status',1); 
		$this->db->where('tanggal', $date); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Cuti Bersama
	public function getCutiBersamaTanggal($tanggal,$aktif=true)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_cuti_bersama AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('YEAR(a.tanggal)', $tanggal);
		if($aktif){
			$this->db->where('a.status', 1);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getCutiBersama($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_cuti_bersama AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getHistoryResetCuti($where, $row = false, $order = null)
	{
		$this->db->select('*');
		$this->db->from('history_reset_cuti');
		$this->db->where($where);
		if(!empty($order)){
			$this->db->order_by($order);
		}
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Kode Akun
	public function getMasterKodeAkun($id=null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pd_kode_akun AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if(!empty($id)){
			$this->db->where('id_kode_akun',$id); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Kendaraan Perjalanan Dinas
	public function getListKendaraanDinas()
	{
		$this->db->select('a.*,(select count(*) from master_pd_bbm cnt where cnt.kode_kendaraan = a.kode) as jum');
		$this->db->from('master_pd_kendaraan AS a'); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKendaraanDinas($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pd_kendaraan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where('id_pd_kendaraan',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListKendaraanDinasActive()
	{
		return $this->model_global->listActiveRecord('master_pd_kendaraan','kode','nama');
	}
	public function checkKendaraanDinasCode($code)
	{
		return $this->model_global->checkCode($code,'master_pd_kendaraan','kode');
	}	
	public function getKendaraanKode($kode)
	{
		$this->db->select('nama');
		$this->db->from('master_pd_kendaraan');
		$this->db->where('kode',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	//========== INTENSIF BBM ==================	
	public function getListBBMKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_kendaraan');
		$this->db->from('master_pd_bbm AS a');
		$this->db->join('master_pd_kendaraan AS b', 'a.kode_kendaraan = b.kode', 'left');
		$this->db->where('a.kode_kendaraan',$kode);
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getBBM($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_kendaraan');
		$this->db->from('master_pd_bbm AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_pd_kendaraan AS d', 'a.kode_kendaraan = d.kode', 'left');
		$this->db->where('id_pd_bbm',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function cekKendaraanJenisJarakAwal($kendaraan,$jenis=null,$jAwal)
	{
		if (empty($kendaraan) || empty($jAwal)) 
			return false;
		$this->db->from('master_pd_bbm');
		$this->db->where('kode_kendaraan',$kendaraan);
		if(!empty($jenis)){
			$this->db->where('j_k_umum',$jenis);
		}
		if(!empty($jAwal)){
			$this->db->where('jarak_awal <=',$jAwal);
			$this->db->where('jarak_akhir >=',$jAwal);
		}
		$data=$this->db->get_where()->num_rows();
		if ($data > 0) {
			return 'ada';
		}else{
			return 'tidak';
		}
	}
	public function cekKendaraanJenisJarakAkhir($kendaraan,$jenis=null,$jAkhir)
	{
		if (empty($kendaraan) || empty($jAkhir)) 
			return false;
		$this->db->from('master_pd_bbm');
		$this->db->where('kode_kendaraan',$kendaraan);
		$this->db->where('j_k_umum',$jenis);
		if(!empty($jAkhir)){
			$this->db->where('jarak_awal <=',$jAkhir);
			$this->db->where('jarak_akhir >=',$jAkhir);
		}
		$data=$this->db->get_where()->num_rows();
		if ($data > 0) {
			return 'ada';
		}else{
			return 'tidak';
		}
	}
	//====================== KATEGORI TUNJANGAN PERJALANAN DINAS ====================
	//
	public function getListKategoriDinas($select=null)
	{
		$this->db->select('a.*,(select count(*) from master_pd_detail_kategori cnt where cnt.kode_kategori = a.kode) as jum');
		$this->db->from('master_pd_kategori AS a');
		if(isset($select)){
			$this->db->where('kode !=',$select); 
		}
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKategoriDinas($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pd_kategori AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where('id_pd_kategori',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKategoriDinasWhere($where=null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pd_kategori AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where('a.status',1); 
		if(!empty($where)){
			$this->db->where($where); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getTipeHotelWhere($where=null,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_tipe_hotel AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if(!empty($where)){
			$this->db->where($where); 
		}
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getJenisPerdinWhere($where=null,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pd_jenis_perdin AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if(!empty($where)){
			$this->db->where($where); 
		}
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getKategoriDinasKode($kode)
	{
		$this->db->select('nama');
		$this->db->from('master_pd_kategori');
		$this->db->where('kode',$kode); 
		$this->db->where('status',1); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListKategoriDinasActive()
	{
		return $this->model_global->listActiveRecord('master_pd_kategori','kode','nama');
	}
	public function checkKategoriDinasCode($code)
	{
		return $this->model_global->checkCode($code,'master_pd_kategori','kode');
	}
	//========== DETAIL KATEGORI TUNJANGAN ==================	
	public function getListDKTKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_kategori,c.nama as nama_grade,d.nama as nama_loker');
		$this->db->from('master_pd_detail_kategori AS a');
		$this->db->join('master_pd_kategori AS b', 'a.kode_kategori = b.kode', 'left');
		$this->db->join('master_grade AS c', 'a.grade = c.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'd.kode_loker = c.kode_loker', 'left');
		$this->db->where('a.kode_kategori',$kode);
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDKT($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_kategori,e.nama as nama_grade,f.nama as nama_loker');
		$this->db->from('master_pd_detail_kategori AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_pd_kategori AS d', 'a.kode_kategori = d.kode', 'left');
		$this->db->join('master_grade AS e', 'a.grade = e.kode_grade', 'left');
		$this->db->join('master_loker AS f', 'f.kode_loker = e.kode_loker', 'left');
		$this->db->where('id_pd_detail',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function cekGradeKode($grade,$kode,$tempat)
	{
		if (empty($grade) || empty($kode)) 
			return false;
		$data=$this->db->get_where('master_pd_detail_kategori',['grade'=>$grade,'kode_kategori'=>$kode,'tempat'=>$tempat])->num_rows();
		$datax=$this->db->get_where('master_pd_detail_kategori',['grade'=>$grade,'kode_kategori'=>$kode,'tempat'=>$tempat])->row_array();
		if ($data > 0) {
			return $data=['val'=>'ada','data'=>$datax['kode']];
		}else{
			return $data=['val'=>'tidak','data'=>$datax['kode']];
		}
	}
	public function getDataGradePerDin($kode_kategori=null,$order=null)
	{
		$this->db->select('a.*,b.kode_kategori as kode_kategori,b.tempat as tempat,b.nominal as nominal,b.keterangan as keterangan,c.nama as nama_kategori,d.nama as nama_loker');
		$this->db->from('master_grade AS a');
		$this->db->join('master_pd_detail_kategori AS b', 'b.grade = a.kode_grade', 'left');
		$this->db->join('master_pd_kategori AS c', 'c.kode = b.kode_kategori', 'left');
		$this->db->join('master_loker AS d', 'd.kode_loker = a.kode_loker', 'left');
		if(!empty($kode_kategori)){
			$this->db->where('b.kode_kategori',$kode_kategori);
		}
		if($order!=null){
			$this->db->order_by($order['kolom'],$order['value']);
			$this->db->order_by($order['kolom2'],$order['value']);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkDataGradePerDin($where)
	{
		if (empty($where))
			return false;
		$this->db->where($where);
		$query=$this->db->get('master_pd_detail_kategori')->num_rows();
		if ($query > 0) {
			return true;
		}else{
			return false;
		}
	}
	// ====================== JARAK ANTAR PLANT =====================
	public function getListJarakPlant()
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_plant_asal,e.nama as nama_plant_tujuan');
		$this->db->from('master_pd_jarak_plant as a'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_loker AS d', 'd.kode_loker = a.plant_asal', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = a.plant_tujuan', 'left');
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getJarakPlant($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_plant_asal,e.nama as nama_plant_tujuan');
		$this->db->from('master_pd_jarak_plant as a'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_loker AS d', 'd.kode_loker = a.plant_asal', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = a.plant_tujuan', 'left');
		$this->db->where('id_jarak_plant',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkJarakPlantCode($code)
	{
		return $this->model_global->checkCode($code,'master_pd_jarak_plant','kode');
	}	
	public function jarakAntarPlant2($asal,$tujuan)
	{
		$this->db->select('jarak');
		$this->db->from('master_pd_jarak_plant');
		$this->db->where('plant_asal',$asal);
		$this->db->where('plant_tujuan',$tujuan); 
		$query=$this->db->get()->row_array();
		return $query;
	}	
	public function jarakAntarPlant($asal,$tujuan)
	{
		$dt=$this->jarakAntarPlant2($asal,$tujuan);
		if($dt==null){
			$this->db->select('jarak');
			$this->db->from('master_pd_jarak_plant');
			$this->db->where('plant_asal',$tujuan);
			$this->db->where('plant_tujuan',$asal); 
			$query=$this->db->get()->row_array();
		}else{
			$query=$dt;
		}
		return $query;
	}	
	// ====================== KODE AKUN PERJALANAN DINAS =====================
	public function getListKodeAkun($where = null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pd_kode_akun as a'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKodeAkunID($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pd_kode_akun as a'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where('id_kode_akun',$id); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkCodeAkun($code)
	{
		return $this->model_global->checkCode($code,'master_pd_kode_akun','kode_akun');
	}
	public function getKodeAkunAktif()
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pd_kode_akun as a'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where('a.status',1); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKodeAkunForSelect2()
	{
		$data=$this->getKodeAkunAktif();
		$pack=[];
		foreach ($data as $d) {
			$pack[$d->kode_akun]=$d->kode_akun.' - '.$d->nama;
		}
		return $pack;
	}
	//===MASTER DATA ABSESNI END===//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA PENGGAJIAN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Taruf Lembur
	public function getListTarifLembur()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('master_tarif_lembur')->result();
		return $query;
	}
	public function getTarifLembur($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_tarif_lembur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_tarif_lembur',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getTarifLemburJenis($where=null)
	{
		$this->db->select('*');
		$this->db->from('master_tarif_lembur');
		if($where!=null){$this->db->where($where);}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getTarifLemburJenisRow($where=null)
	{
		$this->db->select('*');
		$this->db->from('master_tarif_lembur');
		if($where!=null){$this->db->where($where);}
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListTarifLemburActive()
	{
		return $this->model_global->listActiveRecord('master_tarif_lembur','kode_tarif_lembur','nama');
	}
	public function checkTarifLemburCode($code)
	{
		return $this->model_global->checkCode($code,'master_tarif_lembur','kode_tarif_lembur');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Tarif UMK
	public function getListTarifUmk()
	{
		$this->db->select('a.*,b.nama as nama_loker');
		$this->db->from('master_tarif_umk AS a');
		$this->db->join('master_loker AS b', 'b.kode_loker = a.loker', 'left'); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListTarifUmkActive()
	{
		return $this->model_global->listActiveRecord('master_tarif_umk','kode_tarif_umk','nama');
	}
	public function checkTarifUmkCode($code)
	{
		return $this->model_global->checkCode($code,'master_tarif_umk','kode_tarif_umk');
	}
	//===MASTER DATA PENGGAJIAN END===//
	//=================================================BLOCK CHANGE=================================================//
	//
	//===MASTER DATA PENGGAJIAN END===//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA DOKUMEN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Dokumen
	public function getListDokumen()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('master_dokumen')->result();
		return $query;
	}
	public function getDokumen($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_dokumen AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_dokumen',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListDokumenActive()
	{
		return $this->model_global->listActiveRecord('master_dokumen','kode_dokumen','nama');
	}
	public function checkDokumenCode($code)
	{
		return $this->model_global->checkCode($code,'master_dokumen','kode_dokumen');
	}
	public function cekDokumen($kode){
		return $this->db->get_where('master_dokumen',['kode_dokumen'=>$kode])->row_array();
	}
	//===MASTER DATA DOKUMEN END===//
//=================================================BLOCK CHANGE=================================================//
//===MASTER DATA PENILAIAN BEGIN===//
//--------------------------------------------------------------------------------------------------------------//
//Master Batasan Poin
	public function getListBatasanPoin($active = false)
	{
		$this->db->select('a.*');
		$this->db->from('master_jenis_batasan_poin AS a');
		if ($active) {
			$this->db->where('a.status',1); 
		}
		$this->db->order_by('update_date','DESC'); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getBatasanPoin($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_jenis_batasan_poin AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');  
		$this->db->where('id_batasan_poin',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getBatasanPoinKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_jenis_batasan_poin AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');  
		$this->db->where('kode_batasan_poin',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListBatasanPoinActive()
	{
		return $this->model_global->listActiveRecord('master_jenis_batasan_poin','kode_batasan_poin','nama');
	}
	public function checkBatasanPoinCode($code)
	{
		return $this->model_global->checkCode($code,'master_jenis_batasan_poin','kode_batasan_poin');
	}

	//Master Indikator
	// public function getListKpi()
	// {
	// 	$this->db->select('a.*,b.nama as nama_bagian');
	// 	$this->db->from('master_kpi AS a');
	// 	$this->db->join('master_bagian AS b', 'a.kode_bagian = b.kode_bagian', 'left');
	// 	$this->db->order_by('create_date','DESC'); 
	// 	$query=$this->db->get()->result();
	// 	return $query;
	// }
	// public function getKpi($id)
	// {
	// 	$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bidang');
	// 	$this->db->from('master_kpi AS a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
	// 	$this->db->join('master_bagian AS b', 'a.kode_bagian = b.kode_bagian', 'left'); 
	// 	$this->db->where('id_kpi',$id); 
	// 	$query=$this->db->get()->result();
	// 	return $query;
	// }
	// public function getListKpiActive()
	// {
	// 	return $this->model_global->listActiveRecord('master_kpi','kode_kpi','kpi');
	// }
	// public function checkKpiCode($code)
	// {
	// 	return $this->model_global->checkCode($code,'master_kpi','kode_kpi');
	// }

	// ============================ MASTER KPI ===============================
	public function getListKpi($active = false)
	{
		$this->db->select('a.*,bp.nama as nama_batasan_poin');
		$this->db->from('master_kpi AS a');
		$this->db->join('master_jenis_batasan_poin AS bp', 'bp.id_batasan_poin = a.id_jenis_batasan_poin', 'left'); 
		if ($active) {
			$this->db->where('a.status',1); 
			$this->db->order_by('kpi','ASC');
		}else{
			$this->db->order_by('update_date','DESC');
		}		
		//$this->db->join('master_bagian AS b', 'a.kode_bagian = b.kode_bagian', 'left');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKpi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,rms.nama as nama_rumus,bp.nama as nama_batasan_poin');
		$this->db->from('master_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_rumus AS rms', 'rms.function = a.cara_menghitung', 'left'); 
		$this->db->join('master_jenis_batasan_poin AS bp', 'bp.id_batasan_poin = a.id_jenis_batasan_poin', 'left'); 
		$this->db->where('id_kpi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKpiKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,rms.nama as nama_rumus,bp.nama as nama_batasan_poin');
		$this->db->from('master_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_rumus AS rms', 'rms.function = a.cara_menghitung', 'left'); 
		$this->db->join('master_jenis_batasan_poin AS bp', 'bp.id_batasan_poin = a.id_jenis_batasan_poin', 'left'); 
		$this->db->where('kode_kpi',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getJenisKpi($jenis)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,rms.nama as nama_rumus');
		$this->db->from('master_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_rumus AS rms', 'rms.function = a.cara_menghitung', 'left'); 
		$this->db->where('a.status',1); 
		$this->db->where('a.jenis',$jenis); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataJenisKpi($jenis)
	{
		$query=$this->getJenisKpi($jenis);
		$pack=[];
		foreach ($query as $q) {
			$pack[$q->kode_kpi]=$q->kpi;
		}
		return $pack;
	}
	public function getListKpiActive()
	{
		return $this->model_global->listActiveRecord('master_kpi','kode_kpi','kpi');
	}
	public function checkKpiCode($code)
	{
		return $this->model_global->checkCode($code,'master_kpi','kode_kpi');
	}
	public function getKpiWhere($where)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,rms.nama as nama_rumus,bp.nama as nama_batasan_poin');
		$this->db->from('master_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_rumus AS rms', 'rms.function = a.cara_menghitung', 'left'); 
		$this->db->join('master_jenis_batasan_poin AS bp', 'bp.id_batasan_poin = a.id_jenis_batasan_poin', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}

//--------------------------------------------------------------------------------------------------------------//
//Master Form Aspek
	public function getListFormAspek()
	{
		$this->db->select('a.*');
		$this->db->from('master_form_aspek AS a');
		$this->db->order_by('update_date','DESC'); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getFormAspek($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_form_aspek AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_form',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListFormAspekActive()
	{
		return $this->model_global->listActiveRecord('master_form_aspek','kode_form','nama');
	}
	public function checkFormAspekCode($code)
	{
		return $this->model_global->checkCode($code,'master_form_aspek','kode_form');
	}

//--------------------------------------------------------------------------------------------------------------//
//Master Aspek Sikap
	public function getListAspek()
	{
		$this->db->select('a.*,COUNT(k.kode_kuisioner) as jumlah_kuisioner');
		$this->db->from('master_aspek_sikap AS a');
		$this->db->join('master_kuisioner AS k', 'a.kode_aspek = k.kode_aspek', 'left');
		$this->db->group_by('a.id_aspek'); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAspek($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,COUNT(d.kode_kuisioner) as jumlah_kuisioner');
		$this->db->from('master_aspek_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_kuisioner AS d', 'a.kode_aspek = d.kode_aspek', 'left');
		$this->db->where('id_aspek',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAspekKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,COUNT(d.kode_kuisioner) as jumlah_kuisioner');
		$this->db->from('master_aspek_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_kuisioner AS d', 'a.kode_aspek = d.kode_aspek', 'left');
		$this->db->where('a.kode_aspek',$kode); 
		$this->db->group_by('a.kode_aspek,a.id_aspek'); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListAspekActive()
	{
		return $this->model_global->listActiveRecord('master_aspek_sikap','kode_aspek','nama');
	}
	public function checkAspekCode($code)
	{
		return $this->model_global->checkCode($code,'master_aspek_sikap','kode_aspek');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Kuisioner
	public function getListKuisioner($kode_aspek)
	{
		$this->db->select('a.*,b.nama as nama_periode,d.nama as nama_aspek');
		$this->db->from('master_kuisioner AS a');
		$this->db->join('master_aspek_sikap AS d', 'a.kode_aspek = d.kode_aspek', 'left');
		$this->db->join('master_periode_penilaian AS b', 'b.kode_periode = a.kode_periode', 'left');
		$this->db->where('a.kode_aspek',$kode_aspek);  
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKuisionerActive($kode_aspek,$tipe,$periode=false)
	{
		$this->db->select('a.*,b.nama as nama_periode,d.nama as nama_aspek');
		$this->db->from('master_kuisioner AS a');
		$this->db->join('master_aspek_sikap AS d', 'a.kode_aspek = d.kode_aspek', 'left');
		$this->db->join('master_periode_penilaian AS b', 'b.kode_periode = a.kode_periode', 'left');
		$this->db->where('a.kode_aspek',$kode_aspek);  
		$this->db->where('a.kode_tipe',$tipe);  
		if ($periode) {
			$this->db->where('a.kode_periode',$periode); 
		}		 
		$this->db->where('a.status',1);  
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKuisionerActiveKodeAspek($kode_aspek)
	{
		$this->db->select('a.*,d.nama as nama_aspek');
		$this->db->from('master_kuisioner AS a');
		$this->db->join('master_aspek_sikap AS d', 'a.kode_aspek = d.kode_aspek', 'left');
		$this->db->where('a.kode_aspek',$kode_aspek);  
		$this->db->where('a.status',1);  
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}

	public function getKuisioner($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_aspek,e.nama as nama_periode');
		$this->db->from('master_kuisioner AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_aspek_sikap AS d', 'a.kode_aspek = d.kode_aspek', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.kode_periode', 'left');
		$this->db->where('a.id_kuisioner',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKuisionerKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_aspek,e.nama as nama_periode');
		$this->db->from('master_kuisioner AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_aspek_sikap AS d', 'a.kode_aspek = d.kode_aspek', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.kode_periode', 'left');
		$this->db->where('a.kode_kuisioner',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListKuisionerActive()
	{
		return $this->model_global->listActiveRecord('master_kuisioner','kode_kuisioner','kuisioner');
	}
	public function checkKuisionerCode($code)
	{
		return $this->model_global->checkCode($code,'master_kuisioner','kode_kuisioner');
	}

//--------------------------------------------------------------------------------------------------------------//
//Master Periode Penilaian
	public function getListPeriodePenilaian($status = 0)
	{  
		if($status){
			$this->db->where('status',$status); 
			$this->db->order_by('end','ASC');
		}else{
			$this->db->order_by('update_date','DESC');
		}
		
		$query=$this->db->get('master_periode_penilaian')->result();
		return $query;
	}
	public function getPeriodePenilaian($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_periode_penilaian AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_periode',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPeriodePenilaianKode($kode,$active = true)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_periode_penilaian AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.kode_periode',$kode); 
		if($active){
			$this->db->where('a.status',1); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListPeriodePenilaianActive()
	{
		$data=$this->getListPeriodePenilaian();
		$pack=[];
		foreach ($data as $d) {
			if ($d->status == 1) {
				$pack[$d->kode_periode]=$d->nama.' ('.((!empty($d->start))?$this->formatter->getMonth()[$this->otherfunctions->addFrontZero($d->start)] : null).((!empty($d->end))? ' s/d '.$this->formatter->getMonth()[$this->otherfunctions->addFrontZero($d->end)].')' : null);
			}
		}
		ksort($pack);
		return $pack;
	}
	public function checkPeriodePenilaianCode($code)
	{
		return $this->model_global->checkCode($code,'master_periode_penilaian','kode_periode');
	}
	public function getPeriodePenilaianMonth($month,$active = true)
	{
		if (empty($month)) 
			return null;
		$month=(int)$month;
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_periode_penilaian AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.start <= '.$month.' AND a.end >= '.$month); 
		if($active){
			$this->db->where('a.status',1); 
		}
		$query=$this->db->get()->row_array();
		return $query;
	}
//--------------------------------------------------------------------------------------------------------------//
//Master Konversi KPI
	public function getListKonversiKpi($active = false)
	{  
		if ($active) {
			$this->db->where('status',1);
			$this->db->order_by('max','DESC');
		}else{
			$this->db->order_by('update_date','DESC');
		}		
		$query=$this->db->get('master_konversi_kpi')->result();
		return $query;
	}
	public function getKonversiKpi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_konversi_kpi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListKonversiKpiActive()
	{
		return $this->model_global->listActiveRecord('master_konversi_kpi','kode_konversi_kpi','nama');
	}
	public function checkKonversiKpiCode($code)
	{
		return $this->model_global->checkCode($code,'master_konversi_kpi','kode_konversi_kpi');
	}
	public function getListKonversiKpiJenis($jenis)
	{
		if (!empty($jenis)) {
			$jenis=strtoupper($jenis);
		}
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.jenis_kpi',$jenis); 
		$this->db->where('a.status',1); 
		$this->db->order_by('a.min','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiKpiNilai($val,$batas = 2)
	{
		$val=number_format($val,$batas);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.status',1); 
		$this->db->where('a.min <=',$val); 
		$this->db->where('a.max >=',$val); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getMaxKonversiKpi($usage='periode')
	{
		$sql1="SELECT MAX(max) as max FROM master_konversi_kpi AND status = 1";
		$query1=$this->db->query($sql1)->row_array();
		$query1['max']=(isset($query1['max']))?$query1['max']:0;
		$max=$query1['max'];
		if($usage == 'tahunan'){
			$s_sql="SELECT * FROM master_periode_penilaian WHERE status = 1";
			$q=$this->db->query($s_sql)->num_rows();
			if($q > 0){
				$max=$max*$q;
			}
		}
		return $max;
	}
//--------------------------------------------------------------------------------------------------------------//
//Master Konversi Sikap
	public function getListKonversiSikap($active = 0)
	{  
		$this->db->select('*');
		$this->db->from('master_konversi_sikap');
		if ($active) {
			$this->db->where('status',1); 
			$this->db->order_by('min','DESC');
		}else{
			$this->db->order_by('update_date','DESC');
		}		
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiSikap($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_konversi_sikap',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListKonversiSikapActive()
	{
		return $this->model_global->listActiveRecord('master_konversi_sikap','kode_konversi_sikap','nama');
	}
	public function checkKonversiSikapCode($code)
	{
		return $this->model_global->checkCode($code,'master_konversi_sikap','kode_konversi_sikap');
	}
	public function getKonversiSikapVal($val,$batas = 2)
	{
		$val=number_format($val,$batas);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.min <=',$val); 
		$this->db->where('a.max >=',$val); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getMaxKonversiSikap($usage = 'periode')
	{
		$sql="SELECT MAX(max) as max FROM master_konversi_sikap WHERE status = 1";
		$query=$this->db->query($sql)->row_array();
		$query['max']=(isset($query['max']))?$query['max']:0;
		$max=$query['max'];
		if($usage == 'tahunan'){
			$s_sql="SELECT * FROM master_periode_penilaian WHERE status = 1";
			$q=$this->db->query($s_sql)->num_rows();
			if($q > 0){
				$max=$max*$q;
			}
		}
		return $max;
	}

//--------------------------------------------------------------------------------------------------------------//
//Master Konversi Presensi
	public function getListKonversiPresensi($active=0)
	{  
		$this->db->select('*');
		$this->db->from('master_konversi_presensi');
		if (!empty($active)) {
			$this->db->where('status',1);
			$this->db->order_by('jenis','DESC');
			$this->db->order_by('nilai','DESC');			
		}else{
			$this->db->order_by('update_date','DESC');
		}		
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiPresensi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_presensi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_konversi_presensi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiPresensiNilai($val,$jenis,$batas = 2)
	{
		$val=number_format($val,$batas);
		$this->db->select('a.nilai,a.nama');
		$this->db->from('master_konversi_presensi AS a');
		$this->db->where('a.min <=',$val); 
		$this->db->where('a.max >=',$val); 
		$this->db->where('a.jenis',$jenis); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListKonversiPresensiActive()
	{
		return $this->model_global->listActiveRecord('master_konversi_presensi','kode_konversi_presensi','nama');
	}
	public function checkKonversiPresensiCode($code)
	{
		return $this->model_global->checkCode($code,'master_konversi_presensi','kode_konversi_presensi');
	}
	public function getMaxKonversiPresensi($usage='periode')
	{
		$sql1="SELECT MAX(nilai) as max FROM master_konversi_presensi WHERE jenis = 0 AND status = 1";
		$query1=$this->db->query($sql1)->row_array();
		$query1['max']=(isset($query1['max']))?$query1['max']:0;
		$sql2="SELECT MAX(nilai) as max FROM master_konversi_presensi WHERE jenis = 1 AND status = 1";
		$query2=$this->db->query($sql1)->row_array();
		$query2['max']=(isset($query2['max']))?$query2['max']:0;
		$max=$query1['max']+$query2['max'];
		if($usage == 'tahunan'){
			$s_sql="SELECT * FROM master_periode_penilaian WHERE status = 1";
			$q=$this->db->query($s_sql)->num_rows();
			if($q > 0){
				$max=$max*$q;
			}
		}
		return $max;
	}
	public function getMaxPeriode($usage = 'periode')
	{
		$max=1;
		if($usage == 'tahunan'){
			$s_sql="SELECT * FROM master_periode_penilaian WHERE status = 1";
			$q=$this->db->query($s_sql)->num_rows();
			if($q > 0){
				$max=$max*$q;
			}
		}
		return $max;
	}
//--------------------------------------------------------------------------------------------------------------//
//Master Konversi Kuartal
	public function getListKonversiKuartal($active = 0)
	{  
		$this->db->select('*');
		$this->db->from('master_konversi_kuartal');
		if ($active) {
			$this->db->where('status',1);
			$this->db->order_by('min','DESC');
		}else{
			$this->db->order_by('update_date','ASC');
		}		
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiKuartal($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_kuartal AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_konversi_kuartal',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiKuartalNilai($val,$batas = 2)
	{
		$val=number_format($val,$batas);
		$this->db->select('a.*');
		$this->db->from('master_konversi_kuartal AS a');
		$this->db->where('a.min <=',$val); 
		$this->db->where('a.max >=',$val);  
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListKonversiKuartalActive()
	{
		return $this->model_global->listActiveRecord('master_konversi_kuartal','kode_konversi_kuartal','nama');
	}
	public function checkKonversiKuartalCode($code)
	{
		return $this->model_global->checkCode($code,'master_konversi_kuartal','kode_konversi_kuartal');
	}

//--------------------------------------------------------------------------------------------------------------//
//Master Konversi Tahunan
	public function getListKonversiTahunan($active = 0)
	{  
		$this->db->select('*');
		$this->db->from('master_konversi_tahunan');
		if ($active) {
			$this->db->where('status',1);
			$this->db->order_by('min','DESC');
		}else{
			$this->db->order_by('update_date','ASC');
		}		
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiTahunan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_tahunan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_konversi_tahunan',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiTahunanNilai($val,$batas = 2)
	{
		$val=number_format($val,$batas);
		$this->db->select('a.*');
		$this->db->from('master_konversi_tahunan AS a');
		$this->db->where('a.min <=',$val); 
		$this->db->where('a.max >=',$val);  
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListKonversiTahunanActive()
	{
		return $this->model_global->listActiveRecord('master_konversi_tahunan','kode_konversi_tahunan','nama');
	}
	public function checkKonversiTahunanCode($code)
	{
		return $this->model_global->checkCode($code,'master_konversi_tahunan','kode_konversi_tahunan');
	}

//--------------------------------------------------------------------------------------------------------------//
//Master Konversi Insentif
	public function getListKonversiInsentif($active = 0)
	{  
		$this->db->select('*');
		$this->db->from('master_konversi_insentif');
		if ($active) {
			$this->db->where('status',1);
			$this->db->order_by('urutan','ASC');
		}else{
			$this->db->order_by('update_date','ASC');
		}		
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiInsentif($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_insentif AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_konversi_insentif',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiInsentifNilai($val,$batas = 2)
	{
		$val=number_format($val,$batas);
		$this->db->select('a.*');
		$this->db->from('master_konversi_insentif AS a');
		$this->db->where('a.min <=',$val); 
		$this->db->where('a.max >=',$val);  
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListKonversiInsentifActive()
	{
		return $this->model_global->listActiveRecord('master_konversi_insentif','kode_konversi_insentif','nama');
	}
	public function checkKonversiInsentifCode($code)
	{
		return $this->model_global->checkCode($code,'master_konversi_insentif','kode_konversi_insentif');
	}

//--------------------------------------------------------------------------------------------------------------//
//Master Konversi GAP
	public function getListKonversiGap($usage='all')
	{  
		if ($usage == 'active') {
			$this->db->where('status',1);
			$this->db->order_by('update_date','ASC');
		}else{
			$this->db->order_by('min','ASC');			
		}			
		$query=$this->db->get('master_konversi_gap')->result();
		return $query;
	}
	public function getKonversiGap($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_konversi_gap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_konversi_gap',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonversiGapNilai($val,$batas = 2)
	{
		$val=number_format($val,$batas);
		$this->db->select('a.*');
		$this->db->from('master_konversi_gap AS a');
		$this->db->where('a.min <=',$val); 
		$this->db->where('a.max >=',$val);  
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListKonversiGapActive()
	{
		return $this->model_global->listActiveRecord('master_konversi_gap','kode_konversi_gap','nama');
	}
	public function checkKonversiGapCode($code)
	{
		return $this->model_global->checkCode($code,'master_konversi_gap','kode_konversi_gap');
	}	
	public function getTtdRaport($kode_bagian,$kode_loker)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_level_jabatan as a');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_level = a.kode_level_jabatan', 'left'); 
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_departement AS dep', 'dep.kode_departement = bag.kode_departement', 'left'); 
		$this->db->join('karyawan AS emp', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = emp.loker', 'left'); 
		$this->db->where('a.status','1');		
		$this->db->where('bag.kode_bagian',$kode_bagian);		
		$this->db->where('lok.kode_loker',$kode_loker);		
		$query = $this->db->get()->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Rumus
	public function getListRumus()
	{
		$this->db->select('a.*');
		$this->db->from('master_rumus AS a');
		$this->db->order_by('update_date','DESC'); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getRumus($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_rumus AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_rumus',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getRumusFunction($func)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_rumus AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('function',$func); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListRumusActive()
	{
		return $this->model_global->listActiveRecord('master_rumus','kode_rumus','nama');
	}
	public function checkRumusCode($code)
	{
		return $this->model_global->checkCode($code,'master_rumus','kode_rumus');
	}

	//===MASTER DATA PENILAIAN END===//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA DOCUMENT BEGIN===//

	//===MASTER DATA DOCUMENT END===//













    //==jabatan==//
	// function list_level(){
	// 	return $this->db->get('master_level_jabatan')->result();
	// }
	// function list_layer(){
	// 	return $this->db->get('master_kategori_jabatan')->result();
	// }
	// function list_loker(){
	// 	return $this->db->get('master_loker')->result();
	// }
	// function list_tipe(){
	// 	return $this->db->get('master_tipe')->result();
	// }
	// function list_bidang(){
	// 	return $this->db->get('master_bidang')->result();
	// } 
	// function list_notif(){
	// 	return $this->db->get('notification')->result();
	// }
	// function list_menu(){
	// 	return $this->db->query("SELECT * FROM master_menu ORDER BY sequence ASC")->result();
	// }
	// function list_access(){
	// 	return $this->db->get('master_access')->result();
	// }
	// function list_user_group(){
	// 	return $this->db->get('master_user_group')->result();
	// }
	// function user_group_avl(){
	// 	return $this->db->get_where('master_user_group',array('status'=>'aktif'))->result();
	// }
	// function loker_avl(){
	// 	return $this->db->get_where('master_loker',array('status'=>'aktif'))->result();
	// }
	// function bidang_avl(){
	// 	return $this->db->get_where('master_bidang',array('status'=>'aktif'))->result();
	// }
	// function access_avl(){
	// 	return $this->db->get_where('master_access',array('status'=>'aktif'))->result();
	// }
	// function menu_avl(){
	// 	return $this->db->query("SELECT * FROM master_menu WHERE status = 'aktif' ORDER BY sequence ASC")->result();
	// }
	// function notif(){
	// 	$tgl=$this->date;
	// 	return $this->db->query("SELECT * FROM notification WHERE status = 'aktif' AND untuk = 'ADM' AND start <= '$tgl' AND end_date >= '$tgl'")->result();
	// }
	function notif_emp(){
		$tgl=$this->date;
		return $this->db->query("SELECT * FROM notification WHERE status = 1 AND untuk = 'FO' AND start <= '$tgl' AND end_date >= '$tgl'")->result();
	}
	function k_notif($kode){
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('notification AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.kode_notif',$kode); 
		$this->db->where('a.status',1); 
		$query=$this->db->get()->row_array();
		return $query;
		// return $this->db->get_where('notification',array('kode_notif'=>$kode,'status'=>1))->row_array();
	}
	// function tipe_avl(){
	// 	return $this->db->get_where('master_tipe',array('status'=>'aktif'))->result();
	// }
	// function k_loker($kode){
	// 	return $this->db->get_where('master_loker',array('kode_loker'=>$kode))->row_array();
	// }
	// function loker($id){
	// 	return $this->db->get_where('master_loker',array('id_loker'=>$id))->row_array();
	// }
	// function user_group($id){
	// 	return $this->db->get_where('master_user_group',array('id_group'=>$id))->row_array();
	// }
	// function list_stat_peg(){
	// 	return $this->db->get('master_status_pegawai')->result();
	// }
	// function list_jabatan(){
	// 	return $this->db->get('master_jabatan')->result();
	// }
	// function list_sub_jabatan(){
	// 	return $this->db->get('master_sub_jabatan')->result();
	// }
	// function list_variant(){
	// 	return $this->db->get('master_variant_jabatan')->result();
	// }
	function jabatan($id){
		return $this->db->get_where('master_jabatan',array('id_jabatan'=>$id))->row_array();
	}
	// function jabatan_avl(){
	// 	return $this->db->get_where('master_jabatan',array('status'=>'aktif'))->result();
	// }
	// function sub_avl(){
	// 	return $this->db->get_where('master_sub_jabatan',array('status'=>'aktif'))->result();
	// }
	// function sub_jbt($j){
	// 	return $this->db->get_where('master_sub_jabatan',array('kode_jabatan'=>$j))->result();
	// }
	// function k_jabatan($kode){
	// 	return $this->db->get_where('master_jabatan',array('kode_jabatan'=>$kode))->row_array();
	// }
	// function k_s_jabatan($kode){
	// 	return $this->db->get_where('master_sub_jabatan',array('kode_sub'=>$kode))->row_array();
	// }
	// function cek_level($k){
	// 	return $this->db->get_where('master_level_jabatan',array('kode_level'=>$k))->row_array();
	// }
	// function getBawahan($k)
	// {
	// 	return $this->db->get_where('master_jabatan',array('atasan'=>$k))->result();
	// }
	// //==aspek sikap==//
	// function list_form(){
	// 	return $this->db->get('master_form_aspek')->result();
	// }
	// function cek_form($kd){
	// 	return $this->db->get_where('master_form_aspek',array('kode_form'=>$kd))->row_array();
	// }
	// function list_aspek(){
	// 	return $this->db->get('master_aspek_sikap')->result();
	// }
	// function actv_aspek(){
	// 	return $this->db->get_where('master_aspek_sikap',array('status'=>'aktif'))->result();
	// }
	// function actv_form(){
	// 	return $this->db->get_where('master_form_aspek',array('status'=>'aktif'))->result();
	// }
	// function form_sel($tp){
	// 	return $this->db->get_where('master_form_aspek',array('kode_tipe'=>$tp,'status'=>'aktif'))->result();
	// }
	// function cek_aspek($k){
	// 	return $this->db->get_where('master_aspek_sikap',array('kode_aspek'=>$k))->row_array();
	// }
	// function kuisioner($k){
	// 	return $this->db->get_where('master_kuisioner',array('kode_aspek'=>$k))->result();
	// }
	// function cek_kuisioner($k,$tp){
	// 	return $this->db->get_where('master_kuisioner',array('kode_aspek'=>$k,'kode_tipe'=>$tp))->result();
	// }
	// function list_kuisioner(){
	// 	return $this->db->get('master_kuisioner')->result();
	// }
	// //==indikator==//

	// function list_indikator(){
	// 	return $this->db->get('master_indikator')->result();
	// }
	// function which_indikator($k){
	// 	return $this->db->get_where('master_indikator',array('kode_indikator'=>$k))->row_array();
	// }
	// function indikator(){
	// 	return $this->db->get_where('master_indikator',array('status'=>'aktif'))->result();
	// }
	
	// //==target corporate==//
	// function list_target_c(){
	// 	return $this->db->get('target_corporate')->result();
	// }
	// function cek_target($kd){
	// 	return $this->db->get_where('target_corporate',array('kode_target'=>$kd))->row_array();
	// }
	// function tb_target($tb){
	// 	return $this->db->get($tb)->result();
	// }
	// //==organisasi==//
	// function list_grade(){
	// 	return $this->db->get('master_grade')->result();
	// }
	// function list_induk_grade(){
	// 	return $this->db->get('master_induk_grade')->result();
	// }

	// //==concept==//
	// function cek_set($kd){
	// 	return $this->db->get_where('concept_kpi',array('kode_c_kpi'=>$kd))->row_array();
	// }
	// function set_ind(){
	// 	return $this->db->get('concept_kpi')->result();
	// }
	// function id_set($kd){
	// 	return $this->db->get_where('concept_kpi',array('id_c_kpi'=>$kd))->row_array();
	// }
	// //==attitude concept==//
	// function attd_c(){
	// 	return $this->db->get('concept_sikap')->result();
	// }
	// function id_attd($kd){
	// 	return $this->db->get_where('concept_sikap',array('id_c_sikap'=>$kd))->row_array();
	// }
	// function cek_attd($kd){
	// 	return $this->db->get_where('concept_sikap',array('kode_c_sikap'=>$kd))->row_array();
	// }
	// function tb_attd($t){
	// 	return $this->db->get($t)->result();
	// }
	// function tb_k_attd($t,$n){
	// 	return $this->db->get_where($t,array('nik'=>$n))->row_array();
	// }
	// //==denda==//
	// function list_denda(){
	// 	return $this->db->get('dp_denda')->result();
	// }
	// function cek_denda($kd){
	// 	return $this->db->get_where('dp_denda',array('kode_denda'=>$kd))->row_array();
	// }
	// function tb_val_denda($tb,$id){
	// 	return $this->db->get_where($tb,array('id_denda'=>$id))->row_array();
	// }
	// function tb_denda($tb){
	// 	return $this->db->get($tb)->result();
	// }
	// //==presensi==//
	// function list_presensi(){
	// 	return $this->db->get('dp_presensi')->result();
	// }
	// function cek_presensi($kd){
	// 	return $this->db->get_where('dp_presensi',array('kode_presensi'=>$kd))->row_array();
	// }
	// function tb_presensi($tb){
	// 	return $this->db->get($tb)->result();
	// }
	// function tb_sel_p($tb,$ki){
	// 	return $this->db->get_where($tb,array('kode_indikator'=>$ki))->result();
	// }
	// //==anggaran==//
	// function list_anggaran(){
	// 	return $this->db->get('dp_anggaran')->result();
	// }
	// function cek_anggaran($kd){
	// 	return $this->db->get_where('dp_anggaran',array('kode_anggaran'=>$kd))->row_array();
	// }
	// function tb_anggaran($tb){
	// 	return $this->db->get($tb)->result();
	// }
	// //==konversi nilai==//
	// function list_konversi(){
	// 	return $this->db->get('master_konversi_nilai')->result();
	// }
	// //==other==//
	// function up_date(){
	// 	return $this->db->get('master_tgl_update_data')->result();
	// }
	function up_date_actv(){
		return $this->db->get_where('master_tgl_update_data',array('status'=>1,'id_date'=>'1'))->row_array();
	}
	// function list_bbt_s(){
	// 	return $this->db->get('master_bobot_sikap')->result();
	// }
	// function bobot_s(){
	// 	return $this->db->get_where('master_bobot_sikap',array('status'=>'aktif'))->result();
	// }
	// function k_bobot($k){
	// 	return $this->db->get_where('master_bobot_sikap',array('kode_bobot'=>$k,'status'=>'aktif'))->row_array();
	// }
	// function root_pass(){
	// 	return $this->db->get_where('root_password',array('id'=>'1'))->row_array();
	// }
/*++++++++++++++++++++++++++++++++++++++++++++= Sourch Putra S. Bud =++++++++++++++++++++++++++++++++++++++++++++ */
/*++++++++++++++++++++++++++++++ date 02/04/2019 	++++++++++++++++++++++++++++++*/
	
	//--------------------------------------------------------------------------------------------------------------//
	//Master Induk Tunjangan
	public function getListIndukTunjangan()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('master_induk_tunjangan')->result();
		return $query;
	}
	public function getIndukTunjangan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_induk_tunjangan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_induk_tunjangan',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getIndukTunjanganWhere($where=null,$sort=null,$vsort=null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_induk_tunjangan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.status',1);
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($sort) && !empty($vsort)){
			$this->db->order_by($sort,$vsort);
			$this->db->order_by('a.nama','ASD');
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListIndukTunjanganActive()
	{
		return $this->model_global->listActiveRecord('master_induk_tunjangan','kode_induk_tunjangan','nama');
	}
	public function checkIndukTunjanganCode($code)
	{
		return $this->model_global->checkCode($code,'master_induk_tunjangan','kode_induk_tunjangan');
	}
	public function getListIndukTunjanganKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_induk_tunjangan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.kode_induk_tunjangan',$kode);
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Tunjangan
	public function getListTunjangan($where = null, $status = 0, $row = false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, itjg.nama as nama_induk_tunjangan, itjg.sifat, grd.nama as nama_grade, igrd.kode_induk_grade, igrd.nama as nama_induk_grade, lkr.nama as nama_loker, jbt.nama as nama_jabatan');
		$this->db->from('master_tunjangan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_induk_tunjangan AS itjg', 'itjg.kode_induk_tunjangan = a.kode_induk_tunjangan', 'left');
		$this->db->join('master_induk_grade as igrd','igrd.kode_induk_grade = a.kode_induk_grade','left');
		$this->db->join('master_grade as grd','grd.kode_grade = a.kode_grade','left');
		$this->db->join('master_loker as lkr','lkr.kode_loker = a.kode_loker','left');
		$this->db->join('master_jabatan as jbt','jbt.kode_jabatan = a.kode_jabatan','left');
		
		if(!empty($where)){ $this->db->where($where); }
		if($status == 1){ $this->db->where('a.status',1); }
		$this->db->order_by('create_date','DESC');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListTunjanganKode($val, $usage, $mode, $group_by = null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, itjg.nama as nama_induk_tunjangan, grd.nama as nama_grade, igrd.kode_induk_grade, igrd.nama as nama_induk_grade, lkr.nama as nama_loker, jbt.nama as nama_jabatan');
		$this->db->from('master_tunjangan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_induk_tunjangan AS itjg', 'itjg.kode_induk_tunjangan = a.kode_induk_tunjangan', 'left');
		$this->db->join('master_induk_grade as igrd','igrd.kode_induk_grade = a.kode_induk_grade','left');
		$this->db->join('master_grade as grd','grd.kode_grade = a.kode_grade','left');
		$this->db->join('master_loker as lkr','lkr.kode_loker = a.kode_loker','left');
		$this->db->join('master_jabatan as jbt','jbt.kode_jabatan = a.kode_jabatan','left');
		if($mode == 'id'){
			if($usage == 'parent'){ 
				$this->db->where('itjg.id_induk_tunjangan',$val); 
			}elseif($usage == 'child'){
				$this->db->where('a.id_tunjangan',$val); 
			}
		}elseif($mode == 'kode'){
			if($usage == 'parent'){ 
				$this->db->where('itjg.kode_induk_tunjangan',$val); 
			}elseif($usage == 'child'){
				$this->db->where('a.kode_tunjangan',$val);
			}
		}
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}


	public function getJabatanFromGrade($kode,$usage)
	{
		/*$kode as array = ['induk_grade'=> null,'grade'=> null,'loker'=> null,'jabatan'=> null]*/
		$this->db->select('jbt.kode_jabatan, jbt.nama, igrd.kode_induk_grade, grd.kode_grade, grd.kode_loker');
		$this->db->from('master_jabatan as jbt');
		$this->db->join('karyawan as emp','emp.jabatan = jbt.kode_jabatan','left');
		$this->db->join('master_grade as grd','grd.kode_loker = emp.loker','left');
		$this->db->join('master_induk_grade as igrd','igrd.kode_induk_grade = grd.kode_induk_grade','left');
		if($usage == 'induk_grade'){
			if(!empty($kode['induk_grade'])){ $this->db->where('igrd.kode_induk_grade',$kode['induk_grade']); }
			$this->db->group_by('jbt.kode_jabatan');
		}elseif($usage == 'grade'){
			if(!empty($kode['grade'])){ $this->db->where('grd.kode_grade',$kode['grade']); }
			if(!empty($kode['induk_grade'])){ $this->db->where('igrd.kode_induk_grade',$kode['induk_grade']); }
		}elseif($usage == 'loker'){
			if(!empty($kode['grade'])){ $this->db->where('grd.kode_grade',$kode['grade']); }
			if(!empty($kode['induk_grade'])){ $this->db->where('igrd.kode_induk_grade',$kode['induk_grade']); }
			if(!empty($kode['loker'])){ $this->db->where('grd.kode_loker',$kode['loker']); }
			$this->db->group_by('jbt.kode_jabatan');
		}elseif($usage == 'jabatan'){
			if(!empty($kode['grade'])){ $this->db->where('grd.kode_grade',$kode['grade']); }
			if(!empty($kode['induk_grade'])){ $this->db->where('igrd.kode_induk_grade',$kode['induk_grade']); }
			if(!empty($kode['loker'])){ $this->db->where('grd.kode_loker',$kode['loker']); }
			if(!empty($kode['jabatan'])){ $this->db->where('jbt.kode_jabatan',$kode['jabatan']); }
			$this->db->group_by('jbt.kode_jabatan');
		}else{
			if(!empty($kode['grade'])){ $this->db->where('grd.kode_grade',$kode['grade']); }
			if(!empty($kode['induk_grade'])){ $this->db->where('igrd.kode_induk_grade',$kode['induk_grade']); }
			if(!empty($kode['loker'])){ $this->db->where('grd.kode_loker',$kode['loker']); }
			if(!empty($kode['jabatan'])){ $this->db->where('jbt.kode_jabatan',$kode['jabatan']); }
		}
		$query = $this->db->get()->result();
		return $query;
	}

	public function cek_tunjangan_data($where)
	{
		$this->db->select('a.*');
		$this->db->from('master_tunjangan as a');
		$this->db->where($where);
		$query = $this->db->get()->result();
		return $query;
	}
	public function getJabatanKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan,d.kode_bagian');
		$this->db->from('master_jabatan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'inner'); 
		$this->db->where('a.kode_jabatan',$kode); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLokerKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_loker AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.kode_loker',$kode); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getJabatanFromLoker($kode)
	{
		$this->db->select('jbt.kode_jabatan, jbt.nama as nama_jabatan');
		$this->db->from('Karyawan as emp');
		$this->db->join('master_jabatan as jbt','jbt.kode_jabatan = emp.jabatan','left');
		$this->db->where('emp.loker',$kode);
		$query = $this->db->get()->result();
		return $query;
	}public function checkTunjanganCode($code)
	{
		return $this->model_global->checkCode($code,'master_tunjangan','kode_tunjangan');
	}
/*++++++++++++++++++++++++++++++ date 04/04/2019 	++++++++++++++++++++++++++++++*/	
	//--------------------------------------------------------------------------------------------------------------//
	//Periode Penggajian
	public function getPeriodePenggajian($where=null,$group_by = null)
	{
		/*$where is array = ['a.id'=>'1'];*/
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, umk.nama as nama_umk, umk.tarif, sip.nama as nama_sistem_penggajian, lok.nama as nama_loker');
		$this->db->from('data_periode_penggajian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_tarif_umk','left');
		$this->db->join('master_sistem_penggajian as sip','sip.kode_master_penggajian = a.kode_master_penggajian','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by('a.tgl_mulai','DESC');
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkPeriodePenggajianCode($code)
	{
		return $this->model_global->checkCode($code,'data_periode_penggajian','kode_periode_penggajian');
	}
	public function getListPeriodePenggajian($status = 'no_active',$where=null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, umk.nama as nama_umk, umk.tarif, sip.nama as nama_sistem_penggajian, lok.nama as nama_loker');
		$this->db->from('data_periode_penggajian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_tarif_umk','left');
		$this->db->join('master_sistem_penggajian as sip','sip.kode_master_penggajian = a.kode_master_penggajian','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if($where != null){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getListPeriodePenggajianHarian($status = 'no_active',$where=null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, umk.nama as nama_umk, umk.tarif, sip.nama as nama_sistem_penggajian, lok.nama as nama_loker');
		$this->db->from('data_periode_penggajian_harian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_tarif_umk','left');
		$this->db->join('master_sistem_penggajian as sip','sip.kode_master_penggajian = a.kode_master_penggajian','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if($where != null){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
/*++++++++++++++++++++++++++++++ date 09/04/2019 	++++++++++++++++++++++++++++++*/	
//--------------------------------------------------------------------------------------------------------------//
//MasterInsentif
	public function getListInsentif($status = 'active')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_insentif as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getInsentifWhere($where)
	{
		/*$where is array*/
		if(empty($where))
			return null;

		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_insentif as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where($where);
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkInsentifCode($code)
	{
		return $this->model_global->checkCode($code,'master_insentif','kode_insentif');
	}
/*++++++++++++++++++++++++++++++ date 09/04/2019 	++++++++++++++++++++++++++++++*/	
	//--------------------------------------------------------------------------------------------------------------//
	//Periode Penggajian
	public function getListPeriodePenggajianDetail($kode = null,$where = null,$status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, lok.nama as nama_loker, umk.nama as nama_umk, umk.tarif');
		$this->db->from('data_periode_penggajian_detail as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('data_periode_penggajian as dpg','dpg.kode_periode_penggajian = a.kode_periode_penggajian','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_umk','left');
		if(!empty($kode)){ $this->db->where('a.kode_periode_penggajian',$kode); }
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getListPeriodePenggajianDetailSingle($kode = null,$where = null,$status = 0, $order_by = 'update_date desc', $group_by = null)
	{
		if(!empty($kode)){ $this->db->where('kode_periode_penggajian',$kode); }
		if($status == 1){ $this->db->where('status','1'); } 									/*status*/
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($order_by)){ $this->db->order_by($order_by); } 							/*order_by*/
		if(!empty($group_by)){ $this->db->group_by($group_by); } 							/*group_by*/
		$query = $this->db->get('data_periode_penggajian_detail')->result();
		return $query;
	}
	public function checkPeriodePenggajianDetailCode($code)
	{
		return $this->model_global->checkCode($code,'data_periode_penggajian_detail','kode_periode_detail');
	}
/*++++++++++++++++++++++++++++++ date 10/04/2019 	++++++++++++++++++++++++++++++*/	
	//--------------------------------------------------------------------------------------------------------------//
	//Data Ritasi
	public function getListDataRitasi($where = null, $status = 'all_item', $row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, prd.nama as nama_periode, sip.nama as nama_sistem_penggajian, emp.nama as nama_karyawan, jbt.nama as nama_jabatan');
		$this->db->from('data_ritasi as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan as emp','emp.nik = a.nik', 'left');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('data_periode_penggajian as prd','prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
		$this->db->join('master_sistem_penggajian as sip','sip.kode_master_penggajian = prd.kode_master_penggajian','left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Ritasi
	public function getListDataTunjangan($where = null, $status = 'all_item', $row = false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, prd.nama as nama_periode, sip.nama as nama_sistem_penggajian,emp.nama as nama_karyawan, emp.nik as nik_karyawan, jab.id_jabatan, jab.nama as jabatan_karyawan, bag.nama as nama_bagian, lok.nama as nama_loker,prd.tahun');
		$this->db->from('data_pendukung_tunjangan as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan as emp','emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jab.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = emp.loker', 'left');
		$this->db->join('data_periode_penggajian as prd','prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
		$this->db->join('master_sistem_penggajian as sip','sip.kode_master_penggajian = prd.kode_master_penggajian','left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
/*++++++++++++++++++++++++++++++ date 11/04/2019 	++++++++++++++++++++++++++++++*/	
	//--------------------------------------------------------------------------------------------------------------//
	//Master BPJS
	public function getListBpjs($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_bpjs as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getListBpjsRow($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_bpjs as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		// $this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->row_array();
		return $query;
	}
	public function checkBpjsCode($code)
	{
		return $this->model_global->checkCode($code,'master_bpjs','kode_bpjs');
	}
	//Master BPJS Prosentase
	public function getListBpjsProsentase($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_bpjs_prosentase as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkBpjsProsentaseCode($code)
	{
		return $this->model_global->checkCode($code,'master_bpjs_prosentase','kode');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Pinjaman
	public function getListPinjaman($where = null, $status = 'all_item',$sistem_penggajian = 'BULANAN')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, prd.nama as nama_periode');
		$this->db->from('data_pinjaman as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($sistem_penggajian == 'BULANAN'){
			$this->db->join('data_periode_penggajian as prd','prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
		}elseif($sistem_penggajian == 'HARIAN'){
			$this->db->join('data_periode_penggajian_harian as prd','prd.kode_periode_penggajian_harian = a.kode_periode_penggajian', 'left');
		}
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkPinjamanCode($code)
	{
		return $this->model_global->checkCode($code,'data_pinjaman','kode_pinjaman');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data PTKP
	public function getListPtkp($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_ptkp as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.tahun','desc');
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkPtkpCode($code)
	{
		return $this->model_global->checkCode($code,'master_ptkp','kode_ptkp');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data PPH
	public function getListPph($where = null, $status = 'all_item', $order_by = 'a.update_date desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pph as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($order_by)){ $this->db->order_by($order_by); } 				/*order_by*/
		$query = $this->db->get()->result();
		return $query;
	}
	public function getListPphRow($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pph as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); } 
		$query = $this->db->get()->row_array();
		return $query;
	}
	public function getListPphNonRow($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pph_non as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); } 
		$query = $this->db->get()->row_array();
		return $query;
	}
	public function getListPphPesangonRow($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_pph_pesangon as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); } 
		$query = $this->db->get()->row_array();
		return $query;
	}
	public function checkPphCode($code)
	{
		return $this->model_global->checkCode($code,'master_pph','kode_pph');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Tarif Jabatan
	public function getListTarifJabatan($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_tarif_jabatan as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkTarifJabatanCode($code)
	{
		return $this->model_global->checkCode($code,'master_tarif_jabatan','kode_tarif_jabatan');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Periode Lembur
	public function getListPeriodeLembur($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, umk.nama as nama_umk, umk.tarif, sip.nama as nama_sistem_penggajian, lok.nama as nama_loker');
		$this->db->from('data_periode_lembur as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_tarif_umk','left');
		$this->db->join('master_sistem_penggajian as sip','sip.kode_master_penggajian = a.kode_master_penggajian','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }

		$query = $this->db->get()->result();
		return $query;
	}
	public function checkPeriodeLemburCode($code)
	{
		return $this->model_global->checkCode($code,'data_periode_lembur','kode_periode_lembur');
	}
	public function getListPeriodeLemburDetail($kode = null,$where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, lok.nama as nama_loker, umk.nama as nama_umk, umk.tarif');
		$this->db->from('data_periode_lembur_detail as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('data_periode_lembur as dpg','dpg.kode_periode_lembur = a.kode_periode_lembur','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_umk','left');

		if(!empty($kode)){ $this->db->where('a.kode_periode_lembur',$kode); }
		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }

		$query = $this->db->get()->result();
		return $query;
	}
	public function checkPeriodeLemburDetailCode($code)
	{
		return $this->model_global->checkCode($code,'data_periode_lembur_detail','kode_periode_detail');
	}
	public function getTarifUmk($id, $where = null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_tarif_umk AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		if(!empty($id)){ $this->db->where('id_tarif_umk',$id); }
		if(!empty($where)){ $this->db->where($where); }
		$query=$this->db->get()->result();
		return $query;
	}


	public function getListJabatanWhere($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_bagian,e.nama as nama_level_jabatan,f.nama as nama_atasan,g.nama as nama_loker,i.nama as nama_loker_atasan,mls.kode_level_struktur, mls.nama as nama_level_struktur');
		$this->db->from('master_jabatan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = a.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS e', 'e.kode_level_jabatan = a.kode_level', 'left'); 
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.atasan', 'left'); 
		$this->db->join('master_loker AS g', 'g.kode_loker = d.kode_loker', 'left'); 
		$this->db->join('master_bagian AS h', 'h.kode_bagian = f.kode_bagian', 'left');
		$this->db->join('master_loker AS i', 'i.kode_loker = h.kode_loker', 'left');
		$this->db->join('master_level_struktur as mls', 'mls.kode_level_struktur = e.kode_level_struktur','left');

		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLevelStrukturWhere($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc')
	{		
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_level_struktur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where(['id_level_struktur !='=>1]);
		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLevelJabatanWhere($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_level_struktur');
		$this->db->from('master_level_jabatan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_level_struktur AS d', 'd.kode_level_struktur = a.kode_level_struktur', 'left'); 
		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		$query=$this->db->get()->result();
		return $query;
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Petugas Payroll
	public function getListPetugasPayrollWhere($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc', $row = false)
	{
		$this->db->select('a.*,jbt.nama as nama_petugas,emp.nama as nama_karyawan,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_petugas_payroll AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_petugas', 'left'); 
		$this->db->join('karyawan AS emp', 'emp.jabatan = jbt.kode_jabatan', 'left'); 

		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}			
		return $query;
	}

	public function checkPetugasPayrollCode($code)
	{
		return $this->model_global->checkCode($code,'master_petugas_payroll','kode_petugas_payroll');
	}
	public function getListPetugasPPHWhere($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc', $row = false)
	{
		$this->db->select('a.*,jbt.nama as nama_petugas, emp.nama as nama_karyawan,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_petugas_pph AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_petugas', 'left'); 
		$this->db->join('karyawan AS emp', 'emp.jabatan = jbt.kode_jabatan', 'left'); 

		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}

	public function checkPetugasPPHCode($code)
	{
		return $this->model_global->checkCode($code,'master_petugas_pph','kode_petugas_pph');
	}
	//Master Petugas Lembur
	public function getListPetugasLemburWhere($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc', $row = false)
	{
		$this->db->select('a.*,jbt.nama as nama_petugas,emp.nama as nama_karyawan,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_petugas_lembur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_petugas', 'left'); 
		$this->db->join('karyawan AS emp', 'emp.jabatan = jbt.kode_jabatan', 'left'); 

		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}			
		return $query;
	}
	public function checkPetugasPayrollLemburCode($code)
	{
		return $this->model_global->checkCode($code,'master_petugas_lembur','kode_petugas_lembur');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Periode Penggajian Harian
	public function getPeriodePenggajianHarian($where = null, $group_by = null, $status = 0,$order_by = 'a.update_date desc')
	{
		/*$where is array = ['a.id'=>'1'];*/
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, umk.nama as nama_umk, umk.tarif, sip.nama as nama_sistem_penggajian, lok.nama as nama_loker');
		$this->db->from('data_periode_penggajian_harian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_tarif_umk','left');
		$this->db->join('master_sistem_penggajian as sip','sip.kode_master_penggajian = a.kode_master_penggajian','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');

		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }

		$query = $this->db->get()->result();
		return $query;
	}
	public function checkPeriodePenggajianHarianCode($code)
	{
		return $this->model_global->checkCode($code,'data_periode_penggajian_harian','kode_periode_penggajian_harian');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Periode Penggajian Detail
	public function getListPeriodePenggajianHarianDetail($kode = null, $where = null, $status = 0, $group_by = null, $order_by = 'a.update_date desc')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, lok.nama as nama_loker, umk.nama as nama_umk, umk.tarif');
		$this->db->from('data_periode_penggajian_harian_detail as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('data_periode_penggajian_harian as dpg','dpg.kode_periode_penggajian_harian = a.kode_periode_penggajian_harian','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_umk','left');
		if(!empty($kode)){ $this->db->where('a.kode_periode_penggajian_harian',$kode); }

		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }

		$query = $this->db->get()->result();
		return $query;
	}
	public function getListPeriodePenggajianHarianDetailSingle($kode = null, $where = null, $status = 0, $group_by = null, $order_by = 'update_date desc')
	{
		if(!empty($kode)){ $this->db->where('a.kode_periode_penggajian_harian',$kode); }

		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		$query = $this->db->get('data_periode_penggajian_harian_detail')->result();
		return $query;
	}
	public function checkPeriodePenggajianHarianDetailCode($code)
	{
		return $this->model_global->checkCode($code,'data_periode_penggajian_harian_detail','kode_periode_detail');
	}
	public function getListDataBackup()
	{
		$this->db->select('*');
		$this->db->from('data_backup'); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataBackup($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('data_backup AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where('a.id',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListDataRestore()
	{
		$this->db->select('*');
		$this->db->from('data_restore'); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataRestore($id, $row=null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('data_restore AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where('a.id',$id);
		if($row != null){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}

	// ===================================================== PENDUKUNG PPH =================================================
	
	// Jenis Penerima Pajak
	public function getListPenerimaPajak($where=null,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('pph_penerima_pajak AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	//================================================== KUOTA LEMBUR ================================================================
	public function getListKuotaLembur($where=null,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, e.nama as nama_bagian,
		(select count(*) from detail_kuota_lembur cnt where cnt.kode_kuota_lembur = a.kode) as jum_bag, 
		(select sum(sk.sisa_kuota) from detail_kuota_lembur sk where sk.kode_kuota_lembur = a.kode) as sisa_kuota,
		(select sum(cnt.kuota) from detail_kuota_lembur cnt where cnt.kode_kuota_lembur = a.kode) as total_kuota_all');
		$this->db->from('master_kuota_lembur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('detail_kuota_lembur AS d', 'a.kode = d.kode_kuota_lembur', 'left');
		$this->db->join('master_bagian AS e', 'e.kode_bagian = d.kode_bagian', 'left'); 
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->group_by('a.kode');
		$this->db->order_by('a.update_date','DESC');
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListDetailKuotaLembur($where=null,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, e.nama as nama_bagian, lok.nama as nama_loker');
		$this->db->from('detail_kuota_lembur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_bagian AS e', 'e.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_kuota_lembur AS d', 'd.kode = a.kode_kuota_lembur', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = e.kode_loker', 'left'); 
		if(!empty($where)){
			$this->db->where($where);
		}
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListHistoryKuotaLembur($where=null,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, e.nama as nama_bagian, lok.nama as nama_loker');
		$this->db->from('history_kuota_lembur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_bagian AS e', 'e.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('detail_kuota_lembur AS d', 'd.kode_kuota_lembur = a.kode_kuota', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = e.kode_loker', 'left'); 
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
}
