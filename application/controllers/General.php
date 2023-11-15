<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		$this->rando = $this->codegenerator->getPin(5,'full');
	} 
	function cobasendwa(){
		$no ='085725951044';
		$pesan = "Ive came across a similar problem and in the end it was a matter of some old dependencies that were messing up my Heroku server. While at my projects folder Ive run: npm uninstall npm install";
		// $data = $this->curl->sendWALocal($no, $pesan);
		$data = $this->curl->sendWA($no, $pesan);
		echo '<pre>';
        echo 'das';
		print_r($data);
		// $curl = curl_init();
		// curl_setopt_array($curl, array(
		// CURLOPT_URL => 'http://localhost:3000/api/chat/send_text_message',
		// CURLOPT_RETURNTRANSFER => true,
		// CURLOPT_ENCODING => '',
		// CURLOPT_MAXREDIRS => 10,
		// CURLOPT_TIMEOUT => 0,
		// CURLOPT_FOLLOWLOCATION => false,
		// CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		// CURLOPT_CUSTOMREQUEST => 'POST',
		// CURLOPT_POSTFIELDS =>'{
		// 	"api_key": "2098466d-0a9f-40c0-9071-e8ab5c358864",
		// 	"device_id": "3226643262",
		// 	"destination": "085725951044",
		// 	"text": "Ive came across a similar problem and in the end it was a matter of some old dependencies that were messing up my Heroku serve. While at my projects folder Ive run:npm uninstall npm install"
		// }',
		
		// CURLOPT_HTTPHEADER => array(
		// 	'Content-Type: application/json',
		// 	'Cookie: connect.sid=s%3AK4gl0fVm_BYxmwsr22YTBvg380eam3Jg.dGlvdVUXWT%2BkOUnzGCxguLSyH%2FeuI3qgEsrd6%2FEL%2ByU'
		// ),
		// ));

		// $response = curl_exec($curl);

		// curl_close($curl);
		// echo $response;
		// "text": "'.$pesan.'"
	}
	//=========================== RESET UNTUK LUPA PASSWORD ==================================================
	function cek_nomor(){
		$nomor=$this->input->post('nomor');
		$msg_adm = null;
		if(strlen($nomor) > 9){
			$kar = $this->model_karyawan->getEmployeeWhere(['emp.no_hp'=>$nomor, 'emp.status_emp'=>'1'], false);
			$admin = $this->model_admin->getAdminWhere(['a.hp'=>$nomor, 'a.status_adm'=>'1'], false);
			if($kar){
				$ret = 'Nomor Terdaftar';
				$msg = 'true';
			}else{
				$ret = 'Nomor Tidak Terdaftar';
				$msg = 'false';
			}
			if($admin){
				$msg_adm = 'true';
			}else{
				$msg_adm = 'false';
			}
		}else{
			$ret = 'Nomor Tidak Lengkap';
			$msg = 'false';
		}
		$data = [
			'ret'=>$ret,
			'msg'=>$msg,
			'msg_adm'=>$msg_adm,
		];
		echo json_encode($data);
	}
	function send_nomor_forget(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$nomor=$this->input->post('email');
		$param=$this->input->post('param');
		if($param == 'emp'){
			$emp = $this->model_karyawan->getEmployeeWhere(['emp.no_hp'=>$nomor, 'emp.status_emp'=>'1'], true);
			$id_emp_adm = $emp['id_karyawan'];
		}else{
			$emp = $this->model_admin->getAdminWhere2(['a.hp'=>$nomor, 'a.status_adm'=>'1'], true);
			$id_emp_adm = $emp['id_admin'];
		}
		if(!empty($emp)){
			$expired_date = date('Y-m-d H:i:s', strtotime($this->date . ' +1 Hour'));
			$dataLink = [
				'param' => $param,
				'nomor' => $nomor,
				'expired_date' => $expired_date,
			];
			$encrypt = $this->codegenerator->encryptChar($dataLink);
			$token      = $this->rando.uniqid();
			$data4db = [
				'id_emp_adm'=>$id_emp_adm,
				'nama'=>$emp['nama'],
				'token'=>$token,
				'encrypt'=>$encrypt,
				'for'=>'reset password',
				'status'=>1,
				'create_date'=>$this->date,
			];
			// $pesan = 'Untuk Mereset password silahkan klik tautan berikut : http://localhost/jkb/forget/'.$token;
			$pesan = 'Untuk Mereset password silahkan klik tautan berikut : https://hrd.jkb.co.id/i/forget/'.$token.', Link berlaku 60 Menit';
			$send = $this->curl->sendwaapi($nomor, $pesan);
			$dataDB = array_merge($dataLink, $data4db);
			$this->model_global->insertQuery($dataDB,'short_url');
			$datax = [
				'message'=>'Pesan Terkirim, Silahkan Ikuti Petunjuk melalui Whatsapp',
				'status'=>'true',
			];
			// echo '<pre>';
			// print_r($send);
			// if(!empty($send)){
			// 	$msg = json_decode($send);
			// 	if(isset($msg->status)){
			// 		if($msg->status == true){
			// 			$dataDB = array_merge($dataLink, $data4db);
			// 			$this->model_global->insertQuery($dataDB,'short_url');
			// 			$datax = [
			// 				'message'=>'Pesan Terkirim, Silahkan Ikuti Petunjuk melalui Whatsapp',
			// 				'status'=>'true',
			// 			];
			// 		}else{
			// 			$datax = [
			// 				'message'=>$msg->response,
			// 				'status'=>'false',
			// 			];
			// 		}
			// 	}else{
			// 		$datax = [
			// 			'message'=>$send,
			// 			'status'=>'false',
			// 		];
			// 	}
			// }else{
			// 	$datax = [
			// 		'message'=>'Whatsapp Tidak Terhubung',
			// 		'status'=>'false',
			// 	];
			// }
		}else{
			$datax = [
				'message'=>'INVALID',
				'status'=>'false',
			];
		}
		echo json_encode($datax);
	}
	function getDataLupaPassword(){
		$token = $this->uri->segment(3);
		$dataShort = $this->model_global->getDataShortUrl(['token'=>$token,'status'=>'1']);
		if(!empty($dataShort)){
			$now = strtotime($this->date);
			if(strtotime($this->date) > strtotime($dataShort['expired_date'])){
				$this->load->view('admin_tem/header');
				$this->load->view('utama/link_expired');
				$this->load->view('admin_tem/footer');
			}else{
				$data = [
					'dataShort'=>$dataShort,
				];
				$this->load->view('admin_tem/header');
				$this->load->view('utama/reset_password',$data);
				$this->load->view('admin_tem/footer');
			}
		}else{
			$this->load->view('admin_tem/header');
			$this->load->view('utama/link_expired');
			$this->load->view('admin_tem/footer');
		}
	}
	function submit_new_password(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$password=$this->input->post('password');
		$password2=$this->input->post('password2');
		$pasbaru=$this->codegenerator->genPassword($password);
		$upasbaru=$this->codegenerator->genPassword($password2);
		if(!empty($id) && !empty($password)){
			$dataShort = $this->model_global->getDataShortUrl(['id'=>$id]);
			if($dataShort['param'] == 'admin'){
				$table = 'admin';
				$where = ['id_admin'=>$dataShort['id_emp_adm']];
			}elseif($dataShort['param'] == 'emp'){
				$table = 'karyawan';
				$where = ['id_karyawan'=>$dataShort['id_emp_adm']];
			}
			if($pasbaru == $upasbaru){
				$data=['password'=>$pasbaru];
				$data=array_merge($data,$this->model_global->getUpdateProperties(1));
				$upd = $this->model_global->updateQuery($data, $table, $where);
				if($upd){
					$this->model_global->updateQuery(['status'=>'0'], 'short_url', ['id'=>$id]);
					$datax = [
						'status'=>'true',
						'message'=>'Password Berhasil di ubah',
					];
				}else{
					$datax = [
						'status'=>'false',
						'message'=>'Password Gagal di ubah',
					];
				}
			}else{
				$datax = [
					'status'=>'false',
					'message'=>'Password Beda',
				];
			}
		}
		echo json_encode($datax);

	}
}