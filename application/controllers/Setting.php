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

class Setting extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7)); 
		if (isset($_SESSION['adm'])) {
			$this->admin = $_SESSION['adm']['id'];	
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
		$dtroot['admin']=$this->model_admin->adm($this->admin);
		$nm=explode(" ", $dtroot['admin']['nama']);
		$datax['adm'] = array(
			'nama'=>$nm[0],
			'email'=>$dtroot['admin']['email'],
			'kelamin'=>$dtroot['admin']['kelamin'],
			'foto'=>$dtroot['admin']['foto'],
			'create'=>$dtroot['admin']['create_date'],
			'update'=>$dtroot['admin']['update_date'],
			'login'=>$dtroot['admin']['last_login'],
		);
		$this->dtroot=$datax;
		$this->load->dbforge();
	}
	public function index(){
		redirect('pages/dashboard');
	}
	//==sikap==//
	function edt_a_concept(){
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'nama'=>ucwords($this->input->post('nama')),
				'update_date'=>$this->date,
			);
			$this->db->where('id_c_sikap',$id);
			$in=$this->db->update('concept_sikap',$data);
			if ($in) {
				$this->messages->allGood();
			}else{
				$this->messages->allFailure(); 
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/concept_sikap');
	}
	function del_a_concept(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$dt=$this->model_master->id_attd($kode);
			$tb=$dt['nama_tabel'];
			if ($tb != NULL) {
				$this->dbforge->drop_table($tb,TRUE);
			}

			$this->db->where('id_c_sikap',$kode);
			$in=$this->db->delete('concept_sikap');
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure(); 
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/concept_sikap');
	}
	function status_a_concept(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array(
				'status'=>$this->input->post('act'),
			);
			$this->db->where('id_c_sikap',$kode);
			$in=$this->db->update('concept_sikap',$data);
			if ($in) {
				$this->messages->allGood();
			}else{
				$this->messages->allFailure();
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/concept_sikap');
	}
	function add_a_concept(){
		$nama=ucwords($this->input->post('nama'));
		$kode='ATTDCON'.date('dmyHis',strtotime($this->date));
		$data=array(
			'kode_c_sikap'=>$kode,
			'nama'=>$nama,
			'create_date'=>$this->date,
			'update_date'=>$this->date,
		);
		$fields = array(
			'id_atask' => array(
				'type' => 'BIGINT',
				'constraint' => 255,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'id_karyawan' => array(
				'type' => 'BIGINT',
				'constraint' => 255,
				'null'=> TRUE
			),
			'nik' => array(
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null'=> TRUE
			),
			'nama' => array(
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null'=> TRUE
			),
			'id_jabatan' => array(
				'type' => 'BIGINT',
				'constraint' => 255,
				'null'=> TRUE
			),
			'id_loker' => array(
				'type' => 'BIGINT',
				'constraint' => 255,
				'null'=> TRUE
			),
			'partisipan' => array(
				'type' => 'LONGTEXT',
				'null'=> TRUE
			),
			'bobot_ats' => array(
				'type' => 'INT',
				'constraint' => 100,
				'default'=> '0',
			),
			'bobot_rkn' => array(
				'type' => 'INT',
				'constraint' => 100,
				'default'=> '0',
			),
			'bobot_bwh' => array(
				'type' => 'INT',
				'constraint' => 100,
				'default'=> '0',
			),
			
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id_atask', TRUE);
		$nm='catd'.uniqid();
		$this->dbforge->create_table($nm);
		$dtk=$this->model_karyawan->list_karyawan();
		foreach ($dtk as $k) {
			$x=$k->id_karyawan;
			$jbt[$x]=$this->model_master->k_jabatan($k->jabatan);
			$lok[$x]=$this->model_master->k_loker($k->unit);
			$ats[$x]=$jbt[$x]['atasan'];
			
			$bj[$x]=$this->db->get_where('master_jabatan',array('atasan'=>$k->jabatan))->result();
			/*
			if (count($bj[$x]) > 0) {
				$n1[$x]=1;
				foreach ($bj[$x] as $b[$x]) {
					$kr[$x]=$this->model_karyawan->emp_jbt($b[$x]->kode_jabatan);
					if (count($kr[$x]) != 0) {
						$nnp[$x]=1;

						foreach ($kr[$x] as $kk) {
							$kr1[$x][$nnp[$x]]='BWH:'.$kk->id_karyawan;
							$nnp[$x]++;
						}
						$bwh[$x]=implode(';',$kr1[$x]).';';
					}else{
						$bwh[$x]=NULL;
					}
					$n1[$x]++;
				}
				$ww[$x]=explode(';', $bwh[$x]);
				$bwh1[$x]=implode(';', array_unique(array_filter($ww[$x])));
			}else{
				$bwh1[$x]=NULL;
			}
			$ats1[$x]=$this->model_karyawan->emp_jbt($ats[$x]);
			
			if (count($ats1[$x]) > 0) {
				$n2[$x]=1;
				foreach ($ats1[$x] as $a[$x]) {
					$a1[$x][$n2[$x]]='ATS:'.$a[$x]->id_karyawan;
					$n2[$x]++;
				}
				$ax2[$x]=implode(';', $a1[$x]).';';
				$ax3[$x]=explode(';', $ax2[$x]);
				$ats2[$x]=implode(';', array_unique(array_filter($ax3[$x])));
			}else{
				$ats2[$x]=NULL;
			}
			$rkn[$x]=$this->db->query("SELECT * FROM karyawan WHERE jabatan = '$k->jabatan' AND id_karyawan != '$k->id_karyawan'")->result();
			
			if (count($rkn[$x]) > 0) {
				$n3[$x]=1;
				foreach ($rkn[$x] as $r[$x]) {
					$r1[$x][$n3[$x]]='RKN:'.$r[$x]->id_karyawan;
					$n3[$x]++;
				}
				$rx2[$x]=implode(';', $r1[$x]).';';
				$rx3[$x]=explode(';', $rx2[$x]);
				$rkn1[$x]=implode(';', array_unique(array_filter($rx3[$x])));
			}else{
				$rkn1[$x]=NULL;
			}
			//Diri sendiri
			$dr='DRI:'.$x.';';
			$pp[$x]=$dr.$ats2[$x].';'.$bwh1[$x].';'.$rkn1[$x];
			//$pp[$x]=$ats2[$x].';'.$bwh1[$x].';'.$rkn1[$x];
			$pp1[$x]=explode(';', $pp[$x]);
			$pp2[$x]=implode(';', array_unique(array_filter($pp1[$x])));*/
			if (count($bj[$x]) > 0) {
				$kmb[$x]=array();
				foreach ($bj[$x] as $b[$x]) {
					//perusahaan lain, jgn dihapus
					//$krp[$x]=$this->model_karyawan->emp_jbt($b[$x]->kode_jabatan);
					$krp[$x]=$this->db->get_where('karyawan',array('jabatan'=>$b[$x]->kode_jabatan,'unit'=>$k->unit))->result();
					foreach ($krp[$x] as $kkp) {
						array_push($kmb[$x], 'BWH:'.$kkp->id_karyawan);
					}
				}
				$bwh1[$x]=implode(';', $kmb[$x]);
			}else{
				$bwh1[$x]=NULL;
			}
			//perusahaan lain, jgn dihapus
			$ats1[$x]=$this->model_karyawan->emp_jbt($ats[$x]);
			//$ats1[$x]=$this->db->get_where('karyawan',array('jabatan'=>$ats[$x],'unit'=>$k->unit))->result();

			if (count($ats1[$x]) > 0) {
				$kma[$x]=array();
				foreach ($ats1[$x] as $att) {
					array_push($kma[$x],'ATS:'.$att->id_karyawan);
				}
				$ats2[$x]=implode(';', $kma[$x]);
			}else{
				$ats2[$x]=NULL;
			}
			$jj=$k->jabatan;
			//jangan dihapus
			//$rkn[$x]=$this->db->query("SELECT * FROM karyawan WHERE jabatan = '$jj' AND id_karyawan != '$x'")->result();
			$rkn[$x]=$this->db->query("SELECT * FROM karyawan WHERE jabatan = '$jj' AND unit = '$k->unit' AND id_karyawan != '$x'")->result();

			if (count($rkn[$x]) > 0) {
				$kmr[$x]=array();
				foreach ($rkn[$x] as $r) {
					array_push($kmr[$x], 'RKN:'.$r->id_karyawan);
				}
				$rkn1[$x]=implode(';', $kmr[$x]);
			}else{
				$rkn1[$x]=NULL;
			}
				/*
				$dr='DRI:'.$x.';';
				$pp[$x]=$dr.$ats2[$x].';'.$bwh1[$x].';'.$rkn1[$x];*/
				$pp[$x]=$ats2[$x].';'.$bwh1[$x].';'.$rkn1[$x];
				$pp1[$x]=explode(';', $pp[$x]);
				$pp2[$x]=implode(';', array_unique(array_filter($pp1[$x])));
				$datain[$x]=array(
					'id_karyawan'=>$k->id_karyawan,
					'nik'=>$k->nik,
					'nama'=>$k->nama,
					'id_jabatan'=>$jbt[$x]['id_jabatan'],
					'id_loker'=>$lok[$x]['id_loker'],
					'partisipan'=>$pp2[$x],
				);

				$ss=1;
				foreach ($pp1[$x] as $px) {
					$px1[$x]=explode(':', $px);
					if ($px1[$x][0] != 'DRI') {
						$px2[$x][$ss]=$px1[$x][0];
					}
					$ss++;
				}
				if (isset($px2[$x])) {
					$px3[$x]=implode(':',array_values(array_unique(array_filter($px2[$x]))));
					$bb[$x]=$this->model_master->k_bobot($px3[$x]);
					$datain[$x]['bobot_ats']=$bb[$x]['atasan'];
					$datain[$x]['bobot_bwh']=$bb[$x]['bawahan'];
					$datain[$x]['bobot_rkn']=$bb[$x]['rekan'];
				}else{
					$datain[$x]['bobot_ats']=0;
					$datain[$x]['bobot_bwh']=0;
					$datain[$x]['bobot_rkn']=0;
				}
				if ($datain[$x]['partisipan'] != "") {
					$this->db->insert($nm,$datain[$x]);
				}else{
					$not[$x]=array('nama'=>$k->nama,'nik'=>$k->nik);
				}
			}
			$this->db->insert('concept_sikap',$data);
			$upattd=array('nama_tabel'=>$nm);
			$this->db->where('kode_c_sikap',$kode);
			$in=$this->db->update('concept_sikap',$upattd);
			if (isset($not)) {
				foreach ($not as $nn) {
					$na[]='Nama = '.$nn['nama'].' dan NIK = '.$nn['nik'];
				}
				$this->session->set_flashdata('msgwrx',$not);
				//$this->session->set_flashdata('msgwr','<label><i class="fa fa-warning"></i> Insert Data Berhasil Sebagian</label><hr class="message-inner-separator">Karyawan dengan '.implode(', ', $na).' Tidak Memiliki Partisipan, Silahkan Tentukan Pada Menu <a href="'.base_url('pages/master_jabatan').'">Jabatan</a> dan Menu <a href="'.base_url('pages/employee').'">Data Karyawan</a>');
			}elseif ($in) {
				$this->messages->allGood();
			}else{
				$this->messages->allFailure(); 
			}
			redirect('pages/concept_sikap');
		}

		function generate_new_a_concept(){
			$tb='con'.uniqid();
			$nama='Rancangan Penilaian Sikap '.date('d/m/y/His',strtotime($this->date));
			$kode='ATTDCON'.date('dmyHis',strtotime($this->date));
			$data=array(
				'kode_c_sikap'=>$kode,
				'nama'=>$nama,
				'create_date'=>$this->date,
				'update_date'=>$this->date,
			);
			$fields = array(
				'id_atask' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_karyawan' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'nik' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'id_jabatan' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'id_loker' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'partisipan' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'bobot_ats' => array(
					'type' => 'INT',
					'constraint' => 100,
					'default'=> '0',
				),
				'bobot_rkn' => array(
					'type' => 'INT',
					'constraint' => 100,
					'default'=> '0',
				),
				'bobot_bwh' => array(
					'type' => 'INT',
					'constraint' => 100,
					'default'=> '0',
				),

			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id_atask', TRUE);
			$nm='catd'.uniqid();
			$this->dbforge->create_table($nm);
			$dtk=$this->model_karyawan->list_karyawan();
			foreach ($dtk as $k) {
				$x=$k->id_karyawan;
				$jbt[$x]=$this->model_master->k_jabatan($k->jabatan);
				$lok[$x]=$this->model_master->k_loker($k->unit);
				$ats[$x]=$jbt[$x]['atasan'];

				$bj[$x]=$this->db->get_where('master_jabatan',array('atasan'=>$k->jabatan))->result();
				if (count($bj[$x]) > 0) {
					$kmb[$x]=array();
					foreach ($bj[$x] as $b[$x]) {
						//$krp[$x]=$this->model_karyawan->emp_jbt($b[$x]->kode_jabatan);
						$krp[$x]=$this->db->get_where('karyawan',array('jabatan'=>$b[$x]->kode_jabatan,'unit'=>$k->unit))->result();
						foreach ($krp[$x] as $kkp) {
							array_push($kmb[$x], 'BWH:'.$kkp->id_karyawan);
						}
					}
					$bwh1[$x]=implode(';', $kmb[$x]);
				}else{
					$bwh1[$x]=NULL;
				}
				
				$ats1[$x]=$this->model_karyawan->emp_jbt($ats[$x]);
				//$ats1[$x]=$this->db->get_where('karyawan',array('jabatan'=>$ats[$x],'unit'=>$k->unit))->result();
				if (count($ats1[$x]) > 0) {
					$kma[$x]=array();
					foreach ($ats1[$x] as $att) {
						array_push($kma[$x],'ATS:'.$att->id_karyawan);
					}
					$ats2[$x]=implode(';', $kma[$x]);
				}else{
					$ats2[$x]=NULL;
				}
				$jj=$k->jabatan;
				//$rkn[$x]=$this->db->query("SELECT * FROM karyawan WHERE jabatan = '$jj' AND id_karyawan != '$x'")->result();
				$rkn[$x]=$this->db->query("SELECT * FROM karyawan WHERE jabatan = '$jj' AND unit = '$k->unit' AND id_karyawan != '$x'")->result();
				if (count($rkn[$x]) > 0) {
					$kmr[$x]=array();
					foreach ($rkn[$x] as $r) {
						array_push($kmr[$x], 'RKN:'.$r->id_karyawan);
					}
					$rkn1[$x]=implode(';', $kmr[$x]);
				}else{
					$rkn1[$x]=NULL;
				}
				/*
				$dr='DRI:'.$x.';';
				$pp[$x]=$dr.$ats2[$x].';'.$bwh1[$x].';'.$rkn1[$x];*/
				$pp[$x]=$ats2[$x].';'.$bwh1[$x].';'.$rkn1[$x];
				$pp1[$x]=explode(';', $pp[$x]);
				$pp2[$x]=implode(';', array_unique(array_filter($pp1[$x])));
				$datain[$x]=array(
					'id_karyawan'=>$k->id_karyawan,
					'nik'=>$k->nik,
					'nama'=>$k->nama,
					'id_jabatan'=>$jbt[$x]['id_jabatan'],
					'id_loker'=>$lok[$x]['id_loker'],
					'partisipan'=>$pp2[$x],
				);

				$ss=1;
				foreach ($pp1[$x] as $px) {
					$px1[$x]=explode(':', $px);
					if ($px1[$x][0] != 'DRI') {
						$px2[$x][$ss]=$px1[$x][0];
					}
					$ss++;
				}
				if (isset($px2[$x])) {
					$px3[$x]=implode(':',array_values(array_unique(array_filter($px2[$x]))));
					$bb[$x]=$this->model_master->k_bobot($px3[$x]);
					$datain[$x]['bobot_ats']=$bb[$x]['atasan'];
					$datain[$x]['bobot_bwh']=$bb[$x]['bawahan'];
					$datain[$x]['bobot_rkn']=$bb[$x]['rekan'];
				}else{
					$datain[$x]['bobot_ats']=0;
					$datain[$x]['bobot_bwh']=0;
					$datain[$x]['bobot_rkn']=0;
				}
				if ($datain[$x]['partisipan'] != "") {
					$this->db->insert($nm,$datain[$x]);
				}else{
					$not[$x]=array('nama'=>$k->nama,'nik'=>$k->nik);
				}
			}
		//update agenda
			$this->db->insert('concept_sikap',$data);
			$upattd=array('nama_tabel'=>$nm);
			$this->db->where('kode_c_sikap',$kode);
			$in=$this->db->update('concept_sikap',$upattd);
			if (isset($not)) {
				foreach ($not as $nn) {
					$na[]='Nama = '.$nn['nama'].' dan NIK = '.$nn['nik'];
					//$na[]=array($nn['nama'],$nn['nik'];
				}
				$this->session->set_flashdata('msgwrx',$not);
				//$this->session->set_flashdata('msgwr','<label><i class="fa fa-warning"></i> Insert Data Berhasil Sebagian</label><hr class="message-inner-separator">Karyawan dengan '.implode(', ', $na).' Tidak Memiliki Partisipan, Silahkan Tentukan Pada Menu <a href="'.base_url('pages/master_jabatan').'">Jabatan</a> dan Menu <a href="'.base_url('pages/employee').'">Data Karyawan</a>');
			}
			elseif ($in) {
				$this->messages->allGood();
			}else{
				$this->messages->allFailure();
			}
			redirect('pages/concept_sikap');
		}
		function del_many_part_attd(){
			$kode=$this->input->post('kode');
			$dt=$this->input->post('part');
			$nik=$this->input->post('nik');
			$cek=$this->model_master->cek_attd($kode);
			if ($kode == "" || $nik == "" || $cek == "") {
				$this->messages->notValidParam();  
				redirect('pages/concept_sikap');
			}else{
				if ($dt == "") {
					$this->messages->customFailure('Pilih Salah Satu Data!');
					redirect('pages/view_attitude_partisipant/'.$kode.'/'.$nik);
				}
				$cek1=$this->db->get_where($cek['nama_tabel'],array('nik'=>$nik))->row_array();
				if ($cek1 == "") {
					$this->messages->notValidParam();  
					redirect('pages/view_concept_sikap/'.$kode);
				}else{
					$par=array_filter(explode(';', $cek1['partisipan']));
					foreach ($dt as $d) {
						if (in_array($d, $par)) {
							if (($k = array_search($d, $par)) !== false) {
								unset($par[$k]);
							}
						}
					}
					$n=1;
					$ats=array();
					$bwh=array();
					$rkn=array();
					foreach ($par as $p) {
						$p1=explode(':', $p);
						if ($p1[0] == "ATS") {
							array_push($ats, "ATS:".$p1[1]);
						}
						if ($p1[0] == "BWH") {
							array_push($bwh, "BWH:".$p1[1]);
						}
						if ($p1[0] == "RKN") {
							array_push($rkn, "RKN:".$p1[1]);
						}
						if ($p1[0] != "DRI") {
							$p2[$n]=$p1[0];
						}
						$n++;
					}
					$pt1=implode(';', $ats).';'.implode(';', $bwh).';'.implode(';', $rkn);
					$pt=implode(';',array_filter(explode(';', $pt1)));
					if (isset($p2)) {
						$pp=implode(':', array_unique($p2));
						if ($pp == "ATS:RKN:BWH" || $pp == "RKN:ATS:BWH" || $pp == "BWH:ATS:RKN" || $pp == "RKN:BWH:ATS" || $pp == "BWH:RKN:ATS") {
							$pp="ATS:BWH:RKN";
						}elseif ($pp == "RKN:BWH") {
							$pp="BWH:RKN";
						}elseif ($pp == "RKN:ATS") {
							$pp="ATS:RKN";
						}elseif ($pp == "BWH:ATS") {
							$pp="ATS:BWH";
						}
						$pp=implode(':', array_unique($p2));
						$b=$this->db->get_where('master_bobot_sikap',array('kode_bobot'=>$pp))->row_array();
						$data=array('partisipan'=>$pt,'bobot_ats'=>$b['atasan'],'bobot_rkn'=>$b['rekan'],'bobot_bwh'=>$b['bawahan']);
					}else{
						$data=array('partisipan'=>implode(';', $par),'bobot_ats'=>0,'bobot_rkn'=>0,'bobot_bwh'=>0);
					}

					$this->db->where('nik',$nik);
					$this->db->update($cek['nama_tabel'],$data);
					$this->messages->delGood();
					redirect('pages/view_attitude_partisipant/'.$kode.'/'.$nik);
				}
			}
		}
		function sync_bobot_s(){
			$kode=$this->input->post('kode');
			$cek=$this->model_master->cek_attd($kode);
			if ($kode == "") {
				$this->messages->notValidParam();  
				redirect('pages/concept_sikap');
			}else{
				$tb=$cek['nama_tabel'];
				$dt=$this->db->get($tb)->result();
				foreach ($dt as $d) {
					$id=$d->id_karyawan;
					$part=explode(';', $d->partisipan);
					$n=1;
					foreach ($part as $p) {
						$p1=explode(':', $p);
						if ($p1[0] != "DRI") {
							$p2[$id][$n]=$p1[0];
						}
						$n++;
					}
					$pp=implode(':', array_unique($p2[$id]));
					$b=$this->db->get_where('master_bobot_sikap',array('kode_bobot'=>$pp))->row_array();
					$data=array('bobot_ats'=>$b['atasan'],'bobot_rkn'=>$b['rekan'],'bobot_bwh'=>$b['bawahan']);
					$this->db->where('id_karyawan',$id);
					$this->db->update($tb,$data);
				}
				$this->messages->allGood(); 
				redirect('pages/view_concept_sikap/'.$kode);
			}
		}
		function add_part_attd_concept(){
			$kode=$this->input->post('kode');
			$idk=$this->input->post('idk');
			$nik=$this->input->post('nik');
			$kary=$this->input->post('karyawan');
			$ops=$this->input->post('opsi');
			$cek=$this->model_master->cek_attd($kode);
			if ($kode == "" || $nik == "" || $idk == "" || $cek == "" || $kary == "" || $ops == "") {
				$this->messages->notValidParam();  
				redirect('pages/concept_sikap');
			}else{
				$cek1=$this->db->get_where($cek['nama_tabel'],array('id_karyawan'=>$idk))->row_array();
				if ($cek1 == "") {
					$this->messages->notValidParam();  
					redirect('pages/view_concept_sikap/'.$kode);
				}else{

					$par=array_values(array_filter(explode(';', $cek1['partisipan'])));
					foreach ($kary as $k) {
						array_push($par, $dt[$k]=$ops.':'.$k);
					}
					$n=1;
					$ats=array();
					$bwh=array();
					$rkn=array();
					foreach ($par as $p) {
						$p1=explode(':', $p);
						if ($p1[0] == "ATS") {
							array_push($ats, "ATS:".$p1[1]);
						}
						if ($p1[0] == "BWH") {
							array_push($bwh, "BWH:".$p1[1]);
						}
						if ($p1[0] == "RKN") {
							array_push($rkn, "RKN:".$p1[1]);
						}
						if ($p1[0] != "DRI") {
							$p2[$n]=$p1[0];
						}
						$n++;
					}
					$pt1=implode(';', $ats).';'.implode(';', $bwh).';'.implode(';', $rkn);
					$pt=implode(';',array_filter(explode(';', $pt1)));
					$pp=implode(':', array_unique($p2));
					if ($pp == "ATS:RKN:BWH" || $pp == "RKN:ATS:BWH" || $pp == "BWH:ATS:RKN" || $pp == "RKN:BWH:ATS" || $pp == "BWH:RKN:ATS") {
						$pp="ATS:BWH:RKN";
					}elseif ($pp == "RKN:BWH") {
						$pp="BWH:RKN";
					}elseif ($pp == "RKN:ATS") {
						$pp="ATS:RKN";
					}elseif ($pp == "BWH:ATS") {
						$pp="ATS:BWH";
					}
					$b=$this->db->get_where('master_bobot_sikap',array('kode_bobot'=>$pp))->row_array();
					$data=array('partisipan'=>$pt,'bobot_ats'=>$b['atasan'],'bobot_rkn'=>$b['rekan'],'bobot_bwh'=>$b['bawahan']);
					$this->db->where('id_karyawan',$idk);
					$in=$this->db->update($cek['nama_tabel'],$data);
					if ($in) {
						$this->messages->allGood();
					}else{
						$this->messages->allFailure();
					}
					redirect('pages/view_attitude_partisipant/'.$kode.'/'.$nik);
				}
			}
		}
		function add_emp_attd_concept(){
			$kode=$this->input->post('kode');
			$ky=$this->input->post('karyawan');
			$cek=$this->model_master->cek_attd($kode);
			if ($kode == "" || $ky == "" || $cek == "") {
				$this->messages->notValidParam();  
				redirect('pages/concept_sikap');
			}else{
				foreach ($ky as $x) {
					$kry=$this->model_karyawan->emp($x);
					$jbt[$x]=$this->model_master->k_jabatan($kry['jabatan']);
					$lok[$x]=$this->model_master->k_loker($kry['unit']);
					$ats[$x]=$jbt[$x]['atasan'];

					$bj[$x]=$this->db->get_where('master_jabatan',array('atasan'=>$kry['jabatan']))->result();
					if (count($bj[$x]) > 0) {
						$kmb[$x]=array();
						foreach ($bj[$x] as $b[$x]) {
							//$krp[$x]=$this->model_karyawan->emp_jbt($b[$x]->kode_jabatan);
							$krp[$x]=$this->db->get_where('karyawan',array('jabatan'=>$b[$x]->kode_jabatan,'unit'=>$kry['unit']))->result();
							foreach ($krp[$x] as $kkp) {
								array_push($kmb[$x], 'BWH:'.$kkp->id_karyawan);
							}
						}
						$bwh1[$x]=implode(';', $kmb[$x]);
					}else{
						$bwh1[$x]=NULL;
					}

					$ats1[$x]=$this->model_karyawan->emp_jbt($ats[$x]);

					if (count($ats1[$x]) > 0) {
						$kma[$x]=array();
						foreach ($ats1[$x] as $att) {
							array_push($kma[$x],'ATS:'.$att->id_karyawan);
						}
						$ats2[$x]=implode(';', $kma[$x]);
					}else{
						$ats2[$x]=NULL;
					}
					$jj=$kry['jabatan'];
					$un=$kry['unit'];
					//$rkn[$x]=$this->db->query("SELECT * FROM karyawan WHERE jabatan = '$jj' AND id_karyawan != '$x'")->result();
					$rkn[$x]=$this->db->query("SELECT * FROM karyawan WHERE jabatan = '$jj' AND unit = '$un' AND id_karyawan != '$x'")->result();
					if (count($rkn[$x]) > 0) {
						$kmr[$x]=array();
						foreach ($rkn[$x] as $r) {
							array_push($kmr[$x], 'RKN:'.$r->id_karyawan);
						}
						$rkn1[$x]=implode(';', $kmr[$x]);
					}else{
						$rkn1[$x]=NULL;
					}
				/*$dr='DRI:'.$x.';';
				$pp[$x]=$dr.$ats2[$x].';'.$bwh1[$x].';'.$rkn1[$x];*/
				$pp[$x]=$ats2[$x].';'.$bwh1[$x].';'.$rkn1[$x];
				$pp1[$x]=explode(';', $pp[$x]);
				$pp2[$x]=implode(';', array_unique(array_filter($pp1[$x])));
				$datain[$x]=array(
					'id_karyawan'=>$x,
					'nik'=>$kry['nik'],
					'nama'=>$kry['nama'],
					'id_jabatan'=>$jbt[$x]['id_jabatan'],
					'id_loker'=>$lok[$x]['id_loker'],
					'partisipan'=>$pp2[$x],
				);
				$ss=1;
				foreach ($pp1[$x] as $px) {
					$px1[$x]=explode(':', $px);
					if ($px1[$x][0] != 'DRI') {
						$px2[$x][$ss]=$px1[$x][0];
					}
					$ss++;
				}
				$px3[$x]=implode(':',array_values(array_unique(array_filter($px2[$x]))));
				$bb[$x]=$this->model_master->k_bobot($px3[$x]);
				$datain[$x]['bobot_ats']=$bb[$x]['atasan'];
				$datain[$x]['bobot_bwh']=$bb[$x]['bawahan'];
				$datain[$x]['bobot_rkn']=$bb[$x]['rekan'];
				if ($datain[$x]['partisipan'] != "") {
					$this->db->insert($cek['nama_tabel'],$datain[$x]);
				}else{
					$not[$x]=array('nama'=>$kry['nama'],'nik'=>$kry['nik']);
				}
				
			}
			if (isset($not)) {
				foreach ($not as $nn) {
					$na[]='Nama = '.$nn['nama'].' dan NIK = '.$nn['nik'];
				}
				$this->session->set_flashdata('msgwrx',$not);
				//$this->session->set_flashdata('msgwr','<label><i class="fa fa-warning"></i> Insert Data Berhasil Sebagian</label><hr class="message-inner-separator">Karyawan dengan '.implode(', ', $na).' Tidak Memiliki Partisipan, Silahkan Tentukan Pada Menu <a href="'.base_url('pages/master_jabatan').'">Jabatan</a> dan Menu <a href="'.base_url('pages/employee').'">Data Karyawan</a>');
			}else{
				$this->messages->allGood();
			}
			redirect('pages/view_concept_sikap/'.$kode);
		}
	}
	function del_emp_attd_concept(){
		$kode=$this->input->post('kode');
		$idk=$this->input->post('idk');
		$cek=$this->model_master->cek_attd($kode);
		if ($kode == "" || $idk == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/concept_sikap');
		}else{
			$cek1=$this->db->get_where($cek['nama_tabel'],array('id_karyawan'=>$idk))->row_array();
			if ($cek1 == "") {
				$this->messages->notValidParam();  
				redirect('pages/concept_sikap/');
			}else{
				$this->db->where('id_karyawan',$idk);
				$in=$this->db->delete($cek['nama_tabel']);
				if ($in) {
					$this->messages->delGood();
				}else{
					$this->messages->delFailure();
				}
				redirect('pages/view_concept_sikap/'.$kode);
			}
		}
	}
	//==output==//
	function edt_concept(){
		$id=$this->input->post('id');
		if ($id != "") {
			$data=array(
				'nama'=>ucwords($this->input->post('nama')),
				'update_date'=>$this->date,
			);
			$this->db->where('id_c_kpi',$id);
			$in=$this->db->update('concept_kpi',$data);
			if ($in) {
				$this->messages->allGood();
			}else{
				$this->messages->allFailure();
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/concept');
	}
	function del_concept(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$dt=$this->model_master->id_set($kode);
			$tb=$dt['nama_tabel'];
			if ($tb != NULL) {
				$this->dbforge->drop_table($tb,TRUE);
			}

			$this->db->where('id_c_kpi',$kode);
			$in=$this->db->delete('concept_kpi');
			if ($in) {
				$this->messages->delGood(); 
			}else{
				$this->messages->delFailure(); 
			}
		}else{
			$this->messages->notValidParam();
		}
		redirect('pages/concept');
	}
	function status_concept(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array(
				'status'=>$this->input->post('act'),
			);
			$this->db->where('id_c_kpi',$kode);
			$in=$this->db->update('concept_kpi',$data);
			if ($in) {
				$this->messages->allGood();
			}else{
				$this->messages->allFailure(); 
			}
		}else{
			$this->messages->notValidParam(); 
		}
		redirect('pages/concept');
	}
	function add_concept(){
		$nama=ucwords($this->input->post('nama'));
		$kode='CONCEPT'.date('dmyHis',strtotime($this->date));
		$data=array(
			'kode_c_kpi'=>$kode,
			'nama'=>$nama,
			'create_date'=>$this->date,
			'update_date'=>$this->date,
		);
		$in=$this->db->insert('concept_kpi',$data);
		if ($in) {
			$this->messages->allGood(); 
		}else{
			$this->messages->allFailure();
		}
		redirect('pages/concept');
	}

	function generate_new_concept(){
		$tb='con'.uniqid();
		$nama='Rancangan Penilaian Output '.date('d/m/y/His',strtotime($this->date));
		$kode='CONCEPT'.date('dmyHis',strtotime($this->date));
		$data=array(
			'kode_c_kpi'=>$kode,
			'nama'=>$nama,
			'create_date'=>$this->date,
			'update_date'=>$this->date,
		);
		$in=$this->db->insert('concept_kpi',$data);
		if ($in) {
			$this->messages->allGood();
		}else{
			$this->messages->allFailure(); 
		}
		redirect('pages/concept');
	}
	function chain_bobot(){
		$set=$this->input->post('setting');
		if ($set == "") {
			$this->messages->notValidParam();  
			redirect('pages/master_level_jabatan');
		}else{
			foreach ($set as $a) {
				$ck=$this->model_setting->cek_setting($a);
				$tb=$ck['nama_tabel'];
				$dttb=$this->db->get($tb)->result();
				foreach ($dttb as $dtb) {
					$jb[$dtb->id_jabatan]=$dtb->id_jabatan;
				}
				foreach ($jb as $j) {
					$jbt=$this->model_master->jabatan($j);
					$kdl[$j]=$this->model_master->cek_level($jbt['kode_level']);
					$data[$j]=array(
						'bobot_out'=>$kdl[$j]['bobot_out'],
						'bobot_skp'=>$kdl[$j]['bobot_sikap'],
						'bobot_tc'=>$kdl[$j]['bobot_tcorp'],
					);
					$this->db->where('id_jabatan',$j);
					$this->db->update($tb,$data[$j]);
				}
			}
			$this->messages->allGood();
			redirect('pages/master_level_jabatan');

		}

	}
	function del_jabatan_concept(){
		$kode=$this->input->post('kode');
		$id=$this->input->post('id');
		$tb=$this->input->post('tabel');
		$sub=$this->input->post('sub');
		if ($kode == "" || $id == "" || $tb == "") {
			$this->messages->notValidParam();  
			redirect('pages/concept');
		}else{
			$tab=$this->db->get($tb)->num_rows();
			if ($tab == 0) {
				$this->dbforge->drop_table($tb,TRUE);
				$upagd1=array('nama_tabel'=>NULL);
				$this->db->where('kode_c_kpi',$kode);
				$this->db->update('concept_kpi',$upagd1); 
			}
			if (!empty($sub)) {
				$wh=array('id_jabatan'=>$id,'id_sub'=>$sub);
			}else{
				$wh=array('id_jabatan'=>$id);
			}
			$this->db->where($wh);
			$in=$this->db->delete($tb);
			if ($in) {
				$this->messages->delGood(); 
			}else{
				$this->messages->delFailure();
			}
			redirect('pages/view_concept/'.$kode);
		}
	}

	//==INDIKATOR==//
	//--setting indikator--//
	function status_indikator(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array(
				'status'=>$this->input->post('act'),
			);
			$this->db->where('id_indikator',$kode);
			$in=$this->db->update('master_indikator',$data);
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure();
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/setting_master_indikator');
	}
	function add_emp_config(){
		$kode=$this->input->post('kode');
		$tabel=$this->input->post('tabel');
		$jbt=$this->input->post('jabatan');
		$sub=$this->input->post('sub');
		if ($kode == '' || $tabel == '' || $jbt == '') {
			$this->messages->notValidParam();  
			redirect('pages/concept'); 
		}else{
			$kar=$this->input->post('karyawan');
			if (!empty($sub)) {
				$dt=$this->db->query("SELECT * FROM $tabel WHERE id_jabatan = '$jbt' AND id_sub = '$sub'")->result();
			}else{
				$dt=$this->db->query("SELECT * FROM $tabel WHERE id_jabatan = '$jbt' AND id_sub IS NULL")->result();
			}
			foreach ($dt as $d) {
				$k_indi[$d->kode_indikator]=$d->kode_indikator;
			}
			foreach ($kar as $k) {
				$dtk=$this->model_karyawan->emp($k);
				foreach ($k_indi as $ki) {
					if (!empty($sub)) {
						$loker=$this->model_master->k_loker($m_sub['kode_loker']);
						$indi=$this->db->query("SELECT * FROM $tabel WHERE id_jabatan = '$jbt' AND kode_indikator = '$ki' AND id_sub = '$sub'")->row_array();
						$sb=$sub;
					}else{
						$loker=$this->model_master->k_loker($dtk['unit']);
						$indi=$this->db->query("SELECT * FROM $tabel WHERE id_jabatan = '$jbt' AND kode_indikator = '$ki' AND id_sub IS NULL")->row_array();
						$sb=NULL;
					}
					$m_sub=$this->db->get_where('master_sub_jabatan',array('id_sub'=>$sub))->row_array();
					
					$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$dtk['loker_pa']))->row_array();
					$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$dtk['jabatan_pa']))->row_array();
					$jbtn=$this->db->get_where('master_jabatan',array('id_jabatan'=>$jbt))->row_array();
					$data=array(
						'urutan'=>$indi['urutan'],
						'kode_indikator'=>$ki,
						'indikator'=>$indi['indikator'],
						'cara_mengukur'=>$indi['cara_mengukur'],
						'rumus'=>$indi['rumus'],
						'sumber'=>$indi['sumber'],
						'polarisasi'=>$indi['polarisasi'],
						'kaitan'=>$indi['kaitan'],
						'periode'=>$indi['periode'],
						'satuan'=>$indi['satuan'],
						'sifat'=>$indi['sifat'],
						'kode_penilai'=>$indi['kode_penilai'],
						'id_penilai'=>$indi['id_penilai'],
						'bobot'=>$indi['bobot'],
						'id_karyawan'=>$k,
						'konsolidasi'=>$indi['konsolidasi'],
						'id_jabatan'=>$jbt,
						'jabatan'=>$jbtn['jabatan'],
						'id_loker'=>$loker['id_loker'],
						'loker'=>$loker['nama'],
						'id_sub'=>$sb,
						'sub'=>$m_sub['nama_sub'],
						'id_jabatan_pa'=>$dtk['jabatan_pa'],
						'jabatan_pa'=>$jabatan_p['jabatan'],
						'id_loker_pa'=>$dtk['loker_pa'],
						'loker_pa'=>$loker_p['nama'],
					);
					$this->db->insert($tabel,$data);
				}
			}
			$this->messages->allGood();
			if (!empty($sub)) {
			 	redirect('pages/view_concept_setting/'.$kode.'/'.$jbt.'/'.$sub);
			}else{
				redirect('pages/view_concept_setting/'.$kode.'/'.$jbt);
			} 
			
		}
	}
	function c_konsolidasi(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$ka=$this->input->post('kode');
			$tabel=$this->input->post('tabel');
			$sub=$this->input->post('sub');
			$data=array(
				'konsolidasi'=>$this->input->post('act'),
			);
			if (isset($sub)) {
				$where=array('id_jabatan'=>$kode,'id_sub'=>$sub);
			}else{
				$where=array('id_jabatan'=>$kode);
			}
			$this->db->where($where);
			$in=$this->db->update($tabel,$data);
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure(); 
			}
			redirect('pages/view_concept/'.$ka);
		}else{
			$this->messages->notValidParam(); 
			redirect('pages/concept'); 
		}
		
	}
	function up_setting(){
		$kode=$this->input->post('kode');
		$tabel=$this->input->post('tabel');
		$jbt=$this->input->post('jabatan');
		$sub=$this->input->post('sub');

		if ($kode == '' || $tabel == '') {
			$this->messages->notValidParam();  
			redirect('pages/concept'); 
		}else{
			$data=array(
				'bobot'=>$this->input->post('bobot'),
				'target'=>$this->input->post('target'),
				'satuan'=>$this->input->post('satuan'),
				'sifat'=>$this->input->post('sifat'),
				'rumus'=>$this->input->post('rumus'),
				'penilai'=>$this->input->post('penilai'),
				'org'=>$this->input->post('penilai_ky'),
				'urutan'=>$this->input->post('urutan'),
			);
			if (array_sum($data['bobot']) < 100) {
				$this->messages->customFailure('Jumlah Bobot yang ada masukkan KURANG dari 100'); 
				if ($sub != "") {
					redirect('pages/view_concept_setting/'.$kode.'/'.$jbt.'/'.$sub);
				}else{
					redirect('pages/view_concept_setting/'.$kode.'/'.$jbt);
				}
			}elseif (array_sum($data['bobot']) > 100) {
				$this->messages->customFailure('Jumlah Bobot yang ada masukkan LEBIH dari 100');  
				if ($sub != "") {
					redirect('pages/view_concept_setting/'.$kode.'/'.$jbt.'/'.$sub);
				}else{
					redirect('pages/view_concept_setting/'.$kode.'/'.$jbt);
				}
			}else{
				foreach ($data['bobot'] as $k => $v) {
					if ($data['penilai'][$k] == 'P4') {
						$data_o[$k]=array();
						foreach ($data['org'][$k] as $vl) {
							if ($vl == 'ALL') {
								$a_em=$this->db->query("SELECT id_karyawan FROM karyawan")->result();
								foreach ($a_em as $all) {
									array_push($data_o[$k], $all->id_karyawan);
								}
							}else{
								array_push($data_o[$k], $vl);
							}
						}
						$org[$k]=implode(';', array_unique($data_o[$k]));
					}else{
						$org[$k]=NULL;
					}
					$datain=array(
						'bobot'=>$v,
						'target'=>$data['target'][$k],
						'satuan'=>$data['satuan'][$k],
						'sifat'=>$data['sifat'][$k],
						'rumus'=>$data['rumus'][$k],
						'urutan'=>$data['urutan'][$k],
						'id_penilai'=>$org[$k],
						'kode_penilai'=>$data['penilai'][$k],
					);
					// echo '<pre>';
					// print_r($datain);
					// echo '</pre>';
					if ($sub != "") {
						$where=array('id_jabatan'=>$jbt,'kode_indikator'=>$k,'id_sub'=>$sub);
					}else{
						$where=array('id_jabatan'=>$jbt,'kode_indikator'=>$k);
					}

					$this->db->where($where);
					$this->db->update($tabel,$datain);

				}
				$this->messages->allGood();
				redirect('pages/view_concept/'.$kode);
				
			}
		}
	}
	function edt_avl_config(){
		$nm=$this->input->post('tabel');
		$kode=$this->input->post('kode');
		if ($nm == "" || $kode == "") {
			$this->messages->notValidParam();  
			redirect('pages/concept');
		}else{
			$jb=$this->input->post('jbt');
			$emp=$this->input->post('emp');
			$ind=$this->input->post('indikator');
			$konsol=$this->input->post('konsolidasi');
			$sub=$this->input->post('sub');
			$data=array(
				'jabat'=>$jb,
				'emp'=>$emp,
				'indik'=>$ind,
				'konsol'=>$konsol,
			);
			if ($data['jabat'] == '') {
				$this->messages->customFailure('Pilih Salah Satu Data!');
				redirect('pages/view_concept/'.$kode); 
			}else{
				$jabata=array_unique($data['jabat']);
				foreach ($jabata as $kk=>$j) {
					$jbtn=$this->db->get_where('master_jabatan',array('id_jabatan'=>$j))->row_array();
					if (isset($sub[$j])) {
						foreach ($sub[$j] as $sb) {
							if (!isset($data['indik'][$sb])) {
								$this->messages->customFailure('Pilih Salah Satu Data!');
								if ($nm == NULL || $nm == "") {
									$this->dbforge->drop_table($nm,TRUE);
									$upagd1=array('nama_tabel'=>NULL);
									$this->db->where('kode_c_kpi',$kode);
									$this->db->update('concept_kpi',$upagd1); 
								}
								redirect('pages/view_concept/'.$kode);
							}else{
								if (!isset($data['konsol'][$sb])) {
									$kons_s=0;
								}else{
									$kons_s=1;
								}
								$m_sub=$this->db->get_where('master_sub_jabatan',array('id_sub'=>$sb))->row_array();
								$loker=$this->model_master->k_loker($m_sub['kode_loker']);
								if (isset($data['emp'][$sb])) {
									foreach ($data['emp'][$sb] as $kar_s) {

										foreach ($data['indik'][$sb] as $ind_s) {
											$kry=$this->model_karyawan->emp($kar_s);
											$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$kry['loker_pa']))->row_array();
											$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$kry['jabatan_pa']))->row_array();
											$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind_s))->row_array();
											$datain_s=array(
												'kode_indikator'=>$ind_s,
												'indikator'=>$indi['kpi'],
												'cara_mengukur'=>$indi['cara_mengukur'],
												'rumus'=>$indi['rumus'],
												'sumber'=>$indi['sumber'],
												'polarisasi'=>$indi['polarisasi'],
												'kaitan'=>$indi['kaitan'],
												'periode'=>$indi['periode_pelaporan'],
												'id_karyawan'=>$kar_s,
												'konsolidasi'=>$kons_s,
												'id_jabatan'=>$j,
												'jabatan'=>$jbtn['jabatan'],
												'id_loker'=>$loker['id_loker'],
												'loker'=>$loker['nama'],
												'id_sub'=>$sb,
												'sub'=>$m_sub['nama_sub'],
												'id_jabatan_pa'=>$kry['jabatan_pa'],
												'jabatan_pa'=>$jabatan_p['jabatan'],
												'id_loker_pa'=>$kry['loker_pa'],
												'loker_pa'=>$loker_p['nama'],
											);
											$this->db->insert($nm,$datain_s);
								// 			echo '<pre>';
								// print_r($datain_s);
								// echo '</pre>';
										}
									}
									
								}else{
									$nmj=$m_sub['kode_sub'];
									$dtk=$this->db->get_where('karyawan',array('kode_sub'=>$nmj))->result();
									foreach ($dtk as $ka) {
										foreach ($data['indik'][$sb] as $ind_s) {
											$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind_s))->row_array();
											$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$ka->loker_pa))->row_array();
											$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$ka->jabatan_pa))->row_array();
											$datain1_s=array(
												'kode_indikator'=>$ind_s,
												'indikator'=>$indi['kpi'],
												'cara_mengukur'=>$indi['cara_mengukur'],
												'rumus'=>$indi['rumus'],
												'sumber'=>$indi['sumber'],
												'polarisasi'=>$indi['polarisasi'],
												'kaitan'=>$indi['kaitan'],
												'periode'=>$indi['periode_pelaporan'],
												'id_karyawan'=>$ka->id_karyawan,
												'konsolidasi'=>$kons_s,
												'id_jabatan'=>$j,
												'id_loker'=>$loker['id_loker'],
												'id_jabatan_pa'=>$ka->jabatan_pa,
												'id_loker_pa'=>$ka->loker_pa,
												'loker_pa'=>$loker_p['nama'],
												'jabatan'=>$jbtn['jabatan'],
												'loker'=>$loker['nama'],
												'jabatan_pa'=>$jabatan_p['jabatan'],
												'id_sub'=>$sb,
												'sub'=>$m_sub['nama_sub'],
											);
											$this->db->insert($nm,$datain1_s);
								// 			echo '<pre>';
								// print_r($datain1_s);
								// echo '</pre>';
										}
									}

								}


							}
						}
					}else{
						if (!isset($data['indik'][$j])) {
							$this->messages->customFailure('Pilih Salah Satu Data!');
							if ($nm == NULL || $nm == "") {
								$this->dbforge->drop_table($nm,TRUE);
								$upagd1=array('nama_tabel'=>NULL);
								$this->db->where('kode_c_kpi',$kode);
								$this->db->update('concept_kpi',$upagd1); 
							} 
							redirect('pages/view_concept/'.$kode);
						}else{
							if (!isset($data['konsol'][$j])) {
								$kons=0;
							}else{
								$kons=1;
							}
							if (isset($data['emp'][$j])) {
								foreach ($data['emp'][$j] as $kar) {
									foreach ($data['indik'][$j] as $ind) {
										$kry=$this->model_karyawan->emp($kar);
										$loker=$this->model_master->k_loker($kry['unit']);
										$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$kry['loker_pa']))->row_array();
										$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$kry['jabatan_pa']))->row_array();
										$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind))->row_array();
										$datain=array(
											'kode_indikator'=>$ind,
											'indikator'=>$indi['kpi'],
											'cara_mengukur'=>$indi['cara_mengukur'],
											'rumus'=>$indi['rumus'],
											'sumber'=>$indi['sumber'],
											'polarisasi'=>$indi['polarisasi'],
											'kaitan'=>$indi['kaitan'],
											'periode'=>$indi['periode_pelaporan'],
											'id_karyawan'=>$kar,
											'konsolidasi'=>$kons,
											'id_jabatan'=>$j,
											'jabatan'=>$jbtn['jabatan'],
											'id_loker'=>$loker['id_loker'],
											'loker'=>$loker['nama'],
											'id_jabatan_pa'=>$kry['jabatan_pa'],
											'jabatan_pa'=>$jabatan_p['jabatan'],
											'id_loker_pa'=>$kry['loker_pa'],
											'loker_pa'=>$loker_p['nama'],

										);
										$this->db->insert($nm,$datain);
									// echo '<pre>nosub';
									// print_r($datain);
									// echo '</pre>';
									}
								}
							}else{
								$nmj=$jbtn['kode_jabatan'];
								$dtk=$this->db->get_where('karyawan',array('jabatan'=>$nmj))->result();
								foreach ($dtk as $ka) {
									foreach ($data['indik'][$j] as $ind) {
										$loker=$this->model_master->k_loker($ka->unit);
										$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind))->row_array();
										$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$ka->loker_pa))->row_array();
										$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$ka->jabatan_pa))->row_array();
										$datain1=array(
											'kode_indikator'=>$ind,
											'indikator'=>$indi['kpi'],
											'cara_mengukur'=>$indi['cara_mengukur'],
											'rumus'=>$indi['rumus'],
											'sumber'=>$indi['sumber'],
											'polarisasi'=>$indi['polarisasi'],
											'kaitan'=>$indi['kaitan'],
											'periode'=>$indi['periode_pelaporan'],
											'id_karyawan'=>$ka->id_karyawan,
											'konsolidasi'=>$kons,
											'id_jabatan'=>$j,
											'id_loker'=>$loker['id_loker'],
											'id_jabatan_pa'=>$ka->jabatan_pa,
											'id_loker_pa'=>$ka->loker_pa,
											'loker_pa'=>$loker_p['nama'],
											'jabatan'=>$jbtn['jabatan'],
											'loker'=>$loker['nama'],
											'jabatan_pa'=>$jabatan_p['jabatan'],
										);
										$this->db->insert($nm,$datain1);
									// echo '<pre>nosub';
									// print_r($datain1);
									// echo '</pre>';
									}
								}
							}
						}
					}
				}

				

			// foreach ($data['jabat'] as $kk=>$j) {
			// 	if (!isset($data['indik'][$j])) {
			// 		$this->messages->customFailure('Pilih Salah Satu Data!');
			// 		redirect('pages/view_concept/'.$kode);
			// 	}else{
			// 		$jbtn=$this->db->get_where('master_jabatan',array('id_jabatan'=>$j))->row_array();
			// 		if (!isset($data['konsol'][$j])) {
			// 			$kons=0;
			// 		}else{
			// 			$kons=1;
			// 		}
			// 		if ($data['emp'] != "") {
			// 			foreach ($data['emp'][$j] as $kar) {
			// 				foreach ($data['indik'][$j] as $ind) {
			// 					$kry=$this->model_karyawan->emp($kar);
			// 					$loker=$this->model_master->k_loker($kry['unit']);
			// 					$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind))->row_array();
			// 					$datain=array(
			// 						'kode_indikator'=>$ind,
			// 						'indikator'=>$indi['kpi'],
			// 						'cara_mengukur'=>$indi['cara_mengukur'],
			// 						'rumus'=>$indi['rumus'],
			// 						'sumber'=>$indi['sumber'],
			// 						'polarisasi'=>$indi['polarisasi'],
			// 						'kaitan'=>$indi['kaitan'],
			// 						'periode'=>$indi['periode_pelaporan'],
			// 						'id_karyawan'=>$kar,
			// 						'konsolidasi'=>$kons,
			// 						'id_jabatan'=>$j,
			// 						'id_loker'=>$loker['id_loker'],
			// 						'id_jabatan_pa'=>$kry['jabatan_pa'],
			// 						'id_loker_pa'=>$kry['loker_pa'],
			// 					);
			// 					$this->db->insert($nm,$datain);
			// 				}
			// 			}
			// 		}else{
			// 			$nmj=$jbtn['kode_jabatan'];
			// 			$dtk=$this->db->get_where('karyawan',array('jabatan'=>$nmj))->result();
			// 			foreach ($dtk as $ka) {
			// 				foreach ($data['indik'][$j] as $ind) {
			// 					$loker=$this->model_master->k_loker($ka->unit);
			// 					$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind))->row_array();
			// 					$datain1=array(
			// 						'kode_indikator'=>$ind,
			// 						'indikator'=>$indi['kpi'],
			// 						'cara_mengukur'=>$indi['cara_mengukur'],
			// 						'rumus'=>$indi['rumus'],
			// 						'sumber'=>$indi['sumber'],
			// 						'polarisasi'=>$indi['polarisasi'],
			// 						'kaitan'=>$indi['kaitan'],
			// 						'periode'=>$indi['periode_pelaporan'],
			// 						'id_karyawan'=>$ka->id_karyawan,
			// 						'konsolidasi'=>$kons,
			// 						'id_jabatan'=>$j,
			// 						'id_loker'=>$loker['id_loker'],
			// 						'id_jabatan_pa'=>$ka->jabatan_pa,
			// 						'id_loker_pa'=>$ka->loker_pa,
			// 					);
			// 					$this->db->insert($nm,$datain1);
			// 				}
			// 			}
			// 		}
			// 	}
			// }
				$this->messages->allGood(); 
				$u_s=array('id_sub'=>NULL);
				$this->db->where('id_sub',0);
				$this->db->update($nm,$u_s); 
				redirect('pages/view_concept/'.$kode);
			}
		}
	}
	function add_config(){
		$jb=$this->input->post('jbt');
		$emp=$this->input->post('emp');
		$ind=$this->input->post('indikator');
		$kode=$this->input->post('kode');
		$konsol=$this->input->post('konsolidasi');
		$sub=$this->input->post('sub');
		$data=array(
			'jabat'=>$jb,
			'emp'=>$emp,
			'indik'=>$ind,
			'konsol'=>$konsol,
		);
		if ($data['jabat'] == '') {
			$this->messages->customFailure('Pilih Salah Satu Data!');
			redirect('pages/view_concept/'.$kode); 
		}
		$fields = array(
				'id_task' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'urutan' => array(
					'type' => 'INT',
					'constraint' => 100,
					'null'=> TRUE
				),
				'kode_indikator' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null'=> TRUE
				),
				'indikator' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'cara_mengukur' => array(
					'type' => 'LONGTEXT',
					'null'=> TRUE
				),
				'rumus' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'sumber' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'id_karyawan' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'id_jabatan' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'jabatan' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'id_sub' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'sub' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'id_loker' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'loker' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'id_jabatan_pa' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'jabatan_pa' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'id_loker_pa' => array(
					'type' => 'BIGINT',
					'constraint' => 255,
					'null'=> TRUE
				),
				'loker_pa' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'bobot' => array(
					'type' => 'FLOAT',
					'default'=> '0'
				),
				'bobot_out' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'default'=> '0'
				),
				'bobot_skp' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'default'=> '0'
				),
				'bobot_tc' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'default'=> '0'
				),
				'satuan' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null'=> TRUE
				),
				'periode' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'kaitan' => array(
					'type' => 'INT',
					'constraint' => 2,
					'default'=>'0'
				),
				'konsolidasi' => array(
					'type' => 'INT',
					'constraint' => 2,
					'default'=>'0'
				),
				'polarisasi' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'sifat' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null'=> TRUE
				),
				'kode_agenda' => array(
					'type' => 'VARCHAR',
					'constraint' => 300,
					'null'=> TRUE
				),
				'kode_penilai' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null'=> TRUE
				),
				'id_penilai' => array(
					'type' => 'VARCHAR',
					'constraint' => 1000,
					'null'=> TRUE
				),
				'ln1' => array(
					'type' => 'VARCHAR',
					'constraint' => 2000,
					'default'=>'0'
				),
				'nra1' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'na1' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'ln2' => array(
					'type' => 'VARCHAR',
					'constraint' => 2000,
					'default'=>'0'
				),
				'nra2' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'na2' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'ln3' => array(
					'type' => 'VARCHAR',
					'constraint' => 2000,
					'default'=>'0'
				),
				'nra3' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'na3' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'ln4' => array(
					'type' => 'VARCHAR',
					'constraint' => 2000,
					'default'=>'0'
				),
				'nra4' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'na4' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'ln5' => array(
					'type' => 'VARCHAR',
					'constraint' => 2000,
					'default'=>'0'
				),
				'nra5' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'na5' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'ln6' => array(
					'type' => 'VARCHAR',
					'constraint' => 2000,
					'default'=>'0'
				),
				'nra6' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'na6' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'target' => array(
					'type' => 'FLOAT',
					'default'=>'100'
				),
				'realisasi' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'pencapaian' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'nilai_out' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'nilai_tc' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				),
				'nilai_akhir' => array(
					'type' => 'FLOAT',
					'default'=>'0'
				)
			);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id_task', TRUE);
		$nm='c'.uniqid();
		$this->dbforge->create_table($nm);
		$upagd=array('nama_tabel'=>$nm);
		$this->db->where('kode_c_kpi',$kode);
		$this->db->update('concept_kpi',$upagd);
		$jabata=array_unique($data['jabat']);
		foreach ($jabata as $kk=>$j) {
			$jbtn=$this->db->get_where('master_jabatan',array('id_jabatan'=>$j))->row_array();
			if (isset($sub[$j])) {
				foreach ($sub[$j] as $sb) {
					if (!isset($data['indik'][$sb])) {
						$this->messages->customFailure('Pilih Salah Satu Data!');
						$this->dbforge->drop_table($nm,TRUE);
						$upagd1=array('nama_tabel'=>NULL);
						$this->db->where('kode_c_kpi',$kode);
						$this->db->update('concept_kpi',$upagd1); 
						redirect('pages/view_concept/'.$kode);
					}else{
						if (!isset($data['konsol'][$sb])) {
							$kons_s=0;
						}else{
							$kons_s=1;
						}
						$m_sub=$this->db->get_where('master_sub_jabatan',array('id_sub'=>$sb))->row_array();
						$loker=$this->model_master->k_loker($m_sub['kode_loker']);
						if (isset($data['emp'][$sb])) {
							foreach ($data['emp'][$sb] as $kar_s) {
								foreach ($data['indik'][$sb] as $ind_s) {
									$kry=$this->model_karyawan->emp($kar_s);
									$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$kry['loker_pa']))->row_array();
									$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$kry['jabatan_pa']))->row_array();
									$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind_s))->row_array();
									$datain_s=array(
										'kode_indikator'=>$ind_s,
										'indikator'=>$indi['kpi'],
										'cara_mengukur'=>$indi['cara_mengukur'],
										'rumus'=>$indi['rumus'],
										'sumber'=>$indi['sumber'],
										'polarisasi'=>$indi['polarisasi'],
										'kaitan'=>$indi['kaitan'],
										'periode'=>$indi['periode_pelaporan'],
										'id_karyawan'=>$kar_s,
										'konsolidasi'=>$kons_s,
										'id_jabatan'=>$j,
										'jabatan'=>$jbtn['jabatan'],
										'id_loker'=>$loker['id_loker'],
										'loker'=>$loker['nama'],
										'id_sub'=>$sb,
										'sub'=>$m_sub['nama_sub'],
										'id_jabatan_pa'=>$kry['jabatan_pa'],
										'jabatan_pa'=>$jabatan_p['jabatan'],
										'id_loker_pa'=>$kry['loker_pa'],
										'loker_pa'=>$loker_p['nama'],
									);
									$this->db->insert($nm,$datain_s);
								}
							}
						}else{
							$nmj=$m_sub['kode_sub'];
							$dtk=$this->db->get_where('karyawan',array('kode_sub'=>$nmj))->result();
							foreach ($dtk as $ka) {
								foreach ($data['indik'][$sb] as $ind_s) {
									$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind_s))->row_array();
									$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$ka->loker_pa))->row_array();
									$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$ka->jabatan_pa))->row_array();
									$datain1_s=array(
										'kode_indikator'=>$ind_s,
										'indikator'=>$indi['kpi'],
										'cara_mengukur'=>$indi['cara_mengukur'],
										'rumus'=>$indi['rumus'],
										'sumber'=>$indi['sumber'],
										'polarisasi'=>$indi['polarisasi'],
										'kaitan'=>$indi['kaitan'],
										'periode'=>$indi['periode_pelaporan'],
										'id_karyawan'=>$ka->id_karyawan,
										'konsolidasi'=>$kons_s,
										'id_jabatan'=>$j,
										'id_loker'=>$loker['id_loker'],
										'id_jabatan_pa'=>$ka->jabatan_pa,
										'id_loker_pa'=>$ka->loker_pa,
										'loker_pa'=>$loker_p['nama'],
										'jabatan'=>$jbtn['jabatan'],
										'loker'=>$loker['nama'],
										'jabatan_pa'=>$jabatan_p['jabatan'],
										'id_sub'=>$sb,
										'sub'=>$m_sub['nama_sub'],
									);
									$this->db->insert($nm,$datain1_s);
								}
							}
						}
					}
				}
			}else{
				if (!isset($data['indik'][$j])) {
					$this->messages->customFailure('Pilih Salah Satu Data!');
					$this->dbforge->drop_table($nm,TRUE);
					$upagd1=array('nama_tabel'=>NULL);
					$this->db->where('kode_c_kpi',$kode);
					$this->db->update('concept_kpi',$upagd1); 
					redirect('pages/view_concept/'.$kode);
				}else{
					if (!isset($data['konsol'][$j])) {
						$kons=0;
					}else{
						$kons=1;
					}
					if (isset($data['emp'][$j])) {
						foreach ($data['emp'][$j] as $kar) {
							foreach ($data['indik'][$j] as $ind) {
								$kry=$this->model_karyawan->emp($kar);
								$loker=$this->model_master->k_loker($kry['unit']);
								$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$kry['loker_pa']))->row_array();
								$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$kry['jabatan_pa']))->row_array();
								$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind))->row_array();
								$datain=array(
									'kode_indikator'=>$ind,
									'indikator'=>$indi['kpi'],
									'cara_mengukur'=>$indi['cara_mengukur'],
									'rumus'=>$indi['rumus'],
									'sumber'=>$indi['sumber'],
									'polarisasi'=>$indi['polarisasi'],
									'kaitan'=>$indi['kaitan'],
									'periode'=>$indi['periode_pelaporan'],
									'id_karyawan'=>$kar,
									'konsolidasi'=>$kons,
									'id_jabatan'=>$j,
									'jabatan'=>$jbtn['jabatan'],
									'id_loker'=>$loker['id_loker'],
									'loker'=>$loker['nama'],
									'id_jabatan_pa'=>$kry['jabatan_pa'],
									'jabatan_pa'=>$jabatan_p['jabatan'],
									'id_loker_pa'=>$kry['loker_pa'],
									'loker_pa'=>$loker_p['nama'],

								);
								$this->db->insert($nm,$datain);
							}
						}
					}else{
						$nmj=$jbtn['kode_jabatan'];
						$dtk=$this->db->get_where('karyawan',array('jabatan'=>$nmj))->result();
						foreach ($dtk as $ka) {
							foreach ($data['indik'][$j] as $ind) {
								$loker=$this->model_master->k_loker($ka->unit);
								$indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$ind))->row_array();
								$loker_p=$this->db->get_where('master_loker',array('id_loker'=>$ka->loker_pa))->row_array();
								$jabatan_p=$this->db->get_where('master_jabatan',array('id_jabatan'=>$ka->jabatan_pa))->row_array();
								$datain1=array(
									'kode_indikator'=>$ind,
									'indikator'=>$indi['kpi'],
									'cara_mengukur'=>$indi['cara_mengukur'],
									'rumus'=>$indi['rumus'],
									'sumber'=>$indi['sumber'],
									'polarisasi'=>$indi['polarisasi'],
									'kaitan'=>$indi['kaitan'],
									'periode'=>$indi['periode_pelaporan'],
									'id_karyawan'=>$ka->id_karyawan,
									'konsolidasi'=>$kons,
									'id_jabatan'=>$j,
									'id_loker'=>$loker['id_loker'],
									'id_jabatan_pa'=>$ka->jabatan_pa,
									'id_loker_pa'=>$ka->loker_pa,
									'loker_pa'=>$loker_p['nama'],
									'jabatan'=>$jbtn['jabatan'],
									'loker'=>$loker['nama'],
									'jabatan_pa'=>$jabatan_p['jabatan'],
								);
								$this->db->insert($nm,$datain1);
							}
						}
					}
				}
			}
		}
		$this->messages->allGood(); 
		$u_s=array('id_sub'=>NULL);
		$this->db->where('id_sub',0);
		$this->db->update($nm,$u_s); 
		redirect('pages/view_concept/'.$kode);
		
	}
	function del_employee_task(){
		$id=$this->input->post('id');
		$tabel=$this->input->post('tabel');
		$idj=$this->input->post('idj');
		$kode=$this->input->post('kode');
		if ($id == "" || $tabel == "" || $kode == "" || $idj == "") {
			$this->messages->notValidParam();  
			redirect('pages/view_concept_setting/'.$kode.'/'.$idj);
		}else{
			$this->db->where('id_karyawan',$id);
			$in=$this->db->delete($tabel);
			if ($in) {
				$this->messages->delGood(); 
			}else{
				$this->messages->delFailure(); 
			}
			$dt=$this->db->get_where($tabel,array('id_jabatan'=>$idj))->num_rows();
			if ($dt == 0) {
				redirect('pages/view_concept/'.$kode);
			}else{
				redirect('pages/view_concept_setting/'.$kode.'/'.$idj);
			}
		}
	}
	function bobot_akhir(){
		$tabel=$this->input->post('tabel');
		$idj=$this->input->post('idj');
		$kode=$this->input->post('kode');
		if ($tabel == "" || $kode == "" || $idj == "") {
			$this->messages->notValidParam();  
			redirect('pages/view_concept_setting/'.$kode.'/'.$idj);
		}else{
			$data=array(
				'bobot_out'=>$this->input->post('bobotout'),
				'bobot_skp'=>$this->input->post('bobotskp'),
				'bobot_tc'=>$this->input->post('bobottc'),
			);
			$this->db->where('id_jabatan',$idj);
			$in=$this->db->update($tabel,$data);
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure(); 
			}
			redirect('pages/view_concept_setting/'.$kode.'/'.$idj);
		}
	}
}