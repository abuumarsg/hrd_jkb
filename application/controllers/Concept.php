<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	/**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Controller Concept
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

class Concept extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		if ($this->session->has_userdata('adm')) {
			$this->admin = $this->session->userdata('adm')['id'];	 
		}else{ 
			redirect('auth');
		}
		$dtroot['admin']=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
		$nm=explode(" ", $dtroot['admin']['nama']);
		$datax['adm'] = array(
			'nama'=>$nm[0],
			'email'=>$dtroot['admin']['email'],
			'kelamin'=>$dtroot['admin']['kelamin'],
			'foto'=>$dtroot['admin']['foto'],
			'create'=>$dtroot['admin']['create_date'],
			'update'=>$dtroot['admin']['update_date'],
			'login'=>$dtroot['admin']['last_login'],
			'level'=>$dtroot['admin']['level'],
		);
		$this->dtroot=$datax;
		$this->max_range=$this->otherfunctions->poin_max_range();
		$this->max_month=$this->otherfunctions->column_value_max_range();
	}
	public function index(){
		redirect('pages/dashboard');
	}
//--------------------------------------------------------------------------------------------------------------//
//Concept Sikap
	public function concept_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_concept->getListKonsepSikap();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_c_sikap,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_c_sikap,
						$d->kode_c_sikap,
						$d->nama,
						$d->nama_tabel,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->kode_c_sikap)
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_c_sikap');
				$data=$this->model_concept->getKonsepSikap($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_c_sikap,
						'kode_c_sikap'=>$d->kode_c_sikap,
						'nama'=>$d->nama,
						'nama_tabel'=>$d->nama_tabel,
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
				$data = $this->codegenerator->kodeKonsepSikap();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_concept_sikap(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$table=$this->exam->getNameTable('concept_sikap');
			$gen=$this->model_concept->generateKonsepSikap($table);
			if ($gen) {
				$data=[
					'kode_c_sikap'=>strtoupper($kode),
					'nama'=>ucwords($this->input->post('nama')),
					'nama_tabel'=>$table,
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'concept_sikap',$this->model_concept->checkKonsepSikapCode($kode));
			}else{
				$datax=$this->messages->customFailure('Generate Tabel Gagal, Mohon Kontak Administrator!');
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_concept_sikap(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_c_sikap'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_c_sikap']) {
				$cek=$this->model_concept->checkKonsepSikapCode($data['kode_c_sikap']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'concept_sikap',['id_c_sikap'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function view_data_konsep_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$table=$this->input->post('table');
		$kode_c=$this->codegenerator->decryptChar($this->input->post('code'));
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$lokasi = $this->input->post('lokasi');
				$where = (!empty($lokasi)?['c_new.kode_loker'=>$lokasi]:null);
				$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
				$data=$this->model_concept->openTableViewConceptSikap($table,$filter,$where);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_c_sikap,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_c_sikap,
						$d->nik,
						$d->nama.' '.((($d->nama_jabatan != $d->jabatan_now) || ($d->nama_loker != $d->loker_now))?' <i class="fa fa-user-times" style="color:orange" data-toggle="tooltip" title="Sesuaikan Data HRIS"></i>':null),
						(!empty($d->nama_jabatan)) ? $d->nama_jabatan : $this->otherfunctions->getMark(),
						(!empty($d->bagian)) ? $d->bagian : $this->otherfunctions->getMark(),
						(!empty($d->nama_loker)) ? $d->nama_loker : $this->otherfunctions->getMark(),
						(!empty($d->partisipan)) ? count($this->exam->getPartisipantKode($d->partisipan)).' Partisipan' : '0 Partisipan',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik),
						$this->codegenerator->encryptChar($kode_c),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_c_sikap');
				$data=$this->model_concept->openTableViewConceptSikapId($table,$id);
				foreach ($data as $d) {
					$par=$this->exam->getPartisipantKode($d->partisipan);
					$table_data='';
					if (isset($par)) {
						$table_data.='<table class="table table-striped table-hover">
						<thead>
						<tr class="bg-blue">
						<th>No.</th>
						<th>NIK</th>
						<th>Nama Karyawan</th>
						<th>Jabatan</th>
						<th>Lokasi Kerja</th>
						<th>Bagian</th>
						<th>Sebagai</th>
						</tr>
						</thead>
						<tbody>';
						$c=1;
						foreach ($par as $k_p=>$v_p) {
							$emp=$this->model_karyawan->getEmployeeId($k_p);
							$sebagai=$this->exam->getWhatIsPartisipan($v_p);
							$table_data.='<tr>
							<td>'.$c.'.</td>
							<td>'.$emp['nik'].'</td>
							<td>'.$emp['nama'].(($emp['status_emp'] != 1)?' <i class="fa fa-user-times stat err" title="Karyawan Non-Aktif"></i>':'').'</td>
							<td>'.$emp['nama_jabatan'].'</td>
							<td>'.$emp['nama_loker'].'</td>
							<td>'.$emp['bagian'].'</td>
							<td>'.$sebagai.'</td>
							</tr>';
							$c++;
						}
						$table_data.='</tbody>
						</table>';
					}else{
						$table_data.='<div class="callout callout-danger"><b><i class="fa fa-info-circle"></i> Data Partisipan Kosong</b></div>';
					}
					$datax=[
						'id'=>$d->id_c_sikap,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik,
						'nama'=>$d->nama,
						'nama_jabatan'=>$d->nama_jabatan,
						'nama_loker'=>$d->nama_loker,
						'bagian'=>$d->bagian,
						'jumlah_partisipan'=>(!empty($d->partisipan)) ? count($this->otherfunctions->getParseOneLevelVar($d->partisipan)).' Partisipan' : '0 Partisipan',
						'table_data'=>$table_data,
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
			}elseif ($usage == 'get_employee') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
				$data = $this->model_concept->getEmployeeSikap($table,$filter);
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_data_konsep_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id_karyawan');
		$opsi=$this->input->post('opsi_m');
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		if ($id != "" || $opsi != null) {
			$emp=$this->model_karyawan->getEmployeeId($id);
			$atasan=null;
			$bawahan=null;
			$rekan=null;
			if (isset($opsi)) {
				foreach ($opsi as $o) {
					if ($o == 'ATS') {
						$atasan = $this->exam->getPartisipantPack($this->model_karyawan->getEmployeeAtasan($id),'atasan');
					}
					if ($o == 'BWH') {
						$bawahan = $this->exam->getPartisipantPack($this->model_karyawan->getEmployeeBawahan($id),'bawahan');
					}
					if ($o == 'RKN') {
						$rekan = $this->exam->getPartisipantPack($this->model_karyawan->getEmployeeRekan($id),'rekan');
					}
				}
			}
						
			$partisipan=$this->exam->getPartisipantDb($atasan,$bawahan,$rekan);
			if ($partisipan == null) {
				$datax=$this->messages->customFailure('Data Partisipan Kosong');
			}else{
				$bobot_sikap=$this->exam->getBobotData($this->exam->getBobotCode($partisipan));
				$data=[
					'id_karyawan'=>$id,
					'kode_jabatan'=>$emp['jabatan'],
					'kode_loker'=>$emp['loker'],
					'partisipan'=>$partisipan,
				];
				$data=array_merge($data,$bobot_sikap);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,$table);
				$this->model_agenda->updateFromConceptSikap(['id_karyawan'=>$id,'table'=>$table],'karyawan_add');
			}	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function del_employee_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$table=$this->input->post('table');
		$id=$this->input->post('id');
		if (!empty($id) && !empty($table)) {
			$this->model_agenda->updateFromConceptSikap(['id_karyawan'=>$id],'karyawan_del');
			$datax = $this->model_global->deleteQuery($table,['id_karyawan'=>$id]);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function generate_data_konsep_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		$opsi=$this->input->post('opsi');
		if ($table != null && $opsi != null) {
			$emp=$this->model_karyawan->getEmployeeAllActive();
			$err=[];
			foreach ($emp as $e) {
				$id=$e->id_karyawan;
				$atasan=null;
				$bawahan=null;
				$rekan=null;
				foreach ($opsi as $o) {
					if ($o == 'ATS') {
						$atasan = $this->exam->getPartisipantPack($this->model_karyawan->getEmployeeAtasan($id),'atasan');
					}
					if ($o == 'BWH') {
						$bawahan = $this->exam->getPartisipantPack($this->model_karyawan->getEmployeeBawahan($id),'bawahan');
					}
					if ($o == 'RKN') {
						$rekan = $this->exam->getPartisipantPack($this->model_karyawan->getEmployeeRekan($id),'rekan');
					}
				}
				$partisipan=$this->exam->getPartisipantDb($atasan,$bawahan,$rekan);
				if ($partisipan != null) {
					$bobot_sikap=$this->exam->getBobotData($this->exam->getBobotCode($partisipan));
					$data=[
						'id_karyawan'=>$id,
						'kode_jabatan'=>$e->jabatan,
						'kode_loker'=>$e->loker,
						'partisipan'=>$partisipan,
					];
					$data=array_merge($data,$bobot_sikap);
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertUpdateQueryNoMsg($data,$table,['id_karyawan'=>$data['id_karyawan']]);
				}else{
					array_push($err,1);
				}	
			}
			if (count($err) > 0) {
				$datax=$this->messages->customWarning('Beberapa Data Partisipan Kosong Untuk '.count($err).' Karyawan');
			}else{
				$datax=$this->messages->allGood();
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function view_detail_konsep_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$table=$this->input->post('table');
		$kode_c=$this->codegenerator->decryptChar($this->input->post('code'));
		$id_e=$this->input->post('id');
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			$data=$this->model_concept->openTableViewConceptSikapEmpId($table,$id_e);
			if ($usage == 'view_all') {

				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];				
				foreach ($data as $d) {
					$par=$this->exam->getPartisipantKode($d->partisipan);
					if (isset($par)) {
						$bobot=$this->otherfunctions->getParseVar($d->sub_bobot_ats);
						foreach ($par as $k_p => $v_p) {
							$access['l_ac']=$this->otherfunctions->unsetArrayValue($access['l_ac'],'DEL');
							$val_b=null;
							if (isset($bobot[$k_p])) {
								$val_b=$bobot[$k_p];
							}
							$var=[
								'id'=>$k_p,
								'access'=>$access,
							];
							$properties=$this->otherfunctions->getPropertiesTable($var);

							$emp=$this->model_karyawan->getEmployeeId($k_p);
							$sebagai=$this->exam->getWhatIsPartisipan($v_p);
							$sub_bobot='<p class="text-muted"><i class="fa fa-info-circle"></i> Bukan Atasan</p>';
							if ($v_p == 'ATS') {
								$sub_bobot='<input type="hidden" name="b_atasan[]" value="'.$k_p.'"><input type="number" class="form-control" name="sub_bobot_ats[]" onkeyup="countbobot(\'sub_bobot_ats[]\',\'sub_bobot\')" placeholder="Masukkan Bobot Atasan" value="'.$val_b.'">';
							}
							$datax['data'][]=[
								$k_p,
								'<input type="checkbox" class="partisipan_check" name="partisipan_check" value="'.$v_p.':'.$k_p.'" onchange="checkvalue()">',
								$emp['nik'],
								$emp['nama'],
								$emp['nama_jabatan'],
								$emp['bagian'],
								$emp['nama_loker'],								
								$sebagai,
								$sub_bobot,
								$properties['aksi'],
							];
						}
					}
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_e');
				foreach ($data as $d) {
					$par=$this->exam->getPartisipantKode($d->partisipan);
					if (isset($par[$id])) {
						$emp=$this->model_karyawan->getEmployeeId($id);
						$sebagai=$this->exam->getWhatIsPartisipan($par[$id]);
						$datax=[
							'id'=>$id,
							'nik'=>$emp['nik'],
							'nama'=>$emp['nama'],
							'nama_jabatan'=>$emp['nama_jabatan'],
							'nama_loker'=>$emp['nama_loker'],
							'bagian'=>$emp['bagian'],
							'sebagai'=>$sebagai,
							'sebagai_val'=>$par[$id],
							'old'=>$par[$id].':'.$id,
						];
					}
				}
				echo json_encode($datax);
			}elseif ($usage == 'get_employee'){
				$datax=$this->model_concept->getEmployeePartisipan($table,$id_e);
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_detail_konsep_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');	
		$id=$this->codegenerator->decryptChar($this->input->post('id'));
		$id_e=$this->input->post('id_karyawan');
		$opsi=$this->input->post('opsi');
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		if ($id != "" || $opsi != null) {
			$data_t=$this->model_concept->openTableViewConceptSikapEmpId($table,$id);
			foreach ($data_t as $d) {
				$par=$d->partisipan;
				$par=$this->exam->addPartisipanDb($id_e,$opsi,$par);
				$bobot_sikap=$this->exam->getBobotData($this->exam->getBobotCode($par));
				$data=['partisipan'=>$par];
				$data=array_merge($data,$bobot_sikap);
				$data_to_agenda=$data;
				$data_to_agenda['id_karyawan']=$id;
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_agenda->updateFromConceptSikap($data_to_agenda,'partisipan_add');
				$datax = $this->model_global->updateQuery($data,$table,['id_karyawan'=>$id]);
			}	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function delete_many_partisipant()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');	
		$id=$this->codegenerator->decryptChar($this->input->post('id'));
		$list=$this->input->post('partisipan_hide');
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		$sel_all=$this->input->post('select_all');
		if ($id != "" && $table != null) {
			$list=explode(';',str_replace(',', ';', $list));
			$data_t=$this->model_concept->openTableViewConceptSikapEmpId($table,$id);
			foreach ($data_t as $d) {
				$par=$d->partisipan;
				$s_b=$d->sub_bobot_ats;
				if ($sel_all == 'all') {
					$par=null;
					$sub_bobot=null;
				}else{
					$par=$this->exam->delPartisipantDb($list,$par);
					$sub_bobot=$this->exam->delSubBobotAtasanDb($list,$s_b);
				}
				$bobot_sikap=$this->exam->getBobotData($this->exam->getBobotCode($par));
				$data=['partisipan'=>$par,'sub_bobot_ats'=>$sub_bobot];
				$data=array_merge($data,$bobot_sikap);
				$data_to_agenda=$data;
				$data_to_agenda['id_karyawan']=$id;
				$data_to_agenda['partisipan_del']=$list;
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_agenda->updateFromConceptSikap($data_to_agenda,'partisipan_del');
				$datax = $this->model_global->updateQuery($data,$table,['id_karyawan'=>$id]);
			}	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_sub_bobot_atasan()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');	
		$id=$this->codegenerator->decryptChar($this->input->post('id'));
		$list=$this->input->post('b_atasan');
		$bobot=$this->input->post('sub_bobot_ats');
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		if ($id != "" && $list != null && $table != null) {
			$pack=[];
			foreach ($list as $k_l => $v_l) {
				array_push($pack,$v_l.':'.$bobot[$k_l]);
			}
			$pack=implode(';',$pack);
			$data=['sub_bobot_ats'=>$pack];
			$data_to_agenda=$data;
			$data_to_agenda['id_karyawan']=$id;
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$this->model_agenda->updateFromConceptSikap($data_to_agenda,'partisipan_add');
			$datax = $this->model_global->updateQuery($data,$table,['id_karyawan'=>$id]);	
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_partisipant()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');	
		$id_e=$this->codegenerator->decryptChar($this->input->post('id_e'));
		$opsi=$this->input->post('opsi_m');
		$old=$this->input->post('old');
		$id=$this->input->post('id');
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		if ($id != "" && $opsi != null && $table != null && $id_e != null && $old != null) {
			$new=$opsi.':'.$id;
			$data_t=$this->model_concept->openTableViewConceptSikapEmpId($table,$id_e);
			foreach ($data_t as $d) {
				$par=$d->partisipan;
				$s_b=$d->sub_bobot_ats;
				$par=$this->exam->delPartisipantDb([$old],$par);
				$par=$this->exam->addPartisipanDb([$id],$opsi,$par);
				$what=$this->exam->getPartisipantPiece($old,'front');
				if ($what == 'ATS') {
					$sub_bobot=$this->exam->delSubBobotAtasanDb([$old],$s_b);
				}else{
					$sub_bobot=$s_b;
				}
				$bobot_sikap=$this->exam->getBobotData($this->exam->getBobotCode($par));
				$data=['partisipan'=>$par,'sub_bobot_ats'=>$sub_bobot];
				$data=array_merge($data,$bobot_sikap);
				$data_to_agenda=$data;
				$data_to_agenda['id_karyawan']=$id_e;
				$data_to_agenda['id_partisipan']=$id;
				$data_to_agenda['partisipan_del']=[$old];
				$data_to_agenda['old']=$what;
				$data_to_agenda['new']=$opsi;
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_agenda->updateFromConceptSikap($data_to_agenda,'partisipan_edit');
				$datax = $this->model_global->updateQuery($data,$table,['id_karyawan'=>$id_e]);
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function sync_from_agenda_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		$table=$this->input->post('table');
		$table_agenda=$this->input->post('agenda');
		if (empty($kode) || empty($table) || empty($table_agenda)) {
			echo json_encode($this->messages->notValidParam());
		}else{
			$kode=$this->codegenerator->decryptChar($kode);
			$this->model_global->turncateTable($table);
			$data=$this->model_agenda->dataAgendaSikapToConcept($table_agenda);
			$msg=$this->messages->allFailure();
			if (isset($data)) {
				foreach ($data as $d) {
					$data_in=[
						'id_karyawan'=>$d->id_karyawan,
						'kode_jabatan'=>$d->kode_jabatan,
						'kode_loker'=>$d->kode_loker,
						'partisipan'=>$d->partisipan,
						'bobot_ats'=>$d->bobot_ats,
						'bobot_bwh'=>$d->bobot_bwh,
						'bobot_rkn'=>$d->bobot_rkn,
						'sub_bobot_ats'=>$d->sub_bobot_ats,				
					];
					$data_in=array_merge($data_in,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQueryNoMsg($data_in,$table);
				}
				$msg=$this->messages->allGood();
			}
			echo json_encode($msg);
		}
	}
//--------------------------------------------------------------------------------------------------------------//
//Concept Kompetensi
	public function concept_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_concept->getListKonsepKompetensi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_c_kompetensi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_c_kompetensi,
						$d->kode_c_kompetensi,
						$d->nama,
						$d->nama_tabel,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->kode_c_kompetensi)
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_c_kompetensi');
				$data=$this->model_concept->getKonsepKompetensi($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_c_kompetensi,
						'kode_c_kompetensi'=>$d->kode_c_kompetensi,
						'nama'=>$d->nama,
						'nama_tabel'=>$d->nama_tabel,
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
				$data = $this->codegenerator->kodeKonsepKompetensi();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_concept_kompetensi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$table=$this->exam->getNameTable('concept_kompetensi');
			$gen=$this->model_concept->generateKonsepKompetensi($table);
			if ($gen) {
				$data=[
					'kode_c_kompetensi'=>strtoupper($kode),
					'nama'=>ucwords($this->input->post('nama')),
					'nama_tabel'=>$table,
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'concept_kompetensi',$this->model_concept->checkKonsepSikapCode($kode));
			}else{
				$datax=$this->messages->customFailure('Generate Tabel Gagal, Mohon Kontak Administrator!');
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_concept_kompetensi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_c_kompetensi'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_c_kompetensi']) {
				$cek=$this->model_concept->checkKonsepSikapCode($data['kode_c_kompetensi']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'concept_kompetensi',['id_c_kompetensi'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------//
//View Concept Kompetensi
	public function view_data_konsep_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$table=$this->input->post('table');
		$kode_c=$this->input->post('code');
		$tipe_jabatan=$this->input->post('tipe_jabatan');
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_concept->openTableViewConceptKompetensi($table);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				if (isset($data)) {
					foreach ($data as $d) {
						$var=[
							'id'=>'"'.$d->id_karyawan.'"',
							'access'=>$access,
							'status'=>null,
						];

						$properties=$this->otherfunctions->getPropertiesTable($var);
						$datax['data'][]=[
							$d->id_karyawan,
							$d->nik,
							$d->nama.' '.((($d->nama_jabatan != $d->jabatan_now) || ($d->nama_loker != $d->loker_now))?' <i class="fa fa-user-times" style="color:orange" data-toggle="tooltip" title="Sesuaikan Data HRIS"></i>':null),
							$d->nama_jabatan,
							$d->nama_bagian,
							$d->nama_loker,
							$d->jumlah.' Kompetensi',
							$properties['aksi'],
							$kode_c,
							$this->codegenerator->encryptChar($d->nik),
						];
						$no++;
					}
				}				
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id_karyawan=$this->input->post('id_karyawan');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				if ($this->uri->segment(4) == 'table_view') {
					$data=$this->model_concept->openTableViewConceptKompetensiEmployee($table,$id_karyawan,'no');
					foreach ($data as $d) {
						$datax['data'][]=[
							$d->kode_kompetensi,
							$d->kompetensi,
							($d->definisi)?$d->definisi:$this->otherfunctions->getMark(),
							($d->kode_kategori)?$d->kode_kategori.' COMPETENCY':$this->otherfunctions->getMark(),
						];
					}
				}else{
					$data=$this->model_concept->openTableViewConceptKompetensiEmployee($table,$id_karyawan);
					foreach ($data as $d) {
						$penilai_view=$this->otherfunctions->getMark();
						$penilai_dt=$this->otherfunctions->getParseOneLevelVar($d->penilai);
						if (isset($penilai_dt) && $d->penilai != '') {
							$penilai_view='<ol>';
							foreach ($penilai_dt as $pdt) {
								$dt_p_detail=$this->model_karyawan->getAssessorId($pdt);
								$penilai_view.='<li>'.$dt_p_detail['nama'].'</li>';
							}
							$penilai_view.='</ol>';
						}
						$datax=[
							'id_karyawan'=>$d->id_karyawan,
							'nik'=>$d->nik,
							'nama'=>$d->nama,
							'nama_jabatan'=>$d->nama_jabatan,
							'nama_bagian'=>$d->nama_bagian,
							'nama_loker'=>$d->nama_loker,
							'penilai_val'=>($d->penilai)?$this->otherfunctions->getParseOneLevelVar($d->penilai):null,
							'penilai'=>$penilai_view,
							'jumlah_kompetensi'=>($d->jumlah > 0) ? $d->jumlah.' Kompetensi' : $this->otherfunctions->getMark(),
						];
					}
				}				
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeKonsepKpi();
				echo json_encode($data);
			}elseif ($usage == 'select_employee'){
				$tabel=$this->input->post('tabel');
				$jabatan=$this->input->post('jabatan');
				$data=$this->model_concept->getEmployeeKompetensi($tabel,$jabatan);
				echo json_encode($data);
			}elseif ($usage == 'select_jabatan'){
				$tabel=$this->input->post('tabel');
				$data=$this->model_concept->getJabatanKompetensi($tabel);
				echo json_encode($data);
			}elseif ($usage == 'view_kategori_kompetensi'){
				
				$jbt=$this->input->post('kode_jabatan');
				$data_jbt=$this->model_master->getJabatanKode($jbt);
				$pack=[];
				$class=['primary','success','warning','info'];
				if (isset($data_jbt['tipe_jabatan'])) {
					$data=$this->model_master->getListKategoriKompetensi($data_jbt['tipe_jabatan']);				
					if (isset($data)) {
						$cnt=0;
						$table='';
						foreach ($data as $d) {
							if (!strstr( $d->kode_kategori, 'CORE')) {
								$table='<div class="col-md-6">
								<div class="box box-'.((isset($class[$cnt]))?$class[$cnt]:'primary').' box-solid">
								<div class="box-header with-border">
								<h3 class="box-title">'.$d->nama.'</h3>
								</div>
								<div class="box-body">
								<input type="hidden" name="kode_kompetensi_hidden_'.$d->kode_kategori.'" value="">
								<table id="table_data_'.$d->kode_kategori.'" data-tipe="'.$data_jbt['tipe_jabatan'].'" class="table table-bordered table-striped" width="100%">
								<thead>
								<tr>
								<th style="width: 10%;"></th>
								<th style="width: 45%;">Kode</th>
								<th style="width: 45%;">Kompetensi</th>
								<th style="width: 45%;">Aspek</th>
								</tr>
								</thead>
								</table>
								</div>
								</div>
								</div>';
								$pack[$d->kode_kategori]=$table;
								$cnt++;
							}
						}
					}
				}
				
				echo json_encode($pack);
			}elseif ($usage == 'jenis_kompetensi_FUNCTIONAL'){
				$datak=$this->model_master->getListKuisionerFunctionalKompetensiActive($tipe_jabatan);
				$datax['data']=[];
				foreach ($datak as $d) {
					$datax['data'][]=[
						'<input type="checkbox" name="kode_kuisioner" style="cursor:pointer" value="'.$d->kode_kuisioner.'" onchange="checkvalue(\'FUNCTIONAL\')">',
						$d->kode_kuisioner,
						$d->kuisioner,
						$d->nama_aspek,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'jenis_kompetensi_PROFESIONAL'){
				$datak=$this->model_master->getListKuisionerProfesionalKompetensiActive($tipe_jabatan);
				$datax['data']=[];
				foreach ($datak as $d) {
					$datax['data'][]=[
						'<input type="checkbox" name="kode_kuisioner" style="cursor:pointer" value="'.$d->kode_kuisioner.'" onchange="checkvalue(\'PROFESIONAL\')">',
						$d->kode_kuisioner,
						$d->kuisioner,
						$d->nama_aspek,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'jenis_kompetensi_TECHNICAL'){
				$datak=$this->model_master->getListKuisionerTechnicalKompetensiActive($tipe_jabatan);
				$datax['data']=[];
				foreach ($datak as $d) {
					$datax['data'][]=[
						'<input type="checkbox" name="kode_kuisioner" style="cursor:pointer" value="'.$d->kode_kuisioner.'" onchange="checkvalue(\'TECHNICAL\')">',
						$d->kode_kuisioner,
						$d->kuisioner,
						$d->nama_elemen,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function get_data_kuisioner_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if (!$id) 
			echo json_encode($this->messages->notValidParam());
		$data=$this->model_karyawan->getEmployeeId($id,1);
		if (!isset($data)) 
			echo json_encode($this->messages->notValidParam());
		$tipe_jabatan=$data['tipe_jabatan'];
		$tipe_jabatan=$this->exam->getJenisAspekKompetensiTipeJabatan($tipe_jabatan);
		$datax=[];
		if (isset($tipe_jabatan)) {
			$datax['tipe_jabatan']=$tipe_jabatan;
		}
		echo json_encode($datax); 

	}
	function add_jabatan_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		//define post
		$datax=$this->messages->notValidParam();
		$jbt=$this->input->post('jabatan');
		$emp=$this->input->post('karyawan');
		$tabel=$this->input->post('tabel');
		$penilai=$this->input->post('penilai');
		$penilai=($penilai)?implode(';', array_filter($penilai)):null;
		$data_kat=$this->model_master->getListKategoriKompetensi();
		if (isset($data_kat)) {
			foreach ($data_kat as $kat) {
				if (!empty($this->input->post('kode_kompetensi_hidden_'.$kat->kode_kategori))) {
					$k_komp[$kat->kode_kategori]=explode("," ,$this->input->post('kode_kompetensi_hidden_'.$kat->kode_kategori));
				}
			}
		}
		
		if ($jbt != "") {
			if (!isset($emp)) {
				$data_emp=$this->model_karyawan->getEmployeeJabatan($jbt);
				if (isset($data_emp)) {
					foreach ($data_emp as $kar) {
						$val_masa_kerja=$this->otherfunctions->getMasaKerja($kar->tgl_masuk,$this->otherfunctions->getDateNow(),'arr');
						$val_masa_kerja['year']=(isset($val_masa_kerja['year']))?$val_masa_kerja['year']:0;
						if ($kar->poin >= 600 || $val_masa_kerja['year'] >= 3) {
							$emp[]=$kar->id_karyawan;
						}
					}
				}
			}
			if (isset($emp) && (count($emp) > 0)) {
				//define kompetensi
				foreach ($emp as $e) {
					$data_emp=$this->model_karyawan->getEmployeeId($e);
					if (isset($k_komp)) {
						foreach ($k_komp as $k_km => $v_km) {
							if (isset($v_km) && (!strstr( $k_km, 'CRE'))) {
								foreach ($v_km as $val_km) {
									$sub1=[];
									if (strstr( $k_km, 'TECH') && strstr( $val_km, 'TCH')) {
										$dt_km=$this->model_master->getKuisionerTechnicalKompetensiKode($val_km);
										$sub1=[
											'kompetensi'=>$dt_km['kuisioner'],
											'aspek_kompetensi'=>$dt_km['kode_aspek'],
											'poin_1'=>1,
											'satuan_1'=>'Kompeten',
											'poin_2'=>0,
											'satuan_2'=>'Belum Kompeten',
										];
									}elseif (strstr( $k_km, 'FUNC') && strstr( $val_km, 'FNC')) {
										$dt_km=$this->model_master->getKuisionerFunctionalKompetensiKode($val_km);
										$sub1=[
											'kompetensi'=>$dt_km['kuisioner'],
											'definisi'=>$dt_km['definisi'],
											'aspek_kompetensi'=>$dt_km['kode_aspek'],
										];
										$sub_col=[];
										for ($i=1; $i <=10 ; $i++) { 
											if (!empty($dt_km['satuan_'.$i])) {
												$sub_col['poin_'.$i]=$dt_km['poin_'.$i];
												$sub_col['satuan_'.$i]=$dt_km['satuan_'.$i];
											}
										}
										$sub1=array_merge($sub1,$sub_col);
									}elseif (strstr( $k_km, 'PROF') && strstr( $val_km, 'PRF')) {
										$dt_km=$this->model_master->getKuisionerProfesionalKompetensiKode($val_km);
										$sub1=[
											'kompetensi'=>$dt_km['kuisioner'],
											'definisi'=>$dt_km['definisi'],
											'aspek_kompetensi'=>$dt_km['kode_aspek'],
										];
										$sub_col=[];
										for ($i=1; $i <=10 ; $i++) { 
											if (!empty($dt_km['satuan_'.$i])) {
												$sub_col['poin_'.$i]=$dt_km['poin_'.$i];
												$sub_col['satuan_'.$i]=$dt_km['satuan_'.$i];
											}
										}
										$sub1=array_merge($sub1,$sub_col);
									}
									if (count($sub1) > 0) {
										$data=[
											'id_karyawan'=>$e,
											'kode_jabatan'=>$data_emp['jabatan'],
											'kode_loker'=>$data_emp['loker'],
											'kode_kategori'=>$k_km,
											'kode_kompetensi'=>$val_km,
											'penilai'=>$penilai,
										];
										$data=array_merge($data,$sub1);
										$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
										$this->model_global->insertQueryNoMsg($data,$tabel);
										
									}				
								}
							}
						}
					}
				}
				$datax = $this->messages->allGood();
			}
		}
		echo json_encode($datax);
	}
	public function edt_data_penilai_kuisioner_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id_karyawan');
		$penilai=$this->input->post('penilai');
		$penilai=($penilai)?implode(';', array_filter($penilai)):null;
		$tabel=$this->input->post('tabel');
		$datax = $this->messages->allFailure();
		if (!empty($tabel) && !empty($id) && !empty($penilai)) {
			$data=[
				'penilai'=>$penilai,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,$tabel,['id_karyawan'=>$id]);
		}
		echo json_encode($datax); 

	}
	public function view_detail_konsep_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$table=$this->input->post('table');
		$kode_c=$this->input->post('code');
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id_karyawan=$this->input->post('id_karyawan');
				$usage=strtoupper($this->input->post('usage'));
				$tipe_jabatan=strtoupper($this->input->post('tipe_jabatan'));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$datax['data']=[];
				if ($this->uri->segment(4) == 'view_data') {
					$idc=$this->input->post('id_c_kompetensi');
					$data=$this->model_concept->openTableViewConceptKompetensiId($table,$idc);
					if (isset($data)) {
						foreach ($data as $d) {
							//detail penilai
							$penilai=$this->otherfunctions->getMark();
							$penilai_dt=$this->otherfunctions->getParseOneLevelVar($d->penilai);
							if (isset($penilai_dt) && $d->penilai != '') {
								$penilai='<ol>';
								foreach ($penilai_dt as $pdt) {
									$dt_p_detail=$this->model_karyawan->getAssessorId($pdt);
									$penilai.='<li>'.$dt_p_detail['nama'].'</li>';
								}
								$penilai.='</ol>';
							}

							//detail poin
							$tb=[];
							for ($i=1; $i <=10 ; $i++) { 
								$poin='poin_'.$i;
								$satuan='satuan_'.$i;
								if ($d->$poin != 0 && $d->$satuan != null) {
									$var='<tr>
									<td>'.$d->$poin.'</td>
									<td>'.$d->$satuan.'</td>
									</tr>';
									array_push($tb, $var);
								}
							}
							if (isset($tb)) {
								$tb=implode('', $tb);
							}else{
								$tb='<tr>
								<td>'.$this->otherfunctions->getMark(null).'</td>
								<td>'.$this->otherfunctions->getMark(null).'</td>
								</tr>';
							}
							$datax=[
								'id'=>$d->id_c_kompetensi,
								'nama'=>$d->kompetensi,
								'kode_kompetensi'=>$d->kode_kompetensi,
								'definisi'=>($d->definisi)?$d->definisi:$this->otherfunctions->getMark(),
								'penilai'=>$penilai,
								'tr_table'=>$tb,
								'status'=>$d->status,
								'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
								'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
								'create_by'=>$d->create_by,
								'update_by'=>$d->update_by,
								'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
								'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update)
							];
						}
					}
				}elseif ($this->uri->segment(4) == 'table_view') {
					$data=$this->model_concept->openTableViewConceptKompetensiEmployee($table,$id_karyawan,'no');
					$code_old=[];
					foreach ($data as $d) {
						if ($d->kode_kategori == $usage) {
							$code_old[$usage][]=$d->kode_kompetensi;
						}
					}
					if (count($code_old) > 0) {
						if ($usage == 'FUNCTIONAL') {
							$datak=$this->model_master->getListKuisionerFunctionalKompetensiActive($tipe_jabatan);
							foreach ($datak as $dt) {
								if (!in_array($dt->kode_kuisioner, $code_old[$usage])) {
									$datax['data'][]=[
										'<input type="checkbox" name="kode_kuisioner" style="cursor:pointer" value="'.$dt->kode_kuisioner.'" onchange="checkvalue(\''.strtolower($usage).'\')">',
										$dt->kode_kuisioner,
										$dt->kuisioner,
										$dt->nama_aspek,
									];
								}
							}
						}elseif ($usage == 'PROFESIONAL') {
							$datak=$this->model_master->getListKuisionerProfesionalKompetensiActive($tipe_jabatan);
							foreach ($datak as $dt) {
								if (!in_array($dt->kode_kuisioner, $code_old[$usage])) {
									$datax['data'][]=[
										'<input type="checkbox" name="kode_kuisioner" style="cursor:pointer" value="'.$dt->kode_kuisioner.'" onchange="checkvalue(\''.strtolower($usage).'\')">',
										$dt->kode_kuisioner,
										$dt->kuisioner,
										$dt->nama_aspek,
									];
								}
							}
						}elseif ($usage == 'TECHNICAL') {
							$datak=$this->model_master->getListKuisionerTechnicalKompetensiActive($tipe_jabatan);
							foreach ($datak as $dt) {
								if (!in_array($dt->kode_kuisioner, $code_old[$usage])) {
									$datax['data'][]=[
										'<input type="checkbox" name="kode_kuisioner" style="cursor:pointer" value="'.$dt->kode_kuisioner.'" onchange="checkvalue(\''.strtolower($usage).'\')">',
										$dt->kode_kuisioner,
										$dt->kuisioner,
										$dt->nama_aspek,
									];
								}
							}
						}
					}
				}elseif ($this->uri->segment(4) == 'table_data') {
					$data=$this->model_concept->openTableViewConceptKompetensiEmployee($table,$id_karyawan,'no');
					$num=1;
					foreach ($data as $d) {
						if ($d->kode_kategori == $usage) {
							if ($usage == 'FUNCTIONAL') {
								$data_aspek=$this->model_master->getFunctionalKompetensiKode($d->aspek_kompetensi);
							}elseif ($usage == 'PROFESIONAL') {
								$data_aspek=$this->model_master->getProfesionalKompetensiKode($d->aspek_kompetensi);
							}elseif ($usage == 'TECHNICAL') {
								$data_aspek=$this->model_master->getTechnicalKompetensiKode($d->aspek_kompetensi);
							}
							$var=[
								'id'=>'"'.$d->id_c_kompetensi.'"',
								'access'=>$access,
								'status'=>null,
							];
							$properties=$this->otherfunctions->getPropertiesTable($var);
							$datax['data'][]=[
								$d->id_c_kompetensi,
								$d->kode_kompetensi,
								$d->kompetensi,
								((!empty($d->definisi))?$d->definisi:$this->otherfunctions->getMark()),
								(isset($data_aspek['nama']))?((!empty($data_aspek['nama']))?$data_aspek['nama']:$this->otherfunctions->getMark()):$this->otherfunctions->getMark(),
								$properties['aksi']
							];
							$num++;
						}
					}
				}elseif ($this->uri->segment(4) == 'table_bobot') {
					$data=$this->model_concept->openTableViewConceptKompetensiEmployee($table,$id_karyawan,'no');
					if (isset($data)) {
						$sub_data=[];
						$seq=[];
						foreach ($data as $d) {
							if ($d->kode_kategori == $usage) {
								if ($usage == 'FUNCTIONAL') {
									$data_aspek=$this->model_master->getFunctionalKompetensiKode($d->aspek_kompetensi);
								}elseif ($usage == 'PROFESIONAL') {
									$data_aspek=$this->model_master->getProfesionalKompetensiKode($d->aspek_kompetensi);
								}elseif ($usage == 'TECHNICAL') {
									$data_aspek=$this->model_master->getTechnicalKompetensiKode($d->aspek_kompetensi);
								}
								$sub_data[$d->aspek_kompetensi]['nama_aspek']=(isset($data_aspek['nama']))?((!empty($data_aspek['nama']))?$data_aspek['nama']:$this->otherfunctions->getMark()):$this->otherfunctions->getMark();
								$sub_data[$d->aspek_kompetensi]['id']=$d->id_c_kompetensi;
								$sub_data[$d->aspek_kompetensi]['bobot']=$d->bobot;
								array_push($seq, $d->aspek_kompetensi);
							}
						}
						if (isset($sub_data) && isset($seq)) {
							$num1=1;
							foreach ($sub_data as $k_sd=>$sd) {
								$datax['data'][]=[
									$sd['id'],
									$sd['nama_aspek'],
									'<input type="number" step="0.01" class="form-control" name="bobot_'.strtolower($usage).'[]" id="bobot_'.strtolower($usage).'_'.$num1.'" onkeyup="countbobot(\'bobot_'.strtolower($usage).'[]\',\''.strtolower($usage).'\')" value="'.$sd['bobot'].'" min="0" placeholder="Masukkan Bobot" required="required">
									<input type="hidden" name="sequence_'.strtolower($usage).'" value="'.implode(';',array_values(array_unique($seq))).'">',
								];
								$num1++;
							}
						}
					}
				}			
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_detail_konsep_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->input->post('kodeform');
		$val=$this->input->post('kode_'.$usage.'_hidden');
		$tabel=$this->input->post('tabel');
		$idk=$this->input->post('id_karyawan');
		$datax=$this->messages->notValidParam();
		if (!empty($tabel) && !empty($idk)) {
			$k_komp=explode("," ,$val);
			if (isset($k_komp)) {
				$data_emp=$this->model_karyawan->getEmployeeId($idk);
				$data_db=$this->model_concept->openTableViewConceptKompetensiEmployee($tabel,$idk,'no');
				$sub_data=[];
				if (isset($data_db)) {
					foreach ($data_db as $d_db) {
						$sub_data['penilai']=$d_db->penilai;
						$sub_data['bobot'][$d_db->aspek_kompetensi]=$d_db->bobot;
					}
				}
				foreach ($k_komp as $val_km) {
					$sub1=[];
					if (strstr( $val_km, 'TCH')) {
						$dt_km=$this->model_master->getKuisionerTechnicalKompetensiKode($val_km);
						$sub1=[
							'kompetensi'=>$dt_km['kuisioner'],
							'bobot'=>(isset($sub_data['bobot'][$dt_km['kode_aspek']]))?$sub_data['bobot'][$dt_km['kode_aspek']]:null,
							'aspek_kompetensi'=>$dt_km['kode_aspek'],
							'poin_1'=>1,
							'satuan_1'=>'Kompeten',
							'poin_2'=>0,
							'satuan_2'=>'Belum Kompeten',
						];
					}elseif (strstr( $val_km, 'FNC')) {
						$dt_km=$this->model_master->getKuisionerFunctionalKompetensiKode($val_km);
						$sub1=[
							'kompetensi'=>$dt_km['kuisioner'],
							'definisi'=>$dt_km['definisi'],
							'bobot'=>(isset($sub_data['bobot'][$dt_km['kode_aspek']]))?$sub_data['bobot'][$dt_km['kode_aspek']]:null,
							'aspek_kompetensi'=>$dt_km['kode_aspek'],
						];
						$sub_col=[];
						for ($i=1; $i <=10 ; $i++) { 
							if (!empty($dt_km['satuan_'.$i])) {
								$sub_col['poin_'.$i]=$dt_km['poin_'.$i];
								$sub_col['satuan_'.$i]=$dt_km['satuan_'.$i];
							}
						}
						$sub1=array_merge($sub1,$sub_col);
					}elseif (strstr( $val_km, 'PRF')) {
						$dt_km=$this->model_master->getKuisionerProfesionalKompetensiKode($val_km);
						$sub1=[
							'kompetensi'=>$dt_km['kuisioner'],
							'definisi'=>$dt_km['definisi'],
							'bobot'=>(isset($sub_data['bobot'][$dt_km['kode_aspek']]))?$sub_data['bobot'][$dt_km['kode_aspek']]:null,
							'aspek_kompetensi'=>$dt_km['kode_aspek'],
						];
						$sub_col=[];
						for ($i=1; $i <=10 ; $i++) { 
							if (!empty($dt_km['satuan_'.$i])) {
								$sub_col['poin_'.$i]=$dt_km['poin_'.$i];
								$sub_col['satuan_'.$i]=$dt_km['satuan_'.$i];
							}
						}
						$sub1=array_merge($sub1,$sub_col);
					}
					if (count($sub1) > 0) {
						$data=[
							'id_karyawan'=>$idk,
							'kode_jabatan'=>$data_emp['jabatan'],
							'kode_loker'=>$data_emp['loker'],
							'kode_kategori'=>strtoupper($usage),
							'kode_kompetensi'=>$val_km,
							'penilai'=>(isset($sub_data['penilai']))?$sub_data['penilai']:null,
						];
						$data=array_merge($data,$sub1);
						$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQueryNoMsg($data,$tabel);
					}
					
				}
				$datax=$this->messages->allGood();
			}
			
		}
		echo json_encode($datax);
	}
	function edt_bobot_detail_kompetensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->input->post('kodeform');
		$bobot=$this->input->post('bobot_'.$usage);
		$seq=$this->input->post('sequence_'.$usage);
		$tabel=$this->input->post('tabel');
		$idk=$this->input->post('id_karyawan');
		$datax=$this->messages->notValidParam();
		if (!empty($idk) && !empty($tabel) && !empty($bobot) && !empty($seq)) {
			$seq=$this->otherfunctions->getParseOneLevelVar($seq);
			if (isset($seq)) {
				foreach ($seq as $k_s => $v_s) {
					$data=[
						'bobot'=>(isset($bobot[$k_s]))?$bobot[$k_s]:null,
					];
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data,$tabel,['id_karyawan'=>$idk,'aspek_kompetensi'=>$v_s]);
				}
				$datax=$this->messages->allGood();
			}
		}
		echo json_encode($datax);
	}
//=================================================BLOCK CHANGE=================================================//
//===KONSEP KPI BEGIN===//
//--------------------------------------------------------------------------------------------------------------//
//Concept KPI
	public function concept_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_concept->getListKonsepKpi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_c_kpi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_c_kpi,
						$d->kode_c_kpi,
						$d->nama,
						$d->nama_tabel,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->kode_c_kpi)
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_c_kpi');
				$data=$this->model_concept->getKonsepKpi($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_c_kpi,
						'kode_c_kpi'=>$d->kode_c_kpi,
						'nama'=>$d->nama,
						'nama_tabel'=>$d->nama_tabel,
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
				$data = $this->codegenerator->kodeKonsepKpi();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_concept_kpi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$table=$this->exam->getNameTable('concept_kpi');
			$gen=$this->model_concept->generateKonsepKpi($table);
			if ($gen) {
				$data=[
					'kode_c_kpi'=>strtoupper($kode),
					'nama'=>ucwords($this->input->post('nama')),
					'nama_tabel'=>$table,
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'concept_kpi',$this->model_concept->checkKonsepKpiCode($kode));
			}else{
				$datax=$this->messages->customFailure('Generate Tabel Gagal, Mohon Kontak Administrator!');
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_concept_kpi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_c_kpi'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_c_kpi']) {
				$cek=$this->model_concept->checkKonsepKpiCode($data['kode_c_kpi']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'concept_kpi',['id_c_kpi'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------//
//Concept KPI
	public function view_data_konsep_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$table=$this->input->post('table');
		$kode_c=$this->input->post('code');
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$lokasi = $this->input->post('lokasi');
				$where = (!empty($lokasi)?['lok.kode_loker'=>$lokasi]:null);
				$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
				$data=$this->model_concept->openTableViewConceptKpi($table,$filter,$where);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>'"'.$d->kode_jabatan.'"',
						'access'=>$access,
						'status'=>null,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->kode_jabatan,
						$d->nama_jabatan.(($d->jumlah_non_aktif > 0)?' <i class="fa fa-user-times" style="color:red" data-toggle="tooltip" title="Sesuaikan HRIS" ></i>':''),
						$d->nama_bagian,
						$d->nama_level,
						$d->nama_lokasi,
						($d->bobot)?($d->bobot.'% '.(($d->bobot < 100 || $d->bobot > 100)?'<i class="fa fa-warning" style="color:red" data-toggle="tooltip" title="Bobot Harus 100%"></i>':'')):'<label class="label label-danger">Data Kosong</label>',
						$d->jumlah.' KPI',
						(($d->jumlah_emp > 0)?$d->jumlah_emp.' Karyawan':'<label class="label label-danger">Karyawan Kosong</label>'),
						$properties['aksi'],
						$kode_c,
						$this->codegenerator->encryptChar($d->kode_jabatan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$kode_jabatan=$this->input->post('kode_jabatan');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$data=$this->model_concept->openTableViewConceptKpiJabatan($table,$kode_jabatan);
				foreach ($data as $d) {
				//KPI 
					$data_kpi=$this->model_concept->getJenisKpi($table,$kode_jabatan);
					if(count($data_kpi) == 0){
						$kpikpi = '<div class="callout callout-danger"><b><i class="fa fa-info-circle"></i> Data Kosong</b></div>';
					}else{
						$kpikpi = '';
						$kpikpi .= '<table class="table table-hover" width="100%">
						<thead><tr class="bg-blue"><th width="5%">No.</th><th width="75%">KPI</th><th>Target</th><th>Bobot</th></tr></thead>
						<tbody>';
						$nodw = 1;
						$total_bobot_kpi=0;
						foreach ($data_kpi as $dw) {
							$kpikpi .= '<tr><td>'.$nodw.'.</td><td>'.$dw->kpi.'</td><td>'.$dw->target.'</td><td>'.((!empty($dw->bobot))?$dw->bobot.'%':$this->otherfunctions->getMark()).'</td><tr>';
							$total_bobot_kpi=$total_bobot_kpi+$dw->bobot;
							$nodw++;
						}
						$kpikpi .= '<tr><td colspan="3" class="text-center bg-aqua">Total Bobot</td><td><b>'.$total_bobot_kpi.'% '.(($total_bobot_kpi < 100)?'<i class="fa fa-warning" style="color:red" data-toggle="tooltip" title="Bobot Harus 100%"></i>':'<i class="fa fa-check stat scc"></i>').'</b></td></tr>';
						$kpikpi .= '</tbody></table>';
					}
				//Karyawan 
					$data_karyawan=$this->model_concept->getKaryawanKpi($table,$kode_jabatan);
					$kpikaryawan = '';
					$kpikaryawan .= '<table class="table table-hover" width="100%"><thead><tr class="bg-blue"><th width="5%">No.</th><th>NIK</th><th>Nama</th><th>Jabatan</th><th>Lokasi Kerja</th><th>Bagian</th></tr></thead><tbody>';
					$noka = 1;
					foreach ($data_karyawan as $dk) {
						$kpikaryawan .= '<tr><td>'.$noka.'.</td><td>'.$dk->nik.'</td><td>'.$dk->nama.'</td><td>'.$dk->nama_jabatan.'</td><td>'.$dk->nama_loker.'</td><td>'.$dk->bagian.'</td><tr>';
						$noka++;
					}
					$kpikaryawan .= '</tbody></table>';
					$datax=[
						'kode'=>$d->kode_jabatan,
						'nama'=>$d->nama_jabatan,
						'nama_bagian'=>$d->nama_bagian,
						'nama_level'=>$d->nama_level,
						'jumlah_kpi'=>($d->jumlah > 0) ? $d->jumlah.' KPI' : $this->otherfunctions->getMark(),
						'jumlah_emp'=>($d->jumlah_emp > 0) ? $d->jumlah_emp.' Karyawan' : '<label class="label label-danger">Karyawan Kosong</label>',
						'data_kpi'=>$kpikpi,
						'data_karyawan'=>$kpikaryawan,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeKonsepKpi();
				echo json_encode($data);
			}elseif ($usage == 'select_jabatan'){
				$tabel=$this->input->post('tabel');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
				$data=$this->model_concept->getJabatanKpi($tabel,$filter);
				echo json_encode($data);
			}elseif ($usage == 'get_kpi'){
				$datak=$this->model_master->getListKpi(true);
				$datax['data']=[];
				foreach ($datak as $d) {
					$datax['data'][]=[
						'<input type="checkbox" name="kode_kpi" value="'.$d->kode_kpi.'"  style="cursor:pointer" onchange="checkvalue(\''.strtolower($d->jenis).'\')">',
						$d->kode_kpi,
						$d->kpi,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_jabatan_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('jabatan');
		if ($kode != "") {
			$kpi=$this->input->post('kode_kpi');
			$kode_concept = $this->codegenerator->decryptChar($this->input->post('kode_concept'));
			$kode_kpi_hidden=$this->input->post('kode_kpi_hidden');
			$svkodekpi=$this->otherfunctions->getParseOneLevelVar($kode_kpi_hidden,',');
			$jumlah = count($svkodekpi);
			$tabel=$this->input->post('tabel');
			foreach ($svkodekpi as $val) {
				$data_kpi=$this->model_master->getKpiKode($val);
				$data_emp=$this->model_karyawan->getEmployeeJabatan($kode,true,true);
				foreach ($data_emp as $emp) {
					$data=[
						'id_karyawan'=>$emp->id_karyawan,
						'kode_jabatan'=>$kode,
						'kode_loker'=>$emp->loker,
						'kode_kpi' => $val,
						'kpi' => $data_kpi['kpi'],
						'rumus' => $data_kpi['rumus'],
						'unit' => $data_kpi['unit'],
						'definisi' => $data_kpi['definisi'],
						'kaitan' => $data_kpi['kaitan'],
						'jenis_satuan' => $data_kpi['jenis_satuan'],
						'sifat' => $data_kpi['sifat'],
						'cara_menghitung' => $data_kpi['cara_menghitung'],
						'sumber_data'=>$data_kpi['sumber_data'],
						'detail_rumus'=>$data_kpi['detail_rumus'],
						'min'=>$data_kpi['min'],
						'max'=>$data_kpi['max'],
						'id_jenis_batasan_poin'=>$data_kpi['id_jenis_batasan_poin'],
						'lebih_max'=>$data_kpi['lebih_max'],
						'kode_penilai'=>'P1',
					];
					for ($i=1;$i<=$this->max_range;$i++){
						$p='poin_'.$i;
						$s='satuan_'.$i;
						$data[$p]=$data_kpi[$p];
						$data[$s]=$data_kpi[$s];
						if ($data[$p] == null) {
							$data[$s]=null;
						}
					}
					$data_to_agenda=$data;
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQueryNoMsg($data,$tabel);
					$this->model_agenda->updateAgendaFromConceptMaster($data_to_agenda,'add_concept',[],$kode_concept);//sync to agenda
				}
			}
			$datax = $this->messages->allGood();

		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function delete_data_konsep_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode_jabatan = $this->input->post('kode_jabatan');
		$table = $this->input->post('tabel');
		$kode_concept = $this->codegenerator->decryptChar($this->input->post('kode_concept'));
		if(!empty($table)){
			$where = [
				'kode_jabatan' => $kode_jabatan,
			];
			$this->model_agenda->updateAgendaFromConceptMaster($where,'delete_concept',$where,$kode_concept);//sync to agenda
			$datax = $this->model_global->deleteQuery($table,$where);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function view_detail_konsep_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$table=$this->input->post('table');
		$kode_j=$this->input->post('jbt');
		$kode_c=$this->input->post('code');
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_concept->openTableViewConceptKpi($table);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>'"'.$d->kode_jabatan.'"',
						'access'=>$access,
						'status'=>null,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->kode_jabatan,
						$d->nama_jabatan,
						$d->nama_bagian,
						$d->nama_level,
						$d->jumlah.' KPI',
						$properties['aksi'],
						$kode_c,
						$this->codegenerator->encryptChar($d->kode_jabatan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$kode_kpi = $this->input->post('kode_kpi');
				$datak=$this->model_concept->getJenisKpiOne($table,$kode_j,$kode_kpi);
				foreach ($datak as $d) {
					if($d->penilai==''){
						if($d->kode_penilai==''){
							$daftar = $this->otherfunctions->getMark();
						}else{
							$daftar = $this->otherfunctions->getPenilai($d->kode_penilai);
						}
					}else{
						$daftar = '';
						$getx = $this->otherfunctions->getParseOneLevelVar($d->penilai);
						$daftar .= '<a style="cursor: pointer;" class="text-blue" id="btndaftar" onclick="view_daftar()"><i class="fa fa-list"></i> Lihat Daftar</a><br>';
						$daftar .= '<div id="viewdaftar" style="display:none;overflow:auto;max-height:200px">';
						$daftar .= '<ol style="padding-left: 14px;">';
						foreach ($getx as $g) {
							$getempx = $this->model_karyawan->getEmployeeId($g);
							$daftar .= '<li>'.$getempx['nama'].(($getempx['nama_jabatan'])?' ('.$getempx['nama_jabatan'].')':null).'</li>';
						}
						$daftar .= '</ol></div>';
					}
					$tb=[];
					for ($i=1; $i <= $this->max_range; $i++) { 
						$poin='poin_'.$i;
						$satuan='satuan_'.$i;
						if ($d->$satuan != null) {
							$var='<tr>
							<td>'.$d->$poin.'</td>
							<td>'.$d->$satuan.'</td>
							</tr>';
							array_push($tb, $var);
						}
					}
					if (isset($tb)) {
						$tb=implode('', $tb);
					}else{
						$tb='<tr>
						<td>'.$this->otherfunctions->getMark(null).'</td>
						<td>'.$this->otherfunctions->getMark(null).'</td>
						</tr>';
					}
					$datax=[
						'kode_kpi'=>$d->kode_kpi,
						'nama'=>$d->kpi,
						'rumus'=>$d->rumus,
						'rumus_view'=>(!empty($d->rumus)) ? $d->rumus : $this->otherfunctions->getMark(null),
						'unit'=>$d->unit,
						'detail_rumus'=>$d->detail_rumus,
						'sumber_data'=>$d->sumber_data,
						'kaitan'=>$d->kaitan,
						'cara_menghitung'=>$d->cara_menghitung,
						'cara_menghitung_view'=>($d->cara_menghitung)?$this->model_master->getRumusFunction($d->cara_menghitung)['nama']:$this->otherfunctions->getMark(null),
						'kaitan_view'=>$this->otherfunctions->getKaitanNilai($d->kaitan),
						'jenis_satuan'=>$d->jenis_satuan,
						'jenis_satuan_view'=>$this->otherfunctions->getJenisSatuan($d->jenis_satuan),
						'sifat'=>$d->sifat,
						'sifat_view'=>$this->otherfunctions->getSifatKpi($d->sifat),
						'min'=>$d->min,
						'max'=>$d->max,
						'batasan_poin'=>$d->id_jenis_batasan_poin,
						'nama_batasan_poin'=>(!empty($d->nama_batasan_poin)) ? $d->nama_batasan_poin : $this->otherfunctions->getMark(),
						'penilai'=>$daftar,
						'status'=>$d->status,
						'tr_table'=>$tb,
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
				$data = $this->codegenerator->kodeKonsepKpi();
				echo json_encode($data);
			}elseif ($usage == 'select_jabatan'){
				$tabel=$this->input->post('tabel');
				$data=$this->model_concept->getJabatanKpi($tabel);
				echo json_encode($data);
			}elseif ($usage == 'select_karyawan'){
				$data=$this->model_concept->getSelectKaryawanKpi($table,$kode_j);
				echo json_encode($data);
			}elseif ($usage == 'data_karyawan'){
				$datak=$this->model_concept->getKaryawanKpi($table,$kode_j);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$n=1;
				$datax['data']=[];
				foreach ($datak as $d) {
					$val=[
						'id'=>$d->id_karyawan,
						'access'=>$access,
						'status'=>null,
					];
					if (isset($val['access']['l_ac']['del'])) {
						$delete = (in_array($val['access']['l_ac']['del'], $val['access']['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_modal(\''.$val['id'].'\',\'id_karyawan\',\''.$d->nama.'\',\'karyawan\')" style="margin-bottom: 2px;"><i class="fa fa-trash fa-fw" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					if (!empty($d->id_karyawan)) {
						$datax['data'][]=[
							$n,
							$d->nik,
							$d->nama.' '.((($d->nama_jabatan != $d->jabatan_now) || ($d->nama_loker != $d->loker_now))?' <i class="fa fa-user-times" style="color:orange" data-toggle="tooltip" title="Sesuaikan Data HRIS"></i>':null),
							$d->nama_jabatan,
							$d->bagian,
							$d->nama_loker,
							$delete,
						];
						$n++;
					}
				}
				echo json_encode($datax);
			}elseif ($usage == 'data_jenis'){
				$datak=$this->model_concept->getKpiJenis($table,$kode_j);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$n=1;
				$datax['data']=[];
				foreach ($datak as $d) {
					$datax['data'][]=[
						$n,
						$d->jenis,
						'<input type="hidden" name="b_jenis_kpi[]" value="'.$d->jenis.'"><input type="number" step="0.01" class="form-control" name="bobotjeniskpi[]" id="bobotjeniskpi'.$n.'" onkeyup="countbobot(\'bobotjeniskpi[]\',\'bobotkpi\')" value="'.$d->bobot_jenis_kpi.'" min="1" max="100" style="width:100%" placeholder="Masukkan Bobot Jenis KPI '.$d->jenis.'">',
					];
					$n++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'check_emp_data') {
				$cek['cek']=$this->model_concept->checkTableJabatanViewConceptKPi($table,$kode_j);
				echo json_encode($cek);
			}else{
				if ($usage == 'get_kpi'){
					$datak=$this->model_concept->getJenisKpi($table,$kode_j);
					$access=$this->codegenerator->decryptChar($this->input->post('access'));
					$n=1;
					$datax['data']=[];
					$sumbobot=0;
					foreach ($datak as $d) {
						$val=[
							'id'=>$d->kpi,
							'access'=>$access,
							'status'=>null,
						];
						if (isset($val['access']['l_ac']['del'])) {
							$delete = (in_array($val['access']['l_ac']['del'], $val['access']['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick="delete_modal(\''.$d->kode_kpi.'\',\'kode_kpi\',null)" style="margin-bottom: 2px;"><i class="fa fa-trash fa-fw" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
						}else{
							$delete = null;
						}
						$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal(\''.$d->kode_kpi.'\')" style="margin-bottom: 2px;"><i class="fa fa-info-circle fa-fw" data-toggle="tooltip" title="Detail Data"></i></button> ';
						if(!empty($d->target)){
							$vartarget = $d->target;
						}else{
							$vartarget = 0;
						}
						if(!empty($d->bobot)){
							$varbobot = $d->bobot;
						}else{
							$varbobot = 0;
						}
						$target = '<input type="number" step="0.01" class="form-control" name="target[]" id="target_'.$n.'" onblur="counttarget(\'target_'.$n.'\')" onkeyup="countbobot(\'bobot[]\')" value="'.$vartarget.'" min="0">';
						$bobot = '<input type="number" step="0.01" class="form-control" name="bobot[]" id="bobot_'.$n.'" onkeyup="countbobot(\'bobot[]\')" value="'.$varbobot.'" min="0" max="100">';
						$sel1 = array($d->kode_penilai);
						$ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'penilai_'.$n,'onchange'=>'show_emp_concept(\''.''.$n.'\')');
						$penilai=form_dropdown('penilai[]',$this->otherfunctions->getPenilaiList(),$sel1,$ex1);

						$kode_kpi = '<input type="hidden" name="kode_kpi[]" value="'.$d->kode_kpi.'">';

						$getemp = $this->model_karyawan->getEmployeeForSelect2();
						$karyawan = '<select class="form-control select2" id="emp_penilai'.$d->kode_kpi.'" name="emp_penilai'.$d->kode_kpi.'[]" style="width: 100%;" multiple="multiple">';
						foreach ($getemp as $k_ge=>$v_ge) {
							$karyawan .= '<option value="'.$k_ge.'">'.$v_ge.'</option>';
						}
						$karyawan .= '</select>';

						$getemp = $this->model_karyawan->getEmployeeForSelect2();
						$karyawan = '<select class="form-control select2" id="emp_penilai'.$d->kode_kpi.'" name="emp_penilai'.$d->kode_kpi.'[]" style="width: 100%;" multiple="multiple">';
						foreach ($getemp as $k_ge=>$v_ge) {
							$karyawan .= '<option value="'.$k_ge.'">'.$v_ge.'</option>';
						}
						$karyawan .= '</select>';
						$sumbobot += $d->bobot;
						if($d->kode_penilai=='P3'){
							$display = 'block';
							$karyawanid = $d->penilai;
						}else{
							$display = 'none';
							$karyawanid = '';
						}
						$modal_penilai = '
						<button type="button" data-toggle="modal" data-target="#pn_'.''.$n.'" class="btn btn-default text-center"><i class="fa fa-user"></i> Tambah Penilai</button>
						<div id="pn_'.''.$n.'" class="modal modal_penilai" role="dialog">
						<div class="modal-dialog modal-md">
						<div class="modal-content">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title">Tambah Penilai <b class="text-muted header_data">'.$d->kpi.'</b></h2>
						</div>
						<div class="modal-body text-left">
						<div class="form-group">
							<label>Pilih Penilai</label>
							'.$penilai.'
						</div>
						<div class="form-group" style="display: '.$display.';padding-top: 10px;" id="jbt_penilai_'.$n.'">
							<label>Pilih Jabatan</label>
							<select id="fill_jbt_penilai'.$n.'" class="form-control select2" style="width: 100% !important;" placeholder="Pilih Penilai" name="jbt_penilai'.$d->kode_kpi.'[]"></select>
						</div>
						<div class="form-group" style="display: '.$display.';padding-top: 10px;" id="emp_'.$n.'">
						<label>Pilih Karyawan</label>
						'.$karyawan.'
						</div>
						</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-success" data-dismiss="modal" onclick="countbobot(\'bobot_'.'[]\',\''.'\')"><i class="fa fa-check-circle"></i> Selesai</button>
						</div>
						</div>

						</div>
						</div>
						<script>
							$(document).ready(function(){
								var vle = "'.$karyawanid.'";
								var selectedValuesTest = vle.split(";").filter(Boolean);
								$("#emp_penilai'.$d->kode_kpi.'").select2("val",[selectedValuesTest]);
							})
						</script>';
						$datax['data'][]=[
							$n,
							$d->kpi,
							$this->otherfunctions->getSifatKpi($d->sifat),
							$d->unit,
							$target,
							$bobot,
							$modal_penilai,
							$info.$delete,
							$kode_kpi,
							$karyawan,
							$sumbobot,
							$karyawanid,
							$d->kode_kpi,
							$d->bobot,
						];
						$n++;
					}
					echo json_encode($datax);
				}
			}
		}
	}
	public function delete_detail_konsep_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$column = $this->input->post('column');
		$id = $this->input->post('id');
		$kode_jabatan = $this->input->post('kode');
		$table = $this->input->post('table');
		$kodeform = $this->input->post('kodeform');
		$kode_concept = $this->codegenerator->decryptChar($this->input->post('kode_rancangan'));
		if(!empty($table)){
			if($kodeform != 'karyawan'){
				$where = [
					'kode_jabatan' => $kode_jabatan,
					$column => $id
				];
				$datax = $this->model_global->deleteQuery($table,$where);
			}else{
				$where = [
					'kode_jabatan' => $kode_jabatan,
					$column => $id
				];
				$re=$this->model_concept->moveDeleteKpi($table,$where);
				if ($re) {
					$datax = $this->messages->allGood();
				}else{
					$datax = $this->model_global->deleteQuery($table,$where);
				}
			}
			$this->model_agenda->updateAgendaFromConceptMaster($where,'delete_concept',$where,$kode_concept);//sync to agenda
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edit_detail_konsep_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode_kpi = $this->input->post('kode_kpi');
		$target = $this->input->post('target');
		$bobot = $this->input->post('bobot');
		$penilai = $this->input->post('penilai');
		$table = $this->codegenerator->decryptChar($this->input->post('table'));
		$kode_concept = $this->codegenerator->decryptChar($this->input->post('kode_concept'));
		$kode_jabatan = $this->codegenerator->decryptChar($this->input->post('kode_jabatan'));
		if(($kode_kpi && $kode_concept && $table && $kode_jabatan) && ($target!='' || $bobot!='')){
			$no=0;
			foreach ($kode_kpi as $kp) {
				$emp = $this->input->post('emp_penilai'.$kp);
				if($emp==''){
					$emp_penilai='';
				}else{
					$emp_penilai = implode(";",$emp);
				}

				$where = [
					'kode_jabatan' => $kode_jabatan,
					'kode_kpi' => $kp,
				];
				$data = [
					'bobot'=>$bobot[$no],
					'target' => $target[$no],
					'kode_penilai' => $penilai[$no],
					'penilai' => $emp_penilai,
				];
				$data_to_agenda=$data;
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($data,$table,$where);
				$this->model_agenda->updateAgendaFromConceptMaster($data_to_agenda,'edit_concept',$where,$kode_concept);//sync to agenda
				$no++;
			}
			$datax = $this->messages->allGood();
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function add_detail_konsep_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$tabel = $this->input->post('tabel');
		$kode_jabatan = $this->input->post('kode_jabatan');
		$kode_kpi = $this->input->post('kode_kpi');
		$kode_kpi_hidden = $this->otherfunctions->getParseOneLevelVar($this->input->post('kode_kpi_hidden'),',');
		$jumlah_kpi = count($kode_kpi_hidden);
		$kode_concept = $this->codegenerator->decryptChar($this->input->post('kode_concept'));
		$datax='';
		if($tabel!='' || $kode_jabatan!='' || $kode_kpi!=''){
			for($x=0;$x<$jumlah_kpi;$x++){
				$getKPI = $this->model_master->getKpiKode($kode_kpi_hidden[$x]);
				$getEmp = $this->model_karyawan->getEmployeeJabatan($kode_jabatan,true,true);
				foreach ($getEmp as $ge) {
					$data = [
						'id_karyawan' => $ge->id_karyawan,
						'kode_jabatan' => $kode_jabatan,
						'kode_loker'=>$ge->loker,
						'kode_kpi' => $getKPI['kode_kpi'],
						'kpi' => $getKPI['kpi'],
						'rumus' => $getKPI['rumus'],
						'unit' => $getKPI['unit'],
						'definisi' => $getKPI['definisi'],
						'kaitan' => $getKPI['kaitan'],
						'jenis_satuan' => $getKPI['jenis_satuan'],
						'sifat' => $getKPI['sifat'],
						'cara_menghitung' => $getKPI['cara_menghitung'],
						'sumber_data'=>$getKPI['sumber_data'],
						'detail_rumus'=>$getKPI['detail_rumus'],
						'min'=>$getKPI['min'],
						'max'=>$getKPI['max'],
						'id_jenis_batasan_poin'=>$getKPI['id_jenis_batasan_poin'],
						'lebih_max'=>$getKPI['lebih_max'],
						'kode_penilai'=>'P1',
					];
					for ($i=1;$i<=$this->max_range;$i++){
						$p='poin_'.$i;
						$s='satuan_'.$i;
						$data[$p]=$getKPI[$p];
						$data[$s]=$getKPI[$s];
						if ($data[$p] == null) {
							$data[$s]=null;
						}
					}
					$data_to_agenda=$data;
					$cekKPI = $this->model_concept->getConceptKPIWhere($tabel,['a.id_karyawan'=>$ge->id_karyawan,'a.kode_jabatan'=>$kode_jabatan,'a.kode_kpi'=>$getKPI['kode_kpi']]);
					if(empty($cekKPI)){
						$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQueryNoMsg($data,$tabel);
						$this->model_agenda->updateAgendaFromConceptMaster($data_to_agenda,'add_concept',[],$kode_concept);
						$datax = $this->messages->allGood();
					}else{
						$datax = $this->messages->customWarning('KPI Untuk Karyawan Tersebut Sudah Ada.');						
					}
				}
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function add_detail_karyawan_kpi()
	{
		$kodeform = $this->input->post('kodeform');
		$tabel = $this->input->post('tabel');
		$kode_jabatan = $this->input->post('kode_jabatan');
		$kode_concept = $this->codegenerator->decryptChar($this->input->post('kode_concept'));
		$emp = $this->input->post('kpi_karyawan');
		if($kodeform!='' || $tabel!='' || $kode_jabatan!='' || $emp!=''){
			foreach ($emp as $e) {
				$kpi = $this->model_concept->getListTableKonsepKpi($tabel,$kode_jabatan);
				$d_emp=$this->model_karyawan->getEmployeeId($e);
				$this->model_agenda->updateAgendaFromConceptMaster(['id_karyawan'=>$e],'delete_concept',['id_karyawan'=>$e],$kode_concept);
				foreach ($kpi as $k) {
					$data = [
						'id_karyawan' => $e,
						'kode_jabatan' => $kode_jabatan,
						'kode_loker'=>$d_emp['loker'],
						'kode_kpi' => $k->kode_kpi,
						'kpi' => $k->kpi,
						'rumus' => $k->rumus,
						'unit' => $k->unit,
						'definisi' => $k->definisi,
						'kaitan' => $k->kaitan,
						'jenis_satuan' => $k->jenis_satuan,
						'sifat' => $k->sifat,
						'cara_menghitung' => $k->cara_menghitung,
						'sumber_data'=>$k->sumber_data,
						'detail_rumus'=>$k->detail_rumus,
						'min'=>$k->min,
						'max'=>$k->max,
						'bobot'=>$k->bobot,
						'target'=>$k->target,
						'id_jenis_batasan_poin'=>$k->id_jenis_batasan_poin,
						'lebih_max'=>$k->lebih_max,
						'kode_penilai'=>$k->kode_penilai,
						'penilai'=>$k->penilai,
					];
					for ($i=1;$i<=$this->max_range;$i++){
						$p='poin_'.$i;
						$s='satuan_'.$i;
						$data[$p]=$k->$p;
						$data[$s]=$k->$s;
						if ($data[$p] == null) {
							$data[$s]=null;
						}
					}
					$data_to_agenda=$data;
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$cek=$this->model_agenda->openTableAgendaIdCode($tabel,$e,$k->kode_kpi,'a.kode_kpi');
					if ($cek) {
						$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
						$this->model_global->updateQueryNoMsg($data,$tabel,['id_karyawan'=>$e,'kode_kpi'=>$k->kode_kpi]);
					}else{
						$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQueryNoMsg($data,$tabel);
					}
					$this->model_agenda->updateAgendaFromConceptMaster($data_to_agenda,'add_concept',[],$kode_concept);
				}
				
			}
			$this->model_concept->moveDeleteKpi($tabel,['kode_jabatan' => $kode_jabatan],'delete_all_null');
			$datax = $this->messages->allGood();
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	
	public function import_detail_konsep_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$tabel = $this->input->post('tabel');
		$kode_concept = $this->codegenerator->decryptChar($this->input->post('kode_concept'));
		$data['properties']=[
			'post'=>'file',
			'data_post'=>$this->input->post('file', TRUE),
		];
		$arr_lv=[1=>'nik',3=>'kode_jabatan',5=>'kode_kpi',12=>'target',13=>'bobot',14=>'kode_penilai',15=>'penilai'];
		$sheet[0]=[
			'range_huruf'=>3,
			'row'=>2,
			'table'=>$tabel,
			'kode_concept'=>$kode_concept,
			'column_code'=>'kode_kpi',
			'usage'=>'import_detail_konsep_kpi',
			'other'=>['id_admin'=>$this->admin,'kode_jabatan'=>$this->input->post('kode_jabatan')],
			'column'=>$arr_lv,
		];
		$data['data']=$sheet;
		$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	public function sync_from_agenda_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		$table=$this->input->post('table');
		$table_agenda=$this->input->post('agenda');
		if (empty($kode) || empty($table) || empty($table_agenda)) {
			echo json_encode($this->messages->notValidParam());
		}else{
			$kode=$this->codegenerator->decryptChar($kode);
			$this->model_global->turncateTable($table);
			$data=$this->model_agenda->openTableAgenda($table_agenda);
			$msg=$this->messages->allFailure();
			if (isset($data)) {
				foreach ($data as $d) {
					$data_in=[
						'id_karyawan' => $d->id_karyawan,
						'kode_jabatan' => $d->kode_jabatan,
						'kode_loker'=>$d->kode_loker,
						'kode_kpi' => $d->kode_kpi,
						'kpi' => $d->kpi,
						'rumus' => $d->rumus,
						'unit' => $d->unit,
						'definisi' => $d->definisi,
						'kaitan' => $d->kaitan,
						'jenis_satuan' => $d->jenis_satuan,
						'sifat' => $d->sifat,
						'cara_menghitung' => $d->cara_menghitung,
						'sumber_data'=>$d->sumber_data,
						'detail_rumus'=>$d->detail_rumus,
						'min'=>$d->min,
						'max'=>$d->max,
						'id_jenis_batasan_poin'=>$d->id_jenis_batasan_poin,
						'lebih_max'=>$d->lebih_max,
						'kode_penilai'=>$d->kode_penilai,
						'penilai'=>$d->penilai,
						'bobot'=>$d->bobot,
						'target'=>$d->target,
					];
					for ($i=1;$i<=$this->max_range;$i++){
						$p='poin_'.$i;
						$s='satuan_'.$i;
						$data_in[$p]=$d->$p;
						$data_in[$s]=$d->$s;
						if ($data_in[$p] == null) {
							$data_in[$s]=null;
						}
					}
					$data_in=array_merge($data_in,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQueryNoMsg($data_in,$table);
				}
				$msg=$this->messages->allGood();
			}
			echo json_encode($msg);
		}
	}
//===KONSEP KPI END===//
//--------------------------------------------------------------------------------------------------------------//
//Concept Kompetensi
}
