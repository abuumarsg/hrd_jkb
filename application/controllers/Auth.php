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
class Auth extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		// $this->date = '2023-07-01 00:00:39';
		$this->rando = $this->codegenerator->getPin(50,'full');
		$this->conf=$this->otherfunctions->configEmail(); 
		$this->otherfunctions->syncResetCuti($this->date);
	} 
	public function tes_email()
	{
		$this->load->library('email');
		$this->email->initialize($this->otherfunctions->configEmail());
		$this->email->from('hsoftjkb@gmail.com', 'Your Name');
		$this->email->to('abuumarsg@gmail.com');
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');
		$this->email->send();
		print_r($this->email->print_debugger());
	}
	public function index(){
		if(!empty($_COOKIE['nik'])){
			if(!empty($_COOKIE['pages']) == 'adm'){
				redirect('pages');
			}
			if(!empty($_COOKIE['pages']) == 'emp'){
				redirect('kpages');
			}
		}
		if ($this->session->has_userdata('adm')) {
			redirect('pages'); 	
		}elseif ($this->session->has_userdata('emp')) {
			redirect('kpages');
		}
		$this->load->view('admin_tem/header');
		$this->load->view('utama/login');
		$this->load->view('admin_tem/footer');
	}
	public function logout()
	{
		if ($this->session->has_userdata('adm')) {
			$data=['status'=>0];
			$this->db->where('id_admin',$this->session->userdata('adm')['id']);
			$this->db->update('admin',$data);
		}
		if ($this->session->has_userdata('emp')) {
			$data=['status'=>0];
			$this->db->where('id_karyawan',$this->session->userdata('emp')['id']);
			$this->db->update('karyawan',$data);
		}
		setcookie('nik', '', 0, '/');
		setcookie('pages', '', 0, '/');
		$this->session->sess_destroy();
		redirect('auth');
	}
	public function secret_reg(){
		$this->load->view('admin_tem/header');
		$this->load->view('utama/register');
		$this->load->view('admin_tem/footer');
	}
	public function lupa(){
		$this->load->view('admin_tem/header');
		$this->load->view('utama/lupa');
		$this->load->view('admin_tem/footer');
	}
	public function lock(){
		$h_url=base_url('auth');
		if (isset($_SERVER['HTTP_REFERER'])) {
			$h_url=$_SERVER['HTTP_REFERER'];
		}
		if ($this->session->has_userdata('adm') && !$this->session->has_userdata('emp')) {
			$admin=$this->model_admin->getAdminById($this->session->userdata('adm')['id']);
			$data=['nama'=>$admin['nama'],'uname'=>$admin['username'],'foto'=>$admin['foto'],'url'=>$h_url];
			$this->session->unset_userdata('adm');
		}elseif (!$this->session->has_userdata('adm') && $this->session->has_userdata('emp')) {
			$emp=$this->model_karyawan->getEmployeeId($this->session->userdata('emp')['id']);
			$data=['nama'=>$emp['nama'],'uname'=>$emp['username'],'foto'=>$emp['foto'],'url'=>$h_url];
			$this->session->unset_userdata('emp');
		}elseif ($this->session->has_userdata('adm') && $this->session->has_userdata('emp')) {
			$emp=$this->model_karyawan->getEmployeeId($this->session->userdata('emp')['id']);
			$data=['nama'=>$emp['nama'],'uname'=>$emp['username'],'foto'=>$emp['foto'],'url'=>$h_url];
			$this->session->unset_userdata('adm');
			$this->session->unset_userdata('emp');
		}else{
			redirect('auth/logout');
		}
		$this->session->set_userdata('data_lock', $data);
		if ($this->session->has_userdata('data_lock')){
			$this->load->view('admin_tem/header');
			$this->load->view('utama/lock');
			$this->load->view('admin_tem/footer');
		}else{
			redirect('auth/logout');
		}
	}
	public function redirect_pages()
	{
		if ($this->session->has_userdata('adm') && $this->session->has_userdata('emp')) {
			$this->load->view('admin_tem/header');
			$this->load->view('utama/redirect');
			$this->load->view('admin_tem/footer');
		}else{
			redirect('not_found');
		}
	}
	public function do_login()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$username=$this->input->post('username');
		$captcha=$this->input->post('captcha');
		$remember=$this->input->post('remember');
		if($remember == 'on'){
			setcookie('nik', $username, strtotime('+1 year'), '/');
		}
		$password=$this->codegenerator->genPassword($this->input->post('password'));
		if ($captcha != $this->session->userdata('captcha_word')) {
			echo json_encode($this->messages->customFailure('Captcha Salah, Mohon Input Dengan Benar'));
		}else{
			if (empty($username) || empty($password)) {
				echo json_encode($this->messages->unfillForm());
			}else{
				echo json_encode($this->model_global->authSecure($username,$password));
			}
		}
	}
	public function google()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$jwt_token=$this->input->post('token');
		$parser=$this->codegenerator->jwtParser($jwt_token);
		if ($parser) {
			echo json_encode($auth=$this->model_global->authSecure('google','google',$parser->email));
		}else{
			echo json_encode($this->messages->unfillForm());
		}
	}
	function reg_in(){
		if ($this->input->post('kelamin') == 'l') {
			$f='asset/img/admin-photo/userm.png';
		}else{
			$f='asset/img/admin-photo/userf.png';
		}
		$dataadmin = array(
			'nama'=>$this->input->post('nama'),
			'alamat'=>$this->input->post('alamat'),
			'email'=>$this->input->post('email'),
			'kelamin'=>$this->input->post('kelamin'),
			'username'=>$this->input->post('username'),
			'hp'=>$this->input->post('hp'),
			'password'=>hash("sha512", $this->input->post('password2')),
			'foto'=>$f,
			'create_date'=>$this->date,
			'update_date'=>$this->date,
		);
		$data['admincek'] = $this->model_admin->avl($dataadmin['username'],$dataadmin['email']);
		if ($data['admincek'] == "") {
			$this->db->insert('admin',$dataadmin);
			$this->messages->allGood();
			redirect('auth');
		}else{
			$this->messages->duplicateData();
			redirect('auth/secret_reg');
		}
	}
	function history_url()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$url=$this->input->post('ur');
		$this->session->set_flashdata('url', $url);
	}
	function token_verified(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$token=$this->input->post('token');
		$usage=$this->input->post('usage');
		$password=$this->input->post('password2');
		if (empty($id) || empty($token) || empty($usage) || empty($password)) {
			echo json_encode($this->messages->unfillForm()); 
		}else{
			$data=['password'=>$this->codegenerator->genPassword($password),'reset_token'=>null,'email_verified'=>1];
			if ($usage == 'adm') {
				$where=array('id_admin'=>$id,'reset_token'=>$token);
				$this->db->where($where);
				$in=$this->db->update('admin',$data);
				if ($in) {
					$url['linkx']=base_url('auth');
					$return=array_merge($url,$this->messages->customGood('<span class="ec ec-plus1"></span> Password Berhasil Direset, Silahkan Login Dengan Password Baru Anda'));
				}else{
					$return = $this->messages->customFailure('<span class="ec ec-plus1"></span> Password Gagal Direset, Silahkan Ulangi Kembali Proses Reset Password');
				}
			}elseif ($usage == 'emp') {
				$where=array('id_karyawan'=>$id,'reset_token'=>$token);
				$this->db->where($where);
				$in=$this->db->update('karyawan',$data);
				if ($in) {
					$url['linkx']=base_url('auth');
					$return=array_merge($url,$this->messages->customGood('<span class="ec ec-plus1"></span> Password Berhasil Direset, Silahkan Login Dengan Password Baru Anda'));
				}else{
					$return = $this->messages->customFailure('<span class="ec ec-plus1"></span> Password Gagal Direset, Silahkan Ulangi Kembali Proses Reset Password');
				}
			}else{
				$return = $this->messages->notValidParam();
			}
			echo json_encode($return);
		}	
	}
	function token_verificator(){
		$adm_tok=$this->input->get('adm_tok');
		$emp_tok=$this->input->get('emp_tok');
		$data=[];
		if (empty($adm_tok) && !empty($emp_tok)) {
			$emp=$this->model_karyawan->getEmployeeByToken($emp_tok);
			$data=['token'=>$emp_tok,'id'=>$emp['id_karyawan'],'usage'=>'emp'];
		}elseif (!empty($adm_tok) && empty($emp_tok)) {
			$adm=$this->model_admin->getAdminByToken($adm_tok);
			$data=['token'=>$adm_tok,'id'=>$adm['id_admin'],'usage'=>'adm'];
		}else{
			$this->messages->sessNotValidParam(); 
			redirect('auth');
		}
		if (!isset($data)) {
			$this->messages->sessNotValidParam(); 
			redirect('auth');
		}else{
			$this->load->view('admin_tem/header');
			$this->load->view('utama/password',$data);
			$this->load->view('admin_tem/footer');
		}
	}
	function send_email_forget(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$em=$this->input->post('email');
		if (empty($em)){ 
			echo json_encode($this->messages->unfillForm());
		}else{
			echo json_encode($this->model_global->sendEmail($em));
		}
	}
	public function get_captcha()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$this->load->helper('captcha');
		$cek=$this->model_global->getCaptchaExpired();
		if (isset($cek)) {
			foreach ($cek as $d) {
				if (file_exists(FCPATH.$d->path)) {
					unlink(FCPATH.$d->path);
				}	
				$this->model_global->deleteQueryNoMsg('data_captcha',['id_captcha'=>$d->id_captcha]);
			}
		}
		$captcha_path=FCPATH.'asset/img/captcha/';
		$vals = [
			'img_path'      => $captcha_path,
			'img_url'       => base_url('auth/get_captcha/'),
			'font_path'     => FCPATH.'system/fonts/captcha.ttf',
			'img_width'     => '200',
			'img_height'    => 40,
			'expiration'    => 1800,
			'word_length'   => 4,
			'font_size'     => 18,
			'img_id'        => 'Imageid',
			'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'colors'        => [
				'background' => [255, 255, 255],
				'border' => [255, 255, 255],
				'text' => [0, 0, 0],
				'grid' => [46, 149, 247]
			]
		];
		$cap = create_captcha($vals);
		$data = [
		        'captcha_time'  => $cap['time'],
		        'ip_address'    => $this->input->ip_address(),
		        'kode'          => $cap['word'],
		        'path'			=> 'asset/img/captcha/'.$cap['filename'],
		];
		$this->model_global->insertQueryNoMsg($data,'data_captcha');
		$this->session->set_userdata('captcha_word',$cap['word']);
		echo json_encode(['captcha_img'=>$data['path']]);
	}
