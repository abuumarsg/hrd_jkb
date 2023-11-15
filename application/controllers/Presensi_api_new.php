<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use RestServer\RestController;
use RestServer\Format;
require(APPPATH . 'libraries/RestController.php');
require(APPPATH . 'libraries/Format.php');


/**
    * Code From GFEACORP.
    * Web Developer
    * @author      Galeh Fatma Eko Ardiansa S.Kom
    * @type        Controller
    * @package     master_api_ci/Presensi
    * @copyright   Copyright (c) 2020 GFEACORP
    * @version     1.0, 04 Aug 2020
    * Email        galeh.fatma@gmail.com
    * Phone        (+62) 85852924304
    * ==========// HAK CIPTA DILINDUNGI! //==========
*/


class Presensi_api extends RestController
{
    function __construct()
    {
        parent::__construct();
    }
    public function index_get()
    {
        $data=$this->model_presensi->getListPresensiPlainApi(70);
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
                $data_l=[];
                $kode_lembur = '';
                foreach ($data_kar as $k_emp => $d_emp) {
                    $current_date=$start;
                    while ($current_date <= $end)
                    {
                        $data[$current_date][$d_emp]['id_karyawan']=$d_emp;
                        $data[$current_date][$d_emp]['tanggal']=$current_date;
                        $libur = $this->model_master->cekHariLiburDate($current_date);
                        if (isset($libur)) {
                            $data[$current_date][$d_emp]['kode_hari_libur']=(!empty($libur['kode_hari_libur'])?$libur['kode_hari_libur']:null);
                        }
                        $izin = $this->model_presensi->cekIzinCutiIdDate($data[$current_date][$d_emp]['id_karyawan'],$current_date);
                        if (isset($izin)) {
                            $data[$current_date][$d_emp]['kode_ijin']=(!empty($izin['kode_izin_cuti'])?$izin['kode_izin_cuti']:null);
                        }
                        $lembur = $this->model_presensi->cekLemburIdDate($data[$current_date][$d_emp]['id_karyawan'],$current_date);
                        if (isset($lembur) && !empty($lembur)) {
                            $data[$current_date][$d_emp]['no_spl']=$lembur['no_lembur'];
                        }
                        $perdin = $this->model_karyawan->cekDataPerDinPresensi($data[$current_date][$d_emp]['id_karyawan'],$current_date);
                        if (isset($perdin) && !empty($perdin)) {
                            $data[$current_date][$d_emp]['no_perjalanan_dinas']=$perdin['no_sk'];
                        }
                        $cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($d_emp,$current_date);
                        $data[$current_date][$d_emp]['kode_shift'] = $cekjadwal['kode_shift'];
                        if (isset($data_client[$d_emp])) {
                            if (isset($data_client[$d_emp][$current_date])) {
                                $data_log=$data_client[$d_emp][$current_date];
                                foreach ($data_log as $d) {
                                    $data_jam=$this->model_presensi->coreImportPresensi_coba(['tanggal'=>$current_date,'jam'=>$d['jam'],'jadwal'=>$cekjadwal,'id_karyawan'=>$d_emp]);						
                                    if (isset($data_jam['tanggal'])) {
                                        $data[$data_jam['tanggal']][$d_emp]['id_karyawan']=$d_emp;
                                        $data[$data_jam['tanggal']][$d_emp]['tanggal']=$data_jam['tanggal'];
                                        if (isset($data_jam['jam_mulai'])) {
                                            $data[$data_jam['tanggal']][$d_emp]['jam_mulai']=$data_jam['jam_mulai'];
                                        }
                                        if (isset($data_jam['jam_selesai'])) {
                                            $data[$data_jam['tanggal']][$d_emp]['jam_selesai']=$data_jam['jam_selesai'];
                                        }
                                        if (isset($data_jam['kode_shift'])) {
                                            $data[$data_jam['tanggal']][$d_emp]['kode_shift']=$data_jam['kode_shift'];
                                        }
                                    }else{
                                        $data[$current_date][$d_emp]['id_karyawan']=$d_emp;
                                        $data[$current_date][$d_emp]['tanggal']=$current_date;
                                        if (isset($data_jam['jam_mulai'])) {
                                            $data[$current_date][$d_emp]['jam_mulai']=$data_jam['jam_mulai'];
                                        }
                                        if (isset($data_jam['jam_selesai'])) {
                                            $data[$current_date][$d_emp]['jam_selesai']=$data_jam['jam_selesai'];
                                        }
                                        if (isset($data_jam['kode_shift'])) {
                                            $data[$current_date][$d_emp]['kode_shift']=$data_jam['kode_shift'];
                                        }
                                    }
                                    $lama_lembur = $this->formatter->convertDecimaltoJam(14);
                                    $cekLembur = $this->model_karyawan->getLembur(null,['a.id_karyawan'=>$d_emp,'a.tgl_mulai'=>$current_date,'a.validasi'=>1,'a.val_jumlah_lembur >='=>$lama_lembur],'row');
                                    if(!empty($cekLembur)){
                                        $jadwal = $this->model_karyawan->cekJadwalKerjaIdDate($d_emp, $current_date);
                                        $kode_lembur .= $cekLembur['no_lembur'];
                                        $tgl_mulai = $cekLembur['tgl_mulai'];
                                        $tgl_selesai = $cekLembur['tgl_selesai'];
                                        while ($tgl_mulai <= $tgl_selesai)
                                        {
                                            $datay['id_karyawan'] = $cekLembur['id_karyawan'];
                                            $datay['no_spl'] = $cekLembur['no_lembur'];
                                            $datay['tanggal'] = $tgl_mulai;
                                            if($tgl_mulai == $cekLembur['tgl_mulai'] && $tgl_mulai != $cekLembur['tgl_selesai']){
                                                $datay['jam_mulai'] = $data_jam['jam_mulai'];
                                                $datay['jam_selesai'] = $jadwal['jam_selesai'];
                                            }elseif($tgl_mulai != $cekLembur['tgl_mulai'] && $tgl_mulai != $cekLembur['tgl_selesai']){
                                                $datay['jam_mulai'] = $jadwal['jam_mulai'];
                                                $datay['jam_selesai'] = $jadwal['jam_selesai'];
                                            }elseif($tgl_mulai == $cekLembur['tgl_selesai'] && $tgl_mulai != $cekLembur['tgl_mulai']){
                                                $datay['jam_mulai'] = $jadwal['jam_mulai'];
                                                $datay['jam_selesai'] = $data_jam['jam_selesai'];
                                            }else{
                                                $datay['jam_mulai'] = $data_jam['jam_mulai'];
                                                $datay['jam_selesai'] = $data_jam['jam_selesai'];
                                            }
                                            $datay['kode_shift'] = $jadwal['kode_shift'];
                                            $tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
                                            array_push($data_l,$datay);
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
                if(!empty($kode_lembur)){
                    foreach ($data_l as $d =>$dd) {
                        $data_pre = [
                            'id_karyawan' => $dd['id_karyawan'],
                            'no_spl' => $dd['no_spl'],
                            'tanggal' => $dd['tanggal'],
                            'jam_mulai' => $dd['jam_mulai'],
                            'jam_selesai' => $dd['jam_selesai'],
                            'kode_shift' => $dd['kode_shift'],
                        ];
                        if (!empty($dd['id_karyawan']) && !empty($dd['tanggal']) && !empty($dd['kode_shift'])) {
                            $cek=$this->model_karyawan->checkPresensiDate($dd['id_karyawan'],$dd['tanggal']);
                            if (!$cek) {
                                $datax=$this->model_global->insertQuery($data_pre,'data_presensi');
                            }else{
                                $datax=$this->model_global->updateQuery($data_pre,'data_presensi',['tanggal'=>$dd['tanggal'],'id_karyawan'=>$dd['id_karyawan']]);
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
}