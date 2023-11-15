<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	/**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Controller Agenda
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

class Agenda extends CI_Controller 
{
	public function __construct()
	{ 
		parent::__construct(); 
		$this->date = $this->otherfunctions->getDateNow();
		$this->max_periode = 4;
		if ($this->session->has_userdata('adm')) {
			$this->admin = $this->session->userdata('adm')['id'];	 
		}else{ 
			redirect('auth');
		}
		$this->max_range=$this->otherfunctions->poin_max_range();
		$this->max_month=$this->otherfunctions->column_value_max_range();
	}
	public function index(){
		redirect('pages/dashboard');
	}
	//===DASHBOARD AGENDA KPI BEGIN===//
	public function dashboard_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$datax['data']=[];
		echo json_encode($datax);
	}
	//===DASHBOARD KPI END===//
	//===AGENDA KPI BEGIN===//
	public function agenda_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getListAgendaKpi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_a_kpi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel);
					$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$count_p.' %" data-placement="right">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$validate = null;
					if (isset($access['l_ac']['apr'])) {
						if ($d->status){
							if ($d->tgl_selesai <= $this->date){
								if ($d->validasi) {
									$validate = (in_array('APR', $access['access'])) ? '<button type="button" class="btn btn-warning btn-sm"  href="javascript:void(0)" onclick=validate_modal('.$var['id'].',0)><i class="fa fa-calendar-check" data-toggle="tooltip" title="Batalkan Validasi Data"></i></button> ' : null;
								}else{
									$validate = (in_array('APR', $access['access'])) ? '<button type="button" class="btn btn-success btn-sm"  href="javascript:void(0)" onclick=validate_modal('.$var['id'].',1)><i class="fa fa-calendar-check" data-toggle="tooltip" title="Validasi Data"></i></button> ' : null;
								}
							}
						}
					}
					$datax['data'][]=[
						$d->id_a_kpi,
						$d->kode_a_kpi.(($d->validasi)?'<br><label class="label label-success"><i class="fa fa-check"></i> Agenda Tervalidasi</label>':null),
						$d->nama,
						$progress,
						$d->nama_konsep,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()).' - '.$d->tahun,
						$tanggal,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$validate,
						$this->codegenerator->encryptChar($d->kode_c_kpi)
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_a_kpi');
				$data=$this->model_agenda->getAgendaKpi($id); 
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_a_kpi,
						'kode_a_kpi'=>$d->kode_a_kpi,
						'nama'=>$d->nama,
						'nama_detail'=>$d->nama.(($d->validasi)?'<br><label class="label label-success"><i class="fa fa-check"></i> Agenda Tervalidasi</label>':null),
						'nama_tabel'=>$d->nama_tabel,
						'nama_konsep'=>'<a href="'.base_url('pages/view_data_konsep_kpi/'.$this->codegenerator->encryptChar($d->kode_c_kpi)).'" target="blank" title="Klik untuk Detail">'.$d->nama_konsep.'</a>',
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'periode'=>$d->periode,
						'periode_view'=>(!empty($d->periode)) ? ((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()) : $this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeAgendaKpi();
				echo json_encode($data);
			}elseif ($usage == 'get_select2') {
				$data = $this->model_agenda->getListAgendaKpi(true);
				$datax=[];
				if (isset($data)) {
					foreach ($data as $d) {
						$datax[$d->nama_tabel]=$d->nama.' ('.((!empty($d->nama_periode))?$d->nama_periode.' - ':null).$d->tahun.')';
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_agenda_kpi()
	{ 
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$kode_c=$this->input->post('konsep');
			$table=$this->exam->getNameTable('agenda_kpi');
			$data=[
				'kode_a_kpi'=>$kode,
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'kode_c_kpi'=>$kode_c,
				'nama_tabel'=>$table,
				'periode'=>$this->input->post('periode'), 
				'tahun'=>$this->input->post('tahun'),
			];
			$data_log=[
				'kode_l_a_kpi'=>$this->codegenerator->kodeLogAgendaKpi(),
				'nama'=>$data['nama'],
				'tgl_mulai'=>$data['tgl_mulai'],
				'tgl_selesai'=>$data['tgl_selesai'],
				'periode'=>$data['periode'],
				'tahun'=>$data['tahun'],
				'kode_c_kpi'=>$data['kode_c_kpi'],
				'kode_a_kpi'=>$kode,
				'nama_tabel'=>$data['nama_tabel'],				
			];
			$cek_avl=$this->model_agenda->checkAgendaAvailable('agenda_kpi',['periode'=>$data['periode'],'tahun'=>$data['tahun']]);
			if ($cek_avl['agenda'] > 0 || $cek_avl['log_agenda'] > 0){
				$datax=$this->messages->customFailure('Agenda Sudah Ada');
			}else{
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$data_log=array_merge($data_log,$this->model_global->getCreateProperties($this->admin));
				$gen=$this->model_agenda->generateAgendaKpi($kode_c,$table);
				if ($gen) {
					$this->model_global->insertQueryCCNoMsg($data_log,'log_agenda_kpi',$this->model_agenda->checkLogAgendaKpiCode($kode));
					$datax = $this->model_global->insertQueryCC($data,'agenda_kpi',$this->model_agenda->checkAgendaKpiCode($kode));
					$dataNotif =[
						'judul'=>$data['nama'],
						'start'=>$data['tgl_mulai'],
						'end_date'=>$data['tgl_selesai'],
						'admin'=>$this->admin,
						'jenis'=>'Agenda KPI',
						'link'=>base_url('kpages/input_employee_tasks/'.$this->codegenerator->encryptChar($kode)),
					];
					$this->otherfunctions->generateNotifikasi($dataNotif);
				}else{
					$datax=$this->messages->customFailure('Generate Tabel Gagal, Mohon Kontak Administrator!');
				}	
			}		
		}
		echo json_encode($datax);
	}
	public function edt_agenda_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$data=[
				'kode_a_kpi'=>$this->input->post('kode'),
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'periode'=>$this->input->post('periode'),
				'tahun'=>$this->input->post('tahun'),
			];
			$data_log=[
				'nama'=>$data['nama'],
				'tgl_mulai'=>$data['tgl_mulai'],
				'tgl_selesai'=>$data['tgl_selesai'],
				'periode'=>$data['periode'],
				'tahun'=>$data['tahun'],
			];
			$cek_avl=$this->model_agenda->checkAgendaAvailable('agenda_kpi',['periode'=>$data['periode'],'tahun'=>$data['tahun']]);
			if (($cek_avl['agenda'] > 0 || $cek_avl['log_agenda'] > 0) && (($data['periode'] != $this->input->post('periode_old') && $data['tahun'] != $this->input->post('tahun_old')) || ($data['periode'] != $this->input->post('periode_old') && $data['tahun'] == $this->input->post('tahun_old')) || ($data['periode'] == $this->input->post('periode_old') && $data['tahun'] != $this->input->post('tahun_old')))){
				$datax=$this->messages->customFailure('Agenda Sudah Ada');
			}else{
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$data_log=array_merge($data_log,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($data_log,'log_agenda_kpi',['kode_a_kpi'=>$data['kode_a_kpi']]);
				//cek data
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_a_kpi']) {
					$cek=$this->model_master->checkAgendaKpiCode($data['kode_a_kpi']);
				}else{
					$cek=false;
				}
				$datax = $this->model_global->updateQueryCC($data,'agenda_kpi',['id_a_kpi'=>$id],$cek);
			}
		}
		echo json_encode($datax);
	}
	public function val_agenda_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$data=[
				'validasi'=>$this->input->post('status'),
			];
			$dt_agenda=$this->model_agenda->getAgendaKpiKode($kode);
			if (isset($dt_agenda)){
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$data_log=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($data_log,'log_agenda_kpi',['kode_a_kpi'=>$kode]);
				if($data['validasi']){
					$this->model_agenda->syncPoinEmployee(['kode_periode'=>$dt_agenda['periode'],'tahun'=>$dt_agenda['tahun']]);
					$this->model_agenda->syncBobotTertimbangKpi($dt_agenda['nama_tabel'],$data['validasi']);
				}
				$datax = $this->model_global->updateQuery($data,'agenda_kpi',['kode_a_kpi'=>$kode]);
			}else{
				$datax=$this->messages->customFailure('Agenda Tidak Ditemukan');
			}
		}
		echo json_encode($datax);
	}
	public function stt_agenda_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data=$this->input->post('data');
		$where=$this->input->post('where');
		
		if (empty($data) || empty($where))
			echo json_encode($this->messages->notValidParam());
		$dt_agenda=$this->model_agenda->getAgendaKpi($where['id_a_kpi']);
		if(isset($dt_agenda[0])){
			$this->model_global->updateQueryNoMsg($data,'log_agenda_kpi',['kode_a_kpi'=>$dt_agenda[0]->kode_a_kpi]);
			$datax=$this->model_global->updateQuery($data,'agenda_kpi',['kode_a_kpi'=>$dt_agenda[0]->kode_a_kpi]);
		}else{
			$datax=$this->messages->customFailure('Agenda Tidak Ditemukan');
		}
		echo json_encode($datax);
	}
	//--------------------- EXAM KPI BEGIN ---------------------//
	public function data_input_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getLogAgenda('log_agenda_kpi');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$tgl = $this->date;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_l_a_kpi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel);
					$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$count_p.' %" data-placement="right">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					</div>
					</div>';
					$keterangan = '';
					if ($count_p == 0) {
						$keterangan .= '<label class="label label-danger">Belum Ada Data</label>';
					}elseif ($count_p > 0 && $count_p < 100) {
						$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
					}else{
						$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
					}
					if ($keterangan != ''){
						$keterangan.='<br>';
					}
					if ($d->tgl_selesai < $this->otherfunctions->getDateNow('Y-m-d')) {
						$keterangan.='<label class="label label-danger">Waktu Agenda Sudah Habis</label>';
					}
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_l_a_kpi,
						$d->nama,
						$progress,
						$tanggal,
						$keterangan,
						$this->codegenerator->encryptChar($d->kode_a_kpi),
						$d->tahun,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()),
						$this->codegenerator->encryptChar($d->kode_c_kpi),
					];
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_employee_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		$mode=$this->uri->segment(5);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$getPeriode = $this->model_agenda->getAgendaKpiKode($kode);
				$periode_name=(isset($getPeriode['nama_periode']) && isset($getPeriode['tahun']))?$getPeriode['nama_periode'].' - '.$getPeriode['tahun']:'Unknown';
				parse_str($this->input->post('form'), $post_form);
				$getAgenda=$this->model_agenda->getAgendaKpiKode($kode);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
				if (!empty($filter)) {
					$post_form['bagian_filter']=$filter;						
				}					
				$tabel=$getAgenda['nama_tabel'];
				$data=$this->model_agenda->getTabelKpi($tabel, $post_form);
				$no=1;
				$datax['data']=[];
				$data_rekap=[];
				$data_partisipan=[];
				foreach ($data as $d) {
					$kary = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$var=[
						'id'=>$d->id_task,
						'access'=>$access,
					];
					$jml_kpi = $this->model_agenda->getTabelSikapAll($tabel,$d->id_karyawan);
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$data_nilai_kpi=$this->model_agenda->rumusCustomKubotaFinalResultKpi($tabel,$d->id_karyawan,false,true);
					$nilai=(isset($data_nilai_kpi['nilai_akhir']))?$data_nilai_kpi['nilai_akhir']:0;
					$huruf=$this->model_master->getKonversiKpiNilai($nilai);
					$huruf=(isset($huruf['huruf']))?$huruf['huruf']:'Unknown';
					$data_nilai=$this->model_agenda->openTableAgendaIdEmployee($tabel,$d->id_karyawan);
					$all=0;
					$avl=0;
					$max_month=$this->max_month;
					if (isset($data_nilai)) {
						foreach ($data_nilai as $dn) {
							$penilai=[];
							if ($dn->kode_penilai == 'P3' && !empty($dn->penilai)) {
								$penilai=$this->otherfunctions->getParseOneLevelVar($dn->penilai);
								if(!empty($penilai)){
									$all=$all+($max_month*count($penilai));
								}								
							}else{
								$all=$all+$max_month;
							}
							$c_nilai=0;
							for ($i=1; $i <= $max_month ; $i++) { 
								$col_pn='pn'.$i;
								$nilai_plain=$this->otherfunctions->getParseVar($dn->$col_pn);
								if (isset($nilai_plain)) {
									foreach ($nilai_plain as $idp => $nl) {
										if (in_array($idp,$penilai)) {
											$avl=$avl+1;
										}
									}
									$c_nilai=$c_nilai+count($nilai_plain);
								}
							}
							if (count($penilai) == 0) {
								$avl=$avl+$c_nilai;
							}
						}
					}
					$progress = $this->rumus->rumus_prosentase($avl,$all);				
					$progress_view = '<div class="progress active" title="'.$this->formatter->getNumberFloat($progress).'%" data-toggle="tooltip" data-placement="left" style="background:grey;">
						<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%;"></div></div>';	
					$arr_start=[
						$d->id_task,
						$d->nik,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_bagian,
						$d->nama_departement,           
						$d->nama_loker, 						
						count($jml_kpi),
					];
					$arr_month=[];
					for ($i=1; $i <= $max_month ; $i++) { 
						if (isset($data_nilai_kpi['nilai_bulan'][$i])) {
							$arr_month[$i]=$this->formatter->getNumberFloat($data_nilai_kpi['nilai_bulan'][$i]);
						}else{
							$arr_month[$i]=0;
						}
					}
					$arr_end=[
						$this->formatter->getNumberFloat($nilai),
						$huruf,
						$progress_view,
						$this->codegenerator->encryptChar($kode),
						$this->codegenerator->encryptChar($d->id_karyawan),						
					];
					$datax['data'][]=array_merge($arr_start,$arr_month,$arr_end);
					$data_rekap[]=array_merge([
						'nik'=>$d->nik,
						'nama'=>$d->nama_karyawan,
						'jabatan'=>$d->nama_jabatan,
						'bagian'=>$d->nama_bagian,
						'loker'=>$d->nama_loker,     
						'departemen'=>$d->nama_departement,     
						'periode'=>$periode_name,      
						'nilai'=>$this->formatter->getNumberFloat($nilai),
						'huruf'=>$huruf,
					],$arr_month);					
					if($d->kode_penilai == 'P1'){
						$getatasan = $this->model_karyawan->getEmployeeAtasan($d->id_karyawan);
						$id_penilai = (isset($getatasan[0]))?$getatasan[0]:null;
					}else{
						$id_penilai = $d->penilai;
					} 
					for ($p_ke=1; $p_ke <= $this->max_month ; $p_ke++) { 
						$pn = 'pn'.$p_ke;
						$getid = $this->exam->getPartisipanBlmNilai($id_penilai,$d->$pn);
						if(!empty($getid)){
							foreach ($getid as $g) {
								$dataatasan = $this->model_karyawan->getEmployeeId($g);
								$data_partisipan[$d->id_karyawan]=[
									'nik'=>$dataatasan['nik'],
									'nama'=>$dataatasan['nama'],
									'jabatan'=>$dataatasan['nama_jabatan'],
									'bagian'=>$dataatasan['bagian'],
									'loker'=>$dataatasan['nama_loker'],
									'departemen'=>$dataatasan['nama_departement'],
									'nama_dinilai'=>$d->nama_karyawan,
									'jabatan_dinilai'=>$d->nama_jabatan,
								];
							}
						}
					}
					
					$no++;
				}
				if(!empty($mode)){
					$send_data =['rekap'=>$data_rekap,'periode'=>$this->codegenerator->encryptChar($kode)];
					$partisipan_param =['data'=>$data_partisipan,'periode'=>$this->codegenerator->encryptChar($kode)];
					$datax['rekap_nilai']=(isset($data_rekap))?$this->codegenerator->encryptChar($send_data):null;
					$datax['rekap_partisipan']=(isset($data_partisipan))?$this->codegenerator->encryptChar($partisipan_param):null;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_kpi_input()
	{ 
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		$id_kar=$this->codegenerator->decryptChar($this->uri->segment(5));
		$getAgenda=$this->model_agenda->getAgendaKpiKode($kode);
		$id_log = $this->admin;
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->openTableAgendaIdEmployee($getAgenda['nama_tabel'],$id_kar);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=0;
				$max_for=$this->max_month;
				$datax['data']=[];
				foreach ($data as $d) {
					$avl=false;
					$parseId = $this->otherfunctions->getParseOneLevelVar($d->penilai);
					$atasan = $this->model_master->getAtasan($d->kode_jabatan);
					if($d->kode_penilai=='P2'){
						$avl = true;
					}
					if ($avl){
						for ($i_pn=1; $i_pn <= $this->max_month ; $i_pn++) { 
							$col_pn='pn'.$i_pn;
							$pn[$i_pn] = $d->$col_pn;
						}
						$cek_val = 0;
						for ($i=0; $i < $max_for; $i++) { 
							$range_poin[$i]=[];
							for ($i_v=1; $i_v <= $this->max_range; $i_v++) { 
								$poin = 'poin_'.$i_v;
								$satuan = 'satuan_'.$i_v;
								if(!empty($d->$satuan)){
									$range_poin[$i][$d->$poin] = $d->$satuan;
								}else{
									$range_poin[$i][$d->$poin] = $d->$poin;
								}
							}
							for ($i_m=1; $i_m <= $max_for; $i_m++) { 
								if($pn[$i_m]!=''){
									$get_sel = $this->otherfunctions->getParseVar($pn[$i_m]);
									//bypass
									if (isset($get_sel)) {
										if (isset($get_sel['A_'.$id_log])) {
											$selected[$i_m] = $get_sel['A_'.$id_log];
										}else{
											$selected[$i_m] = NULL;
										}
									}
									// $val_e=(isset($get_sel))?end($get_sel):0;
									// $selected[$i_m] = $val_e;
								}else{
									$selected[$i_m] = NULL;
								}
							}
						}
						if($d->jenis_satuan==0){
							for ($i1=1; $i1 <= $max_for; $i1++) { 
								$per[$i1] = form_dropdown('poin_'.$i1.'['.$d->id_task.']', $range_poin[$i1], $selected[$i1], 'class="form-control select2" style="width:100%;" ');
							}
						}elseif($d->jenis_satuan==1) {
							for ($i1=1; $i1 <= $max_for; $i1++) { 
								$per[$i1] = '<div class="form-group"><input type="number" min="0" step="0.01" name="poin_'.$i1.'['.$d->id_task.']" class="form-control" placeholder="Input Data" id="inp_'.$d->id_task.'_'.$i1.'" min="0" value="'.(($selected[$i1] == '')?null:$selected[$i1]).'" onkeyup="max_target(this.value,\''.$d->target.'\',\'inp_'.$d->id_task.'_'.$i1.'\')"></div>';
							}
						}

						if($no!=0){
							$display='block';
						}else{
							$display='';
						}
						$d_kpi=$this->model_master->getKpiKode($d->kode_kpi);
						$c_menghitung='Dijumlahkan';
						if(isset($d_kpi)){
							$c_menghitung=($d_kpi['cara_menghitung'] == 'AVG')?'Average (Rata - Rata)':'Dijumlahkan';
						}
						$arr_start=[
							$d->id_task,
							$d->kpi,
							$c_menghitung,
							$d->sumber_data,
							$d->detail_rumus,
							$d->target,
							$d->bobot,
						];
						for ($in=1;$in<=$max_for;$in++){
							$arr_middle[$in]=$per[$in];
						}
						$arr_end=[
							$d->id_karyawan,
							$display,
							$d->detail_rumus,
							$c_menghitung
						];
						$datax['data'][]=array_merge($arr_start,$arr_middle,$arr_end);
						$no++;			
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_input_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id_karyawan = $this->input->post('id_karyawan');
		$tabel = $this->input->post('tabel');
		$id_part = $this->admin;
		$max_for=3;
		if($id_karyawan!='' || $tabel!='' || $id_part=''){
			$id_task_wajib = $this->input->post('id_tasks_hidden_wajib');
			$id_task_rutin = $this->input->post('id_tasks_hidden_rutin');
			$id_task_tambahan = $this->input->post('id_tasks_hidden_tambahan');

			for ($i1=0; $i1 <=$max_for; $i1++) { 
				$i1_no = $i1+1;
				$poin_wajib[$i1] = $this->input->post('poin'.$i1_no.'_hidden_wajib');
				$poin_rutin[$i1] = $this->input->post('poin'.$i1_no.'_hidden_rutin');
				$poin_tambahan[$i1] = $this->input->post('poin'.$i1_no.'_hidden_tambahan');

				$xpoin=[$poin_wajib[$i1],$poin_rutin[$i1],$poin_tambahan[$i1]];
				$poin[$i1] = $this->exam->mergeArrayNull($xpoin);
			}
			$id_task=[$id_task_wajib,$id_task_rutin,$id_task_tambahan];
			$data1 = $this->exam->mergeArray($id_task);
			foreach ($data1 as $no => $key) {
				$get_data = $this->otherfunctions->convertResultToRowArray(($this->model_agenda->openTableAgendaId($tabel,$key)));
				$bobot = $get_data['bobot'];
				$pn=[];
				for ($i2=0; $i2 <= $max_for; $i2++) { 
					$pn_key = 'pn'.($i2+1);
					$po='';
					if (isset($poin[$i2][$no])) {
						$po=$poin[$i2][$no];
					}
					$pn[$i2] = $this->exam->getNilaiPackRemove($po,'A_'.$id_part,$get_data[$pn_key]); 	
				}
				
				if($get_data['jenis_satuan']==1){
					for ($i3=0; $i3 <= $max_for; $i3++) { 
						$pnx[$i3] = $this->exam->changeJenisSatuanAllBo($key,$pn[$i3],$tabel);
						$nr[$i3] = $this->exam->getNilaiAverage($pnx[$i3]);
						$na[$i3] = $this->exam->getNilaiAverageDone($nr[$i3],$bobot);
						if($na[$i3]==null){
							$na[$i3]=0;
						}
					}
				}else{
					for ($i3=0; $i3 <= $max_for; $i3++) { 
						$nr[$i3] = $this->exam->getNilaiAverage($pn[$i3]);
						$na[$i3] = $this->exam->getNilaiAverageDone($nr[$i3],$bobot);
						if($na[$i3]==null){
							$na[$i3]=0;
						}
					}
				}
				$na_sum = (array_sum($nr))/count($nr);
				$data['na'] = $na_sum;
				for ($i=0; $i <= $max_for; $i++) { 
					$data['pn'.($i+1)]=(($pn[$i] != '')?$pn[$i]:null);
					$data['nr'.($i+1)]=$nr[$i];
					$data['na'.($i+1)]=$this->otherfunctions->changeNullTo($na[$i],0);
				}
				$where = [
					'id_task'=>$key
				];
				$this->model_global->updateQueryNoMsg($data,$tabel,$where); 
			}
			$datax = $this->messages->allGood();
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//import value kpi
	public function import_input_kpi()
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
			'table'=>$this->input->post('tabel'),
			'column_code'=>'kode_kpi',
			'usage'=>'import_kpi',
			'other'=>['id_admin'=>$this->admin],
			'column'=>[
				1=>'nik',6=>'kode_kpi',13=>'target',14=>'bobot',15=>'pn1',16=>'pn2',17=>'pn3',18=>'pn4',
			],
		];
		$data['data']=$sheet;
		$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
//raport kpi
	public function getRaport()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$table = $this->input->post('table');
		$id = $this->input->post('id');
		$agenda = unserialize(base64_decode($this->input->post('agenda')));
		$usage = $this->input->post('usage');
		$id_log = $this->input->post('id_log');
		$max_for = $this->max_month;
		if(empty($usage))
			$usage = 'raport';
		if(empty($id_log))
			$id_log = null;

		$penunjang = '';
		$no = 1;
		$new_val = '';		
		$month_total=[];
		$month_total_real=[];
		for ($i_s=1; $i_s <= $max_for; $i_s++) { 
			$nilai[$i_s] = 0;
			$month_total[$i_s] = 0;	
			$month_total_real[$i_s] = 0;	
		}
		$sumavgmax = 0;
		$nilaixbobot = 0;
		$data = $this->model_agenda->openTableEmployeeKpi($table,$id);
		if(count($data)>0){
			$bobot_all=[];
			$bobot_tall=[];
			$bobot_tertimbang_stat_arr=[];
			$bobot_tertimbang_stat=0;
			foreach ($data as $da){
				for ($i_b=1;$i_b<= $max_for;$i_b++){
					$mnt='na'.$i_b;
					if (is_null($da->$mnt)){
						$bobot_tertimbang_stat_arr[$i_b]=1;
					}
				}
				if($da->not_available == '1'){
					$bobot_tertimbang_stat=1;
				}else{
					$bobot_all[$da->kode_kpi]=$da->bobot;
				}
				$bobot_tall[$da->kode_kpi]=$da->bobot;
				// if($da->not_available == '1'){
				// 	$bobot_tertimbang_stat=1;
				// }else{
				// 	$bobot_all[$da->kode_kpi]=$da->bobot;
				// }
			}
			if ($bobot_tertimbang_stat > 0) {
				foreach ($data as $h) {
					$bobot_bulan=[];
					for ($i_b=1;$i_b<= $max_for;$i_b++){
						$ba_bln=((isset($bobot_tertimbang_stat_arr[$i_b]))?$bobot_all:$bobot_tall);
						if (isset($bobot_tertimbang_stat_arr[$i_b])){
							$bobot_bulan[$i_b]=$this->exam->convertComma($this->exam->bobot_tertimbang($ba_bln,$h->bobot,$h->not_available));
						}else{
							$bobot_bulan[$i_b]=$this->exam->convertComma($this->exam->bobot_tertimbang($ba_bln,$h->bobot,0));
						}
					}
					$new_val .= '<tr>';
					$nr=[];$na=[];$val=[];
					$b_ter_stat = '';
					$bobot=$this->exam->convertComma($this->exam->bobot_tertimbang($bobot_all,$h->bobot,$h->not_available));
					if ($h->kpi == '1'){
						$penunjang = '<br><label class="label label-success">Terkait Data Penunjang</label>';
					}
					if ($bobot_tertimbang_stat > 0 && !$h->not_available) {
						$b_ter_stat=' <label class="label label-danger">Tertimbang</label>';
						// <td>'.$h->bobot.'%</td>
					}
					if($h->sifat == "MAX"){
						$sifat = '<label class="label label-success">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
					}else{
						$sifat = '<label class="label label-warning">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
					}
					$new_val .='
					<td width="3%">'.$no.'.</td>
					<td width="50%">'.$h->kpi.$penunjang.'</td>
					<td>'.$bobot.'%<br>'.$b_ter_stat.'</td>
					<td>'. $this->otherfunctions->getPenilai($h->kode_penilai).'</td>
					<td>'.$h->nama_rumus.'</td>
					<td class="text-center">'.$h->target.'</td>';
					if($h->kode_periode == "BLN") {
						if($usage=='hasil'){
							if($h->kode_penilai=='P3'){
								$exe = $this->exam->getUserPenilai($h->penilai,$id_log);
								if($exe){ 
									if($h->jenis_satuan==1){
										for ($i_n=1; $i_n <= $max_for; $i_n++) { 
											$var_2='capaian_'.$i_n;
											$cols_n='pn'.$i_n;
											if ($h->$cols_n == ''){
												$null[$h->kode_kpi][$i_n]=true;
											}
											$jenis_s=$this->exam->changeJenisSatuanAll($h->id_task,$h->$cols_n,$table);
											$pack[$var_2]=$this->exam->getCapaianNilai($jenis_s,$id_log,$h->bobot);
											$pn[$i_n] = $this->exam->getNilaiAverage($h->$cols_n,true);
											$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
											$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
										}
									}else{
										for ($i_n=1; $i_n <= $max_for; $i_n++) { 
											$col = 'pn'.$i_n;
											$var_2='capaian_'.$i_n;
											if ($h->$col == ''){
												$null[$h->kode_kpi][$i_n]=true;
											}
											$pack[$var_2] = $this->exam->getCapaianNilai($h->$col,$id_log,$h->bobot); 
											$pn[$i_n] = $this->exam->getNilaiAverage($h->$col,true);
											$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
											$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
										}
									}
								}
							}elseif($h->kode_penilai=='P1'){
								for ($i_n=1; $i_n <= $max_for; $i_n++) { 
									$var_pn = 'pn'.$i_n;
									$var_nr = 'nr'.$i_n;
									if ($h->$var_pn == ''){
										$null[$h->kode_kpi][$i_n]=true;
									}
									$var_na = 'na'.$i_n;
									$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
									$nr[$i_n] = $h->$var_nr;
									$na[$i_n] = $h->$var_na;
								}
							}
						}elseif($usage=='raport'){
							for ($i_n=1; $i_n <= $max_for; $i_n++) { 
								$var_pn = 'pn'.$i_n;
								$var_nr = 'nr'.$i_n;
								if ($h->$var_pn == ''){
									$null[$h->kode_kpi][$i_n]=true;
								}
								$var_na = 'na'.$i_n;
								$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
								$nr[$i_n] = $h->$var_nr;
								$na[$i_n] = $h->$var_na;
							}
						}
						$arr_data=['jenis_satuan'=>$h->jenis_satuan,'sifat'=>$h->sifat];
						for ($i_poin=1;$i_poin<=$this->max_range;$i_poin++){
							$p='poin_'.$i_poin;
							$s='satuan_'.$i_poin;
							$arr_data[$p]=$h->$p;
							$arr_data[$s]=$h->$s;
							if ($arr_data[$p] == null) {
								$arr_data[$s]=null;
							}
						}
						for ($i_tb=1; $i_tb <= $max_for; $i_tb++) { 
							$na_konv[$i_tb]=$this->exam->coreConversiKpi($na[$i_tb],$arr_data);
							$na_final[$i_tb]=$na_konv[$i_tb]*($h->bobot/100);
							$na_final_real[$i_tb]=$na_konv[$i_tb]*($bobot_bulan[$i_tb]/100);
							if (isset($null[$h->kode_kpi][$i_tb])) {
								$na_konv[$i_tb]=0;
								$na_final[$i_tb]=null;
								$na_final_real[$i_tb]=null;
							}						
							$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
							<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'%</td>
							<td class="text-center bg-info">'.$this->formatter->getNumberFloat($na_konv[$i_tb]).'<br><b data-toggle="tooltip" title="Poin Terbobot" style="font-size:12pt;cursor:pointer;color:blue">'.$this->formatter->getNumberFloat($na_final[$i_tb]).'</b></td>';
							$month_total[$i_tb]+=$na_final[$i_tb];
							$month_total_real[$i_tb]+=$na_final_real[$i_tb];
						}
						//read rumus menghitung
						if ($h->cara_menghitung == 'SUM') {
							$na_new=$this->rumus->rumus_sum($na_final);
						}else {
							$na_new=$this->rumus->rumus_avg($na_final);
						}					
						$new_val .='<td class="text-center bg-success" style="font-size:12pt"><b>'.$this->formatter->getNumberFloat($na_new).'</b></td>';
					}
					$no++;
					$sumavgmax += $na_new;
					$new_val .= '</tr>';
					for ($i_final=1; $i_final <= $max_for; $i_final++) { 
						$nilai[$i_final] += $na[$i_final];
					}
				}
			}else{
				foreach ($data as $h) {
					$new_val .= '<tr>';
					$nr=[];$na=[];$val=[];
					$b_ter_stat = '';
					$bobot=$this->exam->convertComma($this->exam->bobot_tertimbang($bobot_all,$h->bobot,$h->not_available));
					if ($h->kpi == '1'){
						$penunjang = '<br><label class="label label-success">Terkait Data Penunjang</label>';
					}
					if ($bobot_tertimbang_stat && !$h->not_available) {
						$b_ter_stat=' <label class="label label-danger">Tertimbang</label>';
						// <td>'.$h->bobot.'%</td>
					}
					if($h->sifat == "MAX"){
						$sifat = '<label class="label label-success">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
					}else{
						$sifat = '<label class="label label-warning">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
					}
					$new_val .='
					<td width="3%">'.$no.'.</td>
					<td width="50%">'.$h->kpi.$penunjang.'</td>
					<td>'.$bobot.'%<br>'.$b_ter_stat.'</td>
					<td>'. $this->otherfunctions->getPenilai($h->kode_penilai).'</td>
					<td>'.$h->nama_rumus.'</td>
					<td class="text-center">'.$h->target.'</td>';
					$na_new = 0;
					if($h->kode_periode == "BLN") {
						if($usage=='hasil'){
							if($h->kode_penilai=='P3'){
								$exe = $this->exam->getUserPenilai($h->penilai,$id_log);
								if($exe){ 
									if($h->jenis_satuan==1){
										for ($i_n=1; $i_n <= $max_for; $i_n++) { 
											$var_2='capaian_'.$i_n;
											$cols_n='pn'.$i_n;
											if ($h->$cols_n == ''){
												$null[$h->kode_kpi][$i_n]=true;
											}
											$jenis_s=$this->exam->changeJenisSatuanAll($h->id_task,$h->$cols_n,$table);
											$pack[$var_2]=$this->exam->getCapaianNilai($jenis_s,$id_log,$h->bobot);
											$pn[$i_n] = $this->exam->getNilaiAverage($h->$cols_n,true);
											$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
											$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
										}
									}else{
										for ($i_n=1; $i_n <= $max_for; $i_n++) { 
											$col = 'pn'.$i_n;
											$var_2='capaian_'.$i_n;
											if ($h->$col == ''){
												$null[$h->kode_kpi][$i_n]=true;
											}
											$pack[$var_2] = $this->exam->getCapaianNilai($h->$col,$id_log,$h->bobot); 
											$pn[$i_n] = $this->exam->getNilaiAverage($h->$col,true);
											$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
											$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
										}
									}
								}
							}elseif($h->kode_penilai=='P1'){
								for ($i_n=1; $i_n <= $max_for; $i_n++) { 
									$var_pn = 'pn'.$i_n;
									$var_nr = 'nr'.$i_n;
									if ($h->$var_pn == ''){
										$null[$h->kode_kpi][$i_n]=true;
									}
									$var_na = 'na'.$i_n;
									$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
									$nr[$i_n] = $h->$var_nr;
									$na[$i_n] = $h->$var_na;
								}
							}
						}elseif($usage=='raport'){
							for ($i_n=1; $i_n <= $max_for; $i_n++) { 
								$var_pn = 'pn'.$i_n;
								$var_nr = 'nr'.$i_n;
								if ($h->$var_pn == ''){
									$null[$h->kode_kpi][$i_n]=true;
								}
								$var_na = 'na'.$i_n;
								$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
								$nr[$i_n] = $h->$var_nr;
								$na[$i_n] = $h->$var_na;
							}
						}
						$arr_data=['jenis_satuan'=>$h->jenis_satuan,'sifat'=>$h->sifat];
						for ($i_poin=1;$i_poin<=$this->max_range;$i_poin++){
							$p='poin_'.$i_poin;
							$s='satuan_'.$i_poin;
							$arr_data[$p]=$h->$p;
							$arr_data[$s]=$h->$s;
							if ($arr_data[$p] == null) {
								$arr_data[$s]=null;
							}
						}
						$nilai_realBulan = 0;
						for ($i_tb=1; $i_tb <= $max_for; $i_tb++) { 
							$na_konv[$i_tb]=$this->exam->coreConversiKpi($na[$i_tb],$arr_data);
							$na_final[$i_tb]=$na_konv[$i_tb]*($h->bobot/100);
							if (isset($null[$h->kode_kpi][$i_tb])) {
								$na_konv[$i_tb]=0;
								$na_final[$i_tb]=null;
							}
							// $nilai_realBulan +=$na_final[$i_tb];
							// if ($h->cara_menghitung == 'SUM' && $h->sifat == "MAX") {
							// 	$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
							// 	<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'%</td>
							// 	<td class="text-center bg-info">'.$this->formatter->getNumberFloat($na_konv[$i_tb]).'<br><b data-toggle="tooltip" title="Poin Terbobot" style="font-size:12pt;cursor:pointer;color:blue">'.$this->formatter->getNumberFloat($nilai_realBulan).'</b></td>';
							// }else{
								$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'%</td>
								<td class="text-center bg-info">'.$this->formatter->getNumberFloat($na_konv[$i_tb]).'<br><b data-toggle="tooltip" title="Poin Terbobot" style="font-size:12pt;cursor:pointer;color:blue">'.$this->formatter->getNumberFloat($na_final[$i_tb]).'</b></td>';
							// }
							$month_total[$i_tb]+=$na_final[$i_tb];
						}
						//read rumus menghitung
						// echo '<pre>';
						  
						// $array = array(12, 0, 0, 18, 27, 0, 46);
						// print_r(array_filter($na_final, ">0"));
						// print_r($na_final);
						// $na_final=array_filter($na_final, 'is_numeric');
						// $na_new=end($na_final);
						// print_r($na_new);
						if ($h->cara_menghitung == 'SUM') {
							// if($h->sifat == "MAX"){
							// 	$na_new=$nilai_realBulan;
							// }else{
								// $na_new=$this->rumus->rumus_sum($na_final);
								$na_final=array_filter($na_final, 'is_numeric');
								$na_new=end($na_final);
							// }
						}else {
							$na_new=$this->rumus->rumus_avg($na_final);
						}					
						$new_val .='<td class="text-center bg-success" style="font-size:12pt"><b>'.$this->formatter->getNumberFloat($na_new).'</b></td>';
					}
					$no++;
					$sumavgmax += $na_new;
					$new_val .= '</tr>';
					if($na_new != 0){
						for ($i_final=1; $i_final <= $max_for; $i_final++) { 
							$nilai[$i_final] += $na[$i_final];
						}
					}
				}
			}
			$new_val .= '<tr>';
			// $getKonversi=$this->model_master->getKonversiKpiNilai($sumavgmax);
			// $color=(isset($getKonversi['warna']))?$getKonversi['warna']:'grey';
			// $nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
			// $t_color=($color=='grey')?'black':'white';
			$new_val .= '<tr>';
			$kosongAll = 0;
			$nilaiKosong = 0;
			if (isset($agenda['start']) && isset($agenda['end']) && isset($agenda['tahun'])) {
				$new_val.='<td colspan="6" class="text-center bg-aqua" style="font-size:12pt;font-weight:600;">Nilai Total</td>';
				$periode = $this->formatter->getNameOfMonthByPeriode($agenda['start'],$agenda['end'],$agenda['tahun']);
				$c_month=1;
				foreach ($periode as $pkey => $pval) {
					$nilai_real_terbobot=null;
					if(isset($month_total_real[$c_month]) && isset($month_total[$c_month])){
						if($month_total_real[$c_month] != $month_total[$c_month]){
							if($month_total_real[$c_month] != 0){
								$kosongAll +=1;
								$nilai_real_terbobot = ((isset($month_total_real[$c_month]))?'<br><span style="font-size:11pt;font-weight:600;">'.$this->formatter->getNumberFloat($month_total_real[$c_month]).'</span>':null);
								$nilaiKosong += $month_total_real[$c_month];
							}
						}
					}
					$new_val .= '<td colspan="2" class="bg-aqua">Nilai Bulan <br>'.$pval.'</td><td class="text-center bg-blue" style="font-size:16pt;font-weight:bold">'.((isset($month_total[$c_month]))?$this->formatter->getNumberFloat($month_total[$c_month]):0).$nilai_real_terbobot.'</td>';
					$c_month++;
				}
			}else{
				$new_val.='<td colspan="23" class="text-center bg-aqua"></td>
				<td class="text-center bg-yellow" style="font-size:12pt;font-weight:600;">Nilai Akhir</td>';
			}
			if($kosongAll > 2){
				$sumavgmax = $nilaiKosong/$this->max_month;
			}else{
				$sumavgmax = $sumavgmax;
			}
			$getKonversi=$this->model_master->getKonversiKpiNilai($sumavgmax);
			$color=(isset($getKonversi['warna']))?$getKonversi['warna']:'grey';
			$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
			$t_color=($color=='grey')?'black':'white';
			$new_val .= '<td class="bg-navy text-center" style="font-size:24pt;font-weight:bold;">
			<b>'.$this->formatter->getNumberFloat($sumavgmax).'</b>
			</td>
			</tr>';
			$datax = [
				'tabel'=>$new_val,
				'nilai'=>$this->formatter->getNumberFloat($sumavgmax),
				'huruf'=>$nama,
				'color'=>$color,
				'display'=>'show'
			];
		}else{
			$datax = [
				'tabel'=>'',
				'color'=>'black',
				'nilai'=>0,
				'display'=>'hide'
			];
		}		
		echo json_encode($datax);
	}
	//old function do not delete this function
	// public function getRaport()
	// {
	// 	if (!$this->input->is_ajax_request()) 
	// 		redirect('not_found');
	// 	$table = $this->input->post('table');
	// 	$id = $this->input->post('id');
	// 	$jenis = $this->input->post('jenis');
	// 	$agenda = unserialize(base64_decode($this->input->post('agenda')));
	// 	$usage = $this->input->post('usage');
	// 	$id_log = $this->input->post('id_log');
	// 	$max_for = 4;
	// 	if(empty($usage))
	// 		$usage = 'raport';
	// 	if(empty($id_log))
	// 		$id_log = null;

	// 	$penunjang = '';
	// 	$no = 1;
	// 	$new_val = '';
	// 	for ($i_s=1; $i_s <= $max_for; $i_s++) { 
	// 		$nilai[$i_s] = 0;
	// 	}
	// 	$sumavgmax = 0;
	// 	$nilaixbobot = 0;
	// 	$data = $this->model_agenda->openTableWithJenisKpi($table,$jenis,$id);
	// 	if(count($data)>0){
	// 		foreach ($data as $h) {
	// 			$new_val .= '<tr>';
	// 			$nr=[];$na=[];$val=[];
	// 			if ($h->kpi == '1')
	// 				$penunjang = '<br><label class="label label-success">Terkait Data Penunjang</label>';

	// 			if($h->sifat == "MAX"){
	// 				$sifat = '<label class="label label-success">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
	// 			}else{
	// 				$sifat = '<label class="label label-warning">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
	// 			}
	// 			$new_val .='
	// 			<td>'.$no.'</td>
	// 			<td>'.$h->kpi.$penunjang.'</td>
	// 			<td>'.$h->bobot.'%</td>
	// 			<td>'. $this->otherfunctions->getPenilai($h->kode_penilai).'</td>
	// 			<td>'.$h->nama_rumus.'</td>
	// 			<td class="text-center">'.$h->target.'</td>';
	// 			if($h->kode_periode == "BLN") {
	// 				if($usage=='hasil'){
	// 					if($h->kode_penilai=='P3'){
	// 						$exe = $this->exam->getUserPenilai($h->penilai,$id_log);
	// 						if($exe){ 
	// 							if($h->jenis_satuan==1){
	// 								for ($i_n=1; $i_n <= $max_for; $i_n++) { 
	// 									$var_2='capaian_'.$i_n;
	// 									$cols_n='pn'.$i_n;
	// 									$jenis_s=$this->exam->changeJenisSatuanAll($h->id_task,$h->$cols_n,$table);
	// 									$pack[$var_2]=$this->exam->getCapaianNilai($jenis_s,$id_log,$h->bobot);
	// 									$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
	// 									$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
	// 								}
	// 							}else{
	// 								for ($i_n=1; $i_n <= $max_for; $i_n++) { 
	// 									$col = 'pn'.$i_n;
	// 									$var_2='capaian_'.$i_n;
	// 									$pack[$var_2] = $this->exam->getCapaianNilai($h->$col,$id_log,$h->bobot); 
	// 									$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
	// 									$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
	// 								}
	// 							}
	// 						}
	// 					}elseif($h->kode_penilai=='P1'){
	// 						for ($i_n=1; $i_n <= $max_for; $i_n++) { 
	// 							$var_pn = 'pn'.$i_n;
	// 							$var_nr = 'nr'.$i_n;
	// 							$var_na = 'na'.$i_n;
	// 							$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn);
	// 							$nr[$i_n] = $h->$var_nr;
	// 							$na[$i_n] = $h->$var_na;
	// 						}
	// 					}
	// 				}elseif($usage=='raport'){
	// 					for ($i_n=1; $i_n <= $max_for; $i_n++) { 
	// 						$var_pn = 'pn'.$i_n;
	// 						$var_nr = 'nr'.$i_n;
	// 						$var_na = 'na'.$i_n;
	// 						$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn);
	// 						$nr[$i_n] = $h->$var_nr;
	// 						$na[$i_n] = $h->$var_na;
	// 					}
	// 				}
	// 				for ($i_tb=1; $i_tb <= $max_for; $i_tb++) { 
	// 					$val[]=$na[$i_tb];
	// 					$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($pn[$i_tb]).'</td>
	// 					<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
	// 					<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'</td>';
	// 				}
	// 				//read rumus menghitung
	// 				if ($h->rumus_kpi == 'SUM') {
	// 					$average=$this->rumus->rumus_sum($val);
	// 				}else {
	// 					$average=$this->rumus->rumus_avg($val);
	// 				}
	// 				$new_val .='<td class="text-center bg-success">'.$this->formatter->getNumberFloat($average).'</td>';
	// 			}
	// 			$no++;
	// 			$sumavgmax += $average;
	// 			$new_val .= '</tr>';
	// 			for ($i_final=1; $i_final <= $max_for; $i_final++) { 
	// 				$nilai[$i_final] += $na[$i_final];
	// 			}
	// 			$bobot_jenis = $h->bobot_jenis_kpi;
	// 		}
	// 		$new_val .= '<tr>
	// 		<td colspan="6" class="text-center bg-aqua"><b>Nilai Total</b></td>';
	// 		if ($h->kode_periode == "BLN") {
	// 			$periode = $this->formatter->getNameOfMonthByPeriode($agenda['start'],$agenda['end'],$agenda['tahun']);
	// 			$kc = 1;
	// 			foreach ($periode as $pkey => $pval) {

	// 				$new_val .= '<td colspan="2" class="text-center bg-navy">'.$pval.'</td>
	// 				<td class="text-center bg-info">'.$this->formatter->getNumberFloat($nilai[$kc]).'</td>';
	// 				$kc++;
	// 			}
	// 		}
	// 		$getKonversi=$this->model_master->getKonversiKpiJenis($sumavgmax,$jenis);
	// 		$color=(isset($getKonversi['warna']))?$getKonversi['warna']:'grey';
	// 		$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
	// 		$t_color=($color=='grey')?'black':'white';
	// 		$new_val .= '<td id="nilai_real_'.$jenis.'" class="text-center" style="vertical-align: middle;padding:0px;"><div style="display:block;background-color:'.$color.';"><b style="font-size:12pt;font-weight:600;color:'.$t_color.';">'.$this->formatter->changeNilaiCustom($sumavgmax,'0').'</b><br>
	// 		<b class="text-center" style="color:'.$t_color.'">'.$nama.'</b></div></td></tr>
	// 		<tr>
	// 		<td colspan="16" class="text-center bg-aqua"></td>
	// 		<td colspan="2" class="text-center bg-navy" style="font-size:12pt;font-weight:600;">Nilai x '.$bobot_jenis.'%</td>
	// 		<td class="bg-yellow text-center" id="nilai_akhir_'.$jenis.'" style="font-size:16pt;font-weight:bold;">
	// 		<b>'.$this->formatter->changeNilaiCustom(($sumavgmax*($bobot_jenis/100)),'0').'</b>
	// 		</td>
	// 		</tr>';
	// 		$datax = [
	// 			'tabel'=>$new_val,
	// 			'capaian'=>$sumavgmax,
	// 			'nilai'=>($sumavgmax*($bobot_jenis/100)),
	// 			'bobot'=>$bobot_jenis,
	// 			'display'=>'show'
	// 		];
	// 	}else{
	// 		$datax = [
	// 			'tabel'=>'',
	// 			'capaian'=>0,
	// 			'nilai'=>0,
	// 			'bobot'=>0,
	// 			'display'=>'hide'
	// 		];
	// 	}		
	// 	echo json_encode($datax);
	// }
	//Konversi KPI
	public function getKonversiKpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data=$this->model_master->getListKonversiKpi(true);
		$no=1;
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->nama.(($d->huruf)?' ('.$d->huruf.')':null),
				(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
				(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark()
			];
			$no++;
		}
		echo json_encode($datax);
	}
	public function getKonversiFinal()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$nilai = $this->input->post('nilai');
		$getKonversi=$this->model_master->getKonversiKpiJenis($nilai,'AKHIR');
		$color=(isset($getKonversi['warna']))?$getKonversi['warna']:'grey';
		$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
		$t_color=($color=='grey')?'black':'white';
		$html='<div style="display:block;background-color:'.$color.';"><b style="font-size:25pt;font-weight:600;color:'.$t_color.';">'.$this->formatter->changeNilaiCustom($nilai,'0').'</b><br>
			<b class="text-center" style="color:'.$t_color.'">'.$nama.'</b></div>';
		echo json_encode($html);
	}
	//--------------------- EXAM KPI END ---------------------//
	//===AGENDA KPI END===//
	//=================================================BLOCK CHANGE=================================================//
	//===AGENDA SIKAP BEGIN===//
	public function agenda_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getListAgendaSikap();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_a_sikap,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel,'sikap');
					$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$count_p.' %" data-placement="right">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$validate = null;
					if (isset($access['l_ac']['apr'])) {
						if ($d->status){
							if ($d->tgl_selesai <= $this->date){
								if ($d->validasi) {
									$validate = (in_array('APR', $access['access'])) ? '<button type="button" class="btn btn-warning btn-sm"  href="javascript:void(0)" onclick=validate_modal('.$var['id'].',0)><i class="fa fa-calendar-times" data-toggle="tooltip" title="Batalkan Validasi Data"></i></button> ' : null;
								}else{
									$validate = (in_array('APR', $access['access'])) ? '<button type="button" class="btn btn-success btn-sm"  href="javascript:void(0)" onclick=validate_modal('.$var['id'].',1)><i class="fa fa-calendar-check" data-toggle="tooltip" title="Validasi Data"></i></button> ' : null;
								}
							}
						}
					}
					$datax['data'][]=[
						$d->id_a_sikap,
						$d->kode_a_sikap.(($d->validasi)?'<br><label class="label label-success"><i class="fa fa-check"></i> Agenda Tervalidasi</label>':null),
						$d->nama,
						$progress,
						$d->nama_konsep,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()).' - '.$d->tahun,
						$tanggal,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$validate,
						$this->codegenerator->encryptChar($d->kode_c_sikap)
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_a_sikap');
				$data=$this->model_agenda->getAgendaSikap($id);
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_a_sikap,
						'kode_a_sikap'=>$d->kode_a_sikap,
						'nama'=>$d->nama,
						'nama_detail'=>$d->nama.(($d->validasi)?'<br><label class="label label-success"><i class="fa fa-check"></i> Agenda Tervalidasi</label>':null),
						'nama_tabel'=>$d->nama_tabel,
						'nama_konsep'=>'<a href="'.base_url('pages/view_data_konsep_sikap/'.$this->codegenerator->encryptChar($d->kode_c_sikap)).'" target="blank" title="Klik untuk Detail">'.$d->nama_konsep.'</a>',
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'periode'=>$d->periode,
						'periode_view'=>(!empty($d->nama_periode)) ? ((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()) : $this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeAgendaSikap();
				echo json_encode($data);
			}elseif ($usage == 'get_select2') {
				$data = $this->model_agenda->getListAgendaSikap(true);
				$datax=[];
				if (isset($data)) {
					foreach ($data as $d) {
						$datax[$d->nama_tabel]=$d->nama.' ('.((!empty($d->nama_periode))?$d->nama_periode.' - ':null).$d->tahun.')';
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_agenda_sikap()
	{ 
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$kode_c=$this->input->post('konsep');
			$table=$this->exam->getNameTable('agenda_sikap');
			$data=[
				'kode_a_sikap'=>$kode,
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'kode_c_sikap'=>$kode_c,
				'nama_tabel'=>$table,
				'periode'=>$this->input->post('periode'),
				'tahun'=>$this->input->post('tahun'),
			];
			$data_log=[
				'kode_l_a_sikap'=>$this->codegenerator->kodeLogAgendaSikap(),
				'nama'=>$data['nama'],
				'tgl_mulai'=>$data['tgl_mulai'],
				'tgl_selesai'=>$data['tgl_selesai'],
				'periode'=>$data['periode'],
				'tahun'=>$data['tahun'],
				'kode_c_sikap'=>$data['kode_c_sikap'],
				'kode_a_sikap'=>$kode,
				'nama_tabel'=>$data['nama_tabel'],
				
			];
			$cek_avl=$this->model_agenda->checkAgendaAvailable('agenda_sikap',['periode'=>$data['periode'],'tahun'=>$data['tahun']]);
			if ($cek_avl['agenda'] > 0 || $cek_avl['log_agenda'] > 0){
				$datax=$this->messages->customFailure('Agenda Sudah Ada');
			}else{
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$data_log=array_merge($data_log,$this->model_global->getCreateProperties($this->admin));
				$gen=$this->model_agenda->generateAgendaSikap($kode_c,$table,$data['periode']);
				if ($gen) {
					$this->model_global->insertQueryCCNoMsg($data_log,'log_agenda_sikap',$this->model_agenda->checkLogAgendaSikapCode($kode));
					$datax = $this->model_global->insertQueryCC($data,'agenda_sikap',$this->model_agenda->checkAgendaSikapCode($kode));
					$dataNotif =[
						'judul'=>$data['nama'],
						'start'=>$data['tgl_mulai'],
						'end_date'=>$data['tgl_selesai'],
						'admin'=>$this->admin,
						'jenis'=>'Agenda Aspek Sikap',
						'link'=>base_url('kpages/input_attitude_tasks_value/'.$this->codegenerator->encryptChar($kode)),
					];
					$this->otherfunctions->generateNotifikasi($dataNotif);
				}else{
					$datax=$this->messages->customFailure('Generate Tabel Gagal, Mohon Kontak Administrator!');
				}
			}			
		}
		echo json_encode($datax);
	}
	public function edt_agenda_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$data=[
				'kode_a_sikap'=>$this->input->post('kode'),
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'periode'=>$this->input->post('periode'),
				'tahun'=>$this->input->post('tahun'),
			];
			$data_log=[
				'nama'=>$data['nama'],
				'tgl_mulai'=>$data['tgl_mulai'],
				'tgl_selesai'=>$data['tgl_selesai'],
				'periode'=>$data['periode'],
				'tahun'=>$data['tahun'],
			];
			$cek_avl=$this->model_agenda->checkAgendaAvailable('agenda_sikap',['periode'=>$data['periode'],'tahun'=>$data['tahun']]);
			if (($cek_avl['agenda'] > 0 || $cek_avl['log_agenda'] > 0) && (($data['periode'] != $this->input->post('periode_old') && $data['tahun'] != $this->input->post('tahun_old')) || ($data['periode'] != $this->input->post('periode_old') && $data['tahun'] == $this->input->post('tahun_old')) || ($data['periode'] == $this->input->post('periode_old') && $data['tahun'] != $this->input->post('tahun_old')))){
				$datax=$this->messages->customFailure('Agenda Sudah Ada');
			}else{
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$data_log=array_merge($data_log,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($data_log,'log_agenda_sikap',['kode_a_sikap'=>$data['kode_a_sikap']]);
			//cek data
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_a_sikap']) {
					$cek=$this->model_master->checkAgendaSikapCode($data['kode_a_sikap']);
				}else{
					$cek=false;
				}
				$datax = $this->model_global->updateQueryCC($data,'agenda_sikap',['id_a_sikap'=>$id],$cek);
			}
		}
		echo json_encode($datax);
	}
	public function val_agenda_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$data=[
				'validasi'=>$this->input->post('status'),
			];
			$dt_agenda=$this->model_agenda->getAgendaSikapKode($kode);
			if (isset($dt_agenda)){
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$data_log=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($data_log,'log_agenda_sikap',['kode_a_sikap'=>$kode]);
				if($data['validasi']){
					$this->model_agenda->syncPoinEmployee(['kode_periode'=>$dt_agenda['periode'],'tahun'=>$dt_agenda['tahun']]);
				}
				$datax = $this->model_global->updateQuery($data,'agenda_sikap',['kode_a_sikap'=>$kode]);
			}else{
				$datax=$this->messages->customFailure('Agenda Tidak Ditemukan');
			}
		}
		echo json_encode($datax);
	}
	public function stt_agenda_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data=$this->input->post('data');
		$where=$this->input->post('where');
		
		if (empty($data) || empty($where))
			echo json_encode($this->messages->notValidParam());
		$dt_agenda=$this->model_agenda->getAgendaSikap($where['id_a_sikap']);
		if(isset($dt_agenda[0])){
			$this->model_global->updateQueryNoMsg($data,'log_agenda_sikap',['kode_a_sikap'=>$dt_agenda[0]->kode_a_sikap]);
			$datax=$this->model_global->updateQuery($data,'agenda_sikap',['kode_a_sikap'=>$dt_agenda[0]->kode_a_sikap]);
		}else{
			$datax=$this->messages->customFailure('Agenda Tidak Ditemukan');
		}
		echo json_encode($datax);
	}
//--------------------- KALIBRASI SIKAP BEGIN ---------------------//
	function edit_kalibrasi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id_karyawan');
		$tabel=$this->input->post('tabel');
		if ($id != "") {
			$data=array(
				'na_kalibrasi'=>$this->input->post('nilai'),
			);
			$datax = $this->model_global->updateQuery($data,$tabel,['id_karyawan'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function kalibrasi_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$tabel=$this->input->post('tabel');
		if ($tabel == "") {
			$datax=$this->messages->notValidParam(); 
		}else{
			$karyawan=$this->input->post('karyawan');
			$nilai=$this->input->post('nilai');
			$operator=$this->input->post('operator');
			$data=$this->codegenerator->decryptChar($this->input->post('data'));
			foreach ($karyawan as $k) {
				if (isset($data[$k])) {
					$na[$k]=$data[$k];
					if ($operator == "+") {
						$nax[$k]=$na[$k]+$nilai;
					}else{
						$nax[$k]=$na[$k]-$nilai;
					}
					$datax[$k]=['na_kalibrasi'=>$nax[$k]];
					$datax = $this->model_global->updateQuery($datax[$k],$tabel,['id_karyawan'=>$k]);
				}else{
					$datax=$this->messages->notValidParam();
				}				
			}
		}
		echo json_encode($datax);
	}
	//--------------------- KALIBRASI SIKAP END ---------------------//
	//===AGENDA SIKAP END===//
	//=================================================BLOCK CHANGE=================================================//
	//===AGENDA REWARD BEGIN===//
	public function agenda_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getListAgendaReward();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_a_reward,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel);
					$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$count_p.' %" data-placement="right">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$validate = null;
					if (isset($access['l_ac']['apr'])) {
						if ($d->status){
							if ($d->tgl_selesai <= $this->date){
								if ($d->validasi) {
									$validate = (in_array('APR', $access['access'])) ? '<button type="button" class="btn btn-warning btn-sm"  href="javascript:void(0)" onclick=validate_modal('.$var['id'].',0)><i class="fa fa-calendar-times" data-toggle="tooltip" title="Batalkan Validasi Data"></i></button> ' : null;
								}else{
									$validate = (in_array('APR', $access['access'])) ? '<button type="button" class="btn btn-success btn-sm"  href="javascript:void(0)" onclick=validate_modal('.$var['id'].',1)><i class="fa fa-calendar-check" data-toggle="tooltip" title="Validasi Data"></i></button> ' : null;
								}
							}
						}
					}
					$datax['data'][]=[
						$d->id_a_reward,
						$d->kode_a_reward.(($d->validasi)?'<br><label class="label label-success"><i class="fa fa-check"></i> Agenda Tervalidasi</label>':null),
						$d->nama,
						$progress,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()).' - '.$d->tahun,
						$tanggal,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$validate,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_a_reward');
				$data=$this->model_agenda->getAgendaReward($id);
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_a_reward,
						'kode_a_reward'=>$d->kode_a_reward,
						'nama'=>$d->nama,
						'nama_detail'=>$d->nama.(($d->validasi)?'<br><label class="label label-success"><i class="fa fa-check"></i> Agenda Tervalidasi</label>':null),
						'nama_tabel'=>$d->nama_tabel,
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'periode'=>$d->periode,
						'periode_view'=>(!empty($d->nama_periode)) ? ((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()) : $this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeAgendaReward();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_agenda_reward()
	{ 
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$kode_c=$this->input->post('konsep');
			$table=$this->exam->getNameTable('agenda_reward');
			$data=[
				'kode_a_reward'=>$kode,
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'nama_tabel'=>$table,
				'periode'=>$this->input->post('periode'),
				'tahun'=>$this->input->post('tahun'),
			];
			$data_log=[
				'kode_l_a_reward'=>$this->codegenerator->kodeLogAgendaReward(),
				'nama'=>$data['nama'],
				'tgl_mulai'=>$data['tgl_mulai'],
				'tgl_selesai'=>$data['tgl_selesai'],
				'periode'=>$data['periode'],
				'tahun'=>$data['tahun'],
				'kode_a_reward'=>$kode,
				'nama_tabel'=>$data['nama_tabel'],
			];
			$cek_avl=$this->model_agenda->checkAgendaAvailable('agenda_reward',['periode'=>$data['periode'],'tahun'=>$data['tahun']]);
			if ($cek_avl['agenda'] > 0 || $cek_avl['log_agenda'] > 0){
				$datax=$this->messages->customFailure('Agenda Sudah Ada');
			}else{
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$data_log=array_merge($data_log,$this->model_global->getCreateProperties($this->admin));
				$gen=$this->model_agenda->generateAgendaReward($table);
				if ($gen) {
					$this->model_global->insertQueryCCNoMsg($data_log,'log_agenda_reward',$this->model_agenda->checkLogAgendaRewardCode($kode));
					$datax = $this->model_global->insertQueryCC($data,'agenda_reward',$this->model_agenda->checkAgendaRewardCode($kode));
				}else{
					$datax=$this->messages->customFailure('Generate Tabel Gagal, Mohon Kontak Administrator!');
				}
			}			
		}
		echo json_encode($datax);
	}
	public function edt_agenda_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$data=[
				'kode_a_reward'=>$this->input->post('kode'),
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'periode'=>$this->input->post('periode'),
				'tahun'=>$this->input->post('tahun'),
			];
			$data_log=[
				'nama'=>$data['nama'],
				'tgl_mulai'=>$data['tgl_mulai'],
				'tgl_selesai'=>$data['tgl_selesai'],
				'periode'=>$data['periode'],
				'tahun'=>$data['tahun'],
			];
			$cek_avl=$this->model_agenda->checkAgendaAvailable('agenda_reward',['periode'=>$data['periode'],'tahun'=>$data['tahun']]);
			if (($cek_avl['agenda'] > 0 || $cek_avl['log_agenda'] > 0) && (($data['periode'] != $this->input->post('periode_old') && $data['tahun'] != $this->input->post('tahun_old')) || ($data['periode'] != $this->input->post('periode_old') && $data['tahun'] == $this->input->post('tahun_old')) || ($data['periode'] == $this->input->post('periode_old') && $data['tahun'] != $this->input->post('tahun_old')))){
				$datax=$this->messages->customFailure('Agenda Sudah Ada');
			}else{
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$data_log=array_merge($data_log,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($data_log,'log_agenda_reward',['kode_a_reward'=>$data['kode_a_reward']]);
			//cek data
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_a_reward']) {
					$cek=$this->model_master->checkAgendaRewardCode($data['kode_a_reward']);
				}else{
					$cek=false;
				}
				$datax = $this->model_global->updateQueryCC($data,'agenda_reward',['id_a_reward'=>$id],$cek);
			}
		}
		echo json_encode($datax);
	}
	public function val_agenda_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$data=[
				'validasi'=>$this->input->post('status'),
			];
			$dt_agenda=$this->model_agenda->getAgendaRewardKode($kode);
			if (isset($dt_agenda)){
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$data_log=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($data_log,'log_agenda_reward',['kode_a_reward'=>$kode]);
				if($data['validasi']){
					$this->model_agenda->syncPoinEmployee(['kode_periode'=>$dt_agenda['periode'],'tahun'=>$dt_agenda['tahun']]);
				}
				$datax = $this->model_global->updateQuery($data,'agenda_reward',['kode_a_reward'=>$kode]);
			}else{
				$datax=$this->messages->customFailure('Agenda Tidak Ditemukan');
			}
		}
		echo json_encode($datax);
	}
	public function stt_agenda_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data=$this->input->post('data');
		$where=$this->input->post('where');
		
		if (empty($data) || empty($where))
			echo json_encode($this->messages->notValidParam());
		$dt_agenda=$this->model_agenda->getAgendaReward($where['id_a_reward']);
		if(isset($dt_agenda[0])){
			$this->model_global->updateQueryNoMsg($data,'log_agenda_reward',['kode_a_reward'=>$dt_agenda[0]->kode_a_reward]);
			$datax=$this->model_global->updateQuery($data,'agenda_reward',['kode_a_reward'=>$dt_agenda[0]->kode_a_reward]);
		}else{
			$datax=$this->messages->customFailure('Agenda Tidak Ditemukan');
		}
		echo json_encode($datax);
	}
	public function data_input_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getAgendaActive('agenda_reward');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$access['l_ac']=$this->otherfunctions->unsetArrayValue($access['l_ac'],'DEL');
					$access['l_ac']=$this->otherfunctions->unsetArrayValue($access['l_ac'],'STT');
					$access['l_ac']=$this->otherfunctions->unsetArrayValue($access['l_ac'],'EDT');
					$var=[
						'id'=>$d->id_a_reward,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel);
					$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$count_p.' %" data-placement="right">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$keterangan='<label class="label label-success"><i class="fa fa-check"></i> Agenda Sedang Berlangsung</label>';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_a_reward,
						'<a href="'.base_url('pages/input_reward_value/').$this->codegenerator->encryptChar($d->kode_a_reward).'">'.$d->kode_a_reward.'</a>',
						$d->nama,
						$progress,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()).' - '.$d->tahun,
						$tanggal,
						$keterangan,
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_a_reward');
				$data=$this->model_agenda->getAgendaReward($id);
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_a_reward,
						'kode_a_reward'=>$d->kode_a_reward,
						'nama'=>$d->nama,
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'periode_view'=>(!empty($d->nama_periode)) ? ((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()) : $this->otherfunctions->getMark(),
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
	public function input_agenda_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$table=$this->input->post('table');
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:null;
				$post_form=[];
				if (!empty($filter)) {
					$post_form['bagian_filter']=$filter;
				}					
				$data=$this->model_agenda->openTableAgenda($table,$post_form);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$access['l_ac']=$this->otherfunctions->unsetArrayValue($access['l_ac'],'STT');
					$var=[
						'id'=>$d->id_task,
						'access'=>$access,
					];
					$table_reward='';
					if ($d->kode_reward != null) {
						$val=$this->otherfunctions->getParseVar($d->kode_reward);
						$table_reward .= '<table class="table table-striped">
						<thead>
						<tr class="bg-blue">
						<th>Reward</th>
						<th>Poin</th>
						</tr>
						</thead><tbody>';
						foreach ($val as $k_v => $v_v) {
							$nama=$this->model_master->getRewardKode($k_v)['nama'];
							$table_reward .='<tr>
							<td>'.$nama.'</td>
							<td>'.$v_v.'</td>
							</tr>';
						}
						$table_reward .= '</tbody></table>';

					}
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_task,
						$d->nik,
						$d->nama,
						$d->nama_jabatan,
						$d->bagian,
						$d->nama_loker,
						$table_reward,
						$d->na,
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_task');
				$data=$this->model_agenda->openTableAgendaId($table,$id);
				$datax=[];
				foreach ($data as $d) {
					$reward=[];
					$table_reward='';
					if ($d->kode_reward != null) {
						$val=$this->otherfunctions->getParseVar($d->kode_reward);
						$table_reward .= '<table class="table table-striped">
						<thead>
						<tr class="bg-blue">
						<th>Reward</th>
						<th>Poin</th>
						</tr>
						</thead><tbody>';
						foreach ($val as $k_v => $v_v) {
							array_push($reward,$k_v);
							$nama=$this->model_master->getRewardKode($k_v)['nama'];
							$table_reward .='<tr>
							<td>'.$nama.'</td>
							<td>'.$v_v.'</td>
							</tr>';
						}
						$table_reward .= '</tbody></table>';

					}
					$datax=[
						'id'=>$d->id_task,
						'nik'=>$d->nik,
						'nama'=>$d->nama,
						'nama_jabatan'=>$d->nama_jabatan,
						'nama_loker'=>$d->nama_loker,
						'nama_bagian'=>$d->bagian,
						'table_view'=>$table_reward,
						'reward_val'=>$reward,
						'poin'=>$d->na,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'get_employee') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
				$data = $this->model_agenda->getEmployeeReward($table,$filter);
				echo json_encode($data);
			}elseif ($usage == 'get_reward') {
				$data = $this->model_master->getListRewardActive($table);
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_employee_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$emp=$this->input->post('karyawan');
		$table=$this->input->post('table');
		if ($emp == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$reward=$this->input->post('reward');
			$pack=[];
			$poin=[];
			foreach ($reward as $r) {
				$m_r=$this->model_master->getRewardKode($r);
				if (isset($m_r)) {
					$pack[$r]=$m_r['nilai'];
					array_push($poin,$m_r['nilai']);
				}				
			}
			$reward=$this->exam->packValue($pack);
			$poin=array_sum($poin);
			if (isset($emp)) {
				foreach ($emp as $e) {
					$kar=$this->model_karyawan->getEmployeeId($e);
					$data=[
						'id_karyawan'=>$e,
						'kode_jabatan'=>$kar['jabatan'],
						'kode_loker'=>$kar['loker'],
						'kode_reward'=>$reward,
						'na'=>$poin,
					];
					$this->model_global->insertQueryNoMsg($data,$table);
				}
				$datax=$this->messages->allGood();
			}else{
				$datax=$this->messages->allFailure();
			}		
		}
		echo json_encode($datax);
	}
	function edit_employee_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		if ($id == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$reward=$this->input->post('reward');
			$pack=[];
			$poin=[];
			foreach ($reward as $r) {
				$m_r=$this->model_master->getRewardKode($r);
				if (isset($m_r)) {
					$pack[$r]=$m_r['nilai'];
					array_push($poin,$m_r['nilai']);
				}				
			}
			$reward=$this->exam->packValue($pack);
			$poin=array_sum($poin);
			if (isset($reward)) {
				$data=[
					'kode_reward'=>$reward,
					'na'=>$poin,
				];
				$datax=$this->model_global->updateQuery($data,$table,['id_task'=>$id]);
			}else{
				$datax=$this->messages->allFailure();
			}		
		}
		echo json_encode($datax);
	}
	//===AGENDA REWARD END===//
	//=================================================BLOCK CHANGE=================================================//
	//===AGENDA KOMPETENSI BEGIN===//
	//===AGENDA KOMPETENSI END===//
	//=================================================BLOCK CHANGE=================================================//
	//===LOG AGENDA KPI BEGIN===//
	public function log_agenda_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getListLogAgendaKpi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_l_a_kpi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel);
					$progress='<div class="progress active">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					<b class="text-black">'.$count_p.' %</b>
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_l_a_kpi,
						$d->kode_l_a_kpi,
						$d->nama,
						$progress,
						$d->nama_konsep,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()).' - '.$d->tahun,
						$tanggal,
						$properties['tanggal'],
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->kode_c_kpi)
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_l_a_kpi');
				$data=$this->model_agenda->getLogAgendaKpi($id);
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_l_a_kpi,
						'kode_l_a_kpi'=>$d->kode_l_a_kpi,
						'kode_a_kpi'=>$d->kode_a_kpi,
						'nama'=>$d->nama,
						'nama_tabel'=>$d->nama_tabel,
						'nama_konsep'=>'<a href="'.base_url('pages/view_data_konsep_kpi/'.$this->codegenerator->encryptChar($d->kode_c_kpi)).'" target="blank" title="Klik untuk Detail">'.$d->nama_konsep.'</a>',
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'periode'=>$d->periode,
						'periode_view'=>(!empty($d->nama_periode)) ? ((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()) : $this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeAgendaSikap();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	//===LOG AGENDA KPI END===//
	//=================================================BLOCK CHANGE=================================================//
	//===LOG AGENDA SIKAP BEGIN===//
	public function log_agenda_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getListLogAgendaSikap();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_l_a_sikap,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel,'sikap');
					$progress='<div class="progress active">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					<b class="text-black">'.$count_p.' %</b>
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_l_a_sikap,
						$d->kode_l_a_sikap,
						$d->nama,
						$progress,
						$d->nama_konsep,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()).' - '.$d->tahun,
						$tanggal,
						$properties['tanggal'],
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->kode_c_sikap)
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_l_a_sikap');
				$data=$this->model_agenda->getLogAgendaSikap($id);
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_l_a_sikap,
						'kode_l_a_sikap'=>$d->kode_l_a_sikap,
						'kode_a_sikap'=>$d->kode_a_sikap,
						'nama'=>$d->nama,
						'nama_tabel'=>$d->nama_tabel,
						'nama_konsep'=>'<a href="'.base_url('pages/view_data_konsep_sikap/'.$this->codegenerator->encryptChar($d->kode_c_sikap)).'" target="blank" title="Klik untuk Detail">'.$d->nama_konsep.'</a>',
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'periode'=>$d->periode,
						'periode_view'=>(!empty($d->nama_periode)) ? ((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()) : $this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeAgendaSikap();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
//===LOG AGENDA SIKAP END===//
//=================================================BLOCK CHANGE=================================================//
//===LOG AGENDA REWARD BEGIN===//
	public function log_agenda_reward()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getListLogAgendaReward();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_l_a_reward,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel);
					$progress='<div class="progress active">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					<b class="text-black">'.$count_p.' %</b>
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_l_a_reward,
						$d->kode_l_a_reward,
						$d->nama,
						$progress,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()).' - '.$d->tahun,
						$tanggal,
						$properties['tanggal'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_l_a_reward');
				$data=$this->model_agenda->getLogAgendaReward($id);
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_l_a_reward,
						'kode_l_a_reward'=>$d->kode_l_a_reward,
						'kode_a_reward'=>$d->kode_a_reward,
						'nama'=>$d->nama,
						'nama_tabel'=>$d->nama_tabel,
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'periode_view'=>(!empty($d->nama_periode)) ? ((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()) : $this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeAgendaSikap();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
//===LOG AGENDA REWARD END===//
//=================================================BLOCK CHANGE=================================================//
//===LOG AGENDA KOMPETENSI BEGIN===//
//===LOG AGENDA KOMPETENSI END===//
//=================================================BLOCK CHANGE=================================================//
//===REPORT SIKAP BEGIN===//
	public function data_hasil_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getLogAgenda('log_agenda_sikap');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$tgl = $this->date;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_l_a_sikap,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel,'sikap');
					$progress2='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$count_p.' %" data-placement="right">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$keterangan = '';
					if ($count_p == 0) {
						$keterangan .= '<label class="label label-danger">Belum Ada Data</label>';
					}elseif ($count_p > 0 && $count_p < 100) {
						$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
					}else{
						$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
					}
					if ($keterangan != ''){
						$keterangan.='<br>';
					}
					if ($d->tgl_selesai < $this->otherfunctions->getDateNow('Y-m-d')) {
						$keterangan.='<label class="label label-danger">Waktu Agenda Sudah Habis</label>';
					}
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_l_a_sikap,
						$d->nama,
						$progress2,
						$tanggal,
						$keterangan,
						$this->codegenerator->encryptChar($d->kode_a_sikap),
						$d->tahun,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()),
						$this->codegenerator->encryptChar($d->kode_c_sikap),
					];
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_employee_result_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		parse_str($this->input->post('form'), $post_form);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		$id = $this->input->post('id_karyawan');
		$param = $this->input->post('param');
		$access=$this->codegenerator->decryptChar($this->input->post('access'));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
		$agenda=$this->model_agenda->getAgendaSikapKode($kode);
		if (!isset($agenda)) {
			$datax=$this->messages->notValidParam();
		}else{
			$tabel=$agenda['nama_tabel'];
			if (!empty($filter)) {
				$post_form['bagian_filter']=$filter;
			}
			$list_emp=$this->model_agenda->openTableAgenda($tabel, $post_form);
			if (isset($list_emp)) {
				foreach ($list_emp as $list) {
					$id_karyawan[$list->id_karyawan]=$list->id_karyawan;
					$data_emp[$list->id_karyawan]=[
						'nik'=>$list->nik,
						'nama'=>$list->nama,
						'nama_jabatan'=>$list->nama_jabatan,
						'bagian'=>$list->bagian,
						'nama_departemen'=>$list->nama_departemen,						
						'nama_loker'=>$list->nama_loker,						
					];
				}
			} 

			if ($usage == 'view_all' || $usage == 'view_one' || $usage == 'view_other') {
				$datax['data']=[];
				if (isset($id_karyawan)) {
					foreach ($id_karyawan as $idk) {
						$data=$this->model_agenda->rumusCustomKubotaFinalResultSikap($tabel,$idk,'advance_list');
						$no=1;

						if ($usage == 'view_all') {	
							$_add='';
							if (isset($data['nilai_kalibrasi'])) {
								if (!empty($data['nilai_kalibrasi'])) {
									if (isset($data['nilai_akhir'])) {
										$selisih=$data['nilai_akhir']-$data['nilai_kalibrasi'];
										if ($selisih < 0) {
											$_add.='<b style="color:green" title="Nilai Ditambah"> +'.$this->formatter->getNumberFloat(abs($selisih)).'</b>';	
										}elseif ($selisih > 0) {
											$_add.='<b style="color:red" title="Nilai Dikurangi"> -'.$this->formatter->getNumberFloat($selisih).'</b>';
										}
									}
								}
							}
							$_status='';
							if (isset($data['status_detail']['count_done']) && isset($data['status_detail']['count_all'])) {
								if ($data['status_detail']['count_done'] == 0) {
									$_status.='<i class="fa fa-times-circle text-red" data-toggle="tooltip" title="Belum Dinilai"></i>';
								}elseif ($data['status_detail']['count_done'] < $data['status_detail']['count_all']) {
									$_status.='<i class="fa fa-refresh fa-spin text-yellow" data-toggle="tooltip" title="Belum Selesai"></i>';
								}elseif ($data['status_detail']['count_done'] == $data['status_detail']['count_all']) {
									$_status.='<i class="fa fa-check-circle text-green" data-toggle="tooltip" title="Selesai"></i>';
								}
							}
							$aksi='<label class="label label-danger">Tidak Diizinkan</label>';
							if (in_array('EDT',$access['access']) && !$agenda['validasi']) {
								$aksi='<a href="javascript:void(0)" onclick=view_kalibrasi_one('.$idk.') data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-balance-scale" data-toggle="tooltips" title="Kalibrasi Nilai"></i></a>';
							}
							$datax['data'][]=[
								$idk,
								(isset($data_emp[$idk]['nik']))?$data_emp[$idk]['nik']:$this->otherfunctions->getMark(),
								(isset($data_emp[$idk]['nama']))?($_status.' '.$data_emp[$idk]['nama']):$this->otherfunctions->getMark(),
								(isset($data_emp[$idk]['nama_jabatan']))?$data_emp[$idk]['nama_jabatan']:$this->otherfunctions->getMark(),
								(isset($data_emp[$idk]['bagian']))?$data_emp[$idk]['bagian']:$this->otherfunctions->getMark(),
								(isset($data_emp[$idk]['nama_departemen']))?$data_emp[$idk]['nama_departemen']:$this->otherfunctions->getMark(),
								(isset($data_emp[$idk]['nama_loker']))?$data_emp[$idk]['nama_loker']:$this->otherfunctions->getMark(),
								((isset($data['status_detail']['count_all']))?('<a href="javascript:void(0)" style="color:black" class="btn btn-sm btn-default" onclick=view_partisipan('.$idk.')><i class="fa fa-users"></i> '.$data['status_detail']['count_all'].' Partisipan</a>'):$this->otherfunctions->getMark()),
								((isset($data['status_detail']['count_unfinish']))?('<a href="javascript:void(0)"  class="btn btn-sm btn-danger" onclick=view_belum('.$idk.')><i class="fa fa-users"></i> '.$data['status_detail']['count_unfinish'].' Partisipan</a>'):$this->otherfunctions->getMark()),
								((isset($data['status_detail']['count_done']))?('<a href="javascript:void(0)"  class="btn btn-sm btn-success" onclick=view_sudah('.$idk.')><i class="fa fa-users"></i> '.$data['status_detail']['count_done'].' Partisipan</a>'):$this->otherfunctions->getMark()),
								((isset($data['nilai_akhir']))?$this->formatter->getNumberFloat($data['nilai_akhir']):0).'<br>'.$_add,
								((isset($data['nilai_kalibrasi']))?$this->formatter->getNumberFloat($data['nilai_kalibrasi']):0),
								$aksi,
								$this->codegenerator->encryptChar($kode),
								$this->codegenerator->encryptChar($idk),
							];
						}elseif ($usage == 'view_one') {
							if (!empty($id)) {
								if ($idk  == $id) {
									$data_all=[];$data_unfinish=[];$data_done=[];
									if (isset($data['status_detail']['partisipan']) && isset($data['head_column'])) {
										foreach ($data['head_column'] as $head) {
											$sbg=$this->exam->getWhatIsPartisipan($head);
											if (isset($data['status_detail']['partisipan'][$head])) {
												foreach ($data['status_detail']['partisipan'][$head] as $d_p) {
													$d_p='[<b>'.$sbg.'</b>] '.$d_p;
													array_push($data_all,$d_p);
												}
											}
										}
									}
									if (isset($data['status_detail']['unfinish']) && isset($data['head_column'])) {
										foreach ($data['head_column'] as $head) {
											$sbg=$this->exam->getWhatIsPartisipan($head);
											if (isset($data['status_detail']['unfinish'][$head])) {
												foreach ($data['status_detail']['unfinish'][$head] as $d_p) {
													$d_p='[<b>'.$sbg.'</b>] '.$d_p;
													array_push($data_unfinish,$d_p);
												}
											}
										}
									}
									if (isset($data['status_detail']['done']) && isset($data['head_column'])) {
										foreach ($data['head_column'] as $head) {
											$sbg=$this->exam->getWhatIsPartisipan($head);
											if (isset($data['status_detail']['done'][$head])) {
												foreach ($data['status_detail']['done'][$head] as $d_p) {
													$d_p='[<b>'.$sbg.'</b>] '.$d_p;
													array_push($data_done,$d_p);
												}
											}
										}
									}
									$datax['data_all']='<div class="callout callout-danger"><i class="fa fa-info-circle"></i> Data Kosong</div>';
									$datax['data_unfinish']='<div class="callout callout-danger"><i class="fa fa-info-circle"></i> Data Kosong</div>';
									$datax['data_done']='<div class="callout callout-danger"><i class="fa fa-info-circle"></i> Data Kosong</div>';
									if (isset($data_all)) {
										$no_all=1;
										$datax['data_all']='';
										foreach ($data_all as $d_all) {
											$datax['data_all'].=(($no_all > 1) ? '<br>':'').$no_all.'. '.$d_all;
											$no_all++;				
										}
									}
									if (isset($data_unfinish)) {
										$no_unfinish=1;
										$datax['data_unfinish']='';
										foreach ($data_unfinish as $d_unfinish) {
											$datax['data_unfinish'].=(($no_unfinish > 1) ? '<br>':'').$no_unfinish.'. '.$d_unfinish;
											$no_unfinish++;				
										}
									}
									if (isset($data_done) && (count($data_done) > 0)) {
										$no_done=1;
										$datax['data_done']='';
										foreach ($data_done as $d_done) {
											$datax['data_done'].=(($no_done > 1) ? '<br>':'').$no_done.'. '.$d_done;
											$no_done++;				
										}
									}
									$datax['nama']=$data_emp[$idk]['nama'];
								}
							}
							
						}elseif ($usage == 'view_other') {
							if (!empty($id)) {
								if ($idk  == $id) {
									$datax=[
										'tabel'=>$tabel,
										'kode_agenda'=>$kode,
										'id_karyawan'=>$id,
									];
									$datax['nama']=$data_emp[$idk]['nama'];
									if (isset($data['nilai_kalibrasi'])) {
										$datax['nilai_kalibrasi']=$data['nilai_kalibrasi'];
									}								
								}
							}
						}
						$no++;
					}
					
				}
				echo json_encode($datax);	
			}elseif ($usage == 'kalibrasi_all') {
				if (isset($id_karyawan)) {
					$datax=['tabel'=>$tabel];
					$datax['karyawan']=[];
					foreach ($id_karyawan as $idk) {
						$data=$this->model_agenda->rumusCustomKubotaFinalResultSikap($tabel,$idk);
						if (isset($data['nilai_akhir'])) {
							$nilai[$idk]=$data['nilai_akhir'];
						}
						array_push($datax['karyawan'],['id'=>$idk,'text'=>((isset($data_emp[$idk]['nama']) && isset($data_emp[$idk]['nama_jabatan']))?$data_emp[$idk]['nama'].' ('.$data_emp[$idk]['nama_jabatan'].')':$this->otherfunctions->getMark())]);
					}
					$datax['nilai_akhir']=(isset($nilai))?$this->codegenerator->encryptChar($nilai):null;										
					echo json_encode($datax);		
				}
			}elseif ($usage == 'get_property') {
				$datax=[];
				if (isset($id_karyawan)) {
					$datax['periode']=$agenda['nama_periode'];
					$datax['tahun']=$agenda['tahun'];
					$rekap_nilai['periode']=$agenda['nama_periode'];
					$rekap_nilai['tahun']=$agenda['tahun'];
					foreach ($id_karyawan as $idk) {
						$data=$this->model_agenda->rumusCustomKubotaFinalResultSikap($tabel,$idk,'advance_list');
						if (isset($data['nilai_akhir'])) {
							$rekap_nilai['rekap'][$idk]=[
								'nik'=>$data_emp[$idk]['nik'],
								'nama'=>$data_emp[$idk]['nama'],
								'nama_jabatan'=>$data_emp[$idk]['nama_jabatan'],
								'bagian'=>$data_emp[$idk]['bagian'],
								'nama_loker'=>$data_emp[$idk]['nama_loker'],
								'nama_departemen'=>$data_emp[$idk]['nama_departemen'],
								'nilai_akhir'=>$data['nilai_akhir'],
								'nilai_kalibrasi'=>$data['nilai_kalibrasi'],
								'nilai_ats'=>(isset($data['sum_value']['ATS']) ? $data['sum_value']['ATS'] : null),
								'nilai_bwh'=>(isset($data['sum_value']['BWH']) ? $data['sum_value']['BWH'] : null),
								'nilai_rkn'=>(isset($data['sum_value']['RKN']) ? $data['sum_value']['RKN'] : null),
							];
						}

						if (isset($data['status_detail']['unfinish']) && isset($data['head_column'])) {
							foreach ($data['head_column'] as $head) {
								$sbg=$this->exam->getWhatIsPartisipan($head,['icon'=>false]);
								if (isset($data['status_detail']['unfinish'][$head])) {
									foreach ($data['status_detail']['unfinish'][$head] as $id_p=>$d_p) {
										$data_k=$this->model_karyawan->getEmployeeId($id_p);
										$d_p='['.strtoupper($sbg).'] '.$d_p;
										$rekap_nilai['partisipan'][]=[	
											'nik'=>$data_k['nik'],
											'nama'=>$d_p,
											'nama_jabatan'=>$data_k['nama_jabatan'],
											'bagian'=>$data_k['bagian'],
											'nama_departemen'=>$data_emp[$idk]['nama_departemen'],
											'nama_loker'=>$data_k['nama_loker'],
											'belum_menilai'=>$data_emp[$idk]['nama'].' ('.$data_emp[$idk]['nama_jabatan'].')',
										];
									}
								}
							}
						}
					}
					$datax['rekap_nilai']=(isset($rekap_nilai))?$this->codegenerator->encryptChar($rekap_nilai):null;										
					
				}
				echo json_encode($datax);
			}
		}
	}	
	public function report_value_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->input->post('kode_agenda'));
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		$id_en=$this->codegenerator->decryptChar($this->input->post('id'));
		$cek=$this->model_agenda->getAgendaSikapKode($kode);
		if ($usage == null || empty($cek)) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$datax=[];
				$data=$this->model_agenda->rumusCustomKubotaFinalResultSikap($table,$id_en,'report');
				$table='<table class="table">
				<thead>
				<tr class="bg-green">
				<th>No.</th>
				<th>Nama Aspek Sikap</th>
				<th>Bobot</th>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$name_col=$this->exam->getWhatIsPartisipan($head);
						$table.='<th class="text-center" colspan="2">'.$name_col.'<br>('.((isset($data['bobot_column'][$head]))?$data['bobot_column'][$head]:0).'%)</th>';
					}
				}
				$table .='</tr>
				<tr>
				<th colspan="3" class="bg-green"></th>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$table.='<th class="text-center bg-blue">Capaian</th><th class="text-center bg-yellow">Nilai</th>';
					}
				}
				$table .='</tr></thead>
				</tbody>';
				if (isset($data['list_aspek'])) {
					$no=1;
					foreach ($data['list_aspek'] as $k_l => $v_l) {
						$table.='<tr><td>'.$no.'</td>
						<td><a href="'.base_url('pages/report_detail_sikap/'.$this->codegenerator->encryptChar($kode).'/'.$this->codegenerator->encryptChar($k_l).'/'.$this->codegenerator->encryptChar($id_en)).'" data-toggle="tooltip" title="Klik Untuk Detail">'.$v_l.'</a></td>
						<td>'.((isset($data['bobot_aspek'][$k_l]))?$data['bobot_aspek'][$k_l]:0).'%</td>';
						if (isset($data['head_column'])) {
							foreach ($data['head_column'] as $head) {
								$name_index=$this->exam->getWhatColPartisipan($head,'na');
								$capaian=(isset($data['capaian'][$k_l][$head]))?$data['capaian'][$k_l][$head]:0;
								$nilai=(isset($data[$name_index][$k_l]))?$data[$name_index][$k_l]:0;
								$table.='<td class="text-center">'.$this->formatter->getNumberFloat($capaian).'</td>
								<td class="text-center bg-warning">'.$this->formatter->getNumberFloat($nilai).'</td>';
							}
						}
						$table.='</tr>';
						$no++;
					}
				}				
				$table.= '<tr>
				<td colspan="3" class="text-center bg-aqua"><b>Nilai Total</b></td>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$nilai_total=(isset($data['sum_value'][$head]))?$data['sum_value'][$head]:0;
						$table.= '<td class="text-center bg-aqua"></td>
						<td class="text-center bg-warning"><b>'.$this->formatter->getNumberFloat($nilai_total).'</b></td>';
					}
				}
				$table.= '</tr><tr><td colspan="3" class="text-center bg-aqua"></td>';
				$col=2;
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$nilai_terbobot=(isset($data['value_bobot'][$head]))?$data['value_bobot'][$head]:0;
						$table.= '<td class="text-center bg-navy">Nilai x '.((isset($data['bobot_column'][$head]))?$data['bobot_column'][$head]:0).'%</td>
						<td class="text-center bg-yellow"><b>'.$this->formatter->getNumberFloat($nilai_terbobot).'</b></td>';
						$col=$col+2;
					}
				}
				$nilai_akhir=(isset($data['nilai_akhir']))?$data['nilai_akhir']:0;
				$nilai_akhir_old=(isset($data['nilai_akhir']))?$data['nilai_akhir']:0;
				$kalibrasi_stat=(empty($data['nilai_kalibrasi']))?false:true;
				$getKonversi=$this->model_master->getKonversiSikapVal($nilai_akhir);
				$color=(isset($getKonversi['warna']))?$getKonversi['warna']:null;
				$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
				$t_color=($color=='grey')?'black':'white';
				$table.= '</tr>
				<tr><td style="font-size:16pt" class="bg-blue text-center" colspan="'.$col.'"><b>Nilai Akhir</b></td><td class="text-center" style="font-size:16pt;background-color:'.$color.';color:'.$t_color.'">'.$this->formatter->getNumberFloat($nilai_akhir).'</td></tr>
				</tbody></table>';
				$nilai_akhir=(empty($data['nilai_kalibrasi']))?$nilai_akhir:$data['nilai_kalibrasi'];
				$getKonversi=$this->model_master->getKonversiSikapVal($nilai_akhir);
				$color=(isset($getKonversi['warna']))?$getKonversi['warna']:null;
				$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
				$t_color=($color=='grey')?'black':'white';
				$tbl_nilai = '	<table class="table table-bordered">
				<tr>
				<th class="text-center bg-yellow" style="font-size:16pt">Nilai Akhir Sikap</th>
				</tr>
				<tr>
				<th class="text-center" style="font-size:30pt;background-color:'.$color.';color:'.$t_color.'">'.$this->formatter->getNumberFloat($nilai_akhir).'</th>
				</tr>
				<tr>
				<th class="text-center" style="font-size:16pt">'.$nama.'</th>
				</tr>';
				if ($kalibrasi_stat && $data['nilai_kalibrasi'] != $nilai_akhir_old){
					$kalibrasi_value=$data['nilai_kalibrasi']-$nilai_akhir_old;
					if ($kalibrasi_value < 0) {
						$kalibrasi_value='<b class="err">(-) '.$this->formatter->getNumberFloat(abs($kalibrasi_value)).'</b>';
					}elseif ($kalibrasi_value > 0) {
						$kalibrasi_value='<b style="color:#006303">(+) '.$this->formatter->getNumberFloat($kalibrasi_value).'</b>';
					}
					$tbl_nilai.='<tr>
						<th class="bg-aqua text-center" style="font-size:14pt">Nilai Dikalibrasi '.$kalibrasi_value.'</th>
					</tr>';
				}
				$tbl_nilai.='</table>';
				$datax=[
					'table_view'=>$table,
					'nama_agenda'=>$cek['nama'],
					'tahun_agenda'=>$cek['tahun'],
					'periode_agenda'=>((isset($this->model_master->getListPeriodePenilaianActive()[$cek['periode']])) ? $this->model_master->getListPeriodePenilaianActive()[$cek['periode']] : $this->otherfunctions->getMark()),
					'nilai_akhir'=>$tbl_nilai,
				];
				echo json_encode($datax);
			}elseif ($usage == 'view_konversi') {
				$data=$this->model_master->getListKonversiSikap();
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$datax['data'][]=[
						$d->nama,
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						(!empty($d->warna)) ? '<i class="fa fa-circle" style="color:'.$d->warna.'"></i>' :$this->otherfunctions->getMark()
					];
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function report_detail_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		$kode_aspek=$this->codegenerator->decryptChar($this->uri->segment(5));
		$id=$this->codegenerator->decryptChar($this->uri->segment(6));
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$getAgenda=$this->model_agenda->getAgendaSikapKode($kode);
				$nama_agenda=$getAgenda['nama'];
				$tahun_agenda=$getAgenda['tahun'];
				$periode_agenda=$getAgenda['periode'];
				$tabel=$getAgenda['nama_tabel'];
				$data=$this->model_agenda->getTabelSikapId($tabel,$id,$kode_aspek);
				$datax['data']=[];
				$n=1;
				foreach ($data as $d) {
					$asp[$d->kode_aspek]=$d->kode_aspek;
					$kuis[$n]=$d->kuisioner;
					$rt_ats[$n]=$d->rata_atas;
					$rt_bwh[$n]=$d->rata_bawah;
					$rt_rkn[$n]=$d->rata_rekan;
					$n_atas[$n]=$d->nilai_atas;
					$n_bawah[$n]=$d->nilai_bawah;
					$n_rkn[$n]=$d->nilai_rekan;
					$n_dri[$n]=$d->nilai_diri;
					$k_dri[$n]=$d->keterangan_diri;
					$k_ats[$n]=$d->keterangan_atas;
					$k_bwh[$n]=$d->keterangan_bawah;
					$k_rkn[$n]=$d->keterangan_rekan;
					$part1[$d->partisipan]=$d->partisipan;
					$sb_atas[$n]=$d->sub_bobot_ats;
					$n++;
				}
				$pp1=implode('', $part1);
				$part=explode(';', $pp1);
				foreach ($part as $px) {
					$px1=explode(':', $px);
					$px2[]=$px1[0];
				}
				$ppx1=implode(';', array_unique($px2));
				$ppx=explode(';', $ppx1);
				$rata_rata='';
				foreach ($ppx as $aa) {
					$a1=explode(':', $aa);
					if ($a1[0] == "ATS") {
						$ns=1;
						foreach ($rt_ats as $rtaa) {
							if ($rtaa != 0) {
								$rt1[$ns]=$rtaa;
							}
							$ns++;
						}
						if (isset($rt1)) {
							$rta=array_sum($rt1)/count($rt1);
							$rata_rata.='<tr>
							<th class="bg-blue text-center">Rata-Rata Atasan</th>
							<td class="text-center">'.number_format($rta,2,',',',').'</td>
							</tr>';
						}
					}
					if ($a1[0] == "BWH") {
						$ns1=1;
						foreach ($rt_bwh as $rtab) {
							if ($rtab != 0) {
								$rt1b[$ns1]=$rtab;
							}
							$ns1++;
						}
						if (isset($rt1b)) {
							$rtab=array_sum($rt1b)/count($rt1b);
							$rata_rata.='<tr>
							<th class="bg-blue text-center">Rata-Rata Bawahan</th>
							<td class="text-center">'.number_format($rtab,2,',',',').'</td>
							</tr>';
						}
					}
					if ($a1[0] == "RKN") {
						$ns2=1;
						foreach ($rt_rkn as $rtar) {
							if ($rtar != 0) {
								$rt1r[$ns2]=$rtar;
							}
							$ns2++;
						}
						if (isset($rt1r)) {
							$rtar=array_sum($rt1r)/count($rt1r);
							$rata_rata.='<tr>
							<th class="bg-blue text-center">Rata-Rata Rekan</th>
							<td class="text-center">'.number_format($rtar,2,',',',').'</td>
							</tr>';
						}
					}
				}
				$nama_part='';
				$nama_part.='<th>No.</th>
				<th>Kuisioner</th>';
				foreach ($part as $p) {
					$p1=explode(':', $p);
					$ikd=$p1[1];
					$kr=$this->model_karyawan->getEmployeeId($ikd);
					if ($p1[0] == 'DRI') {
						$nama_part.='<th style="font-size:8pt;" class="text-center" colspan="2">'.$kr['nama'].'<br>(<i class="fa fa-user"></i> Diri Sendiri)</th>';
					}
					if ($p1[0] == 'ATS') {
						if (!empty($sb_atas[0]) || !empty($sb_atas[1])){
							$nama_part.='<th style="font-size:8pt;" class="text-center" colspan="3">'.$kr['nama'].'<br>(<i class="fa fa-star"></i> Atasan)</th>';
						}else{
							$nama_part.='<th style="font-size:8pt;" class="text-center" colspan="2">'.$kr['nama'].'<br>(<i class="fa fa-star"></i> Atasan)</th>';
						}
					}
					if ($p1[0] == 'BWH') {
						$nama_part.='<th style="font-size:8pt;" class="text-center" colspan="2">'.$kr['nama'].'<br>(Bawahan)</th>';
					}
					if ($p1[0] == 'RKN') {
						$nama_part.='<th style="font-size:8pt;" class="text-center" colspan="2">'.$kr['nama'].'<br>(Rekan)</th>';
					}
				}
				$ket_part='';
				$ket_part.='<th class="bg-green" colspan="2"></th>';
				foreach ($part as $p) {
					$p1=explode(':', $p);
					$ikd=$p1[1];
					if ($p1[0] == 'ATS') {
						if (!empty($sb_atas[0]) || !empty($sb_atas[1])){
							$ket_part.='<th class="text-center bg-yellow">Capaian</th>';
						}
					}
					$ket_part.='<th class="text-center bg-blue">Nilai</th>
					<th class="text-center bg-aqua">Keterangan</th>';
				}
				$kuisi='';
				$n=1;
				foreach ($kuis as $k) {
					$kuisi.='<tr>
					<td>'.$n.'.</td>
					<td width="50%">'.$k.'</td>';
					$xc=0;
					foreach ($part as $pp) {
						$pp1=explode(':', $pp);
						if ($pp1[0] == "DRI") {
							$kuisi.='<td class="text-center bg-info">'.$n_dri[$n].'</td>
							<td>'.$k_dri[$n].'</td>';
							$no[$pp][]=$n_dri[$n];
						}
						if ($pp1[0] == "ATS") {
							$idx = $pp1[1];
							$nx[$n]=array_filter(explode(';', $n_atas[$n]));
							foreach ($nx[$n] as $nn) {
								$ne[$n]=explode(':', $nn);
								$ni[$n][$ne[$n][0]]=$ne[$n][1];
							}
							$nx1[$n]=array_filter(explode(';', $k_ats[$n]));
							foreach ($nx1[$n] as $nn1) {
								$ne1[$n]=explode(':', $nn1);
								$ni1[$n][$ne1[$n][0]]=$ne1[$n][1];
							}
							if (isset($ni[$n][$pp1[1]])) {
								$na[$n]=$ni[$n][$pp1[1]];
								if (isset($ni1[$n][$pp1[1]])) {
									$ke[$n]=$ni1[$n][$pp1[1]];
								}else{
									$ke[$n]='<label class="label label-default">Tidak Ada Komentar</label>';
								}
							}else{
								$na[$n]=0;
								$ke[$n]='<label class="label label-default">Tidak Ada Komentar</label>';
							}
							$n_at=$this->exam->getPartisipantId($n_atas[$n]);
							$sb_at=$this->exam->getPartisipantId($sb_atas[$n]);
							$nil=[];
							if (isset($n_at)) {
								foreach ($n_at as $k_nx => $v_nx) {
									if(empty($sb_at[$k_nx]) || $sb_at[$k_nx]==0){
										$nil[$k_nx]=0;
									}else{
										$nil[$k_nx]=$v_nx*($sb_at[$k_nx]/100);
									}
								}
							}
							
							if (!empty($sb_atas[$n])){
								$kuisi.='<td>'.$na[$n].'</td>';
								$kuisi.='<td class="text-center bg-info">'.((isset($nil[$idx]))?$nil[$idx]:0).'</td>';
								$kuisi.='<td>'.$ke[$n].'</td>';
								$no[$pp][]=((isset($nil[$idx]))?$nil[$idx]:0);
							}else{
								$kuisi.='<td class="text-center bg-info">'.$na[$n].'</td>
								<td>'.$ke[$n].'</td>';
								$no[$pp][]=$na[$n];
							}
						}
						if ($pp1[0] == "BWH") {
							$nxb[$n]=array_filter(explode(';', $n_bawah[$n]));
							foreach ($nxb[$n] as $nnb) {
								$neb[$n]=explode(':', $nnb);
								$nib[$n][$neb[$n][0]]=$neb[$n][1];
							}
							$nx1b[$n]=array_filter(explode(';', $k_bwh[$n]));
							foreach ($nx1b[$n] as $nn1b) {
								$ne1b[$n]=explode(':', $nn1b);
								$ni1b[$n][$ne1b[$n][0]]=$ne1b[$n][1];
							}
							if (isset($nib[$n][$pp1[1]])) {
								$nab[$n]=$nib[$n][$pp1[1]];
								if (isset($ni1b[$n][$pp1[1]])) {
									$keb[$n]=$ni1b[$n][$pp1[1]];
								}else{
									$keb[$n]='<label class="label label-default">Tidak Ada Komentar</label>';
								}
							}else{
								$nab[$n]=0;
								$keb[$n]='<label class="label label-default">Tidak Ada Komentar</label>';

							}
							$kuisi.='<td class="text-center bg-info">'.$nab[$n].'</td>
							<td>'.$keb[$n].'</td>';
							$no[$pp][]=$nab[$n];
						}
						if ($pp1[0] == "RKN") {
							$nxr[$n]=array_filter(explode(';', $n_rkn[$n]));
							foreach ($nxr[$n] as $nnr) {
								$ner[$n]=explode(':', $nnr);
								$nir[$n][$ner[$n][0]]=$ner[$n][1];
							}
							$nx1r[$n]=array_filter(explode(';', $k_rkn[$n]));
							foreach ($nx1r[$n] as $nn1r) {
								$ne1r[$n]=explode(':', $nn1r);
								$ni1r[$n][$ne1r[$n][0]]=$ne1r[$n][1];
							}
							if (isset($nir[$n][$pp1[1]])) {
								$nar[$n]=$nir[$n][$pp1[1]];

								if (isset($ni1r[$n][$pp1[1]])) {
									$ker[$n]=$ni1r[$n][$pp1[1]];
								}else{
									$ker[$n]='<label class="label label-default">Tidak Ada Komentar</label>';
								}
							}else{
								$nar[$n]=0;
								$ker[$n]='<label class="label label-default">Tidak Ada Komentar</label>';

							}
							$kuisi.='<td class="text-center bg-info">'.$nar[$n].'</td>
							<td>'.$ker[$n].'</td>';
							$no[$pp][]=$nar[$n];
						}
						$xc++;
					}
					$kuisi.='<tr>';
					$n++;
				}
				$kuisi.='<tr>
				<td colspan="2" class="text-center bg-navy"><b>Rata - Rata</b></td>';
				$xc = 0;
				foreach ($part as $o) {
					$p1=explode(':', $o);
					$ikd=$p1[1];
					if ($p1[0] == 'ATS' && !empty($sb_atas[1])) {
						if (!empty($sb_atas[1])){
							$sb_ata=$this->exam->getPartisipantId($sb_atas[1]);
							$vall=[];
							$xx= 0;
							foreach ($sb_ata as $key_sb => $val_sb) {
								$vall[$xx]=$val_sb;
								$xx++;
							}
							$kuisi.='<td class="text-center bg-yellow"><b>Capaian x '.number_format($vall[$xc]). '%</b></td>
							<td class="text-center bg-blue"><b>'.number_format((array_sum($no[$o])/count($no[$o])),2,',',',').'</b></td>
							<td class="text-center bg-navy"></td>';
						}
						else{
							$kuisi.='<td class="text-center bg-blue"><b>'.number_format((array_sum($no[$o])/count($no[$o])),2,',',',').'</b></td>
							<td class="text-center bg-navy"></td>';
						}
					}else{
						$kuisi.='<td class="text-center bg-blue"><b>'.number_format((array_sum($no[$o])/count($no[$o])),2,',',',').'</b></td>
						<td class="text-center bg-navy"></td>';
					}
					$xc++;
				}
				$kuisi.='</tr>';
				$datax=[
					'rata_rata'=>$rata_rata,
					'nama_part'=>$nama_part,
					'ket_part'=>$ket_part,
					'kuisi'=>$kuisi,
				];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
//saran sikap
	public function saran_penilaian()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id = $this->input->post('id_karyawan');
				$kode_periode = $this->input->post('kode_periode');
				$tahun = $this->input->post('tahun');
				$data=$this->model_agenda->getListSaran($id,$kode_periode,$tahun);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_saran,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$admin = $this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
					if($admin['id_admin'] == $d->id_admin){
						$delete = '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_saran.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
						$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_saran.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					}else{
						$delete = '';
						$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_saran.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					}
					$aksi=$info.$delete;
					$pengirim = $this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
					$words = explode(" ",$d->saran);
					$datax['data'][]=[
						$d->id_saran,
						($d->nama_admin)?$d->nama_admin:$d->nama_karyawan,
						(implode(" ",array_splice($words,0,10))),
						$aksi,
						$d->saran,
						count($words),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_saran');
				$data=$this->model_agenda->getListSaranId($id);
				$admin = $this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
				foreach ($data as $d) {
					$acc_edit=false;
					if($admin['id_admin'] == $d->id_admin){
						$acc_edit=true;
					}
					$datax=[
						'id'=>$d->id_saran,
						'id_pengirim'=>$d->id_karyawan,
						'nama_pengirim'=>$d->nama_pengirim,
						'id_penerima'=>$d->untuk,
						'nama_penerima'=>$d->nama_penerima,
						'jenis'=>$d->jenis_saran,
						'jenisview'=>$this->otherfunctions->getJenisSaran($d->jenis_saran),
						'saran'=>$d->saran,
						'acc_edit'=>$acc_edit,
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
	public function saran_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id = $this->input->post('id_karyawan');
				$periode = $this->input->post('kode_periode');
				$tahun = $this->input->post('tahun');
				if (!empty($kode_periode)) {
					$getTable=$this->model_agenda->getLogAgendaSikapPeriode($periode,$tahun);
				}else{
					$getTable=$this->model_agenda->getLogAgendaSikapTahun($tahun);
				}
				$tabel = [];
				foreach ($getTable as $g) {
					$tabel[] = $g->nama_tabel;
				}
				$datax['data']=[];
				if(isset($tabel)){
					foreach ($tabel as $tbb){
						$data=$this->model_agenda->openTableAgendaIdEmployee($tbb,$id);
						$access=$this->codegenerator->decryptChar($this->input->post('access'));
						if(isset($data)){
							foreach ($data as $d) {
								$var=[
									'id'=>$d->id_task,
									'access'=>$access,
								];
								$ket = ['keterangan_atas','keterangan_bawah','keterangan_rekan'];
								foreach ($ket as $kk => $kv) {
									$get_ket = $this->otherfunctions->getParseVar($d->$kv);
									$keterangan = (!empty($get_ket)) ? $get_ket : [];
									foreach ($keterangan as $kek => $kev) {
										$emp = $this->model_karyawan->getEmployeeId($kek);
										$asp = $this->model_master->getAspekKode($d->kode_aspek);
										if ($kv == 'keterangan_atas'){
											$sebagai = 'Atasan';
										}elseif ($kv == 'keterangan_bawah') {
											$sebagai = 'Bawahan';
										}elseif ($kv == 'keterangan_rekan') {
											$sebagai = 'Rekan';
										}
										$datax['data'][]=[
											$d->id_task,
											$asp['nama'],
											$d->kuisioner,
											$kev,
										];
									}
								}
							}
						}
					}
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_saran');
				$data=$this->model_agenda->getListSaranId($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_saran,
						'id_pengirim'=>$d->id_karyawan,
						'nama_pengirim'=>$d->nama_pengirim,
						'id_penerima'=>$d->untuk,
						'nama_penerima'=>$d->nama_penerima,
						'jenis'=>$d->jenis_saran,
						'jenisview'=>$this->otherfunctions->getJenisSaran($d->jenis_saran),
						'saran'=>$d->saran,
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
//===REPORT SIKAP END===//
//=================================================BLOCK CHANGE=================================================//
	

//===REPORT KPI BEGIN===//
	public function data_hasil_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$this->data_input_kpi();
	}
	public function view_employee_result_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$this->view_employee_kpi();
	}
//===REPORT KPI END===//
//=================================================BLOCK CHANGE=================================================//
//===REPORT GROUP BEGIN===//
	public function data_hasil_group()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getReportGroupList();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$periode=$this->model_master->getListPeriodePenilaianActive();
					$datax['data'][$no-1]=[
						$no,
						'<a href="'.base_url('pages/view_employee_result_group/'.$this->codegenerator->encryptChar($d)).'">Raport Gabungan Tahun '.$d.'</a>',
					];	
					foreach ($periode as $k_p=>$p) {
						$link=$this->codegenerator->encryptChar($k_p.'-'.$d);
						array_push($datax['data'][$no-1],'<a href="'.base_url('pages/view_employee_result_group/'.$link).'" class="btn btn-warning"><i class="fa fa-line-chart"></i> Lihat Nilai '.$p.' '.$d.'</a>');
					}				
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_employee_result_group()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->codegenerator->decryptChar($this->input->post('data'));
		if (empty($kode)) {
			$datax=$this->messages->notValidParam();
		}else{
			parse_str($this->input->post('form'), $post_form);
			$access=$this->codegenerator->decryptChar($this->input->post('access'));
			$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
			if (!empty($filter)) {
				$post_form['bagian_filter']=$filter;
			}
			if($this->uri->segment(3) == 'tahunan'){
				$dt=$this->model_agenda->getListRaportTahunanHistory($kode, $post_form);
				$datax['data']=[];
				if(isset($dt)){
					foreach ($dt as $d){
						$kode['id']=$d->id_karyawan;
						$kode['kode_periode']=null;
						$auto_rank='';
						if($d->auto_rank_up_old){
							$auto_rank.='<br><label class="label label-default">Rank Up Otomatis</label>';
						}
						$ar1=[
							$d->id_karyawan,
							$d->nik,
							$d->nama,
							$d->nama_jabatan,
							$d->nama_bagian,
							$d->nama_departement,
							$d->nama_loker
						];
						$ar2=[];
						$data_p=$this->model_master->getListPeriodePenilaian(1);
						if (isset($data_p)) {
							$cn=1;
							foreach ($data_p as $dp){
								$cols='q_'.$dp->kode_periode;
								$ar2[$cn]=(($d->$cols)?$this->formatter->getNumberFloat($d->$cols):0);
								$cn++;
							}
						}
						$ar3=[
							$this->codegenerator->encryptChar($kode),
						];
						$datax['data'][]=array_merge($ar1,$ar2,$ar3);
					}
				}
			}else{				
				$datax['data']=$this->model_agenda->getListEmployeeReportGroup($kode, $post_form);
			}
		}
		echo json_encode($datax);
	}
	public function view_employee_result_insentif()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->codegenerator->decryptChar($this->input->post('data'));
		parse_str($this->input->post('form'), $post_form);
		$access=$this->codegenerator->decryptChar($this->input->post('access'));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
		if (!empty($filter)) {
			$post_form['bagian_filter']=$filter;
		}
		if (!empty($kode)) {
			$datax['data']=[];
			$data=$this->model_agenda->getListEmployeeInsentif($kode,$post_form);
			if (isset($data)) {
				foreach ($data as $id_karyawan => $d) {
					if (isset($d[7])) {
						$d[7]=$this->formatter->getNumberFloat($d[7]);
					}
					$datax['data'][]=array_values($d);
				}
			}
			
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function report_value_group()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->codegenerator->decryptChar($this->input->post('data'));
		if (!isset($kode)) {
			$datax=$this->messages->notValidParam();
		}else{
			$param_max=(empty($kode['kode_periode']))?'tahunan':'periode';
			$table='<div class="callout callout-danger">
			<label><i class="fa fa-times-circle"></i> Data Kosong</label><br>
			Tidak Ada Data yang Ditampilkan
			</div>';
			$data=$this->model_agenda->getReportGroupEmployee($kode['kode_periode'],$kode['tahun'],$kode['id']);
			$nilai_akhir=0;
			$max_periode=$this->model_master->getMaxPeriode($param_max);
			$usage_presensi=($param_max == 'tahunan')?'tahunan':'kuartal';
			$data_presensi=$this->model_presensi->rumusCustomKubotaFinalResultPresensi($kode['id'],$kode['kode_periode'],$kode['tahun'],$usage_presensi);
			$nilai_kpi=($data['kpi'] != '')?$data['kpi']:0;
			$nilai_sikap=0;
			if (!empty($data['sikap'])) {
				foreach ($data['sikap'] as $kode_aspek => $nilai) {
					$nilai_sikap=$nilai_sikap+array_sum($nilai['nilai']);
				}
			}
			$nilai_kpi_terbobot=$nilai_kpi*($data['bobot_kpi']/100);
			$nilai_sikap_terbobot=$nilai_sikap*($data['bobot_sikap']/100);
			$nilai_akhir=($nilai_kpi_terbobot + $nilai_sikap_terbobot)-$data_presensi['nilai_akhir'];
			if(isset($kode['kode_periode'])){
				$r_kuartal=$this->model_master->getKonversiKuartalNilai($nilai_akhir);
				$color=(isset($r_kuartal['warna']))?$r_kuartal['warna']:null;
				$nama=(isset($r_kuartal['nama']))?$r_kuartal['nama']:'Unknown';
				$alert_tahunan='';
			}else{
				$r_tahunan=$this->model_master->getKonversiTahunanNilai($nilai_akhir);
				$color=(isset($r_tahunan['warna']))?$r_tahunan['warna']:null;
				$nama=(isset($r_tahunan['nama']))?$r_tahunan['nama']:null;
				$alert_tahunan='<div class="row">
				<div class="col-md-12">
					<div class="callout callout-info"><i class="fa fa-info-circle"></i> Hanya Agenda yang <b>SUDAH DIVALIDASI</b> saja yang akan masuk dalam Raport Tahunan </div>
				</div>
			</div>';
			}
			$table=$alert_tahunan.'
			<div class="row">
				<div class="col-md-12">
					<h3><i class="fa fa-trophy text-yellow"></i> Aspek Penilaian Kinerja</h3>
					<table class="table table-stripped table-hover" style="font-size:14pt">
						<thead>
							<tr class="bg-navy">
								<th class="text-center">Aspek Penilaian</th>
								<th class="text-center">Bobot</th>
								<th class="text-center">Aktual</th>
								<th class="text-center">Nilai Akhir</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>KPI Output</td>
								<td class="text-center">'.$data['bobot_kpi'].'%</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($nilai_kpi).'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($nilai_kpi_terbobot).'</td>
							</tr>
							<tr>
								<td>Aspek Sikap 360°</td>
								<td class="text-center">'.$data['bobot_sikap'].'%</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($nilai_sikap).'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($nilai_sikap_terbobot).'</td>
							</tr>
							
							<tr>
								<td colspan="3" class="bg-info text-center"><b style="font-size:16pt">TOTAL</b></td>
								<td class="text-center bg-blue"><b style="font-size:16pt">'.$this->formatter->getNumberFloat(($nilai_kpi_terbobot+$nilai_sikap_terbobot)).'</b></td>
							</tr>
							<tr>
								<td colspan="3">Kedisiplinan (Pengurang)</td>
								<td class="text-center" style="color:red">'.$this->formatter->getNumberFloat($data_presensi['nilai_akhir']).'</td>
							</tr>
							<tr>
								<td colspan="3" class="bg-info text-center"><b style="font-size:16pt">NILAI AKHIR</b></td>
								<td class="text-center bg-blue"><b style="font-size:25pt">'.$this->formatter->getNumberFloat($nilai_akhir).'</b></td>
							</tr>';
						$table.='</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8">
					<h3><i class="fa fa-calendar-check text-yellow"></i> Kedisiplinan (Pengurang)</h3>
					<table class="table table-stripped table-hover">
						<thead>
							<tr class="bg-red">
								<th class="text-center">Jenis Kedisiplinan</th>
								<th class="text-center">Aktual</th>
								<th class="text-center">Terkonversi</th>
							</tr>
						</thead>
						<tbody>';
						foreach ($this->otherfunctions->getJenisPresensiList() as $k_pre => $v_pre) {
							$k_pre=strtolower($k_pre);
							$table.='<tr>
								<td>'.$v_pre.'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($data_presensi[$k_pre]).'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($data_presensi[$k_pre.'_konv']).'</td>
							</tr>';
						}
						$table.='<tr>
							<td colspan="2" class="bg-warning text-center"><b style="font-size:16pt">TOTAL</b></td>
							<td class="text-center bg-yellow"><b style="font-size:16pt;color:red">'.$this->formatter->getNumberFloat($data_presensi['nilai_akhir']).'</b></td>
						</tr>';
						$table.='</tbody>
					</table>
				</div>
				<div class="col-md-4">
					<h3><i class="fa fa-line-chart text-yellow"></i> Nilai Akhir</h3>
					<table class="table table-bordered">
						<tr>
							<th class="text-center" id="nilai_akhir" style="font-size:50pt;background-color:'.$color.';color:gray">'.$this->formatter->getNumberFloat($nilai_akhir).'</th>
						</tr>
						<tr>
							<th class="text-center" id="konversi_akhir" style="font-size:20pt">'.$nama.'</th>
						</tr>
					</table>
				</div>
			</div>';
			
			$emp = $this->model_karyawan->getEmployeeId($kode['id']);
			$data_print = [
				'id'=>$kode['id'],
				'nik'=>$emp['nik'],
				'nama'=>$emp['nama'],
				'jabatan'=>$emp['nama_jabatan'],
				'bagian'=>$emp['bagian'],
				'loker'=>$emp['nama_loker'],
				'departement'=>$emp['nama_departement'],
				'nilai'=>$this->formatter->getNumberFloat($nilai_akhir),
				'keterangan'=>$nama
			];
			$profile = $this->codegenerator->encryptChar($data_print);
			$table_print = [
				'rekap' =>[
					'table_print'=>$this->codegenerator->encryptChar($table),
					'profile'=>$this->codegenerator->encryptChar($data_print),
					'tahun'=>$kode['tahun']
				],
				'periode'=>$kode['kode_periode'],
				'periode_plain'=>((isset($kode['nama']))?$kode['nama']:null),
			];
			$datax = array_merge(['table_view'=>$table, 'data_print'=>$this->codegenerator->encryptChar($table_print)]);
		}
		echo json_encode($datax);
	}
	public function report_value_group_koversi()
	{
		$kode=$this->codegenerator->decryptChar($this->input->post('data'));
		$name='Tahunan';
		if(!empty($kode['kode_periode'])){
			$name='Kuartal';
		}
		$table = 
		'<div class="row">
		<div class="col-md-12" style="overflow:auto">
		<table class="table table-hover">
		<thead>
		<tr class="bg-green">
		<th>Nama Konversi Nilai '.$name.'</th>
		<th>Rentang Nilai</th>
		<th>Warna</th>
		</tr>
		</thead>
		<tbody>';
		if(!empty($kode['kode_periode'])){
			$data=$this->model_master->getListKonversiKuartal();
			foreach ($data as $d) {
				$warna = (!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark();
				$table .= '<tr>';
				$table .= '<td>'.$d->nama.'</td>';
				$table .= '<td>'.(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()).'</td>';
				$table .= '<td>'.$warna.'</td>';
				$table .= '</tr>';
			}
			$datax['table_view']=$table;
		}else{
			$data=$this->model_master->getListKonversiTahunan();
			foreach ($data as $d) {
				$warna = (!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark();
				$table .= '<tr>';
				$table .= '<td>'.$d->nama.'</td>';
				$table .= '<td>'.(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()).'</td>';
				$table .= '<td>'.$warna.'</td>';
				$table .= '</tr>';
			}
			
		}
		$table .= '</tbody>
		</table">
		</div>
		</div>';
		$datax['table_view']=$table;
		
		echo json_encode($datax);
	}
//add saran
	function add_saran()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$tahun = $this->input->post('tahun');
		$id_karyawan = $this->input->post('id_karyawan');
		$kode_periode = $this->input->post('kode_periode');
		$saran = $this->input->post('saran');
		$id_pengirim = $this->admin;
		if ($id_karyawan != "" || $id_pengirim != "" ) {
			$adm=$this->model_admin->getAdmin($this->admin);
			$data=[
				'id_karyawan'=>((isset($adm['id_karyawan']))?$adm['id_karyawan']:null),
				'id_admin'=>$id_pengirim,
				'kode_saran'=>$this->codegenerator->kodeSaran(),
				'tahun'=>$tahun,
				'kode_periode'=>$kode_periode,
				'untuk'=>$id_karyawan,
				'saran'=>$saran,
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			unset($data['status']);
			$datax = $this->model_global->insertQuery($data,'data_saran_penilaian');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_saran()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id = $this->input->post('id');
		$saran = $this->input->post('saran');
		if ($id != "") {
			$data=[
				'saran'=>$saran,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			unset($data['status']);
			$datax = $this->model_global->updateQuery($data,'data_saran_penilaian',['id_saran'=>$id]);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//===REPORT GROUP END===//
	//=================================================BLOCK CHANGE=================================================//
	//===AGENDA KOMPETENSI BEGIN===//
	public function agenda_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getListAgendaKompetensi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_a_kompetensi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel);
					$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$count_p.' %" data-placement="right">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					</div>
					</div>';
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$validate = null;
					if (isset($access['l_ac']['apr'])) {
						if ($d->tgl_selesai <= $this->date){
							if ($d->validasi) {
								$validate = (in_array($val['l_ac']['apr'], $access['access'])) ? '<button type="button" class="btn btn-warning btn-sm"  href="javascript:void(0)" onclick=validate_modal('.$val['id'].',0)><i class="fa fa-check" data-toggle="tooltip" title="Batalkan Validasi Data"></i></button> ' : null;
							}else{
								$validate = (in_array($val['l_ac']['apr'], $access['access'])) ? '<button type="button" class="btn btn-success btn-sm"  href="javascript:void(0)" onclick=validate_modal('.$val['id'].',1)><i class="fa fa-check" data-toggle="tooltip" title="Validasi Data"></i></button> ' : null;
							}
						}
					}
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_a_kompetensi,
						$d->kode_a_kompetensi,
						$d->nama,
						$progress,
						$d->nama_konsep,
						$tanggal,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$validate,
						$this->codegenerator->encryptChar($d->kode_c_kompetensi)
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_a_kompetensi');
				$data=$this->model_agenda->getAgendaKompetensi($id); 
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_a_kompetensi,
						'kode_a_kompetensi'=>$d->kode_a_kompetensi,
						'nama'=>$d->nama,
						'nama_tabel'=>$d->nama_tabel,
						'nama_konsep'=>'<a href="'.base_url('pages/view_data_konsep_kompetensi/'.$this->codegenerator->encryptChar($d->kode_c_kompetensi)).'" target="blank" title="Klik untuk Detail">'.$d->nama_konsep.'</a>',
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'periode'=>$d->periode,
						'periode_view'=>(!empty($d->periode)) ? ((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()) : $this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeAgendaKompetensi();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_agenda_kompetensi()
	{ 
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$kode_c=$this->input->post('konsep');
			$table=$this->exam->getNameTable('agenda_kompetensi');
			$data=[
				'kode_a_kompetensi'=>$kode,
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'kode_c_kompetensi'=>$kode_c,
				'nama_tabel'=>$table,
				'tahun'=>$this->input->post('tahun'),
			];
			$data_log=[
				'kode_l_a_kompetensi'=>$this->codegenerator->kodeLogAgendaKompetensi(),
				'nama'=>$data['nama'],
				'tgl_mulai'=>$data['tgl_mulai'],
				'tgl_selesai'=>$data['tgl_selesai'],
				'tahun'=>$data['tahun'],
				'kode_c_kompetensi'=>$data['kode_c_kompetensi'],
				'kode_a_kompetensi'=>$kode,
				'nama_tabel'=>$data['nama_tabel'],				
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$data_log=array_merge($data_log,$this->model_global->getCreateProperties($this->admin));
			$gen=$this->model_agenda->generateAgendaKompetensi($kode_c,$table);
			if ($gen) {
				$this->model_global->insertQueryCCNoMsg($data_log,'log_agenda_kompetensi',$this->model_agenda->checkLogAgendaKompetensiCode($kode));
				$datax = $this->model_global->insertQueryCC($data,'agenda_kompetensi',$this->model_agenda->checkAgendaKompetensiCode($kode));
			}else{
				$datax=$this->messages->customFailure('Generate Tabel Gagal, Mohon Kontak Administrator!');
			}			
		}
		echo json_encode($datax);
	}
	public function edt_agenda_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$data=[
				'kode_a_kompetensi'=>$this->input->post('kode'),
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'tahun'=>$this->input->post('tahun'),
			];
			$data_log=[
				'nama'=>$data['nama'],
				'tgl_mulai'=>$data['tgl_mulai'],
				'tgl_selesai'=>$data['tgl_selesai'],
				'tahun'=>$data['tahun'],
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$data_log=array_merge($data_log,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQueryNoMsg($data_log,'log_agenda_kompetensi',['kode_a_kompetensi'=>$data['kode_a_kompetensi']]);
		//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_a_kompetensi']) {
				$cek=$this->model_master->checkAgendaKompetensiCode($data['kode_a_kompetensi']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'agenda_kompetensi',['id_a_kompetensi'=>$id],$cek);
		}
		echo json_encode($datax);
	}
	function kalibrasi_one_sikap()
	{
		$tabel=$this->input->post('tabel');
		$kode=$this->input->post('kode');
		if ($tabel == "" || $kode == "") {
			$this->messages->notValidParam();  
		}else{
			$karyawan=$this->input->post('id_karyawan');
			$nilai=$this->input->post('nilai');
			$data=array('nilai_kalibrasi'=>$nilai);
			$this->db->where('id_karyawan',$karyawan);
			$in=$this->db->update($tabel,$data);
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure();  
			}
		}
		redirect('pages/result_attitude_tasks_value/'.$kode);
	}
	public function open_agenda()
	{
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array('status_open'=>1);
			$agd=$this->db->get_where('agenda',array('id_agenda'=>$kode))->row_array();
			$this->db->where('kode_agenda',$agd['kode_agenda']);
			$this->db->update('log_agenda',$data);
			$this->db->where('id_agenda',$kode);
			$in=$this->db->update('agenda',$data);
			if ($in) {
				$this->messages->allGood();  
			}else{
				$this->messages->allFailure();  
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/agenda');
	}
	public function close_agenda()
	{
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array('status_open'=>0);
			$agd=$this->db->get_where('agenda',array('id_agenda'=>$kode))->row_array();
			$this->db->where('kode_agenda',$agd['kode_agenda']);
			$this->db->update('log_agenda',$data);
			$this->db->where('id_agenda',$kode);
			$in=$this->db->update('agenda',$data);
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure();  
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/agenda');
	}
	public function open_att_agenda()
	{
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array('status_open'=>1);
			$agd=$this->db->get_where('attd_agenda',array('id_agenda'=>$kode))->row_array();
			$this->db->where('kode_agenda',$agd['kode_agenda']);
			$this->db->update('log_attd_agenda',$data);
			$this->db->where('id_agenda',$kode);
			$in=$this->db->update('attd_agenda',$data);
			if ($in) {
				$this->messages->allGood();  
			}else{
				$this->messages->allFailure();  
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/agenda');
	}
	public function close_att_agenda()
	{
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array('status_open'=>0);
			$agd=$this->db->get_where('attd_agenda',array('id_agenda'=>$kode))->row_array();
			$this->db->where('kode_agenda',$agd['kode_agenda']);
			$this->db->update('log_attd_agenda',$data);
			$this->db->where('id_agenda',$kode);
			$in=$this->db->update('attd_agenda',$data);
			if ($in) {
				$this->messages->allGood();  
			}else{
				$this->messages->allFailure();  
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/agenda');
	}
//==sikap==//
	function input_attitude_value(){
		$kode=$this->input->post('kode');
		$cek=$this->model_agenda->actv_attd($kode);
		$id=$this->input->post('id');
		if ($kode == "" || $cek == "" || $id == "") {
			$this->messages->notValidParam();  
			redirect('pages/attitude_tasks');
		}else{
			$nilai=$this->input->post('nilai');
			$ket=$this->input->post('keterangan');
			$prt=$this->input->post('penilai');
			$tb=$this->input->post('tabel');
			if (isset($ket)) {
				foreach ($ket as $k1 => $kn) {
					$kn=ucwords($kn);
					if ($kn != "") {
						$dt=$this->db->get_where($tb,array('kode_kuisioner'=>$k1,'id_karyawan'=>$id))->row_array();

						$kp=explode(':', $prt);
						if ($kp[0] == "ATS") {
							$ks=$kp[1].':'.$kn.';'.$dt['keterangan_ats'];
							$ks1=explode(';', $ks);
							foreach ($ks1 as $ko) {
								$ko1=explode(':', $ko);
								if ($ko1[0] == $kp[1]) {
									if ($kn != $ko1[1]) {
										$kva=$kp[1].':'.$ko1[1];
										if (($kkey = array_search($kva, $ks1)) !== false) {
											unset($ks1[$kkey]);
										}
									}
								}
							}
							$ksa=array_unique($ks1);
							$kdata=array(
								'keterangan_ats'=>implode(';', $ksa),
							);
						}elseif ($kp[0] == "BWH") {
							$ks=$kp[1].':'.$kn.';'.$dt['keterangan_bwh'];
							$ks1=explode(';', $ks);
							foreach ($ks1 as $ko) {
								$ko1=explode(':', $ko);
								if ($ko1[0] == $kp[1]) {
									if ($kn != $ko1[1]) {
										$kva=$kp[1].':'.$ko1[1];
										if (($kkey = array_search($kva, $ks1)) !== false) {
											unset($ks1[$kkey]);
										}
									}
								}
							}
							$ksa=array_unique($ks1);
							$kdata=array(
								'keterangan_bwh'=>implode(';', $ksa),
							);
						}elseif ($kp[0] == "RKN") {
							$ks=$kp[1].':'.$kn.';'.$dt['keterangan_rkn'];
							$ks1=explode(';', $ks);
							foreach ($ks1 as $ko) {
								$ko1=explode(':', $ko);
								if ($ko1[0] == $kp[1]) {
									if ($kn != $ko1[1]) {
										$kva=$kp[1].':'.$ko1[1];
										if (($kkey = array_search($kva, $ks1)) !== false) {
											unset($ks1[$kkey]);
										}
									}
								}
							}
							$ksa=array_unique($ks1);
							$kdata=array(
								'keterangan_rkn'=>implode(';', $ksa),
							);
						}else {
							$kdata['keterangan_dri']=$kn;
						}
						$kwh=array('id_karyawan'=>$id,'kode_kuisioner'=>$k1);
						$this->db->where($kwh);
						$this->db->update($tb,$kdata);
					}

				}
			}
			
			foreach ($nilai as $k => $n) {
				if ($n != "") {
					$dt=$this->db->get_where($tb,array('kode_kuisioner'=>$k,'id_karyawan'=>$id))->row_array();

					$p=explode(':', $prt);
					if ($p[0] == "ATS") {
						$s=$p[1].':'.$n.';'.$dt['nilai_ats'];
						$s1=explode(';', $s);
						foreach ($s1 as $o) {
							$o1=explode(':', $o);
							if ($o1[0] == $p[1]) {
								if ($n != $o1[1]) {
									$va=$p[1].':'.$o1[1];
									if (($key = array_search($va, $s1)) !== false) {
										unset($s1[$key]);
									}
								}
							}
						}
						$sa=array_unique($s1);
						$sa1=implode(';', $sa);
						$sa2=array_filter(explode(';', $sa1));
						$q=1;
						foreach ($sa2 as $s2) {
							$s3=explode(':', $s2);
							$s4[$q]=$s3[1];
							$q++;
						}
						$rt1=array_filter($s4);
						$rt=array_sum($rt1)/count($rt1);
						$data=array(
							'nilai_ats'=>implode(';', $sa),
							'rata_ats'=>$rt,
							'na_ats'=>$rt*($dt['bobot_ats']/100),
						);
					}elseif ($p[0] == "BWH") {
						$s=$p[1].':'.$n.';'.$dt['nilai_bwh'];
						$s1=explode(';', $s);
						foreach ($s1 as $o) {
							$o1=explode(':', $o);
							if ($o1[0] == $p[1]) {
								if ($n != $o1[1]) {
									$va=$p[1].':'.$o1[1];
									if (($key = array_search($va, $s1)) !== false) {
										unset($s1[$key]);
									}
								}
							}
						}
						$sa=array_unique($s1);
						$sa1=implode(';', $sa);
						$sa2=array_filter(explode(';', $sa1));
						$q=1;
						foreach ($sa2 as $s2) {
							$s3=explode(':', $s2);
							$s4[$q]=$s3[1];
							$q++;
						}
						$rt1=array_filter($s4);
						$rt=array_sum($rt1)/count($rt1);
						$data=array(
							'nilai_bwh'=>implode(';', $sa),
							'rata_bwh'=>$rt,
							'na_bwh'=>$rt*($dt['bobot_bwh']/100),
						);
					}elseif ($p[0] == "RKN") {
						$s=$p[1].':'.$n.';'.$dt['nilai_rkn'];
						$s1=explode(';', $s);
						foreach ($s1 as $o) {
							$o1=explode(':', $o);
							if ($o1[0] == $p[1]) {
								if ($n != $o1[1]) {
									$va=$p[1].':'.$o1[1];
									if (($key = array_search($va, $s1)) !== false) {
										unset($s1[$key]);
									}
								}
							}
						}
						$sa=array_unique($s1);
						$sa1=implode(';', $sa);
						$sa2=array_filter(explode(';', $sa1));
						$q=1;
						foreach ($sa2 as $s2) {
							$s3=explode(':', $s2);
							$s4[$q]=$s3[1];
							$q++;
						}
						$rt1=array_filter($s4);
						$rt=array_sum($rt1)/count($rt1);
						$data=array(
							'nilai_rkn'=>implode(';', $sa),
							'rata_rkn'=>$rt,
							'na_rkn'=>$rt*($dt['bobot_rkn']/100),
						);
					}else {
						$data['nilai_dri']=$n;
					}
					$wh=array('id_karyawan'=>$id,'kode_kuisioner'=>$k);
					$this->db->where($wh);
					$this->db->update($tb,$data);
					$dt1=$this->db->get_where($tb,array('kode_kuisioner'=>$k,'id_karyawan'=>$id))->row_array();
					$data1['nilai_akhir']=$dt1['na_ats']+$dt1['na_bwh']+$dt1['na_rkn'];
					$this->db->where($wh);
					$this->db->update($tb,$data1);
				}

			}
			$this->messages->allGood();  
			redirect('pages/input_attitude_value/'.$kode.'/'.$id.'/'.$prt);
		}
		
	}
	function add_attd_agenda(){
		$nm=$this->input->post('nama');
		if ($nm == "") {
			$this->messages->notValidParam();  
			redirect('pages/agenda');
		}else{
			$tgl=explode(" - ", $this->input->post('date'));
			$tt=str_replace("/", "-", $tgl[1]);
			$tt1=str_replace("/", "-", $tgl[0]);
			$start=date("Y-m-d H:i:s",strtotime($tt1));
			$end=date("Y-m-d H:i:s",strtotime($tt));
			$kode_aad='AGDATTD'.uniqid();
			$data = array(
				'nama_agenda'=>$nm,
				'tgl_mulai'=>$start,
				'tgl_selesai'=>$end,
				'semester'=>$this->input->post('semester'),
				'tahun'=>$this->input->post('tahun'),
				'kode_agenda'=>$kode_aad,
				'create_date'=>$this->date,
				'update_date'=>$this->date,
				'keterangan'=>"not_entry",
			);
			$ceky=$this->model_agenda->cek_year_a($data['tahun'],$data['semester']);
			if ($ceky == 0) {
				$notf='<p><b><i>Dear All,<u></u></i></b></p><p><b></b>Diberitahukan Kepada Seluruh Karyawan Bahwa Ada Agenda Aspek Sikap Baru Dengan Nama&nbsp;<b>'.ucwords($nm).'</b>, Diharapkan Anda Mengisi Nilai Sebelum Tanggal '.date('d/m/Y H:i:s',strtotime($end)).' WIB, Anda Dapat Mengisi Nilai Pada Menu&nbsp;<b>Penilaian &gt; Input Penilaian Sikap&nbsp;</b>atau Dengan Menuju Link Berikut <b><a href="'.base_url('kpages/input_attitude_tasks_value/'.$kode_agda).'">'.ucwords($nm).'</a></b>, Jika <b>Tidak Ada</b> List Karyawan maka anda tidak ada keharusan untuk melakukan penilaian.</p>';
				$kya=$this->db->query("SELECT id_karyawan FROM karyawan WHERE status_emp = 'aktif'")->result();
				$kk=array();
				foreach ($kya as $kyy) {
					array_push($kk, $kyy->id_karyawan);
				}
				$data_not=array(
					'kode_notif'=>"NTF".date("dmYHis",strtotime($this->date)),
					'judul'=>'Agenda Aspek Sikap '.ucwords($nm),
					'isi'=>$notf,
					'start'=>$this->date,
					'end_date'=>$end,
					'kode'=>$kode_aad,
					'sifat'=>1,
					'tipe'=>'info',
					'untuk'=>'FO',
					'id_for'=>implode(';', $kk),
					'create_date'=>$this->date,
					'update_date'=>$this->date,
					'create_by'=>$this->dtroot['adm']['namax'],
					'id_create_by'=>$this->admin,
				);
				$this->db->insert('notification',$data_not);
				$this->db->insert('log_attd_agenda',$data);
				$in=$this->db->insert('attd_agenda',$data);
				if ($in) {
					$this->messages->allGood();  
				}else{
					$this->messages->allFailure();  
				}
			}else{
				$this->messages->allFailure(); 
			}
			
			redirect('pages/agenda');
		}
	}
	function edt_attd_agenda(){
		$id=$this->input->post('id');
		$kode=$this->input->post('kode');
		$cek=$this->model_agenda->cek_attd_agd($kode);
		if ($id == "" || $cek == "" || $kode == "") {
			$this->messages->notValidParam();  
			redirect('pages/agenda');
		}else{
			$nm=$this->input->post('nama');
			$tgl=explode(" - ", $this->input->post('date'));
			$tt=str_replace("/", "-", $tgl[1]);
			$tt1=str_replace("/", "-", $tgl[0]);
			$start=date("Y-m-d H:i:s",strtotime($tt1));
			$end=date("Y-m-d H:i:s",strtotime($tt));
			$data = array(
				'nama_agenda'=>$nm,
				'tgl_mulai'=>$start,
				'tgl_selesai'=>$end,
				'semester'=>$this->input->post('semester'),
				'tahun'=>$this->input->post('tahun'),
				'update_date'=>$this->date,
			);
			if ($data['tahun'] != $cek['tahun'] && $data['semester'] != $cek['semester']) {
				$ceky=$this->model_agenda->cek_year_a($data['tahun'],$data['semester']);
				if ($ceky > 0) {
					$this->messages->duplicateData(); 
				}else{
					$this->db->where('kode_agenda',$kode);
					$this->db->update('log_attd_agenda',$data);
					$this->db->where('kode_agenda',$kode);
					$in=$this->db->update('attd_agenda',$data);
					if ($in) {
						$this->messages->allGood(); 
					}else{
						$this->messages->allFailure(); 
					}
				}
			}else{
				$this->db->where('kode_agenda',$kode);
				$this->db->update('log_attd_agenda',$data);
				$this->db->where('kode_agenda',$kode);
				$in=$this->db->update('attd_agenda',$data);
				if ($in) {
					$this->messages->allGood();  
				}else{
					$this->messages->allFailure();  
				}
			}
			redirect('pages/agenda');
		}
	}
	function status_attd_agenda(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array(
				'status'=>$this->input->post('act'),
			);
			$this->db->where('id_agenda',$kode);
			$in=$this->db->update('attd_agenda',$data);
			if ($in) {
				$this->messages->allGood();  
			}else{
				$this->messages->allFailure();  
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/agenda');
	}
	function del_attd_agenda(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$this->db->where('id_agenda',$kode);
			$in=$this->db->delete('attd_agenda');
			if ($in) {
				$this->messages->delGood(); 
			}else{
				$this->messages->delFailure();  
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/agenda');
	}
	function del_log_attd_agenda(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$dt=$this->db->get_where('log_attd_agenda',array('id_agenda'=>$kode))->row_array();
			$tb=$dt['tabel_agenda']; 
			if ($tb != NULL) {
				$this->dbforge->drop_table($tb,TRUE);
			}
			$this->db->where('kode_agenda',$dt['kode_agenda']);
			$this->db->delete('attd_agenda');
			$this->db->where('id_agenda',$kode);
			$in=$this->db->delete('log_attd_agenda');
			if ($in) {
				$this->messages->delGood(); 
			}else{
				$this->messages->delFailure();  
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/log_agenda');
	}
	function create_attd_task(){
		$kode=$this->input->post('kode');
		if ($kode == "") {
			$this->messages->notValidParam();  
			redirect('pages/agenda');
		}else{
			$kdc=$this->input->post('attd_concept');
			$con=$this->model_setting->attd_concept($kdc);
			$tb=$this->model_setting->table_attd($con['nama_tabel']);
			$col = array(
				'id_task' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_karyawan' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'nik' => array(
					'type' => 'VARCHAR',
					'constraint' => 1000,
					'null'=> TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 1000,
					'default'=> 0
				),
				'id_jabatan' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'jabatan' => array(
					'type' => 'VARCHAR',
					'constraint' => 1000,
					'null'=> TRUE
				),
				'id_loker' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'loker' => array(
					'type' => 'VARCHAR',
					'constraint' => 1000,
					'null'=> TRUE
				),
				'tipe_jabatan' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'partisipan' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'kode_form' => array(
					'type' => 'VARCHAR',
					'constraint' => 1000,
					'null'=> TRUE
				),
				'kode_aspek' => array(
					'type' => 'VARCHAR',
					'constraint' => 1000,
					'null'=> TRUE
				),
				'bobot' => array(
					'type' => 'INT',
					'constraint' => 100,
					'null'=> TRUE
				),
				'kode_kuisioner' => array(
					'type' => 'VARCHAR',
					'constraint' => 1000,
					'null'=> TRUE
				),
				'kuisioner' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'bawah' => array(
					'type' => 'INT',
					'constraint' => 100,
					'null'=> TRUE
				),
				'atas' => array(
					'type' => 'INT',
					'constraint' => 100,
					'null'=> TRUE
				),
				'nilai_ats' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'rata_ats' => array(
					'type' => 'FLOAT',
					'default'=> 0
				),
				'na_ats' => array(
					'type' => 'FLOAT',
					'default'=> 0
				),
				'bobot_ats' => array(
					'type' => 'INT',
					'constraint' => 100,
					'default'=> 0
				),
				'keterangan_ats' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'nilai_bwh' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'rata_bwh' => array(
					'type' => 'FLOAT',
					'default'=> 0
				),
				'na_bwh' => array(
					'type' => 'FLOAT',
					'default'=> 0
				),
				'bobot_bwh' => array(
					'type' => 'INT',
					'constraint' => 100,
					'default'=> 0
				),
				'keterangan_bwh' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'nilai_rkn' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'rata_rkn' => array(
					'type' => 'FLOAT',
					'default'=> 0
				),
				'na_rkn' => array(
					'type' => 'FLOAT',
					'default'=> 0
				),
				'bobot_rkn' => array(
					'type' => 'INT',
					'constraint' => 100,
					'default'=> 0
				),
				'keterangan_rkn' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'nilai_dri' => array(
					'type' => 'INT',
					'constraint' => 100,
					'default'=> 0
				),
				'keterangan_dri' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'nilai_akhir' => array(
					'type' => 'FLOAT',
					'default'=> 0
				),
				'nilai_kalibrasi' => array(
					'type' => 'FLOAT',
					'default'=> 0
				),
			);
			$tab='atts'.uniqid();
			$this->dbforge->add_field($col);
			$this->dbforge->add_key('id_task', TRUE);
			$this->dbforge->create_table($tab);
			foreach ($tb as $t) {
				$jbt=$this->model_master->jabatan($t->id_jabatan);
				$lok=$this->model_master->loker($t->id_loker);
				$tp=$jbt['tipe_jabatan'];
				$frm=$this->model_master->form_sel($tp);
				foreach ($frm as $f) {
					$bbt=explode(';', $f->bobot_aspek);
					foreach ($bbt as $b) {
						$asp=explode(':', $b);
						$k_asp=$asp[0];
						$b_asp=$asp[1];
						$kuis=$this->model_master->cek_kuisioner($k_asp,$tp);
						$aspp=$this->model_master->cek_aspek($k_asp);
						foreach ($kuis as $k) {
							$data=array(
								'id_karyawan'=>$t->id_karyawan,
								'nik'=>$t->nik,
								'nama'=>$t->nama,
								'id_jabatan'=>$t->id_jabatan,
								'jabatan'=>$jbt['jabatan'],
								'id_loker'=>$t->id_loker,
								'loker'=>$lok['nama'],
								'tipe_jabatan'=>$tp,
								'partisipan'=>$t->partisipan,
								'kode_form'=>$f->kode_form,
								'kode_aspek'=>$k_asp,
								'bobot'=>$b_asp,
								'bobot_ats'=>$t->bobot_ats,
								'bobot_bwh'=>$t->bobot_bwh,
								'bobot_rkn'=>$t->bobot_rkn,
								'kode_kuisioner'=>$k->kode_kuisioner,
								'kuisioner'=>$k->kuisioner,
								'bawah'=>$k->bawah,
								'atas'=>$k->atas,
							);
							$this->db->insert($tab,$data);
						}
					}
				}
				
			}
			$data1=array('tabel_agenda'=>$tab,'keterangan'=>'progress');
			$this->db->where('kode_agenda',$kode);
			$this->db->update('attd_agenda',$data1);
			$this->db->where('kode_agenda',$kode);
			$this->db->update('log_attd_agenda',$data1);
			$this->messages->allGood();  
			redirect('pages/agenda');
			
		}
	}
//==output==//
	function edt_agenda(){
		$id=$this->input->post('id'); 
		$kode=$this->input->post('kode');
		$cek=$this->model_agenda->cek_agd($kode);
		if ($id == "" || $cek == "" || $kode == "") {
			$this->messages->notValidParam();  
			redirect('pages/agenda');
		}else{
			$nm=$this->input->post('nama');
			$tgl=explode(" - ", $this->input->post('date'));
			$start=$this->formatter->getDateTimeFormatDb($tgl[0]);
			$end=$this->formatter->getDateTimeFormatDb($tgl[1]);
			$data = array(
				'nama_agenda'=>$nm,
				'tgl_mulai'=>$start,
				'tgl_selesai'=>$end,
				'semester'=>$this->input->post('semester'),
				'tahun'=>$this->input->post('tahun'),
				'update_date'=>$this->date,
			);
			if ($data['tahun'] != $cek['tahun'] && $data['semester'] != $cek['semester']) {
				$ceky=$this->model_agenda->cek_year($data['tahun'],$data['semester']);
				if ($ceky > 0) {
					$this->messages->duplicateData(); 
				}else{
					$this->db->where('kode_agenda',$kode);
					$this->db->update('log_agenda',$data);
					$this->db->where('id_agenda',$id);
					$in=$this->db->update('agenda',$data);
					if ($in) {
						$this->messages->allGood();  
					}else{
						$this->messages->allFailure(); 
					}
				}
			}else{
				$this->db->where('kode_agenda',$kode);
				$this->db->update('log_agenda',$data);
				$this->db->where('kode_agenda',$kode);
				$in=$this->db->update('agenda',$data);
				if ($in) {
					$this->messages->allGood(); 
				}else{
					$this->messages->allFailure(); 
				}
			}
			redirect('pages/agenda');
		}
	}
	public function konversi_gap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$datax['data']=[];
		$data=$this->model_master->getListKonversiGap('active');
		if (isset($data)) {
			foreach ($data as $d) {
				$datax['data'][]=[
					'<b style="font-size:14pt">'.$d->nama.'<b>',
					'<b style="font-size:14pt">'.$d->min.'% - '.$d->max.'%</b>',
					'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>',
				];
			}
		}
		echo json_encode($datax);
	}
// function add_agenda(){
// 	$nm=$this->input->post('nama');
// 	if ($nm == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/agenda');
// 	}else{
// 		$tgl=explode(" - ", $this->input->post('date'));
// 		$start=$this->formatter->getDateTimeFormatDb($tgl[0]);
// 		$end=$this->formatter->getDateTimeFormatDb($tgl[1]);
// 		$kode_agda='AGD'.uniqid();
// 		$data = array(
// 			'nama_agenda'=>ucwords($nm),
// 			'tgl_mulai'=>$start,
// 			'tgl_selesai'=>$end,
// 			'semester'=>$this->input->post('semester'),
// 			'tahun'=>$this->input->post('tahun'),
// 			'kode_agenda'=>$kode_agda,
// 			'create_date'=>$this->date,
// 			'update_date'=>$this->date,
// 			'keterangan'=>"not_entry",
// 		);
	

// 		$ceky=$this->model_agenda->cek_year($data['tahun'],$data['semester']);
// 		if ($ceky > 0) {
// 			$this->messages->duplicateData(); 
// 			redirect('pages/agenda');
// 		}
// 		$notf='<p><b><i>Dear All,<u></u></i></b></p><p><b></b>Diberitahukan Kepada Seluruh Karyawan Bahwa Ada Agenda Output Baru Dengan Nama&nbsp;<b>'.ucwords($nm).'</b>, Diharapkan Anda Mengisi Nilai Sebelum Tanggal '.date('d/m/Y H:i:s',strtotime($end)).' WIB, Anda Dapat Mengisi Nilai Pada Menu&nbsp;<b>Penilaian &gt; Input Penilaian KPI Output&nbsp;</b>atau Dengan Menuju Link Berikut <b><a href="'.base_url('kpages/input_tasks_value/'.$kode_agda).'">'.ucwords($nm).'</a></b>, Jika <b>Tidak Ada</b> List Karyawan maka anda tidak ada keharusan untuk melakukan penilaian.</p>';
// 		$kya=$this->db->query("SELECT id_karyawan FROM karyawan WHERE status_emp = 'aktif'")->result();
// 		$kk=array();
// 		foreach ($kya as $kyy) {
// 			array_push($kk, $kyy->id_karyawan);
// 		}
// 		$data_not=array(
// 			'kode_notif'=>"NTF".date("dmYHis",strtotime($this->date)),
// 			'judul'=>'Agenda KPI Output '.ucwords($nm),
// 			'isi'=>$notf,
// 			'start'=>$this->date,
// 			'end_date'=>$end,
// 			'kode'=>$kode_agda,
// 			'sifat'=>1,
// 			'tipe'=>'info',
// 			'untuk'=>'FO',
// 			'id_for'=>implode(';', $kk),
// 			'create_date'=>$this->date,
// 			'update_date'=>$this->date,
// 			'create_by'=>$this->dtroot['adm']['namax'],
// 			'id_create_by'=>$this->admin,
// 		);
// 		$this->db->insert('notification',$data_not);
// 		$lok=$this->model_master->loker_avl();
// 		//DENDA
// 		$tb_denda='d'.uniqid();
// 		$data_denda=array(
// 			'kode_denda'=>strtoupper("DND".date("dmYHis",strtotime($this->date))),
// 			'nama_denda'=>"Data Denda Tahun ".$data['tahun'],
// 			'nama_tabel'=>$tb_denda,
// 			'kode_agenda'=>$data['kode_agenda'],
// 			'tahun'=>$data['tahun'],
// 			'semester'=>$data['semester'],
// 			'edit'=>'1',
// 			'create_date'=>$this->date,
// 			'update_date'=>$this->date,
// 		);
// 		$col_d = array(
// 			'id_denda' => array(
// 				'type' => 'INT',
// 				'constraint' => 255,
// 				'unsigned' => TRUE,
// 				'auto_increment' => TRUE
// 			),
// 			'kode_loker' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 300,
// 				'null'=> TRUE
// 			),
// 			'nama' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 300,
// 				'null'=> TRUE
// 			),
// 			'pyd1' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pyd2' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pyd3' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pyd4' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pyd5' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pyd6' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pd1' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pd2' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pd3' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pd4' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pd5' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pd6' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'tgt1' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'tgt2' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'tgt3' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'tgt4' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'tgt5' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'tgt6' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'pa1' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'pa2' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'pa3' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'pa4' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'pa5' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'pa6' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'ratapyd' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'ratapd' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'ratatgt' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'default'=> 0
// 			),
// 			'ratapa' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			)
// 		);
// 		$this->dbforge->add_field($col_d);
// 		$this->dbforge->add_key('id_denda', TRUE);
// 		$this->dbforge->create_table($tb_denda);

// 		$dtt1=array('nama'=>'Konsolidasi');
// 		$this->db->insert($tb_denda,$dtt1);
// 		foreach ($lok as $l) {
// 			$dtt=array('kode_loker'=>$l->kode_loker,'nama'=>$l->nama);
// 			$this->db->insert($tb_denda,$dtt);
// 		}
// 		$this->db->insert('dp_denda',$data_denda);

// 		//TARGET CORPORATE
// 		$tb_tc='ttc'.uniqid();
// 		$data_tc=array(
// 			'kode_target'=>strtoupper("TGT".date("dmYHis",strtotime($this->date))),
// 			'nama_target'=>"Target Corporate Tahun ".$data['tahun'],
// 			'nama_tabel'=>$tb_tc,
// 			'tahun'=>$data['tahun'],
// 			'semester'=>$data['semester'],
// 			'edit'=>'1',
// 			'kode_agenda'=>$data['kode_agenda'],
// 			'create_date'=>$this->date,
// 			'update_date'=>$this->date,
// 		);
// 		if ($data['semester'] == "1") {
// 			$col_tc = array(
// 				'id_target' => array(
// 					'type' => 'INT',
// 					'constraint' => 255,
// 					'unsigned' => TRUE,
// 					'auto_increment' => TRUE
// 				),
// 				'kode_loker' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'nama' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'anggaran_juni' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'os_desember' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'target_growth' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'os_juni' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'growth' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'pencapaian' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'nilai' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				)
// 			);
// 		}else{
// 			$col_tc = array(
// 				'id_target' => array(
// 					'type' => 'INT',
// 					'constraint' => 255,
// 					'unsigned' => TRUE,
// 					'auto_increment' => TRUE
// 				),
// 				'kode_loker' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'nama' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'anggaran_desember' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'os_juni' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'target_growth' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'os_desember' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'growth' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 3000,
// 					'default'=> 0
// 				),
// 				'pencapaian' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'nilai' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				)
// 			);
// 		}
// 		$this->dbforge->add_field($col_tc);
// 		$this->dbforge->add_key('id_target', TRUE);
// 		$this->dbforge->create_table($tb_tc);
// 		$dtt22=array('nama'=>'Konsolidasi');
// 		$this->db->insert($tb_tc,$dtt22);
// 		foreach ($lok as $l1) {
// 			$dtt1=array('kode_loker'=>$l1->kode_loker,'nama'=>$l1->nama);
// 			$this->db->insert($tb_tc,$dtt1);
// 		}

// 		$this->db->insert('target_corporate',$data_tc);

// 		//ANGGARAN
// 		$tb_ag='ag'.uniqid();
// 		$data_ag=array(
// 			'kode_anggaran'=>strtoupper("AGR".date("dmYHis",strtotime($this->date))),
// 			'nama_anggaran'=>"Data Perbandingan Anggaran Tahun ".$data['tahun'],
// 			'nama_tabel'=>$tb_ag,
// 			'kode_agenda'=>$data['kode_agenda'],
// 			'tahun'=>$data['tahun'],
// 			'semester'=>$data['semester'],
// 			'edit'=>'1',
// 			'create_date'=>$this->date,
// 			'update_date'=>$this->date,
// 		);
// 		$col_ag = array(
// 			'id_anggaran' => array(
// 				'type' => 'BIGINT',
// 				'constraint' => 255,
// 				'unsigned' => TRUE,
// 				'auto_increment' => TRUE
// 			),
// 			'id_karyawan' => array(
// 				'type' => 'BIGINT',
// 				'constraint' => 255,
// 				'null'=> TRUE
// 			),
// 			'nik' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 300,
// 				'null'=> TRUE
// 			),
// 			'nama_karyawan' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'null'=> TRUE
// 			),
// 			'kode_indikator' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 300,
// 				'null'=> TRUE
// 			),
// 			'n1' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'n2' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'na' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			)
// 		);
// 		$this->dbforge->add_field($col_ag);
// 		$this->dbforge->add_key('id_anggaran', TRUE);
// 		$this->dbforge->create_table($tb_ag);

// 		$this->db->insert('dp_anggaran',$data_ag);
// 		//PRESENSI
// 		$tb_pr='pr'.uniqid();
// 		$data_pr=array(
// 			'kode_presensi'=>strtoupper("PRS".date("dmYHis",strtotime($this->date))),
// 			'nama_presensi'=>"Data Presensi Tahun ".$data['tahun'],
// 			'nama_tabel'=>$tb_pr,
// 			'kode_agenda'=>$data['kode_agenda'],
// 			'tahun'=>$data['tahun'],
// 			'semester'=>$data['semester'],
// 			'edit'=>'1',
// 			'create_date'=>$this->date,
// 			'update_date'=>$this->date,
// 		);
// 		$col_pr = array(
// 			'id_presensi' => array(
// 				'type' => 'BIGINT',
// 				'constraint' => 255,
// 				'unsigned' => TRUE,
// 				'auto_increment' => TRUE
// 			),
// 			'id_karyawan' => array(
// 				'type' => 'BIGINT',
// 				'constraint' => 255,
// 				'null'=> TRUE
// 			),
// 			'nik' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 300,
// 				'null'=> TRUE
// 			),
// 			'nama_karyawan' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 1000,
// 				'null'=> TRUE
// 			),
// 			'kode_indikator' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 300,
// 				'null'=> TRUE
// 			),
// 			'n1' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'n2' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'na' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			)
// 		);
// 		$this->dbforge->add_field($col_pr);
// 		$this->dbforge->add_key('id_presensi', TRUE);
// 		$this->dbforge->create_table($tb_pr);

// 		$this->db->insert('dp_presensi',$data_pr);
// 		//Bobot
// 		$tb_b='bbt'.uniqid();
// 		$data_b=array(
// 			'nama'=>"Data Bobot Tahun ".$data['tahun'],
// 			'nama_tabel'=>$tb_b,
// 			'tahun'=>$data['tahun'],
// 			'semester'=>$data['semester'],
// 			'create_date'=>$this->date,
// 			'update_date'=>$this->date,
// 		);
// 		$col_b = array(
// 			'id_bobot' => array(
// 				'type' => 'BIGINT',
// 				'constraint' => 255,
// 				'unsigned' => TRUE,
// 				'auto_increment' => TRUE
// 			),
// 			'kode_level' => array(
// 				'type' => 'VARCHAR',
// 				'constraint' => 300,
// 				'null'=> TRUE
// 			),
// 			'bobot_out' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'bobot_skp' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			),
// 			'bobot_tc' => array(
// 				'type' => 'FLOAT',
// 				'default'=> 0
// 			)
// 		);
// 		$this->dbforge->add_field($col_b);
// 		$this->dbforge->add_key('id_bobot', TRUE);
// 		$this->dbforge->create_table($tb_b);
// 		$this->db->insert('bobot_agenda',$data_b);
// 		$lv=$this->db->get_where('master_level_jabatan',array('status'=>'aktif'))->result();
// 		foreach ($lv as $lv1) {
// 			$data_lv=array(
// 				'kode_level'=>$lv1->kode_level,
// 				'bobot_out'=>$lv1->bobot_out,
// 				'bobot_skp'=>$lv1->bobot_sikap,
// 				'bobot_tc'=>$lv1->bobot_tcorp,
// 			);
// 			$this->db->insert($tb_b,$data_lv);
// 		}

// 		$this->db->insert('log_agenda',$data);
// 		$in=$this->db->insert('agenda',$data);
// 		if ($in) {
// 			$this->messages->allGood();  
// 		}else{
// 			$this->messages->allFailure();  
// 		}
// 		redirect('pages/agenda');
// 	}
// }
// 	function new_target_corporate(){
// 		$kode=$this->uri->segment(3);
// 		$data=$this->model_agenda->cek_agd($kode);
// 		if ($kode == "" || $data == "") {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$tb_tc='ttc'.uniqid();
// 			$data_tc=array(
// 				'kode_target'=>strtoupper("TGT".date("dmYHis",strtotime($this->date))),
// 				'nama_target'=>"Target Corporate Tahun ".$data['tahun'],
// 				'nama_tabel'=>$tb_tc,
// 				'tahun'=>$data['tahun'],
// 				'semester'=>$data['semester'],
// 				'edit'=>'1',
// 				'kode_agenda'=>$data['kode_agenda'],
// 				'create_date'=>$this->date,
// 				'update_date'=>$this->date,
// 			);
// 			if ($data['semester'] == "1") {
// 				$col_tc = array(
// 					'id_target' => array(
// 						'type' => 'INT',
// 						'constraint' => 255,
// 						'unsigned' => TRUE,
// 						'auto_increment' => TRUE
// 					),
// 					'kode_loker' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 300,
// 						'null'=> TRUE
// 					),
// 					'nama' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 300,
// 						'null'=> TRUE
// 					),
// 					'anggaran_juni' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'os_desember' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'target_growth' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'os_juni' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'growth' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'pencapaian' => array(
// 						'type' => 'FLOAT',
// 						'default'=> 0
// 					),
// 					'nilai' => array(
// 						'type' => 'FLOAT',
// 						'default'=> 0
// 					)
// 				);
// 			}else{
// 				$col_tc = array(
// 					'id_target' => array(
// 						'type' => 'INT',
// 						'constraint' => 255,
// 						'unsigned' => TRUE,
// 						'auto_increment' => TRUE
// 					),
// 					'kode_loker' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 300,
// 						'null'=> TRUE
// 					),
// 					'nama' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 300,
// 						'null'=> TRUE
// 					),
// 					'anggaran_desember' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'os_juni' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'target_growth' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'os_desember' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'growth' => array(
// 						'type' => 'VARCHAR',
// 						'constraint' => 3000,
// 						'default'=> 0
// 					),
// 					'pencapaian' => array(
// 						'type' => 'FLOAT',
// 						'default'=> 0
// 					),
// 					'nilai' => array(
// 						'type' => 'FLOAT',
// 						'default'=> 0
// 					)
// 				);
// 			}
// 			$this->dbforge->add_field($col_tc);
// 			$this->dbforge->add_key('id_target', TRUE);
// 			$this->dbforge->create_table($tb_tc);
// 			$dtt22=array('nama'=>'Konsolidasi');
// 			$this->db->insert($tb_tc,$dtt22);
// 			$lok=$this->model_master->loker_avl();
// 			foreach ($lok as $l1) {
// 				$dtt1=array('kode_loker'=>$l1->kode_loker,'nama'=>$l1->nama);
// 				$this->db->insert($tb_tc,$dtt1);
// 			}

// 			$in=$this->db->insert('target_corporate',$data_tc);
// 			if ($in) {
// 				$this->messages->allGood();  
// 			}else{
// 				$this->messages->allFailure();  
// 			}
// 			redirect('pages/agenda');
// 		}
// 	}
// 	function new_denda(){
// 		$kode=$this->uri->segment(3);
// 		$data=$this->model_agenda->cek_agd($kode);
// 		if ($kode == "" || $data == "") {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$tb_denda='d'.uniqid();
// 			$data_denda=array(
// 				'kode_denda'=>strtoupper("DND".date("dmYHis",strtotime($this->date))),
// 				'nama_denda'=>"Data Denda Tahun ".$data['tahun'],
// 				'nama_tabel'=>$tb_denda,
// 				'kode_agenda'=>$data['kode_agenda'],
// 				'tahun'=>$data['tahun'],
// 				'semester'=>$data['semester'],
// 				'edit'=>'1',
// 				'create_date'=>$this->date,
// 				'update_date'=>$this->date,
// 			);
// 			$col_d = array(
// 				'id_denda' => array(
// 					'type' => 'INT',
// 					'constraint' => 255,
// 					'unsigned' => TRUE,
// 					'auto_increment' => TRUE
// 				),
// 				'kode_loker' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'nama' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'pyd1' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pyd2' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pyd3' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pyd4' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pyd5' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pyd6' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pd1' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pd2' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pd3' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pd4' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pd5' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pd6' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'tgt1' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'tgt2' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'tgt3' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'tgt4' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'tgt5' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'tgt6' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'pa1' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'pa2' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'pa3' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'pa4' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'pa5' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'pa6' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'ratapyd' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'ratapd' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'ratatgt' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'default'=> 0
// 				),
// 				'ratapa' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				)
// 			);
// 			$this->dbforge->add_field($col_d);
// 			$this->dbforge->add_key('id_denda', TRUE);
// 			$this->dbforge->create_table($tb_denda);

// 			$dtt1=array('nama'=>'Konsolidasi');
// 			$this->db->insert($tb_denda,$dtt1);
// 			$lok=$this->model_master->loker_avl();
// 			foreach ($lok as $l) {
// 				$dtt=array('kode_loker'=>$l->kode_loker,'nama'=>$l->nama);
// 				$this->db->insert($tb_denda,$dtt);
// 			}
// 			$in=$this->db->insert('dp_denda',$data_denda);
// 			if ($in) {
// 				$this->messages->allGood(); 
// 			}else{
// 				$this->messages->allFailure(); 
// 			}
// 			redirect('pages/agenda');
// 		}
// 	}
// 	function new_presensi(){
// 		$kode=$this->uri->segment(3);
// 		$data=$this->model_agenda->cek_agd($kode);
// 		if ($kode == "" || $data == "") {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$tb_pr='pr'.uniqid();
// 			$data_pr=array(
// 				'kode_presensi'=>strtoupper("PRS".date("dmYHis",strtotime($this->date))),
// 				'nama_presensi'=>"Data Presensi Tahun ".$data['tahun'],
// 				'nama_tabel'=>$tb_pr,
// 				'kode_agenda'=>$data['kode_agenda'],
// 				'tahun'=>$data['tahun'],
// 				'semester'=>$data['semester'],
// 				'edit'=>'1',
// 				'create_date'=>$this->date,
// 				'update_date'=>$this->date,
// 			);
// 			$col_pr = array(
// 				'id_presensi' => array(
// 					'type' => 'BIGINT',
// 					'constraint' => 255,
// 					'unsigned' => TRUE,
// 					'auto_increment' => TRUE
// 				),
// 				'id_karyawan' => array(
// 					'type' => 'BIGINT',
// 					'constraint' => 255,
// 					'null'=> TRUE
// 				),
// 				'nik' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'nama_karyawan' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'null'=> TRUE
// 				),
// 				'kode_indikator' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'n1' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'n2' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'na' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				)
// 			);
// 			$this->dbforge->add_field($col_pr);
// 			$this->dbforge->add_key('id_presensi', TRUE);
// 			$this->dbforge->create_table($tb_pr);

// 			$in=$this->db->insert('dp_presensi',$data_pr);
// 			if ($in) {
// 				$this->messages->allGood();  
// 			}else{
// 				$this->messages->allFailure();  
// 			}
// 			redirect('pages/agenda');
// 		}
// 	}
// 	function new_anggaran(){
// 		$kode=$this->uri->segment(3);
// 		$data=$this->model_agenda->cek_agd($kode);
// 		if ($kode == "" || $data == "") {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$tb_ag='ag'.uniqid();
// 			$data_ag=array(
// 				'kode_anggaran'=>strtoupper("AGR".date("dmYHis",strtotime($this->date))),
// 				'nama_anggaran'=>"Data Perbandingan Anggaran Tahun ".$data['tahun'],
// 				'nama_tabel'=>$tb_ag,
// 				'kode_agenda'=>$data['kode_agenda'],
// 				'tahun'=>$data['tahun'],
// 				'semester'=>$data['semester'],
// 				'edit'=>'1',
// 				'create_date'=>$this->date,
// 				'update_date'=>$this->date,
// 			);
// 			$col_ag = array(
// 				'id_anggaran' => array(
// 					'type' => 'BIGINT',
// 					'constraint' => 255,
// 					'unsigned' => TRUE,
// 					'auto_increment' => TRUE
// 				),
// 				'id_karyawan' => array(
// 					'type' => 'BIGINT',
// 					'constraint' => 255,
// 					'null'=> TRUE
// 				),
// 				'nik' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'nama_karyawan' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 1000,
// 					'null'=> TRUE
// 				),
// 				'kode_indikator' => array(
// 					'type' => 'VARCHAR',
// 					'constraint' => 300,
// 					'null'=> TRUE
// 				),
// 				'n1' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'n2' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				),
// 				'na' => array(
// 					'type' => 'FLOAT',
// 					'default'=> 0
// 				)
// 			);
// 			$this->dbforge->add_field($col_ag);
// 			$this->dbforge->add_key('id_anggaran', TRUE);
// 			$this->dbforge->create_table($tb_ag);

// 			$in=$this->db->insert('dp_anggaran',$data_ag);
// 			if ($in) {
// 				$this->messages->allGood();  
// 			}else{
// 				$this->messages->allFailure(); 
// 			}
// 			redirect('pages/agenda');
// 		}
// 	}
// 	function status_agenda(){
// 		$kode=$this->input->post('id');
// 		if ($kode != "") {
// 			$data=array(
// 				'status'=>$this->input->post('act'),
// 			);
// 			$this->db->where('id_agenda',$kode);
// 			$in=$this->db->update('agenda',$data);
// 			if ($in) {
// 				$this->messages->allGood(); 
// 			}else{
// 				$this->messages->allFailure(); 
// 			}
// 		}else{
// 			$this->messages->notValidParam();  
// 		}
// 		redirect('pages/agenda');
// 	}
// 	function del_agenda(){
// 		$kode=$this->input->post('id');
// 		if ($kode != "") {
// 			$this->db->where('id_agenda',$kode);
// 			$in=$this->db->delete('agenda');
// 			if ($in) {
// 				$this->messages->delGood();  
// 			}else{
// 				$this->messages->delFailure();  
// 			}
// 		}else{
// 			$this->messages->notValidParam();  
// 		}
// 		redirect('pages/agenda');
// 	}
// 	function del_log_agenda(){
// 		$kode=$this->input->post('id');
// 		if ($kode != "") {
// 			$dt=$this->db->get_where('log_agenda',array('id_agenda'=>$kode))->row_array();
// 			$tb=$dt['tabel_agenda'];
// 			if ($tb != NULL) {
// 				$this->dbforge->drop_table($tb,TRUE);
// 			}
// 			$wh=array('tahun'=>$dt['tahun'],'semester'=>$dt['semester']);
// 			$dt1=$this->db->get_where('bobot_agenda',$wh)->result();
// 			foreach ($dt1 as $d) {
// 				$tbl=$d->nama_tabel;
// 				if ($d->nama_tabel != NULL) {
// 					$this->dbforge->drop_table($d->nama_tabel,TRUE);
// 				}
// 			}
// 			$this->db->where($wh);
// 			$this->db->delete('bobot_agenda');
// 			$this->db->where('kode_agenda',$dt['kode_agenda']);
// 			$this->db->delete('agenda');
// 			$this->db->where('id_agenda',$kode);
// 			$in=$this->db->delete('log_agenda');
// 			if ($in) {
// 				$this->messages->delGood();  
// 			}else{
// 				$this->messages->delFailure(); 
// 			}
// 		}else{
// 			$this->messages->notValidParam();  
// 		}
// 		redirect('pages/log_agenda');
// 	}
// 	function create_task(){
// 		$kode=$this->input->post('kode_agenda');
// 		$cek=$this->model_agenda->cek_agd($kode);
// 		if ($kode == "" || $cek == "") {
// 			redirect('pages/agenda');
// 		}else{
// 			$kc=$this->input->post('concept');
// 			$data=$this->model_setting->cek_setting($kc);
// 			$nmt=$data['nama_tabel'];
// 			$da=$this->db->get($nmt)->result();
// 			$nmta='ts'.uniqid();
// 			$this->db->query("CREATE TABLE $nmta LIKE $nmt");
// 			foreach ($da as $d) {
// 				$this->db->insert($nmta,$d);
// 				$dd=array('kode_agenda'=>$kode);
// 				$this->db->where('id_task',$d->id_task);
// 				$this->db->update($nmta,$dd);
// 			}

// 		//update agenda
// 			$upagd=array('tabel_agenda'=>$nmta,'keterangan'=>'progress');
// 			$this->db->where('kode_agenda',$kode);
// 			$this->db->update('agenda',$upagd);
// 		//update log_agenda
// 			$upagd=array('tabel_agenda'=>$nmta,'keterangan'=>'progress');
// 			$this->db->where('kode_agenda',$kode);
// 			$this->db->update('log_agenda',$upagd);
// 			$this->messages->allGood();  
// 			redirect('pages/agenda');
// 		}
// 	}
// 	function add_new_concept_task(){
// 		$kode=$this->input->post('kode_agenda');
// 		$cek=$this->model_agenda->cek_agd($kode);
// 		if ($kode == "" || $cek == "" || $cek['tabel_agenda'] == "") {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$con=$this->input->post('concept');
// 			$dt=$this->model_setting->cek_setting($con);
// 			$tbc=$dt['nama_tabel'];
// 			$tba=$cek['tabel_agenda'];
// 			$dttbc1=$this->model_setting->concept_data($tbc);
// 		//$dttba=$this->model_agenda->task($tba);
// 			foreach ($dttbc1 as $c1) {
// 				$kar[$c1->id_karyawan]=$c1->id_karyawan;
// 				$jpa1[$c1->id_karyawan][]=$c1->id_jabatan_pa;
// 				$lpa1[$c1->id_karyawan][]=$c1->id_loker_pa;
// 			}
// 			foreach ($kar as $k) {
// 				$jpa[$k]=implode('',array_unique($jpa1[$k]));
// 				$lpa[$k]=implode('',array_unique($lpa1[$k]));

// 				$dttba=$this->db->get_where($tba,array('id_karyawan'=>$k))->result();
// 				if (count($dttba) != 0) {
// 					foreach ($dttba as $a) {
// 						if ($a->id_jabatan_pa != $jpa[$k]) {
// 							$sv=1;
// 						}else{
// 							$sv=0;
// 						}
// 					/*
// 					if (in_array($a->kode_indikator, $ind[$k])) {
// 						if (($key = array_search($a->kode_indikator, $ind[$k])) !== false) {
// 						    unset($ind[$k][$key]);
// 						}
// 					}
// 					*/
// 				}
// 				if ($sv == 1) {
// 					$indi=$this->db->get_where($tbc,array('id_karyawan'=>$k))->result();
// 					foreach ($indi as $i) {
// 						$data=array(
// 							'kode_indikator'=>$i->kode_indikator,
// 							'indikator'=>$i->indikator,
// 							'cara_mengukur'=>$i->cara_mengukur,
// 							'rumus'=>$i->rumus,
// 							'sumber'=>$i->sumber,
// 							'periode'=>$i->periode,
// 							'kaitan'=>$i->kaitan,
// 							'konsolidasi'=>$i->konsolidasi,
// 							'polarisasi'=>$i->polarisasi,
// 							'id_karyawan'=>$k,
// 							'id_jabatan'=>$i->id_jabatan,
// 							'id_loker'=>$i->id_loker,
// 							'id_jabatan_pa'=>$i->id_jabatan_pa,
// 							'id_loker_pa'=>$i->id_loker_pa,
// 							'loker_pa'=>$i->loker_pa,
// 							'jabatan'=>$i->jabatan,
// 							'loker'=>$i->loker,
// 							'jabatan_pa'=>$i->jabatan_pa,
// 							'id_sub'=>$i->id_sub,
// 							'sub'=>$i->sub,
// 							'kode_penilai'=>$i->kode_penilai,
// 							'id_penilai'=>$i->id_penilai,
// 							'target'=>$i->target,
// 							'realisasi'=>$i->realisasi,
// 							'satuan'=>$i->satuan,
// 						);
// 						$this->db->insert($tba,$data);
// 					}
// 				}
// 				/*
// 				foreach ($ind[$k] as $i1) {
// 					$indi=$this->db->get_where($tbc,array('id_karyawan'=>$k,'kode_indikator'=>$i1))->row_array();
// 					$data=array(
// 						'kode_indikator'=>$i1,
// 						'indikator'=>$indi['indikator'],
// 						'cara_mengukur'=>$indi['cara_mengukur'],
// 						'rumus'=>$indi['rumus'],
// 						'sumber'=>$indi['sumber'],
// 						'id_karyawan'=>$k,
// 						'id_jabatan'=>$indi['id_jabatan'],
// 						'id_loker'=>$indi['id_loker'],
// 						'id_jabatan_pa'=>$indi['id_jabatan_pa'],
// 						'id_loker_pa'=>$indi['id_loker_pa'],
// 						'bobot'=>$indi['bobot'],
// 						'bobot_out'=>$indi['bobot_out'],
// 						'bobot_skp'=>$indi['bobot_skp'],
// 						'bobot_tc'=>$indi['bobot_tc'],
// 						'satuan'=>$indi['satuan'],
// 						'periode'=>$indi['periode'],
// 						'kaitan'=>$indi['kaitan'],
// 						'konsolidasi'=>$indi['konsolidasi'],
// 						'polarisasi'=>$indi['polarisasi'],
// 						'sifat'=>$indi['sifat'],
// 						'kode_penilai'=>$indi['kode_penilai'],
// 						'kode_agenda'=>$indi['kode_agenda'],
// 						'id_penilai'=>$indi['id_penilai'],
// 						'validasi'=>$indi['validasi'],
// 						'target'=>$indi['target'],
// 						'realisasi'=>$indi['realisasi'],
// 					);
// 					$this->db->insert($tba,$data);
// 				}
// 				*/
// 			}else{
// 				$indi=$this->db->get_where($tbc,array('id_karyawan'=>$k))->result();
// 				foreach ($indi as $i) {
// 					$data=array(
// 						'kode_indikator'=>$i->kode_indikator,
// 						'indikator'=>$i->indikator,
// 						'cara_mengukur'=>$i->cara_mengukur,
// 						'rumus'=>$i->rumus,
// 						'sumber'=>$i->sumber,
// 						'periode'=>$i->periode,
// 						'kaitan'=>$i->kaitan,
// 						'konsolidasi'=>$i->konsolidasi,
// 						'polarisasi'=>$i->polarisasi,
// 						'id_karyawan'=>$k,
// 						'id_jabatan'=>$i->id_jabatan,
// 						'id_loker'=>$i->id_loker,
// 						'id_jabatan_pa'=>$i->id_jabatan_pa,
// 						'id_loker_pa'=>$i->id_loker_pa,
// 						'loker_pa'=>$i->loker_pa,
// 						'jabatan'=>$i->jabatan,
// 						'loker'=>$i->loker,
// 						'jabatan_pa'=>$i->jabatan_pa,
// 						'id_sub'=>$i->id_sub,
// 						'sub'=>$i->sub,
// 						'kode_penilai'=>$i->kode_penilai,
// 						'id_penilai'=>$i->id_penilai,
// 						'target'=>$i->target,
// 						'realisasi'=>$i->realisasi,
// 						'satuan'=>$i->satuan,
// 					);
// 					$this->db->insert($tba,$data);
// 				}	
// 			}
// 		}
// 		$this->messages->allGood();  
// 		redirect('pages/agenda');
// 	}
// }
// function input_value(){
// 	$kode=$this->input->post('kode');
// 	$id=$this->input->post('id');
// 	$tb=$this->input->post('tabel');
// 	$penilai=$this->input->post('penilai');
// 	if ($kode == "" || $tb == "" || $id == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/tasks');
// 	}else{
// 		$ln1=$this->input->post('ln1');
// 		$ln2=$this->input->post('ln2');
// 		$ln3=$this->input->post('ln3');
// 		$ln4=$this->input->post('ln4');
// 		$ln5=$this->input->post('ln5');
// 		$ln6=$this->input->post('ln6');
// 		if (isset($ln1)) {
// 			foreach ($ln1 as $k => $v) {

