<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Curl{
    
    // public function __construct()
    // {
    //     // $this->CI =& get_instance();
    // }

    // public function index()
    // {
    //     $this->redirect('not_found');
    // }
    
    public function sendWA($tujuan, $pesan){
        $curl = curl_init();
        curl_setopt_array(
            $curl, array(
            CURLOPT_URL => 'https://wa.hrd.jkb.co.id/api/chat/send_text_message',
            // CURLOPT_URL => 'http://localhost:3000/api/chat/send_text_message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
                // "device_id": "8186137819",
            CURLOPT_POSTFIELDS =>'{
                "api_key": "2098466d-0a9f-40c0-9071-e8ab5c358864",
                "device_id": "8186137819",
                "destination": "'.$tujuan.'",
                "text": "'.$pesan.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: connect.sid=s%3AK4gl0fVm_BYxmwsr22YTBvg380eam3Jg.dGlvdVUXWT%2BkOUnzGCxguLSyH%2FeuI3qgEsrd6%2FEL%2ByU'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function sendWALocal($tujuan, $pesan){
        $curl = curl_init();
        curl_setopt_array(
            $curl, array(
            CURLOPT_URL => 'http://localhost:3000/api/chat/send_text_message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "api_key": "2098466d-0a9f-40c0-9071-e8ab5c358864",
                "device_id": "3226643262",
                "destination": "'.$tujuan.'",
                "text": "'.$pesan.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: connect.sid=s%3AK4gl0fVm_BYxmwsr22YTBvg380eam3Jg.dGlvdVUXWT%2BkOUnzGCxguLSyH%2FeuI3qgEsrd6%2FEL%2ByU'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function sendWapisender($tujuan, $pesan){
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://wapisender.id/api/v1/send-message?api_key=HgBCfhjwX0un7VFvoypCWe16rSV14EQh&device_key=3kw0py&destination='.$tujuan.'&message='.$pesan,
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
        // return $response;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://wapisender.id/api/v1/send-message?api_key=HgBCfhjwX0un7VFvoypCWe16rSV14EQh&device_key=3kw0py&destination=085725951044&message=test%2520pesan%2520masuk%252041212414134%2520%252526%2520ini%2520contoh',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }
    public function sendwaapiME($tujuan, $pesan){
        $datakirim = [
            'message'=> $pesan,
            'number' => $tujuan,
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
        //   CURLOPT_POSTFIELDS => array('message' => 'oke','number' => '081252053793','file_dikirim'=> new CURLFILE('/C:/Users/Nusantara/Documents/Laporan Buku Tamu Tanggal 01-9-2022.pdf')),
        ));        
        $response = curl_exec($curl);        
        curl_close($curl);
        return $response;
    }
    public function sendwaapi($tujuan, $pesan){
        $datakirim = [
            'message'=> $pesan,
            'number' => $tujuan,
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
        return $response;
    }
    public function sendwaapi_Pay($tujuan, $pesan)
    {        
        $token = "tb8uFMZ9uToGwKULeLGe886N8dcDbti2Jv6HF2rtgVwpGcpgy9";
        // $phone= "62812xxxxxx"; //untuk group pakai groupid contoh: 62812xxxxxx-xxxxx
        $nomor = substr($tujuan, 1, 14);
        $phone = '62'.$nomor;
        $message = $pesan;

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$phone.'&message='.$message,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
?>