// ============================================================= VALIDASI IZIN =====================================================
	function validasi_izin(){
		$token=$this->codegenerator->decryptChar($this->uri->segment(3));
		if ($token['token'] == "" && $token['date_now'] == "") {
			$this->messages->sessNotValidParam();
			redirect('auth');
		}else{
			$data=$this->model_karyawan->getIzinCutiTokenDate($token['token'],$token['date_now']);
			if (empty($data)) {
				$this->messages->sessNotValidParam();
				redirect('auth');
			}else{
				$emp=$this->model_karyawan->getEmpID($data['id_karyawan']);
                $tanggal=$this->formatter->getDateMonthFormatUser($data['tgl_mulai']).' '.$this->formatter->timeFormatUser($data['jam_mulai']).' - <br>'.$this->formatter->getDateMonthFormatUser($data['tgl_selesai']).' '.$this->formatter->timeFormatUser($data['jam_selesai']);
                $tanggal_db=$data['tgl_mulai'].' '.$data['jam_mulai'].' - '.$data['tgl_selesai'].' '.$data['jam_selesai'];
				$alamat_emp=(!empty($emp['alamat_asal_jalan'])?$emp['alamat_asal_jalan'].', ':null).
						(!empty($emp['alamat_asal_desa'])?$emp['alamat_asal_desa'].', ':null).
						(!empty($emp['alamat_asal_kecamatan'])?$emp['alamat_asal_kecamatan'].', ':null).
						(!empty($emp['alamat_asal_kabupaten'])?$emp['alamat_asal_kabupaten'].', ':null).
						(!empty($emp['alamat_asal_provinsi'])?$emp['alamat_asal_provinsi'].', ':null).
						(!empty($emp['alamat_asal_pos'])?$emp['alamat_asal_pos'].', ':null);
				$datain=['token'=>$token['token'],
					'id'=>$data['id_izin_cuti'],
					'idk'=>$emp['id_karyawan'],
					'nik'=>$emp['nik'],
					'nama'=>$emp['nama'],
					'alamat'=>$alamat_emp,
					'jabatan'=>$emp['nama_jabatan'],
					'bagian'=>$emp['nama_bagian'],
					'loker'=>$emp['nama_loker'],
					'kode_ijin'=>$data['kode_izin_cuti'],
					'jenis'=>$this->otherfunctions->getIzinCuti($this->model_master->getMasterIzinJenis($data['jenis'])['jenis']),
					'jenis_izin_cuti'=>$this->model_master->getMasterIzinJenis($data['jenis'])['nama'].' ('.$this->otherfunctions->getIzinCuti($this->model_master->getMasterIzinJenis($data['jenis'])['jenis']).')',
					'tanggal'=>$tanggal,
					'lama_izin'=>$this->otherfunctions->hitungHari($data['tgl_mulai'],$data['tgl_selesai']).' Hari',
					'tanggal_db'=>$tanggal_db,
					'alasan'=>$data['alasan'],
					'keterangan'=>$data['keterangan'],
					'create_date'=>$token['date_now'],
					'validasi'=>$data['validasi'],
				];
				$this->load->view('admin_tem/header');
				$this->load->view('utama/validasi_izin',$datain);
				$this->load->view('admin_tem/footer');
			}
		}
	}
	public function change_validasi_izin()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id_izin_cuti');
		$id_kar=$this->input->post('id_k');
		$val_db=$this->input->post('validasi_db');
		$jenis_db=$this->input->post('jenis_db');
		$vali=$this->input->post('validasi');
		$token=$this->input->post('token');
		$create_date=$this->input->post('create_date');
		$sisa_cuti_db=$this->model_karyawan->getEmpID($id_kar)['sisa_cuti'];
		$tgl_mulai=$this->model_karyawan->getIzinCutiID($id)['tgl_mulai'];
		$tgl_selesai=$this->model_karyawan->getIzinCutiID($id)['tgl_selesai'];
		$jum_cuti=$this->otherfunctions->hitungHari($tgl_mulai,$tgl_selesai);
		$d_izin_cuti=$this->model_karyawan->getIzinCutiID($id);
		$potongCuti=$this->model_master->getMasterIzinJenis($d_izin_cuti['jenis'])['potong_cuti'];
		$emp=$this->model_karyawan->getEmpID($id_kar);
		$tanggal=$this->formatter->getDateMonthFormatUser($d_izin_cuti['tgl_mulai']).' '.$this->formatter->timeFormatUser($d_izin_cuti['jam_mulai']).' -
				<br>'.$this->formatter->getDateMonthFormatUser($d_izin_cuti['tgl_selesai']).' '.$this->formatter->timeFormatUser($d_izin_cuti['jam_selesai']);
		$alamat_emp=(!empty($emp['alamat_asal_jalan'])?$emp['alamat_asal_jalan'].', ':null).
				(!empty($emp['alamat_asal_desa'])?$emp['alamat_asal_desa'].', ':null).
				(!empty($emp['alamat_asal_kecamatan'])?$emp['alamat_asal_kecamatan'].', ':null).
				(!empty($emp['alamat_asal_kabupaten'])?$emp['alamat_asal_kabupaten'].', ':null).
				(!empty($emp['alamat_asal_provinsi'])?$emp['alamat_asal_provinsi'].', ':null).
				(!empty($emp['alamat_asal_pos'])?$emp['alamat_asal_pos'].', ':null);
		$data_emp = ['kepada'=>$emp['nama'], 
					'nama'=>$emp['nama'], 
					'nik'=>$emp['nik'], 
					'alamat'=>$alamat_emp, 
					'jabatan'=>$emp['nama_jabatan'], 
					'loker'=>$emp['nama_loker'], 
					'tanggal'=>$tanggal, 
					'jenis'=>$this->model_master->getMasterIzinJenis($d_izin_cuti['jenis'])['nama'], 
					'alasan'=>$d_izin_cuti['alasan'], 
					'keterangan'=>$d_izin_cuti['keterangan'], 
					'tgl'=>$this->date, 
					'kode'=>$d_izin_cuti['kode_izin_cuti'],
					'validasi'=>(($vali==1)?'DIIZINKAN':'TIDAK DIIZINKAN'),
					'url'=>$this->otherfunctions->companyClientProfile()['url'],
					'logo'=>$this->otherfunctions->companyClientProfile()['logo_url'],
				];
		$email_emp=$emp['email'];
		if($create_date == '' && $token == ''){
			$datax=$this->messages->customFailure('Invalid Parameter');
		}else{
			if($val_db==2 && $vali==1 || $val_db==0 && $vali==1){
				if($jenis_db == 'C' && $potongCuti==1){
					$sisa_cuti=$sisa_cuti_db-$jum_cuti;
					$data_kar=['sisa_cuti'=>$sisa_cuti];
					$where_kar=['id_karyawan'=>$id_kar];
					$this->model_global->updateQueryNoMsg($data_kar,'karyawan',$where_kar);
				}
				$data_presensi=[];
				while ($d_izin_cuti['tgl_mulai'] <= $d_izin_cuti['tgl_selesai'])
				{
					$data_presensi[]=[
						'id_karyawan'   =>$id_kar,
						'tanggal'    =>$d_izin_cuti['tgl_mulai'],
					];
					$d_izin_cuti['tgl_mulai'] = date('Y-m-d', strtotime($d_izin_cuti['tgl_mulai'] . ' +1 day'));
				}
				foreach ($data_presensi as $d => $value) {
					$value['kode_ijin']=$d_izin_cuti['kode_izin_cuti'];
					$cek=$this->model_karyawan->checkPresensiDate($id_kar,$value['tanggal']);
					if($cek){
						$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
						$this->model_global->updateQueryNoMsg($value,'data_presensi',['id_karyawan'=>$id_kar,'tanggal'=>$value['tanggal']]);
					}else{
						$value=array_merge($value,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQueryNoMsg($value,'data_presensi');
					}
				}
				$data=['validasi'=>$vali];
				$where=['id_izin_cuti'=>$id,'validasi'=>$val_db,'token'=>$token,'create_date'=>$create_date];
				$dbs=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
			}elseif($val_db==1 && $vali==0){
				if($jenis_db == 'C' && $potongCuti==1){
					$sisa_cuti=$sisa_cuti_db+$jum_cuti;
					$data_kar=['sisa_cuti'=>$sisa_cuti];
					$where_kar=['id_karyawan'=>$id_kar];
					$this->model_global->updateQueryNoMsg($data_kar,'karyawan',$where_kar);
				}
				$data_presensi=[];
				while ($d_izin_cuti['tgl_mulai'] <= $d_izin_cuti['tgl_selesai'])
				{
					$data_presensi[]=[
						'id_karyawan'   =>$id_kar,
						'tanggal'    =>$d_izin_cuti['tgl_mulai'],
					];
					$d_izin_cuti['tgl_mulai'] = date('Y-m-d', strtotime($d_izin_cuti['tgl_mulai'] . ' +1 day'));
				}
				foreach ($data_presensi as $d => $value) {
					$value['kode_ijin']=null;
					$cek=$this->model_karyawan->checkPresensiDate($id_kar,$value['tanggal']);
					if($cek){
						$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
						$this->model_global->updateQueryNoMsg($value,'data_presensi',['id_karyawan'=>$id_kar,'tanggal'=>$value['tanggal']]);
					}else{
						$value=array_merge($value,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQueryNoMsg($value,'data_presensi');
					}
				}
				$data=['validasi'=>$vali];
				$where=['id_izin_cuti'=>$id,'validasi'=>$val_db,'token'=>$token,'create_date'=>$create_date];
				$dbs=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
			}else{
				$data=['validasi'=>$vali];
				$where=['id_izin_cuti'=>$id,'validasi'=>$val_db,'token'=>$token,'create_date'=>$create_date];
				$dbs=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
			}
			if(isset($emp)){
				$ci = get_instance();
				$ci->email->initialize($this->otherfunctions->configEmail());
				$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengajuan Izin/Cuti Online');
				$list = array($email_emp);
				$ci->email->to($email_emp);
				$ci->email->subject('Validasi Izin/Cuti Online');
				$body = $this->load->view('email_template/email_izin_validasi',$data_emp,TRUE);
				$ci->email->message($body);
				$eml=$this->email->send();
			}
			if ($eml && $dbs){
				$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
			}elseif($dbs){
				$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
			}elseif($eml){
				$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
			}else{
				$datax=$this->messages->allFailure();
			}
		}
		echo json_encode($datax);
	}
// ============================================================= VALIDASI LEMBUR =====================================================
	function validasi_lembur(){
		$token=$this->codegenerator->decryptChar($this->uri->segment(3));
		if ($token['token'] == "" && $token['date_now'] == "") {
			$this->messages->sessNotValidParam();
			redirect('auth');
		}else{
			$data=$this->model_karyawan->getLemburTokenDate($token['token'],$token['date_now']);
			if (empty($data)) {
				$this->messages->sessNotValidParam();
				redirect('auth');
			}else{
                $tanggal=$this->formatter->getDateMonthFormatUser($data['tgl_mulai']).' '.$this->formatter->timeFormatUser($data['jam_mulai']).' - <br>'.$this->formatter->getDateMonthFormatUser($data['tgl_selesai']).' '.$this->formatter->timeFormatUser($data['jam_selesai']);
				$tanggal_db=$data['tgl_mulai'].' '.$data['jam_mulai'].' - '.$data['tgl_selesai'].' '.$data['jam_selesai'];
				$dataLembur=$this->model_karyawan->getLemburTrans($data['no_lembur']);
				$id_karyawan=[];
				foreach ($dataLembur as $dl) {
					$id_karyawan[]=$dl->id_karyawan;
				}
				$datain=['token'		  =>$token['token'],
						'date_now'		  =>$token['date_now'],
						'no_lembur'       =>$data['no_lembur'],
						'tanggal'         =>$tanggal,
						'jumlah_lembur'   =>$this->otherfunctions->getStringInterval($data['jumlah_lembur']),
						'dibuat_oleh'     =>$this->model_karyawan->getEmpID($data['dibuat_oleh'])['nama'].' - '.$this->model_karyawan->getEmpID($data['dibuat_oleh'])['nama_jabatan'],
						'diperiksa_oleh'  =>$this->model_karyawan->getEmpID($data['diperiksa_oleh'])['nama'].' - '.$this->model_karyawan->getEmpID($data['diperiksa_oleh'])['nama_jabatan'],
						'diketahui_oleh'  =>$this->model_karyawan->getEmpID($data['diketahui_oleh'])['nama'].' - '.$this->model_karyawan->getEmpID($data['diketahui_oleh'])['nama_jabatan'],
						'tgl_buat'        =>$this->formatter->getDateMonthFormatUser($data['tgl_buat']),
						'potong_jam'      =>($data['potong_jam']==1)?'Potong Jam Istirahat':'Tidak Potong Jam Istirahat',
						'keterangan'      =>$data['keterangan'],
						'jumlah_karyawan' =>count($id_karyawan),
						'karyawan'        =>$this->otherfunctions->getKaryawanViewEmail($id_karyawan),
						'validasi'		  =>$data['validasi'],
				];
				$this->load->view('admin_tem/header');
				$this->load->view('utama/validasi_lembur',$datain);
				$this->load->view('admin_tem/footer');
			}
		}
	}
	public function change_validasi_lembur()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$no_lembur   =$this->input->post('no_lembur');
		$val_db      =$this->input->post('validasi_db');
		$vali        =$this->input->post('validasi');
		$token       =$this->input->post('token');  
		$date_now    =$this->input->post('date_now');
		$dataLembur  =$this->model_karyawan->getLemburTrans($no_lembur);
		$dLembur  =$this->model_karyawan->getLemburNoLembur($no_lembur);
		$id_karyawan=[];
		$emailCreateBy=[];
		foreach ($dataLembur as $dl) {
			$id_karyawan[]=$dl->id_karyawan;
			$emailCreateBy[]=$this->model_admin->getAdminRowArray($dl->create_by)['email'];
		}
		if($date_now == '' && $token == ''){
			$datax=$this->messages->customFailure('Invalid Parameter');
		}else{
			if($val_db==2 && $vali==1 || $val_db==0 && $vali==1){
				$data=[	'validasi'		   =>$vali,
						'val_tgl_mulai'    =>$dLembur['tgl_mulai'].' '.$dLembur['jam_mulai'],'val_tgl_selesai'=>$dLembur['tgl_selesai'].' '.$dLembur['jam_selesai'],'val_jumlah_lembur'=>$dLembur['jumlah_lembur'],'val_potong_jam'=>$dLembur['potong_jam']
					];
			}elseif($val_db==1 && $vali==0){
				$data=[	'validasi'=>$vali,'val_tgl_mulai'    =>null,'val_tgl_selesai'  =>null,'val_jumlah_lembur'=>null,'val_potong_jam'=>null];
			}else{
				$data=[	'validasi'=>$vali,'val_tgl_mulai'=>$dLembur['tgl_mulai'].' '.$dLembur['jam_mulai'],'val_tgl_selesai'=>$dLembur['tgl_selesai'].' '.$dLembur['jam_selesai'],'val_jumlah_lembur'=>$dLembur['jumlah_lembur'],'val_potong_jam'=>$dLembur['potong_jam'],
					];
			}
			$where=['no_lembur'=>$no_lembur,'validasi'=>$val_db,'token'=>$token,'date_now'=>$date_now];
			$dbs=$this->model_global->updateQuery($data,'data_lembur',$where);
			foreach ($dataLembur as $d) {
				$dataPresensi=[];
				while ($d->tgl_mulai <= $d->tgl_selesai)
				{
					$dataPresensi[]=[
						'id_karyawan'   =>$d->id_karyawan,
						'tanggal'    =>$d->tgl_mulai,
					];
					$d->tgl_mulai = date('Y-m-d', strtotime($d->tgl_mulai . ' +1 day'));
				}
				foreach ($dataPresensi as $dp => $value) {
					if($vali==1){
						$value['no_spl']=$d->no_lembur;
					}else{
						$value['no_spl']=null;
					}
					$cek=$this->model_karyawan->checkPresensiDate($d->id_karyawan,$value['tanggal']);
					if($cek){
						$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
						$this->model_global->updateQueryNoMsg($value,'data_presensi',['id_karyawan'=>$d->id_karyawan,'tanggal'=>$value['tanggal']]);
					}else{
						$value=array_merge($value,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQueryNoMsg($value,'data_presensi');
					}
				}
				$emp=$this->model_karyawan->getEmpID($d->id_karyawan);
				$tanggal	 =$this->formatter->getDateMonthFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai).' -
						<br>'.$this->formatter->getDateMonthFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai);
				$data_emp = ['kepada'=>$emp['nama'], 
							'nama'           =>$emp['nama'],
							'nik'            =>$emp['nik'],
							'jabatan'        =>$emp['nama_jabatan'],
							'loker'          =>$emp['nama_loker'],
							'tanggal'        =>$tanggal,
							'no_lembur'      =>$d->no_lembur,
							'lama_lembur'    =>$this->otherfunctions->getStringInterval($d->jumlah_lembur),
							'potong_jam'     =>($d->potong_jam==1)?'Potong Jam Istirahat':'Tidak Potong Jam Istirahat',
							'keterangan'     =>$d->keterangan,
							'jumlah_karyawan'=>count($id_karyawan),
							'karyawan'       =>$this->otherfunctions->getKaryawanViewEmail($id_karyawan),
							'tgl'=>$this->date,
							'url'=>$this->otherfunctions->companyClientProfile()['url'],
							'logo'=>$this->otherfunctions->companyClientProfile()['logo_url'],
						];
				$email_emp=$emp['email'];
				if(!empty($emp['email']) && $vali==1){
					$ci = get_instance();
					$ci->email->initialize($this->otherfunctions->configEmail());
					$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Tugas Lembur Karyawan Online');
					$list = array($email_emp);
					$ci->email->to($email_emp);
					$ci->email->subject('Tugas Lembur Karyawan Online');
					$body = $this->load->view('email_template/email_perintah_lembur',$data_emp,TRUE);
					$ci->email->message($body);
					$eml=$this->email->send();
				}else{
					$eml=null;
				}
			}
		}
		if ($eml && $dbs){
			$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
		}elseif($dbs){
			$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
		}elseif($eml){
			$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
		}else{
			$datax=$this->messages->allFailure();
		}
		echo json_encode($datax);
	}