// 				$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($v != "") {	
// 					$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';
// 					if ($old['ln1'] != '0') {
// 						$ni3[$k]=explode(',', $old['ln1'].''.$ni1[$k]);
// 						foreach ($ni3[$k] as $nn3[$k]) {
// 							$fn=str_replace('{', '', $nn3[$k]);
// 							$bc=str_replace('}', '', $fn);
// 							$bc1=explode(' ', $bc);
// 							if ($bc1[0] == "ADM") {
// 								$bc2=explode(':', $bc1[1]);
// 								if ($bc2[0] == $this->admin) {
// 									if ($v != $bc2[1]) {
// 										$va='{ADM '.$this->admin.':'.$bc2[1].'}';
// 										if (($key = array_search($va, $ni3[$k])) !== false) {
// 											unset($ni3[$k][$key]);
// 										}
// 									}
// 								}
// 							}
// 						}
// 						$ni2[$k]=array_unique($ni3[$k]);
// 					}else{
// 						$ni2[$k]=explode(',', $ni1[$k]);
// 					}
// 					$ni[$k]=array_filter($ni2[$k]);
// 					foreach ($ni[$k] as $naa[$k]) {
// 						$front[$k]= str_replace('{', '', $naa[$k]);
// 						$back1[$k]= str_replace('}', '', $front[$k]);
// 						$back2[$k]=explode(' ', $back1[$k]);
// 						if ($back2[$k][0] == 'ADM') {
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}else{
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}
// 						$nfa1[$k][]=$back[$k];
// 					}
// 					$nf1[$k]=array_sum($nfa1[$k])/count($ni[$k]);
// 					$nn[$k]=($old['bobot']/100)*$nf1[$k];
// 					if ($old['ln1'] != '0') {
// 						$nii1[$k]=implode(',', $ni2[$k]);
// 					}else{
// 						$nii1[$k]=$ni1[$k];
// 					}
// 					$data[$k]=array(
// 						'ln1'=>$nii1[$k],
// 						'nra1'=>$nf1[$k],
// 						'na1'=>$nn[$k],
// 					);
					
