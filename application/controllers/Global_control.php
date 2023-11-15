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
	
class Global_control extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		if (!$this->session->has_userdata('adm') && !$this->session->has_userdata('emp')) {
			redirect('not_found'); 
		}
		if (isset($_SESSION['adm'])) {
			$this->admin = $_SESSION['adm']['id'];	
		}
	}
	public function index(){
		redirect('not_found');
	}
//update select option
	public function select2_global() 
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$table=$this->input->post('table');
		$column=$this->input->post('column');
		$name=$this->input->post('name');
		$sort=$this->input->post('sort');
		$s_val=$this->input->post('s_val');
		if (empty($table) || empty($column) || empty($name))
			echo json_encode($this->messages->notValidParam());
		$datax=$this->model_global->listActiveRecord($table,$column,$name,$sort,$s_val);
		echo json_encode($datax);
	}
	// public function select2_global() 
	// {
	// 	if (!$this->input->is_ajax_request()) 
	// 		redirect('not_found');
	// 	$table=$this->input->post('table');
	// 	$column=$this->input->post('column');
	// 	$name=$this->input->post('name');
	// 	if (empty($table) || empty($column) || empty($name))
	// 		echo json_encode($this->messages->notValidParam());
	// 	$datax=$this->model_global->listActiveRecord($table,$column,$name);
	// 	echo json_encode($datax);
	// }
	public function change_status()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$table=$this->input->post('table');
		$data=$this->input->post('data');
		$where=$this->input->post('where');
		
		if (empty($table) || empty($data) || empty($where))
			echo json_encode($this->messages->notValidParam());
		$datax=$this->model_global->updateQuery($data,$table,$where);
		echo json_encode($datax);
	}
	public function change_status2()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$tahun=$this->input->post('tahun');
		$table=$this->input->post('table');
		$data=$this->input->post('data');
		$where=$this->input->post('where');
		$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		if (empty($table) || empty($data) || empty($where))
			echo json_encode($this->messages->notValidParam());
		$datax=$this->model_global->updateQuery($data,$table,$where);
		$this->otherfunctions->insertToHistoryResetCuti('UPDATE', 'Data Berhasil Di Update', $this->date, $tahun);
		echo json_encode($datax);
	}
	public function delete()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$table=$this->input->post('table');
		$column=$this->input->post('column');
		$id=$this->input->post('id');
		$table2=$this->input->post('table2');
		$column2=$this->input->post('column2');
		$id2=$this->input->post('id2');
		$drop_table=$this->input->post('table_drop');
		$link_table=$this->input->post('link_table');
		$link_col=$this->input->post('link_col');
		$link_data_col=$this->input->post('link_data_col');
		$file=$this->input->post('file');
		if (empty($table) || empty($column) || empty($id))
			echo json_encode($this->messages->notValidParam());
		if (isset($drop_table)) {
			$this->model_global->dropTable($drop_table);
		}
		if (!empty($link_table) && !empty($link_col) && !empty($link_data_col)) {
			$wh=[$link_col=>$link_data_col];
			$this->model_global->deleteQueryNoMsg($link_table,$wh);
		}
		if(!empty($file)){
			unlink($file);
		}
		if (!empty($table2) && !empty($column2) && !empty($id2)){
			$where2=[$column2=>$id2];
			$this->model_global->deleteQuery($table2,$where2);
		}
		$where=[$column=>$id];
		$datax=$this->model_global->deleteQuery($table,$where);
		echo json_encode($datax);
	}
	public function select2_custom()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		if (empty($usage) && empty($table))
			echo json_encode($this->messages->notValidParam());
		if ($usage == 'master_periode_penilaian') {
			$datax=$this->model_master->getListPeriodePenilaianActive();
		}else if ($usage == 'get_agenda_kpi') {
			$kpi=$this->model_agenda->getListAgendaKpi();
			$datax=[];
			foreach ($kpi as $k) {
				$datax[$k->nama_tabel]=$k->nama.' ('.$k->nama_periode.' - '.$k->tahun.')';
			}
		}else{			
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function file_download()
	{
		if (empty($this->uri->segment(3))) {
			redirect('not_found');
		}
		$file=$this->codegenerator->decryptChar($this->uri->segment(3));
		// print_r($file);
		$do=$this->filehandler->doDownload($file);
		if (!$do) {
			redirect('not_found');
		}
	}
	//sistem needs

	public function get_notif_list()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$datax['data']=[];
		$param=$this->codegenerator->decryptChar($this->input->post('param'));
		if (isset($param['param']) && isset($param['id'])) {
			$datax['data_return']='';
			$data=$this->model_master->getNotification($param['id'],$param['param']);
			if (isset($data)) {
				foreach ($data as $d) {
					$read=$this->model_master->getNotifRead($param['id'],$param['param'],$d->id_notif,'all');
					$datax['data'][]=[
						'<input type="checkbox" name="data_check" onchange="checkvalue()" value="'.$d->id_notif.'" class="data_checked">',
						(($read)?'<i class="far fa-circle" style="font-size:8pt"></i>':'<i class="fa fa-circle" style="color:red;font-size:8pt"></i>'),
						(($read)?'':((date('Y-m-d',strtotime($d->start)) < date('Y-m-d',strtotime($this->otherfunctions->getDateNow())))?'':'<label class="label label-default" style="border-radius:3px">NEW<label>')),
						'<a href="'.base_url('pages/read_notification/'.$this->codegenerator->encryptChar($d->kode_notif)).'">'.(($read)?$d->nama_buat:'<b>'.$d->nama_buat.'</b>').'</a>',
						'<b>'.$d->judul.'</b> - '.$this->formatter->cutCharReadMore($d->isi),
						($d->file_notif)?'<i class="fa fa-paperclip"></i>':'',
						$d->start,
					];
				}
			}
		}
		echo json_encode($datax);
	}
	public function delete_many_notification()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('data_hide');
		$param=$this->codegenerator->decryptChar($this->input->post('param'));
		if (!empty($id)) {
			$id=explode(',',$id);
			if (isset($id)) {
				foreach ($id as $i) {
					$data_notif=$this->model_master->getNotif($i);
					if (isset($data_notif)) {
						$data_notif=$this->otherfunctions->convertResultToRowArray($data_notif);
						$id_del_old=$data_notif['id_del'];
						$id_new=$this->exam->addEditValueOneLevelDb($param['id'],$id_del_old);
						$data=[
							'id_del'=>$id_new,
						];
						$this->model_global->updateQueryNoMsg($data,'notification',['id_notif'=>$i]);
					}
					
				}
			}
			$datax=$this->messages->allGood();
		}else{
			$datax=$this->messages->customFailure('Anda tidak memilih apapun');
		}
		echo json_encode($datax);
	}
	public function read_notification()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$param=$this->codegenerator->decryptChar($this->input->post('param'));
		$data=$this->model_master->getNotifRead($param['idu'],$param['param'],$param['id'],'one');
		$datax=[];
		if (isset($data)) {
			$id_read_old=$data['id_read'];
			$id_new=$this->exam->addEditValueOneLevelDb($param['idu'],$id_read_old);
			$dataz=['id_read'=>$id_new];
			$this->model_global->updateQueryNoMsg($dataz,'notification',['id_notif'=>$param['id']]);
			$sifat='';
			if ($data['sifat']) {
				$sifat='<label class="label label-primary bg-maroon"><i class="fa fa-info"></i> Penting</label>';
			}
			$tipe='';
			if ($data['tipe'] == 'danger') {
				$tipe='<span style="color:red"><i class="fa fa-times-circle"></i> Larangan</span>';
			}elseif ($data['tipe'] == 'warning') {
				$tipe='<span style="color:#ff9000"><i class="fa fa-warning"></i> Peringatan</span>';
			}else{
				$tipe='<span class="text-primary"><i class="fa fa-info-circle"></i> Informasi Pemberitahuan</span>';
			}
			$attc='<li style="border: none;"><small class="text-muted">No Attachment</small></li>';
			if ($data['file_notif']) {
				$icon='fa fa-file-pdf-o';
				if (strpos($data['file_notif'], '.xls') || strpos($data['file_notif'], '.xlsx')) {
					$icon='fa fa-file-excel-o';
				}elseif (strpos($data['file_notif'], '.docx') || strpos($data['file_notif'], '.docx')) {
					$icon='fa fa-file-word-o';
				}elseif (strpos($data['file_notif'], '.jpg') || strpos($data['file_notif'], '.png') || strpos($data['file_notif'], '.jpeg')) {
					$icon='fa fa-file-image-o';
				}
				$attc='<li>
				<span class="mailbox-attachment-icon"><i class="'.$icon.'"></i></span>

				<div class="mailbox-attachment-info">
				<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($data['file_notif'])).'" style="color:black" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> '.str_replace('_','',(str_replace('asset/upload-attachment/','',$data['file_notif']))).'</a>
				<span class="mailbox-attachment-size">
				File Attachment
				<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($data['file_notif'])).'" class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
				</span>
				</div>
				</li>';
			}
			$datax=[
				'id'=>$data['id_notif'],
				'judul'=>$data['judul'],
				'isi'=>$data['isi'],
				'dari'=>$data['nama_buat'],
				'file'=>$data['file_notif'],
				'create'=>$this->formatter->getFullDateTimeUser($data['start']),
				'sifat'=>$sifat,
				'tipe'=>$tipe,
				'attc'=>$attc,
			];
		}
		echo json_encode($datax);
	}

	public function encryptChar()
	{
		$val = $this->input->post('val');
		$new_val = $this->codegenerator->encryptChar($val);
		echo json_encode($new_val);
	}

	public function decryptChar()
	{
		$val = $this->input->post('val');
		$new_val = $this->codegenerator->decryptChar($val);
		echo json_encode($new_val);
	}
	public function get_status_sync()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$ret=false;
		$stat=$this->db->get_where('general_settings',['kode'=>'SYNC'])->row_array();
		if ($stat) {
			if ($stat['value_bool'] == 1) {
				$ret='<div class="callout callout-danger"><b><i class="fa fa-sync fast-spin"></i> Data Log Mesin Sedang Disinkronisasi Otomatis</b></div>';
			}
		}
		echo json_encode($ret);
	}
	public function get_value_sync()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$ret=false;
		$stat=$this->db->get_where('general_settings',['kode'=>'SYNC'])->row_array();
		if ($stat) {
			if ($stat['value_bool'] == 1) {
				$ret=$stat['value_bool'];
			}
		}
		echo json_encode($ret);
	}
}