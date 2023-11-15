<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	/**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Presensi Controller
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

class Presensi extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7)); 
		if (isset($_SESSION['adm'])) {
			$this->admin = $_SESSION['adm']['id'];	
		}else{
			// redirect('auth');
			$this->admin = $_SESSION['emp']['id'];	
		}
		$ha = '0123456789';
	    $panjang = strlen($ha);
	    $rand = '';
	    for ($i = 0; $i < 6; $i++) {
	        $rand .= $ha[rand(0, $panjang - 1)];
	    }
	    $this->rando = $rand;
		// $dtroot['admin']=$this->model_admin->adm($this->admin);
		$dtroot['admin']=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
		$nm=explode(" ", $dtroot['admin']['nama']);
		$datax['adm'] = array(
				'nama'=>$nm[0],
				'email'=>$dtroot['admin']['email'],
				'kelamin'=>$dtroot['admin']['kelamin'],
				'foto'=>$dtroot['admin']['foto'],
				'create'=>$dtroot['admin']['create_date'],
				'update'=>$dtroot['admin']['update_date'],
				'login'=>$dtroot['admin']['last_login'],
				'kode_bagian'=>$dtroot['admin']['kode_bagian'],
				'list_bagian'=>$dtroot['admin']['list_filter_bagian'],
			);
		$this->dtroot=$datax;
		$this->load->dbforge();
	}
	public function index(){
		redirect('pages/dashboard');
	}
	public function tes_email()
	{	
		$list=array('abuumarsg@gmail.com','ahmadumar559@gmail.com');
		$this->load->library('email');
		$this->email->initialize($this->otherfunctions->configEmail());
		$this->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Your Name');
		$this->email->to($list);
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');
		$this->email->send();
		print_r($this->email->print_debugger());
	}
	// function del_presensi(){
	// 	$kode=$this->input->post('id');
	// 	if ($kode != "") {
	// 		$dt=$this->db->get_where('dp_presensi',array('id_presensi'=>$kode))->row_array();
	// 		$tb=$dt['nama_tabel'];
	// 		$this->dbforge->drop_table($tb,TRUE);

	// 		$this->db->where('id_presensi',$kode);
	// 		$in=$this->db->delete('dp_presensi');
	// 		if ($in) {
	// 			$this->messages->delGood();
	// 		}else{
	// 			$this->messages->delFailure(); 
	// 		}
	// 	}else{
	// 		$this->messages->notValidParam(); 
	// 	}
	// 	redirect('pages/presensi');
	// }
	// function edt_value_presensi(){
	// 	$kode=$this->input->post('kode');
	// 	$cek=$this->model_master->cek_presensi($kode);
	// 	if ($kode == "" || $cek == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('pages/presensi');
	// 	}else{
	// 		$idk=$this->input->post('id');
	// 		$tb=$cek['nama_tabel'];
	// 		$agd=$this->model_agenda->cek_agd($cek['kode_agenda']);
	// 		$tbagd=$agd['tabel_agenda'];
	// 		$indx=$this->db->get_where($tbagd,array('kode_indikator'=>$cek['kode_indikator']))->result();
	// 		foreach ($indx as $ix) {
	// 			$inn[$ix->rumus]=$ix->rumus;
	// 		}
	// 		$ind['rumus']=implode('', $inn);
	// 		if ($ind['rumus'] == NULL) {
	// 			$tr=0;
	// 			$tm=0;
	// 		}else{
	// 			$rm=explode(";", $ind['rumus']);
	// 			$tr=$rm[0];
	// 			$tm=$rm[1];
	// 		}
	// 		$dtr=$this->input->post('telat');
	// 		$dtm=$this->input->post('absen');
	// 		$na=100-(($dtr*$tr)+($dtm*$tm));
	// 		$data=array(
	// 			'n1'=>$dtr,
	// 			'n2'=>$dtm,
	// 			'na'=>$na,
	// 		);
	// 		if ($cek['kait'] == '1') {
	// 			$dind=$this->db->get_where($tbagd,array('kode_indikator'=>$cek['kode_indikator'],'id_karyawan'=>$idk))->row_array();
	// 			$data1=array(
	// 				'nra6'=>$na,
	// 				'na6'=>$na*($dind['bobot']/100),
	// 				'nilai_out'=>$na*($dind['bobot']/100),
	// 			);
	// 			$wh=array('id_karyawan'=>$idk,'kode_indikator'=>$cek['kode_indikator']);
	// 			$this->db->where($wh);
	// 			$this->db->update($tbagd,$data1);
	// 		}
	// 		$this->db->where('id_karyawan',$idk);
	// 		$in=$this->db->update($tb,$data);
	// 		if ($in) {
	// 			$this->messages->allGood(); 
	// 		}else{
	// 			$this->messages->allFailure(); 
	// 		}
	//    		redirect('pages/view_presensi/'.$kode);
	// 	}
	// } 
	// function lock_presensi(){
	// 	$kode=$this->input->post('id');
	// 	if ($kode != "") {
	// 		$data=array(
	// 			'edit'=>$this->input->post('lock'),
	// 		);
	// 		$this->db->where('id_presensi',$kode);
	// 		$in=$this->db->update('dp_presensi',$data);
	// 		if ($in) {
	// 			if ($this->input->post('lock') == 0) {
	// 				$this->messages->customGood('Data Terkunci');  
	// 			}else{
	// 				$this->messages->customGood('Data Terbuka'); 

	// 			}
	// 		}else{
	// 			$this->messages->allFailure(); 
	// 		}
	//    		redirect('pages/presensi');
	// 	}else{
	// 		$this->messages->notValidParam();  
	// 		redirect('pages/presensi');
	// 	}
	// }
	// function chain_presensi(){
	// 	$kode=$this->input->post('kode');
	// 	$cek=$this->model_master->cek_presensi($kode);
	// 	if ($kode == "" || $cek == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('pages/agenda');
	// 	}else{
	// 		$ag=$this->model_agenda->cek_agd($cek['kode_agenda']);
	// 		$tbagd=$ag['tabel_agenda'];
	// 		$tbps=$cek['nama_tabel'];
	// 		$dt=$this->db->get($tbps)->result();
	// 		foreach ($dt as $d) {
	// 				$in1=$this->db->get_where($tbagd,array('id_karyawan'=>$d->id_karyawan,'kode_indikator'=>$d->kode_indikator))->row_array();
	// 				$data=array(
	// 					'nra6'=>$d->na,
	// 					'na6'=>$d->na*($in1['bobot']/100),
	// 					'nilai_out'=>$d->na*($in1['bobot']/100),
	// 				);
	// 				$wh=array('id_karyawan'=>$d->id_karyawan,'kode_indikator'=>$d->kode_indikator);
	// 				$this->db->where($wh);
	// 				$this->db->update($tbagd,$data);
				
	// 		}
	// 		$data1=array('kait'=>'1','edit'=>'0');
	// 		$this->db->where('kode_presensi',$kode);
	// 		$in=$this->db->update('dp_presensi',$data1);
	// 		if ($in) {
	// 			$this->messages->allGood(); 	
	// 		}else{
	// 			$this->messages->allFailure(); 
	// 		}
	// 		redirect('pages/agenda');
	// 	}
	// }
	// function export(){
	// 	$kagd=$this->input->post('kode_agd');
	// 	if ($kagd == "") {
	// 		$this->messages->notValidParam();  
	// 		redirect('pages/presensi');
	// 	}else{
	// 		$kp=$this->input->post('kode');
	// 		$pr=$this->model_master->cek_presensi($kp);
	// 		$tb=$this->input->post('tabel_agd');
	// 		$indi=$this->input->post('indi_presensi');
	// 		foreach ($indi as $i) {
	// 			$dtk=$this->model_master->tb_sel_p($tb,$i);
	// 			foreach ($dtk as $d) {
	// 				$idk[$d->id_karyawan]=$d->id_karyawan;
	// 			}
	// 			$kpz=$this->model_master->which_indikator($i);
	// 			$kpi[$i]=$kpz['kpi'];
	// 		}
	//         if(count($idk)>0){
	//             $objPHPExcel = new PHPExcel();
	// 			// Set document properties
	// 			$objPHPExcel->getProperties()->setCreator("Galeh Fatma Eko A")
	// 										 ->setLastModifiedBy("Galeh Fatma Eko A")
	// 										 ->setTitle($pr['nama_presensi'].' Semester'.$pr['semester'])
	// 										 ->setSubject($pr['nama_presensi'])
	// 										 ->setDescription($pr['nama_presensi'].' Semester'.$pr['semester'])
	// 										 ->setKeywords($pr['nama_presensi'])
	// 										 ->setCategory($pr['nama_presensi']);
	// 			// Add some data
	// 			$tri=1;
	// 			for ($chrf='A'; $chrf!="AAA"; $chrf++){
	// 			 	$huruf[$tri]=$chrf;
	// 			 	$tri++;
	// 			}
	// 			$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
	// 	                array(
	// 	                    'fill' => array(
	// 	                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	// 	                        'color' => array('rgb' => '002868')
	// 	                    ),
	// 	                    'font' => array(
	// 	                        'color' => array('rgb' => 'FFFFFF')
	// 	                    )
	// 	                )
	// 	            );						 							 
				
	            
	//             //$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth(10); 
	//             $ch=6;
	//             $ch1=7;
	//             foreach ($kpi as $x =>$val) {
	//             	$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch].'2')->applyFromArray(
	// 	                array(
	// 	                    'fill' => array(
	// 	                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	// 	                        'color' => array('rgb' => 'ff9900')
	// 	                    ),
	// 	                    'font' => array(
	// 	                        'color' => array('rgb' => 'FFFFFF')
	// 	                    )
	// 	                ) 
	// 	            );
	// 	            $objPHPExcel->getActiveSheet()->getStyle($huruf[$ch1].'2')->applyFromArray(
	// 	                array(
	// 	                    'fill' => array(
	// 	                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	// 	                        'color' => array('rgb' => 'ff0000')
	// 	                    ),
	// 	                    'font' => array(
	// 	                        'color' => array('rgb' => 'FFFFFF')
	// 	                    )
	// 	                )
	// 	            );
	// 	            $objPHPExcel->getActiveSheet()->getStyle($huruf[$ch].'1')->applyFromArray(
	// 	                array(
	// 	                    'fill' => array(
	// 	                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	// 	                        'color' => array('rgb' => 'ffdd00')
	// 	                    ),
	// 	                    'font' => array(
	// 	                        'color' => array('rgb' => '000000')
	// 	                    )
	// 	                )
	// 	            );
	// 	            $objPHPExcel->getActiveSheet()->getStyle($huruf[$ch1].'1')->applyFromArray(
	// 	                array(
	// 	                    'fill' => array(
	// 	                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	// 	                        'color' => array('rgb' => '00f2ff')
	// 	                    ),
	// 	                    'font' => array(
	// 	                        'color' => array('rgb' => '000000')
	// 	                    )
	// 	                )
	// 	            );
	// 	            $objPHPExcel->getActiveSheet(0)
	// 	            			->getColumnDimension($huruf[$ch])
 //        						->setAutoSize(true);
 //        			$objPHPExcel->getActiveSheet(0)
	// 	            			->getColumnDimension($huruf[$ch1])
 //        						->setAutoSize(true);
	//             	$objPHPExcel->setActiveSheetIndex(0)
	// 			            ->setCellValue($huruf[$ch].'1', $x)
	// 			            ->setCellValue($huruf[$ch1].'1', $val)
	// 			            ;
	//             	$ch = $ch+2;
	//             	$ch1= $ch1+2;
	//             }
	//             foreach (range('A', 'E') as $cool) {
	//             	$objPHPExcel->getActiveSheet(0)
	// 	            			->getColumnDimension($cool)
 //        						->setAutoSize(true);
	//             }
	// 			$objPHPExcel->setActiveSheetIndex(0)
	// 						->mergeCells('A1:A2')
	// 						->mergeCells('B1:B2')
	// 						->mergeCells('C1:C2')
	// 						->mergeCells('D1:D2')
	// 						->mergeCells('E1:E2')
	// 			            ->setCellValue('A1', 'No.')
	// 			            ->setCellValue('B1', 'NIK')
	// 			            ->setCellValue('C1', 'Nama')
	// 			            ->setCellValue('D1', 'Jabatan')
	// 			            ->setCellValue('E1', 'Kantor');
	// 			$cch=6;
	//             $cch1=7;
	//             foreach ($kpi as $val) {
	//             	$objPHPExcel->setActiveSheetIndex(0)
	//             			->setCellValue($huruf[$cch].'2', 'Terlambat')
	// 			            ->setCellValue($huruf[$cch1].'2', 'Tidak Masuk');
	//             	$cch = $cch+2;
	//             	$cch1= $cch1+2;
	//             }            
				            

	// 			$br=3;
	// 			$no=1;
	// 			foreach ($idk as $k) {
	// 				$kr=$this->model_karyawan->emp($k);
	// 				$jbt=$this->model_master->k_jabatan($kr['jabatan']);
	// 				$lok=$this->model_master->k_loker($kr['unit']);
	// 			    $objPHPExcel->setActiveSheetIndex(0)
	// 			    		->setCellValue('A'.$br, $no.'.')
	// 			            ->setCellValueExplicit('B'.$br, $kr['nik'], PHPExcel_Cell_DataType::TYPE_STRING)
	// 			            ->setCellValue('C'.$br, $kr['nama'])
	// 			            ->setCellValue('D'.$br, $jbt['jabatan'])
	// 			            ->setCellValue('E'.$br, $lok['nama']);
	// 			            $cch2=6;
	// 			            $cch21=7;
	// 			            foreach ($kpi as $val) {
	// 			            	$objPHPExcel->setActiveSheetIndex(0)
	// 						            ->setCellValue($huruf[$cch2].''.$br, 0)
	// 						            ->setCellValue($huruf[$cch21].''.$br, 0);;
	// 			            	$cch2 = $cch2+2;
	// 			            	$cch21= $cch21+2;
	// 			            } 
				            
	// 			            $br++;   
	// 			            $no++;    	
	// 			}            
				
	// 			// Rename worksheet
	// 			$objPHPExcel->getActiveSheet()->setTitle('Data Presensi');
	// 			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	// 			$objPHPExcel->setActiveSheetIndex(0);
	// 			// Redirect output to a client’s web browser (Excel5)
	// 			header('Content-Type: application/vnd.ms-excel');
	// 			header('Content-Disposition: attachment;filename="'.$pr['nama_presensi'].'.xls"');
	// 			header('Cache-Control: max-age=0');
	// 			// If you're serving to IE 9, then the following may be needed
	// 			header('Cache-Control: max-age=1');
	// 			// If you're serving to IE over SSL, then the following may be needed
	// 			header ('Expires: '.date('D, d M Y H:i:s',strtotime($this->date)).' GMT'); // Date in the past
	// 			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	// 			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	// 			header ('Pragma: public'); // HTTP/1.0
	// 			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
	// 			$objWriter->save('php://output');
	// 			exit;
	//         }else{
	//             redirect('pages/view_presensi/'.$kp);
	//         }
	// 	}
	// 	$this->messages->allGood();
	// 	redirect('pages/view_presensi'.$kp); 
	// }
	// function import(){
	// 	$kpre=$this->input->post('kode');
	// 	$pre=$this->model_master->cek_presensi($kpre);
	// 	if ($kpre == "" || $pre == "") {
	// 		redirect('pages/presensi');
	// 	}else{
	// 		$kagd=$this->input->post('kode_agd');
	// 		$tbagd=$this->input->post('tabel_agd');
	// 		$fileName = $this->input->post('file', TRUE);

	// 		$config['upload_path'] = './asset/upload-exel/'; 
	// 		$config['file_name'] = $fileName;
	// 		$config['max_size'] = 1000;
	// 		$config['allowed_types'] = 'xls|xlsx|csv|ods|ots';

	// 		$this->load->library('upload', $config);
	// 		$this->upload->initialize($config); 
			
	// 		if (!$this->upload->do_upload('file')) {
	// 			$this->messages->customFailure($this->upload->display_errors());
	// 			redirect('pages/view_presensi/'.$kpre); 
	// 		} else {
	// 			$media = $this->upload->data();
	// 			$inputFileName = './asset/upload-exel/'.$media['file_name'];
				
	// 			try {
	// 				$inputFileType = IOFactory::identify($inputFileName);
	// 				$objReader = IOFactory::createReader($inputFileType);
	// 				$objPHPExcel = $objReader->load($inputFileName);
	// 			} catch(Exception $e) {
	// 				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	// 			}

	// 			$sheet = $objPHPExcel->getSheet(0);
	// 			$highestRow = ($sheet->getHighestRow());
	// 			$highestColumn = $sheet->getHighestColumn();
	// 			//echo $highestColumn;
	// 			$rr=range('F', $highestColumn);
	// 			$rr1=range('F', $highestColumn);
	// 			$tt=0;
	// 			foreach ($rr as $r) {
	// 				if ($tt < count($rr1)) {
	// 					$ind[$rr1[$tt]]=$sheet->getCell($rr1[$tt].'1')->getValue();
	// 					$tt=$tt+2;
	// 				}
	// 			}

	// 			$tri=1;
	// 			for ($chrf='A'; $chrf!="AAAA"; $chrf++){
	// 			 	$huruf[$tri]=$chrf;
	// 			 	$tri++; 
	// 			}
	// 			for ($row = 3; $row <= $highestRow; $row++){  
	// 				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
	// 					NULL,
	// 					TRUE,
	// 					FALSE);
					
	// 				foreach ($ind as $kd => $indd) {
	// 					$hr=array_search($kd, $huruf);
	// 					$late=$sheet->getCell($huruf[$hr].$row)->getValue();
	// 					$abs=$sheet->getCell($huruf[$hr+1].$row)->getValue();
	// 					$nila[$indd]=array('late'=>$late,'abs'=>$abs);
	// 					$ky=$this->model_karyawan->emp_nik($rowData[0][1]);
	// 					$indx=$this->db->get_where($tbagd,array('kode_indikator'=>$indd,'id_karyawan'=>$ky['id_karyawan']))->row_array();
	// 					if (count($indx) > 0) {
	// 						$rumus=explode(';', $indx['rumus']);
	// 						if (isset($rumus)) {
	// 							$btrl=$rumus[0];
	// 							$babs=$rumus[1];
	// 						}else{
	// 							$btrl=0;
	// 							$babs=0;
	// 						}
	// 						$nilai[$row]=100-(($nila[$indd]['late']*$btrl)+($nila[$indd]['abs']*$babs));
	// 						if ($nilai[$row] <= 0) {
	// 							$nilai[$row] = 0;
	// 						}elseif ($nilai[$row] >= 100) {
	// 							$nilai[$row] = 100;
	// 						}else{
	// 							$nilai[$row] = $nilai[$row];
	// 						}
	// 						$data = array(
	// 							'id_karyawan'=>$ky['id_karyawan'],
	// 							'nik'=> $rowData[0][1],
	// 							'nama_karyawan'=> $rowData[0][2],
	// 							'kode_indikator'=>$indd,
	// 							'n1'=> $nila[$indd]['late'],
	// 							'n2'=> $nila[$indd]['abs'],
	// 							'na'=> $nilai[$row],

	// 						);
	// 						$this->db->insert($pre['nama_tabel'],$data);
	// 					}
	// 				}
					
	// 				// $indx=$this->db->get_where($tbagd,array('kode_indikator'=>$ind[0]))->result();
	// 				// foreach ($indx as $ix) {
	// 				// 	$inn[$ix->rumus]=$ix->rumus;
	// 				// }
	// 				// $indik['rumus']=implode('', $inn);
	// 				// if ($indik['rumus'] == NULL) {
	// 				// 	$babs=0;
	// 				// 	$btrl=0;
	// 				// }else{
	// 				// 	$rms=explode(';', $indik['rumus']);
	// 				// 	$btrl=$rms[0];
	// 				// 	$babs=$rms[1];
	// 				// }
	// 				// $nilai[$row]=100-(($rowData[0][5]*$btrl)+($rowData[0][6]*$babs));
	// 				// if ($nilai[$row] <= 0) {
	// 				// 	$nilai[$row] = 0;
	// 				// }elseif ($nilai[$row] >= 100) {
	// 				// 	$nilai[$row] = 100;
	// 				// }else{
	// 				// 	$nilai[$row] = $nilai[$row];
	// 				// }
	// 				// if ($rowData[0][1] != "") {
	// 				// 	$dtk=$this->model_karyawan->emp_nik($rowData[0][1]);
	// 				// 	$data = array(
	// 				// 		'id_karyawan'=>$dtk['id_karyawan'],
	// 				// 		'nik'=> $rowData[0][1],
	// 				// 		'nama_karyawan'=> $rowData[0][2],
	// 				// 		'kode_indikator'=>implode(',', $ind),
	// 				// 		'n1'=> $rowData[0][5],
	// 				// 		'n2'=> $rowData[0][6],
	// 				// 		'na'=> $nilai[$row],

	// 				// 	);
	// 				// 	//$this->db->insert($pre['nama_tabel'],$data);
	// 				// }
	// 			}
	// 			$data1=array('kode_indikator'=>implode(',', $ind));
	// 			$this->db->where('kode_presensi',$kpre);
	// 			$in=$this->db->update('dp_presensi',$data1);
	// 			if ($in) {
	// 				$this->messages->allGood();
	// 			}else{
	// 				$this->messages->allFailure();
	// 			}
	// 			redirect('pages/view_presensi/'.$kpre);
	// 		}
	// 	}
	// }
//			    ____  ____    __  __                ||
//			   / _  |/ _  \  / / / /                ||
//=======	  / /_/ / /_) / / / / /      ===========||
//			 / __  / /__) \/ /_/ /                  ||
//			/_/ /_/_______/\____/                   ||

//==================================== ABSENSI ==============================================
//IZIN & CUTI
	public function pilih_k_izin()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getPilihKaryawanMutasi();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_karyawan,
				'<a class="pilih" style="cursor:pointer" 
				data-nik			="'.$d->nik.'" 
				data-id_karyawan	="'.$d->id_karyawan.'" 
				data-nama			="'.$d->nama.'" 
				data-jabatan		="'.$d->jabatan.'" 
				data-nama_jabatan	="'.$d->nama_jabatan.'" 
				data-kode_lokasi	="'.$d->loker.'" 
				data-nama_lokasi	="'.$d->nama_loker.'">'.
				$d->nik.'</a>',
				$d->nama,
				$d->nama_jabatan,
				$d->nama_loker,
			];
		}
		echo json_encode($datax);		
	}
	public function pilih_k_cuti()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$data=$this->model_karyawan->getPilihKaryawanMutasi();
		$datax['data']=[];
		foreach ($data as $d) {
			$datax['data'][]=[
				$d->id_karyawan,
				'<a class="pilih_cuti" style="cursor:pointer" 
				data-nik_cuti			="'.$d->nik.'" 
				data-id_karyawan_cuti	="'.$d->id_karyawan.'" 
				data-nama_cuti			="'.$d->nama.'" 
				data-jabatan_cuti		="'.$d->jabatan.'" 
				data-nama_jabatan_cuti	="'.$d->nama_jabatan.'" 
				data-kode_lokasi_cuti	="'.$d->loker.'" 
				data-nama_lokasi_cuti	="'.$d->nama_loker.'">'.
				$d->nik.'</a>',
				$d->nama,
				$d->nama_jabatan,
				$d->nama_loker,
			];
		}
		echo json_encode($datax);		
	}
	public function izin_cuti_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				parse_str($this->input->post('form'), $form);
				$param=$this->input->post('kode');
				$bagian=(!empty($form['bagian_harian'])) ? $form['bagian_harian']: null;	
				$unit=(!empty($form['unit_harian'])) ? $form['unit_harian'] : null;
				$status_validasi=(!empty($form['status_validasi']) && isset($form['status_validasi'])) ? $form['status_validasi'] : null;
				$jenis_cuti=(!empty($form['jenis_cuti']) && isset($form['jenis_cuti'])) ? $form['jenis_cuti'] : null;
				$tanggalx=(!empty($form['tanggal'])) ? $form['tanggal'] : null;
				if($param == 'all'){
					$bulantahun = ['bulan'=>date('m'),'tahun'=>date('Y')];
					$data=$this->model_karyawan->getIzinCutiHarianNew(null,null,null,$bulantahun);
				}else{
					$bagianx=(!empty($bagian)) ? ["jbt.kode_bagian" => $bagian] : [];	
					$unitx=(!empty($unit)) ? ["h.loker" => $unit] : [];
					$status_validasix=(!empty($status_validasi) && isset($status_validasi)) ? ["a.validasi" => $status_validasi] : [];
					$jenis_cutix=(!empty($jenis_cuti) && isset($jenis_cuti)) ? ["a.jenis" => $jenis_cuti] : [];
					$where = array_merge($bagianx,$unitx,$status_validasix,$jenis_cutix);
					$data=$this->model_karyawan->getIzinCutiHarianNew($where,$bagian,$tanggalx);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_izin_cuti,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis);
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$properties['aksi']=str_replace('view_modal', 'view_modal_harian', $properties['aksi']);
					$properties['aksi']=str_replace('delete_modal', 'delete_modal_harian', $properties['aksi']);
					if (isset($access['l_ac']['val_izin'])) {
						if(in_array($access['l_ac']['val_izin'], $access['access'])){
							if($d->validasi==2){
								$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need('.$d->id_izin_cuti.')><i class="fa fa-warning"></i> Perlu Validasi</button> ';
							}elseif($d->validasi==1){
								$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes('.$d->id_izin_cuti.')><i class="fa fa-check-circle"></i> Diizinkan</button> ';
							}elseif($d->validasi==0){
								$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no('.$d->id_izin_cuti.')><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
							}
						}else{
							$validasi = $this->otherfunctions->getStatusIzin($d->validasi);
						}
					}else{
						$validasi = '<label class="label label-danger"> Tidak Punya Akses</label>';
					}
					$datax['data'][]=[
						$d->id_izin_cuti,
						$d->kode_izin_cuti,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						$d->nama_jenis_izin,
						$validasi,
						$properties['aksi'].$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_izin_cuti');
				$data=$this->model_karyawan->getIzinCuti($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_menyetujui_2=($d->jbt_menyetujui_2 != null) ? ' - '.$d->jbt_menyetujui_2 : null;
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis_ic);
					$mulai = $this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->timeFormatUser($d->jam_mulai).' WIB';
					$selesai = $this->formatter->getDateMonthFormatUser($d->tgl_selesai).' - '.$this->formatter->timeFormatUser($d->jam_selesai).' WIB';
					if($d->skd_dibayar == 1){
						$skd = '<span class="text-success">SKD Dibayar</span>';
					}else{
						$skd = '<span class="text-danger">SKD Tidak Dibayar</span>';
					}
					$datax=[
						'id'=>$d->id_izin_cuti,
						'id_karyawan'=>$d->id_karyawan,
						'nomor'=>$d->kode_izin_cuti,
						'tanggal_mulai'=>$mulai,
						'tanggal_selesai'=>$selesai,
						'tgl_mulai_val'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jenis_cuti'=>$d->nama_jenis_izin.' ('.$nama_jenis.')',
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'emenyetujui2'=>$d->menyetujui_2,
						'alasan'=>(!empty($d->alasan)) ? $d->alasan:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'skd'=>$skd,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						// 'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						// 'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'nama_buat'=>(!empty($d->cbfo)) ? $d->nama_buat_fo : $d->nama_buat,
						'nama_update'=>(!empty($d->ubfo)) ? $d->nama_update_fo : $d->nama_update,
						'e_jenis_cuti'=>$d->jenis,
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($d->tgl_mulai),
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($d->tgl_selesai),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'menyetujui2'=>(!empty($d->nama_menyetujui_2)) ? $d->nama_menyetujui_2.$jbt_menyetujui_2:$this->otherfunctions->getMark(),
						'e_alasan'=>$d->alasan,
						'e_skd'=>$d->skd_dibayar,
						'e_keterangan'=>$d->keterangan,
						'e_validasi'=>$d->validasi,
						'nama_jenis_ic'=>$d->jenis_ic,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'notYetValidate') {
				$ada='<li class="header">Data Izin Cuti Karyawan yang belum di Validasi</li><li class="divider"></li>';
				$data_izin=$this->model_karyawan->getIzinCutiHarianNew(['a.validasi'=>'2']);
				$c=[];
				if(isset($data_izin)){
					foreach ($data_izin as $d) {
						$ada.='<li><a href="'.base_url('pages/data_izin_cuti').'"><i class="fas fa-user"></i> '.$d->nama_karyawan.' <small style="color:red; font-size:8pt;">'.$d->nama_jenis_izin.'</small> <small class="text-muted pull-right" title="Tanggal Berakhir Perjanjian">'.$this->formatter->getDateMonthFormatUser($d->tgl_mulai).'</small></a></li>';
						// $ada.='<li><a href="'.base_url('pages/view_perjanjian_kerja/'.$this->codegenerator->encryptChar($d->id_izin_cuti)).'"><i class="fas fa-user"></i> '.$d->nama_karyawan.' <small style="color:red; font-size:8pt;">'.$d->nama_jenis_izin.'</small> <small class="text-muted pull-right" title="Tanggal Berakhir Perjanjian">'.$this->formatter->getDateMonthFormatUser($d->tgl_mulai).'</small></a></li>';
						array_push($c,1);
					}
					$ada.='<li class="footer"><a href="'.base_url('pages/data_izin_cuti').'">Tampilkan Semua</a></li>';
				}
				if (count($c) == 0) {
					$ada='<li class="text-center"><small class="text-muted"><i class="icon-close"></i> Tidak Ada Data</small></li><li class="divider"> </li>';
				}
				$data=[	'count'=>count($c),
						'value'=>$ada,
					];
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function izin_cuti()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getLisIzinCuti();
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$bulan = $this->input->post('bulan');
					$tahun = $this->input->post('tahun');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
					$data=$this->model_karyawan->getLisIzinCuti('search',$where);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_izin_cuti,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$vali=$this->model_karyawan->getJumStatusValidasi($d->id_karyawan)['valid'];
					if($vali==0){
						$valid='<label class="label label-success" style="font-size:14px;">Sudah Divalidasi</label>';
					}else{
						$valid='<label class="label label-warning" style="font-size:14px;">'.$vali.' Belum Divalidasi</label>';
					}
					$datax['data'][]=[
						$d->id_izin_cuti,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_bagian,
						$d->nama_loker,
						$this->formatter->getDateMonthFormatUser($d->create_date),
						$d->jum.' Data',
						$valid,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_izin_cuti');
				$data=$this->model_karyawan->getIzinCuti($id);
				foreach ($data as $d) {
					$tabel_izin='';
					$tabel_izin.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>No. Izin Cuti</th>
          							<th>Jenis</th>
          							<th>Tanggal Mulai</th>
          							<th>Tanggal Selesai</th>
          							<th>Alasan</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_izin=$this->model_karyawan->getListIzinCutiNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_izin as $d_i) {
									$nama_jenis = $this->otherfunctions->getIzinCuti($d_i->jenis);
          							$tabel_izin.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$d_i->kode_izin_cuti.'</td>
          							<td>'.$d_i->nama_jenis_izin.' ('.$nama_jenis.')</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_i->tgl_mulai).' - '.$this->formatter->timeFormatUser($d->jam_mulai).'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($d_i->tgl_selesai).' - '.$this->formatter->timeFormatUser($d->jam_selesai).'</td>
          							<td>'.$d_i->alasan.'</td>
          						</tr>';
          						$no++;
          					}
	          				$tabel_izin.='</tbody>
	          			</table>';
					$datax=[
						'tabel_izin'=>$tabel_izin,
						'loker'=>$d->nama_loker,
						'jabatan'=>$d->nama_jabatan,
						'bagian'=>$d->nama_bagian,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'foto'=> base_url($d->foto),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeIzinCuti();
        		echo json_encode($data);
			}elseif ($usage == 'izincuti') {
				$data = $this->model_master->getMasterIzinForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'kar_reset') {
				$data = $this->model_karyawan->getEmployeeAllActiveFilter();
				$datax=[];
				foreach ($data as $d) {
					$datax[]=$d->id_karyawan;
				}
				echo json_encode($datax);
			}elseif ($usage == 'kar_reset_val_null') {
				$data = $this->model_karyawan->getRefreshEmployeeForSelect2();
				echo json_encode($data);
			}elseif ($usage == 'view_sisa_cuti') {
				// echo '<pre>';
				// print_r();
				$order = [ 'kolom'=>'nama', 'value'=>'ASD',];
				$data=$this->model_karyawan->getEmployeeAllActive($this->dtroot['adm']['kode_bagian'],$order);
				$tabel_sisa='';
				$tabel_sisa.='<hr><h4 align="center"><b>Data Sisa Cuti Karyawan</b></h4>
						<div style="max-height: 400px; overflow: auto;">
					<table class="table table-bordered table-striped data-table">
						<thead>
							<tr class="bg-blue">
								<th>No.</th>
								<th>Nama</th>
								<th>Jabatan</th>
								<th>Lokasi Kerja</th>
								<th>Sisa Cuti</th>
							</tr>
						</thead>
						<tbody>';
							$no=1;
							foreach ($data as $d) {
								$tabel_sisa.='<tr>
								<td>'.$no.'</td>
								<td>'.$d->nama.'</td>
								<td>'.$d->nama_jabatan.'</td>
								<td>'.$d->nama_loker.'</td>
								<td>'.$d->sisa_cuti.'</td>
							</tr>';
							$no++;
							}
						$tabel_sisa.='</tbody>
					</table></div>';
				$datax=[
					'tabel_sisa_cuti'=>$tabel_sisa,
				];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_izin_cuti()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListIzinCutiNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_izin_cuti,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis);
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['val_izin'])) {
						if(in_array($access['l_ac']['val_izin'], $access['access'])){
							if($d->validasi==2){
								$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need('.$d->id_izin_cuti.')><i class="fa fa-warning"></i> Perlu Validasi</button> ';
							}elseif($d->validasi==1){
								$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes('.$d->id_izin_cuti.')><i class="fa fa-check-circle"></i> Diizinkan</button> ';
							}elseif($d->validasi==0){
								$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no('.$d->id_izin_cuti.')><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
							}
						}else{
							$validasi = $this->otherfunctions->getStatusIzin($d->validasi);
						}
					}else{
						$validasi = '<label class="label label-danger"> Tidak Punya Akses</label>';
					}
					$datax['data'][]=[
						$d->id_izin_cuti,
						$d->kode_izin_cuti,
						'<label class="label label-success" style="font-size:14px;">'.$nama_jenis.'</label>',
						$d->nama_jenis_izin,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai),
						$this->formatter->getDateMonthFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai),
						$validasi,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'].$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_izin_cuti');
				$data=$this->model_karyawan->getIzinCuti($id);
				// print_r($data);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_menyetujui_2=($d->jbt_menyetujui_2 != null) ? ' - '.$d->jbt_menyetujui_2 : null;
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis_ic);
					$mulai = $this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->timeFormatUser($d->jam_mulai).' WIB';
					$selesai = $this->formatter->getDateMonthFormatUser($d->tgl_selesai).' - '.$this->formatter->timeFormatUser($d->jam_selesai).' WIB';
					if($d->skd_dibayar == 1){
						$skd = '<span class="text-success">SKD Dibayar</span>';
					}else{
						$skd = '<span class="text-danger">SKD Tidak Dibayar</span>';
					}
					$datax=[
						'id'=>$d->id_izin_cuti,
						'id_karyawan'=>$d->id_karyawan,
						'nomor'=>$d->kode_izin_cuti,
						'tanggal_mulai'=>$mulai,
						'tanggal_selesai'=>$selesai,
						'tgl_mulai_val'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jenis_cuti'=>$d->nama_jenis_izin.' ('.$nama_jenis.')',
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'emenyetujui2'=>$d->menyetujui_2,
						'alasan'=>(!empty($d->alasan)) ? $d->alasan:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'skd'=>$skd,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						// 'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						// 'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'nama_buat'=>(!empty($d->cbfo)) ? $d->nama_buat_fo : $d->nama_buat,
						'nama_update'=>(!empty($d->ubfo)) ? $d->nama_update_fo : $d->nama_update,
						'e_jenis_cuti'=>$d->jenis,
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($d->tgl_mulai),
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($d->tgl_selesai),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'menyetujui2'=>(!empty($d->nama_menyetujui_2)) ? $d->nama_menyetujui_2.$jbt_menyetujui_2:$this->otherfunctions->getMark(),
						'e_alasan'=>$d->alasan,
						'e_skd'=>$d->skd_dibayar,
						'e_keterangan'=>$d->keterangan,
						'e_validasi'=>$d->validasi,
						'nama_jenis_ic'=>$d->jenis_ic,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'employee') {
				$data = $this->model_karyawan->getEmployeeForSelect2();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function izin_cuti_satpam()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getIzinCutiHarian();
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_izin_cuti,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$vali=$this->model_karyawan->getJumStatusValidasi($d->id_karyawan)['valid'];
					if($vali==0){
						$valid='<label class="label label-success" style="font-size:14px;">Sudah Divalidasi</label>';
					}else{
						$valid='<label class="label label-warning" style="font-size:14px;">'.$vali.' Belum Divalidasi</label>';
					}
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->izinOrCuti);
					$datax['data'][]=[
						$d->id_izin_cuti,
						$d->kode_izin_cuti,
						$d->nama_karyawan.' ('.$d->nik_karyawan.')',
						$d->nama_jabatan,
						$d->nama_bagian,
						'<label class="label label-success" style="font-size:14px;">'.$nama_jenis.'</label>',
						$d->nama_jenis_izin,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai),
						$this->formatter->getDateMonthFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai),
						$valid,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_izin_cuti');
				$data=$this->model_karyawan->getIzinCuti($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_menyetujui_2=($d->jbt_menyetujui_2 != null) ? ' - '.$d->jbt_menyetujui_2 : null;
					$nama_jenis = $this->otherfunctions->getIzinCuti($d->jenis_ic);
					$mulai = $this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->timeFormatUser($d->jam_mulai).' WIB';
					$selesai = $this->formatter->getDateMonthFormatUser($d->tgl_selesai).' - '.$this->formatter->timeFormatUser($d->jam_selesai).' WIB';
					if($d->skd_dibayar == 1){
						$skd = '<span class="text-success">SKD Dibayar</span>';
					}else{
						$skd = '<span class="text-danger">SKD Tidak Dibayar</span>';
					}
					$datax=[
						'loker'=>$d->nama_loker,
						'jabatan'=>$d->nama_jabatan,
						'bagian'=>$d->nama_bagian,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'foto'=> base_url($d->foto),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'nomor'=>$d->kode_izin_cuti,
						'tanggal_mulai'=>$mulai,
						'tanggal_selesai'=>$selesai,
						'tgl_mulai_val'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai),
						'tgl_selesai_val'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai),
						'jenis_cuti'=>$d->nama_jenis_izin.' ('.$nama_jenis.')',
						'emengetahui'=>$d->mengetahui,
						'emenyetujui'=>$d->menyetujui,
						'emenyetujui2'=>$d->menyetujui_2,
						'alasan'=>(!empty($d->alasan)) ? $d->alasan:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'skd'=>$skd,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'e_jenis_cuti'=>$d->jenis,
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($d->tgl_mulai),
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($d->tgl_selesai),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'menyetujui2'=>(!empty($d->nama_menyetujui_2)) ? $d->nama_menyetujui_2.$jbt_menyetujui_2:$this->otherfunctions->getMark(),
						'e_alasan'=>$d->alasan,
						'e_skd'=>$d->skd_dibayar,
						'e_keterangan'=>$d->keterangan,
						'e_validasi'=>$d->validasi,
						'nama_jenis_ic'=>$d->jenis_ic,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_izin_cuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$tanggal = $this->input->post('tanggal');
		$tanggalOld = $this->input->post('tanggal_old');
		$t_awal=explode(" ", $this->formatter->getDateFromRange($tanggal,'start'));
		$t_akhir=explode(" ", $this->formatter->getDateFromRange($tanggal,'end'));
		$t_awal_old=explode(" ", $this->formatter->getDateFromRange($tanggalOld,'start'));
		$t_akhir_old=explode(" ", $this->formatter->getDateFromRange($tanggalOld,'end'));
		$id_karyawan = $this->input->post('id_karyawan');
		$kode = $this->input->post('no_cuti');
		if ($id != "") {
			$this->model_karyawan->syncIzintoDataPresensi($id_karyawan,$kode,$t_awal[0],$t_awal_old[0]);
			$data=array(
				'id_karyawan'=>$id_karyawan,
				'kode_izin_cuti'=>$this->input->post('no_cuti'),
				'jenis'=>$this->input->post('jenis_cuti'),
				'tgl_mulai'=>$t_awal[0],
				'tgl_selesai'=>$t_akhir[0],
				'jam_mulai'=>$t_awal[1],
				'jam_selesai'=>$t_akhir[1],
				'alasan'=>$this->input->post('alasan'),
				'skd_dibayar'=>$this->input->post('skd'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'menyetujui_2'=>$this->input->post('menyetujui2'),
				'keterangan'=>$this->input->post('keterangan'),
				'ubfo'=>null,
			);
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_izin_cuti_karyawan',['id_izin_cuti'=>$id]);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
	   	echo json_encode($datax);
	}
	function add_izin_cuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_cuti');
		$tanggal = $this->input->post('tanggal');
		$tgl_awal = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tanggal,'end');
		$emp=$this->model_karyawan->getEmpID($this->input->post('id_karyawan_cuti'));
		$ats=$this->model_master->getJabatanKodeRow($emp['jabatan']);
		$atasan=$this->model_karyawan->getEmployeeJabatan($ats['atasan']);
		$token=$this->rando.uniqid();
		$date_now=$this->date;
		$dtin=['token'=>$token,'date_now'=>$date_now];
		$link=$this->otherfunctions->companyClientProfile()['link_val_izin'].$this->codegenerator->encryptChar($dtin);
		$alamat_emp=(!empty($emp['alamat_asal_jalan'])?$emp['alamat_asal_jalan'].', ':null).
				(!empty($emp['alamat_asal_desa'])?$emp['alamat_asal_desa'].', ':null).
				(!empty($emp['alamat_asal_kecamatan'])?$emp['alamat_asal_kecamatan'].', ':null).
				(!empty($emp['alamat_asal_kabupaten'])?$emp['alamat_asal_kabupaten'].', ':null).
				(!empty($emp['alamat_asal_provinsi'])?$emp['alamat_asal_provinsi'].', ':null).
				(!empty($emp['alamat_asal_pos'])?$emp['alamat_asal_pos'].', ':null);
		$data_emp = [
			'kepada'=>$emp['nama'], 
			'nama'=>$emp['nama'], 
			'nik'=>$emp['nik'], 
			'alamat'=>$alamat_emp, 
			'jabatan'=>$emp['nama_jabatan'], 
			'loker'=>$emp['nama_loker'], 
			'tanggal'=>$tanggal, 
			'jenis'=>$this->model_master->getMasterIzinJenis($this->input->post('jenis_cuti'))['nama'], 
			'alasan'=>$this->input->post('alasan_cuti'), 
			'keterangan'=>$this->input->post('keterangan_cuti'), 
			'tgl'=>$this->date, 
			'kode'=>$kode,
			'url'=>$this->otherfunctions->companyClientProfile()['url'],
			'logo'=>$this->otherfunctions->companyClientProfile()['logo_url'],
		];
		$data_atasan = [
			'kepada'=>$atasan['nama'], 
			'nama'=>$emp['nama'], 
			'nik'=>$emp['nik'], 
			'alamat'=>$alamat_emp,
			'jabatan'=>$emp['nama_jabatan'], 
			'loker'=>$emp['nama_loker'],
			'tanggal'=>$tanggal, 
			'jenis'=>$this->model_master->getMasterIzinJenis($this->input->post('jenis_cuti'))['nama'], 
			'alasan'=>$this->input->post('alasan_cuti'),
			'keterangan'=>$this->input->post('keterangan_cuti'), 
			'tgl'=>$this->date, 
			'link'=>$link, 
			'kode'=>$kode,
			'url'=>$this->otherfunctions->companyClientProfile()['url'],
			'logo'=>$this->otherfunctions->companyClientProfile()['logo_url'],
		];
		$email_emp=$emp['email'];
		$email_atasan=$atasan['email'];
		// $email_emp='abuumarsg@gmail.com';
		// $email_atasan='ahmadumar559@gmail.com';
		$t_awal=explode(" ", $tgl_awal);
		$t_akhir=explode(" ", $tgl_akhir);
		if ($kode != "") {
			$id_karyawan = $this->input->post('id_karyawan_cuti');
			$data=array(
				'id_karyawan'=>$this->input->post('id_karyawan_cuti'),
				'kode_izin_cuti'=>$kode,
				'jenis'=>$this->input->post('jenis_cuti'),
				'skd_dibayar'=>$this->input->post('skd'),
				'tgl_mulai'=>$t_awal[0],
				'tgl_selesai'=>$t_akhir[0],
				'jam_mulai'=>$t_awal[1],
				'jam_selesai'=>$t_akhir[1],
				'alasan'=>$this->input->post('alasan_cuti'),
				'mengetahui'=>$this->input->post('mengetahui'),
				'menyetujui'=>$this->input->post('menyetujui'),
				'menyetujui_2'=>$this->input->post('menyetujui2'),
				'keterangan'=>$this->input->post('keterangan_cuti'),
				'validasi'		=> 2,
				'token'=>$token,
				'date_now'=>$date_now,
			);
			$this->model_karyawan->syncIzintoDataPresensi($id_karyawan,$kode,$t_awal[0],$t_akhir[0]);
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQueryCC($data,'data_izin_cuti_karyawan',$this->model_karyawan->checkIzinCutiCode($data['kode_izin_cuti']));
			// if(isset($atasan)){
			// 	$ci = get_instance();
			// 	$ci->email->initialize($this->otherfunctions->configEmail());
			// 	$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengajuan Izin/Cuti Online');
			// 	$list = array($email_atasan);
			// 	$ci->email->to($email_atasan);
			// 	$ci->email->subject('Pengajuan Izin/Cuti Online');
			// 	$body = $this->load->view('email_template/email_izin_atasan',$data_atasan,TRUE);
			// 	$ci->email->message($body);
			// 	$eml=$this->email->send();
			// }
			// if(isset($emp)){
			// 	$ci = get_instance();
			// 	$ci->email->initialize($this->otherfunctions->configEmail());
			// 	$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengajuan Izin/Cuti Online');
			// 	$list = array($email_emp);
			// 	$ci->email->to($email_emp);
			// 	$ci->email->subject('Pengajuan Izin/Cuti Online');
			// 	$body = $this->load->view('email_template/email_izin_karyawan',$data_emp,TRUE);
			// 	$ci->email->message($body);
			// 	$eml=$this->email->send();
			// }
			// if ($eml && $dbs){
			// 	$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
			// }elseif($dbs){
			// 	$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
			// }elseif($eml){
			// 	$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
			// }else{
			// 	$datax=$this->messages->allFailure();
			// }
			// if($dbs){
			// 	$datax=$this->messages->allGood();
			// }else{
			// 	$datax=$this->messages->allFailure();
			// }
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function tanggalIzin(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_kar = $this->input->post('id_kar');
		$kode_izin = $this->input->post('jenis');
		$tanggal = $this->input->post('tanggal');
		$tgl_awal=$this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir=$this->formatter->getDateFromRange($tanggal,'end');
		$hari=$this->otherfunctions->hitungHari($tgl_awal,$tgl_akhir);
		$jenis=$this->model_master->getMasterIzinJenis($kode_izin)['jenis'];
		$maksimal=$this->model_master->getMasterIzinJenis($kode_izin)['maksimal'];
		$nama_jenis=$this->otherfunctions->getIzinCuti($jenis);
		$sisa_cuti=$this->model_karyawan->getEmpID($id_kar)['sisa_cuti'];
		if($jenis == 'C'){
			if($hari > $maksimal){
				$msg='<b>Jenis Cuti ini maksimal '.$maksimal.' Hari, tidak boleh '.$hari.' Hari, Sisa Cuti karyawan '.$sisa_cuti.' Hari</b>';
			}else{
				$msg='<b>karyawan akan mengajukan '.$nama_jenis.' selama '.$hari.' Hari</b>';
			}
		}else{
			if($maksimal < $hari){
				$msg='<b>Jenis Izin ini maksimal '.$maksimal.' Hari, tidak boleh '.$hari.' Hari</b>';
			}else{
				$msg='<b>karyawan akan mengajukan '.$nama_jenis.' selama '.$hari.' Hari</b>';
			}
		}
		$data=['msg'=>$msg,'jenis'=>$jenis,'hari'=>$hari,'maksimal'=>$maksimal,'sisa_cuti'=>$sisa_cuti];
		echo json_encode($data);
	}
	public function cekTanggalIzin(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_kar = $this->input->post('id_kar');
		$kode_izin = $this->input->post('jenis');
		$jenis=$this->model_master->getMasterIzinJenis($kode_izin)['jenis'];
		$cek=$this->model_karyawan->cekDataIzinCuti($id_kar, $jenis);
		$msg=null;
		$ci=[];
		if(isset($cek)){
			foreach ($cek as $c) {
				$ci[]=$c['buat'] == $c['sekarang'] && $c['id_kar'] == $c['id_karyawan'] && $c['jenis'] == $c['jenis_db'];
			}
			if(array_sum($ci) > 2){
				$msg='karyawan tidak diIzinkan untuk mengajukan Cuti / Izin lebih dari 3 (Satu) Kali dalam 1 Hari, Silahkan mengajukan Cuti /Izin lagi besok atau Edit Data Pengajuan Cuti karyawan Sebelumnya..';
			}else{
				$msg=null;
			}
		}
		$sum=array_sum($ci);
		$data=['msg'=>$msg,'cek'=>$sum,];
		echo json_encode($data);
	}
	public function cekSisaCuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$msg        = null;
		$sisa_cuti  = 0;
		$id_kar     = $this->input->post('id_kar');
		$kode_izin  = $this->input->post('jenis');
		$tanggal    = $this->input->post('tanggal');
		$tgl_awal   = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir  = $this->formatter->getDateFromRange($tanggal,'end');
		$hari       = $this->otherfunctions->hitungHari($tgl_awal,$tgl_akhir);
		$jenis      = $this->model_master->getMasterIzinJenis($kode_izin)['jenis'];
		$potong_cuti= $this->model_master->getMasterIzinJenis($kode_izin)['potong_cuti'];
		if($jenis == 'C' && $potong_cuti == 1){
			$sisa_cuti=$this->model_karyawan->getEmpID($id_kar)['sisa_cuti'];
			if($sisa_cuti <= $hari){
				$sisa_cuti=($sisa_cuti==null)?0:$sisa_cuti;
				$msg='Sisa Cuti karyawan '.$sisa_cuti.' Hari, karyawan tidak dapat mengajukan cuti, silahkan hubungi administrator..';
			}
		}elseif($jenis == 'C' && $potong_cuti != 1){
			$msg='Anda memilih jenis Cuti yang tidak memotong jumlah cuti tahunan.';
		}else{
			$msg=null;
		}
		$data=['msg'=>$msg,'sisa_cuti'=>$sisa_cuti,'hari'=>$hari,'potong_cuti'=>$potong_cuti];
		echo json_encode($data);
	}
	public function minCuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_kar     = $this->input->post('id_kar');
		$kode_izin  = $this->input->post('jenis');
		$tanggal    = $this->input->post('tanggal');
		$msg = null;
		if($kode_izin == 'MIC201907160001'){
			$dataCuti = $this->model_karyawan->getListIzinCutiWhereMaxID(['id_karyawan'=>$id_kar,'jenis'=>$kode_izin,'validasi !='=>'0']);
			$tgl_awal   = $this->formatter->getDateFromRange($tanggal,'start');
			if(strtotime($dataCuti['tgl_selesai']) > strtotime($tgl_awal)){
				$msg = 'Anda belum bisa mengajukan Cuti Tahunan karena Tanggal yang anda input harus melewati terakhir kali anda cuti';
			}else{
				$hari       = $this->otherfunctions->countDayNotIncludeLeave($dataCuti['tgl_selesai'], $tgl_awal);
				$minCuti	= $this->model_master->getGeneralSetting('MIN_CUTI')['value_int'];
				if($minCuti > $hari){
					$msg = 'Anda belum bisa mengajukan Cuti Tahunan Kembali karena anda mengajukan Cuti Tahunan '.$hari.' Hari yang lalu, jarak minimal pengajuan antar Cuti tahunan adalah '.$minCuti.' Hari';
				}
			}
		}
		$data=['msg'=>$msg];
		echo json_encode($data);
	}
	public function validasi_izin()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		// echo '<pre>';
		$id=$this->input->post('id_izin_cuti');
		$id_kar=$this->input->post('id_k');
		$val_db=$this->input->post('validasi_db');
		$jenis_db=$this->input->post('jenis_db');
		$vali=$this->input->post('validasi');
		$sisa_cuti_db=$this->model_karyawan->getEmpID($id_kar)['sisa_cuti'];
		// $d_izin_cuti=$this->model_karyawan->getIzinCutiID($id);
		$d_izin_cuti=$this->model_karyawan->getIzinCutiIDNew($id);
		// print_r($d_izin_cuti);
		// $tgl_mulai=$this->model_karyawan->getIzinCutiID($id)['tgl_mulai'];
		// $tgl_selesai=$this->model_karyawan->getIzinCutiID($id)['tgl_selesai'];
		$jum_cuti=$this->otherfunctions->hitungHari($d_izin_cuti['tgl_mulai'],$d_izin_cuti['tgl_selesai']);
		$potongCuti=$this->model_master->getMasterIzinJenis($d_izin_cuti['jenis'])['potong_cuti'];
		$emp=$this->model_karyawan->getEmpID($id_kar);
		$tanggal=$this->formatter->getDateMonthFormatUser($d_izin_cuti['tgl_mulai']).' '.$this->formatter->timeFormatUser($d_izin_cuti['jam_mulai']).' -
				<br>'.$this->formatter->getDateMonthFormatUser($d_izin_cuti['tgl_selesai']).' '.$this->formatter->timeFormatUser($d_izin_cuti['jam_selesai']);
		$alamat_emp=(!empty($emp['alamat_asal_jalan'])?$emp['alamat_asal_jalan'].', ':null).
				(!empty($emp['alamat_asal_desa'])?$emp['alamat_asal_desa'].', ':null).
				(!empty($emp['alamat_asal_kecamatan'])?$emp['alamat_asal_kecamatan'].', ':null).
				(!empty($emp['alamat_asal_kabupaten'])?$emp['alamat_asal_kabupaten'].', ':null).
				(!empty($emp['alamat_asal_provinsi'])?$emp['alamat_asal_provinsi'].', ':null).
				(!empty($emp['alamat_asal_pos'])?$emp['alamat_asal_pos'].', ':null);
		$data_emp = ['kepada'=>$emp['nama'], 
					'nama'=>$emp['nama'], 
					'nik'=>$emp['nik'], 
					'alamat'=>$alamat_emp, 
					'jabatan'=>$emp['nama_jabatan'], 
					'loker'=>$emp['nama_loker'], 
					'tanggal'=>$tanggal, 
					'jenis'=>$this->model_master->getMasterIzinJenis($d_izin_cuti['jenis'])['nama'], 
					'alasan'=>$d_izin_cuti['alasan'], 
					'keterangan'=>$d_izin_cuti['keterangan'], 
					'tgl'=>$this->date, 
					'kode'=>$d_izin_cuti['kode_izin_cuti'],
					'validasi'=>(($vali==1)?'DIIZINKAN':'TIDAK DIIZINKAN'),
					'url'=>$this->otherfunctions->companyClientProfile()['url'],
					'logo'=>$this->otherfunctions->companyClientProfile()['logo_url'],
				];
		$email_emp=$emp['email'];
		if($val_db==2 && $vali==1 || $val_db==0 && $vali==1){
			if($jenis_db == 'C' && $potongCuti == 1){
				$sisa_cuti=$sisa_cuti_db-$jum_cuti;
				$data_kar=['sisa_cuti'=>$sisa_cuti];
				$where_kar=['id_karyawan'=>$id_kar];
				$this->model_global->updateQueryNoMsg($data_kar,'karyawan',$where_kar);
			}
			$data_presensi=[];
			while ($d_izin_cuti['tgl_mulai'] <= $d_izin_cuti['tgl_selesai'])
			{ 
				$data_presensi[$d_izin_cuti['tgl_mulai']]=[
					'id_karyawan'   =>$id_kar,
					'tanggal'    	=>$d_izin_cuti['tgl_mulai'],
				];
				$d_izin_cuti['tgl_mulai'] = date('Y-m-d', strtotime($d_izin_cuti['tgl_mulai'] . ' +1 day'));
			}
			foreach ($data_presensi as $d => $value) {
				$cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($value['id_karyawan'],$d);
				if($d_izin_cuti['izinOrCuti'] == 'I' && $d_izin_cuti['hitung_terlambat'] == '0'){
					$value['jam_mulai']=$cekjadwal['jam_mulai'];
				}
				$value['kode_ijin']=$d_izin_cuti['kode_izin_cuti'];
				$cek=$this->model_karyawan->checkPresensiDate($id_kar,$d);
				if($cek){
					$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($value,'data_presensi',['id_karyawan'=>$id_kar,'tanggal'=>$d]);
				}else{
					$value['kode_shift']=$cekjadwal['kode_shift'];
					$value=array_merge($value,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQueryNoMsg($value,'data_presensi');
				}
			}
			$data=['validasi'=>$vali];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$where=['id_izin_cuti'=>$id,'validasi'=>$val_db];
			$dbs=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
		}elseif($val_db==1 && $vali==0){
			if($jenis_db == 'C' && $potongCuti == 1){
				$sisa_cuti=$sisa_cuti_db+$jum_cuti;
				$data_kar=['sisa_cuti'=>$sisa_cuti];
				$where_kar=['id_karyawan'=>$id_kar];
				$this->model_global->updateQueryNoMsg($data_kar,'karyawan',$where_kar);
			}
			$data_presensi=[];
			while ($d_izin_cuti['tgl_mulai'] <= $d_izin_cuti['tgl_selesai'])
			{
				$data_presensi[$d_izin_cuti['tgl_mulai']]=[
					'id_karyawan'   =>$id_kar,
					'tanggal'    =>$d_izin_cuti['tgl_mulai'],
				];
				$d_izin_cuti['tgl_mulai'] = date('Y-m-d', strtotime($d_izin_cuti['tgl_mulai'] . ' +1 day'));
			}
			foreach ($data_presensi as $d => $value) {
				$value['kode_ijin']=null;
				if($d_izin_cuti['izinOrCuti'] == 'I' && $d_izin_cuti['hitung_terlambat'] == '0'){
					$where="emp.id_karyawan='".$id_kar."'";
					$dataLogPreAll = $this->model_karyawan->getDataTemporariSync($value['tanggal'],$value['tanggal'],$where);
					$data_jam = [];
					if ($dataLogPreAll) {
						foreach ($dataLogPreAll as $kp => $pre) {
							$cekjadwal2 = $this->model_presensi->cekJadwalKerjaIdDateJKB($id_kar,$value['tanggal']);
							$data_jamx=$this->model_karyawan->coreImportPresensi22(['kode_shift'=>$cekjadwal2['kode_shift'],'tanggal'=>$value['tanggal'],'jam'=>$pre->jam,'jadwal'=>$cekjadwal2,'id_karyawan'=>$id_kar]);
							$data_jam[$value['tanggal']][]=$data_jamx;
						}
					}
					if(!empty($data_jam)){
						foreach ($data_jam as $tgl => $log) {
							foreach ($log as $key => $val) {
								$dataPre = array_merge($value, $val);
								$cek=$this->model_karyawan->checkPresensiDate($id_kar,$d);
								if($cek){
									$dataPre=array_merge($dataPre,$this->model_global->getUpdateProperties($this->admin));
									$this->model_global->updateQueryNoMsg($dataPre,'data_presensi',['id_karyawan'=>$id_kar,'tanggal'=>$d]);
								}else{
									$dataPre=array_merge($dataPre,$this->model_global->getCreateProperties($this->admin));
									$this->model_global->insertQueryNoMsg($dataPre,'data_presensi');
								}
							}
						}
					}
				}
				$cek=$this->model_karyawan->checkPresensiDate($id_kar,$d);
				if($cek){
					$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($value,'data_presensi',['id_karyawan'=>$id_kar,'tanggal'=>$d]);
				}else{
					$value=array_merge($value,$this->model_global->getCreateProperties($this->admin));
					$this->model_global->insertQueryNoMsg($value,'data_presensi');
				}
			}
			$data=['validasi'=>$vali];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$where=['id_izin_cuti'=>$id,'validasi'=>$val_db];
			$dbs=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
		}else{
			$data=['validasi'=>$vali];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$where=['id_izin_cuti'=>$id,'validasi'=>$val_db];
			$dbs=$this->model_global->updateQuery($data,'data_izin_cuti_karyawan',$where);
		}
		// if(isset($emp)){
		// 	$ci = get_instance();
		// 	$ci->email->initialize($this->otherfunctions->configEmail());
		// 	$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengajuan Izin/Cuti Online');
		// 	$list = array($email_emp);
		// 	$ci->email->to($email_emp);
		// 	$ci->email->subject('Validasi Izin/Cuti Online');
		// 	$body = $this->load->view('email_template/email_izin_validasi',$data_emp,TRUE);
		// 	$ci->email->message($body);
		// 	$eml=$this->email->send();
		// }
		// if ($eml && $dbs){
		// 	$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
		// }elseif($dbs){
		// 	$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
		// }elseif($eml){
		// 	$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
		// }else{
		// 	$datax=$this->messages->allFailure();
		// }
		if($dbs){
			$datax=$this->messages->allGood();
		}else{
			$datax=$this->messages->allFailure();
		}
		echo json_encode($datax);
	}
	public function reset_izin_cuti(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$karyawan=$this->input->post('karyawan_reset');
		$jum_cuti=$this->input->post('jumlah_cuti');
		foreach($karyawan as $kar){
			$data=['sisa_cuti'=>$jum_cuti];
			$where=['id_karyawan'=>$kar];
			$datax=$this->model_global->updateQuery($data,'karyawan',$where);
		}
		echo json_encode($datax);
	}
//========================================================== DATA LEMBUR Per TRANSAKSI =======================================================
	public function data_lembur_trans()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				$bagian = $this->input->post('bagian');
				if($param == 'all'){
					$data=$this->model_karyawan->getListLemburTrans();
				}elseif($param == 'tab'){
					$where = ['param'=>'tab','bagian'=>$bagian,];
					$data=$this->model_karyawan->getListLemburTrans('search',$where);
				}else{
					$unit = $this->input->post('unit');
					$tanggal = $this->input->post('tanggal');
					$status_validasi = $this->input->post('status_validasi');
					$potong_jam = $this->input->post('potong_jam');
					$where = ['param'=>$param,'bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal,'status_validasi'=>$status_validasi,'potong_jam'=>$potong_jam];
					$data=$this->model_karyawan->getListLemburTrans('search',$where,'cari');
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_data_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tgl_lembur=$this->formatter->getDateMonthFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai).' - <br>'.$this->formatter->getDateMonthFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal("'.$d->no_lembur.'")><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal("'.$d->no_lembur.'")><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					if($d->status_potong == 1){
						if (isset($access['l_ac']['adjust_lembur'])) {
							if(in_array($access['l_ac']['adjust_lembur'], $access['access'])){
								if (isset($access['l_ac']['val_lembur'])) {
									if(in_array($access['l_ac']['val_lembur'], $access['access'])){
										if($d->validasi==2){
											$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need("'.$d->no_lembur.'")><i class="fa fa-warning"></i> Perlu Validasi</button> ';
										}elseif($d->validasi==1){
											$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes("'.$d->no_lembur.'")><i class="fa fa-check-circle"></i> Diizinkan</button> ';
										}elseif($d->validasi==0){
											$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no("'.$d->no_lembur.'")><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
										}
									}else{
										$validasi = $this->otherfunctions->getStatusIzin($d->validasi);
									}
								}else{
									$validasi = null;
								}
							}else{
								$validasi = $this->otherfunctions->getStatusIzin($d->validasi);
							}
						}else{
							$validasi = $this->otherfunctions->getStatusIzin($d->validasi);
						}
					}else{
						if (isset($access['l_ac']['val_lembur'])) {
							if(in_array($access['l_ac']['val_lembur'], $access['access'])){
								if($d->validasi==2){
									$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need("'.$d->no_lembur.'")><i class="fa fa-warning"></i> Perlu Validasi</button> ';
								}elseif($d->validasi==1){
									$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes("'.$d->no_lembur.'")><i class="fa fa-check-circle"></i> Diizinkan</button> ';
								}elseif($d->validasi==0){
									$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no("'.$d->no_lembur.'")><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
								}
							}else{
								$validasi = $this->otherfunctions->getStatusIzin($d->validasi);
							}
						}else{
							$validasi = null;
						}
					}
					// if (isset($access['l_ac']['val_lembur'])) {
					// 	if(in_array($access['l_ac']['val_lembur'], $access['access'])){
					// 		if($d->validasi==2){
					// 			$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need("'.$d->no_lembur.'")><i class="fa fa-warning"></i> Perlu Validasi</button> ';
					// 		}elseif($d->validasi==1){
					// 			$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes("'.$d->no_lembur.'")><i class="fa fa-check-circle"></i> Diizinkan</button> ';
					// 		}elseif($d->validasi==0){
					// 			$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no("'.$d->no_lembur.'")><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
					// 		}
					// 	}else{
					// 		$validasi = $this->otherfunctions->getStatusIzin($d->validasi);
					// 	}
					// }else{
					// 	$validasi = null;
					// }
					if (isset($access['l_ac']['prn'])) {
						$print = (in_array($access['l_ac']['prn'], $access['access'])) ? '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=do_print("'.$d->no_lembur.'")><i class="fa fa-print" data-toggle="tooltip" title="Cetak Data"></i></button> ' : null;
					}else{
						$print = null;
					}
					if (isset($access['l_ac']['adjust_lembur'])) {
						if(in_array($access['l_ac']['adjust_lembur'], $access['access'])){
							if($d->validasi==2){
								$bPotong = null;
							}elseif($d->validasi==1){
								$bPotong = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=potongLembur("'.$d->no_lembur.'")><i class="fa fa-check-circle" data-toggle="tooltip" title="Sesuaikan Potong Lembur"></i></button> ';
							}elseif($d->validasi==0){
								$bPotong = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=potongLembur("'.$d->no_lembur.'")><i class="fa fa-check-circle" data-toggle="tooltip" title="Sesuaikan Potong Lembur"></i></button> ';
							}
							if($d->status_potong == 1){
								$status_potong = '<i class="fa fa-check-circle" style="color:green;"></i>';
							}else{
								$status_potong = '<i class="fa fa-times-circle" style="color:red;"></i>';
							}
						}else{
							$bPotong =null;
							$status_potong = null;
						}
					}else{
						$bPotong =null;
						$status_potong = null;
					}
					$datax['data'][]=[
						$d->id_data_lembur,
						$d->no_lembur.'<br>'.$status_potong,
						$tgl_lembur,
						$d->nama_buat,
						$this->formatter->getDateMonthFormatUser($d->tgl_buat),
						$d->jum_no.' Karyawan',
						$validasi,
						$properties['tanggal'],
						$info.$bPotong.$print.(($d->validasi!=2)?$delete:null),
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$no_l = $this->input->post('no_lembur');
				$data=$this->model_karyawan->getLemburTrans($no_l, true);
				// foreach ($data as $d) {
					$tabel_lembur='';
					$tabel_lembur.='<h4 align="center"><b>Data Karyawan Yang Lembur</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>NIK</th>
          							<th>Nama</th>
          							<th>Jabatan</th>
          							<th>Lama Lembur</th>
          							<th>Kode Customer</th>
          							<th>Keterangan</th>
          							<th>Jam Jadwal</th>
          							<th>Jam Presensi</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_lembur=$this->model_karyawan->getLemburTrans($no_l);
          						$no=1;
          						foreach ($data_lembur as $dl) {
									$jam_lama_l=$this->otherfunctions->getDataExplode($dl->jumlah_lembur,':','start');
									$menit_lama_l=$this->otherfunctions->getDataExplode($dl->jumlah_lembur,':','end');
									$jml_lmbr=(isset($dl->jumlah_lembur)) ? $dl->jumlah_lembur:$this->otherfunctions->getMark();
									$tgl_pre = $this->model_karyawan->getListPresensiId(null,['pre.id_karyawan'=>$dl->id_karyawan,'pre.tanggal'=>$dl->tgl_mulai],null,'row');
          							$tabel_lembur.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$dl->nik_karyawan.'</td>
          							<td>'.$dl->nama_karyawan.'</td>
          							<td>'.$dl->nama_jabatan.'</td>
          							<td>'.(($jam_lama_l!='00')?$jam_lama_l.' Jam, ':null).(($menit_lama_l!='00')?$menit_lama_l.' Menit':null).'</td>
          							<td>'.$dl->kode_customer.'</td>
          							<td>'.$dl->keterangan.'</td>
          							<td>'.$this->formatter->timeFormatUser($tgl_pre['jam_mulai_shift']).' - '.$this->formatter->timeFormatUser($tgl_pre['jam_selesai_shift']).'</td>
          							<td>'.$this->formatter->timeFormatUser($tgl_pre['jam_mulai']).' - '.$this->formatter->timeFormatUser($tgl_pre['jam_selesai']).'</td>
          						</tr>';
          						$no++;
          					}
	          				$tabel_lembur.='</tbody>
						  </table>';
					if(!empty($data['val_jumlah_lembur']) && $data['val_jumlah_lembur'] != 'NULL' && $data['val_jumlah_lembur'] != null){
						$jam_lama_lem_val=$this->otherfunctions->getDataExplode($data['val_jumlah_lembur'],':','start');
						$menit_lama_lem_val=$this->otherfunctions->getDataExplode($data['val_jumlah_lembur'],':','end');
					}else{
						$jam_lama_lem_val=null;
						$menit_lama_lem_val=null;
					}
					$lama_lembur_vali=(($jam_lama_lem_val!='00')?$jam_lama_lem_val.' Jam, ':null).(($menit_lama_lem_val!='00')?$menit_lama_lem_val.' Menit':null);
					$lama_lembur_val=(!empty($data['val_jumlah_lembur'])?$lama_lembur_vali:$this->otherfunctions->getMark());
					$ptg_jam=($data['potong_jam']==1)?'<b class="text-success">Potong Jam Istirahat</b>':'<b class="text-danger">Tidak Potong Jam Istirahat</b>';
					// $lamaLembur       = $this->formatter->convertJamtoDecimal($data['jumlah_lembur']);
					// $PI               = $this->payroll->getPotonganIstirahat($lamaLembur,$data['tgl_mulai']);
					// $pIstirahat       = $this->formatter->convertDecimaltoJam($PI);
					$potonganIstirahat= $this->otherfunctions->getStringInterval($data['potong_jam']);
					// $lamaLemburval       = $this->formatter->convertJamtoDecimal($data['val_jumlah_lembur']);
					// $tglvalidasi		 = $this->otherfunctions->getDataExplode($data['val_tgl_mulai'],' ','start');
					// $PIval               = $this->payroll->getPotonganIstirahat($lamaLemburval,$tglvalidasi);
					// $pIstirahatval       = $this->formatter->convertDecimaltoJam($PIval);
					if(!empty($data['val_potong_jam']) && $data['val_potong_jam'] != 'NULL' && $data['val_potong_jam'] != null){
						$potonganIstirahatval= $this->otherfunctions->getStringInterval($data['val_potong_jam']);
					}else{
						$potonganIstirahatval=null;
					}
					$data_after_val='<div class="panel panel-success">
							<div class="panel-heading bg-green"><h4>Data Validasi Lembur</h4></div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Tanggal Mulai</label>
												<div class="col-md-6">'.(!empty($data['val_tgl_mulai'])?$this->formatter->getDateTimeMonthFormatUser($data['val_tgl_mulai'],1):$this->otherfunctions->getMark()).'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Tangaal Selesai</label>
												<div class="col-md-6">'.(!empty($data['val_tgl_selesai'])?$this->formatter->getDateTimeMonthFormatUser($data['val_tgl_selesai'],1):$this->otherfunctions->getMark()).'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Lama Lembur</label>
												<div class="col-md-6">'.$lama_lembur_val.'</div>
											</div><br>
										</div>
										<div class="col-md-6">
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Potong Jam Istirahat</label>
												<div class="col-md-6">'.$potonganIstirahatval.'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Catatan Validasi</label>
												<div class="col-md-6">'.(!empty($data['val_catatan'])?$data['val_catatan']:$this->otherfunctions->getMark()).'</div>
											</div><br>
										</div>
									</div>
								</div>
							</div>
						</div>';
					$tabel_lembur_spl='';
					$tabel_lembur_spl.='<h4 align="center"><b>Data Karyawan Yang Lembur</b></h4>
						<div style="max-height: 400px; overflow: auto;">
						<form id="form_edit_spl" class="form-horizontal">
							<table class="table table-bordered table-striped data-table">
								<thead>
									<tr class="bg-blue">
										<th>No.</th>
										<th>No. SPL</th>
										<th>Nama</th>
										<th>Jabatan</th>
										<th>Lama Lembur</th>
										<th>Jam Jadwal</th>
										<th>Jam Presensi</th>
									</tr>
								</thead>
								<tbody>';
								$data_lembur=$this->model_karyawan->getLemburTrans($no_l);
									$no=1;
									foreach ($data_lembur as $dl) {
										$jam_lama_l=$this->otherfunctions->getDataExplode($dl->jumlah_lembur,':','start');
										$menit_lama_l=$this->otherfunctions->getDataExplode($dl->jumlah_lembur,':','end');
										$jml_lmbr=(isset($dl->jumlah_lembur)) ? $dl->jumlah_lembur:$this->otherfunctions->getMark();
										$tgl_pre = $this->model_karyawan->getListPresensiId(null,['pre.id_karyawan'=>$dl->id_karyawan,'pre.tanggal'=>$dl->tgl_mulai],null,'row');
										$tabel_lembur_spl.='<tr>
										<td>'.$no.'<input type="hidden" name="id_data_lembur[]" value="'.$dl->id_data_lembur.'"></td>
										<td><input type="text" name="no_spl[]" class="form-control kode_spl" placeholder="No SPL" required="required" style="width: 100%;" onkeyup="cekNoSPL(this.value)" value="'.$dl->no_lembur.'"></td>
										<td>'.$dl->nama_karyawan.'</td>
										<td>'.$dl->nama_jabatan.'</td>
										<td>'.(($jam_lama_l!='00')?$jam_lama_l.' Jam, ':null).(($menit_lama_l!='00')?$menit_lama_l.' Menit':null).'</td>
										<td>'.$this->formatter->timeFormatUser($tgl_pre['jam_mulai_shift']).' - '.$this->formatter->timeFormatUser($tgl_pre['jam_selesai_shift']).'</td>
										<td>'.$this->formatter->timeFormatUser($tgl_pre['jam_mulai']).' - '.$this->formatter->timeFormatUser($tgl_pre['jam_selesai']).'</td>
									</tr>';
									$no++;
								}
								$tabel_lembur_spl.='</tbody>
							</table>
							<span id="div_span_cek_spl"></span>
							<br><h4 class="text-muted"><font color="green">Kami sarankan Anda merubah 1 (satu) sampai 4 (empat) digit angka dari belakang tanpa menambah dan mengurangi panjang nomor SPL.</font></h4>
						</form>';
					$jam_lama_lem=$this->otherfunctions->getDataExplode($data['jumlah_lembur'],':','start');
					$menit_lama_lem=$this->otherfunctions->getDataExplode($data['jumlah_lembur'],':','end');
					$e_karyawan=[];
					foreach ($data_lembur as $key) {
						$e_karyawan[]=$key->id_karyawan;
					}
					$jbt_buat         = ($data['jbt_buat'] != null) ? ' - '.$data['jbt_buat'] : null;
					$jbt_periksa      = ($data['jbt_periksa'] != null) ? ' - '.$data['jbt_periksa'] : null;
					$jbt_ketahui      = ($data['jbt_ketahui'] != null) ? ' - '.$data['jbt_ketahui'] : null;
					$kodeBagian4Kuota = $this->model_karyawan->getEmployeeId($data['id_karyawan'])['kode_bagian'];
					$historyKuota = $this->model_master->getListHistoryKuotaLembur(['a.kode_lembur'=>$no_l],true);
					if(!empty($historyKuota)){
						$pembebanan = (($historyKuota['kode_bagian'] == $kodeBagian4Kuota) ? '0' : '1');
					}else{
						$pembebanan = '0';
					}
					// echo '<pre>';
					// print_r($historyKuota);
					// print_r($kodeBagian4Kuota);echo '<br>';
					// print_r($historyKuota['kode_bagian']);
					$datax=[
						'id'=>$data['id_data_lembur'],
						'no_lembur'=>$data['no_lembur'],
						'table_lembur_kary'=>$tabel_lembur,
						'tanggal_mulai'=>$this->formatter->getDateMonthFormatUser($data['tgl_mulai']).' '.$this->formatter->timeFormatUser($data['jam_mulai']),
						'tanggal_selesai'=>$this->formatter->getDateMonthFormatUser($data['tgl_selesai']).' '.$this->formatter->timeFormatUser($data['jam_selesai']),
						'lama_lembur'=>(($jam_lama_lem!='00')?$jam_lama_lem.' Jam, ':null).(($menit_lama_lem!='00')?$menit_lama_lem.' Menit':null),
						// 'potong'=>($data['potong_jam']==1)?'<b class="text-success">Potong Jam Istirahat</b>':'<b class="text-danger">Tidak Potong Jam Istirahat</b>',
						'potong'=>($data['potong_jam'] != "00:00")?$potonganIstirahat:$this->otherfunctions->getMark(),
						'potongval'=>($data['val_potong_jam'] != "00:00")?$potonganIstirahatval:$this->otherfunctions->getMark(),
						'keterangan'=>$data['keterangan'],
						'kode_customer'=>$data['kode_customer'],
						'e_dibuat'=>$data['dibuat_oleh'],
						'e_diperiksa'=>$data['diperiksa_oleh'],
						'e_diketahui'=>$data['diketahui_oleh'],
						'e_jenis_lembur'=>$data['jenis_lembur'],
						'jenis_lembur'=>(!empty($data['jenis_lembur'])?$this->otherfunctions->getJenisLemburKey($data['jenis_lembur']):$this->otherfunctions->getMark()),
						'jam_istirahat_edit'=>$data['potong_jam'],
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($data['tgl_mulai']).' '.$data['jam_mulai'],
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($data['tgl_selesai']).' '.$data['jam_selesai'],
						'e_tgl_buat'=>$this->formatter->getDateFormatUser($data['tgl_buat']),
						'e_keterangan'=>$data['keterangan'],
						'e_karyawan'=>$e_karyawan,
						'e_validasi'=>$data['validasi'],
						'status_potong'=>$data['status_potong'],
						'val_pLembur'=>$data['val_potong_jam'],
						'data_after_val'=>$data_after_val,
						'table_lembur_spl'=>$tabel_lembur_spl,
						'status_val'=>$this->otherfunctions->getStatusIzin($data['validasi']),
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($data['create_date']),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($data['update_date']),
						'create_by'=>$data['create_by'],
						'update_by'=>$data['update_by'],
						'nama_buat'=>(!empty($data['nama_buat'])) ? $data['nama_buat']:$this->otherfunctions->getMark($data['nama_buat']),
						'nama_update'=>(!empty($data['nama_update']))?$data['nama_update']:$this->otherfunctions->getMark($data['nama_update']),
						'tgl_buat'=>$this->formatter->getDateMonthFormatUser($data['tgl_buat']),
						'diajukan'=>(!empty($data['nama_buat_trans'])) ? $data['nama_buat_trans'].$jbt_buat:$this->otherfunctions->getMark(),
						'diperiksa'=>(!empty($data['nama_periksa'])) ? $data['nama_periksa'].$jbt_periksa:$this->otherfunctions->getMark(),
						'diketahui'=>(!empty($data['nama_ketahui'])) ? $data['nama_ketahui'].$jbt_ketahui:$this->otherfunctions->getMark(),
						'kode_bagian'=>$kodeBagian4Kuota,
						'lama_lembur_kuota'=>$this->formatter->convertJamtoDecimal($data['jumlah_lembur']),
						'count_old'=>count($e_karyawan),
						'pembebanan'=>$pembebanan,
						'kode_bagian_kuota'=>$historyKuota['kode_bagian'],
						'pilihan_lembur'=>(!empty($data['pilihan_lembur'])?$this->otherfunctions->getPilihanLemburKey($data['pilihan_lembur']):$this->otherfunctions->getMark()),
						'e_pilihan_lembur'=>$data['pilihan_lembur'],
					];
				// }
				echo json_encode($datax);
			}elseif ($usage == 'view_select') {
				$kode = $this->input->post('kode_bagian');
				if($kode == 'BAG002'){
					$data = $this->model_karyawan->getEmployeeAllActive();
				}else{
					$data = $this->model_karyawan->getEmployeeKodeBagian($kode);
				}
				$datax=[];
				foreach ($data as $d) {
					$datax[]=$d->id_karyawan;
				}
				echo json_encode($datax);
			}elseif ($usage == 'get_select') {
				$kode = $this->input->post('kode_bagian');
				if($kode == 'BAG002'){
					$data = $this->model_karyawan->getEmployeeAllActive();
				}else{
					$data = $this->model_karyawan->getEmployeeKodeBagian($kode);
				}
				$options='';
				$select=[];
				foreach ($data as $d) {
					$options.='<option value="'.$d->id_karyawan.'">'.$d->nama.' ('.$d->nama_jabatan.')</option>';
					$select[]=$d->id_karyawan;
				}
				$datax = [
					'options'=>$options,
					'select'=>$select,
				];
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeDataLembur();
        		echo json_encode($data);
			}elseif ($usage == 'cek_spl') {
				$kode = $this->input->post('no_spl');
				$data = $this->model_presensi->cekNoSPL($kode);
        		echo json_encode($data);
			}elseif ($usage == 'getLemburend') {
				// print_r($this->dtroot);
				$ada='<li class="header">Data Pengajuan Lembur Terbaru</li><li class="divider"></li>';
				// $kode_bagian = [];
				$postime = strpos($this->dtroot['adm']['list_bagian'], ";");
				// $kode_bagian = null;
				$jumlahVal = 0;
				if (!empty($this->dtroot['adm']['list_bagian'])) {
					if ($postime == true) {
						$kode_bagianx = $this->otherfunctions->getDataExplode($this->dtroot['adm']['list_bagian'],';','all');						
						$jabatan = $this->model_karyawan->getBagianBawahanNotifWhere($kode_bagianx);
						// $where = '';
						// $c_lvx=1;
						// foreach ($kode_bagianx as $key => $bag) {
						// 	$where.="a.kode_bagian='".$bag."'";
						// 	if (count($kode_bagianx) > $c_lvx) {
						// 		$where.=' OR ';
						// 	}
						// 	$c_lvx++;
						// }
						// $jabatan = $this->model_karyawan->getBagianBawahanNotifWhere($where);
						$c_lvxx=1;
						$wherex = '';
						foreach ($jabatan as $key => $jab) {
							$wherex.="jab.kode_jabatan='".$jab."' AND lem.validasi='2' AND lem.jam_mulai IS NOT NULL";
							if (count($jabatan) > $c_lvxx) {
								$wherex.=' OR ';
							}
							$c_lvxx++;
						}
						$d_lembur=$this->model_karyawan->getDataLemburJabatan($wherex);
						$jumlahVal = count($d_lembur);
						foreach ($d_lembur as $d) {
							$ada.='<li><a href="'.base_url('pages/data_lembur').'"><i class="fa fas fa-calendar-plus fa-fw"></i> '.$d->nama_buat.' | <small style="color:red; font-size:8pt;">'.$d->jum_no.' Karyawan</small> <small class="text-muted pull-right" title="Tanggal Pengajuan Lembur">'.$this->formatter->getDateMonthFormatUser($d->tgl_mulai).'</small></a></li>';
						}
						$ada.='<li class="footer"><a href="'.base_url('pages/data_lembur').'">Tampilkan Semua</a></li>';
					}else{
						$kode_bagianx = $this->dtroot['adm']['list_bagian'];
						$where = '';
						$jabatan = $this->model_karyawan->getBagianBawahanNotif($kode_bagianx);
						if(!empty($jabatan)){
							$c_lv=1;
							foreach ($jabatan as $key => $jab) {
								$where.="jab.kode_jabatan='".$jab."' AND lem.validasi='2' AND lem.jam_mulai IS NOT NULL";
								if (count($jabatan) > $c_lv) {
									$where.=' OR ';
								}
								$c_lv++;
							}
						}
						$d_lembur=$this->model_karyawan->getDataLemburJabatan($where);
						$jumlahVal = count($d_lembur);
						foreach ($d_lembur as $d) {
							$ada.='<li><a href="'.base_url('pages/data_lembur').'"><i class="fa fas fa-calendar-plus fa-fw"></i> '.$d->nama_buat.' | <small style="color:red; font-size:8pt;">'.$d->jum_no.' Karyawan</small> <small class="text-muted pull-right" title="Tanggal Pengajuan Lembur">'.$this->formatter->getDateMonthFormatUser($d->tgl_mulai).'</small></a></li>';
						}
						$ada.='<li class="footer"><a href="'.base_url('pages/data_lembur').'">Tampilkan Semua</a></li>';
					}
				}else{
					$d_lembur=$this->model_karyawan->getDataLemburJabatan("lem.validasi='2' AND lem.jam_mulai IS NOT NULL");
					$jumlahVal = count($d_lembur);
					foreach ($d_lembur as $d) {
						$ada.='<li><a href="'.base_url('pages/data_lembur').'"><i class="fa fas fa-calendar-plus fa-fw"></i> '.$d->nama_buat.' | <small style="color:red; font-size:8pt;">'.$d->jum_no.' Karyawan</small> <small class="text-muted pull-right" title="Tanggal Pengajuan Lembur">'.$this->formatter->getDateMonthFormatUser($d->tgl_mulai).'</small></a></li>';
					}
					$ada.='<li class="footer"><a href="'.base_url('pages/data_lembur').'">Tampilkan Semua</a></li>';
				}
				if ($jumlahVal == 0 || $jumlahVal == null) {
					$ada='<li class="text-center"><small class="text-muted"><i class="icon-close"></i> Tidak Ada Data</small></li><li class="divider"> </li>';
				}
				$dataxx=[	'count'=>$jumlahVal,
						'value'=>$ada,
					];
				// print_r($data);
        		echo json_encode($dataxx);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_lembur_masal()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		// $kode       = $this->input->post('kode_spl');
		$kode       = $this->codegenerator->kodeDataLembur();
		// $kode = 'SPL202012160001';
		$id_karyawan= $this->input->post('karyawan');
		$tanggal    = $this->input->post('tanggal');
		$tgl_awal   = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir  = $this->formatter->getDateFromRange($tanggal,'end');
		$jml_lembur = $this->otherfunctions->getDivTime($tgl_awal,$tgl_akhir);
		$pIst 		= $this->payroll->getPotonganIstirahat($jml_lembur,$tgl_awal,$this->input->post('jenis_lembur'));
		$potongIstirahat = $this->formatter->convertDecimaltoJam($pIst);
		$potonganIstirahat = $this->otherfunctions->getStringInterval($potongIstirahat);
		$t_awal     = explode(" ", $tgl_awal);
		$t_akhir    = explode(" ", $tgl_akhir);
		$token      = $this->rando.uniqid();
		$date_now   = $this->date;
		$dtin       = ['token'=>$token,'date_now'=>$date_now];
		$link       = $this->otherfunctions->companyClientProfile()['link_val_lembur'].$this->codegenerator->encryptChar($dtin);
		$dibuat     = $this->input->post('id_dibuat');
		$diperiksa  = $this->input->post('id_diperiksa');
		$diketahui  = $this->input->post('id_diketahui');
		$pilihan_lembur  = $this->input->post('pilihan_lembur');
		$data_validasi = [
			'kepada'=>'Validator Lembur', 
			'no_lembur'       =>$kode,
			'tanggal'         =>$tanggal,
			'tgl_mulai'       =>$t_awal,
			'tgl_selesai'     =>$t_akhir,
			'jumlah_lembur'   =>$jml_lembur,
			'dibuat_oleh'     =>$this->model_karyawan->getEmpID($this->input->post('id_dibuat'))['nama'].' - '.$this->model_karyawan->getEmpID($this->input->post('id_dibuat'))['nama_jabatan'],
			'diperiksa_oleh'  =>$this->model_karyawan->getEmpID($this->input->post('id_diperiksa'))['nama'].' - '.$this->model_karyawan->getEmpID($this->input->post('id_diperiksa'))['nama_jabatan'],
			'diketahui_oleh'  =>$this->model_karyawan->getEmpID($this->input->post('id_diketahui'))['nama'].' - '.$this->model_karyawan->getEmpID($this->input->post('id_diketahui'))['nama_jabatan'],
			'tgl_buat'        =>$this->input->post('tgl_buat'),
			// 'potong_jam'      =>($this->input->post('jam_istirahat')==1)?'Potong Jam Istirahat':'Tidak Potong Jam Istirahat',
			'potong_jam'      =>$potonganIstirahat,
			'keterangan'      =>$this->input->post('keterangan'),
			'jumlah_karyawan' =>count($id_karyawan),
			'karyawan'        =>$this->otherfunctions->getKaryawanViewEmail($id_karyawan),
			'tgl'             =>$this->date,
			'link'            =>$link,
			'kode'            =>$kode,
			// 'kode'            =>$this->codegenerator->kodeDataLembur(),
			'url'             =>$this->otherfunctions->companyClientProfile()['url'],
			'logo'            =>$this->otherfunctions->companyClientProfile()['logo_url'],
		];
		$email_val=array_filter(array_unique($this->otherfunctions->getEmailValLembur()));
		if ($kode != "") {
			foreach ($id_karyawan as $n) {
				$data=[
					'id_karyawan'=>$n,
					// 'no_lembur'     =>$this->codegenerator->kodeDataLembur(),
					'no_lembur'     =>$kode,
					'tgl_mulai'     =>$t_awal[0],
					'tgl_selesai'   =>$t_akhir[0],
					'jam_mulai'     =>$t_awal[1],
					'jam_selesai'   =>$t_akhir[1],
					'jumlah_lembur' =>$jml_lembur,
					'dibuat_oleh'   =>$dibuat,
					'diperiksa_oleh'=>$diperiksa,
					'diketahui_oleh'=>$diketahui,
					'tgl_buat'      =>$this->formatter->getDateFormatDb($this->input->post('tgl_buat')),
					// 'potong_jam'    =>$this->input->post('jam_istirahat'),
					'potong_jam'    =>$potongIstirahat,
					'kode_customer' =>$this->input->post('kode_customer'),
					'keterangan'    =>$this->input->post('keterangan'),
					'jenis_lembur'  =>$this->input->post('jenis_lembur'),
					'pilihan_lembur'  =>$this->input->post('pilihan_lembur'),
					'validasi'		=> 2,
				];
				$data=array_merge($data,$dtin,$this->model_global->getCreateProperties($this->admin));
				$cekNoSPL = $this->model_presensi->cekLemburNoSPL($kode);
				if(empty($cekNoSPL)){
					// $dbs = $this->model_global->insertQuery($data,'data_lembur');
					$datax = $this->model_global->insertQuery($data,'data_lembur');
				}else{
					$cekNo = $this->model_presensi->cekLemburNoSPL($kode,$dibuat,$diperiksa,$diketahui);
					if($kode == $cekNo['no_lembur'] && $dibuat == $cekNo['dibuat_oleh'] && $diperiksa == $cekNo['diperiksa_oleh'] && $diketahui == $cekNo['diketahui_oleh']){
						$datax = $this->model_global->insertQuery($data,'data_lembur');
					}else{
						$datax=$this->messages->customWarning('No Lembur Sudah Digunakan, Harap Refresh No Lembur');
					}
				}
			}
			// if(isset($email_val)){
			// 	$ci = get_instance();
			// 	$ci->email->initialize($this->otherfunctions->configEmail());
			// 	$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengajuan Lembur Online');
			// 	$ci->email->to($email_val);
			// 	$ci->email->subject('Pengajuan Lembur');
			// 	$body = $this->load->view('email_template/email_lembur_validasi',$data_validasi,TRUE);
			// 	$ci->email->message($body);
			// 	$eml=$this->email->send();
			// }
			// if ($eml && $dbs){
			// 	$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
			// }elseif($dbs){
			// 	$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
			// }elseif($eml){
			// 	$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
			// }else{
			// 	$datax=$this->messages->allFailure();
			// }
			// if($dbs){
			// 	$datax=$this->messages->allGood();
			// }else{
			// 	$datax=$this->messages->allFailure();
			// }
			$bagian= $this->input->post('bagian');
			$pembebanan= $this->input->post('pembebanan');
			$bagian_beban= $this->input->post('bagian_beban');
			if(!empty($bagian)){
				$kode_bagian = (($pembebanan == '1') ? $bagian_beban : $bagian);
				$lamaLembur   = $this->formatter->convertJamtoDecimal($jml_lembur);
				$total_lembur = $lamaLembur*(count($id_karyawan));
				// $bulan = $this->otherfunctions->getDataExplode($tgl_awal,'-','end');
				// $tahun = $this->otherfunctions->getDataExplode($tgl_awal,'-','start');
				// $detailKuota=$this->model_master->getListDetailKuotaLembur(['d.bulan'=>$bulan,'d.tahun'=>$tahun,'a.kode_bagian'=>$bagian],true);
				$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
				$where = 'a.kode_bagian="'.$kode_bagian.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
				$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
				if(!empty($detailKuota)){
					if($pilihan_lembur == 'lembur_project'){
						$dataP=['use_project'=>($detailKuota['use_project']+$total_lembur)];
					}else{
						$dataP=['use_non_project'=>($detailKuota['use_non_project']+$total_lembur)];
					}
					$sisa_kuota = $detailKuota['sisa_kuota'];
					$data=['sisa_kuota'=>($sisa_kuota-$total_lembur)];
					$where=['kode_kuota_lembur'=>$detailKuota['kode_kuota_lembur'],'kode_bagian'=>$kode_bagian];
					$this->model_global->updateQueryNoMsg(array_merge($data, $dataP), 'detail_kuota_lembur',$where);
					foreach ($id_karyawan as $n) {
						$dataH=[
							'id_karyawan'	=>$n,
							'kode_lembur'   =>$kode,
							'kode_kuota'	=>$detailKuota['kode_kuota_lembur'],
							'jam' 			=>$lamaLembur,
							'kode_bagian' 	=>$kode_bagian,
							'pilihan_lembur'=>$pilihan_lembur,
						];
						$dataH=array_merge($dataH,$this->model_global->getCreateProperties($this->admin));
						$this->model_global->insertQueryNoMsg($dataH,'history_kuota_lembur');
					}
				}
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_lembur_trans()
	{
		// $this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id				= $this->input->post('id');
		$no_lembur		= $this->input->post('kode_spl');
		$tanggal 		= $this->input->post('tanggal');
		$kar_old		= $this->input->post('karyawan_old');
		$id_karyawan	= $this->input->post('id_karyawan');
		$val_old		= $this->input->post('validasi_old');
		$tgl_awal 		= $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir 		= $this->formatter->getDateFromRange($tanggal,'end');
		$jml_lembur 	= $this->otherfunctions->getDivTime($tgl_awal,$tgl_akhir);
		$t_awal			= explode(" ", $tgl_awal);
		$t_akhir		= explode(" ", $tgl_akhir);
		$d_kar_old		= $this->otherfunctions->getDataExplode($kar_old,',','all');
		$lamaLembur		= $this->formatter->convertJamtoDecimal($jml_lembur);
		$pIst 			= $this->payroll->getPotonganIstirahat($lamaLembur,$t_awal[0],$this->input->post('jenis_lembur'));
		$potongIstirahat= $this->formatter->convertDecimaltoJam($pIst);
		$pilihan_lembur  = $this->input->post('pilihan_lembur');
		$pilihan_lemburt_old  = $this->input->post('pilihan_lemburt_old');
		// $potonganIstirahat= $this->otherfunctions->getStringInterval($potongIstirahat);
		if ($no_lembur != "") {
			foreach ($id_karyawan as $idk) {
				$data=[
					'id_karyawan'	=>$idk,
					'no_lembur'		=>$no_lembur,
					'tgl_mulai'		=>$t_awal[0],
					'tgl_selesai'	=>$t_akhir[0],
					'jam_mulai'		=>$t_awal[1],
					'jam_selesai'	=>$t_akhir[1],
					'jumlah_lembur'	=>$jml_lembur,
					'validasi'		=>$val_old,
					'dibuat_oleh'	=>$this->input->post('id_dibuat'),
					'diperiksa_oleh'=>$this->input->post('id_diperiksa'),
					'diketahui_oleh'=>$this->input->post('id_diketahui'),
					'tgl_buat'		=>$this->formatter->getDateFormatDb($this->input->post('tgl_buat')),
					'potong_jam'	=>$potongIstirahat,//$this->input->post('jam_istirahat'),
					'kode_customer' =>$this->input->post('kode_customer'),
					'keterangan'	=>$this->input->post('keterangan'),
					'jenis_lembur'  =>$this->input->post('jenis_lembur'),
					'status_potong' =>0,
					'pilihan_lembur' =>$pilihan_lembur,
				];
				if(in_array($idk, $d_kar_old)){
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_lembur',['no_lembur'=>$no_lembur,'id_karyawan'=>$idk]);
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_karyawan)){
							$datax=$this->model_global->deleteQuery('data_lembur',['no_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
						}
					}
				}else{
					$data['id_karyawan']=$idk;
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQuery($data,'data_lembur');
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_karyawan)){
							$datax=$this->model_global->deleteQuery('data_lembur',['no_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
						}
					}
					$data0 = $this->model_karyawan->getListLemburMax(0);
					$data1 = $this->model_karyawan->getListLemburMax(1);
					$id_lembur_0 = $data0['id_data_lembur'];
					$id_lembur_1 = $data1['id_data_lembur'];
					if($data0['no_lembur'] != $data1['no_lembur']){
						unset($data0['id_data_lembur']);
						unset($data1['id_data_lembur']);
						$this->model_global->updateQueryNoMsg($data0,'data_lembur',['id_data_lembur'=>$id_lembur_1]);
						$this->model_global->updateQueryNoMsg($data1,'data_lembur',['id_data_lembur'=>$id_lembur_0]);
						$datax = $this->messages->allGood();
					}
				}
			}
			$kode_bagian = $this->input->post('kode_bagian');
			$lama_lembur_old = $this->input->post('lama_lembur_old');
			$count_old = $this->input->post('count_old');
			$beban = $this->input->post('pembebanan');
			$beban_old = $this->input->post('pembebanan_old');
			$bagian_beban_new = $this->input->post('bagian_beban');
			$bagian_beban_old = $this->input->post('bagian_beban_edit_old');
			if(!empty($kode_bagian)){
				if($beban_old == '0' && $beban == '0'){
					$bagian_kar = $kode_bagian;
					$bagian_bold = $kode_bagian;
					$bagian_bnew = null;
				}elseif($beban_old == '0' && $beban == '1'){
					$bagian_kar = $bagian_beban_new;
					$bagian_bold = $kode_bagian;
					$bagian_bnew = $bagian_beban_new;
				}elseif($beban_old == '1' && $beban == '0'){
					$bagian_kar = $kode_bagian;
					$bagian_bold = $bagian_beban_old;
					$bagian_bnew = $kode_bagian;
				}elseif($beban_old == '1' && $beban == '1'){
					$bagian_kar = $bagian_beban_new;
					$bagian_bold = $bagian_beban_old;
					$bagian_bnew = $bagian_beban_new;
				}
				if(!empty($id_karyawan)){
					foreach ($id_karyawan as $idk) {
						if(in_array($idk, $d_kar_old)){
							$datah = ['jam'=>$lamaLembur,'kode_bagian' 	=>$bagian_kar,];
							$datah=array_merge($datah,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($datah,'history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$idk]);
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}else{
							$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
							$where = 'a.kode_bagian="'.$bagian_kar.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
							$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
							$dataH=[
								'id_karyawan'	=>$idk,
								'kode_lembur'   =>$no_lembur,
								'kode_kuota'	=>$detailKuota['kode_kuota_lembur'],
								'jam' 			=>$lamaLembur,
								'kode_bagian' 	=>$bagian_kar,
							];
							$dataH=array_merge($dataH,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($dataH,'history_kuota_lembur');
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}
					}
				}
				if(empty($bagian_bnew)){
					$total_lembur = $lamaLembur*(count($id_karyawan));
					$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
					$wherex = 'a.kode_bagian="'.$bagian_bold.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuotax=$this->model_master->getListDetailKuotaLembur($wherex,true);
					// === pilihan lembur ===
					if($pilihan_lembur == $pilihan_lemburt_old){
						$choose = ($pilihan_lembur == 'lembur_project') ?'use_project' : 'use_non_project';
						$dChoose = $detailKuotax[$choose]-($lama_lembur_old*$count_old);
						$dataP=[$choose=>($dChoose+$total_lembur)];
					}else{
						$choosex = ($pilihan_lembur == 'lembur_project') ?'use_non_project' : 'use_project';
						$csx = ($pilihan_lembur == 'lembur_project') ?'use_project' : 'use_non_project';
						$dChoosex = $detailKuotax[$choosex]-($lama_lembur_old*$count_old);
						$dChox = $detailKuotax[$csx]+$total_lembur;
						$dataP=[$csx=>($dChox), $choosex=>$dChoosex];
					}
					// ========================
					$sisa_kuota = $detailKuotax['sisa_kuota']+($lama_lembur_old*$count_old);
					$dataxx=['sisa_kuota'=>($sisa_kuota-$total_lembur)];
					$wherexx=['kode_kuota_lembur'=>$detailKuotax['kode_kuota_lembur'],'kode_bagian'=>$bagian_bold];
					$this->model_global->updateQueryNoMsg(array_merge($dataxx, $dataP),'detail_kuota_lembur',$wherexx);
				}else{
					$total_lembur = $lamaLembur*(count($id_karyawan));
					$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
					$wherex = 'a.kode_bagian="'.$bagian_bold.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuotax=$this->model_master->getListDetailKuotaLembur($wherex,true);
					// === pilihan lembur ===
					if($pilihan_lembur == $pilihan_lemburt_old){
						$choose = ($pilihan_lembur == 'lembur_project') ?'use_project' : 'use_non_project';
						$dChoose = $detailKuotax[$choose]-($lama_lembur_old*$count_old);
						$dataP=[$choose=>$dChoose];
					}else{
						$choosex = ($pilihan_lembur == 'lembur_project') ?'use_non_project' : 'use_project';
						$csx = ($pilihan_lembur == 'lembur_project') ?'use_project' : 'use_non_project';
						$dChoosex = $detailKuotax[$choosex]-($lama_lembur_old*$count_old);
						$dChox = $detailKuotax[$csx];
						$dataP=[$csx=>($dChox), $choosex=>$dChoosex];
					}
					$dataxx=['sisa_kuota'=>($detailKuotax['sisa_kuota']+($lama_lembur_old*$count_old))];
					$wherexx=['kode_kuota_lembur'=>$detailKuotax['kode_kuota_lembur'],'kode_bagian'=>$bagian_bold];
					$this->model_global->updateQueryNoMsg(array_merge($dataxx, $dataP),'detail_kuota_lembur',$wherexx);
					// ======================== EDIT KE BAGIAN BARU ==========================

					$where = 'a.kode_bagian="'.$bagian_bnew.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
					// === pilihan lembur ===
					if($pilihan_lembur == $pilihan_lemburt_old){
						$choose = ($pilihan_lembur == 'lembur_project') ?'use_project' : 'use_non_project';
						$dChoose = $detailKuota[$choose];//-($lama_lembur_old*$count_old);
						$dataPn=[$choose=>($dChoose+$total_lembur)];
					}else{
						$choosex = ($pilihan_lembur == 'lembur_project') ?'use_non_project' : 'use_project';
						$csx = ($pilihan_lembur == 'lembur_project') ?'use_project' : 'use_non_project';
						$dChoosex = $detailKuota[$choosex];//-($lama_lembur_old*$count_old);
						$dChox = $detailKuota[$csx]+$total_lembur;
						$dataPn=[$csx=>($dChox), $choosex=>$dChoosex];
					}
					// echo '<pre>';
					// print_r($dataP);
					// print_r($dataPn);
					$data=['sisa_kuota'=>($detailKuota['sisa_kuota']-$total_lembur)];
					$where=['kode_kuota_lembur'=>$detailKuota['kode_kuota_lembur'],'kode_bagian'=>$bagian_bnew];
					$this->model_global->updateQueryNoMsg(array_merge($data, $dataPn),'detail_kuota_lembur',$where);
				}
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_no_spl()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id_data_lembur');
		$no_spl=$this->input->post('no_spl');
		foreach ($id as $key => $id_data_lembur) {
			$data=array_merge(['no_lembur'=>$no_spl[$key]],$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_lembur',['id_data_lembur'=>$id_data_lembur]);
		}
		echo json_encode($datax);
	}
	public function validasi_lembur()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$no_lembur=$this->input->post('no_lembur');
		$val_db=$this->input->post('validasi_db');
		$vali=$this->input->post('validasi');
		$tgl_val=$this->input->post('tgl_val');
		$tgl_old=$this->input->post('tgl_old');
		$potong=$this->input->post('potong');
		$catatan=$this->input->post('catatan');
		$samakan=$this->input->post('samakan');
		$jenis_lembur=$this->input->post('jenis_lembur');
		$tgl_awal = $this->formatter->getDateFromRange($tgl_val,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tgl_val,'end');
		$tgl_awal_old = $this->formatter->getDateFromRange($tgl_old,'start');
		$tgl_akhir_old = $this->formatter->getDateFromRange($tgl_old,'end');
		$dataLembur  =$this->model_karyawan->getLemburTrans($no_lembur);
		// print_r($tgl_val);
		$id_karyawan=[];
		$emailCreateBy=[];
		foreach ($dataLembur as $dl) {
			$id_karyawan[]=$dl->id_karyawan;
			$emailCreateBy[]=$this->model_admin->getAdminRowArray($dl->create_by)['email'];
		}
		if($val_db==2 && $vali==1 || $val_db==0 && $vali==1){
			if($samakan==1){
				$jml_lembur = $this->otherfunctions->getDivTime($tgl_awal_old,$tgl_akhir_old,'lembur');
			}else{
				$jml_lembur = $this->otherfunctions->getDivTime($tgl_awal,$tgl_akhir,'lembur');
			}
			$tglvalidasi     = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
			$lamaLembur      = $this->formatter->convertJamtoDecimal($jml_lembur);
			$pIst            = $this->payroll->getPotonganIstirahat($lamaLembur,$tglvalidasi,$jenis_lembur);
			$potongIstirahat = $this->formatter->convertDecimaltoJam($pIst);
			if($samakan==1){
				$data=[	'validasi'=>$vali,'val_tgl_mulai'=>$tgl_awal_old,'val_tgl_selesai'=>$tgl_akhir_old,'val_jumlah_lembur'=>$jml_lembur,'val_potong_jam'=>$potongIstirahat,'val_catatan'=>$catatan];
			}else{
				$data=[	'validasi'=>$vali,'val_tgl_mulai'=>$tgl_awal,'val_tgl_selesai'=>$tgl_akhir,'val_jumlah_lembur'=>$jml_lembur,'val_potong_jam'=>$potongIstirahat,'val_catatan'=>$catatan];
			}
			// $dataPresensi=['no_spl'=>$no_lembur,];
		}elseif($val_db==1 && $vali==0){
			$data=[	'validasi'=>$vali,'val_tgl_mulai'=>null,'val_tgl_selesai'=>null,'val_jumlah_lembur'=>null,'val_potong_jam' =>null,'val_catatan'=>null,];
			// $dataPresensi=['no_spl'=>null,];
		}else{
			$data=[	'validasi'=>$vali,'val_tgl_mulai'=>null,'val_tgl_selesai'=>null,'val_jumlah_lembur'=>null,'val_potong_jam' =>null,'val_catatan'=>null,];
			// $dataPresensi=['no_spl'=>null,];
		}
		$where=['no_lembur'=>$no_lembur,];
		$dbs=$this->model_global->updateQuery($data,'data_lembur',$where);
		foreach ($dataLembur as $d) {
			if($vali==1){
				$value['no_spl']=$d->no_lembur;
			}else{
				$value['no_spl']=null;
			}
			$value['id_karyawan']=$d->id_karyawan;
			$value['tanggal']=$d->tgl_mulai;
			$cek=$this->model_karyawan->checkPresensiEmpDate($d->id_karyawan,$value['tanggal']);
			if(!empty($cek)){
				$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
				$this->model_global->updateQueryNoMsg($value,'data_presensi',['id_karyawan'=>$d->id_karyawan,'tanggal'=>$value['tanggal']]);
			}else{
				$value=array_merge($value,$this->model_global->getCreateProperties($this->admin));
				$this->model_global->insertQueryNoMsg($value,'data_presensi');
			}
			$potonganIstirahatval= $this->otherfunctions->getStringInterval($d->val_potong_jam);
			$emp=$this->model_karyawan->getEmpID($d->id_karyawan);
			$tanggal	 =$this->formatter->getDateMonthFormatUser($d->tgl_mulai).' '.$this->formatter->timeFormatUser($d->jam_mulai).' -
					<br>'.$this->formatter->getDateMonthFormatUser($d->tgl_selesai).' '.$this->formatter->timeFormatUser($d->jam_selesai);
			$data_emp = ['kepada'=>$emp['nama'], 
						'nama'           =>$emp['nama'],
						'nik'            =>$emp['nik'],
						'jabatan'        =>$emp['nama_jabatan'],
						'loker'          =>$emp['nama_loker'],
						'tanggal'        =>$tanggal,
						'no_lembur'      =>$d->no_lembur,
						'lama_lembur'    =>$this->otherfunctions->getStringInterval($d->jumlah_lembur),
						'potong_jam'     =>($d->potong_jam != "00:00")?$potonganIstirahatval:'Tidak Potong Jam Istirahat',
						'keterangan'     =>$d->keterangan,
						'jumlah_karyawan'=>count($id_karyawan),
						'karyawan'       =>$this->otherfunctions->getKaryawanViewEmail($id_karyawan),
						'tgl'=>$this->date,
						'url'=>$this->otherfunctions->companyClientProfile()['url'],
						'logo'=>$this->otherfunctions->companyClientProfile()['logo_url'],
					];
			$email_emp=$emp['email'];
			// if(!empty($emp['email']) && $vali==1){
			// 	$ci = get_instance();
			// 	$ci->email->initialize($this->otherfunctions->configEmail());
			// 	$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Tugas Lembur Karyawan Online');
			// 	$list = array($email_emp);
			// 	$ci->email->to($email_emp);
			// 	$ci->email->subject('Tugas Lembur Karyawan Online');
			// 	$body = $this->load->view('email_template/email_perintah_lembur',$data_emp,TRUE);
			// 	$ci->email->message($body);
			// 	$eml=$this->email->send();
			// }else{
			// 	$eml=null;
			// }
		}
		// if ($eml && $dbs){
		// 	$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
		// }elseif($dbs){
		// 	$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
		// }elseif($eml){
		// 	$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
		// }else{
		// 	$datax=$this->messages->allFailure();
		// }
		if($dbs){
			$datax=$this->messages->allGood();
		}else{
			$datax=$this->messages->allFailure();
		}
		echo json_encode($datax);
	}
	public function editPotongJamIstirahat()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$no_spl=$this->input->post('no_spl');
		$potong_jam=$this->input->post('potong_jam');
		$datax=$this->model_global->updateQuery(['val_potong_jam'=>$potong_jam,'status_potong'=>1],'data_lembur',['no_lembur'=>$no_spl]);
		echo json_encode($datax);
	}
	public function data_lembur_validasi_massal()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$datax['data']=[];
				$data_lembur=$this->model_karyawan->getListLemburTransWhere(['a.validasi'=>2]);
					$no=1;
					foreach ($data_lembur as $dl) {
						$tgl_lembur=$this->formatter->getDateMonthFormatUser($dl->tgl_mulai).' '.$this->formatter->timeFormatUser($dl->jam_mulai).' - <br>'.$this->formatter->getDateMonthFormatUser($dl->tgl_selesai).' '.$this->formatter->timeFormatUser($dl->jam_selesai);
						$cekBox ='<input type="hidden" name="no_lembur[]" value="'.$dl->no_lembur.'">
							<span id="status_off'.$no.'" style="font-size: 20px;"><i class="fa fa-square" aria-hidden="true"></i></span>
							<span id="status_on'.$no.'" style="display: none; font-size: 20px;"><i class="fa fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
							<input type="hidden" id="status_id'.$no.'" name="status_val[]" value="0">';
						$datax['data'][]=[
							$no,
							$dl->no_lembur,
							$tgl_lembur,
							$dl->nama_buat,
							$this->formatter->getDateMonthFormatUser($dl->tgl_buat),
							$cekBox,
						];
					$no++;
				}
			}elseif($usage == 'tabel'){
				$tabel='';
				$tabel.='
					<div style="max-height: 400px; overflow: auto;">
					<table class="table table-bordered table-striped data-table">
						<thead>
							<tr class="bg-blue">
								<th>No.</th>
								<th>No Lembur</th>
								<th>Tanggal Lembur</th>
								<th>Dibuat</th>
								<th>Tanggal Buat</th>
								<th>
									<span id="status_off_all" style="font-size: 20px;"><i class="fa fa-square" aria-hidden="true"></i></span>
									<span id="status_on_all" style="display: none; font-size: 20px;"><i class="fa fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;<br>
									<span> Pilih Semua</span>
									<input type="hidden" id="status_id_all" name="status_all" value="">
								</th>
							</tr>
						</thead>
						<tbody>';
						$data_lembur=$this->model_karyawan->getListLemburTransWhere(['a.validasi'=>2]);
						if(!empty($data_lembur)){
							$no=1;
							foreach ($data_lembur as $dl) {
								$tgl_lembur=$this->formatter->getDateMonthFormatUser($dl->tgl_mulai).' '.$this->formatter->timeFormatUser($dl->jam_mulai).' - <br>'.$this->formatter->getDateMonthFormatUser($dl->tgl_selesai).' '.$this->formatter->timeFormatUser($dl->jam_selesai);
								$cekBox ='<input type="hidden" name="no_lembur[]" value="'.$dl->no_lembur.'">
									<span id="status_off'.$no.'" style="font-size: 20px;"><i class="fa fa-square" aria-hidden="true"></i></span>
									<span id="status_on'.$no.'" style="display: none; font-size: 20px;"><i class="fa fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
									<input type="hidden" id="status_id'.$no.'" name="status_val[]" value="0">';
								$tabel.='<tr>
									<td>'.$no.'</td>
									<td>'.$dl->no_lembur.'</td>
									<td>'.$tgl_lembur.'</td>
									<td>'.$dl->nama_buat.'</td>
									<td>'.$this->formatter->getDateMonthFormatUser($dl->tgl_buat).'</td>
									<td>'.$cekBox.'</td>
								</tr>';
								$no++;
							}
						}else{
							$tabel.='<tr>
									<td colspan="6" class="text-center">Tidak Ada Data</td>
								</tr>';
						}
						$tabel.='</tbody>
					</table>';
				$datax=['tabel'=>$tabel];
			}elseif($usage == 'jum_data'){
				$data_lembur=$this->model_karyawan->getListLemburTransWhere(['a.validasi'=>2]);
				$datax=['jumlah'=>count($data_lembur)];
			}
			echo json_encode($datax);
		}
	}
	public function do_validasi_lembur_massal()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$kode = $this->input->post('kode');
		$tabel = $this->input->post('tabel');
		parse_str($this->input->post('tabel'), $post_form);
		$status_all = $post_form['status_all'];
		if(isset($post_form['no_lembur'])){
			$no_lembur = $post_form['no_lembur'];
			$status_val = $post_form['status_val'];
			if($kode == 1){
				if($status_all == 1){
					foreach ($no_lembur as $knl_all => $vnl_all) {
						$data = ['validasi'=>1];
						$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
						$datax = $this->model_global->updateQuery($data,'data_lembur',['no_lembur'=>$vnl_all]);
					}
				}else{
					foreach ($no_lembur as $knl => $vnl) {
						if($status_val[$knl] == 1){
							$data = ['validasi'=>1];
							$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($data,'data_lembur',['no_lembur'=>$vnl]);
						}else{
							$datax = $this->messages->customFailure('Tidak Ada Data yang dipilih');
						}
					}
				}
			}else{
				if($status_all == 1){
					foreach ($no_lembur as $knlx_all => $vnlx_all) {
						$data = ['validasi'=>0];
						$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
						$datax = $this->model_global->updateQuery($data,'data_lembur',['no_lembur'=>$vnlx_all]);
					}
				}else{
					foreach ($no_lembur as $knlx => $vnlx) {
						if($status_val[$knlx] == 1){
							$data = ['validasi'=>0];
							$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($data,'data_lembur',['no_lembur'=>$vnlx]);
						}else{
							$datax = $this->messages->customFailure('Tidak Ada Data yang dipilih');
						}
					}
				}
			}
		}else{
			$datax = $this->messages->customFailure('Tidak Ada yang perlu di validasi');
		}
		echo json_encode($datax);
	}
	public function do_reset_nomor_lembur()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data0 = $this->model_karyawan->getListLemburMax(0);
		$data1 = $this->model_karyawan->getListLemburMax(1);
		$id_lembur_0 = $data0['id_data_lembur'];
		$id_lembur_1 = $data1['id_data_lembur'];
		if($data0['no_lembur'] != $data1['no_lembur']){
			unset($data0['id_data_lembur']);
			unset($data1['id_data_lembur']);
			$this->model_global->updateQueryNoMsg($data0,'data_lembur',['id_data_lembur'=>$id_lembur_1]);
			$this->model_global->updateQueryNoMsg($data1,'data_lembur',['id_data_lembur'=>$id_lembur_0]);
			$datax = $this->messages->allGood();
		}else{
			$datax = $this->messages->customFailure('Tidak Ada yang perlu di Reset');
		}
		echo json_encode($datax);
	}
	function add_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('kode_spl');
		$id_karyawan=$this->input->post('karyawan');
		$tanggal = $this->input->post('tanggal');
		$tgl_awal = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tanggal,'end');
		$jml_lembur = $this->otherfunctions->getDivTime($tgl_awal,$tgl_akhir);
		$t_awal=explode(" ", $tgl_awal);
		$t_akhir=explode(" ", $tgl_akhir);
		if ($kode != "") {
			$data=['id_karyawan'=>$id_karyawan,
				'no_lembur'=>$kode,
				'tgl_mulai'=>$t_awal[0],
				'tgl_selesai'=>$t_akhir[0],
				'jam_mulai'=>$t_awal[1],
				'jam_selesai'=>$t_akhir[1],
				'jumlah_lembur'=>$jml_lembur,
				// 'dibuat_oleh'=>$this->input->post('id_dibuat'),
				'dibuat_oleh'=>$this->admin,
				'diperiksa_oleh'=>$this->input->post('id_diperiksa'),
				'diketahui_oleh'=>$this->input->post('id_diketahui'),
				'tgl_buat'=>$this->formatter->getDateFormatDb($this->input->post('tgl_buat')),
				// 'potong_jam'=>$this->input->post('jam_istirahat'),
				'kode_customer' =>$this->input->post('kode_customer'),
				'keterangan'=>$this->input->post('keterangan'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->insertQuery($data,'data_lembur');
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_lembur_trans2	(){
		
		// $this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$no_lembur=$this->input->post('kode_spl');
		$tanggal = $this->input->post('tanggal');
		$kar_old=$this->input->post('karyawan_old');
		$id_karyawan=$this->input->post('id_karyawan');
		$val_old=$this->input->post('validasi_old');
		$tgl_awal = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tanggal,'end');
		$jml_lembur = $this->otherfunctions->getDivTime($tgl_awal,$tgl_akhir);
		$t_awal=explode(" ", $tgl_awal);
		$t_akhir=explode(" ", $tgl_akhir);
		$d_kar_old=$this->otherfunctions->getDataExplode($kar_old,',','all');
		$lamaLembur= $this->formatter->convertJamtoDecimal($jml_lembur);
		$pIst 		= $this->payroll->getPotonganIstirahat($lamaLembur,$t_awal[0],$this->input->post('jenis_lembur'));
		$potongIstirahat= $this->formatter->convertDecimaltoJam($pIst);
		// $potonganIstirahat= $this->otherfunctions->getStringInterval($potongIstirahat);
		if ($no_lembur != "") {
			foreach ($id_karyawan as $idk) {
				$data=[
					'id_karyawan'	=>$idk,
					'no_lembur'		=>$no_lembur,
					'tgl_mulai'		=>$t_awal[0],
					'tgl_selesai'	=>$t_akhir[0],
					'jam_mulai'		=>$t_awal[1],
					'jam_selesai'	=>$t_akhir[1],
					'jumlah_lembur'	=>$jml_lembur,
					'validasi'		=>$val_old,
					'dibuat_oleh'	=>$this->input->post('id_dibuat'),
					'diperiksa_oleh'=>$this->input->post('id_diperiksa'),
					'diketahui_oleh'=>$this->input->post('id_diketahui'),
					'tgl_buat'		=>$this->formatter->getDateFormatDb($this->input->post('tgl_buat')),
					'potong_jam'	=>$potongIstirahat,//$this->input->post('jam_istirahat'),
					'kode_customer' =>$this->input->post('kode_customer'),
					'keterangan'	=>$this->input->post('keterangan'),
					'jenis_lembur'  =>$this->input->post('jenis_lembur'),
					'status_potong' =>0,
				];
				if(in_array($idk, $d_kar_old)){
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_lembur',['no_lembur'=>$no_lembur,'id_karyawan'=>$idk]);
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_karyawan)){
							$datax=$this->model_global->deleteQuery('data_lembur',['no_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
						}
					}
				}else{
					$data['id_karyawan']=$idk;
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQuery($data,'data_lembur');
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_karyawan)){
							$datax=$this->model_global->deleteQuery('data_lembur',['no_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
						}
					}
					$data0 = $this->model_karyawan->getListLemburMax(0);
					$data1 = $this->model_karyawan->getListLemburMax(1);
					$id_lembur_0 = $data0['id_data_lembur'];
					$id_lembur_1 = $data1['id_data_lembur'];
					if($data0['no_lembur'] != $data1['no_lembur']){
						unset($data0['id_data_lembur']);
						unset($data1['id_data_lembur']);
						$this->model_global->updateQueryNoMsg($data0,'data_lembur',['id_data_lembur'=>$id_lembur_1]);
						$this->model_global->updateQueryNoMsg($data1,'data_lembur',['id_data_lembur'=>$id_lembur_0]);
						$datax = $this->messages->allGood();
					}
				}
			}
			$kode_bagian = $this->input->post('kode_bagian');
			$lama_lembur_old = $this->input->post('lama_lembur_old');
			$count_old = $this->input->post('count_old');
			$beban = $this->input->post('pembebanan');
			$beban_old = $this->input->post('pembebanan_old');
			$bagian_beban_new = $this->input->post('bagian_beban');
			$bagian_beban_old = $this->input->post('bagian_beban_edit_old');
			echo '<pre>';
			// print_r($beban);
			echo 'Beban OLD : '.$beban_old.'<br>';
			echo 'Beban NEW : '.$beban.'<br>';
			echo 'Bagian : '.$kode_bagian.'<br>';
			echo 'NO SPL : '.$no_lembur.'<br>';
			echo 'Bagian Beban OLD : '.$bagian_beban_old.'<br>';
			echo 'Bagian Beban NEW : '.$bagian_beban_new.'<br>';
			if(!empty($kode_bagian)){
				if($beban_old == '0' && $beban == '0'){
					$bagian_kar = $kode_bagian;
					$bagian_bold = $kode_bagian;
					$bagian_bnew = null;
					foreach ($id_karyawan as $idk) {
						if(in_array($idk, $d_kar_old)){
							$datah = ['jam'=>$lamaLembur,'kode_bagian' 	=>$kode_bagian];
							$datah=array_merge($datah,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($datah,'history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$idk]);
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}else{
							$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
							$where = 'a.kode_bagian="'.$kode_bagian.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
							$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
							$dataH=[
								'id_karyawan'	=>$idk,
								'kode_lembur'   =>$no_lembur,
								'kode_kuota'	=>$detailKuota['kode_kuota_lembur'],
								'jam' 			=>$lamaLembur,
								'kode_bagian' 	=>$kode_bagian,
							];
							$dataH=array_merge($dataH,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($dataH,'history_kuota_lembur');
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}
					}
					$total_lembur = $lamaLembur*(count($id_karyawan));
					$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
					$where = 'a.kode_bagian="'.$kode_bagian.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
					$sisa_kuota = $detailKuota['sisa_kuota']+($lama_lembur_old*$count_old);
					$data=['sisa_kuota'=>($sisa_kuota-$total_lembur)];
					$where=['kode_kuota_lembur'=>$detailKuota['kode_kuota_lembur'],'kode_bagian'=>$kode_bagian];
					$this->model_global->updateQueryNoMsg($data,'detail_kuota_lembur',$where);
				}elseif($beban_old == '0' && $beban == '1'){
					$bagian_kar = $bagian_beban_new;
					$bagian_bold = $kode_bagian;
					$bagian_bnew = $bagian_beban_new;
					foreach ($id_karyawan as $idk) {
						if(in_array($idk, $d_kar_old)){
							$datah = ['jam'=>$lamaLembur,'kode_bagian' 	=>$bagian_beban_new,];
							$datah=array_merge($datah,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($datah,'history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$idk]);
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}else{
							$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
							$where = 'a.kode_bagian="'.$bagian_beban_new.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
							$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
							$dataH=[
								'id_karyawan'	=>$idk,
								'kode_lembur'   =>$no_lembur,
								'kode_kuota'	=>$detailKuota['kode_kuota_lembur'],
								'jam' 			=>$lamaLembur,
								'kode_bagian' 	=>$bagian_beban_new,
							];
							$dataH=array_merge($dataH,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($dataH,'history_kuota_lembur');
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}
					}
					//update bagian old
					$total_lembur = $lamaLembur*(count($id_karyawan));
					$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
					$wherex = 'a.kode_bagian="'.$kode_bagian.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuotax=$this->model_master->getListDetailKuotaLembur($wherex,true);
					$dataxx=['sisa_kuota'=>($detailKuotax['sisa_kuota']+($lama_lembur_old*$count_old))];
					$wherexx=['kode_kuota_lembur'=>$detailKuotax['kode_kuota_lembur'],'kode_bagian'=>$kode_bagian];
					$this->model_global->updateQueryNoMsg($dataxx,'detail_kuota_lembur',$wherexx);
					//update bagian new
					$where = 'a.kode_bagian="'.$bagian_beban_new.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
					$data=['sisa_kuota'=>($detailKuota['sisa_kuota']-$total_lembur)];
					$where=['kode_kuota_lembur'=>$detailKuota['kode_kuota_lembur'],'kode_bagian'=>$bagian_beban_new];
					$this->model_global->updateQueryNoMsg($data,'detail_kuota_lembur',$where);
				}elseif($beban_old == '1' && $beban == '0'){
					$bagian_kar = $kode_bagian;
					$bagian_bold = $bagian_beban_old;
					$bagian_bnew = $kode_bagian;
					foreach ($id_karyawan as $idk) {
						if(in_array($idk, $d_kar_old)){
							$datah = ['jam'=>$lamaLembur,'kode_bagian' 	=>$kode_bagian,];
							$datah=array_merge($datah,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($datah,'history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$idk]);
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}else{
							$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
							$where = 'a.kode_bagian="'.$kode_bagian.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
							$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
							$dataH=[
								'id_karyawan'	=>$idk,
								'kode_lembur'   =>$no_lembur,
								'kode_kuota'	=>$detailKuota['kode_kuota_lembur'],
								'jam' 			=>$lamaLembur,
								'kode_bagian' 	=>$kode_bagian,
							];
							$dataH=array_merge($dataH,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($dataH,'history_kuota_lembur');
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}
					}
					//update bagian old
					$total_lembur = $lamaLembur*(count($id_karyawan));
					$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
					$wherex = 'a.kode_bagian="'.$bagian_beban_old.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuotax=$this->model_master->getListDetailKuotaLembur($wherex,true);
					$dataxx=['sisa_kuota'=>($detailKuotax['sisa_kuota']+($lama_lembur_old*$count_old))];
					$wherexx=['kode_kuota_lembur'=>$detailKuotax['kode_kuota_lembur'],'kode_bagian'=>$bagian_beban_old];
					$this->model_global->updateQueryNoMsg($dataxx,'detail_kuota_lembur',$wherexx);
					//update bagian new
					$where = 'a.kode_bagian="'.$kode_bagian.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
					$data=['sisa_kuota'=>($detailKuota['sisa_kuota']-$total_lembur)];
					$where=['kode_kuota_lembur'=>$detailKuota['kode_kuota_lembur'],'kode_bagian'=>$kode_bagian];
					$this->model_global->updateQueryNoMsg($data,'detail_kuota_lembur',$where);
				}elseif($beban_old == '1' && $beban == '1'){
					$bagian_kar = $bagian_beban_new;
					$bagian_bold = $bagian_beban_old;
					$bagian_bnew = $bagian_beban_new;
					foreach ($id_karyawan as $idk) {
						if(in_array($idk, $d_kar_old)){
							$datah = ['jam'=>$lamaLembur,'kode_bagian' 	=>$bagian_beban_new,];
							$datah=array_merge($datah,$this->model_global->getUpdateProperties($this->admin));
							$datax = $this->model_global->updateQuery($datah,'history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$idk]);
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}else{
							$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
							$where = 'a.kode_bagian="'.$bagian_beban_new.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
							$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
							$dataH=[
								'id_karyawan'	=>$idk,
								'kode_lembur'   =>$no_lembur,
								'kode_kuota'	=>$detailKuota['kode_kuota_lembur'],
								'jam' 			=>$lamaLembur,
								'kode_bagian' 	=>$bagian_beban_new,
							];
							$dataH=array_merge($dataH,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($dataH,'history_kuota_lembur');
							foreach ($d_kar_old as $k_old){
								if(!in_array($k_old, $id_karyawan)){
									$datax=$this->model_global->deleteQuery('history_kuota_lembur',['kode_lembur'=>$no_lembur,'id_karyawan'=>$k_old]);
								}
							}
						}
					}
					//update bagian old
					$total_lembur = $lamaLembur*(count($id_karyawan));
					$tanggal = $this->otherfunctions->getDataExplode($tgl_awal,' ','start');
					$wherex = 'a.kode_bagian="'.$bagian_beban_old.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuotax=$this->model_master->getListDetailKuotaLembur($wherex,true);
					$dataxx=['sisa_kuota'=>($detailKuotax['sisa_kuota']+($lama_lembur_old*$count_old))];
					$wherexx=['kode_kuota_lembur'=>$detailKuotax['kode_kuota_lembur'],'kode_bagian'=>$bagian_beban_old];
					$this->model_global->updateQueryNoMsg($dataxx,'detail_kuota_lembur',$wherexx);
					//update bagian new
					$where = 'a.kode_bagian="'.$bagian_beban_new.'" AND d.status=1 AND "'.$tanggal.'" BETWEEN d.tgl_mulai AND d.tgl_selesai';
					$detailKuota=$this->model_master->getListDetailKuotaLembur($where,true);
					$data=['sisa_kuota'=>($detailKuota['sisa_kuota']-$total_lembur)];
					$where=['kode_kuota_lembur'=>$detailKuota['kode_kuota_lembur'],'kode_bagian'=>$bagian_beban_new];
					$this->model_global->updateQueryNoMsg($data,'detail_kuota_lembur',$where);
				}
				// $bulan = $this->otherfunctions->getDataExplode($tgl_awal,'-','end');
				// $tahun = $this->otherfunctions->getDataExplode($tgl_awal,'-','start');
				// $detailKuota=$this->model_master->getListDetailKuotaLembur(['d.bulan'=>$bulan,'d.tahun'=>$tahun,'a.kode_bagian'=>$bagian],true);
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		// echo json_encode($datax);
	}
//================================================ DATA LEMBUR per karyawan =======================================================
	public function data_lembur_kar()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getListLembur();
				}elseif($param == 'tab'){
					$where = ['param'=>$param,'bagian'=>null,'unit'=>null];
					$data=$this->model_karyawan->getListLembur('search',$where);
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$tanggal = $this->input->post('tanggal');
					$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal];
					$data=$this->model_karyawan->getListLembur('search',$where);
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_data_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
			        if (isset($access['l_ac']['del'])) {
			            $delete = '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal_kar('.$d->id_data_lembur.')><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ';
			        }
        			$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_kar('.$d->id_data_lembur.')><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					$datax['data'][]=[
						$d->id_data_lembur,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_bagian,
						$d->nama_loker,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						$d->jum,
						$info.$delete,
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_data_lembur');
				$data=$this->model_karyawan->getLembur($id);
				foreach ($data as $d) {
					$tabel_lembur='';
					$tabel_lembur.='
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nomor Lembur</th>
          							<th>Mulai</th>
          							<th>Selesai</th>
          							<th>Lama Lembur</th>
          							<th>Kode Customer</th>
          							<th>Keterangan</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_lembur=$this->model_karyawan->getListLemburNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_lembur as $dl) {
          							$jml_lmbr=(isset($dl->jumlah_lembur)) ? $dl->jumlah_lembur:$this->otherfunctions->getMark();
          							$tabel_lembur.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$dl->no_lembur.'</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($dl->tgl_mulai).' - '.$this->formatter->timeFormatUser($dl->jam_mulai).' WIB</td>
          							<td>'.$this->formatter->getDateMonthFormatUser($dl->tgl_selesai).' - '.$this->formatter->timeFormatUser($dl->jam_selesai).' WIB</td>
          							<td>'.$jml_lmbr.'</td>
          							<td>'.$dl->kode_customer.'</td>
          							<td>'.$dl->keterangan.'</td>
          						</tr>';
          						$no++;
          					}
	          				$tabel_lembur.='</tbody>
	          			</table>';
					$datax=[
						'id'=>$d->id_data_lembur,
						'table_lembur'=>$tabel_lembur,
						'loker'=>$d->nama_loker,
						'jabatan'=>$d->nama_jabatan,
						'bagian'=>$d->nama_bagian,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'status_emp'=>$d->status_emp,
						'foto'=> base_url($d->foto),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodeDataLemburPlan();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}	
	public function view_lembur_kar()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListLemburNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_data_lembur,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$dibuat=($d->jbt_buat != null) ? ' - '.$d->jbt_buat : null;
					$diperiksa=($d->jbt_periksa != null) ? ' - '.$d->jbt_periksa : null;
					$diketahui=($d->jbt_ketahui != null) ? ' - '.$d->jbt_ketahui : null;
					if($d->potong_jam == 1){
						$potong = '<span class="text-success">Dipotong Jam Istirahat</span>';
					}else{
						$potong = '<span class="text-danger">Tidak dipotong Jam Istirahat</span>';
					}
					$datax['data'][]=[
						$d->id_data_lembur,
						$d->no_lembur,
						$this->formatter->getDateMonthFormatUser($d->tgl_mulai),
						$this->formatter->timeFormatUser($d->jam_mulai),
						$this->formatter->getDateMonthFormatUser($d->tgl_selesai),
						$this->formatter->timeFormatUser($d->jam_selesai),
						// (!empty($d->nama_buat))?$d->nama_buat.$dibuat : $this->otherfunctions->getMark(),
						// (!empty($d->nama_periksa))?$d->nama_periksa.$diperiksa : $this->otherfunctions->getMark(),
						// (!empty($d->nama_ketahui))?$d->nama_ketahui.$diketahui : $this->otherfunctions->getMark(),
						$this->formatter->getDateMonthFormatUser($d->tgl_buat),
						$d->jumlah_lembur,
						// $potong,
						// (!empty($d->keterangan))?$d->keterangan:$this->otherfunctions->getMark(),
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],//.$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_data_lembur');
				$data=$this->model_karyawan->getLembur($id);
				foreach ($data as $d) {
					$mulai = $this->formatter->getDateMonthFormatUser($d->tgl_mulai).' - '.$this->formatter->timeFormatUser($d->jam_mulai).' WIB';
					$selesai = $this->formatter->getDateMonthFormatUser($d->tgl_selesai).' - '.$this->formatter->timeFormatUser($d->jam_selesai).' WIB';
					$dibuat=($d->jbt_buat != null) ? ' - '.$d->jbt_buat : null;
					$diperiksa=($d->jbt_periksa != null) ? ' - '.$d->jbt_periksa : null;
					$diketahui=($d->jbt_ketahui != null) ? ' - '.$d->jbt_ketahui : null;
					$datax=[
						'id'=>$d->id_data_lembur,
						'id_karyawan'=>$d->id_karyawan,
						'nomor'=>$d->no_lembur,
						'tanggal_mulai'=>$mulai,
						'tanggal_selesai'=>$selesai,
						'tgl_mulai_val'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$d->jam_mulai,
						'tgl_selesai_val'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$d->jam_selesai,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'tanggal_buat'=>$this->formatter->getDateMonthFormatUser($d->tgl_buat),
						'jumlah_lembur'=>$d->jumlah_lembur,
						'potong_jam'=>$d->potong_jam,
						'dibuat_oleh'=>(!empty($d->nama_buat_plan))?$d->nama_buat_plan.$dibuat : $this->otherfunctions->getMark(),
						'diperiksa_oleh'=>(!empty($d->nama_periksa))?$d->nama_periksa.$diperiksa : $this->otherfunctions->getMark(),
						'diketahui_oleh'=>(!empty($d->nama_ketahui))?$d->nama_ketahui.$diketahui : $this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark(),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark(),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark(),
						'e_dibuat'=>$d->dibuat_oleh,
						'e_diperiksa'=>$d->diperiksa_oleh,
						'e_diketahui'=>$d->diketahui_oleh,
						'jam_istirahat_edit'=>$d->potong_jam,
						'e_tanggal_mulai'=>$this->formatter->getDateFormatUser($d->tgl_mulai).' '.$d->jam_mulai,
						'e_tanggal_selesai'=>$this->formatter->getDateFormatUser($d->tgl_selesai).' '.$d->jam_selesai,
						'e_tgl_buat'=>$this->formatter->getDateFormatUser($d->tgl_buat),
						'e_keterangan'=>$d->keterangan,
						'kode_customer'=>$d->kode_customer,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function edit_lembur(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode=$this->input->post('kode_spl');
		$tanggal = $this->input->post('tanggal');
		$tgl_awal = $this->formatter->getDateFromRange($tanggal,'start');
		$tgl_akhir = $this->formatter->getDateFromRange($tanggal,'end');
		$jml_lembur = $this->otherfunctions->getDivTime($tgl_awal,$tgl_akhir);
		$t_awal=explode(" ", $tgl_awal);
		$t_akhir=explode(" ", $tgl_akhir);
		if ($kode != "") {
			$data=['id_karyawan'=>$this->input->post('id_karyawan'),
				'no_lembur'=>$kode,
				'tgl_mulai'=>$t_awal[0],
				'tgl_selesai'=>$t_akhir[0],
				'jam_mulai'=>$t_awal[1],
				'jam_selesai'=>$t_akhir[1],
				'jumlah_lembur'=>$jml_lembur,
				'dibuat_oleh'=>$this->input->post('id_dibuat'),
				'diperiksa_oleh'=>$this->input->post('id_diperiksa'),
				'diketahui_oleh'=>$this->input->post('id_diketahui'),
				'tgl_buat'=>$this->formatter->getDateFormatDb($this->input->post('tgl_buat')),
				'potong_jam'=>$this->input->post('jam_istirahat'),
				'keterangan'=>$this->input->post('keterangan'),
			];
			$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_lembur',['id_data_lembur'=>$id]);
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
// ================================================= DATA PERJALANAN DINAS ==========================================================
	public function data_perjalanan_dinas()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$param = $this->input->post('param');
				$bagian = $this->input->post('bagian');
				$unit = $this->input->post('unit');
				$tanggal = $this->input->post('tanggal');
				$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal];
				$data=$this->model_karyawan->getListDataPerjalananDinas('search',$where,'cari');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$datax['data'][]=[
						$d->id_pd,
						$d->nik_karyawan,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' - '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1),
						$tujuan,
						$d->jum,
						$properties['aksi'],
						$this->codegenerator->encryptChar($d->nik_karyawan),
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd');
				$data=$this->model_karyawan->getPerjalananDinasID($id);
				foreach ($data as $d) {
					$tabel='';
					$tabel.='<hr><h4 align="center"><b>Data Perjanan Dinas '.$d->nama_karyawan.'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>No Perjalanan Dinas</th>
          							<th>Tanggal</th>
          							<th>Tujuan</th>
          							<th>Kendaraan</th>
          						</tr>
          					</thead>
          					<tbody>';
          					$data_pd=$this->model_karyawan->getListPerjalananDinasNik($d->nik_karyawan);
          						$no=1;
          						foreach ($data_pd as $dpd) {
									$tujuan=($dpd->plant=='plant')?$dpd->nama_plant_tujuan:$dpd->lokasi_tujuan;
									$kendaraan=($dpd->kendaraan=='KPD0001')?$dpd->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($dpd->nama_kendaraan).')':$dpd->nama_kendaraan_j;
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$dpd->no_sk.'</td>
          							<td>'.$this->formatter->getDateTimeMonthFormatUser($dpd->tgl_berangkat,1).' - '.$this->formatter->getDateTimeMonthFormatUser($dpd->tgl_pulang,1).'</td>
          							<td>'.$tujuan.'</td>
          							<td>'.$kendaraan.'</td>
          							</tr>';
          						$no++;
          					}
	          				$tabel.='</tbody>
	          			</table>';
					$datax=[
						'tabel'=>$tabel,
						'jabatan'=>$d->nama_jabatan,
						'loker'=>$d->nama_loker,
						'id'=>$d->id_pd,
						'id_karyawan'=>$d->id_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_tunjangan') {
				parse_str($this->input->post('search'),$search);
				if(!empty($search['id_karyawan'])){
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Tunjangan Perjalanan Dinas</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th rowspan="2">No.</th>
          							<th rowspan="2">Nama Karyawan</th>
          							<th rowspan="2">Grade</th>
									<th rowspan="2">Jarak</th>';
          							// if(!empty($search['id_karyawan'])){
          							// 	if(count($search['id_karyawan'])==1){
									// 		$tabel.='<th rowspan="2">Transport</th>';
									// 	}
									// }
									// if(!empty($search['penginapan'])){
									// 	if(count($search['id_karyawan'])==1){
									// 		$tabel.='<th rowspan="2">Tunjangan Penginapan</th>';
									// 	}
									// }
									if($search['tujuan'] == 'non_plant'){
										$tabel.='<th colspan="3" class="text-center">TUNJANGAN MAKAN</th>';
									}else{
										$tabel.='<th colspan="4" class="text-center">TUNJANGAN MAKAN</th>';
									}
									$tabel.='<th rowspan="2" class="text-center">JUMLAH<br>UM Perjalanan</th>';
									$tabel.='<th rowspan="2">'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';
									$tabel.='<th rowspan="2">'.$this->model_master->getKategoriDinasKode('KAPD202301140001')['nama'].'</th>';
									$tabel.='<th rowspan="2">'.$this->model_master->getKategoriDinasKode('KAPD202302090001')['nama'].'</th>';
									$where = 'NOT(a.kode = "KAPD0001" OR a.kode = "KAPD0002" OR a.kode = "KAPD0003" OR a.kode = "KAPD0004" OR a.kode = "KAPD0005" OR a.kode = "KAPD0006" OR a.kode = "NONPLANT" OR a.kode = "KAPD202301140001" OR a.kode = "KAPD202302090001")';
									$tunjangan=$this->model_master->getKategoriDinasWhere($where);
          							if(!empty($tunjangan)){
          								foreach ($tunjangan as $tunj) {
          									$nama_tunj=$this->model_master->getKategoriDinasKode($tunj->kode)['nama'];
          									$tabel.='<th rowspan="2" width="15%">'.$nama_tunj.'</th>';
          								}
									}
          							$tabel.='<th rowspan="2">TOTAL</th>
          						</tr>
          						<tr class="bg-green">
								  <th class="text-center">Pagi</th>
								  <th class="text-center">Siang</th>
								  <th class="text-center">Malam</th>';
								  if($search['tujuan'] == 'plant'){
								  	$tabel.='<th class="text-center">Tambahan</th>';
								  }
          						$tabel.='</tr>
          					</thead>
          					<tbody>';
								$no=1;
								$nominal_bbm_all=[];
								$nama_kendaraan_all=[];
								$nama_penginapan_all=[];
								$nominal_penginapan_all=[];
								$total = 0;
          						if(!empty($search['id_karyawan'])){
          							foreach ($search['id_karyawan'] as $id_k) {
          								$namaKar=$this->model_karyawan->getEmpID($id_k)['nama'];
          								$gradeKar=$this->model_karyawan->getEmpID($id_k)['grade'];
										// echo $gradeKar;
          								$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
          								$kendaraan=(!empty($search['kendaraan'])?$search['kendaraan']:null);
										$kendaraan_umum=(!empty($search['kendaraan_umum'])?$search['kendaraan_umum']:null);
										$jarak_antar_plant=$this->model_master->jarakAntarPlant($search['plant_asal'],$search['plant_tujuan'])['jarak'];
          								$jarak=(($search['tujuan']=='plant')?$jarak_antar_plant:$search['jarak']);
										$penginapan=(!empty($search['penginapan'])?$search['penginapan']:null);
										$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
          								$nominal_penginapan=$this->formatter->getFormatMoneyDb($search['total_penginapan']);
          								$nama_kendaraan=$this->model_master->getKendaraanKode($kendaraan)['nama'];
          								$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
          								$nama_kendaraan_all[]=$this->model_master->getKendaraanKode($kendaraan)['nama'];
          								$nama_penginapan_all[]=$this->otherfunctions->getPenginapan($penginapan);
										$nominal_bbm_all[]=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
          								$nominal_penginapan_all[]=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
										$na=0;
										$jarak_val = (!empty($jarak) ? $jarak.' KM' : '0 KM');
          								$tabel.='<tr>
          								<td>'.$no.'</td>
          								<td>'.$namaKar.'</td>
          								<td>'.$namaGrade.'</td>
          								<td>'.$jarak_val.'</td>';
										// if(!empty($search['id_karyawan'])){
										// 	if(count($search['id_karyawan'])==1){
										// 		$nominal_bbmx=$this->formatter->getFormatMoneyDb($search['total_nominal_bbm']);
										// 		$tabel.='<td>'.$nama_kendaraan.' <br>('.$this->formatter->getFormatMoneyUser($nominal_bbmx).')</td>';
          								// 		$na=$na+$nominal_bbmx;
										// 	}
										// }
										// if(!empty($penginapan)){
										// 	if(count($search['id_karyawan'])==1){
										// 		$tabel.='<td>'.$nama_penginapan.' ('.$this->formatter->getFormatMoneyUser($nominal_penginapan).')</td>';
										// 		$na=$na+$nominal_penginapan;
										// 	}
										// }
										$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
										$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
										$nominal_grade=	$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
										$jarakMinNon=$this->model_master->getGeneralSetting('MJPDN')['value_int'];
										$nominal_non=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'NONPLANT')['nominal'];
										if($search['tujuan'] == 'non_plant'){
											$uang_makan_dasar = $nominal_dasar+$nominal_non;
											$jarakMinimal = $jarakMinNon;
										}else{
											$uang_makan_dasar = $nominal_dasar+$nominal_grade;
											$jarakMinimal = $jarakMin;
										}
										$nominal_tambahan=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
										$flag = (isset($search['flag']) ? 'edit' : 'add');
										$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMinimal,$jarak,$nominal_tambahan, $flag);
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($dx['uang_makan']['nPagi']).'</td>'; 
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($dx['uang_makan']['nSiang']).'</td>'; 
										$tabel.='<td>'.$this->formatter->getFormatMoneyUser($dx['uang_makan']['nMalam']).'</td>'; 
										if($search['tujuan'] == 'non_plant'){
											$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam'];
										}else{
											$tabel.='<td>'.$this->formatter->getFormatMoneyUser($dx['uang_makan']['nTambahan']).'</td>'; 
											$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam']+$dx['uang_makan']['nTambahan'];
										}
          								$na=$na+($nominal_pd);
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_pd).'</td>';
										//========= Insentif Bantuan Plan ==============
										$jenisPerdin = $this->model_master->getJenisPerdinWhere(['a.kode'=>$search['jenis_perdin']], true)['insentif'];
										if($jenisPerdin){
											$jarakibp=$this->model_master->getGeneralSetting('JMIBP')['value_int'];
											$nominalIBP = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002');
											if($search['tujuan'] == 'plant'){
												$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal']) : $nominalIBP['nominal_min']);
											}else{
												$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal_non']) : 0);
											}
											$na=$na+($nominal_saku*$dx['selisih']['hari']);
											$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_saku*$dx['selisih']['hari']).'</td>';
										}else{
											$tabel.='<td>0</td>';
										}
										//============ INAP ============
										$table_inap='<td></td>';
										if($dx['tgl_sampai'] != $dx['tgl_pulang']){
											if($dx['selisihFromSampai']['jam'] > 8 || $dx['selisihFromSampai']['hari'] > 1 || ($dx['selisihFromSampai']['jam'] == '0' && $dx['selisihFromSampai']['alljam'] == 24)){
												$nominalInap = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202301140001')['nominal'];
												$jumlahHari = (($dx['selisihFromSampai']['hari'] > 1 && $dx['selisihFromSampai']['jam'] <= 8) ? ($dx['selisihFromSampai']['hari']-1) : $dx['selisihFromSampai']['hari']);
												$na=$na+($nominalInap*$jumlahHari);
												$table_inap='<td>'.$this->formatter->getFormatMoneyUser($nominalInap*$jumlahHari).'</td>';
											}
										}
										$tabel.=$table_inap;
										//=========== STORING ==========
										$whereSt = 'emp.id_karyawan="'.$id_k.'" AND (jbt.kode_bagian="BAG201910160002" OR jbt.kode_bagian="BAG201910170001") AND emp.status_emp="1"';
										$karSt = $this->model_karyawan->getEmployeeWhere($whereSt, true);
										$table_storing='<td></td>';
										if(!empty($karSt)){
											$jarakStoring=$this->model_master->getGeneralSetting('JMSTORING')['value_int'];
											$nominalStoring = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202302090001');
											$nominal_storing = (($jarak >= $jarakStoring) ? ($nominalStoring['nominal']) : $nominalStoring['nominal_min']);
											$na=$na+($nominal_storing*$dx['selisih']['hari']);
											$table_storing='<td>'.$this->formatter->getFormatMoneyUser($nominal_storing*$dx['selisih']['hari']).'</td>';
										}
										$tabel.=$table_storing;
										//============ OTHER ===========
										$wherex = 'NOT(a.kode = "KAPD0001" OR a.kode = "KAPD0002" OR a.kode = "KAPD0003" OR a.kode = "KAPD0004" OR a.kode = "KAPD0005" OR a.kode = "KAPD0006" OR a.kode = "NONPLANT" OR a.kode = "KAPD202301140001" OR a.kode = "KAPD202302090001")';
										$tunjanganx=$this->model_master->getKategoriDinasWhere($wherex);
										if(!empty($tunjanganx)){
											foreach ($tunjanganx as $tunj) {
												$nominalx=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal'];
												$na=$na+($nominalx*$dx['selisih']['hari']);
												$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominalx*$dx['selisih']['hari']).'</td>';
											}
										} 
          								$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
										</tr>';
										$total+=$na;
          								$no++;
          							}
          						}
								$totalDriver = 0;
								if(isset($search['driver']) && $search['driver'] == '1'){
									// echo 'driver => '.$search['driver'];
									$jarak_antar_plant = $this->model_master->jarakAntarPlant($search['plant_asal'],$search['plant_tujuan'])['jarak'];
									$jarak = (($search['tujuan']=='plant')?$jarak_antar_plant:$search['jarak']);
									$jarak_val = (!empty($jarak) ? $jarak.' KM' : '0 KM');
									$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
									$gradeKar = 'GRD201908141604';
									$nominal_dasar = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
									$nominal_grade =	$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
									$jarakMinNon = $this->model_master->getGeneralSetting('MJPDN')['value_int'];
									$nominal_non = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'NONPLANT')['nominal'];
									if($search['tujuan'] == 'non_plant'){
										$uang_makan_dasar = $nominal_dasar+$nominal_non;
										$jarakMinimal = $jarakMinNon;
									}else{
										$uang_makan_dasar = $nominal_dasar+$nominal_grade;
										$jarakMinimal = $jarakMin;
									}
									$nominal_tambahan = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
									$flag = (isset($search['flag']) ? 'edit' : 'add');
									$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMinimal,$jarak,$nominal_tambahan, $flag);
									$nPagi = $this->formatter->getFormatMoneyUser($dx['uang_makan']['nPagi']);
									$nSiang = $this->formatter->getFormatMoneyUser($dx['uang_makan']['nSiang']);
									$nMalam = $this->formatter->getFormatMoneyUser($dx['uang_makan']['nMalam']);
									if($search['tujuan'] == 'non_plant'){
										$nTambahan = null;
										$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam'];
									}else{
										$nTambahan = '<td>'.$this->formatter->getFormatMoneyUser($dx['uang_makan']['nTambahan']).'</td>'; 
										$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam']+$dx['uang_makan']['nTambahan'];
									}
									$totalDriver += $nominal_pd;
									$td_nominal_pd = $this->formatter->getFormatMoneyUser($nominal_pd);
									$tabel.='<tr>
									<td>'.(count($search['id_karyawan'])+1).'</td>
									<td>'.$search['nama_driver'].' - <b><i>Driver</i></b></td>
									<td>Non Grade</td>
									<td>'.$jarak_val.'</td>
									<td>'.$nPagi.'</td>
									<td>'.$nSiang.'</td>
									<td>'.$nMalam.'</td>';
									$tabel.= $nTambahan;
									$tabel.='<td>'.$td_nominal_pd.'</td>
									<td></td>
									<td></td>
									<td></td>
									<td>'.$td_nominal_pd.'</td>
									</tr>';
								}
	          				$tabel.='</tbody>
						  </table>';
						  $tabel.='<h4 align="right">Total Dana Untuk Karyawan <b>'.$this->formatter->getFormatMoneyUser(($total+$totalDriver)).'</b></h4>';
						  $id_k=(!empty($search['id_karyawan'])?($search['id_karyawan']):0);
						  $jKendaraan=(!empty($search['jum_kendaraan'])?$search['jum_kendaraan']:1);
						  $jKamar=(!empty($search['jumlah_kamar'])?$search['jumlah_kamar']:1);
						  $jHari=(!empty($search['jumlah_hari'])?$search['jumlah_hari']:1);
						  $nAllKendaraanx=array_sum($nominal_bbm_all)/count($id_k);
						  $nAllKendaraan=($nAllKendaraanx==0)?$this->formatter->getFormatMoneyDb($search['nominal_bbm']):$nAllKendaraanx;
						  $totalAkomodasi=($nAllKendaraan*$jKendaraan)+$this->formatter->getFormatMoneyDb($search['total_penginapan']);
					$datax=[
						'tabel'=>$tabel,
						'nominal_bbm_all'=>$this->formatter->getFormatMoneyUser($nAllKendaraan*$jKendaraan),
						'nominal_bbm_per_ken'=>$this->formatter->getFormatMoneyUser($nAllKendaraan),
						'nominal_tunjangan'=>$search['nominal_penginapan'],
						'nominal_tunjangan_all'=>$search['total_penginapan'],
						'nama_kendaraan_all'=>isset($nama_kendaraan_all)?$nama_kendaraan_all[0]:$this->otherfunctions->getMark(),
						'nama_tunjangan_all'=>isset($nama_kendaraan_all)?$nama_penginapan_all[0]:$this->otherfunctions->getMark(),
						'jumlah'=>count($search['id_karyawan'])+1,
						'jum_ken'=>$search['jum_kendaraan'],
						'jum_kamar'=>$jKamar,
						'jum_hari'=>$jHari,
						'total_akomodasi'=>$this->formatter->getFormatMoneyUser($totalAkomodasi),
					];
				}else{
					$datax=$this->messages->customFailure('Karyawan Kosong !');
				}
        		echo json_encode($datax);
			}elseif ($usage == 'view_tunjangan_nominal') {
				parse_str($this->input->post('search'),$search);
				$input_nominal_bbm = $search['nominal_bbm'];
				$kendaraan=(!empty($search['kendaraan'])?$search['kendaraan']:null);
			  	$kendaraan_umum=(!empty($search['kendaraan_umum'])?$search['kendaraan_umum']:null);
			  	$jarak_antar_plant=$this->model_master->jarakAntarPlant($search['plant_asal'],$search['plant_tujuan'])['jarak'];
				$jarak=(($search['tujuan']=='plant')?$jarak_antar_plant:$search['jarak']);
				$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
				$jKendaraan=(!empty($search['jum_kendaraan'])?$search['jum_kendaraan']:1);
				if(!empty($input_nominal_bbm) && !empty($kendaraan_umum)){
					$nominal_bbm = $this->formatter->getFormatMoneyDb($input_nominal_bbm);
				}
				// echo '<pre>';
				// print_r($search);
				// echo 'kendaraan = '.$kendaraan.'<br>';
				// echo 'kendaraan_umum = '.$kendaraan_umum.'<br>';
				// echo 'jarak_antar_plant = '.$jarak_antar_plant.'<br>';
				// echo 'jarak = '.$jarak.'<br>';
				// echo 'nominal_bbm = '.$nominal_bbm.'<br>';
				// echo 'jjKendaraan = '.$jKendaraan.'<br>';
				$datax=[
					'nominal_bbm'=>$this->formatter->getFormatMoneyUser($nominal_bbm),
					'nominal_bbm_all'=>$this->formatter->getFormatMoneyUser($nominal_bbm*$jKendaraan),
					'nominal_val'=>$nominal_bbm*$jKendaraan,
				];
        		echo json_encode($datax);
			}elseif ($usage == 'view_tunjangan_nominal_val') {
				parse_str($this->input->post('search'),$search);
				$kendaraan=(!empty($search['kendaraan'])?$search['kendaraan']:null);
			  	$kendaraan_umum=(!empty($search['kendaraan_umum'])?$search['kendaraan_umum']:null);
			  	$jarak_antar_plant=$this->model_master->jarakAntarPlant($search['plant_asal'],$search['plant_tujuan'])['jarak'];
				$jarak=(($search['tujuan']=='plant')?$jarak_antar_plant:$search['jarak']);
				$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kendaraan,$kendaraan_umum,$jarak)['nominal'];
				$jKendaraan=(!empty($search['jum_kendaraan'])?$search['jum_kendaraan']:1);
				$datax=[
					'nominal_bbm'=>$this->formatter->getFormatMoneyUser($nominal_bbm*$jKendaraan),
					'nominal_val'=>$nominal_bbm*$jKendaraan,
				];
        		echo json_encode($datax);
			}elseif ($usage == 'view_nominal_penginapan') {
				parse_str($this->input->post('search'),$search);
				$nominal_penginapan=(!empty($search['nominal_penginapan'])?$this->formatter->getFormatMoneyDb($search['nominal_penginapan']):0);
				$jumlah_kamar=(!empty($search['jumlah_kamar'])?$search['jumlah_kamar']:null);
				$jumlah_hari=(!empty($search['jumlah_hari'])?$search['jumlah_hari']:1);
				$nominal_bbm=(!empty($search['nominal_bbm_per'])?$this->formatter->getFormatMoneyDb($search['nominal_bbm_per']):0);
				$jumlah_ken=(!empty($search['jum_kendaraan'])?$search['jum_kendaraan']:null);
				$nominal_hotel = ($nominal_penginapan*$jumlah_kamar)*$jumlah_hari;
				$datax=[
					'biaya_penginapan'=>$this->formatter->getFormatMoneyUser($nominal_hotel),
					'biaya_penginapan_val'=>$nominal_penginapan*$jumlah_kamar,
					'biaya_bbm'=>$this->formatter->getFormatMoneyUser($nominal_bbm*$jumlah_ken),
					'biaya_bbm_val'=>$nominal_bbm*$jumlah_ken,
				];
        		echo json_encode($datax);
			}elseif ($usage == 'view_all_trans') {
				$levelAdmin=$this->model_admin->adm($this->admin)['level'];
				$l_acc=$this->otherfunctions->getYourAccess($this->admin);
				$l_ac=$this->otherfunctions->getAllAccess();
				if (isset($l_ac['val_perdin'])) {
					if (in_array($l_ac['val_perdin'], $l_acc)) {
						if($levelAdmin==0 || $levelAdmin==1){
							$aksesAdmin='tidak ada';
						}else{
							$aksesAdmin=$this->admin;
						}
					}else{
						$aksesAdmin=$this->admin;
					}
				}
				$param = $this->input->post('param');
				if($param == 'all'){
					$data=$this->model_karyawan->getPerjalananDinasPerTransaksi($aksesAdmin);
				}else{
					$bagian = $this->input->post('bagian');
					$unit = $this->input->post('unit');
					$tanggal = $this->input->post('tanggal');
					$where = ['param'=>'search','bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal];
					$data=$this->model_karyawan->getPerjalananDinasPerTransaksi($aksesAdmin,'search',$where,'cari');
				}
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					if (isset($access['l_ac']['del'])) {
						$delete = (in_array($access['l_ac']['del'], $access['access'])) ? '<button type="button" class="btn btn-danger btn-sm"  href="javascript:void(0)" onclick=delete_modal_u("'.$d->no_sk.'")><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></button> ' : null;
					}else{
						$delete = null;
					}
					$info = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick=view_modal_u("'.$d->no_sk.'")><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> ';
					if (isset($access['l_ac']['val_perdin'])) {
						if(in_array($access['l_ac']['val_perdin'], $access['access'])){
							if($d->validasi_ac==2){
								$validasi = '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=modal_need("'.$d->no_sk.'")><i class="fa fa-warning"></i> Perlu Validasi</button> ';
							}elseif($d->validasi_ac==1){
								$validasi = '<button type="button" class="btn btn-success btn-sm" href="javascript:void(0)" onclick=modal_yes("'.$d->no_sk.'")><i class="fa fa-check-circle"></i> Diizinkan</button> ';
							}elseif($d->validasi_ac==0){
								$validasi = '<button type="button" class="btn btn-danger btn-sm" href="javascript:void(0)" onclick=modal_no("'.$d->no_sk.'")><i class="fa fa-times-circle"></i> Tidak DIizinkan</button> ';
							}
						}else{
							$validasi = $this->otherfunctions->getStatusIzin($d->validasi_ac);
							// $validasi = '<p class="text-center">Anda tidak dapat akses</p>';
						}
					}else{
						$validasi = null;
					}
					$noPerDin=$this->codegenerator->encryptChar($d->no_sk);
					if (isset($access['l_ac']['prn'])) {
						$print = (in_array($access['l_ac']['prn'], $access['access'])) ? '<button type="button" class="btn btn-warning btn-sm" href="javascript:void(0)" onclick=do_print("'.$noPerDin.'")><i class="fa fa-print" data-toggle="tooltip" title="Cetak Data"></i></button> ' : null;
					}else{
						$print = null;
					}
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$datax['data'][]=[
						$d->id_pd,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' -<br>'.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1),
						$d->nama_plant_asal,
						$tujuan,
						$d->jum.' Karyawan',
						$validasi,
						$this->otherfunctions->getStatusPerdin($d->status_pd),
						$properties['tanggal'],
						$info.(($d->validasi_ac!=2)?$delete:null).$print,
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one_trans'){
				$kode = $this->input->post('no_sk');
				$data=$this->model_karyawan->getPerjalananDinasKodeSK($kode);
				$jum_kar=count($data);
				foreach ($data as $d) {
					$adaDriver = 0;
					$namaDriver = '';
					if(empty($d->driver && $d->driver != '1')){
						$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
						$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
						$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
						$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
						$jarak=($d->kendaraan=='KPD0001')?$d->jarak.' km':$this->otherfunctions->getMark();
						$tabel='';
						$tabel.='<h4 align="center"><b>Data Tunjangan Perjalanan Dinas</b></h4>
							<div style="max-height: 400px; overflow: auto;">
							<table class="table table-bordered table-striped data-table">
								<thead>
									<tr class="bg-blue">
										<th rowspan="2">No.</th>
										<th rowspan="2">Nama Karyawan</th>
										<th rowspan="2">Grade</th>';
										if($d->plant=='plant'){
											$tabel.='<th colspan="4" class="text-center">TUNJANGAN MAKAN</th>';
										}else{
											$tabel.='<th colspan="3" class="text-center">TUNJANGAN MAKAN</th>';
										}
										$val_tunjangan = (!empty($d->val_tunjangan)?$d->val_tunjangan:$d->tunjangan);
										$val_besar_tunjangan = (!empty($d->val_tunjangan)?$d->val_besar_tunjangan:$d->besar_tunjangan);
										$tunJa=$this->otherfunctions->getDataTunjanganPerdin($val_tunjangan,$val_besar_tunjangan);
										if(!empty($tunJa)){
											foreach ($tunJa as $keys => $val) {
												if($keys == 'UM'){
													$tabel.='<th rowspan="2" class="text-center">JUMLAH<br>UM Perjalanan</th>';
												}elseif($keys == 'KAPD0002'){
													$tabel.='<th rowspan="2" class="text-center">'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';
												// }elseif($keys == 'NONPLANT'){
												// 	$tabel.='<th rowspan="2" class="text-center">UM Non Plant</th>';
												}else{
													$tabel.='<th rowspan="2">'.$this->model_master->getKategoriDinasKode($keys)['nama'].'</th>';
												}
											}
										}
										$tabel.='<th rowspan="2">TOTAL</th>
									</tr>
									<tr class="bg-green">
									<th class="text-center">Pagi</th>
									<th class="text-center">Siang</th>
									<th class="text-center">Malam</th>';
										if($d->plant=='plant'){
											$tabel.='<th class="text-center">Tambahan</th>';
										}
									$tabel.='</tr>
								</thead>
								<tbody>';
									$no=1;
									$total=0;
									$dataw=$this->model_karyawan->getEmpNoSKTransaksi($kode);
									foreach($dataw as $w){
										if(empty($w->driver) && $w->driver != '1'){
											$namaKar=$this->model_karyawan->getEmpID($w->id_karyawan)['nama'];
											$gradeKar=$this->model_karyawan->getEmpID($w->id_karyawan)['grade'];
											$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
											$jarak=(!empty($d->jarak)?$d->jarak:null);
											$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
											$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
											$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
											$na=0;
											$tabel.='<tr>
											<td>'.$no.'</td>
											<td>'.$namaKar.'</td>
											<td>'.$namaGrade.'</td>';
											$tunjangan_uang_makan = (!empty($w->val_tunjangan_um)?$w->val_tunjangan_um:$w->tunjangan_um);
											$tun_um=$this->otherfunctions->getTunjanganUMPerdin($tunjangan_uang_makan);
											$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
											$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
											$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
											if(!empty($tun_um)){
												$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['pagi']).'</td>'; 
												$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['siang']).'</td>'; 
												$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['malam']).'</td>'; 
												if($d->plant=='plant' && isset($tun_um['lembur'])){
													$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['lembur']).'</td>'; 
													$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam']+$tun_um['lembur'];
												}else{
													$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam'];
												}
											}
											$kode_tunjangan = (!empty($w->val_tunjangan)?$w->val_tunjangan:$w->tunjangan);
											$basar_tunjangan = (!empty($w->val_besar_tunjangan)?$w->val_besar_tunjangan:$w->besar_tunjangan);
											$tunJax=$this->otherfunctions->getDataTunjanganPerdin($kode_tunjangan,$basar_tunjangan);
											if(!empty($tunJax)){
												foreach ($tunJax as $key => $valx) {
													$tabel.='<td>'.$this->formatter->getFormatMoneyUser($valx).'</td>'; 
													$na=$na+$valx;
												}
											}
											$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
											</tr>';
											$no++;
											$total+=$na;
										}else{
											$adaDriver += 1;
											$namaDriver = $d->id_karyawan;
											$namaKar=$w->id_karyawan;
											$gradeKar='GRD201908141604';
											$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
											$jarak=(!empty($d->jarak)?$d->jarak:null);
											$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
											$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
											$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
											$na=0;
											$tabel.='<tr>
											<td>'.$no.'</td>
											<td>'.$namaKar.' - <b><i>Driver</i></b></td>
											<td>'.$namaGrade.'</td>';
											$tunjangan_uang_makan = (!empty($w->val_tunjangan_um)?$w->val_tunjangan_um:$w->tunjangan_um);
											$tun_um=$this->otherfunctions->getTunjanganUMPerdin($tunjangan_uang_makan);
											$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
											$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
											$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
											if(!empty($tun_um)){
												$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['pagi']).'</td>'; 
												$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['siang']).'</td>'; 
												$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['malam']).'</td>'; 
												if($d->plant=='plant' && isset($tun_um['lembur'])){
													$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['lembur']).'</td>'; 
													$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam']+$tun_um['lembur'];
												}else{
													$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam'];
												}
											}
											$kode_tunjangan = (!empty($w->val_tunjangan)?$w->val_tunjangan:$w->tunjangan);
											$basar_tunjangan = (!empty($w->val_besar_tunjangan)?$w->val_besar_tunjangan:$w->besar_tunjangan);
											$tunJax=$this->otherfunctions->getDataTunjanganPerdin($kode_tunjangan,$basar_tunjangan);
											if(!empty($tunJax)){
												foreach ($tunJax as $key => $valx) {
													$tabel.='<td>'.$this->formatter->getFormatMoneyUser($valx).'</td>'; 
													$na=$na+$valx;
												}
											}
											$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
											</tr>';
											$no++;
											$total+=$na;
										}
									}
								$tabel.='</tbody>
							</table>';
						$tabel.='<h4 align="right">Total Dana Untuk Karyawan <b>'.$this->formatter->getFormatMoneyUser($total).'</b></h4>';
						$val_menginap=($d->val_menginap==1)?'Menginap':'Tidak Menginap';
						$val_jumlah_kamar = (!empty($d->val_jumlah_kamar)?$d->val_jumlah_kamar:0);
						$val_jumlah_hari = (!empty($d->val_jumlah_hari)?$d->val_jumlah_hari:0);
						$data_after_val='<div class="panel panel-success">
							<div class="panel-heading bg-green"><h4>Data Validasi Perjalanan Dinas</h4></div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Tanggal Berangkat</label>
												<div class="col-md-6">'.$this->formatter->getDateTimeMonthFormatUser($d->val_tgl_berangkat,1).' WIB</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Tanggal Sampai</label>
												<div class="col-md-6">'.$this->formatter->getDateTimeMonthFormatUser($d->val_tgl_sampai,1).' WIB</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Tanggal Pulang</label>
												<div class="col-md-6">'.$this->formatter->getDateTimeMonthFormatUser($d->val_tgl_pulang,1).' WIB</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Kendaraan</label>
												<div class="col-md-6">'.(!empty($d->val_nama_kendaraan_j)?$d->val_nama_kendaraan_j:'-').'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Nama Kendaraan Umum</label>
												<div class="col-md-6">'.$this->otherfunctions->getKendaraanUmum($d->val_kendaraan_umum).'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Nominal BBM Per Kendaraan</label>
												<div class="col-md-6">'.$this->formatter->getFormatMoneyUser($d->val_nominal_bbm).'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Jumlah Kendaraan</label>
												<div class="col-md-6">'.(!empty($d->val_jum_kendaraan)?$d->val_jum_kendaraan.' Kendaraan': '-').'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Total Nominal Transport</label>
												<div class="col-md-6">'.$this->formatter->getFormatMoneyUser($d->val_nominal_bbm*$d->val_jum_kendaraan).'</div>
											</div><br>
										</div>
										<div class="col-md-6">
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Menginap</label>
												<div class="col-md-6">'.(!empty($d->val_menginap)?$val_menginap:'-').'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Penginapan</label>
												<div class="col-md-6">'.(!empty($d->val_penginapan)?$this->otherfunctions->getPenginapan($d->val_penginapan):null).'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Kelas Hotel</label>
												<div class="col-md-6">'.(!empty($d->val_jenis_hotel)?$this->model_master->getTipeHotelWhere(['a.kode'=>$d->val_jenis_hotel],true)['nama']:null).'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Biaya Penginapan</label>
												<div class="col-md-6">'.$this->formatter->getFormatMoneyUser($d->val_nominal_penginapan).'</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Jumlah Kamar</label>
												<div class="col-md-6">'.$val_jumlah_kamar.' Kamar</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Jumlah Hari</label>
												<div class="col-md-6">'.$val_jumlah_hari.' Hari</div>
											</div><br>
											<div class="form-group col-md-12">
												<label class="col-md-6 control-label">Total Biaya Penginapan</label>
												<div class="col-md-6">'.$this->formatter->getFormatMoneyUser($d->val_nominal_penginapan*$val_jumlah_kamar*$val_jumlah_hari).'</div>
											</div><br>
										</div>
									</div>
								</div>';
							// $val_jumlah_kamar = (!empty($d->val_jumlah_kamar)?$d->val_jumlah_kamar:1);
							$totalAkomodasiVal=($d->val_nominal_bbm*$d->val_jum_kendaraan)+($d->val_nominal_penginapan*$val_jumlah_kamar*$val_jumlah_hari);
							$data_after_val.='<h4 align="right">Total Dana Untuk Akomodasi Setelah di Validasi <b>'.$this->formatter->getFormatMoneyUser($totalAkomodasiVal).'</b></h4>
							</div>
						</div>';
						$tabelEndProses='';
						$tabelEndProses.='<hr><h4 align="center"><b>Data Kode Akun</b></h4>
							<div style="max-height: 400px; overflow: auto;">
							<button type="button" class="btn btn-sm btn-success pull-left" href="javascript:void(0)" onclick=modal_proses()><i class="fa fa-plus"></i> Tambah Data</button><br><br>
							<table class="table table-bordered table-striped data-table">
								<thead>
									<tr class="bg-green">
										<th>No.</th>
										<th>Kode Akun</th>
										<th>Nama</th>
										<th>Nominal</th>
										<th>Keterangan</th>
										<th width="7%">Aksi</th>
									</tr>
								</thead>
								<tbody>';
									$no=1;
									$totalKA=0;
									$dataKA=$this->model_karyawan->getKodeAkunNoSK($kode);
									foreach($dataKA as $ka){
										$tabelEndProses.='<tr>
										<td>'.$no.'</td>
										<td>'.$ka->kode_akun.'</td>
										<td>'.$ka->nama_akun.'</td>
										<td>'.$this->formatter->getFormatMoneyUser($ka->nominal).'</td>
										<td>'.$ka->keterangan.'</td>
										<td><button type="button" class="btn btn-sm btn-info" href="javascript:void(0)" onclick=edit_modal_ka('.$ka->id.')><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-sm btn-danger" href="javascript:void(0)" onclick=delete_modal_ka('.$ka->id.')><i class="fa fa-trash"></i></button></td>
										</tr>';
										$no++;
										$totalKA=$totalKA+$ka->nominal;
									}
								$tabelEndProses.='</tbody>
							</table>';
							$tabelEndProses.='<h4 align="right">Total Dana Kode Akun <b>'.$this->formatter->getFormatMoneyUser($totalKA).'</b></h4>';
						$e_karyawan=[];
						foreach ($dataw as $key) {
							$e_karyawan[]=$key->id_karyawan;
						}
						$namaKar=$this->model_karyawan->getEmpID($d->id_karyawan)['nama'];
						$gradeKar=$this->model_karyawan->getEmpID($d->id_karyawan)['grade'];
						$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
						$t_kendaraan=(!empty($d->kendaraan)?$d->kendaraan:null);
						$kendaraan_umum=(!empty($d->nama_kendaraan)?$d->nama_kendaraan:null);
						$jarak=(!empty($d->jarak)?$d->jarak:null);
						$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
						$nominal_bbm=$this->model_karyawan->getTunjanganBBM($t_kendaraan,$kendaraan_umum,$jarak)['nominal'];
						$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
						$totalAkomodasi=$d->nominal_bbm+$d->total_biaya_penginapan;
						$totalAkomodasiVal=$d->val_nominal_bbm+$d->val_nominal_penginapan;
						$tgl_berangkat = $this->otherfunctions->getDataExplode($d->tgl_berangkat,' ','start');
						$jam_berangkat = $this->otherfunctions->getDataExplode($d->tgl_berangkat,' ','end');
						$tgl_sampai = $this->otherfunctions->getDataExplode($d->tgl_sampai,' ','start');
						$jam_sampai = $this->otherfunctions->getDataExplode($d->tgl_sampai,' ','end');
						$tgl_pulang = $this->otherfunctions->getDataExplode($d->tgl_pulang,' ','start');
						$jam_pulang = $this->otherfunctions->getDataExplode($d->tgl_pulang,' ','end');
						$jumlah_kendaraan = (empty($d->jumlah_kendaraan) || $d->jumlah_kendaraan == 0)?'1':$d->jumlah_kendaraan;
					}else{
						$adaDriver += 1;
						$namaDriver = $d->id_karyawan;
					}
					$datax=[
						'id'=>$d->id_pd,
						// 'id_karyawan'=>$d->id_karyawan,
						'e_karyawan'=>$e_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'jum_kar'=>$jum_kar,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tanggal_berangkat'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB',
						'tanggal_sampai'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						'tanggal_pulang'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_pulang,1).' WIB',
						'tujuan'=>$tujuan,
						'asal'=>$d->nama_plant_asal,
						'kendaraan'=>$kendaraan,
						'jarak'=>$d->jarak.' km',
						'plant'=>$d->plant,
						'menginap'=>$d->menginap,
						'nama_penginapan'=>$this->otherfunctions->getPenginapan($d->nama_penginapan),
						// 'nominal_bbm'=>(!empty($nominal_bbm)?$this->formatter->getFormatMoneyUser($nominal_bbm):$this->otherfunctions->getMark()),
						// 'nominal_penginapan'=>(!empty($nominal_penginapan)?$this->formatter->getFormatMoneyUser($nominal_penginapan):$this->otherfunctions->getMark()),
						'nominal_bbm'=>(!empty($d->nominal_bbm)?$this->formatter->getFormatMoneyUser($d->nominal_bbm):$this->otherfunctions->getMark()),
						'e_nominal_bbm'=>(!empty($d->nominal_bbm)?$this->formatter->getFormatMoneyUser($d->nominal_bbm):null),
						'e_total_bbm'=>$this->formatter->getFormatMoneyUser($d->nominal_bbm*$jumlah_kendaraan),
						'jumlah_kendaraan'=>(!empty($d->jumlah_kendaraan)?$jumlah_kendaraan.' Kendaraan':$this->otherfunctions->getMark()),
						'nominal_per_ken'=>(!empty($d->nominal_bbm)&&!empty($jumlah_kendaraan)?$this->formatter->getFormatMoneyUser($d->nominal_bbm/$jumlah_kendaraan):$this->otherfunctions->getMark()),
						'nominal_penginapan'=>(!empty($d->nominal_penginapan)?$this->formatter->getFormatMoneyUser($d->nominal_penginapan):$this->otherfunctions->getMark()),
						'jumlah_kamar'=>(!empty($d->jumlah_hotel)?$d->jumlah_hotel:$this->otherfunctions->getMark()),
						'jumlah_hari'=>(!empty($d->jumlah_hari)?$d->jumlah_hari:$this->otherfunctions->getMark()),
						'total_penginapan'=>(!empty($d->total_biaya_penginapan)?$this->formatter->getFormatMoneyUser($d->total_biaya_penginapan):$this->otherfunctions->getMark()),
						'nominal_penginapan_edit'=>$d->nominal_penginapan,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						// 'dibuat'=>(!empty($d->nama_dibuat)) ? $d->nama_dibuat.$jbt_dibuat:$this->otherfunctions->getMark(),
						'tugas'=>(!empty($d->tugas)) ? $d->tugas:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'tabel_tunjangan'=>$tabel,
						'data_after_val'=>$data_after_val,
						'validasi_ac'=>$d->validasi_ac,
						'e_plant_tujuan'=>$d->plant_tujuan,
						'e_plant_asal'=>$d->plant_asal,
						'e_kendaraan'=>$d->kendaraan,
						'e_lokasi'=>$d->lokasi_tujuan,
						'e_jarak'=>$d->jarak,
						'e_kendaraan_umum'=>$d->nama_kendaraan,
						'e_nama_penginapan'=>$d->nama_penginapan,
						'jumlah'=>$jum_kar,
						'jumlah_kendaraan_edit'=>$jumlah_kendaraan,
						'e_nominal_bbm_per'=>$d->nominal_bbm/$jumlah_kendaraan,
						'e_nominal_bbm'=>$this->formatter->getFormatMoneyUser($d->nominal_bbm),
						// 'e_nominal_penginapan'=>$this->formatter->getFormatMoneyUser($d->total_biaya_penginapan),
						'kelas_hotel_edit'=>$d->jenis_hotel,
						'nominal_penginapan_edit'=>$this->formatter->getFormatMoneyUser($d->nominal_penginapan),
						'jumlah_kamar_edit'=>$d->jumlah_hotel,
						'jumlah_hari_edit'=>$d->jumlah_hari,
						'total_penginapan_edit'=>$this->formatter->getFormatMoneyUser($d->total_biaya_penginapan),
						// 'nominal_bbm_all'=>$search['nominal_bbm'],
						// 'nominal_tunjangan_all'=>$search['nominal_penginapan'],
						// 'nama_kendaraan_all'=>isset($nama_kendaraan_all)?$nama_kendaraan_all[0]:$this->otherfunctions->getMark(),
						// 'nama_tunjangan_all'=>isset($nama_kendaraan_all)?$nama_penginapan_all[0]:$this->otherfunctions->getMark(),
						// 'e_tunjangan'=>$e_tunjangan,
						'e_tanggal_mulai'=>$this->formatter->getDateTimeFormatUser($d->tgl_berangkat),
						'e_tanggal_selesai'=>$this->formatter->getDateTimeFormatUser($d->tgl_sampai),
						'e_tanggal_pulang'=>$this->formatter->getDateTimeFormatUser($d->tgl_pulang),
						// 'e_tanggal_berangkat_val'=>$this->formatter->getDateFormatUser($tgl_berangkat).' '.$jam_berangkat,
						// 'e_tanggal_sampai_val'=>$this->formatter->getDateFormatUser($tgl_sampai).' '.$jam_sampai,
						'e_tanggal_pulang_val'=>$this->formatter->getDateFormatUser($tgl_pulang).' '.$jam_pulang,
						'tanggal_pulang_val_aja'=>$this->formatter->getDateFormatUser($tgl_pulang),
						'jam_pulang_val'=>$jam_pulang,
						'e_tanggal_berangkat_val'=>$this->formatter->getDateFormatUser($tgl_berangkat).' '.$jam_berangkat.' - '.$this->formatter->getDateFormatUser($tgl_sampai).' '.$jam_sampai,
						'e_tugas'=>$d->tugas,
						'e_kelas_hotel'=>$d->jenis_hotel,
						'e_nominal_penginapan'=>$this->formatter->getFormatMoneyUser($d->nominal_penginapan),
						'e_jumlah_kamar'=>$d->jumlah_hotel,
						'e_jumlah_hari'=>$d->jumlah_hari,
						'e_total_penginapan'=>$this->formatter->getFormatMoneyUser($d->total_biaya_penginapan),
						'e_keterangan'=>$d->keterangan,
						'e_mengetahui'=>$d->mengetahui,
						'e_menyetujui'=>$d->menyetujui,
						'e_dibuat'=>$d->dibuat,
						'status_pd'=>$d->status_pd,
						'jenis_perdin'=>$d->jenis_perdin,
						'total_akomodasi'=>$this->formatter->getFormatMoneyUser($totalAkomodasi),
						'total_akomodasi_val'=>$this->formatter->getFormatMoneyUser($totalAkomodasiVal),
						'data_end_proses'=>$tabelEndProses,
						'adaDriver'=>$adaDriver,
						'namaDriver'=>$namaDriver,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'cek_jumlah_karyawan') {
				$karyawan = $this->input->post('kary');
				$kary=($karyawan==null || $karyawan == 0)?null:count($karyawan);
				$data = ['val'=>$kary];
        		echo json_encode($data);
			}elseif ($usage == 'diberikan_karyawan') {
				$karyawan = $this->input->post('kary');
				$data=$this->model_karyawan->getEmployeeForSelect2ID($karyawan);
        		echo json_encode($data);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePerjalananDinas();
        		echo json_encode($data);
			}elseif ($usage == 'pilihtunjangan') {
				$data = $this->model_karyawan->pilihTunjanganSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'refreshtunjangan') {
				$data = $this->model_karyawan->refreshTunjangan();
        		echo json_encode($data);
			}elseif ($usage == 'pilihKodeAKun') {
				$data = $this->model_karyawan->getKodeAkunForSelect2();
        		echo json_encode($data);
			}elseif ($usage == 'view_kode_akun') {
				$kode = $this->input->post('no_sk');
				$data=$this->model_karyawan->getPerjalananDinasKodeSK($kode);
				foreach ($data as $d) {
					$tabel_end_proses='<table class="table table-bordered table-striped data-table" id="myTable">
									<thead>
										<tr class="bg-blue">
											<th>Kode Akun</th>
											<th>Nominal</th>
											<th>Keterangan</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>';
					$dataakun=$this->model_master->getKodeAkunAktif();
					$select='';
					$select.='<select name="kode_akun[]" class="form-control select2" style="width: 100%;" required="required">';
						$select.='<option value="">Pilih Data</option>';
						foreach ($dataakun as $da) {
							$select.='<option value="'.$da->kode_akun.'">'.$da->kode_akun.' ('.$da->nama.')</option>';
						}
					$select.='</select>';
					$nominal='<div class="input-group">
						<input type="text" name="nominal_end[]" class="input-money form-control" placeholder="Tetapkan Nominal" required="required" style="width: 100%;"></div>
						<script>
							$(document).ready(function(){
								$(".input-money").keyup(function () {
									this.value = formatRupiah(this.value, "Rp. ");
								});
								$(".input-money").focus(function (data) {
									if (this.value == "Rp. 0") {
										this.value = "";
									}
								});
								$(".input-money").focusout(function (data) {
									if (this.value == "") {
										this.value = "Rp. 0";
									} else if (this.value == "0") {
										this.value = "Rp. 0";
									}
								});
							})
						</script>';
					$keterangan='<div class="input-group">
						<textarea type="text" name="keterangan[]" class="form-control" placeholder="Keterangan" style="width: 100%;"></textarea></div>';
					$datax=[
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark(),
						'select'=>$select,
						'nominal'=>$nominal,
						'keterangan'=>$keterangan,
						'tabel_end_proses'=>$tabel_end_proses,
					];
				}
        		echo json_encode($datax);
			}elseif ($usage == 'edit_kode_akun') {
				$id = $this->input->post('id');
				$data=$this->model_karyawan->getKodeAkun($id);
				foreach ($data as $d) {
					$datax=[
						'id'=>$d->id,
						'kode_perjalanan_dinas'=>$d->kode_perjalanan_dinas,
						'kode_akun'=>$d->kode_akun,
						'nama_akun'=>$d->nama_akun,
						'nominal'=>$this->formatter->getFormatMoneyUser($d->nominal),
						'keterangan'=>$d->keterangan,
					];
				}
        		echo json_encode($datax);
			}elseif ($usage == 'get_tipe_hotel') {
				$kode = $this->input->post('kode');
				$data=$this->model_master->getTipeHotelWhere(['a.kode'=>$kode],true);
				$datax=[
					'id'=>$data['id'],
					'msg'=>'<br>Tipe Hotel '.$data['nama'].' nominal '.$this->formatter->getFormatMoneyUser($data['minimal']).' sampai '.$this->formatter->getFormatMoneyUser($data['maksimal']).'</br>',
					'minimal'=>$data['minimal'],
					'maksimal'=>$data['maksimal'],
				];
				echo json_encode($datax);
			}elseif ($usage == 'get_tipe_hotel_2') {
				$kode = $this->input->post('kode');
				$nominal = $this->input->post('nominal');
				$data=$this->model_master->getTipeHotelWhere(['a.kode'=>$kode],true);
				$datax=[
					'minimal'=>$data['minimal'],
					'maksimal'=>$data['maksimal'],
					'nominal'=>$this->formatter->getFormatMoneyDb($nominal),
				];
				echo json_encode($datax);
			}elseif ($usage == 'getJenisPerdin') {
				$data=$this->model_master->getJenisPerdinWhere(['a.status'=>'1'],false);
				$pack=[];
				foreach ($data as $d) {
					$pack[null]='Pilih Data';
					$pack[$d->kode]=$d->nama;
				}
				echo json_encode($pack);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_perjalanan_dinas(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$data=$this->model_karyawan->getListPerjalananDinasNik($this->codegenerator->decryptChar($this->uri->segment(4)));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_pd,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
					$datax['data'][]=[
						$d->id_pd,
						$d->nama_karyawan,
						$d->no_sk,
						$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB - '.$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						$tujuan,
						$kendaraan,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi'],//.$properties['cetak'],
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_pd');
				$data=$this->model_karyawan->getPerjalananDinasID($id);
				foreach ($data as $d) {
					$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
					$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
					$jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
					$tujuan=($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan;
					$kendaraan=($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j;
					$jarak=($d->kendaraan=='KPD0001')?$d->jarak.' km':$this->otherfunctions->getMark();
					$tabel='';
					$tabel.='<h4 align="center"><b>Data Tunjangan Perjanan Dinas</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>Nama Karyawan</th>
          							<th>Grade</th>
          							<th>Jarak</th>
          							<th>Transport</th>';
          							if(!empty($d->nama_penginapan)){
          								$tabel.='<th>Tunjangan Penginapan</th>';
          							}
          							// if(!empty($d->tunjangan)){
          							// 	$val_tunjangan=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
          							// 	foreach ($val_tunjangan as $tunj) {
          							// 		$nama_tunj=$this->model_master->getKategoriDinasKode($tunj)['nama'];
          							// 		$tabel.='<th>'.$nama_tunj.'</th>';
          							// 	}
									// }
									$tunjangan=$this->model_master->getListKategoriDinas('KAPD0001');
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0003')['nama'].'</th>';		
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0004')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0005')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0006')['nama'].'</th>';
									$tabel.='<th>'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';  
          							$tabel.='<th>Total</th>
          						</tr>
          					</thead>
          					<tbody>';
          						$no=1;
          						if(!empty($d->id_karyawan)){
          							$namaKar=$this->model_karyawan->getEmpID($d->id_karyawan)['nama'];
          							$gradeKar=$this->model_karyawan->getEmpID($d->id_karyawan)['grade'];
          							$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
          							$t_kendaraan=(!empty($d->kendaraan)?$d->kendaraan:null);
          							$kendaraan_umum=(!empty($d->nama_kendaraan)?$d->nama_kendaraan:null);
          							$jarak=(!empty($d->jarak)?$d->jarak:null);
          							$penginapan=(!empty($d->nama_penginapan)?$d->nama_penginapan:null);
          							$nominal_bbm=$this->model_karyawan->getTunjanganBBM($t_kendaraan,$kendaraan_umum,$jarak)['nominal'];
          							$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
          							$nama_kendaraan=$this->model_master->getKendaraanKode($t_kendaraan)['nama'];
          							$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
          							$na=0;
									$jarak_val=!empty($jarak)?$jarak.' KM':'0 KM';
          							$tabel.='<tr>
          							<td>'.$no.'</td>
          							<td>'.$namaKar.'</td>
          							<td>'.$namaGrade.'</td>
          								<td>'.$jarak_val.'</td>
          							<td>'.$nama_kendaraan.' ('.$this->formatter->getFormatMoneyUser($nominal_bbm).')</td>';
          							$na=$na+$nominal_bbm;
          							if(!empty($penginapan)){
          								$tabel.='<td>'.$nama_penginapan.' ('.$this->formatter->getFormatMoneyUser($nominal_penginapan).')</td>';
          								$na=$na+$nominal_penginapan;
          							}
          							// if(!empty($d->tunjangan)){
          							// 	$val_tunjn=$this->otherfunctions->getParseOneLevelVar($d->tunjangan);
          							// 	foreach ($val_tunjn as $tunj) {
          							// 		$nominal=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
          							// 		$na=$na+$nominal;
          							// 		$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal).'</td>';
          							// 	}
									// }
										$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
									$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
									$na=$na+$nominal_dasar;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_dasar).'</td>'; 
									$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
									$na=$na+$nominal_grade;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_grade).'</td>'; 
									// $nominal_pd=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0005')['nominal'];
									$nominal_pd=(($jarak >= $jarakMin)?($nominal_dasar+$nominal_grade):0);
									$na=$na+$nominal_pd;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_pd).'</td>'; 
									$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
									$na=$na+$nominal_lembur;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_lembur).'</td>'; 
									$nominal_saku=(($jarak >= $jarakMin)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
									$na=$na+$nominal_saku;
									$tabel.='<td>'.$this->formatter->getFormatMoneyUser($nominal_saku).'</td>';
          							$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
          							</tr>';
          							$no++;
          						}
	          				$tabel.='</tbody>
	          			</table>';
	          			// $e_tunjangan=[];
	          			// $tunja=(isset($d->tunjangan)?$this->otherfunctions->getParseOneLevelVar($d->tunjangan):[]);
	          			// if (isset($tunja)) {
	          			// 	foreach ($tunja as $key=>$tunj) {
	          			// 		$e_tunjangan[$key]=$tunj;
	          			// 	}
	          			// }
					$datax=[
						'id'=>$d->id_pd,
						'id_karyawan'=>$d->id_karyawan,
						'nik'=>$d->nik_karyawan,
						'nama'=>$d->nama_karyawan,
						'no_sk'=>(!empty($d->no_sk)) ? $d->no_sk : $this->otherfunctions->getMark($d->no_sk),
						'tanggal_berangkat'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,1).' WIB',
						'tanggal_sampai'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,1).' WIB',
						'tanggal_pulang'=>$this->formatter->getDateTimeMonthFormatUser($d->tgl_pulang,1).' WIB',
						'tujuan'=>$tujuan,
						'asal'=>$d->nama_plant_asal,
						'kendaraan'=>$kendaraan,
						'jarak'=>$d->jarak.' km',
						'plant'=>$d->plant,
						'menginap'=>$d->menginap,
						'nama_penginapan'=>$this->otherfunctions->getPenginapan($d->nama_penginapan),
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'mengetahui'=>(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:$this->otherfunctions->getMark(),
						'menyetujui'=>(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:$this->otherfunctions->getMark(),
						'dibuat'=>(!empty($d->nama_dibuat)) ? $d->nama_dibuat.$jbt_dibuat:$this->otherfunctions->getMark(),
						'tugas'=>(!empty($d->tugas)) ? $d->tugas:$this->otherfunctions->getMark(),
						'keterangan'=>(!empty($d->keterangan)) ? $d->keterangan:$this->otherfunctions->getMark($d->keterangan),
						'tabel_tunjangan'=>$tabel,
						'e_plant_tujuan'=>$d->plant_tujuan,
						'e_plant_asal'=>$d->plant_asal,
						'e_kendaraan'=>$d->kendaraan,
						'e_lokasi'=>$d->lokasi_tujuan,
						'e_jarak'=>$d->jarak,
						'e_kendaraan_umum'=>$d->nama_kendaraan,
						'e_nama_penginapan'=>$d->nama_penginapan,
						// 'e_tunjangan'=>$e_tunjangan,
						'e_tanggal_mulai'=>$this->formatter->getDateTimeFormatUser($d->tgl_berangkat),
						'e_tanggal_selesai'=>$this->formatter->getDateTimeFormatUser($d->tgl_sampai),
						'e_tanggal_pulang'=>$this->formatter->getDateTimeFormatUser($d->tgl_pulang),
						'e_tugas'=>$d->tugas,
						'e_keterangan'=>$d->keterangan,
						'e_mengetahui'=>$d->mengetahui,
						'e_menyetujui'=>$d->menyetujui,
						'e_dibuat'=>$d->dibuat,
					];
				}
				echo json_encode($datax);
			}elseif ($usage == 'kode') {
				$data = $this->codegenerator->kodePeringatanKerja();
        		echo json_encode($data);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_perjalanan_dinas(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk');
		if ($kode != "") {
			$search = [
				'tanggal' => $this->input->post('tanggal'),
				'tanggal_pulang' => $this->input->post('tanggal_pulang'),
			];
			$id_k=$this->input->post('id_karyawan');
			// $tunjangan=$this->input->post('tunjangan');
			$penginapan=$this->input->post('penginapan');
			$kode_kendaraan=$this->input->post('kendaraan');
			$kendaraan_umum=$this->input->post('kendaraan_umum');
			$jum_kendaraan=$this->input->post('jum_kendaraan');
			$input_nominal_bbm=$this->input->post('nominal_bbm');
			$kelas_hotel=$this->input->post('kelas_hotel');
			$driver=$this->input->post('driver');
			$jenis_perdin=$this->input->post('jenis_perdin');
			if($this->input->post('tujuan')=='plant'){
				$jarak=$this->model_master->jarakAntarPlant($this->input->post('plant_asal'),$this->input->post('plant_tujuan'))['jarak'];
			}else{
				$jarak=$this->input->post('jarak');
			}
			$jum_kar	= count($id_k);
			$token      = $this->rando.uniqid();
			$date_now   = $this->date;
			$dtin       = ['token'=>$token,'date_now'=>$date_now];
			$link       = $this->otherfunctions->companyClientProfile()['link_val_perdin'].$this->codegenerator->encryptChar($dtin);
			foreach ($id_k as $id_ka) {
				$gradeKar=$this->model_karyawan->getEmpID($id_ka)['grade'];
				$na=0;
				$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
				$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
				$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
				$nominal_tambahan=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
				if($this->input->post('tujuan') == 'non_plant'){
					$jarakMinNon=$this->model_master->getGeneralSetting('MJPDN')['value_int'];
					$nominal_non=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'NONPLANT')['nominal'];
					$uang_makan_dasar = $nominal_dasar+$nominal_non;
					$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMinNon,$jarak,$nominal_tambahan);
					$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam'];
					$jarakMinimal = $jarakMinNon;
				}else{
					$uang_makan_dasar = $nominal_dasar+$nominal_grade;
					$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMin,$jarak,$nominal_tambahan);
					$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam']+$dx['uang_makan']['nTambahan'];
					$jarakMinimal = $jarakMin;
				}
				$na=$na+($nominal_pd);
				//========= Insentif Bantuan Plan ==============
				$jenisPerdin = $this->model_master->getJenisPerdinWhere(['a.kode'=>$jenis_perdin], true)['insentif'];
				if($jenisPerdin){
					$jarakibp=$this->model_master->getGeneralSetting('JMIBP')['value_int'];
					$nominalIBP = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002');
					if($this->input->post('tujuan') == 'plant'){
						$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal']) : $nominalIBP['nominal_min']);
					}else{
						$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal_non']) : 0);
					}
					$na=$na+($nominal_saku*$dx['selisih']['hari']);
				}else{
					$nominal_saku = 0;
				}
				//========================================================== BBM ============================================
				if($jum_kar==1){
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
				}else{
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal']*$jum_kendaraan;
					$nominal_penginapan=$this->formatter->getFormatMoneyDb($this->input->post('nominal_penginapan'));
				}
				if(!empty($input_nominal_bbm)){
					$nominal_bbm = $this->formatter->getFormatMoneyDb($input_nominal_bbm);
				}
				//========================================================== UM ============================================
				if($this->input->post('tujuan') == 'non_plant'){
					$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'];
				}else{
					$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'].';lembur:'.$dx['uang_makan']['nTambahan'];
				}
				//============ INSENTIF INAP ===================
				$nominalInap = 0;
				if($dx['tgl_sampai'] != $dx['tgl_pulang']){
					if($dx['selisihFromSampai']['jam'] > 8 || $dx['selisihFromSampai']['hari'] > 1 || ($dx['selisihFromSampai']['jam'] == '0' && $dx['selisihFromSampai']['alljam'] == 24)){
						$n_Inap = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202301140001')['nominal'];
						$jumlahHari = (($dx['selisihFromSampai']['hari'] > 1 && $dx['selisihFromSampai']['jam'] <= 8) ? ($dx['selisihFromSampai']['hari']-1) : $dx['selisihFromSampai']['hari']);
						$na=$na+($n_Inap*$jumlahHari);
						$nominalInap=($n_Inap*$jumlahHari);
					}
				}
				//============ STORING ===================
				$whereSt = 'emp.id_karyawan="'.$id_ka.'" AND (jbt.kode_bagian="BAG201910160002" OR jbt.kode_bagian="BAG201910170001") AND emp.status_emp="1"';
				$karSt = $this->model_karyawan->getEmployeeWhere($whereSt, true);
				$nominal_storing = 0;
				if(!empty($karSt)){
					$jarakStoring=$this->model_master->getGeneralSetting('JMSTORING')['value_int'];
					$nominalStoring = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202302090001');
					$nominal_storing = (($jarak >= $jarakStoring) ? ($nominalStoring['nominal']) : $nominalStoring['nominal_min']);
					$na=$na+($nominal_storing*$dx['selisih']['hari']);
				}
				//============ OTHER ===================
				$wherex = 'NOT(a.kode = "KAPD0001" OR a.kode = "KAPD0002" OR a.kode = "KAPD0003" OR a.kode = "KAPD0004" OR a.kode = "KAPD0005" OR a.kode = "KAPD0006" OR a.kode = "NONPLANT" OR a.kode = "KAPD202301140001" OR a.kode = "KAPD202302090001")';
				$tunjanganx=$this->model_master->getKategoriDinasWhere($wherex);
				$kode_tunj=[];
				$besar_tunj=[];
				if(!empty($tunjanganx)){
					foreach ($tunjanganx as $tunj) {
						$kode_tunj[]=$tunj->kode;
						$nominalx=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal'];
						$nominalxx = (!empty($nominalx)?$nominalx:0);
						$besar_tunj[]=($nominalxx*$dx['selisih']['hari']);
						$na=$na+($nominalxx*$dx['selisih']['hari']);
					}
				} 
				$kode_tunjanganx = implode($kode_tunj,";");
				$besar_tunjanganx = implode($besar_tunj,";");
				$na=$na+$nominal_bbm;
				$na=$na+$nominal_penginapan;
				$besar_tunjangan=(isset($nominal_pd)?$nominal_pd:0).';'.
					(isset($nominal_saku)?($nominal_saku*$dx['selisih']['hari']):0).';'.
					(isset($nominalInap)?($nominalInap):0).';'.
					(isset($nominal_storing)?($nominal_storing*$dx['selisih']['hari']):0);
				$kode_tunjangan="UM;KAPD0002;KAPD202301140001;KAPD202302090001";
				if(!empty($kode_tunjanganx)){
					$kode_tunjangan = $kode_tunjangan.';'.$kode_tunjanganx;
					$besar_tunjangan = $besar_tunjangan.';'.$besar_tunjanganx;
				}
				$data=[
					'id_karyawan'=>$id_ka,
					'no_sk'=>strtoupper($kode),
					'tgl_berangkat'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
					'tgl_sampai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
					'tgl_pulang'=>$this->formatter->getDateTimeFormatDb($this->input->post('tanggal_pulang')),
					'plant'=>$this->input->post('tujuan'),
					'plant_asal'=>$this->input->post('plant_asal'),
					'plant_tujuan'=>$this->input->post('plant_tujuan'),
					'lokasi_tujuan'=>$this->input->post('lokasi_tujuan'),
					'jarak'=>$jarak,
					'kendaraan'=>$kode_kendaraan,
					'nama_kendaraan'=>$kendaraan_umum,
					'jumlah_kendaraan'=>$jum_kendaraan,
					'nominal_bbm'=>$nominal_bbm,
					'menginap'=>$this->input->post('menginap'),
					'nama_penginapan'=>$penginapan,
					'nominal_penginapan'=>$nominal_penginapan,
					'tunjangan'=>$kode_tunjangan,
					'tunjangan_um'=>$tunjangan_um,
					'besar_tunjangan'=>$besar_tunjangan,//implode(';',$nominal),
					'jenis_hotel'=>$kelas_hotel,
					'jenis_perdin'=>$jenis_perdin,
					'jumlah_hotel'=>$this->input->post('jumlah_kamar'),
					'jumlah_hari'=>$this->input->post('jumlah_hari'),
					'total_biaya_penginapan'=>$this->formatter->getFormatMoneyDb($this->input->post('total_penginapan')),
					// 'tunjangan'=>implode(';',$tunjangan),
					'total_tunjangan'=>$this->formatter->getFormatMoneyDb($na),
					'tugas'=>$this->input->post('tugas'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'dibuat'=>$this->admin,
					'keterangan'=>$this->input->post('keterangan'),
					'status_pd'=>1,
					'validasi_ac'=>2,
				];
				$data=array_merge($data,$dtin,$this->model_global->getCreateProperties($this->admin));
				// echo '<pre>';
				// print_r($data);
				$dbs = $this->model_global->insertQuery($data,'data_perjalanan_dinas');
				// $data_validasi = ['kepada'=>'Validator Perjalanan Dinas',
				// 		'no_perjalanan'         =>$kode,
				// 		'tgl_berangkat'         =>$this->formatter->getDateTimeMonthFormatUser($this->formatter->getDateFromRange($this->input->post('tanggal'),'start')),
				// 		'tgl_sampai'            =>$this->formatter->getDateTimeMonthFormatUser($this->formatter->getDateFromRange($this->input->post('tanggal'),'end')),
				// 		'plant'                 =>$this->otherfunctions->getTujuanPD($this->input->post('tujuan')),
				// 		'plant_asal'            =>$this->model_master->getLokerKodeArray($this->input->post('plant_asal'))['nama'],
				// 		'tujuan'                =>($this->input->post('tujuan')=='plant')?$this->model_master->getLokerKodeArray($this->input->post('plant_tujuan'))['nama']:$this->input->post('lokasi_tujuan'),
				// 		'jarak'                 =>$jarak,
				// 		'kendaraan'             =>$this->model_master->getKendaraanKode($kode_kendaraan)['nama'],
				// 		'jum_kendaraan'         =>$jum_kendaraan,
				// 		'nominal_per_kendaraan' =>($nominal_bbm/$jum_kendaraan),
				// 		'nominal_bbm'           =>$this->formatter->getFormatMoneyUser($nominal_bbm),
				// 		'nominal_penginapan'    =>$this->formatter->getFormatMoneyUser($nominal_penginapan),
				// 		'penginapan'            =>$penginapan,
				// 		'tugas'                 =>$this->input->post('tugas'),
				// 		'keterangan'            =>$this->input->post('keterangan'),
				// 		'jumlah_karyawan'       =>$jum_kar,
				// 		'karyawan'              =>$this->otherfunctions->getKaryawanViewEmail($id_k),
				// 		'dibuat'                =>$this->model_admin->getAdminRowArray($this->admin)['nama'],
				// 		'mengetahui'            =>$this->model_karyawan->getEmpID($this->input->post('mengetahui'))['nama'].' - '.$this->model_karyawan->getEmpID($this->input->post('mengetahui'))['nama_jabatan'],
				// 		'menyetujui'            =>$this->model_karyawan->getEmpID($this->input->post('menyetujui'))['nama'].' - '.$this->model_karyawan->getEmpID($this->input->post('menyetujui'))['nama_jabatan'],
				// 		'tgl'                   =>$this->date,
				// 		'link'                  =>$link,
				// 		'kode'                  =>$kode,
				// 		'url'                   =>$this->otherfunctions->companyClientProfile()['url'],
				// 		'logo'                  =>$this->otherfunctions->companyClientProfile()['logo_url'],
				// 	];
				// $email_val=array_filter(array_unique($this->otherfunctions->getEmailValPerjalananDinas()));
			}
			if(isset($driver) && $driver == '1'){
				$gradeKar='GRD201908141604';
				$na=0;
				$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
				$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
				$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
				$nominal_tambahan=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
				if($this->input->post('tujuan') == 'non_plant'){
					$jarakMinNon=$this->model_master->getGeneralSetting('MJPDN')['value_int'];
					$nominal_non=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'NONPLANT')['nominal'];
					$uang_makan_dasar = $nominal_dasar+$nominal_non;
					$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMinNon,$jarak,$nominal_tambahan);
					$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam'];
					$jarakMinimal = $jarakMinNon;
				}else{
					$uang_makan_dasar = $nominal_dasar+$nominal_grade;
					$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMin,$jarak,$nominal_tambahan);
					$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam']+$dx['uang_makan']['nTambahan'];
					$jarakMinimal = $jarakMin;
				}
				$na=$na+($nominal_pd);
				//========= Insentif Bantuan Plan ==============
				$jarakibp=$this->model_master->getGeneralSetting('JMIBP')['value_int'];
				$nominalIBP = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002');
				if($this->input->post('tujuan') == 'plant'){
					$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal']) : $nominalIBP['nominal_min']);
				}else{
					$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal_non']) : 0);
				}
				$na=$na+($nominal_saku*$dx['selisih']['hari']);
				//========================================================== BBM ============================================
				if($jum_kar==1){
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
				}else{
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal']*$jum_kendaraan;
					$nominal_penginapan=$this->formatter->getFormatMoneyDb($this->input->post('nominal_penginapan'));
				}
				if(!empty($input_nominal_bbm)){
					$nominal_bbm = $this->formatter->getFormatMoneyDb($input_nominal_bbm);
				}
				//========================================================== UM ============================================
				if($this->input->post('tujuan') == 'non_plant'){
					$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'];
				}else{
					$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'].';lembur:'.$dx['uang_makan']['nTambahan'];
				}
				//============ INSENTIF INAP ===================
				$nominalInap = 0;
				$nominal_storing = 0;
				$kode_tunj=[];
				$besar_tunj=[];
				$kode_tunjanganx = implode($kode_tunj,";");
				$besar_tunjanganx = implode($besar_tunj,";");
				$na=$na+$nominal_bbm;
				$na=$na+$nominal_penginapan;
				$besar_tunjangan=(isset($nominal_pd)?$nominal_pd:0).';0;0;0';
				$kode_tunjangan="UM;KAPD0002;KAPD202301140001;KAPD202302090001";
				if(!empty($kode_tunjanganx)){
					$kode_tunjangan = $kode_tunjangan.';'.$kode_tunjanganx;
					$besar_tunjangan = $besar_tunjangan.';'.$besar_tunjanganx;
				}
				$data=[
					'id_karyawan'=>$this->input->post('nama_driver'),
					'driver'=>$this->input->post('driver'),
					'no_sk'=>strtoupper($kode),
					'tgl_berangkat'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
					'tgl_sampai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
					'tgl_pulang'=>$this->formatter->getDateTimeFormatDb($this->input->post('tanggal_pulang')),
					'plant'=>$this->input->post('tujuan'),
					'plant_asal'=>$this->input->post('plant_asal'),
					'plant_tujuan'=>$this->input->post('plant_tujuan'),
					'lokasi_tujuan'=>$this->input->post('lokasi_tujuan'),
					'jarak'=>$jarak,
					'kendaraan'=>$kode_kendaraan,
					'nama_kendaraan'=>$kendaraan_umum,
					'jumlah_kendaraan'=>$jum_kendaraan,
					'nominal_bbm'=>$nominal_bbm,
					'menginap'=>$this->input->post('menginap'),
					'nama_penginapan'=>$penginapan,
					'nominal_penginapan'=>$nominal_penginapan,
					'tunjangan'=>$kode_tunjangan,
					'tunjangan_um'=>$tunjangan_um,
					'besar_tunjangan'=>$besar_tunjangan,//implode(';',$nominal),
					'jenis_hotel'=>$kelas_hotel,
					'jenis_perdin'=>$jenis_perdin,
					'jumlah_hotel'=>$this->input->post('jumlah_kamar'),
					'jumlah_hari'=>$this->input->post('jumlah_hari'),
					'total_biaya_penginapan'=>$this->formatter->getFormatMoneyDb($this->input->post('total_penginapan')),
					// 'tunjangan'=>implode(';',$tunjangan),
					'total_tunjangan'=>$this->formatter->getFormatMoneyDb($na),
					'tugas'=>$this->input->post('tugas'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'dibuat'=>$this->admin,
					'keterangan'=>$this->input->post('keterangan'),
					'status_pd'=>1,
					'validasi_ac'=>2,
				];
				$data=array_merge($data,$dtin,$this->model_global->getCreateProperties($this->admin));
				// echo '<pre>';
				// print_r($data);
				$dbs = $this->model_global->insertQuery($data,'data_perjalanan_dinas');
			}
			// if(isset($email_val)){
			// 	$ci = get_instance();
			// 	$ci->email->initialize($this->otherfunctions->configEmail());
			// 	$ci->email->from($this->otherfunctions->configEmail()['smtp_user'], 'Pengajuan Perjalanan Dinas Online');
			// 	$ci->email->to($email_val);
			// 	$ci->email->subject('Pengajuan Perjalanan Dinas');
			// 	$body = $this->load->view('email_template/email_perdin_validasi',$data_validasi,TRUE);
			// 	$ci->email->message($body);
			// 	$eml=$this->email->send();
			// }
			// if ($eml && $dbs){
			// 	$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
			// }elseif($dbs){
			// 	$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
			// }elseif($eml){
			// 	$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
			// }else{
			// 	$datax=$this->messages->allFailure();
			// }
			if($dbs){
				$datax=$this->messages->allGood();
			}else{
				$datax=$this->messages->allFailure();
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_perjalanan_dinas(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$kode=$this->input->post('no_sk');
		if ($kode != "") {
			$search = [
				'tanggal' => $this->input->post('tanggal'),
				'tanggal_pulang' => $this->input->post('tanggal_pulang'),
			];
			$id_k=$this->input->post('id_karyawan');
			// $tunjangan=$this->input->post('tunjangan');
			$penginapan=$this->input->post('penginapan');
			$kode_kendaraan=$this->input->post('kendaraan');
			$kendaraan_umum=$this->input->post('kendaraan_umum');
			$jum_kendaraan=$this->input->post('jum_kendaraan');
			$input_nominal_bbm=$this->input->post('nominal_bbm');
			$kelas_hotel=$this->input->post('kelas_hotel');
			$kar_old=$this->input->post('karyawan_old');
			$d_kar_old=$this->otherfunctions->getDataExplode($kar_old,',','all');
			$no_sk_old=$this->input->post('no_sk_old');
			$driver=$this->input->post('driver');
			$jenis_perdin=$this->input->post('jenis_perdin');
			if($this->input->post('tujuan')=='plant'){
				$jarak=$this->model_master->jarakAntarPlant($this->input->post('plant_asal'),$this->input->post('plant_tujuan'))['jarak'];
			}else{
				$jarak=$this->input->post('jarak');
			}
			$jum_kar	= count($id_k);
			$token      = $this->rando.uniqid();
			$date_now   = $this->date;
			$dtin       = ['token'=>$token,'date_now'=>$date_now];
			$link       = $this->otherfunctions->companyClientProfile()['link_val_perdin'].$this->codegenerator->encryptChar($dtin);
			foreach ($id_k as $id_ka) {
				$gradeKar=$this->model_karyawan->getEmpID($id_ka)['grade'];
				$na=0;
				$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
				$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
				$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
				$nominal_tambahan=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
				if($this->input->post('tujuan') == 'non_plant'){
					$jarakMinNon=$this->model_master->getGeneralSetting('MJPDN')['value_int'];
					$nominal_non=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'NONPLANT')['nominal'];
					$uang_makan_dasar = $nominal_dasar+$nominal_non;
					$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMinNon,$jarak,$nominal_tambahan,'edit');
					$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam'];
					$jarakMinimal = $jarakMinNon;
				}else{
					$uang_makan_dasar = $nominal_dasar+$nominal_grade;
					$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMin,$jarak,$nominal_tambahan,'edit');
					$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam']+$dx['uang_makan']['nTambahan'];
					$jarakMinimal = $jarakMin;
				}
				$na=$na+($nominal_pd);
				//========= Insentif Bantuan Plan ==============
				$jenisPerdin = $this->model_master->getJenisPerdinWhere(['a.kode'=>$jenis_perdin], true)['insentif'];
				if($jenisPerdin){
					$jarakibp=$this->model_master->getGeneralSetting('JMIBP')['value_int'];
					$nominalIBP = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002');
					if($this->input->post('tujuan') == 'plant'){
						$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal']) : $nominalIBP['nominal_min']);
					}else{
						$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal_non']) : 0);
					}
					$na=$na+($nominal_saku*$dx['selisih']['hari']);
				}
				//========================================================== BBM ============================================
				if($jum_kar==1){
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
				}else{
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal']*$jum_kendaraan;
					$nominal_penginapan=$this->formatter->getFormatMoneyDb($this->input->post('nominal_penginapan'));
				}
				if(!empty($input_nominal_bbm)){
					$nominal_bbm = $this->formatter->getFormatMoneyDb($input_nominal_bbm);
				}
				//========================================================== UM ============================================
				if($this->input->post('tujuan') == 'non_plant'){
					$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'];
				}else{
					$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'].';lembur:'.$dx['uang_makan']['nTambahan'];
				}
				//============ INSENTIF INAP ===================
				$nominalInap = 0;
				if($dx['tgl_sampai'] != $dx['tgl_pulang']){
					if($dx['selisihFromSampai']['jam'] > 8){
						$nominalInap = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202301140001')['nominal'];
						$na=$na+($nominalInap*$dx['selisihFromSampai']['hari']);
					}
				}
				//============ STORING ===================
				$whereSt = 'emp.id_karyawan="'.$id_ka.'" AND (jbt.kode_bagian="BAG201910160002" OR jbt.kode_bagian="BAG201910170001") AND emp.status_emp="1"';
				$karSt = $this->model_karyawan->getEmployeeWhere($whereSt, true);
				$nominal_storing = 0;
				if(!empty($karSt)){
					$jarakStoring=$this->model_master->getGeneralSetting('JMSTORING')['value_int'];
					$nominalStoring = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202302090001');
					$nominal_storing = (($jarak >= $jarakStoring) ? ($nominalStoring['nominal']) : $nominalStoring['nominal_min']);
					$na=$na+($nominal_storing*$dx['selisih']['hari']);
				}
				//============ OTHER ===================
				$wherex = 'NOT(a.kode = "KAPD0001" OR a.kode = "KAPD0002" OR a.kode = "KAPD0003" OR a.kode = "KAPD0004" OR a.kode = "KAPD0005" OR a.kode = "KAPD0006" OR a.kode = "NONPLANT" OR a.kode = "KAPD202301140001" OR a.kode = "KAPD202302090001")';
				$tunjanganx=$this->model_master->getKategoriDinasWhere($wherex);
				$kode_tunj=[];
				$besar_tunj=[];
				if(!empty($tunjanganx)){
					foreach ($tunjanganx as $tunj) {
						$kode_tunj[]=$tunj->kode;
						$nominalx=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal'];
						$nominalxx = (!empty($nominalx)?$nominalx:0);
						$besar_tunj[]=($nominalxx*$dx['selisih']['hari']);
						$na=$na+($nominalxx*$dx['selisih']['hari']);
					}
				} 
				$kode_tunjanganx = implode($kode_tunj,";");
				$besar_tunjanganx = implode($besar_tunj,";");
				$na=$na+$nominal_bbm;
				$na=$na+$nominal_penginapan;
				$besar_tunjangan=(isset($nominal_pd)?$nominal_pd:0).';'.
					(isset($nominal_saku)?($nominal_saku*$dx['selisih']['hari']):0).';'.
					(isset($nominalInap)?($nominalInap*$dx['selisihFromSampai']['hari']):0).';'.
					(isset($nominal_storing)?($nominal_storing*$dx['selisih']['hari']):0);
				$kode_tunjangan="UM;KAPD0002;KAPD202301140001;KAPD202302090001";
				if(!empty($kode_tunjanganx)){
					$kode_tunjangan = $kode_tunjangan.';'.$kode_tunjanganx;
					$besar_tunjangan = $besar_tunjangan.';'.$besar_tunjanganx;
				}
				$data=[
					'id_karyawan'=>$id_ka,
					'no_sk'=>strtoupper($kode),
					'tgl_berangkat'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
					'tgl_sampai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
					'tgl_pulang'=>$this->formatter->getDateTimeFormatDb($this->input->post('tanggal_pulang')),
					'plant'=>$this->input->post('tujuan'),
					'plant_asal'=>$this->input->post('plant_asal'),
					'plant_tujuan'=>$this->input->post('plant_tujuan'),
					'lokasi_tujuan'=>$this->input->post('lokasi_tujuan'),
					'jarak'=>$jarak,
					'kendaraan'=>$kode_kendaraan,
					'nama_kendaraan'=>$kendaraan_umum,
					'jumlah_kendaraan'=>$jum_kendaraan,
					'nominal_bbm'=>$nominal_bbm,
					'menginap'=>$this->input->post('menginap'),
					'nama_penginapan'=>$penginapan,
					'nominal_penginapan'=>$nominal_penginapan,
					'tunjangan'=>$kode_tunjangan,
					'tunjangan_um'=>$tunjangan_um,
					'besar_tunjangan'=>$besar_tunjangan,//implode(';',$nominal),
					'jenis_hotel'=>$kelas_hotel,
					'jenis_perdin'=>$jenis_perdin,
					'jumlah_hotel'=>$this->input->post('jumlah_kamar'),
					'jumlah_hari'=>$this->input->post('jumlah_hari'),
					'total_biaya_penginapan'=>$this->formatter->getFormatMoneyDb($this->input->post('total_penginapan')),
					// 'tunjangan'=>implode(';',$tunjangan),
					'total_tunjangan'=>$this->formatter->getFormatMoneyDb($na),
					'tugas'=>$this->input->post('tugas'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'dibuat'=>$this->admin,
					'keterangan'=>$this->input->post('keterangan'),
					'status_pd'=>1,
					'validasi_ac'=>2,
				];
				// echo '<pre>';
				// print_r($data);				
				if(in_array($id_ka, $d_kar_old)){
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$id_ka]);
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_k)){
							$datax=$this->model_global->deleteQuery('data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$k_old]);
						}
					}
				}else{
					$data['id_karyawan']=$id_ka;
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQuery($data,'data_perjalanan_dinas');
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_k)){
							$datax=$this->model_global->deleteQuery('data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$k_old]);
						}
					}
				}
			}
			if(isset($driver) && $driver == '1'){
				$gradeKar='GRD201908141604';
				$na=0;
				$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
				$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
				$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
				$nominal_tambahan=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
				if($this->input->post('tujuan') == 'non_plant'){
					$jarakMinNon=$this->model_master->getGeneralSetting('MJPDN')['value_int'];
					$nominal_non=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'NONPLANT')['nominal'];
					$uang_makan_dasar = $nominal_dasar+$nominal_non;
					$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMinNon,$jarak,$nominal_tambahan,'edit');
					$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam'];
					$jarakMinimal = $jarakMinNon;
				}else{
					$uang_makan_dasar = $nominal_dasar+$nominal_grade;
					$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMin,$jarak,$nominal_tambahan,'edit');
					$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam']+$dx['uang_makan']['nTambahan'];
					$jarakMinimal = $jarakMin;
				}
				$na=$na+($nominal_pd);
				//========= Insentif Bantuan Plan ==============
				$jarakibp=$this->model_master->getGeneralSetting('JMIBP')['value_int'];
				$nominalIBP = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002');
				if($this->input->post('tujuan') == 'plant'){
					$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal']) : $nominalIBP['nominal_min']);
				}else{
					$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal_non']) : 0);
				}
				$na=$na+($nominal_saku*$dx['selisih']['hari']);
				//========================================================== BBM ============================================
				if($jum_kar==1){
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
				}else{
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal']*$jum_kendaraan;
					$nominal_penginapan=$this->formatter->getFormatMoneyDb($this->input->post('nominal_penginapan'));
				}
				if(!empty($input_nominal_bbm)){
					$nominal_bbm = $this->formatter->getFormatMoneyDb($input_nominal_bbm);
				}
				//========================================================== UM ============================================
				if($this->input->post('tujuan') == 'non_plant'){
					$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'];
				}else{
					$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'].';lembur:'.$dx['uang_makan']['nTambahan'];
				}
				//============ INSENTIF INAP ===================
				$nominalInap = 0;
				$nominal_storing = 0;
				$kode_tunj=[];
				$besar_tunj=[];
				$kode_tunjanganx = implode($kode_tunj,";");
				$besar_tunjanganx = implode($besar_tunj,";");
				$na=$na+$nominal_bbm;
				$na=$na+$nominal_penginapan;
				$besar_tunjangan=(isset($nominal_pd)?$nominal_pd:0).';0;0;0';
				$kode_tunjangan="UM;KAPD0002;KAPD202301140001;KAPD202302090001";
				if(!empty($kode_tunjanganx)){
					$kode_tunjangan = $kode_tunjangan.';'.$kode_tunjanganx;
					$besar_tunjangan = $besar_tunjangan.';'.$besar_tunjanganx;
				}
				$data=[
					'id_karyawan'=>$this->input->post('nama_driver'),
					'driver'=>$this->input->post('driver'),
					'no_sk'=>strtoupper($kode),
					'tgl_berangkat'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'start'),
					'tgl_sampai'=>$this->formatter->getDateFromRange($this->input->post('tanggal'),'end'),
					'tgl_pulang'=>$this->formatter->getDateTimeFormatDb($this->input->post('tanggal_pulang')),
					'plant'=>$this->input->post('tujuan'),
					'plant_asal'=>$this->input->post('plant_asal'),
					'plant_tujuan'=>$this->input->post('plant_tujuan'),
					'lokasi_tujuan'=>$this->input->post('lokasi_tujuan'),
					'jarak'=>$jarak,
					'kendaraan'=>$kode_kendaraan,
					'nama_kendaraan'=>$kendaraan_umum,
					'jumlah_kendaraan'=>$jum_kendaraan,
					'nominal_bbm'=>$nominal_bbm,
					'menginap'=>$this->input->post('menginap'),
					'nama_penginapan'=>$penginapan,
					'nominal_penginapan'=>$nominal_penginapan,
					'tunjangan'=>$kode_tunjangan,
					'tunjangan_um'=>$tunjangan_um,
					'besar_tunjangan'=>$besar_tunjangan,//implode(';',$nominal),
					'jenis_hotel'=>$kelas_hotel,
					'jenis_perdin'=>$jenis_perdin,
					'jumlah_hotel'=>$this->input->post('jumlah_kamar'),
					'jumlah_hari'=>$this->input->post('jumlah_hari'),
					'total_biaya_penginapan'=>$this->formatter->getFormatMoneyDb($this->input->post('total_penginapan')),
					// 'tunjangan'=>implode(';',$tunjangan),
					'total_tunjangan'=>$this->formatter->getFormatMoneyDb($na),
					'tugas'=>$this->input->post('tugas'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'dibuat'=>$this->admin,
					'keterangan'=>$this->input->post('keterangan'),
					'status_pd'=>1,
					'validasi_ac'=>2,
				];
				$cekPerdin = $this->model_karyawan->getPerdinWhere(['no_sk'=>$no_sk_old,'driver'=>'1']);
				if($cekPerdin){
					$data=array_merge($data, $this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_perjalanan_dinas',['no_sk'=>$no_sk_old,'driver'=>'1']);
				}else{
					// $data['id_karyawan']=$id_ka;
					$data=array_merge($data, $this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQuery($data,'data_perjalanan_dinas');
				}
				// echo '<pre>';
				// print_r($data);
				// $dbs = $this->model_global->insertQuery($data,'data_perjalanan_dinas');
			}else{
				$datax=$this->model_global->deleteQuery('data_perjalanan_dinas',['no_sk'=>$no_sk_old,'driver'=>'1']);
			}
			// if($dbs){
			// 	$datax=$this->messages->allGood();
			// }else{
			// 	$datax=$this->messages->allFailure();
			// }
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_perjalanan_dinasOLD(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id=$this->input->post('id');
		$kode=$this->input->post('no_sk');
		$kar_old=$this->input->post('karyawan_old');
		$no_sk_old=$this->input->post('no_sk_old');
		$tgl_berangkat=$this->formatter->getDateFromRange($this->input->post('tanggal'),'start');
		$tgl_sampai=$this->formatter->getDateFromRange($this->input->post('tanggal'),'end');
		$id_karyawan=$this->input->post('id_karyawan');
		$penginapan=$this->input->post('penginapan');
		$jarak=$this->input->post('jarak');
		$kode_kendaraan=$this->input->post('kendaraan');
		$kendaraan_umum=$this->input->post('kendaraan_umum');
		$jum_kendaraan=$this->input->post('jum_kendaraan');
		$d_kar_old=$this->otherfunctions->getDataExplode($kar_old,',','all');
		$tujuan=$this->input->post('tujuan');
		if ($kode != "") {
			foreach ($id_karyawan as $idk) {
				$jum_kar=count($id_karyawan);
				$jum_kar_old=count($d_kar_old);
				$gradeKar=$this->model_karyawan->getEmpID($idk)['grade'];
				$na=0;
				// $nominal=[];
				// foreach ($tunjangan as $tunj) {
				// 	$nominal[]=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
				// 	$nominal_tunj=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj)['nominal'];
				// 	$na=$na+$nominal_tunj;
				// }
				$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
				$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
				$na=$na+$nominal_dasar;
				$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
				$na=$na+$nominal_grade;
				// $nominal_pd=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0005')['nominal'];
				$nominal_pd=(($jarak >= $jarakMin)?($nominal_dasar+$nominal_grade):0);
				$na=$na+$nominal_pd;
				$nominal_lembur=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
				$na=$na+$nominal_lembur;
				$jarakibp=$this->model_master->getGeneralSetting('JMIBP')['value_int'];
				$nominalIBP = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002');
				if($tujuan == 'plant'){
					$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal']) : $nominalIBP['nominal_min']);
				}else{
					// $nominal_saku = $nominalIBP['nominal_non'];
					$nominal_saku = (($jarak >= $jarakibp) ? ($nominalIBP['nominal_non']) : 0);
				}
				// $nominal_saku=(($jarak >= $jarakMin)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
				$na=$na+$nominal_saku;
				if($jum_kar==1){
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal'];
					$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
				}else{
					// $nominal_bbm=$this->formatter->getFormatMoneyDb($this->input->post('nominal_bbm'));
					$nominal_bbm=$this->model_karyawan->getTunjanganBBM($kode_kendaraan,$kendaraan_umum,$jarak)['nominal']*$jum_kendaraan;
					$n_penginapan=$this->formatter->getFormatMoneyDb($this->input->post('nominal_penginapan'));
					$nominal_penginapan=(($n_penginapan!='')?$n_penginapan:0);
				}
				$input_nominal = $this->input->post('nominal_bbm');
				if(!empty($input_nominal)){
					$nominal_bbm = $this->formatter->getFormatMoneyDb($input_nominal);
				}
				$na=$na+$nominal_bbm;
				$na=$na+$nominal_penginapan;
				$besar_tunjangan = 
					(isset($nominal_dasar)?$nominal_dasar:0).';'
					.(isset($nominal_grade)?$nominal_grade:0).';'
					.(isset($nominal_pd)?$nominal_pd:0).';'
					.(isset($nominal_lembur)?$nominal_lembur:0).';'
					.(isset($nominal_saku)?$nominal_saku:0);
				if($tujuan=='plant'){
					$lokasi_tujuan=null;
					$plant_tujuan=$this->input->post('plant_tujuan');
					$jarak=$this->model_master->jarakAntarPlant($this->input->post('plant_asal'),$this->input->post('plant_tujuan'))['jarak'];
				}else{
					$lokasi_tujuan=$this->input->post('lokasi_tujuan');
					$plant_tujuan=null;
					$jarak=$this->input->post('jarak');
				}
				$data=[
					'id_karyawan'=>$idk,
					'no_sk'=>strtoupper($kode),
					'tgl_berangkat'=>$tgl_berangkat,
					'tgl_sampai'=>$tgl_sampai,
					'tgl_pulang'=>$this->formatter->getDateTimeFormatDb($this->input->post('tanggal_pulang')),
					'plant'=>$tujuan,
					'plant_asal'=>$this->input->post('plant_asal'),
					'plant_tujuan'=>$plant_tujuan,
					'lokasi_tujuan'=>$lokasi_tujuan,
					'jarak'=>$jarak,
					'kendaraan'=>$kode_kendaraan,
					'nama_kendaraan'=>$kendaraan_umum,
					'nominal_bbm'=>$nominal_bbm,
					'jumlah_kendaraan'=>$jum_kendaraan,
					'menginap'=>$this->input->post('menginap'),
					'jenis_hotel'=>$this->input->post('kelas_hotel'),
					'nominal_penginapan'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal_penginapan')),
					'jumlah_hotel'=>$this->input->post('jumlah_kamar'),
					'jumlah_hari'=>$this->input->post('jumlah_hari'),
					'total_biaya_penginapan'=>$this->formatter->getFormatMoneyDb($this->input->post('total_penginapan')),
					'nama_penginapan'=>$penginapan,
					'nominal_penginapan'=>$nominal_penginapan,
					'besar_tunjangan'=>$besar_tunjangan,
					'total_tunjangan'=>$na,
					'tugas'=>$this->input->post('tugas'),
					'mengetahui'=>$this->input->post('mengetahui'),
					'menyetujui'=>$this->input->post('menyetujui'),
					'dibuat'=>$this->admin,
					'keterangan'=>$this->input->post('keterangan'),
				];					
				if(in_array($idk, $d_kar_old)){
					$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$idk]);
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_karyawan)){
							$datax=$this->model_global->deleteQuery('data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$k_old]);
						}
					}
				}else{
					$data['id_karyawan']=$idk;
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQuery($data,'data_perjalanan_dinas');
					foreach ($d_kar_old as $k_old){
						if(!in_array($k_old, $id_karyawan)){
							$datax=$this->model_global->deleteQuery('data_perjalanan_dinas',['no_sk'=>$no_sk_old,'id_karyawan'=>$k_old]);
						}
					}
				}
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_kode_akun(){
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id = $this->input->post('id');
		if(!empty($id)){
			$data=[
				'kode_perjalanan_dinas'=>$this->input->post('kode_perdin'),
				'kode_akun'=>strtoupper($this->input->post('kode_akun')),
				'nominal'=>$this->formatter->getFormatMoneyDb($this->input->post('nominal')),
				'keterangan'=>$this->input->post('keterangan'),
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_pd_kode_akun',['id'=>$id]);
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function change_status_perjalanan_dinas(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('no_sk');
		$val_db=$this->input->post('validasi_db');
		$vali=$this->input->post('validasi');
		parse_str($this->input->post('form'),$form);
		if ($vali == 1){
			$tanggal_pulang=$form['tanggal_pulang'];
			$jam_pulang=$form['jam_pulang'];
		}
		if($val_db==2 && $vali==1 || $val_db==0 && $vali==1){
			$data=[
				'validasi_ac'=>$vali,
				'status_pd'=>2,
				'val_tgl_berangkat'=>$this->formatter->getDateFromRange($form['tanggal'],'start'),
				'val_tgl_sampai'=>$this->formatter->getDateFromRange($form['tanggal'],'end'),
				// 'val_tgl_pulang'=>$this->formatter->getDateTimeFormatDb($form['tanggal_pulang']),
				'val_tgl_pulang'=>$this->formatter->getDateFormatDb($form['tanggal_pulang']).' '.$jam_pulang,
				'val_kendaraan'=>$form['kendaraan'],
				'val_kendaraan_umum'=>$form['kendaraan_umum'],
				'val_jum_kendaraan'=>$form['jum_kendaraan'],
				'val_nominal_bbm'=>$this->formatter->getFormatMoneyDb($form['nominal_bbm_per']),
				// 'val_nominal_bbm'=>$this->formatter->getFormatMoneyDb($form['nominal_bbm']),
				'val_menginap'=>$form['menginap_val'],
				'val_penginapan'=>$form['penginapan'],
				'val_nominal_penginapan'=>$this->formatter->getFormatMoneyDb($form['nominal_penginapan']),
				'val_jenis_hotel'=>(isset($form['kelas_hotel'])?$form['kelas_hotel']:null),
				'val_jumlah_kamar'=>(isset($form['jumlah_kamar'])?$form['jumlah_kamar']:null),
				'val_jumlah_hari'=>(isset($form['jumlah_hari'])?$form['jumlah_hari']:null),
				'val_total_penginapan'=>(isset($form['total_penginapan'])?$this->formatter->getFormatMoneyDb($form['total_penginapan']):null),				
			];
		}elseif($val_db==1 && $vali==0){
			$data=[	'validasi_ac'=>$vali,'val_kendaraan'=>null,'val_kendaraan_umum'=>null,'val_jum_kendaraan'=>null,'val_nominal_bbm'=>null,'val_menginap'=>null,'val_penginapan'=>null,'val_nominal_penginapan'=>null, 'status_pd'=>3,
			];
		}else{
			$data=[	'validasi_ac'=>$vali,'val_kendaraan'=>null,'val_kendaraan_umum'=>null,'val_jum_kendaraan'=>null,'val_nominal_bbm'=>null,'val_menginap'=>null,'val_penginapan'=>null,'val_nominal_penginapan'=>null, 'status_pd'=>3,
			];
		}
		if($val_db != 1 && $vali != 0){
			$dataPerDin  =$this->model_karyawan->getPerjalananDinasKodeSK($id);
			$id_karyawan=[];
			foreach ($dataPerDin as $d) {
				if($d->driver != '1'){
					$search = [
						'tanggal' => $form['tanggal'],
						'tanggal_pulang' => $form['tanggal_pulang'],
						'jam_pulang' => $jam_pulang,
					];
					$gradeKar=$this->model_karyawan->getEmpID($d->id_karyawan)['grade'];
					$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
					$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
					$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
					$tglMulai=$this->otherfunctions->getDataExplode($d->tgl_berangkat,' ','start');
					$tglSelesai=$this->otherfunctions->getDataExplode($d->tgl_sampai,' ','start');
					$dataPresensi=[];
					while ($tglMulai <= $tglSelesai)
					{
						$dataPresensi[]=[
							'id_karyawan'   =>$d->id_karyawan,
							'tanggal'    =>$tglMulai,
						];
						$tglMulai = date('Y-m-d', strtotime($tglMulai . ' +1 day'));
					}
					foreach ($dataPresensi as $dp => $value) {
						if($vali==1){
							$value['no_perjalanan_dinas']=$d->no_sk;
						}else{
							$value['no_perjalanan_dinas']=null;
						}
						$cek=$this->model_karyawan->checkPresensiDate($d->id_karyawan,$value['tanggal']);
						if($cek){
							$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
							$this->model_global->updateQueryNoMsg($value,'data_presensi',['id_karyawan'=>$d->id_karyawan,'tanggal'=>$value['tanggal']]);
						}else{
							$value=array_merge($value,$this->model_global->getCreateProperties($this->admin));
							$this->model_global->insertQueryNoMsg($value,'data_presensi');
						}
					}
					$nominal_tambahan=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
					if($form['tujuan'] == 'non_plant'){
						$jarakMinNon=$this->model_master->getGeneralSetting('MJPDN')['value_int'];
						$nominal_non=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'NONPLANT')['nominal'];
						$uang_makan_dasar = $nominal_dasar+$nominal_non;
						$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMinNon,$form['jarak'],$nominal_tambahan, 'edit');
						$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam'];
						$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'];
						$jarakMinimal = $jarakMinNon;
					}else{
						$uang_makan_dasar = $nominal_dasar+$nominal_grade;
						$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMin,$form['jarak'],$nominal_tambahan, 'edit');
						$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam']+$dx['uang_makan']['nTambahan'];
						$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'].';lembur:'.$dx['uang_makan']['nTambahan'];
						$jarakMinimal = $jarakMin;
					}
					//========= Insentif Bantuan Plan ==============
					$jarakibp=$this->model_master->getGeneralSetting('JMIBP')['value_int'];
					$nominalIBP = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002');
					if($form['tujuan'] == 'plant'){
						$nominal_saku = (($form['jarak'] >= $jarakibp) ? ($nominalIBP['nominal']) : $nominalIBP['nominal_min']);
					}else{
						$nominal_saku = (($form['jarak'] >= $jarakibp) ? ($nominalIBP['nominal_non']) : 0);
					}
					// $nominal_saku=(($form['jarak'] >= $jarakMinimal)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
					//============ INSENTIF INAP ===================
					$nominalInap = 0;
					if($dx['tgl_sampai'] != $dx['tgl_pulang']){
						if($dx['selisihFromSampai']['jam'] > 8){
							$nominalInap = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202301140001')['nominal'];
						}
					}
					//============ STORING ===================
					$whereSt = 'emp.id_karyawan="'.$d->id_karyawan.'" AND (jbt.kode_bagian="BAG201910160002" OR jbt.kode_bagian="BAG201910170001") AND emp.status_emp="1"';
					$karSt = $this->model_karyawan->getEmployeeWhere($whereSt, true);
					$nominal_storing = 0;
					if(!empty($karSt)){
						$jarakStoring=$this->model_master->getGeneralSetting('JMSTORING')['value_int'];
						$nominalStoring = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202302090001');
						$nominal_storing = (($form['jarak'] >= $jarakStoring) ? ($nominalStoring['nominal']) : $nominalStoring['nominal_min']);
					}
					//============ OTHER ===================
					$wherex = 'NOT(a.kode = "KAPD0001" OR a.kode = "KAPD0002" OR a.kode = "KAPD0003" OR a.kode = "KAPD0004" OR a.kode = "KAPD0005" OR a.kode = "KAPD0006" OR a.kode = "NONPLANT" OR a.kode = "KAPD202301140001" OR a.kode = "KAPD202302090001")';
					$tunjanganx=$this->model_master->getKategoriDinasWhere($wherex);
					$kode_tunj=[];
					$besar_tunj=[];
					if(!empty($tunjanganx)){
						foreach ($tunjanganx as $tunj) {
							$kode_tunj[]=$tunj->kode;
							$nominalx=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal'];
							$nominalxx = (!empty($nominalx)?$nominalx:0);
							$besar_tunj[]=($nominalxx*$dx['selisih']['hari']);
						}
					} 
					$kode_tunjanganx = implode($kode_tunj,";");
					$besar_tunjanganx = implode($besar_tunj,";");
					$besar_tunjangan=(isset($nominal_pd)?$nominal_pd:0).';'.(isset($nominal_saku)?($nominal_saku*$dx['selisih']['hari']):0).';'.
					(isset($nominalInap)?($nominalInap*$dx['selisihFromSampai']['hari']):0).';'.
					(isset($nominal_storing)?($nominal_storing*$dx['selisih']['hari']):0);
					$kode_tunjangan="UM;KAPD0002;KAPD202301140001;KAPD202302090001";
					if(!empty($kode_tunjanganx)){
						$kode_tunjangan = $kode_tunjangan.';'.$kode_tunjanganx;
						$besar_tunjangan = $besar_tunjangan.';'.$besar_tunjanganx;
					}
					$where=['no_sk'=>$id,'id_karyawan'=>$d->id_karyawan];
					$data['val_tunjangan_um']=$tunjangan_um;
					$data['val_tunjangan']=$kode_tunjangan;
					$data['val_besar_tunjangan']=$besar_tunjangan;
					// echo'<pre>';
					// print_r($data);
					$dbs=$this->model_global->updateQuery($data,'data_perjalanan_dinas',$where);
				}else{
					$search = [
						'tanggal' => $form['tanggal'],
						'tanggal_pulang' => $form['tanggal_pulang'],
						'jam_pulang' => $jam_pulang,
					];
					$gradeKar='GRD201908141604';
					$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
					$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
					$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
					$tglMulai=$this->otherfunctions->getDataExplode($d->tgl_berangkat,' ','start');
					$tglSelesai=$this->otherfunctions->getDataExplode($d->tgl_sampai,' ','start');
					$nominal_tambahan=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0006')['nominal'];
					if($form['tujuan'] == 'non_plant'){
						$jarakMinNon=$this->model_master->getGeneralSetting('MJPDN')['value_int'];
						$nominal_non=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'NONPLANT')['nominal'];
						$uang_makan_dasar = $nominal_dasar+$nominal_non;
						$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMinNon,$form['jarak'],$nominal_tambahan, 'edit');
						$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam'];
						$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'];
						$jarakMinimal = $jarakMinNon;
					}else{
						$uang_makan_dasar = $nominal_dasar+$nominal_grade;
						$dx = $this->payroll->getTanggalJamPerdin($search,$uang_makan_dasar,$jarakMin,$form['jarak'],$nominal_tambahan, 'edit');
						$nominal_pd = $dx['uang_makan']['nPagi']+$dx['uang_makan']['nSiang']+$dx['uang_makan']['nMalam']+$dx['uang_makan']['nTambahan'];
						$tunjangan_um = 'pagi:'.$dx['uang_makan']['nPagi'].';siang:'.$dx['uang_makan']['nSiang'].';malam:'.$dx['uang_makan']['nMalam'].';lembur:'.$dx['uang_makan']['nTambahan'];
						$jarakMinimal = $jarakMin;
					}
					//========= Insentif Bantuan Plan ==============
					// $jarakibp=$this->model_master->getGeneralSetting('JMIBP')['value_int'];
					// $nominalIBP = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002');
					// if($form['tujuan'] == 'plant'){
					// 	$nominal_saku = (($form['jarak'] >= $jarakibp) ? ($nominalIBP['nominal']) : $nominalIBP['nominal_min']);
					// }else{
					// 	$nominal_saku = (($form['jarak'] >= $jarakibp) ? ($nominalIBP['nominal_non']) : 0);
					// }
					$nominal_saku = 0;
					// $nominal_saku=(($form['jarak'] >= $jarakMinimal)?($this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0002')['nominal']):0);
					//============ INSENTIF INAP ===================
					$nominalInap = 0;
					// if($dx['tgl_sampai'] != $dx['tgl_pulang']){
					// 	if($dx['selisihFromSampai']['jam'] > 8){
					// 		$nominalInap = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202301140001')['nominal'];
					// 	}
					// }
					//============ STORING ===================
					// $whereSt = 'emp.id_karyawan="'.$d->id_karyawan.'" AND (jbt.kode_bagian="BAG201910160002" OR jbt.kode_bagian="BAG201910170001") AND emp.status_emp="1"';
					// $karSt = $this->model_karyawan->getEmployeeWhere($whereSt, true);
					// $nominal_storing = 0;
					// if(!empty($karSt)){
					// 	$jarakStoring=$this->model_master->getGeneralSetting('JMSTORING')['value_int'];
					// 	$nominalStoring = $this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD202302090001');
					// 	$nominal_storing = (($form['jarak'] >= $jarakStoring) ? ($nominalStoring['nominal']) : $nominalStoring['nominal_min']);
					// }
					//============ OTHER ===================
					$wherex = 'NOT(a.kode = "KAPD0001" OR a.kode = "KAPD0002" OR a.kode = "KAPD0003" OR a.kode = "KAPD0004" OR a.kode = "KAPD0005" OR a.kode = "KAPD0006" OR a.kode = "NONPLANT" OR a.kode = "KAPD202301140001" OR a.kode = "KAPD202302090001")';
					$tunjanganx=$this->model_master->getKategoriDinasWhere($wherex);
					$kode_tunj=[];
					$besar_tunj=[];
					if(!empty($tunjanganx)){
						foreach ($tunjanganx as $tunj) {
							$kode_tunj[]=$tunj->kode;
							$nominalx=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,$tunj->kode)['nominal'];
							$nominalxx = (!empty($nominalx)?$nominalx:0);
							$besar_tunj[]=($nominalxx*$dx['selisih']['hari']);
						}
					} 
					$kode_tunjanganx = implode($kode_tunj,";");
					$besar_tunjanganx = implode($besar_tunj,";");
					$besar_tunjangan=(isset($nominal_pd)?$nominal_pd:0).';'.(isset($nominal_saku)?($nominal_saku*$dx['selisih']['hari']):0).';'.
					(isset($nominalInap)?($nominalInap*$dx['selisihFromSampai']['hari']):0).';'.
					(isset($nominal_storing)?($nominal_storing*$dx['selisih']['hari']):0);
					$kode_tunjangan="UM;KAPD0002;KAPD202301140001;KAPD202302090001";
					if(!empty($kode_tunjanganx)){
						$kode_tunjangan = $kode_tunjangan.';'.$kode_tunjanganx;
						$besar_tunjangan = $besar_tunjangan.';'.$besar_tunjanganx;
					}
					$where=['no_sk'=>$id,'id_karyawan'=>$d->id_karyawan];
					$data['val_tunjangan_um']=$tunjangan_um;
					$data['val_tunjangan']=$kode_tunjangan;
					$data['val_besar_tunjangan']=$besar_tunjangan;
					// echo'<pre>';
					// print_r($data);
					$dbs=$this->model_global->updateQuery($data,'data_perjalanan_dinas',$where);

				}
			}
		}else{
			$where=['no_sk'=>$id];
			$dbs=$this->model_global->updateQuery($data,'data_perjalanan_dinas',$where);
		}
		// if ($eml && $dbs){
		// 	$datax=$this->messages->customGood('Email Terkirim Dan Data Telah Tersimpan di Database..');
		// }elseif($dbs){
		// 	$datax=$this->messages->customWarning('Email Tidak Terkirim Dan Data Telah Tersimpan di Database..');
		// }elseif($eml){
		// 	$datax=$this->messages->customWarning('Email Terkirim Dan Data Tidak Tersimpan di Database..');
		// }else{
		// 	$datax=$this->messages->allFailure();
		// }
		if($dbs){
			$datax=$this->messages->allGood();
		}else{
			$datax=$this->messages->allFailure();
		}
		echo json_encode($datax);
	}
	public function add_end_proses_perdin(){
		if (!$this->input->is_ajax_request()) 
		   redirect   ('not_found');
		$kode_trans = $this->input->post('kode_trans');
		$kode       = $this->input->post('kode_akun');
		if(!empty($kode_trans) && !empty($kode)){
			$value['status_pd']=3;
			$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
			$this->model_global->updateQueryNoMsg($value,'data_perjalanan_dinas',['no_sk'=>$kode_trans,]);
			$nominal    = $this->input->post('nominal_end');
			$keterangan = $this->input->post('keterangan');
			foreach ($kode as $keyCode => $valCode) {
				$data=[ 'kode_perjalanan_dinas'	=>$kode_trans,
						'kode_akun' 			=>$valCode,
						'nominal'   			=>$nominal[$keyCode],
						'keterangan'			=>$keterangan[$keyCode],
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'data_pd_kode_akun');
			}
		}elseif(!empty($kode_trans) && empty($kode)){
			$value['status_pd']=3;
			$value=array_merge($value,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($value,'data_perjalanan_dinas',['no_sk'=>$kode_trans,]);
		}else{
			$datax = $this->messages->customFailure('Data Kosong');
		}
		echo json_encode($datax);
	}
	public function addNewKoreksi(){
		$kode_perdin = $this->input->post('kode_perdin');
		$kodeEncrypt = $this->codegenerator->encryptChar($kode_perdin);
		$total_transport       = $this->formatter->getFormatMoneyDb($this->input->post('total_transport'));
		$total_penginapan = $this->formatter->getFormatMoneyDb($this->input->post('total_penginapan'));
		$id_karyawan = $this->input->post('id_karyawan');
		$tunjangan = $this->input->post('tunjangan');
		$nTunjangan = $this->codegenerator->decryptChar($tunjangan);
		$um_pagi = $this->input->post('um_pagi');
		$um_siang = $this->input->post('um_siang');
		$um_malam = $this->input->post('um_malam');
		$um_tambahan = $this->input->post('um_tambahan');
		// echo '<pre>';
		// print_r($id_karyawan);
		// print_r($um_tambahan);
		$tunjangan_um = [];
		if(!empty($id_karyawan)){
			foreach ($id_karyawan as $key => $id) {
				$umLembur = (isset($um_tambahan[$key])) ? $this->formatter->getFormatMoneyDb($um_tambahan[$key]) : null;
				$tunjangan_um[$id] = 'pagi:'.$this->formatter->getFormatMoneyDb($um_pagi[$key]).';siang:'.$this->formatter->getFormatMoneyDb($um_siang[$key]).';malam:'.$this->formatter->getFormatMoneyDb($um_malam[$key]).';lembur:'.$umLembur.'';
			}
		}
		$tun = [];
		$kodeTun = [];
		$tunjangan_other = [];
		if(!empty($nTunjangan)){
			foreach ($nTunjangan as $key => $v) {
				$tun[$key] = $this->input->post($key);
				$kodeTun[] = $key;
				if(!empty($id_karyawan)){
					foreach ($id_karyawan as $kx => $id) {
						if($key == 'UM'){
							$tunjangan_other[$id][$key] = 0;
						}else{
							$tunjangan_other[$id][$key] = $this->formatter->getFormatMoneyDb($tun[$key][$kx]);
						}
					}
				}
			}
		}
		$kodeTunjangan = implode(';', $kodeTun);
		$nilaiTunjangan = [];
		if(!empty($tunjangan_other)){
			foreach ($tunjangan_other as $key => $value) {
				$nilaiTunjangan[$key] = implode(';', $value);
			}
		}
		$dataPerdin = $this->model_karyawan->getPerjalananDinasKodeSK($kode_perdin);
		if(!empty($dataPerdin)){
			foreach ($dataPerdin as $dp) {
				$newD = $this->model_karyawan->getPerjalananDinasID($dp->id_pd, null, true);
				unset($newD['nama_buat']);
				unset($newD['nama_update']);
				unset($newD['nama_karyawan']);
				unset($newD['nik_karyawan']);
				unset($newD['nama_jabatan']);
				unset($newD['nama_bagian']);
				unset($newD['nama_loker']);
				unset($newD['nama_mengetahui']);
				unset($newD['jbt_mengetahui']);
				unset($newD['nama_menyetujui']);
				unset($newD['jbt_menyetujui']);
				unset($newD['nama_dibuat']);
				unset($newD['nama_kendaraan_j']);
				unset($newD['nama_plant_tujuan']);
				unset($newD['nama_plant_asal']);
				unset($newD['val_nama_kendaraan_j']);
				unset($newD['tunjangan']);
				unset($newD['tunjangan_um']);
				unset($newD['besar_tunjangan']);
				unset($newD['val_tunjangan']);
				unset($newD['val_tunjangan_um']);
				unset($newD['val_besar_tunjangan']);
				unset($newD['status']);
				unset($newD['create_date']);
				unset($newD['update_date']);
				unset($newD['create_by']);
				unset($newD['update_by']);
				unset($newD['jbt_dibuat']);
				unset($newD['nominal_bbm']);
				unset($newD['total_biaya_penginapan']);
				unset($newD['val_nominal_bbm']);
				unset($newD['val_total_penginapan']);
				$newD['tunjangan'] = $kodeTunjangan;
				$newD['tunjangan_um'] = $tunjangan_um[$newD['id_karyawan']];
				$newD['besar_tunjangan'] = $nilaiTunjangan[$newD['id_karyawan']];
				$newD['val_tunjangan'] = $kodeTunjangan;
				$newD['val_tunjangan_um'] = $tunjangan_um[$newD['id_karyawan']];
				$newD['val_besar_tunjangan'] = $nilaiTunjangan[$newD['id_karyawan']];
				$newD['nominal_bbm'] = $total_transport;
				$newD['total_biaya_penginapan'] = $total_penginapan;
				$newD['val_nominal_bbm'] = $total_transport;
				$newD['val_total_penginapan'] = $total_penginapan;
				$cek = $this->model_karyawan->getKoreksiPerdin(['id_karyawan'=>$newD['id_karyawan'], 'no_sk'=>$kode_perdin]);
				if(empty($cek)){
					$data=array_merge($newD, $this->model_global->getCreateProperties($this->admin));
					$dbs = $this->model_global->insertQuery($data,'data_perjalanan_dinas_koreksi');
				}else{
					$data=array_merge($newD, $this->model_global->getUpdateProperties($this->admin));
					$dbs = $this->model_global->updateQuery($data,'data_perjalanan_dinas_koreksi', ['id_karyawan'=>$newD['id_karyawan'], 'no_sk'=>$kode_perdin]);
				}
				// print_r($dbs);
			}
		}
		redirect('pages/koreksi_perjalanan_dinas/'.$kodeEncrypt);
	}
// ================================================= JADWAL KERJA ==========================================================

	public function data_jadwal_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				parse_str($this->input->post('form'), $post_form);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$filter=(!empty($access['l_ac']['ftr']))?$access['kode_bagian']:null;
				$data=$this->model_karyawan->getListJdwalKerja($post_form,null,null);
				// $data=$this->model_karyawan->getListJdwalKerja($post_form,null,$filter);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_jadwal,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datar=[
						$d->id_jadwal,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$d->nama_bagian,
					];
					$datan = [];
					for ($i=1; $i <= 31; $i++) { 
						$tgln = 'tgl_'.$i;
				        $ii =($i < 10)?'0'.$i:$i;
						$datex = date($d->tahun.'-'.$this->formatter->zeroPadding($d->bulan).'-'.$ii);
						$hari = $this->formatter->getNameOfDay($datex);
    					$bgr = ($hari=='Minggu') ? 'text-red' : '';
						$lbr = $this->model_master->cekHariLibur($datex,'date');
    					$bgr_lbr = (!empty($lbr)) ? ' text-red' : '';
						if(!empty($d->$tgln)){
							$shift = $this->model_master->getMasterShiftKode($d->$tgln)['nama'];
						}else{
							$shift = $this->otherfunctions->getMark();
						}
						$datan[] = '<a onclick="view_shift('.$d->id_jadwal.','.$i.')" class="'.$bgr.$bgr_lbr.'">'.$shift.'</a>';
					}
					$datay = [$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,$properties['aksi']];
					$datax['data'][] = array_merge($datar,$datan,$datay); 
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_jadwal');
				$data=$this->model_karyawan->getListJdwalKerjaId($id);
				$status = ($data['status']=='1') ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Tidak Aktif</b>';
				$table = '<table class="table table-bordered data-table" style="table-layout: auto;">
	        				<thead>
	        					<tr class="bg-blue">
	        						<th>No.</th>
	        						<th>Tanggal</th>
	        						<th>Kode Shift</th>
	        						<th>Shift</th>
	        						<th>Jam Masuk</th>
	        						<th>Jam Pulang</th>
	        					</tr>
	        				</thead>
	        				<tbody>';
							$tglakhir = date('t', strtotime($data['tahun'].'-'.$this->formatter->zeroPadding($data['bulan']).'-01'));
    						for ($i=1; $i <= $tglakhir; $i++) {
								$tgln = 'tgl_'.$i;
								$mulai = 'start_'.$i;
								$selesai = 'end_'.$i;
								$getShift = $this->model_master->getMasterShiftKode($data[$tgln]);
								$shift = (!empty($getShift['nama'])) ? $getShift['nama'] : $this->otherfunctions->getMark();
								$kode_shift = (!empty($data[$tgln])) ? $data[$tgln] : $this->otherfunctions->getMark();
								if($kode_shift=='CSTM'){
									$jam_mulai = (!empty($data[$mulai])) ? $this->formatter->timeFormatUser($data[$mulai]).'  WIB' : $this->otherfunctions->getMark();
									$jam_selesai = (!empty($data[$selesai])) ? $this->formatter->timeFormatUser($data[$selesai]).'  WIB' : $this->otherfunctions->getMark();
								}else{
									$jam_mulai = (!empty($getShift['jam_mulai'])) ? $this->formatter->timeFormatUser($getShift['jam_mulai']).'  WIB' : $this->otherfunctions->getMark();
									$jam_selesai = (!empty($getShift['jam_selesai'])) ? $this->formatter->timeFormatUser($getShift['jam_selesai']).'  WIB' : $this->otherfunctions->getMark();
								}
				                $ii =($i < 10)?'0'.$i:$i;
    							$datex = date($data['tahun'].'-'.$this->formatter->zeroPadding($data['bulan']).'-'.$ii);
    							$hari = $this->formatter->getNameOfDay($datex);
    							$bgr = ($hari=='Minggu') ? 'bg-red' : '';
    							$harix = ($hari=='Minggu') ? 'Minggu' : '';
    							$tooltip = ($harix!=null) ? 'data-toggle="tooltip" title="'.$harix.'"': '';
    							$lbr = $this->model_master->cekHariLibur($datex,'date');
    							$bgr_lbr = (!empty($lbr)) ? 'bg-red' : '';
    							$table .= '<tr class="'.$bgr.' '.$bgr_lbr.' '.$tooltip.' "'.$lbr.'">
			        							<td>'.$i.'.</td>
				        						<td>'.$hari.', '.$ii.' '.$this->formatter->getNameOfMonth($data['bulan']).' '.$data['tahun'].'</td>
				        						<td>'.$kode_shift.'</td>
				        						<td>'.$shift.'</td>
				        						<td>'.$jam_mulai.'</td>
				        						<td>'.$jam_selesai.'</td>
				        					</tr>';
    						}
	        	$table .= '</tbody>
	        			</table>';

	        	$table_edit = '<table class="table table-bordered data-table" style="table-layout: auto;">
		        				<thead>
		        					<tr class="bg-blue">
		        						<th>No.</th>
		        						<th>Tanggal</th>
		        						<th>Pilih Shift</th>
		        					</tr>
		        				</thead>
		        				<tbody>';
		      	$new_master_shift[null] = null;
				$master_shift = $this->model_karyawan->getShiftForSelect2();
				foreach ($master_shift as $key => $value) {
					$new_master_shift[$key] = $value;
				}
				
				for ($i1=1; $i1 <= $tglakhir; $i1++) { 
					$tgln1 = 'tgl_'.$i1;
					$d_start = 'start_'.$i1;
					$d_end = 'end_'.$i1;
					$d_i_start = 'i_start_'.$i1;
					$d_i_end = 'i_end_'.$i1;
					$selected = $data[$tgln1];
					if($selected=='CSTM'){
						$c_kode_m_shift=null;
					}else{
						$c_kode_m_shift='style="display:none; table-layout: auto;"';
					}
					$start='<div class="input-group bootstrap-timepicker">
						<div class="input-group-addon">
							<i class="fa fa-clock-o"></i>
						</div>
						<input type="text" name="jam_masuk'.$i1.'" id="jam_masuk_one'.$i1.'" class="time-picker2 form-control field" placeholder="Tetapkan Jam Masuk" required="required" value="'.$this->formatter->timeFormatUser($data[$d_start]).'"></div>';
					$i_start='<div class="input-group bootstrap-timepicker">
						<div class="input-group-addon">
							<i class="fa fa-clock-o"></i>
						</div>
						<input type="text" name="jam_i_start'.$i1.'" id="jam_i_in_one'.$i1.'" class="time-picker2 form-control field" placeholder="Tetapkan Jam Masuk" required="required" value="'.$this->formatter->timeFormatUser($data[$d_i_start]).'"></div>';
					$i_end='<div class="input-group bootstrap-timepicker">
						<div class="input-group-addon">
							<i class="fa fa-clock-o"></i>
						</div>
						<input type="text" name="jam_i_end'.$i1.'" id="jam_i_out_one'.$i1.'" class="time-picker2 form-control field" placeholder="Tetapkan Jam Masuk" required="required" value="'.$this->formatter->timeFormatUser($data[$d_i_end]).'"></div>';
					$end='<div class="input-group bootstrap-timepicker">
						<div class="input-group-addon">
							<i class="fa fa-clock-o"></i>
						</div>
						<input type="text" name="jam_pulang'.$i1.'" id="jam_pulang_one'.$i1.'" class="time-picker2 form-control field" placeholder="Tetapkan Jam Masuk" required="required" value="'.$this->formatter->timeFormatUser($data[$d_end]).'"></div>';
				    $ii1 =($i1 < 10)?'0'.$i1:$i1;
    				$datex = date($data['tahun'].'-'.$this->formatter->zeroPadding($data['bulan']).'-'.$ii1);
					$hari = $this->formatter->getNameOfDay($datex);
					$bgr = ($hari=='Minggu') ? 'bg-red' : '';
    					$harix = ($hari=='Minggu') ? 'Minggu' : '';
    					$tooltip = ($harix!=null) ? 'data-toggle="tooltip" title="'.$harix.'"': '';
					$lbr1 = $this->model_master->cekHariLibur($datex,'date');
					$bgr_lbr1 = (!empty($lbr1)) ? 'bg-red' : '';
					$table_edit .= '<tr class="'.$bgr.' '.$bgr_lbr1.'" '.$tooltip.' '.$lbr1.'">
			    						<td>'.$i1.'.</td>
			    						<td>'.$hari.', '.$ii1.' '.$this->formatter->getNameOfMonth($data['bulan']).' '.$data['tahun'].'</td>
										<td>'.form_dropdown('kode_master_shift_'.$i1, $new_master_shift, $selected, 'class="form-control select2" style="width:100%;" id="id_master_shift'.$i1.'"  onchange="shiftCustomEdit('.$i1.')"').'
											<table id="table_custom_shift'.$i1.'" '.$c_kode_m_shift.' class="table table-striped">
												<tr>
													<th width="20%">Jam Masuk</th>
													<td id="td_start'.$i1.'">'.$start.'
													</td>
												</tr>
												<tr>
													<th width="20%">Istirahat Mulai</th>
													<td id="td_end'.$i1.'">'.$i_start.'
													</td>
												</tr>
												<tr>
													<th width="20%">Istirahat Selesai</th>
													<td id="td_i_start'.$i1.'">'.$i_end.'
													</td>
												</tr>
												<tr>
													<th width="20%">Jam Pulang</th>
													<td id="td_i_end'.$i1.'">'.$end.'
													</td>
												</tr>
											</table>
										</td>
			    					</tr>';
				}
		        $table_edit .= '</tbody>
		        			</table>
						<script>
							$(document).ready(function(){
								$(".time-picker2").timepicker({
									showInputs: false,
									showMeridian: false,
									showSeconds: false,
									minuteStep: 1,
								}).attr("readonly", "readonly").css("cursor","pointer");
							})
						</script>';
				$datax=[
					'id'=>$data['id_jadwal'],
					'id_karyawan' => $data['id_karyawan'],
					'tgl_batas'=> $tglakhir,
					'tgl_presensi'=>$this->formatter->getNameOfMonth($data['bulan']).' '.$data['tahun'],
					'nik_karyawan'=>$data['nik'],
					'nama_karyawan'=>$data['nama_karyawan'],
					'nama'=>$data['nama_karyawan'],
					'jabatan_karyawan'=>$data['nama_jabatan'],
					'table'=>$table,
					'table_edit'=>$table_edit,
					'bulan_plain'=>$data['bulan'],
					'tahun_plain'=>$data['tahun'],
					'status'=>$status,
					'create_date'=>$this->formatter->getDateTimeMonthFormatUser($data['create_date']),
					'update_date'=>$this->formatter->getDateTimeMonthFormatUser($data['update_date']),
					'create_by'=>(!empty($data['nama_buat'])) ? $data['nama_buat']:$this->otherfunctions->getMark($data['nama_buat']),
					'update_by'=>(!empty($data['nama_update'])) ? $data['nama_update']:$this->otherfunctions->getMark($data['nama_update']),
				];
				echo json_encode($datax);
			}elseif ($usage == 'date_one') {
				$id = $this->input->post('id_jadwal');
				$tgl = $this->input->post('tgl');
				if(!empty($tgl) || !empty($id)){
					$data=$this->model_karyawan->getListJdwalKerjaId($id);
					$tgln = 'tgl_'.$tgl;
					$mulai = 'start_'.$tgl;
					$selesai = 'end_'.$tgl;
					$i_mulai = 'i_start_'.$tgl;
					$i_selesai = 'i_end_'.$tgl;
					for ($i2=1; $i2 <= $tgl; $i2++) {
						$ii2 =($i2 < 10)?'0'.$i2:$i2;
						$getShift = $this->model_master->getMasterShiftKode($data[$tgln]);
	    				$datex = date($data['tahun'].'-'.$this->formatter->zeroPadding($data['bulan']).'-'.$ii2);
						$hari = $this->formatter->getNameOfDay($datex);
						$lbr_minggu = ($hari=='Minggu') ? ' ( <span style="color: red;"> Minggu </span> )' : '';
						$libur = $this->model_master->cekHariLibur($datex,'date');
						$lbr_raya =  (!empty($libur)) ? ' ( <span style="color: red;"> '.$libur.' </span> )' : '';

						if(!empty($getShift['kode_master_shift'])){
							if($getShift['kode_master_shift']=='CSTM'){
								$kode_shift = $getShift['kode_master_shift'];
								$nama_shift = $getShift['nama'];
								$jam_shift = $this->formatter->timeFormatUser($data[$mulai]).' - '.$this->formatter->timeFormatUser($data[$selesai]);
							}else{
								$kode_shift = $getShift['kode_master_shift'];
								$nama_shift = $getShift['nama'];
								$jam_shift = $this->formatter->timeFormatUser($getShift['jam_mulai']).' - '.$this->formatter->timeFormatUser($getShift['jam_selesai']);
							}
						}else{
							$kode_shift = '<i class="fa fa-times-circle" style="color:red" data-toggle="tooltip" title="Unknown"></i>';
							$nama_shift = '<i class="fa fa-times-circle" style="color:red" data-toggle="tooltip" title="Unknown"></i>';
							$jam_shift = '<i class="fa fa-times-circle" style="color:red" data-toggle="tooltip" title="Unknown"></i>';
						}
						$datax = [
							'id'=>$data['id_jadwal'],
							'id_karyawan'=>$data['id_karyawan'],
							'tgl_urut'=>$tgl,
							'nama_karyawan'=>$data['nama_karyawan'],
							'jabatan'=>$data['nama_jabatan'],
							'kode_shift'=>$kode_shift,
							'shift'=>$nama_shift,
							'jam'=>$jam_shift,
							'bulan'=>$data['bulan'],
							'tahun'=>$data['tahun'],
							'tgl_jadwal'=>$hari.', '.$ii2.' '.$this->formatter->getNameOfMonth($data['bulan']).' '.$data['tahun'].$lbr_minggu.$lbr_raya,
							'tgl_value'=>$data[$tgln],
							'mulai'=>(!empty($data[$mulai])||($data[$mulai]='00:00:00'))?$this->formatter->timeFormatUser($data[$mulai]):null,
							'i_mulai'=>(!empty($data[$i_mulai])||($data[$i_mulai]='00:00:00'))?$this->formatter->timeFormatUser($data[$i_mulai]):null,
							'i_selesai'=>(!empty($data[$i_selesai])||($data[$i_selesai]='00:00:00'))?$this->formatter->timeFormatUser($data[$i_selesai]):null,
							'selesai'=>(!empty($data[$selesai])||($data[$selesai]='00:00:00'))?$this->formatter->timeFormatUser($data[$selesai]):null,
						];
					}
					echo json_encode($datax);				
				}else{
					echo json_encode($this->messages->notValidParam());
				}
			}elseif($usage=='shift'){
				$data = $this->model_karyawan->getShiftForSelect2();
				echo json_encode($data);
			}elseif($usage=='refresh_shift'){
				$data = $this->model_karyawan->getRefreshShift();
				echo json_encode($data);
			}elseif ($usage == 'view_select') {
				$kode_jabatan = $this->input->post('kode_jabatan');
				// if($kode == 'BAG002'){
				// 	$data = $this->model_karyawan->getEmployeeAllActive();
				// }else{
				// 	$data = $this->model_karyawan->getEmployeeKodeJabatan($kode);
				// }
				$datx=$this->model_master->getJabatanAll();
				$arr=$this->model_master->getDataBawahan($datx, $kode_jabatan);
				$c_jbt=$this->otherfunctions->getDataExplode($arr,';','all');
				$datax=[];
				if($kode_jabatan!=""){
					foreach ($c_jbt as $c) {
						$emp = $this->model_karyawan->getEmployeekodeJabatan($c);
						if ($emp != null){
							foreach ($emp as $e) {
								$datax[]=$e->id_karyawan;
							}
						}
					}
				}
				// $datax=[];
				// foreach ($data as $d) {
				// 	$datax[]=$d->id_karyawan;
				// }
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function add_jadwal_kerja()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$kode_jabatan 		= $this->input->post('kode_jabatan');
		$emp 		= $this->input->post('id_karyawan');
		$kode_master_shift 	= $this->input->post('kode_master_shift');
		$tgl_berlaku 		= $this->input->post('tgl_berlaku');
		$jam_masuk			= $this->input->post('jam_masuk');
		$istirahat_mulai	= $this->input->post('istirahat_mulai');
		$istirahat_selesai	= $this->input->post('istirahat_selesai');
		$jam_pulang			= $this->input->post('jam_pulang');
		$cek_hari=[];
		if (isset($kode_master_shift)) {
			foreach ($kode_master_shift as $kms) {
				$data_shift=$this->model_master->getMasterShiftKode($kms);
				if (isset($data_shift)) {
					$cek_hari[$kms]=$this->otherfunctions->getParseOneLevelVar($data_shift['hari']);
				}
			}
		}
		// $datx=$this->model_master->getJabatanAll();
		// $arr=$this->model_master->getDataBawahan($datx, $kode_jabatan);
		// $c_jbt=$this->otherfunctions->getDataExplode($arr,';','all');
		if($kode_jabatan!=""){
		// 	foreach ($c_jbt as $c) {
		// 		$emp = $this->model_karyawan->getEmployeekodeJabatan($c);
			if ($emp != null){
				foreach ($emp as $id_karyawan) {
					$tanggal=$this->formatter->getPeriodeJadwal($tgl_berlaku,$cek_hari);
					foreach ($tanggal as $k_y=>$y) {
						foreach ($y as $k_m=>$m) {
							if ($k_m < 10) {
								$k_m = str_replace('0', '', $k_m);
							}
							$cek_data=$this->model_karyawan->cekJadwalKerjaIdK($id_karyawan,$k_m,$k_y);
							if($cek_data == null){
								$data=[
									'id_karyawan'=>$id_karyawan,
									'bulan'=>$k_m,
									'tahun'=>$k_y,
								];
								foreach ($m as $day => $kode_shift) {
									$col=$this->formatter->getCols2($day);
									$data[$col]=$kode_shift;
									if($kode_shift=='CSTM'){
										$start=$this->formatter->getColumn($day,'start_');
										$i_start=$this->formatter->getColumn($day,'i_start_');
										$i_end=$this->formatter->getColumn($day,'i_end_');
										$end=$this->formatter->getColumn($day,'end_');
										$data[$start]=$this->formatter->getTimeDb($jam_masuk);
										$data[$i_start]=$this->formatter->getTimeDb($istirahat_mulai);
										$data[$i_end]=$this->formatter->getTimeDb($istirahat_selesai);
										$data[$end]=$this->formatter->getTimeDb($jam_pulang);
									}
								}
								if (!empty($kode_shift) && !empty($k_m) && !empty($k_y) && !empty($day) && !empty($e)) {
									$date=$k_y.'-'.$k_m.'-'.$day;
									$this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
								}
								$datay=array_merge($data,$this->model_global->getCreateProperties($this->admin));
								$datax = $this->model_global->insertQuery($datay,'data_jadwal_kerja');
							}else{
								foreach ($cek_data as $cd) {
									$data=[
										'id_karyawan'=>$id_karyawan,
										'bulan'=>$k_m,
										'tahun'=>$k_y,
									];
									foreach ($m as $day => $kode_shift) {
										$col=$this->formatter->getCols2($day);
										$data[$col]=$kode_shift;
										if($kode_shift=='CSTM'){
											$start=$this->formatter->getColumn($day,'start_');
											$i_start=$this->formatter->getColumn($day,'i_start_');
											$i_end=$this->formatter->getColumn($day,'i_end_');
											$end=$this->formatter->getColumn($day,'end_');
											$data[$start]=$this->formatter->getTimeDb($jam_masuk);
											$data[$i_start]=$this->formatter->getTimeDb($istirahat_mulai);
											$data[$i_end]=$this->formatter->getTimeDb($istirahat_selesai);
											$data[$end]=$this->formatter->getTimeDb($jam_pulang);
										}
										if (!empty($kode_shift) && !empty($cd->bulan) && !empty($cd->tahun) && !empty($day) && !empty($e)) {
											$date=$cd->tahun.'-'.$cd->bulan.'-'.$day;
											$this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
										}
										$where=['id_karyawan'=>$id_karyawan,'bulan'=>$cd->bulan,'tahun'=>$cd->tahun];
										$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
										$this->model_global->updateQueryNoMsg($data,'data_jadwal_kerja',$where);
										$datax=$this->messages->customWarning2('Jadwal Kerja Karyawan Pada Bulan Tersebut Berhasil Diperbarui');
									}
								}
							}
						}
					}
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->customFailure('Tidak Ada Karyawan Dalam Jabatan Tersebut'));
			}
		// 	}
		}else{
			echo json_encode($this->messages->notValidParam());
		}
	}
	public function add_group_jadwal_kerja()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$id_karyawan 		= $this->input->post('id_karyawan');
		$kode_master_shift 	= $this->input->post('kode_master_shift');
		$tgl_berlaku 		= $this->input->post('tgl_berlaku');
		$jam_masuk			= $this->input->post('jam_masuk');
		$istirahat_mulai	= $this->input->post('istirahat_mulai');
		$istirahat_selesai	= $this->input->post('istirahat_selesai');
		$jam_pulang			= $this->input->post('jam_pulang');
		$mode			= $this->input->post('mode');
		// echo '<pre>';
		// print_r($mode);
		$cek_hari=[];
		if (isset($kode_master_shift)) {
			foreach ($kode_master_shift as $kms) {
				$data_shift=$this->model_master->getMasterShiftKode($kms);
				if (isset($data_shift)) {
					$cek_hari[$kms]=$this->otherfunctions->getParseOneLevelVar($data_shift['hari']);
				}
			}
		}
		if($id_karyawan!=""){
			if(in_array('all', $id_karyawan)){
				$karyawan = $this->model_karyawan->getEmployeeAllActiveFilter();
				foreach ($karyawan as $e) {
					$tanggal=$this->formatter->getPeriodeJadwal($tgl_berlaku,$cek_hari);
					foreach ($tanggal as $k_y=>$y) {
						foreach ($y as $k_m=>$m) {
							if ($k_m < 10) {
								$k_m = str_replace('0', '', $k_m);
							}
							$cek_data=$this->model_karyawan->cekJadwalKerjaIdK($e->id_karyawan,$k_m,$k_y);
							if($cek_data == null){
								$data=[
									'id_karyawan'=>$e->id_karyawan,
									'bulan'=>$k_m,
									'tahun'=>$k_y,
								];
								foreach ($m as $day => $kode_shift) {
									$col=$this->formatter->getColumn($day,'tgl_');
									$data[$col]=$kode_shift;
									if($kode_shift=='CSTM'){
										$start=$this->formatter->getColumn($day,'start_');
										$i_start=$this->formatter->getColumn($day,'i_start_');
										$i_end=$this->formatter->getColumn($day,'i_end_');
										$end=$this->formatter->getColumn($day,'end_');
										$data[$start]=$this->formatter->getTimeDb($jam_masuk);
										$data[$i_start]=$this->formatter->getTimeDb($istirahat_mulai);
										$data[$i_end]=$this->formatter->getTimeDb($istirahat_selesai);
										$data[$end]=$this->formatter->getTimeDb($jam_pulang);
									}
									if (!empty($kode_shift) && !empty($k_m) && !empty($k_y) && !empty($day) && !empty($e->id_karyawan)) {
										$date=$k_y.'-'.$k_m.'-'.$day;
										$this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e->id_karyawan);
									}
								}
								$datay=array_merge($data,$this->model_global->getCreateProperties($this->admin));
								$datax = $this->model_global->insertQuery($datay,'data_jadwal_kerja');
							}else{
								if(empty($mode) && $mode != 'on'){
									foreach ($cek_data as $cd) {
										$data=[
											'id_karyawan'=>$e->id_karyawan,
											'bulan'=>$k_m,
											'tahun'=>$k_y,
										];
										foreach ($m as $day => $kode_shift) {
											$col=$this->formatter->getCols2($day);
											$data[$col]=$kode_shift;
											if($kode_shift=='CSTM'){
												$start=$this->formatter->getColumn($day,'start_');
												$i_start=$this->formatter->getColumn($day,'i_start_');
												$i_end=$this->formatter->getColumn($day,'i_end_');
												$end=$this->formatter->getColumn($day,'end_');
												$data[$start]=$this->formatter->getTimeDb($jam_masuk);
												$data[$i_start]=$this->formatter->getTimeDb($istirahat_mulai);
												$data[$i_end]=$this->formatter->getTimeDb($istirahat_selesai);
												$data[$end]=$this->formatter->getTimeDb($jam_pulang);
											}
											if (!empty($kode_shift) && !empty($cd->bulan) && !empty($cd->tahun) && !empty($day) && !empty($e->id_karyawan)) {
												$date=$cd->tahun.'-'.$cd->bulan.'-'.$day;
												$this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e->id_karyawan);
											}
											$where=['id_karyawan'=>$e->id_karyawan,'bulan'=>$cd->bulan,'tahun'=>$cd->tahun];
											$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
											$this->model_global->updateQueryNoMsg($data,'data_jadwal_kerja',$where);
											$datax=$this->messages->customWarning2('Jadwal Kerja Karyawan Pada Bulan Tersebut Berhasil Diperbarui');
										}
									}
								}
							}
						}
					}
				}
			}else{
				foreach ($id_karyawan as $k => $e) {
					$tanggal=$this->formatter->getPeriodeJadwal($tgl_berlaku,$cek_hari);
					foreach ($tanggal as $k_y=>$y) {
						foreach ($y as $k_m=>$m) {
							if ($k_m < 10) {
								$k_m = str_replace('0', '', $k_m);
							}
							$cek_data=$this->model_karyawan->cekJadwalKerjaIdK($e,$k_m,$k_y);
							if($cek_data == null){
								$data=[
									'id_karyawan'=>$e,
									'bulan'=>$k_m,
									'tahun'=>$k_y,
								];
								foreach ($m as $day => $kode_shift) {
									// $col=$this->formatter->getCols2($day);
									$col=$this->formatter->getColumn($day,'tgl_');
									$data[$col]=$kode_shift;
									if($kode_shift=='CSTM'){
										$start=$this->formatter->getColumn($day,'start_');
										$i_start=$this->formatter->getColumn($day,'i_start_');
										$i_end=$this->formatter->getColumn($day,'i_end_');
										$end=$this->formatter->getColumn($day,'end_');
										$data[$start]=$this->formatter->getTimeDb($jam_masuk);
										$data[$i_start]=$this->formatter->getTimeDb($istirahat_mulai);
										$data[$i_end]=$this->formatter->getTimeDb($istirahat_selesai);
										$data[$end]=$this->formatter->getTimeDb($jam_pulang);
									}
									if (!empty($kode_shift) && !empty($k_m) && !empty($k_y) && !empty($day) && !empty($e)) {
										$date=$k_y.'-'.$k_m.'-'.$day;
										$this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
									}
								}
								$datay=array_merge($data,$this->model_global->getCreateProperties($this->admin));
								$datax = $this->model_global->insertQuery($datay,'data_jadwal_kerja');
							}else{
								foreach ($cek_data as $cd) {
									$data=[
										'id_karyawan'=>$e,
										'bulan'=>$k_m,
										'tahun'=>$k_y,
									];
									foreach ($m as $day => $kode_shift) {
										$col=$this->formatter->getCols2($day);
										$data[$col]=$kode_shift;
										if($kode_shift=='CSTM'){
											$start=$this->formatter->getColumn($day,'start_');
											$i_start=$this->formatter->getColumn($day,'i_start_');
											$i_end=$this->formatter->getColumn($day,'i_end_');
											$end=$this->formatter->getColumn($day,'end_');
											$data[$start]=$this->formatter->getTimeDb($jam_masuk);
											$data[$i_start]=$this->formatter->getTimeDb($istirahat_mulai);
											$data[$i_end]=$this->formatter->getTimeDb($istirahat_selesai);
											$data[$end]=$this->formatter->getTimeDb($jam_pulang);
										}
										if (!empty($kode_shift) && !empty($cd->bulan) && !empty($cd->tahun) && !empty($day) && !empty($e)) {
											$date=$cd->tahun.'-'.$cd->bulan.'-'.$day;
											$this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
										}
										$where=['id_karyawan'=>$e,'bulan'=>$cd->bulan,'tahun'=>$cd->tahun];
										$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
										$this->model_global->updateQueryNoMsg($data,'data_jadwal_kerja',$where);
										$datax=$this->messages->customWarning2('Jadwal Kerja Karyawan Pada Bulan Tersebut Berhasil Diperbarui');
									}
								}
							}
						}
					}
				}
			}
			echo json_encode($datax);
		}else{
			echo json_encode($this->messages->notValidParam());
		}
	}
	public function add_group_jadwal_kerja2()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$id_karyawan 		= $this->input->post('id_karyawan');
		$kode_master_shift 	= $this->input->post('kode_master_shift');
		$tgl_berlaku 		= $this->input->post('tgl_berlaku');
		$jam_masuk			= $this->input->post('jam_masuk');
		$istirahat_mulai	= $this->input->post('istirahat_mulai');
		$istirahat_selesai	= $this->input->post('istirahat_selesai');
		$jam_pulang			= $this->input->post('jam_pulang');
		$cek_hari=[];
		if (isset($kode_master_shift)) {
			foreach ($kode_master_shift as $kms) {
				$data_shift=$this->model_master->getMasterShiftKode($kms);
				if (isset($data_shift)) {
					$cek_hari[$kms]=$this->otherfunctions->getParseOneLevelVar($data_shift['hari']);
				}
			}
		}
		if($id_karyawan!=""){
			foreach ($id_karyawan as $k => $e) {
				$tanggal=$this->formatter->getPeriodeJadwal($tgl_berlaku,$cek_hari);
				foreach ($tanggal as $k_y=>$y) {
					foreach ($y as $k_m=>$m) {
						if ($k_m < 10) {
	                		$k_m = str_replace('0', '', $k_m);
	                	}
						$cek_data=$this->model_karyawan->cekJadwalKerjaIdK($e,$k_m,$k_y);
						if($cek_data == null){
							$data=[
								'id_karyawan'=>$e,
								'bulan'=>$k_m,
								'tahun'=>$k_y,
							];
							foreach ($m as $day => $kode_shift) {
								// $col=$this->formatter->getCols2($day);
								$col=$this->formatter->getColumn($day,'tgl_');
								$data[$col]=$kode_shift;
								if($kode_shift=='CSTM'){
									$start=$this->formatter->getColumn($day,'start_');
									$i_start=$this->formatter->getColumn($day,'i_start_');
									$i_end=$this->formatter->getColumn($day,'i_end_');
									$end=$this->formatter->getColumn($day,'end_');
									$data[$start]=$this->formatter->getTimeDb($jam_masuk);
									$data[$i_start]=$this->formatter->getTimeDb($istirahat_mulai);
									$data[$i_end]=$this->formatter->getTimeDb($istirahat_selesai);
									$data[$end]=$this->formatter->getTimeDb($jam_pulang);
								}
								if (!empty($kode_shift) && !empty($k_m) && !empty($k_y) && !empty($day) && !empty($e)) {
									$date=$k_y.'-'.$k_m.'-'.$day;
									$this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
								}
							}
							$datay=array_merge($data,$this->model_global->getCreateProperties($this->admin));
							$datax = $this->model_global->insertQuery($datay,'data_jadwal_kerja');
						}else{
							foreach ($cek_data as $cd) {
								$data=[
									'id_karyawan'=>$e,
									'bulan'=>$k_m,
									'tahun'=>$k_y,
								];
								foreach ($m as $day => $kode_shift) {
									$col=$this->formatter->getCols2($day);
									$data[$col]=$kode_shift;
									if($kode_shift=='CSTM'){
										$start=$this->formatter->getColumn($day,'start_');
										$i_start=$this->formatter->getColumn($day,'i_start_');
										$i_end=$this->formatter->getColumn($day,'i_end_');
										$end=$this->formatter->getColumn($day,'end_');
										$data[$start]=$this->formatter->getTimeDb($jam_masuk);
										$data[$i_start]=$this->formatter->getTimeDb($istirahat_mulai);
										$data[$i_end]=$this->formatter->getTimeDb($istirahat_selesai);
										$data[$end]=$this->formatter->getTimeDb($jam_pulang);
									}
									if (!empty($kode_shift) && !empty($cd->bulan) && !empty($cd->tahun) && !empty($day) && !empty($e)) {
										$date=$cd->tahun.'-'.$cd->bulan.'-'.$day;
										$this->model_karyawan->synctoDataPresensi($date,$kode_shift,$e);
									}
									$where=['id_karyawan'=>$e,'bulan'=>$cd->bulan,'tahun'=>$cd->tahun];
									$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
									$this->model_global->updateQueryNoMsg($data,'data_jadwal_kerja',$where);
									$datax=$this->messages->customWarning2('Jadwal Kerja Karyawan Pada Bulan Tersebut Berhasil Diperbarui');
								}
							}
						}
					}
				}
			}
			echo json_encode($datax);
		}else{
			echo json_encode($this->messages->notValidParam());
		}
	}
	public function edit_all_jadwal_kerja()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');

		$batas_tgl = $this->input->post('data_tgl_batas_edit');
		$id_karyawan = $this->input->post('id_karyawan');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$id = $this->input->post('data_id_edit');
		for ($i=1; $i <= $batas_tgl; $i++) { 
			$tgl = 'tgl_'.$i;
			$mulai = 'start_'.$i;
			$i_mulai = 'i_start_'.$i;
			$i_end = 'i_end_'.$i;
			$end = 'end_'.$i;
			$data[$tgl] 	= $this->input->post('kode_master_shift_'.$i);
			$data[$mulai] 	= (!empty($this->input->post('jam_masuk'.$i))?$this->formatter->getTimeDb($this->input->post('jam_masuk'.$i)):null);
			$data[$i_mulai] = (!empty($this->input->post('jam_i_start'.$i))?$this->formatter->getTimeDb($this->input->post('jam_i_start'.$i)):null);
			$data[$i_end] 	= (!empty($this->input->post('jam_i_end'.$i))?$this->formatter->getTimeDb($this->input->post('jam_i_end'.$i)):null);
			$data[$end] 	= (!empty($this->input->post('jam_pulang'.$i))?$this->formatter->getTimeDb($this->input->post('jam_pulang'.$i)):null);
		}
		if (count($data) >0) {
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_jadwal_kerja',['id_jadwal'=>$id]);
			if (!empty($bulan) && !empty($tahun) && isset($date)) {
				foreach ($date as $tgl => $shift) {
					$date=$tahun.'-'.$bulan.'-'.$tgl;
					$this->model_karyawan->synctoDataPresensi($date,$shift,$id_karyawan);
				}
			}
		}else{
			$datax=$this->messages->customFailure('Data Inputan Kosong. Harap Cek Data Kembali!');
		}
		echo json_encode($datax);
	}
	public function edit_one_jadwal_kerja()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');

		$id_karyawan = $this->input->post('id_karyawan');
		$kode_shif = $this->input->post('kode_master_shift');
		$id = $this->input->post('id');
		$tgl = $this->input->post('tgl_urut');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		if($kode_shif=='CSTM'){
			$start = $this->input->post('jam_masuk');
			$i_start = $this->input->post('istirahat_mulai');
			$i_end = $this->input->post('istirahat_selesai');
			$end = $this->input->post('jam_pulang');
		}else{
			$start = null;
			$i_start = null;
			$i_end = null;
			$end = null;
		}
		if (!empty($id)) {
			$data['tgl_'.$tgl] = $kode_shif;
			$data['start_'.$tgl] = $start;
			$data['i_start_'.$tgl] = $i_start;
			$data['i_end_'.$tgl] = $i_end;
			$data['end_'.$tgl] = $end;
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_jadwal_kerja',['id_jadwal'=>$id]);
			if (!empty($bulan) && !empty($tahun)) {
				$date=$tahun.'-'.$bulan.'-'.$tgl;
				$this->model_karyawan->synctoDataPresensi($date,$kode_shif,$id_karyawan);
			}
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
//================================================================= DATA PRESENSI ===================================================	
	public function import_presensi()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$mesin = $this->input->post('kode_mesin');
		if($mesin=='1'){
			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			$sheet[0]=[
				'range_huruf'=>3,
				'row'=>2,
				'table'=>'data_presensi',
				'column_code'=>'id_karyawan',
				'usage'=>'presensi_one',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'tanggal_import'=>$this->input->post('tanggal_import'),
				'bagian_import'=>$this->input->post('bagian_import'),
				//urutan sama dengan export
				'column'=>[
					2=>'id_finger',3=>'tanggal_waktu',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		}elseif($mesin=='2'){
			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			$sheet[0]=[
				'range_huruf'=>3,
				'row'=>2,
				'table'=>'data_presensi',
				'column_code'=>'id_karyawan',
				'usage'=>'presensi_one',
				// 'usage'=>'presensi_two',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'tanggal_import'=>$this->input->post('tanggal_import'),
				'bagian_import'=>$this->input->post('bagian_import'),
				'column'=>[
					1=>'id_finger',4=>'tanggal_waktu',
				],
				// 'column'=>[
				// 	1=>'id_finger',3=>'tanggal',8=>'jam_mulai',10=>'jam_selesai',
				// ],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function add_presensi()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$id_karyawan = $this->input->post('id_karyawan');
		$tanggal = $this->formatter->getDateFormatDb($this->input->post('tanggal'));
		$jam_mulai = $this->input->post('jam_mulai');
		$jam_selesai = $this->input->post('jam_selesai');
		if ($id_karyawan != "") {
			$libur = $this->model_master->cekHariLiburDate($tanggal)['kode_hari_libur'];
			$kode_libur=(!empty($libur)?$libur:null);
			$izin = $this->model_karyawan->cekIzinCutiPresensi($id_karyawan,$tanggal)['kode_izin_cuti'];
			$kode_izin=(!empty($izin)?$izin:null);
			$lembur = $this->model_karyawan->cekDataLemburPresensi($id_karyawan,$tanggal)['no_lembur'];
			$kode_lembur=(!empty($lembur)?$lembur:null);
			$tgl_perdin =$tanggal.' 00:00:00';
			$perdin = $this->model_karyawan->cekDataPerDinPresensi($id_karyawan,$tgl_perdin)['no_sk'];
			$kode_perdin=(!empty($perdin)?$perdin:null);
			if(!empty($this->model_karyawan->cekJadwalKerjaIdDate($id_karyawan,$tanggal)['kode_shift'])){
				$cekpresensi = $this->model_karyawan->cekPresensiId($id_karyawan,$tanggal);
				if($cekpresensi != 'false'){
					$cekjadwal = $this->model_karyawan->cekPresensiIdKar($id_karyawan,$tanggal)['kode_shift'];
					$data=array(
						'id_karyawan'=>$id_karyawan,
						'tanggal' => $tanggal,
						'jam_mulai'=>$this->formatter->timeFormatDb($jam_mulai),
						'jam_selesai'=>$this->formatter->timeFormatDb($jam_selesai),
						'kode_shift'=>$cekjadwal,
						'kode_hari_libur'=>$kode_libur,
						'kode_ijin'=>$kode_izin,
						'no_spl'=>$kode_lembur,
						'no_perjalanan_dinas'=>$kode_perdin,
					);
					$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQuery($data,'data_presensi');
				}else{
					$datax = $this->messages->customFailure('Karyawan Yang Bersangkutan Sudah Ada');
				}
			}else{
				$kode_shift=$this->input->post('kode_master_shift');
				$month = (int)date('m',strtotime($tanggal));
				$year = date('Y',strtotime($tanggal));
				$day = (int)date('d',strtotime($tanggal));
				$day=($day<10)?str_replace('0','',$day):$day;
				$month=($month<10)?str_replace('0','',$month):$month;
				$cek=$this->model_karyawan->cekJadwalKerjaIdK($id_karyawan,$month,$year);
				if(!empty($cek)){
					$where=['id_karyawan'=>$id_karyawan,'bulan'=>$month,'tahun'=>$year];
					$data_j['tgl_'.$day] = $kode_shift;
					$data_j=array_merge($data_j,$this->model_global->getUpdateProperties($this->admin));
					$this->model_global->updateQueryNoMsg($data_j,'data_jadwal_kerja',$where);
				}else{
					$data_j=[
						'id_karyawan'=>$id_karyawan,
						'bulan'=>$month,
						'tahun'=>$year,
					];
					$data_j['tgl_'.$day] = $kode_shift;
					$datay=array_merge($data_j,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->insertQueryNoMsg($datay,'data_jadwal_kerja');
				}
				$data_p=[
					'id_karyawan'=>$id_karyawan,
					'tanggal' => $tanggal,
					'jam_mulai'=>$this->formatter->timeFormatDb($jam_mulai),
					'jam_selesai'=>$this->formatter->timeFormatDb($jam_selesai),
					'kode_shift'=>$kode_shift,
					'kode_hari_libur'=>$kode_libur,
					'kode_ijin'=>$kode_izin,
					'no_spl'=>$kode_lembur,
					'no_perjalanan_dinas'=>$kode_perdin,
				];
				$data=array_merge($data_p,$this->model_global->getCreateProperties($this->admin));
				$datax = $this->model_global->insertQuery($data,'data_presensi');
			}
		}else{
			$datax = $this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	public function edit_presensi_harian()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$id = $this->input->post('id');
		$id_karyawan = $this->input->post('id_karyawan');
		$jam_mulai = $this->input->post('jam_mulai');
		$jam_selesai = $this->input->post('jam_selesai');
		if ($id != "") {
			$data_p=[
				'jam_mulai'=>$this->formatter->timeFormatDb($jam_mulai),
				'jam_selesai'=>$this->formatter->timeFormatDb($jam_selesai),
			];
			$data=array_merge($data_p,$this->model_global->getCreateProperties($this->admin));
			$datax = $this->model_global->updateQuery($data,'data_presensi',['id_p_karyawan'=>$id]);
		}
		echo json_encode($datax);
	}
	public function edit_presensi_one()
	{
		if (!$this->input->is_ajax_request())
		   redirect('not_found');
		$id = $this->input->post('id');
		$id_karyawan = $this->input->post('id_karyawan');
		$tanggal = $this->formatter->getDateFormatDb($this->input->post('tanggal'));
		$jam_mulai = $this->input->post('jam_mulai');
		$jam_selesai = $this->input->post('jam_selesai');
		if ($id != "") {
			if ($id_karyawan != "") {
				$libur = $this->model_master->cekHariLiburDate($tanggal)['kode_hari_libur'];
				$kode_libur=(!empty($libur)?$libur:null);
				$izin = $this->model_karyawan->cekIzinCutiPresensi($id_karyawan,$tanggal)['kode_izin_cuti'];
				$kode_izin=(!empty($izin)?$izin:null);
				$lembur = $this->model_karyawan->cekDataLemburPresensi($id_karyawan,$tanggal)['no_lembur'];
				$kode_lembur=(!empty($lembur)?$lembur:null);
				$tgl_perdin =$tanggal.' 00:00:00';
				$perdin = $this->model_karyawan->cekDataPerDinPresensi($id_karyawan,$tgl_perdin)['no_sk'];
				$kode_perdin=(!empty($perdin)?$perdin:null);
				$kode_shift=$this->input->post('kode_master_shift');
				$month = (int)date('m',strtotime($tanggal));
				$year = date('Y',strtotime($tanggal));
				$day = (int)date('d',strtotime($tanggal));
				$day=($day<10)?str_replace('0','',$day):$day;
				$month=($month<10)?str_replace('0','',$month):$month;
				if(!empty($this->model_karyawan->cekJadwalKerjaIdDate($id_karyawan,$tanggal)['kode_shift'])){
					$cekpresensi = $this->model_karyawan->cekPresensiId($id_karyawan,$tanggal);
					if($cekpresensi != 'false'){
						// $presensiIDTanggal = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getListPresensiId($id))['tanggal'];
						// $presensiIDShift = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getListPresensiId($id))['kode_shift'];
						// $day_p = (int)date('d',strtotime($presensiIDTanggal));
						// $day_p=($day_p<10)?str_replace('0','',$day_p):$day_p;
						// if($tanggal != $presensiIDTanggal){
						// 	$data_j['tgl_'.$day] = $kode_shift;
						// 	$data_j['tgl_'.$day_p] = $presensiIDShift;
						// }else{
						// 	$data_j['tgl_'.$day] = $kode_shift;
						// }
						$where=['id_karyawan'=>$id_karyawan,'bulan'=>$month,'tahun'=>$year];
						$data_j['tgl_'.$day] = $kode_shift;
						$data_j=array_merge($data_j,$this->model_global->getUpdateProperties($this->admin));
						$this->model_global->updateQueryNoMsg($data_j,'data_jadwal_kerja',$where);
						$data=[
							'tanggal' => $tanggal,
							'jam_mulai'=>$this->formatter->timeFormatDb($jam_mulai),
							'jam_selesai'=>$this->formatter->timeFormatDb($jam_selesai),
						];
						$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
						$datax = $this->model_global->updateQuery($data,'data_presensi',['id_p_karyawan'=>$id]);
					}else{
						$data=[
							'tanggal' => $tanggal,
							'jam_mulai'=>$this->formatter->timeFormatDb($jam_mulai),
							'jam_selesai'=>$this->formatter->timeFormatDb($jam_selesai),
						];
						$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
						$datax = $this->model_global->updateQuery($data,'data_presensi',['id_p_karyawan'=>$id]);
					}
				}else{
					$cek=$this->model_karyawan->cekJadwalKerjaIdK($id_karyawan,$month,$year);
					if(!empty($cek)){
						$where=['id_karyawan'=>$id_karyawan,'bulan'=>$month,'tahun'=>$year];
						$data_j['tgl_'.$day] = $kode_shift;
						$data_j=array_merge($data_j,$this->model_global->getUpdateProperties($this->admin));
						$this->model_global->updateQueryNoMsg($data_j,'data_jadwal_kerja',$where);
					}else{
						$data_j=[
							'id_karyawan'=>$id_karyawan,
							'bulan'=>$month,
							'tahun'=>$year,
						];
						$data_j['tgl_'.$day] = $kode_shift;
						$datay=array_merge($data_j,$this->model_global->getCreateProperties($this->admin));
						$datax = $this->model_global->insertQueryNoMsg($datay,'data_jadwal_kerja');
					}
					$data_p=[
						'id_karyawan'=>$id_karyawan,
						'tanggal' => $tanggal,
						'jam_mulai'=>$this->formatter->timeFormatDb($jam_mulai),
						'jam_selesai'=>$this->formatter->timeFormatDb($jam_selesai),
						'kode_shift'=>$kode_shift,
						'kode_hari_libur'=>$kode_libur,
						'kode_ijin'=>$kode_izin,
						'no_spl'=>$kode_lembur,
						'no_perjalanan_dinas'=>$kode_perdin,
					];
					$data=array_merge($data_p,$this->model_global->getCreateProperties($this->admin));
					$datax = $this->model_global->updateQuery($data,'data_presensi',['id_p_karyawan'=>$id]);
				}
			}else{
				$datax = $this->messages->notValidParam();
			}
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function cekJadwalKerja()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id_kar = $this->input->post('id_kar');
		$tanggal = $this->input->post('tanggal');
		$cek=$this->model_karyawan->cekJadwalKerjaIdDate($id_kar,$this->formatter->getDateFormatDb($tanggal),'avb');
		if($cek == 'false'){
			$msg='Karyawan ini belum memiliki jadwal kerja pada tanggal tersebut, Silahkan Input Shift untuk membuat jadwal..';
		}else{
			$msg=null;
		}
		$data=['msg'=>$msg,'cek'=>$cek,];
		echo json_encode($data);
	}
	public function data_presensi()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');

		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				parse_str($this->input->post('form'), $post_form);
				$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($post_form['tanggal'],'start')));
				$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($post_form['tanggal'],'end')));
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$filter=(!empty($access['l_ac']['ftr']))?$access['kode_bagian']:null;
				$data=$this->model_karyawan->getListPresensi('group',$post_form,$filter);
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$cn_data = $this->model_karyawan->getListPresensiIdKar($post_form['usage'],$d->id_karyawan,$post_form);
					$var=[
						'id'=>$d->id_karyawan,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_p_karyawan,
						'<a href="'.base_url('pages/view_presensi/'.$this->codegenerator->encryptChar($d->nik)).'" target="blank">'.$d->nik.'</a>',
						$d->nama_karyawan,
						(!empty($d->nama_jabatan)) ? $d->nama_jabatan : $this->otherfunctions->getMark(),
						(!empty($d->nama_bagian)) ? $d->nama_bagian : $this->otherfunctions->getMark(),
						(!empty($d->nama_loker)) ? $d->nama_loker : $this->otherfunctions->getMark(),
						count($cn_data).' Data Presensi',
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_karyawan');
				$data=$this->model_karyawan->getEmployeeId($id);
				$datax = [
					'id_karyawan'=>$data['id_karyawan'],
					'nik'=>$data['nik'],
					'nama'=>$data['nama'],
					'nama_jabatan'=>(!empty($data['nama_jabatan'])) ? $data['nama_jabatan'] : $this->otherfunctions->getMark(),
					'bagian'=>(!empty($data['bagian'])) ? $data['bagian'] : $this->otherfunctions->getMark(),
					'nama_loker'=>(!empty($data['nama_loker'])) ? $data['nama_loker'] : $this->otherfunctions->getMark(),
					'status'=>(!empty($data['status'])) ? '<b class="text-success">Aktif</b>' : $this->otherfunctions->getMark(),
					'create_date'=>(!empty($data['create_date'])) ? $this->formatter->getDateTimeMonthFormatUser($data['create_date']) : $this->otherfunctions->getMark(),
					'update_date'=>(!empty($data['update_date'])) ? $this->formatter->getDateTimeMonthFormatUser($data['update_date']) : $this->otherfunctions->getMark(),
					'create_by'=>(!empty($data['nama_buat'])) ? $data['nama_buat'] : $this->otherfunctions->getMark(),
					'update_by'=>(!empty($data['nama_update'])) ? $data['nama_update'] : $this->otherfunctions->getMark(),
				];
				echo json_encode($datax);
			}elseif ($usage == 'tabel_view') {
				$id = $this->input->post('id_karyawan');
				parse_str($this->input->post('form'), $post_form);
				$data=$this->model_karyawan->getListPresensiIdKar($post_form['usage'],$id,$post_form);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$table = '';
				foreach ($data as $d) {
					$day = $this->formatter->getNameOfDay($d->tanggal);
					$month = $this->formatter->getDateMonthFormatUser($d->tanggal);
					if(isset($d->nama_shift)){
						$tgl_mulai = $d->tanggal.' '.$d->jam_mulai;
						if($d->jam_selesai > $d->jam_mulai){
							$t_s = $d->tanggal;
							$tgl_selesai = $d->tanggal.' '.$d->jam_selesai;
						}else{
							$t_s = date('Y-m-d', strtotime('+1 days', strtotime($d->tanggal)));
							$tgl_selesai = $t_s.' '.$d->jam_selesai;
						}
						$nama_shift = (empty($d->nama_shift)) ? '' : $d->nama_shift;
						$jam_mulai = (empty($d->jam_mulai_shift)) ? '' : $d->jam_mulai_shift;
						$jam_selesai = (empty($d->jam_selesai_shift)) ? '' : $d->jam_selesai_shift;
						$jdw_jam = $nama_shift.' ['.$jam_mulai.' - '.$jam_selesai.']';
						$j_jdwl_k=$this->otherfunctions->getDivTime($jam_mulai,$jam_selesai);
						$j_jam_k=$this->otherfunctions->getDivTime($tgl_mulai,$tgl_selesai);
						if(!empty($j_jdwl_k) && !empty($j_jam_k)){
							if($j_jdwl_k < $j_jam_k){
								$ed_over = $this->otherfunctions->getDivTime($j_jdwl_k,$j_jam_k);
								$over =$this->otherfunctions->getStringInterval($ed_over);
							}else{
								$over=$this->otherfunctions->getLabelMark(null,'success','Tidak Over');
							}
						}
						$terlambat=$this->otherfunctions->getMark();
						if(!empty($d->jam_mulai) && !empty($jam_mulai)){
							$jam_mulai_shift=$jam_mulai;
							if ($d->setting_dispensasi_jam_masuk == 'yes'){
								if ($d->dispensasi){
									$jam_mulai_shift=$d->dispensasi;
								}
							}
							if($d->jam_mulai > $jam_mulai_shift){
								if (($day == "Minggu" || $d->kode_hari_libur) && $d->setting_terlambat == 'no'){
									$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
								}else{	
									$e_terlambat = $this->otherfunctions->getDivTime($jam_mulai_shift,$d->jam_mulai,'time','H:i:s');
									$terlambat =$this->otherfunctions->getStringInterval($e_terlambat);
								}
							}else{
								$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
							}
						}
						$plgcepat=$this->otherfunctions->getMark();
						if(!empty($d->jam_selesai) && !empty($jam_selesai)){
							if(strtotime($t_s.' '.$d->jam_selesai) < strtotime($d->tanggal.' '.$jam_selesai)){
								$e_plgcepat = $this->otherfunctions->getDivTime($d->tanggal.' '.$jam_selesai,$t_s.' '.$d->jam_selesai);
								$pc_st=$this->otherfunctions->getDataExplode($e_plgcepat,':','start');
								$pc_end=$this->otherfunctions->getDataExplode($e_plgcepat,':','end');
								$plgcepat=(($pc_st!='00')?$pc_st.' Jam, ':null).(($pc_end!='00')?$pc_end.' Menit':null);
							}else{
								$plgcepat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Pulang Cepat</label>';
							}
						}
						$plg_tlcp = '<table><tr><td>Terlambat </td><td>: '.$terlambat.'</td></tr><tr><td>Pulang Cepat </td><td>: '.$plgcepat.'</td></tr></table>';
					}else{
						$lavelx = $this->otherfunctions->getLabelMark(null,'danger' ,'Tidak Ada Data');
						$jdw_jam = $lavelx;
						$over = $lavelx;
						$plg_tlcp = $lavelx;
					}
					$namaizin=(!empty($d->kode_ijin)?$d->nama_ijin.' ('.$d->jenis_izin.')':null);
					$jml_jam = $this->otherfunctions->getRangeTime($d->jam_mulai,$d->jam_selesai);
					$libur = $this->model_master->cekHariLibur($d->tanggal,'date');
					$bglibur = (empty($libur)) ? '' : 'bg-red';
					if(!empty($d->val_jumlah_lembur)){
						$lembur = (int)explode(":",$d->val_jumlah_lembur)[0];
					}else{
						$lembur=null;
					}
					if(!empty($libur)){
						$lembur = $this->otherfunctions->getIntervalJam($d->jam_mulai,$d->jam_selesai);
					}
					$table .= '<tr class="'.$bglibur.'">
						<td class="nowrap">'.$no.'</td>
						<td class="nowrap">'.$day.', '.$month.'</td>
						<td class="nowrap">'.$this->otherfunctions->getLabelMark($this->formatter->getTimeFormatUser($d->jam_mulai,'WIB'),'danger','Tidak Scan').'</td>
						<td class="nowrap">'.$this->otherfunctions->getLabelMark($this->formatter->getTimeFormatUser($d->jam_selesai,'WIB'),'danger','Tidak Scan').'</td>
						<td class="nowrap">'.$this->otherfunctions->getLabelMark($this->otherfunctions->getStringInterval($j_jam_k),'danger','Tidak Scan').'</td>
						<td class="nowrap">'.$this->otherfunctions->getLabelMark($jdw_jam,'danger','Jadwal Kosong','').'</td>
						<td class="nowrap">'.$this->otherfunctions->getLabelMark($namaizin,'success','Tidak Ijin').'</td>
						<td class="nowrap">'.$this->otherfunctions->getLabelMark($lembur,'success','Tidak Lembur','Jam').'</td>
						<td class="nowrap">'.$over.'</td>
						<td class="nowrap">'.$plg_tlcp.'</td>
						<td class="nowrap">'.$this->otherfunctions->getLabelMark($libur,'danger','Tidak Libur').'</td>
						</tr>';
					$no++;
				}
				$datax = ['table'=>$table];
				echo json_encode($datax);
			}elseif($usage == 'employee'){
				$data = $this->model_karyawan->getEmployeeForSelect2FilterJadwal(false);
        		echo json_encode($data);
			}elseif($usage == 'nik_employee'){
				$data = $this->model_karyawan->getEmployeeForSelect2Filter(true,true);
        		echo json_encode($data);
			}elseif ($usage == 'view_select') {
				$kode = $this->input->post('kode_lokasi');
				if($kode == ''){
					$data = $this->model_karyawan->getEmployeeAllActive();
				}else{
					$data = $this->model_karyawan->getEmpKodeLokasi($kode);
				}
				$datax='';
				foreach ($data as $d) {
					$datax.='<option value="'.$d->id_karyawan.'">'.$d->nama.' ('.$d->nama_jabatan.')</option>';
				}
				echo json_encode($datax);
			}elseif ($usage == 'getAlpa3times') {
				$ada='<li class="header">Data Karyawan Alpa 3 Hari Berturut-turut</li><li class="divider"></li>';
				// $tanggal_selesai = '2022-10-18';
				$tanggal_selesai =date('Y-m-d', strtotime($this->date));
				$tanggal_mulai =date('Y-m-d', strtotime('-3 days', strtotime($tanggal_selesai)));
				$kary = [];
				$dtx = $this->model_karyawan->getListAbsensiHarianRange($tanggal_mulai,$tanggal_selesai,null,null,true);
				if(!empty($dtx)){
					foreach ($dtx as $dx) {
						if($dx->kode_jabatan != 'JBT201901160029' && $dx->kode_jabatan != 'JBT201901160064' && $dx->kode_jabatan != 'JBT201901160067' && $dx->kode_jabatan != 'JBT201901160133' && $dx->kode_jabatan != 'JBT201909040009' && $dx->kode_jabatan != 'JBT202104010001' && $dx->kode_jabatan != 'JBT201901160065'){
							$libur =  $this->otherfunctions->checkHariLiburActive($dx->tanggal);
							if(!isset($libur) && empty($dx->jam_mulai) && empty($dx->jam_selesai) && empty($dx->kode_hari_libur) && empty($dx->kode_ijin) && empty($dx->no_spl)){
								$kary[$dx->nama_karyawan][] = [
									'tanggal'=>$dx->tanggal,
									'jabatan'=>$dx->nama_jabatan
								];
							}
						}
					}
					$jumlahVal = 0;
					foreach ($kary as $key => $value) {
						if(count($value) > 2){
							$tgl = [];
							$jabatan = '';
							foreach ($value as $k => $v) {
								$jabatan = $v['jabatan'];
								$ntg = explode('-', $v['tanggal']);
								$tgl[] = $ntg[2].'/'.$ntg[1];
							}
							asort($tgl);
							$newTgl = implode(', ', $tgl);
							$ada.='<li><a href="#"><i class="fas fa-user-times"></i>  '.$key.' | <small style="color:red; font-size:8pt;">'.($jabatan).'</small> <small class="text-muted pull-right" title="Tanggal Alpa">'.$newTgl.'</small></a></li>';
							$jumlahVal += 1;
						}
					}
				}
				if ($jumlahVal == 0 || $jumlahVal == null) {
					$ada='<li class="text-center"><small class="text-muted"><i class="icon-close"></i> Tidak Ada Data</small></li><li class="divider"> </li>';
				}
				$datax=[	
					'count'=>$jumlahVal,
					'value'=>$ada,
				];
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function view_presensi()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {				
				parse_str($this->input->post('form'), $post_form);
				$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($post_form['tanggal'],'start')));
				$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($post_form['tanggal'],'end')));
				if($post_form['usage'] == 'all'){
					$tanggal_selesai = date('Y-m-d');
					$tanggal_mulai = date('Y-m-d', strtotime('-2 month', strtotime($tanggal_selesai)));
				}
				$id = $this->input->post('id_karyawan');
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				while (strtotime($tanggal_selesai)>=strtotime($tanggal_mulai)){
					$day = $this->formatter->getNameOfDay($tanggal_selesai);
					$month = $this->formatter->getDateMonthFormatUser($tanggal_selesai);
					$day_month = $day.', '.$month;
					$libur = $this->model_master->cekHariLibur($tanggal_selesai,'date');
					if(!empty($libur)){
						$day_month = '<b class="text-danger" data-toggle="tooltip" data-placement="right" title="'.$libur.'">'.$day.', '.$month.'</b>';
					}
					$cek_data = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getListPresensiId(null, ['pre.id_karyawan'=>$id,'pre.tanggal'=>$tanggal_selesai]));
					if(empty($cek_data)){
						$x_id = '';
						$x_jam_masuk = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_jam_keluar = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_jml_jam_kerja = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_jadwal_jam_kerja = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_ijin = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_lembur = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_over = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_terlambat = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_hari_libur = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
						$x_aksi = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
					}else{
						$data = $this->model_karyawan->getListPresensiId(null, ['pre.id_karyawan'=>$id,'pre.tanggal'=>$tanggal_selesai]);
						foreach ($data as $d) {
							$var=[
								'id'=>$d->id_p_karyawan,
								'create'=>$d->create_date,
								'update'=>$d->update_date,
								'access'=>$access,
								'status'=>$d->status,
							];
							$properties=$this->otherfunctions->getPropertiesTable($var);
							$nama_shift = (empty($d->nama_shift)) ? '' : $d->nama_shift;
							$jam_mulai = (empty($d->jam_mulai_shift)) ? '' : $d->jam_mulai_shift;
							$jam_selesai = (empty($d->jam_selesai_shift)) ? '' : $d->jam_selesai_shift;
							$jadwal_jam_kerja = $nama_shift.' ['.$jam_mulai.' - '.$jam_selesai.']';
							$tgl_mulai = $d->tanggal.' '.$d->jam_mulai;
							if($d->jam_selesai > $d->jam_mulai){
								$t_s = $d->tanggal;
								$tgl_selesai = $d->tanggal.' '.$d->jam_selesai;
							}else{
								$t_s = date('Y-m-d', strtotime('+1 days', strtotime($d->tanggal)));
								$tgl_selesai = $t_s.' '.$d->jam_selesai;
							}
							if(empty($d->nama_shift)){
								$jadwal_jam_kerja = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Jadwal Tidak DItemukan</label></div>';
								$over = $this->otherfunctions->getLabelMark(null,'danger','Tidak Over','Jam');
								$plg_tlcp = $this->otherfunctions->getLabelMark(null,'danger','Jam Normal','Jam');
							}else{
								$j_jdwl_k=$this->otherfunctions->getDivTime($jam_mulai,$jam_selesai);
								$j_jam_k=$this->otherfunctions->getDivTime($tgl_mulai,$tgl_selesai);
								$over=$this->otherfunctions->getLabelMark(null,'success','Tidak Over');
								if(!empty($j_jdwl_k) && !empty($j_jam_k)){
									if($j_jdwl_k < $j_jam_k){
										$ed_over = $this->otherfunctions->getDivTime($j_jdwl_k,$j_jam_k);
										$over =$this->otherfunctions->getStringInterval($ed_over);
									}else{
										$over=$this->otherfunctions->getLabelMark(null,'success','Tidak Over');
									}
								}
								$terlambat=$this->otherfunctions->getMark();
								if(!empty($d->jam_mulai) && !empty($jam_mulai)){
									$jam_mulai_shift=$jam_mulai;
									if ($d->setting_dispensasi_jam_masuk == 'yes'){
										if ($d->dispensasi){
											$jam_mulai_shift=$d->dispensasi;
										}
									}
									if($d->jam_mulai > $jam_mulai_shift){
										if (($day == "Minggu" || $d->kode_hari_libur) && $d->setting_terlambat == 'no'){
											$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
										}else{
											$e_terlambat = $this->otherfunctions->getDivTime($jam_mulai,$d->jam_mulai_shift);
											$terlambat =$this->otherfunctions->getStringInterval($e_terlambat);
										}
									}else{
										$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
									}
								}
								$plgcepat=$this->otherfunctions->getMark();
								if(!empty($d->jam_selesai) && !empty($jam_selesai)){
									if(strtotime($t_s.' '.$d->jam_selesai) < strtotime($d->tanggal.' '.$jam_selesai)){
										$e_plgcepat = $this->otherfunctions->getDivTime($d->tanggal.' '.$jam_selesai,$t_s.' '.$d->jam_selesai);
										$pc_st=$this->otherfunctions->getDataExplode($e_plgcepat,':','start');
										$pc_end=$this->otherfunctions->getDataExplode($e_plgcepat,':','end');
										$plgcepat=(($pc_st!='00')?$pc_st.' Jam, ':null).(($pc_end!='00')?$pc_end.' Menit':null);
									}else{
										$plgcepat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Pulang Cepat</label>';
									}
								}
								$plg_tlcp = '<table><tr><td>Terlambat </td><td>: '.$terlambat.'</td></tr><tr><td>Pulang Cepat </td><td>: '.$plgcepat.'</td></tr></table>';
							}
							$namaizin=(!empty($d->kode_ijin)?$d->nama_izin.' ('.$d->jenis_izin.')':null);
							if(!empty($d->val_jumlah_lembur)){
								$lembur = (int)explode(":",$d->val_jumlah_lembur)[0];
							}else{
								$lembur = null;
							}
							if(!empty($libur)){
								$lembur = $this->otherfunctions->getIntervalJam($d->jam_mulai,$d->jam_selesai);
							}
							$x_id = $id;
							$x_jam_masuk = '<center>'.$this->formatter->getTimeFormatUser($d->jam_mulai,'WIB').'</center>';
							$x_jam_keluar = '<center>'.$this->formatter->getTimeFormatUser($d->jam_selesai,'WIB').'</center>';
							$x_jml_jam_kerja = '<center>'.$this->otherfunctions->getStringInterval($this->otherfunctions->getDivTime($tgl_mulai,$tgl_selesai)).'</center>';
							$x_jadwal_jam_kerja = $jadwal_jam_kerja;
							$x_ijin = '<center>'.$this->otherfunctions->getLabelMark($namaizin,'success','Tidak Izin').'</center>';
							$x_lembur = '<center>'.$this->otherfunctions->getLabelMark($lembur,'success','Tidak Lembur','Jam').'</center>';
							$x_over = '<center>'.$over.'</center>';
							$x_terlambat = '<center>'.$plg_tlcp.'</center>';
							$x_hari_libur = '<center>'.$this->otherfunctions->getLabelMark($d->nama_libur,'success','Jam Kerja').'</center>';
							$x_aksi = '<center style="width: 100px;">'.$properties['aksi'].'</center>';
						}
					}
					$datax['data'][]=[
						$x_id,
						$day_month,
						$x_jam_masuk,
						$x_jam_keluar,
						$x_jml_jam_kerja,
						$x_jadwal_jam_kerja,
						$x_ijin,
						$x_lembur,
						$x_over,
						$x_terlambat,
						$x_hari_libur,
						$x_aksi
					];

					$tanggal_selesai = mktime(0,0,0,date("m",strtotime($tanggal_selesai)),date("d",strtotime($tanggal_selesai))-1,date("Y",strtotime($tanggal_selesai)));
					$tanggal_selesai=date("Y-m-d", $tanggal_selesai);
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_presensi');
				$data=$this->model_karyawan->getListPresensiId($id);
				foreach ($data as $d) {
					$day = $this->formatter->getNameOfDay($d->tanggal);
					$month = $this->formatter->getDateMonthFormatUser($d->tanggal);
					$status = ($d->status=='1') ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Tidak Aktif</b>';
					if(empty($d->nama_shift)){
						$jdw_jam = '<div><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Jadwal Tidak DItemukan</label></div>';
						$over = '<div><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Over</label></div>';
						$plg_tlcp = '<div><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
					}else{
						$tgl_mulai = $d->tanggal.' '.$d->jam_mulai;
						if($d->jam_selesai > $d->jam_mulai){
							$t_s = $d->tanggal;
							$tgl_selesai = $d->tanggal.' '.$d->jam_selesai;
						}else{
							$t_s = date('Y-m-d', strtotime('+1 days', strtotime($d->tanggal)));
							$tgl_selesai = $t_s.' '.$d->jam_selesai;
						}
						$nama_shift = (empty($d->nama_shift)) ? '' : $d->nama_shift;
						$jam_mulai = (empty($d->jam_mulai_shift)) ? '' : $d->jam_mulai_shift;
						$jam_selesai = (empty($d->jam_selesai_shift)) ? '' : $d->jam_selesai_shift;
						$jdw_jam = $nama_shift.' ['.$jam_mulai.' - '.$jam_selesai.']';
						$j_jdwl_k=$this->otherfunctions->getDivTime($jam_mulai,$jam_selesai);
						$j_jam_k=$this->otherfunctions->getDivTime($tgl_mulai,$tgl_selesai);
						if(!empty($j_jdwl_k) && !empty($j_jam_k)){
							if($j_jdwl_k < $j_jam_k){
								$ed_over = $this->otherfunctions->getDivTime($j_jdwl_k,$j_jam_k);
								$over =$this->otherfunctions->getStringInterval($ed_over);
							}else{
								$over=$this->otherfunctions->getLabelMark(null,'success','Tidak Over');
							}
						}
						$terlambat=$this->otherfunctions->getMark();
						if(!empty($d->jam_mulai) && !empty($jam_mulai)){
							$jam_mulai_shift=$jam_mulai;
							if ($d->setting_dispensasi_jam_masuk == 'yes'){
								if ($d->dispensasi){
									$jam_mulai_shift=$d->dispensasi;
								}
							}
							if($d->jam_mulai > $jam_mulai_shift){
								if (($day == "Minggu" || $d->kode_hari_libur) && $d->setting_terlambat == 'no'){
									$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
								}else{
									$e_terlambat = $this->otherfunctions->getDivTime($jam_mulai_shift,$d->jam_mulai,'time','H:i:s');
									$terlambat =$this->otherfunctions->getStringInterval($e_terlambat);
								}
							}else{
								$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
							}
						}
						$plgcepat=$this->otherfunctions->getMark();
						if(!empty($d->jam_selesai) && !empty($jam_selesai)){
							if(strtotime($t_s.' '.$d->jam_selesai) < strtotime($d->tanggal.' '.$jam_selesai)){
								$e_plgcepat = $this->otherfunctions->getDivTime($d->tanggal.' '.$jam_selesai,$t_s.' '.$d->jam_selesai);
								$pc_st=$this->otherfunctions->getDataExplode($e_plgcepat,':','start');
								$pc_end=$this->otherfunctions->getDataExplode($e_plgcepat,':','end');
								$plgcepat=(($pc_st!='00')?$pc_st.' Jam, ':null).(($pc_end!='00')?$pc_end.' Menit':null);
							}else{
								$plgcepat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Pulang Cepat</label>';
							}
						}
						$plg_tlcp = '<table><tr><td>Terlambat </td><td>: '.$terlambat.'</td></tr><tr><td>Pulang Cepat </td><td>: '.$plgcepat.'</td></tr></table>';
					}
					$jml_jam = $this->otherfunctions->getStringInterval($this->otherfunctions->getDivTime($tgl_mulai,$tgl_selesai));
					$libur = $this->otherfunctions->checkHariLibur($d->tanggal);
					// $lembur = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getLembur(null, ['a.id_karyawan'=>$d->id_karyawan,'a.tgl_mulai '=>$d->tanggal]));
					$lembur = null;
					if(!empty($d->val_jumlah_lembur)){
						$lembur = (int)explode(":",$d->val_jumlah_lembur)[0];
					}
					if(!empty($libur)){
						$lembur = $jml_jam;
					}
					$setJamMulai = (!empty($d->jam_mulai)) ? $d->jam_mulai : '00:00:00';
					$setJamSelesai = (!empty($d->jam_selesai)) ? $d->jam_selesai : '00:00:00';
					$getIzin=$this->model_karyawan->getJenisIzinKodeIzin($d->kode_ijin)['jenis'];
					$n_izin=$this->model_karyawan->getIzinCutiPresensi($getIzin)['nama'];
					$jenis_izin=' ('.$this->otherfunctions->getIzinCuti($this->model_karyawan->getIzinCutiPresensi($getIzin)['jenis']).')';
					$namaizin=(!empty($d->kode_ijin)?$n_izin.$jenis_izin:null);
					$datax=[
						'id'=>$d->id_p_karyawan,
						'id_karyawan' => $d->id_karyawan,
						'tgl_presensi'=>$day.', '.$month,
						'tgl_masuk'=>$this->formatter->getTimeFormatUser($d->jam_mulai,'WIB'),
						'tgl_selesai'=>$this->formatter->getTimeFormatUser($d->jam_selesai,'WIB'),
						'gettgl_mulai'=>$this->formatter->getDateTimeFormatUser($d->tanggal.' '.$d->jam_mulai),
						'gettgl_selesai'=>$this->formatter->getDateTimeFormatUser($d->tanggal.' '.$d->jam_selesai),
						'jam_kerja'=>$this->otherfunctions->getLabelMark($jml_jam,'danger','Tidak Scan','Jam','left'),
						'jadwal'=>$jdw_jam,
						'over'=>$over,
						'plg_trlmbt'=>$plg_tlcp,
						'ijin_cuti'=>$this->otherfunctions->getLabelMark($namaizin,'success','Tidak Izin'),
						'lembur'=>$this->otherfunctions->getLabelMark($lembur,'success','Tidak Lembur','Jam','left'),
						'libur'=>$this->otherfunctions->getLabelMark($libur,'success','Tidak Libur','','left'),
						'status'=>$status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'update_by'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'tanggal'=>$this->formatter->getDateFormatUser($d->tanggal),
						'jam_mulai'=>$setJamMulai,
						'jam_selesai'=>$setJamSelesai,
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function data_presensi_harian()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				parse_str($this->input->post('form'), $post_form);
				if(empty($post_form['tanggal'])){
					$date_now = $this->otherfunctions->getDataExplode($this->date,' ','start');
					$tgl = $this->formatter->getDateFormatUser($date_now);
					$post_form['tanggal'] = $tgl.' - '.$tgl;
				}
				$data=$this->model_karyawan->getListPresensiHarian($post_form);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$day=$this->formatter->getNameOfDay($d->tanggal);
					if(!empty($d->no_spl)){
						if(!empty($d->val_jumlah_lembur) && $d->val_jumlah_lembur != 'NULL'){
							$lama_lembur = $this->formatter->convertDecimaltoJam(13);
							$lama_lembur_real = $d->val_jumlah_lembur;//$this->formatter->convertDecimaltoJam($d->val_jumlah_lembur);
							if($lama_lembur_real > $lama_lembur){
								$cekLembur = $this->model_karyawan->getLembur(null,['no_lembur'=>$d->no_spl],'row');
								$mulai = $cekLembur['tgl_mulai'];
								$selesai = $cekLembur['tgl_selesai'];
								$count = 0;
								while ($mulai <= $selesai)
								{
									$count+=1;
									$mulai = date('Y-m-d', strtotime($mulai . ' +1 day'));
								}
								$v_lembur =$this->otherfunctions->getStringInterval($lama_lembur_real).'<br>
										Mulai : '.$this->formatter->getDateFormatUser($cekLembur['tgl_mulai']).' - '.$this->formatter->getDateFormatUser($cekLembur['tgl_selesai']).'';
							}else{
								// $v_lembur =$this->otherfunctions->getStringInterval($d->val_jumlah_lembur);
								$awal = $this->formatter->convertJamtoDecimal($d->val_jumlah_lembur);
								$akhir= $this->formatter->convertJamtoDecimal($d->val_potong_jam);
								$lembur_akhir = $awal-$akhir;
								$v_lembur =$this->formatter->convertDecimaltoJam($lembur_akhir);
							}
						}else{
							$v_lembur=null;
						}
					}else{
						$v_lembur=null;
					}
					// if(!empty($d->jenis_izin)){
					// 	$nama_izin_cuti=$d->nama_jenis_izin;
					// }else{
					// 	$nama_izin_cuti=null;
					// }
					if(!empty($d->nama_jenis_izin)){
						$nama_izin_cuti=$d->nama_jenis_izin;
					}else{
						$nama_izin_cuti=$d->kode_ijin;
					}
					$jam_mulai = (empty($d->jam_mulai_shift)) ? '' : $d->jam_mulai_shift;
					$jam_selesai = (empty($d->jam_selesai_shift)) ? '' : $d->jam_selesai_shift;
					$jadwal_jam_kerja = '<span class="scc" title="Jam Masuk">'.$this->formatter->getTimeFormatUser($jam_mulai).'</span> - <span class="err" title="Jam Keluar">'.$this->formatter->getTimeFormatUser($jam_selesai).'</span>';
					$jumlah_jam_kerja=$this->otherfunctions->getDivTime($d->jam_mulai,$d->jam_selesai);
					if(empty($d->nama_shift)){
						$jadwal_jam_kerja = $this->otherfunctions->getLabelMark(null,'danger','Tidak Ada Jadwal');
						$over = $this->otherfunctions->getLabelMark(null,'danger','Tidak Over','Jam');
						$plg_tlcp = $this->otherfunctions->getLabelMark(null,'danger','Jam Normal','Jam');
						$nama_shift = $this->otherfunctions->getLabelMark(null,'danger','Tidak Ada Shift','Jam');
					}else{
						$nama_shift = $d->nama_shift;
						$tgl_mulai = $d->tanggal.' '.$d->jam_mulai;
						if($d->jam_selesai > $d->jam_mulai){
							$t_s = $d->tanggal;
							$tgl_selesai = $d->tanggal.' '.$d->jam_selesai;
						}else{
							$t_s = date('Y-m-d', strtotime('+1 days', strtotime($d->tanggal)));
							$tgl_selesai = $t_s.' '.$d->jam_selesai;
						}
						$j_jdwl_k=$this->otherfunctions->getDivTime($jam_mulai,$jam_selesai);
						$j_jam_k=$this->otherfunctions->getDivTime($tgl_mulai,$tgl_selesai);
						$over=$this->otherfunctions->getLabelMark(null,'success','Tidak Over');
						if(!empty($j_jdwl_k) && !empty($j_jam_k)){
							if($j_jdwl_k < $j_jam_k){
								$ed_over = $this->otherfunctions->getDivTime($j_jdwl_k,$j_jam_k);
								$over =$this->otherfunctions->getStringInterval($ed_over);
							}else{
								$over=$this->otherfunctions->getLabelMark(null,'success','Tidak Over');
							}
						}
						$terlambat=$this->otherfunctions->getMark();
						if(!empty($d->jam_mulai) && !empty($jam_mulai)){
							$jam_mulai_shift=$jam_mulai;
							if ($d->setting_dispensasi_jam_masuk == 'yes'){
								if ($d->dispensasi){
									$jam_mulai_shift=$d->dispensasi;
								}
							}
							if($d->jam_mulai > $jam_mulai_shift){
								if (($day == "Minggu" || $d->kode_hari_libur) && $d->setting_terlambat == 'no'){
									$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
								}else{
									$e_terlambat = $this->otherfunctions->getDivTime($jam_mulai_shift,$d->jam_mulai,'time','H:i:s');
									$terlambat =$this->otherfunctions->getStringInterval($e_terlambat);
								}
							}else{
								$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
							}
						}
						$plgcepat=$this->otherfunctions->getMark();
						if(!empty($d->jam_selesai) && !empty($jam_selesai)){
							if(strtotime($t_s.' '.$d->jam_selesai) < strtotime($d->tanggal.' '.$jam_selesai)){
								$e_plgcepat = $this->otherfunctions->getDivTime($d->tanggal.' '.$jam_selesai,$t_s.' '.$d->jam_selesai);
								$pc_st=$this->otherfunctions->getDataExplode($e_plgcepat,':','start');
								$pc_end=$this->otherfunctions->getDataExplode($e_plgcepat,':','end');
								$plgcepat=(($pc_st!='00')?$pc_st.' Jam, ':null).(($pc_end!='00')?$pc_end.' Menit':null);
							}else{
								$plgcepat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Pulang Cepat</label>';
							}
						}
						$plg_tlcp = '<table><tr><td>Terlambat </td><td>: '.$terlambat.'</td></tr><tr><td>Pulang Cepat </td><td>: '.$plgcepat.'</td></tr></table>';
					}
					$x_jam_masuk = $this->formatter->getTimeFormatUser($d->jam_mulai,'WIB');
					$x_jam_keluar = $this->formatter->getTimeFormatUser($d->jam_selesai,'WIB');
					$x_jml_jam_kerja = (!empty($jumlah_jam_kerja) && $jumlah_jam_kerja != null && $jumlah_jam_kerja != '-') ? $this->otherfunctions->getStringInterval($jumlah_jam_kerja):$this->otherfunctions->getLabelMark(null,'danger','tidak ada data');
					// $x_jml_jam_kerja = $jumlah_jam_kerja;
					$x_jadwal_jam_kerja = $jadwal_jam_kerja;
					$x_ijin = $this->otherfunctions->getLabelMark($nama_izin_cuti,'success','Tidak Izin');
					$x_lembur = $this->otherfunctions->getLabelMark($v_lembur,'success','Tidak Lembur');
					$x_over = $over;
					$x_terlambat = $plg_tlcp;
					$x_hari_libur = $this->otherfunctions->getLabelMark($d->nama_libur,'success','Jam Kerja');
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_harian('.$d->id_p_karyawan.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_p_karyawan,
						$d->nama_karyawan,
						$d->nama_jabatan,
						$this->formatter->getDateMonthFormatUser($d->tanggal),
						$x_jam_masuk,
						$x_jam_keluar,
						$x_jml_jam_kerja,
						$nama_shift,
						$x_jadwal_jam_kerja,
						$x_ijin,
						$x_lembur,
						$x_over,
						$x_terlambat,
						$x_hari_libur,
						$aksi
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_presensi');
				$data=$this->model_karyawan->getListPresensiId($id);
				foreach ($data as $d) {
					$day = $this->formatter->getNameOfDay($d->tanggal);
					$month = $this->formatter->getDateMonthFormatUser($d->tanggal);
					$status = ($d->status=='1') ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Tidak Aktif</b>';
					if(!empty($d->no_spl)){
						if(!empty($d->val_jumlah_lembur)){
							$l_st=$this->otherfunctions->getDataExplode($d->val_jumlah_lembur,':','start');
							$l_end=$this->otherfunctions->getDataExplode($d->val_jumlah_lembur,':','end');
							$v_lembur=$l_st.' Jam, '.$l_end.' Menit';
						}else{
							$v_lembur=null;
						}
					}else{
						$v_lembur=null;
					}
					$jenis_izin_cuti=$d->jenis_izin;
					// if(!empty($d->jenis_izin)){
					// 	$nama_izin_cuti=$d->nama_jenis_izin;
					// }else{
					// 	$nama_izin_cuti=null;
					// }
					if(!empty($d->nama_jenis_izin)){
						$nama_izin_cuti=$d->nama_jenis_izin;
					}else{
						$nama_izin_cuti=$d->kode_ijin;
					}
					$jam_mulai = (empty($d->jam_mulai_shift)) ? '' : $d->jam_mulai_shift;
					$jam_selesai = (empty($d->jam_selesai_shift)) ? '' : $d->jam_selesai_shift;
					$nama_shift = (empty($d->nama_shift)) ? '' : $d->nama_shift;
					$jadwal_jam_kerja = '<span class="scc" title="Jam Masuk">'.$this->formatter->getTimeFormatUser($jam_mulai).'</span> - <span class="err" title="Jam Keluar">'.$this->formatter->getTimeFormatUser($jam_selesai).'</span>';
					$jumlah_jam_kerja=$this->otherfunctions->getDivTime($d->jam_mulai,$d->jam_selesai);
					if(empty($d->nama_shift)){
						$jadwal_jam_kerja = '<label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Jadwal Tidak DItemukan</label>';
						$over = $this->otherfunctions->getLabelMark(null,'danger','Tidak Over','Jam');
						$plg_tlcp = $this->otherfunctions->getLabelMark(null,'danger','Jam Normal','Jam');
					}else{
						$tgl_mulai = $d->tanggal.' '.$d->jam_mulai;
						if($d->jam_selesai > $d->jam_mulai){
							$t_s = $d->tanggal;
							$tgl_selesai = $d->tanggal.' '.$d->jam_selesai;
						}else{
							$t_s = date('Y-m-d', strtotime('+1 days', strtotime($d->tanggal)));
							$tgl_selesai = $t_s.' '.$d->jam_selesai;
						}
						$j_jdwl_k=$this->otherfunctions->getDivTime($jam_mulai,$jam_selesai);
						$j_jam_k=$this->otherfunctions->getDivTime($tgl_mulai,$tgl_selesai);
						$over=$this->otherfunctions->getLabelMark(null,'success','Tidak Over');
						if(!empty($j_jdwl_k) && !empty($j_jam_k)){
							if($j_jdwl_k < $j_jam_k){
								$ed_over = $this->otherfunctions->getDivTime($j_jdwl_k,$j_jam_k);
								$over =$this->otherfunctions->getStringInterval($ed_over);
							}else{
								$over=$this->otherfunctions->getLabelMark(null,'success','Tidak Over');
							}
						}
						$terlambat=$this->otherfunctions->getMark();
						if(!empty($d->jam_mulai) && !empty($jam_mulai)){
							$jam_mulai_shift=$jam_mulai;
							if ($d->setting_dispensasi_jam_masuk == 'yes'){
								if ($d->dispensasi){
									$jam_mulai_shift=$d->dispensasi;
								}
							}
							if($d->jam_mulai > $jam_mulai_shift){
								if (($day == "Minggu" || $d->kode_hari_libur) && $d->setting_terlambat == 'no'){
									$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
								}else{
									$e_terlambat = $this->otherfunctions->getDivTime($jam_mulai_shift,$d->jam_mulai,'time','H:i:s');
									$terlambat =$this->otherfunctions->getStringInterval($e_terlambat);
								}
							}else{
								$terlambat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Terlambat</label>';
							}
						}
						$plgcepat=$this->otherfunctions->getMark();
						if(!empty($d->jam_selesai) && !empty($jam_selesai)){
							if(strtotime($t_s.' '.$d->jam_selesai) < strtotime($d->tanggal.' '.$jam_selesai)){
								$e_plgcepat = $this->otherfunctions->getDivTime($d->tanggal.' '.$jam_selesai,$t_s.' '.$d->jam_selesai);
								$pc_st=$this->otherfunctions->getDataExplode($e_plgcepat,':','start');
								$pc_end=$this->otherfunctions->getDataExplode($e_plgcepat,':','end');
								$plgcepat=(($pc_st!='00')?$pc_st.' Jam, ':null).(($pc_end!='00')?$pc_end.' Menit':null);
							}else{
								$plgcepat='<label class="label label-sm label-success label-xs"><i class="fa fa-check"></i> Tidak Pulang Cepat</label>';
							}
						}
						$plg_tlcp = '<table><tr><td>Terlambat </td><td>: '.$terlambat.'</td></tr><tr><td>Pulang Cepat </td><td>: '.$plgcepat.'</td></tr></table>';
					}
					$setJamMulai = (!empty($d->jam_mulai)) ? $d->jam_mulai : '00:00:00';
					$setJamSelesai = (!empty($d->jam_selesai)) ? $d->jam_selesai : '00:00:00';
					if($jumlah_jam_kerja !=null && $jumlah_jam_kerja != '' && $jumlah_jam_kerja != '-'){
						$jum_jam_ker =$this->otherfunctions->getStringInterval($jumlah_jam_kerja);
					}else{
						$jum_jam_ker =null;
					}
					$data_log='';
					$selesai=date('Y-m-d',strtotime($d->tanggal.' +1 day'));
					$data_log.='<h4 align="center"><b>Data Log Absen Karyawan '.$d->nama_karyawan.'</b><br>
						pada tanggal <b>'.$this->formatter->getDateMonthFormatUser($d->tanggal).' & '.$this->formatter->getDateMonthFormatUser($selesai).'</b></h4>
						<div style="max-height: 400px; overflow: auto;">
          				<table class="table table-bordered table-striped data-table">
          					<thead>
          						<tr class="bg-blue">
          							<th>No.</th>
          							<th>NIK</th>
          							<th>Lokasi Finger</th>
          							<th>Tanggal</th>
          							<th>Jam</th>
          							<th>Status</th>
          						</tr>
          					</thead>
          					<tbody>';
							$dLog=$this->model_karyawan->getDataTemporari($d->tanggal,$selesai,['a.id_karyawan'=>$d->id_karyawan],['kolom'=>'tanggal','value'=>'ASC']);
							if(!empty($dLog)){
          						$no=1;
          						foreach ($dLog as $dl) {
          							$data_log.='<tr>
										<td>'.$no.'</td>
										<td>'.$dl->nik.'</td>
										<td>'.$dl->nama_loker.'</td>
										<td>'.$this->formatter->getDateFormatUser($dl->tanggal).'</td>
										<td>'.$this->formatter->getTimeFormatUserFull($dl->jam,'WIB').'</td>
										<td>'.(($dl->sync)?'<i class="fa fa-check-circle stat scc" data-toggle="tooltip" title="Sudah Di Sinkronasi"></i>':'<i class="fa fa-times-circle stat err" data-toggle="tooltip" title="Belum Di Sinkronasi"></i>').'</td>
									</tr>';
									$no++;
								}
							}else{
								$data_log.='<tr>
									<td colspan="6" class="text-center"><b>Tidak Ada Data</b></td>
								</tr>';
							}
	          				$data_log.='</tbody>
						  </table>';
					$datax=[
						'id'=>$d->id_p_karyawan,
						'id_karyawan' => $d->id_karyawan,
						'nama_karyawan' => $d->nama_karyawan,
						'jabatan_karyawan' => $d->nama_jabatan,
						'tgl_presensi'=>$day.', '.$month,
						'tgl_masuk'=>$this->formatter->getTimeFormatUser($d->jam_mulai,'WIB'),
						'tgl_selesai'=>$this->formatter->getTimeFormatUser($d->jam_selesai,'WIB'),
						'gettgl_mulai'=>$this->formatter->getDateTimeFormatUser($d->tanggal.' '.$d->jam_mulai),
						'gettgl_selesai'=>$this->formatter->getDateTimeFormatUser($d->tanggal.' '.$d->jam_selesai),
						'jam_kerja'=>$this->otherfunctions->getLabelMark($jum_jam_ker,'danger','Tidak Ada Data'),
						'kode_shift' => $d->kode_shift,
						'jadwal'=>$jadwal_jam_kerja,
						'over'=>$over,
						'plg_trlmbt'=>$plg_tlcp,
						'ijin_cuti'=>$this->otherfunctions->getLabelMark($nama_izin_cuti,'success','Tidak Izin'),
						'lembur'=>$this->otherfunctions->getLabelMark($v_lembur,'success','Tidak Lembur'),
						'libur'=>$this->otherfunctions->getLabelMark($d->nama_libur,'success','Tidak Libur',''),
						'status'=>$status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'update_by'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
						'tanggal'=>$this->formatter->getDateFormatUser($d->tanggal),
						'tanggal_db'=>$d->tanggal,
						'jam_mulai'=>$setJamMulai,
						'jam_selesai'=>$setJamSelesai,
						'data_log'=>$data_log,
					];
				}
				echo json_encode($datax);
			
			}elseif ($usage == 'get_karyawan_select') {
				$bagian = $this->input->post('bagian');
				$karyawan = $this->model_karyawan->getKaryawanBagianJoin($bagian, true);
				$sel_karyawan = '<option value="all">Pilih Semua</option>';
				foreach ($karyawan as $bkey) {
					$sel_karyawan .= '<option value="'.$bkey->id_kar.'">'.$bkey->nama_kar.' - '.$bkey->nama_jabatan.'</option>';
				}
				$datax = ['karyawan'=>$sel_karyawan];
        		echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function import_presensi_log()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$mesin = $this->input->post('kode_mesin');
		if($mesin=='1'){
			//solusion
			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			$sheet[0]=[
				'range_huruf'=>3,
				'row'=>2,
				'table'=>'temporari_data_presensi',
				'column_code'=>'id_karyawan',
				'usage'=>'presensi_one_log',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'column'=>[
					2=>'id_finger',3=>'tanggal_waktu',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		}elseif($mesin=='2'){
			//FP
			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			$sheet[0]=[
				'range_huruf'=>3,
				'row'=>2,
				'table'=>'temporari_data_presensi',
				'column_code'=>'id_karyawan',
				'usage'=>'presensi_one_log',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'column'=>[
					1=>'id_finger',4=>'tanggal_waktu',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		}elseif($mesin=='dat'){
			$extd = pathinfo($_FILES['file']['name']);
			if($extd['extension'] == 'dat'){
				if($_FILES["file"]["name"]){
					$config['file_name'] 		= 'Data_log_presensi.dat';
					$config['allowed_types']    = 'dat';
					$config['upload_path']      = './application/document';
					$this->load->library('upload', $config);
					$datalogup = $this->upload->do_upload('file');
					$dt=$this->upload->data();
					if (!$datalogup){
						$datax = $this->messages->customFailure('Gagal di Upload');
					}else{
						$datalogup = $this->upload->data("file_name");
						$dataakta = array('upload_data' => $this->upload->data());
						$open = fopen(APPPATH.'document/Data_log_presensi.dat','r');
						$data = [];
						while (!feof($open)) 
						{
							$getTextLine = fgets($open);
							$explodeLine = explode("\t",$getTextLine);
							$id_finger = $explodeLine[0];
							if(!empty($id_finger)){
								$emp = $this->model_karyawan->getEmployeeFinger($id_finger);
								$wkt = $this->otherfunctions->getDataExplode($explodeLine[1],' ','all');
								$date = $wkt[0];
								$time = $wkt[1];
								$dataLog = [
									'id_finger'=>$id_finger,
									'id_karyawan'=>(isset($emp['id_karyawan'])) ? ((!empty($emp['id_karyawan'])) ? $emp['id_karyawan'] : null) : null,
									'tanggal'=>(isset($date)) ? ((!empty($date)) ? $date : null) : null,
									'jam'=>(isset($time)) ? ((!empty($time)) ? $time : null) : null,
									'sync'=>0,
								];
								$dataLog=array_merge($dataLog,$this->model_global->getCreateProperties($this->admin));
								$where = [  
									'id_karyawan'=>$emp['id_karyawan'],
									'id_finger'=>$id_finger,
									'tanggal'=>$date,
									'jam'=>$time,
								];
								$cekTemp = $this->model_karyawan->getTemporariWhere($where);
								if(empty($cekTemp)){
									$datax = $this->model_global->insertQuery($dataLog,'temporari_data_presensi');
								}else{
									$datax = $this->messages->customGood('Data Sudah Ada Di Database.');
								}
							}
						}
						fclose($open);
						unlink(APPPATH.'document/Data_log_presensi.dat');
					}
				}
			}
		}elseif($mesin=='txt'){
			$extd = pathinfo($_FILES['file']['name']);
			if($extd['extension'] == 'txt' || $extd['extension'] == 'TXT'){
				if($_FILES["file"]["name"]){
					$config['file_name'] 		= 'Data_log_presensi.txt';
					$config['allowed_types']    = 'TXT|txt';
					$config['upload_path']      = './application/document';
					$this->load->library('upload', $config);
					$datalogup = $this->upload->do_upload('file');
					// var_dump($this->upload->data());
					if (!$datalogup){
						$datax = $this->messages->customFailure('Gagal di Upload');
					}else{
						$datalogup = $this->upload->data("file_name");
						$open = fopen(APPPATH.'document/Data_log_presensi.txt','r');
						$data = [];
						while (!feof($open)) 
						{
							$getTextLine = fgets($open);
							$explodeLine = explode("\t",$getTextLine);
							$id_finger = (isset($explodeLine[2])?$explodeLine[2]:null);
							$datetime = (isset($explodeLine[6])?$explodeLine[6]:null);
							if(!empty($id_finger) && $id_finger != 'EnNo' && !empty($datetime)){
								$emp = $this->model_karyawan->getEmployeeFinger($id_finger);
								$wkt = $this->otherfunctions->getDataExplode($datetime,' ','all');
								$date = $wkt[0];
								$time = $wkt[1];
								$dataLog = [
									'id_finger'=>$id_finger,
									'id_karyawan'=>(isset($emp['id_karyawan'])) ? ((!empty($emp['id_karyawan'])) ? $emp['id_karyawan'] : null) : null,
									'tanggal'=>(isset($date)) ? ((!empty($date)) ? $date : null) : null,
									'jam'=>(isset($time)) ? ((!empty($time)) ? $time : null) : null,
									'sync'=>0,
								];
								$dataLog=array_merge($dataLog,$this->model_global->getCreateProperties($this->admin));
								$where = [  
									'id_karyawan'=>$emp['id_karyawan'],
									'id_finger'=>$id_finger,
									'tanggal'=>$date,
									'jam'=>$time,
								];
								$cekTemp = $this->model_karyawan->getTemporariWhere($where);
								if(empty($cekTemp)){
									$datax = $this->model_global->insertQuery($dataLog,'temporari_data_presensi');
								}else{
									$datax = $this->messages->customGood('Data Sudah Ada Di Database.');
								}
							}
						}
						fclose($open);
						unlink(APPPATH.'document/Data_log_presensi.txt');
					}
				}
			}
		}else{
			$datax = $this->messages->notValidParam(); 
		}
		echo json_encode($datax);
	}
	public function edit_ganti_shift()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$id = $this->input->post('id');
		$id_karyawan = $this->input->post('id_karyawan');
		$tanggal = $this->input->post('tanggal');
		// echo $tanggal;
		// $shift_lama = $this->input->post('kode_master_shift_lama');
		$shift_baru = $this->input->post('kode_master_shift_baru');
		if(!empty($id)){
			if($shift_baru == 'SSL'){
				$mulai = $tanggal;
				$selesai=$tanggal;
			}else{
				$mulai = $tanggal;
				$selesai=date('Y-m-d',strtotime($tanggal.' +1 day'));
			}
			$dataLog=$this->model_karyawan->getDataTemporari($mulai,$selesai,['a.id_karyawan'=>$id_karyawan]);
			if(!empty($dataLog)){
				foreach($dataLog as $d){
					$data_in=[
						'id_karyawan'=>$d->id_karyawan,
						'tanggal'=>$tanggal,
						'jam'=>$d->jam,
					];
					$pushPres = $this->model_presensi->syncPresensiGantiShift($data_in,$shift_baru);
				}
			}else{
				$data_in=[
					'id_karyawan'=>$id_karyawan,
					'tanggal'=>$tanggal,
					'jam'=>null,
				];
				$pushPres = $this->model_presensi->syncPresensiGantiShift($data_in,$shift_baru);
			}
			if($pushPres){
				echo json_encode($this->messages->allGood());
			}else{
				echo json_encode($this->messages->allFailure());
			}
		}
	}
	public function data_presensi_log()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
		   echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				parse_str($this->input->post('form'), $post_form);
				$data=$this->model_karyawan->getListPresensiLog($post_form);
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					if(empty($d->id_karyawan)){
						$stt = ' <i class="fa fa-user-times" data-toggle="tooltips" title="Karyawan Belum Terdaftar" style="color:red"></i>';
					}else{
						$stt = null;
					}
					$aksi = '<button type="button" class="btn btn-info btn-sm" href="javascript:void(0)" onclick="view_modal_log('.$d->id_temporari.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
					$datax['data'][]=[
						$d->id_temporari,
						$d->id_finger,
						$d->nama_karyawan.$stt,
						$d->nama_jabatan,
						$this->formatter->getDayDateFormatUserId($d->tanggal),
						$d->jam.' WIB',
						$aksi,
					];
					$no++;
				}
				// $datax['data']=[];
				// while (strtotime($tanggal_selesai)>=strtotime($tanggal_mulai)){
				// 	// $day = $this->formatter->getNameOfDay($tanggal_selesai);
				// 	$month = $this->formatter->getDayDateFormatUserId($tanggal_selesai);
				// 	// $day_month = $day.', '.$month;
				// 	$libur = $this->model_master->cekHariLibur($tanggal_selesai,'date');
				// 	if(!empty($libur)){
				// 		$month = '<b class="text-danger" data-toggle="tooltip" data-placement="right" title="'.$libur.'">'.$month.'</b>';
				// 	}
				// 	$cek_data=$this->model_karyawan->getListPresensiLog($post_form,['tanggal'=>$tanggal_selesai]);
				// 	if (empty($cek_data)){
				// 		$id_tem = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
				// 		$finger = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
				// 		$nama_kar = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
				// 		$ja_kar = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
				// 		$tanggal_tem = $month; //$this->formatter->getDateMonthFormatUser($tanggal_selesai);
				// 		$jam_tem = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
				// 		$aksi_tem = '<div style="text-align: center;"><label class="label label-sm label-danger label-xs"><i class="fa fa-times"></i> Tidak Ada Data</label></div>';
				// 	}else{
				// 		// $datax['data']=[];
				// 		// $data=$this->model_karyawan->getListPresensiLog($post_form,['tanggal'=>$tanggal_selesai]);
				// 		foreach ($cek_data as $d) {
				// 			$aksi = '<button type="button" class="btn btn-info btn-xs" href="javascript:void(0)" onclick="view_modal_log('.$d->id_temporari.')"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button>';
				// 			$datax['data'][]=[
				// 				$id_tem = $d->id_temporari,
				// 				$finger = $d->id_finger,
				// 				$nama_kar = $d->nama_karyawan,
				// 				$ja_kar = $d->nama_jabatan,
				// 				$tanggal_tem = $month,//$this->formatter->getDateMonthFormatUser($tanggal_selesai),
				// 				$jam_tem = $d->jam.' WIB',
				// 				$aksi_tem = $aksi,
				// 			];
				// 		}
				// 	}
				// 	$datax['data'][]=[
				// 		$id_tem,
				// 		$finger,
				// 		$nama_kar,
				// 		$ja_kar,
				// 		$tanggal_tem,
				// 		$jam_tem,
				// 		$aksi_tem,
				// 	];
				// 	$tanggal_selesai = mktime(0,0,0,date("m",strtotime($tanggal_selesai)),date("d",strtotime($tanggal_selesai))-1,date("Y",strtotime($tanggal_selesai)));
				// 	$tanggal_selesai=date("Y-m-d", $tanggal_selesai);
				// }
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_temporari');
				$data=$this->model_karyawan->getListPresensiIdLog($id);
				foreach ($data as $d) {
					$day = $this->formatter->getNameOfDay($d->tanggal);
					$month = $this->formatter->getDateMonthFormatUser($d->tanggal);
					$status = ($d->status=='1') ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Tidak Aktif</b>';
					$datax=[
						'id'=>$d->id_temporari,
						'id_karyawan' => $d->id_karyawan,
						'nama_karyawan' => $d->nama_karyawan,
						'jabatan_karyawan' => $d->nama_jabatan,
						'tanggal'=>$day.', '.$month,
						'jam'=>$d->jam.' WIB',
						'status'=>$status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'update_by'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update),
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	public function sync_data_presensi()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$tanggal = $this->input->post('tanggal_sync');
		$lokasi = $this->input->post('lokasi');
		$level = $this->input->post('level');
		$ber_kar = $this->input->post('all_kar');
		$ber_lev = $this->input->post('all_lev');
		$all_karyawan = $this->input->post('all_karyawan');
		$all_level = $this->input->post('all_level');
		$karyawan = $this->input->post('karyawan');
		$tanggal_mulai = $this->formatter->getDateFromRange($tanggal,'start','no');
		$tanggal_selesai = $this->formatter->getDateFromRange($tanggal,'end','no');
		if($ber_kar == 1){
			if($all_karyawan == 1){
				$where="emp.loker='".$lokasi."'";
			}else{
				$where='';
				$c_lv=1;
				foreach ($karyawan as $key => $idkar) {
					$where.="emp.id_karyawan='".$idkar."'";
					if (count($karyawan) > $c_lv) {
						$where.=' OR ';
					}
					$c_lv++;
				}
			}
		}else{
			if($all_level == 1){
				$mLevel = $this->model_master->getLevelStrukturWhere(null,null,1);
				$or_lv='';
				$c_lv=1;
				foreach ($mLevel as $m) {
					$or_lv.="lvl.kode_level_struktur='".$m->kode_level_struktur."'";
					if (count($mLevel) > $c_lv) {
						$or_lv.=' OR ';
					}
					$c_lv++;
				}				
				$where="emp.loker = '".$lokasi."' and (".$or_lv.")";
			}else{
				$or_lv='';
				$c_lv=1;
				foreach ($level as $kl => $vl) {
					$or_lv.="lvl.kode_level_struktur='".$vl."'";
					if (count($level) > $c_lv) {
						$or_lv.=' OR ';
					}
					$c_lv++;
				}
				$where="emp.loker = '".$lokasi."' and (".$or_lv.")";
			}
		}
		$data=[];
		// $data_l=[];
		// $kode_lembur = '';
		$current_date=$tanggal_mulai;
		while ($current_date <= $tanggal_selesai)
		{
			$dataLogPreAll = $this->model_karyawan->getDataTemporariSync($current_date,$tanggal_selesai,$where);
			if ($dataLogPreAll) {
				// echo '<pre>';
				// print_r($dataLogPreAll);
				foreach ($dataLogPreAll as $kp => $pre) {
					$data[$current_date][$pre->id_karyawan]['id_karyawan']=$pre->id_karyawan;
					$data[$current_date][$pre->id_karyawan]['tanggal']=$current_date;
					$libur = $this->model_master->cekHariLiburDate($current_date);
					if (isset($libur)) {
						$data[$current_date][$pre->id_karyawan]['kode_hari_libur']=(!empty($libur['kode_hari_libur'])?$libur['kode_hari_libur']:null);
					}else{
						$data[$current_date][$pre->id_karyawan]['kode_hari_libur']=null;
					}
					$izin = $this->model_presensi->cekIzinCutiIdDate($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date);
					if (isset($izin)) {
						$data[$current_date][$pre->id_karyawan]['kode_ijin']=(!empty($izin['kode_izin_cuti'])?$izin['kode_izin_cuti']:null);
					}else{
						$data[$current_date][$pre->id_karyawan]['kode_ijin']=null;
					}
					$dinas=$this->model_karyawan->cekDataPerDinPresensi($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date);
					if ($dinas) {
						$data[$current_date][$pre->id_karyawan]['no_perjalanan_dinas']=(!empty($dinas['no_sk'])?$dinas['no_sk']:null);
					}else{
						$data[$current_date][$pre->id_karyawan]['no_perjalanan_dinas']=null;
					}
					$cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($pre->id_karyawan,$current_date);
					$lembur = $this->model_presensi->checkLemburSyncIDDate($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date);
					if (isset($lembur) && !empty($lembur)) {
						$data[$current_date][$pre->id_karyawan]['no_spl']=$lembur['no_lembur'];
						// $cekjadwal['jam_mulai']=$lembur['jam_mulai'];
						$cekjadwalLong['jam_selesai']=$lembur['jam_selesai'];
					}					
					$lama_lembur_first = $this->formatter->convertDecimaltoJam(10);
					$lama_lembur = $this->formatter->convertDecimaltoJam(16);
					$cek_lembur_first = $this->model_presensi->cekLemburIdDateNew($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date,['validasi'=>1,'val_jumlah_lembur >='=>$lama_lembur_first,'val_jumlah_lembur <'=>$lama_lembur]);
					$cek_lembur = $this->model_presensi->cekLemburIdDateNew($data[$current_date][$pre->id_karyawan]['id_karyawan'],$current_date,['validasi'=>1,'val_jumlah_lembur >='=>$lama_lembur]);
					// echo '<pre>';
					// print_r($cek_lembur_first);
					// echo 'Pertama<br>';
					// echo 'Kedua<br>';
					// print_r($cek_lembur);
					if(!empty($cek_lembur_first)){
						$cekjadwal2 = $this->model_presensi->cekJadwalKerjaIdDateJKB($pre->id_karyawan,$current_date);
						$data_jamx=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal2['kode_shift'],'tanggal'=>$current_date,'jam'=>$pre->jam,'jadwal'=>$cekjadwal2,'id_karyawan'=>$pre->id_karyawan]);
						// print_r($pre->id_karyawan);
						// print_r($data_jamx);
						$tgl_mulai = $cek_lembur_first['tgl_mulai'];
						$tgl_selesai = $cek_lembur_first['tgl_selesai'];
						while ($tgl_mulai <= $tgl_selesai)
						{
							$datay['id_karyawan'] = $cek_lembur_first['id_karyawan'];
							$datay['tanggal'] = $tgl_mulai;
							if(!empty($data_jamx)){
								if($tgl_mulai == $cek_lembur_first['tgl_mulai'] && $data_jamx['tanggal'] == $tgl_mulai){
									// echo 'Pertama - '.$tgl_mulai;
									$data_jam_mulai = null;
									if (isset($data_jamx['jam_mulai'])) {
										$data_jam_mulai=$data_jamx['jam_mulai'];
									}
									$data_jam_selesai = null;
									if (isset($data_jamx['jam_selesai'])) {
										$data_jam_selesai=$data_jamx['jam_selesai'];
									}else{
										$data_jam_selesai=$cekjadwalLong['jam_selesai'];
									}
									$datay['jam_mulai'] = $data_jam_mulai;
									$datay['jam_selesai'] = $data_jam_selesai;
									if(empty($datay['jam_mulai'])){
										unset($datay['jam_mulai']);
									}
									if(empty($datay['jam_selesai'])){
										unset($datay['jam_selesai']);
									}
									$datay['no_spl'] = $cek_lembur_first['no_lembur'];
									$datay['kode_shift'] = $cekjadwal2['kode_shift'];
									// $datay['jam_selesai'] = $cekjadwal['jam_selesai'];
									// print_r($datay);
									if (!empty($datay['id_karyawan']) && !empty($datay['tanggal']) && !empty($datay['kode_shift'])) {
										$cek=$this->model_karyawan->checkPresensiDate($datay['id_karyawan'],$datay['tanggal']);
										if (!$cek) {
											$datay=array_merge($datay,$this->model_global->getCreateProperties($this->admin));
											$datax=$this->model_global->insertQuery($datay,'data_presensi');
										}else{
											$datay=array_merge($datay,$this->model_global->getUpdateProperties($this->admin));
											$datax=$this->model_global->updateQuery($datay,'data_presensi',['tanggal'=>$datay['tanggal'],'id_karyawan'=>$datay['id_karyawan']]);
										}
									}
								}elseif($tgl_mulai == $cek_lembur_first['tgl_selesai'] && $tgl_mulai != $cek_lembur_first['tgl_mulai']){
									// echo 'Kedua - '.$tgl_mulai;
									$data_jam_selesai = null;
									if (isset($data_jamx['jam_selesai'])) {
										$data_jam_selesai=$data_jamx['jam_selesai'];
									}
									if(empty($datay['jam_mulai'])){
										unset($datay['jam_mulai']);
									}
									if(empty($datay['jam_selesai'])){
										unset($datay['jam_selesai']);
									}
									$datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
									$datay['jam_selesai'] = null;
									$datay['no_spl'] = null;
									$datay['kode_shift'] = $cekjadwal2['kode_shift'];
									// print_r($datay);
									if (!empty($datay['id_karyawan']) && !empty($datay['tanggal']) && !empty($datay['kode_shift'])) {
										$cek=$this->model_karyawan->checkPresensiDate($datay['id_karyawan'],$datay['tanggal']);
										if (!$cek) {
											$datay=array_merge($datay,$this->model_global->getCreateProperties($this->admin));
											$datax=$this->model_global->insertQuery($datay,'data_presensi');
										}else{
											$datay=array_merge($datay,$this->model_global->getUpdateProperties($this->admin));
											$datax=$this->model_global->updateQuery($datay,'data_presensi',['tanggal'=>$datay['tanggal'],'id_karyawan'=>$datay['id_karyawan']]);
										}
									}
								}
							}
							$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
						}
					}elseif(!empty($cek_lembur)){
						$cekjadwal2 = $this->model_presensi->cekJadwalKerjaIdDateJKB($pre->id_karyawan,$current_date);
						$data_jamx=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal2['kode_shift'],'tanggal'=>$current_date,'jam'=>$pre->jam,'jadwal'=>$cekjadwal2,'id_karyawan'=>$pre->id_karyawan]);
						$tgl_mulai = $cek_lembur['tgl_mulai'];
						$tgl_selesai = $cek_lembur['tgl_selesai'];
						while ($tgl_mulai <= $tgl_selesai)
						{
							$datay['id_karyawan'] = $cek_lembur['id_karyawan'];
							$datay['tanggal'] = $current_date;
							if(!empty($data_jamx)){
								if($current_date == $cek_lembur['tgl_mulai'] && $current_date != $cek_lembur['tgl_selesai']){
									$data_jam_mulai = null;
									if (isset($data_jamx['jam_mulai'])) {
										$data_jam_mulai=$data_jamx['jam_mulai'];
									}
									$datay['jam_mulai'] = $data_jam_mulai;
									$datay['jam_selesai'] = $cekjadwal2['jam_selesai'];
									$datay['no_spl'] = $cek_lembur['no_lembur'];
								}elseif($current_date != $cek_lembur['tgl_mulai'] && $current_date != $cek_lembur['tgl_selesai']){
									$datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
									$datay['jam_selesai'] = $cekjadwal2['jam_selesai'];
								}elseif($current_date == $cek_lembur['tgl_selesai'] && $current_date != $cek_lembur['tgl_mulai']){
									$data_jam_selesai = null;
									if (isset($data_jamx['jam_selesai'])) {
										$data_jam_selesai=$data_jamx['jam_selesai'];
									}
									$datay['jam_mulai'] = $cekjadwal2['jam_mulai'];
									$datay['jam_selesai'] = $data_jam_selesai;
								}else{
									$data_jam_mulai = null;
									if (isset($data_jamx['jam_mulai'])) {
										$data_jam_mulai=$data_jamx['jam_mulai'];
									}else{
										$data_jam_mulai = $cekjadwal2['jam_mulai'];
									}
									$data_jam_selesai = null;
									if (isset($data_jamx['jam_selesai'])) {
										$data_jam_selesai=$data_jamx['jam_selesai'];
									}
									$datay['jam_mulai'] = $data_jam_mulai;
									$datay['jam_selesai'] = $data_jam_selesai;
								}
							}
							$datay['kode_shift'] = $cekjadwal2['kode_shift'];
							$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
							if (!empty($datay['id_karyawan']) && !empty($datay['tanggal']) && !empty($datay['kode_shift'])) {
								$cek=$this->model_karyawan->checkPresensiDate($datay['id_karyawan'],$datay['tanggal']);
								if (!$cek) {
									$datax=$this->model_global->insertQuery($datay,'data_presensi');
								}else{
									$datax=$this->model_global->updateQuery($datay,'data_presensi',['tanggal'=>$datay['tanggal'],'id_karyawan'=>$datay['id_karyawan']]);
								}
							}
						}
					}else{
						// $cekjadwal['jam_selesai']=$cekjadwal['jam_selesai'];
						$data_jam=$this->model_karyawan->coreImportPresensi(['kode_shift'=>$cekjadwal['kode_shift'],'tanggal'=>$current_date,'jam'=>$pre->jam,'jadwal'=>$cekjadwal,'id_karyawan'=>$pre->id_karyawan]);
						$data[$current_date][$pre->id_karyawan]['kode_shift'] = $cekjadwal['kode_shift'];
						// echo '<pre>';
						// print_r($data_jam);
						if (isset($data_jam['tanggal'])) {
							$data[$data_jam['tanggal']][$pre->id_karyawan]['id_karyawan']=$pre->id_karyawan;
							$data[$data_jam['tanggal']][$pre->id_karyawan]['tanggal']=$data_jam['tanggal'];
							if (isset($data_jam['jam_mulai'])) {
								$data[$data_jam['tanggal']][$pre->id_karyawan]['jam_mulai']=$data_jam['jam_mulai'];
							}
							if (isset($data_jam['jam_selesai'])) {
								$data[$data_jam['tanggal']][$pre->id_karyawan]['jam_selesai']=$data_jam['jam_selesai'];
							}
							if (isset($data_jam['kode_shift'])) {
								$data[$data_jam['tanggal']][$pre->id_karyawan]['kode_shift']=$data_jam['kode_shift'];
							}
						}else{
							$data[$current_date][$pre->id_karyawan]['id_karyawan']=$pre->id_karyawan;
							$data[$current_date][$pre->id_karyawan]['tanggal']=$current_date;
							if (isset($data_jam['jam_mulai'])) {
								$data[$current_date][$pre->id_karyawan]['jam_mulai']=$data_jam['jam_mulai'];
							}
							if (isset($data_jam['jam_selesai'])) {
								$data[$current_date][$pre->id_karyawan]['jam_selesai']=$data_jam['jam_selesai'];
							}
							if (isset($data_jam['kode_shift'])) {
								$data[$current_date][$pre->id_karyawan]['kode_shift']=$data_jam['kode_shift'];
							}
						}
					}				
				}
			}else{
				$dataKar = $this->model_karyawan->getEmployeeWhere($where);
				if(!empty($dataKar)){
					foreach($dataKar as $d){
						$data[$current_date][$d->id_karyawan]['id_karyawan']=$d->id_karyawan;
						$data[$current_date][$d->id_karyawan]['tanggal']=$current_date;
						$libur = $this->model_master->cekHariLiburDate($current_date);
						if (isset($libur)) {
							$data[$current_date][$d->id_karyawan]['kode_hari_libur']=(!empty($libur['kode_hari_libur'])?$libur['kode_hari_libur']:null);
						}else{
							$data[$current_date][$d->id_karyawan]['kode_hari_libur']=null;
						}
						$izin = $this->model_presensi->cekIzinCutiIdDate($data[$current_date][$d->id_karyawan]['id_karyawan'],$current_date);
						if (isset($izin)) {
							$data[$current_date][$d->id_karyawan]['kode_ijin']=(!empty($izin['kode_izin_cuti'])?$izin['kode_izin_cuti']:null);
						}else{
							$data[$current_date][$d->id_karyawan]['kode_ijin']=null;
						}
						$dinas=$this->model_karyawan->cekDataPerDinPresensi($data[$current_date][$d->id_karyawan]['id_karyawan'],$current_date);
						if ($dinas) {
							$data[$current_date][$d->id_karyawan]['no_perjalanan_dinas']=(!empty($dinas['no_sk'])?$dinas['no_sk']:null);
						}else{
							$data[$current_date][$d->id_karyawan]['no_perjalanan_dinas']=null;
						}
					}
				}
				// $datax=$this->messages->customFailure('Data Tidak Di Temukan Dalam Log Presensi');
			}
			$current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
		}
		// echo '<pre>';
		// print_r($data);
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
							$data_in=array_merge($d,$this->model_global->getCreateProperties($this->admin));
							$datax=$this->model_global->insertQuery($data_in,'data_presensi'); 
						}else{
							if (isset($wh['jam_mulai']) || isset($wh['jam_selesai'])) {
								$data_in=array_merge($d,$this->model_global->getUpdateProperties($this->admin));
								$datax=$this->model_global->updateQuery($data_in,'data_presensi',['tanggal'=>$tanggal,'id_karyawan'=>$id_karyawan]);
							}else{
								// $datax=$this->messages->allGood(); 
								$data_in=array_merge($d,$this->model_global->getUpdateProperties($this->admin));
								$datax=$this->model_global->updateQuery($data_in,'data_presensi',['tanggal'=>$tanggal,'id_karyawan'=>$id_karyawan]);
							}
						}
					}
				}
			}
		}else{
			$datax=$this->messages->allGood();
		}
		// if(!empty($kode_lembur)){
		// 	foreach ($data_l as $d =>$dd) {
		// 		$data_pre = [
		// 			'id_karyawan' => $dd['id_karyawan'],
		// 			'no_spl' => $dd['no_spl'],
		// 			'tanggal' => $dd['tanggal'],
		// 			'jam_mulai' => $dd['jam_mulai'],
		// 			'jam_selesai' => $dd['jam_selesai'],
		// 			'kode_shift' => $dd['kode_shift'],
		// 		];
		// 		if (!empty($dd['id_karyawan']) && !empty($dd['tanggal']) && !empty($dd['kode_shift'])) {
		// 			$cek=$this->model_karyawan->checkPresensiDate($dd['id_karyawan'],$dd['tanggal']);
		// 			if (!$cek) {
		// 				// $datax=$this->model_global->insertQuery($data_pre,'data_presensi');
		// 			}else{
		// 				// $datax=$this->model_global->updateQuery($data_pre,'data_presensi',['tanggal'=>$dd['tanggal'],'id_karyawan'=>$dd['id_karyawan']]);
		// 			}
		// 		}
		// 	}
		// }
		// $datax=$this->messages->allGood();
		echo json_encode($datax);
	}
	public function sync_data_presensi_old()
	{
		if (!$this->input->is_ajax_request())
			redirect('not_found');
		$tanggal = $this->input->post('tanggal_sync');
		$lokasi = $this->input->post('lokasi');
		$level = $this->input->post('level');
		$ber_kar = $this->input->post('all_kar');
		$ber_lev = $this->input->post('all_lev');
		$all_karyawan = $this->input->post('all_karyawan');
		$all_level = $this->input->post('all_level');
		$karyawan = $this->input->post('karyawan');
		$tanggal_mulai = $this->formatter->getDateFromRange($tanggal,'start','no');
		$tanggal_selesai = $this->formatter->getDateFromRange($tanggal,'end','no');
		$dataLogPreAll = [];
		if($ber_kar == 1){
			if($all_karyawan == 1){
				$where = ['emp.loker'=>$lokasi];
				$dataLogPreAll[] = $this->model_karyawan->getDataTemporari($tanggal_mulai,$tanggal_selesai,$where);
			}else{
				foreach ($karyawan as $key => $idkar) {
					$where = ['emp.id_karyawan'=>$idkar];
					$dataLogPreAll[] = $this->model_karyawan->getDataTemporari($tanggal_mulai,$tanggal_selesai,$where);
				}
			}
		}else{
			if($all_level == 1){
				$mLevel = $this->model_master->getLevelStrukturWhere(null,null,1);
				foreach ($mLevel as $m) {
					$where = ['emp.loker'=>$lokasi,'lvl.kode_level_struktur'=>$m->kode_level_struktur];
					$dataLogPreAll[] = $this->model_karyawan->getDataTemporari($tanggal_mulai,$tanggal_selesai,$where);
				}
			}else{
				foreach ($level as $kl => $vl) {
					$where = ['emp.loker'=>$lokasi,'lvl.kode_level_struktur'=>$vl];
					$dataLogPreAll[] = $this->model_karyawan->getDataTemporari($tanggal_mulai,$tanggal_selesai,$where);
				}
			}
		}
		$date_loop=$this->formatter->dateLoopFull($tanggal_mulai,$tanggal_selesai);
		$data_temp=[];
		$data_jam=[];
		if (!empty($dataLogPreAll)) {
			foreach ($dataLogPreAll as $kp => $dataLogPre) {
				foreach ($dataLogPre as $pre) {
					$cekjadwal = $this->model_presensi->cekJadwalKerjaIdDateJKB($pre->id_karyawan, $pre->tanggal);
					if (!empty($cekjadwal['kode_shift'])) {
						$data_temp[$pre->id_karyawan][$pre->tanggal]=[
							'tanggal'=>$pre->tanggal,
							'kode_shift'=>$cekjadwal['kode_shift'],
							'jadwal'=>$cekjadwal,
						];
						$data_jam[$pre->id_karyawan][$pre->tanggal][]=$pre->jam;
					}else{
						$datax=$this->messages->allGood();
					}			
				}
			}
		}else{
			$datax=$this->messages->customFailure('Data Tidak Di Temukan Dalam Log Presensi');
		}
		if (count($data_temp) > 0) {
			$data_l=[];
			$kode_lembur = '';
			foreach ($data_temp as $id_karyawan => $date) {
				foreach ($date as $temp) {
					$data['id_karyawan']=$id_karyawan;
					$data['tanggal']=$temp['tanggal'];
					$data['kode_shift']=$temp['kode_shift'];
					if (isset($data_jam[$id_karyawan][$temp['tanggal']])) {
						$jam_mulai=[];
						$jam_selesai=[];
						foreach ($data_jam[$id_karyawan][$temp['tanggal']] as $times) {
							$lama_lembur = $this->formatter->convertDecimaltoJam(13);
							$cek_lembur = $this->model_karyawan->getLembur(null,['a.id_karyawan'=>$id_karyawan,'a.tgl_mulai'=>$temp['tanggal'],'a.validasi'=>1,'a.val_jumlah_lembur >='=>$lama_lembur]);
							if(!empty($cek_lembur)){
								foreach ($cek_lembur as $cl) {
									$kode_lembur .= $cl->no_lembur;
									$tgl_mulai = $cl->tgl_mulai;
									$tgl_selesai = $cl->tgl_selesai;
									while ($tgl_mulai <= $tgl_selesai)
									{
										$datay['id_karyawan'] = $cl->id_karyawan;
										$datay['no_spl'] = $cl->no_lembur;
										$datay['tanggal'] = $tgl_mulai;
										if($tgl_mulai == $cl->tgl_mulai && $tgl_mulai != $cl->tgl_selesai){
											$datay['jam_mulai'] = min($data_jam[$id_karyawan][$cl->tgl_mulai]);
											$datay['jam_selesai'] = $temp['jadwal']['jam_selesai'];
										}elseif($tgl_mulai != $cl->tgl_mulai && $tgl_mulai != $cl->tgl_selesai){
											$datay['jam_mulai'] = $temp['jadwal']['jam_mulai'];
											$datay['jam_selesai'] = $temp['jadwal']['jam_selesai'];
										}elseif($tgl_mulai == $cl->tgl_selesai && $tgl_mulai != $cl->tgl_mulai){
											$datay['jam_mulai'] = $temp['jadwal']['jam_mulai'];
											$datay['jam_selesai'] = min($data_jam[$id_karyawan][$cl->tgl_selesai]);
										}else{
											$datay['jam_mulai'] = min($data_jam[$id_karyawan][$cl->tgl_mulai]);
											$datay['jam_selesai'] = min($data_jam[$id_karyawan][$cl->tgl_selesai]);
										}
										$datay['kode_shift'] = $temp['jadwal']['kode_shift'];
										$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
										array_push($data_l,$datay);
									}
								}
							}else{
								$dataa['kode_shift'] 	= $temp['kode_shift'];
								$dataa['jadwal'] 		= $temp['jadwal'];
								$dataa['tanggal'] 		= $temp['tanggal'];
								$dataa['id_karyawan'] 	= $id_karyawan;
								$dataa['jam'] 			= $times;
								$dataz = array_merge($temp['jadwal'],['jam'=>$times]);
								// $data_check_jam 		= $this->model_karyawan->coreImportPresensiJKB($dataz);
								$data_check_jam 		= $this->model_karyawan->coreImportPresensi($dataa);
								if (isset($data_check_jam['jam_mulai'])) {
									array_push($jam_mulai,(!empty($data_check_jam['jam_mulai'])?$data_check_jam['jam_mulai']:null));
								}
								if (isset($data_check_jam['jam_selesai'])) {
									array_push($jam_selesai,(!empty($data_check_jam['jam_selesai'])?$data_check_jam['jam_selesai']:null));
								}
							}
						}
						$data['jam_mulai']=((count($jam_mulai)>0)?min($jam_mulai):null);
						$data['jam_selesai']=((count($jam_selesai)>0)?min($jam_selesai):null);
					}
					$libur = $this->model_master->cekHariLiburDate($temp['tanggal']);
					if (isset($libur)) {
						$data['kode_hari_libur']=(!empty($libur['kode_hari_libur'])?$libur['kode_hari_libur']:null);
					}else{
						$data['kode_hari_libur']=null;
					}
					$izin = $this->model_karyawan->cekIzinCutiPresensi($id_karyawan,$temp['tanggal']);
					if (isset($izin)) {
						$data['kode_ijin']=(!empty($izin['kode_izin_cuti'])?$izin['kode_izin_cuti']:null);
					}else{
						$data['kode_ijin']=null;
					}
					$lembur = $this->model_karyawan->cekDataLemburPresensi($id_karyawan,$temp['tanggal']);
					if (isset($lembur) && !empty($lembur)) {
						$data['no_spl']=$lembur['no_lembur'];
					}else{
						$data['no_spl']=null;
					}
					$perdin = $this->model_karyawan->cekDataPerDinPresensi($id_karyawan,$temp['tanggal']);
					if (isset($perdin) && !empty($perdin)) {
						$data['no_perjalanan_dinas']=$perdin['no_sk'];
					}else{
						$data['no_perjalanan_dinas']=null;
					}
					foreach ($date_loop as $dloop) {
						$cek_loop=$this->model_karyawan->checkPresensiDate($data['id_karyawan'],$dloop);
						if (!$cek_loop) {
							$cekjadwal_loop = $this->model_presensi->cekJadwalKerjaIdDateJKB($data['id_karyawan'],$dloop);
							if (isset($cekjadwal_loop['kode_shift'])) {
								$data_in_loop=[
									'id_karyawan'=>$data['id_karyawan'],
									'tanggal'=>$dloop,
									'kode_shift'=>$cekjadwal_loop['kode_shift'],
								];
								$libur_loop = $this->model_master->cekHariLiburDate($dloop);
								if (isset($libur_loop)) {
									$data_in_loop['kode_hari_libur']=(!empty($libur_loop['kode_hari_libur'])?$libur_loop['kode_hari_libur']:null);
								}else{
									$data_in_loop['kode_hari_libur']=null;
								}
								$izin_loop = $this->model_karyawan->cekIzinCutiPresensi($data_in_loop['id_karyawan'],$dloop);
								if (isset($izin_loop)) {
									$data_in_loop['kode_ijin']=(!empty($izin_loop['kode_izin_cuti'])?$izin_loop['kode_izin_cuti']:null);
								}else{
									$data_in_loop['kode_ijin']=null;
								}
								$lembur_loop = $this->model_karyawan->cekDataLemburPresensi($data_in_loop['id_karyawan'],$dloop);
								if (isset($lembur_loop) && !empty($lembur_loop)) {
									$data_in_loop['no_spl']=$lembur_loop['no_lembur'];
								}else{
									$data_in_loop['no_spl']=null;
								}
								$perdin_loop = $this->model_karyawan->cekDataPerDinPresensi($data_in_loop['id_karyawan'],$dloop);
								if (isset($perdin_loop) && !empty($perdin_loop)) {
									$data_in_loop['no_perjalanan_dinas']=$perdin_loop['no_sk'];
								}else{
									$data_in_loop['no_perjalanan_dinas']=null;
								}
								$data_in_loop=array_merge($data_in_loop,$this->model_global->getCreateProperties($this->admin));
								$datax=$this->model_global->insertQuery($data_in_loop,'data_presensi'); 
							}                                                            
						}
					}
					if (!empty($data['id_karyawan']) && !empty($data['tanggal']) && in_array($data['tanggal'],$date_loop)) {
						$cek=$this->model_karyawan->checkPresensiDate($data['id_karyawan'],$data['tanggal']);
						if (!$cek) {
							array_merge($data,$this->model_global->getCreateProperties($this->admin));
							$datax=$this->model_global->insertQuery($data,'data_presensi');
						}else{
							unset($data['create_date']);
							unset($data['create_by']);
							$datax=$this->model_global->updateQuery($data,'data_presensi',['tanggal'=>$data['tanggal'],'id_karyawan'=>$data['id_karyawan']]);
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
		}else{
			$datax=$this->messages->customFailure('Data Tidak Di Temukan Dalam Log Presensi');
		}
		echo json_encode($datax);
	}
	public function syncPresensiToJadwal()
	{
		if (!$this->input->is_ajax_request()) 
		   redirect('not_found');
		$karyawan = $this->input->post('karyawan');
		$tanggal = $this->input->post('tanggal');
		$tanggal_mulai = $this->formatter->getDateFromRange($tanggal,'start','no');
		$tanggal_selesai = $this->formatter->getDateFromRange($tanggal,'end','no');
		$date_loop=$this->formatter->dateLoopFull($tanggal_mulai,$tanggal_selesai);
		foreach ($date_loop as $tgl) {
			foreach ($karyawan as $keys => $kar) {
				$cekjadwal = $this->model_karyawan->cekJadwalKerjaIdDate($kar,$tgl);
				if(isset($cekjadwal)){
					$data = [	'kode_shift'=>(!empty($cekjadwal['kode_shift'])?$cekjadwal['kode_shift']:null),
							'jam_mulai'=>(!empty($cekjadwal['jam_mulai'])?$cekjadwal['jam_mulai']:null),
							'jam_selesai'=>(!empty($cekjadwal['jam_selesai'])?$cekjadwal['jam_selesai']:null),
							'tanggal'=>$tgl,
							'id_karyawan'=>$kar,
						];
					$cek=$this->model_karyawan->checkPresensiDate($kar,$tgl);
					if (!$cek) {
						$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
						$datax=$this->model_global->insertQuery($data,'data_presensi');
					}else{
						unset($data['create_date']);
						unset($data['create_by']);
						$cekData=$this->model_karyawan->checkPresensiEmpDate($kar,$tgl);
						$data['jam_mulai'] = (empty($cekData['jam_mulai'])?$data['jam_mulai']:$cekData['jam_mulai']);
						$data['jam_selesai'] = (empty($cekData['jam_selesai'])?$data['jam_selesai']:$cekData['jam_selesai']);
						$datax=$this->model_global->updateQuery($data,'data_presensi',['tanggal'=>$tgl,'id_karyawan'=>$kar,'kode_shift'=>$data['kode_shift']]);
					}
				}
			}
		}
		echo json_encode($datax);
	}
	public function data_presensi_pa()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$usage=$this->uri->segment(3);
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'view_all') {
				$access=$this->codegenerator->decryptChar($this->input->post('access'));
				$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
				$data=$this->model_presensi->getListPresensi(false,$filter);				
				$no=1;
				$datax['data']=[];
				foreach ($data as $d) {
					$var=[
						'id'=>$d->id_presensi,
						'create'=>$d->create_date,
						'update'=>$d->update_date,
						'access'=>$access,
						'status'=>$d->status,
					];
					$properties=$this->otherfunctions->getPropertiesTable($var);
					$datax['data'][]=[
						$d->id_presensi,
						((!empty($d->bulan)) ? $this->formatter->getMonth()[$this->otherfunctions->addFrontZero($d->bulan)] : 'Unknown').' '.$d->tahun,
						$d->nik,
						$d->nama,
						$d->nama_jabatan,
						$d->nama_bagian,
						$d->nama_loker,						
						(!empty($d->ijin)) ? $d->ijin: 0,
						(!empty($d->telat)) ? $d->telat: 0,
						(!empty($d->mangkir)) ? $d->mangkir: 0,
						(!empty($d->sp)) ? $d->sp: 0,
						$properties['tanggal'],
						$properties['status'],
						$properties['aksi']
					];
					$no++;
				}
				echo json_encode($datax);
			}elseif ($usage == 'view_one') {
				$id = $this->input->post('id_presensi');
				$data=$this->model_presensi->getPresensi($id);
				foreach ($data as $d) {
					$ijin=(!empty($d->ijin)) ? $d->ijin : 0;
					$mangkir=(!empty($d->mangkir)) ? $d->mangkir : 0;
					$telat=(!empty($d->telat)) ? $d->telat : 0;
					$sp=(!empty($d->sp)) ? $d->sp : 0;
					$table ='<tr><td>Ijin</td><td>'.$ijin.'</td></tr>
					<tr><td>Terlambat</td><td>'.$telat.'</td></tr>
					<tr><td>Bolos</td><td>'.$mangkir.'</td></tr>
					<tr><td>SP</td><td>'.$sp.'</td></tr>';
					$datax=[
						'id'=>$d->id_presensi,
						'bulan_tahun'=>((!empty($d->bulan)) ? $this->formatter->getMonth()[$this->otherfunctions->addFrontZero($d->bulan)] : 'Unknown').' '.$d->tahun,
						'nik'=>$d->nik,
						'nama'=>$d->nama,
						'nama_jabatan'=>$d->nama_jabatan,
						'nama_loker'=>$d->nama_loker,
						'nama_bagian'=>$d->nama_bagian,
						'data_tr_view'=>$table,
						'bulan_val'=>$this->otherfunctions->addFrontZero($d->bulan),
						'tahun_val'=>$d->tahun,
						'ijin_val'=>$d->ijin,
						'mangkir_val'=>$d->mangkir,
						'telat_val'=>$d->telat,
						'sp_val'=>$d->sp,
						'status'=>$d->status,
						'create_date'=>$this->formatter->getDateTimeMonthFormatUser($d->create_date),
						'update_date'=>$this->formatter->getDateTimeMonthFormatUser($d->update_date),
						'create_by'=>$d->create_by,
						'update_by'=>$d->update_by,
						'nama_buat'=>(!empty($d->nama_buat)) ? $d->nama_buat:$this->otherfunctions->getMark($d->nama_buat),
						'nama_update'=>(!empty($d->nama_update))?$d->nama_update:$this->otherfunctions->getMark($d->nama_update)
					];
				}
				echo json_encode($datax);
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
	function add_data_presensi_pa(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$karyawan=$this->input->post('karyawan');
		if (isset($karyawan)) {
			$bulan=$this->input->post('bulan');
			$tahun=$this->input->post('tahun');
			$ijin=($this->input->post('ijin') != '') ? $this->input->post('ijin') : 0;
			$mangkir=($this->input->post('mangkir') != '') ? $this->input->post('mangkir') : 0;
			$telat=($this->input->post('telat') != '') ? $this->input->post('telat') : 0;
			$sp=($this->input->post('sp') != '') ? $this->input->post('sp') : 0;
			foreach ($karyawan as $k) {
				$kar=$this->model_karyawan->getEmployeeId($k);
				$data=[
					'id_karyawan'=>$k,
					'nik'=>$kar['nik'],
					'id_finger'=>$kar['id_finger'],
					'ijin'=>(!empty($ijin)) ? $ijin : null,
					'mangkir'=>(!empty($mangkir)) ? $mangkir : null,
					'telat'=>(!empty($telat)) ? $telat : null,
					'sp'=>(!empty($sp)) ? $sp : null,
					'bulan'=>$bulan,
					'tahun'=>$tahun,
				];
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$cek=$this->model_presensi->checkPresensiMonthYear($kar['nik'],$bulan,$tahun);
				if (!$cek) {
					$this->model_global->insertQueryNoMsg($data,'data_presensi_pa');
				}
			}
			$datax = $this->messages->allGood();
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function edt_data_presensi_pa(){
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$id=$this->input->post('id');
		if ($id != "") {
			$data_p=$this->model_presensi->getPresensi($id);
			$bulan=$this->input->post('bulan');
			$tahun=$this->input->post('tahun');
			$bulan_old=$this->input->post('bulan_old');
			$tahun_old=$this->input->post('tahun_old');
			$ijin=($this->input->post('ijin') != '') ? $this->input->post('ijin') : 0;
			$mangkir=($this->input->post('mangkir') != '') ? $this->input->post('mangkir') : 0;
			$telat=($this->input->post('telat') != '') ? $this->input->post('telat') : 0;
			$sp=($this->input->post('sp') != '') ? $this->input->post('sp') : 0;
			$data=[
				'ijin'=>(!empty($ijin)) ? $ijin : null,
				'mangkir'=>(!empty($mangkir)) ? $mangkir : null,
				'telat'=>(!empty($telat)) ? $telat : null,
				'sp'=>(!empty($sp)) ? $sp : null,
				'bulan'=>$bulan,
				'tahun'=>$tahun,
			];
			$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
			foreach ($data_p as $d) {
				if (($bulan == $bulan_old)&&($tahun == $tahun_old)) {
					$datax = $this->model_global->updateQuery($data,'data_presensi_pa',['id_presensi'=>$id]);
				}else{
					$cek=$this->model_presensi->checkPresensiMonthYear($d->nik,$bulan,$tahun);
					if (!$cek) {
						$datax = $this->model_global->updateQuery($data,'data_presensi_pa',['id_presensi'=>$id]);
					}else{
						$datax=$this->messages->customFailure('Data Sudah Ada di Database');
					}
				}
			}
		}else{
			$datax=$this->messages->notValidParam();
		}
		echo json_encode($datax);
	}
	function export_presensi_pa()
	{
		$data['properties']=[
			'title'=>"Template Data Presensi",
			'subject'=>"Template Data Presensi",
			'description'=>"Template untuk data presensi",
			'keywords'=>"Template Data, Template Presensi",
			'category'=>"Template",
		];
		$body=[];
		//filter data
		$dtroot=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
		$l_acc=$this->otherfunctions->getYourAccess($this->admin);
		$filter=(in_array('FTR', $l_acc))?$dtroot['kode_bagian']:0;
		$karyawan=$this->model_karyawan->getEmployeeAllActive(null,$filter);
		$row_body=2;
		$row=$row_body;
		foreach ($karyawan as $k) {
			$body[$row]=[$k->id_finger,$k->nik,$k->nama,$k->nama_jabatan,$k->bagian,$k->nama_loker];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Presensi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'ID FINGER','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','LOKASI KERJA','BULAN (1 - 12)','TAHUN','IJIN','TERLAMBAT','BOLOS','SP'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	function import_data_presensi_pa()
	{
		if (!$this->input->is_ajax_request()) 
			redirect('not_found');
		$data['properties']=[
			'post'=>'file',
			'data_post'=>$this->input->post('file', TRUE),
		];
		$sheet[0]=[
			'range_huruf'=>3,
			'row'=>2,
			'table'=>'data_presensi_pa',
			'column_code'=>'nik',
			'usage'=>'presensi_pa',
			'column_proerties'=>$this->model_global->getCreateProperties($this->admin),
			'column'=>[
				0=>'id_finger',1=>'nik',6=>'bulan',7=>'tahun',8=>'ijin',9=>'telat',10=>'mangkir',11=>'sp',
			],
		];
		$data['data']=$sheet;
		$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	public function sync_data_presensi_pa()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');
		$bulan = $this->input->post('bulan_for');
		$tahun = $this->input->post('tahun');
		$kar=$this->input->post('karyawan');
		$all_kar=$this->input->post('all_kary');
		if ($all_kar=='1') {
			$empl = $this->model_karyawan->getListEmployeeActive();
			foreach ($empl as $k_em=>$v_em) {
				$empx[]=$k_em;
			}
			$karyawan = $empx;
		}else{
			$karyawan = $kar;
		}
		foreach ($karyawan as $k_emp => $emp) {
			$jum_i = 0;
			$getMasterIzin = $this->model_master->getMasterIzin(null,['a.ikut_pa'=>1]);
			foreach ($getMasterIzin as $mi) {
				$izin = $this->model_karyawan->getIzinCuti(null,['a.jenis'=>$mi->kode_master_izin,'MONTH(a.tgl_mulai)'=>$bulan,'YEAR(a.tgl_mulai)'=>$tahun,'a.id_karyawan'=>$emp]);
				if(!empty($izin) || $izin != 0){
					$jum_i += count($izin);
				}
			}
			$dataPeringatanEmp=$this->model_karyawan->getPeringatanKerja(null,['a.potong_pa !=' =>null,'MONTH(a.tgl_berlaku)'=>$bulan,'YEAR(a.tgl_berlaku)'=>$tahun,'a.id_karyawan'=>$emp]);
			$jum_sp = 0;
			foreach ($dataPeringatanEmp as $dpe) {
				$jum_sp += $dpe->potong_pa;
			}
			$jum_bolos = 0;
			$jum_telat = 0;
			for($i=1;$i<32;$i++){
				$hari = ($i < 10)?'0'.$i:$i;
				$tanggal=$tahun.'-'.$bulan.'-'.$hari;
				$presensi = $this->model_karyawan->getListPresensiId(null,['pre.tanggal'=>$tanggal,'pre.id_karyawan'=>$emp]);
				if(empty($presensi)){
					$cekTanggal = $this->formatter->cekValueTanggal($bulan,$tahun);
					if($i < $cekTanggal){
						$jum_bolos +=1;
					}
				}else{
					foreach ($presensi as $pre) {
						$jadwal = $this->model_karyawan->cekJadwalKerjaIdDate($emp,$pre->tanggal);
						if($pre->jam_mulai > $jadwal['jam_mulai']){
							$jum_telat +=1;
						}
					}
				}
			}
			$nik = $this->model_karyawan->getEmpID($emp)['nik'];
			$data=[
				'id_karyawan'=>$emp,
				'nik'        =>$nik,
				'id_finger'  =>$this->model_karyawan->getEmpID($emp)['id_finger'],
				'sp'         =>$jum_sp,
				'ijin'       =>$jum_i,
				'mangkir'    =>$jum_bolos,
				'telat'      =>$jum_telat,
				'bulan'      =>$bulan,
				'tahun'      =>$tahun,
			];
			$cekPresensiPA = $this->model_presensi->checkPresensiMonthYear($nik,$bulan,$tahun);
			if($cekPresensiPA){
				$data=array_merge($data,$this->model_global->getUpdateProperties($this->admin));
				$datax=$this->model_global->updateQuery($data,'data_presensi_pa',['id_karyawan'=>$emp,'bulan'=>$bulan,'tahun'=>$tahun]);
			}else{
				$data=array_merge($data,$this->model_global->getCreateProperties($this->admin));
				$datax=$this->model_global->insertQuery($data,'data_presensi_pa');
			}
		}
		echo json_encode($datax);
	}
}
