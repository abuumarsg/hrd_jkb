<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * Code From GFEACORP.
	 * Web Developer
	 * @author 		Galeh Fatma Eko Ardiansa
	 * @package		Model Agenda
	 * @copyright	Copyright (c) 2018 GFEACORP
	 * @version 	1.0, 1 September 2018
	 * Email 		galeh.fatma@gmail.com
	 * Phone		(+62) 85852924304
	 */

class Model_agenda extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		$this->max_range=$this->otherfunctions->poin_max_range();
		$this->max_month=$this->otherfunctions->column_value_max_range();
	}
//GLOBAL MODEL AGENDA
	public function getAgendaActive($table)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,c.nama as nama_periode');
		$this->db->from($table.' AS a');
		$this->db->join('master_periode_penilaian AS c', 'c.kode_periode = a.periode', 'left');
		$this->db->where('a.tgl_mulai <=', $this->date);		 	
		$this->db->where('a.tgl_selesai >=', $this->date);
		$this->db->where('a.status',1);		 			 	
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkAgendaAvailable($table,$where)
	{
		$ret=['agenda'=>0,'log_agenda'=>0];
		if (empty($table) || !$this->db->table_exists($table) || !$this->db->table_exists('log_'.$table) || empty($where))
			return $ret;
		$ret['agenda']=$this->db->get_where($table,$where)->num_rows();
		$ret['log_agenda']=$this->db->get_where('log_'.$table,$where)->num_rows();
		return $ret;
	}
	public function getLogAgenda($table)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*'.(($table != 'log_agenda_reward')?',c.nama as nama_periode':null));
		$this->db->from($table.' AS a');
		if ($table != 'log_agenda_reward') {
			$this->db->join('master_periode_penilaian AS c', 'c.kode_periode = a.periode', 'left');
		}
		$this->db->where('a.status',1);		 			 	
		$this->db->where('a.nama_tabel IS NOT NULL');	 			 	
		$this->db->order_by('create_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function openTableAgenda($table,$data = null, $where = null)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker, d.nama as bagian,b.kode_periode as kode_periode,e.nama as nama_departemen,r.nama as nama_rank,s.nama as nama_rank_old,emp.poin_old as poin_old,emp.poin_now as poin_now,emp.poin as poin');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan AND emp.status_emp = 1', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  
		$this->db->join('master_level_struktur AS e', 'e.kode_level_struktur = d.kode_level_struktur', 'left');
		// $this->db->join('master_departement AS e', 'e.kode_departement = d.kode_departement', 'left');
		$this->db->join('master_rank AS r', 'emp.grade = r.kode_rank', 'left');
		$this->db->join('master_rank AS s', 'emp.grade_old = s.kode_rank', 'left');
		$this->db->join('master_level_jabatan AS lv', 'lv.kode_level_jabatan = b.kode_level', 'left');
		if(!empty($data)){
			(!empty($data['loker_filter'])) ? $this->db->where('c.kode_loker', $data['loker_filter']) : null;
			(!empty($data['bagian_filter'])) ? $this->db->where('d.kode_bagian', $data['bagian_filter']) : null;
			(!empty($data['departemen_filter'])) ? $this->db->where('e.kode_departement', $data['departemen_filter']) : null;
			(!empty($data['id_karyawan_filter'])) ? $this->db->where('emp.id_karyawan', $data['id_karyawan_filter']) : null;
			if (!empty($data['level_jabatan_filter'])) {
				$sq_level=null;
				$cl=1;
				foreach ($data['level_jabatan_filter'] as $kode_level) {
					if (!empty($kode_level)) {
						$sq_level.="b.kode_level = '$kode_level'";
						if ($cl < count($data['level_jabatan_filter'])) {
							$sq_level.=' OR ';
						}
						$cl++;
					}							
				}
				if (!empty($sq_level)) {
					$this->db->where('('.$sq_level.')');
				}
			}
		}
		if(!empty($where)){$this->db->where($where);}
		$this->db->where('lv.ikut_pa',1);
		$this->db->order_by('emp.nik','ASC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function openTableAgendaId($table,$id,$row=false)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker, d.nama as bagian');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  
		$this->db->where('a.id_task',$id);
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}	   
	public function openTableAgendaIdEmployee($table,$id=null,$search=null)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.kelamin,emp.foto,emp.tgl_masuk,emp.email,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker,b.kode_periode as kode_periode,d.nama as bagian,b_new.nama as nama_jabatan_baru,c_new.nama as nama_loker_baru,d_new.nama as bagian_baru');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  		
		$this->db->join('master_jabatan AS b_new', 'b_new.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_loker AS c_new', 'c_new.kode_loker = emp.loker', 'left'); 
		$this->db->join('master_bagian AS d_new', 'd_new.kode_bagian = b_new.kode_bagian', 'left');
		if(isset($id) && $id != null){
			$this->db->where('a.id_karyawan',$id);
		}
		if(isset($search['bagian_filter']) && $search['bagian_filter'] != null){
			$this->db->where('b.kode_bagian',$search['bagian_filter']);
		}
		if(isset($search['loker_filter']) && $search['loker_filter'] != null){
			$this->db->where('a.kode_loker',$search['loker_filter']);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function openTableAgendaIdCode($table,$id_emp,$kode,$column_code,$column_id='a.id_karyawan')
	{
		if (empty($table) || empty($kode) || empty($column_code) || empty($id_emp) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker,b.kode_periode as kode_periode,d.nama as bagian');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  
		$this->db->where([$column_code=>$kode,$column_id=>$id_emp]);		
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getValueProgressAgenda($table,$agenda='kpi')
	{
		// print_r($table);
		if (empty($table) || !$this->db->table_exists($table))
			return 0;
		$pack=[]; 
		$data=$this->openTableAgenda($table);
		if (count($data) == 0) 
			return 0;
		foreach ($data as $d) {
			if ($agenda == 'kpi'){
				for ($i=1; $i <= $this->max_month; $i++) { 
					$cols='na'.$i;
					if (!is_null($d->$cols)) {
						array_push($pack,1);
					}
				}	
			}else if($agenda == 'sikap'){
				if (!empty($d->na)) {
					array_push($pack,1);
				}
			}		
		}
		$count=0;
		if ($agenda == 'kpi'){
			$count=count($pack)/(count($data)*$this->max_month)*100;
		}else if($agenda == 'sikap'){
			$count=count($pack)/count($data)*100;
		}		
		return number_format($count,2);
	}
	public function checkActiveAgenda($table,$kode,$column_code)
	{
		if (empty($table) || empty($kode) || empty($column_code) || !$this->db->table_exists($table))
			return null;
		$query=$this->db->get_where($table,[$column_code=>$kode,'tgl_mulai <='=>$this->date,'tgl_selesai >='=>$this->date,'status'=>1])->row_array();
		return $query;
	}
//===AGENDA KPI BEGIN===//
//--------------------------------------------------------------------------------------------------------------//
//Agenda KPI
	public function getListAgendaKpi($active = false)
	{
		$this->db->select('a.*,b.nama as nama_konsep,c.nama as nama_periode, c.start, c.end,c.batas as batas');
		$this->db->from('agenda_kpi AS a');
		$this->db->join('master_periode_penilaian AS c', 'c.kode_periode = a.periode', 'left'); 
		$this->db->join('concept_kpi AS b', 'b.kode_c_kpi = a.kode_c_kpi', 'left'); 
		if ($active) {
			$this->db->where('a.status',1);
		}	
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaKpi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('agenda_kpi AS a');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left');
		$this->db->where('id_a_kpi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaKpiValidate($validate=1)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('agenda_kpi AS a');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left');
		$this->db->where('a.validasi',$validate); 
		$this->db->where('a.status',1); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaKpiKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep, e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('agenda_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left'); 
		$this->db->where('kode_a_kpi',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getAgendaKpiTable($table)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep, e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('agenda_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left'); 
		$this->db->where('a.nama_tabel',$table); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function checkAgendaKpiCode($code)
	{
		return $this->model_global->checkCode($code,'agenda_kpi','kode_a_kpi');
	}
	public function openTableEmployeeKpi($table,$id)
	{
		if (empty($id) || empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker,b.kode_periode as kode_periode, d.nama as bagian,rms.nama as nama_rumus,rms.function as rumus_kpi');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  
		$this->db->join('master_kpi AS kpi', 'kpi.kode_kpi =a.kode_kpi', 'left');  
		$this->db->join('master_rumus AS rms', 'rms.function =a.cara_menghitung', 'left');  
		$this->db->where('a.id_karyawan',$id);		
		$query=$this->db->get()->result();
		return $query;
	}
	public function generateAgendaKpi($c,$name)
	{
		if (empty($c) || empty($name)) 
			return false;
		$table_name=$this->model_concept->getKonsepKpiKode($c)['nama_tabel'];
		$open_table=$this->model_concept->openTableConceptKpi($table_name);
		$ret=false;
		$arr_start = [
			'id_task' => ['type' => 'BIGINT','constraint' => 255,'unsigned' => TRUE,'auto_increment' => TRUE],
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
		$arr_value=[];
		for ($i_value=1; $i_value <= $this->max_month ; $i_value++) { 
			$pn='pn'.$i_value;
			$nr='nr'.$i_value;
			$na='na'.$i_value;
			$arr_value[$pn] = ['type' => 'TEXT','null'=> TRUE];
			$arr_value[$nr] = ['type' => 'FLOAT','null'=> TRUE];
			$arr_value[$na] = ['type' => 'FLOAT','null'=> TRUE];
		}
		$arr_end=[
			'na' => ['type' => 'FLOAT','null'=> TRUE],
			'final_result' => ['type' => 'FLOAT','null'=> TRUE],
		];
		$cols=array_merge($arr_start,$arr_poin,$arr_value,$arr_end);
		$in=$this->model_global->createTable($name,$cols,'id_task');
		if ($in) {
			$table_name=$this->model_concept->getKonsepKpiKode($c)['nama_tabel'];
			if (!empty($table_name)) {
				$open_table=$this->model_concept->openTableConceptKpi($table_name);
				if (!empty($open_table)) {
					foreach ($open_table as $d) {
						if (!empty($d->id_karyawan) && !empty($d->kode_jabatan)) {
							$data=[
								'id_karyawan'=>$d->id_karyawan,
								'kode_jabatan'=>$d->kode_jabatan,
								'kode_loker'=>$d->kode_loker,
								'kode_kpi' => $d->kode_kpi,
								'kpi' => $d->kpi,
								'rumus' => $d->rumus,
								'unit' => $d->unit,
								'definisi' => $d->definisi,
								'kaitan' => $d->kaitan,
								'jenis_satuan' => $d->jenis_satuan,
								'sifat' => $d->sifat,
								'cara_menghitung' => $d->cara_menghitung,
								'sumber_data'=>$d->sumber_data,
								'detail_rumus'=>$d->detail_rumus,
								'min'=>$d->min,
								'max'=>$d->max,
								'id_jenis_batasan_poin'=>$d->id_jenis_batasan_poin,
								'lebih_max'=>$d->lebih_max,
								'bobot'=>$d->bobot,
								'target'=>$d->target,
								'kode_penilai'=>$d->kode_penilai,
								'penilai'=>$d->penilai,
								'not_available'=>$d->not_available,
							];
							for ($i=1;$i<=$this->max_range;$i++){
								$p='poin_'.$i;
								$s='satuan_'.$i;
								$data[$p]=$d->$p;
								$data[$s]=$d->$s;
								if ($data[$p] == null) {
									$data[$s]=null;
								}
							}
							$this->model_global->insertQuery($data,$name);
						}
					}
					$ret=true;
				}
			}
		}
		return $ret;
	}
	public function getTabelKpi($tabel, $data = null){
		$this->db->select('a.*,emp.nama as nama_karyawan,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker, d.nama as nama_bagian, e.nama as nama_departement,b.kode_periode as kode_periode');
		$this->db->from($tabel.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');
		$this->db->join('master_departement AS e', 'e.kode_departement = d.kode_departement', 'left');
		if(!empty($data)){
			(!empty($data['loker_filter'])) ? $this->db->where('c.kode_loker', $data['loker_filter']) : null;
			(!empty($data['bagian_filter'])) ? $this->db->where('d.kode_bagian', $data['bagian_filter']) : null;
			(!empty($data['departemen_filter'])) ? $this->db->where('e.kode_departement', $data['departemen_filter']) : null;
		}
		$this->db->group_by('a.id_karyawan');
		$this->db->order_by('emp.nik','ASC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPenilaiAtasan($tabel,$kode_jabatan){
		$where = ['b.atasan' => $kode_jabatan, 'kode_penilai'=>'P1'];
		$this->db->select('a.*');
		$this->db->from($tabel.' AS a');
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->where($where); 
		$this->db->group_by('a.id_karyawan');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPenilaiAtasanAll($tabel,$id_karyawan){
		$where = ['a.id_karyawan' => $id_karyawan,'a.kode_penilai'=>'P1'];
		$this->db->select('a.*');
		$this->db->from($tabel.' AS a');
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPenilaiAtasanAllIdIsi($tabel,$id_karyawan){
		$where =['a.id_karyawan' => $id_karyawan, 'a.kode_penilai'=>'P1','a.na !='=>null];
		$this->db->select('a.*');
		$this->db->from($tabel.' AS a');
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPenilaiUserLain($tabel){
		$where = ['kode_penilai'=>'P3'];
		$this->db->select('a.*');
		$this->db->from($tabel.' AS a');
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->where($where); 
		$this->db->group_by('a.id_karyawan');
		$query=$this->db->get()->result();
		return $query;
	}
	public function Task_na($tabel,$id){
		$where =['kode_penilai'=>'P3','id_karyawan'=>$id];
		$this->db->select('a.*');
		$this->db->from($tabel.' AS a');
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getBawahanTernilaiAll($tabel,$kode_jabatan){
		$where = array(
			'b.atasan' => $kode_jabatan, 
			'kode_penilai'=>'P1' 
		);
		$this->db->select('a.*');
		$this->db->from($tabel.' AS a');
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->where($where); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function rumusCustomKubotaFinalResultKpi($table,$id=null,$insentif = false,$per_month=false,$search=null)
	{
		if (empty($table) || empty($id) || !$this->db->table_exists($table))
			return null;
		$pack=[];
		$data=$this->openTableAgendaIdEmployee($table,$id,$search);
		$max_col=$this->max_month;
		if ($insentif) {
			$data_agenda=$this->getAgendaKpiTable($table);
			if (isset($data_agenda)) {
				for ($i_periode=$data_agenda['start']; $i_periode <=$data_agenda['end']; $i_periode++) { 
					if ($i_periode == 12) {
						$max_col=$this->max_month-1;
					}
				}
			}
		}
		if (isset($data)) {
			$bobot_all=[];
			foreach ($data as $da){
				if(!$da->not_available){
					$bobot_all[$da->kode_kpi]=$this->exam->convertComma($da->bobot);
				}
			}
			foreach ($data as $d) {
				$id_karyawan[$d->id_karyawan]=$d->id_karyawan;
				// $bobot[$d->id_karyawan][$d->kode_kpi]=$d->bobot;
				$bobot[$d->id_karyawan][$d->kode_kpi]=$this->exam->bobot_tertimbang($bobot_all,$this->exam->convertComma($d->bobot),$d->not_available);
				$cara_menghitung[$d->id_karyawan][$d->kode_kpi]=$d->cara_menghitung;
				$target[$d->id_karyawan][$d->kode_kpi]=$d->target;
				$data_konv[$d->id_karyawan][$d->kode_kpi]=[
					'jenis_satuan'=>$d->jenis_satuan,
					'sifat'=>$d->sifat,
				];
				for ($i_poin=1; $i_poin <=$this->max_range; $i_poin++) { 
					$poin = 'poin_'.$i_poin;
					$satuan = 'satuan_'.$i_poin;
					$data_konv[$d->id_karyawan][$d->kode_kpi][$poin]=$d->$poin;
					$data_konv[$d->id_karyawan][$d->kode_kpi][$satuan]=$d->$satuan;
				}
				for ($i=1; $i <=$max_col ; $i++) { 
					$c_pn='pn'.$i;	
					$c_nr='nr'.$i;	
					$c_na='na'.$i;
					if ($d->$c_pn == ''){
						$null[$d->id_karyawan][$d->kode_kpi][$i]=true;
					}
					$pn[$d->id_karyawan][$d->kode_kpi][$i]=$this->exam->getNilaiAverage($d->$c_pn,true);	
					$nr[$d->id_karyawan][$d->kode_kpi][$i]=$d->$c_nr;	
					$na[$d->id_karyawan][$d->kode_kpi][$i]=$d->$c_na;
				}
			}
			if (isset($id_karyawan)) {
				foreach ($id_karyawan as $k) {
					$rr = count($bobot[$k]);
					if (isset($bobot[$k])) {
						foreach ($bobot[$k] as $k_kpi => $v_bobot) {
							$plain[$k][$k_kpi]=0;
							$n_kpi=0;
							$n_target=0;
							$n_capaian=0;
							if (isset($na[$k][$k_kpi])) {
								$nilai_bulan[$k][$k_kpi]=[];
								$nilai_per_bulan[$k]=[];
								$nilai_capaian[$k][$k_kpi]=[];
								foreach ($na[$k][$k_kpi] as $bulan => $nilai_prosen) {
									$nilai_prosen=(isset($nilai_prosen)|| !empty($nilai_prosen))?$nilai_prosen:0;
									$nilbul = $this->exam->coreConversiKpi($nilai_prosen,$data_konv[$k][$k_kpi]);
									$bobot[$k][$k_kpi] = str_replace(",",".",$bobot[$k][$k_kpi]);
									$nilai_bulan[$k][$k_kpi][$bulan] = $nilbul*(ceil($bobot[$k][$k_kpi])/100);
									// $nilai_bulan[$k][$k_kpi][$bulan]=$this->exam->coreConversiKpi($nilai_prosen,$data_konv[$k][$k_kpi])*($bobot[$k][$k_kpi]/100);
									if(isset($null[$k][$k_kpi][$bulan])){
										if($null[$k][$k_kpi][$bulan]){
											$nilai_bulan[$k][$k_kpi][$bulan]=0;
										}
									}
									if ($per_month) {
										$pack['nilai_bulan'][$bulan]=0;
									}
									if(isset($nr[$k][$k_kpi][$bulan])){
										$nilai_capaian[$k][$k_kpi][] = $nr[$k][$k_kpi][$bulan];
									}
								}
								if (isset($cara_menghitung[$k][$k_kpi])) {
									if ($cara_menghitung[$k][$k_kpi] == 'SUM') {
										$n_kpi=$this->rumus->rumus_sum($nilai_bulan[$k][$k_kpi]);
										if ($insentif) {
											$n_kpi=((6/5)*$this->rumus->rumus_sum($nilai_bulan[$k][$k_kpi]));
										}
										$n_capaian = 0;
										if(!empty($nilai_capaian[$k][$k_kpi])){
											$n_capaian = array_sum($nilai_capaian[$k][$k_kpi]);
										}
										$n_target = 0;
										if($target[$k][$k_kpi] != 0){
											$n_target += $target[$k][$k_kpi];
										}
										// $n_capaian = array_sum($nilai_capaian[$k][$k_kpi]);
										// $n_target = array_sum($target[$k][$k_kpi]);
									}elseif ($cara_menghitung[$k][$k_kpi] == 'AVG') {
										$n_kpi=$this->rumus->rumus_avg($nilai_bulan[$k][$k_kpi]);
										$n_capaian = array_sum($nilai_capaian[$k][$k_kpi])/$max_col;
										$n_target = $target[$k][$k_kpi];
									}
								}
							}												
							$pack['rata_na'][$k_kpi]=$n_kpi;	
							$pack['capaian'][$k_kpi]=$n_capaian;	
							$pack['target'][$k_kpi]=$n_target;
						}
						$pack['na']['nilai']=array_sum($pack['rata_na']);
						$pack['na']['capaian']=array_sum($pack['rata_na']);
						$pack['nilai_akhir']=$pack['na']['nilai'];
						if ($per_month && isset($nilai_bulan[$k])) {
							foreach ($nilai_bulan[$k] as $kode_kpi => $val_month) {
								if (is_array($val_month) && count($val_month) > 0) {
									foreach ($val_month as $m => $m_val) {
										$pack['nilai_bulan'][$m]+=$m_val;
									}									
								}									
							}
						}
					}				
				}
			}	
		}	
		return $pack;
	}
	//old function do not delete this function
	// public function rumusCustomKubotaFinalResultKpi($table,$id)
	// {
	// 	if (empty($table) || empty($id) || !$this->db->table_exists($table))
	// 		return null;
	// 	$pack=[];
	// 	$data=$this->openTableAgendaIdEmployee($table,$id);
	// 	foreach ($data as $d) {
	// 		$id_karyawan[$d->id_karyawan]=$d->id_karyawan;
	// 		$bobot[$d->id_karyawan][$d->kode_kpi]=$d->bobot;
	// 		$bobot_jenis_kpi[$d->id_karyawan][$d->kode_kpi][$d->jenis]=$d->bobot_jenis_kpi;
	// 		$jenis[$d->id_karyawan][$d->kode_kpi]=$d->jenis;
	// 		for ($i=1; $i <=4 ; $i++) { 
	// 			$c_pn='pn'.$i;	
	// 			$c_nr='nr'.$i;	
	// 			$c_na='na'.$i;
	// 			$pn[$d->id_karyawan][$d->kode_kpi][$d->jenis][$i]=$d->$c_pn;	
	// 			$nr[$d->id_karyawan][$d->kode_kpi][$d->jenis][$i]=$d->$c_nr;	
	// 			$na[$d->id_karyawan][$d->kode_kpi][$d->jenis][$i]=$d->$c_na;	
	// 		}
	// 	}
	// 	if (isset($id_karyawan)) {
	// 		foreach ($id_karyawan as $k) {
	// 			if (isset($bobot[$k])) {
	// 				foreach ($bobot[$k] as $k_kpi => $v_bobot) {
	// 					$jenis_kpi=$jenis[$k][$k_kpi];
	// 					$bobot_jenis=$bobot_jenis_kpi[$k][$k_kpi][$jenis_kpi];
	// 					$pack['bobot_jenis'][$jenis_kpi]=$this->exam->hitungBobot($bobot_jenis);
	// 					$d_kpi=$this->model_master->getKpiKode($k_kpi);
	// 					$pack['rata_na'][$jenis_kpi][$k_kpi]=$this->exam->hitungAverageArray($na[$k][$k_kpi][$jenis_kpi]);
	// 					if (isset($d_kpi['cara_menghitung'])) {
	// 						if ($d_kpi['cara_menghitung'] == 'SUM') {
	// 							$pack['rata_na'][$jenis_kpi][$k_kpi]=$this->rumus->rumus_sum($na[$k][$k_kpi][$jenis_kpi]);
	// 						}
	// 					}						
	// 					$pack['na']['nilai'][$jenis_kpi]=array_sum($pack['rata_na'][$jenis_kpi])*$pack['bobot_jenis'][$jenis_kpi];
	// 					$pack['na']['capaian'][$jenis_kpi]=array_sum($pack['rata_na'][$jenis_kpi]);
	// 					$pack['nilai_akhir']=array_sum($pack['na']['nilai']);
	// 				}
	// 			}				
	// 		}
	// 	}		
	// 	return $pack;
	// }
	public function updateAgendaFromConceptMaster($data,$usage,$where = [],$cconcept = null)
	{
		$ret=false;
		if (empty($data) || empty($usage)) 
			return $ret;		
		$data_agenda=$this->getAgendaKpiValidate(0);
		if (isset($data_agenda)) {
			foreach ($data_agenda as $d) {
				if ($this->db->table_exists($d->nama_tabel)) {
					if ($usage == 'master') {
						if (isset($data['kode_kpi']) || isset($data['id_jenis_batasan_poin'])) {
							if (isset($data['id_jenis_batasan_poin']) && !isset($data['kode_kpi'])) {
								$where=['id_jenis_batasan_poin'=>$data['id_jenis_batasan_poin']];
							}else{
								$where=['kode_kpi'=>$data['kode_kpi']];
							}
							$this->model_global->updateQueryNoMsg($data,$d->nama_tabel,$where);
							$ret=true;
						}						
					}elseif ($usage == 'add_concept' && (!empty($cconcept) && $cconcept == $d->kode_c_kpi)) {
						$cek=$this->model_agenda->openTableAgendaIdCode($d->nama_tabel,$data['id_karyawan'],$data['kode_kpi'],'a.kode_kpi');
						if ($cek){
							$this->model_global->updateQueryNoMsg($data,$d->nama_tabel,['id_karyawan'=>$data['id_karyawan'],'kode_kpi'=>$data['kode_kpi']]);
						}else{
							$this->model_global->insertQueryNoMsg($data,$d->nama_tabel);
						}						
						$ret=true;
					}elseif ($usage == 'edit_concept' && (!empty($cconcept) && $cconcept == $d->kode_c_kpi)) {
						$where=(count($where) > 0)?$where:['kode_kpi'=>$data['kode_kpi'],'id_karyawan'=>$data['id_karyawan']];
						if (isset($data['kode_jabatan'])) {
							$where['kode_jabatan']=$data['kode_jabatan'];
							if (isset($data['kode_kpi'])){
								$where['kode_kpi']=$data['kode_kpi'];
							}
						}
						$this->model_global->updateQueryNoMsg($data,$d->nama_tabel,$where);
						$ret=true;
					}elseif ($usage == 'delete_concept' && (!empty($cconcept) && $cconcept == $d->kode_c_kpi)) {
						$where=(count($where) > 0)?$where:['kode_kpi'=>$data['kode_kpi'],'id_karyawan'=>$data['id_karyawan']];
						$this->model_global->deleteQueryNoMsg($d->nama_tabel,$where);
						$ret=true;
					}elseif ($usage == 'update_other' && (!empty($cconcept) && $cconcept == $d->kode_c_kpi)) {
						$this->model_global->updateQueryNoMsg($data,$d->nama_tabel,$where);
						$ret=true;
					}
				}
			}
		}
		return $ret;
	}
	public function syncBobotTertimbangKpi($table,$navl=0)
	{
		$ret=false;
		if (!$table) 
			return $ret;
		$agenda=$this->getAgendaKpiTable($table);
		if($agenda){
			$max_month=$this->otherfunctions->max_month_agenda($agenda);
			$wh=null;
			for ($i=1; $i <=$max_month ; $i++) { 
				$wh.="nr".$i." IS NULL";
				if ($i < $max_month) {
					$wh.=" OR ";
				}
			}
			// echo '<pre>';
			// print_r($navl);
			// print_r($wh);
			// $data_table=$this->db->get_where($table,$wh)->result();
			// print_r($data_table);
			if ($wh){
				$data_table=$this->db->get_where($table,$wh)->result();
				if($data_table){
					foreach ($data_table as $d){
						$data=['not_available'=>$navl];
						$where=['id_task'=>$d->id_task];
						$this->model_global->updateQueryNoMsg($data,$table,$where);
						$ret=true;
					}
				}else{
					$data_tablex=$this->db->get($table)->result();
					foreach ($data_tablex as $dx){
						$datax=['not_available'=>null];
						$wherex=['id_task'=>$dx->id_task];
						$this->model_global->updateQueryNoMsg($datax,$table,$wherex);
						$ret=true;
					}
				}
			}
		}
		return $ret;
	}
//--------------------------------------------------------------------------------------------------------------//
//Log Agenda Kpi
	public function getListLogAgendaKpi($active = false)
	{
		$this->db->select('a.*,b.nama as nama_konsep,c.nama as nama_periode, c.start, c.end,c.batas as batas');
		$this->db->from('log_agenda_kpi AS a');
		$this->db->join('master_periode_penilaian AS c', 'c.kode_periode = a.periode', 'left'); 
		$this->db->join('concept_kpi AS b', 'b.kode_c_kpi = a.kode_c_kpi', 'left'); 
		if ($active) {
			$this->db->where('a.status',1);
		}else{
			$this->db->order_by('update_date','DESC');
		}	
		
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaKpi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('log_agenda_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('id_l_a_kpi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaKpiPeriode($periode,$tahun,$validate=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('log_agenda_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('a.periode',$periode); 
		$this->db->where('a.tahun',$tahun); 
		$this->db->where('a.status',1); 
		if ($validate) {
			$this->db->where('a.validasi',1); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaKpiTahun($tahun,$validate=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('log_agenda_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left'); 
		$this->db->where('a.tahun',$tahun);
		$this->db->where('a.status',1); 
		if ($validate) {
			$this->db->where('a.validasi',1); 
		} 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaKpiKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('log_agenda_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('kode_l_a_kpi',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getLogAgendaKpiKodeLink($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,e.nama as nama_periode, e.start, e.end,e.batas as batas');
		$this->db->from('log_agenda_kpi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('a.kode_a_kpi',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function checkLogAgendaKpiCode($code)
	{
		return $this->model_global->checkCode($code,'log_agenda_kpi','kode_l_a_kpi');
	}
//--------------------------------------------------------------------------------------------------------------//
//Agenda Aspek Sikap
	//be carefull to use this function
	public function updateFromConceptSikap($data,$usage)
	{
		$ret=false;
		if (empty($data)) 
			return $ret;
		$data_agenda=$this->getAgendaSikapValidate(0);
		if (isset($data_agenda)) {
			foreach ($data_agenda as $d) {
				if ($this->db->table_exists($d->nama_tabel)) {
					if ($usage == 'partisipan_add') {
						$ret=$this->model_global->updateQueryNoMsg($data,$d->nama_tabel,['id_karyawan'=>$data['id_karyawan']]);
					}elseif ($usage == 'partisipan_edit') {
						$data_db=$this->openTableAgendaIdEmployee($d->nama_tabel,$data['id_karyawan']);
						$bobot=['ats'=>$data['bobot_ats'],'bwh'=>$data['bobot_bwh'],'rkn'=>$data['bobot_rkn']];
						$nilai=$this->exam->editNilaiPartisipantDbOne($data['id_partisipan'],$data['new'],$data['old'],$data_db,$bobot);
						if (isset($nilai)) {
							foreach ($nilai as $k_kuis => $val) {
								$this->model_global->updateQueryNoMsg($val,$d->nama_tabel,['id_karyawan'=>$data['id_karyawan'],'kode_kuisioner'=>$k_kuis]);
							}
						}
						$nilai_del=$this->exam->delNilaiPartisipantDb($data['partisipan_del'],$data_db,$bobot);
						if (isset($nilai_del)) {
							foreach ($nilai_del as $k_kuis_del => $val_del) {
								$this->model_global->updateQueryNoMsg($val_del,$d->nama_tabel,['id_karyawan'=>$data['id_karyawan'],'kode_kuisioner'=>$k_kuis_del]);
							}
						}
						$data_new=[
							'partisipan'=>$data['partisipan'],
							'sub_bobot_ats'=>$data['sub_bobot_ats'],
							'bobot_ats'=>$data['bobot_ats'],
							'bobot_bwh'=>$data['bobot_bwh'],
							'bobot_rkn'=>$data['bobot_rkn']
						];
						$ret=$this->model_global->updateQueryNoMsg($data_new,$d->nama_tabel,['id_karyawan'=>$data['id_karyawan']]);
					}elseif ($usage == 'partisipan_del') {
						$data_db=$this->openTableAgendaIdEmployee($d->nama_tabel,$data['id_karyawan']);
						$bobot=['ats'=>$data['bobot_ats'],'bwh'=>$data['bobot_bwh'],'rkn'=>$data['bobot_rkn']];
						$nilai_del=$this->exam->delNilaiPartisipantDb($data['partisipan_del'],$data_db,$bobot);
						if (isset($nilai_del)) {
							foreach ($nilai_del as $k_kuis_del => $val_del) {
								$this->model_global->updateQueryNoMsg($val_del,$d->nama_tabel,['id_karyawan'=>$data['id_karyawan'],'kode_kuisioner'=>$k_kuis_del]);
							}
						}
						$data_new=[
							'partisipan'=>$data['partisipan'],
							'sub_bobot_ats'=>$data['sub_bobot_ats'],
							'bobot_ats'=>$data['bobot_ats'],
							'bobot_bwh'=>$data['bobot_bwh'],
							'bobot_rkn'=>$data['bobot_rkn']
						];
						$ret=$this->model_global->updateQueryNoMsg($data_new,$d->nama_tabel,['id_karyawan'=>$data['id_karyawan']]);
					}elseif ($usage == 'karyawan_del') {
						$ret=$this->model_global->deleteQueryNoMsg($d->nama_tabel,['id_karyawan'=>$data['id_karyawan']]);
					}elseif($usage == 'karyawan_add'){
						$open_table=$this->model_concept->openTableViewConceptSikapEmpId($data['table'],$data['id_karyawan']);
						if (!empty($open_table)) {
							foreach ($open_table as $d_c) {
								$aspek_sikap=$this->otherfunctions->getParseVar($d_c->bobot_aspek);
								if (!empty($aspek_sikap) && !empty($d_c->partisipan)) {
									foreach ($aspek_sikap as $k_as => $v_as) {
										$kuisioner=$this->model_master->getKuisionerActive($k_as,$d_c->kode_tipe);
										if (!empty($kuisioner)) {
											foreach ($kuisioner as $k_k) {
												$data_new=[
													'id_karyawan'=>$d_c->id_karyawan,
													'kode_jabatan'=>$d_c->kode_jabatan,
													'kode_loker'=>$d_c->kode_loker,
													'partisipan'=>$d_c->partisipan,
													'bobot_ats'=>$d_c->bobot_ats,
													'bobot_bwh'=>$d_c->bobot_bwh,
													'bobot_rkn'=>$d_c->bobot_rkn,
													'sub_bobot_ats'=>$d_c->sub_bobot_ats,
													'kode_aspek'=>$k_as,
													'kode_kuisioner'=>$k_k->kode_kuisioner,
													'kuisioner'=>$k_k->kuisioner,
													'definisi'=>$k_k->definisi,
													'atas'=>$k_k->atas,
													'bawah'=>$k_k->bawah,
													'kode_tipe'=>$k_k->kode_tipe,
													'bobot'=>$v_as,											
												];
												for ($i=1; $i <=5 ; $i++) { 
													$po='poin_'.$i;
													$sa='satuan_'.$i;
													$data_new[$po]=$k_k->$po;
													$data_new[$sa]=$k_k->$sa;
												}
												$ret=$this->model_global->insertQueryNoMsg($data_new,$d->nama_tabel);
											}
										}
									}
								}						
							}
						}
					}
				}
			}
		}
		return $ret;
	}
	public function getListAgendaSikap($active = false)
	{
		$this->db->select('a.*,b.nama as nama_konsep,c.nama as nama_periode');
		$this->db->from('agenda_sikap AS a');
		$this->db->join('master_periode_penilaian AS c', 'c.kode_periode = a.periode', 'left'); 
		$this->db->join('concept_sikap AS b', 'b.kode_c_sikap = a.kode_c_sikap', 'left'); 
		if ($active) {
			$this->db->order_by('a.status',1);
		}	
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaSikapValidate($validate = 1)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,f.nama as nama_periode');
		$this->db->from('agenda_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left'); 
		$this->db->join('master_periode_penilaian AS f', 'f.kode_periode = a.periode', 'left');
		$this->db->where('a.validasi',$validate); 
		$this->db->where('a.status',1); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaSikap($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,f.nama as nama_periode');
		$this->db->from('agenda_sikap AS a');
		$this->db->join('master_periode_penilaian AS f', 'f.kode_periode = a.periode', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left');
		$this->db->where('id_a_sikap',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaSikapKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,f.nama as nama_periode');
		$this->db->from('agenda_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left'); 
		$this->db->join('master_periode_penilaian AS f', 'f.kode_periode = a.periode', 'left');
		$this->db->where('kode_a_sikap',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getAgendaSikapKodeKonsep($kode_konsep)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,f.nama as nama_periode');
		$this->db->from('agenda_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left'); 
		$this->db->join('master_periode_penilaian AS f', 'f.kode_periode = a.periode', 'left');
		$this->db->where('kode_c_sikap',$kode_konsep); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkAgendaSikapCode($code)
	{
		return $this->model_global->checkCode($code,'agenda_sikap','kode_a_sikap');
	}
	function getTabelSikapId($tabel,$id,$kode_aspek){
		return $this->db->get_where($tabel,array('id_karyawan'=>$id,'kode_aspek'=>$kode_aspek))->result();
	}
	function getTabelSikapAll($tabel,$id){
		return $this->db->get_where($tabel,array('id_karyawan'=>$id))->result();
	}
	function getTabelSikap($tabel){
		$this->db->select('a.*');
		$this->db->from($tabel.' AS a');
		$this->db->group_by('a.id_karyawan');
		$query=$this->db->get()->result();
		return $query;
	}
	public function generateAgendaSikap($c,$name,$periode)
	{
		if (empty($c) || empty($name)) 
			return false;
		$ret=false;
		$arr_start=[
			'id_task' => ['type' => 'BIGINT','constraint' => 255,'unsigned' => TRUE,'auto_increment' => TRUE],
			'id_karyawan' => ['type' => 'BIGINT','constraint' => 255,'null'=> TRUE],
			'kode_jabatan' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'kode_loker' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'partisipan' => ['type' => 'LONGTEXT','null'=> TRUE],
			'bobot_ats' => ['type' => 'FLOAT','null'=> TRUE],
			'bobot_rkn' => ['type' => 'FLOAT','null'=> TRUE],
			'bobot_bwh' => ['type' => 'FLOAT','null'=> TRUE],
			'sub_bobot_ats' => ['type' => 'TEXT','null'=> TRUE],
			'kode_aspek' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'kode_kuisioner' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
			'kuisioner' => ['type' => 'TEXT','null'=> TRUE],
			'definisi' => ['type' => 'TEXT','null'=> TRUE],
			'atas' => ['type' => 'FLOAT','null'=> TRUE],
			'bawah' => ['type' => 'FLOAT','null'=> TRUE],
			'kode_tipe' => ['type' => 'VARCHAR','constraint' => 300,'null'=> TRUE],
		];
		$arr_poin=[];
		for ($i=1; $i <=5 ; $i++) { 
			$arr_poin['poin_'.$i] = ['type' => 'FLOAT','null'=> TRUE];
			$arr_poin['satuan_'.$i] = ['type' => 'TEXT','null'=> TRUE];
		}
		$arr_end = [
			'bobot' => ['type' => 'FLOAT','null'=> TRUE],
			'nilai_atas' => ['type' => 'TEXT','null'=> TRUE],
			'rata_atas' => ['type' => 'FLOAT','null'=> TRUE],
			'na_atas' => ['type' => 'FLOAT','null'=> TRUE],
			'keterangan_atas' => ['type' => 'TEXT','null'=> TRUE],
			'nilai_bawah' => ['type' => 'TEXT','null'=> TRUE],
			'rata_bawah' => ['type' => 'FLOAT','null'=> TRUE],
			'na_bawah' => ['type' => 'FLOAT','null'=> TRUE],
			'keterangan_bawah' => ['type' => 'TEXT','null'=> TRUE],
			'nilai_rekan' => ['type' => 'TEXT','null'=> TRUE],
			'rata_rekan' => ['type' => 'FLOAT','null'=> TRUE],
			'na_rekan' => ['type' => 'FLOAT','null'=> TRUE],
			'keterangan_rekan' => ['type' => 'TEXT','null'=> TRUE],
			'nilai_diri' => ['type' => 'TEXT','null'=> TRUE],
			'rata_diri' => ['type' => 'FLOAT','null'=> TRUE],
			'na_diri' => ['type' => 'FLOAT','null'=> TRUE],
			'keterangan_diri' => ['type' => 'TEXT','null'=> TRUE],
			'na' => ['type' => 'FLOAT','null'=> TRUE],
			'na_kalibrasi' => ['type' => 'FLOAT','null'=> TRUE],
			'final_result' => ['type' => 'FLOAT','null'=> TRUE],
		];
		$cols=array_merge($arr_start,$arr_poin,$arr_end);
		$in=$this->model_global->createTable($name,$cols,'id_task');
		if ($in) {
			$table_name=$this->model_concept->getKonsepSikapKode($c)['nama_tabel'];
			if (!empty($table_name)) {
				$open_table=$this->model_concept->openTableConceptSikap($table_name);
				if (!empty($open_table)) {
					foreach ($open_table as $d) {
						$aspek_sikap=$this->otherfunctions->getParseVar($d->bobot_aspek);
						if (!empty($aspek_sikap) && !empty($d->partisipan)) {
							foreach ($aspek_sikap as $k_as => $v_as) {
								$kuisioner=$this->model_master->getKuisionerActive($k_as,$d->kode_tipe);
								if (!empty($kuisioner)) {
									foreach ($kuisioner as $k_k) {
										$data=[
											'id_karyawan'=>$d->id_karyawan,
											'kode_jabatan'=>$d->kode_jabatan,
											'kode_loker'=>$d->kode_loker,
											'partisipan'=>$d->partisipan,
											'bobot_ats'=>$d->bobot_ats,
											'bobot_ats'=>$d->bobot_ats,
											'bobot_bwh'=>$d->bobot_bwh,
											'bobot_rkn'=>$d->bobot_rkn,
											'sub_bobot_ats'=>$d->sub_bobot_ats,
											'kode_aspek'=>$k_as,
											'kode_kuisioner'=>$k_k->kode_kuisioner,
											'kuisioner'=>$k_k->kuisioner,
											'definisi'=>$k_k->definisi,
											'atas'=>$k_k->atas,
											'bawah'=>$k_k->bawah,
											'kode_tipe'=>$k_k->kode_tipe,
											'bobot'=>$v_as,											
										];
										for ($i=1; $i <=5 ; $i++) { 
											$po='poin_'.$i;
											$sa='satuan_'.$i;
											$data[$po]=$k_k->$po;
											$data[$sa]=$k_k->$sa;
										}
										$this->model_global->insertQuery($data,$name);
									}
								}
							}
						}						
					}
					$ret=true;
				}
			}
		}
		return $ret;
	}
	public function rumusCustomKubotaFinalResultSikap($table,$id,$usage = 'list')
	{
		if (empty($table) || empty($id) || !$this->db->table_exists($table))
			return null;
		$pack=[];
		$data=$this->openTableAgendaIdEmployee($table,$id);
		if (isset($data)) {
			foreach ($data as $d) {
				$id_karyawan[$d->id_karyawan]=$d->id_karyawan;
				$bobot_db[$d->id_karyawan]['ATS']=$d->bobot_ats;
				$bobot_db[$d->id_karyawan]['BWH']=$d->bobot_bwh;
				$bobot_db[$d->id_karyawan]['RKN']=$d->bobot_rkn;
				$nilai_kalibrasi[$d->id_karyawan]=$d->na_kalibrasi;
			//$sub_bobot_ats[$d->id_karyawan]=$d->sub_bobot_ats;
				$partisipan[$d->id_karyawan]=$d->partisipan;
				$bobot_aspek[$d->id_karyawan][$d->kode_aspek]=$d->bobot;
				$na_db[$d->id_karyawan]['ATS'][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_atas;
				$na_db[$d->id_karyawan]['BWH'][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_bawah;
				$na_db[$d->id_karyawan]['RKN'][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_rekan;
				if ($usage == 'advance_list') {
					$nilai_db[$d->id_karyawan]['ATS'][$d->kode_aspek][$d->kode_kuisioner]=$d->nilai_atas;
					$nilai_db[$d->id_karyawan]['BWH'][$d->kode_aspek][$d->kode_kuisioner]=$d->nilai_bawah;
					$nilai_db[$d->id_karyawan]['RKN'][$d->kode_aspek][$d->kode_kuisioner]=$d->nilai_rekan;
				// $nilai_diri[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->nilai_diri;
				}elseif ($usage == 'report') {
					$rata[$d->id_karyawan]['ATS'][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_atas;
					$rata[$d->id_karyawan]['BWH'][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_bawah;
					$rata[$d->id_karyawan]['RKN'][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_rekan;
				// $rata[$d->id_karyawan]['DRI'][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_diri;
				}			
			}	
			if (isset($id_karyawan)) {
				foreach ($id_karyawan as $k) {
				//other property
					if (isset($partisipan[$k])) {
						$pack['head_column']=$this->exam->getPartisipanColumnTable($partisipan[$k]);
						if ($usage == 'advance_list') {
							$pack['status_detail']=$this->exam->getPartisipantStatusList($partisipan[$k],$nilai_db[$k]);
						}					
					}

					if (isset($bobot_aspek[$k])) {
						$na=0;
						foreach ($bobot_aspek[$k] as $k_asp => $val) {
						//usages
							if ($usage == 'report') {
								$pack['bobot_aspek'][$k_asp]=$val;
								$d_aspek=$this->model_master->getAspekKode($k_asp);
								if (isset($d_aspek)) {
									$pack['list_aspek'][$k_asp]=$d_aspek['nama'];
								}
								if (isset($pack['head_column'])) {
									foreach ($pack['head_column'] as $head_column) {
										$pack['capaian'][$k_asp][$head_column]=(isset($rata[$k][$head_column][$k_asp]))?$this->exam->hitungAverageArray($rata[$k][$head_column][$k_asp]):0;
										$pack['bobot_column'][$head_column]=(isset($bobot_db[$k][$head_column]))?$bobot_db[$k][$head_column]:0;
									}
								}
							}			

						//counting value
							$pack['nilai_aspek'][$k_asp]=0;	
							if (isset($pack['head_column'])) {
								foreach ($pack['head_column'] as $head_column) {
									if (isset($na_db[$k][$head_column][$k_asp])) {
										$name_index=$this->exam->getWhatColPartisipan($head_column,'na');
										$nilai_aspek[$name_index][$k_asp]=($this->exam->hitungAverageArray($na_db[$k][$head_column][$k_asp]))*($val/100);
										$na=$na+($nilai_aspek[$name_index][$k_asp])*(($bobot_db[$k][$head_column])?($bobot_db[$k][$head_column]/100):0);
										$pack['nilai_aspek'][$k_asp]=$pack['nilai_aspek'][$k_asp]+$nilai_aspek[$name_index][$k_asp];
										if ($usage == 'report') {
											$pack[$name_index][$k_asp]=$nilai_aspek[$name_index][$k_asp];
											$pack[$k_asp][$name_index]=($nilai_aspek[$name_index][$k_asp])*(($bobot_db[$k][$head_column])?($bobot_db[$k][$head_column]/100):0);
										}
									}
								}
							}
						}
						if ($usage == 'report') {
							if (isset($pack['head_column'])) {
								foreach ($pack['head_column'] as $head_column) {
									$name_index=$this->exam->getWhatColPartisipan($head_column,'na');
									$pack['sum_value'][$head_column]=(isset($pack[$name_index]))?array_sum($pack[$name_index]):0;
									$pack['value_bobot'][$head_column]=$pack['sum_value'][$head_column]*(($bobot_db[$k][$head_column])?($bobot_db[$k][$head_column]/100):0);
								}
							}
						}
						$pack['nilai_kalibrasi']=$nilai_kalibrasi[$k];
						$pack['nilai_akhir']=$na;
					}
				}
			}
		}				
		return $pack;
	}
	public function ResultSikapKuisioner($kode,$filter=null)
	{
		$agenda=$this->getAgendaSikapKode($kode);
		$table=$agenda['nama_tabel'];
		if (empty($table) || !$this->db->table_exists($table))
			return [];
		$pack=[];
		$data=$this->openTableAgenda($table);
		if (isset($data)) {
			foreach ($data as $d) {
				$aspek=$this->model_master->getAspekKode($d->kode_aspek);
				$penilai=null;
				$keterangan=null;
				$pn=$this->otherfunctions->getParseVar($d->partisipan);
				if (isset($pn)) {
					$cn=1;
					foreach ($pn as $sbg=>$id_emp) {
						$emp=$this->model_karyawan->getEmployeeId($id_emp);
						if (isset($emp)) {
							$penilai.='['.$this->exam->getWhatIsPartisipan($sbg,['icon'=>false]).'] '.$emp['nama'];
							if ($cn < count($pn)) {
								$penilai.="\n";
							}
							$cn++;
						}						
					}
				}
				$ket=array_filter([$d->keterangan_atas,$d->keterangan_bawah,$d->keterangan_rekan]);
				$ket=$this->otherfunctions->getParseVar(implode(';',$ket));
				if (isset($ket)) {
					$cn_ket=1;
					foreach ($ket as $id_emp=>$ktr) {
						$emp=$this->model_karyawan->getEmployeeId($id_emp);
						if (isset($emp)) {
							$keterangan.="[".$emp['nama']."] ".$ktr;
							if ($cn_ket < count($ket)) {
								$keterangan.="\n";
							}
							$cn_ket++;
						}						
					}
				}
				$pack[$d->id_karyawan][$d->kode_kuisioner]=[
					'nik'=>$d->nik,
					'nama'=>$d->nama,
					'jabatan'=>$d->nama_jabatan,
					'loker'=>$d->nama_loker,
					'bagian'=>$d->bagian,
					'departement'=>$d->nama_departemen,
					'aspek'=>$aspek['nama'],
					'penilai'=>$penilai,
					'kuisioner'=>$d->kuisioner,
					'keterangan'=>$keterangan,
					'nilai_atas'=>$d->na_atas,
					'nilai_bawah'=>$d->na_bawah,
					'nilai_rekan'=>$d->na_rekan,
					'nilai_akhir'=>$this->rumus->rumus_avg([$d->na_atas,$d->na_bawah,$d->na_rekan]),
				];
			}
		}
		return $pack;		
	}
	public function reportValueSikap($val,$bbt)
	{
		if(empty($val))
			return null;

		$n1=1;
		foreach ($val as $aa) {
			if ($aa != 0) {
				$sa[$n1]=$aa;
			}
			$n1++;
		}
		if (isset($sa)) {
			$r_atas=array_sum($val)/count($sa);
			$atas=$r_atas*$bbt;
		}else{
			$atas=0;
			$r_atas=0;
		}

		$new_val = [
			'pss'=>$atas,
			'r_pss'=>$r_atas
		];
		return $new_val;
	}
	public function reportDetailSikap($n_val,$k_val,$pp1)
	{
		$nx_n=array_filter(explode(';', $n_val));
		foreach ($nx_n as $nn) {
			$ne_n=explode(':', $nn);
			$ni_n[$ne_n[0]]=$ne_n[1];
		}
		$nx1_n=array_filter(explode(';', $k_val));
		foreach ($nx1_n as $nn1) {
			$ne1_n=explode(':', $nn1);
			$ni1_n[$ne1_n[0]]=$ne1_n[1];
		}
		if (isset($ni_n[$pp1])) {
			$na_n=$ni_n[$pp1];
			if (isset($ni1_n[$pp1])) {
				$ke_n=$ni1_n[$pp1];
			}else{
				$ke_n='<label class="label label-default">Tidak Ada Komentar</label>';
			}
		}else{
			$na_n=0;
			$ke_n='<label class="label label-default">Tidak Ada Komentar</label>';
		}
		$new_val = [
			'na' =>$na_n,
			'ke' =>$ke_n
		];
		return $new_val;
	}
	public function updateAgendaSikapFromConceptMaster($data,$usage,$where = [],$cconcept = null)
	{
		$ret=false;
		if (empty($data) || empty($usage)) 
			return $ret;		
		$data_agenda=$this->getAgendaSikapValidate(0);
		if (isset($data_agenda)) {
			foreach ($data_agenda as $d) {
				if ($this->db->table_exists($d->nama_tabel)) {
					if ($usage == 'master' && isset($data['kode_kuisioner'])) {
						if (isset($data['kode_periode'])) {
							unset($data['kode_periode']);
						}
						$this->model_global->updateQueryNoMsg($data,$d->nama_tabel,['kode_kuisioner'=>$data['kode_kuisioner']]);
						$ret=true;
					}elseif ($usage == 'master_form_aspek' && !empty($data)) {
						$data=$this->otherfunctions->getParseVar($data);
						if (isset($data)) {
							foreach ($data as $k_asp => $bobot){
								$this->model_global->updateQueryNoMsg(['bobot'=>$bobot],$d->nama_tabel,['kode_aspek'=>$k_asp]);
							}
						}
						$ret=true;
					}elseif ($usage == 'add_concept' && (!empty($cconcept) && $cconcept == $d->kode_c_sikap)) {
						$this->model_global->insertQueryNoMsg($data,$d->nama_tabel);
						$ret=true;
					}elseif ($usage == 'edit_concept' && (!empty($cconcept) && $cconcept == $d->kode_c_sikap)) {
						$where=(count($where) > 0)?$where:['id_karyawan'=>$data['id_karyawan']];
						$this->model_global->updateQueryNoMsg($data,$d->nama_tabel,$where);
						$ret=true;
					}elseif ($usage == 'delete_concept' && (!empty($cconcept) && $cconcept == $d->kode_c_sikap)) {
						$where=(count($where) > 0)?$where:['id_karyawan'=>$data['id_karyawan']];
						$this->model_global->deleteQueryNoMsg($d->nama_tabel,$where);
						$ret=true;
					}
				}
			}
		}
		return $ret;
	}
	public function dataAgendaSikapToConcept($table)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('id_karyawan,kode_jabatan,kode_loker,partisipan,bobot_ats,bobot_bwh,bobot_rkn,sub_bobot_ats');
		$this->db->from($table);
		$this->db->group_by('id_karyawan');
		$query=$this->db->get()->result();
		return $query;	
	}
//--------------------------------------------------------------------------------------------------------------//
//Log Agenda Aspek Sikap
	public function getListLogAgendaSikap()
	{
		$this->db->select('a.*,b.nama as nama_konsep,c.nama as nama_periode');
		$this->db->from('log_agenda_sikap AS a');
		$this->db->join('master_periode_penilaian AS c', 'c.kode_periode = a.periode', 'left'); 
		$this->db->join('concept_sikap AS b', 'b.kode_c_sikap = a.kode_c_sikap', 'left'); 	
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaSikap($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,,e.nama as nama_periode');
		$this->db->from('log_agenda_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left'); 
		$this->db->where('id_l_a_sikap',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaSikapPeriode($periode,$tahun,$validate=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,,e.nama as nama_periode');
		$this->db->from('log_agenda_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left'); 
		$this->db->where('a.periode',$periode); 
		$this->db->where('a.tahun',$tahun); 
		$this->db->where('a.status',1); 
		if ($validate) {
			$this->db->where('a.validasi',1); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaSikapTahun($tahun,$validate=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,,e.nama as nama_periode');
		$this->db->from('log_agenda_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');  
		$this->db->where('a.tahun',$tahun); 
		$this->db->where('a.status',1); 
		if ($validate) {
			$this->db->where('a.validasi',1); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaSikapKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep');
		$this->db->from('log_agenda_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left'); 
		$this->db->where('kode_l_a_sikap',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getLogAgendaSikapKodeLink($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,e.nama as nama_periode');
		$this->db->from('log_agenda_sikap AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('a.kode_a_sikap',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function checkLogAgendaSikapCode($code)
	{
		return $this->model_global->checkCode($code,'log_agenda_sikap','kode_l_a_sikap');
	}
//--------------------------------------------------------------------------------------------------------------//
//Agenda Reward
	public function getListAgendaReward()
	{	
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get('agenda_reward ')->result();
		return $query;
	}
	public function getAgendaReward($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,e.nama as nama_periode');
		$this->db->from('agenda_reward AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('id_a_reward',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaRewardKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,e.nama as nama_periode');
		$this->db->from('agenda_reward AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('kode_a_reward',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function checkAgendaRewardCode($code)
	{
		return $this->model_global->checkCode($code,'agenda_reward','kode_a_reward');
	}
	public function getEmployeeReward($tabel,$filter=0)
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
	public function generateAgendaReward($name)
	{
		if (empty($name)) 
			return false;
		$ret=false;
		$cols = [
			'id_task' => [
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
			'kode_reward' => [
				'type' => 'TEXT',
				'null'=> TRUE
			],
			'na' => [
				'type' => 'FLOAT',
				'null'=> TRUE
			],
		];
		$in=$this->model_global->createTable($name,$cols,'id_task');
		if ($in) {
			$ret=true;
		}
		return $ret;
	}
	public function rumusCustomKubotaFinalResultReward($table,$id)
	{
		if (empty($table) || empty($id) || !$this->db->table_exists($table))
			return null;
		$pack=[];
		$data=$this->openTableAgendaIdEmployee($table,$id);
		foreach ($data as $d) {
			$param=$this->otherfunctions->getParseVar($d->kode_reward);
			if (isset($param)) {
				foreach ($param as $k_r => $v_r) {
					$pack['na'][$k_r]=$v_r;
				}
			}
			$pack['nilai_akhir']=$d->na;		
		}		
		return $pack;
	}

//--------------------------------------------------------------------------------------------------------------//
//Log Agenda Reward
	public function getListLogAgendaReward()
	{
		$this->db->select('a.*');
		$this->db->from('log_agenda_reward AS a');
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaRewardValidate($validate=1)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,e.nama as nama_periode');
		$this->db->from('agenda_reward AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('validasi',$validate); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaReward($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,e.nama as nama_periode');
		$this->db->from('log_agenda_reward AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('id_l_a_reward',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaRewardPeriode($periode,$tahun,$validate=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,e.nama as nama_periode');
		$this->db->from('log_agenda_reward AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left'); 
		$this->db->where('a.periode',$periode); 
		$this->db->where('a.tahun',$tahun);
		$this->db->where('a.status',1); 
		if ($validate) {
			$this->db->where('a.validasi',1); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaRewardTahun($tahun,$validate=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,e.nama as nama_periode');
		$this->db->from('log_agenda_reward AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('tahun',$tahun); 
		$this->db->where('a.status',1); 
		if ($validate) {
			$this->db->where('a.validasi',1); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogAgendaRewardKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,e.nama as nama_periode');
		$this->db->from('log_agenda_reward AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_periode_penilaian AS e', 'e.kode_periode = a.periode', 'left');
		$this->db->where('kode_l_a_reward',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function checkLogAgendaRewardCode($code)
	{
		return $this->model_global->checkCode($code,'log_agenda_reward','kode_l_a_reward');
	}
//===AGENDA Kompetensi BEGIN===//
//--------------------------------------------------------------------------------------------------------------//
//Agenda Kompetensi
	public function getListAgendaKompetensi()
	{
		$this->db->select('a.*,b.nama as nama_konsep');
		$this->db->from('agenda_kompetensi AS a');
		$this->db->join('concept_kompetensi AS b', 'b.kode_c_kompetensi = a.kode_c_kompetensi', 'left'); 	
		$this->db->order_by('update_date','DESC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaKompetensi($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep');
		$this->db->from('agenda_kompetensi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kompetensi AS d', 'd.kode_c_kompetensi = a.kode_c_kompetensi', 'left');
		$this->db->where('id_a_kompetensi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaKompetensiValidate($validate=1)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep');
		$this->db->from('agenda_kompetensi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kompetensi AS d', 'd.kode_c_kompetensi = a.kode_c_kompetensi', 'left');
		$this->db->where('validasi',$validate); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaKompetensiKode($kode)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep, e.start, e.end,e.batas as batas');
		$this->db->from('agenda_kompetensi AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('concept_kompetensi AS d', 'd.kode_c_kompetensi = a.kode_c_kompetensi', 'left'); 
		$this->db->where('kode_a_kompetensi',$kode); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function checkAgendaKompetensiCode($code)
	{
		return $this->model_global->checkCode($code,'agenda_kompetensi','kode_a_kompetensi');
	}
	public function openTableWithJenisKompetensi($table,$jenis,$id)
	{
		if (empty($jenis) || empty($id) || empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker, d.nama as bagian');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  
		$this->db->where('a.jenis',$jenis);		
		$this->db->where('a.id_karyawan',$id);		
		$query=$this->db->get()->result();
		return $query;
	}
	public function generateAgendaKompetensi($c,$name)
	{
		if (empty($c) || empty($name)) 
			return false;
		$ret=false;
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
			'id_task' => [
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
			'pn' => [
				'type' => 'TEXT',
				'null'=> TRUE
			],
			'nr' => [
				'type' => 'FLOAT',
				'null'=> TRUE
			],
			'na' => [
				'type' => 'FLOAT',
				'null'=> TRUE
			],
			// 'status' => [
			// 	'type' => 'TINYINT',
			// 	'constraint' => 1,
			// 	'null'=> TRUE
			// ],
			// 'create_date' => [
			// 	'type' => 'DATETIME',
			// 	'null'=> TRUE
			// ],
			// 'update_date' => [
			// 	'type' => 'DATETIME',
			// 	'null'=> TRUE
			// ],
			// 'create_by' => [
			// 	'type' => 'BIGINT',
			// 	'constraint' => 255,
			// 	'null'=> TRUE
			// ],
			// 'update_by' => [
			// 	'type' => 'BIGINT',
			// 	'constraint' => 255,
			// 	'null'=> TRUE
			// ],
		];
		$cols=array_merge($cols,$cols2);
		$in=$this->model_global->createTable($name,$cols,'id_task');
		if ($in) {
			$table_name=$this->model_concept->getKonsepKompetensiKode($c)['nama_tabel'];
			if (!empty($table_name)) {
				$open_table=$this->model_concept->openTableConceptKompetensi($table_name);
				if (!empty($open_table)) {
					foreach ($open_table as $d) {
						$data=[
							'id_karyawan'=>$d->id_karyawan,
							'kode_jabatan'=>$d->kode_jabatan,
							'kode_loker'=>$d->kode_loker,
							'kode_kompetensi'=>$d->kode_kompetensi,
							'kompetensi'=>$d->kompetensi,
							'definisi'=>$d->definisi,
							'kaitan'=>$d->kaitan,
							'unit'=>$d->unit,
							'jenis_satuan'=>$d->jenis_satuan,
							'jenis'=>$d->jenis,
							'sifat'=>$d->sifat,
							'bobot_jenis_kompetensi'=>$d->bobot_jenis_kompetensi,
							'bobot'=>$d->bobot,
							'penilai'=>$d->penilai,
						];
						for ($i=1; $i <=10 ; $i++) { 
							$po='poin_'.$i;
							$sa='satuan_'.$i;
							$data[$po]=$d->$po;
							$data[$sa]=$d->$sa;
						}
						$this->model_global->insertQuery($data,$name);
					}
					$ret=true;
				}
			}
		}
		return $ret;
	}
//--------------------------------------------------------------------------------------------------------------//
//Report Group
	public function getReportGroupList()
	{
		$kpi=$this->getListLogAgendaKpi();
		$sikap=$this->getListLogAgendaSikap();
		$reward=$this->getListLogAgendaReward();
		$pack=[];
		foreach ($kpi as $k) {
			array_push($pack,$k->tahun);
		}
		foreach ($sikap as $s) {
			array_push($pack,$s->tahun);
		}
		foreach ($reward as $r) {
			array_push($pack,$r->tahun);
		}
		return array_unique($pack);
	}
	public function getListEmployeeReportGroup($kode, $data_form = null)
	{
	//kode is array
		$pack=[];
		if (!isset($kode)){
			if (!isset($kode['tahun'])) {
				return $pack;
			}
			return $pack;
		} 	
		$emp=[];	
		$table=[];		
		$usage='kuartal';
		if(isset($kode['kode_periode'])){
			$kpi=$this->getLogAgendaKpiPeriode($kode['kode_periode'],$kode['tahun']);
			$sikap=$this->getLogAgendaSikapPeriode($kode['kode_periode'],$kode['tahun']);
			// $reward=$this->getLogAgendaRewardPeriode($kode['kode_periode'],$kode['tahun']);
		}elseif (isset($kode['tahun']) && !isset($kode['kode_periode'])) {
			$kpi=$this->getLogAgendaKpiTahun($kode['tahun'],true);
			$sikap=$this->getLogAgendaSikapTahun($kode['tahun'],true);
			// $reward=$this->getLogAgendaRewardTahun($kode['tahun'],true);
			$kode['kode_periode']=null;
			$usage='tahun';
		}
		if (isset($kpi)) {
			foreach ($kpi as $kp) {
				array_push($table,$kp->nama_tabel.';kpi');
			}
		}
		if (isset($sikap)) {
			foreach ($sikap as $sk) {
				array_push($table,$sk->nama_tabel.';sikap');
			}
		}
		// if (isset($reward)) {
		// 	foreach ($reward as $rw) {
		// 		array_push($table,$rw->nama_tabel.';reward');
		// 	}
		// }
		if (isset($table)) {
			$emp=[];
			$table=array_filter($table);
			foreach ($table as $tb) {					
				$tb=$this->otherfunctions->getParseOneLevelVar($tb);
				if (isset($tb[0])) {						
					$data=$this->openTableAgenda($tb[0], $data_form);
					if (isset($data)) {
						foreach ($data as $d) {
							$kode['id']=$d->id_karyawan;
							$history_poin=$this->model_agenda->getListRaportTahunanHistory($kode);
							$p_old=($d->poin_old)?$d->poin_old:0;
							if ($d->poin_old < $d->poin && $d->poin_old) {
								$p_old=$d->poin_old+$d->poin_now;
							}
							$r_old=$d->nama_rank_old;
							$r_now=$d->nama_rank;
							if (isset($history_poin[0])){
								if($usage == 'tahun' || $usage == 'tahunan'){
									$p_old=$history_poin[0]->poin_old_tahun;
								}else{
									$cols_history='poin_old_'.$kode['kode_periode'];
									$p_old=$history_poin[0]->$cols_history;
								}
								$auto_rank='';
								if($history_poin[0]->auto_rank_up_old){
									$auto_rank.='<br><label class="label label-default">Rank Up Otomatis</label>';
								}
								$r_old=(($history_poin[0]->nama_rank_old)?$history_poin[0]->nama_rank_old.$auto_rank:$this->otherfunctions->getMark());
								$r_now=(($history_poin[0]->nama_rank)?$history_poin[0]->nama_rank:$this->otherfunctions->getMark());
							}
							
							$emp[$d->id_karyawan]=[$d->id_karyawan,
							$d->nik,
							$d->nama,
							$d->nama_jabatan,
							$d->bagian,
							$d->nama_departemen,
							$d->nama_loker,
							0, //kpi 	7
							0, //sikap		8
							0, //total		9
							0, //kedisiplinan		10
							0, //nilai_akhir11
							];
						}
					}
				}
			}
			if (isset($emp)){
				foreach ($emp as $k_e => $v_e) {
					$emp_data=$this->model_karyawan->getEmployeeId($k_e);
					$nilai_total[$k_e]=0;
					if (isset($table)) {
						foreach ($table as $l_tb) {
							$l_tb=$this->otherfunctions->getParseOneLevelVar($l_tb);
							if (isset($l_tb[0])) {
								if ($l_tb[1] == 'sikap') {
									$sikap=((isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']:0);
									if (isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi'])) {
										$sikap=((!empty($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi']:$sikap);
									}
									$v_e[8]=$v_e[8]+$sikap;
								}elseif ($l_tb[1] == 'kpi') {
									$kpi=((isset($this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e)['nilai_akhir']:0);
									$v_e[7]=$v_e[7]+$kpi;
								}
								// elseif ($l_tb[1] == 'reward') {
								// 	$reward=((isset($this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']:0);
								// 	// $nilai_total[$k_e]=$nilai_total[$k_e]+$reward;
								// 	$v_e[16]=$v_e[16]+$reward;
								// }
							}	 
						}
					}
					$v_e[10]=$this->model_presensi->rumusCustomKubotaFinalResultPresensi($k_e,$kode['kode_periode'],$kode['tahun'],$usage)['nilai_akhir'];
					$nilai_total[$k_e]=($v_e[7]*($emp_data['bobot_out']/100))+($v_e[8]*($emp_data['bobot_sikap']/100));
					$nilai_akhir[$k_e]=$nilai_total[$k_e]-$v_e[10];
					$v_e[7]=$this->formatter->getNumberFloat($v_e[7]);
					$v_e[8]=$this->formatter->getNumberFloat($v_e[8]);
					$v_e[9]=$this->formatter->getNumberFloat($nilai_total[$k_e]);
					$v_e[10]=$this->formatter->getNumberFloat($this->model_presensi->rumusCustomKubotaFinalResultPresensi($k_e,$kode['kode_periode'],$kode['tahun'],$usage)['nilai_akhir']);
					$v_e[11]=$this->formatter->getNumberFloat($nilai_akhir[$k_e]);
					$data_konversi=(($usage == 'kuartal')?$this->model_master->getKonversiKuartalNilai($nilai_akhir[$k_e]):$this->model_master->getKonversiTahunanNilai($nilai_akhir[$k_e]));
					$huruf=((isset($data_konversi['huruf']))?$data_konversi['huruf']:'Unknown');
					$kode['id']=$k_e;
					$v_e=array_merge($v_e,[$huruf,$this->codegenerator->encryptChar($kode),$this->codegenerator->encryptChar($k_e)]);
					$pack[]=$v_e;
				}
			}
		}
		return $pack;
	}
	//=========================================>>>>>>>>>>>>>>>>>>>>>>>>>>>old function don't delete this=========================================>>>>>>>>>>>>>>>>>>>>>>>>>>>
	// public function getListEmployeeReportGroup($kode, $data_form = null)
	// {
	// //kode is array
	// 	$pack=[];
	// 	if (!isset($kode)){
	// 		if (!isset($kode['tahun'])) {
	// 			return $pack;
	// 		}
	// 		return $pack;
	// 	} 	
	// 	$emp=[];	
	// 	$table=[];		
	// 	$usage='kuartal';
	// 	if(isset($kode['kode_periode'])){
	// 		$kpi=$this->getLogAgendaKpiPeriode($kode['kode_periode'],$kode['tahun']);
	// 		$sikap=$this->getLogAgendaSikapPeriode($kode['kode_periode'],$kode['tahun']);
			// $reward=$this->getLogAgendaRewardPeriode($kode['kode_periode'],$kode['tahun']);
	// 	}elseif (isset($kode['tahun']) && !isset($kode['kode_periode'])) {
	// 		$kpi=$this->getLogAgendaKpiTahun($kode['tahun']);
	// 		$sikap=$this->getLogAgendaSikapTahun($kode['tahun']);
	// 		$reward=$this->getLogAgendaRewardTahun($kode['tahun']);
	// 		$kode['kode_periode']=null;
	// 		$usage='tahun';
	// 	}
	// 	if (isset($kpi)) {
	// 		foreach ($kpi as $kp) {
	// 			array_push($table,$kp->nama_tabel.';kpi');
	// 		}
	// 	}
	// 	if (isset($sikap)) {
	// 		foreach ($sikap as $sk) {
	// 			array_push($table,$sk->nama_tabel.';sikap');
	// 		}
	// 	}
	// 	if (isset($reward)) {
	// 		foreach ($reward as $rw) {
	// 			array_push($table,$rw->nama_tabel.';reward');
	// 		}
	// 	}
	// 	if (isset($table)) {
	// 		$emp=[];
	// 		$table=array_filter($table);
	// 		foreach ($table as $tb) {					
	// 			$tb=$this->otherfunctions->getParseOneLevelVar($tb);
	// 			if (isset($tb[0])) {						
	// 				$data=$this->openTableAgenda($tb[0], $data_form);
	// 				if (isset($data)) {
	// 					foreach ($data as $d) {
	// 						$emp[$d->id_karyawan]=[$d->id_karyawan,$d->nik,$d->nama,$d->nama_jabatan,$d->bagian,$d->nama_loker];
	// 					}
	// 				}
	// 			}
	// 		}
	// 		if (isset($emp)){
	// 			foreach ($emp as $k_e => $v_e) {
	// 				$nilai_total[$k_e]=0;
	// 				if (isset($table)) {
	// 					foreach ($table as $l_tb) {
	// 						$l_tb=$this->otherfunctions->getParseOneLevelVar($l_tb);
	// 						if (isset($l_tb[0])) {
	// 							if ($l_tb[1] == 'kpi') {
	// 								$nilai_total[$k_e]=$nilai_total[$k_e]+((isset($this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e)['nilai_akhir']:0);
	// 							}elseif ($l_tb[1] == 'sikap') {
	// 								$nilai_total[$k_e]=$nilai_total[$k_e]+((isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']:0);
	// 							}elseif ($l_tb[1] == 'reward') {
	// 								$nilai_total[$k_e]=$nilai_total[$k_e]+((isset($this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']:0);
	// 							}
	// 						}	 
	// 					}
	// 				}
	// 				//presensi
	// 				$nilai_total[$k_e]=$nilai_total[$k_e]+$this->model_presensi->rumusCustomKubotaFinalResultPresensi($k_e,$kode['kode_periode'],$kode['tahun'],$usage)['nilai_akhir'];
	// 				array_push($v_e,$this->formatter->getNumberFloat($nilai_total[$k_e]));
	// 				$data_konversi=(($usage == 'kuartal')?$this->model_master->getKonversiKuartalNilai($nilai_total[$k_e]):$this->model_master->getKonversiTahunanNilai($nilai_total[$k_e]));
	// 				$huruf=((isset($data_konversi['huruf']))?$data_konversi['huruf']:'Unknown');
	// 				array_push($v_e,$huruf);
	// 				$kode['id']=$k_e;
	// 				array_push($v_e,$this->codegenerator->encryptChar($kode));						
	// 				$pack[]=$v_e;
	// 			}
	// 		}
	// 	}
	// 	return $pack;
	// }
	public function getReportGroupEmployee($periode,$tahun,$id)
	{
		$pack=['kpi'=>null,'sikap'=>null,'presensi'=>null,'bobot_kpi'=>0,'bobot_sikap'=>0];
		if (empty($tahun) || empty($id))
			return $pack;
		$table=[];
		if (!empty($periode)) {
			$kpi=$this->getLogAgendaKpiPeriode($periode,$tahun);
			$sikap=$this->getLogAgendaSikapPeriode($periode,$tahun);
			// $reward=$this->getLogAgendaRewardPeriode($periode,$tahun);
			$u_presensi='kuartal';
		}else{
			$kpi=$this->getLogAgendaKpiTahun($tahun,true);
			$sikap=$this->getLogAgendaSikapTahun($tahun,true);
			// $reward=$this->getLogAgendaRewardTahun($tahun,true);
			$u_presensi='tahunan';
		}		
		if (isset($kpi)) {
			foreach ($kpi as $kp) {
				array_push($table,$kp->nama_tabel.';kpi');
			}
		}
		if (isset($sikap)) {
			foreach ($sikap as $sk) {
				array_push($table,$sk->nama_tabel.';sikap');
			}
		}
		// if (isset($reward)) {
		// 	foreach ($reward as $rw) {
		// 		array_push($table,$rw->nama_tabel.';reward');
		// 	}
		// }
		if (isset($table)) {
			$table=array_filter($table);
			$nilai_sikap=0;
			$nilai_kpi=0;
			$emp=$this->model_karyawan->getEmployeeId($id);
			if (isset($emp)) {
				$pack['bobot_kpi']=$emp['bobot_out'];
				$pack['bobot_sikap']=$emp['bobot_sikap'];
			}
			foreach ($table as $tb) {					
				$tb=$this->otherfunctions->getParseOneLevelVar($tb);
				if (isset($tb[0])) {		
					if($tb[1] == 'sikap'){
						$data_sikap=$this->rumusCustomKubotaFinalResultSikap($tb[0],$id,'report');
						if (isset($data_sikap['nilai_aspek']) && isset($data_sikap['list_aspek'])) {	
							foreach ($data_sikap['nilai_aspek'] as $k_ds=>$v_ds) {
								$n_asp=(isset($data_sikap[$k_ds])?array_sum($data_sikap[$k_ds]):0);
								$pack['sikap'][$k_ds]['nilai'][]=$n_asp;
								$aspek=((isset($data_sikap['list_aspek'][$k_ds]))?$data_sikap['list_aspek'][$k_ds]:'Unknown');
								$pack['sikap'][$k_ds]['nama_aspek']=$aspek;
							}
							if (isset($data_sikap['nilai_kalibrasi'])) {
								if (!empty($data_sikap['nilai_kalibrasi'])) {
									$pack['kalibrasi_sikap']=['value_old'=>$data_sikap['nilai_akhir'],'value_kalibrasi'=>$data_sikap['nilai_kalibrasi']];
								}
								
							}
						}							
					}elseif ($tb[1] == 'kpi') {
						$data_kpi=$this->rumusCustomKubotaFinalResultKpi($tb[0],$id);
						if (isset($data_kpi['nilai_akhir'])) {
							$pack['kpi']=$data_kpi['nilai_akhir'];
						}
					}
					// elseif ($tb[1] == 'reward') {
					// 	$data_reward=$this->rumusCustomKubotaFinalResultReward($tb[0],$id);
					// 	if (isset($data_reward['na'])) {
					// 		foreach ($data_reward['na'] as $k_r => $v_r) {
					// 			$pack['reward'][$k_r][]=$v_r;
					// 		}
					// 	}
					// }												
				}
			}
		}
		// $pack['punishment']=$this->model_karyawan->getPoinSuratPeringatan($id,$tahun,$periode)['poin_pengurang'];
		$pack['presensi']=$this->model_presensi->rumusCustomKubotaFinalResultPresensi($id,$periode,$tahun,$u_presensi);
		return $pack;
	}
	public function getCountBobotJenisKpiWithMax($id,$periode,$tahun,$usage = 'periode')
	{
		if (empty($tahun) || empty($id))
			return $pack;
		$table=[];
		$var=['max'=>0,'agenda'=>0];
		if (!empty($periode)) {
			$kpi=$this->getLogAgendaKpiPeriode($periode,$tahun);
			$u_presensi='kuartal';
		}else{
			$kpi=$this->getLogAgendaKpiTahun($tahun);
			$u_presensi='tahunan';
		}
		if (isset($kpi)) {
			foreach ($kpi as $kp) {
				array_push($table,$kp->nama_tabel);
			}
		}
		if (isset($table)) {
			$table=array_filter($table);
			foreach($table as $tb){
				$this->db->select('a.bobot_jenis_kpi,a.jenis,MAX(k.max) as max,(max*(a.bobot_jenis_kpi/100)) as total');
				$this->db->from($tb.' AS a');
				$this->db->join('master_konversi_kpi AS k', 'k.jenis_kpi = a.jenis', 'left');
				$this->db->where('a.id_karyawan',$id); 
				$this->db->group_by('a.jenis'); 
				$query=$this->db->get()->result();
				if (isset($query)) {
					foreach ($query as $d) {
						$var['max']=$var['max']+$d->total;
					}
				}
				$var['agenda']++;
			}
		}
		return $var;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Sync Point Employee
	public function syncPoinEmployee($kode)
	{
		$ret=false;
		if (!isset($kode)){
			if (!isset($kode['tahun']) || !isset($kode['kode_periode'])) {
				return $ret;
			}
			return $ret;
		} 	
		$table=[];		
		$usage='kuartal';
		$kpi=$this->getLogAgendaKpiPeriode($kode['kode_periode'],$kode['tahun'],true);
		$sikap=$this->getLogAgendaSikapPeriode($kode['kode_periode'],$kode['tahun'],true);
		// $reward=$this->getLogAgendaRewardPeriode($kode['kode_periode'],$kode['tahun'],true);
		if ((count($kpi) > 0) && (count($sikap) > 0)) {
			$data=$this->getListEmployeeTalentPool($kode);
			if (isset($data)) {
				foreach ($data as $d){
					if (!empty($d[0])) {
						$emp=$this->model_karyawan->getEmployeeId($d[0]);
						if (isset($emp)) {
							$d_max_poin=$this->model_master->getGeneralSetting('PRUP');
							$val_masa_kerja=$this->otherfunctions->getMasaKerja($emp['tgl_masuk'],$this->otherfunctions->getDateNow(),'arr');
							$masa=null;
							$satuan=null;
							if($emp['rank_up_masa_kerja']){
								$ex_masa=explode(' ',$emp['rank_up_masa_kerja']);
								if(isset($ex_masa)){
									if (is_numeric($ex_masa[0])){
										$masa=$ex_masa[0];
										$satuan=$ex_masa[1];
									}else{
										$masa=$ex_masa[1];
										$satuan=$ex_masa[0];
									}
									if ($satuan == 'Bulan') {
										$satuan = 'month_all';
									}else{
										$satuan = 'year';
									}
								}
							}
							$masa_kerja=false;
							if (isset($val_masa_kerja[$satuan])) {
								if ($val_masa_kerja[$satuan] >= $masa) {
									$masa_kerja=true;
								}
							}
							$d[5]=(empty($d[5]))?0:$d[5];
							$p_now=($d[5]+$emp['poin_old']);
							$data_in=[
								'poin_old'=>$p_now,
								'poin_now'=>$d[5],
								'poin'=>$p_now,
							];
							$data_to_history=[
								'kode_periode'=>$kode['kode_periode'],
								'tahun'=>$kode['tahun'],
								'id_karyawan'=>$emp['id_karyawan'],
								'poin_now'=>$data_in['poin_now'],
								'poin'=>$data_in['poin'],
								'poin_old'=>$emp['poin_old'],
								'kode_jabatan'=>$emp['jabatan'],
								'kode_loker'=>$emp['loker'],
								'rank_now'=>$emp['grade'],
								'rank_old'=>$emp['grade_old'],
								'max_poin_rank_up'=>((!empty($emp['rank_up_poin_max']))?$emp['rank_up_poin_max']:0),
								'sisa'=>0,
								'rank_up'=>false,
							];
							if (($emp['auto_rank_up'] == 1) && (!empty($emp['rank_up_poin_max']))) {
								if ($p_now >= $emp['rank_up_poin_max'] && $masa_kerja) {
									$p_now=$p_now-$emp['rank_up_poin_max'];
									$data_in['grade']=$emp['rank_up'];
									$data_in['grade_old']=$emp['grade'];
									$data_in['poin']=0;
									$data_in['poin_old']=$p_now;
									$data_to_history['rank_up']=true;
									$data_to_history['rank_now']=$emp['rank_up'];
									$data_to_history['rank_old']=$emp['grade'];
									$data_to_history['sisa']=$p_now;
								}
							}
							$cek=$this->checkHostoryPoin($emp['id_karyawan'],$kode['kode_periode'],$kode['tahun']);
							if($cek){
								if (isset($cek['poin_old'])) {
									$data_to_history['poin_old']=$cek['poin_old'];
									$data_to_history['poin']=$cek['poin_old']+$data_in['poin_now'];
									$data_to_history['rank_up']=$cek['rank_up'];
									if (number_format($data_in['poin_now'],2) != number_format($cek['poin_now'],2)) {	
										if (($emp['auto_rank_up'] == 1) && (!empty($emp['rank_up_poin_max']))) {
											if ($data_to_history['poin'] >= $emp['rank_up_poin_max'] && $masa_kerja) {
												$p_now=$data_to_history['poin']-$emp['rank_up_poin_max'];
												$data_in['grade']=$emp['rank_up'];
												$data_in['grade_old']=$emp['grade'];
												$data_in['poin']=0;
												$data_in['poin_old']=$p_now;
												$data_to_history['rank_up']=true;
												$data_to_history['rank_now']=$emp['rank_up'];
												$data_to_history['rank_old']=$emp['grade'];
												$data_to_history['sisa']=$p_now;
											}
										}
										if (!$data_to_history['rank_up']) {
											$data_in['poin_old']=$cek['poin_old'];
											$data_in['poin']=$cek['poin_old']+$data_in['poin_now'];
										}
										if (($cek['rank_up'] == 1) && (!empty($emp['rank_up_poin_max']))) {
											if ($data_to_history['poin'] >= $emp['rank_up_poin_max'] && $masa_kerja) {
												$p_now=$data_to_history['poin']-$emp['rank_up_poin_max'];
												$data_in['poin']=0;
												$data_in['poin_old']=$p_now;
												$data_to_history['rank_up']=true;
												$data_to_history['rank_now']=$emp['rank_up'];
												$data_to_history['rank_old']=$emp['grade'];
												$data_to_history['sisa']=$p_now;
											}
										}
										$this->model_global->updateQueryNoMsg($data_to_history,'data_history_poin',['id_karyawan'=>$d[0],'kode_periode'=>$kode['kode_periode'],'tahun'=>$kode['tahun']]);
										$this->model_global->updateQueryNoMsg($data_in,'karyawan',['id_karyawan'=>$d[0]]);
									}
								}
							}else{
								$this->model_global->insertQueryNoMsg($data_to_history,'data_history_poin');
								$this->model_global->updateQueryNoMsg($data_in,'karyawan',['id_karyawan'=>$d[0]]);
							}
						}
					}
				}
			}
		}
		return $ret;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Saran
	public function getListSaran($id,$kode_periode,$tahun)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_admin,f.nama as nama_karyawan, e.nama as nama_penerima');
		$this->db->from('data_saran_penilaian AS a');
		$this->db->join('admin AS d', 'd.id_admin = a.id_admin', 'left'); 
		$this->db->join('karyawan AS e', 'e.id_karyawan = a.untuk', 'left'); 
		$this->db->join('karyawan AS f', 'f.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.untuk',$id); 
		$this->db->where('a.tahun',$tahun); 
		if(!empty($kode_periode)){
			$this->db->where('a.kode_periode',$kode_periode); 
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListSaranId($id)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,d.nama as nama_pengirim, e.nama as nama_penerima,adm.nama as nama_admin');
		$this->db->from('data_saran_penilaian AS a');
		$this->db->join('karyawan AS d', 'd.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('admin AS adm', 'adm.id_admin = a.id_admin', 'left'); 
		$this->db->join('karyawan AS e', 'e.id_karyawan = a.untuk', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_saran',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Agenda KPI penilaian
	public function getListAgendaKpiDepartemen($where = null, $status = 'all_item')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, smt.nama as nama_periode, smt.start, smt.end, smt.batas');
		$this->db->from('agenda_kpi_departemen as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_periode_semester AS smt', 'smt.kode_semester = a.periode', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); } 						
		if(!empty($where)){ $this->db->where($where); } 										
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkAgendaKpiDepartemenCode($code)
	{
		return $this->model_global->checkCode($code,'agenda_kpi_departemen','kode_a_kpi_departemen');
	}
	public function cekNullData($kode)
	{
		$this->db->select('cek.*');
		$this->db->from('data_pic_kpi_departemen as cek');
		$this->db->where('cek.kode_a_kpi_departemen',$kode);
		$this->db->where('cek.id_karyawan',null);
		$query = $this->db->get()->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Agenda PIC KPI penilaian
	public function getListPicKpiDepartemen($where = null, $cek_kode = null, $status = 'active')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, mkpi.nama as nama_m_kpi, dkpi.nama as nama_d_kpi, vkpi.nama as nama_v_kpi, vkpi.metode');
		$this->db->from('data_pic_kpi_departemen as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_kpi_departemen AS mkpi', 'mkpi.kode_kpi_departemen = a.kode_kpi_departemen', 'left');
		$this->db->join('master_view_kpi_departemen AS dkpi', 'dkpi.kode_data_kpi_departemen = a.kode_data_kpi_departemen', 'left');
		$this->db->join('master_detail_kpi_departemen AS vkpi', 'vkpi.kode_view_kpi_departemen = a.kode_view_kpi_departemen', 'left');
		if($status == 'active'){ $this->db->where('a.status','1'); } 						
		if(!empty($where)){ $this->db->where($where); } 										

		$this->db->order_by('a.id_karyawan','asc');
		$this->db->order_by('a.update_date','desc');			
		$query = $this->db->get()->result();
		return $query;
	}

	public function getListNilaiKpiDepartemen($where = null, $status = 'all_item', $usage = 'other')
	{
		if($usage == 'single'){
			$this->db->select('a.*');
			$this->db->from('nilai_kpi_departemen_master as a');
		}else{
			$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, mkpi.nama as nama_m_kpi, akpi.nama as nama_agenda, akpi.start, akpi.end, akpi.batas');
			$this->db->from('nilai_kpi_departemen_master as a');
			$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
			$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
			$this->db->join('master_kpi_departemen AS mkpi', 'mkpi.kode_kpi_departemen = a.kode_kpi_departemen', 'left');
			$this->db->join('agenda_kpi_departemen AS akpi', 'akpi.kode_a_kpi_departemen = a.kode_a_kpi_departemen', 'left');
		}
		if($status == 'active'){ $this->db->where('a.status','1'); } 						
		if(!empty($where)){ $this->db->where($where); } 										

		$this->db->order_by('a.update_date','desc');			
		$query = $this->db->get()->result();
		return $query;
	}

	public function getListNilaiKpiDepartemenView($where = null, $status = 'all_item', $usage = 'other')
	{
		if($usage == 'single'){
			$this->db->select('a.*');
			$this->db->from('nilai_kpi_departemen_view as a');
		}else{
			$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, mkpi.nama as nama_m_kpi, akpi.nama as nama_agenda, akpi.start, akpi.end, akpi.batas');
			$this->db->from('nilai_kpi_departemen_view as a');
			$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
			$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
			$this->db->join('master_kpi_departemen AS mkpi', 'mkpi.kode_kpi_departemen = a.kode_kpi_departemen', 'left');
			$this->db->join('agenda_kpi_departemen AS akpi', 'akpi.kode_a_kpi_departemen = a.kode_a_kpi_departemen', 'left');
		}
		if($status == 'active'){ $this->db->where('a.status','1'); } 						
		if(!empty($where)){ $this->db->where($where); } 										

		$this->db->order_by('a.update_date','desc');			
		$query = $this->db->get()->result();
		return $query;
	}
//===============================  ABU  (DASBOR)  ==============================
//  KPI
	public function getListAgendaKpiTahun($tahun=null,$periode=null)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,f.nama as nama_periode');
		$this->db->from('agenda_kpi AS a');
		$this->db->join('master_periode_penilaian AS f', 'f.kode_periode = a.periode', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left');
		if(!empty($tahun)){
			$this->db->where('tahun',$tahun);
		}
		if(!empty($periode)){
			$this->db->where('periode',$periode);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function openTableAgendaIdEmployeeChart($table,$id=null,$search=null)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker,b.kode_periode as kode_periode, d.nama as bagian,rms.nama as nama_rumus,rms.function as rumus_kpi');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  
		$this->db->join('master_departement AS e', 'e.kode_departement = d.kode_departement', 'left');  
		$this->db->join('master_level_struktur AS lvl', 'lvl.kode_level_struktur = d.kode_level_struktur', 'left');  
		$this->db->join('master_kpi AS kpi', 'kpi.kode_kpi =a.kode_kpi', 'left');  
		$this->db->join('master_rumus AS rms', 'rms.function =kpi.cara_menghitung', 'left'); 
		$this->db->order_by('emp.nik','ASC');
		if(isset($id)){
			$this->db->where('a.id_karyawan',$id);
		}
		// if(isset($search['departemen_filter'])){
		// 	$this->db->where('d.kode_departement',$search['departemen_filter']);
		// }
		if(isset($search['departemen_filter']) && $search['departemen_filter'] != null){
			$this->db->where('lvl.kode_level_struktur',$search['departemen_filter']);
		}
		if(isset($search['bagian_filter']) && $search['bagian_filter'] != null){
			$this->db->where('b.kode_bagian',$search['bagian_filter']);
		}
		if(isset($search['loker_filter']) && $search['loker_filter'] != null){
			$this->db->where('a.kode_loker',$search['loker_filter']);
		}
		if(isset($search['kpi_filter']) && $search['kpi_filter'] != null){
			$this->db->where('a.kode_kpi',$search['kpi_filter']);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function getAgendaKpiTahunValidate($tahun,$periode=null,$validate=1)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,f.nama as nama_periode');
		$this->db->from('agenda_kpi AS a');
		$this->db->join('master_periode_penilaian AS f', 'f.kode_periode = a.periode', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_kpi AS d', 'd.kode_c_kpi = a.kode_c_kpi', 'left');
		$this->db->where('tahun',$tahun);
		if(isset($periode)){
			$this->db->where('periode',$periode);
		}
		$this->db->where('validasi',$validate); 
		$query=$this->db->get()->result();
		return $query;
	}
// SIKAP
	public function getAgendaSikapTahunValidate($tahun,$periode=null,$validate=1)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, d.nama as nama_konsep,f.nama as nama_periode');
		$this->db->from('agenda_sikap AS a');
		$this->db->join('master_periode_penilaian AS f', 'f.kode_periode = a.periode', 'left'); 
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('concept_sikap AS d', 'd.kode_c_sikap = a.kode_c_sikap', 'left');
		$this->db->where('tahun',$tahun);
		if(isset($periode)){
			$this->db->where('periode',$periode);
		}
		$this->db->where('validasi',$validate); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function openTableSikapIdk($table,$id)
	{
		if (empty($id) || empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker,b.kode_periode as kode_periode, d.nama as bagian');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');
		$this->db->where('a.id_karyawan',$id);		
		$query=$this->db->get()->result();
		return $query;
	}
	public function getKuartal($bulan)
	{
		if (empty($bulan))
			return null;
		$this->db->select('kode_periode');
		$this->db->from('master_periode_penilaian');
		$this->db->where('start <=',$bulan);
		$this->db->where('end >=',$bulan);
		$this->db->where('status',1);
		$query=$this->db->get()->row_array();
		return $query;
	}
//dasbor talent pool
	public function openTableAgendaTalent($table,$data = null)
	{
		if (empty($table) || !$this->db->table_exists($table))
			return null;
		$this->db->select('a.*,emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker, d.nama as bagian,b.kode_periode as kode_periode');
		$this->db->from($table.' AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS b', 'b.kode_jabatan = a.kode_jabatan', 'left'); 
		$this->db->join('master_loker AS c', 'c.kode_loker = a.kode_loker', 'left'); 
		$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  
		$this->db->join('master_departement AS e', 'e.kode_departement = d.kode_departement', 'left');
		$this->db->join('master_level_jabatan AS f', 'f.kode_level_jabatan = b.kode_level', 'left');
		if(!empty($data)){
			(!empty($data['loker_talent'])) ? $this->db->where('c.kode_loker', $data['loker_talent']) : null;
			(!empty($data['bagian_talent'])) ? $this->db->where('d.kode_bagian', $data['bagian_talent']) : null;
			(!empty($data['departemen_talent'])) ? $this->db->where('e.kode_departement', $data['departemen_talent']) : null;
			(!empty($data['level_talent'])) ? $this->db->where('f.kode_level_jabatan', $data['level_talent']) : null;
		}
		$this->db->order_by('emp.nama','ASC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getListEmployeeTalentPool($kode, $data_form = null)
	{
	//kode is array
		$pack=[];
		if (!isset($kode)){
			if (!isset($kode['tahun'])) {
				return $pack;
			}
			return $pack;
		} 	
		$emp=[];	
		$table=[];		
		$usage='kuartal';
		if(isset($kode['kode_periode'])){
			$kpi=$this->getLogAgendaKpiPeriode($kode['kode_periode'],$kode['tahun']);
			$sikap=$this->getLogAgendaSikapPeriode($kode['kode_periode'],$kode['tahun']);
			// $reward=$this->getLogAgendaRewardPeriode($kode['kode_periode'],$kode['tahun']);
		}elseif (isset($kode['tahun']) && !isset($kode['kode_periode'])) {
			$kpi=$this->getLogAgendaKpiTahun($kode['tahun'],true);
			$sikap=$this->getLogAgendaSikapTahun($kode['tahun'],true);
			// $reward=$this->getLogAgendaRewardTahun($kode['tahun'],true);
			$kode['kode_periode']=null;
			$usage='tahun';
		}
		if (isset($kpi)) {
			foreach ($kpi as $kp) {
				array_push($table,$kp->nama_tabel.';kpi');
			}
		}
		if (isset($sikap)) {
			foreach ($sikap as $sk) {
				array_push($table,$sk->nama_tabel.';sikap');
			}
		}
		// if (isset($reward)) {
		// 	foreach ($reward as $rw) {
		// 		array_push($table,$rw->nama_tabel.';reward');
		// 	}
		// }
		if (isset($table)) {
			$emp=[];
			$table=array_filter($table);
			foreach ($table as $tb) {					
				$tb=$this->otherfunctions->getParseOneLevelVar($tb);
				if (isset($tb[0])) {						
					$data=$this->openTableAgendaTalent($tb[0], $data_form);
					if (isset($data)) {
						foreach ($data as $d) {
							$emp[$d->id_karyawan]=[$d->id_karyawan,$d->nama,$d->nama_jabatan];
						}
					}
				}
			}
			if (isset($emp)){
				$count_huruf=[];
				foreach ($emp as $k_e => $v_e) {
					$nilai_total[$k_e]=0;
					$nilai_rw[$k_e]=0;
					$emp_data=$this->model_karyawan->getEmployeeId($k_e);
					if (isset($table)) {
						foreach ($table as $l_tb) {
							$l_tb=$this->otherfunctions->getParseOneLevelVar($l_tb);
							if (isset($l_tb[0])) {
								if ($l_tb[1] == 'kpi') {
									$nilai_total[$k_e]=$nilai_total[$k_e]+(((isset($this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e)['nilai_akhir']:0)*($emp_data['bobot_out']/100));
								}elseif ($l_tb[1] == 'sikap') {
									$nilai_total[$k_e]=$nilai_total[$k_e]+(((isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']:0)*($emp_data['bobot_sikap']/100));
									if (isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi'])) {
										$nilai_total[$k_e]=(((!empty($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi']:$nilai_total[$k_e])*($emp_data['bobot_sikap']/100));
									}
								}
								// elseif ($l_tb[1] == 'reward') {
								// 	$nilai_rw[$k_e]=$nilai_rw[$k_e]+((isset($this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']:0);
								// }
							}	
						}
					}
					$kedisiplinan[$k_e]=$this->model_presensi->rumusCustomKubotaFinalResultPresensi($k_e,$kode['kode_periode'],$kode['tahun'],$usage)['nilai_akhir'];
					$nilai_total[$k_e]=$nilai_total[$k_e]-$kedisiplinan[$k_e];
					array_push($v_e,number_format($nilai_total[$k_e],2));
					$data_konversi=(($usage == 'kuartal')?$this->model_master->getKonversiKuartalNilai($nilai_total[$k_e]):$this->model_master->getKonversiTahunanNilai($nilai_total[$k_e]));
					$huruf=((isset($data_konversi['huruf']))?$data_konversi['huruf']:'Unknown');
					array_push($v_e,$huruf);
					array_push($v_e,$nilai_total[$k_e]);
					$kode['id']=$k_e;						
					$pack[]=$v_e;
					$count_huruf[]=$huruf;
				}
			}
		}
		return $pack;
	}
	public function getListEmployeeInsentif($kode, $data_form = null)
	{
	//kode is array
		$pack=[];
		if (!isset($kode)){
			if (!isset($kode['tahun'])) {
				return $pack;
			}
			return $pack;
		} 	
		$emp=[];	
		$emp_data=[];				
		$periode=$this->model_master->getListPeriodePenilaian(true);
		if (isset($periode)) {
			foreach ($periode as $per) {
				$table[$per->kode_periode]=[];	
				$kpi=$this->getLogAgendaKpiPeriode($per->kode_periode,$kode['tahun']);
				$sikap=$this->getLogAgendaSikapPeriode($per->kode_periode,$kode['tahun']);
				$reward=$this->getLogAgendaRewardPeriode($per->kode_periode,$kode['tahun']);
				$usage='kuartal';
				if (isset($kpi)) {
					foreach ($kpi as $kp) {
						array_push($table[$per->kode_periode],$kp->nama_tabel.';kpi');
					}
				}
				if (isset($sikap)) {
					foreach ($sikap as $sk) {
						array_push($table[$per->kode_periode],$sk->nama_tabel.';sikap');
					}
				}
				if (isset($reward)) {
					foreach ($reward as $rw) {
						array_push($table[$per->kode_periode],$rw->nama_tabel.';reward');
					}
				}
				if (isset($table[$per->kode_periode])) {
					$table[$per->kode_periode]=array_filter($table[$per->kode_periode]);
					foreach ($table[$per->kode_periode] as $tb) {					
						$tb=$this->otherfunctions->getParseOneLevelVar($tb);
						if (isset($tb[0])) {						
							$data=$this->openTableAgenda($tb[0], $data_form);
							if (isset($data)) {
								foreach ($data as $d) {
									$emp[$d->id_karyawan]=[$d->id_karyawan,$d->nik,$d->nama,$d->nama_jabatan,$d->bagian,$d->nama_departemen,$d->nama_loker];
								}
							}
						}
					}
					if (isset($emp)){
						foreach ($emp as $k_e => $v_e) {
							$nilai_total[$k_e]=0;
							$nilai_rw[$k_e]=0;
							if (count($table[$per->kode_periode]) > 0) {
								foreach ($table[$per->kode_periode] as $l_tb) {
									$l_tb=$this->otherfunctions->getParseOneLevelVar($l_tb);
									if (isset($l_tb[0])) {
										if ($l_tb[1] == 'kpi') {
											//
											$nilai_total[$k_e]=$nilai_total[$k_e]+((isset($this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e,true)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e,true)['nilai_akhir']:0);
										}elseif ($l_tb[1] == 'sikap') {
											$nilai_total[$k_e]=$nilai_total[$k_e]+((isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']:0);
											if (isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi'])) {
												$nilai_total[$k_e]=((!empty($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi']:$nilai_total[$k_e]);
											}
										}elseif ($l_tb[1] == 'reward') {
											$nilai_rw[$k_e]=$nilai_rw[$k_e]+((isset($this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']:0);
										}
									}	
								}
								
							}
							$sp=$this->model_karyawan->getPoinSuratPeringatan($k_e,$kode['tahun'],$per->kode_periode)['poin_pengurang'];
							//presensi
							$nilai_total[$k_e]=$nilai_total[$k_e]+$this->model_presensi->rumusCustomKubotaFinalResultPresensi($k_e,$per->kode_periode,$kode['tahun'],$usage)['nilai_akhir'];
							$p_pengurang=$nilai_total[$k_e]*($sp/100);
							$nilai_total[$k_e]=$nilai_total[$k_e]-$p_pengurang+$nilai_rw[$k_e];
							if (count($table[$per->kode_periode]) > 0) {
								$emp_data[$k_e][$per->kode_periode]=number_format($nilai_total[$k_e],2);
							}else{
								$emp_data[$k_e][$per->kode_periode]=0;
							}
							
							
							// $data_konversi=(($usage == 'kuartal')?$this->model_master->getKonversiKuartalNilai($nilai_total[$k_e]):$this->model_master->getKonversiTahunanNilai($nilai_total[$k_e]));
							// $huruf=((isset($data_konversi['huruf']))?$data_konversi['huruf']:'Unknown');
							// array_push($v_e,$huruf);
							// array_push($v_e,$nilai_total[$k_e]);	
							// $pack[]=$v_e;
							// $count_huruf[]=$huruf;
						}
					}
				}
			}
			$datax=[];
			if (count($emp) > 0 && count($emp_data) > 0) {
				$all_value=[];
				foreach ($emp_data as $id_karyawan => $e_data) {
					$all_value[$id_karyawan]=$this->rumus->rumus_avg($e_data);
				}
				foreach ($emp as $id_karyawan => $val) {
					if (isset($emp_data[$id_karyawan])) {
						$avg=$this->rumus->rumus_avg($emp_data[$id_karyawan]);
						foreach ($periode as $p) {
							if (!isset($emp_data[$id_karyawan][$p->kode_periode])) {
								$emp_data[$id_karyawan][$p->kode_periode]=0;
							}else{
								$emp_data[$id_karyawan][$p->kode_periode]=$this->formatter->getNumberFloat($emp_data[$id_karyawan][$p->kode_periode]);
							}
						}
						ksort($emp_data[$id_karyawan]);
						$huruf='Unknown';
						if (count($all_value) > 0) {
							$huruf=$this->exam->rangeInsentif($all_value,$avg);
						}						
						$datax[$id_karyawan]=array_merge($val,$emp_data[$id_karyawan],[$avg,$huruf]);
					}
				}			
			}
		}
		$max_col=6;
		return $this->otherfunctions->arraySortIndex($datax,($max_col+1),'DESC');
	}
	public function listKonversi()
	{
		$this->db->select('*');
		$this->db->from('master_konversi_tahunan');
		$this->db->where('status',1);
		$query=$this->db->get()->result();
		return $query;
	}
//dasbord raport
	public function raportTahunan($data_raport)
	{
		$pack=['kpi'=>null,'sikap'=>null,'presensi'=>null,'reward'=>null,'bobot_kpi'=>0,'bobot_sikap'=>0];
		if (empty($data_raport))
			return $pack;
		if(isset($data_raport['kpi'])){
			$sub_nilai_kpi=0;
			$k_jenis=[];
			if (isset($data_raport['kpi'])) {
				if ($data_raport['kpi'] != '') {
					$sub_nilai_kpi=$sub_nilai_kpi+$data_raport['kpi'];
				}
			}
			$pack['kpi']=number_format($sub_nilai_kpi,2);
		}else{
			$sub_nilai_kpi=0;
			$pack['kpi']=$sub_nilai_kpi;
		}
		if (isset($data_raport['sikap'])) {
			$sub_nilai_sikap=0;
			$k_asp=[];
			foreach ($data_raport['sikap'] as $k_sk => $v_sk) {
				array_push($k_asp,$k_sk);
			}
			$max_asp=count($k_asp);
			if (isset($k_asp[0])) {
				$nilai_sikap=((isset($data_raport['sikap'][$k_asp[0]]['nilai']))?array_sum($data_raport['sikap'][$k_asp[0]]['nilai']):0);
				$sub_nilai_sikap=$sub_nilai_sikap+$nilai_sikap;
			}
			for ($i=1; $i < $max_asp ; $i++) { 
				$nilai_sikap=((isset($data_raport['sikap'][$k_asp[$i]]['nilai']))?array_sum($data_raport['sikap'][$k_asp[$i]]['nilai']):0);
				$sub_nilai_sikap=$sub_nilai_sikap+$nilai_sikap;
			}
			if (isset($data_raport['kalibrasi_sikap']['value_kalibrasi'])) {
				$sub_nilai_sikap=$data_raport['kalibrasi_sikap']['value_kalibrasi'];
			}
			$pack['sikap']=number_format($sub_nilai_sikap,2);
		}else{
			$sub_nilai_sikap=0;
			$pack['sikap']=$sub_nilai_sikap;
		}
		if(isset($data_raport['presensi'])){
			$sub_nilai_presensi=((isset($data_raport['presensi']['nilai_akhir']))?$data_raport['presensi']['nilai_akhir']:0);
			$pack['presensi']=number_format($sub_nilai_presensi,2);
		}else{
			$sub_nilai_presensi=0;
			$pack['presensi']=$sub_nilai_presensi;
		}
		if (isset($data_raport['bobot_kpi'])) {
			$pack['bobot_kpi']=$data_raport['bobot_kpi'];
		}
		if (isset($data_raport['bobot_sikap'])) {
			$pack['bobot_sikap']=$data_raport['bobot_sikap'];
		}
		return $pack;
	}
	public function checkHostoryPoin($id,$periode,$tahun)
	{
		if (empty($id) || empty($periode) || empty($tahun))
			return null;
		$q=$this->db->get_where('data_history_poin',['id_karyawan'=>$id,'kode_periode'=>$periode,'tahun'=>$tahun])->row_array();
		return $q;
	}
	public function getListRaportTahunanHistory($kode,$post_form=null)
	{
		if(empty($kode))
			return null;
		$data_p=$this->model_master->getListPeriodePenilaian(1);
		if (isset($kode['kode_periode'])){
			if(!empty($kode['kode_periode'])){
				$data_p=$this->model_master->getPeriodePenilaianKode($kode['kode_periode'],true);
			}
		}
		if (!isset($data_p)) {
			return null;
		}else{
			$sb_p='';
			foreach ($data_p as $dp){
				$sb_p.="FORMAT((select q_".$dp->kode_periode.".poin_now from data_history_poin q_".$dp->kode_periode." where q_".$dp->kode_periode.".kode_periode = '".$dp->kode_periode."' and q_".$dp->kode_periode.".id_karyawan = main.id_karyawan and q_".$dp->kode_periode.".tahun = main.tahun),2) as q_".$dp->kode_periode.",FORMAT((select poin_old_".$dp->kode_periode.".poin_old from data_history_poin poin_old_".$dp->kode_periode." where poin_old_".$dp->kode_periode.".kode_periode = '".$dp->kode_periode."' and poin_old_".$dp->kode_periode.".id_karyawan = main.id_karyawan and poin_old_".$dp->kode_periode.".tahun = main.tahun),2) as poin_old_".$dp->kode_periode.",";
			}
			if ($sb_p == '') {
				return null;
			}else{
				$this->db->select("DISTINCT(main.id_karyawan),emp.nama as nama,emp.nik as nik,b.nama as nama_jabatan,c.nama as nama_loker,d.nama as nama_bagian,e.nama as nama_departement,r.nama as nama_rank,s.nama as nama_rank_old,s.auto_rank_up as auto_rank_up_old,
				FORMAT((SELECT sld.poin_old from data_history_poin sld where sld.id_history_poin = (select min(slds.id_history_poin) from data_history_poin slds where slds.id_karyawan = main.id_karyawan and slds.tahun = main.tahun)),2) as poin_old_tahun,".$sb_p."
				FORMAT((select sum(pn.poin_now) from data_history_poin pn where pn.id_karyawan = main.id_karyawan and pn.tahun = main.tahun),2) as poin_now_tahun,
				FORMAT(((select sum(pn.poin_now) from data_history_poin pn where pn.id_karyawan = main.id_karyawan and pn.tahun = main.tahun) + (SELECT sld.poin_old from data_history_poin sld where sld.id_history_poin = (select min(slds.id_history_poin) from data_history_poin slds where slds.id_karyawan = main.id_karyawan and slds.tahun = main.tahun))),2) as total,
				FORMAT((select MAX(pn.max_poin_rank_up) from data_history_poin pn where pn.id_karyawan = main.id_karyawan and pn.tahun = main.tahun),2) as max_poin_rank_up,
				FORMAT((((select sum(pn.poin_now) from data_history_poin pn where pn.id_karyawan = main.id_karyawan and pn.tahun = main.tahun) + (SELECT sld.poin_old from data_history_poin sld where sld.id_history_poin = (select min(slds.id_history_poin) from data_history_poin slds where slds.id_karyawan = main.id_karyawan and slds.tahun = main.tahun))) - (select MAX(pn.max_poin_rank_up) from data_history_poin pn where pn.id_karyawan = main.id_karyawan and pn.tahun = main.tahun)),2) as sisa");
				$this->db->from('data_history_poin main');
				$this->db->join('karyawan AS emp', 'emp.id_karyawan = main.id_karyawan', 'left'); 
				$this->db->join('master_jabatan AS b', 'b.kode_jabatan = main.kode_jabatan', 'left'); 
				$this->db->join('master_loker AS c', 'c.kode_loker = main.kode_loker', 'left'); 
				$this->db->join('master_bagian AS d', 'd.kode_bagian = b.kode_bagian', 'left');  
				$this->db->join('master_departement AS e', 'e.kode_departement = d.kode_departement', 'left');
				$this->db->join('master_rank AS r', 'r.kode_rank = main.rank_now', 'left');
				$this->db->join('master_rank AS s', 's.kode_rank = main.rank_old', 'left');
				if(!empty($post_form)){
					(!empty($post_form['loker_filter'])) ? $this->db->where('c.kode_loker', $post_form['loker_filter']) : null;
					(!empty($post_form['bagian_filter'])) ? $this->db->where('d.kode_bagian', $post_form['bagian_filter']) : null;
					(!empty($post_form['departemen_filter'])) ? $this->db->where('e.kode_departement', $post_form['departemen_filter']) : null;
					if (!empty($post_form['level_jabatan_filter'])) {
						$sq_level=null;
						$cl=1;
						foreach ($post_form['level_jabatan_filter'] as $kode_level) {
							if (!empty($kode_level)) {
								$sq_level.="b.kode_level = '$kode_level'";
								if ($cl < count($post_form['level_jabatan_filter'])) {
									$sq_level.=' OR ';
								}
								$cl++;
							}							
						}
						if (!empty($sq_level)) {
							$this->db->where('('.$sq_level.')');
						}
					}
				}
				if(isset($kode['id'])){
					if(!empty($kode['id'])){
						$this->db->where('emp.id_karyawan',$kode['id']);
					}
				}
				if(isset($kode['kode_periode'])){
					if(!empty($kode['kode_periode'])){
						$this->db->where('main.kode_periode',$kode['kode_periode']);
					}
				}
				$this->db->order_by('emp.nik','ASC');
				$this->db->where('main.tahun',$kode['tahun']);
				$query=$this->db->get()->result();
				return $query;
			}
		}
	}
	public function getPointEmployee($id)
	{
		$nilai=0;
		if (empty($id))
			return $nilai;
		$tahun=date('Y');
		$month=date('m');
		if ($month == 1) {
			$month=12;
			$tahun=$tahun-1;
		}else{
			$month=(int)$month-1;
		}
		$kode=['tahun'=>$tahun,'kode_periode'=>null,'id'=>$id];
		$periode=$this->model_master->getPeriodePenilaianMonth($month);
		if(isset($periode['kode_periode'])){
			$kode['kode_periode']=$periode['kode_periode'];
		}
		$data_nilai=$this->getReportGroupEmployee($kode['kode_periode'],$kode['tahun'],$kode['id']);
		if (empty($data_nilai['kpi']) && empty($data_nilai['sikap'])) {
			$agenda=$this->getListLogAgendaKpi(true);
			if (isset($agenda)) {
				foreach ($agenda as $ag) {
					$kode['kode_periode']=$ag->periode;
					$kode['tahun']=$ag->tahun;
				}
			}
			$data_nilai=$this->getReportGroupEmployee($kode['kode_periode'],$kode['tahun'],$kode['id']);
		}
		$emp = $this->model_karyawan->getEmployeeId($kode['id']);
		$poin_old=$emp['poin_old'];
		if ($emp['poin_old'] < $emp['poin'] && $emp['poin_old']) {
			$poin_old=$emp['poin_old']+$emp['poin_now'];
		}
		$history_poin=$this->model_agenda->getListRaportTahunanHistory($kode);
		if (isset($history_poin[0])){
			$cols_history='poin_old_'.$kode['kode_periode'];
			$poin_old=$history_poin[0]->$cols_history;
		}
		if (!empty($data_nilai['kpi'])) {
			$nilai=$nilai+$data_nilai['kpi'];
			// foreach ($data_nilai['kpi'] as $kp) {
			// 	$nilai=$nilai+array_sum($kp);
			// }
		}
		if (!empty($data_nilai['sikap'])) {
			foreach ($data_nilai['sikap'] as $val) {
				if (!isset($data_nilai['kalibrasi_sikap']['value_kalibrasi'])) {
					$nilai=$nilai+array_sum($val['nilai']);
										
				}			
			}
		}	
		if (isset($data_nilai['kalibrasi_sikap']['value_kalibrasi'])) {
			$nilai=$nilai+$data_nilai['kalibrasi_sikap']['value_kalibrasi'];
		}	
		if (!empty($data_nilai['presensi'])) {
			$nilai=$nilai+$data_nilai['presensi']['nilai_akhir'];
		}
		// if (!empty($data_nilai['punishment'])) {
		// 	$p_pengurang=$nilai*($data_nilai['punishment']/100);
		// 	$nilai=$nilai-$p_pengurang;
		// }
		// if (!empty($data_nilai['reward'])) {
		// 	foreach ($data_nilai['reward'] as $val) {
		// 		$nilai=$nilai+array_sum($val);
		// 	}
		// }
		return number_format(($nilai+$poin_old),2);
	}
	public function deleteFromPenilaiKpi($table,$id_karyawan)
	{
		if (empty($id_karyawan) || empty($table)) 
			return false;
		$sql="SELECT id_karyawan,kode_kpi,penilai FROM $table WHERE CONCAT(';',penilai,';') LIKE ';$id_karyawan;%' and kode_penilai = 'P3' GROUP BY id_karyawan";
		$q=$this->db->query($sql)->result();
		if (isset($q)) {
			foreach ($q as $d) {
				$del=$this->exam->delPartisipantDb($id_karyawan,$d->penilai);
				if ($del != 'null') {
					$this->model_global->updateQueryNoMsg(['penilai'=>$del],$table,['id_karyawan'=>$d->id_karyawan,'kode_kpi'=>$d->kode_kpi]);
				}				
			}
		}
		return false;
	}
	public function deleteFromPartisipanSikap($table,$id_karyawan)
	{
		if (empty($id_karyawan) || empty($table)) 
			return false;		
		$sql="SELECT id_karyawan,partisipan FROM $table WHERE CONCAT(partisipan,';') LIKE '%:$id_karyawan;%'";
		$q=$this->db->query($sql)->result();
		if (isset($q)) {
			foreach ($q as $d) {
				$del=$this->exam->delPartisipantDbId($id_karyawan,$d->partisipan);
				if ($del != 'null') {
					$this->model_global->updateQueryNoMsg(['partisipan'=>$del],$table,['id_karyawan'=>$d->id_karyawan]);
				}				
			}
		}
		return false;
	}
	//sync non aktif
	public function deleteEmployeeNonActive($id_karyawan)
	{
		$ret=false;
		if (empty($id_karyawan))
			return $ret;
		//agenda
		$kpi=$this->getAgendaKpiValidate(0);
		$sikap=$this->getAgendaSikapValidate(0);
		$reward=$this->getAgendaRewardValidate(0);
		$c_kpi=$this->model_concept->getListKonsepKpi(true);
		$c_sikap=$this->model_concept->getListKonsepSikap(true);
		if (isset($kpi)) {
			foreach ($kpi as $d_kpi) {
				$tabel_kpi=$d_kpi->nama_tabel;
				$this->model_global->deleteQueryNoMsg($tabel_kpi,['id_karyawan'=>$id_karyawan]);
				$this->deleteFromPenilaiKpi($tabel_kpi,$id_karyawan);
			}
		}
		if (isset($sikap)) {
			foreach ($sikap as $d_sikap) {
				$tabel_sikap=$d_sikap->nama_tabel;
				$this->model_global->deleteQueryNoMsg($tabel_sikap,['id_karyawan'=>$id_karyawan]);
				$this->deleteFromPartisipanSikap($tabel_sikap,$id_karyawan);
			}
		}
		if (isset($reward)) {
			foreach ($reward as $d_reward) {
				$tabel_reward=$d_reward->nama_tabel;
				$this->model_global->deleteQueryNoMsg($tabel_reward,['id_karyawan'=>$id_karyawan]);
			}
		}
		if (isset($c_kpi)) {
			foreach ($c_kpi as $d_c_kpi) {
				$tabel_c_kpi=$d_c_kpi->nama_tabel;
				$this->model_global->deleteQueryNoMsg($tabel_c_kpi,['id_karyawan'=>$id_karyawan]);
				$this->deleteFromPenilaiKpi($tabel_c_kpi,$id_karyawan);
			}
		}
		if (isset($c_sikap)) {
			foreach ($c_sikap as $d_c_sikap) {
				$tabel_c_sikap=$d_c_sikap->nama_tabel;
				$this->model_global->deleteQueryNoMsg($tabel_c_sikap,['id_karyawan'=>$id_karyawan]);
				$this->deleteFromPartisipanSikap($tabel_c_sikap,$id_karyawan);
			}
		}
	}
	public function checkApprovalPa($id_emp,$id_check,$sebagai,$kode)
	{
		$ret=false;
		if (empty($id_emp) || empty($id_check) || empty($sebagai)) 
			return $ret;
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,per.nama as nama_periode, per.start, per.end,per.batas as batas');
		$this->db->from('data_check_pa AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->join('master_periode_penilaian AS per', 'per.kode_periode = a.kode_periode', 'left'); 
		if (isset($kode['tahun'])) {
			$this->db->where('a.tahun',$kode['tahun']);
		}
		if (isset($kode['kode_periode'])) {
			$this->db->where('a.kode_periode',$kode['kode_periode']);
		}
		if ($sebagai == 'APR') {
			$this->db->where("CONCAT(';',a.approval,';') LIKE '%;$id_check;%'");
		}elseif ($sebagai == 'RVW') {
			$this->db->where("CONCAT(';',a.reviewer,';') LIKE '%;$id_check;%'");
		}elseif ($sebagai == 'MKR') {
			$this->db->where("CONCAT(';',a.maker,';') LIKE '%;$id_check;%'");
		}
		$this->db->where('a.id_karyawan',$id_emp); 
		$this->db->where('a.status',1); 
		$query=$this->db->get()->row_array();
		if (isset($query) && count($query) > 0) {
			$ret=true;
		}
		return $ret;
	}
	public function getAprovalPa($id_emp,$kode)
	{
		if (emptY($id_emp) || empty($kode)) 
			return null;
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update');
		$this->db->from('data_check_pa AS a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left');
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_karyawan',$id_emp);
		if (isset($kode['tahun'])) {
			$this->db->where('a.tahun',$kode['tahun']);
		}
		if (isset($kode['kode_periode'])) {
			$this->db->where('a.kode_periode',$kode['kode_periode']);
		}
		$this->db->where('a.status',1); 
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListEmployeeApproval($kode, $data_form = null,$id_admin,$page,$usages=false)
	{
	//kode is array
		$pack=[];
		if (!isset($kode)){
			if (!isset($kode['tahun'])) {
				return $pack;
			}
			return $pack;
		} 	
		$emp=[];	
		$emp_select=[];	
		$emp_data=[];	
		$table=[];	
		$kpi=$this->getLogAgendaKpiPeriode($kode['kode_periode'],$kode['tahun']);
		$sikap=$this->getLogAgendaSikapPeriode($kode['kode_periode'],$kode['tahun']);
		$reward=$this->getLogAgendaRewardPeriode($kode['kode_periode'],$kode['tahun']);
		$usage='kuartal';
		if (isset($kpi)) {
			foreach ($kpi as $kp) {
				array_push($table,$kp->nama_tabel.';kpi');
			}
		}
		if (isset($sikap)) {
			foreach ($sikap as $sk) {
				array_push($table,$sk->nama_tabel.';sikap');
			}
		}
		if (isset($reward)) {
			foreach ($reward as $rw) {
				array_push($table,$rw->nama_tabel.';reward');
			}
		}
		if (isset($table)) {
			$table=array_filter($table);
			foreach ($table as $tb) {					
				$tb=$this->otherfunctions->getParseOneLevelVar($tb);
				if (isset($tb[0])) {						
					$data=$this->openTableAgenda($tb[0], $data_form);
					if (isset($data)) {
						foreach ($data as $d) {
							$data_approve=$this->model_karyawan->getReviewer($d->id_karyawan,$kode,$id_admin);
							$apr=false;
							if (count($data_approve) > 0) {
								foreach ($data_approve as $da) {
									if ($da['sebagai'] != 'MKR' && !empty($kode['kode_periode']) && $da['id_karyawan'] == $id_admin) {
										$apr=true;
									}
								}
							}
							if ($apr) {
								$kode['id']=$d->id_karyawan;
								$history_poin=$this->model_agenda->getListRaportTahunanHistory($kode);
								$p_old=($d->poin_old)?$d->poin_old:0;
								if ($d->poin_old < $d->poin && $d->poin_old) {
									$p_old=$d->poin_old+$d->poin_now;
								}
								$r_old=$d->nama_rank_old;
								$r_now=$d->nama_rank;
								if (isset($history_poin[0])){
									if($usage == 'tahun' || $usage == 'tahunan'){
										$p_old=$history_poin[0]->poin_old_tahun;
									}else{
										$cols_history='poin_old_'.$kode['kode_periode'];
										$p_old=$history_poin[0]->$cols_history;
									}
									$auto_rank='';
									if($history_poin[0]->auto_rank_up_old){
										$auto_rank.='<br><label class="label label-default">Rank Up Otomatis</label>';
									}
									$r_old=(($history_poin[0]->nama_rank_old)?$history_poin[0]->nama_rank_old.$auto_rank:$this->otherfunctions->getMark());
									$r_now=(($history_poin[0]->nama_rank)?$history_poin[0]->nama_rank:$this->otherfunctions->getMark());
								}
								
								$emp[$d->id_karyawan]=[$d->id_karyawan,
								$d->nik,
								$d->nama,
								$d->nama_jabatan,
								$d->bagian,
								$d->nama_departemen,
								$d->nama_loker,
								$r_old,
								$r_now,
								$p_old, // poin old 	9
								0, //presensi 	10
								0, //sikap		11
								0, //kpi		12
								'Unknown', //	13
								0, //total		14
								0, //punishment	15
								0, //reward		16
								0, //nilai		17
								'Unknown', //huruf		18
								0, //total		19
								];
								if ($usages == 'employee') {
									$emp_select[$d->id_karyawan]=['nama'=>$d->nama,'nama_jabatan'=>$d->nama_jabatan];
								}
							}
						}
					}
				}
			}
			if (isset($emp) && $usages != 'employee'){						
				foreach ($emp as $k_e => $v_e) {
					$nilai_total[$k_e]=0;
					if (isset($table)) {
						foreach ($table as $l_tb) {
							$l_tb=$this->otherfunctions->getParseOneLevelVar($l_tb);
							if (isset($l_tb[0])) {
								if ($l_tb[1] == 'sikap') {
									$sikap=((isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_akhir']:0);
									if (isset($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi'])) {
										$sikap=((!empty($this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi']))?$this->rumusCustomKubotaFinalResultSikap($l_tb[0],$k_e)['nilai_kalibrasi']:$sikap);
									}
									$nilai_total[$k_e]=$nilai_total[$k_e]+$sikap;
									$v_e[11]=$v_e[11]+$sikap;
								}elseif ($l_tb[1] == 'kpi') {
									$kpi=((isset($this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultKpi($l_tb[0],$k_e)['nilai_akhir']:0);
									$nilai_total[$k_e]=$nilai_total[$k_e]+$kpi;
									$v_e[12]=$v_e[12]+$kpi;
								}elseif ($l_tb[1] == 'reward') {
									$reward=((isset($this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']))?$this->rumusCustomKubotaFinalResultReward($l_tb[0],$k_e)['nilai_akhir']:0);
									// $nilai_total[$k_e]=$nilai_total[$k_e]+$reward;
									$v_e[16]=$v_e[16]+$reward;
								}
							}	 
						}
					}
					$v_e[15]=$this->model_karyawan->getPoinSuratPeringatan($k_e,$kode['tahun'],$kode['kode_periode'])['poin_pengurang'];
					$nilai_total[$k_e]=$nilai_total[$k_e]+$this->model_presensi->rumusCustomKubotaFinalResultPresensi($k_e,$kode['kode_periode'],$kode['tahun'],$usage)['nilai_akhir'];
					$p_pengurang=$nilai_total[$k_e]*($v_e[15]/100);
					$nilai_akhir[$k_e]=$nilai_total[$k_e]-$p_pengurang+$v_e[16];
					$poin_old=$v_e[9];
					$v_e[9]=$this->formatter->getNumberFloat($poin_old);
					//presensi
					$v_e[10]=$this->formatter->getNumberFloat($this->model_presensi->rumusCustomKubotaFinalResultPresensi($k_e,$kode['kode_periode'],$kode['tahun'],$usage)['nilai_akhir']);
					$v_e[11]=$this->formatter->getNumberFloat($v_e[11]);
					$v_e[12]=$this->formatter->getNumberFloat($v_e[12]);
					$v_e[14]=$this->formatter->getNumberFloat($nilai_total[$k_e]);
					$v_e[15]=$this->formatter->getNumberFloat($v_e[15]);
					$v_e[16]=$this->formatter->getNumberFloat($v_e[16]);
					$data_konversi=(($usage == 'kuartal')?$this->model_master->getKonversiKuartalNilai($nilai_akhir[$k_e]):$this->model_master->getKonversiTahunanNilai($nilai_total[$k_e]));
					$huruf=((isset($data_konversi['huruf']))?$data_konversi['huruf']:'Unknown');
					$v_e[17]=$this->formatter->getNumberFloat($nilai_akhir[$k_e]);
					$v_e[18]=$huruf;
					$v_e[19]=$this->formatter->getNumberFloat(($nilai_akhir[$k_e]+$poin_old));
					$data_approve=$this->model_karyawan->getReviewer($k_e,$kode,$id_admin);
					$apr=false;
					$btn=null;
					if (count($data_approve) > 0) {
						foreach ($data_approve as $da) {
							if ($da['sebagai'] != 'MKR' && !empty($kode['kode_periode']) && $da['id_karyawan'] == $id_admin) {
								$apr=true;
								$cek=$this->model_agenda->checkApprovalPa($k_e,$da['id_karyawan'],$da['sebagai'],$kode);
								if ($cek) {
									if ($page == 'admin') {
										$btn='<label class="label label-success"><i class="fa fa-check"></i> Sudah Melakukan '.$this->otherfunctions->getSebagaiRaport($da['sebagai']).'</label><br>';
										if (isset($access['access'])) {
											if (in_array('APR', $access['access'])) {
												$btn.=' <button class="btn btn-danger btn-xs" data-toggle="tooltip" onclick="do_approve(0,'.$da['id_karyawan'].',\''.$da['sebagai'].'\','.$k_e.')" title="Cancel Approve"><i class="fa fa-times-circle"></i></button>';
											}
										}							
									}elseif ($page == 'fo'){
										if ($da['id_karyawan'] == $id_admin) {
											$btn=' <button class="btn btn-danger btn-xs" data-toggle="tooltip" onclick="do_approve(0,'.$da['id_karyawan'].',\''.$da['sebagai'].'\','.$k_e.')" title="Cancel Approve"><i class="fa fa-times-circle"></i></button>';
										}
									}						
								}else{
									if ($page == 'admin') {
										$btn='<button class="btn btn-info btn-xs" data-toggle="tooltip" onclick="send_email()" title="Notify '.$da['nama'].'"><i class="fa fa-envelope"></i></button>';
										if (isset($access['access'])) {
											if (in_array('APR', $access['access'])) {
												$btn.=' <button class="btn btn-warning btn-xs" data-toggle="tooltip" onclick="do_approve(1,'.$da['id_karyawan'].',\''.$da['sebagai'].'\','.$k_e.')" title="Approve"><i class="fa fa-check-circle"></i></button>';
											}	
										}
									}elseif ($page == 'fo'){
										$btn='<button class="btn btn-info btn-xs" data-toggle="tooltip" onclick="send_email()" title="Notify '.$da['nama'].'"><i class="fa fa-envelope"></i></button>';
										if ($da['id_karyawan'] == $id_admin) {
											$btn=' <button class="btn btn-warning btn-xs" data-toggle="tooltip" onclick="do_approve(1,'.$da['id_karyawan'].',\''.$da['sebagai'].'\','.$k_e.')" title="Approve"><i class="fa fa-check-circle"></i></button>';
										}
									}											
								}
								$v_e[20]=$btn;
								$v_e[21]=$this->codegenerator->encryptChar($k_e);
							}
						}								
					}
					if ($apr) {
						$emp_data[$k_e]=$v_e;
					}
				}
			}
		}
		return (($usages == 'employee')?$emp_select:$emp_data);
	}
}