// 					echo '<pre>';
// 					print_r($data);
// 					echo '<pre>';
					
// 					$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
// 					$this->db->where($wh);
// 					$this->db->update($tb,$data[$k]);

// 				}
// 				$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($new['ln1'] != '0') {
// 					$nii[$k][]=$new['na1'];
// 				}
// 			}
// 		}
		
// 		if (isset($ln2)) {
// 			foreach ($ln2 as $k => $v) {

// 				$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($v != "") {
// 					$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

// 					if ($old['ln2'] != '0') {
// 						$ni3[$k]=explode(',', $old['ln2'].''.$ni1[$k]);
// 						foreach ($ni3[$k] as $nn3[$k]) {
// 							$fn=str_replace('{', '', $nn3[$k]);
// 							$bc=str_replace('}', '', $fn);
// 							$bc1=explode(' ', $bc);
// 							if ($bc1[0] == "ADM") {
// 								$bc2=explode(':', $bc1[1]);
// 								if ($bc2[0] == $this->admin) {
// 									if ($v != $bc2[1]) {
// 										$va='{ADM '.$this->admin.':'.$bc2[1].'}';
// 										if (($key = array_search($va, $ni3[$k])) !== false) {
// 											unset($ni3[$k][$key]);
// 										}
// 									}
// 								}
// 							}
// 						}
// 						$ni2[$k]=array_unique($ni3[$k]);
// 					}else{
// 						$ni2[$k]=explode(',', $ni1[$k]);
// 					}

