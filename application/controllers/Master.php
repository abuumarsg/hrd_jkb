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
    
class Master extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		$this->filter=$this->model_admin->getFilter()['list_bagian'];
		if ($this->session->has_userdata('adm')) {
			$this->admin = $this->session->userdata('adm')['id'];	 
		}else{ 
			redirect('auth');
		}
		$this->max_range=$this->otherfunctions->poin_max_range();
		$this->max_month=$this->otherfunctions->column_value_max_range();
		$dtroot['admin']=$this->model_admin->adm($this->admin);
		$nm=explode(" ", $dtroot['admin']['nama']);
		$datax['adm'] = [
				'nama'=>$nm[0],
				'namax'=>$dtroot['admin']['nama'],
				'email'=>$dtroot['admin']['email'],
				'kelamin'=>$dtroot['admin']['kelamin'],
				'level'=>$dtroot['admin']['level'],
				'id_karyawan'=>$dtroot['admin']['id_karyawan'],
				'foto'=>$dtroot['admin']['foto'],
				'create'=>$dtroot['admin']['create_date'],
				'update'=>$dtroot['admin']['update_date'],
				'login'=>$dtroot['admin']['last_login'],
		];
		$this->dtroot=$datax;
	}
	public function index(){
		redirect('pages/dashboard');
	}
	public function coba()
	{
		echo '<pre>';
		print_r($this->dtroot);
		echo '</pre>';
	}
	//==SETTINGS BEGIN==//
	//Setting Bobot Sikap
	public function master_bobot_sikap()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListBobotSikap();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					unset($access['l_ac']['del']);
					unset($access['l_ac']['stt']);
					$var=[
						'id'=>$d->id_bobot,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_bobot,
						$d->nama,
						($d->atasan != null && $d->atasan != '0') ? $d->atasan.' %' : $this->otherfunctions->getMark($d->atasan),
						($d->bawahan != null && $d->bawahan != '0') ? $d->bawahan.' %' : $this->otherfunctions->getMark($d->bawahan),
						($d->rekan != null && $d->rekan != '0') ? $d->rekan.' %' : $this->otherfunctions->getMark($d->rekan),
						$properties['tanggal'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_bobot');
				$data=$this->model_master->getBobotSikap($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_bobot,
						'nama'=>$d->nama,
						'atasan'=>($d->atasan != null && $d->atasan != 0) ? $d->atasan.' %' : $this->otherfunctions->getMark($d->atasan),
						'bawahan'=>($d->bawahan != null && $d->bawahan != 0) ? $d->bawahan.' %' : $this->otherfunctions->getMark($d->bawahan),
						'rekan'=>($d->rekan != null && $d->rekan != 0) ? $d->rekan.' %' : $this->otherfunctions->getMark($d->rekan),
						'val_atasan'=>$d->atasan,
						'val_bawahan'=>$d->bawahan,
						'val_rekan'=>$d->rekan,
						'sumbobot'=>($d->atasan+$d->bawahan+$d->rekan),
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
	function edt_bobot_sikap(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'nama'=>ucwords($this->input->post('nama')),
				'atasan'=>ucwords($this->input->post('bobot_atasan')),
				'bawahan'=>ucwords($this->input->post('bobot_bawahan')),
				'rekan'=>ucwords($this->input->post('bobot_rekan')),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'master_bobot_sikap',['id_bobot'=>$id]);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//------------------------------------------------------------------------------------------------------//
	//Setting Konversi
	public function master_konversi_nilai()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKonversiNilai();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_konversi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_konversi,
						$d->nama,
						$d->awal,
						$d->akhir,
						$d->huruf,
						($d->warna != null) ? '<i class="fas fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(null),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_konversi');
				$data=$this->model_master->getKonversiNilai($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_konversi,
						'nama'=>$d->nama,
						'awal'=>$d->awal,
						'akhir'=>$d->akhir,
						'huruf'=>$d->huruf,
						'warna'=>($d->warna != null) ? '<i class="fas fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(null),
						'warna_val'=>$d->warna,
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
	function add_konversi(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nama=$this->input->post('nama');
		if ($nama != "") {
			$data=[
				'nama'=>ucwords($nama),
				'awal'=>$this->input->post('awal'),
				'akhir'=>$this->input->post('akhir'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'master_konversi_nilai');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_konversi(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'nama'=>ucwords($this->input->post('nama')),
				'awal'=>$this->input->post('awal'),
				'akhir'=>$this->input->post('akhir'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'master_konversi_nilai',['id_konversi'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//------------------------------------------------------------------------------------------------------//
	//Setting Tanggal Update Informasi Karyawan
	public function master_tgl_update_data()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListTglUpdate();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					unset($access['l_ac']['del']);
					$var=[
						'id'=>$d->id_date,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$jum_karyawan=$this->model_karyawan->listEmpActive();
					$ii=explode(';', $d->id_for);
					if (count($ii) == count($jum_karyawan)) {
						$j_kar = '<label class="label label-primary">Semua Karyawan</label>';
					}else{
						$j_kar = '<a href="javascript:void(0)" onclick="view_modal_karyawan('.$d->id_date.')">'.count($ii).' Karyawan</a>';
					}
					$datax['data'][]=[
						$d->id_date,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB',
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB',
						$j_kar,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_date');
				$data=$this->model_master->getTglUpdate($id);
				foreach ($data as $d) {
					$jum_karyawan=$this->model_karyawan->listEmpActive();
					$ii=explode(';', $d->id_for);
					if (count($ii) == count($jum_karyawan)) {
						$j_kar = '<label class="label label-primary">Semua Karyawan</label>';
					}else{
						$j_kar = '<label class="label label-success">'.count($ii).' Karyawan</label>';
					}
					$e_karyawan=[];
					$tunja=(isset($d->id_for)?$this->otherfunctions->getParseOneLevelVar($d->id_for):[]);
					if (isset($tunja)) {
						foreach ($tunja as $key=>$tunj) {
							$e_karyawan[$key]=$tunj;
						}
					}
					$datax=[
						'id'=>$d->id_date,
						'tgl_mulai'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB',
						'tgl_selesai'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB',
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->tgl_selesai),
						'karyawan'=>$j_kar,
						'e_karyawan'=>$e_karyawan,
						'count_kar'=>count($ii),
						'jum_kar'=>count($jum_karyawan),
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
			}elseif ($usage == 'view_karyawan') {
				$id = $this->input->post('id_date');
				$data=$this->model_master->getTglUpdate($id);
				$table = '';
				foreach ($data as $d) {
					$karya=(isset($d->id_for)?$this->otherfunctions->getParseOneLevelVar($d->id_for):[]);
					if (isset($karya)) {
						$no=1;
						foreach ($karya as $key=>$kary) {
							$table .= '<tr">
								<td class="nowrap">'.$no.'</td>
								<td class="nowrap">'.$this->model_karyawan->getEmployeeId($kary)['nama'].'</td>
								<td class="nowrap">'.$this->model_karyawan->getEmployeeId($kary)['nama_jabatan'].'</td>
								</tr>';
							$no++;
						}
					}
				}
				$datax = ['table'=>$table];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edt_up_date_emp(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$start=$this->formatter->getDateFromRange($this->input->post('date'),'start');
			$end=$this->formatter->getDateFromRange($this->input->post('date'),'end');
			$kar=$this->input->post('karyawan');
			$all_kar=$this->input->post('all_kary');
			$data = array(
				'tgl_mulai'=>$start,
				'tgl_selesai'=>$end,
			);
			if ($all_kar=='1') {
				$emp=$this->model_karyawan->listEmpActive();
				foreach ($emp as $em) {
					$op5[]=$em->id_karyawan;
				}
				$data['id_for']=implode(';', $op5);
			}else{
				$data['id_for']=implode(';', $kar);
			}
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'master_tgl_update_data',['id_date'=>$id]);
		}else{
			$datax=$this->messages->notValidParam();  
		}
		echo json_encode($datax);
	}

	//------------------------------------------------------------------------------------------------------//
	//Setting Hak Akses
	public function master_access()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListAccess();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_access,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_access,
						$d->kode_access,
						$d->nama,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_access');
				$data=$this->model_master->getAccess($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_access,
						'kode_access'=>$d->kode_access,
						'nama'=>$d->nama,
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
	function add_access(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_access'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_access',$this->model_master->checkAccessCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_access(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_access'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_access']) {
				$cek=$this->model_master->checkAccessCode($data['kode_access']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_access',['id_access'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}	

	//------------------------------------------------------------------------------------------------------//
	//Setting User Group
	public function master_user_group()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListUserGroup();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_group,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);

					$idm=array_filter(explode(';', $d->list_id_menu));
					$amenu = count($idm).' Menu';
					$amenux ='';
					foreach ($idm as $menul) {
						$mnuu=$this->db->get_where('master_menu',array('id_menu'=>$menul,'id_menu !='=>1))->row_array();
						$amenux .= '<i class="'.$mnuu['icon'].'"></i> '.$mnuu['nama'].'<br>';
					}

					$hak=array_filter(explode(';', $d->list_access));
					$ahak = count($hak).' Hak Akses';
					$ahakx ='';
					foreach ($hak as $hakl) {
						$haku=$this->db->get_where('master_access',array('id_access'=>$hakl))->row_array();
						$ahakx .= '<i class="fa fa-link"></i> '.$haku['nama'].'<br>';
					}

					$datax['data'][]=[
						$d->id_group,
						$d->nama,
						$amenu,
						$ahak,
						$properties['status'],
						$properties['tanggal'],
						$properties['aksi'],
						$amenux,
						$ahakx

					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_group');
				$data=$this->model_master->getUserGroupOne($id);
				foreach ($data as $d) {

					$idm=explode(';', $d->list_id_menu);
						$amenu = count($idm).' Menu';
					$amenux ='';
					foreach ($idm as $menul) {
						$mnuu=$this->db->get_where('master_menu',array('id_menu'=>$menul,'id_menu !='=>1))->row_array();
						$amenux .= '<i class="'.$mnuu['icon'].'"></i> '.$mnuu['nama'].'<br>';
					}
					
					$aks=explode(';', $d->list_access);
						$ahak = count($aks).' Hak Akses';
					$ahakx ='';
					foreach ($aks as $hakl) {
						$haku=$this->db->get_where('master_access',array('id_access'=>$hakl))->row_array();
						$ahakx .= '<i class="fa fa-link"></i> '.$haku['nama'].'<br>';
					}
					$list_bagian=$this->otherfunctions->getParseOneLevelVar($d->list_bagian);
					$bg_detail=$this->otherfunctions->getMark();
					if (!empty($list_bagian)){
						$bg_detail='<ol>';
						foreach ($list_bagian as $lb){
							$db=$this->model_master->getBagian(null,['a.kode_bagian'=>$lb]);
							if (isset($db[0])){
								$bg_detail.='<li>'.$db[0]->nama.(($db[0]->nama_level_struktur)?' ('.$db[0]->nama_level_struktur.')':null).'</li>';
							}
						}
						$bg_detail.='</ol>';
					}
					$datax=[
						'id'=>$d->id_group,
						'nama'=>$d->nama,
						'menu'=>$amenu,
						'akses'=>$ahak,
						'list_bagian'=>$list_bagian,
						'list_bagian_detail'=>$bg_detail,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'checked_menu'=>$idm,
						'checked_akses'=>$aks,
						'detail_menu'=>$amenux,
						'detail_akses'=>$ahakx
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_user_group(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nama=ucwords($this->input->post('nama'));
		$menu_add=$this->input->post('menu_add');
		$akses_add=$this->input->post('akses_add');
		$bagian = $this->input->post('list_bagian');
		if ($nama != "") {
			$data=array(
				'nama'=>$nama,
				'list_id_menu'=>str_replace(",",";",$menu_add),
				// 'list_access'=>str_replace(",",";",$akses_add),
				'list_access'=>$this->otherfunctions->packingArray(explode(',',$akses_add)),
			);
			if(!empty($bagian)){
				if(in_array('all',$bagian)){
					$datac = $this->model_master->getListBagian(true);
					foreach ($datac as $dx) {
						$op5[]=$dx->kode_bagian;
					}
					$data['list_bagian']=implode(';', $op5);
				}else{
					$cek_filter=$this->model_master->checkAccessFilter($data['list_access']);
					$data['list_bagian']=(($cek_filter)?$this->otherfunctions->packingArray($this->input->post('list_bagian')):null);
				}
			}
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_user_group',null);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax); 
	}
	function edt_user_group(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$menu_edit=$this->input->post('menu_edit');
		$akses_edit=$this->input->post('akses_edit');
		$bagian = $this->input->post('list_bagian');
		if ($id != "") {
			$data=array(
				'nama'=>ucwords($this->input->post('nama')),
				'list_id_menu'=>str_replace(",",";",$menu_edit),
				// 'list_access'=>str_replace(",",";",$akses_edit),
				'list_access'=>$this->otherfunctions->packingArray(explode(',',$akses_edit)),
			);
			if(!empty($bagian)){
				if(in_array('all',$bagian)){
					$datac = $this->model_master->getListBagian(true);
					foreach ($datac as $dx) {
						$op5[]=$dx->kode_bagian;
					}
					$data['list_bagian']=implode(';', $op5);
				}else{
					$cek_filter=$this->model_master->checkAccessFilter($data['list_access']);
					$data['list_bagian']=(($cek_filter)?$this->otherfunctions->packingArray($this->input->post('list_bagian')):null);
				}
			}
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQueryCC($data,'master_user_group',['id_group'=>$id],null);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}

//------------------------------------------------------------------------------------------------------//
//Setting User Group FO
	public function master_user_group_user()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListUserGroupUser();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_group,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$properties['aksi']=str_replace('view_modal', 'view_modal_user', $properties['aksi']);
					$properties['status']=str_replace('do_status', 'do_status_user', $properties['status']);
					$properties['aksi']=str_replace('delete_modal', 'delete_modal_user', $properties['aksi']);
					$idm=array_filter(explode(';', $d->list_id_menu));
					$amenu = count($idm).' Menu';
					$amenux ='';
					foreach ($idm as $menul) {
						$mnuu=$this->db->get_where('master_menu_user',array('id_menu'=>$menul,'id_menu !='=>0))->row_array();
						$amenux .= '<i class="'.$mnuu['icon'].'"></i> '.$mnuu['nama'].'<br>';
					}

					$hak=array_filter(explode(';', $d->list_access));
					$ahak = count($hak).' Hak Akses';
					$ahakx ='';
					foreach ($hak as $hakl) {
						$haku=$this->db->get_where('master_access',array('id_access'=>$hakl))->row_array();
						$ahakx .= '<i class="fa fa-link"></i> '.$haku['nama'].'<br>';
					}

					$datax['data'][]=[
						$d->id_group,
						$d->nama,
						$amenu,
						$ahak,
						$properties['status'],
						$properties['tanggal'],
						$properties['aksi'],
						$amenux,
						$ahakx

					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_group');
				$data=$this->model_master->getUserGroupOneUser($id);
				foreach ($data as $d) {

					$idm=array_filter(explode(';', $d->list_id_menu));
					$idm_val=explode(';', $d->list_id_menu);
					$amenu = count($idm).' Menu';
					$amenux ='';
					foreach ($idm as $menul) {
						$mnuu=$this->db->get_where('master_menu_user',array('id_menu'=>$menul,'id_menu !='=>1))->row_array();
						$amenux .= '<i class="'.$mnuu['icon'].'"></i> '.$mnuu['nama'].'<br>';
					}
					
					$aks=array_filter(explode(';', $d->list_access));
					$aks_val=explode(';', $d->list_access);
					$ahak = count($aks).' Hak Akses';
					$ahakx ='';
					foreach ($aks as $hakl) {
						$haku=$this->db->get_where('master_access',array('id_access'=>$hakl))->row_array();
						$ahakx .= '<i class="fa fa-link"></i> '.$haku['nama'].'<br>';
					}
					
					$datax=[
						'id'=>$d->id_group,
						'nama'=>$d->nama,
						'menu'=>$amenu,
						'akses'=>$ahak,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'checked_menu'=>$idm_val,
						'checked_akses'=>$aks_val,
						'detail_menu'=>$amenux,
						'detail_akses'=>$ahakx
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_user_group_user(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$nama=ucwords($this->input->post('nama'));
		$menu_add=$this->input->post('menu_add');
		$akses_add=$this->input->post('akses_add');
		if ($nama != "") {
			$data=array(
				'nama'=>$nama,
				'list_id_menu'=>str_replace(",",";",$menu_add),
				'list_access'=>str_replace(",",";",$akses_add),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_user_group_user',null);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax); 
	}
	function edt_user_group_user(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$menu_edit=$this->input->post('menu_edit');
		$akses_edit=$this->input->post('akses_edit');
		if ($id != "") {
			$data=array(
				'nama'=>ucwords($this->input->post('nama')),
				'list_id_menu'=>str_replace(",",";",$menu_edit),
				'list_access'=>str_replace(",",";",$akses_edit),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQueryCC($data,'master_user_group_user',['id_group'=>$id],null);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}

	//------------------------------------------------------------------------------------------------------//
	//Setting Manajemen Menu
	public function master_menu(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListMenu();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_menu,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
		      		$sb=$this->otherfunctions->getParseOneLevelVar($d->sub_url);
					$res=null;
					if (count($sb) > 0) {
						$res='<ol>';	
						foreach ($sb as $sbb) {
							$res.='<li>'.$sbb.'</li>';
						}
						$res.='</ol>';
					}
					$datax['data'][]=[
						$d->id_menu,
						'<i class="fa '.$d->icon.'"></i> '.$d->nama,
						$d->parent_name,
						$d->sequence,
						$d->url,
						$res,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_menu');
				$data=$this->model_master->getAllMenubyId($id);
				foreach ($data as $d) {
					$sb=$this->otherfunctions->getParseOneLevelVar($d->sub_url);
					$res=null;
					if (count($sb) > 0) {
						$res='<ol>';	
						foreach ($sb as $sbb) {
							$res.='<li>'.$sbb.'</li>';
						}
						$res.='</ol>';
					}
					$datax=[
						'id'=>$d->id_menu,
						'nama'=>$d->nama,
						'parent'=>$d->parent_name,
						'parent_val'=>$d->parent,
						'url'=>$d->url,
						'sub_url'=>$res,
						'sub_url_val'=>$d->sub_url,
						'icon'=>$d->icon,
						'sequence'=>$d->sequence,
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
	function add_menu(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nama=$this->input->post('nama');
		$seq=$this->input->post('sequence');
		$parent=$this->input->post('parent');
		$sub_in=$this->input->post('sub_url');
		if ($nama == "" || $seq == "" || $parent == ""){
			echo json_encode($this->messages->notValidParam());
		}else{
			$icon =$this->input->post('icon');
			if ($icon == '') {
				$icon = 'fas fa-chevron-circle-right';
			}
			$url=strtolower($this->input->post('url'));
			$par=$this->model_master->getAllMenubyId($parent);
			foreach ($par as $px) {
				$sub_url=$this->otherfunctions->addValueToArrayDb($px->sub_url,$url,';');
				// if ($px->parent !) {
					
				// }
			}
			if ($parent != 0) {
				$this->model_global->updateQuery(['sub_url'=>$sub_url],'master_menu',['id_menu'=>$parent]);
			}
			$data=[
				'nama'=>ucwords($nama),
				'parent'=>$parent,
				'sequence'=>$seq,
				'icon'=>$icon,
				'url'=>$url,
				'sub_url'=>$sub_in,
			]; 
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			echo json_encode($this->model_global->insertQuery($data,'master_menu'));
		}
	}
	function edt_menu(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$nama=$this->input->post('nama');
		$seq=$this->input->post('sequence');
		$parent=$this->input->post('parent');
		$parent_old=$this->input->post('parent_old');
		$sub_in=$this->input->post('sub_url');
		if ($id == "" || $parent_old == "" || $nama == "" || $seq == "" || $parent == ""){
			echo json_encode($this->messages->unfillForm());
		}else{
			$icon =$this->input->post('icon');
			if ($icon == '') {
				$icon = 'fas fa-chevron-circle-right';
			}
			$url=strtolower($this->input->post('url'));
			$par=$this->model_master->getAllMenubyId($parent);
			if ($parent_old == $parent) {
				foreach ($par as $px) {
					$sub_url_add=$this->otherfunctions->addValueToArrayDb($px->sub_url,$url,';');
				}
				if ($parent != 0) {
					$this->model_global->updateQuery(['sub_url'=>$sub_url_add],'master_menu',['id_menu'=>$parent]);
				}
			}else{
				$par_old=$this->model_master->getAllMenubyId($parent_old);
				foreach ($par_old as $px_old) {
					$sub_url_del=$this->otherfunctions->removeValueToArrayDb($px_old->sub_url,$url,';');
				}
				foreach ($par as $px) {
					$sub_url_add=$this->otherfunctions->addValueToArrayDb($px->sub_url,$url,';');
				}
				if ($parent_old != 0 && $parent != 0) {
					$this->model_global->updateQuery(['sub_url'=>$sub_url_add],'master_menu',['id_menu'=>$parent]);
					$this->model_global->updateQuery(['sub_url'=>$sub_url_del],'master_menu',['id_menu'=>$parent_old]);
				}
			}
			$data=[
				'nama'=>ucwords($nama),
				'parent'=>$parent,
				'sequence'=>$seq,
				'icon'=>$icon,
				'url'=>$url,
				'sub_url'=>$sub_in,
			]; 
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			echo json_encode($this->model_global->updateQuery($data,'master_menu',['id_menu'=>$id]));
		}
	}
	//Setting Manajemen Menu User Admin
	public function master_menu_user(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListMenuUser();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_menu,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$properties['aksi']=str_replace('view_modal', 'view_modal_u', $properties['aksi']);
					$properties['status']=str_replace('do_status', 'do_status_u', $properties['status']);
					$properties['aksi']=str_replace('delete_modal', 'delete_modal_u', $properties['aksi']);
					$sb=$this->otherfunctions->getParseOneLevelVar($d->sub_url);
					$res=null;
					if (count($sb) > 0) {
						$res='<ol>';	
						foreach ($sb as $sbb) {
							$res.='<li>'.$sbb.'</li>';
						}
						$res.='</ol>';
					}
					$datax['data'][]=[
						$d->id_menu,
						'<i class="fa '.$d->icon.'"></i> '.$d->nama,
						$d->parent_name,
						$d->sequence,
						$d->url,
						$res,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_menu');
				$data=$this->model_master->getAllMenuUserbyId($id);
				foreach ($data as $d) {
					$sb=$this->otherfunctions->getParseOneLevelVar($d->sub_url);
					$res=null;
					if (count($sb) > 0) {
						$res='<ol>';	
						foreach ($sb as $sbb) {
							$res.='<li>'.$sbb.'</li>';
						}
						$res.='</ol>';
					}
					$datax=[
						'id'=>$d->id_menu,
						'nama'=>$d->nama,
						'parent'=>$d->parent_name,
						'parent_val'=>$d->parent,
						'url'=>$d->url,
						'sub_url'=>$res,
						'sub_url_val'=>$d->sub_url,
						'icon'=>$d->icon,
						'sequence'=>$d->sequence,
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
	function add_user_menu(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$nama=$this->input->post('nama');
		$seq=$this->input->post('sequence');
		$parent=$this->input->post('parent');
		$sub_in=$this->input->post('sub_url');
		if ($nama == "" || $seq == "" || $parent == ""){
			echo json_encode($this->messages->notValidParam());
		}else{
			$icon =$this->input->post('icon');
			if ($icon == '') {
				$icon = 'fas fa-chevron-circle-right';
			}
			$url=strtolower($this->input->post('url'));
			$par=$this->model_master->getAllMenuUserbyId($parent);
			$sub_url='';
			foreach ($par as $px) {
				$sub_url=$this->otherfunctions->addValueToArrayDb($px->sub_url,$url,';');
			}
			if ($parent != 0) {
				$this->model_global->updateQuery(['sub_url'=>$sub_url],'master_menu_user',['id_menu'=>$parent]);
			}
			$data=[
				'nama'=>ucwords($nama),
				'parent'=>$parent,
				'sequence'=>$seq,
				'icon'=>$icon,
				'url'=>$url,
				'sub_url'=>$sub_in,
			]; 
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			echo json_encode($this->model_global->insertQuery($data,'master_menu_user'));
		}
	}
	function edt_user_menu(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		$nama=$this->input->post('nama');
		$seq=$this->input->post('sequence');
		$parent=$this->input->post('parent');
		$parent_old=$this->input->post('parent_old');
		$sub_in=$this->input->post('sub_url');
		if ($id == "" || $parent_old == "" || $nama == "" || $seq == "" || $parent == ""){
			echo json_encode($this->messages->unfillForm());
		}else{
			$icon =$this->input->post('icon');
			if ($icon == '') {
				$icon = 'fas fa-chevron-circle-right';
			}
			$url=strtolower($this->input->post('url'));
			$par=$this->model_master->getAllMenuUserbyId($parent);
			$sub_url_add='';
			if ($parent_old == $parent) {
				foreach ($par as $px) {
					$sub_url_add=$this->otherfunctions->addValueToArrayDb($px->sub_url,$url,';');
				}
				if ($parent != 0) {
					$this->model_global->updateQuery(['sub_url'=>$sub_url_add],'master_menu_user',['id_menu'=>$parent]);
				}
			}else{
				$par_old=$this->model_master->getAllMenuUserbyId($parent_old);
				foreach ($par_old as $px_old) {
					$sub_url_del=$this->otherfunctions->removeValueToArrayDb($px_old->sub_url,$url,';');
				}
				foreach ($par as $px) {
					$sub_url_add=$this->otherfunctions->addValueToArrayDb($px->sub_url,$url,';');
				}
				if ($parent_old != 0 && $parent != 0) {
					$this->model_global->updateQuery(['sub_url'=>$sub_url_add],'master_menu_user',['id_menu'=>$parent]);
					$this->model_global->updateQuery(['sub_url'=>$sub_url_del],'master_menu_user',['id_menu'=>$parent_old]);
				}
			}
			$data=[
				'nama'=>ucwords($nama),
				'parent'=>$parent,
				'sequence'=>$seq,
				'icon'=>$icon,
				'url'=>$url,
				'sub_url'=>$sub_in,
			]; 
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			echo json_encode($this->model_global->updateQuery($data,'master_menu_user',['id_menu'=>$id]));
		}
	}
	//------------------------------------------------------------------------------------------------------//
	//Setting Manajemen Notifikasi
	public function master_notifikasi()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListNotif();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				$adm=$this->model_admin->getAdminAllActive();
				$kar=$this->model_karyawan->getListEmployeeActive();
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_notif,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
        			$send = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_send('.$d->id_notif.')><i class="fa fa-send" data-toggle="tooltip" title="Send Email"></i></button> ';
					if ($d->untuk == 'ADM') {
						$fadm=$this->otherfunctions->getDataExplode($d->id_for,';','all');
						if (count($fadm) == count($adm)) {
							$forx = '<label class="label label-primary">Semua Admin</label>';
						}else{
							$forx = '<a href="javascript:void(0)" onclick=view_modal_for('.$d->id_notif.') data-toggle="modal">'.count($fadm).' Admin</a>';
						}
					}else{
						$fkar=$this->otherfunctions->getDataExplode($d->id_for,';','all');
						if (count($fkar) == count($kar)) {
							$forx = '<label class="label label-primary">Semua Karyawan</label>';
						}else{
							$forx = '<a href="javascript:void(0)" onclick=view_modal_for('.$d->id_notif.') data-toggle="modal">'.count($fkar).' Karyawan</a>';
						}
					}
					// $isi = '<div style="white-space:normal;word-wrap: break-word;"><span id="read_partian_'.$d->id_notif.'" title="'.$d->isi.'">'.$this->formatter->limit_words($d->isi,3).'... <a onclick="readmore('.$d->id_notif.')" style="cursor: pointer;color:#4A89BF;"><i class="fa fa-chevron-circle-right fa-fw"></i></a></span><span id="read_full_'.$d->id_notif.'" style="display:none;">'.$d->isi.'  <a onclick="hidemore('.$d->id_notif.')" style="cursor: pointer;color:#4A89BF;"><i class="fa fa-chevron-circle-left fa-fw"></i></a></span></div>';
					$isi = '<div style="white-space:normal;word-wrap: break-word;"><span id="read_partian_'.$d->id_notif.'" title="'.$d->isi.'">'.$this->formatter->limit_words($d->isi,3).'... </span></div>';
					$datax['data'][]=[
						$d->id_notif,
						$d->kode_notif,
						$d->judul,
						// $isi,
						$this->formatter->limit_words($d->isi,3),
						'<label class="label label-success" data-toggle="tooltip" title="Tanggal Mulai">'.$this->formatter->getDateTimeMonthFormatUser($d->start).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Tanggal Selesai">'.$this->formatter->getDateTimeMonthFormatUser($d->end_date).' WIB</label>',
						$this->otherfunctions->getSifat($d->sifat),
						$this->otherfunctions->getTipeNotifikasi($d->tipe),
						$forx,
						$properties['tanggal'],
						$properties['status'],
						$send.$properties['aksi'],
						$d->isi,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_notif');
				$data=$this->model_master->getNotif($id);
				foreach ($data as $d) {
					$adm=$this->model_admin->getListAdminActive();
					$kar=$this->model_karyawan->getListEmployeeActive();
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Tanggal Mulai"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->start).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Tanggal Selesai"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->end_date).' WIB</label>';
					$read ='';
					$read.='<ol>';
					$ii =$this->otherfunctions->getParseOneLevelVar($d->id_read);
					foreach ($ii as $i) {
						if ($d->untuk == "ADM") {
							$cn=$this->db->query("SELECT nama FROM admin WHERE id_admin = '$i'")->row_array();
						}else{
							$cn=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$i'")->row_array();
						}
						$read.= '<li>'.$cn['nama'].'</li>';
					}
					$read.= '</ol>';
					$del ='';
					$del.='<ol>';
					$iii =$this->otherfunctions->getParseOneLevelVar($d->id_del);
					foreach ($iii as $i) {
						if ($d->untuk == "ADM") {
							$cn=$this->db->query("SELECT nama FROM admin WHERE id_admin = '$i'")->row_array();
						}else{
							$cn=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$i'")->row_array();
						}
						$del.= '<li>'.$cn['nama'].'</li>';
					}
					$del.= '</ol>';
					$for=[];
					$forii=$this->otherfunctions->getParseOneLevelVar($d->id_for);
					if ($d->untuk == 'ADM') {
						if (count($forii) == count($adm)) {
							$for = 'ALL';
						}else{
							foreach ($forii as $iv) {
								$for[]=$iv;
							}
						}
					}else{
						if (count($forii) == count($kar)) {
							$for = 'ALL';
						}else{
							foreach ($forii as $iv) {
								$for[]=$iv;
							}
						}
					}
					$nama_for=($d->untuk == 'ADM')?'Admin':'Karyawan';
					$jml_baca=(count($ii)>0)?count($ii).' '.$nama_for:'<label class="label label-danger">Belum dibaca</label>';
					$jml_del=(count($iii)>0)?count($iii).' '.$nama_for:'<label class="label label-danger">Belum dihapus</label>';
					$nama_file=str_replace('styles/assets/document/pdf/','',$d->file);
					$file=(!empty($d->file))?'<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($d->file)).'">'.$nama_file.'</a><br><span class="text-sm">*Klik untuk download file</span>':'<label class="label label-danger">Tidak Ada File</label>';
					$isi_read='<div style="white-space:normal;word-wrap: break-word;"><span id="read_partian2_'.$d->id_notif.'" title="'.$d->isi.'">'.$this->formatter->limit_words($d->isi).'... <a onclick="readmore2('.$d->id_notif.')" style="cursor: pointer;color:#4A89BF;"><i class="fa fa-chevron-circle-right fa-fw"></i></a></span><span id="read_full2_'.$d->id_notif.'" style="display:none;">'.$d->isi.' <a onclick="hidemore2('.$d->id_notif.')" style="cursor: pointer;color:#4A89BF;"><i class="fa fa-chevron-circle-left fa-fw"></i></a></span></div>';
					$datax=[
						'id'=>$d->id_notif,
						'kode_notif'=>$d->kode_notif,
						'judul'=>$d->judul,
						// 'isi'=>$isi_read,
						'isi'=>$d->isi,
						'nama_sifat'=>$this->otherfunctions->getSifat($d->sifat),
						'nama_tipe'=>$this->otherfunctions->getTipeNotifikasi($d->tipe),
						'tgl_notif'=>$tanggal,
						'nama_untuk'=>$nama_for,
						'jml_bc'=>count($ii),
						'id_read'=>$read,
						'jumlah_baca'=>$jml_baca,
						'jml_del'=>count($iii),
						'jumlah_delete'=>$jml_del,
						'id_del'=>$del,
						'file'=>$file,
						'link_file'=>$d->file,
						'nama_file'=>$nama_file,
						'untuk_edit'=>$d->untuk,
						'tipe_edit'=>$d->tipe,
						'sifat_edit'=>$d->sifat,
						'id_for'=>$for,
						'eisi'=>$d->isi,
						'tgl_mulai_val'=>$this->formatter->getDateTimeFormatUser($d->start),
						'tgl_selesai_val'=>$this->formatter->getDateTimeFormatUser($d->end_date),
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
			}elseif ($usage == 'view_daftar') {
				$id = $this->input->post('id_notif');
				$data=$this->model_master->getNotif($id);
				foreach ($data as $d) {
					$untuk=($d->untuk == 'ADM')?'Daftar Admin':'Daftar Karyawan';
					$daftar='';
					$daftar.='<div class="modal-body" style="height:500px;overflow:auto;">
					<ol>';
					$ii =$this->otherfunctions->getParseOneLevelVar($d->id_for);
					foreach ($ii as $i) {
						if ($d->untuk == "ADM") {
							$cn=$this->db->query("SELECT nama FROM admin WHERE id_admin = '$i'")->row_array();
						}else{
							$cn=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$i'")->row_array();
						}
						$daftar.= '<li>'.$cn['nama'].'</li>';
					}
					$daftar.= '</ol></div>';
					$datax=[
						'untuk'=>$untuk,
						'daftar'=>$daftar,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeNotif();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_notif(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		$judul=$this->input->post('judul');
		if ($kode != "") {
			$data=array(
				'kode_notif'=>$kode,
				'judul'=>ucwords($judul),
				'isi'=>ucwords($this->input->post('isi_notif')),
				'start'=>$this->formatter->getDateFromRange($this->input->post('date'),'start'),
				'end_date'=>$this->formatter->getDateFromRange($this->input->post('date'),'end'),
				'sifat'=>$this->input->post('sifat'),
				'tipe'=>$this->input->post('tipe'),
				'untuk'=>$this->input->post('untuk'),
			);
			if ($this->input->post('untuk') == "ADM") {
				$admx=$this->input->post('adm');
				if (in_array('ALL', $admx)) {
					$adm=$this->model_admin->getListAdminActive();
					foreach ($adm as $k_ad=>$v_ad) {
						$op5[]=$k_ad;
					}
					$data['id_for']=implode(';', $op5);
				}else{
					$data['id_for']=implode(';', $admx);
				}
			}else{
				$empx=$this->input->post('emp');
				if (in_array('ALL', $empx)) {
					$emp=$this->model_karyawan->getListEmployeeActive();
					foreach ($emp as $k_em=>$v_em) {
						$op5x[]=$k_em;
					}
					$data['id_for']=implode(';', $op5x);
				}else{
					$data['id_for']=implode(';', $empx);
				}
			}
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax=$this->model_global->insertQueryCC($data,'notification',$this->model_master->checkNotifCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_notif(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'judul'=>ucwords($this->input->post('judul')),
				'isi'=>ucwords($this->input->post('isi')),
				'start'=>$this->formatter->getDateFromRange($this->input->post('date'),'start'),
				'end_date'=>$this->formatter->getDateFromRange($this->input->post('date'),'end'),
				'sifat'=>$this->input->post('sifat'),
				'tipe'=>$this->input->post('tipe'),
				'untuk'=>$this->input->post('untuk'),
			);
			if ($this->input->post('untuk') == "ADM") {
				$admx=$this->input->post('adm');
				if (in_array('ALL', $admx)) {
					$adm=$this->model_admin->getListAdminActive();
					foreach ($adm as $k_ad=>$v_ad) {
						$op5[]=$k_ad;
					}
					$data['id_for']=implode(';', $op5);
				}else{
					$data['id_for']=implode(';', $admx);
				}
			}else{
				$empx=$this->input->post('emp');
				if (in_array('ALL', $empx)) {
					$emp=$this->model_karyawan->getListEmployeeActive();
					foreach ($emp as $k_em=>$v_em) {
						$op5x[]=$k_em;
					}
					$data['id_for']=implode(';', $op5x);
				}else{
					$data['id_for']=implode(';', $empx);
				}
			}
			$old=$this->input->post('kode_old');
			if ($old != $kode) {
				$cek=$this->model_master->checkNotifCode($kode);
			}else{
				$cek=false;
			}
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin,$status));
			$datax=$this->model_global->updateQueryCC($data,'notification',['id_notif'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}            
	function del_notif_users(){
		$kode=$this->input->post('kode');
		$cek=$this->model_master->k_notif($kode);
		if ($kode != "" && count($cek) > 0) {
			$id=explode(';', $cek['id_del']);
			if (!in_array($this->admin, $id)) {
				array_push($id, $this->admin);
			}
			if (isset($id)) {
				$idd=implode(';', array_unique(array_filter($id)));
			}else{
				$idd=NULL;
			}
			$data=array('id_del'=>$idd,);
			$datax=$this->model_global->updateQuery($data,'notification',['kode_notif'=>$kode]);
		}else{
			$datax=$this->messages->notValidParam();  
		}
		echo json_encode($datax);
	}
	function send_email_notifikasi(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_notifikasi = $this->input->post('id_notifikasi');
		$notif=$this->model_master->getNotif($id_notifikasi,true);
		$karyawan = $this->input->post('karyawan');
		$subject = $this->input->post('subject');
		$isi_email = $this->input->post('isi_email');
		$emailKar=[];
		foreach ($karyawan as $kar => $emp) {
			$emailKar[] = $this->model_karyawan->getEmpID($emp)['email'];
		}
		$emailEmp=array_filter(array_unique($emailKar));
		$data_ntf = [
			'judul'       => $notif['judul'],
			'isi'         => $notif['isi'],
			'tgl_mulai'   => $this->formatter->getDateTimeMonthFormatUser($notif['start']),
			'tgl_selesai' => $this->formatter->getDateTimeMonthFormatUser($notif['end_date']),
			'sifat'       => $this->otherfunctions->getSifat($notif['sifat']),
			'tipe'        => $this->otherfunctions->getTipeNotifikasi($notif['tipe']),
			'tgl'         => $this->date,
			'isi_email'   => $isi_email,
			'url'         => $this->otherfunctions->companyClientProfile()['url'],
			'logo'        => $this->otherfunctions->companyClientProfile()['logo_url'],
			'subject'	  => $subject,
			'name_office' => $this->otherfunctions->companyClientProfile()['name_office'],
		];
		if(isset($karyawan)){
			$ci = get_instance();
			$ci->email->initialize($this->otherfunctions->configEmail());
			$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengingat Input PA Online');
			$ci->email->to($emailEmp);
			$ci->email->subject($subject);
			$body = $this->load->view('email_template/email_notifikasi',$data_ntf,TRUE);
			$ci->email->message($body);
			$eml=$this->email->send();
		}
		if($eml){
			$datax=$this->messages->customGood('Email Berhasil Terkirim');
		}else{
			$datax=$this->messages->customFailure('Email Tidak Terkirim');
		}
		echo json_encode($datax);
	}
	function del_berkas()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$berkas=$this->input->post('berkas');
		$id=$this->input->post('id');
		$table='notification';
		$column='id_notif';
		
		if($id != ""){
			if($berkas != ""){
				$where=[$column=>$id];
				$path = 'asset/upload/'.$berkas;
				if(unlink($path)){
					$datax=$this->model_global->deleteQuery($table,$where);
				}
			}else{
				$where=[$column=>$id];
				$datax=$this->model_global->deleteQuery($table,$where);
			}
		}else{
			$datax=$this->messages->notValidParam();  
		}
		echo json_encode($datax);
	}

	//------------------------------------------------------------------------------------------------------//
	//Setting Root Password
	public function master_root_password()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_master->getRootPassword($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id,
						'plain'=>$d->plain,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function reset_root_pass(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$pass=$this->input->post('password');
		if ($pass != "") {
			$data=array('plain'=>$pass,'encrypt'=>$this->codegenerator->genPassword($pass));
			// $emp=array('root_password'=>$this->codegenerator->genPassword($pass));
			// $this->db->update('karyawan',$emp);
			echo json_encode($this->model_global->updateQuery($data,'root_password',['id'=>1]));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//--------------------------------------------------------------------------------------------------//
	// Setting company profile
	public function company_profile()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_master->getDataCompany();
				// foreach ($data as $d) {
					$datax=[
						'id'=>$data['id'],
						'visi'=>$data['visi'],
						'misi'=>$data['misi'],
						'moto'=>$data['moto'],
						'sejarah'=>$data['sejarah'],
					];
				// }
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	} 
	function edit_company(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'visi'=>$this->input->post('visi'),
				'misi'=>$this->input->post('misi'),
				'moto'=>$this->input->post('moto'),
				'sejarah'=>$this->input->post('sejarah'),
			);
			// $data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_company_profile',['id'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Berita
	public function data_berita()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListBerita();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_berita,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
                        $d->id_berita,
                        '<img src="'.base_url($d->gambar).'" width="200px">',
						$d->judul,
						$d->nama_kategori,
						$this->formatter->getDateMonthFormatUser($d->tgl_posting),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_berita');
				$data=$this->model_master->getBerita($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_berita,
						'nama'=>$d->judul,
						'judul'=>$d->judul,
						'gambar'=>'<img src="'.base_url($d->gambar).'" width="400px" style="align:center;">',
						'e_gambar'=>$d->gambar,
						'isi'=>$d->isi,
						'nama_kategori'=>$d->nama_kategori,
						'e_kategori'=>$d->kategori,
						'tgl_posting'=>$this->formatter->getDateMonthFormatUser($d->tgl_posting),
						'e_tgl'=>$this->formatter->getDateFormatUser($d->tgl_posting),
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
				$data = $this->codegenerator->kodeDokumen();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_berita(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');        
		$judul=$this->input->post('judul');
		if ($judul != "") {
			$other=[
				'judul'=>ucwords($judul),
				'kategori'=>$this->input->post('kategori'),
				'tgl_posting'=>$this->formatter->getDateFormatDb($this->input->post('tgl_posting')),
				'isi'=>$this->input->post('isi'),
			];
			$other=array_merge($other,$this->model_global->getCreateProperties($this->admin));
			$data=[
				'post'=>'file',
		        'data_post'=>$this->input->post('file', TRUE),
		        'table'=>'data_berita',
		        'column'=>'gambar', 
		        'usage'=>'insert',
		        'otherdata'=>$other,
		    ];
		    $datax=$this->filehandler->doUpload($data,'image','berita');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_berita(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$judul=$this->input->post('judul');
		if ($judul != "") {
			$other=[
				'judul'=>ucwords($judul),
				'kategori'=>$this->input->post('kategori'),
				'isi'=>$this->input->post('isi'),
				'tgl_posting'=>$this->formatter->getDateFormatDb($this->input->post('tgl_posting')),
			];
			$other=array_merge($other,$this->model_global->getUpdateProperties($this->admin));
			$data=[
				'post'=>'file',
		        'data_post'=>$this->input->post('file', TRUE),
		        'table'=>'data_berita',
		        'column'=>'gambar',
		        'where'=>['id_berita'=>$id],
		        'usage'=>'update',
                'otherdata'=>$other,
                'unlink'=>'yes',
		    ];
		    $datax=$this->filehandler->doUpload($data,'image','berita');
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Data Struktur
	public function data_struktur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListStruktur();
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
					$properties['aksi']=str_replace('view_modal', 'view_modal_s', $properties['aksi']);
					$properties['status']=str_replace('do_status', 'do_status_s', $properties['status']);
					$properties['aksi']=str_replace('delete_modal', 'delete_modal_s', $properties['aksi']);
					$datax['data'][]=[
                        $d->id,
                        '<img src="'.base_url($d->gambar).'" width="200px">',
						$d->judul,
						$d->nama_lokasi,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_master->getStruktur($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id,
						'nama'=>$d->judul,
						'judul'=>$d->judul,
						'gambar'=>'<img src="'.base_url($d->gambar).'" width="400px" style="align:center;">',
						'e_gambar'=>$d->gambar,
						'isi'=>$d->isi,
						'nama_lokasi'=>$d->nama_lokasi,
						'e_lokasi'=>$d->lokasi,
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
				$data = $this->codegenerator->kodeDokumen();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_struktur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');        
		$judul=$this->input->post('judul');
		if ($judul != "") {
			$other=[
				'judul'=>ucwords($judul),
				'isi'=>$this->input->post('isi'),
				'lokasi'=>$this->input->post('lokasi'),
			];
			$other=array_merge($other,$this->model_global->getCreateProperties($this->admin));
			$data=[
				'post'=>'file',
		        'data_post'=>$this->input->post('file', TRUE),
		        'table'=>'data_struktur',
		        'column'=>'gambar', 
		        'usage'=>'insert',
		        'otherdata'=>$other,
		    ];
		    $datax=$this->filehandler->doUpload($data,'image','struktur');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_struktur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$judul=$this->input->post('judul');
		if ($judul != "") {
			$other=[
				'judul'=>ucwords($judul),
				'isi'=>$this->input->post('isi'),
				'lokasi'=>$this->input->post('lokasi'),
			];
			$other=array_merge($other,$this->model_global->getUpdateProperties($this->admin));
			$data=[
				'post'=>'file',
		        'data_post'=>$this->input->post('file', TRUE),
		        'table'=>'data_struktur',
		        'column'=>'gambar',
		        'where'=>['id'=>$id],
		        'usage'=>'update',
                'otherdata'=>$other,
                'unlink'=>'yes',
		    ];
		    $datax=$this->filehandler->doUpload($data,'image','struktur');
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//------------------------------------------------------------------------------------------------------//
	//General Setting 
	//====Aktif non aktif Uang makan====//
	public function setting_setting_um()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'setting_um'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('SET_UM');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$this->otherfunctions->getAktifUangMakanKey($data['value_int']),
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_setting_um_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$nominal=$this->input->post('setting_um');
		if ($nominal != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$nominal],'general_settings',['kode'=>'SET_UM']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Uang makan====//
	public function setting_uang_makan()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'uang_makan'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('UM');
					if (isset($data)){
						$datax=[
							'value'=>$this->formatter->getFormatMoneyUser($data['value_decimal']),
							'plain'=>$this->formatter->getFormatMoneyUser($data['value_decimal']),
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_uang_makan_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$nominal=$this->input->post('nominal');
		if ($nominal != "") {
			echo json_encode($this->model_global->updateQuery(['value_decimal'=>$this->formatter->getFormatMoneyDb($nominal)],'general_settings',['kode'=>'UM']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Jam Kerja 1 Bulam====//
	public function setting_jam_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'jam_kerja'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('JKSB');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'].' Jam',
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_jam_kerja_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$jum_jam_kerja=$this->input->post('jam_kerja');
		if ($jum_jam_kerja != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$jum_jam_kerja],'general_settings',['kode'=>'JKSB']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	public function setting_jam_kerja_s()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'jam_kerja_s'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('JKSRNDL');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'].' Jam',
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_jam_kerja_s_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$jum_jam_kerja=$this->input->post('jam_kerja_s');
		if ($jum_jam_kerja != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$jum_jam_kerja],'general_settings',['kode'=>'JKSRNDL']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Tahun karyawan====//
	public function setting_tahun_kar()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'tahun_kar'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('TPB');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'],
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_tahun_kar_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$tahun_kar=$this->input->post('tahun_kar');
		if ($tahun_kar != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$tahun_kar],'general_settings',['kode'=>'TPB']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Potongan karyawan Baru====//
	public function setting_kar_new()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'kar_new'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('PTMB');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'],
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_kar_new_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kar_new=$this->input->post('kar_new');
		if ($kar_new != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$kar_new],'general_settings',['kode'=>'PTMB']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Potongan karyawan Lama====//
	public function setting_kar_old()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'kar_old'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('PTML');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'],
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_kar_old_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kar_old=$this->input->post('kar_old');
		if ($kar_old != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$kar_old],'general_settings',['kode'=>'PTML']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Persentase potongan gaji karyawan non_wfh====//
	public function setting_non_wfh()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'non_wfh'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('NONWFH');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'],
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_non_wfh_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$non_wfh=$this->input->post('non_wfh');
		if ($non_wfh != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$non_wfh],'general_settings',['kode'=>'NONWFH']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Persentase potongan gaji karyawan WFH====//
	public function setting_wfh()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'wfh'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('WFH');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'],
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_wfh_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$wfh=$this->input->post('wfh');
		if ($wfh != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$wfh],'general_settings',['kode'=>'WFH']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Besar Minimal Pesangon dikenakan pph====//
	public function setting_minp()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'minp'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('MIN_P');
					if (isset($data)){
						$datax=[
							'value'=>$this->formatter->getFormatMoneyUser($data['value_decimal']),
							'plain'=>$this->formatter->getFormatMoneyUser($data['value_decimal']),
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_minp_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$minp=$this->input->post('minp');
		if ($minp != "") {
			echo json_encode($this->model_global->updateQuery(['value_decimal'=>$this->formatter->getFormatMoneyDb($minp)],'general_settings',['kode'=>'MIN_P']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Persentase Potongan pesangon NPWP====//
	public function setting_npwpp()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'npwpp'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('NPWP_P');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'],
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_npwpp_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$npwpp=$this->input->post('npwpp');
		if ($npwpp != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$npwpp],'general_settings',['kode'=>'NPWP_P']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Persentase Potongan pesangon non NPWP====//
	public function setting_nnpwpp()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'nnpwpp'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('NNPWP_P');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'],
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_nnpwpp_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$nnpwpp=$this->input->post('nnpwpp');
		if ($nnpwpp != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$nnpwpp],'general_settings',['kode'=>'NNPWP_P']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//====Uang makan====//
	public function setting_umur_pensiun()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'umur_pensiun'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('UMUR_PENSIUN');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'],
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_umur_pensiun_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$umur_pensiun=$this->input->post('umur_pensiun');
		if ($umur_pensiun != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$umur_pensiun],'general_settings',['kode'=>'UMUR_PENSIUN']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//==== Jarak Minimum untuk mendapatkan uang makan perjalanan dinas====//
	public function setting_jarak_min()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'jarak_min'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('MJPD');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'].' KM',
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_jarak_min_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$jum_jarak_min=$this->input->post('jarak_min');
		if ($jum_jarak_min != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$jum_jarak_min],'general_settings',['kode'=>'MJPD']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	public function setting_jarak_min_non()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'jarak_min_non'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('MJPDN');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'].' KM',
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function setting_jarak_min_non_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$jum_jarak_min=$this->input->post('jarak_min_non');
		if ($jum_jarak_min != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$jum_jarak_min],'general_settings',['kode'=>'MJPDN']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	// insentif bantuan plan
	public function setting_jarak_min_ibp()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'jarak_min_ibp'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('JMIBP');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'].' KM',
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function setting_jarak_min_ibp_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$jum_jarak_min=$this->input->post('jarak_min_ibp');
		if ($jum_jarak_min != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$jum_jarak_min],'general_settings',['kode'=>'JMIBP']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	// insentif storing
	public function setting_jarak_min_storing()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'jarak_min_storing'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('JMSTORING');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'].' KM',
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function setting_jarak_min_storing_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$jum_jarak_min=$this->input->post('jarak_min_storing');
		if ($jum_jarak_min != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$jum_jarak_min],'general_settings',['kode'=>'JMSTORING']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	//==== setting hari minimal cuti====//
	public function setting_hariMinCuti()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'hariMinCuti'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('MIN_CUTI');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'].' Hari',
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function setting_hariMinCuti_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$jum_hariMinCuti=$this->input->post('hariMinCuti');
		if ($jum_hariMinCuti != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$jum_hariMinCuti],'general_settings',['kode'=>'MIN_CUTI']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	public function setting_potongan_terlambat()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_one') {
				$id = $this->input->post('id');
				if (empty($id) || $id != 'potongan_terlambat'){
					echo json_encode($this->messages->notValidParam());
				}else{
					$data=$this->model_master->getGeneralSetting('POT_TERLAMBAT');
					if (isset($data)){
						$datax=[
							'value'=>$data['value_int'],
							'plain'=>$data['value_int'].' KM',
						];
						echo json_encode($datax);
					}else{
						echo json_encode($this->messages->customFailure('Data Setting Not Found'));
					}
				}
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function setting_potongan_terlambat_edit(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$jum_potongan_terlambat=$this->input->post('potongan_terlambat');
		if ($jum_potongan_terlambat != "") {
			echo json_encode($this->model_global->updateQuery(['value_int'=>$jum_potongan_terlambat],'general_settings',['kode'=>'POT_TERLAMBAT']));
		}else{
			echo json_encode($this->messages->notValidParam()); 
		}
	}
	// public function setting_rankup_max_work_time()
	// {
	// 	if (!$this->input->is_ajax_request()) 
	// 	redirect('not_found');
	// 	$usage=$this->uri->segment(3);
	// 	if ($usage == null) {
	// 		echo json_encode($this->messages->notValidParam());
	// 	}else{
	// 		if ($usage == 'view_one') {
	// 			$id = $this->input->post('id');
	// 			if (empty($id) || $id != 'rankup_max_work_time'){
	// 				echo json_encode($this->messages->notValidParam());
	// 			}else{
	// 				$data=$this->model_master->getGeneralSetting('MRUP');
	// 				if(isset($data)){
	// 					$masa=null;
	// 					$satuan=null;
	// 					if($data['value_string']){
	// 						$ex_masa=explode(' ',$data['value_string']);
	// 						if(isset($ex_masa)){
	// 							if (is_numeric($ex_masa[0])){
	// 								$masa=$ex_masa[0];
	// 								$satuan=$ex_masa[1];
	// 							}else{
	// 								$masa=$ex_masa[1];
	// 								$satuan=$ex_masa[0];
	// 							}
	// 						}
	// 					}
	// 					$datax=[
	// 						'value'=>$masa,
	// 						'value_secondary'=>$satuan,
	// 						'plain'=>$data['value_string'],
	// 					];
	// 					echo json_encode($datax);
	// 				}else{
	// 					echo json_encode($this->messages->customFailure('Data Setting Not Found'));
	// 				}
	// 			}
	// 		}else{
	// 			echo json_encode($this->messages->notValidParam());
	// 		}
	// 	}
	// }
	// function setting_rankup_max_work_time_edit(){
	// 	if (!$this->input->is_ajax_request()) 
	// 	redirect('not_found');
	// 	$work_time=$this->input->post('masa_kerja');
	// 	$work_time_satuan=$this->input->post('masa_kerja_satuan');
	// 	if ($work_time != "" && $work_time_satuan != "") {
	// 		echo json_encode($this->model_global->updateQuery(['value_string'=>ucwords($work_time.' '.$work_time_satuan)],'general_settings',['kode'=>'MRUP']));
	// 	}else{
	// 		echo json_encode($this->messages->notValidParam()); 
	// 	}
	// }
	//==SETTINGS END==//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA KARYAWAN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Level Struktur
	public function master_level_struktur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListLevelStruktur(false);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_level_struktur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_level_struktur,
						$d->kode_level_struktur,
						$d->nama,
						$d->squence,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_level_struktur');
				$data=$this->model_master->getLevelStruktur($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_level_struktur,
						'kode_level_struktur'=>$d->kode_level_struktur,
						'nama'=>$d->nama,
						'squence'=>$d->squence,
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
				$data = $this->codegenerator->kodeLevelStruktur();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	} 
	function add_level_struktur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_level_struktur'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'squence'=>$this->input->post('squence'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_level_struktur',$this->model_master->checkLevelStrukturCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_level_struktur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_level_struktur'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'squence'=>$this->input->post('squence')
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_level_struktur']) {
				$cek=$this->model_master->checkLevelStrukturCode($data['kode_level_struktur']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_level_struktur',['id_level_struktur'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Level Jabatan
	public function master_level_jabatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListLevelJabatan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_level_jabatan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$sumbobot = ($d->bobot_out)+($d->bobot_sikap)+($d->bobot_tcorp);
					$datax['data'][]=[
						$d->id_level_jabatan,
						$d->kode_level_jabatan,
						$d->nama,
						$d->nama_level_struktur,
						// $sumbobot,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_level_jabatan');
				$data=$this->model_master->getLevelJabatan($id);
				foreach ($data as $d) {
					$sumbobot = ($d->bobot_out)+($d->bobot_sikap)+($d->bobot_tcorp);
					$datax=[
						'id'=>$d->id_level_jabatan,
						'kode_jabatan'=>$d->kode_level_jabatan,
						'nama'=>$d->nama,
						'nama_level_struktur'=>$d->nama_level_struktur,
						'kode_level_struktur'=>$d->kode_level_struktur,
						'bobot_out_val'=>$d->bobot_out,
						'bobot_sikap_val'=>$d->bobot_sikap,
						'bobot_tcorp_val'=>$d->bobot_tcorp,
						'bobot_out'=>($d->bobot_out != null && $d->bobot_out != '0') ? $d->bobot_out.' %' : $this->otherfunctions->getMark(null),
						'bobot_sikap'=>($d->bobot_sikap != null && $d->bobot_sikap != '0') ? $d->bobot_sikap.' %' : $this->otherfunctions->getMark(null),
						'bobot_tcorp'=>($d->bobot_tcorp != null && $d->bobot_tcorp != '0') ? $d->bobot_tcorp.' %' : $this->otherfunctions->getMark(null),
						'sumbobot'=>$sumbobot,
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
				$data = $this->codegenerator->kodeLevelJabatan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_level_jabatan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_level_jabatan'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_level_struktur'=>$this->input->post('struktur'),
				'bobot_out'=>$this->input->post('bobot_out'),
				'bobot_sikap'=>$this->input->post('bobot_sikap'),
				'bobot_tcorp'=>$this->input->post('bobot_tcorp'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_level_jabatan',$this->model_master->checkJabatanCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_level_jabatan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_level_jabatan'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_level_struktur'=>$this->input->post('struktur'),
				'bobot_out'=>$this->input->post('bobot_tg_edit'),
				'bobot_sikap'=>$this->input->post('bobot_sk_edit'),
				'bobot_tcorp'=>$this->input->post('bobot_tc_edit'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_level_jabatan']) {
				$cek=$this->model_master->checkLevelJabatanCode($data['kode_level_jabatan']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_level_jabatan',['id_level_jabatan'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Bidang
	/*Disable if not use
	public function master_bidang()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListBidang();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_bidang,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_bidang,
						$d->kode_bidang,
						$d->nama,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_bidang');
				$data=$this->model_master->getBidang($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_bidang,
						'kode_bidang'=>$d->kode_bidang,
						'nama'=>$d->nama,
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
				$data = $this->codegenerator->kodeBidang();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_bidang(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_bidang'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_bidang',$this->model_master->checkBidangCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_bidang(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_bidang'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_bidang']) {
				$cek=$this->model_master->checkBidangCode($data['kode_bidang']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_bidang',['id_bidang'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}*/

	//--------------------------------------------------------------------------------------------------------------//
	//Master Bagian
	public function master_bagian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListBagian();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_bagian,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_bagian,
						$d->kode_bagian,
						$d->nama,
						(!empty($d->nama_level_struktur)) ? $d->nama_level_struktur:$this->otherfunctions->getMark(null),
						(!empty($d->nama_loker)) ? $d->nama_loker:$this->otherfunctions->getMark(null),
						(!empty($d->nama_atasan)) ? $d->nama_atasan.((!empty($d->nama_loker_atasan)) ? ' ('.$d->nama_loker_atasan.')':''):$this->otherfunctions->getMark(null),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_bagian');
				$data=$this->model_master->getBagian($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_bagian,
						'kode_bagian'=>$d->kode_bagian,
						'kode_level_struktur'=>$d->kode_level_struktur,
						'kode_loker'=>$d->kode_loker,
						'nama'=>$d->nama,
						'atasan'=>$d->atasan,
						'status'=>$d->status,
						'nama_level_struktur'=>(!empty($d->nama_level_struktur)) ? $d->nama_level_struktur:$this->otherfunctions->getMark(null),
						'nama_atasan'=>(!empty($d->nama_atasan)) ? $d->nama_atasan.((!empty($d->nama_loker_atasan)) ? ' ('.$d->nama_loker_atasan.')':''):$this->otherfunctions->getMark(null),
						'nama_loker'=>(!empty($d->nama_loker)) ? $d->nama_loker:$this->otherfunctions->getMark(null),
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
				$data = $this->codegenerator->kodeBagian();
        		echo json_encode($data);
			}elseif ($usage == 'get_select2') {
				$datax = $this->model_master->getFilterBagianSelect2($this->filter,'all');
				// $data = $this->model_master->getListBagian(true);
				// $datax=[];
				// if (isset($data)){
				// 	foreach ($data as $d){
				// 		$datax[$d->kode_bagian]=$d->nama.(($d->nama_loker)?' ('.$d->nama_loker.')':'');
				// 	}
				// }
        		echo json_encode($datax);
			}elseif ($usage == 'get_select2_all') {
				// $datax = $this->model_master->getFilterBagianSelect2($this->filter,'all');
				$data = $this->model_master->getListBagian(true);
				$datax=[];
				if (isset($data)){
					foreach ($data as $d){
						$datax[$d->kode_bagian]=$d->nama.(($d->nama_loker)?' ('.$d->nama_loker.')':'');
					}
				}
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_bagian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$loker=$this->input->post('loker');
			foreach ($loker as $lok){
				$data=array(
					'kode_bagian'=>strtoupper($kode),
					'nama'=>ucwords($this->input->post('nama')),
					'kode_level_struktur'=>strtoupper($this->input->post('level_struktur')),
					'kode_loker'=>strtoupper($lok),
					'atasan'=>strtoupper($this->input->post('atasan_bagian')),
				);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'master_bagian',$this->model_master->checkBagianCode($kode));
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_bagian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'kode_bagian'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_level_struktur'=>strtoupper($this->input->post('level_struktur')),
				'kode_loker'=>strtoupper($this->input->post('loker')),
				'atasan'=>strtoupper($this->input->post('atasan_bagian')),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_bagian']) {
				$cek=$this->model_master->checkBagianCode($data['kode_bagian']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_bagian',['id_bagian'=>$id],$cek);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Jabatan
	public function master_jabatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListJabatan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_jabatan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$bagian=(isset($d->nama_loker)?$d->nama_bagian.' ('.$d->nama_loker.')':$d->nama_bagian);
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$sumbobot = ($d->bobot_out)+($d->bobot_sikap);
					$datax['data'][]=[
						$d->id_jabatan,
						$d->kode_jabatan,
						$d->nama,
						$d->nama_level_jabatan,
						$this->otherfunctions->getTipeJabatan($d->tipe_jabatan),
						(isset($d->nama_bagian)?$bagian:$this->otherfunctions->getMark()),
						(isset($d->nama_loker)?$d->nama_loker:$this->otherfunctions->getMark()),
						$d->nama_atasan,
						(isset($d->nama_loker_atasan)?$d->nama_loker_atasan:$this->otherfunctions->getMark()),
						$this->otherfunctions->getPeriodePenilaian($d->kode_periode),
						(isset($d->nama_user_group)?$d->nama_user_group:$this->otherfunctions->getMark()),
						$sumbobot,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_jabatan');
				$data=$this->model_master->getJabatan($id);
				foreach ($data as $d) {
					$bagian=(isset($d->nama_loker)?$d->nama_bagian.' ('.$d->nama_loker.')':$d->nama_bagian);
					$atasan=(isset($d->nama_loker_atasan)?$d->nama_atasan.' ('.$d->nama_loker_atasan.')':$d->nama_atasan);
					$sumbobot = ($d->bobot_out)+($d->bobot_sikap);
					$setting_terlambat='Ya';
					$setting_terlambat_val=1;
					if ($d->setting){
						$setting=json_decode($d->setting,true);
						if (isset($setting['terlambat'])){
							if ($setting['terlambat'] == 'no'){
								$setting_terlambat='Tidak';
								$setting_terlambat_val=0;
							}
						}
					}
					$setting_dispensasi_jam_masuk='Tidak';
					$setting_dispensasi_jam_masuk_val=0;
					if ($d->setting){
						$setting=json_decode($d->setting,true);
						if (isset($setting['dispensasi_jam_masuk'])){
							if ($setting['dispensasi_jam_masuk'] == 'yes'){
								$setting_dispensasi_jam_masuk='Ya';
								$setting_dispensasi_jam_masuk_val=1;
							}
						}
					}
					$datax=[
						'id'=>$d->id_jabatan,
						'kode_jabatan'=>$d->kode_jabatan,
						'nama'=>$d->nama,
						'nama_level_jabatan'=>$d->nama_level_jabatan,
						'nama_tipe'=>$this->otherfunctions->getTipeJabatan($d->tipe_jabatan),
						'nama_bagian'=>$bagian,
						'atasan'=>$atasan,
						'periode'=>$this->otherfunctions->getPeriodePenilaian($d->kode_periode),
						'kode_level'=>$d->kode_level,
						'tipe_jabatan'=>$d->tipe_jabatan,
						'kode_bagian'=>$d->kode_bagian,
						'kode_periode'=>$d->kode_periode,
						'kode_atasan'=>$d->atasan,
						'user_group'=>$d->nama_user_group,
						'e_user_group'=>$d->id_group_user,
						'bobot_out_val'=>$d->bobot_out,
						'bobot_sikap_val'=>$d->bobot_sikap,
						'bobot_out'=>($d->bobot_out != null && $d->bobot_out != '0') ? $d->bobot_out.' %' : $this->otherfunctions->getMark(null),
						'bobot_sikap'=>($d->bobot_sikap != null && $d->bobot_sikap != '0') ? $d->bobot_sikap.' %' : $this->otherfunctions->getMark(null),
						'sumbobot'=>$sumbobot,
						'setting_terlambat'=>$setting_terlambat,
						'setting_terlambat_val'=>$setting_terlambat_val,
						'setting_dispensasi_jam_masuk'=>$setting_dispensasi_jam_masuk,
						'setting_dispensasi_jam_masuk_val'=>$setting_dispensasi_jam_masuk_val,
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
			}elseif ($usage == 'nama_jabatan') {
				$data = $this->model_master->getJabatanForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'nama_bagian') {
				$data = $this->model_master->getBagianForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'nama_atasan') {
				$data = $this->model_master->getAtasanForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'refresh_bagian') {
				$data = $this->model_master->getRefreshBagian();
        		echo json_encode($data);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeJabatan();
        		echo json_encode($data);
			}elseif ($usage == 'get_select2') {
				$data = $this->model_master->getFilterJabatanSelect2($this->filter);
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_jabatan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$bagian=$this->input->post('bagian');
			foreach ($bagian as $bag) {
				$setting=[];
				$terlambat=$this->input->post('setting_terlambat');
				$dispensasi_jam_masuk=$this->input->post('setting_dispensasi_jam_masuk');
				if ($terlambat == 1){
					$setting['terlambat']='yes';
				}else{
					$setting['terlambat']='no';
				}
				if ($dispensasi_jam_masuk == 1){
					$setting['dispensasi_jam_masuk']='yes';
				}else{
					$setting['dispensasi_jam_masuk']='no';
				}
				$data=[
					// 'kode_jabatan'=>strtoupper($kode),
					'nama'=>ucwords($this->input->post('nama')),
					'kode_level'=>ucwords($this->input->post('level')),
					'tipe_jabatan'=>ucwords($this->input->post('tipe')),
					'atasan'=>strtoupper($this->input->post('atasan')),
					'kode_periode'=>strtoupper($this->input->post('periode')),
					'kode_bagian'=>$bag,
					'id_group_user'=>$this->input->post('user_group'),
					'bobot_out'=>$this->input->post('bobot_out'),
					'bobot_sikap'=>$this->input->post('bobot_sikap'),
					'setting'=>json_encode($setting)
				];
                $data['kode_jabatan'] = $this->codegenerator->kodeJabatan();
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'master_jabatan');
				// $datax = $this->model_global->insertQueryCC($data,'master_jabatan',$this->model_master->checkJabatanCode($kode));
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_jabatan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$setting=[];
			$terlambat=$this->input->post('setting_terlambat');
			$dispensasi_jam_masuk=$this->input->post('setting_dispensasi_jam_masuk');
			if ($terlambat == 1){
				$setting['terlambat']='yes';
			}else{
				$setting['terlambat']='no';
			}
			if ($dispensasi_jam_masuk == 1){
				$setting['dispensasi_jam_masuk']='yes';
			}else{
				$setting['dispensasi_jam_masuk']='no';
			}
			$data=[
				'kode_jabatan'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_level'=>ucwords($this->input->post('level')),
				'tipe_jabatan'=>ucwords($this->input->post('tipe')),
				'atasan'=>strtoupper($this->input->post('atasan')),
				'kode_periode'=>strtoupper($this->input->post('periode')),
				'kode_bagian'=>strtoupper($this->input->post('bagian')),
				'id_group_user'=>$this->input->post('user_group'),
				'bobot_out'=>$this->input->post('bobot_tg_edit'),
				'bobot_sikap'=>$this->input->post('bobot_sk_edit'),
				'setting'=>json_encode($setting)
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_jabatan']) {
				$cek=$this->model_master->checkJabatanCode($data['kode_jabatan']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_jabatan',['id_jabatan'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Lokasi Kerja
	public function master_lokasi()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListLoker();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_loker,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if($d->kode_loker == 'LOK201907300007'){
						unset($properties['delete']);
						$properties['delete']=null;
					}
					$datax['data'][]=[
						$d->id_loker,
						$d->kode_loker,
						$d->nama,
						$d->alamat,
						$d->kota,
						$this->otherfunctions->getYesNo($d->um),
						$properties['tanggal'],
						$properties['status'],
						$properties['info'].$properties['delete'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_loker');
				$data=$this->model_master->getLoker($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_loker,
						'kode_loker'=>$d->kode_loker,
						'nama'=>$d->nama,
						'alamat'=>$d->alamat,
						'kota'=>$d->kota,
						'telp'=>$d->telp,
						'status'=>$d->status,
						'um'=>$this->otherfunctions->getYesNo($d->um),
						'e_um'=>$d->um,
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
				$data = $this->codegenerator->kodeLoker();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_lokasi(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_loker'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'alamat'=>ucwords($this->input->post('alamat')),
				'kota'=>ucwords($this->input->post('kota')),
				'telp'=>ucwords($this->input->post('telp')),
				'um'=>$this->input->post('uang_makan'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_loker',$this->model_master->checkLokerCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_lokasi(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_loker'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'alamat'=>ucwords($this->input->post('alamat')),
				'kota'=>ucwords($this->input->post('kota')),
				'telp'=>ucwords($this->input->post('telp')),
				'um'=>$this->input->post('uang_makan'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_loker']) {
				$cek=$this->model_master->checkLokerCode($data['kode_loker']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_loker',['id_loker'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Status Karyawan
	public function master_status_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListStatusKaryawan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_status_karyawan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_status_karyawan,
						$d->kode_status,
						$d->nama,
						$d->masa_habis,
						$d->gaji_pokok.' %',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_status_karyawan');
				$data=$this->model_master->getStatusKaryawan($id);
				foreach ($data as $d) {
					//format masa habis
					$ex_masa=explode(' ',$d->masa_habis);
					if(isset($ex_masa)){
						if (is_numeric($ex_masa[0])){
							$masa=$ex_masa[0];
							$satuan=$ex_masa[1];
						}else{
							$masa=$ex_masa[1];
							$satuan=$ex_masa[0];
						}
					}else{
						$masa=null;
						$satuan=null;
					}
					$datax=[
						'id'=>$d->id_status_karyawan,
						'kode_status'=>$d->kode_status,
						'nama'=>$d->nama,
						'masa_habis'=>$d->masa_habis,
						'gaji_pokok'=>$d->gaji_pokok.' %',
						'gaji_pokok_in'=>$d->gaji_pokok,
						'masa'=>$masa,
						'satuan'=>$satuan,
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
				$data = $this->codegenerator->kodeStatusKaryawan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_status_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			if ($this->input->post('masahbs') == "Umur") {
				$masa=$this->input->post('masahbs').' '.$this->input->post('masa');
			}else{
				$masa=$this->input->post('masa').' '.$this->input->post('masahbs');
			}
			$data=[
				'kode_status'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('status_pegawai')),
				'masa_habis'=>ucwords($masa),
				'gaji_pokok'=>ucwords($this->input->post('gaji')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_status_karyawan',$this->model_master->checkStatusKaryawanCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_status_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			if ($this->input->post('masahbs_edit') == "Umur") {
				$masa=$this->input->post('masahbs_edit').' '.$this->input->post('masa');
			}else{
				$masa=$this->input->post('masa').' '.$this->input->post('masahbs_edit');
			}
			$data=[
				'kode_status'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'masa_habis'=>ucwords($masa),
				'gaji_pokok'=>ucwords($this->input->post('gaji')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_status']) {
				$cek=$this->model_master->checkBidangCode($data['kode_status']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_status_karyawan',['id_status_karyawan'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Grade
//-----------------------------------------------------------------------------------------------------------------------------------------//
//--INDUK GRADE--//
	public function master_induk_grade()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListIndukGrade();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_induk_grade,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_induk_grade,
						$d->kode_induk_grade,
						$d->nama,
						(!empty($d->jum)?$d->jum .' Data Grade':$this->otherfunctions->getMark($d->jum)),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->kode_induk_grade),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_induk_grade');
				$data=$this->model_master->getIndukGrade($id);
				foreach ($data as $d) {
  					$turunan_grade=$this->model_master->getListGradeKode($d->kode_induk_grade);
          			if(count($turunan_grade) != null){
						$tabel='';
						$tabel.='<hr><h4 align="center"><b>Data Turunan Grade '.$d->nama.'</b></h4>
							<div style="max-height: 400px; overflow: auto;">
	          				<table class="table table-bordered table-striped data-table">
	          					<thead>
	          						<tr class="bg-blue">
	          							<th>No.</th>
	          							<th>Nama Grade</th>
	          							<th>Lokasi Grade</th>
	          							<th>Gaji Pokok</th>
	          							<th>Dokumen</th>
	          						</tr>
	          					</thead>
	          					<tbody>';
	          						$no=1;
	          						foreach ($turunan_grade as $tg) {
	          							$tabel.='<tr>
	          							<td>'.$no.'</td>
	          							<td>'.$tg->nama.'</td>
	          							<td>'.$tg->nama_loker.'</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($tg->gapok).'</td>
	          							<td>'.$tg->nama_dokumen.'</td>
	          						</tr>';
	          						$no++;
	          					}
		          				$tabel.='</tbody>
		          			</table></div>';
		          	}else{
		          		$tabel=null;
		          	}
					$datax=[
						'id'=>$d->id_induk_grade,
						'kode_induk_grade'=>$d->kode_induk_grade,
						'nama'=>$d->nama,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'tabel'=>$tabel,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeIndukGrade();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_induk_grade(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=array(
				'kode_induk_grade'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_induk_grade',$this->model_master->checkGradeCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_induk_grade(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'kode_induk_grade'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_induk_grade']) {
				$cek=$this->model_master->checkGradeCode($data['kode_induk_grade']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_induk_grade',['id_induk_grade'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--GRADE--//
	public function master_grade()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode_induk=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListGradeKode($kode_induk);//getListGrade();
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
						$d->kode_grade,
						$d->nama_induk_grade,
						$d->nama,
						(!empty($d->nama_loker)) ? $d->nama_loker:$this->otherfunctions->getMark($d->nama_loker),
						$this->formatter->getFormatMoneyUser($d->gapok),
						$this->formatter->getFormatMoneyUser($d->um),
						// (!empty($d->tahun)) ? $d->tahun:$this->otherfunctions->getMark($d->tahun),
						(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_grade');
				$data=$this->model_master->getGrade($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_grade,
						'kode_grade'=>$d->kode_grade,
						'nama'=>$d->nama,
						'gapok'=>$this->formatter->getFormatMoneyUser($d->gapok),
						'um'=>$this->formatter->getFormatMoneyUser($d->um),
						'kode_dokumen'=>$d->kode_dokumen,
						'nama_dokumen'=>(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
						'tahun'=>(!empty($d->tahun)) ? $d->tahun:$this->otherfunctions->getMark($d->tahun),
						'e_tahun'=>$d->tahun,
						'kode_loker'=>$d->kode_loker,
						'nama_loker'=>(!empty($d->nama_loker)) ? $d->nama_loker:$this->otherfunctions->getMark($d->nama_loker),
						'kode_induk_grade'=>$d->kode_induk_grade,
						'nama_induk_grade'=>(!empty($d->nama_induk_grade)) ? $d->nama_induk_grade:$this->otherfunctions->getMark($d->nama_induk_grade),
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
				$data = $this->codegenerator->kodeGrade();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_grade(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		$induk=$this->input->post('induk_grade');
		if ($kode != "" && $induk != "") {
			$data=array(
				'kode_grade'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_induk_grade'=>ucwords($this->input->post('induk_grade')),
				'gapok'=>$this->formatter->getFormatMoneyDb($this->input->post('gapok')),
				'um'=>$this->formatter->getFormatMoneyDb($this->input->post('um')),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
				'kode_loker'=>ucwords($this->input->post('loker')),
				'tahun'=>NULL,
				// 'tahun'=>$this->input->post('tahun'),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_grade',$this->model_master->checkGradeCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_grade(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "" && $this->input->post('induk_grade') != "") {
			$data=array(
				'kode_grade'=>strtoupper($this->input->post('kode')),
				'kode_induk_grade'=>ucwords($this->input->post('induk_grade')),
				'nama'=>ucwords($this->input->post('nama')),
				'gapok'=>$this->formatter->getFormatMoneyDb($this->input->post('gapok')),
				'um'=>$this->formatter->getFormatMoneyDb($this->input->post('um')),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
				'kode_loker'=>ucwords($this->input->post('loker')),
				'tahun'=>$this->input->post('tahun'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_grade']) {
				$cek=$this->model_master->checkGradeCode($data['kode_grade']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_grade',['id_grade'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function import_master_grade()
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
				'table'=>'master_grade',
				'column_code'=>'nama',
				'usage'=>'import_master_grade',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'column'=>[
					1=>'kode_induk_grade',2=>'kode_grade',3=>'nama',4=>'gapok',5=>'kode_dokumen',6=>'kode_loker',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Sistem Penggajian
	public function master_sistem_penggajian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListSistemPenggajian();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_master_penggajian,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					unset($var['access']['l_ac']['del']);
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_master_penggajian,
						$d->kode_master_penggajian,
						$d->nama,
						$this->formatter->getFormatMoneyUser($d->upah),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_master_penggajian');
				$data=$this->model_master->getSistemPenggajian($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_master_penggajian,
						'kode_master_penggajian'=>$d->kode_master_penggajian,
						'nama'=>$d->nama,
						'upah'=>$this->formatter->getFormatMoneyUser($d->upah),
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
				$data = $this->codegenerator->kodeSistemPenggajian();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_sistem_penggajian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_master_penggajian'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'upah'=>$this->formatter->getFormatMoneyDb($this->input->post('upah')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_sistem_penggajian',$this->model_master->checkSistemPenggajianCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_sistem_penggajian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_master_penggajian'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'upah'=>$this->formatter->getFormatMoneyDb($this->input->post('upah')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_master_penggajian']) {
				$cek=$this->model_master->checkSistemPenggajianCode($data['kode_master_penggajian']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_sistem_penggajian',['id_master_penggajian'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Mutasi Promosi Demosi
	public function master_mutasi_karyawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListMutasiKaryawan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_m_mutasi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_m_mutasi,
						$d->kode_mutasi,
						$d->nama,
						(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_m_mutasi');
				$data=$this->model_master->getMutasiKaryawan($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_m_mutasi,
						'kode_mutasi'=>$d->kode_mutasi,
						'kode_dokumen'=>$d->kode_dokumen,
						'nama'=>$d->nama,
						'status'=>$d->status,
						'nama_dokumen'=>(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
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
				$data = $this->codegenerator->kodeMutasiKaryawan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_mutasi_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_mutasi'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_dokumen'=>strtoupper($this->input->post('dokumen')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_mutasi',$this->model_master->checkMutasiKaryawanCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_mutasi_karyawan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_mutasi'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_dokumen'=>strtoupper($this->input->post('dokumen')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_mutasi']) {
				$cek=$this->model_master->checkMutasiKaryawanCode($data['kode_mutasi']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_mutasi',['id_m_mutasi'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Daftar RS
	public function master_daftar_rs()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListDaftarRS();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_master_rs,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_master_rs,
						$d->kode_master_rs,
						$d->nama,
						$d->alamat,
						$d->telp,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_master_rs');
				$data=$this->model_master->getDaftarRS($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_master_rs,
						'kode_master_rs'=>$d->kode_master_rs,
						'nama'=>$d->nama,
						'alamat'=>$d->alamat,
						'telp'=>$d->telp,
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
				$data = $this->codegenerator->kodeDaftarRS();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_daftar_rs(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_master_rs'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'alamat'=>ucwords($this->input->post('alamat')),
				'telp'=>ucwords($this->input->post('telp')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_daftar_rs',$this->model_master->checkDaftarRSCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_daftar_rs(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_master_rs'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'alamat'=>ucwords($this->input->post('alamat')),
				'telp'=>ucwords($this->input->post('telp'))
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_master_rs']) {
				$cek=$this->model_master->checkDaftarRSCode($data['kode_master_rs']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_daftar_rs',['id_master_rs'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Kategori Kecelakaan
	public function master_kategori_kecelakaan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKategoriKecelakaan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kategori_kecelakaan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])&& $d->kode_kategori_kecelakaan!='KK_DLM') ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_kategori_kecelakaan.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_kategori_kecelakaan.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$d->id_kategori_kecelakaan,
						$d->kode_kategori_kecelakaan,
						$d->nama,
						(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$delete.$info,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_kategori_kecelakaan');
				$data=$this->model_master->getKategoriKecelakaan($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_kategori_kecelakaan,
						'kode_kategori_kecelakaan'=>$d->kode_kategori_kecelakaan,
						'kode_dokumen'=>$d->kode_dokumen,
						'nama'=>$d->nama,
						'nama_dokumen'=>(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
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
				$data = $this->codegenerator->kodeKategoriKecelakaan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_kategori_kecelakaan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_kategori_kecelakaan'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_kategori_kecelakaan',$this->model_master->checkKategoriKecelakaanCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_kategori_kecelakaan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_kategori_kecelakaan'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_kategori_kecelakaan']) {
				$cek=$this->model_master->checkKategoriKecelakaanCode($data['kode_kategori_kecelakaan']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_kategori_kecelakaan',['id_kategori_kecelakaan'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Surat Peringatan
	public function master_surat_peringatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListSuratPeringatan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_sp,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_sp,
						$d->kode_sp,
						$d->nama,
						$d->berlaku,
						(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_sp');
				$data=$this->model_master->getSuratPeringatan($id);
				foreach ($data as $d) {
					//format masa habis
					$masa=null;
					$satuan=null;
					if($d->berlaku){
						$ex_masa=explode(' ',$d->berlaku);
						if(isset($ex_masa)){
							if (is_numeric($ex_masa[0])){
								$masa=$ex_masa[0];
								$satuan=$ex_masa[1];
							}else{
								$masa=$ex_masa[1];
								$satuan=$ex_masa[0];
							}
						}
					}
					$datax=[
						'id'=>$d->id_sp,
						'kode_sp'=>$d->kode_sp,
						'nama'=>$d->nama,
						'kode_dokumen'=>$d->kode_dokumen,
						'berlaku'=>$d->berlaku,
						'nama_dokumen'=>(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
						'masa'=>$masa,
						'satuan'=>$satuan,
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
				$data = $this->codegenerator->kodeSuratPeringatan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_surat_peringatan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$masa=$this->input->post('masa').' '.$this->input->post('masahbs');
			$data=[
				'kode_sp'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'berlaku'=>ucwords($masa),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_surat_peringatan',$this->model_master->checkStatusKaryawanCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_surat_peringatan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$masa=$this->input->post('masa').' '.$this->input->post('masahbs_edit');
			$data=[
				'kode_sp'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'berlaku'=>ucwords($masa),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_sp']) {
				$cek=$this->model_master->checkSuratPeringatanCode($data['kode_sp']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_surat_peringatan',['id_sp'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Surat Perjanjian
	public function master_surat_perjanjian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListSuratPerjanjian();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_perjanjian,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])&&$d->kode_perjanjian!='PTSP' && $d->kode_perjanjian!='RSGN') ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_perjanjian.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_perjanjian.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$d->id_perjanjian,
						$d->kode_perjanjian,
						$d->nama,
						$d->berlaku,
						(!empty($d->nama_status_karyawan)) ? $d->nama_status_karyawan:$this->otherfunctions->getMark($d->nama_status_karyawan),
						(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
						$d->notif_exp,
						$properties['tanggal'],
						$properties['status'],
						$info.$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_perjanjian');
				$data=$this->model_master->getSuratPerjanjian($id);
				foreach ($data as $d) {
					//format masa habis
					$ex_masa=explode(' ',$d->berlaku);
					if(isset($ex_masa)){
						if (is_numeric($ex_masa[0])){
							$masa=$ex_masa[0];
							$satuan=$ex_masa[1];
						}else{
							$masa=$ex_masa[1];
							$satuan=$ex_masa[0];
						}
					}else{
						$masa=null;
						$satuan=null;
					}
					$datax=[
						'id'=>$d->id_perjanjian,
						'kode_perjanjian'=>$d->kode_perjanjian,
						'nama'=>$d->nama,
						'kode_status_karyawan'=>$d->kode_status,
						'kode_dokumen'=>$d->kode_dokumen,
						'berlaku'=>$d->berlaku,
						'notif_exp'=>$d->notif_exp,
						'nama_status_karyawan'=>(!empty($d->nama_status_karyawan)) ? $d->nama_status_karyawan:$this->otherfunctions->getMark($d->nama_status_karyawan),
						'nama_dokumen'=>(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
						'masa'=>$masa,
						'satuan'=>$satuan,
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
				$data = $this->codegenerator->kodeSuratPerjanjian();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_surat_perjanjian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
				$masa=$this->input->post('masa').' '.$this->input->post('masahbs');
			$data=[
				'kode_perjanjian'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'berlaku'=>ucwords($masa),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
				'kode_status'=>ucwords($this->input->post('status_karyawan')),
				'notif_exp'=>ucwords($this->input->post('notif')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_surat_perjanjian',$this->model_master->checkSuratPerjanjianCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_surat_perjanjian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$masa=$this->input->post('masa').' '.$this->input->post('masahbs_edit');
			$data=[
				'kode_perjanjian'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'berlaku'=>ucwords($masa),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
				'kode_status'=>ucwords($this->input->post('status_karyawan')),
				'notif_exp'=>ucwords($this->input->post('notif')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_perjanjian']) {
				$cek=$this->model_master->checkSuratPerjanjianCode($data['kode_perjanjian']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_surat_perjanjian',['id_perjanjian'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Kelompok Shift
	public function master_kelompok_shift()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKelompokShift();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_shift,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_shift,
						$d->kode_shift,
						$d->nama,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_shift');
				$data=$this->model_master->getKelompokShift($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_shift,
						'kode_shift'=>$d->kode_shift,
						'nama'=>$d->nama,
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
				$data = $this->codegenerator->kodeKelompokShift();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_kelompok_shift(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_shift'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_kelompok_shift',$this->model_master->checkKelompokShiftCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_kelompok_shift(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_shift'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_shift']) {
				$cek=$this->model_master->checkKelompokShiftCode($data['kode_shift']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_kelompok_shift',['id_shift'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Bank
	public function master_bank()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListBank();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_bank,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_bank,
						$d->kode,
						$d->nama,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_bank');
				$data=$this->model_master->getBank($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_bank,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
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
				$data = $this->codegenerator->kodeBank();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_bank(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_bank',$this->model_master->checkKelompokShiftCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_bank(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode']) {
				$cek=$this->model_master->checkKelompokShiftCode($data['kode']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_bank',['id_bank'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Alasan Keluar
	public function master_alasan_keluar()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListAlasanKeluar();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_alasan_keluar,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_alasan_keluar,
						$d->kode_alasan_keluar,
						$d->nama,
						(!empty($d->nama_dokumen_keterangan)) ? $d->nama_dokumen_keterangan:$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_alasan_keluar');
				$data=$this->model_master->getAlasanKeluar($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_alasan_keluar,
						'kode_alasan_keluar'=>$d->kode_alasan_keluar,
						'kode_dokumen'=>$d->kode_dokumen,
						'kode_dokumen_keterangan'=>$d->dokumen_keterangan,
						'nama'=>$d->nama,
						'nama_dokumen'=>(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
						'nama_dokumen_keterangan'=>(!empty($d->nama_dokumen_keterangan)) ? $d->nama_dokumen_keterangan:$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeAlasanKeluar();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_alasan_keluar(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_alasan_keluar'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
				'dokumen_keterangan'=>ucwords($this->input->post('dokumen_keterangan')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_alasan_keluar',$this->model_master->checkAlasanKeluarCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_alasan_keluar(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_alasan_keluar'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'kode_dokumen'=>ucwords($this->input->post('dokumen')),
				'dokumen_keterangan'=>ucwords($this->input->post('dokumen_keterangan')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_alasan_keluar']) {
				$cek=$this->model_master->checkAlasanKeluarCode($data['kode_alasan_keluar']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_alasan_keluar',['id_alasan_keluar'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//===MASTER DATA KARYAWAN END===//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA ABSENSI BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Ijin Cuti
	public function master_izin()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListMasterIzin();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_master_izin,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_master_izin,
						$d->kode_master_izin,
						$d->nama,
						$this->otherfunctions->getIzinCuti($d->jenis),
						$d->maksimal,
						//$d->potong_upah,
						$this->otherfunctions->getYesNo($d->potong_upah),
						$this->otherfunctions->getYesNo($d->ikut_pa),
						// $d->potongan_kali,
						(!empty($d->nama_master_penggajian)) ? $d->nama_master_penggajian:$this->otherfunctions->getMark($d->nama_master_penggajian),
						$this->otherfunctions->getYesNo($d->potong_cuti),
						(empty($d->potongan_gaji) || $d->potongan_gaji ==0)?'<label class="label label-danger label-md">Tidak dipotong</label>':$d->potongan_gaji.'%',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_master_izin');
				$data=$this->model_master->getMasterIzin($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_master_izin,
						'kode_master_izin'=>$d->kode_master_izin,
						'nama'=>$d->nama,
						'kode_master_penggajian'=>$d->kode_master_penggajian,
						'nama_sistem_penggajian'=>(!empty($d->nama_sistem_penggajian)) ? $d->nama_sistem_penggajian:$this->otherfunctions->getMark($d->nama_sistem_penggajian),
						'maksimal'=>$d->maksimal.' Hari',
						'maksimal_e'=>$d->maksimal,
						'potong_upah'=>$this->otherfunctions->getYesNo($d->potong_upah),
						'jenis'=>$this->otherfunctions->getIzinCuti($d->jenis),
						'potong_cuti'=>$this->otherfunctions->getYesNo($d->potong_cuti),
						'ikut_pa_view'=>$this->otherfunctions->getYesNo($d->ikut_pa),
						'ikut_pa'=>$d->ikut_pa,
						'kode_potong_upah'=>$d->potong_upah,
						'e_potong_cuti'=>$d->potong_cuti,
						'kode_jenis'=>$d->jenis,
						'potongan_kali'=>$d->potongan_kali,
						'potongan'=>$d->potongan_gaji,
						'potongan_view'=>(empty($d->potongan_gaji) || $d->potongan_gaji ==0)?'<label class="label label-danger label-md">Tidak dipotong</label>':$d->potongan_gaji.'%',
						'kode_dokumen'=>$d->kode_dokumen,
						'nama_dokumen'=>(!empty($d->nama_dokumen)) ? $d->nama_dokumen:$this->otherfunctions->getMark($d->nama_dokumen),
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
				$data = $this->codegenerator->kodeMasterIzin();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_master_izin(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_master_izin'		=>strtoupper($kode),
				'nama'					=>ucwords($this->input->post('nama')),
				'maksimal'				=>ucwords($this->input->post('maksimal')),
				'potong_upah'			=>ucwords($this->input->post('potong_upah')),
				'ikut_pa'				=>ucwords($this->input->post('potong_pa')),
				'potongan_kali'			=>ucwords($this->input->post('potongan_kali')),
				'kode_master_penggajian'=>$this->input->post('satuan'),
				'jenis'					=>ucwords($this->input->post('jenis')),
				'kode_dokumen'			=>strtoupper($this->input->post('dokumen')),
				'potong_cuti'			=>$this->input->post('potong_cuti'),
				'potongan_gaji'			=>$this->input->post('potongan'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_izin',$this->model_master->checkMasterIzinCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_master_izin(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_master_izin'      =>strtoupper($this->input->post('kode')),
				'nama'                  =>ucwords($this->input->post('nama')),
				'maksimal'              =>ucwords($this->input->post('maksimal')),
				'potong_upah'           =>strtoupper($this->input->post('potong_upah')),
				'ikut_pa'               =>ucwords($this->input->post('potong_pa')),
				'potongan_kali'         =>ucwords($this->input->post('potongan_kali')),
				'kode_master_penggajian'=>$this->input->post('satuan'),
				'jenis'                 =>strtoupper($this->input->post('jenis')),
				'kode_dokumen'          =>strtoupper($this->input->post('dokumen')),
				'potong_cuti'           =>$this->input->post('potong_cuti'),
				'potongan_gaji'			=>$this->input->post('potongan'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_master_izin']) {
				$cek=$this->model_master->checkMasterIzinCode($data['kode_master_izin']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_izin',['id_master_izin'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Shift
	public function master_shift()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListMasterShift();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_master_shift,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])&&$d->kode_master_shift!='CSTM'&&$d->kode_master_shift!='SSP'&&$d->kode_master_shift!='SSS'&&$d->kode_master_shift!='SSM'&&$d->kode_master_shift!='SSL') ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_master_shift.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_master_shift.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$hari_v =$this->formatter->getFormatManyDays($d->hari);
					$datax['data'][]=[
						$d->id_master_shift,
						$d->kode_master_shift,
						$d->nama,
						$this->formatter->timeFormatUser($d->jam_mulai),
						$this->formatter->timeFormatUser($d->jam_selesai),
						(!empty($hari_v)) ? $hari_v:$this->otherfunctions->getMark(),
						$this->otherfunctions->getYshift($d->shift),
						(empty($d->potongan) || $d->potongan ==0)?'<label class="label label-danger label-md">Tidak dipotong</label>':$d->potongan.'%',
						$properties['tanggal'],
						$properties['status'],
						$info.$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_master_shift');
				$data=$this->model_master->getMasterShift($id);
				foreach ($data as $d) {
					$hari_v =$this->formatter->getFormatManyDays($d->hari);
					$hari_e=$this->otherfunctions->getParseOneLevelVar($d->hari);
					$datax=[
						'id'=>$d->id_master_shift,
						'kode_master_shift'=>$d->kode_master_shift,
						'nama'=>$d->nama,
						'jam_mulai'=>$this->formatter->timeFormatUser($d->jam_mulai),
						'jam_selesai'=>$this->formatter->timeFormatUser($d->jam_selesai),
						'jam_istirahat_mulai'=>$this->formatter->timeFormatUser($d->istirahat_mulai),
						'jam_istirahat_selesai'=>$this->formatter->timeFormatUser($d->istirahat_selesai),
						'shift'=>$this->otherfunctions->getYshift($d->shift),
						'kode_shift'=>$d->shift,
						'hari'=>(!empty($hari_v)) ? $hari_v:$this->otherfunctions->getMark(),
						'hari_e'=>$hari_e,
						'potongan'=>$d->potongan,
						'potongan_view'=>(empty($d->potongan) || $d->potongan ==0)?'<label class="label label-danger label-md">Tidak dipotong</label>':$d->potongan.'%',
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
				$data = $this->codegenerator->kodeMasterShift();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_master_shift(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_master_shift'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'jam_mulai'=>$this->formatter->timeFormatDb($this->input->post('jam_mulai')),
				'jam_selesai'=>$this->formatter->timeFormatDb($this->input->post('jam_selesai')),
				'istirahat_mulai'=>$this->formatter->timeFormatDb($this->input->post('jam_istirahat_mulai')),
				'istirahat_selesai'=>$this->formatter->timeFormatDb($this->input->post('jam_istirahat_selesai')),
				'shift'=>ucwords($this->input->post('shift')),
				'hari'=>implode(';', $this->input->post('hari')),
				'potongan'=>$this->input->post('potongan'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_shift',$this->model_master->checkMasterShiftCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_master_shift(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_master_shift'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'jam_mulai'=>$this->formatter->timeFormatDb($this->input->post('jam_mulai')),
				'jam_selesai'=>$this->formatter->timeFormatDb($this->input->post('jam_selesai')),
				'istirahat_mulai'=>$this->formatter->timeFormatDb($this->input->post('jam_istirahat_mulai')),
				'istirahat_selesai'=>$this->formatter->timeFormatDb($this->input->post('jam_istirahat_selesai')),
				'shift'=>strtoupper($this->input->post('shift')),
				'hari'=>implode(';', $this->input->post('hari')),
				'potongan'=>$this->input->post('potongan'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_master_shift']) {
				$cek=$this->model_master->checkMasterShiftCode($data['kode_master_shift']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_shift',['id_master_shift'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Hari Libur
	public function master_hari_libur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$tahun = $this->input->post('tahun');
				// echo $tahun;
				// $where = 'a.YEAR(tanggal)='.$tahun;
				$data=$this->model_master->getHariLiburTanggal($tahun);
				// $data=$this->model_master->getListHariLibur();
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_hari_libur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_hari_libur,
						$d->kode_hari_libur,
						$this->formatter->getDateMonthFormatUser($d->tanggal),
						$d->nama,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_hari_libur');
				$data=$this->model_master->getHariLibur($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_hari_libur,
						'kode_hari_libur'=>$d->kode_hari_libur,
						'nama'=>$d->nama,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tanggal),
						'tanggal_e'=>$this->formatter->getDateFormatUser($d->tanggal),
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
				$data = $this->codegenerator->kodeHariLibur();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_hari_libur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=array(
				'kode_hari_libur'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'tanggal'=>$this->formatter->getDateFormatDb($this->input->post('tanggal')),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_hari_libur',$this->model_master->checkHariLiburCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_hari_libur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'kode_hari_libur'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'tanggal'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_e')),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_hari_libur']) {
				$cek=$this->model_master->checkHariLiburCode($data['kode_hari_libur']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_hari_libur',['id_hari_libur'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Cuti Bersama
	public function master_cuti_bersama()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$tahun = $this->input->post('tahun');
				$data=$this->model_master->getCutiBersamaTanggal($tahun, false);
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
						$d->kode,
						$this->formatter->getDateMonthFormatUser($d->tanggal),
						$d->nama,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_master->getCutiBersama($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						'tanggal'=>$this->formatter->getDateMonthFormatUser($d->tanggal),
						'tanggal_e'=>$this->formatter->getDateFormatUser($d->tanggal),
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
			}elseif ($usage == 'view_jumlah') {
				$tahun = $this->input->post('tahun');
				$data=$this->model_master->getCutiBersamaTanggal($tahun);
				$dataAll=$this->model_master->getCutiBersamaTanggal($tahun, false);
				$jumlah = null;
				$updateJan = 0;
				$updateJul = 0;
				if(!empty($data)){
					$jumlah = count($data);
					$historyJan=$this->model_master->getHistoryResetCuti(['tahun'=>$tahun, 'flag'=>'SYNC JAN'], true);
					// $historyJan=$this->model_master->getHistoryResetCuti(['tahun'=>$tahun, 'flag'=>'SYNC JAN', 'flag'=>'UPDATE'], true, 'datetime ASC');
					$historyJul=$this->model_master->getHistoryResetCuti(['tahun'=>$tahun, 'flag'=>'SYNC JUL'], true);
					foreach ($dataAll as $d) {
						if(!empty($historyJan['datetime'])){
							if(strtotime($d->update_date) >= strtotime($historyJan['datetime'])){
								$updateJan+=1;
							}
						}
						if(!empty($historyJul['datetime'])){
							if(strtotime($d->update_date) >= strtotime($historyJul['datetime'])){
								$updateJul+=1;
							}
						}
					}
				}
				$msg = null;
				if(!empty($jumlah)){
					$msg = 'Jumlah Cuti Bersama Tahun <b style="color:green;">'.$tahun.'</b> adalah <b style="color:green;">'.$jumlah.' Hari</b>, <br> Cuti Tahunan Karyawan adalah 12 Hari, dan<br>Karyawan masih dapat mengajuan Cuti Pribadi <b style="color:red;">'.(12-$jumlah).' Hari</b>';
				}
				$ketUpdate = null;
				if($updateJan > 0 || $updateJul > 0){
					$ketUpdate = 'Telah terjadi perubahan data pada <b style="color:green;">Master Cuti Bersama</b>, silahkan Sinkron data dahulu';
				}
				$datax = [
					'keterangan'=>$msg,
					'updateJan'=>$updateJan,
					'updateJul'=>$updateJul,
					'keterangan_update'=>$ketUpdate,
				];
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeCutiBersama();
        		echo json_encode($data);
			}elseif ($usage == 'syncronize') {
				$tahun = $this->input->post('tahun');
				$datetime = $this->date;
				// $datetime = '2023-09-23 16:58:26';
				$date = $this->formatter->getDayMonthYearsHourMinute($datetime);
				if($date['bulan'] <= 6 && $date['bulan'] >= 1){
					$emp = $this->model_karyawan->getEmployeeWhere(['emp.status_emp'=>'1']);
					$CutiBersama=$this->model_master->getCutiBersamaTanggal($tahun);
					$jCB = 0;
					if(!empty($CutiBersama)){
						$jCB = count($CutiBersama);
					}
					$mulai = $tahun.'-01-01';
					$selesai = $tahun.'-06-30';
					foreach ($emp as $e) {
						$dataIzin = $this->model_karyawan->getListIzinCutiWhere(['a.id_karyawan'=>$e->id_karyawan, 'a.jenis'=>'MIC201907160001', 'a.validasi'=>'1'], $mulai, $selesai);
						$countCuti = 0;
						if(!empty($dataIzin)){
							foreach ($dataIzin as $dd) {
								$jum_cuti=$this->otherfunctions->hitungHari($dd->tgl_mulai, $dd->tgl_selesai);
								$countCuti += $jum_cuti;
							}
						}
						$sisa_cuti = (12-$jCB+$e->sc_old-$countCuti);
						$datax = $this->model_global->updateQuery(['sisa_cuti' => $sisa_cuti],'karyawan',['id_karyawan'=>$e->id_karyawan]);
					}
					echo json_encode($datax);
				}elseif($date['bulan'] <= 12 && $date['bulan'] >= 7){
					$emp = $this->model_karyawan->getEmployeeWhere(['emp.status_emp'=>'1']);
					$CutiBersama=$this->model_master->getCutiBersamaTanggal($tahun);
					$jCB = 0;
					if(!empty($CutiBersama)){
						$jCB = count($CutiBersama);
					}
					$mulai = $tahun.'-07-01';
					$selesai = $tahun.'-12-31';
					foreach ($emp as $e) {
						$dataIzin = $this->model_karyawan->getListIzinCutiWhere(['a.id_karyawan'=>$e->id_karyawan, 'a.jenis'=>'MIC201907160001', 'a.validasi'=>'1'], $mulai, $selesai);
						$countCuti = 0;
						if(!empty($dataIzin)){
							foreach ($dataIzin as $dd) {
								$jum_cuti=$this->otherfunctions->hitungHari($dd->tgl_mulai, $dd->tgl_selesai);
								$countCuti += $jum_cuti;
							}
						}
						$sisa_cuti = (12-$jCB-$countCuti);
						$datax = $this->model_global->updateQuery(['sisa_cuti' => $sisa_cuti],'karyawan',['id_karyawan'=>$e->id_karyawan]);
					}
					echo json_encode($datax);
				}else{
					$datax = $this->messages->notValidParam();
					echo json_encode($datax);
				}
			}else{
				$datax = $this->messages->notValidParam();
				echo json_encode($datax);
			}
		}
	}
	function add_cuti_bersama(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=array(
				'kode'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'tanggal'=>$this->formatter->getDateFormatDb($this->input->post('tanggal')),
			);
			$tahun=explode('-', $data['tanggal']);
			$this->otherfunctions->insertToHistoryResetCuti('ADD', 'Data Berhasil Di Tambah', $this->date, $tahun[0]);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_cuti_bersama',$this->model_master->checkHariLiburCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_cuti_bersama(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'kode'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'tanggal'=>$this->formatter->getDateFormatDb($this->input->post('tanggal_e')),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode']) {
				$cek=$this->model_master->checkHariLiburCode($data['kode']);
			}else{
				$cek=false;
			}
			$tahun=explode('-', $data['tanggal']);
			$this->otherfunctions->insertToHistoryResetCuti('EDIT', 'Data Berhasil Di Edit', $this->date, $tahun[0]);
			$datax = $this->model_global->updateQueryCC($data,'master_cuti_bersama',['id'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Kode Akun
	public function master_kode_akun()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getMasterKodeAkun();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kode_akun,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_kode_akun,
						$d->kode_akun,
						$d->nama,
						$d->keterangan,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_kode_akun');
				$data=$this->model_master->getMasterKodeAkun($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_kode_akun,
						'kode_akun'=>$d->kode_akun,
						'nama'=>$d->nama,
						'keterangan'=>$d->keterangan,
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
	function add_master_kode_akun(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=array(
				'kode_akun'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'keterangan'=>$this->input->post('keterangan'),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_pd_kode_akun',$this->model_master->checkHariLiburCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_master_kode_akun(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'kode_akun'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'keterangan'=>$this->input->post('keterangan'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_akun']) {
				$cek=$this->model_master->checkHariLiburCode($data['kode_akun']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_pd_kode_akun',['id_kode_akun'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Kendaraan Perjalanan Dinas
	public function master_pd_kendaraan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKendaraanDinas();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd_kendaraan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])&&$d->kode!='KPD0001') ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_pd_kendaraan.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_pd_kendaraan.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$d->id_pd_kendaraan,
						$d->kode,
						$d->nama,
						(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						(!empty($d->jum)?$d->jum .' Data':$this->otherfunctions->getMark($d->jum)),
						$properties['tanggal'],
						$properties['status'],
						$info.$delete,
						$this->codegenerator->encryptChar($d->kode),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd_kendaraan');
				$data=$this->model_master->getKendaraanDinas($id);
				foreach ($data as $d) {
  					$intensif_bbm=$this->model_master->getListBBMKode($d->kode);
  					if(count($intensif_bbm) != null){
						$tabel='';
						$tabel.='<hr><h4 align="center"><b>Data Intensif BBM '.$d->nama.'</b></h4>
	          				<table class="table table-bordered table-striped data-table">
	          					<thead>
	          						<tr class="bg-blue">
	          							<th>No.</th>
	          							<th>Nama Kendaraan</th>
	          							<th>Dari Jarak (km)</th>
	          							<th>Sampai Jarak (km)</th>
	          							<th>Besar Intensif</th>
	          							<th>Keterangan</th>
	          						</tr>
	          					</thead>
	          					<tbody>';
	          					$no=1;
	          					foreach ($intensif_bbm as $tg) {
	          						$jns_umum=(!empty($tg->j_k_umum)?' ('.$this->otherfunctions->getKendaraanUmum($tg->j_k_umum).')':null);
	          							$tabel.='<tr>
	          							<td>'.$no.'</td>
	          							<td>'.$tg->nama_kendaraan.$jns_umum.'</td>
	          							<td>'.$tg->jarak_awal.' KM</td>
	          							<td>'.$tg->jarak_akhir.' KM</td>
	          							<td>'.$this->formatter->getFormatMoneyUser($tg->nominal).'</td>
	          							<td>'.$tg->keterangan.'</td>
	          						</tr>';
	          						$no++;
	          					}
		          				$tabel.='</tbody>
		          			</table>';
		          	}else{
		          		$tabel=null;
		          	}
					$datax=[
						'id'=>$d->id_pd_kendaraan,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						'keterangan'=>$d->keterangan,
						'tabel'=>$tabel,
						'v_keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeKendaraanPD();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_master_pd_kendaraan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'keterangan'=>ucwords($this->input->post('keterangan')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_pd_kendaraan',$this->model_master->checkKendaraanDinasCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_master_pd_kendaraan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'keterangan'=>ucwords($this->input->post('keterangan')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode']) {
				$cek=$this->model_master->checkKendaraanDinasCode($data['kode']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_pd_kendaraan',['id_pd_kendaraan'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Intensif BBM Perjalanan Dinas
	public function master_bbm_kendaraan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode_kendaraan=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListBBMKode($kode_kendaraan);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd_bbm,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$jns_umum=(!empty($d->j_k_umum)?' ('.$this->otherfunctions->getKendaraanUmum($d->j_k_umum).')':null);
					$datax['data'][]=[
						$d->id_pd_bbm,
						$d->kode,
						$d->nama_kendaraan.$jns_umum,
						(!empty($d->jarak_awal)||$d->jarak_awal==0) ? $d->jarak_awal.' km':$this->otherfunctions->getMark(),
						(!empty($d->jarak_akhir)||$d->jarak_akhir==0) ? $d->jarak_akhir.' km':$this->otherfunctions->getMark(),
						$this->formatter->getFormatMoneyUser($d->nominal),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd_bbm');
				$data=$this->model_master->getBBM($id);
				foreach ($data as $d) {
					$dari=(!empty($d->jarak_awal)||$d->jarak_awal==0) ? $d->jarak_awal.' km':$this->otherfunctions->getMark();
					$sampai=(!empty($d->jarak_akhir)||$d->jarak_akhir==0) ? $d->jarak_akhir.' km':$this->otherfunctions->getMark();
					$jns_umum=(!empty($d->j_k_umum)?' ('.$this->otherfunctions->getKendaraanUmum($d->j_k_umum).')':null);
					$datax=[
						'id'=>$d->id_pd_bbm,
						'kode'=>$d->kode,
						'kode_k'=>$d->kode_kendaraan,
						'nama'=>$d->nama_kendaraan.$jns_umum,
						'jarak'=>$dari.'  -  '.$sampai,
						'nominal'=>(!empty($d->nominal)) ? $this->formatter->getFormatMoneyUser($d->nominal):$this->otherfunctions->getMark(),
						'v_keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'dari'=>$d->jarak_awal,
						'sampai'=>$d->jarak_akhir,
						'nmnl'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'keterangan'=>$d->keterangan,
						'e_kendaraan_umum'=>$d->j_k_umum,
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
				$data = $this->codegenerator->kodeBBMPD();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_master_bbm_kendaraan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode_bbm');
		$kendaraan=$this->input->post('kode_kendaraan');
		$jenis=$this->input->post('kendaraan_umum');
		$jAwal=$this->input->post('jarak_min');
		$jAkhir=$this->input->post('jarak_mak');
		if ($kode != "") {
			if($jAwal >= $jAkhir){
				$datax=$this->messages->customFailure('Jarak awal tidak boleh sama atau lebih besar dengan jarak akhir');
			}else{
				$cekJarakAwal=$this->model_master->cekKendaraanJenisJarakAwal($kendaraan,$jenis,$jAwal);
				$cekJarakAkhir=$this->model_master->cekKendaraanJenisJarakAkhir($kendaraan,$jenis,$jAkhir);
				// if($cekJarakAwal=='tidak' && $cekJarakAkhir=='tidak'){
					$data=[
						'kode'=>strtoupper($kode),
						'kode_kendaraan'=>ucwords($this->input->post('kode_kendaraan')),
						'j_k_umum'=>$this->input->post('kendaraan_umum'),
						'jarak_awal'=>ucwords($this->input->post('jarak_min')),
						'jarak_akhir'=>ucwords($this->input->post('jarak_mak')),
						'nominal'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal')),
						'keterangan'=>ucwords($this->input->post('keterangan')),
					];
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQueryCC($data,'master_pd_bbm',$this->model_master->checkKendaraanDinasCode($kode));
				// }else{
				// 	$datax=$this->messages->customFailure('Nama Kendaraan pada jarak tersebut sudah ada');
				// }
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_master_bbm_kendaraan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kendaraan=$this->input->post('kode_kendaraan');
		$jenis=$this->input->post('kendaraan_umum');
		$jAwal=$this->input->post('jarak_min');
		$jAkhir=$this->input->post('jarak_mak');
		$kendaraan_old=$this->input->post('kode_kendaraan_old');
		$jenis_old=$this->input->post('kendaraan_umum_old');
		$jAwal_old=$this->input->post('jarak_min_old');
		$jAkhir_old=$this->input->post('jarak_mak_old');
		if ($id != "" && $this->input->post('kode') != "") {
			if($jAwal >= $jAkhir){
				$datax=$this->messages->customFailure('Jarak awal tidak boleh sama atau lebih besar dengan jarak akhir');
			}else{
				// $cekJarakAwal=$this->model_master->cekKendaraanJenisJarakAwal($kendaraan,$jenis,$jAwal);
				// $cekJarakAkhir=$this->model_master->cekKendaraanJenisJarakAkhir($kendaraan,$jenis,$jAkhir);
				// if($cekJarakAwal=='ada' && $cekJarakAkhir=='ada' && $kendaraan==$kendaraan_old && $jenis==$jenis_old && $jAwal==$jAwal_old && $jAkhir==$jAkhir_old){
					$data=array(
						'kode'=>strtoupper($this->input->post('kode')),
						'kode_kendaraan'=>ucwords($this->input->post('kode_kendaraan')),
						'j_k_umum'=>$this->input->post('kendaraan_umum'),
						'jarak_awal'=>ucwords($this->input->post('jarak_min')),
						'jarak_akhir'=>ucwords($this->input->post('jarak_mak')),
						'nominal'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal')),
						'keterangan'=>ucwords($this->input->post('keterangan')),
					);
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'master_pd_bbm',['id_pd_bbm'=>$id]);
				// }else{					
				// 	$datax=$this->messages->customFailure('Nama Kendaraan pada jarak tersebut sudah ada');
				// }
			}
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//=========== Master Kategori Perjalanan Dinas
	public function master_pd_kategori()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKategoriDinas();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd_kategori,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if ($d->status == 1) {
						$status='<button type="button" class="stat scc" href="javascript:void(0)" onclick=do_status_kategori('.$d->id_pd_kategori.',0)><i class="fa fa-toggle-on"></i></button>';
					}else{
						$status='<button type="button" class="stat err" href="javascript:void(0)" onclick=do_status_kategori('.$d->id_pd_kategori.',1)><i class="fa fa-toggle-off"></i></button>';
					}
					if (isset($access['l_ac']['stt'])) {
						$var_st=($d->status == 1) ? '<i class="fa fa-toggle-on stat scc" title="Tidak Diijinkan"></i>':'<i class="fa fa-toggle-off stat err" title="Tidak Diijinkan"></i>';
						$status=(in_array($access['l_ac']['stt'], $access['access']) && isset($access['l_ac']['stt']))  ? $status : $var_st;
					}else{
						$status=$this->messages->not_allow();
					}
			        if (isset($access['l_ac']['del'])) {
			            $delete = (in_array($access['l_ac']['del'], $access['access'])&&$d->kode!='KAPD0001'&&$d->kode!='KAPD0002'&&$d->kode!='KAPD0003'&&$d->kode!='KAPD0004'&&$d->kode!='KAPD0005'&&$d->kode!='KAPD0006'&&$d->kode!='NONPLANT'&&$d->kode!='KAPD202301140001'&&$d->kode!='KAPD202302090001')?'<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal_kategori('.$d->id_pd_kategori.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ':null;
			        }else{
			        	$delete=null;
			        }
        			$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_kategori('.$d->id_pd_kategori.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$d->id_pd_kategori,
						$d->kode,
						$d->nama,
						(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						(!empty($d->jum)?$d->jum .' Data':$this->otherfunctions->getMark($d->jum)),
						$properties['tanggal'],
						$status,
						$info.$delete,
						$this->codegenerator->encryptChar($d->kode),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd_kategori');
				$data=$this->model_master->getKategoriDinas($id);
				foreach ($data as $d) {
  					$intensif_bbm=$this->model_master->getListDKTKode($d->kode);
  					if(count($intensif_bbm) != null){
						$tabel='';
						$tabel.='<hr><h4 align="center"><b>Data Detail Tunjangan Kategori '.$d->nama.'</b></h4>
							<div style="max-height: 400px; overflow: auto;">
	          				<table class="table table-bordered table-striped">
	          					<thead>
	          						<tr class="bg-blue">
	          							<th>No.</th>
	          							<th>Nama Kategori</th>
	          							<th>Grade</th>';
	          							$tabel.=($d->kode=='KAPD0001')?'<th>Tempat</th>':null;
	          							$tabel.= '<th>Nominal</th>
	          							<th>Keterangan</th>
	          						</tr>
	          					</thead>
	          					<tbody>';
	          					$no=1;
	          					foreach ($intensif_bbm as $tg) {
									$grade=(!empty($tg->grade)) ? $tg->nama_grade.' ('.$tg->nama_loker.')':$this->otherfunctions->getMark();
									$nominal=(empty($tg->nominal)||$tg->nominal==0)?$this->otherfunctions->getMark():$this->formatter->getFormatMoneyUser($tg->nominal);
	          							$tabel.='<tr>
	          							<td>'.$no.'</td>
	          							<td>'.$tg->nama_kategori.'</td>
	          							<td>'.$grade.'</td>';
	          							$tabel.=($d->kode=='KAPD0001')?'<td>'.$this->otherfunctions->getPenginapan($tg->tempat).'</td>':null;
	          							$tabel.= '<td>'.$nominal.'</td>
	          							<td>'.$tg->keterangan.'</td>
	          						</tr>';
	          						$no++;
	          					}
		          				$tabel.='</tbody>
		          			</table></div>';
		          	}else{
		          		$tabel=null;
		          	}
					$datax=[
						'id'=>$d->id_pd_kategori,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						'nominal_min'=>$d->nominal_min,
						'nominal_min_view'=>$this->otherfunctions->getYesNo($d->nominal_min),
						'keterangan'=>$d->keterangan,
						'tabel'=>$tabel,
						'v_keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
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
			}elseif ($usage == 'view_one_x') {
				$kode = $this->input->post('kode');
				$data=$this->model_master->getKategoriDinasWhere(['a.kode'=>$kode]);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_pd_kategori,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						'nominal_min'=>$d->nominal_min,
						'nominal_min_view'=>$this->otherfunctions->getYesNo($d->nominal_min),
						'keterangan'=>$d->keterangan,
						'v_keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeKategoriPD();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_master_pd_kategori(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'nominal_min'=>ucwords($this->input->post('nominal_min')),
				'keterangan'=>ucwords($this->input->post('keterangan')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_pd_kategori',$this->model_master->checkKategoriDinasCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_master_pd_kategori(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'nominal_min'=>ucwords($this->input->post('nominal_min')),
				'keterangan'=>ucwords($this->input->post('keterangan')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode']) {
				$cek=$this->model_master->checkKategoriDinasCode($data['kode']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_pd_kategori',['id_pd_kategori'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//======================== Master Detail Kategori tunjangan Perjalanan Dinas =========================
	public function master_detail_kategori()
	{if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode_kategori=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListDKTKode($kode_kategori);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd_detail,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$grade=(!empty($d->grade))?$d->nama_grade.' ('.$d->nama_loker.')':$this->otherfunctions->getMark();
					$tempat=($kode_kategori=='KAPD0001')?$this->otherfunctions->getPenginapan($d->tempat):$this->otherfunctions->getMark();
					$datax['data'][]=[
						$d->id_pd_detail,
						$d->kode,
						$d->nama_kategori,
						$grade,
						(empty($d->nominal)||$d->nominal==0)?$this->otherfunctions->getMark():$this->formatter->getFormatMoneyUser($d->nominal),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd_detail');
				$data=$this->model_master->getDKT($id);
				foreach ($data as $d) {
					$grade=(!empty($d->grade)) ? $d->nama_grade.' ('.$d->nama_loker.')':$this->otherfunctions->getMark();
					$datax=[
						'id'=>$d->id_pd_detail,
						'kode'=>$d->kode,
						'kode_k'=>$d->kode_kategori,
						'nama'=>$d->nama_kategori,
						'grade'=>$grade,
						'tempat'=>(!empty($d->tempat)) ? $d->tempat:$this->otherfunctions->getMark(),
						'nominal'=>(empty($d->nominal)||$d->nominal==0) ? $this->otherfunctions->getMark():$this->formatter->getFormatMoneyUser($d->nominal),
						'nominal_min'=>(empty($d->nominal_min)||$d->nominal_min==0) ? $this->otherfunctions->getMark():$this->formatter->getFormatMoneyUser($d->nominal_min),
						'nominal_non'=>(empty($d->nominal_non)||$d->nominal_non==0) ? $this->otherfunctions->getMark():$this->formatter->getFormatMoneyUser($d->nominal_non),
						'v_keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'kode_grade'=>$d->grade,
						'tempat'=>$this->otherfunctions->getPenginapan($d->tempat),
						'e_tempat'=>$d->tempat,
						'nmnl'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'nominal_min_view'=>$this->formatter->getFormatMoneyUser($d->nominal_min),
						'nominal_non_view'=>$this->formatter->getFormatMoneyUser($d->nominal_non),
						'keterangan'=>$d->keterangan,
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
				$data = $this->codegenerator->kodeDKTPD();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_master_detail_kategori(){
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$kode=$this->input->post('kode_kategori');
		$kode_kategori=$this->input->post('kode_tunjangan');
		$grade=$this->input->post('kode_grade');
		$tempat=$this->input->post('tempat');
		if ($kode != "") {
			// $cekGradeKode=$this->model_master->cekGradeKode($grade,$kode_kategori,$tempat);
			// if($cekGradeKode=='tidak'){
				$data=[
					'kode'=>strtoupper($kode),
					'kode_kategori'=>$kode_kategori,
					'grade'=>$grade,
					'tempat'=>$tempat,
					'nominal'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal')),
					'nominal_min'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal_minimal')),
					'nominal_non'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal_non')),
					'keterangan'=>ucwords($this->input->post('keterangan')),
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'master_pd_detail_kategori');
			// }else{
			// 	$datax=$this->messages->customFailure('Kategori, Grade, dan Nama Lokasi sama dengan data yg sudah ada');
			// }
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edit_master_detail_kategori(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode_kategori=$this->input->post('kode_kategori');
		$grade=$this->input->post('kode_grade');
		$tempat=$this->input->post('tempat');
		$kod_tempat=($kode_kategori=='KAPD0001')?$tempat:null;
		$kode=$this->input->post('kode');
		if ($id != "" && $this->input->post('kode') != "") {
			$cekGradeKode=$this->model_master->cekGradeKode($grade,$kode_kategori,$tempat);
			$kategori_old=$this->input->post('kode_kategori_old');
			$tempat_old=$this->input->post('tempat_old');
			$grade_old=$this->input->post('kode_grade_old');
			// if($cekGradeKode['val']=='tidak'){ //&& $cekGradeKode['data']=$kode//$grade==$grade_old && $kode_kategori==$kategori_old && $tempat==$tempat_old){
				$data=array(
					'kode'=>strtoupper($kode),
					'kode_kategori'=>ucwords($this->input->post('kode_kategori')),
					'grade'=>ucwords($this->input->post('kode_grade')),
					'tempat'=>$kod_tempat,
					'nominal'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal')),
					'nominal_min'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal_minimal')),
					'nominal_non'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal_non')),
					'keterangan'=>ucwords($this->input->post('keterangan')),
				);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'master_pd_detail_kategori',['id_pd_detail'=>$id]);
			// }else{
			// 	$datax=$this->messages->customFailure('Kategori, Grade, dan Nama Lokasi sama dengan data yg sudah ada');
			// }
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function import_grade_perjalanan_dinas()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			// print_r($_FILES);
		   	$kode_tunjangan = $this->input->post('kode_tunjangan');
			$sheet[0]=[
				'range_huruf'=>3,
				'row'=>2,
				'table'=>'master_pd_detail_kategori',
				'other'=>['kode_tunjangan'=>$kode_tunjangan],
				'column_code'=>'kode',
				'usage'=>'import_pd_detail_kategori',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'column'=>[
					// 1=>'grade', 4=>'kode_kategori', 6=>'tempat', 7=>'nominal', 8=>'keterangan',
					1=>'grade', 4=>'tempat', 5=>'nominal', 6=>'nominal_min', 7=>'nominal_non', 8=>'keterangan',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	//Master jarak Plant Perjalanan Dinas
	public function master_pd_jarak_plant()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListJarakPlant();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_jarak_plant,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$properties['aksi']=str_replace('view_modal', 'view_modal_jarak', $properties['aksi']);
					$properties['status']=str_replace('do_status', 'do_status_jarak', $properties['status']);
					$properties['aksi']=str_replace('delete_modal', 'delete_modal_jarak', $properties['aksi']);
					$datax['data'][]=[
						$d->id_jarak_plant,
						$d->kode,
						$d->nama_plant_asal,
						$d->nama_plant_tujuan,
						(!empty($d->jarak)) ? $d->jarak.' KM':$this->otherfunctions->getMark(),
						(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_jarak_plant');
				$data=$this->model_master->getJarakPlant($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_jarak_plant,
						'kode'=>$d->kode,
						'asal'=>$d->nama_plant_asal,
						'tujuan'=>$d->nama_plant_tujuan,
						'e_asal'=>$d->plant_asal,
						'e_tujuan'=>$d->plant_tujuan,
						'jarak'=>$d->jarak,
						'keterangan'=>$d->keterangan,
						'v_keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeJarakPlant();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_master_pd_jarak_plant(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode'=>strtoupper($kode),
				'plant_asal'=>$this->input->post('plant_asal'),
				'plant_tujuan'=>$this->input->post('plant_tujuan'),
				'jarak'=>$this->input->post('jarak'),
				'keterangan'=>ucwords($this->input->post('keterangan')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_pd_jarak_plant',$this->model_master->checkJarakPlantCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_master_pd_jarak_plant(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode'=>strtoupper($this->input->post('kode')),
				'plant_asal'=>$this->input->post('plant_asal'),
				'plant_tujuan'=>$this->input->post('plant_tujuan'),
				'jarak'=>$this->input->post('jarak'),
				'keterangan'=>ucwords($this->input->post('keterangan')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode']) {
				$cek=$this->model_master->checkJarakPlantCode($data['kode']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_pd_jarak_plant',['id_jarak_plant'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//Master Kode Akun Perjalanan Dinas
	public function master_pd_kode_akun()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKodeAkun();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kode_akun,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$properties['aksi']=str_replace('view_modal', 'view_kode_akun', $properties['aksi']);
					$properties['status']=str_replace('do_status', 'do_status_kode_akun', $properties['status']);
					$properties['aksi']=str_replace('delete_modal', 'delete_kode_akun', $properties['aksi']);
					
					// if (isset($access['l_ac']['del'])) {
					// 	$delete = (in_array($access['l_ac']['del'], $access['access'])&&$d->kode_perjanjian!='PTSP' && $d->kode_perjanjian!='RSGN') ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_perjanjian.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					// }else{
					// 	$delete = null;
					// }
					// $info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_perjanjian.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';


					$datax['data'][]=[
						$d->id_kode_akun,
						$d->kode_akun,
						$d->nama,
						(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_kode_akun');
				$data=$this->model_master->getKodeAkunID($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_kode_akun,
						'kode'=>$d->kode_akun,
						'nama'=>$d->nama,
						'keterangan'=>$d->keterangan,
						'v_keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeJarakPlant();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_master_pd_kode_akun(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_akun'=>strtoupper($kode),
				'nama'=>$this->input->post('nama'),
				'keterangan'=>ucwords($this->input->post('keterangan')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_pd_kode_akun',$this->model_master->checkCodeAkun($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_master_pd_kode_akun(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode=$this->input->post('kode');
		if ($id != "") {
			$data=[
				'kode_akun'=>strtoupper($kode),
				'nama'=>$this->input->post('nama'),
				'keterangan'=>ucwords($this->input->post('keterangan')),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_akun']) {
				$cek=$this->model_master->checkCodeAkun($data['kode_akun']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_pd_kode_akun',['id_kode_akun'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//===MASTER DATA ABSENSI END===//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA PENGGAJIAN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Tarif Lembur
	public function master_tarif_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListTarifLembur();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_tarif_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_tarif_lembur,
						$d->kode_tarif_lembur,
						$d->nama,
						$this->otherfunctions->getJenisLemburKey($d->jenis_lembur),
						$d->jam_ke,
						$d->faktor_kali,
						$d->jam_potong.' Jam',
						// $this->formatter->getFormatMoneyUser($d->tarif),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_tarif_lembur');
				$data=$this->model_master->getTarifLembur($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_tarif_lembur,
						'kode_tarif_lembur'=>$d->kode_tarif_lembur,
						'nama'=>$d->nama,
						'jenis_lembur'=>$this->otherfunctions->getJenisLemburKey($d->jenis_lembur),
						'jam_ke'=>$d->jam_ke,
						'faktor_kali'=>$d->faktor_kali.' Kali',
						'jam_potong'=>$d->jam_potong.' Jam',
						'e_jenis_lembur'=>$d->jenis_lembur,
						'e_faktor_kali'=>$d->faktor_kali,
						'e_jam_potong'=>$d->jam_potong,
						'keterangan'=>$d->keterangan,
						'tarif'=>$this->formatter->getFormatMoneyUser($d->tarif),
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
				$data = $this->codegenerator->kodeTarifLembur();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_tarif_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_tarif_lembur'=>strtoupper($kode),
				'nama'             =>ucwords($this->input->post('nama')),
				'jenis_lembur'     =>$this->input->post('jenis_lembur'),
				'jam_potong'	   =>$this->input->post('jam_potong'),
				'jam_ke'           =>$this->input->post('jam_ke'),
				'faktor_kali'      =>$this->input->post('faktor_kali'),
				'keterangan'       =>$this->input->post('keterangan'),
				'tarif'            =>$this->formatter->getFormatMoneyDb($this->input->post('tarif')),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_tarif_lembur',$this->model_master->checkTarifLemburCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_tarif_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_tarif_lembur'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'tarif'=>$this->formatter->getFormatMoneyDb($this->input->post('tarif')),
				'jenis_lembur'     =>$this->input->post('jenis_lembur'),
				'jam_potong'	   =>$this->input->post('jam_potong'),
				'jam_ke'           =>$this->input->post('jam_ke'),
				'faktor_kali'      =>$this->input->post('faktor_kali'),
				'keterangan'       =>$this->input->post('keterangan'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_tarif_lembur']) {
				$cek=$this->model_master->checkTarifLemburCode($data['kode_tarif_lembur']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_tarif_lembur',['id_tarif_lembur'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Tarif UMK
	public function master_tarif_umk()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListTarifUmk();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_tarif_umk,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_tarif_umk,
						$d->kode_tarif_umk,
						$d->nama,
						$this->formatter->getFormatMoneyUser($d->tarif),
						(!empty($d->nama_loker)) ? $d->nama_loker:$this->otherfunctions->getMark(),
						(!empty($d->tahun)) ? $d->tahun:$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_tarif_umk');
				$data=$this->model_master->getTarifUmk($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_tarif_umk,
						'kode_tarif_umk'=>$d->kode_tarif_umk,
						'nama'=>$d->nama,
						'tarif'=>$this->formatter->getFormatMoneyUser($d->tarif),
						'tahun'=>(!empty($d->tahun)) ? $d->tahun:$this->otherfunctions->getMark(),
						'lokasi'=>(!empty($d->nama_loker)) ? $d->nama_loker:$this->otherfunctions->getMark(),
						'e_lokasi'=>$d->loker,
						'e_tahun'=>$d->tahun,
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
				$data = $this->codegenerator->kodeTarifUmk();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_tarif_umk(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$loker=$this->input->post('loker');
			foreach ($loker as $lok) {
				$data=[
					// 'kode_tarif_umk'=>strtoupper($kode),
					'nama'=>ucwords($this->input->post('nama')),
					'tarif'=>$this->formatter->getFormatMoneyDb($this->input->post('tarif')),
					'tahun'=>$this->input->post('tahun'),
					'loker'=>$lok,
				];
				$data['kode_tarif_umk']=$this->codegenerator->kodeTarifUmk();
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'master_tarif_umk');//,$this->model_master->checkTarifUmkCode($kode));
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_tarif_umk(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_tarif_umk'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'tarif'=>$this->formatter->getFormatMoneyDb($this->input->post('tarif')),
				'tahun'=>$this->input->post('tahun'),
				'loker'=>$this->input->post('loker'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_tarif_umk']) {
				$cek=$this->model_master->checkTarifUmkCode($data['kode_tarif_umk']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_tarif_umk',['id_tarif_umk'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	
	//===MASTER DATA PENGGAJIAN END===//
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA DOKUMEN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Dokumen
	public function master_dokumen()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListDokumen();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_dokumen,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
          			$info='<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_dokumen.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					if (isset($access['l_ac']['del'])) {
						if($d->kode_dokumen != 'DOC_PERDIN'){
							$delete = (in_array($access['l_ac']['del'], $access['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_dokumen.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
						}else{
							$delete=null;
						}
					}else{
						$delete = null;
					}

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_dokumen,
						$d->kode_dokumen,
						'<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($d->file)).'">'.$d->nama.'</a>',
						$properties['tanggal'],
						$properties['status'],
						// $properties['aksi'],
						$info.$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_dokumen');
				$data=$this->model_master->getDokumen($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_dokumen,
						'kode_dokumen'=>$d->kode_dokumen,
						'nama_val'=>$d->nama,
						'nama'=>'<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($d->file)).'">'.$d->nama.'</a><br><span class="text-sm">*Klik untuk download file</span>',
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
				$data = $this->codegenerator->kodeDokumen();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_dokumen(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$other=[
				'kode_dokumen'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
			];
			$other=array_merge($other,$this->model_global->getCreateProperties($this->admin));
			$data=[
				'post'=>'file',
		        'data_post'=>$this->input->post('file', TRUE),
		        'table'=>'master_dokumen',
		        'column'=>'file', 
		        'usage'=>'insert',
		        'otherdata'=>$other,
		    ];
		    $datax=$this->filehandler->doUpload($data,'doc');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_dokumen(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$other=[
				'nama'=>ucwords($this->input->post('nama')),
			];
			$other=array_merge($other,$this->model_global->getUpdateProperties($this->admin));
			$data=[
				'post'=>'file',
		        'data_post'=>$this->input->post('file', TRUE),
		        'table'=>'master_dokumen',
		        'column'=>'file',
		        'where'=>['id_dokumen'=>$id],
		        'usage'=>'update',
		        'otherdata'=>$other,
		    ];
		    $datax=$this->filehandler->doUpload($data,'doc');
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	
	//===MASTER DATA DOKUMEN END===//
	
	//===MASTER DATA KARYAWAN END===//
	
	//==INDIKATOR==//
	public function master_indikator()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKpi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kpi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_kpi,
						$d->kode_kpi,
						$d->kpi,
						$d->cara_mengukur,
						$d->sumber,
						$this->otherfunctions->getPeriodePenilaian($d->periode_pelaporan),
						$d->polarisasi,
						$d->nama_bagian,
						$properties['status'],
						$properties['aksi'],
						$properties['tanggal'],
						$d->kode_bagian
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_kpi');
				$data=$this->model_master->getKpi($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_kpi,
						'kode_kpi'=>$d->kode_kpi,
						'nama'=>$d->kpi,
						'cara_mengukur'=>$d->cara_mengukur,
						'sumber'=>$d->sumber,
						'polarisasi'=>$d->polarisasi,
						'periode_pelaporan'=>$this->otherfunctions->getPeriodePenilaian($d->periode_pelaporan),
						'kode_periode_pelaporan'=>$d->periode_pelaporan,
						'nama_bidang'=>$d->nama_bidang,
						'kaitan'=>$d->kaitan,
						'rumus'=>$d->rumus,
						'kode_bagian'=>$d->kode_bagian,
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
				$data = $this->codegenerator->kodeKpi();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_indikator(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=array(
				'kode_kpi'=>$kode,
				'kpi'=>ucwords($this->input->post('indikator')),
				'cara_mengukur'=>ucwords($this->input->post('pengukuran')),
				'sumber'=>ucwords($this->input->post('sumber')),
				'periode_pelaporan'=>ucwords($this->input->post('pelaporan')),
				'polarisasi'=>ucwords($this->input->post('polarisasi')),
				'kode_bagian'=>strtoupper($this->input->post('bagian')),
				'kaitan'=>$this->input->post('kaitan'),
				'rumus'=>$this->input->post('rumus'),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_kpi',$this->model_master->checkKpiCode($kode));		
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function edt_indikator(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'kode_kpi'=>strtoupper($this->input->post('kode')),
				'kpi'=>ucwords($this->input->post('indikator')),
				'cara_mengukur'=>ucwords($this->input->post('pengukuran')),
				'sumber'=>ucwords($this->input->post('sumber')),
				'periode_pelaporan'=>ucwords($this->input->post('pelaporan')),
				'polarisasi'=>ucwords($this->input->post('polarisasi')),
				'kode_bagian'=>strtoupper($this->input->post('bagian')),
				'kaitan'=>$this->input->post('kaitan'),
				'rumus'=>$this->input->post('rumus'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_kpi']) {
				$cek=$this->model_master->checkKpiCode($data['kode_kpi']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_kpi',['id_kpi'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	
/*++++++++++++++++++++++++++++++++++++++++++++= Sourch Putra S. Bud =++++++++++++++++++++++++++++++++++++++++++++ */
/*++++++++++++++++++++++++++++++ date 02/04/2019 	++++++++++++++++++++++++++++++*/

	//--------------------------------------------------------------------------------------------------------------//
	//Master Induk Tunjangan Penggajian
	public function master_induk_tunjangan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListIndukTunjangan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_induk_tunjangan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_induk_tunjangan,
						$d->kode_induk_tunjangan,
						$d->nama,
						$this->otherfunctions->searchFromArray($d->sifat,['0'=>'Tidak Tetap','1'=>'Tetap']),
						$this->otherfunctions->searchFromArray($d->with_payroll,['0'=>'Bersama Gaji Pokok','1'=>'Sendiri']),
						$this->otherfunctions->searchFromArray($d->pph,['0'=>'Tidak','1'=>'Ya']),
						$this->otherfunctions->searchFromArray($d->upah,['0'=>'Tidak','1'=>'Ya']),
						count($listTunjangan = $this->model_master->getListTunjangan(['a.kode_induk_tunjangan'=>$d->kode_induk_tunjangan])).' Data',
						// $this->formatter->getFormatMoneyUser($d->nominal),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->kode_induk_tunjangan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_induk_tunjangan');
				$data=$this->model_master->getIndukTunjangan($id);
				foreach ($data as $d) {
					$div_detail ='';
					$listTunjangan = $this->model_master->getListTunjangan(['a.kode_induk_tunjangan'=>$d->kode_induk_tunjangan]);
					$no = 1;
					foreach ($listTunjangan as $gt) {
						$emp = $this->otherfunctions->getDataExplode($gt->karyawan, ';', 'all');
						$exkaryawan2 =[];
						foreach ($emp as $key => $value) {
							$data_karyawan = $this->model_karyawan->getEmpID($value);
							if($this->dtroot['adm']['level'] != 0){
								if($data_karyawan['golongan'] == 1){
									$exkaryawan2[$value] = $data_karyawan['nama'];
								}
							}else{
								$exkaryawan2[$value] = $data_karyawan['nama'];
							}
						}
						$karyawan = '<ol>';
						asort($exkaryawan2);
						foreach ($exkaryawan2 as $key => $value) {
							$karyawan .= '<li>'.$value.'. </li>';
						}
						$karyawan .= '</ol>';
						$div_detail .= '<tr>
						<td>'.$no.'</td>
						<td>'.$gt->nama.'</td>
						<td>'.$this->formatter->getFormatMoneyUser($gt->nominal).'</td>
						<td>'.$karyawan.'</td>
						<td>'.(($gt->status == '1') ? "Aktif" : "Tidak Aktif").'</td>
						</tr>';
						$no++;					}

					$datax=[
						'id'=>$d->id_induk_tunjangan,
						'kode_induk_tunjangan'=>$d->kode_induk_tunjangan,
						'nama'=>$d->nama,
						'sifat'=>$d->sifat,
						'periode'=>$d->with_payroll,
						'pph'=>$d->pph,
						'upah'=>$d->upah,
						'detail'=>$div_detail,
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
				$data = $this->codegenerator->kodeIndukTunjangan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_induk_tunjangan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_induk_tunjangan'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'sifat'=>$this->input->post('sifat'),
				'with_payroll'=>$this->input->post('periode'),
				'pph'=>$this->input->post('pph'),
				'upah'=>$this->input->post('upah'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_induk_tunjangan',$this->model_master->checkIndukTunjanganCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_induk_tunjangan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_induk_tunjangan'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'sifat'=>$this->input->post('sifat'),
				'with_payroll'=>$this->input->post('periode'),
				'pph'=>$this->input->post('pph'),
				'upah'=>$this->input->post('upah'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_induk_tunjangan']) {
				$cek=$this->model_master->checkTarifUmkCode($data['kode_induk_tunjangan']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_induk_tunjangan',['id_induk_tunjangan'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--TUNJANGAN--//
	public function master_tunjangan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode_induk=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListTunjanganKode($kode_induk,'parent','kode');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
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

					/*========== Induk grade ==========*/
					// $indukgrade = '<div style="text-align: center;"><label class="label label-sm label-success label-xs">Semua Grade</label></div>';
					// if(!empty($d->kode_induk_grade)){
					// 	$indukgrade = $d->nama_induk_grade;
					// }
					/*========== End Induk grade ==========*/

					/*========== grade ==========*/
					// $grade = '<div style="text-align: center;"><label class="label label-sm label-success label-xs">Semua Sub-Grade</label></div>';
					// if(!empty($d->kode_grade)){
					// 	$grade = [];
					// 	$no_grade = 1;
					// 	$grdeEx = explode(";",$d->kode_grade);
					// 	foreach ($grdeEx as $gkey => $gvalue) {
					// 		$dataGrade = $this->otherfunctions->convertResultToRowArray($this->model_master->getGrade($gvalue));
					// 		$grade[] = $dataGrade['nama'];
					// 		$no_grade++;
					// 	}
					// 	$grade = implode("<br>", $grade);
					// }/*========== end grade ==========*/
					
					// /*========== jabatan ==========*/
					// $jabatanEx = explode(";",$d->kode_jabatan);
					// $i_jabatan = $this->model_master->getListJabatanWhere(['a.id_jabatan !='=>1,'a.id_jabatan !='=>2,'a.id_jabatan !='=>3],null,1);
					// if(count($i_jabatan) == count($jabatanEx)){
					// 	$jabatan = '<div style="text-align: center;"><label class="label label-sm label-success label-xs">Semua Jabatan</label></div>';
					// }else{
					// 	$jabatan = count($jabatanEx).' Jabatan';
					// }
					$karyawanEx = explode(";",$d->karyawan);
					$i_karyawan = $this->model_karyawan->getPilihKaryawanMutasi();
					if(count($i_karyawan) == count($karyawanEx)){
						$karyawan = '<div style="text-align: center;"><label class="label label-sm label-success label-xs">Semua karyawan</label></div>';
					}else{
						$karyawan = count($karyawanEx).' Karyawan';
					}
					if($this->dtroot['adm']['level'] != 0){
						if($d->create_by != $this->admin){
							$delete = null; 
						}else{
							$delete = $properties['delete']; 
						}
					}else{
						$delete = $properties['delete'];
					}
					$datax['data'][]=[
						$d->id_tunjangan,
						$d->kode_tunjangan,
						$d->nama,
						$this->formatter->getFormatMoneyUser($d->nominal),
						$karyawan,
						// $d->tahun,
						$properties['tanggal'],
						$properties['status'],
						// $properties['aksi']
						$properties['info'].$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_tunjangan');
				$data=$this->model_master->getListTunjanganKode($id,'child','id');
				foreach ($data as $d) {
					if(!empty($d->kode_grade)){
						$grade = explode(";",$d->kode_grade);
					}else{
						$grade = ['all'];
					}
					$karyawanEx = explode(";",$d->karyawan);
					$i_karyawan = $this->model_karyawan->getPilihKaryawanMutasi();
					if(count($i_karyawan) == count($karyawanEx)){
						$tunjangan = ['all'];
					}else{
						$tunjangan = explode(";",$d->karyawan);
					}
					// $grade = 
					$datax=[
						'id'=>$d->id_tunjangan,
						'kode_tunjangan'=>$d->kode_tunjangan,
						'nama'=>$d->nama,
						'induk_grade'=>$d->kode_induk_grade,
						'grade'=>$grade,
						'tunjangan'=>$tunjangan,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'tahun'=>$d->tahun,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_info') {
				$id = $this->input->post('id_tunjangan');
				$data=$this->model_master->getListTunjanganKode($id,'child','id');
				foreach ($data as $d) {
					if(empty($d->kode_induk_grade)){
						$induk_grade = '<label class="label label-sm label-success label-xs">Semua Grade</label>';
					}else{
						$induk_grade = $d->nama_induk_grade;
					}
					// if(empty($d->kode_grade)){
					// 	$grade = '<label class="label label-sm label-success label-xs">Semua Sub-Grade</label>';
					// }else{
					// 	$exgrade = explode(";", $d->kode_grade);
					// 	$exgrade2 = [];
					// 	foreach ($exgrade as $key => $value) {
					// 		$data_grade = $this->otherfunctions->convertResultToRowArray($this->model_master->getGrade($value));
					// 		$exgrade2[$value] = $data_grade['nama'];
					// 	}

					// 	$grade = '<table>';
					// 	$no_grade = 1;
					// 	asort($exgrade2);
					// 	foreach ($exgrade2 as $key => $value) {
					// 		$grade .= '<tr><td style="padding-right: 5px;vertical-align: top;width: 20px;">'.$no_grade.'. </td><td>'.$value.'</td></tr>';
					// 		$no_grade++;
					// 	}
					// 	$grade .= '</table>';
					// }
					/*=======Jabatan=======*/
					// if(empty($d->kode_jabatan)){
					// 	$induk_grade = '<label class="label label-sm label-success label-xs">Semua Jabatan</label>';
					// }else{
					// 	$exjabatan = explode(";", $d->kode_jabatan);
					// 	$exjabatan2 =[];
					// 	foreach ($exjabatan as $key => $value) {
					// 		$data_jabatan = $this->model_master->jabatan($value);
					// 		$exjabatan2[$value] = $data_jabatan['nama'];
					// 	}

					// 	$jabatan = '<table>';
					// 	$no_jabatan = 1;
					// 	asort($exjabatan2);
					// 	foreach ($exjabatan2 as $key => $value) {
					// 		$jabatan .= '<tr><td style="padding-right: 5px;vertical-align: top;width: 20px;">'.$no_jabatan.'. </td><td>'.$value.'</td></tr>';
					// 		$no_jabatan++;
					// 	}
					// 	$jabatan .= '</table>';
					// }
					if(empty($d->karyawan)){
						$induk_grade = '<label class="label label-sm label-success label-xs">Semua Karyawan</label>';
					}else{
						$exkaryawan = explode(";", $d->karyawan);
						$exkaryawan2 =[];
						foreach ($exkaryawan as $key => $value) {
							$data_karyawan = $this->model_karyawan->getEmpID($value);
							if($this->dtroot['adm']['level'] != 0){
								if($data_karyawan['golongan'] == 1){
									$exkaryawan2[$value] = $data_karyawan['nama'];
								}
							}else{
								$exkaryawan2[$value] = $data_karyawan['nama'];
							}
						}
						$karyawan = '<table>';
						$no_karyawan = 1;
						asort($exkaryawan2);
						foreach ($exkaryawan2 as $key => $value) {
							$karyawan .= '<tr><td style="padding-right: 5px;vertical-align: top;width: 20px;">'.$no_karyawan.'. </td><td>'.$value.'</td></tr>';
							$no_karyawan++;
						}
						$karyawan .= '</table>';
					}
					$datax=[
						'id'=>$d->id_tunjangan,
						'kode_tunjangan'=>$d->kode_tunjangan,
						'nama'=>$d->nama,
						'induk_tunjangan'=>$d->nama_induk_tunjangan,
						'induk_grade'=>$induk_grade,
						// 'grade'=>$grade,
						'jabatan'=>$karyawan,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'tahun'=>$d->tahun,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'admin'=>$this->admin,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'table'=>$this->table_detail_tunjangan($d->kode_tunjangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'check_all') {
				$table = $this->input->post('table');
				$value = $this->input->post('value');

				if($table == 'master_grade'){
					$this->db->where('kode_induk_grade',$this->input->post('where'));
				}
				$this->db->where('status','1');
				$data = $this->db->get($table)->result();
				$datax = [];
				foreach ($data as $d) {
					$datax[] = $d->$value;
				}
        		echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeTunjangan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function table_detail_tunjangan($kode)
	{
		$data=$this->model_master->getListTunjanganKode($kode,'child','kode');
		$table = '<table class="table table-responsive table-striped table-bordered table-fixed"><tr><th style="text-align:center;">No</th><th style="text-align:center;">Induk Grade</th><th style="text-align:center;">Grade</th><th style="text-align:center;">Lokasi Kerja</th><th style="text-align:center;">Jabatan</th></tr>';
		$no = 1;
		foreach ($data as $d) {
			$table .= '<tr>
							<td>'.$no.'</td>
							<td>'.$d->nama_induk_grade.'</td>
							<td>'.$d->nama_grade.'</td>
							<td>'.$d->nama_loker.'</td>
							<td>'.$d->nama_jabatan.'</td>
						</tr>';
			$no++;
		}
		$table .= '</table>';
		return $table;
	}
	function add_tunjangan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		// $kode = $this->input->post('kode');
		// $induk_tunjangan = $this->input->post('induk_tunjangan');
		// $nama = $this->input->post('nama');
		// $induk_grade = $this->input->post('induk_grade');
		// $grade = $this->input->post('grade');
		// $loker = $this->input->post('loker');
		// $jabatan_in = $this->input->post('jabatan');
		// $nominal = $this->input->post('nominal');
		// $tahun = $this->input->post('tahun');
		$kode = $this->input->post('kode');
		$induk_tunjangan = $this->input->post('induk_tunjangan');
		$nama = $this->input->post('nama');
		$karyawan_in = $this->input->post('karyawan');
		$nominal = $this->input->post('nominal');
		if(!empty($kode)){
			// if(!empty($induk_grade)){
			// 	if(!empty($grade)){
			// 		$c_grade = $this->otherfunctions->checkValueAll($grade);
			// 		if($c_grade){
			// 			$i_grade = $this->model_master->getListGradeKode($induk_grade,'induk_grade');
			// 			$grade = [];
			// 			foreach ($i_grade as $i) {
			// 				$grade[] = $i->id_grade;
			// 			}
			// 			$grade = implode(";", $grade);
			// 		}else{
			// 			$grade = implode(";", $grade);
			// 		}
			// 	}else{
			// 		$i_grade = $this->model_master->getListGradeKode($induk_grade,'induk_grade');
			// 		$grade = [];
			// 		foreach ($i_grade as $i) {
			// 			$grade[] = $i->id_grade;
			// 		}
			// 		$grade = implode(";", $grade);
			// 	}				
			// }
			// if(in_array('all',$jabatan_in)){
			// 	$i_jabatan = $this->model_master->getListJabatanWhere(['a.id_jabatan !='=>1,'a.id_jabatan !='=>2,'a.id_jabatan !='=>3],null,1);
			// 	$jabatan = [];
			// 	foreach ($i_jabatan as $i) {
			// 		$jabatan[] = $i->id_jabatan;
			// 	}
			// 	$jabatan = implode(";", $jabatan);
			// }else{
			// 	$c_jabatan = $this->otherfunctions->checkValueAll($jabatan_in);
			// 	if($c_jabatan){
			// 		$i_jabatan = $this->model_master->getListJabatanWhere(['a.id_jabatan !='=>1,'a.id_jabatan !='=>2,'a.id_jabatan !='=>3],null,1);
			// 		$jabatan = [];
			// 		foreach ($i_jabatan as $i) {
			// 			$jabatan[] = $i->id_jabatan;
			// 		}
			// 		$jabatan = implode(";", $jabatan_in);
			// 	}else{
			// 		$jabatan = implode(";", $jabatan_in);
			// 	}
			// }
			if(in_array('all',$karyawan_in)){
				$i_karyawan = $this->model_karyawan->getPilihKaryawanMutasi();
				$karyawanx = [];
				foreach ($i_karyawan as $i) {
					$karyawanx[] = $i->id_karyawan;
				}
				$karyawan = implode(";", $karyawanx);
			}else{
				$c_karyawan = $this->otherfunctions->checkValueAll($karyawan_in);
				if($c_karyawan){
					$i_karyawan = $this->model_master->getPilihKaryawanMutasi();
					$karyawan = [];
					foreach ($i_karyawan as $i) {
						$karyawan[] = $i->id_karyawan;
					}
					$karyawan = implode(";", $karyawan_in);
				}else{
					$karyawan = implode(";", $karyawan_in);
				}
			}
			$data=[
				'kode_tunjangan'=>strtoupper($kode),
				'kode_induk_tunjangan'=>strtoupper($induk_tunjangan),
				'nama'=>ucwords($nama),
				'karyawan'=> $karyawan,
				'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
				// 'kode_induk_grade'=> $induk_grade,
				// 'kode_grade'=> $grade,
				// 'kode_jabatan'=> $jabatan,
				// 'tahun'=>$tahun,
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'master_tunjangan');
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_tunjangan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			// $induk_grade = $this->input->post('induk_grade');
			// $grade = $this->input->post('grade');
			$karyawan_in = $this->input->post('karyawan');
			
			/*-------get grade-------*/
			// if(!empty($induk_grade)){
			// 	if(!empty($grade)){
			// 		$c_grade = $this->otherfunctions->checkValueAll($grade);
			// 		if($c_grade){
			// 			$i_grade = $this->model_master->getListGradeKode($induk_grade,'induk_grade');
			// 			$grade = [];
			// 			foreach ($i_grade as $i) {
			// 				$grade[] = $i->id_grade;
			// 			}
			// 			$grade = implode(";", $grade);
			// 		}else{
			// 			$grade = implode(";", $grade);
			// 		}
			// 	}else{
			// 		$i_grade = $this->model_master->getListGradeKode($induk_grade,'induk_grade');
			// 		$grade = [];
			// 		foreach ($i_grade as $i) {
			// 			$grade[] = $i->id_grade;
			// 		}
			// 		$grade = implode(";", $grade);
			// 	}
				
			// }
			// /*-------end get grade-------*/
			// /*-------get jabatan-------*/
			// if(in_array('all',$jabatan_in)){
			// 	$i_jabatan = $this->model_master->getListJabatanWhere(['a.id_jabatan !='=>1,'a.id_jabatan !='=>2,'a.id_jabatan !='=>3],null,1);
			// 	$jabatan = [];
			// 	foreach ($i_jabatan as $i) {
			// 		$jabatan[] = $i->id_jabatan;
			// 	}
			// 	$jabatan = implode(";", $jabatan);
			// }else{
			// 	$c_jabatan = $this->otherfunctions->checkValueAll($jabatan_in);
			// 	if($c_jabatan){
			// 		$i_jabatan = $this->model_master->getListJabatanWhere(['a.id_jabatan !='=>1,'a.id_jabatan !='=>2,'a.id_jabatan !='=>3],null,1);
			// 		$jabatan = [];
			// 		foreach ($i_jabatan as $i) {
			// 			$jabatan[] = $i->id_jabatan;
			// 		}
			// 		$jabatan = implode(";", $jabatan_in);
			// 	}else{
			// 		$jabatan = implode(";", $jabatan_in);
			// 	}
			// }
			if(in_array('all',$karyawan_in)){
				$i_karyawan = $this->model_karyawan->getPilihKaryawanMutasi();
				$karyawanx = [];
				foreach ($i_karyawan as $i) {
					$karyawanx[] = $i->id_karyawan;
				}
				$karyawan = implode(";", $karyawanx);
			}else{
				$c_karyawan = $this->otherfunctions->checkValueAll($karyawan_in);
				if($c_karyawan){
					$i_karyawan = $this->model_master->getPilihKaryawanMutasi();
					$karyawan = [];
					foreach ($i_karyawan as $i) {
						$karyawan[] = $i->id_karyawan;
					}
					$karyawan = implode(";", $karyawan_in);
				}else{
					$karyawan = implode(";", $karyawan_in);
				}
			}
			$data=array(
				'kode_tunjangan'=>$this->input->post('kode'),
				'nama'=>ucwords($this->input->post('nama')),
				'karyawan'=> $karyawan,
				// 'kode_induk_grade'=> $induk_grade,
				// 'kode_grade'=> $grade,
				// 'kode_jabatan'=> $jabatan,
				'nominal'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal')),
				// 'tahun'=>$this->input->post('tahun'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_tunjangan']) {
				$cek=$this->model_master->checkTunjanganCode($data['kode_tunjangan']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_tunjangan',['kode_tunjangan'=>$data['kode_tunjangan']],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function get_child_tunjangan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage = $this->input->post('usage');
		$post_induk_grade = $this->input->post('induk_grade');
		$post_grade = $this->input->post('grade');
		$post_loker = $this->input->post('loker');
		$post_jabatan = $this->input->post('jabatan');

		if($usage == 'grade'){
			$data_loker = [];
			$data_jabatan = [];
			$data = $this->model_master->getListGradeKode($post_induk_grade);
			$grade = '<option></option><option value="all">Pilih Semua</option>';
			$loker = '<option></option><option value="all">Pilih Semua</option>';
			$jabatan = '<option></option><option value="all">Pilih Semua</option>';
			foreach ($data as $d) {
				$grade .= '<option value="'.$d->id_grade.'">'.$d->nama.'</option>';
				$data_loker[$d->kode_loker] = $d->nama_loker;
			}
			$data_loker = array_unique($data_loker);
			foreach ($data_loker as $key => $value) {
				$loker .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$get_jabatan = $this->model_master->getJabatanFromGrade(['induk_grade'=>$post_induk_grade],'induk_grade');
			foreach ($get_jabatan as $g) {
				$data_jabatan[$g->kode_jabatan] = $g->nama;
			}
			$data_jabatan = array_unique($data_jabatan);
			foreach ($data_jabatan as $key => $value) {
				$jabatan .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$datax = [
				'grade' => $grade,
				'count_grade' => count($data),
				'loker' => $loker,
				'jabatan' => $jabatan
			];
		}elseif($usage == 'loker'){
			$data_loker = [];
			$data_jabatan = [];
			$data = $this->model_master->getListGradeKode($post_grade,'grade');
			$loker = '<option></option>';
			$jabatan = '<option></option>';
			foreach ($data as $d) {
				$data_loker[$d->kode_loker] = $d->nama_loker;
			}
			$data_loker = array_unique($data_loker);
			foreach ($data_loker as $key => $value) {
				$loker .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$get_jabatan = $this->model_master->getJabatanFromGrade(['induk_grade'=>$post_induk_grade, 'grade'=>$post_grade],'grade');
			foreach ($get_jabatan as $g) {
				$data_jabatan[$g->kode_jabatan] = $g->nama;
			}
			$data_jabatan = array_unique($data_jabatan);
			foreach ($data_jabatan as $key => $value) {
				$jabatan .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$datax = [
				'loker' => $loker,
				'jabatan' => $jabatan
			];
		}elseif($usage == 'jabatan'){
			$data_jabatan = [];
			$get_jabatan = $this->model_master->getJabatanFromGrade(['induk_grade'=>$post_induk_grade, 'grade'=>$post_grade, 'loker'=>$post_loker],'loker');
			$jabatan = '<option></option>';
			foreach ($get_jabatan as $g) {
				$data_jabatan[$g->kode_jabatan] = $g->nama;
			}
			$data_jabatan = array_unique($data_jabatan);
			foreach ($data_jabatan as $key => $value) {
				$jabatan .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$datax = [
				'jabatan' => $jabatan
			];
		}
		
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Periode Penggajian--//
	public function periode_penggajian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode_induk=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				if($this->dtroot['adm']['level'] != 0){
					$data=$this->model_master->getPeriodePenggajian(['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN']);
				}else{
					$data=$this->model_master->getPeriodePenggajian(['a.kode_master_penggajian'=>'BULANAN']);
				}
				// $data=$this->model_master->getPeriodePenggajian();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_periode_penggajian,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if($d->status_gaji == 1){
						$status_selesai = $this->otherfunctions->getLabelMark(null,'success','Sudah Selesai',null,null,'check');
					}else{
						$status_selesai = $this->otherfunctions->getLabelMark(null,'danger','Belum Selesai');
					}
					$tanggal = '<label class="label label-primary" data-toggle="tooltip" title="Tanggal Mulai"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateMonthFormatUser($d->tgl_mulai).'</label><br>
									<label class="label label-danger" data-toggle="tooltip" title="Tanggal Selesai"><i class="fas fa-times fa-fw"></i>'.$this->formatter->getDateMonthFormatUser($d->tgl_selesai).'</label>';
					$get_loker = $this->model_master->getListPeriodePenggajianDetail($d->kode_periode_penggajian);
					$jml_bagian = 0;
					foreach ($get_loker as $gl) {
						$jml_bagian = $jml_bagian+count(explode(";", $gl->id_bagian));
					}
					$datax['data'][]=[
						$d->id_periode_penggajian,
						'<a href="'.base_url('pages/view_periode_penggajian/').$this->codegenerator->encryptChar($d->kode_periode_penggajian).'">'.$d->kode_periode_penggajian.'</a>',
						$d->nama,
						$d->nama_sistem_penggajian,
						'<center>'.$tanggal.'</center>',
						$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						count($get_loker).' Lokasi',
						$jml_bagian.' Bagian',
						$status_selesai,
						$properties['tanggal'],
						$properties['status'],
						$properties['info'],//$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_periode_penggajian');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getPeriodePenggajian(['a.id_periode_penggajian'=>$id]);
				foreach ($data as $d) {
					if($d->tgl_selesai < date('Y-m-d')){
						$status_selesai = $this->otherfunctions->getLabelMark(null,'danger','Sudah Selesai','','left');
					}else{
						$status_selesai = $this->otherfunctions->getLabelMark(null,'success','Belum Selesai','','left');
					}
					$get_lokasi = $this->model_master->getListPeriodePenggajianDetail($d->kode_periode_penggajian);
					$div_detail = '';
					$no = 1;
					foreach ($get_lokasi as $gl) {
						foreach (explode(";", $gl->id_bagian) as $key => $value) {
							$get_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian($value));
							$div_detail .= '<tr>
									<td>'.$no.'</td>
									<td>'.$gl->nama_loker.'</td>
									<td>'.$get_bagian['nama'].'</td>
									<td>'.$get_bagian['nama_level_struktur'].'</td>
									</tr>';
							$no++;
						}
					}
					if($mode == 'edit'){
						$tgl_transfer = $this->formatter->getDateFormatUser($d->tgl_transfer);
					}else{
						$tgl_transfer = $this->otherfunctions->getLabelMark($this->formatter->getDateFormatUser($d->tgl_transfer),'danger','Belum Ditransfer','','left');
					}
					$datax=[
						'id'=>$d->id_periode_penggajian,
						'kode_periode_penggajian'=>$d->kode_periode_penggajian,
						'nama'=>$d->nama,
						'tgl_mulai'=>($mode == 'edit') ?  $this->formatter->getDateFormatUser($d->tgl_mulai) : $this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						'tgl_selesai'=>($mode == 'edit') ? $this->formatter->getDateFormatUser($d->tgl_selesai) : $this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						'tgl_transfer'=>$tgl_transfer,
						'total_transfer'=>($mode == 'edit') ? $d->total_transfer : $this->formatter->getFormatMoneyUser($d->total_transfer),
						'sistem_penggajian'=>($mode == 'edit') ? $d->kode_master_penggajian : $d->nama_sistem_penggajian,
						'detail'=>($mode != 'edit') ? $div_detail : '',
						'bulan_tahun'=>$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						'bulan'=>$d->bulan,
						'tahun'=>$d->tahun,
						'status_selesai'=>$status_selesai,
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
			}elseif($usage == 'periode_lama'){
				$kode = $this->input->post('kode_periode_penggajian');
				$data=$this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode]);
				$datax = '';
				foreach ($data as $d) {
					$datax = explode(";", $d->id_bagian);
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeriodePenggajian();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edt_periode_penggajian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$kode = $this->input->post('kode');
			$nama = $this->input->post('nama');
			$mulai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
			$selesai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
			$kode_old = $this->input->post('kode_old');
			$sistem_penggajian = $this->input->post('sistem_penggajian');
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$bulan_old = $this->input->post('bulan_old');
			$tahun_old = $this->input->post('tahun_old');
			// $cekDataOld = $this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode,'a.bulan'=>$bulan_old,'a.tahun'=>$tahun_old]);
			// $cekData = $this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian !='=>$kode,'a.bulan'=>$bulan,'a.tahun'=>$tahun]);
			$data = [
				'nama'=>$nama,
				'tgl_mulai'=>$mulai,
				'tgl_selesai'=>$selesai,
				'kode_master_penggajian'=>$sistem_penggajian,
				'bulan'=>$bulan,
				'tahun'=>$tahun,
			];
			// if(!empty($cekDataOld)){
			// 	if ($kode_old != $kode) {
			// 		$cek=$this->model_master->checkPeriodePenggajianCode($kode);
			// 	}else{
			// 		$cek=false;
			// 	}
			// 	if($selesai > $mulai){
			// 		if(empty($cekData)){
			// 			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			// 			$datax = $this->model_global->updateQueryCC($data,'data_periode_penggajian',['kode_periode_penggajian'=>$kode],$cek);
			// 		}else{
			// 			$datax=$this->messages->customFailure('Data Pada Bulan & Tahun Tersebut Sudah Ada');
			// 		}
			// 	}else{
			// 		$datax=$this->messages->notValidParam();
			// 	}
			// }else{
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'data_periode_penggajian',['kode_periode_penggajian'=>$kode]);
			// }	
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_periode_penggajian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		$nama = $this->input->post('nama');
		$mulai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
		$selesai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
		$old_periode = $this->input->post('old_periode');
		$sistem_penggajian = $this->input->post('sistem_penggajian');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		if ($kode != "") {
			// $cekData = $this->model_master->getPeriodePenggajian(['a.bulan'=>$bulan,'a.tahun'=>$tahun]);
			// if(empty($cekData)){
				if($selesai > $mulai){
					$data = [
						'nama'                   =>$nama,
						'tgl_mulai'              =>$mulai,
						'tgl_selesai'            =>$selesai,
						'kode_master_penggajian' =>$sistem_penggajian,
						'kode_periode_penggajian'=>$kode,
						'bulan'                  =>$bulan,
						'tahun'                  =>$tahun,
					];
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQueryCC($data,'data_periode_penggajian',$this->model_master->checkPeriodePenggajianCode($kode));
				// }else{
				// 	$datax=$this->messages->notValidParam();
				// }
			}else{
				$datax=$this->messages->customFailure('Data Pada Bulan & Tahun Tersebut Sudah Ada');
			}	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function del_periode_penggajian(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$kode=$this->input->post('kode');
		if ($id != "" || $kode != "") {
			$del_data = $this->model_global->deleteQuery('data_periode_penggajian',['id_periode_penggajian'=>$id]);
			if($del_data['status_data']){
				$this->model_global->deleteQueryNoMsg('data_penggajian',['kode_periode'=>$kode]);
				$del_data = $this->model_global->deleteQuery('data_periode_penggajian_detail',['kode_periode_penggajian'=>$kode]);
				if($del_data['status_data']){
					$datax=$this->messages->delGood(); 
				}else{
					$datax=$this->messages->customFailure('Detail Data Gagal Terhapus.'); 
				}
			}else{
				$datax=$this->messages->notValidParam(); 
			}
		}
		echo json_encode($datax);
	}
	function copy_periode_penggajian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$admin=$this->admin;
		$kode=$this->input->post('kode_periode');
		$nama = $this->input->post('nama');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$mulai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
		$selesai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
		$kodePeriodePenggajian = $this->codegenerator->kodePeriodePenggajian();
		$cekData = $this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$kode]);
		$periodeBefore=$this->otherfunctions->convertResultToRowArray($cekData);
		$cekDatath = $this->model_master->getPeriodePenggajian(['a.kode_old_periode'=>$kode,'a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.create_by'=>$admin]);
		if(empty($cekDatath)){
			$dataPeriode = [
				'kode_periode_penggajian'=>$kodePeriodePenggajian,
				'nama'                   =>$nama,
				'tgl_mulai'              =>$mulai,
				'tgl_selesai'            =>$selesai,
				'kode_master_penggajian' =>$periodeBefore['kode_master_penggajian'],
				'status_gaji'            =>0,
				'bulan'                  =>$bulan,
				'tahun'                  =>$tahun,
				'kode_old_periode'=>$kode
			];
			$dataPeriode=array_merge($dataPeriode,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($dataPeriode,'data_periode_penggajian',$this->model_master->checkPeriodePenggajianCode($kodePeriodePenggajian));
			$cek_detail = $this->model_master->getListPeriodePenggajianDetail($kode);
			foreach ($cek_detail as $cd) {
				$kodePeriodeDetail = $this->codegenerator->kodePeriodePenggajianDetail();
				$dataDetail=[
					'kode_periode_detail'    =>$kodePeriodeDetail,
					'kode_periode_penggajian'=>$kodePeriodePenggajian,
					'kode_umk'               =>$cd->kode_umk,
					'kode_loker'             =>$cd->kode_loker,
					'id_bagian'              =>$cd->id_bagian,
				];
				$dataDetail=array_merge($dataDetail,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($dataDetail,'data_periode_penggajian_detail',$this->model_master->checkPeriodePenggajianDetailCode($kodePeriodeDetail));
			}
		}else{
			$datax=$this->messages->customFailure('Data Bulan & Tahun tersebut Sudah Ada');
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Master Insentif--//
	public function master_insentif()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListInsentif('all_item');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_insentif,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$karyawan = ($d->id_karyawan == 'all') ? $this->model_karyawan->getEmployeeAllActive() : explode(";", $d->id_karyawan);
					$datax['data'][]=[
						$d->id_insentif,
						$d->kode_insentif,
						$d->nama,
						$this->formatter->getFormatMoneyUser($d->nominal),
						$d->tahun,
						count($karyawan).' Karyawan',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_insentif');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getInsentifWhere(['a.id_insentif'=>$id]);
				foreach ($data as $d) {
					if($mode == 'edit'){
						$karyawan = explode(";", $d->id_karyawan);
					}else{
						$table = '';
						$no = 1;
						if($d->id_karyawan == 'all'){
							$bag = $this->model_global->ObjectToArray(['id_karyawan'],'karyawan',['status_emp'=>1]);
						}else{
							$bag = explode(";", $d->id_karyawan);
						}
						foreach ($bag as $key => $value) {
							$get_emp = $this->model_karyawan->getEmployeeId($value);
							$table .= '	<tr>
							<td>'.$no.'</td>
							<td>'.$get_emp['nama'].'</td>
							<td>'.$get_emp['nama_jabatan'].'</td>
							<td>'.$get_emp['bagian'].'</td>
							<td>'.$get_emp['nama_loker'].'</td>
							</tr>';
							$no++;
						}
						$karyawan = $table;
					}
					$datax=[
						'id'=>$d->id_insentif,
						'kode_insentif'=>$d->kode_insentif,
						'nama'=>$d->nama,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'tahun'=>$d->tahun,
						'keterangan'=>$d->keterangan,
						'insentif_lama'=>$d->kode_old_insentif,
						'id_karyawan'=>$karyawan,
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
			}elseif($usage == 'periode_lama'){
				$kode = $this->input->post('kode_insentif');
				$data=$this->model_master->getInsentifWhere(['a.kode_insentif'=>$kode]);
				$datax = '';
				foreach ($data as $d) {
					$datax = explode(";", $d->id_karyawan);
				}
				echo json_encode($datax);
			}elseif ($usage == 'check_all') {
				$table = $this->input->post('table');
				$value = $this->input->post('value');

				if($table == 'karyawan'){
					$this->db->where('status_emp','1');
				}
				$this->db->where('status','1');
				$data = $this->db->get($table)->result();
				$datax = [];
				foreach ($data as $d) {
					$datax[] = $d->$value;
				}
        		echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeIntensif();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_insentif()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$nominal = $this->input->post('nominal');
		$tahun = $this->input->post('tahun');
		$old_insentif = $this->input->post('old_insentif');
		$karyawan = $this->input->post('karyawan');
		$keterangan = $this->input->post('keterangan');
		
		if ($kode != "") {
			$c_karyawan = $this->otherfunctions->checkValueAll($karyawan);
			if($c_karyawan){
				$karyawan = 'all';
			}else{
				$karyawan = implode(";", $karyawan);
			}
			$data = [
				'kode_insentif'=>$kode,
				'nama'=>$nama,
				'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
				'tahun'=>$tahun,
				'kode_old_insentif'=>$old_insentif,
				'id_karyawan'=>$karyawan,
				'keterangan'=>$keterangan,
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_insentif',$this->model_master->checkInsentifCode($kode));	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function edt_insentif(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$id=$this->input->post('id');
		if ($id != "") {
			$karyawan = $this->input->post('karyawan');
			$c_karyawan = $this->otherfunctions->checkValueAll($karyawan);
			if($c_karyawan){
				$karyawan = 'all';
			}else{
				$karyawan = implode(";", $karyawan);
			}
			$data=[
				'kode_insentif'=>$this->input->post('kode'),
				'nama'=>$this->input->post('nama'),
				'nominal'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal')),
				'tahun'=>$this->input->post('tahun'),
				'kode_old_insentif'=>$this->input->post('old_insentif'),
				'id_karyawan'=>$karyawan,
				'keterangan'=>$this->input->post('keterangan'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			/*cek data*/
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_insentif']) {
				$cek=$this->model_master->checkInsentifCode($data['kode_insentif']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_insentif',['id_insentif'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Detail Periode Penggajian--//
	public function view_periode_penggajian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_master->getListPeriodePenggajianDetail($kode);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_periode_detail,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$jml_bagian = count(explode(";", $d->id_bagian));
					$datax['data'][]=[
						$d->id_periode_detail,
						$d->kode_periode_detail,
						$d->nama_loker,
						// $d->nama_umk.'<br>('.$this->formatter->getFormatMoneyUser($d->tarif).')',
						$jml_bagian.' Bagian',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_periode_detail');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPeriodePenggajianDetail(null,['a.id_periode_detail'=>$id]);
				foreach ($data as $d) {
					if($mode == 'edit'){
						$bagian = explode(";", $d->id_bagian);
						$detail ='';
					}else{
						$table = '';
						$no = 1;
						if(!empty($d->id_bagian)){
							$bag = explode(';', $d->id_bagian);
							foreach ($bag as $key => $value) {
								$get_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian($value));
								$table .= '	<tr>
								<td>'.$no.'</td>
								<td>'.$get_bagian['nama'].'</td>
								<td>'.$get_bagian['nama_level_struktur'].'</td>
								</tr>';
								$no++;
							}
						}
						$detail = $table;
						$bagian = '';
					}
               
					$datax=[
						'id'=>$d->id_periode_detail,
						'kode'=>$d->kode_periode_detail,
						'induk_kode'=>$d->kode_periode_penggajian,
						'umk'=>($mode == 'edit') ? $d->kode_umk : $d->nama_umk.' ('.$this->formatter->getFormatMoneyUser($d->tarif).')',
						'loker'=>($mode == 'edit') ? $d->kode_loker : $d->nama_loker,
						'bagian'=>$bagian,
						'detail'=>$detail,
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
			}elseif ($usage == 'child') {
				$kode_loker = $this->input->post('kode_loker');
				/*get tarif umk*/
				$lokasi = $this->model_master->getTarifUmk(null,['a.loker'=>$kode_loker]);
				$sel_umk = '<option></option>';
				foreach ($lokasi as $l) {
					$sel_umk .= '<option value="'.$l->kode_tarif_umk.'">'.$l->nama.'</optiom>';
				}
				/*get bagian*/
				// $bagian = $this->model_master->getBagian(null,['a.kode_loker'=>$kode_loker]);
				// $sel_bagian = '<option></option>';
				// foreach ($bagian as $b) {
				// 	$sel_bagian .= '<option value="'.$b->id_bagian.'">'.$b->nama.'</optiom>';
				// }
				$bagian = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$kode_loker);
				$sel_bagian = '';
				$sel_bagian .= '<option value="all">Pilih Semua</option>';
				foreach ($bagian as $bkey => $bvalue) {
					if(!empty($bvalue)){
						$data_bagian = $this->model_master->getBagianRow(null,['a.kode_bagian'=>$bvalue]);
						$sel_bagian .= '<option value="'.$data_bagian['id_bagian'].'">'.$data_bagian['nama'].' ('.$data_bagian['nama_lokasi'].')</option>';
					}
				}
				/*result*/
				$datax = ['umk'=>$sel_umk,'bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'selectLevel') {
				// echo '<pre>';
				// print_r($this->dtroot);
				$data = $this->model_master->getLevelSelect2Filter($this->dtroot['adm']['level']);
        		echo json_encode($data);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeriodePenggajianDetail();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edt_periode_detail(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$loker = $this->input->post('loker');
		$umk = $this->input->post('umk');
		$induk_kode = $this->input->post('induk_kode');
		$bagian = $this->input->post('bagian');
		$c_bagian = $this->otherfunctions->checkValueAll($bagian);
		if($c_bagian){
			$bagianx = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$loker);
			$sel_bagian = [];
			foreach ($bagianx as $bkey => $bvalue) {
				if(!empty($bvalue)){
					$data_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian(null,['a.kode_bagian'=>$bvalue]));
					$sel_bagian[] = $data_bagian['id_bagian'];
				}
			}
			$bagian = implode(";",$sel_bagian);
		}else{
			$bagian = implode(";", $bagian);
		}
		
		if ($id != "") {
			$cek_periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$induk_kode]));
			$data=array(
				'kode_loker'=>$loker,
				'id_bagian'=>$bagian,
				'kode_umk'=>$umk,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			/* cek data */
			$old=$this->input->post('kode_old');
			if ($old != $kode) {
				$cek=$this->model_master->checkPeriodePenggajianDetailCode($kode);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'data_periode_penggajian_detail',['id_periode_detail'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_periode_detail(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode=$this->input->post('kode');
		$cek=$this->model_master->checkPeriodePenggajianDetailCode($kode);
		if(!empty($cek) || empty($kode)){
			$kode = $this->codegenerator->kodePeriodePenggajianDetail();
		}
		$induk_kode=$this->input->post('induk_kode');
		$umk = $this->input->post('umk');
		$kode_loker = $this->input->post('loker');
		$bagian = $this->input->post('bagian');
		$c_bagian = $this->otherfunctions->checkValueAll($bagian);
		if($c_bagian){
			$bagianx = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$kode_loker);
			$sel_bagian = [];
			foreach ($bagianx as $bkey => $bvalue) {
				if(!empty($bvalue)){
					$data_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian(null,['a.kode_bagian'=>$bvalue]));
					$sel_bagian[] = $data_bagian['id_bagian'];
				}
			}
			$bagianData = implode(";",$sel_bagian);
		}else{
			$bagianData = implode(";", $bagian);
		}
		if ($kode != "" || $induk_kode != "") {
			$cek_periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$induk_kode]));
			$data = [
				'kode_periode_detail'=>$kode,
				'kode_loker'=>$kode_loker,
				'kode_umk'=>$umk,
				'id_bagian'=>$bagianData,
				'kode_periode_penggajian'=>$induk_kode
			];			
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'data_periode_penggajian_detail');			
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Data Ritasi--//
	public function data_ritasi()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$kode = $this->input->post('kode');
				$periode = $this->input->post('periode');
				$minggu = $this->input->post('minggu');
				// if(($kode == 'all' || empty($periode)) || ($kode == 'search' && empty($periode))){
				// 	$data = $this->model_master->getListDataRitasi();
				// }else{
				// 	$data = $this->model_master->getListDataRitasi(['a.kode_periode_penggajian'=>$periode]);
				// }
				if($kode == 'all' && empty($periode)){
					$data = $this->model_master->getListDataRitasi(['a.kode_periode_penggajian'=>'q']);
				}elseif($kode == 'search' && !empty($periode)){
					$where = (!empty($minggu)) ? ['a.kode_periode_penggajian'=>$periode,'a.minggu'=>$minggu] : ['a.kode_periode_penggajian'=>$periode];
					$data = $this->model_master->getListDataRitasi($where);
				}else{
					$data = $this->model_master->getListDataRitasi();
				}
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_ritasi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['val_ritasi'])) {
						if(in_array($access['l_ac']['val_ritasi'], $access['access'])){
							if($d->validasi==2){
								$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need("'.$d->id_ritasi.'")><i class="fa fa-warning"></i> Perlu Validasi</button> ';
							}elseif($d->validasi==1){
								$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes("'.$d->id_ritasi.'")><i class="fa fa-check-circle"></i> Diizinkan</button> ';
							}elseif($d->validasi==0){
								$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no("'.$d->id_ritasi.'")><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
							}
						}else{
							$validasi = $this->otherfunctions->getStatusIzin($d->validasi);
						}
					}else{
						$validasi = null;
					}
					$datax['data'][]=[
						$d->id_ritasi,
						$d->nik,
						$d->nama,
						$this->formatter->getFormatMoneyUser($d->nominal),
						$this->formatter->getFormatMoneyUser($d->nominal_non_ppn),
						(!empty($d->rit)?$d->rit:$this->otherfunctions->getMark()),
						(!empty($d->rit_non_ppn)?$d->rit_non_ppn:$this->otherfunctions->getMark()),
						// (!empty($d->keterangan)?$d->keterangan:$this->otherfunctions->getMark()),
						(!empty($d->minggu)?$this->otherfunctions->getlistWeekRitasi($d->minggu):$this->otherfunctions->getMark()),
						$d->nama_periode.' ('.$d->nama_sistem_penggajian.')',
						$validasi,
						// $properties['tanggal'],
						// $properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_ritasi');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListDataRitasi(['a.id_ritasi'=>$id]);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_ritasi,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik,
						'nama'=>$d->nama,
						'rit'=>(!empty($d->rit)?$d->rit:$this->otherfunctions->getMark()),
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'rit_non'=>(!empty($d->rit_non_ppn)?$d->rit_non_ppn:$this->otherfunctions->getMark()),
						'nominal_non'=>$this->formatter->getFormatMoneyUser($d->nominal_non_ppn),
						'keterangan'=>(!empty($d->keterangan)?$d->keterangan:$this->otherfunctions->getMark()),
						'eketerangan'=>$d->keterangan,
						'minggu'=>$this->otherfunctions->getlistWeekRitasi($d->minggu),
						'minggu_periode'=>'Pada '.$this->otherfunctions->getlistWeekRitasi($d->minggu).' di Periode '.$d->nama_periode.' ('.$d->nama_sistem_penggajian.')',
						'e_minggu'=>$d->minggu,
						'periode'=>($mode == 'edit') ? $d->kode_periode_penggajian : $d->nama_periode.' ('.$d->nama_sistem_penggajian.')',
						'e_validasi'=>$d->validasi,
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
	public function import_ritasi()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		// echo '<pre>';
		// $extd = pathinfo($_FILES['file']['name']);
		// $config['file_name'] 		= 'Data_log_presensi.xls';
		// $config['allowed_types']    = 'xls|xlsx';
		// $config['upload_path']      = './application/document';
		// $this->load->library('upload', $config);
		// $datalogup = $this->upload->do_upload('file');
		// // $dt=$this->upload->data();
		// var_dump($this->upload->data());
		$periode = $this->input->post('periode');
		$minggu = $this->input->post('minggu');
		$data['properties']=[
			'post'=>'file',
			'data_post'=>$this->input->post('file', TRUE),
		];
		$sheet[0]=[
			'range_huruf'=>3,
			'row'=>2,
			'other'=>['kode_periode_penggajian'=>$periode,'minggu'=>$minggu],
			'table'=>'data_ritasi',
			'column_code'=>'nama',
			'usage'=>'import_ritasi',
			'column_properties'=>$this->model_global->getCreateProperties($this->admin),
			'column'=>[
				1=>'nama',2=>'nik',3=>'nominal',4=>'nominal_non_ppn',5=>'rit',6=>'rit_non_ppn',7=>'keterangan',
			],
		];
		$data['data']=$sheet;
		$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	public function edt_ritasi()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'rit'=>$this->input->post('rit'),
				'nominal'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal')),
				'rit_non_ppn'=>$this->input->post('rit_non'),
				'nominal_non_ppn'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal_non')),
				'keterangan'=>$this->input->post('keterangan'),
				'minggu'=>$this->input->post('minggu'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_ritasi',['id_ritasi'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function add_ritasi(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$karyawan=$this->input->post('karyawan');
		$rit=$this->input->post('rit');
		$rit_non=$this->input->post('rit_non');
		$nominal = $this->input->post('nominal');
		$nominal_non = $this->input->post('nominal_non');
		$periode = $this->input->post('periode');		
		$keterangan = $this->input->post('keterangan');
		$minggu = $this->input->post('minggu');
		if ($karyawan != "") {
			$emp = $this->model_karyawan->getEmployeeId($karyawan);
			$data = array(
				'id_karyawan'=>$karyawan,
				'nik'=>$emp['nik'],
				'nama'=>$emp['nama'],
				'rit'=>$rit,
				'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
				'rit_non_ppn'=>$rit_non,
				'nominal_non_ppn'=>$this->formatter->getFormatMoneyDb($nominal_non),
				'kode_periode_penggajian'=>$periode,
				'keterangan'=>$this->input->post('keterangan'),
				'minggu'=>$minggu,
			);
			// $cekRitasi = $this->model_master->getListDataRitasi(['a.id_karyawan'=>$karyawan,'a.kode_periode_penggajian'=>$periode,'a.minggu'=>$minggu,'a.keterangan'=>$keterangan],'active',true);
			$cekRitasi = $this->model_master->getListDataRitasi(['a.id_karyawan'=>$karyawan,'a.kode_periode_penggajian'=>$periode,'a.minggu'=>$minggu],'active',true);
			if(empty($cekRitasi)){
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'data_ritasi');
			}else{
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datae = $this->model_global->updateQueryNoMsgCallback($data,'data_ritasi',['id_karyawan'=>$karyawan,'kode_periode_penggajian'=>$periode,'minggu'=>$minggu, 'validasi !='=>'1']);
				// print_r($datae);
				if($datae){
					$datax = $this->messages->allGood(); 
				}else{					
					$datax = $this->messages->customWarning('Data Sudah Ada');
				}
			}
			// 	$emp = $this->model_karyawan->getEmployeeId($karyawan);
			// 	$data_in = array(
			// 		'id_karyawan'=>$karyawan,
			// 		'nik'=>$emp['nik'],
			// 		'nama'=>$emp['nama'],
			// 		'rit'=>$rit+$cekRitasi['rit'],
			// 		'nominal'=>($this->formatter->getFormatMoneyDb($nominal)+$cekRitasi['nominal']),
			// 		'rit_non_ppn'=>$rit_non+$cekRitasi['rit_non_ppn'],
			// 		'nominal_non_ppn'=>($this->formatter->getFormatMoneyDb($nominal_non)+$cekRitasi['nominal_non_ppn']),
			// 		'kode_periode_penggajian'=>$periode,
			// 	);
			// 	$data_in=array_merge($data_in,$this->model_global->getUpdateProperties($this->admin));
			// 	$datax = $this->model_global->updateQuery($data_in,'data_ritasi',['id_ritasi'=>$cekRitasi['id_ritasi']]);
			// }
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function validasi_ritasi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id_ritasi');
		$id_karyawan=$this->input->post('id_karyawan');
		$val_db=$this->input->post('validasi_db');
		$vali=$this->input->post('validasi');
		$data=['validasi'=>$vali];
		$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		$where=['id_ritasi'=>$id,'validasi'=>$val_db];
		$dbs=$this->model_global->updateQuery($data,'data_ritasi',$where);
		// echo '<pre>';
		// echo 'id = '.$id;echo '<br>';
		// echo 'id_karyawan = '.$id_karyawan;echo '<br>';
		// echo 'val_db = '.$val_db;echo '<br>';
		// echo 'vali = '.$vali;
		// $data = $this->model_master->getListDataRitasi(['a.id_ritasi'=>$id]);
		// print_r($data);
		// if($val_db==2 && $vali==1 || $val_db==0 && $vali==1){
		// 	$data=['validasi'=>$vali];
		// 	$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		// 	$where=['id_ritasi'=>$id,'validasi'=>$val_db];
		// 	$dbs=$this->model_global->updateQuery($data,'data_ritasi',$where);
		// }elseif($val_db==1 && $vali==0){
		// 	$data=['validasi'=>$vali];
		// 	$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		// 	$where=['id_ritasi'=>$id,'validasi'=>$val_db];
		// 	$dbs=$this->model_global->updateQuery($data,'data_ritasi',$where);
		// }else{
		// 	$data=['validasi'=>$vali];
		// 	$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		// 	$where=['id_ritasi'=>$id,'validasi'=>$val_db];
		// 	$dbs=$this->model_global->updateQuery($data,'data_ritasi',$where);
		// }
		echo json_encode($dbs);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Master BPJS--//
	public function master_bpjs()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_master->getListBpjs();
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_bpjs,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_bpjs,
						$d->kode_bpjs,
						$d->nama,
						$d->inisial,
						'<center>'.$d->bpjs_perusahaan.' %'.'</center>',
						'<center>'.$d->bpjs_karyawan.' %'.'</center>',
						// '<center>'.$d->tahun.'</center>',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_bpjs');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListBpjs(['a.id_bpjs'=>$id]);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_bpjs,
						'kode'=>$d->kode_bpjs,
						'nama'=>$d->nama,
						'inisial'=>$d->inisial,
						'bpjs_perusahaan'=>($mode == 'edit') ? $d->bpjs_perusahaan : $d->bpjs_perusahaan.' %',
						'bpjs_karyawan'=>($mode == 'edit') ? $d->bpjs_karyawan : $d->bpjs_karyawan.' %',
						'tahun'=>($mode == 'edit') ? $d->tahun : $d->tahun,
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
				$data = $this->codegenerator->kodebpjs();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function edt_bpjs()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'nama'=>$this->input->post('nama'),
				'inisial'=>$this->input->post('inisial'),
				'bpjs_perusahaan'=>$this->input->post('bpjs_perusahaan'),
				'bpjs_karyawan'=>$this->input->post('bpjs_karyawan'),
				'tahun'=>$this->input->post('tahun'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'master_bpjs',['id_bpjs'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_bpjs(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode=$this->input->post('kode');
		$nama=$this->input->post('nama');
		$inisial=$this->input->post('inisial');
		$bpjs_perusahaan = $this->input->post('bpjs_perusahaan');
		$bpjs_karyawan = $this->input->post('bpjs_karyawan');
		$tahun = $this->input->post('tahun');
		
		if ($kode != "") {
			$data = array(
				'kode_bpjs'=>$kode,
				'nama'=>$nama,
				'inisial'=>$inisial,
				'bpjs_perusahaan'=>$bpjs_perusahaan,
				'bpjs_karyawan'=>$bpjs_karyawan,
				'tahun'=>$tahun,
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_bpjs',$this->model_master->checkBpjsCode($kode));
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
			$status = $this->input->post('status');
			$cekBPJS=$this->model_master->getListBpjs(['a.status'=>$status]);
			if(empty($cekBPJS)){
				$datax = ['msg'=>'kosong'];
			}else{
				$datax=['msg'=>'ada_data'];
			}
		}elseif($usage == 'notif'){
			$datax = $this->messages->customFailure('Data Master Pada Tahun tersebut Kosong');
		}elseif($usage == 'data'){
			// $tahun = $this->input->post('tahun');
			$tahun_for = $this->input->post('tahun_for');
			$bulan_for = $this->input->post('bulan_for');
			if(empty($tahun_for) || empty($bulan_for)){
				$datax = $this->messages->customFailure('Inputan Tidak boleh Kosong');
			}else{
				if(in_array('all',$bulan_for)){
					$bulan = $this->formatter->getMonth();
					foreach ($bulan as $key => $val) {
						$cekDataMasterBPJS=$this->model_payroll->getListBpjsEmp(['a.tahun'=>$tahun_for,'a.bulan'=>$key],1);
						$dataMasterBPJS=$this->model_master->getListBpjs(['a.status'=>1]);
						$dataEmpAktif=$this->model_karyawan->listEmpActive();
						if(empty($cekDataMasterBPJS) || $cekDataMasterBPJS == null){
							if(!empty($dataMasterBPJS) && !empty($dataEmpAktif)){
								foreach ($dataEmpAktif as $dea) {
									$emp_single = $this->model_karyawan->getEmployeeId($dea->id_karyawan);
									$karyawan = $this->payroll->cekDataBPJSEmp($emp_single,$dataMasterBPJS,$key,$tahun_for);
									$data=array_merge($karyawan,$this->model_global->getCreateProperties($this->admin));
									$datax = $this->model_global->insertQuery($data,'data_bpjs');
								}
							}else{
								$datax = $this->messages->notValidParam();
							}
						}else{
							if(!empty($dataMasterBPJS) && !empty($dataEmpAktif)){
								foreach ($dataEmpAktif as $dead) {
									$emp_single = $this->model_karyawan->getEmployeeId($dead->id_karyawan);
									$karyawan = $this->payroll->cekDataBPJSEmp($emp_single,$dataMasterBPJS,$key,$tahun_for);
									$data=array_merge($karyawan,$this->model_global->getUpdateProperties($this->admin));
									$datax = $this->model_global->updateQuery($data,'data_bpjs',['bulan'=>$key,'tahun'=>$tahun_for,'id_karyawan'=>$dead->id_karyawan]);
								}
							}else{
								$datax = $this->messages->notValidParam();
							}
						}
					}
				}else{
					if(isset($bulan_for)){
						foreach ($bulan_for as $key => $val) {
							$cekDataMasterBPJS=$this->model_payroll->getListBpjsEmp(['a.tahun'=>$tahun_for,'a.bulan'=>$val],1);
							$dataMasterBPJS=$this->model_master->getListBpjs(null,'active');
							// print_r($dataMasterBPJS);
							$dataEmpAktif=$this->model_karyawan->listEmpActive();
							if(empty($cekDataMasterBPJS) || $cekDataMasterBPJS == null){
								if(!empty($dataMasterBPJS) && !empty($dataEmpAktif)){
									foreach ($dataEmpAktif as $deae) {
										$emp_single = $this->model_karyawan->getEmployeeId($deae->id_karyawan);
										$karyawan = $this->payroll->cekDataBPJSEmp($emp_single,$dataMasterBPJS,$val,$tahun_for);
										$data=array_merge($karyawan,$this->model_global->getCreateProperties($this->admin));
										$datax = $this->model_global->insertQuery($data,'data_bpjs');
									}
								}else{
									$datax = $this->messages->notValidParam();
								}
							}else{
								if(!empty($dataMasterBPJS) && !empty($dataEmpAktif)){
									foreach ($dataEmpAktif as $deaf) {
										$emp_single = $this->model_karyawan->getEmployeeId($deaf->id_karyawan);
										$karyawan = $this->payroll->cekDataBPJSEmp($emp_single,$dataMasterBPJS,$val,$tahun_for);
										$data=array_merge($karyawan,$this->model_global->getUpdateProperties($this->admin));
										$datax = $this->model_global->updateQuery($data,'data_bpjs',['bulan'=>$val,'tahun'=>$tahun_for,'id_karyawan'=>$deaf->id_karyawan]);
									}
								}else{
									$datax = $this->messages->notValidParam();
								}
							}
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
	//--Master BPJS Prosentase--//
	public function master_bpjs_prosentase()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_master->getListBpjsProsentase();
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
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
						$d->kode,
						$d->nama,
						// $d->inisial,
						'<center>'.$d->prosentase_perusahaan.' %'.'</center>',
						'<center>'.$d->prosentase_karyawan.' %'.'</center>',
						'<center>'.$d->tahun.'</center>',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListBpjsProsentase(['a.id'=>$id]);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						// 'inisial'=>$d->inisial,
						'prosentase_perusahaan'=>($mode == 'edit') ? $d->prosentase_perusahaan : $d->prosentase_perusahaan.' %',
						'prosentase_karyawan'=>($mode == 'edit') ? $d->prosentase_karyawan : $d->prosentase_karyawan.' %',
						'tahun'=>($mode == 'edit') ? $d->tahun : $d->tahun,
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
				$data = $this->codegenerator->kodebpjs();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function edt_bpjs_prosentase()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'kode'=>$this->input->post('kode'),
				'nama'=>$this->input->post('nama'),
				// 'inisial'=>$this->input->post('inisial'),
				'prosentase_perusahaan'=>$this->input->post('bpjs_perusahaan'),
				'prosentase_karyawan'=>$this->input->post('bpjs_karyawan'),
				'tahun'=>$this->input->post('tahun'),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'master_bpjs_prosentase',['id'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_bpjs_prosentase(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode=$this->input->post('kode');
		$nama=$this->input->post('nama');
		// $inisial=$this->input->post('inisial');
		$prosentase_perusahaan = $this->input->post('prosentase_perusahaan');
		$prosentase_karyawan = $this->input->post('prosentase_karyawan');
		$tahun = $this->input->post('tahun');
		
		if ($kode != "") {
			$data = array(
				'kode'=>$kode,
				'nama'=>$nama,
				// 'inisial'=>$inisial,
				'prosentase_perusahaan'=>$prosentase_perusahaan,
				'prosentase_karyawan'=>$prosentase_karyawan,
				'tahun'=>$tahun,
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_bpjs_prosentase',$this->model_master->checkBpjsProsentaseCode($kode));
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Master Pinjaman--//
	public function master_pinjaman()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListPinjaman();
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
					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$empKodeGaji = $this->model_karyawan->getPeriodePenggajianKar($d->id_karyawan)['kode_penggajian'];
					$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$d->kode_periode_penggajian]));
					$periodeHarian = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$d->kode_periode_penggajian]));
					if($empKodeGaji=='BULANAN'){
						$periodeGaji=$periode['nama'].' ('.$periode['nama_sistem_penggajian'].')';
					}else{
						$periodeGaji=$periodeHarian['nama'].' ('.$periodeHarian['nama_sistem_penggajian'].')';
					}
					$datax['data'][]=[
						$d->id_pinjaman,
						$d->kode_pinjaman,
						$d->nama,
						$emp['nama'],
						$this->formatter->getFormatMoneyUser($d->nominal),
						(!empty($d->angsuran_ke)) ? $d->angsuran_ke : 0,
						$periodeGaji,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pinjaman');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPinjaman(['a.id_pinjaman'=>$id]);
				foreach ($data as $d) {
					$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
					$empKodeGaji = $this->model_karyawan->getPeriodePenggajianKar($d->id_karyawan)['kode_penggajian'];
					$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$d->kode_periode_penggajian]));
					$periodeHarian = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$d->kode_periode_penggajian]));
					if($empKodeGaji=='BULANAN'){
						$periodeGaji=$periode['nama'].' ('.$periode['nama_sistem_penggajian'].')';
					}else{
						$periodeGaji=$periodeHarian['nama'].' ('.$periodeHarian['nama_sistem_penggajian'].')';
					}
					$selectKodePengB='';
					$selectKodePengH='';
					if($empKodeGaji=='BULANAN'){
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
					$selectKodePeng = ($empKodeGaji=='BULANAN')?$selectKodePengB:$selectKodePengH;
					$datax=[
						'id'=>$d->id_pinjaman,
						'kode'=>$d->kode_pinjaman,
						'nama'=>$d->nama,
						'karyawan'=>($mode == 'edit') ? $d->id_karyawan : $emp['nama'],
						'jabatan'=>($mode == 'edit') ? '' : $emp['nama_jabatan'],
						'bagian'=>($mode == 'edit') ? '' : $emp['bagian'],
						'loker'=>($mode == 'edit') ? '' : $emp['nama_loker'],
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'angsuran_ke'=>(!empty($d->angsuran_ke)) ? $d->angsuran_ke : 0,
						'tahun'=>$d->tahun,
						'keterangan'=>$d->keterangan,
						'periode'=>($mode == 'edit') ? $selectKodePeng : $periodeGaji,
						'periode_e'=>($mode == 'edit') ? $selectKodePeng : null,
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
				$data = $this->codegenerator->kodePinjaman();
        		echo json_encode($data);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'periodeKar') {
				$idkar = $this->input->post('idkar');
				$emp = $this->model_karyawan->getPeriodePenggajianKar($idkar)['kode_penggajian'];
				$select='';
				if($emp=='BULANAN'){
					$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
					if ($level > 1){
						$where['a.create_by'] = $this->admin;
					}
					$where['status_gaji'] = 0;
					$periode = $this->model_master->getListPeriodePenggajian(null, $where);
					$select.='<option></option>';
					foreach ($periode as $p) {
						$select.='<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
					}
					$value = null;
				}else{
					$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
					if ($level > 0){
						$where['a.create_by'] = $this->admin;
					}
					$where['status_gaji'] = 0;
					$periode = $this->model_master->getListPeriodePenggajianHarian(null, $where);
					$select.='<option></option>';
					foreach ($periode as $p) {
						$select.='<option value="'.$p->kode_periode_penggajian_harian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
					}
					$value = 'HARIAN';
				}
				$datax = ['select'=>$select,'value'=>$value];
        		echo json_encode($datax);
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
		$karyawan = $this->input->post('karyawan');
		$nominal = $this->input->post('nominal');
		$angsuran_ke = $this->input->post('angsuran_ke');
		$keterangan = $this->input->post('keterangan');
		$periode = $this->input->post('periode');

		if ($kode != "") {
			$data = array(
				'kode_pinjaman'=>$kode,
				'nama'=>$nama,
				'id_karyawan'=>$karyawan,
				'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
				'angsuran_ke'=>$angsuran_ke,
				'keterangan'=>$keterangan,
				'kode_periode_penggajian'=>$periode,
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'data_pinjaman',$this->model_master->checkPinjamanCode($kode));	
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
		$nominal = $this->input->post('nominal');
		$angsuran_ke = $this->input->post('angsuran_ke');
		$keterangan = $this->input->post('keterangan');
		$periode = $this->input->post('periode');

		if ($id != "") {
			$data=array(
				'kode_pinjaman'=>$kode,
				'nama'=>$nama,
				'id_karyawan'=>$karyawan,
				'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
				'angsuran_ke'=>$angsuran_ke,
				'keterangan'=>$keterangan,
				'kode_periode_penggajian'=>$periode,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_pinjaman']) {
				$cek=$this->model_master->checkPinjamanCode($data['kode_pinjaman']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'data_pinjaman',['id_pinjaman'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}


//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Master PTKP--//
	public function master_ptkp()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListPtkp();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_ptkp,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_ptkp,
						$d->kode_ptkp,
						$d->nama,
						$d->tahun,
						$d->status_ptk,
						$this->formatter->getFormatMoneyUser($d->tarif_per_tahun),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_ptkp');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPtkp(['a.id_ptkp'=>$id]);
				foreach ($data as $d) {
					$status_ptkp = $this->otherfunctions->getStatusPajakList()[$d->status_ptk];
					$datax=[
						'id'=>$d->id_ptkp,
						'kode'=>$d->kode_ptkp,
						'nama'=>$d->nama,
						'status_ptkp'=>($mode == 'edit') ? $d->status_ptk : $status_ptkp,
						'tarif'=>$this->formatter->getFormatMoneyUser($d->tarif_per_tahun),
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
				$data = $this->codegenerator->kodePtkp();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_ptkp()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$tahun = $this->input->post('tahun');
		$status_ptkp = $this->input->post('status_ptkp');
		$nominal = $this->input->post('nominal');

		if ($kode != "") {
			$data = array(
				'kode_ptkp'=>$kode,
				'nama'=>$nama,
				'tahun'=>$tahun,
				'status_ptk'=>$status_ptkp,
				'tarif_per_tahun'=>$this->formatter->getFormatMoneyDb($nominal),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_ptkp',$this->model_master->checkPtkpCode($kode));	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function edt_ptkp(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$tahun = $this->input->post('tahun');
		$status_ptkp = $this->input->post('status_ptkp');
		$nominal = $this->input->post('nominal');

		if ($id != "") {
			$data=array(
				'kode_ptkp'=>$kode,
				'nama'=>$nama,
				'tahun'=>$tahun,
				'status_ptk'=>$status_ptkp,
				'tarif_per_tahun'=>$this->formatter->getFormatMoneyDb($nominal),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_ptkp']) {
				$cek=$this->model_master->checkPtkpCode($data['kode_ptkp']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_ptkp',['id_ptkp'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Master PPH--//
	public function master_pph()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListPph();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pph,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$dari = ($d->dari == 0) ? 'Rp. 0,00' : $this->formatter->getFormatMoneyUser($d->dari);
					$sampai = ($d->sampai == 0) ? '> '.$this->formatter->getFormatMoneyUser($d->dari) : $this->formatter->getFormatMoneyUser($d->sampai);
					$datax['data'][]=[
						$d->id_pph,
						$d->kode_pph,
						$d->nama,
						$dari,
						$sampai,
						$d->tarif.' %',
						$d->non_npwp.' %',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pph');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPph(['a.id_pph'=>$id]);
				foreach ($data as $d) {
					$dari = ($d->dari == 0) ? 'Rp. 0,00' : $this->formatter->getFormatMoneyUser($d->dari);
					$sampai = ($d->sampai == 0) ? '> '.$this->formatter->getFormatMoneyUser($d->dari) : $this->formatter->getFormatMoneyUser($d->sampai);
					$datax=[
						'id'=>$d->id_pph,
						'kode'=>$d->kode_pph,
						'nama'=>$d->nama,
						'dari'=>$dari,
						'sampai'=>($mode == 'edit') ? ($d->sampai == 0) ? 'Rp. 0,00' : $this->formatter->getFormatMoneyUser($d->sampai) : $sampai,
						'tarif'=>($mode == 'edit') ? $d->tarif : $d->tarif.' %',
						'tarif_non_npwp'=>($mode == 'edit') ? $d->non_npwp : $d->non_npwp.' %',
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
				$data = $this->codegenerator->kodePph();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_pph()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$dari = $this->input->post('dari');
		$sampai = $this->input->post('sampai');
		$tarif = $this->input->post('tarif');
		$tarif_non_npwp = $this->input->post('tarif_non_npwp');
		$tahun = $this->input->post('tahun');

		if ($kode != "") {
			$data = array(
				'kode_pph'=>$kode,
				'nama'=>$nama,
				'dari'=>$this->formatter->getFormatMoneyDb($dari),
				'sampai'=>$this->formatter->getFormatMoneyDb($sampai),
				'tahun'=>$tahun,
				'tarif'=>$tarif,
				'non_npwp'=>$tarif_non_npwp,
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_pph',$this->model_master->checkPphCode($kode));	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function edt_pph(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$dari = $this->input->post('dari');
		$sampai = $this->input->post('sampai');
		$tarif = $this->input->post('tarif');
		$tahun = $this->input->post('tahun');
		$tarif_non_npwp = $this->input->post('tarif_non_npwp');

		if ($id != "") {
			$data=array(
				'kode_pph'=>$kode,
				'nama'=>$nama,
				'dari'=>$this->formatter->getFormatMoneyDb($dari),
				'sampai'=>$this->formatter->getFormatMoneyDb($sampai),
				'tahun'=>$tahun,
				'tarif'=>$tarif,
				'non_npwp'=>$tarif_non_npwp,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_pph']) {
				$cek=$this->model_master->checkPtkpCode($data['kode_pph']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_pph',['id_pph'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Master Tarif Jabatan--//
	public function master_tarif_jabatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListTarifJabatan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_tarif_jabatan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_tarif_jabatan,
						$d->kode_tarif_jabatan,
						$d->nama,
						$d->tarif.' %',
						$this->formatter->getFormatMoneyUser($d->maximal),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_tarif_jabatan');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListTarifJabatan(['a.id_tarif_jabatan'=>$id]);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_tarif_jabatan,
						'kode'=>$d->kode_tarif_jabatan,
						'nama'=>$d->nama,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->maximal),
						'tarif'=>($mode == 'edit') ? $d->tarif : $d->tarif.' %',
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
				$data = $this->codegenerator->kodeTarifJabatan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_tarif_jabatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$tarif = $this->input->post('tarif');
		$nominal = $this->input->post('nominal');

		if ($kode != "") {
			$data = array(
				'kode_tarif_jabatan'=>$kode,
				'nama'=>$nama,
				'tarif'=>$tarif,
				'maximal'=>$this->formatter->getFormatMoneyDb($nominal),
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_tarif_jabatan',$this->model_master->checkTarifJabatanCode($kode));	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function edt_tarif_jabatan(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$nama = $this->input->post('nama');
		$tarif = $this->input->post('tarif');
		$nominal = $this->input->post('nominal');

		if ($id != "") {
			$data=array(
				'kode_tarif_jabatan'=>$kode,
				'nama'=>$nama,
				'tarif'=>$tarif,
				'maximal'=>$this->formatter->getFormatMoneyDb($nominal),
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_tarif_jabatan']) {
				$cek=$this->model_master->checkTarifJabatanCode($data['kode_tarif_jabatan']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_tarif_jabatan',['id_tarif_jabatan'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Periode Penggajian--//
	public function periode_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		$kode_induk=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			$where=[];
			$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
			if ($level > 0){
				$where['a.create_by'] = $this->admin;
			}	
			if ($usage == 'view_all') {
				$data=$this->model_master->getListPeriodeLembur($where);
				// $data=$this->model_master->getListPeriodeLembur();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_periode_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
            //    if($d->tgl_selesai < date('Y-m-d')){
               if($d->status_gaji == 1){
               	$status_selesai = $this->otherfunctions->getLabelMark(null,'success','Sudah Selesai',null,null,'check');
               }else{
               	$status_selesai = $this->otherfunctions->getLabelMark(null,'danger','Belum Selesai');
               }
               $tanggal = '<label class="label label-primary" data-toggle="tooltip" title="Tanggal Mulai"><i class="fas fa-pen fa-fw"></i>'.$this->formatter->getDateMonthFormatUser($d->tgl_mulai).'</label><br>
               				<label class="label label-danger" data-toggle="tooltip" title="Tanggal Selesai"><i class="fa fa-edit fa-fw"></i>'.$this->formatter->getDateMonthFormatUser($d->tgl_selesai).'</label>';
               $get_loker = $this->model_master->getListPeriodeLemburDetail($d->kode_periode_lembur,null,null,1);
               $jml_bagian = 0;
               foreach ($get_loker as $gl) {
               	$jml_bagian = $jml_bagian+count(explode(";", $gl->id_bagian));
               }
					$datax['data'][]=[
						$d->id_periode_lembur,
						'<a href="'.base_url('pages/view_periode_lembur/').$this->codegenerator->encryptChar($d->kode_periode_lembur).'">'.$d->kode_periode_lembur.'</a>',
						$d->nama,
						$d->nama_sistem_penggajian,
						'<center>'.$tanggal.'</center>',
						$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						count($get_loker).' Lokasi',
						$jml_bagian.' Bagian',
						$status_selesai,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_periode_lembur');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPeriodeLembur(['a.id_periode_lembur'=>$id]);
				$datax=[];
				foreach ($data as $d) {
               if($d->tgl_selesai < date('Y-m-d')){
               	$status_selesai = $this->otherfunctions->getLabelMark(null,'danger','Sudah Selesai','','left');
               }else{
               	$status_selesai = $this->otherfunctions->getLabelMark(null,'success','Belum Selesai','','left');
               }
               $get_lokasi = $this->model_master->getListPeriodeLemburDetail($d->kode_periode_lembur,null,null,1);
               $div_detail = '';
               $no = 1;
               foreach ($get_lokasi as $gl) {
               	foreach (explode(";", $gl->id_bagian) as $key => $value) {
               		$get_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian($value));
               		$div_detail .= '	<tr>
													<td style="width:5%">'.$no.'</td>
													<td>'.$gl->nama_loker.'</td>
													<td>'.$get_bagian['nama'].'</td>
													<td>'.$get_bagian['nama_level_struktur'].'</td>
													</tr>';
							$no++;
               	}
               }
               if($mode == 'edit'){
               	$tgl_transfer = $this->formatter->getDateFormatUser($d->tgl_transfer);
               }else{
               	$tgl_transfer = $this->otherfunctions->getLabelMark($this->formatter->getDateFormatUser($d->tgl_transfer),'danger','Belum Ditransfer','','left');
               }
					$datax=[
						'id'=>$d->id_periode_lembur,
						'kode_periode_lembur'=>$d->kode_periode_lembur,
						'nama'=>$d->nama,
						'tgl_mulai'=>($mode == 'edit') ?  $this->formatter->getDateFormatUser($d->tgl_mulai) : $this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						'tgl_selesai'=>($mode == 'edit') ? $this->formatter->getDateFormatUser($d->tgl_selesai) : $this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						'tgl_transfer'=>$tgl_transfer,
						'total_transfer'=>($mode == 'edit') ? $d->total_transfer : $this->formatter->getFormatMoneyUser($d->total_transfer),
						'sistem_penggajian'=>($mode == 'edit') ? $d->kode_master_penggajian : $d->nama_sistem_penggajian,
						'detail'=>($mode != 'edit') ? $div_detail : '',
						'bulan_tahun'=>$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						'bulan'=>$d->bulan,
						'tahun'=>$d->tahun,
						'status_selesai'=>$status_selesai,
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
			}elseif($usage == 'periode_lama'){
				$kode = $this->input->post('kode_periode_lembur');
				$data=$this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode]);
				$datax = '';
				foreach ($data as $d) {
					$datax = explode(";", $d->id_bagian);
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeriodeLembur();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edt_periode_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode=$this->input->post('kode');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$bulan_old = $this->input->post('bulan_old');
		$tahun_old = $this->input->post('tahun_old');
		$cekDataOld = $this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode,'a.bulan'=>$bulan_old,'a.tahun'=>$tahun_old]);
		$cekData = $this->model_master->getListPeriodeLembur(['a.kode_periode_lembur !='=>$kode,'a.bulan'=>$bulan,'a.tahun'=>$tahun]);
		if ($id != "") {
			$data=array(
				'kode_periode_lembur'=>$this->input->post('kode'),
				'nama'=>$this->input->post('nama'),
				'tgl_mulai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
				'tgl_selesai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
				'kode_master_penggajian'=>$this->input->post('sistem_penggajian'),
				'bulan'=>$bulan,
				'tahun'=>$tahun,
			);
			if(!empty($cekDataOld)){
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_periode_lembur']) {
					$cek=$this->model_master->checkPeriodeLemburCode($data['kode_periode_lembur']);
				}else{
					$cek=false;
				}
				if($data['tgl_selesai'] > $data['tgl_mulai']){
					if(empty($cekData)){
						$datax = $this->model_global->updateQueryCC($data,'data_periode_lembur',['id_periode_lembur'=>$id],$cek);
					}else{
						$datax=$this->messages->customFailure('Data Bulan & Tahun tersebut Sudah Ada');
					}
				}else{
					$datax=$this->messages->notValidParam();
				}
			}else{
				$datax = $this->model_global->updateQuery($data,'data_periode_lembur',['id_periode_lembur'=>$id]);
			}
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_periode_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode');
		$nama = $this->input->post('nama');
		$mulai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
		$selesai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
		$old_periode = $this->input->post('old_periode');
		$sistem_penggajian = $this->input->post('sistem_penggajian');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		if ($kode != "") {
			$cekData = $this->model_master->getListPeriodeLembur(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.create_by'=>$this->admin]);
			if(empty($cekData)){
				if($selesai > $mulai){
						$data = array(
							'kode_periode_lembur'=>$kode,
							'nama'=>$nama,
							'tgl_mulai'=>$mulai,
							'tgl_selesai'=>$selesai,
							'kode_master_penggajian'=>$sistem_penggajian,
							'bulan'=>$bulan,
							'tahun'=>$tahun,
						);
						$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
						$datax = $this->model_global->insertQueryCC($data,'data_periode_lembur',$this->model_master->checkPeriodeLemburCode($kode));
				}else{
					$datax=$this->messages->notValidParam();
				}
			}else{
				$datax=$this->messages->customFailure('Data Bulan & Tahun tersebut Sudah Ada');
			}
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function delete_periode_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode_periode=$this->input->post('kode_periode');
		if ($id != "") {
			$kodeP = $this->model_master->getListPeriodeLembur(['a.id_periode_lembur'=>$id]);
			$kode = $this->otherfunctions->convertResultToRowArray($kodeP);
			$wh=['kode_periode_lembur'=>$kode['kode_periode_lembur']];
			$this->model_global->deleteQueryNoMsg('data_periode_lembur_detail',$wh);
			$where=['kode_periode'=>$kode['kode_periode_lembur']];
			$this->model_global->deleteQueryNoMsg('data_penggajian_lembur',$where);
			$datax=$this->model_global->deleteQuery('data_periode_lembur',['id_periode_lembur'=>$id]);
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function copy_periode_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode_periode');
		$nama = $this->input->post('nama');
		$mulai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
		$selesai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
		$cek_detail = $this->model_master->getListPeriodeLemburDetail($kode);
		$kodePeriodeLembur = $this->codegenerator->kodePeriodeLembur();
		$cekData = $this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$kode]);
		$periodeBefore=$this->otherfunctions->convertResultToRowArray($cekData);
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$cekDatath = $this->model_master->getListPeriodeLembur(['a.kode_old_periode'=>$kode,'a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.create_by'=>$this->admin]);
		if(empty($cekDatath)){
			$dataPeriode = [
				'kode_periode_lembur'   =>$kodePeriodeLembur,
				'nama'                  =>$nama,
				'tgl_mulai'             =>$mulai,
				'tgl_selesai'           =>$selesai,
				'kode_master_penggajian'=>$periodeBefore['kode_master_penggajian'],
				'status_gaji'           =>0,
				'bulan'                 =>$bulan,
				'tahun'                 =>$tahun,
				'kode_old_periode'=>$kode
			];
			$dataPeriode=array_merge($dataPeriode,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($dataPeriode,'data_periode_lembur',$this->model_master->checkPeriodeLemburCode($kodePeriodeLembur));
			foreach ($cek_detail as $cd) {
				$kodePeriodeDetail = $this->codegenerator->kodePeriodeLemburDetail();
				$dataDetail=[
					'kode_periode_detail'=>$kodePeriodeDetail,
					'kode_periode_lembur'=>$kodePeriodeLembur,
					'kode_umk'           =>$cd->kode_umk,
					'kode_loker'         =>$cd->kode_loker,
					'id_bagian'          =>$cd->id_bagian,
				];
				$dataDetail=array_merge($dataDetail,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($dataDetail,'data_periode_lembur_detail',$this->model_master->checkPeriodeLemburDetailCode($kodePeriodeDetail));
			}
		}else{
			$datax=$this->messages->customFailure('Data Bulan & Tahun tersebut Sudah Ada');
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Detail Periode Lembur--//
	public function view_periode_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_master->getListPeriodeLemburDetail($kode);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_periode_detail,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$jml_bagian = count(explode(";", $d->id_bagian));
					$datax['data'][]=[
						$d->id_periode_detail,
						$d->kode_periode_detail,
						$d->nama_loker,
						// $d->nama_umk.'<br>('.$this->formatter->getFormatMoneyUser($d->tarif).')',
						$jml_bagian.' Bagian',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_periode_detail');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPeriodeLemburDetail(null,['a.id_periode_detail'=>$id]);
				foreach ($data as $d) {
					if($mode == 'edit'){
						$bagian = explode(";", $d->id_bagian);
						$detail = '';
					}else{
						$table = '';
						$no = 1;
						$bag = explode(';', $d->id_bagian);
						foreach ($bag as $key => $value) {
							$get_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian($value));
							$table .= '	<tr>
							<td style="width: 5%;">'.$no.'</td>
							<td>'.$get_bagian['nama'].'</td>
							<td>'.$get_bagian['nama_level_struktur'].'</td>
							</tr>';
							$no++;
						}
						$detail = $table;
						$bagian = '';
					}
               
					$datax=[
						'id'=>$d->id_periode_detail,
						'kode'=>$d->kode_periode_detail,
						'umk'=>($mode == 'edit') ? $d->kode_umk : $d->nama_umk.' ( '.$this->formatter->getFormatMoneyUser($d->tarif).' )',
						'loker'=>($mode == 'edit') ? $d->kode_loker : $d->nama_loker,
						'bagian'=>$bagian,
						'detail'=>$detail,
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
			}elseif ($usage == 'child') {
				// $kode_loker = $this->input->post('kode_loker');
				// $lokasi = $this->model_master->getTarifUmk(null,['a.loker'=>$kode_loker]);
				// $sel_umk = '<option></option>';
				// foreach ($lokasi as $l) {
				// 	$sel_umk .= '<option value="'.$l->kode_tarif_umk.'">'.$l->nama.'</optiom>';
				// }
				// $bagian = $this->model_master->getBagian(null,['a.kode_loker'=>$kode_loker]);
				// $sel_bagian = '<option></option>';
				// foreach ($bagian as $b) {
				// 	$sel_bagian .= '<option value="'.$b->id_bagian.'">'.$b->nama.'</optiom>';
				// }
				// $datax = ['umk'=>$sel_umk,'bagian'=>$sel_bagian];
    			// echo json_encode($datax);
				$kode_loker = $this->input->post('kode_loker');
				/*get tarif umk*/
				$lokasi = $this->model_master->getTarifUmk(null,['a.loker'=>$kode_loker]);
				$sel_umk = '<option></option>';
				foreach ($lokasi as $l) {
					$sel_umk .= '<option value="'.$l->kode_tarif_umk.'">'.$l->nama.'</optiom>';
				}
				/*get bagian*/
				// $bagian = $this->model_master->getBagian(null,['a.kode_loker'=>$kode_loker]);
				// $sel_bagian = '<option></option>';
				// foreach ($bagian as $b) {
				// 	$sel_bagian .= '<option value="'.$b->id_bagian.'">'.$b->nama.'</optiom>';
				// }

				$bagian = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$kode_loker);
				$sel_bagian = '<option value="all">Pilih Semua</option>';
				foreach ($bagian as $bkey => $bvalue) {
					$data_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian(null,['a.kode_bagian'=>$bvalue]));
					$sel_bagian .= '<option value="'.$data_bagian['id_bagian'].'">'.$data_bagian['nama'].'</optiom>';
				}
				/*result*/
				$datax = ['umk'=>$sel_umk,'bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeriodeLemburDetail();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edt_periode_lembur_detail(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$id=$this->input->post('id');
		$kode=$this->input->post('kode');
		$induk_kode=$this->input->post('induk_kode');
		$umk = $this->input->post('umk');
		$kode_loker = $this->input->post('loker');
		$bagian = $this->input->post('bagian');
		$c_bagian = $this->otherfunctions->checkValueAll($bagian);
		if($c_bagian){
			$bagianx = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$kode_loker);
			$sel_bagian = [];
			foreach ($bagianx as $bkey => $bvalue) {
				$data_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian(null,['a.kode_bagian'=>$bvalue]));
				$sel_bagian[] = $data_bagian['id_bagian'];
			}
			$bagian = implode(";",$sel_bagian);
		}else{
			$bagian = implode(";", $bagian);
		}
		if ($id != "") {
			$data=array(
				'kode_periode_detail'=>$kode,
				'kode_umk'=>$umk,
				'kode_loker'=>$kode_loker,
				'id_bagian'=>$bagian,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_periode_detail']) {
				$cek=$this->model_master->checkPeriodeLemburDetailCode($data['kode_periode_detail']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'data_periode_lembur_detail',['id_periode_detail'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_periode_lembur_detail(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode=$this->input->post('kode');
		$induk_kode=$this->input->post('induk_kode');
		$umk = $this->input->post('umk');
		$kode_loker = $this->input->post('loker');
		$bagian = $this->input->post('bagian');
		/* if(empty($bagian)){
			$get_bagian = $this->model_master->getBagian(null,['a.kode_loker'=>$kode_loker]);
			$id_bagian = [];
			foreach ($get_bagian as $g) {
				if($g->kode_bagian != 'BAG001' && $g->kode_bagian != 'BAG002'){
					$id_bagian[] = $g->id_bagian;
				}
			}
		}else{
			$id_bagian = $bagian;
		} */
		$c_bagian = $this->otherfunctions->checkValueAll($bagian);
		if($c_bagian){
			$bagianx = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$kode_loker);
			$sel_bagian = [];
			foreach ($bagianx as $bkey => $bvalue) {
				$data_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian(null,['a.kode_bagian'=>$bvalue]));
				$sel_bagian[] = $data_bagian['id_bagian'];
			}
			$bagian = implode(";",$sel_bagian);
		}else{
			$bagian = implode(";", $bagian);
		}
		if ($kode != "" || $induk_kode != "") {
			$data = array(
				'kode_periode_detail'=>$kode,
				'kode_periode_lembur'=>$induk_kode,
				'kode_umk'=>$umk,
				'kode_loker'=>$kode_loker,
				'id_bagian'=>$bagian,
			);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'data_periode_lembur_detail',$this->model_master->checkPeriodeLemburDetailCode($kode));
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Master Petugas Payroll--//
	public function master_petugas_payroll()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListPetugasPayrollWhere(null, 'a.id_petugas_payroll');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_petugas_payroll,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					// if($d->kode_peserta != 'all'){
					// 	$jml_peserta = count(explode(";",$d->kode_peserta));
					// }else{
					// 	$jbt_ptg = $this->model_master->getListJabatanWhere(['a.kode_level !='=>null], null, 1,'mls.squence asc, a.nama asc');
					// 	$jml_peserta = count($jbt_ptg);
					// }
					if($d->id_karyawan != 'all'){
						if(!empty($d->id_karyawan)){
							$jml_peserta = count(explode(";",$d->id_karyawan)).' Karyawan';
						}else{
							$jml_peserta = '<label class="label label-danger" style="font-size:14px;"><i class="fa fa-times-circle"></i> Tidak Ada Data</label>';
						}
					}else{
						// $jbt_ptg = $this->model_master->getListJabatanWhere(['a.kode_level !='=>null], null, 1,'mls.squence asc, a.nama asc');
						$jbt_ptg = $this->model_karyawan->getEmployeeAllActive();
						$jml_peserta = count($jbt_ptg).' Karyawan';
					}
					
					$datax['data'][]=[
						$d->id_petugas_payroll,
						$d->kode_petugas_payroll,
						$d->nama_petugas,
						$jml_peserta,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_petugas_payroll');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPetugasPayrollWhere(['a.id_petugas_payroll'=>$id]);
				foreach ($data as $d) {
               $div_detail ='';
					// if($d->kode_peserta != 'all'){
					// 	$x_peserta = explode(";",$d->kode_peserta);
					// 	$no = 1;
					// 	foreach ($x_peserta as $key => $value) {
					// 		$jabatan = $this->model_master->getListJabatanWhere(['a.kode_jabatan'=>$value]);
					// 		foreach ($jabatan as $j) {
					// 			$div_detail .= '<tr>
					// 			<td style="width: 5%;">'.$no.'</td>
					// 			<td>'.$j->nama.'</td>
					// 			<td>'.$j->nama_atasan.'</td>
					// 			</tr>';
					// 			$no++;
					// 		}
					// 	}
					// }else{
					// 	$no = 1;
					// 	$jbt_ptg = $this->model_master->getListJabatanWhere(['a.kode_level !='=>null], null, 1,'mls.squence asc, a.nama asc');
					// 	foreach ($jbt_ptg as $j) {
					// 		$div_detail .= '<tr>
					// 		<td style="width: 5%;">'.$no.'</td>
					// 		<td>'.$j->nama.'</td>
					// 			<td>'.$j->nama_atasan.'</td>
					// 		</tr>';
					// 		$no++;
					// 	}
					// }
					if(!empty($d->id_karyawan)){
						$x_peserta = explode(";",$d->id_karyawan);
						$no = 1;
						foreach ($x_peserta as $key => $value) {
							$karyawan = $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$value], true);
							// foreach ($karyawan as $j) {
								$div_detail .= '<tr>
								<td style="width: 5%;">'.$no.'</td>
								<td>'.$karyawan['nama'].'</td>
								<td>'.$karyawan['nama_jabatan'].'</td>
								<td>'.$karyawan['nama_bagian'].'</td>
								<td>'.$karyawan['nama_loker'].'</td>
								</tr>';
								$no++;
							// }
						}
					}
					$datax=[
						'id'=>$d->id_petugas_payroll,
						'kode'=>$d->kode_petugas_payroll,
						'petugas'=>($mode == 'view') ? $d->nama_petugas : $d->kode_petugas,
						// 'peserta'=>($mode == 'view') ? $div_detail : explode(";",$d->kode_peserta),
						'peserta'=>($mode == 'view') ? $div_detail : explode(";",$d->id_karyawan),
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
				$data = $this->codegenerator->kodePetugasPayroll();
        		echo json_encode($data);
			}elseif ($usage == 'select_employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2forPetugasJabatan(null,true);
        		echo json_encode($data);
			}elseif ($usage == 'select2') {
				$data=$this->model_master->getListPetugasPayrollWhere(null,null,1);
				$pack = [];
				foreach ($data as $d) {
					$pack[$d->kode_petugas_payroll]=(($d->nama_karyawan)?$d->nama_karyawan:null).' - ('.$d->nama_petugas.')';
				}
        		echo json_encode($pack);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_petugas_payroll()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode = $this->input->post('kode');
		$petugas = $this->input->post('petugas');
		$peserta = $this->input->post('peserta');
		$idKar = $this->input->post('id_karyawan');

		if ($kode != "") {
			$c_idKar = $this->otherfunctions->checkValueAll($idKar);
			if($c_idKar){
				$sel_idKar = [];
				$datakar=$this->model_karyawan->getEmployeeAllActive();
				foreach ($datakar as $d) {
					$sel_idKar[] = $d->id_karyawan;
				}
				// $idKar = 'all';
				$idKaryawan = implode(";",$sel_idKar);
			}else{
				$idKaryawan = implode(";", $idKar);
			}
			// $c_peserta = $this->otherfunctions->checkValueAll($peserta);
			// if($c_peserta){
			// 	$sel_jabatan = [];
			// 	$jbt_ptg = $this->model_master->getListJabatanWhere(['a.kode_level !='=>null], null, 1,'mls.squence asc, a.nama asc');
			// 	foreach ($jbt_ptg as $j) {
			// 		$sel_jabatan[] = $j->kode_jabatan;
			// 	}
			// 	// $peserta = 'all';
			// 	$peserta = implode(";",$sel_jabatan);
			// }else{
			// 	$peserta = implode(";", $peserta);
			// }

			$cek=$this->model_master->checkPetugasPayrollCode($kode);
			if($cek){
				$kode = $this->codegenerator->kodePetugasPayroll();
			}
			
			$data = [
				'kode_petugas_payroll'=>$kode,
				'kode_petugas'=>$petugas,
				// 'kode_peserta'=>$peserta,
				'id_karyawan'=>$idKaryawan,
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_petugas_payroll',$this->model_master->checkPetugasPayrollCode($kode));	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function edt_petugas_payroll(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$petugas = $this->input->post('petugas');
		// $peserta = $this->input->post('peserta');
		$idKar = $this->input->post('id_karyawan');

		if ($id != "") {
			// $c_peserta = $this->otherfunctions->checkValueAll($peserta);
			// if($c_peserta){
			// 	$sel_jabatan = [];
			// 	$jbt_ptg = $this->model_master->getListJabatanWhere(['a.kode_level !='=>null], null, 1,'mls.squence asc, a.nama asc');
			// 	foreach ($jbt_ptg as $j) {
			// 		$sel_jabatan[] = $j->kode_jabatan;
			// 	}
			// 	// $peserta = 'all';
			// 	$peserta = implode(";",$sel_jabatan);
			// }else{
			// 	$peserta = implode(";", $peserta);
			// }
			$c_idKar = $this->otherfunctions->checkValueAll($idKar);
			if($c_idKar){
				$sel_idKar = [];
				$datakar=$this->model_karyawan->getEmployeeAllActive();
				foreach ($datakar as $d) {
					$sel_idKar[] = $d->id_karyawan;
				}
				// $idKar = 'all';
				$idKaryawan = implode(";",$sel_idKar);
			}else{
				$idKaryawan = implode(";", $idKar);
			}
			$data = [
				'kode_petugas_payroll'=>$kode,
				'kode_petugas'=>$petugas,
				// 'kode_peserta'=>$peserta,
				'id_karyawan'=>$idKaryawan,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			/*cek data*/
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_petugas_payroll']) {
				$cek=$this->model_master->checkPetugasPayrollCode($data['kode_petugas_payroll']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_petugas_payroll',['id_petugas_payroll'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--Master Petugas PPH--//
	public function master_petugas_pph()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListPetugasPPHWhere(null,'a.id_petugas_pph');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_petugas_pph,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if($d->id_karyawan != 'all'){
						if(!empty($d->id_karyawan)){
							$jml_peserta = count(explode(";",$d->id_karyawan)).' Karyawan';
						}else{
							$jml_peserta = '<label class="label label-danger" style="font-size:14px;"><i class="fa fa-times-circle"></i> Tidak Ada Data</label>';
						}
					}else{
						$jbt_ptg = $this->model_karyawan->getEmployeeAllActive();
						$jml_peserta = count($jbt_ptg).' Karyawan';
					}
					
					$datax['data'][]=[
						$d->id_petugas_pph,
						$d->kode_petugas_pph,
						$d->nama_petugas,
						$jml_peserta,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_petugas_pph');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPetugasPPHWhere(['a.id_petugas_pph'=>$id]);
				foreach ($data as $d) {
               		$div_detail ='';
					if(!empty($d->id_karyawan)){
						$x_peserta = explode(";",$d->id_karyawan);
						$no = 1;
						foreach ($x_peserta as $key => $value) {
							$karyawan = $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$value], true);
							// foreach ($karyawan as $j) {
								$div_detail .= '<tr>
								<td style="width: 5%;">'.$no.'</td>
								<td>'.$karyawan['nama'].'</td>
								<td>'.$karyawan['nama_jabatan'].'</td>
								<td>'.$karyawan['nama_bagian'].'</td>
								<td>'.$karyawan['nama_loker'].'</td>
								</tr>';
								$no++;
							// }
						}
					}
					$datax=[
						'id'=>$d->id_petugas_pph,
						'kode'=>$d->kode_petugas_pph,
						'petugas'=>($mode == 'view') ? $d->nama_petugas : $d->kode_petugas,
						'peserta'=>($mode == 'view') ? $div_detail : explode(";",$d->id_karyawan),
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
				$data = $this->codegenerator->kodePetugasPPH();
        		echo json_encode($data);
			}elseif ($usage == 'select_employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2forPetugasJabatan(null,true);
        		echo json_encode($data);
			}elseif ($usage == 'select2') {
				$data=$this->model_master->getListPetugasPPHWhere(null,null,1);
				$pack = [];
				foreach ($data as $d) {
					$pack[$d->kode_petugas_pph]=(($d->nama_karyawan)?$d->nama_karyawan:null).' - ('.$d->nama_petugas.')';
				}
        		echo json_encode($pack);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_petugas_pph()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode = $this->input->post('kode');
		$petugas = $this->input->post('petugas');
		$peserta = $this->input->post('peserta');
		$idKar = $this->input->post('id_karyawan');

		if ($kode != "") {
			$c_idKar = $this->otherfunctions->checkValueAll($idKar);
			if($c_idKar){
				$sel_idKar = [];
				$datakar=$this->model_karyawan->getEmployeeAllActive();
				foreach ($datakar as $d) {
					$sel_idKar[] = $d->id_karyawan;
				}
				$idKaryawan = implode(";",$sel_idKar);
			}else{
				$idKaryawan = implode(";", $idKar);
			}
			$cek=$this->model_master->checkPetugasPPHCode($kode);
			if($cek){
				$kode = $this->codegenerator->kodePetugasPPH();
			}			
			$data = [
				'kode_petugas_pph'=>$kode,
				'kode_petugas'=>$petugas,
				'id_karyawan'=>$idKaryawan,
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_petugas_pph',$this->model_master->checkPetugasPPHCode($kode));	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edt_petugas_pph(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$petugas = $this->input->post('petugas');
		$idKar = $this->input->post('id_karyawan');
		if ($id != "") {
			$c_idKar = $this->otherfunctions->checkValueAll($idKar);
			if($c_idKar){
				$sel_idKar = [];
				$datakar=$this->model_karyawan->getEmployeeAllActive();
				foreach ($datakar as $d) {
					$sel_idKar[] = $d->id_karyawan;
				}
				$idKaryawan = implode(";",$sel_idKar);
			}else{
				$idKaryawan = implode(";", $idKar);
			}
			$data = [
				'kode_petugas_pph'=>$kode,
				'kode_petugas'=>$petugas,
				// 'kode_peserta'=>$peserta,
				'id_karyawan'=>$idKaryawan,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			/*cek data*/
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_petugas_pph']) {
				$cek=$this->model_master->checkPetugasPPHCode($data['kode_petugas_pph']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_petugas_pph',['id_petugas_pph'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//=============== MASTER PETUGAS LEMBUR ============================
	public function master_petugas_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListPetugasLemburWhere(null, 'a.id_petugas_lembur');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_petugas_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if($d->id_karyawan != 'all'){
						if(!empty($d->id_karyawan)){
							$jml_peserta = count(explode(";",$d->id_karyawan)).' Karyawan';
						}else{
							$jml_peserta = '<label class="label label-danger" style="font-size:14px;"><i class="fa fa-times-circle"></i> Tidak Ada Data</label>';
						}
					}else{
						$jbt_ptg = $this->model_karyawan->getEmployeeAllActive();
						$jml_peserta = count($jbt_ptg).' Karyawan';
					}					
					$datax['data'][]=[
						$d->id_petugas_lembur,
						$d->kode_petugas_lembur,
						$d->nama_petugas,
						$jml_peserta,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_petugas_lembur');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPetugasLemburWhere(['a.id_petugas_lembur'=>$id]);
				foreach ($data as $d) {
               $div_detail ='';
					if(!empty($d->id_karyawan)){
						$x_peserta = explode(";",$d->id_karyawan);
						$no = 1;
						foreach ($x_peserta as $key => $value) {
							$karyawan = $this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$value], true);
							$div_detail .= '<tr>
							<td style="width: 5%;">'.$no.'</td>
							<td>'.$karyawan['nama'].'</td>
							<td>'.$karyawan['nama_jabatan'].'</td>
							<td>'.$karyawan['nama_bagian'].'</td>
							<td>'.$karyawan['nama_loker'].'</td>
							</tr>';
							$no++;
						}
					}
					$datax=[
						'id'=>$d->id_petugas_lembur,
						'kode'=>$d->kode_petugas_lembur,
						'petugas'=>($mode == 'view') ? $d->nama_petugas : $d->kode_petugas,
						'peserta'=>($mode == 'view') ? $div_detail : explode(";",$d->id_karyawan),
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
				$data = $this->codegenerator->kodePetugasPayrollLembur();
        		echo json_encode($data);
			}elseif ($usage == 'select_employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2forPetugasJabatan(null,true);
        		echo json_encode($data);
			}elseif ($usage == 'select2') {
				$data=$this->model_master->getListPetugasLemburWhere(null,null,1);
				$pack = [];
				foreach ($data as $d) {
					$pack[$d->kode_petugas_lembur]=(($d->nama_karyawan)?$d->nama_karyawan:null).' - ('.$d->nama_petugas.')';
				}
        		echo json_encode($pack);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_petugas_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode = $this->input->post('kode');
		$petugas = $this->input->post('petugas');
		$peserta = $this->input->post('peserta');
		$idKar = $this->input->post('id_karyawan');

		if ($kode != "") {
			$c_idKar = $this->otherfunctions->checkValueAll($idKar);
			if($c_idKar){
				$sel_idKar = [];
				$datakar=$this->model_karyawan->getEmployeeAllActive();
				foreach ($datakar as $d) {
					$sel_idKar[] = $d->id_karyawan;
				}
				$idKaryawan = implode(";",$sel_idKar);
			}else{
				$idKaryawan = implode(";", $idKar);
			}
			$cek=$this->model_master->checkPetugasPayrollLemburCode($kode);
			if($cek){
				$kode = $this->codegenerator->kodePetugasPayrollLembur();
			}
			
			$data = [
				'kode_petugas_lembur'=>$kode,
				'kode_petugas'=>$petugas,
				'id_karyawan'=>$idKaryawan,
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_petugas_lembur',$this->model_master->checkPetugasPayrollLemburCode($kode));	
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edt_petugas_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$petugas = $this->input->post('petugas');
		$idKar = $this->input->post('id_karyawan');

		if ($id != "") {
			$c_idKar = $this->otherfunctions->checkValueAll($idKar);
			if($c_idKar){
				$sel_idKar = [];
				$datakar=$this->model_karyawan->getEmployeeAllActive();
				foreach ($datakar as $d) {
					$sel_idKar[] = $d->id_karyawan;
				}
				$idKaryawan = implode(";",$sel_idKar);
			}else{
				$idKaryawan = implode(";", $idKar);
			}
			$data = [
				'kode_petugas_lembur'=>$kode,
				'kode_petugas'=>$petugas,
				'id_karyawan'=>$idKaryawan,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_petugas_lembur']) {
				$cek=$this->model_master->checkPetugasPayrollLemburCode($data['kode_petugas_lembur']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_petugas_lembur',['id_petugas_lembur'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Periode Penggajian Harian--//
	public function periode_penggajian_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			$id_admin = $this->admin;
			if ($usage == 'view_all') {
				$data=$this->model_master->getPeriodePenggajianHarian(['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'HARIAN']);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_periode_penggajian_harian,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
               	if($d->status_gaji == 1){
               		$status_selesai = $this->otherfunctions->getLabelMark(null,'success','Sudah Selesai',null,null,'check');
               	}else{
               		$status_selesai = $this->otherfunctions->getLabelMark(null,'danger','Belum Selesai');
               	}
				$tanggal = '<label class="label label-primary" data-toggle="tooltip" title="Tanggal Mulai">
										<i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateMonthFormatUser($d->tgl_mulai).'
									</label><br>
									<label class="label label-danger" data-toggle="tooltip" title="Tanggal Selesai">
										<i class="fas fa-times fa-fw"></i>'.$this->formatter->getDateMonthFormatUser($d->tgl_selesai).'
									</label>';

               $get_loker = $this->model_master->getListPeriodePenggajianHarianDetail($d->kode_periode_penggajian_harian);
               $jml_bagian = 0;
               foreach ($get_loker as $gl) {
               	$jml_bagian = $jml_bagian+count(explode(";", $gl->id_bagian));
               }
					$datax['data'][]=[
						$d->id_periode_penggajian_harian,
						'<a href="'.base_url('pages/view_periode_penggajian_harian/').$this->codegenerator->encryptChar($d->kode_periode_penggajian_harian).'">'.$d->kode_periode_penggajian_harian.'</a>',
						$d->nama,
						$d->nama_sistem_penggajian,
						'<center>'.$tanggal.'</center>',
						$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						count($get_loker).' Lokasi',
						$jml_bagian.' Bagian',
						$status_selesai,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_periode_penggajian_harian');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getPeriodePenggajianHarian(['a.id_periode_penggajian_harian'=>$id]);
				foreach ($data as $d) {
               if($d->tgl_selesai < date('Y-m-d')){
               	$status_selesai = $this->otherfunctions->getLabelMark(null,'danger','Sudah Selesai','','left');
               }else{
               	$status_selesai = $this->otherfunctions->getLabelMark(null,'success','Belum Selesai','','left');
               }
               $get_lokasi = $this->model_master->getListPeriodePenggajianHarianDetail($d->kode_periode_penggajian_harian);
               $div_detail = '';
               $no = 1;
               foreach ($get_lokasi as $gl) {
               	foreach (explode(";", $gl->id_bagian) as $key => $value) {
               		$get_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian($value));
               		$div_detail .= '	<tr>
													<td>'.$no.'</td>
													<td>'.$gl->nama_loker.'</td>
													<td>'.$get_bagian['nama'].'</td>
													<td>'.$get_bagian['nama_level_struktur'].'</td>
													</tr>';
							$no++;
               	}
               }
               if($mode == 'edit'){
               	$tgl_transfer = $this->formatter->getDateFormatUser($d->tgl_transfer);
               }else{
               	$tgl_transfer = $this->otherfunctions->getLabelMark($this->formatter->getDateFormatUser($d->tgl_transfer),'danger','Belum Ditransfer','','left');
               }
					$datax=[
						'id'=>$d->id_periode_penggajian_harian,
						'kode_periode_penggajian'=>$d->kode_periode_penggajian_harian,
						'nama'=>$d->nama,
						'tgl_mulai'=>($mode == 'edit') ?  $this->formatter->getDateFormatUser($d->tgl_mulai) : $this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						'tgl_selesai'=>($mode == 'edit') ? $this->formatter->getDateFormatUser($d->tgl_selesai) : $this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						'tgl_transfer'=>$tgl_transfer,
						'total_transfer'=>($mode == 'edit') ? $d->total_transfer : $this->formatter->getFormatMoneyUser($d->total_transfer),
						'sistem_penggajian'=>($mode == 'edit') ? $d->kode_master_penggajian : $d->nama_sistem_penggajian,
						'detail'=>($mode != 'edit') ? $div_detail : '',
						'bulan_tahun'=>$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						'bulan'=>$d->bulan,
						'tahun'=>$d->tahun,
						'status_selesai'=>$status_selesai,
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
			}elseif($usage == 'periode_lama'){
				$kode = $this->input->post('kode_periode_penggajian_harian');
				$data=$this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$kode]);
				$datax = '';
				foreach ($data as $d) {
					$datax = explode(";", $d->id_bagian);
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeriodePenggajianHarian();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edt_periode_penggajian_harian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$kode = $this->input->post('kode');
			$nama = $this->input->post('nama');
			$mulai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
			$selesai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
			$kode_old = $this->input->post('kode_old');
			$sistem_penggajian = $this->input->post('sistem_penggajian');
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$bulan_old = $this->input->post('bulan_old');
			$tahun_old = $this->input->post('tahun_old');
			$cekDataOld = $this->model_master->getListPeriodePenggajianHarian(null,['a.kode_periode_penggajian_harian'=>$kode,'a.bulan'=>$bulan_old,'a.tahun'=>$tahun_old]);
			$cekData = $this->model_master->getListPeriodePenggajianHarian(null,['a.kode_periode_penggajian_harian !='=>$kode,'a.bulan'=>$bulan,'a.tahun'=>$tahun]);
			$data = [
				'nama'=>$nama,
				'tgl_mulai'=>$mulai,
				'tgl_selesai'=>$selesai,
				'kode_master_penggajian'=>$sistem_penggajian,
				'bulan'=>$bulan,
				'tahun'=>$tahun,
			];
			if(!empty($cekDataOld)){
				if ($kode_old != $kode) {
					$cek=$this->model_master->checkPeriodePenggajianHarianCode($kode);
				}else{
					$cek=false;
				}
				if($selesai > $mulai){
					if(empty($cekData)){
						$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
						$datax = $this->model_global->updateQueryCC($data,'data_periode_penggajian_harian',['kode_periode_penggajian_harian'=>$kode],$cek);
					}else{
						$datax=$this->messages->customFailure('Data Bulan & Tahun tersebut Sudah Ada');
					}
				}else{
					$datax=$this->messages->notValidParam();
				}
			}else{
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'data_periode_penggajian_harian',['kode_periode_penggajian_harian'=>$kode]);
			}
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function add_periode_penggajian_harian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode=$this->input->post('kode');
		$cek_kode = $this->model_master->checkPeriodePenggajianHarianCode($kode);
		if(empty($cek_kode)){
			$kode = $this->codegenerator->kodePeriodePenggajianHarian();
		}
		$nama = $this->input->post('nama');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$mulai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
		$selesai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
		$old_periode = $this->input->post('old_periode');
		$sistem_penggajian = $this->input->post('sistem_penggajian');
		if ($kode != "") {
			// $cekData = $this->model_master->getListPeriodePenggajianHarian(null,['a.bulan'=>$bulan,'a.tahun'=>$tahun]);
			// if(empty($cekData)){
				if($selesai > $mulai){
					$data = [
						'nama'=>$nama,
						'tgl_mulai'=>$mulai,
						'tgl_selesai'=>$selesai,
						'bulan'=>$bulan,
						'tahun'=>$tahun,
						'kode_master_penggajian'=>$sistem_penggajian,
						'kode_periode_penggajian_harian'=>$kode
					];
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQueryCC($data,'data_periode_penggajian_harian',$this->model_master->checkPeriodePenggajianHarianCode($kode));
				}else{
					$datax=$this->messages->notValidParam();
				}
			// }else{
			// 	$datax=$this->messages->customFailure('Data Bulan & Tahun tersebut Sudah Ada');
			// }
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function copy_periode_penggajian_harian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode_periode');
		$nama = $this->input->post('nama');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$mulai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
		$selesai = $this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
		$kodePeriodePenggajian = $this->codegenerator->kodePeriodePenggajianHarian();
		$cekData = $this->model_master->getListPeriodePenggajianHarian('active',['a.kode_periode_penggajian_harian'=>$kode]);
		$periodeBefore=$this->otherfunctions->convertResultToRowArray($cekData);
		// $cekDatath = $this->model_master->getListPeriodePenggajianHarian(null,['a.bulan'=>$bulan,'a.tahun'=>$tahun]);
		// if(empty($cekDatath)){
			$dataPeriode = [
				'kode_periode_penggajian_harian'=>$kodePeriodePenggajian,
				'nama'                          =>$nama,
				'tgl_mulai'                     =>$mulai,
				'tgl_selesai'                   =>$selesai,
				'kode_master_penggajian'        =>$periodeBefore['kode_master_penggajian'],
				'status_gaji'                   =>0,
				'bulan'                         =>$bulan,
				'tahun'                         =>$tahun,
			];
			$dataPeriode=array_merge($dataPeriode,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($dataPeriode,'data_periode_penggajian_harian',$this->model_master->checkPeriodePenggajianCode($kodePeriodePenggajian));
			$cek_detail = $this->model_master->getListPeriodePenggajianHarianDetail($kode);
			foreach ($cek_detail as $cd) {
				$kodePeriodeDetail = $this->codegenerator->kodePeriodePenggajianHarianDetail();
				$dataDetail=[
					'kode_periode_detail'           =>$kodePeriodeDetail,
					'kode_periode_penggajian_harian'=>$kodePeriodePenggajian,
					'kode_umk'                      =>$cd->kode_umk,
					'kode_loker'                    =>$cd->kode_loker,
					'id_bagian'                     =>$cd->id_bagian,
				];
				$dataDetail=array_merge($dataDetail,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($dataDetail,'data_periode_penggajian_harian_detail',$this->model_master->checkPeriodePenggajianDetailCode($kodePeriodeDetail));
			}
		// }else{
		// 	$datax=$this->messages->customFailure('Data Bulan & Tahun tersebut Sudah Ada');
		// }
		echo json_encode($datax);
	}

	function del_periode_penggajian_harian(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$kode=$this->input->post('kode');
		if ($id != "" || $kode != "") {
			$del_data = $this->model_global->deleteQuery('data_periode_penggajian_harian',['id_periode_penggajian_harian'=>$id]);
			if($del_data['status_data']){
				$this->model_global->deleteQueryNoMsg('data_penggajian_harian',['kode_periode'=>$kode]);
				$del_data = $this->model_global->deleteQuery('data_periode_penggajian_harian_detail',['kode_periode_penggajian_harian'=>$kode]);
				if($del_data['status_data']){
					$datax=$this->messages->delGood(); 
				}else{
					$datax=$this->messages->customFailure('Detail Data Gagal Terhapus.'); 
				}
			}else{
				$datax=$this->messages->notValidParam(); 
			}
		}
		echo json_encode($datax);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
	//--Detail Periode Penggajian--//
	public function view_periode_penggajian_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_master->getListPeriodePenggajianHarianDetail($kode);
				$access = $this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_periode_detail,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$jml_bagian = count(explode(";", $d->id_bagian));
					$datax['data'][]=[
						$d->id_periode_detail,
						$d->kode_periode_detail,
						$d->nama_loker,
						// $d->nama_umk.'<br>('.$this->formatter->getFormatMoneyUser($d->tarif).')',
						$jml_bagian.' Bagian',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_periode_detail');
				$mode = $this->input->post('mode');
				$data=$this->model_master->getListPeriodePenggajianHarianDetail(null,['a.id_periode_detail'=>$id]);
				foreach ($data as $d) {
					if($mode == 'edit'){
						$bagian = explode(";", $d->id_bagian);
						$detail ='';
					}else{
						$table = '';
						$no = 1;
						$bag = explode(';', $d->id_bagian);
						foreach ($bag as $key => $value) {
							$get_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian($value));
							$table .= '	<tr>
							<td style="width: 5%;">'.$no.'</td>
							<td>'.$get_bagian['nama'].'</td>
							<td>'.$get_bagian['nama_level_struktur'].'</td>
							</tr>';
							$no++;
						}
						$detail = $table;
						$bagian = '';
					}
               
					$datax=[
						'id'=>$d->id_periode_detail,
						'kode'=>$d->kode_periode_detail,
						'induk_kode'=>$d->kode_periode_penggajian_harian,
						'umk'=>($mode == 'edit') ? $d->kode_umk : $d->nama_umk.' ('.$this->formatter->getFormatMoneyUser($d->tarif).')',
						'loker'=>($mode == 'edit') ? $d->kode_loker : $d->nama_loker,
						'bagian'=>$bagian,
						'detail'=>$detail,
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
			}elseif ($usage == 'child') {
				$kode_loker = $this->input->post('kode_loker');
				/*get tarif umk*/
				$lokasi = $this->model_master->getTarifUmk(null,['a.loker'=>$kode_loker]);
				$sel_umk = '<option></option>';
				foreach ($lokasi as $l) {
					$sel_umk .= '<option value="'.$l->kode_tarif_umk.'">'.$l->nama.'</optiom>';
				}
				/*get bagian*/
				// $bagian = $this->model_master->getBagian(null,['a.kode_loker'=>$kode_loker]);
				// $sel_bagian = '<option></option>';
				// foreach ($bagian as $b) {
				// 	$sel_bagian .= '<option value="'.$b->id_bagian.'">'.$b->nama.'</optiom>';
				// }

				$bagian = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$kode_loker);
				$sel_bagian = '<option value="all">Pilih Semua</option>';
				foreach ($bagian as $bkey => $bvalue) {
					$data_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian(null,['a.kode_bagian'=>$bvalue]));
					$sel_bagian .= '<option value="'.$data_bagian['id_bagian'].'">'.$data_bagian['nama'].'</optiom>';
				}
				/*result*/
				$datax = ['umk'=>$sel_umk,'bagian'=>$sel_bagian];
        		echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeriodePenggajianHarianDetail();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edt_periode_detail_harian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$id=$this->input->post('id');
		$kode = $this->input->post('kode');
		$loker = $this->input->post('loker');
		$umk = $this->input->post('umk');
		$induk_kode = $this->input->post('induk_kode');
		$bagian = $this->input->post('bagian');
		$c_bagian = $this->otherfunctions->checkValueAll($bagian);
		if($c_bagian){
			$bagianx = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$loker);
			$sel_bagian = [];
			foreach ($bagianx as $bkey => $bvalue) {
				$data_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian(null,['a.kode_bagian'=>$bvalue]));
				$sel_bagian[] = $data_bagian['id_bagian'];
			}
			$bagian = implode(";",$sel_bagian);
		}else{
			$bagian = implode(";", $bagian);
		}
		
		if ($id != "") {
			$cek_periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$induk_kode]));
			$data=array(
				'kode_loker'=>$loker,
				'id_bagian'=>$bagian,
				'kode_umk'=>$umk,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			/* cek data */
			$old=$this->input->post('kode_old');
			if ($old != $kode) {
				$cek=$this->model_master->checkPeriodePenggajianHarianDetailCode($kode);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'data_periode_penggajian_harian_detail',['id_periode_detail'=>$id],$cek);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	function add_periode_detail_harian(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');

		$kode=$this->input->post('kode');
		$cek=$this->model_master->checkPeriodePenggajianHarianDetailCode($kode);
		if(!empty($cek) || empty($kode)){
			$kode = $this->codegenerator->kodePeriodePenggajianHarianDetail();
		}
		$induk_kode=$this->input->post('induk_kode');
		$umk = $this->input->post('umk');
		$kode_loker = $this->input->post('loker');
		$bagian = $this->input->post('bagian');
		$c_bagian = $this->otherfunctions->checkValueAll($bagian);
		if($c_bagian){
			$bagianx = $this->payroll->getBagianFromPetugasPayroll($this->dtroot['adm']['id_karyawan'],$kode_loker);
			$sel_bagian = [];
			foreach ($bagianx as $bkey => $bvalue) {
				$data_bagian = $this->otherfunctions->convertResultToRowArray($this->model_master->getBagian(null,['a.kode_bagian'=>$bvalue]));
				$sel_bagian[] = $data_bagian['id_bagian'];
			}
			$bagian = implode(";",$sel_bagian);
		}else{
			$bagian = implode(";", $bagian);
		}
		
		if ($kode != "" || $induk_kode != "") {
			$cek_periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajianHarian(['a.kode_periode_penggajian_harian'=>$induk_kode]));
			$data = [
				'kode_periode_detail'=>$kode,
				'kode_loker'=>$kode_loker,
				'kode_umk'=>$umk,
				'id_bagian'=>$bagian,
				'kode_periode_penggajian_harian'=>$induk_kode
			];
			
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'data_periode_penggajian_harian_detail');
			
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	// ============================================= BACKUP & RESTORE ====================================================
	public function data_backup()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListDataBackup();
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
					$file = str_replace('application/document/backup_db/','',$d->file);
					$datax['data'][]=[
						$d->id,
						'<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($d->file)).'">'.$d->nama.'</a>',
						$file,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_master->getDataBackup($id);
				foreach ($data as $d) {
					$file = str_replace('application/document/backup_db/','',$d->file);
					$datax=[
						'id'=>$d->id,
						'file'=>$file,
						'file_l'=>$d->file,
						'nama_val'=>$d->nama,
						'nama'=>'<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($d->file)).'">'.$d->nama.'</a><br><span class="text-sm">*Klik untuk download file</span>',
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
	public function backup_db()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$dir=APPPATH.'document/backup_db/';
		$dirDB='application/document/backup_db/';
		$this->load->dbutil();
		// $backup = $this->dbutil->backup();
		$this->load->helper('file');
		// write_file($dir.'mybackup.gz', $backup);
		// $this->load->helper('download');
		// force_download('mybackup.gz', $backup);
		$nama = $this->input->post('nama');
		$jenis = $this->input->post('jenis');
		if($jenis == 'custom'){
			$tabel_input = $this->input->post('tabel');
		}elseif($jenis == 'kebutuhan'){
			$tabel_input = [
				0=>'karyawan',
				1=>'data_denda',
				2=>'data_denda_angsuran',
				3=>'data_jadwal_kerja',
				4=>'data_lembur',
				5=>'data_izin_cuti_karyawan',
			];
		}elseif($jenis == 'hasil'){
			$tabel_input = [
				0=>'data_penggajian',
				1=>'data_penggajian_harian',
				2=>'data_penggajian_lembur',
				3=>'data_penggajian_pph',
				4=>'data_penggajian_tunjangan',
				5=>'data_periode_lembur',
				6=>'data_periode_lembur_detail',
				7=>'data_periode_penggajian',
				8=>'data_periode_penggajian_detail',
				9=>'data_periode_penggajian_harian',
				10=>'data_periode_penggajian_harian_detail',
			];
		}
		print_r($tabel_input);
		$tabel=[];
		if(in_array('all',$tabel_input)){
			$tb_db=$this->db->list_tables();
			foreach ($tb_db as $keydb => $valuedb) {
				$tabel[]=$valuedb;
			}
		}else{
			foreach ($tabel_input as $key => $value) {
				$tabel[]=$value;
			}
		}
		$tanggal = str_replace('-','',$this->date);
		$tanggal = str_replace(' ','',$tanggal);
		$tanggal = str_replace(':','',$tanggal);
		$prefs = [
			'tables'     => $tabel,
			'ignore'     => ['data_backup','data_restore'],
			'format'     => 'txt',
			'filename'   => $nama.'_'.$tanggal.'.sql',
			'add_drop'   => TRUE,
			'add_insert' => TRUE,
			'newline'    => "\n"
		];
		$backup = $this->dbutil->backup($prefs);
		write_file($dir.$prefs['filename'], $backup);
		$data = [
				'nama'=>$nama,
				'file'=>$dirDB.$prefs['filename'],
			];
		$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
		$datax = $this->model_global->insertQuery($data,'data_backup');
		echo json_encode($datax);
	}
	function edit_backup_db(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$data=[
			'nama'=>$this->input->post('nama'),
		];
		$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		$datax = $this->model_global->updateQuery($data,'data_backup',['id'=>$id]);
		echo json_encode($datax);
	}
	function import_restore(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$other=[
			'nama'=>ucwords($this->input->post('nama')),
		];
		$other=array_merge($other,$this->model_global->getCreateProperties($this->admin));
		$data=[
			'post'=>'file',
			'data_post'=>$this->input->post('file', TRUE),
			'table'=>'data_restore',
			'column'=>'file', 
			'usage'=>'insert',
			'otherdata'=>$other,
		];
		// print_r($data);
		$datax=$this->filehandler->doUpload($data,'restore');
		echo json_encode($datax);
	}
	public function data_restore()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListDataRestore();
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
					// if (isset($access['l_ac']['del'])) {
					// 	$delete = (in_array($access['l_ac']['del'], $access['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal_u('.$d->id.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					// }else{
					// 	$delete = null;
					// }
					// $info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_u('.$d->id.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					if($d->status_restore == 0){
						$restore = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=status_restore('.$d->id.')><i class="fa fa-upload" data-toggle="tooltip" title="Restore Data"></i></button> ';
					}else{
						$restore = null;
					}
					$properties['aksi']=str_replace('view_modal', 'view_modal_restore', $properties['aksi']);
					$properties['status']=str_replace('do_status', 'do_status_restore', $properties['status']);
					$properties['aksi']=str_replace('delete_modal', 'delete_modal_restore', $properties['aksi']);
					$file = str_replace('application/document/restore_db/','',$d->file);
					$datax['data'][]=[
						$d->id,
						'<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($d->file)).'">'.$d->nama.'</a>',
						$file,
						$this->otherfunctions->getStatusRestoreKey($d->status_restore),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$restore,
						// $info.$restore.$delete,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_master->getDataRestore($id);
				foreach ($data as $d) {
					$file = str_replace('application/document/restore_db/','',$d->file);
					$datax=[
						'id'=>$d->id,
						'file'=>$file,
						'file_l'=>$d->file,
						'nama_val'=>$d->nama,
						'nama'=>'<a href="'.base_url('global_control/file_download/'.$this->codegenerator->encryptChar($d->file)).'">'.$d->nama.'</a><br><span class="text-sm">*Klik untuk download file</span>',
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
	function edit_restore_db(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$data=[
			'nama'=>$this->input->post('nama'),
		];
		$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		$datax = $this->model_global->updateQuery($data,'data_restore',['id'=>$id]);
		echo json_encode($datax);
	}
	public function restore_db()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$param=$this->input->post('param');
		$file=$this->model_master->getDataRestore($id,'row')['file'];
		$isi_file = file_get_contents($file);
		$datax=$this->dbhandler->getExplodeFromInsert($isi_file);
		foreach ($datax as $dkey => $value) {
			unset($value['data'][0]);
			if($param == 'insert'){
				$data=$this->model_global->insertQuery($value['data'],$value['table']);
				if($data){
					$datax = $this->messages->customGood('Data Berhasil Di Restore');
				}else{
					$datax = $this->messages->allFailure();
				}
			}else{
				$data=$this->model_global->updateQuery($value['data'],$value['table'],[$value['id_primary']=>$value['value_primary']]);
				if($data){
					$datax = $this->messages->customGood('Data Berhasil Di Perbarui');
				}else{
					$datax = $this->messages->allFailure();
				}
			}
		}
		$this->model_global->updateQuery(['status_restore'=>1],'data_restore',['id'=>$id]);
		echo json_encode($datax);
	}
	//=================================================BLOCK CHANGE=================================================//
	//===MASTER DATA PENILAIAN BEGIN===//
	//--------------------------------------------------------------------------------------------------------------//
	//Master Jenis Batasan Poin
	public function master_jenis_batasan_poin()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListBatasanPoin();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_batasan_poin,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tb=[];
					for ($i=1; $i <=$this->max_range ; $i++) { 
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
					$datax['data'][]=[
						$d->id_batasan_poin,
						$d->kode_batasan_poin,
						$d->nama,
						(($d->lebih_max)?'Ya':'Tidak'),
						'<div style="max-height:300px;overflow:auto"><table class="table table-bordered table-striped table-responsive"><thead><tr class="bg-blue"><th>Poin</th><th>Satuan</th></tr></thead><tbody>'.$tb.'</tbody></table></div>',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_batasan_poin');
				$data=$this->model_master->getBatasanPoin($id);
				$datax=[];
				foreach ($data as $d) {
					$tb=[];
					for ($i=1; $i <=$this->max_range ; $i++) { 
						$poin='poin_'.$i;
						$satuan='satuan_'.$i;
						if ($d->$satuan != '') {
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
						'id'=>$d->id_batasan_poin,
						'kode_batasan_poin'=>$d->kode_batasan_poin,
						'nama'=>$d->nama,
						'lebih_max'=>(($d->lebih_max)?'Ya':'Tidak'),
						'lebih_max_val'=>$d->lebih_max,
						'tr_table'=>$tb,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update)
					];
					for ($i=1;$i<=$this->max_range;$i++){
						$p='poin_'.$i;
						$s='satuan_'.$i;
						if ($d->$s == '') {
							$d->$p=null;
						}
						$datax[$p]=$d->$p;
						$datax[$s]=$d->$s;
					}
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeBatasanPoin();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_jenis_batasan_poin(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_batasan_poin'=>$kode,
				'nama'=>ucwords($this->input->post('nama')),
				'lebih_max'=>$this->input->post('lebih_max'),
			];
			for ($i=1;$i<=$this->max_range;$i++){
				$p='poin_'.$i;
				$s='satuan_'.$i;
				$data[$p]=$this->input->post($p);
				$data[$s]=$this->input->post($s);
				if ($data[$p] == null) {
					$data[$s]=null;
				}
			}
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_jenis_batasan_poin',$this->model_master->checkBatasanPoinCode($kode));		
		}else{
			$datax=$this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	function edt_jenis_batasan_poin(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		$kode=$this->input->post('kode');
		if ($id != "") {
			$data=[
				'kode_batasan_poin'=>$kode,
				'nama'=>ucwords($this->input->post('nama')),
				'lebih_max'=>$this->input->post('lebih_max'),
			];
			$data_to_kpi=[];
			for ($i=1;$i<=$this->max_range;$i++){
				$p='poin_'.$i;
				$s='satuan_'.$i;
				$data[$p]=$this->input->post($p);
				$data[$s]=$this->input->post($s);
				if ($data[$p] == null) {
					$data[$s]=null;
				}
				$data_to_kpi[$p]=$data[$p];
				$data_to_kpi[$s]=$data[$s];
			}
			$data_to_kpi['lebih_max']=$data['lebih_max'];
			$data_to_trans=$data;
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_batasan_poin']) {
				$cek=$this->model_master->checkBatasanPoinCode($data['kode_batasan_poin']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_jenis_batasan_poin',['id_batasan_poin'=>$id],$cek);
			$this->model_global->updateQueryNoMsg($data_to_kpi,'master_kpi',['id_jenis_batasan_poin'=>$id]);
			if (!$cek){
				$data_to_kpi['id_jenis_batasan_poin']=$id;
				$this->model_concept->updateFromMasterKPI($data_to_kpi);//update to concept
				$this->model_agenda->updateAgendaFromConceptMaster($data_to_kpi,'master');//update to agenda
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
		//--------------------------------------------------------------------------------------------------------------//
		//Master KPI
		public function master_kpi()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$usage=$this->uri->segment(3);
			if ($usage == null) {
				echo json_encode($this->messages->notValidParam());
			}else{
				if ($usage == 'view_all') {
					$data=$this->model_master->getListKpi();
					$access=$this->codegenerator->decryptChar($this->input->post('access'));
					$no=1;
					$datax['data']=[];
					foreach ($data as $d) {
						$var=[
							'id'=>$d->id_kpi,
							'create'=>$d->create_date,
							'update'=>$d->update_date,
							'access'=>$access,
							'status'=>$d->status,
						];
						$properties=$this->otherfunctions->getPropertiesTable($var);
						$datax['data'][]=[
							$d->id_kpi,
							$d->kode_kpi,
							$d->kpi,
							$d->detail_rumus,
							($d->unit != null) ? $d->unit : $this->otherfunctions->getMark(null),
							($d->nama_batasan_poin != null) ? $d->nama_batasan_poin : $this->otherfunctions->getMark(null),
							($d->sifat != null) ? $this->otherfunctions->getSifatKpi($d->sifat) : $this->otherfunctions->getMark(null),
							$this->otherfunctions->getYesNo($d->kpi_utama),
							$properties['status'],
							$properties['aksi'],
						];
						$no++;
					}
					echo json_encode($datax);
				}elseif ($usage == 'view_one') {
					$id = $this->input->post('id_kpi');
					$data=$this->model_master->getKpi($id);
					foreach ($data as $d) {
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
							'id'=>$d->id_kpi,
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
							'kode_bagian'=>$d->kode_bagian,
							'min'=>$d->min,
							'max'=>$d->max,
							'lebih_max'=>(($d->lebih_max)?'Ya':'Tidak'),
							'lebih_max_val'=>$d->lebih_max,
							'batasan_poin'=>$d->id_jenis_batasan_poin,
							'nama_batasan_poin'=>(!empty($d->nama_batasan_poin)) ? $d->nama_batasan_poin : $this->otherfunctions->getMark(),
							'bagian'=>(!empty($d->nama_bagian)) ? $d->nama_bagian : $this->otherfunctions->getMark(),
							'tr_table'=>$tb,
							'status'=>$d->status,
							'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
							'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
							'create_by'=>$d->create_by,
							'update_by'=>$d->update_by,
							'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
							'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
							'kpi_utama'=>$this->otherfunctions->getYesNo($d->kpi_utama),
							'e_kpi_utama'=>$d->kpi_utama,
						];
						for ($i=1;$i<=$this->max_range;$i++){
							$p='poin_'.$i;
							$s='satuan_'.$i;
							if ($d->$s == null) {
								$d->$p=null;
							}
							$datax[$p]=$d->$p;
							$datax[$s]=$d->$s;
						}
					}
					echo json_encode($datax);
				}elseif ($usage == 'kode') {
					$data = $this->codegenerator->kodeKpi();
					echo json_encode($data);
				}else{
					echo json_encode($this->messages->notValidParam());
				}
			}
		}
		function add_kpi(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$kode=$this->input->post('kode');
			if ($kode != "") {
				$data=[
					'kode_kpi'=>$kode,
					'kpi'=>ucwords($this->input->post('kpi')),
					'rumus'=>$this->input->post('rumus'),
					'unit'=>$this->input->post('unit'),
					'detail_rumus'=>$this->input->post('detail_rumus'),
					'sumber_data'=>$this->input->post('sumber_data'),
					'kaitan'=>$this->input->post('kaitan'),
					'jenis_satuan'=>$this->input->post('jenis_satuan'),
					'sifat'=>$this->input->post('sifat'),
					'cara_menghitung'=>strtoupper($this->input->post('cara_menghitung')),
					'kode_bagian'=>$this->input->post('bagian'),
					'min'=>$this->input->post('min'),
					'max'=>$this->input->post('max'),
					'id_jenis_batasan_poin'=>$this->input->post('batasan_poin'),
					'lebih_max'=>$this->input->post('lebih_max'),
					'kpi_utama'=>$this->input->post('kpi_utama'),
				];
				if ($data['sifat'] == 'MAX') {
					$data['min']=null;
					$data['max']=null;
				}
				for ($i=1;$i<=$this->max_range;$i++){
					$p='poin_'.$i;
					$s='satuan_'.$i;
					$data[$p]=$this->input->post($p);
					$data[$s]=$this->input->post($s);
					if ($data[$p] == null) {
						$data[$s]=null;
					}
				}
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'master_kpi',$this->model_master->checkKpiCode($kode));		
			}else{
				$datax=$this->messages->notValidParam(); 
			}
			echo json_encode($datax);
		}
		function edt_kpi(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$id=$this->input->post('id');
			$kode=$this->input->post('kode');
			if ($id != "") {
				$data=[
					'kode_kpi'=>$kode,
					'kpi'=>ucwords($this->input->post('kpi')),
					'rumus'=>$this->input->post('rumus'),
					'unit'=>$this->input->post('unit'),
					'detail_rumus'=>$this->input->post('detail_rumus'),
					'sumber_data'=>$this->input->post('sumber_data'),
					'kaitan'=>$this->input->post('kaitan'),
					'jenis_satuan'=>$this->input->post('jenis_satuan'),
					'sifat'=>$this->input->post('sifat'),
					'cara_menghitung'=>strtoupper($this->input->post('cara_menghitung')),
					'kode_bagian'=>$this->input->post('bagian'),
					'min'=>$this->input->post('min'),
					'max'=>$this->input->post('max'),
					'id_jenis_batasan_poin'=>$this->input->post('batasan_poin'),
					'lebih_max'=>$this->input->post('lebih_max'),
					'kpi_utama'=>$this->input->post('kpi_utama'),
				];
				if ($data['sifat'] == 'MAX') {
					$data['min']=null;
					$data['max']=null;
				}
				for ($i=1;$i<=$this->max_range;$i++){
					$p='poin_'.$i;
					$s='satuan_'.$i;
					$data[$p]=$this->input->post($p);
					$data[$s]=$this->input->post($s);
					if ($data[$p] == null) {
						$data[$s]=null;
					}
				}
				$data_to_trans=$data;
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				//cek data
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_kpi']) {
					$cek=$this->model_master->checkKpiCode($data['kode_kpi']);
				}else{
					$cek=false;
				}
				$datax = $this->model_global->updateQueryCC($data,'master_kpi',['id_kpi'=>$id],$cek);
				if (!$cek){
					unset($data_to_trans['kode_bagian']);
					$this->model_concept->updateFromMasterKPI($data_to_trans);//update to concept
					$this->model_agenda->updateAgendaFromConceptMaster($data_to_trans,'master');//update to agenda
				}
			}else{
				$datax=$this->messages->notValidParam();
			}
			echo json_encode($datax);
		}
		function export_kpi()
		{
			$data['properties']=[
				'title'=>"Template Master KPI",
				'subject'=>"Template Master KPI",
				'description'=>"Template untuk master KPI",
				'keywords'=>"Template Master, Template KPI",
				'category'=>"Template",
			];
			
			$head=['KODE KPI (KPIYYYYMMDDXXXX)','KPI','CARA MENGHITUNG (AVG/SUM)','UNIT / SATUAN','DETAIL RUMUS','KAITAN NILAI (0/1)','SUMBER DATA','MIN/MAX','JENIS SATUAN (0/1)','NILAI MINIMAL','NILAI MAKSIMAL'];
			for ($i=1;$i<=$this->max_range;$i++){
				array_push($head,'POIN '.$i);
				array_push($head,'NILAI '.$i);
			}
			$sheet[0]=[
				'range_huruf'=>3,
				'sheet_title'=>'Template Master KPI',
				'head'=>[
					'row_head'=>1,
					'data_head'=>$head,
				]
			];
			$data['data']=$sheet;
			$this->rekapgenerator->genExcel($data);
		}
		function export_data_kpi()
		{
			$data['properties']=[
				'title'=>"Data Master KPI",
				'subject'=>"Data Master KPI",
				'description'=>"Data untuk master KPI",
				'keywords'=>"Data Master, Data KPI",
				'category'=>"Data",
			];
			
			$body=[];
			$datax=$this->model_master->getListKpi(true);
			$row_body=2;
			$row=$row_body;
			foreach ($datax as $d) {
				$arr[$row]=[];
				for ($i=1; $i <= $this->max_range ; $i++) { 
					$col_p='poin_'.$i;
					$col_s='satuan_'.$i;
					array_push($arr[$row],$d->$col_p);
					array_push($arr[$row],$d->$col_s);
				}
				$body[$row]=[
					$d->kode_kpi,
					$d->kpi,
					// $d->rumus,
					$d->cara_menghitung,
					$d->unit,
					$d->detail_rumus,
					// $d->definisi,
					$d->kaitan,
					$d->sumber_data,
					$d->jenis,
					$d->sifat,
					$d->jenis_satuan,
					$d->min,
					$d->max,
				];
				$body[$row]=array_merge($body[$row],$arr[$row]);
				$row++;
			}
			$head=['KODE KPI (KPIYYYYMMDDXXXX)','KPI','CARA MENGHITUNG (AVG/SUM)','UNIT / SATUAN','DETAIL RUMUS','KAITAN NILAI (0/1)','SUMBER DATA','MIN/MAX','JENIS SATUAN (0/1)','NILAI MINIMAL','NILAI MAKSIMAL'];
			for ($i=1;$i<=$this->max_range;$i++){
				array_push($head,'POIN '.$i);
				array_push($head,'NILAI '.$i);
			}
			$sheet[0]=[
				'range_huruf'=>3,
				'sheet_title'=>'Data Master KPI',
				'head'=>[
					'row_head'=>1,
					'data_head'=>$head,
				],
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
			];
			$data['data']=$sheet;
			$this->rekapgenerator->genExcel($data);
		}
		function import_kpi()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			
			$col=['kode_kpi','kpi','cara_menghitung','unit','detail_rumus','kaitan','sumber_data','sifat','jenis_satuan','min','max'];
			for ($i=1;$i<=$this->max_range;$i++){
				array_push($col,'poin_'.$i);
				array_push($col,'satuan_'.$i);
			}
			$sheet[0]=[
				'range_huruf'=>3,
				'row'=>2,
				'table'=>'master_kpi',
				'column_code'=>'kode_kpi',
				'column_proerties'=>$this->model_global->getCreateProperties($this->admin),
				//urutan sama dengan export
				'column'=>$col,
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
			echo json_encode($datax);
		}
//--------------------------------------------------------------------------------------------------------------//
//Master Aspek Sikap
		public function master_aspek_sikap()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$usage=$this->uri->segment(3);
			if ($usage == null) {
				echo json_encode($this->messages->notValidParam());
			}else{
				if ($usage == 'view_all') {
					$data=$this->model_master->getListAspek();
					$access=$this->codegenerator->decryptChar($this->input->post('access'));
					$no=1;
					$datax['data']=[];
					foreach ($data as $d) {
						$var=[
							'id'=>$d->id_aspek,
							'create'=>$d->create_date,
							'update'=>$d->update_date,
							'access'=>$access,
							'status'=>$d->status,
						];
						$properties=$this->otherfunctions->getPropertiesTable($var);
						$kode_aspek_encode = $this->codegenerator->encryptChar($d->kode_aspek);
						$datax['data'][]=[
							$d->id_aspek,
							$d->kode_aspek,
							$d->nama,
							$d->keterangan,
							($d->jumlah_kuisioner == 0) ? '<label class="label label-danger">Tidak Ada Kuisioner</label>' : $d->jumlah_kuisioner.' Kuisioner',
							$properties['tanggal'],
							$properties['status'],
							$properties['aksi'],
							$kode_aspek_encode
						];
						$no++;
					}
					echo json_encode($datax);
				}elseif ($usage == 'view_one') {
					$id = $this->input->post('id_aspek');
					$data=$this->model_master->getAspek($id);
					foreach ($data as $d) {
						$datax=[
							'id'=>$d->id_aspek,
							'kode_aspek'=>$d->kode_aspek,
							'nama'=>$d->nama,
							'keterangan'=>$d->keterangan,
							'jumlah_kuisioner'=>($d->jumlah_kuisioner == 0) ? '<label class="label label-danger">Tidak Ada Kuisioner</label>' : $d->jumlah_kuisioner.' Kuisioner',
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
					$data = $this->codegenerator->kodeAspek();
					echo json_encode($data);
				}else{
					echo json_encode($this->messages->notValidParam());
				}
			}
		} 
		function add_aspek_sikap(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$kode=$this->input->post('kode');
			if ($kode != "") {
				$data=array(
					'kode_aspek'=>$kode,
					'nama'=>ucwords($this->input->post('nama')),
					'keterangan'=>ucwords($this->input->post('keterangan')),
				);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'master_aspek_sikap',$this->model_master->checkAspekCode($kode));
			}else{
				$datax = $this->messages->notValidParam();
			}
			echo json_encode($datax);
		}

		function del_aspek_sikap(){
			$id=$this->input->post('id');
			$kode=$this->input->post('kode');
			if ($id != "" && $kode != "") {
				$this->db->where('id_aspek',$id);
				$in=$this->db->delete('master_aspek_sikap');
				if ($in) {
					$this->db->where('kode_aspek',$kode);
					$this->db->delete('master_kuisioner');
					$datax = $this->messages->allGood(); 
				}else{
					$datax = $this->messages->allFailure();  
				}
			}else{
				$datax = $this->messages->notValidParam(); 
			}
			echo json_encode($datax);
		}
		function edt_aspek_sikap(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$id=$this->input->post('id');
			if ($id != "") {
				$data=array(
					'kode_aspek'=>$this->input->post('kode'),
					'nama'=>ucwords($this->input->post('nama')),
					'keterangan'=>ucwords($this->input->post('keterangan')),
				);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_aspek']) {
					$cek=$this->model_master->checkAspekCode($data['kode_aspek']);
				}else{
					$cek=false;
				}
				$datax = $this->model_global->updateQueryCC($data,'master_aspek_sikap',['id_aspek'=>$id],$cek);
			}else{
				$datax = $this->messages->notValidParam(); 
			}
			echo json_encode($datax);
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Kuisioner
		public function master_kuisioner()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$usage=$this->uri->segment(3);
			$kodeasp=$this->uri->segment(4);
			if ($usage == null) {
				echo json_encode($this->messages->notValidParam());
			}else{
				if ($usage == 'view_all') {
					$data=$this->model_master->getListKuisioner($kodeasp);
					$access=$this->codegenerator->decryptChar($this->input->post('access'));
					$no=1;
					$datax['data']=[];
					foreach ($data as $d) {
						$var=[
							'id'=>$d->id_kuisioner,
							'create'=>$d->create_date,
							'update'=>$d->update_date,
							'access'=>$access,
							'status'=>$d->status,
						];

						$properties=$this->otherfunctions->getPropertiesTable($var);
						$nilai = $d->bawah.' - '.$d->atas;
						$datax['data'][]=[
							$d->id_kuisioner,
							$d->kode_kuisioner,
							$d->kuisioner,
							$d->definisi,
							$nilai,
							(($this->otherfunctions->getTipeJabatan($d->kode_tipe)) ? $this->otherfunctions->getTipeJabatan($d->kode_tipe) : $this->otherfunctions->getMark()),
							$properties['tanggal'],
							$properties['status'],
							$properties['aksi']
						];
						$no++;
					}
					echo json_encode($datax);
				}elseif ($usage == 'view_one') {
					$id = $this->input->post('id_kuisioner');
					$data=$this->model_master->getKuisioner($id);
					foreach ($data as $d) {
						$tb=[];
						for ($i=1; $i <6 ; $i++) { 
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
							'id'=>$d->id_kuisioner,
							'kode_kuisioner'=>$d->kode_kuisioner,
							'nama'=>$d->kuisioner,
							'bawah'=>$d->bawah,
							'atas'=>$d->atas,
							'tipe_val'=>$d->kode_tipe,
							'tipe'=>$this->otherfunctions->getTipeJabatan($d->kode_tipe),
							'definisi'=>$d->definisi,	
							'tr_table'=>$tb,
							'status'=>$d->status,
							'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
							'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
							'create_by'=>$d->create_by,
							'update_by'=>$d->update_by,
							'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
							'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update)
						];
						for ($i=1; $i <=5 ; $i++) { 
							$col1='poin_'.$i;
							$col2='satuan_'.$i;
							$datax[$col1]=$d->$col1;
							$datax[$col2]=$d->$col2;
						}
					}
					echo json_encode($datax);
				}elseif ($usage == 'kode') {
					$data = $this->codegenerator->kodeKuisioner();
					echo json_encode($data);
				}else{
					echo json_encode($this->messages->notValidParam());
				}
			}
		}
		function add_kuisioner(){
			$kode=$this->input->post('kode_aspek');
			$cek=$this->model_master->getAspekKode($kode);
			if ($kode != "" || isset($cek)) {
				$data=array(
					'kode_kuisioner'=>$this->codegenerator->kodeKuisioner(),
					'kode_aspek'=>$kode,
					'kuisioner'=>ucwords($this->input->post('kuisioner')),
					'kode_tipe'=>$this->input->post('tipe'),
					'bawah'=>$this->input->post('bawah'),
					'atas'=>$this->input->post('atas'),
					'definisi'=>$this->input->post('definisi'),
				);
				for ($i=1; $i <=5 ; $i++) { 
					$col1='poin_'.$i;
					$col2='satuan_'.$i;
					$data[$col1]=$this->input->post($col1);
					$data[$col2]=$this->input->post($col2);
				}
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'master_kuisioner',$this->model_master->checkKuisionerCode($kode));
			}else{
				$datax=$this->messages->notValidParam();
			}
			echo json_encode($datax);
		}
		function edt_kuisioner(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$id=$this->input->post('id');
			if ($id != "") {
				$data=array(
					'kode_kuisioner'=>$this->input->post('kode'),
					'kuisioner'=>ucwords($this->input->post('kuisioner')),
					'kode_tipe'=>ucwords($this->input->post('tipe')),
					'bawah'=>$this->input->post('bawah'),
					'atas'=>$this->input->post('atas'),
					'definisi'=>$this->input->post('definisi'),
				);
				for ($i=1; $i <=5 ; $i++) { 
					$col1='poin_'.$i;
					$col2='satuan_'.$i;
					$data[$col1]=$this->input->post($col1);
					$data[$col2]=$this->input->post($col2);
				}
				$data_to_agenda=$data;
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
	//cek data
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_kuisioner']) {
					$cek=$this->model_master->checkKuisionerCode($data['kode_kuisioner']);
				}else{
					$cek=false;
				}
				$datax = $this->model_global->updateQueryCC($data,'master_kuisioner',['id_kuisioner'=>$id],$cek);
				if (!$cek){
					$this->model_agenda->updateAgendaSikapFromConceptMaster($data_to_agenda,'master');//sync to agenda
				}
			}else{
				$datax=$this->messages->notValidParam();
			}
			echo json_encode($datax);
		}
		function set_batasan_kuisioner(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$bw=$this->input->post('bawah');
			$kode=$this->input->post('kode');
			if ($bw != "" || $kode != "") {
				$data=array(
					'bawah'=>$this->input->post('bawah'),
					'atas'=>$this->input->post('atas'),
				);
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax = $this->model_global->updateQuery($data,'master_kuisioner',['kode_aspek'=>$kode]);
			}else{
				$datax = $this->messages->notValidParam(); 
			}
			echo json_encode($datax);
		}
//--------------------------------------------------------------------------------------------------------------//
//Master Form Aspek
		public function master_form_aspek()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$usage=$this->uri->segment(3);
			if ($usage == null) {
				echo json_encode($this->messages->notValidParam());
			}else{
				if ($usage == 'view_all') {
					$data=$this->model_master->getListFormAspek();
					$access=$this->codegenerator->decryptChar($this->input->post('access'));
					$no=1;
					$datax['data']=[];
					foreach ($data as $d) {
						$var=[
							'id'=>$d->id_form,
							'create'=>$d->create_date,
							'update'=>$d->update_date,
							'access'=>$access,
							'status'=>$d->status,
						];
						$properties=$this->otherfunctions->getPropertiesTable($var);
						$sumbobot=0;
						$table_bobot='';
						if ($d->bobot_aspek != null) {
							$val=$this->otherfunctions->getParseVar($d->bobot_aspek);
							$table_bobot .= '<table class="table table-striped">
							<thead>
							<tr class="bg-blue">
							<th>Nama Aspek</th>
							<th>Bobot %</th>
							</tr>
							</thead><tbody>';
							foreach ($val as $k_v => $v_v) {
								$nama_asp=$this->model_master->getAspekKode($k_v)['nama'];
								$bobot=$v_v;
								$table_bobot .='<tr>
								<td>'.$nama_asp.'</td>
								<td>'.$bobot.'</td>
								</tr>';
								$sumbobot+=$bobot;
							}
							$table_bobot .= '</tbody></table>';
						}
						$datax['data'][]=[
							$d->id_form,
							$d->kode_form,
							$d->nama,
							$this->otherfunctions->getTipeJabatan($d->kode_tipe),
							($table_bobot != '') ? $table_bobot : $this->otherfunctions->getMark(),
							$properties['tanggal'],
							$properties['status'],
							$properties['aksi'],
							$sumbobot
						];
						$no++;
					}
					echo json_encode($datax);
				}elseif ($usage == 'view_one') {
					$id = $this->input->post('id_form');
					$data=$this->model_master->getFormAspek($id);
					foreach ($data as $d) {
						$bag=[];
						$sumbobot=0;
						$table_bobot='';
						if ($d->bobot_aspek != null) {
							$val=$this->otherfunctions->getParseVar($d->bobot_aspek);
							$table_bobot .= '<table class="table table-striped">
							<thead>
							<tr class="bg-blue">
							<th>Nama Aspek</th>
							<th>Bobot %</th>
							</tr>
							</thead><tbody>';
							foreach ($val as $k_v => $v_v) {
								$nama_asp=$this->model_master->getAspekKode($k_v)['nama'];
								$bobot=$v_v;
								$table_bobot .='<tr>
								<td>'.$nama_asp.'</td>
								<td>'.$bobot.'</td>
								</tr>';
								$sumbobot+=$bobot;
							}
							$table_bobot .= '</tbody></table>';
						}					
						$datax=[
							'id'=>$d->id_form,
							'kode_form'=>$d->kode_form,
							'nama'=>$d->nama,
							'tipe'=>$this->otherfunctions->getTipeJabatan($d->kode_tipe),
							'kode_tipe'=>$d->kode_tipe,
							'bobot_aspek'=>$table_bobot,
							'total_bobot_aspek'=>($sumbobot != 0 || $sumbobot == 100) ? $sumbobot.'% '.$this->otherfunctions->getMark('success') : $sumbobot.'% '.$this->otherfunctions->getMark(),
							'status'=>$d->status,
							'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
							'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
							'create_by'=>$d->create_by,
							'update_by'=>$d->update_by,
							'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
							'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
							'sumbobot'=>$sumbobot
						];
					}
					echo json_encode($datax);
				}elseif ($usage == 'kode') {
					$data = $this->codegenerator->kodeFormAspek();
					echo json_encode($data);
				}else{
					echo json_encode($this->messages->notValidParam());
				}
			}
		} 
		function add_form_aspek(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$kode=$this->input->post('kode');
			if ($kode != "") {
				$bbt=$this->input->post('bobot');
				$bbt1=[];
				if (isset($bbt)) {
					foreach ($bbt as $b => $v) {
						if ($v != "" || $v != 0) {
							$bx=$b.':'.$v;
							array_push($bbt1, $bx);
						}
					}
					$bbt2=implode(';', $bbt1);
				}else{
					$bbt2=null;
				}
				$data=array(
					'kode_form'=>$kode,
					'nama'=>ucwords($this->input->post('nama')),
					'kode_tipe'=>$this->input->post('tipe'),
					'bobot_aspek'=>$bbt2,
				);
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'master_form_aspek',$this->model_master->checkFormAspekCode($kode));
			}else{
				$datax=$this->messages->notValidParam();
			}
			echo json_encode($datax);
		}
		function edt_form_aspek(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$id=$this->input->post('id');
			if ($id != "") {
				$bbt=$this->input->post('bobot');
				$bbt1=[];
				if (isset($bbt)) {
					foreach ($bbt as $b => $v) {
						if ($v != "" || $v != 0) {
							$bx=$b.':'.$v;
							array_push($bbt1, $bx);
						}
					}
					$bbt2=implode(';', $bbt1);
				}else{
					$bbt2=null;
				}
				$data=[
					'kode_form'=>$this->input->post('kode'),
					'nama'=>ucwords($this->input->post('nama')),
					'bobot_aspek'=>$bbt2,
					'kode_tipe'=>$this->input->post('tipe'),
				];
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
	//cek data
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_form']) {
					$cek=$this->model_master->checkFormAspekCode($data['kode_form']);
				}else{
					$cek=false;
				}
				$datax = $this->model_global->updateQueryCC($data,'master_form_aspek',['id_form'=>$id],$cek);
				if (!$cek){
					$this->model_agenda->updateAgendaSikapFromConceptMaster($data['bobot_aspek'],'master_form_aspek');//sync to agenda
				}
			}else{
				$datax=$this->messages->notValidParam();
			}
			echo json_encode($datax);
		}

		public function master_aspek_actv()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$id=$this->input->post('id');
			$kode=$this->input->post('kode');
			$aspek=$this->model_master->getListAspek();
			if (isset($aspek)) {
				if ($id == 'no') {
					$begin='';
					foreach ($aspek as $asp) {
						$begin .= '<tr>
						<td>'.$asp->nama.'</td>
						<td><input type="number" max="100" min="0" placeholder="Masukkan Bobot" id="bobot_'.$kode.'[]" name="bobot['.$asp->kode_aspek.']" class="form-control bobot_'.$kode.'"></td>
						</tr>';
					}
					$begin .='<tr>
					<td class="bg-success text-center" style="background-color: transparent;"><b>Bobot Total</b></td>
					<td><input id="data_hasilbobot_'.$kode.'" class="form-control input-md" disabled /></td>
					</tr>';
				}else{
					$data=$this->model_master->getFormAspek($id);
					$begin='';
					foreach ($data as $d) {
						if (!empty($d->bobot_aspek)) {
							$val=$this->otherfunctions->getParseVar($d->bobot_aspek);
							foreach ($aspek as $aspx) {
								$begin .= '<tr>
								<td>'.$aspx->nama.'</td>
								<td><input type="number" max="100" min="0" placeholder="Masukkan Bobot" id="bobot_'.$kode.'[]" name="bobot['.$aspx->kode_aspek.']" class="form-control bobot_'.$kode.'" value="';
								$begin .=(isset($val[$aspx->kode_aspek])) ? $val[$aspx->kode_aspek] : null;
								$begin .='"></td>
								</tr>';
							}
							$begin .='<tr>
							<td class="bg-success text-center" style="background-color: transparent;"><b>Bobot Total</b></td>
							<td><input id="data_hasilbobot_'.$kode.'" value="'.array_sum($val).'" class="form-control input-md" disabled /></td>
							</tr>';
						}

					}
				}
			}else{
				$begin=null;
			}
			echo json_encode($begin);
		}
//--------------------------------------------------------------------------------------------------------------//
//Master Periode Penilaian
		public function master_periode_penilaian()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$usage=$this->uri->segment(3);
			if ($usage == null) {
				echo json_encode($this->messages->notValidParam());
			}else{
				if ($usage == 'view_all') {
					$data=$this->model_master->getListPeriodePenilaian();
					$access=$this->codegenerator->decryptChar($this->input->post('access'));
					$no=1;
					$datax['data']=[];
					foreach ($data as $d) {
						$var=[
							'id'=>$d->id_periode,
							'create'=>$d->create_date,
							'update'=>$d->update_date,
							'access'=>$access,
							'status'=>$d->status,
						];
						$properties=$this->otherfunctions->getPropertiesTable($var);
						$datax['data'][]=[
							$d->id_periode,
							$d->kode_periode,
							$d->nama,
							(!empty($d->start) && !empty($d->end)) ? $this->formatter->getMonth()[$this->otherfunctions->addFrontZero($d->start)].' s/d '.$this->formatter->getMonth()[$this->otherfunctions->addFrontZero($d->end)] : $this->otherfunctions->getMark(),
							(!empty($d->batas)) ? 'Tanggal '.$d->batas.' di Bulan berikutnya' : $this->otherfunctions->getMark(),
							$properties['tanggal'],
							$properties['status'],
							$properties['aksi']
						];
						$no++;
					}
					echo json_encode($datax);
				}elseif ($usage == 'view_one') {
					$id = $this->input->post('id_periode');
					$data=$this->model_master->getPeriodePenilaian($id);
					foreach ($data as $d) {
						$datax=[
							'id'=>$d->id_periode,
							'kode_periode'=>$d->kode_periode,
							'nama'=>$d->nama,
							'bulan'=>(!empty($d->start) && !empty($d->end)) ? $this->formatter->getMonth()[$this->otherfunctions->addFrontZero($d->start)].' s/d '.$this->formatter->getMonth()[$this->otherfunctions->addFrontZero($d->end)] : $this->otherfunctions->getMark(),
							'start'=>$this->otherfunctions->addFrontZero($d->start),
							'end'=>$this->otherfunctions->addFrontZero($d->end),
							'batasview'=>(!empty($d->batas)) ? 'Tanggal '.$d->batas.' di Bulan berikutnya' : $this->otherfunctions->getMark(),
							'batas'=>$d->batas,
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
					$data = $this->codegenerator->kodePeriodePenilaian();
					echo json_encode($data);
				}else{
					echo json_encode($this->messages->notValidParam());
				}
			}
		}
		function add_periode_penilaian(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$kode=$this->input->post('kode');
			if ($kode != "") {
				$data=[
					'kode_periode'=>strtoupper($kode),
					'nama'=>ucwords($this->input->post('nama')),
					'start'=>$this->input->post('start'),
					'end'=>$this->input->post('end'),
					'batas'=>$this->input->post('tanggal'),
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQueryCC($data,'master_periode_penilaian',$this->model_master->checkPeriodePenilaianCode($kode));
			}else{
				$datax=$this->messages->notValidParam();
			}
			echo json_encode($datax);
		}
		function edt_periode_penilaian(){
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$id=$this->input->post('id');
			if ($id != "") {
				$data=[
					'kode_periode'=>strtoupper($this->input->post('kode')),
					'nama'=>ucwords($this->input->post('nama')),
					'start'=>$this->input->post('start'),
					'end'=>$this->input->post('end'),
					'batas'=>$this->input->post('tanggal'),
				];
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
//cek data
				$old=$this->input->post('kode_old');
				if ($old != $data['kode_periode']) {
					$cek=$this->model_master->checkPeriodePenilaianCode($data['kode_periode']);
				}else{
					$cek=false;
				}
				$datax = $this->model_global->updateQueryCC($data,'master_periode_penilaian',['id_periode'=>$id],$cek);
			}else{
				$datax=$this->messages->notValidParam();
			}
			echo json_encode($datax);
		}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi KPI
	public function master_konversi_kpi()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKonversiKpi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_konversi_kpi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_konversi_kpi,
						$d->kode_konversi_kpi,
						$d->nama,
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						$d->huruf,
						(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_konversi_kpi');
				$data=$this->model_master->getKonversiKpi($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_konversi_kpi,
						'kode_konversi_kpi'=>$d->kode_konversi_kpi,
						'nama'=>$d->nama,
						'min_max'=>(($d->min !='') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						'min'=>$d->min,
						'max'=>$d->max,
						'huruf'=>$d->huruf,
						'warna_val'=>$d->warna,
						'warna'=>(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeKonversiKpi();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_konversi_kpi(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_konversi_kpi'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_konversi_kpi',$this->model_master->checkKonversiKpiCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_konversi_kpi(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_konversi_kpi'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_konversi_kpi']) {
				$cek=$this->model_master->checkKonversiKpiCode($data['kode_konversi_kpi']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_konversi_kpi',['id_konversi_kpi'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Sikap
	public function master_konversi_sikap()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKonversiSikap();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_konversi_sikap,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_konversi_sikap,
						$d->kode_konversi_sikap,
						$d->nama,
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						$d->huruf,
						(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_konversi_sikap');
				$data=$this->model_master->getKonversiSikap($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_konversi_sikap,
						'kode_konversi_sikap'=>$d->kode_konversi_sikap,
						'nama'=>$d->nama,
						'min_max'=>(($d->min !='') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						'min'=>$d->min,
						'max'=>$d->max,
						'huruf'=>$d->huruf,
						'warna_val'=>$d->warna,
						'warna'=>(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeKonversiSikap();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_konversi_sikap(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_konversi_sikap'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_konversi_sikap',$this->model_master->checkKonversiSikapCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_konversi_sikap(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_konversi_sikap'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_konversi_sikap']) {
				$cek=$this->model_master->checkKonversiSikapCode($data['kode_konversi_sikap']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_konversi_sikap',['id_konversi_sikap'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Presensi
	public function master_konversi_presensi()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKonversiPresensi();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$satuan=($d->jenis == 1)?' Jam':' Hari';
					$var=[
						'id'=>$d->id_konversi_presensi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_konversi_presensi,
						$d->kode_konversi_presensi,
						$d->nama.' ( '.$this->otherfunctions->getJenisPresensi($d->jenis).' )',
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()).$satuan,
						$d->huruf,
						(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
						$this->otherfunctions->getJenisPresensi($d->jenis),
						$d->nilai,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_konversi_presensi');
				$data=$this->model_master->getKonversiPresensi($id);
				foreach ($data as $d) {
					$satuan=($d->jenis == 1)?' Jam':' Hari';
					$datax=[
						'id'=>$d->id_konversi_presensi,
						'kode_konversi_presensi'=>$d->kode_konversi_presensi,
						'nama_val'=>$d->nama,
						'nama'=>$d->nama.' ( '.$this->otherfunctions->getJenisPresensi($d->jenis).' )',
						'min_max'=>(($d->min !='') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()).$satuan,
						'min'=>$d->min,
						'max'=>$d->max,
						'huruf'=>$d->huruf,
						'nilai'=>$d->nilai,
						'warna_val'=>$d->warna,
						'warna'=>(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
						'jenis'=>$this->otherfunctions->getJenisPresensi($d->jenis),
						'jenis_val'=>$d->jenis,
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
				$data = $this->codegenerator->kodeKonversiPresensi();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_konversi_presensi(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_konversi_presensi'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
				'jenis'=>$this->input->post('jenis'),
				'nilai'=>$this->input->post('nilai'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_konversi_presensi',$this->model_master->checkKonversiPresensiCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_konversi_presensi(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_konversi_presensi'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
				'jenis'=>$this->input->post('jenis'),
				'nilai'=>$this->input->post('nilai'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_konversi_presensi']) {
				$cek=$this->model_master->checkKonversiPresensiCode($data['kode_konversi_presensi']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_konversi_presensi',['id_konversi_presensi'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Kuartal
	public function master_konversi_kuartal()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKonversiKuartal();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_konversi_kuartal,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_konversi_kuartal,
						$d->kode_konversi_kuartal,
						$d->nama,
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						$d->huruf,
						(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_konversi_kuartal');
				$data=$this->model_master->getKonversiKuartal($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_konversi_kuartal,
						'kode_konversi_kuartal'=>$d->kode_konversi_kuartal,
						'nama'=>$d->nama,
						'min_max'=>(($d->min !='') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						'huruf'=>$d->huruf,
						'min'=>$d->min,
						'max'=>$d->max,
						'warna_val'=>$d->warna,
						'warna'=>(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeKonversiKuartal();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_konversi_kuartal(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_konversi_kuartal'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'huruf'=>$this->input->post('huruf'),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_konversi_kuartal',$this->model_master->checkKonversiKuartalCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_konversi_kuartal(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_konversi_kuartal'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'huruf'=>$this->input->post('huruf'),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_konversi_kuartal']) {
				$cek=$this->model_master->checkKonversiKuartalCode($data['kode_konversi_kuartal']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_konversi_kuartal',['id_konversi_kuartal'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Tahunan
	public function master_konversi_tahunan()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKonversiTahunan();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_konversi_tahunan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_konversi_tahunan,
						$d->kode_konversi_tahunan,
						$d->nama,
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						$d->huruf,
						(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_konversi_tahunan');
				$data=$this->model_master->getKonversiTahunan($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_konversi_tahunan,
						'kode_konversi_tahunan'=>$d->kode_konversi_tahunan,
						'nama'=>$d->nama,
						'min_max'=>(($d->min !='') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						'huruf'=>$d->huruf,
						'min'=>$d->min,
						'max'=>$d->max,
						'warna_val'=>$d->warna,
						'warna'=>(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeKonversiTahunan();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_konversi_tahunan(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_konversi_tahunan'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'huruf'=>$this->input->post('huruf'),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_konversi_tahunan',$this->model_master->checkKonversiTahunanCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_konversi_tahunan(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_konversi_tahunan'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'huruf'=>$this->input->post('huruf'),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_konversi_tahunan']) {
				$cek=$this->model_master->checkKonversiTahunanCode($data['kode_konversi_tahunan']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_konversi_tahunan',['id_konversi_tahunan'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}

	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi Insentif
	public function master_konversi_insentif()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKonversiInsentif();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_konversi_insentif,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_konversi_insentif,
						$d->kode_konversi_insentif,
						$d->nama,
						$d->prosentase,
						$d->huruf,
						$d->urutan,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_konversi_insentif');
				$data=$this->model_master->getKonversiInsentif($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_konversi_insentif,
						'kode_konversi_insentif'=>$d->kode_konversi_insentif,
						'nama'=>$d->nama,
						'huruf'=>$d->huruf,
						'urutan'=>$d->urutan,
						'prosentase'=>$d->prosentase,
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
				$data = $this->codegenerator->kodeKonversiInsentif();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_konversi_insentif(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_konversi_insentif'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'huruf'=>$this->input->post('huruf'),
				'urutan'=>$this->input->post('urutan'),
				'prosentase'=>$this->input->post('prosentase'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_konversi_insentif',$this->model_master->checkKonversiInsentifCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_konversi_insentif(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_konversi_insentif'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'huruf'=>$this->input->post('huruf'),
				'urutan'=>$this->input->post('urutan'),
				'prosentase'=>$this->input->post('prosentase'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_konversi_insentif']) {
				$cek=$this->model_master->checkKonversiInsentifCode($data['kode_konversi_insentif']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_konversi_insentif',['id_konversi_insentif'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//--------------------------------------------------------------------------------------------------------------//
	//Master Konversi GAP
	public function master_konversi_gap()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKonversiGap();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_konversi_gap,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_konversi_gap,
						$d->kode_konversi_gap,
						$d->nama,
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						$d->huruf,
						(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_konversi_gap');
				$data=$this->model_master->getKonversiGap($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_konversi_gap,
						'kode_konversi_gap'=>$d->kode_konversi_gap,
						'nama'=>$d->nama,
						'min_max'=>(($d->min !='') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						'min'=>$d->min,
						'max'=>$d->max,
						'huruf'=>$d->huruf,
						'warna_val'=>$d->warna,
						'warna'=>(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark(),
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
				$data = $this->codegenerator->kodeKonversiGap();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_konversi_gap(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_konversi_gap'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_konversi_gap',$this->model_master->checkKonversiGapCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_konversi_gap(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_konversi_gap'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'min'=>$this->input->post('min'),
				'max'=>$this->input->post('max'),
				'huruf'=>$this->input->post('huruf'),
				'warna'=>$this->input->post('warna'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_konversi_gap']) {
				$cek=$this->model_master->checkKonversiGapCode($data['kode_konversi_gap']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_konversi_gap',['id_konversi_gap'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}	
	public function getAllKonversi()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->input->post('usage');
		$option=$this->codegenerator->decryptChar($this->input->post('option'));
		
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			$datax['data']=[];
			if ($usage == 'presensi') {
				$data=$this->model_master->getListKonversiPresensi(1);			
				if (isset($data)) {
					foreach ($data as $d) {
						$datax['data'][]=[
							$d->nama,
							(($d->min != '') ? $this->formatter->getNumberFloat($d->min) : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $this->formatter->getNumberFloat($d->max) : $this->otherfunctions->getMark()),
							$this->otherfunctions->getJenisPresensi($d->jenis),
							$d->nilai,
							(!empty($d->warna)) ? '<i class="fa fa-circle" style="color:'.$d->warna.'"></i>' :$this->otherfunctions->getMark()
						];
					}
				}			
			}else{
				if ($usage == 'kpi') {
					$data=$this->model_master->getListKonversiKpi(1);
				}elseif ($usage == 'sikap') {
					$data=$this->model_master->getListKonversiSikap(1);		
				}elseif ($usage == 'gabungan') {
					$data=$this->model_master->getListKonversiKuartal(1);
					if(empty($option['kode_periode'])){
						$data=$this->model_master->getListKonversiTahunan(1);
					}
				}
				if (isset($data)) {
					foreach ($data as $d) {
						$datax['data'][]=[
							$d->nama,
							(($d->min != '') ? $this->formatter->getNumberFloat($d->min) : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $this->formatter->getNumberFloat($d->max) : $this->otherfunctions->getMark()),
							(!empty($d->warna)) ? '<i class="fa fa-circle" style="color:'.$d->warna.'"></i>' :$this->otherfunctions->getMark()
						];
					}
				}
				
			}
			echo json_encode($datax);
		}
	}
//--------------------------------------------------------------------------------------------------------------//
//Master Rumus
	public function master_rumus()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListRumus();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_rumus,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_rumus,
						$d->kode_rumus,
						$d->nama,
						$d->function,
						$d->keterangan,								
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_rumus');
				$data=$this->model_master->getRumus($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_rumus,
						'kode_rumus'=>$d->kode_rumus,
						'nama'=>$d->nama,
						'function'=>$d->function,
						'keterangan'=>$d->keterangan,
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
				$data = $this->codegenerator->kodeRumus();
				echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_rumus(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$kode=$this->input->post('kode');
		if ($kode != "") {
			$data=[
				'kode_rumus'=>strtoupper($kode),
				'nama'=>ucwords($this->input->post('nama')),
				'function'=>strtoupper($this->input->post('function')),
				'keterangan'=>$this->input->post('keterangan'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'master_rumus',$this->model_master->checkRumusCode($kode));
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_rumus(){
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_rumus'=>strtoupper($this->input->post('kode')),
				'nama'=>ucwords($this->input->post('nama')),
				'function'=>strtoupper($this->input->post('function')),
				'keterangan'=>$this->input->post('keterangan'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			//cek data
			$old=$this->input->post('kode_old');
			if ($old != $data['kode_rumus']) {
				$cek=$this->model_master->checkRumusCode($data['kode_rumus']);
			}else{
				$cek=false;
			}
			$datax = $this->model_global->updateQueryCC($data,'master_rumus',['id_rumus'=>$id],$cek);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	//===END MASTER DATA PENILAIAN ===//
	
	//=============================================== KUOTA LEMBUR =================================================================//
	public function master_kuota_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_master->getListKuotaLembur();
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_kuota_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$dataDetail=$this->model_master->getListDetailKuotaLembur(['a.kode_kuota_lembur'=>$d->kode]);
					$dataBag = count($dataDetail).' Bagian';
					$dataBagx ='';
					if(!empty($dataDetail)){
						foreach ($dataDetail as $dk) {
							$dataBagx .= '<i class="fas fa-dot-circle"></i> '.$dk->nama_bagian.' ('.$dk->nama_loker.')<br>';
						}
					}
					$datax['data'][]=[
						$d->id_kuota_lembur,
						'<a href="'.base_url("pages/view_kuota_lembur/".$this->codegenerator->encryptChar($d->kode)).'">'.$d->nama.'</a>',
						// $this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						$dataBag,
						$d->total_kuota_all.' Jam',
						$d->sisa_kuota.' Jam',
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
						$dataBagx,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_kuota_lembur');
				$data=$this->model_master->getListKuotaLembur(['a.id_kuota_lembur'=>$id]);
				foreach ($data as $d) {
					$data_bag='';
					// $selesai=date('Y-m-d',strtotime($d->tanggal.' +1 day'));
					$data_bag.='<h4 align="center"><b>Detail Bagian Kuota '.$d->nama.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama Bagian</th>
          							<th>Lokasi Kerja</th>
          							<th>Persentase</th>
          							<th>Jumlah Kuota<br>(Jam)</th>
          							<th>Sisa Kuota<br>(Jam)</th>
          						</tr>
          					</thead>
          					<tbody>';
							$dataDetail=$this->model_master->getListDetailKuotaLembur(['a.kode_kuota_lembur'=>$d->kode]);
							if(!empty($dataDetail)){
          						$no=1;
          						foreach ($dataDetail as $dl) {
									$bagian = ($dl->kode_bagian == 'buff') ? 'KUOTA BUFFER' : $dl->nama_bagian;
          							$data_bag.='<tr>
										<td>'.$no.'.</td>
										<td>'.$bagian.'</td>
										<td>'.$dl->nama_loker.'</td>
										<td>'.$dl->persen.'%</td>
										<td>'.$dl->kuota.'</td>
										<td>'.$dl->sisa_kuota.'</td>
									</tr>';
									$no++;
								}
							}else{
								$data_bag.='<tr>
									<td colspan="5" class="text-center"><b>Tidak Ada Data</b></td>
								</tr>';
							}
						$data_bag.='</tbody>
					</table>';
					$datax=[
						'id'=>$d->id_kuota_lembur,
						'kode'=>$d->kode,
						'nama'=>$d->nama,
						'tanggal_view'=>$this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						'bulan_view'=>$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
						'jumlah_bag'=>$d->jum_bag.' Bagian',
						'total'=>$d->total_kuota_all.' Jam',
						'sisa'=>$d->sisa_kuota.' Jam',
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($d->tgl_mulai),
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($d->tgl_selesai),
						'bulan_e'=>$d->bulan,
						'tahun_e'=>$d->tahun,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'data_bag'=>$data_bag,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeBank();
        		echo json_encode($data);
			}elseif ($usage == 'get_select2') {
				$pack=[];
				$data = $this->model_master->getListBagian(true);
				if (isset($data)){
					$pack['buff']='KUOTA BUFFER';
					foreach ($data as $d){
						$pack[$d->kode_bagian]=$d->nama.(($d->nama_loker)?' ('.$d->nama_loker.')':null);
					}
				}
        		echo json_encode($pack);
			}elseif ($usage == 'get_select2_kuota') {
				$pack=[];
				$data = $this->model_master->getListKuotaLembur("a.status=1");
				if (isset($data)){
					foreach ($data as $d){
						$pack[$d->kode]=$d->nama.' ('.$this->formatter->getDateFormatUser($d->tgl_mulai).'-'.$this->formatter->getDateFormatUser($d->tgl_selesai).')';
					}
				}
        		echo json_encode($pack);
			}elseif($usage == 'kuota_lembur'){
				$dataKuota=$this->model_master->getListKuotaLembur(['a.status'=>'1']);
				$c=[];
				$ada='<li class="header">Kuota Lembur</li><li class="divider"></li>';
				if(!empty($dataKuota)){
					foreach ($dataKuota as $d) {
						$namaKuota = $this->otherfunctions->cutText($d->nama, 35, 2);
						$ada.='<li><a style="color:green; font-size:12pt;" href="'.base_url('pages/cetakKuotaLembur/'.$d->kode).'" target="_blank"><i class="fas fa-clock"></i> '.$namaKuota.'<small class="text-muted pull-right" style="color:green; font-size:12pt;"><i class="fas fa-print"></i> Cetak</small></a>
							<ul>';
								$detail=$this->model_master->getListDetailKuotaLembur(['a.kode_kuota_lembur'=>$d->kode]);
								if(!empty($detail)){
									foreach ($detail as $e) {
										$bagian = ($e->kode_bagian == 'buff') ? 'KUOTA BUFFER' : $e->nama_bagian;
										$bagian = $this->otherfunctions->cutText($bagian, 35, 2);
										// $ada.='<li> '.$bagian.' <small style="color:red; font-size:8pt;" title="Sisa Kuota">'.$e->sisa_kuota.' Jam</small> <small class="text-muted pull-right" title="Total Kuota">'.$e->kuota.' Jam&nbsp;&nbsp;</small></li>';
										$color = (($e->sisa_kuota <= 0)? ' style="background-color: red;color:white;"' : null);
										$colors = (($e->sisa_kuota <= 0)? ' style="font-size:8pt;color:white;"' : null);
										$ada.='<li'.$color.'> '.$bagian.' 
											<small'.$colors.' class="text-muted pull-right" title="Sisa Kuota">'.number_format($e->sisa_kuota,0,'.','').' Jam &nbsp;&nbsp;</small>
											<small'.$colors.' class="text-muted pull-right" title="Total Kuota">'.number_format($e->kuota,0,'.','').' Jam |&nbsp;</small>
										</li>';
									}
								}
							$ada.='</ul>
						</li>';
						array_push($c,1);
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
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function getCart()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all'){
				$no=1;
                $datax['data']=[];
                $data = $this->cart->contents();
                foreach ($data as $d) {
					$bag = $this->model_master->getBagianKode($d['name']);
					$nama = ($d['name'] == 'buff') ? 'KUOTA BUFFER' : $bag['nama'].' - ('.$bag['nama_loker'].')';
                    $aksi = '<a href="javascript:void(0)" onclick=deleteChart("'.$d['rowid'].'") class="btn btn-danger btn-sm"><span class="fa fa-close"></span> Hapus</a>';
					$datax['data'][]=[
						$no.'<input type="hidden" id="kode'.$no.'" name="kode_all[]" value="'.$d['name'].'"><input type="hidden" id="id'.$no.'" name="id_all[]" value="'.$d['rowid'].'">',
						$nama,
						'<input type="number" id="persen'.$no.'" class="form-control" name="persen_all[]" value="'.$d['persen'].'" style="width: 100%;">',
						'<input type="number" id="kuota'.$no.'" class="form-control" name="kuota_all[]" value="'.$d['price'].'" style="width: 100%;" readonly>',
                        $aksi.'<input type="hidden" name="id_barang_all[]" value="'.$d['id'].'">',
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif($usage == 'view_one'){
			}elseif($usage == 'getStatusCust'){
                $cust=$this->input->post('cust');
				$kode_pelanggan=$this->input->post('kode_pelanggan');
                $data = $this->cart->contents();
                foreach ($data as $d) {
					$barang = $this->model_data->getDataBarang(['a.kode'=>$d['kode']],true);
					$getMember = $this->model_data->getDataPelanggan(['kode'=>$kode_pelanggan],true)['member'];
					if($d['satuan_fix'] == $d['satuan']){
						$harga = ($cust==1 && $getMember==1)?$barang['harga_cust']:$barang['harga_jual'];
					}elseif($d['satuan_fix'] == $d['satuan_pack']){
						$harga = ($cust==1 && $getMember==1)?$barang['harga_per_pack']:$barang['harga_per_pack'];
					}
					$data = $this->cart->update(['rowid' => $d['rowid'], 'price' => $harga]);
				}
        		echo json_encode($data);
			}elseif($usage == 'getDataBarang'){
                $id=$this->input->post('id');
                $kode=$this->input->post('kode');
                $satuan=$this->input->post('satuan');
                $cust=$this->input->post('cust');
				$kode_pelanggan=$this->input->post('kode_pelanggan');
				$barang = $this->model_data->getDataBarang(['a.kode'=>$kode],true);
				$getMember = $this->model_data->getDataPelanggan(['kode'=>$kode_pelanggan],true)['member'];
				if($satuan == $barang['satuan']){
					$harga = ($cust==1 && $getMember==1)?$barang['harga_cust']:$barang['harga_jual'];
				}elseif($satuan == $barang['satuan_pack']){
					$harga = ($cust==1 && $getMember==1)?$barang['harga_per_pack']:$barang['harga_per_pack'];
				}
                $this->cart->update(['rowid' => $id, 'price' => $harga, 'satuan_fix' => $satuan]);
        		echo json_encode($harga);
			}elseif($usage == 'getUpdateKuota'){
                $kuota=$this->input->post('kuota');
                $id=$this->input->post('id');
				$data=$this->cart->update(['rowid' => $id, 'price' => $kuota]);
				echo json_encode($data);
			}elseif($usage == 'getPersenKuota'){
                $persen=$this->input->post('persen');
                $id=$this->input->post('id');
                $jumlah_kuota=$this->input->post('jumlah_kuota');
                $jumlah_kuota=($jumlah_kuota == 0 || empty($jumlah_kuota)) ? 1 : $jumlah_kuota;
				$data=$this->cart->update(['rowid' => $id, 'persen' => $persen, 'jumlah_kuota' => $jumlah_kuota, 'price' => 0]);
				echo json_encode($data);
				// echo json_encode($this->messages->OK());
			}elseif($usage == 'getQtyKuota'){
                $qty=$this->input->post('qty');
                $id=$this->input->post('id');
				$data=$this->cart->update(['rowid' => $id, 'qty' => $qty]);
				echo json_encode($this->messages->OK());
			}elseif($usage == 'getDiscBarang'){
                $id=$this->input->post('id');
                $disc=$this->input->post('disc');
                $data=$this->cart->update(['rowid' => $id, 'disc' => $disc]);
        		echo json_encode($data);
			}elseif($usage == 'get_jumlah_char'){
				$data = count($this->cart->contents());
        		echo json_encode($data);
			}elseif($usage == 'get_total'){
                $total=$this->cart->total();
                $datax=['total'=>$total,'total_user'=>$total.' Jam'];
        		echo json_encode($datax);
			}elseif($usage == 'total_kuota'){
                $total=$this->cart->total();
                $total_persen=$this->cart->total_persen();
                $datax=['total_belanja'=>$total,'total_user'=>$total.' Jam','total_persen'=>$total_persen.'%'];
        		echo json_encode($datax);
			}elseif($usage == 'total_disc'){
                $total=$this->cart->total_disc();
                $datax=['total_disc'=>$total,'total_user'=>$this->formatter->getFormatMoneyUser($total),];
        		echo json_encode($datax);
			}elseif($usage == 'delete'){
                $row_id=$this->input->post('id');
                $data=$this->cart->update(['rowid' => $row_id, 'qty' => 0]);
        		echo json_encode($data);
			}elseif($usage == 'getLabelSuccess'){
                $kode=$this->input->post('kode');
                $trans=$this->model_data->getDataTransaksi(['a.kode'=>$kode],true);
                $paketTransaksi=$this->model_data->getDataHistoryTransaksi(['a.kode_transaksi'=>$kode]);
                $tabel='';
                $tabel .= '<table class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr class="bg-blue">
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th style="text-align:center;">Harga(Rp)</th>
                            <th style="text-align:center;">Satuan</th>
                            <th style="text-align:center;">Diskon(Rp)</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:center;">SubTotal</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $no=1;
                    foreach ($paketTransaksi as $pt) {
                        $tabel .= '<tr>
                            <td>'. $no.'</td>
                            <td>'.$pt->name.'</td>
                            <td style="text-align:center;">'.$this->otherfunctions->getSatuan($pt->satuan).'</td>
                            <td style="text-align:center;">'.$this->formatter->getFormatMoneyUser2($pt->price).'</td>
                            <td style="text-align:center;">'.$this->formatter->getFormatMoneyUser2($pt->disc).'</td>
                            <td style="text-align:center;">'.$pt->qty.'</td>
                            <td style="text-align:center;">'.$this->formatter->getFormatMoneyUser2($pt->subtotal).'</td>
                        </tr>';
                        $no++;
                    }
                    $tabel .= '</tbody>
                </table>';
                $datax=['tabel'=>$tabel,
                        'kode'=>$kode,
                        'nama'=>$trans['nama_konsumen'],
                        'tanggal'=>$this->formatter->getDateMonthFormatUser($trans['tanggal']),
                        'total_harga'=>$this->formatter->getFormatMoneyUser2($trans['total_harga']),
                        'diskon'=>$this->formatter->getFormatMoneyUser2($trans['diskon']),
                        'jumlah'=>$this->formatter->getFormatMoneyUser2($trans['jumlah']),
                        'bayar'=>$this->formatter->getFormatMoneyUser2($trans['bayar']),
                        'kembalian'=>$this->formatter->getFormatMoneyUser2($trans['kembalian']),
                        'kembali'=>(!empty($trans['bayar']) ? ($trans['bayar']-$trans['jumlah']) : $trans['jumlah']),
                    ];
        		echo json_encode($datax);
				
			}
		}
	}
	public function addToCartKuota()
	{
		if (!$this->input->is_ajax_request()) 
		  redirect('not_found');
        $kode_bagian=$this->input->post('kode_bagian');
        $jumlah_kuota=$this->input->post('jumlah_kuota');
        $data = [
			'id'                => $kode_bagian,
			'name'				=> $kode_bagian,
			'qty'				=> 1,
			'price'				=> 0,
			'persen'			=> 0,
		];
		$datax = $this->cart->insert($data);
		echo json_encode($datax);
	}
	public function do_add_master_kuota()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
        $nama = $this->input->post('nama');
		if(!empty($nama)){
			$kode          	= $this->codegenerator->kodeKuotaLembur();
			$tanggal 		= $this->input->post('tanggal');
			// $bulan 			= $this->input->post('bulan');
			// $tahun 			= $this->input->post('tahun');
			$total_b	   	= $this->cart->total();//$this->input->post('total_b');
			$kode_all		= $this->input->post('kode_all');
			$id_all		 	= $this->input->post('id_all');
			$kuota_all		= $this->input->post('kuota_all');
			$persen_all		= $this->input->post('persen_all');
			$tgl_mulai 		= date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'start')));
			$tgl_selesai 	= date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'end')));
			$dd = $this->cart->contents();
			$idRow=[];
			foreach ($dd as $ddt) {
				$idRow[]=$ddt['rowid'];
			}
			foreach ($kode_all as $keys => $val) {
				$detailTrans = [ 
					'kode_kuota_lembur'=>$kode,
					'kode_bagian'=>$val,
					'persen'=>$persen_all[$keys],
					'kuota'=>$kuota_all[$keys],
					'sisa_kuota'=> $kuota_all[$keys],
				];
				$detailTrans=array_merge($detailTrans,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($detailTrans,'detail_kuota_lembur');
			}
			// $this->model_global->insertQueryNoMsg($detailTrans,'detail_kuota_lembur');
			$dataFix=[ 
				'kode'			=> $kode,
				'nama'			=> $nama,
				'tgl_mulai'		=> $tgl_mulai,
				'tgl_selesai'	=> $tgl_selesai,
				'total_kuota'	=> $total_b,
			];
			$dataFix=array_merge($dataFix,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($dataFix,'master_kuota_lembur');
			$this->cart->destroy();
		}else{
			$datax=$this->messages->notValidParam();
		}
        echo json_encode($datax);
    }
	public function edit_kuota_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$tanggal 		= $this->input->post('tanggal');
		$tgl_mulai 		= date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'start')));
		$tgl_selesai 	= date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'end')));
		if ($id != "") {
			$data=[
				'kode'			=>$this->input->post('kode'),
				'nama'			=>$this->input->post('nama'),
				// 'bulan'			=>$this->input->post('bulan'),
				// 'tahun'			=>$this->input->post('tahun'),
				'tgl_mulai'		=> $tgl_mulai,
				'tgl_selesai'	=> $tgl_selesai,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'master_kuota_lembur',['id_kuota_lembur'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function getCartForDuplicate()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_table'){
				$kode=$this->input->post('kode');
				$jumlah=$this->input->post('jumlah');
				$after=$this->input->post('after');
				if(empty($after)){
					$this->cart->destroy();
					$detail=$this->model_master->getListDetailKuotaLembur(['a.kode_kuota_lembur'=>$kode]);
					if(!empty($detail)){
						foreach ($detail as $d) {
							$cart = [
								'id'                => $d->kode_bagian,
								'name'				=> $d->kode_bagian,
								'qty'				=> 1,
								'price'				=> (int)round((($d->persen/100)*$jumlah), 0),
								'persen'			=> $d->persen,
							];
							$cartx = $this->cart->insert($cart);
						}
					}
				}
				$no=1;
                $datax['data']=[];
                $data = $this->cart->contents();
                foreach ($data as $d) {
					$bag = $this->model_master->getBagianKode($d['name']);
					$nama = ($d['name'] == 'buff') ? 'KUOTA BUFFER' : $bag['nama'].' - ('.$bag['nama_loker'].')';
                    $aksi = '<a href="javascript:void(0)" onclick=deleteChart("'.$d['rowid'].'") class="btn btn-danger btn-sm"><span class="fa fa-close"></span> Hapus</a>';
					$datax['data'][]=[
						$no.'<input type="hidden" id="kodedup'.$no.'" name="kode_all[]" value="'.$d['name'].'"><input type="hidden" id="iddup'.$no.'" name="id_all[]" value="'.$d['rowid'].'">',
						$nama,
						'<input type="number" id="persendup'.$no.'" class="form-control" name="persen_all[]" value="'.$d['persen'].'" style="width: 100%;" readonly>',
						'<input type="number" id="kuotadup'.$no.'" class="form-control" name="kuota_all[]" value="'.$d['price'].'" style="width: 100%;" readonly>'.'<input type="hidden" name="id_barang_all[]" value="'.$d['id'].'">',
                        // $aksi.,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif($usage == 'view_table_after'){
				$no=1;
                $datax['data']=[];
                $data = $this->cart->contents();
                foreach ($data as $d) {
					$bag = $this->model_master->getBagianKode($d['name']);
					$nama = ($d['name'] == 'buff') ? 'KUOTA BUFFER' : $bag['nama'].' - ('.$bag['nama_loker'].')';
                    $aksi = '<a href="javascript:void(0)" onclick=deleteChart("'.$d['rowid'].'") class="btn btn-danger btn-sm"><span class="fa fa-close"></span> Hapus</a>';
					$datax['data'][]=[
						$no.'<input type="hidden" id="kodedup'.$no.'" name="kode_all[]" value="'.$d['name'].'"><input type="hidden" id="iddup'.$no.'" name="id_all[]" value="'.$d['rowid'].'">',
						$nama,
						'<input type="number" id="persendup'.$no.'" class="form-control" name="persen_all[]" value="'.$d['persen'].'" style="width: 100%;">',
						'<input type="number" id="kuotadup'.$no.'" class="form-control" name="kuota_all[]" value="'.$d['price'].'" style="width: 100%;" readonly>'.'<input type="hidden" name="id_barang_all[]" value="'.$d['id'].'">',
                        // $aksi.,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif($usage == 'getPersenKuota'){
                $persen=$this->input->post('persen');
                $id=$this->input->post('id');
                $jumlah_kuota=$this->input->post('jumlah_kuota');
                $jumlah_kuota=($jumlah_kuota == 0 || empty($jumlah_kuota)) ? 1 : $jumlah_kuota;
				$data=$this->cart->update(['rowid' => $id, 'persen' => $persen, 'jumlah_kuota' => $jumlah_kuota, 'price' => 0]);
				echo json_encode($data);
			}
		}
	}
	public function duplicate_master_kuota()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
        $nama = $this->input->post('nama');
		if(!empty($nama)){
			$kode          	= $this->codegenerator->kodeKuotaLembur();
			$tanggal 		= $this->input->post('tanggal');
			// $bulan 			= $this->input->post('bulan');
			// $tahun 			= $this->input->post('tahun');
			$total_b	   	= $this->cart->total();//$this->input->post('total_b');
			$kode_all		= $this->input->post('kode_all');
			$id_all		 	= $this->input->post('id_all');
			$kuota_all		= $this->input->post('kuota_all');
			$persen_all		= $this->input->post('persen_all');
			$tgl_mulai 		= date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'start')));
			$tgl_selesai 	= date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'end')));
			$dd = $this->cart->contents();
			$idRow=[];
			foreach ($dd as $ddt) {
				$idRow[]=$ddt['rowid'];
			}
			foreach ($kode_all as $keys => $val) {
				$detailTrans = [ 
					'kode_kuota_lembur'=>$kode,
					'kode_bagian'=>$val,
					'persen'=>$persen_all[$keys],
					'kuota'=>$kuota_all[$keys],
					'sisa_kuota'=> $kuota_all[$keys],
				];
				// print_r($detailTrans);
				$detailTrans=array_merge($detailTrans,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($detailTrans,'detail_kuota_lembur');
			}
			$dataFix=[ 
				'kode'			=> $kode,
				'nama'			=> $nama,
				'tgl_mulai'		=> $tgl_mulai,
				'tgl_selesai'	=> $tgl_selesai,
				'total_kuota'	=> $total_b,
			];
			// print_r($dataFix);
			$dataFix=array_merge($dataFix,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($dataFix,'master_kuota_lembur');
			$this->cart->destroy();
		}else{
			$datax=$this->messages->notValidParam();
		}
        echo json_encode($datax);
    }
	//============================================ DETAIL KUOTA LEMBUR =================================================================//
	public function detail_kuota_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
				$data=$this->model_master->getListDetailKuotaLembur(['a.kode_kuota_lembur'=>$kode]);
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
					$bagian = ($d->kode_bagian == 'buff') ? 'KUOTA BUFFER' : $d->nama_bagian;
					$datax['data'][]=[
						$d->id,
						$bagian,
						$d->nama_loker,
						$d->persen.'%',
						$d->kuota,
						$d->sisa_kuota,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id');
				$data=$this->model_master->getListDetailKuotaLembur(['a.id'=>$id]);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id,
						'kode'=>$d->kode_kuota_lembur,
						'kode_bagian'=>$d->kode_bagian,
						'nama'=> ($d->kode_bagian == 'buff') ? 'KUOTA BUFFER' : $d->nama_bagian,//;$d->nama_bagian,
						'nama_lokasi'=>$d->nama_loker,
						'persen_v'=>$d->persen.'%',
						'persen'=>$d->persen,
						'kuota'=>$d->kuota,
						'sisa_kuota'=>$d->sisa_kuota,
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
				$data = $this->codegenerator->kodeBank();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_detail_kuota_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode_kuota_lembur');
		if ($kode != "") {
			$data=[
				'kode_kuota_lembur'	=> $this->input->post('kode_kuota_lembur'),
				'kode_bagian'		=> $this->input->post('bagian'),
				'kuota'				=> $this->input->post('kuota'),
				'sisa_kuota'		=> $this->input->post('kuota'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'detail_kuota_lembur');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_detail_kuota_lembur()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data=[
				'kode_kuota_lembur'=>$this->input->post('kode'),
				'kode_bagian'=>$this->input->post('bagian'),
				'persen'=>$this->input->post('persen'),
				'kuota'=>$this->input->post('kuota'),
				'sisa_kuota'=>$this->input->post('sisa_kuota'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'detail_kuota_lembur',['id'=>$id]);
		}else{
        	$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function sendwa()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$nomor=$this->input->post('nomor');
		$pesan=$this->input->post('pesan');
		if ($nomor != "" && $pesan != "") {
			$send = $this->curl->sendwaapi($nomor, $pesan);
			// $send = $this->curl->sendWapisender($nomor, $pesan);
			// $msg = json_decode($send);
			echo '<pre>';
			// // print_r($nomor);
			// // print_r($msg->message);
			print_r($send);
			var_dump($send);
			// if($send){
			// 	echo 'ada';
			// }else{
			// 	echo 'error';
			// }
			// if(isset($msg->status) && $msg->status) {
				// $datax=$this->messages->customGood($msg->message);
			// }else{
			// 	$datax=$this->messages->customFailure('ERROR');
			// }
		}else{
			// $datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://wapisender.id/api/v1/send-message?api_key=HgBCfhjwX0un7VFvoypCWe16rSV14EQh&device_key=3kw0py&destination=085725951044&message=tes',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => false,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);
        // echo $response;
	}
}	
