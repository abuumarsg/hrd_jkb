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

class Krekap extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
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
		$dtroot['admin']=$this->model_karyawan->getEmployeeId($this->admin);
		$nm=explode(" ", $dtroot['admin']['nama']);
		$datax['adm'] = array(
			'nama'=>$nm[0],
			'nik'=>$dtroot['admin']['nik'],
			'id_karyawan'=>$dtroot['admin']['id_karyawan'],
			'email'=>$dtroot['admin']['email'],
			'kelamin'=>$dtroot['admin']['kelamin'],
			'jabatan'=>$dtroot['admin']['jabatan'],
			'foto'=>$dtroot['admin']['foto'],
			'create'=>$dtroot['admin']['create_date'],
			'update'=>$dtroot['admin']['update_date'],
			'login'=>$dtroot['admin']['last_login'],
			'masuk'=>$dtroot['admin']['tgl_masuk'],
		);
		$this->dtroot=$datax;			
		$this->max_range=$this->otherfunctions->poin_max_range();
		$this->max_month=$this->otherfunctions->column_value_max_range();
		$this->dir=$_SERVER['DOCUMENT_ROOT'].'/asset/img/loo.png';
	}
	public function index(){
		redirect('pages/dashboard');
	}
	public function exportAspekSikap()
	{
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		// $id_log = $this->admin;
		// $getJabatan = $this->model_karyawan->getEmployeeId($id_log);
		// $kode_jabatan = $getJabatan['jabatan'];
		// $posisi  = ["ATS","BWH","RKN","DRI"];
		// $max_for = 4;
		$id=$this->admin;
		$getAgenda=$this->model_agenda->getAgendaSikapKode($kode);
		$tabel=$getAgenda['nama_tabel'];
		$data=$this->model_agenda->openTableAgenda($tabel);
		$pack=[];
		// echo '<pre>';
		foreach ($data as $d) {
			$pack[$d->id_karyawan][$d->id_task][]=$this->exam->getPartisipantKode($d->partisipan);
		}
		// $data_pick=[];
		$datax=[];
		if (isset($pack)) {
			foreach ($pack as $k_idpar => $v_idpar) {
				foreach ($v_idpar as $k_idtask => $v_idtask) {
					foreach ($v_idtask as $key => $value) {
						if ($value){
							foreach ($value as $k => $v) {
								if($k == $id){
									// $data_pick[$k_idpar][$v][]=$k_idtask;
									$datax[$v][]=$this->model_agenda->openTableAgenda($tabel,null,['a.id_task'=>$k_idtask]);
								}
							}
						}
					}
				}
			}
		}
		$data['properties']=[
			'title'=>"Template Import Aspek Sikap",
			'subject'=>"Template Import Aspek Sikap",
			'description'=>"Template Import Aspek Sikap,Template Import Aspek Sikap Karyawan",
			'keywords'=>"Template Import Aspek Sikap, Aspek Sikap",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		foreach ($datax as $k=>$sbg) {
			foreach ($sbg as $sebagai=>$dx) {
				foreach ($dx as $d) {
					$namaAspekSikap = $this->model_master->getAspekKode($d->kode_aspek)['nama'];
					$body[$row_body]=[
						($row_body-1).'.',$d->id_task, $getAgenda['nama'].' Tahun '.$getAgenda['tahun'].' '.$getAgenda['nama_periode'], $d->nik, $d->nama,$d->nama_jabatan,$d->bagian,$namaAspekSikap,$d->kuisioner,$this->exam->getWhatIsPartisipan($k,'rekap'),null,null
					];
					$row_body++;
				}
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Import Aspek Sikap',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No.','ID TASK'."\n".'(JANGAN DI UBAH)','Nama Agenda','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','Nama Aspek Sikap','KUISIONER (Memberikan Nilai Harus Rentang 1-5 dan Tidak Boleh Angka 3)','Anda Sebagai','NILAI','Keterangan',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	function import_aspek_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$getAgenda=$this->model_agenda->getAgendaSikapKode($kode);
		$tabel=$getAgenda['nama_tabel'];
		$data['properties']=[
			'post'=>'file',
			'data_post'=>$this->input->post('file', TRUE),
		];
		$sheet[0]=[
			'range_huruf'=>3,
			'row'=>2,
			'table'=>$tabel,
			'column_code'=>'id_task',
			'usage'=>'aspek_sikap',
			'column_proerties'=>$this->model_global->getCreateProperties($this->admin),
			'id_admin'=>$this->admin,
			'column'=>[
				1=>'id_task',3=>'nik',10=>'nilai',11=>'keterangan',
			],
		];
		$data['data']=$sheet;
		$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
}
