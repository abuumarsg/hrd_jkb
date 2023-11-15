<?php

/**
 * GFEACORP - Web Developer
 *
 * @package  Codeigniter
 * @author   Galeh Fatma Eko Ardiansa <galeh.fatma@gmail.com>
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Exelex extends CI_Controller
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
	}
	/*
	public function index(){
		redirect('pages/dashboard');
	}
	*/
	public function export(){
        $ambildata = $this->db->query("SELECT nik,nama,email FROM karyawan")->result();
        
        if(count($ambildata)>0){
        	$dtk=$this->model_karyawan->list_karyawan();
            $objPHPExcel = new PHPExcel();
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("Galeh Fatma Eko A")
										 ->setLastModifiedBy("Galeh Fatma Eko A")
										 ->setTitle("Data Karyawan BPR WM")
										 ->setSubject("Data Karyawan BPR WM")
										 ->setDescription("Data Seluruh Karyawan")
										 ->setKeywords("Data Karyawan")
										 ->setCategory("Data Karyawan");
			// Add some data
			$objPHPExcel->getActiveSheet()->getStyle("A1:C1")->applyFromArray(
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
            //$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth(10); 
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'NIK')
			            ->setCellValue('B1', 'Nama')
			            ->setCellValue('C1', 'Email');

			            $br=2;
			foreach ($dtk as $k) {

			     $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValueExplicit('A'.$br, $k->nik, PHPExcel_Cell_DataType::TYPE_STRING)
			            ->setCellValue('B'.$br, $k->nama)
			            ->setCellValue('C'.$br, $k->email);
			            
			            $br++;       	
			}            
			
			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Data Karyawan');
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Data Karyawan.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;

        }else{
            //redirect('Excel');
        }
        
    }
}