// 					$ni[$k]=array_filter($ni2[$k]);
// 					foreach ($ni[$k] as $naa[$k]) {

// 						$front[$k]= str_replace('{', '', $naa[$k]);
// 						$back1[$k]= str_replace('}', '', $front[$k]);
// 						$back2[$k]=explode(' ', $back1[$k]);

// 						if ($back2[$k][0] == 'ADM') {
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}else{
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}
						
// 						$nfa2[$k][]=$back[$k];
// 					}
// 					$nf1[$k]=array_sum($nfa2[$k])/count($ni[$k]);
// 					$nn[$k]=($old['bobot']/100)*$nf1[$k];
// 					if ($old['ln2'] != '0') {
// 						$nii1[$k]=implode(',', $ni2[$k]);
// 					}else{
// 						$nii1[$k]=$ni1[$k];
// 					}
// 					//print_r($nf[$k]);
// 					$data[$k]=array(
// 						'ln2'=>$nii1[$k],
// 						'nra2'=>$nf1[$k],
// 						'na2'=>$nn[$k],
// 					);
// 					$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
// 					$this->db->where($wh);
// 					$this->db->update($tb,$data[$k]);

// 				}
// 				$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($new['ln2'] != '0') {
// 					$nii[$k][]=$new['na2'];
// 				}
// 			}
// 		}
// 		if (isset($ln3)) {
// 			foreach ($ln3 as $k => $v) {

