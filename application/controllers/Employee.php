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

class Employee extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
		if (isset($_SESSION['adm'])) {
			$this->admin = $_SESSION['adm']['id'];	
		}else{
			redirect('auth');
		}
		$ha = '0123456789';
	    $panjang = strlen($ha);
	    $rand = '';
	    for ($i = 0; $i < 10; $i++) {
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
				'level'=>$dtroot['admin']['level'],
				'id_karyawan'=>$dtroot['admin']['id_karyawan'],
				'create'=>$dtroot['admin']['create_date'],
				'update'=>$dtroot['admin']['update_date'],
				'login'=>$dtroot['admin']['last_login'],
			);
		$this->dtroot=$datax;
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
		$this->filter=$this->model_admin->getFilter()['list_bagian'];		
		$this->access=array('access'=>$l_acc,'l_ac'=>$l_ac,'b_stt'=>$attr,'n_all'=>$not_allow);
	}
	public function index(){
		redirect('pages/dashboard');
	}
	function coba(){
		$data = $this->insertDataPenggajianHarian();
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	function edt_jabatan(){
		$id=$this->input->post('id_karyawan');
		$nik=$this->input->post('nik');
		if ($id == "" || $nik == ""){
			$this->messages->notValidParam();  
			redirect('pages/employee');
		}else{
			if ($this->input->post('jabatan_pa') == "" || $this->input->post('jabatan_pa') == NULL) {
				$jpa=NULL;
			}else{
				$jpa=$this->input->post('jabatan_pa');
			}
			if ($this->input->post('loker_pa') == "" || $this->input->post('loker_pa') == NULL) {
				$lpa=NULL;
			}else{
				$lpa=$this->input->post('loker_pa');
			}
			if ($this->input->post('pilih') == '0') {
				$jpa=NULL;
				$lpa=NULL;
			}
			if ($this->input->post('pilih_sub') == '0') {
				$kode_sub=NULL;
				$jbt=$this->input->post('jabatan');
				$lok=$this->input->post('loker');
			}else{
				$sub=$this->input->post('sub_jbt');
				$subd=$this->model_master->k_s_jabatan($sub);
				$jbt=$subd['kode_jabatan'];				
				$lok=$subd['kode_loker'];
				$kode_sub=$sub;
			}
			$data=array(
				'jabatan'=>$jbt,
				'unit'=>$lok,
				'jabatan_pa'=>$jpa,
				'loker_pa'=>$lpa,
				'kode_sub'=>$kode_sub,
			);
			$this->db->where('id_karyawan',$id);
			$in=$this->db->update('karyawan',$data);
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure(); 
			}
	   		redirect('pages/view_employee/'.$nik);
		}
	}
	function up_foto(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->input->post('nik');
		if ($nik != "") {
			$other=$this->model_global->getUpdateProperties($this->admin);
			$data=[
				'post'=>'foto',
		        'data_post'=>$this->input->post('foto', TRUE),
		        'table'=>'karyawan',
		        'column'=>'foto',
		        'where'=>['nik'=>$nik],
		        'usage'=>'update',
		        'otherdata'=>$other,
		    ];
			$getEmp = $this->model_karyawan->getEmployeeNik($nik);
			if($getEmp['foto'] != ''){
				if($getEmp['foto'] == 'asset/img/user-photo/user.png' || $getEmp['foto'] == 'asset/img/user-photo/userf.png'){
					// tidak bisa if($getEmp['foto'] != 'asset/img/user-photo/user.png' || $getEmp['foto'] != 'asset/img/user-photo/userf.png')
				}else{
					unlink($getEmp['foto']);
				}
			}
		    $datax=$this->filehandler->doUpload($data);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_new(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->input->post('nik');
		if ($nik != "") {
			$nama_anak			=$this->input->post('nama_anak');
			$kelamin_anak		=$this->input->post('kelamin_anak');
			$tempat_lahir_anak	=$this->input->post('tempat_lahir_anak');
			$tanggal_lahir_anak	=$this->input->post('tanggal_lahir_anak');
			$no_hp_anak			=$this->input->post('nohp_anak');
			$pendidikan_anak	=$this->input->post('pendidikan_anak');
			if ($nama_anak != NULL) {
				for ($i = 1; $i <=count($nama_anak) ; $i++) {
					$data_anak=array(
						'nama_anak'			=>$nama_anak[$i],
						'kelamin_anak'		=>$kelamin_anak[$i],
						'tempat_lahir_anak'	=>$tempat_lahir_anak[$i],
						'tanggal_lahir_anak'=>$this->formatter->getDateFormatDb($tanggal_lahir_anak[$i]),
						'pendidikan_anak'	=>$pendidikan_anak[$i],
						'no_telp'			=>$no_hp_anak[$i],
						'nik'				=>$nik,
						);
					$data_anak2=array_merge($data_anak,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQueryNoMsg($data_anak2,'karyawan_anak');
				}
			}
			if ($nama_anak>1) {
				$data['jumlah_anak']=count($nama_anak);
			}else{
				$data['jumlah_anak']=null;
			}
			$nama_saudara				= $this->input->post('nama_saudara');
			$jenis_kelamin_saudara		= $this->input->post('jenis_kelamin_saudara');
			$tempat_lahir_saudara		= $this->input->post('tempat_lahir_saudara');
			$tanggal_lahir_saudara		= $this->input->post('tanggal_lahir_saudara');
			$pendidikan_saudara			= $this->input->post('pendidikan_saudara');
			$no_telp_saudara			= $this->input->post('no_telp_saudara');
			if ($nama_saudara != NULL){
				for ($j = 1; $j <=count($nama_saudara); $j++) {
				$data_saudara = array(
					'nama_saudara'			=> $nama_saudara[$j],
					'jenis_kelamin_saudara'	=> $jenis_kelamin_saudara[$j],
					'tempat_lahir_saudara'	=> $tempat_lahir_saudara[$j],
					'tanggal_lahir_saudara'	=> $this->formatter->getDateFormatDb($tanggal_lahir_saudara[$j]),
					'pendidikan_saudara'	=> $pendidikan_saudara[$j],
					'no_telp_saudara'		=> $no_telp_saudara[$j],
					'nik'					=> $nik
					);
				$data_saudara2=array_merge($data_saudara,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($data_saudara2,'karyawan_saudara');
				}
			}
			$jenjang_pendidikan 	= $this->input->post('jenjang_pendidikan');
			$nama_sekolah			= $this->input->post('nama_sekolah');
			$jurusan				= $this->input->post('jurusan');
			$fakultas				= $this->input->post('fakultas');
			$tahun_masuk			= $this->input->post('tahun_masuk');
			$tahun_keluar			= $this->input->post('tahun_keluar');
			$alamat_sekolah			= $this->input->post('alamat_sekolah');
			if ($nama_sekolah != NULL){
				for ($k = 1; $k <=count($nama_sekolah) ; $k++) {
				$data_sekolah = array ( 
					'jenjang_pendidikan'	=>$jenjang_pendidikan[$k],
					'nama_sekolah'	=> $nama_sekolah[$k],
					'jurusan'		=> $jurusan[$k],
					'fakultas'		=> $fakultas[$k],
					'tahun_masuk'	=> $this->formatter->getDateFormatDb($tahun_masuk[$k]),
					'tahun_keluar'	=> $this->formatter->getDateFormatDb($tahun_keluar[$k]),
					'alamat_sekolah'=> $alamat_sekolah[$k],
					'nik'			=> $nik
				);
				$data_sekolah2=array_merge($data_sekolah,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($data_sekolah2,'karyawan_pendidikan');
				}
			}
			$nama_pnf				= $this->input->post('nama_pnf');
			$tanggal_masuk_pnf		= $this->input->post('tanggal_masuk_pnf');
			$sertifikat_pnf			= $this->input->post('sertifikat_pnf');
			$nama_lembaga_pnf		= $this->input->post('nama_lembaga_pnf');
			$alamat_pnf				= $this->input->post('alamat_pnf');
			$keterangan_pnf			= $this->input->post('keterangan_pnf');
			if ($nama_pnf != NULL){
				for ($l = 1; $l <=count($nama_pnf) ; $l++) {
				$data_pnf = array (
					'nama_pnf'			=> $nama_pnf[$l],
					'tanggal_masuk_pnf'	=> $this->formatter->getDateFormatDb($tanggal_masuk_pnf[$l]),
					'sertifikat_pnf'	=> $sertifikat_pnf[$l],
					'nama_lembaga_pnf'	=> $nama_lembaga_pnf[$l],
					'alamat_pnf'		=> $alamat_pnf[$l],
					'keterangan_pnf'	=> $keterangan_pnf[$l],
					'nik'				=> $nik
				);
				$data_pnf=array_merge($data_pnf,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($data_pnf,'karyawan_pnf');
				}
			}
			$nama_penghargaan 		= $this->input->post('nama_penghargaan');
			$tahun 					= $this->input->post('tahun');
			$peringkat				= $this->input->post('peringkat');
			$yg_menetapkan			= $this->input->post('yg_menetapkan');
			$penyelenggara			= $this->input->post('penyelenggara');
			$keterangan 			= $this->input->post('keterangan');
			if ($nama_penghargaan !=NULL){
				for ($m = 1; $m <=count($nama_penghargaan) ; $m++) {
				$data_penghargaan = array (
					'nama_penghargaan'	=> $nama_penghargaan[$m],
					'tahun'			=> $this->formatter->getDateFormatDb($tahun[$m]),
					'peringkat'			=> $peringkat[$m],
					'yg_menetapkan'		=> $yg_menetapkan[$m],
					'penyelenggara'		=> $penyelenggara[$m],
					'keterangan'		=> $keterangan[$m],
					'nik'				=> $nik  
				);
				$data_penghargaan2=array_merge($data_penghargaan,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($data_penghargaan2,'karyawan_penghargaan');
				}
			}
			$nama_organisasi		= $this->input->post('nama_organisasi');
			$tahun_masuk 			= $this->input->post('tahun_masuk');
			$tahun_keluar 			= $this->input->post('tahun_keluar');
			$jabatan 				= $this->input->post('jabatan_org');
			if ($nama_organisasi !=NULL){
				for ($n = 1; $n <=count($nama_organisasi) ; $n++) {
				$data_organisasi = array (
					'nama_organisasi'	=> $nama_organisasi[$n],
					'tahun_masuk'		=> $this->formatter->getDateFormatDb($tahun_masuk[$n]),
					'tahun_keluar'		=> $this->formatter->getDateFormatDb($tahun_keluar[$n]),
					'jabatan_org'		=> $jabatan[$n],
					'nik'				=> $nik
				);
				$data_organisasi2=array_merge($data_organisasi,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($data_organisasi2,'karyawan_organisasi');
				}
			}
			$bahasa 		= $this->input->post('bahasa');
			$membaca		= $this->input->post('membaca');
			$menulis		= $this->input->post('menulis');
			$berbicara		= $this->input->post('berbicara');
			$mendengar		= $this->input->post('mendengar');
			if  ($bahasa != NULL){
				for ($o = 1; $o <=count($bahasa) ; $o++) {
				$data_bahasa=array(
					'bahasa'	=> $bahasa[$o],
					'membaca'	=> $membaca[$o],
					'menulis'	=> $menulis[$o],
					'berbicara'	=> $berbicara[$o],
					'mendengar'	=> $mendengar[$o],
					'nik'		=> $nik
				);
				$data_bahasa2=array_merge($data_bahasa,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($data_bahasa2,'karyawan_bahasa');
				}
			}
			$datas1 = ['nik'=> $this->input->post('nik'),
						'no_sk_baru'=>$this->input->post('no_sk_baru'),
						'tgl_sk_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_masuk')),
						'tgl_berlaku_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_masuk')),
						'berlaku_sampai_baru'=>$this->formatter->getDateFormatDb($this->input->post('berlaku_sampai')),
						'status_baru'=>$this->input->post('status_perjanjian'),
						'status_karyawan'=> $this->input->post('status_karyawan'),
						];
			$datas2=array_merge($datas1,$this->model_global->getCreateProperties($this->admin));
			$this->model_global->insertQueryCCNoMsg($datas2,'data_perjanjian_kerja',$this->model_karyawan->checkPeringatanCode($datas1['no_sk_baru']));
			$pendidikan_max = $this->model_karyawan->pendidikan_max($nik);
			$other['pendidikan']=$pendidikan_max['jenjang_pendidikan'];
			$other['universitas']=$pendidikan_max['nama_sekolah'];
			$other['jurusan']=$pendidikan_max['jurusan'];
			$other=[
				'nik'						=> $nik,
				'no_ktp'					=> $this->input->post('no_id'),
				'id_finger'					=> $this->input->post('id_finger'),
				'nama'						=> strtoupper($this->input->post('nama')),
				'tempat_lahir'				=> $this->input->post('tempat_lahir'),
				'tgl_lahir'					=> $this->formatter->getDateFormatDb($this->input->post('tgl_lahir')),
				'no_hp'						=> $this->input->post('no_hp'),
				'agama'						=> $this->input->post('agama'),
				'kelamin'					=> $this->input->post('kelamin'),
				'jabatan'					=> $this->input->post('jabatan'),
				'loker'						=> $this->input->post('loker'),
				'grade'						=> $this->input->post('grade'),
				'status_perjanjian'			=> $this->input->post('no_sk_baru'),
				'status_karyawan'			=> $this->input->post('status_karyawan'),
				'tgl_masuk'					=> $this->formatter->getDateFormatDb($this->input->post('tgl_masuk')),
				'npwp'						=> $this->input->post('no_npwp'),
				'gol_darah'					=> $this->input->post('darah'),
				'bpjskes'					=> $this->input->post('no_bpjskes'),
				'bpjstk'					=> $this->input->post('no_bpjstk'),
				'berat_badan'				=> $this->input->post('berat_badan'),
				'tinggi_badan'				=> $this->input->post('tinggi_badan'),
				'rekening'					=> $this->input->post('rekening'),
				'nama_bank'					=> $this->input->post('bank'),
				'email'						=> $this->input->post('email'),
				'status_pajak'				=> $this->input->post('status_pajak'),
				'status_nikah'				=> $this->input->post('nikah'),
				'kode_penggajian'			=> $this->input->post('sistem_penggajian'),
				'alamat_asal_jalan'			=> $this->input->post('alamat_asal_jalan'),
				'alamat_asal_desa'			=> $this->input->post('alamat_asal_desa'),
				'alamat_asal_kecamatan'		=> $this->input->post('alamat_asal_kecamatan'),
				'alamat_asal_kabupaten'		=> $this->input->post('alamat_asal_kabupaten'),
				'alamat_asal_provinsi'		=> $this->input->post('alamat_asal_provinsi'),
				'alamat_asal_pos'			=> $this->input->post('alamat_asal_pos'),
				'alamat_sekarang_jalan'		=> $this->input->post('alamat_sekarang_jalan'),
				'alamat_sekarang_desa'		=> $this->input->post('alamat_sekarang_desa'),
				'alamat_sekarang_kecamatan'	=> $this->input->post('alamat_sekarang_kecamatan'),
				'alamat_sekarang_kabupaten'	=> $this->input->post('alamat_sekarang_kabupaten'),
				'alamat_sekarang_provinsi'	=> $this->input->post('alamat_sekarang_provinsi'),
				'alamat_sekarang_pos'		=> $this->input->post('alamat_sekarang_pos'),
				'nama_ayah'					=> $this->input->post('nama_ayah'),
				'tempat_lahir_ayah'			=> $this->input->post('tempat_lahir_ayah'),
				'tanggal_lahir_ayah'		=> $this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_ayah')),
				'pendidikan_terakhir_ayah'	=> $this->input->post('pendidikan_terakhir_ayah'),
				'no_hp_ayah'				=> $this->input->post('no_telp_ayah'),
				'alamat_ayah'				=> $this->input->post('alamat_ayah'),
				'desa_ayah'					=> $this->input->post('desa_ayah'),
				'kecamatan_ayah'			=> $this->input->post('kecamatan_ayah'),
				'kabupaten_ayah'			=> $this->input->post('kabupaten_ayah'),
				'provinsi_ayah'				=> $this->input->post('provinsi_ayah'),
				'kode_pos_ayah'				=> $this->input->post('kode_pos_ayah'),
				'nama_ibu'					=> $this->input->post('nama_ibu'),
				'tempat_lahir_ibu'			=> $this->input->post('tempat_lahir_ibu'),
				'tanggal_lahir_ibu'			=> $this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_ibu')),
				'pendidikan_terakhir_ibu'	=> $this->input->post('pendidikan_terakhir_ibu'),
				'no_hp_ibu'					=> $this->input->post('no_telp_ibu'),
				'alamat_ibu'				=> $this->input->post('alamat_ibu'),
				'desa_ibu'					=> $this->input->post('desa_ibu'),
				'kecamatan_ibu'				=> $this->input->post('kecamatan_ibu'),
				'kabupaten_ibu'				=> $this->input->post('kabupaten_ibu'),
				'provinsi_ibu'				=> $this->input->post('provinsi_ibu'),
				'kode_pos_ibu'				=> $this->input->post('kode_pos_ibu'),
				'nama_pasangan'				=> $this->input->post('nama_pasangan'),
				'tempat_lahir_pasangan'		=> $this->input->post('tempat_lahir_pasangan'),
				'tanggal_lahir_pasangan'	=> $this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_pasangan')),
				'pendidikan_terakhir_pasangan'	=> $this->input->post('pendidikan_terakhir_pasangan'),
				'no_hp_pasangan'			=> $this->input->post('no_telp_pasangan'),
				'alamat_pasangan'			=> $this->input->post('alamat_pasangan'),
				'desa_pasangan'				=> $this->input->post('desa_pasangan'),
				'kecamatan_pasangan'		=> $this->input->post('kecamatan_pasangan'),
				'kabupaten_pasangan'		=> $this->input->post('kabupaten_pasangan'),
				'provinsi_pasangan'			=> $this->input->post('provinsi_pasangan'),
				'kode_pos_pasangan'			=> $this->input->post('kode_pos_pasangan'),
				'baju'						=> $this->input->post('baju'),
				'metode_pph'				=> $this->input->post('metode_pph'),
				'sepatu'					=> $this->input->post('sepatu'),
				'password'					=> hash('sha512', '123456'),
				'gaji_pokok'				=> $this->input->post('gaji_pokok'),
				'gaji'						=> $this->formatter->getFormatMoneyDb($this->input->post('besaran_gaji')),
				'sisa_cuti'					=>0,	
			];
			$jab_sek=$this->input->post('jabatan_sekunder');
			$other['jabatan_sekunder']=(isset($jab_sek)?implode(';', $jab_sek):null);
			$other2=array_merge($other,$this->model_global->getCreateProperties($this->admin));
			$foto_gender=($other['kelamin'] == 'p')?'userf.png':'user.png';
			$data=[
				'post'=>'foto',
		        'data_post'=>$this->input->post('foto', TRUE),
		        'table'=>'karyawan',
		        'column'=>'foto',
		        'usage'=>'insert',
		        'allow_null'=>TRUE,
		        'default_dir'=>'asset/img/user-photo/'.$foto_gender,
		        'otherdata'=>$other2,
		    ];
		    $datax=$this->filehandler->doUpload($data,'image');
			if($datax){
				$idKar = $this->model_karyawan->getEmployeeNik($nik)['id_karyawan'];
				$kode_master_shift=$this->input->post('kode_master_shift');
				$tgl_berlaku_shift=$this->input->post('tgl_berlaku_shift');
				$petugas_payroll=$this->input->post('petugas_payroll');
				$petugas_pph=$this->input->post('petugas_pph');
				$petugas_lembur=$this->input->post('petugas_lembur');
				$this->otherfunctions->addNewJadwalKerja($kode_master_shift, $tgl_berlaku_shift, $idKar, $this->admin);
				$this->otherfunctions->implodeToPetugasPayrollNewKar($petugas_payroll, $idKar, $this->admin);
				$this->otherfunctions->implodeToPetugasPPHNewKar($petugas_pph, $idKar, $this->admin);
				$this->otherfunctions->implodeToPetugasLemburNewKar($petugas_lembur, $idKar, $this->admin);
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function generate_nik(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$tgl_masuk = $this->input->post('tgl_masuk');
		$tgl_lahir = $this->input->post('tgl_lahir');
		$nik=$this->codegenerator->nikJkb($tgl_masuk,$tgl_lahir);
		$cek=$this->model_karyawan->checkNik($nik);
		if ($cek) {
			$msg='<i class="fa fa-times"></i> NIK sudah dipakai';
		}else{
			$msg='<i class="fa fa-check"></i> NIK Tersedia';
		}
		$data=['msg'=>$msg,'nik'=>$nik,'status_data'=>$cek];
		echo json_encode($data);
	}
	function res_foto(){
		$nik=$this->input->post('nik');
		$kelamin=$this->input->post('kelamin');
		if ($nik == ""){
			$datax = $this->messages->notValidParam();
		}else{
			$getEmp = $this->model_karyawan->getEmployeeNik($nik);
			if($getEmp['foto'] == 'asset/img/user-photo/user.png'){
				$foto='asset/img/user-photo/user.png';
			}elseif($getEmp['foto'] == 'asset/img/user-photo/userf.png'){
				$foto='asset/img/user-photo/userf.png';
			}else{
				unlink($getEmp['foto']);
				if ($kelamin == 'l') {
					$foto='asset/img/user-photo/user.png';
				}else{
					$foto='asset/img/user-photo/userf.png';
				}
			}
			$where = ['nik'=>$nik];
			$data = [
				'foto'=> $foto,
				'update_date'=>$this->date,
				'update_by'=>$this->admin
			];
			$datax = $this->model_global->updateQuery($data,'karyawan',$where);
		}
		echo json_encode($datax);
	}
	function up_pass(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id_karyawan=$this->input->post('id_karyawan');
		$paslama=$this->codegenerator->genPassword($this->input->post('old_pass'));
		$pasbaru=$this->codegenerator->genPassword($this->input->post('password1'));
		$upasbaru=$this->codegenerator->genPassword($this->input->post('password2'));
		if (empty($id_karyawan)) {
			$datax=$this->messages->notValidParam();  
		}else{
			$cekpass = $this->model_karyawan->getEmpID($id_karyawan);
			$kode = $cekpass['password'];
			if($paslama==$kode){
				if($pasbaru==$upasbaru){
					$data=['password'=>$pasbaru];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id_karyawan]);
				}else{
					$datax=$this->messages->customFailure('Password Tidak Sama Dengan Ulangi Password');
				}
			}else{
				$datax=$this->messages->customFailure('Password Lama Salah');
			}
		}
		echo json_encode($datax);
	}
	function del_log(){
		$nik=$this->input->post('nik');
		$nik1=$this->model_karyawan->emp($nik);
		if ($nik == "") {
			$this->messages->notValidParam();  
			redirect('pages/employee');
		}else{
			$this->db->where('id_karyawan',$nik);
			$in=$this->db->delete('log_login_karyawan');
			if ($in) {
				$this->session->set_flashdata('dlog_sc','<label><i class="fa fa-check-circle"></i> Hapus Riwayat Login Berhasil</label><hr class="message-inner-separator">Semua Riwayat Login Karyawan ini berhasil dihapus'); 
			}else{
				$this->session->set_flashdata('dlog_err','<label><i class="fa fa-times-circle"></i> Hapus Riwayat Login Gagal</label><hr class="message-inner-separator">Semua Riwayat Login Karyawan ini Gagal dihapus'); 
			}
			redirect('pages/view_employee/'.$nik1['nik']);
		}
	}
	function del_employee(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$this->db->where('id_karyawan',$kode);
			$in=$this->db->delete('karyawan');
			if ($in) {
				$this->messages->delGood(); 
			}else{
				$this->messages->delFailure();
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/employee');
	}
//            ____  ____    __  __                ||
//           / _  |/ _  \  / / / /                ||
//=======   / /_/ / /_) / / / / /      ===========||
//         / __  / /__) \/ /_/ /                  ||
//        /_/ /_/_______/\____/                   ||
	public function employee()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					if(!empty($this->filter) && $param != 'search'){						
						$wherex = '';
						$c_lvx=1;
						foreach ($this->filter as $key => $bag) {
							$wherex.="bag.kode_bagian='".$bag."'";
							if (count($this->filter) > $c_lvx) {
								$wherex.=' OR ';
							}
							$c_lvx++;
						}
						$wherex = '('.$wherex.')';
						$data=$this->model_karyawan->getListKaryawan('where4cAll',['param'=>'all'],$wherex);
					}else{
						$data=$this->model_karyawan->getListKaryawan('nosearch', ['param'=>'all']);
					}
				}else{
					if(!empty($this->filter)){
						$bagian = $this->input->post('bagian');
						if(empty($bagian)){
							$where4c = '';
							$c_lvx=1;
							foreach ($this->filter as $key => $bag) {
								$where4c.="bag.kode_bagian='".$bag."'";
								if (count($this->filter) > $c_lvx) {
									$where4c.=' OR ';
								}
								$c_lvx++;
							}
							$data=$this->model_karyawan->getListKaryawan('where4cAll',['param'=>'all'],$where4c);
						}else{
							$bagian = $this->input->post('bagian');
							$unit = $this->input->post('unit');
							$bulan = $this->input->post('bulan');
							$tahun = $this->input->post('tahun');
							$where4c = ['bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
							$data=$this->model_karyawan->getListKaryawan('where4c',['param'=>'all'],$where4c);
						}
					}else{
						$bagian = $this->input->post('bagian');
						$unit = $this->input->post('unit');
						$bulan = $this->input->post('bulan');
						$tahun = $this->input->post('tahun');
						$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
						$data=$this->model_karyawan->getListKaryawan('search',$where);
					}
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					unset($access['l_ac']['stt']);
					$var=[
						'id'=>$d->id_karyawan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$awal  = date_create($d->tgl_masuk);
					$akhir = date_create();
					$lama_kerja  = date_diff($awal, $akhir);
					$datax['data'][]=[
						$d->id_karyawan,
						'<a href="'.base_url("pages/view_employee/".$this->codegenerator->encryptChar($d->nik)).'">'.$d->nik.'</a>',
						$d->nama,
						($d->nama_jabatan != null) ? $d->nama_jabatan : $this->otherfunctions->getMark(),
						($d->nama_level_jabatan != null) ? $d->nama_level_jabatan : $this->otherfunctions->getMark(),
						($d->nama_bagian != null) ? $d->nama_bagian : $this->otherfunctions->getMark(),
						($d->nama_level_struktur != null) ? $d->nama_level_struktur : $this->otherfunctions->getMark(),
						($d->nama_loker != null) ? $d->nama_loker : $this->otherfunctions->getMark(),
						($d->nama_grade != null) ? $d->nama_grade.' ('.$d->nama_loker_grade.')': $this->otherfunctions->getMark(),
						$d->id_finger,
						($d->tgl_masuk != null) ? $this->formatter->getDateMonthFormatUser($d->tgl_masuk) : $this->otherfunctions->getMark(),
						$lama_kerja->y.' Tahun, '.$lama_kerja->m.' Bulan',
						($d->tgl_lahir != null) ? $this->formatter->getDateMonthFormatUser($d->tgl_lahir) : $this->otherfunctions->getMark(),
						$properties['tanggal'],
						($d->status != null && $d->status == 1) ? '<i class="fa fa-circle scc" title="Online"></i>' : '<i class="fa fa-circle text-muted" title="Offline"></i>',
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik),
					];
					$no++;
				}
				echo json_encode($datax);
				// $param = $this->input->post('param');
				// if($param == 'all'){
				// 	$data=$this->model_karyawan->getListKaryawan();
				// }else{
				// 	$bagian = $this->input->post('bagian');
				// 	$unit = $this->input->post('unit');
				// 	$bulan = $this->input->post('bulan');
				// 	$tahun = $this->input->post('tahun');
				// 	$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
				// 	$data=$this->model_karyawan->getListKaryawan('search',$where);
				// }
				// $access=$this->codegenerator->decryptChar($this->input->post('access'));
				// $no=1;
				// $datax['data']=[];
				// foreach ($data as $d) {
				// 	unset($access['l_ac']['stt']);
				// 	$var=[
				// 		'id'=>$d->id_karyawan,
				// 		'create'=>$d->create_date,
				// 		'update'=>$d->update_date,
				// 		'access'=>$access,
				// 		'status'=>$d->status,
				// 	];
				// 	$properties=$this->otherfunctions->getPropertiesTable($var);
				// 	$dt_kar_1=[
				// 		$d->id_karyawan,
				// 		'<a href="'.base_url("pages/view_employee/".$this->codegenerator->encryptChar($d->nik)).'">'.$d->nik.'</a>',
				// 		$d->nama,
				// 		($d->nama_jabatan != null) ? $d->nama_jabatan : $this->otherfunctions->getMark(),
				// 	];
				// 	$dt_struktur=$this->model_master->getListLevelStruktur(true,'val');
				// 	$dt_kar_2=[];
				// 	foreach ($dt_struktur as $dt_s){
				// 		$lower=strtolower(str_replace('/','_',$dt_s->nama));
				// 		$lower=strtolower(str_replace('-','_',$lower));
				// 		$lower=strtolower(str_replace(' ','_',$lower));
				// 		$col='nama_'.$lower;
				// 		$col_lokasi='nama_lokasi_'.$lower;
				// 		$dt_kar_2[]=(!empty($d->$col)) ? $d->$col.((!empty($d->$col_lokasi)) ? ' ('.$d->$col_lokasi.')':'') : $this->otherfunctions->getMark();
				// 	}
				// 	$awal  = date_create($d->tgl_masuk);
				// 	$akhir = date_create();
				// 	$lama_kerja  = date_diff($awal, $akhir);
				// 	$dt_kar_3=[
				// 		($d->nama_loker != null) ? $d->nama_loker : $this->otherfunctions->getMark(),
				// 		($d->nama_grade != null) ? $d->nama_grade.' ('.$d->nama_loker_grade.')': $this->otherfunctions->getMark(),
				// 		($d->tgl_masuk != null) ? $this->formatter->getDateMonthFormatUser($d->tgl_masuk) : $this->otherfunctions->getMark(),
				// 		$lama_kerja->y.' Tahun, '.$lama_kerja->m.' Bulan',
				// 		($d->tgl_lahir != null) ? $this->formatter->getDateMonthFormatUser($d->tgl_lahir) : $this->otherfunctions->getMark(),
				// 		$properties['tanggal'],
				// 		($d->status != null && $d->status == 1) ? '<i class="fa fa-circle scc" title="Online"></i>' : '<i class="fa fa-circle text-muted" title="Offline"></i>',
				// 		$properties['aksi'],
				// 	];
				// 	$datax['data'][]=array_merge($dt_kar_1,$dt_kar_2,$dt_kar_3);
				// 	$no++;
				// }
				// echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_karyawan');
				$d=$this->model_karyawan->getEmployeeId($id);
				$nama_grade=(isset($d['nama_loker_grade'])) ? ' ('.$d['nama_loker_grade'].')':$this->otherfunctions->getMark();
				$datax=[
					'id'=>$d['id_karyawan'],
					'nama'=>$d['nama'],
					'nik'=>$d['nik'],
					'loker'=>$d['loker'],
					'foto'=>base_url($d['foto']),
					'nama_loker'=>$d['nama_loker'],
					'grade'=>(isset($d['grade'])) ? $d['nama_grade'].$nama_grade:$this->otherfunctions->getMark(),
					'tgl_masuk'=>$d['tgl_masuk'],
					'gettgl_masuk'=>$this->formatter->getDateMonthFormatUser($d['tgl_masuk']),
					'jabatan'=>$d['jabatan'],
					'nama_jabatan'=>(isset($d['nama_jabatan'])) ? $d['nama_jabatan']:$this->otherfunctions->getMark(),
					'status'=>$d['status'],
					'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d['create_date']),
					'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d['update_date']),
					'create_by'=>$d['create_by'],
					'update_by'=>$d['update_by'],
					'nama_buat'=>(!empty($d['nama_buat'])) ? $d['nama_buat']:$this->otherfunctions->getMark($d['nama_buat']),
					'nama_update'=>(!empty($d['nama_update']))?$d['nama_update']:$this->otherfunctions->getMark($d['nama_update'])
				];
				echo json_encode($datax);
			}elseif($usage=='refresh_employee'){
				$data = $this->model_karyawan->getRefreshKaryawan();
        		echo json_encode($data);
			}elseif($usage=='view_all_wfh'){
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListKaryawan();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListKaryawan('search',$where);
				}
				// $data=$this->model_karyawan->getListKaryawan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					unset($access['l_ac']['stt']);
					$var=[
						'id'=>$d->id_karyawan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$awal  = date_create($d->tgl_masuk);
					$akhir = date_create();
					$lama_kerja  = date_diff($awal, $akhir);
					$datax['data'][]=[
						$d->id_karyawan,
						$d->nik,
						$d->nama,
						($d->nama_jabatan != null) ? $d->nama_jabatan : $this->otherfunctions->getMark(),
						($d->nama_bagian != null) ? $d->nama_bagian : $this->otherfunctions->getMark(),
						($d->nama_loker != null) ? $d->nama_loker : $this->otherfunctions->getMark(),
						($d->hari_kerja_wfh != null) ? $d->hari_kerja_wfh.' Hari' : $this->otherfunctions->getMark(),
						($d->hari_kerja_non_wfh != null) ? $d->hari_kerja_non_wfh.' Hari' : $this->otherfunctions->getMark(),
						($d->wfh != null) ? $this->otherfunctions->getWFH($d->wfh) : $this->otherfunctions->getMark(),
						$properties['tanggal'],
						($d->status != null && $d->status == 1) ? '<i class="fa fa-circle scc" title="Online"></i>' : '<i class="fa fa-circle text-muted" title="Offline"></i>',
						$properties['info'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif($usage=='view_one_wfh'){
				$id = $this->input->post('id_karyawan');
				$d=$this->model_karyawan->getEmployeeId($id);
				$nama_grade=(isset($d['nama_loker_grade'])) ? ' ('.$d['nama_loker_grade'].')':$this->otherfunctions->getMark();
				$datax=[
					'id'=>$d['id_karyawan'],
					'nama'=>$d['nama'],
					'nik'=>$d['nik'],
					'loker'=>$d['loker'],
					'nama_loker'=>$d['nama_loker'],
					'foto'=>base_url($d['foto']),
					'jabatan'=>$d['jabatan'],
					'wfh'=>(isset($d['hari_kerja_wfh'])) ? $d['hari_kerja_wfh'].' Hari':$this->otherfunctions->getMark(),
					'non_wfh'=>(isset($d['hari_kerja_non_wfh'])) ? $d['hari_kerja_non_wfh'].' Hari':$this->otherfunctions->getMark(),
					'jenis_kerja'=>(isset($d['wfh'])) ? $this->otherfunctions->getWFH($d['wfh']):$this->otherfunctions->getMark(),
					'wfh_edit'=>$d['hari_kerja_wfh'],
					'non_wfh_edit'=>$d['hari_kerja_non_wfh'],
					'jenis_kerja_edit'=>$d['wfh'],
					'nama_jabatan'=>(isset($d['nama_jabatan'])) ? $d['nama_jabatan']:$this->otherfunctions->getMark(),
					'status'=>$d['status'],
					'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d['create_date']),
					'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d['update_date']),
					'create_by'=>$d['create_by'],
					'update_by'=>$d['update_by'],
					'nama_buat'=>(!empty($d['nama_buat'])) ? $d['nama_buat']:$this->otherfunctions->getMark($d['nama_buat']),
					'nama_update'=>(!empty($d['nama_update']))?$d['nama_update']:$this->otherfunctions->getMark($d['nama_update'])
				];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	} 
	//======================================== WORK FROM HOME ================================================//
	public function edit_wfh()
	{
		if (!$this->input->is_ajax_request()) 
   			redirect('not_found');
		$id = $this->input->post('id');
		if(!empty($id)){
			$wfh = $this->input->post('wfh');
			$non_wfh = $this->input->post('non_wfh');
			$jenis = $this->input->post('jenis');
			$data=[
				'hari_kerja_wfh'=>$wfh,
				'hari_kerja_non_wfh'=>$non_wfh,
				'wfh'=>$jenis,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//=========================================================================================================================
	//**VIEW info pribadi **//
	public function emp_part_info()
	{
			$nik=$this->uri->segment(3);
			$kar=$this->model_karyawan->getEmployeeNik($nik);
			$jum_anak = $this->db->get_where('karyawan_anak',array('nik'=>$nik))->num_rows(); 
			$jum_pen = $this->model_karyawan->countPendidikan($nik);
			$max=$this->model_karyawan->pendidikan_max($nik);
			$data=array(
				'profile'=>$kar,
				'jumlah_anak'=>(!empty($jum_anak) || $jum_anak != 0) ? $jum_anak: $this->otherfunctions->getCustomMark($jum_anak,'<label class="label label-danger">Belum Punya Anak</label>'),
				'jml_pend'=>$jum_pen,
				'pendidikan_max'=>$max,
			);
		$datax = $this->load->view('_partial/_view_employee_info',$data);
		echo json_encode($datax);
	}
	//** partial employee **//
	public function emppribadi()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
		$nik = $this->input->post('nik');
		$maxJenjanng = $this->model_karyawan->pendidikan_max($nik);
		$mjjang = $this->otherfunctions->getEducate($maxJenjanng['jenjang_pendidikan']);
		$data=$this->model_karyawan->getEmployeeOneNik($nik);
		foreach ($data as $d) {
			$jum_anak = $this->db->get_where('karyawan_anak',array('nik'=>$nik))->num_rows();
			if ($d->status_suspen == 1) {
				$stt_sus = '<button type="button" class="pull-right stat err" title="Di Suspend" href="javascript:void(0)" onclick=status_suspen('.$d->id_karyawan.',0)><i class="fa fa-toggle-off"></i></button>';
			}else{
				$stt_sus = '<button type="button" class="pull-right stat scc" title="Tidak Di Suspend" href="javascript:void(0)" onclick=status_suspen('.$d->id_karyawan.',1)><i class="fa fa-toggle-on"></i></button>';
			}
			$datax=[
				'id_karyawan'=>$d->id_karyawan,
				'nik'=>$d->nik,
				'finger_code'=>$d->id_finger,
				'no_ktp'=>$d->no_ktp,
				'nama'=>$d->nama,
				'grade'=>$d->nama_grade.' ('.$d->nama_loker_grade.')',
				'no_hp'=>$d->no_hp,
				'foto'=>$d->foto,
				'email'=>$d->email,
				'kelamin'=>$d->kelamin,
				'status_karyawan'=>$d->nama_status,
				'tempat_lahir'=>$d->tempat_lahir,
				'tgl_lahir'=>$this->formatter->getDateMonthFormatUser($d->tgl_lahir),
				'update_date'=>$this->formatter->getDateTimeFormatUser($d->update_date),
				'nama_loker'=>$d->nama_loker,
				'nama_jbt'=>$d->nama_jabatan,
				'nama_bgn'=>$d->nama_bagian,
				'nama_lvl'=>$d->nama_level_jabatan,
				'status_suspen'=>$stt_sus,
				'jumlah_anak'=>(!empty($jum_anak) || $jum_anak != 0) ? $jum_anak.' Anak': $this->otherfunctions->getCustomMark($jum_anak,'<label class="label label-danger">Belum Punya Anak</label>'),
				'maxJenjang'=> (!empty($mjjang)) ? $mjjang: $this->otherfunctions->getCustomMark($mjjang,'<label class="label label-danger">Jenjang Tidak Ada</label>'),
				'maxSekolah'=> (!empty($maxJenjanng['nama_sekolah'])) ? $maxJenjanng['nama_sekolah']: $this->otherfunctions->getCustomMark($maxJenjanng['nama_sekolah'],'<label class="label label-danger">Universitas Tidak Ada</label>'),
				'MaxJurusan'=> (!empty($maxJenjanng['jurusan'])) ? $maxJenjanng['jurusan'] : $this->otherfunctions->getCustomMark($maxJenjanng['jurusan'],'<label class="label label-danger">Jurusan Tidak Ada</label>'),
			];
		}
		echo json_encode($datax);
	}
	public function emp_part_update()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			//'status'=>$this->model_master->getListStatusKaryawan(),
			'kelamin'=> $this->otherfunctions->getGenderList(),
			'nikah'	=> $this->otherfunctions->getStatusNikahList(),
			'darah'=> $this->otherfunctions->getBloodList(),
			'agama'=> $this->otherfunctions->getReligionList(),
			'pendidikan'=> $this->otherfunctions->getEducateList(),
			'bahasa'=>$this->otherfunctions->getBahasaList(),
			'status_pajak'=> $this->otherfunctions->getStatusPajakList(),
			'baju'=> $this->otherfunctions->getUkuranBajuList(),
			'metode_pph'=> $this->otherfunctions->getMetodePerhitunganList(),
			'gaji_pokok'=> $this->otherfunctions->getJenisGajiList(),
			'profile'=>$kar,
			'access'=>$this->access,
		);
		$this->load->view('_partial/_view_employee_update',$data);
	}

	//------------------------------------------------------------------------------------------------------//
	//**Update info pribadi **//
	public function emp_part_pribadi()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			//'status'=>$this->model_master->getListStatusKaryawan(),
			'kelamin'=> $this->otherfunctions->getGenderList(),
			'darah'=> $this->otherfunctions->getBloodList(),
			'nikah'	=> $this->otherfunctions->getStatusNikahList(),
			'agama'=> $this->otherfunctions->getReligionList(),
			'status_pajak'=> $this->otherfunctions->getStatusPajakList(),
			'baju'=> $this->otherfunctions->getUkuranBajuList(),
			'profile'=>$kar,
		);
		$this->load->view('_partial/_view_employee_pribadi',$data);
	}
	public function edit_pribadi()
	{
		if (!$this->input->is_ajax_request()) 
   			redirect('not_found');
	   	$id=$this->input->post('id_karyawan');
	   	$kelamin=$this->input->post('kelamin');
		$nik=$this->input->post('username');
		$kar=$this->db->get_where('karyawan',array('id_karyawan' => $id))->row_array();
		if($id!=''){
		   	if ($kar['foto'] == 'asset/img/user-photo/user.png' || $kar['foto'] == 'asset/img/user-photo/userf.png') {
				if ($kelamin == 'l' || $kelamin == 'L') {
					$foto='asset/img/user-photo/user.png';
				}else{
					$foto='asset/img/user-photo/userf.png';
				}
			}else{
				$foto=$kar['foto'];
			}
			if($nik!=$kar['nik']){
				$anak=$this->db->get_where('karyawan_anak',['nik'=>$kar['nik']])->result();
				foreach ($anak as $ank) {
					$data_ank=['nik'=>$nik];
					$data_anak=array_merge($data_ank,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_anak,'karyawan_anak',['id_anak'=>$ank->id_anak]);
				}
				$saudara=$this->db->get_where('karyawan_saudara',['nik'=>$kar['nik']])->result();
				foreach ($saudara as $sau) {
					$data_sau=['nik'=>$nik];
					$data_saudara=array_merge($data_sau,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_saudara,'karyawan_saudara',['id_saudara'=>$sau->id_saudara]);
				}
				$org=$this->db->get_where('karyawan_organisasi',['nik'=>$kar['nik']])->result();
				foreach ($org as $orga) {
					$data_org=['nik'=>$nik];
					$data_organisasi=array_merge($data_org,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_organisasi,'karyawan_organisasi',['id_k_organisasi'=>$orga->id_k_organisasi]);
				}
				$bahasa=$this->db->get_where('karyawan_bahasa',['nik'=>$kar['nik']])->result();
				foreach ($bahasa as $bhs) {
					$data_bhs=['nik'=>$nik];
					$data_bahasa=array_merge($data_bhs,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_bahasa,'karyawan_bahasa',['id_k_bahasa'=>$bhs->id_k_bahasa]);
				}
				$pendidikan=$this->db->get_where('karyawan_pendidikan',['nik'=>$kar['nik']])->result();
				foreach ($pendidikan as $pend) {
					$data_pend=['nik'=>$nik];
					$data_pendidikan=array_merge($data_pend,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_pendidikan,'karyawan_pendidikan',['id_k_pendidikan'=>$pend->id_k_pendidikan]);
				}
				$pnf=$this->db->get_where('karyawan_pnf',['nik'=>$kar['nik']])->result();
				foreach ($pnf as $pn) {
					$data_pn=['nik'=>$nik];
					$data_pnf=array_merge($data_pn,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_pnf,'karyawan_pnf',['id_k_pnf'=>$pn->id_k_pnf]);
				}
				$penghargaan=$this->db->get_where('karyawan_penghargaan',['nik'=>$kar['nik']])->result();
				foreach ($penghargaan as $peng) {
					$data_peng=['nik'=>$nik];
					$data_penghargaan=array_merge($data_peng,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_penghargaan,'karyawan_penghargaan',['id_k_penghargaan'=>$peng->id_k_penghargaan]);
				}
				$perjanjian_kerja=$this->db->get_where('data_perjanjian_kerja',['nik'=>$kar['nik']])->result();
				foreach ($perjanjian_kerja as $pj) {
					$data_pj=['nik'=>$nik];
					$data_perj=array_merge($data_pj,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_perj,'data_perjanjian_kerja',['id_p_kerja'=>$pj->id_p_kerja]);
				}
			}
	   		$dataa=array(
	   			'nik'=>$nik,
				'id_finger'=>$this->input->post('finger_code'),
				'no_ktp'=>$this->input->post('no_ktp'),
				'nama'=>strtoupper($this->input->post('nama')),
				'alamat_asal_jalan'=>$this->input->post('alamat_asal_jalan'),
				'alamat_asal_desa'=>$this->input->post('alamat_asal_desa'),
				'alamat_asal_kecamatan'=>$this->input->post('alamat_asal_kecamatan'),
				'alamat_asal_kabupaten'=>$this->input->post('alamat_asal_kabupaten'),
				'alamat_asal_provinsi'=>$this->input->post('alamat_asal_provinsi'),
				'alamat_asal_pos'=>$this->input->post('alamat_asal_pos'),
				'alamat_sekarang_jalan'=>$this->input->post('alamat_sekarang_jalan'),
				'alamat_sekarang_desa'=>$this->input->post('alamat_sekarang_desa'),
				'alamat_sekarang_kecamatan'=>$this->input->post('alamat_sekarang_kecamatan'),
				'alamat_sekarang_kabupaten'=>$this->input->post('alamat_sekarang_kabupaten'),
				'alamat_sekarang_provinsi'=>$this->input->post('alamat_sekarang_provinsi'),
				'alamat_sekarang_pos'=>$this->input->post('alamat_sekarang_pos'),
				'berat_badan'=>$this->input->post('berat'),
				'tinggi_badan'=>$this->input->post('tinggi'),
				'no_hp'=>$this->input->post('no_hp'),
				'npwp'=>$this->input->post('npwp'),
				'bpjstk'=>$this->input->post('bpjstk'),
				'bpjskes'=>$this->input->post('bpjskes'),
				'rekening'=>$this->input->post('rekening'),
				'email'=>$this->input->post('email'),
				'nama_bank'=>$this->input->post('bank'),
				'baju'=>$this->input->post('baju'),
				'metode_pph'=>$this->input->post('metode_pph'),
				'sepatu'=>$this->input->post('sepatu'),
				'kelamin'=>$kelamin,
				'foto'=>$foto,
				'agama'=>$this->input->post('agama'),
				'gol_darah'=>$this->input->post('gol_darah'),
				'status_nikah'=>$this->input->post('nikah'),
				'status_pajak'=>$this->input->post('status_pajak'),
				'tempat_lahir'=>$this->input->post('tempat_lahir'),
				'tgl_lahir'=>$this->formatter->getDateFormatDb($this->input->post('tgl_lahir')),
				'tgl_masuk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_masuk')),
				'golongan'=>$this->input->post('golongan'),
			);
			$data=array_merge($dataa,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
			if($nik!=$kar['nik']){
				$red = base_url('pages/view_employee/'.$this->codegenerator->encryptChar($nik));
				$url['linkx']=$red;
	            $datax=array_merge($url,$this->messages->customGood('NIK Berhasil Dirubah.'));
			}
	   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function emp_part_keluarga()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'access'=>$this->access,
			'profile'=>$kar,
			'pendidikan'=> $this->otherfunctions->getEducateList(),
		);
		$this->load->view('_partial/_view_employee_keluarga',$data);
	}
	public function emp_part_ayah()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'pendidikan'=> $this->otherfunctions->getEducateList(),
		);
		$this->load->view('_partial/_view_employee_ayah',$data);
	}
	public function edit_ayah()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
	   	$id=$this->input->post('id_karyawan');
	   	if($id!=''){
	   		$data=array(
				'nama_ayah'=>strtoupper($this->input->post('nama_ayah')),
				'tempat_lahir_ayah'=>$this->input->post('tempat_lahir_ayah'),
				'tanggal_lahir_ayah'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_ayah')),
				'no_hp_ayah'=>$this->input->post('no_hp_ayah'),
				'pendidikan_terakhir_ayah'=>$this->input->post('pendidikan_terakhir_ayah'),
				'alamat_ayah'=>$this->input->post('alamat_ayah'),
				'desa_ayah'=>$this->input->post('desa_ayah'),
				'kecamatan_ayah'=>$this->input->post('kecamatan_ayah'),
				'kabupaten_ayah'=>$this->input->post('kabupaten_ayah'),
				'provinsi_ayah'=>$this->input->post('provinsi_ayah'),
				'kode_pos_ayah'=>$this->input->post('kode_pos_ayah'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
   		}else{
			$datax = $this->messages->notValidParam(); 
		}
	  echo json_encode($datax);
	}
	public function emp_part_ibu()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'pendidikan'=> $this->otherfunctions->getEducateList(),
		);
		$this->load->view('_partial/_view_employee_ibu',$data);
	}
	public function edit_ibu()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
	   	$id=$this->input->post('id_karyawan');
	   	if($id!=''){
	   		$data=array(
				'nama_ibu'=>strtoupper($this->input->post('nama_ibu')),
				'tempat_lahir_ibu'=>$this->input->post('tempat_lahir_ibu'),
				'tanggal_lahir_ibu'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_ibu')),
				'no_hp_ibu'=>$this->input->post('no_hp_ibu'),
				'pendidikan_terakhir_ibu'=>$this->input->post('pendidikan_terakhir_ibu'),
				'alamat_ibu'=>$this->input->post('alamat_ibu'),
				'desa_ibu'=>$this->input->post('desa_ibu'),
				'kecamatan_ibu'=>$this->input->post('kecamatan_ibu'),
				'kabupaten_ibu'=>$this->input->post('kabupaten_ibu'),
				'provinsi_ibu'=>$this->input->post('provinsi_ibu'),
				'kode_pos_ibu'=>$this->input->post('kode_pos_ibu'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
   		}else{
			$datax = $this->messages->notValidParam(); 
		}
	  echo json_encode($datax);
	}
	public function emp_part_anak()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'access'=>$this->access,
			'profile'=>$kar,
			'pendidikan'=> $this->otherfunctions->getEducateList(),
			'kelamin'=> $this->otherfunctions->getGenderList(),
		);
		$this->load->view('_partial/_view_employee_anak',$data);
	}
	//--EMP ANAK--//
	public function emp_anak(){
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListAnak($nik);
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_anak,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$ttl = $d->tempat_lahir_anak.', '.$this->formatter->getDateMonthFormatUser($d->tanggal_lahir_anak);
					$gender = $this->otherfunctions->getGender($d->kelamin_anak);
					$educa = $this->otherfunctions->getEducate($d->pendidikan_anak);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_anak('.$d->id_anak.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>
						<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_anak('.$d->id_anak.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
					$datax['data'][]=[
						$d->id_anak,
						(!empty($d->nama_anak)) ? $d->nama_anak:$this->otherfunctions->getMark($d->nama_anak),
						(!empty($ttl)) ? $ttl:$this->otherfunctions->getMark($ttl),
						(!empty($gender)) ? $gender:$this->otherfunctions->getMark($gender),
						(!empty($educa)) ? $educa:$this->otherfunctions->getMark($educa),
						(!empty($d->no_telp)) ? $d->no_telp:$this->otherfunctions->getMark($d->no_telp),
						$aksi,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_anak');
				$data=$this->model_karyawan->getAnak($id,$nik);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_anak,
						'nama_anak'=>$d->nama_anak,
						'nik'=>$d->nik,
						'kelamin_anak'=>$d->kelamin_anak,
						'getkelamin_anak'=>$this->otherfunctions->getGender($d->kelamin_anak),
						'tempat_lahir_anak'=>$d->tempat_lahir_anak,
						'tanggal_lahir_anak'=>$this->formatter->getDateFormatUser($d->tanggal_lahir_anak),
						'getTTL'=>$d->tempat_lahir_anak.', '.$this->formatter->getDateMonthFormatUser($d->tanggal_lahir_anak),
						'pendidikan_anak'=>$d->pendidikan_anak,
						'getPendidikan'=>$this->otherfunctions->getEducate($d->pendidikan_anak),
						'no_telp'=>$d->no_telp,
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
	public function add_anak()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
		$nik=$this->input->post('nik');
		if($nik!=''){
			$data=array(
					'nik'=>$nik,
					'nama_anak'=>$this->input->post('nama_anak'),
					'kelamin_anak'=>$this->input->post('kelamin_anak'),
					'tempat_lahir_anak'=>$this->input->post('tempat_lahir_anak'),
					'tanggal_lahir_anak'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_anak')),
					'pendidikan_anak'=>$this->input->post('pendidikan_anak'),
					'no_telp'=>$this->input->post('no_telp'),
			);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'karyawan_anak');
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edit_anak()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
		$nik=$this->input->post('nik');
		$id=$this->input->post('id_anak');
		if($nik!='' || $id!=''){
			$data=array(
					'nama_anak'=>$this->input->post('nama_anak'),
					'kelamin_anak'=>$this->input->post('kelamin_anak'),
					'tempat_lahir_anak'=>$this->input->post('tempat_lahir_anak'),
					'tanggal_lahir_anak'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_anak')),
					'pendidikan_anak'=>$this->input->post('pendidikan_anak'),
					'no_telp'=>$this->input->post('no_telp'),
			);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$where = array('id_anak' => $id, 'nik' => $nik );
				$datax = $this->model_global->updateQuery($data,'karyawan_anak',$where);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function emp_part_saudara()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'access'=>$this->access,
			'profile'=>$kar,
			'pendidikan'=> $this->otherfunctions->getEducateList(),
			'kelamin'=> $this->otherfunctions->getGenderList(),
		);
		$this->load->view('_partial/_view_employee_saudara',$data);
	}
	//** saudara **//
	public function empsaudara()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->saudara($nik);
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_saudara,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$ttl = $d->tempat_lahir_saudara.', '.$this->formatter->getDateMonthFormatUser($d->tanggal_lahir_saudara);
					$gender = $this->otherfunctions->getGender($d->jenis_kelamin_saudara);
					$educa = $this->otherfunctions->getEducate($d->pendidikan_saudara);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_saudara('.$d->id_saudara.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>
						<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_saudara('.$d->id_saudara.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
					$datax['data'][]=[
						$d->id_saudara,
						(!empty($d->nama_saudara)) ? $d->nama_saudara:$this->otherfunctions->getMark($d->nama_saudara),
						(!empty($ttl)) ? $ttl:$this->otherfunctions->getMark($ttl),
						(!empty($gender)) ? $gender:$this->otherfunctions->getMark($gender),
						(!empty($educa)) ? $educa:$this->otherfunctions->getMark($educa),
						(!empty($d->no_telp_saudara)) ? $d->no_telp_saudara:$this->otherfunctions->getMark($d->no_telp_saudara),
						$aksi
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_saudara');
				$data=$this->model_karyawan->getSaudara($id,$nik);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_saudara,
						'nama_saudara'=>$d->nama_saudara,
						'nik'=>$d->nik,
						'jenis_kelamin_saudara'=>$d->jenis_kelamin_saudara,
						'getkelamin_saudara'=>$this->otherfunctions->getGender($d->jenis_kelamin_saudara),
						'tempat_lahir_saudara'=>$d->tempat_lahir_saudara,
						'tanggal_lahir_saudara'=>$this->formatter->getDateFormatUser($d->tanggal_lahir_saudara),
						'getTTL'=>$d->tempat_lahir_saudara.', '.$this->formatter->getDateMonthFormatUser($d->tanggal_lahir_saudara),
						'pendidikan_saudara'=>$d->pendidikan_saudara,
						'getPendidikan'=>$this->otherfunctions->getEducate($d->pendidikan_saudara),
						'no_telp_saudara'=>$d->no_telp_saudara,
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
	public function add_saudara()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
   		$nik=$this->input->post('nik');
		if($nik!=''){
			$data=array(
					'nik'=>$nik,
					'nama_saudara'=>$this->input->post('nama_saudara'),
					'jenis_kelamin_saudara'=>$this->input->post('jenis_kelamin_saudara'),
					'tempat_lahir_saudara'=>$this->input->post('tempat_lahir_saudara'),
					'tanggal_lahir_saudara'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_saudara')),
					'pendidikan_saudara'=>$this->input->post('pendidikan_saudara'),
					'no_telp_saudara'=>$this->input->post('no_telp_saudara'),
			);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'karyawan_saudara');
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edit_saudara()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
		$nik=$this->input->post('nik');
		$id=$this->input->post('id_saudara');
		if($nik!='' || $id!=''){
			$data=array(
					'nama_saudara'=>$this->input->post('nama_saudara'),
					'jenis_kelamin_saudara'=>$this->input->post('kelamin_saudara'),
					'tempat_lahir_saudara'=>$this->input->post('tempat_lahir_saudara'),
					'tanggal_lahir_saudara'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_saudara')),
					'pendidikan_saudara'=>$this->input->post('pendidikan_saudara'),
					'no_telp_saudara'=>$this->input->post('no_telp'),
			);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$where = array('id_saudara' => $id, 'nik' => $nik );
				$datax = $this->model_global->updateQuery($data,'karyawan_saudara',$where);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function emp_part_pasangan()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'pendidikan'=> $this->otherfunctions->getEducateList(),
		);
		$this->load->view('_partial/_view_employee_pasangan',$data);
	}
	public function edit_pasangan()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
	   	$id=$this->input->post('id_karyawan');
	   	if($id!=''){
	   		$data=array(
				'nama_pasangan'=>strtoupper($this->input->post('nama_pasangan')),
				'tempat_lahir_pasangan'=>$this->input->post('tempat_lahir_pasangan'),
				'tanggal_lahir_pasangan'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_lahir_pasangan')),
				'no_hp_pasangan'=>$this->input->post('no_hp_pasangan'),
				'pendidikan_terakhir_pasangan'=>$this->input->post('pendidikan_terakhir_pasangan'),
				'alamat_pasangan'=>$this->input->post('alamat_pasangan'),
				'desa_pasangan'=>$this->input->post('desa_pasangan'),
				'kecamatan_pasangan'=>$this->input->post('kecamatan_pasangan'),
				'kabupaten_pasangan'=>$this->input->post('kabupaten_pasangan'),
				'provinsi_pasangan'=>$this->input->post('provinsi_pasangan'),
				'kode_pos_pasangan'=>$this->input->post('kode_pos_pasangan'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
   		}else{
			$datax = $this->messages->notValidParam(); 
		}
	  echo json_encode($datax);
	}
	public function emp_part_pendidikan()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'access'=>$this->access,
			'profile'=>$kar,
			'pendidikan'=> $this->otherfunctions->getEducateList(),
		);
		$this->load->view('_partial/_view_employee_pendidikan',$data);
	}
	public function emp_part_formal()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'access'=>$this->access,
			'profile'=>$kar,
			'pendidikan'=> $this->otherfunctions->getEducateList(),
		);
		$this->load->view('_partial/_view_employee_formal',$data);
	}
	//** pendidikan formal **//
	public function emppendidikan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->pendidikan($nik);
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_k_pendidikan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tgl_masuk = $this->formatter->getDateMonthFormatUser($d->tahun_masuk);
					$tgl_keluar = $this->formatter->getDateMonthFormatUser($d->tahun_keluar);
					$educa = $this->otherfunctions->getEducate($d->jenjang_pendidikan);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_formal('.$d->id_k_pendidikan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>
						<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_formal('.$d->id_k_pendidikan.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
					$datax['data'][]=[
						$d->id_k_pendidikan,
						$educa,
						(!empty($d->nama_sekolah)) ? $d->nama_sekolah:$this->otherfunctions->getMark($d->nama_sekolah),
						(!empty($d->jurusan)) ? $d->jurusan:$this->otherfunctions->getMark($d->jurusan),
						$aksi,
						(!empty($d->fakultas)) ? $d->fakultas:$this->otherfunctions->getMark($d->fakultas),
						$tgl_masuk,
						$tgl_keluar,
						$d->alamat_sekolah
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_k_pendidikan');
				$data=$this->model_karyawan->getPendidikan($id,$nik);
				$maxJenjanng = $this->model_karyawan->pendidikan_max($nik);
				$mjjang = $this->otherfunctions->getEducate($maxJenjanng['jenjang_pendidikan']);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_k_pendidikan,
						'jenjang_pendidikan'=>$d->jenjang_pendidikan,
						'getjenjang_pendidikan'=>$this->otherfunctions->getEducate($d->jenjang_pendidikan),
						'nama_sekolah'=>$d->nama_sekolah,
						'jurusan'=>$d->jurusan,
						'fakultas'=>$d->fakultas,
						'tahun_masuk'=>$d->tahun_masuk,
						'gettahun_masuk'=>$this->formatter->getDateFormatUser($d->tahun_masuk),
						'getvtahun_masuk'=>$this->formatter->getDateMonthFormatUser($d->tahun_masuk),
						'tahun_keluar'=>$d->tahun_keluar,
						'gettahun_keluar'=>$this->formatter->getDateFormatUser($d->tahun_keluar),
						'getvtahun_keluar'=>$this->formatter->getDateMonthFormatUser($d->tahun_keluar),
						'alamat_sekolah'=>$d->alamat_sekolah,
						'nik'=>$d->nik,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'maxJenjang'=> (!empty($mjjang)) ? $mjjang: $this->otherfunctions->getCustomMark($mjjang,'<label class="label label-danger">Jenjang Tidak Ada</label>'),
						'maxSekolah'=> (!empty($maxJenjanng['nama_sekolah'])) ? $maxJenjanng['nama_sekolah']: $this->otherfunctions->getCustomMark($maxJenjanng['nama_sekolah'],'<label class="label label-danger">Universitas Tidak Ada</label>'),
						'MaxJurusan'=> (!empty($maxJenjanng['jurusan'])) ? $maxJenjanng['jurusan'] : $this->otherfunctions->getCustomMark($maxJenjanng['jurusan'],'<label class="label label-danger">Jurusan Tidak Ada</label>'),
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_formal()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
   		$nik=$this->input->post('nik');
   		if($nik!=''){
   		$data=array(
				'nik'=>$nik,
				'jenjang_pendidikan'=>$this->input->post('jenjang_pendidikan'),
				'nama_sekolah'=>$this->input->post('nama_sekolah'),
				'jurusan'=>$this->input->post('jurusan'),
				'fakultas'=>$this->input->post('fakultas'),
				'tahun_masuk'=>$this->formatter->getDateFormatDb($this->input->post('tahun_masuk')),
				'tahun_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tahun_keluar')),
				'alamat_sekolah'=>$this->input->post('alamat_sekolah'),
   			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'karyawan_pendidikan');
   		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edit_formal()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
   	$nik=$this->input->post('nik');
   	$id=$this->input->post('id_k_pendidikan');
   	if($nik!='' || $id!=''){
   		$data=array(
				'jenjang_pendidikan'=>$this->input->post('jenjang_pendidikan'),
				'nama_sekolah'=>$this->input->post('nama_sekolah'),
				'jurusan'=>$this->input->post('jurusan'),
				'fakultas'=>$this->input->post('fakultas'),
				'tahun_masuk'=>$this->formatter->getDateFormatDb($this->input->post('tahun_masuk')),
				'tahun_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tahun_keluar')),
				'alamat_sekolah'=>$this->input->post('alamat_sekolah'),
   		);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$where = array('id_k_pendidikan' => $id, 'nik' => $nik );
			$datax = $this->model_global->updateQuery($data,'karyawan_pendidikan',$where);
   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}

	//** pendidikan non formal **//
	public function emp_part_n_formal()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'access'=>$this->access,
		);
		$this->load->view('_partial/_view_employee_n_formal',$data);
	}
	public function emp_nonformal()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->pnf($nik);
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_k_pnf,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tgl_masuk = $this->formatter->getDateMonthFormatUser($d->tanggal_masuk_pnf);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_nformal('.$d->id_k_pnf.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>
						<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_nformal('.$d->id_k_pnf.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
					$datax['data'][]=[
						$d->id_k_pnf,
						$d->nama_pnf,
						$d->sertifikat_pnf,
						$d->nama_lembaga_pnf,
						$aksi
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_k_pnf');
				$data=$this->model_karyawan->getPnf($id,$nik);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_k_pnf,
						'nama_pnf'=>$d->nama_pnf,
						'tanggal_masuk_pnf'=>$d->tanggal_masuk_pnf,
						'gettanggal_masuk_pnf'=>$this->formatter->getDateFormatUser($d->tanggal_masuk_pnf),
						'getvtanggal_masuk_pnf'=>$this->formatter->getDateMonthFormatUser($d->tanggal_masuk_pnf),
						'sertifikat_pnf'=>$d->sertifikat_pnf,
						'nama_lembaga_pnf'=>$d->nama_lembaga_pnf,
						'alamat_pnf'=>$d->alamat_pnf,
						'keterangan_pnf'=>$d->keterangan_pnf,
						'nik'=>$d->nik,
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
	public function add_nformal()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
   	$nik=$this->input->post('nik');
   	if($nik!=''){
   		$data=array(
				'nik'=>$nik,
				'nama_pnf'=>$this->input->post('nama_pnf'),
				'tanggal_masuk_pnf'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_masuk_pnf')),
				'sertifikat_pnf'=>$this->input->post('sertifikat_pnf'),
				'nama_lembaga_pnf'=>$this->input->post('nama_lembaga_pnf'),
				'alamat_pnf'=>$this->input->post('alamat_pnf'),
				'keterangan_pnf'=>$this->input->post('keterangan_pnf'),
   		);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'karyawan_pnf');
   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edit_nformal()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
   	$nik=$this->input->post('nik');
   	$id=$this->input->post('id_k_pnf');
   	if($nik!='' || $id!=''){
   		$data=array(
				'nama_pnf'=>$this->input->post('nama_pnf'),
				'tanggal_masuk_pnf'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_masuk_pnf')),
				'sertifikat_pnf'=>$this->input->post('sertifikat_pnf'),
				'nama_lembaga_pnf'=>$this->input->post('nama_lembaga_pnf'),
				'alamat_pnf'=>$this->input->post('alamat_pnf'),
				'keterangan_pnf'=>$this->input->post('keterangan_pnf'),
   		);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$where = array('id_k_pnf' => $id, 'nik' => $nik );
			$datax = $this->model_global->updateQuery($data,'karyawan_pnf',$where);
   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function emp_part_organisasi()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'access'=>$this->access,
		);
		$this->load->view('_partial/_view_employee_organisasi',$data);
	}
	//------------------------------------------------------------------------------------------------------//
	//** organisasi **//
	public function emp_org()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->organisasi($nik);
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_k_organisasi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tahun_masuk = $this->formatter->getDateMonthFormatUser($d->tahun_masuk);
					$tahun_keluar = $this->formatter->getDateMonthFormatUser($d->tahun_keluar);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_org('.$d->id_k_organisasi.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>
						<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_org('.$d->id_k_organisasi.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
					$datax['data'][]=[
						$d->id_k_organisasi,
						$d->nama_organisasi,
						$tahun_masuk,
						$tahun_keluar,
						$d->jabatan_org,
						$aksi
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_k_organisasi');
				$data=$this->model_karyawan->getOrganisasi($id,$nik);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_k_organisasi,
						'nama_organisasi'=>$d->nama_organisasi,
						'tahun_masuk'=>$d->tahun_masuk,
						'gettahun_masuk'=>$this->formatter->getDateFormatUser($d->tahun_masuk),
						'getvtahun_masuk'=>$this->formatter->getDateMonthFormatUser($d->tahun_masuk),
						'tahun_keluar'=>$d->tahun_keluar,
						'gettahun_keluar'=>$this->formatter->getDateFormatUser($d->tahun_keluar),
						'getvtahun_keluar'=>$this->formatter->getDateMonthFormatUser($d->tahun_keluar),
						'jabatan_org'=>$d->jabatan_org,
						'nik'=>$d->nik,
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
	public function add_org()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
   	$nik=$this->input->post('nik');
   	if($nik!=''){
   		$data=array(
				'nik'=>$nik,
				'nama_organisasi'=>$this->input->post('nama_organisasi'),
				'tahun_masuk'=>$this->formatter->getDateFormatDb($this->input->post('tahun_masuk')),
				'tahun_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tahun_keluar')),
				'jabatan_org'=>$this->input->post('jabatan_org'),
   		);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'karyawan_organisasi');
   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edit_org()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
   	$nik=$this->input->post('nik');
   	$id=$this->input->post('idorg');
   	if($nik!='' || $id!=''){
   		$data=array(
				'nama_organisasi'=>$this->input->post('nama_organisasi'),
				'tahun_masuk'=>$this->formatter->getDateFormatDb($this->input->post('tahun_masuk')),
				'tahun_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tahun_keluar')),
				'jabatan_org'=>$this->input->post('jabatan_org'),
   		);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$where = array('id_k_organisasi' => $id, 'nik' => $nik );
			$datax = $this->model_global->updateQuery($data,'karyawan_organisasi',$where);
   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}

//** Penghargaan **//
	public function emp_part_penghargaan()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'access'=>$this->access,
		);
		$this->load->view('_partial/_view_employee_penghargaan',$data);
	}
	public function emp_hrg()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->penghargaan($nik);
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_k_penghargaan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tanggal = $this->formatter->getDateMonthFormatUser($d->tahun);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_hrg('.$d->id_k_penghargaan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>
						<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_hrg('.$d->id_k_penghargaan.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
					$datax['data'][]=[
						$d->id_k_penghargaan,
						$d->nama_penghargaan,
						$tanggal,
						$d->peringkat,
						$d->penyelenggara,
						$aksi
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_k_penghargaan');
				$data=$this->model_karyawan->getPenghargaan($id,$nik);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_k_penghargaan,
						'nama_penghargaan'=>$d->nama_penghargaan,
						'tanggalv'=>$this->formatter->getDateMonthFormatUser($d->tahun),
						'peringkat'=>$d->peringkat,
						'yg_menetapkan'=>$d->yg_menetapkan,
						'penyelenggara'=>$d->penyelenggara,
						'keterangan'=>$d->keterangan,
						'tanggal'=>$this->formatter->getDateFormatUser($d->tahun),
						'nik'=>$d->nik,
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
	public function add_hrg()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
	   	$nik=$this->input->post('nik');
	   	if($nik!=''){
	   		$data=array(
					'nik'=>$nik,
					'nama_penghargaan'=>$this->input->post('nama_penghargaan'),
					'tahun'=>$this->formatter->getDateFormatDb($this->input->post('tanggal')),
					'peringkat'=>$this->input->post('peringkat'),
					'yg_menetapkan'=>$this->input->post('yg_menetapkan'),
					'penyelenggara'=>$this->input->post('penyelenggara'),
					'keterangan'=>$this->input->post('keterangan'),
	   		);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'karyawan_penghargaan');
	   	}else{
				$datax = $this->messages->notValidParam(); 
			}
			echo json_encode($datax);
	}
	public function edit_hrg()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
	   	$nik=$this->input->post('nik');
	   	$id=$this->input->post('idhrg');
	   	if($nik!='' || $id!=''){
	   		$data=array(
					'nama_penghargaan'=>$this->input->post('nama_penghargaan'),
					'tahun'=>$this->formatter->getDateFormatDb($this->input->post('tanggal')),
					'peringkat'=>$this->input->post('peringkat'),
					'yg_menetapkan'=>$this->input->post('yg_menetapkan'),
					'penyelenggara'=>$this->input->post('penyelenggara'),
					'keterangan'=>$this->input->post('keterangan'),
	   		);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$where = array('id_k_penghargaan' => $id, 'nik' => $nik );
				$datax = $this->model_global->updateQuery($data,'karyawan_penghargaan',$where);
	   	}else{
				$datax = $this->messages->notValidParam(); 
			}
			echo json_encode($datax);
	}
	public function emp_part_bahasa()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'access'=>$this->access,
			'bahasa'=>$this->otherfunctions->getBahasaList(),
			'radio'=>$this->otherfunctions->getRadioList(),
		);
		$this->load->view('_partial/_view_employee_bahasa',$data);
	}
	public function empbahasa()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->bahasa($nik);
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_k_bahasa,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$bahasa = $this->otherfunctions->getBahasa($d->bahasa);
					$membaca = $this->otherfunctions->getRadio($d->membaca);
					$menulis = $this->otherfunctions->getRadio($d->menulis);
					$berbicara = $this->otherfunctions->getRadio($d->berbicara);
					$mendengar = $this->otherfunctions->getRadio($d->mendengar);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_bahasa('.$d->id_k_bahasa.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>
						<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_bahasa('.$d->id_k_bahasa.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
					$datax['data'][]=[
						$d->id_k_bahasa,
						$bahasa,
						$membaca,
						$menulis,
						$berbicara,
						$mendengar,
						$aksi,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_k_bahasa');
				$data=$this->model_karyawan->getBahasa($id,$nik);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_k_bahasa,
						'bahasa'=>$d->bahasa,
						'bahasav'=>$this->otherfunctions->getBahasa($d->bahasa),
						'membacav'=>$this->otherfunctions->getRadio($d->membaca),
						'menulisv'=>$this->otherfunctions->getRadio($d->menulis),
						'berbicarav'=>$this->otherfunctions->getRadio($d->berbicara),
						'mendengarv'=>$this->otherfunctions->getRadio($d->mendengar),
						'membaca'=>$d->membaca,
						'menulis'=>$d->menulis,
						'berbicara'=>$d->berbicara,
						'mendengar'=>$d->mendengar,
						'nik'=>$d->nik,
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
	public function add_bahasa()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
	   	$nik=$this->input->post('nik');
	   	if($nik!=''){
	   		$data=array(
					'nik'=>$nik,
					'bahasa'=>$this->input->post('bahasa'),
					'membaca'=>$this->input->post('membaca'),
					'menulis'=>$this->input->post('menulis'),
					'berbicara'=>$this->input->post('berbicara'),
					'mendengar'=>$this->input->post('mendengar'),
	   		);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'karyawan_bahasa');
	   	}else{
				$datax = $this->messages->notValidParam(); 
			}
			echo json_encode($datax);
	}
	public function edit_bahasa()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
	   	$nik=$this->input->post('nik');
	   	$id=$this->input->post('id_k_bahasa');
	   	if($nik!='' || $id!=''){
	   		$data=array(
					'bahasa'=>$this->input->post('bahasa2'),
					'membaca'=>$this->input->post('membaca2'),
					'menulis'=>$this->input->post('menulis2'),
					'berbicara'=>$this->input->post('berbicara2'),
					'mendengar'=>$this->input->post('mendengar2'),
	   		);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$where = array('id_k_bahasa' => $id, 'nik' => $nik );
				$datax = $this->model_global->updateQuery($data,'karyawan_bahasa',$where);
	   	}else{
				$datax = $this->messages->notValidParam(); 
			}
			echo json_encode($datax);
	}
	//------------------------------------------------------------------------------------------------------//
	//** employee jabatan **//
	public function emp_part_jabatan()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'gaji_pokok'=> $this->otherfunctions->getJenisGajiList(),
		);
		$this->load->view('_partial/_view_employee_jabatan',$data);
	}
	public function emp_part_jabatan_grade()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'grade') {
				$data = $this->model_master->getMasterGradeForSelect2();
        		echo json_encode($data);
        	}
        }
	}
	
	public function edit_jabatan()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
	   	$id=$this->input->post('id_karyawan');
		if($id!=''){
	   		$data=array(
					'jabatan'=>$this->input->post('jabatan'),
					'loker'=>$this->input->post('loker'),
					'grade'=>$this->input->post('grade'),
					'status_karyawan'=>$this->input->post('status_karyawan'),
					'kode_penggajian'=>$this->input->post('sistem_penggajian'),
					'gaji_pokok'=>$this->input->post('gaji_pokok'),
					'gaji'=>$this->formatter->getFormatMoneyDb($this->input->post('gaji')),
	   		);
			$jab_sek=$this->input->post('jabatan_sekunder');
			$data['jabatan_sekunder']=(isset($jab_sek)?implode(';', $jab_sek):null);
			$data2=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data2,'karyawan',['id_karyawan'=>$id]);	   	}else{
				$datax = $this->messages->notValidParam(); 
			}
		echo json_encode($datax);
	}

	//------------------------------------------------------------------------------------------------------//
	//** employee foto **//
	public function emp_part_foto()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
		);
		$this->load->view('_partial/_view_employee_foto',$data);
	}

	//------------------------------------------------------------------------------------------------------//
	//** employee password **//
	public function emp_part_pass()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
		);
		$this->load->view('_partial/_view_employee_pass',$data);
	}
	
	//------------------------------------------------------------------------------------------------------//
	//** employee riwayat pekerjaan **//
	public function emp_part_job()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
		);
		$this->load->view('_partial/_view_employee_job',$data);
	}
	
	//------------------------------------------------------------------------------------------------------//
	//** employee riwayat login **//
	public function emp_part_log()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$jum_log = $this->db->get_where('log_login_karyawan',array('id_karyawan'=>$kar['id_karyawan']))->num_rows(); 
		$data=array(
			'profile'=>$kar,
			'jumlah_log'=>$jum_log,
		);
		$this->load->view('_partial/_view_employee_log',$data);
	}

	public function emp_log()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);

		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->db->get_where('log_login_karyawan',array('id_karyawan'=>$kar['id_karyawan']))->result(); 
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_karyawan,
					];

					$lastlog = date("l, d F Y", strtotime($d->tgl_login)).' <i style="color:red;" class="fa fa-clock-o"></i> '.date("H:i:s", strtotime($d->tgl_login));
					$datax['data'][]=[
						$d->id_karyawan,
						$lastlog
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$jum_log = $this->db->get_where('log_login_karyawan',array('id_karyawan'=>$kar['id_karyawan']))->num_rows(); 
				$datax=[
						'jumlah_log'=>$jum_log,
					];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	// function del_log(){

	// 	if (!$this->input->is_ajax_request()) 
	// 	   redirect('not_found');
	// 	$id=$this->input->post('id');
	// 	if (empty($id))
	// 		echo json_encode($this->messages->notValidParam());
	// 	$where=['id_karyawan'=>$id];
	// 	$datax=$this->model_global->deleteQuery('log_login_karyawan',$where);
	// 	echo json_encode($datax);

	// 	// $nik=$this->input->post('nik');
	// 	// $nik1=$this->model_karyawan->emp($nik);
	// 	// if ($nik == "") {
	// 	// 	$this->messages->notValidParam();  
	// 	// 	redirect('pages/employee');
	// 	// }else{
	// 	// 	$this->db->where('id_karyawan',$nik);
	// 	// 	$in=$this->db->delete('log_login_karyawan');
	// 	// 	if ($in) {
	// 	// 		$this->session->set_flashdata('dlog_sc','<label><i class="fa fa-check-circle"></i> Hapus Riwayat Login Berhasil</label><hr class="message-inner-separator">Semua Riwayat Login Karyawan ini berhasil dihapus'); 
	// 	// 	}else{
	// 	// 		$this->session->set_flashdata('dlog_err','<label><i class="fa fa-times-circle"></i> Hapus Riwayat Login Gagal</label><hr class="message-inner-separator">Semua Riwayat Login Karyawan ini Gagal dihapus'); 
	// 	// 	}
	// 	// 	redirect('pages/view_employee/'.$nik1['nik']);
	// 	// }
	// }
	//------------------------------------------------------------------------------------------------------//
	//** employee grade **//
	public function emp_part_grade()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$grade=$this->model_karyawan->emp_grade($kar['grade']);
		$data=array(
			'profile'=>$kar,
			'grade'=>$grade,
		);
		$this->load->view('_partial/_view_employee_grade',$data);
	}
	public function edt_grade()
	{
		if (!$this->input->is_ajax_request()) 
   		redirect('not_found');
   	$id=$this->input->post('id_karyawan');
   	if($id!=''){
   		$data=array(
				'grade'=>$this->input->post('grade'),
   		);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	//------------------------------------------------------------------------------------------------------//
	//** employee riwayat pekerjaan non_aktif**//
	public function emp_part_work()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'access'=>$this->access,
		);
		$this->load->view('_partial/_view_employee_work',$data);
	}
	//------------------------------------------------------------------------------------------------------//
	//** employee data tambahan non_aktif**//
	public function emp_part_other()
	{
		$nik=$this->uri->segment(3);
		$kar=$this->model_karyawan->getEmployeeNik($nik);
		$data=array(
			'profile'=>$kar,
			'access'=>$this->access,
		);
		$this->load->view('_partial/_view_employee_other',$data);
	}

