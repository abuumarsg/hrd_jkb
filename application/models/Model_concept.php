<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	/**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Model Concept
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

class Model_concept extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->date=$this->otherfunctions->getDateNow();		
		$this->max_range=$this->otherfunctions->poin_max_range();
		$this->max_month=$this->otherfunctions->column_value_max_range();
	}
//--------------------------------------------------------------------------------------------------------------//
//Global Model for Concept
	public function updateConceptFromEmployee($data,$usage)
	{
		$ret=false;
		if (empty($data)) 
			return $ret;
		//kpi
		$data_concept=$this->getListKonsepKpi(true);
		if (isset($data_concept)) {
			foreach ($data_concept as $d) {
				if ($this->db->table_exists($d->nama_tabel) && !empty($d->nama_tabel)) {
					$data['kode_konsep']=$d->kode_c_kpi;
					$ret=$this->moveDeleteKpi($d->nama_tabel,$data,'move_save');
				}
			}
		}
		$data_concept_sikap=$this->getListKonsepSikap(true);
		if (isset($data_concept_sikap)) {
			foreach ($data_concept_sikap as $ds) {
				if ($this->db->table_exists($ds->nama_tabel) && !empty($ds->nama_tabel)) {
					$data_sikap['kode_konsep']=$ds->kode_c_sikap;
					$ret=$this->syncLeaderSikap($data['id_karyawan'],$ds->kode_c_sikap,$data['kode_jabatan_new'],$data['atasan_old']);
				}
			}
		}
	}
	
//--------------------------------------------------------------------------------------------------------------//
//Konsep KPI
	public function moveDeleteKpi($table,$data,$usage='delete_save')	
	{
		$ret=false;
		if (empty($data) || empty($table)) 
			return $ret;
		if (($usage == 'delete_save' || $usage == 'delete_all' || $usage == 'delete_all_null')) {
			if ($usage == 'delete_save' && (isset($data['kode_jabatan']) && isset($data['id_karyawan']))) {
				$cek=$this->checkBeforeDeleteKpiConcept($table,$data['kode_jabatan'],$data['id_karyawan']);
				if ($cek) {
					$dt_in=['id_karyawan'=>null];
					$this->model_global->updateQueryNoMsg($dt_in,$table,['kode_jabatan'=>$data['kode_jabatan']]);
				}else{
					$this->model_global->deleteQueryNoMsg($table,['kode_jabatan'=>$data['kode_jabatan'],'id_karyawan'=>$data['id_karyawan']]);
				}
			}elseif ($usage == 'delete_all' && (isset($data['kode_jabatan']) && isset($data['id_karyawan']))) {
				//carefull to use this
				$this->model_global->deleteQueryNoMsg($table,['kode_jabatan'=>$data['kode_jabatan'],'id_karyawan'=>$data['id_karyawan']]);
			}elseif ($usage == 'delete_all_null' && (isset($data['kode_jabatan']))) {
				//carefull to use this
				$this->model_global->deleteQueryNoMsg($table,['kode_jabatan'=>$data['kode_jabatan'],'id_karyawan'=>null]);
			}
		}elseif (($usage == 'move_save' || $usage == 'move_delete_all') && (isset($data['kode_jabatan_new']) && isset($data['kode_jabatan_old']) && isset($data['id_karyawan']) && isset($data['kode_loker_old']) && isset($data['kode_loker_new']) && isset($data['kode_konsep']))) {
			if ($usage == 'move_save') {
				$cek=$this->checkBeforeDeleteKpiConcept($table,$data['kode_jabatan_old'],$data['id_karyawan']);
				if ($cek) {
					$dt_in=['id_karyawan'=>null];
					$this->model_global->updateQueryNoMsg($dt_in,$table,['kode_jabatan'=>$data['kode_jabatan_old']]);
				}else{
					$this->model_global->deleteQueryNoMsg($table,['kode_jabatan'=>$data['kode_jabatan_old'],'id_karyawan'=>$data['id_karyawan']]);
				}
			}elseif ($usage == 'move_delete_all') {
				$this->model_global->deleteQueryNoMsg($table,['kode_jabatan'=>$data['kode_jabatan_old'],'id_karyawan'=>$data['id_karyawan']]);
			}
			$this->model_agenda->updateAgendaFromConceptMaster(['id_karyawan'=>$data['id_karyawan']],'delete_concept',['id_karyawan'=>$data['id_karyawan']],$data['kode_konsep']);
			$data_new=$this->db->select('*')->from($table)->where('kode_jabatan',$data['kode_jabatan_new'])->group_by('kode_kpi')->get()->result();			
			if (isset($data_new)) {
				foreach ($data_new as $k) {
					$data_in = [
						'id_karyawan' => $data['id_karyawan'],
						'kode_jabatan' => $data['kode_jabatan_new'],
						'kode_loker'=>$data['kode_loker_new'],
						'kode_kpi' => $k->kode_kpi,
						'kpi' => $k->kpi,
						'rumus' => $k->rumus,
						'unit' => $k->unit,
						'definisi' => $k->definisi,
						'kaitan' => $k->kaitan,
						'jenis_satuan' => $k->jenis_satuan,
						'sifat' => $k->sifat,
						'cara_menghitung' => $k->cara_menghitung,
						'sumber_data'=>$k->sumber_data,
						'detail_rumus'=>$k->detail_rumus,
						'min'=>$k->min,
						'max'=>$k->max,
						'id_jenis_batasan_poin'=>$k->id_jenis_batasan_poin,
						'lebih_max'=>$k->lebih_max,
						'bobot'=>$k->bobot,
						'target'=>$k->target,
						'kode_penilai'=>$k->kode_penilai,
						'penilai'=>$k->penilai,
					];
					for ($i=1;$i<=$this->max_range;$i++){
						$p='poin_'.$i;
						$s='satuan_'.$i;
						$data[$p]=$k->$p;
						$data[$s]=$k->$s;
						if ($data[$p] == null) {
							$data[$s]=null;
						}
					}
					$data_to_agenda=$data_in;
					$data_in=array_merge($data_in,$this->model_global->getCreateProperties(1));
					$this->model_global->insertQueryNoMsg($data_in,$table);
					$this->model_agenda->updateAgendaFromConceptMaster($data_to_agenda,'add_concept',[],$data['kode_konsep']);
				}
				$this->model_global->deleteQueryNoMsg($table,['kode_jabatan'=>$data['kode_jabatan_new'],'id_karyawan'=>null]);
			}
		}
		return $ret;
	}
	public function checkBeforeDeleteKpiConcept($table,$kode_jabatan,$id_karyawan)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return false;
		$this->db->select('id_karyawan');
		$this->db->from($table);
		$this->db->where('kode_jabatan',$kode_jabatan);
		$this->db->group_by('id_karyawan');
		$res=$this->db->get()->result();
		$re=false;
		if (isset($res)) {
			$kar=[];
			foreach ($res as $d) {
				$kar[$d->id_karyawan]=$d->id_karyawan;
			}
			if (in_array($id_karyawan,$kar)) {
				unset($kar[$id_karyawan]);
			}
			if (count($kar) == 0) {
				$re=true;
			}
		}
		return $re;
	}
	public function getListKonsepKpi($active=false)
	{
		$this->db->order_by('update_date','DESC');
		if ($active) {
			$this->db->where('status',true); 
		}
		$query=$this->db->get('concept_kpi')->result();
		return $query;
	}
	public function getKonsepKpi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('concept_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_c_kpi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonsepKpiKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('concept_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('kode_c_kpi',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getJabatanKpi($tabel,$filter=0)
	{
		if (empty($tabel)) 
			return [null=>'Tidak Ada Jabatan'];
		$this->db->select('a.kode_jabatan');
		$this->db->from($tabel.' AS a'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		if (!empty($filter)) {
		 	$this->db->where('jbt.kode_bagian',$filter);
		} 
		$this->db->group_by('a.kode_jabatan');
		$query=$this->db->get()->result();
		$jabatan=$this->model_master->getJabatanEmployee($filter);
		foreach ($query as $q) {
			if (isset($jabatan[$q->kode_jabatan])) {
				unset($jabatan[$q->kode_jabatan]);
			}
		}
		return $jabatan;
	}
	public function getListKonsepKpiActive()
	{
		return $this->model_global->listActiveRecord('concept_kpi','kode_c_kpi','nama');
	}
	public function checkKonsepKpiCode($code)
	{
		return $this->model_global->checkCode($code,'concept_kpi','kode_c_kpi');
	}
	public function openTableViewConceptKpi($table,$k_bagian=0, $where=null)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.kode_jabatan,(select count(DISTINCT(c.kode_kpi)) from '.$table.' c WHERE kode_jabatan = a.kode_jabatan) as jumlah,(select count(DISTINCT(f.id_karyawan)) from '.$table.' f WHERE f.kode_jabatan = a.kode_jabatan) as jumlah_emp,
		(select count(DISTINCT(em.status_emp)) from '.$table.' e LEFT JOIN karyawan em ON em.id_karyawan = e.id_karyawan AND em.status_emp = 0 where e.kode_jabatan = a.kode_jabatan) as jumlah_non_aktif,
		b.nama as nama_jabatan,b.kode_jabatan as kode_jabatan, c.nama as nama_bagian, d.nama as nama_level,(select SUM(bw.bobot) from (select DISTINCT(y.kode_kpi),y.bobot,y.kode_jabatan from '.$table.' y) as bw where bw.kode_jabatan = a.kode_jabatan) as bobot, lok.nama as nama_lokasi');
		$this->db->from($table.' AS a');
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS d', 'd.kode_level_jabatan = b.kode_level', 'left'); 
		$this->db->join('karyawan AS emp', 'emp.jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS lok', 'emp.loker = lok.kode_loker', 'left');
		$this->db->group_by('a.kode_jabatan');
		if (!empty($k_bagian)) {
			$this->db->where('b.kode_bagian',$k_bagian);
		}
		if (!empty($where)) {
			$this->db->where($where);
		}
		return $this->db->get()->result();
	}
	public function openTableConceptKpi($table,$jenis=null)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		if (empty($jenis)) {
			$this->db->select('a.*,emp.nama as nama_karyawan,emp.nik as nik, b.nama as nama_jabatan,b.kode_jabatan as kode_jabatan, c.nama as nama_bagian, d.nama as nama_level');
			$this->db->from($table.' AS a');
			$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
			$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
			$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left'); 
			$this->db->join('master_level_jabatan AS d', 'd.kode_level_jabatan = b.kode_level', 'left'); 
			return $this->db->get()->result();
		}else{
			return $this->db->get_where($table,['jenis'=>$jenis])->row_array();
		}		
	}
	public function openTableViewConceptKpiJabatan($table,$kode=null, $mode = 'group_by',$filter = 0)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$sub_select='a.*,';
		if($mode == 'group_by'){
			$sub_select='a.kode_jabatan,COUNT(a.kode_kpi) as jumlah,(select count(DISTINCT(f.id_karyawan)) from '.$table.' f WHERE f.kode_jabatan = a.kode_jabatan) as jumlah_emp,';
			$this->db->group_by('a.kode_jabatan');
		}
		$this->db->select($sub_select.'emp.nama as nama_karyawan,emp.nik as nik, b.nama as nama_jabatan,b.kode_jabatan as kode_jabatan, c.nama as nama_bagian, d.nama as nama_level,rm.nama as nama_rumus');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left'); 
		$this->db->join('master_level_jabatan AS d', 'd.kode_level_jabatan = b.kode_level', 'left'); 
		$this->db->join('master_rumus AS rm', 'rm.function = a.cara_menghitung', 'left'); 
		if (!empty($kode)) {
			$this->db->where('a.kode_jabatan',$kode); 
		}
		if (!empty($filter)) {
		 	$this->db->where('b.kode_bagian',$filter);
		} 
		$this->db->order_by('emp.nik','ASC');
		$this->db->order_by('emp.nama','ASC');
		return $this->db->get()->result();
	}
	public function checkTableJabatanViewConceptKpi($table,$kode)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		return $this->db->get_where($table,['kode_jabatan'=>$kode])->num_rows();
	}
	public function getJenisKpi($tabel,$jbt)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from($tabel.' AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.status',1); 
		$this->db->where('a.kode_jabatan',$jbt); 
		$this->db->group_by('a.kode_kpi');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKpiJenis($tabel,$jbt)
	{
		$this->db->select('a.jenis,a.bobot_jenis_kpi');
		$this->db->from($tabel.' AS a');
		$this->db->where('a.kode_jabatan',$jbt); 
		$this->db->group_by('a.jenis');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getConceptKPIWhere($tabel,$where, $row=false)
	{
		$this->db->select('a.id_karyawan,a.kode_kpi');
		$this->db->from($tabel.' AS a');
		$this->db->where($where); 
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getKaryawanKpi($tabel,$jbt)
	{
		$this->db->select('a.id_karyawan,emp.nama as nama,emp.nik as nik,loker.nama as nama_loker,jbt.nama as nama_jabatan,bag.nama as bagian,jbt_new.nama as jabatan_now,loker_new.nama as loker_now');
		$this->db->from($tabel.' AS a');
		$this->db->join('karyawan AS emp', 'a.id_karyawan = emp.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'a.kode_jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'a.kode_loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_jabatan AS jbt_new', 'emp.jabatan = jbt_new.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker_new', 'emp.loker = loker_new.kode_loker', 'left');
		$this->db->where('a.status',1); 
		$this->db->where('a.kode_jabatan',$jbt); 
		$this->db->group_by('a.id_karyawan');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getSelectNamaKpi($tabel,$jbt,$jenis)
	{
		if (empty($tabel)) 
			return [null=>'Tidak Ada KPI'];
		$this->db->select('a.kode_kpi');
		$this->db->from($tabel.' AS a'); 
		$this->db->where('a.kode_jabatan',$jbt); 
		$this->db->where('a.jenis',$jenis);
		$this->db->group_by('a.kode_kpi');
		$this->db->order_by('a.kode_kpi','asc');
		$query=$this->db->get()->result();
		$kpi=$this->model_master->getDataJenisKpi($jenis);
		foreach ($query as $q) {
			if (isset($kpi[$q->kode_kpi])) {
				unset($kpi[$q->kode_kpi]);
			}
		}
		return $kpi;
	}
	public function getSelectKaryawanKpi($tabel,$jbt)
	{
		if (empty($tabel)) 
			return [];
		$query=$this->getKaryawanKpi($tabel,$jbt);
		$karyawan=$this->model_karyawan->getSelectEmployeeKpi($jbt);
		foreach ($query as $q) {
			if (isset($karyawan[$q->id_karyawan])) {
				unset($karyawan[$q->id_karyawan]);
			}
		}
		return array_filter($karyawan);
	}
	public function getListTableKonsepKpi($tabel,$jbt)
	{
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where('kode_jabatan',$jbt); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getJenisKpiOne($tabel,$jbt,$kpi)
	{
		$this->db->select('a.*,rm.nama as nama_rumus,b.nama as nama_buat, c.nama as nama_update,bp.nama as nama_batasan_poin');
		$this->db->from($tabel.' AS a');
		$this->db->join('master_rumus AS rm', 'rm.function = a.cara_menghitung', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_jenis_batasan_poin AS bp', 'bp.id_batasan_poin = a.id_jenis_batasan_poin', 'left'); 
		$this->db->where('a.kode_jabatan',$jbt); 
		$this->db->where('a.kode_kpi',$kpi); 
		$this->db->group_by('a.kode_kpi');
		$query=$this->db->get()->result();
		return $query;
	}
	public function generateKonsepKpi($name)
	{
		if (empty($name)) 
			return false;
		$arr_start = [
			'id_c_kpi' => ['type' => 'BIGINT','constraint' => 255,'unsigned' => TRUE,'auto_increment' => TRUE],
			'id_karyawan' => ['type' => 'BIGINT','constraint' => 255,'null'=> TRUE],
			'kode_jabatan' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'kode_loker' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'kode_kpi' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'kpi' => ['type' => 'TEXT','null'=> TRUE],
			'rumus' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'unit' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'definisi' => ['type' => 'TEXT','null'=> TRUE],
			'kaitan' => ['type' => 'TINYINT','constraint' => 1,'null'=> TRUE],
			'jenis_satuan' => ['type' => 'TINYINT','constraint' => 1,'null'=> TRUE],
			'sifat' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'cara_menghitung' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'sumber_data' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'detail_rumus' => ['type' => 'TEXT','null'=> TRUE],
			'min' => ['type' => 'FLOAT','null'=> TRUE],
			'max' => ['type' => 'FLOAT','null'=> TRUE],
			'id_jenis_batasan_poin' => ['type' => 'INT','constraint' => 255,'null'=> TRUE],
			'lebih_max' => ['type' => 'TINYINT','constraint' => 1,'null'=> TRUE],
			'bobot' => ['type' => 'FLOAT','null'=> TRUE],
			'target' => ['type' => 'FLOAT','null'=> TRUE],
			'kode_penilai' => ['type' => 'VARCHAR','constraint' => 30,'null'=> TRUE],
			'penilai' => ['type' => 'TEXT','null'=> TRUE],
			'kpi_utama' => ['type' => 'VARCHAR','constraint' => 100,'null'=> TRUE],
			'not_available' => ['type' => 'TINYINT','constraint' => 1,'null'=> TRUE],
		];
		$arr_poin=[];
		for ($i_poin=1; $i_poin <= $this->max_range ; $i_poin++) { 
			$p='poin_'.$i_poin;
			$s='satuan_'.$i_poin;
			$arr_poin[$p] = ['type' => 'FLOAT','null'=> TRUE];
			$arr_poin[$s] = ['type' => 'TEXT','null'=> TRUE];
		}
		$arr_end=[
			'status' => ['type' => 'TINYINT','constraint' => 1,'null'=> TRUE],
			'create_date' => ['type' => 'DATETIME','null'=> TRUE],
			'update_date' => ['type' => 'DATETIME','null'=> TRUE],
			'create_by' => ['type' => 'BIGINT','constraint' => 255,'null'=> TRUE],
			'update_by' => ['type' => 'BIGINT','constraint' => 255,'null'=> TRUE],
		];
		$cols=array_merge($arr_start,$arr_poin,$arr_end);
		$in=$this->model_global->createTable($name,$cols,'id_c_kpi');
		if ($in) {
			return true;
		}else{
			return false;
		}
	}
	public function updateFromMasterKPI($data)
	{
		$ret=false;
		if (empty($data)) 
			return $ret;
		$data_concept=$this->getListKonsepKpi(true);
		if (isset($data_concept)) {
			foreach ($data_concept as $d) {
				if ($this->db->table_exists($d->nama_tabel)) {
					if (isset($data['id_jenis_batasan_poin']) && !isset($data['kode_kpi'])) {
						$where=['id_jenis_batasan_poin'=>$data['id_jenis_batasan_poin']];
					}else{
						$where=['kode_kpi'=>$data['kode_kpi']];
					}
					$this->model_global->updateQueryNoMsg($data,$d->nama_tabel,$where);
					$ret=true;
				}
			}
		}
		return $ret;
	}
//--------------------------------------------------------------------------------------------------------------//
//Konsep Sikap
	public function getListKonsepSikap($active=true)
	{
		if ($active) {
			$this->db->where('status',true); 
		}
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('concept_sikap')->result();
		return $query;
	}
	public function getKonsepSikap($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('concept_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_c_sikap',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonsepSikapKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('concept_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('kode_c_sikap',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getEmployeeSikap($tabel,$filter=0)
	{
		if (empty($tabel)) 
			return [null=>'Tidak Karyawan'];
		$this->db->select('a.id_karyawan');
		$this->db->from($tabel.' AS a'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		if (!empty($filter)) {
		 	$this->db->where('jbt.kode_bagian',$filter);
		} 
		$query=$this->db->get()->result();
		$emp=$this->model_karyawan->getEmployeeForSelect2($filter);
		foreach ($query as $q) {
			if (isset($emp[$q->id_karyawan])) {
				unset($emp[$q->id_karyawan]);
			}
		}
		return $emp;
	}
	public function getListKonsepSikapActive()
	{
		return $this->model_global->listActiveRecord('concept_sikap','kode_c_sikap','nama');
	}
	public function checkKonsepSikapCode($code)
	{
		return $this->model_global->checkCode($code,'concept_sikap','kode_c_sikap');
	}
	public function openTableViewConceptSikap($table,$filter=0, $where=null) 
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker, d.nama as bagian,e.nama as nama_buat, f.nama as nama_update,b_new.nama as jabatan_now,c_new.nama as loker_now');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left'); 
		$this->db->join('admin AS e', 'e.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS f', 'f.id_admin = a.update_by', 'left'); 
		$this->db->join('master_jabatan AS b_new', 'b_new.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_loker AS c_new', 'c_new.kode_loker = emp.loker', 'left'); 
		if (!empty($filter)) {
			$this->db->where('b.kode_bagian',$filter);
		}
		if (!empty($where)) {
			$this->db->where($where);
		}
		$this->db->order_by('a.create_date','DESC');
		return $this->db->get()->result();
	}
	public function openTableViewConceptSikapId($table,$id)
	{
		if (empty($table) || !$this->db->table_exists($table) || empty($id))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker, d.nama as bagian,e.nama as nama_buat, f.nama as nama_update');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left'); 
		$this->db->join('admin AS e', 'e.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS f', 'f.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_c_sikap',$id); 
		return $this->db->get()->result();
	}
	public function openTableViewConceptSikapEmpId($table,$id_k)
	{
		if (empty($table) || !$this->db->table_exists($table) || empty($id_k))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker, d.nama as bagian,e.nama as nama_buat, f.nama as nama_update,asp.bobot_aspek as bobot_aspek,b.tipe_jabatan as kode_tipe');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left'); 
		$this->db->join('master_form_aspek AS asp', 'asp.kode_tipe = b.tipe_jabatan', 'left'); 
		$this->db->join('admin AS e', 'e.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS f', 'f.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_karyawan',$id_k); 
		return $this->db->get()->result();
	}
	public function checkTableEmployeeViewConceptSikap($table,$nik)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$cek=$this->model_karyawan->getEmployeeId($nik);
		if (isset($cek) && !empty($cek)) {
			$id=$id;
		}else{
			$id=$this->model_karyawan->getEmployeeNik($nik)['id_karyawan'];
		}
		$query = $this->db->get_where($table,['id_karyawan'=>$id])->num_rows();
		return $query;
	}
	public function openTableConceptSikap($table)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.bobot_aspek as bobot_aspek,b.tipe_jabatan as kode_tipe');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_form_aspek AS c', 'c.kode_tipe = b.tipe_jabatan', 'left'); 
		return $this->db->get()->result();
	}
	public function getEmployeePartisipan($table,$id)
	{
		if (empty($table) || !$this->db->table_exists($table) || empty($id))
			return null;
		$data=$this->openTableViewConceptSikapEmpId($table,$id);
		$pack=$this->model_karyawan->getEmployeeForSelect2();
		foreach ($data as $d) {
			$par=$this->exam->getPartisipantKode($d->partisipan);
			if (isset($par)) {
				foreach ($par as $k_p => $v_p) {
					if (isset($pack[$k_p])) {
						unset($pack[$k_p]);
					}
					if (isset($pack[$id])) {
						unset($pack[$id]);
					}
				}
			}
		}
		return $pack;
	}
	public function getJenisSikap($tabel,$jenis,$jbt)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from($tabel.' AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.status',1); 
		$this->db->where('a.jenis',$jenis); 
		$this->db->where('a.kode_jabatan',$jbt); 
		$this->db->group_by('a.kode_sikap');
		$query=$this->db->get()->result();
		return $query;
	}
	public function generateKonsepSikap($name)
	{
		if (empty($name)) 
			return false;
		$cols = [
			'id_c_sikap' => ['type' => 'BIGINT','constraint' => 255,'unsigned' => TRUE,'auto_increment' => TRUE],
			'id_karyawan' => ['type' => 'BIGINT','constraint' => 255,'null'=> TRUE],
			'kode_jabatan' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'kode_loker' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'partisipan' => ['type' => 'LONGTEXT','null'=> TRUE],
			'bobot_ats' => ['type' => 'FLOAT','null'=> TRUE],
			'bobot_rkn' => ['type' => 'FLOAT','null'=> TRUE],
			'bobot_bwh' => ['type' => 'FLOAT','null'=> TRUE],
			'sub_bobot_ats' => ['type' => 'TEXT','null'=> TRUE],
			'status' => ['type' => 'TINYINT','constraint' => 1,'null'=> TRUE],
			'create_date' => ['type' => 'DATETIME','null'=> TRUE],
			'update_date' => ['type' => 'DATETIME','null'=> TRUE],
			'create_by' => ['type' => 'BIGINT','constraint' => 255,'null'=> TRUE],
			'update_by' => ['type' => 'BIGINT','constraint' => 255,'null'=> TRUE],
		];
		$in=$this->model_global->createTable($name,$cols,'id_c_sikap');
		if ($in) {
			return true;
		}else{
			return false;
		}
	}
	public function syncLeaderSikap($id,$kode_konsep,$kode_jabatan,$id_atasan_old)
	{
		if (empty($id) || empty($kode_konsep) || empty($kode_jabatan)) 
			return false;
		$data_concept=$this->getKonsepSikapKode($kode_konsep);
		$emp=$this->model_karyawan->getEmployeeId($id);
		if (isset($data_concept) && isset($emp)) {
			$table=$data_concept['nama_tabel'];
			$opsi='ATS';
			$new=$opsi.':'.$emp['id_atasan'];
			$old=$opsi.':'.$id_atasan_old;
			$data_t=$this->model_concept->openTableViewConceptSikapEmpId($table,$id);
			foreach ($data_t as $d) {
				$par=$d->partisipan;
				$s_b=$d->sub_bobot_ats;
				$par=$this->exam->delPartisipantDb([$old],$par); 
				$par=$this->exam->addPartisipanDb([$emp['id_atasan']],$opsi,$par);
				$what=$this->exam->getPartisipantPiece($old,'front');
				if ($what == 'ATS') {
					$sub_bobot=$this->exam->delSubBobotAtasanDb([$old],$s_b);
				}else{
					$sub_bobot=$s_b;
				}
				$bobot_sikap=$this->exam->getBobotData($this->exam->getBobotCode($par));
				$data=['partisipan'=>$par,'sub_bobot_ats'=>$sub_bobot,'kode_jabatan'=>$kode_jabatan];
				$data=array_merge($data,$bobot_sikap);
				$data_to_agenda=$data;
				$data_to_agenda['id_karyawan']=$id;
				$data_to_agenda['id_partisipan']=$emp['id_atasan'];
				$data_to_agenda['partisipan_del']=[$old];
				$data_to_agenda['old']=$what;
				$data_to_agenda['new']=$opsi;
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_agenda->updateFromConceptSikap($data_to_agenda,'partisipan_edit');
				$this->model_global->updateQueryNoMsg($data,$table,['id_karyawan'=>$id]);
			}
		}
		
	}
//--------------------------------------------------------------------------------------------------------------//
//Konsep Kompetensi
	public function getListKonsepKompetensi()
	{
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('concept_kompetensi')->result();
		return $query;
	}
	public function getKonsepKompetensi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('concept_kompetensi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('id_c_kompetensi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKonsepKompetensiKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('concept_kompetensi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('kode_c_kompetensi',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getEmployeeKompetensi($tabel,$jabatan,$poin_max=600)
	{
		if (empty($tabel)) 
			return [null=>'Tidak Karyawan'];
		$this->db->select('a.id_karyawan');
		$this->db->from($tabel.' AS a'); 
		$query=$this->db->get()->result();
		$data_emp=$this->model_karyawan->getEmployeeAllActive();
		$emp=[];
		if (isset($data_emp)) {
			foreach ($data_emp as $d) {
				$masa_kerja=$this->otherfunctions->getMasaKerja($d->tgl_masuk,$this->otherfunctions->getDateNow(),'array');
				if ((($masa_kerja['year'] >= 3) || ($d->poin >= $poin_max)) && $jabatan == $d->jabatan) {
					$emp[$d->id_karyawan]=$d->nama.' ('.$d->nama_jabatan.')';
				}
			}
		}
		if (isset($query)) {
			foreach ($query as $q) {
				if (isset($emp[$q->id_karyawan])) {
					unset($emp[$q->id_karyawan]);
				}
			}
		}
		return $emp;
	}
	public function getJabatanKompetensi($tabel)
	{
		if (empty($tabel)) 
			return [null=>'Tidak Karyawan'];
		// $this->db->select('a.kode_jabatan');
		// $this->db->from($tabel.' AS a'); 
		// $query=$this->db->get()->result();
		$data_jbt=$this->model_master->getListJabatan(1);
		$jbt=[];
		if (isset($data_jbt)) {
			foreach ($data_jbt as $d) {
				$jbt[$d->kode_jabatan]=$d->nama.' ('.(($d->nama_bagian)?$d->nama_bagian:'Unknown').')';				
			}
		}
		// if (isset($query)) {
		// 	foreach ($query as $q) {
		// 		if (isset($jbt[$q->kode_jabatan])) {
		// 			unset($jbt[$q->kode_jabatan]);
		// 		}
		// 	}
		// }
		return $jbt;
	}
	public function getListKonsepKompetensiActive()
	{
		return $this->model_global->listActiveRecord('concept_kompetensi','kode_c_kompetensi','nama');
	}
	public function checkKonsepKompetensiCode($code)
	{
		return $this->model_global->checkCode($code,'concept_kompetensi','kode_c_kompetensi');
	}
	public function generateKonsepKompetensi($name)
	{
		if (empty($name)) 
			return false;
		for ($i=1; $i <=10 ; $i++) { 
			$sub_cols['poin_'.$i]=[
				'type' => 'FLOAT',
				'null'=> TRUE
			];
			$sub_cols['satuan_'.$i]=[
				'type' => 'TEXT',
				'null'=> TRUE
			];
		}
		$cols = [
			'id_c_kompetensi' => [
				'type' => 'BIGINT',
				'constraint' => 255,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			],
			'id_karyawan' => [
				'type' => 'BIGINT',
				'constraint' => 255,
				'null'=> TRUE
			],			
			'kode_jabatan' => [
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null'=> TRUE
			],
			'kode_loker' => [
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null'=> TRUE
			],
			'kode_kategori' => [
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null'=> TRUE
			],
			'kode_kompetensi' => [
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null'=> TRUE
			],
			'kompetensi' => [
				'type' => 'TEXT',
				'null'=> TRUE
			],
			'definisi' => [
				'type' => 'TEXT',
				'null'=> TRUE
			],
			'aspek_kompetensi' => [
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null'=> TRUE
			],
			'sifat' => [
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null'=> TRUE
			],
			'bobot_jenis_kompetensi' => [
				'type' => 'FLOAT',
				'null'=> TRUE
			],
		];
		$cols=array_merge($cols,$sub_cols);
		$cols2 = [
			'bobot' => [
				'type' => 'FLOAT',
				'null'=> TRUE
			],
			'penilai' => [
				'type' => 'TEXT',
				'null'=> TRUE
			],
			'status' => [
				'type' => 'TINYINT',
				'constraint' => 1,
				'null'=> TRUE
			],
			'create_date' => [
				'type' => 'DATETIME',
				'null'=> TRUE
			],
			'update_date' => [
				'type' => 'DATETIME',
				'null'=> TRUE
			],
			'create_by' => [
				'type' => 'BIGINT',
				'constraint' => 255,
				'null'=> TRUE
			],
			'update_by' => [
				'type' => 'BIGINT',
				'constraint' => 255,
				'null'=> TRUE
			],
		];
		$cols=array_merge($cols,$cols2);
		$in=$this->model_global->createTable($name,$cols,'id_c_kompetensi');
		if ($in) {
			return true;
		}else{
			return false;
		}
	}
	public function openTableViewConceptKompetensi($table)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.id_karyawan,a.kode_jabatan,emp.nik as nik,emp.nama as nama,(select count(DISTINCT(c.kode_kompetensi)) from '.$table.' c WHERE id_karyawan = a.id_karyawan) as jumlah ,b.nama as nama_jabatan,b.kode_jabatan as kode_jabatan, c.nama as nama_bagian, d.nama as nama_level,e.nama as nama_loker,b_new.nama as jabatan_now,c_new.nama as loker_now');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left'); 
		$this->db->join('master_loker AS e', 'e.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_level_jabatan AS d', 'd.kode_level_jabatan = b.kode_level', 'left'); 
		$this->db->join('master_jabatan AS b_new', 'b_new.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_loker AS c_new', 'c_new.kode_loker = emp.loker', 'left'); 
		$this->db->group_by('a.id_karyawan');
		return $this->db->get()->result();
	}
	public function openTableViewConceptKompetensiId($table,$id)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nik as nik,emp.nama as nama,(select count(DISTINCT(c.kode_kompetensi)) from '.$table.' c WHERE id_karyawan = a.id_karyawan) as jumlah ,b.nama as nama_jabatan,b.kode_jabatan as kode_jabatan, c.nama as nama_bagian, d.nama as nama_level,e.nama as nama_loker,b_new.nama as jabatan_now,c_new.nama as loker_now,sc.nama as nama_buat, sd.nama as nama_update');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left'); 
		$this->db->join('master_loker AS e', 'e.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_level_jabatan AS d', 'd.kode_level_jabatan = b.kode_level', 'left'); 
		$this->db->join('master_jabatan AS b_new', 'b_new.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_loker AS c_new', 'c_new.kode_loker = emp.loker', 'left');
		$this->db->join('admin AS sc', 'sc.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS sd', 'sd.id_admin = a.update_by', 'left');  
		$this->db->where('a.id_c_kompetensi',$id);
		return $this->db->get()->result();
	}
	public function openTableViewConceptKompetensiEmployee($table,$id, $mode = 'group_by')
	{
		if (empty($table) || !$this->db->table_exists($table) || empty($id))
			return null;
		$sub_select='a.*,';
		if($mode == 'group_by'){
			$sub_select='COUNT(a.kode_kompetensi) as jumlah,';
			$this->db->group_by('a.id_karyawan');
		}
		$this->db->select($sub_select.'a.*, b.nama as nama_jabatan,a.kode_jabatan,emp.nik as nik,emp.nama as nama,b.kode_jabatan as kode_jabatan, c.nama as nama_bagian, d.nama as nama_level,e.nama as nama_loker');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_bagian AS c', 'c.kode_bagian = b.kode_bagian', 'left'); 
		$this->db->join('master_loker AS e', 'e.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_level_jabatan AS d', 'd.kode_level_jabatan = b.kode_level', 'left'); 
		$this->db->where('a.id_karyawan',$id); 
		return $this->db->get()->result();
	}
	public function checkTableEmployeeViewConcept($table,$id)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$cek=$this->model_karyawan->getEmployeeId($id);
		if (isset($cek)) {
			$id=$id;
		}else{
			$id=$this->model_karyawan->getEmployeeNik($id)['id_karyawan'];
		}
		return $this->db->get_where($table,['id_karyawan'=>$id])->num_rows();
	}
	public function getKategoriKompetensi($table,$id)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*, kat.nama as nama_kategori');
		$this->db->from($table.' AS a');
		$this->db->join('master_kategori_kompetensi AS kat', 'kat.kode_kategori = a.kode_kategori', 'left');
		$this->db->where('a.id_karyawan',$id); 
		$data=$this->db->get()->result();
		$ret=[];
		if (isset($data)) {
			foreach ($data as $d) {
				$ret[$d->kode_kategori]=$d->nama_kategori;
			}
		}
		return $ret;
	}
}