// 				$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($v != "") {
// 					$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

// 					if ($old['ln3'] != '0') {
// 						$ni3[$k]=explode(',', $old['ln3'].''.$ni1[$k]);
// 						foreach ($ni3[$k] as $nn3[$k]) {
// 							$fn=str_replace('{', '', $nn3[$k]);
// 							$bc=str_replace('}', '', $fn);
// 							$bc1=explode(' ', $bc);
// 							if ($bc1[0] == "ADM") {
// 								$bc2=explode(':', $bc1[1]);
// 								if ($bc2[0] == $this->admin) {
// 									if ($v != $bc2[1]) {
// 										$va='{ADM '.$this->admin.':'.$bc2[1].'}';
// 										if (($key = array_search($va, $ni3[$k])) !== false) {
// 											unset($ni3[$k][$key]);
// 										}
// 									}
// 								}
// 							}
// 						}
// 						$ni2[$k]=array_unique($ni3[$k]);
// 					}else{
// 						$ni2[$k]=explode(',', $ni1[$k]);
// 					}

// 					$ni[$k]=array_filter($ni2[$k]);
// 					foreach ($ni[$k] as $naa[$k]) {

// 						$front[$k]= str_replace('{', '', $naa[$k]);
// 						$back1[$k]= str_replace('}', '', $front[$k]);
// 						$back2[$k]=explode(' ', $back1[$k]);

