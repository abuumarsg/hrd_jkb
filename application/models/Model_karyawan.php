<?php
/**
* 
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_karyawan extends CI_Model
{
	protected $CI;
	function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
    }
	function list_karyawan(){
		return $this->db->get('karyawan')->result();
	}
	function count_emp(){
		return $this->db->get('karyawan')->num_rows();
	}
	function emp($id){
		return $this->db->get_where('karyawan',array('id_karyawan'=>$id))->row_array();
	}
	function forget_email($em){
		return $this->db->get_where('karyawan',array('email'=>$em))->row_array();
	}
	function res($t){
		return $this->db->get_where('karyawan',array('reset_token'=>$t))->row_array();
	}
	function emp_cek($un,$pass){
		return $this->db->get_where('karyawan',array('nik'=>$un,'password'=>$pass))->row_array();
	}
	function emp_cek_root($un,$pass){
		return $this->db->get_where('karyawan',array('nik'=>$un,'root_password'=>$pass))->row_array();
	}
	function log_login($id){
		return $this->db->get_where('log_login_karyawan',array('id_karyawan'=>$id))->result();
	}
	function emp_nik($nik){
		return $this->db->get_where('karyawan',array('nik'=>$nik))->row_array();
	}
	//new function
	function listEmpActive(){
		return $this->db->get_where('karyawan',array('status_emp'=>1))->result();
	}
	public function getEmployeeId($id,$active=false)
	{
		$this->db->select('
			emp.*,
			loker.nama as nama_loker,
			loker.um as uang_makan,
			jbt.id_jabatan,
			jbt.atasan as id_atasan,
			jbt.nama as nama_jabatan,
			jbt.id_group_user as id_group_user,
			bag.kode_bagian as kode_bagian,
			bag.nama as bagian,
			bag.kode_bagian as kode_bagian,
			b.nama as nama_buat,
			c.nama as nama_update,
			grd.id_grade,
			grd.gapok as gaji_pokok_grade,
			grd.um as uang_makan_grade,
			grd.nama as nama_grade,
			d.nama as nama_loker_grade,
			sts.nama as nama_status,
			g.nama as nama_level_jabatan,
			bag.nama as nama_bagian,
			bank.nama as nama_bank_emp,
			dep.nama as nama_departement,
			jbt.bobot_out,
			jbt.bobot_sikap,
		');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		$this->db->join('master_status_karyawan AS sts', 'emp.status_karyawan = sts.kode_status', 'left');
		$this->db->join('master_level_jabatan AS g', 'g.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_departement AS dep', 'dep.kode_departement = bag.kode_departement', 'left');
		// $this->db->join('master_bagian AS i', 'i.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_bank AS bank', 'bank.kode = emp.nama_bank', 'left'); 
		$this->db->where('emp.id_karyawan',$id);
		if ($active){
			$this->db->where('emp.status_emp',1);
		}
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getEmployeeAll($filter=null, $order=null)
	{
		$this->db->select('emp.*,loker.nama as nama_loker,jbt.nama as nama_jabatan,bag.nama as bagian, grd.nama as nama_grade,d.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		$this->db->where('emp.status_emp',1);
		if (!empty($filter)) {
			$this->db->where('jbt.kode_bagian',$filter);
		}
		if (!empty($order)) {
			$this->db->order_by($order['kolom'],$order['value']);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getEmployeeAllActive($filter=null, $order=null, $where=null)
	{
		$this->db->select('emp.*,loker.nama as nama_loker,jbt.nama as nama_jabatan,bag.nama as bagian, grd.nama as nama_grade,d.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		$this->db->where('emp.status_emp',1);
		if (!empty($filter)) {
			$this->db->where('jbt.kode_bagian',$filter);
		}
		if (!empty($where)) {
			$this->db->where($where);
		}
		if (!empty($order)) {
			$this->db->order_by($order['kolom'],$order['value']);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkNik($nik)
	{
		return $this->model_global->checkCode($nik,'karyawan','nik');
	}
	public function getEmployeeForSelect2forPetugasJabatan($filter=null,$all=false)
	{
		$data=$this->getEmployeeAllActive($filter);
		$pack=[];
		if($all){
			$pack['all']='Pilih Semua';
		}else{
			$pack[null]='Pilih Data';
		}
		foreach ($data as $d) {
			$pack[$d->id_karyawan]=$d->nama.((!empty($d->nama_jabatan)) ? ' ('.$d->nama_jabatan.')' : '').' - '.((!empty($d->nama_loker)) ? ' ('.$d->nama_loker.')' : '');
		}
		// echo '<pre>';
		// print_r($pack);
		return $pack;
	}
	public function getEmployeeForSelect2($filter=null,$all=false)
	{
		$data=$this->getEmployeeAllActive($filter);
		$pack=[];
		if($all){
			$pack['all']='Pilih Semua';
		}else{
			$pack[null]='Pilih Data';
		}
		foreach ($data as $d) {
			$pack[$d->id_karyawan]=$d->nama.((!empty($d->nama_jabatan)) ? ' ('.$d->nama_jabatan.')' : '').' - '.((!empty($d->nama_loker)) ? ' ('.$d->nama_loker.')' : '');
		}
		return $pack;
	}
	public function getRefreshEmployeeForSelect2()
	{
		$pack=$this->getEmployeeForSelect2();
		if (isset($pack[null])) {
			unset($pack[null]);
		}
		return $pack;
	}
	public function getEmployeeForSelect2ID($id_karyawan)
	{
		$pack=[];
		foreach ($id_karyawan as $id) {
			$d=$this->getEmpID($id);
			$pack[$d['id_karyawan']]=$d['nama'].((!empty($d['nama_jabatan'])) ? ' ('.$d['nama_jabatan'].')' : '');
		}
		return $pack;
	}
	public function getListEmployeeActive()
	{
		return $this->model_global->listUserActiveRecord('karyawan','id_karyawan','nama','fo');
	}
	public function getEmployeeByEmail($email)
	{
		return $this->db->get_where('karyawan',array('email'=>$email))->row_array();
	}
	public function getEmployeeByToken($token)
	{
		return $this->db->get_where('karyawan',array('reset_token'=>$token))->row_array();
	}
	public function emp_jbt($j){
		return $this->db->get_where('karyawan',array('jabatan'=>$j))->result();
	}
	public function getBawahan($j)
	{
		$CI =& get_instance();
		if (empty($j)) 
			return null;
		$bag=[];
		$j_bwh=$CI->model_master->getBawahan($j);
		if (count($j_bwh) == 0) {
			return null;
		}else{
			foreach ($j_bwh as $jb) {
				$kar=$this->emp_jbt($jb->kode_jabatan);
				if (count($kar) > 0) {
					foreach ($kar as $k) {
						array_push($bag, $k->id_karyawan);
					}
				}
			}
		}
		if (count($bag)>0) {
			return $bag;
		}else{
			return null;
		}
	}
	public function getConvertNiktoId($nik)
	{
		if (empty($nik)) 
			return null;
		$kar=$this->emp_nik($nik);
		if (isset($kar)) {
			return $kar['id_karyawan'];
		}else{
			return null;
		}
	}
	public function getConvertIdtoNik($id)
	{
		if (empty($id)) 
			return null;
		$kar=$this->emp_nik($id);
		if (isset($kar)) {
			return $kar['nik'];
		}else{
			return null;
		}
	}
	//Log Karyawan
	function log_kar($idkar){
		return $this->db->get_where('log_login_karyawan', array('id_karyawan'=>$idkar))->result();
	}
	//Grade Karyawan
	function emp_grade($kode){
		$this->db->select('grd.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('master_grade AS grd');
		$this->db->join('admin AS b', 'b.id_admin = grd.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = grd.update_by', 'left'); 
		$this->db->where('grd.kode_grade',$kode);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getEmployeeAllActiveFilter($valnik = false, $select = false)
	{
		$filter=$this->model_global->getFilterbyBagian();
		$this->db->select('emp.*,loker.nama as nama_loker,jbt.nama as nama_jabatan,bag.nama as bagian, grd.nama as nama_grade,d.nama as nama_loker_grade,non.tgl_keluar');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		$this->db->join('data_karyawan_tidak_aktif AS non', 'non.id_karyawan = emp.id_karyawan', 'left');			
		if (!empty($filter)){
			$sq=$filter;
		}
		if(!$valnik){
			$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		}
		if($select){
			$tahun = date('Y');
			// $this->db->where("YEAR(non.tgl_keluar) = '$tahun'");
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getEmployeeForSelect2Filter($valnik = false, $select = false)
	{
		$data=$this->getEmployeeAllActiveFilter($valnik, $select);
		$pack=[];
		foreach ($data as $d) {
			$nik = null;
			$status = null;
			if($valnik){
				$nik = $d->nik.' - ';
				if($d->status_emp == '0'){
					$status = ' ~ '.$this->otherfunctions->getStatusKaryawan($d->status_emp);
				}
			}
			$pack[$d->id_karyawan]=$nik.$d->nama.((!empty($d->nama_jabatan)) ? ' ('.$d->nama_jabatan.')' : '').$status;
		}
		return $pack;
	}
	public function getEmployeeForSelect2FilterJadwal($valnik = false, $select = false)
	{
		$data=$this->getEmployeeAllActiveFilter($valnik, $select);
		$pack=[];
		$pack['all']='Semua Karyawan';
		foreach ($data as $d) {
			$nik = null;
			$status = null;
			if($valnik){
				$nik = $d->nik.' - ';
				if($d->status_emp == '0'){
					$status = ' ~ '.$this->otherfunctions->getStatusKaryawan($d->status_emp);
				}
			}
			$pack[$d->id_karyawan]=$nik.$d->nama.((!empty($d->nama_jabatan)) ? ' ('.$d->nama_jabatan.')' : '').$status;
		}
		return $pack;
	}
	public function getDataPesan(){
		$this->db->select('a.*,emp.nama as nama_karyawan,b.nama as nama_jabatan,d.nama as nama_jenis');
		$this->db->from('data_pesan as a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left');
		$this->db->join('master_jenis_pesan AS d', 'd.kode = a.jenis', 'left');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPesanID($id){
		$this->db->select('a.*,emp.nama as nama_karyawan,b.nama as nama_jabatan,d.nama as nama_jenis');
		$this->db->from('data_pesan as a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left');
		$this->db->join('master_jenis_pesan AS d', 'd.kode = a.jenis', 'left');
		$this->db->where('id_pesan', $id);
		$query=$this->db->get()->result();
		return $query;
	}
	function pesanID($id){
		return $this->db->get_where('data_pesan', array('id_pesan'=>$id,))->row_array();
	}
    // public function getMessageUnread($id_admin)
    // {
	// 	$data_pesan=$this->getDataPesan();
	// 	foreach ($data_pesan as $dp){
	// 		$dia=$this->otherfunctions->getDataExplode($d->id_for,';','all');
	// 		$id_admin=[];
	// 		foreach($dia as $dd => $ee){
	// 			$id_admin[]=$this->model_admin->getAdminRowArray($ee)['id_admin'];
	// 		}
	// 		if(in_array($id_admin,$d->id_for)){

	// 		}
	// 	}
    //     // $sc = "SELECT a.*,
    //     // emp.nik as nik_karyawan, 
    //     // emp.nama as nama_karyawan, 
    //     // jbt.nama as nama_jabatan,
    //     // b.nama as nama_bagian, 
    //     // COUNT(a.id_karyawan) as jum
    //     // FROM data_pesan as a 
	// 	// LEFT JOIN karyawan emp ON emp.id_karyawan = a.id_karyawan
    //     // LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = emp.jabatan
    //     // LEFT JOIN master_bagian b ON b.kode_bagian = jbt.kode_bagian";
	// 	// // $sc.=" ORDER BY a.create_date DESC";
	// 	// $query=$this->db->query($sc)->result();
	// 	// return $query;
	// 	$this->db->select('a.*');
	// 	$this->db->from('data_pesan AS a');
	// 	$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
	// 	$this->db->join('master_jabatan AS b', 'b.kode_jabatan = emp.jabatan', 'left'); 
	// 	$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left');
	// 	// $this->db->where('emp.nik',$nik);
	// 	$query=$this->db->get()->result();
	// 	return $query;
    // }
//              ____  ____    __  __                ||
//             / _  |/ _  \  / / / /                ||
//=======     / /_/ / /_) / / / / /      ===========||
//           / __  / /__) \/ /_/ /                  ||
//          /_/ / /_______/\____/                   ||
         
//==============================================================  DATA KARYAWAN ========================================================
	public function getListKaryawan($usage = 'nosearch',$where = null,$where4c = null)
	{
		if($usage=='nosearch'){
			$next =  'WHERE emp.status_emp=1';
		}elseif($usage=='where4cAll'){
			$next = (!empty($where4c))?'WHERE '.$where4c.' AND emp.status_emp="1"':'WHERE emp.status_emp=1';
		}elseif($usage=='where4c'){
			$lokasix=($where4c['unit']!=null) ? " loker.kode_loker = '".$where4c['unit']."' " : '';
			$bagianx=($where4c['bagian']!=null) ? " bag.kode_bagian = '".$where4c['bagian']."' " : '';
			$bulanx=($where4c['bulan']!=null) ? "MONTH(emp.tgl_masuk) = ".$where4c['bulan'] : '';
			$tahunx=($where4c['tahun']!=null) ? "YEAR(emp.tgl_masuk) = ".$where4c['tahun'] : '';
			$all_query = [$lokasix,$bulanx,$tahunx,$bagianx];
			$nquery = array_filter($all_query);
			$nqueryx = implode(' AND ',$nquery);
			$next = (!empty($nqueryx))?'WHERE '.$nqueryx.' AND emp.status_emp="1"':'WHERE emp.status_emp=1';
		}else{
			if($where['param']=='all'){
				// $next = '';
				$next =  'WHERE emp.status_emp=1';
			}elseif($where['param']=='nik'){
				$next = "WHERE emp.nik = '".$where['nik']."'";
			}else{
				$lokasix=($where['unit']!=null) ? " loker.kode_loker = '".$where['unit']."' " : '';
				$bagianx=($where['bagian']!=null) ? " bag.kode_bagian = '".$where['bagian']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(emp.tgl_masuk) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(emp.tgl_masuk) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx,$bagianx];
				$nquery = array_filter($all_query);
				$nqueryx = implode(' AND ',$nquery);
				$next = (!empty($nqueryx))?'WHERE '.$nqueryx.' AND emp.status_emp="1"':'WHERE emp.status_emp=1';
			}
		}
		$sc="SELECT emp.*,
		jbt.nama as nama_jabatan,
		loker.nama as nama_loker,
		grd.nama as nama_grade,
		d.nama as nama_loker_grade,
		stt.nama as nama_status_karyawan,
		bank.nama as nama_akun_bank,
		lvljbt.nama as nama_level_jabatan,
		lstr.nama as nama_level_struktur,
		bag.nama as nama_bagian
		FROM karyawan emp
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = emp.jabatan
		LEFT JOIN master_loker loker ON loker.kode_loker = emp.loker
		LEFT JOIN master_grade grd ON grd.kode_grade = emp.grade
		LEFT JOIN master_loker d ON grd.kode_loker = d.kode_loker
		LEFT JOIN master_status_karyawan stt ON stt.kode_status = emp.status_karyawan
		LEFT JOIN master_bank bank ON bank.kode = emp.nama_bank
		LEFT JOIN master_bagian bag ON bag.kode_bagian = jbt.kode_bagian
		LEFT JOIN master_level_struktur lstr ON lstr.kode_level_struktur = bag.kode_level_struktur
		LEFT JOIN master_level_jabatan lvljbt ON lvljbt.kode_level_jabatan = jbt.kode_level
		$next";
		// $filter=$this->model_global->getFilterbyBagian($where['bagian']);
		// $filter=$this->model_global->getFilterbyBagian(((isset($where['bagian']))?$where['bagian']:null));
		// if (!empty($filter)){
		// 	$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter.' AND emp.status_emp = 1');
		// }
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();	
		return $query;
	}
	// public function getListKaryawan($usage = 'nosearch',$where = null)
	// {
	// 	if($usage=='nosearch'){
	// 		$next =  'WHERE emp.status_emp=1';
	// 	}else{
	// 		if($where['param']=='all'){
	// 			$next = '';
	// 		}elseif($where['param']=='nik'){
	// 			$next = "WHERE emp.nik = '".$where['nik']."'";
	// 		}else{
	// 			$bagianx=($where['bagian']!=null) ? " bag_bagian.kode_bagian = '".$where['bagian']."' " : '';
	// 			$lokasix=($where['unit']!=null) ? " loker.kode_loker = '".$where['unit']."' " : '';
	// 			$bulanx=($where['bulan']!=null) ? "MONTH(emp.tgl_masuk) = ".$where['bulan'] : '';
	// 			$tahunx=($where['tahun']!=null) ? "YEAR(emp.tgl_masuk) = ".$where['tahun'] : '';
	// 			$all_query = [$bagianx,$lokasix,$bulanx,$tahunx];
	// 			$nquery = array_filter($all_query);
	// 			$nquery = implode(' AND ',$nquery);
	// 			$next = (!empty($nquery))?'WHERE '.$nquery.' AND emp.status_emp=1':null;
	// 		}
	// 	}
	// 	$dt_struktur=$this->model_master->getListLevelStruktur(true);
	// 	$sub_query_join='';
	// 	$sub_query_select='';
	// 	if(isset($dt_struktur)){
	// 		$st=0;
	// 		foreach ($dt_struktur as $keys=>$dt_s){
	// 			$nama=($st > 0)?$dt_struktur[$keys-1]->nama:$dt_s->nama; 
	// 			$lower_ats=strtolower(str_replace('/','_',$nama));
	// 			$lower_ats=strtolower(str_replace('-','_',$lower_ats));
	// 			$lower_ats=strtolower(str_replace(' ','_',$lower_ats));
	// 			$lower=strtolower(str_replace('/','_',$dt_s->nama));
	// 			$lower=strtolower(str_replace('-','_',$lower));
	// 			$lower=strtolower(str_replace(' ','_',$lower));
	// 			$sub_query_join.="LEFT JOIN master_level_struktur lvstr_".$lower." ON lvstr_".$lower.".nama LIKE '%".$dt_s->nama."%'
	// 			LEFT JOIN master_bagian bag_".$lower." ON bag_".$lower.".kode_level_struktur = lvstr_".$lower.".kode_level_struktur AND 
	// 			IF ((lvstr_".$lower.".squence = lstr.squence),(bag_".$lower.".kode_bagian = jbt.kode_bagian),(bag_".$lower.".kode_bagian = bag_".$lower_ats.".atasan))
	// 			LEFT JOIN master_loker loker_".$lower." ON loker_".$lower.".kode_loker = bag_".$lower.".kode_loker
	// 			";
	// 			$sub_query_select.='bag_'.$lower.'.nama as nama_'.$lower.',loker_'.$lower.'.nama as nama_lokasi_'.$lower.',';
	// 			$st++;			
	// 		}
	// 	}
		
	// 	$sc="SELECT emp.*,
	// 	jbt.nama as nama_jabatan,
	// 	loker.nama as nama_loker,
	// 	$sub_query_select
	// 	grd.nama as nama_grade,
	// 	d.nama as nama_loker_grade,
	// 	stt.nama as nama_status_karyawan,
	// 	bank.nama as nama_akun_bank,
	// 	lvljbt.nama as nama_level_jabatan
	// 	FROM karyawan emp
	// 	LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = emp.jabatan
	// 	LEFT JOIN master_loker loker ON loker.kode_loker = emp.loker
	// 	LEFT JOIN master_grade grd ON grd.kode_grade = emp.grade
	// 	LEFT JOIN master_loker d ON grd.kode_loker = d.kode_loker
	// 	LEFT JOIN master_status_karyawan stt ON stt.kode_status = emp.status_karyawan
	// 	LEFT JOIN master_bank bank ON bank.kode = emp.nama_bank
	// 	LEFT JOIN master_bagian bag ON bag.kode_bagian = jbt.kode_bagian
	// 	LEFT JOIN master_level_struktur lstr ON lstr.kode_level_struktur = bag.kode_level_struktur
	// 	LEFT JOIN master_level_jabatan lvljbt ON lvljbt.kode_level_jabatan = jbt.kode_level
	// 	$sub_query_join
	// 	$next  ORDER BY update_date DESC";
	// 	$query=$this->db->query($sc)->result();	
	// 	return $query;
	// }
	function getKTPEmployee($nik){
		// return $this->db->get_where('karyawan',array('nik'=>$nik))->row_array();
		$this->db->select('no_ktp');
		$this->db->from('karyawan');
		$this->db->where('nik',$nik);
		$this->db->where('status_emp',1);
		$query=$this->db->get()->row_array();
		return $query['no_ktp'];
	}
	function getEmployeeNik($nik,$aktif=true){
		// return $this->db->get_where('karyawan',array('nik'=>$nik))->row_array();
		$this->db->select('emp.*,sts.nama as nama_status,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_jabatan,e.nama as nama_grade,f.nama as nama_loker,g.nama as nama_level_jabatan,h.nama as nama_peringatan,i.nama as nama_bagian,j.nama as nama_loker_grade, k.nama as nama_atasan,');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_status_karyawan AS sts', 'emp.status_karyawan = sts.kode_status', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_grade AS e', 'e.kode_grade = emp.grade', 'left'); 
		$this->db->join('master_loker AS f', 'f.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_level_jabatan AS g', 'g.kode_level_jabatan = d.kode_level', 'left'); 
		$this->db->join('master_surat_peringatan AS h', 'h.kode_sp = emp.status_disiplin', 'left'); 
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left'); 
		$this->db->join('master_loker AS j', 'j.kode_loker = e.kode_loker', 'left'); 
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = d.atasan', 'left');
		$this->db->where('emp.nik',$nik); 
		if($aktif){
			$this->db->where('emp.status_emp',1);
		}
		$query=$this->db->get()->row_array();
		return $query;
	}
	function getEmployeeWhere($where=null, $row=false, $order=null){
		// return $this->db->get_where('karyawan',array('nik'=>$nik))->row_array();
		$this->db->select('emp.*,sts.nama as nama_status,b.nama as nama_buat, c.nama as nama_update,jbt.nama as nama_jabatan,e.nama as nama_grade,f.nama as nama_loker,g.nama as nama_level_jabatan,h.nama as nama_peringatan,i.nama as nama_bagian,i.kode_bagian,j.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_status_karyawan AS sts', 'emp.status_karyawan = sts.kode_status', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_grade AS e', 'e.kode_grade = emp.grade', 'left'); 
		$this->db->join('master_loker AS f', 'f.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_level_jabatan AS g', 'g.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_surat_peringatan AS h', 'h.kode_sp = emp.status_disiplin', 'left'); 
		$this->db->join('master_bagian AS i', 'i.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS j', 'j.kode_loker = e.kode_loker', 'left'); 
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($order)){
			$this->db->order_by($order);
		}
		if ($row) {
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	function getEmployeeOneNik($nik){
		// return $this->db->get_where('karyawan',array('nik'=>$nik))->row_array();
		$this->db->select('emp.*,sts.nama as nama_status,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_jabatan,e.nama as nama_grade,f.nama as nama_loker,g.nama as nama_level_jabatan,h.nama as nama_peringatan,i.nama as nama_bagian,j.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_status_karyawan AS sts', 'emp.status_karyawan = sts.kode_status', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_grade AS e', 'e.kode_grade = emp.grade', 'left'); 
		$this->db->join('master_loker AS f', 'f.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_level_jabatan AS g', 'g.kode_level_jabatan = d.kode_level', 'left'); 
		$this->db->join('master_surat_peringatan AS h', 'h.kode_sp = emp.status_disiplin', 'left'); 
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left'); 
		$this->db->join('master_loker AS j', 'j.kode_loker = e.kode_loker', 'left'); 
		$this->db->where('emp.nik',$nik);
		$query=$this->db->get()->result();
		return $query;
	}
	function getEmpID($id_karyawan){
		$this->db->select('emp.*,sts.nama as nama_status,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_jabatan,e.nama as nama_grade,f.nama as nama_loker,g.nama as nama_level_jabatan,i.nama as nama_bagian,d.id_group_user as id_group_user');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_status_karyawan AS sts', 'emp.status_karyawan = sts.kode_status', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_grade AS e', 'e.kode_grade = emp.grade', 'left'); 
		$this->db->join('master_loker AS f', 'f.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_level_jabatan AS g', 'g.kode_level_jabatan = d.kode_level', 'left'); 
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left'); 
		$this->db->where('emp.id_karyawan',$id_karyawan);
		$query=$this->db->get()->row_array();
		return $query;
	}
	function getEmpIDResult($id_karyawan){
		$this->db->select('emp.*,sts.nama as nama_status,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_jabatan,e.nama as nama_grade,f.nama as nama_loker,g.nama as nama_level_jabatan,i.nama as nama_bagian');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_status_karyawan AS sts', 'emp.status_karyawan = sts.kode_status', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_grade AS e', 'e.kode_grade = emp.grade', 'left'); 
		$this->db->join('master_loker AS f', 'f.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_level_jabatan AS g', 'g.kode_level_jabatan = d.kode_level', 'left'); 
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left'); 
		$this->db->where('emp.id_karyawan',$id_karyawan);
		$query=$this->db->get()->result();
		return $query;
	}
	function getEmployeeJabatan($jabatan, $active = false, $result=false){
		// return $this->db->get_where('karyawan',array('nik'=>$nik))->row_array();
		$this->db->select('emp.*,sts.nama as nama_status,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_jabatan,e.nama as nama_grade,f.nama as nama_loker,g.nama as nama_level_jabatan,h.nama as nama_peringatan,i.nama as nama_bagian,j.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_status_karyawan AS sts', 'emp.status_karyawan = sts.kode_status', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_grade AS e', 'e.kode_grade = emp.grade', 'left'); 
		$this->db->join('master_loker AS f', 'f.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_level_jabatan AS g', 'g.kode_level_jabatan = d.kode_level', 'left'); 
		$this->db->join('master_surat_peringatan AS h', 'h.kode_sp = emp.status_disiplin', 'left'); 
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left');
		$this->db->join('master_loker AS j', 'j.kode_loker = e.kode_loker', 'left'); 
		$this->db->where('emp.jabatan',$jabatan);
		if ($active) {
			$this->db->where('emp.status_emp',1);
		}
		if ($result) {
			$query=$this->db->get()->result();
		}else{
			$query=$this->db->get()->row_array();
		}
		return $query;
	}
	function getEmployeeBagian($bagian, $active = false, $result=false){
		// return $this->db->get_where('karyawan',array('nik'=>$nik))->row_array();
		$this->db->select('emp.*,sts.nama as nama_status,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_jabatan,e.nama as nama_grade,f.nama as nama_loker,g.nama as nama_level_jabatan,h.nama as nama_peringatan,i.nama as nama_bagian,j.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_status_karyawan AS sts', 'emp.status_karyawan = sts.kode_status', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_grade AS e', 'e.kode_grade = emp.grade', 'left'); 
		$this->db->join('master_loker AS f', 'f.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_level_jabatan AS g', 'g.kode_level_jabatan = d.kode_level', 'left'); 
		$this->db->join('master_surat_peringatan AS h', 'h.kode_sp = emp.status_disiplin', 'left'); 
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left');
		$this->db->join('master_loker AS j', 'j.kode_loker = e.kode_loker', 'left'); 
		$this->db->where('i.kode_bagian',$bagian);
		if ($active) {
			$this->db->where('emp.status_emp',1);
		}
		if ($result) {
			$query=$this->db->get()->result();
		}else{
			$query=$this->db->get()->row_array();
		}
		return $query;
	}
	public function checkDataKaryawan($where)
	{
		if (empty($where))
			return false;
		$this->db->where($where);
		$query=$this->db->get('karyawan')->num_rows();
		if ($query > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function getRefreshKaryawan()
	{
		$pack=$this->getEmployeeForSelect2();
		if (isset($pack[null])) {
			unset($pack[null]);
		}
		return $pack;
	}
	// Karyawan Anak
	function getListAnak($nik)	{
		return $this->db->get_where('karyawan_anak',array('nik'=>$nik))->result();	
	}
	function getAnak($id,$nik){
		$where = array(
						'id_anak' => $id,
						'nik'=> $nik
					);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('karyawan_anak AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	//Saudara
	function saudara($nik){
		return $this->db->get_where('karyawan_saudara', array('nik'=>$nik))->result();
	}
	function getSaudara($id,$nik){	
		$where = array(
						'id_saudara' => $id,
						'nik'=> $nik
					);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('karyawan_saudara AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	//Pendidikan
	function pendidikan($nik){
		return $this->db->get_where('karyawan_pendidikan', array('nik'=>$nik))->result();
	}
	function pendidikan_max($nik){
		$sql=("SELECT * FROM karyawan_pendidikan WHERE id_k_pendidikan = (SELECT max(id_k_pendidikan) FROM karyawan_pendidikan WHERE nik='$nik')");
		return $this->db->query($sql)->row_array();
	}
	function countPendidikan($nik){
		return $this->db->get_where('karyawan_pendidikan', array('nik'=>$nik))->num_rows();
	}
	function getPendidikan($id,$nik){
		$where = array(
						'id_k_pendidikan' => $id,
						'nik'=> $nik
					);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('karyawan_pendidikan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	//Pendidikan Non Formal
	function pnf($nik){
		return $this->db->get_where('karyawan_pnf', array('nik'=>$nik))->result();
	}
	function getPnf($id,$nik){
		$where = array(
						'id_k_pnf' => $id,
						'nik'=> $nik
					);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('karyawan_pnf AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	//Organisasi
	function organisasi($nik){
		$this->db->order_by('update_date','DESC');
		return $this->db->get_where('karyawan_organisasi', array('nik'=>$nik))->result();
	}
	function getOrganisasi($id,$nik){
		$where = array(
						'id_k_organisasi' => $id,
						'nik'=> $nik
					);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('karyawan_organisasi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	//Penghargaan
	function penghargaan($nik){
		$this->db->order_by('update_date','DESC');
		return $this->db->get_where('karyawan_penghargaan', array('nik'=>$nik))->result();
	}
	function getPenghargaan($id,$nik){
		$where = array(
						'id_k_penghargaan' => $id,
						'nik'=> $nik
					);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('karyawan_penghargaan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	//Bahasa
	function bahasa($nik){
		$this->db->order_by('update_date','DESC');
		return $this->db->get_where('karyawan_bahasa', array('nik'=>$nik))->result();
	}
	function getBahasa($id,$nik){
		$where = array(
						'id_k_bahasa' => $id,
						'nik'=> $nik
					);
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('karyawan_bahasa AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getEmpKodeLokasi($kode)
	{
		$this->db->select('emp.*,loker.nama as nama_loker,jbt.nama as nama_jabatan,bag.nama as bagian,b.nama as nama_buat, c.nama as nama_update, grd.nama as nama_grade, d.nama as nama_loker_grade,bag.kode_bagian,bag.nama as nama_bagian');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		$this->db->join('master_loker AS loker', 'loker.kode_loker = emp.loker', 'left');
		$this->db->where('emp.loker',$kode);
		$query=$this->db->get()->result();
		return $query;
	}
//=================================== DATA MUTASI ==========================================================================

	public function getListDataMutasi($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_mutasi = (SELECT max(id_mutasi) FROM data_mutasi_jabatan x WHERE x.id_karyawan = a.id_karyawan)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE f.nik = '".$where['nik']."'";
			}else{
				$lokasix=($where['unit']!=null) ? " e.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl_sk) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl_sk) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND id_mutasi = (SELECT max(id_mutasi) FROM data_mutasi_jabatan x WHERE x.id_karyawan = a.id_karyawan)':'WHERE id_mutasi = (SELECT max(id_mutasi) FROM data_mutasi_jabatan x WHERE x.id_karyawan = a.id_karyawan)';
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_mutasi_jabatan cnt where cnt.id_karyawan = a.id_karyawan) as jum,
		b.nama as nama_jabatan,
		c.nama as nama_loker,
		jbt.nama as nama_jabatan_baru,
		e.nama as nama_loker_baru,
		f.nama as nama_karyawan,
		f.nik as nik_karyawan,
		g.nama as nama_status,
		h.nama as nama_mengetahui,
		i.nama as jbt_mengetahui,
		j.nama as nama_menyetujui,
		k.nama as jbt_menyetujui
		FROM data_mutasi_jabatan a
		LEFT JOIN master_jabatan b ON b.kode_jabatan = a.jabatan_asal
		LEFT JOIN master_loker c ON c.kode_loker = a.lokasi_asal
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = a.jabatan_baru
		LEFT JOIN master_loker e ON e.kode_loker = a.lokasi_baru
		LEFT JOIN karyawan f ON f.id_karyawan = a.id_karyawan
		LEFT JOIN master_mutasi g ON g.kode_mutasi = a.status_mutasi
		LEFT JOIN karyawan h ON h.id_karyawan = a.mengetahui
		LEFT JOIN master_jabatan i ON i.kode_jabatan = h.jabatan
		LEFT JOIN karyawan j ON j.id_karyawan = a.menyetujui
		LEFT JOIN master_jabatan k ON k.kode_jabatan = j.jabatan
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter .' ORDER BY update_date DESC');
		}
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getPilihKaryawanMutasi($where = null)
	{
		$filter=$this->model_global->getFilterbyBagian();
		$this->db->select('emp.id_karyawan as id_karyawan,emp.nama as nama, emp.jabatan as jabatan, emp.loker as loker, emp.nik as nik, loker.nama as nama_loker, jbt.nama as nama_jabatan');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->group_by('emp.nik');
		$this->db->order_by('emp.nama','ASD');
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		if(!empty($where)){
			$this->db->where($where); 
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function getMutasi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		f.nama as nama_jabatan_baru,
		g.nama as nama_loker_baru,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		i.nama as nama_status,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_jabatan_baru,
		m.nama as nama_loker_baru,
		n.nama as nama_menyetujui,
		n.alamat_asal_jalan as jalan,
		n.alamat_asal_desa as desa,
		n.alamat_asal_kecamatan as kecamatan,
		n.alamat_asal_kabupaten as kabupaten,
		o.nama as jbt_menyetujui,
		bag.nama as nama_bagian,
		doc.file as nama_file');
		$this->db->from('data_mutasi_jabatan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = a.jabatan_asal', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = a.lokasi_asal', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.jabatan_baru', 'left');
		$this->db->join('master_loker AS g', 'g.kode_loker = a.lokasi_baru', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_mutasi AS i', 'i.kode_mutasi = a.status_mutasi', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = i.kode_dokumen', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('master_jabatan AS l', 'l.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS m', 'm.kode_loker = h.loker', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'd.kode_bagian = bag.kode_bagian', 'left');
		$this->db->where('id_mutasi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListMutasiNik($nik, $fo=null)
	{
		$where=['h.nik'=>$nik];
		$this->db->select('a.*,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		f.nama as nama_jabatan_baru,
		g.nama as nama_loker_baru,
		h.nik as nik,
		h.nama as nama_karyawan,
		i.nama as nama_status,
		j.nama as nama_jabatan,
		k.nama as nama_loker');
		$this->db->from('data_mutasi_jabatan AS a');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = a.jabatan_asal', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = a.lokasi_asal', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = a.jabatan_baru', 'left');
		$this->db->join('master_loker AS g', 'g.kode_loker = a.lokasi_baru', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_mutasi AS i', 'i.kode_mutasi = a.status_mutasi', 'left');
		$this->db->join('master_jabatan AS j', 'j.kode_jabatan = h.jabatan', 'left'); 
		$this->db->join('master_loker AS k', 'k.kode_loker = h.loker', 'left'); 
		$this->db->where($where);
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkMutasiCode($code)
	{
		return $this->model_global->checkCode($code,'data_mutasi_jabatan','no_sk');
	}
	public function cekJabatan($jabatan)
	{
		$jab=$this->db->query("SELECT tipe_jabatan FROM master_jabatan WHERE kode_jabatan = '$jabatan'")->row_array();
		if ($jab['tipe_jabatan'] == 'GOL1'){
			$f = $this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$jabatan' AND status_emp = '1'")->result();
		}else{
			$f=[];
		}
		return $f;
	}
//========================================= PERJANJIAN KERJA ======================================================================
	public function getListDataPerjanjianKerja($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_p_kerja = (SELECT max(id_p_kerja) FROM data_perjanjian_kerja x WHERE x.nik = a.nik)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE f.nik = '".$where['nik']."'";
			}else{
				// $bagianx=($where['bagian']!=null) ? " jbt.kode_bagian = '".$where['bagian']."' " : '';
				$lokasix=($where['unit']!=null) ? " e.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl_sk_baru) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl_sk_baru) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
					// $next = (!empty($nquery))?'WHERE '.$nquery.' AND id_p_kerja = (SELECT max(id_p_kerja) FROM data_perjanjian_kerja x WHERE x.nik = a.nik)':'WHERE id_p_kerja = (SELECT max(id_p_kerja) FROM data_perjanjian_kerja x WHERE x.nik = a.nik)';
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_perjanjian_kerja cnt where cnt.nik = a.nik) as jum,
		b.nama as nama_status_lama,
		c.nama as nama_status_baru,
		jbt.nama as nama_jabatan_baru,
		e.nama as nama_loker_baru,
		f.nama as nama_karyawan,
		f.nik as nik_karyawan,
		f.status_emp as aktifOrNot,
		h.nama as nama_mengetahui,
		i.nama as jbt_mengetahui,
		j.nama as nama_menyetujui,
		k.nama as jbt_menyetujui
		FROM data_perjanjian_kerja a
		LEFT JOIN master_surat_perjanjian b ON b.kode_perjanjian = a.status_lama
		LEFT JOIN master_surat_perjanjian c ON c.kode_perjanjian = a.status_baru
		LEFT JOIN karyawan f ON f.nik = a.nik
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = f.jabatan
		LEFT JOIN master_loker e ON e.kode_loker = f.loker
		LEFT JOIN karyawan h ON h.id_karyawan = a.mengetahui
		LEFT JOIN master_jabatan i ON i.kode_jabatan = h.jabatan
		LEFT JOIN karyawan j ON j.id_karyawan = a.menyetujui
		LEFT JOIN master_jabatan k ON k.kode_jabatan = j.jabatan
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getPerjanjianKerja($id, $row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_status_lama,
		e.nama as nama_status_baru,
		h.nama as nama_karyawan,
		h.tempat_lahir as tempat_lahir,
		h.tgl_lahir as tgl_lahir,
		h.alamat_asal_jalan as alamat_asal_jalan,
		h.alamat_asal_desa as alamat_asal_desa,
		h.alamat_asal_kecamatan as alamat_asal_kecamatan,
		h.alamat_asal_kabupaten as alamat_asal_kabupaten,
		h.alamat_asal_provinsi as alamat_asal_provinsi,
		h.alamat_asal_pos as alamat_asal_pos,
		h.gaji_pokok as jenis_gaji,
		h.gaji as gaji_non_matrix,
		h.rekening,
		j.alamat_asal_jalan as jalan_atasan,
		j.alamat_asal_desa as desa_atasan,
		j.alamat_asal_kecamatan as kecamatan_atasan,
		j.alamat_asal_kabupaten as kabupaten_atasan,
		j.alamat_asal_provinsi as provinsi_atasan,
		j.alamat_asal_pos as pos_atasan,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_jabatan,
		m.nama as nama_loker,
		n.nama as nama_menyetujui,
		o.nama as jbt_menyetujui,
		doc.file as nama_file,
		p.gapok as gapok,
		stt.nama as nama_status');
		$this->db->from('data_perjanjian_kerja AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_surat_perjanjian AS d', 'd.kode_perjanjian = a.status_lama', 'left');
		$this->db->join('master_surat_perjanjian AS e', 'e.kode_perjanjian = a.status_baru', 'left');
		$this->db->join('karyawan AS h', 'h.nik = a.nik', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('master_jabatan AS l', 'l.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS m', 'm.kode_loker = h.loker', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = e.kode_dokumen', 'left');
		$this->db->join('master_grade AS p', 'p.kode_grade = h.grade', 'left');
		$this->db->join('master_status_karyawan AS stt', 'stt.kode_status = a.status_karyawan', 'left');
		$this->db->where('id_p_kerja',$id); 
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function listPerjanjianNik($nik){
		$sql="SELECT * FROM data_perjanjian_kerja WHERE id_p_kerja=(SELECT max(id_p_kerja) FROM data_perjanjian_kerja WHERE nik = '$nik')";
		return $this->db->query($sql)->row_array();
	}
	public function getPilihKaryawanPerjanjian()
	{
		$filter=$this->model_global->getFilterbyBagian();
		$this->db->select('emp.id_karyawan as id_karyawan,emp.nama as nama,emp.nik as knik,c.nama as nama_status,b.*');
		$this->db->from('karyawan AS emp');
		$this->db->join('data_perjanjian_kerja AS b', 'b.no_sk_baru = emp.status_perjanjian', 'left');
		$this->db->join('master_surat_perjanjian AS c', 'c.kode_perjanjian = b.status_baru', 'left');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->group_by('emp.nik');
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPilihKaryawanPerjanjianNIK($nik)
	{
		$this->db->select('emp.nama as nama,emp.nik as knik,c.nama as nama_status,b.*');
		$this->db->from('karyawan AS emp');
		$this->db->join('data_perjanjian_kerja AS b', 'b.no_sk_baru = emp.status_perjanjian', 'left');
		$this->db->join('master_surat_perjanjian AS c', 'c.kode_perjanjian = b.status_baru', 'left');
		$this->db->where('emp.nik',$nik);
		// $this->db->where('emp.status_emp',1);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListPerjanjianKerjaNik($nik,$fo=null)
	{
		$where=['a.nik'=>$nik];
		$this->db->select('a.*,
		d.nama as nama_status_lama,
		e.nama as nama_status_baru,
		h.nama as nama_karyawan,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui');
		$this->db->from('data_perjanjian_kerja AS a');
		$this->db->join('master_surat_perjanjian AS d', 'd.kode_perjanjian = a.status_lama', 'left');
		$this->db->join('master_surat_perjanjian AS e', 'e.kode_perjanjian = a.status_baru', 'left');
		$this->db->join('karyawan AS h', 'h.nik = a.nik', 'left');
		$this->db->join('karyawan AS j', 'j.nik = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.nik = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		$this->db->where($where); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkPerjanjianCode($code)
	{
		return $this->model_global->checkCode($code,'data_perjanjian_kerja','no_sk_baru');
	}
    public function getAgreementEnd($where = false)
    {
		$sc = "SELECT 
        emp.nik as nik_karyawan, 
        emp.nama as nama_karyawan, 
        jbt.nama as nama_jabatan,
        b.nama as nama_bagian, 
        COUNT(a.nik) as jum, 
		a.berlaku_sampai_baru as tgl_end,
		m_p.notif_exp as expire_notif,
		m_p.kode_perjanjian,
		ABS(DATEDIFF(CURDATE(), a.berlaku_sampai_baru)) as left_day,
		a.no_sk_baru
        FROM data_perjanjian_kerja as a 
	    LEFT JOIN data_perjanjian_kerja pr on pr.nik = a.nik and pr.id_p_kerja > a.id_p_kerja 
		LEFT JOIN karyawan emp ON emp.nik = a.nik
		LEFT JOIN master_surat_perjanjian m_p ON m_p.kode_perjanjian = a.status_baru
        LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = emp.jabatan
        LEFT JOIN master_bagian b ON b.kode_bagian = jbt.kode_bagian";
		$filter=$this->model_global->getFilterbyBagian();		
		if (!empty($filter)){
			$sc.=' WHERE  emp.status_emp=1 AND '.$filter;
			$wh=' WHERE ';
		}else{
			$sc.=' WHERE  emp.status_emp=1 AND pr.berlaku_sampai_baru is null';
		}
		$sc.=" GROUP BY emp.nik,emp.nama,jbt.nama,b.nama,tgl_end,expire_notif,a.id_p_kerja";		
		if ($where){
			$sc.=$where;
		}
		$sc.=" ORDER BY tgl_end ASC";
		$query=$this->db->query($sc)->result();
		return $query;
    }
//================================================== PERINGATAN KERJA =======================================================
	public function getListDataPeringatan($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_peringatan = (SELECT max(id_peringatan) FROM data_peringatan_karyawan x WHERE x.id_karyawan = a.id_karyawan)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE d.nik = '".$where['nik']."'";
			}else{
				// $bagianx=($where['bagian']!=null) ? " jbt.kode_bagian = '".$where['bagian']."' " : '';
				$lokasix=($where['unit']!=null) ? " f.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl_sk) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl_sk) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_peringatan_karyawan cnt where cnt.id_karyawan = a.id_karyawan) as jum,
		b.nama as nama_status_lama,
		c.nama as nama_status_baru,
		d.nik as nik_karyawan,
		d.nama as nama_karyawan,
		jbt.nama as nama_jabatan,
		f.nama as nama_loker,
		g.nama as nama_mengetahui,
		h.nama as nama_menyetujui,
		i.nama as jbt_mengetahui,
		j.nama as jbt_menyetujui
		FROM data_peringatan_karyawan a
		LEFT JOIN master_surat_peringatan b ON b.kode_sp = a.status_asal
		LEFT JOIN master_surat_peringatan c ON c.kode_sp = a.status_baru
		LEFT JOIN karyawan d ON d.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = d.jabatan
		LEFT JOIN master_loker f ON f.kode_loker = d.loker
		LEFT JOIN karyawan g ON g.id_karyawan = a.mengetahui
		LEFT JOIN karyawan h ON h.id_karyawan = a.menyetujui
		LEFT JOIN master_jabatan i ON i.kode_jabatan = g.jabatan
		LEFT JOIN master_jabatan j ON j.kode_jabatan = h.jabatan
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getPilihKaryawanPeringatan()
	{
		$filter=$this->model_global->getFilterbyBagian();
		$this->db->select('emp.id_karyawan as id_kar,emp.nama as nama,emp.nik as nik,emp.status_disiplin as kode_disiplin,d.nama as nama_disiplin,b.*');
		$this->db->from('karyawan AS emp');
		$this->db->join('data_peringatan_karyawan AS b', 'b.status_baru = emp.status_disiplin', 'left');
		$this->db->join('master_surat_peringatan AS c', 'c.kode_sp = b.status_baru', 'left');
		$this->db->join('master_surat_peringatan AS d', 'emp.status_disiplin = d.kode_sp', 'left');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->group_by('emp.nik');
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPeringatanKerja($id=null,$where=null,$row=null)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_status_lama,
		e.nama as nama_status_baru,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_jabatan,
		m.nama as nama_loker,
		n.nama as nama_menyetujui,
		o.nama as jbt_menyetujui,
		p.nama as nama_dibuat,
		q.nama as jbt_dibuat,
		doc.file as nama_file,
		dd.kode as kode_denda');
		$this->db->from('data_peringatan_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_surat_peringatan AS d', 'd.kode_sp = a.status_asal', 'left');
		$this->db->join('master_surat_peringatan AS e', 'e.kode_sp = a.status_baru', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('master_jabatan AS l', 'l.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS m', 'm.kode_loker = h.loker', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('karyawan AS p', 'p.id_karyawan = a.dibuat', 'left');
		$this->db->join('master_jabatan AS q', 'q.kode_jabatan = p.jabatan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = e.kode_dokumen', 'left');
		$this->db->join('data_denda AS dd', 'dd.kode_peringatan = a.no_sk', 'left');
		if(!empty($id)){
			$this->db->where('a.id_peringatan',$id);
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($row)){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListPeringatanNik($nik, $fo=null)
	{
		$where=['h.nik'=>$nik];
		$this->db->select('a.*,
		d.nama as nama_status_lama,
		e.nama as nama_status_baru,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui');
		$this->db->from('data_peringatan_karyawan AS a');
		$this->db->join('master_surat_peringatan AS d', 'd.kode_sp = a.status_asal', 'left');
		$this->db->join('master_surat_peringatan AS e', 'e.kode_sp = a.status_baru', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->where($where); 
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkPeringatanCode($code)
	{
		return $this->model_global->checkCode($code,'data_peringatan_karyawan','no_sk');
	}
//======================================= DATA GRADE KARYAWAN ==============================================
	public function getListGrade($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE a.id_grade = (SELECT max(id_grade) FROM data_grade_karyawan x WHERE x.id_karyawan = a.id_karyawan)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE d.nik = '".$where['nik']."'";
			}else{
				$lokasix=($where['unit']!=null) ? " k.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl_sk) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl_sk) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];

				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND a.id_grade = (SELECT max(id_grade) FROM data_grade_karyawan x WHERE x.id_karyawan = a.id_karyawan)':null;
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_grade_karyawan cnt where cnt.id_karyawan = a.id_karyawan) as jum,
		b.nama as nama_grade_lama,
		c.nama as nama_grade_baru,
		d.nik as nik_karyawan,
		d.nama as nama_karyawan,
		e.nama as nama_loker_grade,
		l.nama as nama_loker_grade_lama,
			jbt.nama as nama_jabatan,
			g.nama as nama_mengetahui,
			h.nama as jbt_mengetahui,
			i.nama as nama_menyetujui,
			j.nama as jbt_menyetujui,
			k.nama as nama_loker
		FROM data_grade_karyawan a
		LEFT JOIN master_grade b ON b.kode_grade = a.grade_asal
		LEFT JOIN master_grade c ON c.kode_grade = a.grade_baru
		LEFT JOIN karyawan d ON d.id_karyawan = a.id_karyawan
		LEFT JOIN master_loker e ON e.kode_loker = c.kode_loker
		LEFT JOIN master_loker l ON l.kode_loker = b.kode_loker
			LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = d.jabatan
			LEFT JOIN karyawan g ON g.id_karyawan = a.mengetahui
			LEFT JOIN master_jabatan h ON h.kode_jabatan = g.jabatan
			LEFT JOIN karyawan i ON i.id_karyawan = a.menyetujui
			LEFT JOIN master_jabatan j ON j.kode_jabatan = i.jabatan
			LEFT JOIN master_loker k ON k.kode_loker = d.loker
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getPilihKaryawanGrade()
	{
		$filter=$this->model_global->getFilterbyBagian();
		$this->db->select('emp.id_karyawan as id_kar,emp.nama as nama,emp.nik as nik,emp.grade as kode_grade,d.nama as nama_grade,e.nama as nama_loker,b.*');
		$this->db->from('karyawan AS emp');
		$this->db->join('data_grade_karyawan AS b', 'b.grade_baru = emp.grade', 'left');
		$this->db->join('master_grade AS c', 'c.kode_grade = b.grade_baru', 'left');
		$this->db->join('master_grade AS d', 'emp.grade = d.kode_grade', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = d.kode_loker', 'left');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->group_by('emp.nik');
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function getGradeKaryawan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_grade_lama,
		e.nama as nama_grade_baru,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_jabatan,
		m.nama as nama_loker,
		n.nama as nama_menyetujui,
		o.nama as jbt_menyetujui,
		p.nama as nama_loker_grade,
		q.nama as nama_loker_grade_lama,
		doc.file as nama_file');
		$this->db->from('data_grade_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_grade AS d', 'd.kode_grade = a.grade_asal', 'left');
		$this->db->join('master_grade AS e', 'e.kode_grade = a.grade_baru', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('master_jabatan AS l', 'l.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS m', 'm.kode_loker = h.loker', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('master_loker AS p', 'p.kode_loker = e.kode_loker', 'left');
		$this->db->join('master_loker AS q', 'q.kode_loker = d.kode_loker', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = e.kode_dokumen', 'left');
		$this->db->where('a.id_grade',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListGradeNik($nik, $fo=null)
	{
		$where=['h.nik'=>$nik];
		$this->db->select('a.*,
		d.nama as nama_grade_lama,
		e.nama as nama_grade_baru,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui,
		n.nama as nama_loker_grade');
		$this->db->from('data_grade_karyawan AS a');
		$this->db->join('master_grade AS d', 'd.kode_grade = a.grade_asal', 'left');
		$this->db->join('master_grade AS e', 'e.kode_grade = a.grade_baru', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('master_loker AS n', 'n.kode_loker = e.kode_loker', 'left');
		$this->db->where($where);
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkGradeCode($code)
	{
		return $this->model_global->checkCode($code,'data_peringatan_karyawan','no_sk');
	}
//============================================== KECELAKAAN KERJA ===========================================================
	public function getListKecelakaanKerja($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_kecelakaan = (SELECT max(id_kecelakaan) FROM data_kecelakaan_kerja x WHERE x.id_karyawan = a.id_karyawan)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE d.nik = '".$where['nik']."'";
			}else{
				$lokasix=($where['unit']!=null) ? " k.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND  id_kecelakaan = (SELECT max(id_kecelakaan) FROM data_kecelakaan_kerja x WHERE x.id_karyawan = a.id_karyawan)':'WHERE id_kecelakaan = (SELECT max(id_kecelakaan) FROM data_kecelakaan_kerja x WHERE x.id_karyawan = a.id_karyawan)';
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}

		$sc="SELECT a.*,(select count(*) from data_kecelakaan_kerja cnt where cnt.id_karyawan = a.id_karyawan) as jum,
		b.nama as nama_kategori_kecelakaan,
		d.nik as nik_karyawan,
		d.nama as nama_karyawan,
			jbt.nama as nama_jabatan,
			g.nama as nama_mengetahui,
			h.nama as jbt_mengetahui,
			i.nama as nama_menyatakan,
			j.nama as jbt_menyatakan,
			k.nama as nama_loker,
			l.nama as nama_saksi_1,
			m.nama as jbt_saksi_1,
			n.nama as nama_saksi_2,
			o.nama as jbt_saksi_2,
			p.nama as nama_penanggungjawab,
			q.nama as jbt_penanggungjawab,
			r.nama as nama_loker_kejadian,
			s.nama as nama_rumahsakit
		FROM data_kecelakaan_kerja a
		LEFT JOIN master_kategori_kecelakaan b ON b.kode_kategori_kecelakaan = a.kode_kategori_kecelakaan
		LEFT JOIN karyawan d ON d.id_karyawan = a.id_karyawan
			LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = d.jabatan
			LEFT JOIN karyawan g ON g.id_karyawan = a.mengetahui
			LEFT JOIN master_jabatan h ON h.kode_jabatan = g.jabatan
			LEFT JOIN karyawan i ON i.id_karyawan = a.menyatakan
			LEFT JOIN master_jabatan j ON j.kode_jabatan = i.jabatan
			LEFT JOIN master_loker k ON k.kode_loker = d.loker
			LEFT JOIN karyawan l ON l.id_karyawan = a.saksi_1
			LEFT JOIN master_jabatan m ON m.kode_jabatan = l.jabatan
			LEFT JOIN karyawan n ON n.id_karyawan = a.saksi_2
			LEFT JOIN master_jabatan o ON o.kode_jabatan = n.jabatan
			LEFT JOIN karyawan p ON p.id_karyawan = a.penanggungjawab
			LEFT JOIN master_jabatan q ON q.kode_jabatan = p.jabatan
			LEFT JOIN master_loker r ON r.kode_loker = a.kode_loker
			LEFT JOIN master_daftar_rs s ON s.kode_master_rs = a.kode_master_rs
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getPilihKaryawanKecelakaanKerja()
	{
		$filter=$this->model_global->getFilterbyBagian();
		$this->db->select('emp.id_karyawan as id_kar,emp.nama as nama,emp.nik as nik,emp.jabatan as kode_jabatan,jbt.nama as nama_jabatan');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->group_by('emp.nik');
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKecelakaanKerjaKaryawan($id)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_kategori_kecelakaan,
		e.nama as nama_rs,
		e.alamat as alamat_rs,
		f.nama as nama_loker_kejadian,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		h.bpjstk as bpjs_tk,
		l.nama as nama_jabatan,
		m.nama as nama_loker,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		n.nama as nama_menyatakan,
		o.nama as jbt_menyatakan,
		p.nama as nama_saksi_1,
		q.nama as jbt_saksi_1,
		r.nama as nama_saksi_2,
		s.nama as jbt_saksi_2,
		t.nama as nama_penanggungjawab,
		u.nama as jbt_penanggungjawab,
		doc.file as nama_file');
		$this->db->from('data_kecelakaan_kerja AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_kategori_kecelakaan AS d', 'd.kode_kategori_kecelakaan = a.kode_kategori_kecelakaan', 'left');
		$this->db->join('master_daftar_rs AS e', 'e.kode_master_rs = a.kode_master_rs', 'left');
		$this->db->join('master_loker AS f', 'f.kode_loker = a.kode_loker', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS l', 'l.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS m', 'm.kode_loker = h.loker', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyatakan', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('karyawan AS p', 'p.id_karyawan = a.saksi_1', 'left');
		$this->db->join('master_jabatan AS q', 'q.kode_jabatan = p.jabatan', 'left');
		$this->db->join('karyawan AS r', 'r.id_karyawan = a.saksi_2', 'left');
		$this->db->join('master_jabatan AS s', 's.kode_jabatan = r.jabatan', 'left');
		$this->db->join('karyawan AS t', 't.id_karyawan = a.penanggungjawab', 'left');
		$this->db->join('master_jabatan AS u', 'u.kode_jabatan = t.jabatan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = d.kode_dokumen', 'left');
		$this->db->where('id_kecelakaan',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListKecelakaanKerjaNik($nik, $fo = null)
	{
		$where=['h.nik'=>$nik];
		$this->db->select('a.*,
		d.nama as nama_kategori_kecelakaan,
		e.nama as nama_rs,
		f.nama as nama_loker,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui');
		$this->db->from('data_kecelakaan_kerja AS a');
		$this->db->join('master_kategori_kecelakaan AS d', 'd.kode_kategori_kecelakaan = a.kode_kategori_kecelakaan', 'left');
		$this->db->join('master_daftar_rs AS e', 'e.kode_master_rs = a.kode_master_rs', 'left');
		$this->db->join('master_loker AS f', 'f.kode_loker = a.kode_loker', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		$this->db->where($where); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkKecelakaanKerjaCode($code)
	{
		return $this->model_global->checkCode($code,'data_peringatan_karyawan','no_sk');
	}
//==================================== KARYAWAN TIDAK AKTIF ===============================================
	public function getListKaryawanNonAktif($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_kta = (SELECT max(id_kta) FROM data_karyawan_tidak_aktif x WHERE x.id_karyawan = a.id_karyawan) AND f.status_emp = 0';
		}else{
			if($where['param']=='all'){
				$next = "WHERE f.status_emp = 0";
			}elseif($where['param']=='nik'){
				$next = "WHERE f.nik = '".$where['nik']."'";
			}else{
				$lokasix=($where['unit']!=null) ? " c.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl_keluar) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl_keluar) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];

				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND  id_kta = (SELECT max(id_kta) FROM data_karyawan_tidak_aktif x WHERE x.id_karyawan = a.id_karyawan)':'WHERE id_kta = (SELECT max(id_kta) FROM data_karyawan_tidak_aktif x WHERE x.id_karyawan = a.id_karyawan)';
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_karyawan_tidak_aktif cnt where cnt.id_karyawan = a.id_karyawan) as jum,
		jbt.nama as nama_jabatan,
		c.nama as nama_loker,
		f.nama as nama_karyawan,
		f.nik as nik_karyawan,
		f.tgl_masuk as tgl_masuk,
			g.nama as nama_mengetahui,
			h.nama as jbt_mengetahui,
			i.nama as nama_menyetujui,
			j.nama as jbt_menyetujui,
			k.nama as nama_alasan
		FROM karyawan f
		LEFT JOIN data_karyawan_tidak_aktif a ON f.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = f.jabatan
		LEFT JOIN master_loker c ON c.kode_loker = f.loker
			LEFT JOIN karyawan g ON g.id_karyawan = a.mengetahui
			LEFT JOIN master_jabatan h ON h.kode_jabatan = g.jabatan
			LEFT JOIN karyawan i ON i.id_karyawan = a.menyetujui
			LEFT JOIN master_jabatan j ON j.kode_jabatan = i.jabatan
			LEFT JOIN master_alasan_keluar k ON k.kode_alasan_keluar = a.alasan
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getListKaryawanNonAktifAll($usage = 'nosearch',$where = null)
	{
		if($usage=='nosearch'){
			$next =  'WHERE emp.status_emp=1';
		}else{
			if($where['param']=='all'){
				$next = 'WHERE emp.status_emp=0';
			}elseif($where['param']=='nik'){
				$next = "WHERE emp.nik = '".$where['nik']."'";
			}else{
				$bagianx=($where['bagian']!=null) ? " bag_bagian.kode_bagian = '".$where['bagian']."' " : '';
				$lokasix=($where['unit']!=null) ? " loker.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(emp.tgl_masuk) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(emp.tgl_masuk) = ".$where['tahun'] : '';
				$all_query = [$bagianx,$lokasix,$bulanx,$tahunx];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				$next = (!empty($nquery))?'WHERE '.$nquery.' AND emp.status_emp=0':null;
			}
		}
		$dt_struktur=$this->model_master->getListLevelStruktur(true);
		$sub_query_join='';
		$sub_query_select='';
		if(isset($dt_struktur)){
			$st=0;
			foreach ($dt_struktur as $keys=>$dt_s){
				$nama=($st > 0)?$dt_struktur[$keys-1]->nama:$dt_s->nama; 
				$lower_ats=strtolower(str_replace('/','_',$nama));
				$lower_ats=strtolower(str_replace('-','_',$lower_ats));
				$lower_ats=strtolower(str_replace(' ','_',$lower_ats));
				$lower=strtolower(str_replace('/','_',$dt_s->nama));
				$lower=strtolower(str_replace('-','_',$lower));
				$lower=strtolower(str_replace(' ','_',$lower));
				$sub_query_join.="LEFT JOIN master_level_struktur lvstr_".$lower." ON lvstr_".$lower.".nama LIKE '%".$dt_s->nama."%'
				LEFT JOIN master_bagian bag_".$lower." ON bag_".$lower.".kode_level_struktur = lvstr_".$lower.".kode_level_struktur AND 
				IF ((lvstr_".$lower.".squence = lstr.squence),(bag_".$lower.".kode_bagian = jbt.kode_bagian),(bag_".$lower.".kode_bagian = bag_".$lower_ats.".atasan))
				LEFT JOIN master_loker loker_".$lower." ON loker_".$lower.".kode_loker = bag_".$lower.".kode_loker
				";
				$sub_query_select.='bag_'.$lower.'.nama as nama_'.$lower.',loker_'.$lower.'.nama as nama_lokasi_'.$lower.',';
				$st++;			
			}
		}
		
		$sc="SELECT emp.*,
		jbt.nama as nama_jabatan,
		loker.nama as nama_loker,
		$sub_query_select
		grd.nama as nama_grade,
		d.nama as nama_loker_grade,
		stt.nama as nama_status_karyawan,
		bank.nama as nama_akun_bank,
		lvljbt.nama as nama_level_jabatan
		FROM karyawan emp
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = emp.jabatan
		LEFT JOIN master_loker loker ON loker.kode_loker = emp.loker
		LEFT JOIN master_grade grd ON grd.kode_grade = emp.grade
		LEFT JOIN master_loker d ON grd.kode_loker = d.kode_loker
		LEFT JOIN master_status_karyawan stt ON stt.kode_status = emp.status_karyawan
		LEFT JOIN master_bank bank ON bank.kode = emp.nama_bank
		LEFT JOIN master_bagian bag ON bag.kode_bagian = jbt.kode_bagian
		LEFT JOIN master_level_struktur lstr ON lstr.kode_level_struktur = bag.kode_level_struktur
		LEFT JOIN master_level_jabatan lvljbt ON lvljbt.kode_level_jabatan = jbt.kode_level
		$sub_query_join
		$next  ORDER BY update_date DESC";
		$query=$this->db->query($sc)->result();	
		return $query;
	}
	public function getPilihKaryawanNonAktif()
	{
		$filter=$this->model_global->getFilterbyBagian();
		$this->db->select('emp.id_karyawan as id_karyawan,emp.nama as nama, emp.jabatan as jabatan, emp.loker as loker, emp.nik as nik, emp.tgl_masuk as tgl_masuk, loker.nama as nama_loker, jbt.nama as nama_jabatan');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->group_by('emp.nik');
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKaryawanNonAktif($id)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		h.id_karyawan as id_karyawan,
		h.nik as nik,
		h.nama as nama_karyawan,
		h.tgl_masuk as tgl_masuk,
		h.tempat_lahir as tempat_lahir,
		h.tgl_lahir as tgl_lahir,
		h.alamat_asal_jalan as alamat_asal_jalan,
		h.alamat_asal_desa as alamat_asal_desa,
		h.alamat_asal_kecamatan as alamat_asal_kecamatan,
		h.alamat_asal_kabupaten as alamat_asal_kabupaten,
		h.alamat_asal_provinsi as alamat_asal_provinsi,
		h.alamat_asal_pos as alamat_asal_pos,
		h.status_emp as status_emp,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui,
		n.nama as nama_alasan,
		doc.file as nama_file');
		$this->db->from('data_karyawan_tidak_aktif AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('master_alasan_keluar AS n', 'n.kode_alasan_keluar = a.alasan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = n.dokumen_keterangan', 'left');
		$this->db->where('id_kta',$id); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKaryawanNonAktifWhere($where)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		h.id_karyawan as id_karyawan,
		h.nik as nik,
		h.status_pajak,
		h.npwp,
		h.jabatan as kode_jabatan,
		d.kode_bagian,
		h.loker as kode_loker,
		h.grade as kode_grade,
		h.tgl_masuk,
		h.nama as nama_karyawan,
		h.tgl_masuk as tgl_masuk,
		h.tempat_lahir as tempat_lahir,
		h.tgl_lahir as tgl_lahir,
		h.alamat_asal_jalan as alamat_asal_jalan,
		h.alamat_asal_desa as alamat_asal_desa,
		h.alamat_asal_kecamatan as alamat_asal_kecamatan,
		h.alamat_asal_kabupaten as alamat_asal_kabupaten,
		h.alamat_asal_provinsi as alamat_asal_provinsi,
		h.alamat_asal_pos as alamat_asal_pos,
		h.status_emp as status_emp,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui,
		n.nama as nama_alasan,
		doc.file as nama_file');
		$this->db->from('data_karyawan_tidak_aktif AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		// $this->db->join('master_grade AS grd', 'h.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('master_alasan_keluar AS n', 'n.kode_alasan_keluar = a.alasan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = n.dokumen_keterangan', 'left');
		$this->db->where($where); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKaryawanNonAktifWhere2($where, $order='h.update_date DESC')
	{
		$this->db->select('
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		bag.nama as bagian,
		h.id_karyawan as id_karyawan,
		h.nik as nik,
		h.status_pajak,
		h.npwp,
		h.jabatan as kode_jabatan,
		d.kode_bagian,
		h.loker as kode_loker,
		h.grade as kode_grade,
		grd.nama as nama_grade,
		h.tgl_masuk,
		h.nama as nama_karyawan,
		h.npwp as no_npwp,
		d.nama as nama_jabatan,
		bag.nama as nama_bagian,
		e.nama as nama_loker,
		grd.nama as nama_grade,
		h.status_pajak as status_ptkp,
		h.no_ktp,
		h.status_emp,
		h.nama as nama,
		a.tgl_keluar,
		a.keterangan,
		h.tgl_masuk as tgl_masuk');
		$this->db->from('data_karyawan_tidak_aktif AS a');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_bagian AS bag', 'd.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_grade AS grd', 'h.grade = grd.kode_grade', 'left');
		$this->db->where($where);
		$this->db->order_by($order);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getEmployeeAllActive2($where=null)
	{
		$this->db->select('
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		bag.nama as bagian,
		grd.nama as nama_grade,
		h.id_karyawan as id_karyawan,
		h.nik as nik,
		h.status_pajak,
		h.npwp,
		h.jabatan as kode_jabatan,
		d.kode_bagian,
		h.loker as kode_loker,
		h.grade as kode_grade,
		h.tgl_masuk,
		h.nama as nama_karyawan,
		h.npwp as no_npwp,
		d.nama as nama_jabatan,
		bag.nama as nama_bagian,
		e.nama as nama_loker,
		grd.nama as nama_grade,
		h.status_pajak as status_ptkp,
		h.nama as nama,
		h.status_emp,
		h.no_ktp,
		h.tgl_masuk as tgl_masuk');
		$this->db->from('karyawan AS h');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');	
		$this->db->join('master_bagian AS bag', 'd.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_grade AS grd', 'h.grade = grd.kode_grade', 'left');
		if (!empty($where)){
			$this->db->where($where); 
		}
		$this->db->order_by('h.update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListKaryawanNonAktifIDK($idk)
	{
		$this->db->select('a.*,emp.nik as nik,emp.nama as nama_kar,emp.tgl_masuk as tgl_masuk,al.nama as nama_alasan,j.nama as nama_mengetahui,k.nama as jbt_mengetahui,l.nama as nama_menyetujui,m.nama as jbt_menyetujui,');
		$this->db->from('data_karyawan_tidak_aktif AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_alasan_keluar AS al', 'al.kode_alasan_keluar = a.alasan', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->where('a.id_karyawan',$idk); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkKaryawanNonAktifCode($code)
	{
		return $this->model_global->checkCode($code,'data_karyawan_tidak_aktif','no_sk');
	}
	public function emp_finger($id){
		return $this->db->get_where('karyawan',array('id_finger'=>$id))->result();
	}
	public function getEmployeeFinger($id_finger)
	{
		if (empty($id_finger)) 
			return null;
		$this->db->select('emp.*,loker.nama as nama_loker,jbt.nama as nama_jabatan,bag.nama as bagian,b.nama as nama_buat, c.nama as nama_update, grd.nama as nama_grade, d.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		$this->db->where('emp.id_finger',$id_finger);
		$this->db->where('emp.status_emp',1); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function finger_id($id){
		$sc="SELECT id_finger FROM karyawan where id_karyawan='$id' AND status_emp = 1";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function cekPilihPerjanjian($status){
		$sc="SELECT * FROM master_surat_perjanjian where kode_status='$status' AND status = 1";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function cekPilihStatusKaryawan($kode_perjanjian){
		$sc="SELECT a.*,
		b.nama as nama_status
		FROM master_surat_perjanjian a
		LEFT JOIN master_status_karyawan b ON b.kode_status = a.kode_status  where a.kode_perjanjian='$kode_perjanjian' AND a.status = 1";
		$query=$this->db->query($sc)->result();
		return $query;
	}
//==================================== DATA EXIT INTERVIEW ===============================================
	public function getListExitInterview($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = '';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE d.nik = '".$where['nik']."'";
			}else{
				$lokasix=($where['unit']!=null) ? " c.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl_keluar) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl_keluar) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];

				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = '';
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,
		jbt.nama as nama_jabatan,
		c.nama as nama_loker,
		f.nama as nama_karyawan,
		f.nik as nik_karyawan,
		f.tgl_masuk as tgl_masuk,
		k.nama as nama_alasan
		FROM data_exit_interview a
		LEFT JOIN karyawan f ON f.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = f.jabatan
		LEFT JOIN master_loker c ON c.kode_loker = f.loker
		LEFT JOIN master_alasan_keluar k ON k.kode_alasan_keluar = a.alasan_keluar
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getListExitInterviewID($id)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		h.id_karyawan as id_karyawan,
		h.nik as nik,
		h.nama as nama_karyawan,
		h.tgl_masuk as tgl_masuk,
		h.tempat_lahir as tempat_lahir,
		h.tgl_lahir as tgl_lahir,
		h.alamat_asal_jalan as alamat_asal_jalan,
		h.alamat_asal_desa as alamat_asal_desa,
		h.alamat_asal_kecamatan as alamat_asal_kecamatan,
		h.alamat_asal_kabupaten as alamat_asal_kabupaten,
		h.alamat_asal_provinsi as alamat_asal_provinsi,
		h.alamat_asal_pos as alamat_asal_pos,
		h.status_emp as status_emp,
		n.nama as nama_alasan,
		doc.file as nama_file');
		$this->db->from('data_exit_interview AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_alasan_keluar AS n', 'n.kode_alasan_keluar = a.alasan_keluar', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = n.dokumen_keterangan', 'left');
		$this->db->where('id_exit',$id); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListExitInterviewNik($nik)
	{
		$where=['h.nik'=>$nik,'a.stt_del'=>1];
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		h.id_karyawan as id_karyawan,
		h.nik as nik,
		h.nama as nama_karyawan,
		h.tgl_masuk as tgl_masuk,
		h.tempat_lahir as tempat_lahir,
		h.tgl_lahir as tgl_lahir,
		h.alamat_asal_jalan as alamat_asal_jalan,
		h.alamat_asal_desa as alamat_asal_desa,
		h.alamat_asal_kecamatan as alamat_asal_kecamatan,
		h.alamat_asal_kabupaten as alamat_asal_kabupaten,
		h.alamat_asal_provinsi as alamat_asal_provinsi,
		h.alamat_asal_pos as alamat_asal_pos,
		h.status_emp as status_emp,
		n.nama as nama_alasan,
		doc.file as nama_file');
		$this->db->from('data_exit_interview AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_alasan_keluar AS n', 'n.kode_alasan_keluar = a.alasan_keluar', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = n.dokumen_keterangan', 'left');
		$this->db->where($where);
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}

// ==================================== DENDA KARYAWAN ===============================================
	public function getListDenda($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_denda = (SELECT max(id_denda) FROM data_denda x WHERE x.id_karyawan = a.id_karyawan)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE d.nik = '".$where['nik']."'";
			}else{
				$lokasix=($where['unit']!=null) ? " k.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];

				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND  id_denda = (SELECT max(id_denda) FROM data_denda x WHERE x.id_karyawan = a.id_karyawan)':null;
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_denda c where c.id_karyawan = a.id_karyawan and c.status_denda is null) as jum,
		(select count(*) from data_denda c where  c.id_karyawan = a.id_karyawan and c.status_denda='non_peringatan') as jum_non,
		c.nik as nik_karyawan,
		c.nama as nama_karyawan,
		jbt.nama as nama_jabatan,
		e.nama as nama_loker
		FROM data_denda a
		LEFT JOIN data_peringatan_karyawan b ON a.kode_peringatan = b.no_sk
		LEFT JOIN karyawan c ON c.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = c.jabatan
		LEFT JOIN master_loker e ON e.kode_loker = c.loker
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getDenda($id)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_karyawan,
		d.nik as nik_karyawan,
		e.nama as nama_jabatan,
		f.nama as nama_loker');
		$this->db->from('data_denda AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS e', 'e.kode_jabatan = d.jabatan', 'left');
		$this->db->join('master_loker AS f', 'f.kode_loker = d.loker', 'left');
		$this->db->where('id_denda',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListDendaNik($nik, $fo=null)
	{
		$where=['d.nik'=>$nik];
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,d.nik as nik_karyawan,d.nama as nama_karyawan,
			(SELECT saldo_denda FROM data_denda_angsuran x WHERE x.kode_denda = a.kode AND x.id_angsuran = (SELECT MAX(y.id_angsuran) FROM data_denda_angsuran y WHERE y.kode_denda = x.kode_denda)) as max_saldo,
			(select count(*) from data_denda_angsuran cnt where cnt.kode_denda = a.kode) as sudah_diangsur');
		$this->db->from('data_denda AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->where($where); 
		$this->db->order_by('create_date','DESC');
		$this->db->group_by('kode_peringatan');
		$this->db->having('a.status_denda =', null);
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		// $this->db->group_by('CASE WHEN a.kode_peringatan IS NOT NULL THEN a.kode_peringatan END',FALSE);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListDendaNikNon($nik, $fo=null)
	{
		$where=['d.nik'=>$nik];
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,d.nik as nik_karyawan,d.nama as nama_karyawan,
			(SELECT saldo_denda FROM data_denda_angsuran x WHERE x.kode_denda = a.kode AND x.id_angsuran = (SELECT MAX(y.id_angsuran) FROM data_denda_angsuran y WHERE y.kode_denda = x.kode_denda)) as max_saldo,
			(select count(*) from data_denda_angsuran cnt where cnt.kode_denda = a.kode) as sudah_diangsur');
		$this->db->from('data_denda AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->where($where); 
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		$this->db->order_by('create_date','DESC');
		$this->db->group_by('a.kode_peringatan');
		$this->db->having('a.status_denda =', 'non_peringatan');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataDendaPerId($id)
	{
		$where=['a.id_denda'=>$id];
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,d.nik as nik_karyawan,d.nama as nama_karyawan,jab.nama as nama_jabatan,loker.nama as nama_loker,
			j.nama as nama_mengetahui,
			k.nama as jbt_mengetahui,
			n.nama as nama_menyetujui,
			o.nama as jbt_menyetujui,
			p.nama as nama_dibuat,
			q.nama as jbt_dibuat');
		$this->db->from('data_denda AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = d.jabatan', 'left');
		$this->db->join('master_loker AS loker', 'loker.kode_loker = d.loker', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('karyawan AS p', 'p.id_karyawan = a.dibuat', 'left');
		$this->db->join('master_jabatan AS q', 'q.kode_jabatan = p.jabatan', 'left');
		$this->db->where($where); 
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataAngsuranDenda($kode)
	{
		$where=['a.kode_denda'=>$kode];
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update');
		$this->db->from('data_denda_angsuran AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->where($where); 
		$this->db->order_by('a.tgl_angsuran','ASC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListPesanFO($idk)
	{
		$where=['a.id_karyawan'=>$idk,'a.del_fo'=>1];
		$this->db->select('a.*,b.nama as nama_jenis,emp.nama as nama_karyawan,emp.nik as nik_karyawan,emp.id_karyawan as id_karyawan');
		$this->db->from('data_pesan AS a');
		$this->db->join('master_jenis_pesan AS b', 'b.kode = a.jenis', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->where($where); 
		$this->db->order_by('a.create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPesanFOID($id)
	{
		$where=['a.id_pesan'=>$id];
		$this->db->select('a.*,b.nama as nama_jenis,emp.nama as nama_karyawan,emp.nik as nik_karyawan');
		$this->db->from('data_pesan AS a');
		$this->db->join('master_jenis_pesan AS b', 'b.kode = a.jenis', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->where($where); 
		$this->db->order_by('a.create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
//================================================  ABSENSI KARYAWAN ====================================================
//___________________________________________________IZIN DAN CUTI_______________________________________________________
	public function getLisIzinCuti($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_izin_cuti = (SELECT max(id_izin_cuti) FROM data_izin_cuti_karyawan x WHERE x.id_karyawan = a.id_karyawan)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE b.nik = '".$where['nik']."'";
			}else{
				$lokasix=($where['unit']!=null) ? " d.kode_loker = '".$where['unit']."' " : '';
				$bulanx=($where['bulan']!=null) ? "MONTH(a.tgl_mulai) = ".$where['bulan'] : '';
				$tahunx=($where['tahun']!=null) ? "YEAR(a.tgl_mulai) = ".$where['tahun'] : '';
				$all_query = [$lokasix,$bulanx,$tahunx];

				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND id_izin_cuti = (SELECT max(id_izin_cuti) FROM data_izin_cuti_karyawan x WHERE x.id_karyawan = a.id_karyawan)':'WHERE id_izin_cuti = (SELECT max(id_izin_cuti) FROM data_izin_cuti_karyawan x WHERE x.id_karyawan = a.id_karyawan)';
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_izin_cuti_karyawan cnt where cnt.id_karyawan = a.id_karyawan) as jum,
		jbt.nama as nama_jabatan,
		d.nama as nama_loker,
		b.nama as nama_karyawan,
		b.nik as nik_karyawan,
		e.nama as nama_bagian,
		iz.nama as nama_izin,
		iz.jenis as jenis_izin
		FROM data_izin_cuti_karyawan a
		LEFT JOIN karyawan b ON b.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = b.jabatan
		LEFT JOIN master_loker d ON d.kode_loker = b.loker
		LEFT JOIN master_bagian e ON e.kode_bagian = jbt.kode_bagian
		LEFT JOIN master_izin iz ON iz.kode_master_izin = a.jenis
		$next";
		$bagian=(isset($where['bagian'])?$where['bagian']:null);
		$filter=$this->model_global->getFilterbyBagian($bagian);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getIzinCutiHarianNew($where = null, $bagian = null, $tanggal = null, $bt = null)
	{
		$filter=$this->model_global->getFilterbyBagian();
		$mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'start')));
		$selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'end')));
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		jbt.nama as nama_jabatan,
		e.nama as nama_loker,
		f.nama as nama_jenis_izin,
		f.jenis as jenis_ic,
		f.potongan_gaji,
		f.potong_upah,
		f.potong_cuti,
		f.nama as nama_izin,
		f.jenis as jenis_izin,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		h.foto as foto,
		i.nama as nama_bagian,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui,
		n.nama as nama_menyetujui_2,
		o.nama as jbt_menyetujui_2,
		doc.file as nama_file,');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_izin AS f', 'f.kode_master_izin = a.jenis', 'left');
		$this->db->join('master_bagian AS i', 'i.kode_bagian = jbt.kode_bagian', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui_2', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = f.kode_dokumen', 'left');
		if(!empty($tanggal)){
			$this->db->where("a.tgl_mulai BETWEEN '".$mulai."' AND '".$selesai."'");
 		}
		if(!empty($bt)){
			$this->db->where('MONTH(a.tgl_mulai)',$bt['bulan']);
			$this->db->where('YEAR(a.tgl_mulai)',$bt['tahun']);
 		}
		if(!empty($where)){
			$this->db->where($where);
		}
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('a.status IS NOT NULL '.((!empty($sq))?' AND '.$sq:null));
		$this->db->order_by('a.tgl_mulai', 'DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getIzinCuti($id = null, $where = null, $row = null)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,cb.nama as nama_buat_fo,ub.nama as nama_update_fo,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		f.nama as nama_jenis_izin,
		f.jenis as jenis_ic,
		f.potongan_gaji,
		f.potong_upah,
		f.potong_cuti,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		h.foto as foto,
		i.nama as nama_bagian,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui,
		n.nama as nama_menyetujui_2,
		o.nama as jbt_menyetujui_2,
		doc.file as nama_file,');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_izin AS f', 'f.kode_master_izin = a.jenis', 'left');
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui_2', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = f.kode_dokumen', 'left');
		$this->db->join('karyawan AS cb', 'cb.id_karyawan = a.cbfo', 'left');
		$this->db->join('karyawan AS ub', 'ub.id_karyawan = a.ubfo', 'left');
		if(!empty($id)){ $this->db->where('id_izin_cuti',$id); }
		if(!empty($where)){ $this->db->where($where); }
		// $this->db->where('MONTH(a.tgl_mulai)','02');
		// $this->db->where('YEAR(a.tgl_mulai)','2021');
		if(!empty($row)){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getIzinCutiPay($tanggal=null, $where = null, $row = null)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,cb.nama as nama_buat_fo,ub.nama as nama_update_fo,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		f.nama as nama_jenis_izin,
		f.jenis as jenis_ic,
		f.potongan_gaji,
		f.potong_upah,
		f.potong_cuti,
		f.hitung_terlambat,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		h.foto as foto,
		i.nama as nama_bagian,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui,
		n.nama as nama_menyetujui_2,
		o.nama as jbt_menyetujui_2,
		doc.file as nama_file,');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_izin AS f', 'f.kode_master_izin = a.jenis', 'left');
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui_2', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = f.kode_dokumen', 'left');
		$this->db->join('karyawan AS cb', 'cb.id_karyawan = a.cbfo', 'left');
		$this->db->join('karyawan AS ub', 'ub.id_karyawan = a.ubfo', 'left');
		$this->db->where('"'.$tanggal.'" BETWEEN a.tgl_mulai AND a.tgl_selesai');
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($row)){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListIzinCutiNik($nik, $fo=null, $izin_fo=null)
	{
		$where=['b.nik'=>$nik];
		$this->db->select('a.*,
		b.nik as nik_karyawan,
		b.nama as nama_karyawan,
		c.nama as nama_jenis_izin,
		c.jenis as jenis');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_izin AS c', 'c.kode_master_izin = a.jenis', 'left');
		$this->db->where($where); 
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		if($izin_fo=='izin_fo'){
			$this->db->where('a.stt_del',1);
		}
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListIzinCutiWhere($where, $mulai=null, $selesai=null)
	{
		$this->db->select('a.*,
		b.nik as nik_karyawan,
		b.nama as nama_karyawan,
		c.nama as nama_jenis_izin,
		c.jenis as jenis');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_izin AS c', 'c.kode_master_izin = a.jenis', 'left');
		$this->db->where($where);
		if(!empty($mulai) && !empty($selesai)){
			$this->db->where("a.tgl_mulai BETWEEN '".$mulai."' AND '".$selesai."'");
 		}
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListIzinCutiWhereMaxID($where)
	{
		$this->db->select('a.*');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->where("id_izin_cuti = (SELECT max(id_izin_cuti) FROM data_izin_cuti_karyawan x WHERE x.id_karyawan = a.id_karyawan)");
		$this->db->where($where);
		$query=$this->db->get()->row_array();
		// $query=$this->db->get()->result();
		return $query;
	}
	public function checkIzinCutiCode($code)
	{
		return $this->model_global->checkCode($code,'data_izin_cuti_karyawan','kode_izin_cuti');
	}
	public function checkIzinCuti($date)
	{
		if(empty($date))
			return null;
		$data = $this->getLisIzinCuti();
		foreach ($data as $d) {
			if($date >= $d->tgl_mulai AND $date <= $d->tgl_selesai){
				$val = [
					'id_izin_cuti'=>$d->id_izin_cuti,
					'kode_izin_cuti'=>$d->jenis,
					'nama_izin'=>$d->nama_izin
				];
			}
		}
	}
	public function getListIzinCutiBawahanKar($jab = null, $val=null)
	{
		$this->db->select('a.*,
		b.nik as nik_karyawan,
		b.nama as nama_karyawan,
		c.nama as nama_jenis_izin,
		c.jenis as jenis,
		b.jabatan as jabatan,');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_izin AS c', 'c.kode_master_izin = a.jenis', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = b.jabatan', 'left');
		if ($jab != null){
			$this->db->where('d.kode_jabatan', $jab);
			$this->db->where('a.atasan_del', 1);
			$this->db->order_by('a.create_date','DESC');
		}
		if ($val != null){
			$this->db->where('a.validasi',2);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListIzinCutiBawahan($jabatan,$val)
	{
		if(empty($jabatan))
			return null;
		// $data =  $this->getListIzinCutiBawahanKar();
		// foreach ($data as $d) {
		// 	$dataz = [];
	    //   	if (in_array($d->jabatan,$jabatan)) {
		// 		foreach ($jabatan as $jab){
		// 			$datax =  $this->getListIzinCutiBawahanKar($jab);
		// 			array_push($dataz, $datax);
		// 		}
		// 	}
		// }
		$data =  $this->getListIzinCutiBawahanKar();
		$jabatan_x = [];
		foreach ($data as $d) {
			$jabatan_x[]= $d->jabatan;
		}
		$dataz = [];
		if (!in_array($jabatan_x, $jabatan)) {
			foreach ($jabatan as $jab_x => $jab_y){
				$datax =  $this->model_karyawan->getListIzinCutiBawahanKar($jab_y,$val);
				array_push($dataz, $datax);
			}
		}
		return $dataz;
	}
	public function getJumStatusValidasi($id_karyawan){
		$this->db->select('COUNT(*) as valid');
		$this->db->from('data_izin_cuti_karyawan');
		$this->db->where('validasi', 2);
		$this->db->where('id_karyawan', $id_karyawan);
		$query=$this->db->get()->row_array();
		return $query;
			// "SELECT COUNT(*) as valid FROM ijin_karyawan WHERE validasi=2 AND nik='$ij->nik'")->row_array();
	}
	public function getIzinCutiID($id){
		$this->db->select('*');
		$this->db->from('data_izin_cuti_karyawan');
		$this->db->where('id_izin_cuti', $id);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getIzinCutiTokenDate($token,$date){
		$this->db->select('*');
		$this->db->from('data_izin_cuti_karyawan');
		$this->db->where('token', $token);
		$this->db->where('date_now', $date);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getIzinCutiHarian()
	{
		$hari_ini=$this->CI->formatter->getDateFormatDbTimeToNoTime($this->date);
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		f.nama as nama_jenis_izin,
		f.jenis as jenis_ic,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		h.foto as foto,
		i.nama as nama_bagian,
		j.nama as nama_mengetahui,
		k.nama as jbt_mengetahui,
		l.nama as nama_menyetujui,
		m.nama as jbt_menyetujui,
		n.nama as nama_menyetujui_2,
		o.nama as jbt_menyetujui_2,
		mstr.jenis as izinOrCuti,
		doc.file as nama_file,');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_izin AS f', 'f.kode_master_izin = a.jenis', 'left');
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui_2', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = f.kode_dokumen', 'left');
		$this->db->join('master_izin AS mstr', 'mstr.kode_master_izin = a.jenis', 'left');
		$this->db->where('tgl_mulai',$hari_ini);
		$this->db->where('validasi',1);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getIzinCutiIDNew($id, $where=null)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		f.nama as nama_jenis_izin,
		f.jenis as jenis_ic,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		h.foto as foto,
		i.nama as nama_bagian,
		mstr.jenis as izinOrCuti,
		mstr.hitung_terlambat,
		doc.file as nama_file,');
		$this->db->from('data_izin_cuti_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_izin AS f', 'f.kode_master_izin = a.jenis', 'left');
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left');
		$this->db->join('master_dokumen AS doc', 'doc.kode_dokumen = f.kode_dokumen', 'left');
		$this->db->join('master_izin AS mstr', 'mstr.kode_master_izin = a.jenis', 'left');
		$this->db->where('a.id_izin_cuti', $id);
		if(!empty($where)){
			$this->db->where($where);
		}
		$query=$this->db->get()->row_array();
		return $query;
	}
//============================================== DATA LEMBUR ============================================================
//
	public function getListLembur($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = b.id_karyawan)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE a.nik = '".$where['nik']."'";
			}elseif($where['param']=='tab'){
				$next = "WHERE a.nik = 'xx'";
			}else{				
				if(!empty($where['tanggal'])){
					$tanggal_mulai = date('Y-m-d',strtotime($this->CI->formatter->getDateFromRange($where['tanggal'],'start')));
					$tanggal_selesai = date('Y-m-d',strtotime($this->CI->formatter->getDateFromRange($where['tanggal'],'end')));
				}
				$tgl_mulai = ($tanggal_mulai !=null) ? " b.tgl_mulai >= '".$tanggal_mulai."' " : '';	
				$tgl_selesai = ($tanggal_selesai !=null) ? " b.tgl_mulai <='".$tanggal_selesai."' " : '';
				$unit = ($where['unit']!=null) ? " d.kode_loker = '".$where['unit']."' " : '';
				$all_query = [$unit,$tgl_mulai,$tgl_selesai];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = b.id_karyawan)':'WHERE id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = b.id_karyawan)';
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT b.*,(select count(*) from data_lembur cnt where cnt.id_karyawan = b.id_karyawan) as jum,
		jbt.nama as nama_jabatan,
		d.nama as nama_loker,
		a.nama as nama_karyawan,
		a.nik as nik_karyawan,
		e.nama as nama_bagian,
		j.nama as nama_buat,
		k.nama as jbt_buat,
		l.nama as nama_periksa,
		m.nama as jbt_periksa,
		n.nama as nama_ketahui,
		o.nama as jbt_ketahui
		FROM karyawan a
		LEFT JOIN data_lembur b ON b.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = a.jabatan
		LEFT JOIN master_loker d ON d.kode_loker = a.loker
		LEFT JOIN master_bagian e ON e.kode_bagian = jbt.kode_bagian
		LEFT JOIN karyawan j ON j.id_karyawan = b.dibuat_oleh
		LEFT JOIN master_jabatan k ON k.kode_jabatan = j.jabatan
		LEFT JOIN karyawan l ON l.id_karyawan = b.diperiksa_oleh
		LEFT JOIN master_jabatan m ON m.kode_jabatan = l.jabatan
		LEFT JOIN karyawan n ON n.id_karyawan = b.diketahui_oleh
		LEFT JOIN master_jabatan o ON o.kode_jabatan = n.jabatan
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY b.create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getListLemburx($usage = 'nosearch',$where = null,$mode = 'data')
	{
		if($usage=='nosearch'){
			$next = 'WHERE id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = a.id_karyawan)';
		}else{
			if($where['param']=='all'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE b.nik = '".$where['nik']."'";
			}else{				
				if(!empty($where['tanggal'])){
					$tanggal_mulai = date('Y-m-d',strtotime($this->CI->formatter->getDateFromRange($where['tanggal'],'start')));
					$tanggal_selesai = date('Y-m-d',strtotime($this->CI->formatter->getDateFromRange($where['tanggal'],'end')));
				}
				$tgl_mulai = ($tanggal_mulai !=null) ? " a.tgl_mulai >= '".$tanggal_mulai."' " : '';	
				$tgl_selesai = ($tanggal_selesai !=null) ? " a.tgl_mulai <='".$tanggal_selesai."' " : '';
				$unit = ($where['unit']!=null) ? " d.kode_loker = '".$where['unit']."' " : '';
				$all_query = [$unit,$tgl_mulai,$tgl_selesai];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = a.id_karyawan)':'WHERE id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = a.id_karyawan)';
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_lembur cnt where cnt.id_karyawan = a.id_karyawan) as jum,
		jbt.nama as nama_jabatan,
		d.nama as nama_loker,
		b.nama as nama_karyawan,
		b.nik as nik_karyawan,
		e.nama as nama_bagian,
		j.nama as nama_buat,
		k.nama as jbt_buat,
		l.nama as nama_periksa,
		m.nama as jbt_periksa,
		n.nama as nama_ketahui,
		o.nama as jbt_ketahui
		FROM data_lembur a
		LEFT JOIN karyawan b ON b.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = b.jabatan
		LEFT JOIN master_loker d ON d.kode_loker = b.loker
		LEFT JOIN master_bagian e ON e.kode_bagian = jbt.kode_bagian
		LEFT JOIN karyawan j ON j.id_karyawan = a.dibuat_oleh
		LEFT JOIN master_jabatan k ON k.kode_jabatan = j.jabatan
		LEFT JOIN karyawan l ON l.id_karyawan = a.diperiksa_oleh
		LEFT JOIN master_jabatan m ON m.kode_jabatan = l.jabatan
		LEFT JOIN karyawan n ON n.id_karyawan = a.diketahui_oleh
		LEFT JOIN master_jabatan o ON o.kode_jabatan = n.jabatan
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getLembur($id = null, $where = null, $row = null)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		h.status_emp as status_emp,
		h.foto as foto,
		i.nama as nama_bagian,
		j.nama as nama_buat,
		k.nama as jbt_buat,
		l.nama as nama_periksa,
		m.nama as jbt_periksa,
		n.nama as nama_ketahui,
		o.nama as jbt_ketahui');
		$this->db->from('data_lembur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.dibuat_oleh', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.diperiksa_oleh', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.diketahui_oleh', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');

		if(isset($id)){ $this->db->where('id_data_lembur',$id);  }
		if(isset($where)){ $this->db->where($where); }
		if($row == null){
			$query=$this->db->get()->result();
		}else{
			$query=$this->db->get()->row_array();
		}
		return $query;
	}
	public function getListLemburNik($nik)
	{
		$where=['b.nik'=>$nik];
		$this->db->select('a.*,
		b.nik as nik_karyawan,
		b.nama as nama_karyawan,
		c.nama as nama_buat,
		d.nama as jbt_buat,
		e.nama as nama_periksa,
		f.nama as jbt_periksa,
		g.nama as nama_ketahui,
		h.nama as jbt_ketahui');
		$this->db->from('data_lembur AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS c', 'c.id_karyawan = a.dibuat_oleh', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = c.jabatan', 'left');
		$this->db->join('karyawan AS e', 'e.id_karyawan = a.diperiksa_oleh', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = e.jabatan', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.diketahui_oleh', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		$this->db->where($where); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkLemburCode($code)
	{
		return $this->model_global->checkCode($code,'data_lembur','no_lembur');
	}
	public function getEmployeeKodeBagian($kode)
	{
		$filter=$this->model_global->getFilterbyBagian($kode);
		$this->db->select('emp.*,loker.nama as nama_loker,jbt.nama as nama_jabatan,bag.nama as bagian,b.nama as nama_buat, c.nama as nama_update, grd.nama as nama_grade, d.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		// $this->db->where('bag.kode_bagian',$kode);
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function cekDataLemburIdDate($idkar,$tanggal)
	{
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('id_karyawan', $idkar);
		$this->db->where('tgl_mulai', $tanggal);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataLemburAll($where, $bagian, $tanggal)
	{
		$filter=$this->model_global->getFilterbyBagian($bagian);
		$mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'start')));
		$selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'end')));
		$this->db->select('a.*,
		b.nik as nik_karyawan,
		b.nama as nama_karyawan,
		jbt.nama as nama_jabatan,
		lok.nama as nama_loker,
		c.nama as nama_buat,
		d.nama as jbt_buat,
		e.nama as nama_periksa,
		f.nama as jbt_periksa,
		g.nama as nama_ketahui,
		h.nama as jbt_ketahui');
		$this->db->from('data_lembur AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS c', 'c.id_karyawan = a.dibuat_oleh', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = c.jabatan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = b.jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = b.loker', 'left');
		$this->db->join('karyawan AS e', 'e.id_karyawan = a.diperiksa_oleh', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = e.jabatan', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.diketahui_oleh', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		if(!empty($tanggal)){
			$this->db->where("a.tgl_mulai BETWEEN '".$mulai."' AND '".$selesai."'");
 		}
		$this->db->where($where);
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('b.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$this->db->order_by('a.tgl_mulai','ASD');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataLemburAllWhere($where=null, $mulai=null, $selesai=null)
	{
		$this->db->select('a.*,
		b.nik as nik_karyawan,
		b.nama as nama_karyawan,
		jab.nama as nama_jabatan,
		lok.nama as nama_loker,
		c.nama as nama_buat,
		d.nama as jbt_buat,
		e.nama as nama_periksa,
		f.nama as jbt_periksa,
		g.nama as nama_ketahui,
		h.nama as jbt_ketahui');
		$this->db->from('data_lembur AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS c', 'c.id_karyawan = a.dibuat_oleh', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = c.jabatan', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = b.jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = b.loker', 'left');
		$this->db->join('karyawan AS e', 'e.id_karyawan = a.diperiksa_oleh', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = e.jabatan', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.diketahui_oleh', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		if(!empty($mulai) && !empty($selesai)){
			$this->db->where("a.tgl_mulai BETWEEN '".$mulai."' AND '".$selesai."'");
 		}
		if(!empty($where)){
			$this->db->where($where);
		}
		$sq=null;
		$this->db->where('b.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$this->db->order_by('a.tgl_mulai','ASD');
		$query=$this->db->get()->result();
		return $query;
	}
	//============================================= DATA LEMBUR TRANSAKSI ==================================================
//
	public function getListLemburTrans($usage = 'nosearch',$where = null,$mode = 'data')
	{
		$bulan = $this->CI->otherfunctions->getDataExplode($this->date,'-','end');
		$tahun = $this->CI->otherfunctions->getDataExplode($this->date,'-','start');
		if($usage=='nosearch'){
			// $next = 'WHERE id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = a.id_karyawan)';
			$next = 'WHERE month(a.tgl_mulai)="'.$bulan.'" AND year(a.tgl_mulai)="'.$tahun.'"';
			// $next = '';
		}else{
			if($where['param']=='all'){
				// $next = '';
				$next = 'WHERE month(a.tgl_mulai)="'.$bulan.'" AND year(a.tgl_mulai)="'.$tahun.'"';
			}elseif($where['param']=='tab'){
				$next = '';
			}elseif($where['param']=='nik'){
				$next = "WHERE b.nik = '".$where['nik']."'";
			}else{
				if(!empty($where['tanggal'])){
					$tanggal_mulai = date('Y-m-d',strtotime($this->CI->formatter->getDateFromRange($where['tanggal'],'start')));
					$tanggal_selesai = date('Y-m-d',strtotime($this->CI->formatter->getDateFromRange($where['tanggal'],'end')));
				}
				$unit = ($where['unit']!=null) ? " d.kode_loker = '".$where['unit']."' " : '';
				$s_val = ($where['status_validasi']!=null) ? " a.validasi = '".$where['status_validasi']."' " : '';
				$p_jam = ($where['potong_jam']!=null) ? " a.status_potong = '".$where['potong_jam']."' " : '';
				// $tgl_mulai = ($tanggal_mulai !=null) ? " a.tgl_mulai >= '".$tanggal_mulai."' " : '';	
				// $tgl_selesai = ($tanggal_selesai !=null) ? " a.tgl_mulai <='".$tanggal_selesai."' " : '';
				// $all_query = [$unit,$tgl_mulai,$tgl_selesai];
				$tanggal = ($tanggal_mulai !=null) ? " a.tgl_mulai BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'" : '';	
				$all_query = [$unit,$tanggal,$s_val,$p_jam];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = a.id_karyawan)':'WHERE id_data_lembur = (SELECT max(id_data_lembur) FROM data_lembur x WHERE x.id_karyawan = a.id_karyawan)';
				}elseif($mode == 'cari'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery:null;
				}
			}
		}
		$sc="SELECT a.*,(select count(*) from data_lembur cnt where cnt.id_karyawan = a.id_karyawan) as jum,
		(select count(*) from data_lembur jmlh where jmlh.no_lembur = a.no_lembur) as jum_no,
		jbt.nama as nama_jabatan,
		d.nama as nama_loker,
		b.nama as nama_karyawan,
		b.nik as nik_karyawan,
		e.nama as nama_bagian,
		j.nama as nama_buat,
		k.nama as jbt_buat,
		l.nama as nama_periksa,
		m.nama as jbt_periksa,
		n.nama as nama_ketahui,
		o.nama as jbt_ketahui
		FROM data_lembur a
		LEFT JOIN karyawan b ON b.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = b.jabatan
		LEFT JOIN master_loker d ON d.kode_loker = b.loker
		LEFT JOIN master_bagian e ON e.kode_bagian = jbt.kode_bagian
		LEFT JOIN karyawan j ON j.id_karyawan = a.dibuat_oleh
		LEFT JOIN master_jabatan k ON k.kode_jabatan = j.jabatan
		LEFT JOIN karyawan l ON l.id_karyawan = a.diperiksa_oleh
		LEFT JOIN master_jabatan m ON m.kode_jabatan = l.jabatan
		LEFT JOIN karyawan n ON n.id_karyawan = a.diketahui_oleh
		LEFT JOIN master_jabatan o ON o.kode_jabatan = n.jabatan
		$next";
		$filter=$this->model_global->getFilterbyBagian($where['bagian']);	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" GROUP BY no_lembur ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getLemburTrans($no_lembur,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,
		d.nama as nama_jabatan,
		e.nama as nama_loker,
		h.nama as nama_karyawan,
		h.nik as nik_karyawan,
		h.status_emp as status_emp,
		h.foto as foto,
		i.nama as nama_bagian,
		j.nama as nama_buat_trans,
		k.nama as jbt_buat,
		l.nama as nama_periksa,
		m.nama as jbt_periksa,
		n.nama as nama_ketahui,
		o.nama as jbt_ketahui,
		pre.jam_mulai as jam_mulai_pre,pre.jam_selesai as jam_selesai_pre, pre.tanggal as tanggal_pre');
		$this->db->from('data_lembur AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS h', 'h.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = h.jabatan', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = h.loker', 'left');
		$this->db->join('master_bagian AS i', 'i.kode_bagian = d.kode_bagian', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.dibuat_oleh', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS l', 'l.id_karyawan = a.diperiksa_oleh', 'left');
		$this->db->join('master_jabatan AS m', 'm.kode_jabatan = l.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.diketahui_oleh', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		$this->db->join('data_presensi AS pre', 'pre.id_karyawan = a.id_karyawan and pre.tanggal = a.tgl_mulai', 'left');
		$this->db->where('no_lembur',$no_lembur); 
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListLemburTransNik($nik)
	{
		$where=['b.nik'=>$nik];
		$this->db->select('a.*,
		b.nik as nik_karyawan,
		b.nama as nama_karyawan,
		c.nama as nama_buat,
		d.nama as jbt_buat,
		e.nama as nama_periksa,
		f.nama as jbt_periksa,
		g.nama as nama_ketahui,
		h.nama as jbt_ketahui');
		$this->db->from('data_lembur AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS c', 'c.id_karyawan = a.dibuat_oleh', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = c.jabatan', 'left');
		$this->db->join('karyawan AS e', 'e.id_karyawan = a.diperiksa_oleh', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = e.jabatan', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.diketahui_oleh', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		$this->db->where($where); 
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkLemburTransCode($code)
	{
		return $this->model_global->checkCode($code,'data_lembur','no_lembur');
	}
	public function getLemburTokenDate($token,$date){
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('token', $token);
		$this->db->where('date_now', $date);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getLemburIDLembur($id){
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('id_data_lembur', $id);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getLemburNoLembur($no_lembur){
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('no_lembur', $no_lembur);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListLemburTransWhere($where)
	{
		$this->db->select('a.*,
		b.nik as nik_karyawan,
		b.nama as nama_karyawan,
		c.nama as nama_buat,
		d.nama as jbt_buat,
		e.nama as nama_periksa,
		f.nama as jbt_periksa,
		g.nama as nama_ketahui,
		h.nama as jbt_ketahui');
		$this->db->from('data_lembur AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('karyawan AS c', 'c.id_karyawan = a.dibuat_oleh', 'left');
		$this->db->join('master_jabatan AS d', 'd.kode_jabatan = c.jabatan', 'left');
		$this->db->join('karyawan AS e', 'e.id_karyawan = a.diperiksa_oleh', 'left');
		$this->db->join('master_jabatan AS f', 'f.kode_jabatan = e.jabatan', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.diketahui_oleh', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		$this->db->where($where); 
		$this->db->order_by('update_date','DESC');
		$this->db->group_by('no_lembur');
		$query=$this->db->get()->result();
		return $query;
	}
//========================================= DATA PERJALANAN DINAS =========================================================
//
	public function getListDataPerjalananDinas($usage = 'nosearch',$where = null,$mode = 'data')
	{
		$filter=$this->model_global->getFilterbyBagian((($where['bagian'])?$where['bagian']:null));
		$this->db->select('a.*,
		count(*) as jum,
		emp.nama as nama_karyawan,
		emp.nik as nik_karyawan,
		jbt.nama as nama_jabatan,
		bag.nama as nama_bagian,
		loker.nama as nama_loker,
		e.nama as nama_plant_tujuan,
		g.nama as nama_mengetahui,
		h.nama as jbt_mengetahui,
		i.nama as nama_menyetujui,
		j.nama as jbt_menyetujui,
		k.nama as nama_dibuat,
		m.nama as nama_kendaraan_j,
		n.nama as nama_plant_asal,
		o.nama as nama_val_kendaraan
		');
		$this->db->from('data_perjalanan_dinas AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_loker AS loker', 'loker.kode_loker = emp.loker', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jbt.kode_bagian', 'left');
		$this->db->join('master_loker AS e', 'e.kode_loker = a.plant_tujuan', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		$this->db->join('karyawan AS i', 'i.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS j', 'j.kode_jabatan = i.jabatan', 'left');
		$this->db->join('karyawan AS k', 'k.id_karyawan = a.dibuat', 'left');
		$this->db->join('master_jabatan AS l', 'l.kode_jabatan = k.jabatan', 'left');
		$this->db->join('master_pd_kendaraan AS m', 'm.kode = a.kendaraan', 'left');
		$this->db->join('master_loker AS n', 'n.kode_loker = a.plant_asal', 'left');
		$this->db->join('master_pd_kendaraan AS o', 'o.kode = a.val_kendaraan', 'left');
		if(!empty($filter)){
			$this->db->where($filter);
		}
		if(!empty($where['tanggal'])){
			$tanggal_mulai = $this->CI->formatter->getDateFromRange($where['tanggal'],'start');
			$tanggal_selesai = $this->CI->formatter->getDateFromRange($where['tanggal'],'end');
			$this->db->where("a.tgl_berangkat BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'");
		}
		if(isset($where['unit'])){
			if ($where['unit']){
				$this->db->where('emp.loker',$where['unit']);
			}
		}
		$query=$this->db->get()->result();
		return $query;


		// if($usage=='nosearch'){
		// 	$next = 'WHERE id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.id_karyawan = a.id_karyawan)';
		// 	// $next = '';
		// }else{
		// 	if($where['param']=='all'){
		// 		$next = '';
		// 	}elseif($where['param']=='nik'){
		// 		$next = "WHERE b.nik = '".$where['nik']."'";
		// 	}else{
		// 		if(!empty($where['tanggal'])){
		// 			$tanggal_mulai = $this->CI->formatter->getDateFromRange($where['tanggal'],'start');
		// 			$tanggal_selesai = $this->CI->formatter->getDateFromRange($where['tanggal'],'end');
		// 		}
		// 		$bagian = ($where['bagian']!=null) ? " d.kode_bagian = '".$where['bagian']."' " : '';
		// 		$unit = ($where['unit']!=null) ? " e.kode_loker = '".$where['unit']."' " : '';
		// 		$tgl_mulai = ($tanggal_mulai !=null) ? " a.tgl_berangkat >= '".$tanggal_mulai."' " : '';	
		// 		$tgl_selesai = ($tanggal_selesai !=null) ? " a.tgl_berangkat <= '".$tanggal_selesai."' " : '';
		// 		$all_query = [$bagian,$unit,$tgl_mulai,$tgl_selesai];
		// 		$nquery = array_filter($all_query);
		// 		$nquery = implode(' AND ',$nquery);
		// 		if($mode == 'cari'){
		// 			$next = (!empty($nquery))?'WHERE '.$nquery:'WHERE id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.id_karyawan = a.id_karyawan)';
		// 		}elseif($mode == 'data'){
		// 			$next = (!empty($nquery))?'WHERE '.$nquery.' AND id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.id_karyawan = a.id_karyawan)':'WHERE id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.id_karyawan = a.id_karyawan)';
		// 		}elseif($mode == 'rekap'){
		// 			$next = (!empty($nquery))?'WHERE '.$nquery:null;
		// 		}
		// 	}
		// }
		// if ($filter){
		// 	if ($next){
		// 		$next.=' AND '.$filter;
		// 	}else{
		// 		$next.=' WHERE '.$filter;
		// 	}
		// }
		// $sc="SELECT 
		// FROM data_perjalanan_dinas a
		// LEFT JOIN karyawan b ON b.id_karyawan = a.id_karyawan
		// LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = b.jabatan
		// LEFT JOIN master_bagian bag ON bag.kode_bagian = jbt.kode_bagian
		// LEFT JOIN master_loker d ON d.kode_loker = b.loker
		// LEFT JOIN master_loker e ON e.kode_loker = a.plant_tujuan
		// LEFT JOIN karyawan h ON h.id_karyawan = a.mengetahui
		// LEFT JOIN master_jabatan i ON i.kode_jabatan = h.jabatan
		// LEFT JOIN karyawan j ON j.id_karyawan = a.menyetujui
		// LEFT JOIN master_jabatan k ON k.kode_jabatan = j.jabatan
		// LEFT JOIN admin l ON l.id_karyawan = a.dibuat
		// LEFT JOIN master_pd_kendaraan m ON m.kode = a.kendaraan
		// LEFT JOIN master_loker o ON o.kode_loker = a.plant_asal
		// LEFT JOIN master_pd_kendaraan p ON p.kode = a.val_kendaraan
		// $next  ORDER BY update_date DESC";
		// $query=$this->db->query($sc)->result();
		// return $query;
	}
	public function getPerjalananDinasID($id=null, $where=null, $row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,d.nama as nama_karyawan,d.nik as nik_karyawan,
		e.nama as nama_jabatan,
		f.nama as nama_loker,
		g.nama as nama_mengetahui,
		h.nama as jbt_mengetahui,
		i.nama as nama_menyetujui,
		j.nama as jbt_menyetujui,
		k.nama as nama_dibuat,
		l.nama as jbt_dibuat,
		m.nama as nama_kendaraan_j,
		n.nama as nama_plant_tujuan,
		o.nama as nama_plant_asal,
		');
		$this->db->from('data_perjalanan_dinas AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS e', 'e.kode_jabatan = d.jabatan', 'left');
		$this->db->join('master_loker AS f', 'f.kode_loker = d.loker', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		$this->db->join('karyawan AS i', 'i.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS j', 'j.kode_jabatan = i.jabatan', 'left');
		$this->db->join('karyawan AS k', 'k.id_karyawan = a.dibuat', 'left');
		$this->db->join('master_jabatan AS l', 'l.kode_jabatan = k.jabatan', 'left');
		$this->db->join('master_pd_kendaraan AS m', 'm.kode = a.kendaraan', 'left');
		$this->db->join('master_loker AS n', 'n.kode_loker = a.plant_tujuan', 'left');
		$this->db->join('master_loker AS o', 'o.kode_loker = a.plant_asal', 'left');
		if(!empty($id)){
			$this->db->where('id_pd',$id);
		}
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
	public function getPerjalananDinasPerTransaksi($admin='tidak ada', $usage = 'nosearch',$where = null,$mode = 'data')
	{
		$filter=$this->model_global->getFilterbyBagian((($where['bagian'])?$where['bagian']:null));
		if($admin != 'tidak ada'){
			$w_adm = " AND a.dibuat = $admin";
		}else{
			$w_adm = '';
		}
		if($usage=='nosearch'){
			// $next = 'WHERE id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.id_karyawan = a.id_karyawan)'.$w_adm;
			$next = '';
		}else{
			if($where['param']=='all'){
				$next = 'WHERE id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.id_karyawan = a.id_karyawan)'.$w_adm;
			}elseif($where['param']=='nik'){
				$next = "WHERE f.nik = '".$where['nik']."'";
			}else{
				if(!empty($where['tanggal'])){
					$tanggal_mulai = $this->CI->formatter->getDateFromRange($where['tanggal'],'start');
					$tanggal_selesai = $this->CI->formatter->getDateFromRange($where['tanggal'],'end');
				}
				$bagian = ($where['bagian']!=null) ? " h.kode_bagian = '".$where['bagian']."' " : '';
				$unit = ($where['unit']!=null) ? " f.kode_loker = '".$where['unit']."' " : '';
				$statusK = ($where['status']=='belum') ? " kor.no_sk IS NULL" : " kor.no_sk IS NOT NULL";
				$statusKoreksi = (empty($where['status']) ? "" : $statusK );
				$tgl_mulai = ($tanggal_mulai !=null) ? " a.tgl_berangkat >= '".$tanggal_mulai."' " : '';	
				$tgl_selesai = ($tanggal_selesai !=null) ? " a.tgl_berangkat <= '".$tanggal_selesai."' " : '';
				$all_query = [$bagian, $unit, $tgl_mulai, $tgl_selesai, $statusKoreksi];
				$nquery = array_filter($all_query);
				$nquery = implode(' AND ',$nquery);
				if($mode == 'cari'){
					$next = (!empty($nquery))?'WHERE '.$nquery:'WHERE id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.no_sk = a.no_sk)'.$w_adm;
				}elseif($mode == 'data'){
					$next = (!empty($nquery))?'WHERE '.$nquery.' AND id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.no_sk = a.no_sk)'.$w_adm:'WHERE id_pd = (SELECT max(id_pd) FROM data_perjalanan_dinas x WHERE x.no_sk = a.no_sk)'.$w_adm;
				}elseif($mode == 'rekap'){
					$next = (!empty($nquery))?'WHERE '.$nquery.''.$w_adm:null;
				}
			}
		}
		if ($filter){
			if ($next){
				$next.=' AND '.$filter;
			}else{
				$next.=' WHERE '.$filter;
			}
		}
		$sc="SELECT a.*,(select count(*) from data_perjalanan_dinas cnt where cnt.no_sk = a.no_sk) as jum,
		b.nama as nama_buat,
		c.nama as nama_update,
		d.nama as nama_karyawan,
		d.nik as nik_karyawan,
		jbt.nama as nama_jabatan,
		f.nama as nama_loker,
		g.nama as nama_mengetahui,
		h.nama as jbt_mengetahui,
		i.nama as nama_menyetujui,
		j.nama as jbt_menyetujui,
		k.nama as nama_dibuat,
		m.nama as nama_kendaraan_j,
		n.nama as nama_plant_tujuan,
		o.nama as nama_plant_asal,
		p.nama as nama_val_kendaraan,
		kor.no_sk as no_sk_koreksi
		FROM data_perjalanan_dinas a
		LEFT JOIN admin b ON b.id_admin = a.create_by 
		LEFT JOIN admin c ON c.id_admin = a.update_by
		LEFT JOIN karyawan d ON d.id_karyawan = a.id_karyawan
		LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = d.jabatan
		LEFT JOIN master_loker f ON f.kode_loker = d.loker
		LEFT JOIN karyawan g ON g.id_karyawan = a.mengetahui
		LEFT JOIN master_jabatan h ON h.kode_jabatan = g.jabatan
		LEFT JOIN karyawan i ON i.id_karyawan = a.menyetujui
		LEFT JOIN master_jabatan j ON j.kode_jabatan = i.jabatan
		LEFT JOIN admin k ON k.id_karyawan = a.dibuat
		LEFT JOIN master_pd_kendaraan m ON m.kode = a.kendaraan
		LEFT JOIN master_loker n ON n.kode_loker = a.plant_tujuan
		LEFT JOIN master_loker o ON o.kode_loker = a.plant_asal
		LEFT JOIN master_pd_kendaraan p ON p.kode = a.val_kendaraan
		LEFT JOIN data_perjalanan_dinas_koreksi kor ON kor.no_sk = a.no_sk
		$next GROUP BY no_sk ORDER BY update_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getPerjalananDinasKodeSK($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,d.nama as nama_karyawan,d.nik as nik_karyawan,
		e.nama as nama_jabatan,
		bag.nama as nama_bagian,
		f.nama as nama_loker,
		g.nama as nama_mengetahui,
		h.nama as jbt_mengetahui,
		i.nama as nama_menyetujui,
		j.nama as jbt_menyetujui,
		k.nama as nama_dibuat,
		m.nama as nama_kendaraan_j,
		n.nama as nama_plant_tujuan,
		o.nama as nama_plant_asal,
		p.nama as val_nama_kendaraan_j,
		');
		$this->db->from('data_perjalanan_dinas AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS e', 'e.kode_jabatan = d.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = e.kode_bagian', 'left');
		$this->db->join('master_loker AS f', 'f.kode_loker = d.loker', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		$this->db->join('karyawan AS i', 'i.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS j', 'j.kode_jabatan = i.jabatan', 'left');
		$this->db->join('admin AS k', 'k.id_karyawan = a.dibuat', 'left');
		// $this->db->join('master_jabatan AS l', 'l.kode_jabatan = k.jabatan', 'left');
		$this->db->join('master_pd_kendaraan AS m', 'm.kode = a.kendaraan', 'left');
		$this->db->join('master_loker AS n', 'n.kode_loker = a.plant_tujuan', 'left');
		$this->db->join('master_loker AS o', 'o.kode_loker = a.plant_asal', 'left');
		$this->db->join('master_pd_kendaraan AS p', 'p.kode = a.val_kendaraan', 'left');
		$this->db->where('no_sk',$kode); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPerjalananDinasKodeSKGroup($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,d.nama as nama_karyawan,d.nik as nik_karyawan,
		e.nama as nama_jabatan,
		bag.nama as nama_bagian,
		f.nama as nama_loker,
		g.nama as nama_mengetahui,
		h.nama as jbt_mengetahui,
		i.nama as nama_menyetujui,
		j.nama as jbt_menyetujui,
		k.nama as nama_dibuat,
		m.nama as nama_kendaraan_j,
		n.nama as nama_plant_tujuan,
		o.nama as nama_plant_asal,
		p.nama as val_nama_kendaraan_j,
		');
		$this->db->from('data_perjalanan_dinas AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS e', 'e.kode_jabatan = d.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = e.kode_bagian', 'left');
		$this->db->join('master_loker AS f', 'f.kode_loker = d.loker', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		$this->db->join('karyawan AS i', 'i.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS j', 'j.kode_jabatan = i.jabatan', 'left');
		$this->db->join('admin AS k', 'k.id_karyawan = a.dibuat', 'left');
		// $this->db->join('master_jabatan AS l', 'l.kode_jabatan = k.jabatan', 'left');
		$this->db->join('master_pd_kendaraan AS m', 'm.kode = a.kendaraan', 'left');
		$this->db->join('master_loker AS n', 'n.kode_loker = a.plant_tujuan', 'left');
		$this->db->join('master_loker AS o', 'o.kode_loker = a.plant_asal', 'left');
		$this->db->join('master_pd_kendaraan AS p', 'p.kode = a.val_kendaraan', 'left');
		$this->db->where('no_sk',$kode); 
		$this->db->group_by('a.no_sk'); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getEmpNoSKTransaksi($kode){
		$where=['no_sk'=>$kode];
		$this->db->select('*');
		$this->db->from('data_perjalanan_dinas');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKoreksiPerdin($where, $row = false){
		$this->db->select('*');
		$this->db->from('data_perjalanan_dinas_koreksi');
		$this->db->where($where);
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getKoreksiPerdin2($where, $row = false)
	{
		$this->db->select('a.*,b.nama as nama_buat,c.nama as nama_update,d.nama as nama_karyawan,d.nik as nik_karyawan,
		e.nama as nama_jabatan,
		bag.nama as nama_bagian,
		f.nama as nama_loker,
		g.nama as nama_mengetahui,
		h.nama as jbt_mengetahui,
		i.nama as nama_menyetujui,
		j.nama as jbt_menyetujui,
		k.nama as nama_dibuat,
		m.nama as nama_kendaraan_j,
		n.nama as nama_plant_tujuan,
		o.nama as nama_plant_asal,
		p.nama as val_nama_kendaraan_j,
		');
		$this->db->from('data_perjalanan_dinas_koreksi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS e', 'e.kode_jabatan = d.jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = e.kode_bagian', 'left');
		$this->db->join('master_loker AS f', 'f.kode_loker = d.loker', 'left');
		$this->db->join('karyawan AS g', 'g.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS h', 'h.kode_jabatan = g.jabatan', 'left');
		$this->db->join('karyawan AS i', 'i.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS j', 'j.kode_jabatan = i.jabatan', 'left');
		$this->db->join('admin AS k', 'k.id_karyawan = a.dibuat', 'left');
		$this->db->join('master_pd_kendaraan AS m', 'm.kode = a.kendaraan', 'left');
		$this->db->join('master_loker AS n', 'n.kode_loker = a.plant_tujuan', 'left');
		$this->db->join('master_loker AS o', 'o.kode_loker = a.plant_asal', 'left');
		$this->db->join('master_pd_kendaraan AS p', 'p.kode = a.val_kendaraan', 'left');
		$this->db->where($where);
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListPerjalananDinasNik($nik, $fo=null)
	{
		$where=['d.nik'=>$nik];
		$this->db->select('a.*,b.nama as nama_kendaraan_j,c.nama as nama_plant_tujuan,d.nama as nama_karyawan');
		$this->db->from('data_perjalanan_dinas AS a');
		$this->db->join('master_pd_kendaraan AS b', 'b.kode = a.kendaraan', 'left');
		$this->db->join('master_loker AS c', 'c.kode_loker = a.plant_tujuan', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left');
		$this->db->where($where); 
		if($fo=='fo'){
			$this->db->where('a.status',1);
		}
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkPDCode($code)
	{
		return $this->model_global->checkCode($code,'data_perjalanan_dinas','no_sk');
	}
	public function pilihTunjanganSelect2()
	{
		$data=$this->model_master->getListKategoriDinas('KAPD0001');
		$pack=[];
		foreach ($data as $d) {
			$pack[$d->kode]=$d->nama;
		}
		return $pack;
	}
	public function refreshTunjangan()
	{
		$pack=$this->pilihTunjanganSelect2();
		if (isset($pack[null])) {
			unset($pack[null]);
		}
		return $pack;
	}
	public function getTunjanganGradeKetegori($grade,$kode_kategori)
	{
		$where=['grade'=>$grade,'kode_kategori'=>$kode_kategori];
		$this->db->select('*');
		$this->db->from('master_pd_detail_kategori');
		$this->db->where($where);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getTunjanganGradePenginapan($grade,$penginapan=null)
	{
		if(empty($penginapan))
			return null;
		$this->db->select('*');
		$this->db->from('master_pd_detail_kategori');
		if(isset($grade) || !empty($grade)){
			$this->db->where('grade', $grade);
		}
		if(isset($penginapan) || !empty($penginapan)){
			$this->db->where('tempat', $penginapan);
		}
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getTunjanganBBM($kode_kendaraan,$jenis=null,$jarak=null)
	{
		if(empty($kode_kendaraan) || empty($jarak))
			return null;
		$this->db->select('*');
		$this->db->from('master_pd_bbm');
		$this->db->where('kode_kendaraan', $kode_kendaraan);
		if(isset($jenis) && !empty($jenis)){
			$this->db->where('j_k_umum', $jenis);
		}
		if(!empty($jarak)){
			$this->db->where('jarak_awal <='.$jarak.' AND jarak_akhir >='. $jarak);
		}
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getPerdinTokenDate($token,$date){
		$this->db->select('*');
		$this->db->from('data_perjalanan_dinas');
		$this->db->where('token', $token);
		$this->db->where('date_now', $date);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getPerdinNoSK($no_sk){
		$this->db->select('*');
		$this->db->from('data_perjalanan_dinas');
		$this->db->where('no_sk', $no_sk);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getPerdinWhere($where){
		$this->db->select('*');
		$this->db->from('data_perjalanan_dinas');
		$this->db->where($where);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getKodeAkunForSelect2()
	{
		$data=$this->model_master->getKodeAkunAktif();
		$pack=[];
		foreach ($data as $d) {
			(isset($edit)?$pack[null]='Pilih Data':null);
			$pack[$d->kode_akun]=$d->kode_akun.' ('.$d->nama.')';
		}
		return $pack;
	}
	public function getKodeAkunNoSK($kode)
	{
		$this->db->select('a.*,mka.nama as nama_akun');
		$this->db->from('data_pd_kode_akun AS a');
		$this->db->join('master_pd_kode_akun AS mka','mka.kode_akun = a.kode_akun', 'left');
		$this->db->where('kode_perjalanan_dinas',$kode);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKodeAkun($id)
	{
		$this->db->select('a.*,b.nama as nama_akun');
		$this->db->from('data_pd_kode_akun as a');
		$this->db->join('master_pd_kode_akun AS b', 'b.kode_akun = a.kode_akun', 'left');
		$this->db->where('id',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
//=============================================== DATA JADWAL KERJA ====================================================
//
	public function getListJdwalKerja($data,$id=null,$group=null,$order=null)
	{
		if($data['usage']=='search' || $data['usage']=='all'){
			$bagian = (!empty($data['bagian']))?$data['bagian']:false;
			$filter=$this->model_global->getFilterbyBagian($bagian);
		}
		$this->db->select('jk.*,emp.nama as nama_karyawan, emp.nik as nik, emp.id_karyawan as id_karyawan, emp.id_finger as id_finger, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker');
		$this->db->from('data_jadwal_kerja AS jk');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = jk.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left');
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left');
		if($data['usage']=='search'){
			$bulan = null;
			if(isset($data['bulan']) || !empty($data['bulan'])){
				$bulan=($data['bulan'] < 10) ? str_replace('0', '', $data['bulan']) : $data['bulan'];
			}
			$tahun = null;
			if(isset($data['tahun']) || !empty($data['tahun'])){
				$tahun=$data['tahun'];
			}
			$bulanx=(!empty($bulan)) ? ["jk.bulan" => $bulan] : [];
			$tahunx=(!empty($tahun)) ? ["jk.tahun" => $tahun] : ["jk.tahun" => date('Y')];	
			$lokasix=(!empty($data['lokasi'])) ? ["lkr.kode_loker" => $data['lokasi']] : [];
			$nquery = array_merge($lokasix,$bulanx,$tahunx);
			$nexts = (!empty($nquery))? $this->db->where($nquery) : null;
		}elseif($data['usage']=='all'){
			// $month = date('m',strtotime($this->date));
			// $year = date('Y',strtotime($this->date));
			// $bulan=($month < 10)?str_replace('0', '', $month):$month;
			// $this->db->where('tahun',$year);
			// $this->db->where('bulan',$bulan);
			$this->db->where('bulan',13);
		}elseif($data['usage']=='id'){
			$this->db->where('jk.id_karyawan',$id);
		}
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		if($order == null){
			$this->db->order_by('jk.create_date','DESC');
		}else{
			$this->db->order_by($order['kolom'],$order['value']);
		}
		if(!empty($group)){
			$this->db->group_by($group);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListJadwalKerjaWhere($where)
	{
		$this->db->select('*');
		$this->db->from('data_jadwal_kerja');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getBagianBawahan($kode_bagian)
	{
		$datx=$this->model_master->getJabatanBagian($kode_bagian);
		// print_r($datx);
		$dat=$this->model_master->getJabatanAll();
		$arr=[];
		foreach ($datx as $dt){
			$arr[]=$this->model_master->getDataBawahan($dat, $dt->kode_jabatan);
		}
		$c_jbt=[];
		foreach($arr as $r){
			if (strpos($r, ';') !== false) {
				$c_jbt[]=$this->otherfunctions->getDataExplode($r,';','all');
			}
		}
		// return $c_jbt;
		foreach($c_jbt as $jbt){
			foreach($jbt as $j){
				return $j;
			}
		}
	}
	public function getBagianBawahanNotif($kode_bagian)
	{
		if (empty($kode_bagian))
			return null;
		$datx=$this->model_master->getJabatanBagian($kode_bagian);
		$dat=$this->model_master->getJabatanAll();
		$arr=[];
		foreach ($datx as $dt => $kode_jabatan){
			$arr[]=$this->model_master->getDataBawahan($dat, $kode_jabatan);
		}
		$c_jbt=[];
		foreach($arr as $r){
			if (strpos($r, ';') !== false) {
				$c_jbt[]=$this->otherfunctions->getDataExplode($r,';','all');
			}
		}
		$jx=[];
		// return $c_jbt;
		foreach($c_jbt as $jbt){
			foreach($jbt as $j){
				$jx[] = $j;
			}
		}
		return array_filter(array_unique($jx));
	}
	public function getBagianBawahanNotifWhere($where)
	{
		if (empty($where))
			return null;
		// echo '<pre>';
		$datx=[];
		foreach($where as $key => $kode_bagian){
			$datx3=$this->model_master->getJabatanBagian($kode_bagian);
			foreach($datx3 as $kesy => $kode_jabatan){
				$datx[]=$kode_jabatan;
			}
		}
		// print_r($datx);
		// $datx=$this->model_master->getJabatanBagianWhere($where);
		// $dat=$this->model_master->getJabatanAll();
		// $arr=[];
		// foreach ($datx as $dt => $kode_jabatan){
		// 	$arr[]=$this->model_master->getDataBawahan($dat, $kode_jabatan);
		// }
		// $c_jbt=[];
		// foreach($arr as $r){
		// 	if (strpos($r, ';') !== false) {
		// 		$c_jbt[]=$this->otherfunctions->getDataExplode($r,';','all');
		// 	}
		// }
		// $jx=[];
		// // return $c_jbt;
		// foreach($c_jbt as $jbt){
		// 	foreach($jbt as $j){
		// 		$jx[] = $j;
		// 	}
		// }
		return array_filter(array_unique($datx));
	}
	public function getListJdwalKerjaId($id)
	{
		$this->db->select('jk.*,emp.nama as nama_karyawan, emp.nik, emp.id_karyawan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('data_jadwal_kerja AS jk');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = jk.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = jk.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = jk.update_by', 'left'); 
		$this->db->where('jk.id_jadwal',$id);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getEmployeekodeJabatan($kode)
	{
		$this->db->select('emp.*,loker.nama as nama_loker,jbt.nama as nama_jabatan,bag.nama as bagian,b.nama as nama_buat, c.nama as nama_update, grd.nama as nama_grade, d.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		$this->db->where('emp.jabatan',$kode);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getEmployeeIDkodeJabatan($kode)
	{
		$this->db->select('*');
		$this->db->from('karyawan');
		$this->db->where('jabatan',$kode);
		$query=$this->db->get()->row_array();
		return $query['id_karyawan'];
	}
	public function getListPresensiIdKaryawan($id_karyawan,$tahun=null){
		$this->db->select('*');
		$this->db->from('data_presensi');
		$this->db->where('id_karyawan',$id_karyawan);
		if($tahun != null){
			$this->db->where('YEAR(tanggal)', $tahun);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function cekPresensiIdKar($id)
	{
		if(empty($id))
			return null;
		$val = 'false';
		$data = $this->getListPresensiIdKaryawan($id);
		foreach ($data as $d) {
			$shift = $this->CI->model_master->getMasterShiftKode($d->kode_shift);
			if(!empty($shift)){
				$val = [
					'kode_shift' => $d->kode_shift,
					'nama_shift' => $shift['nama'],
					'jam_mulai' => $shift['jam_mulai'],
					'jam_selesai' => $shift['jam_selesai']
				];
			}
		}
		return $val;
	}
	public function cekJadwalKerjaIdDate($id, $date, $avb = null)
	{
		if(empty($id) || empty($date))
			return null;
		$val = 'false';
		$data = $this->getListJdwalKerja(['usage'=>'id'],$id);
		// $data = $this->getListJdwalKerjaId($id);
		$val = [
			'kode_shift' => null,
			'nama_shift' => null,
			'jam_mulai' => null,
			'jam_selesai' => null,
			'tanggal'=>$date,
			'id_karyawan'=>$id,
		];
		if($avb != null){
			if($data == null){
				$val == 'false';
			}
		}
		foreach ($data as $d) {
			$month = (int)date('m',strtotime($date));
			$year = date('Y',strtotime($date));
			$day = (int)date('d',strtotime($date));
			$days = 'tgl_'.$day;
			if($id == $d->id_karyawan){
				if($year == $d->tahun){
					if($month == $d->bulan){
						if(!empty($d->$days)){
							$shift = $this->model_master->getMasterShiftKode($d->$days);
							if ($d->$days == 'CUSTOM' || $d->$days == 'CSTM' || $shift['nama'] == 'CUSTOM') {
								$col_s='start_'.$day;
								$col_e='end_'.$day;
								$shift['jam_mulai']=$d->$col_s;
								$shift['jam_selesai']=$d->$col_e;
							}
							$val = [
								'kode_shift' => $d->$days,
								'nama_shift' => $shift['nama'],
								'jam_mulai' => $shift['jam_mulai'],
								'jam_selesai' => $shift['jam_selesai'],
								'tanggal'=>$date,
								'id_karyawan'=>$id,
							];
						}
					}
				}
			}
			if($avb != null){
				if($id != $d->id_karyawan && $year != $d->tahun && $month != $d->bulan){
					if(empty($d->days)){
						$val == 'false';
					}
				}
			}
		}
		return $val;
	}
	// public function cekJadwalKerjaId($id, $date)
	// {
	// 	if(empty($id) || empty($date))
	// 		return null;

	// 	$month = (int)date('m',strtotime($date));
	// 	$year = date('Y',strtotime($date));
	// 	$day = (int)date('d',strtotime($date));
	// 	$data = $this->getListJdwalKerjaWhere(['jk.id_karyawan'=>$id,'jk.bulan'=>$month,'jk.tahun'=>$year]);
	// 	$val = [];
	// 	foreach ($data as $d) {
	// 		$day = 'tgl_'.$day;
	// 		if(!empty($d->$day)){
	// 			$shift = $this->CI->model_master->getMasterShiftKode($d->$day);
	// 			$val = [
	// 				'kode_shift' => $d->$day,
	// 				'nama_shift' => $shift['nama'],
	// 				'jam_mulai' => $shift['jam_mulai'],
	// 				'jam_selesai' => $shift['jam_selesai']
	// 			];
	// 		}
	// 	}
	// 	return $val;
	// }
	// public function cekJadwalKerjaId($id, $date)
	// {
	// 	if(empty($id) || empty($date))
	// 		return null;

	// 	$val = 'false';
	// 	$data = $this->getListJdwalKerja(['usage'=>'all']);
	// 	foreach ($data as $d) {
	// 		$month = date('m',strtotime($date));
	// 		$year = date('Y',strtotime($date));
	// 		$day = (int)date('d',strtotime($date));
	// 		$day = 'tgl_'.$day;
	// 		if($id == $d->id_karyawan){
	// 			if($year == $d->tahun){
	// 				if($month == $d->bulan){
	// 					if(!empty($d->$day)){
	// 						$shift = $this->CI->model_master->getMasterShiftKode($d->$day);
	// 						$val = [
	// 							'kode_shift' => $d->$day,
	// 							'nama_shift' => $shift['nama'],
	// 							'jam_mulai' => $shift['jam_mulai'],
	// 							'jam_selesai' => $shift['jam_selesai']
	// 						];
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// 	return $val;
	// }
	public function cekJadwalKerjaIdK($id_karyawan,$bulan_mulai,$tahun){
		if(empty($id_karyawan) || empty($bulan_mulai) || empty($tahun))
			return null;
		$this->db->where('id_karyawan',$id_karyawan);
		$this->db->where('bulan',$bulan_mulai);
		$this->db->where('tahun',$tahun);
		$query=$this->db->get('data_jadwal_kerja')->result();
		return $query;
	}
	public function cekLemburId($nik,$tgl)
	{
		if(empty($nik) || empty($tgl))
			return null;
		$new_val = null;
		$data = $this->getListLemburNik($nik);
		foreach ($data as $d) {
			if($tgl==$d->tgl_mulai){
				$lembur = $this->otherfunctions->getRangeTime($d->jam_mulai,$d->jam_selesai);
				$new_val = $lembur;
			}
		}
		return $new_val;
	}
	public function getShiftForSelect2($edit=null)
	{
		$data=$this->model_master->getListMasterShiftAk();
		$pack=[];
		foreach ($data as $d) {
			(isset($edit)?$pack[null]='Pilih Data':null);
			$hari_v =$this->formatter->getFormatManyDays($d->hari);
			$pack[$d->kode_master_shift]=$d->nama.((!empty($hari_v)) ? ' ('.$hari_v.', '.$this->formatter->getTimeFormatUser($d->jam_mulai).'-'.$this->formatter->getTimeFormatUser($d->jam_selesai).')' : '');
		}
		return $pack;
	}
	public function getRefreshShift()
	{
		$data=$this->model_master->getListMasterShiftAk('custom');
		$pack=[];
		foreach ($data as $d) {
			$hari_v =$this->formatter->getFormatManyDays($d->hari);
			$pack[$d->kode_master_shift]=$d->nama.((!empty($hari_v)) ? ' ('.$hari_v.', '.$this->formatter->getTimeFormatUser($d->jam_mulai).'-'.$this->formatter->getTimeFormatUser($d->jam_selesai).')' : '');
		}
		if (isset($pack[null])) {
			unset($pack[null]);
		}
		return $pack;
	}
//========================================== DATA PRESENSI ====================================================
//
	public function getListPresensi($mode = 'group',$data, $fltr = null)
	{
		$filter=$this->model_global->getFilterbyBagian(((isset($data['bagian']))?$data['bagian']:null));
		$tanggal_mulai = date('Y-m-d',strtotime($this->otherfunctions->getDateNow()));
		$tanggal_selesai = date('Y-m-d',strtotime('-1 month',strtotime($this->otherfunctions->getDateNow())));
		if (isset($data['tanggal'])) {
			if(!empty($data['tanggal'])){
				$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'start')));
				$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'end')));
			}
		}
		$this->db->select("pre.*,emp.nama as nama_karyawan, emp.nik, emp.id_karyawan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur,(SELECT COUNT(*) FROM data_presensi x WHERE x.id_karyawan = pre.id_karyawan AND (x.tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_selesai')) as jum");
		$this->db->from('data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = pre.kode_hari_libur', 'left'); 
		if($data['usage']=='search'){
			(!empty($data['lokasi'])) ? $lokasi = ["lkr.kode_loker" =>$data['lokasi']] : $lokasi=[];
			(!empty($data['karyawan'])) ? $karyawan = ["pre.id_karyawan" => $data['karyawan']] : $karyawan=[];
			$nquery = array_merge($lokasi,$karyawan);
			$nexts = (!empty($nquery))? $this->db->where($nquery) : null;
			$nexts;
		}
		$this->db->where("pre.tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'");
		if($mode=='group'){
			$this->db->group_by('pre.id_karyawan'); 
		}
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListPresensiIdKar($usage,$karyawan,$data)
	{
		$tanggal_mulai = date('Y-m-d',strtotime($this->otherfunctions->getDateNow()));
		$tanggal_selesai = date('Y-m-d',strtotime('-1 month',strtotime($this->otherfunctions->getDateNow())));
		if(isset($data['tanggal'])){
			if (!empty($data['tanggal'])) {
				$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'start')));
				$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'end')));
			}			
		}
		$this->db->select("pre.*,emp.nama as nama_karyawan, emp.nik, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur,shf.nama as nama_shift,shf.jam_mulai as jam_mulai_shift,shf.jam_selesai as jam_selesai_shift,dl.val_jumlah_lembur,ijin.jenis as jenis_izin,mijin.nama as nama_ijin,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.terlambat')) as setting_terlambat,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.dispensasi_jam_masuk')) as setting_dispensasi_jam_masuk,IF(bef.jam_selesai >= '02:00:00' AND bef.jam_selesai <= '08:00:00',DATE_FORMAT(DATE_ADD(CONCAT(bef.tanggal,' ',bef.jam_selesai),INTERVAL +7 HOUR),'%H:%i:%s'),null) as dispensasi");
		$this->db->from('data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left');
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = pre.kode_hari_libur', 'left'); 
		$this->db->join('master_shift AS shf', 'shf.kode_master_shift = pre.kode_shift', 'left'); 
		$this->db->join('data_lembur AS dl', 'dl.no_lembur = pre.no_spl', 'left');
		$this->db->join('data_izin_cuti_karyawan AS ijin', 'ijin.kode_izin_cuti = pre.kode_ijin', 'left'); 
		$this->db->join('master_izin AS mijin', 'mijin.kode_master_izin = ijin.jenis', 'left'); 
		$this->db->join('data_presensi AS bef', 'bef.tanggal = DATE_ADD(pre.tanggal,INTERVAL -1 DAY) and bef.id_karyawan = pre.id_karyawan', 'left');
		$this->db->where('pre.id_karyawan',$karyawan);
		$this->db->where("pre.tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'");
		$this->db->order_by('pre.tanggal','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListPresensiId($id = null, $where = null, $fo=null,$row=null)
	{
		$this->db->select("pre.*,emp.id_karyawan, emp.nama as nama_karyawan, emp.nik, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur, b.nama as nama_buat, c.nama as nama_update,sft.jam_mulai as jam_mulai_shift,sft.jam_selesai as jam_selesai_shift,sft.nama as nama_shift,dl.val_jumlah_lembur,dic.jenis as jenis_izin,mi.nama as nama_jenis_izin,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.terlambat')) as setting_terlambat,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.dispensasi_jam_masuk')) as setting_dispensasi_jam_masuk,IF(bef.jam_selesai >= '02:00:00' AND bef.jam_selesai <= '08:00:00',DATE_FORMAT(DATE_ADD(CONCAT(bef.tanggal,' ',bef.jam_selesai),INTERVAL +7 HOUR),'%H:%i:%s'),null) as dispensasi");
		$this->db->from('data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = pre.kode_hari_libur', 'left'); 
		$this->db->join('master_shift AS sft', 'sft.kode_master_shift = pre.kode_shift', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = pre.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = pre.update_by', 'left');
		$this->db->join('data_lembur AS dl', 'dl.no_lembur = pre.no_spl', 'left');
		$this->db->join('data_izin_cuti_karyawan AS dic', 'dic.kode_izin_cuti = pre.kode_ijin', 'left');
		$this->db->join('master_izin AS mi', 'mi.kode_master_izin = dic.jenis', 'left'); 
		$this->db->join('data_presensi AS bef', 'bef.tanggal = DATE_ADD(pre.tanggal,INTERVAL -1 DAY) and bef.id_karyawan = pre.id_karyawan', 'left');
		if(!empty($id)){
			$this->db->where('pre.id_p_karyawan',$id);
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		if($fo=='fo'){
			$this->db->where('pre.status',1);
		}
		if($row != null){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getPresensiWhereRow($where = null)
	{
		$this->db->select('a.*, sft.potongan');
		$this->db->from('data_presensi as a');
		$this->db->join('master_shift AS sft', 'sft.kode_master_shift = a.kode_shift', 'left'); 
		if(!empty($where)){
			$this->db->where($where);
		}
		$query=$this->db->get()->row_array();
		return $query;
	}

	public function cekPresensiId($id,$date)
	{
		if(empty($id) || empty($date))
			return null;
		$cek = 'true';
		$data = $this->getListPresensi('no_group',null);
		foreach ($data as $d) {
			if($date==$d->tanggal){
				if($id==$d->id_karyawan){
					$cek = 'false';
				}
			}
		}
		return $cek;
	}
	public function synctoDataPresensi($date,$shift,$id_emp)
	{
		$ret=false;
		if (empty($date) || empty($shift) || empty($id_emp)) 
			return $ret;
		$data=[
			'tanggal'=>$this->CI->formatter->getDateFormatDb($date),
			'kode_shift'=>$shift,
			'id_karyawan'=>$id_emp
		];
		$this->model_global->updateQueryNoMsg(['sync'=>0],'temporari_data_presensi',['id_karyawan'=>$id_emp,'tanggal'=>$data['tanggal']]);
		$cek_presensi=$this->checkPresensiDate($id_emp,$date);
		if ($cek_presensi) {
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQueryNoMsg($data,'data_presensi',['id_karyawan'=>$id_emp,'tanggal'=>$data['tanggal']]);
			$ret=true;
		}else{
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$this->model_global->insertQueryNoMsg($data,'data_presensi');
			$ret=true;
		}
		return $ret;
	}
	public function syncIzintoDataPresensi($id_karyawan,$kode,$tgl_awal,$tgl_akhir)
	{
		$ret=false;
		if (empty($id_karyawan) ||empty($kode) || empty($tgl_awal) || empty($tgl_akhir)) 
			return $ret;
		$cekPre = $this->checkPresensiEmpDate($id_karyawan, $tgl_awal);
		$cekPreOld = $this->checkPresensiEmpDate($id_karyawan, $tgl_akhir);
		if($tgl_awal != $tgl_akhir){
			if(!empty($cekPreOld)){
				$datao = [
					'id_karyawan'=>$id_karyawan,
					'tanggal'=>$cekPreOld['tanggal'],
					'kode_ijin'=>null,
					'jam_mulai'=>$cekPreOld['jam_mulai'],
					'jam_selesai'=>$cekPreOld['jam_selesai'],
					'kode_shift'=>$cekPreOld['kode_shift'],
					'kode_hari_libur'=>$cekPreOld['kode_hari_libur'],
					'no_spl'=>$cekPreOld['no_spl'],
					'no_perjalanan_dinas'=>$cekPreOld['no_perjalanan_dinas'],
				];				
				$datao=array_merge($datao,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQueryNoMsg($datao,'data_presensi',['id_p_karyawan'=>$cekPreOld['id_p_karyawan']]);
				$ret=true;
			}
			if(!empty($cekPre)){
				$data = [
					'id_karyawan'=>$id_karyawan,
					'tanggal'=>$cekPre['tanggal'],
					'kode_ijin'=>null,
					'jam_mulai'=>$cekPre['jam_mulai'],
					'jam_selesai'=>$cekPre['jam_selesai'],
					'kode_shift'=>$cekPre['kode_shift'],
					'kode_hari_libur'=>$cekPre['kode_hari_libur'],
					'no_spl'=>$cekPre['no_spl'],
					'no_perjalanan_dinas'=>$cekPre['no_perjalanan_dinas'],
				];				
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQueryNoMsg($data,'data_presensi',['id_p_karyawan'=>$cekPre['id_p_karyawan']]);
				$ret=true;
			}
		}else{
			if(!empty($cekPre)){
				$data = [
					'id_karyawan'=>$id_karyawan,
					'tanggal'=>$cekPre['tanggal'],
					'kode_ijin'=>null,
					'jam_mulai'=>$cekPre['jam_mulai'],
					'jam_selesai'=>$cekPre['jam_selesai'],
					'kode_shift'=>$cekPre['kode_shift'],
					'kode_hari_libur'=>$cekPre['kode_hari_libur'],
					'no_spl'=>$cekPre['no_spl'],
					'no_perjalanan_dinas'=>$cekPre['no_perjalanan_dinas'],
				];				
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQueryNoMsg($data,'data_presensi',['id_p_karyawan'=>$cekPre['id_p_karyawan']]);
				$ret=true;
			}
		}
		return $ret;
	}
	public function checkPresensiDate($id,$date)
	{
		if (empty($id) || empty($date))
			return false;
		$this->db->where('id_karyawan',$id); 
		$this->db->where('tanggal',$date);
		$query=$this->db->get('data_presensi')->num_rows();
		if ($query > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function checkPresensiWhere($where)
	{
		if (empty($where))
			return false;
		$this->db->where($where);
		$query=$this->db->get('data_presensi')->num_rows();
		if ($query > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function checkPresensiEmpDate($id,$date)
	{
		if (empty($id) || empty($date))
			return false;
		$this->db->where('id_karyawan',$id); 
		$this->db->where('tanggal',$date);
		$query=$this->db->get('data_presensi')->row_array();
		return $query;
	}
	public function getDataLemburDate($id,$date)
	{
		if (empty($id) || empty($date))
			return false;
		$this->db->where('id_karyawan',$id); 
		$this->db->where('tgl_mulai',$date);
		$this->db->where('validasi',1);
		$query=$this->db->get('data_lembur')->result();
		return $query;
	}
	public function getDataLemburBagianDate($kode_bagian,$date)
	{
		if (empty($kode_bagian) || empty($date))
			return false;
		$this->db->select('lem.*,jab.kode_bagian');
		$this->db->from('data_lembur AS lem');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = lem.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = emp.jabatan', 'left');
		$this->db->where('jab.kode_bagian',$kode_bagian);
		$this->db->where('lem.tgl_mulai', $date);
		$this->db->where('lem.validasi',1);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataLemburBagianIDKarMonth($where, $month, $tahun)
	{
		if (empty($where) || empty($month))
			return false;
		$this->db->select('lem.*,jab.kode_bagian, emp.nama as nama_kar');
		$this->db->from('data_lembur AS lem');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = lem.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = emp.jabatan', 'left');
		$this->db->where($where);
		$this->db->where('MONTH(lem.tgl_mulai)', $month);
		$this->db->where('YEAR(lem.tgl_mulai)', $tahun);
		$this->db->where('emp.kode_penggajian', 'BULANAN');
		$this->db->where('lem.validasi',1);
		$this->db->where('emp.status_emp','1');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataLemburBagianMonth($kode_bagian,$date, $tahun)
	{
		if (empty($kode_bagian) || empty($date))
			return false;
		$this->db->select('lem.*,jab.kode_bagian, emp.nama as nama_kar');
		$this->db->from('data_lembur AS lem');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = lem.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = emp.jabatan', 'left');
		$this->db->where('jab.kode_bagian',$kode_bagian);
		$this->db->where('MONTH(lem.tgl_mulai)', $date);
		$this->db->where('YEAR(lem.tgl_mulai)', $tahun);
		$this->db->where('emp.kode_penggajian', 'BULANAN');
		$this->db->where('lem.validasi',1);
		$this->db->where('emp.status_emp','1');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataLemburJabatanMonth($kode_jabatan,$date, $tahun)
	{
		if (empty($kode_jabatan) || empty($date))
			return false;
		$this->db->select('lem.*,jab.kode_jabatan');
		$this->db->from('data_lembur AS lem');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = lem.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = emp.jabatan', 'left');
		$this->db->where('jab.kode_jabatan',$kode_jabatan);
		$this->db->where('MONTH(lem.tgl_mulai)', $date);
		$this->db->where('YEAR(lem.tgl_mulai)', $tahun);
		$this->db->where('emp.kode_penggajian', 'BULANAN');
		$this->db->where('lem.validasi',1);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataLemburJabatan($where)
	{
		if (empty($where))
			return false;
		$this->db->select('lem.*,jab.kode_jabatan,
		(select count(*) from data_lembur jmlh where jmlh.no_lembur = lem.no_lembur) as jum_no,
		j.nama as nama_buat
		');
		$this->db->from('data_lembur AS lem');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = lem.id_karyawan', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = lem.dibuat_oleh', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = emp.jabatan', 'left');
		$this->db->where($where);
		// $this->db->where('lem.validasi','2');
		$this->db->group_by('lem.no_lembur');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkPresensiJamSelesai($id,$date)
	{
		if (empty($id) || empty($date))
			return false;
		$this->db->where('id_karyawan',$id); 
		$this->db->where('tanggal',$date);
		$query=$this->db->get('data_presensi')->row_array();
		return $query['jam_selesai'];
	}
	public function getLogLogin($id){
		if (empty($id)) 
			return null;
		return $this->db->get_where('log_login_karyawan',array('id_karyawan'=>$id))->result();
	}
	public function cekDataIzinCutiKar($id_k){
		$this->db->select('ij.*,izn.jenis as jenis_izin');
		$this->db->from('data_izin_cuti_karyawan as ij');
		$this->db->join('master_izin AS izn', 'izn.kode_master_izin = ij.jenis', 'left');
		$this->db->where('ij.id_karyawan',$id_k);
		$query=$this->db->get()->result();
		return $query;
	}
	public function cekDataIzinCuti($id_kar, $jenis)//, $id=null)
	{
		if(empty($id_kar) || empty($jenis))
			return null;
		$dataz = [];
		$data =  $this->cekDataIzinCutiKar($id_kar);
		foreach ($data as $d) {
			$create = substr($d->create_date,0,10);
			$now = substr($this->date,0,10);
			$dataz[] = ['buat'=>$create,'sekarang'=>$now,'id_kar'=>$id_kar,'id_karyawan'=>$d->id_karyawan,'jenis'=>$jenis,'jenis_db'=>$d->jenis_izin];
		}
		return $dataz;
	}
	public function getListPresensiHarian($data)
	{ 
		$filter=$this->model_global->getFilterbyBagian(((isset($data['bagian_filter']))?$data['bagian_filter']:null));
		$tanggal_mulai = date('Y-m-d',strtotime($this->otherfunctions->getDateNow()));
		$tanggal_selesai = date('Y-m-d',strtotime(strtotime($this->otherfunctions->getDateNow())));
		if (isset($data['tanggal'])) {
			if(!empty($data['tanggal'])){
				$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'start')));
				$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'end')));
			}
		}
		$this->db->select("pre.*,emp.nama as nama_karyawan, emp.nik, emp.id_karyawan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur,shf.jam_mulai as jam_mulai_shift,shf.jam_selesai as jam_selesai_shift,shf.nama as nama_shift,dl.val_jumlah_lembur,dl.val_potong_jam,dic.jenis as jenis_izin,mi.nama as nama_jenis_izin,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.terlambat')) as setting_terlambat,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.dispensasi_jam_masuk')) as setting_dispensasi_jam_masuk,IF(bef.jam_selesai >= '02:00:00' AND bef.jam_selesai <= '08:00:00',DATE_FORMAT(DATE_ADD(CONCAT(bef.tanggal,' ',bef.jam_selesai),INTERVAL +7 HOUR),'%H:%i:%s'),null) as dispensasi");
		$this->db->from('data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = pre.kode_hari_libur', 'left'); 
		$this->db->join('master_shift AS shf', 'shf.kode_master_shift = pre.kode_shift', 'left');
		$this->db->join('data_lembur AS dl', 'dl.no_lembur = pre.no_spl and dl.id_karyawan=pre.id_karyawan', 'left');
		$this->db->join('data_izin_cuti_karyawan AS dic', 'dic.kode_izin_cuti = pre.kode_ijin', 'left');
		$this->db->join('master_izin AS mi', 'mi.kode_master_izin = dic.jenis', 'left');
		$this->db->join('data_presensi AS bef', 'bef.tanggal = DATE_ADD(pre.tanggal,INTERVAL -1 DAY) and bef.id_karyawan = pre.id_karyawan', 'left');
		if(!empty($data['lokasi'])){
			$this->db->where("emp.loker",$data['lokasi']);
		}
		// if (isset($data['opsi'])) {
		// 	if ($data['opsi'] == 'normal') {
		// 		$this->db->where('(pre.jam_mulai IS NOT NULL AND pre.jam_selesai IS NOT NULL)');
		// 	}elseif ($data['opsi'] == 'no_scan') {
		// 		$this->db->where('(pre.jam_mulai IS NULL AND pre.jam_selesai IS NULL)');
		// 	}elseif ($data['opsi'] == 'no_scan_in') {
		// 		$this->db->where('(pre.jam_mulai IS NULL AND pre.jam_selesai IS NOT NULL)');
		// 	}elseif ($data['opsi'] == 'no_scan_out') {
		// 		$this->db->where('(pre.jam_mulai IS NOT NULL AND pre.jam_selesai IS NULL)');
		// 	}elseif ($data['opsi'] == 'late') {
		// 		$this->db->where('(pre.jam_mulai > shf.jam_mulai)');
		// 	}elseif ($data['opsi'] == 'go_home_to_fast') {
		// 		$this->db->where('(pre.jam_selesai < shf.jam_selesai)');
		// 	}
		// }
		$this->db->where("pre.tanggal BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'");
		$this->db->where('emp.status_emp = 1 '.(($filter)?' AND '.$filter:null));
		// $this->db->group_by('pre.id_karyawan');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListPresensiHarianPrint($data=null, $where=null)
	{ 
		if(!empty($data)){
			$filter=$this->model_global->getFilterbyBagian(((isset($data['bagian']))?$data['bagian']:null));
			$tanggal_mulai = date('Y-m-d',strtotime($this->otherfunctions->getDateNow()));
			$tanggal_selesai = date('Y-m-d',strtotime(strtotime($this->otherfunctions->getDateNow())));
			if (isset($data['tanggal'])) {
				if(!empty($data['tanggal'])){
					$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'start')));
					$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'end')));
				}
			}
		}
		$this->db->select("pre.*,emp.nama as nama_karyawan, emp.nik, emp.id_karyawan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur,shf.jam_mulai as jam_mulai_shift,shf.jam_selesai as jam_selesai_shift,shf.nama as nama_shift,dl.val_jumlah_lembur,dl.val_potong_jam,dic.jenis as jenis_izin,mi.nama as nama_jenis_izin,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.terlambat')) as setting_terlambat,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.dispensasi_jam_masuk')) as setting_dispensasi_jam_masuk,IF(bef.jam_selesai >= '02:00:00' AND bef.jam_selesai <= '08:00:00',DATE_FORMAT(DATE_ADD(CONCAT(bef.tanggal,' ',bef.jam_selesai),INTERVAL +7 HOUR),'%H:%i:%s'),null) as dispensasi");
		$this->db->from('data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = pre.kode_hari_libur', 'left'); 
		$this->db->join('master_shift AS shf', 'shf.kode_master_shift = pre.kode_shift', 'left');
		$this->db->join('data_lembur AS dl', 'dl.no_lembur = pre.no_spl and dl.id_karyawan=pre.id_karyawan', 'left');
		$this->db->join('data_izin_cuti_karyawan AS dic', 'dic.kode_izin_cuti = pre.kode_ijin', 'left');
		$this->db->join('master_izin AS mi', 'mi.kode_master_izin = dic.jenis', 'left');
		$this->db->join('data_presensi AS bef', 'bef.tanggal = DATE_ADD(pre.tanggal,INTERVAL -1 DAY) and bef.id_karyawan = pre.id_karyawan', 'left');
		if(!empty($data)){
			$this->db->where("pre.tanggal BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'");
			$this->db->where('emp.status_emp = 1 '.(($filter)?' AND '.$filter:null));
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		// $this->db->group_by('pre.id_karyawan');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListAbsensiHarianPrint($tanggal=null, $lokasi=null, $where=null, $row=false)
	{ 
		$this->db->select("pre.*,emp.nama as nama_karyawan, emp.nik, emp.id_karyawan, emp.jabatan as kode_jabatan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur,shf.jam_mulai as jam_mulai_shift,shf.jam_selesai as jam_selesai_shift,shf.nama as nama_shift,dl.val_jumlah_lembur,dl.val_potong_jam,dic.jenis as jenis_izin,mi.nama as nama_jenis_izin,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.terlambat')) as setting_terlambat,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.dispensasi_jam_masuk')) as setting_dispensasi_jam_masuk,IF(bef.jam_selesai >= '02:00:00' AND bef.jam_selesai <= '08:00:00',DATE_FORMAT(DATE_ADD(CONCAT(bef.tanggal,' ',bef.jam_selesai),INTERVAL +7 HOUR),'%H:%i:%s'),null) as dispensasi, mi.jenis as iorc, dic.jam_mulai as jam_mulai_izin, dic.jam_selesai as jam_selesai_izin, dic.alasan, dic.tgl_mulai as tgl_mulai_izin, dic.tgl_selesai as tgl_selesai_izin");
		$this->db->from('data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = pre.kode_hari_libur', 'left'); 
		$this->db->join('master_shift AS shf', 'shf.kode_master_shift = pre.kode_shift', 'left');
		$this->db->join('data_lembur AS dl', 'dl.no_lembur = pre.no_spl and dl.id_karyawan=pre.id_karyawan', 'left');
		$this->db->join('data_izin_cuti_karyawan AS dic', 'dic.kode_izin_cuti = pre.kode_ijin', 'left');
		$this->db->join('master_izin AS mi', 'mi.kode_master_izin = dic.jenis', 'left');
		$this->db->join('data_presensi AS bef', 'bef.tanggal = DATE_ADD(pre.tanggal,INTERVAL -1 DAY) and bef.id_karyawan = pre.id_karyawan', 'left');
		if(!empty($tanggal)){
			$this->db->where("pre.tanggal = '".$tanggal."'");
			$this->db->where('emp.status_emp = 1 ');
		}
		if(!empty($lokasi)){
			$this->db->where("emp.loker = '".$lokasi."'");
		}
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
	public function getListAbsensiHarianRange($tanggal_mulai=null, $tanggal_selesai=null, $lokasi=null, $where=null)
	{ 
		$this->db->select("pre.*,emp.nama as nama_karyawan, emp.nik, emp.id_karyawan, emp.jabatan as kode_jabatan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur,shf.jam_mulai as jam_mulai_shift,shf.jam_selesai as jam_selesai_shift,shf.nama as nama_shift,dl.val_jumlah_lembur,dl.val_potong_jam,dic.jenis as jenis_izin,mi.nama as nama_jenis_izin,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.terlambat')) as setting_terlambat,JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.dispensasi_jam_masuk')) as setting_dispensasi_jam_masuk,IF(bef.jam_selesai >= '02:00:00' AND bef.jam_selesai <= '08:00:00',DATE_FORMAT(DATE_ADD(CONCAT(bef.tanggal,' ',bef.jam_selesai),INTERVAL +7 HOUR),'%H:%i:%s'),null) as dispensasi, mi.jenis as iorc, dic.jam_mulai as jam_mulai_izin, dic.jam_selesai as jam_selesai_izin, dic.alasan, dic.tgl_mulai as tgl_mulai_izin, dic.tgl_selesai as tgl_selesai_izin");
		$this->db->from('data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = pre.kode_hari_libur', 'left'); 
		$this->db->join('master_shift AS shf', 'shf.kode_master_shift = pre.kode_shift', 'left');
		$this->db->join('data_lembur AS dl', 'dl.no_lembur = pre.no_spl and dl.id_karyawan=pre.id_karyawan', 'left');
		$this->db->join('data_izin_cuti_karyawan AS dic', 'dic.kode_izin_cuti = pre.kode_ijin', 'left');
		$this->db->join('master_izin AS mi', 'mi.kode_master_izin = dic.jenis', 'left');
		$this->db->join('data_presensi AS bef', 'bef.tanggal = DATE_ADD(pre.tanggal,INTERVAL -1 DAY) and bef.id_karyawan = pre.id_karyawan', 'left');
		if(!empty($tanggal_mulai) && !empty($tanggal_selesai)){
			$this->db->where("pre.tanggal BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'");
			$this->db->where('emp.status_emp = 1 ');
		}
		if(!empty($lokasi)){
			$this->db->where("emp.loker = '".$lokasi."'");
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function cekIzinCutiKode($kode)
	{
		$this->db->select('*');
		$this->db->from('data_izin_cuti_karyawan');
		$this->db->where('kode_izin_cuti',$kode);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListPresensiLog($data,$where = null)
	{ 
		$filter=$this->model_global->getFilterbyBagian(((isset($data['bagian_filter']))?$data['bagian_filter']:null));
		$tanggal_mulai = date('Y-m-d',strtotime('-1 day',strtotime($this->otherfunctions->getDateNow())));
		$tanggal_selesai = date('Y-m-d',strtotime($this->otherfunctions->getDateNow()));
		if (isset($data['tanggal'])) {
			if(!empty($data['tanggal'])){
				$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'start')));
				$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'end')));
			}
		}
		$this->db->select('pre.*,emp.nama as nama_karyawan, emp.nik, emp.id_karyawan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker');
		$this->db->from('temporari_data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
		if(!empty($data['lokasi'])){
			$this->db->where("emp.loker",$data['lokasi']);
		} 
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->where("pre.tanggal BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'");
		$this->db->where('emp.status_emp = 1 '.(($filter)?' AND '.$filter:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListPresensiIdLog($id = null, $where = null, $fo=null,$row=null)
	{
		$this->db->select('pre.*,emp.id_karyawan, emp.nama as nama_karyawan, emp.nik, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('temporari_data_presensi AS pre');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = pre.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left');
		$this->db->join('admin AS b', 'b.id_admin = pre.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = pre.update_by', 'left');
		if(!empty($id)){
			$this->db->where('pre.id_temporari',$id);
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		if($fo=='fo'){
			$this->db->where('pre.status',1);
		}
		if($row != null){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getDataTemporari($tgl_mulai, $tgl_selesai, $where=null, $order=null)
	{
		$this->db->select('a.*,emp.nik,lkr.nama as nama_loker');
		$this->db->from('temporari_data_presensi as a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_level_struktur AS lvl', 'lvl.kode_level_struktur = bgn.kode_level_struktur', 'left');
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left');
		$this->db->where("tanggal BETWEEN '".$tgl_mulai."' AND '".$tgl_selesai."'");
		// $this->db->where("tanggal = '".$tgl_mulai."'");
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($order)){
			$this->db->order_by($order['kolom'], $order['value']);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataTemporariSync($tgl_mulai, $tgl_selesai,$where=null)
	{
		$this->db->select('a.*');
		$this->db->from('temporari_data_presensi as a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_level_struktur AS lvl', 'lvl.kode_level_struktur = bgn.kode_level_struktur', 'left');
		$this->db->where("tanggal = '".$tgl_mulai."'");
		if(!empty($where)){
			$this->db->where($where);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getTemporariWhere($where)
	{
		$this->db->select('*');
		$this->db->from('temporari_data_presensi');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getTemporariWhereNew($where)
	{
		$this->db->select('a.*, emp.nama as nama_karyawan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian');
		$this->db->from('temporari_data_presensi as a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_level_struktur AS lvl', 'lvl.kode_level_struktur = bgn.kode_level_struktur', 'left');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function cekJadwalSync($start_date, $end_date)
	{
		$jadwal_kerja=[];
		while ($start_date <= $end_date)
		{
			$d=date('d',strtotime($start_date));
			$mo=date('m',strtotime($start_date));
			$y=date('Y',strtotime($start_date));
			$m=(($mo < 10) ? str_replace('0','',$mo) : $mo);
			$data_jadwal=$this->getIdShiftNoKar($d,$m,$y);
			if (isset($data_jadwal)) {
				foreach ($data_jadwal as $d) {
					if (!empty($d->shift)) {
						$jadwal_kerja[]=[
						// $jadwal_kerja[$d->id_finger][$start_date]=[
							'tanggal'   	=>$start_date,
							'id_karyawan'   =>$d->id_karyawan,
							'kode_shift'    =>$d->shift,
							'nik'           =>$d->nik,
							's'             =>$d->jam_mulai,
							'e'             =>$d->jam_selesai,
							'jam_masuk'     =>null,
							'jam_keluar'    =>null
						];
					}
				}
			}
			$start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));
		}
		return $jadwal_kerja;
	}
	public function coreImportPresensi($data)
	{
		// echo '<pre>';
		// print_r($data);
		$ret=[];
		if (empty($data)) 
			return $ret;
		if (isset($data['jadwal'])) {
			$toleransi_masuk=3;//jam
			$toleransi_pulang=13;//jam
			$tglx2=date('Y-m-d',strtotime($data['tanggal'].' -1 day'));
			$cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($data['id_karyawan'],$tglx2);
			if (!empty($cekjadwal['jam_mulai']) && !empty($cekjadwal['jam_selesai'])) {
				$half=$this->formatter->difHalfTime($cekjadwal['jam_mulai'],$cekjadwal['jam_selesai']);
				//toleransi jam masuk
				$tol_s_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],$toleransi_masuk,'-');
				$tol_e_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],$half,'+');
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],$toleransi_pulang,'+');
				// echo $tol_s_selesai.' - '.$tol_e_selesai;
				// echo '<br>';
				$time=$data['jam'];
				if (strtotime($cekjadwal['jam_selesai']) < strtotime($cekjadwal['jam_mulai'])) {					
					if (strtotime($tol_s_selesai) > strtotime($tol_e_selesai)) {
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$cekjadwal['kode_shift'];
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$cekjadwal['kode_shift'];
							}
						}
					}									
				}
				if (strtotime($cekjadwal['jam_selesai']) > strtotime($time) && ($time >= '00:00:00' && $time <= $tol_e_selesai)) {
				// if (strtotime($cekjadwal['jam_selesai']) > strtotime($time) && ($time >= '00:00:00' && $time <= '06:00:00')) {
					$ret['jam_selesai']=$time;
					$ret['tanggal']=$tglx2;
					$ret['kode_shift']=$cekjadwal['kode_shift'];
				}										
			}	
			if (!empty($data['jadwal']['jam_mulai']) && !empty($data['jadwal']['jam_selesai'])) {
				$half=$this->formatter->difHalfTime($data['jadwal']['jam_mulai'],$data['jadwal']['jam_selesai']);
				//toleransi jam masuk
				$tol_s_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],$toleransi_masuk,'-');
				$tol_e_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],$half,'+');
				// echo $data['jadwal']['jam_mulai'].' >= '.$data['jadwal']['jam_selesai'].'<=>'.$toleransi_masuk.'<='.$half;
				// echo $tol_s_mulai.' >= '.$tol_e_mulai.'<=>'.$toleransi_masuk.'<='.$half;
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],$toleransi_pulang,'+');
				//compare
				$time=$data['jam'];
				$tgl=$data['jadwal']['tanggal'];			
				if (strtotime($data['jadwal']['jam_selesai']) < strtotime($data['jadwal']['jam_mulai'])) {
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) >= strtotime($tol_e_mulai)) {
						// echo '1';
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}
					}
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						// echo '2';
						// echo $time.' >= '.$tol_s_mulai.'<=>'.$time.'<='.$tol_e_mulai;
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}
					}
					// $tglx=date('Y-m-d',strtotime($tgl.' +1 day'));
					// if (isset($data_excel[$finger][$tglx])) {
					// 	$timez=min($data_excel[$finger][$tglx]);
					// 	if ($timez >= $tol_s_selesai && $timez <= $tol_e_selesai) {
					// 		$ret['jam_selesai']=$timez;
					// 	}
					// }
					// if (isset($ret['jam_selesai'])) {
					// 	if (empty($ret['jam_selesai'])) {
					// 		$tglx1=date('Y-m-d',strtotime($tgl.' +1 day'));
					// 		if (isset($data_excel[$finger][$tglx1])) {
					// 			$timez1=min($data_excel[$finger][$tglx1]);
					// 			if ($tol_e_selesai < $tol_s_selesai) {
					// 				if ($timez1 <= $tol_s_selesai && $tol_e_selesai >= $timez1) {
					// 					$ret['jam_selesai']=$timez1;
					// 				}
					// 			}
					// 		}
					// 	}
					// }
					if (isset($ret['jam_mulai'])) {
						if (empty($ret['jam_mulai'])) {
							if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
								$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
								if (!$cek) {
									$ret['jam_mulai']=$time;
								}
							}
						}
					}
					//for jadwal 00:00:00
					if (isset($ret['jam_selesai']) && $data['jadwal']['jam_selesai'] == '00:00:00') {
						if (empty($ret['jam_selesai'])) {
							if (strtotime($tol_e_selesai) < strtotime($tol_s_selesai)) {
								if (strtotime($time) >= strtotime($tol_s_selesai)) {
									// $cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									// if (!$cek) {
										$ret['jam_selesai']=$time;
									// }
								}
								if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
									// $cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									// if (!$cek) {
										$ret['jam_selesai']=$time;
									// }
								}
							}else{
								if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
									// $cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									// if (!$cek) {
										$ret['jam_selesai']=$time;
									// }
								}
							}
						}
					}		
				}else{	
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							if (isset($ret['jam_selesai'])) {
								if ($time == $ret['jam_selesai']) {
									unset($ret['jam_selesai']);
								}
							}
							$ret['jam_mulai']=$time;
							$ret['tanggal']=$tgl;
							$ret['kode_shift']=$data['jadwal']['kode_shift'];
						}						
					}
					if (strtotime($tol_e_selesai) < strtotime($tol_s_selesai)) {
						if (strtotime($time) >= strtotime($tol_s_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['tanggal']=$tgl;
								$ret['kode_shift']=$data['jadwal']['kode_shift'];
								$ret['jam_selesai']=$time;
							}
						}
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
					}             						
				}
			}
		}		
		return $ret;
	}
	public function coreImportPresensiOLD($data)
	{
		$ret=[];
		if (empty($data)) 
			return $ret;
		if (isset($data['jadwal'])) {
			$tglx2= date('Y-m-d',strtotime($data['tanggal'].' -1 day'));
			$cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($data['id_karyawan'],$tglx2);
			if (!empty($cekjadwal['jam_mulai']) && !empty($cekjadwal['jam_selesai'])) {
				if (strtotime($cekjadwal['jam_selesai']) < strtotime($cekjadwal['jam_mulai'])) {
					$half=$this->formatter->difHalfTime($cekjadwal['jam_mulai'],$cekjadwal['jam_selesai']);
					//toleransi jam masuk
					$tol_s_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],2,'-');
					$tol_e_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],$half,'+');
					//toleransi jam pulang
					$tol_s_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],$half,'-');
					$tol_e_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],14,'+');
					$time=$data['jam'];
					if (strtotime($tol_s_selesai) > strtotime($tol_e_selesai)) {
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$cekjadwal['kode_shift'];
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$cekjadwal['kode_shift'];
							}
						}
					}									
				}											
			}	
			if (!empty($data['jadwal']['jam_mulai']) && !empty($data['jadwal']['jam_selesai'])) {
				$half=$this->formatter->difHalfTime($data['jadwal']['jam_mulai'],$data['jadwal']['jam_selesai']);
				//toleransi jam masuk
				$tol_s_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],2,'-');
				$tol_e_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],$half,'+');
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],14,'+');
				//compare
				$time=$data['jam'];
				$tgl=$data['jadwal']['tanggal'];
				if (strtotime($data['jadwal']['jam_selesai']) < strtotime($data['jadwal']['jam_mulai'])) {
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) >= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}
					}
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}
					}
					
					// $tglx=date('Y-m-d',strtotime($tgl.' +1 day'));
					// if (isset($data_excel[$finger][$tglx])) {
					// 	$timez=min($data_excel[$finger][$tglx]);
					// 	if ($timez >= $tol_s_selesai && $timez <= $tol_e_selesai) {
					// 		$ret['jam_selesai']=$timez;
					// 	}
					// }
					// if (isset($ret['jam_selesai'])) {
					// 	if (empty($ret['jam_selesai'])) {
					// 		$tglx1=date('Y-m-d',strtotime($tgl.' +1 day'));
					// 		if (isset($data_excel[$finger][$tglx1])) {
					// 			$timez1=min($data_excel[$finger][$tglx1]);
					// 			if ($tol_e_selesai < $tol_s_selesai) {
					// 				if ($timez1 <= $tol_s_selesai && $tol_e_selesai >= $timez1) {
					// 					$ret['jam_selesai']=$timez1;
					// 				}
					// 			}
					// 		}
					// 	}
					// }
					if (isset($ret['jam_mulai'])) {
						if (empty($ret['jam_mulai'])) {
							if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
								$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
								if (!$cek) {
									$ret['jam_mulai']=$time;
								}
							}
						}
					}
					//for jadwal 00:00:00
					if (isset($ret['jam_selesai']) && $data['jadwal']['jam_selesai'] == '00:00:00') {
						if (empty($ret['jam_selesai'])) {
							if (strtotime($tol_e_selesai) < strtotime($tol_s_selesai)) {
								if (strtotime($time) >= strtotime($tol_s_selesai)) {
									$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									if (!$cek) {
										$ret['jam_selesai']=$time;
									}
								}
								if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
									$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									if (!$cek) {
										$ret['jam_selesai']=$time;
									}
								}
							}else{
								if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
									$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									if (!$cek) {
										$ret['jam_selesai']=$time;
									}
								}
							}
						}
					}
										
				}else{	
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}						
					}
					if (strtotime($tol_e_selesai) < strtotime($tol_s_selesai)) {
						if (strtotime($time) >= strtotime($tol_s_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
					}	                						
				}
			}
		}
		return $ret;
	}
	public function coreImportPresensiJKB($data)
	{
		$ret=[];
		if (empty($data)) 
			return $ret;
		if (isset($data['kode_shift'])) {
			$tglx2= date('Y-m-d',strtotime($data['tanggal'].' -1 day'));
			$cekjadwal = $this->cekJadwalKerjaIdDate($data['id_karyawan'],$tglx2);
			if (!empty($cekjadwal['jam_mulai']) && !empty($cekjadwal['jam_selesai'])) {
				if (strtotime($cekjadwal['jam_selesai']) < strtotime($cekjadwal['jam_mulai'])) {
					$half=$this->formatter->difHalfTime($cekjadwal['jam_mulai'],$cekjadwal['jam_selesai']);
					//toleransi jam masuk
					$tol_s_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],2,'-');
					$tol_e_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],$half,'+');
					//toleransi jam pulang
					$tol_s_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],$half,'-');
					$tol_e_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],14,'+');
					$time=$data['jam'];
					if (strtotime($tol_s_selesai) > strtotime($tol_e_selesai)) {
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$cekjadwal['kode_shift'];
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$cekjadwal['kode_shift'];
							}
						}
					}									
				}											
			}	
			if (!empty($data['jam_mulai']) && !empty($data['jam_selesai'])) {
				$half=$this->formatter->difHalfTime($data['jam_mulai'],$data['jam_selesai']);
				//toleransi jam masuk
				$tol_s_mulai=$this->formatter->jam($data['jam_mulai'],2,'-');
				$tol_e_mulai=$this->formatter->jam($data['jam_mulai'],$half,'+');
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($data['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($data['jam_selesai'],14,'+');
				//compare
				$time=$data['jam'];
				$tgl=$data['tanggal'];
				if (strtotime($data['jam_selesai']) < strtotime($data['jam_mulai'])) {
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) >= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}
					}
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}
					}
					if (isset($ret['jam_mulai'])) {
						if (empty($ret['jam_mulai'])) {
							if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
								$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
								if (!$cek) {
									$ret['jam_mulai']=$time;
								}
							}
						}
					}
					//for jadwal 00:00:00
					if (isset($ret['jam_selesai']) && $data['jam_selesai'] == '00:00:00') {
						if (empty($ret['jam_selesai'])) {
							if (strtotime($tol_e_selesai) < strtotime($tol_s_selesai)) {
								if (strtotime($time) >= strtotime($tol_s_selesai)) {
									$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									if (!$cek) {
										$ret['jam_selesai']=$time;
									}
								}
								if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
									$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									if (!$cek) {
										$ret['jam_selesai']=$time;
									}
								}
							}else{
								if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
									$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
									if (!$cek) {
										$ret['jam_selesai']=$time;
									}
								}
							}
						}
					}
										
				}else{	
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}						
					}
					if (strtotime($tol_e_selesai) < strtotime($tol_s_selesai)) {
						if (strtotime($time) >= strtotime($tol_s_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
					}	                						
				}
			}
		}
		return $ret;
	}
	public function coreImportPresensi22($data)
	{
		$ret=[];
		if (empty($data)) 
			return $ret;
		if (isset($data['jadwal'])) {
			$toleransi_masuk=3;//jam
			$toleransi_pulang=13;//jam
			$tglx2=date('Y-m-d',strtotime($data['tanggal'].' -1 day'));
			$cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($data['id_karyawan'],$tglx2);
			if (!empty($cekjadwal['jam_mulai']) && !empty($cekjadwal['jam_selesai'])) {
				$half=$this->formatter->difHalfTime($cekjadwal['jam_mulai'],$cekjadwal['jam_selesai']);
				//toleransi jam masuk
				$tol_s_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],$toleransi_masuk,'-');
				$tol_e_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],$half,'+');
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],$toleransi_pulang,'+');
				// echo $tol_s_selesai.' - '.$tol_e_selesai;
				// echo '<br>';
				$time=$data['jam'];
				if (strtotime($cekjadwal['jam_selesai']) < strtotime($cekjadwal['jam_mulai'])) {					
					if (strtotime($tol_s_selesai) > strtotime($tol_e_selesai)) {
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime22($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$cekjadwal['kode_shift'];
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime22($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$cekjadwal['kode_shift'];
							}
						}
					}									
				}
				if (strtotime($cekjadwal['jam_selesai']) > strtotime($time) && ($time >= '00:00:00' && $time <= $tol_e_selesai)) {
					$ret['jam_selesai']=$time;
					$ret['tanggal']=$tglx2;
					$ret['kode_shift']=$cekjadwal['kode_shift'];
				}										
			}	
			if (!empty($data['jadwal']['jam_mulai']) && !empty($data['jadwal']['jam_selesai'])) {
				$half=$this->formatter->difHalfTime($data['jadwal']['jam_mulai'],$data['jadwal']['jam_selesai']);
				//toleransi jam masuk
				$tol_s_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],$toleransi_masuk,'-');
				$tol_e_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],$half,'+');
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],$toleransi_pulang,'+');
				//compare
				$time=$data['jam'];
				$tgl=$data['jadwal']['tanggal'];			
				if (strtotime($data['jadwal']['jam_selesai']) < strtotime($data['jadwal']['jam_mulai'])) {
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) >= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime22($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}
					}
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime22($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							$ret['jam_mulai']=$time;
						}
					}
					if (isset($ret['jam_mulai'])) {
						if (empty($ret['jam_mulai'])) {
							if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
								$cek=$this->checkPresensiTime22($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
								if (!$cek) {
									$ret['jam_mulai']=$time;
								}
							}
						}
					}
					if (isset($ret['jam_selesai']) && $data['jadwal']['jam_selesai'] == '00:00:00') {
						if (empty($ret['jam_selesai'])) {
							if (strtotime($tol_e_selesai) < strtotime($tol_s_selesai)) {
								if (strtotime($time) >= strtotime($tol_s_selesai)) {
									$ret['jam_selesai']=$time;
								}
								if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
									$ret['jam_selesai']=$time;
								}
							}else{
								if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
									$ret['jam_selesai']=$time;
								}
							}
						}
					}		
				}else{	
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						$cek=$this->checkPresensiTime22($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						if (!$cek) {
							if (isset($ret['jam_selesai'])) {
								if ($time == $ret['jam_selesai']) {
									unset($ret['jam_selesai']);
								}
							}
							$ret['jam_mulai']=$time;
							$ret['tanggal']=$tgl;
							$ret['kode_shift']=$data['jadwal']['kode_shift'];
						}						
					}
					if (strtotime($tol_e_selesai) < strtotime($tol_s_selesai)) {
						if (strtotime($time) >= strtotime($tol_s_selesai)) {
							$cek=$this->checkPresensiTime22($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['tanggal']=$tgl;
								$ret['kode_shift']=$data['jadwal']['kode_shift'];
								$ret['jam_selesai']=$time;
							}
						}
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime22($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime22($data['id_karyawan'],$data['tanggal'],$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
							}
						}
					}             						
				}
			}
		}		
		return $ret;
	}
	public function checkPresensiTime22($id,$tgl,$time,$usage)
	{
		$this->db->select('a.jam_mulai,a.jam_selesai');
		$this->db->from('data_presensi AS a');
		$this->db->where('a.tanggal',$tgl);
		$this->db->where('a.id_karyawan',$id);
		if ($usage == 'jam_mulai') {
			$this->db->where("a.jam_mulai > '$time' AND a.jam_mulai IS NOT NULL");
		}elseif ($usage == 'jam_selesai') {
			$this->db->where("a.jam_selesai > '$time' AND a.jam_selesai IS NOT NULL");
		}
		$query=$this->db->get()->row_array();
		return (($query)?true:false);
	}
	public function checkPresensiTime($id,$tgl,$time,$usage)
	{
		$this->db->select('a.jam_mulai,a.jam_selesai');
		$this->db->from('data_presensi AS a');
		$this->db->where('a.tanggal',$tgl);
		$this->db->where('a.id_karyawan',$id);
		if ($usage == 'jam_mulai') {
			$this->db->where("a.jam_mulai < '$time' AND a.jam_mulai IS NOT NULL");
		}elseif ($usage == 'jam_selesai') {
			$this->db->where("a.jam_selesai > '$time' AND a.jam_selesai IS NOT NULL");
		}
		$query=$this->db->get()->row_array();
		return (($query)?true:false);
	}
	public function cekJamPresensi($id,$tgl,$usage)
	{
		$this->db->select('a.jam_mulai,a.jam_selesai');
		$this->db->from('data_presensi AS a');
		$this->db->where('a.tanggal',$tgl);
		$this->db->where('a.id_karyawan',$id);
		if ($usage == 'jam_mulai') {
			$this->db->where("a.jam_mulai IS NOT NULL");
		}elseif ($usage == 'jam_selesai') {
			$this->db->where("a.jam_selesai IS NOT NULL");
		}
		$query=$this->db->get()->row_array();
		return (($query)?true:false);
	}

	//===================================================== FOR CHART ===========================================================	
	public function getEmployeeDateInChart()
	{
		$data=$this->db->query('SELECT YEAR(STR_TO_DATE(emp.tgl_masuk, "%Y-%m-%d")) as tahun,COUNT(emp.nik) as jumlah, COUNT(non.id_karyawan) as jumlah_non FROM karyawan as emp LEFT JOIN data_karyawan_tidak_aktif non ON non.id_karyawan = emp.id_karyawan GROUP BY tahun')->result();
		$pack=[];
		$pack['label']='Data Karyawan Masuk';
		foreach ($data as $d) {
			$pack['tahun'][]=$d->tahun;
			$pack['jumlah'][]=$d->jumlah;
			$pack['jumlah_non'][]=$d->jumlah_non;
		}
		return $pack;
	}
	public function getEmployeeGenderChart()
	{
		$data=$this->db->query('SELECT kelamin,count(*) as jumlah FROM karyawan GROUP BY kelamin')->result();
		$pack=[];
		$pack['label']='Data Karyawan Kelamin';
		foreach ($data as $d) {
			$pack['kelamin'][]=$this->otherfunctions->getGender($d->kelamin);
			$pack['jumlah'][]=$d->jumlah;
		}
		return $pack;
	}
	public function getEmployeeLokerChart()
	{
		$data=$this->db->query('SELECT lok.nama as nama_loker,count(*) as jumlah FROM karyawan emp LEFT JOIN master_loker lok ON lok.kode_loker = emp.loker GROUP BY lok.kode_loker')->result();
		$pack=[];
		$pack['label']='Data Karyawan Lokasi Kerja';
		foreach ($data as $d) {
			$pack['loker'][]=($d->nama_loker)?$d->nama_loker:'Unknown';
			$pack['jumlah'][]=$d->jumlah;
		}
		return $pack;
	}
	public function getEmployeeBagianChart()
	{
		$data=$this->db->query('SELECT bag.nama as nama_bagian,count(*) as jumlah FROM karyawan emp LEFT JOIN master_jabatan jbt ON jbt.kode_jabatan = emp.jabatan LEFT JOIN master_bagian bag ON bag.kode_bagian = jbt.kode_bagian GROUP BY bag.kode_bagian')->result();
		$pack=[];
		$pack['label']='Data Karyawan Bagian';
		foreach ($data as $d) {
			$pack['bagian'][]=($d->nama_bagian)?$d->nama_bagian:'Unknown';
			$pack['jumlah'][]=$d->jumlah;
		}
		return $pack;
	}
	public function getEmpMonth($bulan,$lokasi=null,$tahun=null)
	{
		$this->db->select('tgl_masuk');
		$this->db->from('karyawan');
		$this->db->where('MONTH(tgl_masuk)',$bulan);
		$this->db->where('status_emp',1);
		if($tahun!=null){
			$this->db->where('YEAR(tgl_masuk)',$tahun);
		}
		if($lokasi!='all'){
			$this->db->where('loker',$lokasi);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getEmpNonAktifMonth($bulan,$lokasi=null,$tahun=null)
	{
		$this->db->select('non.id_karyawan,kar.nik as nik_kar');
		$this->db->from('data_karyawan_tidak_aktif AS non');
		$this->db->join('karyawan AS kar', 'kar.id_karyawan = non.id_karyawan', 'left'); 
		$this->db->where('MONTH(non.tgl_berlaku)',$bulan); 
		$this->db->where('kar.status_emp',0); 
		if($tahun!=null){
			$this->db->where('YEAR(non.tgl_berlaku)',$tahun);
		}
		if($lokasi!=null){
			$this->db->where('kar.loker',$lokasi);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getstatusEmp($status,$lokasi=null)
	{
		$this->db->select('status_karyawan');
		$this->db->from('karyawan');
		$this->db->where('status_karyawan',$status);
		$this->db->where('status_emp',1);
		if($lokasi!=null){
			$this->db->where('loker',$lokasi);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getJenisKelaminEmp($kelamin,$lokasi=null)
	{
		$this->db->select('kelamin');
		$this->db->from('karyawan');
		$this->db->where('kelamin',$kelamin);
		$this->db->where('status_emp',1);
		if($lokasi!=null){
			$this->db->where('loker',$lokasi);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}	
	public function getAgamaEmp($agama,$lokasi=null)
	{
		$this->db->select('agama');
		$this->db->from('karyawan');
		$this->db->where('agama',$agama);
		$this->db->where('status_emp',1);
		if($lokasi!=null){
			$this->db->where('loker',$lokasi);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getEmpIzin($bulan,$jenis,$lokasi=null,$tahun=null)
	{
		$this->db->select('ij.tgl_mulai');
		$this->db->from('data_izin_cuti_karyawan as ij');
		$this->db->where('MONTH(ij.tgl_mulai)',$bulan);
		$this->db->join('karyawan AS kar', 'kar.id_karyawan = ij.id_karyawan', 'left');
		$this->db->join('master_izin AS izn', 'izn.kode_master_izin = ij.jenis', 'left');
		$this->db->where('izn.jenis',$jenis);
		$this->db->where('kar.status_emp',1);
		if($tahun!=null){
			$this->db->where('YEAR(ij.tgl_mulai)',$tahun);
		}
		if($lokasi!=null){
			$this->db->where('kar.loker',$lokasi);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getEmpIzinChart($bulan,$jenis,$lokasi=null,$tahun=null,$bagian=null)
	{
		$this->db->select('ij.tgl_mulai');
		$this->db->from('data_izin_cuti_karyawan as ij');
		$this->db->where('MONTH(ij.tgl_mulai)',$bulan);
		$this->db->join('karyawan AS kar', 'kar.id_karyawan = ij.id_karyawan', 'left');
		$this->db->join('master_izin AS izn', 'izn.kode_master_izin = ij.jenis', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = kar.jabatan', 'left'); 
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jab.kode_bagian', 'left'); 
		$this->db->where('ij.jenis',$jenis);
		$this->db->where('kar.status_emp',1);
		if($tahun!=null){
			$this->db->where('YEAR(ij.tgl_mulai)',$tahun);
		}
		if($lokasi!=null){
			$this->db->where('kar.loker',$lokasi);
		}
		if($bagian!=null){
			$this->db->where('bag.kode_bagian',$bagian);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getEmpPeringatan($bulan,$status,$lokasi=null,$tahun=null)
	{
		$this->db->select('dis.tgl_berlaku');
		$this->db->from('data_peringatan_karyawan as dis');
		$this->db->where('MONTH(dis.tgl_berlaku)',$bulan);
		$this->db->join('karyawan AS kar', 'kar.id_karyawan = dis.id_karyawan', 'left'); 
		$this->db->where('dis.status_baru',$status);
		$this->db->where('kar.status_emp',1);
		if($tahun!=null){
			$this->db->where('YEAR(dis.tgl_berlaku)',$tahun);
		}
		if($lokasi!=null){
			$this->db->where('kar.loker',$lokasi);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getEmpIzinBagian($idkar,$kode,$mulai,$selesai)
	{
		$this->db->select('a.id_karyawan');
		$this->db->from('data_izin_cuti_karyawan as a');
		$this->db->join('karyawan AS kar', 'kar.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->where('a.id_karyawan',$idkar);
		$this->db->where('a.jenis',$kode);
		$this->db->where("a.tgl_mulai BETWEEN '".$mulai."' AND '".$selesai."'");
		$this->db->where('kar.status_emp',1);
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getBagianEmp($lokasi=null)
	{
		$this->db->select('bag.nama as nama_bagian, count(kar.nik) as jumlah');
		$this->db->from('karyawan as kar');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = kar.jabatan', 'left'); 
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = jab.kode_bagian', 'left'); 
		$this->db->where('bag.kode_bagian !=','BAG002');
		$this->db->group_by('bag.kode_bagian');
		if($lokasi!=null){
			$this->db->where('kar.loker',$lokasi);
		}
		$data=$this->db->get()->result();
		$pack=[];
		$pack['label']='Data Karyawan Bagian';
		foreach ($data as $d) {
			$pack['bagian'][]=($d->nama_bagian)?$d->nama_bagian:'Unknown';
			$pack['jumlah'][]=$d->jumlah;
		}
		return $pack;
	}
	public function getPendidikanEmp($pendidikan,$lokasi=null)
	{
		$this->db->select('nik');
		$this->db->from('karyawan');
		$this->db->where('pendidikan',$pendidikan);
		if($lokasi!=null){
			$this->db->where('loker',$lokasi);
		}
		$query=$this->db->get()->num_rows();
		return $query;
	}
	public function getListPinjaman($where = null, $status = 0)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, prd.nama as nama_periode');
		$this->db->from('data_pinjaman as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('data_periode_penggajian as prd','prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkPinjamanCode($code)
	{
		return $this->model_global->checkCode($code,'data_pinjaman','kode_pinjaman');
	}
// =========================================== DATA PAYROLL LAMA  For BACKUP DATA ========================================


	// public function checkTemporariPresensiDate($id,$date)
	// {
	// 	if (empty($id) || empty($date))
	// 		return false;
	// 	$this->db->where('id_karyawan',$id); 
	// 	$this->db->where('tanggal',$date);
	// 	$query=$this->db->get('temporari_data_presensi')->num_rows();
	// 	if ($query > 0) {
	// 		return true;
	// 	}else{
	// 		return false;
	// 	}
	// }
	// public function checkTemporariPresensiJamSelesai($id,$date)
	// {
	// 	if (empty($id) || empty($date))
	// 		return false;
	// 	$this->db->where('id_karyawan',$id); 
	// 	$this->db->where('tanggal',$date);
	// 	$query=$this->db->get('temporari_data_presensi')->row_array();
	// 	return $query['jam_selesai'];
	// }
	// public function cekTemporariTanggal($tgl_awal,$tgl_akhir)
	// {
	// 	if(empty($tgl_awal) || empty($tgl_akhir))
	// 		return null;
	// 	$this->db->select('*');
	// 	$this->db->from('temporari_data_presensi');
	// 	$this->db->where('tanggal >=',$tgl_awal);
	// 	$this->db->where('tanggal <=',$tgl_akhir);
	// 	$query=$this->db->get()->result();
	// 	return $query;
	// }
	// public function cekDataPresensiIdK($id_karyawan,$tanggal){
	// 	if(empty($id_karyawan) || empty($tanggal))
	// 		return null;
	// 	$this->db->where('id_karyawan',$id_karyawan);
	// 	$this->db->where('tanggal',$tanggal);
	// 	$query=$this->db->get('data_presensi')->result();
	// 	return $query;
	// }
	// public function cekDataTemporari($id_karyawan,$tanggal){
	// 	if(empty($id_karyawan) || empty($tanggal))
	// 		return null;
	// 	$query=$this->db->get_where('temporari_data_presensi',['id_karyawan'=>$id_karyawan,'tanggal'=>$tanggal])->row_array();
	// 	return $query;
	// }
//======================================================= DATA PAYROLL ======================================================
	
	// public function getDataPayroll($where = null, $status = 0)
	// {
	// 	$this->db->select('
	// 		a.*,
	// 		b.nama as nama_buat, 
	// 		c.nama as nama_update, 
	// 		emp.nama as nama_karyawan, 
	// 		emp.rekening, 
	// 		jbt.nama as nama_jabatan, 
	// 		bag.nama as nama_bagian, 
	// 		lok.nama as nama_loker, 
	// 		grd.nama as nama_grade, 
	// 		prd.nama as nama_periode, 
	// 		prd.tgl_mulai, 
	// 		prd.tgl_selesai, 
	// 		msp.nama as nama_sistem_penggajian, 
	// 		mtu.nama as nama_umk, 
	// 		mtu.tarif');
	// 	$this->db->from('data_penggajian as a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
	// 	$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
	// 	$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
	// 	$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
	// 	$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
	// 	$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
	// 	$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
	// 	$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
	// 	$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');

	// 	if($status == 1){ $this->db->where('a.status','1'); } 						/*status*/

	// 	if(!empty($where)){ $this->db->where($where); } 										/*where*/

	// 	$this->db->order_by('a.update_date','desc');
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }
	// public function getEmployeeWhere($where,$active = 0)
	// {
	// 	$this->db->select('
	// 		emp.*,
	// 		loker.nama as nama_loker,
	// 		jbt.nama as nama_jabatan,
	// 		bag.nama as bagian,
	// 		bag.id_bagian,
	// 		bag.kode_bagian,
	// 		b.nama as nama_buat,
	// 		c.nama as nama_update,
	// 		grd.nama as nama_grade,
	// 		grd.gapok as gaji_grade,
	// 		d.nama as nama_loker_grade');
	// 	$this->db->from('karyawan AS emp');
	// 	$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
	// 	$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
	// 	$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
	// 	$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
	// 	$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
	// 	$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
	// 	if(!empty($where)){
	// 		$this->db->where($where);
	// 	}
	// 	if($active == 1){
	// 		$this->db->where('status_emp',1);
	// 	}
		
	// 	$query=$this->db->get()->result();
	// 	return $query;
	// }
	// public function getListJdwalKerjaWhere($where)
	// {
	// 	$this->db->select('jk.*,emp.nama as nama_karyawan, emp.nik, emp.id_karyawan, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, b.nama as nama_buat, c.nama as nama_update');
	// 	$this->db->from('data_jadwal_kerja AS jk');
	// 	$this->db->join('karyawan AS emp', 'emp.id_karyawan = jk.id_karyawan', 'left'); 
	// 	$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
	// 	$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
	// 	$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
	// 	$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left'); 
	// 	$this->db->join('admin AS b', 'b.id_admin = jk.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = jk.update_by', 'left'); 
	// 	if(isset($where)){ $this->db->where($where); }
	// 	$query=$this->db->get()->result();
	// 	return $query;
	// }
	// public function getDataPayrollTunjangan($where = null)
	// {
	// 	$this->db->select('
	// 		a.*,
	// 		prd.nama as nama_periode, 
	// 		prd.tgl_mulai, 
	// 		prd.tgl_selesai, 
	// 		msp.nama as nama_sistem_penggajian, 
	// 		mtu.nama as nama_umk, 
	// 		mtu.tarif');
	// 	$this->db->from('data_penggajian_tunjangan as a');
	// 	$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
	// 	$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
	// 	$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
	// 	if(!empty($where)){ $this->db->where($where); } 										/*where*/
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }
	// public function getDataPayrollSingle($where = null, $group_by = null)
	// {
	// 	if(!empty($where)){ $this->db->where($where); } 
	// 	if(!empty($group_by)){ $this->db->group_by($group_by); }				/*group by*/
	// 	$this->db->order_by('update_date','desc');
	// 	$query = $this->db->get('data_penggajian')->result();
	// 	return $query;
	// }
	// public function getDataPayrollTunjanganSingle($where = null, $group_by = null)
	// {
	// 	if(!empty($where)){ $this->db->where($where); } 						/*where*/
	// 	if(!empty($group_by)){ $this->db->group_by($group_by); }				/*group by*/
	// 	$query = $this->db->get('data_penggajian_tunjangan')->result();
	// 	return $query;
	// }

	// public function getDataLogPayroll($where = null, $status = 0, $group_by = null)
	// {
	// 	$this->db->select('
	// 		a.*,
	// 		b.nama as nama_buat, 
	// 		c.nama as nama_update, 
	// 		emp.nama as nama_karyawan, 
	// 		emp.rekening, 
	// 		jbt.nama as nama_jabatan, 
	// 		bag.nama as nama_bagian, 
	// 		lok.nama as nama_loker, 
	// 		grd.nama as nama_grade, 
	// 		prd.nama as nama_periode, 
	// 		prd.tgl_mulai, 
	// 		prd.tgl_selesai, 
	// 		msp.nama as nama_sistem_penggajian, 
	// 		mtu.nama as nama_umk, 
	// 		mtu.tarif');
	// 	$this->db->from('log_data_penggajian as a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
	// 	$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
	// 	$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
	// 	$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
	// 	$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
	// 	$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
	// 	$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
	// 	$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
	// 	$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');

	// 	if($status == 1){ $this->db->where('a.status','1'); } 						/*status*/

	// 	if(!empty($where)){ $this->db->where($where); } 										/*where*/
	// 	if(!empty($group_by)){ $this->db->group_by($group_by); } 										/*groupby*/

	// 	$this->db->order_by('a.update_date','desc');
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }

	// public function getDataLogPayrollTunjangan($where = null,$sistem_penggajian = 'BULANAN')
	// {
	// 	$this->db->select('
	// 		a.*,
	// 		prd.nama as nama_periode, 
	// 		prd.tgl_mulai, 
	// 		prd.tgl_selesai, 
	// 		msp.nama as nama_sistem_penggajian, 
	// 		mtu.nama as nama_umk, 
	// 		mtu.tarif');
	// 	$this->db->from('log_data_penggajian_tunjangan as a');
	// 	if($sistem_penggajian == 'BULANAN'){
	// 		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
	// 	}else{
	// 		$this->db->join('data_periode_penggajian_harian AS prd', 'prd.kode_periode_penggajian_harian = a.kode_periode_penggajian', 'left');
	// 	}
	// 	$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
	// 	$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
	// 	if(!empty($where)){ $this->db->where($where); } 	/*where*/
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }
	// //--------------------------------------------------------------------------------------------------------------//
	// //Data Penggajian Lembur
	// public function getDataPayrollLeburSingle($where = null, $group_by = null)
	// {
	// 	if(!empty($where)){ $this->db->where($where); } 
	// 	if(!empty($group_by)){ $this->db->group_by($group_by); }				/*group by*/
	// 	$this->db->order_by('update_date','desc');
	// 	$query = $this->db->get('data_penggajian_lembur')->result();
	// 	return $query;
	// }

	// public function getDataPayrollLembur($where = null, $status = 0)
	// {
	// 	$this->db->select('
	// 		a.*,
	// 		b.nama as nama_buat, 
	// 		c.nama as nama_update, 
	// 		emp.nama as nama_karyawan, 
	// 		emp.rekening, 
	// 		emp.tgl_masuk, 
	// 		emp.nik, 
	// 		jbt.nama as nama_jabatan, 
	// 		bag.nama as nama_bagian, 
	// 		lok.nama as nama_loker, 
	// 		grd.nama as nama_grade, 
	// 		prd.nama as nama_periode, 
	// 		prd.tgl_mulai, 
	// 		prd.tgl_selesai, 
	// 		msp.nama as nama_sistem_penggajian, 
	// 		mtu.nama as nama_umk, 
	// 		mtu.tarif');
	// 	$this->db->from('data_penggajian_lembur as a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
	// 	$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
	// 	$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
	// 	$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
	// 	$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
	// 	$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
	// 	$this->db->join('data_periode_lembur AS prd', 'prd.kode_periode_lembur = a.kode_periode', 'left');
	// 	$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
	// 	$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');

	// 	if($status == 1){ $this->db->where('a.status','1'); } 						/*status*/

	// 	if(!empty($where)){ $this->db->where($where); } 										/*where*/

	// 	$this->db->order_by('a.update_date','desc');
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }

	// public function getDataLogPayrollLembur($where = null, $status = 0, $group_by = null)
	// {
	// 	$this->db->select('
	// 		a.*,
	// 		b.nama as nama_buat, 
	// 		c.nama as nama_update, 
	// 		emp.nama as nama_karyawan, 
	// 		emp.rekening, 
	// 		jbt.nama as nama_jabatan, 
	// 		bag.nama as nama_bagian, 
	// 		lok.nama as nama_loker, 
	// 		grd.nama as nama_grade, 
	// 		prd.nama as nama_periode, 
	// 		prd.tgl_mulai, 
	// 		prd.tgl_selesai, 
	// 		msp.nama as nama_sistem_penggajian, 
	// 		mtu.nama as nama_umk, 
	// 		mtu.tarif');
	// 	$this->db->from('log_data_penggajian_lembur as a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
	// 	$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
	// 	$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
	// 	$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
	// 	$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
	// 	$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
	// 	$this->db->join('data_periode_lembur AS prd', 'prd.kode_periode_lembur = a.kode_periode', 'left');
	// 	$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
	// 	$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');

	// 	if($status == 1){ $this->db->where('a.status','1'); } 						/*status*/

	// 	if(!empty($where)){ $this->db->where($where); } 										/*where*/
	// 	if(!empty($group_by)){ $this->db->group_by($group_by); } 										/*groupby*/

	// 	$this->db->order_by('a.update_date','desc');
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }
	// //--------------------------------------------------------------------------------------------------------------//
	// //Data BPJS
	// public function getListBpjsEmp($where = null, $status = 0)
	// {
	// 	$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, emp.nama as nama_karyawan, emp.nik, jbt.nama as nama_jabatan');
	// 	$this->db->from('data_bpjs as a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
	// 	$this->db->join('karyawan as emp','emp.id_karyawan = a.id_karyawan','left');
	// 	$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
	// 	if($status == 1){ $this->db->where('a.status','1'); } 						/*status*/
	// 	if(!empty($where)){ $this->db->where($where); } 										/*where*/
	// 	$this->db->order_by('a.status','desc');
	// 	$this->db->order_by('a.update_date','desc');
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }
	// public function checkBpjsEmpCode($code)
	// {
	// 	return $this->model_global->checkCode($code,'data_bpjs','kode_k_bpjs');
	// }
	// //--------------------------------------------------------------------------------------------------------------//
	// //Data Pendukung Lain
	// public function getListDataPendukungLain($where = null, $status = 0, $group_by = null, $order_by = null)
	// {
	// 	if(empty($order_by)){ $order_by = "a.update_date desc"; }
	// 	$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, emp.nama as nama_karyawan, emp.nik, jbt.nama as nama_jabatan, prd.nama as nama_periode');
	// 	$this->db->from('data_pendukung_lain as a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
	// 	$this->db->join('karyawan as emp','emp.id_karyawan = a.id_karyawan','left');
	// 	$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
	// 	$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
	// 	if($status == 1){ $this->db->where('a.status','1'); } 								/*status*/
	// 	if(!empty($where)){ $this->db->where($where); } 										/*where*/
	// 	if(!empty($group_by)){ $this->db->group_by($group_by); } 								/*group_by*/
	// 	$this->db->order_by($order_by);																/*order_by*/
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }
	// public function checkPendukungLainCode($code)
	// {
	// 	return $this->model_global->checkCode($code,'data_pendukung_lain','kode_pen_lain');
	// }

	// //--------------------------------------------------------------------------------------------------------------//
	// //Data Pendukung Lain
	// public function getListDataPenggajianPph($where = null, $status = 0, $group_by = null, $order_by = null)
	// {
	// 	if(empty($order_by)){ $order_by = "a.update_date desc"; }
	// 	$this->db->select('
	// 		a.*,
	// 		b.nama as nama_buat,
	// 		c.nama as nama_update,
	// 		jbt.nama as nama_jabatan,
	// 		bag.nama as nama_bagian,
	// 		lok.nama as nama_loker,
	// 		grd.nama as nama_grade,
	// 		msp.nama as nama_sistem_penggajian,
	// 		prd.nama as nama_periode,
	// 		prd.tgl_mulai as tgl_mulai_periode,
	// 		prd.tgl_selesai as tgl_selesai_periode,
	// 		');
	// 	$this->db->from('data_penggajian_pph as a');
	// 	$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
	// 	$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
	// 	$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
	// 	$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
	// 	$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
	// 	$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
	// 	$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = a.kode_master_penggajian', 'left');
	// 	$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
	// 	if($status == 1){ $this->db->where('a.status','1'); } 								/*status*/
	// 	if(!empty($where)){ $this->db->where($where); } 										/*where*/
	// 	if(!empty($group_by)){ $this->db->group_by($group_by); } 								/*group_by*/
	// 	$this->db->order_by($order_by);																/*order_by*/
	// 	$query = $this->db->get()->result();
	// 	return $query;
	// }
	public function getIDFromBagianKar($kode_bagian)
	{
		$this->db->select('emp.id_karyawan as id_karyawan, emp.nik as nik');
		$this->db->from('karyawan as emp');
		$this->db->join('master_jabatan as jab','jab.kode_jabatan = emp.jabatan','left');
		$this->db->where('jab.kode_bagian',$kode_bagian);
		return $this->db->get()->result();
	}
	public function cekIzinCutiPresensi($id_k,$tgl)
	{
		$this->db->select('*');
		$this->db->from('data_izin_cuti_karyawan');
		$this->db->where('id_karyawan',$id_k);
		// $this->db->where('tgl_mulai <='.$tgl.' AND tgl_selesai >='. $tgl);
		$this->db->where('tgl_mulai <=',$tgl);
		$this->db->where('tgl_selesai >=',$tgl);
		return $this->db->get()->row_array();
	}
	public function cekHariLiburPresensi($tgl)
	{
		$this->db->select('*');
		$this->db->from('master_hari_libur');
		$this->db->where('tanggal',$tgl);
		return $this->db->get()->row_array();
	}
	public function cekLemburNoLembur($kode)
	{
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('no_lembur',$kode);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function cekDataLemburPresensi($id_k,$tgl)
	{
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('id_karyawan',$id_k);
		// $this->db->where('tgl_mulai <='.$tgl.' AND tgl_selesai >='. $tgl);
		$this->db->where('tgl_mulai',$tgl);
		// $this->db->where('validasi',1);
		// $this->db->where('tgl_mulai <=',$tgl);
		// $this->db->where('tgl_selesai >=',$tgl);
		// $this->db->where('tgl_selesai >=',$tgl);
		return $this->db->get()->row_array();
	}
	public function cekDataPerDinPresensi($id_k,$tgl)
	{
		if (!$tgl || !$id_k)
			return null;
		$this->db->select('*');
		$this->db->from('data_perjalanan_dinas');
		$this->db->where('id_karyawan',$id_k);
		// $this->db->where('tgl_berangkat <='.$tgl.' AND tgl_sampai >='. $tgl);
		$this->db->where('tgl_berangkat <=',$tgl);
		$this->db->where('tgl_sampai >=',$tgl);
		return $this->db->get()->row_array();
	}
	public function getJenisIzinKodeIzin($kode)
	{
		$this->db->select('jenis');
		$this->db->from('data_izin_cuti_karyawan');
		$this->db->where('kode_izin_cuti',$kode);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getIzinCutiPresensi($kode)
	{
		$this->db->select('*');
		$this->db->from('master_izin');
		$this->db->where('kode_master_izin',$kode);
		$query=$this->db->get()->row_array();
		return $query;
	}
    public function getIdShift($d,$m,$y,$id_karyawan){
    	
        $col=$this->CI->formatter->getCols2($d);
		$this->db->select('jk.'.$col.' as shift,jk.id_karyawan as id_karyawan, kar.id_finger as id_finger,kar.nik as nik,sh.jam_mulai as jam_mulai, sh.jam_selesai as jam_selesai');
		$this->db->from('data_jadwal_kerja as jk');
		$this->db->join('master_shift AS sh', 'sh.kode_master_shift = jk.'.$col, 'left');
		$this->db->join('karyawan AS kar', 'kar.id_karyawan = jk.id_karyawan', 'left');
		$this->db->where('jk.tahun',$y);
		$this->db->where('jk.bulan',$m);
		$this->db->where('jk.id_karyawan',$id_karyawan);
		$query=$this->db->get()->row_array();
		return $query;
    }
    public function getIdShiftNoKar($d,$m,$y){
    	
        $col=$this->CI->formatter->getCols2($d);
		$this->db->select('jk.'.$col.' as shift,jk.id_karyawan as id_karyawan, kar.id_finger as id_finger,kar.nik as nik,sh.jam_mulai as jam_mulai, sh.jam_selesai as jam_selesai');
		$this->db->from('data_jadwal_kerja as jk');
		$this->db->join('master_shift AS sh', 'sh.kode_master_shift = jk.'.$col, 'left');
		$this->db->join('karyawan AS kar', 'kar.id_karyawan = jk.id_karyawan', 'left');
		$this->db->where('jk.tahun',$y);
		$this->db->where('jk.bulan',$m);
		$query=$this->db->get()->result();
		return $query;
    }
    public function getKaryawanBagianJoin($kode, $status = false)
    {
        $this->db->select('emp.nama as nama_kar,emp.id_karyawan as id_kar,bag.nama as bagian,emp.foto as foto,emp.tgl_masuk as tgl_masuk,emp.nik as nik,jab.nama as nama_jabatan,bag.nama as nama_bagian,lok.nama as loker,bag.kode_bagian as kode_bagian');
        $this->db->from('karyawan emp');
        $this->db->join('master_jabatan jab', 'jab.kode_jabatan = emp.jabatan','left');
        $this->db->join('master_bagian bag', 'bag.kode_bagian = jab.kode_bagian','left');
        $this->db->join('master_loker lok', 'lok.kode_loker = emp.loker','left');
        $this->db->where('bag.kode_bagian', $kode);
		if($status){
        	$this->db->where('emp.status_emp', '1');
		}
		$query = $this->db->get()->result();
		return $query;
    }
    public function getKaryawanBagian($kode_bagian)
    {
        $pack=[];
			$kar=$this->getKaryawanBagianJoin($kode_bagian);
			if (isset($kar)) {
				foreach ($kar as $k) {
					$pack[]=$k->id_kar;
				}
			}
        return $pack;
    }
	public function checkPresensiDateImport($id,$date)
	{
		if (empty($id) || empty($date))
			return false;
		$this->db->select('*');
		$this->db->from('data_presensi');
		$this->db->where('id_karyawan',$id); 
		$this->db->where('tanggal',$date);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function cekIzinCutiIdDate($idk,$date)
	{
		$this->db->select('*');
		$this->db->from('data_izin_cuti_karyawan');
		$this->db->where('id_karyawan',$idk);
		$this->db->where('tgl_mulai',$date);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getPeriodePenggajianKar($idk)
	{
		$this->db->select('*');
		$this->db->from('karyawan');
		$this->db->where('id_karyawan',$idk);
		$query=$this->db->get()->row_array();
		return $query;
	}
	//============================================================ FOR PAYROLL ===========================================================
	public function getSelectEmployeeKpi($kode)
	{
		$query=$this->getEmployeeJabatan($kode,true,true);
		// echo '<pre>';
		// print_r($query);
		$pack=[];
		if(!empty($query)){
			foreach ($query as $q) {
				$pack[$q->id_karyawan]=$q->nama;
			}
		}
		return $pack;
	}
	public function getEmployeeAtasan($id)
	{
		$return = $this->getEmployeeId($id,true);
		$res = [];
		if(isset($return)){
			$job_leader=$this->model_master->getAtasan($return['jabatan']);
			$leader=$this->getEmployeeJabatan($job_leader, true, true);
			if (count($leader) > 0) {
				foreach ($leader as $k) {
					array_push($res, $k->id_karyawan);
				}
			}
		}
		$res=array_values(array_unique($res));
		return $res;
	}
	public function getEmployeeBawahan($id)
	{
		$return = $this->getEmployeeId($id,true);
		$res = [];
		if(count($return)>0){
			$job_staff=$this->model_master->getBawahan($return['jabatan']);
			if (count($job_staff) > 0) {
				foreach ($job_staff as $j_s) {
					$staff=$this->getEmployeeJabatan($j_s->kode_jabatan, true, true);
					if (count($staff) > 0) {
						foreach ($staff as $k) {
							array_push($res, $k->id_karyawan);
						}
					}
				}
			}
		}
		$res=array_values(array_unique($res));
		return $res;
	}
	public function getEmployeeRekan($id)
	{
		$return = $this->getEmployeeId($id,true);
		$res = [];
		if(count($return)>0){
			$collage=$this->getEmployeeBagian($return['kode_bagian'], true, true);
			$job_leader=$this->model_master->getAtasan($return['jabatan']);
			$leader=$this->getEmployeeJabatan($job_leader, true, true);
			if (count($collage) > 0) {
				foreach ($collage as $k) {
					if ($k->id_karyawan != $id) {
						if (count($leader) > 0) {
							foreach ($leader as $lead) {
								if($lead->id_karyawan != $k->id_karyawan){
									array_push($res, $k->id_karyawan);
								}
							}
						}else{
							array_push($res, $k->id_karyawan);
						} 
					}
				}
			}
		}
		$res=array_values(array_unique($res));
		return $res;
	}
	// public function getEmployeeRekan($id)
	// {
	// 	$return = $this->getEmployeeId($id);
	// 	$res = [];
	// 	if(count($return)>0){
	// 		$collage=$this->getEmployeeBagian($return['kode_bagian'], false, true);
	// 		if (count($collage) > 0) {
	// 			foreach ($collage as $k) {
	// 				if ($k->id_karyawan != $id) {
	// 					array_push($res, $k->id_karyawan);    
	// 				}
	// 			}
	// 		}
	// 	}
	// 	$res=array_values(array_unique($res));
	// 	return $res;
	// }
	// public function getEmployeeRekan($id)
	// {
	// 	$return = $this->getEmployeeId($id);
	// 	$res = [];
	// 	if(count($return)>0){
	// 		$collage=$this->getEmployeeJabatan($return['jabatan'], false, true);
	// 		if (count($collage) > 0) {
	// 			foreach ($collage as $k) {
	// 				if ($k->id_karyawan != $id) {
	// 					array_push($res, $k->id_karyawan);    
	// 				}
	// 			}
	// 		}
	// 	}
	// 	$res=array_values(array_unique($res));
	// 	return $res;
	// }
	//========================================================= DATA NON KARYAWAN ========================================================//
	//====================================================================================================================================//
	// Data Non Karyawan
	public function getListNonKaryawan($where=null,$row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_jenis');
		$this->db->from('data_non_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('pph_penerima_pajak AS d', 'd.kode_pajak = a.jenis', 'left');
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
	public function getListTransaksiNonKaryawanX($where=null,$row=false,$group=null)
	{
		$this->db->select('a.*,(select count(*) from transaksi_non_karyawan cnt where cnt.id_non = a.id_non) as jum,
		b.nama as nama_buat, c.nama as nama_update,
		non.nik,non.nama as nama_non,non.no_telp,non.alamat,non.status_pajak,non.npwp,non.keterangan as non_keterangan,non.jenis as jenis,non.perhitungan_pajak,pph.nama as jenis_pajak,
		d.nama as nama_mengetahui,
		e.nama as jbt_mengetahui,
		f.nama as nama_menyetujui,
		g.nama as jbt_menyetujui
		');
		$this->db->from('transaksi_non_karyawan AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('data_non_karyawan AS non', 'non.id_non = a.id_non', 'left');
		$this->db->join('pph_penerima_pajak AS pph', 'pph.kode_pajak = non.jenis', 'left');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS e', 'e.kode_jabatan = d.jabatan', 'left');
		$this->db->join('karyawan AS f', 'f.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS g', 'g.kode_jabatan = f.jabatan', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($group)){
			$this->db->group_by($group);
		}
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListTransaksiNonKaryawan($where=null,$row=false,$group=null)
	{ 
		// $sc=" SELECT a.*, (select count(*) from transaksi_non_karyawan cnt where cnt.id_non = a.id_non) as jum,
		$sc=" SELECT a.*, b.nama as nama_buat, c.nama as nama_update, non.nik, non.nama as nama_non, non.no_telp, non.alamat, non.status_pajak, non.perhitungan_pajak, non.npwp, non.keterangan as non_keterangan, non.jenis as jenis, pph.nama as jenis_pajak, d.nama as nama_mengetahui, e.nama as jbt_mengetahui, f.nama as nama_menyetujui, g.nama as jbt_menyetujui
		FROM transaksi_non_karyawan AS a 
		LEFT JOIN admin AS b ON b.id_admin = a.create_by 
		LEFT JOIN admin AS c ON c.id_admin = a.update_by 
		LEFT JOIN data_non_karyawan AS non ON non.id_non = a.id_non 
		LEFT JOIN pph_penerima_pajak AS pph ON pph.kode_pajak = non.jenis 
		LEFT JOIN karyawan AS d ON d.id_karyawan = a.mengetahui 
		LEFT JOIN master_jabatan AS e ON e.kode_jabatan = d.jabatan 
		LEFT JOIN karyawan AS f ON f.id_karyawan = a.menyetujui 
		LEFT JOIN master_jabatan AS g ON g.kode_jabatan = f.jabatan ORDER BY a.update_date DESC";
		// LEFT JOIN master_jabatan AS g ON g.kode_jabatan = f.jabatan GROUP BY a.id_non";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getListLemburMax($limit)
	{
		// $this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		// $this->db->from('data_lembur AS a');
		// $this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		// $this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		// $this->db->order_by('id_data_lembur','DESC');
		// $this->db->limit($limit,1);
		// $query=$this->db->get()->row_array();
		// return $query;
		$sc="SELECT * FROM data_lembur ORDER BY id_data_lembur DESC LIMIT $limit, 1";
		$query=$this->db->query($sc)->row_array();
		return $query;
	}
	public function getListKaryawanSelect2($where=null)
	{
		$filter=$this->model_global->getFilterbyBagian(((isset($where['bagian']))?$where['bagian']:null));
		$this->db->select('
			emp.nik,emp.nama,emp.id_karyawan,
			loker.nama as nama_loker,
			jbt.id_jabatan,
			jbt.nama as nama_jabatan,
			bag.kode_bagian,
			bag.nama as bagian,
		');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_loker AS loker', 'loker.kode_loker = emp.loker', 'left');		
		$this->db->join('master_level_jabatan AS lvj', 'lvj.kode_level_jabatan = jbt.kode_level', 'left');	
		$this->db->join('master_level_struktur AS lvst', 'lvst.kode_level_struktur = lvj.kode_level_struktur', 'left');	
		if (isset($where['id_atasan_langsung'])) {
			if (!empty($where['id_atasan_langsung'])) {
				$this->db->join('karyawan AS atasan', 'atasan.jabatan = jbt.atasan', 'left');
				$this->db->where('(atasan.id_karyawan = '.$where['id_atasan_langsung'].' OR emp.id_karyawan = '.$where['id_atasan_langsung'].')');
			}
		}
		if (isset($where['param'])) {
			if (!empty($where['param'])) {
				if ($where['param'] == 'nik' && isset($where['nik'])) {
					$this->db->where('emp.nik',$where['nik']);
				}
			}
		}
		if (isset($where['unit'])) {
			if (!empty($where['unit'])) {
				$this->db->where('loker.kode_loker',$where['unit']);
			}
		}
		if (isset($where['bulan'])) {
			if (!empty($where['bulan'])) {
				$this->db->where('MONTH(emp.tgl_masuk)',$where['bulan']);
			}
		}
		if (isset($where['tahun'])) {
			if (!empty($where['tahun'])) {
				$this->db->where('YEAR(emp.tgl_masuk)',$where['tahun']);
			}
		}
		if (isset($where['aktif'])) {
			if (!empty($where['aktif'])) {
				$this->db->where('emp.status_emp',1);
			}
		}
		if (isset($where['regu'])) {
			if (!empty($where['regu'])) {
				$this->db->where('emp.regu',$where['regu']);
			}
		}
		if (isset($where['level_up'])) {
			if (!empty($where['level_up'])) {
				$sqc=null;
				if (isset($where['sequence_struktur'])) {
					if (!empty($where['sequence_struktur'])) {
						$sqc=$where['sequence_struktur'];
					}
				}
				if ($where['level_up'] == 'up' && !is_null($sqc)) {
					$this->db->where("lvst.squence <= ".$sqc);
				}elseif($where['level_up']=='down' && !is_null($sqc)){
					$this->db->where("lvst.squence >= ".$sqc);
				}
			}
		}
		if (!empty($filter) && !isset($where['bypass'])){
			$this->db->where($filter);
		}
		$this->db->order_by('emp.nama','ASC');
		$query=$this->db->get()->result();
		return $query;
	}
}
