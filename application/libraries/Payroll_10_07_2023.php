<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * Code From GFEACORP.
     * Web Developer
     * @author      Putra Setya Budi
     * @package     Payroll
     * @copyright   Copyright (c) 2019 GFEACORP
     * @version     1.0, 25 April 2019
     * Email        putrasetya.b@gmail.com
     * Phone        (+62) 899-353-4765
     */

class Payroll {
    
    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function index()
    {
        $this->redirect('not_found');
    }

   	public function getFormatMoneyUser($val)
   	{
    	$data = $this->CI->formatter->getFormatMoneyUser($val);
		if(empty($val)){
			$data = 'Rp. 0,00';
		}
    	return $data;
   	}
	public function getKaryawanFromPeriode($kode_periode, $dtroot)
	{
		$periode = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
		// print_r($periode);	
		$periode_detail = $this->CI->model_master->getListPeriodePenggajianDetail($kode_periode);
		if($dtroot['adm']['level'] != 0){
			$en_access_idKar = $this->CI->payroll->getKaryawanFromPetugasPayroll($dtroot['adm']['id_karyawan']);
			$en_access_jabatan = $this->CI->payroll->getJabatanFromPetugasPayroll($dtroot['adm']['id_karyawan']);
		}
		$karyawan = [];
		foreach ($periode_detail as $pd) {
			$pos = strpos($pd->id_bagian, ';');
			if ($pos == true) {
				$id_bagian = $this->CI->otherfunctions->getDataExplode($pd->id_bagian,';','all');
				foreach ($id_bagian as $k_bag => $val_bag) {
					$emp = $this->CI->model_payroll->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'bag.id_bagian'=>$val_bag,'emp.kode_penggajian'=>$periode['kode_master_penggajian']],1);
					foreach ($emp as $e) {
						$tgl_masuk = date('Y-m-d',strtotime($e->tgl_masuk));
						$tgl_mulai = date('Y-m-d',strtotime($periode['tgl_mulai']));
						if($dtroot['adm']['level'] == 0){
							// if($tgl_masuk <= $tgl_mulai){
								$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],1,true);
								$karyawan[$e->id_karyawan] = $this->cekAgendaPayroll($kary);
							// }
						}else{
							// if(in_array($e->jabatan,$en_access_jabatan)){
							// 	$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],1,true);
							// 	$karyawan[$e->id_karyawan] = $this->cekAgendaPayroll($kary);
							// }
							if(in_array($e->id_karyawan,$en_access_idKar)){
								// if($tgl_masuk <= $tgl_mulai){
									$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],1,true);
									$karyawan[$e->id_karyawan] = $this->cekAgendaPayroll($kary);
								// }
							}
						}
					}
				}
			}else{
				$id_bagian = $pd->id_bagian;
				$empx = $this->CI->model_payroll->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'bag.id_bagian'=>$id_bagian,'emp.kode_penggajian'=>$periode['kode_master_penggajian']],1);
				foreach ($empx as $e) {
					$tgl_masuk = date('Y-m-d',strtotime($e->tgl_masuk));
					$tgl_mulai = date('Y-m-d',strtotime($periode['tgl_mulai']));
					if($dtroot['adm']['level'] == 0){
						// if($tgl_masuk <= $tgl_mulai){
							$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],1,true);
							$karyawan[$e->id_karyawan] = $this->cekAgendaPayroll($kary);
						// }
					}else{
						// if(in_array($e->jabatan,$en_access_jabatan)){
						// 	$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],1,true);
						// 	$karyawan[$e->id_karyawan] = $this->cekAgendaPayroll($kary);
						// }
						if(in_array($e->id_karyawan,$en_access_idKar)){
							// if($tgl_masuk <= $tgl_mulai){
								$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],1,true);
								$karyawan[$e->id_karyawan] = $this->cekAgendaPayroll($kary);
							// }
						}
					}
				}
			}
		}
		return $karyawan;
	}
 	public function cekAgendaPayroll($emp)
 	{
		if(empty($emp))
			return null;
		$masa_kerja = $this->CI->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
		$uang_makan_grade=0;
		if($emp['gaji_pokok'] == 'matrix'){
			$gaji_pokok = (empty($emp['gaji_grade'])) ? 0 : $emp['gaji_grade'];
			$uang_makan_grade=(($emp['uang_makan_grade'])?$emp['uang_makan_grade']:0);
		}else{
			$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
		}
		$data_karyawan = [
			'id_karyawan'=>$emp['id_karyawan'],
			// 'kode_umk'=>$p->kode_umk,
			'nik'=>$emp['nik'],
			'nama'=>$emp['nama'],
			'kode_jabatan'=>$emp['jabatan'],
			'kode_grade'=>$emp['grade'],
			'kode_bagian'=>$emp['kode_bagian'],
			'rekening'=>$emp['rekening'],
			'npwp'=>$emp['npwp'],
			// 'nama_bagian_emp'=>$emp['bagian'],
			// 'nama_bagian_periode'=>$get_bagian['nama'],
			'kode_loker'=>$emp['loker'],
			// 'kode_loker_periode'=>$p->kode_loker,
			'tgl_masuk'=>$emp['tgl_masuk'],
			'masa_kerja'=>$masa_kerja,
			'gaji_pokok'=>$gaji_pokok,
			'uang_makan_grade'=>$uang_makan_grade,
			'gaji_bpjs_kes'=>$emp['gaji_bpjs'],
			'gaji_bpjs_tk'=>$emp['gaji_bpjs_tk'],
			'status_pajak'=>$emp['status_pajak'],
			'no_ktp'=>$emp['no_ktp'],
			'wfh'=>$emp['wfh'],
			'hari_kerja_wfh'=>$emp['hari_kerja_wfh'],
			'hari_kerja_non_wfh'=>$emp['hari_kerja_non_wfh'],
		];
 		return $data_karyawan;
 	}
 	public function cekAgendaPenggajian($emp,$kode_periode)
 	{
 		$periode = $this->CI->model_master->getListPeriodePenggajianDetail($kode_periode);
 		$data_karyawan = [];
 		foreach ($periode as $p) {
 			$id_bagian = explode(";",$p->id_bagian);
 			$periode_master = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getPeriodePenggajian(['kode_periode_penggajian'=>$p->kode_periode_penggajian]));
 			foreach ($id_bagian as $key => $value) {
 				$get_bagian =  $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getBagian($value));
 				if($emp['loker']==$p->kode_loker && $emp['bagian'] == $get_bagian['nama'] && $emp['kode_penggajian'] == $periode_master['kode_master_penggajian']){
					$masa_kerja = $this->CI->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
					$uang_makan_grade=0;
					if($emp['gaji_pokok'] == 'matrix'){
						$gaji_pokok = (empty($emp['gaji_pokok_grade'])) ? 0 : $emp['gaji_pokok_grade'];
						$uang_makan_grade=(($emp['uang_makan_grade'])?$emp['uang_makan_grade']:0);
					}else{
						$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
					}
 					$data_karyawan = [
 						'id_karyawan'=>$emp['id_karyawan'],
 						'kode_umk'=>$p->kode_umk,
 						'nik'=>$emp['nik'],
 						'nama'=>$emp['nama'],
 						'kode_jabatan'=>$emp['jabatan'],
 						'kode_grade'=>$emp['grade'],
 						'kode_bagian'=>$get_bagian['kode_bagian'],
 						'rekening'=>$emp['rekening'],
						 'npwp'=>$emp['npwp'],
 						// 'nama_bagian_emp'=>$emp['bagian'],
 						// 'nama_bagian_periode'=>$get_bagian['nama'],
 						'kode_loker'=>$emp['loker'],
 						// 'kode_loker_periode'=>$p->kode_loker,
 						'tgl_masuk'=>$emp['tgl_masuk'],
 						'masa_kerja'=>$masa_kerja,
 						'gaji_pokok'=>$gaji_pokok,
 						'uang_makan_grade'=>$uang_makan_grade,
						 'gaji_bpjs_kes'=>$emp['gaji_bpjs'],
						 'gaji_bpjs_tk'=>$emp['gaji_bpjs_tk'],
 						'status_pajak'=>$emp['status_pajak'],
 						'wfh'=>$emp['wfh'],
 						'hari_kerja_wfh'=>$emp['hari_kerja_wfh'],
 						'hari_kerja_non_wfh'=>$emp['hari_kerja_non_wfh'],
 					];
 				}
 			}
 		}
 		return $data_karyawan;
 	}
 	public function cekAgendaPenggajianHarian($emp,$kode_periode)
 	{
 		$periode = $this->CI->model_master->getListPeriodePenggajianHarianDetail($kode_periode);
 		$data_karyawan = [];
 		foreach ($periode as $p) {
 			$id_bagian = explode(";",$p->id_bagian);
 			$periode_master = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getPeriodePenggajianHarian(['kode_periode_penggajian_harian'=>$p->kode_periode_penggajian_harian]));
 			foreach ($id_bagian as $key => $value) {
 				$get_bagian =  $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getBagian($value));
 				if($emp['loker']==$p->kode_loker && $emp['bagian'] == $get_bagian['nama'] && $emp['kode_penggajian'] == $periode_master['kode_master_penggajian']){
					$masa_kerja = $this->CI->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
					if($emp['gaji_pokok'] == 'matrix'){
						$gaji_pokok = (empty($emp['gaji_pokok_grade'])) ? 0 : $emp['gaji_pokok_grade'];
					}else{
						$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
					}
 					$data_karyawan = [
 						'id_karyawan'=>$emp['id_karyawan'],
 						'kode_umk'=>$p->kode_umk,
 						'nik'=>$emp['nik'],
 						'nama'=>$emp['nama'],
 						'kode_jabatan'=>$emp['jabatan'],
 						'kode_grade'=>$emp['grade'],
 						'kode_bagian'=>$get_bagian['kode_bagian'],
 						'rekening'=>$emp['rekening'],
 						// 'nama_bagian_emp'=>$emp['bagian'],
 						// 'nama_bagian_periode'=>$get_bagian['nama'],
 						'kode_loker'=>$emp['loker'],
 						// 'kode_loker_periode'=>$p->kode_loker,
 						'tgl_masuk'=>$emp['tgl_masuk'],
 						'masa_kerja'=>$masa_kerja,
 						'gaji_pokok'=>$gaji_pokok,
 						'status_pajak'=>$emp['status_pajak'],
 					];
 				}
 			}
 		}
 		return $data_karyawan;
 	}
	//------------------ Agenda Penggajian Lembur --------------------
	
	public function getKaryawanFromPeriodeLembur($kode_periode, $dtroot)
	{
		$periode = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode_periode]));
		$periode_detail = $this->CI->model_master->getListPeriodeLemburDetail($kode_periode);
		// if($dtroot['adm']['level'] != 0){
		if($dtroot['adm']['jenis_admin'] == 'karyawan'){
			$en_access_idKar = $this->getKaryawanFromPetugasLembur($dtroot['adm']['id_karyawan']);
			$en_access_jabatan = $this->getJabatanFromPetugasLembur($dtroot['adm']['id_karyawan']);
		}
		$karyawan = [];
		foreach ($periode_detail as $pd) {
			// if (strpos($pd->id_bagian, ';') == true) {
			if (strpos($pd->id_bagian, ';') !== true) {
				$id_bagian = $this->CI->otherfunctions->getDataExplode($pd->id_bagian,';','all');
				foreach ($id_bagian as $k_bag => $val_bag) {
					$emp = $this->CI->model_payroll->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'bag.id_bagian'=>$val_bag,'emp.kode_penggajian'=>$periode['kode_master_penggajian']],'2');
					foreach ($emp as $e) {
						if($dtroot['adm']['level'] == 0 && $dtroot['adm']['jenis_admin'] == 'superadmin'){
							$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],'2',true);
							$karyawan[$e->id_karyawan] = $this->cekAgendaPayrollLembur($kary);
						}else{
							if(in_array($e->id_karyawan,$en_access_idKar)){
								$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],'2',true);
								$karyawan[$e->id_karyawan] = $this->cekAgendaPayrollLembur($kary);
							}
						}
					}
				}
			}else{
				$id_bagian = $pd->id_bagian;
				$empx = $this->CI->model_payroll->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'bag.id_bagian'=>$id_bagian,'emp.kode_penggajian'=>$periode['kode_master_penggajian']],'2');
				foreach ($empx as $e) {
					if($dtroot['adm']['level'] == 0 && $dtroot['adm']['jenis_admin'] == 'superadmin'){
						$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],'2',true);
						$karyawan[$e->id_karyawan] = $this->cekAgendaPayrollLembur($kary);
					}else{
						if(in_array($e->id_karyawan,$en_access_idKar)){
							$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],'2',true);
							$karyawan[$e->id_karyawan] = $this->cekAgendaPayrollLembur($kary);
						}
					}
				}
			}
		}
		return $karyawan;
	}
 	public function cekAgendaPayrollLembur($emp)
 	{
		if(empty($emp))
			return null;
		$masa_kerja = $this->CI->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
		$uang_makan_grade=0;
		if($emp['gaji_pokok'] == 'matrix'){
			$gaji_pokok = (empty($emp['gaji_grade'])) ? 0 : $emp['gaji_grade'];
			$uang_makan_grade=(($emp['uang_makan_grade'])?$emp['uang_makan_grade']:0);
		}else{
			$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
		}
		$data_karyawan = [
			'id_karyawan'=>$emp['id_karyawan'],
			// 'kode_umk'=>$p->kode_umk,
			'nik'=>$emp['nik'],
			'nama'=>$emp['nama'],
			'kode_jabatan'=>$emp['jabatan'],
			'kode_grade'=>$emp['grade'],
			'kode_bagian'=>$emp['kode_bagian'],
			'rekening'=>$emp['rekening'],
			'npwp'=>$emp['npwp'],
			// 'nama_bagian_emp'=>$emp['bagian'],
			// 'nama_bagian_periode'=>$get_bagian['nama'],
			'kode_loker'=>$emp['loker'],
			// 'kode_loker_periode'=>$p->kode_loker,
			'tgl_masuk'=>$emp['tgl_masuk'],
			'masa_kerja'=>$masa_kerja,
			'gaji_pokok'=>$gaji_pokok,
			'uang_makan_grade'=>$uang_makan_grade,
			'gaji_bpjs_kes'=>$emp['gaji_bpjs'],
			'gaji_bpjs_tk'=>$emp['gaji_bpjs_tk'],
			'status_pajak'=>$emp['status_pajak'],
			'no_ktp'=>$emp['no_ktp'],
			'wfh'=>$emp['wfh'],
			'hari_kerja_wfh'=>$emp['hari_kerja_wfh'],
			'hari_kerja_non_wfh'=>$emp['hari_kerja_non_wfh'],
		];
 		return $data_karyawan;
 	}
	//-------------------------- end penggajian lembur ---------------------
 	public function getInsentif($id)
 	{
 		$data_insentif = $this->CI->model_master->getListInsentif('active');
 		$new_val = [];
 		foreach ($data_insentif as $d) {
 			$exIdKaryawan = explode(";", $d->id_karyawan);
 			foreach ($exIdKaryawan as $key => $value) {
 				if($id == $value){
 					$new_val[] = $d->nominal;
 				}
 			}
 		}
 		return array_sum($new_val);
 	}
 	public function getInsentifVal($id)
 	{
 		$data_insentif = $this->CI->model_master->getListInsentif('active');
 		$new_val = [];
 		foreach ($data_insentif as $d) {
 			$exIdKaryawan = explode(";", $d->id_karyawan);
 			foreach ($exIdKaryawan as $key => $value) {
 				if($id == $value){
 					$new_val[] = $d->nama.':'.$d->nominal;
 				}
 			}
 		}
 		return implode(";", $new_val);
 	}
 	public function getRitasi($id,$kode_periode)
 	{
 		$data_ritasi = $this->CI->model_master->getListDataRitasi(['a.kode_periode_penggajian'=>$kode_periode, 'a.id_karyawan'=>$id, 'a.validasi'=>'1'],'active');
		//  echo '<pre>';
		//  print_r($data_ritasi);
 		$nominal = [];
 		$jumlah = [];
 		foreach ($data_ritasi as $d) {
 			$nominal[] = $d->nominal + $d->nominal_non_ppn;
 			$jumlah[] = $d->rit + $d->rit_non_ppn;
 		}
		$data = [
			'nominal'=>array_sum($nominal),
			'jumlah'=>array_sum($jumlah),
		];
 		return $data;
 	}
 	public function getTUnjanganTetap($id)
 	{
 		$emp = $this->CI->model_karyawan->getEmployeeId($id);
 		$data_tunjangan =  $this->CI->model_master->getListTunjangan(null, '1');
 		$tunjangan = [];
 		foreach ($data_tunjangan as $d) {
 			$dat_induk = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListIndukTunjanganKode($d->kode_induk_tunjangan));
			$exidemp = explode(";",$d->karyawan);
			if($dat_induk['sifat'] == '1'){
				if(in_array($emp['id_karyawan'],$exidemp)){
					$tunjangan[$d->kode_induk_tunjangan] = $d->nominal;
				}
			}
 		}
 		return $tunjangan;
 	}
 	public function getTUnjanganNonTetapPayroll($id,$kode_periode_penggajian)
 	{
 		if(empty($id) || empty($kode_periode_penggajian))
 			return $val = null;
		$val = $this->CI->model_master->getListDataTunjangan(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian],'active',true);
		$tunjangan = [];
		 if(!empty($val['tunjangan'])){
			$valTunjangan = $this->CI->otherfunctions->getDataExplode($val['tunjangan'],';','all');
			foreach ($valTunjangan as $key => $kode_induk) {
				$dat_induk = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListIndukTunjanganKode($kode_induk));
				if($dat_induk['sifat'] == '0'){
					$data_tunjangan =  $this->CI->model_master->getListTunjangan(['a.kode_induk_tunjangan'=>$dat_induk['kode_induk_tunjangan']]);
					if(!empty($data_tunjangan)){
						foreach ($data_tunjangan as $d) {
							if(!empty($d->karyawan)){
								$exidemp = explode(";",$d->karyawan);
								if(in_array($id,$exidemp)){
									$tunjangan[$d->kode_induk_tunjangan] = $d->nominal;
								}
							}
						}
					}
				}
			}
		 }
 		return $tunjangan;
 	}
 	public function getTUnjangan($id)
 	{
 		$emp = $this->CI->model_karyawan->getEmployeeId($id);
 		$data_tunjangan =  $this->CI->model_master->getListTunjangan();
 		$tunjangan = [];
 		foreach ($data_tunjangan as $d) {
 			$dat_induk = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListIndukTunjanganKode($d->kode_induk_tunjangan));
			$exidemp = explode(";",$d->karyawan);
			if($dat_induk['sifat'] == '1'){
				if(in_array($emp['id_karyawan'],$exidemp)){
					$tunjangan[$d->kode_tunjangan] = [
						'nama'=>$d->nama,
						'kode_induk'=>$d->kode_induk_tunjangan,
						'kode'=>$d->kode_tunjangan,
						'nominal'=>$d->nominal,
						'tahun'=>$d->tahun,
						'sifat'=>$dat_induk['sifat'],
						'with_payroll'=>$dat_induk['with_payroll'],
						'pph'=>$dat_induk['pph'],
						'upah'=>$dat_induk['upah'],
						'nominal'=>$d->nominal,
					];
				}
			}
			// if($d->kode_induk_grade == ''){
 			// 	$exidemp = explode(";",$d->kode_jabatan);
 			// 	if(in_array($emp['id_jabatan'],$exidemp)){
 			// 		$tunjangan[$d->kode_tunjangan] = [
 			// 			'nama'=>$d->nama,
 			// 			'kode_induk'=>$d->kode_induk_tunjangan,
 			// 			'kode'=>$d->kode_tunjangan,
 			// 			'nominal'=>$d->nominal,
 			// 			'tahun'=>$d->tahun,
 			// 			'sifat'=>$dat_induk['sifat'],
 			// 			'with_payroll'=>$dat_induk['with_payroll'],
 			// 			'pph'=>$dat_induk['pph'],
 			// 			'upah'=>$dat_induk['upah'],
	 		// 			'nominal'=>$d->nominal,
 			// 		];
 			// 	}
 			// }else{
 			// 	$exidemp = explode(";",$d->kode_jabatan);
 			// 	$exidgrd = explode(";",$d->kode_grade);
 			// 	if(in_array($emp['id_jabatan'],$exidemp) && in_array($emp['id_grade'],$exidgrd)){
 			// 		$tunjangan[$d->kode_tunjangan] = [
 			// 			'nama'=>$d->nama,
 			// 			'kode_induk'=>$d->kode_induk_tunjangan,
 			// 			'kode'=>$d->kode_tunjangan,
 			// 			'nominal'=>$d->nominal,
 			// 			'tahun'=>$d->tahun,
 			// 			'sifat'=>$dat_induk['sifat'],
 			// 			'with_payroll'=>$dat_induk['with_payroll'],
 			// 			'pph'=>$dat_induk['pph'],
 			// 			'upah'=>$dat_induk['upah'],
	 		// 			'nominal'=>$d->nominal,
 			// 		];
 			// 	}
 			// }
 		}
 		return $tunjangan;
 	}
 	public function getTUnjanganNonTetap($id,$kode_periode_penggajian)
 	{
 		if(empty($id) || empty($kode_periode_penggajian))
 			return $val['tunjangan'] = null;
 		$val = $this->CI->model_master->getListDataTunjangan(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian],'active',true);
 		return $val['tunjangan'];
 	}

 	public function getTunjanganVal($getTUnjangan)
 	{
 		$tunjangan = [];
 		if(isset($getTUnjangan)){
 			foreach ($getTUnjangan as $key => $value) {
 				$tunjangan[] = $value['kode'].':'.$value['nominal'];
 			}
 		}
 		return implode(";", $tunjangan);
 	}
 	public function getTunjanganNominal($getTUnjangan)
 	{
 		$tunjangan_nominal = 0;
 		if(isset($getTUnjangan)){
 			foreach ($getTUnjangan as $key => $value) {
 				$tunjangan_nominal += $value['nominal'];
 			}
 		}
 		return $tunjangan_nominal;
 	}

 	public function getTunjanganNominalPayroll($getTUnjangan)
 	{
 		$tunjangan_nominal = 0;
 		if(isset($getTUnjangan)){
 			foreach ($getTUnjangan as $key => $value) {
 				if($value['sifat'] == 1){
 					$tunjangan_nominal += $value['nominal'];
 				}else{
 					if($value['upah'] == 1){
 						$tunjangan_nominal += $value['nominal'];
 					}
 				}
 			}
 		}
 		return $tunjangan_nominal;
 	}

 	public function getTunjanganNominalPayrollAll($getTUnjangan)
 	{
 		$tunjangan_nominal = 0;
 		if(isset($getTUnjangan)){
 			foreach ($getTUnjangan as $key => $value) {
				$tunjangan_nominal += $value['nominal'];
 			}
 		}
 		return $tunjangan_nominal;
 	}
 	public function getTUnjanganAll($id,$tunjangan,$tunjanganTidakTetap)
 	{
		$kode_induk = [];
		foreach ($tunjangan as $tu => $vtu) {
			$kode_induk[] = $vtu['kode_induk'];
		}
		if(!empty($tunjanganTidakTetap)){
			$ttt = $this->CI->otherfunctions->getDataExplode($tunjanganTidakTetap,';','all');
			$sss = array_unique(array_merge($kode_induk,$ttt));
		}else{
			$sss = $kode_induk;
		}
 		$emp = $this->CI->model_karyawan->getEmployeeId($id);
 		$tunjanganxx = [];
 		foreach ($sss as $dd =>$kode) {
			$dat_induk = $this->CI->model_master->getListTunjangan(['a.kode_induk_tunjangan'=>$kode],1);
			foreach ($dat_induk as $d) {
				$exidemp = explode(";",$d->karyawan);
				if(in_array($emp['id_karyawan'],$exidemp)){
					$tunjanganxx[$d->kode_tunjangan] = [
						'nama'=>$d->nama,
						'kode_induk'=>$d->kode_induk_tunjangan,
						'kode'=>$d->kode_tunjangan,
						'nominal'=>$d->nominal,
						'tahun'=>$d->tahun,
						'nominal'=>$d->nominal,
					];
				}
			}
		}
 		return $tunjanganxx;
 	}

 	public function getTunjanganNominalTetap($getTUnjangan)
 	{
 		$tunjangan_nominal = 0;
 		if(isset($getTUnjangan)){
 			foreach ($getTUnjangan as $key => $value) {
 				if($value['sifat'] == 1){
 					$tunjangan_nominal += $value['nominal'];
 				}
 			}
 		}
 		return $tunjangan_nominal;
 	}

 	public function getTunjanganNominalTidakTetap($getTUnjangan)
 	{
 		$tunjangan_nominal = 0;
 		if(isset($getTUnjangan)){
 			foreach ($getTUnjangan as $key => $value) {
 				if($value['sifat'] == 0){
 					$tunjangan_nominal += $value['nominal'];
 				}
 			}
 		}
 		return $tunjangan_nominal;
 	}

 	public function getTunjanganNominalPPH($getTUnjangan)
 	{
 		$tunjangan_nominal = 0;
 		if(!empty($getTUnjangan)){
 			foreach ($getTUnjangan as $key => $value) {
 				if($value['pph'] == 1){
 					$tunjangan_nominal += $value['nominal'];
 				}
 			}
 		}
 		return $tunjangan_nominal;
	}
	public function update_tunjangan($karyawan,$kode_periode,$id_admin,$sistem_penggajian = 'BULANAN')
 	{
 		if(!empty($karyawan)){
			foreach (array_values(array_filter($karyawan)) as $key => $value_emp) {
				$where_tunjangan = [
					'id_karyawan'=>$value_emp['id_karyawan'],
					'kode_periode_penggajian'=>$kode_periode
				];
				$del_tunjangan = $this->CI->model_global->deleteQuery('data_penggajian_tunjangan',$where_tunjangan);
				if($sistem_penggajian == 'BULANAN'){
					$periode = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
				}elseif($sistem_penggajian == 'HARIAN'){
					$periode = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$kode_periode]));
				}
				if(!empty($periode)){
					$per_nama = $periode['nama'];
					$per_tgl_mulai = $periode['tgl_mulai'];
					$per_tgl_selesai = $periode['tgl_selesai'];
					$per_kode_master_penggajian = $periode['kode_master_penggajian'];
				}else{
					$per_nama = null;
					$per_tgl_mulai = null;
					$per_tgl_selesai = null;
					$per_kode_master_penggajian = null;
				}
				if($del_tunjangan['status_data']){
					$tunjangan=$this->getTUnjangan($value_emp['id_karyawan']);
					foreach ($tunjangan as $key => $value) {
						$data = [
							'id_karyawan'=>$value_emp['id_karyawan'],
							'nik'=>$value_emp['nik'],
							'nama_karyawan'=>$value_emp['nama'],
							'kode_jabatan'=>$value_emp['kode_jabatan'],
							'kode_bagian'=>$value_emp['kode_bagian'],
							'kode_loker'=>$value_emp['kode_loker'],
							'kode_grade'=>$value_emp['kode_grade'],
							'tgl_masuk'=>$value_emp['tgl_masuk'],
							'masa_kerja'=>$value_emp['masa_kerja'],
							'kode_periode_penggajian'=>$kode_periode,
							'nama_periode'=>$per_nama,
							'tgl_mulai'=>$per_tgl_mulai,
							'tgl_selesai'=>$per_tgl_selesai,
							'kode_master_penggajian'=>$per_kode_master_penggajian,
							'tahun_pay'=>date("Y",strtotime($per_tgl_selesai)),
							'nama_tunjangan'=>$value['nama'],
							'kode_tunjangan'=>$key,
							'kode_induk_tunjangan'=>$value['kode_induk'],
							'tahun_tunjangan'=>$value['tahun'],
							'sifat'=>$value['sifat'],
							'with_payroll'=>$value['with_payroll'],
							'pph'=>$value['pph'],
							'nominal'=>$value['nominal'],
							'upah'=>$value['upah'],
						];
						$data=array_merge($data,$this->CI->model_global->getCreateProperties($id_admin));
						$this->CI->model_global->insertQuery($data,'data_penggajian_tunjangan');
					}
				}
			}
 		}
 	}
 	public function getTableTunjangan($id,$kode_periode_penggajian)
 	{
 		$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
 						<thead>
 						<tr>
 							<th>Nama Tunjangan</th>
 							<th>Nominal</th>
 						</tr>
 						</thead>';
 		if(empty($id) || empty($kode_periode_penggajian)){
 			$table .= '<tbody><tr><td colspan="2"><center>Tidak Ada Data</center></td></th></tbody>';
 		}else{
 			$table .= '<tbody>';
 			$data = $this->CI->model_payroll->getDataPayrollTunjangan(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian]);
 			foreach ($data as $d) {
 				$table .= '<tr>
 									<td>'.$d->nama_tunjangan.'</td>
 									<td>'.$this->CI->formatter->getFormatMoneyUser($d->nominal).'</td>
 								</tr>';
 			}
 			$table .= '</tbody>';
 		}
 		$table .= '</table>';
 		return $table;
 	}

 	public function getTablePenambah($id,$kode_periode_penggajian,$id_pay,$usage = 'data',$sistem_penggajian = 'BULANAN',$tunjangan = null)
 	{
		if($sistem_penggajian == 'BULANAN'){
			if($usage == 'data'){
				$pay_emp = $this->CI->model_payroll->getDataPayroll(['a.id_pay'=>$id_pay]);
			}else{
				$pay_emp = $this->CI->model_payroll->getDataLogPayroll(['a.id_pay'=>$id_pay]);
			}
		}elseif($sistem_penggajian == 'HARIAN'){
			if($usage == 'data'){
				$pay_emp = $this->CI->model_payroll->getDataPayrollHarian(['a.id_pay'=>$id_pay]);
			}else{
				$pay_emp = $this->CI->model_payroll->getDataLogPayrollHarian(['a.id_pay'=>$id_pay]);
			}
		}
		if(!empty($pay_emp)){
			$pay_emp = $this->CI->otherfunctions->convertResultToRowArray($pay_emp);
			$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
			<tr>
			<th style="text-align:center;" colspan="2">Faktor Penambah</th>
			</tr>
			<tr>
			<th style="text-align:center;" >Nama</th>
			<th style="text-align:center;" >Nominal</th>
			</tr>
			</thead>';
			$table .= '<tbody>';
			$table .= '<tr><td>Insentif</td><td>'.$this->getFormatMoneyUser($pay_emp['insentif']).'</td></tr>';
			$table .= '<tr><td>Ritasi</td><td>'.$this->getFormatMoneyUser($pay_emp['ritasi']).'</td></tr>';
			$table.= '<tr><td>Uang Makan</td><td>'.$this->getFormatMoneyUser($pay_emp['uang_makan']).'</td></tr>';
			if(!empty($pay_emp['data_lain'])){				
				$val_data=$this->CI->otherfunctions->getDataExplode($pay_emp['data_lain'],';','all');
				$nominal_data=$this->CI->otherfunctions->getDataExplode($pay_emp['nominal_lain'],';','all');
				$ket_lain=$this->CI->otherfunctions->getDataExplode($pay_emp['data_lain_nama'],';','all');
				foreach ($val_data as $kData => $vData) {
					if($vData=='penambah'){
						$table.= '<tr><td>'.$ket_lain[$kData].'</td><td>'.$this->getFormatMoneyUser($nominal_data[$kData]).'</td></tr>';
					}
				}
			}
			if(!empty($tunjangan)){
				$tunj = $this->CI->otherfunctions->getDataExplode($tunjangan,';','all');
				$data_tunjangan = [];
				foreach ($tunj as $ktu => $vtu) {
					$kode_tunjangan = $this->CI->otherfunctions->getDataExplode($vtu,':','start');
					$nominal_tunjangan = $this->CI->otherfunctions->getDataExplode($vtu,':','end');
					$induk=$this->CI->model_master->getListTunjangan(['a.kode_tunjangan'=>$kode_tunjangan],1,true);
					$nominal_t = $this->CI->formatter->getFormatMoneyUser($nominal_tunjangan);
					$data_tunjangan[] = [
						'nama'=>$induk['nama_induk_tunjangan'],
						'nominal'=>$nominal_t,
						'sifat'=>$induk['sifat'],
					];
				}
				foreach ($data_tunjangan as $key_t => $value_t) {
					if($value_t['sifat'] == 1){
						$table .= '<tr>
						<td>'.$value_t['nama'].' <b>(TETAP)</b></td>
						<td style="white-space: nowrap;">'.$value_t['nominal'].'</td>
						</tr>';
					}else{
						$table .= '<tr>
						<td>'.$value_t['nama'].' <b>(TIDAK TETAP)</b></td>
						<td style="white-space: nowrap;">'.$value_t['nominal'].'</td>
						</tr>';
					}
				}
			}
			// if($usage == 'data'){
			// 	$data = $this->CI->model_payroll->getDataPayrollTunjangan(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian],$sistem_penggajian);
			// }else{
			// 	$data = $this->CI->model_payroll->getDataLogPayrollTunjangan(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian],$sistem_penggajian);
			// }
			// foreach ($data as $d) {
			// 	if($d->sifat == 1){
			// 		$table .= '<tr>
			// 		<td>'.$d->nama_tunjangan.' <b>(TETAP)</b></td>
			// 		<td style="white-space: nowrap;">'.$this->CI->formatter->getFormatMoneyUser($d->nominal).'</td>
			// 		</tr>';
			// 	}else{
			// 		// if($d->upah == 1){
			// 			$table .= '<tr>
			// 			<td>'.$d->nama_tunjangan.' <b>(TIDAK TETAP)</b></td>
			// 			<td style="white-space: nowrap;">'.$this->CI->formatter->getFormatMoneyUser($d->nominal).'</td>
			// 			</tr>';
			// 		// }
			// 	}
			// }
			$table .= '</tbody>';
			$table .= '</table>';
		}else{
			$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
							<thead>
								<tr>
								<th style="text-align:center;" colspan="2">Faktor Penambah</th>
									</tr>
								<tr>
									<th style="text-align:center;" >Nama</th>
									<th style="text-align:center;" >Nominal</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>';
		}
 		return $table;
 	}
 	public function getTablePengurang($id_pay,$usage = 'data',$sistem_penggajian = 'BULANAN')
 	{
		if($sistem_penggajian == 'BULANAN'){
			if($usage == 'data'){
				$pay_emp = $this->CI->model_payroll->getDataPayroll(['a.id_pay'=>$id_pay]);
			}else{
				$pay_emp = $this->CI->model_payroll->getDataLogPayroll(['a.id_pay'=>$id_pay]);
			}
		}elseif($sistem_penggajian == 'HARIAN'){
			if($usage == 'data'){
				$pay_emp = $this->CI->model_payroll->getDataPayrollHarian(['a.id_pay'=>$id_pay]);
			}else{
				$pay_emp = $this->CI->model_payroll->getDataLogPayrollHarian(['a.id_pay'=>$id_pay]);
			}
		}

 		// if($usage == 'data'){
 		// 	$pay_emp = $this->CI->model_karyawan->getDataPayroll(['a.id_pay'=>$id_pay]);
 		// }else{
 		// 	$pay_emp = $this->CI->model_karyawan->getDataLogPayroll(['a.id_pay'=>$id_pay]);
		 // }
		if(!empty($pay_emp)){
			$pay_emp = $this->CI->otherfunctions->convertResultToRowArray($pay_emp);
			// $getPresensiData = $this->getPresensiData($id,$kode_periode_penggajian);
			// $bpjs = $this->getBpjs($id);
			// $bpjs_jk = [$bpjs['jht'],$bpjs['jkk'],$bpjs['jkm']];
			// $angsuran = $this->getAngsuran($id,$kode_periode_penggajian);
			// $potongan_tidak_masuk = $this->getPotongannTidakMasuk($getPresensiData,$gaji_pokok,$tgl_masuk);
			// $potongan_tidak_masuk = $this->CI->otherfunctions->nonPembulatan($potongan_tidak_masuk);
			$bpjs_jk = [$pay_emp['bpjs_jht'],$pay_emp['bpjs_jkk'],$pay_emp['bpjs_jkm']];
			$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
			<tr>
			<th style="text-align:center;" colspan="2">Faktor Pengurang</th>
			</tr>
			<tr>
			<th style="text-align:center;" >Nama</th>
			<th style="text-align:center;" >Nominal</th>
			</tr>
			</thead>';
			$table .= '<tbody>';
			$potTidakMasuk = ($pay_emp['pot_tidak_masuk']+$pay_emp['n_terlambat']+$pay_emp['n_izin']+$pay_emp['n_iskd']+$pay_emp['n_imp']);
			$table .= '<tr><td>Potongan Tidak Masuk</td><td>'.$this->getFormatMoneyUser($potTidakMasuk).'</td></tr>';
			$table .= '<tr><td>BPJS TK - JHT, JKK, JKM</td><td>'.$this->getFormatMoneyUser(array_sum($bpjs_jk)).'</td></tr>';
			$table .= '<tr><td>BPJS Pensiun</td><td>'.$this->getFormatMoneyUser($pay_emp['bpjs_pen']).'</td></tr>';
			$table .= '<tr><td>BPJS Kesehatan</td><td>'.$this->getFormatMoneyUser($pay_emp['bpjs_kes']).'</td></tr>';
			$table .= '<tr><td>Piutang</td><td>'.$this->getFormatMoneyUser($pay_emp['angsuran']).'</td></tr>';
			$table .= '<tr><td>Denda</td><td>'.$this->getFormatMoneyUser($pay_emp['nominal_denda']).'</td></tr>';
			$table .= '<tr><td>Ijin Meninggalkan Pekerjaan</td><td>'.$this->getFormatMoneyUser($pay_emp['meninggalkan_jam_kerja_n']).'</td></tr>';
			if(!empty($pay_emp['data_lain'])){				
				$val_data=$this->CI->otherfunctions->getDataExplode($pay_emp['data_lain'],';','all');
				$nominal_data=$this->CI->otherfunctions->getDataExplode($pay_emp['nominal_lain'],';','all');
				$ket_lain=$this->CI->otherfunctions->getDataExplode($pay_emp['data_lain_nama'],';','all');
				foreach ($val_data as $kData => $vData) {
					if($vData=='pengurang'){
						$table.= '<tr><td>'.$ket_lain[$kData].'</td><td>'.$this->getFormatMoneyUser($nominal_data[$kData]).'</td></tr>';
					}
				}
			}
			$table .= '</tbody>';
			$table .= '</table>';
		}else{
			$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
			<tr>
			<th style="text-align:center;" colspan="2">Faktor Pengurang</th>
			</tr>
			<tr>
			<th style="text-align:center;" >Nama</th>
			<th style="text-align:center;" >Nominal</th>
			</tr>
			</thead>
			<tbody></tbody>
			</table>';
		}
 		return $table;
	 }
	 
 	public function getTablePenambahHarian($id,$kode_periode_penggajian,$id_pay,$usage = 'data',$sistem_penggajian = 'HARIAN')
 	{
		if($sistem_penggajian == 'HARIAN'){
			if($usage == 'data'){
				$pay_emp = $this->CI->model_payroll->getDataPayrollHarian(['a.id_pay'=>$id_pay]);
			}else{
				$pay_emp = $this->CI->model_payroll->getDataLogPayrollHarian(['a.id_pay'=>$id_pay]);
			}
		}
		if(!empty($pay_emp)){
			$pay_emp = $this->CI->otherfunctions->convertResultToRowArray($pay_emp);
			$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
			<tr>
			<th style="text-align:center;" colspan="2">Faktor Penambah</th>
			</tr>
			<tr>
			<th style="text-align:center;" >Nama</th>
			<th style="text-align:center;" >Nominal</th>
			</tr>
			</thead>';
			$table .= '<tbody>';
			if(!empty($pay_emp['data_lain'])){				
				$val_data=$this->CI->otherfunctions->getDataExplode($pay_emp['data_lain'],';','all');
				$nominal_data=$this->CI->otherfunctions->getDataExplode($pay_emp['nominal_lain'],';','all');
				$ket_lain=$this->CI->otherfunctions->getDataExplode($pay_emp['keterangan_lain'],';','all');
				foreach ($val_data as $kData => $vData) {
					if($vData=='penambah'){
						$table.= '<tr><td>'.$ket_lain[$kData].'</td><td>'.$this->getFormatMoneyUser($nominal_data[$kData]).'</td></tr>';
					}
				}
			}
			if($usage == 'data'){
				$data = $this->CI->model_payroll->getDataPayrollTunjangan(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian],$sistem_penggajian);
			}else{
				$data = $this->CI->model_payroll->getDataLogPayrollTunjangan(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian],$sistem_penggajian);
			}
			foreach ($data as $d) {
				if($d->sifat == 1){
					$table .= '<tr>
					<td>'.$d->nama_tunjangan.'</td>
					<td style="white-space: nowrap;">'.$this->CI->formatter->getFormatMoneyUser($d->nominal).'</td>
					</tr>';
				}else{
					if($d->upah == 1){
						$table .= '<tr>
						<td>'.$d->nama_tunjangan.'</td>
						<td style="white-space: nowrap;">'.$this->CI->formatter->getFormatMoneyUser($d->nominal).'</td>
						</tr>';
					}
				}
			}
			$table .= '</tbody>';
			$table .= '</table>';
		}else{
			$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
							<thead>
								<tr>
								<th style="text-align:center;" colspan="2">Faktor Penambah</th>
									</tr>
								<tr>
									<th style="text-align:center;" >Nama</th>
									<th style="text-align:center;" >Nominal</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>';
		}
 		return $table;
 	}
 	public function getTablePengurangHarian($id_pay,$usage = 'data',$sistem_penggajian = 'HARIAN')
 	{
		if($sistem_penggajian == 'HARIAN'){
			if($usage == 'data'){
				$pay_emp = $this->CI->model_payroll->getDataPayrollHarian(['a.id_pay'=>$id_pay]);
			}else{
				$pay_emp = $this->CI->model_payroll->getDataLogPayrollHarian(['a.id_pay'=>$id_pay]);
			}
		}
		if(!empty($pay_emp)){
			$pay_emp = $this->CI->otherfunctions->convertResultToRowArray($pay_emp);
			$bpjs_jk = [$pay_emp['bpjs_jht'],$pay_emp['bpjs_jkk'],$pay_emp['bpjs_jkm']];
			$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
			<tr>
			<th style="text-align:center;" colspan="2">Faktor Pengurang</th>
			</tr>
			<tr>
			<th style="text-align:center;" >Nama</th>
			<th style="text-align:center;" >Nominal</th>
			</tr>
			</thead>';
			$table .= '<tbody>';
			if(!empty($pay_emp['data_lain'])){				
				$val_data=$this->CI->otherfunctions->getDataExplode($pay_emp['data_lain'],';','all');
				$nominal_data=$this->CI->otherfunctions->getDataExplode($pay_emp['nominal_lain'],';','all');
				$ket_lain=$this->CI->otherfunctions->getDataExplode($pay_emp['keterangan_lain'],';','all');
				foreach ($val_data as $kData => $vData) {
					if($vData=='pengurang'){
						$table.= '<tr><td>'.$ket_lain[$kData].'</td><td>'.$this->getFormatMoneyUser($nominal_data[$kData]).'</td></tr>';
					}
				}
			}
			$table .= '</tbody>';
			$table .= '</table>';
		}else{
			$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
			<tr>
			<th style="text-align:center;" colspan="2">Faktor Pengurang</th>
			</tr>
			<tr>
			<th style="text-align:center;" >Nama</th>
			<th style="text-align:center;" >Nominal</th>
			</tr>
			</thead>
			<tbody></tbody>
			</table>';
		}
 		return $table;
 	}
 	public function getTableLembur($id_pay,$usage = 'data',$sistem_penggajian = 'BULANAN')
 	{
		if($sistem_penggajian == 'BULANAN'){
			if($usage == 'data'){
				$pay_emp = $this->CI->model_payroll->getDataPayroll(['a.id_pay'=>$id_pay]);
			}else{
				$pay_emp = $this->CI->model_payroll->getDataLogPayroll(['a.id_pay'=>$id_pay]);
			}
			$pay_emp = $this->CI->otherfunctions->convertResultToRowArray($pay_emp);
			$get_lembur = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_payroll->getDataPayrollLembur(['a.kode_periode'=>$pay_emp['kode_periode'],'a.id_karyawan'=>$pay_emp['id_karyawan']]));
		}elseif($sistem_penggajian == 'HARIAN'){
			if($usage == 'data'){
				$pay_emp = $this->CI->model_payroll->getDataPayrollHarianNew(['a.id_pay'=>$id_pay]);
			}else{
				$pay_emp = $this->CI->model_payroll->getDataLogPayrollHarian(['a.id_pay'=>$id_pay]);
			}
			$pay_emp = $this->CI->otherfunctions->convertResultToRowArray($pay_emp);
			$get_lembur = [
				'jam_biasa'=>$pay_emp['jam_biasa'],
				'nominal_biasa'=>$pay_emp['nominal_biasa'],
				'jam_libur'=>$pay_emp['jam_libur'],
				'nominal_libur'=>$pay_emp['nominal_libur'],
				'jam_libur_pendek'=>$pay_emp['jam_libur_pendek'],
				'nominal_libur_pendek'=>$pay_emp['nominal_libur_pendek'],
				'gaji_terima'=>$pay_emp['gaji_lembur'],
			];
		}
 		// if($usage == 'data'){
 		// 	$pay_emp = $this->CI->model_karyawan->getDataPayroll(['a.id_pay'=>$id_pay]);
 		// }else{
 		// 	$pay_emp = $this->CI->model_karyawan->getDataLogPayroll(['a.id_pay'=>$id_pay]);
 		// }
 		$table = '<table class="border" width="100%" border="3">
 		<thead>
 		<tr>
 		<th style="text-align:center;" colspan="3">Data Lembur</th>
 		</tr>
 		<tr>
 		<th style="text-align:center;" >Nama</th>
 		<th style="text-align:center;" >Jam</th>
 		<th style="text-align:center;" >Nominal</th>
 		</tr>
 		</thead>';
		 $table .= '<tbody>';
		 $table.= '<tr><td style="padding: 2px;">Jam Lembur Biasa</td><td style="padding: 2px;">'.$get_lembur['jam_biasa'].'</td><td style="padding: 2px;">'.$this->getFormatMoneyUser($get_lembur['nominal_biasa']).'</td></tr>';
		 $table.= '<tr><td style="padding: 2px;">Jam Lembur Libur</td><td style="padding: 2px;">'.$get_lembur['jam_libur'].'</td><td style="padding: 2px;">'.$this->getFormatMoneyUser($get_lembur['nominal_libur']).'</td></tr>';
		 $table.= '<tr><td style="padding: 2px;">Jam Libur Istirahat</td><td style="padding: 2px;">'.$get_lembur['jam_libur_pendek'].'</td><td style="padding: 2px;">'.$this->getFormatMoneyUser($get_lembur['nominal_libur_pendek']).'</td></tr>';
		 $table.= '<tr><td style="padding: 2px;">Total Uang Lembur</td><td style="padding: 2px;"></td><td style="padding: 2px;">'.$this->getFormatMoneyUser($get_lembur['gaji_terima']).'</td></tr>';
 		$table .= '</tbody>';
 		$table .= '</table>';
 		return $table;
 	}
 	public function getTableBPJSHarian($id_pay)
 	{
		$bpjs = $this->CI->model_payroll->getDataPayrollHarianNew(['a.id_pay'=>$id_pay],null,null,null,true);
		$table = '<table class="border" width="100%" border="3">
		<thead>
			<tr>
				<th style="text-align:center;padding: 2px;" colspan="2">Data BPJS</th>
			</tr>
			<tr>
				<th style="text-align:center;padding: 2px;">BPJS</th>
				<th style="text-align:center;padding: 2px;">Nominal</th>
			</tr>
 		</thead>';
		 $table.= '<tbody>';
		 $table.= '<tr><td  style="padding: 2px;">BPJS Kesehatan</td><td  style="padding: 2px;">'.$this->getFormatMoneyUser($bpjs['jkes']).'</td></tr>';
		 $table.= '<tr><td  style="padding: 2px;">Jaminan Hari Tua</td>><td  style="padding: 2px;">'.$this->getFormatMoneyUser($bpjs['jht']).'</td></tr>';
		 $table.= '<tr><td  style="padding: 2px;">Jaminan Pensiun</td><td  style="padding: 2px;">'.$this->getFormatMoneyUser($bpjs['jpen']).'</td></tr>';
		 $table.= '<tr><td  style="padding: 2px;">Jaminan Kecelakaan Kerja</td><td  style="padding: 2px;">'.$this->getFormatMoneyUser($bpjs['jkk']).'</td></tr>';
		 $table.= '<tr><td  style="padding: 2px;">Jaminan Kematian</td><td  style="padding: 2px;">'.$this->getFormatMoneyUser($bpjs['jkm']).'</td></tr>';
		 $table.= '<tr><td  style="padding: 2px;">Total BPJS</td><td  style="padding: 2px;">'.$this->getFormatMoneyUser(($bpjs['jkes']+$bpjs['jht']+$bpjs['jpen']+$bpjs['jkk']+$bpjs['jkm'])).'</td></tr>';
 		$table .= '</tbody>';
 		$table .= '</table>';
 		return $table;
 	}
 	public function getTableDataLainnya($id_pay)
 	{
		$lainnya = $this->CI->model_payroll->getDataPayrollHarianNew(['a.id_pay'=>$id_pay],null,null,null,true);
		$table = '<table class="border" width="100%" border="3">
		<thead>
			<tr>
				<th style="text-align:center;padding: 2px;" colspan="3">Data Lainnya</th>
			</tr>
			<tr>
				<th style="text-align:center;padding: 2px;">NAMA</th>
				<th style="text-align:center;padding: 2px;">Jenis</th>
				<th style="text-align:center;padding: 2px;">Nominal</th>
			</tr>
 		</thead>';
		 $table.= '<tbody>';
		 if(!empty($lainnya) && !empty($lainnya['data_lain'])){
			 $data_lain = $this->CI->otherfunctions->getDataExplode($lainnya['data_lain'],";","all");
			 $nama_lain = $this->CI->otherfunctions->getDataExplode($lainnya['keterangan_lain'],";","all");
			 $nominal_lain = $this->CI->otherfunctions->getDataExplode($lainnya['nominal_lain'],";","all");
			 $no = 1;
			foreach ($data_lain as $key => $value) {
				$table.= '<tr><td style="padding: 2px;">'.$no.'. '.$nama_lain[$key].'</td>
				<td style="padding: 2px;">'.$no.'. '.$value.'</td>
				<td style="padding: 2px;">'.$no.'. '.$this->getFormatMoneyUser($nominal_lain[$key]).'</td></tr>';
				$no++;
			}
		 }else{
			 $table.='<tr>
				 <th style="text-align:center;padding: 2px;" colspan="3">Tidak Ada Data</th>
			 </tr>';
		 }
 		$table .= '</tbody>';
 		$table .= '</table>';
 		return $table;
 	}

 	public function getTablePPH($id,$gaji_pokok,$id_pay,$usage = 'data')
 	{
 		if($usage == 'data'){
 			$pay_emp = $this->CI->model_payroll->getDataPayroll(['a.id_pay'=>$id_pay]);
 		}else{
 			$pay_emp = $this->CI->model_payroll->getDataLogPayroll(['a.id_pay'=>$id_pay]);
 		}
 		$pay_emp = $this->CI->otherfunctions->convertResultToRowArray($pay_emp);

 		$emp = $this->CI->model_karyawan->getEmployeeId($id);
 		$tunjangan = $this->getTUnjangan($id);
 		$tunjangan_nominal = $this->getTunjanganNominalPayroll($tunjangan);
 		$upah = $gaji_pokok+$tunjangan_nominal;
		// $tunjangan_tetap = $this->getTunjanganNominalTetap($tunjangan);
		$bpjs_p_jkk = $this->getBpjsBayarPerusahaan($upah, 'JKK-RS');
		$bpjs_p_jkm = $this->getBpjsBayarPerusahaan($upah, 'JKM');
		$bpjs_p_jkes = $this->getBpjsBayarPerusahaan($upah, 'jkes');
		// $bruto_bulan = $gaji_pokok+$tunjangan_tetap+$bpjs_p_jkk+$bpjs_p_jkm+$bpjs_p_jkes;
		$bruto_bulan = $this->getBrutoBulan($id,$gaji_pokok,$tunjangan);
		$bruto_tahun = ($bruto_bulan*12);
		$biaya_jabatan = $this->getBiayaJabatan($id,$gaji_pokok,$tunjangan)['biaya_hasil'];
		$bpjs_p_jht = $this->getBpjsBayarPerusahaan($upah, 'JHT');
		// $bpjs_k_jht = $this->getBpjs($id)['jht'];
		$bpjs_k_jht = $pay_emp['bpjs_jht'];
		$iuran_k_pensiun = $this->getIuranPensiun($id,$gaji_pokok);
		$jml_pengurangan = ($biaya_jabatan+$bpjs_k_jht+$iuran_k_pensiun);

		$netto_bulan = $bruto_bulan-$jml_pengurangan;
		$netto_tahun = $netto_bulan*12;
		$kena_pajak_tahun = $this->getPajakPertahun($netto_bulan,$emp['status_pajak']);
 		$ptkp = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListPtkp(['status_ptk'=>$emp['status_pajak']]));
		$layer =$this->getLayerPPH($kena_pajak_tahun);
		$pph = $this->getPPHPertahun($layer,$emp['npwp']);

		if(empty($emp['npwp'])){
			$get_pph = $pph['plus_npwp'];
		}else{
			$get_pph = $pph['pph_bulan'];
		}
		// $new_val = [
		// 	'emp'=>$emp,
		// 	'tunjangan'=>$tunjangan,
		// 	'bpjs_p_jkk'=>$bpjs_p_jkk,
		// 	'bpjs_p_jkm'=>$bpjs_p_jkm,
		// 	'bpjs_p_jkes'=>$bpjs_p_jkes,

		// 	'bruto_bulan'=>$bruto_bulan,
		// 	'bruto_tahun'=>$bruto_tahun,
		// 	'biaya_jabatan'=>$biaya_jabatan,
		// 	'bpjs_p_jht'=>$bpjs_p_jht,
		// 	'bpjs_k_jht'=>$bpjs_k_jht,
		// 	'iuran_k_pensiun'=>$iuran_k_pensiun,
		// 	'jml_pengurangan'=>$jml_pengurangan,
		// 	'netto_bulan'=>$netto_bulan,
		// 	'netto_tahun'=>$netto_tahun,
		// 	'kena_pajak_tahun'=>$kena_pajak_tahun,
		// 	'layer'=>$layer,
		// 	'pph'=>$pph,
		// 	'get_pph'=>$get_pph,
		// 	'ptkp'=>$ptkp,
		// 	'ptkp_emp'=>$emp['status_pajak'],
		// ];
		// return $new_val;

 		$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
 		<thead>
 		<tr>
 		<th style="text-align:center;" colspan="2">PPH-21</th>
 		</tr>
 		<tr>
 		<th style="text-align:center;" >Faktor</th>
 		<th style="text-align:center;" >Nominal</th>
 		</tr>
 		</thead>';
 		$table .= '<tbody>';
 		$table .= '<tr><td>Bruto</td><td>'.$this->getFormatMoneyUser($this->CI->otherfunctions->nonPembulatan($bruto_bulan)).'</td></tr>';
 		$table .= '<tr><td>Netto</td><td>'.$this->getFormatMoneyUser($this->CI->otherfunctions->nonPembulatan($netto_bulan)).'</td></tr>';
 		$table .= '<tr><td>PPH Sebulan</td><td>'.$this->getFormatMoneyUser($this->CI->otherfunctions->nonPembulatan($get_pph)).'</td></tr>';
 		$table .= '</tbody>';
 		$table .= '</table>';
 		return $table;
 	}

 	public function getBpjs($id,$kode_periode_penggajian)
 	{
 		$periode = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode_penggajian]));
		// $bulan = date("m",strtotime($periode['tgl_mulai']));
		// $tahun = date("Y",strtotime($periode['tgl_mulai']));
		$bulan = $periode['bulan'];
		$tahun = $periode['tahun'];
		$data_bpjs = $this->CI->model_payroll->getListBpjsEmp(['a.id_karyawan'=>$id,'a.bulan'=>$bulan,'a.tahun'=>$tahun],'active');
		if(!empty($data_bpjs)){
			foreach ($data_bpjs as $d) {
				$new_val = [
					'jht'=>$d->jht,
					'jkk'=>$d->jkk,
					'jkm'=>$d->jkm,
					'jpns'=>$d->jpns,
					'jkes'=>$d->jkes
				];
			}
		}else{
			$new_val = [
				'jht'=>0,
				'jkk'=>0,
				'jkm'=>0,
				'jpns'=>0,
				'jkes'=>0
			];
		}
 		return $new_val;
 	}

 	public function getBpjsBayarPerusahaan($gaji_pokok, $usage)
 	{
 		if(empty($usage))
 			return null;
 		$per_bpjs = 0;
 		$get_bpjs = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListBpjs(['a.inisial'=>$usage,'a.status'=>1]));
 		if(!empty($get_bpjs)){
 			$per_bpjs = $get_bpjs['bpjs_perusahaan'];
 		}
 		$new_val = ($per_bpjs/100)*$gaji_pokok;
 		return $new_val;
 	}

 	public function getBpjsBayarSendiri($gaji_pokok, $usage, $id_kar=null)
 	{
 		if(empty($usage))
 			return null;
		$new_val = null;
 		$get_bpjs = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListBpjs(['a.inisial'=>$usage,'a.status'=>1]));
		$umurPensiun = $this->CI->model_master->getGeneralSetting('UMUR_PENSIUN')['value_int'];
 		if(!empty($get_bpjs)){
			if($usage == 'JPNS'){
				if(!empty($id_kar)){
					$emp = $this->CI->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$id_kar],true);
					$umur = $this->CI->otherfunctions->intervalTimeYear($emp['tgl_lahir'],'umur');
					if($umur['tahun'] >= $umurPensiun){
						$per_bpjs = 0;
					}else{
						$per_bpjs = $get_bpjs['bpjs_karyawan'];
					}
				}else{
					$per_bpjs = $get_bpjs['bpjs_karyawan'];
				}
			}else{
 				$per_bpjs = $get_bpjs['bpjs_karyawan'];
			}
 			$new_val = ($per_bpjs/100)*$gaji_pokok;
 		}
 		// $per_bpjs = 0;
 		// $get_bpjs = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListBpjs(['a.inisial'=>$usage,'a.status'=>1]));
 		// if(!empty($get_bpjs)){
 		// 	$per_bpjs = $get_bpjs['bpjs_karyawan'];
 		// }
 		// $new_val = ($per_bpjs/100)*$gaji_pokok;
 		return $new_val;
 	}

 	public function getBrutoBulan($id,$gaji_pokok,$tunjangan)
 	{
 		if(empty($id) || empty($gaji_pokok)){
 			$bruto = 0;
 		}else{
 			// $bpjs_jkk = $this->getBpjsBayarPerusahaan($gaji_pokok,'jkk');
 			// $bpjs_jkm = $this->getBpjsBayarPerusahaan($gaji_pokok,'jkm');
 			// $bpjs_jkes = $this->getBpjsBayarPerusahaan($gaji_pokok,'jkes');
 			$bpjs_jkk = $this->getBpjsBayarPerusahaan($upah, 'JKK-RS');
 			$bpjs_jkm = $this->getBpjsBayarPerusahaan($upah, 'JKM');
 			$bpjs_jkes = $this->getBpjsBayarPerusahaan($upah, 'jkes');
 			// $tunjangan = $this->getTUnjangan($id);
 			$tunjangan_nominal = 0;
 			if(!empty($tunjangan)){
	 			foreach ($tunjangan as $key => $value) {
	 				if($value['sifat'] == 1){
	 					$tunjangan_nominal += $value['nominal'];
	 				}else{
	 					if($value['pph'] == 1){
	 						$tunjangan_nominal += $value['nominal'];
	 					}
	 				}
	 			}
 			}
 			// $tunjangan_nominal = $this->getTunjanganNominal($tunjangan);
 			$bruto = $gaji_pokok+$tunjangan_nominal+$bpjs_jkk+$bpjs_jkm+$bpjs_jkes;
 		}
 		return $bruto;
 	}

 	// public function getBiayaJabatan($id,$gaji_pokok,$tunjangan)
 	// {
 	// 	if(empty($id) || empty($gaji_pokok)){
 	// 		$biaya = [
 	// 			'biaya_asli'=>0,
 	// 			'biaya_hasil'=>0
 	// 		];
 	// 	}else{
 	// 		$master_biaya = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListTarifJabatan());
 	// 		$bruto = $this->getBrutoBulan($id,$gaji_pokok,$tunjangan);
 	// 		$biaya_asli =($master_biaya['tarif']/100)*$bruto;
 	// 		if($biaya_asli>$master_biaya['maximal']){
 	// 			$biaya_convert = $master_biaya['maximal'];
 	// 		}else{
 	// 			$biaya_convert = $biaya_asli;
 	// 		}
 	// 		$biaya = [
 	// 			'biaya_asli'=>$biaya_asli,
 	// 			'biaya_hasil'=>$biaya_convert
 	// 		];
 	// 	}
 	// 	return $biaya;
 	// }
 	public function getBiayaJabatan($bruto_bulan)
 	{
 		if(empty($bruto_bulan)){
 			$biaya = [
 				'biaya_asli'=>0,
 				'biaya_hasil'=>0
 			];
 		}else{
 			$master_biaya = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListTarifJabatan());
 			$biaya_asli =($master_biaya['tarif']/100)*$bruto_bulan;
 			if($biaya_asli>$master_biaya['maximal']){
 				$biaya_convert = $master_biaya['maximal'];
 			}else{
 				$biaya_convert = $biaya_asli;
 			}
 			$biaya = [
 				'biaya_asli'=>$biaya_asli,
 				'biaya_hasil'=>$biaya_convert
 			];
 		}
 		return $biaya;
 	}

 	// public function getIuranPensiun($id,$gaji_pokok)
 	// {
 	// 	if(empty($id) || empty($gaji_pokok)){
 	// 		$iuran = 0;
 	// 	}else{
 	// 		$tunjangan = $this->getTUnjangan($id);	
 	// 		// $tunjangan_nominal = $this->getTunjanganNominal($tunjangan);
 	// 		// $tunjangan_nominal = 0;
 	// 		// if(!empty($tunjangan)){
	 // 		// 	foreach ($tunjangan as $key => $value) {
	 // 		// 		if($value['pph'] == 1){
	 // 		// 			$tunjangan_nominal += $value['nominal'];
	 // 		// 		}
	 // 		// 	}
 	// 		// }
 	// 		$tunjangan_nominal = $this->getTunjanganNominalPPH($tunjangan);
 	// 		$iuran = (1/100)*($gaji_pokok+$tunjangan_nominal);
 	// 	}
 	// 	return $iuran;
 	// }
 	public function getIuranPensiun($upah)
 	{
 		if(empty($upah)){
 			$iuran = 0;
 		}else{
 			$iuran = (1/100)*($upah);
 		}
 		return $iuran;
 	}

 	// public function getPajakPertahun($netto_bulan,$ptkp)
 	// {
 	// 	if(empty($netto_bulan) || empty($ptkp))
 	// 		return 0;

 	// 	$data_ptkp = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListPtkp(['status_ptk'=>$ptkp]));
 	// 	$netto_tahun = $netto_bulan*12;
 	// 	$tarif_pajak = $netto_tahun-$data_ptkp['tarif_per_tahun'];
 	// 	return $tarif_pajak;
 	// }

 	public function getPajakPertahun($netto_tahun,$ptkp)
 	{
 		if(empty($netto_tahun) || empty($ptkp))
 			return 0;
 		$data_ptkp = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListPtkp(['status_ptk'=>$ptkp]));
 		$tarif_pajak = $netto_tahun-$data_ptkp['tarif_per_tahun'];
 		return $tarif_pajak;
 	}
 	public function getPajakPertahunHarian($netto,$ptkp,$presensi)
 	{
 		if(empty($netto) || empty($ptkp))
 			return 0;
		if($presensi == '0' || empty($presensi)){
			$n_ptkp = 0;
			$tarif_pajak = 0;
		}else{
			$data_ptkp = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListPtkp(['status_ptk'=>$ptkp]));
			$n_ptkp = $presensi*$data_ptkp['tarif_per_tahun']/360;
			$tarif_pajak = $netto-$n_ptkp;
		}
		$data = [
			'ptkp'=>$n_ptkp,
			'pkp'=>$tarif_pajak,
		];
 		return $data;
 	}
 	public function getPTKP($ptkp)
 	{
 		if(empty($ptkp))
 			return 0;
 		$data_ptkp = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListPtkp(['status_ptk'=>$ptkp]));
 		return $data_ptkp['tarif_per_tahun'];
 	}

 	// public function getLayerPPH($tarif_pajak)
 	// {
 	// 	if(empty($tarif_pajak)){
 	// 		$val = [
 	// 			'tarif'=> 0,
 	// 			'dari'=> 0,
 	// 			'sampai'=> 0,
 	// 			'rentang'=>0,
 	// 			'hasil'=>0
 	// 		];
 	// 	}else{
 	// 		$val =[];
 	// 		$pph = $this->CI->model_master->getListPph();
 	// 		foreach ($pph as $p) {
 	// 			if($tarif_pajak > $p->dari){
 	// 				$rentang = $tarif_pajak-($p->dari-1);
 	// 				if($tarif_pajak > $p->sampai){
 	// 					$rentang = $p->sampai;
 	// 				}
 	// 				$hasil = ($p->tarif/100)*$rentang;
 	// 				$val[$p->kode_pph] = [
 	// 					'tarif'=> $p->tarif,
 	// 					'dari'=> $p->dari,
 	// 					'sampai'=> $p->sampai,
 	// 					'rentang'=>$rentang,
 	// 					'hasil'=>$hasil
 	// 				];
 	// 			}
 	// 		}
 	// 	}
 	// 	return $val;
 	// }
	// $pph = $this->CI->model_master->getListPph(null,'active');
	// foreach ($pph as $p) {
	// 	if($tarif_pajak > $p->dari && $tarif_pajak < $p->sampai){
	// 		$rentang = $tarif_pajak-($p->dari-1);
	// 		if($tarif_pajak > $p->sampai){
	// 			$rentang = $p->sampai;
	// 		}
	// 		$hasil = ($p->tarif/100)*($tarif_pajak-$p->dari);
	// 		$val[$p->kode_pph] = [
	// 			'tarif'=> $p->tarif,
	// 			'dari'=> $p->dari,
	// 			'sampai'=> $p->sampai,
	// 			'rentang'=>$rentang,
	// 			'hasil'=>$hasil
	// 		];
	// 	}
	// }
 	public function getLayerPPH($tarif_pajak, $npwp=null)
 	{
 		if(empty($tarif_pajak)){
 			$val = [
 				'tarif'=> 0,
 				'dari'=> 0,
 				'sampai'=> 0,
 				'rentang'=>0,
 				'hasil'=>0
 			];
 		}else{
 			$val =[];
 			if(!empty($npwp)){
				if($tarif_pajak > 0){
					$pph_1 = $this->CI->model_master->getListPphRow(['a.level'=>1],'active');
					$pph_2 = $this->CI->model_master->getListPphRow(['a.level'=>2],'active');
					$pph_3 = $this->CI->model_master->getListPphRow(['a.level'=>3],'active');
					$pph_4 = $this->CI->model_master->getListPphRow(['a.level'=>4],'active');
					$val = [];
					$tarif_2 = $tarif_pajak - $pph_1['sampai'];
					$tarif_3 = $tarif_pajak - $pph_2['sampai'];
					$tarif_4 = $tarif_pajak - $pph_3['sampai'];
					$layer2 = $tarif_pajak-($pph_1['sampai']);
					$layer3 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']);
					$layer4 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']);
					if($pph_1['dari'] <= $tarif_pajak && $pph_1['sampai'] >= $tarif_pajak){
						$hasil = ($pph_1['tarif']/100)*($tarif_pajak);
						$val[$pph_1['kode_pph']] = [
							'tarif'=> $pph_1['tarif'],
							'dari'=> $pph_1['dari'],
							'sampai'=> $pph_1['sampai'],
							'level'=>$pph_1['level'],
							'hasil'=>$hasil
						];
					}elseif($layer2 > 0 && $layer3 < 0 && $layer4 < 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = 0;
						if($tarif_pajak >= $pph_1['sampai']){
							$hasil_2 = ($pph_2['tarif']/100)*($tarif_2);
						}
						$val[$pph_2['kode_pph']] = [
							'tarif'=> $pph_2['tarif'],
							'dari'=> $pph_2['dari'],
							'sampai'=> $pph_2['sampai'],
							'level'=>$pph_2['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil'=>$hasil_1+$hasil_2,
						];
					}elseif($layer3 > 0 && $layer4 < 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['tarif']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['tarif']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']));
						$val[$pph_3['kode_pph']] = [
							'tarif'=> $pph_3['tarif'],
							'dari'=> $pph_3['dari'],
							'sampai'=> $pph_3['sampai'],
							'level'=>$pph_3['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil3'=>$hasil_3,
							'hasil'=>$hasil_1+$hasil_2+$hasil_3,
						];
					}elseif($layer3 > 0 && $layer4 > 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['tarif']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['tarif']/100)*($pph_3['sampai']);
						$hasil_4 = ($pph_4['tarif']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']));
						$val[$pph_4['kode_pph']] = [
							'tarif'=> $pph_4['tarif'],
							'dari'=> $pph_4['dari'],
							'sampai'=> $pph_4['sampai'],
							'level'=>$pph_4['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil3'=>$hasil_3,
							'hasil4'=>$hasil_4,
							'hasil'=>$hasil_1+$hasil_2+$hasil_3+$hasil_4,
						];
					}
				}else{
					$val[] = [
						'tarif'=> 0,
						'dari'=> 0,
						'sampai'=> 0,
						'rentang'=>0,
						'hasil'=>0
					];
				}
			}else{
				if($tarif_pajak > 0){
					$pph_1 = $this->CI->model_master->getListPphRow(['a.level'=>1],'active');
					$pph_2 = $this->CI->model_master->getListPphRow(['a.level'=>2],'active');
					$pph_3 = $this->CI->model_master->getListPphRow(['a.level'=>3],'active');
					$pph_4 = $this->CI->model_master->getListPphRow(['a.level'=>4],'active');
					$val = [];
					$tarif_2 = $tarif_pajak - $pph_1['sampai'];
					$tarif_3 = $tarif_pajak - $pph_2['sampai'];
					$tarif_4 = $tarif_pajak - $pph_3['sampai'];
					$layer3 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']);
					$layer4 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']);
					if($pph_1['sampai'] >= $tarif_pajak){
						$hasil = ($pph_1['non_npwp']/100)*($tarif_pajak);
						$val[$pph_1['kode_pph']] = [
							'tarif'=> $pph_1['non_npwp'],
							'dari'=> $pph_1['dari'],
							'sampai'=> $pph_1['sampai'],
							'level'=>$pph_1['level'],
							'hasil'=>$hasil
						];
					}elseif($pph_2['dari'] <= $tarif_pajak && $pph_2['sampai'] >= $tarif_pajak){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1['sampai']);
						$hasil_2 = 0;
						if($tarif_pajak >= $pph_1['sampai']){
							$hasil_2 = ($pph_2['non_npwp']/100)*($tarif_2);
						}
						$val[$pph_2['kode_pph']] = [
							'tarif'=> $pph_2['non_npwp'],
							'dari'=> $pph_2['dari'],
							'sampai'=> $pph_2['sampai'],
							'level'=>$pph_2['level'],
							'hasil'=>$hasil_1+$hasil_2,
						];
					}elseif($layer3 <= $pph_3['sampai']){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['non_npwp']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['non_npwp']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']));
						$val[$pph_3['kode_pph']] = [
							'tarif'=> $pph_3['non_npwp'],
							'dari'=> $pph_3['dari'],
							'sampai'=> $pph_3['sampai'],
							'level'=>$pph_3['level'],
							'hasil'=>$hasil_1+$hasil_2+$hasil_3,
						];
					}elseif($layer4 >= $pph_4['sampai']){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['non_npwp']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['non_npwp']/100)*($pph_3['sampai']);
						$hasil_4 = ($pph_4['non_npwp']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']));
						$val[$pph_4['kode_pph']] = [
							'tarif'=> $pph_4['non_npwp'],
							'dari'=> $pph_4['dari'],
							'sampai'=> $pph_4['sampai'],
							'level'=>$pph_4['level'],
							'hasil'=>$hasil_1+$hasil_2+$hasil_3+$hasil_4,
						];
					}
				}else{
					$val[] = [
						'tarif'=> 0,
						'dari'=> 0,
						'sampai'=> 0,
						'rentang'=>0,
						'hasil'=>0
					];
				}
			}
 		}
 		return $val;
 	}
 	public function getLayerPPHHarian($tarif_pajak, $npwp=null)
 	{
 		if(empty($tarif_pajak)){
 			$val = [
 				'tarif'=> 0,
 				'dari'=> 0,
 				'sampai'=> 0,
 				'rentang'=>0,
 				'hasil'=>0
 			];
 		}else{
 			$val =[];
 			if(!empty($npwp)){
				//  print_r($npwp);
				if($tarif_pajak > 0){
					$pph_1 = $this->CI->model_master->getListPphRow(['a.level'=>1],'active');
					$pph_2 = $this->CI->model_master->getListPphRow(['a.level'=>2],'active');
					$pph_3 = $this->CI->model_master->getListPphRow(['a.level'=>3],'active');
					$pph_4 = $this->CI->model_master->getListPphRow(['a.level'=>4],'active');
					$val = [];
					$tarif_2 = $tarif_pajak - $pph_1['sampai'];
					$tarif_3 = $tarif_pajak - $pph_2['sampai'];
					$tarif_4 = $tarif_pajak - $pph_3['sampai'];
					$layer2 = $tarif_pajak-($pph_1['sampai']);
					$layer3 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']);
					$layer4 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']);
					if($pph_1['dari'] <= $tarif_pajak && $pph_1['sampai'] >= $tarif_pajak){
						$hasil = ($pph_1['tarif']/100)*($tarif_pajak);
						$val[$pph_1['kode_pph']] = [
							'tarif'=> $pph_1['tarif'],
							'dari'=> $pph_1['dari'],
							'sampai'=> $pph_1['sampai'],
							'level'=>$pph_1['level'],
							'hasil'=>$hasil
						];
					}elseif($layer2 > 0 && $layer3 < 0 && $layer4 < 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = 0;
						if($tarif_pajak >= $pph_1['sampai']){
							$hasil_2 = ($pph_2['tarif']/100)*($tarif_2);
						}
						$val[$pph_2['kode_pph']] = [
							'tarif'=> $pph_2['tarif'],
							'dari'=> $pph_2['dari'],
							'sampai'=> $pph_2['sampai'],
							'level'=>$pph_2['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil'=>$hasil_1+$hasil_2,
						];
					}elseif($layer3 > 0 && $layer4 < 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['tarif']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['tarif']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']));
						$val[$pph_3['kode_pph']] = [
							'tarif'=> $pph_3['tarif'],
							'dari'=> $pph_3['dari'],
							'sampai'=> $pph_3['sampai'],
							'level'=>$pph_3['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil3'=>$hasil_3,
							'hasil'=>$hasil_1+$hasil_2+$hasil_3,
						];
					}elseif($layer3 > 0 && $layer4 > 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['tarif']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['tarif']/100)*($pph_3['sampai']);
						$hasil_4 = ($pph_4['tarif']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']));
						$val[$pph_4['kode_pph']] = [
							'tarif'=> $pph_4['tarif'],
							'dari'=> $pph_4['dari'],
							'sampai'=> $pph_4['sampai'],
							'level'=>$pph_4['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil3'=>$hasil_3,
							'hasil4'=>$hasil_4,
							'hasil'=>$hasil_1+$hasil_2+$hasil_3+$hasil_4,
						];
					}
				}else{
					$val[] = [
						'tarif'=> 0,
						'dari'=> 0,
						'sampai'=> 0,
						'rentang'=>0,
						'hasil'=>0
					];
				}
			}else{
				if($tarif_pajak > 0){
					$pph_1 = $this->CI->model_master->getListPphRow(['a.level'=>1],'active');
					$pph_2 = $this->CI->model_master->getListPphRow(['a.level'=>2],'active');
					$pph_3 = $this->CI->model_master->getListPphRow(['a.level'=>3],'active');
					$pph_4 = $this->CI->model_master->getListPphRow(['a.level'=>4],'active');
					$pph_1_sampai = $pph_1['sampai'];
					$pph_2_sampai = $pph_2['sampai'];
					$pph_3_sampai = $pph_3['sampai'];
					$pph_4_sampai = $pph_4['sampai'];
					$val = [];
					$tarif_2 = $tarif_pajak - $pph_1_sampai;
					$tarif_3 = $tarif_pajak - $pph_2_sampai;
					$tarif_4 = $tarif_pajak - $pph_3_sampai;
					$layer3 = $tarif_pajak-($pph_1_sampai+$pph_2_sampai);
					$layer4 = $tarif_pajak-($pph_1_sampai+$pph_2_sampai+$pph_3_sampai);
					if($pph_1_sampai >= $tarif_pajak){
						$hasil = ($pph_1['non_npwp']/100)*($tarif_pajak);
						$val[$pph_1['kode_pph']] = [
							'tarif'=> $pph_1['non_npwp'],
							'dari'=> $pph_1['dari'],
							'sampai'=> $pph_1_sampai,
							'level'=>$pph_1['level'],
							'hasil'=>$hasil
						];
					}elseif($pph_2['dari'] <= $tarif_pajak && $pph_2_sampai >= $tarif_pajak){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1_sampai);
						$hasil_2 = 0;
						if($tarif_pajak >= $pph_1_sampai){
							$hasil_2 = ($pph_2['non_npwp']/100)*($tarif_2);
						}
						$val[$pph_2['kode_pph']] = [
							'tarif'=> $pph_2['non_npwp'],
							'dari'=> $pph_2['dari'],
							'sampai'=> $pph_2_sampai,
							'level'=>$pph_2['level'],
							'hasil'=>$hasil_1+$hasil_2,
						];
					}elseif($layer3 <= $pph_3_sampai){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1_sampai);
						$hasil_2 = ($pph_2['non_npwp']/100)*($pph_2_sampai);
						$hasil_3 = ($pph_3['non_npwp']/100)*($tarif_pajak-($pph_1_sampai+$pph_2_sampai));
						$val[$pph_3['kode_pph']] = [
							'tarif'=> $pph_3['non_npwp'],
							'dari'=> $pph_3['dari'],
							'sampai'=> $pph_3_sampai,
							'level'=>$pph_3['level'],
							'hasil'=>$hasil_1+$hasil_2+$hasil_3,
						];
					}elseif($layer4 >= $pph_4_sampai){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1_sampai);
						$hasil_2 = ($pph_2['non_npwp']/100)*($pph_2_sampai);
						$hasil_3 = ($pph_3['non_npwp']/100)*($pph_3_sampai);
						$hasil_4 = ($pph_4['non_npwp']/100)*($tarif_pajak-($pph_1_sampai+$pph_2_sampai+$pph_3_sampai));
						$val[$pph_4['kode_pph']] = [
							'tarif'=> $pph_4['non_npwp'],
							'dari'=> $pph_4['dari'],
							'sampai'=> $pph_4_sampai,
							'level'=>$pph_4['level'],
							'hasil'=>$hasil_1+$hasil_2+$hasil_3+$hasil_4,
						];
					}
				}else{
					$val[] = [
						'tarif'=> 0,
						'dari'=> 0,
						'sampai'=> 0,
						'rentang'=>0,
						'hasil'=>0
					];
				}
			}
 		}
 		return $val;
 	}
 	public function getLayerPPHNonKaryawan($tarif_pajak, $npwp=null)
 	{
 		if(empty($tarif_pajak)){
 			$val = [
 				'tarif'=> 0,
 				'dari'=> 0,
 				'sampai'=> 0,
 				'rentang'=>0,
 				'hasil'=>0
 			];
 		}else{
 			$val =[];
 			if(!empty($npwp)){
				if($tarif_pajak > 0){
					$pph_1 = $this->CI->model_master->getListPphNonRow(['a.level'=>1],'active');
					$pph_2 = $this->CI->model_master->getListPphNonRow(['a.level'=>2],'active');
					$pph_3 = $this->CI->model_master->getListPphNonRow(['a.level'=>3],'active');
					$pph_4 = $this->CI->model_master->getListPphNonRow(['a.level'=>4],'active');
					$val = [];
					$tarif_2 = $tarif_pajak - $pph_1['sampai'];
					$tarif_3 = $tarif_pajak - $pph_2['sampai'];
					$tarif_4 = $tarif_pajak - $pph_3['sampai'];
					$layer2 = $tarif_pajak-($pph_1['sampai']);
					$layer3 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']);
					$layer4 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']);
					// if($pph_1['sampai'] >= $tarif_pajak){
					if($pph_1['dari'] <= $tarif_pajak && $pph_1['sampai'] >= $tarif_pajak){
						$hasil = ($pph_1['tarif']/100)*($tarif_pajak);
						$val[$pph_1['kode_pph']] = [
							'tarif'=> $pph_1['tarif'],
							'dari'=> $pph_1['dari'],
							'sampai'=> $pph_1['sampai'],
							'level'=>$pph_1['level'],
							'hasil'=>$hasil
						];
					// }elseif($pph_2['dari'] <= $tarif_pajak && $pph_2['sampai'] >= $tarif_pajak){
					}elseif($layer2 > 0 && $layer3 < 0 && $layer4 < 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = 0;
						if($tarif_pajak >= $pph_1['sampai']){
							$hasil_2 = ($pph_2['tarif']/100)*($tarif_2);
						}
						$val[$pph_2['kode_pph']] = [
							'tarif'=> $pph_2['tarif'],
							'dari'=> $pph_2['dari'],
							'sampai'=> $pph_2['sampai'],
							'level'=>$pph_2['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil'=>$hasil_1+$hasil_2,
						];
					// }elseif($layer3 <= $pph_3['sampai']){
					// }elseif($pph_3['dari'] <= $tarif_pajak && $pph_3['sampai'] >= $tarif_pajak){
					}elseif($layer3 > 0 && $layer4 < 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['tarif']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['tarif']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']));
						$val[$pph_3['kode_pph']] = [
							'tarif'=> $pph_3['tarif'],
							'dari'=> $pph_3['dari'],
							'sampai'=> $pph_3['sampai'],
							'level'=>$pph_3['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil3'=>$hasil_3,
							'hasil'=>$hasil_1+$hasil_2+$hasil_3,
						];
					// }elseif($layer4 >= $pph_4['sampai']){
					// }elseif($pph_4['dari'] <= $tarif_pajak){
					}elseif($layer3 > 0 && $layer4 > 0){
						$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['tarif']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['tarif']/100)*($pph_3['sampai']);
						$hasil_4 = ($pph_4['tarif']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']));
						$val[$pph_4['kode_pph']] = [
							'tarif'=> $pph_4['tarif'],
							'dari'=> $pph_4['dari'],
							'sampai'=> $pph_4['sampai'],
							'level'=>$pph_4['level'],
							'hasil1'=>$hasil_1,
							'hasil2'=>$hasil_2,
							'hasil3'=>$hasil_3,
							'hasil4'=>$hasil_4,
							'hasil'=>$hasil_1+$hasil_2+$hasil_3+$hasil_4,
						];
					}
				}else{
					$val[] = [
						'tarif'=> 0,
						'dari'=> 0,
						'sampai'=> 0,
						'rentang'=>0,
						'hasil'=>0
					];
				}
			}else{
				if($tarif_pajak > 0){
					$pph_1 = $this->CI->model_master->getListPphRow(['a.level'=>1],'active');
					$pph_2 = $this->CI->model_master->getListPphRow(['a.level'=>2],'active');
					$pph_3 = $this->CI->model_master->getListPphRow(['a.level'=>3],'active');
					$pph_4 = $this->CI->model_master->getListPphRow(['a.level'=>4],'active');
					$val = [];
					$tarif_2 = $tarif_pajak - $pph_1['sampai'];
					$tarif_3 = $tarif_pajak - $pph_2['sampai'];
					$tarif_4 = $tarif_pajak - $pph_3['sampai'];
					$layer2 = $tarif_pajak-($pph_1['sampai']);
					$layer3 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']);
					$layer4 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']);
					if($pph_1['dari'] <= $tarif_pajak && $pph_1['sampai'] >= $tarif_pajak){
						$hasil = ($pph_1['non_npwp']/100)*($tarif_pajak);
						$val[$pph_1['kode_pph']] = [
							'tarif'=> $pph_1['non_npwp'],
							'dari'=> $pph_1['dari'],
							'sampai'=> $pph_1['sampai'],
							'level'=>$pph_1['level'],
							'hasil'=>$hasil
						];
					}elseif($layer2 > 0 && $layer3 < 0 && $layer4 < 0){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1['sampai']);
						$hasil_2 = 0;
						if($tarif_pajak >= $pph_1['sampai']){
							$hasil_2 = ($pph_2['non_npwp']/100)*($tarif_2);
						}
						$val[$pph_2['kode_pph']] = [
							'tarif'=> $pph_2['non_npwp'],
							'dari'=> $pph_2['dari'],
							'sampai'=> $pph_2['sampai'],
							'level'=>$pph_2['level'],
							'hasil'=>$hasil_1+$hasil_2,
						];
					}elseif($layer3 > 0 && $layer4 < 0){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['non_npwp']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['non_npwp']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']));
						$val[$pph_3['kode_pph']] = [
							'tarif'=> $pph_3['non_npwp'],
							'dari'=> $pph_3['dari'],
							'sampai'=> $pph_3['sampai'],
							'level'=>$pph_3['level'],
							'hasil'=>$hasil_1+$hasil_2+$hasil_3,
						];
					}elseif($layer3 > 0 && $layer4 > 0){
						$hasil_1 = ($pph_1['non_npwp']/100)*($pph_1['sampai']);
						$hasil_2 = ($pph_2['non_npwp']/100)*($pph_2['sampai']);
						$hasil_3 = ($pph_3['non_npwp']/100)*($pph_3['sampai']);
						$hasil_4 = ($pph_4['non_npwp']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']));
						$val[$pph_4['kode_pph']] = [
							'tarif'=> $pph_4['non_npwp'],
							'dari'=> $pph_4['dari'],
							'sampai'=> $pph_4['sampai'],
							'level'=>$pph_4['level'],
							'hasil'=>$hasil_1+$hasil_2+$hasil_3+$hasil_4,
						];
					}
				}else{
					$val[] = [
						'tarif'=> 0,
						'dari'=> 0,
						'sampai'=> 0,
						'rentang'=>0,
						'hasil'=>0
					];
				}
			}
 		}
 		return $val;
 	}

 	public function getPPHPertahun($layerpph,$npwp)
 	{
 		if(empty($layerpph)){
 			$new_val = [
 				'pph_tahun'=>0,
 				'pph_bulan'=>0,
 				'plus_npwp'=>0
 			];
 		}else{
 			$pph = 0;
 			foreach ($layerpph as $key => $value) {
 				$pph += $value['hasil'];
 			}
 			if(empty($npwp)){
 				$npwpx = ((20/100)*($pph/12))+($pph/12);
 			}else{
 				$npwpx = ($pph/12);
 			}
 			$new_val = [
 				'pph_tahun'=>$pph,
 				'pph_bulan'=>($pph/12),
 				'plus_npwp'=>$npwpx
 			];
 			// if(empty($npwp)){
 			// 	$npwpx = ((20/100)*($pph/12))+($pph/12);
 			// }else{
 			// 	$npwpx = ($pph/12);
 			// }
 			// $new_val = [
 			// 	'pph_tahun'=>$pph,
 			// 	'pph_bulan'=>($pph/12),
 			// 	'plus_npwp'=>$npwpx
 			// ];
 		}
 		return $new_val;
 	}
 	public function getLayerPPHPesangon($tarif_pajak)
 	{
 		if(empty($tarif_pajak)){
 			$val = [
 				'tarif'=> 0,
 				'dari'=> 0,
 				'sampai'=> 0,
 				'rentang'=>0,
 				'hasil'=>0
 			];
 		}else{
 			// $val =[];
			if($tarif_pajak > 0){
				$pph_1 = $this->CI->model_master->getListPphPesangonRow(['a.level'=>1],'active');
				$pph_2 = $this->CI->model_master->getListPphPesangonRow(['a.level'=>2],'active');
				$pph_3 = $this->CI->model_master->getListPphPesangonRow(['a.level'=>3],'active');
				$pph_4 = $this->CI->model_master->getListPphPesangonRow(['a.level'=>4],'active');
				$val = [];
				$tarif_2 = $tarif_pajak - $pph_1['sampai'];
				$tarif_3 = $tarif_pajak - $pph_2['sampai'];
				$tarif_4 = $tarif_pajak - $pph_3['sampai'];
				$layer2 = $tarif_pajak-($pph_1['sampai']);
				$layer3 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']);
				$layer4 = $tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']);
				if($pph_1['dari'] <= $tarif_pajak && $pph_1['sampai'] >= $tarif_pajak){
					$hasil = ($pph_1['tarif']/100)*($tarif_pajak);
					$val = [
						'tarif'=> $pph_1['tarif'],
						'dari'=> $pph_1['dari'],
						'sampai'=> $pph_1['sampai'],
						'level'=>$pph_1['level'],
						'hasil'=>$hasil
					];
				}elseif($layer2 > 0 && $layer3 < 0 && $layer4 < 0){
					$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
					$hasil_2 = 0;
					if($tarif_pajak >= $pph_1['sampai']){
						$hasil_2 = ($pph_2['tarif']/100)*($tarif_2);
					}
					$val = [
						'tarif'=> $pph_2['tarif'],
						'dari'=> $pph_2['dari'],
						'sampai'=> $pph_2['sampai'],
						'level'=>$pph_2['level'],
						'hasil'=>$hasil_1+$hasil_2,
					];
				}elseif($layer3 > 0 && $layer4 < 0){
					$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
					$hasil_2 = ($pph_2['tarif']/100)*($pph_1['sampai']);
					$hasil_3 = ($pph_3['tarif']/100)*($tarif_pajak-($pph_1['sampai']+$pph_1['sampai']));
					$val = [
						'tarif'=> $pph_3['tarif'],
						'dari'=> $pph_3['dari'],
						'sampai'=> $pph_3['sampai'],
						'level'=>$pph_3['level'],
						// 'hasil1'=>$hasil_1,
						// 'hasil2'=>$hasil_2,
						// 'hasil3'=>$hasil_3,
						'hasil'=>$hasil_1+$hasil_2+$hasil_3,
					];
				}elseif($layer3 > 0 && $layer4 > 0){
					$hasil_1 = ($pph_1['tarif']/100)*($pph_1['sampai']);
					$hasil_2 = ($pph_2['tarif']/100)*($pph_2['sampai']);
					$hasil_3 = ($pph_3['tarif']/100)*($pph_3['sampai']);
					$hasil_4 = ($pph_4['tarif']/100)*($tarif_pajak-($pph_1['sampai']+$pph_2['sampai']+$pph_3['sampai']));
					$val = [
						'tarif'=> $pph_4['tarif'],
						'dari'=> $pph_4['dari'],
						'sampai'=> $pph_4['sampai'],
						'level'=>$pph_4['level'],
						'hasil'=>$hasil_1+$hasil_2+$hasil_3+$hasil_4,
					];
				}
			}else{
				$val = [
					'tarif'=> 0,
					'dari'=> 0,
					'sampai'=> 0,
					'rentang'=>0,
					'hasil'=>0
				];
			}
 		}
 		return $val;
 	}
	public function getPresensiHarian($id_karyawan,$tgl_mulai,$tgl_selesai)
	{
		$presensi = 0;
		$start=$tgl_mulai;
		while ($start <= $tgl_selesai)
		{
			$d_lembur=$this->CI->model_karyawan->getDataLemburDate($id_karyawan, $start);
			$d_pre=$this->CI->model_karyawan->checkPresensiEmpDate($id_karyawan, $start);
			if(((!empty($d_pre['jam_mulai']) && !empty($d_pre['jam_selesai'])) && ($d_pre['jam_mulai'] != "00:00:00" && $d_pre['jam_selesai'] != "00:00:00"))){
				$libur =  $this->CI->otherfunctions->checkHariLiburActive($start);
				if(isset($libur) || !empty($libur)){
					// $presensi_libur[] = $tgl_mulai;
					$presensi+=0;
				}else{
					$presensi+=1;
				}
			}else{
				$presensi+=0;
			}					
			$start = date('Y-m-d', strtotime($start . ' +1 day'));
		}
		return $presensi;
	}
 	public function getPresensiData($id,$from,$to,$gaji,$tgl_masuk)
 	{
 		if(empty($id))
 			return null;
 		$presensi_ada = [];
 		$presensi_tidak = [];
 		$presensi_libur = [];
		$total_gaji = 0;
		$count_date = $this->CI->otherfunctions->getDivDate($from,$to);
		// $gaji_per_hari = ($gaji/$count_date);
		$gaji_per_hari = $this->getGajiPerHari($gaji,$tgl_masuk);
		while ($from <= $to){
			$dataPresensi = $this->CI->model_karyawan->getPresensiWhereRow(['id_karyawan'=>$id,'tanggal'=>$from]);
			if(!empty($dataPresensi)){
				$libur = $this->CI->otherfunctions->checkHariLiburActive($from);
				if(isset($libur)){
					$presensi_libur[] = $libur;
				}
				$presensi_ada[] = $dataPresensi;
				// $gajix = (!empty($dataPresensi['potongan']))?($dataPresensi['potongan']/100)*$gaji_per_hari:$gaji_per_hari;
				if(!empty($dataPresensi['potongan'])){
					$gajix = $gaji_per_hari-(($dataPresensi['potongan']/100)*$gaji_per_hari);
				}else{
					$gajix = $gaji_per_hari;
				}
				$total_gaji += $gajix;
			}else{
				$libur = $this->CI->otherfunctions->checkHariLiburActive($from);
				if(isset($libur)){
					$presensi_libur[] = $from;
				}else{
					$presensi_tidak[] = $from;
				}
			}
			$from = date('Y-m-d', strtotime($from . ' +1 day'));
		}
		$new_val = [
			'ada'=>$presensi_ada,
			'libur'=>$presensi_libur,
			'tidak'=>$presensi_tidak,
			'gaji_pokok'=>$total_gaji,
		];
 		return $new_val;
 	}

 	public function getPresensiDataHarian($id,$kode_periode_penggajian_harian)
 	{
 		if(empty($id) || empty($kode_periode_penggajian_harian))
 			return null;

 		$periode = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$kode_periode_penggajian_harian]));
 		$presensi_ada = [];
 		$presensi_tidak = [];
 		$from = $periode['tgl_mulai'];
		$to = $periode['tgl_selesai'];
		while (strtotime($from)<=strtotime($to)){
			$presensi = $this->CI->model_karyawan->getListPresensiId(null, ['pre.id_karyawan'=>$id, 'pre.tanggal ' => $from]);
			$presensi = $this->CI->otherfunctions->convertResultToRowArray($presensi);
			if(!empty($presensi)){
				$presensi['hari_libur'] = null;
				$libur = $this->CI->otherfunctions->checkHariLiburActive($from);
				if(isset($libur)){
					$presensi['hari_libur'] = $libur;
				}
				$presensi_ada[] = $presensi;
			}else{
				$libur = $this->CI->otherfunctions->checkHariLiburActive($from);
				if(isset($libur)){
					$presensi_libur[] = $from;
				}else{
					$presensi_tidak[] = $from;
				}
			}
			$from = mktime(0,0,0,date("m",strtotime($from)),date("d",strtotime($from))+1,date("Y",strtotime($from)));
			$from=date("Y-m-d", $from);
		}
		$new_val = [
			'ada'=>$presensi_ada,
			'libur'=>$presensi_libur,
			'tidak'=>$presensi_tidak,
		];
 		return $new_val;
 	}
 	public function getPresensiDataPayrollHarian($id,$from,$to)
 	{
 		if(empty($id) || empty($from) || empty($to))
 			return null;
		while (strtotime($from)<=strtotime($to)){
			$presensi = $this->CI->model_karyawan->getListPresensiId(null, ['pre.id_karyawan'=>$id, 'pre.tanggal ' => $from]);
			$presensi = $this->CI->otherfunctions->convertResultToRowArray($presensi);
			if(!empty($presensi)){
				$presensi['hari_libur'] = null;
				$libur = $this->CI->otherfunctions->checkHariLiburActive($from);
				if(isset($libur)){
					$presensi['hari_libur'] = $libur;
				}
				$presensi_ada[] = $presensi;
			}else{
				$libur = $this->CI->otherfunctions->checkHariLiburActive($from);
				if(isset($libur)){
					$presensi_libur[] = $from;
				}else{
					$presensi_tidak[] = $from;
				}
			}
			$from = mktime(0,0,0,date("m",strtotime($from)),date("d",strtotime($from))+1,date("Y",strtotime($from)));
			$from=date("Y-m-d", $from);
		}
		$new_val = [
			'ada'=>$presensi_ada,
			'libur'=>$presensi_libur,
			'tidak'=>$presensi_tidak,
		];
 		return $new_val;
 	}

 	public function getTableGajiBerih($total_gaji)
 	{
 		if(empty($total_gaji)){
 			$total_gaji = 0;
 		}
 		$table = '<table class="table table-bordered table-striped table-responsive" width="100%">
 		<tr>
 		<th>Gaji Diperoleh</th>
 		<th>'.$this->getFormatMoneyUser($total_gaji).'</th>
 		</tr>';
 		$table .= '</table>';
 		return $table;
 	}

 	public function getUangMakan($getPresensiData)
 	{
 		if(empty($getPresensiData))
			 return null;
		$uangM=$this->CI->model_master->getGeneralSetting('UM')['value_decimal'];
 		$count_masuk = $getPresensiData['ada'];
 		// $count_masuk = count($getPresensiData['ada']);
 		$uang_makan = $uangM*$count_masuk;
 		return $uang_makan;
 	}
 	public function getUangMakanPayroll($count_masuk, $idkaryawan, $count_libur_lembur)
 	{
		$uang_makan = 0;
 		if(empty($count_masuk) && empty($idkaryawan))
			 return $uang_makan;
		$statusUM=$this->CI->model_karyawan->getEmployeeId($idkaryawan);
		if ($statusUM){
			if($statusUM['uang_makan'] == 1){
				$setum=$this->CI->model_master->getGeneralSetting('SET_UM')['value_int'];
				if($setum == '0'){
					$uangM=$this->CI->model_master->getGeneralSetting('UM')['value_decimal'];
					$uang_makan = ($uangM*$count_masuk)+($uangM*$count_libur_lembur);
				}else{
					if ($statusUM['gaji_pokok'] == 'matrix'){
						$uangMakanGrade=$statusUM['uang_makan_grade'];
					}else{
						$uangMakanGrade=$this->CI->model_master->getGeneralSetting('UM')['value_decimal'];
					}
					$uang_makan = ($uangMakanGrade*$count_masuk)+($uangMakanGrade*$count_libur_lembur);
				}
			}
		}
 		return $uang_makan;
 	}

 	public function getPotongannTidakMasuk($getPresensiData,$gaji_pokok,$tgl_masuk)
 	{
 		if(empty($getPresensiData) || empty($gaji_pokok))
 			return null;
		$tahunKar = $this->CI->model_master->getGeneralSetting('TPB')['value_int'];
		$karNew   = $this->CI->model_master->getGeneralSetting('PTMB')['value_int'];
		$karOld   = $this->CI->model_master->getGeneralSetting('PTML')['value_int'];
 		$tahun_masuk = $this->CI->otherfunctions->getDataExplode($tgl_masuk,'-','start');
 		if($tahun_masuk >= $tahunKar){
 			$potongan = $gaji_pokok/$karNew;
 		}else{
 			$potongan = $gaji_pokok/$karOld;
		}
 		$val_potongan = $potongan*count($getPresensiData['tidak']);
 		return $val_potongan;
 	}
 	public function getGajiPerHari($gaji_pokok,$tgl_masuk)
 	{
 		if(empty($gaji_pokok) || empty($tgl_masuk))
 			return null;
		$tahunKar = $this->CI->model_master->getGeneralSetting('TPB')['value_int'];
		$karNew   = $this->CI->model_master->getGeneralSetting('PTMB')['value_int'];
		$karOld   = $this->CI->model_master->getGeneralSetting('PTML')['value_int'];
 		$tahun_masuk = $this->CI->otherfunctions->getDataExplode($tgl_masuk,'-','start');
 		if($tahun_masuk >= $tahunKar){
 			$gaji_per_hari = $gaji_pokok/$karNew;
 		}else{
 			$gaji_per_hari = $gaji_pokok/$karOld;
		}
 		return $gaji_per_hari;
 	}
 	public function getHariKerjaKar($tgl_masuk)
 	{
 		if(empty($tgl_masuk))
 			return null;
		$tahunKar = $this->CI->model_master->getGeneralSetting('TPB')['value_int'];
		$karNew   = $this->CI->model_master->getGeneralSetting('PTMB')['value_int'];
		$karOld   = $this->CI->model_master->getGeneralSetting('PTML')['value_int'];
 		$tahun_masuk = $this->CI->otherfunctions->getDataExplode($tgl_masuk,'-','start');
 		if($tahun_masuk >= $tahunKar){
 			$hari = $karNew;
 		}else{
 			$hari = $karOld;
		}
 		return $hari;
 	}

 	// public function getIjinCuti($id,$kode_periode_penggajian,$sistem_penggajian = 'BULANAN',$from,$to)
 	public function getIjinCuti($id,$from,$to)
 	{
 		$data = [
 			'jam'=>null,
 			'hari'=>null,
 		];
		$jam = [];
		$hari = [];
		$izin = [];
		$imp = [];
		$iskd = [];
		$getijincuti = $this->CI->model_karyawan->getIzinCuti(null,['a.id_karyawan'=>$id,'a.tgl_selesai >='=>$from,'a.tgl_selesai <='=>$to]);
		foreach ($getijincuti as $d) {
			$cekMasterIzin=$this->CI->model_master->getMasterIzinJenis($d->jenis);
			if($cekMasterIzin['potong_upah']==1 && $d->validasi==1){
				$result_data = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_karyawan->getIzinCuti(null,['a.id_izin_cuti'=>$d->id_izin_cuti]));
				if($d->tgl_mulai == $d->tgl_selesai){
					$jam[] = $result_data;
				}else{
					$hari[] = $result_data;
				}
				if($d->jenis == 'IMP'){
					$imp[] = $result_data;
				}elseif($d->jenis == 'IZIN'){
					$izin[] = $result_data;
				}elseif($d->jenis == 'ISKD'){
					$iskd[] = $result_data;
				}
			}
		}
 		$data = [
 			'jam'=>$jam,
 			'hari'=>$hari,
 			'izin'=>$izin,
 			'imp'=>$imp,
 			'iskd'=>$iskd,
 		];
 		return $data;
 	}
	public function getPresensiIzinPayroll($emp,$from,$to)
	{ 
		if(empty($emp))
			return null;
		$pernwfh = $this->CI->model_master->getGeneralSetting('NONWFH')['value_int'];
		$perwfh = $this->CI->model_master->getGeneralSetting('WFH')['value_int'];
		$presensi_ada = [];
		$presensi_tidak = [];
		$presensi_libur = [];
		$dataIzinjam = [];
		$dataIzinCuti = [];
		$dataIzinhari = [];
		$dataIzinimp = [];
		$dataIzinizin = [];
		$dataIziniskd = [];
		$iskdpaid = [];
		$cutiMelahirkan = [];
		$dataIzinLain = [];
		$tanggal_terlambat = [];
		$countLibur = 0;
		$presensi_libur_lembur = [];
		$countLibur_lembur = 0;
		$countAlpa = 0;
		$countPresensi = 0;
		$countIzin = 0;
		$gajiPre = 0;
		$gajiIzin = 0;
		$terlambat = 0;
		$count_date = $this->CI->otherfunctions->getDivDate($from,$to);
		$gaji_per_hari = $this->getGajiPerHari($emp['gaji_pokok'],$emp['tgl_masuk']);
		$date_loop=$this->CI->formatter->dateLoopFull($from,$to);
		$dataPayx = $this->CI->model_presensi->getDetailPresensiForPayroll($emp['id_karyawan'],$from,$to);
		// print_r($dataPayx);
		$jumlah_data = 0;
		foreach ($dataPayx as $d) {
			$jumlah_data += 1;
			if(in_array($d->tanggal,$date_loop)){
				if($d->kode_shift == 'SSP' || $d->kode_shift == 'SSS' || $d->kode_shift == 'SSM' || $d->kode_shift == 'SSL'){
					if((!empty($d->jam_mulai) && !empty($d->jam_selesai)) && $d->kode_shift != 'SSL'){
						$presensi_ada[] = $d->tanggal;
						$countPresensi += 1;
					}elseif(empty($d->jam_mulai) && empty($d->jam_selesai) && $d->kode_shift != 'SSL'){
						if(empty($d->kode_ijin)){
							$presensi_tidak[] = $d->tanggal;
							$countAlpa += 1;
						}else{
							$presensi_ada[] = $d->tanggal;
							$countPresensi += 1;
						}
					}elseif(empty($d->jam_mulai) && empty($d->jam_selesai) && $d->kode_shift == 'SSL'){
						$presensi_libur[] = $d->tanggal;
						$countLibur += 1;
					}
					if(!empty($d->terlambat) && $d->terlambat != '0' && $d->kode_shift != 'SSL'){
						$vterlambat=$this->getTerlambatPresensi($d->terlambat);
						$terlambatx =$this->CI->formatter->convertJamtoDecimal($vterlambat);
						$terlambat +=$terlambatx;
						if($terlambatx >= 0.5){
							$tanggal_terlambat[] = $d->tanggal;
						}
					}
				}else{
					if ($emp['tgl_masuk'] <= $d->tanggal) {
						$libur =  $this->CI->otherfunctions->checkHariLiburActive($d->tanggal);
					}
					if((!empty($d->jam_mulai) && !empty($d->jam_selesai)) && ($d->jam_mulai != '00:00:00' || $d->jam_selesai != '00:00:00')){
						if(empty($d->kode_hari_libur) && !isset($libur)){
							if(empty($d->kode_ijin) || $d->kode_master_izin == 'IMP'){
								$presensi_ada[] = $d->tanggal;
								$countPresensi += 1;
							}
						}else{
							if(isset($libur)){
								$presensi_libur_lembur[] = $d->tanggal;
								$countLibur_lembur += 1;
							}
						}
					}elseif(empty($d->jam_mulai) && empty($d->jam_selesai) && empty($d->kode_hari_libur) && empty($d->kode_ijin) && empty($d->no_spl)){
						if(isset($libur)){
							$presensi_libur[] = $d->tanggal;
							$countLibur += 1;
						}else{
							$presensi_tidak[] = $d->tanggal;
							$countAlpa += 1;
						}
					}elseif((empty($d->jam_mulai) && empty($d->jam_selesai)) && empty($d->kode_hari_libur) && empty($d->kode_ijin) && empty($d->no_spl) && $d->jam_mulai == '00:00:00' && $d->jam_selesai == '00:00:00'){
						if(!isset($libur)){
							$presensi_tidak[] = $d->tanggal;
							$countAlpa += 1;
						}
					}elseif((!empty($d->jam_mulai) && !empty($d->jam_selesai)) && empty($d->kode_hari_libur) && empty($d->kode_ijin) && ($d->jam_mulai == '00:00:00' && $d->jam_selesai == '00:00:00')){
						if(!isset($libur)){
							$presensi_tidak[] = $d->tanggal;
							$countAlpa += 1;
						// }elseif($libur == 'Minggu'){
						// 	$presensi_tidak[] = $d->tanggal;
						// 	$countAlpa += 1;
						}
					}elseif ((empty($d->jam_mulai) && empty($d->jam_selesai)) && !empty($d->kode_hari_libur) && $emp['tgl_masuk'] > $d->tanggal) {
						//kondisi masuk ditengah
						if(!isset($libur)){
							$presensi_tidak[] = $d->tanggal;
							$countAlpa += 1;
						}
					}
					if(!empty($d->terlambat) && $d->terlambat != '0' && empty($d->kode_hari_libur)){
						$vterlambat=$this->getTerlambatPresensi($d->terlambat);
						$terlambatx =$this->CI->formatter->convertJamtoDecimal($vterlambat);
						if($terlambatx >= 0.5){
							$terlambat +=$terlambatx;
							$tanggal_terlambat[] = $d->tanggal;
							// print_r($d->tanggal.' - '.$d->terlambat.' - '.$vterlambat.' - '.$terlambatx);echo '<br>';
						}
					}
				}
			}
		}
		// while ($from <= $to){
		// 	$dataIzin = $this->CI->model_karyawan->getIzinCuti(null,['a.id_karyawan'=>$emp['id_karyawan'],'a.tgl_selesai >='=>$from,'a.tgl_selesai <='=>$to],'row');
		// 	// $dataIzin = $this->CI->model_karyawan->getIzinCuti(null,['a.id_karyawan'=>$emp['id_karyawan'],'a.tgl_mulai'=>$from],'row');
		// 	// $dataIzin = $this->CI->model_karyawan->getIzinCutiPay($from,['a.id_karyawan'=>$emp['id_karyawan']],'row');
		// 	if(!empty($dataIzin)){
		// 		$countIzin += 1;
		// 		if($dataIzin['validasi']==1){
		// 			$result_data = $this->CI->model_karyawan->getIzinCuti(null,['a.id_izin_cuti'=>$dataIzin['id_izin_cuti']],'row');
		// 			if($dataIzin['tgl_mulai'] == $dataIzin['tgl_selesai']){
		// 				$dataIzinjam[] = $result_data;
		// 			}else{
		// 				$dataIzinhari[] = $result_data;
		// 			}
		// 			if($dataIzin['jenis'] == 'IMP'){
		// 				$dataIzinimp[] = $result_data;
		// 			}elseif($dataIzin['jenis'] == 'IZIN'){
		// 				$dataIzinizin[] = $result_data;
		// 			}elseif($dataIzin['jenis'] == 'ISKD'){
		// 				if($dataIzin['potong_upah'] == '1'){
		// 					$dataIziniskd[] = $result_data;
		// 				}
		// 			}
		// 		}
		// 	}
		// 	$from = date('Y-m-d', strtotime($from . ' +1 day'));
		// }
		$tanggal_izin_terlambat = [];
		$izin_terlambat = 0;
		while ($from <= $to){
			// echo $from.' <br>';
			$dataIzin = $this->CI->model_karyawan->getIzinCutiPay($from,['a.id_karyawan'=>$emp['id_karyawan']],'row');
			// print_r($dataIzin);
			if(!empty($dataIzin)){
				// $countIzin += 1;
				if($dataIzin['validasi']==1){
					$dataIzinCuti[$dataIzin['kode_izin_cuti']] = $dataIzin;
					// $result_data = $this->CI->model_karyawan->getIzinCuti(null,['a.id_izin_cuti'=>$dataIzin['id_izin_cuti']],'row');
					if($dataIzin['tgl_mulai'] == $dataIzin['tgl_selesai']){
						$dataIzinjam[$dataIzin['kode_izin_cuti']] = $dataIzin;
					}else{
						$dataIzinhari[$dataIzin['kode_izin_cuti']] = $dataIzin;
					}
					if($dataIzin['jenis'] == 'IMP'){
						$dataIzinimp[$dataIzin['kode_izin_cuti']] = $dataIzin;
					}elseif($dataIzin['jenis'] == 'IZIN'){
						$dataIzinizin[$dataIzin['kode_izin_cuti']] = $dataIzin;
					}elseif($dataIzin['jenis'] == 'ISKD'){
						if($dataIzin['potong_upah'] == '1'){
							$dataIziniskd[$dataIzin['kode_izin_cuti']] = $dataIzin;
						}else{
							$iskdpaid[$dataIzin['kode_izin_cuti']] = $dataIzin;
						}
					}elseif($dataIzin['jenis'] == 'MIC201901090008'){
						$cutiMelahirkan[$dataIzin['kode_izin_cuti']] = $dataIzin;
					}else{
						if($dataIzin['hitung_terlambat'] == '1' && $dataIzin['potong_upah'] == '1' && $dataIzin['jenis'] != 'IMP'){
							$dataIzinimp[$dataIzin['kode_izin_cuti']] = $dataIzin;
							$selesai = $this->CI->formatter->convertJamtoDecimal($dataIzin['jam_selesai']);
							$mulai = $this->CI->formatter->convertJamtoDecimal($dataIzin['jam_mulai']);
							$izinterlambat = $selesai-$mulai;
							$izin_terlambat +=$izinterlambat;
							$tanggal_izin_terlambat[] = $dataIzin['tgl_mulai'];
						}else{
							if($dataIzin['potong_upah'] == '1'){
								$dataIzinLain[$dataIzin['kode_izin_cuti']] = $dataIzin;
							}
						}
					}
				}
			}
			$from = date('Y-m-d', strtotime($from . ' +1 day'));
		}
		$countIzin = count($dataIzinCuti);
		$presensiE = $countPresensi-$countIzin;
		$potAlpa = $countAlpa*$gaji_per_hari;
		$gajiFix = $emp['gaji_pokok']-$gajiIzin;
		$presensiEnd = $presensiE;
		$new_val = [
			'countLibur'=>$countLibur,
			'countAlpa'=>$countAlpa,
			'countPresensiReal'=>$presensiE,
			'countPresensi'=>$countPresensi,
			'countIzin'=>$countIzin,
			'gajiPre'=>$gajiPre,
			'gajiIzin'=>$gajiIzin,
			'potAlpa'=>$potAlpa,
			'gaji_harian'=>$gaji_per_hari,
			'gaji_kotor'=>0,//$gajiKotor,
			'gaji_bersih'=>$gajiFix,
			'ada'=>$presensi_ada,
			'libur'=>$presensi_libur,
			'tidak'=>$presensi_tidak,
			'lemburLibur'=>$presensi_libur_lembur,
			'jumlah_data'=>$jumlah_data,
			'countlemburLibur'=>$countLibur_lembur,
			'tanggal_terlambat'=> array_diff($tanggal_terlambat, $tanggal_izin_terlambat),
			'tanggal_izin_terlambat'=>$tanggal_izin_terlambat,
			'izin_terlambat'=>$izin_terlambat,
			'getIzinCuti'=> [
					'jam'=>$dataIzinjam,
					'hari'=>$dataIzinhari,
					'izin'=>$dataIzinizin,
					'imp'=>$dataIzinimp,
					'iskd'=>$dataIziniskd,
					'iskdpaid'=>$iskdpaid,
					'cutiMelahirkan'=>$cutiMelahirkan,
					'izinLain'=>$dataIzinLain,
					'terlambat'=>$terlambat-$izin_terlambat,
			],
		];
		// echo '<pre>';
		// print_r($new_val);
		return $new_val;
	}
 	public function getIjinCutiSimple($getIjinCuti,$from=null,$to=null)
 	{
 		if(empty($getIjinCuti)){
 			$getIjinCuti = [
 				'jam'=>0,
 				'hari'=>0,
 				'izin'=>0,
 				'iskd'=>0,
 				'iskdpaid'=>0,
 				'imp'=>0,
 				'izinLain'=>0,
				'cutiMelahirkan'=>0,
				'terlambat'=>0,
 			];
 		}else{
			$val_izin = [];
			$val_iskd = [];
			$val_imp = [];
			$val_iskdpaid = [];
			$val_izinLain = [];
			$val_cutiMelahirkan = [];
 			$jam = 0;
 			$hari = 0;
 			$izin = 0;
 			$iskd = 0;
 			$iskdpaid = 0;
 			$imp = 0;
 			$izinLain = 0;
 			$cutiMelahirkan = 0;
 			$terlambat = 0;
 			if(!empty($getIjinCuti['jam'])){
 				foreach ($getIjinCuti['jam'] as $key => $value) {
					$rjam = $this->CI->otherfunctions->getDivTime($value['jam_mulai'],$value['jam_selesai']);
					$ss=$this->CI->otherfunctions->getDataExplode($rjam,':','start');
					$r_Jam=(($ss > 7) ? 07 : $ss);
 					$jam += $r_Jam;
 				}
 			}
 			if(!empty($getIjinCuti['hari'])){
 				foreach ($getIjinCuti['hari'] as $hkey => $hvalue) {
					if($hvalue['tgl_mulai'] == $hvalue['tgl_selesai']){
						$hari += 1;
					}else{
						$date_loop=$this->CI->formatter->dateLoopFull($hvalue['tgl_mulai'],$hvalue['tgl_selesai']);
						// print_r($date_loop);
						$periode_endB = date('Y-m-d',strtotime('-1 month', strtotime($to)));
						$periode_start = date('Y-m-d',strtotime($from));
						$periode_end = date('Y-m-d',strtotime($to));
						foreach ($date_loop as $key => $tanggal) {
							$date = date('Y-m-d',strtotime($tanggal));
							$libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
							if (!isset($libur)) {
								if($periode_start <= $date && $date >= $periode_endB && $date <= $periode_end){
									$hari +=1;
								}
							}
						}
					}
					// print_r($date_loop);
 					// $rhari = $this->getJmlHariIjin($hvalue['tgl_mulai'],$hvalue['tgl_selesai']);
 					// $hari += $rhari;
 				}
 			}
 			if(!empty($getIjinCuti['izin'])){
 				foreach ($getIjinCuti['izin'] as $key => $value) {
					// $rizin = $this->getJmlHariIjin($value['tgl_mulai'],$value['tgl_selesai']);
					// $izin+=$rizin;
					// $val_izin[] = $value['tgl_mulai'];
					if($value['tgl_mulai'] == $value['tgl_selesai']){
						$izin += 1;
						$val_izin[] = $value['tgl_mulai'];
					}else{
						$date_loop=$this->CI->formatter->dateLoopFull($value['tgl_mulai'],$value['tgl_selesai']);
						$periode_endB = date('Y-m-d',strtotime('-1 month', strtotime($to)));
						$periode_start = date('Y-m-d',strtotime($from));
						$periode_end = date('Y-m-d',strtotime($to));
						foreach ($date_loop as $key => $tanggal) {
							$date = date('Y-m-d',strtotime($tanggal));
							$libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
							if (!isset($libur)) {
								if($periode_start <= $date && $date >= $periode_endB && $date <= $periode_end){
									$izin +=1;
									$val_izin[] = $tanggal;
								}
							}
						}
					}
 				}
 			}
 			if(!empty($getIjinCuti['iskd'])){
 				foreach ($getIjinCuti['iskd'] as $key => $jvalue) {
					// $rskd = $this->getJmlHariIjin($jvalue['tgl_mulai'],$jvalue['tgl_selesai']);
					// $iskd+=$rskd;
					// $val_iskd[] = $jvalue['tgl_mulai'];
					if($jvalue['tgl_mulai'] == $jvalue['tgl_selesai']){
						$iskd += 1;
						$val_iskd[] = $jvalue['tgl_mulai'];
					}else{
						$date_loop=$this->CI->formatter->dateLoopFull($jvalue['tgl_mulai'],$jvalue['tgl_selesai']);
						$periode_endB = date('Y-m-d',strtotime('-1 month', strtotime($to)));
						$periode_start = date('Y-m-d',strtotime($from));
						$periode_end = date('Y-m-d',strtotime($to));
						foreach ($date_loop as $key => $tanggal) {
							$date = date('Y-m-d',strtotime($tanggal));
							$libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
							if (!isset($libur)) {
								if($periode_start <= $date && $date >= $periode_endB && $date <= $periode_end){
									$iskd +=1;
									$val_iskd[] = $tanggal;
								}
							}
						}
					}
 				}
 			}
 			if(!empty($getIjinCuti['iskdpaid'])){
 				foreach ($getIjinCuti['iskdpaid'] as $key => $jvalue) {
					if($jvalue['tgl_mulai'] == $jvalue['tgl_selesai']){
						$iskdpaid += 1;
						$val_iskdpaid[] = $jvalue['tgl_mulai'];
					}else{
						$date_loop=$this->CI->formatter->dateLoopFull($jvalue['tgl_mulai'],$jvalue['tgl_selesai']);
						$periode_endB = date('Y-m-d',strtotime('-1 month', strtotime($to)));
						$periode_start = date('Y-m-d',strtotime($from));
						$periode_end = date('Y-m-d',strtotime($to));
						foreach ($date_loop as $key => $tanggal) {
							$date = date('Y-m-d',strtotime($tanggal));
							$libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
							if (!isset($libur)) {
								if($periode_start <= $date && $date >= $periode_endB && $date <= $periode_end){
									$iskdpaid +=1;
									$val_iskdpaid[] = $tanggal;
								}
							}
						}
					}
 				}
 			}
 			if(!empty($getIjinCuti['imp'])){
 				foreach ($getIjinCuti['imp'] as $key => $value) {
					if($value['tgl_mulai'] == $value['tgl_selesai']){
						$pres = $this->CI->model_presensi->getPresensiIdDate($value['id_karyawan'],$value['tgl_mulai']);
						$rimp = $this->CI->otherfunctions->getDivTime($value['jam_mulai'],$value['jam_selesai']);
						$potonganIstirahat = 0;
						if($value['jam_mulai'] <= $pres['istirahat_mulai'] && $pres['istirahat_selesai'] <= $value['jam_selesai']){
							$potonganIstirahat = 1;
						}
						$totalJam = $this->CI->formatter->convertJamtoDecimal($rimp);
						$imp += $totalJam-$potonganIstirahat;
						$val_imp[] = $value['tgl_mulai'];
						// $imp += 1;
						// $val_imp[] = $value['tgl_mulai'];
					}else{
						$date_loop=$this->CI->formatter->dateLoopFull($value['tgl_mulai'],$value['tgl_selesai']);
						$periode_endB = date('Y-m-d',strtotime('-1 month', strtotime($to)));
						$periode_start = date('Y-m-d',strtotime($from));
						$periode_end = date('Y-m-d',strtotime($to));
						foreach ($date_loop as $key => $tanggal) {
							$date = date('Y-m-d',strtotime($tanggal));
							$libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
							if (!isset($libur)) {
								if($periode_start <= $date && $date >= $periode_endB && $date <= $periode_end){
									$imp +=1;
									$val_imp[] = $tanggal;
								}
							}
						}
					}
 				}
 			}
 			if(!empty($getIjinCuti['izinLain'])){
 				foreach ($getIjinCuti['izinLain'] as $key => $value) {
					if($value['tgl_mulai'] == $value['tgl_selesai']){
						$rizinLain = $this->CI->otherfunctions->getDivTime($value['jam_mulai'],$value['jam_selesai']);
						$izinLain+=$this->CI->formatter->convertJamtoDecimal($rizinLain);
						$val_izinLain[] = $value['tgl_mulai'];
					}else{
						$date_loop=$this->CI->formatter->dateLoopFull($value['tgl_mulai'],$value['tgl_selesai']);
						$periode_endB = date('Y-m-d',strtotime('-1 month', strtotime($to)));
						$periode_start = date('Y-m-d',strtotime($from));
						$periode_end = date('Y-m-d',strtotime($to));
						foreach ($date_loop as $key => $tanggal) {
							$date = date('Y-m-d',strtotime($tanggal));
							$libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
							if (!isset($libur)) {
								if($periode_start <= $date && $date >= $periode_endB && $date <= $periode_end){
									$izinLain +=1;
									$val_izinLain[] = $tanggal;
								}
							}
						}
					}
 				}
 			}
 			if(!empty($getIjinCuti['cutiMelahirkan'])){
 				foreach ($getIjinCuti['cutiMelahirkan'] as $key => $value) {
					$date_loop=$this->CI->formatter->dateLoopFull($value['tgl_mulai'],$value['tgl_selesai']);
					$periode_endB = date('Y-m-d',strtotime('-1 month', strtotime($to)));
					$periode_start = date('Y-m-d',strtotime($from));
					$periode_end = date('Y-m-d',strtotime($to));
					foreach ($date_loop as $key => $tanggal) {
						$cutiMelahirkan +=1;
						$val_cutiMelahirkan[] = $tanggal;
					}
 				}
 			}
 			$data = [
 				'ijin_per_jam'=>$jam,
 				'ijin_per_hari'=>$hari,
 				'izin'=>$izin,
 				'imp'=>$imp,
 				'iskd'=>$iskd,
 				'izinLain'=>$izinLain,
 				'cutiMelahirkan'=>$cutiMelahirkan,
 				'val_izin'=>$val_izin,
 				'val_imp'=>$val_imp,
 				'val_iskd'=>$val_iskd,
 				'iskdpaid'=>$iskdpaid,
 				'val_iskdpaid'=>$val_iskdpaid,
 				'val_izinLain'=>$val_izinLain,
 				'val_cutiMelahirkan'=>$val_cutiMelahirkan,
				'terlambat'=>$getIjinCuti['terlambat'],
 			];
 		}
 		return $data;
 	}
 	public function getUpahIjinCuti($getIjinCutiSimple,$gaji_pokok,$tgl_masuk,$terlambat=0)
 	{
 		$data = [
 			'ijin_per_jam'=>0,
 			'ijin_per_hari'=>0,
			'izin'=>0,
			'imp'=>0,
			'iskd'=>0,
 		];
 		if(empty($getIjinCutiSimple))
 			return $data;
		$tahunKar = $this->CI->model_master->getGeneralSetting('TPB')['value_int'];
		$karNew   = $this->CI->model_master->getGeneralSetting('PTMB')['value_int'];
		$karOld   = $this->CI->model_master->getGeneralSetting('PTML')['value_int'];
 		$tahun_masuk = $this->CI->otherfunctions->getDataExplode($tgl_masuk,'-','start');
 		if($tahun_masuk >= $tahunKar){
 			$n_terlambat = (($gaji_pokok/$karNew)/7) * $getIjinCutiSimple['terlambat'];
 			$jam       = (($gaji_pokok/$karNew)/7) * $getIjinCutiSimple['ijin_per_jam'];
 			$hari      = ($gaji_pokok/$karNew) * $getIjinCutiSimple['ijin_per_hari'];
 			$imp       = (($gaji_pokok/$karNew)/7) * $getIjinCutiSimple['imp'];
 			$izin      = ($gaji_pokok/$karNew) * $getIjinCutiSimple['izin'];
 			$iskd      = ($gaji_pokok/$karNew) * $getIjinCutiSimple['iskd'];
 		}else{
 			$n_terlambat = (($gaji_pokok/$karOld)/7) * $getIjinCutiSimple['terlambat'];
 			$jam       = (($gaji_pokok/$karOld)/7) * $getIjinCutiSimple['ijin_per_jam'];
 			$hari      = ($gaji_pokok/$karOld) * $getIjinCutiSimple['ijin_per_hari'];
 			$imp       = (($gaji_pokok/$karOld)/7) * $getIjinCutiSimple['imp'];
 			$izin      = ($gaji_pokok/$karOld) * $getIjinCutiSimple['izin'];
 			$iskd      = ($gaji_pokok/$karOld) * $getIjinCutiSimple['iskd'];
		} 		
 		$data = [
 			'ijin_per_jam' =>$jam,
 			'ijin_per_hari'=>$hari,
 			'imp'          =>$imp,
 			'izin'         =>$izin,
 			'iskd'         =>$iskd,
 			'terlambat'    =>$n_terlambat,
 		];
 		return $data;
 	}
	public function getJmlHariIjin($tgl_mulai,$tgl_selesai)
	{
		if(empty($tgl_mulai) || empty($tgl_selesai))
			return null;
		$from = $tgl_mulai;
		$to = $tgl_selesai;
		$interval = 0;
		while (strtotime($from)<=strtotime($to)){			
			$interval ++;
			$from = mktime(0,0,0,date("m",strtotime($from)),date("d",strtotime($from))+1,date("Y",strtotime($from)));
			$from=date("Y-m-d", $from);
		}
		return $interval;
	}
 	public function getAngsuran($id,$kode_periode_penggajian,$sistem_penggajian = 'BULANAN')
 	{
 		$val = [
 			'nominal'=>0,
 			'angsuran_ke'=>0,
 		];
 		if(empty($id) || empty($kode_periode_penggajian))
 			return $val;
		
 		$data = $this->CI->model_master->getListPinjaman(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian],'active',$sistem_penggajian);
 		$data = $this->CI->otherfunctions->convertResultToRowArray($data);
 		$val = [
 			'nominal'=>(!empty($data['nominal']))? $data['nominal'] : 0,
 			'angsuran_ke'=>(!empty($data['angsuran_ke']))? $data['angsuran_ke'] : 0,
 		];
 		return $val;
 	}
	public function getPinjamanNew($id,$bulan=null,$tahun=null)
	{
		if(empty($id) || empty($bulan) || empty($tahun))
			return null;
		// $where = ['a.id_karyawan'=>$id,'a.bulan =>'=>$bulan,'a.tahun =>'=>$tahun,'a.status_pinjaman'=>0];
		// $where = ['a.id_karyawan'=>$id,'a.status_pinjaman'=>0];
		$where = 'a.id_karyawan ="'.$id.'" AND (a.bulan <="'.$bulan.'" OR a.tahun <="'.$tahun.'") AND a.status_pinjaman=0';
		$data = $this->CI->model_payroll->getListPinjaman($where);
		$nominal =[];
		$angsuran_kex = [];
		if(!empty($data)){
			foreach ($data as $d) {
				$ang = $this->CI->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$d->kode_pinjaman],1);
				$countAng = count($ang);
				$pattern = json_decode($d->pattern);
				$keys = ($countAng+1);
				$nominal_angsuran = $pattern->$keys;
				$nominal[$d->kode_pinjaman] = $nominal_angsuran;
				$angsuran_kex[$d->kode_pinjaman] = $keys;
			}
		}
		$datax=[
			'nominal'=>$nominal,
			'angsuran_ke'=>$angsuran_kex,
		];
		return $datax;
	}
	public function getPinjamanPayroll($id,$tanggal=null)
	{
		if(empty($id) || empty($tanggal))
			return null;
		$where = 'a.id_karyawan ="'.$id.'" AND (a.tanggal <="'.$tanggal.'") AND a.status_pinjaman=0';
		$data = $this->CI->model_payroll->getListPinjaman($where);
		// print_r($data);
		$nominal =[];
		$angsuran_kex = [];
		if(!empty($data)){
			foreach ($data as $d) {
				$ang = $this->CI->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$d->kode_pinjaman],1);
				if(!empty($ang)){
					$countAng = count($ang);
				}else{
					$countAng = 0;
				}
				$pattern = json_decode($d->pattern);
				$keys = ($countAng+1);
				$nominal_angsuran = $pattern->$keys;
				$nominal[$d->kode_pinjaman] = $nominal_angsuran;
				$angsuran_kex[$d->kode_pinjaman] = $keys;
			}
		}
		$datax=[
			'nominal'=>$nominal,
			'angsuran_ke'=>$angsuran_kex,
		];
		return $datax;
	}
 	public function getDenda($id,$kode_periode_penggajian,$sistem_penggajian = 'BULANAN')
 	{
 		$val = [
 			'nominal'=>0,
 			'angsuran_ke'=>0,
 		];
 		if(empty($id) || empty($kode_periode_penggajian))
 			return $val;
		
 		$data = $this->CI->model_payroll->getListDenda(['a.id_karyawan'=>$id,'a.kode_periode_penggajian'=>$kode_periode_penggajian],'active',$sistem_penggajian);
 		$data = $this->CI->otherfunctions->convertResultToRowArray($data);
 		$val = [
 			'nominal'=>(!empty($data['besar_angsuran']))? $data['besar_angsuran'] : 0,
 			'angsuran_ke'=>(!empty($data['angsuran_ke']))? $data['angsuran_ke'] : 0,
 		];
 		return $val;
 	}

 	public function getPresensiDetail($id,$getPresensiData)
 	{
 		$val = [
 			'alpha'=>null,
 			'ijin'=>null,
 			'skd'=>null,
 		];
 		if(empty($id) || empty($getPresensiData))
 			return $val;
 		$ijin = [];
 		$alpha = [];
 		$skd = [];
 		foreach ($getPresensiData['tidak'] as $key => $value) {
 			$ijinx = $this->CI->model_karyawan->getIzinCuti(null,['a.id_karyawan'=>$id,'a.tgl_mulai <=' => $value, 'a.tgl_selesai >=' => $value]);
 			$ijinx = $this->CI->otherfunctions->convertResultToRowArray($ijinx);
 			if(isset($ijinx)){
 				$ijin[] = $value;
 				$data_ijin = $this->CI->model_karyawan->getIzinCuti($ijinx['id_izin_cuti']);
 				$data_ijin = $this->CI->otherfunctions->convertResultToRowArray($data_ijin);
 				if(!empty($data_ijin['skd_dibayar'])){
 					$skd[] = $value;
 				}
 			}else{
 				$alpha[] = $value;
 			}
 		}
 		$val = [
 			'alpha'=>$alpha,
 			'ijin'=>$ijin,
 			'skd'=>$skd,
 		];
 		return $val;
 	}