// ======================================================== VALIDASI PERJALANAN DINAS =====================================================
	function validasi_perdin(){
		$token=$this->codegenerator->decryptChar($this->uri->segment(3));
		if ($token['token'] == "" && $token['date_now'] == "") {
			$this->messages->sessNotValidParam();
			redirect('auth');
		}else{
			$data=$this->model_karyawan->getPerdinTokenDate($token['token'],$token['date_now']);
			if (empty($data)) {
				$this->messages->sessNotValidParam();
				redirect('auth');
			}else{
                $tanggal=$this->formatter->getDateTimeMonthFormatUser($data['tgl_berangkat']).' - <br>'.$this->formatter->getDateTimeMonthFormatUser($data['tgl_sampai']);
				$dataPerdin=$this->model_karyawan->getPerjalananDinasKodeSK($data['no_sk']);;
				$id_karyawan=[];
				foreach ($dataPerdin as $dl) {
					$id_karyawan[]=$dl->id_karyawan;
				}
				$datain=['token'		  		=>$token['token'],
						'date_now'              =>$token['date_now'],
						'no_perdin'             =>$data['no_sk'],
						'tanggal'               =>$tanggal,
						'tgl_berangkat'         =>$this->formatter->getDateTimeMonthFormatUser($data['tgl_berangkat']),
						'tgl_sampai'            =>$this->formatter->getDateTimeMonthFormatUser($data['tgl_sampai']),
						'plant'                 =>$this->otherfunctions->getTujuanPD($data['plant']),
						'plant_asal'            =>$this->model_master->getLokerKodeArray($data['plant_asal'])['nama'],
						'tujuan'                =>($data['plant']=='plant')?$this->model_master->getLokerKodeArray($data['plant_tujuan'])['nama']:$data['lokasi_tujuan'],
						'jarak'                 =>$data['jarak'],
						'kendaraan'             =>$this->model_master->getKendaraanKode($data['kendaraan'])['nama'],
						'jum_kendaraan'         =>$data['jumlah_kendaraan'],
						'nominal_per_kendaraan' =>$this->formatter->getFormatMoneyUser($data['nominal_bbm']/$data['jumlah_kendaraan']),
						'nominal_bbm'           =>$this->formatter->getFormatMoneyUser($data['nominal_bbm']),
						'nominal_penginapan'    =>$this->formatter->getFormatMoneyUser($data['nominal_penginapan']),
						'penginapan'            =>$this->otherfunctions->getPenginapan($data['nama_penginapan']),
						'tugas'                 =>$data['tugas'],
						'keterangan'            =>$data['keterangan'],
						'dibuat'                =>$this->model_admin->getAdminRowArray($data['dibuat'])['nama'],
						'mengetahui'            =>$this->model_karyawan->getEmpID($data['mengetahui'])['nama'].' - '.$this->model_karyawan->getEmpID($data['mengetahui'])['nama_jabatan'],
						'menyetujui'            =>$this->model_karyawan->getEmpID($data['menyetujui'])['nama'].' - '.$this->model_karyawan->getEmpID($data['menyetujui'])['nama_jabatan'],
						'tgl'                   =>$this->date,
						'jumlah_karyawan'       =>count($id_karyawan),
						'karyawan'              =>$this->otherfunctions->getKaryawanViewEmail($id_karyawan),
						'validasi'              =>$data['validasi_ac'],
				];
				$this->load->view('admin_tem/header');
				$this->load->view('utama/validasi_perdin',$datain);
				$this->load->view('admin_tem/footer');
			}
		}
	}
	public function change_validasi_perdin()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$no_perdin   =$this->input->post('no_perdin');
		$val_db      =$this->input->post('validasi_db');
		$vali        =$this->input->post('validasi');
		$token       =$this->input->post('token');  
		$date_now    =$this->input->post('date_now');
		$dataPerDin  =$this->model_karyawan->getPerjalananDinasKodeSK($no_perdin);
		$dPerdin     =$this->model_karyawan->getPerdinNoSK($no_perdin);
		$id_karyawan=[];
		$emailCreateBy=[];
		foreach ($dataPerDin as $dl) {
			$id_karyawan[]=$dl->id_karyawan;
			$emailCreateBy[]=$this->model_admin->getAdminRowArray($dl->create_by)['email'];
		}
		if($date_now == '' && $token == ''){
			$datax=$this->messages->customFailure('Invalid Parameter');
		}else{
			if($val_db==2 && $vali==1 || $val_db==0 && $vali==1){
				$data=[	'validasi_ac'		    =>$vali,
						'val_kendaraan'         =>$dPerdin['kendaraan'],
						'val_kendaraan_umum'    =>$dPerdin['nama_kendaraan'],
						'val_jum_kendaraan'     =>$dPerdin['jumlah_kendaraan'],
						'val_nominal_bbm'       =>$dPerdin['nominal_bbm'],
						'val_menginap'          =>$dPerdin['menginap'],
						'val_penginapan'        =>$dPerdin['nama_penginapan'],
						'val_nominal_penginapan'=>$dPerdin['nominal_penginapan'],
						'status_pd'=>2,
					];
			}elseif($val_db==1 && $vali==0){
				$data=[	'validasi_ac'=>$vali,'val_kendaraan'=>null,'val_kendaraan_umum'=>null,'val_jum_kendaraan'=>null,'val_nominal_bbm'=>null,'val_menginap'=>null,'val_penginapan'=>null,'val_nominal_penginapan'=>null,
				'status_pd'=>3,
				];
			}else{
				$data=[	'validasi_ac'=>$vali,'val_kendaraan'=>null,'val_kendaraan_umum'=>null,'val_jum_kendaraan'=>null,'val_nominal_bbm'=>null,'val_menginap'=>null,'val_penginapan'=>null,'val_nominal_penginapan'=>null,
				'status_pd'=>3,
				];
			}
			$where=['no_sk'=>$no_perdin,'validasi_ac'=>$val_db,'token'=>$token,'date_now'=>$date_now];
			$dbs=$this->model_global->updateQuery($data,'data_perjalanan_dinas',$where);
			$id_karyawan=[];
			foreach ($dataPerDin as $d) {
				$tglMulai=$this->otherfunctions->getDataExplode($d->tgl_berangkat,' ','start');
				$tglSelesai=$this->otherfunctions->getDataExplode($d->tgl_sampai,' ','start');
				$dataPresensi=[];
				while ($tglMulai <= $tglSelesai)
				{
					$dataPresensi[]=[
						'id_karyawan'   =>$d->id_karyawan,
						'tanggal'    =>$tglMulai,
					];
					$tglMulai = date('Y-m-d', strtotime($tglMulai . ' +1 day'));
				}
				foreach ($dataPresensi as $dp => $value) {
					if($vali==1){
						$value['no_perjalanan_dinas']=$d->no_sk;
					}else{
						$value['no_perjalanan_dinas']=null;
					}
					$cek=$this->model_karyawan->checkPresensiDate($d->id_karyawan,$value['tanggal']);
					if($cek){
						$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
						$this->model_global->updateQueryNoMsg($value,'data_presensi',['id_karyawan'=>$d->id_karyawan,'tanggal'=>$value['tanggal']]);
					}else{
						$value=array_merge($value,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQueryNoMsg($value,'data_presensi');
					}
				}
				$id_karyawan[]=$d->id_karyawan;
				$emp=$this->model_karyawan->getEmpID($d->id_karyawan);
				// $tanggal	 =$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat).' - <br>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai);
				$data_emp = ['kepada'				=>$emp['nama'], 
							'nama'                  =>$emp['nama'],
							'nik'                   =>$emp['nik'],
							'jabatan'               =>$emp['nama_jabatan'],
							'loker'                 =>$emp['nama_loker'],
							'no_perjalanan'         =>$d->no_sk,
							'tgl_berangkat'         =>$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat),
							'tgl_sampai'            =>$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai),
							'plant'                 =>$this->otherfunctions->getTujuanPD($d->plant),
							'plant_asal'            =>$this->model_master->getLokerKodeArray($d->plant_asal)['nama'],
							'tujuan'                =>($d->plant=='plant')?$this->model_master->getLokerKodeArray($d->plant_tujuan)['nama']:$d->lokasi_tujuan,
							'jarak'                 =>$d->jarak,
							'kendaraan'             =>$this->model_master->getKendaraanKode($d->kendaraan)['nama'],
							'jum_kendaraan'         =>$d->jumlah_kendaraan,
							'nominal_per_kendaraan' =>($d->nominal_bbm/$d->jumlah_kendaraan),
							'nominal_bbm'           =>$this->formatter->getFormatMoneyUser($d->nominal_bbm),
							'nominal_penginapan'    =>$this->formatter->getFormatMoneyUser($d->nominal_penginapan),
							'penginapan'            =>$d->nama_penginapan,
							'tugas'                 =>$d->tugas,
							'keterangan'            =>$d->keterangan,
							'jumlah_karyawan'       =>count($id_karyawan),
							'karyawan'              =>$this->otherfunctions->getKaryawanViewEmail($id_karyawan),
							'dibuat'                =>$this->model_admin->getAdminRowArray($d->dibuat)['nama'],
							'mengetahui'            =>$this->model_karyawan->getEmpID($d->mengetahui)['nama'].' - '.$this->model_karyawan->getEmpID($d->mengetahui)['nama_jabatan'],
							'menyetujui'            =>$this->model_karyawan->getEmpID($d->menyetujui)['nama'].' - '.$this->model_karyawan->getEmpID($d->menyetujui)['nama_jabatan'],
							'tgl'                   =>$this->date,
							'url'                   =>$this->otherfunctions->companyClientProfile()['url'],
							'logo'                  =>$this->otherfunctions->companyClientProfile()['logo_url'],
						];
				$email_emp=$emp['email'];
				if(!empty($emp['email']) && $vali==1){
					$ci = get_instance();
					$ci->email->initialize($this->otherfunctions->configEmail());
					$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Tugas Perjalanan Dinas Karyawan Online');
					$list = array($email_emp);
					$ci->email->to($email_emp);
					$ci->email->subject('Tugas Perjalanan Dinas Karyawan Online');
					$body = $this->load->view('email_template/email_perintah_perjalanan_dinas',$data_emp,TRUE);
					$ci->email->message($body);
					$eml=$this->email->send();
				}else{
					$eml=false;
				}
			}
			if ($eml && $dbs){
				$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
			}elseif($dbs){
				$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
			}elseif($eml){
				$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
			}else{
				$datax=$this->messages->allFailure();
			}
			echo json_encode($datax);
		}
	}
}
