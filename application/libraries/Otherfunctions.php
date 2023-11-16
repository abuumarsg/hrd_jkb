<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Code From GFEACORP.
* Web Developer
* @author 		Galeh Fatma Eko Ardiansa
* @package		Otherfunctions
* @copyright	Copyright (c) 2018 GFEACORP
* @version 	1.0, 1 September 2018
* Email 		galeh.fatma@gmail.com
* Phone		(+62) 85852924304
*/

class Otherfunctions {

    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    public function index()
    {
        $this->redirect('not_found');
    }
//===DATE BEGIN===//
    public function getDateNow($format = 'Y-m-d H:i:s') 
    {
        $date=gmdate($format, time() + 3600*(7));
        return $date;
    }
//===DATE END===//
    // public function configEmail()
    // {
    //     $config = [
    //         'protocol'=>'smtp',
    //         // 'smtp_host'=>'ssl://smtp.yandex.com',
    //         'smtp_host'=>'smtp.yandex.com',
    //         'smtp_port'=>'465',
    //         'smtp_user'=>'jkbnet@yandex.com',
    //         // 'smtp_pass'=>'3zU27Q5QxMYdye5',
    //         'smtp_pass'=>'r@has1ajkb',
    //         'charset'=>'utf-8',
    //         'priority'=>'1',
    //         'mailtype'=>'html',
    //         // 'smtp_crypto'=>'tls',
    //         'smtp_crypto'=>'ssl',
    //         'crlf'=>"\r\n",
    //         'newline'=>"\r\n",
    //     ];
    //     return $config;
    // }
    // public function configEmail()
    // {
    //     $config = [
    //         'protocol'=>'smtp',
    //         'smtp_host'=>'smtp-relay.sendinblue.com',
    //         'smtp_port'=>'587',
    //         'smtp_user'=>'jkb.hsoft@gmail.com',
    //         'smtp_pass'=>'Vnt1Czv5cBX4y8Gf',
    //         'charset'=>'utf-8',
    //         'priority'=>'1',
    //         'mailtype'=>'html',
    //         'smtp_crypto'=>'tls',
    //         'crlf'=>"\r\n",
    //         'newline'=>"\r\n",
    //     ];
    //     return $config;
    // }	
    public function configEmail()
    {
        $config = [
			'protocol'=>'smtp',
			'smtp_host'=>'ssl://smtp.gmail.com',
			'smtp_port'=>'465',
			'smtp_user'=>'triswuloh@gmail.com',
			'smtp_pass'=>'wuloh112233',
			'charset'=>'utf-8',
			'priority'=>'1',
			'mailtype'=>'html',
			'crlf'	=>"\r\n",
			'newline'=>"\r\n",
			'wordwrap' => TRUE,
			'useragent' => 'Codeigniter',
        ];
        return $config;
    }

//===BLOCK CHANGE===//

//===PERSONAL DATA BEGIN===//
//blood
    public function getBloodList()
    {
        $pack=[
            'a'=>'A',
            'b'=>'B',
            'ab'=>'AB',
            'o'=>'O'
        ];
        return $pack;
    }
    public function getBlood($key)
    {
        return $this->getVarFromArrayKey($key,$this->getBloodList());
    }

//gender
    public function getGenderList()
    {
        $pack=[
            'l'=>'Laki - Laki',
            'p'=>'Perempuan'
        ];
        return $pack;
    }
    public function getGender($key)
    {
        return $this->getVarFromArrayKey($key,$this->getGenderList());
    }

//religion
    public function getReligionList()
    {
        $pack=[
            'islam'=>'Islam',
            'k_katolik'=>'Kristen Katolik',
            'k_protestan'=>'Kristen Protestan',
            'hindu'=>'Hindu',
            'buddha'=>'Buddha',
            'konghucu'=>'Konghucu'
        ];
        return $pack;
    }
    public function getReligion($key)
    {
        return $this->getVarFromArrayKey($key,$this->getReligionList());
    }

//tipe jabatan
    public function getTipeJabatanList()
    {
        $pack=[
            'GOL1'=>'Golongan 1',
            'GOL2'=>'Golongan 2',
            'GOL3'=>'Golongan 3',
        ];
        return $pack;
    }
    public function getTipeJabatan($key)
    {
        return $this->getVarFromArrayKey($key,$this->getTipeJabatanList());
    }
//===PERSONAL DATA END===//

//===BLOCK CHANGE===//

//===EXAM BEGIN===//
//penilai
    public function getPenilaiList()
    {
        $pack=[
            'P1'=>'Atasan Langsung',
            'P2'=>'Admin',
            'P3'=>'Pilih User'
        ];
        return $pack;
    }
    public function getPenilai($key)
    {
        return $this->getVarFromArrayKey($key,$this->getPenilaiList());
    }

//penilai
    public function getPeriodePenilaianList()
    {
        $pack=[
            'BLN'=>'Bulanan',
            'SMT'=>'Semesteran',
            'THN'=>'Tahunan'
        ];
        return $pack;
    }
    public function getPeriodePenilaian($key)
    {
        return $this->getVarFromArrayKey($key,$this->getPeriodePenilaianList());
    }
//===EXAM END===//

//===BLOCK CHANGE===//

//===MASTER DATA BEGIN===//
//MASTER KPI
    public function getKaitanNilaiList()
    {
        $pack=[
           '0'=>'Tidak Berkaitan',
           '1'=>'Berkaitan'
       ];
       return $pack;
   }
   public function getKaitanNilai($key)
   {
    return $this->getVarFromArrayKey($key,$this->getKaitanNilaiList());
    }
    public function getJenisSatuanList()
    {
        $pack=[
           '0'=>'Huruf',
           '1'=>'Angka'
       ];
       return $pack;
    }
    public function getJenisSatuan($key)
    {
        return $this->getVarFromArrayKey($key,$this->getJenisSatuanList());
    }
    public function getJenisKpiList()
    {
        $pack=[
           'WAJIB'=>'Wajib',
           'TAMBAHAN'=>'Tambahan',
           'RUTIN'=>'Rutin',
       ];
       return $pack;
    }
    public function getJenisKpi($key)
    {
        return $this->getVarFromArrayKey($key,$this->getJenisKpiList());
    }
    public function getSifatKpiList()
    {
        $pack=[
           'MIN'=>'Minimal',
           'MAX'=>'Maksimal'
       ];
       return $pack;
    }
    public function getSifatKpi($key)
    {
        return $this->getVarFromArrayKey($key,$this->getSifatKpiList());
    }
	public function poin_max_range()
	{
		return 10;
	}
	public function column_value_max_range()
	{
		//jumlah penilaian
		return 3;
	}


    //===MASTER DATA END===//

    //===BLOCK CHANGE===//

    //===OTHERS BEGIN===//
    //boolean 
    public function getBoolean($sel = null)
    {
        $pack=[
           '1'=>'Ya',
           '0'=>'Tidak'
        ];
        if ($sel == '') {
            return $pack;
        }else{
            return (isset($pack[$sel]) ? $pack[$sel] : $this->getMark());        
        }      
    }
	function Even($array)
	{
		if($array!=0)
		   return TRUE;
		else 
		   return FALSE; 
	}
    //range huruf excel
    public function getRangeHuruf($digit=2)
    {
        $range=[];
        $letter=[];
        for ($i=0; $i <($digit+1) ; $i++) { 
           $letter[]='A';
       }
       $max=implode('',$letter);
       $c=0;
       for ($hrf='A'; $hrf!=$max; $hrf++){
           $range[$c]=$hrf;
           $c++;
       }
       return $range;
    }
    public function companyProfile()
    {
        $data=[
            'name'=>'HSOFT - HUCLE Software',
            'description'=>'HSOFT adalah aplikasi pengelolaan karyawan di perusahaan <b>'.$this->companyClientProfile()['name_office'].'</b> dengan menawarkan fitur <b>Pengelolaan Karyawan, Sistem Penggajian, Sistem Absensi dan Sistem Penilaian Kinerja</b>',
            'name_office'=>'PT. HUCLE Indonesia',
            'address'=>'Jl. Malabar No.149, Gajahmungkur, Semarang Kota <br> Jawa Tengah 50232',
            'call'=>'(024) 76424977',
            'email'=>'hucleconsulting@yahoo.co.id',
            'website'=>'http://hucle-consulting.com/',
            'website2'=>'https://huclesoftware.com',
            'logo'=>base_url('asset/img/hsoft.png'),
            'version'=>'1.1',
            'meta_description'=>'HSOFT, Hucle Software, Sistem HRD, HRD Management System, PT. Hucle Indonesia, '.$this->companyClientProfile()['meta_description'],
            'meta_author'=>'Galeh Fatma Eko Ardiansa S.Kom',
            'maps'=>'<div class="mapouter"><div class="gmap_canvas"><iframe width="100%" height="300" id="gmap_canvas" src="https://maps.google.com/maps?q=pt%20hucle%20indonesia&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://www.embedgooglemap.net">embedgooglemap.net</a></div><style>.mapouter{text-align:right;height:300px;width:100%;}.gmap_canvas {overflow:hidden;background:none!important;height:300px;width:100%;}</style></div>',
        ];
        return $data;
    }
    public function companyClientProfile()
    {
    //please change company profile
        $data=[
            'name'=>'JKB',
            'name_office'=>'CV. Jati Kencana',
            'address'=>'Jl. Malabar No.149, Gajahmungkur, Semarang Kota <br> Jawa Tengah 50232',
            'call'=>'(024) 76424977',
            'email'=>'hucleconsulting@yahoo.co.id',
            'website'=>'https://jkb.co.id/',
            'website2'=>'https://huclesoftware.com',
            'logo'=>base_url('asset/img/logo.png'),
            'meta_description'=>'HSOFT JKB, Hucle Software JKB, Sistem HRD JKB, HRD Management System JKB',
            'url'=>'https://hrd.jkb.co.id/',
            'logo_url'=>'https://hrd.jkb.co.id/asset/img/logo.png',
            // 'link_val_izin'=>'https://'.$_SERVER['HTTP_HOST'].'/auth/validasi_izin/',
            'link_val_izin'=>'https://'.$_SERVER['HTTP_HOST'].'/auth/validasi_izin/',
            'link_val_lembur'=>'https://'.$_SERVER['HTTP_HOST'].'/auth/validasi_lembur/',
            'link_val_perdin'=>'https://'.$_SERVER['HTTP_HOST'].'/auth/validasi_perdin/',
        ];
        return $data;
    }
    public function getVarFromArrayKey($key,$pack)
    {
        if (!isset($pack[$key])) 
            return $this->getMark('danger');
        return $pack[$key];
    }
    public function titlePages($uri)
    {
        if (empty($uri))
           return null;
       $new_val=null;
       $ex=explode('_', $uri);
       if (count($ex) > 0) {
           $new_val=implode(' ', $ex);
       }else{
           $new_val=$uri;
       }
       return ucwords(strtolower($new_val)).' | ';
    }
    public function getParseVar($val)
    {
        if(empty($val)) 
            return null;
        $new_val = null;
        $bag=[];
        $ex=explode(';', $val);
        foreach ($ex as $e) {
            $var=explode(':',$e);
            $bag[$var[0]]=$var[1];
        }
        $new_val=$bag;
        return $new_val;
    }
	public function getParseOneLevelVar($val,$params=';')
	{
		$bag=[];
		if(empty($val)) 
			return $bag;
		$bag=explode($params,$val);
		return array_unique(array_filter($bag));
	}

