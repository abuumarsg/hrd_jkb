<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

class Payroll extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7)); 
		if (isset($_SESSION['adm'])) {
			$this->admin = $_SESSION['adm']['id'];	
		}else{
			// redirect('auth');
			$this->admin = $_SESSION['emp']['id'];	
		}
		$ha = '0123456789';
	    $panjang = strlen($ha);
	    $rand = '';
	    for ($i = 0; $i < 6; $i++) {
	        $rand .= $ha[rand(0, $panjang - 1)];
	    }
	    $this->rando = $rand;		
		$dtroot['admin']=$this->model_admin->adm($this->admin);
		$nm=explode(" ", $dtroot['admin']['nama']);
		$datax['adm'] = array(
				'nama'=>$nm[0],
				'email'=>$dtroot['admin']['email'],
				'kelamin'=>$dtroot['admin']['kelamin'],
				'foto'=>$dtroot['admin']['foto'],
				'create'=>$dtroot['admin']['create_date'],
				'update'=>$dtroot['admin']['update_date'],
				'login'=>$dtroot['admin']['last_login'],
			);
		$this->dtroot=$datax;
		$this->load->dbforge();
	}
	public function index(){
		redirect('pages/dashboard');
    }
	public function data_penggajian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_admin = $this->admin;
				$sistem_penggajian = $this->input->post('sistem_penggajian');
				$data = $this->model_payroll->getDataPayroll(['a.create_by'=>$id_admin]);
				// $data = $this->model_payroll->getDataPayroll(['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>$sistem_penggajian]);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pay,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);

					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$masa_kerja = $this->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
					$datax['data'][]=[
						$d->id_pay,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						$this->formatter->getFormatMoneyUser($d->insentif),
						$this->formatter->getFormatMoneyUser($d->ritasi),
						$this->formatter->getFormatMoneyUser($d->uang_makan),
						$this->formatter->getFormatMoneyUser($d->pot_tidak_masuk),
						$this->formatter->getFormatMoneyUser($d->bpjs_jht),
						$this->formatter->getFormatMoneyUser($d->bpjs_jkk),
						$this->formatter->getFormatMoneyUser($d->bpjs_jkm),
						$this->formatter->getFormatMoneyUser($d->bpjs_pen),
						$this->formatter->getFormatMoneyUser($d->bpjs_kes),
						$this->formatter->getFormatMoneyUser($d->angsuran),
						$d->angsuran_ke,
						$this->formatter->getFormatMoneyUser($d->gaji_bersih),
						$d->no_rek,
						$this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pay');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataPayroll(['a.id_pay'=>$id]);

				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$penambah = $this->payroll->getTablePenambah($d->id_karyawan,$d->kode_periode,$id,'data');
					$pengurang = $this->payroll->getTablePengurang($id,'data');
					$total_gaji = $this->payroll->getTableGajiBerih($d->gaji_bersih);
					$datax=[
						'id_pay'=>$d->id_pay,
						'nik'=>$d->nik,
						'nama_karyawan'=>$d->nama_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'grade'=>$d->nama_grade,
						'bagian'=>$d->nama_bagian,
						'loker'=>$d->nama_loker,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'masa'=>$masa_kerja,
						'rekening'=>$d->rekening,
						'periode'=>$d->nama_periode. ' ( '.$d->nama_sistem_penggajian.' )',
						'sistem'=>$d->nama_sistem_penggajian,
						'penambah'=>$penambah,
						'pengurang'=>$pengurang,
						'total_gaji'=>$total_gaji,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						'pph'=>'',
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodebpjk();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function ready_data_payroll()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$kode_periode = $this->input->post('kode_periode');
		$data_ritasi = $this->input->post('data_ritasi');
		$data_insentif = $this->input->post('data_insentif');
		$data_bpjs = $this->input->post('data_bpjs');
		$data_pinjaman = $this->input->post('data_pinjaman');
		$data_lain = $this->input->post('data_lain');
		$metode_bpjs = $this->input->post('metode_bpjs');
		// $ins_data = $this->insertDataPenggajian($kode_periode,$data_ritasi,$data_insentif,$data_bpjs,$data_pinjaman,$data_lain,$metode_bpjs);

		if(!empty($kode_periode)){
			$data_periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
			$id_admin = $this->admin;
			$del_data = $this->model_global->deleteQuery('data_penggajian',['create_by'=>$id_admin,'kode_master_penggajian'=>$data_periode['kode_master_penggajian']]);
			if($del_data['status_data']){
				$del_tun = $this->model_global->deleteQuery('data_penggajian_tunjangan',['create_by'=>$id_admin,'kode_master_penggajian'=>$data_periode['kode_master_penggajian']]);
				if($del_tun['status_data']){

					$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
					$periode_detail = $this->model_master->getListPeriodePenggajianDetail($kode_periode);
					if($this->dtroot['adm']['level'] != 0){
						$en_access_jabatan = $this->payroll->getJabatanFromPetugasPayroll($this->dtroot['adm']['id_karyawan']);
					}
					$emp_loker = [];
					foreach ($periode_detail as $pd) {
						$emp = $this->model_payroll->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'emp.kode_penggajian'=>$periode['kode_master_penggajian']],1);
						foreach ($emp as $e) {
							if($this->dtroot['adm']['level'] == 0){
								$emp_loker[$e->id_karyawan] = $e->id_karyawan;
							}else{
								if(in_array($e->jabatan,$en_access_jabatan)){
									$emp_loker[$e->id_karyawan] = $e->id_karyawan;
								}
							}
						}
					}
					$karyawan = [];
					foreach ($emp_loker as $key => $value) {
						$emp_single = $this->model_karyawan->getEmployeeId($value);
						$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
					}

					foreach (array_values(array_filter($karyawan)) as $key => $value) {
						$gaji_pokok = $value['gaji_pokok'];
						$gaji_pokok = (empty($gaji_pokok)) ? 0 : $gaji_pokok;
						/*=== penambah ===*/
						/*insentif*/
						if(!empty($data_insentif)){
							$insentif = $this->payroll->getInsentif($value['id_karyawan']);
						}else{
							$insentif = 0;
						}
						/*ritasi*/
						if(!empty($data_ritasi)){
							$ritasi = $this->payroll->getRitasi($value['id_karyawan'],$kode_periode);
						}else{
							$ritasi = 0;
						}
						/*Presensi*/
						$presensi = $this->payroll->getPresensiData($value['id_karyawan'],$kode_periode); 										
						/*uang makan*/
						$uang_makan = $this->payroll->getUangMakan($presensi); 																			
						$uang_makan = (empty($uang_makan)) ? 0 : $uang_makan;
						/*tunjangan*/
						$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);																
						$tunjangan_nominal = $this->payroll->getTunjanganNominalPayroll($tunjangan);												/*nominal tunjangan total*/
						$tunjangan_nominal = (empty($tunjangan_nominal)) ? 0 : $tunjangan_nominal;
						$upah = $gaji_pokok+$tunjangan_nominal;
						$nominal_penambah = $upah+$insentif+$ritasi+$uang_makan;


						/*=== pengurang ===*/
						/*Potongan Tidak Masuk*/
						$pot_tidak_masuk = $this->payroll->getPotongannTidakMasuk($presensi,$value['gaji_pokok'],$value['tgl_masuk']);	
						$pot_tidak_masuk = (empty($pot_tidak_masuk)) ? 0 : $pot_tidak_masuk;
						/*bpjs*/
						if(!empty($data_bpjs)){
							if($metode_bpjs == 'nominal'){
								$bpjs = $this->payroll->getBpjs($value['id_karyawan']); 

								$bpjs_jht = (empty($bpjs['jht'])) ? 0 : $bpjs['jht'];
								$bpjs_jkk = (empty($bpjs['jkk'])) ? 0 : $bpjs['jkk'];
								$bpjs_jkm = (empty($bpjs['jkm'])) ? 0 : $bpjs['jkm'];
								$bpjs_jpns = (empty($bpjs['jpns'])) ? 0 :  $bpjs['jpns'];
								$bpjs_jkes = (empty($bpjs['jkes'])) ? 0 :  $bpjs['jkes'];

								$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
							}else{
								$bpjs_jht = $this->payroll->getBpjsBayarSendiri($upah, 'JHT');
								$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($upah, 'JKK-RS');
								$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($upah, 'JKM');
								$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($upah, 'JPNS');
								$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($upah, 'JKES');
								$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
							}
						}else{
							$bpjs_jht = 0;
							$bpjs_jkk = 0;
							$bpjs_jkm = 0;
							$bpjs_jpns = 0;
							$bpjs_jkes = 0;
							$pe_bpjs = 0;
						}
						
						/*angsuran hutang*/	
						if(!empty($data_pinjaman)){
							$angsuran = $this->payroll->getAngsuran($value['id_karyawan'],$kode_periode);
						}else{
							$angsuran = [
								'nominal'=>0,
								'angsuran_ke'=>0,
							];
						}																
						
						/*ijin Cuti*/										
						$data_ijin_cuti = $this->payroll->getIjinCuti($value['id_karyawan'],$kode_periode);
						$ijin_cuti = $this->payroll->getIjinCutiSimple($data_ijin_cuti);
						$ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$value['gaji_pokok'],$value['tgl_masuk']);
						$nominal_ijin_perjam = $ijin_nominal['ijin_per_jam'];
						/*detail presensi*/
						$detail_presensi = $this->payroll->getPresensiDetail($value['id_karyawan'],$presensi);
						$nominal_pengurang = $pe_bpjs+$pot_tidak_masuk+$angsuran['nominal']+$nominal_ijin_perjam;
						if(!empty($data_lain)){
							$data_lain = $this->payroll->getPendukungLain($value['id_karyawan'],$kode_periode);
							$nominal_data_lain = $this->payroll->getNominalPendukungLain($data_lain);
						}else{
							$nominal_data_lain = [
								'penambah'=>0,
								'pengurang'=>0
							];
						}	

						$gaji_bersih = ($nominal_penambah-$nominal_pengurang)+$nominal_data_lain['penambah']-$nominal_data_lain['pengurang'];
						$data = [
							'kode_periode'=>$kode_periode,
							'nama_periode'=>$periode['nama'],
							'tgl_mulai'=>$periode['tgl_mulai'],
							'tgl_selesai'=>$periode['tgl_selesai'],
							'kode_master_penggajian'=>$periode['kode_master_penggajian'],
							'id_karyawan'=>$value['id_karyawan'],
							'nik'=>$value['nik'],
							'nama_karyawan'=>$value['nama'],
							'kode_jabatan'=>$value['kode_jabatan'],
							'kode_grade'=>$value['kode_grade'],
							'kode_bagian'=>$value['kode_bagian'],
							'kode_loker'=>$value['kode_loker'],
							'tgl_masuk'=>$value['tgl_masuk'],
							'masa_kerja'=>$value['masa_kerja'],
							'gaji_pokok'=>$value['gaji_pokok'],
							'insentif'=>$insentif,
							'ritasi'=>$ritasi,
							'uang_makan'=>$this->otherfunctions->pembulatanDepanKoma($uang_makan),
							'pot_tidak_masuk'=>$pot_tidak_masuk,
							'bpjs_jht'=>$bpjs_jht,
							'bpjs_jkk'=>$bpjs_jkk,
							'bpjs_jkm'=>$bpjs_jkm,
							'bpjs_pen'=>$bpjs_jpns,
							'bpjs_kes'=>$bpjs_jkes,
							'angsuran'=>$angsuran['nominal'],
							'angsuran_ke'=>$angsuran['angsuran_ke'],
							'tunjangan'=>$this->otherfunctions->pembulatanDepanKoma($tunjangan_nominal),
							'gaji_bersih'=>$gaji_bersih,
							'no_rek'=>$value['rekening'],
							'meninggalkan_jam_kerja'=>$ijin_cuti['ijin_per_jam'],
							'meninggalkan_jam_kerja_n'=>$nominal_ijin_perjam,
							'alpha'=>implode(";",$detail_presensi['alpha']),
							'ijin'=>implode(";",$detail_presensi['ijin']),
							'sakit_skd'=>implode(";",$detail_presensi['skd']),
						];
						$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQuery($data,'data_penggajian');

						// $this->payroll->update_tunjangan($value,$kode_periode,$tunjangan,$this->admin);
						// $this->update_pph21($kode_periode,$data_bpjs,$metode_bpjs,$emp_loker);
					}
					// $ins_data = $this->insertDataPenggajian($kode_periode,$data_ritasi,$data_insentif,$data_bpjs,$data_pinjaman,$data_lain,$metode_bpjs);
					// if($ins_data == 'true'){
					// 	$datax = 'true';
					// }
				}
			}
			$datax = 'true';
		}else{
			$datax = 'true';
		}
		echo json_encode($datax);
	}
	// public function ready_data_payroll()
	// {
	// 	if (!$this->input->is_ajax_request()) 
	// 	   	redirect('not_found');

	// 	$kode_periode = $this->input->post('kode_periode');
	// 	$data_ritasi = $this->input->post('data_ritasi');
	// 	$data_insentif = $this->input->post('data_insentif');
	// 	$data_bpjs = $this->input->post('data_bpjs');
	// 	$data_pinjaman = $this->input->post('data_pinjaman');
	// 	$data_lain = $this->input->post('data_lain');
	// 	$metode_bpjs = $this->input->post('metode_bpjs');
	// 	$ins_data = $this->insertDataPenggajian($kode_periode,$data_ritasi,$data_insentif,$data_bpjs,$data_pinjaman,$data_lain,$metode_bpjs);

	// 	if(!empty($kode_periode)){
	// 		$data_periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
	// 		$id_admin = $this->admin;
	// 		$del_data = $this->model_global->deleteQuery('data_penggajian',['create_by'=>$id_admin,'kode_master_penggajian'=>$data_periode['kode_master_penggajian']]);
	// 		if($del_data['status_data']){
	// 			$del_tun = $this->model_global->deleteQuery('data_penggajian_tunjangan',['create_by'=>$id_admin,'kode_master_penggajian'=>$data_periode['kode_master_penggajian']]);
	// 			if($del_tun['status_data']){
	// 				$ins_data = $this->insertDataPenggajian($kode_periode,$data_ritasi,$data_insentif,$data_bpjs,$data_pinjaman,$data_lain,$metode_bpjs);
	// 				if($ins_data == 'true'){
	// 					$datax = 'true';
	// 				}
	// 			}
	// 		}
	// 		$datax = 'true';
	// 	}else{
	// 		$datax = 'true';
	// 	}
	// 	echo json_encode($datax);
	// }
	// // public function insertDataPenggajian()
	// public function insertDataPenggajian($kode_periode,$data_ritasi,$data_insentif,$data_bpjs,$data_pinjaman,$data_lain,$metode_bpjs)
	// {
	// 	// $kode_periode = "PRP201905210001";
	// 	// $data_ritasi = "data_ritasi";
	// 	// $data_insentif = "data_insentif";
	// 	// $data_bpjs = "data_bpjs";
	// 	// $data_pinjaman = "data_pinjaman";
	// 	// $data_tdk_tetap = "data_tdk_tetap";
	// 	// $data_lain = "data_lain";
	// 	// $metode_bpjs = "nominal";

	// 	if(empty($kode_periode))
	// 		redirect('not_found');

	// 	$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
	// 	$periode_detail = $this->model_master->getListPeriodePenggajianDetail($kode_periode);
	// 	if($this->dtroot['adm']['level'] != 0){
	// 		$en_access_jabatan = $this->payroll->getJabatanFromPetugasPayroll($this->dtroot['adm']['id_karyawan']);
	// 	}

	// 	$emp_loker = [];
	// 	foreach ($periode_detail as $pd) {
	// 		$emp = $this->model_karyawan->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'emp.kode_penggajian'=>$periode['kode_master_penggajian']],1);
	// 		foreach ($emp as $e) {
	// 			if($this->dtroot['adm']['level'] == 0){
	// 				$emp_loker[$e->id_karyawan] = $e->id_karyawan;
	// 			}else{
	// 				if(in_array($e->jabatan,$en_access_jabatan)){
	// 					$emp_loker[$e->id_karyawan] = $e->id_karyawan;
	// 				}
	// 			}
	// 		}
	// 	}
	// 	$karyawan = [];
	// 	foreach ($emp_loker as $key => $value) {
	// 		$emp_single = $this->model_karyawan->getEmployeeId($value);
	// 		$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
	// 	}

	// 	foreach (array_values(array_filter($karyawan)) as $key => $value) {
	// 		$gaji_pokok = $value['gaji_pokok'];
	// 		$gaji_pokok = (empty($gaji_pokok)) ? 0 : $gaji_pokok;
	// 		/*=== penambah ===*/
	// 		/*insentif*/
	// 		if(!empty($data_insentif)){
	// 			$insentif = $this->payroll->getInsentif($value['id_karyawan']);
	// 		}else{
	// 			$insentif = 0;
	// 		}
	// 		/*ritasi*/
	// 		if(!empty($data_ritasi)){
	// 			$ritasi = $this->payroll->getRitasi($value['id_karyawan'],$kode_periode);
	// 		}else{
	// 			$ritasi = 0;
	// 		}
	// 		/*Presensi*/
	// 		$presensi = $this->payroll->getPresensiData($value['id_karyawan'],$kode_periode); 										
	// 		/*uang makan*/
	// 		$uang_makan = $this->payroll->getUangMakan($presensi); 																			
	// 		$uang_makan = (empty($uang_makan)) ? 0 : $uang_makan;
	// 		/*tunjangan*/
	// 		$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);																
	// 		$tunjangan_nominal = $this->payroll->getTunjanganNominalPayroll($tunjangan);												/*nominal tunjangan total*/
	// 		$tunjangan_nominal = (empty($tunjangan_nominal)) ? 0 : $tunjangan_nominal;
	// 		$upah = $gaji_pokok+$tunjangan_nominal;
	// 		$nominal_penambah = $upah+$insentif+$ritasi+$uang_makan;


	// 		/*=== pengurang ===*/
	// 		/*Potongan Tidak Masuk*/
	// 		$pot_tidak_masuk = $this->payroll->getPotongannTidakMasuk($presensi,$value['gaji_pokok'],$value['tgl_masuk']);	
	// 		$pot_tidak_masuk = (empty($pot_tidak_masuk)) ? 0 : $pot_tidak_masuk;
	// 		/*bpjs*/
	// 		if(!empty($data_bpjs)){
	// 			if($metode_bpjs == 'nominal'){
	// 				$bpjs = $this->payroll->getBpjs($value['id_karyawan']); 

	// 				$bpjs_jht = (empty($bpjs['jht'])) ? 0 : $bpjs['jht'];
	// 				$bpjs_jkk = (empty($bpjs['jkk'])) ? 0 : $bpjs['jkk'];
	// 				$bpjs_jkm = (empty($bpjs['jkm'])) ? 0 : $bpjs['jkm'];
	// 				$bpjs_jpns = (empty($bpjs['jpns'])) ? 0 :  $bpjs['jpns'];
	// 				$bpjs_jkes = (empty($bpjs['jkes'])) ? 0 :  $bpjs['jkes'];

	// 				$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
	// 			}else{
	// 				$bpjs_jht = $this->payroll->getBpjsBayarSendiri($upah, 'JHT');
	// 				$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($upah, 'JKK-RS');
	// 				$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($upah, 'JKM');
	// 				$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($upah, 'JPNS');
	// 				$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($upah, 'JKES');
	// 				$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
	// 			}
	// 		}else{
	// 			$bpjs_jht = 0;
	// 			$bpjs_jkk = 0;
	// 			$bpjs_jkm = 0;
	// 			$bpjs_jpns = 0;
	// 			$bpjs_jkes = 0;
	// 			$pe_bpjs = 0;
	// 		}
			
	// 		/*angsuran hutang*/	
	// 		if(!empty($data_pinjaman)){
	// 			$angsuran = $this->payroll->getAngsuran($value['id_karyawan'],$kode_periode);
	// 		}else{
	// 			$angsuran = [
	// 				'nominal'=>0,
	// 				'angsuran_ke'=>0,
	// 			];
	// 		}																
			
	// 		/*ijin Cuti*/										
	// 		$data_ijin_cuti = $this->payroll->getIjinCuti($value['id_karyawan'],$kode_periode);
	// 		$ijin_cuti = $this->payroll->getIjinCutiSimple($data_ijin_cuti);
	// 		$ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$value['gaji_pokok'],$value['tgl_masuk']);
	// 		$nominal_ijin_perjam = $ijin_nominal['ijin_per_jam'];
	// 		/*detail presensi*/
	// 		$detail_presensi = $this->payroll->getPresensiDetail($value['id_karyawan'],$presensi);
	// 		$nominal_pengurang = $pe_bpjs+$pot_tidak_masuk+$angsuran['nominal']+$nominal_ijin_perjam;
	// 		if(!empty($data_lain)){
	// 			$data_lain = $this->payroll->getPendukungLain($value['id_karyawan'],$kode_periode);
	// 			$nominal_data_lain = $this->payroll->getNominalPendukungLain($data_lain);
	// 		}else{
	// 			$nominal_data_lain = [
	// 				'penambah'=>0,
	// 				'pengurang'=>0
	// 			];
	// 		}	

	// 		$gaji_bersih = ($nominal_penambah-$nominal_pengurang)+$nominal_data_lain['penambah']-$nominal_data_lain['pengurang'];
	// 		$data = [
	// 			'kode_periode'=>$kode_periode,
	// 			'nama_periode'=>$periode['nama'],
	// 			'tgl_mulai'=>$periode['tgl_mulai'],
	// 			'tgl_selesai'=>$periode['tgl_selesai'],
	// 			'kode_master_penggajian'=>$periode['kode_master_penggajian'],
	// 			'id_karyawan'=>$value['id_karyawan'],
	// 			'nik'=>$value['nik'],
	// 			'kode_jabatan'=>$value['kode_jabatan'],
	// 			'kode_grade'=>$value['kode_grade'],
	// 			'kode_bagian'=>$value['kode_bagian'],
	// 			'kode_loker'=>$value['kode_loker'],
	// 			'tgl_masuk'=>$value['tgl_masuk'],
	// 			'masa_kerja'=>$value['masa_kerja'],
	// 			'gaji_pokok'=>$value['gaji_pokok'],
	// 			'insentif'=>$insentif,
	// 			'ritasi'=>$ritasi,
	// 			'uang_makan'=>$this->otherfunctions->pembulatanDepanKoma($uang_makan),
	// 			'pot_tidak_masuk'=>$pot_tidak_masuk,
	// 			'bpjs_jht'=>$bpjs_jht,
	// 			'bpjs_jkk'=>$bpjs_jkk,
	// 			'bpjs_jkm'=>$bpjs_jkm,
	// 			'bpjs_pen'=>$bpjs_jpns,
	// 			'bpjs_kes'=>$bpjs_jkes,
	// 			'angsuran'=>$angsuran['nominal'],
	// 			'angsuran_ke'=>$angsuran['angsuran_ke'],
	// 			'tunjangan'=>$this->otherfunctions->pembulatanDepanKoma($tunjangan_nominal),
	// 			'gaji_bersih'=>$gaji_bersih,
	// 			'no_rek'=>$value['rekening'],
	// 			'meninggalkan_jam_kerja'=>$ijin_cuti['ijin_per_jam'],
	// 			'meninggalkan_jam_kerja_n'=>$nominal_ijin_perjam,
	// 			'alpha'=>implode(";",$detail_presensi['alpha']),
	// 			'ijin'=>implode(";",$detail_presensi['ijin']),
	// 			'sakit_skd'=>implode(";",$detail_presensi['skd']),
	// 		];
	// 		$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
	// 		$this->model_global->insertQuery($data,'data_penggajian');

	// 		$this->payroll->update_tunjangan($value,$kode_periode,$tunjangan,$this->admin);
	// 		$this->update_pph21($kode_periode,$data_bpjs,$metode_bpjs,$emp_loker);
	// 	}
	// 	return 'true';
	// }

	// public function update_pph21()
	public function update_pph21($kode_periode,$data_bpjs,$metode_bpjs,$emp_loker)
	{
		if(empty($kode_periode)){
			return 'false';
		}else{
			$cek_periode = $this->model_payroll->getListDataPenggajianPph(['a.kode_periode'=>$kode_periode]);
			if(!empty($cek_periode)){
				$this->model_global->deleteQuery('data_penggajian_pph',['kode_periode'=>$kode_periode]);
			}
			$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
			$periode_detail = $this->model_master->getListPeriodePenggajianDetail($kode_periode);
			// $emp_loker = [];
			// foreach ($periode_detail as $pd) {
			// 	$emp = $this->model_karyawan->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'emp.kode_penggajian'=>$periode['kode_master_penggajian']],1);
			// 	foreach ($emp as $e) {
			// 		$emp_loker[$e->id_karyawan] = $e->id_karyawan;
			// 	}
			// }
			$karyawan = [];
			foreach ($emp_loker as $key => $value) {
				$emp_single = $this->model_karyawan->getEmployeeId($value);
				$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
			}
			foreach (array_values(array_filter($karyawan)) as $key => $value) {
				$data_emp = $this->model_karyawan->getEmployeeId($value['id_karyawan']);
				$gaji_pokok = $value['gaji_pokok'];
				$gaji_pokok = (empty($gaji_pokok)) ? 0 : $gaji_pokok;
				/*tunjangan*/
				$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);
				/*nominal tunjangan total*/																
				$tunjangan_nominal = $this->payroll->getTunjanganNominalPayroll($tunjangan);
				$tunjangan_nominal = (empty($tunjangan_nominal)) ? 0 : $tunjangan_nominal;
				$upah = $gaji_pokok+$tunjangan_nominal;
				$bpjs_p_jht = $this->payroll->getBpjsBayarPerusahaan($upah, 'JHT');
				$bpjs_p_jkk = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKK-RS');
				$bpjs_p_jkm = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKM');
				$bpjs_p_jpns = $this->payroll->getBpjsBayarPerusahaan($upah, 'JPNS');
				$bpjs_p_jkes = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKES');
				/*Bruto*/
				$bruto_bulan = $upah+$bpjs_p_jkk+$bpjs_p_jkm+$bpjs_p_jkes;
				$bruto_tahun = $bruto_bulan*12;
				/*biaya jabatan -  is array*/ 
				$biaya_jabatan = $this->payroll->getBiayaJabatan($bruto_bulan);

				if(!empty($data_bpjs)){
					if($metode_bpjs == 'nominal'){
						$bpjs = $this->payroll->getBpjs($value['id_karyawan']); 

						$bpjs_jht = (empty($bpjs['jht'])) ? 0 : $bpjs['jht'];
						$bpjs_jkk = (empty($bpjs['jkk'])) ? 0 : $bpjs['jkk'];
						$bpjs_jkm = (empty($bpjs['jkm'])) ? 0 : $bpjs['jkm'];
						$bpjs_jpns = (empty($bpjs['jpns'])) ? 0 :  $bpjs['jpns'];
						$bpjs_jkes = (empty($bpjs['jkes'])) ? 0 :  $bpjs['jkes'];
					}else{
						$bpjs_jht = $this->payroll->getBpjsBayarSendiri($upah, 'JHT');
						$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($upah, 'JKK-RS');
						$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($upah, 'JKM');
						$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($upah, 'JPNS');
						$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($upah, 'JKES');
					}
				}else{
					$bpjs_jht = 0;
					$bpjs_jkk = 0;
					$bpjs_jkm = 0;
					$bpjs_jpns = 0;
					$bpjs_jkes = 0;
				}
					
				/*Iuran Pensiun*/
				$iuran_pensiun_perusahaan = $bpjs_p_jpns;
				$iuran_pensiun_pekerja = $bpjs_jpns;
				/*Jumlah Pengurang*/
				$jumlah_pengurang = $biaya_jabatan['biaya_hasil']+$bpjs_jht+$iuran_pensiun_pekerja;
				/*Netto*/
				$netto_bulan = $bruto_bulan-$jumlah_pengurang;
				$netto_tahun = $netto_bulan*12;
				/*tarif pajak*/  

				$tarif_pajak = $this->payroll->getPajakPertahun($netto_tahun,$data_emp['status_pajak']);
				$layer_pph = $this->payroll->getLayerPPH($tarif_pajak);
				$get_pph = $this->payroll->getPPHPertahun($layer_pph,$data_emp['npwp']);

				$data = [
					'kode_periode'=>$kode_periode,
					'nama_periode'=>$periode['nama'],
					'tgl_mulai'=>$periode['tgl_mulai'],
					'tgl_selesai'=>$periode['tgl_selesai'],
					'kode_master_penggajian'=>$periode['kode_master_penggajian'],
					'nik'=>$data_emp['nik'],
					'nama_karyawan'=>$data_emp['nama'],
					'kode_jabatan'=>$data_emp['jabatan'],
					'kode_bagian'=>$data_emp['kode_bagian'],
					'kode_loker'=>$data_emp['loker'],
					'kode_grade'=>$data_emp['grade'],
					'tgl_masuk'=>$data_emp['tgl_masuk'],
					'masa_kerja'=>$value['masa_kerja'],
					'status_ptkp'=>$data_emp['status_pajak'],
					'gaji_pokok'=>$value['gaji_pokok'],

					'tunjangan'=>$tunjangan_nominal,
					'bpjs_jkk_perusahaan'=>$bpjs_p_jkk,
					'bpjs_jkm_perusahaan'=>$bpjs_p_jkm,
					'bpjs_kes_perusahaan'=>$bpjs_p_jkes,
					'bruto_sebulan'=>$bruto_bulan,
					'bruto_setahun'=>$bruto_tahun,
					'biaya_jabatan'=>$biaya_jabatan['biaya_hasil'],
					'bpjs_jht_perusahaan'=>$bpjs_p_jht,
					'bpjs_jht_pekerja'=>$bpjs_jht,
					'iuran_pensiun_perusahaan'=>$iuran_pensiun_perusahaan,
					'iuran_pensiun_pekerja'=>$iuran_pensiun_pekerja,
					'jml_pengurang'=>$jumlah_pengurang,
					'netto_sebulan'=>$netto_bulan,
					'netto_setahun'=>$netto_tahun,
					'pajak_setahun'=>$tarif_pajak,
					'pph_setahun'=>$get_pph['pph_tahun'],
					'no_npwp'=>$data_emp['npwp'],
					'pph_sebulan'=>$get_pph['plus_npwp'],
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQuery($data,'data_penggajian_pph');
			}
		}
		return 'true';
	}
	
	public function send_to_log()
	{
		$usage = $this->input->post('usage');
		if($usage == 'pindah'){
			$id_admin = $this->admin;
			$total = 0;
			$data = $this->model_payroll->getDataPayrollSingle(['create_by'=>$id_admin,'kode_master_penggajian'=>'BULANAN']);
			foreach ($data as $d) {
				$new_data = $this->model_payroll->getDataPayrollSingle(['id_pay'=>$d->id_pay,'create_by'=>$id_admin]);
				$new_data = $this->otherfunctions->convertResultToRowArray($new_data);
				unset($new_data['id_pay']);
				$new_data=array_merge($new_data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->insertQuery($new_data,'log_data_penggajian');
				$total += $d->gaji_bersih;
			}
			$kode_periode = $this->otherfunctions->convertResultToRowArray($data)['kode_periode'];

			$data_tunjangan = $this->model_payroll->getDataPayrollTunjanganSingle(['kode_master_penggajian'=>$kode_periode,'kode_master_penggajian'=>'BULANAN']);
			foreach ($data_tunjangan as $dt) {
				$new_data_t = $this->model_payroll->getDataPayrollTunjanganSingle(['id_pay_t'=>$dt->id_pay_t]);
				$new_data_t = $this->otherfunctions->convertResultToRowArray($new_data_t);
				unset($new_data_t['id_pay_t']);
				$this->model_global->insertQuery($new_data_t,'log_data_penggajian_tunjangan');
			}
			$data_periode =[
				'status_gaji'=>1,
				'tgl_transfer'=>date('Y-m-d'),
				'total_transfer'=>$total,
			];
			$data_periode=array_merge($data_periode,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQuery($data_periode,'data_periode_penggajian',['kode_periode_penggajian'=>$kode_periode]);
			/* $this->db->truncate('data_penggajian'); */
			/* $this->db->truncate('data_penggajian_tunjangan'); */
			
			$this->model_global->deleteQuery('data_penggajian',['kode_periode'=>$kode_periode,'kode_master_penggajian'=>'BULANAN']);
			$this->model_global->deleteQuery('data_penggajian_tunjangan',['kode_periode_penggajian'=>$kode_periode,'kode_master_penggajian'=>'BULANAN']);
			$datax = $this->messages->allGood();
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	public function getUpatePeriode()
	{
		$periode = $this->model_master->getPeriodePenggajian(['a.status_gaji'=>0]);
		$datax = '<option></option>';
		foreach ($periode as $p) {
			$datax = '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
		}
		echo json_encode($datax);
	}

	public function data_log_penggajian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				$kode_periode = $this->input->post('kode_periode');
				if(empty($kode_periode)){
					$data = $this->model_payroll->getDataLogPayroll(['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN']);
				}else{
					$data = $this->model_payroll->getDataLogPayroll(['a.kode_periode'=>$kode_periode,'a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN']);
				}
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pay,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);

					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$masa_kerja = $this->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
					$bulan_prd = $this->formatter->getNameOfMonth(date('m', strtotime($d->tgl_selesai)));
					$tahun_prd = date('Y', strtotime($d->tgl_selesai));
					$datax['data'][]=[
						$d->id_pay,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$d->nama_periode,
						$d->nama_sistem_penggajian,
						$bulan_prd,
						$tahun_prd,
						$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						$this->formatter->getFormatMoneyUser($d->insentif),
						$this->formatter->getFormatMoneyUser($d->ritasi),
						$this->formatter->getFormatMoneyUser($d->uang_makan),
						$this->formatter->getFormatMoneyUser($d->pot_tidak_masuk),
						$this->formatter->getFormatMoneyUser($d->bpjs_jht),
						$this->formatter->getFormatMoneyUser($d->bpjs_jkk),
						$this->formatter->getFormatMoneyUser($d->bpjs_jkm),
						$this->formatter->getFormatMoneyUser($d->bpjs_pen),
						$this->formatter->getFormatMoneyUser($d->bpjs_kes),
						$this->formatter->getFormatMoneyUser($d->angsuran),
						$d->angsuran_ke,
						$this->formatter->getFormatMoneyUser($d->gaji_bersih),
						$d->no_rek,
						$this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pay');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataLogPayroll(['a.id_pay'=>$id]);

				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$penambah = $this->payroll->getTablePenambah($d->id_karyawan,$d->kode_periode,$id,'log');
					$pengurang = $this->payroll->getTablePengurang($d->id_pay,'log');
					$total_gaji = $this->payroll->getTableGajiBerih($d->gaji_bersih);
					$pph = '';
					$datax=[
						'id_pay'=>$d->id_pay,
						'nik'=>$d->nik,
						'nama_karyawan'=>$d->nama_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'grade'=>$d->nama_grade,
						'bagian'=>$d->nama_bagian,
						'loker'=>$d->nama_loker,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'masa'=>$masa_kerja,
						'rekening'=>$d->rekening,
						'periode'=>$d->nama_periode. ' ( '.$d->nama_sistem_penggajian.' )',
						'sistem'=>$d->nama_sistem_penggajian,
						'penambah'=>$penambah,
						'pengurang'=>$pengurang,
						'total_gaji'=>$total_gaji,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						'pph'=>$pph,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodebpjk();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Data Payroll Lembur--//
	public function data_penggajian_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_payroll->getDataPayrollLembur(['a.create_by'=>$this->admin,'a.kode_master_penggajian'=>'BULANAN']);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_penggajian_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);

					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$datax['data'][]=[
						$d->id_penggajian_lembur,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($d->tgl_masuk),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						$this->formatter->getFormatMoneyUser($d->upah),
						$d->jam_biasa,
						$this->formatter->getFormatMoneyUser($d->nominal_biasa),
						$d->jam_libur,
						$this->formatter->getFormatMoneyUser($d->nominal_libur),
						$d->jam_libur_pendek,
						$this->formatter->getFormatMoneyUser($d->nominal_libur_pendek),
						$this->formatter->getFormatMoneyUser($d->gaji_terima),
						$d->no_rekening,
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_penggajian_lembur');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataPayrollLembur(['a.id_penggajian_lembur'=>$id]);
				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$detail = '
						<table class="table table-bordered table-striped table-responsive" width="100%">
							<tr>
								<th></th>
								<th>Jam</th>
								<th>Nominal</th>
							</tr>
							<tr>
								<td>Lembur Hari Biasa</td>
								<td>'.$d->jam_biasa.'</td>
								<td>'.$this->formatter->getFormatMoneyUser($d->nominal_biasa).'</td>
							</tr>
							<tr>
								<td>Lembur Hari Libur</td>
								<td>'.$d->jam_libur.'</td>
								<td>'.$this->formatter->getFormatMoneyUser($d->nominal_libur).'</td>
							</tr>
							<tr>
								<td>Lembur Libur Pendek</td>
								<td>'.$d->jam_libur_pendek.'</td>
								<td>'.$this->formatter->getFormatMoneyUser($d->nominal_libur_pendek).'</td>
							</tr>
							<tr>
								<th>Total Gaji Diterima</th>
								<td></td>
								<th>'.$this->formatter->getFormatMoneyUser($d->gaji_terima).'</th>
							</tr>
						</table>';
					$datax=[
						'id_penggajian_lembur'=>$d->id_penggajian_lembur,
						'nik'=>$d->nik,
						'nama_karyawan'=>$d->nama_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'grade'=>$d->nama_grade,
						'bagian'=>$d->nama_bagian,
						'loker'=>$d->nama_loker,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'masa'=>$masa_kerja,
						'rekening'=>$d->rekening,
						'periode'=>$d->nama_periode. ' ( '.$d->nama_sistem_penggajian.' )',
						'sistem'=>$d->nama_sistem_penggajian,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						'detail_lembur'=>$detail,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}

	public function ready_data_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$kode_periode = $this->input->post('kode_periode');
		$datax = 'true';
		$id_admin = $this->admin;
		if(!empty($kode_periode)){
			/* $del_data = $this->db->truncate('data_penggajian_lembur'); */
			$data_periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode_periode]));
			$del_data = $this->model_global->deleteQuery('data_penggajian_lembur',['create_by'=>$id_admin,'kode_master_penggajian'=>$data_periode['kode_master_penggajian']]);
			if($del_data['status_data']){
				$ins_data = $this->insertDataPenggajianLembur($kode_periode);
			}
		}
		echo json_encode($datax);
	}

	public function insertDataPenggajianLembur($kode_periode)
	{
		if(empty($kode_periode))
			redirect('not_found');

		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode_periode]));
		$periode_detail = $this->model_master->getListPeriodeLemburDetail($kode_periode);
		if($this->dtroot['adm']['level'] != 0){
			$en_access_jabatan = $this->payroll->getJabatanFromPetugasPayroll($this->dtroot['adm']['id_karyawan']);
		}

		$emp_loker = [];
		foreach ($periode_detail as $p) {
			$emp = $this->model_payroll->getEmployeeWhere(['emp.loker'=>$p->kode_loker],1);
			foreach ($emp as $e) {
				if($this->dtroot['adm']['level'] == 0){
					$emp_loker[] = $e->id_karyawan;
				}else{
					if(in_array($e->jabatan,$en_access_jabatan)){
						$emp_loker[] = $e->id_karyawan;
					}
				}
			}
		}

		$karyawan = [];
		foreach ($emp_loker as $key => $value) {
			$emp_single = $this->model_karyawan->getEmployeeId($value);
			$karyawan[] = $this->payroll->cekAgendaLembur($emp_single,$kode_periode);
		}

		foreach (array_values(array_filter($karyawan)) as $key => $value) {
			$data_lembur = $this->payroll->getLemburEmp($value['id_karyawan'],$kode_periode);
			$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);
			$tunjangan_tetap = $this->payroll->getTunjanganNominalTetap($tunjangan);
			$upah = $value['gaji_pokok']+$tunjangan_tetap;
			$lembur = $this->payroll->getNominalLembur($data_lembur,$upah);
			$req_lembur = $this->payroll->getReqLembur($lembur,$value['id_karyawan']);
			
			$nominal_biasa = $this->otherfunctions->pembulatanDepanKoma($req_lembur['nominal_biasa']);
			$nominal_libur = $this->otherfunctions->pembulatanDepanKoma($req_lembur['nominal_libur']);
			$nominal_libur_pendek = $this->otherfunctions->pembulatanDepanKoma($req_lembur['nominal_libur_pendek']);
			$gaji_terima = $nominal_biasa+$nominal_libur+$nominal_libur_pendek;
			$data = [
				'kode_periode'=>$kode_periode,
				'nama_periode'=>$periode['nama'],
				'tgl_mulai'=>$periode['tgl_mulai'],
				'tgl_selesai'=>$periode['tgl_selesai'],
				'kode_master_penggajian'=>$periode['kode_master_penggajian'],
				'id_karyawan'=>$value['id_karyawan'],
				'kode_jabatan'=>$value['kode_jabatan'],
				'kode_grade'=>$value['kode_grade'],
				'kode_bagian'=>$value['kode_bagian'],
				'kode_loker'=>$value['kode_loker'],
				'tgl_masuk'=>$value['tgl_masuk'],
				'masa_kerja'=>$value['masa_kerja'],
				'gaji_pokok'=>$value['gaji_pokok'],
				'upah'=>$upah,
				'jam_biasa'=>(Int)$req_lembur['jam_biasa'],
				'nominal_biasa'=>$nominal_biasa,
				'jam_libur'=>(Int)$req_lembur['jam_libur'],
				'nominal_libur'=>$nominal_libur,
				'jam_libur_pendek'=>(Int)$req_lembur['jam_libur_pendek'],
				'nominal_libur_pendek'=>$nominal_libur_pendek,
				'gaji_terima'=>$gaji_terima,
				'no_rekening'=>$value['rekening'],
				'tgl_proses'=>date('Y-m-d h:i:s'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$this->model_global->insertQuery($data,'data_penggajian_lembur');
		}
		return 'true';
	}

	public function send_to_log_lembur()
	{
		$usage = $this->input->post('usage');
		if($usage == 'pindah'){
			$id_admin = $this->admin;
			$total = 0;
			$data = $this->model_payroll->getDataPayrollLeburSingle(['create_by'=>$id_admin,'kode_master_penggajian'=>'BULANAN']);
			foreach ($data as $d) {
				$new_data = $this->model_payroll->getDataPayrollLeburSingle(['id_penggajian_lembur'=>$d->id_penggajian_lembur]);
				$new_data = $this->otherfunctions->convertResultToRowArray($new_data);
				unset($new_data['id_penggajian_lembur']);
				$this->model_global->insertQuery($new_data,'log_data_penggajian_lembur');
				/* $kode_periode = $d->kode_periode; */
				$total += $d->gaji_terima;
			}
			$kode_periode = $this->otherfunctions->convertResultToRowArray($data)['kode_periode'];

			$data_periode =[
				'status_gaji'=>1,
				'tgl_transfer'=>date('Y-m-d'),
				'total_transfer'=>$total,
			];
			$this->model_global->updateQuery($data_periode,'data_periode_lembur',['kode_periode_lembur'=>$kode_periode]);
			$this->model_global->deleteQuery('data_penggajian_lembur',['kode_periode'=>$kode_periode]);
			/* $this->db->empty_table('data_penggajian_lembur'); */
			$datax = $this->messages->allGood();
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	public function getUpatePeriodeLembur()
	{
		$periode = $this->model_master->getListPeriodeLembur(['a.status_gaji'=>0,null,1]);
		$datax = '<option></option>';
		foreach ($periode as $p) {
			$datax = '<option value="'.$p->kode_periode_lembur.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
		}
		echo json_encode($datax);
	}

	public function data_log_penggajian_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				$kode_periode = $this->input->post('kode_periode');
				$form_filter['a.create_by'] = $id_admin;
				$form_filter['a.kode_master_penggajian'] = 'BULANAN';
				if(!empty($kode_periode)){ $form_filter['a.kode_periode'] = $kode_periode; }
				/* if(empty($kode_periode)){
					$data = $this->model_karyawan->getDataLogPayrollLembur(['a.create_by'=>$id_admin]);
				}else{
					$data = $this->model_karyawan->getDataLogPayrollLembur(['a.kode_periode'=>$kode_periode,'a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN']);
				} */
				$data = $this->model_payroll->getDataLogPayrollLembur($form_filter);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_penggajian_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);


					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$masa_kerja = $this->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
					$bulan_prd = $this->formatter->getNameOfMonth(date('m', strtotime($d->tgl_selesai)));
					$tahun_prd = date('Y', strtotime($d->tgl_selesai));
					$datax['data'][]=[
						$d->id_penggajian_lembur,
						$emp['nik'],
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($d->tgl_masuk),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$d->nama_periode,
						$d->nama_sistem_penggajian,
						$bulan_prd,
						$tahun_prd,
						$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						$this->formatter->getFormatMoneyUser($d->upah),
						$d->jam_biasa,
						$this->formatter->getFormatMoneyUser($d->nominal_biasa),
						$d->jam_libur,
						$this->formatter->getFormatMoneyUser($d->nominal_libur),
						$d->jam_libur_pendek,
						$this->formatter->getFormatMoneyUser($d->nominal_libur_pendek),
						$this->formatter->getFormatMoneyUser($d->gaji_terima),
						$d->no_rekening,
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_penggajian_lembur');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataLogPayrollLembur(['a.id_penggajian_lembur'=>$id]);
				$datax = [];
				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$detail = '
						<table class="table table-bordered table-striped table-responsive" width="100%">
							<tr>
								<th></th>
								<th>Jam</th>
								<th>Nominal</th>
							</tr>
							<tr>
								<td>Lembur Hari Biasa</td>
								<td>'.$d->jam_biasa.'</td>
								<td>'.$this->formatter->getFormatMoneyUser($d->nominal_biasa).'</td>
							</tr>
							<tr>
								<td>Lembur Hari Libur</td>
								<td>'.$d->jam_libur.'</td>
								<td>'.$this->formatter->getFormatMoneyUser($d->nominal_libur).'</td>
							</tr>
							<tr>
								<td>Lembur Libur Pendek</td>
								<td>'.$d->jam_libur_pendek.'</td>
								<td>'.$this->formatter->getFormatMoneyUser($d->nominal_libur_pendek).'</td>
							</tr>
							<tr>
								<td>Total Gaji Diterima</td>
								<td></td>
								<td>'.$this->formatter->getFormatMoneyUser($d->gaji_terima).'</td>
							</tr>
						</table>';
					$datax=[
						'id_penggajian_lembur'=>$d->id_penggajian_lembur,
						'nik'=>$emp['nik'],
						'nama_karyawan'=>$d->nama_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'grade'=>$d->nama_grade,
						'bagian'=>$d->nama_bagian,
						'loker'=>$d->nama_loker,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'masa'=>$masa_kerja,
						'rekening'=>$d->rekening,
						'periode'=>$d->nama_periode. ' ( '.$d->nama_sistem_penggajian.' )',
						'sistem'=>$d->nama_sistem_penggajian,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						'detail_lembur'=>$detail,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}

//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Master BPJS--//
	public function data_bpjs()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_payroll->getListBpjsEmp();
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_k_bpjs,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_k_bpjs,
						$d->kode_k_bpjs,
						$d->nama_karyawan,
						$this->formatter->getFormatMoneyUser($d->jht),
						$this->formatter->getFormatMoneyUser($d->jkk),
						$this->formatter->getFormatMoneyUser($d->jkm),
						$this->formatter->getFormatMoneyUser($d->jpns),
						$this->formatter->getFormatMoneyUser($d->jkes),
						'<center>'.$d->tahun.'</center>',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_k_bpjs');
				$mode = $this->input->post('mode');
				$data=$this->model_payroll->getListBpjsEmp(['a.id_k_bpjs'=>$id]);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_k_bpjs,
						'kode'=>$d->kode_k_bpjs,
						'nama'=>$d->nama_karyawan,
						'id_karyawan'=>$d->id_karyawan,
						'jht'=>$this->formatter->getFormatMoneyUser($d->jht),
						'jkk'=>$this->formatter->getFormatMoneyUser($d->jkk),
						'jkm'=>$this->formatter->getFormatMoneyUser($d->jkm),
						'jpns'=>$this->formatter->getFormatMoneyUser($d->jpns),
						'jkes'=>$this->formatter->getFormatMoneyUser($d->jkes),
						'tahun'=>$d->tahun,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update)
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodebpjk();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function edt_data_bpjs()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'id_karyawan'=>$this->input->post('karyawan'),
				'jht'=>$this->formatter->getFormatMoneyDb($this->input->post('jht')),
				'jkk'=>$this->formatter->getFormatMoneyDb($this->input->post('jkk')),
				'jkm'=>$this->formatter->getFormatMoneyDb($this->input->post('jkm')),
				'jpns'=>$this->formatter->getFormatMoneyDb($this->input->post('jpns')),
				'jkes'=>$this->formatter->getFormatMoneyDb($this->input->post('jkes')),
				'tahun'=>$this->input->post('tahun'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_bpjs',['id_k_bpjs'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_data_bpjs(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data = [
				'kode_k_bpjs'=>$kode,
				'id_karyawan'=>$this->input->post('karyawan'),
				'jht'=>$this->formatter->getFormatMoneyDb($this->input->post('jht')),
				'jkk'=>$this->formatter->getFormatMoneyDb($this->input->post('jkk')),
				'jkm'=>$this->formatter->getFormatMoneyDb($this->input->post('jkm')),
				'jpns'=>$this->formatter->getFormatMoneyDb($this->input->post('jpns')),
				'jkes'=>$this->formatter->getFormatMoneyDb($this->input->post('jkes')),
				'tahun'=>$this->input->post('tahun'),
			];
			$cek_tahun = $this->model_payroll->getListBpjsEmp(['a.id_karyawan'=>$this->input->post('karyawan'),'tahun'=>$this->input->post('tahun')]);
			if(empty($cek_tahun)){
				$cek_data = $this->model_payroll->getListBpjsEmp(['a.id_karyawan'=>$this->input->post('karyawan')]);
				if(empty($cek_data)){
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQueryCC($data,'data_bpjs',$this->model_payroll->checkBpjsEmpCode($kode));
				}else{
					$data_status = ['status'=>0];
					$datax_status = $this->model_global->updateQuery($data_status,'data_bpjs',['id_karyawan'=>$this->input->post('karyawan')]);
					if($datax_status['status_data']){
						$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
						$datax = $this->model_global->insertQueryCC($data,'data_bpjs',$this->model_payroll->checkBpjsEmpCode($kode));
					}else{
						$datax=$this->messages->notValidParam();
					}
				}
			}else{
				$datax=$this->messages->customFailure('data sudah ada!');
			}
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function import_data_bpjs()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			$sheet[0]=[
				'range_huruf'=>0,
				'row'=>2,
				'table'=>'data_bpjs',
				'column_code'=>null,
				'usage'=>'import_data_bpjs',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'column'=>[
					0=>'nik',1=>'jht',2=>'jkk',3=>'jkm',4=>'jpns',5=>'jkes',6=>'tahun'
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}

//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Data Insetif--//
	public function data_insentif()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data_selet_ins = $this->model_master->getListInsentif();
				$emp_ins = [];
				foreach ($data_selet_ins as $si) {
					$emp_ins[] = $si->id_karyawan; 
				}
				$emp_ins = array_values(array_unique(explode(";",implode(";",$emp_ins))));
				$emp_insx = [];
				foreach ($emp_ins as $ekey => $evalue) {
					$data_empx = $this->model_karyawan->getEmployeeId($evalue);
					$emp_insx[$evalue] = $data_empx['nama'];
				}
				natcasesort($emp_insx);
				$data = $this->model_payroll->getListBpjsEmp();
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($emp_insx as $ik => $iv) {
					$id_karyawan = $ik;
					$var=[
						'id'=>$id_karyawan,
						'create'=>null,
						'update'=>null,
						'access'=>$access,
						'status'=>null,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$data_emp = $this->model_karyawan->getEmployeeId($id_karyawan);
					$get_insentif = '<table>';
					$total_ins = 0;
					$enc_get_ins = [];
					foreach ($data_selet_ins as $si) {
						$send_ins = $this->otherfunctions->convertResultToRowArray($this->model_master->getInsentifWhere(['id_insentif'=>$si->id_insentif]));
						$get_ins = $this->payroll->getInsentifPerId($send_ins,$id_karyawan);
						if(!empty($get_ins)){
							$get_insentif .= '<tr><td> '.$get_ins['nama'].' </td><td> '.$get_ins['tahun'].' </td><td> '.$this->formatter->getFormatMoneyUser($get_ins['nominal']).' </td></tr>';
							$total_ins += $get_ins['nominal'];
							$enc_get_ins[] = $get_ins;
						}
					}
					$encr_get_insentif = $this->codegenerator->encryptChar($enc_get_ins);
					$get_insentif .= '</table>';
					$box_enc = '<input type="hidden" id="enc_ins_'.$id_karyawan.'" value="'.$encr_get_insentif.'">';
					$datax['data'][]=[
						$id_karyawan,
						$data_emp['nik'].$box_enc,
						$data_emp['nama'],
						$data_emp['nama_jabatan'],
						$data_emp['bagian'],
						$data_emp['nama_loker'],
						$data_emp['nama_grade'],
						$get_insentif,
						$this->formatter->getFormatMoneyUser($total_ins),
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id_karyawan = $this->input->post('id_karyawan');
				$mode = $this->input->post('mode');
				$insentif = $this->codegenerator->decryptChar($this->input->post('insentif'));
				$total_ins = 0;
				$no = 1;
				$get_insentif = '';
				if ($mode == 'view'){
					foreach ($insentif as $key => $get_ins) {
						$total_ins += $get_ins['nominal'];
						$get_insentif .= '<tr><td> '.$no.' </td><td> '.$get_ins['kode_insentif'].' </td><td> '.$get_ins['nama'].' </td><td> '.$this->formatter->getFormatMoneyUser($get_ins['nominal']).' </td><td> '.$get_ins['tahun'].' </td></tr>';
						$no++;
					}
				}elseif ($mode == 'edit'){
					foreach ($insentif as $key => $get_ins) {
						$total_ins += $get_ins['nominal'];
						$get_insentif .= $get_ins['id_insentif'].';';
						$no++;
					}
					$get_insentif = array_values(array_filter(explode(";",$get_insentif)));
				}
				$data_emp = $this->model_karyawan->getEmployeeId($id_karyawan);
				$datax=[
					'id'=>$data_emp['id_karyawan'],
					'nik'=>$data_emp['nik'],
					'nama'=>$data_emp['nama'],
					'jabatan'=>$data_emp['nama_jabatan'],
					'bagian'=>$data_emp['bagian'],
					'loker'=>$data_emp['nama_loker'],
					'grade'=>$data_emp['nama_grade'],
					'nominal'=>$this->formatter->getFormatMoneyUser($total_ins),
					'detail'=>$get_insentif,
				];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_insentif(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$karyawan = $this->input->post('karyawan');
		$insentif = $this->input->post('insentif');
		if(!empty($karyawan) || !empty($insentif)){
			$c_karyawan = $this->otherfunctions->checkValueAll($karyawan);
			if($c_karyawan){
				$empx = $this->model_karyawan->getEmployeeAllActive();
				$emp = [];
				foreach ($empx as $e) {
					$emp[] = $e->id_karyawan;
				}
			}else{
				$emp = $karyawan;
			}

			$c_insentif = $this->otherfunctions->checkValueAll($insentif);
			if($c_insentif){
				$insx = $this->model_master->getListInsentif();
				$ins = [];
				foreach ($insx as $i) {
					$ins[] = $i->id_insentif;
				}
			}else{
				$ins = $insentif;
			}
			foreach ($emp as $ve) {
				foreach ($ins as $vi) {
					$get_ins = $this->otherfunctions->convertResultToRowArray($this->model_master->getInsentifWhere(['a.id_insentif'=>$vi]));
					$id_emp_int = explode(";",$get_ins['id_karyawan']);
					$cd_ins = 0;
					foreach ($id_emp_int as $id_key => $id_value) {
						if($id_value != $ve){
							$cd_ins = 1;
						}
					}
					if($cd_ins == 1){
						$data['id_karyawan'] = $get_ins['id_karyawan'].';'.$ve;
					}else{
						$data['id_karyawan'] = $get_ins['id_karyawan'];
					}
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQuery($data,'master_insentif',['id_insentif'=>$vi]);
				}
			}
			$datax = $this->messages->allGood();
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function delete_insentif()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$id_karyawan = $this->input->post('id');
		$insx = $this->model_master->getListInsentif();
		foreach ($insx as $i) {
			$id_emp_int = explode(";",$i->id_karyawan);
			$new_id_emp = [];
			foreach ($id_emp_int as $id_key => $id_value) {
				if($id_value != $id_karyawan){
					$new_id_emp[] = $id_value;
				}
			}
			$data['id_karyawan'] = implode(";",$new_id_emp);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQuery($data,'master_insentif',['id_insentif'=>$i->id_insentif]);
		}
		$datax = $this->messages->allGood();
		echo json_encode($datax);
	}
	public function edit_insentif()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$id_karyawan = $this->input->post('id_karyawan');
		$karyawan = $this->input->post('karyawan');
		$insentif = $this->input->post('insentif');
		if(!empty($karyawan) || !empty($insentif) || !empty($id_karyawan)){
			$emt_ins = $this->model_master->getListInsentif();
			foreach ($emt_ins as $ei) {
				$emt_ins_id = explode(";",$ei->id_karyawan);
				$empt_id = [];
				foreach ($emt_ins_id as $idt_key => $idt_value) {
					if($idt_value != $id_karyawan){
						$empt_id[] = $idt_value;
					}
				}
				$n_empt_id[$ei->id_insentif] = $empt_id;
			}
			$c_insentif = $this->otherfunctions->checkValueAll($insentif);
			if($c_insentif){
				foreach ($n_empt_id as $nkey => $nvalue) {
					$data['id_karyawan'] = implode(";",$nvalue).';'.$karyawan;
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQuery($data,'master_insentif',['id_insentif'=>$nkey]);
				}
			}else{
				foreach ($insentif as $ikey => $ivalue) {
					// $data['id_karyawan'] = implode(";",$n_empt_id[$ivalue]).';'.$id_karyawan;
					$n_empt_id[$ivalue] = explode(";",implode(";",$n_empt_id[$ivalue]).';'.$karyawan);
				}
				foreach ($n_empt_id as $nkey => $nvalue) {
					$data['id_karyawan'] = implode(";",$nvalue);
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQuery($data,'master_insentif',['id_insentif'=>$nkey]);
				}
			}
			$datax = $this->messages->allGood();
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Data Pendukung Lain--//
	public function data_pendukung_lain()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_payroll->getListDataPendukungLain();
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pen_lain,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_pen_lain,
						$d->kode_pen_lain,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$this->formatter->getFormatMoneyUser($d->nominal),
						ucwords($d->sifat),
						ucwords($d->keterangan),
						$d->tahun,
						$d->nama_periode,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pen_lain');
				$mode = $this->input->post('mode');
				$data=$this->model_payroll->getListDataPendukungLain(['a.id_pen_lain'=>$id]);
				$datax = [];
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_pen_lain,
						'kode'=>$d->kode_pen_lain,
						'nama'=>$d->nama_karyawan,
						'id_karyawan'=>$d->id_karyawan,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'sifat'=>($mode == 'view') ? ucwords($d->sifat) : $d->sifat,
						'periode'=>($mode == 'view') ? $d->nama_periode : $d->kode_periode,
						'keterangan'=>($mode == 'view') ? ucwords($d->keterangan) : $d->keterangan,
						'tahun'=>$d->tahun,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update)
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePendukungLain();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function edt_pendukung_lain()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$id = $this->input->post('id');
		$karyawan = $this->input->post('karyawan');
		$nominal = $this->input->post('nominal');
		$sifat = $this->input->post('sifat');
		$keterangan = $this->input->post('keterangan');
		$periode = $this->input->post('periode');
		$tahun = $this->input->post('tahun');

		if ($id != "") {
			$data=array(
				'id_karyawan'=>$karyawan,
				'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
				'sifat'=>$sifat,
				'keterangan'=>ucwords($keterangan),
				'tahun'=>$tahun,
				'kode_periode'=>$periode,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_pendukung_lain',['id_pen_lain'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_pendukung_lain(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$karyawan = $this->input->post('karyawan');
		$nominal = $this->input->post('nominal');
		$sifat = $this->input->post('sifat');
		$keterangan = $this->input->post('keterangan');
		$periode = $this->input->post('periode');
		$tahun = $this->input->post('tahun');
		if(!empty($karyawan) || !empty($periode) || !empty($tahun)){

			$c_karyawan = $this->otherfunctions->checkValueAll($karyawan);
			if($c_karyawan){
				$empx = $this->model_karyawan->getEmployeeAllActive();
				$emp = [];
				foreach ($empx as $e) {
					$emp[] = $e->id_karyawan;
				}
			}else{
				$emp = $karyawan;
			}

			foreach ($emp as $ekey => $evalue) {
				$data = [
					'id_karyawan'=>$evalue,
					'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
					'sifat'=>$sifat,
					'keterangan'=>ucwords($keterangan),
					'tahun'=>$tahun,
					'kode_periode'=>$periode,
				];
				$data['kode_pen_lain'] = $this->codegenerator->kodePendukungLain();
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQuery($data,'data_pendukung_lain');
			}
			
			$datax=$this->messages->allGood(); 
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}	
	public function check_emp_data_pendukung_lain()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$karyawan = $this->input->post('karyawan');
		$kode = $this->input->post('kode');
		$nominal = $this->input->post('nominal');
		$sifat = $this->input->post('sifat');
		$keterangan = ucwords($this->input->post('keterangan'));
		$periode = $this->input->post('periode');
		$tahun = $this->input->post('tahun');
		if(!empty($karyawan) || !empty($periode) || !empty($tahun)){

			$c_karyawan = $this->otherfunctions->checkValueAll($karyawan);
			if($c_karyawan){
				$empx = $this->model_karyawan->getEmployeeAllActive();
				$emp = [];
				foreach ($empx as $e) {
					$emp[] = $e->id_karyawan;
				}
			}else{
				$emp = $karyawan;
			}
			$data = 'tidak';
			$nama_karyawan = [];
			foreach ($emp as $ekey => $evalue) {
				$cek_data = $this->model_payroll->getListDataPendukungLain(['a.id_karyawan'=>$evalue,'a.kode_periode'=>$periode,'a.sifat'=>$sifat]);
				$cek_data = $this->otherfunctions->convertResultToRowArray($cek_data);
				if(!empty($cek_data)){
					$emp_data = $this->model_karyawan->getEmployeeId($evalue);
					$data = 'ada';
					$nama_karyawan[] = $emp_data['nama'];
				}
			}
			$datax = [
				'data'=>$data,
				'karyawan'=>implode(", ",$nama_karyawan)
			];
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Data PPH 21--//
	public function data_pph_21()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				$kode_periode = $this->input->post('kode_periode');
				$sistem_penggajian = $this->input->post('sistem_penggajian');
				$form_filter['a.create_by'] = $id_admin;
				if(!empty($kode_periode)){ $form_filter['a.kode_periode'] = $kode_periode; }
				if(!empty($sistem_penggajian)){ $form_filter['a.kode_master_penggajian'] = $sistem_penggajian; }
				/* if(empty($kode_periode)){
					$data = $this->model_karyawan->getListDataPenggajianPph(['a.create_by'=>$id_admin]);
				}else{
					$data = $this->model_karyawan->getListDataPenggajianPph(['a.kode_periode'=>$kode_periode,'a.create_by'=>$id_admin]);
				} */
				$data = $this->model_payroll->getListDataPenggajianPph($form_filter);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_p_pph,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);

					// $emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					// $masa_kerja = $this->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
					$bulan = $this->formatter->getNameOfMonth(date('m', strtotime($d->tgl_selesai_periode)));
					$pajak_setahun = ($d->pajak_setahun < 0) ? '( '.$this->formatter->getFormatMoneyUser(abs($d->pajak_setahun)).' )' : $this->formatter->getFormatMoneyUser($d->pajak_setahun);
					$datax['data'][]=[
						$d->id_p_pph,
						$d->nik,
						$d->nama_karyawan,
						$d->no_npwp,
						$d->nama_jabatan,
						$d->nama_bagian,
						$d->nama_loker,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($d->tgl_masuk),'danger'),
						$this->otherfunctions->getLabelMark($d->masa_kerja,'danger'),
						$d->nama_periode,
						$d->nama_sistem_penggajian,
						$bulan,
						$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						$this->formatter->getFormatMoneyUser($d->tunjangan),
						$this->formatter->getFormatMoneyUser($d->bruto_sebulan),
						$this->formatter->getFormatMoneyUser($d->netto_sebulan),
						$pajak_setahun,
						$this->formatter->getFormatMoneyUser($d->pph_setahun),
						$this->formatter->getFormatMoneyUser($d->pph_sebulan),
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_p_pph');
				$data = $this->model_payroll->getListDataPenggajianPph(['a.id_p_pph'=>$id]);
				$datax=[];
				foreach ($data as $d) {
					$bulan_prd = $this->formatter->getNameOfMonth(date('m', strtotime($d->tgl_selesai_periode)));
					$tahun_prd = date('Y', strtotime($d->tgl_selesai_periode));
					$pajak_setahun = ($d->pajak_setahun < 0) ? '( '.$this->formatter->getFormatMoneyUser(abs($d->pajak_setahun)).' )' : $this->formatter->getFormatMoneyUser($d->pajak_setahun);
					$datax=[
						'id_p_pph'=>$d->id_p_pph,
						'kode_periode'=>$d->kode_periode,
						'nama_periode'=>$d->nama_periode,
						'tgl_mulai'=>$this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						'tgl_selesai'=>$this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						'nik'=>$d->nik,
						'nama_karyawan'=>$d->nama_karyawan,
						'kode_jabatan'=>$d->nama_jabatan,
						'kode_bagian'=>$d->nama_bagian,
						'kode_loker'=>$d->nama_loker,
						'kode_grade'=>$d->nama_grade,
						'nama_sistem_penggajian'=>$d->nama_sistem_penggajian,
						'bulan_prd'=>$bulan_prd,
						'tahun_prd'=>$tahun_prd,
						'tgl_masuk'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'masa_kerja'=>$d->masa_kerja,
						'status_ptkp'=>$d->status_ptkp,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						'tunjangan'=>$this->formatter->getFormatMoneyUser($d->tunjangan),
						'bpjs_jkk_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_jkk_perusahaan),
						'bpjs_jkm_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_jkm_perusahaan),
						'bpjs_kes_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_kes_perusahaan),
						'bruto_sebulan'=>$this->formatter->getFormatMoneyUser($d->bruto_sebulan),
						'bruto_setahun'=>$this->formatter->getFormatMoneyUser($d->bruto_setahun),
						'biaya_jabatan'=>$this->formatter->getFormatMoneyUser($d->biaya_jabatan),
						'bpjs_jht_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_jht_perusahaan),
						'bpjs_jht_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_jht_pekerja),
						'iuran_pensiun_perusahaan'=>$this->formatter->getFormatMoneyUser($d->iuran_pensiun_perusahaan),
						'iuran_pensiun_pekerja'=>$this->formatter->getFormatMoneyUser($d->iuran_pensiun_pekerja),
						'jml_pengurang'=>$this->formatter->getFormatMoneyUser($d->jml_pengurang),
						'netto_sebulan'=>$this->formatter->getFormatMoneyUser($d->netto_sebulan),
						'netto_setahun'=>$this->formatter->getFormatMoneyUser($d->netto_setahun),
						'pajak_setahun'=>$pajak_setahun,
						'pph_setahun'=>$this->formatter->getFormatMoneyUser($d->pph_setahun),
						'no_npwp'=>$d->no_npwp,
						'pph_sebulan'=>$this->formatter->getFormatMoneyUser($d->pph_sebulan),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	
 	
	//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Data Payroll Harian--//
	public function data_penggajian_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_admin = $this->admin;
				$sistem_penggajian = $this->input->post('sistem_penggajian');
				$data = $this->model_payroll->getDataPayrollHarian(['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'HARIAN']);
				/* $data = $this->model_karyawan->getDataPayrollHarian(['a.create_by'=>$id_admin]); */
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pay,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);

					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$masa_kerja = $this->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
					$datax['data'][]=[
						$d->id_pay,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						$this->formatter->getFormatMoneyUser($d->insentif),
						$this->formatter->getFormatMoneyUser($d->ritasi),
						$this->formatter->getFormatMoneyUser($d->uang_makan),
						$this->formatter->getFormatMoneyUser($d->pot_tidak_masuk),
						$this->formatter->getFormatMoneyUser($d->bpjs_jht),
						$this->formatter->getFormatMoneyUser($d->bpjs_jkk),
						$this->formatter->getFormatMoneyUser($d->bpjs_jkm),
						$this->formatter->getFormatMoneyUser($d->bpjs_pen),
						$this->formatter->getFormatMoneyUser($d->bpjs_kes),
						$this->formatter->getFormatMoneyUser($d->angsuran),
						$d->angsuran_ke,
						$this->formatter->getFormatMoneyUser($d->gaji_bersih),
						$d->no_rek,
						$this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pay');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataPayrollHarian(['a.id_pay'=>$id]);

				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$penambah = $this->payroll->getTablePenambah($d->id_karyawan,$d->kode_periode,$id,'data','HARIAN');
					$pengurang = $this->payroll->getTablePengurang($id,'data','HARIAN');
					$lembur = $this->payroll->getTableLembur($id,'data','HARIAN');
					$total_gaji = $this->payroll->getTableGajiBerih($d->gaji_bersih);
					$datax=[
						'id_pay'=>$d->id_pay,
						'nik'=>$d->nik,
						'nama_karyawan'=>$d->nama_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'grade'=>$d->nama_grade,
						'bagian'=>$d->nama_bagian,
						'loker'=>$d->nama_loker,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'masa'=>$masa_kerja,
						'rekening'=>$d->rekening,
						'periode'=>$d->nama_periode. ' ( '.$d->nama_sistem_penggajian.' )',
						'sistem'=>$d->nama_sistem_penggajian,
						'penambah'=>$penambah,
						'pengurang'=>$pengurang,
						'lembur'=>$lembur,
						'total_gaji'=>$total_gaji,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						'pph'=>'',
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				/* $data = $this->codegenerator->kodebpjk(); */
        		/* echo json_encode($data); */
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function ready_data_payroll_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$kode_periode = $this->input->post('kode_periode');
		$data_ritasi = $this->input->post('data_ritasi');
		$data_insentif = $this->input->post('data_insentif');
		$data_bpjs = $this->input->post('data_bpjs');
		$data_pinjaman = $this->input->post('data_pinjaman');
		$data_lain = $this->input->post('data_lain');
		$metode_bpjs = $this->input->post('metode_bpjs');

		/* $datax = [
			'kode_periode'=>$kode_periode,
			'data_ritasi'=>$data_ritasi,
			'data_insentif'=>$data_insentif,
			'data_bpjs'=>$data_bpjs,
			'data_pinjaman'=>$data_pinjaman,
			'data_lain'=>$data_lain,
			'metode_bpjs'=>$metode_bpjs,
		]; */
		if(!empty($kode_periode)){
			$data_periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$kode_periode]));
			$id_admin = $this->admin;
			// $del_data = $this->model_global->deleteQuery('data_periode_penggajian_harian',['create_by'=>$id_admin,'kode_master_penggajian'=>'HARIAN']);
			// if($del_data['status_data']){
			// 	$where_del_detail = [
			// 		'create_by'=>$id_admin,
			// 		'kode_periode_penggajian_harian'=>$data_periode['kode_periode_penggajian_harian'],
			// 	];
			// 	$del_data_detail = $this->model_global->deleteQuery('data_periode_penggajian_harian_detail',$where_del_detail);
			// 	if($del_data_detail['status_data']){
			// 		$ins_data = $this->insertDataPenggajianHarian($kode_periode,$data_ritasi,$data_insentif,$data_bpjs,$data_pinjaman,$data_lain,$metode_bpjs);
			// 		if($ins_data['status_data']){
			// 			$datax = 'true';
			// 		}
			// 	}
			// }
			$del_data = $this->model_global->deleteQuery('data_penggajian_harian',['create_by'=>$id_admin,'kode_master_penggajian'=>'HARIAN']);
			if($del_data['status_data']){
				$ins_data = $this->insertDataPenggajianHarian($kode_periode,$data_ritasi,$data_insentif,$data_bpjs,$data_pinjaman,$data_lain,$metode_bpjs);
				if($ins_data['status_data']){
					$datax = 'true';
				}else{
					$datax = 'true';
				}
			}
		}else{
			$datax = 'true';
		}
		echo json_encode($datax);
	}

	// public function insertDataPenggajianHarian()
	public function insertDataPenggajianHarian($kode_periode,$data_ritasi,$data_insentif,$data_bpjs,$data_pinjaman,$data_lain,$metode_bpjs)
	{
		// $kode_periode = "PRH201905270001";
		// $data_ritasi = "data_ritasi";
		// $data_insentif = "data_insentif";
		// $data_bpjs = "data_bpjs";
		// $data_pinjaman = "data_pinjaman";
		// $data_tdk_tetap = "data_tdk_tetap";
		// $data_lain = "data_lain";
		// $metode_bpjs = "nominal";

		if(empty($kode_periode))
			redirect('not_found');

		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$kode_periode]));
		if($this->dtroot['adm']['level'] != 0){
			$en_access_jabatan = $this->payroll->getJabatanFromPetugasPayroll($this->dtroot['adm']['id_karyawan']);
		}

		$periode_detail = $this->model_master->getListPeriodePenggajianHarianDetail($kode_periode);
		$emp_loker = [];
		foreach ($periode_detail as $pd) {
			$emp = $this->model_payroll->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'emp.kode_penggajian'=>'HARIAN'],1);
			foreach ($emp as $e) {
				if($this->dtroot['adm']['level'] == 0){
					$emp_loker[$e->id_karyawan] = $e->id_karyawan;
				}else{
					if(in_array($e->jabatan,$en_access_jabatan)){
						$emp_loker[$e->id_karyawan] = $e->id_karyawan;
					}
				}
			}
		}

		$karyawan = [];
		foreach ($emp_loker as $key => $value) {
			$emp_single = $this->model_karyawan->getEmployeeId($value);
			$karyawan[] = $this->payroll->cekAgendaPenggajianHarian($emp_single,$kode_periode);
		}
		
		foreach (array_values(array_filter($karyawan)) as $key => $value) {
			$gaji_pokok = (empty($value['gaji_pokok'])) ? 0 : $value['gaji_pokok'];
			/*=== PENAMBAH ===*/
			/*INSENTIF*/
			if(!empty($data_insentif)){
				$insentif = $this->payroll->getInsentif($value['id_karyawan']);
			}else{
				$insentif = 0;
			}
			/*RITASI*/
			if(!empty($data_ritasi)){
				$ritasi = $this->payroll->getRitasi($value['id_karyawan'],$kode_periode);
			}else{
				$ritasi = 0;
			}
			/*PRESENSI*/
			$presensi = $this->payroll->getPresensiDataHarian($value['id_karyawan'],$kode_periode);
			/*UANG MAKAN*/
			$uang_makan = (empty($this->payroll->getUangMakan($presensi))) ? 0 : $this->payroll->getUangMakan($presensi);
			/*TUNJANGAN*/
			$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);																
			$tunjangan_nominal = $this->payroll->getTunjanganNominalPayroll($tunjangan);	/*nominal tunjangan total*/
			$tunjangan_nominal = (empty($tunjangan_nominal)) ? 0 : $tunjangan_nominal;
			$upah = ($gaji_pokok+$tunjangan_nominal);
			$upah_harian = $upah/26;
			$nominal_penambah = ($upah_harian*count($presensi['ada']))+$insentif+$ritasi+$uang_makan;

			/*=== PENGURANG ===*/
			/*BOJS*/
			if(!empty($data_bpjs)){
				if($metode_bpjs == 'nominal'){
					$bpjs = $this->payroll->getBpjs($value['id_karyawan']); 
					
					$bpjs_jht = (empty($bpjs['jht'])) ? 0 : $bpjs['jht'];
					$bpjs_jkk = (empty($bpjs['jkk'])) ? 0 : $bpjs['jkk'];
					$bpjs_jkm = (empty($bpjs['jkm'])) ? 0 : $bpjs['jkm'];
					$bpjs_jpns = (empty($bpjs['jpns'])) ? 0 :  $bpjs['jpns'];
					$bpjs_jkes = (empty($bpjs['jkes'])) ? 0 :  $bpjs['jkes'];
					
					$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
				}else{
					$bpjs_jht = $this->payroll->getBpjsBayarSendiri($upah, 'JHT');
					$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($upah, 'JKK-RS');
					$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($upah, 'JKM');
					$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($upah, 'JPNS');
					$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($upah, 'JKES');
					$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
				}
			}else{
				$bpjs_jht = 0;
				$bpjs_jkk = 0;
				$bpjs_jkm = 0;
				$bpjs_jpns = 0;
				$bpjs_jkes = 0;
				$pe_bpjs = 0;
			}
			
			/*ANGSURAN HUTANG*/	
			if(!empty($data_pinjaman)){
				$angsuran = $this->payroll->getAngsuran($value['id_karyawan'],$kode_periode,'HARIAN');
			}else{
				$angsuran = [
					'nominal'=>0,
					'angsuran_ke'=>0,
				];
			}		
			/*IJIN CUTI*/										
			$data_ijin_cuti = $this->payroll->getIjinCuti($value['id_karyawan'],$kode_periode,'HARIAN');
			$ijin_cuti = $this->payroll->getIjinCutiSimple($data_ijin_cuti);
			$ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$value['gaji_pokok'],$value['tgl_masuk']);
			$nominal_ijin_perjam = $ijin_nominal['ijin_per_jam'];
			/*DETAIL PRESENSI*/
			$detail_presensi = $this->payroll->getPresensiDetail($value['id_karyawan'],$presensi);
			$nominal_pengurang = $pe_bpjs+$angsuran['nominal']+$nominal_ijin_perjam;
			if(!empty($data_lain)){
				$data_lain = $this->payroll->getPendukungLain($value['id_karyawan'],$kode_periode);
				$nominal_data_lain = $this->payroll->getNominalPendukungLain($data_lain);
			}else{
				$nominal_data_lain = [
					'penambah'=>0,
					'pengurang'=>0
				];
			}	
			/*=== DATA LEBUR ===*/
			$data_lembur = $this->payroll->getLemburEmp($value['id_karyawan'],null,$periode['tgl_mulai'],$periode['tgl_selesai']);
			$lembur = $this->payroll->getNominalLembur($data_lembur,$upah);
			$req_lembur = $this->payroll->getReqLembur($lembur,$value['id_karyawan']);
			
			$nominal_biasa = $req_lembur['nominal_biasa'];
			$nominal_libur = $req_lembur['nominal_libur'];
			$nominal_libur_pendek = $req_lembur['nominal_libur_pendek'];
			$gaji_lembur = $nominal_biasa+$nominal_libur+$nominal_libur_pendek;

			$gaji_bersih = (($nominal_penambah-$nominal_pengurang)+$gaji_lembur)+$nominal_data_lain['penambah']-$nominal_data_lain['pengurang'];
			$data = [
				'kode_periode'=>$kode_periode,
				'nama_periode'=>$periode['nama'],
				'tgl_mulai'=>$periode['tgl_mulai'],
				'tgl_selesai'=>$periode['tgl_selesai'],
				'kode_master_penggajian'=>$periode['kode_master_penggajian'],
				'id_karyawan'=>$value['id_karyawan'],
				'nik'=>$value['nik'],
				'nama_karyawan'=>$value['nama'],
				'kode_jabatan'=>$value['kode_jabatan'],
				'kode_grade'=>$value['kode_grade'],
				'kode_bagian'=>$value['kode_bagian'],
				'kode_loker'=>$value['kode_loker'],
				'tgl_masuk'=>$value['tgl_masuk'],
				'masa_kerja'=>$value['masa_kerja'],
				'gaji_pokok'=>$value['gaji_pokok'],
				'insentif'=>$insentif,
				'ritasi'=>$ritasi,
				'uang_makan'=>$uang_makan,
				'pot_tidak_masuk'=>'',
				'bpjs_jht'=>$bpjs_jht,
				'bpjs_jkk'=>$bpjs_jkk,
				'bpjs_jkm'=>$bpjs_jkm,
				'bpjs_pen'=>$bpjs_jpns,
				'bpjs_kes'=>$bpjs_jkes,
				'angsuran'=>$angsuran['nominal'],
				'angsuran_ke'=>$angsuran['angsuran_ke'],
				'tunjangan'=>$tunjangan_nominal,
				'jam_biasa'=>(Int)$req_lembur['jam_biasa'],
				'nominal_biasa'=>$nominal_biasa,
				'jam_libur_pendek'=>(Int)$req_lembur['jam_libur_pendek'],
				'nominal_libur_pendek'=>$nominal_libur_pendek,
				'jam_libur'=>(Int)$req_lembur['jam_libur'],
				'nominal_libur'=>$nominal_libur,
				'gaji_lembur'=>$gaji_lembur,
				'gaji_bersih'=>$gaji_bersih,
				'no_rek'=>$value['rekening'],
				'meninggalkan_jam_kerja'=>$ijin_cuti['ijin_per_jam'],
				'meninggalkan_jam_kerja_n'=>$nominal_ijin_perjam,
				'alpha'=>implode(";",$detail_presensi['alpha']),
				'ijin'=>implode(";",$detail_presensi['ijin']),
				'sakit_skd'=>implode(";",$detail_presensi['skd']),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'data_penggajian_harian');
			$this->payroll->update_tunjangan($value,$kode_periode,$tunjangan,$this->admin,'HARIAN');
			// echo '<pre>';
			// print_r($data);
			// echo '</pre>';
			
		}
		// return $datax;
	}

	
	// public function insertDataPenggajianLemburHarian()
	public function insertDataPenggajianLemburHarian($kode_periode,$karyawan)
	{
		//$kode_periode = "PRP201905250002";
		if(empty($kode_periode))
			redirect('not_found');

		
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
		// $periode_detail = $this->model_master->getListPeriodePenggajianDetail($kode_periode);
		// if($this->dtroot['adm']['level'] != 0){
		// 	$en_access_jabatan = $this->payroll->getJabatanFromPetugasPayroll($this->dtroot['adm']['id_karyawan']);
		// }

		// $emp_loker = [];
		// foreach ($periode_detail as $pd) {
		// 	$emp = $this->model_karyawan->getEmployeeWhere(['emp.loker'=>$pd->kode_loker,'emp.kode_penggajian'=>$periode['kode_master_penggajian']],1);
		// 	foreach ($emp as $e) {
		// 		if($this->dtroot['adm']['level'] == 0){
		// 			$emp_loker[$e->id_karyawan] = $e->id_karyawan;
		// 		}else{
		// 			if(in_array($e->jabatan,$en_access_jabatan)){
		// 				$emp_loker[$e->id_karyawan] = $e->id_karyawan;
		// 			}
		// 		}
		// 	}
		// }
		
		// $karyawan = [];
		// foreach ($emp_loker as $key => $value) {
		// 	$emp_single = $this->model_karyawan->getEmployeeId($value);
		// 	$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
		// }

		foreach (array_values(array_filter($karyawan)) as $key => $value) {
			$data_lembur = $this->payroll->getLemburEmp($value['id_karyawan'],null,$periode['tgl_mulai'],$periode['tgl_selesai']);
			$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);
			$tunjangan_tetap = $this->payroll->getTunjanganNominalTetap($tunjangan);
			$upah = $value['gaji_pokok']+$tunjangan_tetap;
			$lembur = $this->payroll->getNominalLembur($data_lembur,$upah);
			$req_lembur = $this->payroll->getReqLembur($lembur,$value['id_karyawan']);
			
			$nominal_biasa = $this->otherfunctions->pembulatanDepanKoma($req_lembur['nominal_biasa']);
			$nominal_libur = $this->otherfunctions->pembulatanDepanKoma($req_lembur['nominal_libur']);
			$nominal_libur_pendek = $this->otherfunctions->pembulatanDepanKoma($req_lembur['nominal_libur_pendek']);
			$gaji_terima = $nominal_biasa+$nominal_libur+$nominal_libur_pendek;
			$data = [
				'kode_periode'=>$kode_periode,
				'nama_periode'=>$periode['nama'],
				'tgl_mulai'=>$periode['tgl_mulai'],
				'tgl_selesai'=>$periode['tgl_selesai'],
				'kode_master_penggajian'=>$periode['kode_master_penggajian'],
				'id_karyawan'=>$value['id_karyawan'],
				'nik'=>$value['nik'],
				'nama_karyawan'=>$value['nama'],
				'kode_jabatan'=>$value['kode_jabatan'],
				'kode_grade'=>$value['kode_grade'],
				'kode_bagian'=>$value['kode_bagian'],
				'kode_loker'=>$value['kode_loker'],
				'tgl_masuk'=>$value['tgl_masuk'],
				'masa_kerja'=>$value['masa_kerja'],
				'gaji_pokok'=>$value['gaji_pokok'],
				'upah'=>$upah,
				'jam_biasa'=>(Int)$req_lembur['jam_biasa'],
				'nominal_biasa'=>$nominal_biasa,
				'jam_libur'=>(Int)$req_lembur['jam_libur'],
				'nominal_libur'=>$nominal_libur,
				'jam_libur_pendek'=>(Int)$req_lembur['jam_libur_pendek'],
				'nominal_libur_pendek'=>$nominal_libur_pendek,
				'gaji_terima'=>$gaji_terima,
				'no_rekening'=>$value['rekening'],
				'tgl_proses'=>date('Y-m-d h:i:s'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$this->model_global->insertQuery($data,'data_penggajian_lembur');
		}
		 return $data;
	}
	public function send_to_log_harian()
	{
		$usage = $this->input->post('usage');
		if($usage == 'pindah'){
			$id_admin = $this->admin;
			$total = 0;
			$data = $this->model_payroll->getDataLogPayrollSingle(['create_by'=>$id_admin,'kode_master_penggajian'=>'HARIAN']);
			$kode_periode = $this->otherfunctions->convertResultToRowArray($data)['kode_periode'];
			foreach ($data as $d) {
				$new_data = $this->model_payroll->getDataLogPayrollSingle(['id_pay'=>$d->id_pay,'create_by'=>$id_admin]);
				$new_data = $this->otherfunctions->convertResultToRowArray($new_data);
				unset($new_data['id_pay']);
				$new_data=array_merge($new_data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->insertQuery($new_data,'log_data_penggajian_harian');
				$total += $d->gaji_bersih;
			}
			$kode_periode = $this->otherfunctions->convertResultToRowArray($data)['kode_periode'];
			$data_tunjangan = $this->model_payroll->getDataPayrollTunjanganSingle(['kode_periode_penggajian'=>$kode_periode,'kode_master_penggajian'=>'HARIAN']);
			foreach ($data_tunjangan as $dt) {
				$new_data_t = $this->model_payroll->getDataPayrollTunjanganSingle(['id_pay_t'=>$dt->id_pay_t]);
				$new_data_t = $this->otherfunctions->convertResultToRowArray($new_data_t);
				unset($new_data_t['id_pay_t']);
				$this->model_global->insertQuery($new_data_t,'log_data_penggajian_tunjangan');
			}
			
			/* $total_lembur = 0;
			$data = $this->model_karyawan->getDataPayrollLeburSingle(['create_by'=>$id_admin,'kode_master_penggajian'=>'HARIAN']);
			foreach ($data as $d) {
				$new_data = $this->model_karyawan->getDataPayrollLeburSingle(['id_penggajian_lembur'=>$d->id_penggajian_lembur]);
				$new_data = $this->otherfunctions->convertResultToRowArray($new_data);
				unset($new_data['id_penggajian_lembur']);
				$this->model_global->insertQuery($new_data,'log_data_penggajian_lembur');
				$total_lembur += $d->gaji_terima;
			} */
			$data_periode =[
				'status_gaji'=>1,
				'tgl_transfer'=>date('Y-m-d'),
				'total_transfer'=>($total),
			];
			$data_periode=array_merge($data_periode,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQuery($data_periode,'data_periode_penggajian_harian',['kode_periode_penggajian_harian'=>$kode_periode]);
			/* $this->db->truncate('data_penggajian'); */
			/* $this->db->truncate('data_penggajian_tunjangan'); */
			
			$this->model_global->deleteQuery('data_penggajian_harian',['kode_periode'=>$kode_periode,'kode_master_penggajian'=>'HARIAN']);
			$this->model_global->deleteQuery('data_penggajian_tunjangan',['kode_periode_penggajian'=>$kode_periode,'kode_master_penggajian'=>'HARIAN']);
			$datax = $this->messages->allGood();
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Data Payroll Harian--//
	public function data_log_penggajian_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_admin = $this->admin;
				$kode_periode = $this->input->post('kode_periode');
				
				$form_filter['a.create_by'] = $id_admin;
				$form_filter['a.kode_master_penggajian'] = 'HARIAN';
				if(!empty($kode_periode)){ $form_filter['a.kode_periode'] = $kode_periode; }
				
				$data = $this->model_payroll->getDataLogPayrollHarian($form_filter);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pay,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);

					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					// $get_lembur = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getDataPayrollLembur(['a.kode_periode'=>$d->kode_periode,'a.id_karyawan'=>$d->id_karyawan]));
					$get_lembur = [
						'jam_biasa'=>$d->jam_biasa,
						'nominal_biasa'=>$d->nominal_biasa,
						'jam_libur'=>$d->jam_libur,
						'nominal_libur'=>$d->nominal_libur,
						'jam_libur_pendek'=>$d->jam_libur_pendek,
						'nominal_libur_pendek'=>$d->nominal_libur_pendek,
						'gaji_terima'=>$d->gaji_lembur,
					];
					$masa_kerja = $this->otherfunctions->intervalTimeYear($emp['tgl_masuk']);
					$bulan_prd = $this->formatter->getNameOfMonth(date('m', strtotime($d->tgl_selesai)));
					$tahun_prd = date('Y', strtotime($d->tgl_selesai));
					$datax['data'][]=[
						$d->id_pay,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$d->nama_periode,
						$d->nama_sistem_penggajian,
						$bulan_prd,
						$tahun_prd,
						$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						$this->formatter->getFormatMoneyUser($d->insentif),
						$this->formatter->getFormatMoneyUser($d->ritasi),
						$this->formatter->getFormatMoneyUser($d->uang_makan),
						$this->formatter->getFormatMoneyUser($d->pot_tidak_masuk),
						$this->formatter->getFormatMoneyUser($d->bpjs_jht),
						$this->formatter->getFormatMoneyUser($d->bpjs_jkk),
						$this->formatter->getFormatMoneyUser($d->bpjs_jkm),
						$this->formatter->getFormatMoneyUser($d->bpjs_pen),
						$this->formatter->getFormatMoneyUser($d->bpjs_kes),
						$this->formatter->getFormatMoneyUser($d->angsuran),
						$d->angsuran_ke,
						$this->formatter->getFormatMoneyUser($get_lembur['gaji_terima']),
						$this->formatter->getFormatMoneyUser($d->gaji_bersih),
						$d->no_rek,
						$this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pay');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataLogPayrollHarian(['a.id_pay'=>$id]);

				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					// $penambah = $this->payroll->getTablePenambah($d->id_karyawan,$d->kode_periode,$id,'data');
					// $pengurang = $this->payroll->getTablePengurang($id,'log');
					// $lembur = $this->payroll->getTableLembur($id,'log');
					// $total_gaji = $this->payroll->getTableGajiBerih($d->gaji_bersih);
					
					$penambah = $this->payroll->getTablePenambah($d->id_karyawan,$d->kode_periode,$id,'log','HARIAN');
					$pengurang = $this->payroll->getTablePengurang($id,'log','HARIAN');
					$lembur = $this->payroll->getTableLembur($id,'log','HARIAN');
					$total_gaji = $this->payroll->getTableGajiBerih($d->gaji_bersih);

					$datax=[
						'id_pay'=>$d->id_pay,
						'kode_periode'=>$d->kode_periode,
						'nik'=>$d->nik,
						'nama_karyawan'=>$d->nama_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'grade'=>$d->nama_grade,
						'bagian'=>$d->nama_bagian,
						'loker'=>$d->nama_loker,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'masa'=>$masa_kerja,
						'rekening'=>$d->rekening,
						'periode'=>$d->nama_periode. ' ( '.$d->nama_sistem_penggajian.' )',
						'sistem'=>$d->nama_sistem_penggajian,
						'penambah'=>$penambah,
						'pengurang'=>$pengurang,
						'lembur'=>$lembur,
						'total_gaji'=>$total_gaji,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						'pph'=>'',
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodebpjk();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
}
