<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
use RestServer\RestController;
use RestServer\Format;
require(APPPATH . 'libraries/RestController.php');
require(APPPATH . 'libraries/Format.php');

class JKBApiController extends RestController
{
    function __construct()
    {
        parent::__construct();
    }
    public function index_get()
    { 
        $id = $this->get('id');
        if (empty($id)) {
            $data=$this->model_karyawan->getEmployeeWhere(['emp.status_emp'=>1]);
        }else{
            $data=$this->model_karyawan->getEmployeeWhere(['emp.id_karyawan'=>$id,'emp.status_emp'=>1]);
        }
        $this->response($data);
    }
    public function login_get()
    { 
        $username = $this->get('username');
        $password = $this->get('password');
        if (empty($username) || empty($password)) {
            $data=$this->apimessages->not_found();;
        }else{
            $data=$this->model_karyawan->getEmployeeWhere(['emp.nik'=>$username,'emp.password'=>$password,'emp.status_emp'=>1]);
        }
        $this->response( ['status' => true,'data' => $data], 200);
    }
    public function nik_get()
    { 
        $nik = $this->get('nik');
        if (empty($nik)) {
            $data=$this->apimessages->not_found();;
        }else{
            $data=$this->model_karyawan->getEmployeeWhere(['emp.nik'=>$nik,'emp.status_emp'=>1]);
        }
        $this->response( ['status' => true,'data' => $data], 200);
    }
    public function presensi_get()
    { 
        $id_karyawan = $this->get('id_karyawan');
        $tanggal = $this->get('tanggal');
        if (empty($id_karyawan) || empty($tanggal)) {
            $data=$this->apimessages->not_found();;
        }else{
            $data=$this->model_presensi->getDetailPresensiForPayroll($id_karyawan,$tanggal,$tanggal);
        }
        $this->response( ['status' => true,'data' => $data], 200);
    }
}
