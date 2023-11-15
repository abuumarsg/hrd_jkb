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
  
class Admin extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();

		if ($this->session->has_userdata('adm')) {
			$this->admin = $this->session->userdata('adm')['id'];	 
		}else{ 
			redirect('auth');
		}
		$this->rando = $this->codegenerator->getPin(6,'number');		
		$dtroot['admin']=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
		$datax['adm'] = array(
			'nama'=>$dtroot['admin']['nama'],
			'email'=>$dtroot['admin']['email'],
			'kelamin'=>$dtroot['admin']['kelamin'],
			'foto'=>$dtroot['admin']['foto'],
			'create'=>$dtroot['admin']['create_date'],
			'update'=>$dtroot['admin']['update_date'],
			'login'=>$dtroot['admin']['last_login'],
			'level'=>$dtroot['admin']['level'],
		);
		$this->dtroot=$datax;
	}
	function index(){
		redirect('pages/dashboard');
	}
//==admin==//
	public function list_admin()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_admin->getListAdmin();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_admin,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status_adm,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_admin,
						$d->nama,
						$d->email,
						$d->nama_group,
						($d->last_login == '0000-00-00 00:00:00')?'<label class="label label-danger">Belum Pernah Login</label>':$this->formatter->getDateTimeMonthFormatUser($d->last_login).' WIB',
						$this->otherfunctions->getLevelAdmin($d->level),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
						($d->status == 1) ? '<i title="online" class="fas fa-circle text-green"></i>':'<i title="offline" class="far fa-circle" style="color:red;border-color:red"></i>',
						$d->username,
						$d->level
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_admin');
				$data=$this->model_admin->getAdmin($id);	
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_admin,
						'nama'=>$d->nama,
						'username'=>$d->username,
						'email'=>$d->email,
						'alamat'=>$d->alamat,
						'v_level'=>$this->otherfunctions->getLevelAdmin($d->level),
						'level'=>$d->level,
						'foto'=>base_url($d->foto),
						'hp'=>$d->hp,
						'kelamin'=>$this->otherfunctions->getGender($d->kelamin),
						'kelamin_val'=>$d->kelamin,
						'user_group'=>$d->nama_group,
						'user_group_val'=>$d->id_group,
						'status'=>$d->status_adm,
						'last_login'=>$this->formatter->getDateTimeMonthFormatUser($d->last_login),
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update)
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'level') {
				$data=$this->model_admin->getAdmin($this->admin);	
				foreach ($data as $d) {
					$datax=['level'=>$d->level,];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_admin(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$emp=$this->input->post('employee');
		if (!isset($emp)) {
			$datax=$this->messages->notValidParam(); 
		}else{
			$ug=$this->input->post('u_group');
			$lv=$this->input->post('level');
			foreach ($emp as $e) {
				$kar=$this->model_karyawan->getEmployeeId($e);
				if (isset($kar)) {
					$data=array(
						'nama'=>$kar['nama'],
						'alamat'=>$kar['alamat_sekarang_jalan'].','.$kar['alamat_sekarang_desa'].','.$kar['alamat_sekarang_kecamatan'].','.$kar['alamat_sekarang_kabupaten'].','.$kar['alamat_sekarang_provinsi'].','.$kar['alamat_sekarang_pos'],
						'email'=>$kar['email'],
						'username'=>$kar['nik'],
						'password'=>$kar['password'],
						'foto'=>$kar['foto'],
						'hp'=>$kar['no_hp'],
						'id_karyawan'=>$e,
						'email_verified'=>$kar['email_verified'],
						'id_group'=>$ug,
						'level'=>$lv,
						'status_adm'=>1,
					);
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax=$this->model_global->insertQuery($data,'admin');
				}
			}
			if (isset($datax)) {
				$datax=$datax;	
			}else{
				$datax=$this->messages->notValidParam();
			}
		}
		echo json_encode($datax);
	}
	function edt_admin(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id == "") {
			$datax=$this->messages->notValidParam();  
		}else{
			// $lv=$this->input->post('level');
			// if($lv>$this->dtroot['adm']['level']){
			// 	$datax=$this->messages->notValidParam();
			// }else{
			// 	if (isset($lv)) {
			// 		$lv1=$lv;
			// 	}else{
			// 		$lv1=2;
			// 	}
			$data=array(
				'nama'=>ucwords($this->input->post('nama')),
				'username'=>$this->input->post('username'),
				'email'=>$this->input->post('email'),
				'level'=>$this->input->post('level'),
				'alamat'=>$this->input->post('alamat'),
				'hp'=>$this->input->post('no_hp'),
				'id_group'=>$this->input->post('u_group'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'admin',['id_admin'=>$id]);
			// }
		}
		echo json_encode($datax);
	}
	function reset_password(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$paslama=$this->codegenerator->genPassword($this->input->post('old_password'));
		$pasbaru=$this->codegenerator->genPassword($this->input->post('password'));
		$upasbaru=$this->codegenerator->genPassword($this->input->post('u_password'));
		if ($id == "") {
			$datax=$this->messages->notValidParam();  
		}else{
			$cekpass = $this->model_admin->getAdmin($id);
			foreach ($cekpass as $keypass) {
				$kode = $keypass->password;
			}
			if($paslama==$kode){
				if($pasbaru==$upasbaru){
					$data=['password'=>$pasbaru];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'admin',['id_admin'=>$id]);
				}else{
					$datax=$this->messages->customFailure('Password Tidak Sama Dengan Ulangi Password');
				}
			}else{
				$datax=$this->messages->customFailure('Password Lama Salah');
			}
		}
		echo json_encode($datax);
	}
	public function changeSkin(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$skin=$this->input->post('skin');
		if ($id == "") {
			$datax=$this->messages->notValidParam();  
		}else{
			$data=['skin'=>$skin];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'admin',['id_admin'=>$id]);
		}
		echo json_encode($datax);
	}
	
	function res_foto(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->admin;
		$adm=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($id));
		if($adm['foto'] == 'asset/img/admin-photo/userm.png' || $adm['foto'] == 'asset/img/user-photo/user.png'){
			$foto='asset/img/admin-photo/userm.png';
		}elseif($adm['foto'] == 'asset/img/admin-photo/userf.png' || $adm['foto'] == 'asset/img/user-photo/userf.png'){
			$foto='asset/img/admin-photo/userf.png';
		}else{
			unlink($adm['foto']);
			if ($adm['kelamin'] == 'l') {
				$foto='asset/img/admin-photo/userm.png';
			}else{
				$foto='asset/img/admin-photo/userf.png';
			}
		}
		if ($id != "") {
			$data=['foto'=>$foto,];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'admin',['id_admin'=>$id],null);
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function profile()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if($usage == 'view_all'){
				$data=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
				if($data['kelamin'] == 'l'){
					$kel = '<i class="fa fa-male" style="color:blue"></i> '.$this->otherfunctions->getGender($data['kelamin']).'';
				}else{
					$kel = '<i class="fa fa-female" style="color:#ff00a5"></i> '.$this->otherfunctions->getGender($data['kelamin']).'';
				}
				if ($data['email_verified'] == 0) {
					$email = '<a data-toggle="tooltip" title="Email Belum Diverifikasi">'.$data['email'].'</a> <i style="color:red;" class="fa fa-warning"></i> <a class="btn btn-xs btn-warning" href="'.base_url('pages/verifikasi').'">Verifikasi</a>';
				}else{
					$email = $data['email'].' <i style="color:green;" data-toggle="tooltip" title="Terverifikasi" class="fa fa-check-circle"></i>';
				}
				$datax=['kodekelamin'=>$data['kelamin'],
						'foto'=>$data['foto'],
						'nama'=>$data['nama'],
						'email'=>$email,
						'alamat'=>$data['alamat'],
						'username'=>$data['username'],
						'jk'=>$kel,
					];
			}elseif($usage == 'view_log') {
				$data=$this->model_admin->getLogLogin($this->admin);
				$no=1;
				$datax['data']=[];
				if (isset($data)) {
					foreach ($data as $d) {
						$datax['data'][]=[
							$d->id_admin,
							'<i class="fa fa-calendar-o text-success"></i> '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_login),
						];
						$no++;
					}
				}				
			}elseif ($usage == 'view_one') {
				$jum_log = $this->db->get_where('log_login_admin',array('id_admin'=>$this->admin))->num_rows(); 
				$datax=['jumlah_log'=>$jum_log,];
			}else{
				$datax=$this->messages->notValidParam();
			}
				echo json_encode($datax);
		}
	}
	function del_log(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$id=$this->input->post('id');
		if(!empty($id)){
			$where=['id_admin'=>$id];
			$datax=$this->model_global->deleteQuery('log_login_admin',$where);
		}else{
			$datax = $this->messages->notValidParam();
		}
		
		echo json_encode($datax);
	}
	function up_foto(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$id=$this->admin;
		$other2=$this->model_global->getCreateProperties($this->admin);
		$data=[
			'post'=>'foto',
			'data_post'=>$this->input->post('foto', TRUE),
			'table'=>'admin',
			'column'=>'foto', 
			'where'=>['id_admin'=>$id],
			'usage'=>'update',
			'otherdata'=>$other2,
		];
		$datax=$this->filehandler->doUpload($data,'image');
		echo json_encode($datax);
	}
	function up_data(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		if(!empty($this->admin)){
			$cek_email = $this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
			$old_email = $cek_email['email'];
			$new_email = $this->input->post('email');
			$email = ($old_email==$new_email)?$old_email:$new_email;
			$ev = ($old_email==$new_email)?$cek_email['email_verified']:null;
			$data=[
				'nama'=>$this->input->post('nama'),
				'alamat'=>$this->input->post('alamat'),
				'hp'=>$this->input->post('nomor'),
				'email'=>$email,
				'email_verified'=>$ev,
				'username'=>$this->input->post('username'),
				'kelamin'=>$this->input->post('kelamin'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'admin',['id_admin'=>$this->admin]);
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function up_pass(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$paslama=$this->codegenerator->genPassword($this->input->post('old_pass'));
		$pasbaru=$this->codegenerator->genPassword($this->input->post('password1'));
		$upasbaru=$this->codegenerator->genPassword($this->input->post('password2'));
		if (empty($this->admin)) {
			$datax=$this->messages->notValidParam();  
		}else{
			$cekpass = $this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
			$kode = $cekpass['password'];
			if($paslama==$kode){
				if($pasbaru==$upasbaru){
					$data=['password'=>$pasbaru];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'admin',['id_admin'=>$this->admin]);
				}else{
					$datax=$this->messages->customFailure('Password Tidak Sama Dengan Ulangi Password');
				}
			}else{
				$datax=$this->messages->customFailure('Password Lama Salah');
			}
		}
		echo json_encode($datax);
	}

}