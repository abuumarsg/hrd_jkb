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
class Izinsatpam extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		$this->rando = $this->codegenerator->getPin(50,'full');
		$this->conf=$this->otherfunctions->configEmail(); 
	}
	public function index(){
		redirect('not_found');
		// // $this->load->view('admin_tem/header');
		// $this->load->view('utama/presensi_satpam');
		// // $this->load->view('admin_tem/footer');
		// // $data=['data_pre'=>$this->access];
		// // $this->load->view('admin_tem/headerx',$this->dtroot);
		// // $this->load->view('admin_tem/sidebarx',$this->dtroot);
		// // $this->load->view('pages/presensi_satpam',$data);
		// // $this->load->view('admin_tem/footerx');
	}
	public function izin_cuti()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getIzinCutiHarian();
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_izin_cuti,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$vali=$this->model_karyawan->getJumStatusValidasi($d->id_karyawan)['valid'];
					if($vali==0){
						$valid='<label class="label label-success" style="font-size:14px;">Sudah Divalidasi</label>';
					}else{
						$valid='<label class="label label-warning" style="font-size:14px;">'.$vali.' Belum Divalidasi</label>';
					}
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->izinOrCuti);
					$datax['data'][]=[
						$d->id_izin_cuti,
						$d->kode_izin_cuti,
						$d->nama_karyawan.' ('.$d->nik_karyawan.')',
						$d->nama_jabatan,
						$d->nama_bagian,
						'<label class="label label-success" style="font-size:14px;">'.$nama_jenis.'</label>',
						$d->nama_jenis_izin,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai),
						$this->formatter->getDateMonthFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai),
						$valid,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
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
						'loker'=>$d->nama_loker,
						'jabatan'=>$d->nama_jabatan,
						'bagian'=>$d->nama_bagian,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'foto'=> base_url($d->foto),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'nomor'=>$d->kode_izin_cuti,
						'tanggal_mulai'=>$mulai,
						'tanggal_selesai'=>$selesai,
						'tgl_mulai_val'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai),
						'jenis_cuti'=>$d->nama_jenis_izin.' ('.$nama_jenis.')',
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'emenyetujui2'=>$d->menyetujui_2,
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
						'menyetujui2'=>(!empty($d->nama_menyetujui_2)) ? $d->nama_menyetujui_2.$jbt_menyetujui_2:$this->otherfunctions->getMark(),
						'e_alasan'=>$d->alasan,
						'e_skd'=>$d->skd_dibayar,
						'e_keterangan'=>$d->keterangan,
						'e_validasi'=>$d->validasi,
						'nama_jenis_ic'=>$d->jenis_ic,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
}