/*====================================================================================================================================================================================*/
/*Payroll Lembur*/
/*====================================================================================================================================================================================*/

	public function cekAgendaLembur($idkar,$kode_periode)
	{
		$emp = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$idkar,'emp.kode_penggajian'=>'BULANAN'],1,true);
		$periode = $this->CI->model_master->getListPeriodeLemburDetail($kode_periode);
		$data_karyawan = null;
		if(!empty($emp)){
			$masa_kerja = $this->CI->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
			$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
			if($emp['gaji_pokok'] == 'matrix'){
				$gaji_pokok = (empty($emp['gaji_pokok_grade'])) ? 0 : $emp['gaji_pokok_grade'];
			}
			$data_karyawan = [
				'id_karyawan'=>$emp['id_karyawan'],
				'nik'=>$emp['nik'],
				'nama'=>$emp['nama'],
				'kode_jabatan'=>$emp['jabatan'],
				'kode_grade'=>$emp['grade'],
				'kode_bagian'=>$emp['kode_bagian'],
				'rekening'=>$emp['rekening'],
				'kode_loker'=>$emp['loker'],
				'tgl_masuk'=>$emp['tgl_masuk'],
				'masa_kerja'=>$masa_kerja,
				'gaji_pokok'=>$gaji_pokok,
				'status_pajak'=>$emp['status_pajak'],
			];
		}
		return $data_karyawan;
	}
	// public function cekAgendaLembur($idkar,$kode_periode)
		// {
		// $emp = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$idkar],1,true);
		// 	$periode = $this->CI->model_master->getListPeriodeLemburDetail($kode_periode);
		// 	// $data_karyawan = [];
		// 	// foreach ($periode as $p) {
		// 	// 	$id_bagian = explode(";",$p->id_bagian);
		// 	// 	$periode_master = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListPeriodeLembur(['kode_periode_lembur'=>$p->kode_periode_lembur]));
		// 		// foreach ($id_bagian as $key => $value) {
		// 		// 	$get_bagian = $this->CI->model_master->getBagianRow($value);
		// 		// 	if($emp['loker']==$p->kode_loker && $emp['kode_bagian'] == $get_bagian['kode_bagian']){
		// 			// if($emp['loker']==$p->kode_loker && $emp['kode_bagian'] == $get_bagian['kode_bagian'] && $emp['kode_penggajian'] == $periode_master['kode_master_penggajian']){
		// 			if(!empty($emp)){
		// 				$masa_kerja = $this->CI->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
		// 				$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
		// 				if($emp['gaji_pokok'] == 'matrix'){
		// 					$gaji_pokok = (empty($emp['gaji_pokok_grade'])) ? 0 : $emp['gaji_pokok_grade'];
		// 				}
		// 				$data_karyawan = [
		// 					'id_karyawan'=>$emp['id_karyawan'],
		// 					// 'kode_umk'=>$p->kode_umk,
		// 					'nik'=>$emp['nik'],
		// 					'nama'=>$emp['nama'],
		// 					'kode_jabatan'=>$emp['jabatan'],
		// 					'kode_grade'=>$emp['grade'],
		// 					// 'kode_bagian'=>$get_bagian['kode_bagian'],
		// 					'kode_bagian'=>$emp['kode_bagian'],
		// 					'rekening'=>$emp['rekening'],
		// 					'kode_loker'=>$emp['loker'],
		// 					'tgl_masuk'=>$emp['tgl_masuk'],
		// 					'masa_kerja'=>$masa_kerja,
		// 					'gaji_pokok'=>$gaji_pokok,
		// 					'status_pajak'=>$emp['status_pajak'],
		// 				];
		// 			}
		// 		// 	}
		// 		// }
		// 	// }
		// 	return $data_karyawan;
	// }

 	public function getLemburEmp($id_karyawan = null,$kode_periode = null,$tgl_mulai = null,$tgl_selesai = null)
 	{
		
		if(!empty($kode_periode)){
			$periode = $this->CI->otherfunctions->convertResultToRowArray($this->CI->model_master->getListPeriodeLembur(['kode_periode_lembur'=>$kode_periode]));
			$per_mulai = $periode['tgl_mulai'].' 00:00:00';
			$per_selesai = $periode['tgl_selesai'].' 23:59:59';
		}else{
			$per_mulai = $tgl_mulai.' 00:00:00';
			$per_selesai = $tgl_selesai.' 23:59:59';
		}
 		$data = $this->CI->model_karyawan->getLembur(null,['a.id_karyawan'=>$id_karyawan,'a.val_tgl_mulai >=' => $per_mulai, 'a.val_tgl_mulai <=' => $per_selesai,'a.validasi'=>1]);
 		return $data;
 	}

 	public function getNominalLembur($getLemburEmp,$upah,$idkar=null)
 	{
		if(!empty($idkar)){
			$emp=$this->CI->model_karyawan->getEmployeeId($idkar);
			$cekLoker = $this->CI->model_master->getLokerLike('LOK201907300007')['kode_loker'];
			if($cekLoker == $emp['loker']){
				$jamKer=$this->CI->model_master->getGeneralSetting('JKSRNDL')['value_int'];
			}else{
				$jamKer=$this->CI->model_master->getGeneralSetting('JKSB')['value_int'];
			}
		}else{
			$jamKer=$this->CI->model_master->getGeneralSetting('JKSB')['value_int'];
		}
 		$new_upah = (($upah != 0) ? ((1/$jamKer)*$upah) : 0);
 		$lembur = [];
 		foreach ($getLemburEmp as $g) {
 			$lembur[] = $this->getNominalLemburSingle($g,$new_upah);
 		}
 		return $lembur;
 	}
 	public function getNominalLemburSingle($lembur,$new_upah)
 	{
 		$jam_libur_pendek = 0;
 		$jam_biasa = 0;
 		$jam_libur = 0;
		$jam_lembur_libur_istirahat = 0;
 		$nominal_libur_pendek = 0;
 		$nominal_biasa = 0;
		$nominal_libur = 0;
		$nominal_lembur_libur_istirahat = 0;
		$ekuivalen = 0;
 		$lembur = json_decode(json_encode($lembur), True);
		$lamaPengajuan      = $this->CI->formatter->convertJamtoDecimal($lembur['val_jumlah_lembur']);
		$lamaPotongan       = $this->CI->formatter->convertJamtoDecimal($lembur['val_potong_jam']);
		$lamaLembur			= $lamaPengajuan - $lamaPotongan;
		if($lembur['jenis_lembur'] == 'LJI'){
			$LemburIstirahat=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LJI','jam_ke'=>$lamaLembur]);
			foreach ($LemburIstirahat as $li => $ist) {
				$jam_libur_pendek = $lamaLembur;
				$nominal_libur_pendek = ($new_upah*$ist->faktor_kali);
				$ekuivalen = $ist->faktor_kali;
			}
		}elseif($lembur['jenis_lembur'] == 'LJIL'){
			$LemburIstirahatLibur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LJIL','jam_ke'=>$lamaLembur]);
			foreach ($LemburIstirahatLibur as $li => $ist) {
				$jam_lembur_libur_istirahat = $lamaLembur;
				$nominal_lembur_libur_istirahat = ($new_upah*$ist->faktor_kali);
				$ekuivalen = $ist->faktor_kali;
			}
		}elseif($lembur['jenis_lembur'] == 'LHL'){
			$mTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LHL','jam_ke'=>$lamaLembur]);
			foreach ($mTarifLembur as $mtl => $mval) {
				$jam_libur = $lamaLembur;
				$nominal_libur = ($new_upah*$mval->faktor_kali);
				$ekuivalen = $mval->faktor_kali;
			}
		}elseif($lembur['jenis_lembur'] == 'LKN'){
			$nTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LKN','jam_ke'=>$lamaLembur]);
			foreach ($nTarifLembur as $ntl => $nval) {
				$jam_biasa = $lamaLembur;
				$nominal_biasa = ($new_upah*$nval->faktor_kali);
				$ekuivalen = $nval->faktor_kali;
			}
		}else{
 			$cek_hari_libur = $this->CI->otherfunctions->checkHariLiburActive($lembur['tgl_mulai']);
			if(empty($cek_hari_libur)){
				$nTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LKN','jam_ke'=>$lamaLembur]);
				foreach ($nTarifLembur as $ntl => $nval) {
					$jam_biasa = $lamaLembur;
					$nominal_biasa = ($new_upah*$nval->faktor_kali);
					$ekuivalen = $nval->faktor_kali;
				}
			}else{
				$mTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LHL','jam_ke'=>$lamaLembur]);
				foreach ($mTarifLembur as $mtl => $mval) {
					$jam_libur = $lamaLembur;
					$nominal_libur = ($new_upah*$mval->faktor_kali);
					$ekuivalen = $mval->faktor_kali;
				}
			}
		}
 		$datax = [
 			'id_karyawan'=>$lembur['id_karyawan'],
 			'tgl_mulai'=>$lembur['tgl_mulai'],
 			'tgl_selesai'=>$lembur['tgl_selesai'],
 			'potong_jam'=>$lembur['potong_jam'],
 			'jam_biasa'=>$jam_biasa,
 			'nominal_biasa'=>$nominal_biasa,
 			'jam_libur_pendek'=>$jam_libur_pendek,
 			'nominal_libur_pendek'=>$nominal_libur_pendek,
 			'jam_lembur_libur_istirahat'=>$jam_lembur_libur_istirahat,
 			'nominal_lembur_libur_istirahat'=>$nominal_lembur_libur_istirahat,
 			'jam_libur'=>$jam_libur,
			'nominal_libur'=>$nominal_libur,
			'ekuivalen'=>$ekuivalen,
 		];
 		return $datax;
	}
 	public function getReqLembur($getNominalLemburSingle,$id_karyawan)
 	{
 		$jam_libur_pendek = 0;
 		$jam_biasa = 0;
 		$jam_libur = 0;
 		$nominal_libur_pendek = 0;
 		$nominal_biasa = 0;
 		$nominal_libur = 0;
		$jam_lembur_libur_istirahat = 0;
		$nominal_lembur_libur_istirahat = 0;
		$ekuivalen = 0;
 		foreach ($getNominalLemburSingle as $key => $value) {
 			$jam_libur_pendek += $value['jam_libur_pendek'];
 			$jam_biasa += $value['jam_biasa'];
 			$jam_libur += $value['jam_libur'];
 			$jam_lembur_libur_istirahat += $value['jam_lembur_libur_istirahat'];
 			$nominal_libur_pendek += $value['nominal_libur_pendek'];
 			$nominal_biasa += $value['nominal_biasa'];
 			$nominal_libur += $value['nominal_libur'];
 			$nominal_lembur_libur_istirahat += $value['nominal_lembur_libur_istirahat'];
 			$ekuivalen += $value['ekuivalen'];
 		}
 		$datax = [
 			'id_karyawan'=>$id_karyawan,
 			'jam_libur_pendek'=>$jam_libur_pendek,
 			'nominal_libur_pendek'=>$nominal_libur_pendek,
 			'jam_biasa'=>$jam_biasa,
 			'nominal_biasa'=>$nominal_biasa,
 			'jam_libur'=>$jam_libur,
 			'nominal_libur'=>$nominal_libur,
 			'ekuivalen'=>$ekuivalen,
 			'jam_lembur_libur_istirahat'=>$jam_lembur_libur_istirahat,
 			'nominal_lembur_libur_istirahat'=>$nominal_lembur_libur_istirahat,
 		];
 		return $datax;
 	}
	public function getNominalLemburDate($idkar, $tanggal, $jenis_lembur = null, $val_jumlah_lembur = null, $val_potong_jam = null)
	{
		$emp=$this->CI->model_karyawan->getEmployeeId($idkar);
		// echo '<pre>';
		// print_r($emp);
		if($emp['gaji_pokok'] == 'matrix'){
			$gaji_pokok = (empty($emp['gaji_pokok_grade'])) ? 0 : $emp['gaji_pokok_grade'];
		}else{
			$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
		}
		$tunjangan = $this->getTUnjangan($idkar);
		$tunjangan_tetap = $this->getTunjanganNominalTetap($tunjangan);
		$upah = $gaji_pokok;//+$tunjangan_tetap;
		$cekLoker = $this->CI->model_master->getLokerLike('LOK201907300007')['kode_loker'];
		if($cekLoker == $emp['loker']){
			$jamKer=$this->CI->model_master->getGeneralSetting('JKSRNDL')['value_int'];
		}else{
			$jamKer=$this->CI->model_master->getGeneralSetting('JKSB')['value_int'];
		}
		$new_upah = (1/$jamKer)*$upah;
		// $cekDataLembur=$this->CI->model_karyawan->cekDataLemburIdDate($idkar, $tanggal);
		// $jum_lem=0;
		$nominal=0;
		// echo '<pre>';
		// print_r($gaji_pokok);
		// foreach ($cekDataLembur as $cdl) {
			$lamaPengajuan      = $this->CI->formatter->convertJamtoDecimal($val_jumlah_lembur);
			$lamaPotongan       = $this->CI->formatter->convertJamtoDecimal($val_potong_jam);
			$lamaLembur			= ($lamaPengajuan - $lamaPotongan);
			$jum_lem			= $lamaLembur;

			if($jenis_lembur == 'LJI'){
				$mTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LJI','jam_ke'=>$jum_lem]);
				foreach ($mTarifLembur as $mtl => $mval) {
					$nominal = ($new_upah*$mval->faktor_kali);
				}
			}elseif($jenis_lembur == 'LJIL'){
				$LemburIstirahatLibur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LJIL','jam_ke'=>$jum_lem]);
				foreach ($LemburIstirahatLibur as $li => $ist) {
					$nominal = ($new_upah*$ist->faktor_kali);
				}
			}elseif($jenis_lembur == 'LKN'){
				$nTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LKN','jam_ke'=>$jum_lem]);
				foreach ($nTarifLembur as $ntl => $nval) {
					$nominal = ($new_upah*$nval->faktor_kali);
				}
			}elseif($jenis_lembur == 'LHL'){
				$TarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LHL','jam_ke'=>$jum_lem]);
				foreach ($TarifLembur as $mtl => $val) {
					$nominal = ($new_upah*$val->faktor_kali);
				}
			}else{
				$cek_hari_libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
				if(empty($cek_hari_libur)){
					$nTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LKN','jam_ke'=>$jum_lem]);
					foreach ($nTarifLembur as $ntl => $nval) {
						$nominal = ($new_upah*$nval->faktor_kali);
					}
				}else{
					$TarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LHL','jam_ke'=>$jum_lem]);
					foreach ($TarifLembur as $mtl => $val) {
						$nominal = ($new_upah*$val->faktor_kali);
					}
				}
			}
		// }
		// if($jenis_lembur == 'LJI'){
		// 	$mTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LJI','jam_ke'=>$jum_lem]);
		// 	foreach ($mTarifLembur as $mtl => $mval) {
		// 		$nominal = ($new_upah*$mval->faktor_kali);
		// 	}
		// }else{
		// 	$cek_hari_libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
		// 	if(empty($cek_hari_libur)){
		// 		$nTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LKN','jam_ke'=>$jum_lem]);
		// 		foreach ($nTarifLembur as $ntl => $nval) {
		// 			$nominal = ($new_upah*$nval->faktor_kali);
		// 		}
		// 	}else{
		// 		$TarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LHL','jam_ke'=>$jum_lem]);
		// 		foreach ($TarifLembur as $mtl => $val) {
		// 			$nominal = ($new_upah*$val->faktor_kali);
		// 		}
		// 	}
		// }
		return $nominal;
	}
	public function getNominalLemburDateNew($idkar, $tanggal, $jenis_lembur = null, $val_jumlah_lembur = null, $val_potong_jam = null)
	{
		$emp=$this->CI->model_karyawan->getEmployeeId($idkar);
		if($emp['gaji_pokok'] == 'matrix'){
			$gaji_pokok = (empty($emp['gaji_pokok_grade'])) ? 0 : $emp['gaji_pokok_grade'];
		}else{
			$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
		}
		$tunjangan = $this->getTUnjangan($idkar);
		$tunjangan_tetap = $this->getTunjanganNominalTetap($tunjangan);
		$upah = $gaji_pokok;//+$tunjangan_tetap;
		$cekLoker = $this->CI->model_master->getLokerLike('LOK201907300007')['kode_loker'];
		if($cekLoker == $emp['loker']){
			$jamKer=$this->CI->model_master->getGeneralSetting('JKSRNDL')['value_int'];
		}else{
			$jamKer=$this->CI->model_master->getGeneralSetting('JKSB')['value_int'];
		}
		$new_upah = (1/$jamKer)*$upah;
		$nominal = 0;
		$ekuivalen = '';
		$lamaPengajuan      = $this->CI->formatter->convertJamtoDecimal($val_jumlah_lembur);
		$lamaPotongan       = $this->CI->formatter->convertJamtoDecimal($val_potong_jam);
		$lamaLembur			= ($lamaPengajuan - $lamaPotongan);
		$jum_lem			= $lamaLembur;
		if($jenis_lembur == 'LJI'){
			$mTarifLembur=$this->CI->model_master->getTarifLemburJenisRow(['jenis_lembur'=>'LJI','jam_ke'=>$jum_lem]);
			$nominal = ($new_upah*$mTarifLembur['faktor_kali']);
			$ekuivalen = $mTarifLembur['faktor_kali'];
		}elseif($jenis_lembur == 'LJIL'){
			$LemburIstirahatLibur=$this->CI->model_master->getTarifLemburJenisRow(['jenis_lembur'=>'LJIL','jam_ke'=>$jum_lem]);
			$nominal = ($new_upah*$LemburIstirahatLibur['faktor_kali']);
			$ekuivalen = $LemburIstirahatLibur['faktor_kali'];
		}elseif($jenis_lembur == 'LKN'){
			$nTarifLembur=$this->CI->model_master->getTarifLemburJenisRow(['jenis_lembur'=>'LKN','jam_ke'=>$jum_lem]);
			$nominal = ($new_upah*$nTarifLembur['faktor_kali']);
			$ekuivalen = $nTarifLembur['faktor_kali'];
		}elseif($jenis_lembur == 'LHL'){
			$TarifLembur=$this->CI->model_master->getTarifLemburJenisRow(['jenis_lembur'=>'LHL','jam_ke'=>$jum_lem]);
			$nominal = ($new_upah*$TarifLembur['faktor_kali']);
			$ekuivalen = $TarifLembur['faktor_kali'];
		}else{
			$cek_hari_libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
			if(empty($cek_hari_libur)){
				$nTarifLembur=$this->CI->model_master->getTarifLemburJenisRow(['jenis_lembur'=>'LKN','jam_ke'=>$jum_lem]);
				$nominal = ($new_upah*$nTarifLembur['faktor_kali']);
				$ekuivalen = $nTarifLembur['faktor_kali'];
			}else{
				$TarifLembur=$this->CI->model_master->getTarifLemburJenisRow(['jenis_lembur'=>'LHL','jam_ke'=>$jum_lem]);
				$nominal = ($new_upah*$TarifLembur['faktor_kali']);
				$ekuivalen = $TarifLembur['faktor_kali'];
			}
		}
		$data = ['nominal'=>$nominal,'ekuivalen'=>$ekuivalen];
		return $data;
	}
	public function getNominalLemburDateHarian($idkar, $tanggal, $jenis_lembur = null, $val_jumlah_lembur = null, $val_potong_jam = null)
	{
		$emp=$this->CI->model_karyawan->getEmployeeId($idkar);
		if($emp['gaji_pokok'] == 'matrix'){
			$gaji_pokok = (empty($emp['gaji_pokok_grade'])) ? 0 : $emp['gaji_pokok_grade'];
		}else{
			$gaji_pokok = (empty($emp['gaji'])) ? 0 : $emp['gaji'];
		}
		$tahunKar = $this->CI->model_master->getGeneralSetting('TPB')['value_int'];
		$karNew   = $this->CI->model_master->getGeneralSetting('PTMB')['value_int'];
		$karOld   = $this->CI->model_master->getGeneralSetting('PTML')['value_int'];
		$tahun_masuk = $this->CI->otherfunctions->getDataExplode($emp['tgl_masuk'],'-','start');
		if($tahun_masuk >= $tahunKar){
			$upah_bulanan = $gaji_pokok*$karNew;
		}else{
			$upah_bulanan = $gaji_pokok*$karOld;
		}
		$upah = $upah_bulanan;
		$cekLoker = $this->CI->model_master->getLokerLike('LOK201907300007')['kode_loker'];
		if($cekLoker == $emp['loker']){
			$jamKer=$this->CI->model_master->getGeneralSetting('JKSRNDL')['value_int'];
		}else{
			$jamKer=$this->CI->model_master->getGeneralSetting('JKSB')['value_int'];
		}
		$new_upah = (1/$jamKer)*$upah;
		$nominal=0;
		$lamaPengajuan      = $this->CI->formatter->convertJamtoDecimal($val_jumlah_lembur);
		$lamaPotongan       = $this->CI->formatter->convertJamtoDecimal($val_potong_jam);
		$lamaLembur			= ($lamaPengajuan - $lamaPotongan);
		$jum_lem			= $lamaLembur;

		if($jenis_lembur == 'LJI'){
			$mTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LJI','jam_ke'=>$jum_lem]);
			foreach ($mTarifLembur as $mtl => $mval) {
				$nominal = ($new_upah*$mval->faktor_kali);
			}
		}elseif($jenis_lembur == 'LJIL'){
			$LemburIstirahatLibur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LJIL','jam_ke'=>$jum_lem]);
			foreach ($LemburIstirahatLibur as $li => $ist) {
				$nominal = ($new_upah*$ist->faktor_kali);
			}
		}elseif($jenis_lembur == 'LKN'){
			$nTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LKN','jam_ke'=>$jum_lem]);
			foreach ($nTarifLembur as $ntl => $nval) {
				$nominal = ($new_upah*$nval->faktor_kali);
			}
		}elseif($jenis_lembur == 'LHL'){
			$TarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LHL','jam_ke'=>$jum_lem]);
			foreach ($TarifLembur as $mtl => $val) {
				$nominal = ($new_upah*$val->faktor_kali);
			}
		}else{
			$cek_hari_libur = $this->CI->otherfunctions->checkHariLiburActive($tanggal);
			if(empty($cek_hari_libur)){
				$nTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LKN','jam_ke'=>$jum_lem]);
				foreach ($nTarifLembur as $ntl => $nval) {
					$nominal = ($new_upah*$nval->faktor_kali);
				}
			}else{
				$TarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LHL','jam_ke'=>$jum_lem]);
				foreach ($TarifLembur as $mtl => $val) {
					$nominal = ($new_upah*$val->faktor_kali);
				}
			}
		}
		return $nominal;
	}

 	public function getInsentifPerId($master_insentif,$id_emp)
 	{
 		if(empty($master_insentif) || empty($id_emp))
 			return null;
 		$new_val = null;
 		$ins_id_emp = explode(";",$master_insentif['id_karyawan']);
 		foreach ($ins_id_emp as $key => $value) {
 			if($id_emp == $value){
 				$new_val = $master_insentif;
 			}
 		}
 		return $new_val;
 	}
 	public function getPendukungLain($id_karyawan,$kode_periode)
 	{
 		$datax = [];
 		$data_lain = $this->CI->model_payroll->getListDataPendukungLain(['a.id_karyawan'=>$id_karyawan,'a.kode_periode'=>$kode_periode]);
 		foreach ($data_lain as $dl) {
 			$datax[] = [
 				'kode_pen_lain'=>$dl->kode_pen_lain,
 				'id_karyawan'=>$dl->id_karyawan,
 				'nominal'=>$dl->nominal,
 				'nama'=>$dl->nama,
 				'sifat'=>$dl->sifat,
 				'hallo'=>$dl->hallo,
 				'keterangan'=>$dl->keterangan,
 				'tahun'=>$dl->tahun,
 				'kode_periode'=>$dl->kode_periode,
 				'status'=>$dl->status,
 				'create_date'=>$dl->create_date,
 				'update_date'=>$dl->update_date,
 				'create_by'=>$dl->create_by,
 				'update_by'=>$dl->update_by
 			];
 		}
 		return $datax;
 	}
 	public function getPendukungLainHarian($id_karyawan,$minggu,$bulan,$tahun)
 	{
 		$datax = [];
 		$data_lain = $this->CI->model_payroll->getListDataPendukungLain(['a.id_karyawan'=>$id_karyawan,'a.minggu'=>$minggu,'a.bulan'=>$bulan,'a.tahun'=>$tahun]);
 		foreach ($data_lain as $dl) {
 			$datax[] = [
 				'kode_pen_lain'=>$dl->kode_pen_lain,
 				'id_karyawan'=>$dl->id_karyawan,
 				'nominal'=>$dl->nominal,
 				'nama'=>$dl->nama,
 				'sifat'=>$dl->sifat,
 				'keterangan'=>$dl->keterangan,
 				'tahun'=>$dl->tahun,
 				'kode_periode'=>$dl->kode_periode,
 				'status'=>$dl->status,
 				'create_date'=>$dl->create_date,
 				'update_date'=>$dl->update_date,
 				'create_by'=>$dl->create_by,
 				'update_by'=>$dl->update_by
 			];
 		}
 		return $datax;
 	}

 	public function getNominalPendukungLain($getPendukungLain)
 	{
 		$nominal_pengurang = 0;
		$nominal_penambah = 0;
		$sifat=[];
		$nama=[];
		$nominal=[];
		$hallo=[];
		$keterangan=[];
 		foreach ($getPendukungLain as $key => $value) {
			// $val_sifat[]=$value['sifat'];
			$nama[]=$value['nama'];
			$nominal[]=$value['nominal'];
			$hallo[]=$value['hallo'];
			$keterangan[]=$value['keterangan'];
 			if($value['sifat'] == 'penambah'){
				$sifat[]=$value['sifat'];
 				$nominal_penambah += $value['nominal'];
 			}elseif($value['sifat'] == 'pengurang'){
				$sifat[]=$value['sifat'];
 				$nominal_pengurang += $value['nominal'];
 			}
 		}
 		$val = [
 			'penambah'=>$nominal_penambah,
 			'pengurang'=>$nominal_pengurang,
 			'sifat'=>$sifat,
 			'nama'=>$nama,
 			'hallo'=>$hallo,
 			'nominal'=>$nominal,
 			'keterangan'=>$keterangan
 		];
 		return $val;
 	}
    public function getDataLainView($val){
        if(empty($val))
			return null;
		$val_data=$this->CI->otherfunctions->getDataExplode($val,';','all');
        $datax='';
        $no=1;
        foreach ($val_data as $aa => $valx) {
            $datax.=$no.'. '.$valx.'<br>';
            $no++;
        }
        return $datax;
    }
    public function getDataLainNominalView($val){
        if(empty($val))
			return null;
		$val_data=$this->CI->otherfunctions->getDataExplode($val,';','all');
        $datax='';
        $no=1;
        foreach ($val_data as $aa => $valx) {
            $datax.=$no.'. '.$this->CI->formatter->getFormatMoneyUserReq($valx).'<br>';
            $no++;
        }
        return $datax;
    }
    public function getDataLainNamaNominalView($opr, $val, $nom,$excel=false){
        if(empty($val))
			return null;
		$opr_data=$this->CI->otherfunctions->getDataExplode($opr,';','all');
		$val_data=$this->CI->otherfunctions->getDataExplode($val,';','all');
		$nominal=$this->CI->otherfunctions->getDataExplode($nom,';','all');
        $datax='';
        $no=1;
        foreach ($val_data as $aa => $valx) {
			if($opr_data[$aa] == 'pengurang'){
				$operator = '(-)';
			}else{
				$operator = '(+)';
			}
            $datax.=$operator.' '.$valx.' ('.$this->CI->formatter->getFormatMoneyUserReq($nominal[$aa]).')'.(($excel)?" \n":'<br>');
            $no++;
        }
        return $datax;
    }
    public function getDataLainRekapitulasi($opr, $val, $nom){
        if(empty($val))
			return null;
		$opr_data=$this->CI->otherfunctions->getDataExplode($opr,';','all');
		$val_data=$this->CI->otherfunctions->getDataExplode($val,';','all');
		$nom_data=$this->CI->otherfunctions->getDataExplode($nom,';','all');
        $nominal_nambah=0;
        $nominal_kurang=0;
        $no=1;
        foreach ($val_data as $aa => $valx) {
			$nominal = ((isset($nom_data[$aa]) && !empty($nom_data[$aa])) ? $nom_data[$aa] : 0 );
			if($opr_data[$aa] == 'pengurang'){
				$nominal_kurang+=$nominal;
			}else{
				$nominal_nambah+=$nominal;
			}
            $no++;
        }
		$datax=[
			'penambah'=>$nominal_nambah,
			'pengurang'=>$nominal_kurang,
		];
        return $datax;
    }
    public function getNominalDataLainNama($nom){
        if(empty($nom))
			return null;
		$nominal=$this->CI->otherfunctions->getDataExplode($nom,';','all');
        $nomx=0;
        foreach ($nominal as $aa => $valx) {
            $nomx+=$valx;
        }
        return $nomx;
    }
 	// public function getNominalPendukungLain($getPendukungLain)
 	// {
 	// 	$nominal_pengurang = 0;
	// 	$nominal_penambah = 0;
	// 	$sifat='';
	// 	$nominal='';
 	// 	foreach ($getPendukungLain as $key => $value) {
	// 		$val_sifat[]=$value['sifat'];
 	// 		if($value['sifat'] == 'penambah'){
	// 			$sifat.=$value['sifat'];
	// 			$nominal.=$value['nominal'];
 	// 			$nominal_penambah += $value['nominal'];
 	// 		}elseif($value['sifat'] == 'pengurang'){
	// 			$sifat.=$value['sifat'];
	// 			$nominal.=$value['nominal'];
 	// 			$nominal_pengurang += $value['nominal'];
 	// 		}
 	// 	}
 	// 	$val = [
 	// 		'penambah'=>$nominal_penambah,
 	// 		'pengurang'=>$nominal_pengurang,
 	// 		'sifat'=>$sifat,
 	// 		'nominal'=>$nominal
 	// 	];
 	// 	return $val;
 	// }

 	public function getBagianFromPetugasPayroll($id_karyawan,$kode_loker=null)
 	{
 		if(empty($id_karyawan)){
 			// $bagian = $this->CI->model_master->getBagian(null,['a.kode_loker'=>$kode_loker]);
 			$bagian = $this->CI->model_master->getBagian(null,['g.loker'=>$kode_loker]);
 			$x_bagian = [];
 			foreach ($bagian as $b) {
 				$x_bagian[] = $b->kode_bagian;
 			}
 		}else{
 			$emp = $this->CI->model_karyawan->getEmployeeId($id_karyawan);
 			$data = $this->CI->model_master->getListPetugasPayrollWhere(['kode_petugas'=>$emp['jabatan']]);
 			$x_bagian = [];
 			foreach ($data as $d) {
 				$peserta = explode(";",$d->id_karyawan);
 				foreach ($peserta as $key => $value) {
					$bagian = $this->CI->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$value,'emp.loker'=>$kode_loker],true);
					$x_bagian[] = $bagian['kode_bagian'];
 				}
 			}
 			$x_bagian = array_values(array_unique($x_bagian));
 			// $x_peserta = [];
 			// foreach ($data as $d) {
 			// 	$peserta = explode(";",$d->kode_peserta);
 			// 	foreach ($peserta as $key => $value) {
 			// 		$x_peserta[] = $value;
 			// 	}
 			// }
 			// $x_bagian = [];
 			// foreach ($x_peserta as $key => $value) {
			// 	$bagian = $this->CI->model_master->getJabatanWhere(['a.kode_jabatan'=>$value,'emp.loker'=>$kode_loker],true);
			// 	// $bagian = $this->CI->model_master->getJabatanKodeRow($value);
 			// 	$x_bagian[] = $bagian['kode_bagian'];
 			// }
 			// $x_bagian = array_values(array_unique($x_bagian));
 		}
 		return $x_bagian;
 	}

 	public function getJabatanFromPetugasPayroll($id_karyawan)
 	{
 		if(empty($id_karyawan)){
 			return null;
 		}else{
 			$emp = $this->CI->model_karyawan->getEmployeeId($id_karyawan);
 			$data = $this->CI->model_master->getListPetugasPayrollWhere(['kode_petugas'=>$emp['jabatan']]);
 			$x_peserta = [];
 			foreach ($data as $d) {
 				$peserta = explode(";",$d->kode_peserta);
 				foreach ($peserta as $key => $value) {
 					$x_peserta[] = $value;
 				}
 			}
 			return $x_peserta;
 		}
 	}
 	public function getKaryawanFromPetugasPayroll($id_karyawan)
 	{
 		if(empty($id_karyawan)){
 			return null;
 		}else{
 			$emp = $this->CI->model_karyawan->getEmployeeId($id_karyawan);
 			$data = $this->CI->model_master->getListPetugasPayrollWhere(['kode_petugas'=>$emp['jabatan']]);
 			$x_peserta = [];
 			foreach ($data as $d) {
 				$peserta = explode(";",$d->id_karyawan);
 				foreach ($peserta as $key => $value) {
 					$x_peserta[] = $value;
 				}
 			}
 			return array_values(array_unique($x_peserta));
 		}
 	}
 	public function getKaryawanFromPetugasPPH($id_karyawan)
 	{
 		if(empty($id_karyawan)){
 			return null;
 		}else{
 			$emp = $this->CI->model_karyawan->getEmployeeId($id_karyawan);
 			$data = $this->CI->model_master->getListPetugasPPHWhere(['kode_petugas'=>$emp['jabatan'],'a.status'=>'1']);
 			$x_peserta = [];
 			foreach ($data as $d) {
 				$peserta = explode(";",$d->id_karyawan);
 				foreach ($peserta as $key => $value) {
 					$x_peserta[] = $value;
 				}
 			}
 			return array_values(array_unique($x_peserta));
 		}
 	}
	//---------------------------- get karyawan from petugas -------------------------
 	public function getJabatanFromPetugasLembur($id_karyawan)
 	{
 		if(empty($id_karyawan)){
 			return null;
 		}else{
 			$emp = $this->CI->model_karyawan->getEmployeeId($id_karyawan);
 			$data = $this->CI->model_master->getListPetugasLemburWhere(['kode_petugas'=>$emp['jabatan']]);
 			$x_peserta = [];
 			foreach ($data as $d) {
 				$peserta = explode(";",$d->kode_peserta);
 				foreach ($peserta as $key => $value) {
 					$x_peserta[] = $value;
 				}
 			}
 			return $x_peserta;
 		}
 	}
 	public function getKaryawanFromPetugasLembur($id_karyawan)
 	{
 		if(empty($id_karyawan)){
 			return null;
 		}else{
 			$emp = $this->CI->model_karyawan->getEmployeeId($id_karyawan);
 			$data = $this->CI->model_master->getListPetugasLemburWhere(['kode_petugas'=>$emp['jabatan']]);
 			$x_peserta = [];
 			foreach ($data as $d) {
 				$peserta = explode(";",$d->id_karyawan);
 				foreach ($peserta as $key => $value) {
 					$x_peserta[] = $value;
 				}
 			}
 			return array_values(array_unique($x_peserta));
 		}
 	}
	//---------------------------- end karyawan from petugas -------------------------
	public function getPotonganIstirahat($lamaLembur,$tanggalLembur,$jenisLembur=null)
	{
 		if(empty($lamaLembur) || empty($tanggalLembur))
			 return null;
		$jamAfterPotong = 0;
 		if($jenisLembur == 'LJI'){
			$mTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LJI','jam_ke'=>$lamaLembur]);
			foreach ($mTarifLembur as $mtl => $mval) {
				$jamAfterPotong=$mval->jam_potong;
			}
 		}else{
			$cek_hari_libur = $this->CI->otherfunctions->checkHariLiburActive($tanggalLembur);
			if(empty($cek_hari_libur)){
				$mTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LKN','jam_ke'=>$lamaLembur]);
				foreach ($mTarifLembur as $mtl => $mval) {
					$jamAfterPotong=$mval->jam_potong;
				}
			}else{
				$mTarifLembur=$this->CI->model_master->getTarifLemburJenis(['jenis_lembur'=>'LHL','jam_ke'=>$lamaLembur]);
				foreach ($mTarifLembur as $mtl => $mval) {
					$jamAfterPotong=$mval->jam_potong;
				}
			}
		 }
 		
		return $jamAfterPotong;
	}
 	public function cekDataBPJSEmp($emp,$dataMasterBPJS,$bulan=null,$tahun=null)
 	{
 		$data_karyawan = [];
 		// foreach ($dataMasterBPJS as $p) {
			if(!empty($emp['gaji_bpjs'])){
				$gaji_pokok = $emp['gaji_bpjs'];
			}else{
				$gaji_pokok = 0;//(empty($emp['gaji_pokok_grade'])) ? 0 : $emp['gaji_pokok_grade'];
			}
			$gaji_bpjstk =(empty($emp['gaji_bpjs_tk'])) ? 0 : $emp['gaji_bpjs_tk'];
			$jkk=$this->CI->model_master->getListBpjsRow(['inisial'=>'JKK-RS','a.status'=>1])['bpjs_karyawan'];
			$potEmpjkk=($jkk/100)*$gaji_bpjstk;
			$jkm=$this->CI->model_master->getListBpjsRow(['inisial'=>'JKM','a.status'=>1])['bpjs_karyawan'];
			$potEmpjkm=($jkm/100)*$gaji_bpjstk;
			$jht=$this->CI->model_master->getListBpjsRow(['inisial'=>'JHT','a.status'=>1])['bpjs_karyawan'];
			$potEmpjht=($jht/100)*$gaji_bpjstk;
			$jpns=$this->CI->model_master->getListBpjsRow(['inisial'=>'JPNS','a.status'=>1])['bpjs_karyawan'];
			$potEmpjpns=($jpns/100)*$gaji_bpjstk;
			$jkes=$this->CI->model_master->getListBpjsRow(['inisial'=>'JKES','a.status'=>1])['bpjs_karyawan'];
			$potEmpjkes=($jkes/100)*$gaji_pokok;
			$data_karyawan = [
				'id_karyawan'=>$emp['id_karyawan'],
				'kode_k_bpjs'=>$this->CI->codegenerator->kodebpjk(),
				'gaji_bpjs'=>$gaji_pokok,
				'gaji_bpjs_tk'=>$gaji_bpjstk,
				'jkk'=>$potEmpjkk,
				'jkm'=>$potEmpjkm,
				'jht'=>$potEmpjht,
				'jpns'=>$potEmpjpns,
				'jkes'=>$potEmpjkes,
				'bulan'=>($bulan==null)?0:$bulan,
				'tahun'=>($tahun==null)?0:$tahun,
			];
 		// }
 		return $data_karyawan;
 	}
 	public function getDataBeforeBPJSEmp($emp,$dataBPJS,$bulan,$tahun)
 	{
 		$data_karyawan = [];
 		foreach ($dataBPJS as $p) {
			$data_karyawan = [
				'id_karyawan'=>$emp['id_karyawan'],
				'kode_k_bpjs'=>$this->CI->codegenerator->kodebpjk(),
				'jkk'=>$p->jkk,
				'jkm'=>$p->jkm,
				'jht'=>$p->jht,
				'jpns'=>$p->jpns,
				'jkes'=>$p->jkes,
				'bulan'=>$bulan,
				'tahun'=>$tahun,
			];
 		}
 		return $data_karyawan;
 	}
 	public function getPerjalananDinas($id,$periode)
 	{
		$tgl_mulai = $periode['mulai'].' 00:00:00';
		$tgl_selesai = $periode['selesai'].' 23:59:59';
		$perdin = $this->CI->model_karyawan->getPerjalananDinasID(null,['a.id_karyawan'=>$id,'a.tgl_berangkat >='=>$tgl_mulai,'a.tgl_pulang <='=>$tgl_selesai]);
		$nominal_group = 0;
		$n_nominal = 0;
		foreach ($perdin as $p) {
			$nominal_group += ($p->val_nominal_bbm+$p->val_nominal_penginapan);
			$tunjangan = $this->CI->otherfunctions->getDataExplode($p->val_besar_tunjangan,';','all');
			if ($tunjangan){
				$n_nominal += array_sum($tunjangan);
			}
		}
		// $nominal_perdin = ($nominal_group+$n_nominal);
		$nominal_perdin = $n_nominal;
		return $nominal_perdin;
	}
 	public function getLembur($id,$bulan,$tahun)
 	{
		$lembur = $this->CI->model_payroll->getDataLogPayrollLembur(['a.id_karyawan'=>$id,'a.bulan'=>$bulan,'a.tahun'=>$tahun]);
		$n_nominal = 0;
		foreach ($lembur as $p) {
			$n_nominal += $p->gaji_terima;
		}
		return $n_nominal;
	}
 	public function getKodeAkunPPh($id,$bulan,$tahun)
 	{
		if(empty($id) || empty($bulan) || empty($tahun))
			return null;
		$kodeAkun = $this->CI->model_payroll->getWhereDataKodeAkunPPH21("id_karyawan='$id' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun' AND kode_akun != '6114' AND kode_akun != '090910' AND kode_akun != '090911'");
		$n_nominal = 0;
		if(!empty($kodeAkun)){
			foreach ($kodeAkun as $d) {
				$n_nominal += $d->nominal;
			}
		}
		return $n_nominal;
	}
 	public function getKodeAkunPesangon($id,$bulan,$tahun)
 	{
		if(empty($id) || empty($bulan) || empty($tahun))
			return null;
		$kodeAkun = $this->CI->model_payroll->getWhereDataKodeAkunPPH21(['id_karyawan'=>$id,'MONTH(tanggal)'=>$bulan,'YEAR(tanggal)'=>$tahun,'kode_akun'=>'6114']);
		$n_nominal = 0;
		if(!empty($kodeAkun)){
			foreach ($kodeAkun as $d) {
				$n_nominal += $d->nominal;
			}
		}
		return $n_nominal;
	}
 	public function getKodeAkunBonus($id,$bulan,$tahun)
 	{
		if(empty($id) || empty($bulan) || empty($tahun))
			return null;
		$kodeAkun = $this->CI->model_payroll->getWhereDataKodeAkunPPH21(['id_karyawan'=>$id,'MONTH(tanggal)'=>$bulan,'YEAR(tanggal)'=>$tahun,'kode_akun'=>'090910']);
		$n_nominal = 0;
		if(!empty($kodeAkun)){
			foreach ($kodeAkun as $d) {
				$n_nominal += $d->nominal;
			}
		}
		return $n_nominal;
	}
 	public function getKodeAkunTHR($id,$bulan,$tahun)
 	{
		if(empty($id) || empty($bulan) || empty($tahun))
			return null;
		$kodeAkun = $this->CI->model_payroll->getWhereDataKodeAkunPPH21(['id_karyawan'=>$id,'MONTH(tanggal)'=>$bulan,'YEAR(tanggal)'=>$tahun,'kode_akun'=>'090911']);
		$n_nominal = 0;
		if(!empty($kodeAkun)){
			foreach ($kodeAkun as $d) {
				$n_nominal += $d->nominal;
			}
		}
		return $n_nominal;
	}
	public function getKaryawanFromPayrollHarian($param, $dtroot)
	{
		if(empty($param))
			return null;
		if($dtroot['adm']['level'] != 0 || $dtroot['adm']['level'] != 1){
			$en_access_jabatan = $this->CI->payroll->getJabatanFromPetugasPayroll($dtroot['adm']['id_karyawan']);
		}
		if($param['bagian'] == 'all'){
			$empx = $this->CI->model_payroll->getEmployeeWhere(['emp.loker'=>$param['lokasi'],'emp.kode_penggajian'=>'HARIAN'],1);
		}else{
			$empx = $this->CI->model_payroll->getEmployeeWhere(['emp.loker'=>$param['lokasi'],'bag.kode_bagian'=>$param['bagian'],'emp.kode_penggajian'=>'HARIAN'],1);
		}
		$karyawan = [];
		foreach ($empx as $e) {
			if($dtroot['adm']['level'] == 0 || $dtroot['adm']['level'] == 1){
				$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],1,true);
				$karyawan[$e->id_karyawan] = $this->cekAgendaPayroll($kary);
			}else{
				if(in_array($e->jabatan,$en_access_jabatan)){
					$kary = $this->CI->model_payroll->getEmployeeWhere(['emp.id_karyawan'=>$e->id_karyawan],1,true);
					$karyawan[$e->id_karyawan] = $this->cekAgendaPayroll($kary);
				}
			}
		}
		return $karyawan;
	}
	public function getPinjaman($id,$bulan=null,$tahun=null)
	{
		if(empty($id) || empty($bulan) || empty($tahun))
			return null;
		// $where = ['a.id_karyawan'=>$id,'a.bulan ='=>$bulan,'a.tahun ='=>$tahun,'a.status_pinjaman'=>0];
		$where = ['a.id_karyawan'=>$id,'a.status_pinjaman'=>0];
		$data = $this->CI->model_payroll->getListPinjaman($where);
		$nominal =[];
		foreach ($data as $d) {
			$ang = $this->CI->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$d->kode_pinjaman],1);
			$countAng = count($ang);
			$pattern = json_decode($d->pattern);
			$keys = ($countAng+1);
			$nominal_angsuran = $pattern->$keys;
			$nominal[$d->kode_pinjaman] = $nominal_angsuran;
		}
		return $nominal;
	}
	public function getSisaPinjaman($kode_pinjaman,$nominal)
	{
		$n_ang = 0;
		$ang = $this->CI->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$kode_pinjaman],1);
		foreach ($ang as $a) {
			$n_ang +=$a->nominal;
		}
		$sisa = $nominal-$n_ang;
		return $sisa;
	}
	public function getAllSisaPinjaman($patternx, $jumlah_pinjaman, $jumlah_angsuran)
	{
		if(empty($patternx) || empty($jumlah_pinjaman) || empty($jumlah_angsuran))
			return null;
		$pattern=json_decode($patternx, TRUE);
		$datax = [];
		$jum=$jumlah_pinjaman;
		for ($i=1; $i <= $jumlah_angsuran ; $i++) {
			$jum-=$pattern[$i];
			$datax[] = $jum;
		}
		return $datax;
	}
	public function getPeriodeBulananHarian($admin)
	{		
		$periode = $this->CI->model_master->getListPeriodePenggajian(['a.create_by'=>$admin]);
		$periodeh = $this->CI->model_master->getPeriodePenggajianHarian(['a.create_by'=>$admin]);
		$data = [];
		foreach ($periode as $d) {
			$data[$d->kode_periode_penggajian]=$d->nama.' ('.$d->kode_master_penggajian.')';
		}
		foreach ($periodeh as $dx) {
			$data[$dx->kode_periode_penggajian_harian]=$dx->nama.' ('.$dx->kode_master_penggajian.')';
		}
		return $data;
	}
	public function getTanggalBulan($date)
	{
		if(empty($date))
			return null;
		$tgl = $this->CI->otherfunctions->getDataExplode($date,';','all');
		$daysx = '';
		foreach ($tgl as $key => $value) {
			$days = $this->CI->formatter->getDayMonthYears($value);
			$daysx .= $days['hari'].'/'.$days['bulan'].', ';
		}
		return $daysx;
	}
	public function getTerlambatPresensi($terlambat)
	{		
		if(empty($terlambat))
			return null;
		$terlambatx=$this->CI->formatter->convertJamtoDecimal($terlambat);
		if(!empty($terlambatx)){
			if($terlambatx >= 0.01 && $terlambatx <= 0.5){
				$vterlambat = "00:30:00";
			}elseif($terlambatx > 0.5 && $terlambatx <= 1){
				$vterlambat = "01:00:00";
			}elseif($terlambatx > 1 && $terlambatx <= 1.5){
				$vterlambat = "01:30:00";
			}elseif($terlambatx > 1.5 && $terlambatx <= 2){
				$vterlambat = "02:00:00";
			}elseif($terlambatx > 2 && $terlambatx <= 2.5){
				$vterlambat = "02:30:00";
			}elseif($terlambatx > 2.5 && $terlambatx <= 3){
				$vterlambat = "03:0:00";
			}elseif($terlambatx > 3 && $terlambatx <= 3.5){
				$vterlambat = "03:30:00";
			}elseif($terlambatx > 3.5 && $terlambatx <= 4){
				$vterlambat = "04:00:00";
			}elseif($terlambatx > 4 && $terlambatx <= 4.5){
				$vterlambat = "04:30:00";
			}elseif($terlambatx > 4.5 && $terlambatx <= 5){
				$vterlambat = "05:00:00";
			}elseif($terlambatx > 5 && $terlambatx <= 5.5){
				$vterlambat = "05:30:00";
			}elseif($terlambatx > 5.5 && $terlambatx <= 6){
				$vterlambat = "06:00:00";
			}elseif($terlambatx > 6 && $terlambatx <= 6.5){
				$vterlambat = "06:30:00";
			}elseif($terlambatx > 6.5 && $terlambatx <= 7){
				$vterlambat = "07:00:00";
			}elseif($terlambatx > 7 && $terlambatx <= 7.5){
				$vterlambat = "07:30:00";
			}elseif($terlambatx > 7.5 && $terlambatx <= 8){
				$vterlambat = "08:00:00";
			}elseif($terlambatx > 8 && $terlambatx <= 8.5){
				$vterlambat = "08:30:00";
			}elseif($terlambatx > 8.5 && $terlambatx <= 9){
				$vterlambat = "09:00:00";
			}else{
				$vterlambat = "10:00:00";
			}
		}else{
			$vterlambat = null;
		}
		return $vterlambat;
	}
	public function getBulanMasukKeryawan($tgl_masuk,$nik)
	{
		if(empty($tgl_masuk))
			return null;
		$now = date('Y-m-d');
		$bulanMasuk = $this->CI->otherfunctions->getDataExplode($tgl_masuk,'-','end');
		$masaKerjaTahun = $this->CI->otherfunctions->getMasaKerja($tgl_masuk,$now,'q')['year'];
		$statusKar = $this->CI->model_karyawan->listPerjanjianNik($nik);
		if($statusKar['status_baru'] == 'RSGN' || $statusKar['status_baru'] == 'PTSP'){
			$bulanKeluar = $this->CI->otherfunctions->getDataExplode($statusKar['tgl_berlaku_baru'],'-','end');
		}else{
			$bulanKeluar = '12';
		}
		if($masaKerjaTahun >= 1){
			$masa_kerja = 'Januari - Desember';
			$awal='01';
			$akhir=$bulanKeluar;
		}else{
			$bm = $this->CI->formatter->getNameOfMonth($bulanMasuk);
			$masa_kerja = $bm.' - Desember';
			$awal=$bulanMasuk;
			$akhir=$bulanKeluar;
		}
		$data = [
			'awal'=>$awal,
			'akhir'=>$akhir,
			'masa_kerja'=>$masa_kerja,
		];
		return $data;		
	}
	public function getTanggalJamPerdin($search,$nominal,$jarakMin,$jarak,$nominal_tambahan,$flag='add')
	{
		if(empty($search))
			return null;
		$tanggal_mulai = $this->CI->otherfunctions->getDataExplode($search['tanggal'], " - ", 'start');
		$tanggal_sampai = $this->CI->otherfunctions->getDataExplode($search['tanggal'], " - ", 'end');
		$tglx_sampai = $this->CI->otherfunctions->getDataExplode($tanggal_sampai, " ", 'start');
		$jam_sampai = $this->CI->otherfunctions->getDataExplode($tanggal_sampai, " ", 'end');
		$tanggal_brg = $this->CI->otherfunctions->getDataExplode($tanggal_mulai, " ", 'start');
		$jam_brg = $this->CI->otherfunctions->getDataExplode($tanggal_mulai, " ", 'end');
		if (strpos($search['tanggal_pulang'], ' ') !== false) {
			$tanggal_pulang = $this->CI->otherfunctions->getDataExplode($search['tanggal_pulang'], " ", 'start');
			$jam_pulang = $this->CI->otherfunctions->getDataExplode($search['tanggal_pulang'], " ", 'end');
		}else{
			$tanggal_pulang = $search['tanggal_pulang'];
			$jam_pulang = $search['jam_pulang'];
		}
		$jam_pulang = (($flag=='add') ? $jam_pulang.':00' : $jam_pulang);
		$berangkat = $this->CI->formatter->getDateFormatDb($tanggal_brg).' '.$jam_brg.':00';
		$sampai = $this->CI->formatter->getDateFormatDb($tglx_sampai).' '.$jam_sampai.':00';
		$pulang = $this->CI->formatter->getDateFormatDb($tanggal_pulang).' '.$jam_pulang;
		$selisih = $this->CI->otherfunctions->getRangeTimeDate($berangkat, $pulang, 'all');
		$selisihFromSampai = $this->CI->otherfunctions->getRangeTimeDate($sampai, $pulang, 'all');
		$tgl_berangkat=$this->CI->formatter->getDateFormatDb($tanggal_brg);
		$tgl_sampai=$this->CI->formatter->getDateFormatDb($tglx_sampai);
		$tgl_pulang=$this->CI->formatter->getDateFormatDb($tanggal_pulang);
		$jamMsk = '08:00:00';
		$jamIst = '12:00:00';
		$jamMskIst = '13:00:00';
		$jamPlg = '16:00:00';
		$jamExt = '18:00:00';
		if($tgl_sampai == $tgl_pulang){
			$umPagi = 0;
			$umSiang = 0;
			$umSore = 0;
			$umMalam = 0;
			if (strtotime($jam_sampai) <= strtotime($jamMsk)) {
				$umPagi += 1;
			}
			if (strtotime($jam_pulang) >= strtotime($jamIst)) {
				if (strtotime($jam_brg) < strtotime($jamMskIst)) {
					$umSiang += 1;
				}
			}
			if (strtotime($jam_pulang) >= strtotime($jamPlg)) {
				$umSore += 1;
			}
			if (strtotime($jam_pulang) >= strtotime($jamExt)) {
				$umMalam += 1;
			}
			$nominalPagi = (($jarak >= $jarakMin)?($umPagi*$nominal):0);
			$nominalSiang = $umSiang*$nominal;
			$nominalSore = (($jarak >= $jarakMin)?($umSore*$nominal):0);
			$nominalMalam = $umMalam*$nominal;
			$nominalTambahan = $umMalam*$nominal_tambahan;
		}else{
			$umPagi = 0;
			$umSiang = 0;
			$umSore = 0;
			$umMalam = 0;
			$tgl_mulai = $tgl_sampai;
			$tgl_selesai = $tgl_pulang;
			while ($tgl_mulai <= $tgl_selesai)
			{
				if($tgl_mulai == $tgl_sampai){
					if (strtotime($jam_sampai) <= strtotime($jamMsk)) {
						$umPagi += 1;
					}
					if (strtotime($jam_brg) < strtotime($jamMskIst)) {
						$umSiang += 1;
					}
					$umSore += 1;	
					$umMalam += 1;
				}elseif($tgl_mulai == $tgl_pulang){
					if (strtotime($jam_pulang) >= strtotime($jamIst)) {
						$umSiang += 1;
					}
					if (strtotime($jam_pulang) >= strtotime($jamPlg)) {
						$umSore += 1;
					}
					if (strtotime($jam_pulang) >= strtotime($jamExt)) {
						$umMalam += 1;
					}
					if (strtotime($jam_pulang) >= strtotime($jamMsk)) {
						$umPagi += 1;
					}
				}else{
					$umPagi += 1;	
					$umSiang += 1;	
					$umSore += 1;	
					$umMalam += 1;
				}
				$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
			}
			$nominalPagi = $umPagi*$nominal;
			$nominalSiang = $umSiang*$nominal;
			$nominalSore = $umSore*$nominal;
			$nominalMalam = $umMalam*$nominal;
			$nominalTambahan = $umMalam*$nominal_tambahan;
		}
		$uang_makan = [
			'Pagi'=>$umPagi,
			'Siang'=>$umSiang,
			'Malam'=>$umSore,
			'Lembur'=>$umMalam,
			'nPagi'=>$nominalPagi,
			'nSiang'=>$nominalSiang,
			'nMalam'=>$nominalSore,
			'nLembur'=>$nominalMalam,
			'nTambahan'=>$nominalTambahan,
			// 'nPagi'=>(($jarak >= $jarakMin)?($umPagi*$nominal):0),
			// 'nSiang'=>$umSiang*$nominal,
			// 'nMalam'=>(($jarak >= $jarakMin)?($umSore*$nominal):0),
			// 'nLembur'=>$umMalam*$nominal,
		];
		$datax = [
			'jam_berangkat'=>$jam_brg.':00',
			'jam_sampai'=>$jam_sampai.':00',
			'jam_pulang'=>$jam_pulang.':00',
			'tgl_berangkat'=>$tgl_berangkat,
			'tgl_sampai'=>$tgl_sampai,
			'tgl_pulang'=>$tgl_pulang,
			'berangkat'=>$berangkat,
			'sampai'=>$sampai,
			'pulang'=>$pulang,
			'selisih'=>$selisih,
			'selisihFromSampai'=>$selisihFromSampai,
			'uang_makan'=>$uang_makan,
		];
		return $datax;
	}
}