// 						if ($back2[$k][0] == 'ADM') {
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}else{
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}
						
// 						$nfa3[$k][]=$back[$k];
// 					}
// 					$nf1[$k]=array_sum($nfa3[$k])/count($ni[$k]);
// 					$nn[$k]=($old['bobot']/100)*$nf1[$k];
// 					if ($old['ln3'] != '0') {
// 						$nii1[$k]=implode(',', $ni2[$k]);
// 					}else{
// 						$nii1[$k]=$ni1[$k];
// 					}
// 					//print_r($nf[$k]);
// 					$data[$k]=array(
// 						'ln3'=>$nii1[$k],
// 						'nra3'=>$nf1[$k],
// 						'na3'=>$nn[$k],
// 					);
// 					$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
// 					$this->db->where($wh);
// 					$this->db->update($tb,$data[$k]);

// 				}
// 				$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($new['ln3'] != '0') {
// 					$nii[$k][]=$new['na3'];
// 				}
// 			}
// 		}
// 		if (isset($ln4)) {
// 			foreach ($ln4 as $k => $v) {

// 				$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($v != "") {
// 					$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

// 					if ($old['ln4'] != '0') {
// 						$ni3[$k]=explode(',', $old['ln4'].''.$ni1[$k]);
// 						foreach ($ni3[$k] as $nn3[$k]) {
// 							$fn=str_replace('{', '', $nn3[$k]);
// 							$bc=str_replace('}', '', $fn);
// 							$bc1=explode(' ', $bc);
// 							if ($bc1[0] == "ADM") {
// 								$bc2=explode(':', $bc1[1]);
// 								if ($bc2[0] == $this->admin) {
// 									if ($v != $bc2[1]) {
// 										$va='{ADM '.$this->admin.':'.$bc2[1].'}';
// 										if (($key = array_search($va, $ni3[$k])) !== false) {
// 											unset($ni3[$k][$key]);
// 										}
// 									}
// 								}
// 							}
// 						}
// 						$ni2[$k]=array_unique($ni3[$k]);
// 					}else{
// 						$ni2[$k]=explode(',', $ni1[$k]);
// 					}

