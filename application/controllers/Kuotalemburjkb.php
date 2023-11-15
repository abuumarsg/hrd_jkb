<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuotalemburjkb extends CI_Controller
{
	public function __construct() 
	{ 
		parent::__construct();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
		if ($this->session->has_userdata('adm')) {
			$this->admin = $this->session->userdata('adm')['id'];	 
		}else{ 
			redirect('auth');
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
	function cekKuota()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$tanggal = $this->input->post('tanggal');
		$bagian = $this->input->post('bagian');
		$karyawan = $this->input->post('karyawan');
		$tgl_awal = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tanggal,'end');
		$jml_lembur = $this->otherfunctions->getDivTime($tgl_awal,$tgl_akhir,'lembur');
        if($jml_lembur != '-'){
            $lamaLembur = $this->formatter->convertJamtoDecimal($jml_lembur);
        }else{
            $jml_lemburx = '00:00';
            $lamaLembur = $this->formatter->convertJamtoDecimal($jml_lemburx);
        }
        if(!empty($karyawan)){
        	$total_lembur = $lamaLembur*(count($karyawan));
		}else{
        	$total_lembur = 0;
		}
        $bulan = $this->otherfunctions->getDataExplode($tgl_awal,'-','end');
        $tahun = $this->otherfunctions->getDataExplode($tgl_awal,'-','start');
        if(!empty($bagian)){
			$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
			$where = 'a.kode_bagian="'.$bagian.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
			$data=$this->model_master->getListDetailKuotaLembur($where,true);
			// $data=$this->model_master->getListDetailKuotaLembur(['d.bulan'=>$bulan,'d.tahun'=>$tahun,'a.kode_bagian'=>$bagian],true);
            if(!empty($data)){
                $kuota = $data['kuota'];
                $sisa_kuota = $data['sisa_kuota'];
                $bagian = $data['nama_bagian'];
                $msg = 'Jumlah Kuota pada bagian '.$data['nama_bagian'].' ('.$data['nama_loker'].') adalah <b>'.$kuota.' Jam</b> dan <b>Sisa Kuota '.$sisa_kuota.' Jam.</b><br>
                Dan Transaksi ini akan mengurangi <b>'.$total_lembur.' Jam</b> ';//.$data['kode_kuota_lembur'];
            }else{
                $bagian = null;
                $msg = '<b>Bagian Ini Tidak dapat Kuota Lembur atau belum di setting jumlah kuota</b>';
            }
        }else{
            $bagian = null;
            $msg = 'Bagian Tidak Boleh Kosong';
        }
		$data = [
            'msg'=>$msg,
            'bagian'=>$bagian,
        ];
		echo json_encode($data);
    }
	function cekKuotaEdit()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$tanggal = $this->input->post('tanggal');
		$bagian = $this->input->post('bagian');
		$karyawan = $this->input->post('karyawan');
		$kuota_old = $this->input->post('kuota_old');
		$count_old = $this->input->post('count_old');
		$beban = $this->input->post('beban');
		$beban_old = $this->input->post('beban_old');
		$bagian_beban = $this->input->post('bagian_beban');
		$bagian_beban_new = $this->input->post('bagian_beban_new');
		$no_lembur = $this->input->post('no_lembur');
		$tgl_awal = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tanggal,'end');
		$jml_lembur = $this->otherfunctions->getDivTime($tgl_awal,$tgl_akhir,'lembur');
		// echo '<pre>';
		// // print_r($beban);
		// echo 'Beban OLD : '.$beban_old.'<br>';
		// echo 'Beban NEW : '.$beban.'<br>';
		// echo 'Bagian : '.$bagian.'<br>';
		// echo 'NO SPL : '.$no_lembur.'<br>';
		// echo 'Bagian Beban OLD : '.$bagian_beban.'<br>';
		// echo 'Bagian Beban NEW : '.$bagian_beban_new.'<br>';
		// if($beban_old == '0' && $beban == '1'){
		// 	$bagianx = $bagian_beban_new;
		// }elseif($beban_old == '1' && $beban == '1'){
		// 	$bagianx = $bagian_beban_new;
		// }elseif($beban_old == '1' && $beban == '0'){
		// 	$bagianx = $bagian;
		// }elseif($beban_old == '0' && $beban == '0'){
		// 	$bagianx = $bagian;
		// }
		if($jml_lembur != '-'){
			$lamaLembur = $this->formatter->convertJamtoDecimal($jml_lembur);
		}else{
			$jml_lemburx = '00:00';
			$lamaLembur = $this->formatter->convertJamtoDecimal($jml_lemburx);
		}
		if(!empty($karyawan)){
			$total_lembur = $lamaLembur*(count($karyawan));
		}else{
			$total_lembur = 0;
		}
		$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
		if($beban == '1'){
			$bagianx = $bagian_beban_new;
		}elseif($beban == '0'){
			$bagianx = $bagian_beban_new;
		}
		$where = 'a.kode_bagian="'.$bagianx.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
		$data=$this->model_master->getListDetailKuotaLembur($where,true);
		if(!empty($data)){
			$kuota = $data['kuota'];
			$sisa_kuota = $data['sisa_kuota']+($kuota_old*$count_old);
			$bagian = $data['nama_bagian'];
			$sisa_kuotax = (($sisa_kuota > $kuota)?$kuota:$sisa_kuota);
			$msg = 'Jumlah Kuota pada bagian '.$data['nama_bagian'].' ('.$data['nama_loker'].') adalah <b>'.$kuota.' Jam</b> dan <b>Sisa Kuota '.$sisa_kuotax.' Jam.</b><br>
			Dan Transaksi ini akan mengurangi <b>'.$total_lembur.' Jam</b> ';
		}else{
			$bagian = null;
			$msg = '<b>Bagian Ini Tidak dapat Kuota Lembur atau belum di setting jumlah kuota</b>';
		}
		$datax = [
			'msg'=>$msg,
			'bagian'=>$bagian,
		];
		// print_r($datax);
		// if($beban_old == '0' && $beban == '0'){
		// 	if($jml_lembur != '-'){
		// 		$lamaLembur = $this->formatter->convertJamtoDecimal($jml_lembur);
		// 	}else{
		// 		$jml_lemburx = '00:00';
		// 		$lamaLembur = $this->formatter->convertJamtoDecimal($jml_lemburx);
		// 	}
		// 	if(!empty($karyawan)){
		// 		$total_lembur = $lamaLembur*(count($karyawan));
		// 	}else{
		// 		$total_lembur = 0;
		// 	}
		// 	$bulan = $this->otherfunctions->getDataExplode($tgl_awal,'-','end');
		// 	$tahun = $this->otherfunctions->getDataExplode($tgl_awal,'-','start');
		// 	if(!empty($bagian)){
		// 		$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
		// 		$where = 'a.kode_bagian="'.$bagian.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
		// 		$data=$this->model_master->getListDetailKuotaLembur($where,true);
		// 		if(!empty($data)){
		// 			$kuota = $data['kuota'];
		// 			$sisa_kuota = $data['sisa_kuota']+($kuota_old*$count_old);
		// 			$bagian = $data['nama_bagian'];
		// 			$msg = 'Jumlah Kuota pada bagian '.$data['nama_bagian'].' ('.$data['nama_loker'].') adalah <b>'.$kuota.' Jam</b> dan <b>Sisa Kuota '.$sisa_kuota.' Jam.</b><br>
		// 			Dan Transaksi ini akan mengurangi <b>'.$total_lembur.' Jam</b> ';
		// 		}else{
		// 			$bagian = null;
		// 			$msg = '<b>Bagian Ini Tidak dapat Kuota Lembur atau belum di setting jumlah kuota</b>';
		// 		}
		// 	}else{
		// 		$bagian = null;
		// 		$msg = 'Bagian Tidak Boleh Kosong';
		// 	}
		// 	$data = [
		// 		'msg'=>$msg,
		// 		'bagian'=>$bagian,
		// 	];
		// }
		echo json_encode($datax);
    }
}