<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpayroll extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7)); 
		if ($this->session->has_userdata('adm')) {
			$this->session->unset_userdata('emp'); 
		}elseif ($this->session->has_userdata('emp')) {
			$this->session->unset_userdata('adm'); 
		}
        //check session login
        if ($this->session->has_userdata('adm')) {
            $this->admin = $this->session->userdata('adm')['id'];	
        }elseif ($this->session->has_userdata('emp')) {
            $this->admin = $this->session->userdata('emp')['id'];	
        }else{
            redirect('auth');
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
				'id_karyawan'=>$dtroot['admin']['id_karyawan'],
				'kelamin'=>$dtroot['admin']['kelamin'],
				'foto'=>$dtroot['admin']['foto'],
				'level'=>$dtroot['admin']['level'],
				'jenis_admin'=>$dtroot['admin']['admin'],
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
// ========================================================= DATA PENGGAJIAN =============================================================
	public function data_penggajian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$usage=$this->uri->segment(3);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_admin = $this->admin;
				$sistem_penggajian = $this->input->post('sistem_penggajian');
				$param = $this->input->post('param');
				$periode = $this->input->post('periode');
				$bagian = $this->input->post('bagian');
				$id = $this->input->post('id');
				$dataSearch=['param'=>'search','periode'=>$periode,'bagian'=>$bagian];
				if ($id){
					$id=$this->codegenerator->decryptChar($id);
					$where = ['a.id_karyawan'=>$id];
				}else{
					if($param == 'all'){
						$where = ['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>$sistem_penggajian];
					}else{
						$where=['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>$sistem_penggajian];
					}
					if (!is_null($level) && $level <= 2){
						$where=['a.kode_master_penggajian'=>$sistem_penggajian];
					}
				}
				if ($periode){
					$data = $this->model_payroll->getDataPayroll($where,0,$dataSearch, false);
				}else{
					$data=[];
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
					$data_1 = [
						$d->id_pay,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
					];
					$total = 0;
					$data_2=[];
					$valTunjangan = $this->otherfunctions->getDataExplode($d->tunjangan_val,';','all');
					$masterIndukTunjangan = $this->model_master->getIndukTunjanganWhere(null,'a.sifat','DESC');
					foreach ($masterIndukTunjangan as $key_it) {
						$dtun='';
						if(!empty($valTunjangan)){
							foreach ($valTunjangan as $key_tun => $val_tun) {
								$kode_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','start');
								$nominal_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','end');
								$induk=$this->model_master->getListTunjangan(['a.kode_tunjangan'=>$kode_tunjangan],1,true);
								$nominal_t = $this->formatter->getFormatMoneyUser($nominal_tunjangan);
								if($key_it->kode_induk_tunjangan == $induk['kode_induk_tunjangan']){
									$dtun.=$nominal_t;
								}
							}
						}
						$data_2[]=$dtun;
					}
					$p_tidak_masuk=$d->pot_tidak_masuk;
					$data_3 = [
						$this->formatter->getFormatMoneyUserReq($d->insentif),
						$this->formatter->getFormatMoneyUserReq($d->ritasi),
						$this->formatter->getFormatMoneyUserReq($d->uang_makan),
						$this->formatter->getFormatMoneyUserReq($p_tidak_masuk),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jht),
						// $this->formatter->getFormatMoneyUserReq($d->bpjs_jkk),
						// $this->formatter->getFormatMoneyUserReq($d->bpjs_jkm),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_pen),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_kes),
						$this->formatter->getFormatMoneyUserReq($d->angsuran),
						// $d->angsuran_ke,
						$this->formatter->getFormatMoneyUserReq($d->nominal_denda),
						$d->angsuran_ke_denda,
						$this->payroll->getDataLainView($d->data_lain),
						$this->payroll->getDataLainView($d->data_lain_nama),
						$this->payroll->getDataLainNominalView($d->nominal_lain),
						// $this->payroll->getDataLainView($d->keterangan_lain),
						$this->formatter->getFormatMoneyUserReq($d->gaji_bersih),
						$d->no_rek,
						// $this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
						$properties['tanggal'],
						$properties['aksi']
					];
					$datax['data'][] = array_merge($data_1, $data_2, $data_3);


					// $datax['data'][]=[
					// 	$d->id_pay,
					// 	$d->nik,
					// 	$d->nama_karyawan,
					// 	$d->nama_jabatan,
					// 	$d->nama_grade,
					// 	$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),'danger'),
					// 	$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
					// 	$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
					// 	$this->formatter->getFormatMoneyUserReq($d->insentif),
					// 	$this->formatter->getFormatMoneyUserReq($d->ritasi),
					// 	$this->formatter->getFormatMoneyUserReq($d->uang_makan),
					// 	$this->formatter->getFormatMoneyUserReq($d->pot_tidak_masuk),
					// 	$this->formatter->getFormatMoneyUserReq($d->bpjs_jht),
					// 	$this->formatter->getFormatMoneyUserReq($d->bpjs_jkk),
					// 	$this->formatter->getFormatMoneyUserReq($d->bpjs_jkm),
					// 	$this->formatter->getFormatMoneyUserReq($d->bpjs_pen),
					// 	$this->formatter->getFormatMoneyUserReq($d->bpjs_kes),
					// 	$this->formatter->getFormatMoneyUserReq($d->angsuran),
					// 	$d->angsuran_ke,
					// 	$this->formatter->getFormatMoneyUserReq($d->nominal_denda),
					// 	$d->angsuran_ke_denda,
					// 	$this->payroll->getDataLainView($d->data_lain),
					// 	$this->payroll->getDataLainView($d->data_lain_jenis),
					// 	$this->payroll->getDataLainNominalView($d->nominal_lain),
					// 	$this->payroll->getDataLainView($d->keterangan_lain),
					// 	$this->formatter->getFormatMoneyUserReq($d->gaji_bersih),
					// 	$d->no_rek,
					// 	// $this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
					// 	$properties['tanggal'],
					// 	$properties['aksi']
					// ];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pay');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataPayroll(['a.id_pay'=>$id]);
				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$penambah = $this->payroll->getTablePenambah($d->id_karyawan,$d->kode_periode,$id,'data','BULANAN',$d->tunjangan_val);
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
			}elseif ($usage == 'bagian') {
				$kode_periode = $this->input->post('kode_periode');
				$log = $this->input->post('log');
				$bagian = $this->model_payroll->getBagianFromPeriodeGajiBulanan($kode_periode,null,$log);
				$sel_bagian = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($bagian) as $bkey) {
					$sel_bagian .= '<option value="'.$bkey->kode_bagian.'">'.$bkey->nama_bagian.' - '.$bkey->nama_loker.'</option>';
				}
				$datax = ['bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'bagian_sync') {
				$kode_periode = $this->input->post('kode_periode');
				$bagian = $this->model_payroll->getBagianFromPeriodeGajiBulanan($kode_periode);
				$sel_bagian = '<option value="">Pilih Data</option>';
				foreach (array_filter($bagian) as $bkey) {
					$sel_bagian .= '<option value="'.$bkey->kode_bagian.'">'.$bkey->nama_bagian.' - '.$bkey->nama_loker.'</option>';
				}
				$datax = ['bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'karyawan') {
				$kode_periode = $this->input->post('kode_periode');
				$kode_bagian = $this->input->post('kode_bagian');
				$karyawan = $this->model_payroll->getBagianFromPeriodeGajiBulanan($kode_periode,$kode_bagian);
				$sel_karyawan = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($karyawan) as $bkey) {
					$sel_karyawan .= '<option value="'.$bkey->id_karyawan.'">'.$bkey->nama_karyawan.' - '.$bkey->nama_jabatan.'</option>';
				}
				$datax = ['karyawan'=>$sel_karyawan];
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function data_penggajian_user()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$usage=$this->uri->segment(3);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_admin = $this->admin;
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('tahun');
				if ($bulan && $tahun){
					$where = ['a.bulan'=>$bulan, 'a.tahun'=>$tahun, 'a.id_karyawan'=>$id_admin];
					$data = $this->model_payroll->getDataPayrollUser($where);
				}else{
					$data=[];
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
					$data_1 = [
						$d->id_pay,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
					];
					$total = 0;
					$data_2=[];
					$valTunjangan = $this->otherfunctions->getDataExplode($d->tunjangan_val,';','all');
					$masterIndukTunjangan = $this->model_master->getIndukTunjanganWhere(null,'a.sifat','DESC');
					foreach ($masterIndukTunjangan as $key_it) {
						$dtun='';
						if(!empty($valTunjangan)){
							foreach ($valTunjangan as $key_tun => $val_tun) {
								$kode_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','start');
								$nominal_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','end');
								$induk=$this->model_master->getListTunjangan(['a.kode_tunjangan'=>$kode_tunjangan],1,true);
								$nominal_t = $this->formatter->getFormatMoneyUser($nominal_tunjangan);
								if($key_it->kode_induk_tunjangan == $induk['kode_induk_tunjangan']){
									$dtun.=$nominal_t;
								}
							}
						}
						$data_2[]=$dtun;
					}
					$p_tidak_masuk=$d->pot_tidak_masuk;
					$data_3 = [
						$this->formatter->getFormatMoneyUserReq($d->insentif),
						$this->formatter->getFormatMoneyUserReq($d->ritasi),
						$this->formatter->getFormatMoneyUserReq($d->uang_makan),
						$this->formatter->getFormatMoneyUserReq($p_tidak_masuk),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jht),
						// $this->formatter->getFormatMoneyUserReq($d->bpjs_jkk),
						// $this->formatter->getFormatMoneyUserReq($d->bpjs_jkm),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_pen),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_kes),
						$this->formatter->getFormatMoneyUserReq($d->angsuran),
						// $d->angsuran_ke,
						$this->formatter->getFormatMoneyUserReq($d->nominal_denda),
						$d->angsuran_ke_denda,
						$this->payroll->getDataLainView($d->data_lain),
						$this->payroll->getDataLainView($d->data_lain_nama),
						$this->payroll->getDataLainNominalView($d->nominal_lain),
						// $this->payroll->getDataLainView($d->keterangan_lain),
						$this->formatter->getFormatMoneyUserReq($d->gaji_bersih),
						$d->no_rek,
						// $this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
						$properties['tanggal'],
						$properties['aksi']
					];
					$datax['data'][] = array_merge($data_1, $data_2, $data_3);
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pay');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataPayroll(['a.id_pay'=>$id]);
				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$penambah = $this->payroll->getTablePenambah($d->id_karyawan,$d->kode_periode,$id,'data','BULANAN',$d->tunjangan_val);
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
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function cek_data_payroll()
	{
		$kode_periode = $this->input->post('kode_periode');
		$cekKodePeriode=$this->model_payroll->cekKodePeriode(['kode_periode'=>$kode_periode]);
		if(empty($cekKodePeriode)){
			$datax = ['msg'=>'true'];
		}else{
			$datax=['msg'=>'ada_data','kode_periode'=>$kode_periode];
		}
		echo json_encode($datax);
	}
	public function ready_data_payroll()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$kode_periode  = $this->input->post('kode_periode');
		$data_ritasi   = $this->input->post('data_ritasi');
		$data_insentif = $this->input->post('data_insentif');
		$data_bpjs     = $this->input->post('data_bpjs');
		$data_pinjaman = $this->input->post('data_pinjaman');
		$data_denda    = $this->input->post('data_denda');
		$data_lain     = $this->input->post('data_lain');
		$metode_bpjs   = 'nominalx';
		if(!empty($kode_periode)){
			$id_admin = $this->admin;
			$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
			$karyawan = $this->payroll->getKaryawanFromPeriode($kode_periode, $this->dtroot);
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
					if(!empty($data_bpjs)){
						if($metode_bpjs == 'nominal'){
							$bpjs = $this->payroll->getBpjs($value['id_karyawan'],$kode_periode); 
							$bpjs_jht = (empty($bpjs['jht'])) ? 0 : $bpjs['jht'];
							$bpjs_jkk = (empty($bpjs['jkk'])) ? 0 : $bpjs['jkk'];
							$bpjs_jkm = (empty($bpjs['jkm'])) ? 0 : $bpjs['jkm'];
							$bpjs_jpns = (empty($bpjs['jpns'])) ? 0 :  $bpjs['jpns'];
							$bpjs_jkes = (empty($bpjs['jkes'])) ? 0 :  $bpjs['jkes'];
							$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
						}else{
							$bpjs_jht = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JHT');
							$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JKK-RS');
							$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JKM');
							$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JPNS', $value['id_karyawan']);
							$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_kes'], 'JKES');
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
					if(!empty($data_pinjaman)){
						// 	$angsuran = $this->payroll->getAngsuran($value['id_karyawan'],$kode_periode);
						// $angsuran = $this->payroll->getPinjamanNew($value['id_karyawan'],$periode['bulan'],$periode['tahun']);
						$angsuran = $this->payroll->getPinjamanPayroll($value['id_karyawan'],$periode['tgl_selesai']);
						$jum_angsuran = array_sum($angsuran['nominal']);
					}else{
						$angsuran = null;
						$jum_angsuran = 0;
					}
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
					$potTerlambat = $this->model_master->getGeneralSetting('POT_TERLAMBAT')['value_int'];
					if($potTerlambat == '1'){
						$potongan_terlambat = $ijin_nominal['terlambat'];
					}else{
						$potongan_terlambat = 0;
					}
					$nominal_ijin_perjam = $ijin_nominal['izin']+$ijin_nominal['iskd']+$ijin_nominal['imp']+$potongan_terlambat;
					/*=== pengurang ===*/	
					$pot_tidak_masuk = $dataPay['potAlpa'];
					$pot_tidak_masuk = (empty($pot_tidak_masuk) || !empty($ijin_cuti['val_cutiMelahirkan'])) ? 0 : $this->otherfunctions->nonPembulatan($pot_tidak_masuk);
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
						$dataLainHallo 		= null;
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
						'gaji_bpjs_kes'           =>$value['gaji_bpjs_kes'],
						'gaji_bpjs_tk'            =>$value['gaji_bpjs_tk'],
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
						'data_lain_hallo'         =>$dataLainHallo,
						'nominal_lain'            =>$dataLainNominal,
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
						'n_terlambat'			  =>$potongan_terlambat,
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
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertUpdateQueryNoMsg($data,'data_penggajian',['id_karyawan'=>$data['id_karyawan'],'kode_periode'=>$data['kode_periode']]);
					$datax = $this->messages->allGood();
				}
			}else{
				$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
			}
		}else{
			$datax = 'true kode periode kosong';
		}
		echo json_encode($datax);
	}
	public function del_ada_data()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$id_admin = $this->admin;
		$kode_periode = $this->input->post('kode_periode');
		$cekAngsuranIn = $this->model_payroll->getListAngsuran(['a.kode_periode'=>$kode_periode],1);
		if(!empty($cekAngsuranIn)){
			foreach($cekAngsuranIn as $dd){
				$data_pin = ['status_pinjaman'=>0];
				$data_pin=array_merge($data_pin,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($data_pin,'data_pinjaman',['kode_pinjaman'=>$dd->kode_pinjaman]);
			}
			$del=$this->model_global->deleteQuery('data_angsuran',['kode_periode'=>$kode_periode]);
		}
		$datax=$this->model_global->deleteQuery('data_penggajian',['kode_periode'=>$kode_periode]);
		// $datax=$this->model_global->deleteQuery('data_penggajian',['create_by'=>$id_admin,'kode_periode'=>$kode_periode]);
		echo json_encode($datax);
	}
	public function sync_data_penggajian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$kode_periode = $this->input->post('periode');
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
		$bagian = $this->input->post('bagian');
		$emp = $this->input->post('karyawan');
		$data_bpjs     = $this->input->post('data_bpjs');
		// $metode_bpjs   = $this->input->post('metode_bpjs');
		$metode_bpjs   = 'nominal';
		$data_ritasi   = 'data_ritasi';
		$data_insentif = 'data_insentif';
		$data_pinjaman = 'data_pinjaman';
		$data_denda    = 'data_denda';
		$data_lain     = 'data_lain';
		$karyawan = [];
		if(in_array('all',$emp)){
			$empl = $this->model_payroll->getBagianFromPeriodeGajiBulanan($kode_periode,$bagian);
			foreach ($empl as $kary) {
				$emp_single = $this->model_karyawan->getEmployeeId($kary->id_karyawan);
				$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
			}
		}else{
			foreach ($emp as $key => $value) {
				$emp_single = $this->model_karyawan->getEmployeeId($value);
				$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
			}
		}
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
				$nominal_penambah = $dataPay['gaji_bersih']+array_sum($tunjanganTetap)+array_sum($tunjanganTidakTetap)+$insentif+$ritasi+$uang_makan;
				/*=== pengurang ===*/	
				$pot_tidak_masuk = $dataPay['potAlpa'];
				$pot_tidak_masuk = (empty($pot_tidak_masuk) || !empty($ijin_cuti['val_cutiMelahirkan'])) ? 0 : $this->otherfunctions->nonPembulatan($pot_tidak_masuk);
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
				if(!empty($data_denda)){
					$denda = $this->payroll->getDenda($value['id_karyawan'],$kode_periode);
				}else{
					$denda = [
						'nominal'=>0,
						'angsuran_ke'=>0,
					];
				}
				// echo '<pre>';
				// print_r($dataPay);
				$ijin_cuti = $this->payroll->getIjinCutiSimple($dataPay['getIzinCuti'],$from,$to);
				$ijin_nominal = $this->payroll->getUpahIjinCuti($ijin_cuti,$value['gaji_pokok'],$value['tgl_masuk']);
				$potTerlambat = $this->model_master->getGeneralSetting('POT_TERLAMBAT')['value_int'];
				if($potTerlambat == '1'){
					$potongan_terlambat = $ijin_nominal['terlambat'];
				}else{
					$potongan_terlambat = 0;
				}
				$nominal_ijin_perjam = $ijin_nominal['izin']+$ijin_nominal['iskd']+$ijin_nominal['imp']+$potongan_terlambat;//+$ijin_nominal['terlambat'];
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
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'data_penggajian',['id_karyawan'=>$value['id_karyawan'],'kode_periode'=>$kode_periode]);
			}
		}else{
			$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
		}
		echo json_encode($datax);
	}
 	