// 					$ni[$k]=array_filter($ni2[$k]);
// 					foreach ($ni[$k] as $naa[$k]) {

// 						$front[$k]= str_replace('{', '', $naa[$k]);
// 						$back1[$k]= str_replace('}', '', $front[$k]);
// 						$back2[$k]=explode(' ', $back1[$k]);

// 						if ($back2[$k][0] == 'ADM') {
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}else{
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}
						
// 						$nfa4[$k][]=$back[$k];
// 					}
// 					$nf1[$k]=array_sum($nfa4[$k])/count($ni[$k]);
// 					$nn[$k]=($old['bobot']/100)*$nf1[$k];
// 					if ($old['ln4'] != '0') {
// 						$nii1[$k]=implode(',', $ni2[$k]);
// 					}else{
// 						$nii1[$k]=$ni1[$k];
// 					}
// 					//print_r($nf[$k]);
// 					$data[$k]=array(
// 						'ln4'=>$nii1[$k],
// 						'nra4'=>$nf1[$k],
// 						'na4'=>$nn[$k],
// 					);
// 					$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
// 					$this->db->where($wh);
// 					$this->db->update($tb,$data[$k]);

// 				}
// 				$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($new['ln4'] != '0') {
// 					$nii[$k][]=$new['na4'];
// 				}
// 			}
// 		}

// 		if (isset($ln5)) {
// 			foreach ($ln5 as $k => $v) {

// 				$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($v != "") {
// 					$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

// 					if ($old['ln5'] != '0') {
// 						$ni3[$k]=explode(',', $old['ln5'].''.$ni1[$k]);
// 						foreach ($ni3[$k] as $nn3[$k]) {
// 							$fn=str_replace('{', '', $nn3[$k]);
// 							$bc=str_replace('}', '', $fn);
// 							$bc1=explode(' ', $bc);
// 							if ($bc1[0] == "ADM") {
// 								$bc2=explode(':', $bc1[1]);
// 								if ($bc2[0] == $this->admin) {
// 									if ($v != $bc2[1]) {
// 										$va='{ADM '.$this->admin.':'.$bc2[1].'}';
// 										if (($key = array_search($va, $ni3[$k])) !== false) {
// 											unset($ni3[$k][$key]);
// 										}
// 									}
// 								}
// 							}
// 						}
// 						$ni2[$k]=array_unique($ni3[$k]);
// 					}else{
// 						$ni2[$k]=explode(',', $ni1[$k]);
// 					}

// 					$ni[$k]=array_filter($ni2[$k]);
// 					foreach ($ni[$k] as $naa[$k]) {

// 						$front[$k]= str_replace('{', '', $naa[$k]);
// 						$back1[$k]= str_replace('}', '', $front[$k]);
// 						$back2[$k]=explode(' ', $back1[$k]);

// 						if ($back2[$k][0] == 'ADM') {
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}else{
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}
						
// 						$nfa5[$k][]=$back[$k];
// 					}
// 					$nf1[$k]=array_sum($nfa5[$k])/count($ni[$k]);
// 					$nn[$k]=($old['bobot']/100)*$nf1[$k];
// 					if ($old['ln5'] != '0') {
// 						$nii1[$k]=implode(',', $ni2[$k]);
// 					}else{
// 						$nii1[$k]=$ni1[$k];
// 					}
// 					//print_r($nf[$k]);
// 					$data[$k]=array(
// 						'ln5'=>$nii1[$k],
// 						'nra5'=>$nf1[$k],
// 						'na5'=>$nn[$k],
// 					);
// 					$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
// 					$this->db->where($wh);
// 					$this->db->update($tb,$data[$k]);

// 				}
// 				$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($new['ln5'] != '0') {
// 					$nii[$k][]=$new['na5'];
// 				}
// 			}
// 		}
		
// 		if (isset($ln6)) {
// 			foreach ($ln6 as $k => $v) {
				
// 				$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($v != "") {
// 					$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

// 					if ($old['ln6'] != '0') {
// 						$ni3[$k]=explode(',', $old['ln6'].''.$ni1[$k]);
// 						foreach ($ni3[$k] as $nn3[$k]) {
// 							$fn=str_replace('{', '', $nn3[$k]);
// 							$bc=str_replace('}', '', $fn);
// 							$bc1=explode(' ', $bc);
// 							if ($bc1[0] == "ADM") {
// 								$bc2=explode(':', $bc1[1]);
// 								if ($bc2[0] == $this->admin) {
// 									if ($v != $bc2[1]) {
// 										$va='{ADM '.$this->admin.':'.$bc2[1].'}';
// 										if (($key = array_search($va, $ni3[$k])) !== false) {
// 											unset($ni3[$k][$key]);
// 										}
// 									}
// 								}
// 							}
// 						}
// 						$ni2[$k]=array_unique($ni3[$k]);
// 					}else{
// 						$ni2[$k]=explode(',', $ni1[$k]);
// 					}

// 					$ni[$k]=array_filter($ni2[$k]);
// 					foreach ($ni[$k] as $naa[$k]) {

// 						$front[$k]= str_replace('{', '', $naa[$k]);
// 						$back1[$k]= str_replace('}', '', $front[$k]);
// 						$back2[$k]=explode(' ', $back1[$k]);

