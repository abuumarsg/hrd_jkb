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

class Coba extends CI_Controller 
{
	public function __construct() 
	{  
		parent::__construct(); 
		$this->date = $this->otherfunctions->getDateNow();
    }
	function cobasendwa(){
		// $no ='085725951044';
		// // $pesan = "Ive came across a similar problem and in the end it was a matter of some old dependencies that were messing up my Heroku server. While at my projects folder Ive run: npm uninstall npm install";
		// $pesan = 'test%20pesan%20masuk';
		// // $data = $this->curl->sendWALocal($no, $pesan);
		// $data = $this->curl->sendWapisender($no, $pesan);
		// echo '<pre>';
        // echo 'das';
		// print_r($data);

		$curl = curl_init();
        print_r($curl);
		curl_setopt_array($curl, array(
			// CURLOPT_URL => 'https://wapisender.id/api/v1/send-message',
			CURLOPT_URL => 'https://wapisender.id/api/v1/send-message?api_key=HgBCfhjwX0un7VFvoypCWe16rSV14EQh&device_key=3kw0py&destination=085725951044&message=test%20pesan%20masuk%2041212414134',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
            // CURLOPT_POSTFIELDS =>'{
            //     "api_key": "HgBCfhjwX0un7VFvoypCWe16rSV14EQh",
            //     "device_key": "3kw0py",
            //     "destination": "085725951044",
            //     "text": "test%20pesan%20masuk%2041212414134"
            // }',
            // CURLOPT_HTTPHEADER => array(
            //     'Content-Type: application/json',
            //     'Cookie: connect.sid=s%3AK4gl0fVm_BYxmwsr22YTBvg380eam3Jg.dGlvdVUXWT%2BkOUnzGCxguLSyH%2FeuI3qgEsrd6%2FEL%2ByU'
            // ),
		));
		$response = curl_exec($curl);
		// curl_close($curl);
        $err = curl_close($curl);        
        if($err){
            echo 'error';
            echo "cURL ERROR : ".$err;
        }else{
            echo 'berhasil';
            print_r($response);
        }
		// echo $response;
        // print_r($response);

		
        // $curl = curl_init();
        // curl_setopt_array(
        //     $curl, array(
        //     CURLOPT_URL => 'https://wapisender.id/api/v1/send-message',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS =>'{
        //         "api_key": "HgBCfhjwX0un7VFvoypCWe16rSV14EQh",
        //         "device_key": "3kw0py",
        //         "destination": "085725951044",
        //         "text": "test%20pesan%20masuk%2041212414134"
        //     }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
        //         'Cookie: connect.sid=s%3AK4gl0fVm_BYxmwsr22YTBvg380eam3Jg.dGlvdVUXWT%2BkOUnzGCxguLSyH%2FeuI3qgEsrd6%2FEL%2ByU'
        //     ),
        // ));
        // echo $curl;
        // $response = curl_exec($curl);
        // curl_close($curl);
        // var_dump($response);

	}
    
