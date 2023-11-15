<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	/**
     * Code From GFEACORP. 
     * Web Developer 
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Controller Kagenda (User)
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

	class Kagenda extends CI_Controller
	{
		public function __construct() 
		{
			parent::__construct();
			$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
			if (isset($_SESSION['emp'])) {
				$this->admin = $_SESSION['emp']['id'];	
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
			$nm=explode(" ", $dtroot['admin']['nama']);
			$datax['adm'] = array(
				'nama'=>$nm[0],
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
			);
			$this->dtroot=$datax;			
			$this->max_range=$this->otherfunctions->poin_max_range();
			$this->max_month=$this->otherfunctions->column_value_max_range();
		}
		public function index(){
			redirect('kpages/dashboard');
		}
		public function konversi_gap()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			$datax['data']=[];
			$data=$this->model_master->getListKonversiGap('active');
			if (isset($data)) {
				foreach ($data as $d) {
					$datax['data'][]=[
						'<b style="font-size:14pt">'.$d->nama.'<b>',
						'<b style="font-size:14pt">'.$d->min.'% - '.$d->max.'%</b>',
						'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>',
					];
				}
			}
			echo json_encode($datax);
		}
		public function dashboard_kpi()
		{
			if (!$this->input->is_ajax_request()) 
				redirect('not_found');
			parse_str($this->input->post('search_param'),$search);
			$id=$this->admin;
			$data=$this->model_agenda->getAgendaKpiValidate(0);
			if (isset($data)) {
				foreach ($data as $d) {
					$table[$d->nama_tabel]=$d->nama_tabel;
				}
			}
			if (isset($search['agenda_filter'])) {
				if (!empty($search['agenda_filter'])) {
					$table=[$search['agenda_filter']];
				}
			}
			// $table=[$this->input->post('agenda_filter')];
			// if (empty($this->input->post('agenda_filter'))) {
			// 	$data=$this->model_agenda->getAgendaKpiValidate(0);
			// 	if (isset($data)) {
			// 		foreach ($data as $d) {
			// 			$table[$d->nama_tabel]=$d->nama_tabel;
			// 		}
			// 	}
			// }
			
			$datax['data']=[];
			if (isset($table)) {
				foreach ($table as $tb) {
					$data_tabel=$this->model_agenda->openTableAgendaIdEmployee($tb,$id);
					if (isset($data_tabel)) {
						foreach ($data_tabel as $dt) {
							for ($i=1; $i <=5 ; $i++) { 
								$col='pn'.$i;
								$pack_nilai[$col]=(!empty($dt->$col))?$this->exam->getNilaiAverage($dt->$col):0;
							}
							$total=(isset($pack_nilai))?array_sum($pack_nilai):0;
							$gap=$this->exam->rumusProsentase($dt->target,$total);
							$data_konv=$this->model_master->getKonversiGapNilai($gap);
							$color='green';
							if (isset($data_konv['warna'])) {
								$color=$data_konv['warna'];
							}
							$datax['data'][]=[
								$dt->id_task,
								'<b style="font-size:16pt;">'.$dt->kode_kpi.'</b>',
								'<b style="font-size:16pt;">'.$dt->kpi.'</b>',
								'<b style="font-size:16pt;">'.$dt->sifat.'</b>',
								'<b style="font-size:16pt;">'.$dt->target.'</b>',
								'<b style="font-size:16pt;">'.$this->formatter->getNumberFloat(((isset($pack_nilai['pn1']))?$pack_nilai['pn1']:0)).'</b>',
								'<b style="font-size:16pt;">'.$this->formatter->getNumberFloat(((isset($pack_nilai['pn2']))?$pack_nilai['pn2']:0)).'</b>',
								'<b style="font-size:16pt;">'.$this->formatter->getNumberFloat(((isset($pack_nilai['pn3']))?$pack_nilai['pn3']:0)).'</b>',
								'<b style="font-size:16pt;">'.$this->formatter->getNumberFloat(((isset($pack_nilai['pn4']))?$pack_nilai['pn4']:0)).'</b>',
								'<b style="font-size:16pt;">'.$this->formatter->getNumberFloat($total).'</b>',
								'<b style="font-size:40pt;color:'.$color.';">'.$this->formatter->getNumberFloat($gap).'%</b>',
							];
						}
					}
				}
			}
			echo json_encode($datax);
		}
		function del_notif_users(){
			$kode=$this->input->post('kode');
			$cek=$this->model_master->k_notif($kode);
			if ($kode != "" && count($cek) > 0) {
				$id=explode(';', $cek['id_del']);
				if (!in_array($this->admin, $id)) {
					array_push($id, $this->admin);
				}
				if (isset($id)) {
					$idd=implode(';', array_unique(array_filter($id)));
				}else{
					$idd=NULL;
				}
				$data=array('id_del'=>$idd,);
				$this->db->where('kode_notif',$kode);
				$in=$this->db->update('notification',$data);
				if ($in) {
					$this->messages->delGood();
				}else{
					$this->messages->delFailure();
				}
			}else{
				$this->messages->notValidParam();  
			}
			redirect('kpages/read_all_notification');
		}
		

		function input_value(){
			$kode=$this->input->post('kode');
			$id=$this->input->post('id');
			$tb=$this->input->post('tabel');
			$penilai=$this->input->post('penilai');
			if ($kode == "" || $tb == "" || $id == "") {
				$this->messages->notValidParam();  
				redirect('kpages/tasks');
			}else{
				$ln1=$this->input->post('ln1');
				$ln2=$this->input->post('ln2');
				$ln3=$this->input->post('ln3');
				$ln4=$this->input->post('ln4');
				$ln5=$this->input->post('ln5');
				$ln6=$this->input->post('ln6');
				if (isset($ln1)) {
					foreach ($ln1 as $k => $v) {

						$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
						if ($v != "") {	
							$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';
							if ($old['ln1'] != '0') {
								$ni3[$k]=explode(',', $old['ln1'].''.$ni1[$k]);
								foreach ($ni3[$k] as $nn3[$k]) {
									$fn=str_replace('{', '', $nn3[$k]);
									$bc=str_replace('}', '', $fn);
									$bc1=explode(' ', $bc);
									if ($bc1[0] == "KAR") {
										$bc2=explode(':', $bc1[1]);
										if ($bc2[0] == $this->admin) {
											if ($v != $bc2[1]) {
												$va='{KAR '.$this->admin.':'.$bc2[1].'}';
												if (($key = array_search($va, $ni3[$k])) !== false) {
													unset($ni3[$k][$key]);
												}
											}
										}
									}
								}
								$ni2[$k]=array_unique($ni3[$k]);
							}else{
								$ni2[$k]=explode(',', $ni1[$k]);
							}
							$ni[$k]=array_filter($ni2[$k]);
							foreach ($ni[$k] as $naa[$k]) {
								$front[$k]= str_replace('{', '', $naa[$k]);
								$back1[$k]= str_replace('}', '', $front[$k]);
								$back2[$k]=explode(' ', $back1[$k]);
								if ($back2[$k][0] == 'KAR') {
									$back3[$k]=explode(':', $back2[$k][1]);
									$back[$k]=$back3[$k][1];
								}else{
									$back3[$k]=explode(':', $back2[$k][1]);
									$back[$k]=$back3[$k][1];
								}
								$nfa1[$k][]=$back[$k];
							}
							$nf1[$k]=array_sum($nfa1[$k])/count($ni[$k]);
							$nn[$k]=($old['bobot']/100)*$nf1[$k];
							if ($old['ln1'] != '0') {
								$nii1[$k]=implode(',', $ni2[$k]);
							}else{
								$nii1[$k]=$ni1[$k];
							}
							$data[$k]=array(
								'ln1'=>$nii1[$k],
								'nra1'=>$nf1[$k],
								'na1'=>$nn[$k],
							);
						/*
						echo '<pre>';
						print_r($data);
						echo '<pre>';
						*/
						$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
						$this->db->where($wh);
						$this->db->update($tb,$data[$k]);

					}
					$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($new['ln1'] != '0') {
						$nii[$k][]=$new['na1'];
					}
				}
			}
			
			if (isset($ln2)) {
				foreach ($ln2 as $k => $v) {

					$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($v != "") {
						$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

						if ($old['ln2'] != '0') {
							$ni3[$k]=explode(',', $old['ln2'].''.$ni1[$k]);
							foreach ($ni3[$k] as $nn3[$k]) {
								$fn=str_replace('{', '', $nn3[$k]);
								$bc=str_replace('}', '', $fn);
								$bc1=explode(' ', $bc);
								if ($bc1[0] == "KAR") {
									$bc2=explode(':', $bc1[1]);
									if ($bc2[0] == $this->admin) {
										if ($v != $bc2[1]) {
											$va='{KAR '.$this->admin.':'.$bc2[1].'}';
											if (($key = array_search($va, $ni3[$k])) !== false) {
												unset($ni3[$k][$key]);
											}
										}
									}
								}
							}
							$ni2[$k]=array_unique($ni3[$k]);
						}else{
							$ni2[$k]=explode(',', $ni1[$k]);
						}

						$ni[$k]=array_filter($ni2[$k]);
						foreach ($ni[$k] as $naa[$k]) {

							$front[$k]= str_replace('{', '', $naa[$k]);
							$back1[$k]= str_replace('}', '', $front[$k]);
							$back2[$k]=explode(' ', $back1[$k]);

							if ($back2[$k][0] == 'KAR') {
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}else{
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}
							
							$nfa2[$k][]=$back[$k];
						}
						$nf1[$k]=array_sum($nfa2[$k])/count($ni[$k]);
						$nn[$k]=($old['bobot']/100)*$nf1[$k];
						if ($old['ln2'] != '0') {
							$nii1[$k]=implode(',', $ni2[$k]);
						}else{
							$nii1[$k]=$ni1[$k];
						}
						//print_r($nf[$k]);
						$data[$k]=array(
							'ln2'=>$nii1[$k],
							'nra2'=>$nf1[$k],
							'na2'=>$nn[$k],
						);
						$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
						$this->db->where($wh);
						$this->db->update($tb,$data[$k]);

					}
					$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($new['ln2'] != '0') {
						$nii[$k][]=$new['na2'];
					}
				}
			}
			if (isset($ln3)) {
				foreach ($ln3 as $k => $v) {

					$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($v != "") {
						$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

						if ($old['ln3'] != '0') {
							$ni3[$k]=explode(',', $old['ln3'].''.$ni1[$k]);
							foreach ($ni3[$k] as $nn3[$k]) {
								$fn=str_replace('{', '', $nn3[$k]);
								$bc=str_replace('}', '', $fn);
								$bc1=explode(' ', $bc);
								if ($bc1[0] == "KAR") {
									$bc2=explode(':', $bc1[1]);
									if ($bc2[0] == $this->admin) {
										if ($v != $bc2[1]) {
											$va='{KAR '.$this->admin.':'.$bc2[1].'}';
											if (($key = array_search($va, $ni3[$k])) !== false) {
												unset($ni3[$k][$key]);
											}
										}
									}
								}
							}
							$ni2[$k]=array_unique($ni3[$k]);
						}else{
							$ni2[$k]=explode(',', $ni1[$k]);
						}

						$ni[$k]=array_filter($ni2[$k]);
						foreach ($ni[$k] as $naa[$k]) {

							$front[$k]= str_replace('{', '', $naa[$k]);
							$back1[$k]= str_replace('}', '', $front[$k]);
							$back2[$k]=explode(' ', $back1[$k]);

							if ($back2[$k][0] == 'KAR') {
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}else{
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}
							
							$nfa3[$k][]=$back[$k];
						}
						$nf1[$k]=array_sum($nfa3[$k])/count($ni[$k]);
						$nn[$k]=($old['bobot']/100)*$nf1[$k];
						if ($old['ln3'] != '0') {
							$nii1[$k]=implode(',', $ni2[$k]);
						}else{
							$nii1[$k]=$ni1[$k];
						}
						//print_r($nf[$k]);
						$data[$k]=array(
							'ln3'=>$nii1[$k],
							'nra3'=>$nf1[$k],
							'na3'=>$nn[$k],
						);
						$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
						$this->db->where($wh);
						$this->db->update($tb,$data[$k]);

					}
					$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($new['ln3'] != '0') {
						$nii[$k][]=$new['na3'];
					}
				}
			}
			if (isset($ln4)) {
				foreach ($ln4 as $k => $v) {

					$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($v != "") {
						$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

						if ($old['ln4'] != '0') {
							$ni3[$k]=explode(',', $old['ln4'].''.$ni1[$k]);
							foreach ($ni3[$k] as $nn3[$k]) {
								$fn=str_replace('{', '', $nn3[$k]);
								$bc=str_replace('}', '', $fn);
								$bc1=explode(' ', $bc);
								if ($bc1[0] == "KAR") {
									$bc2=explode(':', $bc1[1]);
									if ($bc2[0] == $this->admin) {
										if ($v != $bc2[1]) {
											$va='{KAR '.$this->admin.':'.$bc2[1].'}';
											if (($key = array_search($va, $ni3[$k])) !== false) {
												unset($ni3[$k][$key]);
											}
										}
									}
								}
							}
							$ni2[$k]=array_unique($ni3[$k]);
						}else{
							$ni2[$k]=explode(',', $ni1[$k]);
						}

						$ni[$k]=array_filter($ni2[$k]);
						foreach ($ni[$k] as $naa[$k]) {

							$front[$k]= str_replace('{', '', $naa[$k]);
							$back1[$k]= str_replace('}', '', $front[$k]);
							$back2[$k]=explode(' ', $back1[$k]);

							if ($back2[$k][0] == 'KAR') {
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}else{
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}
							
							$nfa4[$k][]=$back[$k];
						}
						$nf1[$k]=array_sum($nfa4[$k])/count($ni[$k]);
						$nn[$k]=($old['bobot']/100)*$nf1[$k];
						if ($old['ln4'] != '0') {
							$nii1[$k]=implode(',', $ni2[$k]);
						}else{
							$nii1[$k]=$ni1[$k];
						}
						//print_r($nf[$k]);
						$data[$k]=array(
							'ln4'=>$nii1[$k],
							'nra4'=>$nf1[$k],
							'na4'=>$nn[$k],
						);
						$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
						$this->db->where($wh);
						$this->db->update($tb,$data[$k]);

					}
					$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($new['ln4'] != '0') {
						$nii[$k][]=$new['na4'];
					}
				}
			}
			if (isset($ln5)) {
				foreach ($ln5 as $k => $v) {

					$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($v != "") {
						$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

						if ($old['ln5'] != '0') {
							$ni3[$k]=explode(',', $old['ln5'].''.$ni1[$k]);
							foreach ($ni3[$k] as $nn3[$k]) {
								$fn=str_replace('{', '', $nn3[$k]);
								$bc=str_replace('}', '', $fn);
								$bc1=explode(' ', $bc);
								if ($bc1[0] == "KAR") {
									$bc2=explode(':', $bc1[1]);
									if ($bc2[0] == $this->admin) {
										if ($v != $bc2[1]) {
											$va='{KAR '.$this->admin.':'.$bc2[1].'}';
											if (($key = array_search($va, $ni3[$k])) !== false) {
												unset($ni3[$k][$key]);
											}
										}
									}
								}
							}
							$ni2[$k]=array_unique($ni3[$k]);
						}else{
							$ni2[$k]=explode(',', $ni1[$k]);
						}

						$ni[$k]=array_filter($ni2[$k]);
						foreach ($ni[$k] as $naa[$k]) {

							$front[$k]= str_replace('{', '', $naa[$k]);
							$back1[$k]= str_replace('}', '', $front[$k]);
							$back2[$k]=explode(' ', $back1[$k]);

							if ($back2[$k][0] == 'KAR') {
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}else{
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}
							
							$nfa5[$k][]=$back[$k];
						}
						$nf1[$k]=array_sum($nfa5[$k])/count($ni[$k]);
						$nn[$k]=($old['bobot']/100)*$nf1[$k];
						if ($old['ln5'] != '0') {
							$nii1[$k]=implode(',', $ni2[$k]);
						}else{
							$nii1[$k]=$ni1[$k];
						}
						//print_r($nf[$k]);
						$data[$k]=array(
							'ln5'=>$nii1[$k],
							'nra5'=>$nf1[$k],
							'na5'=>$nn[$k],
						);
						$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
						$this->db->where($wh);
						$this->db->update($tb,$data[$k]);

					}
					$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($new['ln5'] != '0') {
						$nii[$k][]=$new['na5'];
					}
				}
			}
			if (isset($ln6)) {
				foreach ($ln6 as $k => $v) {
					
					$old=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($v != "") {
						$ni1[$k]='{'.$penilai.' '.$this->admin.':'.$v.'},';

						if ($old['ln6'] != '0') {
							$ni3[$k]=explode(',', $old['ln6'].''.$ni1[$k]);
							foreach ($ni3[$k] as $nn3[$k]) {
								$fn=str_replace('{', '', $nn3[$k]);
								$bc=str_replace('}', '', $fn);
								$bc1=explode(' ', $bc);
								if ($bc1[0] == "KAR") {
									$bc2=explode(':', $bc1[1]);
									if ($bc2[0] == $this->admin) {
										if ($v != $bc2[1]) {
											$va='{KAR '.$this->admin.':'.$bc2[1].'}';
											if (($key = array_search($va, $ni3[$k])) !== false) {
												unset($ni3[$k][$key]);
											}
										}
									}
								}
							}
							$ni2[$k]=array_unique($ni3[$k]);
						}else{
							$ni2[$k]=explode(',', $ni1[$k]);
						}

						$ni[$k]=array_filter($ni2[$k]);
						foreach ($ni[$k] as $naa[$k]) {

							$front[$k]= str_replace('{', '', $naa[$k]);
							$back1[$k]= str_replace('}', '', $front[$k]);
							$back2[$k]=explode(' ', $back1[$k]);

							if ($back2[$k][0] == 'KAR') {
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}else{
								$back3[$k]=explode(':', $back2[$k][1]);
								$back[$k]=$back3[$k][1];
							}
							
							$nfa6[$k][]=$back[$k];
						}
						$nf1[$k]=array_sum($nfa6[$k])/count($ni[$k]);
						$nn[$k]=($old['bobot']/100)*$nf1[$k];
						if ($old['ln6'] != '0') {
							$nii1[$k]=implode(',', $ni2[$k]);
						}else{
							$nii1[$k]=$ni1[$k];
						}
						//print_r($nf[$k]);
						$data[$k]=array(
							'ln6'=>$nii1[$k],
							'nra6'=>$nf1[$k],
							'na6'=>$nn[$k],
						);
						$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k);
						$this->db->where($wh);
						$this->db->update($tb,$data[$k]);

					}
					$new=$this->db->get_where($tb,array('id_karyawan'=>$id,'kode_indikator'=>$k))->row_array();
					if ($new['ln6'] != '0') {
						$nii[$k][]=$new['na6'];
					}
				}
			}
			foreach ($nii as $k11 => $v11) {
				$na[$k11]=array_sum($nii[$k11])/count($nii[$k11]);
				$data[$k11]=array(
					'nilai_out'=>$na[$k11],
				);
				$wh=array('id_karyawan'=>$id,'kode_indikator'=>$k11);
				$this->db->where($wh);
				$this->db->update($tb,$data[$k11]);
			}
		}
		$this->messages->allGood();
		redirect('kpages/input_tasks_value/'.$kode);
	}
	function up_input_value(){
		$tb=$this->input->post('nmtb');
		$kode_agd=$this->input->post('kode_agenda');
		if ($tb == "") {
			$this->messages->notValidParam();  
			redirect('kpages/input_tasks_value/'.$kode_agd);
		}else{
			$id=$this->input->post('id');
			$data=array('nilai'=>$this->input->post('nilai'),);
			$this->db->where('id_task',$id);
			$in=$this->db->update($tb,$data);
			if ($in) {
				$this->messages->allGood();
			}else{
				$this->messages->allFailure(); 
			}
			redirect('kpages/input_tasks_value/'.$kode_agd);
		}
	}
	public function data_input_sikap()
	{
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getAgendaActive('agenda_sikap');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$tgl = $this->date;
				$datax['data']=[];
				foreach ($data as $da) {
					$nmtb=$da->nama_tabel;
					$id=$this->admin;
					$dt=$this->db->query("SELECT * FROM $nmtb WHERE id_karyawan != '$id'")->result();
					$pp=array();
					$at=array();
					$bw=array();
					$rk=array();
					foreach ($dt as $d) {
						$this_value=array();
						$this_quiz=array();
						$part[$d->id_karyawan]=explode(';', $d->partisipan);
						$n=1;
						$ats[$d->id_karyawan]=array();
						$bwh[$d->id_karyawan]=array();
						$rkn[$d->id_karyawan]=array();
						foreach ($part[$d->id_karyawan] as $p) {
							$p1[$d->id_karyawan]=explode(':', $p);
							if ($p1[$d->id_karyawan][0] == "ATS") {
								array_push($ats[$d->id_karyawan], $p1[$d->id_karyawan][1]);
							}
							if ($p1[$d->id_karyawan][0] == "BWH") {
								array_push($bwh[$d->id_karyawan], $p1[$d->id_karyawan][1]);
							}
							if ($p1[$d->id_karyawan][0] == "RKN") {
								array_push($rkn[$d->id_karyawan], $p1[$d->id_karyawan][1]);
							}
							$n++;
						}
						if (in_array($this->admin, $ats[$d->id_karyawan])) {
							array_push($at, $d->id_karyawan);
							array_push($pp, 'ATS:'.$d->id_karyawan);
						}
						if (in_array($this->admin, $bwh[$d->id_karyawan])) {
							array_push($bw, $d->id_karyawan);
							array_push($pp, 'BWH:'.$d->id_karyawan);
						}
						if (in_array($this->admin, $rkn[$d->id_karyawan])) {
							array_push($rk, $d->id_karyawan);
							array_push($pp, 'RKN:'.$d->id_karyawan);
						}
					}
					$pp1=array_values(array_unique($pp));
					$ky=$this->model_karyawan->getEmployeeId($this->admin);
					$sbg=array();
					$smp=array();
					foreach ($pp1 as $px1) {
						$px=explode(':', $px1);
						array_push($smp, $px[1]);
						if ($px[0] == "ATS") {
							array_push($sbg, "ATS");
						}
						if ($px[0] == "BWH") {
							array_push($sbg, "BWH");
						}
						if ($px[0] == "RKN") {
							array_push($sbg, "RKN");
						}
					}
					foreach ($smp as $smp1) {
						$this_data=$this->db->query("SELECT * FROM $nmtb WHERE id_karyawan = '$smp1'")->result();
						$q=1;
						foreach ($this_data as $t) {
							$ss[$smp1][$q]=$q;
							$part[$t->id_karyawan][$t->partisipan]=$t->partisipan;
							$nilai[$t->id_karyawan][$t->na]=$t->na;
							$k_asp[$t->id_karyawan][$q]=$t->kode_aspek;
							$n_ra[$t->id_karyawan][$q]=$t->nilai_atas;
							$n_rb[$t->id_karyawan][$q]=$t->nilai_bawah;
							$n_rr[$t->id_karyawan][$q]=$t->nilai_rekan;
							$n_rd[$t->id_karyawan][$q]=$t->nilai_diri;
							$q++;
						}
					}                          
					$n2=0;
					foreach ($smp as $ki) {
						foreach ($ss[$ki] as $ssx) {
							array_push($this_quiz, 1);
						}
						if ($sbg[$n2] == "BWH") {
							$ne=1;
							foreach ($n_rb[$ki] as $rd) {
								$r2=array_filter(explode(';', $rd));
								foreach ($r2 as $rx) {
									$rx1=explode(':', $rx);
									if ($rx1[0] == $id) {
										$txx[$ki][$ne]=$rx1[1];
										array_push($this_value, 1);
									}
								}
								$ne++; 
							}
						}elseif ($sbg[$n2] == "ATS") {
							$ne=1;
							foreach ($n_ra[$ki] as $rd) {
								$r2=array_filter(explode(';', $rd));
								foreach ($r2 as $rx) {
									$rx1=explode(':', $rx);
									if ($rx1[0] == $id) {
										$txx[$ki][$ne]=$rx1[1];
										array_push($this_value, 1);
									}
								}
								$ne++; 
							}
						}elseif ($sbg[$n2] == "RKN") {
							$ne=1;
							foreach ($n_rr[$ki] as $rd) {
								$r2=array_filter(explode(';', $rd));
								foreach ($r2 as $rx) {
									$rx1=explode(':', $rx);
									if ($rx1[0] == $id) {
										$txx[$ki][$ne]=$rx1[1];
										array_push($this_value, 1);
									}
								}
								$ne++; 
							}
						}else{
							$ne=1;
							foreach ($n_rd[$ki] as $rd) {
								$txx[$ki][$ne]=$rd;
								array_push($this_value, 1);
								$ne++; 
							}
						}
						$n2++;
					}
					if ($da->keterangan == "not_entry" || !isset($this_quiz)) {
						$jm1=0;
					}else{
						$jm1= (empty(count($this_quiz)))?0:((count($this_value)/count($this_quiz))*100);						
					}
					$nama='';
					$nama.='<a href="'.base_url('kpages/input_attitude_tasks_value/'.$this->codegenerator->encryptChar($da->kode_a_sikap)).'">';
					if($jm1 == 0){
						$nama.='<i class="fa fa-times-circle text-red" data-toggle="tooltip" title="Belum Dinilai"></i> ';
					}elseif ($jm1 > 0 && $jm1 < 100) {
						$nama.='<i class="fa fa-refresh fa-spin text-yellow" data-toggle="tooltip" title="Belum Selesai"></i> ';
					}else{
						$nama.='<i class="fa fa-check-circle text-green" data-toggle="tooltip" title="Selesai"></i> ';
					}
					$nama.=$da->nama.' <br>
					<label class="label label-primary">'.$da->tahun.'</label>';
					$n_periodee=((isset($this->model_master->getListPeriodePenilaianActive()[$da->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$da->periode] : $this->otherfunctions->getMark());
					$nama.='<label class="label label-info">'.$n_periodee.'</label>';
					$nama.='</a>';
					$nama_hasil='';
					$nama_hasil.='<a href="'.base_url('kpages/result_attd_tasks_value/'.$this->codegenerator->encryptChar($da->kode_a_sikap)).'">';
					if($jm1 == 0){
						$nama_hasil.='<i class="fa fa-times-circle text-red" data-toggle="tooltip" title="Belum Dinilai"></i> ';
					}elseif ($jm1 > 0 && $jm1 < 100) {
						$nama_hasil.='<i class="fa fa-refresh fa-spin text-yellow" data-toggle="tooltip" title="Belum Selesai"></i> ';
					}else{
						$nama_hasil.='<i class="fa fa-check-circle text-green" data-toggle="tooltip" title="Selesai"></i> ';
					}
					$nama_hasil.=$da->nama.' <br>
					<label class="label label-primary">'.$da->tahun.'</label>';
					$n_periode=((isset($this->model_master->getListPeriodePenilaianActive()[$da->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$da->periode] : $this->otherfunctions->getMark());
					$nama_hasil.='<label class="label label-info">'.$n_periode.'</label>';
					$nama_hasil.='</a>';
					$progress='';
					$jm=number_format($jm1,2);
					$progress.='<div class="progress active">
					<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$jm.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$jm.'%">
					<b class="text-black">'.$jm.'%</b>
					</div>
					</div>';
					$tanggal='';
					$tanggal.='<label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal"><i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($da->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal"><i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($da->tgl_selesai).' WIB</label>';
					$keterangan='';
					if($jm1 == 0){
						$keterangan.='<label class="label label-danger"><i class="fa fa-times-circle"></i> Anda Belum Menilai</label>';
					}elseif ($jm1 > 0 && $jm1 < 100) {
						$keterangan.='<label class="label label-warning"><i class="fa fa-refresh fa-spin"></i> Anda Belum Selesai Menilai</label>';
					}else{
						$keterangan.='<label class="label label-success"><i class="fa fa-check-circle"></i> Anda Sudah Selesai Menilai</label>';
					}
					if ($keterangan != ''){
						$keterangan.='<br>';
					}
					if (date("Y-m-d H:i:s",strtotime($da->tgl_selesai)) < date("Y-m-d H:i:s",strtotime($tgl))) {
						$keterangan.='<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
					}elseif ((date("Y-m-d H:i:s",strtotime($da->tgl_mulai)) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($da->tgl_selesai)) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
						$keterangan.='<label class="label label-info">Agenda Sedang Berlangsung</label>';
					}
					if ($keterangan != ''){
						$keterangan.='<br>';
					}
					if ($da->validasi == 0) {
						$keterangan.='<label class="label label-danger"><i class="fa fa-times"></i> Nilai Belum Divalidasi</label>';
					}else{
						$keterangan.='<label class="label label-success"><i class="fa fa-check"></i> Nilai Sudah Divalidasi</label>';
					}
					$datax['data'][]=[
						$da->id_a_sikap,
						$nama,
						$progress,
						$tanggal,
						$keterangan,
						$this->codegenerator->encryptChar($da->kode_a_sikap),
						$da->tahun,
						((isset($this->model_master->getListPeriodePenilaianActive()[$da->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$da->periode] : $this->otherfunctions->getMark()),
						$this->codegenerator->encryptChar($da->kode_c_sikap),
						$nama_hasil,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function input_attitude_task_value()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
				// print_r($kode);		
		$id_log = $this->admin;
		$getJabatan = $this->model_karyawan->getEmployeeId($id_log);
		$kode_jabatan = $getJabatan['jabatan'];
		$posisi  = ["ATS","BWH","RKN","DRI"];
		$max_for = 4;
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id=$this->admin;
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
				// echo '<pre>';
				if (isset($data) && isset($pack)) {
					$data_pick=[];
					if (isset($pack['partisipan'])) {
						foreach ($pack['partisipan'] as $k_par => $v_par) {
				// print_r($v_par[$id]);		
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
					if (isset($data_pick)) {
						if (isset($data_pick['karyawan'])) {
							foreach ($data_pick['karyawan'] as $kar) {
								$emp=$this->model_karyawan->getEmployeeId($kar);
								$avg=$this->exam->hitungAverageArray($nilai[$kar]);
								$now=count($nilai[$kar]);
								$count_all=$count[$kar];
								if ($now == 0) {
									$badge='<i class="fa fa-times-circle text-red"></i> ';
									$status='<label class="label label-danger">Belum Menilai</label>';
								}elseif ($now > 0 && $count_all > $now) {
									$badge='<i class="fa fa-refresh text-yellow fa-spin"></i> ';
									$status='<label class="label label-warning">Belum Selesai</label>';
								}else if ($now == $count_all) {
									$badge='<i class="fa fa-check-circle text-green"></i> ';
									$status='<label class="label label-success">Selesai</label>';
								}
								$kode_sikap=$this->codegenerator->encryptChar($kode);
								$kode_aspek=$this->codegenerator->encryptChar(array_values(array_unique($pack['aspek'][$kar]))[0]);
								$datax['data'][]=[
									$kar,
									'<a href="'.base_url('kpages/input_attitude_value/'.$kode_sikap.'/'.$data_pick['c_sebagai'][$kar].':'.$kar.'/'.$kode_aspek).'">'.$badge.$emp['nama'].'</a>',
									$emp['nama_jabatan'],
									$emp['bagian'],
									$emp['nama_loker'],
									($data_pick['sebagai'][$kar])?$data_pick['sebagai'][$kar]:'Unknown',
									$status,
									$this->formatter->getNumberFloat($avg),
									'<a href="'.base_url('kpages/report_attd_value/'.$kode_sikap.'/'.$data_pick['c_sebagai'][$kar].':'.$kar.'/'.$kode_aspek).'" target="_blank">'.$badge.$emp['nama'].'</a>',
								];
							}
						}						
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function input_attitude_value()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->codegenerator->decryptChar($this->input->post('kode_agenda'));
		$kode_aspek=$this->codegenerator->decryptChar($this->input->post('kode_aspek'));
		$tabel=$this->codegenerator->decryptChar($this->input->post('tabel'));
		$id_kar=$this->codegenerator->decryptChar($this->input->post('id'));
		$usage=$this->uri->segment(3);
		$idx=$this->uri->segment(4);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$sa=explode(':', $idx);
				$id=$sa[1];
				$sb=$sa[0].':'.$this->admin;
				$datax=[];
				$data=$this->model_agenda->getTabelSikapId($tabel,$id,$kode_aspek);
				$datas=$this->model_agenda->openTableAgendaIdEmployee($tabel,$id);
				$n1=1;
				foreach ($datas as $d) {
					$aspekx[$n1]=$d->kode_aspek;
					$part=explode(';', $d->partisipan);
					if (in_array($sb, $part)) {
						$idkk[]=$d->id_karyawan;
					}
					$n1++;
				}
				$kar=$this->model_karyawan->getEmployeeId($id);
				$ky=$this->model_karyawan->getEmployeeId($this->admin);
				$pp1=array_values(array_unique($idkk));
				$sbg=$sa[0];
				$x=1;
				foreach ($data as $d1) {
					$kuisioner[$x]=$d1->kuisioner;
					$kode_kuisioner[$x]=$d1->kode_kuisioner;
					$ats[$x]=$d1->atas;
					$bwh[$x]=$d1->bawah;
					$kode_k[$x]=$d1->kode_kuisioner;
					if ($sbg == "DRI") {
						if ($d1->nilai_dri != NULL) {
							$nilai[$x]=$d1->nilai_diri;
							$ket[$x]=$d1->keterangan_diri;
							$avl[$x]=$d1->nilai_diri;
						}else{
							$nilai[$x]=NULL;
							$ket[$x]=NULL;
						}
					}elseif ($sbg == "ATS") {
						if ($d1->nilai_atas != NULL) {
							$o=explode(';', $d1->nilai_atas);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_atas);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}elseif ($sbg == "BWH") {
						if ($d1->nilai_bawah != NULL) {
							$o=explode(';', $d1->nilai_bawah);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_bawah);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}elseif ($sbg == "RKN") {
						if ($d1->nilai_rekan != NULL) {
							$o=explode(';', $d1->nilai_rekan);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_rekan);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}
					if (!isset($nilai[$x])) {
						$nilai[$x]=NULL;
					}
					if (!isset($ket[$x])) {
						$ket[$x]=NULL;
					}
					$x++;
					$ko_a=array_values(array_unique($aspekx));
					$sebagai='';
					$sebagai.='<b>Anda Sebagai</b>';
					if ($sbg == "ATS") {
						$ss='<label class="label label-info pull-right"><i class="fa fa-star text-yellow"></i> Atasan</label>';
					}elseif ($sbg == "BWH") {
						$ss='<label class="label label-danger pull-right">Bawahan</label>';
					}elseif ($id == $this->admin) {
						$ss='<label class="label label-success pull-right"><i class="fa fa-user"></i> Diri Sendiri</label>';
					}else{
						$ss='<label class="label label-warning pull-right">Rekan Kerja</label>';
					}
					$sebagai.=$ss;
					$das='';
					if (count($ko_a) != 0) {
						$das.='<p class="text-muted text-center"><b>Daftar Aspek Sikap</b></p>
						<ul class="list-group list-group-unbordered">';
						foreach ($ko_a as $ko) {
							$aspk=$this->model_master->getAspekKode($ko);
							$kode_en=$this->codegenerator->encryptChar($kode);
							$ko_en=$this->codegenerator->encryptChar($ko);
							$das.='<a href="'.base_url('kpages/input_attitude_value/'.$kode_en.'/'.$sbg.':'.$id.'/'.$ko_en).'">
							<li class="list-group-item">';
							if ($kode_aspek == $ko) {
								$das.='<b class="text-red" style="font-size:14pt;"><i class="fa  fa-arrow-circle-right"></i> ';
							}else{
								$das.='<b>';
							}
							$das.=$aspk['nama'].'</b> 
							</li></a>';
							$koas[]=$ko;
						}
					}
					$ko_asp='';
					if (isset($koas)) {
						$ko_asp.=implode(';', $koas);
					}
				}
				$datax=[
					'sebagai'=>$sebagai,
					'das'=>$das,
					'kode'=>$kode,
					'penilai'=>$sbg.':'.$this->admin,
					'id'=>$id,
					'sbg'=>$sbg,
					'tabel'=>$tabel,
					'aspek'=>$kode_aspek,
					'koas'=>$ko_asp,
				];
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$sa=explode(':', $idx);
				$id=$sa[1];
				$sb=$sa[0].':'.$this->admin;
				$datax['data']=[];
				$data=$this->model_agenda->getTabelSikapId($tabel,$id,$kode_aspek);
				$datas=$this->model_agenda->openTableAgendaIdEmployee($tabel,$id);
				$n1=1;
				foreach ($datas as $d) {
					$aspekx[$n1]=$d->kode_aspek;
					$part=explode(';', $d->partisipan);
					if (in_array($sb, $part)) {
						$idkk[]=$d->id_karyawan;
					}
					$n1++;
				}
				$kar=$this->model_karyawan->getEmployeeId($id);
				$ky=$this->model_karyawan->getEmployeeId($this->admin);
				$pp1=array_values(array_unique($idkk));
				$sbg=$sa[0];
				$x=1;
				foreach ($data as $d1) {
					$kuisioner[$x]=$d1->kuisioner;
					$kode_kuisioner[$x]=$d1->kode_kuisioner;
					$ats[$x]=$d1->atas;
					$bwh[$x]=$d1->bawah;
					$kode_k[$x]=$d1->kode_kuisioner;
					if ($sbg == "DRI") {
						if ($d1->nilai_dri != NULL) {
							$nilai[$x]=$d1->nilai_diri;
							$ket[$x]=$d1->keterangan_diri;
							$avl[$x]=$d1->nilai_diri;
						}else{
							$nilai[$x]=NULL;
							$ket[$x]=NULL;
						}
					}elseif ($sbg == "ATS") {
						if ($d1->nilai_atas != NULL) {
							$o=explode(';', $d1->nilai_atas);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_atas);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}elseif ($sbg == "BWH") {
						if ($d1->nilai_bawah != NULL) {
							$o=explode(';', $d1->nilai_bawah);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_bawah);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}elseif ($sbg == "RKN") {
						if ($d1->nilai_rekan != NULL) {
							$o=explode(';', $d1->nilai_rekan);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_rekan);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}
					if (!isset($nilai[$x])) {
						$nilai[$x]=NULL;
					}
					if (!isset($ket[$x])) {
						$ket[$x]=NULL;
					}
					$x++;
					$ko_a=array_values(array_unique($aspekx));
					$n=1;
					foreach ($kuisioner as $num=> $k) {

						$kuisi='';
						$no='';
						$nill='';
						$kett='';
						$no.=$n;
						$asps=$this->model_master->getAspekKode($kode_aspek);
						$kusr=$this->model_master->getKuisionerKode($kode_kuisioner[$num]);
						$kuisi.=$k;
						$kuisi.=($d1->definisi)?'<br><br><b><u>Definisi:</u></b><br>'.$d1->definisi:null;
						if ($nilai[$n] == 0) {
							$kuisi.='<br><b data-toggle="tooltip" title="Belum Dinilai"><i class="fa fa-times-circle text-red"></i> ';
						}else{
							$kuisi.='<br><b data-toggle="tooltip" title="Sudah Dinilai"><i class="fa fa-check-circle text-green"></i> ';
						}
						$kuisi.=$asps['nama'].'</b>';
						$poinOption = '<option></option>';
						for ($i_p=1; $i_p <=5 ; $i_p++) { 
							$point='poin_'.$i_p;
							$satuan='satuan_'.$i_p;
							$select=($nilai[$n] == "" || $nilai[$n] == NULL) ? null : (($nilai[$n] == $d1->$point) ? ' selected="selected"':null);
							if($d->$point !='0' && $d1->$satuan!='0'){
								$poinOption .= '<option value="'.$d1->$point.'"'.$select.'>'.$d1->$satuan.'</option>';
							}
						}
						$nill.='<select class="form-control select2" name="poin['.$kode_kuisioner[$n].']" id="up'.$n.'" onchange="isi('.$n.')"  data-bawah="'.$bwh[$n].'" data-atas="'.$ats[$n].'" style="width:100%;" required>
						'.$poinOption.'
						</select>';
						$kett.='<textarea class="form-control" style="min-width:200px;" minlength="10" onblur="isi('.$n.')" name="keterangan['.$kode_k[$n].']" placeholder="Keterangan" id="kt'.$n.'"';
						if ($nilai[$n] > $bwh[$n] && $nilai[$n] < $ats[$n]) {
							$kett.=' disabled="disabled"';
						}
						$kett.='>';
						if (isset($ket[$n])) {
							$kett.=$ket[$n];
						}
						$kett.='</textarea>';
						$ket_content[$n]='';
						if (($nilai[$n] == "" || $nilai[$n] == NULL)) {
							if ($ats[$n] < $nilai[$n] || $bwh > $nilai[$n]) {
								$ket_content[$n]='Anda Harus Mengisi Keterangan';
							}
						}
						$kett.='<p class="text-red text-center" id="ps'.$n.'"></p>';
						$n++;
					}
					$datax['data'][]=[
						$no,
						$kuisi,
						$nill,
						$kett,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
//EDIT/ADD
	function input_sikap_value(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->input->post('kode');
		$sbg=$this->input->post('sbg');
		$cek=$this->model_agenda->getAgendaSikapKode($kode);
		$id=$this->input->post('id');
		if ($kode == "" || $id == "") {
			$datax = $this->messages->notValidParam();  
		}else{
			$nilai=$this->input->post('poin');
			$ket=$this->input->post('keterangan');
			$prt=$this->input->post('penilai');
			$tb=$this->input->post('tabel');
			$aspek=$this->input->post('aspek');
			$koas=$this->input->post('koas');
			if (isset($ket)) {
				foreach ($ket as $k1 => $kn) {
					$kn=ucwords($kn);
					if ($kn != "") {
						$dt=$this->model_agenda->openTableAgendaIdCode($tb,$id,$k1,'kode_kuisioner');
						$kp=explode(':', $prt);
						if ($kp[0] == "ATS") {
							$ket_a=$this->exam->addEditValueWithIdDb($kp[1],$kn,$dt['keterangan_atas']);
							$kdata=['keterangan_atas'=>$ket_a];
						}elseif ($kp[0] == "BWH") {
							$ket_b=$this->exam->addEditValueWithIdDb($kp[1],$kn,$dt['keterangan_bawah']);
							$kdata=['keterangan_bawah'=>$ket_b];
						}elseif ($kp[0] == "RKN") {
							$ket_r=$this->exam->addEditValueWithIdDb($kp[1],$kn,$dt['keterangan_rekan']);
							$kdata=['keterangan_rekan'=>$ket_r];
						}else {
							$ket_d=$this->exam->addEditValueWithIdDb($kp[1],$kn,$dt['keterangan_diri']);
							$kdata['keterangan_diri']=$ket_d;
						}
						$this->model_global->updateQueryNoMsg($kdata,$tb,['id_karyawan'=>$id,'kode_kuisioner'=>$k1]);
					}
				}
			}
			foreach ($nilai as $k => $n) {
				if ($n != "") {
					$dt=$this->model_agenda->openTableAgendaIdCode($tb,$id,$k,'kode_kuisioner');
					$p=explode(':', $prt);
					if ($p[0] == "ATS") {
						$n_a=$this->exam->addEditValueWithIdDb($p[1],$n,$dt['nilai_atas']);
						$r_a=$this->exam->getNilaiAverage($n_a);
						if(!empty($dt['sub_bobot_ats'])){
							$sb_atas=$this->exam->getPartisipantId($dt['sub_bobot_ats']);
							$n_k=[];
							$l_nilai=$this->otherfunctions->getParseVar($n_a);
							foreach ($l_nilai as $k_l => $v_l) {
								$n_k[$k_l]=$v_l*((isset($sb_atas[$k_l]))?$this->exam->hitungBobot($sb_atas[$k_l]):0);
							}
							$r_a= array_sum($n_k);
						}

						
						$data=[
							'nilai_atas'=>$n_a,
							'rata_atas'=>$r_a,
							'na_atas'=>$r_a*($this->exam->hitungBobot($dt['bobot_ats'])),
						];
						if (!isset($ket[$k])){
							echo $this->exam->deleteValueWithIdDb($p[1],$dt['keterangan_atas']);
							$data['keterangan_atas']=$this->exam->deleteValueWithIdDb($p[1],$dt['keterangan_atas']);
						}
					}elseif ($p[0] == "BWH") {
						$n_b=$this->exam->addEditValueWithIdDb($p[1],$n,$dt['nilai_bawah']);
						$r_b=$this->exam->getNilaiAverage($n_b);
						$data=[
							'nilai_bawah'=>$n_b,
							'rata_bawah'=>$r_b,
							'na_bawah'=>$r_b*($this->exam->hitungBobot($dt['bobot_bwh'])),
						];
						if (!isset($ket[$k])){
							$data['keterangan_bawah']=$this->exam->deleteValueWithIdDb($p[1],$dt['keterangan_bawah']);
						}
					}elseif ($p[0] == "RKN") {
						$n_r=$this->exam->addEditValueWithIdDb($p[1],$n,$dt['nilai_rekan']);
						$r_r=$this->exam->getNilaiAverage($n_r);
						$data=[
							'nilai_rekan'=>$n_r,
							'rata_rekan'=>$r_r,
							'na_rekan'=>$r_r*($this->exam->hitungBobot($dt['bobot_rkn'])),
						];
						if (!isset($ket[$k])){
							$data['keterangan_rekan']=$this->exam->deleteValueWithIdDb($p[1],$dt['keterangan_rekan']);
						}
					}else {
						$data['nilai_diri']=$n;
					}
					$this->model_global->updateQuery($data,$tb,['id_karyawan'=>$id,'kode_kuisioner'=>$k]);
					$dt1=$this->model_agenda->openTableAgendaIdCode($tb,$id,$k,'kode_kuisioner');
					$this->model_global->updateQuery(['na'=>($dt1['na_atas']+$dt1['na_bawah']+$dt1['na_rekan'])],$tb,['id_karyawan'=>$id,'kode_kuisioner'=>$k]);	
				}
			}
		}
		$koas1=explode(';', $koas);
		$kkkk=array_values(array_unique($koas1));
		$jumk=count($kkkk);
		$sea=array_search($aspek, $koas1)+1;
		$kode_en=$this->codegenerator->encryptChar($kode);
		if ($sea == $jumk) {
			$red = base_url('kpages/input_attitude_tasks_value/'.$kode_en);
			$url['linkx']=$red;
			$datax=array_merge($url,$this->messages->customGood('Silahkan Menilai Karyawan Selanjutnya.'));
		}else{
			$koas2=$this->codegenerator->encryptChar($koas1[$sea]);
			$red = base_url('kpages/input_attitude_value/'.$kode_en.'/'.$sbg.':'.$id.'/'.$koas2);
			$url['linkx']=$red;
			$datax=array_merge($url,$this->messages->customGood('Silahkan Menilai Aspek Selanjutnya.'));
		}
		echo json_encode($datax);
	}
//HASIL SIKAP
	public function report_attd_value()
	{
		$kode=$this->codegenerator->decryptChar($this->input->post('kode_agenda'));
		$kode_aspek=$this->codegenerator->decryptChar($this->input->post('kode_aspek'));
		$tabel=$this->codegenerator->decryptChar($this->input->post('tabel'));
		$id_kar=$this->codegenerator->decryptChar($this->input->post('id'));
		$usage=$this->uri->segment(3);
		$idx=$this->uri->segment(4);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$sa=explode(':', $idx);
				$id=$sa[1];
				$sb=$sa[0].':'.$this->admin;
				$dt=$this->model_agenda->getTabelSikapAll($tabel,$id);
				$dtx=$this->model_agenda->openTableAgendaIdEmployee($tabel,$id);
				if (count($dt) == 0) {
					$this->messages->notValidParam(); 
					redirect('kpages/result_attd_tasks_value/'.$kode);
				}
				$n1=1;
				foreach ($dtx as $d) {
					$aspekx[$n1]=$d->kode_aspek;
					$part=explode(';', $d->partisipan);
					if (in_array($sb, $part)) {
						$idkk[]=$d->id_karyawan;
					}
					$n1++;
				}
				$kar=$this->model_karyawan->getEmployeeId($id);
				$ky=$this->model_karyawan->getEmployeeId($this->admin);
				if (!isset($idkk)) {
					$this->messages->notValidParam(); 
					redirect('kpages/result_attd_tasks_value/'.$kode);
				}
				$pp1=array_values(array_unique($idkk));
				$sbg=$sa[0];
				$x=1;
				foreach ($dt as $d1) {
					$kuisioner[$x]=$d1->kuisioner;
					$ats[$x]=$d1->atas;
					$bwh[$x]=$d1->bawah;
					$kode_k[$x]=$d1->kode_kuisioner;
					$kode_a[$x]=$d1->kode_aspek;
					if ($sbg == "DRI") {
						if ($d1->nilai_dri != NULL) {
							$nilai[$x]=$d1->nilai_diri;
							$ket[$x]=$d1->keterangan_diri;
							$avl[$x]=$d1->nilai_diri;
						}else{
							$nilai[$x]=NULL;
							$ket[$x]=NULL;
						}
					}elseif ($sbg == "ATS") {
						if ($d1->nilai_atas != NULL) {
							$o=explode(';', $d1->nilai_atas);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_atas);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}elseif ($sbg == "BWH") {
						if ($d1->nilai_bawah != NULL) {
							$o=explode(';', $d1->nilai_bawah);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_bawah);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}elseif ($sbg == "RKN") {
						if ($d1->nilai_rkn != NULL) {
							$o=explode(';', $d1->nilai_rekan);
							foreach ($o as $ox) {
								$o1=explode(':', $ox);
								if ($o1[0] == $this->admin) {
									$nilai[$x]=$o1[1];
									$avl[$x]=$o1[1];
								}
							}
							$ko=explode(';', $d1->keterangan_rekan);
							foreach ($ko as $kox) {
								$ko1=explode(':', $kox);
								if ($ko1[0] == $this->admin) {
									$ket[$x]=$ko1[1];
								}
							}
						}else{
							$nilai[$x]=NULL;
						}
					}

					if (!isset($nilai[$x])) {
						$nilai[$x]=NULL;
					}
					if (!isset($ket[$x])) {
						$ket[$x]=NULL;
					}
					$x++;
			// }
					$ko_a=array_values(array_unique($aspekx));
					$n=1; 
					$no='';
					foreach ($kuisioner as $k) {
						$kuiz='';
						$kasi='';
						$nill='';
						$kett='';
						$no.=$n;
						$kuiz.=$k;
						$asps=$this->model_master->getAspekKode($d1->kode_aspek);
						$kasi.='<b>'.$asps['nama'].'</b>';
						if ($nilai[$n] == "" || $nilai[$n] == NULL) {
							$nill.='<label class="label label-danger">Belum Dinilai</label>';
						}else{
							$nill.=$nilai[$n];
						}
						if (isset($ket[$n])) {
							$kett.=$ket[$n];
						}else{
							$kett.='<label class="label label-default">Tidak Ada Keterangan</label>';
						}
						$n++;
					}
					$datax['data'][]=[
						$no,
						$kuiz,
						$kasi,
						$nill,
						$kett,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	//kpi
	public function data_input_tasks()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$id_log = $this->admin;
				$data=$this->model_agenda->getAgendaActive('agenda_kpi');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$kary = $this->model_karyawan->getEmployeeId($id_log);
				$no=1;
				$tgl = $this->date;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_a_kpi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$getAgenda=$this->model_agenda->getAgendaKpiKode($d->kode_a_kpi);
					$cekna = $this->otherfunctions->getDatePeriode($getAgenda['start'],$getAgenda['end'],$getAgenda['tahun']);
					$count_p=number_format($this->exam->getValueProgressAgendaFo($d->nama_tabel,$cekna,$kary['jabatan'],$id_log),2);
					$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$count_p.' %" data-placement="right">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$count_p.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$count_p.'%">
					</div>
					</div>';
					$keterangan = '';
					if ($count_p == 0) {
						$keterangan .= '<label class="label label-danger">Belum Ada Data</label>';
					}elseif ($count_p > 0 && $count_p < 100) {
						$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
					}else{
						$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
					}
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_a_kpi,
						$d->nama,
						$progress,
						$tanggal,
						$keterangan,
						$this->codegenerator->encryptChar($d->kode_a_kpi),
						$d->tahun,
						((isset($this->model_master->getListPeriodePenilaianActive()[$d->periode])) ? $this->model_master->getListPeriodePenilaianActive()[$d->periode] : $this->otherfunctions->getMark()),
						$this->codegenerator->encryptChar($d->kode_c_kpi),
					];
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function input_employee_tasks()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4)); 
		$id_log = $this->admin;
		$getJabatan = $this->model_karyawan->getEmployeeId($id_log);
		$kode_jabatan = $getJabatan['jabatan']; 
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$getAgenda=$this->model_agenda->getAgendaKpiKode($kode); 
				$tabel=$getAgenda['nama_tabel'];
				$data=$this->model_agenda->getTabelKpi($tabel);
				$cekna = $this->otherfunctions->getDatePeriode($getAgenda['start'],$getAgenda['end'],$getAgenda['tahun']);
				$no=1;
				$datax['data']=[];
				foreach ($data as $u) {
					$cek_penilai = $this->exam->cekPenilai($u->id_karyawan,$tabel,$id_log);
					if($cek_penilai){
						$dat_isi = 0;
						$kary = $this->model_karyawan->getEmployeeId($u->id_karyawan);
						$cprog = $this->model_agenda->openTableAgendaIdEmployee($tabel,$u->id_karyawan);
						$dat_all = 0;
						foreach ($cprog as $ck) {
							for ($i=1;$i<=$this->max_month;$i++){
								$col_bln='pn'.$i;
								$dat_isi +=$this->exam->countSumloopEmp($ck->$col_bln,$id_log);
								if ($ck->kode_penilai == 'P1'){
									// if ($kary['id_atasan'] == $id_log){
										$dat_all+=1;
									// }
								}
								if($ck->kode_penilai == 'P3'){
									$cekId = $this->otherfunctions->getParseOneLevelVar($ck->penilai);
									if (is_array($cekId)){
										if(in_array($id_log,$cekId)){
											$dat_all+=1;
										}
									}
								}
								
							}							
						}
						if($dat_all==0 || empty($dat_all)){
							$progress = 0;
						}else{
							$progress = $this->rumus->rumus_prosentase($dat_isi,$dat_all);
						}
						$progressx = '<div class="progress active" title="'.$this->formatter->getNumberFloat($progress).'%" data-toggle="tooltip" data-placement="left" style="background:grey;">
						<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%;">
						
						</div>
						</div>';
						if($progress==0){
							$ket = '<label class="label label-danger">Belum Dinilai</label>';
							$status = '<i class="fa fa-times-circle err fa-fw" aria-hidden="true"></i>';
							$status_text = 'Belum Dinilai';
						}elseif($progress==100){
							$ket = '<label class="label label-success">Sudah Selesai</label>';
							$status = '<i class="fa fa-check-circle scc fa-fw" aria-hidden="true"></i>';
							$status_text = 'Sudah Dinilai';
						}else{
							$ket = '<label class="label label-warning">Belum Selesai</label>';
							$status = '<i class="fa fa-refresh fa-spin fa-fw text-yellow" aria-hidden="true"></i>';
							$status_text = 'Belum Selesai';
						}

						$datax['data'][]=[
							$u->id_task,
							$kary['nik'],
							$kary['nama'],
							$kary['nama_jabatan'],
							$kary['nama_loker'],
							$progressx,
							$status,
							$this->codegenerator->encryptChar($kode),
							$this->codegenerator->encryptChar($u->id_karyawan),
							$status_text,
							$ket,
							$this->codegenerator->encryptChar($tabel),
						];
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function export_input_kpi()
	{
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$id_kar=$this->uri->segment(4);
		$id_log=$this->admin;
		$user = $this->model_karyawan->getEmployeeId($id_log);
		if ($id_kar != 'all') {
			$id_kar=$this->codegenerator->decryptChar($id_kar);
			$emp = $this->model_karyawan->getEmployeeId($id_kar);
		}		
		$getAgenda=$this->model_agenda->getAgendaKpiKode($kode);
		$usage=$this->uri->segment(5);
		if($usage == 'template'){
			$data['properties']=[
				'title'=>"Template Input KPI".((isset($emp['nama']))?" - ".$emp['nama']:null),
				'subject'=>"Template Input KPI".((isset($emp['nama']))?" - ".$emp['nama']:null),
				'description'=>"Template untuk Input KPI".((isset($emp['nama']))?" - ".$emp['nama']:null),
				'keywords'=>"Template Input, Input KPI",
				'category'=>"Template",
			];
			$ket_n = '';
		}elseif($usage == 'rekap'){
			$data['properties']=[
				'title'=>"Data Hasil KPI".((isset($emp['nama']))?" - ".$emp['nama']:null),
				'subject'=>"Data Hasil KPI".((isset($emp['nama']))?" - ".$emp['nama']:null),
				'description'=>"Data untuk Hasil KPI".((isset($emp['nama']))?" - ".$emp['nama']:null),
				'keywords'=>"Data Hasil, Hasil KPI",
				'category'=>"Data",
			];
			$ket_n = 'Belum Dinilai';
		}
		$body=[];
		
		$row_body=2;
		$row=$row_body;
		if ($id_kar == 'all') {
			$tabel=$getAgenda['nama_tabel'];
			$dataz=$this->model_agenda->getTabelKpi($tabel);
			foreach ($dataz as $u) {
				$cek_penilai = $this->exam->cekPenilai($u->id_karyawan,$tabel,$this->admin);
				if($cek_penilai){
					$datax=$this->model_agenda->openTableAgendaIdEmployee($getAgenda['nama_tabel'],$u->id_karyawan);
					foreach ($datax as $d) {
						$avl=false;
						$parseId = $this->otherfunctions->getParseOneLevelVar($d->penilai);
						$atasan = $this->model_master->getAtasan($d->kode_jabatan);
						if($d->kode_penilai=='P1'){
							if($atasan==$user['jabatan'])
								$avl = true;
						}elseif($d->kode_penilai=='P3'){
							$parseId = $this->otherfunctions->getParseOneLevelVar($d->penilai);
							if (is_array($parseId)) {
								if (in_array($id_log,$parseId)) {
									$avl = true;
								}
							}
						}
						if ($avl){
							for ($i=1; $i <= $this->max_month; $i++) { 
								$pnx = 'pn'.$i;
								$val_e[$i] = $ket_n;
								$get_sel = $this->otherfunctions->getParseVar($d->$pnx);
								if(!empty($get_sel)){
									foreach ($get_sel as $gk => $gv) {
										if($gk==$this->admin){
											$val_e[$i] = $gv;
										}
									}
								}
							}
							$d_kpi=$this->model_master->getKpiKode($d->kode_kpi);
							$c_menghitung='Dijumlahkan';
							if(isset($d_kpi)){
								$c_menghitung=($d_kpi['cara_menghitung'] == 'AVG')?'Average (Rata - Rata)':'Dijumlahkan';
							}
							if($usage == 'template'){
								$arr_start=[($row-1),$d->nik,$d->nama,$d->nama_jabatan,$d->bagian,$d->nama_loker,$d->kode_kpi,$d->kpi,$c_menghitung,$this->otherfunctions->getJenisSatuan($d->jenis_satuan),$d->detail_rumus,$d->unit,$d->sumber_data,$d->target,$d->bobot.'%'];
								$arr_end=[];
								for ($i=1; $i <= $this->max_month ; $i++) { 
									$arr_end[$i]=(isset($val_e[$i]))?$val_e[$i]:$ket_n;
								}
								$body[$row]=array_merge($arr_start,$arr_end);
							}elseif($usage == 'rekap'){
								$arr_start=[($row-1),$d->nik,$d->nama,$d->nama_jabatan,$d->bagian,$d->nama_loker,$d->kode_kpi,$d->kpi,$c_menghitung,$this->otherfunctions->getJenisSatuan($d->jenis_satuan),$d->detail_rumus,$d->unit,$d->sumber_data,$d->target,$d->bobot.'%'];
								$arr_end=[];
								for ($i=1; $i <= $this->max_month ; $i++) { 
									$arr_end[$i]=(isset($val_e[$i]))?$val_e[$i]:$ket_n;
								}
								$body[$row]=array_merge($arr_start,$arr_end);
							}
							$row++;
						}
					}
				}
			}
		}else{
			$datax=$this->model_agenda->openTableAgendaIdEmployee($getAgenda['nama_tabel'],$id_kar);
			foreach ($datax as $d) {
				$avl=false;
				$parseId = $this->otherfunctions->getParseOneLevelVar($d->penilai);
				$atasan = $this->model_master->getAtasan($d->kode_jabatan);
				if($d->kode_penilai=='P1'){
					if($atasan==$user['jabatan'])
						$avl = true;
				}elseif($d->kode_penilai=='P3'){
					$parseId = $this->otherfunctions->getParseOneLevelVar($d->penilai);
					if (is_array($parseId)) {
						if (in_array($id_log,$parseId)) {
							$avl = true;
						}
					}
				}
				if ($avl){
					for ($i=1; $i <= $this->max_month; $i++) { 
						$pnx = 'pn'.$i;
						$val_e[$i] = $ket_n;
						$get_sel = $this->otherfunctions->getParseVar($d->$pnx);
						if(!empty($get_sel)){
							foreach ($get_sel as $gk => $gv) {
								if($gk==$this->admin){
									$val_e[$i] = $gv;
								}
							}
						}
					}
					$d_kpi=$this->model_master->getKpiKode($d->kode_kpi);
					$c_menghitung='Dijumlahkan';
					if(isset($d_kpi)){
						$c_menghitung=($d_kpi['cara_menghitung'] == 'AVG')?'Average (Rata - Rata)':'Dijumlahkan';
					}
					if($usage == 'template'){
						$arr_start=[($row-1).'.',$d->nik,$d->nama,$d->nama_jabatan,$d->bagian,$d->nama_loker,$d->kode_kpi,$d->kpi,$c_menghitung,$this->otherfunctions->getJenisSatuan($d->jenis_satuan),$d->detail_rumus,$d->unit,$d->sumber_data,$d->target,$d->bobot.'%'];
						$arr_end=[];
						for ($i=1; $i <= $this->max_month ; $i++) { 
							$arr_end[$i]=(isset($val_e[$i]))?$val_e[$i]:$ket_n;
						}
						$body[$row]=array_merge($arr_start,$arr_end);
					}elseif($usage == 'rekap'){
						$arr_start=[($row-1).'.',$d->nik,$d->nama,$d->nama_jabatan,$d->bagian,$d->nama_loker,$d->kode_kpi,$d->kpi,$c_menghitung,$this->otherfunctions->getJenisSatuan($d->jenis_satuan),$d->detail_rumus,$d->unit,$d->sumber_data,$d->target,$d->bobot.'%'];
						$arr_end=[];
						for ($i=1; $i <= $this->max_month ; $i++) { 
							$arr_end[$i]=(isset($val_e[$i]))?$val_e[$i]:$ket_n;
						}
						$body[$row]=array_merge($arr_start,$arr_end);
					}
					$row++;
				}
			}
		}
		$peri = $this->formatter->getDateYearPeriode($getAgenda['start'],$getAgenda['end'],$getAgenda['tahun'],$getAgenda['batas']);
		$periodex=[];
		for ($x=0; $x < $this->max_month; $x++) { 
			$month_down = date('Y-m-d',strtotime($peri[$x]));
			$periode = explode('-', $month_down);
			$month = $this->formatter->getMonth()[$periode[1]];
			$periodex[] = strtoupper($month.' - '.$periode[0]);
		}

		if($usage == 'template'){
			$sheet[0]=[
				'range_huruf'=>3,
				'sheet_title'=>'Template Input KPI',
				'head'=>[
					'row_head'=>1,
					'data_head'=>[
						'No.','NIK','NAMA','JABATAN','BAGIAN','LOKASI KERJA','KODE KPI','KPI','CARA MENGHITUNG','JENIS SATUAN','JENIS','UNIT','SUMBER DATA','TARGET','BOBOT (%)'],
				],
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
			];
		}elseif($usage == 'rekap'){
			$sheet[0]=[
				'range_huruf'=>3,
				'sheet_title'=>'Data Input KPI',
				'head'=>[
					'row_head'=>1,
					'data_head'=>[
						'No.','NIK','NAMA','JABATAN','BAGIAN','LOKASI KERJA','KODE KPI','KPI','CARA MENGHITUNG','JENIS SATUAN','JENIS','UNIT','SUMBER DATA','TARGET','BOBOT'],
					'row_body'=>$row_body,
				],
			];
		}
		$head_merge = array_merge($sheet[0]['head']['data_head'],$periodex);
		$sheet[0]['head']['data_head'] = $head_merge;
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function import_input_kpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data['properties']=[
			'post'=>'file',
			'data_post'=>$this->input->post('file', TRUE),
		];
		$col=[1=>'nik',6=>'kode_kpi'];
		$cn=1;
		for ($i=15; $i < (15+$this->max_month) ; $i++) { 
			$cols='pn'.$cn;
			$col[$i]=$cols;
			$cn++;
		}		
		$sheet[0]=[
			'range_huruf'=>3,
			'row'=>2,
			'table'=>$this->input->post('tabel'),
			'column_code'=>'kode_kpi',
			'usage'=>'import_task',
			'other'=>['id_admin'=>$this->admin],
			'column'=>$col,
		];
		$data['data']=$sheet;
		$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	//function original
	// public function input_tasks_value()
	// {
	// 	if (!$this->input->is_ajax_request()) 
	// 		redirect('not_found');
	// 	$usage=$this->uri->segment(3);
	// 	$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
	// 	$id_kar=$this->codegenerator->decryptChar($this->uri->segment(5));
	// 	$getAgenda=$this->model_agenda->getAgendaKpiKode($kode);
	// 	$id_log = $this->admin;
	// 	if ($usage == null) {
	// 		echo json_encode($this->messages->notValidParam());
	// 	}else{
	// 		if ($usage == 'view_all') {
	// 			$jenis = strtoupper($this->input->post('jenis'));
	// 			$data=$this->model_agenda->openTableAgendaIdEmployee($getAgenda['nama_tabel'],$id_kar);
	// 			$no=0;
	// 			$datax['data']=[];
	// 			foreach ($data as $d) {
	// 				$exe = $this->exam->cekPenilai($d->id_task,$getAgenda['nama_tabel'],$id_log);
	// 				if($exe){
	// 					if($d->jenis==$jenis){
	// 						$pn = array($d->pn1,$d->pn2,$d->pn3,$d->pn4);
	// 						$val1 = [];$val2 = [];$val3 = [];$val4 = [];$val5 = [];
	// 						$key1 = [];$key2 = [];$key3 = [];$key4 = [];$key5 = [];
	// 						$cek_val = 0;
	// 						for ($i=0; $i < 4; $i++) { 
	// 							if(empty($d->poin_1) || $d->poin_1==0){
	// 								if(!empty($d->satuan_1) || $d->satuan_1!=0){
	// 									$key1 = array($d->poin_1);
	// 									$val1 = array($d->satuan_1);
	// 									$cek_val=$cek_val+1;
	// 								}
	// 							}else{
	// 								if(empty($d->satuan_1)){
	// 									$key1 = array($d->poin_1);
	// 									$val1 = array('data_tidak_ada');
	// 									$cek_val=$cek_val+1;
	// 								}else{
	// 									$key1 = array($d->poin_1);
	// 									$val1 = array($d->satuan_1);
	// 									$cek_val=$cek_val+1; 
	// 								}
	// 							}
	// 							if(empty($d->poin_2) || $d->poin_2==0){
	// 								if(!empty($d->satuan_2) || $d->satuan_2!=0){
	// 									$key2 = array($d->poin_2);
	// 									$val2 = array($d->satuan_2);
	// 									$cek_val=$cek_val+1;
	// 								}
	// 							}else{
	// 								if(empty($d->satuan_2)){
	// 									$key2 = array($d->poin_2);
	// 									$val2 = array('data_tidak_ada');
	// 									$cek_val=$cek_val+1;
	// 								}else{
	// 									$key2 = array($d->poin_2);
	// 									$val2 = array($d->satuan_2);
	// 									$cek_val=$cek_val+1;
	// 								}
	// 							}
	// 							if(empty($d->poin_3) || $d->poin_3==0){
	// 								if(!empty($d->satuan_3) || $d->satuan_3!=0){
	// 									$key3 = array($d->poin_3);
	// 									$val3 = array($d->satuan_3);
	// 									$cek_val=$cek_val+1;
	// 								}
	// 							}else{
	// 								if(empty($d->satuan_3)){
	// 									$key3 = array($d->poin_3);
	// 									$val3 = array('data_tidak_ada');
	// 									$cek_val=$cek_val+1;
	// 								}else{
	// 									$key3 = array($d->poin_3);
	// 									$val3 = array($d->satuan_3);
	// 									$cek_val=$cek_val+1;
	// 								}
									
	// 							}
	// 							if(empty($d->poin_4) || $d->poin_4==0){
	// 								if(!empty($d->satuan_4) || $d->satuan_4!=0){
	// 									$key4 = array($d->poin_4);
	// 									$val4 = array($d->satuan_4);
	// 									$cek_val=$cek_val+1;
	// 								}
	// 							}else{
	// 								if(empty($d->satuan_4)){
	// 									$key4 = array($d->poin_4);
	// 									$val4 = array('data_tidak_ada');
	// 									$cek_val=$cek_val+1;
	// 								}else{
	// 									$key4 = array($d->poin_4);
	// 									$val4 = array($d->satuan_4);
	// 									$cek_val=$cek_val+1;
	// 								}
									
	// 							}
	// 							if(empty($d->poin_5) || $d->poin_5==0){
	// 								if(!empty($d->satuan_4) || $d->satuan_5!=0){
	// 									$key5 = array($d->poin_5);
	// 									$val5 = array($d->satuan_5);
	// 									$cek_val=$cek_val+1;
	// 								}
	// 							}else{
	// 								if(empty($d->satuan_5)){
	// 									$key5 = array($d->poin_5);
	// 									$val5 = array('data_tidak_ada');
	// 									$cek_val=$cek_val+1;
	// 								}else{
	// 									$key5 = array($d->poin_5);
	// 									$val5 = array($d->satuan_5);
	// 									$cek_val=$cek_val+1;
	// 								}
	// 							}

	// 							$dat = date('m');
	// 							$peri = $this->formatter->getNameOfMonthByPeriodeNum($getAgenda['start'],$getAgenda['end'],$getAgenda['tahun']);
	// 							for ($x=0; $x < 4; $x++) { 
	// 								// if($peri[$x]==$dat){
	// 									$in[$x] = '';
	// 									$name[$x] = 'poin_'.strtolower($jenis).'[]';
	// 									$class[$x] = 'poin_'.strtolower($jenis);
	// 								// }else{
	// 								// 	$in[$x] = 'disabled="readonly"';
	// 								// 	$name[$x] = 'poin_'.$x.'[]';
	// 								// 	$class[$x] = '';
	// 								// }
	// 							}

	// 							$valx = array_merge($val1,$val2,$val3,$val4,$val5);
	// 							$valx = array_filter($valx, 'strlen');
	// 							$keyx = array_merge($key1,$key2,$key3,$key4,$key5);
	// 							$keyx = array_filter($keyx, 'strlen');
	// 							$options[$i] = array_combine($keyx,$valx);
	// 							if($cek_val==0){
	// 								$options[$i] = array('0' => 'TIdak Ada Data');
	// 							}

	// 							if($pn[$i]!=''){
	// 								$val_e = null;
	// 								$get_sel = $this->otherfunctions->getParseVar($pn[$i]);
	// 								foreach ($get_sel as $gk => $gv) {
	// 									if($gk==$id_log)
	// 										$val_e = $gv;
	// 								}
	// 								$selected[$i] = array($val_e);
	// 								$setvalue[$i] = $val_e;
	// 							}else{
	// 								$selected[$i] = NULL;
	// 								$setvalue[$i] = NULL;
	// 							}
	// 						}
	// 						if($d->jenis_satuan==0){
	// 							$per1 = form_dropdown($name[0], $options[0], $selected[0], 'class="form-control select2 '.$class[0].'" style="width:100%;" '.$in[0]);
	// 							$per2 = form_dropdown($name[1], $options[1], $selected[1], 'class="form-control select2 '.$class[1].'" style="width:100%;" '.$in[1]);
	// 							$per3 = form_dropdown($name[2], $options[2], $selected[2], 'class="form-control select2 '.$class[2].'" style="width:100%;" '.$in[2]);
	// 							$per4 = form_dropdown($name[3], $options[3], $selected[3], 'class="form-control select2 '.$class[3].'" style="width:100%;" '.$in[3]);
	// 						}else{
	// 							$per1 = '<input type="number" step="0.01" name="'.$name[0].'" class="form-control '.$class[0].'" placeholder="Input Data" min="0" max="'.$d->target.'" value="'.$setvalue[0].'" '.$in[0].'>';
	// 							$per2 = '<input type="number" step="0.01" name="'.$name[1].'" class="form-control '.$class[1].'" placeholder="Input Data" min="0" max="'.$d->target.'" value="'.$setvalue[1].'" '.$in[1].'>';
	// 							$per3 = '<input type="number" step="0.01" name="'.$name[2].'" class="form-control '.$class[2].'" placeholder="Input Data" min="0" max="'.$d->target.'" value="'.$setvalue[2].'" '.$in[2].'>';
	// 							$per4 = '<input type="number" step="0.01" name="'.$name[3].'" class="form-control '.$class[3].'" placeholder="Input Data" min="0" max="'.$d->target.'" value="'.$setvalue[3].'" '.$in[3].'>';
	// 						}
	// 						if($d->na==null || $d->na==0){
	// 							$status = '<i class="fa fa-times-circle err" aria-hidden="true"></i>';
	// 							$status_text = 'Belum Dinilai';
	// 						}else{
	// 							$status = '<i class="fa fa-check-circle scc" aria-hidden="true"></i>';
	// 							$status_text = 'Sudah Dinilai';
	// 						}
	// 						if($no!=0){
	// 							$display='block';
	// 						}else{
	// 							$display='';
	// 						}
	// 						$datax['data'][]=[
	// 							$d->id_task,
	// 							$d->kpi,
	// 							$d->jenis,
	// 							$d->unit,
	// 							$this->formatter->limit_words($d->definisi),
	// 							$d->target,
	// 							$d->bobot,
	// 							$per1,
	// 							$per2,
	// 							$per3,
	// 							$per4,
	// 							strtolower($jenis),
	// 							$display,
	// 							$d->definisi,
	// 						];
	// 						$no++;
	// 					}
	// 				}
	// 			}
	// 			echo json_encode($datax);
	// 		}else{
	// 			echo json_encode($this->messages->notValidParam());
	// 		}
	// 	}
	// }
	// public function add_input_task()
	// {
	// 	if (!$this->input->is_ajax_request()) 
	// 		redirect('not_found');
	// 	$id_part = $this->admin;
	// 	$id_karyawan = $this->input->post('id_karyawan');
	// 	$tabel = $this->input->post('tabel');
	// 	$start = $this->input->post('start');
	// 	$end = $this->input->post('end');
	// 	$tahun = $this->input->post('tahun');
	// 	$mouth = date('m');
	// 	$max_for = 4;
	// 	$periode = $this->formatter->getNameOfMonthByPeriodeNum($start,$end,$tahun);
	// 	foreach ($periode as $pkey => $pval) {
	// 		// if($pval==$mouth){
	// 			$mo = $pkey;
	// 		// }
	// 	}
	// 	$jenis = ['wajib','rutin','tambahan'];
	// 	for ($i=0; $i < count($jenis); $i++) { 
	// 		$id_tasks[$jenis[$i]] = $this->input->post('id_tasks_hidden_'.$jenis[$i]);
	// 		$poin_hidden[$jenis[$i]] = $this->input->post('poin_hidden_'.$jenis[$i]);
	// 	}

	// 	$data_id_task=[$id_tasks['wajib'],$id_tasks['rutin'],$id_tasks['tambahan']];
	// 	$svidtask = $this->exam->mergeArrayNull($data_id_task);
	// 	$jum_id = count($svidtask);

	// 	$data_poin=[$poin_hidden['wajib'],$poin_hidden['rutin'],$poin_hidden['tambahan']];
	// 	$svpoin = $this->exam->mergeArrayNull($data_poin);
	// 	$no=0;
	// 	foreach ($svidtask as $val) {
	// 		$new_get = $this->otherfunctions->convertResultToRowArray($this->model_agenda->openTableAgendaId($tabel,$val));
	// 		$bobot = $new_get['bobot'];
	// 		for ($i1=0; $i1 < $max_for; $i1++) { 
	// 			if($mo==$i1){ 
	// 				$i1_num = $i1+1;
	// 				$pn_num = 'pn'.$i1_num;
	// 				$pn = $this->exam->getNilaiPackRemove($svpoin[$no],$id_part,$new_get[$pn_num]);
	// 				$data[$pn_num]=$pn;
	// 			}
	// 		}
	// 		if($new_get['jenis_satuan']==1){
	// 			for ($i2=0; $i2 < $max_for; $i2++) { 
	// 				$i2_num = $i2+1;
	// 				$pnx = 'pn'.$i2_num;
	// 				if($mo==$i2){
	// 					$changepn[$i2_num] = $this->exam->changeJenisSatuanAllBo($val,$data[$pnx],$tabel);
	// 				}else{
	// 					$changepn[$i2_num] = $this->exam->changeJenisSatuanAllBo($val,$new_get[$pnx],$tabel);
	// 				}
	// 				$nr[$i2_num] = $this->exam->getNilaiAverage($changepn[$i2_num]);
	// 				$na[$i2_num] = $this->exam->getNilaiAverageDone($nr[$i2_num],$bobot);
	// 			}
	// 		}elseif($new_get['jenis_satuan']==0){
	// 			for ($i2=0; $i2 < $max_for; $i2++) { 
	// 				$i2_num = $i2+1;
	// 				$pnx = 'pn'.$i2_num;
	// 				if($mo==$i2){
	// 					$nr[$i2_num] = $this->exam->getNilaiAverage($data[$pnx]);
	// 				}else{
	// 					$nr[$i2_num] = $this->exam->getNilaiAverage($new_get[$pnx]);
	// 				}
	// 				$na[$i2_num] = $this->exam->getNilaiAverageDone($nr[$i2_num],$bobot);
	// 			}
	// 		}

	// 		$na_sum = (array_sum($nr))/count($nr);
	// 		for ($i3=0; $i3 < $max_for; $i3++) { 
	// 			$i3_num = $i3+1;
	// 			$data['nr'.$i3_num]=$nr[$i3_num];
	// 			$data['na'.$i3_num]=$this->otherfunctions->changeNullTo($na[$i3_num],0);
	// 		}
	// 		$data['na'] =$na_sum;
	// 		$where = [
	// 			'id_task'=>$val
	// 		];
	// 		$this->model_global->updateQueryNoMsg($data,$tabel,$where);
	// 		$no++;
	// 		$datax = $this->messages->allGood();
	// 	}
	// 	echo json_encode($datax);
	// }
	//==================================================== bypass all month ==================================================//
	public function input_tasks_value()
	{ 
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->uri->segment(4));
		$id_kar=$this->codegenerator->decryptChar($this->uri->segment(5));
		$getAgenda=$this->model_agenda->getAgendaKpiKode($kode);
		$id_log = $this->admin;
		$user = $this->model_karyawan->getEmployeeId($id_log);
		$correction=($this->input->post('correction'))?$this->input->post('correction'):false;
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->openTableAgendaIdEmployee($getAgenda['nama_tabel'],$id_kar);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=0;
				$max_for=$this->max_month;
				$datax['data']=[];
				foreach ($data as $d) {
					$avl=false;
					$parseId = $this->otherfunctions->getParseOneLevelVar($d->penilai);
					$atasan = $this->model_master->getAtasan($d->kode_jabatan);
					if($d->kode_penilai=='P1'){
						if($atasan==$user['jabatan'])
							$avl = true;
					}elseif($d->kode_penilai=='P3'){
						$parseId = $this->otherfunctions->getParseOneLevelVar($d->penilai);
						if (is_array($parseId)) {
							if (in_array($id_log,$parseId)) {
								$avl = true;
							}
						}
					}
					if ($correction) {
						$avl = true;
					}
					if ($avl){
						for ($i_pn=1; $i_pn <= $this->max_month ; $i_pn++) { 
							$col_pn='pn'.$i_pn;
							$pn[$i_pn] = $d->$col_pn;
						}
						$cek_val = 0;
						for ($i=1; $i <= $max_for; $i++) { 
							$range_poin[$i]=[];
							for ($i_v=1; $i_v <= $this->max_range; $i_v++) { 
								$poin = 'poin_'.$i_v;
								$satuan = 'satuan_'.$i_v;
								if(!empty($d->$satuan)){
									$range_poin[$i][$d->$poin] = $d->$satuan;
								}else{
									$range_poin[$i][$d->$poin] = $d->$poin;
								}
							}
							for ($i_m=1; $i_m <= $max_for; $i_m++) { 
								if($pn[$i_m]!=''){
									$get_sel = $this->otherfunctions->getParseVar($pn[$i_m]);
									//bypass
									if (isset($get_sel)) {
										if (isset($get_sel[$id_log])) {
											$selected[$i_m] = $get_sel[$id_log];
											if (isset($get_sel['COR'])) {
												$correct_val[$i_m] = true;
												$selected[$i_m] = $get_sel['COR'];
											}
										}
										if ($correction) {
											if (isset($get_sel['COR'])){
												$selected[$i_m] = $get_sel['COR'];
											}
										}
										// if (isset($get_sel[$id_log])) {
										// 	$selected[$i_m] = $get_sel[$id_log];
										// }else{
										// 	$selected[$i_m] = NULL;
										// }
									}
									// $val_e=(isset($get_sel))?end($get_sel):0;
									// $selected[$i_m] = $val_e;
								}else{
									$selected[$i_m] = NULL;
								}
							}
						}
						if($d->jenis_satuan==0){
							//old coment
							// for ($i1=1; $i1 <= $max_for; $i1++) { 
							// 	$per[$i1] = form_dropdown('poin_'.$i1.'['.$d->id_task.']', $range_poin[$i1], $selected[$i1], 'class="form-control select" style="width:100%;" ');
							// }
							//new komen
							// for ($i1=1; $i1 <= $max_for; $i1++) { 
							// 	$per[$i1] = form_dropdown('poin_'.$i1.'['.$d->id_task.']', $range_poin[$i1], $selected[$i1], 'class="form-control select2" style="width:100%;" ');
							// }
							for ($i1=1; $i1 <= $max_for; $i1++) { 
								// $cek_val=$this->model_agenda->getValidasiKpi($id_kar,['kode_periode'=>$getAgenda['periode'],'tahun'=>$getAgenda['tahun']],$i1);
								$checked='';
								// if (isset($cek_val['validasi']) || $correct_val[$i1]){
								// 	if ($cek_val['validasi'] || $correct_val[$i1]){
								// 		$checked=' readonly="readonly"';
								// 	}
								// }
								$per[$i1] = form_dropdown('poin_'.$i1.'['.$d->id_task.']', $range_poin[$i1], $selected[$i1], 'class="form-control select2" style="width:100%;" '.$checked);
								if ($correction){
									$nr_col='nr'.$i1;
									if (is_numeric($d->$nr_col)) {
										$per[$i1] .='<p>Nilai Sebelumnya : <b class="text-blue">'.((isset($range_poin[$i1][$d->$nr_col]))?$range_poin[$i1][$d->$nr_col]:'Unknown').'</b></p>';
									}									
								}
							}
						}elseif($d->jenis_satuan==1) {
							//new komen
							// for ($i1=1; $i1 <= $max_for; $i1++) { 
							// 	$per[$i1] = '<div class="form-group"><input type="number" min="0" step="0.01" name="poin_'.$i1.'['.$d->id_task.']" class="form-control" placeholder="Input Data" id="inp_'.$d->id_task.'_'.$i1.'" min="0" value="'.(($selected[$i1] == '')?null:$selected[$i1]).'" onkeyup="max_target(this.value,\''.$d->target.'\',\'inp_'.$d->id_task.'_'.$i1.'\')"></div>';
							// }
							for ($i1=1; $i1 <= $max_for; $i1++) { 
								// $cek_val=$this->model_agenda->getValidasiKpi($id_kar,['kode_periode'=>$getAgenda['periode'],'tahun'=>$getAgenda['tahun']],$i1);
								$checked='';
								// if (isset($cek_val['validasi']) || $correct_val[$i1]){
								// 	if ($cek_val['validasi'] || $correct_val[$i1]){
								// 		$checked=' readonly="readonly"';
								// 	}
								// }
								$per[$i1] = '<div class="form-group"><input type="number" min="0" step="0.01" name="poin_'.$i1.'['.$d->id_task.']" class="form-control" placeholder="Input Data" id="inp_'.$d->id_task.'_'.$i1.'" min="0" value="'.(($selected[$i1] == '')?null:$selected[$i1]).'" '.$checked.' onkeyup="max_target(this.value,\''.$d->target.'\',\'inp_'.$d->id_task.'_'.$i1.'\')"></div>';
								if ($correction){
									$nr_col='nr'.$i1;
									if (is_numeric($d->$nr_col)) {
										$per[$i1] .='<p>Nilai Sebelumnya : <b class="text-blue">'.$d->$nr_col.'</b></p>';
									}									
								}
							}
						}

						if($no!=0){
							$display='block';
						}else{
							$display='';
						}
						$d_kpi=$this->model_master->getKpiKode($d->kode_kpi);
						$c_menghitung='Dijumlahkan';
						if(isset($d_kpi)){
							$c_menghitung=($d_kpi['cara_menghitung'] == 'AVG')?'Average (Rata - Rata)':'Dijumlahkan';
						}
						$arr_start=[
							$d->id_task,
							$d->kpi,
							$c_menghitung,
							$d->sumber_data,
							$d->detail_rumus,
							$d->target,
							$d->bobot,
						];
						for ($in=1;$in<=$max_for;$in++){
							$arr_middle[$in]=$per[$in];
						}
						$arr_end=[
							$d->id_karyawan,
							$display,
							$d->detail_rumus,
							$c_menghitung
						];
						$datax['data'][]=array_merge($arr_start,$arr_middle,$arr_end);
						$no++;			
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_input_task()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id_karyawan = $this->input->post('id_karyawan');
		$tabel = $this->input->post('tabel');
		// $id_part = $this->admin;
		$correction = $this->input->post('correction');
		$id_part = (($correction)?'COR':$this->admin);
		$max_for=$this->max_month;
		if($id_karyawan!='' || $tabel!='' || $id_part=''){
			for ($i1=1; $i1 <=$max_for; $i1++) { 
				$poin[$i1] = $this->input->post('poin_'.$i1);
			}
			if (isset($poin)) {
				foreach ($poin as $bulan => $nilai) {
					if (isset($nilai) && (count($nilai) > 0)) {
						foreach ($nilai as $id_task => $value) {
							// if ($value != '' && $value != null) {
								$get_data = $this->otherfunctions->convertResultToRowArray(($this->model_agenda->openTableAgendaId($tabel,$id_task)));
								$pn_key = 'pn'.$bulan;
								$pn[$id_task][$bulan] = $this->exam->getNilaiPackRemove($value,$id_part,$get_data[$pn_key]);
								$target[$id_task] = $get_data['target'];
								$poin_max[$id_task] = ((is_numeric($get_data['satuan_1']))?$get_data['satuan_1']:$get_data['poin_1']);
								$lebih_max[$id_task] = $get_data['lebih_max'];
								$sifat[$id_task] = $get_data['sifat'];
								$max_target[$id_task] = $get_data['max'];
								$cara_menghitung[$id_task] = $get_data['cara_menghitung'];
							// }							
						}
					}
				}
				if (isset($pn) && isset($target) && isset($poin_max) && isset($lebih_max)) {
					foreach ($pn as $id_task_in => $bulan_in) {
						$target_in=((isset($target[$id_task_in]))?$target[$id_task_in]:0);
						$poin_max_in=((isset($poin_max[$id_task_in]))?$poin_max[$id_task_in]:100);
						$lebih_max_in=((isset($lebih_max[$id_task_in]))?$lebih_max[$id_task_in]:false);
						$sifat_in=((isset($sifat[$id_task_in]))?$sifat[$id_task_in]:'MAX');
						$max_target_in=((isset($max_target[$id_task_in]))?$max_target[$id_task_in]:'');
						$cara_menghitung_in=((isset($cara_menghitung[$id_task_in]))?$cara_menghitung[$id_task_in]:'AVG');
						if (isset($bulan_in)) {
							foreach ($bulan_in as $bln => $val) {
								$data_in=[
									'pn'.$bln=>$val,
									'nr'.$bln=>$this->exam->getNilaiSum($val)
								];
								if ($cara_menghitung_in == 'SUM'){
									$nilai_count=$nilai_count+$data_in['nr'.$bln];
								}else{
									$nilai_count=$data_in['nr'.$bln];
								}
								// if ($sifat_in == 'MIN') {
								// 	$data_in['na'.$bln]=$this->rumus->rumus_prosentase_negatif($data_in['nr'.$bln],$target_in,$max_target_in);
								// }else{
								// 	$data_in['na'.$bln]=$this->rumus->rumus_prosentase($data_in['nr'.$bln],$target_in);
								// }	
								if ($sifat_in == 'MIN') {
									$data_in['na'.$bln]=$this->rumus->rumus_prosentase_negatif($nilai_count,$target_in,$max_target_in);
								}else{
									$data_in['na'.$bln]=$this->rumus->rumus_prosentase($nilai_count,$target_in);
								}									
								if (!$lebih_max_in) {
									if ($data_in['na'.$bln] > $poin_max_in) {
										$data_in['na'.$bln]=$poin_max_in;
									}
								}
								if ($val == '' || $val == null){
									$data_in['na'.$bln]=null;
								}
								$this->model_global->updateQueryNoMsg($data_in,$tabel,['id_task'=>$id_task_in]); 
							}
						}
					}
				}				
			}
			$datax = $this->messages->allGood();
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function getRaport()
	{
		$table = $this->input->post('table');
		// print_r($table);
		$id = $this->input->post('id');
		$agenda = unserialize(base64_decode($this->input->post('agenda')));
		$usage = $this->input->post('usage');
		$id_log = $this->input->post('id_log');
		$max_for = $this->max_month;
		if(empty($usage))
			$usage = 'raport';
		if(empty($id_log))
			$id_log = null;

		$penunjang = '';
		$no = 1;
		$new_val = '';
		$month_total=[];
		$month_total_real=[];
		for ($i_s=1; $i_s <= $max_for; $i_s++) { 
			$nilai[$i_s] = 0;
			$month_total[$i_s] = 0;			
			$month_total_real[$i_s] = 0;			
		}
		$sumavgmax = 0;
		$nilaixbobot = 0;
		$data = $this->model_agenda->openTableEmployeeKpi($table,$id);
		if(count($data)>0){
			$bobot_all=[];
			$bobot_tall=[];
			$bobot_tertimbang_stat_arr=[];
			$bobot_tertimbang_stat=0;
			foreach ($data as $da){
				for ($i_b=1;$i_b<= $max_for;$i_b++){
					$mnt='na'.$i_b;
					if (is_null($da->$mnt)){
						$bobot_tertimbang_stat_arr[$i_b]=1;
					}
				}
				if($da->not_available == '1'){
					$bobot_tertimbang_stat=1;
				}else{
					$bobot_all[$da->kode_kpi]=$da->bobot;
				}
				$bobot_tall[$da->kode_kpi]=$da->bobot;
			}
			if ($bobot_tertimbang_stat > 0) {
				foreach ($data as $h) {
					$bobot_bulan=[];
					for ($i_b=1;$i_b<= $max_for;$i_b++){
						$ba_bln=((isset($bobot_tertimbang_stat_arr[$i_b]))?$bobot_all:$bobot_tall);
						if (isset($bobot_tertimbang_stat_arr[$i_b])){
							$bobot_bulan[$i_b]=$this->exam->convertComma($this->exam->bobot_tertimbang($ba_bln,$h->bobot,$h->not_available));
						}else{
							$bobot_bulan[$i_b]=$this->exam->convertComma($this->exam->bobot_tertimbang($ba_bln,$h->bobot,0));
						}
					}
					$new_val .= '<tr>';
					$nr=[];$na=[];$val=[];
					$b_ter_stat = '';
					$bobot=$this->exam->convertComma($this->exam->bobot_tertimbang($bobot_all,$h->bobot,$h->not_available));
					if ($h->kpi == '1'){
						$penunjang = '<br><label class="label label-success">Terkait Data Penunjang</label>';
					}
					if ($bobot_tertimbang_stat && !$h->not_available) {
						$b_ter_stat=' <label class="label label-danger">Tertimbang</label>';
						// <td>'.$h->bobot.'%</td>
					}
					if($h->sifat == "MAX"){
						$sifat = '<label class="label label-success">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
					}else{
						$sifat = '<label class="label label-warning">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
					}
					$new_val .='
					<td width="3%">'.$no.'.</td>
					<td width="50%">'.$h->kpi.$penunjang.'</td>
					<td>'.$bobot.'%<br>'.$b_ter_stat.'</td>
					<td>'. $this->otherfunctions->getPenilai($h->kode_penilai).'</td>
					<td>'.$h->nama_rumus.'</td>
					<td class="text-center">'.$h->target.'</td>';
					if($h->kode_periode == "BLN") {
						if($usage=='hasil'){
							if($h->kode_penilai=='P3'){
								$exe = $this->exam->getUserPenilai($h->penilai,$id_log);
								if($exe){ 
									if($h->jenis_satuan==1){
										for ($i_n=1; $i_n <= $max_for; $i_n++) { 
											$var_2='capaian_'.$i_n;
											$cols_n='pn'.$i_n;
											if ($h->$cols_n == ''){
												$null[$h->kode_kpi][$i_n]=true;
											}
											$jenis_s=$this->exam->changeJenisSatuanAll($h->id_task,$h->$cols_n,$table);
											$pack[$var_2]=$this->exam->getCapaianNilai($jenis_s,$id_log,$h->bobot);
											$pn[$i_n] = $this->exam->getNilaiAverage($h->$cols_n,true);
											$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
											$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
										}
									}else{
										for ($i_n=1; $i_n <= $max_for; $i_n++) { 
											$col = 'pn'.$i_n;
											$var_2='capaian_'.$i_n;
											if ($h->$col == ''){
												$null[$h->kode_kpi][$i_n]=true;
											}
											$pack[$var_2] = $this->exam->getCapaianNilai($h->$col,$id_log,$h->bobot); 
											$pn[$i_n] = $this->exam->getNilaiAverage($h->$col,true);
											$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
											$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
										}
									}
								}
							}elseif($h->kode_penilai=='P1'){
								for ($i_n=1; $i_n <= $max_for; $i_n++) { 
									$var_pn = 'pn'.$i_n;
									$var_nr = 'nr'.$i_n;
									if ($h->$var_pn == ''){
										$null[$h->kode_kpi][$i_n]=true;
									}
									$var_na = 'na'.$i_n;
									$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
									$nr[$i_n] = $h->$var_nr;
									$na[$i_n] = $h->$var_na;
								}
							}
						}elseif($usage=='raport'){
							for ($i_n=1; $i_n <= $max_for; $i_n++) { 
								$var_pn = 'pn'.$i_n;
								$var_nr = 'nr'.$i_n;
								if ($h->$var_pn == ''){
									$null[$h->kode_kpi][$i_n]=true;
								}
								$var_na = 'na'.$i_n;
								$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
								$nr[$i_n] = $h->$var_nr;
								$na[$i_n] = $h->$var_na;
							}
						}
						$arr_data=['jenis_satuan'=>$h->jenis_satuan,'sifat'=>$h->sifat];
						for ($i_poin=1;$i_poin<=$this->max_range;$i_poin++){
							$p='poin_'.$i_poin;
							$s='satuan_'.$i_poin;
							$arr_data[$p]=$h->$p;
							$arr_data[$s]=$h->$s;
							if ($arr_data[$p] == null) {
								$arr_data[$s]=null;
							}
						}						
						for ($i_tb=1; $i_tb <= $max_for; $i_tb++) { 
							$na_konv[$i_tb]=$this->exam->coreConversiKpi($na[$i_tb],$arr_data);
							$na_final[$i_tb]=$na_konv[$i_tb]*($h->bobot/100);
							$na_final_real[$i_tb]=$na_konv[$i_tb]*($bobot_bulan[$i_tb]/100);
							if (isset($null[$h->kode_kpi][$i_tb])) {
								$na_konv[$i_tb]=0;
								$na_final[$i_tb]=null;
								$na_final_real[$i_tb]=null;
							}						
							$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
							<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'%</td>
							<td class="text-center bg-info">'.$this->formatter->getNumberFloat($na_konv[$i_tb]).'<br><b data-toggle="tooltip" title="Poin Terbobot" style="font-size:12pt;cursor:pointer;color:blue">'.$this->formatter->getNumberFloat($na_final[$i_tb]).'</b></td>';
							$month_total[$i_tb]+=$na_final[$i_tb];
							$month_total_real[$i_tb]+=$na_final_real[$i_tb];
						}
						//read rumus menghitung
						if ($h->cara_menghitung == 'SUM') {
							$na_new=$this->rumus->rumus_sum($na_final);
						}else {
							$na_new=$this->rumus->rumus_avg($na_final);
						}					
						$new_val .='<td class="text-center bg-success" style="font-size:12pt"><b>'.$this->formatter->getNumberFloat($na_new).'</b></td>';
					}
					$no++;
					$sumavgmax += $na_new;
					$new_val .= '</tr>';
					for ($i_final=1; $i_final <= $max_for; $i_final++) { 
						$nilai[$i_final] += $na[$i_final];
					}
				}
			}else{
				foreach ($data as $h) {
					// $bobot_bulan=[];
					// for ($i_b=1;$i_b<= $max_for;$i_b++){
					// 	$ba_bln=((isset($bobot_tertimbang_stat_arr[$i_b]))?$bobot_all:$bobot_tall);
					// 	if (isset($bobot_tertimbang_stat_arr[$i_b])){
					// 		$bobot_bulan[$i_b]=$this->exam->convertComma($this->exam->bobot_tertimbang($ba_bln,$h->bobot,$h->not_available));
					// 	}else{
					// 		$bobot_bulan[$i_b]=$this->exam->convertComma($this->exam->bobot_tertimbang($ba_bln,$h->bobot,0));
					// 	}
					// }
					$new_val .= '<tr>';
					$nr=[];$na=[];$val=[];
					$bobot=$this->exam->convertComma($this->exam->bobot_tertimbang($bobot_all,$h->bobot,$h->not_available));
					$penunjang = '';
					$b_ter_stat = '';
					if ($h->kpi == '1'){
						$penunjang = '<br><label class="label label-success">Terkait Data Penunjang</label>';
					}
					if ($bobot_tertimbang_stat > 0 && !$h->not_available) {
						$b_ter_stat=' <label class="label label-danger">Tertimbang</label>';
						// <td>'.$h->bobot.'%</td>
					}
					if($h->sifat == "MAX"){
						$sifat = '<label class="label label-success">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
					}else{
						$sifat = '<label class="label label-warning">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
					}
					$new_val .='
					<td width="3%">'.$no.'.</td>
					<td width="50%">'.$h->kpi.$penunjang.'</td>
					<td>'.$bobot.'%<br>'.$b_ter_stat.'</td>
					<td>'. $this->otherfunctions->getPenilai($h->kode_penilai).'</td>
					<td>'.$h->nama_rumus.'</td>
					<td class="text-center">'.$h->target.'</td>';
					if($h->kode_periode == "BLN") {
						if($usage=='hasil'){
							if($h->kode_penilai=='P3'){
								$exe = $this->exam->getUserPenilai($h->penilai,$id_log);
								if($exe){ 
									if($h->jenis_satuan==1){
										for ($i_n=1; $i_n <= $max_for; $i_n++) { 
											$var_2='capaian_'.$i_n;
											$cols_n='pn'.$i_n;
											if ($h->$cols_n == ''){
												$null[$h->kode_kpi][$i_n]=true;
											}
											$jenis_s=$this->exam->changeJenisSatuanAll($h->id_task,$h->$cols_n,$table);
											$pack[$var_2]=$this->exam->getCapaianNilai($jenis_s,$id_log,$h->bobot);
											// $pack[$var_2]=$this->exam->getCapaianNilai($jenis_s,$id_log,$bobot_bulan[$i_n]);
											$pn[$i_n] = $this->exam->getNilaiAverage($h->$cols_n,true);
											$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
											$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
										}
									}else{
										for ($i_n=1; $i_n <= $max_for; $i_n++) { 
											$col = 'pn'.$i_n;
											$var_2='capaian_'.$i_n;
											if ($h->$col == ''){
												$null[$h->kode_kpi][$i_n]=true;
											}
											// $pack[$var_2] = $this->exam->getCapaianNilai($h->$col,$id_log,$bobot_bulan[$i_n]); 
											$pack[$var_2] = $this->exam->getCapaianNilai($h->$col,$id_log,$h->bobot); 
											$pn[$i_n] = $this->exam->getNilaiAverage($h->$col,true);
											$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
											$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
										}
									}
								}
							}elseif($h->kode_penilai=='P1'){
								for ($i_n=1; $i_n <= $max_for; $i_n++) { 
									$var_pn = 'pn'.$i_n;
									$var_nr = 'nr'.$i_n;
									if ($h->$var_pn == ''){
										$null[$h->kode_kpi][$i_n]=true;
									}
									$var_na = 'na'.$i_n;
									$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
									$nr[$i_n] = $h->$var_nr;
									$na[$i_n] = $h->$var_na;
								}
							}
						}elseif($usage=='raport'){
							for ($i_n=1; $i_n <= $max_for; $i_n++) { 
								$var_pn = 'pn'.$i_n;
								$var_nr = 'nr'.$i_n;
								if ($h->$var_pn == ''){
									$null[$h->kode_kpi][$i_n]=true;
								}
								$var_na = 'na'.$i_n;
								$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
								$nr[$i_n] = $h->$var_nr;
								$na[$i_n] = $h->$var_na;
							}
						}
						$arr_data=['jenis_satuan'=>$h->jenis_satuan,'sifat'=>$h->sifat];
						for ($i_poin=1;$i_poin<=$this->max_range;$i_poin++){
							$p='poin_'.$i_poin;
							$s='satuan_'.$i_poin;
							$arr_data[$p]=$h->$p;
							$arr_data[$s]=$h->$s;
							if ($arr_data[$p] == null) {
								$arr_data[$s]=null;
							}
						}
						$nilai_realBulan = 0;
						for ($i_tb=1; $i_tb <= $max_for; $i_tb++) { 
							$na_konv[$i_tb]=$this->exam->coreConversiKpi($na[$i_tb],$arr_data);
							// $na_final[$i_tb]=$na_konv[$i_tb]*($bobot_bulan[$i_tb]/100);
							$na_final[$i_tb]=$na_konv[$i_tb]*($h->bobot/100);
							if (isset($null[$h->kode_kpi][$i_tb])) {
								$na_konv[$i_tb]=0;
								$na_final[$i_tb]=null;
							}
							$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
							<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'%</td>
							<td class="text-center bg-info">'.$this->formatter->getNumberFloat($na_konv[$i_tb]).'<br><b data-toggle="tooltip" title="Poin Terbobot" style="font-size:12pt;cursor:pointer;color:blue">'.$this->formatter->getNumberFloat($na_final[$i_tb]).'</b></td>';
							// $nilai_realBulan +=$na_final[$i_tb];
							// if ($h->cara_menghitung == 'SUM' && $h->sifat == "MAX") {
							// 	$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
							// 	<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'%</td>
							// 	<td class="text-center bg-info">'.$this->formatter->getNumberFloat($na_konv[$i_tb]).'<br><b data-toggle="tooltip" title="Poin Terbobot" style="font-size:12pt;cursor:pointer;color:blue">'.$this->formatter->getNumberFloat($nilai_realBulan).'</b></td>';
							// }else{
							// 	$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
							// 	<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'%</td>
							// 	<td class="text-center bg-info">'.$this->formatter->getNumberFloat($na_konv[$i_tb]).'<br><b data-toggle="tooltip" title="Poin Terbobot" style="font-size:12pt;cursor:pointer;color:blue">'.$this->formatter->getNumberFloat($na_final[$i_tb]).'</b></td>';
							// }
							$month_total[$i_tb]+=$na_final[$i_tb];
						}
						//read rumus menghitung
						// if ($h->cara_menghitung == 'SUM') {
						// 	if($h->sifat == "MAX"){
						// 		$na_new=$nilai_realBulan;
						// 	}else{
						// 		// $na_new=$this->rumus->rumus_sum($na_final);
						// 		$na_final=array_filter($na_final, 'is_numeric');
						// 		$na_new=end($na_final);
						// 	}
						// }else {
						// 	$na_new=$this->rumus->rumus_avg($na_final);
						// }
						if ($h->cara_menghitung == 'SUM') {
							$na_new=$this->rumus->rumus_sum($na_final);
						}else {
							$na_new=$this->rumus->rumus_avg($na_final);
						}
						$new_val .='<td class="text-center bg-success" style="font-size:12pt"><b>'.$this->formatter->getNumberFloat($na_new).'</b></td>';
					}
					$no++;
					$sumavgmax += $na_new;
					$new_val .= '</tr>';
					for ($i_final=1; $i_final <= $max_for; $i_final++) { 
						$nilai[$i_final] += $na[$i_final];
					}
				}
			}
			$new_val .= '<tr>';
			// $getKonversi=$this->model_master->getKonversiKpiNilai($sumavgmax);
			// $color=(isset($getKonversi['warna']))?$getKonversi['warna']:'grey';
			// $nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
			// $t_color=($color=='grey')?'black':'white';
			$new_val .= '<tr>';
			$kosongAll = 0;
			$nilaiKosong = 0;
			if (isset($agenda['start']) && isset($agenda['end']) && isset($agenda['tahun'])) {
				$new_val.='<td colspan="6" class="text-center bg-aqua" style="font-size:12pt;font-weight:600;">Nilai Total</td>';
				$periode = $this->formatter->getNameOfMonthByPeriode($agenda['start'],$agenda['end'],$agenda['tahun']);
				$c_month=1;
				foreach ($periode as $pkey => $pval) {
					$nilai_real_terbobot=null;
					if(isset($month_total_real[$c_month]) && isset($month_total[$c_month])){
						if($month_total_real[$c_month] != $month_total[$c_month]){
							if($month_total_real[$c_month] != 0){
								$kosongAll +=1;
								$nilai_real_terbobot = ((isset($month_total_real[$c_month]))?'<br><span style="font-size:11pt;font-weight:600;">'.$this->formatter->getNumberFloat($month_total_real[$c_month]).'</span>':null);
								$nilaiKosong += $month_total_real[$c_month];
							}
						}
					}
					$new_val .= '<td colspan="2" class="bg-aqua">Nilai Bulan <br>'.$pval.'</td><td class="text-center bg-blue" style="font-size:16pt;font-weight:bold">'.((isset($month_total[$c_month]))?$this->formatter->getNumberFloat($month_total[$c_month]):0).$nilai_real_terbobot.'</td>';
					$c_month++;
				}
			}else{
				$new_val.='<td colspan="23" class="text-center bg-aqua"></td>
				<td class="text-center bg-yellow" style="font-size:12pt;font-weight:600;">Nilai Akhir</td>';
			}
			if($kosongAll > 2){
				$sumavgmax = $nilaiKosong/$this->max_month;
			}else{
				$sumavgmax = $sumavgmax;
			}
			$getKonversi=$this->model_master->getKonversiKpiNilai($sumavgmax);
			$color=(isset($getKonversi['warna']))?$getKonversi['warna']:'grey';
			$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
			$t_color=($color=='grey')?'black':'white';
			$new_val.='<td class="bg-navy text-center" style="font-size:24pt;font-weight:bold;">
			<b>'.$this->formatter->getNumberFloat($sumavgmax).'</b>
			</td>
			</tr>';
			$datax = [
				'tabel'=>$new_val,
				'nilai'=>$this->formatter->getNumberFloat($sumavgmax),
				'huruf'=>$nama,
				'color'=>$color,
				'display'=>'show'
			];
		}else{
			$datax = [
				'tabel'=>'',
				'color'=>'black',
				'nilai'=>0,
				'display'=>'hide'
			];
		}
		echo json_encode($datax);
	}

//=============================================== IMPORTANT ============================================ 

// foreach ($data as $h) {
// 	$bobot_bulan=[];
// 	for ($i_b=1;$i_b<= $max_for;$i_b++){
// 		$ba_bln=((isset($bobot_tertimbang_stat_arr[$i_b]))?$bobot_all:$bobot_tall);
// 		if (isset($bobot_tertimbang_stat_arr[$i_b])){
// 			$bobot_bulan[$i_b]=$this->exam->convertComma($this->exam->bobot_tertimbang($ba_bln,$h->bobot,$h->not_available));
// 		}else{
// 			$bobot_bulan[$i_b]=$this->exam->convertComma($this->exam->bobot_tertimbang($ba_bln,$h->bobot,0));
// 		}
// 	}
// 	$new_val .= '<tr>';
// 	$nr=[];$na=[];$val=[];
// 	$bobot=$this->exam->convertComma($this->exam->bobot_tertimbang($bobot_all,$h->bobot,$h->not_available));
// 	$penunjang = '';
// 	$b_ter_stat = '';
// 	if ($h->kpi == '1'){
// 		$penunjang = '<br><label class="label label-success">Terkait Data Penunjang</label>';
// 	}
// 	if ($bobot_tertimbang_stat > 0 && !$h->not_available) {
// 		$b_ter_stat=' <label class="label label-danger">Tertimbang</label>';
// 		// <td>'.$h->bobot.'%</td>
// 	}
// 	if($h->sifat == "MAX"){
// 		$sifat = '<label class="label label-success">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
// 	}else{
// 		$sifat = '<label class="label label-warning">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
// 	}
// 	$new_val .='
// 	<td width="3%">'.$no.'.</td>
// 	<td width="50%">'.$h->kpi.$penunjang.'</td>
// 	<td>'.$bobot.'%<br>'.$b_ter_stat.'</td>
// 	<td>'. $this->otherfunctions->getPenilai($h->kode_penilai).'</td>
// 	<td>'.$h->nama_rumus.'</td>
// 	<td class="text-center">'.$h->target.'</td>';
// 	if($h->kode_periode == "BLN") {
// 		if($usage=='hasil'){
// 			if($h->kode_penilai=='P3'){
// 				$exe = $this->exam->getUserPenilai($h->penilai,$id_log);
// 				if($exe){ 
// 					if($h->jenis_satuan==1){
// 						for ($i_n=1; $i_n <= $max_for; $i_n++) { 
// 							$var_2='capaian_'.$i_n;
// 							$cols_n='pn'.$i_n;
// 							if ($h->$cols_n == ''){
// 								$null[$h->kode_kpi][$i_n]=true;
// 							}
// 							$jenis_s=$this->exam->changeJenisSatuanAll($h->id_task,$h->$cols_n,$table);
// 							$pack[$var_2]=$this->exam->getCapaianNilai($jenis_s,$id_log,$bobot_bulan[$i_n]);
// 							$pn[$i_n] = $this->exam->getNilaiAverage($h->$cols_n,true);
// 							$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
// 							$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
// 						}
// 					}else{
// 						for ($i_n=1; $i_n <= $max_for; $i_n++) { 
// 							$col = 'pn'.$i_n;
// 							$var_2='capaian_'.$i_n;
// 							if ($h->$col == ''){
// 								$null[$h->kode_kpi][$i_n]=true;
// 							}
// 							$pack[$var_2] = $this->exam->getCapaianNilai($h->$col,$id_log,$bobot_bulan[$i_n]); 
// 							$pn[$i_n] = $this->exam->getNilaiAverage($h->$col,true);
// 							$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
// 							$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
// 						}
// 					}
// 				}
// 			}elseif($h->kode_penilai=='P1'){
// 				for ($i_n=1; $i_n <= $max_for; $i_n++) { 
// 					$var_pn = 'pn'.$i_n;
// 					$var_nr = 'nr'.$i_n;
// 					if ($h->$var_pn == ''){
// 						$null[$h->kode_kpi][$i_n]=true;
// 					}
// 					$var_na = 'na'.$i_n;
// 					$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
// 					$nr[$i_n] = $h->$var_nr;
// 					$na[$i_n] = $h->$var_na;
// 				}
// 			}
// 		}elseif($usage=='raport'){
// 			for ($i_n=1; $i_n <= $max_for; $i_n++) { 
// 				$var_pn = 'pn'.$i_n;
// 				$var_nr = 'nr'.$i_n;
// 				if ($h->$var_pn == ''){
// 					$null[$h->kode_kpi][$i_n]=true;
// 				}
// 				$var_na = 'na'.$i_n;
// 				$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn,true);
// 				$nr[$i_n] = $h->$var_nr;
// 				$na[$i_n] = $h->$var_na;
// 			}
// 		}
// 		$arr_data=['jenis_satuan'=>$h->jenis_satuan,'sifat'=>$h->sifat];
// 		for ($i_poin=1;$i_poin<=$this->max_range;$i_poin++){
// 			$p='poin_'.$i_poin;
// 			$s='satuan_'.$i_poin;
// 			$arr_data[$p]=$h->$p;
// 			$arr_data[$s]=$h->$s;
// 			if ($arr_data[$p] == null) {
// 				$arr_data[$s]=null;
// 			}
// 		}
// 		for ($i_tb=1; $i_tb <= $max_for; $i_tb++) { 
// 			$na_konv[$i_tb]=$this->exam->coreConversiKpi($na[$i_tb],$arr_data);
// 			$na_final[$i_tb]=$na_konv[$i_tb]*($bobot_bulan[$i_tb]/100);
// 			if (isset($null[$h->kode_kpi][$i_tb])) {
// 				$na_konv[$i_tb]=0;
// 				$na_final[$i_tb]=null;
// 			}
// 			$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
// 			<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'%</td>
// 			<td class="text-center bg-info">'.$this->formatter->getNumberFloat($na_konv[$i_tb]).'<br><b data-toggle="tooltip" title="Poin Terbobot" style="font-size:12pt;cursor:pointer;color:blue">'.$this->formatter->getNumberFloat($na_final[$i_tb]).'</b></td>';
// 			$month_total[$i_tb]+=$na_final[$i_tb];
// 		}
// 		//read rumus menghitung
// 		if ($h->cara_menghitung == 'SUM') {
// 			$na_new=$this->rumus->rumus_sum($na_final);
// 		}else {
// 			$na_new=$this->rumus->rumus_avg($na_final);
// 		}					
// 		$new_val .='<td class="text-center bg-success" style="font-size:12pt"><b>'.$this->formatter->getNumberFloat($na_new).'</b></td>';
// 	}
// 	$no++;
// 	$sumavgmax += $na_new;
// 	$new_val .= '</tr>';
// 	for ($i_final=1; $i_final <= $max_for; $i_final++) { 
// 		$nilai[$i_final] += $na[$i_final];
// 	}
// }
	
	//don't delete this function
	// public function getRaport()
	// {
	// 	$table = $this->input->post('table');
	// 	$id = $this->input->post('id');
	// 	$jenis = $this->input->post('jenis');
	// 	$agenda = unserialize(base64_decode($this->input->post('agenda')));
	// 	$usage = $this->input->post('usage');
	// 	$id_log = $this->input->post('id_log');
	// 	$max_for = 4;
	// 	if(empty($usage))
	// 		$usage = 'raport';
	// 	if(empty($id_log))
	// 		$id_log = null;

	// 	$penunjang = '';
	// 	$no = 1;
	// 	$new_val = '';
	// 	for ($i_s=1; $i_s <= $max_for; $i_s++) { 
	// 		$nilai[$i_s] = 0;
	// 	}
	// 	$sumavgmax = 0;
	// 	$nilaixbobot = 0;
	// 	$data = $this->model_agenda->openTableWithJenisKpi($table,$jenis,$id);
	// 	if(count($data)>0){
	// 		foreach ($data as $h) {
	// 			$new_val .= '<tr>';
	// 			$nr=[];$na=[];$val=[];
	// 			if ($h->kpi == '1')
	// 				$penunjang = '<br><label class="label label-success">Terkait Data Penunjang</label>';

	// 			if($h->sifat == "MAX"){
	// 				$sifat = '<label class="label label-success">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
	// 			}else{
	// 				$sifat = '<label class="label label-warning">'.$this->otherfunctions->getSifatKpi($h->sifat).'</label>';
	// 			}
	// 			$new_val .='
	// 			<td>'.$no.'</td>
	// 			<td>'.$h->kpi.$penunjang.'</td>
	// 			<td>'.$h->bobot.'%</td>
	// 			<td>'. $this->otherfunctions->getPenilai($h->kode_penilai).'</td>
	// 			<td>'.$h->nama_rumus.'</td>
	// 			<td class="text-center">'.$h->target.'</td>';
	// 			if($h->kode_periode == "BLN") {
	// 				if($usage=='hasil'){
	// 					if($h->kode_penilai=='P3'){
	// 						$exe = $this->exam->getUserPenilai($h->penilai,$id_log);
	// 						if($exe){ 
	// 							if($h->jenis_satuan==1){
	// 								for ($i_n=1; $i_n <= $max_for; $i_n++) { 
	// 									$var_2='capaian_'.$i_n;
	// 									$cols_n='pn'.$i_n;
	// 									$jenis_s=$this->exam->changeJenisSatuanAll($h->id_task,$h->$cols_n,$table);
	// 									$pack[$var_2]=$this->exam->getCapaianNilai($jenis_s,$id_log,$h->bobot);
	// 									$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
	// 									$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
	// 								}
	// 							}else{
	// 								for ($i_n=1; $i_n <= $max_for; $i_n++) { 
	// 									$col = 'pn'.$i_n;
	// 									$var_2='capaian_'.$i_n;
	// 									$pack[$var_2] = $this->exam->getCapaianNilai($h->$col,$id_log,$h->bobot); 
	// 									$nr[$i_n] = $pack['capaian_'.$i_n]['capaian'];
	// 									$na[$i_n] = $pack['capaian_'.$i_n]['nilai'];
	// 								}
	// 							}
	// 						}
	// 					}elseif($h->kode_penilai=='P1'){
	// 						for ($i_n=1; $i_n <= $max_for; $i_n++) {
	// 							$var_pn = 'pn'.$i_n; 
	// 							$var_nr = 'nr'.$i_n;
	// 							$var_na = 'na'.$i_n;
	// 							$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn);
	// 							$nr[$i_n] = $h->$var_nr;
	// 							$na[$i_n] = $h->$var_na;
	// 						}
	// 					}
	// 				}elseif($usage=='raport'){
	// 					for ($i_n=1; $i_n <= $max_for; $i_n++) { 
	// 						$var_pn = 'pn'.$i_n;
	// 						$var_nr = 'nr'.$i_n;
	// 						$var_na = 'na'.$i_n;
	// 						$pn[$i_n] = $this->exam->getNilaiAverage($h->$var_pn);
	// 						$nr[$i_n] = $h->$var_nr;
	// 						$na[$i_n] = $h->$var_na;
	// 					}
	// 				}
	// 				for ($i_tb=1; $i_tb <= $max_for; $i_tb++) { 
	// 					$val[]=$na[$i_tb];
	// 					$new_val .='<td class="text-center">'.$this->formatter->getNumberFloat($pn[$i_tb]).'</td>
	// 					<td class="text-center">'.$this->formatter->getNumberFloat($nr[$i_tb]).'</td>
	// 					<td class="text-center">'.$this->formatter->getNumberFloat($na[$i_tb]).'</td>';
	// 				}
	// 				//read rumus menghitung
	// 				if ($h->rumus_kpi == 'SUM') {
	// 					$average=$this->rumus->rumus_sum($val);
	// 				}else {
	// 					$average=$this->rumus->rumus_avg($val);
	// 				}
	// 				$new_val .='<td class="text-center bg-success">'.$this->formatter->getNumberFloat($average).'</td>';
	// 			}
	// 			$no++;
	// 			$sumavgmax += $average;
	// 			$new_val .= '</tr>';
	// 			for ($i_final=1; $i_final <= $max_for; $i_final++) { 
	// 				$nilai[$i_final] += $na[$i_final];
	// 			}
	// 			$bobot_jenis = $h->bobot_jenis_kpi;
	// 		}
	// 		$new_val .= '<tr>
	// 		<td colspan="6" class="text-center bg-aqua"><b>Nilai Total</b></td>';
	// 		if ($h->kode_periode == "BLN") {
	// 			$periode = $this->formatter->getNameOfMonthByPeriode($agenda['start'],$agenda['end'],$agenda['tahun']);
	// 			$kc = 1;
	// 			foreach ($periode as $pkey => $pval) {

	// 				$new_val .= '<td colspan="2" class="text-center bg-navy">'.$pval.'</td>
	// 				<td class="text-center bg-info">'.$this->formatter->getNumberFloat($nilai[$kc]).'</td>';
	// 				$kc++;
	// 			}
	// 		}

	// 		$getKonversi=$this->model_master->getKonversiKpiJenis($sumavgmax,$jenis);
	// 		$color=(isset($getKonversi['warna']))?$getKonversi['warna']:'grey';
	// 		$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
	// 		$t_color=($color=='grey')?'black':'white';
	// 		$new_val .= '<td id="nilai_real_'.$jenis.'" class="text-center" style="vertical-align: middle;padding:0px;"><div style="display:block;background-color:'.$color.';"><b style="font-size:12pt;font-weight:600;color:'.$t_color.';">'.$this->formatter->changeNilaiCustom($sumavgmax,'0').'</b><br>
	// 		<b class="text-center" style="color:'.$t_color.'">'.$nama.'</b></div></td></tr>
	// 		<tr>
	// 		<td colspan="16" class="text-center bg-aqua"></td>
	// 		<td colspan="2" class="text-center bg-navy" style="font-size:12pt;font-weight:600;">Nilai x '.$bobot_jenis.'%</td>
	// 		<td class="bg-yellow text-center" id="nilai_akhir_'.$jenis.'" style="font-size:16pt;font-weight:bold;">
	// 		<b>'.$this->formatter->changeNilaiCustom(($sumavgmax*($bobot_jenis/100)),'0').'</b>
	// 		</td>
	// 		</tr>';
	// 		$datax = [
	// 			'tabel'=>$new_val,
	// 			'capaian'=>$sumavgmax,
	// 			'nilai'=>($sumavgmax*($bobot_jenis/100)),
	// 			'bobot'=>$bobot_jenis,
	// 			'display'=>'show'
	// 		];
	// 	}else{
	// 		$datax = [
	// 			'tabel'=>'',
	// 			'capaian'=>0,
	// 			'nilai'=>0,
	// 			'bobot'=>0,
	// 			'display'=>'hide'
	// 		];
	// 	}
	// 	echo json_encode($datax);
	// }
	public function getKonversiFinal()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$nilai = $this->input->post('nilai');
		$getKonversi=$this->model_master->getKonversiKpiJenis($nilai,'AKHIR');
		$color=(isset($getKonversi['warna']))?$getKonversi['warna']:'grey';
		$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
		$t_color=($color=='grey')?'black':'white';
		$html='<div style="display:block;background-color:'.$color.';"><b style="font-size:25pt;font-weight:600;color:'.$t_color.';">'.$this->formatter->changeNilaiCustom($nilai,'0').'</b><br>
		<b class="text-center" style="color:'.$t_color.'">'.$nama.'</b></div>';
		echo json_encode($html);
	}
	public function add_saran()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$tahun = null;
		$kode_periode = null;
		$tahun = $this->input->post('tahun');
		$id_karyawan = $this->input->post('id_karyawan');
		$periode = $this->input->post('periode');
		$saran = $this->input->post('saran');
		$id_pengirim = $this->admin;
		$exper = explode('-', $periode);
		if(!empty($exper[1])){
			$tahun = $exper[1];
			$kode_periode = $exper[0];
		}else{
			$tahun = $exper[0];
			$kode_periode = '';
		}
		if ($id_karyawan != "" || $id_pengirim != "" ) {
			$data=[
				'id_karyawan'=>$id_pengirim,
				'tahun'=>$tahun,
				'kode_periode'=>$kode_periode,
				'untuk'=>$id_karyawan,
				'saran'=>$saran,
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			unset($data['status']);
			$datax = $this->model_global->insertQuery($data,'data_saran_penilaian');
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_saran()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id = $this->input->post('id');
		$saran = $this->input->post('saran');
		if ($id != "") {
			$data=[
				'saran'=>$saran,
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			unset($data['status']);
			$datax = $this->model_global->updateQuery($data,'data_saran_penilaian',['id_saran'=>$id]);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	
	public function saran_penilaian()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$tahun = null;
				$kode_periode = null;
				$id = $this->input->post('id_karyawan');
				$periode = $this->input->post('periode');
				$exper = explode('-', $periode);
				if(!empty($exper[1])){
					$tahun = $exper[1];
					$kode_periode = $exper[0];
				}else{
					$tahun = $exper[0];
				}

				$data=$this->model_agenda->getListSaran($id,$kode_periode,$tahun);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_saran,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if($this->admin == $d->id_karyawan){
						$delete = '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal('.$d->id_saran.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
						$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_saran.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					}else{
						$delete = '';
						$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal('.$d->id_saran.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					}
					$aksi=$info.$delete;
					$words = explode(" ",$d->saran);
					$datax['data'][]=[
						$d->id_saran,
						($d->nama_karyawan)?$d->nama_karyawan:$d->nama_admin,
						(implode(" ",array_splice($words,0,10))),
						$aksi,
						$d->saran,
						count($words),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_saran');
				$data=$this->model_agenda->getListSaranId($id);
				foreach ($data as $d) {
					$acc_edit=false;
					if($this->admin == $d->id_karyawan){
						$acc_edit=true;
					}
					$datax=[
						'id'=>$d->id_saran,
						'saran'=>$d->saran,
						'acc_edit'=>$acc_edit,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function saran_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$tahun = null;
				$kode_periode = null;
				$id = $this->input->post('id_karyawan');
				$periode = $this->input->post('periode');
				$exper = explode('-', $periode);
				if(!empty($exper[1])){
					$tahun = $exper[1];
					$kode_periode = $exper[0];
				}else{
					$tahun = $exper[0];
				}
				if (!empty($kode_periode)) {
					$getTable=$this->model_agenda->getLogAgendaSikapPeriode($kode_periode,$tahun);
				}else{
					$getTable=$this->model_agenda->getLogAgendaSikapTahun($tahun);
				}
				$tabel = [];
				foreach ($getTable as $g) {
					$tabel[] = $g->nama_tabel;
				}
				$datax['data'] = [];
				if(isset($tabel)){
					foreach ($tabel as $tbb){
						$data=$this->model_agenda->openTableAgendaIdEmployee($tbb,$id);
						if(isset($data)){
							foreach ($data as $d) { 
								$var=[
									'id'=>$d->id_task,
								];
								$ket = ['keterangan_atas','keterangan_bawah','keterangan_rekan'];
								foreach ($ket as $kk => $kv) {
									$get_ket = $this->otherfunctions->getParseVar($d->$kv);
									$keterangan = (!empty($get_ket)) ? $get_ket : [];
									foreach ($keterangan as $kek => $kev) {
										$emp = $this->model_karyawan->getEmployeeId($kek);
										$asp = $this->model_master->getAspekKode($d->kode_aspek);
										if ($kv == 'keterangan_atas'){
											$sebagai = 'Atasan';
										}elseif ($kv == 'keterangan_bawah') {
											$sebagai = 'Bawahan';
										}elseif ($kv == 'keterangan_rekan') {
											$sebagai = 'Rekan';
										}
										$datax['data'][]=[
											$d->id_task,
											$asp['nama'],
											$d->kuisioner,
											$kev,
										];
									}
								}
							}
						}
						
					}
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_saran');
				$data=$this->model_agenda->getListSaranId($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id_saran,
						'id_penerima'=>$d->untuk,
						'nama_penerima'=>$d->nama_penerima,
						'jenis'=>$d->jenis_saran,
						'jenisview'=>$this->otherfunctions->getJenisSaran($d->jenis_saran),
						'saran'=>$d->saran,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function list_report_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$tgl = $this->date;
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$no=0;
				$datax['data']=[];
				$cek=$this->model_agenda->getListLogAgendaSikap();
				$data=[];
				if (count($cek) > 0) {
					foreach ($cek as $c) {
						if (!empty($c->nama_tabel)) {
							$tb=$this->model_agenda->openTableAgendaIdEmployee($c->nama_tabel,$this->admin);
							if (count($tb) > 0) {
								array_push($data, $c->kode_a_sikap);
							}
						}
					}
				}
				$datax['data']=[];
				if (isset($data)) {
					$data=array_filter($data);
					foreach ($data as $d) {
						$agenda=$this->model_agenda->getLogAgendaSikapKodeLink($d);
						$tanggal = '<label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal" data-placement="right">
						<i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($agenda['tgl_mulai']).' WIB
						</label><br>
						<label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal" data-placement="right">
						<i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($agenda['tgl_selesai']).' WIB
						</label>';
						$keterangan ='';
						if ($agenda['keterangan'] == "not_entry") {
							$keterangan .= '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
						}elseif ($agenda['keterangan'] == "progress") {
							$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
						}else{
							$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
						}
						$keterangan .= '<br>';
						if (date("Y-m-d H:i:s",strtotime($agenda['tgl_selesai'])) < date("Y-m-d H:i:s",strtotime($tgl))) {
							$keterangan .= '<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
						}elseif ((date("Y-m-d H:i:s",strtotime($agenda['tgl_mulai'])) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($agenda['tgl_selesai'])) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
							$keterangan .= '<label class="label label-info">Agenda Sedang Berlangsung</label>';
						}
						$keterangan .= '<br>';
						if ($agenda['validasi'] == 0) {
							$keterangan .= '<label class="label label-danger"><i class="fa fa-times"></i> Nilai Belum Divalidasi</label>';
						}else{
							$keterangan .= '<label class="label label-success"><i class="fa fa-check"></i> Nilai Sudah Divalidasi</label>';
						}
						if (isset($agenda)) {
							$datax['data'][]=[
								$agenda['id_l_a_sikap'],
								$agenda['nama'],
								$tanggal,
								$keterangan,
								$this->codegenerator->encryptChar($agenda['kode_a_sikap']),
								$agenda['nama_periode'],
								$agenda['tahun'],
							];
							$no++;
						}
						
					}
				}
				
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function list_report_output()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		$tgl = $this->date;
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			$id_login = $this->admin;
			if ($usage == 'view_all') {
				$no=0;
				$datax['data']=[];
				$cek=$this->model_agenda->getListAgendaKpi(1);
				$data=[];
				if (count($cek) > 0) {
					$lst=array();
					foreach ($cek as $c) {
						if ($c->nama_tabel != "") {
							$tb=$this->model_agenda->openTableAgendaIdEmployee($c->nama_tabel,$id_login);
							if (count($tb) > 0) {
								array_push($lst, $c->kode_a_kpi);
							}
						}
					}
					if (count($lst) > 0) {
						$data=array_filter($lst);
					}
				}
				$datax['data']=[];
				if (isset($data)) {
					foreach ($data as $d) {
						$agenda=$this->model_agenda->getLogAgendaKpiKodeLink($d);
						$tanggal = '<label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal" data-placement="right">
						<i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($agenda['tgl_mulai']).' WIB
						</label><br>
						<label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal" data-placement="right">
						<i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($agenda['tgl_selesai']).' WIB
						</label>';
						$keterangan ='';
						if ($agenda['keterangan'] == "not_entry") {
							$keterangan .= '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
						}elseif ($agenda['keterangan'] == "progress") {
							$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
						}else{
							$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
						}
						$keterangan .= '<br>';
						if (date("Y-m-d H:i:s",strtotime($agenda['tgl_selesai'])) < date("Y-m-d H:i:s",strtotime($tgl))) {
							$keterangan .= '<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
						}elseif ((date("Y-m-d H:i:s",strtotime($agenda['tgl_mulai'])) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($agenda['tgl_selesai'])) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
							$keterangan .= '<label class="label label-info">Agenda Sedang Berlangsung</label>';
						}
						$keterangan .= '<br>';
						if ($agenda['validasi'] == 0) {
							$keterangan .= '<label class="label label-danger"><i class="fa fa-times"></i> Nilai Belum Divalidasi</label>';
						}else{
							$keterangan .= '<label class="label label-success"><i class="fa fa-check"></i> Nilai Sudah Divalidasi</label>';
						}
						if (isset($agenda)) {
							$datax['data'][]=[
								$agenda['id_l_a_kpi'],
								$agenda['nama'],
								$tanggal,
								$keterangan,
								$this->codegenerator->encryptChar($agenda['kode_a_kpi']),
								$agenda['nama_periode'],
								$agenda['tahun'],
							];
							$no++;
						}
						
					}
				}
				
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function list_raport_bawahan_output()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->input->post('kode'));
		$tabel=$this->codegenerator->decryptChar($this->input->post('tabel'));
		if ($usage == null || $kode == null || $tabel == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
				foreach ($data as $d) {
					$emp=$this->model_karyawan->getEmployeeId($d);
					$data_nilai=$this->model_agenda->rumusCustomKubotaFinalResultKpi($tabel,$d,false,true);
					$nilai=(isset($data_nilai['nilai_akhir'])|| !empty($data_nilai))?$data_nilai['nilai_akhir']:0;
					$huruf=$this->model_master->getKonversiKpiNilai($nilai);
					$huruf=(isset($huruf['huruf']))?$huruf['huruf']:'Unknown';
					$arr_start=[
						$this->codegenerator->encryptChar($d),
						$emp['nik'],
						$emp['nama'],
						$emp['nama_jabatan'],
						$emp['bagian'],
						$emp['nama_departement'],
						$emp['nama_loker'],
					];
					$arr_month=[];
					for ($i=1; $i <= $this->max_month ; $i++) { 
						if (isset($data_nilai['nilai_bulan'][$i])) {
							$arr_month[$i]=$this->formatter->getNumberFloat($data_nilai['nilai_bulan'][$i]);
						}else{
							$arr_month[$i]=0;
						}
					}
					$arr_end=[
						$this->formatter->getNumberFloat($nilai),
						$huruf,
						$this->codegenerator->encryptChar($kode),
					];
					$datax['data'][]=array_merge($arr_start,$arr_month,$arr_end);
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function raport_bawahan_output()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		$tgl = $this->date;
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$no=0;
				$datax['data']=[];
				$cek=$this->model_agenda->getListLogAgendaKpi(1);
				if (isset($cek)) {
					foreach ($cek as $d) {
						if(!empty($d->nama_tabel)){
							$tanggal = '<label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal" data-placement="right">
							<i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB
							</label><br>
							<label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal" data-placement="right">
							<i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB
							</label>';
							$keterangan ='';
							if ($d->keterangan == "not_entry") {
								$keterangan .= '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
							}elseif ($d->keterangan == "progress") {
								$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
							}else{
								$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
							}
							$keterangan .= '<br>';
							if (date("Y-m-d H:i:s",strtotime($d->tgl_selesai)) < date("Y-m-d H:i:s",strtotime($tgl))) {
								$keterangan .= '<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
							}elseif ((date("Y-m-d H:i:s",strtotime($d->tgl_mulai)) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($d->tgl_selesai)) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
								$keterangan .= '<label class="label label-info">Agenda Sedang Berlangsung</label>';
							}
							$keterangan .= '<br>';
							if ($d->validasi == 0) {
								$keterangan .= '<label class="label label-danger"><i class="fa fa-times"></i> Nilai Belum Divalidasi</label>';
							}else{
								$keterangan .= '<label class="label label-success"><i class="fa fa-check"></i> Nilai Sudah Divalidasi</label>';
							}
							$datax['data'][]=[
								$d->id_l_a_kpi,
								$d->nama,
								$tanggal,
								$keterangan,
								$this->codegenerator->encryptChar($d->kode_a_kpi),
								$d->nama_periode,
								$d->tahun,
							];
							$no++;
						}
						
					}
				}				
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	//======GABUNGAN======//
	public function list_raport_group()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_agenda->getReportGroupList();
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$periode=$this->model_master->getListPeriodePenilaianActive();
					$datax['data'][$no-1]=[
						$no,
						'<a href="'.base_url('kpages/report_value_group/'.$this->codegenerator->encryptChar($d)).'">Raport Gabungan Tahun '.$d.'</a>',
					];	
					foreach ($periode as $k_p=>$p) {
						$link=$this->codegenerator->encryptChar($k_p.'-'.$d);
						array_push($datax['data'][$no-1],'<a href="'.base_url('kpages/report_value_group/'.$link).'" class="btn btn-warning"><i class="fa fa-line-chart"></i> Lihat Nilai '.$p.' '.$d.'</a>');
					}				
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function getKonversiKpi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data=$this->model_master->getListKonversiKpi(true);
		$no=1;
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->nama,
				(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
				(!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark()
			];
			$no++;
		}
		echo json_encode($datax);
	}
	public function report_value_group()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode=$this->codegenerator->decryptChar($this->input->post('data'));
		if (!isset($kode)) {
			$datax=$this->messages->notValidParam();
		}else{
			$param_max=(empty($kode['kode_periode']))?'tahunan':'quartal';
			$table='<div class="callout callout-danger">
			<label><i class="fa fa-times-circle"></i> Data Kosong</label><br>
			Tidak Ada Data yang Ditampilkan
			</div>';
			$data=$this->model_agenda->getReportGroupEmployee($kode['kode_periode'],$kode['tahun'],$kode['id']);
			$nilai_akhir=0;
			$max_periode=$this->model_master->getMaxPeriode($param_max);
			$usage_presensi=($param_max == 'tahunan')?'tahunan':'kuartal';
			$data_presensi=$this->model_presensi->rumusCustomKubotaFinalResultPresensi($kode['id'],$kode['kode_periode'],$kode['tahun'],$usage_presensi);
			$nilai_sikap=0;
			if (!empty($data['sikap'])) {
				foreach ($data['sikap'] as $kode_aspek => $nilai) {
					$nilai_sikap=$nilai_sikap+array_sum($nilai['nilai']);
				}
			}
			$nilai_kpi=($data['kpi'] != '' && $data['kpi'] != null)?$data['kpi']:0;			
			$nilai_kpi_terbobot=$nilai_kpi*($data['bobot_kpi']/100);
			$nilai_sikap_terbobot=$nilai_sikap*($data['bobot_sikap']/100);
			$nilai_akhir=($nilai_kpi_terbobot + $nilai_sikap_terbobot)-$data_presensi['nilai_akhir'];
			if(isset($kode['kode_periode'])){
				$r_kuartal=$this->model_master->getKonversiKuartalNilai($nilai_akhir);
				$color=(isset($r_kuartal['warna']))?$r_kuartal['warna']:null;
				$nama=(isset($r_kuartal['nama']))?$r_kuartal['nama']:'Unknown';
				$alert_tahunan='';
			}else{
				$r_tahunan=$this->model_master->getKonversiTahunanNilai($nilai_akhir);
				$color=(isset($r_tahunan['warna']))?$r_tahunan['warna']:null;
				$nama=(isset($r_tahunan['nama']))?$r_tahunan['nama']:null;
				$alert_tahunan='<div class="row">
				<div class="col-md-12">
					<div class="callout callout-info"><i class="fa fa-info-circle"></i> Hanya Agenda yang <b>SUDAH DIVALIDASI</b> saja yang akan masuk dalam Raport Tahunan </div>
				</div>
			</div>';
			}
			$table=$alert_tahunan.'			
			<div class="row">
				<div class="col-md-8">
					<h3><i class="fa fa-calendar-check text-yellow"></i> Kedisiplinan (Pengurang)</h3>
					<table class="table table-stripped table-hover">
						<thead>
							<tr class="bg-red">
								<th class="text-center">Jenis Kedisiplinan</th>
								<th class="text-center">Aktual</th>
								<th class="text-center">Terkonversi</th>
							</tr>
						</thead>
						<tbody>';
						foreach ($this->otherfunctions->getJenisPresensiList() as $k_pre => $v_pre) {
							$k_pre=strtolower($k_pre);
							$table.='<tr>
								<td>'.$v_pre.'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($data_presensi[$k_pre]).'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($data_presensi[$k_pre.'_konv']).'</td>
							</tr>';
						}
						$table.='<tr>
							<td colspan="2" class="bg-warning text-center"><b style="font-size:16pt">TOTAL</b></td>
							<td class="text-center bg-yellow"><b style="font-size:16pt;color:red">'.$this->formatter->getNumberFloat($data_presensi['nilai_akhir']).'</b></td>
						</tr>';
						$table.='</tbody>
					</table>
				</div>
				<div class="col-md-4">
					<h3><i class="fa fa-line-chart text-yellow"></i> Nilai Akhir</h3>
					<table class="table table-bordered">
						<tr>
							<th class="text-center" id="nilai_akhir" style="font-size:50pt;background-color:'.$color.';color:gray">'.$this->formatter->getNumberFloat($nilai_akhir).'</th>
						</tr>
						<tr>
							<th class="text-center" id="konversi_akhir" style="font-size:20pt">'.$nama.'</th>
						</tr>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3><i class="fa fa-trophy text-yellow"></i> Aspek Penilaian Kinerja</h3>
					<table class="table table-stripped table-hover" style="font-size:14pt">
						<thead>
							<tr class="bg-navy">
								<th class="text-center">Aspek Penilaian</th>
								<th class="text-center">Bobot</th>
								<th class="text-center">Aktual</th>
								<th class="text-center">Nilai Akhir</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>KPI Output</td>
								<td class="text-center">'.$data['bobot_kpi'].'%</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($nilai_kpi).'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($nilai_kpi_terbobot).'</td>
							</tr>
							<tr>
								<td>Aspek Sikap 360°</td>
								<td class="text-center">'.$data['bobot_sikap'].'%</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($nilai_sikap).'</td>
								<td class="text-center">'.$this->formatter->getNumberFloat($nilai_sikap_terbobot).'</td>
							</tr>
							
							<tr>
								<td colspan="3" class="bg-info text-center"><b style="font-size:16pt">TOTAL</b></td>
								<td class="text-center bg-blue"><b style="font-size:16pt">'.$this->formatter->getNumberFloat(($nilai_kpi_terbobot+$nilai_sikap_terbobot)).'</b></td>
							</tr>
							<tr>
								<td colspan="3">Kedisiplinan (Pengurang)</td>
								<td class="text-center" style="color:red">'.$this->formatter->getNumberFloat($data_presensi['nilai_akhir']).'</td>
							</tr>
							<tr>
								<td colspan="3" class="bg-info text-center"><b style="font-size:16pt">NILAI AKHIR</b></td>
								<td class="text-center bg-blue"><b style="font-size:25pt">'.$this->formatter->getNumberFloat($nilai_akhir).'</b></td>
							</tr>';
						$table.='</tbody>
					</table>
				</div>
			</div>';
			
			$emp = $this->model_karyawan->getEmployeeId($kode['id']);
			$data_print = [
				'id'=>$kode['id'],
				'nik'=>$emp['nik'],
				'nama'=>$emp['nama'],
				'jabatan'=>$emp['nama_jabatan'],
				'bagian'=>$emp['bagian'],
				'loker'=>$emp['nama_loker'],
				'departement'=>$emp['nama_departement'],
				'nilai'=>$this->formatter->getNumberFloat($nilai_akhir),
				'keterangan'=>$nama
			];
			$profile = $this->codegenerator->encryptChar($data_print);
			$table_print = [
				'rekap' =>[
					'table_print'=>$this->codegenerator->encryptChar($table),
					'profile'=>$this->codegenerator->encryptChar($data_print),
					'tahun'=>$kode['tahun']
				],
				'periode'=>$kode['kode_periode'],
				'periode_plain'=>((isset($kode['nama']))?$kode['nama']:null),
			];
			$datax = array_merge(['table_view'=>$table, 'data_print'=>$this->codegenerator->encryptChar($table_print)]);
		}
		echo json_encode($datax);
	}
	public function report_value_group_koversi()
	{
		$kode=$this->codegenerator->decryptChar($this->input->post('data'));
		$name='Tahunan';
		if(!empty($kode['kode_periode'])){
			$name='Kuartal';
		}
		$table = 
		'<div class="row">
		<div class="col-md-12" style="overflow:auto">
		<table class="table table-hover">
		<thead>
		<tr class="bg-green">
		<th>Nama Konversi Nilai '.$name.'</th>
		<th>Rentang Nilai</th>
		<th>Warna</th>
		</tr>
		</thead>
		<tbody>';
		if(!empty($kode['kode_periode'])){
			$data=$this->model_master->getListKonversiKuartal();
			foreach ($data as $d) {
				$warna = (!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark();
				$table .= '<tr>';
				$table .= '<td>'.$d->nama.'</td>';
				$table .= '<td>'.(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()).'</td>';
				$table .= '<td>'.$warna.'</td>';
				$table .= '</tr>';
			}
			$datax['table_view']=$table;
		}else{
			$data=$this->model_master->getListKonversiTahunan();
			foreach ($data as $d) {
				$warna = (!empty($d->warna))?'<i class="fa fa-circle" style="color:'.$d->warna.'"></i>':$this->otherfunctions->getMark();
				$table .= '<tr>';
				$table .= '<td>'.$d->nama.'</td>';
				$table .= '<td>'.(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()).'</td>';
				$table .= '<td>'.$warna.'</td>';
				$table .= '</tr>';
			}
			
		}
		$table .= '</tbody>
		</table">
		</div>
		</div>';
		$datax['table_view']=$table;
		echo json_encode($datax);
	}
	public function raport_bawahan()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$usefor = $this->input->post('usefor');
				$data=$this->model_agenda->getReportGroupList();
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$periode=$this->model_master->getListPeriodePenilaianActive();
					$pages='view_raport_bawahan';
					$btn='btn-warning';
					$icon='fa-line-chart';
					if ($usefor == 'approval') {
						$pages='view_approval_task';
						$btn='btn-success';
						$icon='fa-check-circle';
					}
					$datax['data'][$no-1]=[
						$no,
						'<a href="'.base_url('kpages/'.$pages.'/'.$this->codegenerator->encryptChar($d)).'">Raport Gabungan Tahun '.$d.'</a>',
					];	
					foreach ($periode as $k_p=>$p) {
						$link=$this->codegenerator->encryptChar($k_p.'-'.$d);
						array_push($datax['data'][$no-1],'<a href="'.base_url('kpages/'.$pages.'/'.$link).'" class="btn '.$btn.'"><i class="fa '.$icon.'"></i> Lihat Nilai '.$p.' '.$d.'</a>');
					}				
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_raport_bawahan()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null && empty($this->input->post('kode'))) {
			echo json_encode($this->messages->notValidParam());
		}else{
			$kode=$this->codegenerator->decryptChar($this->input->post('kode'));
			if($this->uri->segment(3) == 'tahunan'){
				$datax['data']=[];
				$data = $this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
				foreach ($data as $idk) {
					$kode['id']=$idk;
					$dt_nilai=$this->model_agenda->getListRaportTahunanHistory($kode);
					if(isset($dt_nilai)){
						foreach ($dt_nilai as $d){
							$kode['id']=$d->id_karyawan;
							$kode['kode_periode']=null;
							$auto_rank='';
							if($d->auto_rank_up_old){
								$auto_rank.='<br><label class="label label-default">Rank Up Otomatis</label>';
							}
							$ar1=[
								$d->id_karyawan,
								$d->nik,
								$d->nama,
								$d->nama_jabatan,
								$d->nama_bagian,
								$d->nama_departement,
								$d->nama_loker,
							];
							$ar2=[];
							$data_p=$this->model_master->getListPeriodePenilaian(1);
							if (isset($data_p)) {
								$cn=1;
								foreach ($data_p as $dp){
									$cols='q_'.$dp->kode_periode;
									$ar2[$cn]=(($d->$cols)?$this->formatter->getNumberFloat($d->$cols):0);
									$cn++;
								}
							}
							$ar3=[
								$this->codegenerator->encryptChar($kode),
								$this->codegenerator->encryptChar($idk),
							];
							$datax['data'][]=array_merge($ar1,$ar2,$ar3);
						}
					}
				}				
				echo json_encode($datax);
			}elseif ($usage == 'periode') {
				$datax['data']=[];
				$data = $this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
				foreach ($data as $d) {
					$dt_nilai=$this->model_agenda->getListEmployeeReportGroup($kode,['id_karyawan_filter'=>$d]);
					if (isset($dt_nilai[0])) {
						$datax['data'][]=$dt_nilai[0];
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function report_value_sikap()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->input->post('kode_agenda'));
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		$id_en=$this->admin;
		$cek=$this->model_agenda->getAgendaSikapKode($kode);
		if ($usage == null || empty($cek)) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$datax=[];
				$data=$this->model_agenda->rumusCustomKubotaFinalResultSikap($table,$id_en,'report');
				$table='<table class="table">
				<thead>
				<tr class="bg-green">
				<th>No.</th>
				<th>Nama Aspek Sikap</th>
				<th>Bobot</th>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$name_col=$this->exam->getWhatIsPartisipan($head);
						$table.='<th class="text-center" colspan="2">'.$name_col.'<br>('.((isset($data['bobot_column'][$head]))?$data['bobot_column'][$head]:0).'%)</th>';
					}
				}
				$table .='</tr>
				<tr>
				<th colspan="3" class="bg-green"></th>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$table.='<th class="text-center bg-blue">Capaian</th><th class="text-center bg-yellow">Nilai</th>';
					}
				}
				$table .='</tr></thead>
				</tbody>';
				if (isset($data['list_aspek'])) {
					$no=1;
					foreach ($data['list_aspek'] as $k_l => $v_l) {
						$table.='<tr><td>'.$no.'</td>
						<td>'.$v_l.'</td>
						<td>'.((isset($data['bobot_aspek'][$k_l]))?$data['bobot_aspek'][$k_l]:0).'%</td>';
						if (isset($data['head_column'])) {
							foreach ($data['head_column'] as $head) {
								$name_index=$this->exam->getWhatColPartisipan($head,'na');
								$capaian=(isset($data['capaian'][$k_l][$head]))?$data['capaian'][$k_l][$head]:0;
								$nilai=(isset($data[$name_index][$k_l]))?$data[$name_index][$k_l]:0;
								$table.='<td class="text-center">'.$this->formatter->getNumberFloat($capaian).'</td>
								<td class="text-center bg-warning">'.$this->formatter->getNumberFloat($nilai).'</td>';
							}
						}
						$table.='</tr>';
						$no++;
					}
				}				
				$table.= '<tr>
				<td colspan="3" class="text-center bg-aqua"><b>Nilai Total</b></td>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$nilai_total=(isset($data['sum_value'][$head]))?$data['sum_value'][$head]:0;
						$table.= '<td class="text-center bg-aqua"></td>
						<td class="text-center bg-warning"><b>'.$this->formatter->getNumberFloat($nilai_total).'</b></td>';
					}
				}
				$table.= '</tr><tr><td colspan="3" class="text-center bg-aqua"></td>';
				$col=2;
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$nilai_terbobot=(isset($data['value_bobot'][$head]))?$data['value_bobot'][$head]:0;
						$table.= '<td class="text-center bg-navy">Nilai x '.((isset($data['bobot_column'][$head]))?$data['bobot_column'][$head]:0).'%</td>
						<td class="text-center bg-yellow"><b>'.$this->formatter->getNumberFloat($nilai_terbobot).'</b></td>';
						$col=$col+2;
					}
				}
				$nilai_akhir=(isset($data['nilai_akhir']))?$data['nilai_akhir']:0;
				$nilai_akhir_old=(isset($data['nilai_akhir']))?$data['nilai_akhir']:0;
					$kalibrasi_stat=(empty($data['nilai_kalibrasi']))?false:true;
				$getKonversi=$this->model_master->getKonversiSikapVal($nilai_akhir);
				$color=(isset($getKonversi['warna']))?$getKonversi['warna']:null;
				$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
				$t_color=($color=='grey')?'black':'white';
				$table.= '</tr>
				<tr><td style="font-size:16pt" class="bg-blue text-center" colspan="'.$col.'"><b>Nilai Akhir</b></td><td class="text-center" style="font-size:16pt;background-color:'.$color.';color:'.$t_color.'">'.$this->formatter->getNumberFloat($nilai_akhir).'</td></tr>
				</tbody></table>';
				$nilai_akhir=(empty($data['nilai_kalibrasi']))?$nilai_akhir:$data['nilai_kalibrasi'];
				$getKonversi=$this->model_master->getKonversiSikapVal($nilai_akhir);
				$color=(isset($getKonversi['warna']))?$getKonversi['warna']:null;
				$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
				$t_color=($color=='grey')?'black':'white';
				$tbl_nilai = '	<table class="table table-bordered">
				<tr>
				<th class="text-center bg-yellow" style="font-size:16pt">Nilai Akhir Sikap</th>
				</tr>
				<tr>
				<th class="text-center" style="font-size:30pt;background-color:'.$color.';color:'.$t_color.'">'.$this->formatter->getNumberFloat($nilai_akhir).'</th>
				</tr>
				<tr>
				<th class="text-center" style="font-size:16pt">'.$nama.'</th>
				</tr>';
				if ($kalibrasi_stat && $data['nilai_kalibrasi'] != $nilai_akhir_old){
					$kalibrasi_value=$data['nilai_kalibrasi']-$nilai_akhir_old;
					if ($kalibrasi_value < 0) {
						$kalibrasi_value='<b class="err">(-) '.$this->formatter->getNumberFloat(abs($kalibrasi_value)).'</b>';
					}elseif ($kalibrasi_value > 0) {
						$kalibrasi_value='<b style="color:#006303">(+) '.$this->formatter->getNumberFloat($kalibrasi_value).'</b>';
					}
					$tbl_nilai.='<tr>
						<th class="bg-aqua text-center" style="font-size:14pt">Nilai Dikalibrasi '.$kalibrasi_value.'</th>
					</tr>';
				}
				$tbl_nilai.='</table>';
				$datax=[
					'table_view'=>$table,
					'nama_agenda'=>$cek['nama'],
					'tahun_agenda'=>$cek['tahun'],
					'periode_agenda'=>((isset($this->model_master->getListPeriodePenilaianActive()[$cek['periode']])) ? $this->model_master->getListPeriodePenilaianActive()[$cek['periode']] : $this->otherfunctions->getMark()),
					'nilai_akhir'=>$tbl_nilai,
				];
				echo json_encode($datax);
			}elseif ($usage == 'view_konversi') {
				$data=$this->model_master->getListKonversiSikap();
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$datax['data'][]=[
						$d->nama,
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						(!empty($d->warna)) ? '<i class="fa fa-circle" style="color:'.$d->warna.'"></i>' :$this->otherfunctions->getMark()
					];
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function report_value_bawahan_sikap()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->input->post('kode_agenda'));
		$table=$this->codegenerator->decryptChar($this->input->post('table'));
		$id_en=$this->codegenerator->decryptChar($this->input->post('id'));;
		$cek=$this->model_agenda->getAgendaSikapKode($kode);
		if ($usage == null || empty($cek)) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$datax=[];
				$data=$this->model_agenda->rumusCustomKubotaFinalResultSikap($table,$id_en,'report');
				$table='<table class="table">
				<thead>
				<tr class="bg-green">
				<th>No.</th>
				<th>Nama Aspek Sikap</th>
				<th>Bobot</th>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$name_col=$this->exam->getWhatIsPartisipan($head);
						$table.='<th class="text-center" colspan="2">'.$name_col.'<br>('.((isset($data['bobot_column'][$head]))?$data['bobot_column'][$head]:0).'%)</th>';
					}
				}
				$table .='</tr>
				<tr>
				<th colspan="3" class="bg-green"></th>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$table.='<th class="text-center bg-blue">Capaian</th><th class="text-center bg-yellow">Nilai</th>';
					}
				}
				$table .='</tr></thead>
				</tbody>';
				if (isset($data['list_aspek'])) {
					$no=1;
					foreach ($data['list_aspek'] as $k_l => $v_l) {
						$table.='<tr><td>'.$no.'</td>
						<td>'.$v_l.'</td>
						<td>'.((isset($data['bobot_aspek'][$k_l]))?$data['bobot_aspek'][$k_l]:0).'%</td>';
						if (isset($data['head_column'])) {
							foreach ($data['head_column'] as $head) {
								$name_index=$this->exam->getWhatColPartisipan($head,'na');
								$capaian=(isset($data['capaian'][$k_l][$head]))?$data['capaian'][$k_l][$head]:0;
								$nilai=(isset($data[$name_index][$k_l]))?$data[$name_index][$k_l]:0;
								$table.='<td class="text-center">'.$this->formatter->getNumberFloat($capaian).'</td>
								<td class="text-center bg-warning">'.$this->formatter->getNumberFloat($nilai).'</td>';
							}
						}
						$table.='</tr>';
						$no++;
					}
				}				
				$table.= '<tr>
				<td colspan="3" class="text-center bg-aqua"><b>Nilai Total</b></td>';
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$nilai_total=(isset($data['sum_value'][$head]))?$data['sum_value'][$head]:0;
						$table.= '<td class="text-center bg-aqua"></td>
						<td class="text-center bg-warning"><b>'.$this->formatter->getNumberFloat($nilai_total).'</b></td>';
					}
				}
				$table.= '</tr><tr><td colspan="3" class="text-center bg-aqua"></td>';
				$col=2;
				if (isset($data['head_column'])) {
					foreach ($data['head_column'] as $head) {
						$nilai_terbobot=(isset($data['value_bobot'][$head]))?$data['value_bobot'][$head]:0;
						$table.= '<td class="text-center bg-navy">Nilai x '.((isset($data['bobot_column'][$head]))?$data['bobot_column'][$head]:0).'%</td>
						<td class="text-center bg-yellow"><b>'.$this->formatter->getNumberFloat($nilai_terbobot).'</b></td>';
						$col=$col+2;
					}
				}
				$nilai_akhir=(isset($data['nilai_akhir']))?$data['nilai_akhir']:0;
				$nilai_akhir_old=(isset($data['nilai_akhir']))?$data['nilai_akhir']:0;
				$kalibrasi_stat=(empty($data['nilai_kalibrasi']))?false:true;
				$getKonversi=$this->model_master->getKonversiSikapVal($nilai_akhir);
				$color=(isset($getKonversi['warna']))?$getKonversi['warna']:null;
				$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
				$t_color=($color=='grey')?'black':'white';
				$table.= '</tr>
				<tr><td style="font-size:16pt" class="bg-blue text-center" colspan="'.$col.'"><b>Nilai Akhir</b></td><td class="text-center" style="font-size:16pt;background-color:'.$color.';color:'.$t_color.'">'.$this->formatter->getNumberFloat($nilai_akhir).'</td></tr>
				</tbody></table>';
				$nilai_akhir=(empty($data['nilai_kalibrasi']))?$nilai_akhir:$data['nilai_kalibrasi'];
				$getKonversi=$this->model_master->getKonversiSikapVal($nilai_akhir);
				$color=(isset($getKonversi['warna']))?$getKonversi['warna']:null;
				$nama=(isset($getKonversi['nama']))?$getKonversi['nama']:'Unknown';
				$t_color=($color=='grey')?'black':'white';
				$tbl_nilai = '	<table class="table table-bordered">
				<tr>
				<th class="text-center bg-yellow" style="font-size:16pt">Nilai Akhir Sikap</th>
				</tr>
				<tr>
				<th class="text-center" style="font-size:30pt;background-color:'.$color.';color:'.$t_color.'">'.$this->formatter->getNumberFloat($nilai_akhir).'</th>
				</tr>
				<tr>
				<th class="text-center" style="font-size:16pt">'.$nama.'</th>
				</tr>';
				if ($kalibrasi_stat && $data['nilai_kalibrasi'] != $nilai_akhir_old){
					$kalibrasi_value=$data['nilai_kalibrasi']-$nilai_akhir_old;
					if ($kalibrasi_value < 0) {
						$kalibrasi_value='<b class="err">(-) '.$this->formatter->getNumberFloat(abs($kalibrasi_value)).'</b>';
					}elseif ($kalibrasi_value > 0) {
						$kalibrasi_value='<b style="color:#006303">(+) '.$this->formatter->getNumberFloat($kalibrasi_value).'</b>';
					}
					$tbl_nilai.='<tr>
						<th class="bg-aqua text-center" style="font-size:14pt">Nilai Dikalibrasi '.$kalibrasi_value.'</th>
					</tr>';
				}
				$tbl_nilai.='</table>';
				$datax=[
					'table_view'=>$table,
					'nama_agenda'=>$cek['nama'],
					'tahun_agenda'=>$cek['tahun'],
					'periode_agenda'=>((isset($this->model_master->getListPeriodePenilaianActive()[$cek['periode']])) ? $this->model_master->getListPeriodePenilaianActive()[$cek['periode']] : $this->otherfunctions->getMark()),
					'nilai_akhir'=>$tbl_nilai,
				];
				echo json_encode($datax);
			}elseif ($usage == 'view_konversi') {
				$data=$this->model_master->getListKonversiSikap();
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$datax['data'][]=[
						$d->nama,
						(($d->min != '') ? $d->min : $this->otherfunctions->getMark()).' - '.(($d->max != '') ? $d->max : $this->otherfunctions->getMark()),
						(!empty($d->warna)) ? '<i class="fa fa-circle" style="color:'.$d->warna.'"></i>' :$this->otherfunctions->getMark()
					];
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function raport_bawahan_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$tgl = $this->date;
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=[];
				$datax['data']=[];
				$cek=$this->model_agenda->getListLogAgendaSikap();
				if (isset($cek)) {
					foreach ($cek as $d) {
						if(!empty($d->nama_tabel)){
							$tanggal = '<label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal" data-placement="right">
							<i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB
							</label><br>
							<label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal" data-placement="right">
							<i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB
							</label>';
							$keterangan ='';
							if ($d->keterangan == "not_entry") {
								$keterangan .= '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
							}elseif ($d->keterangan == "progress") {
								$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
							}else{
								$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
							}
							$keterangan .= '<br>';
							if (date("Y-m-d H:i:s",strtotime($d->tgl_selesai)) < date("Y-m-d H:i:s",strtotime($tgl))) {
								$keterangan .= '<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
							}elseif ((date("Y-m-d H:i:s",strtotime($d->tgl_mulai)) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($d->tgl_selesai)) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
								$keterangan .= '<label class="label label-info">Agenda Sedang Berlangsung</label>';
							}
							$keterangan .= '<br>';
							if ($d->validasi == 0) {
								$keterangan .= '<label class="label label-danger"><i class="fa fa-times"></i> Nilai Belum Divalidasi</label>';
							}else{
								$keterangan .= '<label class="label label-success"><i class="fa fa-check"></i> Nilai Sudah Divalidasi</label>';
							}
							$datax['data'][]=[
								$d->id_l_a_sikap,
								$d->nama,
								$tanggal,
								$keterangan,
								$this->codegenerator->encryptChar($d->kode_a_sikap),
								$d->nama_periode,
								$d->tahun,
							];
						}
					}
				}				
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function list_raport_bawahan_sikap()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		$kode=$this->codegenerator->decryptChar($this->input->post('kode'));
		$tabel=$this->codegenerator->decryptChar($this->input->post('tabel'));
		if ($usage == null || $kode == null || $tabel == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data = $this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
				foreach ($data as $d) {
					$emp=$this->model_karyawan->getEmployeeId($d);
					$data_nilai=$this->model_agenda->rumusCustomKubotaFinalResultSikap($tabel,$d,'list');
					$nilai=(isset($data_nilai['nilai_akhir']))?$data_nilai['nilai_akhir']:0;
					if (isset($data_nilai['nilai_kalibrasi'])) {
						$nilai=(!empty($data_nilai['nilai_kalibrasi']))?$data_nilai['nilai_kalibrasi']:$nilai;
					}
					$huruf=$this->model_master->getKonversiSikapVal($nilai);
					$huruf=(isset($huruf['huruf']))?$huruf['huruf']:'Unknown';
					$datax['data'][]=[
						$this->codegenerator->encryptChar($d),
						$emp['nik'],
						$emp['nama'],
						$emp['nama_jabatan'],
						$emp['bagian'],
						$emp['nama_departement'],
						$emp['nama_loker'],
						$this->formatter->getNumberFloat($nilai),
						$huruf,
						$this->codegenerator->encryptChar($kode),
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function get_list_log_agenda_kpi(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data=$this->model_agenda->getListLogAgendaKpi();
		$datax=[];
		if (isset($data)){
			foreach($data as $d){
				if(!empty($d->kode_a_kpi)){
					$datax[$this->codegenerator->encryptChar($d->kode_a_kpi)]=$d->nama;
				}
			}
		}
		if(isset($datax)){
			$datax=array_filter(array_unique($datax));
		}
		echo json_encode($datax);
	}
	function rekap_kpi_bawahan(){
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		if(empty($kode)){
			redirect('not_found');
		}
		parse_str($_SERVER['QUERY_STRING'], $post_form);
		if (isset($post_form['bulan'])) {
			$post_form['bulan']=$this->codegenerator->decryptChar($post_form['bulan']);
		}
		$data['properties']=[
			'title'=>"Rekap Hasil Penilaian KPI Bawahan",
			'subject'=>"Rekap Hasil Penilaian KPI Bawahan",
			'description'=>"Rekap Hasil Penilaian KPI Bawahan HSOFT KUBOTA",
			'keywords'=>"Rekap Data Bawahan, Rekap Nilai Bawahan, Rekap Nilai KPI, Rekap KPI",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if(!empty($kode)){
			$cek=$this->model_agenda->getLogAgendaKpiKodeLink($kode);
			$data_b = $this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
			if(isset($data_b) && isset($cek)){
				foreach ($data_b as $d) {
					$emp=$this->model_karyawan->getEmployeeId($d);
					if(isset($emp)){
						$dtnilai=$this->model_agenda->rumusCustomKubotaFinalResultKpi($cek['nama_tabel'],$d,false,true);
						$nilai=(isset($dtnilai['nilai_akhir']))?$dtnilai['nilai_akhir']:0;
						$huruf=$this->model_master->getKonversiKpiNilai($nilai);
						$huruf=(isset($huruf['huruf']))?$huruf['huruf']:'Unknown';
						$arr_body_start=[($row-1),$emp['nik'],$emp['nama'],$emp['nama_jabatan'],$emp['bagian'],$emp['nama_departement'],$emp['nama_loker'],];
						$arr_body_month=[];
						for ($i=1; $i <= $this->max_month ; $i++) { 
							if (isset($dtnilai['nilai_bulan'][$i])) {
								$arr_body_month[$i]=$this->formatter->getNumberFloat($dtnilai['nilai_bulan'][$i],2,'en');
							}else{
								$arr_body_month[$i]=0;
							}
						}
						$arr_body_end=[$this->formatter->getNumberFloat($nilai,2,'en'),$huruf,$cek['nama_periode'].' - Tahun '.$cek['tahun'],];
						$body[$row]=array_merge($arr_body_start,$arr_body_month,$arr_body_end);
						$row++;
					}
				}
			}
		}
		$arr_head_start=['No','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA'];
		$arr_head_month=[];
		if (isset($post_form['bulan'])) {
			$arr_head_month=$post_form['bulan'];
		}
		$numer=[];
		for ($i=7; $i <=($this->max_month+7) ; $i++) { 
			array_push($numer,$i);
		}
		$arr_head_end=['NILAI','HURUF','PERIODE'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Nilai KPI Bawahan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>array_merge($arr_head_start,$arr_head_month,$arr_head_end),
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
				'numeric'=>$numer,
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	function get_list_log_agenda_sikap(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data=$this->model_agenda->getListLogAgendaSikap();
		$datax=[];
		if (isset($data)){
			foreach($data as $d){
				if(!empty($d->kode_a_sikap)){
					$datax[$this->codegenerator->encryptChar($d->kode_a_sikap)]=$d->nama;
				}
			}
		}
		if(isset($datax)){
			$datax=array_filter(array_unique($datax));
		}
		echo json_encode($datax);
	}
	function rekap_sikap_bawahan(){
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		if(empty($kode)){
			redirect('not_found');
		}
		$data['properties']=[
			'title'=>"Rekap Hasil Penilaian Aspek Sikap (360°) Bawahan",
			'subject'=>"Rekap Hasil Penilaian Aspek Sikap (360°) Bawahan",
			'description'=>"Rekap Hasil Penilaian Aspek Sikap (360°) Bawahan HSOFT KUBOTA",
			'keywords'=>"Rekap Data Bawahan, Rekap Nilai Bawahan, Rekap Nilai Aspek Sikap (360°), Rekap Aspek Sikap (360°)",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if(!empty($kode)){
			$cek=$this->model_agenda->getLogAgendaSikapKodeLink($kode);
			$data_b = $this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
			if(isset($data_b) && isset($cek)){
				foreach ($data_b as $d) {
					$emp=$this->model_karyawan->getEmployeeId($d);
					if(isset($emp)){
						$nilai=$this->model_agenda->rumusCustomKubotaFinalResultSikap($cek['nama_tabel'],$d);
						$nilai=(isset($nilai['nilai_akhir']))?$nilai['nilai_akhir']:0;
						$huruf=$this->model_master->getKonversiSikapVal($nilai);
						$huruf=(isset($huruf['huruf']))?$huruf['huruf']:'Unknown';
						$body[$row]=[($row-1),$emp['nik'],$emp['nama'],$emp['nama_jabatan'],$emp['bagian'],$emp['nama_departement'],$emp['nama_loker'],$cek['nama_periode'].' - Tahun '.$cek['tahun'],$this->formatter->getNumberFloat($nilai,2,'en'),$huruf];
						$row++;
					}
				}
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Nilai Aspek Sikap Bawahan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA','PERIODE','NILAI','HURUF'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
				'numeric'=>[8],
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	function get_list_log_agenda_gabungan(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data=$this->model_agenda->getReportGroupList();
		$datax=[];
		if (isset($data)){
			foreach($data as $d){
				$periode=$this->model_master->getListPeriodePenilaianActive();
				$datax[$this->codegenerator->encryptChar($d)]='Raport Gabungan Tahunan - Tahun '.$d;	
				foreach ($periode as $k_p=>$p) {
					$link=$this->codegenerator->encryptChar($k_p.'-'.$d);
					$datax[$link]='Raport Gabungan '.$p.' '.$d;
				}
			}
		}
		if(isset($datax)){
			$datax=array_filter(array_unique($datax));
		}
		echo json_encode($datax);
	}
	function rekap_akhir_bawahan(){
		parse_str($_SERVER['QUERY_STRING'], $post_form);
		$perth=((isset($post_form['nama_periode']))?$post_form['nama_periode']:'Unknown');
		$kode=((isset($post_form['nama_periode']))?$this->codegenerator->decryptChar($post_form['kode']):null);
		$data['properties']=[
			'title'=>"Rekap Data Hasil PA Bawahan ".$perth,
			'subject'=>"Rekap Data Hasil PA Bawahan ".$perth,
			'description'=>"Rekap Data Hasil PA Bawahan ".$perth.",Rekap Data Hasil PA Bawahan ",
			'keywords'=>"Rekap Data Hasil PA Bawahan ".$perth.",Hasil PA Bawahan ".$perth,
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if(!empty($kode)){
			$data_b = $this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
			if(isset($data_b)){
				foreach ($data_b as $d) {
					$datax = $this->model_agenda->getListEmployeeReportGroup($kode,['id_karyawan_filter'=>$d]);
					if(isset($datax)){
						foreach ($datax as $k => $v) {
							$body[$row]=[
								($row-1),
								$v[1],
								$v[2],
								$v[3],
								$v[4],
								$v[5],
								$v[6],
								$this->formatter->getNumberFloat($v[7],2,'en'),
								$this->formatter->getNumberFloat($v[8],2,'en'),
								$this->formatter->getNumberFloat($v[9],2,'en'),
								$this->formatter->getNumberFloat($v[10],2,'en'),
								$this->formatter->getNumberFloat($v[11],2,'en'),
								$v[12],
								$perth];
							$row++;
						}
					}					
				}
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Hasil PA Bawahan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No.','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA','NILAI ASPEK KPI OUTPUT','NILAI ASPEK SIKAP 360°','NILAI TOTAL','KEDISIPLINAN','NILAI '.$perth,'HURUF','PERIODE'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
				'numeric'=>[7,8,9,10,11],
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_akhir_bawahan_tahunan()
	{
		if(empty($this->uri->segment(3))){
			redirect('not_found');
		}
		parse_str($_SERVER['QUERY_STRING'], $post_form);
		$perth=((isset($post_form['nama_periode']))?$post_form['nama_periode']:'Unknown');
		$kode=((isset($post_form['nama_periode']))?$this->codegenerator->decryptChar($post_form['kode']):null);
		$data['properties']=[
			'title'=>"Rekap Data Hasil PA Bawahan Tahun ".$kode['tahun'],
			'subject'=>"Rekap Data Hasil PA Bawahan Tahun ".$kode['tahun'],
			'description'=>"Rekap Data Hasil PA Bawahan Tahun ".$kode['tahun'].",Rekap Data Hasil PA Bawahan ",
			'keywords'=>"Rekap Data Hasil PA Bawahan Tahun ".$kode['tahun'].",Hasil PA Bawahan Tahun ".$kode['tahun'],
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$data_p=$this->model_master->getListPeriodePenilaian(1);
		if(!empty($kode)){
			$data_b = $this->model_karyawan->getBawahan($this->dtroot['adm']['jabatan']);
			if(isset($data_b)){
				foreach ($data_b as $idk) {
					$kode['id']=$idk;
					$datax=$this->model_agenda->getListRaportTahunanHistory($kode);
					if(isset($datax)){
						foreach ($datax as $k=>$d) {
							$auto_rank='';
							if($d->auto_rank_up_old){
								$auto_rank.=' [Rank Up Otomatis]';
							}
							$ar1=[
								($row_body-1).'.',
								$d->nik,
								$d->nama,
								$d->nama_jabatan,
								$d->nama_bagian,
								$d->nama_departement,
								$d->nama_loker,
							];
							$ar2=[];
							if (isset($data_p)) {
								$cn=1;
								foreach ($data_p as $dp){
									$cols='q_'.$dp->kode_periode;
									$ar2[$cn]=(($d->$cols)?$this->formatter->getNumberFloat($d->$cols,2,'en'):0);
									$cn++;
								}
							}
							$body[$row_body]=array_merge($ar1,$ar2);
							$row_body++;
						}
					}
				}
			}
		}
		$head_arr1=[
			'No.','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA'
		];
		$head_arr2=[];
		if (isset($data_p)) {
			foreach ($data_p as $dp){
				$head_arr2[]=(($dp->nama)?$dp->nama:'Unknown');
			}
		}
		$max_per=count($head_arr2)+6;
		$col_numeric=[];
		for ($i=1; $i <= count($head_arr2); $i++) { 
			array_push($col_numeric,($i+6));
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Hasil PA',
			'head'=>[
				'row_head'=>1,
				'data_head'=>array_merge($head_arr1,$head_arr2),
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
				'numeric'=>$col_numeric,
			],
		];

		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function data_input_kpi_departemen()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {

				$where = ['a.tgl_mulai <='=> $this->date,'a.tgl_selesai >='=> $this->date];
				$data=$this->model_agenda->getListAgendaKpiDepartemen($where);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$tgl = $this->date;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_a_kpi_departemen,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$tanggal='<label class="label label-success" data-toggle="tooltip" title="Mulai Tanggal"><i class="fas fa-check fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Selesai Tanggal"><i class="fa fa-times fa-fw"></i>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_selesai).' WIB</label>';
					$val_ket = null;
					if(!empty($d->keterangan)){
						$ket1 = explode(";",$d->keterangan);
						foreach ($ket1 as $key1 => $value1) {
							$ket2 = explode(":",$value1);
							if($ket2[0] == $this->admin){
								$val_ket = $ket2[1];
							}
						}
					}
					$keterangan = '';
					if ($val_ket == "not_entry" || $val_ket == null || $val_ket == "") {
						$keterangan .= '<label class="label label-danger">Belum Ada Data</label>';
					}elseif ($val_ket == "progress") {
						$keterangan .= '<label class="label label-warning">Proses Entry Data</label>';
					}else{
						$keterangan .= '<label class="label label-success">Semua Data Selesai Diisi</label>';
					}

					$progress_data = $this->model_agenda->getListPicKpiDepartemen(['a.kode_a_kpi_departemen'=>$d->kode_a_kpi_departemen],$d->kode_a_kpi_departemen,'all_item');
					if(empty($progress_data)){
						$progress = '<label class="label label-danger">tidak ada data</label>';
					}else{
						$progress_start = 0;
						$progress_end = count($progress_data);
						foreach ($progress_data as $p) {
							if(!empty($p->id_karyawan)){
								$progress_start++;
							}
						}
						$progress='<div class="progress active" style="background:#c4c4c4" data-toggle="tooltip" title="'.$progress_start.' / '.$progress_end.' ( '.(($progress_start/$progress_end)*100).'% )" data-placement="right">
						<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.(($progress_start/$progress_end)*100).'" aria-valuemin="0" aria-valuemax="100" style="width: '.(($progress_start/$progress_end)*100).'%">
						</div>
						</div>';
					}

					$start = $this->formatter->getNameOfMonth($d->start);
					$end = $this->formatter->getNameOfMonth($d->end);

					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_a_kpi_departemen,
						$d->nama,
						'<center>'.$progress.'</center>',
						$tanggal,
						$keterangan,
						$this->codegenerator->encryptChar($d->kode_a_kpi_departemen),
						$d->tahun,
						$d->nama_periode.' ('.$start.' s/d '.$end.')',
						$this->codegenerator->encryptChar(1),
						'<a class="btn btn-primary" href="'.base_url('kpages/print_data_input_kpi_departemen/'.$this->codegenerator->encryptChar($d->kode_a_kpi_departemen)).'" target="_blank"><i class="fas fa-print"></i></a>',
					];
					$no++;
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_i_nilai_kpip()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');

		$kode_agenda = $this->input->post('kode_agenda');
		$kode_mkpi = $this->input->post('kode_mkpi');
		$number = $this->input->post('number');
		$id_karyawan = $this->input->post('id_karyawan');
		$tahun = $this->input->post('tahun');
		$keyx = ['target','aktual','evaluasi'];
		/*Master Data*/
		for ($i=1; $i < 7; $i++) { 
			${'master_n'.$i} = $this->input->post('master_n'.$i);
		}
		$data=[
			'kode_a_kpi_departemen'=>$kode_agenda,
			'kode_kpi_departemen'=>$kode_mkpi,
			'id_karyawan'=>$id_karyawan,
			'tahun'=>$tahun
		];
		$cek = $this->otherfunctions->convertResultToRowArray($this->model_agenda->getListNilaiKpiDepartemen($data,'all_item','single'));
		if(!empty($cek)){
			for ($i=1; $i < 7; $i++) { 
				$data['n'.$i] = $this->otherfunctions->convertKeyValue($keyx,${'master_n'.$i},'number');
			}
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQuery($data,'nilai_kpi_departemen_master',['id_nilai'=>$cek['id_nilai']]);
		}else{
			for ($i=1; $i < 7; $i++) { 
				$data['n'.$i] = $this->otherfunctions->convertKeyValue($keyx,${'master_n'.$i},'number');
			}
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$this->model_global->insertQuery($data,'nilai_kpi_departemen_master');
		}
		/*View Data*/
		$kode_vkpi = $this->input->post('kode_vkpi');
		$kode_dkpi = $this->input->post('kode_dkpi');
		$keyxv = ['target_kpi','target','evaluasi','remark'];
		foreach ($kode_vkpi as $vkey => $vval) {
			$datav=[
				'kode_a_kpi_departemen'=>$kode_agenda,
				'kode_kpi_departemen'=>$kode_mkpi,
				'kode_data_kpi_departemen'=>$kode_dkpi[$vkey],
				'kode_view_kpi_departemen'=>$vval,
				'id_karyawan'=>$id_karyawan,
				'tahun'=>$tahun,
			];
			for ($iv=1; $iv < 7; $iv++) { 
				${'view_n'.$iv} = $this->input->post('view_n'.$iv.$vval);
			}
			$cekv = $this->otherfunctions->convertResultToRowArray($this->model_agenda->getListNilaiKpiDepartemenView($datav,'all_item','single'));
			if(!empty($cekv)){
				for ($iv=1; $iv < 7; $iv++) { 
					$datav['n'.$iv] = $this->otherfunctions->convertKeyValue($keyxv,${'view_n'.$iv},'number');
				}
				$datav['hasil_aktivitas'] = $this->input->post('hasil_'.$vval);
				$datav=array_merge($datav,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQuery($datav,'nilai_kpi_departemen_view',['id_nilai'=>$cekv['id_nilai']]);
			}else{
				for ($iv=1; $iv < 7; $iv++) { 
					$datav['n'.$iv] = $this->otherfunctions->convertKeyValue($keyxv,${'view_n'.$iv},'number');
				}
				$datav['hasil_aktivitas'] = $this->input->post('hasil_'.$vval);
				$datav=array_merge($datav,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQuery($datav,'nilai_kpi_departemen_view');
			}
		}

		$datax = $this->messages->allGood();
		echo json_encode($datax);
	}

	public function edt_ket_kpi_departemen()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode_agenda = $this->input->post('kode_agenda');
		$usage = $this->input->post('usage');
		$id_karyawan = $this->input->post('id_karyawan');
		$data_agenda = $this->otherfunctions->convertResultToRowArray($this->model_agenda->getListAgendaKpiDepartemen(['a.kode_a_kpi_departemen'=>$kode_agenda]));
		$keterangan = $data_agenda['keterangan'];
		if($usage != 'last'){
			$val_ket = 'progress';
		}else{
			$val_ket = 'selesai';
		}
		$new_ket = $this->exam->getNilaiPackRemove($val_ket,$id_karyawan,$keterangan);
		$data = [
			'keterangan'=>$new_ket
		];
		$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
		$this->model_global->updateQueryNoMsg($data,'agenda_kpi_departemen',['kode_a_kpi_departemen'=>$kode_agenda]);
		echo json_encode(['status'=>'success']);
	}
	public function view_approval_task()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode = $this->codegenerator->decryptChar($this->input->post('kode'));
		$page=$this->input->post('page');
		$usage=$this->uri->segment(3);
		if ($usage == 'view_all') {
			$datax['data']=[];
			$data=$this->model_agenda->getListEmployeeApproval($kode,null,$this->admin,$page);
			if (isset($data)) {
				foreach ($data as $d) {
					$datax['data'][]=$d;
				}
			}
			echo json_encode($datax);
		}elseif ($usage == 'get_employee') {
			$data=$this->model_agenda->getListEmployeeApproval($kode,null,$this->admin,$page,'employee');
			$datax=[];
			if (isset($data)) {
				foreach ($data as $id_karyawan => $d) {
					if (isset($d['nama']) && isset($d['nama_jabatan'])) {
						$datax[$id_karyawan]=$d['nama'].(($d['nama_jabatan'])?' ('.$d['nama_jabatan'].')':null);
					}
				}
			}
			echo json_encode($datax);
		}else{
			echo json_encode($this->messages->notValidParam());
		}				
	}
	public function approval_pa_all()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$karyawan=$this->input->post('karyawan');
		$kode = $this->codegenerator->decryptChar($this->input->post('kode'));
		if (!empty($kode)) {
			$emp=[];
			if (isset($karyawan)) {
				foreach ($karyawan as $id_karyawan) {
					array_push($emp,$id_karyawan);
				}
			}else{
				$all_karyawan=$this->model_agenda->getListEmployeeApproval($kode,null,$this->admin,'fo','employee');
				if (isset($all_karyawan)) {
					foreach ($all_karyawan as $id_karyawan => $d) {
						array_push($emp,$id_karyawan);
					}
				}
			}
			if (count($emp) > 0) {
				$datax=$this->messages->notValidParam();
				foreach($emp as $id_emp){
					$kode['id']=$id_emp;
					$data=[
						'id_karyawan'=>$kode['id'],
						'kode_periode'=>$kode['kode_periode'],
						'tahun'=>$kode['tahun'],
					];
					$cek=$this->model_agenda->getAprovalPa($kode['id'],$kode);
					$status=1;
					$id=$this->admin;
					$data_approve=$this->model_karyawan->getReviewer($kode['id'],$kode,$this->admin);
					if (count($data_approve) > 0) {
						foreach ($data_approve as $da) {
							if ($da['sebagai'] != 'MKR' && !empty($kode['kode_periode']) && $da['id_karyawan'] == $this->admin) {
								$sebagai=$da['sebagai'];
							}
						}
					}
					if ($sebagai == 'MKR') {
						if (!empty($cek)) {
							$old=$cek['maker'];
							if ($status) {
								$id=$this->otherfunctions->addValueToArrayDb($old,$id);
							}else{
								$id=$this->otherfunctions->removeValueToArrayDb($old,$id);
							}
						}
						$data['maker']=$id;
					}elseif ($sebagai == 'RVW') {
						if (!empty($cek)) {
							$old=$cek['reviewer'];
							if ($status) {
								$id=$this->otherfunctions->addValueToArrayDb($old,$id);
							}else{
								$id=$this->otherfunctions->removeValueToArrayDb($old,$id);
							}
						}
						$data['reviewer']=$id;
					}elseif ($sebagai == 'APR') {
						if (!empty($cek)) {
							$old=$cek['approval'];
							if ($status) {
								$id=$this->otherfunctions->addValueToArrayDb($old,$id);
							}else{
								$id=$this->otherfunctions->removeValueToArrayDb($old,$id);
							}
						}
						$data['approval']=$id;
					}
					if ($sebagai){
						if ($cek) {
							$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($data,'data_check_pa',['id_karyawan'=>$kode['id'],'kode_periode'=>$kode['kode_periode'],'tahun'=>$kode['tahun']]);
						}else{
							$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($data,'data_check_pa');
						}
					}					
				}
			}		
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
}