// 						if ($back2[$k][0] == 'ADM') {
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}else{
// 							$back3[$k]=explode(':', $back2[$k][1]);
// 							$back[$k]=$back3[$k][1];
// 						}
						
// 						$nfa6[$k][]=$back[$k];
// 					}
// 					$nf1[$k]=array_sum($nfa6[$k])/count($ni[$k]);
// 					$nn[$k]=($old['bobot']/100)*$nf1[$k];
// 					if ($old['ln6'] != '0') {
// 						$nii1[$k]=implode(',', $ni2[$k]);
// 					}else{
// 						$nii1[$k]=$ni1[$k];
// 					}
// 					//print_r($nf[$k]);
// 					$data[$k]=array(
// 						'ln6'=>$nii1[$k],
// 						'nra6'=>$nf1[$k],
// 						'na6'=>$nn[$k],
// 					);
// 					$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
// 					//print_r($data[$k]);
// 					$this->db->where($wh);
// 					$this->db->update($tb,$data[$k]);

// 				}
// 				$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
// 				if ($new['ln6'] != '0') {
// 					$nii[$k][]=$new['na6'];
// 				}
// 			}
// 		}
// 		foreach ($nii as $k11 => $v11) {
// 			$na[$k11]=array_sum($nii[$k11])/count($nii[$k11]);
// 			$data[$k11]=array(
// 				'nilai_out'=>$na[$k11],
// 			);
// 			$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k11);
// 			$this->db->where($wh);
// 			$this->db->update($tb,$data[$k11]);
// 		}
		
// 	}
// 	$this->messages->allGood();  
// 	redirect('pages/input_tasks_value/'.$kode);

// }
// function up_input_value(){
// 	$tb=$this->input->post('nmtb');
// 	$kode_agd=$this->input->post('kode_agenda');
// 	if ($tb == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/input_tasks_value/'.$kode_agd);
// 	}else{
// 		$id=$this->input->post('id');
// 		$data=array('nilai'=>$this->input->post('nilai'),);
// 		$this->db->where('id_task',$id);
// 		$in=$this->db->update($tb,$data);
// 		if ($in) {
// 			$this->messages->allGood();  
// 		}else{
// 			$this->messages->allFailure();  
// 		}
// 		redirect('pages/input_tasks_value/'.$kode_agd);
// 	}
// }
// function export_value(){
// 	$kode=$this->input->post('kode');
// 	$cek=$this->model_agenda->cek_agd($kode);
// 	if ($kode == "" || $cek == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/tasks');
// 	}else{ 
// 		$tb=$this->input->post('tabel_agd');
// 		$indi=$this->input->post('indi');
// 		if ($indi == "") {
// 			$tbb=$this->db->get_where($tb,array('kaitan'=>'0'))->result();
// 			foreach ($tbb as $t) {
// 				$indx[$t->kode_indikator]=$t->kode_indikator;
// 			}
// 			foreach ($indx as $i) {
// 				$dtk=$this->model_master->tb_sel_p($tb,$i);
// 				foreach ($dtk as $d) {
// 					$idk[$d->id_karyawan]=$d->id_karyawan;
// 				}
// 				$kpz=$this->model_master->which_indikator($i);
// 				$kpi[$i]=$kpz['kpi'];
// 			}
// 		}else{
// 			foreach ($indi as $i) {
// 				$dtk=$this->model_master->tb_sel_p($tb,$i);
// 				foreach ($dtk as $d) {
// 					$idk[$d->id_karyawan]=$d->id_karyawan;
// 				}
// 				$kpz=$this->model_master->which_indikator($i);
// 				$kpi[$i]=$kpz['kpi'];
// 			}
// 		}
		
// 		if(count($idk)>0){
// 			$objPHPExcel = new PHPExcel();
// 			// Set document properties
// 			$objPHPExcel->getProperties()->setCreator("Galeh Fatma Eko A")
// 			->setLastModifiedBy("Galeh Fatma Eko A")
// 			->setTitle('Nilai Aktual '.$cek['nama_agenda'].' Semester'.$cek['semester'])
// 			->setSubject($cek['nama_agenda'])
// 			->setDescription($cek['nama_agenda'].' Semester'.$cek['semester'])
// 			->setKeywords($cek['nama_agenda'])
// 			->setCategory($cek['nama_agenda']);
// 			// Add some data
// 			$tri=1;
// 			for ($chrf='A'; $chrf!="AAAA"; $chrf++){
// 				$huruf[$tri]=$chrf;
// 				$tri++;
// 			}
// 			$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
// 				array(
// 					'fill' => array(
// 						'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 						'color' => array('rgb' => 'af00a4')
// 					),
// 					'font' => array(
// 						'color' => array('rgb' => 'FFFFFF')
// 					)
// 				)
// 			);						 							 
			
			
//             //$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth(10); 
// 			$ch=6;
// 			$ch1=7;
// 			$ch2=8;
// 			$ch3=9;
// 			$ch4=10;
// 			$ch5=11;
// 			foreach ($kpi as $x => $val) {
// 				$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch].'2')->applyFromArray(
// 					array(
// 						'fill' => array(
// 							'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 							'color' => array('rgb' => '8e0038')
// 						),
// 						'font' => array(
// 							'color' => array('rgb' => 'FFFFFF')
// 						)
// 					)
// 				);
// 				$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch1].'2')->applyFromArray(
// 					array(
// 						'fill' => array(
// 							'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 							'color' => array('rgb' => '3b008e')
// 						),
// 						'font' => array(
// 							'color' => array('rgb' => 'FFFFFF')
// 						)
// 					)
// 				);
// 				$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch2].'2')->applyFromArray(
// 					array(
// 						'fill' => array(
// 							'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 							'color' => array('rgb' => '7f8e00')
// 						),
// 						'font' => array(
// 							'color' => array('rgb' => 'FFFFFF')
// 						)
// 					)
// 				);
// 				$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch3].'2')->applyFromArray(
// 					array(
// 						'fill' => array(
// 							'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 							'color' => array('rgb' => '008902')
// 						),
// 						'font' => array(
// 							'color' => array('rgb' => 'FFFFFF')
// 						)
// 					)
// 				);
// 				$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch4].'2')->applyFromArray(
// 					array(
// 						'fill' => array(
// 							'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 							'color' => array('rgb' => '5b1966')
// 						),
// 						'font' => array(
// 							'color' => array('rgb' => 'FFFFFF')
// 						)
// 					)
// 				);
// 				$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch5].'2')->applyFromArray(
// 					array(
// 						'fill' => array(
// 							'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 							'color' => array('rgb' => '008e87')
// 						),
// 						'font' => array(
// 							'color' => array('rgb' => 'FFFFFF')
// 						)
// 					)
// 				);
// 				$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch].'1')->applyFromArray(
// 					array(
// 						'fill' => array(
// 							'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 							'color' => array('rgb' => 'ffdd00')
// 						),
// 						'font' => array(
// 							'color' => array('rgb' => '000000')
// 						)
// 					)
// 				);
// 				$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch1].'1')->applyFromArray(
// 					array(
// 						'fill' => array(
// 							'type' => PHPExcel_Style_Fill::FILL_SOLID,
// 							'color' => array('rgb' => '00f2ff')
// 						),
// 						'font' => array(
// 							'color' => array('rgb' => '000000')
// 						)
// 					)
// 				);
// 				$objPHPExcel->getActiveSheet(0)
// 				->getColumnDimension($huruf[$ch])
// 				->setAutoSize(true);
// 				$objPHPExcel->getActiveSheet(0)
// 				->getColumnDimension($huruf[$ch1])
// 				->setAutoSize(true);	
// 				$objPHPExcel->getActiveSheet(0)
// 				->getColumnDimension($huruf[$ch2])
// 				->setAutoSize(true);
// 				$objPHPExcel->getActiveSheet(0)
// 				->getColumnDimension($huruf[$ch3])
// 				->setAutoSize(true);
// 				$objPHPExcel->getActiveSheet(0)
// 				->getColumnDimension($huruf[$ch4])
// 				->setAutoSize(true);	
// 				$objPHPExcel->getActiveSheet(0)
// 				->getColumnDimension($huruf[$ch5])
// 				->setAutoSize(true);								
// 				$objPHPExcel->setActiveSheetIndex(0)
// 				->mergeCells($huruf[$ch1].'1:'.$huruf[$ch1+4].'1')
// 				->setCellValue($huruf[$ch].'1', $x)
// 				->setCellValue($huruf[$ch1].'1', $val);
				
// 				$ch = $ch+6;
// 				$ch1= $ch1+6;
// 				$ch2= $ch2+6;
// 				$ch3= $ch3+6;
// 				$ch4= $ch4+6;
// 				$ch5= $ch5+6;
// 				$mmx[]=1;
// 			}
// 			$mx=(count($mmx)*6)+5;
// 			foreach (range('A', 'E') as $cool) {
// 				$objPHPExcel->getActiveSheet(0)
// 				->getColumnDimension($cool)
// 				->setAutoSize(true);
// 			}
// 			$objPHPExcel->setActiveSheetIndex(0)
// 			->mergeCells('A1:A2')
// 			->mergeCells('B1:B2')
// 			->mergeCells('C1:C2')
// 			->mergeCells('D1:D2')
// 			->mergeCells('E1:E2')
// 			->setCellValue('A1', 'No.')
// 			->setCellValue('B1', 'NIK')
// 			->setCellValue('C1', 'Nama')
// 			->setCellValue('D1', 'Jabatan')
// 			->setCellValue('E1', 'Kantor');
// 			$cch=6;
// 			$cch1=7;
// 			$cch2=8;
// 			$cch3=9;
// 			$cch4=10;
// 			$cch5=11;
// 			foreach ($kpi as $val) {
// 				if ($cek['semester'] == '1') {
// 					$objPHPExcel->setActiveSheetIndex(0)
// 					->setCellValue($huruf[$cch].'2', 'Jan')
// 					->setCellValue($huruf[$cch1].'2', 'Feb')
// 					->setCellValue($huruf[$cch2].'2', 'Mar')
// 					->setCellValue($huruf[$cch3].'2', 'Apr')
// 					->setCellValue($huruf[$cch4].'2', 'Mei')
// 					->setCellValue($huruf[$cch5].'2', 'Jun');
// 				}else{
// 					$objPHPExcel->setActiveSheetIndex(0)
// 					->setCellValue($huruf[$cch].'2', 'Jul')
// 					->setCellValue($huruf[$cch1].'2', 'Agt')
// 					->setCellValue($huruf[$cch2].'2', 'Sep')
// 					->setCellValue($huruf[$cch3].'2', 'Okt')
// 					->setCellValue($huruf[$cch4].'2', 'Nov')
// 					->setCellValue($huruf[$cch5].'2', 'Des');
// 				}
				
// 				$cch = $cch+6;
// 				$cch1= $cch1+6;
// 				$cch2= $cch2+6;
// 				$cch3= $cch3+6;
// 				$cch4= $cch4+6;
// 				$cch5= $cch5+6;
// 			}            
			

// 			$br=3;
// 			$no=1;
// 			foreach ($idk as $k) {
// 				$kr=$this->model_karyawan->emp($k);
// 				$jbt=$this->model_master->k_jabatan($kr['jabatan']);
// 				$lok=$this->model_master->k_loker($kr['unit']);
// 				$objPHPExcel->setActiveSheetIndex(0)
// 				->setCellValue('A'.$br, $no.'.')
// 				->setCellValueExplicit('B'.$br, $kr['nik'], PHPExcel_Cell_DataType::TYPE_STRING)
// 				->setCellValue('C'.$br, $kr['nama'])
// 				->setCellValue('D'.$br, $jbt['jabatan'])
// 				->setCellValue('E'.$br, $lok['nama']);
// 				$cch2=6;
// 				$cch21=7;
// 				$cch22=8;
// 				$cch23=9;
// 				$cch24=10;
// 				$cch25=11;
// 				foreach ($kpi as $val) {
// 					$objPHPExcel->setActiveSheetIndex(0)
// 					->setCellValue($huruf[$cch2].''.$br, 0)
// 					->setCellValue($huruf[$cch21].''.$br, 0)
// 					->setCellValue($huruf[$cch22].''.$br, 0)
// 					->setCellValue($huruf[$cch23].''.$br, 0)
// 					->setCellValue($huruf[$cch24].''.$br, 0)
// 					->setCellValue($huruf[$cch25].''.$br, 0);
// 					$cch2 = $cch2+6;
// 					$cch21= $cch21+6;
// 					$cch22= $cch22+6;
// 					$cch23= $cch23+6;
// 					$cch24= $cch24+6;
// 					$cch25= $cch25+6;
// 				} 
// 				$br++;   
// 				$no++;    	
// 			}            
			
// 			// Rename worksheet
// 			$objPHPExcel->getActiveSheet()->setTitle('Data Aktual');
// 			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
// 			$objPHPExcel->setActiveSheetIndex(0);
// 			// Redirect output to a client’s web browser (Excel5)
// 			header('Content-Type: application/vnd.ms-excel');
// 			header('Content-Disposition: attachment;filename="Nilai Aktual '.$cek['nama_agenda'].'.xls"');
// 			header('Cache-Control: max-age=0');
// 			// If you're serving to IE 9, then the following may be needed
// 			header('Cache-Control: max-age=1');
// 			// If you're serving to IE over SSL, then the following may be needed
// 			header ('Expires: '.date('D, d M Y H:i:s',strtotime($this->date)).' GMT'); // Date in the past
// 			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
// 			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
// 			header ('Pragma: public'); // HTTP/1.0
// 			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
// 			$objWriter->save('php://output');
// 			exit;
// 		}else{
// 			redirect('pages/input_tasks_value/'.$kode);
// 		}
// 	}
// 	$this->messages->allGood(); 
// 	redirect('pages/input_tasks_value/'.$kode); 
// }
// function import_value(){
// 	$kode=$this->input->post('kode');
// 	$cek=$this->model_agenda->cek_agd($kode);
// 	if ($kode == "" || $cek == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/tasks');
// 	}else{
// 		$tb=$this->input->post('tabel_agd');
// 		$fileName = $this->input->post('file', TRUE);

// 		$config['upload_path'] = './asset/upload-exel/'; 
// 		$config['file_name'] = $fileName;
// 		$config['max_size'] = 1000;
// 		$config['allowed_types'] = 'xls|xlsx|csv|ods|ots';

// 		$this->load->library('upload', $config);
// 		$this->upload->initialize($config); 
		
// 		if (!$this->upload->do_upload('file')) {
// 			$this->messages->customFailure($this->upload->display_errors()); 
// 			redirect('pages/input_tasks_value/'.$kode); 
// 		} else {
// 			$media = $this->upload->data();
// 			$inputFileName = './asset/upload-exel/'.$media['file_name'];
			
// 			try {
// 				$inputFileType = IOFactory::identify($inputFileName);
// 				$objReader = IOFactory::createReader($inputFileType);
// 				$objPHPExcel = $objReader->load($inputFileName);
// 			} catch(Exception $e) {
// 				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
// 			}

// 			$sheet = $objPHPExcel->getSheet(0);
// 			$highestRow = ($sheet->getHighestRow());
// 			$highestColumn = $sheet->getHighestColumn();
// 			//echo $highestColumn;
// 			$tri=0;
// 			for ($chrf='A'; $chrf!="AAA"; $chrf++){
// 				$huruf[$tri]=$chrf;
// 				$tri++;
// 			}
// 			$trix=0;
// 			for ($chrfx='F'; $chrfx!=$highestColumn; $chrfx++){
// 				$rr1[$trix]=$chrfx;
// 				$trix++;
// 			}
// 			$trix1=0;
// 			for ($chrfx1='F'; $chrfx1!=$highestColumn; $chrfx1++){
// 				$rr[$trix1]=$chrfx1;
// 				$trix1++;
// 			}
// 			$tt=0;
// 			foreach ($rr as $r) {
// 				if ($tt < count($rr1)) {
// 					$ind[$rr1[$tt]]=$sheet->getCell($rr1[$tt].'1')->getValue();
// 					$tt=$tt+6;
// 				}
// 			}
// 			for ($row = 3; $row <= $highestRow; $row++){  
// 				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
// 					NULL,
// 					TRUE,
// 					FALSE);					
// 				foreach ($ind as $key => $in1) {
// 					$hr=array_search($key, $huruf);
// 					$dtkaa=$this->model_karyawan->emp_nik($rowData[0][1]);
// 					$k=$dtkaa['id_karyawan'];
// 					$jabat=$this->model_master->k_jabatan($dtkaa['jabatan']);
// 					$old=$this->db->get_where($tb,array('kode_indikator'=>$in1,'id_karyawan'=>$k))->row_array();
// 					if ($old != "") {
// 						$im=0;
// 						for ($ix=1; $ix <=6 ; $ix++) {
// 							$nila=$sheet->getCell($huruf[$hr+$im].$row)->getValue();
// 							if ($nila != '0' || $nila != '-') {
// 								$nil=$nila;
// 								$ni1[$ix]='{ADM '.$this->admin.':'.$nil.'},';

// 								if ($old['ln'.$ix] != '0') {
// 									$ni3[$ix]=explode(',', $old['ln'.$ix].''.$ni1[$ix]);
// 									foreach ($ni3[$ix] as $nn3[$ix]) {
// 										$fn=str_replace('{', '', $nn3[$ix]);
// 										$bc=str_replace('}', '', $fn);
// 										$bc1=explode(' ', $bc);
// 										if ($bc1[0] == "ADM") {
// 											$bc2=explode(':', $bc1[1]);
// 											if ($bc2[0] == $this->admin) {
// 												if ($nil != $bc2[1]) {
// 													$va='{ADM '.$this->admin.':'.$bc2[1].'}';
// 													if (($key = array_search($va, $ni3[$ix])) !== false) {
// 														unset($ni3[$ix][$key]);
// 													}
// 												}
// 											}
// 										}
// 									}
// 									$ni2[$ix]=array_unique($ni3[$ix]);
// 								}else{
// 									$ni2[$ix]=explode(',', $ni1[$ix]);
// 								}

// 								$ni[$ix]=array_filter($ni2[$ix]);
// 								$xa=1;
// 								foreach ($ni[$ix] as $naa) {
// 									$fr=str_replace('{', '', $naa);
// 									$bck=str_replace('}', '', $fr);
// 									$bck1=explode(' ', $bck);
// 									$bck2=explode(':', $bck1[1]);
// 									$bck3=$bck2[1];
// 									$nfa[$xa]=$bck3;
// 									$xa++;
// 								}
// 								$nf1[$k][$ix]=array_sum($nfa)/count($ni[$ix]);
// 								$nn[$k][$ix]=($old['bobot']/100)*$nf1[$k][$ix];
// 								if ($old['ln'.$ix] != '0') {
// 									$nii1[$k][$ix]=implode(',', $ni2[$ix]);
// 								}else{
// 									$nii1[$k][$ix]=$ni1[$ix];
// 								}
// 								if ($jabat['kode_periode'] == 'SMT') {
// 									$data[$ix]=array(
// 										'ln6'=>$nii1[$k][$ix],
// 										'nra6'=>$nf1[$k][$ix],
// 										'na6'=>$nn[$k][$ix],
// 									);
// 								}else {
// 									$data[$ix]=array(
// 										'ln'.$ix=>$nii1[$k][$ix],
// 										'nra'.$ix=>$nf1[$k][$ix],
// 										'na'.$ix=>$nn[$k][$ix],
// 									);
// 								}
								
// 								//sql comand
// 								$wh=array('kode_indikator'=>$in1,'id_karyawan'=>$k);
// 								$this->db->where($wh);
// 								$this->db->update($tb,$data[$ix]);
								
// 							} 
// 							$new=$this->db->get_where($tb,array('id_karyawan'=>$k,'kode_indikator'=>$in1))->row_array();
// 							if ($new['ln'.$ix] != '0') {
// 								$nilai_out[$ix]=$new['na'.$ix];
// 							}
// 							$im++;
// 						}
// 					}
					
					
// 					$na_out=array_sum($nilai_out)/count($nilai_out);
// 					$data2=array(
// 						'nilai_out'=>$na_out,
// 					);
// 					$wh1=array('id_karyawan'=>$k,'kode_indikator'=>$in1);
// 					$this->db->where($wh1);
// 					$this->db->update($tb,$data2);
// 				}
// 			}
// 			$this->messages->allGood(); 
// 			redirect('pages/input_tasks_value/'.$kode); 
// 		}
// 	}
// }
// function validate_value()
// {
// 	$id=$this->input->post('id');
// 	if ($id == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/agenda');
// 	}else{
// 		$aktif=$this->input->post('agd');
// 		$val=$this->input->post('val');
// 		if ($aktif == 'aktif') {
// 			$cek=$this->db->query("SELECT * FROM agenda WHERE kode_agenda = '$id'")->row_array();
// 		}else{
// 			$cek=$this->db->query("SELECT * FROM log_agenda WHERE kode_agenda = '$id'")->row_array();
// 		}
// 		if (count($cek) == 0) {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$data=array('validasi'=>$val);
// 			$this->db->where('kode_agenda',$id);
// 			$this->db->update('log_agenda',$data);
// 			$this->db->where('kode_agenda',$id);
// 			$in=$this->db->update('agenda',$data);
// 			if ($in) {
// 				$this->messages->allGood(); 
// 			}else{
// 				$this->messages->allFailure(); 
// 			}
// 			redirect('pages/agenda');
// 		}
// 	}
// }
// function unvalidate_value()
// {
// 	$id=$this->input->post('id');
// 	if ($id == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/agenda');
// 	}else{
// 		$aktif=$this->input->post('agd');
// 		$val=$this->input->post('val');
// 		if ($aktif == 'aktif') {
// 			$cek=$this->db->query("SELECT * FROM agenda WHERE kode_agenda = '$id'")->row_array();
// 		}else{
// 			$cek=$this->db->query("SELECT * FROM log_agenda WHERE kode_agenda = '$id'")->row_array();
// 		}
// 		if (count($cek) == 0) {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$data=array('validasi'=>$val);
// 			$this->db->where('kode_agenda',$id);
// 			$this->db->update('log_agenda',$data);
// 			$this->db->where('kode_agenda',$id);
// 			$in=$this->db->update('agenda',$data);
// 			if ($in) {
// 				$this->messages->allGood(); 
// 			}else{
// 				$this->messages->allFailure(); 
// 			}
// 			redirect('pages/agenda');
// 		}
// 	}
// }
// function validate_value_attd()
// {
// 	$id=$this->input->post('id');
// 	if ($id == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/agenda');
// 	}else{
// 		$aktif=$this->input->post('agd');
// 		$val=$this->input->post('val');
// 		if ($aktif == 'aktif') {
// 			$cek=$this->db->query("SELECT * FROM attd_agenda WHERE kode_agenda = '$id'")->row_array();
// 		}else{
// 			$cek=$this->db->query("SELECT * FROM log_attd_agenda WHERE kode_agenda = '$id'")->row_array();
// 		}
// 		if (count($cek) == 0) {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$data=array('validasi'=>$val);
// 			$this->db->where('kode_agenda',$id);
// 			$this->db->update('log_attd_agenda',$data);
// 			$this->db->where('kode_agenda',$id);
// 			$in=$this->db->update('attd_agenda',$data);
// 			if ($in) {
// 				$this->messages->allGood(); 
// 			}else{
// 				$this->messages->allFailure();  
// 			}
// 			redirect('pages/agenda');
// 		}
// 	}
// }
// function unvalidate_value_attd()
// {
// 	$id=$this->input->post('id');
// 	if ($id == "") {
// 		$this->messages->notValidParam();  
// 		redirect('pages/agenda');
// 	}else{
// 		$aktif=$this->input->post('agd');
// 		$val=$this->input->post('val');
// 		if ($aktif == 'aktif') {
// 			$cek=$this->db->query("SELECT * FROM attd_agenda WHERE kode_agenda = '$id'")->row_array();
// 		}else{
// 			$cek=$this->db->query("SELECT * FROM log_attd_agenda WHERE kode_agenda = '$id'")->row_array();
// 		}
// 		if (count($cek) == 0) {
// 			$this->messages->notValidParam();  
// 			redirect('pages/agenda');
// 		}else{
// 			$data=array('validasi'=>$val);
// 			$this->db->where('kode_agenda',$id);
// 			$this->db->update('log_attd_agenda',$data);
// 			$this->db->where('kode_agenda',$id);
// 			$in=$this->db->update('attd_agenda',$data);
// 			if ($in) {
// 				$this->messages->allGood(); 
// 			}else{
// 				$this->messages->allFailure();  
// 			}
// 			redirect('pages/agenda');
// 		}
// 	}
// }
// function del_db(){
// 	for ($i=1; $i <=5 ; $i++) { 
// 		$data2=array(
// 			'ln'.$i=>0,
// 			'nra'.$i=>0,
// 			'na'.$i=>0,
// 		);
// 		$wh1=array('id_karyawan'=>'10');
// 		$this->db->where($wh1);
// 		$this->db->update('ts5a960acb01c73',$data2);
// 	}
// }

	//===AGENDA KPI DEPARTEMEN===//
	public function agenda_kpi_departemen()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getListAgendaKpiDepartemen();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_a_kpi_departemen,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$count_p=$this->model_agenda->getValueProgressAgenda($d->nama_tabel);

					$progress_data = $this->model_agenda->getListPicKpiDepartemen(['a.kode_a_kpi_departemen'=>$d->kode_a_kpi_departemen],$d->kode_a_kpi_departemen,'all_item');
					if(empty($progress_data)){
						$progress = '<label class="label label-danger">tidak ada data</label>';
					}else{
						$progress_start = 0;
						$progress_end = count($progress_data);
						foreach ($progress_data as $p) {
							if(!empty($p->id_karyawan)){
								$progress_start++;
							}
						}
						$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$progress_start.' / '.$progress_end.' ( '.(($progress_start/$progress_end)*100).'% )" data-placement="right">
						<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.(($progress_start/$progress_end)*100).'" aria-valuemin="0" aria-valuemax="100" style="width: '.(($progress_start/$progress_end)*100).'%">
						</div>
						</div>';
					}

					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$properties=$this->otherfunctions->getPropertiesTable($var);

					$start = $this->formatter->getNameOfMonth($d->start);
					$end = $this->formatter->getNameOfMonth($d->end);

					$datax['data'][]=[
						$d->id_a_kpi_departemen,
						'<a href="'.base_url('pages/input_pic_agenda_kpi_departemen/').$this->codegenerator->encryptChar($d->kode_a_kpi_departemen).'">'.$d->kode_a_kpi_departemen.'</a>',
						$d->nama,
						$d->nama_periode.' ('.$start.' s/d '.$end.')',
						$d->tahun,
						'<center>'.$progress.'</center>',
						$tanggal,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_a_kpi_departemen');
				$data=$this->model_agenda->getListAgendaKpiDepartemen(['a.id_a_kpi_departemen'=>$id]);
				foreach ($data as $d) {
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$datax=[
						'id'=>$d->id_a_kpi_departemen,
						'kode_a_kpi_departemen'=>$d->kode_a_kpi_departemen,
						'nama'=>$d->nama,
						'tanggal'=>$tanggal,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'semester'=>$d->periode,
						'semester_view'=>$d->nama_periode,
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
				$data = $this->codegenerator->kodeAgendaKpiDepartemen();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_agenda_kpi_departemen()
	{ 
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$data=[
				'kode_a_kpi_departemen'=>$kode,
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'periode'=>$this->input->post('semester'),
				'tahun'=>$this->input->post('tahun'),
			];
			
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'agenda_kpi_departemen',$this->model_agenda->checkAgendaKpiDepartemenCode($kode));
		}
		echo json_encode($datax);
	}
	public function edt_agenda_kpi_departemen()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id == '') {
			$datax=$this->messages->notValidParam();
		}else{
			$kode=$this->input->post('kode');
			$data=[
				'kode_a_kpi_departemen'=>$kode,
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'periode'=>$this->input->post('semester'),
				'tahun'=>$this->input->post('tahun'),
			];
			
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_a_kpi_departemen']) {
				$cek=$this->model_master->checkAgendaKpiDepartemenCode($data['kode_a_kpi_departemen']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'agenda_kpi_departemen',['id_a_kpi_departemen'=>$id],$cek);
		}
		echo json_encode($datax);
	}

//===AGENDA KPI PENILAIAN===//
	public function data_pic_kpi_departemen()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$kode = $this->codegenerator->decryptChar($this->input->post('kode_agenda'));
				$data=$this->model_agenda->getListPicKpiDepartemen(['a.kode_a_kpi_departemen'=>$kode],$kode,'all_item');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pic,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$kary = '';
					if(empty($d->id_karyawan)){
						$kary .= '<label class="label label-danger">PIC belum dipilih</label>';
					}else{
						$kary .= '<ol style="padding-left: 15px;">';
						foreach (explode(";", $d->id_karyawan) as $key => $value) {
							$emp = $this->model_karyawan->getEmployeeId($value);
							$kary .= '<li>'.$emp['nama'].'</li>';
						}
						$kary .= '</ol>';
					}

					$get_metode = explode("(=;=)", $d->metode);
					$num_metode = count($get_metode);
					$parse_idemp = $this->otherfunctions->getParseThree($d->id_karyawan);
					$detail = '<table>';
					for ($i=0; $i < $num_metode; $i++) { 
						if(!empty($parse_idemp[($i+1)])){
							$convertEmp = $this->model_global->converAllIn(['table'=>'karyawan','field_set'=>'id_karyawan','field_get'=>'nama','value'=>$parse_idemp[($i+1)]]);
							$convertEmp = implode(", ", $convertEmp);
						}else{
							$convertEmp = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs">PIC Belum Dipilih</label></div>';
						}
						$detail .= '<tr><td style="padding-top: 10px;padding-bottom: 10px;">'.$convertEmp.'</td></tr>';
					}
					$detail .= '</table>';

					$tanggal = (empty($d->create_date) || empty($d->update_date)) ? '<label class="label label-danger">PIC belum dipilih</label>': $properties['tanggal'];
					$status = (empty($d->create_date) || empty($d->update_date)) ? '<label class="label label-danger">PIC belum dipilih</label>': $properties['status'];
					$datax['data'][]=[
						$d->id_pic,
						$d->nama_m_kpi,
						$d->nama_d_kpi,
						$this->otherfunctions->limitWords(nl2br($d->nama_v_kpi), $d->id_pic, 'word'),
						$detail,
						$tanggal,
						$status,
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pic');
				$data=$this->model_agenda->getListPicKpiDepartemen(['a.id_pic'=>$id],null,'all_item');
				$kary = '';
				if(empty($d->id_karyawan)){
					$kary .= '<label class="label label-danger">PIC belum dipilih</label>';
				}else{
					$kary .= '<ol style="padding-left: 15px;">';
					foreach (explode(";", $d->id_karyawan) as $key => $value) {
						$emp = $this->model_karyawan->getEmployeeId($value);
						$kary .= '<li>'.$emp['nama'].'</li>';
					}
					$kary .= '</ol>';
				}
				$create_date = (empty($d->create_date)) ? '<label class="label label-danger">PIC belum dipilih</label>': $this->formatter->getDateTimeMonthFormatUser($d->create_date).'  WIB';
				$update_date = (empty($d->update_date)) ? '<label class="label label-danger">PIC belum dipilih</label>': $this->formatter->getDateTimeMonthFormatUser($d->update_date).'  WIB';
				$nama_buat = (empty($d->nama_buat)) ? '<label class="label label-danger">PIC belum dipilih</label>': $this->otherfunctions->getMark($d->nama_buat);
				$nama_update = (empty($d->nama_update)) ? '<label class="label label-danger">PIC belum dipilih</label>': $this->otherfunctions->getMark($d->nama_update);
				foreach ($data as $d) {
					$get_metode = explode("(=;=)", $d->metode);
					$num_metode = count($get_metode);
					$parse_idemp = $this->otherfunctions->getParseThree($d->id_karyawan);
					$detail = '<table class="table table-bordered table-striped table-responsive" border="1" style="width:100%;"><thead><tr><th>Nama Metode KPI</th><th>Nama PIC</th></tr></thead><tbody>';
					for ($i=0; $i < $num_metode; $i++) { 
						if(!empty($parse_idemp[($i+1)])){
							$convertEmp = $this->model_global->converAllIn(['table'=>'karyawan','field_set'=>'id_karyawan','field_get'=>'nama','value'=>$parse_idemp[($i+1)]]);
							$convertEmp = implode(", ", $convertEmp);
						}else{
							$convertEmp = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs">PIC Belum Dipilih</label></div>';
						}
						$detail .= '<tr><td>'.$get_metode[$i].'</td><td>'.$convertEmp.'</td></tr>';
					}
					$detail .= '</tbody></table>';
					$datax=[
						'id'=>$d->id_pic,
						'kode_a_kpi_departemen'=>$d->kode_a_kpi_departemen,
						'master_kode'=>$d->kode_kpi_departemen,
						'master'=>$d->nama_m_kpi,
						'data_kode'=>$d->kode_data_kpi_departemen,
						'data'=>$d->nama_d_kpi,
						'view_kode'=>$d->kode_view_kpi_departemen,
						'view'=>nl2br($d->nama_v_kpi),
						'detail'=>$detail,
						'num_metode'=>$num_metode,
						'status'=>$d->status,
						'create_date'=>$create_date,
						'update_date'=>$update_date,
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>$nama_buat,
						'nama_update'=>$nama_update,
					];
					for ($i=1; $i <= $num_metode; $i++) { 
						$datax['emp'.($i-1)] = (!empty($parse_idemp[$i])) ? $parse_idemp[$i] : [];
						$datax['metode'.($i-1)] = (!empty($get_metode[($i-1)])) ? $get_metode[($i-1)] : [];
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function ready_page_pic()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$kode_agenda = $this->codegenerator->decryptChar($this->input->post('kode_agenda'));
		$data_kpi = $this->model_master->getListDetailKpiDepartemen();
		foreach ($data_kpi as $dk) {
			$where = [
				'a.kode_a_kpi_departemen'=>$kode_agenda, 
				'a.kode_kpi_departemen'=>$dk->kode_kpi_departemen, 
				'a.kode_data_kpi_departemen'=>$dk->kode_data_kpi_departemen, 
				'a.kode_view_kpi_departemen'=>$dk->kode_view_kpi_departemen, 
			];
			$cek_data = $this->model_agenda->getListPicKpiDepartemen($where,null,'all_item');
			if(empty($cek_data)){
				$data = [
					'kode_a_kpi_departemen'=>$kode_agenda,
					'kode_kpi_departemen'=>$dk->kode_kpi_departemen, 
					'kode_data_kpi_departemen'=>$dk->kode_data_kpi_departemen, 
					'kode_view_kpi_departemen'=>$dk->kode_view_kpi_departemen, 
				];
				$this->model_global->insertQuery($data,'data_pic_kpi_departemen');
			}
		}
		$datax = ['status'=>'true'];
		echo json_encode($datax);
	}
	public function edt_pic_kpi_departemen()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$id=$this->input->post('id');
		$num = $this->input->post('num');
		if ($id != '') {
			$exPic = [];
			for ($i=1; $i <= $num; $i++) {
				$pic = implode(";", $this->input->post('pic'.$i));
				$exPic[] = $i.":".$pic;
			}
			$getPic = implode("/", $exPic);
			$data=[
				'id_karyawan'=>$getPic,
			];
			
			$cek_data = $this->otherfunctions->convertResultToRowArray($this->model_agenda->getListPicKpiDepartemen(['a.id_pic'=>$id],null,'all_item'));
			if(empty($cek_data['create_date'])){
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			}else{
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			}
			$datax = $this->model_global->updateQuery($data,'data_pic_kpi_departemen',['id_pic'=>$id]);
		}else{
			$datax=$this->messages->notValidParam();
		}

		echo json_encode($datax);
	}
	public function data_input_kpi_departemen()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$emp_data = $this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
				$where = ['a.tgl_mulai <='=> $this->date,'a.tgl_selesai >='=> $this->date];
				$data=$this->model_agenda->getListAgendaKpiDepartemen($where);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$tgl = $this->date;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_a_kpi_departemen,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$val_ket = null;
					if(!empty($emp_data['id_karyawan'])){
						if(!empty($d->keterangan)){
							$ket1 = explode(";",$d->keterangan);
							foreach ($ket1 as $key1 => $value1) {
								$ket2 = explode(":",$value1);
								if($ket2[0] == $emp_data['id_karyawan']){
									$val_ket = $ket2[1];
								}
							}
						}
					}else{
						if(!empty($d->keterangan)){
							$ket1 = explode(";",$d->keterangan);
							foreach ($ket1 as $key1 => $value1) {
								$ket2 = explode(":",$value1);
								if($ket2[0] == 'A_'.$this->admin){
									$val_ket = $ket2[1];
								}
							}
						}
					}
					$keterangan = '';
					if ($val_ket == "not_entry" || $val_ket == null || $val_ket == "") {
						$keterangan .= '<label class="label label-danger">Belum Ada Data</label>';
					}elseif ($val_ket == "progress") {
						$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
					}else{
						$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
					}

					$progress_data = $this->model_agenda->getListPicKpiDepartemen(['a.kode_a_kpi_departemen'=>$d->kode_a_kpi_departemen],$d->kode_a_kpi_departemen,'all_item');
					if(empty($progress_data)){
						$progress = '<label class="label label-danger">tidak ada data</label>';
					}else{
						$progress_start = 0;
						$progress_end = count($progress_data);
						foreach ($progress_data as $p) {
							if(!empty($p->id_karyawan)){
								$progress_start++;
							}
						}
						$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$progress_start.' / '.$progress_end.' ( '.(($progress_start/$progress_end)*100).'% )" data-placement="right">
						<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.(($progress_start/$progress_end)*100).'" aria-valuemin="0" aria-valuemax="100" style="width: '.(($progress_start/$progress_end)*100).'%">
						</div>
						</div>';
					}

					$start = $this->formatter->getNameOfMonth($d->start);
					$end = $this->formatter->getNameOfMonth($d->end);
					$print = '';
					if(!empty($var['access']['l_ac']['prn'])){
						$print = '<a class="btn btn-primary" href="'.base_url('pages/print_data_input_kpi_departemen/'.$this->codegenerator->encryptChar($d->kode_a_kpi_departemen)).'" target="_blank"><i class="fas fa-print"></i></a>';
					}
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_a_kpi_departemen,
						$d->nama,
						'<center>'.$progress.'</center>',
						$tanggal,
						$keterangan,
						$this->codegenerator->encryptChar($d->kode_a_kpi_departemen),
						$d->tahun,
						$d->nama_periode.' ('.$start.' s/d '.$end.')',
						$this->codegenerator->encryptChar(1),
						$print,
					];
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_i_nilai_kpip()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$kode_agenda = $this->input->post('kode_agenda');
		$kode_mkpi = $this->input->post('kode_mkpi');
		$number = $this->input->post('number');
		$id_karyawan = $this->input->post('id_karyawan');
		$tahun = $this->input->post('tahun');
		// $emp = $this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($id_karyawan));

		$keyx = ['target','aktual','evaluasi'];

		/*Master Data*/
		for ($i=1; $i < 7; $i++) { 
			${'master_n'.$i} = $this->input->post('master_n'.$i);
		}
		$data=[
			'kode_a_kpi_departemen'=>$kode_agenda,
			'kode_kpi_departemen'=>$kode_mkpi,
			'id_karyawan'=>$id_karyawan,
			'tahun'=>$tahun
		];
		$cek = $this->otherfunctions->convertResultToRowArray($this->model_agenda->getListNilaiKpiDepartemen($data,'all_item','single'));
		if(!empty($cek)){
			for ($i=1; $i < 7; $i++) { 
				$data['n'.$i] = $this->otherfunctions->convertKeyValue($keyx,${'master_n'.$i},'number');
			}
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQuery($data,'nilai_kpi_departemen_master',['id_nilai'=>$cek['id_nilai']]);
		}else{
			for ($i=1; $i < 7; $i++) { 
				$data['n'.$i] = $this->otherfunctions->convertKeyValue($keyx,${'master_n'.$i},'number');
			}
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$this->model_global->insertQuery($data,'nilai_kpi_departemen_master');
		}

		/*View Data*/
		$kode_vkpi = $this->input->post('kode_vkpi');
		$kode_dkpi = $this->input->post('kode_dkpi');
		$keyxv = ['target_kpi','target','evaluasi','remark'];
		foreach ($kode_vkpi as $vkey => $vval) {
			$datav=[
				'kode_a_kpi_departemen'=>$kode_agenda,
				'kode_kpi_departemen'=>$kode_mkpi,
				'kode_data_kpi_departemen'=>$kode_dkpi[$vkey],
				'kode_view_kpi_departemen'=>$vval,
				'id_karyawan'=>$id_karyawan,
				'tahun'=>$tahun,
			];
			for ($iv=1; $iv < 7; $iv++) { 
				${'view_n'.$iv} = $this->input->post('view_n'.$iv.$vval);
			}
			$cekv = $this->otherfunctions->convertResultToRowArray($this->model_agenda->getListNilaiKpiDepartemenView($datav,'all_item','single'));
			if(!empty($cekv)){
				for ($iv=1; $iv < 7; $iv++) { 
					$datav['n'.$iv] = $this->otherfunctions->convertKeyValue($keyxv,${'view_n'.$iv},'number');
				}
				$datav['hasil_aktivitas'] = $this->input->post('hasil_'.$vval);
				$datav=array_merge($datav,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQuery($datav,'nilai_kpi_departemen_view',['id_nilai'=>$cekv['id_nilai']]);
			}else{
				for ($iv=1; $iv < 7; $iv++) { 
					$datav['n'.$iv] = $this->otherfunctions->convertKeyValue($keyxv,${'view_n'.$iv},'number');
				}
				$datav['hasil_aktivitas'] = $this->input->post('hasil_'.$vval);
				$datav=array_merge($datav,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQuery($datav,'nilai_kpi_departemen_view');
			}
		}

		$datax = $this->messages->allGood();
		echo json_encode($datax);
	}
	public function edt_ket_kpi_departemen()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$kode_agenda = $this->input->post('kode_agenda');
		$usage = $this->input->post('usage');
		$id_admin = $this->admin;
		$emp_data = $this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
		if(!empty($emp_data['id_karyawan'])){
			$id_karyawan = $emp_data['id_karyawan'];
		}else{
			$id_karyawan = 'A_'.$this->admin;
		}
		$data_agenda = $this->otherfunctions->convertResultToRowArray($this->model_agenda->getListAgendaKpiDepartemen(['a.kode_a_kpi_departemen'=>$kode_agenda]));
		$keterangan = $data_agenda['keterangan'];
		if($usage != 'last'){
			$val_ket = 'progress';
		}else{
			$val_ket = 'selesai';
		}
		$new_ket = $this->exam->getNilaiPackRemove($val_ket,$id_karyawan,$keterangan);
		$data = [
			'keterangan'=>$new_ket
		];
		$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		$this->model_global->updateQueryNoMsg($data,'agenda_kpi_departemen',['kode_a_kpi_departemen'=>$kode_agenda]);
		echo json_encode(['status'=>'success']);
	}
}