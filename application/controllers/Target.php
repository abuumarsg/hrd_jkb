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

class Target extends CI_Controller
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
	function del_target(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$dt=$this->db->get_where('target_corporate',array('id_target'=>$kode))->row_array();
			$tb=$dt['nama_tabel'];
			$this->dbforge->drop_table($tb,TRUE);

			$this->db->where('id_target',$kode);
			$in=$this->db->delete('target_corporate');
			if ($in) {
				$this->messages->delGood(); 
			}else{
				$this->messages->delFailure(); 
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/target_corporate');
	}
	function add_value(){
		$tb=$this->input->post('tabel');
		$kode=$this->input->post('kode');
		$cek=$this->model_master->cek_target($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/view_target_corporate/'.$kode);
		}else{
			$agg=$this->input->post('agg');
			$os1=$this->input->post('os1');
			$os2=$this->input->post('os2');
			$smt=$this->input->post('smt');
			$agd=$this->model_agenda->cek_agd($cek['kode_agenda']);
			$jm=count($agg);
			if ($smt == "1") {
				foreach ($agg as $k => $v) {
					$tg=$v-$os1[$k];
					if ($tg <= 0) {
						$tg = 0;
					}
					$g=$os2[$k]-$os1[$k];
					if ($g <= 0) {
						$g = 0;
					}
					if ($tg == 0) {
						$capai = 0;
					}else{
						$capai=($g/$tg)*100;
					}
					if ($capai <= 0) {
						$capai = 0;
					}
					$n=(0.1*$capai);
					if ($n <= 0) {
						$n = 0;
					}
					$totagg[]=$v;
					$totos1[]=$os1[$k];
					$totos2[]=$os2[$k];
					$data=array(
						'anggaran_juni'=>$v,
						'os_desember'=>$os1[$k],
						'target_growth'=>$tg,
						'os_juni'=>$os2[$k],
						'growth'=>$g,
						'pencapaian'=>number_format($capai,2),
						'nilai'=>number_format($n,2),
					);
					$this->db->where('id_target',$k);
					$this->db->update($tb,$data);
				}
				$toagg=array_sum($totagg);
				$toos1=array_sum($totos1);
				$toos2=array_sum($totos2);
				$totg=$toagg-$toos1;
				if ($totg <= 0) {
					$totg = 0;
				}
				$tog=$toos2-$toos1;
				if ($tog <= 0) {
					$tog = 0;
				}
				if ($totg == 0) {
					$tocapai = 0;
				}else{
					$tocapai=($tog/$totg)*100;
				}
				if ($tocapai <= 0) {
					$tocapai = 0;
				}
				$ton=(0.1*$tocapai);
				if ($ton <= 0) {
					$ton = 0;
				}
				$data1=array(
					'anggaran_juni'=>$toagg,
					'os_desember'=>$toos1,
					'target_growth'=>$totg,
					'os_juni'=>$toos2,
					'growth'=>$tog,
					'pencapaian'=>number_format($tocapai,2),
					'nilai'=>number_format($ton,2),
				);
				$this->db->where('id_target',1);
				$this->db->update($tb,$data1);
			}else{
				foreach ($agg as $k => $v) {
					$tg=$v-$os1[$k];
					if ($tg <= 0) {
						$tg = 0;
					}
					$g=$os2[$k]-$os1[$k];
					if ($g <= 0) {
						$g = 0;
					}
					if ($tg == 0) {
						$capai = 0;
					}else{
						$capai=($g/$tg)*100;
					}
					if ($capai <= 0) {
						$capai = 0;
					}
					$n=(0.1*$capai);
					if ($n <= 0) {
						$n = 0;
					}
					$totagg[]=$v;
					$totos1[]=$os1[$k];
					$totos2[]=$os2[$k];
					$data=array(
						'anggaran_desember'=>$v,
						'os_juni'=>$os1[$k],
						'target_growth'=>$tg,
						'os_desember'=>$os2[$k],
						'growth'=>$g,
						'pencapaian'=>number_format($capai,2),
						'nilai'=>number_format($n,2),
					);
					$this->db->where('id_target',$k);
					$this->db->update($tb,$data);
				}
				$toagg=array_sum($totagg);
				$toos1=array_sum($totos1);
				$toos2=array_sum($totos2);
				$totg=$toagg-$toos1;
				if ($totg <= 0) {
					$totg = 0;
				}
				$tog=$toos2-$toos1;
				if ($tog <= 0) {
					$tog = 0;
				}
				if ($totg == 0) {
					$tocapai = 0;
				}else{
					$tocapai=($tog/$totg)*100;
				}
				if ($tocapai <= 0) {
					$tocapai = 0;
				}
				$ton=(0.1*$tocapai);
				if ($ton <= 0) {
					$ton = 0;
				}
				$data1=array(
					'anggaran_desember'=>$toagg,
					'os_juni'=>$toos1,
					'target_growth'=>$totg,
					'os_desember'=>$toos2,
					'growth'=>$tog,
					'pencapaian'=>number_format($tocapai,2),
					'nilai'=>number_format($ton,2),
				);
				$this->db->where('id_target',1);
				$this->db->update($tb,$data1);
			}
			if ($cek['kait'] == '1') {
				$tagd=$this->db->get($agd['tabel_agenda'])->result();
				$ttc=$this->db->get($tb)->result();
				foreach ($tagd as $t) {
					$idk[$t->id_karyawan]=$t->id_karyawan;
				}
				foreach ($idk as $ii) {
					$dt1=$this->db->get_where($agd['tabel_agenda'],array('id_karyawan'=>$ii))->result();
					foreach ($dt1 as $dx) {
						$id_lk=array('id_loker'=>$dx->id_loker,'id_loker_pa'=>$dx->id_loker_pa,'kons'=>$dx->konsolidasi);
					}
					if ($id_lk['kons'] == 0) {
						if ($id_lk['id_loker_pa'] == NULL || $id_lk['id_loker_pa'] == "") {
							$dtlk=$this->model_master->loker($id_lk['id_loker']);
						}else{
							$dtlk=$this->model_master->loker($id_lk['id_loker_pa']);
						}
						$unit=$dtlk['kode_loker'];
						$tbg=$this->db->get_where($tb,array('kode_loker'=>$unit))->row_array();
						if ($tbg['pencapaian'] > 100) {
							$ni=100;
						}elseif ($tbg['pencapaian'] < 0) {
							$ni=0;
						}else{
							$ni=$tbg['pencapaian'];
						} 
						$data=array('nilai_tc'=>$ni);
					}else{
						$tbg=$this->db->get_where($tb,array('nama'=>'Konsolidasi'))->row_array();
						if ($tbg['pencapaian'] > 100) {
							$ni=100;
						}elseif ($tbg['pencapaian'] < 0) {
							$ni=0;
						}else{
							$ni=$tbg['pencapaian'];
						}
						$data=array('nilai_tc'=>$ni);
					}
					$this->db->where('id_karyawan',$ii);
					$this->db->update($agd['tabel_agenda'],$data);
				}
			}
			$dtta=array(
				'update_date'=>$this->date,
				'edit'=>'0',
			);
			$this->db->where('kode_target',$kode);
			$in=$this->db->update('target_corporate',$dtta);
			if ($in) {
				$this->messages->allGood(); 	
			}else{
				$this->messages->allFailure(); 
			}
			redirect('pages/view_target_corporate/'.$kode);
		}
	}
	function lock_target(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$data=array(
				'edit'=>$this->input->post('lock'),
			);
			$this->db->where('id_target',$kode);
			$in=$this->db->update('target_corporate',$data);
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
		redirect('pages/target_corporate');
	}
	function chain_target(){
		$kag=$this->input->post('kode');
		if ($kag == NULL) {
			$this->messages->notValidParam();  
			redirect('pages/agenda');
		}else{
			$agenda=$this->db->get_where('agenda',array('kode_agenda'=>$kag))->row_array();
			$target=$this->db->get_where('target_corporate',array('kode_agenda'=>$kag))->row_array();
			$tbtg=$target['nama_tabel'];
			$tb=$agenda['tabel_agenda'];
			$dt=$this->db->get($tb)->result();

			foreach ($dt as $d) {
				$dtk[$d->id_karyawan]=$d->id_karyawan;
			}
			foreach ($dtk as $dk) {
				$dt1=$this->db->get_where($tb,array('id_karyawan'=>$dk))->result();
				foreach ($dt1 as $dx) {
					$id_lk=array('id_loker'=>$dx->id_loker,'id_loker_pa'=>$dx->id_loker_pa,'kons'=>$dx->konsolidasi);
				}
				if ($id_lk['kons'] == 0) {
					if ($id_lk['id_loker_pa'] == NULL || $id_lk['id_loker_pa'] == "") {
						$dtlk=$this->model_master->loker($id_lk['id_loker']);
					}else{
						$dtlk=$this->model_master->loker($id_lk['id_loker_pa']);
					}
					$unit=$dtlk['kode_loker'];
					$tbg=$this->db->get_where($tbtg,array('kode_loker'=>$unit))->row_array();
					if ($tbg['pencapaian'] > 100) {
						$ni=100;
					}elseif ($tbg['pencapaian'] < 0) {
						$ni=0;
					}else{
						$ni=$tbg['pencapaian'];
					} 
					$data=array('nilai_tc'=>$ni);
				}else{
					$tbg=$this->db->get_where($tbtg,array('nama'=>'Konsolidasi'))->row_array();
					if ($tbg['pencapaian'] > 100) {
						$ni=100;
					}elseif ($tbg['pencapaian'] < 0) {
						$ni=0;
					}else{
						$ni=$tbg['pencapaian'];
					}
					$data=array('nilai_tc'=>$ni);
				}
				$this->db->where('id_karyawan',$dk);
				$this->db->update($tb,$data);

			}
			$dtta=array('kait'=>'1');
			$this->db->where('kode_agenda',$kag);
			$in=$this->db->update('target_corporate',$dtta);
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
		$cek=$this->model_master->cek_target($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/target_corporate');
		}else{ 
			$tb=$this->input->post('tabel');
			$dt=$this->db->query("SELECT * FROM $tb WHERE id_target != '1'")->result();
	        if(count($dt)>0){
	            $objPHPExcel = new PHPExcel();
				// Set document properties
				$objPHPExcel->getProperties()->setCreator("Galeh Fatma Eko A")
											 ->setLastModifiedBy("Galeh Fatma Eko A")
											 ->setTitle($cek['nama_target'].' Semester'.$cek['semester'])
											 ->setSubject($cek['nama_target'])
											 ->setDescription($cek['nama_target'].' Semester'.$cek['semester'])
											 ->setKeywords($cek['nama_target'])
											 ->setCategory($cek['nama_target']);
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
		                        'color' => array('rgb' => 'f4fc00')
		                    ),
		                    'font' => array(
		                        'color' => array('rgb' => '000000')
		                    )
		                )
		            );	
				$objPHPExcel->getActiveSheet()->getStyle('D1:F1')->applyFromArray(
		                array(
		                    'fill' => array(
		                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                        'color' => array('rgb' => '002868')
		                    ),
		                    'font' => array(
		                        'color' => array('rgb' => 'FFFFFF')
		                    )
		                )
		            );
				for ($i=1; $i <=6 ; $i++) { 
					$objPHPExcel->getActiveSheet(0)
							->getColumnDimension($huruf[$i])
							->setAutoSize(true);
				}
		        			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', 'No.')
							->setCellValue('B1', 'Kode Lokasi')
							->setCellValue('C1', 'Nama Kantor');
				if ($cek['semester'] == '1') {
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('D1', 'Anggaran Juni '.$cek['tahun'])
							->setCellValue('E1', 'OS Desember '.($cek['tahun']-1))
							->setCellValue('F1', 'OS Juni '.$cek['tahun']);
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('D1', 'Anggaran Desember '.$cek['tahun'])
							->setCellValue('E1', 'OS Juni '.($cek['tahun']-1))
							->setCellValue('F1', 'OS Desember '.$cek['tahun']);
				}
				$br=2;
				$no=1;
				foreach ($dt as $d) {
					$lok=$this->db->get_where('master_loker',array('nama'=>$d->nama))->row_array();
				    $objPHPExcel->setActiveSheetIndex(0)
				    		->setCellValue('A'.$br, $no.".")
				            ->setCellValueExplicit('B'.$br, $lok['kode_loker'], PHPExcel_Cell_DataType::TYPE_STRING)
				            ->setCellValue('C'.$br, $d->nama);
				    if ($cek['semester'] == '1') {
				    	$objPHPExcel->setActiveSheetIndex(0)
				    		->setCellValue('D'.$br, $d->anggaran_juni)
				            ->setCellValue('E'.$br, $d->os_desember)   	
				            ->setCellValue('F'.$br, $d->os_juni); 
				    }else{
				    	$objPHPExcel->setActiveSheetIndex(0)
				    		->setCellValue('D'.$br, $d->anggaran_desember)
				            ->setCellValue('E'.$br, $d->os_juni)   	
				            ->setCellValue('F'.$br, $d->os_desember); 
				    }  
				    $br++;  
				    $no++;	
				}  
				$objPHPExcel->getActiveSheet()->setTitle('Data Target Corporate');
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$cek['nama_target'].'.xls"');
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
	            redirect('pages/anggaran');
	        }
		}
		$this->session->set_flashdata('msgsc','<label><i class="fa fa-check-circle"></i> Export Data Berhasil</label><hr class="message-inner-separator">Download data berhasil, silahkan cek pada folder download anda, jika tidak ada maka anda dapat mengulangi proses export kembali');
		redirect('pages/view_anggaran/'.$kp); 
	}
	function import(){
		$kode=$this->input->post('kode');
		$cek=$this->model_master->cek_target($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/target_corporate');
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
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('msgerr','<label><i class="fa fa-times-circle"></i> Import Data Gagal</label><hr class="message-inner-separator">'.$error['error']); 
				redirect('pages/view_anggaran/'.$kag); 
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
				for ($row = 2; $row <= $highestRow; $row++){  
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
						NULL,
						TRUE,
						FALSE);
					$ang=$rowData[0][3];
					$os1=$rowData[0][4];
					$os2=$rowData[0][5];
					if ($cek['semester'] == '1') {
						
						$tg=$ang-$os1;
						if ($tg <= 0) {
							$tg = 0;
						}
						$g=$os2-$os1;
						if ($g <= 0) {
							$g = 0;
						}
						if ($tg == 0) {
							$capai = 0;
						}else{
							$capai=($g/$tg)*100;
						}
						if ($capai <= 0) {
							$capai = 0;
						}
						$n=(0.1*$capai);
						if ($n <= 0) {
							$n = 0;
						}
						$totagg[]=$ang;
						$totos1[]=$os1;
						$totos2[]=$os2;
						$data=array(
							'anggaran_juni'=>$ang,
							'os_desember'=>$os1,
							'target_growth'=>$tg,
							'os_juni'=>$os2,
							'growth'=>$g,
							'pencapaian'=>number_format($capai,2),
							'nilai'=>number_format($n,2),
						);
						$this->db->where('kode_loker',$rowData[0][1]);
						$this->db->update($tb,$data);	
					}else{
						$tg=$ang-$os1;
						if ($tg <= 0) {
							$tg = 0;
						}
						$g=$os2-$os1;
						if ($g <= 0) {
							$g = 0;
						}
						if ($tg == 0) {
							$capai = 0;
						}else{
							$capai=($g/$tg)*100;
						}
						if ($capai <= 0) {
							$capai = 0;
						}
						$n=(0.1*$capai);
						if ($n <= 0) {
							$n = 0;
						}
						$totagg[]=$ang;
						$totos1[]=$os1;
						$totos2[]=$os2;
						$data=array(
							'anggaran_desember'=>$ang,
							'os_juni'=>$os1,
							'target_growth'=>$tg,
							'os_desember'=>$os2,
							'growth'=>$g,
							'pencapaian'=>number_format($capai,2),
							'nilai'=>number_format($n,2),
						);
						echo '<pre>';
						print_r($data);
						echo '</pre>';
						$this->db->where('kode_loker',$rowData[0][1]);
						$this->db->update($tb,$data);
					}	
				}
				$toagg=array_sum($totagg);
				$toos1=array_sum($totos1);
				$toos2=array_sum($totos2);
				$totg=$toagg-$toos1;
				if ($totg <= 0) {
					$totg = 0;
				}
				$tog=$toos2-$toos1;
				if ($tog <= 0) {
					$tog = 0;
				}
				if ($totg == 0) {
					$tocapai = 0;
				}else{
					$tocapai=($tog/$totg)*100;
				}
				if ($tocapai <= 0) {
					$tocapai = 0;
				}
				$ton=(0.1*$tocapai);
				if ($ton <= 0) {
					$ton = 0;
				}
				if ($cek['semester'] == '1') {
					$data1=array(
						'anggaran_juni'=>$toagg,
						'os_desember'=>$toos1,
						'target_growth'=>$totg,
						'os_juni'=>$toos2,
						'growth'=>$tog,
						'pencapaian'=>number_format($tocapai,2),
						'nilai'=>number_format($ton,2),
					);
				}else{
					$data1=array(
						'anggaran_desember'=>$toagg,
						'os_juni'=>$toos1,
						'target_growth'=>$totg,
						'os_desember'=>$toos2,
						'growth'=>$tog,
						'pencapaian'=>number_format($tocapai,2),
						'nilai'=>number_format($ton,2),
					);
				}
				$this->db->where('id_target',1);
				$this->db->update($tb,$data1);
				if ($cek['kait'] == '1') {
					$agd=$this->model_agenda->cek_agd($cek['kode_agenda']);
					$tagd=$this->db->get($agd['tabel_agenda'])->result();
					$ttc=$this->db->get($tb)->result();
					foreach ($tagd as $t) {
						$idk[$t->id_karyawan]=$t->id_karyawan;
					}
					foreach ($idk as $ii) {
						$dt1=$this->db->get_where($agd['tabel_agenda'],array('id_karyawan'=>$ii,'konsolidasi'=>'1'))->result();
						if (count($dt1) == 0) {
							$datak=$this->model_karyawan->emp($ii);
							$unit1=$datak['unit'];
							$dtlk=$this->model_master->k_loker($unit1);
							$unit=$dtlk['nama'];
							$tbg=$this->db->get_where($tb,array('nama'=>$unit))->row_array();
							if ($tbg['pencapaian'] > 100) {
								$ni=100;
							}elseif ($tbg['pencapaian'] < 0) {
								$ni=0;
							}else{
								$ni=$tbg['pencapaian'];
							}
							$data=array('nilai_tc'=>$ni);
						}else{
							$tbg=$this->db->get_where($tb,array('nama'=>'Konsolidasi'))->row_array();
							if ($tbg['pencapaian'] > 100) {
								$ni=100;
							}elseif ($tbg['pencapaian'] < 0) {
								$ni=0;
							}else{
								$ni=$tbg['pencapaian'];
							}
							$data=array('nilai_tc'=>$ni);
						}
						$this->db->where('id_karyawan',$ii);
						$this->db->update($agd['tabel_agenda'],$data);
					}
				}
				$dtta=array(
					'update_date'=>$this->date,
					'edit'=>'0',
				);
				$this->db->where('kode_target',$kode);
				$in=$this->db->update('target_corporate',$dtta);
				if ($in) {
					$this->messages->allGood(); 	
				}else{
					$this->messages->allFailure(); 
				}
				redirect('pages/view_target_corporate/'.$kode);
				 
			}
		}
	}
}