// ========================================================= DATA PENGGAJIAN HARIAN ==========================================================

	public function data_penggajian_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_admin = $this->admin;
				$param = $this->input->post('param');
				parse_str($this->input->post('form'), $post_form);
				$minggu = null;
				if(isset($post_form['minggu']) || !empty($post_form['minggu'])){
					$minggu=$post_form['minggu'];
				}
				$bulan = null;
				if(isset($post_form['bulan']) || !empty($post_form['bulan'])){
					$bulan=$post_form['bulan'];
				}
				$tahun = null;
				if(isset($post_form['tahun']) || !empty($post_form['tahun'])){
					$tahun=$post_form['tahun'];
				}
				$minggux=(!empty($minggu)) ? ["a.minggu" => $minggu] : [];
				$bulanx=(!empty($bulan)) ? ["a.bulan" => $bulan] : [];
				$tahunx=(!empty($tahun)) ? ["a.tahun" => $tahun] : ["a.tahun" => date('Y')];	
				$lokasix=(!empty($post_form['lokasi'])) ? ["a.kode_loker" => $post_form['lokasi']] : [];
				$bagianx=(!empty($post_form['bagian'])) ? ["a.kode_bagian" => $post_form['bagian']] : [];
				$where = array_merge($lokasix,$bagianx,$minggux,$bulanx,$tahunx);
				if (isset($post_form['id'])){
					if ($post_form['id']){
						$where['a.id_karyawan']=$this->codegenerator->decryptChar($post_form['id']);
					}
				}
				if (!is_null($level) && $level > 2){
					$where['a.create_by'] = $id_admin;
				}
				$data = $this->model_payroll->getDataPayrollHarianNew($where);
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
						$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
						// $this->formatter->getFormatMoneyUserReq($d->upah_harian),
						$d->presensi,
						$this->payroll->getDataLainView($d->data_lain),
						$this->payroll->getDataLainNominalView($d->nominal_lain),
						$this->payroll->getDataLainView($d->keterangan_lain),
						$this->formatter->getFormatMoneyUserReq($d->gaji_diterima),
						$d->total_jam,
						$this->formatter->getFormatMoneyUserReq($d->gaji_lembur),
						$this->formatter->getFormatMoneyUserReq($d->gaji_bersih),
						$d->no_rek,
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pay');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataPayrollHarianNew(['a.id_pay'=>$id]);
				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					// $penambah = $this->payroll->getTablePenambahHarian($d->id_karyawan,$d->kode_periode,$id,'data','HARIAN');
					// $pengurang = $this->payroll->getTablePengurangHarian($id,'data','HARIAN');
					$lembur = $this->payroll->getTableLembur($id,'data','HARIAN');
					$data_bpjs = $this->payroll->getTableBPJSHarian($id);
					$data_lainnya = $this->payroll->getTableDataLainnya($id);
					$total_gaji = $this->payroll->getTableGajiBerih($d->gaji_bersih);
					$datax=[
						'id'=>$d->id_pay,
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
						// 'periode'=>$d->nama_periode. ' ( '.$d->nama_sistem_penggajian.' )',
						'minggu_view'=>$this->otherfunctions->getlistWeek($d->minggu),
						'bulan_view'=>$this->formatter->getNameOfMonth($d->bulan),
						'tahun'=>$d->tahun,
						'presensi'=>$d->presensi.' Hari Kerja',
						'gaji_diterima'=>$this->formatter->getFormatMoneyUser($d->gaji_diterima),
						'sistem'=>'HARIAN',
						// 'penambah'=>$penambah,
						// 'pengurang'=>$pengurang,
						'lembur'=>$lembur,
						'data_bpjs'=>$data_bpjs,
						'data_lainnya'=>$data_lainnya,
						'jam_biasa'=>$d->jam_biasa,
						'nominal_biasa'=>$this->formatter->getFormatMoneyUser($d->nominal_biasa),
						'jam_libur_pendek'=>$d->jam_libur_pendek,
						'nominal_libur_pendek'=>$this->formatter->getFormatMoneyUser($d->nominal_libur_pendek),
						'jam_libur'=>$d->jam_libur,
						'nominal_libur'=>$this->formatter->getFormatMoneyUser($d->nominal_libur),
						'total_jam'=>$d->total_jam,
						'gaji_lembur'=>$this->formatter->getFormatMoneyUser($d->gaji_lembur),
						'total_gaji'=>$total_gaji,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_pokok),
						'jht'=>$this->formatter->getFormatMoneyUser($d->jht),
						'jkk'=>$this->formatter->getFormatMoneyUser($d->jkk),
						'jpen'=>$this->formatter->getFormatMoneyUser($d->jpen),
						'jkm'=>$this->formatter->getFormatMoneyUser($d->jkm),
						'jkes'=>$this->formatter->getFormatMoneyUser($d->jkes),
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
			}elseif ($usage == 'bagian') {
				$kode_periode = $this->input->post('kode_periode');
				$bagian = $this->model_payroll->getBagianFromPeriodeGajiHarian($kode_periode);
				$sel_bagian = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($bagian) as $bkey) {
					$sel_bagian .= '<option value="'.$bkey->kode_bagian.'">'.$bkey->nama_bagian.' - '.$bkey->nama_loker.'</option>';
				}
				$datax = ['bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'bagian_sync') {
				$kode_periode = $this->input->post('kode_periode');
				$bagian = $this->model_payroll->getBagianFromPeriodeGajiHarian($kode_periode);
				$sel_bagian = '<option value="">Pilih Data</option>';
				foreach (array_filter($bagian) as $bkey) {
					$sel_bagian .= '<option value="'.$bkey->kode_bagian.'">'.$bkey->nama_bagian.' - '.$bkey->nama_loker.'</option>';
				}
				$datax = ['bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'karyawan') {
				$kode_periode = $this->input->post('kode_periode');
				$kode_bagian = $this->input->post('kode_bagian');
				$karyawan = $this->model_payroll->getBagianFromPeriodeGajiHarian($kode_periode,$kode_bagian);
				$sel_karyawan = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($karyawan) as $bkey) {
					$sel_karyawan .= '<option value="'.$bkey->id_karyawan.'">'.$bkey->nama_karyawan.' - '.$bkey->nama_jabatan.'</option>';
				}
				$datax = ['karyawan'=>$sel_karyawan];
        		echo json_encode($datax);
			}elseif ($usage == 'get_karyawan_new') {
				$bagian = $this->input->post('bagian');
				$lokasi = $this->input->post('lokasi');
				$minggu = $this->input->post('minggu');
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('tahun');
				$karyawan = $this->model_payroll->getKaryawanFromBagianLokerGajiHarian($bagian,$lokasi,$minggu,$bulan,$tahun);
				$sel_karyawan = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($karyawan) as $bkey) {
					$sel_karyawan .= '<option value="'.$bkey->id_karyawan.'">'.$bkey->nama_karyawan.' - '.$bkey->nama_jabatan.'</option>';
				}
				$datax = ['karyawan'=>$sel_karyawan];
        		echo json_encode($datax);
			}elseif ($usage == 'insert_data_lain') {
				$tabel_end_proses='<table class="table table-bordered table-striped data-table" id="myTable" style="width: 100%;">
								<thead>
									<tr class="bg-blue">
										<th>Jenis</th>
										<th>Nama</th>
										<th>Nominal</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>';
				$select='<select class="form-control select2" name="sifat[]" id="data_sifat_add" style="width: 100%;" required="required">
					<option></option>
					<option value="penambah">Penambah</option>
					<option value="pengurang">Pengurang</option>
				</select>';
				$nama='<input type="text" name="nama[]" class="form-control" placeholder="Nama" required="required" style="width: 100%;">';
				$nominal='<input type="text" name="nominal[]" class="input-money form-control" placeholder="Tetapkan Nominal" required="required" style="width: 100%;">
					<script>
						$(document).ready(function(){
							$(".input-money").keyup(function () {
								this.value = formatRupiah(this.value, "Rp. ");
							});
							$(".input-money").focus(function (data) {
								if (this.value == "Rp. 0") {
									this.value = "";
								}
							});
							$(".input-money").focusout(function (data) {
								if (this.value == "") {
									this.value = "Rp. 0";
								} else if (this.value == "0") {
									this.value = "Rp. 0";
								}
							});
						})
					</script>';
				$datax=[
					'select'=>$select,
					'nama'=>$nama,
					'nominal'=>$nominal,
					'tabel_end_proses'=>$tabel_end_proses,
				];
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function cek_data_payroll_harian()
	{
		$bagian = $this->input->post('bagian');
		$lokasi = $this->input->post('lokasi');
		$minggu = $this->input->post('minggu');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$where = [
			'kode_bagian'=>$bagian,
			'kode_loker'=>$lokasi,
			'minggu'=>$minggu,
			'bulan'=>$bulan,
			'tahun'=>$tahun,
		];
		$cekDataGaji=$this->model_payroll->cekPenggajianHarian($where);
		$cekDataLog=$this->model_payroll->cekLogPenggajianHarian($where);
		if(empty($cekDataGaji)){
			if(!empty($cekDataLog)){
				$data2=['msg'=>'ada_data_log'];
				$datax= array_merge($data2, $where);
			}else{
				$datax = ['msg'=>'true'];
			}
		}else{
			$data1=['msg'=>'ada_data'];
			$datax= array_merge($data1, $where);
		}
		echo json_encode($datax);
	}
	public function ready_data_payroll_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$bagian = $this->input->post('bagian');
		$lokasi = $this->input->post('lokasi');
		$tanggal = $this->input->post('tanggal');
		$minggu = $this->input->post('minggu');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$bpjs = $this->input->post('bpjs');
		// print_r($bpjs);
		$data_lain = $this->input->post('data_lain');
		$from = $this->formatter->getDateFromRange($tanggal,'start','no');
		$to = $this->formatter->getDateFromRange($tanggal,'end','no');
		if(!empty($bagian) && !empty($lokasi)){
			$param = [ 'bagian'=>$bagian, 'lokasi'=>$lokasi];
			$karyawan = $this->payroll->getKaryawanFromPayrollHarian($param, $this->dtroot);
			// print_r($param);
			// print_r($karyawan);
			if(!empty($karyawan)){
				foreach (array_values(array_filter($karyawan)) as $key => $value) {
					$gaji_harian = (empty($value['gaji_pokok'])) ? 0 : $value['gaji_pokok'];
					$tahunKar = $this->model_master->getGeneralSetting('TPB')['value_int'];
					$karNew   = $this->model_master->getGeneralSetting('PTMB')['value_int'];
					$karOld   = $this->model_master->getGeneralSetting('PTML')['value_int'];
					$tahun_masuk = $this->otherfunctions->getDataExplode($value['tgl_masuk'],'-','start');
					if($tahun_masuk >= $tahunKar){
						// $upah_harian = $gaji_harian/$karNew;
						$upah_bulanan = $gaji_harian*$karNew;
					}else{
						// $upah_harian = $gaji_harian/$karOld;
						$upah_bulanan = $gaji_harian*$karOld;
					}
					/*PRESENSI*/
					$dataEmp = [
						'id_karyawan'=>$value['id_karyawan'],
						'gaji_pokok'=>$value['gaji_pokok'],
						'tgl_masuk'=>$value['tgl_masuk'],
						'wfh'=>$value['wfh'],
						'hkwfh'=>$value['hari_kerja_wfh'],
						'hknwfh'=>$value['hari_kerja_non_wfh'],
					];
					$upah_harian = $gaji_harian;
					// $dataPay = $this->payroll->getPresensiIzinPayroll($dataEmp,$from,$to);
					$dataPresensi = $this->payroll->getPresensiHarian($value['id_karyawan'],$from,$to);
					// $presensi = $this->payroll->getPresensiDataHarian($value['id_karyawan'],$kode_periode);
					$besaranGaji = ($upah_harian*$dataPresensi);
					// echo '<pre>';
					if(!empty($bpjs)){
						$bpjs_jht = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JHT');
						$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JKK-RS');
						$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JKM');
						$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_tk'], 'JPNS', $value['id_karyawan']);
						$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($value['gaji_bpjs_kes'], 'JKES');
						$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
					}else{
						$bpjs_jht = 0;
						$bpjs_jkk = 0;
						$bpjs_jkm = 0;
						$bpjs_jpns = 0;
						$bpjs_jkes = 0;
						$pe_bpjs = ($bpjs_jht+$bpjs_jkk+$bpjs_jkm)+$bpjs_jpns+$bpjs_jkes;
					}
					/*Data Pendukung Lain*/
					if(!empty($data_lain)){
						$data_lain_val      = $this->payroll->getPendukungLainHarian($value['id_karyawan'],$minggu,$bulan,$tahun);
						$nominal_data_lain  = $this->payroll->getNominalPendukungLain($data_lain_val);
						$dataLainSifat      = implode(';', $nominal_data_lain['sifat']);
						$dataLainNominal    = implode(';', $nominal_data_lain['nominal']);
						$dataLainKeterangan = implode(';', $nominal_data_lain['keterangan']);
					}else{
						$nominal_data_lain = [
							'penambah'=>0,
							'pengurang'=>0,
							'sifat'=>null,
							'nominal'=>null,
						];
						$dataLainSifat      = null;
						$dataLainNominal    = null;
						$dataLainKeterangan = null;
					}
					/*=== DATA LEMBUR ===*/
					$data_lembur = $this->payroll->getLemburEmp($value['id_karyawan'],null,$from,$to);
					$lembur = $this->payroll->getNominalLembur($data_lembur,$upah_bulanan,$value['id_karyawan']);
					$req_lembur = $this->payroll->getReqLembur($lembur,$value['id_karyawan']);
					
					$nominal_biasa = $req_lembur['nominal_biasa'];
					$nominal_libur = $req_lembur['nominal_libur'];
					$nominal_libur_pendek = $req_lembur['nominal_libur_pendek'];
					$gaji_lembur = $nominal_biasa+$nominal_libur+$nominal_libur_pendek;

					$gaji_diterima = ($besaranGaji+$nominal_data_lain['penambah'])-$nominal_data_lain['pengurang']-$pe_bpjs;
					$gaji_bersih = ($besaranGaji+$gaji_lembur)+$nominal_data_lain['penambah']-$nominal_data_lain['pengurang']-$pe_bpjs;
					$data = [
						'tgl_mulai'             =>$from,
						'tgl_selesai'           =>$to,
						'minggu'                =>$minggu,
						'bulan'                 =>$bulan,
						'tahun'                 =>$tahun,
						'kode_master_penggajian'=>'HARIAN',
						'id_karyawan'           =>$value['id_karyawan'],
						'nik'                   =>$value['nik'],
						'nama_karyawan'         =>$value['nama'],
						'kode_jabatan'          =>$value['kode_jabatan'],
						'kode_grade'            =>$value['kode_grade'],
						'kode_bagian'           =>$value['kode_bagian'],
						'kode_loker'            =>$value['kode_loker'],
						'tgl_masuk'             =>$value['tgl_masuk'],
						'masa_kerja'            =>$value['masa_kerja'],
						'gaji_pokok'            =>$value['gaji_pokok'],
						'status_pajak'          =>$value['status_pajak'],
						'no_ktp'                =>$value['no_ktp'],
						'npwp'                  =>$value['npwp'],
						'jht'					=>$bpjs_jht,
						'jkk'					=>$bpjs_jkk,
						'jkm'					=>$bpjs_jkm,
						'jpen'					=>$bpjs_jpns,
						'jkes'					=>$bpjs_jkes,
						'gaji_saja'             =>$besaranGaji,
						'presensi'              =>$dataPresensi,
						'jam_biasa'             =>$req_lembur['jam_biasa'],
						'nominal_biasa'         =>$nominal_biasa,
						'jam_libur_pendek'      =>$req_lembur['jam_libur_pendek'],
						'nominal_libur_pendek'  =>$nominal_libur_pendek,
						'jam_libur'             =>$req_lembur['jam_libur'],
						'ekuivalen'             =>$req_lembur['ekuivalen'],
						'nominal_libur'         =>$nominal_libur,
						'data_lain'             =>$dataLainSifat,
						'nominal_lain'          =>$dataLainNominal,
						'keterangan_lain'       =>$dataLainKeterangan,
						'gaji_lembur'           =>$gaji_lembur,
						'gaji_diterima'         =>$gaji_diterima,
						'gaji_bersih'           =>($gaji_bersih > 0)?$gaji_bersih:0,
						'no_rek'                =>$value['rekening'],
						'total_jam'             =>($req_lembur['jam_biasa']+$req_lembur['jam_libur']+$req_lembur['jam_libur_pendek']),
					];
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQuery($data,'data_penggajian_harian');
				}
			}else{
				$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
			}
		}else{
			$datax = 'true';
		}
		echo json_encode($datax);
	}
	public function del_ada_data_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$id_admin = $this->admin;
		$bagian = $this->input->post('bagian');
		$lokasi = $this->input->post('lokasi');
		$minggu = $this->input->post('minggu');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		// $datax=$this->model_global->deleteQuery('data_penggajian_harian',['create_by'=>$id_admin,'kode_bagian'=>$bagian,'kode_loker'=>$lokasi,'minggu'=>$minggu,'bulan'=>$bulan,'tahun'=>$tahun]);
		$datax=$this->model_global->deleteQuery('data_penggajian_harian',['kode_bagian'=>$bagian,'kode_loker'=>$lokasi,'minggu'=>$minggu,'bulan'=>$bulan,'tahun'=>$tahun]);
		echo json_encode($datax);
	}
	public function edit_penyesuaian_lembur_penggajian_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$id = $this->input->post('id');
		$jam_lembur = $this->input->post('jam_lembur');
		$jam_lembur_libur = $this->input->post('jam_lembur_libur');
		$jam_lembur_istirahat = $this->input->post('jam_lembur_istirahat');
		$nominalx = $this->formatter->getFormatMoneyDb($this->input->post('nominal'));
		$nominal_libur = $this->formatter->getFormatMoneyDb($this->input->post('nominal_libur'));
		$nominal_istirahat = $this->formatter->getFormatMoneyDb($this->input->post('nominal_istirahat'));
		$jht = $this->formatter->getFormatMoneyDb($this->input->post('jht'));
		$jkk = $this->formatter->getFormatMoneyDb($this->input->post('jkk'));
		$jpen = $this->formatter->getFormatMoneyDb($this->input->post('jpen'));
		$jkm = $this->formatter->getFormatMoneyDb($this->input->post('jkm'));
		$jkes = $this->formatter->getFormatMoneyDb($this->input->post('jkes'));
		$cekDataGaji=$this->model_payroll->cekPenggajianHarianRow(['id_pay'=>$id]);
		$nominalPenambah = 0;
		$nominalPengurang = 0;
		$nominal=(!empty($nominalx)?$nominalx:0);
		if(!empty($cekDataGaji['data_lain'])){
			$data_lain=$this->otherfunctions->getDataExplode($cekDataGaji['data_lain'],';','all');
			$nominalv=$this->otherfunctions->getDataExplode($cekDataGaji['nominal_lain'],';','all');
			$nama=$this->otherfunctions->getDataExplode($cekDataGaji['keterangan_lain'],';','all');
			foreach($data_lain as $ss =>$val){
				if($val == 'penambah'){
					$nominalPenambah +=$nominalv[$ss];
				}else{
					$nominalPengurang +=$nominalv[$ss];
				}
			}
		}
		$total_jam = ($jam_lembur+$jam_lembur_istirahat+$jam_lembur_libur);
		$gaji_lembur = ($nominal+$nominal_libur+$nominal_istirahat);
		$bpjs = $jht+$jkk+$jpen+$jkm+$jkes;
		$gaji_bersih = ($cekDataGaji['gaji_saja']+$nominalPenambah-$nominalPengurang)+$gaji_lembur-$bpjs;
		if(!empty($cekDataGaji)){
			$data = [
				'jam_biasa'=>$jam_lembur,
				'nominal_biasa'=>$nominal,
				'jam_libur_pendek'=>$jam_lembur_istirahat,
				'nominal_libur_pendek'=>$nominal_istirahat,
				'jam_libur'=>$jam_lembur_libur,
				'nominal_libur'=>$nominal_libur,
				'jht'=>$jht,
				'jkk'=>$jkk,
				'jpen'=>$jpen,
				'jkm'=>$jkm,
				'jkes'=>$jkes,
				'total_jam'=>$total_jam,
				'gaji_lembur'=>$gaji_lembur,
				'gaji_bersih'=>$gaji_bersih,
			];
			$data=array_merge($data, $this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_penggajian_harian',['id_pay'=>$id]);
		}
		echo json_encode($datax);
	}
	public function sync_data_penggajian_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$bagian = $this->input->post('bagian');
		$lokasi = $this->input->post('lokasi');
		$minggu = $this->input->post('minggu');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$emp = $this->input->post('karyawan');
		$where = [
			'kode_bagian'=>$bagian,
			'kode_loker'=>$lokasi,
			'minggu'=>$minggu,
			'bulan'=>$bulan,
			'tahun'=>$tahun,
		];
		$dataGajiHarian=$this->model_payroll->cekPenggajianHarianRow($where);
		$data_lain     = $this->input->post('data_lain');
		$karyawan = [];
		if(in_array('all',$emp)){
			$empl = $this->model_payroll->getKaryawanFromBagianLokerGajiHarian($bagian,$lokasi,$minggu,$bulan,$tahun);
			foreach ($empl as $kary) {
				$emp_single = $this->model_karyawan->getEmployeeId($kary->id_karyawan);
				$karyawan[] = $this->payroll->cekAgendaPayroll($emp_single);
			}
		}else{
			foreach ($emp as $key => $value) {
				$emp_single = $this->model_karyawan->getEmployeeId($value);
				$karyawan[] = $this->payroll->cekAgendaPayroll($emp_single);
			}
		}
		if(!empty($karyawan)){
			foreach (array_values(array_filter($karyawan)) as $key => $value) {
				$gaji_harian = (empty($value['gaji_pokok'])) ? 0 : $value['gaji_pokok'];
				$tahunKar = $this->model_master->getGeneralSetting('TPB')['value_int'];
				$karNew   = $this->model_master->getGeneralSetting('PTMB')['value_int'];
				$karOld   = $this->model_master->getGeneralSetting('PTML')['value_int'];
				$tahun_masuk = $this->otherfunctions->getDataExplode($value['tgl_masuk'],'-','start');
				if($tahun_masuk >= $tahunKar){
					// $upah_harian = $gaji_harian/$karNew;
					$upah_bulanan = $gaji_harian*$karNew;
				}else{
					// $upah_harian = $gaji_harian/$karOld;
					$upah_bulanan = $gaji_harian*$karOld;
				}
				$upah_harian = $gaji_harian;
				$dataEmp = [
					'id_karyawan'=>$value['id_karyawan'],
					'gaji_pokok'=>$value['gaji_pokok'],
					'tgl_masuk'=>$value['tgl_masuk'],
					'wfh'=>$value['wfh'],
					'hkwfh'=>$value['hari_kerja_wfh'],
					'hknwfh'=>$value['hari_kerja_non_wfh'],
				];
				$dataPay = $this->payroll->getPresensiIzinPayroll($dataEmp,$dataGajiHarian['tgl_mulai'],$dataGajiHarian['tgl_selesai']);
				$besaranGaji = ($upah_harian*$dataPay['countPresensi']);
				if(!empty($data_lain)){
					$data_lain_val      = $this->payroll->getPendukungLainHarian($value['id_karyawan'],$minggu,$bulan,$tahun);
					$nominal_data_lain  = $this->payroll->getNominalPendukungLain($data_lain_val);
					$dataLainSifat      = implode(';', $nominal_data_lain['sifat']);
					$dataLainNominal    = implode(';', $nominal_data_lain['nominal']);
					$dataLainKeterangan = implode(';', $nominal_data_lain['keterangan']);
				}else{
					$nominal_data_lain = [
						'penambah'=>0,
						'pengurang'=>0,
						'sifat'=>null,
						'nominal'=>null,
					];
					$dataLainSifat      = null;
					$dataLainNominal    = null;
					$dataLainKeterangan = null;
				}
				$data_lembur = $this->payroll->getLemburEmp($value['id_karyawan'],null,$dataGajiHarian['tgl_mulai'],$dataGajiHarian['tgl_selesai']);
				$lembur = $this->payroll->getNominalLembur($data_lembur,$upah_bulanan,$value['id_karyawan']);
				$req_lembur = $this->payroll->getReqLembur($lembur,$value['id_karyawan']);
				$nominal_biasa = $req_lembur['nominal_biasa'];
				$nominal_libur = $req_lembur['nominal_libur'];
				$nominal_libur_pendek = $req_lembur['nominal_libur_pendek'];
				$gaji_lembur = $nominal_biasa+$nominal_libur+$nominal_libur_pendek;
				$gaji_bersih = ($besaranGaji+$gaji_lembur)+$nominal_data_lain['penambah']-$nominal_data_lain['pengurang'];
				$gaji_diterima = ($besaranGaji+$nominal_data_lain['penambah'])-$nominal_data_lain['pengurang'];
				$data = [
					'tgl_mulai'             =>$dataGajiHarian['tgl_mulai'],
					'tgl_selesai'           =>$dataGajiHarian['tgl_selesai'],
					'bulan'                 =>$dataGajiHarian['bulan'],
					'tahun'                 =>$dataGajiHarian['tahun'],
					'kode_master_penggajian'=>$dataGajiHarian['kode_master_penggajian'],
					'id_karyawan'           =>$value['id_karyawan'],
					'nik'                   =>$value['nik'],
					'nama_karyawan'         =>$value['nama'],
					'kode_jabatan'          =>$value['kode_jabatan'],
					'kode_grade'            =>$value['kode_grade'],
					'kode_bagian'           =>$value['kode_bagian'],
					'kode_loker'            =>$value['kode_loker'],
					'tgl_masuk'             =>$value['tgl_masuk'],
					'masa_kerja'            =>$value['masa_kerja'],
					'gaji_pokok'            =>$value['gaji_pokok'],
					// 'upah_harian'           =>$upah_harian,
					'presensi'              =>$dataPay['countPresensi'],
					'jam_biasa'             =>$req_lembur['jam_biasa'],
					'nominal_biasa'         =>$nominal_biasa,
					'jam_libur_pendek'      =>$req_lembur['jam_libur_pendek'],
					'nominal_libur_pendek'  =>$nominal_libur_pendek,
					'jam_libur'             =>$req_lembur['jam_libur'],
					'ekuivalen'             =>$req_lembur['ekuivalen'],
					'nominal_libur'         =>$nominal_libur,
					'data_lain'             =>$dataLainSifat,
					'nominal_lain'          =>$dataLainNominal,
					'keterangan_lain'       =>$dataLainKeterangan,
					'gaji_lembur'           =>$gaji_lembur,
					'gaji_bersih'           =>$gaji_bersih,
					'gaji_diterima'         =>$gaji_diterima,
					'no_rek'                =>$value['rekening'],
					'total_jam'             =>($req_lembur['jam_biasa']+$req_lembur['jam_libur']+$req_lembur['jam_libur_pendek']),
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'data_penggajian_harian',['id_karyawan'=>$value['id_karyawan'],'minggu'=>$minggu,'bulan'=>$bulan,'tahun'=>$tahun]);
			}
		}else{
			$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
		}
		echo json_encode($datax);
	}
	public function insert_data_pendukung_lain_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		// echo '<pre>';
		$bagian = $this->input->post('bagian');
		$lokasi = $this->input->post('lokasi');
		$minggu = $this->input->post('minggu');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$emp = $this->input->post('karyawan');
		$sifat = $this->input->post('sifat');
		$nama = $this->input->post('nama');
		$nominal = $this->input->post('nominal');
		if(in_array('all',$emp)){
			$dataGaji = $this->model_payroll->getKaryawanFromBagianLokerGajiHarian($bagian,$lokasi,$minggu,$bulan,$tahun);
		}else{
			$wherex = '';
			if(!empty($emp)){
				$c_lvx=1;
				foreach ($emp as $key => $value) {
					$wherex.="a.id_karyawan='".$value."' AND a.kode_bagian='".$bagian."' AND a.kode_loker ='".$lokasi."' AND a.minggu='".$minggu."' AND a.bulan='".$bulan."' AND a.tahun = '".$tahun."'";
					if (count($emp) > $c_lvx) {
						$wherex.=' OR ';
					}
					$c_lvx++;
				}
			}
			$dataGaji = $this->model_payroll->getDataPayrollHarianNew($wherex);
		}
		if(!empty($dataGaji)){
			foreach($dataGaji as $dg =>$d){
				$lain = [];
				$namaLain = [];
				$nominalLain = [];
				$nominalPenambah = 0;
				$nominalPengurang = 0;
				if(!empty($sifat)){
					foreach($sifat as $ss =>$val){
						if($val == 'penambah'){
							$nominalPenambah +=$nominal[$ss];
						}else{
							$nominalPengurang +=$nominal[$ss];
						}
						$lain[] = $val;
						$namaLain[] = $nama[$ss];
						$nominalLain[] = $nominal[$ss];
					}
				}
				$bpjs = $d->jht+$d->jkk+$d->jpen+$d->jkm+$d->jkes;
				$gaji_diterima = ($d->gaji_saja+$nominalPenambah)-$nominalPengurang-$bpjs;
				$gaji_lembur = $d->nominal_biasa+$d->nominal_libur+$d->nominal_libur_pendek;
				$gaji_bersih = ($gaji_diterima+$gaji_lembur);
				$data = [
					'gaji_diterima'=>$gaji_diterima,
					'gaji_bersih'=>$gaji_bersih,
					'data_lain'=>implode(";", $lain),
					'nominal_lain'=>implode(";", $nominalLain),
					'keterangan_lain'=>implode(";", $namaLain),
				];
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'data_penggajian_harian',['id_pay'=>$d->id_pay]);
			}
		}
		echo json_encode($datax);
	}
	public function send_to_log_harian()
	{
		$bagian = $this->input->post('bagian');
		$lokasi = $this->input->post('lokasi');
		$minggu = $this->input->post('minggu');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		if($lokasi == null){
			$datax=$this->messages->customFailure('Harap Pilih Lokasi !');
		}elseif($bagian ==  null){
			$datax=$this->messages->customFailure('Harap Pilih Bagian !');
		}elseif($minggu ==  null){
			$datax=$this->messages->customFailure('Harap Pilih Minggu !');
		}elseif($bulan ==  null){
			$datax=$this->messages->customFailure('Harap Pilih Bulan !');
		}elseif($tahun ==  null){
			$datax=$this->messages->customFailure('Harap Pilih Tahun !');
		}else{
			$cekDataGaji=$this->model_payroll->cekPenggajianHarian(['kode_loker'=>$lokasi,'kode_bagian'=>$bagian,'minggu'=>$minggu,'bulan'=>$bulan,'tahun'=>$tahun]);
			if(empty($cekDataGaji)){
				$datax=$this->messages->customFailure('Data Tidak Ditemukan !');
			}else{
				foreach ($cekDataGaji as $d) {
					$cekDataGagix=$this->model_payroll->cekPenggajianHarian(['id_karyawan'=>$d->id_karyawan, 'kode_loker'=>$lokasi,'kode_bagian'=>$bagian,'minggu'=>$minggu,'bulan'=>$bulan,'tahun'=>$tahun]);
					$new_data = $this->otherfunctions->convertResultToRowArray($cekDataGagix);
					unset($new_data['id_pay']);
					$new_data=array_merge($new_data,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->insertQuery($new_data,'log_data_penggajian_harian');
				}
				$this->model_global->deleteQuery('data_penggajian_harian',['kode_loker'=>$lokasi,'kode_bagian'=>$bagian,'minggu'=>$minggu,'bulan'=>$bulan,'tahun'=>$tahun]);
				$datax = $this->messages->allGood();
			}
		}
		echo json_encode($datax);
	}
	public function send_to_log_harian_old()
	{
		$usage = $this->input->post('usage');
		$kode_periode = $this->input->post('kode_periode');
		if($usage == 'pindah'){
			$id_admin = $this->admin;
			$total = 0;
			$data = $this->model_payroll->getDataLogPayrollSingle(['create_by'=>$id_admin,'kode_master_penggajian'=>'HARIAN','kode_periode'=>$kode_periode]);
			// $kode_periode = $this->otherfunctions->convertResultToRowArray($data)['kode_periode'];
			foreach ($data as $d) {
				$new_data = $this->model_payroll->getDataLogPayrollSingle(['id_pay'=>$d->id_pay,'create_by'=>$id_admin]);
				$new_data = $this->otherfunctions->convertResultToRowArray($new_data);
				unset($new_data['id_pay']);
				$new_data=array_merge($new_data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->insertQuery($new_data,'log_data_penggajian_harian');
				$total += $d->gaji_bersih;
			}
			// $kode_periode = $this->otherfunctions->convertResultToRowArray($data)['kode_periode'];
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

// ========================================================= UPDATE PPH 21 ==========================================================

	// public function update_pph21($kode_periode,$data_bpjs,$metode_bpjs,$emp_loker,$karyawan)
		// {
		// 	if(empty($kode_periode)){
		// 		return 'false';
		// 	}else{
		// 		$cek_periode = $this->model_payroll->getListDataPenggajianPph(['a.kode_periode'=>$kode_periode]);
		// 		if(!empty($cek_periode)){
		// 			$this->model_global->deleteQuery('data_penggajian_pph',['kode_periode'=>$kode_periode]);
		// 		}
		// 		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
		// 		// $periode_detail = $this->model_master->getListPeriodePenggajianDetail($kode_periode);
		// 		// $karyawan = [];
		// 		// foreach ($emp_loker as $key => $value) {
		// 		// 	$emp_single = $this->model_karyawan->getEmployeeId($value);
		// 		// 	$karyawan[] = $this->payroll->cekAgendaPenggajian($emp_single,$kode_periode);
		// 		// }
		// 		// if(!empty($karyawan)){
		// 			foreach (array_values(array_filter($karyawan)) as $key => $value) {
		// 				// $value['id_karyawan']=$karyawan;
		// 				$data_emp = $this->model_karyawan->getEmployeeId($value['id_karyawan']);
		// 				$gaji_pokok = $value['gaji_pokok'];
		// 				$gaji_pokok = (empty($gaji_pokok)) ? 0 : $gaji_pokok;
		// 				/*tunjangan*/
		// 				$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);
		// 				/*nominal tunjangan total*/																
		// 				$tunjangan_nominal = $this->payroll->getTunjanganNominalPayroll($tunjangan);
		// 				$tunjangan_nominal = (empty($tunjangan_nominal)) ? 0 : $tunjangan_nominal;
		// 				$upah = $gaji_pokok+$tunjangan_nominal;
		// 				$bpjs_p_jht = $this->payroll->getBpjsBayarPerusahaan($upah, 'JHT');
		// 				$bpjs_p_jkk = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKK-RS');
		// 				$bpjs_p_jkm = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKM');
		// 				$bpjs_p_jpns = $this->payroll->getBpjsBayarPerusahaan($upah, 'JPNS');
		// 				$bpjs_p_jkes = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKES');
		// 				/*Bruto*/
		// 				$bruto_bulan = $upah+$bpjs_p_jkk+$bpjs_p_jkm+$bpjs_p_jkes;
		// 				$bruto_tahun = $bruto_bulan*12;
		// 				/*biaya jabatan -  is array*/ 
		// 				$biaya_jabatan = $this->payroll->getBiayaJabatan($bruto_bulan);

		// 				if(!empty($data_bpjs)){
		// 					if($metode_bpjs == 'nominal'){
		// 						$bpjs = $this->payroll->getBpjs($value['id_karyawan'],$kode_periode); 

		// 						$bpjs_jht = (empty($bpjs['jht'])) ? 0 : $bpjs['jht'];
		// 						$bpjs_jkk = (empty($bpjs['jkk'])) ? 0 : $bpjs['jkk'];
		// 						$bpjs_jkm = (empty($bpjs['jkm'])) ? 0 : $bpjs['jkm'];
		// 						$bpjs_jpns = (empty($bpjs['jpns'])) ? 0 :  $bpjs['jpns'];
		// 						$bpjs_jkes = (empty($bpjs['jkes'])) ? 0 :  $bpjs['jkes'];
		// 					}else{
		// 						$bpjs_jht = $this->payroll->getBpjsBayarSendiri($upah, 'JHT');
		// 						$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($upah, 'JKK-RS');
		// 						$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($upah, 'JKM');
		// 						$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($upah, 'JPNS');
		// 						$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($upah, 'JKES');
		// 					}
		// 				}else{
		// 					$bpjs_jht = 0;
		// 					$bpjs_jkk = 0;
		// 					$bpjs_jkm = 0;
		// 					$bpjs_jpns = 0;
		// 					$bpjs_jkes = 0;
		// 				}
							
		// 				/*Iuran Pensiun*/
		// 				$iuran_pensiun_perusahaan = $bpjs_p_jpns;
		// 				$iuran_pensiun_pekerja = $bpjs_jpns;
		// 				/*Jumlah Pengurang*/
		// 				$jumlah_pengurang = $biaya_jabatan['biaya_hasil']+$bpjs_jht+$iuran_pensiun_pekerja;
		// 				/*Netto*/
		// 				$netto_bulan = $bruto_bulan-$jumlah_pengurang;
		// 				$netto_tahun = $netto_bulan*12;
		// 				/*tarif pajak*/  

		// 				$tarif_pajak = $this->payroll->getPajakPertahun($netto_tahun,$data_emp['status_pajak']);
		// 				$layer_pph = $this->payroll->getLayerPPH($tarif_pajak);
		// 				$get_pph = $this->payroll->getPPHPertahun($layer_pph,$data_emp['npwp']);
		// 				$nominal_perdin = $this->payroll->getPerjalananDinas($value['id_karyawan'],['mulai'=>$periode['tgl_mulai'],'selesai'=>$periode['tgl_selesai']]);

		// 				$data = [
		// 					'kode_periode'            =>$kode_periode,
		// 					'nama_periode'            =>$periode['nama'],
		// 					'tgl_mulai'               =>$periode['tgl_mulai'],
		// 					'tgl_selesai'             =>$periode['tgl_selesai'],
		// 					'kode_master_penggajian'  =>$periode['kode_master_penggajian'],
		// 					'nik'                     =>$data_emp['nik'],
		// 					'nama_karyawan'           =>$data_emp['nama'],
		// 					'kode_jabatan'            =>$data_emp['jabatan'],
		// 					'kode_bagian'             =>$data_emp['kode_bagian'],
		// 					'kode_loker'              =>$data_emp['loker'],
		// 					'kode_grade'              =>$data_emp['grade'],
		// 					'tgl_masuk'               =>$data_emp['tgl_masuk'],
		// 					'masa_kerja'              =>$value['masa_kerja'],
		// 					'status_ptkp'             =>$data_emp['status_pajak'],
		// 					'gaji_pokok'              =>$value['gaji_pokok'],
		// 					'tunjangan'               =>$tunjangan_nominal,
		// 					'bpjs_jkk_perusahaan'     =>$bpjs_p_jkk,
		// 					'bpjs_jkm_perusahaan'     =>$bpjs_p_jkm,
		// 					'bpjs_kes_perusahaan'     =>$bpjs_p_jkes,
		// 					'bruto_sebulan'           =>$bruto_bulan,
		// 					'bruto_setahun'           =>$bruto_tahun,
		// 					'biaya_jabatan'           =>$biaya_jabatan['biaya_hasil'],
		// 					'bpjs_jht_perusahaan'     =>$bpjs_p_jht,
		// 					'bpjs_jht_pekerja'        =>$bpjs_jht,
		// 					'iuran_pensiun_perusahaan'=>$iuran_pensiun_perusahaan,
		// 					'iuran_pensiun_pekerja'   =>$iuran_pensiun_pekerja,
		// 					'jml_pengurang'           =>$jumlah_pengurang,
		// 					'netto_sebulan'           =>$netto_bulan,
		// 					'netto_setahun'           =>$netto_tahun,
		// 					'pajak_setahun'           =>$tarif_pajak,
		// 					'pph_setahun'             =>$get_pph['pph_tahun'],
		// 					'no_npwp'                 =>$data_emp['npwp'],
		// 					'pph_sebulan'             =>$get_pph['plus_npwp'],
		// 					'perjalanan_dinas'        =>$nominal_perdin,
		// 				];
		// 				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
		// 				$this->model_global->insertQuery($data,'data_penggajian_pph');
		// 			}
		// 		// }else{
		// 		// 	$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
		// 		// }
		// 	}
		// 	return 'true';
		// }
		// public function update_pph21_sync($kode_periode,$data_bpjs,$metode_bpjs,$karyawan)
		// {
		// 	if(empty($kode_periode)){
		// 		return 'false';
		// 	}else{
		// 		$cek_periode = $this->model_payroll->getListDataPenggajianPph(['a.kode_periode'=>$kode_periode]);
		// 		if(!empty($cek_periode)){
		// 			$this->model_global->deleteQuery('data_penggajian_pph',['kode_periode'=>$kode_periode]);
		// 		}
		// 		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode_periode]));
		// 		if(!empty($karyawan)){
		// 			foreach (array_values(array_filter($karyawan)) as $key => $value) {
		// 				$data_emp = $this->model_karyawan->getEmployeeId($value['id_karyawan']);
		// 				$gaji_pokok = $value['gaji_pokok'];
		// 				$gaji_pokok = (empty($gaji_pokok)) ? 0 : $gaji_pokok;
		// 				$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);					
		// 				$tunjangan_nominal = $this->payroll->getTunjanganNominalPayroll($tunjangan);
		// 				$tunjangan_nominal = (empty($tunjangan_nominal)) ? 0 : $tunjangan_nominal;
		// 				$upah = $gaji_pokok+$tunjangan_nominal;
		// 				$bpjs_p_jht = $this->payroll->getBpjsBayarPerusahaan($upah, 'JHT');
		// 				$bpjs_p_jkk = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKK-RS');
		// 				$bpjs_p_jkm = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKM');
		// 				$bpjs_p_jpns = $this->payroll->getBpjsBayarPerusahaan($upah, 'JPNS');
		// 				$bpjs_p_jkes = $this->payroll->getBpjsBayarPerusahaan($upah, 'JKES');
		// 				$bruto_bulan = $upah+$bpjs_p_jkk+$bpjs_p_jkm+$bpjs_p_jkes;
		// 				$bruto_tahun = $bruto_bulan*12;
		// 				$biaya_jabatan = $this->payroll->getBiayaJabatan($bruto_bulan);
		// 				if(!empty($data_bpjs)){
		// 					if($metode_bpjs == 'nominal'){
		// 						$bpjs = $this->payroll->getBpjs($value['id_karyawan'],$kode_periode); 
		// 						$bpjs_jht = (empty($bpjs['jht'])) ? 0 : $bpjs['jht'];
		// 						$bpjs_jkk = (empty($bpjs['jkk'])) ? 0 : $bpjs['jkk'];
		// 						$bpjs_jkm = (empty($bpjs['jkm'])) ? 0 : $bpjs['jkm'];
		// 						$bpjs_jpns = (empty($bpjs['jpns'])) ? 0 :  $bpjs['jpns'];
		// 						$bpjs_jkes = (empty($bpjs['jkes'])) ? 0 :  $bpjs['jkes'];
		// 					}else{
		// 						$bpjs_jht = $this->payroll->getBpjsBayarSendiri($upah, 'JHT');
		// 						$bpjs_jkk = $this->payroll->getBpjsBayarSendiri($upah, 'JKK-RS');
		// 						$bpjs_jkm = $this->payroll->getBpjsBayarSendiri($upah, 'JKM');
		// 						$bpjs_jpns = $this->payroll->getBpjsBayarSendiri($upah, 'JPNS');
		// 						$bpjs_jkes = $this->payroll->getBpjsBayarSendiri($upah, 'JKES');
		// 					}
		// 				}else{
		// 					$bpjs_jht = 0;
		// 					$bpjs_jkk = 0;
		// 					$bpjs_jkm = 0;
		// 					$bpjs_jpns = 0;
		// 					$bpjs_jkes = 0;
		// 				}
		// 				$iuran_pensiun_perusahaan = $bpjs_p_jpns;
		// 				$iuran_pensiun_pekerja = $bpjs_jpns;
		// 				$jumlah_pengurang = $biaya_jabatan['biaya_hasil']+$bpjs_jht+$iuran_pensiun_pekerja;
		// 				$netto_bulan = $bruto_bulan-$jumlah_pengurang;
		// 				$netto_tahun = $netto_bulan*12;
		// 				$tarif_pajak = $this->payroll->getPajakPertahun($netto_tahun,$data_emp['status_pajak']);
		// 				$layer_pph = $this->payroll->getLayerPPH($tarif_pajak);
		// 				$get_pph = $this->payroll->getPPHPertahun($layer_pph,$data_emp['npwp']);
		// 				$data = [
		// 					'kode_periode'=>$kode_periode,
		// 					'nama_periode'=>$periode['nama'],
		// 					'tgl_mulai'=>$periode['tgl_mulai'],
		// 					'tgl_selesai'=>$periode['tgl_selesai'],
		// 					'kode_master_penggajian'=>$periode['kode_master_penggajian'],
		// 					'nik'=>$data_emp['nik'],
		// 					'nama_karyawan'=>$data_emp['nama'],
		// 					'kode_jabatan'=>$data_emp['jabatan'],
		// 					'kode_bagian'=>$data_emp['kode_bagian'],
		// 					'kode_loker'=>$data_emp['loker'],
		// 					'kode_grade'=>$data_emp['grade'],
		// 					'tgl_masuk'=>$data_emp['tgl_masuk'],
		// 					'masa_kerja'=>$value['masa_kerja'],
		// 					'status_ptkp'=>$data_emp['status_pajak'],
		// 					'gaji_pokok'=>$value['gaji_pokok'],
		// 					'tunjangan'=>$tunjangan_nominal,
		// 					'bpjs_jkk_perusahaan'=>$bpjs_p_jkk,
		// 					'bpjs_jkm_perusahaan'=>$bpjs_p_jkm,
		// 					'bpjs_kes_perusahaan'=>$bpjs_p_jkes,
		// 					'bruto_sebulan'=>$bruto_bulan,
		// 					'bruto_setahun'=>$bruto_tahun,
		// 					'biaya_jabatan'=>$biaya_jabatan['biaya_hasil'],
		// 					'bpjs_jht_perusahaan'=>$bpjs_p_jht,
		// 					'bpjs_jht_pekerja'=>$bpjs_jht,
		// 					'iuran_pensiun_perusahaan'=>$iuran_pensiun_perusahaan,
		// 					'iuran_pensiun_pekerja'=>$iuran_pensiun_pekerja,
		// 					'jml_pengurang'=>$jumlah_pengurang,
		// 					'netto_sebulan'=>$netto_bulan,
		// 					'netto_setahun'=>$netto_tahun,
		// 					'pajak_setahun'=>$tarif_pajak,
		// 					'pph_setahun'=>$get_pph['pph_tahun'],
		// 					'no_npwp'=>$data_emp['npwp'],
		// 					'pph_sebulan'=>$get_pph['plus_npwp'],
		// 				];
		// 				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
		// 				$this->model_global->updateQuery($data,'data_penggajian_pph',['nik'=>$data_emp['nik'],'kode_periode'=>$kode_periode]);
		// 			}
		// 		}else{
		// 			$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
		// 		}
		// 	}
		// 	return 'true';
	// }
	public function send_to_log()
	{
		$usage = $this->input->post('usage');
		$kode_periode = $this->input->post('kode_periode');
		if($usage == 'pindah'){
			$id_admin = $this->admin;
			$total = 0;
			// $data = $this->model_payroll->getDataPayrollSingle(['create_by'=>$id_admin,'kode_master_penggajian'=>'BULANAN','kode_periode'=>$kode_periode]);
			$data = $this->model_payroll->getDataPayrollSingle(['kode_master_penggajian'=>'BULANAN','kode_periode'=>$kode_periode]);
			foreach ($data as $d) {
				$new_data = $this->model_payroll->getDataPayrollSingle(['id_pay'=>$d->id_pay]);
				$new_data = $this->otherfunctions->convertResultToRowArray($new_data);
				unset($new_data['id_pay']);
				$new_data=array_merge($new_data,$this->model_global->getUpdateProperties($this->admin));
				$move = $this->model_global->insertUpdateQueryNoMsg($new_data,'log_data_penggajian',['id_karyawan'=>$new_data['id_karyawan'],'bulan'=>$new_data['bulan'],'tahun'=>$new_data['tahun'],'kode_periode'=>$new_data['kode_periode']]);
				if($move){
					$this->model_global->deleteQuery('data_penggajian',['id_karyawan'=>$new_data['id_karyawan'],'bulan'=>$new_data['bulan'],'tahun'=>$new_data['tahun'],'kode_periode'=>$new_data['kode_periode']]);
				}
				$total += $d->gaji_bersih;
			}
			// $kode_periode = $this->otherfunctions->convertResultToRowArray($data)['kode_periode'];

			$data_tunjangan = $this->model_payroll->getDataPayrollTunjanganSingle(['kode_master_penggajian'=>$kode_periode,'kode_master_penggajian'=>'BULANAN']);
			foreach ($data_tunjangan as $dt) {
				$new_data_t = $this->model_payroll->getDataPayrollTunjanganSingle(['id_pay_t'=>$dt->id_pay_t]);
				$new_data_t = $this->otherfunctions->convertResultToRowArray($new_data_t);
				unset($new_data_t['id_pay_t']);
				$this->model_global->insertUpdateQueryNoMsg($new_data_t,'log_data_penggajian_tunjangan',['id_karyawan'=>$new_data_t['id_karyawan'],'kode_periode_penggajian'=>$new_data_t['kode_periode_penggajian']]);
			}
			$data_periode =[
				'status_gaji'=>1,
				'tgl_transfer'=>date('Y-m-d'),
				'total_transfer'=>$total,
			];
			$data_periode=array_merge($data_periode,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQuery($data_periode,'data_periode_penggajian',['kode_periode_penggajian'=>$kode_periode]);
			// $this->model_global->deleteQuery('data_penggajian',['kode_periode'=>$kode_periode,'kode_master_penggajian'=>'BULANAN']);
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
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				$kode_periode = $this->input->post('kode_periode');
				$bagian = $this->input->post('bagian');
				$id = $this->input->post('id');
				if ($id){
					$id=$this->codegenerator->decryptChar($id);
					$where = ['a.kode_periode'=>$kode_periode,'a.id_karyawan'=>$id];
				}else{
					if(empty($kode_periode)){
						// $data = $this->model_payroll->getDataLogPayroll(['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN']);
						$where = ['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN'];
					}else{
						$where=['a.kode_periode'=>$kode_periode,'a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN'];
					}
					if (!is_null($level) && $level <= 2){
						$where=['a.kode_periode'=>$kode_periode,'a.kode_master_penggajian'=>'BULANAN'];
					}
					if ($bagian != 'all' && $bagian){
						$where['a.kode_bagian']=$bagian;
					}					
				}
				$data = $this->model_payroll->getDataLogPayroll($where);
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
					$data_1 = [
						$d->id_pay,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_grade,
						$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),'danger'),
						$this->otherfunctions->getLabelMark($masa_kerja,'danger'),
						$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
					];
					$total = 0;
					$data_2=[];
					$valTunjangan = $this->otherfunctions->getDataExplode($d->tunjangan_val,';','all');
					$masterIndukTunjangan = $this->model_master->getIndukTunjanganWhere(null,'a.sifat','DESC');
					foreach ($masterIndukTunjangan as $key_it) {
						$dtun='';
						if(!empty($valTunjangan)){
							foreach ($valTunjangan as $key_tun => $val_tun) {
								$kode_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','start');
								$nominal_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','end');
								$induk=$this->model_master->getListTunjangan(['a.kode_tunjangan'=>$kode_tunjangan],1,true);
								$nominal_t = $this->formatter->getFormatMoneyUser($nominal_tunjangan);
								if($key_it->kode_induk_tunjangan == $induk['kode_induk_tunjangan']){
									$dtun.=$nominal_t;
								}
							}
						}
						$data_2[]=$dtun;
					}
					$data_3 = [
						$this->formatter->getFormatMoneyUserReq($d->insentif),
						$this->formatter->getFormatMoneyUserReq($d->ritasi),
						$this->formatter->getFormatMoneyUserReq($d->uang_makan),
						$this->formatter->getFormatMoneyUserReq($d->pot_tidak_masuk),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jht),
						// $this->formatter->getFormatMoneyUserReq($d->bpjs_jkk),
						// $this->formatter->getFormatMoneyUserReq($d->bpjs_jkm),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_pen),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_kes),
						$this->formatter->getFormatMoneyUserReq($d->angsuran),
						// $d->angsuran_ke,
						$this->formatter->getFormatMoneyUserReq($d->nominal_denda),
						$d->angsuran_ke_denda,
						$this->payroll->getDataLainView($d->data_lain),
						$this->payroll->getDataLainView($d->data_lain_nama),
						$this->payroll->getDataLainNominalView($d->nominal_lain),
						// $this->payroll->getDataLainView($d->keterangan_lain),
						$this->formatter->getFormatMoneyUserReq($d->gaji_bersih),
						$d->no_rek,
						// $this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
						$properties['tanggal'],
						$properties['aksi']
					];
					$datax['data'][] = array_merge($data_1, $data_2, $data_3);
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pay');
				$mode = $this->input->post('mode');
				$data = $this->model_payroll->getDataLogPayroll(['a.id_pay'=>$id]);
				foreach ($data as $d) {
					$masa_kerja = $this->otherfunctions->intervalTimeYear($d->tgl_masuk);
					$penambah = $this->payroll->getTablePenambah($d->id_karyawan,$d->kode_periode,$id,'log','BULANAN',$d->tunjangan_val);
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
	
// ===================================================== DATA PENGGAJIAN LEMBUR ==========================================================

	public function data_penggajian_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				$periode = $this->input->post('periode');
				$bagian = $this->input->post('bagian');
				$id = $this->input->post('id');
				$dataSearch=['param'=>'search','periode'=>$periode,'bagian'=>$bagian];
				if ($id){
					$id=$this->codegenerator->decryptChar($id);
					$where = ['a.id_karyawan'=>$id];
				}else{
					if($param == 'all'){
						$where = ['a.create_by'=>'er','a.kode_master_penggajian'=>'BULANAN'];
					}else{
						$where = ['a.create_by'=>$this->admin,'a.kode_master_penggajian'=>'BULANAN'];
					}
					if (!is_null($level) && $level <= 2){
						if($param != 'all'){
							$where=['a.kode_master_penggajian'=>'BULANAN'];
						}
					}
				}
				$data = $this->model_payroll->getDataPayrollLembur($where,0,$dataSearch);				
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
						$d->nama_periode,
						$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
						// $this->formatter->getFormatMoneyUserReq($d->upah),
						$d->jam_biasa,
						$this->formatter->getFormatMoneyUserReq($d->nominal_biasa),
						$d->jam_libur,
						$this->formatter->getFormatMoneyUserReq($d->nominal_libur),
						$d->jam_libur_pendek,
						$this->formatter->getFormatMoneyUserReq($d->nominal_libur_pendek),
						$this->formatter->getFormatMoneyUserReq($d->gaji_terima),
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
								<th>Lama Lembur</th>
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
								<th>Total Gaji Lembur yang Diterima</th>
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
			}elseif ($usage == 'jabatan') {
				$kode_periode = $this->input->post('kode_periode');
				$log = $this->input->post('log');
				$jabatan = $this->model_payroll->getJabatanFromPeriode($kode_periode,null,$log);
				$sel_jabatan = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($jabatan) as $bkey) {
					$sel_jabatan .= '<option value="'.$bkey->kode_jabatan.'">'.$bkey->nama_jabatan.' - '.$bkey->nama_loker.'</option>';
				}
				$datax = ['jabatan'=>$sel_jabatan];
        		echo json_encode($datax);
			}elseif ($usage == 'bagian') {
				$kode_periode = $this->input->post('kode_periode');
				$log = $this->input->post('log');
				$bagian = $this->model_payroll->getBagianFromPeriode($kode_periode,null,$log);
				$sel_bagian = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($bagian) as $bkey) {
					$sel_bagian .= '<option value="'.$bkey->kode_bagian.'">'.$bkey->nama_bagian.' - '.$bkey->nama_loker.'</option>';
				}
				$datax = ['bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'bagian_sync') {
				$kode_periode = $this->input->post('kode_periode');
				$bagian = $this->model_payroll->getBagianFromPeriode($kode_periode);
				$sel_bagian = '<option value="">Pilih Data</option>';
				foreach (array_filter($bagian) as $bkey) {
					$sel_bagian .= '<option value="'.$bkey->kode_bagian.'">'.$bkey->nama_bagian.' - '.$bkey->nama_loker.'</option>';
				}
				$datax = ['bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'karyawan') {
				$kode_periode = $this->input->post('kode_periode');
				$kode_bagian = $this->input->post('kode_bagian');
				$karyawan = $this->model_payroll->getBagianFromPeriode($kode_periode,$kode_bagian);
				$sel_karyawan = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($karyawan) as $bkey) {
					$sel_karyawan .= '<option value="'.$bkey->id_karyawan.'">'.$bkey->nama_karyawan.' - '.$bkey->nama_jabatan.'</option>';
				}
				$datax = ['karyawan'=>$sel_karyawan];
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function cek_data_payroll_lembur()
	{
		$kode_periode = $this->input->post('kode_periode');
		$cekKodePeriode=$this->model_payroll->cekKodePayrollLembur(['kode_periode'=>$kode_periode]);
		if(empty($cekKodePeriode)){
			$datax = ['msg'=>'true'];
		}else{
			$datax=['msg'=>'ada_data','kode_periode'=>$kode_periode];
		}
		echo json_encode($datax);
	}
	public function ready_data_payroll_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$kode_periode = $this->input->post('kode_periode');
		if(!empty($kode_periode)){
			$karyawan = $this->payroll->getKaryawanFromPeriodeLembur($kode_periode, $this->dtroot);
			$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode_periode]));
			if(!empty($karyawan)){
				foreach (array_values(array_filter($karyawan)) as $key => $value) {
					$data_lembur = $this->payroll->getLemburEmp($value['id_karyawan'],$kode_periode);
					$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);
					$tunjangan_tetap = $this->payroll->getTunjanganNominalTetap($tunjangan);
					$upah = $value['gaji_pokok'];//+$tunjangan_tetap;
					$lembur = $this->payroll->getNominalLembur($data_lembur,$upah,$value['id_karyawan']);
					$req_lembur = $this->payroll->getReqLembur($lembur,$value['id_karyawan']);
					$nominal_biasa = $this->otherfunctions->nonPembulatan($req_lembur['nominal_biasa']);
					$nominal_libur = $this->otherfunctions->nonPembulatan($req_lembur['nominal_libur']);
					$nominal_libur_pendek = $this->otherfunctions->nonPembulatan($req_lembur['nominal_libur_pendek']);
					$nominal_lembur_libur_istirahat = $this->otherfunctions->nonPembulatan($req_lembur['nominal_lembur_libur_istirahat']);
					$gaji_terima = $nominal_biasa+$nominal_libur+$nominal_libur_pendek+$nominal_lembur_libur_istirahat;
					$data = [
						'kode_periode'                   =>$kode_periode,
						'nama_periode'                   =>$periode['nama'],
						'tgl_mulai'                      =>$periode['tgl_mulai'],
						'tgl_selesai'                    =>$periode['tgl_selesai'],
						'bulan'             	  		 =>$periode['bulan'],
						'tahun'             	  		 =>$periode['tahun'],
						'kode_master_penggajian'         =>$periode['kode_master_penggajian'],
						'id_karyawan'                    =>$value['id_karyawan'],
						'nik'                            =>$value['nik'],
						'nama_karyawan'                  =>$value['nama'],
						'kode_jabatan'                   =>$value['kode_jabatan'],
						'kode_grade'                     =>$value['kode_grade'],
						'kode_bagian'                    =>$value['kode_bagian'],
						'kode_loker'                     =>$value['kode_loker'],
						'tgl_masuk'                      =>$value['tgl_masuk'],
						'masa_kerja'                     =>$value['masa_kerja'],
						'gaji_pokok'                     =>$value['gaji_pokok'],
						'upah'                           =>$upah,
						'jam_biasa'                      =>$req_lembur['jam_biasa'],//(Int)$req_lembur['jam_biasa'],
						'nominal_biasa'                  =>$nominal_biasa,
						'jam_libur'                      =>$req_lembur['jam_libur'],
						'nominal_libur'                  =>$nominal_libur,
						'jam_libur_pendek'               =>$req_lembur['jam_libur_pendek'],
						'nominal_libur_pendek'           =>$nominal_libur_pendek,
						'jam_lembur_libur_istirahat'     =>$req_lembur['jam_lembur_libur_istirahat'],
						'nominal_lembur_libur_istirahat' =>$nominal_lembur_libur_istirahat,
						'gaji_terima'                    =>$gaji_terima,
						'no_rekening'                    =>$value['rekening'],
						'tgl_proses'                     =>date('Y-m-d h:i:s'),
						'ekuivalen'                      =>$req_lembur['ekuivalen'],
						'total_jam'                      =>($req_lembur['jam_biasa']+$req_lembur['jam_libur']+$req_lembur['jam_libur_pendek']+$req_lembur['jam_lembur_libur_istirahat']),
					];
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$cekData = $this->model_payroll->getDataPayrollLembur(['a.id_karyawan'=>$data['id_karyawan'],'a.bulan'=>$data['bulan'],'a.tahun'=>$data['tahun']]);
					// if(empty($cekData)){
						$datax=$this->model_global->insertQuery($data,'data_penggajian_lembur');
					// }
				}
			}else{
				$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
			}
		}
		echo json_encode($datax);
	}
	public function ready_data_payroll_lemburOLD()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$kode_periode = $this->input->post('kode_periode');
		if(!empty($kode_periode)){
			$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode_periode]));
			$periode_detail = $this->model_master->getListPeriodeLemburDetail($kode_periode);
			if($this->dtroot['adm']['level'] != 0){
				$en_access_jabatan = $this->payroll->getJabatanFromPetugasPayroll($this->dtroot['adm']['id_karyawan']);
			}
			$emp_loker = [];
			foreach ($periode_detail as $p) {
				$emp = $this->model_payroll->getEmployeeWhere(['emp.loker'=>$p->kode_loker],1);
				foreach ($emp as $e) {
					$emp_loker[] = $e->id_karyawan;
					// if($this->dtroot['adm']['level'] == 0){
					// 	$emp_loker[] = $e->id_karyawan;
					// }else{
					// 	if(in_array($e->jabatan,$en_access_jabatan)){
					// 		$emp_loker[] = $e->id_karyawan;
					// 	}
					// }
				}
			}
			// echo '<pre>';
			// print_r($emp_loker);
			$karyawan = [];
			if(!empty($emp_loker)){
				foreach ($emp_loker as $key => $value) {
					// $emp_single = $this->model_karyawan->getEmployeeId($value);
					$karyawan[] = $this->payroll->cekAgendaLembur($value,$kode_periode);
				}
			}else{
				$datax=$this->messages->customFailure('Tidak Ada Bagian Untuk Periode Ini, Silahkan Cek Kembali Periode Lembur');
			}
			if(!empty($karyawan)){
			// echo '<pre>';
			// print_r($karyawan);
				foreach (array_values(array_filter($karyawan)) as $key => $value) {
					$data_lembur = $this->payroll->getLemburEmp($value['id_karyawan'],$kode_periode);
					$tunjangan = $this->payroll->getTUnjangan($value['id_karyawan']);
					$tunjangan_tetap = $this->payroll->getTunjanganNominalTetap($tunjangan);
					$upah = $value['gaji_pokok'];//+$tunjangan_tetap;
					$lembur = $this->payroll->getNominalLembur($data_lembur,$upah,$value['id_karyawan']);
					$req_lembur = $this->payroll->getReqLembur($lembur,$value['id_karyawan']);
					$nominal_biasa = $this->otherfunctions->nonPembulatan($req_lembur['nominal_biasa']);
					$nominal_libur = $this->otherfunctions->nonPembulatan($req_lembur['nominal_libur']);
					$nominal_libur_pendek = $this->otherfunctions->nonPembulatan($req_lembur['nominal_libur_pendek']);
					$nominal_lembur_libur_istirahat = $this->otherfunctions->nonPembulatan($req_lembur['nominal_lembur_libur_istirahat']);
					$gaji_terima = $nominal_biasa+$nominal_libur+$nominal_libur_pendek+$nominal_lembur_libur_istirahat;
					$data = [
						'kode_periode'                   =>$kode_periode,
						'nama_periode'                   =>$periode['nama'],
						'tgl_mulai'                      =>$periode['tgl_mulai'],
						'tgl_selesai'                    =>$periode['tgl_selesai'],
						'bulan'             	  		 =>$periode['bulan'],
						'tahun'             	  		 =>$periode['tahun'],
						'kode_master_penggajian'         =>$periode['kode_master_penggajian'],
						'id_karyawan'                    =>$value['id_karyawan'],
						'nik'                            =>$value['nik'],
						'nama_karyawan'                  =>$value['nama'],
						'kode_jabatan'                   =>$value['kode_jabatan'],
						'kode_grade'                     =>$value['kode_grade'],
						'kode_bagian'                    =>$value['kode_bagian'],
						'kode_loker'                     =>$value['kode_loker'],
						'tgl_masuk'                      =>$value['tgl_masuk'],
						'masa_kerja'                     =>$value['masa_kerja'],
						'gaji_pokok'                     =>$value['gaji_pokok'],
						'upah'                           =>$upah,
						'jam_biasa'                      =>$req_lembur['jam_biasa'],//(Int)$req_lembur['jam_biasa'],
						'nominal_biasa'                  =>$nominal_biasa,
						'jam_libur'                      =>$req_lembur['jam_libur'],
						'nominal_libur'                  =>$nominal_libur,
						'jam_libur_pendek'               =>$req_lembur['jam_libur_pendek'],
						'nominal_libur_pendek'           =>$nominal_libur_pendek,
						'jam_lembur_libur_istirahat'     =>$req_lembur['jam_lembur_libur_istirahat'],
						'nominal_lembur_libur_istirahat' =>$nominal_lembur_libur_istirahat,
						'gaji_terima'                    =>$gaji_terima,
						'no_rekening'                    =>$value['rekening'],
						'tgl_proses'                     =>date('Y-m-d h:i:s'),
						'ekuivalen'                      =>$req_lembur['ekuivalen'],
						'total_jam'                      =>($req_lembur['jam_biasa']+$req_lembur['jam_libur']+$req_lembur['jam_libur_pendek']),
					];
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$cekData = $this->model_payroll->getDataPayrollLembur(['a.id_karyawan'=>$data['id_karyawan'],'a.bulan'=>$data['bulan'],'a.tahun'=>$data['tahun']]);
					if(empty($cekData)){
						// $datax=$this->db->insert('data_penggajian_lembur',$data);
						$datax=$this->model_global->insertQuery($data,'data_penggajian_lembur');
						// print_r($datax);
					}
				}
			}else{
				$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
			}
		}
		echo json_encode($datax);
	}
	public function delete_data_payroll_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$id_admin = $this->admin;
		$kode_periode = $this->input->post('kode_periode');
		// $datax=$this->model_global->deleteQuery('data_penggajian_lembur',['create_by'=>$id_admin,'kode_periode'=>$kode_periode]);
		$datax=$this->model_global->deleteQuery('data_penggajian_lembur',['kode_periode'=>$kode_periode]);
		echo json_encode($datax);
	}
	public function sync_data_penggajian_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$kode_periode = $this->input->post('periode');
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode_periode]));
		$bagian = $this->input->post('bagian');
		$emp = $this->input->post('karyawan');
		$karyawan = $this->input->post('karyawan');
		if(!empty($karyawan)){
			foreach (array_values(array_filter($karyawan)) as $key => $id_karyawan) {
				$emp_single = $this->model_karyawan->getEmployeeId($id_karyawan);
				$masa_kerja = $this->otherfunctions->intervalTimeYear($emp_single['tgl_masuk']);
				$gaji_pokok = (empty($emp_single['gaji'])) ? 0 : $emp_single['gaji'];
				if($emp_single['gaji_pokok'] == 'matrix'){
					$gaji_pokok = (empty($emp_single['gaji_pokok_grade'])) ? 0 : $emp_single['gaji_pokok_grade'];
				}
				$data_lembur = $this->payroll->getLemburEmp($id_karyawan,$kode_periode);
				$tunjangan = $this->payroll->getTUnjangan($id_karyawan);
				$tunjangan_tetap = $this->payroll->getTunjanganNominalTetap($tunjangan);
				$upah = $gaji_pokok;//+$tunjangan_tetap;
				$lembur = $this->payroll->getNominalLembur($data_lembur,$upah,$id_karyawan);
				$req_lembur = $this->payroll->getReqLembur($lembur,$id_karyawan);
				// echo '<pre>';
				// print_r($req_lembur);
				$nominal_biasa = $this->otherfunctions->nonPembulatan($req_lembur['nominal_biasa']);
				$nominal_libur = $this->otherfunctions->nonPembulatan($req_lembur['nominal_libur']);
				$nominal_libur_pendek = $this->otherfunctions->nonPembulatan($req_lembur['nominal_libur_pendek']);
				$nominal_lembur_libur_istirahat = $this->otherfunctions->nonPembulatan($req_lembur['nominal_lembur_libur_istirahat']);
				$gaji_terima = $nominal_biasa+$nominal_libur+$nominal_libur_pendek+$nominal_lembur_libur_istirahat;
				$data = [
					'kode_periode'                   =>$kode_periode,
					'nama_periode'                   =>$periode['nama'],
					'tgl_mulai'                      =>$periode['tgl_mulai'],
					'tgl_selesai'                    =>$periode['tgl_selesai'],
					'bulan'             	  		 =>$periode['bulan'],
					'tahun'             	  		 =>$periode['tahun'],
					'kode_master_penggajian'         =>$periode['kode_master_penggajian'],
					// 'id_karyawan'                    =>$value['id_karyawan'],
					// 'nik'                            =>$value['nik'],
					// 'nama_karyawan'                  =>$value['nama'],
					// 'kode_jabatan'                   =>$value['kode_jabatan'],
					// 'kode_grade'                     =>$value['kode_grade'],
					// 'kode_bagian'                    =>$value['kode_bagian'],
					// 'kode_loker'                     =>$value['kode_loker'],
					// 'tgl_masuk'                      =>$value['tgl_masuk'],
					// 'masa_kerja'                     =>$value['masa_kerja'],
					'gaji_pokok'                     =>$gaji_pokok,
					'upah'                           =>$upah,
					'jam_biasa'                      =>$req_lembur['jam_biasa'],//(Int)$req_lembur['jam_biasa'],
					'nominal_biasa'                  =>$nominal_biasa,
					'jam_libur'                      =>$req_lembur['jam_libur'],
					'nominal_libur'                  =>$nominal_libur,
					'jam_libur_pendek'               =>$req_lembur['jam_libur_pendek'],
					'nominal_libur_pendek'           =>$nominal_libur_pendek,
					'jam_lembur_libur_istirahat'     =>$req_lembur['jam_lembur_libur_istirahat'],
					'nominal_lembur_libur_istirahat' =>$nominal_lembur_libur_istirahat,
					'gaji_terima'                    =>$gaji_terima,
					// 'no_rekening'                    =>$value['rekening'],
					'tgl_proses'                     =>date('Y-m-d h:i:s'),
					'ekuivalen'                      =>$req_lembur['ekuivalen'],
					'total_jam'                      =>($req_lembur['jam_biasa']+$req_lembur['jam_libur']+$req_lembur['jam_libur_pendek']+$req_lembur['jam_lembur_libur_istirahat']),
				];
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax=$this->model_global->updateQuery($data,'data_penggajian_lembur',['id_karyawan'=>$id_karyawan,'kode_periode'=>$kode_periode]);
			}
		}else{
			$datax=$this->messages->customFailure('Karyawan Untuk Periode Ini Kosong');
		}
		echo json_encode($datax);
	}
	public function send_to_log_lembur()
	{
		$usage = $this->input->post('usage');
		$kode_periode = $this->input->post('kode_periode');
		if($usage == 'pindah'){
			$id_admin = $this->admin;
			$total = 0;
			$data = $this->model_payroll->getDataPayrollLeburSingle(['kode_master_penggajian'=>'BULANAN','kode_periode'=>$kode_periode]);
			foreach ($data as $d) {
				$new_data = $this->model_payroll->getDataPayrollLeburSingle(['id_penggajian_lembur'=>$d->id_penggajian_lembur]);
				$new_data = $this->otherfunctions->convertResultToRowArray($new_data);
				unset($new_data['id_penggajian_lembur']);
				$this->model_global->insertQuery($new_data,'log_data_penggajian_lembur');
				$total += $d->gaji_terima;
			}
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
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				$kode_periode = $this->input->post('kode_periode');
				$bagian = $this->input->post('bagian');
				// if(!empty($kode_periode)){ $form_filter['a.kode_periode'] = $kode_periode; }
				$id = $this->input->post('id');
				$dataSearch=['param'=>'search','periode'=>$kode_periode];
				if ($id){
					$id=$this->codegenerator->decryptChar($id);
					$form_filter['a.id_karyawan'] = $id;
					$form_filter['a.kode_master_penggajian'] = 'BULANAN';
					$form_filter['a.kode_periode'] = $kode_periode;
					$data = $this->model_payroll->getDataLogPayrollLembur($form_filter);
				}else{
					if (!is_null($level) && $level > 2){
						$form_filter['a.create_by'] = $id_admin;
					}
					$form_filter['a.kode_master_penggajian'] = 'BULANAN';
					$form_filter['a.kode_periode'] = $kode_periode;
					
					if ($bagian != 'all' && $bagian){
						$form_filter['a.kode_bagian']=$bagian;
					}
					$data = $this->model_payroll->getDataLogPayrollLembur($form_filter);
					
				}
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
								<td>Lembur Jam Istirahat</td>
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
				$usage = $this->input->post('usage');
				parse_str($this->input->post('form'), $post_form);
				if($usage == 'all'){
					$bulan = (isset($post_form['bulan']) && !empty($post_form['bulan']))?$post_form['bulan']:null;
					$tahun = (isset($post_form['tahun']) && !empty($post_form['tahun']))?$post_form['tahun']:null;
					$where = ['a.bulan'=>$bulan,'a.tahun'=>$tahun,];
					$data = $this->model_payroll->getListBpjsEmp($where);
				}else{
					(!empty($post_form['lokasi'])) ? $lokasi = ["emp.loker" =>$post_form['lokasi']] : $lokasi=[];
					(!empty($post_form['bagian'])) ? $bagian = ["jbt.kode_bagian" => $post_form['bagian']] : $bagian=[];
					$bulan = (isset($post_form['bulan']) && !empty($post_form['bulan']))?['a.bulan'=>$post_form['bulan']]:[];
					$tahun = (isset($post_form['tahun']) && !empty($post_form['tahun']))?['a.tahun'=>$post_form['tahun']]:[];
					$where = array_merge($lokasi,$bagian,$bulan,$tahun);
					$data = $this->model_payroll->getListBpjsEmp($where);
				}
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
						$this->formatter->getFormatMoneyUser($d->gaji_bpjs),
						$this->formatter->getFormatMoneyUser($d->gaji_bpjs_tk),
						$this->formatter->getFormatMoneyUser($d->jht),
						$this->formatter->getFormatMoneyUser($d->jkk),
						$this->formatter->getFormatMoneyUser($d->jkm),
						$this->formatter->getFormatMoneyUser($d->jpns),
						$this->formatter->getFormatMoneyUser($d->jkes),
						'<center>'.$this->formatter->getNameOfMonth($d->bulan).'</center>',
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
						'bulan'=>$this->formatter->getNameOfMonth($d->bulan),
						'ebulan'=>$d->bulan,
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
				'bulan'=>$this->input->post('bulan'),
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
				'bulan'=>$this->input->post('bulan'),
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
	public function generateBPJS()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if($usage == 'cekData'){
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$cekBPJS=$this->model_payroll->getListBpjsEmp(['a.bulan'=>$bulan,'a.tahun'=>$tahun],1);
			if(empty($cekBPJS)){
				$datax = ['msg'=>'kosong'];
			}else{
				$datax=['msg'=>'ada_data'];
			}
		}elseif($usage == 'notif'){
			$datax = $this->messages->customFailure('Data Master BPJS pada tahun tersebut Kosong');
		}elseif($usage == 'notifDataBPJS'){
			$datax = $this->messages->customFailure('Data BPJS Pada Bulan & Tahun tersebut Kosong');
		}elseif($usage == 'data'){
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$tahun_for = $this->input->post('tahun_for');
			$bulan_for = $this->input->post('bulan_for');
			if(empty($bulan) || empty($tahun) || empty($tahun_for) || empty($bulan_for)){
				$datax = $this->messages->customFailure('Inputan Tidak boleh Kosong');
			}else{
				if($bulan == $bulan_for && $tahun == $tahun_for){
					$datax = $this->messages->customFailure('Data yang akan di Generate Sama Dengan Data Sebelumnya');
				}else{
					$dataBPJS=$this->model_payroll->getListBpjsEmp(['a.tahun'=>$tahun,'a.bulan'=>$bulan],1);
					$dataEmpAktif=$this->model_karyawan->listEmpActive();
					if(!empty($dataBPJS) && !empty($dataEmpAktif)){
						foreach ($dataEmpAktif as $dea) {
							$emp_single = $this->model_karyawan->getEmployeeId($dea->id_karyawan);
							$karyawan = $this->payroll->getDataBeforeBPJSEmp($emp_single,$dataBPJS,$bulan_for,$tahun_for);
							$data=array_merge($karyawan,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($data,'data_bpjs');
						}
					}else{
						$datax = $this->messages->notValidParam();
					}
				}
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
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
					// $get_insentif = '<table>';
					// $total_ins = 0;
					// $enc_get_ins = [];
					// foreach ($data_selet_ins as $si) {
					// 	$send_ins = $this->otherfunctions->convertResultToRowArray($this->model_master->getInsentifWhere(['id_insentif'=>$si->id_insentif]));
					// 	$get_ins = $this->payroll->getInsentifPerId($send_ins,$id_karyawan);
					// 	if(!empty($get_ins)){
					// 		$get_insentif .= '<tr><td> '.$get_ins['nama'].' </td><td> '.$get_ins['tahun'].' </td><td> '.$this->formatter->getFormatMoneyUser($get_ins['nominal']).' </td></tr>';
					// 		$total_ins += $get_ins['nominal'];
					// 		$enc_get_ins[] = $get_ins;
					// 	}
					// }
					// $encr_get_insentif = $this->codegenerator->encryptChar($enc_get_ins);
					// $get_insentif .= '</table>';
					// $box_enc = '<input type="hidden" id="enc_ins_'.$id_karyawan.'" value="'.$encr_get_insentif.'">';
					// $datax['data'][]=[
					// 	$id_karyawan,
					// 	$data_emp['nik'].$box_enc,
					// 	$data_emp['nama'],
					// 	$data_emp['nama_jabatan'],
					// 	$data_emp['bagian'],
					// 	$data_emp['nama_loker'],
					// 	$data_emp['nama_grade'],
					// 	$get_insentif,
					// 	$this->formatter->getFormatMoneyUser($total_ins),
					// 	$properties['aksi'],
					// ];
					$total_ins = 0;
					$data_2=[];
					$enc_get_ins = [];
					foreach ($data_selet_ins as $si) {
						$send_ins = $this->otherfunctions->convertResultToRowArray($this->model_master->getInsentifWhere(['id_insentif'=>$si->id_insentif]));
						$get_ins = $this->payroll->getInsentifPerId($send_ins,$id_karyawan);
						if(!empty($get_ins)){
							$get_insentif = $get_ins['tahun'].' ('.$this->formatter->getFormatMoneyUserReq($get_ins['nominal']).')';
							$total_ins += $get_ins['nominal'];
							$enc_get_ins[] = $get_ins;
						}
						$data_2[]=$get_insentif;
					}
					$encr_get_insentif = $this->codegenerator->encryptChar($enc_get_ins);
					$box_enc = '<input type="hidden" id="enc_ins_'.$id_karyawan.'" value="'.$encr_get_insentif.'">';
					$data_1 = [
						$id_karyawan,
						$data_emp['nik'].$box_enc,
						$data_emp['nama'],
						$data_emp['nama_jabatan'],
						$data_emp['bagian'],
						$data_emp['nama_loker'],
						$data_emp['nama_grade'],
					];
					$data_3 = [
						$this->formatter->getFormatMoneyUserReq($total_ins),
						$properties['aksi'],
					];
					$datax['data'][] = array_merge($data_1, $data_2, $data_3);
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
	//--Data Tunjangan--//
	public function data_tunjangan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$kode = $this->input->post('kode');
				$periode = $this->input->post('periode');
				$level = $this->dtroot['adm']['level'];
				if($level != 0){
					if($kode == 'all' && empty($periode)){
						$data = $this->model_master->getListDataTunjangan(['a.kode_periode_penggajian'=>'q', 'emp.golongan'=>'1']);
					}elseif($kode == 'search' && !empty($periode)){
						$data = $this->model_master->getListDataTunjangan(['a.kode_periode_penggajian'=>$periode, 'emp.golongan'=>'1']);
					}else{
						$data = $this->model_master->getListDataTunjangan(['emp.golongan'=>'1']);
					}
				}else{
					if($kode == 'all' && empty($periode)){
						$data = $this->model_master->getListDataTunjangan(['a.kode_periode_penggajian'=>'q']);
					}elseif($kode == 'search' && !empty($periode)){
						$data = $this->model_master->getListDataTunjangan(['a.kode_periode_penggajian'=>$periode]);
					}else{
						$data = $this->model_master->getListDataTunjangan();
					}
				}
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_tunjangan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$data_1 = [
						$d->id_tunjangan,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->jabatan_karyawan,
						$d->nama_bagian,
						$d->nama_loker,
					];
					$total = 0;
					$data_2=[];
					$valTunjangan = $this->otherfunctions->getDataExplode($d->tunjangan,';','all');
					$masterIndukTunjangan = $this->model_master->getIndukTunjanganWhere(null,'a.sifat','DESC');
					foreach ($masterIndukTunjangan as $key_it) {
						$dtun='';
						foreach ($valTunjangan as $key_tun => $val_tun) {
							if($key_it->kode_induk_tunjangan == $val_tun){
								$cek = $this->model_master->getListTunjangan(['a.kode_induk_tunjangan'=>$val_tun],1);
								foreach ($cek as $c) {
									$c_karyawan = $this->otherfunctions->getDataExplode($c->karyawan,';','all');
									if (!empty($c->karyawan)) {
										if(in_array($d->id_karyawan, $c_karyawan)){
											$dtun.=$this->formatter->getFormatMoneyUserReq($c->nominal);
											$total +=$c->nominal;
										}
									}
								}
							}
						}
						$data_2[]=$dtun;
					}
					$data_3 = [
						$this->formatter->getFormatMoneyUserReq($total),
						$d->nama_periode,
						$properties['aksi'],
					];
					$datax['data'][] = array_merge($data_1, $data_2, $data_3);
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$mode = $this->input->post('mode');
				$data = $this->model_master->getListDataTunjangan(['a.id_tunjangan'=>$id]);
				foreach ($data as $d) {
					$tunjanganKar ='';
					$valTunjangan = $this->otherfunctions->getDataExplode($d->tunjangan,';','all');
					$masterIndukTunjangan = $this->model_master->getListIndukTunjanganActive();
					foreach ($masterIndukTunjangan as $key_it => $value_it) {
						$dtun='';
						foreach ($valTunjangan as $key_tun => $val_tun) {
							if($key_it == $val_tun){
								$cek = $this->model_master->getListTunjangan(['a.kode_induk_tunjangan'=>$val_tun],1);
								foreach ($cek as $c) {
									$c_karyawan = $this->otherfunctions->getDataExplode($c->karyawan,';','all');
									if (!empty($c->karyawan)) {
										if(in_array($d->id_karyawan, $c_karyawan)){
											$dtun.=$this->formatter->getFormatMoneyUserReq($c->nominal);
										}
									}
								}
							}
						}
						$tunjanganKar .= '
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">'.ucwords(strtolower($value_it)).'</label>
								<div class="col-md-6">'.$dtun.'</div>
							</div>
						</div>';
					}
					$e_tunjangan=[];
					foreach ($valTunjangan as $key_t => $val_t) {
						$e_tunjangan[]=$val_t;
					}
					$datax=[
						'id'=>$d->id_tunjangan,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jabatan'=>$d->jabatan_karyawan,
						'bagian'=>$d->nama_bagian,
						'loker'=>$d->nama_loker,
						'nama_periode'=>$d->nama_periode,
						'kode_periode'=>$d->kode_periode_penggajian,
						'data_tunjangan_view'=>$tunjanganKar,
						'e_tunjangan'=>$e_tunjangan,
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
			}elseif ($usage == 'jumlah_induk_tunjangan') {
				$masterIndukTunjangan = $this->model_master->getListIndukTunjanganActive();
				$datax=[
					'jumlah'=>count($masterIndukTunjangan),
				];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_tunjangan(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$karyawan = $this->input->post('karyawan');
		$tunjangan = $this->input->post('tunjangan');
		$periode = $this->input->post('periode');
		if(!empty($karyawan) || !empty($tunjangan)){
			if(in_array('all', $karyawan)){
				$empx = $this->model_karyawan->getEmployeeAllActive();
				$emp = [];
				foreach ($empx as $e) {
					$emp[] = $e->id_karyawan;
				}
			}else{
				$emp = $karyawan;
			}
			if(in_array('all', $tunjangan)){
				$data_m =[];
				$masterIndukTunjangan = $this->model_master->getListIndukTunjanganActive();
				foreach ($masterIndukTunjangan as $key_it => $value_it) {
					$data_m[]=$key_it;
				}
				$tunjanganx = implode(";", $data_m);
			}else{
				$tunjanganTetap = $this->model_master->getIndukTunjanganWhere(['a.sifat'=>1]);
				$ttp = [];
				foreach ($tunjanganTetap as $tt) {
					$ttp[] = $tt->kode_induk_tunjangan;
				}
				$tunjanganv = array_merge($tunjangan,$ttp);
				$tunjanganx = implode(";", $tunjanganv);
			}
			foreach ($emp as $id_kar) {
				$cekdata = $this->model_master->getListDataTunjangan(['a.kode_periode_penggajian'=>$periode,'a.id_karyawan'=>$id_kar],'all_item',true);
				if(empty($cekdata)){
					$data = [
						'id_karyawan'=>$id_kar,
						'kode_periode_penggajian'=>$periode,
						'tunjangan'=>$tunjanganx,
					];
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQuery($data,'data_pendukung_tunjangan');
				}else{
					$tunLama = $this->otherfunctions->getDataExplode($cekdata['tunjangan'],';','all');
					$tunNew = array_merge($tunLama, $tunjangan);
					$tunjangany = array_unique($tunNew);
					$tunjanganxz = implode(";", $tunjangany);
					$data = [
						'id_karyawan'=>$id_kar,
						'kode_periode_penggajian'=>$periode,
						'tunjangan'=>$tunjanganxz,
					];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQuery($data,'data_pendukung_tunjangan',['kode_periode_penggajian'=>$periode,'id_karyawan'=>$id_kar]);
				}
			}
			$datax = $this->messages->allGood();
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function delete_tunjangan()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id_tunjangan = $this->input->post('id');
		$datax=$this->model_global->deleteQuery('data_pendukung_tunjangan',['id_tunjangan'=>$id_tunjangan]);
		echo json_encode($datax);
	}
	public function edit_tunjangan()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id_tunjangan = $this->input->post('id_tunjangan');
		$tunjangan = $this->input->post('tunjangan');
		$periode = $this->input->post('periode');
		if(!empty($id_tunjangan) || !empty($tunjangan) || !empty($periode)){
			if(in_array('all', $tunjangan)){
				$data_m =[];
				$masterIndukTunjangan = $this->model_master->getListIndukTunjanganActive();
				foreach ($masterIndukTunjangan as $key_it => $value_it) {
					$data_m[]=$key_it;
				}
				$tunjanganx = implode(";", $data_m);
			}else{
				$tunjanganx = implode(";", $tunjangan);
			}
			$cekdata = $this->model_master->getListDataTunjangan(['a.id_tunjangan'=>$id_tunjangan],'all_item',true);
			if(!empty($cekdata)){
				// $tunLama = $this->otherfunctions->getDataExplode($cekdata['tunjangan'],';','all');
				// $tunNew = array_merge($tunLama, $tunjangan);
				// $tunjangany = array_unique($tunNew);
				// $tunjanganxz = implode(";", $tunjangany);
				// $data = [
					// 'id_karyawan'=>$id_kar,
				// 	'kode_periode_penggajian'=>$periode,
				// 	'tunjangan'=>$tunjanganxz,
				// ];
				// $tunjanganxz = implode(";", $tunjangan);
				$data = [
					'kode_periode_penggajian'=>$periode,
					'tunjangan'=>$tunjanganx,
				];
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQuery($data,'data_pendukung_tunjangan',['id_tunjangan'=>$id_tunjangan]);
			}
			$datax = $this->messages->allGood();
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Data Pendukung Denda--//
	
	public function pilih_denda()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_payroll->getPilihdenda();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_karyawan,
				'<a class="pilih" style="cursor:pointer" 
				data-kode 			="'.$d->kode.'"
				data-nik 			="'.$d->nik.'"
				data-id_karyawan	="'.$d->id_karyawan.'"
				data-nama 			="'.$d->nama.'"
				data-jabatan 		="'.$d->jabatan.'"
				data-nama_jabatan	="'.$d->nama_jabatan.'"
				data-kode_lokasi	="'.$d->loker.'"
				data-nama_lokasi	="'.$d->nama_loker.'"
				data-tgl_denda		="'.$this->formatter->getDateFormatUser($d->tgl_denda).'"
				data-total_denda	="'.$this->formatter->getFormatMoneyUser($d->total_denda).'"
				data-diangsur		="'.$d->diangsur.'"
				data-besar_angsuran	="'.$this->formatter->getFormatMoneyUser($d->besar_angsuran).'"
				data-status_denda	="'.(($d->status_denda=='non_peringatan')?'Non Peringatan':'Denda Dari Peringatan').'"
				data-keterangan		="'.$d->keterangan.'">'.
				$d->kode.'</a>',
				$d->nik,
				$d->nama,
				$d->nama_jabatan,
				(($d->status_denda=='non_peringatan')?'Non Peringatan':'Denda Dari Peringatan'),
			];
		}
		echo json_encode($datax);		
	}
	public function data_denda()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_payroll->getListDataDenda();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_angsuran,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$empKodeGaji = $this->model_karyawan->getPeriodePenggajianKar($d->id_karyawan)['kode_penggajian'];
					$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$d->kode_periode_penggajian]));
					$periodeHarian = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$d->kode_periode_penggajian]));
					if($empKodeGaji=='BULANAN'){
						$periodeGaji=$periode['nama'].' ('.$periode['nama_sistem_penggajian'].')';
					}else{
						$periodeGaji=$periodeHarian['nama'].' ('.$periodeHarian['nama_sistem_penggajian'].')';
					}
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal("'.$d->kode_denda.'")><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal("'.$d->kode_denda.'")><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$status_denda=($d->status_denda=='Lunas')?'<label class="label label-success" style="font-size:10pt;"><i class="fa fa-check"></> '.$d->status_denda.'</label>':'<label class="label label-danger" style="font-size:10pt;"><i class="fa fa-times"></> '.$d->status_denda.'</label>';
					$datax['data'][]=[
						$d->id_angsuran,
						$d->kode_angsuran,
						$d->kode_denda,
						$emp['nama'],
						$d->angsuran_ke,
						$this->formatter->getFormatMoneyUser($d->saldo_denda),
						$status_denda,
						$periodeGaji,
						$properties['tanggal'],
						// $properties['status'],
						$info.$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$kode_denda = $this->input->post('kode_denda');
				$mode = $this->input->post('mode');
				$data=$this->model_payroll->getListDendaKode(['kode_denda'=>$kode_denda]);
				foreach ($data as $d) {
          			$data_denda=$this->model_payroll->getListDendaKode(['a.id_karyawan'=>$d->id_karyawan,'kode_denda'=>$kode_denda]);
          			if(count($data_denda) != null){
						$tabel='';
						$tabel.='<h4 align="center"><b>Data Denda '.$d->nama.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
							<table class="table table-bordered table-striped data-table">
	          					<thead>
	          						<tr class="bg-blue">
	          							<th>No.</th>
	          							<th>Kode Angsuran</th>
	          							<th>Angsuran Ke</th>
	          							<th>Tanggal Angsuran</th>
	          							<th>Besar Angsuran</th>
	          							<th>Sisa Denda</th>
	          							<th>Status Denda</th>
	          							<th>Periode Penggajian</th>
	          							<th>Aksi</th>
	          						</tr>
	          					</thead>
	          					<tbody>';
	          						$no=1;
	          						foreach ($data_denda as $d_p) {
										$empKodeGaji = $this->model_karyawan->getPeriodePenggajianKar($d_p->id_karyawan)['kode_penggajian'];
										$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$d_p->kode_periode_penggajian]));
										$periodeHarian = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$d_p->kode_periode_penggajian]));
										if($empKodeGaji=='BULANAN'){
											$periodeGaji=$periode['nama'].' ('.$periode['nama_sistem_penggajian'].')';
										}else{
											$periodeGaji=$periodeHarian['nama'].' ('.$periodeHarian['nama_sistem_penggajian'].')';
										}
										$status_denda=($d_p->status_denda=='Lunas')?'<label class="label label-success" style="font-size:10pt;"><i class="fa fa-check"></> '.$d_p->status_denda.'</label>':'<label class="label label-danger" style="font-size:10pt;"><i class="fa fa-times"></> '.$d_p->status_denda.'</label>';
	          							$tabel.='<tr>
	          							<td>'.$no.'</td>
	          							<td>'.$d_p->kode_angsuran.'</td>
	          							<td>'.$d_p->angsuran_ke.'</td>
	          							<td>'.$this->formatter->getDateTimeMonthFormatUser($d_p->tgl_angsuran).'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($d_p->besar_angsuran).'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($d_p->saldo_denda).'</td>
	          							<td>'.$status_denda.'</td>
	          							<td>'.$periodeGaji.'</td>
	          							<td><button type="button" class="btn btn-sm btn-info" href="javascript:void(0)" onclick=edit_modal("'.$d_p->kode_angsuran.'")><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-sm btn-danger" href="javascript:void(0)" onclick=delete_modal_u("'.$d_p->kode_angsuran.'")><i class="fa fa-trash"></i></button></td>
	          						</tr>';
	          						$no++;
	          					}
		          				$tabel.='</tbody>
		          			</table>';
	          		}else{
	          			$tabel=null;
	          		}
					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$datax=[
						// 'id'=>$d->id_pinjaman,
						'kode'=>$d->kode_angsuran,
						'kode_denda'=>$d->kode_denda,
						'nama'=>$d->nama,
						'karyawan'=>($mode == 'edit') ? $d->id_karyawan : $emp['nama'],
						'jabatan'=>($mode == 'edit') ? '' : $emp['nama_jabatan'],
						'bagian'=>($mode == 'edit') ? '' : $emp['bagian'],
						'loker'=>($mode == 'edit') ? '' : $emp['nama_loker'],
						// 'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'angsuran_ke'=>(!empty($d->angsuran_ke)) ? $d->angsuran_ke : 0,
						// 'tahun'=>$d->tahun,
						'keterangan'=>$d->keterangan,
						'periode'=>($mode == 'edit') ? $d->kode_periode_penggajian : $d->nama_periode,
						'tabel'=>$tabel,
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
			}elseif ($usage == 'view_one_angsuran') {
				$kode_angsuran = $this->input->post('kode_angsuran');
				$mode = $this->input->post('mode');
				$data=$this->model_payroll->getListDendaKode(['kode_angsuran'=>$kode_angsuran]);
				foreach ($data as $d) {
					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$kodePenggajian = $this->model_karyawan->getPeriodePenggajianKar($d->id_karyawan)['kode_penggajian'];
					$selectKodePengB='';
					$selectKodePengH='';
					if($kodePenggajian=='BULANAN'){
						$periode = $this->model_master->getListPeriodePenggajian(null,['status_gaji'=>0]);
						$selectKodePengB.='<option></option>';
						foreach ($periode as $p) {
							$selected=($d->kode_periode_penggajian == $p->kode_periode_penggajian)?'selected':null;
							$selectKodePengB.='<option '.$selected.' value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
						}
					}else{
						$periode = $this->model_master->getListPeriodePenggajianHarian(null,['status_gaji'=>0]);
						$selectKodePengH.='<option></option>';
						foreach ($periode as $p) {
							$selected=($d->kode_periode_penggajian == $p->kode_periode_penggajian_harian)?'selected':null;
							$selectKodePengH.='<option '.$selected.' value="'.$p->kode_periode_penggajian_harian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
						}
					}
					$selectKodePeng = ($kodePenggajian=='BULANAN')?$selectKodePengB:$selectKodePengH;
					$datax=[
						'kode'=>$d->kode_angsuran,
						'kode_denda'=>$d->kode_denda,
						'nama'=>$d->nama,
						'besar_angsuran'=>$this->formatter->getFormatMoneyUser($d->besar_angsuran),
						'angsuran_ke'=>$d->angsuran_ke,
						'tgl_angsuran'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_angsuran),
						'saldo_denda'=>$this->formatter->getFormatMoneyUser($d->saldo_denda),
						'total_denda'=>$this->formatter->getFormatMoneyUser($d->total_denda),
						'status_denda'=>$d->status_denda,
						'keterangan'=>$d->keterangan,
						'periode'=>($mode == 'edit') ? $d->kode_periode_penggajian : $d->nama_periode,
						'periode_e'=>$selectKodePeng,
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
				$data = $this->codegenerator->kodeAngsuranDenda();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_angsuran_denda()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode           = $this->input->post('kode');
		$kode_denda     = $this->input->post('kode_denda');
		$id_karyawan    = $this->input->post('id_karyawan');
		$nik            = $this->input->post('nik');
		$nama           = $this->input->post('nama');
		$besar_angsuran = $this->formatter->getFormatMoneyDb($this->input->post('besar_angsuran'));
		$diangsur       = $this->input->post('diangsur');
		$total          = $this->formatter->getFormatMoneyDb($this->input->post('total'));
		$status         = $this->input->post('status');
		$tgl            = $this->input->post('tgl');
		$keterangan     = $this->input->post('keterangan');
		$periode        = $this->input->post('periode');
		$cekAngsuranDenda = $this->model_payroll->cekAngsuranDenda($kode_denda);
		$angsuranDenda    = (($cekAngsuranDenda['angsuran_ke']==null || $cekAngsuranDenda['angsuran_ke']=="") ? 0 : $cekAngsuranDenda['angsuran_ke']);
		$angsuran_ke      = ($angsuranDenda+1);
		$saldo_denda      = $total-($besar_angsuran*$angsuran_ke);
		$lunas            = (($diangsur==$angsuran_ke)?'Lunas':'Belum Lunas');
		if ($kode != "" && $kode_denda != "") {
			$data = array(
				'kode_angsuran'          =>$kode,
				'kode_denda'             =>$kode_denda,
				'kode_periode_penggajian'=>$periode,
				'id_karyawan'            =>$id_karyawan,
				'nik'                    =>$nik,
				'nama'                   =>$nama,
				'besar_angsuran'         =>$besar_angsuran,
				'angsuran_ke'            =>$angsuran_ke,
				'tgl_angsuran'           =>$this->date,
				'saldo_denda'            =>$saldo_denda,
				'total_denda'            =>$total,
				'keterangan'             =>$keterangan,
				'status_denda'           =>$lunas,
			);
			if($lunas=='Lunas'){
				$data_den=['lunas'=>1];
				$data_den=array_merge($data_den,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQuery($data_den,'data_denda',['kode'=>$kode_denda]);
			}
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'data_denda_angsuran',$this->model_master->checkPinjamanCode($kode));	
		}else{
			$datax=$this->messages->customFailure('Kode Angsuran Atau Kode Denda Kosong'); 
		}
		echo json_encode($datax);
	}
	function edit_data_denda(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kodeangsuran = $this->input->post('kode_angsuran');
		$periode = $this->input->post('periode');

		if ($kodeangsuran != "") {
			$data=array(
				'kode_angsuran'=>$kodeangsuran,
				'kode_periode_penggajian'=>$periode,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_denda_angsuran',['kode_angsuran'=>$kodeangsuran]);
		}else{
        	$datax=$this->messages->notValidParam();
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
				// $data = $this->model_payroll->getListDataPendukungLain();
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$kode = $this->input->post('kode');
				$periode = $this->input->post('periode');
				if($kode == 'all' && empty($periode)){
					$data = $this->model_payroll->getListDataPendukungLain(['a.kode_periode'=>'q']);
				}elseif($kode == 'search' && !empty($periode)){
					$data = $this->model_payroll->getListDataPendukungLain(['a.kode_periode'=>$periode]);
				}else{
					$data = $this->model_payroll->getListDataPendukungLain();
				}
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
					$empKodeGaji = $this->model_karyawan->getPeriodePenggajianKar($d->id_karyawan)['kode_penggajian'];
					$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$d->kode_periode]));
					$periodeHarian = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$d->kode_periode]));
					if($empKodeGaji=='BULANAN'){
						$periodeGaji=$periode['nama'].' ('.$periode['nama_sistem_penggajian'].')';
					}else{
						$periodeGaji=$periodeHarian['nama'].' ('.$periodeHarian['nama_sistem_penggajian'].')';
					}
					$datax['data'][]=[
						$d->id_pen_lain,
						$d->kode_pen_lain,
						$d->nama,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$this->formatter->getFormatMoneyUser($d->nominal),
						ucwords($d->sifat),
						ucwords($d->keterangan),
						$periodeGaji,
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
					// $emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$empKodeGaji = $this->model_karyawan->getPeriodePenggajianKar($d->id_karyawan)['kode_penggajian'];
					$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$d->kode_periode]));
					$periodeHarian = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$d->kode_periode]));
					if($empKodeGaji=='BULANAN'){
						$periodeGaji=$periode['nama'].' ('.$periode['nama_sistem_penggajian'].')';
					}else{
						$periodeGaji=$periodeHarian['nama'].' ('.$periodeHarian['nama_sistem_penggajian'].')';
					}
					$selectKodePengB='';
					$selectKodePengH='';
					if($empKodeGaji=='BULANAN'){
						$periode = $this->model_master->getListPeriodePenggajian(null,['status_gaji'=>0]);
						// $selectKodePengB.='<option></option>';
						foreach ($periode as $p) {
							$selected=($d->kode_periode == $p->kode_periode_penggajian)?'selected':null;
							$selectKodePengB.='<option '.$selected.' value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
						}
					}else{
						$periode = $this->model_master->getListPeriodePenggajianHarian(null,['status_gaji'=>0]);
						// $selectKodePengH.='<option></option>';
						foreach ($periode as $p) {
							$selected=($d->kode_periode == $p->kode_periode_penggajian_harian)?'selected':null;
							$selectKodePengH.='<option '.$selected.' value="'.$p->kode_periode_penggajian_harian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
						}
					}
					$selectKodePeng = ($empKodeGaji=='BULANAN')?$selectKodePengB:$selectKodePengH;
					$datax=[
						'id'=>$d->id_pen_lain,
						'kode'=>$d->kode_pen_lain,
						'nama'=>$d->nama,
						'nama_karyawan'=>$d->nama_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'id_karyawan'=>$d->id_karyawan,
						'karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik,
						'kode_periode'=>$d->kode_periode,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'sifat'=>($mode == 'view') ? ucwords($d->sifat) : $d->sifat,
						'periode'=>($mode == 'view') ? $periodeGaji : $selectKodePeng,
						'keterangan'=>($mode == 'view') ? ucwords($d->keterangan) : $d->keterangan,
						'hallo'=>($mode == 'view') ? $this->otherfunctions->getYesNo($d->hallo) : $d->hallo,
						// 'jenis'=>$this->otherfunctions->getJenisPendukungPayroll($d->jenis),
						// 'e_jenis'=>$d->jenis,
						// 'periode'=>($mode == 'view') ? $periodeGaji : $d->kode_periode,
						// 'minggu_view'=>$this->otherfunctions->getlistWeek($d->minggu),
						// 'bulan_view'=>$this->formatter->getNameOfMonth($d->bulan),
						// 'minggu'=>$d->minggu,
						// 'bulan'=>$d->bulan,
						// 'tahun'=>$d->tahun,
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
		$nama = $this->input->post('nama');
		$keterangan = $this->input->post('keterangan');
		$periode = $this->input->post('periode');
		$periode_old = $this->input->post('kode_periode_old');
		if($periode == $periode_old || empty($periode)){
			$kode_periode = $periode_old;
		}else{
			$kode_periode =$periode;
		}
		if ($id != "") {
			$data=array(
				'id_karyawan'=>$karyawan,
				'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
				'sifat'=>$sifat,
				'nama'=>$nama,
				'keterangan'=>ucwords($keterangan),
				'kode_periode'=>$kode_periode,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_pendukung_lain',['id_pen_lain'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function add_pendukung_lain()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$karyawan = $this->input->post('karyawan');
		$nominal = $this->input->post('nominal');
		$sifat = $this->input->post('sifat');
		$nama = $this->input->post('nama');
		$keterangan = $this->input->post('keterangan');
		$periode = $this->input->post('periode');
		if(!empty($karyawan)){
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
					'nama'=>$nama,
					'hallo'=>$this->input->post('hallo'),
					'keterangan'=>ucwords($keterangan),
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
		if(!empty($karyawan) || !empty($periode)){
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
				$cek_data = $this->model_payroll->getListDataPendukungLain(['a.id_karyawan'=>$evalue,'a.kode_periode'=>$periode]);
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
	public function import_pendukung_lain()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		   $periode = $this->input->post('periode');
			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			$sheet[0]=[
				'range_huruf'=>3,
				'row'=>2,
				'other'=>['kode_periode'=>$periode],
				'table'=>'data_pendukung_lain',
				'column_code'=>'id_karyawan',
				'usage'=>'import_pendukung_lain',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'column'=>[
					1=>'nik',3=>'nama',4=>'nominal',5=>'sifat',6=>'hallo',7=>'keterangan',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	//============================================================ DATA PPH 21 ===========================================================//
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
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('tahun');
				$koreksi = $this->input->post('koreksi');
				// $form_filter['a.create_by'] = $id_admin;
				if(!empty($bulan)){ $form_filter['a.bulan'] = $bulan; }
				if(!empty($tahun)){ $form_filter['a.tahun'] = $tahun; }
				if(!empty($koreksi)){ $form_filter['a.koreksi'] = $koreksi; }
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
					// $bulan = $this->formatter->getNameOfMonth(date('m', strtotime($d->tgl_selesai_periode)));
					// $pajak_setahun = ($d->pajak_setahun < 0) ? '( '.$this->formatter->getFormatMoneyUserReq(abs($d->pajak_setahun)).' )' : $this->formatter->getFormatMoneyUserReq($d->pajak_setahun);
					// $datax['data'][]=[
					// 	$d->id_p_pph,
					// 	$d->nik,
					// 	$d->nama_karyawan,
					// 	$d->no_npwp,
					// 	$d->nama_jabatan,
					// 	$d->nama_bagian,
					// 	$d->nama_loker,
					// 	$d->nama_grade,
					// 	$this->otherfunctions->getLabelMark($this->formatter->getDateMonthFormatUser($d->tgl_masuk),'danger'),
					// 	$this->otherfunctions->getLabelMark($d->masa_kerja,'danger'),
					// 	// $d->nama_periode,
					// 	// $d->nama_sistem_penggajian,
					// 	// $bulan,
					// 	$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
					// 	$this->formatter->getFormatMoneyUserReq($d->tunjangan),
					// 	$this->formatter->getFormatMoneyUserReq($d->bruto_sebulan),
					// 	$this->formatter->getFormatMoneyUserReq($d->netto_sebulan),
					// 	$pajak_setahun,
					// 	$this->formatter->getFormatMoneyUserReq($d->pph_setahun),
					// 	$this->formatter->getFormatMoneyUserReq($d->pph_sebulan),
					// 	$properties['tanggal'],
					// 	$properties['aksi']
					// ];
					$body_1 = [
						$d->id_p_pph,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_bagian,
						// $d->nama_loker,
						// $d->nama_grade,
						$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
						$d->status_ptkp,
						$this->otherfunctions->getNumberToAbjad($d->koreksi),
					];
					$body_2=[];
					$valTunjangan = $this->otherfunctions->getDataExplode($d->tunjangan_val,';','all');
					$masterIndukTunjangan = $this->model_master->getIndukTunjanganWhere(null,'a.sifat','DESC');
					foreach ($masterIndukTunjangan as $key_it) {
						$dtun='';
						if(!empty($valTunjangan)){
							foreach ($valTunjangan as $key_tun => $val_tun) {
								$kode_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','start');
								$nominal_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','end');
								$induk=$this->model_master->getListTunjangan(['a.kode_tunjangan'=>$kode_tunjangan],1,true);
								$nominal_t = $this->formatter->getFormatMoneyUserReq($nominal_tunjangan);
								if($key_it->kode_induk_tunjangan == $induk['kode_induk_tunjangan']){
									$dtun.=$nominal_t;
								}
							}
						}
						$body_2[]=$dtun;
					}
					$body_3 = [
						$this->formatter->getFormatMoneyUserReq($d->tunjangan),
						$this->formatter->getFormatMoneyUserReq($d->uang_makan),
						$this->formatter->getFormatMoneyUserReq($d->ritasi),
						$this->formatter->getFormatMoneyUserReq($d->lembur),
						$this->formatter->getFormatMoneyUserReq($d->perjalanan_dinas),
						$this->formatter->getFormatMoneyUserReq($d->kode_akun),
						$this->formatter->getFormatMoneyUserReq($d->bonus),
						$this->formatter->getFormatMoneyUserReq($d->thr),
					];
					$pengurang_lain = 0;
					$penambah_lain = 0;
					if(!empty($d->data_lain)){
						$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
						$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
						foreach ($dLain as $key => $value) {
							if($value == 'pengurang'){
								$pengurang_lain += $nLain[$key];
							}else{
								$penambah_lain += $nLain[$key];
							}
						}
					}
					$body_4 = [
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jkk_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jkm_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->pph),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jht_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_kes_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->iuran_pensiun_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->premi_asuransi),
						$this->formatter->getFormatMoneyUserReq($penambah_lain)
					];
					$potonganTidakKerja = ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
					$body_5 = [
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jht_pekerja),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jkk_pekerja),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jkm_pekerja),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_kes_pekerja),
						$this->formatter->getFormatMoneyUserReq($d->iuran_pensiun_pekerja),
						$this->formatter->getFormatMoneyUserReq($d->hutang),
						$this->formatter->getFormatMoneyUserReq($potonganTidakKerja),
						$this->formatter->getFormatMoneyUserReq($d->nominal_denda),
						$this->formatter->getFormatMoneyUserReq($pengurang_lain),
					];
					$body_6 = [
						$this->formatter->getFormatMoneyUserReq($d->yg_diterima),
						$this->formatter->getFormatMoneyUserReq($d->bruto_sebulan),
						$this->formatter->getFormatMoneyUserReq($d->bruto_setahun),
						$this->formatter->getFormatMoneyUserReq($d->pesangon),
						$this->formatter->getFormatMoneyUserReq($d->pph_pesangon),
						$this->formatter->getFormatMoneyUserReq($d->biaya_jabatan),
						$this->formatter->getFormatMoneyUserReq(($d->biaya_jabatan*12)),
						$this->formatter->getFormatMoneyUserReq($d->netto_sebulan),
						$this->formatter->getFormatMoneyUserReq($d->netto_setahun),
						$this->formatter->getFormatMoneyUserReq(($d->ptkp/12)),
						$this->formatter->getFormatMoneyUserReq($d->ptkp),
						$this->formatter->getFormatMoneyUserReq(($d->pkp/12)),
						$this->formatter->getFormatMoneyUserReq($d->pkp),
						$this->formatter->getFormatMoneyUserReq($d->pph_sebulan),
						$this->formatter->getFormatMoneyUserReq($d->pph_setahun),
						$this->formatter->getFormatMoneyUserReq($d->pajak_setahun),
						$this->formatter->getFormatMoneyUserReq($d->pph_dibayar),
						$this->formatter->getFormatMoneyUserReq($d->pph_dipotong),
						$this->formatter->getFormatMoneyUserReq($d->pph_tunjangan),
						$d->no_npwp,
						$properties['tanggal'],
						$properties['aksi']
					];
					$datax['data'][]=array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
					// $datax['data'][]=array_merge($body_1,$body_2,$body_3,$body_4,$body_5,$body_6);
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
					$pengurang_lain = 0;
					$penambah_lain = 0;
					if(!empty($d->data_lain)){
						$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
						$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
						foreach ($dLain as $key => $value) {
							if($value == 'pengurang'){
								$pengurang_lain += $nLain[$key];
							}else{
								$penambah_lain += $nLain[$key];
							}
						}
					}
					$potonganTidakKerja = ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
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
						'bpjs_jht_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_jht_perusahaan),
						'iuran_pensiun_perusahaan'=>$this->formatter->getFormatMoneyUser($d->iuran_pensiun_perusahaan),
						'uang_makan'=>$this->formatter->getFormatMoneyUser($d->uang_makan),
						'ritasi'=>$this->formatter->getFormatMoneyUser($d->ritasi),
						'lembur'=>$this->formatter->getFormatMoneyUser($d->lembur),
						'perdin'=>$this->formatter->getFormatMoneyUser($d->perjalanan_dinas),
						'kode_akun'=>$this->formatter->getFormatMoneyUser($d->kode_akun),
						'bpjs_pph'=>$this->formatter->getFormatMoneyUser($d->pph),
						'premi_asuransi'=>$this->formatter->getFormatMoneyUser($d->premi_asuransi),
						'bpjs_jht_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_jht_pekerja),
						'iuran_pensiun_pekerja'=>$this->formatter->getFormatMoneyUser($d->iuran_pensiun_pekerja),
						'bpjs_jkk_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_jkk_pekerja),
						'bpjs_jkm_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_jkm_pekerja),
						'bpjs_kes_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_kes_pekerja),
						'piutang'=>$this->formatter->getFormatMoneyUser($d->hutang),
						'koreksi_absen'=>$this->formatter->getFormatMoneyUser($potonganTidakKerja),
						'denda'=>$this->formatter->getFormatMoneyUser($d->nominal_denda),
						'tambah_lainnya'=>$this->formatter->getFormatMoneyUser($penambah_lain),
						'potongan_lain'=>$this->formatter->getFormatMoneyUser($pengurang_lain),
						'jml_pengurang'=>$this->formatter->getFormatMoneyUser($d->jml_pengurang),
						'netto_sebulan'=>$this->formatter->getFormatMoneyUser($d->netto_sebulan),
						'netto_setahun'=>$this->formatter->getFormatMoneyUser($d->netto_setahun),
						'pajak_setahun'=>$pajak_setahun,
						'pph_setahun'=>$this->formatter->getFormatMoneyUser($d->pph_setahun),
						'no_npwp'=>$d->no_npwp,
						'pph_sebulan'=>$this->formatter->getFormatMoneyUser($d->pph_sebulan),
						'yg_diterima'=>$this->formatter->getFormatMoneyUser($d->yg_diterima),
						'bruto_sebulan'=>$this->formatter->getFormatMoneyUser($d->bruto_sebulan),
						'bruto_setahun'=>$this->formatter->getFormatMoneyUser($d->bruto_setahun),
						'biaya_jabatan'=>$this->formatter->getFormatMoneyUser($d->biaya_jabatan),
						'netto_sebulan'=>$this->formatter->getFormatMoneyUser($d->netto_sebulan),
						'netto_setahun'=>$this->formatter->getFormatMoneyUser($d->netto_setahun),
						'ptkp_sebulan'=>$this->formatter->getFormatMoneyUser(($d->ptkp/12)),
						'ptkp_setahun'=>$this->formatter->getFormatMoneyUser($d->ptkp),
						'pkp_sebulan'=>$this->formatter->getFormatMoneyUser(($d->pkp/12)),
						'pkp_setahun'=>$this->formatter->getFormatMoneyUser($d->pkp),
						'pph21sebulan'=>$this->formatter->getFormatMoneyUser($d->pph_sebulan),
						'pph21setahun'=>$this->formatter->getFormatMoneyUser($d->pph_setahun),
						'pajak_setahun'=>$this->formatter->getFormatMoneyUser($d->pajak_setahun),
						'koreksi'=>$this->otherfunctions->getNumberToAbjad($d->koreksi),
						'pph21_dibayar'=>$this->formatter->getFormatMoneyUser($d->pph_dibayar),
						'pph21_dipotong'=>$this->formatter->getFormatMoneyUser($d->pph_dipotong),
						'tunjangan_pph'=>$this->formatter->getFormatMoneyUser($d->pph_tunjangan),
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
			}elseif ($usage == 'insert_penunjang') {
				$tabel_end_proses='<table class="table table-bordered table-striped data-table" id="myTable">
								<thead>
									<tr class="bg-blue">
										<th>Penunjang</th>
										<th>Nominal</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>';
				$data_tunjangan=$this->otherfunctions->getPenunjangList();
				$select='';
				$select.='<select name="penunjang[]" class="form-control select2" style="width: 100%;" required="required">';
					$select.='<option value="">Pilih Data</option>';
					foreach ($data_tunjangan as $kda => $vka) {
						$select.='<option value="'.$kda.'">'.$vka.'</option>';
					}
				$select.='</select>';
				$nominal='<div class="input-group">
					<input type="text" name="nominal_penunjang[]" class="input-money form-control" placeholder="Tetapkan Nominal" required="required" style="width: 100%;"></div>
					<script>
						$(document).ready(function(){
							$(".input-money").keyup(function () {
								this.value = formatRupiah(this.value, "Rp. ");
							});
							$(".input-money").focus(function (data) {
								if (this.value == "Rp. 0") {
									this.value = "";
								}
							});
							$(".input-money").focusout(function (data) {
								if (this.value == "") {
									this.value = "Rp. 0";
								} else if (this.value == "0") {
									this.value = "Rp. 0";
								}
							});
						})
					</script>';
				$datax=[
					'select'=>$select,
					'nominal'=>$nominal,
					'tabel_end_proses'=>$tabel_end_proses,
				];
        		echo json_encode($datax);
			}elseif ($usage == 'karyawan') {
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('tahun');
				// $koreksi = $this->input->post('koreksi');
				// $where = ['a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.koreksi'=>$koreksi];
				$where = ['a.bulan'=>$bulan,'a.tahun'=>$tahun];
				$karyawan = $this->model_payroll->getListDataPenggajianPph($where);
				$sel_karyawan = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($karyawan) as $bkey) {
					$koreksi =  (!empty($bkey->koreksi) && ($bkey->koreksi !=0))?' (Pembetulan '.$this->otherfunctions->getNumberToAbjad($bkey->koreksi).')':null;
					$sel_karyawan .= '<option value="'.$bkey->id_p_pph.'">'.$bkey->nama_karyawan.' - '.$bkey->nama_jabatan.$koreksi.'</option>';
				}
				$datax = ['karyawan'=>$sel_karyawan];
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}	
	public function cek_data_pph()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$dataPPh=$this->model_payroll->cekdatapph(['bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi]);
		if(empty($dataPPh)){
			$datax = ['msg'=>'true','bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi];
		}else{
			$datax=['msg'=>'ada_data','bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi];
		}
		echo json_encode($datax);
	}
	public function del_ada_data_pph()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$id_admin = $this->admin;
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$datax=$this->model_global->deleteQuery('data_penggajian_pph',['bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi]);
		// $datax=$this->model_global->deleteQuery('data_penggajian_pph',['create_by'=>$id_admin,'bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi]);
		echo json_encode($datax);
	}
	public function ready_data_pph()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		if($this->dtroot['adm']['level'] != 0){
			$en_access_idKar = $this->payroll->getKaryawanFromPetugasPPH($this->dtroot['adm']['id_karyawan']);
			$or_lv='';
			$c_lv=1;
			foreach ($en_access_idKar as $key => $idkar) {
				$or_lv.="a.id_karyawan='".$idkar."'";
				if (count($en_access_idKar) > $c_lv) {
					$or_lv.=' OR ';
				}
				$c_lv++;
			}
			$where = "a.bulan = '".$bulan."' AND a.tahun = '".$tahun."' and (".$or_lv.")";
		}else{
			$where = "a.bulan = '".$bulan."' AND a.tahun = '".$tahun."'";
		}
		// print_r($where);
		// $cekDataGaji = $this->model_payroll->cekDataPenggajianWhere(['bulan'=>$bulan,'tahun'=>$tahun]);
		// $cekDataGaji = $this->model_payroll->cekDataPenggajianWhere($where);
		// if(!empty($cekDataGaji)){
		// 	$data = $this->model_payroll->cekDataPenggajianWhere($where);
		// }else{
			$data = $this->model_payroll->getDataLogPayroll($where);
		// }
		if(!empty($data)){
			foreach ($data as $g) {
				$data_emp = $this->model_karyawan->getEmployeeId($g->id_karyawan);
				$gaji_pokok = (empty($g->gaji_pokok)) ? 0 : $g->gaji_pokok;
				$tunjangan_nominal = (empty($g->tunjangan)) ? 0 : $g->tunjangan;
				$upah = $gaji_pokok+$tunjangan_nominal;
				$bpjs_p_jht = $this->payroll->getBpjsBayarPerusahaan($g->gaji_bpjs_tk, 'JHT');
				$bpjs_p_jkk = $this->payroll->getBpjsBayarPerusahaan($g->gaji_bpjs_tk, 'JKK-RS');
				$bpjs_p_jkm = $this->payroll->getBpjsBayarPerusahaan($g->gaji_bpjs_tk, 'JKM');
				$bpjs_p_jpns = $this->payroll->getBpjsBayarPerusahaan($g->gaji_bpjs_tk, 'JPNS');
				$bpjs_p_jkes = $this->payroll->getBpjsBayarPerusahaan($g->gaji_bpjs_kes, 'JKES');
				$pengurang_lainx = 0;
				$penambah_lainx = 0;
				$pengurang_lainx_hallo = 0;
				$penambah_lainx_hallo = 0;
				if(!empty($g->data_lain)){
					// $dLain = $this->otherfunctions->getDataExplode($g->data_lain,';','all');
					// $nLain = $this->otherfunctions->getDataExplode($g->nominal_lain,';','all');
					// foreach ($dLain as $key => $value) {
					// 	if($value == 'pengurang'){
					// 		$pengurang_lain += $nLain[$key];
					// 	}else{
					// 		$penambah_lain += $nLain[$key];
					// 	}
					// }
					if (strpos($g->data_lain, ';') !== false) {
						$dLain = $this->otherfunctions->getDataExplode($g->data_lain,';','all');
						$dHallo = $this->otherfunctions->getDataExplode($g->data_lain_hallo,';','all');
						$nLain = $this->otherfunctions->getDataExplode($g->nominal_lain,';','all');
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
						if($g->data_lain == 'pengurang'){
							if($g->data_lain_hallo == '1'){
								$pengurang_lainx_hallo += $g->nominal_lain;
							}else{
								$pengurang_lainx += $g->nominal_lain;
							}
						}else{
							if($g->data_lain_hallo == '1'){
								$penambah_lainx_hallo += $g->nominal_lain;
							}else{
								$penambah_lainx += $g->nominal_lain;
							}
						}
					}
				}
				$pengurang_lain = $pengurang_lainx;
				$penambah_lain = $penambah_lainx;
				$pengurang_hallo = $pengurang_lainx_hallo;
				$penambah_hallo = $penambah_lainx_hallo;
				$nominal_perdin = $this->payroll->getPerjalananDinas($g->id_karyawan,['mulai'=>$g->tgl_mulai,'selesai'=>$g->tgl_selesai]);
				$nominal_lembur = $this->payroll->getLembur($g->id_karyawan,$bulan,$tahun);
				$nominal_kodeAkun = $this->payroll->getKodeAkunPPh($g->id_karyawan,$bulan,$tahun);
				$nominalPesangon = $this->payroll->getKodeAkunPesangon($g->id_karyawan,$bulan,$tahun);
				$nominalBonus = $this->payroll->getKodeAkunBonus($g->id_karyawan,$bulan,$tahun);
				$nominalTHR = $this->payroll->getKodeAkunTHR($g->id_karyawan,$bulan,$tahun);
				$pphPesangon = 0;
				if(!empty($nominalPesangon)){					
					$layer_pph = $this->payroll->getLayerPPHPesangon($nominalPesangon);
					$pphPesangon = $layer_pph['hasil'];
				}
				$yg_diterima = ($gaji_pokok+$tunjangan_nominal+$g->uang_makan+$g->ritasi+$penambah_lain+$penambah_hallo)-
					($g->bpjs_jht+$g->bpjs_jkk+$g->bpjs_jkm+$g->bpjs_pen+$g->bpjs_kes+
						($g->pot_tidak_masuk+$g->n_terlambat+$g->n_izin+$g->n_iskd+$g->n_imp)+
					$g->angsuran+$g->nominal_denda+$pengurang_lain+$pengurang_hallo);
				$bruto_bulan = ($gaji_pokok+$tunjangan_nominal+$g->uang_makan+$g->ritasi+$nominal_lembur+$nominal_perdin+$nominal_kodeAkun+$nominalBonus+$nominalTHR+$penambah_lain+$penambah_hallo)+$bpjs_p_jkk+$bpjs_p_jkm+$bpjs_p_jkes-$pengurang_lain;
				$bruto_tahun = $bruto_bulan*12;
				$biaya_jabatan = $this->payroll->getBiayaJabatan($bruto_bulan);
				$jumlah_pengurang = $biaya_jabatan['biaya_hasil']+$g->bpjs_jht+$g->bpjs_pen;
				$netto_bulan = $bruto_bulan-$jumlah_pengurang;
				$netto_tahun = $netto_bulan*12;
				$tarif_pajak = $this->payroll->getPajakPertahun($netto_tahun,$data_emp['status_pajak']);
				$tarif_pajak = ($tarif_pajak > 0)?$tarif_pajak:0;
				$n_ptkp = $this->payroll->getPTKP($data_emp['status_pajak']);
				$n_pkp = $netto_tahun-$n_ptkp;
				$n_pkp = ($n_pkp > 0)?$n_pkp:0;
				$layer_pph = $this->payroll->getLayerPPH($tarif_pajak,$data_emp['npwp']);
				$get_pph = $this->payroll->getPPHPertahun($layer_pph,$data_emp['npwp']);
				$data = [
					'kode_periode'            =>$g->kode_periode,
					'nama_periode'            =>$g->nama_periode,
					'tgl_mulai'               =>$g->tgl_mulai,
					'tgl_selesai'             =>$g->tgl_selesai,
					'bulan'   		          =>$g->bulan,
					'tahun'         		  =>$g->tahun,
					'koreksi'         		  =>$koreksi,
					'kode_master_penggajian'  =>$g->kode_master_penggajian,
					'nik'                     =>$g->nik,
					'nama_karyawan'           =>$g->nama_karyawan,
					'kode_jabatan'            =>$g->kode_jabatan,
					'kode_bagian'             =>$g->kode_bagian,
					'kode_loker'              =>$g->kode_loker,
					'kode_grade'              =>$g->kode_grade,
					'tgl_masuk'               =>$g->tgl_masuk,
					'masa_kerja'              =>$g->masa_kerja,
					'status_ptkp'             =>$data_emp['status_pajak'],
					'gaji_pokok'              =>$g->gaji_pokok,
					'tunjangan'               =>$tunjangan_nominal,
					'pot_tidak_masuk'         =>$g->pot_tidak_masuk,
					'pot_tidak_kerja'         =>($g->n_terlambat+$g->n_izin+$g->n_iskd+$g->n_imp),
					'bpjs_jkk_perusahaan'     =>$bpjs_p_jkk,
					'bpjs_jkm_perusahaan'     =>$bpjs_p_jkm,
					'bpjs_kes_perusahaan'     =>$bpjs_p_jkes,
					'bpjs_jht_perusahaan'     =>$bpjs_p_jht,
					'iuran_pensiun_perusahaan'=>$bpjs_p_jpns,
					'bpjs_jht_pekerja'        =>$g->bpjs_jht,
					'bpjs_jkk_pekerja'        =>$g->bpjs_jkk,
					'bpjs_jkm_pekerja'        =>$g->bpjs_jkm,
					'bpjs_pen_pekerja'        =>$g->bpjs_pen,
					'bpjs_kes_pekerja'        =>$g->bpjs_kes,
					'iuran_pensiun_pekerja'   =>$g->bpjs_pen,
					'tunjangan_val'           =>$g->tunjangan_val,
					'ritasi'                  =>$g->ritasi,
					'uang_makan'              =>$g->uang_makan,
					'hutang'                  =>$g->angsuran,
					'nominal_denda'           =>$g->nominal_denda,
					'data_lain'               =>$g->data_lain,
					'data_lain_hallo'         =>$g->data_lain_hallo,
					'nominal_lain'            =>$g->nominal_lain,
					'keterangan_lain'         =>$g->keterangan_lain,
					'perjalanan_dinas'        =>$nominal_perdin,
					'lembur'                  =>$nominal_lembur,
					'kode_akun'               =>$nominal_kodeAkun,
					'bonus'		              =>$nominalBonus,
					'thr'		              =>$nominalTHR,
					'pesangon'	              =>$nominalPesangon,
					'pph_pesangon'            =>$pphPesangon,
					'yg_diterima'			  =>$yg_diterima,
					'bruto_sebulan'           =>$bruto_bulan,
					'bruto_setahun'           =>$bruto_tahun,
					'biaya_jabatan'           =>$biaya_jabatan['biaya_hasil'],
					'jml_pengurang'           =>$jumlah_pengurang,
					'netto_sebulan'           =>$netto_bulan,
					'netto_setahun'           =>$netto_tahun,
					'pajak_setahun'           =>$tarif_pajak,
					'pph_setahun'             =>$get_pph['pph_tahun'],
					'no_npwp'                 =>$data_emp['npwp'],
					'pph_sebulan'             =>$get_pph['plus_npwp'],
					'ptkp'					  =>$n_ptkp,
					'pkp'					  =>$n_pkp,
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'data_penggajian_pph');
			}
			$dataNon = $this->model_karyawan->getKaryawanNonAktifWhere("YEAR(tgl_keluar)='$tahun'");
			if(!empty($dataNon)){
				foreach($dataNon as $nn){
					$masa_kerja = $this->otherfunctions->intervalTimeYear($nn->tgl_masuk);
					$nominal_kodeAkun = $this->payroll->getKodeAkunPPh($nn->id_karyawan,$bulan,$tahun);
					$nominalPesangon = $this->payroll->getKodeAkunPesangon($nn->id_karyawan,$bulan,$tahun);
					$nominalBonus = $this->payroll->getKodeAkunBonus($nn->id_karyawan,$bulan,$tahun);
					$nominalTHR = $this->payroll->getKodeAkunTHR($nn->id_karyawan,$bulan,$tahun);
					$pphPesangon = 0;
					if(!empty($nominalPesangon)){					
						$layer_pph = $this->payroll->getLayerPPHPesangon($nominalPesangon);
						$pphPesangon = $layer_pph['hasil'];
					}
					$yg_diterima = 0;
					$bruto_bulan = ($nominal_kodeAkun+$nominalBonus+$nominalTHR);
					$bruto_tahun = $bruto_bulan*12;
					$biaya_jabatan = 0;
					$jumlah_pengurang = 0;
					$netto_bulan = $bruto_bulan-$jumlah_pengurang;
					$netto_tahun = $netto_bulan*12;
					$tarif_pajak = $this->payroll->getPajakPertahun($netto_tahun,$nn->status_pajak);
					$tarif_pajak = ($tarif_pajak > 0)?$tarif_pajak:0;
					$n_ptkp = $this->payroll->getPTKP($nn->status_pajak);
					$n_pkp = $netto_tahun-$n_ptkp;
					$n_pkp = ($n_pkp > 0)?$n_pkp:0;
					$layer_pph = $this->payroll->getLayerPPH($tarif_pajak,$nn->npwp);
					$get_pph = $this->payroll->getPPHPertahun($layer_pph,$nn->npwp);
					$dataNonX = [
						'kode_periode'            =>null,
						'bulan'   		          =>$bulan,
						'tahun'         		  =>$tahun,
						'koreksi'         		  =>$koreksi,
						'nik'                     =>$nn->nik,
						'nama_karyawan'           =>$nn->nama_karyawan,
						'kode_jabatan'            =>$nn->kode_jabatan,
						'kode_bagian'             =>$nn->kode_bagian,
						'kode_loker'              =>$nn->kode_loker,
						'kode_grade'              =>$nn->kode_grade,
						'tgl_masuk'               =>$nn->tgl_masuk,
						'masa_kerja'              =>$masa_kerja,
						'status_ptkp'             =>$nn->status_pajak,
						'kode_akun'               =>$nominal_kodeAkun,
						'bonus'		              =>$nominalBonus,
						'thr'		              =>$nominalTHR,
						'pesangon'	              =>$nominalPesangon,
						'pph_pesangon'            =>$pphPesangon,
						'yg_diterima'			  =>$yg_diterima,
						'bruto_sebulan'           =>$bruto_bulan,
						'bruto_setahun'           =>$bruto_tahun,
						'jml_pengurang'           =>$jumlah_pengurang,
						'netto_sebulan'           =>$netto_bulan,
						'netto_setahun'           =>$netto_tahun,
						'pajak_setahun'           =>$tarif_pajak,
						'pph_setahun'             =>$get_pph['pph_tahun'],
						'no_npwp'                 =>$nn->npwp,
						'pph_sebulan'             =>$get_pph['plus_npwp'],
						'ptkp'					  =>$n_ptkp,
						'pkp'					  =>$n_pkp,
					];
					$dataNonX=array_merge($dataNonX,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQueryNoMsg($dataNonX,'data_penggajian_pph');
				}
			}
		}else{
			$datax = $this->messages->customFailure('Data Penggajian Pada Bulan dan Tahun tersebut Kosong');
		}
		echo json_encode($datax);
	}
	public function add_penunjang(){
		if (!$this->input->is_ajax_request()) 
		   redirect   ('not_found');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$karyawan = $this->input->post('karyawan');
		$penunjang = $this->input->post('penunjang');
		$nominal       = $this->input->post('nominal_penunjang');
		if(!empty($karyawan) && !empty($penunjang) && !empty($nominal)){
			foreach ($karyawan as $k_k => $idpph) {
				$dataPenunjang = [];
				$n_premi = 0;
				$n_pph = 0;
				foreach ($penunjang as $keyCode => $valCode) {
					$dataPenunjang[$valCode] = $nominal[$keyCode];
					if($valCode == 'premi_asuransi'){
						$n_premi += $nominal[$keyCode];
					}
					if($valCode == 'pph_tunjangan'){
						$n_pph += $nominal[$keyCode];
					}
				}
				$where = ['a.id_p_pph'=>$idpph,'a.bulan'=>$bulan,'a.tahun'=>$tahun];
				$e = $this->model_payroll->getListDataPenggajianPph($where,null,null,null,null,true);
				if(!empty($e)){
					$yg_diterima = $e['yg_diterima']+$n_premi+$n_pph;
					$bruto_bulan = $e['bruto_sebulan']+$n_premi+$n_pph;
					$bruto_tahun = $bruto_bulan*12;
					$biaya_jabatan = $this->payroll->getBiayaJabatan($bruto_bulan);
					$jumlah_pengurang = $biaya_jabatan['biaya_hasil']+$e['bpjs_jht_pekerja']+$e['bpjs_pen_pekerja'];
					$netto_bulan = $bruto_bulan-$jumlah_pengurang;
					$netto_tahun = $netto_bulan*12;
					$tarif_pajak = $this->payroll->getPajakPertahun($netto_tahun,$e['status_ptkp']);
					$n_ptkp = $this->payroll->getPTKP($e['status_ptkp']);
					$n_pkp = $netto_tahun-$n_ptkp;
					$layer_pph = $this->payroll->getLayerPPH($tarif_pajak,$e['no_npwp']);
					$get_pph = $this->payroll->getPPHPertahun($layer_pph,$e['no_npwp']);
					$data = [
						'premi_asuransi'		  =>$n_premi,
						'pph'		  			  =>$n_pph,
						'pph_dibayar'			  =>$n_pph,
						'pph_tunjangan'			  =>$n_pph,
						'yg_diterima'			  =>$yg_diterima,
						'bruto_sebulan'           =>$bruto_bulan,
						'bruto_setahun'           =>$bruto_tahun,
						'biaya_jabatan'           =>$biaya_jabatan['biaya_hasil'],
						'jml_pengurang'           =>$jumlah_pengurang,
						'netto_sebulan'           =>$netto_bulan,
						'netto_setahun'           =>$netto_tahun,
						'pajak_setahun'           =>$tarif_pajak,
						'pph_setahun'             =>$get_pph['pph_tahun'],
						'pph_sebulan'             =>$get_pph['plus_npwp'],
						'ptkp'					  =>$n_ptkp,
						'pkp'					  =>$n_pkp,
					];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_penggajian_pph',['id_p_pph'=>$idpph]);
				}
			}
		}else{
			$datax = $this->messages->customFailure('Data Kosong');
		}
		echo json_encode($datax);
	}
	
	//=========================================================== DATA PPH 21 HARIAN ================================================//
	//--Data PPH 21 Harian--//
	public function data_pph_21_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('tahun');
				$koreksi = $this->input->post('koreksi');
				if(!empty($bulan)){ $form_filter['a.bulan'] = $bulan; }
				if(!empty($tahun)){ $form_filter['a.tahun'] = $tahun; }
				// if(!empty($koreksi)){ $form_filter['a.koreksi'] = $koreksi; }
				$form_filter['a.koreksi'] = null;
				if(isset($koreksi) || !empty($koreksi)){
					$form_filter['a.koreksi'] = $koreksi;
				}
				$data = $this->model_payroll->getListDataPenggajianPphHarian($form_filter);
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
					$body_1 = [
						$d->id_p_pph,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_bagian,
						$this->formatter->getFormatMoneyUserReq($d->gaji_1),
						$this->formatter->getFormatMoneyUserReq($d->gaji_2),
						$this->formatter->getFormatMoneyUserReq($d->gaji_3),
						$this->formatter->getFormatMoneyUserReq($d->gaji_4),
						$this->formatter->getFormatMoneyUserReq($d->gaji_5),
						$this->formatter->getFormatMoneyUserReq($d->lembur_1),
						$this->formatter->getFormatMoneyUserReq($d->lembur_2),
						$this->formatter->getFormatMoneyUserReq($d->lembur_3),
						$this->formatter->getFormatMoneyUserReq($d->lembur_4),
						$this->formatter->getFormatMoneyUserReq($d->lembur_5),
					];
					$body_2 = [
						$this->formatter->getFormatMoneyUserReq($d->kode_akun),
						$this->formatter->getFormatMoneyUserReq($d->thr),
						$this->formatter->getFormatMoneyUserReq($d->gaji_diterima),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jkk_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jkm_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->pph_tunjangan),
						$this->formatter->getFormatMoneyUserReq($d->premi_asuransi),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jht_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_pen_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_kes_perusahaan),
						$this->formatter->getFormatMoneyUserReq($d->penambah_lain),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_kes_pekerja),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_pen_pekerja),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jht_pekerja),
						$this->formatter->getFormatMoneyUserReq($d->pengurang_lain),
					];
					$body_3 = [
						$this->formatter->getFormatMoneyUserReq($d->bruto_sebulan),
						$this->formatter->getFormatMoneyUserReq($d->pesangon),
						$this->formatter->getFormatMoneyUserReq($d->netto_sebulan),
						$this->formatter->getFormatMoneyUserReq($d->status_pajak),
						$this->formatter->getFormatMoneyUserReq($d->presensi),
						$this->formatter->getFormatMoneyUserReq($d->ptkp),
					];
					$body_4 = [
						$this->formatter->getFormatMoneyUserReq($d->pkp),
						$this->formatter->getFormatMoneyUserReq($d->pph_sebulan),
						0,
						0,
						$properties['tanggal'],
						$properties['aksi']
					];
					$datax['data'][]=array_merge($body_1,$body_2,$body_3,$body_4);
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_p_pph');
				$data = $this->model_payroll->getListDataPenggajianPphHarian(['a.id_p_pph'=>$id]);
				$datax=[];
				foreach ($data as $d) {
					// $bulan_prd = $this->formatter->getNameOfMonth(date('m', strtotime($d->tgl_selesai_periode)));
					// $tahun_prd = date('Y', strtotime($d->tgl_selesai_periode));
					// $pajak_setahun = ($d->pajak_setahun < 0) ? '( '.$this->formatter->getFormatMoneyUser(abs($d->pajak_setahun)).' )' : $this->formatter->getFormatMoneyUser($d->pajak_setahun);
					$pengurang_lain = 0;
					$penambah_lain = 0;
					if(!empty($d->data_lain)){
						$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
						$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
						foreach ($dLain as $key => $value) {
							if($value == 'pengurang'){
								$pengurang_lain += $nLain[$key];
							}else{
								$penambah_lain += $nLain[$key];
							}
						}
					}
					$potonganTidakKerja = 0;//($d->pot_tidak_kerja+$d->pot_tidak_masuk);
					$datax=[
						'id_p_pph'=>$d->id_p_pph,
						// 'kode_periode'=>$d->kode_periode,
						// 'nama_periode'=>$d->nama_periode,
						// 'tgl_mulai'=>$this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						// 'tgl_selesai'=>$this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						'nik'=>$d->nik,
						'nama_karyawan'=>$d->nama_karyawan,
						'kode_jabatan'=>$d->nama_jabatan,
						'kode_bagian'=>$d->nama_bagian,
						'kode_loker'=>$d->nama_loker,
						'kode_grade'=>$d->nama_grade,
						// 'nama_sistem_penggajian'=>$d->nama_sistem_penggajian,
						'bulan'=>$this->formatter->getNameOfMonth($d->bulan),
						'tahun'=>$d->tahun,
						// 'bulan_prd'=>$bulan_prd,
						// 'tahun_prd'=>$tahun_prd,
						// 'tgl_masuk'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						// 'masa_kerja'=>$d->masa_kerja,
						// 'status_ptkp'=>$d->status_ptkp,
						'status_ptkp'=>$d->status_pajak,
						'gaji_pokok'=>$this->formatter->getFormatMoneyUser($d->gaji_per_hari),
						// 'tunjangan'=>$this->formatter->getFormatMoneyUser($d->tunjangan),
						'bpjs_jkk_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_jkk_perusahaan),
						'bpjs_jkm_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_jkm_perusahaan),
						'bpjs_kes_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_kes_perusahaan),
						'bpjs_jht_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_jht_perusahaan),
						'iuran_pensiun_perusahaan'=>$this->formatter->getFormatMoneyUser($d->bpjs_pen_perusahaan),
						// 'uang_makan'=>$this->formatter->getFormatMoneyUser($d->uang_makan),
						// 'ritasi'=>$this->formatter->getFormatMoneyUser($d->ritasi),
						// 'lembur'=>$this->formatter->getFormatMoneyUser($d->lembur),
						// 'perdin'=>$this->formatter->getFormatMoneyUser($d->perjalanan_dinas),
						'kode_akun'=>$this->formatter->getFormatMoneyUser($d->kode_akun),
						'bpjs_pph'=>$this->formatter->getFormatMoneyUser($d->pph_sebulan),
						'premi_asuransi'=>$this->formatter->getFormatMoneyUser($d->premi_asuransi),
						'bpjs_jht_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_jht_pekerja),
						'iuran_pensiun_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_pen_pekerja),
						'bpjs_jkk_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_jkk_pekerja),
						'bpjs_jkm_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_jkm_pekerja),
						'bpjs_kes_pekerja'=>$this->formatter->getFormatMoneyUser($d->bpjs_kes_pekerja),
						// 'piutang'=>$this->formatter->getFormatMoneyUser($d->hutang),
						// 'koreksi_absen'=>$this->formatter->getFormatMoneyUser($potonganTidakKerja),
						// 'denda'=>$this->formatter->getFormatMoneyUser($d->nominal_denda),
						'tambah_lainnya'=>$this->formatter->getFormatMoneyUser($d->penambah_lain),
						'potongan_lain'=>$this->formatter->getFormatMoneyUser($d->pengurang_lain),
						// 'jml_pengurang'=>$this->formatter->getFormatMoneyUser($d->jml_pengurang),
						'netto_sebulan'=>$this->formatter->getFormatMoneyUser($d->netto_sebulan),
						'netto_setahun'=>$this->formatter->getFormatMoneyUser($d->netto_sebulan*12),
						// 'pajak_setahun'=>$pajak_setahun,
						// 'pph_setahun'=>$this->formatter->getFormatMoneyUser($d->pph_setahun),
						'no_npwp'=>$d->npwp,
						'pph_sebulan'=>$this->formatter->getFormatMoneyUser($d->pph_sebulan),
						'yg_diterima'=>$this->formatter->getFormatMoneyUser($d->gaji_diterima),
						'gaji_lembur'=>$this->formatter->getFormatMoneyUser($d->gaji_lembur),
						'bruto_sebulan'=>$this->formatter->getFormatMoneyUser($d->bruto_sebulan),
						'bruto_setahun'=>$this->formatter->getFormatMoneyUser($d->bruto_sebulan*12),
						// 'biaya_jabatan'=>$this->formatter->getFormatMoneyUser($d->biaya_jabatan),
						'netto_setahun'=>$this->formatter->getFormatMoneyUser($d->netto_sebulan*12),
						'ptkp_sebulan'=>$this->formatter->getFormatMoneyUser(($d->ptkp/12)),
						'ptkp_setahun'=>$this->formatter->getFormatMoneyUser($d->ptkp),
						'pkp_sebulan'=>$this->formatter->getFormatMoneyUser(($d->pkp/12)),
						'pkp_setahun'=>$this->formatter->getFormatMoneyUser($d->pkp),
						'pph21sebulan'=>$this->formatter->getFormatMoneyUser($d->pph_sebulan),
						'pph21setahun'=>$this->formatter->getFormatMoneyUser($d->pph_sebulan*12),
						// 'pajak_setahun'=>$this->formatter->getFormatMoneyUser($d->pajak_setahun),
						'koreksi'=>$this->otherfunctions->getNumberToAbjad($d->koreksi),
						// 'pph21_dibayar'=>$this->formatter->getFormatMoneyUser($d->pph_dibayar),
						// 'pph21_dipotong'=>$this->formatter->getFormatMoneyUser($d->pph_dipotong),
						// 'tunjangan_pph'=>$this->formatter->getFormatMoneyUser($d->pph_tunjangan),
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
			}elseif ($usage == 'insert_penunjang') {
				$tabel_end_proses='<table class="table table-bordered table-striped data-table" id="myTable">
								<thead>
									<tr class="bg-blue">
										<th>Penunjang</th>
										<th>Nominal</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>';
				$data_tunjangan=$this->otherfunctions->getPenunjangList();
				$select='';
				$select.='<select name="penunjang[]" class="form-control select2" style="width: 100%;" required="required">';
					$select.='<option value="">Pilih Data</option>';
					foreach ($data_tunjangan as $kda => $vka) {
						$select.='<option value="'.$kda.'">'.$vka.'</option>';
					}
				$select.='</select>';
				$nominal='<div class="input-group">
					<input type="text" name="nominal_penunjang[]" class="input-money form-control" placeholder="Tetapkan Nominal" required="required" style="width: 100%;"></div>
					<script>
						$(document).ready(function(){
							$(".input-money").keyup(function () {
								this.value = formatRupiah(this.value, "Rp. ");
							});
							$(".input-money").focus(function (data) {
								if (this.value == "Rp. 0") {
									this.value = "";
								}
							});
							$(".input-money").focusout(function (data) {
								if (this.value == "") {
									this.value = "Rp. 0";
								} else if (this.value == "0") {
									this.value = "Rp. 0";
								}
							});
						})
					</script>';
				$datax=[
					'select'=>$select,
					'nominal'=>$nominal,
					'tabel_end_proses'=>$tabel_end_proses,
				];
        		echo json_encode($datax);
			}elseif ($usage == 'karyawan') {
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('tahun');
				$where = ['a.bulan'=>$bulan,'a.tahun'=>$tahun];
				$karyawan = $this->model_payroll->getListDataPenggajianPphHarian($where);
				$sel_karyawan = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($karyawan) as $bkey) {
					$koreksi =  (!empty($bkey->koreksi) && ($bkey->koreksi !=0))?' (Pembetulan '.$this->otherfunctions->getNumberToAbjad($bkey->koreksi).')':null;
					$sel_karyawan .= '<option value="'.$bkey->id_p_pph.'">'.$bkey->nama_karyawan.' - '.$bkey->nama_jabatan.$koreksi.'</option>';
				}
				$datax = ['karyawan'=>$sel_karyawan];
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function ready_data_pph_harian()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		if($this->dtroot['adm']['level'] != 0){
			$en_access_idKar = $this->payroll->getKaryawanFromPetugasPPH($this->dtroot['adm']['id_karyawan']);
			$or_lv='';
			$c_lv=1;
			foreach ($en_access_idKar as $key => $idkar) {
				$or_lv.="a.id_karyawan='".$idkar."'";
				if (count($en_access_idKar) > $c_lv) {
					$or_lv.=' OR ';
				}
				$c_lv++;
			}
			$where = "a.bulan = '".$bulan."' AND a.tahun = '".$tahun."' and (".$or_lv.")";
		}else{
			$where = "a.bulan = '".$bulan."' AND a.tahun = '".$tahun."'";
		}
		// $cekDataGaji = $this->model_payroll->getDataPayrollHarian($where);
		// if(!empty($cekDataGaji)){
		// 	$data = $this->model_payroll->getDataPayrollHarian($where);
		// }else{
			$data = $this->model_payroll->getDataLogPayroll($where);
		// }
		if(!empty($data)){
			$emp = [];
			foreach ($data as $d) {
				$pengurang_lainx = 0;
				$penambah_lainx = 0;
				$pengurang_lainx_hallo = 0;
				$penambah_lainx_hallo = 0;
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
				$emp[$d->id_karyawan][$d->minggu] = [
					'nik'=>$d->nik,
					'id_karyawan'=>$d->id_karyawan,
					'nama_karyawan'=>$d->nama_karyawan,
					'kode_jabatan'=>$d->kode_jabatan,
					'kode_bagian'=>$d->kode_bagian,
					'kode_loker'=>$d->kode_loker,
					'nama_jabatan'=>$d->nama_jabatan,
					'nama_bagian'=>$d->nama_bagian,
					'nama_loker'=>$d->nama_loker,
					'status_pajak'=>$d->status_pajak,
					'no_ktp'=>$d->no_ktp,
					'npwp'=>$d->npwp,
					'gaji_pokok'=>$d->gaji_pokok,
					'gaji_diterima'=>$d->gaji_diterima,
					'gaji_lembur'=>$d->gaji_lembur,
					'jkk'=>$d->jkk,
					'jkm'=>$d->jkm,
					'jpen'=>$d->jpen,
					'jht'=>$d->jht,
					'jkes'=>$d->jkes,
					'presensi'=>$d->presensi,
					'minggu'=>$d->minggu,
					'pengurang_lain'=>$pengurang_lain,
					'penambah_lain'=>$penambah_lain,
					'pengurang_hallo'=>$pengurang_hallo,
					'penambah_hallo'=>$penambah_hallo,
					'data_lain'=>$d->data_lain,
					'data_lain_nama'=>$d->keterangan_lain,
					'data_lain_hallo'=>$d->data_lain_hallo,
					'nominal_lain'=>$d->nominal_lain,
				];
			}
			// echo '<pre>';
			// print_r($emp);
			foreach($emp as $idkar => $minggu){
				$karyawan = [];
				$gaji_diterima = 0;
				$gaji_lembur = 0;
				$jkk = 0;
				$jkm = 0;
				$pph = 0;
				$premi = 0;
				$jht = 0;
				$bpjskes = 0;
				$jaminanPensiun = 0;
				$iuranPensiun = 0;
				$potonganJHT = 0;
				$presensi = 0;
				$pengurang_lain = 0;
				$penambah_lain = 0;
				$pengurang_hallo = 0;
				$penambah_hallo = 0;
				for ($i=1; $i < 6; $i++) {
					if(isset($minggu[$i]['minggu'])){
						if($minggu[$i]['minggu'] == $i){
							$id_karyawan=$minggu[$i]['id_karyawan'];
							$nik=$minggu[$i]['nik'];
							$nama_karyawan=$minggu[$i]['nama_karyawan'];
							$kode_jabatan=$minggu[$i]['kode_jabatan'];
							$kode_bagian=$minggu[$i]['kode_bagian'];
							$kode_loker=$minggu[$i]['kode_loker'];
							$nama_jabatan=$minggu[$i]['nama_jabatan'];
							$nama_bagian=$minggu[$i]['nama_bagian'];
							$nama_loker=$minggu[$i]['nama_loker'];
							$status_pajak=$minggu[$i]['status_pajak'];
							$no_ktp=$minggu[$i]['no_ktp'];
							$npwp=$minggu[$i]['npwp'];
							$gaji_per_hari=$minggu[$i]['gaji_pokok'];
							$gaji_diterima+=($minggu[$i]['gaji_diterima'] > 0)?$minggu[$i]['gaji_diterima']:0;
							$gaji_lembur+=$minggu[$i]['gaji_lembur'];
							$jkk += $minggu[$i]['jkk'];
							$jkm += $minggu[$i]['jkm'];
							$jht += 0;//$minggu[$i]['jht'];
							$jaminanPensiun += 0;//$minggu[$i]['jpen'];
							$iuranPensiun += $minggu[$i]['jpen'];
							$potonganJHT += $minggu[$i]['jht'];
							$bpjskes += $minggu[$i]['jkes'];
							$presensi += $minggu[$i]['presensi'];
							$pengurang_lain += $minggu[$i]['pengurang_lain'];
							$penambah_lain += $minggu[$i]['penambah_lain'];
							$pengurang_hallo += $minggu[$i]['pengurang_hallo'];
							$penambah_hallo += $minggu[$i]['penambah_hallo'];
							$data_lain=$minggu[$i]['data_lain'];
							$data_lain_nama=$minggu[$i]['data_lain_nama'];
							$data_lain_hallo=$minggu[$i]['data_lain_hallo'];
							$nominal_lain=$minggu[$i]['nominal_lain'];
						}
					}
				}
				$employee = $this->model_karyawan->getEmpID($id_karyawan);
				$gaji_minggu1 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>1,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
				$gajiminggu1 = ($gaji_minggu1['gaji_diterima'] > 0) ? $gaji_minggu1['gaji_diterima'] : 0;
				$lemburminggu1 = $gaji_minggu1['gaji_lembur'];
				$gaji_minggu2 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>2,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
				$gajiminggu2 = ($gaji_minggu2['gaji_diterima'] > 0) ? $gaji_minggu2['gaji_diterima'] : 0;
				$lemburminggu2 = $gaji_minggu2['gaji_lembur'];
				$gaji_minggu3 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>3,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
				$gajiminggu3 = ($gaji_minggu3['gaji_diterima'] > 0) ? $gaji_minggu3['gaji_diterima'] : 0;
				$lemburminggu3 = $gaji_minggu3['gaji_lembur'];
				$gaji_minggu4 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>4,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
				$gajiminggu4 = ($gaji_minggu4['gaji_diterima'] > 0) ? $gaji_minggu4['gaji_diterima'] : 0;
				$lemburminggu4 = $gaji_minggu4['gaji_lembur'];
				$gaji_minggu5 = $this->model_payroll->getPenggajianHarianRow(['minggu'=>5,'bulan'=>$bulan,'tahun'=>$tahun,'id_karyawan'=>$id_karyawan]);
				$gajiminggu5 = ($gaji_minggu5['gaji_diterima'] > 0) ? $gaji_minggu5['gaji_diterima'] : 0;
				$lemburminggu5 = $gaji_minggu5['gaji_lembur'];
				$nominal_kodeAkun = $this->payroll->getKodeAkunPPh($id_karyawan,$bulan,$tahun);
				$nominalPesangon = $this->payroll->getKodeAkunPesangon($id_karyawan,$bulan,$tahun);
				$nominalTHR = $this->payroll->getKodeAkunTHR($id_karyawan,$bulan,$tahun);
				$jumlah_penerimaan = $gaji_diterima+$gaji_lembur+$nominal_kodeAkun+$nominalTHR;
				$bruto = $jumlah_penerimaan+$jkk+$jkm;
				$netto = $bruto-($iuranPensiun+$potonganJHT+$bpjskes);
				// $netto_tahun = $netto*12;
				// $tarif = $this->payroll->getPajakPertahunHarian($netto,'K/2','26');
				// $n_pkp = ($tarif['pkp'] > 0)?$tarif['pkp']:0;
				// $n_ptkp = $tarif['ptkp'];
				// $layer_pph = $this->payroll->getLayerPPHHarian($tarif['pkp'],null);
				$netto_tahun = $netto*12;
				$tarif = $this->payroll->getPajakPertahunHarian($netto,$employee['status_pajak'],$presensi);
				$n_ptkp = ($tarif['ptkp'] > 0)?$tarif['ptkp']:0;
				$n_pkp = ($tarif['pkp'] > 0)?$tarif['pkp']:0;
				$layer_pph = $this->payroll->getLayerPPHHarian($n_pkp,$npwp);
				$get_pph = $this->payroll->getPPHPertahun($layer_pph,$npwp);
				// echo '<pre>';
				// print_r($tarif);
				// print_r($layer_pph);
				$pph_sebulan = $get_pph['pph_bulan'];
				$karyawan = [
					'bulan'=>$bulan,
					'tahun'=>$tahun,
					'id_karyawan'=>$id_karyawan,
					'koreksi'=>$koreksi,
					'nik'=>$nik,
					'nama_karyawan'=>$nama_karyawan,
					'kode_jabatan'=>$kode_jabatan,
					'kode_bagian'=>$kode_bagian,
					'kode_loker'=>$kode_loker,
					'nama_jabatan'=>$nama_jabatan,
					'nama_bagian'=>$nama_bagian,
					'nama_loker'=>$nama_loker,
					'status_pajak'=>$employee['status_pajak'],
					'no_ktp'=>$employee['no_ktp'],
					'npwp'=>$employee['npwp'],
					'gaji_per_hari'=>$gaji_per_hari,
					'gaji_1'=>$gajiminggu1,
					'gaji_2'=>$gajiminggu2,
					'gaji_3'=>$gajiminggu3,
					'gaji_4'=>$gajiminggu4,
					'gaji_5'=>$gajiminggu5,
					'lembur_1'=>$lemburminggu1,
					'lembur_2'=>$lemburminggu2,
					'lembur_3'=>$lemburminggu3,
					'lembur_4'=>$lemburminggu4,
					'lembur_5'=>$lemburminggu5,
					'gaji_diterima'=>$gaji_diterima,
					'gaji_lembur'=>$gaji_lembur,
					'bpjs_jkk_perusahaan'=>$jkk,
					'bpjs_jkm_perusahaan'=>$jkm,
					'bpjs_kes_perusahaan'=>0,
					'bpjs_jht_perusahaan'=>$jht,
					'bpjs_pen_perusahaan'=>$jaminanPensiun,
					'bpjs_pen_pekerja'=>$iuranPensiun,
					'bpjs_jht_pekerja'=>$potonganJHT,
					'bpjs_kes_pekerja'=>$bpjskes,
					'bpjs_jkm_pekerja'=>0,
					'bpjs_jkk_pekerja'=>0,
					'presensi'=>$presensi,
					'pengurang_lain'=>$pengurang_lain,
					'penambah_lain'=>$penambah_lain,
					'pengurang_hallo'=>$pengurang_hallo,
					'penambah_hallo'=>$penambah_hallo,
					'data_lain'=>$data_lain,
					'data_lain_nama'=>$data_lain_nama,
					'data_lain_hallo'=>$data_lain_hallo,
					'nominal_lain'=>$nominal_lain,
					'kode_akun'=>$nominal_kodeAkun,
					'pesangon'=>$nominalPesangon,
					'thr'=>$nominalTHR,
					'jumlah_penerimaan'=>$jumlah_penerimaan,
					'bruto_sebulan'=>$bruto,
					'netto_sebulan'=>$netto,
					'ptkp'=>$n_ptkp,
					'pkp'=>$n_pkp,
					'pph_sebulan'=>$pph_sebulan,
				];
				$data=array_merge($karyawan,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'data_pph_harian');
			}
		}else{
			$datax = $this->messages->customFailure('Data Penggajian Pada Bulan dan Tahun tersebut Kosong');
		}
		echo json_encode($datax);
	}
	public function cek_data_pph_harian()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$dataPPh=$this->model_payroll->cekdatapphHarian(['bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi]);
		if(empty($dataPPh)){
			$datax = ['msg'=>'true','bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi];
		}else{
			$datax=['msg'=>'ada_data','bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi];
		}
		echo json_encode($datax);
	}
	public function del_ada_data_pph_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$id_admin = $this->admin;
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$datax=$this->model_global->deleteQuery('data_pph_harian',['bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi]);
		echo json_encode($datax);
	}
	public function add_penunjang_harian(){
		if (!$this->input->is_ajax_request()) 
		   redirect   ('not_found');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$karyawan = $this->input->post('karyawan');
		$penunjang = $this->input->post('penunjang');
		$nominal       = $this->input->post('nominal_penunjang');
		if(!empty($karyawan) && !empty($penunjang) && !empty($nominal)){
			foreach ($karyawan as $k_k => $idpph) {
				$dataPenunjang = [];
				$n_premi = 0;
				$n_pph = 0;
				foreach ($penunjang as $keyCode => $valCode) {
					$dataPenunjang[$valCode] = $nominal[$keyCode];
					if($valCode == 'premi_asuransi'){
						$n_premi += $nominal[$keyCode];
					}
					if($valCode == 'pph_tunjangan'){
						$n_pph += $nominal[$keyCode];
					}
				}
				$where = ['a.id_p_pph'=>$idpph,'a.bulan'=>$bulan,'a.tahun'=>$tahun];
				$e = $this->model_payroll->getListDataPenggajianPphHarian($where,null,null,null,null,true);
				if(!empty($e)){
					$yg_diterima = $e['jumlah_penerimaan']+$n_premi+$n_pph;
					$bruto_bulan = $e['bruto_sebulan']+$n_premi+$n_pph;
					$bruto_tahun = $bruto_bulan*12;
					$jumlah_pengurang = $e['bpjs_pen_pekerja']+$e['bpjs_jht_pekerja']+$e['bpjs_kes_pekerja'];
					$netto_bulan = $bruto_bulan-$jumlah_pengurang;
					$netto_tahun = $netto_bulan*12;
					$tarif = $this->payroll->getPajakPertahunHarian($netto_bulan,$e['status_pajak'],$e['presensi']);
					$n_ptkp = ($tarif['ptkp'] > 0)?$tarif['ptkp']:0;
					$n_pkp = ($tarif['pkp'] > 0)?$tarif['pkp']:0;
					$layer_pph = $this->payroll->getLayerPPHHarian($n_pkp,$e['npwp']);
					$get_pph = $this->payroll->getPPHPertahun($layer_pph,$e['npwp']);
					$data = [
						'premi_asuransi'		  =>$n_premi,
						'pph_sebulan'  			  =>$n_pph,
						'pph_dibayar'			  =>$n_pph,
						'pph_tunjangan'			  =>$n_pph,
						'jumlah_penerimaan'		  =>$yg_diterima,
						'bruto_sebulan'           =>$bruto_bulan,
						'netto_sebulan'           =>$netto_bulan,
						'pph_sebulan'			  =>$get_pph['pph_bulan'],
						'ptkp'					  =>$n_ptkp,
						'pkp'					  =>$n_pkp,
					];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_pph_harian',['id_p_pph'=>$idpph]);
				}else{
					$datax = $this->messages->customFailure('Data Kosong');
				}
			}
		}else{
			$datax = $this->messages->customFailure('Data Kosong');
		}
		echo json_encode($datax);
	}
	//=================================================== DATA PPH 21 NON KARYAWAN  ====================================================//
	//--Data PPH 21 non--//
	public function cek_data_pph_non()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$dataPPh=$this->model_payroll->cekdatapphnon(['bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi]);
		if(empty($dataPPh)){
			$datax = ['msg'=>'true','bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi];
		}else{
			$datax=['msg'=>'ada_data','bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi];
		}
		echo json_encode($datax);
	}
	public function del_ada_data_pph_non()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		// $id_admin = $this->admin;
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$datax=$this->model_global->deleteQuery('data_penggajian_pph21_non',['bulan'=>$bulan,'tahun'=>$tahun]);
		echo json_encode($datax);
	}
	public function ready_data_pph_non()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$usage = $this->input->post('usage');
		$koreksi = $this->input->post('koreksi');
		if ($bulan && $tahun){
			$this->model_global->deleteQueryNoMsg('data_penggajian_pph21_non',['bulan'=>"$bulan",'tahun'=>$tahun]);
			$cekDataTransaksi = $this->model_karyawan->getListTransaksiNonKaryawanX(['MONTH(a.tanggal)'=>$bulan,'YEAR(a.tanggal)'=>$tahun]);
			if(!empty($cekDataTransaksi)){
				$datanon = [];
				foreach ($cekDataTransaksi as $d) {
					$datanon[$d->id_non][] = [
						'id_non'         =>$d->id_non,
						'nomor'          =>$d->nomor,
						'tanggal'        =>$d->tanggal,
						'kegiatan'       =>$d->kegiatan,
						'biaya'          =>$d->biaya,
						'thr'            =>$d->thr,
						'keterangan'     =>$d->keterangan,
						'nik'     		 =>$d->nik,
						'jenis'     	 =>$d->jenis,
						'jenis_pajak'    =>$d->jenis_pajak,
						'status_pajak'   =>$d->status_pajak,
						'perhitungan_pajak'=>$d->perhitungan_pajak,
						'npwp'    		 =>$d->npwp,
						'mengetahui'     =>$d->mengetahui,
						'menyetujui'     =>$d->menyetujui,
						'status'         =>$d->status,
						'create_by'      =>$d->create_by,
						'update_by'      =>$d->update_by,
						'create_date'    =>$d->create_date,
						'update_date'    =>$d->update_date,
						'nama_buat'      =>$d->nama_buat,
						'nama_update'    =>$d->nama_update,
						'nik'            =>$d->nik,
						'nama_non'       =>$d->nama_non,
						'no_telp'        =>$d->no_telp,
						'alamat'         =>$d->alamat,
						'non_keterangan' =>$d->non_keterangan,
						'nama_mengetahui'=>$d->nama_mengetahui,
						'jbt_mengetahui' =>$d->jbt_mengetahui,
						'nama_menyetujui'=>$d->nama_menyetujui,
						'jbt_menyetujui' =>$d->jbt_menyetujui,
					];
				}
				foreach ($datanon as $ke =>$val) {
					$biaya = 0;
					$thr = 0;
					foreach ($val as $e) {
						$biaya += $e['biaya'];
						$thr += $e['thr'];
						$nama = $e['nama_non'];
						$nik = $e['nik'];
						$alamat = $e['alamat'];
						$no_telp = $e['no_telp'];
						$jenis = $e['jenis'];
						$jenis_pajak = $e['jenis_pajak'];
						$status_pajak = $e['status_pajak'];
						$perhitungan_pajak = $e['perhitungan_pajak'];
						$npwp = $e['npwp'];
					}
					$bruto_bulan = ($biaya+$thr);
					$bruto_tahun = $bruto_bulan*12;
					$netto_bulan = $bruto_bulan;
					$netto_tahun = $netto_bulan*12;
					if($perhitungan_pajak == 'NON_PTKP'){
						$tarif_pajak = 0;//$this->payroll->getPajakPertahun($bruto_tahun,$status_pajak);
						$n_ptkp 	 = $bruto_bulan;
						$n_pkp 		 = (50/100)*$bruto_bulan;
						$layer_pph   = $this->payroll->getLayerPPHNonKaryawan($n_pkp,$npwp);
						$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$npwp);
						$pph_tahun   = $get_pph['pph_tahun'];
						$pph_bulan   = $get_pph['pph_bulan'];
						$plus_npwp   = $get_pph['plus_npwp'];
						// $pajak       = $get_pph['pph_bulan'];
						$pajak       = $get_pph['pph_tahun'];
					}else{
						$n_ptkp 	 = $this->payroll->getPTKP($status_pajak);
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
							$layer_pph   = $this->payroll->getLayerPPHNonKaryawan($tarif_pajak,$npwp);
							$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$npwp);
							$pph_tahun   = $get_pph['pph_tahun'];
							$pph_bulan   = $get_pph['pph_bulan'];
							$plus_npwp   = $get_pph['plus_npwp'];
							$pajak       = $get_pph['pph_bulan'];
						}
					}
					$data = [
						'bulan'         =>$bulan,
						'tahun'         =>$tahun,
						'koreksi'     	=>$koreksi,
						'nik'           =>$nik,
						'nama'          =>$nama,
						'alamat'        =>$alamat,
						'no_telp'       =>$no_telp,
						'jenis'         =>$jenis,
						'jenis_pajak'   =>$jenis_pajak,
						'besar_pajak'	=>$pajak,
						'status_pajak'  =>$status_pajak,
						'perhitungan_pajak'=>$perhitungan_pajak,
						'biaya'         =>$biaya,
						'thr'           =>0,//$thr,
						'npwp'          =>$npwp,
						'ptkp'          =>$n_ptkp,
						'pkp'           =>$n_pkp,
						'bruto_sebulan' =>$bruto_bulan,
						'bruto_setahun' =>$bruto_tahun,
						'netto_sebulan' =>$netto_bulan,
						'netto_setahun' =>$netto_tahun,
						'pajak_setahun' =>$tarif_pajak,
						'pph_setahun'   =>$pph_tahun,
						'pph_sebulan'   =>$pph_bulan,
						'pph_npwp'      =>$plus_npwp,
						'tunjangan_pph' =>0,
					];
					if($usage == 'insert'){
						$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
						$datax = $this->model_global->insertQuery($data,'data_penggajian_pph21_non');
					}else{
						$dataPPh=$this->model_payroll->cekdatapphnon(['bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi,'nik'=>$data['nik']]);
						if(empty($dataPPh)){
							$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($data,'data_penggajian_pph21_non');
						}else{
							$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($data,'data_penggajian_pph21_non',['nik'=>$nik,'bulan'=>$bulan,'tahun'=>$tahun,'koreksi'=>$koreksi]);
						}
					}
				}
			}else{
				$datax = $this->messages->customFailure('Data Transaksi PPh 21 Non Karyawan Pada Bulan dan Tahun tersebut Kosong');
			}
		}else{
			$datax = $this->messages->customFailure('Bulan dan Tahun harus diisi!');
		}
		echo json_encode($datax);
	}
	public function data_pph_21_non()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('tahun');
				$koreksi = $this->input->post('koreksi');
				// $form_filter['a.create_by'] = $id_admin;
				if(!empty($bulan)){ $form_filter['a.bulan'] = $bulan; }
				if(!empty($tahun)){ $form_filter['a.tahun'] = $tahun; }
				if(!empty($koreksi)){ $form_filter['a.koreksi'] = $koreksi; }else{$form_filter['a.koreksi'] = 0;}
				// print_r($form_filter);
				$data = $this->model_payroll->getListDataPenggajianPphNon($form_filter);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pph21,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					// unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_pph21,
						$d->nik,
						$d->nama,
						$d->jenis,
						$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						$this->otherfunctions->getNumberToAbjad($d->koreksi),
						$d->status_pajak,
						$d->npwp,
						$this->otherfunctions->getJenisPerhitunganPajakKey($d->perhitungan_pajak),
						$this->formatter->getFormatMoneyUserReq($d->biaya),
						$this->formatter->getFormatMoneyUserReq($d->premi),
						$this->formatter->getFormatMoneyUserReq($d->tunjangan_pph),
						$this->formatter->getFormatMoneyUserReq($d->thr),
						$this->formatter->getFormatMoneyUserReq($d->bruto_sebulan),
						$this->formatter->getFormatMoneyUserReq($d->pkp),
						$this->formatter->getFormatMoneyUserReq($d->besar_pajak),
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pph21');
				$data = $this->model_payroll->getListDataPenggajianPphNon(['a.id_pph21'=>$id]);
				$datax=[];
				foreach ($data as $d) {
					$datax=[
						'id_pph21'     =>$d->id_pph21,
						'nik'          =>$d->nik,
						'nama'         =>$d->nama,
						'alamat'       =>$d->alamat,
						'no_telp'      =>$d->no_telp,
						'bulan'        =>$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						'kode_pajak'   =>$d->jenis,
						'jenis_pajak'  =>$d->jenis_pajak,
						'status_pajak' =>$d->status_pajak,
						'npwp'         =>$d->npwp,
						'bruto_sebulan'=>$this->formatter->getFormatMoneyUserReq($d->bruto_sebulan),
						'bruto_setahun'=>$this->formatter->getFormatMoneyUserReq($d->bruto_setahun),
						'netto_sebulan'=>$this->formatter->getFormatMoneyUserReq($d->netto_sebulan),
						'netto_setahun'=>$this->formatter->getFormatMoneyUserReq($d->netto_setahun),
						'pajak_setahun'=>$this->formatter->getFormatMoneyUserReq($d->pajak_setahun),
						'pph_setahun'  =>$this->formatter->getFormatMoneyUserReq($d->pph_setahun),
						'pph_sebulan'  =>$this->formatter->getFormatMoneyUserReq($d->pph_sebulan),
						'pph_npwp'     =>$this->formatter->getFormatMoneyUserReq($d->pph_npwp),
						'status'       =>$d->status,
						'create_date'  =>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'  =>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'    =>$d->create_by,
						'update_by'    =>$d->update_by,
						'nama_buat'    =>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'  =>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'karyawan') {
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('tahun');
				$where = ['a.bulan'=>$bulan,'a.tahun'=>$tahun];
				$karyawan = $this->model_payroll->getListDataPenggajianPphNon($where);
				$sel_karyawan = '<option value="all">Pilih Semua</option>';
				foreach (array_filter($karyawan) as $bkey) {
					$koreksi =  (!empty($bkey->koreksi) && ($bkey->koreksi !=0))?' (Pembetulan '.$this->otherfunctions->getNumberToAbjad($bkey->koreksi).')':null;
					$sel_karyawan .= '<option value="'.$bkey->id_pph21.'">'.$bkey->nama.' - '.$bkey->jenis_pajak.$koreksi.'</option>';
				}
				$datax = ['karyawan'=>$sel_karyawan];
        		echo json_encode($datax);
			}elseif ($usage == 'insert_penunjang') {
				$tabel_end_proses='<table class="table table-bordered table-striped data-table" id="myTable">
								<thead>
									<tr class="bg-blue">
										<th>Penunjang</th>
										<th>Nominal</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>';
				$data_tunjangan=$this->otherfunctions->getListPenunjangNon();
				$select='';
				$select.='<select name="penunjang[]" class="form-control select2" style="width: 100%;" required="required">';
					$select.='<option value="">Pilih Data</option>';
					foreach ($data_tunjangan as $kda => $vka) {
						$select.='<option value="'.$kda.'">'.$vka.'</option>';
					}
				$select.='</select>';
				$nominal='<div class="input-group">
					<input type="text" name="nominal_penunjang[]" class="input-money form-control" placeholder="Tetapkan Nominal" required="required" style="width: 100%;"></div>
					<script>
						$(document).ready(function(){
							$(".input-money").keyup(function () {
								this.value = formatRupiah(this.value, "Rp. ");
							});
							$(".input-money").focus(function (data) {
								if (this.value == "Rp. 0") {
									this.value = "";
								}
							});
							$(".input-money").focusout(function (data) {
								if (this.value == "") {
									this.value = "Rp. 0";
								} else if (this.value == "0") {
									this.value = "Rp. 0";
								}
							});
						})
					</script>';
				$datax=[
					'select'=>$select,
					'nominal'=>$nominal,
					'tabel_end_proses'=>$tabel_end_proses,
				];
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_penunjang_non(){
		if (!$this->input->is_ajax_request()) 
		   redirect   ('not_found');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$karyawan = $this->input->post('karyawan');
		$penunjang = $this->input->post('penunjang');
		$nominal       = $this->input->post('nominal_penunjang');
		if(!empty($karyawan) && !empty($penunjang) && !empty($nominal)){
			foreach ($karyawan as $k_k => $idpph) {
				$dataPenunjang = [];
				$n_premi = 0;
				$n_pph = 0;
				$pph_dipotong = 0;
				foreach ($penunjang as $keyCode => $valCode) {
					$dataPenunjang[$valCode] = $nominal[$keyCode];
					if($valCode == 'premi_asuransi'){
						$n_premi += $nominal[$keyCode];
					}
					if($valCode == 'pph_tunjangan'){
						$n_pph += $nominal[$keyCode];
					}
					if($valCode == 'pph_dipotong'){
						$pph_dipotong += $nominal[$keyCode];
					}
				}
				$where = ['a.id_pph21'=>$idpph,'a.bulan'=>$bulan,'a.tahun'=>$tahun];
				$e = $this->model_payroll->getListDataPenggajianPphNon($where,null,null,null,null,true);
				if(!empty($e)){
					$bruto_bulan = $e['biaya']+$n_pph;//+$e['thr'];//+$n_premi;
					$bruto_tahun = $bruto_bulan*12;
					$netto_bulan = $bruto_bulan;
					$netto_tahun = $netto_bulan*12;
					if($e['perhitungan_pajak'] == 'NON_PTKP'){
						$tarif_pajak = 0;//$this->payroll->getPajakPertahun($bruto_tahun,$status_pajak);
						$n_ptkp 	 = $bruto_bulan;
						$n_pkp 		 = (50/100)*$bruto_bulan;
						$layer_pph   = $this->payroll->getLayerPPHNonKaryawan($n_pkp,$e['npwp']);
						$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$e['npwp']);
						$pph_tahun   = $get_pph['pph_tahun'];
						$pph_bulan   = $get_pph['pph_bulan'];
						$plus_npwp   = $get_pph['plus_npwp'];
						$pajak       = $get_pph['pph_tahun'];
					}else{
						$n_ptkp 	 = $this->payroll->getPTKP($e['status_pajak']);
						$n_pkp 		 = $netto_tahun-$n_ptkp;
						$n_pkp 		 = ($n_pkp > 0)?$n_pkp:0;
						$tarif_pajak = $this->payroll->getPajakPertahun($bruto_tahun,$e['status_pajak']);
						$tarif_pajak = ($tarif_pajak > 0)?$tarif_pajak:0;
						if($tarif_pajak == 0){
							$pph_tahun   = 0;
							$pph_bulan   = 0;
							$plus_npwp   = 0;
							$pajak       = 0;
						}else{
							$layer_pph   = $this->payroll->getLayerPPHNonKaryawan($tarif_pajak,$e['npwp']);
							$get_pph     = $this->payroll->getPPHPertahun($layer_pph,$e['npwp']);
							$pph_tahun   = $get_pph['pph_tahun'];
							$pph_bulan   = $get_pph['pph_bulan'];
							$plus_npwp   = $get_pph['plus_npwp'];
							$pajak       = $get_pph['pph_bulan'];
						}
					}
					$data = [
						'pph_dipotong'  =>$pph_dipotong,
						'upah_bulan_ini'=>$e['biaya']-$pph_dipotong,
						'premi'			=>$n_premi,
						'tunjangan_pph' =>$n_pph,
						'bruto_sebulan' =>$bruto_bulan,
						'bruto_setahun' =>$bruto_tahun,
						'netto_sebulan' =>$netto_bulan,
						'netto_setahun' =>$netto_tahun,
						'pajak_setahun' =>$tarif_pajak,
						'besar_pajak'	=>$pajak,
						'pph_setahun'   =>$pph_tahun,
						'pph_sebulan'   =>$pph_bulan,
						'pph_npwp'      =>$plus_npwp,
					];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_penggajian_pph21_non',['id_pph21'=>$idpph]);
				}else{
					$datax = $this->messages->customFailure('Data Kosong');
				}
			}
		}else{
			$datax = $this->messages->customFailure('Data Kosong');
		}
		echo json_encode($datax);
	}
	//============================================== DATA LOG PENGGAJIAN HARIAN ===========================================================
	public function data_log_penggajian_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);	
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_admin = $this->admin;
				$kode_periode = $this->input->post('kode_periode');
				if (!is_null($level) && $level > 2){
					$form_filter['a.create_by'] = $id_admin;
				}
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
						$this->formatter->getFormatMoneyUserReq($d->gaji_pokok),
						$this->formatter->getFormatMoneyUserReq($d->insentif),
						$this->formatter->getFormatMoneyUserReq($d->ritasi),
						$this->formatter->getFormatMoneyUserReq($d->uang_makan),
						$this->formatter->getFormatMoneyUserReq($d->pot_tidak_masuk),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jht),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jkk),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_jkm),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_pen),
						$this->formatter->getFormatMoneyUserReq($d->bpjs_kes),
						$this->formatter->getFormatMoneyUserReq($d->angsuran),
						$d->angsuran_ke,
						$this->formatter->getFormatMoneyUserReq($get_lembur['gaji_terima']),
						$this->payroll->getDataLainView($d->data_lain),
						$this->payroll->getDataLainNominalView($d->nominal_lain),
						$this->payroll->getDataLainView($d->keterangan_lain),
						$this->formatter->getFormatMoneyUserReq($d->gaji_bersih),
						$d->no_rek,
						// $this->otherfunctions->getLabelMark($d->tgl_terima,'danger'),
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
	public function cek_data_payroll_notif()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'lembur') {
				echo json_encode($this->messages->customFailure('Data Untuk Periode Ini Kosong (Belum di Generate atau sudah di Simpan ke Log).'));
			}elseif ($usage == 'kartu') {
				echo json_encode($this->messages->customFailure('Data Inputan Tidak Boleh Kosong'));
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	//================================================= TRANSAKSI KODE AKUN =======================================================//
	public function data_kode_akun()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('kode');
				parse_str($this->input->post('form'), $post_form);
				if($param == 'all'){
					$tanggal_selesai = date('Y-m-d');
					$tanggal_mulai = date('Y-m-d', strtotime($tanggal_selesai . ' -1 month'));
				}else{
					$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($post_form['tanggal'],'start')));
					$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($post_form['tanggal'],'end')));
				}
				$data=$this->model_payroll->getListDataKodeAkunPPH21(['a.tanggal >='=>$tanggal_mulai,'a.tanggal <='=>$tanggal_selesai]);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_akun,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$properties['aksi']=str_replace('view_modal', 'view_kode_akun', $properties['aksi']);
					$properties['status']=str_replace('do_status', 'do_status_kode_akun', $properties['status']);
					$properties['aksi']=str_replace('delete_modal', 'delete_kode_akun', $properties['aksi']);
					$datax['data'][]=[
						$d->id_akun,
						$d->nik,
						$d->nama_karyawan,
						$this->formatter->getDateFormatUser($d->tanggal),
						$d->no_bukti,
						$d->kode_akun.' - '.$d->nama_akun,
						$d->catatan,
						$this->formatter->getFormatMoneyUser($d->nominal),
						$d->no_proyek,
						$d->nama_proyek,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_akun');
				$data=$this->model_payroll->getListDataKodeAkunPPH21(['a.id_akun'=>$id]);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					if (strpos($d->nominal, '-') !== false) {
						$nominalx = explode('-', $d->nominal);
						$nominal = $nominalx[1];
						$minus = '1';
					}else{
						$nominal=$d->nominal;
						$minus = '0';
					}
					$datax=[
						'id'=>$d->id_akun,
						'kode'=>$d->kode_akun.' - '.$d->nama_akun,
						'kode_edit'=>$d->kode_akun,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik,
						'nama_karyawan'=>$d->nama_karyawan,
						'tanggal'=>$this->formatter->getDateFormatUser($d->tanggal),
						'no_bukti'=>$d->no_bukti,
						'catatan'=>$d->catatan,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'e_nominal'=>$this->formatter->getFormatMoneyUser($nominal),
						'no_proyek'=>$d->no_proyek,
						'minus'=>$minus,
						'nama_proyek'=>$d->nama_proyek,
						// 'nama_mengetahui'=>$d->nama_proyek,
						// 'nama_menyetujui'=>$d->nama_proyek,
						'nama_mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'nama_menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'mengetahui'=>$d->mengetahui,
						'menyetujui'=>$d->menyetujui,
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
			}elseif ($usage == 'kode_akun') {
				$data = $this->model_master->getKodeAkunForSelect2();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_data_kode_akun(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$no_bukti=$this->input->post('no_bukti');
		$tanggal=$this->input->post('tanggal');
		$karyawan=$this->input->post('karyawan');
		$no_akun=$this->input->post('no_akun');
		$nilai_bayar=$this->input->post('nilai_bayar');
		$mengetahui=$this->input->post('mengetahui');
		$menyetujui=$this->input->post('menyetujui');
		$catatan=$this->input->post('catatan');
		$no_proyek=$this->input->post('no_proyek');
		$nama_proyek=$this->input->post('nama_proyek');
		$val=$this->input->post('minus');
		$nilai = $this->formatter->getFormatMoneyDb($nilai_bayar);
		$nominal = ($val == '1')?'-'.$nilai:$nilai;
		if ($karyawan != "") {
			foreach ($karyawan as $kar) {
				$data=[
					'kode_akun'=>$no_akun,
					'id_karyawan'=>$kar,
					'no_bukti'=>$no_bukti,
					'tanggal'=>$this->formatter->getDateFormatDb($tanggal),
					'catatan'=>$catatan,
					'nominal'=>$nominal,
					'no_proyek'=>$no_proyek,
					'nama_proyek'=>$nama_proyek,
					'menyetujui'=>$menyetujui,
					'mengetahui'=>$mengetahui,
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'data_pph_kode_akun');
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_data_kode_akun(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$no_bukti=$this->input->post('no_bukti');
		$tanggal=$this->input->post('tanggal');
		$karyawan=$this->input->post('karyawan');
		$no_akun=$this->input->post('no_akun');
		$nilai_bayar=$this->input->post('nilai_bayar');
		$mengetahui=$this->input->post('mengetahui');
		$menyetujui=$this->input->post('menyetujui');
		$catatan=$this->input->post('catatan');
		$no_proyek=$this->input->post('no_proyek');
		$nama_proyek=$this->input->post('nama_proyek');
		$val=$this->input->post('minusx');
		$nilai = $this->formatter->getFormatMoneyDb($nilai_bayar);
		$nominal = ($val == '1')?'-'.$nilai:$nilai;
		if ($no_bukti != "") {
			$data=[
				'kode_akun'=>$no_akun,
				// 'id_karyawan'=>$karyawan,
				'no_bukti'=>$no_bukti,
				'tanggal'=>$this->formatter->getDateFormatDb($tanggal),
				'catatan'=>$catatan,
				'nominal'=>$nominal,
				'no_proyek'=>$no_proyek,
				'nama_proyek'=>$nama_proyek,
				'menyetujui'=>$menyetujui,
				'mengetahui'=>$mengetahui,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_pph_kode_akun',['id_akun'=>$id]);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function import_pph_kode_akun()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data['properties']=[
			'post'=>'file',
			'data_post'=>$this->input->post('file', TRUE),
		];
		$sheet[0]=[
			'range_huruf'=>3,
			'row'=>2,
			'table'=>'data_pph_kode_akun',
			'column_code'=>'id_karyawan',
			'usage'=>'pph_kode_akun',
			'column_properties'=>$this->model_global->getCreateProperties($this->admin),
			//urutan sama dengan export
			'column'=>[
				0=>'nik',2=>'tanggal',3=>'no_bukti',4=>'kode_akun',5=>'nama_akun',6=>'catatan',7=>'nominal',8=>'nama_proyek',9=>'no_proyek',
			],
		];
		$data['data']=$sheet;
		$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
//--------------------------------------------Data Pendukung Pinjaman--------------------------------------------//
	public function master_pinjaman()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				parse_str($this->input->post('form'), $post_form);
				$bulan = null;
				if(isset($post_form['bulan']) || !empty($post_form['bulan'])){
					$bulan=$post_form['bulan'];
				}
				$tahun = null;
				if(isset($post_form['tahun']) || !empty($post_form['tahun'])){
					$tahun=$post_form['tahun'];
				}
				$bulanx=(!empty($bulan)) ? ["a.bulan" => $bulan] : [];
				$tahunx=(!empty($tahun)) ? ["a.tahun" => $tahun] : [];
				$where = array_merge($bulanx,$tahunx);
				$data = $this->model_payroll->getListPinjaman($where);
				// $data = $this->model_payroll->getListPinjaman();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pinjaman,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$sisa_angsuran = $this->payroll->getSisaPinjaman($d->kode_pinjaman,$d->nominal);
					$action = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modalEndPinjaman('.$d->id_pinjaman.')><i class="fas fa-swatchbook" data-toggle="tooltip" title="Aksi Status Pinjaman"></i></button> ';
					$datax['data'][]=[
						$d->id_pinjaman,
						'<a href="'.base_url('pages/data_pendukung/view_angsuran/'.$this->codegenerator->encryptChar($d->kode_pinjaman)).'" target="blank">'.$d->kode_pinjaman.'</a>',
						$d->nama,
						$d->nama_karyawan,
						$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						$this->formatter->getFormatMoneyUserReq($d->nominal),
						(!empty($d->lama_angsuran)) ? $d->lama_angsuran.' Kali' : 0,
						(!empty($d->jum_ang)) ? $d->jum_ang.' Kali' : '<label class="label label-danger"><i class="fa fa-times-circle"></i> Belum Diangsur</label>',
						$this->formatter->getFormatMoneyUserReq($sisa_angsuran),
						($d->status_pinjaman == '1')?'<label class="label label-success" style="font-size:12px;">LUNAS</label>':'<label class="label label-danger" style="font-size:12px;">Belum Lunas</label>',
						$action.$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pinjaman');
				$mode = $this->input->post('mode');
				$data=$this->model_payroll->getListPinjaman(['a.id_pinjaman'=>$id]);
				foreach ($data as $d) {
					$pinj=$this->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$d->kode_pinjaman],1);
					$pattern=json_decode($d->pattern);
					$tb='';
					if (isset($pattern)) {
						$tb='<div style="max-height:300px;overflow-y:auto">
							<table class="table" width="100%">
								<tr class="bg-blue">
									<th>Bulan Tahun<br>(Simulasi)</th>
									<th>Besar Angsuran</th>
									<th>Sisa Pinjaman</th>
									<th>Status</th>
								</tr>';
						$sisaAngsuran=$this->payroll->getAllSisaPinjaman($d->pattern,$d->nominal,$d->lama_angsuran);
						// print_r($sisaAngsuran);
						foreach ($pattern as $bulan => $bAngsuran) {
							$dtx = [];
							foreach ($pinj as $dt) {
								$dtx[$bulan][]=['bulan'=>$dt->bulan,'tahun'=>$dt->tahun,'keterangan'=>$dt->keterangan];
							}
							$bulanx = (isset($dtx[$bulan][($bulan-1)])?$dtx[$bulan][($bulan-1)]['bulan']:null);
							$tahunx = (isset($dtx[$bulan][($bulan-1)])?$dtx[$bulan][($bulan-1)]['tahun']:null);
							$keteranganx = (isset($dtx[$bulan][($bulan-1)])?$dtx[$bulan][($bulan-1)]['keterangan']:null);
							// $sisaAngsuranx = (isset($dtx[$bulan][($bulan-1)])?$this->formatter->getFormatMoneyUserReq($sisaAngsuran[$bulan]):null);
							$sisaAngsuranx = $this->formatter->getFormatMoneyUserReq($sisaAngsuran[$bulan-1]);
							$statusx = (isset($dtx[$bulan][($bulan-1)])?'<label class="label label-success" style="font-size:14px;"><i class="fa fa-check-circle"></i> Sudah Dibayar</label>':'<label class="label label-danger" style="font-size:14px;"><i class="fa fa-times-circle"></i> Belum Dibayar</label>');
							$blx = $this->otherfunctions->getBulanTahunAngsuran($d->bulan,$d->lama_angsuran);
								// <th>Bayar</th>
								// <th>Keterangan</th>
								// <td>'.$this->formatter->getNameOfMonth($bulanx).' '.$tahunx.'</td>
								// <td>'.$keteranganx.'</td>
							$tb.='<tr>
								<td>'.$blx[$bulan].'</td>
								<td>'.$this->formatter->getFormatMoneyUserReq($bAngsuran).'</td>
								<td>'.$sisaAngsuranx.'</td>
								<td>'.$statusx.'</td>
							</tr>';
						}
						$tb.='</table></div>';
					}
					$sisa_angsuran = $this->payroll->getSisaPinjaman($d->kode_pinjaman,$d->nominal);
					$datax=[
						'id'=>$d->id_pinjaman,
						'kode'=>$d->kode_pinjaman,
						'nama'=>$d->nama,
						'karyawan'=>($mode == 'edit') ? $d->id_karyawan : $d->nama_karyawan,
						'jabatan'=>($mode == 'edit') ? '' : $d->nama_jabatan,
						'bagian'=>($mode == 'edit') ? '' : $d->nama_bagian,
						'loker'=>($mode == 'edit') ? '' : $d->nama_loker,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						// 'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tanggal),
						'e_tanggal'=>$this->formatter->getDateFormatUser($d->tanggal),
						'tanggal'=>$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						'e_bulan'=>$d->bulan,
						'e_tahun'=>$d->tahun,
						'lama_angsuran'=>(!empty($d->lama_angsuran)) ? $d->lama_angsuran : 0,
						'sudah_diangsur'=>(!empty($d->jum_ang)) ? $d->jum_ang.' Kali' : '<label class="label label-danger"><i class="fa fa-times-circle"></i> Belum Diangsur</label>',
						'jenis'=>$d->jenis,
						'keterangan'=>$d->keterangan,
						'sisa_pinjaman'=>$this->formatter->getFormatMoneyUserReq($sisa_angsuran),
						'jum_angsuran'=>count($pinj),
						'table'=>$tb,
						'pattern'=>$pattern,
						'status_pinjaman'=>$d->status_pinjaman,
						'catatan'=>$d->catatan,
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
			}elseif ($usage == 'view_select') {
				$kode = $this->input->post('kode_lokasi');
				if($kode == ''){
					$data = $this->model_karyawan->getEmployeeAllActive();
				}else{
					$data = $this->model_karyawan->getEmpKodeLokasi($kode);
				}
				$datax='';
				foreach ($data as $d) {
					$datax.='<option value="'.$d->id_karyawan.'">'.$d->nama.' ('.$d->nama_jabatan.')</option>';
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePinjaman();
        		echo json_encode($data);
			}elseif ($usage == 'getJustNominal') {
				$nominal = $this->formatter->getFormatMoneyDb($this->input->post('nominal'));
        		echo json_encode($nominal);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_pinjaman()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$tanggal = $this->input->post('tanggal');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$karyawan = $this->input->post('karyawan');
		$keterangan = $this->input->post('keterangan');
		$nominal = $this->formatter->getFormatMoneyDb($this->input->post('nominal'));
		$lama_angsuran = $this->input->post('lama_angsuran');
		$denda = $this->input->post('denda');
		if($denda == 1){
			$pattern=json_encode($this->input->post('pattern'));
		}else{
			$ang_bulan = ($nominal/$lama_angsuran);
			$pattern='';
			$pattern.='{';
			for ($i=1; $i <= $lama_angsuran; $i++) { 
				$pattern.='"'.$i.'":"'.(int)$ang_bulan.'"'.(($lama_angsuran > ($i))?',':'');
			}
			$pattern.='}';
		}
		if ($kode != "") {
			$data = [
				'kode_pinjaman'=>$kode,
				'nama'=>$nama,
				'id_karyawan'=>$karyawan,
				'jenis'=>$denda,
				// 'tanggal'=>$this->formatter->getDateFormatDb($tanggal),
				'tanggal'=>$tahun.'-'.$bulan.'-01',
				'bulan'=>$bulan,
				'tahun'=>$tahun,
				'nominal'=>$nominal,
				'lama_angsuran'=>$lama_angsuran,
				'keterangan'=>$keterangan,
				'pattern'=>$pattern,
			];
			$cek=$this->model_karyawan->checkPinjamanCode($data['kode_pinjaman']);
			if(!empty($cek)){
				$data['kode_pinjaman'] = $this->codegenerator->kodePinjaman();
			}
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'data_pinjaman',$this->model_karyawan->checkPinjamanCode($data['kode_pinjaman']));	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function edt_pinjaman(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$karyawan = $this->input->post('karyawan');
		$tanggal = $this->input->post('tanggal');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$nominal = $this->formatter->getFormatMoneyDb($this->input->post('nominal'));
		$lama_angsuran = $this->input->post('lama_angsuran');
		$keterangan = $this->input->post('keterangan');
		$jenis = $this->input->post('jenis');
		$jenis_old = $this->input->post('jenis_old');
		$patternx = $this->input->post('pattern');
		// $jenis = 1;
		if(!empty($patternx)){
			$pattern=json_encode($this->input->post('pattern'));
		// }elseif($jenis_old == 0 && $jenis == 0){
		}else{
			$ang_bulan = ($nominal/$lama_angsuran);
			$pattern='';
			$pattern.='{';
			for ($i=1; $i <= $lama_angsuran; $i++) { 
				$pattern.='"'.$i.'":"'.(int)$ang_bulan.'"'.(($lama_angsuran > ($i))?',':'');
			}
			$pattern.='}';
		}
		if ($id != "") {
			$data=array(
				'kode_pinjaman'=>$kode,
				'nama'=>$nama,
				'id_karyawan'=>$karyawan,
				'jenis'=>$jenis,
				// 'tanggal'=>$this->formatter->getDateFormatDb($tanggal),
				'tanggal'=>$tahun.'-'.$bulan.'-01',
				'bulan'=>$bulan,
				'tahun'=>$tahun,
				'nominal'=>$nominal,
				'lama_angsuran'=>$lama_angsuran,
				'keterangan'=>$keterangan,
				'pattern'=>$pattern,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_pinjaman']) {
				$cek=$this->model_karyawan->checkPinjamanCode($data['kode_pinjaman']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'data_pinjaman',['id_pinjaman'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function ubah_status_pinjaman(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_pinjaman=$this->input->post('id_pinjaman');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$data = [
			'catatan'=>$keterangan,
			'status_pinjaman'=>$status,
		];
		$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		$datax = $this->model_global->updateQuery($data,'data_pinjaman',['id_pinjaman'=>$id_pinjaman]);
		echo json_encode($datax);
	}
//--------------------------------------------Data Pendukung ANgsuran --------------------------------------------//
	public function data_angsuran()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$kodePinjaman=$this->codegenerator->decryptChar($this->uri->segment(4));
				$data = $this->model_payroll->getListAngsuran(['a.kode_pinjaman'=>$kodePinjaman],1);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_angsuran,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_angsuran.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$d->id_angsuran,
						$d->kode_angsuran,
						$d->kode_pinjaman,
						$d->nama_karyawan,$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						$this->formatter->getFormatMoneyUserReq($d->nominal),
						$d->keterangan,
						$info,
						// $properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_angsuran');
				$mode = $this->input->post('mode');
				$data=$this->model_payroll->getListAngsuran(['a.id_angsuran'=>$id],1);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_angsuran,
						'kode'=>$d->kode_angsuran,
						'nama'=>$d->nama_pinjaman,
						'karyawan'=>($mode == 'edit') ? $d->id_karyawan : $d->nama_karyawan,
						'jabatan'=>($mode == 'edit') ? '' : $d->nama_jabatan,
						'bagian'=>($mode == 'edit') ? '' : $d->nama_bagian,
						'loker'=>($mode == 'edit') ? '' : $d->nama_loker,
						'keterangan'=>$d->keterangan,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'bulan'=>$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
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
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
}
