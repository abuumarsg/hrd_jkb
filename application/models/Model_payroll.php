<?php
	/**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Presensi Controller
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_payroll extends CI_Model
{
	protected $CI;
	function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
    }
	public function getPilihDenda()
	{
		$filter=$this->model_global->getFilterbyBagian();
		$this->db->select('a.*,emp.id_karyawan as id_karyawan,emp.nama as nama, emp.jabatan as jabatan, emp.loker as loker, emp.nik as nik, loker.nama as nama_loker, jbt.nama as nama_jabatan');
		$this->db->from('data_denda AS a');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->where('a.lunas', 0);
		$sq=null;			
		if (!empty($filter)){
			$sq=$filter;
		}
		$this->db->where('emp.status_emp = 1 '.((!empty($sq))?' AND '.$sq:null));
		$query=$this->db->get()->result();
		return $query;
	}
	public function cekAngsuranDenda($kodeDenda)
	{
		$this->db->select('*');
		$this->db->from('data_denda_angsuran');
		$this->db->where('kode_denda', $kodeDenda);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function getListDataDenda()
	{
		// $this->db->select('*');
		// $this->db->from('data_denda_angsuran');
		// $this->db->group_by('kode_denda');
		// $query=$this->db->get()->result();
		// return $query;if($usage=='nosearch'){
		$next = 'WHERE id_angsuran = (SELECT max(id_angsuran) FROM data_denda_angsuran x WHERE x.id_karyawan = a.id_karyawan)';
		$sc="SELECT a.*,(select count(*) from data_denda_angsuran cnt where cnt.id_karyawan = a.id_karyawan) as jum
		FROM data_denda_angsuran as a
		$next";
		$filter=$this->model_global->getFilterbyBagian();	
		if (!empty($filter)){
			$sc.=((!empty($next) || $next != '')?' AND '.$filter:' WHERE '.$filter);
		}
		$sc.=" ORDER BY create_date DESC";
		$query=$this->db->query($sc)->result();
		return $query;
	}
	public function getListDendaKode($where = null, $status = 'all_item',$sistem_penggajian = 'BULANAN')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, prd.nama as nama_periode');
		$this->db->from('data_denda_angsuran as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if($sistem_penggajian == 'BULANAN'){
			$this->db->join('data_periode_penggajian as prd','prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
		}elseif($sistem_penggajian == 'HARIAN'){
			$this->db->join('data_periode_penggajian_harian as prd','prd.kode_periode_penggajian_harian = a.kode_periode_penggajian', 'left');
		}
		if($status == 'active'){ $this->db->where('a.status','1'); } 						/*STATUS*/
		if(!empty($where)){ $this->db->where($where); } 										/*WHERE*/
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
// ================================================================ GAJI_BULANAN  ==========================================================
	public function getDataPayroll($where = null, $status = 0, $param = null, $log = false)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		if ($log){
			$this->db->from('log_data_penggajian as a');
		}else{
			$this->db->from('data_penggajian as a');
		}
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if ($where){
			if (isset($where['bagian_multi'])){
				$this->db->where($where['bagian_multi']);
				unset($where['bagian_multi']);
			}
		}
		if($param != null){
			if($param['param'] == 'search'){
				if($param['bagian'] == 'all'){
					if ($param['periode']){
						$this->db->where('a.kode_periode',$param['periode']);
					}
				}else{
					if ($param['periode']){
						$this->db->where('a.kode_periode',$param['periode']);
					}
					if ($param['bagian']){
						$this->db->where('a.kode_bagian',$param['bagian']);
					}
				}
			}
		}
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.tgl_masuk','asc');
		$this->db->group_by('a.id_karyawan,a.kode_periode');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getDataPayrollUser($where = null)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('log_data_penggajian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.tgl_masuk','asc');
		$this->db->group_by('a.id_karyawan,a.kode_periode');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getDataPayrollNew($where = null)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('data_penggajian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('emp.nama','asc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getRekapitulasiDataPayrollBulanan($where = null, $group = 'null')
	{
		$this->db->select('
			a.*,
			bag.nama,
			a.kode_loker,
			sum(a.gaji_bersih) as jumlah_penerimaan,
			sum(a.gaji_pokok) as jumlah_gaji_pokok,
			sum(a.tunjangan_tetap) as jumlah_tunjangan_tetap,
			sum(a.tunjangan_non) as jumlah_tunjangan_non,
			sum(a.ritasi) as jumlah_ritasi,
			sum(a.uang_makan) as jumlah_uang_makan,
			sum(a.pot_tidak_masuk) as jumlah_pot_tidak_masuk,
			sum(a.n_terlambat) as jumlah_terlambat,
			sum(a.n_izin) as jumlah_izin,
			sum(a.n_iskd) as jumlah_iskd,
			sum(a.n_imp) as jumlah_imp,
			sum(a.bpjs_jht) as jumlah_bpjs_jht,
			sum(a.bpjs_jkk) as jumlah_bpjs_jkk,
			sum(a.bpjs_jkm) as jumlah_bpjs_jkm,
			sum(a.bpjs_pen) as jumlah_bpjs_pen,
			sum(a.bpjs_kes) as jumlah_bpjs_kes,
			sum(a.angsuran) as jumlah_angsuran,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.tgl_masuk, 
			emp.nik, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			prd.kode_periode_penggajian, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('data_penggajian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group)){
			$this->db->group_by('a.kode_bagian');
		}
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getRekapitulasiDataLogPayrollBulanan($where = null, $group = 'null')
	{
		$this->db->select('
			a.*,
			bag.nama,
			a.kode_loker,
			sum(a.gaji_bersih) as jumlah_penerimaan,
			sum(a.gaji_pokok) as jumlah_gaji_pokok,
			sum(a.tunjangan_tetap) as jumlah_tunjangan_tetap,
			sum(a.tunjangan_non) as jumlah_tunjangan_non,
			sum(a.ritasi) as jumlah_ritasi,
			sum(a.uang_makan) as jumlah_uang_makan,
			sum(a.pot_tidak_masuk) as jumlah_pot_tidak_masuk,
			sum(a.n_terlambat) as jumlah_terlambat,
			sum(a.n_izin) as jumlah_izin,
			sum(a.n_iskd) as jumlah_iskd,
			sum(a.n_imp) as jumlah_imp,
			sum(a.bpjs_jht) as jumlah_bpjs_jht,
			sum(a.bpjs_jkk) as jumlah_bpjs_jkk,
			sum(a.bpjs_jkm) as jumlah_bpjs_jkm,
			sum(a.bpjs_pen) as jumlah_bpjs_pen,
			sum(a.bpjs_kes) as jumlah_bpjs_kes,
			sum(a.angsuran) as jumlah_angsuran,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.tgl_masuk, 
			emp.nik, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			prd.kode_periode_penggajian, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('log_data_penggajian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group)){
			$this->db->group_by('a.kode_bagian');
		}
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getRekapitulasiDataPayrollBulananAll($where = null, $group = null)
	{
		$this->db->select('
			a.*,
			bag.nama,
			a.kode_loker,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.tgl_masuk, 
			emp.nik, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			prd.kode_periode_penggajian, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('data_penggajian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group)){
			$this->db->group_by('a.kode_bagian');
		}
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getBagianFromPeriodeGajiBulanan($periode,$kode_bagian=null,$log=false)
	{
		$this->db->select('a.*,jab.nama as nama_jabatan,bag.nama as nama_bagian,lok.nama as nama_loker');
		if ($log){
			$this->db->from('log_data_penggajian as a');
		}else{
			$this->db->from('data_penggajian as a');
		}		
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->where('a.kode_periode',$periode);
		if(!empty($kode_bagian)){
			$this->db->where('a.kode_bagian', $kode_bagian);
		} 
		if(empty($kode_bagian)){ 
			$this->db->group_by('a.kode_bagian');
		}
		$query = $this->db->get()->result();
		return $query;
	}
	public function cekDataPenggajianWhere($where)
	{
		$this->db->select('*');
		$this->db->from('data_penggajian');
		$this->db->where($where);
		$query = $this->db->get()->result();
		return $query;
	}
	public function cekDataPenggajian($where)
	{
		return $this->db->get_where('data_penggajian',$where)->result();
	}
	public function cekDataTunjangan($where)
	{
		return $this->db->get_where('data_penggajian_tunjangan',$where)->result();
	}
	public function cekKodePeriode($kodePeriode)
	{
		return $this->db->get_where('data_penggajian',$kodePeriode)->result();
	}
	public function cekKodePeriodeHarian($where)
	{
		return $this->db->get_where('data_penggajian_harian',$where)->result();
	}
	public function cekKodePayrollLembur($kodePeriode)
	{
		return $this->db->get_where('data_penggajian_lembur',$kodePeriode)->result();
	}
	public function getEmployeeWhere($where, $active = 0, $row = false)
	{
		$this->db->select('
			emp.*,
			loker.nama as nama_loker,
			jbt.nama as nama_jabatan,
			bag.nama as bagian,
			bag.id_bagian,
			bag.kode_bagian,
			b.nama as nama_buat,
			c.nama as nama_update,
			grd.nama as nama_grade,
			grd.gapok as gaji_grade,
			grd.um as uang_makan_grade,
			grd.gapok as gaji_pokok_grade,
			d.nama as nama_loker_grade');
		$this->db->from('karyawan AS emp');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_loker AS loker', 'emp.loker = loker.kode_loker', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('admin AS b', 'b.id_admin = emp.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = emp.update_by', 'left'); 
		$this->db->join('master_grade AS grd', 'emp.grade = grd.kode_grade', 'left');
		$this->db->join('master_loker AS d', 'grd.kode_loker = d.kode_loker', 'left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if($active == 1){
			$this->db->where('status_emp',1);
		}
		if($active == '2'){
			$this->db->where("(emp.status_emp = '1' OR emp.status_emp = '2')");
		}
		if($row){
			$query=$this->db->get()->row_array();
		}else{
			$query=$this->db->get()->result();
		}
		return $query;
	}
	public function getListJdwalKerjaWhere($where)
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
		if(isset($where)){ $this->db->where($where); }
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataPayrollTunjangan($where = null)
	{
		$this->db->select('
			a.*,
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('data_penggajian_tunjangan as a');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if(!empty($where)){ $this->db->where($where); } 										/*where*/
		$query = $this->db->get()->result();
		return $query;
	}
	public function getDataPayrollSingle($where = null, $group_by = null)
	{
		if(!empty($where)){ $this->db->where($where); } 
		if(!empty($group_by)){ $this->db->group_by($group_by); }				/*group by*/
		$this->db->order_by('update_date','desc');
		$query = $this->db->get('data_penggajian')->result();
		return $query;
	}
	public function getDataPayrollTunjanganSingle($where = null, $group_by = null)
	{
		if(!empty($where)){ $this->db->where($where); } 						/*where*/
		if(!empty($group_by)){ $this->db->group_by($group_by); }				/*group by*/
		$query = $this->db->get('data_penggajian_tunjangan')->result();
		return $query;
	}

	public function getDataLogPayroll($where = null, $status = 0, $group_by = null)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('log_data_penggajian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if($status == 1){ $this->db->where('a.status','1'); } 
		if ($where){
			if (isset($where['bagian_multi'])){
				$this->db->where($where['bagian_multi']);
				unset($where['bagian_multi']);
			}
		}
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by('a.tgl_masuk','asc');
		$query = $this->db->get()->result();
		return $query;
	}

	public function getDataLogPayrollTunjangan($where = null,$sistem_penggajian = 'BULANAN')
	{
		$this->db->select('
			a.*,
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('log_data_penggajian_tunjangan as a');
		if($sistem_penggajian == 'BULANAN'){
			$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode_penggajian', 'left');
		}else{
			$this->db->join('data_periode_penggajian_harian AS prd', 'prd.kode_periode_penggajian_harian = a.kode_periode_penggajian', 'left');
		}
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if(!empty($where)){ $this->db->where($where); } 	/*where*/
		$query = $this->db->get()->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Penggajian Lembur
	public function getDataPayrollLeburSingle($where = null, $group_by = null)
	{
		if(!empty($where)){ $this->db->where($where); } 
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by('update_date','desc');
		$query = $this->db->get('data_penggajian_lembur')->result();
		return $query;
	}
	public function getJabatanFromPeriode($periode,$kode_jabatan=null,$log=false)
	{
		$this->db->select('a.*,jab.nama as nama_jabatan,bag.nama as nama_bagian,lok.nama as nama_loker');
		if ($log){
			$this->db->from('log_data_penggajian_lembur as a');
		}else{
			$this->db->from('data_penggajian_lembur as a');
		}
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->where('a.kode_periode',$periode);
		if(!empty($kode_jabatan)){
			$this->db->where('a.kode_jabatan', $kode_jabatan);
		} 
		if(empty($kode_jabatan)){ 
			$this->db->group_by('a.kode_jabatan');
		}
		$query = $this->db->get()->result();
		return $query;
	}
	public function getBagianFromPeriode($periode,$kode_bagian=null,$log=false)
	{
		$this->db->select('a.*,jab.nama as nama_jabatan,bag.nama as nama_bagian,lok.nama as nama_loker');
		if ($log){
			$this->db->from('log_data_penggajian_lembur as a');
		}else{
			$this->db->from('data_penggajian_lembur as a');
		}
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->where('a.kode_periode',$periode);
		if(!empty($kode_bagian)){
			$this->db->where('a.kode_bagian', $kode_bagian);
		} 
		if(empty($kode_bagian)){ 
			$this->db->group_by('a.kode_bagian');
		}
		$query = $this->db->get()->result();
		return $query;
	}

	public function getDataPayrollLembur($where = null, $status = 0, $param = null, $order=null)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.tgl_masuk, 
			emp.nik, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			prd.kode_periode_lembur, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('data_penggajian_lembur as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_lembur AS prd', 'prd.kode_periode_lembur = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if($param != null){
			if($param['param'] == 'search'){
				if($param['bagian'] == 'all'){
					if ($param['periode']){
						$this->db->where('a.kode_periode',$param['periode']);
					}
				}else{
					if ($param['periode']){
						$this->db->where('a.kode_periode',$param['periode']);
					}
					if ($param['bagian']){
						$this->db->where('a.kode_bagian',$param['bagian']);
					}
				}
			}
		}
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if($order == null){
			$this->db->order_by('a.tgl_masuk','asc');
		}else{
			$this->db->order_by($order['kolom'],$order['value']);
		}
		$query = $this->db->get()->result();
		return $query;
	}
	public function getDataPayrollLemburExcel($where = null, $order=null)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.tgl_masuk, 
			emp.nik, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			prd.kode_periode_lembur, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('data_penggajian_lembur as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_lembur AS prd', 'prd.kode_periode_lembur = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if(!empty($where)){ $this->db->where($where); }
		if($order==null){
			$this->db->order_by('lok.nama,emp.nama','asc');
		}else{
			$this->db->order_by($order['kolom'],$order['value']);
		}
		$query = $this->db->get()->result();
		return $query;
	}
	public function getDataLogPayrollLembur($where = null, $status = 0, $group_by = null)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('log_data_penggajian_lembur as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_lembur AS prd', 'prd.kode_periode_lembur = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by('a.tgl_masuk','asc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getRekapitulasiDataPayrollLembur($where = null, $status = 0)
	{
		$this->db->select('
			a.*,
			bag.nama,
			a.kode_loker,
			sum(a.gaji_terima) as jumlah,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.tgl_masuk, 
			emp.nik, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			prd.kode_periode_lembur, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('data_penggajian_lembur as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_lembur AS prd', 'prd.kode_periode_lembur = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		// $this->db->where('a.gaji_terima > ',0);
		$this->db->group_by('a.kode_bagian');
		$this->db->order_by('a.tgl_masuk','asc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function getRekapitulasiDataLogPayrollLembur($where = null, $status = 0)
	{
		$this->db->select('
			a.*,
			bag.nama,
			a.kode_loker,
			sum(a.gaji_terima) as jumlah,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.tgl_masuk, 
			emp.nik, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai, 
			prd.tgl_selesai, 
			prd.kode_periode_lembur, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('log_data_penggajian_lembur as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_lembur AS prd', 'prd.kode_periode_lembur = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		// $this->db->where('a.gaji_terima > ',0);
		$this->db->group_by('a.kode_bagian');
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data BPJS
	public function getListBpjsEmp($where = null, $status = 0)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, emp.nama as nama_karyawan, emp.nik, jbt.nama as nama_jabatan');
		$this->db->from('data_bpjs as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan as emp','emp.id_karyawan = a.id_karyawan','left');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		if($status == 1){ $this->db->where('a.status',1); }
		if(!empty($where)){ $this->db->where($where); }
		// $this->db->order_by('a.status','desc');
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkBpjsEmpCode($code)
	{
		return $this->model_global->checkCode($code,'data_bpjs','kode_k_bpjs');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Pendukung Lain
	public function getListDataPendukungLain($where = null, $status = 0, $group_by = null, $order_by = null)
	{
		if(empty($order_by)){ $order_by = "a.update_date desc"; }
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, emp.nama as nama_karyawan, emp.nik, jbt.nama as nama_jabatan, prd.nama as nama_periode');
		$this->db->from('data_pendukung_lain as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan as emp','emp.id_karyawan = a.id_karyawan','left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		if($status == 1){ $this->db->where('a.status','1'); } 								/*status*/
		if(!empty($where)){ $this->db->where($where); } 										/*where*/
		if(!empty($group_by)){ $this->db->group_by($group_by); } 								/*group_by*/
		$this->db->order_by($order_by);																/*order_by*/
		$query = $this->db->get()->result();
		return $query;
	}
	public function checkPendukungLainCode($code)
	{
		return $this->model_global->checkCode($code,'data_pendukung_lain','kode_pen_lain');
	}

	//==================================== DATA PPH 21 ==============================================================================//
	//Data PPH 21
	public function cekdatapph($where)
	{
		$this->db->select('*');
		$this->db->from('data_penggajian_pph');
		if(!empty($where)){ $this->db->where($where); } 
		$query = $this->db->get()->result();
		return $query;	
	}
	public function getListDataPenggajianPph($where = null, $status = 0, $group_by = null, $order_by = null,$max=null,$row=false)
	{
		if(empty($order_by)){ $order_by = "a.update_date desc"; }
		$this->db->select('
			a.*,
			b.nama as nama_buat,
			c.nama as nama_update,
			jbt.nama as nama_jabatan,
			bag.nama as nama_bagian,
			lok.nama as nama_loker,
			grd.nama as nama_grade,
			msp.nama as nama_sistem_penggajian,
			prd.nama as nama_periode,
			prd.tgl_mulai as tgl_mulai_periode,
			prd.tgl_selesai as tgl_selesai_periode,
			');
			// emp.no_ktp,
		$this->db->from('data_penggajian_pph as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = a.kode_master_penggajian', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		// $this->db->join('karyawan AS emp', 'emp.nik = a.nik', 'left');
		if(!empty($max)){
			$this->db->where('a.koreksi = (SELECT max(x.koreksi) FROM data_penggajian_pph x WHERE x.nik = a.nik AND x.kode_periode = a.kode_periode)');
		}
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by($order_by);
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	public function getPPH1721A1($where = null, $status = null, $group_by = null, $order_by = null,$max=null,$row=false)
	{
		if(empty($order_by)){ $order_by = "a.update_date desc"; }
		$this->db->select('
			a.*,
			b.nama as nama_buat,
			c.nama as nama_update,
			jbt.nama as nama_jabatan,
			bag.nama as nama_bagian,
			lok.nama as nama_loker,
			grd.nama as nama_grade,
			msp.nama as nama_sistem_penggajian,
			prd.nama as nama_periode,
			prd.tgl_mulai as tgl_mulai_periode,
			prd.tgl_selesai as tgl_selesai_periode,
			');
		// $this->db->from('karyawan as emp');
		// $this->db->join('data_penggajian_pph AS a', 'emp.nik = a.nik', 'left');
		$this->db->from('data_penggajian_pph as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = a.kode_master_penggajian', 'left');
		$this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		if(!empty($max)){
			$this->db->where('a.koreksi = (SELECT max(x.koreksi) FROM data_penggajian_pph x WHERE x.nik = a.nik AND x.kode_periode = a.kode_periode)');
		}
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by($order_by);
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	//==================================== DATA PPH 21 HARIAN ========================================================================//
	//Data PPH 21 HARIAN
	public function cekdatapphHarian($where)
	{
		$this->db->select('*');
		$this->db->from('data_pph_harian');
		if(!empty($where)){ $this->db->where($where); } 
		$query = $this->db->get()->result();
		return $query;	
	}
	public function getListDataPenggajianPphHarian($where = null, $status = 0, $group_by = null, $order_by = null,$max=null,$row=false)
	{
		if(empty($order_by)){ $order_by = "a.update_date desc"; }
		$this->db->select('
			a.*,
			b.nama as nama_buat,
			c.nama as nama_update,
			jbt.nama as nama_jabatan,
			bag.nama as nama_bagian,
			lok.nama as nama_loker,
			grd.nama as nama_grade,
			');
			// msp.nama as nama_sistem_penggajian,
			// prd.nama as nama_periode,
			// prd.tgl_mulai as tgl_mulai_periode,
			// prd.tgl_selesai as tgl_selesai_periode,
		$this->db->from('data_pph_harian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		// $this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = a.kode_master_penggajian', 'left');
		// $this->db->join('data_periode_penggajian AS prd', 'prd.kode_periode_penggajian = a.kode_periode', 'left');
		// $this->db->join('karyawan AS emp', 'emp.nik = a.nik', 'left');
		if(!empty($max)){
			$this->db->where('a.koreksi = (SELECT max(x.koreksi) FROM data_penggajian_pph x WHERE x.nik = a.nik AND x.kode_periode = a.kode_periode)');
		}
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by($order_by);
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
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
	public function cekDataLemburPresensi($id_k,$tgl)
	{
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('id_karyawan',$id_k);
		$this->db->where('tgl_mulai <=',$tgl);
		$this->db->where('tgl_selesai >=',$tgl);
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
		$query=$this->db->get()->row_array();
		return $query;
    }
    public function getKaryawanBagianJoin($kode)
    {
        $this->db->select('emp.nama as nama_kar,emp.id_karyawan as id_kar,bag.nama as bagian,emp.foto as foto,emp.tgl_masuk as tgl_masuk,emp.nik as nik,jab.nama as nama_jabatan,bag.nama as nama_bagian,lok.nama as loker,bag.kode_bagian as kode_bagian');
        $this->db->from('karyawan emp');
        $this->db->join('master_jabatan jab', 'jab.kode_jabatan = emp.jabatan','left');
        $this->db->join('master_bagian bag', 'bag.kode_bagian = jab.kode_bagian','left');
        $this->db->join('master_loker lok', 'lok.kode_loker = emp.loker','left');
        $this->db->where('bag.kode_bagian', $kode);
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
// ========================================================= GAJI_HARIAN ===================================================================
	public function cekPenggajianHarian($where)
	{
		return $this->db->get_where('data_penggajian_harian',$where)->result();
	}
	public function cekPenggajianHarianRow($where)
	{
		return $this->db->get_where('data_penggajian_harian',$where)->row_array();
	}
	public function getPenggajianHarianRow($where)
	{
		$this->db->select('gaji_diterima, gaji_lembur');
		$this->db->from('data_penggajian_harian');
		$this->db->where($where);
		$query=$this->db->get()->row_array();
		return $query;
	}	
	public function getDataPayrollHarian($where = null, $status = 0, $group_by = null, $order_by = 'a.update_date desc',$row = false)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.id_karyawan as id_karyawan, 
			emp.rekening, 
			emp.no_ktp, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade,');
			// msp.nama as nama_sistem_penggajian, 
			// mtu.nama as nama_umk, 
			// mtu.tarif
			// prd.nama as nama_periode, 
			// prd.tgl_mulai as tgl_mulai, 
			// prd.tgl_selesai as tgl_selesai, 
		$this->db->from('data_penggajian_harian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		// $this->db->join('data_periode_penggajian_harian AS prd', 'prd.kode_periode_penggajian_harian = a.kode_periode', 'left');
		// $this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		// $this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');
		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	public function getDataPayrollHarianNew($where = null, $status = 0, $group_by = null, $order_by = 'a.update_date desc',$row = false)
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.id_karyawan as id_karyawan, 
			emp.rekening, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade');
		$this->db->from('data_penggajian_harian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	public function getDataPayrollHarianBagianNew($where = null, $status = 0, $group_by = null, $order_by = 'a.update_date desc',$row = false)
	{
		$this->db->select('
			a.*,
			sum(a.gaji_bersih) as jumlah_gaji_bersih,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.id_karyawan as id_karyawan, 
			emp.rekening, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade');
		$this->db->from('data_penggajian_harian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		if($status == '1'){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		if(!empty($order_by)){ $this->db->order_by($order_by); }
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	public function getDataLogPayrollSingle($where = null, $group_by = null)
	{
		if(!empty($where)){ $this->db->where($where); } 
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by('update_date','desc');
		$query = $this->db->get('data_penggajian_harian')->result();
		return $query;
	}
	public function getBagianFromPeriodeGajiHarian($periode,$kode_bagian=null)
	{
		$this->db->select('a.*,jab.nama as nama_jabatan,bag.nama as nama_bagian,lok.nama as nama_loker');
		$this->db->from('data_penggajian_harian as a');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->where('a.kode_periode',$periode);
		if(!empty($kode_bagian)){
			$this->db->where('a.kode_bagian', $kode_bagian);
		} 
		if(empty($kode_bagian)){ 
			$this->db->group_by('a.kode_bagian');
		}
		$query = $this->db->get()->result();
		return $query;
	}
	public function getKaryawanFromBagianLokerGajiHarian($bagian,$lokasi,$minggu,$bulan,$tahun)
	{
		$this->db->select('a.*,jab.nama as nama_jabatan,bag.nama as nama_bagian,lok.nama as nama_loker');
		$this->db->from('data_penggajian_harian as a');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_jabatan AS jab', 'jab.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->where('a.kode_bagian',$bagian);
		$this->db->where('a.kode_loker',$lokasi);
		$this->db->where('a.minggu',$minggu);
		$this->db->where('a.bulan',$bulan);
		$this->db->where('a.tahun',$tahun);
		if(empty($kode_bagian)){ 
			$this->db->group_by('a.id_karyawan');
		}
		$query = $this->db->get()->result();
		return $query;
	}
	public function getRekapitulasiDataPayrollHarian($where = null, $status = 0)
	{
		$this->db->select('
			a.*,
			bag.nama,
			a.kode_loker,
			sum(a.gaji_bersih) as jumlah,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.tgl_masuk, 
			emp.nik, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade');
		$this->db->from('data_penggajian_harian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->group_by('a.kode_bagian');
		$this->db->order_by('a.update_date','desc');
		$query = $this->db->get()->result();
		return $query;
	}
	
	//========================================== DATA LOG PAYROLL HARIAN =========================================================//
	//DATA LOG PAYROLL HARIAN
	public function cekLogPenggajianHarian($where)
	{
		return $this->db->get_where('log_data_penggajian_harian',$where)->result();
	}
	public function getDataLogPayrollHarian($where = null, $status = 0, $group_by = null, $order_by = 'a.update_date desc')
	{
		$this->db->select('
			a.*,
			b.nama as nama_buat, 
			c.nama as nama_update, 
			emp.nama as nama_karyawan, 
			emp.rekening, 
			emp.id_karyawan as id_karyawan, 
			jbt.nama as nama_jabatan, 
			bag.nama as nama_bagian, 
			lok.nama as nama_loker, 
			grd.nama as nama_grade, 
			prd.nama as nama_periode, 
			prd.tgl_mulai as tgl_mulai, 
			prd.tgl_selesai as tgl_selesai, 
			msp.nama as nama_sistem_penggajian, 
			mtu.nama as nama_umk, 
			mtu.tarif');
		$this->db->from('log_data_penggajian_harian as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = a.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'bag.kode_bagian = a.kode_bagian', 'left');
		$this->db->join('master_loker AS lok', 'lok.kode_loker = a.kode_loker', 'left');
		$this->db->join('master_grade AS grd', 'grd.kode_grade = a.kode_grade', 'left');
		$this->db->join('data_periode_penggajian_harian AS prd', 'prd.kode_periode_penggajian_harian = a.kode_periode', 'left');
		$this->db->join('master_sistem_penggajian AS msp', 'msp.kode_master_penggajian = prd.kode_master_penggajian', 'left');
		$this->db->join('master_tarif_umk AS mtu', 'mtu.kode_tarif_umk = prd.kode_tarif_umk', 'left');

		if($status == '1'){ $this->db->where('a.status','1'); }		/* STATUS */
		if(!empty($where)){ $this->db->where($where); }					/* WHERE */
		if(!empty($group_by)){ $this->db->group_by($group_by); }		/* GROUP BY */
		if(!empty($order_by)){ $this->db->order_by($order_by); }		/* ORDER BY */
		
		$query = $this->db->get()->result();
		return $query;
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Denda
	public function getListDenda($where = null, $status = 'all_item',$sistem_penggajian = 'BULANAN')
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, prd.nama as nama_periode');
		$this->db->from('data_denda_angsuran as a');
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
	//========================================================= DATA PPH 21 NON KARYAWAN  ====================================================//
	public function cekdatapphnon($where)
	{
		$this->db->select('*');
		$this->db->from('data_penggajian_pph21_non');
		if(!empty($where)){ $this->db->where($where); } 
		$query = $this->db->get()->result();
		return $query;	
	}
	public function getListDataPenggajianPphNon($where = null, $status = 0, $group_by = null, $order_by = null, $max=null, $row=false)
	{
		if(empty($order_by)){ $order_by = "a.update_date desc"; }
		$this->db->select('
			a.*,
			b.nama as nama_buat,
			c.nama as nama_update,
		');
		$this->db->from('data_penggajian_pph21_non as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		if(!empty($max)){
			$this->db->where('a.koreksi = (SELECT max(x.koreksi) FROM data_penggajian_pph21_non x WHERE x.nik = a.nik AND x.bulan = a.bulan AND x.tahun = a.tahun)');
		}
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by($order_by);
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	//========================================================= DATA KODE AKUN PPH 21 ==========================================================//
	public function getListDataKodeAkunPPH21($where = null, $status = 0, $group_by = null, $order_by = null)
	{
		if(empty($order_by)){ $order_by = "a.update_date desc"; }
		$this->db->select('
			a.*,
			b.nama as nama_buat,
			c.nama as nama_update,
			emp.nama as nama_karyawan,
			emp.nik as nik,
			pd.nama as nama_akun,
			j.nama as nama_mengetahui,
			k.nama as jbt_mengetahui,
			n.nama as nama_menyetujui,
			o.nama as jbt_menyetujui,
		');
		$this->db->from('data_pph_kode_akun as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = a.id_karyawan', 'left');
		$this->db->join('master_pd_kode_akun AS pd', 'pd.kode_akun = a.kode_akun', 'left');
		$this->db->join('karyawan AS j', 'j.id_karyawan = a.mengetahui', 'left');
		$this->db->join('master_jabatan AS k', 'k.kode_jabatan = j.jabatan', 'left');
		$this->db->join('karyawan AS n', 'n.id_karyawan = a.menyetujui', 'left');
		$this->db->join('master_jabatan AS o', 'o.kode_jabatan = n.jabatan', 'left');
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		if(!empty($group_by)){ $this->db->group_by($group_by); }
		$this->db->order_by($order_by);
		$query = $this->db->get()->result();
		return $query;
	}
	public function getWhereDataKodeAkunPPH21($where = null)
	{
		$this->db->select('*');
		$this->db->from('data_pph_kode_akun');
		if(!empty($where)){
			$this->db->where($where);
		}
		$query = $this->db->get()->result();
		return $query;
	}
	//________________________ Data PINJAMAN ________________________
	public function getListPinjaman($where = null, $status = 0, $row = false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, emp.nama as nama_karyawan, jbt.nama as nama_jabatan, bag.nama as nama_bagian, loker.nama as nama_loker,
		(select count(*) from data_angsuran cnt where cnt.kode_pinjaman = a.kode_pinjaman) as jum_ang,
		');
		$this->db->from('data_pinjaman as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('karyawan as emp','emp.id_karyawan = a.id_karyawan','left');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_loker AS loker', 'loker.kode_loker = emp.loker', 'left');
		$this->db->join('data_angsuran AS ang', 'ang.kode_pinjaman = a.kode_pinjaman', 'left');
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','desc');
		$this->db->group_by('a.kode_pinjaman');
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	public function checkPinjamanCode($code)
	{
		return $this->model_global->checkCode($code,'data_pinjaman','kode_pinjaman');
	}
	public function getListAngsuran($where = null, $status = 0, $row = false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update,emp.nama as nama_karyawan, jbt.nama as nama_jabatan, bag.nama as nama_bagian, loker.nama as nama_loker,pin.nama as nama_pinjaman');
		$this->db->from('data_angsuran as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('data_pinjaman AS pin', 'pin.kode_pinjaman = a.kode_pinjaman', 'left');
		$this->db->join('karyawan as emp','emp.id_karyawan = pin.id_karyawan','left');
		$this->db->join('master_jabatan AS jbt', 'emp.jabatan = jbt.kode_jabatan', 'left');
		$this->db->join('master_bagian AS bag', 'jbt.kode_bagian = bag.kode_bagian', 'left');
		$this->db->join('master_loker AS loker', 'loker.kode_loker = emp.loker', 'left');
		if($status == 1){ $this->db->where('a.status','1'); }
		if(!empty($where)){ $this->db->where($where); }
		$this->db->order_by('a.update_date','ASC');
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	public function getListPeriodeLembur($where=null, $row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, umk.nama as nama_umk, umk.tarif, sip.nama as nama_sistem_penggajian, lok.nama as nama_loker');
		$this->db->from('data_periode_lembur as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_tarif_umk','left');
		$this->db->join('master_sistem_penggajian as sip','sip.kode_master_penggajian = a.kode_master_penggajian','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
	public function getListPeriodeLemburDetail($where = null, $row=false)
	{
		$this->db->select('a.*,b.nama as nama_buat, c.nama as nama_update, lok.nama as nama_loker, umk.nama as nama_umk, umk.tarif');
		$this->db->from('data_periode_lembur_detail as a');
		$this->db->join('admin AS b', 'b.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS c', 'c.id_admin = a.update_by', 'left');
		$this->db->join('data_periode_lembur as dpg','dpg.kode_periode_lembur = a.kode_periode_lembur','left');
		$this->db->join('master_loker as lok','lok.kode_loker = a.kode_loker','left');
		$this->db->join('master_tarif_umk as umk','umk.kode_tarif_umk = a.kode_umk','left');
		if(!empty($where)){
			$this->db->where($where);
		}
		if($row){
			$query = $this->db->get()->row_array();
		}else{
			$query = $this->db->get()->result();
		}
		return $query;
	}
}
