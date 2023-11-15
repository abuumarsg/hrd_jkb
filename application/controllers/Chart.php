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

class Chart extends CI_Controller
{	
	function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		$this->max_range=$this->otherfunctions->poin_max_range();
		$this->max_month=$this->otherfunctions->column_value_max_range();

		if ($this->session->has_userdata('adm')) {
			$this->admin = $this->session->userdata('adm')['id'];	 
		}else{ 
			redirect('auth');
		}
	    $this->rando = $this->codegenerator->getPin(6,'number');		
		$dtroot['admin']=$this->model_admin->adm($this->admin);
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
	public function chart_datein_employee()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$data_label=$this->model_karyawan->getEmployeeDateInChart()['tahun'];
		$data_fill['Karyawan Masuk']=$this->model_karyawan->getEmployeeDateInChart()['jumlah'];
		$data_fill['Karyawan Keluar']=$this->model_karyawan->getEmployeeDateInChart()['jumlah_non'];
		foreach ($data_fill as $k=>$df) {
			$data_prop[]=[
				'label'=>$k,
				'fill'=>'false',
				'borderColor'=>$this->otherfunctions->getRandomColor(),
				'backgroundColor'=>$this->otherfunctions->getRandomColor(),
				'data'=>$df,
			];
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_dateinout_month()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		parse_str($this->input->post('param'),$search);
		$unit=$search['chart_kar_loker'];
		$tahun=$search['chart_kar_year'];
		$getBulan = $this->formatter->getMonth();
		$data_label = [];
		foreach ($getBulan as $gb =>$bulan) {
			$aa = $this->model_karyawan->getEmpMonth($gb,$unit,$tahun);
			$bb = $this->model_karyawan->getEmpNonAktifMonth($gb,$unit,$tahun);
			array_push($data_label,$bulan);
			$data_fill['Karyawan Masuk'][]=$aa;
			$data_fill['Karyawan Keluar'][]=$bb;
		}
		$color=[$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor()];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_gender_employee()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$data_label=$this->model_karyawan->getEmployeeGenderChart()['kelamin'];
		$data_fill=$this->model_karyawan->getEmployeeGenderChart()['jumlah'];
		$color=[];
		foreach ($data_label as $k=> $d) {
			$color[$k]=$this->otherfunctions->getRandomColor();
		}
		$data_prop[]=[
			'fill'=>'false',
			'borderColor'=>$color,
			'backgroundColor'=>$color,
			'data'=>$data_fill,
		];
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_loker_employee()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$data_label=$this->model_karyawan->getEmployeeLokerChart()['loker'];
		$data_fill=$this->model_karyawan->getEmployeeLokerChart()['jumlah'];
		$color=[];
		foreach ($data_label as $k=> $d) {
			$color[$k]=$this->otherfunctions->getRandomColor();
		}
		$data_prop[]=[
			'fill'=>'false',
			'borderColor'=>$color,
			'backgroundColor'=>$color,
			'data'=>$data_fill,
		];
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_bagian_employee()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$data_label=$this->model_karyawan->getEmployeeBagianChart()['bagian'];
		$data_fill=$this->model_karyawan->getEmployeeBagianChart()['jumlah'];
		$color=[];
		foreach ($data_label as $k=> $d) {
			$color[$k]=$this->otherfunctions->getRandomColor();
		}
		$data_prop[]=[
			'fill'=>'false',
			'borderColor'=>$color,
			'backgroundColor'=>$color,
			'data'=>$data_fill,
		];
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_status_kar()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		parse_str($this->input->post('param'),$search);
		$unit=$search['loker_status'];
		$getStatus = $this->model_master->getListStatusKaryawan();
		$data_label = [];
		$data_fill = [];
		$color = [];
		$m=0;
		foreach ($getStatus as $gs) {
			array_push($data_label,$gs->nama);
			$color[$m]=$this->otherfunctions->getRandomColor();
			$aa = $this->model_karyawan->getstatusEmp($gs->kode_status,$unit);
			$data_fill['Status Karyawan'][]=$aa;
			$m++;
		}
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color,
					'backgroundColor'=>$color,
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_jenis_kelamin()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		parse_str($this->input->post('param'),$search);
		$unit=$search['loker_jk'];
		$getKelamin = $this->otherfunctions->getGenderList();
		$data_label = [];
		$data_fill = [];
		$color = [];
		$m=0;
		foreach ($getKelamin as $gk => $val_gk) {
			$aa = $this->model_karyawan->getJenisKelaminEmp($gk,$unit);
			array_push($data_label,$val_gk);
			$color[$m]=$this->otherfunctions->getRandomColor();
			$data_fill['Jenis Kelamin'][]=$aa;
			$m++;
		}
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color,
					'backgroundColor'=>$color,
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_agama()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		parse_str($this->input->post('param'),$search);
		$unit=$search['loker_agama'];
		$getAgama = $this->otherfunctions->getReligionList();//$this->model_karyawan->agama();
		$data_label = [];
		$data_fill = [];
		$color = [];
		$m=0;
		foreach ($getAgama as $ga => $val_ga) {
			$aa = $this->model_karyawan->getAgamaEmp($ga,$unit);
			array_push($data_label,$val_ga);
			$color[$m]=$this->otherfunctions->getRandomColor();
			$data_fill['Agama'][]=$aa;
			$m++;
		}
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color,
					'backgroundColor'=>$color,
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function view_select()
	{
		if (!$this->input->is_ajax_request()) 
		  	redirect('not_found');
		$kode = $this->input->post('kode_bagian');
		if($kode == ''){
			$data = $this->model_karyawan->getEmployeeAllActive();
		}else{
			$data = $this->model_karyawan->getEmployeeWhere(['jbt.kode_bagian'=>$kode]);
		}
		$datax='';
		foreach ($data as $d) {
			$datax.='<option value="'.$d->id_karyawan.'">'.$d->nama.' ('.$d->nama_jabatan.')</option>';
		}
		echo json_encode($datax);
	}
	public function chart_izin_cuti_bagian()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		parse_str($this->input->post('param'),$search);
		if(!empty($search['bagian_izinpre'])){
			$bagian=$search['bagian_izinpre'];
		}else{
			$bagian=null;
		}
		if(!empty($search['bulan'])){
			$bulan=$search['bulan'];
		}else{
			$bulan=date('m');
		}
		if(!empty($search['tahun'])){
			$tahun=$search['tahun'];
		}else{
			$tahun=date('Y');
		}
		$cekTanggal = $this->formatter->cekValueTanggal($bulan,$tahun);
		$from = $tahun.'-'.$bulan.'-01';
		$to = $tahun.'-'.$bulan.'-'.$cekTanggal;
		$where = ['jbt.kode_bagian'=>$bagian,'emp.status_emp'=>'1'];
		$employee = $this->model_karyawan->getEmployeeWhere($where);
		$data_label = [];
		$bulanx = $this->formatter->getNameOfMonth($bulan).' '.$tahun;
		array_push($data_label,$bulanx);
		if(!empty($bagian) && !empty($employee)){
			$hariKerja=$this->model_master->getGeneralSetting('PTML')['value_int'];
			foreach($employee as $d){
				$dataEmp = ['id_karyawan'=>$d->id_karyawan,'gaji_pokok'=>0,'tgl_masuk'=>'2021-09-09','wfh'=>null,'hkwfh'=>null,'hknwfh'=>null];
				$dataPay = $this->payroll->getPresensiIzinPayroll($dataEmp,$from,$to)['countPresensi'];
				if(!empty($dataPay)){
					$totalHari = $this->formatter->cekValueTanggal($bulan, $tahun);
					$jumlah_minggu = $this->formatter->countLibur($bulan, $tahun);
					$hariKerja = $totalHari-$jumlah_minggu;
					$dataPay = ($dataPay>$hariKerja)?$hariKerja:$dataPay;
					$hasil=ceil(($dataPay/$hariKerja)*100);
					// $hasil=($dataPay/$hariKerja)*100;
				}else{
					$hasil=0;
				}
				$data_fill[$d->nama][]=$hasil;
			}
		}else{
			$data_fill['Karyawan'][]=0;
		}
		$color=[$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor()];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_izin_cuti_bagianx()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		parse_str($this->input->post('param'),$search);
		if(!empty($search['kar_izinBag'])){
			$employee=$search['kar_izinBag'];
		}else{
			$employee=null;
		}
		if(!empty($search['tanggal_izinBag'])){
			$tanggal=$search['tanggal_izinBag'];
			$mulaix = $this->otherfunctions->getDataExplode($tanggal,' - ','start');
			$selesaix = $this->otherfunctions->getDataExplode($tanggal,' - ','end');
			$mulai = $this->formatter->getDateFormatDb($mulaix);
			$selesai = $this->formatter->getDateFormatDb($selesaix);
		}else{
			$mulai=null;
			$selesai=null;
		}
		// print_r($employee);
		// $wherex = array_merge($bagian,$loker);
		// $wherex = $bagian;
		// $where = (!empty($wherex)?$wherex:['emp.loker'=>'ll']);
		// $employee = $this->model_karyawan->getEmployeeWhere($where);
		// $data_label = [];
		// $jenis=$this->model_master->getMasterIzin(null,['a.status'=>1]);
		// foreach ($jenis as $j) {
		// 	// $labelx = $this->otherfunctions->getDataExplode($j->nama,' ','start');
		// 	// array_push($data_label,$labelx);
		// 	array_push($data_label,$j->nama);
		// 	if(!empty($employee)){
		// 		foreach($employee as $d){
		// 			$aa = $this->model_karyawan->getEmpIzinBagian($d->id_karyawan,$j->kode_master_izin,$mulai,$selesai);
		// 			$data_fill[$d->nama][]=$aa;
		// 		}
		// 	}else{
		// 		$data_fill['Karyawan'][]=0;
		// 	}
		// }
		// if(!empty($employee)){
		// 	foreach($employee as $kar => $id_karyawan){
		// 		$nama_karyawan = $this->model_karyawan->getEmployeeId($id_karyawan)['nama'];
		// 		array_push($data_label,$nama_karyawan);
		// 		foreach ($jenis as $j) {
		// 			$aa = $this->model_karyawan->getEmpIzinBagian($id_karyawan,$j->kode_master_izin,$mulai,$selesai);
		// 			$data_fill[$j->nama][]=$aa;
		// 		}
		// 	}
		// }else{
		// 	$data_fill['Karyawan'][]=0;
		// }
		$data_label = [];
		$jenis=$this->model_master->getMasterIzin(null,['a.status'=>1]);
		$dJenis = [];
		$alfa = ['A'=>'ALFA'];
		foreach ($jenis as $j) {
			$dJenis[$j->kode_master_izin]=$j->nama;
		}
		$dataLabel = array_merge($alfa, $dJenis);
		if(!empty($employee)){
			foreach($employee as $kar => $id_karyawan){
				$nama_karyawan = $this->model_karyawan->getEmployeeId($id_karyawan)['nama'];
				array_push($data_label,$nama_karyawan);
				foreach ($dataLabel as $kode => $nama) {
					if($kode == 'A'){
						$countAlpa = 0;
						$date_loop=$this->formatter->dateLoopFull($mulai,$selesai);
						$dataPayx = $this->model_presensi->getDetailPresensiForPayroll($id_karyawan,$mulai,$selesai);
						foreach ($dataPayx as $d) {
							if(in_array($d->tanggal,$date_loop)){
								if($d->kode_shift == 'SSP' || $d->kode_shift == 'SSS' || $d->kode_shift == 'SSM' || $d->kode_shift == 'SSL'){
									if(empty($d->jam_mulai) && empty($d->jam_selesai) && $d->kode_shift != 'SSL'){
										$countAlpa += 1;
									}
								}else{
									$libur =  $this->otherfunctions->checkHariLiburActive($d->tanggal);
									if(empty($d->jam_mulai) && empty($d->jam_selesai) && empty($d->kode_hari_libur) && empty($d->kode_ijin) && empty($d->no_spl)){
										if(!isset($libur)){
											$countAlpa += 1;
										}
									}elseif((!empty($d->jam_mulai) && !empty($d->jam_selesai)) && empty($d->kode_hari_libur) && empty($d->kode_ijin) && ($d->jam_mulai == '00:00:00' && $d->jam_selesai == '00:00:00')){
										if(!isset($libur)){
											$countAlpa += 1;
										}
									}
								}
							}
						}
						$aa = $countAlpa;
					}else{
						$aa = $this->model_karyawan->getEmpIzinBagian($id_karyawan,$kode,$mulai,$selesai);
					}
					$data_fill[$nama][]=$aa;
				}
			}
		}else{
			$data_fill['Karyawan'][]=0;
		}
		$color=[$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor()];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_izin_cuti()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		parse_str($this->input->post('param'),$search);
		if(!empty($search['loker_izin'])){
			$unit=$search['loker_izin'];
		}else{
			$unit=null;
		}
		if(!empty($search['tahun_izin'])){
			$tahun=$search['tahun_izin'];
		}else{
			$tahun=null;
		}
		if(!empty($search['bagian_izin'])){
			$bagian=$search['bagian_izin'];
		}else{
			$bagian=null;
		}
		if(!empty($search['karyawan_izin'])){
			$karyawan=$search['karyawan_izin'];
		}else{
			$karyawan=null;
		}
		$getBulan = $this->formatter->getMonth();
		$data_label = [];
		$jenis=$this->model_master->getMasterIzin(null,['a.status'=>1]);
		$dJenis = [];
		$alfa = ['A'=>'ALFA'];
		foreach ($jenis as $j) {
			$dJenis[$j->kode_master_izin]=$j->nama;
		}
		foreach ($getBulan as $gb =>$bulan) {
			array_push($data_label,$bulan);
			$datac = array_merge($alfa,$dJenis);
			foreach ($datac as $kode => $nama) {
				if($kode == 'A'){
					if($tahun != null){
						$alpha = 0;
						for($i=1;$i<32;$i++){
							$hari = ($i < 10)?'0'.$i:$i;
							$tanggal=$tahun.'-'.$gb.'-'.$hari;
							if($karyawan != null && $bagian == null){
								$presensi = $this->model_karyawan->getListPresensiId(null,['pre.tanggal'=>$tanggal,'pre.id_karyawan'=>$karyawan],null,'row');
							}elseif($karyawan == null && $bagian != null){
								$presensi = $this->model_karyawan->getListPresensiId(null,['pre.tanggal'=>$tanggal,'jbt.kode_bagian'=>$bagian],null,'row');
							}elseif($karyawan != null && $bagian != null){
								$presensi = $this->model_karyawan->getListPresensiId(null,['pre.tanggal'=>$tanggal,'pre.id_karyawan'=>$karyawan,'jbt.kode_bagian'=>$bagian],null,'row');
							}else{
								$presensi = $this->model_karyawan->getListPresensiId(null,['pre.tanggal'=>$tanggal],null,'row');
							}
							$libur = $this->otherfunctions->checkHariLiburActive($tanggal);
							if(empty($presensi)){
								$cekTanggal = $this->formatter->cekValueTanggal($gb,$tahun);
								if($i < $cekTanggal){
									if(!isset($libur)){
										$alpha += 1;
									}
								}
							}
						}
					}
					$aa = $alpha;
				}else{
					$aa = $this->model_karyawan->getEmpIzinChart($gb,$kode,$unit,$tahun,$bagian);
				}
				$data_fill[$nama][]=$aa;
			}
		}
		$color=[$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor()];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_peringatan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		parse_str($this->input->post('param'),$search);
		if(!empty($search['loker_peringatan'])){
			$unit=$search['loker_peringatan'];
		}else{
			$unit=null;
		}
		if(!empty($search['tahun_peringatan'])){
			$tahun=$search['tahun_peringatan'];
		}else{
			$tahun=null;
		}
		$getBulan = $this->formatter->getMonth();
		$data_label = [];
		$jenis=$this->model_master->getListSuratPeringatan();
		foreach ($getBulan as $gb =>$bulan) {
			array_push($data_label,$bulan);
			foreach ($jenis as $jns) {
				$aa = $this->model_karyawan->getEmpPeringatan($gb,$jns->kode_sp,$unit,$tahun);
				$data_fill[$jns->nama][]=$aa;
			}
		}
		$color=[$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor(),$this->otherfunctions->getRandomColor()];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_bagian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		parse_str($this->input->post('param'),$search);
		if(isset($search['loker_bagian'])){
			$unit=$search['loker_bagian'];
			$data_fill=$this->model_karyawan->getBagianEmp($unit)['jumlah'];
		}else{
			$data_fill=$this->model_karyawan->getBagianEmp()['jumlah'];
		}
		$data_label=$this->model_karyawan->getBagianEmp()['bagian'];
		$color=[];
		foreach ($data_label as $k=> $d) {
			$color[$k]=$this->otherfunctions->getRandomColor();
		}
		$data_prop[]=[
			'fill'=>'false',
			'borderColor'=>$color,
			'backgroundColor'=>$color,
			'data'=>$data_fill,
		];
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function chart_pendidikan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		parse_str($this->input->post('param'),$search);
		$unit=$search['loker_pendidikan'];
		$getPendidikan = $this->otherfunctions->getEducateList();
		$data_label = [];
		$data_fill = [];
		foreach ($getPendidikan as $gk => $val_edu) {
			$aa = $this->model_karyawan->getPendidikanEmp($gk,$unit);
			array_push($data_label,$val_edu);
			$data_fill['Pendidikan'][]=$aa;
		}
		$color=$this->otherfunctions->getRandomColor();
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color,
					'backgroundColor'=>$color,
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	public function dashboard_kpi()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if($usage=='bo'){			
			parse_str($this->input->post('search_param'),$search);
			$id=$search['karyawan_kpi'];
		}elseif($usage=='fo'){
			parse_str($this->input->post('search_param'),$search);
			$id=$this->codegenerator->decryptChar($this->input->post('id_karyawan'));
		}
		$tahun=date('Y');
		$periode=null;
		if(isset($search['tahun_filter'])){
			$tahun=$search['tahun_filter'];
		}
		if(isset($search['agenda_filter'])){
			$periode=$search['agenda_filter'];
		}
		if(isset($search['kuartal_filter'])){
			$periode=$search['kuartal_filter'];
		}
		$data_tahun=$this->model_agenda->getListAgendaKpiTahun($tahun,$periode);	
		if (isset($data_tahun)) {
			foreach ($data_tahun as $dtahun) {
				$table[$dtahun->nama_tabel]=$dtahun->nama_tabel;
			}
		}
		$data_label=[];
		$data_fill=[];
		if (isset($table)) {
			foreach ($table as $tb) {
				$data_tabel=$this->model_agenda->openTableAgendaIdEmployeeChart($tb,$id,null);
				if (isset($data_tabel)) {
					foreach ($data_tabel as $dt) {
						for ($i=1; $i <=5 ; $i++) { 
							$col='pn'.$i;
							$pack_nilai[$col]=(!empty($dt->$col))?$this->exam->getNilaiAverage($dt->$col):0;
						}
						for ($ii=1; $ii <=4 ; $ii++) { 
							$coll='pn'.$ii;
							$pack_nilaii[$coll]=(!empty($dt->$coll))?$this->exam->getNilaiAverage($dt->$coll):0;
						}
						$average=null;
						if (isset($dt->cara_menghitung) && isset($pack_nilaii)) {
							if ($dt->cara_menghitung == 'SUM') {
								$average=$this->rumus->rumus_sum($pack_nilaii);
							}else{
								$average=$this->rumus->rumus_avg($pack_nilaii);
							}
						}
						//if isnot define
						if($average == null){
							$d_kpi=$this->model_master->getKpiKode($dt->kode_kpi);
							if (isset($d_kpi['cara_menghitung']) && isset($pack_nilaii)) {
								if ($d_kpi['cara_menghitung'] == 'SUM') {
									$average=$this->rumus->rumus_sum($pack_nilaii);
								}else{
									$average=$this->rumus->rumus_avg($pack_nilaii);
								}
							}
						}
						$gap=$this->exam->rumusProsentase($dt->target,$average);
						$arr_data=[
							'jenis_satuan'=>$dt->jenis_satuan,
							'sifat'=>$dt->sifat,
						];
						for ($i=1;$i<=$this->max_range;$i++){
							$p='poin_'.$i;
							$s='satuan_'.$i;
							$arr_data[$p]=$dt->$p;
							$arr_data[$s]=$dt->$s;
							if ($arr_data[$p] == null) {
								$arr_data[$s]=null;
							}
						}
						$na_konv=$this->exam->coreConversiKpi($average,$arr_data);
						$na_final=$na_konv*($dt->bobot/100);
						array_push($data_label, $dt->kpi);
						$data_kpi[$dt->kpi]=[
							"nama_kpi"=>$dt->kpi,
						];
						$n_final=($dt->jenis_satuan==1)?$average:$this->otherfunctions->getMark();
						$na_target=($dt->jenis_satuan==1)?$dt->target:$this->otherfunctions->getMark();
						$na_gap=($dt->jenis_satuan==1)?$gap:$this->otherfunctions->getMark();
						$nilai[$dt->kpi][$tb]=$n_final;
						$data_fill['Target'][]=$na_target;
						$data_fill['GAP'][]=$na_gap;
					}
					if(isset($data_kpi)){
						foreach ($data_kpi as $k_kpi => $kpi) {
							$nilai_akhir=((isset($nilai[$k_kpi]))?array_sum($nilai[$k_kpi]):0);
							$data_fill['Nilai'][]=number_format($nilai_akhir,2);
						}
					}
				}
			}
		}
		$color=['#4286f4','#f7ef00','#be00c1','#02ce7c'];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>((isset($data_prop))?$data_prop:[]),
		];
		echo json_encode($data);
	}
	public function dashboard_kpi_tabel()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if($usage=='bo'){			
			parse_str($this->input->post('search_param'),$search);
			$id=$search['karyawan_kpi'];
		}elseif($usage=='fo'){
			parse_str($this->input->post('search_param'),$search);
			$id=$this->codegenerator->decryptChar($this->input->post('id_karyawan'));
		}
		$tahun=date('Y');
		$periode=null;
		if(isset($search['tahun_filter'])){
			$tahun=$search['tahun_filter'];
		}
		if(isset($search['agenda_filter'])){
			$periode=$search['agenda_filter'];
		}
		if(isset($search['kuartal_filter'])){
			$periode=$search['kuartal_filter'];
		}
		$data_tahun=$this->model_agenda->getListAgendaKpiTahun($tahun,$periode);	
		if (isset($data_tahun)) {
			foreach ($data_tahun as $dtahun) {
				$table[$dtahun->nama_tabel]=$dtahun->nama_tabel;
			}
		}
		$datax['data']=[];
		if (isset($table)) {
			foreach ($table as $tb) {
				$data_tabel=$this->model_agenda->openTableAgendaIdEmployeeChart($tb,$id);
				if (isset($data_tabel)) {
					$noq=1;
					foreach ($data_tabel as $dt) {
						for ($i=1; $i <=$this->max_month ; $i++) { 
							$col='pn'.$i;
							$pack_nilai[$col]=(!empty($dt->$col))?$this->exam->getNilaiAverage($dt->$col):0;
						}
						for ($ii=1; $ii <=$this->max_month ; $ii++) { 
							$coll='pn'.$ii;
							$pack_nilaii[$coll]=(!empty($dt->$coll))?$this->exam->getNilaiAverage($dt->$coll):0;
						}
						$average=null;
						if (isset($dt->cara_menghitung) && isset($pack_nilaii)) {
							if ($dt->cara_menghitung == 'SUM') {
								$average=$this->rumus->rumus_sum($pack_nilaii);
							}else{
								$average=$this->rumus->rumus_avg($pack_nilaii);
							}
						}
						//if isnot define
						if($average == null){
							$d_kpi=$this->model_master->getKpiKode($dt->kode_kpi);
							if (isset($d_kpi['cara_menghitung']) && isset($pack_nilaii)) {
								if ($d_kpi['cara_menghitung'] == 'SUM') {
									$average=$this->rumus->rumus_sum($pack_nilaii);
								}else{
									$average=$this->rumus->rumus_avg($pack_nilaii);
								}
							}
						}
						$gap=$this->exam->rumusProsentase($dt->target,$average);
						$data_konv=$this->model_master->getKonversiGapNilai($gap);
						$color='green';
						if (isset($data_konv['warna'])) {
							$color=$data_konv['warna'];
						}
						$arr_data=[
							'jenis_satuan'=>$dt->jenis_satuan,
							'sifat'=>$dt->sifat,
						];
						for ($i=1;$i<=$this->max_range;$i++){
							$p='poin_'.$i;
							$s='satuan_'.$i;
							$arr_data[$p]=$dt->$p;
							$arr_data[$s]=$dt->$s;
							if ($arr_data[$p] == null) {
								$arr_data[$s]=null;
							}
						}
						$na_konv=$this->exam->coreConversiKpi($average,$arr_data);
						$na_final=$na_konv*($dt->bobot/100);
						$target=($dt->jenis_satuan==1)?$dt->target:$this->otherfunctions->getMark();
						$na_gap=($dt->jenis_satuan==1)?'<b style="font-size:25pt;color:'.$color.'">'.$this->formatter->getNumberFloat($gap).'%</b>':$this->otherfunctions->getMark();
						$nilai_final=($dt->jenis_satuan==1)?$average:$this->otherfunctions->getMark();
						$data_kpi[$dt->kode_kpi]=[
							"id_task"=>$dt->id_task,
							"kode_kpi"=>$dt->kode_kpi,
							"kpi"=>$dt->kpi,
							"sifat"=>$dt->sifat,
							"detail_rumus"=>$dt->detail_rumus,
							"target"=>$target,
							"gap"=>$na_gap,
						];
						$noq++;
						$nilai[$dt->kode_kpi][$tb]=$nilai_final;
					}
				}
			}
			if(isset($data_kpi)){
				foreach ($data_kpi as $k_kpi => $kpi) {
					$nilai_akhir=((isset($nilai[$k_kpi]))?array_sum($nilai[$k_kpi]):0);
					$datax['data'][]=[
						$kpi['id_task'],
						$kpi['kode_kpi'],
						$kpi['kpi'],
						$kpi['sifat'],
						$kpi['detail_rumus'],
						$kpi['target'],
						$this->formatter->getNumberFloat($nilai_akhir),
						$kpi['gap'],
					];
				}
			}
		}
		echo json_encode($datax);
	}
	public function dashboard_tahunan()
	{		
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if($usage=='backoffice'){
			parse_str($this->input->post('search_param'),$search);
			$id=$search['karyawan_tahunan'];
		}elseif($usage=='frontoffice'){
			parse_str($this->input->post('search_param'),$search);
			$id=$this->codegenerator->decryptChar($this->input->post('id_karyawan'));			
		}
		$jenis=['Wajib','Rutin','Tambahan'];
		parse_str($this->input->post('search_param'),$search);
		if (empty($search['tahun_awal']) && empty($search['tahun_akhir'])) {
			$first=date('Y', strtotime(date('Y',strtotime($this->otherfunctions->getDateNow())) . ' -4 year'));
			$end=substr($this->date,0,4);
			$date=range($first,$end);
		}else{
			$date=range($search['tahun_awal'],$search['tahun_akhir']);
		}
		$data_fill['Grafik Tahunan']=[];
		foreach ($date as $tahun) {
			$data_label[]=[$tahun];
			$data_raport=$this->model_agenda->getReportGroupEmployee(null,$tahun,$id);
			$data=$this->model_agenda->raportTahunan($data_raport);
			$na=number_format((($data['kpi']*($data['bobot_kpi']/100)+$data['sikap']*($data['bobot_sikap']/100))-$data['presensi']),2);
			array_push($data_fill['Grafik Tahunan'],$na);
		}
		$color=['#be00c1','#0CE407','#02ce7c','#4286f4','#f7ef00'];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>$data_prop,
		];
		echo json_encode($data);
	}
	// public function kpi_4tahunan()
	// {		
	// 	if (!$this->input->is_ajax_request()) 
	// 	   redirect('not_found');
	// 	$usage=$this->uri->segment(3);
	// 	if($usage=='backoffice'){
	// 		parse_str($this->input->post('search_param'),$search);
	// 		$id=$search['karyawan_tahunan'];
	// 		if(isset($search['bagian_filter'])){
	// 			$bagian=$search['bagian_filter'];
	// 		}else{
	// 			$bagian=null;
	// 		}
	// 		if(isset($search['lokasi_filter'])){
	// 			$lokasi=$search['lokasi_filter'];
	// 		}else{
	// 			$lokasi=null;
	// 		}
	// 	}elseif($usage=='frontoffice'){
	// 		parse_str($this->input->post('search_param'),$search);
	// 		$id=$this->codegenerator->decryptChar($this->input->post('id_karyawan'));			
	// 	}
	// 	if (empty($search['tahun_awal']) && empty($search['tahun_akhir'])) {
	// 		$first=date('Y', strtotime(date('Y',strtotime($this->otherfunctions->getDateNow())) . ' -4 year'));
	// 		$end=substr($this->date,0,4);
	// 		$date=range($first,$end);
	// 	}else{
	// 		$date=range($search['tahun_awal'],$search['tahun_akhir']);
	// 	}
	// 	// print_r($id);
	// 	$data_fill['Pencapaian']=[];
	// 	$data_fill['Terget']=[];
	// 	foreach ($date as $tahun) {
	// 		$data_label[]=[$tahun];
	// 		// $data_raport=$this->model_agenda->getReportGroupEmployee(null,$tahun,$id);
	// 		// $data=$this->model_agenda->raportTahunan($data_raport);
	// 		// $na=number_format((($data['kpi']*($data['bobot_kpi']/100)+$data['sikap']*($data['bobot_sikap']/100))-$data['presensi']),2);
	// 		$target = 0;
	// 		$capaian = 0;
	// 		$kpi=$this->model_agenda->getLogAgendaKpiTahun($tahun,false);
	// 		foreach ($kpi as $kp) {
	// 			$data_kpi=$this->model_agenda->rumusCustomKubotaFinalResultKpi($kp->nama_tabel,$id,null,null,['loker_filter'=>$lokasi,'bagian_filter'=>$bagian]);
	// 			if (isset($data_kpi['capaian']) && isset($data_kpi['target'])) {
	// 				$n_cap = 0;
	// 				$n_tar = 0;
	// 				foreach ($data_kpi['capaian'] as $key => $value) {
	// 					$n_cap+=$value;
	// 				}
	// 				foreach ($data_kpi['target'] as $keyt => $valuet) {
	// 					$n_tar+=$valuet;
	// 				}
	// 				$capaian +=($n_cap/count($data_kpi['capaian']));
	// 				$target+=($n_tar/count($data_kpi['target']));
	// 			}
	// 		}
	// 		array_push($data_fill['Pencapaian'],$capaian);
	// 		array_push($data_fill['Terget'],$target);
	// 	}
	// 	$color=['#be00c1','#0CE407','#02ce7c','#4286f4','#f7ef00'];
	// 	if (isset($data_fill)) {
	// 		$n=0;
	// 		foreach ($data_fill as $k=>$df) {
	// 			$data_prop[$n]=[
	// 				'label'=>$k,
	// 				'fill'=>'false',
	// 				'borderColor'=>$color[$n],
	// 				'backgroundColor'=>$color[$n],
	// 				'data'=>$df,
	// 			];
	// 			$n++;
	// 		}
	// 	}
	// 	$data=[
	// 		'labels'=>$data_label,
	// 		'datasets'=>$data_prop,
	// 	];
	// 	echo json_encode($data);
	// }
	// public function kpi_4tahunan()
	// {
	// 	if (!$this->input->is_ajax_request()) 
	// 	   redirect('not_found');
	// 	$usage=$this->uri->segment(3);
	// 	if($usage=='backoffice'){			
	// 		parse_str($this->input->post('search_param'),$search);
	// 		if(!empty($search['karyawan_tahunan'])){
	// 			$id=$search['karyawan_tahunan'];
	// 		}else{
	// 			$id=null;
	// 		}
	// 	}elseif($usage=='frontoffice'){
	// 		parse_str($this->input->post('search_param'),$search);
	// 		$id=$this->codegenerator->decryptChar($this->input->post('id_karyawan'));
	// 	}
	// 	if (empty($search['tahun_awal']) && empty($search['tahun_akhir'])) {
	// 		$first=date('Y', strtotime(date('Y',strtotime($this->otherfunctions->getDateNow())) . ' -4 year'));
	// 		$end=substr($this->date,0,4);
	// 		$date=range($first,$end);
	// 	}else{
	// 		$date=range($search['tahun_awal'],$search['tahun_akhir']);
	// 	}
	// 	$bagian=null;
	// 	$lokasi=null;
	// 	$kpi_filter = null;
	// 	if(isset($search['bagian_filter'])){
	// 		$bagian=$search['bagian_filter'];
	// 	}
	// 	if(isset($search['lokasi_filter'])){
	// 		$lokasi=$search['lokasi_filter'];
	// 	}
	// 	if(isset($search['kpi_filter'])){
	// 		$kpi_filter=$search['kpi_filter'];
	// 	}
	// 	$data_fill=[];
	// 	$data_label=[];
	// 	$color=[];
	// 	$m=0;
	// 	foreach ($date as $tahun) {
	// 		$data_label[]=$tahun;
	// 		$data_tahun=$this->model_agenda->getListAgendaKpiTahun($tahun);	
	// 		if (isset($data_tahun)) {
	// 			foreach ($data_tahun as $dtahun) {
	// 				$table[$dtahun->nama_tabel]=$dtahun->nama_tabel;
	// 			}
	// 		}
	// 		$color[$m]=$this->otherfunctions->getRandomColor();
	// 		$m++;
	// 	}
	// 	if (isset($table)) {
	// 		foreach ($table as $tb) {
	// 			$data_tabel=$this->model_agenda->openTableAgendaIdEmployeeChart($tb,$id,['loker_filter'=>$lokasi,'bagian_filter'=>$bagian,'kpi_filter'=>$kpi_filter]);
	// 			if (isset($data_tabel)) {
	// 				$na_target = 0;
	// 				foreach ($data_tabel as $dt) {
	// 					for ($i=1; $i <=5 ; $i++) { 
	// 						$col='pn'.$i;
	// 						$pack_nilai[$col]=(!empty($dt->$col))?$this->exam->getNilaiAverage($dt->$col):0;
	// 					}
	// 					for ($ii=1; $ii <=4 ; $ii++) { 
	// 						$coll='pn'.$ii;
	// 						$pack_nilaii[$coll]=(!empty($dt->$coll))?$this->exam->getNilaiAverage($dt->$coll):0;
	// 					}
	// 					$average=null;
	// 					if (isset($dt->cara_menghitung) && isset($pack_nilaii)) {
	// 						if ($dt->cara_menghitung == 'SUM') {
	// 							$average=$this->rumus->rumus_sum($pack_nilaii);
	// 						}else{
	// 							$average=$this->rumus->rumus_avg($pack_nilaii);
	// 						}
	// 					}
	// 					//if isnot define
	// 					if($average == null){
	// 						$d_kpi=$this->model_master->getKpiKode($dt->kode_kpi);
	// 						if (isset($d_kpi['cara_menghitung']) && isset($pack_nilaii)) {
	// 							if ($d_kpi['cara_menghitung'] == 'SUM') {
	// 								$average=$this->rumus->rumus_sum($pack_nilaii);
	// 							}else{
	// 								$average=$this->rumus->rumus_avg($pack_nilaii);
	// 							}
	// 						}
	// 					}
	// 					$gap=$this->exam->rumusProsentase($dt->target,$average);
	// 					$arr_data=[
	// 						'jenis_satuan'=>$dt->jenis_satuan,
	// 						'sifat'=>$dt->sifat,
	// 					];
	// 					for ($i=1;$i<=$this->max_range;$i++){
	// 						$p='poin_'.$i;
	// 						$s='satuan_'.$i;
	// 						$arr_data[$p]=$dt->$p;
	// 						$arr_data[$s]=$dt->$s;
	// 						if ($arr_data[$p] == null) {
	// 							$arr_data[$s]=null;
	// 						}
	// 					}
	// 					$na_konv=$this->exam->coreConversiKpi($average,$arr_data);
	// 					$na_final=$na_konv*($dt->bobot/100);
	// 					// array_push($data_label, $dt->kpi);
	// 					$data_kpi[$dt->kpi]=[
	// 						"nama_kpi"=>$dt->kpi,
	// 					];
	// 					$n_final=($dt->jenis_satuan==1)?$average:$this->otherfunctions->getMark();
	// 					$na_target+=($dt->jenis_satuan==1)?$dt->target:$this->otherfunctions->getMark();
	// 					$na_gap=($dt->jenis_satuan==1)?$gap:$this->otherfunctions->getMark();
	// 					$nilai[$dt->kpi][$tb]=$n_final;
	// 					// $data_fill['Target'][]=$na_target;
	// 					// $data_fill['GAP'][]=$na_gap;
	// 				}
	// 				$data_fill['Target'][]=$na_target;
	// 				if(isset($data_kpi)){
	// 					$na_capai = 0;
	// 					foreach ($data_kpi as $k_kpi => $kpi) {
	// 						$nilai_akhir=((isset($nilai[$k_kpi]))?array_sum($nilai[$k_kpi]):0);
	// 						$na_capai += $nilai_akhir;
	// 						// $data_fill['Capaian'][]=number_format($nilai_akhir,2);
	// 					}
	// 					$data_fill['Capaian'][] = number_format($na_capai,2);
	// 				}
	// 			}
	// 		}
	// 	}
	// 	// print_r($data_fill);
	// 	// $color=['#4286f4','#f7ef00','#be00c1','#02ce7c'];
	// 	if (isset($data_fill)) {
	// 		$n=0;
	// 		foreach ($data_fill as $k=>$df) {
	// 			$data_prop[$n]=[
	// 				'label'=>$k,
	// 				'fill'=>'false',
	// 				'borderColor'=>$color[$n],
	// 				'backgroundColor'=>$color[$n],
	// 				'data'=>$df,
	// 			];
	// 			$n++;
	// 		}
	// 	}
	// 	$data=[
	// 		'labels'=>$data_label,
	// 		'datasets'=>((isset($data_prop))?$data_prop:[]),
	// 	];
	// 	echo json_encode($data);
	// }
	public function kpi_4tahunan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if($usage=='backoffice'){			
			parse_str($this->input->post('search_param'),$search);
			if(!empty($search['karyawan_tahunan'])){
				$id=$search['karyawan_tahunan'];
			}else{
				$id=null;
			}
		}elseif($usage=='frontoffice'){
			parse_str($this->input->post('search_param'),$search);
			$id=$this->codegenerator->decryptChar($this->input->post('id_karyawan'));
		}
		if (empty($search['tahun_awal']) && empty($search['tahun_akhir'])) {
			$first=date('Y', strtotime(date('Y',strtotime($this->otherfunctions->getDateNow())) . ' -4 year'));
			$end=substr($this->date,0,4);
			$date=range($first,$end);
		}else{
			$date=range($search['tahun_awal'],$search['tahun_akhir']);
		}
		$bagian=null;
		$lokasi=null;
		$kpi_filter = null;
		$departemen_filter = null;
		if(isset($search['bagian_filter'])){
			$bagian=$search['bagian_filter'];
		}
		if(isset($search['lokasi_filter'])){
			$lokasi=$search['lokasi_filter'];
		}
		if(isset($search['kpi_filter'])){
			$kpi_filter=$search['kpi_filter'];
		}
		if(isset($search['departemen_filter'])){
			$departemen_filter=$search['departemen_filter'];
		}
		$data_fill=[];
		$data_label=[];
		$color=[];
		for ($m=0; $m < 10; $m++) { 
			$color[$m]=$this->otherfunctions->getRandomColor();
		}
		foreach ($date as $tahun) {
			$data_label[]=$tahun;
			$data_tahun=$this->model_agenda->getListAgendaKpiTahun($tahun);	
			if (isset($data_tahun)) {
				foreach ($data_tahun as $dtahun) {
					$table[$dtahun->nama_tabel]=$dtahun->nama_tabel;
				}
			}
		}
		if (isset($table)) {
			$mKpi = $this->model_master->getKpiWhere(['a.kpi_utama'=>1]);
			foreach ($table as $tb) {
				foreach ($mKpi as $kp) {
					$data_tabel=$this->model_agenda->openTableAgendaIdEmployeeChart($tb,$id,['loker_filter'=>$lokasi,'bagian_filter'=>$bagian,'kpi_filter'=>$kp->kode_kpi,'departemen_filter'=>$departemen_filter]);
					if (isset($data_tabel)) {
						$na_target = 0;
						foreach ($data_tabel as $dt) {
							for ($i=1; $i <=5 ; $i++) { 
								$col='pn'.$i;
								$pack_nilai[$col]=(!empty($dt->$col))?$this->exam->getNilaiAverage($dt->$col):0;
							}
							for ($ii=1; $ii <=4 ; $ii++) { 
								$coll='pn'.$ii;
								$pack_nilaii[$coll]=(!empty($dt->$coll))?$this->exam->getNilaiAverage($dt->$coll):0;
							}
							$average=null;
							if (isset($dt->cara_menghitung) && isset($pack_nilaii)) {
								if ($dt->cara_menghitung == 'SUM') {
									$average=$this->rumus->rumus_sum($pack_nilaii);
								}else{
									$average=$this->rumus->rumus_avg($pack_nilaii);
								}
							}
							//if isnot define
							if($average == null){
								$d_kpi=$this->model_master->getKpiKode($dt->kode_kpi);
								if (isset($d_kpi['cara_menghitung']) && isset($pack_nilaii)) {
									if ($d_kpi['cara_menghitung'] == 'SUM') {
										$average=$this->rumus->rumus_sum($pack_nilaii);
									}else{
										$average=$this->rumus->rumus_avg($pack_nilaii);
									}
								}
							}
							$gap=$this->exam->rumusProsentase($dt->target,$average);
							$arr_data=[
								'jenis_satuan'=>$dt->jenis_satuan,
								'sifat'=>$dt->sifat,
							];
							for ($i=1;$i<=$this->max_range;$i++){
								$p='poin_'.$i;
								$s='satuan_'.$i;
								$arr_data[$p]=$dt->$p;
								$arr_data[$s]=$dt->$s;
								if ($arr_data[$p] == null) {
									$arr_data[$s]=null;
								}
							}
							$na_konv=$this->exam->coreConversiKpi($average,$arr_data);
							$na_final=$na_konv*($dt->bobot/100);
							$data_kpi[$dt->kpi]=[
								"nama_kpi"=>$dt->kpi,
							];
							$n_final=($dt->jenis_satuan==1)?$average:$this->otherfunctions->getMark();
							$na_target=($dt->jenis_satuan==1)?$dt->target:$this->otherfunctions->getMark();
							$na_gap=($dt->jenis_satuan==1)?$gap:$this->otherfunctions->getMark();
							$nilai[$dt->kpi][$tb]=$n_final;
						}
						$data_fill['Target '.$kp->kpi][]=$na_target;
						if(isset($data_kpi)){
							$na_capai = 0;
							foreach ($data_kpi as $k_kpi => $kpi) {
								$nilai_akhir=((isset($nilai[$k_kpi]))?array_sum($nilai[$k_kpi]):0);
								$na_capai = $nilai_akhir;
							}
							$data_fill['Capaian '.$kp->kpi][] = number_format($na_capai,2);
						}
					}
				}
			}
		}
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>((isset($data_prop))?$data_prop:[]),
		];
		echo json_encode($data);
	}
	public function dashboard_kpi_new()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if($usage=='bo'){			
			parse_str($this->input->post('search_param'),$search);
			if(!empty($search['karyawan_kpi'])){
				$id=$search['karyawan_kpi'];
			}else{
				$id=null;
			}
		}elseif($usage=='fo'){
			parse_str($this->input->post('search_param'),$search);
			$id=$this->codegenerator->decryptChar($this->input->post('id_karyawan'));
		}
		$tahun=date('Y');
		$periode=null;
		$bagian=null;
		$lokasi=null;
		if(isset($search['tahun_filter'])){
			$tahun=$search['tahun_filter'];
		}
		if(isset($search['agenda_filter'])){
			$periode=$search['agenda_filter'];
		}
		if(isset($search['kuartal_filter'])){
			$periode=$search['kuartal_filter'];
		}
		if(isset($search['bagian_filter'])){
			$bagian=$search['bagian_filter'];
		}
		if(isset($search['lokasi_filter'])){
			$lokasi=$search['lokasi_filter'];
		}
		$data_tahun=$this->model_agenda->getListAgendaKpiTahun($tahun,$periode);	
		if (isset($data_tahun)) {
			foreach ($data_tahun as $dtahun) {
				$table[$dtahun->nama_tabel]=$dtahun->nama_tabel;
			}
		}
		$data_label=[];
		$data_fill=[];
		if (isset($table)) {
			foreach ($table as $tb) {
				$data_tabel=$this->model_agenda->openTableAgendaIdEmployeeChart($tb,$id,['loker_filter'=>$lokasi,'bagian_filter'=>$bagian]);
				if (isset($data_tabel)) {
					foreach ($data_tabel as $dt) {
						for ($i=1; $i <=5 ; $i++) { 
							$col='pn'.$i;
							$pack_nilai[$col]=(!empty($dt->$col))?$this->exam->getNilaiAverage($dt->$col):0;
						}
						for ($ii=1; $ii <=4 ; $ii++) { 
							$coll='pn'.$ii;
							$pack_nilaii[$coll]=(!empty($dt->$coll))?$this->exam->getNilaiAverage($dt->$coll):0;
						}
						$average=null;
						if (isset($dt->cara_menghitung) && isset($pack_nilaii)) {
							if ($dt->cara_menghitung == 'SUM') {
								$average=$this->rumus->rumus_sum($pack_nilaii);
							}else{
								$average=$this->rumus->rumus_avg($pack_nilaii);
							}
						}
						//if isnot define
						if($average == null){
							$d_kpi=$this->model_master->getKpiKode($dt->kode_kpi);
							if (isset($d_kpi['cara_menghitung']) && isset($pack_nilaii)) {
								if ($d_kpi['cara_menghitung'] == 'SUM') {
									$average=$this->rumus->rumus_sum($pack_nilaii);
								}else{
									$average=$this->rumus->rumus_avg($pack_nilaii);
								}
							}
						}
						$gap=$this->exam->rumusProsentase($dt->target,$average);
						$arr_data=[
							'jenis_satuan'=>$dt->jenis_satuan,
							'sifat'=>$dt->sifat,
						];
						for ($i=1;$i<=$this->max_range;$i++){
							$p='poin_'.$i;
							$s='satuan_'.$i;
							$arr_data[$p]=$dt->$p;
							$arr_data[$s]=$dt->$s;
							if ($arr_data[$p] == null) {
								$arr_data[$s]=null;
							}
						}
						$na_konv=$this->exam->coreConversiKpi($average,$arr_data);
						$na_final=$na_konv*($dt->bobot/100);
						array_push($data_label, $dt->kpi);
						$data_kpi[$dt->kpi]=[
							"nama_kpi"=>$dt->kpi,
						];
						$n_final=($dt->jenis_satuan==1)?$average:$this->otherfunctions->getMark();
						$na_target=($dt->jenis_satuan==1)?$dt->target:$this->otherfunctions->getMark();
						$na_gap=($dt->jenis_satuan==1)?$gap:$this->otherfunctions->getMark();
						$nilai[$dt->kpi][$tb]=$n_final;
						$data_fill['Target'][]=$na_target;
						// $data_fill['GAP'][]=$na_gap;
					}
					if(isset($data_kpi)){
						foreach ($data_kpi as $k_kpi => $kpi) {
							$nilai_akhir=((isset($nilai[$k_kpi]))?array_sum($nilai[$k_kpi]):0);
							$data_fill['Capaian'][]=number_format($nilai_akhir,2);
						}
					}
				}
			}
		}
		// print_r($data_fill);
		$color=['#4286f4','#f7ef00','#be00c1','#02ce7c'];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>((isset($data_prop))?$data_prop:[]),
		];
		echo json_encode($data);
	}
	public function kpi_4tahunan_allkpi()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if($usage=='backoffice'){			
			parse_str($this->input->post('search_param'),$search);
			if(!empty($search['karyawan_allkpi'])){
				$id=$search['karyawan_allkpi'];
			}else{
				$id=null;
			}
		}elseif($usage=='frontoffice'){
			parse_str($this->input->post('search_param'),$search);
			$id=$this->codegenerator->decryptChar($this->input->post('id_karyawan'));
		}
		if (empty($search['tahun_awal']) && empty($search['tahun_akhir'])) {
			$first=date('Y', strtotime(date('Y',strtotime($this->otherfunctions->getDateNow())) . ' -4 year'));
			$end=substr($this->date,0,4);
			$date=range($first,$end);
		}else{
			$date=range($search['tahun_awal'],$search['tahun_akhir']);
		}
		$bagian=null;
		$lokasi=null;
		$kpi_filter = null;
		$departemen_filter = null;
		if(isset($search['bagian_filter'])){
			$bagian=$search['bagian_filter'];
		}
		if(isset($search['lokasi_filter'])){
			$lokasi=$search['lokasi_filter'];
		}
		if(isset($search['kpi_filter'])){
			$kpi_filter=$search['kpi_filter'];
		}
		if(isset($search['departemen_filter'])){
			$departemen_filter=$search['departemen_filter'];
		}
		$data_fill=[];
		$data_label=[];
		$color=[];
		$m=0;
		foreach ($date as $tahun) {
			$data_label[]=$tahun;
			$data_tahun=$this->model_agenda->getListAgendaKpiTahun($tahun);	
			if (isset($data_tahun)) {
				foreach ($data_tahun as $dtahun) {
					$table[$dtahun->nama_tabel]=$dtahun->nama_tabel;
				}
			}
			$color[$m]=$this->otherfunctions->getRandomColor();
			$m++;
		}
		if (isset($table)) {
			foreach ($table as $tb) {
				$data_tabel=$this->model_agenda->openTableAgendaIdEmployeeChart($tb,$id,['loker_filter'=>$lokasi,'bagian_filter'=>$bagian,'kpi_filter'=>$kpi_filter,'departemen_filter'=>$departemen_filter]);
				if (isset($data_tabel)) {
					$na_target = 0;
					foreach ($data_tabel as $dt) {
						for ($i=1; $i <=5 ; $i++) { 
							$col='pn'.$i;
							$pack_nilai[$col]=(!empty($dt->$col))?$this->exam->getNilaiAverage($dt->$col):0;
						}
						for ($ii=1; $ii <=4 ; $ii++) { 
							$coll='pn'.$ii;
							$pack_nilaii[$coll]=(!empty($dt->$coll))?$this->exam->getNilaiAverage($dt->$coll):0;
						}
						$average=null;
						if (isset($dt->cara_menghitung) && isset($pack_nilaii)) {
							if ($dt->cara_menghitung == 'SUM') {
								$average=$this->rumus->rumus_sum($pack_nilaii);
							}else{
								$average=$this->rumus->rumus_avg($pack_nilaii);
							}
						}
						//if isnot define
						if($average == null){
							$d_kpi=$this->model_master->getKpiKode($dt->kode_kpi);
							if (isset($d_kpi['cara_menghitung']) && isset($pack_nilaii)) {
								if ($d_kpi['cara_menghitung'] == 'SUM') {
									$average=$this->rumus->rumus_sum($pack_nilaii);
								}else{
									$average=$this->rumus->rumus_avg($pack_nilaii);
								}
							}
						}
						$gap=$this->exam->rumusProsentase($dt->target,$average);
						$arr_data=[
							'jenis_satuan'=>$dt->jenis_satuan,
							'sifat'=>$dt->sifat,
						];
						for ($i=1;$i<=$this->max_range;$i++){
							$p='poin_'.$i;
							$s='satuan_'.$i;
							$arr_data[$p]=$dt->$p;
							$arr_data[$s]=$dt->$s;
							if ($arr_data[$p] == null) {
								$arr_data[$s]=null;
							}
						}
						$na_konv=$this->exam->coreConversiKpi($average,$arr_data);
						$na_final=$na_konv*($dt->bobot/100);
						// array_push($data_label, $dt->kpi);
						$data_kpi[$dt->kpi]=[
							"nama_kpi"=>$dt->kpi,
						];
						$n_final=($dt->jenis_satuan==1)?$average:$this->otherfunctions->getMark();
						$na_target+=($dt->jenis_satuan==1)?$dt->target:$this->otherfunctions->getMark();
						$na_gap=($dt->jenis_satuan==1)?$gap:$this->otherfunctions->getMark();
						$nilai[$dt->kpi][$tb]=$n_final;
						// $data_fill['Target'][]=$na_target;
						// $data_fill['GAP'][]=$na_gap;
					}
					$data_fill['Target'][]=$na_target;
					if(isset($data_kpi)){
						$na_capai = 0;
						foreach ($data_kpi as $k_kpi => $kpi) {
							$nilai_akhir=((isset($nilai[$k_kpi]))?array_sum($nilai[$k_kpi]):0);
							$na_capai += $nilai_akhir;
							// $data_fill['Capaian'][]=number_format($nilai_akhir,2);
						}
						$data_fill['Capaian'][] = number_format($na_capai,2);
					}
				}
			}
		}
		// $color=['#4286f4','#f7ef00','#be00c1','#02ce7c'];
		if (isset($data_fill)) {
			$n=0;
			foreach ($data_fill as $k=>$df) {
				$data_prop[$n]=[
					'label'=>$k,
					'fill'=>'false',
					'borderColor'=>$color[$n],
					'backgroundColor'=>$color[$n],
					'data'=>$df,
				];
				$n++;
			}
		}
		$data=[
			'labels'=>$data_label,
			'datasets'=>((isset($data_prop))?$data_prop:[]),
		];
		echo json_encode($data);
	}
	//======================================= TURN OVER KARYAWAN ==========================================//
	public function getTurnOverKaryawan()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_in') {
				parse_str($this->input->post('form'), $form);
				$param = $this->input->post('param');
				if($param == 'search'){
					$bulan = (!empty($form['bulan']) ? ['MONTH(emp.tgl_masuk)'=>$form['bulan']] : []);
					$tahun = (!empty($form['tahun']) ? ['YEAR(emp.tgl_masuk)'=>$form['tahun']] : []);
				}else{
					$bulan = (!empty($form['bulan']) ? ['MONTH(emp.tgl_masuk)'=>$form['bulan']] : ['MONTH(emp.tgl_masuk)'=>date('m')]);
					$tahun = (!empty($form['tahun']) ? ['YEAR(emp.tgl_masuk)'=>$form['tahun']] : ['YEAR(emp.tgl_masuk)'=>date('Y')]);
				}
				$where = array_merge($bulan, $tahun);
				$listEmpIn = $this->model_karyawan->getEmployeeWhere($where, false, 'emp.tgl_masuk ASC');
				$datax['data']=[];
				$no=1;
				foreach ($listEmpIn as $d) {
					$datax['data'][]=[
						$no,
						$d->nama,
						$d->nama_bagian,
						$d->nama_loker,
						$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_out') {
				parse_str($this->input->post('form'), $form);
				$param = $this->input->post('param');
				if($param == 'search'){
					$bulan = (!empty($form['bulan']) ? ['MONTH(a.tgl_keluar)'=>$form['bulan']] : []);
					$tahun = (!empty($form['tahun']) ? ['YEAR(a.tgl_keluar)'=>$form['tahun']] : []);
				}else{
					$bulan = (!empty($form['bulan']) ? ['MONTH(a.tgl_keluar)'=>$form['bulan']] : ['MONTH(a.tgl_keluar)'=>date('m')]);
					$tahun = (!empty($form['tahun']) ? ['YEAR(a.tgl_keluar)'=>$form['tahun']] : ['YEAR(a.tgl_keluar)'=>date('Y')]);
				}
				$where = array_merge($bulan, $tahun);
				$listEmpIn = $this->model_karyawan->getKaryawanNonAktifWhere2($where, 'a.tgl_keluar ASC');
				$datax['data']=[];
				$no=1;
				foreach ($listEmpIn as $d) {
					$datax['data'][]=[
						$no,
						$d->nama_karyawan,
						$d->nama_bagian,
						$d->nama_loker,
						$this->formatter->getDateMonthFormatUser($d->tgl_keluar),
						$d->keterangan,
					];
					$no++;
				}
				echo json_encode($datax);
			}
		}
	}
	public function cetak_turnover()
	{
		parse_str($this->input->post('data_filter'), $form);
		$bulanIn = (!empty($form['bulan']) ? ['MONTH(emp.tgl_masuk)'=>$form['bulan']] : []);
		$tahunIn = (!empty($form['tahun']) ? ['YEAR(emp.tgl_masuk)'=>$form['tahun']] : []);
		$whereIn = array_merge($bulanIn, $tahunIn);
		$listEmpIn = $this->model_karyawan->getEmployeeWhere($whereIn, false, 'emp.tgl_masuk ASC');
		$bulanOut = (!empty($form['bulan']) ? ['MONTH(a.tgl_keluar)'=>$form['bulan']] : []);
		$tahunOut = (!empty($form['tahun']) ? ['YEAR(a.tgl_keluar)'=>$form['tahun']] : []);
		$whereOut = array_merge($bulanOut, $tahunOut);
		$listEmpOut = $this->model_karyawan->getKaryawanNonAktifWhere2($whereOut, 'a.tgl_keluar ASC');
		$data =[
			'dataIn'=>$listEmpIn,
			'dataOut'=>$listEmpOut,
			'bulan'=>(!empty($form['bulan']) ? $form['bulan'] : null),
			'tahun'=>(!empty($form['tahun']) ? $form['tahun'] : null),
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/cetak_turnover',$data);
		$this->load->view('print_page/footer');
	}
}