    public function getMark($usage = null)
    {
        $return='<i class="fa fa-times-circle" style="color:red" data-toggle="tooltip" title="Unknown"></i> ';
        switch($usage){
            case 'danger' : $return = '<i class="fa fa-times-circle" style="color:red"></i> '; break;
            case 'warning' : $return = '<i class="fa fa-warning" style="color:orange"></i> '; break;
            case 'success' : $return = '<i class="fa fa-check-circle scc" class=""></i> '; break;
            case 'info' : $return = '<i class="fa fa-info-circle" style="color:blue"></i> '; break;
        }
        return $return;
    }
    public function getCustomMark($usage,$viewx)
    {
        $mark=$viewx;
        if (empty($usage)) 
            return $mark;
        $new_val = ($usage=='danger')? $mark:$mark;
        $new_val = ($usage=='warning')? '<i class="fa fa-warning" style="color:orange"></i> ':$mark;
        $new_val = ($usage=='success')? '<i class="fa fa-check-circle" style="color:green"></i> ':$mark;
        $new_val = ($usage=='info')? '<i class="fa fa-info-circle" style="color:blue"></i> ':$mark;
        return $new_val;
    }
    //access menu
    public function getAllAccess()
    {
        $access=$this->CI->model_master->getListAccess();
        $pack=[];
        foreach ($access as $a) {
            $pack[strtolower($a->kode_access)]=strtoupper($a->kode_access);
        }
        return $pack;
    }
    public function getYourAccess($id)
    {
        if (empty($id)) 
            return null;
        $pack=[];
        $admin=$this->CI->model_admin->getAdminById($id);
        if (!isset($admin)) 
            return null;
        $user_group=$this->CI->model_master->getUserGroup($admin['id_group']);
        if (!isset($user_group)) 
            return null;
        $ex=explode(';',$user_group['list_access']);
        if (!isset($ex)) 
            return null;
        foreach ($ex as $e) {
            $acc=$this->CI->model_master->getAccess($e);

            if (isset($acc)) {
                foreach ($acc as $d) {
                  array_push($pack,$d->kode_access);
              }
          }
      }
      return $pack;
    }
    public function getYourMenu($id)
    {
        if (empty($id)) 
            return null;
        $pack=[];
        $admin=$this->CI->model_admin->getAdminById($id);
        if (!isset($admin)) 
            return null;
        $user_group=$this->CI->model_master->getUserGroup($admin['id_group']);
        if (!isset($user_group)) 
            return null;
        $ex=explode(';',$user_group['list_id_menu']);
        if (!isset($ex)) 
            return null;
            foreach ($ex as $e) {
                $menu=$this->CI->model_master->getMenu($e);
                if (isset($menu)) {
					if ($menu['parent'] || ($menu['parent'] == 0 && $menu['sequence'] == 1)) {
						if ($menu['url'] != '#' && $menu['status'] == 1) {
							$ex1=$this->getParseOneLevelVar($menu['sub_url']);
							if (isset($ex1)) {
								foreach ($ex1 as $e1) {
									array_push($pack,$e1);
								}
							}
							array_push($pack,$menu['url']);
						}
                    }
                }
            }
        return array_values(array_unique($pack));
    }
    public function getYourMenuId($id)
    {
        if (empty($id)) 
            return null;
        $pack=[];
        $admin=$this->CI->model_admin->getAdminById($id);
        if (!isset($admin)) 
            return null;
        $user_group=$this->CI->model_master->getUserGroup($admin['id_group']);
        if (!isset($user_group)) 
            return null;
        $ex=explode(';',$user_group['list_id_menu']);
        if (!isset($ex)) 
            return null;
        $pack=$ex;
        return $pack;
    }
    //list menu
    public function getDrawMenu($your_menu,$data,$parent,$url)
    {
        if (empty($data)) 
        return null;
        $new_val = null;
        foreach ($data as $d){
            if ($d->parent == $parent){
                if (in_array($d->id_menu,$your_menu)) {
                    if ($this->getChildren($data,$d->id_menu)) {
                        $url_act=$this->getParseOneLevelVar($d->sub_url);
                        if (in_array($url,$url_act)) {
                            $class=' class="treeview active"';
                        }else{
                            $class=' class="treeview"';
                        }
                        $name='<i class="fa '.$d->icon.'"></i> <span>'.$d->nama.'</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>';
                        $urlx='#';
                    }else{
                        $url_act=$this->getParseOneLevelVar($d->sub_url);
                        if (in_array($url,$url_act)) {
                            $class=' class="active"';
                        }else{
                            if ($url == $d->url) {
                                $class=' class="active"';
                            }else{
                                $class=null;
                            }
                        }
                        $name='<i class="'.$d->icon.'"></i> <span>'.$d->nama.'</span>';
                        $urlx=$d->url;
                    }
                    $new_val.= '<li '.$class.' style="white-space: normal; overflow-wrap: break-word;"><a href="'.base_url('pages/'.$urlx).'" title="'.$d->nama.'">'.$name.'</a>';
                    if ($this->getChildren($data,$d->id_menu))                                   
                    $new_val.= '<ul class="treeview-menu">'.$this->getDrawMenu($your_menu,$data,$d->id_menu,$url).'</ul>'; 
                    $new_val.= "</li>";   
                }               
            }
        }
        return $new_val;
    }
    public function getChildren($data,$id)
    {
        foreach ($data as $d) {
            if ($d->parent == $id)
                return true;            
        }
        return false;
    }
    public function getDrawMenu2($data,$parent)
    {
        if (empty($data)) 
            return null;
        $new_val = null;
        foreach ($data as $d)
        {
            if ($d->parent == $parent){
                $new_val.= '<li data-jstree=\'{"icon":"'.$d->icon.'"}\' id="'.$d->id_menu.'"><a href="#">'.$d->nama.'</a>';
                if ($this->getChildren2($data,$d->id_menu)){
                    $new_val.= '<ul class="sub_menu">'.$this->getDrawMenu2($data,$d->id_menu).'</ul>';
                }
                $new_val.= "</li>";                  
            }
        }
        return $new_val;
    }
    public function getChildren2($data,$id)
    {
        foreach ($data as $d) {
            if ($d->parent == $id)
                return true;            
        }
        return false;
    }
    public function getMenuParent($data,$child)
    {
        if (empty($data)) 
            return null;
        $new_val = [];
        foreach ($data as $d){
    //if ($d->parent != 0) {
    //if ($d->id_menu == $child) {
          if ($this->getParent($data,$d->id_menu))
            $new_val[$d->id_menu][]=$d->parent;
    // }
    //}
    }
    return $new_val;
    }
    public function getParent($data,$id)
    {
        foreach ($data as $d) {
            if ($d->id_menu == $id)
                return true;            
        }
        return false;
    }
    //notification
    // Tipe Notifikasi 
    public function getTipeNotifikasiList()
    {
        $pack=[
            'info'=>'Informasi Pemberitahuan',
            'warning'=>'Peringatan',
            'danger'=>'Larangan'
        ];
        return $pack;
    }
    public function getTipeNotifikasi($key)
    {
        return $this->getVarFromArrayKey($key,$this->getTipeNotifikasiList());
    } 
    // Sifat 
    public function getSifatList()
    {
        $pack=[
            1=>'Penting',
            2=>'Tidak Penting'
        ];
        return $pack;
    }
    public function getSifat($key)
    {
        return $this->getVarFromArrayKey($key,$this->getSifatList());
    } 
    // untuk 
    public function getUntukList()
    {
        $pack=[
            'ADM'=>'Administrator',
            'FO'=>'Front Office'
        ];
        return $pack;
    }
    public function getUntuk($key)
    {
        return $this->getVarFromArrayKey($key,$this->getUntukList());
    } 
    public function getYourNotification($id,$usage)
    {
        if (empty($id) || empty($usage)) 
            return null;
        $pack=[];
        if ($usage == 'admin') {
            $notif=$this->CI->model_master->getNotifAdmin();
        }elseif($usage == 'fo'){
            $notif=$this->CI->model_master->getNotifEmployee();
        }else{
            return null;
        }
        foreach ($notif as $ntf) {
            $id_admm=explode(';', $ntf->id_for);
            $id_admm_r=explode(';', $ntf->id_read);
            $id_admm_d=explode(';', $ntf->id_del);
            if (in_array($id,$id_admm) && !in_array($id, $id_admm_r) && !in_array($id, $id_admm_d)) {
                $data=array('kode'=>$ntf->kode_notif,'judul'=>$ntf->judul,'tipe'=>$ntf->tipe,'sifat'=>$ntf->sifat);
                array_push($pack, $data);
            }
        }
        return $pack;
    }
    public function addValueToArrayDb($arr,$val,$param)
    {
        if (empty($arr))
            return $val;
        if (empty($val) || empty($param))
            return null;
        $new_val=[];
        $new_val=explode($param,$arr);
        if (isset($new_val)) {
            array_push($new_val,$val);
        }
        $new_val=array_values(array_filter(array_unique($new_val)));
        return implode($param,$new_val);
    }
    public function removeValueToArrayDb($arr,$val,$param)
    {
        if (empty($val))
            return $arr;
        if (empty($arr) || empty($param))
            return null;
        $new_val=[];
        $new_val=explode($param,$arr);
        $pos=array_search($val, $new_val);
        if (isset($new_val[$pos])) {
            unset($new_val[$pos]);
        }
        $new_val=array_values(array_filter(array_unique($new_val)));
        return implode($param,$new_val);
    }
    //property table
    public function getPropertiesTable($val)
    {
        $data=[
            'tanggal'=>null,
            'status'=>null,
            'aksi'=>null
        ];
        if (empty($val))
            return $data;
        $create=null;
        $update=null;
        $status=null;
        $delete=null;
        if (isset($val['create'])) {
            $create=$this->CI->formatter->getDateTimeMonthFormatUser($val['create']);
        }
        if (isset($val['update'])) {
            $update=$this->CI->formatter->getDateTimeMonthFormatUser($val['update']);
        }
        $tanggal='<label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fas fa-pen fa-fw"></i>'.$create.' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit fa-fw"></i>'.$update.' WIB</label>';
        if (isset($val['status'])) {
            if ($val['status'] == 1) {
              $status='<button type="button" class="stat scc" href="javascript:void(0)" onclick=do_status('.$val['id'].',0)><i class="fa fa-toggle-on"></i></button>';
            }else{
                $status='<button type="button" class="stat err" href="javascript:void(0)" onclick=do_status('.$val['id'].',1)><i class="fa fa-toggle-off"></i></button>';
            }
            if (isset($val['access']['l_ac']['stt'])) {
                $var_st=($val['status'] == 1) ? '<i class="fa fa-toggle-on stat scc" title="Tidak Diijinkan"></i>':'<i class="fa fa-toggle-off stat err" title="Tidak Diijinkan"></i>';
                $status=(in_array($val['access']['l_ac']['stt'], $val['access']['access']) && isset($val['access']['l_ac']['stt']))  ? $status : $var_st;
            }else{
                $status=$this->CI->messages->not_allow();
            }
        }
        if (isset($val['access']['l_ac']['del'])) {
            $delete = (in_array($val['access']['l_ac']['del'], $val['access']['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$val['id'].')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
        }else{
            $delete = null;
        }
        $info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$val['id'].')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
        if (isset($val['access']['l_ac']['prn'])) {
            $print = (in_array($val['access']['l_ac']['prn'], $val['access']['access'])) ? '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick="do_print('.$val['id'].')"><i class="fa fa-print" data-toggle="tooltip" title="Cetak Data"></i></button> ' : null;
        }else{
            $print = null;
        }
        $aksi=$info.$delete;
        //================== kedua ====================
        $status2=null;
        $delete2=null;
        if (isset($val['status'])) {
            if ($val['status'] == 1) {
              $status2='<button type="button" class="stat scc" href="javascript:void(0)" onclick=do_status2('.$val['id'].',0)><i class="fa fa-toggle-on"></i></button>';
            }else{
                $status2='<button type="button" class="stat err" href="javascript:void(0)" onclick=do_status2('.$val['id'].',1)><i class="fa fa-toggle-off"></i></button>';
            }
            if (isset($val['access']['l_ac']['stt'])) {
                $var_st=($val['status'] == 1) ? '<i class="fa fa-toggle-on stat scc" title="Tidak Diijinkan"></i>':'<i class="fa fa-toggle-off stat err" title="Tidak Diijinkan"></i>';
                $status2=(in_array($val['access']['l_ac']['stt'], $val['access']['access']) && isset($val['access']['l_ac']['stt']))  ? $status2 : $var_st;
            }else{
                $status2=$this->CI->messages->not_allow();
            }
        }
        if (isset($val['access']['l_ac']['del'])) {
            $delete2 = (in_array($val['access']['l_ac']['del'], $val['access']['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal2('.$val['id'].')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
        }else{
            $delete2 = null;
        }
        $info2 = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal2('.$val['id'].')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
        $data=[
            'tanggal'=>$tanggal,
            'status'=>$status,
            'aksi'=>$aksi,
            'cetak'=>$print,
            'info'=>$info,
            'delete'=>$delete,
            'status2'=>$status2,
            'info2'=>$info2,
            'delete2'=>$delete2,
        ];
        return $data;
    }
    public function colorPiece() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
    public function getRandomColor() {
        return '#'.$this->colorPiece().$this->colorPiece().$this->colorPiece();
    }
    public function addFrontZero($number)
    {
        if (empty($number))
            return 0;
        if ($number < 10) {
            $number='0'.$number;
        }
        return $number;
    }
    public function convertResultToRowArray($valObj) {
        if (empty($valObj) || !isset($valObj))
          return null;
      $valObj=json_decode(json_encode($valObj), true);
      return $valObj[0];
    }
    //===OTHERS END===//
    //new putra
	public function getDatePeriode($start,$end,$tahun)
	{
		$date = date('m');
		// $date = '09';
		$new_val = null;
		$val = $this->CI->formatter->getNameOfMonthByPeriodeNum($start,$end,$tahun);
		for ($x=0; $x < $this->column_value_max_range(); $x++) { 
			if($val[$x]==$date){
				$new_val = $x;
			}
		}
		return $new_val;
	}
    public function changeNullTo($val,$key)
    {
        if($val==null){
            $new_val = $key;
        }else{
            $new_val = $val;
        }
        return $new_val;
    }
    // untuk null/ kosong
    public function getParseVarnull($val)
    {
        if(empty($val)) 
            return null;
        $new_val = null;
        $bag=[];
        $ex=explode(';', $val);
        foreach ($ex as $e) {
            $var=explode(':',$e);
            if($var[1]!=''){
              $bag[$var[0]]=$var[1];
          }else{
              $bag[$var[0]]='';
          }
      }
      $new_val=$bag;
      return $new_val;
    }
    public function getFotoValue($foto,$kelamin)
    {
        if(empty($foto)){
            if($kelamin=='p'){
              $val = 'asset/img/user-photo/userf.png';
          }else{
              $val = 'asset/img/user-photo/user.png';
          }
      }else{
        $val = $foto;
    }
    return $val;
    }
    //status pajak
    public function getStatusPajakList()
    {
        $pack=[
            'TK/0'=>'TK/0',
            'TK/1'=>'TK/1',
            'TK/2'=>'TK/2',
            'TK/3'=>'TK/3',
            'K/0'=>'K/0',
            'K/1'=>'K/1',
            'K/2'=>'K/2',
            'K/3'=>'K/3',
            'K/I/0'=>'K/I/0',
            'K/I/1'=>'K/I/1',
            'K/I/2'=>'K/I/2',
            'K/I/3'=>'K/I/3'
        ];
        return $pack;
    }
    public function getStatusPajak($key)
    {
        return $this->getVarFromArrayKey($key,$this->getStatusPajakList());
    }
    //status Nikah
    public function getStatusNikahList()
    {
        $pack=[
            'BELUM NIKAH'=>'Belum Nikah',
            'NIKAH'=>'Nikah',
            'DUDA'=>'Duda',
            'JANDA'=>'Janda'
        ];
        return $pack;
    }
    public function getStatusNikah($key)
    {
        return $this->getVarFromArrayKey($key,$this->getStatusNikahList());
    }
    //Pendidikan
    public function getEducateList()
    {
        $pack=[
            'SD'=>'SD',
            'SLTP'=>'SLTP',
            'SLTA'=>'SLTA',
            'D1'=>'Diploma 1',
            'D2'=>'Diploma 2',
            'D3'=>'Diploma 3',
            'S1'=>'Strata 1',
            'S2'=>'Strata 2',
            'S3'=>'Strata 3'
        ];
        return $pack;
    }
    public function getEducate($key)
    {
        return $this->getVarFromArrayKey($key,$this->getEducateList());
    }
    //BAHASA
    public function getBahasaList()
    {
        $pack=[
            'BAHASA INDONESIA'=>'Bahasa Indonesia',
            'BAHASA INGGRIS'=>'Bahasa Inggris',
            'BAHASA BELANDA'=>'Bahasa Belanda',
            'BAHASA JEPANG'=>'Bahasa Jepang',
            'BAHASA KOREA'=>'Bahasa Korea',
            'BAHASA JERMAN'=>'Bahasa Jerman',
            'BAHASA ARAB'=>'Bahasa Arab',
            'BAHASA MANDARIN'=>'Bahasa Mandarin',
            'BAHASA MELAYU'=>'Bahasa Melayu',
            'BAHASA JAWA'=>'Bahasa Jawa'
        ];
        return $pack;
    }
    public function getBahasa($key)
    {
        return $this->getVarFromArrayKey($key,$this->getBahasaList());
    }
    //SATUAN Gaji
    public function getSatuanGajiList()
    {
        $pack=[
            'harian'=>'Gaji Harian',
            'mingguan'=>'Gaji Mingguan',
            'bulanan'=>'Gaji Bulanan',
            'borongan'=>'Gaji Borongan',
        ];
        return $pack;
    }
    public function getSatuanGaji($key)
    {
        return $this->getVarFromArrayKey($key,$this->getSatuanGajiList());
    }
    //Jenis IZIN /CUTI
    public function getIzinCutiList()
    {
        $pack=[
            'I'=>'IZIN',
            'C'=>'CUTI',
        ];
        return $pack;
    }
    public function getIzinCuti($key)
    {
        return $this->getVarFromArrayKey($key,$this->getIzinCutiList());
    }
    public function getStatusIzinList()
    {
        $pack=[
            0=>'<label class="label label-danger" style="font-size:14px;"><i class="fa fa-times-circle"></i> Tidak Diizinkan</label>',
            1=>'<label class="label label-success" style="font-size:14px;"><i class="fa fa-check-circle"></i> Diizinkan</label>',
            2=>'<label class="label label-warning" style="font-size:14px;"><i class="fa fa-warning"></i> Belum Divalidasi</label>',
        ];
        return $pack;
    }
    public function getStatusIzin($key)
    {
        return $this->getVarFromArrayKey($key,$this->getStatusIzinList());
    }
    public function getStatusIzinListRekap()
    {
        $pack=[
            0=>'Tidak Diizinkan',
            1=>'Diizinkan',
            2=>'Belum Divalidasi',
        ];
        return $pack;
    }
    public function getStatusIzinRekap($key)
    {
        return $this->getVarFromArrayKey($key,$this->getStatusIzinListRekap());
    }
    public function getStatusPotongJam()
    {
        $pack=[
            0=>'Belum Disesuaikan',
            1=>'Sudah Disesuaikan',
        ];
        return $pack;
    }
    //YES NO
    public function getYesNoList()
    {
        $pack=[
            1=>'Ya',
            0=>'Tidak',
        ];
        return $pack;
    }
    public function getYesNo($key)
    {
        return $this->getVarFromArrayKey($key,$this->getYesNoList());
    }
    //SHIFT
    public function getYshiftList()
    {
        $pack=[
            'SHIFT'=>'Shift',
            'TIDAK SHIFT'=>'Tidak Shift',
        ];
        return $pack;
    }
    public function getYshift($key)
    {
        return $this->getVarFromArrayKey($key,$this->getYshiftList());
    }
    //RADIO BAHASA
    public function getRadioList()
    {
        $pack=[
            5=>'Sangat Baik',
            4=>'Baik',
            3=>'Cukup',
            2=>'Kurang',
            1=>'Sangat Kurang',
        ];
        return $pack;
    }
    public function getRadio($key)
    {
        return $this->getVarFromArrayKey($key,$this->getRadioList());
    }
    public function getGolonganKaryawanList()
    {
        $pack=[
            1=>'Golongan 1',
            2=>'Golongan 2',
            3=>'Golongan 3',
        ];
        return $pack;
    }
    public function getGolonganKaryawan($key)
    {
        return $this->getVarFromArrayKey($key,$this->getGolonganKaryawanList());
    }
    //ALASAN KELUAR
    // public function getAlasanKeluarList()
    // {
    //     $pack=[
    //         1=>'Mengundurkan Diri',
    //         2=>'Putus Kontrak',
    //         3=>'Pensiun',
    //         4=>'Kesalahan Berat',
    //         5=>'Perusahaan Pailit',
    //         6=>'Perusahaan Tutup',
    //         7=>'Meninggal Dunia',
    //         8=>'Mangkir Kerja lebih dari 5 hari',
    //         9=>'Pelanggaran',
    //         10=>'Alasan Lainnya',
    //     ];
    //     return $pack;
    // }
    // public function getAlasanKeluar($key)
    // {
    //     return $this->getVarFromArrayKey($key,$this->getAlasanKeluarList());
    // }
    public function getSkinColorText($skin)
    {
        if(empty($skin))
            return false;

        if($skin == 'dark-mode'){
            $color = '#ECFFFF';
        }else{
            $color = '#3D4440';
        }
        return $color;
    }
    public function hitungHari($awal,$akhir){
        $tglAwal = strtotime($awal);
        $tglAkhir = strtotime($akhir);
        $jeda = abs($tglAkhir - $tglAwal);
        return floor($jeda/(60*60*24)+1);
    }
    public function countDayNotIncludeLeave($tanggal_mulai,$tanggal_selesai)
    {        
		// $date_loop=$this->CI->formatter->dateLoopFull($tanggal_mulai,$tanggal_selesai);
        $countLibur = 0;
        // if(!empty($date_loop)){
        //     foreach ($date_loop as $key => $tanggal) {
        //         $libur = $this->checkHariLiburActive($tanggal);
        //         if(!empty($libur)){
        //             $countLibur += 1;
        //         }
        //     }
        // }
        $tglAwal = strtotime($tanggal_mulai);
        $tglAkhir = strtotime($tanggal_selesai);
        $jeda = abs($tglAkhir - $tglAwal);
        return floor($jeda/(60*60*24)-($countLibur));
    }
    public function hitungBulan($awal,$akhir){
        $tglAwal = strtotime($awal);
        $tglAkhir = strtotime($akhir);
        $jeda = abs($tglAkhir - $tglAwal);
        return floor($jeda/(60*60*24)/30);
    }
    public function jam($time,$int,$op)
    {
        if ($op == '-') {
           $jam=strtotime($time) - (60*60*$int); 
       }else{
          $jam=strtotime($time) + (60*60*$int);
      }
      return date('H:i:s',$jam);
    }
    public function getRangeTime($jam_mulai,$jam_selesai)
    {
        if(empty($jam_mulai) || empty($jam_selesai))
            return null;

        if(strtotime($jam_selesai)>strtotime($jam_mulai)){
            $awal  = explode(':', $jam_mulai)[0];
            $akhir =explode(':', $jam_selesai)[0];
            $new_val = $akhir-$awal;
        }else{
            $new_val = 0;
        }
        return $new_val;
    }

    public function getRangeTimeDate($tgl_mulai,$tgl_selesai,$usage)
    {
        // $usage as Jam = hh.jam, menit = hh.jam ii.menit, detik = hh.jam ii.meni ss.detik, hari = dd.hari //
        if(empty($tgl_mulai) || empty($tgl_selesai))
            return null;

        if(strtotime($tgl_selesai)>strtotime($tgl_mulai)){
            $exp_m = explode(' ', $tgl_mulai);
            $date_m = explode('-', $exp_m[0]);
            $time_m = explode(':', $exp_m[1]);

            $exp_s = explode(' ', $tgl_selesai);
            $date_s = explode('-', $exp_s[0]);
            $time_s = explode(':', $exp_s[1]);

            $waktu_mulai = mktime(date($time_m[0]),date($time_m[1]),date($time_m[2]),date($date_m[1]),date($date_m[2]),date($date_m[0]));
            $waktu_selesai = mktime(date($time_s[0]), date($time_s[1]), date($time_s[2]), date($date_s[1]), date($date_s[2]), date($date_s[0]));
            $selisih_waktu = $waktu_mulai - $waktu_selesai;
            $jumlah_hari = floor($selisih_waktu/86400);
            $sisa = $selisih_waktu % 86400;
            $jumlah_jam = round($sisa/3600);
        // return $jumlah_jam;
            $sisa = $sisa % 3600;
            $jumlah_menit = round($sisa/60);
            $sisa = $sisa % 60;
            $jumlah_detik = round($sisa/1);
            if($usage=='jam'){
                $new_val = abs($jumlah_jam).' Jam';
            }elseif($usage=='menit'){
                $new_val = abs($jumlah_jam).' Jam '.abs($jumlah_menit).' Menit';
            }elseif($usage=='detik'){
                $new_val = abs($jumlah_jam).' Jam '.abs($jumlah_menit).' Menit '.abs($jumlah_detik).' Detik ';
            }elseif($usage=='hari'){
                $new_val = abs($jumlah_hari).' Hari';
            }elseif($usage=='all'){
                $new_val = [
                    'hari'=>abs($jumlah_hari),
                    'jam'=>abs($jumlah_jam),
                    'menit'=>abs($jumlah_menit),
                    'detik'=>abs($jumlah_detik),
                    'alljam'=>((abs($jumlah_hari))*24)+(abs($jumlah_jam)),
                ];
            }
        }else{
            $new_val = '0 Jam';
			if($usage=='all'){
                $new_val = [
                    'hari'=>0,
                    'jam'=>0,
                    'menit'=>0,
                    'detik'=>0,
                    'alljam'=>0,
                ];
			}
        }
        return $new_val;
    }
    public function getLabelMark($val,$usage = 'default' ,$custom = 'Tidak Ada Data', $title = '', $align = 'center',$icon='times')
    {
    // $usage is danger/warning/success/info
        if(empty($val)){
            $new_val = '<div style="text-align: '.$align.';"><label class="label label-sm label-'.$usage.' label-xs"><i class="fa fa-'.$icon.'"></i> '.$custom.'</label></div>';
        }else{
            $new_val = $val.' '.$title;
        }
        return $new_val;
    }
    public function getDivTime($start,$end,$format = 'time',$format_time='H:i')
    {
        if ($start == NULL || $end == NULL) {
            $time='-';        
        }else{
            $day_count=0;
            if (strpos($start,'-')) {
                $s = date_create($start);
                $e=  date_create($end);
                $diff  = date_diff($s,$e);
                $day_count=$diff->format('%d');                  
                $time= $diff->format("%H:%I");
            }else{ 
                $s = strtotime($start);
                $e=  strtotime($end);
                if ($s < $e) {
                    $diff= $e-$s;
                }else{
					$st=date('Y-m-d').' '.$start;
					$ed=date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d')))).' '.$end;
                    $s=strtotime($st);
                    $e=strtotime($ed);
                    $diff= $e-$s;
                }
                $time= gmdate($format_time,abs($diff));
            }
            $day_count=($day_count)?$day_count:0;
			$now=date('Y-m-d',strtotime($this->getDateNow()));
			$str=date('Y-m-d',strtotime($end));
            if (strpos($time,':')) {
                $exp=explode(':',$time);
                if ($now == $str) {
                    $day_count=0;
                }else{
                    $day_count=$day_count;
                }
                $jam=$exp[0]+($day_count*24);
                $menit=$exp[1];
                $detik=((isset($exp[2]))?$exp[2]:'00');
                if($format == 'lembur'){
                    $jam = ($jam<10)?'0'.$jam:$jam;
                    $time=$jam.':'.$menit.(($format_time == 'H:i:s')?':'.$detik:'');
                }else{
                    $time=$jam.':'.$menit.(($format_time == 'H:i:s')?':'.$detik:'');
                }
            } 
        }
        return $time;
    }
    public function getDivDate($start,$end,$format = 1)
    {
        if ($start == NULL || $end == NULL) {
            $day_count='-';        
        }else{
            $day_count=0;
            $dt1 = date_create($start);
            $dt2 = date_create($end);
            $diff = date_diff($dt1, $dt2);
            $day_count=$diff->format('%d')+$format;
        }
        return $day_count;
    }
    public function formatDateFinger($date)
    {
        if(empty($date))
            return null;
        $val=explode('/', $date);
        $new_date=$val[2].'-'.$val[0].'-'.$val[1];
        return $new_date;
    }
    public function formatDateTimeFinger($date)
    {
        if(empty($date))
            return null;

        $par=explode(' ', $date);

        $val=explode('/', $par[0]);
        $new_date=$val[2].'-'.$val[0].'-'.$val[1];
        return $new_date;
    }
    public function cekJamMasukPulang($masuk,$pulang,$jam)
    {
        if(empty($masuk) || empty($pulang) || empty($jam))
            return null;
        $new_val = null;
        $masuk_min = date_create($masuk);
        date_add($masuk_min, date_interval_create_from_date_string('-4 hours'));
        $newmasuk_min = date_format($masuk_min, 'H:i:s');
    // $newmasuk_min = date('H:i:s', $masuk_min);

        $masuk_max = date_create($masuk);
        date_add($masuk_max, date_interval_create_from_date_string('4 hours'));
        $newmasuk_max = date_format($masuk_max, 'H:i:s');
    // $newmasuk_max = date('H:i:s', $masuk_max);

        $pulang_min = date_create($pulang);
        date_add($pulang_min, date_interval_create_from_date_string('-4 hours'));
        $newpulang_min = date_format($pulang_min, 'H:i:s');
    // $newpulang_min = date('H:i:s', $pulang_min);

        $pulang_max = date_create($pulang);
        date_add($pulang_max, date_interval_create_from_date_string('4 hours'));
        $newpulang_max = date_format($pulang_max, 'H:i:s');
    // $newpulang_max = date('H:i:s', $pulang_max);

        if($jam >= $newmasuk_min && $jam <= $newmasuk_max){
            $new_val = 'jam_masuk';
        }elseif($jam >= $newpulang_min && $jam <= $newpulang_max){
            $new_val = 'jam_pulang';
        }
        return $new_val;
    }
    //Ukuran
    public function getUkuranBajuList()
    {
        $pack=[
            'xs'=>'XS',
            's'=>'S',
            'm'=>'M',
            'l'=>'L',
            'xl'=>'XL',
            'xxl'=>'XXL',
            'xxxl'=>'XXXL',
        ];
        return $pack;
    }
    public function getUkuranBaju($key)
    {
        return $this->getVarFromArrayKey($key,$this->getUkuranBajuList());
    }
    //Metode Perhitungan
    public function getMetodePerhitunganList()
    {
        $pack=[
            'nett'=>'NETT',
            'gross'=>'GROSS',
        ];
        return $pack;
    }
    public function getMetodePerhitungan($key)
    {
        return $this->getVarFromArrayKey($key,$this->getMetodePerhitunganList());
    }
    //Gaji Pokok
    public function getJenisGajiList()
    {
        $pack=[
            'matrix'=>'MATRIX',
            'non_matrix'=>'NON-MATRIX',
        ];
        return $pack;
    }
    public function getJenisGaji($key)
    {
        return $this->getVarFromArrayKey($key,$this->getJenisGajiList());
    }
    public function limitWords($string, $id, $mode, $word_limit=10, $warp = 'nowrap')
    {
        if(empty($string) || empty($id) || empty($mode))
            return null;
        $count_string = $this->countWordString($string,$mode);
        if(!empty($count_string)){
            if($count_string <= $word_limit){
                $new_val = $string;
            }else{
                $words = explode(" ",$string);
                $imp_words = implode(" ",array_splice($words,0,$word_limit));
                $new_val = '<div style="'.$warp.'">
                <span class="'.$warp.'" id="read_partian_'.$id.'" title="'.$string.'">'.$imp_words.'... 
                <a onclick="readmore('.$id.')" style="cursor: pointer;color:#4A89BF;"><i class="fa fa-chevron-circle-right fa-fw"></i></a>
                </span>
                <span class="'.$warp.'" id="read_full_'.$id.'" style="display:none;">'.$string.'  
                <a onclick="hidemore('.$id.')" style="cursor: pointer;color:#4A89BF;"><i class="fa fa-chevron-circle-left fa-fw"></i></a>
                </span>
                </div>';
            }
        }else{
            $new_val = null;
        }
        return $new_val;
    }
    public function getFotoValueAdmin($foto,$kelamin)
    {
        if(empty($foto)){
            if($kelamin=='p'){
                $val = 'styles/img/photo/user-photo/userf.png';
            }else{
                $val = 'styles/img/photo/user-photo/user.png';
            }
        }else{
            $val = $foto;
        }
        return $val;
    }
    //Metode Perhitungan
    public function getTujuanPDList()
    {
        $pack=[
            'plant'=>'PLANT',
            'non_plant'=>'NON-PLANT',
        ];
        return $pack;
    }
    public function getTujuanPD($key)
    {
        return $this->getVarFromArrayKey($key,$this->getTujuanPDList());
    }
    //=== HARI ====
    public function getHariList()
    {
        $pack=[
            1=>"SENIN",
            2=>"SELASA",
            3=>"RABU",
            4=>"KAMIS",
            5=>"JUM'AT",
            6=>"SABTU",
            7=>"MINGGU",
        ];
        return $pack;
    }
    public function getHari($key)
    {
        return $this->getVarFromArrayKey($key,$this->getHariList());
    }
    public function getListKendaraanUmum()
    {
        $pack=[
            'bus'=>'BUS',
            'ka'=>'KERETA API',
            'kapal'=>'KAPAL',
            'pesawat'=>'PESAWAT',
            'travel'=>'TRAVEL',
            'angkot'=>'ANGKOT',
            'online'=>'ONLINE',
        ];
        return $pack;
    }
    public function getKendaraanUmum($key)
    {
        return $this->getVarFromArrayKey($key,$this->getListKendaraanUmum());
    }
    public function getListPenginapan()
    {
        $pack=[
            'hotel'=>'HOTEL',
            'mess'=>'MESS',
            'lainnya'=>'Lainnya',
        ];
        return $pack;
    }
    public function getPenginapan($key)
    {
        return $this->getVarFromArrayKey($key,$this->getListPenginapan());
    }
/*++++++++++++++++++++++++++++++++++++++++++++= Sourch Putra S. Bud =++++++++++++++++++++++++++++++++++++++++++++ */
/*++++++++++++++++++++++++++++++ date 02/04/2019 	++++++++++++++++++++++++++++++*/
   public function searchFromArray($val,$array)
   {
   	if($val != 0){
   		if(empty($val) || empty($array))
    		return null;
   	}

    	$new_val = $array[$val];
    	return $new_val;
   }
   public function convertKodetoId($arrKode, $attr)
   {
   	/*
   	$attr = [
   		'table' => ,
   		'kode' => ,
   		'id' => ,
   	];
   	*/
   	if(empty($arrKode) || empty($attr['table']) || empty($attr['kode']) || empty($attr['id']))
   		return null;
   	
   	$new_val = [];
   	$id = $attr['id'];
   	$kode = $attr['kode'];
   	foreach ($arrKode as $key => $value) {
   		$data = [
   			'table' => 	$attr['table'],
   			'where' => 	[
				   				$attr['kode']=>$value
				   			],
   		];
   		$get_data = $this->CI->model_global->getGlobalTable($data, 'all_item');
   		foreach ($get_data as $d) {
   			$new_val[$d->$kode] = $d->$id;
   		}
   	}
   	return $new_val;
   }
   
	public function intervalTimeYear($val, $umur=null)
	{
		if(empty($val))
			return null;

		$date1 = date_create($val);
		$date2 = date_create(date('Y-m-d'));
		$interval = date_diff($date1, $date2);
		if($interval->y > 0){
            if(empty($umur)){
			    $new_val = $interval->y.'Tahun '.$interval->m.'Bulan '.$interval->d.'Hari';
            }else{
			    $new_val = [
                    'tahun'=>$interval->y,
                    'bulan'=>$interval->m,
                    'hari'=>$interval->d,
                ];
            }
		}else{
			if($interval->m > 0){
				$new_val = $interval->m.'Bulan '.$interval->d.'Hari';
			}else{
				if($interval->d > 0){
					$new_val = $interval->d.'Hari';
				}else{
					$new_val = $interval->d.'Hari';
				}
			}
		}
		return $new_val;
	}
	public function getRangeDate($tgl_mulai,$tgl_selesai)
	{
		if(empty($tgl_mulai) || empty($tgl_selesai))
			return null;

		$start = new DateTime($tgl_mulai);
		$end = new DateTime($tgl_selesai);
		$interval = $start->diff($end);
		return ($interval->days)+1;
	}
	public function intervalYearOnly($val)
	{
		if(empty($val))
			return null;

		$date1 = date_create($val);
		$date2 = date_create(date('Y-m-d'));
		$interval = date_diff($date1, $date2);
		$year = $interval->y;
		return $year;
	}

	public function checkHariLibur($date,$usage = 'date')
	{
		if(empty($date))
		return null;

		$libur = null;
		$cek_master_libur = $this->CI->model_master->cekHariLibur($date,'date');
		if(empty($cek_master_libur)){
			if(date('D',strtotime($date)) == 'Sun'){
				$libur = 'Minggu';
			}
		}else{
			$libur = $cek_master_libur;
		}
		return $libur;
    }
    
	public function checkHariLiburActive($date,$usage = 'date')
	{
		if(empty($date))
		return null;

		$libur = null;
		$cek_master_libur = $this->CI->model_master->cekHariLiburActive($date,'date');
		if(empty($cek_master_libur)){
			if(date('D',strtotime($date)) == 'Sun'){
				$libur = 'Minggu';
			}
		}else{
			$libur = $cek_master_libur;
		}
		return $libur;
	}
	public function pembulatanDepanKoma($val,$num = 2)
	{
		$round = ceil($val);
		// $round = round($val);
		$strstring1 = substr($round,0,-2);
		$strstring2 = substr($round,-2);
		$exval = (float)$strstring1.'.'.$strstring2;

		$nol = '';
		for ($i=1; $i <= $num; $i++) { 
			$nol = $nol.'0';
		}
		$new_val = ceil($exval).$nol;
		// $new_val = round($exval).$nol;
        if($new_val == '000'){
            $new_val = null;
        }
		return $new_val;
	}
	public function nonPembulatan($val,$num = 2)
	{
		$new_val = ($val);
		return $new_val;
	}
	public function pembulatanFloor($val)
	{
		$new_val = floor($val);
        $nominal=$this->CI->formatter->getFormatMoneyUserReq($new_val);
		return $nominal;
	}

	public function getInterval($date1,$date2)
	{
		$awal  = date_create($date1);
		$akhir = date_create($date2);
		$diff  = date_diff($awal,$akhir);
		return $diff;
	}

	public function getIntervalJam($date1,$date2)
	{
		$jam = $this->getInterval($date1,$date2);
		return $jam->h;
	}
   public function checkValueAll($val)
   {
   	if(empty($val))
   		return false;

   	$cek_all = false;
   	foreach ($val as $key => $value) {
   		if($value == 'all'){
   			$cek_all = true;
   		}
   	}
    	return $cek_all;
   }
   
   //Sistem Penggajian
    public function getSistemPenggajianList()
    {
        $pack=[
            'HARIAN'=>'HARIAN',
            'BULANAN'=>'BULANAN'
        ];
        return $pack;
    }
    //============================== list menu USER ======================================
    public function getYourMenuUser($id)
    {
        if (empty($id)) 
            return null;
        $pack=[];
        $admin=$this->CI->model_karyawan->getEmployeeId($id);
        if (!isset($admin)) 
            return null;
        $user_group=$this->CI->model_master->getUserGroupUser($admin['id_group_user']);
        if (!isset($user_group)) 
            return null;
        $ex=explode(';',$user_group['list_id_menu']);
        if (!isset($ex)) 
            return null;
        foreach ($ex as $e) {
            $menu=$this->CI->model_master->getMenuUser($e);
            if (isset($menu)) {
				if ($menu['parent'] || ($menu['parent'] == 0 && $menu['sequence'] == 1)) {
					if ($menu['url'] != '#' && $menu['status'] == 1) {
						$ex1=$this->getParseOneLevelVar($menu['sub_url']);
						if (isset($ex1)) {
							foreach ($ex1 as $e1) {
								array_push($pack,$e1);
							}
						}
						array_push($pack,$menu['url']);
					}
				}
            }
        }
        return array_values(array_unique($pack));
    }
    public function getYourMenuUserId($id)
    {
        if (empty($id)) 
            return null;
        $pack=[];
        $admin=$this->CI->model_karyawan->getEmployeeId($id);
        if (!isset($admin)) 
            return null;
        $user_group=$this->CI->model_master->getUserGroupUser($admin['id_group_user']);
        if (!isset($user_group)) 
            return null;
        $ex=explode(';',$user_group['list_id_menu']);
        if (!isset($ex)) 
            return null;
        $pack=$ex;
        return $pack;
    }
    public function getDrawMenuUser($your_menu,$data,$parent,$url)
    {
        if (empty($data)) 
        return null;
        $new_val = null;
        foreach ($data as $d){
            if ($d->parent == $parent){
                if (in_array($d->id_menu,$your_menu)) {
                    if ($this->getChildren($data,$d->id_menu)) {
                        $url_act=$this->getParseOneLevelVar($d->sub_url);
                        if (in_array($url,$url_act)) {
                            $class=' class="treeview active"';
                        }else{
                            $class=' class="treeview"';
                        }
                        $name='<i class="fa '.$d->icon.'"></i> <span>'.$d->nama.'</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>';
                        $urlx='#';
                    }else{
                        $url_act=$this->getParseOneLevelVar($d->sub_url);
                        if (in_array($url,$url_act)) {
                            $class=' class="active"';
                        }else{
                            if ($url == $d->url) {
                                $class=' class="active"';
                            }else{
                                $class=null;
                            }
                        }
                        $name='<i class="'.$d->icon.'"></i> <span>'.$d->nama.'</span>';
                        $urlx=$d->url;
                    }
                    $new_val.= '<li '.$class.' style="white-space: normal; overflow-wrap: break-word;"><a href="'.base_url('kpages/'.$urlx).'" title="'.$d->nama.'">'.$name.'</a>';
                    if ($this->getChildren($data,$d->id_menu))                                   
                    $new_val.= '<ul class="treeview-menu">'.$this->getDrawMenuUser($your_menu,$data,$d->id_menu,$url).'</ul>'; 
                    $new_val.= "</li>";   
                }               
            }
        }
        return $new_val;
    }
    // ======================== ACCESS USER =================
    public function getAllAccessUser()
    {
        $access=$this->CI->model_master->getListAccess(true);
        $pack=[];
        foreach ($access as $a) {
            $pack[strtolower($a->kode_access)]=strtoupper($a->kode_access);
        }
        return $pack;
    }
    public function getYourAccessUser($id)
    {
        if (empty($id)) 
            return null;
        $pack=[];
        $admin=$this->CI->model_karyawan->getEmpID($id);
        if (!isset($admin)) 
            return null;
        $user_group=$this->CI->model_master->getUserGroupUser($admin['id_group_user']);
        if (!isset($user_group)) 
            return null;
        $ex=explode(';',$user_group['list_access']);
        if (!isset($ex)) 
            return null;
        foreach ($ex as $e) {
            $acc=$this->CI->model_master->getAccess($e);
            if (isset($acc)) {
                foreach ($acc as $d) {
                  array_push($pack,$d->kode_access);
                }
            }
        }
        return $pack;
    }
    public function getLevelAdminList($val=null)
    {
		if($val == null){
            $pack=[
                0=>'Level Tertinggi',
                1=>'Level 1',
                2=>'Level 2',
                3=>'Level 3',
                4=>'Level 4',
            ];
        }
		if($val != null){
			$pack=[
				1=>'Level 1',
				2=>'Level 2',
				3=>'Level 3',
                4=>'Level 4',
			];
		}
        return $pack;
    }
    public function getLevelAdmin($key)
    {
        return $this->getVarFromArrayKey($key,$this->getLevelAdminList());
    }
    public function getDataExplode($val,$sep,$param)
    {
        if(empty($val) || empty($sep) || empty($param)) 
            return null;
        $ex=explode($sep,$val);
        $new_val=null;
        if (!isset($ex))
            return null;
		if ($param == 'start') {
			$new_val=$ex[0];
		}elseif ($param == 'end') {
			$new_val=$ex[1];
		}elseif ($param == '3') {
			$new_val=(isset($ex[2])?$ex[2]:null);
		}elseif ($param == '4') {
			$new_val=(isset($ex[3])?$ex[3]:null);
		}elseif ($param == '5') {
			$new_val=(isset($ex[4])?$ex[4]:null);
		}elseif ($param == 'all') {
			$new_val=$ex;
		}else{
			return null;
		}
        return $new_val;
    }
	public function packingArray($arr,$param = ';')
	{
		if (empty($arr))
			return null;		
		$new_val=array_values(array_filter(array_unique($arr)));
		return implode($param,$new_val);
	}
    public function batasi_kata($kalimat_lengkap, $jumlah_kata){
        $arr_str = explode(' ', $kalimat_lengkap);
        $arr_str = array_slice($arr_str, 0, $jumlah_kata );
        return implode(' ', $arr_str);
    }
    public function getStatusPesan()
    {
        $pack=[
            0=>'<label class="label label-success" style="font-size:14px;"><i class="fa fa-check-circle"></i> Dibaca</label>',
            1=>'<label class="label label-default" style="font-size:14px;"><i class="fa fa-check-circle"></i> Terkirim</label>',
        ];
        return $pack;
    }
    public function getStatusPesanKey($key)
    {
        return $this->getVarFromArrayKey($key,$this->getStatusPesan());
    }
    public function getKeyStatusPerdin()
    {
        $pack=[
            1=>'<label class="label label-default" style="font-size:14px;"><i class="fa fa-times-circle"></i> Belum Divalidasi</label>',
            2=>'<label class="label label-warning" style="font-size:14px;"><i class="fa fa-refresh fa-spin"></i> On Progress</label>',
            3=>'<label class="label label-success" style="font-size:14px;"><i class="fa fa-check-circle"></i> Selesai</label>',
        ];
        return $pack;
    }
    public function getStatusPerdin($key)
    {
        return $this->getVarFromArrayKey($key,$this->getKeyStatusPerdin());
    }
    public function getKeyStatusPerdinRekap()
    {
        $pack=[
            1=>'Belum Divalidasi',
            2=>'On Progress',
            3=>'Selesai',
        ];
        return $pack;
    }
    public function getStatusPerdinRekap($key)
    {
        return $this->getVarFromArrayKey($key,$this->getKeyStatusPerdinRekap());
    }
    public function getEmailValLembur(){
        $IDaccValLembur=$this->CI->model_master->getAccessValLembur('VAL_LEMBUR')['id_access'];
        $userGroup=$this->CI->model_master->getListUserGroupValLembur();
		$idAccess=[];
        foreach ($userGroup as $ug) {
			$listAccess=$this->getDataExplode($ug->list_access,';','all');
			if(in_array($IDaccValLembur,$listAccess)){
				$idAccess[]=$ug->id_group;
			}
		}
		$emailValLembur=[];
		foreach ($idAccess as $id => $val_user_group) {
			$getAdmin=$this->CI->model_master->getListAdminValLembur($val_user_group);
			foreach ($getAdmin as $ga) {
				$emailValLembur[]=$ga->email;
			}
        }
        return $emailValLembur;
    }
    public function getEmailValPerjalananDinas(){
        $IDaccValLembur=$this->CI->model_master->getAccessValLembur('VAL_PERDIN')['id_access'];
        $userGroup=$this->CI->model_master->getListUserGroupValLembur();
		$idAccess=[];
        foreach ($userGroup as $ug) {
			$listAccess=$this->getDataExplode($ug->list_access,';','all');
			if(in_array($IDaccValLembur,$listAccess)){
				$idAccess[]=$ug->id_group;
			}
		}
		$emailValLembur=[];
		foreach ($idAccess as $id => $val_user_group) {
			$getAdmin=$this->CI->model_master->getListAdminValLembur($val_user_group);
			foreach ($getAdmin as $ga) {
				$emailValLembur[]=$ga->email;
			}
        }
        return $emailValLembur;
    }
    public function getKaryawanViewEmail($id_kar){
        $data_kar='';
        $data_kar.='<ol>';
        foreach ($id_kar as $kar) {
            $data_kar.='<li>'.$this->CI->model_karyawan->getEmpID($kar)['nama'].' - '.$this->CI->model_karyawan->getEmpID($kar)['nama_jabatan'].'</li>';
        }
        $data_kar.='</ol>';
        return $data_kar;
    }
    public function getKaryawanViewPrint($id_kar){
        if(empty($id_kar))
            return null;
        $data_kar='';
        $no=1;
        foreach ($id_kar as $kar) {
            $data_kar.=$no.'. '.$this->CI->model_karyawan->getEmpID($kar)['nama'].' - '.$this->CI->model_karyawan->getEmpID($kar)['nama_jabatan'].'<w:br/>';
            $no++;
        }
        return $data_kar;
    }
    public function getKodeAKunViewPrint($kodeAkun){
        if(empty($kodeAkun))
            return null;
        $data_akun='';
        $no=1;
        foreach ($kodeAkun as $aa) {
            $data_akun.=$no.'. '.$aa->kode_akun.' ('.$aa->nama_akun.') - '.$this->CI->formatter->getFormatMoneyUser($aa->nominal).' - '.$aa->keterangan.'<w:br/>';
            $no++;
        }
        return $data_akun;
    }
    public function getStringInterval($time){
		if (!$time)
			return null;
		$lama=null;
		$data=explode(':',$time);
		if ($data){
			$jam=$data[0];
			$menit=$data[1];
			$detik=(isset($data[2]))?$data[2]:'00';
			$lama=(($jam!='00')?$jam.' Jam, ':null).(($menit!='00')?$menit.' Menit ':null).(($detik!='00')?$detik.' Detik':null);
		}
        return $lama;
    }
    public function getJenisLembur()
    {
        $pack=[
            'LKN'=>'Lembur di Hari Kerja Normal',
            'LHL'=>'Lembur di Hari Libur',
            'LJI'=>'Lembur di Jam Istirahat',
            'LJIL'=>'Lembur Libur di Jam Istirahat',
        ];
        return $pack;
    }
    public function getJenisLemburKey($key)
    {
        return $this->getVarFromArrayKey($key,$this->getJenisLembur());
    }
    public function getPilihanLembur()
    {
        $pack=[
            'lembur_project'=>'Lembur Project',
            'lembur_non_project'=>'Lembur Non Project',
        ];
        return $pack;
    }
    public function getPilihanLemburKey($key)
    {
        return $this->getVarFromArrayKey($key,$this->getPilihanLembur());
    }
    public function getStrReplace($rplc,$val,$data)
    {
        if(empty($data) || empty($val) || empty($rplc)) 
            return null;
        $ex=str_replace($rplc,$val,$data);
        if (!isset($ex))
            return null;
        $new_val=$ex;
        return $new_val;
    }
	public function checkContentLetter($var,$lenght = 15,$char='_')
	{
		$empty=null;
		for ($i=0; $i < $lenght ; $i++) { 
			$empty.=$char;
		}
		return (($var && $var != '')?$var:$empty);
	}
    public function getStatusRestore()
    {
        $pack=[
            0=>'<label class="label label-warning" style="font-size:14px;"><i class="fa fa-refresh fa-spin"></i> Belum Di Restore</label>',
            1=>'<label class="label label-success" style="font-size:14px;"><i class="fa fa-check-circle"></i> Sudah Di Restore</label>',
        ];
        return $pack;
    }
    public function getStatusRestoreKey($key)
    {
        return $this->getVarFromArrayKey($key,$this->getStatusRestore());
    }
    public function JenisPendukungPayroll()
    {
        $pack=[
            'hallo'=>'Kartu Hallo',
            'lainnya'=>'Lainnya',
        ];
        return $pack;
    }
    public function getJenisPendukungPayroll($key)
    {
        return $this->getVarFromArrayKey($key,$this->JenisPendukungPayroll());
    }
	public function getJenisPresensiList()
	{
		$pack=[
			'IJIN'=>'Ijin',
			'TELAT'=>'Terlambat',
			'BOLOS'=>'Bolos',
			'SP'=>'SP',
		];
		return $pack;
	}
	public function getJenisPresensi($key)
	{
		return $this->getVarFromArrayKey($key,$this->getJenisPresensiList());
	}
//property table
	public function unsetArrayValue($arr,$val)
	{
		$ret=$arr;
		if (is_array($arr)) {
			if (($key = array_search($val, $arr)) !== false) {
				unset($arr[$key]);
			}
		}
		return $arr;
    }
    public function generateNotifikasi($data)
    {
        $kar=[];
        $emp=$this->CI->model_karyawan->getListEmployeeActive();
        foreach ($emp as $k_em=>$v_em) {
            $kar[]=$k_em;
        }
        $kode = $this->CI->codegenerator->kodeNotif();
        $data_id_for=['id_for'=>implode(';', $kar)];
        $isi = '<p><b><i>Dear All,<u></u></i></b></p><p><b></b>Diberitahukan Kepada Seluruh Karyawan Bahwa Ada '.$data['jenis'].' Baru Dengan Nama&nbsp;<b>'.$data['judul'].'</b>, Diharapkan Anda Mengisi Nilai Sebelum Tanggal '.$this->CI->formatter->getDateTimeMonthFormatUser($data['end_date']).' WIB, Anda Dapat Mengisi Nilai Pada Menu&nbsp;<b>Penilaian &gt; Input Penilaian Sikap&nbsp;</b>atau Dengan Menuju Link Berikut <b><a href="'.$data['link'].'">'.$data['judul'].'</a></b>, Jika <b>Tidak Ada</b> List Karyawan maka anda tidak ada keharusan untuk melakukan penilaian.</p>';
        $datax = [
            'kode_notif'=>$kode,
            'judul'=>$data['judul'],
            'isi'=>$isi,
            'start'=>$data['start'],
            'end_date'=>$data['end_date'],
            'kode'=>null,
            'sifat'=>1,
            'tipe'=>'info',
            'untuk'=>'FO',
        ];
        $datax=array_merge($datax, $data_id_for, $this->CI->model_global->getCreateProperties($data['admin']));
        $datar=$this->CI->model_global->insertQueryCC($datax,'notification',$this->CI->model_master->checkNotifCode($kode));
        return $datar;
    }
    public function getFotoValueNonKar()
    {
        $val = 'asset/img/user-photo/user.png';
        return $val;
    }
    public function getAbjadFromNumber($number)
    {
        if(empty($number)) 
            return null;
        $return = null;
        switch($number){
            case '1' : $return = 'A'; break;
            case '2' : $return = 'B'; break;
            case '3' : $return = 'C'; break;
            case '4' : $return = 'D'; break;
            case '5' : $return = 'E'; break;
            case '6' : $return = 'F'; break;
            case '7' : $return = 'G'; break;
            case '8' : $return = 'H'; break;
            case '9' : $return = 'I'; break;
            case '10' : $return = 'J'; break;
            case '11' : $return = 'K'; break;
            case '12' : $return = 'L'; break;
            case '13' : $return = 'M'; break;
            case '14' : $return = 'N'; break;
            case '15' : $return = 'O'; break;
            case '16' : $return = 'P'; break;
            case '17' : $return = 'Q'; break;
            case '18' : $return = 'R'; break;
            case '19' : $return = 'S'; break;
            case '20' : $return = 'T'; break;
            case '21' : $return = 'U'; break;
            case '22' : $return = 'V'; break;
            case '23' : $return = 'W'; break;
            case '24' : $return = 'X'; break;
            case '25' : $return = 'Y'; break;
            case '26' : $return = 'Z'; break;
            case '27' : $return = 'AA'; break;
            case '28' : $return = 'AB'; break;
            case '29' : $return = 'AC'; break;
            case '30' : $return = 'AD'; break;
            case '31' : $return = 'AE'; break;
            case '32' : $return = 'AF'; break;
            case '33' : $return = 'AG'; break;
            case '34' : $return = 'AH'; break;
            case '35' : $return = 'AI'; break;
            case '36' : $return = 'AJ'; break;
            case '37' : $return = 'AK'; break;
            case '38' : $return = 'AL'; break;
            case '39' : $return = 'AM'; break;
            case '40' : $return = 'AN'; break;
            case '41' : $return = 'AO'; break;
            case '42' : $return = 'AP'; break;
            case '43' : $return = 'AQ'; break;
            case '44' : $return = 'AR'; break;
            case '45' : $return = 'AS'; break;
            case '46' : $return = 'AT'; break;
            case '47' : $return = 'AU'; break;
            case '48' : $return = 'AV'; break;
            case '49' : $return = 'AW'; break;
            case '50' : $return = 'AX'; break;
            case '51' : $return = 'AY'; break;
            case '52' : $return = 'AZ'; break;
            case '53' : $return = 'BA'; break;
            case '54' : $return = 'BB'; break;
            case '55' : $return = 'BC'; break;
            case '56' : $return = 'BD'; break;
            case '57' : $return = 'BE'; break;
            case '58' : $return = 'BF'; break;
            case '59' : $return = 'BG'; break;
            case '60' : $return = 'BH'; break;
            case '61' : $return = 'BI'; break;
            case '62' : $return = 'BJ'; break;
            case '63' : $return = 'BK'; break;
            case '64' : $return = 'BL'; break;
            case '65' : $return = 'BM'; break;
            case '66' : $return = 'BN'; break;
            case '67' : $return = 'BO'; break;
            case '68' : $return = 'BP'; break;
            case '69' : $return = 'BQ'; break;
            case '70' : $return = 'BR'; break;
            case '71' : $return = 'BS'; break;
            case '72' : $return = 'BT'; break;
            case '73' : $return = 'BU'; break;
            case '74' : $return = 'BV'; break;
            case '75' : $return = 'BW'; break;
            case '76' : $return = 'BX'; break;
            case '77' : $return = 'BY'; break;
            case '78' : $return = 'BZ'; break;
            case '79' : $return = 'CA'; break;
            case '80' : $return = 'CB'; break;
            case '81' : $return = 'CC'; break;
            case '82' : $return = 'CD'; break;
            case '83' : $return = 'CE'; break;
            case '84' : $return = 'CF'; break;
            case '85' : $return = 'CG'; break;
            case '86' : $return = 'CH'; break;
            case '87' : $return = 'CI'; break;
            case '88' : $return = 'CJ'; break;
            case '89' : $return = 'CK'; break;
            case '90' : $return = 'CL'; break;
            case '91' : $return = 'CM'; break;
            case '92' : $return = 'CN'; break;
            case '93' : $return = 'CO'; break;
            case '94' : $return = 'CP'; break;
            case '95' : $return = 'CQ'; break;
            case '96' : $return = 'CR'; break;
            case '97' : $return = 'CS'; break;
            case '98' : $return = 'CT'; break;
            case '99' : $return = 'CU'; break;
            case '100' : $return = 'CV'; break;
            default : $return = $number; break;
        }
        return $return;
    }
    public function getNumberToAbjadList()
    {
        $pack=[
            '0'=>'Normal',
            '1'=>'Pertama',
            '2'=>'Kedua',
            '3'=>'Ketiga',
            '4'=>'Keempat',
            '5'=>'Kelima',
            '6'=>'Keenam',
            '7'=>'Ketujuh',
            '8'=>'Kedelapan',
            '9'=>'Kesembilan',
            '10'=>'Kesepuluh',
            '11'=>'Kesebelas',
            '12'=>'kedua belas',
            '13'=>'Ketiga belas',
            '14'=>'Keempat belas',
            '15'=>'Kelima belas',
        ];
        return $pack;
    }
    public function getNumberToAbjad($key)
    {
        return $this->getVarFromArrayKey($key,$this->getNumberToAbjadList());
    }
    public function getWFHList()
    {
        $pack=[
            'wfh'=>'WFH',
            'non_wfh'=>'NON WFH',
        ];
        return $pack;
    }
    public function getWFH($key)
    {
        return $this->getVarFromArrayKey($key,$this->getWFHList());
    }
	public function getDataPresensiIzinCuti($id_karyawan,$tanggal_mulai,$tanggal_selesai)
	{
		$terlambat = 0;
		$plgcepat = 0;
		$notin = 0;
		$liburx = 0;
		$notFingerOut = 0;
		$notFingerIn = 0;
		$presensi_hadir=0;
		$lemburLibur=0;
		$terlambatLemburLibur=0;
        $countCuti=0;
        $dataMaster=[];
		$date_loop=$this->CI->formatter->dateLoopFull($tanggal_mulai,$tanggal_selesai);
		foreach ($date_loop as $tanggal) {
			$presensi = $this->CI->model_karyawan->getListPresensiId(null,['pre.id_karyawan'=>$id_karyawan,'pre.tanggal'=>$tanggal],null,'row');
			$jam_mulai = (empty($presensi['jam_mulai_shift'])) ? '' : $presensi['jam_mulai_shift'];
			$jam_selesai = (empty($presensi['jam_selesai_shift'])) ? '' : $presensi['jam_selesai_shift'];
			$libur = $this->checkHariLiburActive($tanggal);
			if(empty($libur)){
				if(empty($presensi['jam_mulai']) && empty($presensi['jam_selesai']) && empty($presensi['kode_hari_libur']) && empty($presensi['kode_ijin'])){
					$notin += 1;
				}
			}else{
				if(!empty($presensi['jam_mulai']) && !empty($presensi['jam_selesai']) && !empty($presensi['kode_hari_libur'])){
					$lemburLibur += 1;
					if(!empty($presensi['jam_mulai']) && !empty($presensi['jam_mulai_shift'])){
						if($presensi['jam_mulai'] > $presensi['jam_mulai_shift']){
							$terlambatLemburLibur +=1;
						}
					}
				}
			}
			if(empty($presensi)){
				if(isset($libur)){
					$liburx += 1;
				}
			}else{
                if(!empty($presensi['kode_hari_libur'])){
					$liburx += 1;
                }
				if(!empty($presensi['jam_mulai']) && !empty($presensi['jam_mulai_shift'])){
					if($presensi['jam_mulai'] > $presensi['jam_mulai_shift']){
						$terlambat +=1;
					}
				}
				if(!empty($presensi['jam_selesai']) && !empty($presensi['jam_selesai_shift'])){
					if($presensi['jam_selesai'] < $presensi['jam_selesai_shift']){
						$plgcepat +=1;
					}
				}
				if(!empty($presensi['jam_mulai']) && !empty($presensi['jam_selesai'])){
					$presensi_hadir+=1;
				}
				if(!empty($presensi['jam_mulai']) && empty($presensi['jam_selesai'])){
					$notFingerOut+=1;
				}
				if(empty($presensi['jam_mulai']) && !empty($presensi['jam_selesai'])){
					$notFingerIn+=1;
				}
			}
            $dataIzin = $this->CI->model_karyawan->getIzinCuti(null,['a.id_karyawan'=>$id_karyawan,'a.tgl_mulai'=>$tanggal],'row');
            if(!empty($dataIzin)){
                if($dataIzin['jenis_ic'] == 'C' && $dataIzin['potong_cuti'] == 1){
                    $countCuti += 1;
                }
                $masterIzin = $this->CI->model_master->getMasterIzin(null,['a.jenis'=>'I']);
                foreach ($masterIzin as $mi) {
                    $jumizin='';
                    if($dataIzin['jenis'] == $mi->kode_master_izin){
                        $izinx = $this->CI->model_karyawan->getIzinCuti(null,['a.id_karyawan'=>$id_karyawan,'a.tgl_mulai'=>$tanggal,'a.jenis'=>$mi->kode_master_izin]);
                        $jumizin .= count($izinx);
                    }
                    $dataMaster[$mi->kode_master_izin][] = $jumizin;
                }
            }
        }
        $dm = [];
        foreach ($dataMaster as $key => $value) {
            $dm[$key] = array_sum($value);
        }
        $dataPre = ['terlambat'=>$terlambat,'plgcepat'=>$plgcepat,'alpa'=>$notin,'libur'=>$liburx,'presensi_hadir'=>$presensi_hadir,'notFingerOut'=>$notFingerOut,'notFingerIn'=>$notFingerIn,'countCuti'=>$countCuti];
        $datax = ['presensi'=>$dataPre, 'izin'=>$dm];
        return $datax;
	}
    public function listWeek()
    {
        $pack=[
            1=>'Minggu 1',
            2=>'Minggu 2',
            3=>'Minggu 3',
            4=>'Minggu 4',
            5=>'Minggu 5',
        ];
        return $pack;
    }
    public function getlistWeek($key)
    {
        return $this->getVarFromArrayKey($key,$this->listWeek());
    }
    public function listWeekRitasi()
    {
        $pack=[
            '1'=>'Minggu 1',
            '2'=>'Minggu 2',
            '3'=>'Minggu 3',
            '4'=>'Minggu 4',
            '5'=>'Minggu 5',
            '6'=>'Minggu 1 Rit Luar Plant',
            '7'=>'Minggu 2 Rit Luar Plant',
            '8'=>'Minggu 3 Rit Luar Plant',
            '9'=>'Minggu 4 Rit Luar Plant',
            '10'=>'Minggu 5 Rit Luar Plant',
            
        ];
        return $pack;
    }
    public function getlistWeekRitasi($key)
    {
        return $this->getVarFromArrayKey($key,$this->listWeekRitasi());
    }
    public function getPenunjangList()
    {
        $pack=[
            // 'pph'=>'PPh',
            'premi_asuransi'=>'Premi Asuransi',
            // 'pph_dibayar'=>'PPH 21 YANG DIBAYAR',
            // 'pph_dipotong'=>'PPH 21 YANG DIPOTONG',
            'pph_tunjangan'=>'Tunjangan PPh',
        ];
        return $pack;
    }
    public function getListPenunjangNon()
    {
        $pack=[
            // 'pph'=>'PPh',
            // 'premi_asuransi'=>'Premi Asuransi',
            // 'pph_dibayar'=>'PPH 21 YANG DIBAYAR',
            'pph_dipotong'=>'PPH 21 YANG DIPOTONG',
            'pph_tunjangan'=>'Tunjangan PPh',
        ];
        return $pack;
    }
    public function getPenunjang($key)
    {
        return $this->getVarFromArrayKey($key,$this->getPenunjangList());
    }
    public function getJenisPerhitunganPajak()
    {
        $pack=[
            'PTKP'=>'PTKP',
            'NON_PTKP'=>'NON PTKP',
        ];
        return $pack;
    }
    public function getJenisPerhitunganPajakKey($key)
    {
        return $this->getVarFromArrayKey($key,$this->getJenisPerhitunganPajak());
    }
    public function getKoreksi()
    {
        $pack=[
            '0'=>'',
            '1'=>'Pembetulan 1',
            '2'=>'Pembetulan 2',
            '3'=>'Pembetulan 3',
            '4'=>'Pembetulan 4',
            '5'=>'Pembetulan 5',
            '6'=>'Pembetulan 6',
        ];
        return $pack;
    }
    public function getKoreksiKey($key)
    {
        return $this->getVarFromArrayKey($key,$this->getKoreksi());
    }
    public function getJenisRekapLembur()
    {
        $pack=[
            '0'=>'Tanpa Rupiah',
            '1'=>'Dengan Rupiah',
        ];
        return $pack;
    }
    public function getJenisRekapLemburKey($key)
    {
        return $this->getVarFromArrayKey($key,$this->getJenisRekapLembur());
    }
	public function getBulanTahunAngsuran($mulai, $lama)
	{
		if (empty($mulai) || empty($lama)) 
			return null;
		$data = [];
		for ($i=0; $i < $lama; $i++) { 
			$nextN = mktime(0, 0, 0, $mulai + $i, date("d"), date("Y"));
			$tgl = date("d/m/Y", $nextN);
			$bulan = $this->getDataExplode($tgl,'/','end');
			$tahun = $this->getDataExplode($tgl,'/','3');
			$data[$i+1] = $this->CI->formatter->getNameOfMonth($bulan).' '.$tahun;
		}
		return $data;
	}
    public function getAktifUangMakan()
    {
        $pack=[
            '0'=>'Tidak Sesuai Grade',
            '1'=>'Sesuai Grade Karyawan',
        ];
        return $pack;
    }
    public function getAktifUangMakanKey($key)
    {
        return $this->getVarFromArrayKey($key,$this->getAktifUangMakan());
    }
	public function getMasaKerja($start,$end,$out='plain')
	{
		if (empty($start) || empty($end)) 
			return null;
		$start=$this->CI->formatter->getDateFormatDb($start);
		$end=$this->CI->formatter->getDateFormatDb($end);
		$start  = new DateTime($start);
		$end = new DateTime($end);
		$diff  = $start->diff($end);
		$val='';
		if (!empty($diff->y)) {
			$val.=$diff->y.' Tahun ';
		}
		if (!empty($diff->m)) {
			$val.=$diff->m.' Bulan';
		}
		if ($out != 'plain') {
			$val=[
				'year'=>$diff->y,
				'month'=>$diff->m,
				'all_month'=>$diff->y*12,
			];
		}else{
			$val=$val;
		}
		return $val;
	}
    public function getTunjanganUMPerdin($value)
    {
		if (empty($value)) 
			return null;
		$um = $this->getDataExplode($value,';','all');
		$umx = [];
		foreach($um as $wt => $va){
			$xx = $this->getDataExplode($va,':','all');
			$umx[$xx[0]]=$xx[1];
		}
		return $umx;
    }
    public function getDataTunjanganPerdin($kode, $value)
    {
		if (empty($kode) || empty($value)) 
			return null;
		$um = $this->getDataExplode($kode,';','all');
		$nom = $this->getDataExplode($value,';','all');
		$umx = [];
		foreach($um as $wt => $va){
			$umx[$va]=$nom[$wt];
		}
		return $umx;
    }
	public function max_month_agenda($agenda)
	{
		$ret=$this->column_value_max_range();
		if (!$agenda)
			return $ret;
		if (is_array($agenda)) {
			if (isset($agenda['start']) && isset($agenda['end'])){
				$ret=$agenda['end']-$agenda['start']+1;
			}
		}
		if (isset($agenda[0])) {
			if (isset($agenda[0]->start) && isset($agenda[0]->end)){
				$ret=$agenda[0]->end-$agenda[0]->start+1;
			}
		}
		return $ret;
	}
    public function getStatusKaryawanList()
    {
        $pack=[
            // 0=>'<label class="label label-danger"> Tidak Aktif</label>',
            // 0=>'<small class="text-muted"><font color="red"> Nonaktif</font></small>',
            0=>'Nonaktif',
            1=>'Aktif',
            2=>'Belum Di nonaktifkan',
            3=>'Sudah Aktif Kembali / ada data lain'
        ];
        return $pack;
    }
    public function getStatusKaryawan($key)
    {
        return $this->getVarFromArrayKey($key,$this->getStatusKaryawanList());
	}
    public function getAllKaryawanYear($tahun)
    {
		$karyawan = $this->CI->model_karyawan->getEmployeeAllActive2("h.status_emp = '1'");
		$dataNon = $this->CI->model_karyawan->getKaryawanNonAktifWhere2("YEAR(tgl_keluar)='$tahun'");
		$data = array_merge($karyawan, $dataNon);
        return $data;
	}
    
	public function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	public function cutText($text, $length, $mode = 2)
	{
		if ($mode != 1)
		{
			if(!empty($text[$length])){
				$char = $text[$length - 1];
				switch($mode)
				{
					case 2: 
						while($char != ' ') {
							$char = $text[--$length];
						}
					case 3:
						while($char != ' ') {
							$char = $text[++$length];
						}
				}
			}
		}
		return substr($text, 0, $length);
	}
	public function addNewJadwalKerja($kode_master_shift, $tgl_berlaku, $id_karyawan, $admin)
    {
		$cek_hari=[];
		if (isset($kode_master_shift)) {
			foreach ($kode_master_shift as $kms) {
				$data_shift=$this->CI->model_master->getMasterShiftKode($kms);
				if (isset($data_shift)) {
					$cek_hari[$kms]=$this->getParseOneLevelVar($data_shift['hari']);
				}
			}
		}
        $tanggal=$this->CI->formatter->getPeriodeJadwal($tgl_berlaku,$cek_hari);
        foreach ($tanggal as $k_y=>$y) {
            foreach ($y as $k_m=>$m) {
                if ($k_m < 10) {
                    $k_m = str_replace('0', '', $k_m);
                }
                $cek_data=$this->CI->model_karyawan->cekJadwalKerjaIdK($id_karyawan,$k_m,$k_y);
                if($cek_data == null){
                    $data=[
                        'id_karyawan'=>$id_karyawan,
                        'bulan'=>$k_m,
                        'tahun'=>$k_y,
                    ];
                    foreach ($m as $day => $kode_shift) {
                        $col=$this->CI->formatter->getCols2($day);
                        $data[$col]=$kode_shift;
                        if($kode_shift=='CSTM'){
                            $start=$this->CI->formatter->getColumn($day,'start_');
                            $i_start=$this->CI->formatter->getColumn($day,'i_start_');
                            $i_end=$this->CI->formatter->getColumn($day,'i_end_');
                            $end=$this->CI->formatter->getColumn($day,'end_');
                            $data[$start]=$this->CI->formatter->getTimeDb($jam_masuk);
                            $data[$i_start]=$this->CI->formatter->getTimeDb($istirahat_mulai);
                            $data[$i_end]=$this->CI->formatter->getTimeDb($istirahat_selesai);
                            $data[$end]=$this->CI->formatter->getTimeDb($jam_pulang);
                        }
                    }
                    if (!empty($kode_shift) && !empty($k_m) && !empty($k_y) && !empty($day) && !empty($e)) {
                        $date=$k_y.'-'.$k_m.'-'.$day;
                        $this->CI->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
                    }
                    $datay=array_merge($data,$this->CI->model_global->getCreateProperties($admin));
                    $datax = $this->CI->model_global->insertQuery($datay,'data_jadwal_kerja');
                }else{
                    foreach ($cek_data as $cd) {
                        $data=[
                            'id_karyawan'=>$id_karyawan,
                            'bulan'=>$k_m,
                            'tahun'=>$k_y,
                        ];
                        foreach ($m as $day => $kode_shift) {
                            $col=$this->CI->formatter->getCols2($day);
                            $data[$col]=$kode_shift;
                            if($kode_shift=='CSTM'){
                                $start=$this->CI->formatter->getColumn($day,'start_');
                                $i_start=$this->CI->formatter->getColumn($day,'i_start_');
                                $i_end=$this->CI->formatter->getColumn($day,'i_end_');
                                $end=$this->CI->formatter->getColumn($day,'end_');
                                $data[$start]=$this->CI->formatter->getTimeDb($jam_masuk);
                                $data[$i_start]=$this->CI->formatter->getTimeDb($istirahat_mulai);
                                $data[$i_end]=$this->CI->formatter->getTimeDb($istirahat_selesai);
                                $data[$end]=$this->CI->formatter->getTimeDb($jam_pulang);
                            }
                            if (!empty($kode_shift) && !empty($cd->bulan) && !empty($cd->tahun) && !empty($day) && !empty($e)) {
                                $date=$cd->tahun.'-'.$cd->bulan.'-'.$day;
                                $this->CI->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
                            }
                            $where=['id_karyawan'=>$id_karyawan,'bulan'=>$cd->bulan,'tahun'=>$cd->tahun];
                            $data=array_merge($data,$this->CI->model_global->getCreateProperties($admin));
                            $this->CI->model_global->updateQueryNoMsg($data,'data_jadwal_kerja',$where);
                            $datax=$this->messages->customWarning2('Jadwal Kerja Karyawan Pada Bulan Tersebut Berhasil Diperbarui');
                        }
                    }
                }
            }
        }
    }
	public function implodeToPetugasPayrollNewKar($kode_petugas_payroll, $id_karyawan, $admin)
    {
        if(!empty($kode_petugas_payroll)){
            $idkar[] = $id_karyawan;
            foreach ($kode_petugas_payroll as $k => $kode) {
                $dold = $this->CI->model_master->getListPetugasPayrollWhere(['a.kode_petugas_payroll'=>$kode],null,1,'a.update_date desc',true);
                if(!empty($dold['id_karyawan'])){
                    $expl = $this->getDataExplode($dold['id_karyawan'],';','all');
                    $new = array_merge($idkar, $expl);
                    $newIdkar = implode(';', $new);
                    $data = [
                        'id_karyawan'=>$newIdkar,
                    ];
                    $data=array_merge($data,$this->CI->model_global->getUpdateProperties($admin));
                    $dx = $this->CI->model_global->updateQueryNoMsg($data,'master_petugas_payroll',['kode_petugas_payroll'=>$kode]);
                }else{
                    $dx = false;
                }
            }
        }else{
            $dx = false;
        }
        return $dx;
    }
	public function implodeToPetugasPPHNewKar($kode_petugas_pph, $id_karyawan, $admin)
    {
        if(!empty($kode_petugas_pph)){
            $idkar[] = $id_karyawan;
            foreach ($kode_petugas_pph as $k => $kode) {
                $dold = $this->CI->model_master->getListPetugasPPHWhere(['a.kode_petugas_pph'=>$kode],null,1,'a.update_date desc',true);
                if(!empty($dold['id_karyawan'])){
                    $expl = $this->getDataExplode($dold['id_karyawan'],';','all');
                    $new = array_merge($idkar, $expl);
                    $newIdkar = implode(';', $new);
                    $data = [
                        'id_karyawan'=>$newIdkar,
                    ];
                    $data=array_merge($data,$this->CI->model_global->getUpdateProperties($admin));
                    $dx = $this->CI->model_global->updateQueryNoMsg($data,'master_petugas_pph',['kode_petugas_pph'=>$kode]);
                }else{
                    $dx = false;
                }
            }
        }else{
            $dx = false;
        }
        return $dx;
    }
	public function implodeToPetugasLemburNewKar($kode_petugas_lembur, $id_karyawan, $admin)
    {
        if(!empty($kode_petugas_lembur)){
            $idkar[] = $id_karyawan;
            foreach ($kode_petugas_lembur as $k => $kode) {
                $dold = $this->CI->model_master->getListPetugasLemburWhere(['a.kode_petugas_lembur'=>$kode],null,1,'a.update_date desc',true);
                if(!empty($dold['id_karyawan'])){
                    $expl = $this->getDataExplode($dold['id_karyawan'],';','all');
                    $new = array_merge($idkar, $expl);
                    $newIdkar = implode(';', $new);
                    $data = [
                        'id_karyawan'=>$newIdkar,
                    ];
                    $data=array_merge($data,$this->CI->model_global->getUpdateProperties($admin));
                    $dx = $this->CI->model_global->updateQueryNoMsg($data,'master_petugas_lembur',['kode_petugas_lembur'=>$kode]);
                }else{
                    $dx = false;
                }
            }
        }else{
            $dx = false;
        }
        return $dx;
    }
    // =========================== RESET IZIN CUTI =======================================
    public function getMonthSisaCuti($max=12)
    {
        $arr=[];
        for ($i=1; $i <= 12; $i++) { 
            $arr[$i]=$max;
            $max--;
        }
        return $arr;
    }
    public function getJumlahSisaCuti($date_start,$max=12)
    {
        $ret=0;
        if (empty($date_start)) 
            return $ret;
        $data_sisa=$this->getMonthSisaCuti($max);
        $month=(int)date('m',strtotime($date_start));
        if (isset($data_sisa[$month])) {
            $ret=$data_sisa[$month];
        }
        return $ret;
    }
	public function reset_izin_cuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$emp = $this->model_karyawan->listEmpActive();
		$cutiMax = $this->model_master->getMasterIzinJenis('CUTI_TAHUNAN')['maksimal'];
		foreach ($emp as $e) {
			$now=date('Y-m-d');
			$masa_kerja = $this->formatter->getCountDateRange($e->tgl_masuk,$now)['bulan_pay'];
			if ($masa_kerja >= 24) {         
				$jum_cuti = $cutiMax;
			}elseif ($masa_kerja >= 12 && $masa_kerja < 24) {                        
				$jum_cuti = $cutiMax-$this->model_master->getJumlahSisaCuti($e->tgl_masuk);
				$jum_cuti=($jum_cuti<0)?0:$jum_cuti;
			}else{
				$jum_cuti = 0;
			}
			$data=['sisa_cuti'=>$jum_cuti];
			$where=['id_karyawan'=>$e->id_karyawan];
			$datax=$this->model_global->updateQuery($data,'karyawan',$where);
		}
		echo json_encode($datax);
	}
    public function insertToHistoryResetCuti($flag, $keterangan, $datetime, $tahun = null)
    {
        return $this->CI->model_global->insertQuery(['flag'=>$flag,'keterangan'=>$keterangan,'datetime'=>$datetime,'tahun'=>$tahun],'history_reset_cuti');
    }
    public function syncResetCutiOLD($datetime)
    {
		$date = $this->CI->formatter->getDayMonthYearsHourMinute($datetime);
		if(($date['hari'] == '01' && $date['bulan'] == '01') || ($date['hari'] == '02' && $date['bulan'] == '01') || ($date['hari'] == '03' && $date['bulan'] == '01') || ($date['hari'] == '04' && $date['bulan'] == '01') || ($date['hari'] == '05' && $date['bulan'] == '01') || ($date['hari'] == '06' && $date['bulan'] == '01')){
			$history=$this->CI->model_master->getHistoryResetCuti(['tahun'=>$date['tahun'], 'flag'=>'SYNC JAN']);
			if(empty($history)){
                $emp = $this->CI->model_karyawan->getEmployeeWhere(['emp.status_emp'=>'1']);
                $CutiBersama=$this->CI->model_master->getCutiBersamaTanggal($date['tahun']);
                $jCB = 0;
                if(!empty($CutiBersama)){
                    $jCB = count($CutiBersama);
                }
				foreach ($emp as $e) {
					$now=date('Y-m-d');
					$masa_kerja = $this->CI->formatter->getCountDateRange($e->tgl_masuk,$now)['bulan_pay'];
					if ($masa_kerja > 12) {
                        $sisaCutiDB = (($e->sisa_cuti < 0) ? 0 : $e->sisa_cuti);
						$sisa_cuti = (12-$jCB)+($sisaCutiDB);
						$data = [
							'sc_old' => $sisaCutiDB,
							'sisa_cuti' => $sisa_cuti,
						];
						$datax = $this->CI->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$e->id_karyawan]);
					}else{
						$data = [
							'sc_old' => 0,
							'sisa_cuti' => 0,
						];
						$datax = $this->CI->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$e->id_karyawan]);
					}
				}
				$this->insertToHistoryResetCuti('SYNC JAN', 'Sinkron Data Cuti Berhasil', $datetime, $date['tahun']);
                $datax = 'true';
			}else{
                $datax = 'false';
			}
		}elseif(($date['hari'] == '01' && $date['bulan'] == '07') || ($date['hari'] == '02' && $date['bulan'] == '07') || ($date['hari'] == '03' && $date['bulan'] == '07') || ($date['hari'] == '04' && $date['bulan'] == '07') || ($date['hari'] == '05' && $date['bulan'] == '07') || ($date['hari'] == '06' && $date['bulan'] == '07')){
			$history=$this->CI->model_master->getHistoryResetCuti(['tahun'=>$date['tahun'], 'flag'=>'SYNC JULI']);
			if(empty($history)){
                $emp = $this->CI->model_karyawan->getEmployeeWhere(['emp.status_emp'=>'1']);
                $CutiBersama=$this->CI->model_master->getCutiBersamaTanggal($date['tahun']);
                $jCB = 0;
                if(!empty($CutiBersama)){
                    $jCB = count($CutiBersama);
                }
				$cutiReal = (12-$jCB);
				foreach ($emp as $k) {
					$sisaCutiDB = (($k->sisa_cuti < 0) ? 0 : $k->sisa_cuti);
					$sisaCuti = (($sisaCutiDB >= $cutiReal) ? $cutiReal : $sisaCutiDB);
					$datax = $this->CI->model_global->updateQuery(['sisa_cuti' => $sisaCuti],'karyawan',['id_karyawan'=>$k->id_karyawan]);
				}
				$this->insertToHistoryResetCuti('SYNC JULI', 'Sinkron Data Cuti Juli Berhasil', $datetime, $date['tahun']);
                $datax = 'true';
			}else{
                $datax = 'false';
            }
        }else{
            $datax = 'false';
        }
        return $datax;
    }
    public function syncResetCuti($datetime)
    {
		$date = $this->CI->formatter->getDayMonthYearsHourMinute($datetime);
		if(($date['hari'] == '01' && $date['bulan'] == '01') || ($date['hari'] == '02' && $date['bulan'] == '01') || ($date['hari'] == '03' && $date['bulan'] == '01') || ($date['hari'] == '04' && $date['bulan'] == '01') || ($date['hari'] == '05' && $date['bulan'] == '01') || ($date['hari'] == '06' && $date['bulan'] == '01')){
			$history=$this->CI->model_master->getHistoryResetCuti("tahun='".$date['tahun']."' AND flag='SYNC JAN' AND status='1'");
			if(empty($history)){
                $emp = $this->CI->model_karyawan->getEmployeeWhere(['emp.status_emp'=>'1']);
                $CutiBersama=$this->CI->model_master->getCutiBersamaTanggal($date['tahun']);
                $jCB = 0;
                if(!empty($CutiBersama)){
                    $jCB = count($CutiBersama);
                }
				foreach ($emp as $e) {
					$now=date('Y-m-d');
					$masa_kerja = $this->CI->formatter->getCountDateRange($e->tgl_masuk,$now)['bulan_pay'];
					if ($masa_kerja > 12) {
                        $sisaCutiDB = (($e->sisa_cuti < 0) ? 0 : $e->sisa_cuti);
						$sisa_cuti = (12-$jCB)+($sisaCutiDB);
						$data = [
							'sc_old' => $sisaCutiDB,
							'sisa_cuti' => $sisa_cuti,
						];
						$datax = $this->CI->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$e->id_karyawan]);
					}else{
						$data = [
							'sc_old' => 0,
							'sisa_cuti' => 0,
						];
						$datax = $this->CI->model_global->updateQuery($data,'karyawan',['id_karyawan'=>$e->id_karyawan]);
					}
				}
                $this->model_global->updateQuery(['status'=>'0'],'history_reset_cuti',['tahun'=>($date['tahun']-1), 'flag'=>'SYNC JULI']);
				$this->insertToHistoryResetCuti('SYNC JAN', 'Sinkron Data Cuti Berhasil', $datetime, $date['tahun']);
                $datax = 'true';
			}else{
                $datax = 'false';
			}
		}elseif(($date['hari'] == '01' && $date['bulan'] == '07') || ($date['hari'] == '02' && $date['bulan'] == '07') || ($date['hari'] == '03' && $date['bulan'] == '07') || ($date['hari'] == '04' && $date['bulan'] == '07') || ($date['hari'] == '05' && $date['bulan'] == '07') || ($date['hari'] == '06' && $date['bulan'] == '07')){
			$history=$this->CI->model_master->getHistoryResetCuti("tahun='".$date['tahun']."' AND flag='SYNC JULI' AND status='1'");
			if(empty($history)){
                $emp = $this->CI->model_karyawan->getEmployeeWhere(['emp.status_emp'=>'1']);
                $CutiBersama=$this->CI->model_master->getCutiBersamaTanggal($date['tahun']);
                $jCB = 0;
                if(!empty($CutiBersama)){
                    $jCB = count($CutiBersama);
                }
				$cutiReal = (12-$jCB);
				foreach ($emp as $k) {
					$sisaCutiDB = (($k->sisa_cuti < 0) ? 0 : $k->sisa_cuti);
					$sisaCuti = (($sisaCutiDB >= $cutiReal) ? $cutiReal : $sisaCutiDB);
					$datax = $this->CI->model_global->updateQuery(['sisa_cuti' => $sisaCuti],'karyawan',['id_karyawan'=>$k->id_karyawan]);
				}
                $this->model_global->updateQuery(['status'=>'0'],'history_reset_cuti',['tahun'=>$date['tahun'], 'flag'=>'SYNC JAN']);
				$this->insertToHistoryResetCuti('SYNC JULI', 'Sinkron Data Cuti Juli Berhasil', $datetime, $date['tahun']);
                $datax = 'true';
			}else{
                $datax = 'false';
            }
        }else{
            $datax = 'false';
        }
        return $datax;
    }
    public function getChoiceList()
    {
        $pack=[
            'A'=>'A',
            'B'=>'B',
            'C'=>'C',
            'D'=>'D',
            'E'=>'E',
        ];
        return $pack;
    }
    public function getChoice($key)
    {
        return $this->getVarFromArrayKey($key,$this->getChoiceList());
    }
    public function getJenisMateriList()
    {
        $pack=[
            'PRE-TEST'=>'PRE-TEST',
            'POST-TEST'=>'POST-TEST',
        ];
        return $pack;
    }
    public function getJenisMateri($key)
    {
        return $this->getVarFromArrayKey($key,$this->getJenisMateriList());
    }
    public function getPreviewFileLearning($file)
    {
        if(empty($file))
            return false;
        $ext=explode('.', $file)[1];
        if($ext == 'pdf' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
            $fileView = '<embed type="application/pdf" src="'.base_url($file).'" width="100%" max-height="1200" height="800"></embed>';
        }elseif($ext == 'xlsx' || $ext == 'pptx' || $ext == 'docx'){
            // $fileView = '<iframe height="500px" src="'.base_url($file).'" width="100%"></iframe>';
            $fileView = '<iframe src="https://docs.google.com/viewer?url='.base_url($file).'&embedded=true&rm=minimal" width="100%" height="800" max-height="1200" style="border: none;"></iframe>';
        }elseif($ext == 'mp4'){
            $fileView = '
            <video controls width="100%">
                <source src="'.base_url($file).'" type="video/webm"/>
                <source src="'.base_url($file).'" type="video/mp4"/>
                Browsermu tidak mendukung tag ini, upgrade donk!
            </video>';
        }else{
            $fileView = '<embed type="application/pdf" src="'.base_url($file).'" width="100%" max-height="1200" height="800"></embed>';
        }
        return $fileView;
    }
}
