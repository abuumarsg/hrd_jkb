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

class Kemp extends CI_Controller
{
	public function __construct() 
	{ 
		parent::__construct();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
		if (isset($_SESSION['emp'])) {
			$this->admin = $_SESSION['emp']['id'];	
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
		$dtroot['admin']=$this->model_karyawan->getEmployeeId($this->admin);//$this->model_karyawan->emp($this->admin);
		$nm=explode(" ", $dtroot['admin']['nama']);
		$datax = array(
				'nama'=>$nm[0],
				'nik'=>$dtroot['admin']['nik'],
				'email'=>$dtroot['admin']['email'],
				'kelamin'=>$dtroot['admin']['kelamin'],
				'jabatan'=>$dtroot['admin']['jabatan'],
				'foto'=>$dtroot['admin']['foto'],
				'create'=>$dtroot['admin']['create_date'],
				'update'=>$dtroot['admin']['update_date'],
				'login'=>$dtroot['admin']['last_login'],
			);
		$this->dtroot=$datax;
	}
	public function index(){
		redirect('kpages/dashboard');
	}
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
				'jumlah_anak'=>(!empty($jum_anak) || $jum_anak != 0) ? $jum_anak.' Anak': $this->otherfunctions->getCustomMark($jum_anak,'<label class="label label-danger">Belum Punya Anak</label>'),
				'maxJenjang'=> (!empty($mjjang)) ? $mjjang: $this->otherfunctions->getCustomMark($mjjang,'<label class="label label-danger">Jenjang Tidak Ada</label>'),
				'maxSekolah'=> (!empty($maxJenjanng['nama_sekolah'])) ? $maxJenjanng['nama_sekolah']: $this->otherfunctions->getCustomMark($maxJenjanng['nama_sekolah'],'<label class="label label-danger">Universitas Tidak Ada</label>'),
				'MaxJurusan'=> (!empty($maxJenjanng['jurusan'])) ? $maxJenjanng['jurusan'] : $this->otherfunctions->getCustomMark($maxJenjanng['jurusan'],'<label class="label label-danger">Jurusan Tidak Ada</label>'),
			];
		}
		echo json_encode($datax);
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
				// 'no_hp'=>$this->input->post('no_hp'),
				// 'npwp'=>$this->input->post('npwp'),
				// 'bpjstk'=>$this->input->post('bpjstk'),
				// 'bpjskes'=>$this->input->post('bpjskes'),
				// 'rekening'=>$this->input->post('rekening'),
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
				// 'status_pajak'=>$this->input->post('status_pajak'),
				'tempat_lahir'=>$this->input->post('tempat_lahir'),
				'tgl_lahir'=>$this->formatter->getDateFormatDb($this->input->post('tgl_lahir')),
				'tgl_masuk'=>$this->formatter->getDateFormatDb($this->input->post('tgl_masuk')),
			);
			$data=array_merge($dataa,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
	   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function up_data_nomor()
	{
		if (!$this->input->is_ajax_request()) 
   			redirect('not_found');
	   	$id_karyawan=$this->input->post('id_karyawan');
	   	$nomor=$this->input->post('nomor');
		if(!empty($id_karyawan)){
	   		$dataa = [
				'no_hp'=>$this->input->post('nomor'),
			];
			$data=array_merge($dataa,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id_karyawan]);
	   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
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
	public function emp_anak(){
		$nik=$this->uri->segment(4);
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListAnak($nik);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$ttl = $d->tempat_lahir_anak.', '.$this->formatter->getDateMonthFormatUser($d->tanggal_lahir_anak);
					$gender = $this->otherfunctions->getGender($d->kelamin_anak);
					$educa = $this->otherfunctions->getEducate($d->pendidikan_anak);
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_anak('.$d->id_anak.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
						//<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_anak('.$d->id_anak.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
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
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$ttl = $d->tempat_lahir_saudara.', '.$this->formatter->getDateMonthFormatUser($d->tanggal_lahir_saudara);
					$gender = $this->otherfunctions->getGender($d->jenis_kelamin_saudara);
					$educa = $this->otherfunctions->getEducate($d->pendidikan_saudara);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_saudara('.$d->id_saudara.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
						// <button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_saudara('.$d->id_saudara.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
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
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$tgl_masuk = $this->formatter->getDateMonthFormatUser($d->tahun_masuk);
					$tgl_keluar = $this->formatter->getDateMonthFormatUser($d->tahun_keluar);
					$educa = $this->otherfunctions->getEducate($d->jenjang_pendidikan);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_formal('.$d->id_k_pendidikan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
						// <button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_formal('.$d->id_k_pendidikan.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
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
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$tgl_masuk = $this->formatter->getDateMonthFormatUser($d->tanggal_masuk_pnf);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_nformal('.$d->id_k_pnf.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
						// <button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_nformal('.$d->id_k_pnf.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
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
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$tahun_masuk = $this->formatter->getDateMonthFormatUser($d->tahun_masuk);
					$tahun_keluar = $this->formatter->getDateMonthFormatUser($d->tahun_keluar);
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_org('.$d->id_k_organisasi.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
						// <button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_org('.$d->id_k_organisasi.')"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button>';
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
	function edit_nomor(){
		if (!$this->input->is_ajax_request()) 
   			redirect('not_found');
	   	$id=$this->input->post('id_karyawan');
		if($id!=''){
	   		$dataa=array(
				'no_hp'=>$this->input->post('no_hp'),
				'no_hp_ayah'=>$this->input->post('no_hp_ayah'),
				'no_hp_ibu'=>$this->input->post('no_hp_ibu'),
				'no_hp_pasangan'=>$this->input->post('no_hp_pasangan'),
				'npwp'=>$this->input->post('npwp'),
				'bpjstk'=>$this->input->post('bpjstk'),
				'bpjskes'=>$this->input->post('bpjskes'),
				'status_pajak'=>$this->input->post('status_pajak'),
				'rekening'=>$this->input->post('rekening'),
				'nama_bank'=>$this->input->post('bank'),
			);
			$data=array_merge($dataa,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
	   	}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
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
				if ($kelamin == 'l' || $kelamin == 'L') {
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

		$id=$this->admin;
		$paslama=$this->codegenerator->genPassword($this->input->post('old_pass'));
		$pasbaru=$this->codegenerator->genPassword($this->input->post('password1'));
		$upasbaru=$this->codegenerator->genPassword($this->input->post('password2'));
		if ($id == "") {
			$datax=$this->messages->notValidParam();  
		}else{
			$cekpass = $this->model_karyawan->getEmployeeId($id);
			$kode = $cekpass['password'];
			if($paslama==$kode){
				if($pasbaru==$upasbaru){
					$data=['password'=>$pasbaru];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
				}else{
					$datax=$this->messages->customFailure('Password Tidak Sama Dengan Ulangi Password');
				}
			}else{
       			$datax=$this->messages->customFailure('Password Lama Salah');
			}
		}
		echo json_encode($datax);
	}
	public function emp_log()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   $datax=$this->messages->notValidParam();
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getLogLogin($this->admin); 
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=['id'=>$d->id_karyawan,];
					$datax['data'][]=[
						$d->id_karyawan,
						'<i class="fa fa-calendar-o text-success"></i> '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_login),
					];
					$no++;
				}
			}elseif ($usage == 'view_one') {
				$jum_log = $this->db->get_where('log_login_karyawan',array('id_karyawan'=>$this->admin))->num_rows(); 
				$datax=['jumlah_log'=>$jum_log,];
			}else{
				$datax=$this->messages->notValidParam();
			}
		}
		echo json_encode($datax);
	}
	function del_log(){
		$this->db->where('id_karyawan',$this->admin);
		$in=$this->db->delete('log_login_karyawan');
		if ($in) {
			$this->session->set_flashdata('dlog_sc','<label><i class="fa fa-check-circle"></i> Hapus Riwayat Login Berhasil</label><hr class="message-inner-separator">Semua Riwayat Login Karyawan ini berhasil dihapus'); 
		}else{
			$this->session->set_flashdata('dlog_err','<label><i class="fa fa-times-circle"></i> Hapus Riwayat Login Gagal</label><hr class="message-inner-separator">Semua Riwayat Login Karyawan ini Gagal dihapus'); 
		}
		redirect('kpages/profile');
	}
//==================================================== LOG KARYAWAN ============================================
	public function log_mutasi_jabatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListMutasiNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$aksi = '
						<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_mutasi('.$d->id_mutasi.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_mutasi,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_status,
						$d->nama_jabatan_baru,
						$d->nama_loker_baru,
						$aksi,
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
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function log_perjanjian_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListPerjanjianKerjaNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_perjanjian('.$d->id_p_kerja.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_p_kerja,
						$d->nama_karyawan,
						$d->no_sk_baru,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk_baru),
						$d->nama_status_baru,
						(!empty($d->tgl_berlaku_baru)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku_baru):$this->otherfunctions->getMark($d->tgl_berlaku_baru),
						(!empty($d->berlaku_sampai_baru)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai_baru):$this->otherfunctions->getMark($d->berlaku_sampai_baru),
						$aksi,
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
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function log_peringatan_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListPeringatanNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_peringatan('.$d->id_peringatan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_peringatan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_status_baru,
						(!empty($d->berlaku_sampai)) ? $this->formatter->getDateMonthFormatUser($d->berlaku_sampai):$this->otherfunctions->getMark($d->berlaku_sampai),
						$aksi,
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
						'besaran_denda'=>$this->formatter->getFormatMoneyUser($d->besaran_denda),
						'besaran_denda_e'=>(!empty($d->besaran_denda)) ? $this->formatter->getFormatMoneyUser($d->besaran_denda):null,
						'jumlah_angsuran'=>$d->jumlah_angsuran,
						'no_sk_v'=>$d->no_sk,
						'kode_denda'=>$d->kode_denda,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function log_data_denda() {
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListDendaNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
          			$per=($d->kode_peringatan!=null)?'<a href="'.base_url('pages/view_peringatan/'.$this->codegenerator->encryptChar($d->nik_karyawan)).'">'.$d->kode_peringatan.'</a>':'Non Peringatan';
          			$per_ang=($d->sudah_diangsur!=0)? $d->sudah_diangsur.' Kali':'<label class="label label-danger">Belum Diangsur</label>';
          			$info='<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_denda('.$d->id_denda.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$d->id_denda,
						$d->nama_karyawan,
						$d->kode,
						$d->kode_peringatan,
						$this->formatter->getFormatMoneyUser($d->total_denda),
						$d->diangsur.' Kali',
						$per_ang,
						$this->formatter->getFormatMoneyUser($d->max_saldo),
						$info,
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
				$data=$this->model_karyawan->getListDendaNikNon($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
          			$per=($d->kode_peringatan!=null)?'<a href="'.base_url('pages/view_peringatan/'.$this->codegenerator->encryptChar($d->nik_karyawan)).'">'.$d->kode_peringatan.'</a>':'Non Peringatan';
          			$per_ang_non=($d->sudah_diangsur!=0)? $d->sudah_diangsur.' Kali':'<label class="label label-danger">Belum Diangsur</label>';
          			$info='<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_non('.$d->id_denda.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$d->id_denda,
						$d->nama_karyawan,
						$d->kode,
						$this->formatter->getFormatMoneyUser($d->total_denda),
						$d->diangsur.' Kali',
						$per_ang_non,
						$this->formatter->getFormatMoneyUser($d->max_saldo),
						$info,
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
	public function log_grade_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListGradeNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_grade('.$d->id_grade.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_grade,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl_sk),
						$d->nama_grade_baru.' ('.$d->nama_loker_grade.')',
						(!empty($d->tgl_berlaku)) ? $this->formatter->getDateMonthFormatUser($d->tgl_berlaku):$this->otherfunctions->getMark($d->tgl_berlaku),
						$aksi,
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
	public function log_kecelakaan_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListKecelakaanKerjaNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_kecelakaan('.$d->id_kecelakaan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_kecelakaan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateMonthFormatUser($d->tgl),
						$d->nama_kategori_kecelakaan,
						$d->nama_rs,
						$aksi,
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
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function log_izin_cuti()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListIzinCutiNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					if($d->skd_dibayar == 1){
						$skd = '<span class="text-success">SKD Dibayar</span>';
					}else{
						$skd = '<span class="text-danger">SKD Tidak Dibayar</span>';
					}
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis);
					if($d->jenis == 'I'){
						$izin_cuti='<label class="label label-success" style="font-size:16 px;">'.$nama_jenis.'</label>';
					}else{
						$izin_cuti='<label class="label label-warning" style="font-size:16 px;">'.$nama_jenis.'</label>';
					}
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_izin('.$d->id_izin_cuti.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_izin_cuti,
						$d->kode_izin_cuti,
						$izin_cuti,
						$d->nama_jenis_izin,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						$this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						$skd,
						(!empty($d->alasan))?$d->alasan:$this->otherfunctions->getMark(),
						$aksi,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_izin_cuti');
				$data=$this->model_karyawan->getIzinCuti($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis_ic);
					$mulai = $this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->timeFormatUser($d->jam_mulai).' WIB';
					$selesai = $this->formatter->getDateMonthFormatUser($d->tgl_selesai).' - '.$this->formatter->timeFormatUser($d->jam_selesai).' WIB';
					if($d->skd_dibayar == 1){
						$skd = '<span class="text-success">SKD Dibayar</span>';
					}else{
						$skd = '<span class="text-danger">SKD Tidak Dibayar</span>';
					}
					$datax=[
						'id'=>$d->id_izin_cuti,
						'id_karyawan'=>$d->id_karyawan,
						'nomor'=>$d->kode_izin_cuti,
						'tanggal_mulai'=>$mulai,
						'tanggal_selesai'=>$selesai,
						'tgl_mulai_val'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$d->jam_mulai,
						'tgl_selesai_val'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$d->jam_selesai,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jenis_cuti'=>$d->nama_jenis_izin.' ('.$nama_jenis.')',
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'alasan'=>(!empty($d->alasan)) ? $d->alasan:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'skd'=>$skd,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'e_jenis_cuti'=>$d->jenis,
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($d->tgl_mulai),
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($d->tgl_selesai),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'e_alasan'=>$d->alasan,
						'e_skd'=>$d->skd_dibayar,
						'e_keterangan'=>$d->keterangan,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function log_perjalanan_dinas()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListPerjalananDinasNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_dinas('.$d->id_pd.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_pd,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB - '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						$tujuan,
						$kendaraan,
						$aksi,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd');
				$data=$this->model_karyawan->getPerjalananDinasID($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
					$jarak=($d->kendaraan=='KPD0001')?$d->jarak.' km':$this->otherfunctions->getMark();
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Tunjangan Perjanan Dinas</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama Karyawan</th>
          							<th>Grade</th>
          							<th>Transport</th>';
          							if(!empty($d->nama_penginapan)){
          								$tabel.='<th>Tunjangan Penginapan</th>';
          							}
          							if(!empty($d->tunjangan)){
          								$val_tunjangan=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
          								foreach ($val_tunjangan as $tunj) {
          									$nama_tunj=$this->model_master->getKategoriDinasKode($tunj)['nama'];
          									$tabel.='<th>'.$nama_tunj.'</th>';
          								}
          							}					
          							$tabel.='<th>Total</th>
          						</tr>
          					</thead>
          					<tbody>';
          						$no=1;
          						if(!empty($d->id_karyawan)){
          							$namaKar=$this->model_karyawan->getEmpID($d->id_karyawan)['nama'];
          							$gradeKar=$this->model_karyawan->getEmpID($d->id_karyawan)['grade'];
          							$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
          							$t_kendaraan=(!empty($d->kendaraan)?$d->kendaraan:null);
          							$kendaraan_umum=(!empty($d->nama_kendaraan)?$d->nama_kendaraan:null);
          							$jarak=(!empty($d->jarak)?$d->jarak:null);
          							$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
          							$nominal_bbm=$this->model_karyawan->getTunjanganBBM($t_kendaraan,$kendaraan_umum,$jarak)['nominal'];
          							$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
          							$nama_kendaraan=$this->model_master->getKendaraanKode($t_kendaraan)['nama'];
          							$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
          							$na=0;
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$namaKar.'</td>
          							<td>'.$namaGrade.'</td>
          							<td>'.$nama_kendaraan.' ('.$this->formatter->getFormatMoneyUser($nominal_bbm).')</td>';
          							$na=$na+$nominal_bbm;
          							if(!empty($penginapan)){
          								$tabel.='<td>'.$nama_penginapan.' ('.$this->formatter->getFormatMoneyUser($nominal_penginapan).')</td>';
          								$na=$na+$nominal_penginapan;
          							}
          							if(!empty($d->tunjangan)){
          								$val_tunjn=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
          								foreach ($val_tunjn as $tunj) {
          									$nominal=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
          									$na=$na+$nominal;
          									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal).'</td>';
          								}
          							}
          							$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
          							</tr>';
          							$no++;
          						}
	          				$tabel.='</tbody>
	          			</table>';
	          			$e_tunjangan=[];
	          			$tunja=(isset($d->tunjangan)?$this->otherfunctions->getParseOneLevelVar($d->tunjangan):[]);
	          			if (isset($tunja)) {
	          				foreach ($tunja as $key=>$tunj) {
	          					$e_tunjangan[$key]=$tunj;
	          				}
	          			}
					$datax=[
						'id'=>$d->id_pd,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tanggal_berangkat'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB',
						'tanggal_sampai'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						'tanggal_pulang'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_pulang,1).' WIB',
						'tujuan'=>$tujuan,
						'kendaraan'=>$kendaraan,
						'jarak'=>$d->jarak.' km',
						'plant'=>$d->plant,
						'menginap'=>$d->menginap,
						'nama_penginapan'=>$this->otherfunctions->getPenginapan($d->nama_penginapan),
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
						'tugas'=>(!empty($d->tugas)) ? $d->tugas:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'tabel_tunjangan'=>$tabel,
						'e_plant_tujuan'=>$d->plant_tujuan,
						'e_kendaraan'=>$d->kendaraan,
						'e_lokasi'=>$d->lokasi_tujuan,
						'e_jarak'=>$d->jarak,
						'e_kendaraan_umum'=>$d->nama_kendaraan,
						'e_nama_penginapan'=>$d->nama_penginapan,
						'e_tunjangan'=>$e_tunjangan,
						'e_tanggal_mulai'=>$this->formatter->getDateTimeFormatUser($d->tgl_berangkat),
						'e_tanggal_selesai'=>$this->formatter->getDateTimeFormatUser($d->tgl_sampai),
						'e_tanggal_pulang'=>$this->formatter->getDateTimeFormatUser($d->tgl_pulang),
						'e_tugas'=>$d->tugas,
						'e_keterangan'=>$d->keterangan,
						'e_mengetahui'=>$d->mengetahui,
						'e_menyetujui'=>$d->menyetujui,
						'e_dibuat'=>$d->dibuat,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function log_presensi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$kar=$this->model_karyawan->getEmployeeNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$data=$this->model_karyawan->getListPresensiIdKaryawan($kar['id_karyawan']);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$day = $this->formatter->getNameOfDay($d->tanggal);
					$month = $this->formatter->getDateMonthFormatUser($d->tanggal);
					$libur = $this->otherfunctions->checkHariLibur($d->tanggal);
					$day_month = $day.', '.$month;
					$ijin_cuti = $this->model_karyawan->getIzinCuti(null, ['a.id_karyawan'=>$d->id_p_karyawan,'a.tgl_mulai <='=>$d->tanggal,'a.tgl_selesai >='=>$d->tanggal]);
					$ijin_cuti =  $this->otherfunctions->convertResultToRowArray($ijin_cuti);
					$lembur = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getLembur(null, ['a.id_karyawan'=>$d->id_p_karyawan,'a.tgl_mulai '=>$d->tanggal]));
					if(!empty($lembur)){
						$lembur = (int)explode(":",$lembur['jumlah_lembur'])[0];
					}
					if(!empty($libur)){
						$lembur = $this->otherfunctions->getIntervalJam($d->jam_mulai,$d->jam_selesai);
					}
					$cekjadwal = $this->model_karyawan->cekJadwalKerjaIdDate($d->id_p_karyawan,$d->tanggal);
					$nama_shift = (empty($cekjadwal['nama_shift'])) ? '' : $cekjadwal['nama_shift'];
					$jam_mulai = (empty($cekjadwal['jam_mulai'])) ? '' : $cekjadwal['jam_mulai'];
					$jam_selesai = (empty($cekjadwal['jam_selesai'])) ? '' : $cekjadwal['jam_selesai'];
					$jadwal_jam_kerja = $nama_shift.' ['.$jam_mulai.' - '.$jam_selesai.']';
					if(empty($cekjadwal['nama_shift'])){
						$jadwal_jam_kerja = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Jadwal Tidak DItemukan</label></div>';
						$over = $this->otherfunctions->getLabelMark(null,'danger','Tidak Over','Jam');
						$plg_tlcp = $this->otherfunctions->getLabelMark(null,'danger','Jam Normal','Jam');
					}else{
						$over = $this->otherfunctions->getInterval($jam_selesai,$d->jam_selesai);
						$over = $over->h.'jam '.$over->i.'menit';
						$terlambat = $this->otherfunctions->getInterval($jam_mulai,$d->jam_mulai);
						$terlambat = $terlambat->h.'jam '.$terlambat->i.'menit';
						$plgcepat = '0jam 0menit';
						if($d->jam_selesai < $jam_selesai){
							$plgcepat = $this->otherfunctions->getInterval($d->jam_selesai,$jam_selesai);
							$plgcepat = $plgcepat->h.'jam '.$plgcepat->i.'menit';
						}
						$plg_tlcp = '<table><tr><td>Terlambat </td><td>: '.$terlambat.'</td></tr><tr><td>Pulang Cepat </td><td>: '.$plgcepat.'</td></tr></table>';
					}
					$x_jam_masuk = '<center>'.$this->formatter->getTimeFormatUser($d->jam_mulai,'WIB').'</center>';
					$x_jam_keluar = '<center>'.$this->formatter->getTimeFormatUser($d->jam_selesai,'WIB').'</center>';
					$x_jml_jam_kerja = '<center>'.$this->otherfunctions->getIntervalJam($d->jam_mulai,$d->jam_selesai).' Jam</center>';
					$x_jadwal_jam_kerja = $jadwal_jam_kerja;
					$x_ijin = '<center>'.$this->otherfunctions->getLabelMark($ijin_cuti['nama_jenis_izin'],'success','Tidak Izin').'</center>';
					$x_lembur = '<center>'.$this->otherfunctions->getLabelMark($lembur,'success','Tidak Lembur','Jam').'</center>';
					$x_over = '<center>'.$over.'</center>';
					$x_terlambat = '<center>'.$plg_tlcp.'</center>';
					$x_hari_libur = '<center>'.$this->otherfunctions->getLabelMark($libur,'success','Jam Kerja').'</center>';
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_presensi('.$d->id_p_karyawan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_p_karyawan,
						$day_month,
						$x_jam_masuk,
						$x_jam_keluar,
						$x_jml_jam_kerja,
						$x_jadwal_jam_kerja,
						$x_ijin,
						$x_lembur,
						$x_over,
						$x_terlambat,
						$x_hari_libur,
						$aksi
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_presensi');
				$data=$this->model_karyawan->getListPresensiId($id);
				foreach ($data as $d) {
					$day = $this->formatter->getNameOfDay($d->tanggal);
					$month = $this->formatter->getDateMonthFormatUser($d->tanggal);
					$status = ($d->status=='1') ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Tidak Aktif</b>';
					$cekjadwal = $this->model_karyawan->cekJadwalKerjaIdDate($d->id_karyawan,$d->tanggal);
					if(empty($cekjadwal['nama_shift'])){
						$jdw_jam = '<div><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Jadwal Tidak DItemukan</label></div>';
						$over = '<div><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Over</label></div>';
					}else{
						$nama_shift = (empty($cekjadwal['nama_shift'])) ? '' : $cekjadwal['nama_shift'];
						$jam_mulai = (empty($cekjadwal['jam_mulai'])) ? '' : $cekjadwal['jam_mulai'];
						$jam_selesai = (empty($cekjadwal['jam_selesai'])) ? '' : $cekjadwal['jam_selesai'];
						$jdw_jam = $nama_shift.' ['.$jam_mulai.' - '.$jam_selesai.']';
						$over = $this->otherfunctions->getLabelMark($this->otherfunctions->getRangeTime($jam_selesai,$d->jam_selesai),'danger','Tidak Over','Jam');
					}
					$jml_jam = $this->otherfunctions->getIntervalJam($d->jam_mulai,$d->jam_selesai);
					$libur = $this->otherfunctions->checkHariLibur($d->tanggal);
					$lembur = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getLembur(null, ['a.id_karyawan'=>$d->id_karyawan,'a.tgl_mulai '=>$d->tanggal]));
					if(!empty($lembur)){
						$lembur = (int)explode(":",$lembur['jumlah_lembur'])[0];
					}
					if(!empty($libur)){
						$lembur = $jml_jam;
					}
					$setJamMulai = (!empty($d->jam_mulai)) ? $d->jam_mulai : '00:00:00';
					$setJamSelesai = (!empty($d->jam_selesai)) ? $d->jam_selesai : '00:00:00';
					$namaizin=$this->model_karyawan->getIzinCutiPresensi($d->kode_ijin)['nama'];
					$datax=[
						'id'=>$d->id_p_karyawan,
						'id_karyawan' => $d->id_karyawan,
						'tgl_presensi'=>$day.', '.$month,
						'tgl_masuk'=>$this->formatter->getTimeFormatUser($d->jam_mulai,'WIB'),
						'tgl_selesai'=>$this->formatter->getTimeFormatUser($d->jam_selesai,'WIB'),
						'gettgl_mulai'=>$this->formatter->getDateTimeFormatUser($d->tanggal.' '.$d->jam_mulai),
						'gettgl_selesai'=>$this->formatter->getDateTimeFormatUser($d->tanggal.' '.$d->jam_selesai),
						'jam_kerja'=>$this->otherfunctions->getLabelMark($jml_jam,'danger','Tidak Scan','Jam','left'),
						'jadwal'=>$jdw_jam,
						'over'=>$over,
						'ijin_cuti'=>$this->otherfunctions->getLabelMark($namaizin,'success','Tidak Izin'),
						'lembur'=>$this->otherfunctions->getLabelMark($lembur,'success','Tidak Lembur','Jam','left'),
						'libur'=>$this->otherfunctions->getLabelMark($libur,'success','Tidak Libur','','left'),
						'status'=>$status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'update_by'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'tanggal'=>$this->formatter->getDateFormatUser($d->tanggal),
						'jam_mulai'=>$setJamMulai,
						'jam_selesai'=>$setJamSelesai,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
// ================================================= IZIN DAN CUTI KARYAWAN ========================================================
	public function view_izin_cuti()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListIzinCutiNik($this->codegenerator->decryptChar($this->uri->segment(4)),'fo','izin_fo');
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$tanggal='<label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fas fa-pen fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->update_date).' WIB</label>';
					$delete = null;
					// $delete ='<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=delete_modal('.$d->id_izin_cuti.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_izin_cuti.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
        			$print = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick="do_print('.$d->id_izin_cuti.')"><i class="fa fa-print" data-toggle="tooltip" title="Cetak Data"></i></button>';
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis);
					$datax['data'][]=[
						$d->id_izin_cuti,
						$d->kode_izin_cuti,
						'<label class="label label-success" style="font-size:14px;">'.$nama_jenis.'</label>',
						$d->nama_jenis_izin,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						$this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						(!empty($d->alasan))?$d->alasan:$this->otherfunctions->getMark(),
						$tanggal,
						$this->otherfunctions->getStatusIzin($d->validasi),
						$info.(($d->validasi!=2)?$delete:null).$print,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_izin_cuti');
				$data=$this->model_karyawan->getIzinCuti($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_menyetujui_2=($d->jbt_menyetujui_2 != null) ? ' - '.$d->jbt_menyetujui_2 : null;
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis_ic);
					$mulai = $this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->timeFormatUser($d->jam_mulai).' WIB';
					$selesai = $this->formatter->getDateMonthFormatUser($d->tgl_selesai).' - '.$this->formatter->timeFormatUser($d->jam_selesai).' WIB';
					if($d->skd_dibayar == 1){
						$skd = '<span class="text-success">SKD Dibayar</span>';
					}else{
						$skd = '<span class="text-danger">SKD Tidak Dibayar</span>';
					}
					$datax=[
						'id'=>$d->id_izin_cuti,
						'id_karyawan'=>$d->id_karyawan,
						'nomor'=>$d->kode_izin_cuti,
						'tanggal_mulai'=>$mulai,
						'tanggal_selesai'=>$selesai,
						'tgl_mulai_val'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$d->jam_mulai,
						'tgl_selesai_val'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$d->jam_selesai,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jenis_cuti'=>$d->nama_jenis_izin.' ('.$nama_jenis.')',
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'emenyetujui2'=>$d->menyetujui_2,
						'alasan'=>(!empty($d->alasan)) ? $d->alasan:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'skd'=>$skd,
						'status'=>$d->status,
						'validasi'=>$this->otherfunctions->getStatusIzin($d->validasi),
						'e_validasi'=>$d->validasi,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat_fo)) ? $d->nama_buat_fo:$d->nama_buat,
						'nama_update'=>(!empty($d->nama_update_fo))?$d->nama_update_fo:$d->nama_update,
						'e_jenis_cuti'=>$d->jenis,
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($d->tgl_mulai),
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($d->tgl_selesai),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'menyetujui2'=>(!empty($d->nama_menyetujui_2)) ? $d->nama_menyetujui_2.$jbt_menyetujui_2:$this->otherfunctions->getMark(),
						'e_alasan'=>$d->alasan,
						'e_skd'=>$d->skd_dibayar,
						'e_keterangan'=>$d->keterangan,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeIzinCuti();
        		echo json_encode($data);
			}elseif ($usage == 'izincuti') {
				$data = $this->model_master->getMasterIzinForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'refresh_mengetahui') {
				$data = $this->model_karyawan->getRefreshEmployeeForSelect2();
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function tanggalIzin(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_kar = $this->input->post('id_kar');
		$kode_izin = $this->input->post('jenis');
		$tanggal = $this->input->post('tanggal');
		$tgl_awal=$this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir=$this->formatter->getDateFromRange($tanggal,'end');
		$hari=$this->otherfunctions->hitungHari($tgl_awal,$tgl_akhir);
		$jenis=$this->model_master->getMasterIzinJenis($kode_izin)['jenis'];
		$maksimal=$this->model_master->getMasterIzinJenis($kode_izin)['maksimal'];
		$nama_jenis=$this->otherfunctions->getIzinCuti($jenis);
		$sisa_cuti=$this->model_karyawan->getEmpID($id_kar)['sisa_cuti'];
		if($jenis == 'C'){
			if($hari > $maksimal){
				$msg='<b>Jenis Cuti ini maksimal '.$maksimal.' Hari, tidak boleh '.$hari.' Hari, Sisa Cuti anda '.$sisa_cuti.' Hari</b>';
			}else{
				$msg='<b>Anda akan mengajukan '.$nama_jenis.' selama '.$hari.' Hari</b>';
			}
		}else{
			if($maksimal < $hari){
				$msg='<b>Jenis Izin ini maksimal '.$maksimal.' Hari, tidak boleh '.$hari.' Hari</b>';
			}else{
				$msg='<b>Anda akan mengajukan '.$nama_jenis.' selama '.$hari.' Hari</b>';
			}
		}
		$data=['msg'=>$msg,'jenis'=>$jenis,'hari'=>$hari,'maksimal'=>$maksimal,'sisa_cuti'=>$sisa_cuti];
		echo json_encode($data);
	}
	public function cekTanggalIzin(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_kar = $this->input->post('id_kar');
		$kode_izin = $this->input->post('jenis');
		$jenis=$this->model_master->getMasterIzinJenis($kode_izin)['jenis'];
		$cek=$this->model_karyawan->cekDataIzinCuti($id_kar, $jenis);
		$ci=[];
		foreach ($cek as $c) {
				$ci[]=$c['buat'] == $c['sekarang'] && $c['id_kar'] == $c['id_karyawan'] && $c['jenis'] == $c['jenis_db'];
		}
		if(array_sum($ci) > 0){
			$msg='Anda tidak diIzinkan untuk mengajukan Cuti / Izin lebih dari 1 (Satu) Kali dalam 1 Hari, Silahkan mengajukan Cuti /Izin lagi besok atau Edit Data Pengajuan Cuti anda Sebelumnya..';
		}else{
			$msg=null;
		}
		$sum=array_sum($ci);
		$data=['msg'=>$msg,'cek'=>$sum,];
		echo json_encode($data);
	}
	public function cekSisaCuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$msg        = null;
		$sisa_cuti  = 0;
		$id_kar     = $this->input->post('id_kar');
		$kode_izin  = $this->input->post('jenis');
		$tanggal    = $this->input->post('tanggal');
		$tgl_awal   = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir  = $this->formatter->getDateFromRange($tanggal,'end');
		$hari       = $this->otherfunctions->hitungHari($tgl_awal,$tgl_akhir);
		$jenis      = $this->model_master->getMasterIzinJenis($kode_izin)['jenis'];
		$potong_cuti= $this->model_master->getMasterIzinJenis($kode_izin)['potong_cuti'];
		if($jenis == 'C' && $potong_cuti == 1){
			$sisa_cuti=$this->model_karyawan->getEmpID($id_kar)['sisa_cuti'];
			if($sisa_cuti <= $hari){
				$msg='Sisa Cuti Anda '.$sisa_cuti.' Hari, Anda tidak dapat mengajukan cuti, silahkan hubungi administrator..';
			}
		}elseif($jenis == 'C' && $potong_cuti != 1){
			$msg='Anda memilih jenis Cuti yang tidak memotong jumlah cuti tahunan.';
		}else{
			$msg=null;
		}
		$data=['msg'=>$msg,'sisa_cuti'=>$sisa_cuti,'hari'=>$hari,'potong_cuti'=>$potong_cuti];
		echo json_encode($data);
	}
	public function minCuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_kar     = $this->input->post('id_kar');
		$kode_izin  = $this->input->post('jenis');
		$tanggal    = $this->input->post('tanggal');
		$msg = null;
		if($kode_izin == 'MIC201907160001'){
			$dataCuti = $this->model_karyawan->getListIzinCutiWhereMaxID(['id_karyawan'=>$id_kar,'jenis'=>$kode_izin,'validasi !='=>'0']);
			$tgl_awal   = $this->formatter->getDateFromRange($tanggal,'start');
			if(strtotime($dataCuti['tgl_selesai']) > strtotime($tgl_awal)){
				$msg = 'Anda belum bisa mengajukan Cuti Tahunan karena Tanggal yang anda input harus melewati terakhir kali anda cuti';
			}else{
				$hari       = $this->otherfunctions->countDayNotIncludeLeave($dataCuti['tgl_selesai'], $tgl_awal);
				$minCuti	= $this->model_master->getGeneralSetting('MIN_CUTI')['value_int'];
				if($minCuti > $hari){
					$msg = 'Anda belum bisa mengajukan Cuti Tahunan Kembali karena anda mengajukan Cuti Tahunan '.$hari.' Hari yang lalu, jarak minimal pengajuan antar Cuti tahunan adalah '.$minCuti.' Hari';
				}
			}
		}
		$data=['msg'=>$msg];
		echo json_encode($data);
	}
	function edit_izin_cuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$tanggal = $this->input->post('tanggal');
		$tgl_awal = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tanggal,'end');
		$t_awal=explode(" ", $tgl_awal);
		$t_akhir=explode(" ", $tgl_akhir);
		if ($id != "") {
			$data=array(
				'id_karyawan'=>$this->input->post('id_karyawan'),
				'kode_izin_cuti'=>$this->input->post('no_cuti'),
				'jenis'=>$this->input->post('jenis_cuti'),
				'tgl_mulai'=>$t_awal[0],
				'tgl_selesai'=>$t_akhir[0],
				'jam_mulai'=>$t_awal[1],
				'jam_selesai'=>$t_akhir[1],
				'alasan'=>$this->input->post('alasan'),
				'skd_dibayar'=>$this->input->post('skd'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'menyetujui_2'=>$this->input->post('menyetujui2'),
				'keterangan'=>$this->input->post('keterangan'),
				'ubfo'=>$this->admin,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_izin_cuti_karyawan',['id_izin_cuti'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_izin_cuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_cuti');
		$tanggal = $this->input->post('tanggal');
		$tgl_awal = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tanggal,'end');
		$t_awal=explode(" ", $tgl_awal);
		$t_akhir=explode(" ", $tgl_akhir);
		$emp=$this->model_karyawan->getEmpID($this->input->post('id_karyawan_cuti'));
		$ats=$this->model_master->getJabatanKodeRow($emp['jabatan']);
		$atasan=$this->model_karyawan->getEmployeeJabatan($ats['atasan']);
		$token=$this->rando.uniqid();
		$date_now=$this->date;
		$dtin=['token'=>$token,'date_now'=>$date_now];
		$link=$this->otherfunctions->companyClientProfile()['link_val_izin'].$this->codegenerator->encryptChar($dtin);
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
					'jenis'=>$this->model_master->getMasterIzinJenis($this->input->post('jenis_cuti'))['nama'], 
					'alasan'=>$this->input->post('alasan_cuti'), 
					'keterangan'=>$this->input->post('keterangan_cuti'), 
					'tgl'=>$this->date, 
					'kode'=>$kode,
					'url'=>$this->otherfunctions->companyClientProfile()['url'],
					'logo'=>$this->otherfunctions->companyClientProfile()['logo_url'],
				];
		$data_atasan = ['kepada'=>$atasan['nama'], 
				'nama'=>$emp['nama'], 
				'nik'=>$emp['nik'], 
				'alamat'=>$alamat_emp,
				'jabatan'=>$emp['nama_jabatan'], 
				'loker'=>$emp['nama_loker'],
				'tanggal'=>$tanggal, 
				'jenis'=>$this->model_master->getMasterIzinJenis($this->input->post('jenis_cuti'))['nama'], 
				'alasan'=>$this->input->post('alasan_cuti'),
				'keterangan'=>$this->input->post('keterangan_cuti'), 
				'tgl'=>$this->date, 
				'link'=>$link, 
				'kode'=>$kode,
				'url'=>$this->otherfunctions->companyClientProfile()['url'],
				'logo'=>$this->otherfunctions->companyClientProfile()['logo_url'],
			];
		$email_emp=$emp['email'];
		$email_atasan=$atasan['email'];
		// $email_emp='abuumarsg@gmail.com';
		// $email_atasan='ahmadumar559@gmail.com';
		if ($kode != "") {
			$data=array(
				'id_karyawan'=>$this->input->post('id_karyawan_cuti'),
				'kode_izin_cuti'=>$kode,
				'jenis'=>$this->input->post('jenis_cuti'),
				'skd_dibayar'=>$this->input->post('skd'),
				'tgl_mulai'=>$t_awal[0],
				'tgl_selesai'=>$t_akhir[0],
				'jam_mulai'=>$t_awal[1],
				'jam_selesai'=>$t_akhir[1],
				'alasan'=>$this->input->post('alasan_cuti'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'menyetujui_2'=>$this->input->post('menyetujui2'),
				'keterangan'=>$this->input->post('keterangan_cuti'),
				'token'=>$token,
				'date_now'=>$date_now,
				'cbfo'=>$this->admin,
				'ubfo'=>$this->admin,
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$dbs = $this->model_global->insertQueryCC($data,'data_izin_cuti_karyawan',$this->model_karyawan->checkIzinCutiCode($data['kode_izin_cuti']));
			if(isset($atasan)){
				$ci = get_instance();
				$ci->email->initialize($this->otherfunctions->configEmail());
				$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengajuan Izin/Cuti Online');
				$list = array($email_atasan);
				$ci->email->to($email_atasan);
				$ci->email->subject('Pengajuan Izin/Cuti Online');
				$body = $this->load->view('email_template/email_izin_atasan',$data_atasan,TRUE);
				$ci->email->message($body);
				$eml=$this->email->send();
			}
			if(isset($emp)){
				$ci = get_instance();
				$ci->email->initialize($this->otherfunctions->configEmail());
				$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengajuan Izin/Cuti Online');
				$list = array($email_emp);
				$ci->email->to($email_emp);
				$ci->email->subject('Pengajuan Izin/Cuti Online');
				$body = $this->load->view('email_template/email_izin_karyawan',$data_emp,TRUE);
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
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
// ===================== Validasi Izin & Cuti =================================
	public function data_validasi_izin()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   	echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$nik=$this->codegenerator->decryptChar($this->uri->segment(4));
				$jabatan=$this->model_karyawan->getEmployeeNik($nik)['jabatan'];
				$bawahan=$this->model_master->getJabatanBawahan($jabatan);//['kode_jabatan'];
				$xx = [];
				foreach ($bawahan as $key) {
					array_push($xx, $key->kode_jabatan);
				}
				$dataz=$this->model_karyawan->getListIzinCutiBawahan($xx,null);
				$no=1;
				$datax['data']=[];
				if(isset($dataz)){
					foreach ($dataz as $data){
						foreach ($data as $d) {
							$tanggal='<label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fas fa-pen fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->update_date).' WIB</label>';
							$delete ='<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=delete_modal('.$d->id_izin_cuti.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
							$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_izin_cuti.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
							if($d->validasi==2){
								$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need('.$d->id_izin_cuti.')><i class="fa fa-warning"></i> Perlu Validasi</button> ';
							}elseif($d->validasi==1){
								$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes('.$d->id_izin_cuti.')><i class="fa fa-check-circle"></i> Diizinkan</button> ';
							}elseif($d->validasi==0){
								$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no('.$d->id_izin_cuti.')><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
							}
							$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis);
							$datax['data'][]=[
								$d->id_izin_cuti,
								$d->nik_karyawan,
								$d->nama_karyawan,
								'<label class="label label-success" style="font-size:14px;">'.$nama_jenis.'</label>',
								$d->nama_jenis_izin,
								$this->formatter->getDateMonthFormatUser($d->tgl_mulai),
								$this->formatter->getDateMonthFormatUser($d->tgl_selesai),
								$tanggal,
								$validasi,
								$info.(($d->validasi!=2)?$delete:null),
							];
							$no++;
						}
					}
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_izin_cuti');
				$data=$this->model_karyawan->getIzinCuti($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis_ic);
					$mulai = $this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->timeFormatUser($d->jam_mulai).' WIB';
					$selesai = $this->formatter->getDateMonthFormatUser($d->tgl_selesai).' - '.$this->formatter->timeFormatUser($d->jam_selesai).' WIB';
					if($d->skd_dibayar == 1){
						$skd = '<span class="text-success">SKD Dibayar</span>';
					}else{
						$skd = '<span class="text-danger">SKD Tidak Dibayar</span>';
					}
					$datax=[
						'id'=>$d->id_izin_cuti,
						'id_karyawan'=>$d->id_karyawan,
						'nama_jabatan'=>$d->nama_jabatan,
						'nama_bagian'=>$d->nama_bagian,
						'nomor'=>$d->kode_izin_cuti,
						'tanggal_mulai'=>$mulai,
						'tanggal_selesai'=>$selesai,
						'tgl_mulai_val'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$d->jam_mulai,
						'tgl_selesai_val'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$d->jam_selesai,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jenis_cuti'=>$d->nama_jenis_izin.' ('.$nama_jenis.')',
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'alasan'=>(!empty($d->alasan)) ? $d->alasan:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'skd'=>$skd,
						'status'=>$d->status,
						'validasi'=>$this->otherfunctions->getStatusIzin($d->validasi),
						'e_validasi'=>$d->validasi,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'e_jenis_cuti'=>$d->jenis,
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($d->tgl_mulai),
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($d->tgl_selesai),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'e_alasan'=>$d->alasan,
						'e_skd'=>$d->skd_dibayar,
						'e_keterangan'=>$d->keterangan,
						'nama_jenis_ic'=>$d->jenis_ic,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function validasi_izin()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id_izin_cuti');
		$id_kar=$this->input->post('id_k');
		$val_db=$this->input->post('validasi_db');
		$jenis_db=$this->input->post('jenis_db');
		$vali=$this->input->post('validasi');
		$sisa_cuti_db=$this->model_karyawan->getEmpID($id_kar)['sisa_cuti'];
		$tgl_mulai=$this->model_karyawan->getIzinCutiID($id)['tgl_mulai'];
		$tgl_selesai=$this->model_karyawan->getIzinCutiID($id)['tgl_selesai'];
		$jum_cuti=$this->otherfunctions->hitungHari($tgl_mulai,$tgl_selesai);
		$d_izin_cuti=$this->model_karyawan->getIzinCutiID($id);
		$potongCuti=$this->model_master->getMasterIzinJenis($d_izin_cuti['jenis'])['potong_cuti'];
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
			$where=['id_izin_cuti'=>$id,'validasi'=>$val_db];
			$datax=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
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
			$where=['id_izin_cuti'=>$id,'validasi'=>$val_db];
			$datax=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
		}else{
			$data=['validasi'=>$vali];
			$where=['id_izin_cuti'=>$id,'validasi'=>$val_db];
			$dbs=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
		}
		echo json_encode($datax);
	}
// ===================== Data Exit Interview =================================
	public function data_exit_interview()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   	echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$nik=$this->codegenerator->decryptChar($this->uri->segment(4));
				$data=$this->model_karyawan->getListExitInterviewNik($nik);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$tanggal='<label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fas fa-pen fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->update_date).' WIB</label>';
					$delete ='<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=delete_modal('.$d->id_exit.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_exit.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$cetak = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=do_print('.$d->id_exit.')><i class="fa fa-print" data-toggle="tooltip" title="Cetak Data"></i></button> ';
					$datax['data'][]=[
						$d->id_exit,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
						$this->formatter->getDateMonthFormatUser($d->tgl_keluar),
						$d->nama_alasan,
						$tanggal,
						$info.$delete.$cetak,
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
// ================================================= DATA PERJALANAN DINAS ==========================================================
	public function data_perjalanan_dinas()
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
					$data=$this->model_karyawan->getListDataPerjalananDinas();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$tanggal = $this->input->post('tanggal');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal];
					$data=$this->model_karyawan->getListDataPerjalananDinas('search',$where,'cari');
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$datax['data'][]=[
						$d->id_pd,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' - '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1),
						$tujuan,
						$d->jum,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd');
				$data=$this->model_karyawan->getPerjalananDinasID($id);
				foreach ($data as $d) {
					$tabel='';
					$tabel.='<hr><h4 align="center"><b>Data Perjanan Dinas '.$d->nama_karyawan.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>No Perjalanan Dinas</th>
          							<th>Tanggal</th>
          							<th>Tujuan</th>
          							<th>Kendaraan</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_pd=$this->model_karyawan->getListPerjalananDinasNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_pd as $dpd) {
									$tujuan=($dpd->plant=='plant')?$dpd->nama_plant_tujuan:$dpd->lokasi_tujuan;
									$kendaraan=($dpd->kendaraan=='KPD0001')?$dpd->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($dpd->nama_kendaraan).')':$dpd->nama_kendaraan_j;
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$dpd->no_sk.'</td>
          							<td>'.$this->formatter->getDateTimeMonthFormatUser($dpd->tgl_berangkat,1).' - '.$this->formatter->getDateTimeMonthFormatUser($dpd->tgl_pulang,1).'</td>
          							<td>'.$tujuan.'</td>
          							<td>'.$kendaraan.'</td>
          							</tr>';
          						$no++;
          					}
	          				$tabel.='</tbody>
	          			</table>';
					$datax=[
						'tabel'=>$tabel,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'id'=>$d->id_pd,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_tunjangan') {
				parse_str($this->input->post('search'),$search);
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Tunjangan Perjalanan Dinas</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama Karyawan</th>
          							<th>Grade</th>
									<th>Jarak</th>';
          							if(!empty($search['id_karyawan'])){
          								if(count($search['id_karyawan'])==1){
											$tabel.='<th>Transfort</th>';
										}
									}
									if(!empty($search['penginapan'])){
										if(count($search['id_karyawan'])==1){
											$tabel.='<th>Tunjangan Penginapan</th>';
										}
									}
          							// <th>Transport</th>';
          							// if(!empty($search['penginapan'])){
          							// 	$tabel.='<th>Tunjangan Penginapan</th>';
									// }
									$tunjangan=$this->model_master->getListKategoriDinas('KAPD0001');
          							// if(!empty($tunjangan)){
          							// 	foreach ($tunjangan as $tunj) {
          							// 		$nama_tunj=$this->model_master->getKategoriDinasKode($tunj->kode)['nama'];
          							// 		$tabel.='<th>'.$nama_tunj.'</th>';
          							// 	}
									// }
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0003')['nama'].'</th>';		
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0004')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0005')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0006')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';
          							$tabel.='<th>Total</th>
          						</tr>
          					</thead>
          					<tbody>';
								$no=1;
								$nominal_bbm_all=[];
								$nama_kendaraan_all=[];
								$nama_penginapan_all=[];
								$nominal_penginapan_all=[];
          						if(!empty($search['id_karyawan'])){
          							foreach ($search['id_karyawan'] as $id_k) {
          								$namaKar=$this->model_karyawan->getEmpID($id_k)['nama'];
          								$gradeKar=$this->model_karyawan->getEmpID($id_k)['grade'];
          								$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
          								// $nominal=[];
          								// if(!empty($tunjangan)){
          								// 	foreach ($tunjangan as $tunj) {
          								// 		$nominal[]=$this->formatter->getFormatMoneyUser($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal']);
          								// 	}
          								// }
          								$kendaraan=!empty($search['kendaraan'])?$search['kendaraan']:null;
										$kendaraan_umum=($search['kendaraan_umum']!=null?$search['kendaraan_umum']:null);
										// $n_k_umum=(($this->otherfunctions->getKendaraanUmum($search['kendaraan_umum'])!=null)?$this->otherfunctions->getKendaraanUmum($search['kendaraan_umum']):null);
										$jarak_antar_plant=$this->model_master->jarakAntarPlant($search['plant_asal'],$search['plant_tujuan'])['jarak'];
          								$jarak=(($search['jarak']!=null)?$search['jarak']:$jarak_antar_plant);
										$penginapan=(!empty($search['penginapan'])?$search['penginapan']:null);
										// $n_bbm.=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
										$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
          								$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
          								$nama_kendaraan=$this->model_master->getKendaraanKode($kendaraan)['nama'];
          								$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
          								$nama_kendaraan_all[]=$this->model_master->getKendaraanKode($kendaraan)['nama'];
          								$nama_penginapan_all[]=$this->otherfunctions->getPenginapan($penginapan);
										$nominal_bbm_all[]=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
          								$nominal_penginapan_all[]=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
										$na=0;
										$jarak_val=!empty($jarak)?$jarak.' KM':'0 KM';
          								$tabel.='<tr>
          								<td>'.$no.'</td>
          								<td>'.$namaKar.'</td>
          								<td>'.$namaGrade.'</td>
          								<td>'.$jarak_val.'</td>';
          								// <td>'.$nama_kendaraan.' <br>('.$this->formatter->getFormatMoneyUser($nominal_bbm).')</td>';
										if(!empty($search['id_karyawan'])){
											if(count($search['id_karyawan'])==1){
												$tabel.='<td>'.$nama_kendaraan.' <br>('.$this->formatter->getFormatMoneyUser($nominal_bbm).')</td>';
          										$na=$na+$nominal_bbm;
											}
										}
										if(!empty($penginapan)){
											if(count($search['id_karyawan'])==1){
												$tabel.='<td>'.$nama_penginapan.' ('.$this->formatter->getFormatMoneyUser($nominal_penginapan).')</td>';
												$na=$na+$nominal_penginapan;
											}
										}
          								// if(!empty($tunjangan)){
          								// 	foreach ($tunjangan as $tunj) {
          								// 		$nominal=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal'];
          								// 		$na=$na+$nominal;
          								// 		$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal).'</td>';
          								// 	}
										// }
										$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
          								$na=$na+$nominal_dasar;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_dasar).'</td>'; 
										$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
          								$na=$na+$nominal_grade;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_grade).'</td>';
										$nominal_pd=(($jarak >= 80)?($nominal_dasar+$nominal_grade):0);
          								$na=$na+$nominal_pd;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_pd).'</td>'; 
										$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
          								$na=$na+$nominal_lembur;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_lembur).'</td>'; 
										$nominal_saku=(($jarak >= 80)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
          								$na=$na+$nominal_saku;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_saku).'</td>';  
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
          								</tr>';
          								$no++;
          							}
          						}
	          				$tabel.='</tbody>
						  </table>';
						  $id_k=(!empty($search['id_karyawan'])?($search['id_karyawan']):0);
					$datax=[
						'tabel'=>$tabel,
						'nominal_bbm_all'=>$this->formatter->getFormatMoneyUser(array_sum($nominal_bbm_all)/count($id_k)*$search['jum_kendaraan']),
						'nominal_bbm_per_ken'=>$this->formatter->getFormatMoneyUser(array_sum($nominal_bbm_all)/count($id_k)),
						// 'nominal_tunjangan_all'=>$this->formatter->getFormatMoneyUser(array_sum($nominal_penginapan_all)/count($id_k)),
						// 'nominal_bbm_all'=>$n_bbm*$search['jum_kendaraan'],
						'nominal_tunjangan_all'=>$search['nominal_penginapan'],
						'nama_kendaraan_all'=>isset($nama_kendaraan_all)?$nama_kendaraan_all[0]:$this->otherfunctions->getMark(),
						'nama_tunjangan_all'=>isset($nama_kendaraan_all)?$nama_penginapan_all[0]:$this->otherfunctions->getMark(),
						'jumlah'=>count($search['id_karyawan']),
						'jum_ken'=>$search['jum_kendaraan'],
						// 'n_bbm'=>$n_bbm,
					];
        		echo json_encode($datax);
			}elseif($usage == 'view_all_trans') {
				$data=$this->model_karyawan->getPerjalananDinasPerTransaksi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal_u("'.$d->no_sk.'")><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_u("'.$d->no_sk.'")><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$datax['data'][]=[
						$d->id_pd,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' -<br>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1),
						$d->nama_plant_asal,
						$tujuan,
						$d->jum.' Karyawan',
						$this->otherfunctions->getStatusIzin($d->validasi_ac),
						$properties['tanggal'],
						$info.$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif($usage == 'view_one_trans'){
				$kode = $this->input->post('no_sk');
				$data=$this->model_karyawan->getPerjalananDinasKodeSK($kode);
				$jum_kar=count($data);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
					$jarak=($d->kendaraan=='KPD0001')?$d->jarak.' km':$this->otherfunctions->getMark();
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Tunjangan Perjalanan Dinas</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama Karyawan</th>
          							<th>Grade</th>';
          							// if(!empty($d->nama_penginapan)){
          							// 	$tabel.='<th>Tunjangan Penginapan</th>';
          							// }
          							// if(!empty($d->tunjangan)){
          							// 	$val_tunjangan=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
          							// 	foreach ($val_tunjangan as $tunj) {
          							// 		$nama_tunj=$this->model_master->getKategoriDinasKode($tunj)['nama'];
          							// 		$tabel.='<th>'.$nama_tunj.'</th>';
          							// 	}
									// }
									$tunjangan=$this->model_master->getListKategoriDinas('KAPD0001');
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0003')['nama'].'</th>';		
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0004')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0005')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0006')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';  
          							$tabel.='<th>Total</th>
          						</tr>
          					</thead>
          					<tbody>';
								$no=1;
								$dataw=$this->model_karyawan->getEmpNoSKTransaksi($kode);
								foreach($dataw as $w){
									// if(!empty($d->id_karyawan)){
										$namaKar=$this->model_karyawan->getEmpID($w->id_karyawan)['nama'];
										$gradeKar=$this->model_karyawan->getEmpID($w->id_karyawan)['grade'];
										$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
										// $t_kendaraan=(!empty($d->kendaraan)?$d->kendaraan:null);
										// $kendaraan_umum=(!empty($d->nama_kendaraan)?$d->nama_kendaraan:null);
										$jarak=(!empty($d->jarak)?$d->jarak:null);
										$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
										// $nominal_bbm=$this->model_karyawan->getTunjanganBBM($t_kendaraan,$kendaraan_umum,$jarak)['nominal'];
										$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
										// $nama_kendaraan=$this->model_master->getKendaraanKode($t_kendaraan)['nama'];
										$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
										$na=0;
										// $jarak_val=!empty($jarak)?$jarak.' KM':'0 KM';
										$tabel.='<tr>
										<td>'.$no.'</td>
										<td>'.$namaKar.'</td>
										<td>'.$namaGrade.'</td>';
										// $na=$na+$nominal_bbm;
										// if(!empty($penginapan)){
										// 	$tabel.='<td>'.$nama_penginapan.' ('.$this->formatter->getFormatMoneyUser($nominal_penginapan).')</td>';
										// 	$na=$na+$nominal_penginapan;
										// }
										// // if(!empty($d->tunjangan)){
										// // 	$val_tunjn=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
										// // 	foreach ($val_tunjn as $tunj) {
										// // 		$nominal=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
										// // 		$na=$na+$nominal;
										// // 		$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal).'</td>';
										// // 	}
										// // }
										$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
										$na=$na+$nominal_dasar;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_dasar).'</td>'; 
										$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
										$na=$na+$nominal_grade;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_grade).'</td>'; 
										// $nominal_pd=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0005')['nominal'];
										$nominal_pd=(($jarak >= 80)?($nominal_dasar+$nominal_grade):0);
										$na=$na+$nominal_pd;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_pd).'</td>'; 
										$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
										$na=$na+$nominal_lembur;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_lembur).'</td>'; 
										$nominal_saku=(($jarak >= 80)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
										$na=$na+$nominal_saku;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_saku).'</td>';
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
										</tr>';
										$no++;
									// }
								}
	          				$tabel.='</tbody>
	          			</table>';
	          			// $e_tunjangan=[];
	          			// $tunja=(isset($d->tunjangan)?$this->otherfunctions->getParseOneLevelVar($d->tunjangan):[]);
	          			// if (isset($tunja)) {
	          			// 	foreach ($tunja as $key=>$tunj) {
	          			// 		$e_tunjangan[$key]=$tunj;
	          			// 	}
						  // }
					$e_karyawan=[];
					foreach ($dataw as $key) {
						$e_karyawan[]=$key->id_karyawan;
					}
					$namaKar=$this->model_karyawan->getEmpID($d->id_karyawan)['nama'];
					$gradeKar=$this->model_karyawan->getEmpID($d->id_karyawan)['grade'];
					$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
					$t_kendaraan=(!empty($d->kendaraan)?$d->kendaraan:null);
					$kendaraan_umum=(!empty($d->nama_kendaraan)?$d->nama_kendaraan:null);
					$jarak=(!empty($d->jarak)?$d->jarak:null);
					$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($t_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
					$datax=[
						'id'=>$d->id_pd,
						// 'id_karyawan'=>$d->id_karyawan,
						'e_karyawan'=>$e_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jum_kar'=>$jum_kar,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tanggal_berangkat'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB',
						'tanggal_sampai'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						'tanggal_pulang'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_pulang,1).' WIB',
						'tujuan'=>$tujuan,
						'asal'=>$d->nama_plant_asal,
						'kendaraan'=>$kendaraan,
						'jarak'=>$d->jarak.' km',
						'plant'=>$d->plant,
						'menginap'=>$d->menginap,
						'nama_penginapan'=>$this->otherfunctions->getPenginapan($d->nama_penginapan),
						// 'nominal_bbm'=>(!empty($nominal_bbm)?$this->formatter->getFormatMoneyUser($nominal_bbm):$this->otherfunctions->getMark()),
						// 'nominal_penginapan'=>(!empty($nominal_penginapan)?$this->formatter->getFormatMoneyUser($nominal_penginapan):$this->otherfunctions->getMark()),
						'nominal_bbm'=>(!empty($d->nominal_bbm)?$this->formatter->getFormatMoneyUser($d->nominal_bbm):$this->otherfunctions->getMark()),
						'jumlah_kendaraan'=>(!empty($d->jumlah_kendaraan)?$d->jumlah_kendaraan.' Kendaraan':$this->otherfunctions->getMark()),
						'nominal_per_ken'=>(!empty($d->nominal_bbm)&&!empty($d->jumlah_kendaraan)?$this->formatter->getFormatMoneyUser($d->nominal_bbm/$d->jumlah_kendaraan):$this->otherfunctions->getMark()),
						'nominal_penginapan'=>(!empty($d->nominal_penginapan)?$this->formatter->getFormatMoneyUser($d->nominal_penginapan):$this->otherfunctions->getMark()),
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
						'tugas'=>(!empty($d->tugas)) ? $d->tugas:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'tabel_tunjangan'=>$tabel,
						'e_plant_tujuan'=>$d->plant_tujuan,
						'e_plant_asal'=>$d->plant_asal,
						'e_kendaraan'=>$d->kendaraan,
						'e_lokasi'=>$d->lokasi_tujuan,
						'e_jarak'=>$d->jarak,
						'e_kendaraan_umum'=>$d->nama_kendaraan,
						'e_nama_penginapan'=>$d->nama_penginapan,
						'jumlah'=>$jum_kar,
						'jumlah_kendaraan_edit'=>$d->jumlah_kendaraan,
						// 'nominal_bbm_all'=>$search['nominal_bbm'],
						// 'nominal_tunjangan_all'=>$search['nominal_penginapan'],
						// 'nama_kendaraan_all'=>isset($nama_kendaraan_all)?$nama_kendaraan_all[0]:$this->otherfunctions->getMark(),
						// 'nama_tunjangan_all'=>isset($nama_kendaraan_all)?$nama_penginapan_all[0]:$this->otherfunctions->getMark(),
						// 'e_tunjangan'=>$e_tunjangan,
						'e_tanggal_mulai'=>$this->formatter->getDateTimeFormatUser($d->tgl_berangkat),
						'e_tanggal_selesai'=>$this->formatter->getDateTimeFormatUser($d->tgl_sampai),
						'e_tanggal_pulang'=>$this->formatter->getDateTimeFormatUser($d->tgl_pulang),
						'e_tugas'=>$d->tugas,
						'e_keterangan'=>$d->keterangan,
						'e_mengetahui'=>$d->mengetahui,
						'e_menyetujui'=>$d->menyetujui,
						'e_dibuat'=>$d->dibuat,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'cek_jumlah_karyawan') {
				$karyawan = $this->input->post('kary');
				$kary=($karyawan==null || $karyawan == 0)?null:count($karyawan);
				$data = ['val'=>$kary];
        		echo json_encode($data);
			}elseif ($usage == 'diberikan_karyawan') {
				$karyawan = $this->input->post('kary');
				$data=$this->model_karyawan->getEmployeeForSelect2ID($karyawan);
        		echo json_encode($data);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePerjalananDinas();
        		echo json_encode($data);
			}elseif ($usage == 'pilihtunjangan') {
				$data = $this->model_karyawan->pilihTunjanganSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'refreshtunjangan') {
				$data = $this->model_karyawan->refreshTunjangan();
        		echo json_encode($data);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'nama_bagian') {
				$data = $this->model_master->getBagianForSelect2();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_perjalanan_dinas()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListPerjalananDinasNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
					$datax['data'][]=[
						$d->id_pd,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB - '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						$tujuan,
						$kendaraan,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd');
				$data=$this->model_karyawan->getPerjalananDinasID($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
					$jarak=($d->kendaraan=='KPD0001')?$d->jarak.' km':$this->otherfunctions->getMark();
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Tunjangan Perjanan Dinas</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama Karyawan</th>
          							<th>Grade</th>
          							<th>Jarak</th>
          							<th>Transport</th>';
          							if(!empty($d->nama_penginapan)){
          								$tabel.='<th>Tunjangan Penginapan</th>';
          							}
          							// if(!empty($d->tunjangan)){
          							// 	$val_tunjangan=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
          							// 	foreach ($val_tunjangan as $tunj) {
          							// 		$nama_tunj=$this->model_master->getKategoriDinasKode($tunj)['nama'];
          							// 		$tabel.='<th>'.$nama_tunj.'</th>';
          							// 	}
									// }
									$tunjangan=$this->model_master->getListKategoriDinas('KAPD0001');
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0003')['nama'].'</th>';		
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0004')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0005')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0006')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';  
          							$tabel.='<th>Total</th>
          						</tr>
          					</thead>
          					<tbody>';
          						$no=1;
          						if(!empty($d->id_karyawan)){
          							$namaKar=$this->model_karyawan->getEmpID($d->id_karyawan)['nama'];
          							$gradeKar=$this->model_karyawan->getEmpID($d->id_karyawan)['grade'];
          							$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
          							$t_kendaraan=(!empty($d->kendaraan)?$d->kendaraan:null);
          							$kendaraan_umum=(!empty($d->nama_kendaraan)?$d->nama_kendaraan:null);
          							$jarak=(!empty($d->jarak)?$d->jarak:null);
          							$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
          							$nominal_bbm=$this->model_karyawan->getTunjanganBBM($t_kendaraan,$kendaraan_umum,$jarak)['nominal'];
          							$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
          							$nama_kendaraan=$this->model_master->getKendaraanKode($t_kendaraan)['nama'];
          							$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
          							$na=0;
									$jarak_val=!empty($jarak)?$jarak.' KM':'0 KM';
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$namaKar.'</td>
          							<td>'.$namaGrade.'</td>
          								<td>'.$jarak_val.'</td>
          							<td>'.$nama_kendaraan.' ('.$this->formatter->getFormatMoneyUser($nominal_bbm).')</td>';
          							$na=$na+$nominal_bbm;
          							if(!empty($penginapan)){
          								$tabel.='<td>'.$nama_penginapan.' ('.$this->formatter->getFormatMoneyUser($nominal_penginapan).')</td>';
          								$na=$na+$nominal_penginapan;
          							}
          							// if(!empty($d->tunjangan)){
          							// 	$val_tunjn=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
          							// 	foreach ($val_tunjn as $tunj) {
          							// 		$nominal=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
          							// 		$na=$na+$nominal;
          							// 		$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal).'</td>';
          							// 	}
									// }
									$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
									$na=$na+$nominal_dasar;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_dasar).'</td>'; 
									$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
									$na=$na+$nominal_grade;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_grade).'</td>'; 
									// $nominal_pd=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0005')['nominal'];
									$nominal_pd=(($jarak >= 80)?($nominal_dasar+$nominal_grade):0);
									$na=$na+$nominal_pd;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_pd).'</td>'; 
									$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
									$na=$na+$nominal_lembur;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_lembur).'</td>'; 
									$nominal_saku=(($jarak >= 80)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
									$na=$na+$nominal_saku;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_saku).'</td>';
          							$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
          							</tr>';
          							$no++;
          						}
	          				$tabel.='</tbody>
	          			</table>';
	          			// $e_tunjangan=[];
	          			// $tunja=(isset($d->tunjangan)?$this->otherfunctions->getParseOneLevelVar($d->tunjangan):[]);
	          			// if (isset($tunja)) {
	          			// 	foreach ($tunja as $key=>$tunj) {
	          			// 		$e_tunjangan[$key]=$tunj;
	          			// 	}
	          			// }
					$datax=[
						'id'=>$d->id_pd,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tanggal_berangkat'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB',
						'tanggal_sampai'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						'tanggal_pulang'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_pulang,1).' WIB',
						'tujuan'=>$tujuan,
						'asal'=>$d->nama_plant_asal,
						'kendaraan'=>$kendaraan,
						'jarak'=>$d->jarak.' km',
						'plant'=>$d->plant,
						'menginap'=>$d->menginap,
						'nama_penginapan'=>$this->otherfunctions->getPenginapan($d->nama_penginapan),
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
						'tugas'=>(!empty($d->tugas)) ? $d->tugas:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'tabel_tunjangan'=>$tabel,
						'e_plant_tujuan'=>$d->plant_tujuan,
						'e_plant_asal'=>$d->plant_asal,
						'e_kendaraan'=>$d->kendaraan,
						'e_lokasi'=>$d->lokasi_tujuan,
						'e_jarak'=>$d->jarak,
						'e_kendaraan_umum'=>$d->nama_kendaraan,
						'e_nama_penginapan'=>$d->nama_penginapan,
						// 'e_tunjangan'=>$e_tunjangan,
						'e_tanggal_mulai'=>$this->formatter->getDateTimeFormatUser($d->tgl_berangkat),
						'e_tanggal_selesai'=>$this->formatter->getDateTimeFormatUser($d->tgl_sampai),
						'e_tanggal_pulang'=>$this->formatter->getDateTimeFormatUser($d->tgl_pulang),
						'e_tugas'=>$d->tugas,
						'e_keterangan'=>$d->keterangan,
						'e_mengetahui'=>$d->mengetahui,
						'e_menyetujui'=>$d->menyetujui,
						'e_dibuat'=>$d->dibuat,
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
	public function add_perjalanan_dinas(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk');
		if ($kode != "") {
			$id_k=$this->input->post('id_karyawan');
			// $tunjangan=$this->input->post('tunjangan');
			$penginapan=$this->input->post('penginapan');
			$kode_kendaraan=$this->input->post('kendaraan');
			$kendaraan_umum=$this->input->post('kendaraan_umum');
			$jum_kendaraan=$this->input->post('jum_kendaraan');
			if($this->input->post('tujuan')=='plant'){
				$jarak=$this->model_master->jarakAntarPlant($this->input->post('plant_asal'),$this->input->post('plant_tujuan'))['jarak'];
			}else{
				$jarak=$this->input->post('jarak');
			}
			$jum_kar=count($id_k);
			foreach ($id_k as $id_ka) {
				$gradeKar=$this->model_karyawan->getEmpID($id_ka)['grade'];
				$na=0;
				// $nominal=[];
				// foreach ($tunjangan as $tunj) {
				// 	$nominal[]=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
				// 	$nominal_tunj=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
				// 	$na=$na+$nominal_tunj;
				// }
				$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
				$na=$na+$nominal_dasar;
				$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
				$na=$na+$nominal_grade;
				// $nominal_pd=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0005')['nominal'];
				$nominal_pd=(($jarak >= 80)?($nominal_dasar+$nominal_grade):0);
				$na=$na+$nominal_pd;
				$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
				$na=$na+$nominal_lembur;
				$nominal_saku=(($jarak >= 80)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
				$na=$na+$nominal_saku;
				if($jum_kar==1){
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
				}else{
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal']*$jum_kendaraan;
					// $nominal_bbm=$this->formatter->getFormatMoneyDb($this->input->post('nominal_bbm'));
					$nominal_penginapan=$this->formatter->getFormatMoneyDb($this->input->post('nominal_penginapan'));
				}
				$na=$na+$nominal_bbm;
				$na=$na+$nominal_penginapan;
				$besar_tunjangan=(isset($nominal_dasar)?$nominal_dasar:0).';'
					.(isset($nominal_grade)?$nominal_grade:0).';'
					.(isset($nominal_pd)?$nominal_pd:0).';'
					.(isset($nominal_lembur)?$nominal_lembur:0).';'
					.(isset($nominal_saku)?$nominal_saku:0);
				$data=[
					'id_karyawan'=>$id_ka,
					'no_sk'=>strtoupper($kode),
					'tgl_berangkat'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
					'tgl_sampai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
					'tgl_pulang'=>$this->formatter->getDateTimeFormatDb($this->input->post('tanggal_pulang')),
					'plant'=>$this->input->post('tujuan'),
					'plant_asal'=>$this->input->post('plant_asal'),
					'plant_tujuan'=>$this->input->post('plant_tujuan'),
					'lokasi_tujuan'=>$this->input->post('lokasi_tujuan'),
					'jarak'=>$jarak,
					'kendaraan'=>$kode_kendaraan,
					'nama_kendaraan'=>$kendaraan_umum,
					'jumlah_kendaraan'=>$jum_kendaraan,
					'nominal_bbm'=>$nominal_bbm,
					'menginap'=>$this->input->post('menginap'),
					'nama_penginapan'=>$penginapan,
					'nominal_penginapan'=>$nominal_penginapan,
					// 'tunjangan'=>implode(';',$tunjangan),
					'besar_tunjangan'=>$besar_tunjangan,//implode(';',$nominal),
					'total_tunjangan'=>$this->formatter->getFormatMoneyDb($na),
					'tugas'=>$this->input->post('tugas'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'dibuat'=>$this->input->post('dibuat'),
					'keterangan'=>$this->input->post('keterangan'),
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'data_perjalanan_dinas');
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_perjalanan_dinas(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode=$this->input->post('no_sk');
		$kar_old=$this->input->post('karyawan_old');
		$no_sk_old=$this->input->post('no_sk_old');
		$tgl_berangkat=$this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
		$tgl_sampai=$this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
		$id_karyawan=$this->input->post('id_karyawan');
		$penginapan=$this->input->post('penginapan');
		$jarak=$this->input->post('jarak');
		$kode_kendaraan=$this->input->post('kendaraan');
		$kendaraan_umum=$this->input->post('kendaraan_umum');
		$jum_kendaraan=$this->input->post('jum_kendaraan');
		$d_kar_old=$this->otherfunctions->getDataExplode($kar_old,',','all');
		if ($kode != "") {
			foreach ($id_karyawan as $idk) {
				$jum_kar=count($id_karyawan);
				$jum_kar_old=count($d_kar_old);
				$gradeKar=$this->model_karyawan->getEmpID($idk)['grade'];
				$na=0;
				// $nominal=[];
				// foreach ($tunjangan as $tunj) {
				// 	$nominal[]=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
				// 	$nominal_tunj=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
				// 	$na=$na+$nominal_tunj;
				// }
				$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
				$na=$na+$nominal_dasar;
				$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
				$na=$na+$nominal_grade;
				// $nominal_pd=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0005')['nominal'];
				$nominal_pd=(($jarak >= 80)?($nominal_dasar+$nominal_grade):0);
				$na=$na+$nominal_pd;
				$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
				$na=$na+$nominal_lembur;
				$nominal_saku=(($jarak >= 80)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
				$na=$na+$nominal_saku;
				if($jum_kar==1){
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
				}else{
					// $nominal_bbm=$this->formatter->getFormatMoneyDb($this->input->post('nominal_bbm'));
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal']*$jum_kendaraan;
					$n_penginapan=$this->formatter->getFormatMoneyDb($this->input->post('nominal_penginapan'));
					$nominal_penginapan=(($n_penginapan!='')?$n_penginapan:0);
				}
				$na=$na+$nominal_bbm;
				$na=$na+$nominal_penginapan;
				$besar_tunjangan=(isset($nominal_dasar)?$nominal_dasar:0).';'
					.(isset($nominal_grade)?$nominal_grade:0).';'
					.(isset($nominal_pd)?$nominal_pd:0).';'
					.(isset($nominal_lembur)?$nominal_lembur:0).';'
					.(isset($nominal_saku)?$nominal_saku:0);
				$tujuan=$this->input->post('tujuan');
				if($tujuan=='plant'){
					$lokasi_tujuan=null;
					$plant_tujuan=$this->input->post('plant_tujuan');
					$jarak=$this->model_master->jarakAntarPlant($this->input->post('plant_asal'),$this->input->post('plant_tujuan'))['jarak'];
				}else{
					$lokasi_tujuan=$this->input->post('lokasi_tujuan');
					$plant_tujuan=null;
					$jarak=$this->input->post('jarak');
				}
				$data=[
					'id_karyawan'=>$idk,
					'no_sk'=>strtoupper($kode),
					'tgl_berangkat'=>$tgl_berangkat,
					'tgl_sampai'=>$tgl_sampai,
					'tgl_pulang'=>$this->formatter->getDateTimeFormatDb($this->input->post('tanggal_pulang')),
					'plant'=>$tujuan,
					'plant_asal'=>$this->input->post('plant_asal'),
					'plant_tujuan'=>$plant_tujuan,
					'lokasi_tujuan'=>$lokasi_tujuan,
					'jarak'=>$jarak,
					'kendaraan'=>$kode_kendaraan,
					'nama_kendaraan'=>$kendaraan_umum,
					'nominal_bbm'=>$nominal_bbm,
					'jumlah_kendaraan'=>$jum_kendaraan,
					'menginap'=>$this->input->post('menginap'),
					'nama_penginapan'=>$penginapan,
					'nominal_penginapan'=>$nominal_penginapan,
					'besar_tunjangan'=>$besar_tunjangan,
					'total_tunjangan'=>$na,
					'tugas'=>$this->input->post('tugas'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'dibuat'=>$this->input->post('dibuat'),
					'keterangan'=>$this->input->post('keterangan'),
				];					
				if(in_array($idk, $d_kar_old)){
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$idk]);
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_karyawan)){
							$datax=$this->model_global->deleteQuery('data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$k_old]);
						}
					}
				}else{
					$data['id_karyawan']=$idk;
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQuery($data,'data_perjalanan_dinas');
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_karyawan)){
							$datax=$this->model_global->deleteQuery('data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$k_old]);
						}
					}
				}
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function val_perjalanan_dinas()
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
					$data=$this->model_karyawan->getListDataPerjalananDinas();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$tanggal = $this->input->post('tanggal');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal];
					$data=$this->model_karyawan->getListDataPerjalananDinas('search',$where,'cari');
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$datax['data'][]=[
						$d->id_pd,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' - '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1),
						$tujuan,
						$d->jum,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd');
				$data=$this->model_karyawan->getPerjalananDinasID($id);
				foreach ($data as $d) {
					$tabel='';
					$tabel.='<hr><h4 align="center"><b>Data Perjanan Dinas '.$d->nama_karyawan.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>No Perjalanan Dinas</th>
          							<th>Tanggal</th>
          							<th>Tujuan</th>
          							<th>Kendaraan</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_pd=$this->model_karyawan->getListPerjalananDinasNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_pd as $dpd) {
									$tujuan=($dpd->plant=='plant')?$dpd->nama_plant_tujuan:$dpd->lokasi_tujuan;
									$kendaraan=($dpd->kendaraan=='KPD0001')?$dpd->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($dpd->nama_kendaraan).')':$dpd->nama_kendaraan_j;
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$dpd->no_sk.'</td>
          							<td>'.$this->formatter->getDateTimeMonthFormatUser($dpd->tgl_berangkat,1).' - '.$this->formatter->getDateTimeMonthFormatUser($dpd->tgl_pulang,1).'</td>
          							<td>'.$tujuan.'</td>
          							<td>'.$kendaraan.'</td>
          							</tr>';
          						$no++;
          					}
	          				$tabel.='</tbody>
	          			</table>';
					$datax=[
						'tabel'=>$tabel,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'id'=>$d->id_pd,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_tunjangan') {
				parse_str($this->input->post('search'),$search);
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Tunjangan Perjalanan Dinas</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama Karyawan</th>
          							<th>Grade</th>
									<th>Jarak</th>';
          							if(!empty($search['id_karyawan'])){
          								if(count($search['id_karyawan'])==1){
											$tabel.='<th>Transfort</th>';
										}
									}
									if(!empty($search['penginapan'])){
										if(count($search['id_karyawan'])==1){
											$tabel.='<th>Tunjangan Penginapan</th>';
										}
									}
          							// <th>Transport</th>';
          							// if(!empty($search['penginapan'])){
          							// 	$tabel.='<th>Tunjangan Penginapan</th>';
									// }
									$tunjangan=$this->model_master->getListKategoriDinas('KAPD0001');
          							// if(!empty($tunjangan)){
          							// 	foreach ($tunjangan as $tunj) {
          							// 		$nama_tunj=$this->model_master->getKategoriDinasKode($tunj->kode)['nama'];
          							// 		$tabel.='<th>'.$nama_tunj.'</th>';
          							// 	}
									// }
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0003')['nama'].'</th>';		
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0004')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0005')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0006')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';
          							$tabel.='<th>Total</th>
          						</tr>
          					</thead>
          					<tbody>';
								$no=1;
								$nominal_bbm_all=[];
								$nama_kendaraan_all=[];
								$nama_penginapan_all=[];
								$nominal_penginapan_all=[];
          						if(!empty($search['id_karyawan'])){
          							foreach ($search['id_karyawan'] as $id_k) {
          								$namaKar=$this->model_karyawan->getEmpID($id_k)['nama'];
          								$gradeKar=$this->model_karyawan->getEmpID($id_k)['grade'];
          								$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
          								// $nominal=[];
          								// if(!empty($tunjangan)){
          								// 	foreach ($tunjangan as $tunj) {
          								// 		$nominal[]=$this->formatter->getFormatMoneyUser($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal']);
          								// 	}
          								// }
          								$kendaraan=!empty($search['kendaraan'])?$search['kendaraan']:null;
										$kendaraan_umum=($search['kendaraan_umum']!=null?$search['kendaraan_umum']:null);
										// $n_k_umum=(($this->otherfunctions->getKendaraanUmum($search['kendaraan_umum'])!=null)?$this->otherfunctions->getKendaraanUmum($search['kendaraan_umum']):null);
										$jarak_antar_plant=$this->model_master->jarakAntarPlant($search['plant_asal'],$search['plant_tujuan'])['jarak'];
          								$jarak=(($search['jarak']!=null)?$search['jarak']:$jarak_antar_plant);
										$penginapan=(!empty($search['penginapan'])?$search['penginapan']:null);
										// $n_bbm.=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
										$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
          								$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
          								$nama_kendaraan=$this->model_master->getKendaraanKode($kendaraan)['nama'];
          								$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
          								$nama_kendaraan_all[]=$this->model_master->getKendaraanKode($kendaraan)['nama'];
          								$nama_penginapan_all[]=$this->otherfunctions->getPenginapan($penginapan);
										$nominal_bbm_all[]=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
          								$nominal_penginapan_all[]=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
										$na=0;
										$jarak_val=!empty($jarak)?$jarak.' KM':'0 KM';
          								$tabel.='<tr>
          								<td>'.$no.'</td>
          								<td>'.$namaKar.'</td>
          								<td>'.$namaGrade.'</td>
          								<td>'.$jarak_val.'</td>';
          								// <td>'.$nama_kendaraan.' <br>('.$this->formatter->getFormatMoneyUser($nominal_bbm).')</td>';
										if(!empty($search['id_karyawan'])){
											if(count($search['id_karyawan'])==1){
												$tabel.='<td>'.$nama_kendaraan.' <br>('.$this->formatter->getFormatMoneyUser($nominal_bbm).')</td>';
          										$na=$na+$nominal_bbm;
											}
										}
										if(!empty($penginapan)){
											if(count($search['id_karyawan'])==1){
												$tabel.='<td>'.$nama_penginapan.' ('.$this->formatter->getFormatMoneyUser($nominal_penginapan).')</td>';
												$na=$na+$nominal_penginapan;
											}
										}
          								// if(!empty($tunjangan)){
          								// 	foreach ($tunjangan as $tunj) {
          								// 		$nominal=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal'];
          								// 		$na=$na+$nominal;
          								// 		$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal).'</td>';
          								// 	}
										// }
										$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
          								$na=$na+$nominal_dasar;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_dasar).'</td>'; 
										$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
          								$na=$na+$nominal_grade;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_grade).'</td>';
										$nominal_pd=(($jarak >= 80)?($nominal_dasar+$nominal_grade):0);
          								$na=$na+$nominal_pd;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_pd).'</td>'; 
										$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
          								$na=$na+$nominal_lembur;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_lembur).'</td>'; 
										$nominal_saku=(($jarak >= 80)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
          								$na=$na+$nominal_saku;
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_saku).'</td>';  
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
          								</tr>';
          								$no++;
          							}
          						}
	          				$tabel.='</tbody>
						  </table>';
						  $id_k=(!empty($search['id_karyawan'])?($search['id_karyawan']):0);
					$datax=[
						'tabel'=>$tabel,
						'nominal_bbm_all'=>$this->formatter->getFormatMoneyUser(array_sum($nominal_bbm_all)/count($id_k)*$search['jum_kendaraan']),
						'nominal_bbm_per_ken'=>$this->formatter->getFormatMoneyUser(array_sum($nominal_bbm_all)/count($id_k)),
						// 'nominal_tunjangan_all'=>$this->formatter->getFormatMoneyUser(array_sum($nominal_penginapan_all)/count($id_k)),
						// 'nominal_bbm_all'=>$n_bbm*$search['jum_kendaraan'],
						'nominal_tunjangan_all'=>$search['nominal_penginapan'],
						'nama_kendaraan_all'=>isset($nama_kendaraan_all)?$nama_kendaraan_all[0]:$this->otherfunctions->getMark(),
						'nama_tunjangan_all'=>isset($nama_kendaraan_all)?$nama_penginapan_all[0]:$this->otherfunctions->getMark(),
						'jumlah'=>count($search['id_karyawan']),
						'jum_ken'=>$search['jum_kendaraan'],
						// 'n_bbm'=>$n_bbm,
					];
        		echo json_encode($datax);
			}elseif($usage == 'view_all_trans') {
				$data=$this->model_karyawan->getPerjalananDinasPerTransaksi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal_u("'.$d->no_sk.'")><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_u("'.$d->no_sk.'")><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					if($d->validasi_ac==2){
						$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need("'.$d->no_sk.'")><i class="fa fa-warning"></i> Perlu Validasi</button> ';
					}elseif($d->validasi_ac==1){
						$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes("'.$d->no_sk.'")><i class="fa fa-check-circle"></i> Diizinkan</button> ';
					}elseif($d->validasi_ac==0){
						$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no("'.$d->no_sk.'")><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
					}
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$datax['data'][]=[
						$d->id_pd,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' -<br>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1),
						$d->nama_plant_asal,
						$tujuan,
						$d->jum.' Karyawan',
						$properties['tanggal'],
						$validasi,
						$info.$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif($usage == 'view_one_trans'){
				$kode = $this->input->post('no_sk');
				$data=$this->model_karyawan->getPerjalananDinasKodeSK($kode);
				$jum_kar=count($data);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
					$jarak=($d->kendaraan=='KPD0001')?$d->jarak.' km':$this->otherfunctions->getMark();
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Tunjangan Perjalanan Dinas</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama Karyawan</th>
          							<th>Grade</th>';
          							// if(!empty($d->nama_penginapan)){
          							// 	$tabel.='<th>Tunjangan Penginapan</th>';
          							// }
          							// if(!empty($d->tunjangan)){
          							// 	$val_tunjangan=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
          							// 	foreach ($val_tunjangan as $tunj) {
          							// 		$nama_tunj=$this->model_master->getKategoriDinasKode($tunj)['nama'];
          							// 		$tabel.='<th>'.$nama_tunj.'</th>';
          							// 	}
									// }
									$tunjangan=$this->model_master->getListKategoriDinas('KAPD0001');
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0003')['nama'].'</th>';		
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0004')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0005')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0006')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';  
          							$tabel.='<th>Total</th>
          						</tr>
          					</thead>
          					<tbody>';
								$no=1;
								$dataw=$this->model_karyawan->getEmpNoSKTransaksi($kode);
								foreach($dataw as $w){
									// if(!empty($d->id_karyawan)){
										$namaKar=$this->model_karyawan->getEmpID($w->id_karyawan)['nama'];
										$gradeKar=$this->model_karyawan->getEmpID($w->id_karyawan)['grade'];
										$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
										// $t_kendaraan=(!empty($d->kendaraan)?$d->kendaraan:null);
										// $kendaraan_umum=(!empty($d->nama_kendaraan)?$d->nama_kendaraan:null);
										$jarak=(!empty($d->jarak)?$d->jarak:null);
										$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
										// $nominal_bbm=$this->model_karyawan->getTunjanganBBM($t_kendaraan,$kendaraan_umum,$jarak)['nominal'];
										$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
										// $nama_kendaraan=$this->model_master->getKendaraanKode($t_kendaraan)['nama'];
										$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
										$na=0;
										// $jarak_val=!empty($jarak)?$jarak.' KM':'0 KM';
										$tabel.='<tr>
										<td>'.$no.'</td>
										<td>'.$namaKar.'</td>
										<td>'.$namaGrade.'</td>';
										// $na=$na+$nominal_bbm;
										// if(!empty($penginapan)){
										// 	$tabel.='<td>'.$nama_penginapan.' ('.$this->formatter->getFormatMoneyUser($nominal_penginapan).')</td>';
										// 	$na=$na+$nominal_penginapan;
										// }
										// // if(!empty($d->tunjangan)){
										// // 	$val_tunjn=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
										// // 	foreach ($val_tunjn as $tunj) {
										// // 		$nominal=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
										// // 		$na=$na+$nominal;
										// // 		$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal).'</td>';
										// // 	}
										// // }
										$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
										$na=$na+$nominal_dasar;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_dasar).'</td>'; 
										$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
										$na=$na+$nominal_grade;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_grade).'</td>'; 
										// $nominal_pd=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0005')['nominal'];
										$nominal_pd=(($jarak >= 80)?($nominal_dasar+$nominal_grade):0);
										$na=$na+$nominal_pd;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_pd).'</td>'; 
										$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
										$na=$na+$nominal_lembur;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_lembur).'</td>'; 
										$nominal_saku=(($jarak >= 80)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
										$na=$na+$nominal_saku;
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_saku).'</td>';
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
										</tr>';
										$no++;
									// }
								}
	          				$tabel.='</tbody>
	          			</table>';
	          			// $e_tunjangan=[];
	          			// $tunja=(isset($d->tunjangan)?$this->otherfunctions->getParseOneLevelVar($d->tunjangan):[]);
	          			// if (isset($tunja)) {
	          			// 	foreach ($tunja as $key=>$tunj) {
	          			// 		$e_tunjangan[$key]=$tunj;
	          			// 	}
						  // }
					$e_karyawan=[];
					foreach ($dataw as $key) {
						$e_karyawan[]=$key->id_karyawan;
					}
					$namaKar=$this->model_karyawan->getEmpID($d->id_karyawan)['nama'];
					$gradeKar=$this->model_karyawan->getEmpID($d->id_karyawan)['grade'];
					$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
					$t_kendaraan=(!empty($d->kendaraan)?$d->kendaraan:null);
					$kendaraan_umum=(!empty($d->nama_kendaraan)?$d->nama_kendaraan:null);
					$jarak=(!empty($d->jarak)?$d->jarak:null);
					$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($t_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
					$datax=[
						'id'=>$d->id_pd,
						// 'id_karyawan'=>$d->id_karyawan,
						'e_karyawan'=>$e_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jum_kar'=>$jum_kar,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tanggal_berangkat'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB',
						'tanggal_sampai'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						'tanggal_pulang'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_pulang,1).' WIB',
						'tujuan'=>$tujuan,
						'asal'=>$d->nama_plant_asal,
						'kendaraan'=>$kendaraan,
						'jarak'=>$d->jarak.' km',
						'plant'=>$d->plant,
						'menginap'=>$d->menginap,
						'nama_penginapan'=>$this->otherfunctions->getPenginapan($d->nama_penginapan),
						// 'nominal_bbm'=>(!empty($nominal_bbm)?$this->formatter->getFormatMoneyUser($nominal_bbm):$this->otherfunctions->getMark()),
						// 'nominal_penginapan'=>(!empty($nominal_penginapan)?$this->formatter->getFormatMoneyUser($nominal_penginapan):$this->otherfunctions->getMark()),
						'nominal_bbm'=>(!empty($d->nominal_bbm)?$this->formatter->getFormatMoneyUser($d->nominal_bbm):$this->otherfunctions->getMark()),
						'jumlah_kendaraan'=>(!empty($d->jumlah_kendaraan)?$d->jumlah_kendaraan.' Kendaraan':$this->otherfunctions->getMark()),
						'nominal_per_ken'=>(!empty($d->nominal_bbm)&&!empty($d->jumlah_kendaraan)?$this->formatter->getFormatMoneyUser($d->nominal_bbm/$d->jumlah_kendaraan):$this->otherfunctions->getMark()),
						'nominal_penginapan'=>(!empty($d->nominal_penginapan)?$this->formatter->getFormatMoneyUser($d->nominal_penginapan):$this->otherfunctions->getMark()),
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
						'tugas'=>(!empty($d->tugas)) ? $d->tugas:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'tabel_tunjangan'=>$tabel,
						'e_plant_tujuan'=>$d->plant_tujuan,
						'e_plant_asal'=>$d->plant_asal,
						'e_kendaraan'=>$d->kendaraan,
						'e_lokasi'=>$d->lokasi_tujuan,
						'e_jarak'=>$d->jarak,
						'e_kendaraan_umum'=>$d->nama_kendaraan,
						'e_nama_penginapan'=>$d->nama_penginapan,
						'jumlah'=>$jum_kar,
						'jumlah_kendaraan_edit'=>$d->jumlah_kendaraan,
						// 'nominal_bbm_all'=>$search['nominal_bbm'],
						// 'nominal_tunjangan_all'=>$search['nominal_penginapan'],
						// 'nama_kendaraan_all'=>isset($nama_kendaraan_all)?$nama_kendaraan_all[0]:$this->otherfunctions->getMark(),
						// 'nama_tunjangan_all'=>isset($nama_kendaraan_all)?$nama_penginapan_all[0]:$this->otherfunctions->getMark(),
						// 'e_tunjangan'=>$e_tunjangan,
						'e_tanggal_mulai'=>$this->formatter->getDateTimeFormatUser($d->tgl_berangkat),
						'e_tanggal_selesai'=>$this->formatter->getDateTimeFormatUser($d->tgl_sampai),
						'e_tanggal_pulang'=>$this->formatter->getDateTimeFormatUser($d->tgl_pulang),
						'e_tugas'=>$d->tugas,
						'e_keterangan'=>$d->keterangan,
						'e_mengetahui'=>$d->mengetahui,
						'e_menyetujui'=>$d->menyetujui,
						'e_dibuat'=>$d->dibuat,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'cek_jumlah_karyawan') {
				$karyawan = $this->input->post('kary');
				$kary=($karyawan==null || $karyawan == 0)?null:count($karyawan);
				$data = ['val'=>$kary];
        		echo json_encode($data);
			}elseif ($usage == 'diberikan_karyawan') {
				$karyawan = $this->input->post('kary');
				$data=$this->model_karyawan->getEmployeeForSelect2ID($karyawan);
        		echo json_encode($data);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePerjalananDinas();
        		echo json_encode($data);
			}elseif ($usage == 'pilihtunjangan') {
				$data = $this->model_karyawan->pilihTunjanganSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'refreshtunjangan') {
				$data = $this->model_karyawan->refreshTunjangan();
        		echo json_encode($data);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'nama_bagian') {
				$data = $this->model_master->getBagianForSelect2();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function change_status_perjalanan_dinas()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('no_sk');
		$val_db=$this->input->post('validasi_db');
		$vali=$this->input->post('validasi');
		$data=['validasi_ac'=>$vali];
		$where=['no_sk'=>$id,'validasi_ac'=>$val_db];
		$datax=$this->model_global->updateQuery($data,'data_perjalanan_dinas',$where);
		echo json_encode($datax);
	}
	//========================================= DATA PESAN USER ============================================================
	public function data_pesan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		$id_kar=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListPesanFO($id_kar);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$tgl='<label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fas fa-pen fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->create_date).' WIB</label>';
					$delete = '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_pesan.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_pesan.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$no,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->nama_jenis,
						$d->judul,
						$this->otherfunctions->getStatusPesanKey($d->status),
						$tgl,
						$info.$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pesan');
				$data=$this->model_karyawan->getPesanFOID($id);
				foreach ($data as $d) {
					$dia=$this->otherfunctions->getDataExplode($d->id_for,';','all');
					$adm=[];
					$id_admin=[];
					foreach($dia as $dd => $ee){
						$adm[]=$this->model_admin->getAdminRowArray($ee)['nama'].'<br>';
						$id_admin[]=$this->model_admin->getAdminRowArray($ee)['id_admin'];
					}
					$datax=[
						'id'=>$d->id_pesan,
						'id_karyawan'=>$d->id_karyawan,
						'nama_karyawan'=>$d->nama_karyawan,
						'nik_karyawan'=>$d->nik_karyawan,
						'nama_jenis'=>$d->nama_jenis,
						'judul'=>$d->judul,
						'status'=>$this->otherfunctions->getStatusPesanKey($d->status),
						'e_status'=>$d->status,
						'e_jenis_pesan'=>$d->jenis,
						'e_admin'=>$id_admin,
						'isi'=>$d->isi,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'ditujukan'=>$adm,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'nama_admin') {
				$data = $this->model_admin->getAdminForSelect2();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	} 
	function add_pesan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_karyawan=$this->input->post('id_karyawan');
		if ($id_karyawan != "") {
			$data=['judul'		=> $this->input->post('judul'),
					'jenis'		=> $this->input->post('jenis_pesan'),
					'isi'		=> $this->input->post('isi'),
					'id_karyawan'=> $id_karyawan,
					'create_date' => $this->date,
				];
			$admx=$this->input->post('admin');
			$all_adm=$this->input->post('all_adm');
			if ($all_adm==1) {
				$adm=$this->model_admin->getAdminAllActive(true);
				foreach ($adm as $ad) {
					$op5[]=$ad->id_admin;
				}
				$data['id_for']=implode(';', $op5);
			}else{
				$data['id_for']=implode(';', $admx);
			}
			$datax = $this->model_global->insertQuery($data,'data_pesan');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_pesan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$admx=$this->input->post('admin');
			$data=[
				'id_karyawan'=>$this->input->post('id_karyawan'),
				'judul'=>$this->input->post('judul'),
				'jenis'=>$this->input->post('jenis'),
				'isi'=>$this->input->post('isi'),
			];
			$data['id_for']=implode(';', $admx);
			$datax = $this->model_global->updateQuery($data,'data_pesan',['id_pesan'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
// ============================================== CHANGE SKIN ========================================================
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
			$datax = $this->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$id]);
		}
		echo json_encode($datax);
	}
	// function edt_pribadi(){
	// 	$d=date('d/m/Y',strtotime($this->input->post('tanggal_lahir')));
	// 	$data=array(
	// 		'no_ktp'=>strtoupper($this->input->post('no_ktp')),
	// 		'nama'=>strtoupper($this->input->post('nama')),
	// 		'tempat_lahir'=>strtoupper($this->input->post('tempat_lahir')),
	// 		'tgl_lahir'=>date('Y-m-d',strtotime($d)),
	// 		'alamat_asal_jalan'=>strtoupper($this->input->post('alamat_asal_jalan')),
	// 		'alamat_asal_desa'=>strtoupper($this->input->post('alamat_asal_desa')),
	// 		'alamat_asal_kecamatan'=>strtoupper($this->input->post('alamat_asal_kecamatan')),
	// 		'alamat_asal_kabupaten'=>strtoupper($this->input->post('alamat_asal_kabupaten')),
	// 		'alamat_asal_provinsi'=>strtoupper($this->input->post('alamat_asal_provinsi')),
	// 		'alamat_asal_pos'=>strtoupper($this->input->post('alamat_asal_pos')),
	// 		'alamat_sekarang_jalan'=>strtoupper($this->input->post('alamat_sekarang_jalan')),
	// 		'alamat_sekarang_desa'=>strtoupper($this->input->post('alamat_sekarang_desa')),
	// 		'alamat_sekarang_kecamatan'=>strtoupper($this->input->post('alamat_sekarang_kecamatan')),
	// 		'alamat_sekarang_kabupaten'=>strtoupper($this->input->post('alamat_sekarang_kabupaten')),
	// 		'alamat_sekarang_provinsi'=>strtoupper($this->input->post('alamat_sekarang_provinsi')),
	// 		'alamat_sekarang_pos'=>strtoupper($this->input->post('alamat_sekarang_pos')),
	// 		'agama'=>$this->input->post('agama'),
	// 		'kelamin'=>$this->input->post('kelamin'),
	// 		'no_hp'=>$this->input->post('no_hp'),
	// 		'gol_darah'=>strtoupper($this->input->post('gol_darah')),
	// 		'tinggi_badan'=>strtoupper($this->input->post('tinggi')),
	// 		'berat_badan'=>strtoupper($this->input->post('berat')),
	// 	);
	// 	$em=$this->input->post('email');
	// 	if ($em != $this->dtroot['email']) {
	// 		$dt1=array('email'=>$em,'email_verified'=>'0');
	// 		$this->db->where('id_karyawan',$this->admin);
	// 		$this->db->update('karyawan',$dt1);
	// 	}
	// 	$this->db->where('id_karyawan',$this->admin);
	// 	$in=$this->db->update('karyawan',$data);
	// 	if ($in) {
	// 		$this->session->set_flashdata('up_pribadi_sc','<label><i class="fa fa-check-circle"></i> Update Data Berhasil</label><hr class="message-inner-separator">Update Data Pribadi berhasil disimpan ke database'); 
	// 	}else{
	// 		$this->session->set_flashdata('up_pribadi_err','<label><i class="fa fa-times-circle"></i> Update Data Gagal</label><hr class="message-inner-separator">Update Data Pribadi gagal disimpan ke database'); 
	// 	}
	// 	redirect('kpages/profile');
	// }
	// function edt_family(){
	// 	$data=array(
	// 		'ayah_kandung'=>strtoupper($this->input->post('ayah_kandung')),
	// 		'ibu_kandung'=>strtoupper($this->input->post('ibu_kandung')),
	// 		'status_nikah'=>$this->input->post('status_nikah'),
	// 	);
	// 	if ($data['status_nikah'] == 'Menikah') {
	// 		$data['nama_pasangan']=strtoupper($this->input->post('nama_pasangan'));
	// 		$data['jumlah_anak']=$this->input->post('jumlah_anak');
	// 		for ($i=1; $i <=3 ; $i++) { 
	// 			$tt='ttl_anak_'.$i;
	// 			$nm='nama_anak_'.$i;
	// 			if (strtoupper($this->input->post($nm)) != "" && $this->input->post($tt) != "") {
	// 				$dat=date('d/m/Y',strtotime($this->input->post($tt)));;
	// 				$data['anak_'.$i]=strtoupper($this->input->post($nm));
	// 				$data[$tt]=date('Y-m-d',strtotime($dat));
	// 			}
	// 		}
	// 	}else{
	// 		$data['nama_pasangan']=NULL;
	// 		$data['jumlah_anak']=NULL;
	// 		for ($i=1; $i <=3 ; $i++) { 
	// 			$data['anak_'.$i]=NULL;
	// 			$data['ttl_anak_'.$i]=NULL;
	// 		}
	// 	}
		
	// 	$this->db->where('id_karyawan',$this->admin);
	// 	$in=$this->db->update('karyawan',$data);
	// 	if ($in) {
	// 		$this->session->set_flashdata('up_pribadi_sc','<label><i class="fa fa-check-circle"></i> Update Data Berhasil</label><hr class="message-inner-separator">Update Data Keluarga berhasil disimpan ke database'); 
	// 	}else{
	// 		$this->session->set_flashdata('up_pribadi_err','<label><i class="fa fa-times-circle"></i> Update Data Gagal</label><hr class="message-inner-separator">Update Data Keluarga gagal disimpan ke database'); 
	// 	}
	// 	redirect('kpages/profile');
	// }
	// function edt_numb(){
	// 	$data=array(
	// 		'no_hp_ayah'=>$this->input->post('no_hp_ayah'),
	// 		'no_hp_ibu'=>$this->input->post('no_hp_ibu'),
	// 		'no_hp_pasangan'=>$this->input->post('no_hp_pasangan'),
	// 		'hub_orang_lain'=>strtoupper($this->input->post('orang_lain')),
	// 		'status_pajak'=>strtoupper($this->input->post('status_pajak')),
	// 		'no_hp_orang_lain'=>$this->input->post('no_hp_orang_lain'),
	// 		'rekening'=>$this->input->post('rekening'),
	// 		'npwp'=>$this->input->post('npwp'),
	// 		'bpjstk'=>$this->input->post('bpjstk'),
	// 		'bpjskes'=>$this->input->post('bpjskes'),
	// 	);
	// 	$this->db->where('id_karyawan',$this->admin);
	// 	$in=$this->db->update('karyawan',$data);
	// 	if ($in) {
	// 		$this->session->set_flashdata('up_pribadi_sc','<label><i class="fa fa-check-circle"></i> Update Data Berhasil</label><hr class="message-inner-separator">Update Data Nomor Penting berhasil disimpan ke database'); 
	// 	}else{
	// 		$this->session->set_flashdata('up_pribadi_err','<label><i class="fa fa-times-circle"></i> Update Data Gagal</label><hr class="message-inner-separator">Update Data Nomor Penting gagal disimpan ke database'); 
	// 	}
	// 	redirect('kpages/profile');
	// }
	// function edt_edua(){
	// 	$data=array(
	// 		'no_hp_ayah'=>$this->input->post('no_hp_ayah'),
	// 		'no_hp_ibu'=>$this->input->post('no_hp_ibu'),
	// 		'no_hp_pasangan'=>$this->input->post('no_hp_pasangan'),
	// 		'hub_orang_lain'=>strtoupper($this->input->post('orang_lain')),
	// 		'status_pajak'=>strtoupper($this->input->post('status_pajak')),
	// 		'no_hp_orang_lain'=>$this->input->post('no_hp_orang_lain'),
	// 		'rekening'=>$this->input->post('rekening'),
	// 		'npwp'=>$this->input->post('npwp'),
	// 		'bpjstk'=>$this->input->post('bpjstk'),
	// 		'bpjskes'=>$this->input->post('bpjskes'),
	// 	);
	// 	$this->db->where('id_karyawan',$this->admin);
	// 	$in=$this->db->update('karyawan',$data);
	// 	if ($in) {
	// 		$this->session->set_flashdata('up_pribadi_sc','<label><i class="fa fa-check-circle"></i> Update Data Berhasil</label><hr class="message-inner-separator">Update Data Nomor Penting berhasil disimpan ke database'); 
	// 	}else{
	// 		$this->session->set_flashdata('up_pribadi_err','<label><i class="fa fa-times-circle"></i> Update Data Gagal</label><hr class="message-inner-separator">Update Data Nomor Penting gagal disimpan ke database'); 
	// 	}
	// 	redirect('kpages/profile');
	// }
}