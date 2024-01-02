<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	/**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Otherfunctions
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

class Pages extends CI_Controller 
{
	public function __construct() 
	{  
		parent::__construct(); 
		if(!empty($_COOKIE['nik'])){
			setcookie('pages', 'adm', strtotime('+1 year'), '/');
		}else{
			setcookie('pages', '', 0, '/');
		}
		$this->date = $this->otherfunctions->getDateNow();
		if ($this->session->has_userdata('emp')) {
			$this->session->unset_userdata('emp');
		}
		if ($this->session->has_userdata('adm')) {
			$this->admin = $this->session->userdata('adm')['id'];
		}else{ 
			if(!empty($_COOKIE['pages']) == 'adm'){
				$dataAdm=$this->db->get_where('admin',['username'=>$_COOKIE['nik']])->row_array();
				$this->session->set_userdata('adm', ['id'=>$dataAdm['id_admin']]);
				$this->admin = $this->session->userdata('adm')['id'];
			}else{
				redirect('auth');
			}
		}
	    $this->rando = $this->codegenerator->getPin(6,'number');
		// $dtroot['admin']=$this->model_admin->adm($this->admin);
		$dtroot['admin']=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
		$l_acc=$this->otherfunctions->getYourAccess($this->admin);
		$l_ac=$this->otherfunctions->getAllAccess();
		if (isset($l_ac['stt'])) {
			if (in_array($l_ac['stt'], $l_acc)) {
		      $attr='type="submit"';
		    }else{
		      $attr='type="button" data-toggle="tooltip" title="Tidak Diizinkan"';
		    }
		    if (!in_array($l_ac['edt'], $l_acc) && !in_array($l_ac['del'], $l_acc)) {
		      $not_allow='<label class="label label-danger">Tidak Diizinkan</label>';
		    }else{
		      $not_allow=NULL;
		    }
		}else{
			$not_allow=null;
			$attr=null;
		}
		
		$this->link=$this->otherfunctions->getYourMenu($this->admin);
		$this->access=['access'=>$l_acc,'l_ac'=>$l_ac,'b_stt'=>$attr,'n_all'=>$not_allow,'kode_bagian'=>$dtroot['admin']['kode_bagian']];
		$nm=explode(" ", $dtroot['admin']['nama']);
		$datax['adm'] = array( 
				'nama'=>$nm[0],
				'email'=>$dtroot['admin']['email'],
				'username'=>$dtroot['admin']['username'],
				'kelamin'=>$dtroot['admin']['kelamin'],
				'foto'=>$dtroot['admin']['foto'],
				'create'=>$dtroot['admin']['create_date'],
				'update'=>$dtroot['admin']['update_date'],
				'login'=>$dtroot['admin']['last_login'],
				'level'=>$dtroot['admin']['level'],
				'id_karyawan'=>$dtroot['admin']['id_karyawan'],
				'skin'=>$dtroot['admin']['skin'],
				'list_bagian'=>$dtroot['admin']['list_filter_bagian'],
				'menu'=>$this->model_master->getListMenuActive(),
				'your_menu'=>$this->otherfunctions->getYourMenuId($this->admin),
				'your_url'=>$this->otherfunctions->getYourMenu($this->admin),
				'notif'=>$this->otherfunctions->getYourNotification($this->admin,'admin'),
				'kode_bagian'=>$dtroot['admin']['kode_bagian'],
				'id_admin'=>$this->admin,
				'access'=>$this->access,
			);
		$this->dtroot=$datax;
	}
	public function index(){
		// echo '<pre>';
		// print_r($_COOKIE);
		redirect('pages/'.reset($this->dtroot['adm']['your_url']));
	}
	function cobapph(){
		echo '<pre>';
		$bulan = '06';
		$tahun = '2021';
		$koreksi = '0';
		$nik = '1308940514';
		$datax = $this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi,'nik'=>$nik]);
		$total_pengurang_lain = 0;
		$total_penambah_lain = 0;
		$total_pengurang_hallo = 0;
		$total_penambah_hallo = 0;
		foreach ($datax as $d) {
			$pengurang_lainx=0;
			$penambah_lainx=0;
			$pengurang_lainx_hallo=0;
			$penambah_lainx_hallo=0;
			if(!empty($d->data_lain)){
				if (strpos($d->data_lain, ';') !== false) {
					$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
					$dHallo = $this->otherfunctions->getDataExplode($d->data_lain_hallo,';','all');
					$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
					foreach ($dLain as $key => $value) {
						if($value == 'pengurang'){
							if($dHallo[$key] == '1'){
								$pengurang_lainx_hallo += $nLain[$key];
							}else{
								$pengurang_lainx += $nLain[$key];
							}
						}else{
							if($dHallo[$key] == '1'){
								$penambah_lainx_hallo += $nLain[$key];
							}else{
								$penambah_lainx += $nLain[$key];
							}
						}
					}
				}else{
					if($d->data_lain == 'pengurang'){
						if($d->data_lain_hallo == '1'){
							$pengurang_lainx_hallo += $d->nominal_lain;
						}else{
							$pengurang_lainx += $d->nominal_lain;
						}
					}else{
						if($d->data_lain_hallo == '1'){
							$penambah_lainx_hallo += $d->nominal_lain;
						}else{
							$penambah_lainx += $d->nominal_lain;
						}
					}
				}
			}
			$pengurang_lain = $pengurang_lainx;
			$penambah_lain = $penambah_lainx;
			$pengurang_hallo = $pengurang_lainx_hallo;
			$penambah_hallo = $penambah_lainx_hallo;
			$total_pengurang_lain += $pengurang_lainx;
			$total_penambah_lain += $penambah_lainx;
			$total_pengurang_hallo += $pengurang_lainx_hallo;
			$total_penambah_hallo += $penambah_lainx_hallo;
			print_r('pengurang_lain => '.$pengurang_lain.'<br>');
			print_r('penambah_lain => '.$penambah_lain.'<br>');
			print_r('pengurang_hallo => '.$pengurang_hallo.'<br>');
			print_r('penambah_hallo => '.$penambah_hallo.'<br>');
		}
		// print_r($datax);
	}
	public function coba()
	{
		echo '<pre>';
		$id = '162';
		$usage = 'JPNS';
		$gaji_pokok = '2000000';
		// $dkd = $this->payroll->getBpjsBayarSendiri($gaji_pokok,$usage,$id);
		// print_r($dkd);
		$emp = $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$id],true);
		$umur = $this->otherfunctions->intervalTimeYear($emp['tgl_lahir'],'umur');
		$umurPensiun = $this->model_master->getGeneralSetting('UMUR_PENSIUN')['value_int'];
		// print_r($umur);
 		$get_bpjs = $this->otherfunctions->convertResultToRowArray($this->model_master->getListBpjs(['a.inisial'=>$usage,'a.status'=>1]));
 		if(!empty($get_bpjs)){
			if($usage == 'JPNS'){
				$emp = $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$id],true);
				$umur = $this->otherfunctions->intervalTimeYear($emp['tgl_lahir'],'umur');
				if($umur['tahun'] >= $umurPensiun){
 					$per_bpjs = 0;
				}else{
 					$per_bpjs = $get_bpjs['bpjs_karyawan'];
				}
			}else{
 				$per_bpjs = $get_bpjs['bpjs_karyawan'];
			}
 			$new_val = ($per_bpjs/100)*$gaji_pokok;
 		}
		print_r($new_val);
	}
	function cobaKTP()
	{
		echo '<pre>';
		$nik = '2910851007';
		$ktp = $this->model_karyawan->getKTPEmployee($nik);
		print_r($ktp);
	}
	function coba3()
	{
		echo '<pre>';
		$id_kar     = '138';//$this->input->post('id_kar');
		$kode_izin  = 'MIC201907160001';//$this->input->post('jenis');
		$tanggal    = '10/01/2023 08:00:00 - 12/01/2023 16:00:00';//$this->input->post('tanggal');
		if($kode_izin == 'MIC201907160001'){
			$dataCuti = $this->model_karyawan->getListIzinCutiWhereMaxID(['id_karyawan'=>$id_kar,'jenis'=>$kode_izin]);
			$tgl_awal   = $this->formatter->getDateFromRange($tanggal,'start');
			$hari       = $this->otherfunctions->countDayNotIncludeLeave($dataCuti['tgl_selesai'], $tgl_awal);
			$minCuti	= $this->model_master->getGeneralSetting('MIN_CUTI')['value_int'];
			if($minCuti > $hari){
				echo 'belum boleh cuti';
			}
			print_r($dataCuti['tgl_selesai']);echo '<br>';
			print_r($tgl_awal);echo '<br>';
			print_r($hari);
		}
		echo '<pre>';
		// print_r($id_kar);echo '<br>';
		// print_r($kode_izin);echo '<br>';
		// print_r($tanggal);
	}
	function coba4()
	{
		echo '<pre>';
		// $biaya = '200000000';
		$biaya = '48500000';
		$thr = '0';
		$status_pajak = 'TK/0';
		$npwp = '0e';
		$perhitungan_pajak = 'NON_PTKP';
		$bruto_bulan = $biaya+$thr;
		$bruto_tahun = $bruto_bulan*12;
		$netto_bulan = $bruto_bulan;
		$netto_tahun = $netto_bulan*12;
		if($perhitungan_pajak == 'NON_PTKP'){
			$n_ptkp = $bruto_bulan;
			$n_pkp = (50/100)*$bruto_bulan;
			// export rekap BP 1721 akhir tahun
			// if(empty($npwp)){
			// 	$pajak = ((3/100)*$bruto_bulan);
			// }else{
			// 	$pajak = ((2.5/100)*$bruto_bulan);
			// }
			// $pph_tahun = $pajak*12;
			// $pph_bulan = $pajak;
			// $plus_npwp = $pajak;
			// $tarif_pajak = 0;
			$layer_pph   = $this->payroll->getLayerPPH($n_pkp,$npwp);
			$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$npwp);
			$pph_tahun   = $get_pph['pph_tahun'];
			$pph_bulan   = $get_pph['pph_bulan'];
			$plus_npwp   = $get_pph['plus_npwp'];
			$pajak       = $get_pph['pph_bulan'];
			// print_r($layer_pph);
		}else{
			$n_ptkp 	 = $this->payroll->getPTKP($status_pajak);
			//n_pkp = 50% dari bruto tahunan
			$n_pkp 		 = $netto_tahun-$n_ptkp;
			$n_pkp 		 = ($n_pkp > 0)?$n_pkp:0;
			$tarif_pajak = $this->payroll->getPajakPertahun($bruto_tahun,$status_pajak);
			$tarif_pajak = ($tarif_pajak > 0)?$tarif_pajak:0;
			if($tarif_pajak == 0){
				$pph_tahun   = 0;
				$pph_bulan   = 0;
				$plus_npwp   = 0;
				$pajak       = 0;
			}else{
				// $layer_pph   = $this->payroll->getLayerPPH($bruto_tahun);
				$layer_pph   = $this->payroll->getLayerPPH($tarif_pajak);
				$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$npwp);
				$pph_tahun   = $get_pph['pph_tahun'];
				$pph_bulan   = $get_pph['pph_bulan'];
				$plus_npwp   = $get_pph['plus_npwp'];
				$pajak       = $get_pph['pph_bulan'];
			}
		}
		$data = [
			'ptkp'=>$n_ptkp,
			'pkp'=>$n_pkp,
			'pph_tahun'=>$pph_tahun,
			'pph_bulan'=>$pph_bulan,
			'plus_npwp'=>$plus_npwp,
			'pajak'=>$pajak,
		];
		print_r($data);
	}
	function coba5()
	{
		echo '<pre>';
        $start_time = microtime(true);
		$allKar = $this->model_karyawan->getEmployeeAllActive();
		$tanggal_selesai = '2022-10-18';
		$tanggal_mulai =date('Y-m-d', strtotime('-3 days', strtotime($tanggal_selesai)));
		$date_loop=$this->formatter->dateLoopFull($tanggal_mulai,$tanggal_selesai);
		// print_r($allKar);
		if(!empty($allKar)){
			// print_r($allKar);
			$kary = [];
			foreach ($allKar as $d) {
				if($d->jabatan != 'JBT201901160029' && $d->jabatan != 'JBT201901160064' && $d->jabatan != 'JBT201901160067' && $d->jabatan != 'JBT201901160133' && $d->jabatan != 'JBT201909040009' && $d->jabatan != 'JBT202104010001' && $d->jabatan != 'JBT201901160065'){
					// foreach ($date_loop as $key => $date) {
					// 	$libur =  $this->otherfunctions->checkHariLiburActive($date);
					// 	if(!isset($libur)){
					// 		$dtx = $this->model_karyawan->getListAbsensiHarianPrint($date,null,['emp.id_karyawan'=>$d->id_karyawan], true);
					// 		if(empty($dtx['jam_mulai']) && empty($dtx['jam_selesai']) && empty($dtx['kode_hari_libur']) && empty($dtx['kode_ijin']) && empty($dtx['no_spl'])){
					// 			$kary[$d->nama][] = $date;
					// 		}
					// 	}
					// }
					//==================================================================================
					$dtx = $this->model_karyawan->getListAbsensiHarianRange($tanggal_mulai,$tanggal_selesai,null,['emp.id_karyawan'=>$d->id_karyawan], true);
					if(!empty($dtx)){
						foreach ($dtx as $dx) {
							$libur =  $this->otherfunctions->checkHariLiburActive($dx->tanggal);
							if(!isset($libur) && empty($dx->jam_mulai) && empty($dx->jam_selesai) && empty($dx->kode_hari_libur) && empty($dx->kode_ijin) && empty($dx->no_spl)){
								$kary[$d->nama][] = [
									'tanggal'=>$dx->tanggal,
									'jabatan'=>$d->nama_jabatan
								];
							}
						}
					}
				}
			}
			// print_r($kary);
			foreach ($kary as $key => $value) {
				if(count($value) > 2){
					echo $key.' - '.count($value).'<br>';
					// print_r($value);echo '<br>';
					$tgl = [];
					$jabatan = '';
					foreach ($value as $k => $v) {
						$jabatan = $v['jabatan'];
						// print_r($v['tanggal']);echo '<br>';
						$ntg = explode('-', $v['tanggal']);
						$tgl[] = $ntg[2].'/'.$ntg[1];
					}
					asort($tgl);
					$newTgl = implode(', ', $tgl);
					print_r($newTgl);echo '<br>';
					print_r($jabatan);echo '<br>';
				}
			}
		}
        $end_time = microtime(true); 
        $execution_time = ($end_time - $start_time);
        echo " It takes ".$execution_time." seconds to execute the script"; 
		// print_r($date_loop);
	}
	function coba6()
	{
		echo '<pre>';
        $start_time = microtime(true);
		$allKar = $this->model_karyawan->getEmployeeAllActive();
		$tanggal_selesai = '2022-10-18';
		$tanggal_mulai =date('Y-m-d', strtotime('-3 days', strtotime($tanggal_selesai)));
		$date_loop=$this->formatter->dateLoopFull($tanggal_mulai,$tanggal_selesai);
		// print_r($allKar);
		// if(!empty($allKar)){
		// 	// print_r($allKar);
		// 	$kary = [];
		// 	foreach ($allKar as $d) {
		// 		if($d->jabatan != 'JBT201901160029' && $d->jabatan != 'JBT201901160064' && $d->jabatan != 'JBT201901160067' && $d->jabatan != 'JBT201901160133' && $d->jabatan != 'JBT201909040009' && $d->jabatan != 'JBT202104010001' && $d->jabatan != 'JBT201901160065'){
					// foreach ($date_loop as $key => $date) {
					// 	$libur =  $this->otherfunctions->checkHariLiburActive($date);
					// 	if(!isset($libur)){
					// 		$dtx = $this->model_karyawan->getListAbsensiHarianPrint($date,null,['emp.id_karyawan'=>$d->id_karyawan], true);
					// 		if(empty($dtx['jam_mulai']) && empty($dtx['jam_selesai']) && empty($dtx['kode_hari_libur']) && empty($dtx['kode_ijin']) && empty($dtx['no_spl'])){
					// 			$kary[$d->nama][] = $date;
					// 		}
					// 	}
					// }
					//==================================================================================
					$dtx = $this->model_karyawan->getListAbsensiHarianRange($tanggal_mulai,$tanggal_selesai,null,null,true);
					// print_r($dtx);
					if(!empty($dtx)){
						foreach ($dtx as $dx) {
							if($dx->kode_jabatan != 'JBT201901160029' && $dx->kode_jabatan != 'JBT201901160064' && $dx->kode_jabatan != 'JBT201901160067' && $dx->kode_jabatan != 'JBT201901160133' && $dx->kode_jabatan != 'JBT201909040009' && $dx->kode_jabatan != 'JBT202104010001' && $dx->kode_jabatan != 'JBT201901160065'){
								$libur =  $this->otherfunctions->checkHariLiburActive($dx->tanggal);
								if(!isset($libur) && empty($dx->jam_mulai) && empty($dx->jam_selesai) && empty($dx->kode_hari_libur) && empty($dx->kode_ijin) && empty($dx->no_spl)){
									$kary[$dx->nama_karyawan][] = [
										'tanggal'=>$dx->tanggal,
										'jabatan'=>$dx->nama_jabatan
									];
								}
							}
						}
					}
			// 	}
			// }
			// print_r($kary);
			foreach ($kary as $key => $value) {
				if(count($value) > 2){
					echo $key.' - '.count($value).'<br>';
					// print_r($value);echo '<br>';
					$tgl = [];
					$jabatan = '';
					foreach ($value as $k => $v) {
						$jabatan = $v['jabatan'];
						// print_r($v['tanggal']);echo '<br>';
						$ntg = explode('-', $v['tanggal']);
						$tgl[] = $ntg[2].'/'.$ntg[1];
					}
					asort($tgl);
					$newTgl = implode(', ', $tgl);
					print_r($newTgl);echo '<br>';
					print_r($jabatan);echo '<br>';
				}
			}
		// }
        $end_time = microtime(true); 
        $execution_time = ($end_time - $start_time);
        echo " It takes ".$execution_time." seconds to execute the script"; 
		// print_r($date_loop);
	}
	function cobapphxx()
	{
		echo '<pre>';
		$bruto_bulan = '42118285';
		// $bruto_bulan = '421182857';
		// $bruto_bulan = '842365714';
		// $bruto_bulan = '1100000000';
		$npwp = 'qw';
		$n_ptkp = $bruto_bulan;
		$n_pkp = (50/100)*$bruto_bulan;
		$layer_pph   = $this->payroll->getLayerPPHNonKaryawan($n_pkp,$npwp);
		$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$npwp);
		$pph_tahun   = $get_pph['pph_tahun'];
		$pph_bulan   = $get_pph['pph_bulan'];
		$plus_npwp   = $get_pph['plus_npwp'];
		$pajak       = $get_pph['pph_bulan'];
		// print_r($n_pkp);
		print_r($get_pph);
	}
	function cobaperdin()
	{
		echo '<pre>';
		// $cc = 'UM;KAPD0002;NONPLANT;KAPD202104060001';
		// $dd = '150000;50000.00;0;60000.00';
		// $um = $this->otherfunctions->getDataExplode($cc,';','all');
		// $nom = $this->otherfunctions->getDataExplode($dd,';','all');
		// $umx = [];
		// foreach($um as $wt => $va){
		// 	$umx[$va]=$nom[$wt];
		// }
		// print_r($umx);
		// $na = 0;
		// foreach($umx as $key => $val){
		// 	// $tabel.='<td>'.$this->formatter->getFormatMoneyUser($val).'</td>';
		// 	$na=$na+$val; 
		// }
		// print_r($na);
		// $search = [
		// 	'tanggal' => '06/04/2021 12:00 - 06/04/2021 14:01',
		// 	'tanggal_pulang' => '09/04/2021 18:00',
		// 	// 'tanggal_pulang' => '06/04/2021 23:00',
		// ];
		// $nominal='10000';
		// $nominal_tambahan='10000';
		// $jarakMin='80';
		// $jarak='50';
		// $dx = $this->payroll->getTanggalJamPerdin($search,$nominal,$jarakMin,$jarak,$nominal_tambahan);
		// $grade='GRD202204080004';
		// $grade='GRD201908140970';
		$grade='GRD202204080003';
		$id_kar = '48';
		// $id_kar = '111';
		$where = 'emp.id_karyawan="'.$id_kar.'" AND (jbt.kode_bagian="BAG201910160002" OR jbt.kode_bagian="BAG201910170001") AND emp.status_emp="1"';
		// $where = 'emp.grade="'.$grade.'" AND (jbt.kode_bagian="BAG201910160002" OR jbt.kode_bagian="BAG201910170001") AND emp.status_emp="1"';
		$kar = $this->model_karyawan->getEmployeeWhere($where, true);
		if(!empty($kar)){
			$nominalInap = $this->model_karyawan->getTunjanganGradeKetegori($grade,'KAPD202302090001')['nominal'];
			echo '<pre>';
			print_r($kar);
			echo '<br>';
			print_r($nominalInap);
		}
		echo 'kosong';
		// echo '<br>';
	}
	function cobapresensi()
	{		
		echo '<pre>';
		$from = '2022-07-26';
		$to = '2022-08-25';
		$dataEmp = [
			'id_karyawan'=>'220',
			'gaji_pokok'=>'3502800',
			'tgl_masuk'=>'2007-10-12',
			'wfh'=>null,
			'hkwfh'=>null,
			'hknwfh'=>null,
		];
		// $dataPayx = $this->model_presensi->getDetailPresensiForPayroll($dataEmp['id_karyawan'],$from,$to);
		// print_r($dataPayx);
		$dataPay = $this->payroll->getPresensiIzinPayroll($dataEmp,$from,$to);
		$ijin_cuti = $this->payroll->getIjinCutiSimple($dataPay['getIzinCuti']);
		$ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$dataEmp['gaji_pokok'],$dataEmp['tgl_masuk']);
		print_r($dataPay);
		print_r($ijin_cuti);
		print_r($ijin_nominal);
	}
	function cobapinjam()
	{		
		echo '<pre>';
		// $id = '475';
		// $id = '119';
		$id = '538';
		$from = '2021-09-26';
		// $from = '2021-04-26';
		$kode_periode = 'PRP202109250002';
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
		// $to = '2021-09-25';
		// $where = 'a.id_karyawan ="'.$id.'" AND (a.tanggal <="'.$from.'") AND a.status_pinjaman=0';
		// $data = $this->model_payroll->getListPinjaman($where);
		// $nominal =[];
		// $angsuran_kex = [];
		// if(!empty($data)){
		// 	foreach ($data as $d) {
		// 		$ang = $this->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$d->kode_pinjaman],1);
		// 		// print_r($ang);
		// 		$countAng = count($ang);
		// 		$pattern = json_decode($d->pattern);
		// 		$keys = ($countAng+1);
		// 		$nominal_angsuran = $pattern->$keys;
		// 		$nominal[$d->kode_pinjaman] = $nominal_angsuran;
		// 		$angsuran_kex[$d->kode_pinjaman] = $keys;
		// 	}
		// }
		// print_r($periode);
		// $angsuran = $this->payroll->getPinjamanNew('475','01','2021');
		$angsuran = $this->payroll->getPinjamanPayroll($id,$from);
		$jum_angsuran = array_sum($angsuran['nominal']);
		// print_r($angsuran);
		// print_r($jum_angsuran);
		// print_r($angsuran);
		$angsuran_kex = null;
		if(!empty($angsuran)){
			foreach ($angsuran['angsuran_ke'] as $kode_pinjaman => $angsuran_ke) {
				$angsuran_kex = $angsuran_ke;
			}
			foreach ($angsuran['nominal'] as $kode_pinjaman => $nominal) {
				$kode_ang = $this->codegenerator->kodeAngsuran();
				$data_ang = [
					'kode_angsuran'=>$kode_ang,
					'kode_pinjaman'=>$kode_pinjaman,
					'kode_periode'=>$kode_periode,
					'bulan'=>$periode['bulan'],
					'tahun'=>$periode['tahun'],
					'nominal'=>$nominal,
					'keterangan'=>'Potong Gaji Periode '.$this->formatter->getNameOfMonth($periode['bulan']).' '.$periode['tahun'],
				];
				$cekAngsuranIn = $this->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$kode_pinjaman,'a.bulan'=>$periode['bulan'],'a.tahun'=>$periode['tahun']],1);
				// print_r($cekAngsuranIn);
				if(empty($cekAngsuranIn)){
					$data_ang=array_merge($data_ang,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQueryNoMsg($data_ang,'data_angsuran');
				}else{
					$data_ang=array_merge($data_ang,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_ang,'data_angsuran',['kode_pinjaman'=>$kode_pinjaman,'bulan'=>$periode['bulan'],'tahun'=>$periode['tahun']]);
				}
				$cekAngsuran = $this->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$kode_pinjaman],1);
				$dataPinjaman = $this->model_payroll->getListPinjaman(['a.kode_pinjaman'=>$kode_pinjaman],1,true);
				if(count($cekAngsuran) == $dataPinjaman['lama_angsuran']){
					$data_pin = ['status_pinjaman'=>1];
					$data_pin=array_merge($data_pin,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_pin,'data_pinjaman',['kode_pinjaman'=>$kode_pinjaman]);
				}
			}
		}
		// print_r($angsuran_kex);
	}
	function cobalembur()
	{		
		echo '<pre>';
		$periode = [
			'tgl_mulai'=>'2021-03-11',
			'tgl_selesai'=>'2021-04-10',
		];
		$bag = 'BAG201908270009';
		$bulanM=$this->otherfunctions->getDataExplode($periode['tgl_mulai'],'-','end');
		$bulanDepan=$this->otherfunctions->getDataExplode($periode['tgl_selesai'],'-','end');
		$tahunM=$this->otherfunctions->getDataExplode($periode['tgl_mulai'],'-','start');
		$tahunDepan=$this->otherfunctions->getDataExplode($periode['tgl_selesai'],'-','start');
		$d_lembur=$this->model_karyawan->getDataLemburBagianMonth($bag, $bulanM, $tahunM);
		$data_lembur=[];
		$data_lemburx=[];
		$totalPer=0;
		foreach ($d_lembur as $dl) {
			if($dl->kode_bagian == $bag && $dl->tgl_mulai >= $periode['tgl_mulai']){
				$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
				$totalPer+=$this->otherfunctions->pembulatanDepanKoma($nominalLembur);
				// $nl=$totalPer;
				// $data_lembur[5]=$this->formatter->getFormatMoneyUser($nl);
				// $data_lembur[]=[
				// 	'id'=>$dl->id_karyawan,
				// 	'nama_kar'=>$dl->nama_kar,
				// 	'tgl'=>$dl->tgl_mulai,
				// 	'nominal'=>$this->otherfunctions->pembulatanDepanKoma($nominalLembur),
				// ];
			}
		}
		$data_lembur[5]=$this->formatter->getFormatMoneyUser($totalPer);
		$d_lemburx=$this->model_karyawan->getDataLemburBagianMonth($bag, $bulanDepan, $tahunDepan);
		$totalPerx=0;
		foreach ($d_lemburx as $dlx) {
			if($dlx->kode_bagian == $bag && $dlx->tgl_mulai <= $periode['tgl_selesai']){
				$nominalLemburx=$this->payroll->getNominalLemburDate($dlx->id_karyawan, $dlx->tgl_mulai, $dlx->jenis_lembur, $dlx->val_jumlah_lembur, $dlx->val_potong_jam);
				$totalPerx+=$this->otherfunctions->pembulatanDepanKoma($nominalLemburx);
				// $nlx=$totalPerx;
				// $data_lemburx[6]=$this->formatter->getFormatMoneyUser($nlx);
				// $data_lemburx[]=[
				// 	'id'=>$dlx->id_karyawan,
				// 	'nama_kar'=>$dlx->nama_kar,
				// 	'tgl'=>$dlx->tgl_mulai,
				// 	'nominal'=>$this->otherfunctions->pembulatanDepanKoma($nominalLemburx),
				// ];
			}
		}
		$data_lembur[6]=$this->formatter->getFormatMoneyUser($totalPerx);
		print_r($data_lembur);
		print_r($data_lemburx);
	}
	function cobaizin()
	{
		echo '<pre>';
		$idkaryawan = '572';
		$gaji_pokok = '3501984';
		$tgl_masuk = '2007-10-12';
		$from = '2022-08-26';
		$to = '2022-09-25';
		$dataEmp = [
			'id_karyawan'=>$idkaryawan,
			'gaji_pokok'=>$gaji_pokok,
			'tgl_masuk'=>$tgl_masuk,
			'wfh'=>null,
			'hkwfh'=>null,
			'hknwfh'=>null,
		];
		$dataPay = $this->payroll->getPresensiIzinPayroll($dataEmp,$from,$to);
		// $ijin_cuti = $this->payroll->getIjinCutiSimple($dataPay['getIzinCuti']);
		// $ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$gaji_pokok,$tgl_masuk);
		$ijin_cuti = $this->payroll->getIjinCutiSimple($dataPay['getIzinCuti'],$from,$to);
		$ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$gaji_pokok,$tgl_masuk);
		print_r($dataPay);
		// // print_r($dataPay['jumlah_data']);
		// // print_r($ijin_cuti['val_cutiMelahirkan']);
		print_r($ijin_cuti);
		print_r($ijin_nominal);
	}
	function cobaizin_me()
	{
		echo '<pre>';
		$idkaryawan = '220';
		// $idkaryawan = '572';
		$gaji_pokok = '3501984';
		$tgl_masuk = '2007-10-12';
		$from = '2022-08-26';
		$to = '2022-09-25';
		$dataEmp = [
			'id_karyawan'=>$idkaryawan,
			'gaji_pokok'=>$gaji_pokok,
			'tgl_masuk'=>$tgl_masuk,
			'wfh'=>null,
			'hkwfh'=>null,
			'hknwfh'=>null,
		];
		$dataPay = $this->payroll_me->getPresensiIzinPayroll($dataEmp,$from,$to);
		// $ijin_cuti = $this->payroll->getIjinCutiSimple($dataPay['getIzinCuti']);
		// $ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$gaji_pokok,$tgl_masuk);
		$ijin_cuti = $this->payroll_me->getIjinCutiSimple($dataPay['getIzinCuti'],$from,$to);
		$ijin_nominal = $this->payroll_me->getUpahIjinCuti($ijin_cuti,$gaji_pokok,$tgl_masuk);
		print_r($dataPay);
		// print_r($ijin_cuti);
		// print_r($ijin_nominal);
		// // print_r($dataPay['jumlah_data']);
		// // print_r($ijin_cuti['val_cutiMelahirkan']);
		// $dataPayx = $this->model_presensi_me->getDetailPresensiForPayroll($dataEmp['id_karyawan'],$from,$to);
		// foreach ($dataPayx as $d) {
		// 	$vterlambat=$this->payroll_me->getTerlambatPresensi($d->terlambatx);
		// 	print_r($vterlambat);
		// }
		// print_r($dataPayx);
		$tanggal_terlambat=array('2022-09-19','2022-09-20','2022-09-21','2022-09-24');
		$tanggal_izin_terlambat=array('2022-09-20','2022-09-24');
		// $arrayx=[];
		// foreach ($tanggal_izin_terlambat as $key => $value) {
			$arrayx = array_diff($tanggal_terlambat, $tanggal_izin_terlambat);
		// }
		// print_r($arrayx);
		// if (($key = array_search($del_val, $messages)) !== false) {
		// 	unset($messages[$key]);
		// }

	}
	function cobapayroll()
	{
		echo '<pre>';
		$idkaryawan = '363';
		// // $from = '2021-07-26';
		// // $to = '2021-08-25';
		$from = '2022-10-26';
		$to = '2022-11-25';
		// $kode_periode = 'PRP202109200001';
		$dataEmp = [
			'id_karyawan'=>$idkaryawan,
			'gaji_pokok'=>'2923196',
			'tgl_masuk'=>'2012-03-01',
			'wfh'=>null,
			'hkwfh'=>null,
			'hknwfh'=>null,
		];
		// // $getijincutia = $this->model_karyawan->getIzinCuti(null,['a.id_karyawan'=>$idkaryawan,'a.tgl_mulai >='=>$from,'a.tgl_mulai <='=>$to]);
		// // $getijincuti = $this->model_karyawan->getIzinCutiPay($from,['a.id_karyawan'=>$idkaryawan]);
		// // print_r($getijincuti);
		// //================================================
		$dataPay = $this->payroll_me->getPresensiIzinPayroll($dataEmp,$from,$to);
		// $dataPay = $this->payroll->getPresensiIzinPayroll($dataEmp,$from,$to);
		// $ijin_cuti = $this->payroll->getIjinCutiSimple($dataPay['getIzinCuti'],$from,$to);
		// // $getIzinCuti = $this->payroll->getIjinCuti($idkaryawan,$from,$to);
		// // $ijin_cuti = $this->payroll->getIjinCutiSimple($getIzinCuti,$from,$to);
		// $ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$dataEmp['gaji_pokok'],$dataEmp['tgl_masuk']);
		// // // print_r($dataPay['getIzinCuti']['terlambat']);
		print_r($dataPay);
		// // print_r($getIzinCuti);
		// print_r($ijin_cuti);
		// print_r($ijin_nominal);	
		// $kode_periode = 'PRP202109290002';
		// $karyawan = $this->payroll->getKaryawanFromPeriode($kode_periode, $this->dtroot);
		// print_r($karyawan);	
	}
	function cobasyncpay()
	{
		$karyawan = [];
		// if(in_array('all',$emp)){
		// 	$empl = $this->model_payroll->getBagianFromPeriodeGajiBulanan($kode_periode,$bagian);
		// 	foreach ($empl as $kary) {
		// 		$emp_single = $this->model_karyawan->getEmployeeId($kary->id_karyawan);
		// 		$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
		// 	}
		// }else{
		// 	foreach ($emp as $key => $value) {
		// 		$emp_single = $this->model_karyawan->getEmployeeId($value);
		// 		$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
		// 	}
		// }
		$kode_periode = 'PRP202111250004';
		$idkar = '364';
		$emp_single = $this->model_karyawan->getEmployeeId($idkar);
		$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
		if(!empty($karyawan)){
			foreach (array_values(array_filter($karyawan)) as $key => $value) {
				$gaji_pokok = $value['gaji_pokok'];
				$gaji_pokok = (empty($gaji_pokok)) ? 0 : $gaji_pokok;
				/*=== penambah ===*/
				if(!empty($data_insentif)){
					$insentif = $this->payroll->getInsentif($value['id_karyawan']);
					$insentif_val = $this->payroll->getInsentifVal($value['id_karyawan']);
				}else{
					$insentif = 0;
					$insentif_val = null;
				}
				if(!empty($data_ritasi)){
					$dataRitasi = $this->payroll->getRitasi($value['id_karyawan'],$kode_periode);
					$ritasi = $dataRitasi['nominal'];
					$jumlahRitasi = $dataRitasi['jumlah'];
				}else{
					$ritasi = 0;
					$jumlahRitasi = 0;
				}
				$from = $periode['tgl_mulai'];
				$to = $periode['tgl_selesai'];
				$dataEmp = [
					'id_karyawan'=>$value['id_karyawan'],
					'gaji_pokok'=>$gaji_pokok,
					'tgl_masuk'=>$value['tgl_masuk'],
					'wfh'=>$value['wfh'],
					'hkwfh'=>$value['hari_kerja_wfh'],
					'hknwfh'=>$value['hari_kerja_non_wfh'],
				];
				$dataPay = $this->payroll->getPresensiIzinPayroll($dataEmp,$from,$to);
				$uang_makan = $this->payroll->getUangMakanPayroll($dataPay['countPresensi'],$value['id_karyawan'],$dataPay['countlemburLibur']);
				$uang_makan = (empty($uang_makan)) ? 0 : $uang_makan;
				$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);
				$tunjanganTidakTetap = $this->payroll->getTUnjanganNonTetap($value['id_karyawan'],$kode_periode);
				$tunjanganAll = $this->payroll->getTUnjanganAll($value['id_karyawan'],$tunjangan,$tunjanganTidakTetap);
				$tunjangan_val = $this->payroll->getTunjanganVal($tunjanganAll);
				$tunjangan_nominal = $this->payroll->getTunjanganNominalPayrollAll($tunjanganAll);
				$tunjanganTetap = $this->payroll->getTUnjanganTetap($value['id_karyawan']);
				$tunjanganTidakTetap = $this->payroll->getTUnjanganNonTetapPayroll($value['id_karyawan'],$kode_periode);
				$upah = $gaji_pokok;
				// $nominal_penambah = $dataPay['gaji_bersih']+$tunjangan_nominal+$insentif+$ritasi+$uang_makan;
				$nominal_penambah = $dataPay['gaji_bersih']+array_sum($tunjanganTetap)+array_sum($tunjanganTidakTetap)+$insentif+$ritasi+$uang_makan;
				/*=== pengurang ===*/	
				$pot_tidak_masuk = $dataPay['potAlpa'];
				$pot_tidak_masuk = (empty($pot_tidak_masuk)) ? 0 : $this->otherfunctions->nonPembulatan($pot_tidak_masuk);
				$bpjs_jht = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JHT');
				$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JKK-RS');
				$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JKM');
				$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JPNS', $value['id_karyawan']);
				$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_kes'], 'JKES');
				$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
				if(!empty($data_pinjaman)){
					$angsuran = $this->payroll->getPinjamanPayroll($value['id_karyawan'],$periode['tgl_selesai']);
					$jum_angsuran = array_sum($angsuran['nominal']);
				}else{
					$angsuran = null;
					$jum_angsuran = 0;
				}
				// echo '<pre>';
				// print_r($angsuran);
				if(!empty($data_denda)){
					$denda = $this->payroll->getDenda($value['id_karyawan'],$kode_periode);
				}else{
					$denda = [
						'nominal'=>0,
						'angsuran_ke'=>0,
					];
				}
				$ijin_cuti = $this->payroll->getIjinCutiSimple($dataPay['getIzinCuti'],$from,$to);
				$ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$value['gaji_pokok'],$value['tgl_masuk']);
				// echo '<pre>';
				// print_r($dataPay);
				// print_r($ijin_cuti);
				// print_r($ijin_nominal);
				$nominal_ijin_perjam = $ijin_nominal['izin']+$ijin_nominal['iskd']+$ijin_nominal['imp']+$ijin_nominal['terlambat'];
				$nominal_pengurang = $pe_bpjs+$pot_tidak_masuk+$jum_angsuran+$denda['nominal']+$nominal_ijin_perjam;
				if(!empty($data_lain)){
					$data_lain_val      = $this->payroll->getPendukungLain($value['id_karyawan'],$kode_periode);
					$nominal_data_lain  = $this->payroll->getNominalPendukungLain($data_lain_val);
					$dataLainNama       = implode(';', $nominal_data_lain['nama']);
					$dataLainSifat      = implode(';', $nominal_data_lain['sifat']);
					$dataLainNominal    = implode(';', $nominal_data_lain['nominal']);
					$dataLainKeterangan = implode(';', $nominal_data_lain['keterangan']);
					$dataLainHallo      = implode(';', $nominal_data_lain['hallo']);
				}else{
					$nominal_data_lain = [
						'penambah'=>0,
						'pengurang'=>0,
						'nama'=>null,
						'sifat'=>null,
						'nominal'=>null,
					];
					$dataLainNama      = null;
					$dataLainSifat      = null;
					$dataLainNominal    = null;
					$dataLainKeterangan = null;
					$dataLainHallo      = null;
				}
				$gaji_bersihx = ($nominal_penambah-$nominal_pengurang)+$nominal_data_lain['penambah']-$nominal_data_lain['pengurang'];
				if($dataPay['countPresensi'] <= 0 && !empty($ijin_cuti['val_cutiMelahirkan'])){
					$gaji_bersih = $gaji_bersihx;
				}elseif($dataPay['countPresensi'] <= 0 && empty($ijin_cuti['val_cutiMelahirkan']) && empty($ijin_cuti['val_iskdpaid'])){
					$gaji_bersih = 0;
				}else{
					$gaji_bersih = $gaji_bersihx;
				}
				$gaji_bersih = (($gaji_bersih > 0)?$gaji_bersih:0);
				$angsuran_kex = null;
				// if(!empty($angsuran)){
				// 	foreach ($angsuran['angsuran_ke'] as $kode_pinjaman => $angsuran_ke) {
				// 		$angsuran_kex = $angsuran_ke;
				// 	}
				// 	foreach ($angsuran['nominal'] as $kode_pinjaman => $nominal) {
				// 		$kode_ang = $this->codegenerator->kodeAngsuran();
				// 		$data_ang = [
				// 			'kode_angsuran'=>$kode_ang,
				// 			'kode_pinjaman'=>$kode_pinjaman,
				// 			'kode_periode'=>$kode_periode,
				// 			'bulan'=>$periode['bulan'],
				// 			'tahun'=>$periode['tahun'],
				// 			'nominal'=>$nominal,
				// 			'keterangan'=>'Potong Gaji Periode '.$this->formatter->getNameOfMonth($periode['bulan']).' '.$periode['tahun'],
				// 		];
				// 		$cekAngsuranIn = $this->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$kode_pinjaman,'a.bulan'=>$periode['bulan'],'a.tahun'=>$periode['tahun']],1);
				// 		if(empty($cekAngsuranIn)){
				// 			$data_ang=array_merge($data_ang,$this->model_global->getCreateProperties($this->admin));
				// 			$this->model_global->insertQueryNoMsg($data_ang,'data_angsuran');
				// 		}else{
				// 			$data_ang=array_merge($data_ang,$this->model_global->getUpdateProperties($this->admin));
				// 			$this->model_global->updateQueryNoMsg($data_ang,'data_angsuran',['kode_pinjaman'=>$kode_pinjaman,'bulan'=>$periode['bulan'],'tahun'=>$periode['tahun']]);
				// 		}
				// 		$cekAngsuran = $this->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$kode_pinjaman],1);
				// 		$dataPinjaman = $this->model_payroll->getListPinjaman(['a.kode_pinjaman'=>$kode_pinjaman],1,true);
				// 		if(count($cekAngsuran) == $dataPinjaman['lama_angsuran']){
				// 			$data_pin = ['status_pinjaman'=>1];
				// 			$data_pin=array_merge($data_pin,$this->model_global->getUpdateProperties($this->admin));
				// 			$this->model_global->updateQueryNoMsg($data_pin,'data_pinjaman',['kode_pinjaman'=>$kode_pinjaman]);
				// 		}
				// 	}
				// }
				$data = [
					'kode_periode'            =>$kode_periode,
					'nama_periode'            =>$periode['nama'],
					'tgl_mulai'               =>$periode['tgl_mulai'],
					'tgl_selesai'             =>$periode['tgl_selesai'],
					'bulan'             	  =>$periode['bulan'],
					'tahun'             	  =>$periode['tahun'],
					'kode_master_penggajian'  =>$periode['kode_master_penggajian'],
					'id_karyawan'             =>$value['id_karyawan'],
					'nik'                     =>$value['nik'],
					'nama_karyawan'           =>$value['nama'],
					'kode_jabatan'            =>$value['kode_jabatan'],
					'kode_grade'              =>$value['kode_grade'],
					'kode_bagian'             =>$value['kode_bagian'],
					'kode_loker'              =>$value['kode_loker'],
					'tgl_masuk'               =>$value['tgl_masuk'],
					'masa_kerja'              =>$value['masa_kerja'],
					'gaji_pokok'              =>$value['gaji_pokok'],
					'npwp'          		  =>$value['npwp'],
					'tunjangan_val'           =>$tunjangan_val,
					'insentif'                =>$insentif,
					'insentif_val'            =>$insentif_val,
					'ritasi'                  =>$ritasi,
					'jumlah_ritasi'           =>$jumlahRitasi,
					'uang_makan'              =>$this->otherfunctions->nonPembulatan($uang_makan),
					'pot_tidak_masuk'         =>$pot_tidak_masuk,
					'bpjs_jht'                =>$bpjs_jht,
					'bpjs_jkk'                =>$bpjs_jkk,
					'bpjs_jkm'                =>$bpjs_jkm,
					'bpjs_pen'                =>$bpjs_jpns,
					'bpjs_kes'                =>$bpjs_jkes,
					'angsuran'                =>$jum_angsuran,
					'angsuran_ke'             =>$angsuran_kex,//$angsuran['angsuran_ke'],
					'nominal_denda'           =>$denda['nominal'],
					'angsuran_ke_denda'       =>$denda['angsuran_ke'],
					'tunjangan'               =>$this->otherfunctions->nonPembulatan($tunjangan_nominal),
					'tunjangan_tetap'         =>$this->otherfunctions->nonPembulatan(array_sum($tunjanganTetap)),
					'tunjangan_non'           =>$this->otherfunctions->nonPembulatan(array_sum($tunjanganTidakTetap)),
					'data_lain_nama'          =>$dataLainNama,
					'data_lain'               =>$dataLainSifat,
					'nominal_lain'            =>$dataLainNominal,
					'data_lain_hallo'         =>$dataLainHallo,
					'keterangan_lain'         =>$dataLainKeterangan,
					'gaji_bersih'             =>$gaji_bersih,
					'no_rek'                  =>$value['rekening'],
					'meninggalkan_jam_kerja'  =>$ijin_cuti['ijin_per_jam'],
					'meninggalkan_jam_kerja_n'=>$nominal_ijin_perjam,
					'alpha'                   =>implode(";",$dataPay['tidak']),
					'val_iskd'                =>implode(";",$ijin_cuti['val_iskd']),
					'val_izin'                =>implode(";",$ijin_cuti['val_izin']),
					'val_imp'                 =>implode(";",$ijin_cuti['val_imp']),
					'terlambat'				  =>$dataPay['getIzinCuti']['terlambat'],
					'n_terlambat'			  =>$ijin_nominal['terlambat'],
					'tanggal_terlambat'       =>implode(";",$dataPay['tanggal_terlambat']),
					// 'alpha'					 =>$dataPay['countAlpa'],
					// 'alpha'                   =>implode(";",$detail_presensi['alpha']),
					// 'ijin'                    =>implode(";",$detail_presensi['ijin']),
					// 'sakit_skd'               =>implode(";",$detail_presensi['skd']),
					'izin'                    =>$ijin_cuti['izin'],
					'iskd'                    =>$ijin_cuti['iskd'],
					'imp'                     =>$ijin_cuti['imp'],
					'n_izin'                  =>$this->otherfunctions->nonPembulatan($ijin_nominal['izin']),
					'n_imp'                   =>$this->otherfunctions->nonPembulatan($ijin_nominal['imp']),
					'n_iskd'                  =>$this->otherfunctions->nonPembulatan($ijin_nominal['iskd']),
				];
				echo '<pre>';
				print_r($data);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				// $datax = $this->model_global->updateQuery($data,'data_penggajian',['id_karyawan'=>$value['id_karyawan'],'kode_periode'=>$kode_periode]);
			}
		}
	}
	function test($kode_jabatan = null)
	{
		$this->load->view('print_page/coba');
	}
	function cobatest()
	{
		$idkar = '324';
		$bulan = '08';
		$tahun = '2022';
		$totalHari = $this->formatter->cekValueTanggal($bulan, $tahun);
		// $jumlah_minggu = $this->formatter->countMinggu($bulan, $tahun);
		$jumlah_minggu = $this->formatter->countLibur($bulan, $tahun);
		echo '<pre>';
		// print_r($dd);echo '<br>';
		print_r($totalHari-$jumlah_minggu);
		
		// $date1 = "01-10-2022";
		// $date2 = "31-10-2022";
		
		// // memecah bagian-bagian dari tanggal $date1
		// $pecahTgl1 = explode("-", $date1);
		
		// // membaca bagian-bagian dari $date1
		// $tgl1 = $pecahTgl1[0];
		// $bln1 = $pecahTgl1[1];
		// $thn1 = $pecahTgl1[2];
		
		// echo "<p>Tanggal yang merupakan hari minggu adalah:</p>";
		
		// // counter looping
		// $i = 0;
		
		// // counter untuk jumlah hari minggu
		// $sum = 0;
		
		// do
		// {
		// // mengenerate tanggal berikutnya
		// 	$tanggal = date("d-m-Y", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1));
			
		// 	// cek jika harinya minggu, maka counter $sum bertambah satu, lalu tampilkan tanggalnya
		// 	if (date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 0)
		// 	{
		// 		$sum++;
		// 		echo $tanggal."<br>";
		// 	}     
			
		// 	// increment untuk counter looping
		// 	$i++;
		// }
		// while ($tanggal != $date2);   
		
		// // looping di atas akan terus dilakukan selama tanggal yang digenerate tidak sama dengan $date2.
		
		// // tampilkan jumlah hari Minggu
		// echo "<p>Jumlah hari minggu antara ".$date1." s/d ".$date2." adalah: ".$sum."</p>";
		
	}
	function cobasync(){
		echo '<pre>';
		$where="emp.id_karyawan='572'";
		$tanggal = '05/10/2022 - 06/10/2022';
		$tanggal_mulai = $this->formatter->getDateFromRange($tanggal,'start','no');
		$tanggal_selesai = $this->formatter->getDateFromRange($tanggal,'end','no');
		$current_date=$tanggal_mulai;
		while ($current_date <= $tanggal_selesai)
		{
			$dataLogPreAll = $this->model_karyawan->getDataTemporariSync($current_date,$tanggal_selesai,$where);
			if ($dataLogPreAll) {					
				foreach ($dataLogPreAll as $kp => $pre) {
					$data[$current_date][$pre->id_karyawan]['id_karyawan']=$pre->id_karyawan;
					$data[$current_date][$pre->id_karyawan]['tanggal']=$current_date;
					$libur = $this->model_master->cekHariLiburDate($current_date);
					if (isset($libur)) {
						$data[$current_date][$pre->id_karyawan]['kode_hari_libur']=(!empty($libur['kode_hari_libur'])?$libur['kode_hari_libur']:null);
					}else{
						$data[$current_date][$pre->id_karyawan]['kode_hari_libur']=null;
					}
					$izin = $this->model_presensi->cekIzinCutiIdDate($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date);
					if (isset($izin)) {
						$data[$current_date][$pre->id_karyawan]['kode_ijin']=(!empty($izin['kode_izin_cuti'])?$izin['kode_izin_cuti']:null);
					}else{
						$data[$current_date][$pre->id_karyawan]['kode_ijin']=null;
					}
					$dinas=$this->model_karyawan->cekDataPerDinPresensi($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date);
					if ($dinas) {
						$data[$current_date][$pre->id_karyawan]['no_perjalanan_dinas']=(!empty($dinas['no_sk'])?$dinas['no_sk']:null);
					}else{
						$data[$current_date][$pre->id_karyawan]['no_perjalanan_dinas']=null;
					}
					$cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($pre->id_karyawan,$current_date);
					$lembur = $this->model_presensi->checkLemburSyncIDDate($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date);
					if (isset($lembur) && !empty($lembur)) {
						$data[$current_date][$pre->id_karyawan]['no_spl']=$lembur['no_lembur'];
						$cekjadwal['jam_selesai']=$lembur['jam_selesai'];
					}
					$lama_lembur_first = $this->formatter->convertDecimaltoJam(10);
					$lama_lembur = $this->formatter->convertDecimaltoJam(16);
					$cek_lembur_first = $this->model_presensi->cekLemburIdDate($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date,['validasi'=>1,'val_jumlah_lembur >='=>$lama_lembur_first,'val_jumlah_lembur <'=>$lama_lembur]);
					$cek_lembur = $this->model_presensi->cekLemburIdDate($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date,['validasi'=>1,'val_jumlah_lembur >='=>$lama_lembur]);
					if(!empty($cek_lembur_first)){
						$cekjadwal2 = $this->model_presensi->cekJadwalKerjaIdDateJKB($pre->id_karyawan,$current_date);
						$data_jamx=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal2['kode_shift'],'tanggal'=>$current_date,'jam'=>$pre->jam,'jadwal'=>$cekjadwal2,'id_karyawan'=>$pre->id_karyawan]);
						$tgl_mulai = $cek_lembur_first['tgl_mulai'];
						$tgl_selesai = $cek_lembur_first['tgl_selesai'];
						while ($tgl_mulai <= $tgl_selesai)
						{
							$datay['id_karyawan'] = $cek_lembur_first['id_karyawan'];
							$datay['no_spl'] = $cek_lembur_first['no_lembur'];
							$datay['tanggal'] = $tgl_mulai;
							if($tgl_mulai == $cek_lembur_first['tgl_mulai'] && $data_jamx['tanggal'] == $tgl_mulai){
								$data_jam_mulai = null;
								if (isset($data_jamx['jam_mulai'])) {
									$data_jam_mulai=$data_jamx['jam_mulai'];
								}
								$data_jam_selesai = null;
								if (isset($data_jamx['jam_selesai'])) {
									$data_jam_selesai=$data_jamx['jam_selesai'];
								}
								$datay['jam_mulai'] = $data_jam_mulai;
								$datay['jam_selesai'] = $data_jam_selesai;
								if(empty($datay['jam_mulai'])){
									unset($datay['jam_mulai']);
								}
								if(empty($datay['jam_selesai'])){
									unset($datay['jam_selesai']);
								}
							}elseif($tgl_mulai == $cek_lembur_first['tgl_selesai'] && $tgl_mulai != $cek_lembur_first['tgl_mulai']){
								$data_jam_selesai = null;
								if (isset($data_jamx['jam_selesai'])) {
									$data_jam_selesai=$data_jamx['jam_selesai'];
								}
								$datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
								$datay['jam_selesai'] = null;;
							}
							$datay['kode_shift'] = $cekjadwal2['kode_shift'];
							$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
							// print_r($datay);
							if (!empty($datay['id_karyawan']) && !empty($datay['tanggal']) && !empty($datay['kode_shift'])) {
								$cek=$this->model_karyawan->checkPresensiDate($datay['id_karyawan'],$datay['tanggal']);
								if (!$cek) {
									$datax=$this->model_global->insertQuery($datay,'data_presensi');
								}else{
									$datax=$this->model_global->updateQuery($datay,'data_presensi',['tanggal'=>$datay['tanggal'],'id_karyawan'=>$datay['id_karyawan']]);
								}
							}
						}
					}elseif(!empty($cek_lembur)){
						$cekjadwal2 = $this->model_presensi->cekJadwalKerjaIdDateJKB($pre->id_karyawan,$current_date);
						$data_jamx=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal2['kode_shift'],'tanggal'=>$current_date,'jam'=>$pre->jam,'jadwal'=>$cekjadwal2,'id_karyawan'=>$pre->id_karyawan]);
						$tgl_mulai = $cek_lembur['tgl_mulai'];
						$tgl_selesai = $cek_lembur['tgl_selesai'];
						while ($tgl_mulai <= $tgl_selesai)
						{
							$datay['id_karyawan'] = $cek_lembur['id_karyawan'];
							$datay['no_spl'] = $cek_lembur['no_lembur'];
							$datay['tanggal'] = $current_date;
							if($current_date == $cek_lembur['tgl_mulai'] && $current_date != $cek_lembur['tgl_selesai']){
								$data_jam_mulai = null;
								if (isset($data_jamx['jam_mulai'])) {
									$data_jam_mulai=$data_jamx['jam_mulai'];
								}
								$datay['jam_mulai'] = $data_jam_mulai;
								$datay['jam_selesai'] = $cekjadwal2['jam_selesai'];
							}elseif($current_date != $cek_lembur['tgl_mulai'] && $current_date != $cek_lembur['tgl_selesai']){
								$datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
								$datay['jam_selesai'] = $cekjadwal2['jam_selesai'];
							}elseif($current_date == $cek_lembur['tgl_selesai'] && $current_date != $cek_lembur['tgl_mulai']){
								$data_jam_selesai = null;
								if (isset($data_jamx['jam_selesai'])) {
									$data_jam_selesai=$data_jamx['jam_selesai'];
								}
								$datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
								$datay['jam_selesai'] = $data_jam_selesai;
							}else{
								$data_jam_mulai = null;
								if (isset($data_jamx['jam_mulai'])) {
									$data_jam_mulai=$data_jamx['jam_mulai'];
								}else{
									$data_jam_mulai = $cekjadwal2['jam_mulai'];
								}
								$data_jam_selesai = null;
								if (isset($data_jamx['jam_selesai'])) {
									$data_jam_selesai=$data_jamx['jam_selesai'];
								}
								$datay['jam_mulai'] = $data_jam_mulai;
								$datay['jam_selesai'] = $data_jam_selesai;
							}
							$datay['kode_shift'] = $cekjadwal2['kode_shift'];
							$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
							if (!empty($datay['id_karyawan']) && !empty($datay['tanggal']) && !empty($datay['kode_shift'])) {
								$cek=$this->model_karyawan->checkPresensiDate($datay['id_karyawan'],$datay['tanggal']);
								if (!$cek) {
									// $datax=$this->model_global->insertQuery($datay,'data_presensi');
								}else{
									// $datax=$this->model_global->updateQuery($datay,'data_presensi',['tanggal'=>$datay['tanggal'],'id_karyawan'=>$datay['id_karyawan']]);
								}
							}
						}
					}else{
						$data_jam=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal['kode_shift'],'tanggal'=>$current_date,'jam'=>$pre->jam,'jadwal'=>$cekjadwal,'id_karyawan'=>$pre->id_karyawan]);
						$data[$current_date][$pre->id_karyawan]['kode_shift'] = $cekjadwal['kode_shift'];
						if (isset($data_jam['tanggal'])) {
							$data[$data_jam['tanggal']][$pre->id_karyawan]['id_karyawan']=$pre->id_karyawan;
							$data[$data_jam['tanggal']][$pre->id_karyawan]['tanggal']=$data_jam['tanggal'];
							if (isset($data_jam['jam_mulai'])) {
								$data[$data_jam['tanggal']][$pre->id_karyawan]['jam_mulai']=$data_jam['jam_mulai'];
							}
							if (isset($data_jam['jam_selesai'])) {
								$data[$data_jam['tanggal']][$pre->id_karyawan]['jam_selesai']=$data_jam['jam_selesai'];
							}
							if (isset($data_jam['kode_shift'])) {
								$data[$data_jam['tanggal']][$pre->id_karyawan]['kode_shift']=$data_jam['kode_shift'];
							}
						}else{
							$data[$current_date][$pre->id_karyawan]['id_karyawan']=$pre->id_karyawan;
							$data[$current_date][$pre->id_karyawan]['tanggal']=$current_date;
							if (isset($data_jam['jam_mulai'])) {
								$data[$current_date][$pre->id_karyawan]['jam_mulai']=$data_jam['jam_mulai'];
							}
							if (isset($data_jam['jam_selesai'])) {
								$data[$current_date][$pre->id_karyawan]['jam_selesai']=$data_jam['jam_selesai'];
							}
							if (isset($data_jam['kode_shift'])) {
								$data[$current_date][$pre->id_karyawan]['kode_shift']=$data_jam['kode_shift'];
							}
						}
					}			
				}
			}else{
				$dataKar = $this->model_karyawan->getEmployeeWhere($where);
				if(!empty($dataKar)){
					foreach($dataKar as $d){
						$data[$current_date][$d->id_karyawan]['id_karyawan']=$d->id_karyawan;
						$data[$current_date][$d->id_karyawan]['tanggal']=$current_date;
						$libur = $this->model_master->cekHariLiburDate($current_date);
						if (isset($libur)) {
							$data[$current_date][$d->id_karyawan]['kode_hari_libur']=(!empty($libur['kode_hari_libur'])?$libur['kode_hari_libur']:null);
						}
						$izin = $this->model_presensi->cekIzinCutiIdDate($data[$current_date][$d->id_karyawan]['id_karyawan'],$current_date);
						if (isset($izin)) {
							$data[$current_date][$d->id_karyawan]['kode_ijin']=(!empty($izin['kode_izin_cuti'])?$izin['kode_izin_cuti']:null);
						}
						$dinas=$this->model_karyawan->cekDataPerDinPresensi($data[$current_date][$d->id_karyawan]['id_karyawan'],$current_date);
						if ($dinas) {
							$data[$current_date][$d->id_karyawan]['no_perjalanan_dinas']=(!empty($dinas['no_sk'])?$dinas['no_sk']:null);
						}
					}
				}
				// $datax=$this->messages->customFailure('Data Tidak Di Temukan Dalam Log Presensi');
			}
			$current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
		}
		echo '<pre>';
		print_r($data);
		if ($data) {
			foreach ($data as $tanggal => $sub_data) {
				if ($sub_data) {
					foreach ($sub_data as $id_karyawan => $d) {
						$wh=['id_karyawan'=>$id_karyawan,'tanggal'=>$tanggal];
						if (isset($d['jam_mulai'])) {
							$wh['jam_mulai']=$d['jam_mulai'];
						}
						if (isset($d['jam_selesai'])) {
							$wh['jam_selesai']=$d['jam_selesai'];
						}
						$cek=$this->model_presensi->checkPresensiWhere(['id_karyawan'=>$id_karyawan,'tanggal'=>$tanggal]);
						// print_r($cek);
						if (!$cek) {
							$data_in=array_merge($d,$this->model_global->getCreateProperties(1));
							// $datax=$this->model_global->insertQuery($data_in,'data_presensi'); 
						}else{
							if (isset($wh['jam_mulai']) || isset($wh['jam_selesai'])) {
								$data_in=array_merge($d,$this->model_global->getUpdateProperties(1));
								// $datax=$this->model_global->updateQuery($data_in,'data_presensi',['tanggal'=>$tanggal,'id_karyawan'=>$id_karyawan]);
							}else{
								// $datax=$this->messages->allGood(); 
								$data_in=array_merge($d,$this->model_global->getUpdateProperties(1));
								// $datax=$this->model_global->updateQuery($data_in,'data_presensi',['tanggal'=>$tanggal,'id_karyawan'=>$id_karyawan]);
							}
						}
					}
				}
			}
		}
	}
	function cobapphharian(){
		$bulan = '09';
		$tahun = '2021';
		$koreksi = '0';
		$presensi = '0';
		$netto = '2000000';
		$status_pajak = 'TK/0';
		$tarif = $this->payroll->getPajakPertahunHarian($netto,$status_pajak,$presensi);
		print_r($tarif);
		// if($this->dtroot['adm']['level'] != 0){
		// 	$en_access_idKar = $this->payroll->getKaryawanFromPetugasPPH($this->dtroot['adm']['id_karyawan']);
		// 	$or_lv='';
		// 	$c_lv=1;
		// 	foreach ($en_access_idKar as $key => $idkar) {
		// 		$or_lv.="a.id_karyawan='".$idkar."'";
		// 		if (count($en_access_idKar) > $c_lv) {
		// 			$or_lv.=' OR ';
		// 		}
		// 		$c_lv++;
		// 	}
		// 	$where = "a.bulan = '".$bulan."' AND a.tahun = '".$tahun."' and (".$or_lv.")";
		// }else{
		// 	$where = "a.bulan = '".$bulan."' AND a.tahun = '".$tahun."'";
		// }
		// $cekDataGaji = $this->model_payroll->getDataPayrollHarian($where);
		// if(!empty($cekDataGaji)){
		// 	$data = $this->model_payroll->getDataPayrollHarian($where);
		// }else{
		// 	$data = $this->model_payroll->getDataLogPayroll(['a.bulan'=>$bulan,'a.tahun'=>$tahun]);
		// }
		// echo '<pre>';
		// if(!empty($data)){
		// 	$emp = [];	
		// 	foreach ($data as $d) {
		// 		$pengurang_lainx = 0;
		// 		$penambah_lainx = 0;
		// 		$pengurang_lainx_hallo = 0;
		// 		$penambah_lainx_hallo = 0;
		// 		if(!empty($d->data_lain)){
		// 			if (strpos($d->data_lain, ';') !== false) {
		// 				$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
		// 				$dHallo = $this->otherfunctions->getDataExplode($d->data_lain_hallo,';','all');
		// 				$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
		// 				foreach ($dLain as $key => $value) {
		// 					if($value == 'pengurang'){
		// 						if($dHallo[$key] == '1'){
		// 							$pengurang_lainx_hallo += $nLain[$key];
		// 						}else{
		// 							$pengurang_lainx += $nLain[$key];
		// 						}
		// 					}else{
		// 						if($dHallo[$key] == '1'){
		// 							$penambah_lainx_hallo += $nLain[$key];
		// 						}else{
		// 							$penambah_lainx += $nLain[$key];
		// 						}
		// 					}
		// 				}
		// 			}else{
		// 				if($d->data_lain == 'pengurang'){
		// 					if($d->data_lain_hallo == '1'){
		// 						$pengurang_lainx_hallo += $d->nominal_lain;
		// 					}else{
		// 						$pengurang_lainx += $d->nominal_lain;
		// 					}
		// 				}else{
		// 					if($d->data_lain_hallo == '1'){
		// 						$penambah_lainx_hallo += $d->nominal_lain;
		// 					}else{
		// 						$penambah_lainx += $d->nominal_lain;
		// 					}
		// 				}
		// 			}
		// 		}
		// 		$pengurang_lain = $pengurang_lainx;
		// 		$penambah_lain = $penambah_lainx;
		// 		$pengurang_hallo = $pengurang_lainx_hallo;
		// 		$penambah_hallo = $penambah_lainx_hallo;
		// 		$emp[$d->id_karyawan][$d->minggu] = [
		// 			'nik'=>$d->nik,
		// 			'id_karyawan'=>$d->id_karyawan,
		// 			'nama_karyawan'=>$d->nama_karyawan,
		// 			'kode_jabatan'=>$d->kode_jabatan,
		// 			'kode_bagian'=>$d->kode_bagian,
		// 			'kode_loker'=>$d->kode_loker,
		// 			'nama_jabatan'=>$d->nama_jabatan,
		// 			'nama_bagian'=>$d->nama_bagian,
		// 			'nama_loker'=>$d->nama_loker,
		// 			'status_pajak'=>$d->status_pajak,
		// 			'no_ktp'=>$d->no_ktp,
		// 			'npwp'=>$d->npwp,
		// 			'gaji_pokok'=>$d->gaji_pokok,
		// 			'gaji_diterima'=>$d->gaji_diterima,
		// 			'gaji_lembur'=>$d->gaji_lembur,
		// 			'jkk'=>$d->jkk,
		// 			'jkm'=>$d->jkm,
		// 			'jpen'=>$d->jpen,
		// 			'jht'=>$d->jht,
		// 			'jkes'=>$d->jkes,
		// 			'presensi'=>$d->presensi,
		// 			'minggu'=>$d->minggu,
		// 			'pengurang_lain'=>$pengurang_lain,
		// 			'penambah_lain'=>$penambah_lain,
		// 			'pengurang_hallo'=>$pengurang_hallo,
		// 			'penambah_hallo'=>$penambah_hallo,
		// 			'data_lain'=>$d->data_lain,
		// 			'data_lain_nama'=>$d->keterangan_lain,
		// 			'data_lain_hallo'=>$d->data_lain_hallo,
		// 			'nominal_lain'=>$d->nominal_lain,
		// 		];
		// 	}
		// 	// print_r($emp);
		// 	foreach($emp as $idkar => $minggu){
		// 		$karyawan = [];
		// 		$gaji_diterima = 0;
		// 		$gaji_lembur = 0;
		// 		$jkk = 0;
		// 		$jkm = 0;
		// 		$pph = 0;
		// 		$premi = 0;
		// 		$jht = 0;
		// 		$bpjskes = 0;
		// 		$jaminanPensiun = 0;
		// 		$iuranPensiun = 0;
		// 		$potonganJHT = 0;
		// 		$presensi = 0;
		// 		$pengurang_lain = 0;
		// 		$penambah_lain = 0;
		// 		$pengurang_hallo = 0;
		// 		$penambah_hallo = 0;
		// 		for ($i=1; $i < 6; $i++) {
		// 			if(isset($minggu[$i]['minggu'])){
		// 				if($minggu[$i]['minggu'] == $i){
		// 					$id_karyawan=$minggu[$i]['id_karyawan'];
		// 					$nik=$minggu[$i]['nik'];
		// 					$nama_karyawan=$minggu[$i]['nama_karyawan'];
		// 					$kode_jabatan=$minggu[$i]['kode_jabatan'];
		// 					$kode_bagian=$minggu[$i]['kode_bagian'];
		// 					$kode_loker=$minggu[$i]['kode_loker'];
		// 					$nama_jabatan=$minggu[$i]['nama_jabatan'];
		// 					$nama_bagian=$minggu[$i]['nama_bagian'];
		// 					$nama_loker=$minggu[$i]['nama_loker'];
		// 					$status_pajak=$minggu[$i]['status_pajak'];
		// 					$no_ktp=$minggu[$i]['no_ktp'];
		// 					$npwp=$minggu[$i]['npwp'];
		// 					$gaji_per_hari=$minggu[$i]['gaji_pokok'];
		// 					$gaji_diterima+=$minggu[$i]['gaji_diterima'];
		// 					$gaji_lembur+=$minggu[$i]['gaji_lembur'];
		// 					$jkk += $minggu[$i]['jkk'];
		// 					$jkm += $minggu[$i]['jkm'];
		// 					$jht += 0;//$minggu[$i]['jht'];
		// 					$jaminanPensiun += 0;//$minggu[$i]['jpen'];
		// 					$iuranPensiun += $minggu[$i]['jpen'];
		// 					$potonganJHT += $minggu[$i]['jht'];
		// 					$bpjskes += $minggu[$i]['jkes'];
		// 					$presensi += $minggu[$i]['presensi'];
		// 					$pengurang_lain += $minggu[$i]['pengurang_lain'];
		// 					$penambah_lain += $minggu[$i]['penambah_lain'];
		// 					$pengurang_hallo += $minggu[$i]['pengurang_hallo'];
		// 					$penambah_hallo += $minggu[$i]['penambah_hallo'];
		// 					$data_lain=$minggu[$i]['data_lain'];
		// 					$data_lain_nama=$minggu[$i]['data_lain_nama'];
		// 					$data_lain_hallo=$minggu[$i]['data_lain_hallo'];
		// 					$nominal_lain=$minggu[$i]['nominal_lain'];
		// 				}
		// 			}
		// 		}
		// 		$employee = $this->model_karyawan->getEmpID($id_karyawan);
		// 		$gaji_minggu1 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>1,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
		// 		$gajiminggu1 = $gaji_minggu1['gaji_diterima'];
		// 		$lemburminggu1 = $gaji_minggu1['gaji_lembur'];
		// 		$gaji_minggu2 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>2,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
		// 		$gajiminggu2 = $gaji_minggu2['gaji_diterima'];
		// 		$lemburminggu2 = $gaji_minggu2['gaji_lembur'];
		// 		$gaji_minggu3 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>3,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
		// 		$gajiminggu3 = $gaji_minggu3['gaji_diterima'];
		// 		$lemburminggu3 = $gaji_minggu3['gaji_lembur'];
		// 		$gaji_minggu4 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>4,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
		// 		$gajiminggu4 = $gaji_minggu4['gaji_diterima'];
		// 		$lemburminggu4 = $gaji_minggu4['gaji_lembur'];
		// 		$gaji_minggu5 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>5,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
		// 		$gajiminggu5 = $gaji_minggu5['gaji_diterima'];
		// 		$lemburminggu5 = $gaji_minggu5['gaji_lembur'];
		// 		$nominal_kodeAkun = $this->payroll->getKodeAkunPPh($id_karyawan,$bulan,$tahun);
		// 		$nominalPesangon = $this->payroll->getKodeAkunPesangon($id_karyawan,$bulan,$tahun);
		// 		$nominalTHR = $this->payroll->getKodeAkunTHR($id_karyawan,$bulan,$tahun);
		// 		$jumlah_penerimaan = $gaji_diterima+$gaji_lembur+$nominal_kodeAkun+$nominalTHR;
		// 		$bruto = $jumlah_penerimaan+$jkk+$jkm+$bpjskes;
		// 		$netto = $bruto-$iuranPensiun-$potonganJHT;
		// 		// $netto_tahun = $netto*12;
		// 		// $tarif_pajak = $this->payroll->getPajakPertahun($netto_tahun,$status_pajak);
		// 		// $tarif_pajak = ($tarif_pajak > 0)?$tarif_pajak:0;
		// 		// $n_ptkp = $this->payroll->getPTKP($status_pajak);
		// 		// $n_pkp = $netto_tahun-$n_ptkp;
		// 		// $n_pkp = ($n_pkp > 0)?$n_pkp:0;
		// 		// $layer_pph = $this->payroll->getLayerPPH($tarif_pajak,$npwp);
		// 		// $get_pph = $this->payroll->getPPHPertahun($layer_pph,$npwp);
		// 		// $ptkp = $n_ptkp;
		// 		// $pkp = $n_pkp;
		// 		$netto_tahun = $netto*12;
		// 		$tarif = $this->payroll->getPajakPertahunHarian($netto,$employee['status_pajak'],$presensi);
		// 		$n_ptkp = ($tarif['ptkp'] > 0)?$tarif['ptkp']:0;
		// 		$n_pkp = ($tarif['pkp'] > 0)?$tarif['pkp']:0;
		// 		$layer_pph = $this->payroll->getLayerPPHHarian($n_pkp,$npwp);
		// 		$get_pph = $this->payroll->getPPHPertahun($layer_pph,$npwp);
		// 		print_r($layer_pph);
		// 		print_r($get_pph);
		// 		$ptkp = $n_ptkp;
		// 		$pkp = $n_pkp;
		// 		$karyawan = [
		// 			'bulan'=>$bulan,
		// 			'tahun'=>$tahun,
		// 			'id_karyawan'=>$id_karyawan,
		// 			'koreksi'=>$koreksi,
		// 			'nik'=>$nik,
		// 			'nama_karyawan'=>$nama_karyawan,
		// 			'kode_jabatan'=>$kode_jabatan,
		// 			'kode_bagian'=>$kode_bagian,
		// 			'kode_loker'=>$kode_loker,
		// 			'nama_jabatan'=>$nama_jabatan,
		// 			'nama_bagian'=>$nama_bagian,
		// 			'nama_loker'=>$nama_loker,
		// 			'status_pajak'=>$employee['status_pajak'],
		// 			'no_ktp'=>$employee['no_ktp'],
		// 			'npwp'=>$employee['npwp'],
		// 			'gaji_per_hari'=>$gaji_per_hari,
		// 			'gaji_1'=>$gajiminggu1,
		// 			'gaji_2'=>$gajiminggu2,
		// 			'gaji_3'=>$gajiminggu3,
		// 			'gaji_4'=>$gajiminggu4,
		// 			'gaji_5'=>$gajiminggu5,
		// 			'lembur_1'=>$lemburminggu1,
		// 			'lembur_2'=>$lemburminggu2,
		// 			'lembur_3'=>$lemburminggu3,
		// 			'lembur_4'=>$lemburminggu4,
		// 			'lembur_5'=>$lemburminggu5,
		// 			'gaji_diterima'=>$gaji_diterima,
		// 			'gaji_lembur'=>$gaji_lembur,
		// 			'bpjs_jkk_perusahaan'=>$jkk,
		// 			'bpjs_jkm_perusahaan'=>$jkm,
		// 			'bpjs_kes_perusahaan'=>0,
		// 			'bpjs_jht_perusahaan'=>$jht,
		// 			'bpjs_pen_perusahaan'=>$jaminanPensiun,
		// 			'bpjs_pen_pekerja'=>$iuranPensiun,
		// 			'bpjs_jht_pekerja'=>$potonganJHT,
		// 			'bpjs_kes_pekerja'=>$bpjskes,
		// 			'bpjs_jkm_pekerja'=>0,
		// 			'bpjs_jkk_pekerja'=>0,
		// 			'presensi'=>$presensi,
		// 			'pengurang_lain'=>$pengurang_lain,
		// 			'penambah_lain'=>$penambah_lain,
		// 			'pengurang_hallo'=>$pengurang_hallo,
		// 			'penambah_hallo'=>$penambah_hallo,
		// 			'data_lain'=>$data_lain,
		// 			'data_lain_nama'=>$data_lain_nama,
		// 			'data_lain_hallo'=>$data_lain_hallo,
		// 			'nominal_lain'=>$nominal_lain,
		// 			'kode_akun'=>$nominal_kodeAkun,
		// 			'pesangon'=>$nominalPesangon,
		// 			'thr'=>$nominalTHR,
		// 			'jumlah_penerimaan'=>$jumlah_penerimaan,
		// 			'bruto_sebulan'=>$bruto,
		// 			'netto_sebulan'=>$netto,
		// 			'ptkp'=>$ptkp,
		// 			'pkp'=>$pkp,
		// 			'pph_sebulan'=>$get_pph['pph_bulan'],
		// 		];
		// 		$data=array_merge($karyawan,$this->model_global->getCreateProperties($this->admin));
		// 		// $datax = $this->model_global->insertQuery($data,'data_pph_harian');
		// 	}
		// 	// $data = array_merge($emp,$karyawan);
		// }
	}
	function cobapphnon(){
		echo '<pre>';
		// $nik = '332215000024564';
		// $tahun = '2021';
		// $bulan = '09';
		// $netto_sd_bulanIni = 0;
		// $pph_sd_bulanIni = 0;
		// $pph_sd_bulanLalu = 0;
		// $bulan =($bulan<10)?str_replace('0','',$bulan):$bulan;
		// for ($i=1; $i <= $bulan; $i++) { 
		// 	$bulanx =($i<10)?'0'.$i:$i;
		// 	$dataPPh = $this->model_payroll->getListDataPenggajianPphNon(['a.nik'=>$nik,'a.bulan'=>$bulanx,'a.tahun'=>$tahun,],null,null,null,'max',true);
		// 	$netto_sd_bulanIni += $dataPPh['netto_sebulan'];
		// 	$pph_sd_bulanIni += $dataPPh['pph_sebulan'];
		// }
		// for ($ix=1; $ix < $bulan; $ix++) { 
		// 	$bulany =($ix<10)?'0'.$ix:$ix;
		// 	$dataPPhx = $this->model_payroll->getListDataPenggajianPphNon(['a.nik'=>$nik,'a.bulan'=>$bulany,'a.tahun'=>$tahun,],null,null,null,'max',true);
		// 	$pph_sd_bulanLalu += $dataPPhx['pph_sebulan'];
		// }
		// print_r($netto_sd_bulanIni);echo '<br>';
		// print_r($pph_sd_bulanIni);echo '<br>';
		// print_r($pph_sd_bulanLalu);echo '<br>';
		// $nik = '332215000024564';
		$status_pajak = 'TK/0';
		$biaya = '23100000';
		$npwp = '012528840513001';
		$perhitungan_pajak  = 'NON_PTKP';
		$bruto_bulan = ($biaya);
		$bruto_tahun = $bruto_bulan*12;
		$netto_bulan = $bruto_bulan;
		$netto_tahun = $netto_bulan*12;
		if($perhitungan_pajak == 'NON_PTKP'){
			$tarif_pajak = 0;//$this->payroll->getPajakPertahun($bruto_tahun,$status_pajak);
			$n_ptkp 	 = $bruto_bulan;
			$n_pkp 		 = (50/100)*$bruto_bulan;
			print_r($n_pkp);echo '<br>';
			$layer_pph   = $this->payroll->getLayerPPHNonKaryawan($n_pkp,$npwp);
			$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$npwp);
			$pph_tahun   = $get_pph['pph_tahun'];
			$pph_bulan   = $get_pph['pph_bulan'];
			$plus_npwp   = $get_pph['plus_npwp'];
			// $pajak       = $get_pph['pph_bulan'];
			$pajak       = $get_pph['pph_tahun'];
			print_r($get_pph);echo '<br>';
		}else{
			$n_ptkp 	 = $this->payroll->getPTKP($status_pajak);
			$n_pkp 		 = $netto_tahun-$n_ptkp;
			$n_pkp 		 = ($n_pkp > 0)?$n_pkp:0;
			$tarif_pajak = $this->payroll->getPajakPertahun($bruto_tahun,$status_pajak);
			$tarif_pajak = ($tarif_pajak > 0)?$tarif_pajak:0;
			// print_r($tarif_pajak);echo '<br>';
			if($tarif_pajak == 0){
				$pph_tahun   = 0;
				$pph_bulan   = 0;
				$plus_npwp   = 0;
				$pajak       = 0;
			}else{
				// $layer_pph   = $this->payroll->getLayerPPH($bruto_tahun);
				$layer_pph   = $this->payroll->getLayerPPHNonKaryawan($tarif_pajak,$npwp);
				$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$npwp);
				$pph_tahun   = $get_pph['pph_tahun'];
				$pph_bulan   = $get_pph['pph_bulan'];
				$plus_npwp   = $get_pph['plus_npwp'];
				$pajak       = $get_pph['pph_bulan'];
			}
		}
		// print_r($pph_sd_bulanLalu);echo '<br>';
	}
	function cobapphtahunan(){
		echo '<pre>';
		$tahun = '2021';
		$bulan = '09';
		// $nik = '2904820707'; //fara
		// $nik = '2810920218'; //dyra
		$nik = '1303851204'; //marias
		// $nik = '2102770308'; //nadhir
		$maxBulan = 0;
		$dataPPh = [];
		for ($bln=1; $bln <= $bulan; $bln++) { 
			$dtpph = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>$nik,'a.tahun'=>$tahun,'a.bulan'=>$bln],null,null,null,'max',true);
			if(!empty($dtpph)){
				$dataPPh[$bln] = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>$nik,'a.tahun'=>$tahun,'a.bulan'=>$bln],null,null,null,'max',true);
				$maxBulan = $bln;
			}
		}
		$jumdata = 0;
		$bruto_sebulan = 0;
		$netto_sebulan = 0;
		foreach ($dataPPh as $bulan =>$val) {
			$jumdata += 1;
			$bruto_sebulan += $val['bruto_sebulan'];
			$netto_sebulan += $val['netto_sebulan'];
			print_r('bulan '.$bulan.' = '.$val['bruto_sebulan']);echo '<br>';
			// $maxBulan = $bulan;
		}
		if($jumdata < 12){
			$kurangBulan = 12 - $jumdata;
		}else{
			$kurangBulan = 0;
		}
		// print_r($dataPPh);echo '<br>';
		print_r($maxBulan);echo '<br>';
		// print_r($jumdata);echo '<br>';
		$bruto_tahun = ($dataPPh[$jumdata]['bruto_sebulan']*$kurangBulan)+$bruto_sebulan;
		print_r('TOTAL bruto sd bulan berjalan = '.$bruto_sebulan);echo '<br>';
		print_r('Bruto Setahun = '.$bruto_tahun);echo '<br>';
		// print_r($netto_sebulan);echo '<br>';
		// print_r($jumdata);echo '<br>';
		// print_r($kurangBulan);echo '<br>';
		// $karyawan = $this->otherfunctions->getAllKaryawanYear($tahun);
		// foreach ($karyawan as $d) {
			// $dataPPh = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>'2904820707','a.tahun'=>$tahun],null,null,null,'max');
			// print_r($dataPPh);
			// if(!empty($dataPPh)){
			// 	foreach ($dataPPh as $p) {
			// 	}
			// }
		// }
	}
	function cobaCart(){
		$data = $this->cart->contents();
		$total=$this->cart->total_persen();
		// $jml_lembur = '2:00';
		// $lamaLembur = $this->formatter->convertJamtoDecimal($jml_lembur);
		// $idKar = '489';
		// $kar = $this->model_karyawan->getEmployeeId($idKar)['kode_bagian'];
		echo '<pre>';
		print_r($data);
		// print_r($kar);
		echo (int)round((290.15), 0);
		echo 680+240+1080;
		// echo number_format("1000000")."<br>";
		// echo number_format("1000000",2)."<br>";
		// echo number_format("1000000",2,",",".");
	}
	function cobasikap(){
		$tabel = 'agenda_sikap_202211242';
		$idk = '344';
		$report=$this->model_agenda->rumusCustomKubotaFinalResultSikap($tabel,$idk,'report');
		$advance_list=$this->model_agenda->rumusCustomKubotaFinalResultSikap($tabel,$idk,'advance_list');
		echo '<pre>';
		// print_r($report);
		echo '<hr>';
		print_r($advance_list);
	}
	function cobasynccuti(){
		// $datetime = $this->date;
		$datetime = '2023-07-04 12:37:39';
		// $datetime = '2023-01-04 12:37:39';
		echo '<pre>';
		print_r($datetime);
		$date = $this->otherfunctions->syncResetCuti($datetime);
		print_r($date);
		// $date = $this->formatter->getDayMonthYearsHourMinute($datetime);
		// if($date['hari'] == '01' && $date['bulan'] == '01'){
		// 	$emp = $this->model_karyawan->getEmployeeWhere(['emp.status_emp'=>'1']);
		// 	// print_r($emp);
		// 	$CutiBersama=$this->model_master->getCutiBersamaTanggal($date['tahun']);
		// 	$jCB = 0;
		// 	if(!empty($CutiBersama)){
		// 		$jCB = count($CutiBersama);
		// 	}
		// 	$history=$this->model_master->getHistoryResetCuti(['tahun'=>$date['tahun'], 'flag'=>'SYNC JAN']);
		// 	if(empty($history)){
		// 		foreach ($emp as $e) {
		// 			$now=date('Y-m-d');
		// 			$masa_kerja = $this->formatter->getCountDateRange($e->tgl_masuk,$now)['bulan_pay'];
		// 			if ($masa_kerja > 12) {
		// 				$sisa_cuti = ((12-$jCB)+$e->sisa_cuti);
		// 				$data = [
		// 					'sc_old' => $e->sisa_cuti,
		// 					'sisa_cuti' => $sisa_cuti,
		// 				];
		// 				$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$e->id_karyawan]);
		// 				// print_r($masa_kerja.' Bulan => '.$e->sisa_cuti.' | '.$jCB.' | '.$sisa_cuti.' | '.$e->nama.'<br>');
		// 			}else{
		// 				$data = [
		// 					'sc_old' => 0,
		// 					'sisa_cuti' => 0,
		// 				];
		// 				$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$e->id_karyawan]);
		// 			}
		// 		}
		// 		$this->otherfunctions->insertToHistoryResetCuti('SYNC JAN', 'Sinkron Data Cuti Januari Berhasil', $this->date, $date['tahun']);
		// 	}else{
		// 		echo 'Sudah ada data';
		// 	}
		// }elseif($date['hari'] == '01' && $date['bulan'] == '07'){
		// 	$emp = $this->model_karyawan->getEmployeeWhere(['emp.status_emp'=>'1']);
		// 	$CutiBersama=$this->model_master->getCutiBersamaTanggal($date['tahun']);
		// 	$jCB = 0;
		// 	if(!empty($CutiBersama)){
		// 		$jCB = count($CutiBersama);
		// 	}
		// 	$history=$this->model_master->getHistoryResetCuti(['tahun'=>$date['tahun'], 'flag'=>'SYNC JULI']);
		// 	if(empty($history)){
		// 		$cutiReal = (12-$jCB);
		// 		foreach ($emp as $k) {
		// 			$sisaCuti = (($k->sisa_cuti >= $cutiReal) ? $cutiReal : $k->sisa_cuti);
		// 			$datax = $this->model_global->updateQuery(['sisa_cuti' => $sisaCuti],'karyawan',['id_karyawan'=>$k->id_karyawan]);
		// 		}
		// 		$this->otherfunctions->insertToHistoryResetCuti('SYNC JULI', 'Sinkron Data Cuti Juli Berhasil', $this->date, $date['tahun']);
		// 	}else{
		// 		echo 'sudah sink';
		// 	}
        // }else{
		// 	echo 'belum waktunya';
		// }
		
	}
	function cobasendwa(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wapisender.id/api/v1/send-message?api_key=HgBCfhjwX0un7VFvoypCWe16rSV14EQh&device_key=3kw0py&destination=085725951044&message=tes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
	}
	function strukturJabatan($kode_jabatan = null)
	{
		$jbt = $this->model_master->getListJabatanWhere(['a.kode_jabatan'=>$kode_jabatan]);
		$data = '';
		$data = '<ul>';
		foreach ($jbt as $jb) {
			$data .= '<li>';
			$data .= $jb->nama.' ( '.$jb->nama_bagian.' - ['.$jb->nama_loker.'] );';
			$cek = $this->model_master->getListJabatanWhere(['a.atasan'=>$kode_jabatan]);
			if(!empty($jbt)){
				$data .= $this->strukturJabatanLv2($jb->kode_jabatan);
			}
			$data .= '</li>';
		}
		$data .= '</ul>';
		return $data;
	}
	function strukturJabatanLv2($kode_jabatan = null)
	{
		$jbt = $this->model_master->getListJabatanWhere(['a.atasan'=>$kode_jabatan]);
		$data = '';
		if(!empty($jbt)){
			foreach ($jbt as $jb) {
				$data .= $this->strukturJabatan($jb->kode_jabatan);
			}
		}
		return $data;
	}
	function cobaimplode()
	{
		$idkar[] = '2344';
		$kode_petugas_payroll = 'MPP202107220002';
		$dold = $this->model_master->getListPetugasPayrollWhere(['a.kode_petugas_payroll'=>$kode_petugas_payroll],null,1,'a.update_date desc',true);
		if(!empty($dold['id_karyawan'])){
			$expl = $this->otherfunctions->getDataExplode($dold['id_karyawan'],';','all');
			$new = array_merge($idkar, $expl);
			$newIdkar = implode(';', $new);
			$data = [
				'id_karyawan'=>$newIdkar,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQueryNoMsg($data,'master_petugas_payroll',['kode_petugas_payroll'=>$kode_petugas_payroll]);
		}
		echo '<pre>';
		print_r($dold);
	}
	//=============PAGE SETTINGS BEGIN=============//
	public function setting_bobot(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_bobot',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_konversi(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$data=array('access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_konversi',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_update(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot); 
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_update',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_access(){
		if ($this->dtroot['adm']['level'] == 0) {
			$data=array(
				'level_adm'=>$this->dtroot['adm']['level'],
				'access'=>$this->access,
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_access',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_user_group(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$data=array(
				'hak_access'=>$this->model_master->getListAccess(true),
				'access'=>$this->access,
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_user_group',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_menu(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$data=array(
				'access'=>$this->access,
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_menu',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_admin(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			if($this->dtroot['adm']['level'] == 0){
				$level=$this->otherfunctions->getLevelAdminList();
			}else{
				$level=$this->otherfunctions->getLevelAdminList(1);
			}
			$data=[
				'access'=>$this->access,
				'level'=>$level,
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_admin',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function general_setting(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$data=[
				'access'=>$this->access,
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_general',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function hari_kerja_wfh(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$data=[
				'access'=>$this->access,
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_hari_kerja_wfh',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_root_password(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$pass=$this->model_master->getRootPassword(1);
			if (count($pass) == 0) {
				$dt=array('id'=>1,'encrypt'=>$this->codegenerator->genPassword('123456'),'plain'=>'Satusampaienam');
				$this->db->insert('root_password',$dt);
			}
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_root_password',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function backup_restore_database(){
		if ($this->dtroot['adm']['level'] == 0 || $this->dtroot['adm']['level'] == 1) {
			$data=[	'access'=>$this->access,
					'list_table'=>$this->db->list_tables(),
				];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/backup_restore_database',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_notifikasi(){
		if ($this->dtroot['adm']['level'] == 0 ||$this->dtroot['adm']['level'] == 1) {
			$data=array('notif'=>$this->model_master->getListNotif(),
				'lvl'=>$this->dtroot['adm']['level'],
				'id_adm'=>$this->admin,
				'access'=>$this->access,
				'list_admin'=>$this->model_admin->getListAdminActive(),
				'list_emp'=>$this->model_karyawan->getListEmployeeActive(),
				'untuk'=>$this->otherfunctions->getUntukList(),
				'tipe'=>$this->otherfunctions->getTipeNotifikasiList(),
				'sifat'=>$this->otherfunctions->getSifatList(),
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_notifikasi',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function setting_management_perusahaan(){
		if ($this->dtroot['adm']['level'] == 0 ||$this->dtroot['adm']['level'] == 1) {
			$data=['comp'=>$this->model_master->getDataCompany(),
				'lvl'=>$this->dtroot['adm']['level'],
				'id_adm'=>$this->admin,
				'access'=>$this->access,
				'list_admin'=>$this->model_admin->getListAdminActive(),
				'list_emp'=>$this->model_karyawan->getListEmployeeActive(),
				'untuk'=>$this->otherfunctions->getUntukList(),
				'tipe'=>$this->otherfunctions->getTipeNotifikasiList(),
				'sifat'=>$this->otherfunctions->getSifatList(),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/setting_management_perusahaan',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function data_pesan(){
		$data=[
			'id_adm'=>$this->admin,
			'access'=>$this->access,
		];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('pages/data_pesan',$data);
		$this->load->view('admin_tem/footerx');
	}
	public function read_message(){
		$data=[
			'id_adm'=>$this->admin,
			'access'=>$this->access,
		];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('pages/read_message',$data);
		$this->load->view('admin_tem/footerx');
	}
	//===PAGE SETTINGS END===//
	//=================================================BLOCK CHANGE=================================================//
	//===PAGE MASTER BEGIN===//
	//++++++++++++++++++++++++++++++MASTER KARYAWAN++++++++++++++++++++++++++++++//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Level Struktur
	public function master_level_struktur(){
		$nama_menu="master_level_struktur";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_level_struktur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Level Jabatan
	public function master_level_jabatan(){
		$nama_menu="master_level_jabatan";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_level_jabatan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Bidang
	public function master_bidang(){
		$nama_menu="master_bidang";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_bidang',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Bagian
	public function master_bagian(){
		$nama_menu="master_bagian";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_bagian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Jabatan
	public function master_jabatan(){
		$nama_menu="master_jabatan";
		if (in_array($nama_menu, $this->link)) {
			$data=['tipe'=>$this->otherfunctions->getTipeJabatanList(),'periode'=>$this->otherfunctions->getPeriodePenilaianList(),'access'=>$this->access,'yesno'=>$this->otherfunctions->getBoolean()];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_jabatan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Lokasi Kerja
	public function master_loker(){
		$nama_menu="master_loker";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_loker',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Status Karyawan
	public function master_status_karyawan(){
		$nama_menu="master_status_karyawan";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_status_karyawan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Grade
	public function master_induk_grade(){
		$nama_menu="master_induk_grade";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];		
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_induk_grade',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function master_grade(){
		$nama_menu="master_grade";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];		
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_grade',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Sistem Penggajian
	public function master_sistem_penggajian(){
		$nama_menu="master_sistem_penggajian";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_sistem_penggajian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Mutasi Promosi Demosi
	public function master_mutasi_karyawan(){
		$nama_menu="master_mutasi_karyawan";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_mutasi',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Daftar RS
	public function master_daftar_rs(){
		$nama_menu="master_daftar_rs";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_daftar_rs',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Kategori Kecelakaan
	public function master_kategori_kecelakaan(){
		$nama_menu="master_kategori_kecelakaan";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_kategori_kecelakaan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Surat Peringatan
	public function master_surat_peringatan(){
		$nama_menu="master_surat_peringatan";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_surat_peringatan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Surat Perjanjian
	public function master_surat_perjanjian(){
		$nama_menu="master_surat_perjanjian";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_surat_perjanjian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Kelompok Shift
	public function master_kelompok_shift(){
		$nama_menu="master_kelompok_shift";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_kelompok_shift',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Bank
	public function master_bank(){
		$nama_menu="master_bank";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_bank',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Alasan Keluar
	public function master_alasan_keluar(){
		$nama_menu="master_alasan_keluar";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_alasan_keluar',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//++++++++++++++++++++++++++++++MASTER ABSENSI++++++++++++++++++++++++++++++//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Ijin Cuti
	public function master_izin_cuti(){
		$nama_menu="master_izin_cuti";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'yesno'=>$this->otherfunctions->getYesNoList(),
				'izincuti'=>$this->otherfunctions->getIzinCutiList(),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_izin',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Shift
	public function master_shift(){
		$nama_menu="master_shift";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'shift'=>$this->otherfunctions->getYshiftList(),
				'hari'=>$this->otherfunctions->getHariList(),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_shift',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Hari Libur
	public function master_hari_libur(){
		$nama_menu="master_hari_libur";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_hari_libur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Hari Libur
	public function master_cuti_bersama(){
		$nama_menu="master_cuti_bersama";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_cuti_bersama',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Hari Libur
	public function master_kode_akun(){
		$nama_menu="master_kode_akun";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_kode_akun',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Perjalanan DInas
	public function master_perjalanan_dinas(){
		$nama_menu="master_perjalanan_dinas";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,
					'kendaraan_umum'=>$this->otherfunctions->getListKendaraanUmum(),
					'penginapan'=>$this->otherfunctions->getListPenginapan(),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_perjalanan_dinas',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master intensif Perjalanan DInas
	public function view_intensif_perjalanan_dinas(){
		$nama_menu="view_intensif_perjalanan_dinas";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,
					'kendaraan_umum'=>$this->otherfunctions->getListKendaraanUmum(),
				];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_pd_bbm',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Detail Kategori Perjalanan DInas
	public function detail_kategori_perjalanan_dinas(){
		$nama_menu="detail_kategori_perjalanan_dinas";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,
					'penginapan'=>$this->otherfunctions->getListPenginapan(),
				];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_pd_detail_kategori',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//++++++++++++++++++++++++++++++MASTER PENGGAJIAN++++++++++++++++++++++++++++++//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Tarif Lembur
	public function master_tarif_lembur(){
		$nama_menu="master_tarif_lembur";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'jenisLembur'=>$this->otherfunctions->getJenisLembur(),
				'pilihanLembur'=>$this->otherfunctions->getPilihanLembur(),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_tarif_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Tarif UMK
	public function master_tarif_umk(){
		$nama_menu="master_tarif_umk";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_tarif_umk',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//++++++++++++++++++++++++++++++MASTER DOKUMEN++++++++++++++++++++++++++++++//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Dokumen
	public function master_dokumen(){
		$nama_menu="master_dokumen";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_dokumen',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function master_kuota_lembur(){
		$nama_menu="master_kuota_lembur";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_kuota_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_kuota_lembur(){
		$nama_menu="view_kuota_lembur";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
			$nama=$this->model_master->getListKuotaLembur(['a.kode'=>$kode],true)['nama'];
			$data=$this->model_master->getListKuotaLembur(['a.kode'=>$kode]);
			$data=array(
				'kode'=> $kode,
				'nama'=> $nama,
				'data'=> $data,
				'access'=>$this->access,
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_kuota_lembur',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}

	//====================================  MASTER PENILAIAN  ====================================//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Jenis Batasa Poin
		public function master_jenis_batasan_poin(){
			$nama_menu="master_jenis_batasan_poin";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,'kaitan'=>$this->otherfunctions->getKaitanNilaiList(),'jenis_satuan'=>$this->otherfunctions->getJenisSatuanList(),'jenis_kpi'=>$this->otherfunctions->getJenisKpiList(),'sifat'=>$this->otherfunctions->getSifatKpiList()];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_jenis_batasan_poin',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master KPI
		public function master_kpi(){
			$nama_menu="master_kpi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,
					'kaitan'=>$this->otherfunctions->getKaitanNilaiList(),
					'jenis_satuan'=>$this->otherfunctions->getJenisSatuanList(),
					'jenis_kpi'=>$this->otherfunctions->getJenisKpiList(),
					'sifat'=>$this->otherfunctions->getSifatKpiList(),
					'yesno'=>$this->otherfunctions->getYesNoList(),
				];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_kpi',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Form Aspek
		public function master_form_aspek(){
			$nama_menu="master_form_aspek";
			if (in_array($nama_menu, $this->link)) {
				$data=array(
					'asp'=>$this->model_master->getListAspekActive(),
					'tipe_jabatan'=>$this->otherfunctions->getTipeJabatanList(),
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_form_aspek',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Aspek Sikap
		public function master_aspek(){
			$nama_menu="master_aspek";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_aspek',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Bobot Sikap
		public function master_bobot_sikap(){
			$nama_menu="master_bobot_sikap";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_bobot_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Master Kuisioner
		public function master_kuisioner(){
			$nama_menu="master_kuisioner";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$cek=$this->model_master->getAspekKode($kode);
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/master_aspek');
				}else{
					$data=[
						'nama'=>$cek['nama'],
						'kode'=>$cek['kode_aspek'],
						'kuisioner'=>$this->model_master->getKuisionerActiveKodeAspek($kode),
						'tipe_jabatan'=>$this->otherfunctions->getTipeJabatanList(),
						'access'=>$this->access,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/master_kuisioner',$data);
					$this->load->view('admin_tem/footerx');
				}	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Periode Penilaian
		public function master_periode_penilaian(){
			$nama_menu="master_periode_penilaian";
			if (in_array($nama_menu, $this->link)) {
				$range=[];
				for ($i=1; $i < 29 ; $i++) { 
					$range[$i]=$i;
				}
				$data=['access'=>$this->access,'bulan'=>$this->formatter->getMonth(),'range_tgl'=>$range];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_periode_penilaian',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//================= KONVERSI NILAI =================//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Nilai KPI
		public function master_konversi_kpi(){
			$nama_menu="master_konversi_kpi";
			if (in_array($nama_menu, $this->link)) {
				$jenis_add=['AKHIR'=>'Akhir'];
				$data=['access'=>$this->access,'jenis_kpi'=>array_merge($this->otherfunctions->getJenisKpiList(),$jenis_add)];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_konversi_kpi',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Nilai Sikap
		public function master_konversi_sikap(){
			$nama_menu="master_konversi_sikap";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_konversi_sikap',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Nilai Presensi
		public function master_konversi_presensi(){
			$nama_menu="master_konversi_presensi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_konversi_presensi',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Nilai Kuartal
		public function master_konversi_kuartal(){
			$nama_menu="master_konversi_kuartal";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_konversi_kuartal',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Nilai Tahunan
		public function master_konversi_tahunan(){
			$nama_menu="master_konversi_tahunan";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_konversi_tahunan',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Insentif
		public function master_konversi_insentif(){
			$nama_menu="master_konversi_insentif";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_konversi_insentif',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi GAP
		public function master_konversi_gap(){
			$nama_menu="master_konversi_gap";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_konversi_gap',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Rumus
		public function master_rumus(){
			$nama_menu="master_rumus";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/master_rumus',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}

	//===PAGE MASTER END===//
	//=================================================BLOCK CHANGE=================================================//
	//===PAGE PENGELOLAAN KARYAWAN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Data Karyawan
	public function data_karyawan(){
		$nama_menu="data_karyawan";
		if (in_array($nama_menu, $this->link)) {
			$data=[ 'access'=>$this->access,
					'd_level'=>$this->model_master->getListLevelStruktur(true,'val'),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_karyawan',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function add_employee(){
		$nama_menu="add_employee";
		if (in_array($nama_menu, $this->link)) {
			$dtroot['admin']=$this->model_admin->adm($this->admin);
			$skin = $dtroot['admin']['skin'];
			$data=array('emp'=>'aa',
				'skin'=>$skin,
				'gender'=>$this->otherfunctions->getGenderList(),
				'darah'=>$this->otherfunctions->getBloodList(),
				'agama'=>$this->otherfunctions->getReligionList(),
				'status_pajak'=>$this->otherfunctions->getStatusPajakList(),
				'nikah'=>$this->otherfunctions->getStatusNikahList(),
				'pendidikan'=>$this->otherfunctions->getEducateList(),
				'bahasa'=>$this->otherfunctions->getBahasaList(),
				'radio'=>$this->otherfunctions->getRadioList(),
				'baju'=>$this->otherfunctions->getUkuranBajuList(),
				'metode_pph'=>$this->otherfunctions->getMetodePerhitunganList(),
				'gaji_pokok'=>$this->otherfunctions->getJenisGajiList(),
				'access'=>$this->access);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/add_employee',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_employee(){
		$nama_menu="view_employee";
		if (in_array($nama_menu, $this->link)) {
			$nik=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar=$this->model_karyawan->getEmployeeNik($nik);
			$jum_log = $this->db->get_where('log_login_karyawan',array('id_karyawan'=>$kar['id_karyawan']))->num_rows();
			$jumlah_anak = $this->db->get_where('karyawan_anak',['nik'=>$nik])->num_rows();
			$data=array(
				'darah'	=> $this->otherfunctions->getBloodList(),
				'kelamin'=> $this->otherfunctions->getGenderList(),
				'nikah'=> $this->otherfunctions->getStatusNikahList(),
				'agama'=> $this->otherfunctions->getReligionList(),
				'm_pendidikan'=> $this->otherfunctions->getEducateList(),
				'pendidikan'=> $this->otherfunctions->getEducateList(),
				'm_bahasa'=> $this->otherfunctions->getBahasaList(),
				'baju'=>$this->otherfunctions->getUkuranBajuList(),
				'status_pajak'=>$this->otherfunctions->getStatusPajakList(),
				'metode_pph'=>$this->otherfunctions->getMetodePerhitunganList(),
				'gaji_pokok'=>$this->otherfunctions->getJenisGajiList(),
				'bahasa'=>$this->otherfunctions->getBahasaList(),
				'radio'=>$this->otherfunctions->getRadioList(),
				'golongan'=>$this->otherfunctions->getGolonganKaryawanList(),
				'profile'=>$kar,
				'log'=>$this->model_karyawan->log_kar($kar['id_karyawan']),
				'jumlah_log'=> $jum_log,
				// 'jumlah_anak'=>$jumlah_anak,
				'access'=>$this->access,
			);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_karyawan');
			}else{
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_employee',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Mutasi
	public function data_mutasi(){
		$nama_menu="data_mutasi";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_mutasi',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_mutasi(){
		$nama_menu="view_mutasi";
		if (in_array($nama_menu, $this->link)){
			$nik 	=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar 	=$this->model_karyawan->getEmployeeNik($nik);
			$data=array('profile' 		=> $kar,
						'access'		=> $this->access,
						);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_mutasi');
			}else{
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_mutasi',$data);
				$this->load->view('admin_tem/footerx');
				}
		} else {
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Perjanjian Kerja
	public function data_perjanjian_kerja(){
		$nama_menu="data_perjanjian_kerja";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_perjanjian_kerja',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_perjanjian_kerja(){
		$nama_menu="view_perjanjian_kerja";
		if (in_array($nama_menu, $this->link)){
			$nik 	=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar 	=$this->model_karyawan->getEmployeeNik($nik,false);
			$data=array('profile' 		=> $kar,
						'access'		=> $this->access,
			);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_perjanjian_kerja');
			}else{
				$this->load->view('admin_tem/headerx', $this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_perjanjian_kerja', $data);
				$this->load->view('admin_tem/footerx');
			}
		} else {
			redirect('pages/not_found');
		}
	}
	public function cetak_kompensasi()
	{
		parse_str($this->input->post('form'), $form);
		$id_p_status = $form['komp_id'];
		$dataAgree 	= $this->model_karyawan->getPerjanjianKerja($id_p_status, true);
		$menyetujui_1 	= $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$form['komp_menyetujui_1']], true);
		$menyetujui_2 	= $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$form['komp_menyetujui_2']], true);
		$mengetahui 	= $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$form['komp_mengetahui']], true);
		$dibuat 	= $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$form['komp_dibuat']], true);
		$data	= [
			'data' 			=> $dataAgree,
			'access'		=> $this->access,
			'menyetujui_1'	=> $menyetujui_1,
			'menyetujui_2'	=> $menyetujui_2,
			'mengetahui'	=> $mengetahui,
			'dibuat'		=> $dibuat,
			'status'		=> $form['perjanjian_baru'],
			'lama_perjanjian_baru'		=> $form['lama_perjanjian_baru'],
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/cetak_kompensasi',$data);
		$this->load->view('print_page/footer');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Peringatan Kerja
	public function data_peringatan(){
		$nama_menu="data_peringatan";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_peringatan',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_peringatan(){
		$nama_menu="view_peringatan";
		if (in_array($nama_menu, $this->link)){
			$nik 	=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar 	=$this->model_karyawan->getEmployeeNik($nik);
			$data=array('profile' 		=> $kar,
						'access'		=> $this->access,
			);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_peringatan');
			}else{
				$this->load->view('admin_tem/headerx', $this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_peringatan', $data);
				$this->load->view('admin_tem/footerx');
			}
		} else {
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Denda
	public function data_denda(){
		$nama_menu="data_denda";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_denda',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_data_denda(){
		$nama_menu="view_data_denda";
		if (in_array($nama_menu, $this->link)){
			$nik 	=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar 	=$this->model_karyawan->getEmployeeNik($nik);
			$data=array('profile' 		=> $kar,
						'access'		=> $this->access,
			);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_denda');
			}else{
				$this->load->view('admin_tem/headerx', $this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_data_denda', $data);
				$this->load->view('admin_tem/footerx');
			}
		} else {
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	// Grade Karyawan
	public function data_grade(){
		$nama_menu="data_grade";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_grade',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_grade(){
		$nama_menu="view_grade";
		if (in_array($nama_menu, $this->link)){
			$nik 	=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar 	=$this->model_karyawan->getEmployeeNik($nik);
			$data=array('profile' 		=> $kar,
						'access'		=> $this->access,
			);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_grade');
			}else{
				$this->load->view('admin_tem/headerx', $this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_grade', $data);
				$this->load->view('admin_tem/footerx');
			}
		} else {
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Kecelakaan Kerja
	public function data_kecelakaan_kerja(){
		$nama_menu="data_kecelakaan_kerja";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_kecelakaan_kerja',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_kecelakaan_kerja(){
		$nama_menu="view_kecelakaan_kerja";
		if (in_array($nama_menu, $this->link)){
			$nik 	=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar 	=$this->model_karyawan->getEmployeeNik($nik);
			$data=array('profile' 		=> $kar,
						'access'		=> $this->access,
			);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_kecelakaan_kerja');
			}else{
				$this->load->view('admin_tem/headerx', $this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_kecelakaan_kerja', $data);
				$this->load->view('admin_tem/footerx');
			}
		} else {
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Karyawan Non aktif
	public function data_karyawan_non_aktif(){
		$nama_menu="data_karyawan_non_aktif";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access,
				'radio'=>$this->otherfunctions->getRadioList(),
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_karyawan_non_aktif',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_karyawan_non_aktif(){
		$nama_menu="view_karyawan_non_aktif";
		if (in_array($nama_menu, $this->link)) {
			$nik 	=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar 	=$this->model_karyawan->getEmployeeNik($nik, false);
			$data=array(
				'access'=>$this->access,
				'profile' 		=> $kar,
			);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_karyawan_non_aktif');
			}else{
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_karyawan_non_aktif',$data);
				$this->load->view('admin_tem/footerx');
			}
		}else{
			redirect('pages/not_found');
		}
	}
	//===PAGE PENGELOLAAN KARYAWAN END===//
	//=================================================BLOCK CHANGE=================================================//
	//===PAGE ABSENSI BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Jadwal Kerja
	public function data_jadwal_kerja(){
		$nama_menu="data_jadwal_kerja";
		if (in_array($nama_menu, $this->link)) {
			$filter=(!empty($this->access['l_ac']['ftr']))?$this->access['kode_bagian']:null;
			$data=array(
				'access'=>$this->access,
				'karyawan'=>$this->model_karyawan->getEmployeeForSelect2($filter),
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_jadwal_kerja',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Izin / Cuti
	public function data_izin_cuti(){
		$nama_menu="data_izin_cuti";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access,
				'jenis'=>$this->otherfunctions->getIzinCutiList(),
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_izin_cuti',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_izin_cuti(){
		$nama_menu="view_izin_cuti";
		if (in_array($nama_menu, $this->link)) {
			$nik 	=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kar 	=$this->model_karyawan->getEmployeeNik($nik);
			$data=array(
				'access'=>$this->access,
				'profile' 		=> $kar,
				'jenis'=>$this->otherfunctions->getIzinCutiList(),
			);
			if ($data['profile'] == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/data_izin_cuti');
			}else{
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_izin_cuti',$data);
				$this->load->view('admin_tem/footerx');
			}
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_izin_satpam(){
		$nama_menu="view_izin_satpam";
		if (in_array($nama_menu, $this->link)) {
			$data=array(
				'access'=>$this->access,
				// 'jenis'=>$this->otherfunctions->getIzinCutiList(),
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_izin_satpam',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Lembur
	public function data_lembur(){
		$nama_menu="data_lembur";
		if (in_array($nama_menu, $this->link)) {
			$filter=(!empty($this->access['l_ac']['ftr']))?$this->access['kode_bagian']:null;
			$data=['access'=>$this->access,
					'karyawan'=>$this->model_karyawan->getEmployeeForSelect2($filter),
					'jenisLembur'=>$this->otherfunctions->getJenisLembur(),
					'pilihanLembur'=>$this->otherfunctions->getPilihanLembur(),
				];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_lembur(){
		$nama_menu="view_lembur";
		if (in_array($nama_menu, $this->link)) {
			// dammy //
			$nik = $this->codegenerator->decryptChar($this->uri->segment(3));
			$kar = $this->model_karyawan->getEmployeeNik($nik);
			$data = ['profile' => $kar,'access' => $this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_lembur_plan(){
		$nama_menu="view_lembur_plan";
		if (in_array($nama_menu, $this->link)) {
			// dammy //
			$nik = $this->codegenerator->decryptChar($this->uri->segment(3));
			$kar = $this->model_karyawan->getEmployeeNik($nik);
			$data = ['profile' => $kar,'access' => $this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_lembur_plan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function cetak_spl()
	{
		$kodeLembur = $this->input->post('kode_lembur');
		$data_lembur=$this->model_karyawan->getLemburTrans($kodeLembur);
		$data=$this->model_karyawan->getLemburTrans($kodeLembur,true);
		$data =[
			'data_lembur'=>$data_lembur,
			'datax'=>$data,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_spl',$data);
		$this->load->view('print_page/footer');
	}
	public function cetak_izin()
	{
		$id_izin = $this->input->post('id_izin');
		$data=$this->model_karyawan->getIzinCuti($id_izin,null,'row');
		$data =[
			'datax'=>$data,
			'now'=>$this->date,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_izin',$data);
		$this->load->view('print_page/footer');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Presensi
	public function data_presensi(){
		$nama_menu="data_presensi";
		if (in_array($nama_menu, $this->link)) {
			$filter=(!empty($this->access['l_ac']['ftr']))?$this->access['kode_bagian']:null;
			$data=['access'=>$this->access,
				'karyawan'=>$this->model_karyawan->getEmployeeForSelect2($filter),
				'mesin_absen'=>$this->model_global->getMesin(),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_presensi',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_presensi(){
		$nama_menu="view_presensi";
		if (in_array($nama_menu, $this->link)) {
			$nik = $this->codegenerator->decryptChar($this->uri->segment(3));
			$kar = $this->model_karyawan->getEmployeeNik($nik);
			$data = ['profile' => $kar,'access' => $this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_presensi',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function data_presensi_pa(){
		$nama_menu="data_presensi_pa";
		if (in_array($nama_menu, $this->link)) {
			$filter=(isset($this->access['l_ac']['ftr']))?$this->access['kode_bagian']:0;
			$data=['access'=>$this->access,'tahun'=>$this->formatter->getYear(),'bulan'=>$this->formatter->getMonth()];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_presensi_pa',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function cetak_data_log()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$tanggal = $data_filter['tanggal'];
		$bagian = $data_filter['bagian'];
		$karyawan = $data_filter['karyawan'];
		$header = $data_filter['header'];
		if(!empty($tanggal)){
			$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'start')));
			$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'end')));
		}else{
			$tanggal_mulai = date('Y-m-d',strtotime('-1 day',strtotime($this->otherfunctions->getDateNow())));
			$tanggal_selesai = date('Y-m-d',strtotime($this->otherfunctions->getDateNow()));
		}
		$emp = [];
		if(in_array('all',$karyawan)){
			$wherex = '';
			$karyawan = $this->model_karyawan->getKaryawanBagianJoin($bagian,true);
			$c_kr = 1;
			if(!empty($karyawan)){
				foreach ($karyawan as $k) {
					$emp[$k->id_kar]=[
						'nama'=>$k->nama_kar
					];
					$wherex.="a.id_karyawan='".$k->id_kar."' AND a.tanggal BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'";
					if (count($karyawan) > $c_kr) {
						$wherex.=' OR ';
					}
					$c_kr++;
				}
			}
		}else{
			$wherex = '';
			if(!empty($karyawan)){
				$c_lvx=1;
				foreach ($karyawan as $key => $value) {
					$emx = $this->model_karyawan->getEmployeeId($value,true);
					$emp[$value]=[
						'nama'=>$emx['nama'],
					];
					$wherex.="a.id_karyawan='".$value."' AND a.tanggal BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'";
					if (count($karyawan) > $c_lvx) {
						$wherex.=' OR ';
					}
					$c_lvx++;
				}
			}
		}	
		$empx = [];
		$data = $this->model_karyawan->getTemporariWhereNew($wherex);
		if(!empty($data)){
			foreach ($data as $d) {
				$empx[$d->id_karyawan][]=[
					'id_karyawan'=>$d->id_karyawan,
					'id_finger'=>$d->id_finger,
					'tanggal'=>$d->tanggal,
					'jam'=>$d->jam,
				];
			}
		}
		$dataAll = [];
		foreach($emp as $key => $val)
		{
			if(!isset($empx[$key])){
				$dataAll[$val['nama']] = null;
			}else{
			 	$dataAll[$val['nama']] = $empx[$key];
			}
			//  $dataAll[$val['nama']] = array_merge($val, $empx[$key]);
		}
		$data =[
			'data'=>$dataAll,
			'time'=>$this->formatter->getDateTimeFormatUser($this->date),
			'admin'=>$this->dtroot['adm']['nama'],
			'header'=>$header,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_log_presensi',$data);
		$this->load->view('print_page/footer');
	}
	public function cetak_data_presensi()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$tanggal = $data_filter['tanggal'];
		$bagian = $data_filter['bagian'];
		$karyawan = $data_filter['karyawan'];
		$header = $data_filter['header'];
		if(!empty($tanggal)){
			$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'start')));
			$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'end')));
		}else{
			$tanggal_mulai = date('Y-m-d',strtotime('-1 day',strtotime($this->otherfunctions->getDateNow())));
			$tanggal_selesai = date('Y-m-d',strtotime($this->otherfunctions->getDateNow()));
		}
		$emp = [];
		if(in_array('all',$karyawan)){
			$wherex = '';
			$karyawan = $this->model_karyawan->getKaryawanBagianJoin($bagian,true);
			$c_kr = 1;
			if(!empty($karyawan)){
				foreach ($karyawan as $k) {
					$emp[$k->id_kar]=[
						'nama'=>$k->nama_kar
					];
					$wherex.="pre.id_karyawan='".$k->id_kar."' AND pre.tanggal BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'";
					if (count($karyawan) > $c_kr) {
						$wherex.=' OR ';
					}
					$c_kr++;
				}
			}
		}else{
			$wherex = '';
			if(!empty($karyawan)){
				$c_lvx=1;
				foreach ($karyawan as $key => $value) {
					$emx = $this->model_karyawan->getEmployeeId($value,true);
					$emp[$value]=[
						'nama'=>$emx['nama'],
					];
					$wherex.="pre.id_karyawan='".$value."' AND pre.tanggal BETWEEN '".$tanggal_mulai."' AND '".$tanggal_selesai."'";
					if (count($karyawan) > $c_lvx) {
						$wherex.=' OR ';
					}
					$c_lvx++;
				}
			}
		}
		$empx = [];
		$data=$this->model_karyawan->getListPresensiHarianPrint(null, $wherex);
		// echo '<pre>';
		// print_r($data);
		if(!empty($data)){
			foreach ($data as $d) {
				$day=$this->formatter->getNameOfDay($d->tanggal);
				$jam_mulai = (empty($d->jam_mulai_shift)) ? '' : $d->jam_mulai_shift;
				$jam_selesai = (empty($d->jam_selesai_shift)) ? '' : $d->jam_selesai_shift;
				$terlambat = 0;
				if(!empty($d->jam_mulai) && !empty($jam_mulai)){
					$jam_mulai_shift = $jam_mulai;
					if ($d->setting_dispensasi_jam_masuk == 'yes'){
						if ($d->dispensasi){
							$jam_mulai_shift = $d->dispensasi;
						}
					}
					if($d->jam_mulai > $jam_mulai_shift){
						if (($day == "Minggu" || $d->kode_hari_libur) && $d->setting_terlambat == 'no'){
							$terlambat = 0;
						}else{
							$e_terlambat = $this->otherfunctions->getDivTime($jam_mulai_shift,$d->jam_mulai,'time','H:i:s');
							// $terlambat = $this->otherfunctions->getStringInterval($e_terlambat);
							$terlambatx =$this->formatter->convertJamtoDecimal($e_terlambat);
							if($terlambatx >= 0.015 && empty($d->kode_ijin)){
								$terlambat=1;
							}
						}
					}else{
						$terlambat=0;
					}
				}
				$minggu = ($day == "Minggu") ? 1 : 0;
				$libur = $this->otherfunctions->checkHariLiburActive($d->tanggal);
				// $libur = (!empty($libur)) ? ' redColor' : null;
				if($d->nama_jabatan == 'SATPAM'){
					$alpa = (empty($d->jam_mulai) && empty($d->jam_selesai) && empty($d->kode_ijin) && empty($d->kode_hari_libur) && $d->kode_shift != "SSL") ? 1 : 0;
					$liburx = (($d->kode_shift == "SSL") ? 1 : 0);
				}else{
					$alpa = (((empty($d->jam_mulai) && empty($d->jam_selesai)) || ($d->jam_mulai == '00:00:00' && $d->jam_selesai == '00:00:00')) && empty($d->kode_ijin) && empty($d->kode_hari_libur) && empty($libur)) ? 1 : 0;
					$liburx = (!empty($libur) ? 1 : 0);
					// $alpa = (((empty($d->jam_mulai) && empty($d->jam_selesai)) || ($d->jam_mulai == '00:00:00' && $d->jam_selesai == '00:00:00')) && empty($d->kode_ijin) && empty($d->kode_hari_libur) && $day != "Minggu") ? 1 : 0;
				}
				$dinasLuar = ($d->jenis_izin == 'MIC202103290001') ? 1 : 0;
				$cuti = ($d->jenis_izin == 'MIC201907160001') ? 1 : 0;
				$cutiLahir = ($d->jenis_izin == 'MIC201901090008') ? 1 : 0;
				$sdr = ($d->jenis_izin == 'ISKD') ? 1 : 0;
				$izin = ($d->jenis_izin == 'IZIN') ? 1 : 0;
				$imp = ($d->jenis_izin == 'IMP') ? 1 : 0;
				$izinTerlambat = ($d->jenis_izin == 'MIC202210070001') ? 1 : 0;
				$other = ($d->jenis_izin != 'IMP' && $d->jenis_izin != 'MIC202103290001' && $d->jenis_izin != 'MIC201907160001' && $d->jenis_izin != 'MIC201901090008' && $d->jenis_izin != 'ISKD' && $d->jenis_izin != 'IZIN' && $d->jenis_izin != 'MIC202210070001' && !empty($d->jenis_izin)) ? 1 : 0;
				$empx[$d->nik][$d->tanggal]=[
					'id_karyawan'=>$d->id_karyawan,
					'nama_karyawan'=>$d->nama_karyawan,
					'nik'=>$d->nik,
					'nama_jabatan'=>$d->nama_jabatan,
					'nama_bagian'=>$d->nama_bagian,
					'tanggal'=>$d->tanggal,
					'jam_mulai'=>$d->jam_mulai,
					'jam_selesai'=>$d->jam_selesai,
					'terlambat'=>$terlambat,
					'alpa'=>$alpa,
					'dinasLuar'=>$dinasLuar,
					'cutiLahir'=>$cutiLahir,
					'cuti'=>$cuti,
					'sdr'=>$sdr,
					'izin'=>$izin,
					'imp'=>$imp,
					'minggu'=>$minggu,
					'libur'=>$liburx,
					'izinTerlambat'=>$izinTerlambat,
					'izinLain'=>$other,
				];
			}
		}
			// 'header'=>$header,
		$data =[
			'time'=>$this->formatter->getDateTimeFormatUser($this->date),
			'admin'=>$this->dtroot['adm']['nama'],
			'nama_bagian'=>$this->model_master->getBagianKode($bagian)['nama'],
			'tgl_mulai'=>$tanggal_mulai,
			'tgl_selesai'=>$tanggal_selesai,
			'data'=>$empx,
			'dateloop'=>$this->formatter->dateLoopFull($tanggal_mulai,$tanggal_selesai),
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_data_presensi',$data);
		$this->load->view('print_page/footer');
	}
	public function cetakKuotaLembur()
	{
		$kode = $this->uri->segment(3);
		// echo '<pre>';
		$dataK=$this->model_master->getListKuotaLembur(['a.kode'=>$kode], true);
		$detail=$this->model_master->getListDetailKuotaLembur(['a.kode_kuota_lembur'=>$kode]);
		// print_r($dataK);
		$data =[
			'data'=>$dataK,
			'detail'=>$detail,
			'time'=>$this->formatter->getDateTimeFormatUser($this->date),
			'admin'=>$this->dtroot['adm']['nama'],
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_kuota_lembur',$data);
		$this->load->view('print_page/footer');
	}
	public function cetak_data_pinjman()
	{
		$periode = $this->input->post('periode');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		// echo '<pre>';
		// echo $bulan.'<br>';
		// echo $tahun.'<br>';
		// $where=['a.kode_periode'=>$periode];
		$where=['a.bulan'=>$bulan,'a.tahun'=>$tahun];
		$data = $this->model_payroll->getDataLogPayroll($where);
		$angsuran = [];
		if(!empty($data)){
			foreach ($data as $d) {
				$nominalHutangLain = 0;
				$namaHutangLain = 0;
				if(!empty($d->data_lain_nama)){
					$nama=explode(';', $d->data_lain_nama);
					$data_lain=explode(';', $d->data_lain);
					$nominal_lain=explode(';', $d->nominal_lain);
					if(strpos($nama[0], 'HUTANG') !== false || strpos($nama[0], 'HTG') !== false ){
						if($data_lain[0] == 'pengurang'){
							$nominalHutangLain = $nominal_lain[0];
							$namaHutangLain = $nama[0];
						}
					}
				}
				if($d->angsuran != '0.00'){
					$angsuran[]=[
						'nik'=>$d->nik,
						'nama'=>$d->nama_karyawan,
						'nama_jabatan'=>$d->nama_jabatan,
						'nama_bagian'=>$d->nama_bagian,
						'angsuran'=>$d->angsuran,
						'angsuran_ke'=>$d->angsuran_ke,
						'nominalHutangLain'=>$nominalHutangLain,
						'namaHutangLain'=>$namaHutangLain,
					];
				}
			}
		}
		$datax = [
			'bulan'=>$bulan,
			'tahun'=>$tahun,
			'angsuran'=>$angsuran,
			'time'=>$this->formatter->getDateTimeFormatUser($this->date),
			'admin'=>$this->dtroot['adm']['nama'],
		];
		// print_r($angsuran);
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_data_pinjaman',$datax);
		$this->load->view('print_page/footer');
	}
	public function cetak_absensi_harian()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$tanggal = $this->formatter->getDateFormatDb($data_filter['tanggal']);
		$lokasi = $data_filter['lokasi'];
		$mengetahui = $data_filter['mengetahui_ah'];
		$dtd = $this->model_karyawan->getListAbsensiHarianPrint($tanggal,$lokasi);
		$dataIzin = [];
		if(!empty($dtd)){
			foreach ($dtd as $d) {
				$libur =  $this->otherfunctions->checkHariLiburActive($d->tanggal);
				$selisih = $this->otherfunctions->getRangeTimeDate($d->tgl_mulai_izin.' '.$d->jam_mulai_izin, $d->tgl_selesai_izin.' '.$d->jam_selesai_izin, 'hari');
				if(!empty($d->kode_ijin)){
					$dataIzin[] = [
						'nama'=>$d->nama_karyawan,
						'nama_jabatan'=>$d->nama_jabatan,
						'nama_bagian'=>$d->nama_bagian,
						'jenis_izin'=>$d->nama_jenis_izin,
						'jam_mulai'=>$d->jam_mulai_izin,
						'jam_selesai'=>$d->jam_selesai_izin,
						'tanggal'=>$d->tanggal,
						'alasan'=>$d->alasan,
						'jumlah_hari'=>$selisih,
					];
				}elseif(empty($d->jam_mulai) && empty($d->jam_selesai) && empty($d->kode_hari_libur) && empty($d->kode_ijin) && empty($d->no_spl) && !isset($libur) && $d->kode_jabatan != 'JBT201901160029' && $d->kode_jabatan != 'JBT201901160064' && $d->kode_jabatan != 'JBT201901160067' && $d->kode_jabatan != 'JBT201901160133' && $d->kode_jabatan != 'JBT201909040009' && $d->kode_jabatan != 'JBT202104010001' && $d->kode_jabatan != 'JBT201901160065'){
					$dataIzin[] = [
						'nama'=>$d->nama_karyawan,
						'nama_jabatan'=>$d->nama_jabatan,
						'nama_bagian'=>$d->nama_bagian,
						'jenis_izin'=>$d->nama_jenis_izin,
						'jam_mulai'=>$d->jam_mulai_izin,
						'jam_selesai'=>$d->jam_selesai_izin,
						'tanggal'=>$d->tanggal,
						'alasan'=>$d->alasan,
						'kode_jabatan'=>$d->kode_jabatan,
						'jumlah_hari'=>$selisih,
					];
				}
			}
		}
		$datax = [
			'dataIzin'=>$dataIzin,
			'lokasi'=>$this->model_master->getLokerKodeArray($lokasi)['nama'],
			'mengetahui'=>$this->model_karyawan->getEmployeeId($mengetahui)['nama'],
			'time'=>$this->formatter->getDateTimeFormatUser($this->date),
			'admin'=>$this->dtroot['adm']['nama'],
			'tanggal'=>$tanggal,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_absensi_harian',$datax);
		$this->load->view('print_page/footer');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//PERJALANAN DINAS
	public function data_perjalanan_dinas(){
		$nama_menu="data_perjalanan_dinas";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,'tujuan'=>$this->otherfunctions->getTujuanPDList(),
				'kendaraan_umum'=>$this->otherfunctions->getListKendaraanUmum(),
				'penginapan'=>$this->otherfunctions->getListPenginapan(),];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_perjalanan_dinas',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function koreksi_perjalanan_dinas(){
		$noPerdin = $this->codegenerator->decryptChar($this->uri->segment(3));
		// $noPerdin = '030/PERD/VIII/2023';
		// $noPerdin = '025/PERD/V/2023';
		$dataPerKar = $this->model_karyawan->getPerjalananDinasKodeSKGroup($noPerdin);
		$dataPerKarAll = $this->model_karyawan->getPerjalananDinasKodeSK($noPerdin);
		$dKoreksiRow = $this->model_karyawan->getKoreksiPerdin(['no_sk'=>$noPerdin], true);
		$dKoreksiAll = $this->model_karyawan->getKoreksiPerdin(['no_sk'=>$noPerdin]);
		$data=[
			'access'=>$this->access,
			'nomor'=>$noPerdin,
			'data'=>$dataPerKar,
			'dataAll'=>$dataPerKarAll,
			'dKoreksiRow'=>$dKoreksiRow,
			'dKoreksiAll'=>$dKoreksiAll,
		];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('pages/koreksi_perjalanan_dinas',$data);
		$this->load->view('admin_tem/footerx');	
	}
	public function view_perjalanan_dinas(){
		$nama_menu="view_perjalanan_dinas";
		if (in_array($nama_menu, $this->link)) {
			$nik = $this->codegenerator->decryptChar($this->uri->segment(3));
			$kar = $this->model_karyawan->getEmployeeNik($nik);
			$data = [
				'profile' => $kar,
				'access' => $this->access,
				'tujuan'=>$this->otherfunctions->getTujuanPDList(),
				'kendaraan_umum'=>$this->otherfunctions->getListKendaraanUmum(),
				'penginapan'=>$this->otherfunctions->getListPenginapan(),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_perjalanan_dinas',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function cetak_perjalanan_dinas()
	{
		$data_filter = [];
		$noSK = $this->input->post('id');
		$noPerDin    = $this->codegenerator->decryptChar($noSK);
		$perDinTrans = $this->model_karyawan->getPerjalananDinasKodeSK($noPerDin);
		$datax       = $this->otherfunctions->convertResultToRowArray($perDinTrans);
		$koreksiOne  = $this->model_karyawan->getKoreksiPerdin2(['no_sk'=>$noPerDin], true);
		$koreksiAll  = $this->model_karyawan->getKoreksiPerdin2(['no_sk'=>$noPerDin], false);
		$data =[
			'perdin'=>$perDinTrans,
			'datax'=>$datax,
			'dataKodeAkun'=>$this->model_karyawan->getKodeAkunNoSK($noPerDin),
			'koreksiOne'=>$koreksiOne,
			'koreksiAll'=>$koreksiAll,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/cetak_perjalanan_dinas',$data);
		$this->load->view('print_page/footer');
	}
	//===PAGE ABSENSI END===//
	//=================================================BLOCK CHANGE=================================================//
	//===PAGE PENGGAJIAN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Ijin Cuti

	//--------------------------------------------------------------------------------------------------------------//
	//Master Shift

	//--------------------------------------------------------------------------------------------------------------//
	//Master Hari Libur

	//===PAGE PENGGAJIAN END===//
	//=================================================BLOCK CHANGE=================================================//
	//===PAGE PENILAIAN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Ijin Cuti

	//--------------------------------------------------------------------------------------------------------------//
	//Master Shift

	//--------------------------------------------------------------------------------------------------------------//
	//Master Hari Libur

	//===PAGE PENILAIAN END===//


















	public function load_modal_delete()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('table');
		if (!empty($id)) {
			$data['modal']=$this->load->view('_partial/_delete_modal_confirm','',TRUE);
			echo json_encode($data);
		}else{
			echo json_encode($this->messages->sessNotValidParam());
		}
	}
	public function print_page(){
		$pg=$this->input->post('page');
		$bagian=$this->input->post('bagian');
		$loker=$this->input->post('loker');
		if ($pg == "") {
			redirect('pages/dashboard');
		}else{
			$datax=unserialize(base64_decode($this->input->post('data')));
			if (isset($datax['bobot'])) {
				$bobot=$datax['bobot'];
			}else{
				$bobot=NULL;
			}
			if (isset($datax['kalibrasi'])) {
				$kalibrasi=$datax['kalibrasi'];
			}else{
				$kalibrasi=NULL;
			}
			if (isset($loker)) {
				$lok=$loker;
			}else{
				$lok=NULL;
			}
			if (isset($bagian)) {
				$bagi=$bagian;
			}else{
				$bagi=NULL;
			}
			$data=array('data'=>$datax['data'],'bobot'=>$bobot,'kalibrasi'=>$kalibrasi,'periode'=>$datax['periode'],'bagi'=>$bagi,'loker'=>$lok);
			$this->load->view('print_page/header');
			$this->load->view('print_page/'.$pg,$data);
			$this->load->view('print_page/footer');
		}
		
	}
	function not_found(){
		$this->load->view('admin_tem/header');
		$this->load->view('not_found');
		$this->load->view('admin_tem/footer');

	}
	//Notif
	public function read_notification(){
		$kode=$this->uri->segment(3);
		$cek=$this->model_master->k_notif($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->sessNotValidParam();  
			redirect('pages/read_all_notification');
		}else{
			$id=explode(';', $cek['id_read']);
			$id_d=explode(';', $cek['id_del']);
			if (!in_array($this->admin, $id_d)) {
				if (!in_array($this->admin, $id)) {
					array_push($id, $this->admin);
				}
				if (isset($id)) {
					$idd=implode(';', array_unique(array_filter($id)));
				}else{
					$idd=NULL;
				}
				$da=array('id_read'=>$idd);
				$this->db->where('kode_notif',$kode);
				$this->db->update('notification',$da);
				$cek1=$this->model_master->k_notif($kode);
				$data=array('notif'=>$cek1,'access'=>$this->access,);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/read_notif',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				$this->messages->sessNotValidParam();  
				redirect('pages/read_all_notification');
			}
		}
	}
	public function read_all_notification(){
		$cek=$this->model_master->notif();
		$cc=array();
		foreach ($cek as $c) {
			$ccx=explode(';', $c->id_for);
			$ccx_r=explode(';', $c->id_read);
			$ccx_d=explode(';', $c->id_del);
			if (in_array($this->admin,$ccx) && !in_array($this->admin, $ccx_d)) {
				$saax=array('kode'=>$c->kode_notif,'judul'=>$c->judul,'tipe'=>$c->tipe,'sifat'=>$c->sifat,'id_read'=>$c->id_read,'id_del'=>$c->id_del);
				array_push($cc, $saax);
			}
		}
		if (isset($cc)) {
			$saa1=$cc;
		}else{
			$saa1=NULL;
		}
		$data=array('notif'=>$saa1,'adm'=>$this->admin,'access'=>$this->access,);
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('pages/read_all_notif',$data);
		$this->load->view('admin_tem/footerx');
	}
	









	
	
	
	
	
	

	
	//main pages
	public function dashboard(){
		$nama_menu="dashboard";
		if (in_array($nama_menu, $this->link)) {
			$filter=(isset($this->access['l_ac']['ftr']))?$this->access['kode_bagian']:0;
			$data=array(
				'jml_emp'=>count($this->model_karyawan->getListKaryawan()),
				// 'agd_actv'=>0,//(count($this->model_agenda->actv_attd_agenda_t()))+(count($this->model_agenda->agenda_aktif())),
				// 'agd'=>0,//(count($this->model_agenda->log_attd_agenda()))+(count($this->model_agenda->log_agenda())),
				// 'conc'=>0,//(count($this->model_master->attd_c()))+(count($this->model_master->set_ind())),
				'agd_actv'=>(count($this->model_agenda->getAgendaActive('agenda_kpi')))+(count($this->model_agenda->getAgendaActive('agenda_sikap'))),
				'agd'=>(count($this->model_agenda->getListLogAgendaKpi()))+(count($this->model_agenda->getListLogAgendaSikap()))+(count($this->model_agenda->getListLogAgendaReward())),
				'conc'=>(count($this->model_concept->getListKonsepKpi()))+(count($this->model_concept->getListKonsepSikap())),
				'access'=>$this->access,
				'karyawan'=>$this->model_karyawan->getEmployeeForSelect2($filter),
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/index',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function profile(){
		$pro=$this->model_admin->adm($this->admin);
		$log=$this->model_admin->log_login($this->admin);
		$dtp['profile'] = array(
			'id'=>$this->admin,
			'nama'=>$pro['nama'],
			'alamat'=>$pro['alamat'],
			'hp'=>$pro['hp'],
			'email'=>$pro['email'],
			'kelamin'=>$pro['kelamin'],
			'username'=>$pro['username'],
			'foto'=>$pro['foto'],
			'ev'=>$pro['email_verified'],
			'level'=>$pro['level'],
			'create'=>date("d F Y",strtotime($pro['create_date'])),
			'update'=>date("l, d F Y", strtotime($pro['update_date'])).' <i style="color:red;" class="fa fa-clock-o"></i> '.date("H:i:s", strtotime($pro['update_date'])),
			'login'=>date("l, d F Y", strtotime($pro['last_login'])).' <i style="color:red;" class="fa fa-clock-o"></i> '.date("H:i:s", strtotime($pro['last_login'])),
			'log'=>$log,
		);
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('pages/profile',$dtp);
		$this->load->view('admin_tem/footerx');
	}
	//==PRESENSI BEGIN==//
	public function presensi(){
		$nama_menu="presensi";
		if (in_array($nama_menu, $this->link)) {
			$data=array('presensi'=>$this->model_master->list_presensi(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/presensi',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	// public function view_presensi(){
	// 	$nama_menu="view_presensi";
	// 	if (in_array($nama_menu, $this->link)) {
	// 		$kode=$this->uri->segment(3);
	// 		$cek=$this->model_master->cek_presensi($kode);
	// 		if ($cek == "") {
	// 			$this->messages->sessNotValidParam();  
	// 			redirect('pages/presensi');
	// 		}else{
	// 			$agd=$this->model_agenda->cek_agd($cek['kode_agenda']);
	// 			$data=array(
	// 				'view'=>$this->model_master->tb_presensi($cek['nama_tabel']),
	// 				'nama'=>$cek['nama_presensi'],
	// 				'smt'=>$cek['semester'],
	// 				'th'=>$cek['tahun'],
	// 				'tabel'=>$cek['nama_tabel'],
	// 				'kind'=>$cek['kode_indikator'],
	// 				'kode'=>$kode,
	// 				'edit'=>$cek['edit'],
	// 				'agenda'=>$agd,
	// 				'access'=>$this->access,
	// 			);
	// 			$this->load->view('admin_tem/headerx',$this->dtroot);
	// 			$this->load->view('admin_tem/sidebarx',$this->dtroot);
	// 			$this->load->view('pages/view_presensi',$data);
	// 			$this->load->view('admin_tem/footerx');
	// 		}
	// 	}else{
	// 		redirect('pages/not_found');
	// 	}
	// }
	//==PRESENSI END==//
	//==ANGGARAN BEGIN==//
	public function anggaran(){
		$nama_menu="anggaran";
		if (in_array($nama_menu, $this->link)) {
			$data=array('anggaran'=>$this->model_master->list_anggaran(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/anggaran',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_anggaran(){
		$nama_menu="view_anggaran";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$cek=$this->model_master->cek_anggaran($kode);
			if ($cek == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/anggaran');
			}else{
				$agd=$this->model_agenda->cek_agd($cek['kode_agenda']);
				$data=array(
					'view'=>$this->model_master->tb_anggaran($cek['nama_tabel']),
					'nama'=>$cek['nama_anggaran'],
					'smt'=>$cek['semester'],
					'th'=>$cek['tahun'],
					'tabel'=>$cek['nama_tabel'],
					'kode'=>$kode,
					'kind'=>$cek['kode_indikator'],
					'edit'=>$cek['edit'],
					'agenda'=>$agd,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_anggaran',$data);
				$this->load->view('admin_tem/footerx');
			}
		}else{
			redirect('pages/not_found');
		}
	}
	//==ANGGARAN END==//
	//==DENDA BEGIN==//
	public function denda(){
		$nama_menu="denda";
		if (in_array($nama_menu, $this->link)) {
			$data=array('denda'=>$this->model_master->list_denda(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/denda',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
		
	}
	public function view_denda(){
		$nama_menu="view_denda";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$cek=$this->model_master->cek_denda($kode);
			if ($cek == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/denda');
			}else{
				$data=array(
					'view'=>$this->model_master->tb_denda($cek['nama_tabel']),
					'nama'=>$cek['nama_denda'],
					'smt'=>$cek['semester'],
					'th'=>$cek['tahun'],
					'tabel'=>$cek['nama_tabel'],
					'kode'=>$kode,
					'edit'=>$cek['edit'],
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_denda',$data);
				$this->load->view('admin_tem/footerx');
			}
		}else{
			redirect('pages/not_found');
		}
	}
	//==ENDDENDA==//

	//==AGENDA BEGIN==//
	//--list agenda--//
	public function agenda(){
		$nama_menu="agenda";
		if (in_array($nama_menu, $this->link)) {
			$data=array('agenda'=>$this->model_agenda->list_agenda(),'tgl'=>$this->date,'attd'=>$this->model_agenda->list_attd_agenda(),'access'=>$this->access,);
			if (count($data['agenda']) != 0) {
				$x=1;
				foreach ($data['agenda'] as $a) {
					$tgl_mulai[$x]=$a->tgl_mulai;
					$tgl_selesai[$x]=$a->tgl_selesai;
					$x++;
				}
			}else{
				$tgl_mulai[]=0;
				$tgl_selesai[]=0;
			}
			if (count($data['attd']) != 0) {
				$x=1;
				foreach ($data['attd'] as $a1) {
					$tgl_mulai1[$x]=$a1->tgl_mulai;
					$tgl_selesai1[$x]=$a1->tgl_selesai;
					$x++;
				}
			}else{
				$tgl_mulai1[]=0;
				$tgl_selesai1[]=0;
			}
			$data1=array(
				'jm'=>count($data['agenda']),
				'start'=>$tgl_mulai,
				'end'=>$tgl_selesai,
				'jma'=>count($data['attd']),
				'start1'=>$tgl_mulai1,
				'end1'=>$tgl_selesai1,
			);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/agenda',$data);
			$this->load->view('admin_tem/footerx',$data1);
		}else{
			redirect('pages/not_found');
		}
	}
	//--log agenda--//
	public function log_agenda(){
		$nama_menu="log_agenda";
		if (in_array($nama_menu, $this->link)) {
			$data=array('log_agd'=>$this->model_agenda->log_agenda(),'tgl'=>$this->date,'log_attd_agd'=>$this->model_agenda->log_attd_agenda(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/log_agenda',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function result_log_output(){
		$nama_menu="result_log_output";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$dt=$this->model_agenda->cek_log_agd($kode);
			if ($dt == "" || $kode == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/log_agenda');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$dt1=$this->model_agenda->task($nmtb);
				foreach ($dt1 as $d) {
					$res[$d->id_karyawan]=$d->id_karyawan;
					$jabatan[$d->id_karyawan][$d->jabatan]=$d->jabatan;
					$loker[$d->id_karyawan][$d->loker]=$d->loker;
					$ind[$d->id_karyawan][$d->kode_indikator]=$d->kode_indikator;
				}
				foreach ($res as $k) {
					$dat=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
					$kar=$this->model_karyawan->emp($k);
					$nilai[$k]=array();
					$nx=1;
					foreach ($dat as $h) {
						$jbt=$this->model_master->k_jabatan($kar['jabatan']);
						if ($jbt['kode_periode'] == 'BLN') {
							$nax[$nx]=array($h->na1,$h->na2,$h->na3,$h->na4,$h->na5,$h->na6);
							$avg_nx[$nx]=array_sum($nax[$nx])/count($nax[$nx]);
						}else{
							$nax[$nx]=array($h->na6);
							$avg_nx[$nx]=array_sum($nax[$nx])/count($nax[$nx]);
						}
						array_push($nilai[$k], $avg_nx[$nx]);
						$nx++;
					}
					$datax[$k]=array(
						'nilai'=>array_sum($nilai[$k]),
						'nik'=>$kar['nik'],
						'nama'=>$kar['nama'],
						'jabatan'=>implode('',$jabatan[$k]),
						'loker'=>implode('',$loker[$k]),
						'ind'=>count($ind[$k]),
					);
				}
				$data=array(
					'agd'=>$dt,
					'tabel'=>$datax,
					'kode'=>$kode,
					'nmtb'=>$nmtb,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/result_log_output',$data);
				$this->load->view('admin_tem/footerx');
			}		
		}else{
			redirect('pages/not_found');
		}
	}
	public function result_log_sikap(){
		$nama_menu="result_log_sikap";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$dta=$this->db->get_where('log_attd_agenda',array('kode_agenda'=>$kode))->row_array();
			if ($dta == "" || $kode == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/log_agenda');
			}else{
				$nmtb=$dta['tabel_agenda'];
				$dt=$this->model_agenda->task($nmtb);
				foreach ($dt as $d) {
					$kari[$d->id_karyawan]=$d->id_karyawan;
					$k_kuis[$d->id_karyawan][$d->kode_kuisioner]=$d->kode_kuisioner;
					$nik[$d->id_karyawan][$d->nik]=$d->nik;
					$part[$d->id_karyawan][$d->partisipan]=$d->partisipan;
					$aspek[$d->id_karyawan][$d->kode_aspek]=$d->kode_aspek;
					$nilai_ats[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_ats;
					$nilai_bwh[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_bwh;
					$nilai_rkn[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_rkn;
					$nilai_dri[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_dri;
					$r_ats[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_ats;
					$r_bwh[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_bwh;
					$r_rkn[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_rkn;
					$bobot[$d->id_karyawan][$d->kode_aspek]=$d->bobot;
					$b_ats[$d->id_karyawan][$d->bobot_ats]=$d->bobot_ats;
					$b_bwh[$d->id_karyawan][$d->bobot_bwh]=$d->bobot_bwh;
					$b_rkn[$d->id_karyawan][$d->bobot_rkn]=$d->bobot_rkn;
					$nama[$d->id_karyawan]=$d->nama;
					$jabatan[$d->id_karyawan]=$d->jabatan;
					$loker[$d->id_karyawan]=$d->loker;
					$kalibrasi[$d->id_karyawan]=$d->nilai_kalibrasi;
				}
				foreach ($r_bwh as $kb => $vb) {
					foreach ($nilai_bwh[$kb] as $kbw1) {
						if ($kbw1 != NULL) {
							$kbw[$kb]=array_filter(explode(';', $kbw1));
							$nsb=1;
							foreach ($kbw[$kb] as $kbbw1) {
								$kbbw[$kb]=explode(':', $kbbw1);
								$sbw[$kb][$nsb]=$kbbw[$kb][0];
								$nsb++;
							}
						}
					}
					foreach ($aspek[$kb] as $asb) {
						$nb=1;
						foreach ($vb[$asb] as $kab) {
							if ($kab != 0) {
								$bw[$kb][$asb][$nb]=$kab;
							}
							$nb++;
						}
						if (isset($bw[$kb][$asb])) {
							$nbw[$kb][$asb]=(array_sum($bw[$kb][$asb])/count($bw[$kb][$asb]))*($bobot[$kb][$asb]/100);	
						}else{
							$nbw[$kb][$asb]=0;
						}
					}
					$bbwh[$kb]=implode('', $b_bwh[$kb])/100;
					$nabw[$kb]=array_sum($nbw[$kb])*$bbwh[$kb];
				}
				foreach ($r_ats as $ka => $va) {
					foreach ($nilai_ats[$ka] as $kat1) {
						if ($kat1 != NULL) {
							$kat[$ka]=array_filter(explode(';', $kat1));
							$nsa=1;
							foreach ($kat[$ka] as $kbat1) {
								$kbat[$ka]=explode(':', $kbat1);
								$sat[$ka][$nsa]=$kbat[$ka][0];
								$nsa++;
							}
						}
					}
					foreach ($aspek[$ka] as $asa) {
						$na=1;
						foreach ($va[$asa] as $kaa) {
							if ($kaa != 0) {
								$at[$ka][$asa][$na]=$kaa;
							}
							$na++;
						}
						if (isset($at[$ka][$asa])) {
							$nat[$ka][$asa]=(array_sum($at[$ka][$asa])/count($at[$ka][$asa]))*($bobot[$ka][$asa]/100);	
						}else{
							$nat[$ka][$asa]=0;
						}
					}
					$bats[$ka]=implode('', $b_ats[$ka])/100;
					$naat[$ka]=array_sum($nat[$ka])*$bats[$ka];
				}
				foreach ($r_rkn as $kr => $vr) {
					foreach ($nilai_dri[$kr] as $kdr1) {
						if ($kdr1 != 0) {
							$sdr[$kr]=$kr;
						}
					}

					foreach ($nilai_rkn[$kr] as $krk1) {
						if ($krk1 != NULL) {
							$krk[$kr]=array_filter(explode(';', $krk1));
							$nsrk=1;
							foreach ($krk[$kr] as $kbrk1) {
								$kbrk[$kr]=explode(':', $kbrk1);
								$srk[$kr][$nsrk]=$kbrk[$kr][0];
								$nsrk++;
							}
						}
					}
					foreach ($aspek[$kr] as $asr) {
						$nr=1;
						foreach ($vr[$asr] as $kar) {
							if ($kar != 0) {
								$rk[$kr][$asr][$nr]=$kar;
							}
							$nr++;
						}
						if (isset($rk[$kr][$asr])) {
							$nrk[$kr][$asr]=(array_sum($rk[$kr][$asr])/count($rk[$kr][$asr]))*($bobot[$kr][$asr]/100);	
						}else{
							$nrk[$kr][$asr]=0;
						}
					}
					$brkn[$kr]=implode('', $b_rkn[$kr])/100;
					$nark[$kr]=array_sum($nrk[$kr])*$brkn[$kr];
				}
				$data=array(
					'kar'=>$kari,
					'nik'=>$nik,
					'nats'=>$naat,
					'nbwh'=>$nabw,
					'nrkn'=>$nark,
					'lok'=>$loker,
					'kalibrasi'=>$kalibrasi,
					'nm'=>$nama,
					'jbt'=>$jabatan,
					'agd'=>$dta,
					'nmtb'=>$nmtb,
					'kode'=>$kode,
					'pr1'=>$part,
					'access'=>$this->access,
				);
				if (isset($sbw)) {
					$data['sbawah']=$sbw;
				}
				if (isset($sat)) {
					$data['satas']=$sat;
				}
				if (isset($sdr)) {
					$data['sdiri']=array_unique($sdr);
				}
				if (isset($srk)) {
					$data['srekan']=$srk;
				}
				$data['data']=array(
					'kar'=>$kari,
					'nik'=>$nik,
					'nats'=>$naat,
					'nbwh'=>$nabw,
					'nrkn'=>$nark,
					'lok'=>$loker,
					'nm'=>$nama,
					'jbt'=>$jabatan,);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/result_log_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}
		}else{
			redirect('pages/not_found');
		}
	}
	public function log_report_output(){
		$nama_menu="log_report_output";
		if (in_array($nama_menu, $this->link)) {
			$nmtb=$this->uri->segment(3);
			$id=$this->uri->segment(4);
			$cektb=$this->db->get_where('log_agenda',array('tabel_agenda'=>$nmtb))->row_array();
			if ($cektb == 0) {
				$this->messages->sessNotValidParam();  
				redirect('pages/log_agenda');
			}
			$cek=$this->model_agenda->result_value($nmtb,$id);
			if ($cek == "" || $nmtb == "" || $id == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/log_agenda');
			}else{
				if (count($cek) == 0) {
					$this->messages->sessNotValidParam();  
					redirect('pages/log_agenda');
				}
				$agd=$this->db->get_where('log_agenda',array('tabel_agenda'=>$nmtb))->row_array();
				$kar=$this->model_karyawan->emp($id);
				if ($kar['jabatan_pa'] != NULL) {
					$jbt=$this->model_master->jabatan($kar['jabatan_pa']);
				}else{
					$jbt=$this->model_master->k_jabatan($kar['jabatan']);
					
				}
				if ($kar['loker_pa'] != NULL) {
					$lok=$this->model_master->loker($kar['loker_pa']);
				}else{
					$lok=$this->model_master->k_loker($kar['unit']);
				}
				$data=array(
					'profile'=>$kar,
					'log'=>$this->db->get_where('log_login_karyawan',array('id_karyawan'=>$kar['id_karyawan']))->result(),
					'jabatan'=>$jbt,
					'loker'=>$lok,
					'hasil'=>$cek,
					'nmtb'=>$nmtb,
					'agd'=>$agd,
					'idk'=>$id,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/log_report_output',$data);
				$this->load->view('admin_tem/footerx');
			}
		}else{
			redirect('pages/not_found');
		}
	}
	public function log_report_sikap(){
		$nama_menu="log_report_sikap";
		if (in_array($nama_menu, $this->link)) {
			$id=$this->uri->segment(4);
			$kode=$this->uri->segment(3);
			$dt=$this->db->get_where('log_attd_agenda',array('kode_agenda'=>$kode))->row_array();
			if ($dt == "" || $kode == "" || $id == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/log_agenda');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$dti=$this->db->get_where($nmtb,array('id_karyawan'=>$id))->result();
				if (count($dti) == 0) {
					$this->messages->sessNotValidParam();  
					redirect('pages/result_log_sikap/'.$kode);
				}
				$n=1;
				$kr=$this->model_karyawan->emp($id);
				foreach ($dti as $d) {
					$jabatan[$d->jabatan]=$d->jabatan;
					$loker[$d->loker]=$d->loker;
					$kode_k[$d->kode_aspek]=$d->kode_aspek;
					$rt_a[$d->kode_aspek][$n]=$d->rata_ats;
					$rt_b[$d->kode_aspek][$n]=$d->rata_bwh;
					$rt_r[$d->kode_aspek][$n]=$d->rata_rkn;
					$nilai_d[$d->kode_aspek][$n]=$d->nilai_dri;
					$bobot[$d->kode_aspek][$n]=$d->bobot;
					$b_ats[$d->bobot_ats]=$d->bobot_ats;
					$b_bwh[$d->bobot_bwh]=$d->bobot_bwh;
					$b_rkn[$d->bobot_rkn]=$d->bobot_rkn;
					$part[$d->partisipan]=$d->partisipan;
					$n++;
				}
				foreach ($part as $p) {
					$p1=explode(';', $p);
					foreach ($p1 as $a) {
						$a1=explode(':', $a);
						if ($a1[0] != "DRI") {
							$a2[]=$a1[0];
						}
					}
				}
				$pp1=array_unique(array_filter($a2));
				array_push($pp1, "DRI");
				$pp=implode(';', $pp1);	
				$data=array(
					'profile'=>$kr,
					'id'=>$id,
					'agd'=>$dt,
					'part'=>$pp,
					'bobot'=>$bobot,
					'jabatan'=>implode('', $jabatan),
					'loker'=>implode('', $loker),
					'asp'=>$kode_k,
					'n_dri'=>$nilai_d,
					'rt_ats'=>$rt_a,
					'rt_bwh'=>$rt_b,
					'rt_rkn'=>$rt_r,
					'b_ats'=>implode('', $b_ats),
					'b_bwh'=>implode('', $b_bwh),
					'b_rkn'=>implode('', $b_rkn),
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/log_report_sikap',$data);
				$this->load->view('admin_tem/footerx');			
			}
		}else{
			redirect('pages/not_found');
		}
	}
	public function log_report_sikap_detail(){
		$nama_menu="log_report_sikap_detail";
		if (in_array($nama_menu, $this->link)) {
			$id=$this->uri->segment(5);
			$kode=$this->uri->segment(3);
			$kode_a=$this->uri->segment(4);
			$dt=$this->db->get_where('log_attd_agenda',array('kode_agenda'=>$kode))->row_array();
			if ($dt == "" || $kode == "" || $id == "" || $kode_a == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/log_agenda');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$dti=$this->db->get_where($nmtb,array('id_karyawan'=>$id,'kode_aspek'=>$kode_a))->result();
				if (count($dti) == 0) {
					$this->messages->sessNotValidParam();  
					redirect('pages/result_log_sikap/'.$kode);
				}
				$n=1;
				$kr=$this->model_karyawan->emp($id);
				foreach ($dti as $d) {
					$kode_k[$d->kode_aspek]=$d->kode_aspek;
					$kuis[$n]=$d->kuisioner;
					$rt_a[$n]=$d->rata_ats;
					$rt_b[$n]=$d->rata_bwh;
					$rt_r[$n]=$d->rata_rkn;
					$nilai_a[$n]=$d->nilai_ats;
					$nilai_b[$n]=$d->nilai_bwh;
					$nilai_r[$n]=$d->nilai_rkn;
					$nilai_d[$n]=$d->nilai_dri;
					$ket_d[$n]=$d->keterangan_dri;
					$ket_a[$n]=$d->keterangan_ats;
					$ket_b[$n]=$d->keterangan_bwh;
					$ket_r[$n]=$d->keterangan_rkn;
					$part[$d->partisipan]=$d->partisipan;
					$n++;
				}
				$pp1=implode('', $part);
				$pp=explode(';', $pp1);
				foreach ($pp as $px) {
					$px1=explode(':', $px);
					$px2[]=$px1[0];
				}
				$ppx1=implode(';', array_unique($px2));
				$ppx=explode(';', $ppx1);
				$aspek=$this->model_master->cek_aspek($kode_a);
				$data=array(
					'nama'=>$kr['nama'],
					'id'=>$id,
					'agd'=>$dt,
					'aspek'=>$aspek,
					'part'=>$pp,
					'partx'=>$ppx,
					'asp'=>$kode_k,
					'kuis'=>$kuis,
					'n_dri'=>$nilai_d,
					'n_ats'=>$nilai_a,
					'n_bwh'=>$nilai_b,
					'n_rkn'=>$nilai_r,
					'rt_ats'=>$rt_a,
					'rt_bwh'=>$rt_b,
					'rt_rkn'=>$rt_r,
					'k_dri'=>$ket_d,
					'k_ats'=>$ket_a,
					'k_bwh'=>$ket_b,
					'k_rkn'=>$ket_r,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/log_report_sikap_detail',$data);
				$this->load->view('admin_tem/footerx'); 
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	//==END AGENDA==//
	//==SIKAP==//
	public function concept_sikap(){
		$nama_menu="concept_sikap";
		if (in_array($nama_menu, $this->link)) {
			$data=array('attd'=>$this->model_master->attd_c(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/concept_sikap',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_concept_sikap(){
		$nama_menu="view_concept_sikap";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$cek=$this->model_master->cek_attd($kode);
			if ($cek == "" || $kode == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/concept_sikap');
			}else{
				$data=array(
					'nama'=>$cek['nama'],
					'tabel'=>$this->model_master->tb_attd($cek['nama_tabel']),
					'kode'=>$kode,
					'access'=>$this->access,

				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_concept_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_attitude_partisipant(){
		$nama_menu="view_attitude_partisipant";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$nik=$this->uri->segment(4);
			$cek=$this->model_master->cek_attd($kode);
			if ($cek == "" || $kode == "" || $nik == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/concept_sikap');
			}else{
				$cek1=$this->db->get_where($cek['nama_tabel'],array('nik'=>$nik))->num_rows();
				if ($cek1 == 0) {
					$this->messages->sessNotValidParam();  
					redirect('pages/view_concept_sikap/'.$kode);
				}
				$kar=$this->model_karyawan->emp_nik($nik);
				$data=array(
					'nama'=>$cek['nama'],
					'tabel'=>$this->model_master->tb_k_attd($cek['nama_tabel'],$nik),
					'kode'=>$kode,
					'emp'=>$kar,
					'access'=>$this->access,
				);
				$data1['jum_part']=count(array_filter(explode(';', $data['tabel']['partisipan'])));
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_attitude_partisipant',$data);
				$this->load->view('admin_tem/footerx',$data1);
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	//==END SIKAP==//
	//==SETTING INDIKATOR==//
	public function concept(){
		$nama_menu="concept";
		if (in_array($nama_menu, $this->link)) {
			$data=array('set'=>$this->model_master->set_ind(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/concept',$data);
			$this->load->view('admin_tem/footerx');
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_concept(){
		$nama_menu="view_concept";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$cek=$this->model_master->cek_set($kode);
			if ($cek == "" || $kode == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/concept');
			}else{
				$data=array(
					'nama'=>$cek['nama'],
					'tabel'=>$cek['nama_tabel'],
					'kode'=>$kode,
					'access'=>$this->access,
				);
				/*
				if ($cek['nama_tabel'] != NULL) {
					$tb=$this->model_setting->tb($cek['nama_tabel']);
					if (count($tb) > 0) {
						
					}else{

					}
				}else{
					$jb=array();
					$sub=array();
					$kar=array();
					$jb1=$this->model_master->jabatan_avl();
					foreach ($jb1 as $j) {
						$sb=$this->model_master->sub_jbt($j->kode_jabatan);
						if (count($sb) > 0) {

							foreach ($sb as $s) {
								$ds=array('id_jabatan'=>$j->id_jabatan,'id_sub'=>$s->id_sub,'nama_sub'=>$s->nama_sub);
								array_push($sub, $ds);
								$ky=$this->db->get_where('karyawan',array('kode_sub'=>$s->kode_sub))->result();
							}
							
						}else{
							$dj=array('id_jabatan'=>$j->id_jabatan,'jabatan'=>$j->jabatan);

							array_push($jb, $dj);
						}
					}
					echo '<pre>';
					print_r($jb);
					echo '</pre>';
					foreach ($jb as $jj) {
						foreach ($sub as $ss) {
							if ($ss->id_jabatan == $jj) {
								# code...
							}
						}
					}

				}*/
				if ($cek['nama_tabel'] == NULL) {
					$data['jabatan']=$this->model_master->jabatan_avl();

					$nn=1;
					foreach ($data['jabatan'] as $l) {
						$sb=$this->model_master->sub_jbt($l->kode_jabatan);
						if (count($sb) > 0) {
							foreach ($sb as $s) {
								$ky1=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$l->kode_jabatan' AND kode_sub = '$s->kode_sub'")->result();
								if (count($ky1) > 0) {
									$sb1[]=count($sb);
									$j1=$this->model_master->k_jabatan($s->kode_jabatan);
									$kt=$j1['kode_kategori'];
									$ind=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
									foreach ($ind as $k1) {
										$data1['ind'][$nn]=count($ind);
									}
									$data['ind'][$s->kode_sub]=$ind;
									$nn++;
								}
							}
						}
						$ky=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$l->kode_jabatan' AND kode_sub IS NULL")->result();
						if (count($ky) > 0) {
							$jbs[]=$l->id_jabatan;
							$kt1=$l->kode_kategori;
							$ind1=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt1' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
							foreach ($ind1 as $k) {
								$data1['ind'][$nn]=count($ind1);
							}
							$data['ind'][$l->id_jabatan]=$ind1;
							$nn++;
						}
						
						/*
						$kt=$l->kode_kategori;
						$ind=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
						foreach ($ind as $k) {
							$data1['ind'][$nn]=count($ind);
						}
						$data['ind'][$l->id_jabatan]=$ind;*/
						
					}

					if (isset($sb1)) {
						$sa=count($sb1);
					}else{
						$sa=0;
					}
					if (isset($jbs)) {
						$jbb=count($jbs);
					}else{
						$jbb=0;
					}
					$data1['jml']=$jbb+$sa;
					//print_r($jbs);
					//echo $jbb;
					//echo $data1['jml'];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/view_concept',$data);
					$this->load->view('admin_tem/footerx',$data1);
				}else{
					$data['concept_data']=$this->model_setting->concept_data($cek['nama_tabel']);
					if (count($data['concept_data']) == 0) {
						$data['jabatan']=$this->model_master->jabatan_avl();

						$nn=1;
						foreach ($data['jabatan'] as $l) {
							$sb=$this->model_master->sub_jbt($l->kode_jabatan);
							if (count($sb) > 0) {
								foreach ($sb as $s) {
									$ky1=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$l->kode_jabatan' AND kode_sub = '$s->kode_sub'")->result();
									if (count($ky1) > 0) {
										$sb1[]=count($sb);
										$j1=$this->model_master->k_jabatan($s->kode_jabatan);
										$kt=$j1['kode_kategori'];
										$ind=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
										foreach ($ind as $k1) {
											$data1['ind'][$nn]=count($ind);
										}
										$data['ind'][$s->kode_sub]=$ind;
										$nn++;
									}
								}
							}
							$ky=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$l->kode_jabatan' AND kode_sub IS NULL")->result();
							if (count($ky) > 0) {
								$jbs[]=$l->id_jabatan;
								$kt1=$l->kode_kategori;
								$ind1=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt1' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
								foreach ($ind1 as $k) {
									$data1['ind'][$nn]=count($ind1);
								}
								$data['ind'][$l->id_jabatan]=$ind1;
								$nn++;
							}
							
							/*
							$kt=$l->kode_kategori;
							$ind=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
							foreach ($ind as $k) {
								$data1['ind'][$nn]=count($ind);
							}
							$data['ind'][$l->id_jabatan]=$ind;*/
							
						}

						if (isset($sb1)) {
							$sa=count($sb1);
						}else{
							$sa=0;
						}
						if (isset($jbs)) {
							$jbb=count($jbs);
						}else{
							$jbb=0;
						}
						$data1['jml']=$jbb+$sa;
						$this->load->view('admin_tem/headerx',$this->dtroot);
						$this->load->view('admin_tem/sidebarx',$this->dtroot);
						$this->load->view('pages/view_concept',$data);
						$this->load->view('admin_tem/footerx',$data1);
					}else{
						foreach ($data['concept_data'] as $c) {
							$jbtn[$c->id_jabatan]=$c->id_jabatan;
						}
						$data['jabatan']=$this->model_master->jabatan_avl();

						$nn=1;
						foreach ($data['jabatan'] as $l) {
							$sb=$this->model_master->sub_jbt($l->kode_jabatan);
							if (count($sb) > 0) {
								foreach ($sb as $s) {
									$ky1=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$l->kode_jabatan' AND kode_sub = '$s->kode_sub'")->result();
									if (count($ky1) > 0) {
										$sb1[]=count($sb);
										$j1=$this->model_master->k_jabatan($s->kode_jabatan);
										$kt=$j1['kode_kategori'];
										$ind=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
										foreach ($ind as $k1) {
											$data1['ind'][$nn]=count($ind);
										}
										$data['ind'][$s->kode_sub]=$ind;
										$nn++;
									}
								}
							}
							$ky=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$l->kode_jabatan' AND kode_sub IS NULL")->result();
							if (count($ky) > 0) {
								$jbs[]=$l->id_jabatan;
								$kt1=$l->kode_kategori;
								$ind1=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt1' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
								foreach ($ind1 as $k) {
									$data1['ind'][$nn]=count($ind1);
								}
								$data['ind'][$l->id_jabatan]=$ind1;
								$nn++;
							}
							
							/*
							$kt=$l->kode_kategori;
							$ind=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
							foreach ($ind as $k) {
								$data1['ind'][$nn]=count($ind);
							}
							$data['ind'][$l->id_jabatan]=$ind;*/
							
						}

						if (isset($sb1)) {
							$sa=count($sb1);
						}else{
							$sa=0;
						}
						if (isset($jbs)) {
							$jbb=count($jbs);
						}else{
							$jbb=0;
						}
						$data1['jml']=$jbb+$sa;
						// $djbt=$this->model_master->jabatan_avl();
						// foreach ($djbt as $jj) {
						// 	if (!in_array($jj->id_jabatan, $jbtn)) {
						// 		$jbtn1[]=$jj->kode_kategori;
						// 		$jbtn2[]=$jj->id_jabatan;
						// 	}
						// }
						// $data['jabatan']=$jbtn2;
						// $data1=array('jml'=>count($jbtn1));
						// $nn=1;
						// foreach ($jbtn1 as $l) {
						// 	$kt=$l;
						// 	$ind=$this->db->query("SELECT * FROM master_indikator WHERE kode_kategori = '$kt' OR kode_kategori = 'KAT0' AND status = 'aktif'")->result();
						// 	foreach ($ind as $k) {
						// 		$data1['ind'][$nn]=count($ind);
						// 	}
						// 	$nn++;
						// }
						$this->load->view('admin_tem/headerx',$this->dtroot);
						$this->load->view('admin_tem/sidebarx',$this->dtroot);
						$this->load->view('pages/view_concept_data',$data);
						$this->load->view('admin_tem/footerx',$data1);
					}
					
				}
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_concept_setting(){
		$nama_menu="view_concept_setting";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$idj=$this->uri->segment(4);
			$id_s=$this->uri->segment(5);
			$cek=$this->model_master->cek_set($kode);

			if ($cek == "" || $kode == "" || $idj == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/concept');
			}else{
				$tab=$cek['nama_tabel'];
				if (isset($id_s)) {
					
					$ck=$this->db->get_where($cek['nama_tabel'],array('id_jabatan'=>$idj,'id_sub'=>$id_s))->num_rows();
					$datax=$this->db->query("SELECT * FROM $tab WHERE id_jabatan = '$idj' AND id_sub = '$id_s' ORDER BY urutan ASC")->result();
				}else{
					$ck=$this->db->get_where($cek['nama_tabel'],array('id_jabatan'=>$idj,'id_sub'=>NULL))->num_rows();
					$datax=$this->db->query("SELECT * FROM $tab WHERE id_jabatan = '$idj' ORDER BY urutan ASC")->result();
				}
				if ($ck == 0) {
					$this->messages->sessNotValidParam();  
					redirect('pages/view_concept/'.$kode);
				}else{
					$jbt=$this->model_master->jabatan($idj);
					$ko_s=$this->db->get_where('master_sub_jabatan',array('id_sub'=>$id_s))->row_array();
					$data=array(
						'nama'=>$jbt['jabatan'],
						'nama_st'=>$cek['nama'],
						'tabel'=>$cek['nama_tabel'],
						'kode'=>$kode,
						'idj'=>$idj,
						'kdj'=>$jbt['kode_jabatan'],
						'view_s'=>$datax,
						'id_sub'=>$id_s,
						'kode_sub'=>$ko_s['kode_sub'],
						'nama_sub'=>$ko_s['nama_sub'],
						'access'=>$this->access,
					);				
					foreach ($data['view_s'] as $d) {
						$res[$d->kode_indikator]=$d->kode_indikator;
					}
					$dts['dts']=count($res);
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/view_concept_setting',$data);
					$this->load->view('admin_tem/footerx',$dts);
				}
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	//==END SETTING INDIKATOR==//
	//==TARGET CORPORATE==//
	public function target_corporate(){
		$nama_menu="target_corporate";
		if (in_array($nama_menu, $this->link)) {
			$data=array('target'=>$this->model_master->list_target_c(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/target_corporate',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_target_corporate(){
		$nama_menu="view_target_corporate";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$cek=$this->model_master->cek_target($kode); 
			if ($cek == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/target_corporate');
			}else{
				$data=array(
					'view'=>$this->model_master->tb_target($cek['nama_tabel']),
					'nama'=>$cek['nama_target'],
					'smt'=>$cek['semester'],
					'th'=>$cek['tahun'],
					'tabel'=>$cek['nama_tabel'],
					'kode'=>$kode,
					'edit'=>$cek['edit'],
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_target_corporate',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	//==END TARGET CORPORATE==//
	//==MASTER BEGIN==//
	//--indikator--//
	public function master_indikator(){
		$nama_menu="master_indikator";
		if (in_array($nama_menu, $this->link)) {
			$data=array('indikator'=>$this->model_master->getListKpi(),'bagian'=>$this->model_master->getListBagianActive(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_indikator',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--aspek sikap--//
	// public function master_form_aspek(){
	// 	$nama_menu="master_form_aspek";
	// 	if (in_array($nama_menu, $this->link)) {
	// 		$data=array(
	// 			'form'=>$this->model_master->list_form(),
	// 			'asp'=>$this->model_master->actv_aspek(),
	// 			'access'=>$this->access,
	// 		);
	// 		$data1=array(
	// 			'jum_asp'=>count($data['asp']),
	// 			'jum_frm'=>count($data['form']),
	// 		);
	// 		$this->load->view('admin_tem/headerx',$this->dtroot);
	// 		$this->load->view('admin_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('pages/master_form_aspek',$data);
	// 		$this->load->view('admin_tem/footerx',$data1);	
	// 	}else{
	// 		redirect('pages/not_found');
	// 	}
	// }
	// public function master_aspek(){
	// 	$nama_menu="master_aspek";
	// 	if (in_array($nama_menu, $this->link)) {
	// 		$data=array('access'=>$this->access,);
	// 		$this->load->view('admin_tem/headerx',$this->dtroot);
	// 		$this->load->view('admin_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('pages/master_aspek',$data);
	// 		$this->load->view('admin_tem/footerx');	
	// 	}else{
	// 		redirect('pages/not_found');
	// 	}
	// }
	// new

	public function master_aspek_data(){
		$nama_menu="master_aspek";
		if (in_array($nama_menu, $this->link)) {
			$data=$this->model_master->getListAspek();
    		echo json_encode($data);
		}else{
			redirect('pages/not_found');
		}
	}
	public function kuisioner(){
		$nama_menu="kuisioner";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$cek=$this->model_master->cek_aspek($kode);
			if ($kode == "" || $cek == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/master_aspek');
			}else{
				$data=array(
					'nama'=>$cek['nama_aspek'],
					'kode'=>$cek['kode_aspek'],
					'kuisioner'=>$this->model_master->kuisioner($kode),
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/kuisioner',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	//--organisasi--//
	
	
	
// new putra //
	
	

// ============= //
	
	//--jabatan--//
	
	public function master_sub_jabatan(){
		$nama_menu="master_sub_jabatan";
		if (in_array($nama_menu, $this->link)) {
			$data=array('sub'=>$this->model_master->list_sub_jabatan(),'jbt'=>$this->model_master->jabatan_avl(),'loker'=>$this->model_master->loker_avl(),'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_sub_jabatan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	
	
	/*
	public function master_variant(){
		$data=array('variant'=>$this->model_master->list_variant(),);

		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('pages/master_variant',$data);
		$this->load->view('admin_tem/footerx');
	}
	*/
	
	//==END MASTER==//
	//==TASKS==//
	public function result_attitude_tasks(){
		$nama_menu="result_attitude_tasks";
		if (in_array($nama_menu, $this->link)) {
			$data=array('attd'=>$this->model_agenda->attd_agenda_open(),'tgl'=>$this->date,'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/result_attitude_tasks',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function result_attitude_tasks_value(){
		$nama_menu="result_attitude_tasks_value";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$dta=$this->model_agenda->actv_attd_open($kode);
			if ($dta == "" || $kode == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/result_attitude_tasks');
			}else{
				$nmtb=$dta['tabel_agenda'];
				$dt=$this->model_agenda->task($nmtb);
				foreach ($dt as $d) {
					$kari[$d->id_karyawan]=$d->id_karyawan;
					$k_kuis[$d->id_karyawan][$d->kode_kuisioner]=$d->kode_kuisioner;
					$nik[$d->id_karyawan][$d->nik]=$d->nik;
					$part[$d->id_karyawan][$d->partisipan]=$d->partisipan;
					$aspek[$d->id_karyawan][$d->kode_aspek]=$d->kode_aspek;
					$nilai_ats[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_ats;
					$nilai_bwh[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_bwh;
					$nilai_rkn[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_rkn;
					$nilai_dri[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_dri;
					$r_ats[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_ats;
					$r_bwh[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_bwh;
					$r_rkn[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_rkn;
					$bobot[$d->id_karyawan][$d->kode_aspek]=$d->bobot;
					$b_ats[$d->id_karyawan][$d->bobot_ats]=$d->bobot_ats;
					$b_bwh[$d->id_karyawan][$d->bobot_bwh]=$d->bobot_bwh;
					$b_rkn[$d->id_karyawan][$d->bobot_rkn]=$d->bobot_rkn;
					$nama[$d->id_karyawan]=$d->nama;
					$jabatan[$d->id_karyawan]=$d->jabatan;
					$loker[$d->id_karyawan]=$d->loker;
					$kalibrasi[$d->id_karyawan]=$d->nilai_kalibrasi;
				}
				/*
				foreach ($kari as $krr) {
					$pr1[$krr]=implode('', $part[$krr]);
					$pr2[$krr]=array_filter(explode(';', $pr1[$krr]));
					$np1=1;
					foreach ($pr2[$krr] as $pk) {
						$pk1[$krr][$np1]=explode(':', $pk);
						$np1++;
					}
				}*/
				foreach ($r_bwh as $kb => $vb) {
					foreach ($nilai_bwh[$kb] as $kbw1) {
						if ($kbw1 != NULL) {
							$kbw[$kb]=array_filter(explode(';', $kbw1));
							$nsb=1;
							foreach ($kbw[$kb] as $kbbw1) {
								$kbbw[$kb]=explode(':', $kbbw1);
								$sbw[$kb][$nsb]=$kbbw[$kb][0];
								$nsb++;
							}
						}
					}
					foreach ($aspek[$kb] as $asb) {
						$nb=1;
						foreach ($vb[$asb] as $kab) {
							if ($kab != 0) {
								$bw[$kb][$asb][$nb]=$kab;
							}
							$nb++;
						}
						if (isset($bw[$kb][$asb])) {
							$nbw[$kb][$asb]=(array_sum($bw[$kb][$asb])/count($bw[$kb][$asb]))*($bobot[$kb][$asb]/100);	
						}else{
							$nbw[$kb][$asb]=0;
						}
					}
					$bbwh[$kb]=implode('', $b_bwh[$kb])/100;
					$nabw[$kb]=array_sum($nbw[$kb])*$bbwh[$kb];
				}
				foreach ($r_ats as $ka => $va) {
					foreach ($nilai_ats[$ka] as $kat1) {
						if ($kat1 != NULL) {
							$kat[$ka]=array_filter(explode(';', $kat1));
							$nsa=1;
							foreach ($kat[$ka] as $kbat1) {
								$kbat[$ka]=explode(':', $kbat1);
								$sat[$ka][$nsa]=$kbat[$ka][0];
								$nsa++;
							}
							//print_r($kat[$ka]);
						}
					}
					foreach ($aspek[$ka] as $asa) {
						$na=1;
						foreach ($va[$asa] as $kaa) {
							if ($kaa != 0) {
								$at[$ka][$asa][$na]=$kaa;
							}
							$na++;
						}
						if (isset($at[$ka][$asa])) {
							$nat[$ka][$asa]=(array_sum($at[$ka][$asa])/count($at[$ka][$asa]))*($bobot[$ka][$asa]/100);	
						}else{
							$nat[$ka][$asa]=0;
						}
					}
					$bats[$ka]=implode('', $b_ats[$ka])/100;
					$naat[$ka]=array_sum($nat[$ka])*$bats[$ka];
				}
				
				foreach ($r_rkn as $kr => $vr) {
					foreach ($nilai_dri[$kr] as $kdr1) {
						if ($kdr1 != 0) {
							$sdr[$kr]=$kr;
						}
					}

					foreach ($nilai_rkn[$kr] as $krk1) {
						if ($krk1 != NULL) {
							$krk[$kr]=array_filter(explode(';', $krk1));
							$nsrk=1;
							foreach ($krk[$kr] as $kbrk1) {
								$kbrk[$kr]=explode(':', $kbrk1);
								$srk[$kr][$nsrk]=$kbrk[$kr][0];
								$nsrk++;
							}
						}
					}
					foreach ($aspek[$kr] as $asr) {
						$nr=1;
						foreach ($vr[$asr] as $kar) {
							if ($kar != 0) {
								$rk[$kr][$asr][$nr]=$kar;
							}
							$nr++;
						}
						if (isset($rk[$kr][$asr])) {
							$nrk[$kr][$asr]=(array_sum($rk[$kr][$asr])/count($rk[$kr][$asr]))*($bobot[$kr][$asr]/100);	
						}else{
							$nrk[$kr][$asr]=0;
						}
					}
					$brkn[$kr]=implode('', $b_rkn[$kr])/100;
					$nark[$kr]=array_sum($nrk[$kr])*$brkn[$kr];
				}
				
				//echo array_sum($bw);
				/*
				echo '<pre>';
						print_r(array_unique($sdr));
						echo '</pre>';
						echo '<pre>';
				print_r($data);
						echo '</pre>';
							
				*/
				
				
				$data=array(
					'kar'=>$kari,
					'nik'=>$nik,
					'nats'=>$naat,
					'nbwh'=>$nabw,
					'nrkn'=>$nark,
					'lok'=>$loker,
					'kalibrasi'=>$kalibrasi,
					'nm'=>$nama,
					'jbt'=>$jabatan,
					'agd'=>$dta,
					'nmtb'=>$nmtb,
					'kode'=>$kode,
					'pr1'=>$part,
					'access'=>$this->access,
				);
				if (isset($sbw)) {
					$data['sbawah']=$sbw;
				}
				if (isset($sat)) {
					$data['satas']=$sat;
				}
				if (isset($sdr)) {
					$data['sdiri']=array_unique($sdr);
				}
				if (isset($srk)) {
					$data['srekan']=$srk;
				}
				$data['data']=array(
					'kar'=>$kari,
					'nik'=>$nik,
					'nats'=>$naat,
					'nbwh'=>$nabw,
					'nrkn'=>$nark,
					'lok'=>$loker,
					'nm'=>$nama,
					'jbt'=>$jabatan,);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/result_attitude_tasks_value',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	public function report_attitude_value(){
		$nama_menu="report_attitude_value";
		if (in_array($nama_menu, $this->link)) {
			$id=$this->uri->segment(4);
			$kode=$this->uri->segment(3);
			$dt=$this->model_agenda->actv_attd_open($kode);
			if ($dt == "" || $kode == "" || $id == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/result_attitude_tasks');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$dti=$this->db->get_where($nmtb,array('id_karyawan'=>$id))->result();
				if (count($dti) == 0) {
					$this->messages->sessNotValidParam();  
					redirect('pages/result_attitude_tasks_value/'.$kode);
				}
				$n=1;
				$kr=$this->model_karyawan->emp($id);
				foreach ($dti as $d) {
					$jabatan[$d->jabatan]=$d->jabatan;
					$loker[$d->loker]=$d->loker;
					$kode_k[$d->kode_aspek]=$d->kode_aspek;
					$rt_a[$d->kode_aspek][$n]=$d->rata_ats;
					$rt_b[$d->kode_aspek][$n]=$d->rata_bwh;
					$rt_r[$d->kode_aspek][$n]=$d->rata_rkn;
					$nilai_d[$d->kode_aspek][$n]=$d->nilai_dri;
					$bobot[$d->kode_aspek][$n]=$d->bobot;
					$b_ats[$d->bobot_ats]=$d->bobot_ats;
					$b_bwh[$d->bobot_bwh]=$d->bobot_bwh;
					$b_rkn[$d->bobot_rkn]=$d->bobot_rkn;
					$part[$d->partisipan]=$d->partisipan;
					$n++;
				}
				foreach ($part as $p) {
					$p1=explode(';', $p);
					foreach ($p1 as $a) {
						$a1=explode(':', $a);
						if ($a1[0] != "DRI") {
							$a2[]=$a1[0];
						}
					}
				}
				$pp1=array_unique(array_filter($a2));
				array_push($pp1, "DRI");
				$pp=implode(';', $pp1);	
				$data=array(
					'profile'=>$kr,
					'id'=>$id,
					'agd'=>$dt,
					'part'=>$pp,
					'bobot'=>$bobot,
					'jabatan'=>implode('', $jabatan),
					'loker'=>implode('', $loker),
					'asp'=>$kode_k,
					'n_dri'=>$nilai_d,
					'rt_ats'=>$rt_a,
					'rt_bwh'=>$rt_b,
					'rt_rkn'=>$rt_r,
					'b_ats'=>implode('', $b_ats),
					'b_bwh'=>implode('', $b_bwh),
					'b_rkn'=>implode('', $b_rkn),
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/report_attitude_value',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	public function report_detail_attitude(){
		$nama_menu="report_detail_attitude";
		if (in_array($nama_menu, $this->link)) {
			$id=$this->uri->segment(5);
			$kode=$this->uri->segment(3);
			$kode_a=$this->uri->segment(4);
			$dt=$this->model_agenda->actv_attd_open($kode);
			if ($dt == "" || $kode == "" || $id == "" || $kode_a == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/result_attitude_tasks');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$dti=$this->db->get_where($nmtb,array('id_karyawan'=>$id,'kode_aspek'=>$kode_a))->result();
				if (count($dti) == 0) {
					$this->messages->sessNotValidParam();  
					redirect('pages/result_attitude_tasks_value/'.$kode);
				}
				$n=1;
				$kr=$this->model_karyawan->emp($id);
				foreach ($dti as $d) {
					$kode_k[$d->kode_aspek]=$d->kode_aspek;
					$kuis[$n]=$d->kuisioner;
					$rt_a[$n]=$d->rata_ats;
					$rt_b[$n]=$d->rata_bwh;
					$rt_r[$n]=$d->rata_rkn;
					$nilai_a[$n]=$d->nilai_ats;
					$nilai_b[$n]=$d->nilai_bwh;
					$nilai_r[$n]=$d->nilai_rkn;
					$nilai_d[$n]=$d->nilai_dri;
					$ket_d[$n]=$d->keterangan_dri;
					$ket_a[$n]=$d->keterangan_ats;
					$ket_b[$n]=$d->keterangan_bwh;
					$ket_r[$n]=$d->keterangan_rkn;
					$part[$d->partisipan]=$d->partisipan;
					$n++;
				}
				$pp1=implode('', $part);
				$pp=explode(';', $pp1);
				foreach ($pp as $px) {
					$px1=explode(':', $px);
					$px2[]=$px1[0];
				}
				$ppx1=implode(';', array_unique($px2));
				$ppx=explode(';', $ppx1);
				$aspek=$this->model_master->cek_aspek($kode_a);
				$data=array(
					'nama'=>$kr['nama'],
					'id'=>$id,
					'agd'=>$dt,
					'aspek'=>$aspek,
					'part'=>$pp,
					'partx'=>$ppx,
					'asp'=>$kode_k,
					'kuis'=>$kuis,
					'n_dri'=>$nilai_d,
					'n_ats'=>$nilai_a,
					'n_bwh'=>$nilai_b,
					'n_rkn'=>$nilai_r,
					'rt_ats'=>$rt_a,
					'rt_bwh'=>$rt_b,
					'rt_rkn'=>$rt_r,
					'k_dri'=>$ket_d,
					'k_ats'=>$ket_a,
					'k_bwh'=>$ket_b,
					'k_rkn'=>$ket_r,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/report_detail_attitude',$data);
				$this->load->view('admin_tem/footerx'); 
			}	
		}else{
			redirect('pages/not_found');
		} 
	}
	public function tasks(){
		$nama_menu="tasks";
		if (in_array($nama_menu, $this->link)) {
			$data=array('agd'=>$this->model_agenda->agenda_open(),'tgl'=>$this->date,'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/tasks',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function result_tasks(){
		$nama_menu="result_tasks";
		if (in_array($nama_menu, $this->link)) {
			$data=array('agd'=>$this->model_agenda->agenda_open(),'tgl'=>$this->date,'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/result_tasks',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function report_value(){
		$nama_menu="report_value";
		if (in_array($nama_menu, $this->link)) {
			$nmtb=$this->uri->segment(3);
			$id=$this->uri->segment(4);
			$cektb=$this->model_agenda->cek_tabel($nmtb);
			$actv=$this->model_agenda->agenda_open();
			if ($cektb == 0) {
				$this->messages->sessNotValidParam();  
				redirect('pages/result_tasks');
			}
			$cek=$this->model_agenda->result_value($nmtb,$id);
			if ($cek == "" || $nmtb == "" || $id == "" || count($actv) == 0) {
				$this->messages->sessNotValidParam();  
				redirect('pages/result_tasks');
			}else{
				if (count($cek) == 0) {
					$this->messages->sessNotValidParam();  
					redirect('pages/result_tasks');
				}
				$agd=$this->db->get_where('agenda',array('tabel_agenda'=>$nmtb))->row_array();
				$kar=$this->model_karyawan->emp($id);
				if ($kar['jabatan_pa'] != NULL) {
					$jbt=$this->model_master->jabatan($kar['jabatan_pa']);
				}else{
					$jbt=$this->model_master->k_jabatan($kar['jabatan']);
					
				}
				if ($kar['loker_pa'] != NULL) {
					$lok=$this->model_master->loker($kar['loker_pa']);
				}else{
					$lok=$this->model_master->k_loker($kar['unit']);
				}
				$data=array(
					'profile'=>$kar,
					'log'=>$this->db->get_where('log_login_karyawan',array('id_karyawan'=>$kar['id_karyawan']))->result(),
					'jabatan'=>$jbt,
					'loker'=>$lok,
					'hasil'=>$cek,
					'nmtb'=>$nmtb,
					'agd'=>$agd,
					'idk'=>$id,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/report_value',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	public function input_tasks_value(){
		$nama_menu="input_tasks_value";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$dt=$this->model_agenda->actv_out_open($kode);
			if ($dt == "" || $kode == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/tasks');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$dt1=$this->model_agenda->task($nmtb);
				foreach ($dt1 as $d) {
					$res[$d->id_karyawan]=$d->id_karyawan;
					$jabatan[$d->id_karyawan][$d->jabatan]=$d->jabatan;
					$loker[$d->id_karyawan][$d->loker]=$d->loker;
					$ind[$d->id_karyawan][$d->kode_indikator]=$d->kode_indikator;
				}
				foreach ($res as $k) {
					$dat=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
					$kar=$this->model_karyawan->emp($k);
					$nilai[$k]=array();
					foreach ($dat as $d1) {
						array_push($nilai[$k], $d1->nilai_out);
					}
					$datax[$k]=array(
						'nilai'=>array_sum($nilai[$k]),
						'nik'=>$kar['nik'],
						'nama'=>$kar['nama'],
						'jabatan'=>implode('',$jabatan[$k]),
						'loker'=>implode('',$loker[$k]),
						'ind'=>count($ind[$k]),
					);
				}
				$data=array(
					'agd'=>$dt,
					'tabel'=>$datax,
					'kode'=>$kode,
					'nmtb'=>$nmtb,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/input_tasks_value',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	public function input_value(){
		$nama_menu="input_value";
		if (in_array($nama_menu, $this->link)) {
			$id=$this->uri->segment(4);
			$kode=$this->uri->segment(3);
			$dt=$this->model_agenda->cek_agd($kode);
			$actv=$this->model_agenda->agenda_open();
			if ($dt == "" || $kode == "" || $id == "" || count($actv) == 0) {
				$this->messages->sessNotValidParam();  
				redirect('pages/tasks');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$dti=$this->db->query("SELECT * FROM $nmtb WHERE id_karyawan = '$id' ORDER BY urutan ASC")->result();
				if ($dti == "") {
					redirect('pages/task');
				}
				$k=$this->db->query("SELECT nama,jabatan,jabatan_pa FROM karyawan WHERE id_karyawan = '$id'")->row_array();
				
				if ($k['jabatan_pa'] != NULL) {
					$jbt=$this->model_master->jabatan($k['jabatan_pa']);
				}else{
					$jbt=$this->model_master->k_jabatan($k['jabatan']);
				}
				$data=array(
					'id'=>$id,
					'agd'=>$dt,
					'tabel'=>$dti,
					'ntabel'=>$nmtb,
					'kode'=>$kode,
					'smt'=>$dt['semester'],
					'nama'=>$k['nama'],
					'jabatan'=>$jbt,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/input_value',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	public function result_tasks_value(){
		$nama_menu="result_tasks_value";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$dt=$this->model_agenda->cek_log_agd($kode);
			$actv=$this->model_agenda->agenda_open();
			if ($dt == "" || $kode == "" || count($actv) == 0) {
				$this->messages->sessNotValidParam();  
				redirect('pages/result_tasks');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$dt1=$this->model_agenda->task($nmtb);
				foreach ($dt1 as $d) {
					$res[$d->id_karyawan]=$d->id_karyawan;
					$jabatan[$d->id_karyawan][$d->jabatan]=$d->jabatan;
					$id_jabatan[$d->id_karyawan][$d->id_jabatan]=$d->id_jabatan;
					$loker[$d->id_karyawan][$d->loker]=$d->loker;
					$ind[$d->id_karyawan][$d->kode_indikator]=$d->kode_indikator;
					$penilai[$d->id_karyawan][$d->kode_indikator]=$d->kode_penilai;
					$user[$d->id_karyawan][$d->kode_indikator]=$d->id_penilai;
					$n1[$d->id_karyawan][$d->kode_indikator]=$d->ln1;
					$n2[$d->id_karyawan][$d->kode_indikator]=$d->ln2;
					$n3[$d->id_karyawan][$d->kode_indikator]=$d->ln3;
					$n4[$d->id_karyawan][$d->kode_indikator]=$d->ln4;
					$n5[$d->id_karyawan][$d->kode_indikator]=$d->ln5;
					$n6[$d->id_karyawan][$d->kode_indikator]=$d->ln6;
				}
				foreach ($res as $k) {
					$dat=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
					$kar=$this->model_karyawan->emp($k);
					$nilai[$k]=array();
					$nx=1;
					foreach ($dat as $h) {
						$jbt=$this->model_master->k_jabatan($kar['jabatan']);
						$ats=$this->db->get_where('karyawan',array('jabatan'=>$jbt['atasan']))->row_array();
						$period[$k]=$jbt['kode_periode'];
						$atasan[$k]=$ats['id_karyawan'];
						if ($jbt['kode_periode'] == 'BLN') {
							$nax[$nx]=array($h->na1,$h->na2,$h->na3,$h->na4,$h->na5,$h->na6);
							$avg_nx[$nx]=array_sum($nax[$nx])/count($nax[$nx]);
						}else{
							$nax[$nx]=array($h->na6);
							$avg_nx[$nx]=array_sum($nax[$nx])/count($nax[$nx]);
						}
						array_push($nilai[$k], $avg_nx[$nx]);
						$nx++;
					}
					$datax[$k]=array(
						'nilai'=>array_sum($nilai[$k]),
						'nik'=>$kar['nik'],
						'nama'=>$kar['nama'],
						'jabatan'=>implode('',$jabatan[$k]),
						'loker'=>implode('',$loker[$k]),
						'periode_penilaian'=>$period[$k],
						'atasan'=>$atasan[$k],
						'ind'=>count($ind[$k]),
					);
				}
				if (isset($penilai)) {
					$penilai=$penilai;
				}else{
					$penilai=NULL;
				}
				if (isset($user)) {
					$user=$user;
				}else{
					$user=NULL;
				}
				$nilai=array('n1'=>$n1,'n2'=>$n2,'n3'=>$n3,'n4'=>$n4,'n5'=>$n5,'n6'=>$n6,);
				$data=array(
					'agd'=>$dt,
					'penilai'=>$penilai,
					'tabel'=>$datax,
					'kode'=>$kode,
					'nmtb'=>$nmtb,
					'user'=>$user,
					'nilai'=>$nilai,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/result_tasks_value',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
		
	}
	public function result_group(){
		$nama_menu="result_group";
		if (in_array($nama_menu, $this->link)) {
			$agdo=$this->db->get_where('log_agenda',array('status'=>'aktif'))->result();
			$agda=$this->db->get_where('log_attd_agenda',array('status'=>'aktif'))->result();
			$dt=array();
			if ($agdo != "" || $agda != "") {
				foreach ($agdo as $o) {
					if (!in_array($o->tahun, $dt)) {
						array_push($dt, $o->tahun);
					}
				}
				foreach ($agda as $a) {
					if (!in_array($a->tahun, $dt)) {
						array_push($dt, $a->tahun);
					}
				}
			}
			$th=$this->input->post('tahun');
			$smt=$this->input->post('semester');
			$bagi=$this->input->post('bagian');
			$lok=$this->input->post('loker');
			// $th=2018;
			// $smt=1;
			$aa=array();
			if ($th != "" && $smt != "") {

				if ($smt == 0) {
					$att=$this->db->get_where('log_attd_agenda',array('tahun'=>$th))->result();
					$out=$this->db->get_where('log_agenda',array('tahun'=>$th))->result();	
				}else{
					$att=$this->db->get_where('log_attd_agenda',array('tahun'=>$th,'semester'=>$smt))->result();
					$out=$this->db->get_where('log_agenda',array('tahun'=>$th,'semester'=>$smt))->result();	
				}
				
				if (count($att) > 0) {
					foreach ($att as $at) {

						$das=$this->db->get($at->tabel_agenda)->result();
						// foreach ($das as $as) {
						// 	$idk[$as->id_karyawan]=$as->id_karyawan; 
						// }
						foreach ($das as $d) {
							$kari[$d->id_karyawan]=$d->id_karyawan;
							$k_kuis[$d->id_karyawan][$d->kode_kuisioner]=$d->kode_kuisioner;
							$part[$d->id_karyawan][$d->partisipan]=$d->partisipan;
							$aspek[$d->id_karyawan][$d->kode_aspek]=$d->kode_aspek;
							$nilai_ats[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_ats;
							$nilai_bwh[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_bwh;
							$nilai_rkn[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_rkn;
							$nilai_dri[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_dri;
							$r_ats[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_ats;
							$r_bwh[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_bwh;
							$r_rkn[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_rkn;
							$bobot[$d->id_karyawan][$d->kode_aspek]=$d->bobot;
							$b_ats[$d->id_karyawan][$d->bobot_ats]=$d->bobot_ats;
							$b_bwh[$d->id_karyawan][$d->bobot_bwh]=$d->bobot_bwh;
							$b_rkn[$d->id_karyawan][$d->bobot_rkn]=$d->bobot_rkn;
							$nama[$d->id_karyawan]=$d->nama;
							$jabatan[$d->id_karyawan]=$d->jabatan;
							$id_jabatan[$d->id_karyawan]=$d->id_jabatan;
							$loker[$d->id_karyawan]=$d->loker;
							$i_loker[$d->id_karyawan]=$d->id_loker;
							$nik[$d->id_karyawan]=$d->nik;
							$kalibrasi[$d->id_karyawan]=$d->nilai_kalibrasi;
						}
						/*
						foreach ($kari as $krr) {
							$pr1[$krr]=implode('', $part[$krr]);
							$pr2[$krr]=array_filter(explode(';', $pr1[$krr]));
							$np1=1;
							foreach ($pr2[$krr] as $pk) {
								$pk1[$krr][$np1]=explode(':', $pk);
								$np1++;
							}
						}*/
						foreach ($r_bwh as $kb => $vb) {
							foreach ($nilai_bwh[$kb] as $kbw1) {
								if ($kbw1 != NULL) {
									$kbw[$kb]=array_filter(explode(';', $kbw1));
									$nsb=1;
									foreach ($kbw[$kb] as $kbbw1) {
										$kbbw[$kb]=explode(':', $kbbw1);
										$sbw[$kb][$nsb]=$kbbw[$kb][0];
										$nsb++;
									}
								}
							}
							foreach ($aspek[$kb] as $asb) {
								$nb=1;
								foreach ($vb[$asb] as $kab) {
									if ($kab != 0) {
										$bw[$kb][$asb][$nb]=$kab;
									}
									$nb++;
								}
								if (isset($bw[$kb][$asb])) {
									$nbw[$kb][$asb]=(array_sum($bw[$kb][$asb])/count($bw[$kb][$asb]))*($bobot[$kb][$asb]/100);	
								}else{
									$nbw[$kb][$asb]=0;
								}
							}
							$bbwh[$kb]=implode('', $b_bwh[$kb])/100;
							$nabw[$kb]=array_sum($nbw[$kb])*$bbwh[$kb];
						}
						foreach ($r_ats as $ka => $va) {
							foreach ($nilai_ats[$ka] as $kat1) {
								if ($kat1 != NULL) {
									$kat[$ka]=array_filter(explode(';', $kat1));
									$nsa=1;
									foreach ($kat[$ka] as $kbat1) {
										$kbat[$ka]=explode(':', $kbat1);
										$sat[$ka][$nsa]=$kbat[$ka][0];
										$nsa++;
									}
									//print_r($kat[$ka]);
								}
							}
							foreach ($aspek[$ka] as $asa) {
								$nar=1;
								foreach ($va[$asa] as $kaa) {
									if ($kaa != 0) {
										$ataa[$ka][$asa][$nar]=$kaa;
									}
									$nar++;
								}
								if (isset($ataa[$ka][$asa])) {
									$nat[$ka][$asa]=(array_sum($ataa[$ka][$asa])/count($ataa[$ka][$asa]))*($bobot[$ka][$asa]/100);	
								}else{
									$nat[$ka][$asa]=0;
								}
							}
							$bats[$ka]=implode('', $b_ats[$ka])/100;
							$naat[$ka]=array_sum($nat[$ka])*$bats[$ka];
						}
						
						foreach ($r_rkn as $kr => $vr) {
							foreach ($nilai_dri[$kr] as $kdr1) {
								if ($kdr1 != 0) {
									$sdr[$kr]=$kr;
								}
							}

							foreach ($nilai_rkn[$kr] as $krk1) {
								if ($krk1 != NULL) {
									$krk[$kr]=array_filter(explode(';', $krk1));
									$nsrk=1;
									foreach ($krk[$kr] as $kbrk1) {
										$kbrk[$kr]=explode(':', $kbrk1);
										$srk[$kr][$nsrk]=$kbrk[$kr][0];
										$nsrk++;
									}
								}
							}
							foreach ($aspek[$kr] as $asr) {
								$nr=1;
								foreach ($vr[$asr] as $kar) {
									if ($kar != 0) {
										$rk[$kr][$asr][$nr]=$kar;
									}
									$nr++;
								}
								if (isset($rk[$kr][$asr])) {
									$nrk[$kr][$asr]=(array_sum($rk[$kr][$asr])/count($rk[$kr][$asr]))*($bobot[$kr][$asr]/100);	
								}else{
									$nrk[$kr][$asr]=0;
								}
							}
							$brkn[$kr]=implode('', $b_rkn[$kr])/100;
							$nark[$kr]=array_sum($nrk[$kr])*$brkn[$kr];
						}
						foreach ($kari as $kp) {
							$jbb=$this->db->get_where('master_jabatan',array('id_jabatan'=>$id_jabatan[$kp]))->row_array();
							$lkr=$this->db->get_where('master_loker',array('id_loker'=>$i_loker[$kp]))->row_array();
							$bag=$this->db->get_where('master_kategori_jabatan',array('kode_kategori'=>$jbb['kode_kategori']))->row_array();
							$nla[$kp]=array('nilai_kalibrasi'=>$kalibrasi[$kp],'nilai_sikap'=>$naat[$kp]+$nark[$kp]+$nabw[$kp],'nama'=>$nama[$kp],'jabatan'=>$jabatan[$kp],'bagian'=>$bag['nama_kategori'],'kode_bagian'=>$jbb['kode_kategori'],'loker'=>$loker[$kp],'kode_loker'=>$lkr['kode_loker'],'nik'=>$nik[$kp],'id_jabatan'=>$id_jabatan[$kp]);
						}
					}
				}
				if (count($out) > 0) {
					foreach ($out as $ot) {
						$nmtb=$ot->tabel_agenda;
						$daso=$this->db->get($ot->tabel_agenda)->result();
						foreach ($daso as $os) {
							$idk1[$os->id_karyawan]=$os->id_karyawan; 
							$jabatan[$os->id_karyawan]=array('jabatan'=>$os->jabatan);
							$loker[$os->id_karyawan]=array('loker'=>$os->loker);
							$id_loker[$os->id_karyawan]=array('id_loker'=>$os->id_loker);
							$id_jabatan[$os->id_karyawan]=array('id_jabatan'=>$os->id_jabatan);
							$id_jabatan[$os->id_karyawan][$os->id_jabatan]=$os->id_jabatan;
						}
						if (isset($idk1)) {
							foreach ($idk1 as $k) {
								$ky=$this->db->query("SELECT nik,nama,jabatan FROM karyawan WHERE id_karyawan = '$k'")->row_array();
								$dat=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
								$nilai_o[$k]=array();
								$nx=1;
								foreach ($dat as $d1) {
									$jbt=$this->model_master->k_jabatan($ky['jabatan']);
									if ($jbt['kode_periode'] == 'BLN') {
										$nax[$nx]=array($d1->na1,$d1->na2,$d1->na3,$d1->na4,$d1->na5,$d1->na6);
										$avg_nx[$nx]=array_sum($nax[$nx])/count($nax[$nx]);
									}else{
										$nax[$nx]=array($d1->na6);
										$avg_nx[$nx]=array_sum($nax[$nx])/count($nax[$nx]);
									}
									array_push($nilai_o[$k], $avg_nx[$nx]);
									$nilai_t[$k]=array('target'=>$d1->nilai_tc);
									$nx++;
								}
								$jbb=$this->db->get_where('master_jabatan',array('id_jabatan'=>$id_jabatan[$k]['id_jabatan']))->row_array();
								$lkr=$this->db->get_where('master_loker',array('id_loker'=>$id_loker[$k]['id_loker']))->row_array();
								$bag=$this->db->get_where('master_kategori_jabatan',array('kode_kategori'=>$jbb['kode_kategori']))->row_array();
								$datax[$k]=array(
									'nik'=>$ky['nik'],
									'nama'=>$ky['nama'],
									'jabatan'=>$jabatan[$k]['jabatan'],
									'id_jabatan'=>$id_jabatan[$k]['id_jabatan'],
									'loker'=>$loker[$k]['loker'],
									'kode_loker'=>$lkr['kode_loker'],
									'kode_bagian'=>$bag['kode_kategori'],
									'bagian'=>$bag['nama_kategori'],
									'nilai_sikap'=>0,
								);
	                          	$nlb[$k]=array('nilai_target'=>array_sum($nilai_o[$k]),'nilai_corp'=>$nilai_t[$k]['target']);

							  // $nilai=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
	        //                   $data1=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->num_rows();
	        //                   $ky=$this->db->query("SELECT nama,jabatan FROM karyawan WHERE id_karyawan = '$k'")->row_array();
	        //                   $jbt=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$ky['jabatan']))->row_array();
	        //                   foreach ($nilai as $hs) {
	        //                     $jo[$k][]=$hs->id_jabatan;
	        //                     $lo[$k][]=$hs->id_loker;
	        //                   }
	        //                   $jo2[$k]=array_unique($jo[$k]);
	        //                   $lo2[$k]=array_unique($lo[$k]);
	        //                   $jo1[$k]=array_values($jo2[$k]);
	        //                   $lo1[$k]=array_values($lo2[$k]);
	        //                   if (count($jo1[$k]) > count($lo1[$k])) {
	        //                     foreach ($lo1[$k] as $lo2[$k]) {
	        //                       array_push($lo1[$k], $lo2[$k]);
	        //                     }
	        //                   }
	        //                   if(count($jo1[$k]) < count($lo1[$k])){
	        //                     foreach ($jo1[$k] as $jo2[$k]) {
	        //                       array_push($jo1[$k], $jo2[$k]);
	        //                     }
	        //                   }
	        //                   $np=1;
	        //                   if (count($jo1[$k]) > 1) {
	        //                     foreach ($jo1[$k] as $ka=>$jaa) {
	        //                       $hasilx=$this->db->get_where($nmtb,array('id_jabatan'=>$jaa,'id_loker'=>$lo1[$k][$ka],'id_karyawan'=>$k))->result();
	        //                       $nx1[$np]=array();
	        //                       $nx2[$np]=array();
	        //                       $nx3[$np]=array();
	        //                       $nx4[$np]=array();
	        //                       $nx5[$np]=array();
	        //                       $nx6[$np]=array();
	        //                       $vl3[$np]=array();
	        //                       foreach ($hasilx as $hx) {
	        //                         array_push($vl3[$np], $hx->nilai_out);
	        //                         array_push($nx1[$np], $hx->na1);
	        //                         array_push($nx2[$np], $hx->na2);
	        //                         array_push($nx3[$np], $hx->na3);
	        //                         array_push($nx4[$np], $hx->na4);
	        //                         array_push($nx5[$np], $hx->na5);
	        //                         array_push($nx6[$np], $hx->na6);
	        //                       }
	        //                       $vl13[$np] = array_filter($vl3[$np]);
	        //                       if (count($vl13[$np]) != 0) {
	        //                         if (array_sum($nx1[$np]) != 0) {
	        //                           $nnn[$np][]=array_sum($nx1[$np]);
	        //                         }
	        //                         if (array_sum($nx2[$np]) != 0) {
	        //                           $nnn[$np][]=array_sum($nx2[$np]);
	        //                         }
	        //                         if (array_sum($nx3[$np]) != 0) {
	        //                           $nnn[$np][]=array_sum($nx3[$np]);
	        //                         }
	        //                         if (array_sum($nx4[$np]) != 0) {
	        //                           $nnn[$np][]=array_sum($nx4[$np]);
	        //                         }
	        //                         if (array_sum($nx5[$np]) != 0) {
	        //                           $nnn[$np][]=array_sum($nx5[$np]);
	        //                         }
	        //                         if (array_sum($nx6[$np]) != 0) {
	        //                           $nnn[$np][]=array_sum($nx6[$np]);
	        //                         }
	        //                         $avgx[$np] = array_sum($nnn[$np])/count($nnn[$np]);
	        //                       }else{
	        //                         $avgx[$np]=0;
	        //                       }
	        //                       $np++;
	        //                     }
	                            
	        //                     foreach ($nilai as $n) {
	        //                       $nout[$k][]=($n->bobot_out/100)*$n->nilai_out;
	        //                       $nsk[$k][($n->bobot_skp/100)*$n->nilai_sikap]=($n->bobot_skp/100)*$n->nilai_sikap;
	        //                       $ntc[$k][($n->bobot_tc/100)*$n->nilai_tc]=($n->bobot_tc/100)*$n->nilai_tc;
	        //                       $bbout[$k][$n->bobot_out]=$n->bobot_out;
	        //                     }
	        //                     $bbout1[$k]=array_filter($bbout[$k]);
	        //                     $bbt1[$k]=implode('', $bbout1[$k])/100;
	        //                     $na[$k][]=array_sum($avgx)/count($avgx)*$bbt1[$k];
	        //                   }else{
	        //                     foreach ($nilai as $n) {
	        //                       $nout[$k][]=($n->bobot_out/100)*$n->nilai_out;
	        //                       $ntc[$k][($n->bobot_tc/100)*$n->nilai_tc]=($n->bobot_tc/100)*$n->nilai_tc;
	        //                       $nx1[$k][]=$n->na1;
	        //                       $nx2[$k][]=$n->na2;
	        //                       $nx3[$k][]=$n->na3;
	        //                       $nx4[$k][]=$n->na4;
	        //                       $nx5[$k][]=$n->na5;
	        //                       $nx6[$k][]=$n->na6;
	        //                       $bbout[$k][$n->bobot_out]=$n->bobot_out;
	        //                     }
	        //                     $bbout1[$k]=array_filter($bbout[$k]);
	        //                     $bbt1[$k]=implode('', $bbout1[$k])/100;
	        //                     $vl13[$k] = array_filter($nout[$k]);
	        //                     if (count($vl13[$k]) != 0) {
	        //                       if (array_sum($nx1[$k]) != 0) {
	        //                         $nnn[$k][]=array_sum($nx1[$k]);
	        //                       }
	        //                       if (array_sum($nx2[$k]) != 0) {
	        //                         $nnn[$k][]=array_sum($nx2[$k]);
	        //                       }
	        //                       if (array_sum($nx3[$k]) != 0) {
	        //                         $nnn[$k][]=array_sum($nx3[$k]);
	        //                       }
	        //                       if (array_sum($nx4[$k]) != 0) {
	        //                         $nnn[$k][]=array_sum($nx4[$k]);
	        //                       }
	        //                       if (array_sum($nx5[$k]) != 0) {
	        //                         $nnn[$k][]=array_sum($nx5[$k]);
	        //                       }
	        //                       if (array_sum($nx6[$k]) != 0) {
	        //                         $nnn[$k][]=array_sum($nx6[$k]);
	        //                       }
	        //                       $na[$k][] = array_sum($nnn[$k])/count($nnn[$k])*$bbt1[$k];
	        //                     }else{
	        //                       $na[$k][]=0;
	        //                     }
	        //                   }
	        //                   foreach ($nsk[$k] as $nk[$k]) {
	        //                     $na[$k][]=$nk[$k];
	        //                   }
	        //                   foreach ($ntc[$k] as $nt[$k]) {
	        //                     $na[$k][]=$nt[$k];
	        //                   }
	        //                   $naa[$k]=$na[$k][0]+$na[$k][1]+$na[$k][2];
							}
						}
						// $daa=array();
						// foreach ($nama1 as $nam) {
						// 	$na=array('nama'=>$nam);
						// 	array_push($daa, $na);
						// }
						// $jabatan=array();
						// foreach ($nama1 as $nam) {
						// 	$na=array('nama'=>$nam);
						// 	array_push($daa, $na);
						// }
						// foreach ($nik as $ni) {
						// 	$das1=$this->db->get_where($at->tabel_agenda,array('nik'=>$ni))->result();
						// 	// foreach ($variable as $key => $value) {
						// 	// 	# code...
						// 	// }
						// }
						//array_push($aa, $nama);
					}
				}
				
			}
			if (isset($idk)) {
				$idk1=$idk;
			}else{
				$idk1=NULL;
			}
			$resx = array();
			if (isset($nla)) {
				foreach($nla as $kpx => $vpx){
					$resx[$kpx]=$vpx;

					if (isset($nlb[$kpx])) {
				   	 	$resx[$kpx] = array_merge($nla[$kpx],$nlb[$kpx]);
					}
				}
			}else{
				if (isset($nlb) && !isset($nla)) {
					foreach($nlb as $kpx => $vpx){
						$resx[$kpx]=$vpx;

						if (isset($datax[$kpx])) {
					   	 	$resx[$kpx] = array_merge($datax[$kpx],$nlb[$kpx]);
						}
					}
				}
				
			}
			foreach ($resx as $kr => $vr) {
				$idjb=$vr['id_jabatan'];
				$jbb=$this->db->query("SELECT kode_level FROM master_jabatan WHERE id_jabatan = '$idjb'")->row_array();
				$lvl=$jbb['kode_level'];
				if ($smt != 0) {
					$dbt=$this->db->query("SELECT * FROM bobot_agenda WHERE tahun = '$th' AND semester = '$smt'")->result();
				}else{
					$dbt=$this->db->query("SELECT * FROM bobot_agenda WHERE tahun = '$th'")->result();
				}
				$tbb[$kr]=array();
				foreach ($dbt as $bt) {
					$nbtb=$bt->nama_tabel;
					$dtbl=$this->db->get_where($nbtb,array('kode_level'=>$lvl))->row_array();
					array_push($tbb[$kr], $dtbl);
							// $xb=1;
							// foreach ($dtbl as $tbl) {
							// 	$tbba[$tbl->kode_level]=array(
							// 		'bobot_out'=>$tbl->bobot_out,
							// 		'bobot_skp'=>$tbl->bobot_skp,
							// 		'bobot_tc'=>$tbl->bobot_tc,
							// 	);
							// 	$xb++;
							// }
							// array_push($tbb, $tbba);

							// $xb1++;
				}
				if (isset($tbb[$kr])) {
					$jmb=count($tbb[$kr]);
							// foreach ($tbb as $bb) {

							// }
					for ($ix=0; $ix <$jmb ; $ix++) { 
						$b_o[$kr][$ix]=$tbb[$kr][$ix]['bobot_out'];
						$b_s[$kr][$ix]=$tbb[$kr][$ix]['bobot_skp'];
						$b_t[$kr][$ix]=$tbb[$kr][$ix]['bobot_tc'];
					}
					if (isset($b_o[$kr])) {
						$rbo[$kr]=array_sum($b_o[$kr])/count($b_o[$kr]);
					}else{
						$rbo[$kr]=0;
					}
					if (isset($b_s[$kr])) {
						$rbs[$kr]=array_sum($b_s[$kr])/count($b_s[$kr]);
					}else{
						$rbs[$kr]=0;
					}
					if (isset($b_t[$kr])) {
						$rbt[$kr]=array_sum($b_t[$kr])/count($b_t[$kr]);
					}else{
						$rbt[$kr]=0;
					}
					$data_b[$kr]=array('bobot_out'=>$rbo[$kr],'bobot_skp'=>$rbs[$kr],'bobot_tc'=>$rbt[$kr]);
				}else{
					$data_b[$kr]=array('bobot_out'=>0,'bobot_skp'=>0,'bobot_tc'=>0);
				}
			}
			if (isset($data_b)) {
				$data_b=$data_b;
			}else{
				$data_b=NULL;
			}
			if (isset($th) && isset($smt)) {
				if ($smt == 1) {
					$sm='Januari - Juni '.$th;
				}elseif ($smt == 2) {
					$sm='Juli - Desember '.$th;
				}else{
					$sm='Januari - Desember '.$th;
				}
				$dtn1=array('nilaix'=>$resx,'periode'=>$sm,'tahun'=>$th,'smt'=>$smt,'bobot'=>$data_b);
				$dtn=array('nilai'=>$dtn1);
				$this->session->set_userdata($dtn);
			}else{
				$this->session->unset_userdata('nilai');
				$data_b=NULL;
			}
			

			//$aa1=array_unique($aa);
			
			$data=array('agd'=>$dt,'tgl'=>$this->date,'att'=>$resx,'bagian'=>$bagi,'lokerx'=>$lok,'smtr'=>$smt,'thn'=>$th,'bobot'=>$data_b,'access'=>$this->access,);
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/result_group',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function result_tasks_group(){
		$nama_menu="result_tasks_group";
		if (in_array($nama_menu, $this->link)) {
			$kode=$this->uri->segment(3);
			$dt=$this->model_agenda->cek_log_agd($kode);
			$actv=$this->model_agenda->result_aktif();
			if ($dt == "" || $kode == "" || count($actv) == 0) {
				$this->messages->sessNotValidParam();  
				redirect('pages/result_group');
			}else{
				$nmtb=$dt['tabel_agenda'];
				$data=array(
					'agd'=>$dt,
					'tabel'=>$this->model_agenda->task($nmtb),
					'nmtb'=>$nmtb,
					'kode'=>$kode,
					'access'=>$this->access,
				);
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/result_tasks_group',$data);
				$this->load->view('admin_tem/footerx');
			}	
		}else{
			redirect('pages/not_found');
		}
	}
	public function report_group(){
		$nama_menu="report_group";
		if (in_array($nama_menu, $this->link)) {
			$id=$this->uri->segment(3);
			if ($id == "") {
				$this->messages->sessNotValidParam();  
				redirect('pages/result_group');
			}else{
				if ($this->session->has_userdata('nilai')) {
					$n=$this->session->userdata('nilai')['nilaix'];
					$per=$this->session->userdata('nilai')['periode'];
					$th=$this->session->userdata('nilai')['tahun'];
					$smt=$this->session->userdata('nilai')['smt'];
					$bbt=$this->session->userdata('nilai')['bobot'];
					
					if ($n[$id] == "") {
						$this->messages->sessNotValidParam();  
						redirect('pages/result_group');
					}else{
						$k=$this->model_karyawan->emp($id);

						if (isset($bbt[$id]['bobot_out'])) {
							$bt_o=$bbt[$id]['bobot_out'];
						}else{
							$bt_o=0;
						}
						if (isset($bbt[$id]['bobot_skp'])) {
							$bt_s=$bbt[$id]['bobot_skp'];
						}else{
							$bt_s=0;
						}
						if (isset($bbt[$id]['bobot_tc'])) {
							$bt_t=$bbt[$id]['bobot_tc'];
						}else{
							$bt_t=0;
						}	
						if (isset($n[$id]['nilai_sikap'])) {
							$sikap=$n[$id]['nilai_sikap'];
						}else{
							$sikap=0;
						}
						if (isset($n[$id]['nilai_kalibrasi'])) {
							$kalibrasi=$n[$id]['nilai_kalibrasi'];
						}else{
							$kalibrasi=0;
						}
						if (isset($n[$id]['nilai_corp'])) {
							$corp=$n[$id]['nilai_corp'];
						}else{
							$corp=0;
						}
						if (isset($n[$id]['nilai_target'])) {
							$target=$n[$id]['nilai_target'];
						}else{
							$target=0;
						}
						$data=array(
							'nama'=>$n[$id]['nama'],
							'nik'=>$n[$id]['nik'],
							'jabatan'=>$n[$id]['jabatan'],
							'loker'=>$n[$id]['loker'],
							'tgl_masuk'=>$k['tgl_masuk'],
							'email'=>$k['email'],
							'kelamin'=>$k['kelamin'],
							'periode'=>$per,
							'foto'=>$k['foto'],
							'sikap'=>$sikap,
							'kalibrasi'=>$kalibrasi,
							'corp'=>$corp,
							'target'=>$target,
							'bobot_out'=>$bt_o,
							'bobot_sikap'=>$bt_s,
							'bobot_tc'=>$bt_t,
							'access'=>$this->access,
						);
						$this->load->view('admin_tem/headerx',$this->dtroot);
						$this->load->view('admin_tem/sidebarx',$this->dtroot);
						$this->load->view('pages/report_group',$data);
						$this->load->view('admin_tem/footerx');
					}
				}else{
					$this->messages->sessNotValidParam();  
					redirect('pages/result_group');
				}
			}	
		}else{
			redirect('pages/not_found');
		}
	}

	//Master Tunjangan Penggajian
	public function master_induk_tunjangan(){
		$nama_menu="master_induk_tunjangan";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_induk_tunjangan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Tunjangan Penggajian
	public function master_tunjangan(){
		$nama_menu="master_tunjangan";
		if (in_array($nama_menu, $this->link)) {
			$kode = $this->codegenerator->decryptChar($this->uri->segment(3));
			if(empty($kode)){
				redirect('pages/master_induk_tunjangan');
			}

			$tunjangan = $this->otherfunctions->convertResultToRowArray($this->model_master->getListIndukTunjanganKode($kode));
			if(empty($tunjangan)){
				redirect('pages/master_induk_tunjangan');
			}else{
				$nama = $tunjangan['nama'];
			}
			$data=['access'=>$this->access,'nama'=>$nama];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_tunjangan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Periode Penggajian
	public function periode_penggajian(){
		$nama_menu="periode_penggajian";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,
				'id_admin'=>$this->admin,];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_periode_penggajian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Insentif
	public function master_insentif(){
		$nama_menu="master_insentif";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_insentif',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_periode_penggajian(){
		$nama_menu="view_periode_penggajian";
		if (in_array($nama_menu, $this->link)) {
			if($this->dtroot['adm']['level'] == 0){
				$loker = 'empty';
				$nama_loker = 'empty';
				$status_adm = $this->dtroot['adm']['level'];
			}else{
				$emp = $this->model_karyawan->getEmployeeId($this->dtroot['adm']['id_karyawan']);
				$loker = $emp['loker'];
				$nama_loker = $emp['nama_loker'];
				$status_adm = $this->dtroot['adm']['level'];
			}
			$kode = $this->codegenerator->decryptChar($this->uri->segment(3));
			$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode]));
			$nama = $periode['nama'];
			$bulan = $this->formatter->getNameOfMonth(date('m', strtotime($periode['tgl_selesai'])));
			$tahun = date('Y', strtotime($periode['tgl_selesai']));
			$data=[
				'access'=>$this->access,
				'periode'=>$periode,
				'nama_periode'=>$nama,
				'bulan_periode'=>$bulan,
				'tahun_periode'=>$tahun,
				'loker'=>$loker,
				'nama_loker'=>$nama_loker,
				'status_adm'=>$status_adm
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_periode_penggajian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Periode Penggajian
	public function data_ritasi(){
		$nama_menu="data_ritasi";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_ritasi',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master BPJS
	public function master_bpjs(){
		$nama_menu="master_bpjs";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_bpjs',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function master_bpjs_data(){
		$nama_menu="master_bpjs_data";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_bpjs_data',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function master_bpjs_karyawan(){
		$nama_menu="master_bpjs_karyawan";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_bpjs_karyawan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function master_bpjs_prosentase(){
		$nama_menu="master_bpjs_prosentase";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_bpjs_prosentase',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Pinjaman
	public function data_pinjaman(){
		$nama_menu="data_pinjaman";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_pinjaman',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master PTKP
	public function master_ptkp(){
		$nama_menu="master_ptkp";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_ptkp',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Tarif PPH 21
	public function master_tarif_pph_21(){
		$nama_menu="master_tarif_pph_21";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_tarif_pph_21',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Tarif Biaya Jabatan
	public function master_tarif_biaya_jabatan(){
		$nama_menu="master_tarif_biaya_jabatan";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_tarif_biaya_jabatan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function monitor_presensi()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);

		$karyawan=$data_filter['karyawan_kartu'];
		$tahun_monitor=$data_filter['tahun_monitor'];
		$data_pre=[];
		foreach($karyawan as $kar){
			$data_pre[]=$this->model_karyawan->getListPresensiIdKaryawan($kar,$tahun_monitor);
		}
		// echo '<pre>';
		// print_r($data_pre);
		$data =[
			'data_presensi'=>$data_pre,
			'karyawan'=>$karyawan,
			'tahun'=>$tahun_monitor,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/kartu_presensi',$data);
		$this->load->view('print_page/footer');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data Penggajian
	public function data_penggajian(){
		$nama_menu="data_penggajian";
		if (in_array($nama_menu, $this->link)) {
			$id_admin = $this->admin;
			$periode = $this->model_payroll->getDataPayrollSingle(['create_by'=>$id_admin,'kode_master_penggajian'=>'BULANAN'],'kode_periode');
			if(empty($periode)){
				$periode = '';
			}else{
				$periode = $this->otherfunctions->convertResultToRowArray($periode)['kode_periode'];
			}
			// $where=['a.status_gaji'=>0,'a.status'=>1,'a.create_by'=>$this->admin,'a.kode_master_penggajian'=>'BULANAN'];
			// if ($this->dtroot['adm']['level'] <= 2){
			// 	$where=['a.status_gaji'=>0,'a.status'=>1,'a.kode_master_penggajian'=>'BULANAN'];
			// }
			$where=['a.status_gaji'=>0,'a.status'=>1,'a.kode_master_penggajian'=>'BULANAN'];
			if ($this->dtroot['adm']['level'] != 0){
				$where="a.status_gaji=0 AND a.status=1 AND (a.create_by = '40' OR a.create_by = '76') AND a.kode_master_penggajian='BULANAN'";
			}
			$list_periode = $this->model_master->getPeriodePenggajian($where,null,1);
			$data=[
				'access'=>$this->access,
				'kode_periode'=>$periode,
				'periode'=>$list_periode,
				'id_admin'=>$this->admin,
				'status_admin'=>$this->dtroot['adm']['level'],
				'indukTunjanganTetap'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>1],'a.sifat','DESC'),
				'indukTunjanganNon'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>0],'a.sifat','DESC'),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_penggajian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	public function slip_gaji()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		if ($this->dtroot['adm']['level'] > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		if($data_filter['usage'] == 'data'){
			if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			$data_gaji = $this->model_payroll->getDataPayroll($form_filter);
		}else{
			if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			$data_gaji = $this->model_payroll->getDataLogPayroll($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/slip_gaji',$data);
		$this->load->view('print_page/footer');
	}
	public function tanda_terima_gaji()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		$where='';
		if ($data_filter['periode']){
			$where.="a.kode_periode = '".$data_filter['periode']."'";
		}
		if(!empty($data_filter['bagian'])){
			if(in_array('all',$data_filter['bagian'])){
				$where.=" AND a.kode_master_penggajian='BULANAN'";
			}else{
				$c_lv=1;
				$where.=" AND a.kode_master_penggajian='BULANAN'";
				$bag=$this->model_global->searchWhereCustom($data_filter['bagian'],'kode_bagian','OR','a');
				if ($bag){
					$where.=' AND '.$bag;
				}
			}
		}
		if($data_filter['usage'] == 'data'){
			$getdata = $this->model_payroll->getDataPayrollNew($where);
		}else{
			$getdata = $this->model_payroll->getDataLogPayroll($where);
		}
		// print_r($getdata);
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$getdata,
			'periode'=>$periode,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/tanda_terima_gaji',$data);
		$this->load->view('print_page/footer');		
	}


	public function log_data_penggajian(){
		$nama_menu="log_data_penggajian";
		if (in_array($nama_menu, $this->link)) {
			$where=['a.kode_master_penggajian'=>'BULANAN'];
			if ($this->dtroot['adm']['level'] != 0){
				$where="a.create_by = '40' OR a.create_by = '76'";
				// $where=['a.create_by'=>$this->admin,'a.kode_master_penggajian'=>'BULANAN'];
			}
			$periode = $this->model_payroll->getDataLogPayroll($where,0,'a.kode_periode', 'a.id_pay DESC');
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin,
				'indukTunjanganTetap'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>1]),
				'indukTunjanganNon'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>0]),
				'periode'=>$periode,
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/log_data_penggajian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	public function rekap_payroll()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$emp = $this->input->post('karyawan');
		$kary = $this->model_karyawan->getEmpID($emp);
		if ($this->dtroot['adm']['level'] > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
			$form_filter['a.kode_bagian'] = $data_filter['bagian'];
		}
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getDataPayroll($form_filter);
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayroll($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$form_filter['a.kode_periode']]));
		$nama_bagian='UNKNOWN';
		$nama_loker='UNKNOWN';
		$nama_buat='UNKNOWN';
		if ($data_gaji){
			foreach ($data_gaji as $dt) {
				$nama_bagian =$dt->nama_bagian;
				$nama_loker =$dt->nama_loker;
				$nama_buat =$dt->nama_buat;
			}
		}
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
			'nama_bagian'=>$nama_bagian,
			'nama_loker'=>$nama_loker,
			'nama_buat'=>$nama_buat,
			'menyetujui'=>$kary['nama'],
			'jbt_menyetujui'=>$kary['nama_jabatan'],
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_payroll',$data);
		$this->load->view('print_page/footer');

	}
	public function rekap_gaji_bulanan_bagian()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$mgt = $this->input->post('mengetahui');
		$myt = $this->input->post('menyetujui');
		$mengetahui = $this->model_karyawan->getEmpID($mgt);
		$menyetujui = $this->model_karyawan->getEmpID($myt);
		if ($this->dtroot['adm']['level'] > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
			$form_filter['a.kode_bagian'] = $data_filter['bagian'];
		}
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getRekapitulasiDataPayrollBulanan($form_filter);
		}else{
			$data_gaji = $this->model_payroll->getRekapitulasiDataLogPayrollBulanan($form_filter);
		}
		$nama_bagian='UNKNOWN';
		$nama_loker='UNKNOWN';
		$nama_buat='UNKNOWN';
		if ($data_gaji){
			foreach ($data_gaji as $dt) {
				$nama_bagian =$dt->nama_bagian;
				$nama_loker =$dt->nama_loker;
				$nama_buat =$dt->nama_buat;
			}
		}
		// echo '<pre>';
		// print_r($data_gaji_all);
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
			'nama_bagian'=>$nama_bagian,
			'nama_loker'=>$nama_loker,
			'nama_buat'=>$nama_buat,
			'mengetahui'=>$mengetahui['nama'],
			'jbt_mengetahui'=>$mengetahui['nama_jabatan'],
			'menyetujui'=>$menyetujui['nama'],
			'jbt_menyetujui'=>$menyetujui['nama_jabatan'],
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_gaji_bulanan_bagian',$data);
		$this->load->view('print_page/footer');
	}	

	public function rekapitulasi_payroll()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		if ($this->dtroot['adm']['level'] > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }

		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_karyawan->getDataPayroll($form_filter);
		}else{
			$data_gaji = $this->model_karyawan->getDataLogPayroll($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekapitulasi_payroll',$data);
		$this->load->view('print_page/footer');
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Periode Lembur
	public function periode_lembur(){
		$nama_menu="periode_lembur";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,
				'id_admin'=>$this->admin,
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_periode_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_periode_lembur(){
		$nama_menu="view_periode_lembur";
		if (in_array($nama_menu, $this->link)) {

			if($this->dtroot['adm']['level'] == 0){
				$loker = 'empty';
				$nama_loker = 'empty';
				$status_adm = $this->dtroot['adm']['level'];
			}else{
				$emp = $this->model_karyawan->getEmployeeId($this->dtroot['adm']['id_karyawan']);
				$loker = $emp['loker'];
				$nama_loker = $emp['nama_loker'];
				$status_adm = $this->dtroot['adm']['level'];
			}

			$kode = $this->codegenerator->decryptChar($this->uri->segment(3));
			$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode]));
			$data=[
				'access'=>$this->access,
				'periode'=>$periode,
				'loker'=>$loker,
				'nama_loker'=>$nama_loker,
				'status_adm'=>$status_adm
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_periode_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data Penggajian Lembur
	public function data_penggajian_lembur(){
		$nama_menu="data_penggajian_lembur";
		if (in_array($nama_menu, $this->link)) {
			$periode = $this->model_payroll->getDataPayrollLeburSingle(null,'kode_periode');
			if(empty($periode)){
				$periode = '';
			}else{
				$periode = $this->otherfunctions->convertResultToRowArray($periode)['kode_periode'];
			}
			// $where=['a.status_gaji'=>0,'a.create_by'=>$this->admin,'a.kode_master_penggajian'=>'BULANAN'];
			// if ($this->dtroot['adm']['level'] < 1){
			// 	$where=['a.status_gaji'=>0,'a.kode_master_penggajian'=>'BULANAN'];
			// }
			$where=['a.status_gaji'=>0,'a.status'=>1,'a.kode_master_penggajian'=>'BULANAN'];
			if ($this->dtroot['adm']['level'] != 0){
				$where="a.status_gaji=0 AND a.status=1 AND (a.create_by = '40' OR a.create_by = '76') AND a.kode_master_penggajian='BULANAN'";
			}
			$list_periode = $this->model_master->getListPeriodeLembur($where,null,1);
			$data=[
				'access'=>$this->access,
				'kode_periode'=>$periode,
				'periode'=>$list_periode,
				'id_admin'=>$this->admin,
				'status_admin'=>$this->dtroot['adm']['level'],
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_penggajian_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	public function slip_lembur()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if (!is_null($level) && $level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		$form_filter['a.gaji_terima !='] = 0;
		if($data_filter['usage'] == 'data'){
			if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			// $data_gaji = $this->model_payroll->getDataPayrollLembur($form_filter,0,null,['kolom'=>'a.nama_karyawan','value'=>'ASC']);
			$data_gaji = $this->model_payroll->getDataPayrollLemburExcel($form_filter,['kolom'=>'a.nama_karyawan','value'=>'ASC']);
		}else{
			if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			$data_gaji = $this->model_payroll->getDataLogPayrollLembur($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/slip_lembur',$data);
		$this->load->view('print_page/footer');
		
	}
	public function tanda_terima_lembur()
	{
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		// print_r($data_filter);
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		$where='';
		if (!is_null($level) && $level > 2){
			$where.="a.create_by='".$this->admin."' AND ";
		}
		if(!empty($data_filter['bagian'])){
			if(in_array('all',$data_filter['bagian'])){
				$where.=" a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
			}else{
				$c_lv=1;
				foreach ($data_filter['bagian'] as $key => $bag) {
					$where.=" a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."' AND a.kode_bagian='".$bag."'";
					if (count($data_filter['bagian']) > $c_lv) {
						$where.=' OR ';
					}
					$c_lv++;
				}
			}
		}
		if($data_filter['usage'] == 'data'){
			$getdata = $this->model_payroll->getDataPayrollLemburExcel($where,['kolom'=>'emp.nama','value'=>'ASC']);
		}elseif($data_filter['usage'] == 'log'){
			$getdata = $this->model_payroll->getDataLogPayrollLembur($where);
		}else{
			$getdata = $this->model_payroll->getRekapitulasiDataLogPayrollLembur($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$getdata,
			'periode'=>$periode,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/tanda_terima_lembur',$data);
		$this->load->view('print_page/footer');		
	}
	public function rekap_lembur()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$emp = $this->input->post('karyawan');
		$kary = $this->model_karyawan->getEmpID($emp);
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
			$form_filter['a.kode_bagian'] = $data_filter['bagian'];
		}
		// $where='';
		// $c_lv=1;
		// if(!empty($data_filter['bagian'])){
		// 	foreach ($data_filter['bagian'] as $key => $bag) {
		// 		$where.="a.create_by='".$this->admin."' AND a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."' AND a.kode_bagian='".$bag."'";
		// 		if (count($data_filter['bagian']) > $c_lv) {
		// 			$where.=' OR ';
		// 		}
		// 		$c_lv++;
		// 	}
		// }
		if($data_filter['usage'] == 'data'){
			// $getdata = $this->model_payroll->getDataPayrollLemburExcel($where,['kolom'=>'emp.nama','value'=>'ASC']);
			$data_gaji = $this->model_payroll->getDataPayrollLembur($form_filter,1,null,['kolom'=>'emp.nama','value'=>'ASC']);
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayrollLembur($form_filter);
		}
		$nama_bagian ='UNKNOWN';
		$nama_loker ='UNKNOWN';
		$nama_buat ='UNKNOWN';
		if ($data_gaji){
			foreach ($data_gaji as $dt) {
				$nama_bagian =$dt->nama_bagian;
				$nama_loker =$dt->nama_loker;
				$nama_buat =$dt->nama_buat;
			}
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
			'nama_bagian'=>$nama_bagian,
			'nama_loker'=>$nama_loker,
			'nama_buat'=>$nama_buat,
			'menyetujui'=>$kary['nama'],
			'jbt_menyetujui'=>$kary['nama_jabatan'],
			'jenis' => $this->input->post('jenis'),
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_lembur',$data);
		$this->load->view('print_page/footer');
	}
	public function rekap_lembur_bagian()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$mgt = $this->input->post('mengetahui');
		$myt = $this->input->post('menyetujui');
		$mengetahui = $this->model_karyawan->getEmpID($mgt);
		$menyetujui = $this->model_karyawan->getEmpID($myt);
		$form_filter['a.create_by'] = $this->admin;
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
			$form_filter['a.kode_bagian'] = $data_filter['bagian'];
		}
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getRekapitulasiDataPayrollLembur($form_filter);
		}else{
			$data_gaji = $this->model_payroll->getRekapitulasiDataLogPayrollLembur($form_filter);
		}
		foreach ($data_gaji as $dt) {
			$nama_bagian =$dt->nama_bagian;
			$nama_loker =$dt->nama_loker;
			$nama_buat =$dt->nama_buat;
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
			'nama_bagian'=>$nama_bagian,
			'nama_loker'=>$nama_loker,
			'nama_buat'=>$nama_buat,
			'mengetahui'=>$mengetahui['nama'],
			'jbt_mengetahui'=>$mengetahui['nama_jabatan'],
			'menyetujui'=>$menyetujui['nama'],
			'jbt_menyetujui'=>$menyetujui['nama_jabatan'],
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_lembur_bagian',$data);
		$this->load->view('print_page/footer');
	}	
	public function rekapitulasi_lembur()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);

		$form_filter['a.create_by'] = $this->admin;
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }

		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getDataPayrollLembur($form_filter);
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayrollLembur($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
			'id_admin'=>$this->admin,
			'kode_periode'=>$form_filter['a.kode_periode'],
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekapitulasi_lembur',$data);
		$this->load->view('print_page/footer');
	}

	public function log_data_penggajian_lembur(){
		$nama_menu="log_data_penggajian_lembur";
		if (in_array($nama_menu, $this->link)) {
			$periode = $this->model_payroll->getDataPayrollLeburSingle(null,'kode_periode');
			if(empty($periode)){
				$periode = '';
			}else{
				$periode = $this->otherfunctions->convertResultToRowArray($periode)['kode_periode'];
			}
			$where=['a.create_by'=>$this->admin,'a.kode_master_penggajian'=>'BULANAN'];
			if ($this->dtroot['adm']['level'] <= 2){
				$where=['a.kode_master_penggajian'=>'BULANAN'];
			}
			$list_periode = $this->model_master->getListPeriodeLembur($where,null,1);
			$data=[
				'access'=>$this->access,
				'kode_periode'=>$periode,
				'periode'=>$list_periode,
				'id_admin'=>$this->admin,
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/log_data_penggajian_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data BPJS
	public function data_bpjs(){
		$nama_menu="data_bpjs";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,
					'jkk'=>$this->model_master->getListBpjsRow(['inisial'=>'JKK-RS','a.status'=>1])['bpjs_karyawan'],
					'jkm'=>$this->model_master->getListBpjsRow(['inisial'=>'JKM','a.status'=>1])['bpjs_karyawan'],
					'jht'=>$this->model_master->getListBpjsRow(['inisial'=>'JHT','a.status'=>1])['bpjs_karyawan'],
					'jpns'=>$this->model_master->getListBpjsRow(['inisial'=>'JPNS','a.status'=>1])['bpjs_karyawan'],
					'jkes'=>$this->model_master->getListBpjsRow(['inisial'=>'JKES','a.status'=>1])['bpjs_karyawan'],
					];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_bpjs',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data Pendukung
	public function data_pendukung(){
		$nama_menu="data_pendukung";
		if (in_array($nama_menu, $this->link)) {
			$nama ='';
			$kode = $this->codegenerator->decryptChar($this->uri->segment(4));
			$tunjangan = $this->otherfunctions->convertResultToRowArray($this->model_master->getListIndukTunjanganKode($kode));
			if(!empty($tunjangan)){
				$nama = $tunjangan['nama'];
			}
			$data=[
				'access'=>$this->access,
				'usage'=>$this->uri->segment(3),
				'jenis'=>$this->otherfunctions->JenisPendukungPayroll(),
				'nama'=>$nama,
				'admin'=>$this->admin,
				'level'=>$this->dtroot['adm']['level'],
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_pendukung',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	public function rekap_data_pendukung_lain()
	{
		$data_pendukung_lain = $this->model_payroll->getListDataPendukungLain(null,null,null,'a.id_karyawan asc');
		$data = ['data'=>$data_pendukung_lain,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_data_pendukung_lain',$data);
		$this->load->view('print_page/footer');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Rekap Data Insentif
	public function rekap_data_insentif()
	{
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
		$no=1;
		$datax=[];
		foreach ($emp_insx as $ik => $iv) {
			$id_karyawan = $ik;
			$data_emp = $this->model_karyawan->getEmployeeId($id_karyawan);
			foreach ($data_selet_ins as $si) {
				$send_ins = $this->otherfunctions->convertResultToRowArray($this->model_master->getInsentifWhere(['id_insentif'=>$si->id_insentif]));
				$get_ins = $this->payroll->getInsentifPerId($send_ins,$id_karyawan);
				if(!empty($get_ins)){
					$datax[] = [
						'id_karyawan'=>$data_emp['id_karyawan'],
						'nik'=>$data_emp['nik'],
						'nama_karyawan'=>$data_emp['nama'],
						'jabatan'=>$data_emp['nama_jabatan'],
						'bagian'=>$data_emp['bagian'],
						'loker'=>$data_emp['nama_loker'],
						'grade'=>$data_emp['nama_grade'],
						'nama_insentif'=>$get_ins['nama'],
						'nominal_insentif'=>$get_ins['nominal'],
						'tahun_insentif'=>$get_ins['tahun'],
					];
				}
			}
			$no++;
		}
		$data = [
			'data'=>$datax,
			'data_emp'=>$emp_insx
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_data_insentif',$data);
		$this->load->view('print_page/footer');
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data PPH-21
	public function kode_akun(){
		$nama_menu="kode_akun";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_kode_akun',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function data_pesangon(){
		$nama_menu="data_pesangon";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_pesangon',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function data_pph_21(){
		$nama_menu="data_pph_21";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin,
				'indukTunjanganTetap'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>1]),
				'indukTunjanganNon'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>0]),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_pph_21',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function data_pph21_harian(){
		$nama_menu="data_pph21_harian";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin,
				'indukTunjanganTetap'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>1]),
				'indukTunjanganNon'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>0]),
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_pph_21_harian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function pph_21_non_karyawan(){
		$nama_menu="pph_21_non_karyawan";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_pph_21_non_karyawan',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	public function rekap_data_pph_21()
	{
		$kode_periode = $this->input->post('kode_periode');
		if(empty($kode_periode)){
			redirect('pages/not_found');
		}else{
			$data_pph = $this->model_payroll->getListDataPenggajianPph(['a.kode_periode'=>$kode_periode]);
			$data_pph_other = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPph(['a.kode_periode'=>$kode_periode]));
			$data = [
				'data'=>$data_pph,
				'nama_periode'=>$data_pph_other['nama_periode'],
				'nama_sistem_penggajian'=>$data_pph_other['nama_sistem_penggajian']
			];
			$this->load->view('print_page/header');
			$this->load->view('print_page/rekap_pph_21.php',$data);
			$this->load->view('print_page/footer');
		}
		
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data PPH-21
	public function master_petugas_payroll(){
		$nama_menu="master_petugas_payroll";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_petugas_payroll',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function master_petugas_pph(){
		$nama_menu="master_petugas_pph";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_petugas_pph',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function master_petugas_lembur(){
		$nama_menu="master_petugas_lembur";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/master_petugas_lembur',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Periode Penggajian
	public function periode_penggajian_harian(){
		$nama_menu="periode_penggajian_harian";
		if (in_array($nama_menu, $this->link)) {
			$data=['access'=>$this->access,
				'id_admin'=>$this->admin,];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_periode_penggajian_harian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	public function view_periode_penggajian_harian(){
		$nama_menu="view_periode_penggajian_harian";
		if (in_array($nama_menu, $this->link)) {
			if($this->dtroot['adm']['level'] == 0){
				$loker = 'empty';
				$nama_loker = 'empty';
				$status_adm = $this->dtroot['adm']['level'];
			}else{
				$emp = $this->model_karyawan->getEmployeeId($this->dtroot['adm']['id_karyawan']);
				$loker = $emp['loker'];
				$nama_loker = $emp['nama_loker'];
				$status_adm = $this->dtroot['adm']['level'];
			}
			$kode = $this->codegenerator->decryptChar($this->uri->segment(3));
			$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$kode]));
			$nama = $periode['nama'];
			$bulan = $this->formatter->getNameOfMonth(date('m', strtotime($periode['tgl_selesai'])));
			$tahun = date('Y', strtotime($periode['tgl_selesai']));
			$data=[
				'access'=>$this->access,
				'periode'=>$periode,
				'nama_periode'=>$nama,
				'bulan_periode'=>$bulan,
				'tahun_periode'=>$tahun,
				'loker'=>$loker,
				'nama_loker'=>$nama_loker,
				'status_adm'=>$status_adm
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/view_periode_penggajian_harian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Data Penggajian Harian
	public function data_penggajian_harian(){
		$nama_menu="data_penggajian_harian";
		if (in_array($nama_menu, $this->link)) {
			// $id_admin = $this->admin;
			// $periode = $this->model_payroll->getDataLogPayrollSingle(['create_by'=>$id_admin,'kode_master_penggajian'=>'HARIAN'],'kode_periode');
			// if(empty($periode)){
			// 	$periode = '';
			// }else{
			// 	$periode = $this->otherfunctions->convertResultToRowArray($periode)['kode_periode'];
			// }
			$data=[
				'access'=>$this->access,
				// 'kode_periode'=>$periode,
				'id_admin'=>$this->admin,
				'status_admin'=>$this->dtroot['adm']['level'],
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/data_penggajian_harian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}

	public function slip_gaji_harian()
	{
		$id_admin = $this->admin;
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);		
		// $form_filter['a.create_by'] = $id_admin;
		// $form_filter['a.kode_master_penggajian'] = 'HARIAN';
		if(!empty($data_filter['bagian'])){ $form_filter['a.kode_bagian'] = $data_filter['bagian']; }
		if(!empty($data_filter['lokasi'])){ $form_filter['a.kode_loker'] = $data_filter['lokasi']; }
		if(!empty($data_filter['minggu'])){ $form_filter['a.minggu'] = $data_filter['minggu']; }
		if(!empty($data_filter['bulan'])){ $form_filter['a.bulan'] = $data_filter['bulan']; }
		if(!empty($data_filter['tahun'])){ $form_filter['a.tahun'] = $data_filter['tahun']; }
		// echo '<pre>';
		// print_r($form_filter);	
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc');
			$data_gaji_r = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc', true);
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayrollHarian($form_filter, null, null, 'a.tgl_masuk asc');
		}
		// $periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodePenggajianHarian('active',['a.kode_periode_penggajian_harian'=>$form_filter['a.kode_periode']]));
		$minggu_view=$this->otherfunctions->getlistWeek($data_gaji_r['minggu']);
		$bulan_view=$this->formatter->getNameOfMonth($data_gaji_r['bulan']);
		$tahun=$data_gaji_r['tahun'];
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$minggu_view.' ('.$bulan_view.' '.$tahun.')',
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/slip_gaji_harian',$data);
		$this->load->view('print_page/footer');
	}

	public function rekap_payroll_harian()
	{
		
		$id_admin = $this->admin;
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$emp = $this->input->post('karyawan');
		$mengetahui = $this->input->post('mengetahui');
		$dibuat = $this->input->post('dibuat');
		$kary = $this->model_karyawan->getEmpID($emp);
		$mengetahuix = $this->model_karyawan->getEmpID($mengetahui);
		$dibuatx = $this->model_karyawan->getEmpID($dibuat);
		// $form_filter['a.create_by'] = $id_admin;
		$form_filter['a.kode_master_penggajian'] = 'HARIAN';
		if(!empty($data_filter['bagian'])){ $form_filter['a.kode_bagian'] = $data_filter['bagian']; }
		if(!empty($data_filter['lokasi'])){ $form_filter['a.kode_loker'] = $data_filter['lokasi']; }
		if(!empty($data_filter['minggu'])){ $form_filter['a.minggu'] = $data_filter['minggu']; }
		if(!empty($data_filter['bulan'])){ $form_filter['a.bulan'] = $data_filter['bulan']; }
		if(!empty($data_filter['tahun'])){ $form_filter['a.tahun'] = $data_filter['tahun']; }
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc');
			$data_gaji_r = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc', true);
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayrollHarian($form_filter, null, null, 'a.tgl_masuk asc');
		}
		$nama_bagian=null;
		$nama_loker=null;
		$nama_buat=null;
		foreach ($data_gaji as $dt) {
			$nama_bagian =$dt->nama_bagian;
			$nama_loker =$dt->nama_loker;
			$nama_buat =$dt->nama_buat;
		}
		$data =[
			'emp_gaji'=>$data_gaji,
			// 'periode'=>$periode,
			'periode'=> $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc', true),
			'nama_bagian'=>$nama_bagian,
			'nama_loker'=>$nama_loker,
			'nama_buat'=>$nama_buat,
			'menyetujui'=>$kary['nama'],
			'jbt_menyetujui'=>$kary['nama_jabatan'],
			'jenis'=>$this->input->post('jenis'),
			'mengetahui'=>$mengetahuix['nama'],
			'dibuat'=>$dibuatx['nama'],
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_payroll_harian',$data);
		$this->load->view('print_page/footer');
	}
	public function rekap_gaji_harian_bagian()
	{
		// parse_str($this->input->post('data_filter'), $data_filter);
		// $mgt = $this->input->post('mengetahui');
		// $myt = $this->input->post('menyetujui');
		// $mengetahui = $this->model_karyawan->getEmpID($mgt);
		// $menyetujui = $this->model_karyawan->getEmpID($myt);
		// $form_filter['a.create_by'] = $this->admin;
		// $form_filter['a.kode_master_penggajian'] = 'HARIAN';
		// if(!empty($data_filter['periode'])){
		// 	$form_filter['a.kode_periode'] = $data_filter['periode'];
		// }
		// if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
		// 	$form_filter['a.kode_bagian'] = $data_filter['bagian'];
		// }	
		// $id_admin = $this->admin;
		// parse_str($this->input->post('data_filter'), $data_filter);
		// $mgt = $this->input->post('mengetahui');
		// $myt = $this->input->post('menyetujui');
		// $mengetahui = $this->model_karyawan->getEmpID($mgt);
		// $menyetujui = $this->model_karyawan->getEmpID($myt);
		// $form_filter['a.create_by'] = $id_admin;
		// $form_filter['a.kode_master_penggajian'] = 'HARIAN';
		// if(!empty($data_filter['bagian'])){ $form_filter['a.kode_bagian'] = $data_filter['bagian']; }
		// if(!empty($data_filter['lokasi'])){ $form_filter['a.kode_loker'] = $data_filter['lokasi']; }
		// if(!empty($data_filter['minggu'])){ $form_filter['a.minggu'] = $data_filter['minggu']; }
		// if(!empty($data_filter['bulan'])){ $form_filter['a.bulan'] = $data_filter['bulan']; }
		// if(!empty($data_filter['tahun'])){ $form_filter['a.tahun'] = $data_filter['tahun']; }
		// if($data_filter['usage'] == 'data'){
		// 	$data_gaji = $this->model_payroll->getRekapitulasiDataPayrollHarian($form_filter);
		// }else{
		// 	$data_gaji = $this->model_payroll->getRekapitulasiDataLogPayrollHarian($form_filter);
		// }
		// foreach ($data_gaji as $dt) {
		// 	$nama_bagian =$dt->nama_bagian;
		// 	$nama_loker =$dt->nama_loker;
		// 	$nama_buat =$dt->nama_buat;
		// }
		// $prd = $this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$form_filter['a.kode_periode']]);
		// $periode = $this->otherfunctions->convertResultToRowArray($prd);
		// $data =[
		// 	'emp_gaji'=>$data_gaji,
		// 	'periode'=>$periode,
		// 	'nama_bagian'=>$nama_bagian,
		// 	'nama_loker'=>$nama_loker,
		// 	'nama_buat'=>$nama_buat,
		// 	'mengetahui'=>$mengetahui['nama'],
		// 	'jbt_mengetahui'=>$mengetahui['nama_jabatan'],
		// 	'menyetujui'=>$menyetujui['nama'],
		// 	'jbt_menyetujui'=>$menyetujui['nama_jabatan'],
		// ];
		$id_admin = $this->admin;
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$emp = $this->input->post('menyetujui');
		$kary = $this->model_karyawan->getEmpID($emp);
		$form_filter['a.create_by'] = $id_admin;
		$form_filter['a.kode_master_penggajian'] = 'HARIAN';
		if(!empty($data_filter['bagian'])){ $form_filter['a.kode_bagian'] = $data_filter['bagian']; }
		if(!empty($data_filter['lokasi'])){ $form_filter['a.kode_loker'] = $data_filter['lokasi']; }
		if(!empty($data_filter['minggu'])){ $form_filter['a.minggu'] = $data_filter['minggu']; }
		if(!empty($data_filter['bulan'])){ $form_filter['a.bulan'] = $data_filter['bulan']; }
		if(!empty($data_filter['tahun'])){ $form_filter['a.tahun'] = $data_filter['tahun']; }
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc');
			$data_gaji_r = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc', true);
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayrollHarian($form_filter, null, null, 'a.tgl_masuk asc');
		}
		$nama_bagian=null;
		$nama_loker=null;
		$nama_buat=null;
		foreach ($data_gaji as $dt) {
			$nama_bagian =$dt->nama_bagian;
			$nama_loker =$dt->nama_loker;
			$nama_buat =$dt->nama_buat;
		}
		$data =[
			'emp_gaji'=>$data_gaji,
			// 'periode'=>$periode,
			'periode'=> $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc', true),
			'nama_bagian'=>$nama_bagian,
			'nama_loker'=>$nama_loker,
			'nama_buat'=>$nama_buat,
			'menyetujui'=>$kary['nama'],
			'jbt_menyetujui'=>$kary['nama_jabatan'],
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_gaji_harian_bagian',$data);
		$this->load->view('print_page/footer');
	}

	public function tanda_terima_gaji_harian()
	{
		$id_admin = $this->admin;
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$form_filter['a.create_by'] = $id_admin;
		$form_filter['a.kode_master_penggajian'] = 'HARIAN';
		if(!empty($data_filter['bagian'])){ $form_filter['a.kode_bagian'] = $data_filter['bagian']; }
		if(!empty($data_filter['lokasi'])){ $form_filter['a.kode_loker'] = $data_filter['lokasi']; }
		if(!empty($data_filter['minggu'])){ $form_filter['a.minggu'] = $data_filter['minggu']; }
		if(!empty($data_filter['bulan'])){ $form_filter['a.bulan'] = $data_filter['bulan']; }
		if(!empty($data_filter['tahun'])){ $form_filter['a.tahun'] = $data_filter['tahun']; }
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc');
			$data_gaji_r = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc', true);
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayrollHarian($form_filter, null, null, 'a.tgl_masuk asc');
		}
		$nama_bagian=null;
		$nama_loker=null;
		$nama_buat=null;
		foreach ($data_gaji as $dt) {
			$nama_bagian =$dt->nama_bagian;
			$nama_loker =$dt->nama_loker;
			$nama_buat =$dt->nama_buat;
		}
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=> $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc', true),
			'nama_bagian'=>$nama_bagian,
			'nama_loker'=>$nama_loker,
			'nama_buat'=>$nama_buat,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/tanda_terima_gaji_harian',$data);
		$this->load->view('print_page/footer');		
	}
	public function log_data_penggajian_harian(){
		$nama_menu="log_data_penggajian_harian";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin,
			];
			$this->load->view('admin_tem/headerx',$this->dtroot);
			$this->load->view('admin_tem/sidebarx',$this->dtroot);
			$this->load->view('pages/log_data_penggajian_harian',$data);
			$this->load->view('admin_tem/footerx');	
		}else{
			redirect('pages/not_found');
		}
	}
	//============================================================= BLOCK CHANGE =============================================================//
	//========================================================= PAGE PENILAIAN BEGIN =========================================================//
	//----------------------------------------------------------------------------------------------------------------------------------------//
	//Leaderboard
		public function leaderboard(){
			$nama_menu="leaderboard";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/leaderboard',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Konsep KPI
		public function data_konsep_kpi(){
			$nama_menu="data_konsep_kpi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_konsep_kpi',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_data_konsep_kpi(){
			$nama_menu="view_data_konsep_kpi";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$cek=$this->model_concept->getKonsepKpiKode($kode);
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_konsep_kpi');
				}else{
					$data=[
						'nama'=>$cek['nama'],
						'kode'=>$cek['kode_c_kpi'],
						'tabel'=>$cek['nama_tabel'],
						'access'=>$this->access,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/view_data_konsep_kpi',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_detail_konsep_kpi(){
			$nama_menu="view_data_konsep_kpi";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$kode_jbt=$this->codegenerator->decryptChar($this->uri->segment(4));
				$cek=$this->model_concept->getKonsepKpiKode($kode);
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_konsep_kpi');
				}else{
					$tabel=$cek['nama_tabel'];
					$cek_jabatan=$this->model_concept->checkTableJabatanViewConceptKpi($tabel,$kode_jbt);
					if ($cek_jabatan == 0 || $cek_jabatan == null) {
						$this->messages->sessNotValidParam();  
						redirect('pages/view_data_konsep_kpi/'.$this->uri->segment(3));
					}else{
						$jbt=$this->model_master->getJabatanKodeRow($kode_jbt);
						$data=[
							'nama'=>$cek['nama'],
							'jabatan'=>$jbt['nama'],
							'kode'=>$cek['kode_c_kpi'],
							'tabel'=>$cek['nama_tabel'],
							'kode_jabatan'=>$kode_jbt,
							'access'=>$this->access,
							'level'=>$this->dtroot['adm']['level'],
						];
						$this->load->view('admin_tem/headerx',$this->dtroot);
						$this->load->view('admin_tem/sidebarx',$this->dtroot);
						$this->load->view('pages/view_detail_konsep_kpi',$data);
						$this->load->view('admin_tem/footerx');
					}
				}
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Konsep Sikap
		public function data_konsep_sikap(){
			$nama_menu="data_konsep_sikap";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_konsep_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_data_konsep_sikap(){
			$nama_menu="view_data_konsep_sikap";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$cek=$this->model_concept->getKonsepSikapKode($kode);
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_konsep_sikap');
				}else{
					$data=[
						'nama'=>$cek['nama'],
						'kode'=>$cek['kode_c_sikap'],
						'tabel'=>$cek['nama_tabel'],
						'access'=>$this->access,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/view_data_konsep_sikap',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_detail_konsep_sikap(){
			$nama_menu="view_data_konsep_sikap";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$nik=$this->codegenerator->decryptChar($this->uri->segment(4));
				$cek=$this->model_concept->getKonsepSikapKode($kode);
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_konsep_sikap');
				}else{
					$tabel=$cek['nama_tabel'];
					$cek_nik=$this->model_concept->checkTableEmployeeViewConceptSikap($tabel,$nik);
					if ($cek_nik == 0 || $cek_nik == null) {
						$this->messages->sessNotValidParam();  
						redirect('pages/view_data_konsep_sikap/'.$this->uri->segment(3));
					}else{
						$emp=$this->model_karyawan->getEmployeeNik($nik);
						$data=[
							'nama'=>$cek['nama'],
							'kode'=>$cek['kode_c_sikap'],
							'tabel'=>$cek['nama_tabel'],
							'emp_nama'=>$emp['nama'],
							'emp_id'=>$emp['id_karyawan'],
							'access'=>$this->access,
						];
						$this->load->view('admin_tem/headerx',$this->dtroot);
						$this->load->view('admin_tem/sidebarx',$this->dtroot);
						$this->load->view('pages/view_detail_konsep_sikap',$data);
						$this->load->view('admin_tem/footerx');
					}
				}
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Konsep Kompetensi
		public function data_konsep_kompetensi(){
			$nama_menu="data_konsep_kompetensi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_konsep_kompetensi',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_data_konsep_kompetensi(){
			$nama_menu="view_data_konsep_kompetensi";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$cek=$this->model_concept->getKonsepKompetensiKode($kode);
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_konsep_kompetensi');
				}else{
					$data=[
						'nama'=>$cek['nama'],
						'kode'=>$cek['kode_c_kompetensi'],
						'tabel'=>$cek['nama_tabel'],
						'access'=>$this->access,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/view_data_konsep_kompetensi',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_detail_konsep_kompetensi(){
			$nama_menu="view_data_konsep_kompetensi";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$nik=$this->codegenerator->decryptChar($this->uri->segment(4));
				$cek=$this->model_concept->getKonsepKompetensiKode($kode);
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_konsep_kompetensi');
				}else{
					$tabel=$cek['nama_tabel'];
					$cek_nik=$this->model_concept->checkTableEmployeeViewConcept($tabel,$nik);
					if ($cek_nik == 0 || $cek_nik == null) {
						$this->messages->sessNotValidParam();  
						redirect('pages/view_data_konsep_kompetensi/'.$this->uri->segment(3));
					}else{
						$emp=$this->model_karyawan->getEmployeeNik($nik);
						$kat_kompetensi=$this->model_concept->getKategoriKompetensi($tabel,$emp['id_karyawan']);
						$data=[
							'nama'=>$cek['nama'],
							'kode'=>$cek['kode_c_kompetensi'],
							'tabel'=>$cek['nama_tabel'],
							'emp_nama'=>$emp['nama'],
							'emp_id'=>$emp['id_karyawan'],
							'access'=>$this->access,
							'kategori'=>array_unique($kat_kompetensi),
							'tipe_jabatan'=>$emp['tipe_jabatan'],
						];
						$this->load->view('admin_tem/headerx',$this->dtroot);
						$this->load->view('admin_tem/sidebarx',$this->dtroot);
						$this->load->view('pages/view_detail_konsep_kompetensi',$data);
						$this->load->view('admin_tem/footerx');
					}
				}
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Agenda KPI
		public function data_agenda_kpi(){
			$nama_menu="data_agenda_kpi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,'tahun'=>$this->formatter->getYear(),];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_agenda_kpi',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Agenda Aspek Sikap
		public function data_agenda_sikap(){
			$nama_menu="data_agenda_sikap";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,'tahun'=>$this->formatter->getYear(),];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_agenda_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_data_agenda_sikap(){
			$nama_menu="view_data_agenda_sikap";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$cek=$this->model_agenda->getAgendaSikapKode($kode);
				if (!isset($cek) || empty($this->uri->segment(3))){
					redirect('pages/not_found');
				}
				$data=['access'=>$this->access,'nama'=>$cek['nama'],'tabel'=>$cek['nama_tabel']];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_data_agenda_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_detail_agenda_sikap(){
			$nama_menu="view_detail_agenda_sikap";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,'tahun'=>$this->formatter->getYear(),];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_detail_agenda_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Agenda Reward
		public function data_agenda_reward(){
			$nama_menu="data_agenda_reward";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,'tahun'=>$this->formatter->getYear(),];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_agenda_reward',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Agenda Kompetensi
		public function data_agenda_kompetensi(){
			$nama_menu="data_agenda_kompetensi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,'tahun'=>$this->formatter->getYear(),];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_agenda_kompetensi',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Input KPI
		public function data_input_kpi(){
			$nama_menu="data_input_kpi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_input_kpi',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_employee_kpi(){
			$nama_menu="view_employee_kpi";
			$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
			$cek=$this->model_agenda->getAgendaKpiKode($kode);
			if (in_array($nama_menu, $this->link)) {
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_input_kpi');
				}else{
					$data=[
						'access'=>$this->access,
						'kode'=>$cek['kode_a_kpi'],
						'periode'=>$cek['periode'],
						'tahun'=>$cek['tahun'],
						'start'=>$cek['start'],
						'end'=>$cek['end'],
						'tabel'=>$cek['nama_tabel'],
						'batas'=>$cek['batas'],
						'agd'=>$cek,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/view_employee_kpi',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}
		public function input_kpi_value(){
			$nama_menu="input_kpi_value";
			$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
			$cek=$this->model_agenda->getAgendaKpiKode($kode);
			$id_kar=$this->codegenerator->decryptChar($this->uri->segment(4));
			if (in_array($nama_menu, $this->link)) {
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_input_kpi');
				}else{
					$kary = $this->model_karyawan->getEmployeeId($id_kar);
					$data=[
						'access'=>$this->access,
						'nama'=>$kary['nama'],
						'tahun'=>$cek['tahun'],
						'start'=>$cek['start'],
						'end'=>$cek['end'],
						'kode'=>$kode,
						'id_kar'=>$kary['id_karyawan'],
						'tabel'=>$cek['nama_tabel'],
						'batas'=>$cek['batas'],
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/input_kpi_value',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Input Sikap
	// public function data_input_sikap(){
	// 	$nama_menu="data_input_sikap";
	// 	if (in_array($nama_menu, $this->link)) {
	// 		$data=['access'=>$this->access];
	// 		$this->load->view('admin_tem/headerx',$this->dtroot);
	// 		$this->load->view('admin_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('pages/data_input_sikap',$data);
	// 		$this->load->view('admin_tem/footerx');
	// 	}else{
	// 		redirect('pages/not_found');
	// 	}
	// }

	//--------------------------------------------------------------------------------------------------------------//
	//Input Kompetensi
		public function data_input_kompetensi(){
			$nama_menu="data_input_kompetensi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_input_kompetensi',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Input Reward
		public function data_input_reward(){
			$nama_menu="data_input_reward";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_input_reward',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function input_reward_value(){
			$nama_menu="input_reward_value";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				$cek=$this->model_agenda->checkActiveAgenda('agenda_reward',$kode,'kode_a_reward');
				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_input_reward');
				}else{
					$data=[
						'nama'=>$cek['nama'],
						'kode'=>$cek['kode_a_reward'],
						'tabel'=>$cek['nama_tabel'],
						'access'=>$this->access,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/input_reward_value',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Log Agenda Sikap
		public function data_log_agenda_sikap(){
			$nama_menu="data_log_agenda_sikap";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_log_agenda_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Log Agenda KPI
		public function data_log_agenda_kpi(){
			$nama_menu="data_log_agenda_kpi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_log_agenda_kpi',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Log Agenda Reward
		public function data_log_agenda_reward(){
			$nama_menu="data_log_agenda_reward";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_log_agenda_reward',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Hasil Penilaian Sikap
		public function data_hasil_sikap(){
			$nama_menu="data_hasil_sikap";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_hasil_sikap',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_employee_result_sikap(){
			$nama_menu="view_employee_result_sikap";
			$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
			$cek=$this->model_agenda->getAgendaSikapKode($kode);
			if (in_array($nama_menu, $this->link)) {
				if ($kode == null || $cek == null) {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_hasil_sikap');
				}else{
					$data=[
						'access'=>$this->access,
						'kode'=>$cek['kode_a_sikap'],
						'periode'=>$cek['periode'],
						'validasi'=>$cek['validasi'],
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/view_employee_result_sikap',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}
		public function report_value_sikap(){
			$nama_menu="report_value_sikap";
			$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
			$id_k=$this->codegenerator->decryptChar($this->uri->segment(4));
			$cek=$this->model_agenda->getAgendaSikapKode($kode);
			$kar=$this->model_karyawan->getEmployeeId($id_k);
			if (in_array($nama_menu, $this->link)) {
				if ($kode == null || $cek == null) {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_hasil_sikap');
				}else{
					$data=[
						'access'=>$this->access,
						'kode'=>$cek['kode_a_sikap'],
						'table'=>$cek['nama_tabel'],
						'periode'=>$cek['periode'],
						'profile'=>$kar,
						'id_kar'=>$id_k,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/report_value_sikap',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}
		public function report_detail_sikap(){
			$nama_menu="report_detail_sikap";
			$kode_sikap=$this->codegenerator->decryptChar($this->uri->segment(3));
			$kode_aspek=$this->codegenerator->decryptChar($this->uri->segment(4));
			$id_kary=$this->codegenerator->decryptChar($this->uri->segment(5));
			$nama_aspek_sikap=$this->model_master->getAspekKode($kode_aspek);
			$cek=$this->model_agenda->getAgendaSikapKode($kode_sikap);
			$kar=$this->model_karyawan->getEmployeeId($id_kary);
			if (in_array($nama_menu, $this->link)) {
				if ($kode_sikap == null || $cek == null) {
					$this->messages->sessNotValidParam();  
					redirect('pages/raport_sikap_value/'.$this->uri->segment(3).'/'.$this->uri->segment(3));
				}else{
					$data=[
						'access'=>$this->access,
						'kode'=>$cek['kode_a_sikap'],
						'periode'=>$cek['periode'],
						'profile'=>$kar,
						'aspek_sikap'=>$nama_aspek_sikap,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/report_detail_sikap',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Hasil Penilaian KPI
		public function data_hasil_kpi(){
			$nama_menu="data_hasil_kpi";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_hasil_kpi',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_employee_result_kpi(){
			$nama_menu="view_employee_result_kpi";
			if (in_array($nama_menu, $this->link)) {
				$kode= $this->codegenerator->decryptChar($this->uri->segment(3));
				$cek = $this->model_agenda->getAgendaKpiKode($kode);

				if ($kode == "" || $cek == "") {
					$this->messages->sessNotValidParam();  
					redirect('pages/data_hasil_kpi');
				}else{
					$data=[
						'kode'=>$kode,
						'access'=>$this->access,
						'agd'=>$cek,
					];
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/view_employee_result_kpi',$data);
					$this->load->view('admin_tem/footerx');
				}
			}else{
				redirect('pages/not_found');
			}
		}
		public function report_value_kpi(){
			$nama_menu="report_value_kpi";
			if (in_array($nama_menu, $this->link)) {
				$kode = $this->codegenerator->decryptChar($this->uri->segment(3));
				$cekagenda = $this->model_agenda->getAgendaKpiKode($kode);
				$nmtb = $cekagenda['nama_tabel'];
				$id=$this->codegenerator->decryptChar($this->uri->segment(4));
				$cek=$this->model_agenda->openTableAgendaIdEmployee($nmtb,$id);
				// echo '<pre>';
				// print_r($nmtb);	
				if ($cek == "" || $nmtb == "" || $id == "") {
					$this->messages->notValidParam();  
					redirect('pages/result_kpi_value/'.$this->codegenerator->encryptChar($kode));
				}else{
					$kar=['nik'=>null,'nama'=>null,'foto'=>null,'kelamin'=>null,'nama_jabatan'=>null,'nama_loker'=>null,'bagian'=>null, 'nama_jabatan_baru'=>null,'nama_loker_baru'=>null,'bagian_baru'=>null,'tgl_masuk'=>null,'email'=>null,'kode_periode'=>null];
					if (count($cek) == 0) {
						$this->messages->notValidParam();  
						redirect('pages/result_kpi_value/'.$this->codegenerator->encryptChar($kode));
					}else{
						foreach($cek as $c){
							$kar['nik']=$c->nik;
							$kar['nama']=$c->nama;
							$kar['foto']=$c->foto;
							$kar['kelamin']=$c->kelamin;
							$kar['nama_jabatan']=$c->nama_jabatan;
							$kar['nama_loker']=$c->nama_loker;
							$kar['bagian']=$c->bagian;
							$kar['nama_jabatan_baru']=$c->nama_jabatan_baru;
							$kar['nama_loker_baru']=$c->nama_loker_baru;
							$kar['bagian_baru']=$c->bagian_baru;
							$kar['tgl_masuk']=$c->tgl_masuk;
							$kar['email']=$c->email;
							$kar['kode_periode']=$c->kode_periode;
						}
					}
					
					$data=array(
						'profile'=>$kar,
						'hasil'=>$cek,
						'nmtb'=>$nmtb,
						'agd'=>$cekagenda,
						'idk'=>$id,
						'id_log'=>$this->admin
					);
					$this->load->view('admin_tem/headerx',$this->dtroot);
					$this->load->view('admin_tem/sidebarx',$this->dtroot);
					$this->load->view('pages/report_value_kpi',$data);
					$this->load->view('admin_tem/footerx');
				}
			}
		}

	//--------------------------------------------------------------------------------------------------------------//
	//Hasil Penilaian Gabungan
		public function data_hasil_group(){
			$nama_menu="data_hasil_group";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access,'periode'=>$this->model_master->getListPeriodePenilaianActive()];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_hasil_group',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_employee_result_group()
		{
			$nama_menu="view_employee_result_group";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				if (empty($kode)){
					$this->messages->sessNotValidParam();  
					redirect('pages/data_hasil_group');
				}				
				if (!empty(strpos($kode, '-'))){
					$kode=explode('-',$kode);
					$kode=['kode_periode'=>$kode[0],'tahun'=>$kode[1],'link'=>$this->uri->segment(3)];
					$pages='view_employee_result_group';
				}else{
					$kode=['tahun'=>$kode,'link'=>$this->uri->segment(3)];
					$pages='view_employee_result_group_tahunan';
				}
				$data=['access'=>$this->access,'kode'=>$kode,'periode_list'=>$this->model_master->getListPeriodePenilaian(1)];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/'.$pages,$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
		public function report_value_group()
		{
			$nama_menu="report_value_group";
			if (in_array($nama_menu, $this->link)) {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
				if (empty($kode)){
					$this->messages->sessNotValidParam();  
					redirect('pages/data_hasil_group');
				}		
				$data=['access'=>$this->access,'kode'=>$kode,'profile'=>$this->model_karyawan->getEmployeeId($kode['id'])];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/report_value_group',$data);
				$this->load->view('admin_tem/footerx');
			}else{
				redirect('pages/not_found');
			}
		}
	//=========================================================PAGE PENILAIAN END=========================================================//
	//====================================================================================================================================//

	//=================================================== PAGE DATA NON KARYAWAN START ===================================================//
	//--------------------------------------------------------------------------------------------------------------//
	//Data Non Karyawan
		public function data_non_karyawan(){
			$nama_menu="data_non_karyawan";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access, 'jenis'=>$this->model_master->getListPenerimaPajak(['a.untuk'=>'non'])];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_non_karyawan',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Transaksi Non Karyawan
		public function transaksi_non_karyawan(){
			$nama_menu="transaksi_non_karyawan";
			if (in_array($nama_menu, $this->link)) {
				$data=['access'=>$this->access];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/data_non_karyawan_transaksi',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}
		public function view_transaksi_non_karyawan(){
			$nama_menu="view_transaksi_non_karyawan";
			if (in_array($nama_menu, $this->link)) {
				$id_non 	= $this->codegenerator->decryptChar($this->uri->segment(3));
				$non_kar 	= $this->model_karyawan->getListTransaksiNonKaryawan(['a.id_non'=>$id_non],true);
				$foto		= $this->otherfunctions->getFotoValueNonKar();
				$data=['access'=>$this->access, 'non'=>$non_kar,'foto'=>$foto];
				$this->load->view('admin_tem/headerx',$this->dtroot);
				$this->load->view('admin_tem/sidebarx',$this->dtroot);
				$this->load->view('pages/view_transaksi_non_karyawan',$data);
				$this->load->view('admin_tem/footerx');	
			}else{
				redirect('pages/not_found');
			}
		}

	//=========================================================PAGE DATA NON KARYAWAN END=================================================//
	public function tes_kirim_wa(){
		$data=['access'=>$this->access];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('pages/tes_kirim_wa',$data);
		$this->load->view('admin_tem/footerx');
	}
	//====================================================================================================================================//
	//============================================= PAGE DATA SELF LEARNING =====================================//
	public function setting_materi(){
		$data=['access'=>$this->access];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('self_learning/setting_self_learning',$data);
		$this->load->view('admin_tem/footerx');
	}
	public function view_soal_learning(){
		$data=['access'=>$this->access];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('self_learning/data_soal',$data);
		$this->load->view('admin_tem/footerx');
	}
	public function view_file_materi_learning(){
		$uri3 = $this->uri->segment(3);
		$data=['access'=>$this->access];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('self_learning/data_file_materi',$data);
		$this->load->view('admin_tem/footerx');
	}
	public function data_materi(){
		$data=['access'=>$this->access];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('self_learning/data_materi',$data);
		$this->load->view('admin_tem/footerx');
	}
	// public function view_file_bagian(){
	// 	$uri3 = $this->uri->segment(3);
	// 	$data=['access'=>$this->access];
	// 	$this->load->view('admin_tem/headerx',$this->dtroot);
	// 	$this->load->view('admin_tem/sidebarx',$this->dtroot);
	// 	$this->load->view('self_learning/data_file_bagian',$data);
	// 	$this->load->view('admin_tem/footerx');
	// }
	public function setting_materi_pelatihan(){
		$data=['access'=>$this->access];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('self_learning/setting_materi_pelatihan', $data);
		$this->load->view('admin_tem/footerx');
	}
	public function riwayat_pelatihan(){
		$data=['access'=>$this->access];
		$this->load->view('admin_tem/headerx',$this->dtroot);
		$this->load->view('admin_tem/sidebarx',$this->dtroot);
		$this->load->view('self_learning/history_materi_pelatihan', $data);
		$this->load->view('admin_tem/footerx');
	}
	//====================================================================================================================================//
}