//====================================================== MUTASI ========================================================
	public function pilih_k_mutasi()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getPilihKaryawanMutasi();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_karyawan,
				'<a class="pilih" style="cursor:pointer" 
				data-nik			="'.$d->nik.'" 
				data-id_karyawan	="'.$d->id_karyawan.'" 
				data-nama			="'.$d->nama.'" 
				data-jabatan		="'.$d->jabatan.'" 
				data-nama_jabatan	="'.$d->nama_jabatan.'" 
				data-kode_lokasi	="'.$d->loker.'" 
				data-nama_lokasi	="'.$d->nama_loker.'">'.
				$d->nik.'</a>',
				$d->nama,
				$d->nama_jabatan,
				$d->nama_loker,
			];
		}
		echo json_encode($datax);		
	}
	public function mutasi_jabatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListDataMutasi();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$param,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListDataMutasi('search',$where,'data');
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_mutasi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_mutasi,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_status,
						$d->nama_jabatan_baru,
						$d->nama_loker_baru,
						$d->jum,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_mutasi');
				$data=$this->model_karyawan->getMutasi($id);
				foreach ($data as $d) {
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Mutasi '.$d->nama_karyawan.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama</th>
          							<th>NO SK</th>
          							<th>Tanggal SK</th>
          							<th>Status</th>
          							<th>Jabatan Baru</th>
          							<th>Lokasi Baru</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_mutasi=$this->model_karyawan->getListMutasiNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_mutasi as $d_m) {
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_m->nama_karyawan.'</td>
          							<td>'.$d_m->no_sk.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_m->tgl_sk).'</td>
          							<td>'.$d_m->nama_status.'</td>
          							<td>'.$d_m->nama_jabatan_baru.'</td>
          							<td>'.$d_m->nama_loker_baru.'</td>
          						</tr>';
          						$no++;
          					}
	          				$tabel.='</tbody>
	          			</table>';
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'tabel'=>$tabel,
						'loker'=>$d->nama_loker_baru,
						'jabatan'=>$d->nama_jabatan_baru,
						'id'=>$d->id_mutasi,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>$d->no_sk,
						'tgl_sk'=>$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						'tgl_berlaku'=>$this->formatter->getDateMonthFormatUser($d->tgl_berlaku),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'lokasi_asal'=>(!empty($d->lokasi_asal)) ? $d->nama_loker:$this->otherfunctions->getMark($d->lokasi_asal),
						'lokasi_baru'=>$d->lokasi_baru,
						'status_mutasi'=>$d->status_mutasi,
						'jabatan_lama'=>(!empty($d->jabatan_asal)) ? $d->nama_jabatan:$this->otherfunctions->getMark($d->jabatan_asal),
						'jabatan_baru'=>$d->jabatan_baru,
						'mengetahui'=>$this->model_karyawan->getEmployeeForSelect2(),
						'menyetujui'=>$this->model_karyawan->getEmployeeForSelect2(),
						'keterangan'=>$d->keterangan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'tgl_sk_e'=>$this->formatter->getDateFormatUser($d->tgl_sk),
						'tgl_berlaku_e'=>$this->formatter->getDateFormatUser($d->tgl_berlaku),
						'vjabatan_lama'=>(!empty($d->jabatan_asal)) ? $d->nama_jabatan:null,
						'vlokasi_asal'=>(!empty($d->lokasi_asal)) ? $d->nama_loker:null,
						'vlokasi_baru'=>$d->nama_loker_baru,
						'vstatus_mutasi'=>$d->nama_status,
						'vjabatan_baru'=>$d->nama_jabatan_baru,
						'vmengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'vmenyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'vketerangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeSkMutasi();
        		echo json_encode($data);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'cekjabatan') {
				if (!$this->input->is_ajax_request()) 
				   redirect('not_found');
				$jabatan=$this->input->post('jabatan');
		 		$idk = $this->input->post('id_karyawan');
				$jab = $this->model_karyawan->cekJabatan($jabatan);
				$jml = count($jab);
				if ($jml > 0){
					$val = 'false';
				} else{
					$val = 'true';
				}
				$datax=['val' => $val];
				echo json_encode($datax);
			}elseif ($usage == 'refreshkaryawan') {
				$data = $this->model_karyawan->getRefreshKaryawan();
        		echo json_encode($data);
			}elseif($usage == 'count'){
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListMutasi();
					$display = 'block';
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$data=$this->model_karyawan->searchMutasi($bagian,$unit,$bulan,$tahun);
					$jml = count($data);
					if($jml < 1){
						$display = 'none';
					}else{
						$display = 'block';
					}
				}
				$datax=['display'=>$display];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_mutasi_jabatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListMutasiNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_mutasi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_mutasi,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_status,
						$d->nama_jabatan_baru,
						$d->nama_loker_baru,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_mutasi');
				$data=$this->model_karyawan->getMutasi($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'id'=>$d->id_mutasi,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>$d->no_sk,
						'tgl_sk'=>$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						'tgl_berlaku'=>$this->formatter->getDateMonthFormatUser($d->tgl_berlaku),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'lokasi_asal'=>(!empty($d->lokasi_asal)) ? $d->nama_loker:$this->otherfunctions->getMark(),
						'lokasi_baru'=>$d->lokasi_baru,
						'status_mutasi'=>$d->status_mutasi,
						'jabatan_lama'=>(!empty($d->jabatan_asal)) ? $d->nama_jabatan:$this->otherfunctions->getMark(),
						'jabatan_baru'=>$d->jabatan_baru,
						'percobaan'=>$d->lama_percobaan,
						'mengetahui'=>$d->mengetahui,
						'menyetujui'=>$d->menyetujui,
						'keterangan'=>$d->keterangan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'tgl_sk_e'=>$this->formatter->getDateFormatUser($d->tgl_sk),
						'tgl_berlaku_e'=>$this->formatter->getDateFormatUser($d->tgl_berlaku),
						'vjabatan_lama'=>(!empty($d->jabatan_asal)) ? $d->nama_jabatan:$this->otherfunctions->getMark(),
						'vlokasi_asal'=>(!empty($d->lokasi_asal)) ? $d->nama_loker:$this->otherfunctions->getMark(),
						'vlokasi_baru'=>$d->nama_loker_baru,
						'vstatus_mutasi'=>$d->nama_status,
						'vpercobaan'=>(isset($d->lama_percobaan)) ? $d->lama_percobaan.' Bulan' : $this->otherfunctions->getMark(),
						'vjabatan_baru'=>$d->nama_jabatan_baru,
						'vmengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'vmenyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'vketerangan'=>(!empty($d->keterangan)) ?$this->formatter->getValHastagView($d->keterangan):$this->otherfunctions->getMark(),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'admin') {
				$data = $this->model_admin->getAdminForSelect2();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_mutasi(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$id_karyawan=$this->input->post('id_karyawan');
		if ($id != "") {
			$data=array(
				'no_sk'=>strtoupper($this->input->post('no_sk')),
				'tgl_sk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk')),
				'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku')),
				'status_mutasi'=>$this->input->post('status_mutasi'),
				'jabatan_baru'=>$this->input->post('jabatan_baru'),
				'lokasi_baru'=>$this->input->post('lokasi_baru'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'lama_percobaan'=>$this->input->post('lama_percobaan'),
				'keterangan'=>$this->input->post('keterangan'),
			);
				$data_kar=array('loker'=>$this->input->post('lokasi_baru'),'jabatan'=>$this->input->post('jabatan_baru'),);
				$where = array('id_karyawan' => $id_karyawan );
				$this->model_global->updateQuery($data_kar,'karyawan',$where);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_mutasi_jabatan',['id_mutasi'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_mutasi(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk');
		if ($kode != "") {
			$data=array(
				'id_karyawan'=>$this->input->post('id_karyawan'),
				'no_sk'=>strtoupper($this->input->post('no_sk')),
				'tgl_sk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk')),
				'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku')),
				'status_mutasi'=>$this->input->post('status_mutasi'),
				'jabatan_asal'=>$this->input->post('jabatan'),
				'lokasi_asal'=>$this->input->post('lokasi_asal'),
				'jabatan_baru'=>$this->input->post('jabatan_baru'),
				'lokasi_baru'=>$this->input->post('lokasi_baru'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'lama_percobaan'=>$this->input->post('lama_percobaan'),
				'keterangan'=>$this->input->post('keterangan'),
			);
				$data_kar=array('loker'=>$this->input->post('lokasi_baru'),'jabatan'=>$this->input->post('jabatan_baru'),);
				$where = array('id_karyawan' => $this->input->post('id_karyawan') );
				$this->model_global->updateQuery($data_kar,'karyawan',$where);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));

			$datax = $this->model_global->insertQueryCC($data,'data_mutasi_jabatan',$this->model_karyawan->checkMutasiCode($data['no_sk']));
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//===================================================== PERJANJIAN KERJA ===================================================
	public function pilih_k_perjanjian()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getPilihKaryawanPerjanjian();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_karyawan,
				'<a class="pilih" style="cursor:pointer" 
				data-nik				="'.$d->knik.'" 
				data-nama				="'.$d->nama.'" 
				data-status_perjanjian	="'.$d->status_baru.'" 
				data-nama_status_perjanjian	="'.$d->nama_status.'" 
				data-no_sk_lama			="'.$d->no_sk_baru.'" 
				data-tgl_sk_lama		="'.$this->formatter->getDateFormatUser($d->tgl_sk_baru).'" 
				data-tgl_berlaku_lama			="'.$this->formatter->getDateFormatUser($d->tgl_berlaku_baru).'" 
				data-tgl_berlaku_sampai_lama	="'.$this->formatter->getDateFormatUser($d->berlaku_sampai_baru).'">'.
				$d->knik.'</a>',
				$d->nama,
				$d->nama_status,
			];
		}
		echo json_encode($datax);		
	}
	public function pilih_k_perjanjian_add()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$nik=$this->input->post('nik');
		$data=$this->model_karyawan->getPilihKaryawanPerjanjianNIK($nik);
		//$datax=[];
		foreach ($data as $d) {
			$datax=[
				'no_sk_lama'=>(!empty($d->no_sk_baru)) ? $d->no_sk_baru : null,
				'tgl_sk_lama'=>(!empty($d->tgl_sk_baru)) ? $this->formatter->getDateFormatUser($d->tgl_sk_baru) : null,
				'tgl_berlaku_lama'=>(!empty($d->tgl_berlaku_baru)) ? $this->formatter->getDateFormatUser($d->tgl_berlaku_baru) : null,
				'berlaku_sampai_lama'=>(!empty($d->berlaku_sampai_baru)) ? $this->formatter->getDateFormatUser($d->berlaku_sampai_baru) : null,
				'status_lama'=>$d->status_baru
			];
		}
		echo json_encode($datax);		
	}
	public function perjanjian_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListDataPerjanjianKerja();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$param,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListDataPerjanjianKerja('search',$where,'data');
				}				
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_p_kerja,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if($d->aktifOrNot==2){
						$nama_kar=$d->nama_karyawan.'<br><small class="text-muted"><font color="red">Karyawan Harus Dinonaktifkan</font></small>';
					}elseif($d->aktifOrNot==0){
						$nama_kar=$d->nama_karyawan.'<br><small class="text-muted"><font color="red">Karyawan Nonaktif</font></small>';
					}else{
						$nama_kar=$d->nama_karyawan;
					}
					$datax['data'][]=[
						$d->id_p_kerja,
						$d->nik,
						$nama_kar,
						$d->no_sk_baru,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk_baru),
						(!empty($d->nama_status_baru)) ? $d->nama_status_baru:$this->otherfunctions->getMark(),
						(!empty($d->berlaku_sampai_baru)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai_baru):$this->otherfunctions->getMark(),
						$d->jum,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_p_kerja');
				$data=$this->model_karyawan->getPerjanjianKerja($id);
				foreach ($data as $d) {
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Perjanjian Kerja '.$d->nama_karyawan.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>NO SK</th>
          							<th>Tanggal SK</th>
          							<th>Status</th>
          							<th>Tanggal Berlaku</th>
          							<th>Berlaku Sampai</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_perjanjian=$this->model_karyawan->getListPerjanjianKerjaNik($d->nik);
          						$no=1;
          						foreach ($data_perjanjian as $d_p) {
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_p->no_sk_baru.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_sk_baru).'</td>
          							<td>'.$d_p->nama_status_baru.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_berlaku_baru).'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->berlaku_sampai_baru).'</td>
          						</tr>';
          						$no++;
          					}
	          				$tabel.='</tbody>
	          			</table>';
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'tabel'=>$tabel,
						'loker'=>$d->nama_loker,
						'jabatan'=>$d->nama_jabatan,
						'id'=>$d->id_p_kerja,
						'no_sk_lama'=>(!empty($d->no_sk_lama)) ? $d->no_sk_lama : $this->otherfunctions->getMark($d->no_sk_lama),
						'tgl_sk_lama'=>(!empty($d->tgl_sk_lama)) ? $this->formatter->getDateMonthFormatUser($d->tgl_sk_lama) : $this->otherfunctions->getMark($d->tgl_sk_lama),
						'tgl_berlaku_lama'=>(!empty($d->tgl_berlaku_lama)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku_lama) : $this->otherfunctions->getMark($d->tgl_berlaku_lama),
						'berlaku_sampai_lama'=>(!empty($d->berlaku_sampai_lama)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai_lama) : $this->otherfunctions->getMark($d->berlaku_sampai_lama),
						'nik'=>$d->nik,
						'nama'=>$d->nama_karyawan,
						'status_lama'=>(!empty($d->nama_status_lama)) ? $d->nama_status_lama : $this->otherfunctions->getMark($d->nama_status_lama),
						'status_baru'=>$d->nama_status_baru,
						'no_sk_baru'=>$d->no_sk_baru,
						'tgl_sk_baru'=>$this->formatter->getDateMonthFormatUser($d->tgl_sk_baru),
						'tgl_berlaku_baru'=>$this->formatter->getDateMonthFormatUser($d->tgl_berlaku_baru),
						'berlaku_sampai_baru'=>$this->formatter->getDateMonthFormatUser($d->berlaku_sampai_baru),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>$d->nama_mengetahui.$jbt_mengetahui,
						'menyetujui'=>$d->nama_menyetujui.$jbt_menyetujui,
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$kode = $this->uri->segment(4);
				$code = empty($kode) ? null : $kode;
				$data = $this->codegenerator->kodePerjanjianKerja($code);
        		echo json_encode($data);
			}elseif ($usage == 'getagreementend') {
	  			$data_agg=$this->model_karyawan->getAgreementEnd();
				$c=[];
				$ada='<li class="header">Perjanjian Akan Berakhir</li><li class="divider"></li>';
				if(isset($data_agg)){
					foreach ($data_agg as $d) {
						// if ($d->kode_perjanjian != 0 && $d->kode_perjanjian != 1) {
						$date_ex=date('Y-m-d',strtotime('-'.$d->expire_notif.' Day',strtotime($d->tgl_end)));
						$date_now=date('Y-m-d',strtotime($this->date));
						if (($date_ex <= $date_now) && ($date_now <= $d->tgl_end)) {
							$left=$this->formatter->getCountDateRange($date_now,$d->tgl_end)['hari'];
							$ada.='<li><a href="'.base_url('pages/view_perjanjian_kerja/'.$this->codegenerator->encryptChar($d->nik_karyawan)).'"><i class="fas fa-user"></i> '.$d->nama_karyawan.' <small style="color:red; font-size:8pt;">'.$left.' Hari Lagi</small> <small class="text-muted pull-right" title="Tanggal Berakhir Perjanjian">'.$this->formatter->getDateMonthFormatUser($d->tgl_end).'</small></a></li>';
							array_push($c,1);
						}
						// }
					}
					$ada.='<li class="footer"><a href="'.base_url('pages/data_perjanjian_kerja').'">Tampilkan Semua</a></li>';
				}
				if (count($c) == 0) {
					$ada='<li class="text-center"><small class="text-muted"><i class="icon-close"></i> Tidak Ada Data</small></li><li class="divider"> </li>';
				}
				$data=[	'count'=>count($c),
						'value'=>$ada,
					];
        		echo json_encode($data);
			}elseif ($usage == 'getTanggalMasuk') {
				$datax = [];
				$nik = $this->input->post('nik');
				if(!empty($nik)){
					$emp = $this->model_karyawan->getEmployeeNik($nik);
					$datax = [
						'tanggal_masuk'=> $this->formatter->getDateFormatUser($emp['tgl_masuk']),
						'tanggal_lahir'=> $this->formatter->getDateFormatUser($emp['tgl_lahir']),
					];
				}
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_perjanjian_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListPerjanjianKerjaNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_p_kerja,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$printConpen = '<button type="button" class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="do_print_konpensasi('.$d->id_p_kerja.')"><i class="fa fa-print" data-toggle="tooltip" title="Cetak Kompensasi"></i></button> ';
					$datax['data'][]=[
						$d->id_p_kerja,
						$d->nama_karyawan,
						$d->no_sk_baru,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk_baru),
						$d->nama_status_baru,
						(!empty($d->tgl_berlaku_baru)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku_baru):$this->otherfunctions->getMark($d->tgl_berlaku_baru),
						(!empty($d->berlaku_sampai_baru)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai_baru):$this->otherfunctions->getMark($d->berlaku_sampai_baru),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$properties['cetak'].$printConpen,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_p_kerja');
				$data=$this->model_karyawan->getPerjanjianKerja($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'id'=>$d->id_p_kerja,
						'no_sk_lama'=>(!empty($d->no_sk_lama)) ? $d->no_sk_lama : $this->otherfunctions->getMark($d->no_sk_lama),
						'tgl_sk_lama'=>(!empty($d->tgl_sk_lama)) ? $this->formatter->getDateMonthFormatUser($d->tgl_sk_lama) : $this->otherfunctions->getMark($d->tgl_sk_lama),
						'tgl_berlaku_lama'=>(!empty($d->tgl_berlaku_lama)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku_lama) : $this->otherfunctions->getMark($d->tgl_berlaku_lama),
						'berlaku_sampai_lama'=>(!empty($d->berlaku_sampai_lama)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai_lama) : $this->otherfunctions->getMark($d->berlaku_sampai_lama),
						'nik'=>$d->nik,
						'nama'=>$d->nama_karyawan,
						'status_karyawan'=>$d->nama_status,
						'status_karyawan_edit'=>$d->status_karyawan,
						'status_lama'=>(!empty($d->nama_status_lama)) ? $d->nama_status_lama : $this->otherfunctions->getMark($d->nama_status_lama),
						'status_baru'=>$d->nama_status_baru,
						'no_sk_baru'=>$d->no_sk_baru,
						'tgl_sk_baru'=>$this->formatter->getDateMonthFormatUser($d->tgl_sk_baru),
						'tgl_berlaku_baru'=>$this->formatter->getDateMonthFormatUser($d->tgl_berlaku_baru),
						'berlaku_sampai_baru'=>$this->formatter->getDateMonthFormatUser($d->berlaku_sampai_baru),
						'gaji'=>$this->formatter->getFormatMoneyUser($d->gaji),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>$d->mengetahui,
						'menyetujui'=>$d->menyetujui,
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'no_sk_lamav'=>(!empty($d->no_sk_lama)) ? $d->no_sk_lama : null,
						'tgl_sk_lamav'=>(!empty($d->tgl_sk_lama)) ? $this->formatter->getDateFormatUser($d->tgl_sk_lama) : null,
						'tgl_berlaku_lamav'=>(!empty($d->tgl_berlaku_lama)) ? $this->formatter->getDateFormatUser($d->tgl_berlaku_lama) : null,
						'berlaku_sampai_lamav'=>(!empty($d->berlaku_sampai_lama)) ? $this->formatter->getDateFormatUser($d->berlaku_sampai_lama) : null,
						'perjanjian_lama'=>$d->nama_status_lama,
						'perjanjian_baru'=>$d->status_baru,
						'status_baruv'=>$d->nama_status_baru,
						'tgl_sk_baruv'=>$this->formatter->getDateFormatUser($d->tgl_sk_baru),
						'tgl_berlaku_baruv'=>$this->formatter->getDateFormatUser($d->tgl_berlaku_baru),
						'berlaku_sampai_baruv'=>$this->formatter->getDateFormatUser($d->berlaku_sampai_baru),
						'mengetahuiv'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujuiv'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'keteranganv'=>$d->keterangan,
						'date_validasi'=>$d->date_validasi,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'call_status') {
				$nik=$this->input->post('nik');
				$data=$this->model_karyawan->getEmployeeOneNik($nik);
				foreach ($data as $d) {
					$datax=[
						'status_karyawan'=>$d->nama_status,
						'aktif_or_not'=>$d->status_emp,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_perjanjian_kerja(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$perjanjian_baru=$this->input->post('perjanjian_baru');
		$date_validasi=$this->input->post('date_validasi');
		$nik=$this->input->post('nik');
		$nonaktif=$this->input->post('nonaktif');
		$idk=$this->model_karyawan->getEmployeeNik($nik)['id_karyawan'];
		if ($id != "") {
			if($perjanjian_baru=='PTSP' || $perjanjian_baru=='RSGN'){
				$data=array(
					'nik'=>$nik,
					'no_sk_baru'=>$this->input->post('no_sk_baru'),
					'tgl_sk_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk_baru')),
					'tgl_berlaku_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'berlaku_sampai_baru'=>$this->formatter->getDateFormatDb($this->input->post('berlaku_sampai_baru')),
					'status_baru'=>$perjanjian_baru,
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'keterangan'=>$this->input->post('keterangan'),
				);
				$data_non=[
					'id_karyawan'=>$idk,
					'no_sk'=>strtoupper($this->input->post('no_sk')),
					'tgl_sk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk_baru')),
					'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'tgl_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'keterangan'=>$this->input->post('keterangan'),
					'date_validasi'=>$date_validasi,
				];
				$data_non=array_merge($data_non,$this->model_global->getCreateProperties($this->admin));
				if($nonaktif=='ya'){
					$data_kar=['status_perjanjian'=>$this->input->post('no_sk_baru'),'status_emp'=>0,];
				}else{
					$data_kar=['status_perjanjian'=>$this->input->post('no_sk_baru'),'status_emp'=>2,];
					$data_non=array_merge($data_non,['status'=>2,]);
				}
				$this->model_global->updateQuery($data_kar,'karyawan',['nik' => $nik]);
				$this->model_global->insertQuery($data_non,'data_karyawan_tidak_aktif');
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'data_perjanjian_kerja',['id_p_kerja'=>$id]);
			}else{
				$data=[
					'nik'=>$nik,
					'no_sk_baru'=>strtoupper($this->input->post('no_sk_baru')),
					'tgl_sk_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk_baru')),
					'tgl_berlaku_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'berlaku_sampai_baru'=>$this->formatter->getDateFormatDb($this->input->post('berlaku_sampai_baru')),
					'status_baru'=>$this->input->post('perjanjian_baru'),
					'gaji'=>$this->formatter->getFormatMoneyDb($this->input->post('gaji')),
					'status_karyawan'=>$this->input->post('status_karyawan'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'keterangan'=>$this->input->post('keterangan'),
				];
				$data_kar=['status_perjanjian'=>$this->input->post('no_sk_baru'),'status_karyawan'=>$this->input->post('status_karyawan'),'status_emp'=>1];
				$this->model_global->updateQuery($data_kar,'karyawan',['nik'=>$nik]);
				$this->db->delete('data_karyawan_tidak_aktif', ['id_karyawan'=>$idk,'date_validasi'=>$date_validasi]);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'data_perjanjian_kerja',['id_p_kerja'=>$id]);
			}
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_perjanjian_kerja(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk_baru');
		$nik=$this->input->post('nik');
		$idkar=$this->model_karyawan->getEmployeeNik($nik)['id_karyawan'];
		$tgl_sk=$this->formatter->getDateFormatDb($this->input->post('tgl_sk_baru'));
		$perjanjian_baru=$this->input->post('perjanjian_baru');
		$nonaktif=$this->input->post('nonaktif');
		$validasi_nonaktif=$this->input->post('validasi_nonaktif');
		$date_valid=$this->date;
		if ($kode != "" && $nik != null && $tgl_sk != null) {
			if($perjanjian_baru=='PTSP' || $perjanjian_baru=='RSGN'){
				$data=array(
					'nik'=>$nik,
					'no_sk_baru'=>$this->input->post('no_sk_baru'),
					'tgl_sk_baru'=>$tgl_sk,
					'tgl_berlaku_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'berlaku_sampai_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'status_baru'=>$perjanjian_baru,
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'keterangan'=>$this->input->post('keterangan'),
					'date_validasi'=>$date_valid,
				);
				$idk=$this->model_karyawan->getEmployeeNik($nik)['id_karyawan'];
				$data_non=[
					'id_karyawan'=>$idk,
					'no_sk'=>strtoupper($this->input->post('no_sk')),
					'tgl_sk'=>$tgl_sk,
					'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'tgl_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'alasan'=>$this->input->post('alasan'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'keterangan'=>$this->input->post('keterangan'),
					'date_validasi'=>$date_valid,
				];
				$data_non=array_merge($data_non,$this->model_global->getCreateProperties($this->admin));
				if($nonaktif=='ya'){
					$data_kar=['status_perjanjian'=>$this->input->post('no_sk_baru'),'status_emp'=>0,];
				}else{
					$data_kar=['status_perjanjian'=>$this->input->post('no_sk_baru'),'status_emp'=>2,];
					$data_non=array_merge($data_non,['status'=>2,]);
				}
				$this->model_global->insertQuery($data_non,'data_karyawan_tidak_aktif');
				$this->model_global->updateQuery($data_kar,'karyawan',['nik' => $nik]);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'data_perjanjian_kerja',$this->model_karyawan->checkPerjanjianCode($data['no_sk_baru']));
			}else{
				$data=[
					'nik'=>$nik,
					'no_sk_lama'=>$this->input->post('no_sk_lama'),
					'tgl_sk_lama'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk_lama')),
					'tgl_berlaku_lama'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_lama')),
					'berlaku_sampai_lama'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_sampai_lama')),
					'status_lama'=>$this->input->post('status_lama'),
					'status_karyawan'=>$this->input->post('status_karyawan'),
					'no_sk_baru'=>$this->input->post('no_sk_baru'),
					'gaji'=>$this->formatter->getFormatMoneyDb($this->input->post('gaji')),
					'tgl_sk_baru'=>$tgl_sk,
					'tgl_berlaku_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_baru')),
					'berlaku_sampai_baru'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_sampai_baru')),
					'status_baru'=>$perjanjian_baru,
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'keterangan'=>$this->input->post('keterangan'),
					'date_validasi'=>$date_valid,
				];
				$val=$this->input->post('val');
				if($val=='aktif'){
					$cek=$this->model_karyawan->checkPerjanjianCode($data['no_sk_baru']);
					$data_kar=['status_perjanjian'=>$this->input->post('no_sk_baru'),'status_karyawan'=>$this->input->post('status_karyawan'),'status_emp'=>1];
					$this->model_global->updateQuery($data_kar,'karyawan',['nik' => $nik]);
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQueryCC($data,'data_perjanjian_kerja',$cek);
				}else{
					$data_kar=['status_perjanjian'=>$this->input->post('no_sk_baru'),'status_karyawan'=>$this->input->post('status_karyawan')];
					$this->model_global->updateQuery($data_kar,'karyawan',['nik' => $nik]);
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQueryCC($data,'data_perjanjian_kerja',$this->model_karyawan->checkPerjanjianCode($data['no_sk_baru']));
				}
			}
		}elseif($validasi_nonaktif=='ya' && $nonaktif=='ya'){
			$data_kar=['status_perjanjian'=>$this->input->post('no_sk_baru'),'status_emp'=>0,];
			$this->model_global->updateQuery($data_kar,'karyawan',['nik' => $nik]);
			$data_non=['status'=>1,];
			$datax = $this->model_global->updateQuery($data_non,'data_karyawan_tidak_aktif',['id_karyawan' => $idkar]);
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function tanggal_janji(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode = $this->input->post('status');
		$gettanggal = $this->input->post('tgl_berlaku');
		$nik = $this->input->post('nik');
		$tgl_lahir = $this->model_karyawan->getEmployeeNik($nik)['tgl_lahir'];
		$status=$this->db->query("SELECT berlaku FROM master_surat_perjanjian WHERE kode_perjanjian = '$kode'")->result();
		foreach ($status as $s) {
			if(substr($s->berlaku,0,4) == 'Umur'){
				$fe = substr($s->berlaku,5,2);
				$thn = substr($tgl_lahir,0,4);
				$tgl = mktime(0, 0, 0, 0, 0, date($thn)+$fe);
				$data = date("d/m/Y", $tgl);
			}else{
				if(substr($s->berlaku,2,1) == 'H') {
					$d = substr($s->berlaku,0,1);
				}elseif(substr($s->berlaku,3,1) == 'H'){
					$d = substr($s->berlaku,0,2);
				}else{
					$d = null;
				}
				if(substr($s->berlaku,2,1) == 'M') {
					$g = substr($s->berlaku,0,1);
				}elseif(substr($s->berlaku,3,1) == 'M'){
					$g = substr($s->berlaku,0,2);
				}else{
					$g = null;
				}
				if(substr($s->berlaku,2,1) == 'B') {
					$e = substr($s->berlaku,0,1);
				}elseif(substr($s->berlaku,3,1) == 'B'){
					$e = substr($s->berlaku,0,2);
				}else{
					$e = null;
				}
				if(substr($s->berlaku,2,1) == 'T'){
					$f = substr($s->berlaku,0,1);
				}elseif(substr($s->berlaku,3,1) == 'T'){
					$f = substr($s->berlaku,0,2);
				}else{
					$f = null;
				}
				$tanggal = substr($gettanggal,0,2);
				$bulan = substr($gettanggal,3,2);
				$tahun = substr($gettanggal,6,4);
				$tgl = mktime(0, 0, 0, date($bulan)+$e, date($tanggal)+$d+($g*7), date($tahun)+$f);
				$data = date("d/m/Y", $tgl);
			}			
			echo json_encode($data);
		}
	}
//Peringatan KErja
	public function pilih_k_peringatan()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getPilihKaryawanPeringatan();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_kar,
				'<a class="pilih" style="cursor:pointer" 
				data-nik			="'.$d->nik.'" 
				data-id_kar	="'.$d->id_kar.'" 
				data-nama			="'.$d->nama.'" 
				data-kode_disiplin	="'.$d->kode_disiplin.'" 
				data-nama_disiplin	="'.$d->nama_disiplin.'">'.
				$d->nik.'</a>',
				$d->nama,
				$d->nama_disiplin,
			];
		}
		echo json_encode($datax);		
	}
	public function peringatan_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListDataPeringatan();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$param,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListDataPeringatan('search',$where,'data');
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_peringatan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_peringatan,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_status_baru,
						$d->jum,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_peringatan');
				$data=$this->model_karyawan->getPeringatanKerja($id);
				foreach ($data as $d) {
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Peringatan '.$d->nama_karyawan.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>NO SK</th>
          							<th>Tanggal SK</th>
          							<th>Peringatan</th>
          							<th>Tanggal Berlaku</th>
          							<th>Berlaku Sampai</th>
          							<th>Pengurang Penilaian</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_peringatan=$this->model_karyawan->getListPeringatanNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_peringatan as $d_p) {
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_p->no_sk.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_sk).'</td>
          							<td>'.$d_p->nama_status_baru.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_berlaku).'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->berlaku_sampai).'</td>
          							<td>'.(!empty($d_p->potong_pa)?$d_p->potong_pa.' Poin':0).'</td>
          						</tr>';
          						$no++;
          					}
	          				$tabel.='</tbody>
	          			</table>';
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'tabel'=>$tabel,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'id'=>$d->id_peringatan,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tgl_sk'=>(!empty($d->tgl_sk)) ? $this->formatter->getDateMonthFormatUser($d->tgl_sk) : $this->otherfunctions->getMark($d->tgl_sk),
						'tgl_berlaku'=>(!empty($d->tgl_berlaku)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku) : $this->otherfunctions->getMark($d->tgl_berlaku),
						'berlaku_sampai'=>(!empty($d->berlaku_sampai)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai) : $this->otherfunctions->getMark($d->berlaku_sampai),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'status_lama'=>(!empty($d->nama_status_lama)) ? $d->nama_status_lama : $this->otherfunctions->getMark($d->nama_status_lama),
						'status_baru'=>$d->nama_status_baru,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeringatanKerja();
        		echo json_encode($data);
			}elseif ($usage == 'kode_denda') {
				$data = $this->codegenerator->kodeDenda();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_peringatan_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListPeringatanNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_peringatan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_peringatan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_status_baru,
						(!empty($d->berlaku_sampai)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai):$this->otherfunctions->getMark($d->berlaku_sampai),
						(!empty($d->potong_pa)?$d->potong_pa.' Poin':0),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_peringatan');
				$data=$this->model_karyawan->getPeringatanKerja($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
					$datax=[
						'id'=>$d->id_peringatan,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tgl_sk'=>(!empty($d->tgl_sk)) ? $this->formatter->getDateMonthFormatUser($d->tgl_sk) : $this->otherfunctions->getMark($d->tgl_sk),
						'tgl_berlaku'=>(!empty($d->tgl_berlaku)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku) : $this->otherfunctions->getMark($d->tgl_berlaku),
						'berlaku_sampai'=>(!empty($d->berlaku_sampai)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai) : $this->otherfunctions->getMark($d->berlaku_sampai),
						'etgl_sk'=>$this->formatter->getDateFormatUser($d->tgl_sk),
						'etgl_berlaku'=>$this->formatter->getDateFormatUser($d->tgl_berlaku),
						'eberlaku_sampai'=>$this->formatter->getDateFormatUser($d->berlaku_sampai),
						'estatus_baru'=>$d->status_baru,
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'edibuat'=>$d->dibuat,
						'epelanggaran'=>$d->pelanggaran,
						'eketerangan'=>$d->keterangan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'status_lama'=>(!empty($d->nama_status_lama)) ? $d->nama_status_lama : $this->otherfunctions->getMark($d->nama_status_lama),
						'estatus_lama'=>$d->nama_status_lama,
						'status_baru'=>$d->nama_status_baru,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'dibuat'=>(!empty($d->nama_dibuat)) ? $d->nama_dibuat.$jbt_dibuat:$this->otherfunctions->getMark(),
						'pelanggaran'=>(!empty($d->pelanggaran)) ?$this->formatter->getValHastagView($d->pelanggaran):$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'denda'=>$d->denda,
						'potong_pa'=>$d->potong_pa,
						'potong_pa_view'=>(!empty($d->potong_pa)?$d->potong_pa.' Poin':0),
						'besaran_denda'=>$this->formatter->getFormatMoneyUser($d->besaran_denda),
						'besaran_denda_e'=>(!empty($d->besaran_denda)) ? $this->formatter->getFormatMoneyUser($d->besaran_denda):null,
						'jumlah_angsuran'=>$d->jumlah_angsuran,
						'no_sk_v'=>$d->no_sk,
						'kode_denda'=>$d->kode_denda,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeringatanKerja();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_peringatan_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$id_k=$this->input->post('id_karyawan');
		$no_sk_new=strtoupper($this->input->post('no_sk'));
		$no_sk_old=strtoupper($this->input->post('no_sk_old'));
		if ($id != "") {
			$data_kar=array('status_disiplin'=>$this->input->post('peringatan_baru'));
			$where = array('id_karyawan' => $id_k);
			$this->model_global->updateQuery($data_kar,'karyawan',$where);
			$no_sk=($no_sk_new == $no_sk_old)?$no_sk_old:$no_sk_new;
			$denda_old=$this->input->post('denda_old');
			$denda_new=$this->input->post('denda_edit');
			if($denda_old==1 && $denda_new==1){
				$total_denda=$this->formatter->getFormatMoneyDb($this->input->post('besaran_denda'));
				$diangsur=$this->input->post('angsuran_edit');
				// $besar_angsuran=($total_denda/$diangsur);
				$data_den=[	'total_denda'=>$total_denda,
							'diangsur'=>$diangsur,
							'tgl_denda'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk')),
							// 'besar_angsuran'=>$besar_angsuran,
							// 'saldo_denda'=>$total_denda,
							'kode_peringatan'=>$no_sk,
							'mengetahui'=>$this->input->post('mengetahui'),
							'menyetujui'=>$this->input->post('menyetujui'),
							'dibuat'=>$this->input->post('dibuat'),];
				$data_d=array_merge($data_den,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->updateQuery($data_d,'data_denda',['kode_peringatan'=>$no_sk_old]);
			}elseif($denda_old==0 && $denda_new==1){
				$kode_denda_new=$this->input->post('kode_denda');
				$kode_denda_old=$this->input->post('kode_denda_old');
				$kode_denda=($kode_denda_new == $kode_denda_old)?$kode_denda_old:$kode_denda_new;
				$total_denda=$this->formatter->getFormatMoneyDb($this->input->post('besaran_denda'));
				$diangsur=$this->input->post('angsuran_edit');
				// $besar_angsuran=($total_denda/$diangsur);
				$data_den=['kode'=>$kode_denda,
							'id_karyawan'=>$id_k,
							'total_denda'=>$total_denda,
							'diangsur'=>$diangsur,
							'tgl_denda'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk')),
							// 'besar_angsuran'=>$besar_angsuran,
							'kode_peringatan'=>$no_sk,
							// 'saldo_denda'=>$this->formatter->getFormatMoneyDb($this->input->post('besaran_denda')),
							'mengetahui'=>$this->input->post('mengetahui'),
							'menyetujui'=>$this->input->post('menyetujui'),
							'dibuat'=>$this->input->post('dibuat'),
						];
				$data_d=array_merge($data_den,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryCC($data_d,'data_denda',$this->model_karyawan->checkPeringatanCode($kode_denda));
			}elseif($denda_old==1 && $denda_new==0){
				$this->db->where('kode_peringatan', $no_sk_old);
				$this->db->delete('data_denda');
			}
			$data=array(
				'id_karyawan'=>$this->input->post('id_karyawan'),
				'no_sk'=>$no_sk,
				'tgl_sk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk')),
				'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku')),
				'berlaku_sampai'=>$this->formatter->getDateFormatDb($this->input->post('berlaku_sampai')),
				'status_baru'=>$this->input->post('peringatan_baru'),
				'pelanggaran'=>$this->input->post('pelanggaran'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'dibuat'=>$this->input->post('dibuat'),
				'denda'=>$denda_new,
				'besaran_denda'=>$this->formatter->getFormatMoneyDb($this->input->post('besaran_denda')),
				'jumlah_angsuran'=>$this->input->post('angsuran_edit'),
				'keterangan'=>$this->input->post('keterangan'),
				'potong_pa'=>$this->input->post('pengurang_poin'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_peringatan_karyawan',['id_peringatan'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_peringatan_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk');
		$id_k=$this->input->post('id_karyawan');
		$tgl_sk=$this->formatter->getDateFormatDb($this->input->post('tgl_sk'));
		if ($kode != "" && $id_k != null && $tgl_sk != null) {
			$data=array(
				'id_karyawan'=>$id_k,
				'no_sk'=>$kode,
				'tgl_sk'=>$tgl_sk,
				'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku')),
				'berlaku_sampai'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku_sampai')),
				'status_asal'=>$this->input->post('status_asal'),
				'status_baru'=>$this->input->post('peringatan_baru'),
				'pelanggaran'=>$this->input->post('pelanggaran'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'potong_pa'=>$this->input->post('pengurang_poin'),
				'dibuat'=>$this->input->post('dibuat'),
				'denda'=>$this->input->post('denda'),
				'besaran_denda'=>$this->formatter->getFormatMoneyDb($this->input->post('besaran_denda')),
				'jumlah_angsuran'=>$this->input->post('angsuran'),
				'keterangan'=>$this->input->post('keterangan'),
			);
			$data_kar=array('status_disiplin'=>$this->input->post('peringatan_baru'));
			$where = array('id_karyawan' => $this->input->post('id_karyawan') );
			$this->model_global->updateQuery($data_kar,'karyawan',$where);
			if($this->input->post('denda')==1){
				$kode_denda=$this->input->post('kode_denda');
				$total_denda=$this->formatter->getFormatMoneyDb($this->input->post('besaran_denda'));
				$diangsur=$this->input->post('angsuran');
				// $besar_angsuran=($total_denda/$diangsur);
				$data_den=['kode'=>$kode_denda,
							'id_karyawan'=>$id_k,
							'total_denda'=>$total_denda,
							'diangsur'=>$diangsur,
							'tgl_denda'=>$tgl_sk,
							// 'besar_angsuran'=>$besar_angsuran,
							// 'saldo_denda'=>$this->formatter->getFormatMoneyDb($this->input->post('besaran_denda')),
							'kode_peringatan'=>$kode,
							'mengetahui'=>$this->input->post('mengetahui'),
							'menyetujui'=>$this->input->post('menyetujui'),
							'dibuat'=>$this->input->post('dibuat'),
						];
				$data_d=array_merge($data_den,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryCC($data_d,'data_denda',$this->model_karyawan->checkPeringatanCode($kode_denda));
			}
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'data_peringatan_karyawan',$this->model_karyawan->checkPeringatanCode($kode));
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function tanggal_peringatan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode = $this->input->post('status');
		$status = $this->db->query("SELECT berlaku FROM master_surat_peringatan WHERE kode_sp = '$kode'")->result();
		foreach ($status as $s) {
			if(substr($s->berlaku,2,1) == 'H') {
				$d = substr($s->berlaku,0,1);
			}elseif(substr($s->berlaku,3,1) == 'H'){
				$d = substr($s->berlaku,0,2);
			}else{
				$d = null;
			}
			if(substr($s->berlaku,2,1) == 'M') {
				$g = substr($s->berlaku,0,1);
			}elseif(substr($s->berlaku,3,1) == 'M'){
				$g = substr($s->berlaku,0,2);
			}else{
				$g = null;
			}
			if(substr($s->berlaku,2,1) == 'B') {
				$e = substr($s->berlaku,0,1);
			}elseif(substr($s->berlaku,3,1) == 'B'){
				$e = substr($s->berlaku,0,2);
			}else{
				$e = null;
			}
			if(substr($s->berlaku,2,1) == 'T'){
				$f = substr($s->berlaku,0,1);
			}elseif(substr($s->berlaku,3,1) == 'T'){
				$f = substr($s->berlaku,0,2);
			}else{
				$f = null;
			}
			$gettanggal = $this->input->post('tgl_berlaku');
			$tanggal = substr($gettanggal,0,2);
			$bulan = substr($gettanggal,3,2);
			$tahun = substr($gettanggal,6,4);
			$tgl = mktime(0, 0, 0, date($bulan)+$e, date($tanggal)+$d+($g*7), date($tahun)+$f);
			$data = date("d/m/Y", $tgl);
			echo json_encode($data);
		}
	}
//DATA DENDA KARYAWAN
	public function data_denda(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListDenda();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$data=$this->model_karyawan->getListDenda($bagian,$unit,$bulan,$tahun);
				}				
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_denda,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_denda,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_loker,
						$d->jum,
						$d->jum_non,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_denda');
				$data=$this->model_karyawan->getDenda($id);
				foreach ($data as $d) {
          			$data_denda=$this->model_karyawan->getListDendaNik($d->nik_karyawan);
          			if(count($data_denda) != null){
						$tabel='';
						$tabel.='<h4 align="center"><b>Data Denda '.$d->nama_karyawan.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">	          				<table class="table table-bordered table-striped data-table">
	          					<thead>
	          						<tr class="bg-blue">
	          							<th>No.</th>
	          							<th>Kode Denda</th>
	          							<th>No SK Peringatan</th>
	          							<th>Total Denda</th>
	          							<th>Angsuran</th>
	          							<th>Sudah Diangsur</th>
	          							<th>Sisa Denda</th>
	          						</tr>
	          					</thead>
	          					<tbody>';
	          						$no=1;
	          						foreach ($data_denda as $d_p) {
	          							$pe=($d_p->kode_peringatan!=null)?$d_p->kode_peringatan:'Non Peringatan';
	          							$per_ang=($d_p->sudah_diangsur!=0)? $d_p->sudah_diangsur.' Kali':'<label class="label label-danger">Belum Diangsur</label>';
	          							$tabel.='<tr>
	          							<td>'.$no.'</td>
	          							<td>'.$d_p->kode.'</td>
	          							<td>'.$pe.'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($d_p->total_denda).'</td>
	          							<td>'.$d_p->diangsur.' Kali</td>
	          							<td>'.$per_ang.'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($d_p->max_saldo).'</td>
	          						</tr>';
	          						$no++;
	          					}
		          				$tabel.='</tbody>
		          			</table>';
	          		}else{
	          			$tabel=null;
	          		}
      				$data_denda_non=$this->model_karyawan->getListDendaNikNon($d->nik_karyawan);
      				if(count($data_denda_non) != null){
						$tabel_non='';
						$tabel_non.='<h4 align="center"><b>Data Denda Non Peringatan</b></h4>
	          				<table class="table table-bordered table-striped data-table">
	          					<thead>
	          						<tr class="bg-blue">
	          							<th>No.</th>
	          							<th>Kode Denda</th>
	          							<th>Total Denda</th>
	          							<th>Angsuran</th>
	          							<th>Sudah Diangsur</th>
	          							<th>Sisa Denda</th>
	          						</tr>
	          					</thead>
	          					<tbody>';
	          						$no=1;
	          						foreach ($data_denda_non as $d_p) {
	          							$pe=($d_p->kode_peringatan!=null)?$d_p->kode_peringatan:'Non Peringatan';
	          							$per_ang_non=($d_p->sudah_diangsur!=0)? $d_p->sudah_diangsur.' Kali':'<label class="label label-danger">Belum Diangsur</label>';
	          							$tabel_non.='<tr>
	          							<td>'.$no.'</td>
	          							<td>'.$d_p->kode.'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($d_p->total_denda).'</td>
	          							<td>'.$d_p->diangsur.' Kali</td>
	          							<td>'.$per_ang_non.'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($d_p->max_saldo).'</td>
	          						</tr>';
	          						$no++;
	          					}
		          				$tabel_non.='</tbody>
		          			</table>';
		          	}else{
		          		$tabel_non=null;
		          	}
					$datax=[
						'tabel'=>$tabel,
						'tabel_non'=>$tabel_non,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'id'=>$d->id_denda,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode_denda') {
				$data = $this->codegenerator->kodeDenda();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_data_denda() {
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListDendaNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_denda,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
          			$per=($d->kode_peringatan!=null)?'<a href="'.base_url('pages/view_peringatan/'.$this->codegenerator->encryptChar($d->nik_karyawan)).'">'.$d->kode_peringatan.'</a>':'Non Peringatan';
          			$per_ang=($d->sudah_diangsur!=0)? $d->sudah_diangsur.' Kali':'<label class="label label-danger">Belum Diangsur</label>';
          			$info='<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_per('.$d->id_denda.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
          			$delete='<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal_per('.$d->id_denda.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_denda,
						$d->nama_karyawan,
						$d->kode,
						$per,
						$this->formatter->getFormatMoneyUser($d->total_denda),
						$d->diangsur.' Kali',
						$per_ang,
						$this->formatter->getFormatMoneyUser($d->max_saldo),
						$properties['tanggal'],
						$properties['status'],
						$info.$delete,
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one_per') {
				$id = $this->input->post('id_denda');
				$data=$this->model_karyawan->getDataDendaPerId($id);
				foreach ($data as $d) {
          			$data_d=$this->model_karyawan->getDataAngsuranDenda($d->kode);
          			if(count($data_d) != null){
						$tabel_per='';
						$tabel_per.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Kode Denda</th>
          							<th>Tanggal Angsuran</th>
          							<th>Besar Angsuran</th>
          							<th>Angsuran Ke</th>
          							<th>Sisa Denda</th>
          						</tr>
          					</thead>
          					<tbody>';
          						$noq=1;
          						foreach ($data_d as $d_p) {
          							$tabel_per.='<tr>
          							<td>'.$noq.'</td>
          							<td>'.$d_p->kode_denda.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_angsuran).'</td>
          							<td>'.$this->formatter->getFormatMoneyUser($d_p->besar_angsuran).'</td>
          							<td>'.$d_p->angsuran_ke.'</td>
          							<td>'.$this->formatter->getFormatMoneyUser($d_p->saldo_denda).'</td>
          						</tr>';
          						$noq++;
          					}
	          				$tabel_per.='</tbody>
	          			</table>';
	          		}else{
	          			$tabel_per=null;
	          		}
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
					$datax=[
						'id'=>$d->id_denda,
						'id_karyawan'=>$d->id_karyawan,
						'nama'=>$d->nama_karyawan,
						'nik'=>$d->nik_karyawan,
						'kode'=>$d->kode,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'tgl_denda'=>$this->formatter->getDateMonthFormatUser($d->tgl_denda),
						'jumlah_denda'=>$this->formatter->getFormatMoneyUser($d->total_denda),
						'jumlah_angsuran'=>$d->diangsur.' Kali',
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'edibuat'=>$d->dibuat,
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'dibuat'=>(!empty($d->nama_dibuat)) ? $d->nama_dibuat.$jbt_dibuat:$this->otherfunctions->getMark(),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'tabel_per'=>$tabel_per,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_all_non') {
				$data=$this->model_karyawan->getListDendaNikNon($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_denda,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
          			$per=($d->kode_peringatan!=null)?'<a href="'.base_url('pages/view_peringatan/'.$this->codegenerator->encryptChar($d->nik_karyawan)).'">'.$d->kode_peringatan.'</a>':'Non Peringatan';
          			$per_ang_non=($d->sudah_diangsur!=0)? $d->sudah_diangsur.' Kali':'<label class="label label-danger">Belum Diangsur</label>';
          			$info='<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_non('.$d->id_denda.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
          			$delete='<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_denda.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_denda,
						$d->nama_karyawan,
						$d->kode,
						$this->formatter->getFormatMoneyUser($d->total_denda),
						$d->diangsur.' Kali',
						$per_ang_non,
						$this->formatter->getFormatMoneyUser($d->max_saldo),
						$properties['tanggal'],
						$properties['status'],
						$info.$delete,
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one_non') {
				$id = $this->input->post('id_denda');
				$data=$this->model_karyawan->getDataDendaPerId($id);
				foreach ($data as $d) {
          			$data_d=$this->model_karyawan->getDataAngsuranDenda($d->kode);
          			if(count($data_d) != null){
						$tabel_non='';
						$tabel_non.='
	          				<table class="table table-bordered table-striped data-table">
	          					<thead>
	          						<tr class="bg-blue">
	          							<th>No.</th>
	          							<th>Kode Denda</th>
	          							<th>Tanggal Angsuran</th>
	          							<th>Besar Angsuran</th>
	          							<th>Angsuran Ke</th>
	          							<th>Sisa Denda</th>
	          						</tr>
	          					</thead>
	          					<tbody>';
	          						$no=1;
	          						foreach ($data_d as $d_p) {
	          							$tabel_non.='<tr>
	          							<td>'.$no.'</td>
	          							<td>'.$d_p->kode_denda.'</td>
	          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_angsuran).'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($d_p->besar_angsuran).'</td>
	          							<td>'.$d_p->angsuran_ke.'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($d_p->saldo_denda).'</td>
	          						</tr>';
	          						$no++;
	          					}
		          				$tabel_non.='</tbody>
		          			</table>';
		          		}else{
		          			$tabel_non='';
		          		}
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
					$datax=[
						'id'=>$d->id_denda,
						'id_karyawan'=>$d->id_karyawan,
						'nama'=>$d->nama_karyawan,
						'nik'=>$d->nik_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'tgl_denda'=>$this->formatter->getDateMonthFormatUser($d->tgl_denda),
						'tgl_denda_e'=>$this->formatter->getDateFormatUser($d->tgl_denda),
						'jumlah_denda'=>$this->formatter->getFormatMoneyUser($d->total_denda),
						'jumlah_angsuran'=>$d->diangsur.' Kali',
						'jumlah_angsuran_e'=>$d->diangsur,
						'kode'=>$d->kode,
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'edibuat'=>$d->dibuat,
						'eketerangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'dibuat'=>(!empty($d->nama_dibuat)) ? $d->nama_dibuat.$jbt_dibuat:$this->otherfunctions->getMark(),
						'keterangan'=>$d->keterangan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'tabel_non'=>$tabel_non,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_denda(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$total_denda=$this->formatter->getFormatMoneyDb($this->input->post('total_denda'));
			$diangsur=$this->input->post('angsuran');
			$besar_angsuran=($total_denda/$diangsur);
			$data=array(
				'kode'=>$this->input->post('kode'),
				'kode_peringatan'=>$this->input->post('kode'),
				'id_karyawan'=>strtoupper($this->input->post('id_karyawan')),
				'tgl_denda'=>$this->formatter->getDateFormatDb($this->input->post('tgl_denda')),
				'total_denda'=>$total_denda,
				'diangsur'=>$diangsur,
				'besar_angsuran'=>$besar_angsuran,
				'status_denda'=>'non_peringatan',
				'keterangan'=>$this->input->post('keterangan'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'dibuat'=>$this->input->post('dibuat'),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));

			$datax = $this->model_global->insertQueryCC($data,'data_denda',$this->model_karyawan->checkMutasiCode($data['kode']));
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_denda(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$total_denda=$this->formatter->getFormatMoneyDb($this->input->post('besaran_denda'));
			$diangsur=$this->input->post('angsuran_edit');
			// $besar_angsuran=($total_denda/$diangsur);
			$data=array(
				'kode'=>$this->input->post('no_denda'),
				'kode_peringatan'=>$this->input->post('no_denda'),
				'id_karyawan'=>strtoupper($this->input->post('id_karyawan')),
				'tgl_denda'=>$this->formatter->getDateFormatDb($this->input->post('tgl_denda')),
				'total_denda'=>$total_denda,
				'diangsur'=>$diangsur,
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'dibuat'=>$this->input->post('dibuat'),
				'keterangan'=>$this->input->post('keterangan'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_denda',['id_denda'=>$id]);
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//GRADE KARYAWAN
	public function pilih_k_grade()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getPilihKaryawanGrade();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_kar,
				'<a class="pilih" style="cursor:pointer" 
				data-nik		="'.$d->nik.'" 
				data-id_kar		="'.$d->id_kar.'" 
				data-nama		="'.$d->nama.'" 
				data-kode_grade	="'.$d->kode_grade.'" 
				data-nama_grade	="'.$d->nama_grade.'  ('.$d->nama_loker.')">'.
				$d->nik.'</a>',
				$d->nama,
				(empty($d->nama_grade)?$d->nama_grade : $d->nama_grade.' ('.$d->nama_loker.')'),
			];
		}
		echo json_encode($datax);		
	}
	public function grade_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListGrade();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListGrade('search',$where);
				}
				
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_grade,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_grade,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_grade_baru.' ('.$d->nama_loker_grade.')',
						$d->jum,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_grade');
				$data=$this->model_karyawan->getGradeKaryawan($id);
				foreach ($data as $d) {
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Grade '.$d->nama_karyawan.'</b></h4>
							<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>NO SK</th>
          							<th>Tanggal SK</th>
          							<th>Grade</th>
          							<th>Tanggal Berlaku</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_grade=$this->model_karyawan->getListGradeNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_grade as $d_p) {
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_p->no_sk.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_sk).'</td>
          							<td>'.$d_p->nama_grade_baru.' ('.$d_p->nama_loker_grade.')</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_berlaku).'</td>
          							</tr>';
          						$no++;
          					}
	          				$tabel.='</tbody>
	          			</table>';
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'tabel'=>$tabel,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'id'=>$d->id_grade,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tgl_sk'=>(!empty($d->tgl_sk)) ? $this->formatter->getDateMonthFormatUser($d->tgl_sk) : $this->otherfunctions->getMark($d->tgl_sk),
						'tgl_berlaku'=>(!empty($d->tgl_berlaku)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku) : $this->otherfunctions->getMark($d->tgl_berlaku),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'grade_lama'=>(!empty($d->nama_grade_lama)) ? $d->nama_grade_lama : $this->otherfunctions->getMark($d->nama_grade_lama),
						'grade_baru'=>$d->nama_grade_baru.$d->nama_loker_grade,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeGradeKaryawan();
        		echo json_encode($data);
			}elseif($usage == 'count'){
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListGrade();
					$display = 'block';
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListGrade('search',$where);
					$jml = count($data);
					if($jml < 1){
						$display = 'none';
					}else{
						$display = 'block';
					}
				}
				$datax=['display'=>$display];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_grade_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListGradeNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_grade,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_grade,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_grade_baru.' ('.$d->nama_loker_grade.')',
						(!empty($d->tgl_berlaku)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku):$this->otherfunctions->getMark($d->tgl_berlaku),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_grade');
				$data=$this->model_karyawan->getGradeKaryawan($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'id'=>$d->id_grade,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tgl_sk'=>(!empty($d->tgl_sk)) ? $this->formatter->getDateMonthFormatUser($d->tgl_sk) : $this->otherfunctions->getMark($d->tgl_sk),
						'tgl_berlaku'=>(!empty($d->tgl_berlaku)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku) : $this->otherfunctions->getMark($d->tgl_berlaku),
						'etgl_sk'=>$this->formatter->getDateFormatUser($d->tgl_sk),
						'etgl_berlaku'=>$this->formatter->getDateFormatUser($d->tgl_berlaku),
						'egrade_baru'=>$d->grade_baru,
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'eketerangan'=>$d->keterangan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'grade_lama'=>(!empty($d->nama_grade_lama)) ? $d->nama_grade_lama.' ('.$d->nama_loker_grade_lama.')': $this->otherfunctions->getMark($d->nama_grade_lama),
						'egrade_lama'=>$d->nama_grade_lama.' ('.$d->nama_loker_grade_lama.')',
						'grade_baru'=>$d->nama_grade_baru.' ('.$d->nama_loker_grade.')',
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeGradeKerja();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_grade_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'id_karyawan'=>$this->input->post('id_karyawan'),
				'no_sk'=>strtoupper($this->input->post('no_sk')),
				'tgl_sk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk')),
				'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku')),
				'grade_baru'=>$this->input->post('grade_baru'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'keterangan'=>$this->input->post('keterangan'),
			);
				$data_kar=array('grade'=>$this->input->post('grade_baru'));
				$where = array('id_karyawan' => $this->input->post('id_karyawan') );
				$this->model_global->updateQuery($data_kar,'karyawan',$where);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_grade_karyawan',['id_grade'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_grade_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk');
		$id_k=$this->input->post('id_karyawan');
		$tgl_sk=$this->formatter->getDateFormatDb($this->input->post('tgl_sk'));
		if ($kode != "" && $id_k != null && $tgl_sk != null) {
			$data=array(
				'id_karyawan'=>$id_k,
				'no_sk'=>$this->input->post('no_sk'),
				'tgl_sk'=>$tgl_sk,
				'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku')),
				'grade_asal'=>$this->input->post('grade_asal'),
				'grade_baru'=>$this->input->post('grade_baru'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'keterangan'=>$this->input->post('keterangan'),
			);
				$data_kar=array('grade'=>$this->input->post('grade_baru'));
				$where = array('id_karyawan' => $this->input->post('id_karyawan') );
				$this->model_global->updateQuery($data_kar,'karyawan',$where);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'data_grade_karyawan',$this->model_karyawan->checkGradeCode($kode));
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//======================================= KECELAKAAN KERJA ====================================================================
	public function pilih_k_kecelakaankerja()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getPilihKaryawanKecelakaanKerja();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_kar,
				'<a class="pilih" style="cursor:pointer" 
				data-nik		="'.$d->nik.'" 
				data-id_kar		="'.$d->id_kar.'" 
				data-nama		="'.$d->nama.'"
				data-nama_jabatan	="'.$d->nama_jabatan.'">'.
				$d->nik.'</a>',
				$d->nama,
				$d->nama_jabatan,
			];
		}
		echo json_encode($datax);		
	}
	public function kecelakaan_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListKecelakaanKerja();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$param,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListKecelakaanKerja('search',$where);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kecelakaan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_kecelakaan,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl),
						$d->nama_kategori_kecelakaan,
						$d->jum,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_kecelakaan');
				$data=$this->model_karyawan->getKecelakaanKerjaKaryawan($id);
				foreach ($data as $d) {
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Kecelakaan Kerja '.$d->nama_karyawan.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>No Kecelakaan</th>
          							<th>Tanggal</th>
          							<th>Kecelakaan Kerja</th>
          							<th>Rumah Sakit</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_kecelakaan=$this->model_karyawan->getListKecelakaanKerjaNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_kecelakaan as $d_p) {
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_p->no_sk.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl).'</td>
          							<td>'.$d_p->nama_kategori_kecelakaan.'</td>
          							<td>'.$d_p->nama_rs.'</td>
          							</tr>';
          						$no++;
          					}
	          				$tabel.='</tbody>
	          			</table>';
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$datax=[
						'tabel'=>$tabel,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'id'=>$d->id_kecelakaan,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tgl'=>(!empty($d->tgl)) ? $this->formatter->getDateMonthFormatUser($d->tgl) : $this->otherfunctions->getMark($d->tgl),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'kategori'=>$d->nama_kategori_kecelakaan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>$d->nama_mengetahui.$jbt_mengetahui,
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeKecelakaanKerja();
        		echo json_encode($data);
			}elseif($usage == 'count'){
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListKecelakaanKerja();
					$display = 'block';
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$data=$this->model_karyawan->getListKecelakaanKerja('view_all',$bagian,$unit,$bulan,$tahun);
					$jml = count($data);
					if($jml < 1){
						$display = 'none';
					}else{
						$display = 'block';
					}
				}
				$datax=['display'=>$display];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_kecelakaan_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListKecelakaanKerjaNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kecelakaan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_kecelakaan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl),
						$d->nama_kategori_kecelakaan,
						$d->nama_rs,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_kecelakaan');
				$data=$this->model_karyawan->getKecelakaanKerjaKaryawan($id);
				foreach ($data as $d) {
					$jbt_menget=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyat=($d->jbt_menyatakan != null) ? ' - '.$d->jbt_menyatakan : null;
					$jbt_sak1=($d->jbt_saksi_1 != null) ? ' - '.$d->jbt_saksi_1 : null;
					$jbt_sak2=($d->jbt_saksi_2 != null) ? ' - '.$d->jbt_saksi_2 : null;
					$jbt_tggjwb=($d->jbt_penanggungjawab != null) ? ' - '.$d->jbt_penanggungjawab : null;
					$datax=[
						'id'=>$d->id_kecelakaan,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'nama_jabatan'=>$d->nama_jabatan,
						'nama_loker'=>$d->nama_loker,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tgl_cetak'=>(!empty($d->tgl_cetak)) ? $this->formatter->getDateMonthFormatUser($d->tgl_cetak) : $this->otherfunctions->getMark(),
						'tgl'=>(!empty($d->tgl)) ? $this->formatter->getDateMonthFormatUser($d->tgl) : $this->otherfunctions->getMark($d->tgl),
						'jam'=>(!empty($d->jam)) ? $this->formatter->timeFormatUser($d->jam) : $this->otherfunctions->getMark(),
						'lokasi'=>(!empty($d->nama_loker_kejadian)) ? $d->nama_loker_kejadian : $this->otherfunctions->getMark($d->nama_loker_kejadian),
						'kategori'=>(!empty($d->nama_kategori_kecelakaan)) ? $d->nama_kategori_kecelakaan : $this->otherfunctions->getMark($d->nama_kategori_kecelakaan),
						'rumahsakit'=>(!empty($d->nama_rs)) ? $d->nama_rs : $this->otherfunctions->getMark($d->nama_rs),
						'mengetahui'=>(!empty($d->nama_mengetahui))?$d->nama_mengetahui.$jbt_menget : $this->otherfunctions->getMark(),
						'menyatakan'=>(!empty($d->nama_menyatakan))?$d->nama_menyatakan.$jbt_menyat : $this->otherfunctions->getMark(),
						'saksi_1'=>(!empty($d->nama_saksi_1))?$d->nama_saksi_1.$jbt_sak1 : $this->otherfunctions->getMark(),
						'saksi_2'=>(!empty($d->nama_saksi_2))?$d->nama_saksi_2.$jbt_sak2 : $this->otherfunctions->getMark(),
						'penanggungjawab'=>(!empty($d->nama_penanggungjawab))?$d->nama_penanggungjawab.$jbt_tggjwb : $this->otherfunctions->getMark(),
						'tembusan'=>(!empty($d->tembusan)) ? $d->tembusan:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'kejadian'=>(!empty($d->kejadian)) ? $d->kejadian:$this->otherfunctions->getMark($d->kejadian),
						'alat'=>(!empty($d->alat)) ? $d->alat:$this->otherfunctions->getMark($d->alat),
						'bagiantubuh'=>(!empty($d->bagian_tubuh)) ? $d->bagian_tubuh:$this->otherfunctions->getMark($d->bagian_tubuh),
						'etgl'=>$this->formatter->getDateFormatUser($d->tgl),
						'etgl_cetak'=>$this->formatter->getDateFormatUser($d->tgl_cetak),
						'ejam'=>$this->formatter->timeFormatUser($d->jam),
						'elokasi'=>$d->kode_loker,
						'lokasi_luar'=>$d->lokasi_kejadian,
						'ekategori'=>$d->kode_kategori_kecelakaan,
						'erumahsakit'=>$d->kode_master_rs,
						'emengetahui'=>$d->mengetahui,
						'emenyatakan'=>$d->menyatakan,
						'esaksi_1'=>$d->saksi_1,
						'esaksi_2'=>$d->saksi_2,
						'epenanggungjawab'=>$d->penanggungjawab,
						'etembusan'=>$d->tembusan,
						'eketerangan'=>$d->keterangan,
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
				$data = $this->codegenerator->kodeKecelakaanKerja();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_kecelakaan_kerja(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$k_kecelakaan=$this->input->post('kategori_kecelakaan');
		if($k_kecelakaan == 'KK_DLM'){
			$lokasi_dalam=$this->input->post('tempat_kejadian');
			$lokasi_luar=null;
		}else{
			$lokasi_dalam=null;
			$lokasi_luar=$this->input->post('tempat_kejadian_luar');
		}
		if ($id != "") {
			$data=array(
				'id_karyawan'=>$this->input->post('id_karyawan'),
				'no_sk'=>$this->input->post('no_kecelakaan'),
				'tgl_cetak'=>$this->formatter->getDateFormatDb($this->input->post('tgl_cetak')),
				'tgl'=>$this->formatter->getDateFormatDb($this->input->post('tgl_kecelakaan')),
				'jam'=>$this->formatter->timeFormatDb($this->input->post('jam_terjadi')),
				'kode_kategori_kecelakaan'=>$k_kecelakaan,
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyatakan'=>$this->input->post('menyatakan'),
				'saksi_1'=>$this->input->post('saksi1'),
				'saksi_2'=>$this->input->post('saksi2'),
				'penanggungjawab'=>$this->input->post('penanggungjawab'),
				'kode_master_rs'=>$this->input->post('rumahsakit'),
				'kode_loker'=>$lokasi_dalam,
				'lokasi_kejadian'=>$lokasi_luar,
				'tembusan'=>$this->input->post('tembusan'),
				'keterangan'=>$this->input->post('keterangan'),
				'kejadian'=>$this->input->post('kejadian'),
				'alat'=>$this->input->post('alat'),
				'bagian_tubuh'=>$this->input->post('bagian_tubuh'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_kecelakaan_kerja',['id_kecelakaan'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_kecelakaan_kerja(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_kecelakaan');
		$id_k=$this->input->post('id_karyawan');
		$tgl=$this->formatter->getDateFormatDb($this->input->post('tgl_kecelakaan'));
		if ($kode != "" && $id_k != null && $tgl != null) {
			$data=array(
				'id_karyawan'=>$id_k,
				'no_sk'=>$kode,
				'tgl_cetak'=>$this->formatter->getDateFormatDb($this->input->post('tgl_cetak')),
				'tgl'=>$tgl,
				'jam'=>$this->formatter->timeFormatDb($this->input->post('jam_terjadi')),
				'kode_kategori_kecelakaan'=>$this->input->post('kategori_kecelakaan'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyatakan'=>$this->input->post('menyatakan'),
				'saksi_1'=>$this->input->post('saksi1'),
				'saksi_2'=>$this->input->post('saksi2'),
				'penanggungjawab'=>$this->input->post('penanggungjawab'),
				'kode_master_rs'=>$this->input->post('rumahsakit'),
				'kode_loker'=>$this->input->post('tempat_kejadian'),
				'lokasi_kejadian'=>$this->input->post('tempat_kejadian_luar'),
				'tembusan'=>$this->input->post('tembusan'),
				'keterangan'=>$this->input->post('keterangan'),
				'kejadian'=>$this->input->post('kejadian'),
				'alat'=>$this->input->post('alat'),
				'bagian_tubuh'=>$this->input->post('bagian_tubuh'),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'data_kecelakaan_kerja',$this->model_karyawan->checkKecelakaanKerjaCode($kode));
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

//KARYAWAN TIDAK AKTIF
	public function pilih_k_nonaktif()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getPilihKaryawanNonAktif();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_karyawan,
				'<a class="pilih" style="cursor:pointer" 
				data-nik			="'.$d->nik.'" 
				data-id_karyawan	="'.$d->id_karyawan.'" 
				data-nama			="'.$d->nama.'" 
				data-jabatan		="'.$d->jabatan.'" 
				data-nama_jabatan	="'.$d->nama_jabatan.'" 
				data-kode_lokasi	="'.$d->loker.'" 
				data-nama_lokasi	="'.$d->nama_loker.'"
				data-tgl_masuk		="'.$this->formatter->getDateFormatUser($d->tgl_masuk).'">'.
				$d->nik.'</a>',
				$d->nama,
				$d->nama_jabatan,
				$d->nama_loker,
			];
		}
		echo json_encode($datax);		
	}
	public function karyawan_tidak_aktif()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListKaryawanNonAktif();
					// $where = ['param'=>'all','bagian'=>'ll'];
					// $data=$this->model_karyawan->getListKaryawanNonAktif('search',$where);
					// $data=$this->model_karyawan->getListKaryawanNonAktif('search');
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListKaryawanNonAktif('search',$where);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kta,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_kta,
						'<a href="'.base_url("pages/view_karyawan_non_aktif/".$this->codegenerator->encryptChar($d->nik_karyawan)).'">'.$d->nik_karyawan.'</a>',
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_loker,
						$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						$this->formatter->getDateMonthFormatUser($d->tgl_keluar),
						$d->jum.' Data',
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_kta');
				$data=$this->model_karyawan->getKaryawanNonAktif($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'id'=>$d->id_kta,
						'no_sk'=>$d->no_sk,
						'tgl_sk'=>$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						'tgl_berlaku'=>$this->formatter->getDateMonthFormatUser($d->tgl_berlaku),
						'tgl_masuk'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'tgl_keluar'=>$this->formatter->getDateMonthFormatUser($d->tgl_keluar),
						'id_karyawan'=>$d->id_karyawan,
						'status_karyawan'=>$d->status_emp,
						'nik'=>$d->nik,
						'nama'=>$d->nama_karyawan,
						'loker'=>$d->nama_loker,
						'jabatan'=>$d->nama_jabatan,
						'alasan'=>$d->nama_alasan,
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'keterangan'=>$d->keterangan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'etgl_sk'=>$this->formatter->getDateFormatUser($d->tgl_sk),
						'etgl_berlaku'=>$this->formatter->getDateFormatUser($d->tgl_berlaku),
						'etgl_masuk'=>$this->formatter->getDateFormatUser($d->tgl_masuk),
						'etgl_keluar'=>$this->formatter->getDateFormatUser($d->tgl_keluar),
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'ealasan'=>$d->alasan,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_kar') {
				$id = $this->input->post('id_karyawan');
				$data=$this->model_karyawan->getEmployeeId($id);
				$datax=[
					'id_karyawan'=>$data['id_karyawan'],
					'nik'=>$data['nik'],
					'nama'=>$data['nama'],
					'jabatan'=>$data['nama_jabatan'],
					'loker'=>$data['nama_loker'],
				];
				echo json_encode($datax);
			}elseif ($usage == 'tabel_view') {
				$id = $this->input->post('id_karyawan');
				$data=$this->model_karyawan->getListKaryawanNonAktifIDK($id);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$table = '';
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kta,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal_one('.$d->id_kta.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					// $properties['cetak'];
					$jbt_mengetahui=($d->jbt_mengetahui != null)?' ('.$d->jbt_mengetahui.')':null;
					$jbt_menyetujui=($d->jbt_menyetujui != null)?' ('.$d->jbt_menyetujui.')':null;
					$table .= '<tr>
						<td class="nowrap">'.$no.'</td>
						<td class="nowrap">'.$d->nama_kar.'</td>
						<td class="nowrap">'.$this->formatter->getDateMonthFormatUser($d->tgl_masuk).'</td>
						<td class="nowrap">'.$this->formatter->getDateMonthFormatUser($d->tgl_keluar).'</td>
						<td class="nowrap">'.(!empty($d->alasan)?$d->nama_alasan:$this->otherfunctions->getMark()).'</td>
						<td class="nowrap">'.(!empty($d->nama_mengetahui)?$d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark()).'</td>
						<td class="nowrap">'.(!empty($d->nama_menyetujui)?$d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark()).'</td>
						<td class="nowrap" width="10px">'.$delete.'</td>
						</tr>';
					$no++;
				}
				$datax = ['table'=>$table];
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeKaryawanNonAktif();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_karyawan_tidak_aktif()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if($usage == 'view_anak'){
				$id = $this->input->post('id_karyawan');
					$tabel_anak='';
					$tabel_anak.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>Nama</th>
          							<th>TTL</th>
          							<th>L/P</th>
          							<th>Pendidikan</th>
          							<th>No. HP</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_anak=$this->model_karyawan->getListAnak($id);
          					if ($data_anak!=null){
          						$no=1;
          						foreach ($data_anak as $d_m) {
          							$tabel_anak.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_m->nama_anak.'</td>
          							<td>'.$this->otherfunctions->getGender($d_m->kelamin_anak).'</td>
          							<td>'.$d_m->tempat_lahir_anak.', '.$this->formatter->getDateMonthFormatUser($d_m->tanggal_lahir_anak).'</td>
          							<td>'.$d_m->pendidikan_anak.'</td>
          							<td>'.$d_m->no_telp.'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_anak('.$d_m->id_anak.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_anak.='<tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_anak.='</tbody>
	          			</table>';
					$datax=[
						'tabel_anak'=>$tabel_anak,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_saudara'){
				$id = $this->input->post('id_karyawan');
					$tabel_saudara='';
					$tabel_saudara.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>Nama</th>
          							<th>TTL</th>
          							<th>L/P</th>
          							<th>Pendidikan</th>
          							<th>No. HP</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_saudara=$this->model_karyawan->saudara($id);
          					if($data_saudara!=null){
          						$no=1;
          						foreach ($data_saudara as $d_m) {
          							$tabel_saudara.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_m->nama_saudara.'</td>
          							<td>'.$this->otherfunctions->getGender($d_m->jenis_kelamin_saudara).'</td>
          							<td>'.$d_m->tempat_lahir_saudara.', '.$this->formatter->getDateMonthFormatUser($d_m->tanggal_lahir_saudara).'</td>
          							<td>'.$d_m->pendidikan_saudara.'</td>
          							<td>'.$d_m->no_telp_saudara.'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_saudara('.$d_m->id_saudara.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_saudara.='<tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_saudara.='</tbody>
	          			</table>';
					$datax=[
						'tabel_saudara'=>$tabel_saudara,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_formal'){
				$id = $this->input->post('id_karyawan');
					$tabel_formal='';
					$tabel_formal.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>Jenjang</th>
          							<th>Nama Sekolah</th>
          							<th>Jurusan</th>
          							<th>Fakultas</th>
          							<th>Tahun Keluar</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_formal=$this->model_karyawan->pendidikan($id);
          					if($data_formal!=null){
          						$no=1;
          						foreach ($data_formal as $d_m) {
          							$tabel_formal.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_m->jenjang_pendidikan.'</td>
          							<td>'.$d_m->nama_sekolah.'</td>
          							<td>'.$d_m->jurusan.'</td>
          							<td>'.$d_m->fakultas.'</td>
          							<td>'.$d_m->tahun_keluar.'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_formal('.$d_m->id_k_pendidikan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_formal.='<tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_formal.='</tbody>
	          			</table>';
					$datax=[
						'tabel_formal'=>$tabel_formal,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_nformal'){
				$id = $this->input->post('id_karyawan');
					$tabel_nformal='';
					$tabel_nformal.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>Nama</th>
          							<th>Tanggal Masuk</th>
          							<th>Sertifikat</th>
          							<th>Nama Lembaga</th>
          							<th>Alamat</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_nformal=$this->model_karyawan->pnf($id);
          					if($data_nformal!=null){
          						$no=1;
          						foreach ($data_nformal as $d_m) {
          							$tabel_nformal.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_m->nama_pnf.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_m->tanggal_masuk_pnf).'</td>
          							<td>'.$d_m->sertifikat_pnf.'</td>
          							<td>'.$d_m->nama_lembaga_pnf.'</td>
          							<td>'.$d_m->alamat_pnf.'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_nformal('.$d_m->id_k_pnf.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_nformal.='<tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_nformal.='</tbody>
	          			</table>';
					$datax=[
						'tabel_nformal'=>$tabel_nformal,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_org'){
				$id = $this->input->post('id_karyawan');
					$tabel_org='';
					$tabel_org.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>Nama Organisasi</th>
          							<th>Tanggal Masuk</th>
          							<th>Tanggal Keluar</th>
          							<th>Jabatan</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_org=$this->model_karyawan->organisasi($id);
          					if($data_org!=null){
          						$no=1;
          						foreach ($data_org as $d_m) {
          							$tabel_org.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_m->nama_organisasi.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_m->tahun_masuk).'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_m->tahun_keluar).'</td>
          							<td>'.$d_m->jabatan_org.'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_org('.$d_m->id_k_organisasi.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_org.='<tr><td colspan="6" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_org.='</tbody>
	          			</table>';
					$datax=[
						'tabel_org'=>$tabel_org,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_penghargaan'){
				$id = $this->input->post('id_karyawan');
					$tabel_penghargaan='';
					$tabel_penghargaan.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>Nama Penghargaan</th>
          							<th>Tanggal</th>
          							<th>Peringatan</th>
          							<th>Menetapkan</th>
          							<th>Penyelenggara</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_penghargaan=$this->model_karyawan->penghargaan($id);
          					if($data_penghargaan!=null){
          						$no=1;
          						foreach ($data_penghargaan as $d_m) {
          							$tabel_penghargaan.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_m->nama_penghargaan.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_m->tahun).'</td>
          							<td>'.$d_m->peringkat.'</td>
          							<td>'.$d_m->yg_menetapkan.'</td>
          							<td>'.$d_m->penyelenggara.'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_penghargaan('.$d_m->id_k_penghargaan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_penghargaan.='<tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_penghargaan.='</tbody>
	          			</table>';
					$datax=[
						'tabel_penghargaan'=>$tabel_penghargaan,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_bahasa'){
				$id = $this->input->post('id_karyawan');
					$tabel_bahasa='';
					$tabel_bahasa.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>Bahasa</th>
          							<th>Membaca</th>
          							<th>Menulis</th>
          							<th>Berbicara</th>
          							<th>Mendengar</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_bahasa=$this->model_karyawan->bahasa($id);
          					if($data_bahasa!=null){
          						$no=1;
          						foreach ($data_bahasa as $d) {
          							$tabel_bahasa.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$this->otherfunctions->getBahasa($d->bahasa).'</td>
          							<td>'.$this->otherfunctions->getRadio($d->membaca).'</td>
          							<td>'.$this->otherfunctions->getRadio($d->menulis).'</td>
          							<td>'.$this->otherfunctions->getRadio($d->berbicara).'</td>
          							<td>'.$this->otherfunctions->getRadio($d->mendengar).'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_bahasa('.$d->id_k_bahasa.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_bahasa.='<tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_bahasa.='</tbody>
	          			</table>';
					$datax=[
						'tabel_bahasa'=>$tabel_bahasa,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_mutasi'){
				$id = $this->input->post('id_karyawan');
					$tabel_mutasi='';
					$tabel_mutasi.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>Nama</th>
          							<th>NO SK</th>
          							<th>Tanggal SK</th>
          							<th>Status</th>
          							<th>Jabatan Baru</th>
          							<th>Lokasi Baru</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_mutasi=$this->model_karyawan->getListMutasiNik($id);
          					if($data_mutasi!=null){
          						$no=1;
          						foreach ($data_mutasi as $d_m) {
          							$tabel_mutasi.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_m->nama_karyawan.'</td>
          							<td>'.$d_m->no_sk.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_m->tgl_sk).'</td>
          							<td>'.$d_m->nama_status.'</td>
          							<td>'.$d_m->nama_jabatan_baru.'</td>
          							<td>'.$d_m->nama_loker_baru.'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_mutasi('.$d_m->id_mutasi.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_mutasi.='<tr><td colspan="8" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_mutasi.='</tbody>
	          			</table>';
					$datax=[
						'tabel_mutasi'=>$tabel_mutasi,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_perjanjian'){
				$id = $this->input->post('id_karyawan');
					$tabel_perjanjian='';
					$tabel_perjanjian.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>NO SK</th>
          							<th>Tanggal SK</th>
          							<th>Status</th>
          							<th>Tanggal Berlaku</th>
          							<th>Berlaku Sampai</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_perjanjian=$this->model_karyawan->getListPerjanjianKerjaNik($id);
          					if($data_perjanjian!=null){
          						$no=1;
          						foreach ($data_perjanjian as $d_p) {
          							$tabel_perjanjian.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_p->no_sk_baru.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_sk_baru).'</td>
          							<td>'.$d_p->nama_status_baru.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_berlaku_baru).'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->berlaku_sampai_baru).'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_perjanjian('.$d_p->id_p_kerja.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_perjanjian.='<tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_perjanjian.='</tbody>
	          			</table>';
					$datax=[
						'tabel_perjanjian'=>$tabel_perjanjian,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_peringatan'){
				$id = $this->input->post('id_karyawan');
					$tabel_peringatan='';
					$tabel_peringatan.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>NO SK</th>
          							<th>Tanggal SK</th>
          							<th>Peringatan</th>
          							<th>Tanggal Berlaku</th>
          							<th>Berlaku Sampai</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_peringatan=$this->model_karyawan->getListPeringatanNik($id);
          					if($data_peringatan!=null){
          						$no=1;
          						foreach ($data_peringatan as $d_p) {
          							$tabel_peringatan.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_p->no_sk.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_sk).'</td>
          							<td>'.$d_p->nama_status_baru.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_berlaku).'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->berlaku_sampai).'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_peringatan('.$d_p->id_peringatan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          						</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_peringatan.='<tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_peringatan.='</tbody>
	          			</table>';
					$datax=[
						'tabel_peringatan'=>$tabel_peringatan,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_grade'){
				$id = $this->input->post('id_karyawan');
					$tabel_grade='';
					$tabel_grade.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>NO SK</th>
          							<th>Tanggal SK</th>
          							<th>Grade</th>
          							<th>Tanggal Berlaku</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_grade=$this->model_karyawan->getListGradeNik($id);
          					if($data_grade!=null){
          						$no=1;
          						foreach ($data_grade as $d_p) {
          							$tabel_grade.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_p->no_sk.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_sk).'</td>
          							<td>'.$d_p->nama_grade_baru.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl_berlaku).'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_grade('.$d_p->id_grade.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          							</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_grade.='<tr><td colspan="6" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_grade.='</tbody>
	          			</table>';
					$datax=[
						'tabel_grade'=>$tabel_grade,
					];
				echo json_encode($datax);
			}elseif($usage == 'view_kecelakaan'){
				$id = $this->input->post('id_karyawan');
					$tabel_kecelakaan='';
					$tabel_kecelakaan.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-green">
          							<th>No.</th>
          							<th>No Kecelakaan</th>
          							<th>Tanggal</th>
          							<th>Kecelakaan Kerja</th>
          							<th>Rumah Sakit</th>
          							<th>Aksi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_kecelakaan=$this->model_karyawan->getListKecelakaanKerjaNik($id);
          					if($data_kecelakaan!=null){
          						$no=1;
          						foreach ($data_kecelakaan as $d_p) {
          							$tabel_kecelakaan.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_p->no_sk.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_p->tgl).'</td>
          							<td>'.$d_p->nama_kategori_kecelakaan.'</td>
          							<td>'.$d_p->nama_rs.'</td>
          							<td><center><button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_kecelakaan('.$d_p->id_kecelakaan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button></center></td>
          							</tr>';
          						$no++;
          						}
          					}else{
          						$tabel_kecelakaan.='<tr><td colspan="6" class="text-center">Tidak Ada Data</td></tr>';
          					}
	          				$tabel_kecelakaan.='</tbody>
	          			</table>';
					$datax=[
						'tabel_kecelakaan'=>$tabel_kecelakaan,
					];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_status_emp(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_karyawan=$this->input->post('id_karyawan');
		if ($id_karyawan != "") {
			$data=[
				'status_emp'=>'1',
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id_karyawan]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function edit_karyawan_tidak_aktif(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$id_karyawan=$this->input->post('id_karyawan');
		if ($id != "" && $id_karyawan != "") {
			$data=array(
				'id_karyawan'=>$id_karyawan,
				'no_sk'=>strtoupper($this->input->post('no_sk')),
				'tgl_sk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk')),
				'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku')),
				'tgl_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tgl_keluar')),
				'alasan'=>$this->input->post('alasan'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'keterangan'=>$this->input->post('keterangan'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_karyawan_tidak_aktif',['id_kta'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_karyawan_tidak_aktif(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk');
		$id=$this->input->post('id_karyawan');
		if ($kode != "" && $id != "") {
			$data=array(
				'id_karyawan'=>$id,
				'no_sk'=>strtoupper($this->input->post('no_sk')),
				'tgl_sk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_sk')),
				'tgl_berlaku'=>$this->formatter->getDateFormatDb($this->input->post('tgl_berlaku')),
				'tgl_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tgl_keluar')),
				'alasan'=>$this->input->post('alasan'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'keterangan'=>$this->input->post('keterangan'),
			);
				$data_kar=array('status_emp'=>0);
				$where = array('id_karyawan' => $this->input->post('id_karyawan') );
				$this->model_global->updateQuery($data_kar,'karyawan',$where);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));

			$datax = $this->model_global->insertQueryCC($data,'data_karyawan_tidak_aktif',$this->model_karyawan->checkMutasiCode($data['no_sk']));
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
// =================================== EXIT INTERVIEW ===========================================
	public function karyawan_exit_interview()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListExitInterview();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getListExitInterview('search',$where);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_exit,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$properties['aksi']=str_replace('view_modal', 'view_modal_exit', $properties['aksi']);
					$properties['aksi']=str_replace('delete_modal', 'delete_modal_exit', $properties['aksi']);
					$properties['cetak']=str_replace('do_print', 'do_print_exit', $properties['cetak']);
					$datax['data'][]=[
						$d->id_exit,
						$d->nik_karyawan,
						// '<a href="'.base_url("pages/view_karyawan_non_aktif/".$this->codegenerator->encryptChar($d->nik_karyawan)).'">'.$d->nik_karyawan.'</a>',
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_loker,
						$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						$this->formatter->getDateMonthFormatUser($d->tgl_keluar),
						$d->nama_alasan,
						$properties['tanggal'],
						$properties['aksi'].$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_exit');
				$data=$this->model_karyawan->getListExitInterviewID($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_exit,
						'tgl_masuk'=>$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						'tgl_keluar'=>$this->formatter->getDateMonthFormatUser($d->tgl_keluar),
						'id_karyawan'=>$d->id_karyawan,
						'status_karyawan'=>$d->status_emp,
						'nik'=>$d->nik,
						'nama'=>$d->nama_karyawan,
						'loker'=>$d->nama_loker,
						'jabatan'=>$d->nama_jabatan,
						'alasan_keluar'=>$d->nama_alasan,
						'alasan_keluar_e'=>$d->alasan_keluar,
						'setelah'=>(!empty($d->setelah)) ? $d->setelah:$this->otherfunctions->getMark(),
						'posisi'=>(!empty($d->posisi)) ? $d->posisi:$this->otherfunctions->getMark(),
						'tertarik'=>(!empty($d->tertarik)) ? $d->tertarik:$this->otherfunctions->getMark(),
						'kompensasi'=>(!empty($d->kompensasi)) ? $d->kompensasi:$this->otherfunctions->getMark(),
						'penilaian'=>(!empty($d->penilaian)) ? $this->otherfunctions->getRadio($d->penilaian):$this->otherfunctions->getMark(),
						'penilaian_e'=>$d->penilaian,
						'alasan'=>(!empty($d->alasan)) ? $d->alasan:$this->otherfunctions->getMark(),
						'lingkungan'=>(!empty($d->lingkungan)) ? $d->lingkungan:$this->otherfunctions->getMark(),
						'support'=>(!empty($d->support)) ? $d->support:$this->otherfunctions->getMark(),
						'pelatihan'=>(!empty($d->pelatihan)) ? $d->pelatihan:$this->otherfunctions->getMark(),
						'saran'=>(!empty($d->saran)) ? $d->saran:$this->otherfunctions->getMark(),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'etgl_masuk'=>$this->formatter->getDateFormatUser($d->tgl_masuk),
						'etgl_keluar'=>$this->formatter->getDateFormatUser($d->tgl_keluar),
						'ealasan'=>$d->alasan,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeKaryawanNonAktif();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_exit_interview(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id_karyawan');
		if ($id != "") {
			$data=array(
				'id_karyawan'=>$id,
				'tgl_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tgl_keluar')),
				'alasan_keluar'=>$this->input->post('alasan_keluar'),
				'setelah'=>$this->input->post('setelah'),
				'posisi'=>$this->input->post('posisi'),
				'tertarik'=>$this->input->post('tertarik'),
				'kompensasi'=>$this->input->post('kompensasi'),
				'penilaian'=>$this->input->post('penilaian'),
				'alasan'=>$this->input->post('alasan'),
				'lingkungan'=>$this->input->post('lingkungan'),
				'support'=>$this->input->post('support'),
				'pelatihan'=>$this->input->post('pelatihan'),
				'saran'=>$this->input->post('saran'),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'data_exit_interview');
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_exit_interview(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$idk=$this->input->post('id_karyawan');
		if ($id != "") {
			$data=array(
				'id_karyawan'=>$idk,
				'tgl_keluar'=>$this->formatter->getDateFormatDb($this->input->post('tgl_keluar')),
				'alasan_keluar'=>$this->input->post('alasan_keluar'),
				'setelah'=>$this->input->post('setelah'),
				'posisi'=>$this->input->post('posisi'),
				'tertarik'=>$this->input->post('tertarik'),
				'kompensasi'=>$this->input->post('kompensasi'),
				'penilaian'=>$this->input->post('penilaian'),
				'alasan'=>$this->input->post('alasan'),
				'lingkungan'=>$this->input->post('lingkungan'),
				'support'=>$this->input->post('support'),
				'pelatihan'=>$this->input->post('pelatihan'),
				'saran'=>$this->input->post('saran'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_exit_interview',['id_exit' => $id]);
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--EMP ANAK--//
	public function emp_anak_non(){
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListAnak($nik);
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_anak,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$ttl = $d->tempat_lahir_anak.', '.$this->formatter->getDateMonthFormatUser($d->tanggal_lahir_anak);
					$gender = $this->otherfunctions->getGender($d->kelamin_anak);
					$educa = $this->otherfunctions->getEducate($d->pendidikan_anak);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_anak('.$d->id_anak.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_anak,
						(!empty($d->nama_anak)) ? $d->nama_anak:$this->otherfunctions->getMark($d->nama_anak),
						(!empty($ttl)) ? $ttl:$this->otherfunctions->getMark($ttl),
						(!empty($gender)) ? $gender:$this->otherfunctions->getMark($gender),
						(!empty($educa)) ? $educa:$this->otherfunctions->getMark($educa),
						(!empty($d->no_telp)) ? $d->no_telp:$this->otherfunctions->getMark($d->no_telp),
						$aksi,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_anak');
				$data=$this->model_karyawan->getAnak($id,$nik);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_anak,
						'nama_anak'=>$d->nama_anak,
						'nik'=>$d->nik,
						'kelamin_anak'=>$d->kelamin_anak,
						'getkelamin_anak'=>$this->otherfunctions->getGender($d->kelamin_anak),
						'tempat_lahir_anak'=>$d->tempat_lahir_anak,
						'tanggal_lahir_anak'=>$this->formatter->getDateFormatUser($d->tanggal_lahir_anak),
						'getTTL'=>$d->tempat_lahir_anak.', '.$this->formatter->getDateMonthFormatUser($d->tanggal_lahir_anak),
						'pendidikan_anak'=>$d->pendidikan_anak,
						'getPendidikan'=>$this->otherfunctions->getEducate($d->pendidikan_anak),
						'no_telp'=>$d->no_telp,
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
 	public function cek_id_finger()
 	{
 		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
 		$id = $this->input->post('id_finger');
		$d=$this->model_karyawan->emp_finger($id);
		$jml_id = count($d);
		if($jml_id > 0){
			$val = 'false';
		}else{
			$val = 'true';
		}
		$datax=['val' => $val];
		echo json_encode($datax);
 	}
 	public function cek_id_finger_nik()
 	{
 		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
 		$id = $this->input->post('id_finger');
 		$idx = $this->input->post('id_karyawan');
		$d=$this->model_karyawan->emp_finger($id);
		$dx=$this->model_karyawan->finger_id($idx);
		$ff=$this->otherfunctions->convertResultToRowArray($dx);
		$jml_id = count($d);
		if($ff['id_finger'] == $id){
			$val = 'yes';
		}elseif($jml_id > 0){
			$val = 'false';
		}else{
			$val = 'true';
		}
		$datax=['val' => $val];
		echo json_encode($datax);
 	}
 	public function cek_status_karyawan()
 	{
 		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
 		$status = $this->input->post('status');
		$data=$this->model_karyawan->cekPilihPerjanjian($status);
		$select='';
            foreach ($data as $d) {
            	$select.='<option value="'.$d->kode_perjanjian.'">'.$d->nama.'</option>';
        	}
		$datax=['perjanjian'=>$select];
		echo json_encode($datax);
 	}
 	public function cek_kode_perjanjian()
 	{
 		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
 		$kode_perjanjian = $this->input->post('kode');
		$data=$this->model_karyawan->cekPilihStatusKaryawan($kode_perjanjian);
		$select='';
		$nama_status='';
		foreach ($data as $d) {
			$select.='<option value="'.$d->kode_status.'">'.$d->nama_status.'</option>';
			$nama_status .= $d->nama_status;
		}
		$datax=[
			'status_karyawan'=>$select,
			'nama_status'=>$nama_status,
		];
		echo json_encode($datax);
 	}
	public function data_pesan(){
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data_pesan=$this->model_karyawan->getDataPesan();
				$c=[];
				$ada='<div class="btn-group pull-right">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fas fa-trash"></i> Hapus Pesan <span class="fa fa-caret-down"></span></button>
                  <ul class="dropdown-menu" role="menu" style="background: #C3FCC0">
                    <li><a data-toggle="modal" href="#del"> Hapus Semua Pesan Untuk Saya</a></li>
                    <li><a data-toggle="modal" href="#del_d">Hapus Semua Pesan Dari Database</button</a></li>
                  </ul>
                </div><br><br>';
				if(isset($data_pesan)){
					foreach ($data_pesan as $d) {
						$for=$this->otherfunctions->getDataExplode($d->id_for,';','all');
						$read=(!empty($d->id_read)?$this->otherfunctions->getDataExplode($d->id_read,';','all'):[]);
						$del=(!empty($d->id_del)?$this->otherfunctions->getDataExplode($d->id_del,';','all'):[]);
						if(!in_array($this->admin,$read)){
                        	$stts = '<i class="fa fa-circle" title="Belum Dibaca" style="color:red;"></i>';
						}else{
                        	$stts = '<i class="fa fa-check" title="Sudah Dibaca" style="color:green;"></i>';
						}
						if(in_array($this->admin,$for)){
							// if(!in_array($this->admin,$read)){
								if(!in_array($this->admin,$del)){
									$ada.='<a href="'.base_url('pages/read_message/'.$this->codegenerator->encryptChar($d->id_pesan)).'" class="list-group-item">';
									$ada.='<h4 class="list-group-item-heading"> '.$d->nama_karyawan.'  <label class="label label-warning"> '.$d->nama_jenis.'</label><small class="text-muted pull-right">'.$this->formatter->getDateTimeMonthFormatUser($d->create_date).' WIB</small></h4><span class="pull-right">'.$stts.'</span>
                        			<label class="label bg-teal disabled color-palette"><i class="fa fa-info-circle"></i> '.$d->judul.'</label><br>
                      				</a>';
									array_push($c,1);
								}
							// }
						}
					}
				}
				if (count($c) == 0) {
					$ada='<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
				}
				$data=[	'count'=>count($c),
						'value'=>$ada,
					];
        		echo json_encode($data);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_karyawan->getPesanID($id);
				$datax=[];
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_pesan,
						'nama_karyawan'=>$d->nama_karyawan,
						'judul'=>$d->judul,
						'nama_jenis'=>$d->nama_jenis,
						'isi'=>$d->isi,
						'create'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'getmessageunread') {
				$data_pesan=$this->model_karyawan->getDataPesan();
				$c=[];
				$ada='<li class="header">Pesan Baru</li><li class="divider"></li>';
				if(isset($data_pesan)){
					foreach ($data_pesan as $d) {
						$for=$this->otherfunctions->getDataExplode($d->id_for,';','all');
						$read=(!empty($d->id_read)?$this->otherfunctions->getDataExplode($d->id_read,';','all'):[]);
						$del=(!empty($d->id_del)?$this->otherfunctions->getDataExplode($d->id_del,';','all'):[]);
						if(in_array($this->admin,$for)){
							if(!in_array($this->admin,$read)){
								if(!in_array($this->admin,$del)){
									$ada.='<li><a href="'.base_url('pages/read_message/'.$this->codegenerator->encryptChar($d->id_pesan)).'"><i class="fas fa-user"></i> '.$d->nama_karyawan.' - '.$d->nama_jabatan.' <small class="text-muted pull-right" style="color:red; font-size:8pt;">'.$d->nama_jenis.'</small></a></li>';
									array_push($c,1);
								}
							}
						}
					}
					$ada.='<li class="footer"><a href="'.base_url('pages/data_pesan').'">Tampilkan Semua</a></li>';
				}
				if (count($c) == 0) {
					$ada='<li class="text-center"><small class="text-muted"><i class="icon-close"></i> Tidak Ada Data</small></li><li class="divider"> </li>';
				}
				$data=[	'count'=>count($c),
						'value'=>$ada,
					];
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function status_read(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$idpesan=$this->input->post('id');
		$cek = $this->model_karyawan->pesanID($idpesan);
		$id_read=(!empty($cek['id_read'])?explode(';', $cek['id_read']):[]);
		$id_del=(!empty($cek['id_del'])?explode(';', $cek['id_del']):[]);
		if (!in_array($this->admin, $id_del)) {
			if (!in_array($this->admin, $id_read)) {
				array_push($id_read, $this->admin);
			}
			if (isset($id_read)) {
				$idd=implode(';', array_unique(array_filter($id_read)));
			}else{
				$idd=NULL;
			}
			if($cek['status']==1){
				$data=['id_read'=>$idd,'status'=>0];
				$this->model_global->updateQueryNoMsg($data,'data_pesan',['id_pesan'=>$idpesan]);
			}
		}
		echo json_encode(['status_data'=>'no_msg']);
	}
	public function status_delete_berita(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$idpesan=$this->input->post('id');
		$cek = $this->model_karyawan->pesanID($idpesan);
		$id_del=(!empty($cek['id_del'])?explode(';', $cek['id_del']):[]);
			if (!in_array($this->admin, $id_del)) {
				array_push($id_del, $this->admin);
			}
			if (isset($id_del)) {
				$idd=implode(';', array_unique(array_filter($id_del)));
			}else{
				$idd=NULL;
			}
			$data=['id_del'=>$idd];
			$datax=$this->model_global->updateQuery($data,'data_pesan',['id_pesan'=>$idpesan]);
		echo json_encode($datax);
	}
	function del_all_pesan_user(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$ceka=$this->db->query("SELECT * FROM data_pesan")->result();
		foreach ($ceka as $cek) {
			$id=explode(';', $cek->id_del);
			if (!in_array($this->admin, $id)) {
				array_push($id, $this->admin);
			}
			if (isset($id)) {
				$idd=implode(';', array_unique(array_filter($id)));
			}else{
				$idd=NULL;
			}
			$data=['id_del'=>$idd];
			$this->db->update('data_pesan',$data);
			$datax=$this->messages->allGood();
		}
		echo json_encode($datax);
	}
	function del_all_pesan_database(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$in=$this->db->query("TRUNCATE TABLE data_pesan");
		if ($in) {
			$datax=$this->messages->delGood();
		}else{
			$datax=$this->messages->delFailure(); 
		}
		echo json_encode($datax);
	}
	
	//========================================================= DATA NON KARYAWAN ========================================================//
	//====================================================================================================================================//
	// Data Non Karyawan
	public function data_non_karyawan()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListNonKaryawan();
				$access=unserialize(base64_decode($this->input->post('access')));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_non,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_non,
						$d->nik,
						$d->nama,
						$d->no_telp,
						$d->nama_jenis,
						$d->alamat,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_non');
				$data=$this->model_karyawan->getListNonKaryawan(['a.id_non'=>$id]);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_non,
						'nama'=>$d->nama,
						'nik'=>$d->nik,
						'no_telp'=>$d->no_telp,
						'alamat'=>$d->alamat,
						'jenis'=>$d->jenis,
						'nama_jenis'=>$d->nama_jenis,
						'keterangan'=>$d->keterangan,
						'npwp'=>$d->npwp,
						'status_pajak'=>$this->otherfunctions->getStatusPajak($d->status_pajak),
						'status_pajak_e'=>$d->status_pajak,
						'perhitungan_pajak'=>$this->otherfunctions->getJenisPerhitunganPajakKey($d->perhitungan_pajak),
						'perhitungan_pajak_e'=>$d->perhitungan_pajak,
						'view_no_telp'=>(!empty($d->no_telp)) ? $d->no_telp:$this->otherfunctions->getMark(),
						'view_alamat'=>(!empty($d->alamat)) ? $d->alamat:$this->otherfunctions->getMark(),
						'view_keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
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
	function add_non_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nama=$this->input->post('nama');
		if ($nama != "") {
			$data=[
				'nama'       =>$nama,
				'nik'        =>$this->input->post('nik'),
				'no_telp'    =>$this->input->post('no_telp'),
				'alamat'     =>$this->input->post('alamat'),
				'jenis'    	 =>$this->input->post('jenis'),
				'keterangan' =>$this->input->post('keterangan'),
				'status_pajak' =>$this->input->post('status_pajak'),
				'perhitungan_pajak' =>$this->input->post('perhitungan_pajak'),
				'npwp' =>$this->input->post('npwp'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'data_non_karyawan');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_non_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'nama'       =>$this->input->post('nama'),
				'nik'        =>$this->input->post('nik'),
				'jenis'    	 =>$this->input->post('jenis'),
				'no_telp'    =>$this->input->post('no_telp'),
				'alamat'     =>$this->input->post('alamat'),
				'keterangan' =>$this->input->post('keterangan'),
				'status_pajak' =>$this->input->post('status_pajak'),
				'perhitungan_pajak' =>$this->input->post('perhitungan_pajak'),
				'npwp' =>$this->input->post('npwp'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_non_karyawan',['id_non'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//====================================================================================================================================//
	// Data Transaksi Non Karyawan
	public function pilih_non_karyawan()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getListNonKaryawan();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_non,
				'<a class="pilih" style="cursor:pointer" 
				data-id_non			="'.$d->id_non.'" 
				data-nik			="'.$d->nik.'" 
				data-nama			="'.$d->nama.'" 
				data-no_telp		="'.$d->no_telp.'" 
				data-alamat			="'.$d->alamat.'">'.
				$d->nik.'</a>',
				$d->nama,
				$d->no_telp,
				$d->alamat,
			];
		}
		echo json_encode($datax);		
	}
	public function transaksi_non_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListTransaksiNonKaryawanX(null,false);
				}else{
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['MONTH(a.tanggal)'=>$bulan,'YEAR(a.tanggal)'=>$tahun];
					$data=$this->model_karyawan->getListTransaksiNonKaryawanX($where,false);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id,
						$d->nik,
						// '<a href="'.base_url('pages/view_transaksi_non_karyawan/'.$this->codegenerator->encryptChar($d->id_non)).'">'.$d->nik.'</a>',
						$d->nama_non,
						$d->nomor,
						$this->formatter->getDateMonthFormatUser($d->tanggal),
						$this->formatter->getFormatMoneyUser($d->biaya),
						$this->formatter->getFormatMoneyUser($d->thr),
						$d->kegiatan,
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_karyawan->getListTransaksiNonKaryawanX(['a.id'=>$id]);
				foreach ($data as $d) {
					// $tabel='';
					// $tabel.='<h4 align="center"><b>Data Mutasi '.$d->nama_non.'</b></h4>
					// 	<div style="max-height: 400px; overflow: auto;">
          			// 	<table class="table table-bordered table-striped data-table">
          			// 		<thead>
          			// 			<tr class="bg-blue">
          			// 				<th>No.</th>
          			// 				<th>Nama</th>
          			// 				<th>Nomor</th>
          			// 				<th>Tanggal</th>
          			// 				<th>Kegiatan</th>
          			// 				<th>Biaya</th>
          			// 				<th>Keterangan</th>
          			// 			</tr>
          			// 		</thead>
          			// 		<tbody>';
          			// 		$data_tr=$this->model_karyawan->getListTransaksiNonKaryawan(['a.id_non'=>$d->id_non]);
          			// 			$no=1;
          			// 			foreach ($data_tr as $d_m) {
          			// 				$tabel.='<tr>
          			// 				<td>'.$no.'</td>
          			// 				<td>'.$d_m->nama_non.'</td>
          			// 				<td>'.$d_m->nomor.'</td>
          			// 				<td>'.$this->formatter->getDateMonthFormatUser($d_m->tanggal).'</td>
          			// 				<td>'.$d_m->kegiatan.'</td>
          			// 				<td>'.$this->formatter->getFormatMoneyUser($d_m->biaya).'</td>
          			// 				<td>'.$d_m->keterangan.'</td>
          			// 			</tr>';
          			// 			$no++;
          			// 		}
	          		// 		$tabel.='</tbody>
	          		// 	</table>';
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'tabel'=>null,
						'id'=>$d->id,
						'id_non'=>$d->id_non,
						'no_sk'=>$d->nomor,
						'nomor'=>$d->nomor,
						'alamat'=>$d->alamat,
						'no_telp'=>$d->no_telp,
						'tgl_sk'=>$this->formatter->getDateMonthFormatUser($d->tanggal),
						'nik'=>$d->nik,
						'nama'=>$d->nama_non,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tanggal),
						'biaya'=>$this->formatter->getFormatMoneyUser($d->biaya),
						'thr'=>$this->formatter->getFormatMoneyUser($d->thr),
						'kegiatan'=>$d->kegiatan,
						'keterangan'=>$d->keterangan,
						'mengetahui'=>$d->mengetahui,
						'menyetujui'=>$d->menyetujui,
						'keterangan'=>$d->keterangan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'tgl_sk_e'=>$this->formatter->getDateFormatUser($d->tanggal),
						'vmengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'vmenyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'vketerangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeTransaksiNonKaryawan();
        		echo json_encode($data);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'cekjabatan') {
				if (!$this->input->is_ajax_request()) 
				   redirect('not_found');
				$jabatan=$this->input->post('jabatan');
		 		$idk = $this->input->post('id_karyawan');
				$jab = $this->model_karyawan->cekJabatan($jabatan);
				$jml = count($jab);
				if ($jml > 0){
					$val = 'false';
				} else{
					$val = 'true';
				}
				$datax=['val' => $val];
				echo json_encode($datax);
			}elseif ($usage == 'refreshkaryawan') {
				$data = $this->model_karyawan->getRefreshKaryawan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_transaksi_non_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_non = $this->codegenerator->decryptChar($this->uri->segment(4));
				$data = $this->model_karyawan->getListTransaksiNonKaryawan(['a.id_non'=>$id_non]);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id,
						$d->nama_non,
						$d->nomor,
						$this->formatter->getDateMonthFormatUser($d->tanggal),
						$d->kegiatan,
						$this->formatter->getFormatMoneyUser($d->biaya),
						$d->keterangan,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],//.$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_karyawan->getListTransaksiNonKaryawan(['a.id'=>$id]);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$datax=[
						'id'=>$d->id,
						'id_non'=>$d->id_non,
						'no_sk'=>$d->nomor,
						'alamat'=>$d->alamat,
						'no_telp'=>$d->no_telp,
						'tgl_sk'=>$this->formatter->getDateMonthFormatUser($d->tanggal),
						'nik'=>$d->nik,
						'nama'=>$d->nama_non,
						'mengetahui'=>$d->mengetahui,
						'menyetujui'=>$d->menyetujui,
						'keterangan'=>$d->keterangan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'tgl_sk_e'=>$this->formatter->getDateFormatUser($d->tanggal),
						'vmengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'vmenyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'vketerangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'biaya'=>$this->formatter->getFormatMoneyUser($d->biaya),
						'thr'=>$this->formatter->getFormatMoneyUser($d->thr),
						'kegiatan'=>$d->kegiatan,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_transaksi_non_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'nomor'=>$this->input->post('nomor'),
				'tanggal'=>$this->formatter->getDateFormatDb($this->input->post('tanggal')),
				'biaya'=>$this->formatter->getFormatMoneyDb($this->input->post('biaya')),
				'thr'=>$this->formatter->getFormatMoneyDb($this->input->post('thr')),
				'kegiatan'=>$this->input->post('kegiatan'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'keterangan'=>$this->input->post('keterangan'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'transaksi_non_karyawan',['id'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_transaksi_non_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk');
		if ($kode != "") {
			$data=array(
				'id_non'=>$this->input->post('id_non'),
				'nomor'=>$this->input->post('no_sk'),
				'tanggal'=>$this->formatter->getDateFormatDb($this->input->post('tanggal')),
				'kegiatan'=>$this->input->post('kegiatan'),
				'biaya'=>$this->formatter->getFormatMoneyDb($this->input->post('biaya')),
				'thr'=>$this->formatter->getFormatMoneyDb($this->input->post('thr')),
				'keterangan'=>$this->input->post('keterangan'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'transaksi_non_karyawan');
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
}