	function cobasendwa2(){
        $curl = curl_init();
        $datakirim = [
            'message'=>'silahkan klik tautan berikut untuk memperbarui password anda : https://hrd.jkb.co.id/jkb/auth/lupa terimakasih',
            'number' => '085725951044',
        ];
        curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://waapi.hrd.jkb.co.id/send-message',
          CURLOPT_URL => 'http://localhost:8000/send-message',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $datakirim,
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo '<pre>';
        print_r($response);
    }
    function cobasendwa3(){
        $parameter = [
			'key' => 'HgBCfhjwX0un7VFvoypCWe16rSV14EQh',
			'device' => '3kw0py',
			'destination' => '085725951044',
			'message' => 'ini adalah pesan untuk contoh',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://wapisender.id/api/v1/send-message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, '{
        //         "api_key": "HgBCfhjwX0un7VFvoypCWe16rSV14EQh",
        //         "device_key": "3kw0py",
        //         "destination": "085725951044",
        //         "message": "test%20pesan%20masuk%2041212414134"
        //     }'
        // );
        print_r($ch);
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        print_r($result);
    }
    function cobawaapi(){
        $datakirim = [
            'message'=> 'pesan masuk',
            'number' => '085725951044',
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://waapi.hrd.jkb.co.id/send-message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $datakirim,
        // CURLOPT_POSTFIELDS => array('message' => 'oke','number' => '085725951044'),
        ));
        // CURLOPT_POSTFIELDS => array('message' => 'oke','number' => '085725951044','file_dikirim'=> new CURLFILE('/C:/Users/Nusantara/Documents/Laporan Buku Tamu Tanggal 01-9-2022.pdf')),
        $response = curl_exec($curl);
        curl_close($curl);
        echo '<pre>';
        print_r($response);
    }
    function cobawaapiButton(){
        $datakirim = [
            "message"=> "*Anda Ingin Mereset Password ?*",
            "number" => "085725951044",
            "namaButton"=>"Reset Password",
            "buttonLink"=>"https://hrd.jkb.co.id/",
            "buttonFooter"=>"ikuti tautan berikut untuk mereset password, \nTautan berlaku 60 Menit",
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://wa.hrd.jkb.co.id/send-message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $datakirim,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo '<pre>';
        print_r($response);
    }
    function cobawaapiButtonSaya(){
        $datakirim = [
            "message"=> "*Anda Ingin Mereset Password ?*",
            "number" => "085725951044",
            "namaButton"=>"Reset Password",
            "buttonLink"=>"https://hrd.jkb.co.id/",
            "buttonFooter"=>"ikuti tautan berikut untuk mereset password, \nTautan berlaku 60 Menit",
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://waapi.hrd.jkb.co.id/send-message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $datakirim,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo '<pre>';
        print_r($response);
    }
    function encryptLink(){
        $dty = '{"status":true,"response":{"key":{"remoteJid":"6285725951044@s.whatsapp.net","fromMe":true,"id":"BAE52E4493D4284D"},"message":{"extendedTextMessage":{"text":"Untuk mereset password silahkan klik tautan berikut : https://hrd.jkb.co.id/forget/W63qI63ea012711524","matchedText":"https://hrd.jkb.co.id/forget/W63qI63ea012711524","canonicalUrl":"https://hrd.jkb.co.id/forget/W63qI63ea012711524","description":"HSOFT, Hucle Software, Sistem HRD, HRD Management System, PT. Hucle Indonesia, HSOFT JKB, Hucle Software JKB, Sistem HRD JKB, HRD Management System JKB","title":"W63qi63ea012711524 |  HRD Management System HSOFT ","previewType":"NONE","jpegThumbnail":"nlkmkm"}},"messageTimestamp":"1676280286","status":"PENDING"}}';
        $data = json_decode($dty);
        // $data = json_encode($data);
        echo'<pre>';
        print_r($data);
    }
    function backupPresensi()
    {
        // echo '<pre>';
        // $id_karyawan = '81';
        // $where = 'YEAR(tanggal) = '.$tahun;//.' AND id_karyawan = '.$id_karyawan;
        // $tahun = '2022';
        $start_time = microtime(true);
        $tahunArray = ['2019','2020','2021','2022'];
        foreach ($tahunArray as $key => $tahun) {
            for ($bulan = 1; $bulan < 13 ; $bulan++) {
                $where = 'YEAR(tanggal) = '.$tahun.' AND MONTH(tanggal) = '.$bulan;
                $data = $this->model_presensi->getPresensiWhere2($where);
                if(!empty($data)){
                    foreach ($data as $d) {
                        unset($d->id_p_karyawan);
                        $cek = $this->model_presensi->getLogPresensiWhere(['id_karyawan'=>$d->id_karyawan, 'tanggal'=>$d->tanggal]);
                        if(empty($cek)){
                            $this->model_global->insertQuery($d,'log_data_presensi');
                        }
                        $this->model_global->deleteQuery('data_presensi',['id_karyawan'=>$d->id_karyawan, 'tanggal'=>$d->tanggal]);
                    }
                }
                sleep(3);
            }
            sleep(4);
        }
        $end_time = microtime(true); 
        $execution_time = ($end_time - $start_time);
        echo " It takes ".$execution_time." seconds to execute the script"; 
    }
    function backupLogAbsen()
    {
        $start_time = microtime(true);
        $tahunArray = ['2019','2020','2021','2022'];
        foreach ($tahunArray as $key => $tahun) {
            for ($bulan = 1; $bulan < 13 ; $bulan++) {
                $where = 'YEAR(tanggal) = '.$tahun.' AND MONTH(tanggal) = '.$bulan;
                $data = $this->model_presensi->getTemporariAbsenWhere($where);
                if(!empty($data)){
                    foreach ($data as $d) {
                        unset($d->id_temporari);
                        $cek = $this->model_presensi->getLogTemporariAbsenWhere(['id_karyawan'=>$d->id_karyawan, 'tanggal'=>$d->tanggal, 'jam'=>$d->jam]);
                        if(empty($cek)){
                            $this->model_global->insertQuery($d,'log_temporari_data_presensi');
                        }
                        $this->model_global->deleteQuery('temporari_data_presensi',['id_karyawan'=>$d->id_karyawan, 'tanggal'=>$d->tanggal, 'jam'=>$d->jam]);
                    }
                }
                sleep(3);
            }
            sleep(4);
        }
        $end_time = microtime(true); 
        $execution_time = ($end_time - $start_time);
        echo " It takes ".$execution_time." seconds to execute the script"; 
    }
    function backupLogLembur()
    {
        $start_time = microtime(true);
        $tahunArray = ['2019','2020','2021','2022'];
        foreach ($tahunArray as $key => $tahun) {
            for ($bulan = 1; $bulan < 13 ; $bulan++) {
                $where = 'YEAR(tgl_mulai) = '.$tahun.' AND MONTH(tgl_mulai) = '.$bulan;
                $data = $this->model_presensi->getDataTableWhere('data_lembur', $where);
                if(!empty($data)){
                    foreach ($data as $d) {
                        unset($d->id_data_lembur);
                        $cek = $this->model_presensi->getDataTableWhere('log_data_lembur', ['id_karyawan'=>$d->id_karyawan, 'tgl_mulai'=>$d->tgl_mulai, 'no_lembur'=>$d->no_lembur]);
                        if(empty($cek)){
                            $this->model_global->insertQuery($d,'log_data_lembur');
                        }
                        $this->model_global->deleteQuery('data_lembur',['id_karyawan'=>$d->id_karyawan, 'tgl_mulai'=>$d->tgl_mulai, 'no_lembur'=>$d->no_lembur]);
                    }
                }
                sleep(3);
            }
            sleep(4);
        }
        $end_time = microtime(true); 
        $execution_time = ($end_time - $start_time);
        echo " It takes ".$execution_time." seconds to execute the script"; 
    }
}