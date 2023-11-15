<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	 * Code From GFEACORP.
	 * Web Developer
	 * @author 		Galeh Fatma Eko Ardiansa
	 * @package		Model Presensi
	 * @copyright	Copyright (c) 2018 GFEACORP
	 * @version 	1.0, 1 September 2018
	 * Email 		galeh.fatma@gmail.com
	 * Phone		(+62) 85852924304
	 */

class Model_presensi extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	public function getListPresensi($active=false,$filter=0)
	{
		$this->db->select('a.*,b.nama as nama,b.nik as nik, c.nama as nama_jabatan, d.nama as nama_loker, e.nama as nama_bagian');
		$this->db->from('data_presensi_pa AS a');
		$this->db->join('karyawan AS b', 'b.nik = a.nik', 'left'); 
		$this->db->join('master_jabatan AS c', 'c.kode_jabatan = b.jabatan', 'left'); 
		$this->db->join('master_loker AS d', 'd.kode_loker = b.loker', 'left'); 
		$this->db->join('master_bagian AS e', 'e.kode_bagian = c.kode_bagian', 'left'); 
		if ($active) {
			$this->db->where('a.status',$active);
		}
		$this->db->order_by('b.nama,a.bulan,a.tahun','ASC');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPresensi($id)
	{
		$this->db->select('a.*,b.nama as nama,b.nik as nik, c.nama as nama_jabatan, d.nama as nama_loker, e.nama as nama_bagian,f.nama as nama_buat, g.nama as nama_update');
		$this->db->from('data_presensi_pa AS a');
		$this->db->join('karyawan AS b', 'b.nik = a.nik', 'left'); 
		$this->db->join('master_jabatan AS c', 'c.kode_jabatan = b.jabatan', 'left'); 
		$this->db->join('master_loker AS d', 'd.kode_loker = b.loker', 'left'); 
		$this->db->join('master_bagian AS e', 'e.kode_bagian = c.kode_bagian', 'left'); 
		$this->db->join('admin AS f', 'f.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS g', 'g.id_admin = a.update_by', 'left'); 
		$this->db->where('a.id_presensi',$id); 
		$query=$this->db->get()->result();
		return $query;
	}
	public function getPresensiEmployee($id,$bulan=false)
	{
		$this->db->select('a.*,b.nama as nama,b.nik as nik, c.nama as nama_jabatan, d.nama as nama_loker, e.nama as nama_bagian,f.nama as nama_buat, g.nama as nama_update');
		$this->db->from('data_presensi_pa AS a');
		$this->db->join('karyawan AS b', 'b.id_karyawan = a.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS c', 'c.kode_jabatan = b.jabatan', 'left'); 
		$this->db->join('master_loker AS d', 'd.kode_loker = b.loker', 'left'); 
		$this->db->join('master_bagian AS e', 'e.kode_bagian = c.kode_bagian', 'left'); 
		$this->db->join('admin AS f', 'f.id_admin = a.create_by', 'left'); 
		$this->db->join('admin AS g', 'g.id_admin = a.update_by', 'left'); 
		$this->db->where('b.id_karyawan',$id); 
		if ($bulan && $bulan != '') {
			$this->db->where('a.bulan',$bulan);
		}
		$query=$this->db->get()->result();
		return $query;
	}
	public function checkPresensiMonthYear($nik,$bulan,$y)
	{
		if (empty($nik) || empty($bulan) || empty($y))
			return false;
		$this->db->where('nik',$nik); 
		$this->db->where('bulan',$bulan); 
		$this->db->where('tahun',$y); 
		$query=$this->db->get('data_presensi_pa')->num_rows();

		if ($query > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function rumusCustomKubotaFinalResultPresensi($id,$periode,$tahun,$usage='kuartal')
	{
		$pack=['sp'=>0,'sp_konv'=>0,'ijin'=>0,'ijin_konv'=>0,'bolos'=>0,'bolos_konv'=>0,'telat'=>0,'telat_konv'=>0,'nilai_akhir'=>0,'avl'=>false];
		if (empty($id) || empty($tahun))
			return $pack;
		$data=$this->getPresensiEmployee($id);	
		$data_periode_thn=$this->model_master->getListPeriodePenilaian(1);	
		if (isset($data)) {
			foreach ($data as $d) {
				if ($usage == 'kuartal') {
					$data_periode=$this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenilaianKode($periode));
					$month=$this->formatter->getNameOfMonthByPeriodeNum($data_periode['start'],$data_periode['end'],$tahun);
					if (isset($month)) {
						$d_month=$this->otherfunctions->addFrontZero($d->bulan);
						if ((in_array($d_month, $month)) && $d->tahun == $tahun) {
							$pack['sp']=$pack['sp']+$d->sp;
							$pack['ijin']=$pack['ijin']+$d->ijin;
							$pack['bolos']=$pack['bolos']+$d->mangkir;
							$pack['telat']=$pack['telat']+$d->telat;
							$pack['avl'][$periode]=true;
						}
					}
				}else{
					if ($d->tahun == $tahun) {
						$pack['sp']=$pack['sp']+$d->sp;
						$pack['ijin']=$pack['ijin']+$d->ijin;
						$pack['bolos']=$pack['bolos']+$d->mangkir;
						$pack['telat']=$pack['telat']+$d->telat;
					}
				}
				// $pack['sp']=$pack['sp']+0;
				// $pack['ijin']=$pack['ijin']+0;
				// $pack['bolos']=$pack['bolos']+0;
				// $pack['telat']=$pack['telat']+0;
				// $pack['avl'][$periode]=true;				
			}
			$pack['sp_konv']=$pack['sp_konv']+$this->model_master->getKonversiPresensiNilai($pack['sp'],'SP')['nilai'];
			$pack['ijin_konv']=$pack['ijin_konv']+$this->model_master->getKonversiPresensiNilai($pack['ijin'],'IJIN')['nilai'];
			$pack['bolos_konv']=$pack['bolos_konv']+$this->model_master->getKonversiPresensiNilai($pack['bolos'],'BOLOS')['nilai'];
			$pack['telat_konv']=$pack['telat_konv']+$this->model_master->getKonversiPresensiNilai($pack['telat'],'TELAT')['nilai'];
		}
		$pack['nilai_akhir']=($pack['sp_konv']+$pack['ijin_konv']+$pack['bolos_konv']+$pack['telat_konv']);
		return $pack;
	}

//========================================================== PRESENSI API ==============================================
//
	public function getListPresensiPlainApi($limit = 100,$sync=0)
    {
        $this->db->select('a.id_temporari,a.id_karyawan,a.tanggal,a.jam');
        $this->db->from('temporari_data_presensi AS a');
        if (!$sync) {
            $this->db->where('a.sync',0);
        }
        $this->db->limit($limit); 
        $this->db->order_by('create_date','ASC');
        $query=$this->db->get()->result_array();
        return $query;
    }
	public function getListPresensiPlainApiCoba($limit = 100,$sync=0)
    {
        $this->db->select('a.id_temporari,a.id_karyawan,a.tanggal,a.jam');
        $this->db->from('temporari_data_presensi AS a');
        if (!$sync) {
            $this->db->where('a.sync',0);
        }
        $this->db->limit($limit); 
        $this->db->order_by('create_date','ASC');
        $query=$this->db->get()->result();
        return $query;
    }
	public function checkPresensiWhere($where)
	{
		if (empty($where))
			return false;
		$query=$this->db->get_where('data_presensi',$where)->num_rows();
		if ($query > 0) {
			return true;
		}else{
			return false;
		}
	}
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
	public function cekJadwalKerjaIdDate($id, $date, $avb = null)
	{
		if(empty($id) || empty($date))
			return null;
		$val = 'false';
		$data = $this->getListJdwalKerja(['usage'=>'id'],$id);
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
			// print_r($date);
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
	public function coreImportPresensi($data)
	{
		$ret=[];
		if (empty($data)) 
			return $ret;
		if (isset($data['jadwal'])) {
			$ret['id_karyawan']=$data['id_karyawan'];
			$ret['tanggal']=$data['tanggal'];
			$tglx2=date('Y-m-d',strtotime($data['tanggal'].' -1 day'));
			$cekjadwal = $this->model_presensi->cekJadwalKerjaIdDate($data['id_karyawan'],$tglx2);
			if (!empty($cekjadwal['jam_mulai']) && !empty($cekjadwal['jam_selesai'])) {
				if (strtotime($cekjadwal['jam_selesai']) < strtotime($cekjadwal['jam_mulai'])) {
					$half=$this->formatter->difHalfTime($cekjadwal['jam_mulai'],$cekjadwal['jam_selesai']);
					//toleransi jam masuk
					$tol_s_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],4,'-');
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
				$tol_s_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],4,'-');
				$tol_e_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],$half,'+');
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],8,'+');
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
	public function cekIzinCutiIdDate($idk,$date)
	{
		$this->db->select('*');
		$this->db->from('data_izin_cuti_karyawan');
		$this->db->where('"'.$date.'" BETWEEN tgl_mulai AND tgl_selesai');
		$this->db->where('id_karyawan',$idk);
		// $this->db->where('tgl_mulai',$date);
		$this->db->where('validasi',1);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function cekLemburIdDate($idk,$date,$where = null,$row = true)
	{
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('"'.$date.'" BETWEEN tgl_mulai AND tgl_selesai');
		$this->db->where('id_karyawan',$idk);
		// $this->db->where('tgl_mulai',$date);
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
	public function cekLemburIdDateNew($idk,$date,$where = null,$row = true)
	{
		$this->db->select('*');
		$this->db->from('data_lembur');
		// $this->db->where('"'.$date.'" BETWEEN tgl_mulai AND tgl_selesai');
		$this->db->where('id_karyawan',$idk);
		$this->db->where('tgl_mulai',$date);
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
	public function cekLemburNoSPL($no, $dibuat=null, $diperiksa=null, $diketahui=null)
	{
		$this->db->select('*');
		$this->db->from('data_lembur');
		$this->db->where('no_lembur',$no);
		if(!empty($dibuat)){
			$this->db->where('dibuat_oleh',$dibuat);
		}
		if(!empty($diperiksa)){
			$this->db->where('diperiksa_oleh',$diperiksa);
		}
		if(!empty($diketahui)){
			$this->db->where('diketahui_oleh',$diketahui);
		}
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function checkPresensiTime($id,$tgl,$time,$usage)
	{
		$this->db->select('jam_mulai,jam_selesai');
		$this->db->from('data_presensi');
		$this->db->where('tanggal',$tgl);
		$this->db->where('id_karyawan',$id);
		if ($usage == 'jam_mulai') {
			$this->db->where("jam_mulai < '$time' AND jam_mulai IS NOT NULL");
		}elseif ($usage == 'jam_selesai') {
			$this->db->where("jam_selesai < '$time' AND jam_selesai IS NOT NULL");
		}
		$query=$this->db->get()->row_array();
		return (($query)?true:false);
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
	public function getPresensiIdDate($id,$date)
	{
		if (empty($id) || empty($date))
			return false;
			$this->db->select('a.*,b.istirahat_mulai,b.istirahat_selesai');
		$this->db->from('data_presensi AS a');
		$this->db->join('master_shift AS b', 'b.kode_master_shift = a.kode_shift', 'left');
		$this->db->where('a.id_karyawan',$id); 
		$this->db->where('a.tanggal',$date);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function checkLemburDate($id,$date)
	{
		if (empty($id) || empty($date))
			return false;
		$this->db->where('id_karyawan',$id); 
		$this->db->where('tgl_mulai',$date);
		$query=$this->db->get('data_lembur')->num_rows();
		return $query;
	}
	public function checkLemburSyncIDDate($id_karyawan,$tgl)
	{
		if (empty($id_karyawan) || empty($tgl))
			return false;
		$ceklembur = $this->checkLemburDate($id_karyawan,$tgl);
		if($ceklembur > 1){
			$lemburx = $this->cekLemburIdDate($id_karyawan,$tgl,['validasi'=>1],false);
			$cekjadwalx = $this->cekJadwalKerjaIdDateJKB($id_karyawan,$tgl);
			$lembur = '';
			foreach ($lemburx as $d) {
				$mulai_val = $this->otherfunctions->getDataExplode($d->val_tgl_mulai,' ','end');
				if($mulai_val >= $cekjadwalx['jam_selesai']){
					$lembur = $this->model_karyawan->getLemburIDLembur($d->id_data_lembur);
				}
			}
		}elseif($ceklembur == 1){
			$lembur = $this->cekLemburIdDate($id_karyawan,$tgl,['validasi'=>1]);
		}else{
			$lembur = null;
		}
		return $lembur;
	}
	public function cekNoSPL($no_spl)
	{
		if (empty($no_spl))
			return false;
		$this->db->where('no_lembur',$no_spl);
		$query=$this->db->get('data_lembur')->num_rows();
		if ($query > 0) {
			return '<h3 class="text-muted"><font color="red"><b>No SPL Sudah digunakan</b></font></h3>';
		}else{
			return '<h3 class="text-muted"><font color="green"><b>No SPL Tersedia</b></font></h3>';
		}
	}
	public function getDetailPresensiPayrollRow($data=null)
	{
		$mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'start')));
		$selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($data['tanggal'],'end')));
		$this->db->select("sub.*,main.nama as nama_karyawan, main.nik, main.id_karyawan, main.id_finger, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur, mi.nama as nama_izin,
		msf.jam_mulai as jadwal_mulai,
		msf.jam_selesai as jadwal_selesai,
		msf.nama as nama_shift,
		(sub.jam_mulai is not null and sub.jam_selesai is not null and sub.kode_ijin is null) AND (sub.no_spl is not null) as lembur,
		IF ((sub.jam_mulai is not null and sub.kode_ijin IS NULL and sub.jam_mulai > msf.jam_mulai), SEC_TO_TIME(TIME_TO_SEC(sub.jam_mulai)-TIME_TO_SEC(msf.jam_mulai)), 0) as terlambat,
		IF ((sub.jam_selesai is not null and sub.kode_ijin IS NULL and sub.jam_selesai < msf.jam_selesai), SEC_TO_TIME(TIME_TO_SEC(msf.jam_selesai)-TIME_TO_SEC(sub.jam_selesai)), 0) as pulang_cepat,
		IF ((sub.jam_mulai is not null and sub.jam_selesai is not null and sub.kode_ijin IS NULL),SEC_TO_TIME(TIME_TO_SEC(sub.jam_selesai)-TIME_TO_SEC(sub.jam_mulai)), 0) as lama_jam_kerja,
		SEC_TO_TIME(TIME_TO_SEC(msf.jam_selesai)-TIME_TO_SEC(msf.jam_mulai)) as lama_jadwal_kerja
		");
		$this->db->from('data_presensi AS sub');
		$this->db->join('karyawan AS main', 'main.id_karyawan = sub.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = main.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = main.loker', 'left');
		$this->db->join('master_shift AS msf', 'msf.kode_master_shift = sub.kode_shift and msf.status = 1', 'left');
		$this->db->join('data_izin_cuti_karyawan AS dti', 'dti.status = 1 and dti.kode_izin_cuti = sub.kode_ijin', 'left'); 
		$this->db->join('master_izin AS mi', 'mi.status = 1 and mi.kode_master_izin = dti.jenis', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = sub.kode_hari_libur', 'left');
		if(!empty($data['tanggal'])){
			$this->db->where("sub.tanggal BETWEEN '".$mulai."' AND '".$selesai."'");
 		}
		if(!empty($data['bagian'])){
			$this->db->where('jbt.kode_bagian',$data['bagian']);
		}
		if(!empty($data['id_karyawan'])){
			$this->db->where('sub.id_karyawan',$data['id_karyawan']);
		}
		$this->db->order_by('sub.tanggal','ASD');
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDetailPresensiForPayroll($id_kar,$mulai,$selesai)
	{
		$this->db->select("sub.*,main.nama as nama_karyawan, main.nik, main.id_karyawan, main.id_finger, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker, lbr.nama as nama_libur, mi.nama as nama_izin,, mi.kode_master_izin as kode_master_izin,
		msf.jam_mulai as jadwal_mulai,
		msf.jam_selesai as jadwal_selesai,
		msf.nama as nama_shift,
		(sub.jam_mulai is not null and sub.jam_selesai is not null and sub.kode_ijin is null) AND (sub.no_spl is not null) as lembur,
		IF ((sub.jam_mulai is not null and sub.jam_mulai > msf.jam_mulai), IF (((JSON_UNQUOTE(JSON_EXTRACT(IF (jbt.setting != '',jbt.setting,null),'$.terlambat')) = 'no') and (sub.kode_hari_libur is not null or DATE_FORMAT(sub.tanggal, '%a') = 'Sun')),0,count_terlambat(bef.jam_selesai,sub.jam_mulai,msf.jam_mulai,JSON_UNQUOTE(JSON_EXTRACT( IF ( jbt.setting != '', `jbt`.`setting`, NULL ), '$.dispensasi_jam_masuk' )))), 0) as terlambat,
		IF ((sub.jam_selesai is not null and sub.kode_ijin IS NULL and sub.jam_selesai < msf.jam_selesai), SEC_TO_TIME(TIME_TO_SEC(msf.jam_selesai)-TIME_TO_SEC(sub.jam_selesai)), 0) as pulang_cepat,
		IF (sub.jam_mulai is not null and sub.jam_selesai is not null and sub.kode_ijin is null,SEC_TO_TIME(ABS(TIME_TO_SEC(TIMEDIFF(CONCAT(sub.tanggal,' ',sub.jam_mulai),CONCAT(IF(msf.jam_mulai > msf.jam_selesai,DATE_ADD(sub.tanggal,INTERVAL +1 DAY),sub.tanggal),' ',sub.jam_selesai))))),0) as lama_jam_kerja,
		IF (msf.jam_mulai is not null and msf.jam_selesai is not null and msf.jam_mulai !=  '00:00:00' and msf.jam_selesai !=  '00:00:00',SEC_TO_TIME(ABS(TIME_TO_SEC(TIMEDIFF(CONCAT(sub.tanggal,' ',msf.jam_mulai),CONCAT(IF(msf.jam_mulai > msf.jam_selesai,DATE_ADD(sub.tanggal,INTERVAL +1 DAY),sub.tanggal),' ',msf.jam_selesai))))),0) as lama_jadwal_kerja,
		");
		$this->db->from('data_presensi AS sub');
		$this->db->join('karyawan AS main', 'main.id_karyawan = sub.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = main.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left'); 
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = main.loker', 'left');
		$this->db->join('master_shift AS msf', 'msf.kode_master_shift = sub.kode_shift and msf.status = 1', 'left');
		$this->db->join('data_izin_cuti_karyawan AS dti', 'dti.status = 1 and dti.kode_izin_cuti = sub.kode_ijin', 'left'); 
		$this->db->join('master_izin AS mi', 'mi.status = 1 and mi.kode_master_izin = dti.jenis', 'left'); 
		$this->db->join('master_hari_libur AS lbr', 'lbr.kode_hari_libur = sub.kode_hari_libur', 'left');
		$this->db->join('data_presensi AS bef', 'bef.tanggal = DATE_ADD(sub.tanggal,INTERVAL -1 DAY) and bef.id_karyawan = sub.id_karyawan', 'left');
		if(!empty($mulai) && !empty($selesai)){
			$this->db->where("sub.tanggal BETWEEN '".$mulai."' AND '".$selesai."'");
		}
		if(!empty($id_kar)){
			$this->db->where('sub.id_karyawan',$id_kar);
		}
		$this->db->order_by('sub.tanggal','ASD');
		$query=$this->db->get()->result();
		return $query;
	}





































	public function getListJadwalKerjaWhere($where)
	{
		$this->db->select('jk.*,emp.nama as nama_karyawan, emp.nik as nik, emp.id_karyawan as id_karyawan, emp.id_finger as id_finger, jbt.nama as nama_jabatan, bgn.nama as nama_bagian, lkr.nama as nama_loker');
		$this->db->from('data_jadwal_kerja AS jk');
		$this->db->join('karyawan AS emp', 'emp.id_karyawan = jk.id_karyawan', 'left'); 
		$this->db->join('master_jabatan AS jbt', 'jbt.kode_jabatan = emp.jabatan', 'left'); 
		$this->db->join('master_level_jabatan AS lvl', 'lvl.kode_level_jabatan = jbt.kode_level', 'left'); 
		$this->db->join('master_bagian AS bgn', 'bgn.kode_bagian = jbt.kode_bagian', 'left');
		$this->db->join('master_loker AS lkr', 'lkr.kode_loker = emp.loker', 'left');
		$this->db->where('emp.status_emp = 1 ');
		$this->db->where($where);
		$query=$this->db->get()->row_array();
		return $query;
	}
	public function cekJadwalKerjaIdDateJKB($id, $date)
	{
		if(empty($id) || empty($date))
			return null;
		$month = (int)date('m',strtotime($date));
		$year = date('Y',strtotime($date));
		$day = (int)date('d',strtotime($date));
		$data = $this->getListJadwalKerjaWhere(['jk.id_karyawan'=>$id,'jk.bulan'=>$month,'jk.tahun'=>$year]);
		$days = 'tgl_'.$day;
		if(!empty($data[$days])){
			$shift = $this->model_master->getMasterShiftKode($data[$days]);
			if ($data[$days] == 'CUSTOM' || $data[$days] == 'CSTM' || $shift['nama'] == 'CUSTOM') {
				$col_s='start_'.$day;
				$col_e='end_'.$day;
				$shift['jam_mulai']=$data[$col_s];
				$shift['jam_selesai']=$data[$col_e];
			}
			$val = [
				'kode_shift' => $data[$days],
				'nama_shift' => $shift['nama'],
				'jam_mulai' => $shift['jam_mulai'],
				'jam_selesai' => $shift['jam_selesai'],
				'tanggal'=>$date,
				'id_karyawan'=>$id,
			];
		}else{
			$val = [
				'kode_shift' => null,
				'nama_shift' => null,
				'jam_mulai' => null,
				'jam_selesai' => null,
				'tanggal'=>$date,
				'id_karyawan'=>$id,
			];
		}
		return $val;
	}
	public function coreImportPresensi_coba($data)
	{
		$ret=[];
		if (empty($data)) 
			return $ret;
		if (isset($data['jadwal'])) {
			$ret['id_karyawan']=$data['id_karyawan'];
			$ret['tanggal']=$data['tanggal'];
			$tglx2=date('Y-m-d',strtotime($data['tanggal'].' -1 day'));
			// $cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($data['id_karyawan'],$tglx2);
			// if (!empty($cekjadwal['jam_mulai']) && !empty($cekjadwal['jam_selesai'])) {
			// 	if (strtotime($cekjadwal['jam_selesai']) < strtotime($cekjadwal['jam_mulai'])) {
			// 		$half=$this->formatter->difHalfTime($cekjadwal['jam_mulai'],$cekjadwal['jam_selesai']);
			// 		//toleransi jam masuk
			// 		$tol_s_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],4,'-');
			// 		$tol_e_mulai=$this->formatter->jam($cekjadwal['jam_mulai'],$half,'+');
			// 		//toleransi jam pulang
			// 		$tol_s_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],$half,'-');
			// 		$tol_e_selesai=$this->formatter->jam($cekjadwal['jam_selesai'],14,'+');
			// 		$time=$data['jam'];
			// 		if (strtotime($tol_s_selesai) > strtotime($tol_e_selesai)) {
			// 			if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
			// 				$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
			// 				if (!$cek) {
			// 					$ret['jam_selesai']=$time;
			// 					$ret['tanggal']=$tglx2;
			// 					$ret['kode_shift']=$cekjadwal['kode_shift'];
			// 				}
			// 			}
			// 		}else{
			// 			if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
			// 				$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
			// 				if (!$cek) {
			// 					$ret['jam_selesai']=$time;
			// 					$ret['tanggal']=$tglx2;
			// 					$ret['kode_shift']=$cekjadwal['kode_shift'];
			// 				}
			// 			}
			// 		}									
			// 	}											
			// }	
			if (!empty($data['jadwal']['jam_mulai']) && !empty($data['jadwal']['jam_selesai'])) {
				$half=$this->formatter->difHalfTime($data['jadwal']['jam_mulai'],$data['jadwal']['jam_selesai']);
				//toleransi jam masuk
				$tol_s_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],4,'-');
				$tol_e_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],$half,'+');
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],9,'+');
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
				// $xxx = [
				// 	'half'	        =>$half,
				// 	'tol_s_mulai'	=>$tol_s_mulai,
				// 	'tol_e_mulai'	=>$tol_e_mulai,
				// 	'tol_s_selesai'	=>strtotime($tol_s_selesai),
				// 	'tol_e_selesai'	=>strtotime($tol_e_selesai),
				// 	'time'	=>strtotime($time),
				// 	'tgl'	=>$tgl,
				// ];
				// print_r($xxx);
			}
		}		
		return $ret;
	}
