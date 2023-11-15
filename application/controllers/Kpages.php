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
 
class Kpages extends CI_Controller
{
	public function __construct() 
	{ 
		parent::__construct();
		if(!empty($_COOKIE['nik'])){
			setcookie('pages', 'emp', strtotime('+1 year'), '/');
		}else{
			setcookie('pages', '', 0, '/');
		}
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
		if ($this->session->has_userdata('adm')) {
			$this->session->unset_userdata('adm');
		}
		if ($this->session->has_userdata('emp')) {
			$this->admin = $this->session->userdata('emp')['id'];
		}else{ 
			if(!empty($_COOKIE['pages']) == 'emp'){
				$dataEmp=$this->db->get_where('karyawan',['nik'=>$_COOKIE['nik']])->row_array();
				$this->session->set_userdata('emp', ['id'=>$dataEmp['id_karyawan']]);
				$this->admin = $this->session->userdata('emp')['id'];
			}else{
				redirect('auth');
			}
		}
		$ha = '0123456789'; 
	    $panjang = strlen($ha);
	    $rand = '';
	    for ($i = 0; $i < 6; $i++) {
	        $rand .= $ha[rand(0, $panjang - 1)]; 
	    }
	    $this->rando = $rand;		
		$dtroot['admin']=$this->model_karyawan->getEmployeeId($this->admin);
		$notii=$this->model_master->notif_emp();
		$mmmx=array();
		foreach ($notii as $notti) { 
			$id_admm=explode(';', $notti->id_for);
			$id_admm_r=explode(';', $notti->id_read);
			$id_admm_d=explode(';', $notti->id_del);
			if (in_array($this->admin,$id_admm) && !in_array($this->admin, $id_admm_r) && !in_array($this->admin, $id_admm_d)) {
				$saax=array('kode'=>$notti->kode_notif,'judul'=>$notti->judul,'tipe'=>$notti->tipe,'sifat'=>$notti->sifat);
				array_push($mmmx, $saax);
			}
		}
		if (isset($mmmx)) {
			$saa1=$mmmx;
		}else{
			$saa1=NULL;
		}
		$nm=explode(" ", $dtroot['admin']['nama']);
		if (isset($nm[1])) {
			$nmmx=$nm[0].' '.$nm[1];
		}else{
			$nmmx=$nm[0];
		}
		$this->link=$this->otherfunctions->getYourMenuUser($this->admin);
		$datax['adm'] = array(
				'nama'=>str_replace(',', '', $nmmx),
				'nama1'=>$dtroot['admin']['nama'],
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
				'notif'=>$saa1,
				'kode_bagian'=>$dtroot['admin']['kode_bagian'],
				'id_group_user'=>$dtroot['admin']['id_group_user'],
				'skin'=>$dtroot['admin']['skin'],
				'menu'=>$this->model_master->getListMenuUserActive(),
				'your_menu'=>$this->otherfunctions->getYourMenuUserId($this->admin),
				'your_url'=>$this->otherfunctions->getYourMenuUser($this->admin),
			);
		$this->dtroot=$datax;
		$l_acc=$this->otherfunctions->getYourAccessUser($this->admin);
		$l_ac=$this->otherfunctions->getAllAccessUser();
        if($dtroot['admin']['id_group_user'] != null || $dtroot['admin']['id_group_user'] != ''){
			if (isset($l_ac['stt'])) {
				if (in_array($l_ac['stt'], $l_acc)) {
					$attr='type="submit"';
				}else{
					$attr='type="button" data-toggle="tooltip" title="Tidak Diizinkan"';
				}
				if (!in_array($l_ac['edt'], $l_acc) && !in_array($l_ac['del'], $l_acc)) {
					$not_allow='<label class="label label-danger">Tidak Diizinkan</label>';
				}else{
					$not_allow=NULL;
				}
			}else{
				$not_allow=null;
				$attr=null;
			}
			$this->access=['access'=>$l_acc,'l_ac'=>$l_ac,'b_stt'=>$attr,'n_all'=>$not_allow];
		}
	}
	function coba()
	{
		$id=$this->admin;
		$kode = 'ASKP202101210001';
		$getAgenda=$this->model_agenda->getAgendaSikapKode($kode);
		$tabel=$getAgenda['nama_tabel'];
		$data=$this->model_agenda->openTableAgenda($tabel);
		$datax['data']=[];
		$pack=[];
		foreach ($data as $d) {
			$pack['partisipan'][$d->id_karyawan]=$this->exam->getPartisipantKode($d->partisipan);
			$pack['nilai_atas'][$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_atas;
			$pack['nilai_bawah'][$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_bawah;
			$pack['nilai_rekan'][$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_rekan;
			$pack['nilai_diri'][$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_diri;
			$pack['kuisioner'][$d->id_karyawan][$d->kode_kuisioner]=$d->kode_kuisioner;
			$pack['aspek'][$d->id_karyawan][$d->kode_aspek]=$d->kode_aspek;
		}
		echo '<pre>';
		// print_r($tabel);
		// echo '<br>';		
		// print_r($id);		
		print_r($pack['partisipan']);		
		if (isset($data) && isset($pack)) {
			$data_pick=[];
			if (isset($pack['partisipan'])) {
				foreach ($pack['partisipan'] as $k_par => $v_par) {
					if (isset($v_par[$id])) {
						$nilai[$k_par]=[];
						$count[$k_par] = count($pack['kuisioner'][$k_par]);
						$data_pick['karyawan'][$k_par]=$k_par;
						$data_pick['sebagai'][$k_par]=$this->exam->getWhatIsPartisipan($v_par[$id]);
						$data_pick['c_sebagai'][$k_par]=$v_par[$id];
						foreach ($pack['kuisioner'][$k_par] as $kuis) {
							$pack_ps = [$pack['nilai_atas'][$k_par][$kuis],$pack['nilai_bawah'][$k_par][$kuis],$pack['nilai_rekan'][$k_par][$kuis],$pack['nilai_diri'][$k_par][$kuis]];
							for ($i=0; $i < $max_for; $i++) { 
								if($v_par[$id]==$posisi[$i]){
									$n_p=$this->exam->getNilaiSikapWithId($pack_ps[$i],$id);
									if ($n_p != '') {
										array_push($nilai[$k_par],$n_p);
									}		
								}
							}
						}						
					}
				}
			}	
		}		

	}
	function cobaproseskpi(){	
		echo '<pre>';	
		$id_log = $this->admin;
		$data=$this->model_agenda->getAgendaActive('agenda_kpi');
		// print_r($data);
		// $access=$this->codegenerator->decryptChar($this->input->post('access'));
		$kary = $this->model_karyawan->getEmployeeId($id_log);
		$no=1;
		$tgl = $this->date;
		$datax['data']=[];
		foreach ($data as $d) {
			$var=[
				'id'=>$d->id_a_kpi,
				'create'=>$d->create_date,
				'update'=>$d->update_date,
				// 'access'=>$access,
				'status'=>$d->status,
			];
			$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
			$getAgenda=$this->model_agenda->getAgendaKpiKode($d->kode_a_kpi);
			$cekna = $this->otherfunctions->getDatePeriode($getAgenda['start'],$getAgenda['end'],$getAgenda['tahun']);
			$count_p=number_format($this->exam->getValueProgressAgendaFo($d->nama_tabel,$cekna,$kary['jabatan'],$id_log),2);
			// print_r($getAgenda);
			// print_r($cekna);
			// print_r($count_p);
		}
	}
	public function index(){
		redirect('kpages/dashboard');
	} 
	function logout(){
		session_destroy();
		$lgt=array('status'=>'offline');
		$this->db->where('id_karyawan',$this->admin);
		$this->db->update('karyawan',$lgt);
		redirect('auth');
	}
	function not_found(){
		$this->load->view('user_tem/header');
		$this->load->view('not_found');
		$this->load->view('user_tem/footer');
	}
	//Notif
	public function read_notification(){
		$kode=$this->uri->segment(3);
		$cek=$this->model_master->k_notif($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('kpages/read_all_notification');
		}else{
			$id=explode(';', $cek['id_read']);
			$id_d=explode(';', $cek['id_del']);
			if (!in_array($this->admin, $id_d)) {
				if (!in_array($this->admin, $id)) {
					array_push($id, $this->admin);
				}
				if (isset($id)) {
					$idd=implode(';', array_unique(array_filter($id)));
				}else{
					$idd=NULL;
				}
				$da=array('id_read'=>$idd);
				$this->db->where('kode_notif',$kode);
				$this->db->update('notification',$da);
				$cek1=$this->model_master->k_notif($kode);
				$data=array('notif'=>$cek1,);
				$this->load->view('user_tem/headerx',$this->dtroot);
				$this->load->view('user_tem/sidebarx',$this->dtroot);
				$this->load->view('user_pages/read_notif',$data);
				$this->load->view('user_tem/footerx');
			}else{
				$this->messages->notValidParam();  
				redirect('kpages/read_all_notification');
			}
		}
	}
	public function read_all_notification(){
		$cek=$this->model_master->notif_emp();
		$cc=array();
		foreach ($cek as $c) {
			$ccx=explode(';', $c->id_for);
			$ccx_r=explode(';', $c->id_read);
			$ccx_d=explode(';', $c->id_del);
			if (in_array($this->admin,$ccx) && !in_array($this->admin, $ccx_d)) {
				$saax=array('kode'=>$c->kode_notif,'start'=>$c->start,'judul'=>$c->judul,'tipe'=>$c->tipe,'sifat'=>$c->sifat,'id_read'=>$c->id_read,'id_del'=>$c->id_del);
				array_push($cc, $saax);
			}
		}
		if (isset($cc)) {
			$saa1=$cc;
		}else{
			$saa1=NULL;
		}
		$data=array('notif'=>$saa1,'adm'=>$this->admin);
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/read_all_notif',$data);
		$this->load->view('user_tem/footerx');
	}
	//main user_pages
	public function dashboard(){
		$qt=$this->db->get('quote')->result();
		$idq=rand(1,count($qt));
		$qt1=$this->db->get_where('quote',array('id'=>$idq))->row_array();
		$tgl_update = $this->model_master->up_date_actv();
		$id_karyawan=explode(';', $tgl_update['id_for']);
		if (in_array($this->admin, $id_karyawan)) {
			$update = true;
		}else{
			$update = false;
		}
		$nik=$this->dtroot['adm']['nik'];
		$jabatan=$this->model_karyawan->getEmployeeNik($nik)['jabatan'];
		$bawahan=$this->model_master->getJabatanBawahan($jabatan);
		$xx = [];
		foreach ($bawahan as $key) {
			array_push($xx, $key->kode_jabatan);
		}
		$dataz=$this->model_karyawan->getListIzinCutiBawahan($xx,'count');
		$count_i=[];
		if(!empty($dataz)){
			foreach ($dataz as $izin) {
				$count_i[]=count($izin);
			}
		}
		$data=['jml_emp'=>$this->model_karyawan->count_emp(),
				'up_date'=>$tgl_update,
				'tgl'=>$this->date,
				'nama'=>$this->dtroot['adm']['nama1'],
				'quote'=>$qt1,
				'update'=>$update,
				'data_izin'=>array_sum($count_i),
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/index',$data);
		$this->load->view('user_tem/footerx');
	}
	public function log_karyawan(){
		$pro=$this->model_karyawan->getEmployeeId($this->admin);
		$data=[	'profile'=>$pro,
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/log_karyawan',$data);
		$this->load->view('user_tem/footerx');
	}
	public function exit_interview(){
		$pro=$this->model_karyawan->getEmployeeId($this->admin);
		$data=[	'profile'=>$pro,
				'radio'=>$this->otherfunctions->getRadioList(),
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/exit_interview',$data);
		$this->load->view('user_tem/footerx');
	}
	public function data_izin_cuti(){
		$pro=$this->model_karyawan->getEmployeeId($this->admin);
		$data=[	'profile'=>$pro,
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/data_izin_cuti',$data);
		$this->load->view('user_tem/footerx');
	}
	public function data_validasi_izin(){
		$pro=$this->model_karyawan->getEmployeeId($this->admin);
		$data=[	'profile'=>$pro,
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/data_validasi_izin',$data);
		$this->load->view('user_tem/footerx');
	}
	public function cetak_izin()
	{
		$id_izin = $this->input->post('id_izin');
		$data=$this->model_karyawan->getIzinCuti($id_izin,null,'row');
		$data =[
			'datax'=>$data,
			'now'=>$this->date,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/rekap_izin',$data);
		$this->load->view('print_page/footer');
	}
	public function data_perjalanan_dinas(){
		$pro=$this->model_karyawan->getEmployeeId($this->admin);
		$data=[	'profile'=>$pro,
				'access'=>$this->access,
				'kendaraan_umum'=>$this->otherfunctions->getListKendaraanUmum(),
				'penginapan'=>$this->otherfunctions->getListPenginapan(),
				'tujuan'=>$this->otherfunctions->getTujuanPDList(),
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/data_perjalanan_dinas',$data);
		$this->load->view('user_tem/footerx');
	}
	public function validasi_perjalanan_dinas(){
		$pro=$this->model_karyawan->getEmployeeId($this->admin);
		$data=[	'profile'=>$pro,
				'access'=>$this->access,
				'kendaraan_umum'=>$this->otherfunctions->getListKendaraanUmum(),
				'penginapan'=>$this->otherfunctions->getListPenginapan(),
				'tujuan'=>$this->otherfunctions->getTujuanPDList(),
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/validasi_perjalanan_dinas',$data);
		$this->load->view('user_tem/footerx');
	}
	public function data_slip_gaji_harian(){
		$nama_menu="data_slip_gaji_harian";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin,
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/data_slip_gaji_harian',$data);
			$this->load->view('user_tem/footerx');	
		}else{
			redirect('not_found');
		}
	}
	public function slip_gaji_harian()
	{
		$id_admin = $this->admin;
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);	
		if(!empty($data_filter['minggu'])){ $form_filter['a.minggu'] = $data_filter['minggu']; }
		if(!empty($data_filter['bulan'])){ $form_filter['a.bulan'] = $data_filter['bulan']; }
		if(!empty($data_filter['tahun'])){ $form_filter['a.tahun'] = $data_filter['tahun']; }
		$form_filter['a.id_karyawan']=$id_admin;
		// echo '<pre>';
		// print_r($form_filter);	
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc');
			$data_gaji_r = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc', true);
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayrollHarian($form_filter, null, null, 'a.tgl_masuk asc');
		}
		$minggu_view=$this->otherfunctions->getlistWeek($data_gaji_r['minggu']);
		$bulan_view=$this->formatter->getNameOfMonth($data_gaji_r['bulan']);
		$tahun=$data_gaji_r['tahun'];
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$minggu_view.' ('.$bulan_view.' '.$tahun.')',
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/slip_gaji_harian',$data);
		$this->load->view('print_page/footer');
	}
	public function data_slip_gaji_bulanan(){
		$nama_menu="data_slip_gaji_bulanan";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin,
				'indukTunjanganTetap'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>1],'a.sifat','DESC'),
				'indukTunjanganNon'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>0],'a.sifat','DESC'),
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/data_slip_gaji_bulanan',$data);
			$this->load->view('user_tem/footerx');	
		}else{
			redirect('not_found');
		}
	}
	public function slip_gaji_10_3_23()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);

		$form_filter['a.id_karyawan'] = $this->admin;
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ 
			$form_filter['a.kode_periode'] = $data_filter['periode']; 
		}else{
			$form_filter['a.kode_periode'] = null; 
		}
		if($data_filter['usage'] == 'data'){
			if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			$data_gaji = $this->model_payroll->getDataPayroll($form_filter,1,null,true);
		}else{
			if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			$data_gaji = $this->model_payroll->getDataLogPayroll($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getPeriodePenggajian(['a.kode_periode_penggajian'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/slip_gaji',$data);
		$this->load->view('print_page/footer');
	}
	public function slip_gaji()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$id_karyawan = $this->admin;
		// $form_filter['a.kode_master_penggajian'] = 'BULANAN';



		if(!empty($data_filter['bulan'])){ 
			$bulan= $data_filter['bulan']; 
		}else{
			$bulan= null; 
		}
		if(!empty($data_filter['tahun'])){ 
			$tahun = $data_filter['tahun']; 
		}else{
			$tahun = null; 
		}
		if(!empty($data_filter['bulan'])){ 
			$bulan= $data_filter['bulan']; 
		}else{
			$bulan= null; 
		}
		$where = ['a.bulan'=>$bulan, 'a.tahun'=>$tahun, 'a.id_karyawan'=>$id_karyawan];
		$data_gaji = $this->model_payroll->getDataPayrollUser($where);
		// echo '<pre>';
		// print_r($data_gaji);
		$tgl_mulai =null;
		$tgl_selesai =null;
		if(!empty($data_gaji)){ 
			foreach ($data_gaji as $d) {
				$tgl_mulai = $d->tgl_mulai;
				$tgl_selesai = $d->tgl_selesai;
			}
		}
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>['tgl_mulai'=>$tgl_mulai, 'tgl_selesai'=>$tgl_selesai],
		];
		// echo 'pre>';
		$this->load->view('print_page/header');
		$this->load->view('print_page/slip_gaji',$data);
		$this->load->view('print_page/footer');
	}
	public function data_slip_gaji_lembur(){
		$nama_menu="data_slip_gaji_lembur";
		if (in_array($nama_menu, $this->link)) {
			$data=[
				'access'=>$this->access,
				'id_admin'=>$this->admin,
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/data_slip_gaji_lembur',$data);
			$this->load->view('user_tem/footerx');	
		}else{
			redirect('not_found');
		}
	}
	public function slip_lembur()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$form_filter['a.id_karyawan'] = $this->admin;
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		$form_filter['a.gaji_terima !='] = 0;
		if($data_filter['usage'] == 'data'){
			if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			// $data_gaji = $this->model_payroll->getDataPayrollLembur($form_filter,0,null,['kolom'=>'a.nama_karyawan','value'=>'ASC']);
			$data_gaji = $this->model_payroll->getDataPayrollLemburExcel($form_filter,['kolom'=>'a.nama_karyawan','value'=>'ASC']);
		}else{
			if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			$data_gaji = $this->model_payroll->getDataLogPayrollLembur($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/slip_lembur',$data);
		$this->load->view('print_page/footer');
		
	}
	public function slip_lembur_user()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$form_filter['a.id_karyawan'] = $this->admin;
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		$form_filter['a.gaji_terima !='] = 0;
		if($data_filter['usage'] == 'data'){
			if(!empty($data_filter['periode'])){ 
				$form_filter['a.kode_periode'] = $data_filter['periode']; 
			}
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			$data_gaji = $this->model_payroll->getDataLogPayrollLembur($form_filter,1,null);
		}else{
			if(!empty($data_filter['periode'])){ 
				$form_filter['a.kode_periode'] = $data_filter['periode']; 
			}
			if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
			$data_gaji = $this->model_payroll->getDataLogPayrollLembur($form_filter);
		}
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$form_filter['a.kode_periode']]));
		$data =[
			'emp_gaji'=>$data_gaji,
			'periode'=>$periode,
		];
		$this->load->view('print_page/header');
		$this->load->view('print_page/slip_lembur',$data);
		$this->load->view('print_page/footer');
		
	}
	public function profile(){
		$pro=$this->model_karyawan->getEmployeeId($this->admin);
		$jum_log = $this->db->get_where('log_login_karyawan',array('id_karyawan'=>$this->admin))->num_rows();
		$tgl_update = $this->model_master->up_date_actv();
		$id_karyawan=explode(';', $tgl_update['id_for']);
		if (in_array($this->admin, $id_karyawan)) {
			$update = true;
		}else{
			$update = false;
		}
		$data=[	'profile'=>$pro,
				'tgl'=>$this->date,
				'up_date'=>$tgl_update,
				'darah'	=> $this->otherfunctions->getBloodList(),
				'kelamin'=> $this->otherfunctions->getGenderList(),
				'nikah'=> $this->otherfunctions->getStatusNikahList(),
				'agama'=> $this->otherfunctions->getReligionList(),
				'm_pendidikan'=> $this->otherfunctions->getEducateList(),
				'pendidikan'=> $this->otherfunctions->getEducateList(),
				'm_bahasa'=> $this->otherfunctions->getBahasaList(),
				'baju'=>$this->otherfunctions->getUkuranBajuList(),
				'status_pajak'=>$this->otherfunctions->getStatusPajakList(),
				'log'=>$this->model_karyawan->log_kar($this->admin),
				'jumlah_log'=> $jum_log,
				'update'=>$update,
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/profile',$data);
		$this->load->view('user_tem/footerx');
	}
	public function visi_misi(){
		$data=[
				'visi_misi'=>$this->model_master->getDataCompany(),
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/data_visi_misi',$data);
		$this->load->view('user_tem/footerx');
	}
	public function berita(){
		$berita=$this->model_master->getListBeritaFO();
		$tgl_berita=$this->model_master->getListTanggalBeritaFO();
		$this->load->library('pagination');
		$config	= ['base_url'=>base_url('kpages/berita/'),
					'total_rows'=> count($this->model_master->getListBeritaFO()),
					'per_page'=>6,
					'uri_segment'=>3,
				];
		$limit = $config['per_page'];
		$uri = $this->uri->segment(3);
		$start = (!empty($uri)) ? $uri : 0;
		$this->pagination->initialize($config); 
		$berita_pg = $this->model_master->dataBeritaPagination($limit, $start);
		$data=[
				'berita'=>$berita_pg,
				'tgl_berita'=>$tgl_berita,
				'paginasi' 	=> $this->pagination->create_links(),
				'limit'		=> $limit,
				'total'		=> $config['total_rows'],
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/data_berita',$data);
		$this->load->view('user_tem/footerx');
	}
	public function read_berita(){
		$id=$this->codegenerator->decryptChar($this->uri->segment(3));
		$count=$this->model_master->getListBeritaFOID($id)['counting_reader']+1;
		$this->model_global->updateQuery(['counting_reader'=>$count],'data_berita',['id_berita'=>$id]);
		$berita=$this->model_master->getListBeritaFOID($id);
		$tgl_berita=$this->model_master->getListTanggalBeritaFO();
		$data=[
				'berita'=>$berita,
				'tgl_berita'=>$tgl_berita,
				'beritaAktif'=>$this->model_master->beritaAktif(),
				'nama_berita'=>$this->model_master->getListBeritaFOID($id)['judul'],
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/read_berita',$data);
		$this->load->view('user_tem/footerx');
	}
	public function struktur(){
		$data=[
			'struktur'=>$this->model_master->getListStrukturFO(),
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/data_struktur',$data);
		$this->load->view('user_tem/footerx');
	}
	public function data_pesan(){
		$pro=$this->model_karyawan->getEmployeeId($this->admin);
		$data=[	'profile'=>$pro,
			];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/data_pesan',$data);
		$this->load->view('user_tem/footerx');
	}
	//==corporate==//
	public function target_corporate(){
		$data=array('target'=>$this->model_master->list_target_c(),);
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/target_corporate',$data);
		$this->load->view('user_tem/footerx');
	}
	public function view_target_corporate(){
		$kode=$this->uri->segment(3);
		$cek=$this->model_master->cek_target($kode); 
		if ($cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/target_corporate');
		}else{
			$data=array(
				'view'=>$this->model_master->tb_target($cek['nama_tabel']),
				'nama'=>$cek['nama_target'],
				'smt'=>$cek['semester'],
				'th'=>$cek['tahun'],
				'tabel'=>$cek['nama_tabel'],
				'kode'=>$kode,
				'edit'=>$cek['edit'],
			);
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/view_target_corporate',$data);
			$this->load->view('user_tem/footerx');
		}
		
	}
	//========================================================= PENILAIAN KINERJA =========================================================//
	//============================================================== INPUT KPI =============================================================//
	public function tasks(){
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/tasks');
		$this->load->view('user_tem/footerx');
	}
	public function input_employee_tasks(){
		$dat = date('m');
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$cek=$this->model_agenda->getAgendaKpiKode($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/tasks');
		}else{
			$data=['agd'=>$cek];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/input_employee_tasks',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	public function input_tasks_value(){
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$cek=$this->model_agenda->getAgendaKpiKode($kode);
		$id_kar=$this->codegenerator->decryptChar($this->uri->segment(4));
		if ($kode == "" || $cek == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/input_tasks_value');
		}else{
			$kary = $this->model_karyawan->getEmployeeId($id_kar);
			$kary2 = $this->model_karyawan->getEmployeeId($this->admin);
			$jbt = $this->db->get_where('master_jabatan',array('kode_jabatan'=>$kary2['jabatan']))->row_array();
			$data=[
				'nama'=>$kary['nama'],
				'tahun'=>$cek['tahun'],
				'start'=>$cek['start'],
				'end'=>$cek['end'],
				'kode'=>$kode,
				'id_kar'=>$kary['id_karyawan'],
				'tabel'=>$cek['nama_tabel'],
				'periode'=>$jbt['kode_periode'],
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/input_tasks_value',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	//============================================================= HASIL KPI =============================================================//
	public function result_tasks(){
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/result_tasks');
		$this->load->view('user_tem/footerx');
	}
	public function result_employee_tasks(){
		$dat = date('m');
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$cek=$this->model_agenda->getAgendaKpiKode($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/tasks');
		}else{
			$data=['agd'=>$cek];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/result_employee_tasks',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	public function report_value_tasks(){
		$cekx=$this->model_agenda->getAgendaKpiKode($this->codegenerator->decryptChar($this->uri->segment(3)));
		$nmtb=$cekx['nama_tabel'];
		$id=$this->codegenerator->decryptChar($this->uri->segment(4));
		$cek=$this->model_agenda->openTableAgendaIdEmployee($nmtb,$id);
		if ($cek == "" || $nmtb == "" || $id == "" || count($cek) == 0) {
			$this->messages->sessNotValidParam();  
			redirect('kpages/result_tasks');
		}else{
			$kar=['nik'=>null,'nama'=>null,'foto'=>null,'kelamin'=>null,'nama_jabatan'=>null,'nama_loker'=>null,'bagian'=>null, 'nama_jabatan_baru'=>null,'nama_loker_baru'=>null,'bagian_baru'=>null,'tgl_masuk'=>null,'email'=>null,'kode_periode'=>null];
			if (count($cek) == 0) {
				$this->messages->sessNotValidParam();  
				redirect('kpages/result_tasks');
			}else{
				foreach($cek as $c){
					$kar['nik']=$c->nik;
					$kar['nama']=$c->nama;
					$kar['foto']=$c->foto;
					$kar['kelamin']=$c->kelamin;
					$kar['nama_jabatan']=$c->nama_jabatan;
					$kar['nama_loker']=$c->nama_loker;
					$kar['bagian']=$c->bagian;
					$kar['nama_jabatan_baru']=$c->nama_jabatan_baru;
					$kar['nama_loker_baru']=$c->nama_loker_baru;
					$kar['bagian_baru']=$c->bagian_baru;
					$kar['tgl_masuk']=$c->tgl_masuk;
					$kar['email']=$c->email;
					$kar['kode_periode']=$c->kode_periode;
				}
			}
			$data=array(
				'profile'=>$kar,
				'nmtb'=>$nmtb,
				'hasil'=>$cek,
				'agd'=>$cekx,
				'idk'=>$id,
				'id_log'=>$this->admin,
			);
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/report_value_tasks',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	//========================================================= INPUT ASPEK SIKAP ==========================================================//
	public function attitude_tasks(){
		$data=array('attd'=>$this->model_agenda->getAgendaActive('agenda_sikap'),'tgl'=>$this->date,'id_adm'=>$this->admin);
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/attitude_tasks',$data);
		$this->load->view('user_tem/footerx');
	}
	public function input_attitude_tasks_value(){
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$cek=$this->model_agenda->getAgendaSikapKode($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/tasks');
		}else{
			$dt = [
				'nama_agenda'=>$cek['nama'],
				'tahun'=>$cek['tahun'],
				'periode'=>$cek['nama_periode'],
			];
			$data=[
				'kode'=>$cek['kode_a_sikap'],
				'agd'=>$dt,
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/input_attitude_tasks_value',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	public function input_attitude_value(){
		$kode_sikap=$this->codegenerator->decryptChar($this->uri->segment(3));
		$kode_sikap_en=$this->uri->segment(3);
		$id_kar=$this->uri->segment(4);
		$kode_aspek=$this->uri->segment(5);
		$cek=$this->model_agenda->getAgendaSikapKode($kode_sikap);
		if ($kode_sikap == "" || $cek == "" || $id_kar == "" || $kode_aspek == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/input_attitude_tasks_value/'.$this->uri->segment(3));
		}else{
			$sa=explode(':', $id_kar);
			$id=$sa[1];
			$dtxx = array(
				'nama_agenda'=>$cek['nama'],
				'tahun'=>$cek['tahun'],
				'periode'=>$cek['nama_periode'],
			);
			$kary = $this->model_karyawan->getEmployeeId($id);
			$table=$cek['nama_tabel'];
			$data=[
				'nama'=>$kary['nama'],
				'jabatan'=>$kary['nama_jabatan'],
				'loker'=>$kary['nama_loker'],
				'foto'=>$kary['foto'],
				'kelamin'=>$kary['kelamin'],
				'kode_sikap'=>$kode_sikap,
				'kode_sikap_en'=>$kode_sikap_en,
				'kode_aspek'=>$kode_aspek,
				'tabel'=>$cek['nama_tabel'],
				'agd'=>$dtxx,
				'id'=>$id,
				'tabel'=>$table,
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/input_attitude_value',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	//========================================================= HASIL ASPEK SIKAP ==========================================================//
	public function result_attd_tasks(){
		$data=array('attd'=>$this->model_agenda->getAgendaActive('agenda_sikap'),'tgl'=>$this->date,'id_adm'=>$this->admin);
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/result_attd_tasks',$data);
		$this->load->view('user_tem/footerx');
	}
	public function result_attd_tasks_value(){
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$cek=$this->model_agenda->getAgendaSikapKode($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/tasks');
		}else{
			$dt = array(
				'nama_agenda'=>$cek['nama'],
				'tahun'=>$cek['tahun'],
				'periode'=>$cek['nama_periode'],
			);
			$data=[
				'kode'=>$cek['kode_a_sikap'],
				'agd'=>$dt,
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/result_attd_tasks_value',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	public function report_attd_value(){
		$kode_sikap=$this->codegenerator->decryptChar($this->uri->segment(3));
		$kode_sikap_en=$this->uri->segment(3);
		$id_kar=$this->uri->segment(4);
		$kode_aspek=$this->uri->segment(5);
		$cek=$this->model_agenda->getAgendaSikapKode($kode_sikap);
		if ($kode_sikap == "" || $cek == "" || $id_kar == "" || $kode_aspek == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/result_attd_tasks_value/'.$this->uri->segment(3));
		}else{
			$sa=explode(':', $id_kar);
			$id=$sa[1];
			$dtxx = array(
				'nama_agenda'=>$cek['nama'],
				'tahun'=>$cek['tahun'],
				'periode'=>$cek['nama_periode'],
			);
			$kary = $this->model_karyawan->getEmployeeId($id);
			$table=$cek['nama_tabel'];
			$data=[
				'nama'=>$kary['nama'],
				'jabatan'=>$kary['nama_jabatan'],
				'loker'=>$kary['nama_loker'],
				'foto'=>$kary['foto'],
				'kode_sikap'=>$kode_sikap,
				'kode_sikap_en'=>$kode_sikap_en,
				'kode_aspek'=>$kode_aspek,
				'tabel'=>$cek['nama_tabel'],
				'agd'=>$dtxx,
				'id'=>$id,
				'tabel'=>$table,
				'profile'=>$kary,
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/report_attd_value',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	//=============================================================== RAPORT ==================================================================//
	//========================================================= RAPORT DIRI SENDIRI ===========================================================//
	//___________________________________________________________ RAPORT ASPEK KPI ____________________________________________________________//
	public function list_raport_output(){
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/list_raport_output');
		$this->load->view('user_tem/footerx');
	}
	public function view_raport_output(){
		$cekx=$this->model_agenda->getAgendaKpiKode($this->codegenerator->decryptChar($this->uri->segment(3)));
		$nmtb=$cekx['nama_tabel'];
		$id=$this->admin;
		$cek=$this->model_agenda->openTableAgendaIdEmployee($nmtb,$id);
		if ($cek == "" || $nmtb == "" || $id == "" || count($cek) == 0) {
			$this->messages->sessNotValidParam(); 
			redirect('kpages/result_tasks');
		}else{
			$kar=['nik'=>null,'nama'=>null,'foto'=>null,'kelamin'=>null,'nama_jabatan'=>null,'nama_loker'=>null,'bagian'=>null, 'nama_jabatan_baru'=>null,'nama_loker_baru'=>null,'bagian_baru'=>null,'tgl_masuk'=>null,'email'=>null,'kode_periode'=>null];
			if (count($cek) == 0) {
				$this->messages->sessNotValidParam(); 
				redirect('kpages/result_tasks/');
			}else{
				foreach($cek as $c){
					$kar['nik']=$c->nik;
					$kar['nama']=$c->nama;
					$kar['foto']=$c->foto;
					$kar['kelamin']=$c->kelamin;
					$kar['nama_jabatan']=$c->nama_jabatan;
					$kar['nama_loker']=$c->nama_loker;
					$kar['bagian']=$c->bagian;
					$kar['nama_jabatan_baru']=$c->nama_jabatan_baru;
					$kar['nama_loker_baru']=$c->nama_loker_baru;
					$kar['bagian_baru']=$c->bagian_baru;
					$kar['tgl_masuk']=$c->tgl_masuk;
					$kar['email']=$c->email;
					$kar['kode_periode']=$c->kode_periode;
				}
			}
			$data=array(
				'profile'=>$kar,
				'nmtb'=>$nmtb,
				'hasil'=>$cek,
				'agd'=>$cekx,
				'idk'=>$id,
				'id_log'=>$this->admin,
			);
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/view_raport_output',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	//__________________________________________________________ RAPORT ASPEK SIKAP ___________________________________________________________//
	public function list_raport_sikap(){
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/list_raport_sikap');
		$this->load->view('user_tem/footerx');
	}
	public function view_raport_sikap(){
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$cek=$this->model_agenda->getLogAgendaSikapKodeLink($this->codegenerator->decryptChar($this->uri->segment(3)));
		if (!isset($cek) || empty($kode)) {
			$this->messages->notValidParam();  
			redirect('kpages/result_tasks');
		}else{
			$kar=$this->model_karyawan->getEmployeeId($this->admin);
			$data=[
				'kode'=>$cek['kode_a_sikap'],
				'table'=>$cek['nama_tabel'],
				'periode'=>$cek['periode'],
				'profile'=>$kar,
			];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/view_raport_sikap',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	//____________________________________________________________ RAPORT AKHIR _____________________________________________________________//
	public function list_raport_group()
	{
		$data=['periode'=>$this->model_master->getListPeriodePenilaianActive()];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/list_raport_group',$data);
		$this->load->view('user_tem/footerx');
	}
	public function report_value_group()
	{
		$kodex = $this->codegenerator->decryptChar($this->uri->segment(3));
		$kodex=explode('-',$kodex);
		$period=['tahun'=>null,'kode_periode'=>null,'id'=>$this->admin];
		if (count($kodex) < 2) {
			$period['tahun']=(isset($kodex[0]))?$kodex[0]:null;
		}else{
			$period['kode_periode']=$kodex[0];
			$period['tahun']=$kodex[1];
		}
		$datax = $this->model_agenda->getReportGroupEmployee($period['kode_periode'],$period['tahun'],$this->admin);
		
		$kode = array_merge($datax,$period);
		$data=['kode'=>$kode,'profile'=>$this->model_karyawan->getEmployeeId($this->admin)];
		//print_r($period);
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/report_value_group',$data);
		$this->load->view('user_tem/footerx');
	}
	//=========================================================== RAPORT BAWAHAN =============================================================//
	//______________________________________________________ RAPORT ASPEK KPI BAWAHAN _______________________________________________________//
	public function raport_bawahan_output(){
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/raport_bawahan_output');
		$this->load->view('user_tem/footerx');
	}
	public function list_raport_bawahan_output()
	{
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$cek=$this->model_agenda->getLogAgendaKpiKodeLink($kode);
		if(isset($cek)){
			$data=['nama_agenda'=>$cek['nama'],'kode'=>$kode,'tabel'=>$cek['nama_tabel'],'agd'=>$cek];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/list_raport_bawahan_output',$data);
			$this->load->view('user_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function view_raport_bawahan_output(){
		$cekx=$this->model_agenda->getAgendaKpiKode($this->codegenerator->decryptChar($this->uri->segment(3)));
		$nmtb=$cekx['nama_tabel'];
		$id=$this->codegenerator->decryptChar($this->uri->segment(4));
		$cek=$this->model_agenda->openTableAgendaIdEmployee($nmtb,$id);
		if ($cek == "" || count($cek) == 0 || $nmtb == "" || $id == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/raport_bawahan_output');
		}else{
			$kar=['nik'=>null,'nama'=>null,'foto'=>null,'kelamin'=>null,'nama_jabatan'=>null,'nama_loker'=>null,'bagian'=>null, 'nama_jabatan_baru'=>null,'nama_loker_baru'=>null,'bagian_baru'=>null,'tgl_masuk'=>null,'email'=>null,'kode_periode'=>null];
			if (count($cek) == 0) {
				$this->messages->sessNotValidParam();  
				redirect('kpages/raport_bawahan_output');
			}else{
				foreach($cek as $c){
					$kar['nik']=$c->nik;
					$kar['nama']=$c->nama;
					$kar['foto']=$c->foto;
					$kar['kelamin']=$c->kelamin;
					$kar['nama_jabatan']=$c->nama_jabatan;
					$kar['nama_loker']=$c->nama_loker;
					$kar['bagian']=$c->bagian;
					$kar['nama_jabatan_baru']=$c->nama_jabatan_baru;
					$kar['nama_loker_baru']=$c->nama_loker_baru;
					$kar['bagian_baru']=$c->bagian_baru;
					$kar['tgl_masuk']=$c->tgl_masuk;
					$kar['email']=$c->email;
					$kar['kode_periode']=$c->kode_periode;
				}
			}
			$data=array(
				'profile'=>$kar,
				'nmtb'=>$nmtb,
				'hasil'=>$cek,
				'agd'=>$cekx,
				'idk'=>$id,
				'id_log'=>$this->admin,
			);
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/view_raport_bawahan_output',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	//____________________________________________________ RAPORT ASPEK SIKAP BAWAHAN _____________________________________________________//
	public function raport_bawahan_sikap(){
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/raport_bawahan_sikap');
		$this->load->view('user_tem/footerx');
	}
	public function list_raport_bawahan_sikap()
	{
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$cek=$this->model_agenda->getLogAgendaSikapKodeLink($kode);
		if(isset($cek)){
			$data=['nama_agenda'=>$cek['nama'],'tabel'=>$cek['nama_tabel']];
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/list_raport_bawahan_sikap',$data);
			$this->load->view('user_tem/footerx');
		}else{
			redirect('not_found');
		}
	}
	public function view_raport_bawahan_sikap(){
		$cekx=$this->model_agenda->getAgendaSikapKode($this->codegenerator->decryptChar($this->uri->segment(3)));
		if(!isset($cekx)){
			$this->messages->sessNotValidParam();  
			redirect('kpages/raport_bawahan_sikap');
		}
		$nmtb=$cekx['nama_tabel'];
		$id=$this->codegenerator->decryptChar($this->uri->segment(4));
		$cek=$this->model_agenda->openTableAgendaIdEmployee($nmtb,$id);
		if ($cek == "" || count($cek) == 0 || $nmtb == "" || $id == "") {
			$this->messages->sessNotValidParam();  
			redirect('kpages/raport_bawahan_sikap');
		}else{
			$kar=$this->model_karyawan->getEmployeeId($id);
			$data=array(
				'profile'=>$kar,
				'table'=>$nmtb,
				'hasil'=>$cek,
				'agd'=>$cekx,
				'kode'=>$this->codegenerator->decryptChar($this->uri->segment(3)),
				'idk'=>$id,
				'id_log'=>$this->admin,
			);
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/view_raport_bawahan_sikap',$data);
			$this->load->view('user_tem/footerx');
		}
	}
	//______________________________________________________ RAPORT AKHIR BAWAHAN _______________________________________________________//
	public function raport_bawahan()
	{
		$data=['periode'=>$this->model_master->getListPeriodePenilaianActive(),];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/raport_bawahan',$data);
		$this->load->view('user_tem/footerx');
	}
	public function view_raport_bawahan()
	{
		if (empty($this->uri->segment(3))) {
			redirect('kpages/not_found');
		}
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		if (empty($kode)){
			$this->messages->sessNotValidParam();  
			redirect('pages/data_hasil_group');
		}				
		if (!empty(strpos($kode, '-'))){
			$kode=explode('-',$kode);
			$kode=['kode_periode'=>$kode[0],'tahun'=>$kode[1],'link'=>$this->uri->segment(3)];
			$pages='view_raport_bawahan';
		}else{
			$kode=['tahun'=>$kode,'link'=>$this->uri->segment(3)];
			$pages='view_raport_bawahan_tahunan';
		}
		$data=['kode'=>$kode,'periode_list'=>$this->model_master->getListPeriodePenilaian(1),'nama_agenda'=>((isset($kode['kode_periode']))?$this->model_master->getListPeriodePenilaianActive()[$kode['kode_periode']].' '.$kode['tahun']:$kode['tahun'])];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/'.$pages,$data);
		$this->load->view('user_tem/footerx');
	}
	public function report_value_bawahan_group()
	{
		$kodex = $this->codegenerator->decryptChar($this->uri->segment(3));
		$id = $this->codegenerator->decryptChar($this->uri->segment(4));
		$kodex=explode('-',$kodex);
		$period=['tahun'=>null,'kode_periode'=>null,'id'=>$id];
		if (count($kodex) < 2) {
			$period['tahun']=(isset($kodex[0]))?$kodex[0]:null;
		}else{
			$period['kode_periode']=$kodex[0];
			$period['tahun']=$kodex[1];
		}
		$datax = $this->model_agenda->getReportGroupEmployee($period['kode_periode'],$period['tahun'],$id);
		
		$kode = array_merge($datax,$period);
		$data=['kode'=>$kode,'profile'=>$this->model_karyawan->getEmployeeId($id),'id_admin'=>$this->admin];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('user_pages/report_value_bawahan_group',$data);
		$this->load->view('user_tem/footerx');
	}

	
	// public function tasks(){
	// 	$agenda=$this->model_agenda->agenda_aktif();
	// 	foreach ($agenda as $ag) {
	// 		$nmtb[$ag->id_agenda]=$ag->tabel_agenda;
	// 		$dt1[$ag->id_agenda]=$this->model_agenda->task($nmtb[$ag->id_agenda]);
	// 		foreach ($dt1[$ag->id_agenda] as $d) {
	// 			$res[$ag->id_agenda][$d->id_karyawan]=$d->id_karyawan;
	// 			$jabatan[$ag->id_agenda][$d->id_karyawan][$d->jabatan]=$d->jabatan;
	// 			$loker[$ag->id_agenda][$d->id_karyawan][$d->loker]=$d->loker;
	// 			$ind[$ag->id_agenda][$d->id_karyawan][$d->kode_indikator]=$d->kode_indikator;
	// 			$id_jabatan[$ag->id_agenda][$d->id_karyawan][$d->id_jabatan]=$d->id_jabatan;
	// 			if ($d->id_sub != NULL) {
	// 				$id_sub[$ag->id_agenda][$d->id_karyawan][$d->id_sub]=$d->id_sub;
	// 			}
	// 		}
	// 		foreach ($res[$ag->id_agenda] as $k) {
	// 			$dat[$ag->id_agenda]=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
	// 			$kar[$ag->id_agenda]=$this->model_karyawan->emp($k);
	// 			$nilai[$ag->id_agenda][$k]=array();
	// 			$penilai[$ag->id_agenda][$k]=array();
	// 			foreach ($dat[$ag->id_agenda] as $d1) {
	// 				if ($d1->kode_penilai != 'P3') {
	// 					$dtp[$ag->id_agenda][$k]=array('kode_penilai'=>$d1->kode_penilai,'id_penilai'=>$d1->id_penilai);
	// 					$dtnil[$ag->id_agenda][$k]=array();
	// 					if ($d1->ln1 != NULL) {
	// 						$nil_1=array_filter(explode(',', $d1->ln1));
	// 						foreach ($nil_1 as $nnil1) {
	// 							$val_1=str_replace('{KAR', '', $nnil1);
	// 							$vval_1=str_replace('}', '', $val_1);
	// 							$vnil_1=explode(':', $vval_1);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_1[0]);
	// 						}
	// 					}
	// 					if ($d1->ln2 != NULL) {
	// 						$nil_2=array_filter(explode(',', $d1->ln2));
	// 						foreach ($nil_2 as $nnil2) {
	// 							$val_2=str_replace('{KAR', '', $nnil2);
	// 							$vval_2=str_replace('}', '', $val_2);
	// 							$vnil_2=explode(':', $vval_2);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_2[0]);
	// 						}
	// 					}
	// 					if ($d1->ln3 != NULL) {
	// 						$nil_3=array_filter(explode(',', $d1->ln3));
	// 						foreach ($nil_3 as $nnil3) {
	// 							$val_3=str_replace('{KAR', '', $nnil3);
	// 							$vval_3=str_replace('}', '', $val_3);
	// 							$vnil_3=explode(':', $vval_3);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_1[0]);
	// 						}
	// 					}
	// 					if ($d1->ln4 != NULL) {
	// 						$nil_4=array_filter(explode(',', $d1->ln4));
	// 						foreach ($nil_4 as $nnil4) {
	// 							$val_4=str_replace('{KAR', '', $nnil4);
	// 							$vval_4=str_replace('}', '', $val_4);
	// 							$vnil_4=explode(':', $vval_4);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_4[0]);
	// 						}
	// 					}
	// 					if ($d1->ln5 != NULL) {
	// 						$nil_5=array_filter(explode(',', $d1->ln5));
	// 						foreach ($nil_5 as $nnil5) {
	// 							$val_5=str_replace('{KAR', '', $nnil5);
	// 							$vval_5=str_replace('}', '', $val_5);
	// 							$vnil_5=explode(':', $vval_5);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_5[0]);
	// 						}
	// 					}
	// 					if ($d1->ln6!= NULL) {
	// 						$nil_6=array_filter(explode(',', $d1->ln6));
	// 						foreach ($nil_6 as $nnil6) {
	// 							$val_6=str_replace('{KAR', '', $nnil6);
	// 							$vval_6=str_replace('}', '', $val_6);
	// 							$vnil_6=explode(':', $vval_6);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_6[0]);
	// 						}
	// 					}
	// 					//nilai
	// 					array_push($nilai[$ag->id_agenda][$k], $dtnil[$ag->id_agenda][$k]);
	// 					//penilai
	// 					array_push($penilai[$ag->id_agenda][$k], $dtp[$ag->id_agenda][$k]);
	// 				}
	// 			}
	// 			if (isset($id_sub[$ag->id_agenda][$k])) {
	// 				$id_s[$ag->id_agenda][$k]=$id_sub[$ag->id_agenda][$k];
	// 				$idss[$ag->id_agenda][$k]=implode('', $id_s[$ag->id_agenda][$k]);
	// 				$sub[$ag->id_agenda][$k]=$this->db->get_where('master_sub_jabatan',array('id_sub'=>$idss[$ag->id_agenda][$k]))->row_array();
	// 				$ksub[$ag->id_agenda][$k]=$sub[$k]['atasan'];
	// 				$ksub1=$ksub[$ag->id_agenda][$k];
	// 				$ka[$ag->id_agenda]=$this->db->query("SELECT id_karyawan FROM karyawan WHERE kode_sub = '$ksub1'")->result();
	// 				$atasan[$ag->id_agenda][$k]=array();
	// 				foreach ($ka[$ag->id_agenda] as $aa) {
	// 					array_push($atasan[$ag->id_agenda][$k], $aa->id_karyawan);
	// 				}
	// 			}else{
	// 				$id_s[$ag->id_agenda][$k]=NULL;
	// 				$idjb[$ag->id_agenda][$k]=implode('',$id_jabatan[$ag->id_agenda][$k]);
	// 				$jb[$ag->id_agenda][$k]=$this->db->get_where('master_jabatan',array('id_jabatan'=>$idjb[$ag->id_agenda][$k]))->row_array();
	// 				$kjb[$ag->id_agenda][$k]=$jb[$ag->id_agenda][$k]['atasan'];
	// 				$kjb1=$kjb[$ag->id_agenda][$k];
	// 				$ka[$ag->id_agenda]=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$kjb1'")->result();
	// 				$atasan[$ag->id_agenda][$k]=array();
	// 				foreach ($ka[$ag->id_agenda] as $aa) {
	// 					array_push($atasan[$ag->id_agenda][$k], $aa->id_karyawan);
	// 				}
	// 			}
	// 			$datax[$ag->id_agenda][$k]=array(
	// 				'nilai'=>$nilai[$ag->id_agenda][$k],
	// 				'nik'=>$kar[$ag->id_agenda]['nik'],
	// 				'nama'=>$kar[$ag->id_agenda]['nama'],
	// 				'jabatan'=>implode('',$jabatan[$ag->id_agenda][$k]),
	// 				'loker'=>implode('',$loker[$ag->id_agenda][$k]),
	// 				'id_jabatan'=>implode('',$id_jabatan[$ag->id_agenda][$k]),
	// 				'id_sub'=>$id_s[$ag->id_agenda],
	// 				'penilai'=>$penilai[$ag->id_agenda][$k],
	// 				'ind'=>count($ind[$ag->id_agenda][$k]),
	// 				'atasan'=>$atasan[$ag->id_agenda][$k],
	// 			);
	// 		}
	// 	}
	// 	if (isset($datax)) {
	// 		$sa=$datax;
	// 	}else{
	// 		$sa=NULL;
	// 	}
	// 	$data=array(
	// 		'agd'=>$agenda,
	// 		'data'=>$sa,
	// 		'jabatan'=>$this->dtroot['adm']['jabatan'],
	// 		'tgl'=>$this->date,
	// 		'jabatan'=>$this->dtroot['adm']['jabatan'],
	// 		'me'=>$this->dtroot['adm']['id_karyawan']
	// 	);
	// 	$this->load->view('user_tem/headerx',$this->dtroot);
	// 	$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 	$this->load->view('user_pages/tasks',$data);
	// 	$this->load->view('user_tem/footerx');
	// }
	// public function result_tasks(){
	// 	$agenda=$this->model_agenda->agenda_aktif();
	// 	foreach ($agenda as $ag) {
	// 		$nmtb[$ag->id_agenda]=$ag->tabel_agenda;
	// 		$dt1[$ag->id_agenda]=$this->model_agenda->task($nmtb[$ag->id_agenda]);
	// 		foreach ($dt1[$ag->id_agenda] as $d) {
	// 			$res[$ag->id_agenda][$d->id_karyawan]=$d->id_karyawan;
	// 			$jabatan[$ag->id_agenda][$d->id_karyawan][$d->jabatan]=$d->jabatan;
	// 			$loker[$ag->id_agenda][$d->id_karyawan][$d->loker]=$d->loker;
	// 			$ind[$ag->id_agenda][$d->id_karyawan][$d->kode_indikator]=$d->kode_indikator;
	// 			$id_jabatan[$ag->id_agenda][$d->id_karyawan][$d->id_jabatan]=$d->id_jabatan;
	// 			if ($d->id_sub != NULL) {
	// 				$id_sub[$ag->id_agenda][$d->id_karyawan][$d->id_sub]=$d->id_sub;
	// 			}
	// 		}
	// 		foreach ($res[$ag->id_agenda] as $k) {
	// 			$dat[$ag->id_agenda]=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
	// 			$kar[$ag->id_agenda]=$this->model_karyawan->emp($k);
	// 			$nilai[$ag->id_agenda][$k]=array();
	// 			$penilai[$ag->id_agenda][$k]=array();
	// 			foreach ($dat[$ag->id_agenda] as $d1) {
	// 				if ($d1->kode_penilai != 'P3') {
	// 					$dtp[$ag->id_agenda][$k]=array('kode_penilai'=>$d1->kode_penilai,'id_penilai'=>$d1->id_penilai);
	// 					$dtnil[$ag->id_agenda][$k]=array();
	// 					if ($d1->ln1 != NULL) {
	// 						$nil_1=array_filter(explode(',', $d1->ln1));
	// 						foreach ($nil_1 as $nnil1) {
	// 							$val_1=str_replace('{KAR', '', $nnil1);
	// 							$vval_1=str_replace('}', '', $val_1);
	// 							$vnil_1=explode(':', $vval_1);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_1[0]);
	// 						}
	// 					}
	// 					if ($d1->ln2 != NULL) {
	// 						$nil_2=array_filter(explode(',', $d1->ln2));
	// 						foreach ($nil_2 as $nnil2) {
	// 							$val_2=str_replace('{KAR', '', $nnil2);
	// 							$vval_2=str_replace('}', '', $val_2);
	// 							$vnil_2=explode(':', $vval_2);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_2[0]);
	// 						}
	// 					}
	// 					if ($d1->ln3 != NULL) {
	// 						$nil_3=array_filter(explode(',', $d1->ln3));
	// 						foreach ($nil_3 as $nnil3) {
	// 							$val_3=str_replace('{KAR', '', $nnil3);
	// 							$vval_3=str_replace('}', '', $val_3);
	// 							$vnil_3=explode(':', $vval_3);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_1[0]);
	// 						}
	// 					}
	// 					if ($d1->ln4 != NULL) {
	// 						$nil_4=array_filter(explode(',', $d1->ln4));
	// 						foreach ($nil_4 as $nnil4) {
	// 							$val_4=str_replace('{KAR', '', $nnil4);
	// 							$vval_4=str_replace('}', '', $val_4);
	// 							$vnil_4=explode(':', $vval_4);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_4[0]);
	// 						}
	// 					}
	// 					if ($d1->ln5 != NULL) {
	// 						$nil_5=array_filter(explode(',', $d1->ln5));
	// 						foreach ($nil_5 as $nnil5) {
	// 							$val_5=str_replace('{KAR', '', $nnil5);
	// 							$vval_5=str_replace('}', '', $val_5);
	// 							$vnil_5=explode(':', $vval_5);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_5[0]);
	// 						}
	// 					}
	// 					if ($d1->ln6!= NULL) {
	// 						$nil_6=array_filter(explode(',', $d1->ln6));
	// 						foreach ($nil_6 as $nnil6) {
	// 							$val_6=str_replace('{KAR', '', $nnil6);
	// 							$vval_6=str_replace('}', '', $val_6);
	// 							$vnil_6=explode(':', $vval_6);
	// 							array_push($dtnil[$ag->id_agenda][$k], $vnil_6[0]);
	// 						}
	// 					}
	// 					//nilai
	// 					array_push($nilai[$ag->id_agenda][$k], $dtnil[$ag->id_agenda][$k]);
	// 					//penilai
	// 					array_push($penilai[$ag->id_agenda][$k], $dtp[$ag->id_agenda][$k]);
	// 				}
	// 			}
	// 			if (isset($id_sub[$ag->id_agenda][$k])) {
	// 				$id_s[$ag->id_agenda][$k]=$id_sub[$ag->id_agenda][$k];
	// 				$idss[$ag->id_agenda][$k]=implode('', $id_s[$ag->id_agenda][$k]);
	// 				$sub[$ag->id_agenda][$k]=$this->db->get_where('master_sub_jabatan',array('id_sub'=>$idss[$ag->id_agenda][$k]))->row_array();
	// 				$ksub[$ag->id_agenda][$k]=$sub[$k]['atasan'];
	// 				$ksub1=$ksub[$ag->id_agenda][$k];
	// 				$ka[$ag->id_agenda]=$this->db->query("SELECT id_karyawan FROM karyawan WHERE kode_sub = '$ksub1'")->result();
	// 				$atasan[$ag->id_agenda][$k]=array();
	// 				foreach ($ka[$ag->id_agenda] as $aa) {
	// 					array_push($atasan[$ag->id_agenda][$k], $aa->id_karyawan);
	// 				}
	// 			}else{
	// 				$id_s[$ag->id_agenda][$k]=NULL;
	// 				$idjb[$ag->id_agenda][$k]=implode('',$id_jabatan[$ag->id_agenda][$k]);
	// 				$jb[$ag->id_agenda][$k]=$this->db->get_where('master_jabatan',array('id_jabatan'=>$idjb[$ag->id_agenda][$k]))->row_array();
	// 				$kjb[$ag->id_agenda][$k]=$jb[$ag->id_agenda][$k]['atasan'];
	// 				$kjb1=$kjb[$ag->id_agenda][$k];
	// 				$ka[$ag->id_agenda]=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$kjb1'")->result();
	// 				$atasan[$ag->id_agenda][$k]=array();
	// 				foreach ($ka[$ag->id_agenda] as $aa) {
	// 					array_push($atasan[$ag->id_agenda][$k], $aa->id_karyawan);
	// 				}
	// 			}
	// 			$datax[$ag->id_agenda][$k]=array(
	// 				'nilai'=>$nilai[$ag->id_agenda][$k],
	// 				'nik'=>$kar[$ag->id_agenda]['nik'],
	// 				'nama'=>$kar[$ag->id_agenda]['nama'],
	// 				'jabatan'=>implode('',$jabatan[$ag->id_agenda][$k]),
	// 				'loker'=>implode('',$loker[$ag->id_agenda][$k]),
	// 				'id_jabatan'=>implode('',$id_jabatan[$ag->id_agenda][$k]),
	// 				'id_sub'=>$id_s[$ag->id_agenda],
	// 				'penilai'=>$penilai[$ag->id_agenda][$k],
	// 				'ind'=>count($ind[$ag->id_agenda][$k]),
	// 				'atasan'=>$atasan[$ag->id_agenda][$k],
	// 			);
	// 		}
	// 	}
	// 	if (isset($datax)) {
	// 		$sa=$datax;
	// 	}else{
	// 		$sa=NULL;
	// 	}
	// 	$data=array(
	// 		'agd'=>$agenda,
	// 		'data'=>$sa,
	// 		'jabatan'=>$this->dtroot['adm']['jabatan'],
	// 		'tgl'=>$this->date,
	// 		'jabatan'=>$this->dtroot['adm']['jabatan'],
	// 		'me'=>$this->dtroot['adm']['id_karyawan']
	// 	);
	// 	$this->load->view('user_tem/headerx',$this->dtroot);
	// 	$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 	$this->load->view('user_pages/result_tasks',$data);
	// 	$this->load->view('user_tem/footerx');
	// }
	public function report_value(){
		$nmtb=$this->uri->segment(3);
		$id=$this->uri->segment(4);
		$cektb=$this->model_agenda->cek_tabel($nmtb);
		if ($cektb == 0) {
			$this->messages->notValidParam();  
			redirect('kpages/result_tasks');
		}
		$cek=$this->model_agenda->result_value($nmtb,$id);
		if ($cek == "" || $nmtb == "" || $id == "") {
			$this->messages->notValidParam();  
			redirect('kpages/result_tasks');
		}else{
			if (count($cek) == 0) {
				$this->messages->notValidParam();  
				redirect('kpages/result_tasks');
			}
			$agd=$this->db->get_where('agenda',array('tabel_agenda'=>$nmtb))->row_array();
			$kar=$this->model_karyawan->emp($id);
			$jbt=$this->model_master->k_jabatan($kar['jabatan']);
			$lok=$this->model_master->k_loker($kar['unit']);
			$data=array(
				'profile'=>$kar,
				'log'=>$this->db->get_where('log_login_karyawan',array('id_karyawan'=>$kar['id_karyawan']))->result(),
				'jabatan'=>$jbt,
				'loker'=>$lok,
				'hasil'=>$cek,
				'nmtb'=>$nmtb,
				'agd'=>$agd,
				'idk'=>$id,
			);
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/report_value',$data);
			$this->load->view('user_tem/footerx');
		}
		
	}
	// public function input_tasks_value(){
	// 	$kode=$this->uri->segment(3);
	// 	$dt=$this->model_agenda->cek_agd($kode);
	// 	if ($dt == "" || $kode == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('kpages/tasks'); 
	// 	}else{
	// 		$nmtb=$dt['tabel_agenda'];
	// 		$dt1=$this->model_agenda->task($nmtb);
	// 		foreach ($dt1 as $d) {
	// 			$res[$d->id_karyawan]=$d->id_karyawan;
	// 			$jabatan[$d->id_karyawan][$d->jabatan]=$d->jabatan;
	// 			$loker[$d->id_karyawan][$d->loker]=$d->loker;
	// 			$ind[$d->id_karyawan][$d->kode_indikator]=$d->kode_indikator;
	// 			$id_jabatan[$d->id_karyawan][$d->id_jabatan]=$d->id_jabatan;
	// 			if ($d->id_sub != NULL) {
	// 				$id_sub[$d->id_karyawan][$d->id_sub]=$d->id_sub;
	// 			}
	// 		}
	// 		foreach ($res as $k) {
	// 			$dat=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
	// 			$kar=$this->model_karyawan->emp($k);
	// 			$nilai[$k]=array();
	// 			$penilai[$k]=array();
	// 			foreach ($dat as $d1) {
	// 				if ($d1->kode_penilai != 'P3') {
	// 					$dtp[$k]=array('kode_penilai'=>$d1->kode_penilai,'id_penilai'=>$d1->id_penilai);
	// 					$dtnil[$k]=array();
	// 					if ($d1->ln1 != NULL) {
	// 						$nil_1=array_filter(explode(',', $d1->ln1));
	// 						foreach ($nil_1 as $nnil1) {
	// 							$val_1=str_replace('{KAR', '', $nnil1);
	// 							$vval_1=str_replace('}', '', $val_1);
	// 							$vnil_1=explode(':', $vval_1);
	// 							array_push($dtnil[$k], $vnil_1[0]);
	// 						}
	// 					}
	// 					if ($d1->ln2 != NULL) {
	// 						$nil_2=array_filter(explode(',', $d1->ln2));
	// 						foreach ($nil_2 as $nnil2) {
	// 							$val_2=str_replace('{KAR', '', $nnil2);
	// 							$vval_2=str_replace('}', '', $val_2);
	// 							$vnil_2=explode(':', $vval_2);
	// 							array_push($dtnil[$k], $vnil_2[0]);
	// 						}
	// 					}
	// 					if ($d1->ln3 != NULL) {
	// 						$nil_3=array_filter(explode(',', $d1->ln3));
	// 						foreach ($nil_3 as $nnil3) {
	// 							$val_3=str_replace('{KAR', '', $nnil3);
	// 							$vval_3=str_replace('}', '', $val_3);
	// 							$vnil_3=explode(':', $vval_3);
	// 							array_push($dtnil[$k], $vnil_1[0]);
	// 						}
	// 					}
	// 					if ($d1->ln4 != NULL) {
	// 						$nil_4=array_filter(explode(',', $d1->ln4));
	// 						foreach ($nil_4 as $nnil4) {
	// 							$val_4=str_replace('{KAR', '', $nnil4);
	// 							$vval_4=str_replace('}', '', $val_4);
	// 							$vnil_4=explode(':', $vval_4);
	// 							array_push($dtnil[$k], $vnil_4[0]);
	// 						}
	// 					}
	// 					if ($d1->ln5 != NULL) {
	// 						$nil_5=array_filter(explode(',', $d1->ln5));
	// 						foreach ($nil_5 as $nnil5) {
	// 							$val_5=str_replace('{KAR', '', $nnil5);
	// 							$vval_5=str_replace('}', '', $val_5);
	// 							$vnil_5=explode(':', $vval_5);
	// 							array_push($dtnil[$k], $vnil_5[0]);
	// 						}
	// 					}
	// 					if ($d1->ln6!= NULL) {
	// 						$nil_6=array_filter(explode(',', $d1->ln6));
	// 						foreach ($nil_6 as $nnil6) {
	// 							$val_6=str_replace('{KAR', '', $nnil6);
	// 							$vval_6=str_replace('}', '', $val_6);
	// 							$vnil_6=explode(':', $vval_6);
	// 							array_push($dtnil[$k], $vnil_6[0]);
	// 						}
	// 					}
	// 					//nilai
	// 					array_push($nilai[$k], $dtnil[$k]);
	// 					//penilai
	// 					array_push($penilai[$k], $dtp[$k]);
	// 				}
	// 			}
	// 			if (isset($id_sub[$k])) {
	// 				$id_s[$k]=$id_sub[$k];
	// 				$idss[$k]=implode('', $id_s[$k]);
	// 				$sub[$k]=$this->db->get_where('master_sub_jabatan',array('id_sub'=>$idss[$k]))->row_array();
	// 				$ksub[$k]=$sub[$k]['atasan'];
	// 				$ka=$this->db->query("SELECT id_karyawan FROM karyawan WHERE kode_sub = '$ksub[$k]'")->result();
	// 				$atasan[$k]=array();
	// 				foreach ($ka as $aa) {
	// 					array_push($atasan[$k], $aa->id_karyawan);
	// 				}
	// 			}else{
	// 				$id_s[$k]=NULL;
	// 				$idjb[$k]=implode('',$id_jabatan[$k]);
	// 				$jb[$k]=$this->db->get_where('master_jabatan',array('id_jabatan'=>$idjb[$k]))->row_array();
	// 				$kjb[$k]=$jb[$k]['atasan'];
	// 				$ka=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$kjb[$k]'")->result();
	// 				$atasan[$k]=array();
	// 				foreach ($ka as $aa) {
	// 					array_push($atasan[$k], $aa->id_karyawan);
	// 				}
	// 			}
	// 			$datax[$k]=array(
	// 				'nilai'=>$nilai[$k],
	// 				'nik'=>$kar['nik'],
	// 				'nama'=>$kar['nama'],
	// 				'jabatan'=>implode('',$jabatan[$k]),
	// 				'loker'=>implode('',$loker[$k]),
	// 				'id_jabatan'=>implode('',$id_jabatan[$k]),
	// 				'id_sub'=>$id_s,
	// 				'penilai'=>$penilai[$k],
	// 				'ind'=>count($ind[$k]),
	// 				'atasan'=>$atasan[$k],
	// 			);
	// 		}
	// 		if (isset($datax)) {
	// 			$sa=$datax;
	// 		}else{
	// 			$sa=NULL;
	// 		}
	// 		$data=array(
	// 			'agd'=>$dt,
	// 			'data'=>$sa,
	// 			'nmtb'=>$nmtb,
	// 			'kode'=>$kode,
	// 			'jabatan'=>$this->dtroot['adm']['jabatan'],
	// 			'me'=>$this->dtroot['adm']['id_karyawan'],
	// 		);
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/input_tasks_value',$data);
	// 		$this->load->view('user_tem/footerx');
	// 	}
		
	// }
	// public function input_attitude_tasks_value(){
	// 	$kode=$this->uri->segment(3);
	// 	$dt1=$this->model_agenda->cek_attd_agd($kode);
	// 	if ($dt1 == "" || $kode == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('kpages/attitude_tasks');
	// 	}else{
	// 		$nmtb=$dt1['tabel_agenda'];
	// 		$id=$this->admin;
	// 		$dt=$this->db->query("SELECT * FROM $nmtb WHERE id_karyawan != '$id'")->result();
	// 		$pp=array();
	// 		$at=array();
	// 			$bw=array();
	// 			$rk=array();
	// 		foreach ($dt as $d) {
	// 			$part[$d->id_karyawan]=explode(';', $d->partisipan);
	// 			$n=1;
	// 			$ats[$d->id_karyawan]=array();
	// 			$bwh[$d->id_karyawan]=array();
	// 			$rkn[$d->id_karyawan]=array();
	// 			foreach ($part[$d->id_karyawan] as $p) {
	// 				$p1[$d->id_karyawan]=explode(':', $p);
	// 				if ($p1[$d->id_karyawan][0] == "ATS") {
	// 					array_push($ats[$d->id_karyawan], $p1[$d->id_karyawan][1]);
	// 				}
	// 				if ($p1[$d->id_karyawan][0] == "BWH") {
	// 					array_push($bwh[$d->id_karyawan], $p1[$d->id_karyawan][1]);
	// 				}
	// 				if ($p1[$d->id_karyawan][0] == "RKN") {
	// 					array_push($rkn[$d->id_karyawan], $p1[$d->id_karyawan][1]);
	// 				}
	// 				$n++;
	// 			}
				
	// 			if (in_array($this->admin, $ats[$d->id_karyawan])) {
	// 				array_push($at, $d->id_karyawan);
	// 				array_push($pp, 'ATS:'.$d->id_karyawan);
	// 			}
	// 			if (in_array($this->admin, $bwh[$d->id_karyawan])) {
	// 				array_push($bw, $d->id_karyawan);
	// 				array_push($pp, 'BWH:'.$d->id_karyawan);

	// 			}
	// 			if (in_array($this->admin, $rkn[$d->id_karyawan])) {
	// 				array_push($rk, $d->id_karyawan);
	// 				array_push($pp, 'RKN:'.$d->id_karyawan);
	// 			}

	// 		}
	// 		/*
	// 		if (isset($at)) {
	// 			$at1=array_values(array_unique($at));
	// 			foreach ($at1 as $pa) {
	// 				array_push($sbg, "BWH");
	// 			}
	// 		}
	// 		if (isset($bw)) {
	// 			$bw1=array_values(array_unique($bw));
	// 			foreach ($bw1 as $pb) {
	// 				array_push($sbg, "ATS");
	// 			}
	// 		}
	// 		if (isset($rk)) {
	// 			$rk1=array_values(array_unique($rk));
	// 			foreach ($rk1 as $pr) {
	// 				array_push($sbg, "RKN");
	// 			}
	// 		}*/
	// 		$pp1=array_values(array_unique($pp));
	// 		$ky=$this->model_karyawan->emp($this->admin);
	// 		$sbg=array();
	// 		$smp=array();
	// 		foreach ($pp1 as $px1) {
	// 			$px=explode(':', $px1);
	// 			array_push($smp, $px[1]);
	// 			if ($px[0] == "ATS") {
	// 				array_push($sbg, "ATS");
	// 			}
	// 			if ($px[0] == "BWH") {
	// 				array_push($sbg, "BWH");
	// 			}
	// 			if ($px[0] == "RKN") {
	// 				array_push($sbg, "RKN");
	// 			}
	// 		}
			

			
	// 		/*
	// 		$n1=1;
	// 		echo '<pre>';
	// 		print_r($sbg);
	// 		echo '</pre>';
	// 		foreach ($pp1 as $px) {
	// 			$kar=$this->model_karyawan->emp($px);
	// 			$jbtx=$this->model_master->k_jabatan($kar['jabatan']);
	// 			$jbt=$this->model_master->k_jabatan($ky['jabatan']);
	// 			if ($jbt['jabatan'] == $jbtx['jabatan']) {
	// 				if ($this->admin != $px) {
	// 					$sbg[$n1]="RKN";
	// 				}else{
	// 					$sbg[$n1]="DRI";
	// 				}
	// 			}else{
	// 				if ($jbt['kode_jabatan'] == $jbtx['atasan']) {
	// 					$sbg[$n1]="ATS";
	// 				}elseif ($jbt['atasan'] == $jbtx['kode_jabatan']) {
	// 					$sbg[$n1]="BWH";
	// 				}
	// 				elseif ($this->admin == $px) {
	// 					$sbg[$n1]="DRI";
	// 				}else{
	// 					$sbg[$n1]="RKN";
	// 				}
	// 			}	
	// 			$n1++;
	// 		}*/
	// 		$data=array(
	// 			'agd'=>$dt1,
	// 			'nmtb'=>$nmtb,
	// 			'kode'=>$kode,
	// 			'idk'=>$smp,
	// 			'id'=>$this->admin,
	// 			'sbg'=>$sbg,
	// 		);
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/input_attitude_tasks_value',$data);
	// 		$this->load->view('user_tem/footerx');
	// 	}
		
	// }
	// public function input_attitude_value(){
	// 	$kode=$this->uri->segment(3);
	// 	$idx=$this->uri->segment(4);

	// 	$kas=$this->uri->segment(5);
	// 	$dt1=$this->model_agenda->cek_attd_agd($kode);
	// 	if ($dt1 == "" || $kode == "" || $kas == "" || $idx == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('kpages/attitude_tasks');
	// 	}else{
	// 		$nmtb=$dt1['tabel_agenda'];
	// 		$sa=explode(':', $idx);
	// 		$id=$sa[1];
	// 		$sb=$sa[0].':'.$this->admin;

	// 		$dt=$this->model_agenda->task_k_as($nmtb,$id,$kas);
	// 		$dtx=$this->model_agenda->task_k($nmtb,$id);
	// 		if (count($dt) == 0) {
	// 			$this->messages->notValidParam();  
	// 			redirect('kpages/input_attitude_tasks_value/'.$kode);
	// 		}
	// 		$n1=1;
	// 		foreach ($dtx as $d) {
	// 			$aspekx[$n1]=$d->kode_aspek;
	// 			$part=explode(';', $d->partisipan);
	// 			if (in_array($sb, $part)) {
	// 				$idkk[]=$d->id_karyawan;
	// 			}
	// 			$n1++;
	// 		}

	// 		$kar=$this->model_karyawan->emp($id);
	// 		$ky=$this->model_karyawan->emp($this->admin);
	// 		if (!isset($idkk)) {
	// 			$this->messages->notValidParam();  
	// 			redirect('kpages/input_attitude_tasks_value/'.$kode);
	// 		}
	// 		$pp1=array_values(array_unique($idkk));

	// 		//print_r($pp1);
	// 		/*

			
	// 		$jbtx=$this->model_master->k_jabatan($kar['jabatan']);
	// 		$jbt=$this->model_master->k_jabatan($ky['jabatan']);
	// 		if ($jbt['jabatan'] == $jbtx['jabatan']) {
	// 			if ($this->admin != $id) {
	// 				$sbg="RKN";
	// 			}else{
	// 				$sbg="DRI";
	// 			}
	// 		}else{
	// 			if ($jbt['kode_jabatan'] == $jbtx['atasan']) {
	// 				$sbg="ATS";
	// 			}elseif ($jbt['atasan'] == $jbtx['kode_jabatan']) {
	// 				$sbg="BWH";
	// 			}elseif ($this->admin == $id) {
	// 				$sbg="DRI";
	// 			}else{
	// 				$sbg="RKN";
	// 			}
	// 		}
	// 		 */
	// 		$sbg=$sa[0];
	// 		$x=1;
	// 		foreach ($dt as $d1) {
	// 			$kuisioner[$x]=$d1->kuisioner;
	// 			$ats[$x]=$d1->atas;
	// 			$bwh[$x]=$d1->bawah;
	// 			$jabat[$d1->jabatan]=$d1->jabatan;
	// 			$loker[$d1->loker]=$d1->loker;
	// 			$kode_k[$x]=$d1->kode_kuisioner;
	// 			if ($sbg == "DRI") {
	// 				if ($d1->nilai_dri != NULL) {
	// 					$nilai[$x]=$d1->nilai_dri;
	// 					$ket[$x]=$d1->keterangan_dri;
	// 					$avl[$x]=$d1->nilai_dri;
	// 				}else{
	// 					$nilai[$x]=NULL;
	// 					$ket[$x]=NULL;
	// 				}
	// 			}elseif ($sbg == "ATS") {
	// 				if ($d1->nilai_ats != NULL) {
	// 					$o=explode(';', $d1->nilai_ats);
	// 					foreach ($o as $ox) {
	// 						$o1=explode(':', $ox);
	// 						if ($o1[0] == $this->admin) {
	// 							$nilai[$x]=$o1[1];
	// 							$avl[$x]=$o1[1];
	// 						}
	// 					}
	// 					$ko=explode(';', $d1->keterangan_ats);
	// 					foreach ($ko as $kox) {
	// 						$ko1=explode(':', $kox);
	// 						if ($ko1[0] == $this->admin) {
	// 							$ket[$x]=$ko1[1];
	// 						}
	// 					}
	// 				}else{
	// 					$nilai[$x]=NULL;
	// 				}
	// 			}elseif ($sbg == "BWH") {
	// 				if ($d1->nilai_bwh != NULL) {
	// 					$o=explode(';', $d1->nilai_bwh);
	// 					foreach ($o as $ox) {
	// 						$o1=explode(':', $ox);
	// 						if ($o1[0] == $this->admin) {
	// 							$nilai[$x]=$o1[1];
	// 							$avl[$x]=$o1[1];
	// 						}
	// 					}
	// 					$ko=explode(';', $d1->keterangan_bwh);
	// 					foreach ($ko as $kox) {
	// 						$ko1=explode(':', $kox);
	// 						if ($ko1[0] == $this->admin) {
	// 							$ket[$x]=$ko1[1];
	// 						}
	// 					}
	// 				}else{
	// 					$nilai[$x]=NULL;
	// 				}
	// 			}elseif ($sbg == "RKN") {
	// 				if ($d1->nilai_rkn != NULL) {
	// 					$o=explode(';', $d1->nilai_rkn);
	// 					foreach ($o as $ox) {
	// 						$o1=explode(':', $ox);
	// 						if ($o1[0] == $this->admin) {
	// 							$nilai[$x]=$o1[1];
	// 							$avl[$x]=$o1[1];
	// 						}
	// 					}
	// 					$ko=explode(';', $d1->keterangan_rkn);
	// 					foreach ($ko as $kox) {
	// 						$ko1=explode(':', $kox);
	// 						if ($ko1[0] == $this->admin) {
	// 							$ket[$x]=$ko1[1];
	// 						}
	// 					}
	// 				}else{
	// 					$nilai[$x]=NULL;
	// 				}
	// 			}

	// 			if (!isset($nilai[$x])) {
	// 				$nilai[$x]=NULL;
	// 			}
	// 			if (!isset($ket[$x])) {
	// 				$ket[$x]=NULL;
	// 			}
	// 			$x++;
	// 		}
	// 		$ko_a=array_values(array_unique($aspekx));
			

	// 		$data=array(
	// 			'kuisioner'=>$kuisioner,
	// 			'nama'=>$kar['nama'],
	// 			'foto'=>$kar['foto'],
	// 			'agd'=>$dt1,
	// 			'nmtb'=>$nmtb,
	// 			'aspek'=>$kas,
	// 			'kode'=>$kode,
	// 			'idk'=>$pp1,
	// 			'sbg'=>$sbg,
	// 			'id'=>$this->admin,
	// 			'id_k'=>$id,
	// 			'kar'=>$kar,
	// 			'nilai'=>$nilai,
	// 			'ket'=>$ket,
	// 			'ats'=>$ats,
	// 			'bwh'=>$bwh,
	// 			'kk'=>$kode_k,
	// 			'jb'=>implode('', $jabat),
	// 			'lk'=>implode('', $loker),
	// 			'pn'=>$sbg.':'.$this->admin,
	// 			'ko_a'=>$ko_a,
	// 		);
	// 		$data1=array('jm_ni'=>count($kuisioner),'ats'=>$ats,'bwh'=>$bwh);
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/input_attitude_value',$data);
	// 		$this->load->view('user_tem/footerx',$data1);
	// 	}
		
	// }
	/*
			$p=explode(':', $id_p);
			foreach ($dti as $d) {
				$nm[$d->nama]=$d->nama;
				$par=array_filter(explode(';', $d->partisipan));

				if (in_array($id_p, $par)) {
					$kuisioner[$n]=$d->kuisioner;
					$kode_k[$n]=$d->kode_kuisioner;
					$aspek[$n]=$d->kode_aspek;
					$ats[$n]=$d->atas;
					$bwh[$n]=$d->bawah;
					if ($p[0] == "ATS") {
						if ($d->nilai_ats != NULL) {
							$nat=array_filter(explode(';', $d->nilai_ats));
							$knat=array_filter(explode(';', $d->keterangan_ats));
							foreach ($nat as $nt) {
								$nt1=explode(':', $nt);
								if (in_array($p[1], $nt1)) {
									$nilai[$n]=$nt1[1];
								}
							}
							foreach ($knat as $knt) {
								$knt1=explode(':', $knt);
								if (in_array($p[1], $knt1)) {
									$keterangan[$n]=$knt1[1];
								}
							}
						}else{
							$nilai[$n]=0;
							$keterangan[$n]=NULL;
						}
					}elseif ($p[0] == "BWH") {
						if ($d->nilai_bwh != NULL) {
							$nat=array_filter(explode(';', $d->nilai_bwh));
							$knat=array_filter(explode(';', $d->keterangan_bwh));
							foreach ($nat as $nt) {
								$nt1=explode(':', $nt);
								if (in_array($p[1], $nt1)) {
									$nilai[$n]=$nt1[1];
								}
							}
							foreach ($knat as $knt) {
								$knt1=explode(':', $knt);
								if (in_array($p[1], $knt1)) {
									$keterangan[$n]=$knt1[1];
								}
							}
						}else{
							$nilai[$n]=0;
							$keterangan[$n]=NULL;
						}

					}elseif ($p[0] == "RKN") {
						if ($d->nilai_rkn != NULL) {
							$nat=array_filter(explode(';', $d->nilai_rkn));
							$knat=array_filter(explode(';', $d->keterangan_rkn));
							foreach ($nat as $nt) {
								$nt1=explode(':', $nt);
								if (in_array($p[1], $nt1)) {
									$nilai[$n]=$nt1[1];
								}
							}
							foreach ($knat as $knt) {
								$knt1=explode(':', $knt);
								if (in_array($p[1], $knt1)) {
									$keterangan[$n]=$knt1[1];
								}
							}
						}else{
							$nilai[$n]=0;
							$keterangan[$n]=NULL;
						}
					}else{
						if ($d->nilai_dri != NULL) {
							$nilai[$n]=$d->nilai_dri;
							$keterangan[$n]=$d->keterangan_dri;
						}else{
							$nilai[$n]=0;
							$keterangan[$n]=NULL;
						}
					}

				}else{
					$this->messages->notValidParam();  
					redirect('pages/result_attitude_partisipant/'.$kode.'/'.$id);
				}

				$n++;
			}
			if (isset($keterangan)) {
				$ke=$keterangan;
			}else{
				$ke=NULL;
			}
			$kr=$this->model_karyawan->emp($p[1]);
			$kr=$this->model_karyawan->emp($id);
			$data=array(
				'agd'=>$dt,
				'aspek'=>$aspek,
				'ket'=>$ke,
				'ats'=>$ats,
				'bwh'=>$bwh,
				'nama'=>implode('', $nm), 
				'kar'=>$kr,
				'sbg'=>$p[0],
				'pn'=>$id_p,
				'id_k'=>$id,
				'tabel'=>$dti,
				'kode'=>$kode,
				'ntabel'=>$nmtb,
				'kuisioner'=>$kuisioner,
				'kk'=>$kode_k,
				'nilai'=>$nilai,
			);
			$data1=array('jm_ni'=>count($nilai),'ats'=>$ats,'bwh'=>$bwh);
			*/
	// public function result_attd_tasks_value(){
	// 	$kode=$this->uri->segment(3);
	// 	$dt1=$this->model_agenda->cek_attd_agd($kode);
	// 	if ($dt1 == "" || $kode == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('kpages/attitude_tasks');
	// 	}else{
	// 		$nmtb=$dt1['tabel_agenda'];
	// 		$id=$this->admin;
	// 		$dt=$this->db->query("SELECT * FROM $nmtb WHERE id_karyawan != '$id'")->result();
	// 		$pp=array();
	// 		$at=array();
	// 			$bw=array();
	// 			$rk=array();
	// 		foreach ($dt as $d) {
	// 			$part[$d->id_karyawan]=explode(';', $d->partisipan);
	// 			$n=1;
	// 			$ats[$d->id_karyawan]=array();
	// 			$bwh[$d->id_karyawan]=array();
	// 			$rkn[$d->id_karyawan]=array();
	// 			foreach ($part[$d->id_karyawan] as $p) {
	// 				$p1[$d->id_karyawan]=explode(':', $p);
	// 				if ($p1[$d->id_karyawan][0] == "ATS") {
	// 					array_push($ats[$d->id_karyawan], $p1[$d->id_karyawan][1]);
	// 				}
	// 				if ($p1[$d->id_karyawan][0] == "BWH") {
	// 					array_push($bwh[$d->id_karyawan], $p1[$d->id_karyawan][1]);
	// 				}
	// 				if ($p1[$d->id_karyawan][0] == "RKN") {
	// 					array_push($rkn[$d->id_karyawan], $p1[$d->id_karyawan][1]);
	// 				}
	// 				$n++;
	// 			}
				
	// 			if (in_array($this->admin, $ats[$d->id_karyawan])) {
	// 				array_push($at, $d->id_karyawan);
	// 				array_push($pp, 'ATS:'.$d->id_karyawan);
	// 			}
	// 			if (in_array($this->admin, $bwh[$d->id_karyawan])) {
	// 				array_push($bw, $d->id_karyawan);
	// 				array_push($pp, 'BWH:'.$d->id_karyawan);

	// 			}
	// 			if (in_array($this->admin, $rkn[$d->id_karyawan])) {
	// 				array_push($rk, $d->id_karyawan);
	// 				array_push($pp, 'RKN:'.$d->id_karyawan);
	// 			}

	// 		}
	// 		/*
	// 		if (isset($at)) {
	// 			$at1=array_values(array_unique($at));
	// 			foreach ($at1 as $pa) {
	// 				array_push($sbg, "BWH");
	// 			}
	// 		}
	// 		if (isset($bw)) {
	// 			$bw1=array_values(array_unique($bw));
	// 			foreach ($bw1 as $pb) {
	// 				array_push($sbg, "ATS");
	// 			}
	// 		}
	// 		if (isset($rk)) {
	// 			$rk1=array_values(array_unique($rk));
	// 			foreach ($rk1 as $pr) {
	// 				array_push($sbg, "RKN");
	// 			}
	// 		}*/
	// 		$pp1=array_values(array_unique($pp));
	// 		$ky=$this->model_karyawan->emp($this->admin);
	// 		$sbg=array();
	// 		$smp=array();
	// 		foreach ($pp1 as $px1) {
	// 			$px=explode(':', $px1);
	// 			array_push($smp, $px[1]);
	// 			if ($px[0] == "ATS") {
	// 				array_push($sbg, "ATS");
	// 			}
	// 			if ($px[0] == "BWH") {
	// 				array_push($sbg, "BWH");
	// 			}
	// 			if ($px[0] == "RKN") {
	// 				array_push($sbg, "RKN");
	// 			}
	// 		}
			

			
	// 		/*
	// 		$n1=1;
	// 		echo '<pre>';
	// 		print_r($sbg);
	// 		echo '</pre>';
	// 		foreach ($pp1 as $px) {
	// 			$kar=$this->model_karyawan->emp($px);
	// 			$jbtx=$this->model_master->k_jabatan($kar['jabatan']);
	// 			$jbt=$this->model_master->k_jabatan($ky['jabatan']);
	// 			if ($jbt['jabatan'] == $jbtx['jabatan']) {
	// 				if ($this->admin != $px) {
	// 					$sbg[$n1]="RKN";
	// 				}else{
	// 					$sbg[$n1]="DRI";
	// 				}
	// 			}else{
	// 				if ($jbt['kode_jabatan'] == $jbtx['atasan']) {
	// 					$sbg[$n1]="ATS";
	// 				}elseif ($jbt['atasan'] == $jbtx['kode_jabatan']) {
	// 					$sbg[$n1]="BWH";
	// 				}
	// 				elseif ($this->admin == $px) {
	// 					$sbg[$n1]="DRI";
	// 				}else{
	// 					$sbg[$n1]="RKN";
	// 				}
	// 			}	
	// 			$n1++;
	// 		}*/
	// 		$data=array(
	// 			'agd'=>$dt1,
	// 			'nmtb'=>$nmtb,
	// 			'kode'=>$kode,
	// 			'idk'=>$smp,
	// 			'id'=>$this->admin,
	// 			'sbg'=>$sbg,
	// 		);
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/result_attd_tasks_value',$data);
	// 		$this->load->view('user_tem/footerx');
	// 	}
	// }
	// public function report_attd_value(){
	// 	$kode=$this->uri->segment(3);
	// 	$idx=$this->uri->segment(4);
	// 	$dt1=$this->model_agenda->cek_attd_agd($kode);
	// 	if ($dt1 == "" || $kode == "" || $idx == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('kpages/result_attd_tasks');
	// 	}else{
	// 		$nmtb=$dt1['tabel_agenda'];
	// 		$sa=explode(':', $idx);
	// 		$id=$sa[1];
	// 		$sb=$sa[0].':'.$this->admin;

	// 		$dt=$this->model_agenda->task_k_asx($nmtb,$id);
	// 		$dtx=$this->model_agenda->task_k($nmtb,$id);
	// 		if (count($dt) == 0) {
	// 			$this->messages->notValidParam();  
	// 			redirect('kpages/result_attd_tasks_value/'.$kode);
	// 		}
	// 		$n1=1;
	// 		foreach ($dtx as $d) {
	// 			$aspekx[$n1]=$d->kode_aspek;
	// 			$part=explode(';', $d->partisipan);
	// 			if (in_array($sb, $part)) {
	// 				$idkk[]=$d->id_karyawan;
	// 			}
	// 			$n1++;
	// 		}

	// 		$kar=$this->model_karyawan->emp($id);
	// 		$ky=$this->model_karyawan->emp($this->admin);
	// 		if (!isset($idkk)) {
	// 			$this->messages->notValidParam();  
	// 			redirect('kpages/result_attd_tasks_value/'.$kode);
	// 		}
	// 		$pp1=array_values(array_unique($idkk));

	// 		//print_r($pp1);
	// 		/*

			
	// 		$jbtx=$this->model_master->k_jabatan($kar['jabatan']);
	// 		$jbt=$this->model_master->k_jabatan($ky['jabatan']);
	// 		if ($jbt['jabatan'] == $jbtx['jabatan']) {
	// 			if ($this->admin != $id) {
	// 				$sbg="RKN";
	// 			}else{
	// 				$sbg="DRI";
	// 			}
	// 		}else{
	// 			if ($jbt['kode_jabatan'] == $jbtx['atasan']) {
	// 				$sbg="ATS";
	// 			}elseif ($jbt['atasan'] == $jbtx['kode_jabatan']) {
	// 				$sbg="BWH";
	// 			}elseif ($this->admin == $id) {
	// 				$sbg="DRI";
	// 			}else{
	// 				$sbg="RKN";
	// 			}
	// 		}
	// 		 */
	// 		$sbg=$sa[0];
	// 		$x=1;
	// 		foreach ($dt as $d1) {
	// 			$jabatan=array('jabatan'=>$d1->jabatan);
	// 			$loker=array('loker'=>$d1->loker);
	// 			$kuisioner[$x]=$d1->kuisioner;
	// 			$ats[$x]=$d1->atas;
	// 			$bwh[$x]=$d1->bawah;
	// 			$kode_k[$x]=$d1->kode_kuisioner;
	// 			$kode_a[$x]=$d1->kode_aspek;
	// 			if ($sbg == "DRI") {
	// 				if ($d1->nilai_dri != NULL) {
	// 					$nilai[$x]=$d1->nilai_dri;
	// 					$ket[$x]=$d1->keterangan_dri;
	// 					$avl[$x]=$d1->nilai_dri;
	// 				}else{
	// 					$nilai[$x]=NULL;
	// 					$ket[$x]=NULL;
	// 				}
	// 			}elseif ($sbg == "ATS") {
	// 				if ($d1->nilai_ats != NULL) {
	// 					$o=explode(';', $d1->nilai_ats);
	// 					foreach ($o as $ox) {
	// 						$o1=explode(':', $ox);
	// 						if ($o1[0] == $this->admin) {
	// 							$nilai[$x]=$o1[1];
	// 							$avl[$x]=$o1[1];
	// 						}
	// 					}
	// 					$ko=explode(';', $d1->keterangan_ats);
	// 					foreach ($ko as $kox) {
	// 						$ko1=explode(':', $kox);
	// 						if ($ko1[0] == $this->admin) {
	// 							$ket[$x]=$ko1[1];
	// 						}
	// 					}
	// 				}else{
	// 					$nilai[$x]=NULL;
	// 				}
	// 			}elseif ($sbg == "BWH") {
	// 				if ($d1->nilai_bwh != NULL) {
	// 					$o=explode(';', $d1->nilai_bwh);
	// 					foreach ($o as $ox) {
	// 						$o1=explode(':', $ox);
	// 						if ($o1[0] == $this->admin) {
	// 							$nilai[$x]=$o1[1];
	// 							$avl[$x]=$o1[1];
	// 						}
	// 					}
	// 					$ko=explode(';', $d1->keterangan_bwh);
	// 					foreach ($ko as $kox) {
	// 						$ko1=explode(':', $kox);
	// 						if ($ko1[0] == $this->admin) {
	// 							$ket[$x]=$ko1[1];
	// 						}
	// 					}
	// 				}else{
	// 					$nilai[$x]=NULL;
	// 				}
	// 			}elseif ($sbg == "RKN") {
	// 				if ($d1->nilai_rkn != NULL) {
	// 					$o=explode(';', $d1->nilai_rkn);
	// 					foreach ($o as $ox) {
	// 						$o1=explode(':', $ox);
	// 						if ($o1[0] == $this->admin) {
	// 							$nilai[$x]=$o1[1];
	// 							$avl[$x]=$o1[1];
	// 						}
	// 					}
	// 					$ko=explode(';', $d1->keterangan_rkn);
	// 					foreach ($ko as $kox) {
	// 						$ko1=explode(':', $kox);
	// 						if ($ko1[0] == $this->admin) {
	// 							$ket[$x]=$ko1[1];
	// 						}
	// 					}
	// 				}else{
	// 					$nilai[$x]=NULL;
	// 				}
	// 			}

	// 			if (!isset($nilai[$x])) {
	// 				$nilai[$x]=NULL;
	// 			}
	// 			if (!isset($ket[$x])) {
	// 				$ket[$x]=NULL;
	// 			}
	// 			$x++;
	// 		}
	// 		$ko_a=array_values(array_unique($aspekx));
			
	// 		$profile=array('email'=>$kar['email'],'kelamin'=>$kar['kelamin'],'tgl_masuk'=>$kar['tgl_masuk'],'username'=>$kar['nik'],'nama'=>$kar['nama'],'foto'=>$kar['foto'],'loker'=>$loker['loker'],'jabatan'=>$jabatan['jabatan']);
	// 		$data=array(
	// 			'kuisioner'=>$kuisioner,
	// 			'profile'=>$profile,
	// 			'kode_asp'=>$kode_a,
	// 			'agd'=>$dt1,
	// 			'nmtb'=>$nmtb,
	// 			'kode'=>$kode,
	// 			'idk'=>$pp1,
	// 			'sbg'=>$sbg,
	// 			'id'=>$this->admin,
	// 			'id_k'=>$id,
	// 			'kar'=>$kar,
	// 			'nilai'=>$nilai,
	// 			'ket'=>$ket,
	// 			'ats'=>$ats,
	// 			'bwh'=>$bwh,
	// 			'kk'=>$kode_k,
	// 			'pn'=>$sbg.':'.$this->admin,
	// 			'ko_a'=>$ko_a,
	// 		);
	// 		$data1=array('jm_ni'=>count($kuisioner),'ats'=>$ats,'bwh'=>$bwh);
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/report_attd_value',$data);
	// 		$this->load->view('user_tem/footerx',$data1);
	// 	}
	// }
	public function input_value(){
		$id=$this->uri->segment(4);
		$kode=$this->uri->segment(3);
		$dt=$this->model_agenda->cek_agd($kode);
		if ($dt == "" || $kode == "" || $id == "") {
			$this->messages->notValidParam();  
			redirect('kpages/tasks');
		}else{
			$nmtb=$dt['tabel_agenda'];
			$idkk=$this->db->get_where('karyawan',array('id_karyawan'=>$this->admin))->row_array();
			$idkk1=$this->db->get_where('karyawan',array('id_karyawan'=>$id))->row_array();
			$jbtn=$idkk['jabatan'];
			$jbtn1=$idkk1['jabatan'];
			//jabatan

			$jb=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$jbtn))->row_array();
			$jb1=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$jbtn1))->row_array();
			$idj=$jb['id_jabatan'];
			$dti=$this->db->query("SELECT * FROM $nmtb WHERE id_karyawan = '$id' AND kode_penilai != 'P3' ORDER BY urutan ASC")->result();
			if ($dti == "") {
				redirect('kpages/task');
			}
			$k=$this->db->query("SELECT nama,jabatan,jabatan_pa FROM karyawan WHERE id_karyawan = '$id'")->row_array();
			
			if ($k['jabatan_pa'] != NULL) {
				$jbt=$this->model_master->jabatan($k['jabatan_pa']);
			}else{
				$jbt=$this->model_master->k_jabatan($k['jabatan']);
			}
			$data=array(
				'id'=>$id,
				'agd'=>$dt,
				'tabel'=>$dti,
				'ntabel'=>$nmtb,
				'kode'=>$kode,
				'smt'=>$dt['semester'],
				'nama'=>$k['nama'],
				'jbtk'=>$jb1,
				'jabatan'=>$jbt,
				'jabatanx'=>$this->dtroot['adm']['jabatan'],
				'nik'=>$this->dtroot['adm']['id_karyawan'],
			);

			
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/input_value',$data);
			$this->load->view('user_tem/footerx');
			
		}
		
	}
	public function result_tasks_value(){
		$kode=$this->uri->segment(3);
		$dt=$this->model_agenda->cek_agd($kode);
		if ($dt == "" || $kode == "") {
			$this->messages->notValidParam();  
			redirect('kpages/result_tasks'); 
		}else{
			if ($dt['validasi'] == 0) {
				$this->messages->notValidParam();  
				redirect('kpages/result_tasks'); 
			}
			$nmtb=$dt['tabel_agenda'];
			$dt1=$this->model_agenda->task($nmtb);
			foreach ($dt1 as $d) {
				$res[$d->id_karyawan]=$d->id_karyawan;
				$jabatan[$d->id_karyawan][$d->jabatan]=$d->jabatan;
				$loker[$d->id_karyawan][$d->loker]=$d->loker;
				$ind[$d->id_karyawan][$d->kode_indikator]=$d->kode_indikator;
				$id_jabatan[$d->id_karyawan][$d->id_jabatan]=$d->id_jabatan;
				if ($d->id_sub != NULL) {
					$id_sub[$d->id_karyawan][$d->id_sub]=$d->id_sub;
				}
			}
			// foreach ($dt1 as $d3) {
			// 	if ($d3->id_penilai != NULL && $d3->kode_penilai == "P4") {
			// 		$ky[$d3->id_karyawan]=$d3->id_karyawan;
			// 	}elseif ($d3->kode_penilai == "P1") {
					
			// 	}elseif ($d3->kode_penilai != "P3") {
					
			// 	}
			// }
			// if (isset($penilai)) {
			// 	foreach ($penilai as $key => $v) {
			// 		print_r($v);
			// 	}
			// }else{

			// }
			
			// foreach ($dt1 as $d) {
			// 	$res[$d->id_karyawan]=$d->id_karyawan;
			// 	$jabatan[$d->id_karyawan][$d->jabatan]=$d->jabatan;
			// 	$loker[$d->id_karyawan][$d->loker]=$d->loker;
			// 	$ind[$d->id_karyawan][$d->kode_indikator]=$d->kode_indikator;
				
			// }
			foreach ($res as $k) {
				$dat=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
				$kar=$this->model_karyawan->emp($k);
				$nilai[$k]=array();
				$penilai[$k]=array();
				foreach ($dat as $d1) {
					if ($d1->kode_penilai != 'P3') {
						$dtp[$k]=array('kode_penilai'=>$d1->kode_penilai,'id_penilai'=>$d1->id_penilai);
						$dtnil[$k]=array();
						if ($d1->ln1 != NULL) {
							$nil_1=array_filter(explode(',', $d1->ln1));
							foreach ($nil_1 as $nnil1) {
								$val_1=str_replace('{KAR', '', $nnil1);
								$vval_1=str_replace('}', '', $val_1);
								$vnil_1=explode(':', $vval_1);
								array_push($dtnil[$k], $vnil_1[0]);
							}
						}
						if ($d1->ln2 != NULL) {
							$nil_2=array_filter(explode(',', $d1->ln2));
							foreach ($nil_2 as $nnil2) {
								$val_2=str_replace('{KAR', '', $nnil2);
								$vval_2=str_replace('}', '', $val_2);
								$vnil_2=explode(':', $vval_2);
								array_push($dtnil[$k], $vnil_2[0]);
							}
						}
						if ($d1->ln3 != NULL) {
							$nil_3=array_filter(explode(',', $d1->ln3));
							foreach ($nil_3 as $nnil3) {
								$val_3=str_replace('{KAR', '', $nnil3);
								$vval_3=str_replace('}', '', $val_3);
								$vnil_3=explode(':', $vval_3);
								array_push($dtnil[$k], $vnil_1[0]);
							}
						}
						if ($d1->ln4 != NULL) {
							$nil_4=array_filter(explode(',', $d1->ln4));
							foreach ($nil_4 as $nnil4) {
								$val_4=str_replace('{KAR', '', $nnil4);
								$vval_4=str_replace('}', '', $val_4);
								$vnil_4=explode(':', $vval_4);
								array_push($dtnil[$k], $vnil_4[0]);
							}
						}
						if ($d1->ln5 != NULL) {
							$nil_5=array_filter(explode(',', $d1->ln5));
							foreach ($nil_5 as $nnil5) {
								$val_5=str_replace('{KAR', '', $nnil5);
								$vval_5=str_replace('}', '', $val_5);
								$vnil_5=explode(':', $vval_5);
								array_push($dtnil[$k], $vnil_5[0]);
							}
						}
						if ($d1->ln6!= NULL) {
							$nil_6=array_filter(explode(',', $d1->ln6));
							foreach ($nil_6 as $nnil6) {
								$val_6=str_replace('{KAR', '', $nnil6);
								$vval_6=str_replace('}', '', $val_6);
								$vnil_6=explode(':', $vval_6);
								array_push($dtnil[$k], $vnil_6[0]);
							}
						}
						//nilai
						array_push($nilai[$k], $dtnil[$k]);
						//penilai
						array_push($penilai[$k], $dtp[$k]);
					}
				}
				if (isset($id_sub[$k])) {
					$id_s[$k]=$id_sub[$k];
					$idss[$k]=implode('', $id_s[$k]);
					$sub[$k]=$this->db->get_where('master_sub_jabatan',array('id_sub'=>$idss[$k]))->row_array();
					$ksub[$k]=$sub[$k]['atasan'];
					$ka=$this->db->query("SELECT id_karyawan FROM karyawan WHERE kode_sub = '$ksub[$k]'")->result();
					$atasan[$k]=array();
					foreach ($ka as $aa) {
						array_push($atasan[$k], $aa->id_karyawan);
					}
				}else{
					$id_s[$k]=NULL;
					$idjb[$k]=implode('',$id_jabatan[$k]);
					$jb[$k]=$this->db->get_where('master_jabatan',array('id_jabatan'=>$idjb[$k]))->row_array();
					$kjb[$k]=$jb[$k]['atasan'];
					$ka=$this->db->query("SELECT id_karyawan FROM karyawan WHERE jabatan = '$kjb[$k]'")->result();
					$atasan[$k]=array();
					foreach ($ka as $aa) {
						array_push($atasan[$k], $aa->id_karyawan);
					}
				}
				$datax[$k]=array(
					'nilai'=>$nilai[$k],
					'nik'=>$kar['nik'],
					'nama'=>$kar['nama'],
					'jabatan'=>implode('',$jabatan[$k]),
					'loker'=>implode('',$loker[$k]),
					'id_jabatan'=>implode('',$id_jabatan[$k]),
					'id_sub'=>$id_s,
					'penilai'=>$penilai[$k],
					'ind'=>count($ind[$k]),
					'atasan'=>$atasan[$k],
				);
			}
			if (isset($datax)) {
				$sa=$datax;
			}else{
				$sa=NULL;
			}
			$data=array(
				'agd'=>$dt,
				'data'=>$sa,
				'nmtb'=>$nmtb,
				'kode'=>$kode,
				'jabatan'=>$this->dtroot['adm']['jabatan'],
				'me'=>$this->dtroot['adm']['id_karyawan'],
			);
			// echo '<pre>';
			//print_r($data);
			$this->load->view('user_tem/headerx',$this->dtroot);
			$this->load->view('user_tem/sidebarx',$this->dtroot);
			$this->load->view('user_pages/result_tasks_value',$data);
			$this->load->view('user_tem/footerx');
		}
		
	}
	//==RAPORT==//
	// public function list_raport_output(){
	// 	$cek=$this->model_agenda->log_agenda();
	// 	if (count($cek) > 0) {
	// 		$lst=array();
	// 		foreach ($cek as $c) {
	// 			if ($c->tabel_agenda != "") {
	// 				$tb=$this->db->get_where($c->tabel_agenda,array('id_karyawan'=>$this->admin))->num_rows();
	// 				if ($tb > 0) {
	// 					array_push($lst, $c->kode_agenda);
	// 				}
	// 			}
	// 		}
	// 		if (count($lst) > 0) {
	// 			$lst1=array_filter($lst);
	// 		}else{
	// 			$lst1=NULL;
	// 		}
	// 	}else{
	// 		$lst1=NULL;
	// 	}
		
	// 	$data=array(
	// 		'agd'=>$lst1,
	// 		'tgl'=>$this->date,
	// 		'jabatan'=>$this->dtroot['adm']['jabatan'],
	// 	);
	// 	$this->load->view('user_tem/headerx',$this->dtroot);
	// 	$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 	$this->load->view('user_pages/list_raport_output',$data);
	// 	$this->load->view('user_tem/footerx');
	// }
	// public function view_raport_output(){
	// 	$nmtb=$this->uri->segment(3);
	// 	$id=$this->admin;
	// 	$cektb=$this->model_agenda->cek_tabel($nmtb);
	// 	if ($cektb == 0) {
	// 		$this->messages->notValidParam();  
	// 		redirect('kpages/list_raport_output');
	// 	}
	// 	$cek=$this->model_agenda->result_value($nmtb,$id); 
	// 	if ($cek == "" || $nmtb == "" || $id == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('kpages/list_raport_output');
	// 	}else{
	// 		if (count($cek) == 0) {
	// 			$this->messages->notValidParam();  
	// 			redirect('kpages/list_raport_output');
	// 		}
	// 		$agd=$this->db->get_where('log_agenda',array('tabel_agenda'=>$nmtb))->row_array();
	// 		$kar=$this->model_karyawan->emp($id);
	// 		if ($kar['jabatan_pa'] != NULL) {
	// 			$jbt=$this->model_master->jabatan($kar['jabatan_pa']);
	// 		}else{
	// 			$jbt=$this->model_master->k_jabatan($kar['jabatan']);

	// 		}
	// 		if ($kar['loker_pa'] != NULL) {
	// 			$lok=$this->model_master->loker($kar['loker_pa']);
	// 		}else{
	// 			$lok=$this->model_master->k_loker($kar['unit']);
	// 		}
	// 		$data=array(
	// 			'profile'=>$kar,
	// 			'log'=>$this->db->get_where('log_login_karyawan',array('id_karyawan'=>$kar['id_karyawan']))->result(),
	// 			'jabatan'=>$jbt,
	// 			'loker'=>$lok,
	// 			'hasil'=>$cek,
	// 			'nmtb'=>$nmtb,
	// 			'agd'=>$agd,
	// 			'idk'=>$id,
	// 		);
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/view_raport_output',$data);
	// 		$this->load->view('user_tem/footerx');
	// 	}
		
	// }
	// public function list_raport_sikap()
	// {
	// 	$cek=$this->model_agenda->log_attd_agenda();
	// 	if (count($cek) > 0) {
	// 		$lst=array();
	// 		foreach ($cek as $c) {
	// 			if ($c->tabel_agenda != "") {
	// 				$tb=$this->db->get_where($c->tabel_agenda,array('id_karyawan'=>$this->admin))->num_rows();
	// 				if ($tb > 0) {
	// 					array_push($lst, $c->kode_agenda);
	// 				}
	// 			}
	// 		}
	// 		if (count($lst) > 0) {
	// 			$lst1=array_filter($lst);
	// 		}else{
	// 			$lst1=NULL;
	// 		}
	// 	}else{
	// 		$lst1=NULL;
	// 	}
		
	// 	$data=array(
	// 		'agd'=>$lst1,
	// 		'tgl'=>$this->date,
	// 		'jabatan'=>$this->dtroot['adm']['jabatan'],
	// 	);
	// 	$this->load->view('user_tem/headerx',$this->dtroot);
	// 	$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 	$this->load->view('user_pages/list_raport_sikap',$data);
	// 	$this->load->view('user_tem/footerx');
	// }
	// public function view_raport_sikap()
	// {
	// 	$id=$this->admin;
	// 	$nmtb=$this->uri->segment(3);
	// 	if ($nmtb == "" || $id == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('kpages/list_raport_sikap');
	// 	}else{
	// 		$dti=$this->db->get_where($nmtb,array('id_karyawan'=>$id))->result();
	// 		$dt=$this->db->get_where('log_attd_agenda',array('tabel_agenda'=>$nmtb))->row_array();
	// 		if (count($dti) == 0) {
	// 			$this->messages->notValidParam();  
	// 			redirect('kpages/list_raport_sikap');
	// 		}
	// 		$n=1;
	// 		$kr=$this->model_karyawan->emp($id);
	// 		foreach ($dti as $d) {
	// 			$jabatan[$d->jabatan]=$d->jabatan;
	// 			$loker[$d->loker]=$d->loker;
	// 			$kode_k[$d->kode_aspek]=$d->kode_aspek;
	// 			$rt_a[$d->kode_aspek][$n]=$d->rata_ats;
	// 			$rt_b[$d->kode_aspek][$n]=$d->rata_bwh;
	// 			$rt_r[$d->kode_aspek][$n]=$d->rata_rkn;
	// 			$nilai_d[$d->kode_aspek][$n]=$d->nilai_dri;
	// 			$bobot[$d->kode_aspek][$n]=$d->bobot;
	// 			$b_ats[$d->bobot_ats]=$d->bobot_ats;
	// 			$b_bwh[$d->bobot_bwh]=$d->bobot_bwh;
	// 			$b_rkn[$d->bobot_rkn]=$d->bobot_rkn;
	// 			$part[$d->partisipan]=$d->partisipan;
	// 			$n++;
	// 		}
	// 		foreach ($part as $p) {
	// 			$p1=explode(';', $p);
	// 			foreach ($p1 as $a) {
	// 				$a1=explode(':', $a);
	// 				if ($a1[0] != "DRI") {
	// 					$a2[]=$a1[0];
	// 				}
	// 			}
	// 		}
	// 		$pp1=array_unique(array_filter($a2));
	// 		array_push($pp1, "DRI");
	// 		$pp=implode(';', $pp1);	
	// 		$data=array(
	// 			'profile'=>$kr,
	// 			'id'=>$id,
	// 			'agd'=>$dt,
	// 			'part'=>$pp,
	// 			'bobot'=>$bobot,
	// 			'jabatan'=>implode('', $jabatan),
	// 			'loker'=>implode('', $loker),
	// 			'asp'=>$kode_k,
	// 			'n_dri'=>$nilai_d,
	// 			'rt_ats'=>$rt_a,
	// 			'rt_bwh'=>$rt_b,
	// 			'rt_rkn'=>$rt_r,
	// 			'b_ats'=>implode('', $b_ats),
	// 			'b_bwh'=>implode('', $b_bwh),
	// 			'b_rkn'=>implode('', $b_rkn),
	// 		);
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/view_raport_sikap',$data);
	// 		$this->load->view('user_tem/footerx');
	// 	}
	// }
	// public function raport_bawahan()
	// {
	// 	$bawahan=$this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
	// 	if (!empty($bawahan) || isset($bawahan)) {
	// 		$data=['bawahan'=>$bawahan];
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/raport_bawahan',$data);
	// 		$this->load->view('user_tem/footerx');
	// 	}else{
	// 		redirect('not_found');
	// 	}
	// }
	// public function raport_bawahan_output()
	// {
	// 	$bawahan=$this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
	// 	if (!empty($bawahan) || isset($bawahan)) {
	// 		$data=['bawahan'=>$bawahan];
	// 		$this->load->view('user_tem/headerx',$this->dtroot);
	// 		$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 		$this->load->view('user_pages/raport_bawahan_output',$data);
	// 		$this->load->view('user_tem/footerx');
	// 	}else{
	// 		redirect('not_found');
	// 	}
	// }
	// public function list_raport_group()
	// {
	// 	$cek=$this->model_agenda->log_attd_agenda();
	// 	$cek1=$this->model_agenda->log_agenda();
	// 	if (count($cek) > 0) {
	// 		$lst=array();
	// 		foreach ($cek as $c) {
	// 			if ($c->tabel_agenda != "") {
	// 				$tb=$this->db->get_where($c->tabel_agenda,array('id_karyawan'=>$this->admin))->num_rows();
	// 				if ($tb > 0) {
	// 					array_push($lst, $c->kode_agenda);
	// 				}
	// 			}
	// 		}
	// 		if (count($lst) > 0) {
	// 			$lst1=array_filter($lst);
	// 		}else{
	// 			$lst1=NULL;
	// 		}
	// 	}else{
	// 		$lst1=NULL;
	// 	}
	// 	if (count($cek1) > 0) {
	// 		$lstx=array();
	// 		foreach ($cek1 as $c) {
	// 			if ($c->tabel_agenda != "") {
	// 				$tb=$this->db->get_where($c->tabel_agenda,array('id_karyawan'=>$this->admin))->num_rows();
	// 				if ($tb > 0) {
	// 					array_push($lstx, $c->kode_agenda);
	// 				}
	// 			}
	// 		}
	// 		if (count($lstx) > 0) {
	// 			$lstx1=array_filter($lstx);
	// 		}else{
	// 			$lstx1=NULL;
	// 		}
	// 	}else{
	// 		$lstx1=NULL;
	// 	}
	// 	$agd=array();
	// 	if ($lst1 != NULL) {
	// 		foreach ($lst1 as $k) {
	// 			$ag=$this->db->get_where('log_attd_agenda',array('kode_agenda'=>$k))->row_array();
	// 			$ss=array('semester'=>$ag['semester'],'tahun'=>$ag['tahun']);
	// 			if (!in_array($ss, $agd)) {
	// 				array_push($agd, $ss);
	// 			}
				
	// 		}
	// 	}
	// 	if ($lstx1 != NULL) {
	// 		foreach ($lstx1 as $kx) {
	// 			$agx=$this->db->get_where('log_agenda',array('kode_agenda'=>$kx))->row_array();
	// 			$ssx=array('semester'=>$agx['semester'],'tahun'=>$agx['tahun']);
	// 			if (!in_array($ssx, $agd)) {
	// 				array_push($agd, $ssx);
	// 			}
	// 		}
	// 	}
	// 	$data=array(
	// 		'agd'=>$agd,
	// 		'nama'=>'Raport Hasil Akhir Penilaian Kinerja',
	// 	);

	// 	$this->load->view('user_tem/headerx',$this->dtroot);
	// 	$this->load->view('user_tem/sidebarx',$this->dtroot);
	// 	$this->load->view('user_pages/list_raport_group',$data);
	// 	$this->load->view('user_tem/footerx');
	// }
	public function view_raport_group()
	{
		$th=$this->uri->segment(3);
		$smt=$this->uri->segment(4);
		if ($th == "" || $smt == "") {
			$this->messages->notValidParam();  
			redirect('kpages/list_raport_group');
		}else{
			$cek=$this->db->get_where('log_attd_agenda',array('tahun'=>$th,'semester'=>$smt))->result();
			$cek1=$this->db->get_where('log_agenda',array('tahun'=>$th,'semester'=>$smt))->result();
			if (count($cek) > 0) {
				$lst=array();
				foreach ($cek as $c) {
					if ($c->tabel_agenda != "") {
						$tb=$this->db->get_where($c->tabel_agenda,array('id_karyawan'=>$this->admin))->num_rows();
						if ($tb > 0) {
							array_push($lst, $c->tabel_agenda);
						}
					}
				}
				if (count($lst) > 0) {
					$lst1=array_filter($lst);
				}else{
					$lst1=NULL;
				}
			}else{
				$lst1=NULL;
			}
			if (count($cek1) > 0) {
				$lstx=array();
				foreach ($cek1 as $c) {
					if ($c->tabel_agenda != "") {
						$tb=$this->db->get_where($c->tabel_agenda,array('id_karyawan'=>$this->admin))->num_rows();
						if ($tb > 0) {
							array_push($lstx, $c->tabel_agenda);
						}
					}
				}
				if (count($lstx) > 0) {
					$lstx1=array_filter($lstx);
				}else{
					$lstx1=NULL;
				}
			}else{
				$lstx1=NULL;
			}
			if (count($lst1) == 0 && count($lstx1) == 0) {
				$this->messages->notValidParam();  
				redirect('kpages/list_raport_group');
			}else{
				//sikap
				if(isset($lst1)){
				foreach ($lst1 as $s) {
					$das=$this->db->get_where($s,array('id_karyawan'=>$this->admin))->result();
					foreach ($das as $d) {
						$kari[$d->id_karyawan]=$d->id_karyawan;
						$k_kuis[$d->id_karyawan][$d->kode_kuisioner]=$d->kode_kuisioner;
						$part[$d->id_karyawan][$d->partisipan]=$d->partisipan;
						$aspek[$d->id_karyawan][$d->kode_aspek]=$d->kode_aspek;
						$nilai_ats[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_ats;
						$nilai_bwh[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_bwh;
						$nilai_rkn[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_rkn;
						$nilai_dri[$d->id_karyawan][$d->kode_kuisioner]=$d->nilai_dri;
						$r_ats[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_ats;
						$r_bwh[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_bwh;
						$r_rkn[$d->id_karyawan][$d->kode_aspek][$d->kode_kuisioner]=$d->rata_rkn;
						$bobot[$d->id_karyawan][$d->kode_aspek]=$d->bobot;
						$b_ats[$d->id_karyawan][$d->bobot_ats]=$d->bobot_ats;
						$b_bwh[$d->id_karyawan][$d->bobot_bwh]=$d->bobot_bwh;
						$b_rkn[$d->id_karyawan][$d->bobot_rkn]=$d->bobot_rkn;
						$nama[$d->id_karyawan]=$d->nama;
						$jabatan[$d->id_karyawan]=$d->jabatan;
						$id_jabatan[$d->id_karyawan]=$d->id_jabatan;
						$loker[$d->id_karyawan]=$d->loker;
						$i_loker[$d->id_karyawan]=$d->id_loker;
						$nik[$d->id_karyawan]=$d->nik;
						$kalibrasi[$d->id_karyawan]=$d->nilai_kalibrasi;
					}
						foreach ($r_bwh as $kb => $vb) {
							foreach ($nilai_bwh[$kb] as $kbw1) {
								if ($kbw1 != NULL) {
									$kbw[$kb]=array_filter(explode(';', $kbw1));
									$nsb=1;
									foreach ($kbw[$kb] as $kbbw1) {
										$kbbw[$kb]=explode(':', $kbbw1);
										$sbw[$kb][$nsb]=$kbbw[$kb][0];
										$nsb++;
									}
								}
							}
							foreach ($aspek[$kb] as $asb) {
								$nb=1;
								foreach ($vb[$asb] as $kab) {
									if ($kab != 0) {
										$bw[$kb][$asb][$nb]=$kab;
									}
									$nb++;
								}
								if (isset($bw[$kb][$asb])) {
									$nbw[$kb][$asb]=(array_sum($bw[$kb][$asb])/count($bw[$kb][$asb]))*($bobot[$kb][$asb]/100);	
								}else{
									$nbw[$kb][$asb]=0;
								}
							}
							$bbwh[$kb]=implode('', $b_bwh[$kb])/100;
							$nabw[$kb]=array_sum($nbw[$kb])*$bbwh[$kb];
						}
						foreach ($r_ats as $ka => $va) {
							foreach ($nilai_ats[$ka] as $kat1) {
								if ($kat1 != NULL) {
									$kat[$ka]=array_filter(explode(';', $kat1));
									$nsa=1;
									foreach ($kat[$ka] as $kbat1) {
										$kbat[$ka]=explode(':', $kbat1);
										$sat[$ka][$nsa]=$kbat[$ka][0];
										$nsa++;
									}
									//print_r($kat[$ka]);
								}
							}
							foreach ($aspek[$ka] as $asa) {
								$nar=1;
								foreach ($va[$asa] as $kaa) {
									if ($kaa != 0) {
										$ataa[$ka][$asa][$nar]=$kaa;
									}
									$nar++;
								}
								if (isset($ataa[$ka][$asa])) {
									$nat[$ka][$asa]=(array_sum($ataa[$ka][$asa])/count($ataa[$ka][$asa]))*($bobot[$ka][$asa]/100);	
								}else{
									$nat[$ka][$asa]=0;
								}
							}
							$bats[$ka]=implode('', $b_ats[$ka])/100;
							$naat[$ka]=array_sum($nat[$ka])*$bats[$ka];
						}
						
						foreach ($r_rkn as $kr => $vr) {
							foreach ($nilai_dri[$kr] as $kdr1) {
								if ($kdr1 != 0) {
									$sdr[$kr]=$kr;
								}
							}

							foreach ($nilai_rkn[$kr] as $krk1) {
								if ($krk1 != NULL) {
									$krk[$kr]=array_filter(explode(';', $krk1));
									$nsrk=1;
									foreach ($krk[$kr] as $kbrk1) {
										$kbrk[$kr]=explode(':', $kbrk1);
										$srk[$kr][$nsrk]=$kbrk[$kr][0];
										$nsrk++; 
									}
								}
							}
							foreach ($aspek[$kr] as $asr) {
								$nr=1;
								foreach ($vr[$asr] as $kar) {
									if ($kar != 0) {
										$rk[$kr][$asr][$nr]=$kar;
									}
									$nr++;
								}
								if (isset($rk[$kr][$asr])) {
									$nrk[$kr][$asr]=(array_sum($rk[$kr][$asr])/count($rk[$kr][$asr]))*($bobot[$kr][$asr]/100);	
								}else{
									$nrk[$kr][$asr]=0;
								}
							}
							$brkn[$kr]=implode('', $b_rkn[$kr])/100;
							$nark[$kr]=array_sum($nrk[$kr])*$brkn[$kr];
						}
						foreach ($kari as $kp) {
							$agda=$this->db->get_where('attd_agenda',array('tabel_agenda'=>$s))->row_array();
							$jbb=$this->db->get_where('master_jabatan',array('id_jabatan'=>$id_jabatan[$kp]))->row_array();
							$lkr=$this->db->get_where('master_loker',array('id_loker'=>$i_loker[$kp]))->row_array();
							$bag=$this->db->get_where('master_kategori_jabatan',array('kode_kategori'=>$jbb['kode_kategori']))->row_array();
							$nla[$kp]=array('validasi_s'=>$agda['validasi'],'nilai_kalibrasi'=>$kalibrasi[$kp],'nilai_sikap'=>$naat[$kp]+$nark[$kp]+$nabw[$kp],'nama'=>$nama[$kp],'jabatan'=>$jabatan[$kp],'bagian'=>$bag['nama_kategori'],'kode_bagian'=>$jbb['kode_kategori'],'loker'=>$loker[$kp],'kode_loker'=>$lkr['kode_loker'],'nik'=>$nik[$kp],'id_jabatan'=>$id_jabatan[$kp]);
						}
				}}
				//output
				if (isset($lstx1)) {
				foreach ($lstx1 as $o) {
					$daso=$this->db->get_where($o,array('id_karyawan'=>$this->admin))->result();
					foreach ($daso as $os) {
						$idk1[$os->id_karyawan]=$os->id_karyawan; 
						$jabatan[$os->id_karyawan]=array('jabatan'=>$os->jabatan);
						$loker[$os->id_karyawan]=array('loker'=>$os->loker);
						$id_jabatan[$os->id_karyawan]=array('id_jabatan'=>$os->id_jabatan);
						$id_jabatan[$os->id_karyawan][$os->id_jabatan]=$os->id_jabatan;
					}
					if (isset($idk1)) {
						foreach ($idk1 as $k) {
							$ky=$this->db->query("SELECT nik,nama,jabatan FROM karyawan WHERE id_karyawan = '$k'")->row_array();
							$dat=$this->db->get_where($o,array('id_karyawan'=>$k))->result();
							$nilai_o[$k]=array();
							$nx=1;
							foreach ($dat as $d1) {
								$jbt=$this->model_master->k_jabatan($ky['jabatan']);
								if ($jbt['kode_periode'] == 'BLN') {
									$nax[$nx]=array($d1->na1,$d1->na2,$d1->na3,$d1->na4,$d1->na5,$d1->na6);
									$avg_nx[$nx]=array_sum($nax[$nx])/count($nax[$nx]);
								}else{
									$nax[$nx]=array($d1->na6);
									$avg_nx[$nx]=array_sum($nax[$nx])/count($nax[$nx]);
								}
								array_push($nilai_o[$k], $avg_nx[$nx]);
								$nilai_t[$k]=array('target'=>$d1->nilai_tc);
								$nx++;
							}
							$datax[$k]=array(
								'nik'=>$ky['nik'],
								'nama'=>$ky['nama'],
								'jabatan'=>$jabatan[$k]['jabatan'],
								'id_jabatan'=>$id_jabatan[$k]['id_jabatan'],
								'loker'=>$loker[$k]['loker'],
								'nilai_sikap'=>0,
							);
							$agdax=$this->db->get_where('agenda',array('tabel_agenda'=>$o))->row_array();
							$nlb[$k]=array('validasi_o'=>$agdax['validasi'],'nilai_target'=>array_sum($nilai_o[$k]),'nilai_corp'=>$nilai_t[$k]['target']);
						}
					}
				}}
				$resx = array();
				if (isset($nla)) {
					foreach($nla as $kpx => $vpx){
						$resx[$kpx]=$vpx;

						if (isset($nlb[$kpx])) {
					   	 	$resx[$kpx] = array_merge($nla[$kpx],$nlb[$kpx]);
						}
					}
				}else{
					if (isset($nlb) && !isset($nla)) {
						foreach($nlb as $kpx => $vpx){
							$resx[$kpx]=$vpx;

							if (isset($datax[$kpx])) {
						   	 	$resx[$kpx] = array_merge($datax[$kpx],$nlb[$kpx]);
							}
						}
					}
					
				}
				foreach ($resx as $kr => $vr) {
					$idjb=$vr['id_jabatan'];
					$jbb=$this->db->query("SELECT kode_level FROM master_jabatan WHERE id_jabatan = '$idjb'")->row_array();
					$lvl=$jbb['kode_level'];
					if ($smt != 0) {
						$dbt=$this->db->query("SELECT * FROM bobot_agenda WHERE tahun = '$th' AND semester = '$smt'")->result();
					}else{
						$dbt=$this->db->query("SELECT * FROM bobot_agenda WHERE tahun = '$th'")->result();
					}
					$tbb[$kr]=array();
					foreach ($dbt as $bt) {
						$nbtb=$bt->nama_tabel;
						$dtbl=$this->db->get_where($nbtb,array('kode_level'=>$lvl))->row_array();
						array_push($tbb[$kr], $dtbl);
					}
					if (isset($tbb[$kr])) {
						$jmb=count($tbb[$kr]);
						for ($ix=0; $ix <$jmb ; $ix++) { 
							$b_o[$kr][$ix]=$tbb[$kr][$ix]['bobot_out'];
							$b_s[$kr][$ix]=$tbb[$kr][$ix]['bobot_skp'];
							$b_t[$kr][$ix]=$tbb[$kr][$ix]['bobot_tc'];
						}
						if (isset($b_o[$kr])) {
							$rbo[$kr]=array_sum($b_o[$kr])/count($b_o[$kr]);
						}else{
							$rbo[$kr]=0;
						}
						if (isset($b_s[$kr])) {
							$rbs[$kr]=array_sum($b_s[$kr])/count($b_s[$kr]);
						}else{
							$rbs[$kr]=0;
						}
						if (isset($b_t[$kr])) {
							$rbt[$kr]=array_sum($b_t[$kr])/count($b_t[$kr]);
						}else{
							$rbt[$kr]=0;
						}
						$data_b[$kr]=array('bobot_out'=>$rbo[$kr],'bobot_skp'=>$rbs[$kr],'bobot_tc'=>$rbt[$kr]);
					}else{
						$data_b[$kr]=array('bobot_out'=>0,'bobot_skp'=>0,'bobot_tc'=>0);
					}
				}

				if (isset($data_b)) {
					$data_b=$data_b;
				}else{
					$data_b=NULL;
				}
				if (count($resx) == 0) {
					$this->messages->notValidParam();  
					redirect('kpages/list_raport_group');
				}else{
					$data=array('att'=>$resx,'smtr'=>$smt,'thn'=>$th,'bobot'=>$data_b,'id'=>$this->admin,'foto'=>$this->dtroot['adm']['foto'],'kelamin'=>$this->dtroot['adm']['kelamin'],'email'=>$this->dtroot['adm']['email'],'nik'=>$this->dtroot['adm']['nik'],'tgl_masuk'=>$this->dtroot['adm']['masuk'],'periode'=>'Tahun '.$th.' Semester '.$smt);
					$this->load->view('user_tem/headerx',$this->dtroot);
					$this->load->view('user_tem/sidebarx',$this->dtroot);
					$this->load->view('user_pages/view_raport_group',$data);
					$this->load->view('user_tem/footerx');
				}
			}
		}
	}
	public function task_to_follow(){
		$data=[	
			'profile'=>$this->model_karyawan->getEmployeeId($this->admin),
		];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('self_learning/user/task_to_follow', $data);
		$this->load->view('user_tem/footerx');
	}
	public function riwayat_learning(){
		$data=[	
			'profile'=>$this->model_karyawan->getEmployeeId($this->admin),
		];
		$this->load->view('user_tem/headerx',$this->dtroot);
		$this->load->view('user_tem/sidebarx',$this->dtroot);
		$this->load->view('self_learning/user/riwayat_learning', $data);
		$this->load->view('user_tem/footerx');
	}
}
