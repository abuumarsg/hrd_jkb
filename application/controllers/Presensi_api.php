<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use RestServer\RestController;
use RestServer\Format;
require(APPPATH . 'libraries/RestController.php');
require(APPPATH . 'libraries/Format.php');

class Presensi_api extends RestController
{
    function __construct()
    {
        parent::__construct();
    }
    public function index_get()
    {
        $data=$this->model_presensi->getListPresensiPlainApi(100);
        if($data){
            $this->apimessages->good_data($data);
        }else{
            $this->apimessages->not_found();
        }
    }
    public function index_post()
    {
        $data_api=$this->post('data');
        $data_api=json_decode($data_api,true);
        $data_client=[];
        $data=[];
        $datax=[];
        $date=[];
        $data_kar=[];
        $start_time = microtime(true);
        if ($data_api) {
            foreach ($data_api as $key => $value) {
                $data_client[$value['id_karyawan']][$value['tanggal']][]=$value;
                $data_kar[] = $value['id_karyawan'];
                foreach ($value as $k_v => $v) {
                    if ($k_v == 'tanggal') {
                        $date[$v]=$v;
                    }
                    if ($k_v == 'id_temporari') {
                        $this->model_global->updateQueryNoMsg(['sync'=>1],'temporari_data_presensi',['id_temporari'=>$v]);
                    }
                }
            }
            if ($date && $data_client) {
                $this->model_global->updateQueryNoMsg(['value_bool'=>1],'general_settings',['kode'=>'SYNC']);
                $start=min($date);
                $end=max($date);
                $emp_all=$this->model_karyawan->getListKaryawan();
                foreach ($emp_all as $d_emp) {
                    $current_date=$start;
                    while ($current_date <= $end)
                    {
                        $data[$current_date][$d_emp->id_karyawan]['id_karyawan']=$d_emp->id_karyawan;
                        $data[$current_date][$d_emp->id_karyawan]['tanggal']=$current_date;
                        $libur = $this->model_master->cekHariLiburDate($current_date);
                        if (isset($libur)) {
                            $data[$current_date][$d_emp->id_karyawan]['kode_hari_libur']=(!empty($libur['kode_hari_libur'])?$libur['kode_hari_libur']:null);
                        }else{
                            $data[$current_date][$d_emp->id_karyawan]['kode_hari_libur']=null;
                        }
                        $izin = $this->model_presensi->cekIzinCutiIdDate($data[$current_date][$d_emp->id_karyawan]['id_karyawan'],$current_date);
                        if (isset($izin)) {
                            $data[$current_date][$d_emp->id_karyawan]['kode_ijin']=(!empty($izin['kode_izin_cuti'])?$izin['kode_izin_cuti']:null);
                        }else{
                            $data[$current_date][$d_emp->id_karyawan]['kode_ijin']=null;
                        }
                        $perdin = $this->model_karyawan->cekDataPerDinPresensi($data[$current_date][$d_emp->id_karyawan]['id_karyawan'],$current_date);
                        if (isset($perdin) && !empty($perdin)) {
                            $data[$current_date][$d_emp->id_karyawan]['no_perjalanan_dinas']=$perdin['no_sk'];
                        }else{
                            $data[$current_date][$d_emp->id_karyawan]['no_perjalanan_dinas']=null;
                        }
                        $cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($d_emp->id_karyawan,$current_date);
					    $lembur = $this->model_presensi->checkLemburSyncIDDate($data[$current_date][$d_emp->id_karyawan]['id_karyawan'],$current_date);
                        if (isset($lembur) && !empty($lembur)) {
                            $data[$current_date][$d_emp->id_karyawan]['no_spl']=$lembur['no_lembur'];
                            // $cekjadwal['jam_selesai']=$lembur['jam_selesai'];
                            $cekjadwalLong['jam_selesai']=$lembur['jam_selesai'];
                        }
                        $data[$current_date][$d_emp->id_karyawan]['kode_shift'] = $cekjadwal['kode_shift'];
                        if (isset($data_client[$d_emp->id_karyawan])) {
                            if (isset($data_client[$d_emp->id_karyawan][$current_date])) {
                                $data_log=$data_client[$d_emp->id_karyawan][$current_date];
                                foreach ($data_log as $d) {                                    
                                    $lama_lembur_first = $this->formatter->convertDecimaltoJam(10);
                                    $lama_lembur = $this->formatter->convertDecimaltoJam(16);
                                    $cek_lembur_first = $this->model_presensi->cekLemburIdDateNew($data[$current_date][$d_emp->id_karyawan]['id_karyawan'],$current_date,['validasi'=>1,'val_jumlah_lembur >='=>$lama_lembur_first,'val_jumlah_lembur <'=>$lama_lembur]);
                                    $cek_lembur = $this->model_presensi->cekLemburIdDateNew($data[$current_date][$d_emp->id_karyawan]['id_karyawan'],$current_date,['validasi'=>1,'val_jumlah_lembur >='=>$lama_lembur]);
                                    if(!empty($cek_lembur_first)){
                                        $cekjadwal2 = $this->model_presensi->cekJadwalKerjaIdDateJKB($d_emp->id_karyawan,$current_date);
                                        $data_jamx=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal2['kode_shift'],'tanggal'=>$current_date,'jam'=>$d['jam'],'jadwal'=>$cekjadwal2,'id_karyawan'=>$d_emp->id_karyawan]);
                                        $tgl_mulai = $cek_lembur_first['tgl_mulai'];
                                        $tgl_selesai = $cek_lembur_first['tgl_selesai'];
                                        while ($tgl_mulai <= $tgl_selesai)
                                        {
                                            $datay['id_karyawan'] = $cek_lembur_first['id_karyawan'];
                                            $datay['tanggal'] = $tgl_mulai;
                                            if(!empty($data_jamx)){
                                                if($tgl_mulai == $cek_lembur_first['tgl_mulai'] && $data_jamx['tanggal'] == $tgl_mulai){
                                                    $data_jam_mulai = null;
                                                    if (isset($data_jamx['jam_mulai'])) {
                                                        $data_jam_mulai=$data_jamx['jam_mulai'];
                                                    }
                                                    $data_jam_selesai = null;
                                                    if (isset($data_jamx['jam_selesai'])) {
                                                        $data_jam_selesai=$data_jamx['jam_selesai'];
                                                    }else{
                                                        $data_jam_selesai=$cekjadwalLong['jam_selesai'];
                                                    }
                                                    $datay['jam_mulai'] = $data_jam_mulai;
                                                    $datay['jam_selesai'] = $data_jam_selesai;
                                                    if(empty($datay['jam_mulai'])){
                                                        unset($datay['jam_mulai']);
                                                    }
                                                    if(empty($datay['jam_selesai'])){
                                                        unset($datay['jam_selesai']);
                                                    }
                                                    $datay['no_spl'] = $cek_lembur_first['no_lembur'];
                                                    $datay['kode_shift'] = $cekjadwal2['kode_shift'];
                                                    if (!empty($datay['id_karyawan']) && !empty($datay['tanggal']) && !empty($datay['kode_shift'])) {
                                                        $cek=$this->model_karyawan->checkPresensiDate($datay['id_karyawan'],$datay['tanggal']);
                                                        if (!$cek) {
                                                            $datay=array_merge($datay,$this->model_global->getCreateProperties(1));
                                                            $datax=$this->model_global->insertQuery($datay,'data_presensi');
                                                        }else{
                                                            $datay=array_merge($datay,$this->model_global->getUpdateProperties(1));
                                                            $datax=$this->model_global->updateQuery($datay,'data_presensi',['tanggal'=>$datay['tanggal'],'id_karyawan'=>$datay['id_karyawan']]);
                                                        }
                                                    }
                                                }elseif($tgl_mulai == $cek_lembur_first['tgl_selesai'] && $tgl_mulai != $cek_lembur_first['tgl_mulai']){
                                                    $data_jam_selesai = null;
                                                    if (isset($data_jamx['jam_selesai'])) {
                                                        $data_jam_selesai=$data_jamx['jam_selesai'];
                                                    }
                                                    if(empty($datay['jam_mulai'])){
                                                        unset($datay['jam_mulai']);
                                                    }
                                                    if(empty($datay['jam_selesai'])){
                                                        unset($datay['jam_selesai']);
                                                    }
                                                    $datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
                                                    $datay['jam_selesai'] = null;
                                                    $datay['no_spl'] = null;
                                                    $datay['kode_shift'] = $cekjadwal2['kode_shift'];
                                                    if (!empty($datay['id_karyawan']) && !empty($datay['tanggal']) && !empty($datay['kode_shift'])) {
                                                        $cek=$this->model_karyawan->checkPresensiDate($datay['id_karyawan'],$datay['tanggal']);
                                                        if (!$cek) {
                                                            $datay=array_merge($datay,$this->model_global->getCreateProperties(1));
                                                            $datax=$this->model_global->insertQuery($datay,'data_presensi');
                                                        }else{
                                                            $datay=array_merge($datay,$this->model_global->getUpdateProperties(1));
                                                            $datax=$this->model_global->updateQuery($datay,'data_presensi',['tanggal'=>$datay['tanggal'],'id_karyawan'=>$datay['id_karyawan']]);
                                                        }
                                                    }
                                                }
                                            }
                                            $tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
                                        }
                                    }elseif(!empty($cek_lembur)){
                                        $cekjadwal2 = $this->model_presensi->cekJadwalKerjaIdDateJKB($d_emp->id_karyawan,$current_date);
                                        $data_jamx=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal2['kode_shift'],'tanggal'=>$current_date,'jam'=>$d['jam'],'jadwal'=>$cekjadwal2,'id_karyawan'=>$d_emp->id_karyawan]);
                                        $tgl_mulai = $cek_lembur['tgl_mulai'];
                                        $tgl_selesai = $cek_lembur['tgl_selesai'];
                                        while ($tgl_mulai <= $tgl_selesai)
                                        {
                                            $datay['id_karyawan'] = $cek_lembur['id_karyawan'];
                                            $datay['tanggal'] = $current_date;
                                            if(!empty($data_jamx)){
                                                if($current_date == $cek_lembur['tgl_mulai'] && $current_date != $cek_lembur['tgl_selesai']){
                                                    $data_jam_mulai = null;
                                                    if (isset($data_jamx['jam_mulai'])) {
                                                        $data_jam_mulai=$data_jamx['jam_mulai'];
                                                    }
                                                    $datay['jam_mulai'] = $data_jam_mulai;
                                                    $datay['jam_selesai'] = $cekjadwal2['jam_selesai'];
                                                    $datay['no_spl'] = $cek_lembur['no_lembur'];
                                                }elseif($current_date != $cek_lembur['tgl_mulai'] && $current_date != $cek_lembur['tgl_selesai']){
                                                    $datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
                                                    $datay['jam_selesai'] = $cekjadwal2['jam_selesai'];
                                                }elseif($current_date == $cek_lembur['tgl_selesai'] && $current_date != $cek_lembur['tgl_mulai']){
                                                    $data_jam_selesai = null;
                                                    if (isset($data_jamx['jam_selesai'])) {
                                                        $data_jam_selesai=$data_jamx['jam_selesai'];
                                                    }
                                                    $datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
                                                    $datay['jam_selesai'] = $data_jam_selesai;
                                                }else{
                                                    $data_jam_mulai = null;
                                                    if (isset($data_jamx['jam_mulai'])) {
                                                        $data_jam_mulai=$data_jamx['jam_mulai'];
                                                    }else{
                                                        $data_jam_mulai = $cekjadwal2['jam_mulai'];
                                                    }
                                                    $data_jam_selesai = null;
                                                    if (isset($data_jamx['jam_selesai'])) {
                                                        $data_jam_selesai=$data_jamx['jam_selesai'];
                                                    }
                                                    $datay['jam_mulai'] = $data_jam_mulai;
                                                    $datay['jam_selesai'] = $data_jam_selesai;
                                                }
                                            }
                                            $datay['kode_shift'] = $cekjadwal2['kode_shift'];
                                            $tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
                                            if (!empty($datay['id_karyawan']) && !empty($datay['tanggal']) && !empty($datay['kode_shift'])) {
                                                $cek=$this->model_karyawan->checkPresensiDate($datay['id_karyawan'],$datay['tanggal']);
                                                if (!$cek) {
                                                    $datax=$this->model_global->insertQuery($datay,'data_presensi');
                                                }else{
                                                    $datax=$this->model_global->updateQuery($datay,'data_presensi',['tanggal'=>$datay['tanggal'],'id_karyawan'=>$datay['id_karyawan']]);
                                                }
                                            }
                                        }
                                    }else{
                                        $data_jam=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal['kode_shift'],'tanggal'=>$current_date,'jam'=>$d['jam'],'jadwal'=>$cekjadwal,'id_karyawan'=>$d_emp->id_karyawan]);
                                        if (isset($data_jam['tanggal'])) {
                                            $data[$data_jam['tanggal']][$d_emp->id_karyawan]['id_karyawan']=$d_emp->id_karyawan;
                                            $data[$data_jam['tanggal']][$d_emp->id_karyawan]['tanggal']=$data_jam['tanggal'];
                                            if (isset($data_jam['jam_mulai'])) {
                                                $data[$data_jam['tanggal']][$d_emp->id_karyawan]['jam_mulai']=$data_jam['jam_mulai'];
                                            }
                                            if (isset($data_jam['jam_selesai'])) {
                                                $data[$data_jam['tanggal']][$d_emp->id_karyawan]['jam_selesai']=$data_jam['jam_selesai'];
                                            }
                                            if (isset($data_jam['kode_shift'])) {
                                                $data[$data_jam['tanggal']][$d_emp->id_karyawan]['kode_shift']=$data_jam['kode_shift'];
                                            }
                                        }else{
                                            $data[$current_date][$d_emp->id_karyawan]['id_karyawan']=$d_emp->id_karyawan;
                                            $data[$current_date][$d_emp->id_karyawan]['tanggal']=$current_date;
                                            if (isset($data_jam['jam_mulai'])) {
                                                $data[$current_date][$d_emp->id_karyawan]['jam_mulai']=$data_jam['jam_mulai'];
                                            }
                                            if (isset($data_jam['jam_selesai'])) {
                                                $data[$current_date][$d_emp->id_karyawan]['jam_selesai']=$data_jam['jam_selesai'];
                                            }
                                            if (isset($data_jam['kode_shift'])) {
                                                $data[$current_date][$d_emp->id_karyawan]['kode_shift']=$data_jam['kode_shift'];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
                    }
                }
                if ($data) {
                    foreach ($data as $tanggal => $sub_data) {
                        if ($sub_data) {
                            foreach ($sub_data as $id_karyawan => $d) {
                                $wh=['id_karyawan'=>$id_karyawan,'tanggal'=>$tanggal];
                                if (isset($d['jam_mulai'])) {
                                    $wh['jam_mulai']=$d['jam_mulai'];
                                }
                                if (isset($d['jam_selesai'])) {
                                    $wh['jam_selesai']=$d['jam_selesai'];
                                }
                                $cek=$this->model_presensi->checkPresensiWhere(['id_karyawan'=>$id_karyawan,'tanggal'=>$tanggal]);
                                if (!$cek) {
                                    $data_in=array_merge($d,$this->model_global->getCreateProperties(1));
                                    $this->model_global->insertQueryNoMsg($data_in,'data_presensi'); 
                                }else{
                                    if (isset($wh['jam_mulai']) || isset($wh['jam_selesai'])) {
                                        $data_in=array_merge($d,$this->model_global->getUpdateProperties(1));
                                        $this->model_global->updateQueryNoMsg($data_in,'data_presensi',['tanggal'=>$tanggal,'id_karyawan'=>$id_karyawan]);
                                    }
                                }
                            }
                        }
                    }
                }
            }            
        }
        $end_time = microtime(true); 
        $execution_time = ($end_time - $start_time); 
        $this->model_global->updateQueryNoMsg(['value_bool'=>0],'general_settings',['kode'=>'SYNC']);
        echo " It takes ".$execution_time." seconds to execute the script"; 
    }
	public function delete_log_presensi_get()
    {
		$bulan=6;
        $start_time = microtime(true); 
		$date=date('Y-m-d',strtotime('-'.$bulan.' Month',strtotime($this->otherfunctions->getDateNow('Y-m-d'))));
		$where="tanggal < '$date'";
		$this->model_global->deleteQueryNoMsg('temporari_data_presensi',$where);
		$end_time = microtime(true); 
		$execution_time = ($end_time - $start_time); 
		$data='Success, time processing '.$execution_time.' second';
        $this->apimessages->good_data($data);
    }
}
