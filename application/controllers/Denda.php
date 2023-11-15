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

class Denda extends CI_Controller
{
	public function __construct()
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
	/*
	function add_denda(){
		$tahun=$this->input->post('tahun');
		if ($tahun != "") {
			$kode="DND".date("dmYHis",strtotime($this->date));
			$smt=$this->input->post('semester');
			$nama="Data Denda Tahun ".$tahun;
			$tb='d'.uniqid();
			$data=array(
				'kode_denda'=>strtoupper($kode),
				'nama_denda'=>$nama,
				'nama_tabel'=>$tb,
				'tahun'=>$tahun,
				'semester'=>$smt,
				'edit'=>'1',
				'create_date'=>$this->date,
				'update_date'=>$this->date,
			);
			
				$fields = array(
					'id_denda' => array(
						'type' => 'INT',
						'constraint' => 255,
						'unsigned' => TRUE,
						'auto_increment' => TRUE
					),
					'nama' => array(
						'type' => 'VARCHAR',
						'constraint' => 300,
						'null'=> TRUE
					),
					'pyd1' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pyd2' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pyd3' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pyd4' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pyd5' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pyd6' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pd1' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pd2' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pd3' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pd4' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pd5' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pd6' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'tgt1' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'tgt2' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'tgt3' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'tgt4' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'tgt5' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'tgt6' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'pa1' => array(
						'type' => 'FLOAT',
						'default'=> 0
					),
					'pa2' => array(
						'type' => 'FLOAT',
						'default'=> 0
					),
					'pa3' => array(
						'type' => 'FLOAT',
						'default'=> 0
					),
					'pa4' => array(
						'type' => 'FLOAT',
						'default'=> 0
					),
					'pa5' => array(
						'type' => 'FLOAT',
						'default'=> 0
					),
					'pa6' => array(
						'type' => 'FLOAT',
						'default'=> 0
					),
					'ratapyd' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'ratapd' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'ratatgt' => array(
						'type' => 'VARCHAR',
						'constraint' => 1000,
						'default'=> 0
					),
					'ratapa' => array(
						'type' => 'FLOAT',
						'default'=> 0
					)
				);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id_denda', TRUE);
			$this->dbforge->create_table($tb);

			$lok=$this->model_master->list_loker();
			$dtt1=array('nama'=>'Konsolidasi');
			$this->db->insert($tb,$dtt1);
			foreach ($lok as $l) {
				$kode=$l->nama;
				$dtt=array('nama'=>$kode);
				$this->db->insert($tb,$dtt);
			}
			
			$in=$this->db->insert('dp_denda',$data);
			if ($in) {
				$this->session->set_flashdata('msgsc','<label><i class="fa fa-check-circle"></i> Insert Data Berhasil</label><hr class="message-inner-separator">Insert Denda berhasil disimpan ke database'); 
			}else{
				$this->session->set_flashdata('msgerr','<label><i class="fa fa-times-circle"></i> Insert Data Gagal</label><hr class="message-inner-separator">Insert Denda gagal disimpan ke database, silahkan periksa kembali data anda'); 
			}
	   		redirect('pages/denda');
		}else{
			redirect('pages/denda');
		}
	}
	*/
	function status_denda(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array(
				'status'=>$this->input->post('act'),
			);
			$this->db->where('id_denda',$kode);
			$in=$this->db->update('dp_denda',$data);
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure(); 
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/denda');
	}
	function lock_denda(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array(
				'edit'=>$this->input->post('lock'),
			);
			$this->db->where('id_denda',$kode);
			$in=$this->db->update('dp_denda',$data);
			if ($in) {
				if ($this->input->post('lock') == 0) {
					$this->messages->customGood('Data Terkunci'); 
				}else{
					$this->messages->customGood('Data Terbuka');
				}
			}else{
				$this->messages->allFailure();
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/denda');
	}
	function add_value(){
		$kode=$this->input->post('kode');
		$cek=$this->model_master->cek_denda($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/denda');
		}else{
			$tb=$this->input->post('tabel');
			$indi=explode(',',$cek['kode_indikator']);
			$ch1=$cek['kait'];
			$agd=$this->model_agenda->cek_agd($cek['kode_agenda']);
			$tbagd=$agd['tabel_agenda'];
			$id_denda=array();
			for ($i=1; $i<=6 ; $i++) { 
				$pyd[$i]=$this->input->post('pyd'.$i);
				$pd[$i]=$this->input->post('pd'.$i);
				foreach ($pyd[$i] as $kpy => $py) {
					$tg[$i][$kpy]=0.001*$py;
					if ($tg[$i][$kpy] != 0) {
						$pax[$i][$kpy]=($pd[$i][$kpy]/$tg[$i][$kpy])*100;
						if ($pax[$i][$kpy] >= 125) {
							$pa[$i][$kpy]=125;
						}elseif ($pax[$i][$kpy] < 0) {
							$pa[$i][$kpy]=0;
						}else{
							$pa[$i][$kpy]=$pax[$i][$kpy];
						}
					}else{
						$pa[$i][$kpy]=0;
					}
					$pydx[$kpy][$i]=$py;
					$pdx[$kpy][$i]=$pd[$i][$kpy];
					$tgx[$kpy][$i]=$tg[$i][$kpy];
					$paxx[$kpy][$i]=$pa[$i][$kpy];
					array_push($id_denda, $kpy);
					$data[$i][$kpy]=array(
						'pyd'.$i=>$py,
						'pd'.$i=>$pd[$i][$kpy],
						'tgt'.$i=>$tg[$i][$kpy],
						'pa'.$i=>$pa[$i][$kpy],
					);
					//db
					$this->db->where('id_denda',$kpy);
					$this->db->update($tb,$data[$i][$kpy]);
				}
				$ppyd[$i]=array_sum($pyd[$i]);
				$ppd[$i]=array_sum($pd[$i]);
				$tg1[$i]=0.001*($ppyd[$i]);
				if ($tg1[$i] != 0) {
					$pax1[$i]=($ppd[$i]/$tg1[$i])*100;
					if ($pax1[$i] >= 125) {
						$pa1[$i]=125;
					}elseif ($pax1[$i] < 0) {
						$pa1[$i]=0;
					}else{
						$pa1[$i]=$pax1[$i];
					}
				}else{
					$pa1[$i]=0;
				}

				$datak[$i]=array(
					'pyd'.$i=>$ppyd[$i],
					'pd'.$i=>$ppd[$i],
					'tgt'.$i=>$tg1[$i],
					'pa'.$i=>$pa1[$i],
				);
				//db
				$this->db->where('id_denda',1);
				$this->db->update($tb,$datak[$i]);
			}
			$id_d=array_unique($id_denda);
			foreach ($id_d as $idd) {
				$datax[$idd]=array(
					'ratapyd'=>array_sum($pydx[$idd])/count($pydx[$idd]),
					'ratapd'=>array_sum($pdx[$idd])/count($pdx[$idd]),
					'ratatgt'=>array_sum($tgx[$idd])/count($tgx[$idd]),
					'ratapa'=>array_sum($paxx[$idd])/count($paxx[$idd]),
				);
				//db
				$this->db->where('id_denda',$idd);
				$this->db->update($tb,$datax[$idd]);
			}
			$datakx=array(
				'ratapyd'=>array_sum($ppyd)/count($ppyd),
				'ratapd'=>array_sum($ppd)/count($ppd),
				'ratatgt'=>array_sum($tg1)/count($tg1),
				'ratapa'=>array_sum($pa1)/count($pa1),
			);
			//db
			$this->db->where('id_denda',1);
			$this->db->update($tb,$datakx);
			if ($ch1 == '1') {
				foreach ($indi as $i) {
					$dnn=$this->model_master->tb_denda($tb);
					foreach ($dnn as $dn) {
						$nmlok=$dn->nama;
						$dtab=$this->db->get_where($tbagd,array('kode_indikator'=>$i))->result();
						foreach ($dtab as $dd) {
							$ky=$this->model_karyawan->emp($dd->id_karyawan);
							$loker=$this->model_master->k_loker($ky['unit']);
							$jbt=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$ky['jabatan']))->row_array();
							if ($dd->konsolidasi == 0) {
								if ($dn->id_denda != 1) {
									if ($loker['nama'] == $nmlok) {
										$bb2=$dd->bobot/100;
										if ($jbt['kode_periode'] == 'SMT') {
											$dta=array(
												'nra6'=>$dn->ratapa,
												'na6'=>$dn->ratapa*$bb2,
												'nilai_out'=>$dn->ratapa*$bb2,
											);
										}else{
											$dta=array(
												'nra1'=>$dn->pa1,
												'na1'=>$dn->pa1*$bb2,
												'nra2'=>$dn->pa2,
												'na2'=>$dn->pa2*$bb2,
												'nra3'=>$dn->pa3,
												'na3'=>$dn->pa3*$bb2,
												'nra4'=>$dn->pa4,
												'na4'=>$dn->pa4*$bb2,
												'nra5'=>$dn->pa5,
												'na5'=>$dn->pa5*$bb2,
												'nra6'=>$dn->pa6,
												'na6'=>$dn->pa6*$bb2,
												'nilai_out'=>$dn->ratapa*$bb2,
											);
										}
										$wh=array('kode_indikator'=>$i,'id_karyawan'=>$dd->id_karyawan);
										$this->db->where($wh);
										$this->db->update($tbagd,$dta);
									}
								}
							}else{
								if ($dn->id_denda == 1) {
									$bb2=$dd->bobot/100;
									if ($jbt['kode_periode'] == 'SMT') {
										$dta=array(
											'nra6'=>$dn->ratapa,
											'na6'=>$dn->ratapa*$bb2,
											'nilai_out'=>$dn->ratapa*$bb2,
										);
									}else{
										$dta=array(
											'nra1'=>$dn->pa1,
											'na1'=>$dn->pa1*$bb2,
											'nra2'=>$dn->pa2,
											'na2'=>$dn->pa2*$bb2,
											'nra3'=>$dn->pa3,
											'na3'=>$dn->pa3*$bb2,
											'nra4'=>$dn->pa4,
											'na4'=>$dn->pa4*$bb2,
											'nra5'=>$dn->pa5,
											'na5'=>$dn->pa5*$bb2,
											'nra6'=>$dn->pa6,
											'na6'=>$dn->pa6*$bb2,
											'nilai_out'=>$dn->ratapa*$bb2,
										);
									}
									$wh=array('kode_indikator'=>$i,'id_karyawan'=>$dd->id_karyawan,'konsolidasi'=>'1');
									$this->db->where($wh);
									$this->db->update($tbagd,$dta);
								}
							}
						}
					}
				}
			}
			$dttd=array('edit'=>'0');
			$this->db->where('kode_denda',$kode);
			$in=$this->db->update('dp_denda',$dttd);
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure(); 
			}
			redirect('pages/view_denda/'.$kode);
		}
	}
	
	function del_denda(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$dt=$this->db->get_where('dp_denda',array('id_denda'=>$kode))->row_array();
			$tb=$dt['nama_tabel'];
			$this->dbforge->drop_table($tb,TRUE);

			$this->db->where('id_denda',$kode);
			$in=$this->db->delete('dp_denda');
			if ($in) {
				$this->messages->delGood();
			}else{
				$this->messages->delFailure();
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/denda');
	}
	function chain_denda(){
		$kag=$this->input->post('kode');
		$indi=$this->input->post('indi_denda');
		if ($kag == NULL || $indi == NULL) {
			$this->messages->notValidParam();  
			redirect('pages/agenda');
		}else{
			$agenda=$this->db->get_where('agenda',array('kode_agenda'=>$kag))->row_array();
			$target=$this->db->get_where('dp_denda',array('kode_agenda'=>$kag))->row_array();
			$tbtg=$target['nama_tabel'];
			$tb=$agenda['tabel_agenda'];
			foreach ($indi as $i) {
				$dt=$this->db->get_where($tb,array('kode_indikator'=>$i))->result();
				foreach ($dt as $d) {
					$dtk[$d->id_karyawan]=$d->id_karyawan;
				}
				foreach ($dtk as $dk) {
					$dt1=$this->db->get_where($tb,array('id_karyawan'=>$dk,'konsolidasi'=>'1'))->result();
					$dt2=$this->db->get_where($tb,array('id_karyawan'=>$dk,'kode_indikator'=>$i))->row_array();
					$datak=$this->db->get_where('karyawan',array('id_karyawan'=>$dk))->row_array();
					$jbt=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$datak['jabatan']))->row_array();
					$bbt=$dt2['bobot']/100;
					if (count($dt1) == 0) {
						$unit1=$datak['unit'];
						$dtlk=$this->db->get_where('master_loker',array('kode_loker'=>$unit1))->row_array();
						$unit=$dtlk['nama'];
						$tbg=$this->db->get_where($tbtg,array('nama'=>$unit))->row_array();
						if ($jbt['kode_periode'] == 'SMT') {
							$data=array(
								'nra6'=>$tbg['ratapa'],
								'na6'=>$tbg['ratapa']*$bbt,
								'nilai_out'=>$tbg['ratapa']*$bbt,
							);
						}else{
							$data=array(
								'nra1'=>$tbg['pa1'],
								'na1'=>$tbg['pa1']*$bbt,
								'nra2'=>$tbg['pa2'],
								'na2'=>$tbg['pa2']*$bbt,
								'nra3'=>$tbg['pa3'],
								'na3'=>$tbg['pa3']*$bbt,
								'nra4'=>$tbg['pa4'],
								'na4'=>$tbg['pa4']*$bbt,
								'nra5'=>$tbg['pa5'],
								'na5'=>$tbg['pa5']*$bbt,
								'nra6'=>$tbg['pa6'],
								'na6'=>$tbg['pa6']*$bbt,
								'nilai_out'=>$tbg['ratapa']*$bbt,
							);
						}
					}else{
						$tbg=$this->db->get_where($tbtg,array('nama'=>'Konsolidasi'))->row_array();
						if ($jbt['kode_periode'] == 'SMT') {
							$data=array(
								'nra6'=>$tbg['ratapa'],
								'na6'=>$tbg['ratapa']*$bbt,
								'nilai_out'=>$tbg['ratapa']*$bbt,
							);
						}else{
							$data=array(
								'nra1'=>$tbg['pa1'],
								'na1'=>$tbg['pa1']*$bbt,
								'nra2'=>$tbg['pa2'],
								'na2'=>$tbg['pa2']*$bbt,
								'nra3'=>$tbg['pa3'],
								'na3'=>$tbg['pa3']*$bbt,
								'nra4'=>$tbg['pa4'],
								'na4'=>$tbg['pa4']*$bbt,
								'nra5'=>$tbg['pa5'],
								'na5'=>$tbg['pa5']*$bbt,
								'nra6'=>$tbg['pa6'],
								'na6'=>$tbg['pa6']*$bbt,
								'nilai_out'=>$tbg['ratapa']*$bbt,
							);
						}
					}
					$wh=array('id_karyawan'=>$dk,'kode_indikator'=>$i);
					$this->db->where($wh);
					$this->db->update($tb,$data);
				}
			}
			
			$dtta=array('kait'=>'1','kode_indikator'=>implode(',', $indi));
			$this->db->where('kode_agenda',$kag);
			$in=$this->db->update('dp_denda',$dtta);
			if ($in) {
				$this->messages->allGood();
			}else{
				$this->messages->allFailure();
			}
			redirect('pages/agenda'); 
		}
	}
	function export(){
		$kode=$this->input->post('kode');
		$cek=$this->model_master->cek_denda($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/denda');
		}else{ 
			$tb=$this->input->post('tabel');
			$dt=$this->db->query("SELECT * FROM $tb WHERE id_denda != '1'")->result();
	        if(count($dt)>0){
	            $objPHPExcel = new PHPExcel();
				// Set document properties
				$objPHPExcel->getProperties()->setCreator("Galeh Fatma Eko A")
											 ->setLastModifiedBy("Galeh Fatma Eko A")
											 ->setTitle($cek['nama_denda'].' Semester'.$cek['semester'])
											 ->setSubject($cek['nama_denda'])
											 ->setDescription($cek['nama_denda'].' Semester'.$cek['semester'])
											 ->setKeywords($cek['nama_denda'])
											 ->setCategory($cek['nama_denda']);
				// Add some data
	            $tri=1;
				for ($chrf='A'; $chrf!="AA"; $chrf++){
				 	$huruf[$tri]=$chrf;
				 	$tri++;
				}
				$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
		                array(
		                    'fill' => array(
		                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                        'color' => array('rgb' => 'f49542')
		                    ),
		                    'font' => array(
		                        'color' => array('rgb' => '000000')
		                    )
		                )
		            );	
				$objPHPExcel->getActiveSheet()->getStyle('D1:O1')->applyFromArray(
		                array(
		                    'fill' => array(
		                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                        'color' => array('rgb' => '00a01a')
		                    ),
		                    'font' => array(
		                        'color' => array('rgb' => 'FFFFFF')
		                    )
		                )
		            );
				
				
		        $objPHPExcel->getActiveSheet(0)
							->getColumnDimension('A')
							->setAutoSize(true);
				$objPHPExcel->getActiveSheet(0)
							->getColumnDimension('B')
							->setAutoSize(true);
				$objPHPExcel->getActiveSheet(0)
							->getColumnDimension('C')
							->setAutoSize(true);									
				$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('A1:A2')
							->mergeCells('B1:B2')
							->mergeCells('C1:C2')
							->setCellValue('A1', 'No.')
							->setCellValue('B1', 'Kode Lokasi')
							->setCellValue('C1', 'Nama Kantor');
				$bulan=array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','November','Desember');

				if ($cek['semester'] == '1') {
					$hr1=4;
					$hr2=5;
					for ($x=0; $x <6 ; $x++) { 
						$objPHPExcel->getActiveSheet()->getStyle($huruf[$hr1].'2')->applyFromArray(
			                array(
			                    'fill' => array(
			                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			                        'color' => array('rgb' => '0079b2')
			                    ),
			                    'font' => array(
			                        'color' => array('rgb' => 'FFFFFF')
			                    )
			                )
			            );
			            $objPHPExcel->getActiveSheet()->getStyle($huruf[$hr2].'2')->applyFromArray(
			                array(
			                    'fill' => array(
			                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			                        'color' => array('rgb' => '1e0082')
			                    ),
			                    'font' => array(
			                        'color' => array('rgb' => 'FFFFFF')
			                    )
			                )
			            );
						$objPHPExcel->getActiveSheet(0)
							->getColumnDimension($huruf[$hr1])
							->setAutoSize(true);
						$objPHPExcel->getActiveSheet(0)
							->getColumnDimension($huruf[$hr2])
							->setAutoSize(true);
						$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells($huruf[$hr1].'1:'.$huruf[$hr2].'1')
							->setCellValue($huruf[$hr1].'1', $bulan[$x].' '.$cek['tahun'])
							->setCellValue($huruf[$hr1].'2', 'PYD')
							->setCellValue($huruf[$hr2].'2', 'Pendapatan Denda');
							$hr1=$hr1+2;
							$hr2=$hr2+2;
					}
				}else{
					$hr1=4;
					$hr2=5;
					for ($x=6; $x <12 ; $x++) { 
						$objPHPExcel->getActiveSheet(0)
							->getColumnDimension($huruf[$hr1])
							->setAutoSize(true);
						$objPHPExcel->getActiveSheet(0)
							->getColumnDimension($huruf[$hr2])
							->setAutoSize(true);
						$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells($huruf[$hr1].'1:'.$huruf[$hr2].'1')
							->setCellValue($huruf[$hr1].'1', $bulan[$x].' '.$cek['tahun'])
							->setCellValue($huruf[$hr1].'2', 'PYD')
							->setCellValue($huruf[$hr2].'2', 'Pendapatan Denda');
							$hr1=$hr1+2;
							$hr2=$hr2+2;
					}
				}
				$br=3;
				$no=1;
				foreach ($dt as $d) {
				    $objPHPExcel->setActiveSheetIndex(0)
				    		->setCellValue('A'.$br, $no.".")
				            ->setCellValueExplicit('B'.$br, $d->kode_loker, PHPExcel_Cell_DataType::TYPE_STRING)
				            ->setCellValue('C'.$br, $d->nama);
				    $dd=4;  
				    $dd1=5;      
				    for ($j=1; $j <=6 ; $j++) { 
				    	$pp= 'pyd'.$j;
				    	$pp2= 'pd'.$j;
					    	$objPHPExcel->setActiveSheetIndex(0)
					    		->setCellValue($huruf[$dd].$br, $d->$pp)
					    		->setCellValue($huruf[$dd1].$br, $d->$pp2); 
					    $dd=$dd+2;
					    $dd1=$dd1+2;
				    }
				     
				    $br++;  
				    $no++;	
				}  
				$objPHPExcel->getActiveSheet()->setTitle('Data Denda');
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$cek['nama_denda'].'.xls"');
				header('Cache-Control: max-age=0');
				// If you're serving to IE 9, then the following may be needed
				header('Cache-Control: max-age=1');
				// If you're serving to IE over SSL, then the following may be needed
				header ('Expires: '.date('D, d M Y H:i:s',strtotime($this->date)).' GMT'); // Date in the past
				header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
				header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
				header ('Pragma: public'); // HTTP/1.0
				$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save('php://output');
				exit;
	        }else{
	            redirect('pages/denda');
	        }
		}
		$this->messages->allGood();
		redirect('pages/view_denda/'.$kode); 
	}
	function import(){
		$kode=$this->input->post('kode');
		$cek=$this->model_master->cek_denda($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/denda');
		}else{ 
			$tb=$this->input->post('tabel');
			$fileName = $this->input->post('file', TRUE);

			$config['upload_path'] = './asset/upload-exel/'; 
			$config['file_name'] = $fileName;
			$config['max_size'] = 1000;
			$config['allowed_types'] = 'xls|xlsx|csv|ods|ots';

			$this->load->library('upload', $config);
			$this->upload->initialize($config); 
			
			if (!$this->upload->do_upload('file')) {
				$this->messages->customFailure($this->upload->display_errors());
				redirect('pages/view_denda/'.$kode); 
			} else {
				$media = $this->upload->data();
				$inputFileName = './asset/upload-exel/'.$media['file_name'];
				
				try {
					$inputFileType = IOFactory::identify($inputFileName);
					$objReader = IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
				} catch(Exception $e) {
					die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				}

				$sheet = $objPHPExcel->getSheet(0);
				$highestRow = ($sheet->getHighestRow());
				$highestColumn = $sheet->getHighestColumn();
				//echo $highestColumn;
				$tri=0;
				for ($chrf='A'; $chrf!="AA"; $chrf++){
				 	$huruf[$tri]=$chrf;
				 	$tri++;
				}
				$rr=range('D', $highestColumn);
				$rr1=range('D', $highestColumn);
				$tt=0;
				foreach ($rr as $r) {
					if ($tt < count($rr1)) {
						$ind[$rr1[$tt]]=$sheet->getCell($rr1[$tt].'1')->getValue();
						$tt=$tt+2;
					}
				}
				$mxcl=array_search($highestColumn, $huruf);
				for ($row = 3; $row <= $highestRow; $row++){  
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
						NULL,
						TRUE,
						FALSE);
					$klok=$rowData[0][1];
					$d=1;
					foreach ($ind as $key => $val){
						$hr=array_search($key, $huruf); 
						$pyd=$sheet->getCell($huruf[$hr].$row)->getValue();
						$pd=$sheet->getCell($huruf[$hr+1].$row)->getValue();
						$tgt=0.001*$pyd;
						if ($tgt != 0) {
							$pa1=($pd/$tgt)*100;
							if ($pa1 <= 0) {
								$pa=0;
							}elseif ($pa1 >= 125) {
								$pa=125;
							}else{
								$pa=$pa1;
							}
						}else{
							$pa=0;
						}
						$pydk[$klok][]=$pyd;
						$pdk[$klok][]=$pd;
						$tgtk[$klok][]=$tgt;
						$pak[$klok][]=$pa;
						$pydk1[$d][$klok]=$pyd;
						$pdk1[$d][$klok]=$pd;
						$tgtk1[$d][$klok]=$tgt;
						$pak1[$d][$klok]=$pa;
						$data=array(
							'pyd'.$d=>$pyd,
							'pd'.$d=>$pd,
							'tgt'.$d=>$tgt,
							'pa'.$d=>$pa,
						);
						//sql
						$this->db->where('kode_loker',$klok);
						$this->db->update($tb,$data);
						$d++;
					}
				}
				foreach ($pydk as $k => $v) {
					$rpydk[$k]=array_sum($pydk[$k])/count($pydk[$k]);
					$rpdk[$k]=array_sum($pdk[$k])/count($pdk[$k]);
					$rtgtk[$k]=array_sum($tgtk[$k])/count($tgtk[$k]);
					$rpak[$k]=array_sum($pak[$k])/count($pak[$k]);
					if ($rtgtk[$k] != 0) {
						$rpak1[$k]=($rpdk[$k]/$rtgtk[$k])*100;
						if ($rpak1[$k] <= 0) {
							$rpak[$k]=0;
						}elseif ($rpak1[$k] >= 125) {
							$rpak[$k]=125;
						}else{
							$rpak[$k]=$rpak1[$k];
						}
					}else{
						$rpak[$k]=0;
					}
					$data1[$k]=array(
						'ratapyd'=>$rpydk[$k],
						'ratapd'=>$rpdk[$k],
						'ratatgt'=>$rtgtk[$k],
						'ratapa'=>$rpak[$k],
					);

					//sql
					$this->db->where('kode_loker',$k);
					$this->db->update($tb,$data1[$k]);
				}
				
				foreach ($pydk1 as $kx => $vx) {
					$ppyd=array_sum($vx);
					$pppd=array_sum($pdk1[$kx]);
					$pptgt=array_sum($tgtk1[$kx]);
					$pppa=array_sum($pak1[$kx]);
					if ($pptgt != 0) {
						$pppa1=($pppd/$pptgt)*100;
						if ($pppa1 <= 0) {
							$pppa=0;
						}elseif ($pppa1 >= 125) {
							$pppa=125;
						}else{
							$pppa=$pppa1;
						}
					}else{
						$pppa=0;
					}
					$pydkx[$kx]=$ppyd;
					$pdkx[$kx]=$pppd;
					$tgtkx[$kx]=$pptgt;
					$pakx[$kx]=$pppa;
					$datax=array(
						'pyd'.$kx=>$ppyd,
						'pd'.$kx=>$pppd,
						'tgt'.$kx=>$pptgt,
						'pa'.$kx=>$pppa,
					);
					//sql
					$this->db->where('id_denda',1);
					$this->db->update($tb,$datax);
					
				}
				
				$pyx=array_sum($pydkx)/count($pydkx);
				$pdx=array_sum($pdkx)/count($pdkx);
				$ptx=array_sum($tgtkx)/count($tgtkx);
				$pax=array_sum($pakx)/count($pakx);
				if ($ptx != 0) {
					$pax1=($pdx/$ptx)*100;
					if ($pax1 <= 0) {
						$pax=0;
					}elseif ($pax1 >= 125) {
						$pax=125;
					}else{
						$pax=$pax1;
					}
				}else{
					$pax1=0;
				}
				$dataxx1=array(
					'ratapyd'=>$pyx,
					'ratapd'=>$pdx,
					'ratatgt'=>$ptx,
					'ratapa'=>$pax,
				);
				//sql	
				$this->db->where('id_denda',1);
				$this->db->update($tb,$dataxx1);

				if ($cek['kait'] == 1) {
					$indi=explode(',', $cek['kode_indikator']);
					$agd=$this->db->get_where('agenda',array('kode_agenda'=>$cek['kode_agenda']))->row_array();
					foreach ($indi as $i) {
						$dnn=$this->model_master->tb_denda($tb);
						foreach ($dnn as $dn) {
							$nmlok=$dn->nama;
							$dtab=$this->db->get_where($agd['tabel_agenda'],array('kode_indikator'=>$i))->result();
							foreach ($dtab as $dd) {
								$ky=$this->model_karyawan->emp($dd->id_karyawan);
								$jbt=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$ky['jabatan']))->row_array();
								$loker=$this->model_master->k_loker($ky['unit']);
								if ($dd->konsolidasi == 0) {
									if ($dn->id_denda != 1) {
										if ($loker['nama'] == $nmlok) {
											$bb2=$dd->bobot/100;
											if ($jbt['kode_periode'] == 'SMT') {
												$dta=array(
													'nra6'=>$dn->ratapa,
													'na6'=>$dn->ratapa*$bb2,
													'nilai_out'=>$dn->ratapa*$bb2,
												);
											}else{
												$dta=array(
													'nra1'=>$dn->pa1,
													'na1'=>$dn->pa1*$bb2,
													'nra2'=>$dn->pa2,
													'na2'=>$dn->pa2*$bb2,
													'nra3'=>$dn->pa3,
													'na3'=>$dn->pa3*$bb2,
													'nra4'=>$dn->pa4,
													'na4'=>$dn->pa4*$bb2,
													'nra5'=>$dn->pa5,
													'na5'=>$dn->pa5*$bb2,
													'nra6'=>$dn->pa6,
													'na6'=>$dn->pa6*$bb2,
													'nilai_out'=>$dn->ratapa*$bb2,
												);
											}
											$wh=array('kode_indikator'=>$i,'id_karyawan'=>$dd->id_karyawan);
											$this->db->where($wh);
											$this->db->update($agd['tabel_agenda'],$dta);
										}
									}
								}else{
									if ($dn->id_denda == 1) {
										$bb2=$dd->bobot/100;
										if ($jbt['kode_periode'] == 'SMT') {
											$dta=array(
												'nra6'=>$dn->ratapa,
												'na6'=>$dn->ratapa*$bb2,
												'nilai_out'=>$dn->ratapa*$bb2,
											);
										}else{
											$dta=array(
												'nra1'=>$dn->pa1,
												'na1'=>$dn->pa1*$bb2,
												'nra2'=>$dn->pa2,
												'na2'=>$dn->pa2*$bb2,
												'nra3'=>$dn->pa3,
												'na3'=>$dn->pa3*$bb2,
												'nra4'=>$dn->pa4,
												'na4'=>$dn->pa4*$bb2,
												'nra5'=>$dn->pa5,
												'na5'=>$dn->pa5*$bb2,
												'nra6'=>$dn->pa6,
												'na6'=>$dn->pa6*$bb2,
												'nilai_out'=>$dn->ratapa*$bb2,
											);
										}
										$wh=array('kode_indikator'=>$i,'id_karyawan'=>$dd->id_karyawan,'konsolidasi'=>'1');
										$this->db->where($wh);
										$this->db->update($agd['tabel_agenda'],$dta);
									}
								}
							}
						}
					}
				}
				//
				$datt=array('edit'=>'0');
				$this->db->where('kode_denda',$kode);
				$in=$this->db->update('dp_denda',$datt);
				if ($in) {
					$this->messages->allGood();	
				}else{
					$this->messages->allFailure();
				}
				redirect('pages/view_denda/'.$kode);			
			}
		}
	}			
}