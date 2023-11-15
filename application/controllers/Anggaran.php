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

class Anggaran extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->date = $this->otherfunctions->getDateNow();
		if (isset($_SESSION['adm'])) {
			$this->admin = $_SESSION['adm']['id'];	
		}else{
			redirect('auth');
		}
	    $this->rando = $this->codegenerator->getPin(6,'number');		
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
	function del_anggaran(){
		$kode=$this->input->post('id');
		if ($kode != "") {
			$dt=$this->db->get_where('dp_anggaran',array('id_anggaran'=>$kode))->row_array();
			$tb=$dt['nama_tabel'];
			$this->dbforge->drop_table($tb,TRUE);

			$this->db->where('id_anggaran',$kode);
			$in=$this->db->delete('dp_anggaran');
			if ($in) {
				$this->messages->allGood(); 
			}else{
				$this->messages->allFailure();  
			}
		}else{
			$this->messages->notValidParam();  
		}
		redirect('pages/anggaran');
	}
	function export(){
		$kagd=$this->input->post('kode_agd');
		if ($kagd == "") {
			$this->messages->notValidParam();  
			redirect('pages/anggaran');
		}else{ 
			$kp=$this->input->post('kode');
			$pr=$this->model_master->cek_anggaran($kp);
			$tb=$this->input->post('tabel_agd');
			$indi=$this->input->post('indi');
			foreach ($indi as $i) {
				$dtk=$this->model_master->tb_sel_p($tb,$i);
				foreach ($dtk as $d) {
					$idk[$d->id_karyawan]=$d->id_karyawan;
				}
				$kpz=$this->model_master->which_indikator($i);
				$kpi[$i]=$kpz['kpi'];
			}
	        if(count($idk)>0){
	            $objPHPExcel = new PHPExcel();
				// Set document properties
				$objPHPExcel->getProperties()->setCreator("Galeh Fatma Eko A")
											 ->setLastModifiedBy("Galeh Fatma Eko A")
											 ->setTitle($pr['nama_anggaran'].' Semester'.$pr['semester'])
											 ->setSubject($pr['nama_anggaran'])
											 ->setDescription($pr['nama_anggaran'].' Semester'.$pr['semester'])
											 ->setKeywords($pr['nama_anggaran'])
											 ->setCategory($pr['nama_anggaran']);
				// Add some data
	            $tri=1;
				for ($chrf='A'; $chrf!="AAA"; $chrf++){
				 	$huruf[$tri]=$chrf;
				 	$tri++;
				}
				$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
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
				
	            
	            //$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth(10); 
	            $ch=6;
	            $ch1=7;
	            foreach ($kpi as $x => $val) {
	            	$objPHPExcel->getActiveSheet()->getStyle($huruf[$ch].'2')->applyFromArray(
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
		            $objPHPExcel->getActiveSheet()->getStyle($huruf[$ch1].'2')->applyFromArray(
		                array(
		                    'fill' => array(
		                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                        'color' => array('rgb' => '008902')
		                    ),
		                    'font' => array(
		                        'color' => array('rgb' => 'FFFFFF')
		                    )
		                )
		            );
		            $objPHPExcel->getActiveSheet()->getStyle($huruf[$ch].'1')->applyFromArray(
		                array(
		                    'fill' => array(
		                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                        'color' => array('rgb' => 'ffdd00')
		                    ),
		                    'font' => array(
		                        'color' => array('rgb' => '000000')
		                    )
		                )
		            );
		            $objPHPExcel->getActiveSheet()->getStyle($huruf[$ch1].'1')->applyFromArray(
		                array(
		                    'fill' => array(
		                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                        'color' => array('rgb' => '00f2ff')
		                    ),
		                    'font' => array(
		                        'color' => array('rgb' => '000000')
		                    )
		                )
		            );
		            $objPHPExcel->getActiveSheet(0)
		            			->getColumnDimension($huruf[$ch])
        						->setAutoSize(true);
        			$objPHPExcel->getActiveSheet(0)
		            			->getColumnDimension($huruf[$ch1])
        						->setAutoSize(true);			
	            	$objPHPExcel->setActiveSheetIndex(0)
				            ->setCellValue($huruf[$ch].'1', $x)
				            ->setCellValue($huruf[$ch1].'1', $val);
	            	$ch = $ch+2;
	            	$ch1= $ch1+2;
	            }
	            foreach (range('A', 'E') as $cool) {
	            	$objPHPExcel->getActiveSheet(0)
		            			->getColumnDimension($cool)
        						->setAutoSize(true);
	            }
				$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('A1:A2')
							->mergeCells('B1:B2')
							->mergeCells('C1:C2')
							->mergeCells('D1:D2')
							->mergeCells('E1:E2')
				            ->setCellValue('A1', 'No.')
				            ->setCellValue('B1', 'NIK')
				            ->setCellValue('C1', 'Nama')
				            ->setCellValue('D1', 'Jabatan')
				            ->setCellValue('E1', 'Kantor');
				$cch=6;
	            $cch1=7;
	            foreach ($kpi as $val) {
	            	$objPHPExcel->setActiveSheetIndex(0)
	            			->setCellValue($huruf[$cch].'2', 'ANGGARAN')
				            ->setCellValue($huruf[$cch1].'2', 'RIIL');
	            	$cch = $cch+2;
	            	$cch1= $cch1+2;
	            }            
				            

				$br=3;
				$no=1;
				foreach ($idk as $k) {
					$kr=$this->model_karyawan->emp($k);
					$jbt=$this->model_master->k_jabatan($kr['jabatan']);
					$lok=$this->model_master->k_loker($kr['unit']);
				    $objPHPExcel->setActiveSheetIndex(0)
				    		->setCellValue('A'.$br, $no.'.')
				            ->setCellValueExplicit('B'.$br, $kr['nik'], PHPExcel_Cell_DataType::TYPE_STRING)
				            ->setCellValue('C'.$br, $kr['nama'])
				            ->setCellValue('D'.$br, $jbt['jabatan'])
				            ->setCellValue('E'.$br, $lok['nama']);
				            $cch2=6;
				            $cch21=7;
				            foreach ($kpi as $val) {
				            	$objPHPExcel->setActiveSheetIndex(0)
							            ->setCellValue($huruf[$cch2].''.$br, 0)
							            ->setCellValue($huruf[$cch21].''.$br, 0);;
				            	$cch2 = $cch2+2;
				            	$cch21= $cch21+2;
				            } 
				            
				            $br++;   
				            $no++;    	
				}            
				
				// Rename worksheet
				$objPHPExcel->getActiveSheet()->setTitle('Data Anggaran');
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$pr['nama_anggaran'].'.xls"');
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
		$this->messages->allGood(); 
		redirect('pages/view_anggaran/'.$kp); 
	}
	function import(){
		$kag=$this->input->post('kode');
		if ($kag == "") {
			$this->messages->notValidParam();  
			redirect('pages/anggaran');
		}else{
			$kagd=$this->input->post('kode_agd');
			$tbagd=$this->input->post('tabel_agd');
			$agg=$this->model_master->cek_anggaran($kag);
			$fileName = $this->input->post('file', TRUE);

			$config['upload_path'] = './asset/upload-exel/'; 
			$config['file_name'] = $fileName;
			$config['max_size'] = 1000;
			$config['allowed_types'] = 'xls|xlsx|csv|ods|ots';

			$this->load->library('upload', $config);
			$this->upload->initialize($config); 
			
			if (!$this->upload->do_upload('file')) {
				$this->messages->customFailure($this->upload->display_errors()); 
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
				for ($chrf='A'; $chrf!="AAA"; $chrf++){
				 	$huruf[$tri]=$chrf;
				 	$tri++;
				}
				$rr=range('F', $highestColumn);
				$rr1=range('F', $highestColumn);
				$tt=0;
				foreach ($rr as $r) {
					if ($tt < count($rr1)) {
						$ind[$rr1[$tt]]=$sheet->getCell($rr1[$tt].'1')->getValue();
						$tt=$tt+2;
					}
				}
				for ($row = 3; $row <= $highestRow; $row++){  
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
						NULL,
						TRUE,
						FALSE);					
					foreach ($ind as $key => $in1) {
						$hr=array_search($key, $huruf);
						$ang=$sheet->getCell($huruf[$hr].$row)->getValue();
						$ril=$sheet->getCell($huruf[$hr+1].$row)->getValue();
						if ($ril > $ang) {
							$na=125;
						}elseif ($ril == $ang) {
							$na=100;
						}elseif ($ril < $ang) {
							$naa1=($ril/$ang)*100;
							$na=number_format($naa1,2);
						}else{
							$na=0;
						}
						if ($rowData[0][1] != "") {
							$dtkaa=$this->model_karyawan->emp_nik($rowData[0][1]);
							$data = array(
								"id_karyawan"=>$dtkaa['id_karyawan'],
								"nik"=> $rowData[0][1],
								"nama_karyawan"=> $rowData[0][2],
								"kode_indikator"=>$in1,
								"n1"=>$ang,
								"n2"=>$ril,
								"na"=>$na,
							);
							$this->db->insert($agg['nama_tabel'],$data);
						}
					}
				}
				$datt=array('kode_indikator'=>implode(',',$ind));
				$this->db->where('kode_anggaran',$kag);
				$in=$this->db->update('dp_anggaran',$datt);
				if ($in) {
					$this->messages->allGood();
				}else{
					$this->messages->allFailure();
				}
				redirect('pages/view_anggaran/'.$kag); 
			}
		}
	}
	function chain_anggaran(){
		$kode=$this->input->post('kode');
		$cek=$this->model_master->cek_anggaran($kode);
		if ($kode == "" || $cek == "") {
			$this->messages->notValidParam();  
			redirect('pages/anggaran');
		}else{
			$agd=$this->model_agenda->cek_agd($cek['kode_agenda']);
			$tbagd=$agd['tabel_agenda'];
			$indi=explode(',', $cek['kode_indikator']);
			$dtag=$this->model_master->tb_anggaran($cek['nama_tabel']);
			foreach ($dtag as $d) {
				$idk[$d->id_karyawan]=$d->id_karyawan;
			}
			foreach ($idk as $i) {
				foreach ($indi as $ind) {
					$dtt=$this->db->get_where($cek['nama_tabel'],array('id_karyawan'=>$i,'kode_indikator'=>$ind))->row_array();
					$dtt1=$this->db->get_where($tbagd,array('id_karyawan'=>$i,'kode_indikator'=>$ind))->row_array();
					$na=$dtt['na']*($dtt1['bobot']/100);
					$data=array(
						'id_karyawan'=>$i,
						'kode_indikator'=>$ind,
						'nra6'=>$dtt['na'],
						'na6'=>$na,
						'nilai_out'=>$na,
					);
					$wh=array('id_karyawan'=>$i,'kode_indikator'=>$ind);
					$this->db->where($wh);
					$this->db->update($tbagd,$data);
				}
			}
		}
		$data1=array('kait'=>'1');
		$this->db->where('kode_anggaran',$kode);
		$in=$this->db->update('dp_anggaran',$data1);
		if ($in) {
			$this->messages->allGood();
		}else{
			$this->messages->allFailure();
		}
		redirect('pages/agenda');
	}
}