//======================================================= SINKRON GANTI SHIFT ===================================================
	public function coreSyncPresensi($data)
	{
		$ret=[];
		if (empty($data)) 
			return $ret;
		if (isset($data['jadwal'])) {
			$ret['id_karyawan']=$data['id_karyawan'];
			$ret['tanggal']=$data['tanggal'];
			// $tglx2=date('Y-m-d',strtotime($data['tanggal'].' -1 day'));
			// $tglx2=date('Y-m-d',strtotime($data['tanggal'].' +1 day'));
			$tglx2=$data['tanggal'];
			// $cekjadwal = $this->model_presensi->cekJadwalKerjaIdDate($data['id_karyawan'],$tglx2);
			$shift = $this->model_master->getMasterShiftKode($data['kode_shift']);
			if (!empty($shift['jam_mulai']) && !empty($shift['jam_selesai'])) {
				if (strtotime($shift['jam_selesai']) < strtotime($shift['jam_mulai'])) {
					$half=$this->formatter->difHalfTime($shift['jam_mulai'],$shift['jam_selesai']);
					//toleransi jam masuk
					$tol_s_mulai=$this->formatter->jam($shift['jam_mulai'],4,'-');
					$tol_e_mulai=$this->formatter->jam($shift['jam_mulai'],$half,'+');
					//toleransi jam pulang
					$tol_s_selesai=$this->formatter->jam($shift['jam_selesai'],$half,'-');
					$tol_e_selesai=$this->formatter->jam($shift['jam_selesai'],8,'+');
					$time=$data['jam'];
					if (strtotime($tol_s_selesai) > strtotime($tol_e_selesai)) {
						if (strtotime($time) <= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$data['kode_shift'];
							}
						}
					}else{
						if (strtotime($time) >= strtotime($tol_s_selesai) && strtotime($time) <= strtotime($tol_e_selesai)) {
							$cek=$this->checkPresensiTime($data['id_karyawan'],$tglx2,$time,'jam_selesai');
							if (!$cek) {
								$ret['jam_selesai']=$time;
								$ret['tanggal']=$tglx2;
								$ret['kode_shift']=$data['kode_shift'];
							}
						}
					}									
				}											
			}
			if (!empty($data['jadwal']['jam_mulai']) && !empty($data['jadwal']['jam_selesai'])) {
				$half=$this->formatter->difHalfTime($data['jadwal']['jam_mulai'],$data['jadwal']['jam_selesai']);
				//toleransi jam masuk
				$tol_s_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],4,'-');
				$tol_e_mulai=$this->formatter->jam($data['jadwal']['jam_mulai'],$half,'+');
				//toleransi jam pulang
				$tol_s_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],$half,'-');
				$tol_e_selesai=$this->formatter->jam($data['jadwal']['jam_selesai'],8,'+');
				//compare
				$time=$data['jam'];
				$tgl=$data['jadwal']['tanggal'];
				if (strtotime($data['jadwal']['jam_selesai']) < strtotime($data['jadwal']['jam_mulai'])) {
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) >= strtotime($tol_e_mulai)) {
						// $cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						// if (!$cek) {
							$ret['jam_mulai']=$time;
						// }
					}
					if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
						// $cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						// if (!$cek) {
							$ret['jam_mulai']=$time;
						// }
					}
					if (isset($ret['jam_mulai'])) {
						if (empty($ret['jam_mulai'])) {
							if (strtotime($time) >= strtotime($tol_s_mulai) && strtotime($time) <= strtotime($tol_e_mulai)) {
								// $cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
								// if (!$cek) {
									$ret['jam_mulai']=$time;
								// }
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
						// $cek=$this->checkPresensiTime($data['id_karyawan'],$data['tanggal'],$time,'jam_mulai');
						// if (!$cek) {
							$ret['jam_mulai']=$time;
						// }						
					}
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
		}		
		return $ret;
	}
	public function syncPresensiGantiShift($d,$kode_shift)
	{
		$libur = $this->model_master->cekHariLiburDate($d['tanggal']);
		if (isset($libur)) {
			$data['kode_hari_libur']=(!empty($libur['kode_hari_libur'])?$libur['kode_hari_libur']:null);
		}
		$izin = $this->cekIzinCutiIdDate($d['id_karyawan'],$d['tanggal']);
		if (isset($izin)) {
			$data['kode_ijin']=(!empty($izin['kode_izin_cuti'])?$izin['kode_izin_cuti']:null);
		}
		$lembur = $this->cekLemburIdDate($d['id_karyawan'],$d['tanggal']);
		if (isset($lembur) && !empty($lembur)) {
			$data['no_spl']=$lembur['no_lembur'];
		}
		$shift = $this->model_master->getMasterShiftKode($kode_shift);
		$cekjadwal = [
			'jam_mulai'=>$shift['jam_mulai'],
			'jam_selesai'=>$shift['jam_selesai'],
			'tanggal'=>$d['tanggal'],
			'kode_shift'=>$kode_shift,
		];	
		// $cekjadwal = $this->cekJadwalKerjaIdDate($d['id_karyawan'],$d['tanggal']);
		$data['kode_shift'] = $cekjadwal['kode_shift'];
		$data_jam=$this->coreSyncPresensi(['tanggal'=>$d['tanggal'],'jam'=>$d['jam'],'jadwal'=>$cekjadwal,'id_karyawan'=>$d['id_karyawan'],'kode_shift'=>$kode_shift]);			
		if (isset($data_jam['tanggal'])) {
			$data['id_karyawan']=$d['id_karyawan'];
			$data['tanggal']=$data_jam['tanggal'];
			if (isset($data_jam['jam_mulai'])) {
				$data['jam_mulai']=$data_jam['jam_mulai'];
			}
			if (isset($data_jam['jam_selesai'])) {
				$data['jam_selesai']=$data_jam['jam_selesai'];
			}
			if (isset($data_jam['kode_shift'])) {
				$data['kode_shift']=$data_jam['kode_shift'];
			}
		}else{
			$data['id_karyawan']=$d['id_karyawan'];
			// $data['tanggal']=$current_date;
			$data['tanggal']=$d['tanggal'];
			if (isset($data_jam['jam_mulai'])) {
				$data['jam_mulai']=$data_jam['jam_mulai'];
			}
			if (isset($data_jam['jam_selesai'])) {
				$data['jam_selesai']=$data_jam['jam_selesai'];
			}
			if (isset($data_jam['kode_shift'])) {
				$data['kode_shift']=$data_jam['kode_shift'];
			}
		}
		$wh=['id_karyawan'=>$data['id_karyawan'],'tanggal'=>$data['tanggal']];
		if (isset($data['jam_mulai'])) {
			$wh['jam_mulai']=$data['jam_mulai'];
		}
		if (isset($data['jam_selesai'])) {
			$wh['jam_selesai']=$data['jam_selesai'];
		}
		// echo '<pre>';	
		// print_r($data);
		$cek=$this->checkPresensiWhere(['id_karyawan'=>$data['id_karyawan'],'tanggal'=>$data['tanggal']]);
		if (!$cek) {
			$data_in=array_merge($data,$this->model_global->getCreateProperties(1));
			$this->model_global->insertQueryNoMsg($data_in,'data_presensi'); 
			$datax = true;
		}else{
			// if (isset($wh['jam_mulai']) || isset($wh['jam_selesai'])) {
				$data_in=array_merge($data,$this->model_global->getUpdateProperties(1));
				$this->model_global->updateQueryNoMsg($data_in,'data_presensi',['tanggal'=>$data['tanggal'],'id_karyawan'=>$data['id_karyawan']]);
				$datax = true;
			// }else{
			// 	$datax = true;	
			// }
		}
		return $datax;
	}
	public function getPresensiWhere2($where)
	{
		if (empty($where))
			return false;
		$this->db->select('*');
		$this->db->from('data_presensi');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogPresensiWhere($where)
	{
		if (empty($where))
			return false;
		$this->db->select('*');
		$this->db->from('log_data_presensi');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getTemporariAbsenWhere($where)
	{
		if (empty($where))
			return false;
		$this->db->select('*');
		$this->db->from('temporari_data_presensi');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getLogTemporariAbsenWhere($where)
	{
		if (empty($where))
			return false;
		$this->db->select('*');
		$this->db->from('log_temporari_data_presensi');
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
	public function getDataTableWhere($table, $where)
	{
		if (empty($where))
			return false;
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$query=$this->db->get()->result();
		return $query;
	}
}
