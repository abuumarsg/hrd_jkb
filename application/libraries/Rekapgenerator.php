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

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class Rekapgenerator{

    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
        // $this->dir=$_SERVER['DOCUMENT_ROOT'].'/jkb/application/document/temp/';
        $this->dir=APPPATH.'document/temp/';
        $this->excel=new Spreadsheet();
        $this->word=new PhpWord();
        $this->date=$this->CI->otherfunctions->getDateNow();
		$this->max_range=$this->CI->otherfunctions->poin_max_range();
		$this->max_month=$this->CI->otherfunctions->column_value_max_range();
    }

    public function index()
    {
        $this->redirect('not_found');
    }

//###################################################### BLOCK OF EXPORT BEGIN ######################################################//
//=========================================== REKAP PROPERTIES ===========================================//  
    public function excelProperties($p)
    {
        $this->excel->getProperties()
        ->setCreator("HSOFT SYSTEM")
        ->setLastModifiedBy("HSOFT SYSTEM")
        ->setTitle($p['title'])
        ->setSubject($p['subject'])
        ->setDescription($p['description'])
        ->setKeywords($p['keywords'])
        ->setCategory($p['category']);       
    }    
    public function excelFooter($p)
    {
        $writer = new Xlsx($this->excel);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$p['title'].'.xls"');
        header ('Expires: '.date('D, d M Y H:i:s',strtotime($this->date)).' GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: no-cache, must-revalidate,max-age=0,post-check=0, pre-check=0', false);
        header ('Pragma: no-cache');
        $writer->save('php://output');
        exit;
    }
    public function autoSizeColumn($sheet = 0,$letter)
    {
        $this->excel->getActiveSheet($sheet)->getColumnDimension($letter)->setAutoSize(true);
    }
//=========================================== END REKAP PROPERTIES ===========================================//


    public function genDoc()
    {
        echo '<h1>Nothing</h1>';
    }
    public function genExcel($data)
	{
		$this->excelProperties($data['properties']);
		$sheets=(count($data['data']) > 1)?true:false;
		foreach ($data['data'] as $sheet => $value) {
			if ($sheets) {
                $this->excel->createSheet($sheet);   
            }    
            //Batasi Kolom
			if (isset($value['only_show_column'])) {
				if (is_array($value['only_show_column']) && $value['only_show_column']) {
					$header_define=[];
					foreach ($value['only_show_column'] as $show_column) {
						if (isset($value['head']['data_head'][$show_column])) {
							$header_define[]=$value['head']['data_head'][$show_column];
						}
					}
					$value['head']['data_head']=$header_define;
					if (isset($value['body']['data_body']) && isset($value['body']['row_body'])) {
						if ($value['body']['data_body']) {
							$body_define=[];
							$begin_row=$value['body']['row_body'];
							foreach ($value['body']['data_body'] as $key_body => $val_body) {
								$body_define_detail=[];
								foreach ($value['only_show_column'] as $show_column) {
									if (isset($val_body[$show_column])) {
										$body_define_detail[]=$val_body[$show_column];
									}							
								}
								$body_define[$begin_row]=$body_define_detail;
								$begin_row++;								
							}
							$value['body']['data_body']=$body_define;
						}
					}
				}
			}
			$range=$this->CI->otherfunctions->getRangeHuruf($value['range_huruf']);
			if (isset($value['head_merge'])) {
                if(isset($value['head_merge']['data_head_2'])){
                    $min_letter=min(array_keys($value['head_merge']['data_head_2']));
                    $max_letter=max(array_keys($value['head_merge']['data_head_2']));
                    $row_head=$value['head_merge']['row_head'];
                    $this->excel->getSheet($sheet)->getStyle($range[$min_letter].$row_head.':'.$range[$max_letter].$row_head)->applyFromArray(
                        [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ],
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                                'rotation' => 90,
                                'startColor' => [
                                    'argb' => 'FF0099FF',
                                ],
                                'endColor' => [
                                    'argb' => 'FFFFFFFF',
                                ],
                            ],
                        ]
                    );  
                    $this->excel->getSheet($sheet)->getStyle($range[$min_letter].($row_head+1).':'.$range[$max_letter].($row_head+1))->applyFromArray(
                        [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ],
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                                'rotation' => 90,
                                'startColor' => [
                                    'argb' => 'FF0099FF',
                                ],
                                'endColor' => [
                                    'argb' => 'FFFFFFFF',
                                ],
                            ],
                        ]
                    );
                    foreach ($value['head_merge']['data_head_1'] as $k_h_1 => $v_h_1) {
                        $this->autoSizeColumn($sheet,$range[$k_h_1]);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue(($range[$k_h_1].($row_head)), $v_h_1);
                        $this->excel->setActiveSheetIndex($sheet)->getStyle(($range[$k_h_1].($row_head)))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setWrapText(true);
                    }
                    foreach ($value['head_merge']['data_head_2'] as $k_h => $v_h) {
                        $this->autoSizeColumn($sheet,$range[$k_h]);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue(($range[$k_h].($row_head+1)), $v_h);
                        $this->excel->setActiveSheetIndex($sheet)->getStyle(($range[$k_h].($row_head+1)))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setWrapText(true);
                    }
                    if(isset($value['head_merge']['jumData']['1'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['1'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
									'startColor' => [
										'argb' => 'FFFFFF00',
									],
                                    // 'color' => array('rgb' => 'FFCCFF') => ungu
                                    // 'color' => array('rgb' => '39A724') => hijau
                                    // 'color' => array('rgb' => 'FFFF00') => kuning
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['2'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['2'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    // 'color' => array('rgb' => '39A724')
									'startColor' => [
										'argb' => 'FF9ef01a',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['3'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['3'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
									'startColor' => [
										'argb' => 'FFFFCCFF',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['4'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['4'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    // 'color' => array('rgb' => '80E5FE')
									'startColor' => [
										'argb' => 'FF00ffea',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['5'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['5'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
									'startColor' => [
										'argb' => 'FF80E5FE',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['6'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['6'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
									'startColor' => [
										'argb' => 'FF80E5FE',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['7'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['7'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    // 'color' => array('rgb' => '0099ff')
									'startColor' => [
										'argb' => 'FF80E5FE',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['8'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['8'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
									'startColor' => [
										'argb' => 'FFA9E4D7',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['9'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['9'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
									'startColor' => [
										'argb' => 'FF1DB9C3',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                    if(isset($value['head_merge']['jumData']['10'])){
                        $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['10'])->applyFromArray(
                            array(
                                'fill' => array(
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
									'startColor' => [
										'argb' => 'FFA9E4D7',
									],
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );  
                    }
                }else{
                    $min_letter=min(array_keys($value['head_merge']['data_head']));
                    $max_letter=max(array_keys($value['head_merge']['data_head']));
                    $row_head=$value['head_merge']['row_head'];
                    $this->excel->getSheet($sheet)->getStyle($range[$min_letter].$row_head.':'.$range[$max_letter].$row_head)->applyFromArray(
                        array(
                            'fill' => array(
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'argb' => 'FF80E5FE',
								],
                                // 'color' => array('rgb' => '0099ff')
                            ),
                            'font' => array(
                                'color' => array('rgb' => '000000')
                            )
                        )
                    );  
                    foreach ($value['head_merge']['data_head'] as $k_h => $v_h) {
                        $this->autoSizeColumn($sheet,$range[$k_h]);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue(($range[$k_h].$row_head), $v_h);
                        $this->excel->setActiveSheetIndex($sheet)->getStyle(($range[$k_h].$row_head))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setWrapText(true);
                    }
                }
                if (isset($value['head_merge']['max_merge'])) {
                    for ($i=1; $i <= $value['head_merge']['max_merge']; $i++) {
                        $this->excel->setActiveSheetIndex($sheet)->mergeCells($value['head_merge']['merge_'.$i]);
                    }
                }
                if (isset($value['head_merge']['abjadTop']) && $value['head_merge']['abjadTop'] == true) {
                    if(isset($value['head_merge']['data_head_2'])){
                        $min_letter=min(array_keys($value['head_merge']['data_head_2']));
                        $max_letter=max(array_keys($value['head_merge']['data_head_2']));
                        if(isset($value['head_merge']['row_head'])){
                            $row_head=$value['head_merge']['row_head']-1;
                        }else{
                            $row_head = 1;
                        }
                        $this->excel->getSheet($sheet)->getStyle($range[$min_letter].$row_head.':'.$range[$max_letter].($row_head+1))->applyFromArray(
                            array(
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );
                    }
                    if(isset($value['head_merge']['data_head'])){
                        $min_letter=min(array_keys($value['head_merge']['data_head']));
                        $max_letter=max(array_keys($value['head_merge']['data_head']));
                        if(isset($value['head_merge']['row_head'])){
                            $row_head=$value['head_merge']['row_head']-1;
                        }else{
                            $row_head = 1;
                        }
                        $this->excel->getSheet($sheet)->getStyle($range[$min_letter].$row_head.':'.$range[$max_letter].($row_head+1))->applyFromArray(
                            array(
                                'alignment' => array(
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    )
                                ),
                            )
                        );
                    }
                }
                if(isset($value['head_merge']['jumData']['1'])){
                    $this->excel->getSheet($sheet)->getStyle($value['head_merge']['jumData']['1'])->applyFromArray(
                        array(
                            'fill' => array(
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
								'startColor' => [
									'argb' => 'FFFFFF00',
								],
                                // 'color' => array('rgb' => 'FFCCFF') => ungu
                                // 'color' => array('rgb' => '39A724') => hijau
                                // 'color' => array('rgb' => 'FFFF00') => kuning
                            ),
                            'font' => array(
                                'color' => array('rgb' => '000000')
                            ),
                            'alignment' => array(
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                )
                            ),
                        )
                    );  
                }
			}
			if (isset($value['head'])) {
                $min_letter=min(array_keys($value['head']['data_head']));
                $max_letter=max(array_keys($value['head']['data_head']));
                $row_head=$value['head']['row_head'];
                $this->excel->getSheet($sheet)->getStyle($range[$min_letter].$row_head.':'.$range[$max_letter].$row_head)->applyFromArray(
                    array(
                        'fill' => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
							'startColor' => [
								'argb' => 'FF80E5FE',
							],
                            // 'color' => array('rgb' => '0099ff')
                        ),
                        'font' => array(
                            'color' => array('rgb' => '000000')
                        )
                    )
                );  
				foreach ($value['head']['data_head'] as $k_h => $v_h) {
					$this->autoSizeColumn($sheet,$range[$k_h]);
					$this->excel->setActiveSheetIndex($sheet)->setCellValue(($range[$k_h].$row_head), $v_h);
                    $this->excel->setActiveSheetIndex($sheet)->getStyle(($range[$k_h].$row_head))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setWrapText(true);
				}
			}
			if (isset($value['body'])) {
				foreach ($value['body']['data_body'] as $k_h => $v_h) {
					foreach ($v_h as $v_k => $v_v) {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValueExplicit(($range[$v_k].$k_h), $v_v, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $this->excel->setActiveSheetIndex($sheet)->getStyle(($range[$v_k].$k_h))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setWrapText(true);
						if ($v_v == '#') {
							$this->excel->setActiveSheetIndex($sheet)->getStyle($range[$v_k].$k_h.':'.$range[($v_k+1)].$k_h)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
						}
						
					}
				}
			}
			$this->excel->getSheet($sheet)->setTitle($value['sheet_title']); 
			$active_sheet=($sheets)?0:$sheet;
			$this->excel->setActiveSheetIndex($active_sheet);
        }
        $sheet_list=$this->excel->getSheetNames();
		foreach ($sheet_list as $name_sheet) {
			if ($name_sheet == 'Worksheet') {
				$this->excel->setActiveSheetIndexByName($name_sheet);
				$sheetIndex = $this->excel->getActiveSheetIndex();
				$this->excel->removeSheetByIndex($sheetIndex);
			}
		}
		$this->excelFooter($data['properties']);
	}
//###################################################### BLOCK OF EXPORT END ######################################################//
//---------------------------------------------------------------------------------------------------------------------------------//
//###################################################### BLOCK OF IMPORT BEGIN ######################################################//
    public function ruleImportFileExcel($filename)
    {
        $config['upload_path'] = $this->dir;
        $config['file_name'] = $filename;
        $config['allowed_types'] = 'xls|xlsx|csv|ods|ots';
        return $config;
    }

    public function importFileExcel($datax)
    {
        $rule=$this->ruleImportFileExcel($datax['properties']['data_post']);
        $this->CI->load->library('upload', $rule);
        $this->CI->upload->initialize($rule);
        if (!$this->CI->upload->do_upload($datax['properties']['post'])) {
            $data_return=$this->CI->messages->customFailure($this->CI->upload->display_errors()); 
            // var_dump($this->CI->upload->display_errors());
        }else{
            $file = $this->CI->upload->data();
            $file_db=$rule['upload_path'].$file['file_name'];
            try {
                $inputFileType =  \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_db);
                $objReader =  \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($file_db);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($file_db,PATHINFO_BASENAME).'": '.$e->getMessage());
            }
            $same_code=[];
            $all_data=[];
            foreach ($datax['data'] as $sheet_file => $value) {
            //import presensi solusion
                if(isset($value['tanggal_import'])){
                    $date_range=explode(' - ',$value['tanggal_import']);
                    $start_date=$this->CI->formatter->getDateFormatDb($date_range[0]);
                    $end_date=$this->CI->formatter->getDateFormatDb($date_range[1]);
                }
                // if(isset($value['bagian_import'])){
                //     $dataKar=$this->CI->model_karyawan->getKaryawanBagian($value['bagian_import']);
                // }
                if ($value['usage'] == 'presensi_one') {
                    $jadwal_kerja=[];
                    while ($start_date <= $end_date)
                    {
                        $d=date('d',strtotime($start_date));
                        $mo=date('m',strtotime($start_date));
                        $y=date('Y',strtotime($start_date));
                        $m=(($mo < 10) ? str_replace('0','',$mo) : $mo);
                        $data_jadwal=$this->CI->model_karyawan->getIdShiftNoKar($d,$m,$y);
                        if (isset($data_jadwal)) {
                            foreach ($data_jadwal as $d) {
                                if (!empty($d->shift)) {
                                    $jadwal_kerja[$d->id_finger][$start_date]=[
                                        'id_karyawan'   =>$d->id_karyawan,
                                        'kode_shift'    =>$d->shift,
                                        'nik'           =>$d->nik,
                                        's'             =>$d->jam_mulai,
                                        'e'             =>$d->jam_selesai,
                                        'jam_masuk'     =>null,
                                        'jam_keluar'    =>null
                                    ];
                                }
                            }
                        }
                        $start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));
                    }
                } 
//range huruf 
                $range=$this->CI->otherfunctions->getRangeHuruf($value['range_huruf']);
                $sheet = $objPHPExcel->getSheet($sheet_file);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                $data_excel=[];
                for ($row = $value['row']; $row <= $highestRow; $row++){ 
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
                // print_r($value['column']); 
                    foreach ($rowData as $r_d) {
                        if (!empty($r_d[1])) {
                            $data=[];
                            foreach ($value['column'] as $k_c => $v_c) {
                                $data[$v_c]=$r_d[$k_c];     
                            }
                            if(isset($value['column_properties'])){
                                $data=array_merge($data,$value['column_properties']);  
                            }
                            if (isset($value['usage'])) {
                                if ($value['usage'] == 'presensi_one') {  
                                    $id_finger = $data['id_finger'];
                                    if (isset($jadwal_kerja)) {
                                        if (isset($jadwal_kerja[$id_finger])) {
                                            if (strpos($data['tanggal_waktu'], '-') !== false) {
                                                $dateNtime = explode(' ', $data['tanggal_waktu']);
                                                $month=$this->CI->formatter->formatDateImportPresensiString($dateNtime[0]);
                                                $tgl = $month.' '.$dateNtime[1];
                                                $date_time = explode(' ',$tgl);
                                            }else{
                                                $date_time=explode(' ',$data['tanggal_waktu']);
                                            }
                                            // print_r($data['tanggal_waktu']);
                                            if (isset($date_time[1]) && isset($date_time[0])) {                             
                                                $time=$this->CI->formatter->getTimeDb($date_time[1]);
                                                $date=$this->CI->formatter->formatDatePresensiForDb($date_time[0]);
                                                $data_excel[$id_finger][$date][$row]=$time;
                                            }
                                        }
                                    }
                                }elseif ($value['usage'] == 'presensi_one_log') {
                                    if(!empty($data['id_finger'])){
                                        $emp = $this->CI->model_karyawan->getEmployeeFinger($data['id_finger']);
                                        // if(!empty($emp['id_karyawan'])){
                                        if(!empty($data['tanggal_waktu'])){
                                            $date_time=$this->CI->otherfunctions->getDataExplode($data['tanggal_waktu'],' ','all');
                                            if (isset($date_time[1]) && isset($date_time[0])) {
                                                if(isset($date_time[2])){
                                                    if($date_time[2] == 'PM'){
                                                        $wkt = $this->CI->otherfunctions->getDataExplode($date_time[1],':','all');
                                                        $jam = ($wkt[0]+12).':'.$wkt[1].':'.$wkt[2];
                                                        $time=$this->CI->formatter->getTimeDb($jam);
                                                    }else{
                                                        $time=$this->CI->formatter->getTimeDb($date_time[1]);
                                                    }
                                                }else{
                                                    if (strpos($date_time[1], '.') !== false) {
                                                        $wkt = $this->CI->otherfunctions->getDataExplode($date_time[1],'.','all');
                                                        $jam = ($wkt[0]).':'.$wkt[1].':'.$wkt[2];
                                                        $time=$this->CI->formatter->getTimeDb($jam);
                                                    }else{
                                                        // $time = PHPExcel_Style_NumberFormat::toFormattedString($date_time[1], 'hh:i:ss');
                                                        $time=$this->CI->formatter->getTimeDb($date_time[1]);
                                                    }
                                                    // $time=$this->CI->formatter->getTimeDb($date_time[1]);
                                                }
                                                // $tanggal  = strtotime(PHPExcel_Style_NumberFormat::toFormattedString($date_time[0],'YYYY-MM-DD'));
                                                // $date = date('Y-m-d',$tanggal);
                                                $date=$this->CI->formatter->formatDatePresensiForDb($date_time[0]);
                                            }
                                        }
                                        $data['id_karyawan'] = (isset($emp['id_karyawan'])) ? ((!empty($emp['id_karyawan'])) ? $emp['id_karyawan'] : null) : null;
                                        $data['tanggal'] = (isset($date)) ? ((!empty($date)) ? $date : null) : null;
                                        $data['jam'] = (isset($time)) ? ((!empty($time)) ? $time : null) : null;
                                        // }
                                        // unset($data['id_finger']);
                                        unset($data['tanggal_waktu']);
                                    }
                                }elseif ($value['usage'] == 'presensi_two') {
                                    if(!empty($data['id_finger'])){
                                        $emp = $this->CI->model_karyawan->getEmployeeFinger($data['id_finger']);
                                        if(!empty($emp['id_karyawan'])){
                                            $cekjadwal = $this->CI->model_karyawan->cekJadwalKerjaId($emp['id_karyawan'],$this->CI->formatter->getDateFormatDb($data['tanggal']));
                                            $data['id_karyawan'] = (isset($emp['id_karyawan'])) ? ((!empty($emp['id_karyawan'])) ? $emp['id_karyawan'] : null) : null;
                                            $data['tanggal'] = (isset($data['tanggal'])) ? ((!empty($data['tanggal'])) ? $this->CI->formatter->getDateFormatDb($data['tanggal']) : null) : null;
                                            $data['jam_mulai'] = (isset($data['jam_mulai'])) ? ((!empty($data['jam_mulai'])) ? $data['jam_mulai'] : null) : null;
                                            $data['jam_selesai'] = (isset($data['jam_selesai'])) ? ((!empty($data['jam_selesai'])) ? $data['jam_selesai'] : null) : null;
                                            $data['kode_shift'] = (isset($cekjadwal['kode_shift'])) ? ((!empty($cekjadwal['kode_shift'])) ? $cekjadwal['kode_shift'] : null) : null;
                                        }
                                    unset($data['id_finger']);
                                    }
                                }elseif ($value['usage'] == 'import_master_grade') {
                                    if(!empty($data['kode_induk_grade'])){
                                        $data['kode_grade']         = ($data['kode_grade'] != null)?$data['kode_grade']:$this->CI->codegenerator->kodeGrade();
                                        $data['kode_induk_grade']   = (isset($data['kode_induk_grade'])?$data['kode_induk_grade']:null);
                                        $data['nama']               = (isset($data['nama'])?$data['nama']:null);
                                        $data['gapok']              = (isset($data['gapok'])?$data['gapok']:null);
                                        $data['kode_dokumen']       = (isset($data['kode_dokumen'])?$data['kode_dokumen']:null);
                                        $data['kode_loker']         = (isset($data['kode_loker'])?$data['kode_loker']:null);
                                        // $data['tahun']              = (isset($data['tahun'])?$data['tahun']:null);
                                    }
                                }elseif ($value['usage'] == 'import_pd_detail_kategori') {
                                    if(!empty($value['other']['kode_tunjangan'])){
                                        $data['kode']               = $this->CI->codegenerator->kodeDKTPD();
                                        $data['grade']              = (isset($data['grade'])?$data['grade']:null);
                                        // $data['kode_kategori']      = (isset($data['kode_kategori'])?$data['kode_kategori']:null);
                                        $data['nominal']            = (isset($data['nominal'])?$data['nominal']:null);
                                        $data['nominal_min']            = (isset($data['nominal_min'])?$data['nominal_min']:null);
                                        $data['nominal_non']            = (isset($data['nominal_non'])?$data['nominal_non']:null);
                                        $data['keterangan']         = (isset($data['keterangan'])?$data['keterangan']:null);
                                        $data['tempat']             = (isset($data['tempat'])?$data['tempat']:null);
                                     	$data['kode_kategori']      = (isset($value['other']['kode_tunjangan'])) ? $value['other']['kode_tunjangan']:null;
                                    }
                                }elseif ($value['usage'] == 'import_ritasi') {
                                    if(!empty($data['nik'])){
                                        // $emp = $this->CI->model_karyawan->getEmployeeAllActive();
                                        // foreach ($emp as $e) {
                                        // 	if($e->nik == $data['nik']){
                                        // 		$data['id_karyawan'] = $e->id_karyawan;
                                        // 	}
                                        // }
                                        $data['id_karyawan'] = $this->CI->model_karyawan->getEmployeeNik($data['nik'])['id_karyawan'];
                                        $data['id_karyawan'] = (empty($data['id_karyawan'])) ? '' : $data['id_karyawan'];
                                     	$data['nik'] = (empty($data['nik'])) ? '' : $data['nik'];
                                     	$data['nama'] = (empty($data['nama'])) ? '' : $data['nama'];
                                     	$data['rit'] = (empty($data['rit'])) ? '' : $data['rit'];
                                     	$data['nominal'] = (empty($data['nominal'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['nominal']);
                                     	$data['rit_non_ppn'] = (empty($data['rit_non_ppn'])) ? '' : $data['rit_non_ppn'];
                                     	$data['nominal_non_ppn'] = (empty($data['nominal_non_ppn'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['nominal_non_ppn']);
                                     	$data['kode_periode_penggajian'] = (empty($value['other']['kode_periode_penggajian'])) ? '' : $value['other']['kode_periode_penggajian'];
                                     	$data['minggu'] = (empty($value['other']['minggu'])) ? '' : $value['other']['minggu'];
                                     	$data['keterangan'] = (empty($data['keterangan'])) ? '' : $data['keterangan'];
                                    }
                                }elseif ($value['usage'] == 'import_bpjs_karyawan') {
                                    if(!empty($data['nik'])){
                                    	$emp = $this->CI->model_karyawan->getEmployeeAllActive();
                                    	foreach ($emp as $e) {
                                    		if($e->nik == $data['nik']){
                                    			$data['id_karyawan'] = $e->id_karyawan;
                                    		}
                                    	}
                                    	$data['jht'] = (empty($data['jht'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['jht']);
                                    	$data['jkk'] = (empty($data['jkk'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['jkk']);
                                    	$data['jkm'] = (empty($data['jkm'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['jkm']);
                                    	$data['jpns'] = (empty($data['jpns'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['jpns']);
                                    	$data['jkes'] = (empty($data['jkes'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['jkes']);
                                    	$data['kode_k_bpjs'] = $this->CI->codegenerator->kodebpjk();
                                    	
                                    }
                                }elseif ($value['usage'] == 'importGajiNonMatrix') {
                                    if(!empty($data['nik'])){
                                        $data['nik']        = (isset($data['nik'])?$data['nik']:null);
                                        $data['gaji']       = (isset($data['gaji'])?$data['gaji']:null);
                                    }
                                }elseif ($value['usage'] == 'importGajiBpjs') {
                                    if(!empty($data['nik'])){
                                        $data['nik']        = (isset($data['nik'])?$data['nik']:null);
                                        $data['gaji_bpjs']       = (isset($data['gaji_bpjs'])?$data['gaji_bpjs']:null);
                                        $data['gaji_bpjs_tk']       = (isset($data['gaji_bpjs_tk'])?$data['gaji_bpjs_tk']:null);
                                    }
                                }elseif ($value['usage'] == 'wfh') {
                                    if(!empty($data['nik'])){
                                        $data['hari_kerja_wfh'] = (isset($data['hari_kerja_wfh'])?$data['hari_kerja_wfh']:null);
                                        $data['hari_kerja_non_wfh'] = (isset($data['hari_kerja_non_wfh'])?$data['hari_kerja_non_wfh']:null);
                                        $data['wfh'] = (isset($data['wfh'])?$data['wfh']:null);
                                    }
                                }elseif ($value['usage'] == 'pph_kode_akun') {
                                    // echo '<pre>';
                                    $tanggal  = strtotime(PHPExcel_Style_NumberFormat::toFormattedString($data['tanggal'],'YYYY-MM-DD'));
                                    $data['tanggal'] = date('Y-m-d',$tanggal);
                                    // print_r($data['tanggal']);
                                    if(!empty($data['nik'])){
                                    	$emp = $this->CI->model_karyawan->getEmployeeNik($data['nik']);
                                        $data['id_karyawan'] = $emp['id_karyawan'];
                                    	$data['tanggal'] = (empty($data['tanggal'])) ? '' : $data['tanggal'];
                                    	$data['no_bukti'] = (empty($data['no_bukti'])) ? '' : $data['no_bukti'];
                                    	$data['kode_akun'] = (empty($data['kode_akun'])) ? '' : $data['kode_akun'];
                                    	$data['catatan'] = (empty($data['catatan'])) ? '' : $data['catatan'];
                                    	$data['nominal'] = (empty($data['nominal'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['nominal']);
                                    	$data['nama_proyek'] = (empty($data['nama_proyek'])) ? '' : $data['nama_proyek'];
                                        $data['no_proyek'] = (empty($data['no_proyek'])) ? '' : $data['no_proyek'];
                                        $mKodeAkun = $this->CI->model_master->getListKodeAkun(['a.kode_akun'=>$data['kode_akun']]);
                                        if(empty($mKodeAkun)){
                                            $data_m=[
                                                'kode_akun'=>strtoupper($data['kode_akun']),
                                                'nama'=>$data['nama_akun'],
                                            ];
                                            $data_m=array_merge($data_m,$value['column_properties']);
                                            $this->CI->model_global->insertQueryCC($data_m,'master_pd_kode_akun',$this->CI->model_master->checkCodeAkun($data['kode_akun']));
                                        }
                                        unset($data['nama_akun']);
                                        unset($data['nik']);
                                    }
                                }elseif ($value['usage'] == 'import_pendukung_lain') {
                                    if(!empty($data['nik'])){
                                        $emp = $this->CI->model_karyawan->getEmployeeAllActive();
                                        foreach ($emp as $e) {
                                        	if($e->nik == $data['nik']){
                                        		$data['id_karyawan'] = $e->id_karyawan;
                                        	}
                                        }
                                        $data['kode_pen_lain'] = $this->CI->codegenerator->kodePendukungLain();
                                        $data['id_karyawan'] = (empty($data['id_karyawan'])) ? '' : $data['id_karyawan'];
                                     	// $data['nik'] = (empty($data['nik'])) ? '' : $data['nik'];
                                     	$data['nama'] = (empty($data['nama'])) ? '' : $data['nama'];
                                     	$data['nominal'] = (empty($data['nominal'])) ? '' : $this->CI->formatter->getFormatMoneyDb($data['nominal']);
                                     	$data['sifat'] = (empty($data['sifat'])) ? '' : $data['sifat'];
                                     	$data['hallo'] = (empty($data['hallo'])) ? '' : $data['hallo'];
                                     	$data['keterangan'] = (empty($data['keterangan'])) ? '' : $data['keterangan'];
                                     	$data['kode_periode'] = (empty($value['other']['kode_periode'])) ? '' : $value['other']['kode_periode'];
                                        unset($data['nik']);
                                    }
                                }elseif ($value['usage'] == 'data_jadwal_kerja') {
                                    if(!empty($data['nik'])){
                                    	$emp = $this->CI->model_karyawan->getEmployeeNik($data['nik']);
                                        $data['id_karyawan'] = $emp['id_karyawan'];
                                        $bulan = 0;
                                        if(!empty($data['bulan'])){
                                            $bulan=($data['bulan'] < 10) ? str_replace('0', '', $data['bulan']) : $data['bulan'];
                                        }
                                    	$data['bulan'] = (empty($bulan)) ? '' : $bulan;
                                    	$data['tahun'] = (empty($data['tahun'])) ? '' : $data['tahun'];
                                    	$data['tgl_1'] = (empty($data['tgl_1'])) ? '' : $data['tgl_1'];
                                    	$data['tgl_2'] = (empty($data['tgl_2'])) ? '' : $data['tgl_2'];
                                    	$data['tgl_3'] = (empty($data['tgl_3'])) ? '' : $data['tgl_3'];
                                    	$data['tgl_4'] = (empty($data['tgl_4'])) ? '' : $data['tgl_4'];
                                    	$data['tgl_5'] = (empty($data['tgl_5'])) ? '' : $data['tgl_5'];
                                    	$data['tgl_6'] = (empty($data['tgl_6'])) ? '' : $data['tgl_6'];
                                    	$data['tgl_7'] = (empty($data['tgl_7'])) ? '' : $data['tgl_7'];
                                    	$data['tgl_8'] = (empty($data['tgl_8'])) ? '' : $data['tgl_8'];
                                    	$data['tgl_9'] = (empty($data['tgl_9'])) ? '' : $data['tgl_9'];
                                    	$data['tgl_10'] = (empty($data['tgl_10'])) ? '' : $data['tgl_10'];
                                    	$data['tgl_11'] = (empty($data['tgl_11'])) ? '' : $data['tgl_11'];
                                    	$data['tgl_12'] = (empty($data['tgl_12'])) ? '' : $data['tgl_12'];
                                    	$data['tgl_13'] = (empty($data['tgl_13'])) ? '' : $data['tgl_13'];
                                    	$data['tgl_14'] = (empty($data['tgl_14'])) ? '' : $data['tgl_14'];
                                    	$data['tgl_15'] = (empty($data['tgl_15'])) ? '' : $data['tgl_15'];
                                    	$data['tgl_16'] = (empty($data['tgl_16'])) ? '' : $data['tgl_16'];
                                    	$data['tgl_17'] = (empty($data['tgl_17'])) ? '' : $data['tgl_17'];
                                    	$data['tgl_18'] = (empty($data['tgl_18'])) ? '' : $data['tgl_18'];
                                    	$data['tgl_19'] = (empty($data['tgl_19'])) ? '' : $data['tgl_19'];
                                    	$data['tgl_20'] = (empty($data['tgl_20'])) ? '' : $data['tgl_20'];
                                    	$data['tgl_21'] = (empty($data['tgl_21'])) ? '' : $data['tgl_21'];
                                    	$data['tgl_22'] = (empty($data['tgl_22'])) ? '' : $data['tgl_22'];
                                    	$data['tgl_23'] = (empty($data['tgl_23'])) ? '' : $data['tgl_23'];
                                    	$data['tgl_24'] = (empty($data['tgl_24'])) ? '' : $data['tgl_24'];
                                    	$data['tgl_25'] = (empty($data['tgl_25'])) ? '' : $data['tgl_25'];
                                    	$data['tgl_26'] = (empty($data['tgl_26'])) ? '' : $data['tgl_26'];
                                    	$data['tgl_27'] = (empty($data['tgl_27'])) ? '' : $data['tgl_27'];
                                    	$data['tgl_28'] = (empty($data['tgl_28'])) ? '' : $data['tgl_28'];
                                    	$data['tgl_29'] = (empty($data['tgl_29'])) ? '' : $data['tgl_29'];
                                    	$data['tgl_30'] = (empty($data['tgl_30'])) ? '' : $data['tgl_30'];
                                    	$data['tgl_31'] = (empty($data['tgl_31'])) ? '' : $data['tgl_31'];
                                        unset($data['nik']);
                                    }
                                }elseif ($value['usage'] == 'presensi_pa') {
									$ijin=(isset($data['ijin'])) ? ((!empty($data['ijin'])) ? $data['ijin'] : 0) : 0;
									$mangkir=(isset($data['mangkir'])) ? ((!empty($data['mangkir'])) ? $data['mangkir'] : 0) : 0;
									$telat=(isset($data['telat'])) ? ((!empty($data['telat'])) ? $data['telat'] : 0) : 0;
									$sp=(isset($data['sp'])) ? ((!empty($data['sp'])) ? $data['sp'] : 0) : 0;
									$data['sp'] = (isset($data['sp'])) ? ((!empty($data['sp'])) ? $data['sp'] : 0) : 0;
									$data['ijin'] = (isset($data['ijin'])) ? ((!empty($data['ijin'])) ? $data['ijin'] : 0) : 0;
									$data['mangkir'] = (isset($data['mangkir'])) ? ((!empty($data['mangkir'])) ? $data['mangkir'] : 0) : 0;
                                    $data['telat'] = (isset($data['telat'])) ? ((!empty($data['telat'])) ? $data['telat'] : 0) : 0;
                                    $data['id_karyawan'] = $this->model_karyawan->getEmployeeNik($data['nik'])['id_karyawan'];
                                }elseif ($value['usage'] == 'import_task'){
									$emp = $this->CI->model_karyawan->getEmployeeNik($data['nik']);
									$id_karyawan = $emp['id_karyawan'];
									$tabel = $value['table'];
									$id_part = $value['other']['id_admin'];
									$max_for=$this->max_month;
									$getidtask = $this->CI->model_agenda->openTableAgendaIdCode($value['table'],$id_karyawan,$data['kode_kpi'],'kode_kpi');
									$get_data = $this->CI->otherfunctions->convertResultToRowArray(($this->CI->model_agenda->openTableAgendaId($value['table'],$getidtask['id_task'])));
									if (isset($get_data)) {
										$target = ($get_data['target'])?$get_data['target']:0;
										$poin_max = ((is_numeric($get_data['satuan_1']))?$get_data['satuan_1']:$get_data['poin_1']);
										$lebih_max = ($get_data['lebih_max'])?$get_data['lebih_max']:false;
										$sifat = ($get_data['sifat'])?$get_data['sifat']:'MAX';
										$max_target = $get_data['max'];
										for ($bulan=1; $bulan <= $this->max_month ; $bulan++) {											
											$pn_key = 'pn'.$bulan;
											// $data[$pn_key]=$data[$pn_key];
											$pn[$bulan] = $this->CI->exam->getNilaiPackRemove($data[$pn_key],$id_part,$get_data[$pn_key]);
											$data['pn'.$bulan]=$pn[$bulan];
											$data['nr'.$bulan]=$this->CI->exam->getNilaiSum($pn[$bulan]);
											if ($sifat == 'MIN') {
												$data['na'.$bulan]=$this->CI->rumus->rumus_prosentase_negatif($data['nr'.$bulan],$target,$max_target);
											}else{
												$data['na'.$bulan]=$this->CI->rumus->rumus_prosentase($data['nr'.$bulan],$target);
											}								
											if (!$lebih_max) {
												if ($data['na'.$bulan] > $poin_max) {
													$data['na'.$bulan]=$poin_max;
												}
											}
										}
									}
									unset($data['nik']);
								}elseif ($value['usage'] == 'import_detail_konsep_kpi'){
									$data['target'] = (!empty($data['target'])) ? $data['target'] : '0';
									$data['bobot'] = (!empty($data['bobot'])) ? $data['bobot'] : '0';
									$getid = $this->CI->model_agenda->openTableAgendaIdCode($value['table'],$data['nik'],$data['kode_kpi'],'kode_kpi','emp.nik');
                                    $data['id_karyawan'] = $getid['id_karyawan'];
                                }elseif($value['usage'] == 'aspek_sikap'){
                                    $tb = $this->CI->model_agenda->openTableAgendaId($value['table'],$data['id_task'],true);
                                    $partisipan = $this->CI->exam->getPartisipantKode($tb['partisipan']);
                                    $data['nilai'] = ($data['nilai'] != 3)?$data['nilai']:null;
                                    foreach ($partisipan as $k_par => $v_par) {
                                        if($k_par == $value['id_admin']){
                                            if(!empty($data['keterangan'])){
                                                if ($v_par == "ATS") {
                                                    $ket_a=$this->CI->exam->addEditValueWithIdDb($k_par,$data['keterangan'],$tb['keterangan_atas']);
                                                    $kdata=['keterangan_atas'=>$ket_a];
                                                }elseif ($v_par == "BWH") {
                                                    $ket_b=$this->CI->exam->addEditValueWithIdDb($k_par,$data['keterangan'],$tb['keterangan_bawah']);
                                                    $kdata=['keterangan_bawah'=>$ket_b];
                                                }elseif ($v_par == "RKN") {
                                                    $ket_r=$this->CI->exam->addEditValueWithIdDb($k_par,$data['keterangan'],$tb['keterangan_rekan']);
                                                    $kdata=['keterangan_rekan'=>$ket_r];
                                                }else {
                                                    $ket_d=$this->CI->exam->addEditValueWithIdDb($k_par,$data['keterangan'],$tb['keterangan_diri']);
                                                    $kdata['keterangan_diri']=$ket_d;
                                                }
                                            $this->CI->model_global->updateQueryNoMsg($kdata,$value['table'],['id_task'=>$data['id_task']]);
                                            }
                                            if ($v_par == "ATS") {
                                                $n_a=$this->CI->exam->addEditValueWithIdDb($k_par,$data['nilai'],$tb['nilai_atas']);
                                                $r_a=$this->CI->exam->getNilaiAverage($n_a);
                                                if(!empty($tb['sub_bobot_ats'])){
                                                    $sb_atas=$this->CI->exam->getPartisipantId($tb['sub_bobot_ats']);
                                                    $n_k=[];
                                                    $l_nilai=$this->CI->otherfunctions->getParseVar($n_a);
                                                    foreach ($l_nilai as $k_l => $v_l) {
                                                        $n_k[$k_l]=$v_l*((isset($sb_atas[$k_l]))?$this->CI->exam->hitungBobot($sb_atas[$k_l]):0);
                                                    }
                                                    $r_a= array_sum($n_k);
                                                }
                                                $data['nilai_atas']=$n_a;
                                                $data['rata_atas']=$r_a;
                                                $data['na_atas']=$r_a*($this->CI->exam->hitungBobot($tb['bobot_ats']));
                                                if (!isset($data['keterangan'])){
                                                    echo $this->CI->exam->deleteValueWithIdDb($k_par,$tb['keterangan_atas']);
                                                    $data['keterangan_atas']=$this->CI->exam->deleteValueWithIdDb($k_par,$tb['keterangan_atas']);
                                                }
                                            }elseif ($v_par == "BWH") {
                                                $n_b=$this->CI->exam->addEditValueWithIdDb($k_par,$data['nilai'],$tb['nilai_bawah']);
                                                $r_b=$this->CI->exam->getNilaiAverage($n_b);
                                                $data['nilai_bawah']=$n_b;
                                                $data['rata_bawah']=$r_b;
                                                $data['na_bawah']=$r_b*($this->CI->exam->hitungBobot($tb['bobot_bwh']));
                                                if (!isset($data['keterangan'])){
                                                    $data['keterangan_bawah']=$this->CI->exam->deleteValueWithIdDb($k_par,$tb['keterangan_bawah']);
                                                }
                                            }elseif ($v_par == "RKN") {
                                                $n_r=$this->CI->exam->addEditValueWithIdDb($k_par,$data['nilai'],$tb['nilai_rekan']);
                                                $r_r=$this->CI->exam->getNilaiAverage($n_r);
                                                $data['nilai_rekan']=$n_r;
                                                $data['rata_rekan']=$r_r;
                                                $data['na_rekan']=$r_r*($this->CI->exam->hitungBobot($tb['bobot_rkn']));
                                                if (!isset($data['keterangan'])){
                                                    $data['keterangan_rekan']=$this->CI->exam->deleteValueWithIdDb($k_par,$tb['keterangan_rekan']);
                                                }
                                            }else {
                                                $data['nilai_diri']=$data['nilai'];
                                            }
                                            // $this->CI->model_global->updateQuery($data,$value['table'],['id_task'=>$data['id_task']]);
                                            // $dt1 = $this->CI->model_agenda->openTableAgendaId($value['table'],$data['id_task'],true);
                                            // $this->model_global->updateQuery(['na'=>($dt1['na_atas']+$dt1['na_bawah']+$dt1['na_rekan'])],$tb,['id_task'=>$data['id_task']]);
                                        }
                                    }
                                }
                            }
                            // print_r($data);
                // ==== INSERT ke DATABASE ====
                            if (isset($value['usage'])) {
                                if ($value['usage'] == 'presensi_one_log') {
                                    // if (!empty($data['id_karyawan']) && !empty($data['tanggal'])) {
                                    $where = [  'id_karyawan'=>$data['id_karyawan'],
                                                'id_finger'=>$data['id_finger'],
                                                'tanggal'=>$data['tanggal'],
                                                'jam'=>$data['jam'],
                                            ];
                                    $cekTemp = $this->CI->model_karyawan->getTemporariWhere($where);
                                    if(empty($cekTemp)){
                                        $this->CI->model_global->insertQueryNoMsg($data,$value['table']);
                                    }else{
                                        $this->CI->messages->customGood('Data Sudah Ada Di Database.');
                                    }
                                    // }
                                }elseif($value['usage'] == 'presensi_two'){
                                    if (!empty($data['id_karyawan']) && !empty($data['tanggal']) && !empty($data['kode_shift'])) {
                                        $cek=$this->CI->model_karyawan->checkPresensiDate($data['id_karyawan'],$data['tanggal']);
                                        if (!$cek) {
                                            $this->CI->model_global->insertQueryNoMsg($data,$value['table']);  
                                        }else{
                                            $same_code[]=$data[$value['column_code']];
                                        }                                    
                                    }
                                }elseif ($value['usage'] == 'import_master_grade') {
                                    if(!empty($data['kode_induk_grade'])){
                                        $cek=$this->CI->model_master->checkDataGrade(['kode_grade'=>$data['kode_grade'],'kode_loker'=>$data['kode_loker']]);
                                        if(!$cek){
                                            $this->CI->model_global->insertQueryNoMsg($data,$value['table']);
                                        }else{
                                            $this->CI->model_global->updateQueryNoMsg($data,$value['table'],['kode_grade'=>$data['kode_grade']]); 
                                            // $same_code[]=$data[$value['column_code']];
                                        }
                                    }
                                }elseif ($value['usage'] == 'import_pd_detail_kategori') {
                                    if(!empty($data['kode_kategori'])){
                                        $cek=$this->CI->model_master->checkDataGradePerDin(['grade'=>$data['grade'],'kode_kategori'=>$data['kode_kategori']]);
                                        if(!$cek){
                                            $this->CI->model_global->insertQueryNoMsg($data,$value['table']);
                                        }else{
                                            $this->CI->model_global->updateQueryNoMsg($data,$value['table'],['grade'=>$data['grade'],'kode_kategori'=>$data['kode_kategori']]); 
                                            // $same_code[]=$data[$value['column_code']];
                                        }
                                    }
                                }elseif ($value['usage'] == 'import_ritasi') {
                                    $where = ['a.nik'=>$data['nik'],'a.kode_periode_penggajian'=>$data['kode_periode_penggajian'],'a.minggu'=>$data['minggu']];
                                    // $wherex = ['nik'=>$data['nik'],'kode_periode_penggajian'=>$data['kode_periode_penggajian'],'minggu'=>$data['minggu']];
                                    // $where = ['a.nik'=>$data['nik'],'a.kode_periode_penggajian'=>$data['kode_periode_penggajian'],'a.minggu'=>$data['minggu'],'a.validasi'=>'1'];
                                    $wherex = ['nik'=>$data['nik'],'kode_periode_penggajian'=>$data['kode_periode_penggajian'],'minggu'=>$data['minggu'],'validasi !='=>'1'];
                                    $cek_data = $this->CI->model_master->getListDataRitasi($where,'active',true);
                                    if(empty($cek_data)){
                                        $this->CI->model_global->insertQueryNoMsg($data,$value['table']);
                                    }else{
                                        $this->CI->model_global->updateQueryNoMsgCallback($data,$value['table'],$wherex);
                                        $same_code[]=$data[$value['column_code']];
                                    }
                                    // $cek_data = $this->CI->model_master->getListDataRitasi(['a.nik'=>$data['nik'],'a.kode_periode_penggajian'=>$data['kode_periode_penggajian']],'active',true);
                                    // if(empty($cek_data)){
                                        // $this->CI->model_global->insertQueryNoMsg($data,$value['table']);
                                    // }else{
                                    //     $data['rit']                = (!empty($data['rit'])?$data['rit']:0);
                                    //     $data['nominal']            = (!empty($data['nominal'])?$data['nominal']:0);
                                    //     $data['rit_non_ppn']        = (!empty($data['rit_non_ppn'])?$data['rit_non_ppn']:0);
                                    //     $data['nominal_non_ppn']    = (!empty($data['nominal_non_ppn'])?$data['nominal_non_ppn']:0);
                                    //     $cek_data['rit']            = (!empty($cek_data['rit'])?$cek_data['rit']:0);
                                    //     $cek_data['nominal']        = (!empty($cek_data['nominal'])?$cek_data['nominal']:0);
                                    //     $cek_data['rit_non_ppn']    = (!empty($cek_data['rit_non_ppn'])?$cek_data['rit_non_ppn']:0);
                                    //     $cek_data['nominal_non_ppn']= (!empty($cek_data['nominal_non_ppn'])?$cek_data['nominal_non_ppn']:0);
                                    //     $dataq['kode_periode_penggajian'] = $data['kode_periode_penggajian'];
                                    //     $dataq['rit']               = $data['rit']+$cek_data['rit'];
                                    //     $dataq['nominal']           = $data['nominal']+$cek_data['nominal'];
                                    //     $dataq['rit_non_ppn']       = $data['rit_non_ppn']+$cek_data['rit_non_ppn'];
                                    //     $dataq['nominal_non_ppn']   = $data['nominal_non_ppn']+$cek_data['nominal_non_ppn'];
                                    // 	$this->CI->model_global->updateQuery($dataq,$value['table'],['nik'=>$data['nik'],'kode_periode_penggajian'=>$data['kode_periode_penggajian']]);
                                    // }
                                }elseif ($value['usage'] == 'import_pendukung_lain') {
                                    $cek_data = $this->CI->model_payroll->getListDataPendukungLain(['a.id_karyawan'=>$data['id_karyawan'],'a.kode_periode'=>$data['kode_periode']]);
                                    if(empty($cek_data)){
                                        $this->CI->model_global->insertQueryNoMsg($data,$value['table']);
                                    }else{
                                    	$this->CI->model_global->updateQuery($data,$value['table'],['id_karyawan'=>$data['id_karyawan'],'kode_periode'=>$data['kode_periode']]);
                                    }
                                }elseif ($value['usage'] == 'import_bpjs_karyawan') {
		                            unset($data['nik']);
                                    if(in_array('all',$value['bulan'])){
                                        $bulan = $this->CI->formatter->getMonth();
                                        foreach ($bulan as $key => $val) {
                                            $data['bulan']=$key;
                                            $data['tahun']=$value['tahun'];
                                            $cek_data = $this->CI->model_payroll->getListBpjsEmp(['a.id_karyawan'=>$data['id_karyawan'],'a.bulan'=>$key,'a.tahun'=>$value['tahun']]);
	                                        if(empty($cek_data)){
                                                $data=array_merge($data,$this->CI->model_global->getCreateProperties($value['admin']));
                                                $this->CI->model_global->insertQueryNoMsg($data,'data_bpjs');
                                            }else{
                                                $data=array_merge($data,$this->CI->model_global->getUpdateProperties($value['admin']));
                                                $this->CI->model_global->updateQueryNoMsg($data,'data_bpjs',['bulan'=>$key,'tahun'=>$value['tahun'],'id_karyawan'=>$data['id_karyawan']]);
                                            }
                                        }
                                    }else{
                                        if(isset($value['bulan'])){
                                            foreach ($value['bulan'] as $key => $val) {
                                                $data['bulan']=$val;
                                                $data['tahun']=$value['tahun'];
                                                $cek_data = $this->CI->model_payroll->getListBpjsEmp(['a.id_karyawan'=>$data['id_karyawan'],'a.bulan'=>$val,'a.tahun'=>$value['tahun']]);
                                                if(empty($cek_data)){
                                                    $data=array_merge($data,$this->CI->model_global->getCreateProperties($value['admin']));
                                                    $this->CI->model_global->insertQueryNoMsg($data,'data_bpjs');
                                                }else{
                                                    $data=array_merge($data,$this->CI->model_global->getUpdateProperties($value['admin']));
                                                    $this->CI->model_global->updateQueryNoMsg($data,'data_bpjs',['bulan'=>$val,'tahun'=>$value['tahun'],'id_karyawan'=>$data['id_karyawan']]);
                                                }
                                            }
                                        }
                                    }
                                }elseif ($value['usage'] == 'importGajiNonMatrix') {
                                    if(!empty($data['nik'])){
                                        $cek=$this->CI->model_karyawan->checkDataKaryawan(['nik'=>$data['nik']]);
                                        if($cek){
                                            $this->CI->model_global->updateQueryNoMsg($data,$value['table'],['nik'=>$data['nik']]); 
                                        }
                                    }
                                }elseif ($value['usage'] == 'importGajiBpjs') {
                                    if(!empty($data['nik'])){
                                        $cek=$this->CI->model_karyawan->checkDataKaryawan(['nik'=>$data['nik']]);
                                        if($cek){
                                            $this->CI->model_global->updateQueryNoMsg($data,$value['table'],['nik'=>$data['nik']]); 
                                        }
                                    }
                                }elseif ($value['usage'] == 'wfh') {
                                    if(!empty($data['nik'])){
                                        $cek=$this->CI->model_karyawan->checkDataKaryawan(['nik'=>$data['nik']]);
                                        if($cek){
                                            $this->CI->model_global->updateQueryNoMsg($data,$value['table'],['nik'=>$data['nik']]); 
                                        }
                                    }
                                }elseif ($value['usage'] == 'presensi_pa') {
									if (!empty($data['bulan']) && !empty($data['tahun'])) {
										$cek=$this->CI->model_presensi->checkPresensiMonthYear($data['nik'],$data['bulan'],$data['tahun']);
										if (!$cek) {
											$this->CI->model_global->insertQueryNoMsg($data,$value['table']);  
										}else{
											$this->CI->model_global->updateQueryNoMsg($data,$value['table'],['nik'=>$data['nik'],'bulan'=>$data['bulan'],'tahun'=>$data['tahun']]);
										}                                    
									}
                                }elseif ($value['usage'] == 'pph_kode_akun') {
                                    if(!empty($data['id_karyawan'])){
                                        $where = [
                                            'id_karyawan'=>$data['id_karyawan'],
                                            'kode_akun'=>$data['kode_akun'],
                                            'no_bukti'=>$data['no_bukti'],
                                            'tanggal'=>$data['tanggal'],
                                        ];
										$cek=$this->CI->model_payroll->getWhereDataKodeAkunPPH21($where);
										if (!$cek) {
											$this->CI->model_global->insertQueryNoMsg($data,$value['table']);  
										}else{
											$this->CI->model_global->updateQueryNoMsg($data,$value['table'],$where);
										}
                                    }
                                }elseif ($value['usage'] == 'data_jadwal_kerja') {
                                    if(!empty($data['id_karyawan'])){
                                        $where = [
                                            'id_karyawan'=>$data['id_karyawan'],
                                            'bulan'=>$data['bulan'],
                                            'tahun'=>$data['tahun'],
                                        ];
										$cek=$this->CI->model_karyawan->getListJadwalKerjaWhere($where);
										if (!$cek) {
											$this->CI->model_global->insertQueryNoMsg($data,$value['table']);  
										}else{
											$this->CI->model_global->updateQueryNoMsg($data,$value['table'],$where);
										}
                                    }
								}elseif ($value['usage'] == 'import_task'){
									$where = [
										'id_task'=>$getidtask['id_task']
									];									
									$this->CI->model_global->updateQueryNoMsg($data,$value['table'],$where);
								}elseif ($value['usage'] == 'import_detail_konsep_kpi'){
                                    // echo '<pre>';
                                    // print_r($data);
									$cek = $this->CI->model_agenda->openTableAgendaIdCode($value['table'],$data['nik'],$data['kode_kpi'],'kode_kpi','emp.nik');
									if(empty($cek['id_c_kpi'])){
										$getkpi = $this->CI->model_master->getKpikode($data['kode_kpi']);
										$getbobot = $this->CI->model_concept->openTableConceptKpi($value['table'],$getkpi['jenis']);
										$empall = $this->CI->model_karyawan->getEmployeeAll();
										foreach ($empall as $e) {
											if($e->jabatan == $data['kode_jabatan']){
												$data = [
													'id_karyawan' => $e->id_karyawan,
													'kode_jabatan' => $data['kode_jabatan'],
													'kode_loker' => $e->loker,
													'kode_kpi' => $data['kode_kpi'],
													'kpi' => $getkpi['kpi'],
													'rumus' => $getkpi['rumus'],
													'unit' => $getkpi['unit'],
													'definisi' => $getkpi['definisi'],
													'kaitan' => $getkpi['kaitan'],
													'jenis_satuan' => $getkpi['jenis_satuan'],
													'sifat' => $getkpi['sifat'],
													'cara_menghitung' => $getkpi['cara_menghitung'],
													'sumber_data'=>$getkpi['sumber_data'],
													'detail_rumus'=>$getkpi['detail_rumus'],
													'min'=>$getkpi['min'],
													'max'=>$getkpi['max'],
													'id_jenis_batasan_poin'=>$getkpi['id_jenis_batasan_poin'],
													'lebih_max'=>$getkpi['lebih_max'],
													'bobot' =>  $data['bobot'],
													'target' =>  $data['target'],
												];
												for ($i=1; $i <=$this->max_month ; $i++) { 
													$p='poin_'.$i;
													$s='satuan_'.$i;
													$data[$p]=$getkpi[$p];
													$data[$s]=$getkpi[$s];
												}
												$data_to_agenda=$data;
                                                $cekKPI = $this->CI->model_concept->getConceptKPIWhere($value['table'],['a.id_karyawan'=>$e->id_karyawan,'a.kode_jabatan'=>$data['kode_jabatan'],'a.kode_kpi'=>$data['kode_kpi']]);
                                                // echo '<pre>';
                                                // print_r($data);
                                                if(empty($cekKPI)){
                                                    $data=array_merge($data,$this->CI->model_global->getCreateProperties($value['other']['id_admin']));
                                                    $this->CI->model_global->insertQueryNoMsg($data,$value['table']); 
                                                    $this->CI->model_agenda->updateAgendaFromConceptMaster($data_to_agenda,'add_concept',[],$value['kode_concept']);
                                                }
											}
										}
									}else{
										$where = [
											'kode_kpi'=>$data['kode_kpi'],'kode_jabatan'=>$data['kode_jabatan'],'id_karyawan'=>$data['id_karyawan']
										];
										if (isset($data['nik'])) {
											unset($data['nik']);
										}
                                        $data=array_merge($data,$this->CI->model_global->getUpdateProperties($value['other']['id_admin']));
										$this->CI->model_global->updateQueryNoMsg($data,$value['table'],$where);
										unset($data['id_c_kpi']);
										$data_to_agenda=$data;
										$this->CI->model_agenda->updateAgendaFromConceptMaster($data_to_agenda,'edit_concept',$where,$value['kode_concept']);
									}
								}elseif ($value['usage'] == 'import_kpi'){
									$where = [
										'id_task'=>$getidtask['id_task']
									];
									$this->CI->model_global->updateQueryNoMsg($data,$value['table'],$where); 
                                }elseif($value['usage'] == 'aspek_sikap'){
                                    unset($data['nik']);
                                    unset($data['nilai']);
                                    unset($data['keterangan']);
                                    $where = ['id_task'=>$data['id_task']];
                                    $this->CI->model_global->updateQuery($data,$value['table'],$where);
                                    $dt1 = $this->CI->model_agenda->openTableAgendaId($value['table'],$data['id_task'],true);
                                    $this->CI->model_global->updateQuery(['na'=>($dt1['na_atas']+$dt1['na_bawah']+$dt1['na_rekan'])],$value['table'],$where);

								}
                            }else{
                                if (isset($data[$value['column_code']])) {
                                    $kode=$data[$value['column_code']];
                                    $cek_kode=$this->CI->model_global->checkCode($kode,$value['table'],$value['column_code']);
                                    if (!$cek_kode) {
                                    	$this->CI->model_global->insertQueryNoMsg($data,$value['table']); 
                                    }else{
                                        $same_code[]=$data['nik'];
                                    }
                                }
                            }
                            if (isset($data[$value['column_code']])) {
                                array_push($all_data,$data[$value['column_code']]);
                            }
                        }
                    }
                }
                
        // print_r($jadwal_kerja);
        // print_r($data_excel);
                if ($value['usage'] == 'presensi_one') {
                    foreach ($jadwal_kerja as $finger=>$date_jadwal) {
                        foreach ($date_jadwal as $tgl=>$d_j) {
                            if (isset($data_excel[$finger][$tgl])) {
                            // if (isset($data_excel)) {
                                foreach ($data_excel[$finger][$tgl] as $time) {
                                    if ($d_j['s'] != '00:00:00' && $d_j['e'] != '00:00:00' || (($d_j['s'] == '00:00:00' && $d_j['e'] != '00:00:00') || ($d_j['s'] != '00:00:00' && $d_j['e'] == '00:00:00'))) {
                                        $half=$this->CI->formatter->difHalfTime($d_j['s'],$d_j['e']);
                                        //toleransi jam masuk
                                        $tol_s_mulai=$this->CI->formatter->jam($d_j['s'],2,'-');
                                        $tol_e_mulai=$this->CI->formatter->jam($d_j['s'],$half,'+');
                                        //toleransi jam pulang
                                        $tol_s_selesai=$this->CI->formatter->jam($d_j['e'],$half,'-');
                                        $tol_e_selesai=$this->CI->formatter->jam($d_j['e'],14,'+'); 
                                        //compare
                                        if (strtotime($d_j['e']) < strtotime($d_j['s'])) {
                                            if ($time >= $tol_s_mulai && $time >= $tol_e_mulai) {
                                                $jadwal_kerja[$finger][$tgl]['jam_masuk']=$time;
                                            }
                                            if ($time >= $tol_s_mulai && $time <= $tol_e_mulai) {
                                                $jadwal_kerja[$finger][$tgl]['jam_masuk']=$time;
                                            }
                                            $tglx=date('Y-m-d',strtotime($tgl.' +1 day'));
                                            if (isset($data_excel[$finger][$tglx])) {
                                                $timez=min($data_excel[$finger][$tglx]);
                                                if ($timez >= $tol_s_selesai && $timez <= $tol_e_selesai) {
                                                    $jadwal_kerja[$finger][$tgl]['jam_keluar']=$timez;
                                                }
                                            }
                                            if (isset($jadwal_kerja[$finger][$tgl]['jam_keluar'])) {
                                                if (empty($jadwal_kerja[$finger][$tgl]['jam_keluar'])) {
                                                    $tglx1=date('Y-m-d',strtotime($tgl.' +1 day'));
                                                    if (isset($data_excel[$finger][$tglx1])) {
                                                        $timez1=min($data_excel[$finger][$tglx1]);
                                                        if ($tol_e_selesai < $tol_s_selesai) {
                                                            if ($timez1 <= $tol_s_selesai && $tol_e_selesai >= $timez1) {
                                                                $jadwal_kerja[$finger][$tgl]['jam_keluar']=$timez1;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if (isset($jadwal_kerja[$finger][$tgl]['jam_masuk'])) {
                                                if (empty($jadwal_kerja[$finger][$tgl]['jam_masuk'])) {
                                                    if ($time >= $tol_s_mulai && $time <= $tol_e_mulai) {
                                                        $jadwal_kerja[$finger][$tgl]['jam_masuk']=$time;
                                                    }
                                                }
                                            }
                                            $tglx2=date('Y-m-d',strtotime($tgl.' -1 day'));
                                            if (isset($jadwal_kerja[$finger][$tglx2])) {
                                                $timez2=min($data_excel[$finger][$tgl]);
                                                if ($timez2 <= $tol_s_selesai && $timez2 <= $tol_e_selesai) {
                                                    $jadwal_kerja[$finger][$tglx2]['jam_keluar']=$timez2;
                                                }
                                            }
                                            //for jadwal 00:00:00
                                            if (isset($jadwal_kerja[$finger][$tgl]['jam_keluar']) && $d_j['e'] == '00:00:00') {
                                                if (empty($jadwal_kerja[$finger][$tgl]['jam_keluar'])) {
                                                    if ($tol_e_selesai < $tol_s_selesai) {
                                                        if ($time >= $tol_s_selesai) {
                                                            $jadwal_kerja[$finger][$tgl]['jam_keluar']=$time;
                                                        }
                                                        if ($time <= $tol_s_selesai && $time <= $tol_e_selesai) {
                                                            $jadwal_kerja[$finger][$tgl]['jam_keluar']=$time;
                                                        }
                                                    }else{
                                                        if ($time >= $tol_s_selesai && $time <= $tol_e_selesai) {
                                                            $jadwal_kerja[$finger][$tgl]['jam_keluar']=$time;
                                                        }
                                                    }
                                                }
                                            }
                                        }else{  
                                            if ($time >= $tol_s_mulai && $time <= $tol_e_mulai) {
                                                $jadwal_kerja[$finger][$tgl]['jam_masuk']=$time;
                                            }
                                            if ($tol_e_selesai < $tol_s_selesai) {
                                                if ($time >= $tol_s_selesai) {
                                                    $jadwal_kerja[$finger][$tgl]['jam_keluar']=$time;
                                                }
                                                if ($time <= $tol_s_selesai && $time <= $tol_e_selesai) {
                                                    $jadwal_kerja[$finger][$tgl]['jam_keluar']=$time;
                                                }
                                            }else{
                                                if ($time >= $tol_s_selesai && $time <= $tol_e_selesai) {
                                                    $jadwal_kerja[$finger][$tgl]['jam_keluar']=$time;
                                                }
                                            }                                           
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // print_r($data_excel);
                    // print_r($jadwal_kerja);
                    foreach ($jadwal_kerja as $finger=>$date_jadwal) {
                        foreach ($date_jadwal as $tgl=>$d_j) {
                            unset($d_j['s']);unset($d_j['e']);
                            $cek = $this->CI->model_karyawan->checkPresensiDateImport($d_j['id_karyawan'],$tgl);
                            $cekizin=$this->CI->model_karyawan->cekIzinCutiPresensi($d_j['id_karyawan'],$tgl);
                            $kodeizincuti=(!empty($cekizin)?$cekizin['kode_izin_cuti']:null);
                            $cekLibur=$this->CI->model_karyawan->cekHariLiburPresensi($tgl);
                            $kodeLibur=(!empty($cekLibur)?$cekLibur['kode_hari_libur']:null);
                            $cekLembur=$this->CI->model_karyawan->cekDataLemburPresensi($d_j['id_karyawan'],$tgl);
                            $kodeLembur=(!empty($cekLembur)?$cekLembur['no_lembur']:null);
                            $perdin = $this->CI->model_karyawan->cekDataPerDinPresensi($d_j['id_karyawan'],$tgl)['no_sk'];
                            $kode_perdin=(!empty($perdin)?$perdin:null);
                            $data['kode_ijin']=$kodeizincuti;
                            $data['kode_hari_libur']=$kodeLibur;
                            $data['no_spl']=$kodeLembur;
                            $data['kode_shift']=$d_j['kode_shift'];
                            $data['no_perjalanan_dinas']=$kode_perdin;
                            if (!empty($cek['id_karyawan'])) {
                                if ($cek['jam_mulai'] == null) {
                                    $data['jam_mulai']=$d_j['jam_masuk'];
                                }else{
                                    $data['jam_mulai']=$cek['jam_mulai'];
                                }
                                if (!empty($cek['jam_selesai'])) {
                                    $data['jam_selesai']=$cek['jam_selesai'];
                                }else{
                                    $data['jam_selesai']=$d_j['jam_keluar'];
                                }
                            }else{
                                $data['jam_mulai']=$d_j['jam_masuk'];
                                $data['jam_selesai']=$d_j['jam_keluar'];
                            }
                            $data['id_karyawan'] = (isset($d_j['id_karyawan'])) ? ((!empty($d_j['id_karyawan'])) ? $d_j['id_karyawan'] : null) : null;
                            $data['tanggal'] = (isset($tgl)) ? ((!empty($tgl)) ? $tgl : null) : null;
                            unset($data['tanggal_waktu']);
                            unset($data['id_finger']);
                            // print_r($data);
                            //db process
                            if (!empty($data['id_karyawan']) && !empty($data['tanggal'])) {
                                $cek=$this->CI->model_karyawan->checkPresensiDate($data['id_karyawan'],$data['tanggal']);
                                if (!$cek) {
                                    $this->CI->model_global->insertQueryNoMsg($data,$value['table']);
                                }else{
                                    $this->CI->model_global->updateQueryNoMsg($data,$value['table'],['id_karyawan'=>$data['id_karyawan'],'tanggal'=>$data['tanggal']]);
                                }                                    
                            }
                            if (isset($data[$value['column_code']])) {
                                array_push($all_data,$data[$value['column_code']]);
                            }
                        }
                    }
                }
            }
            if (count($same_code) > 0 && count($same_code) < count($all_data)) {
                $data_return=$this->CI->messages->customWarning('Data '.implode(', ', $same_code).' sudah ada di database, beberapa data yang lain sudah masuk');
            }else if(count($all_data) == 0){
                $data_return=$this->CI->messages->customFailure('Data Anda Kosong');
            }else if(count($same_code) == count($all_data)){
                $data_return=$this->CI->messages->customFailure('Semua data sudah ada di database');
            }else{
                $data_return=$this->CI->messages->allGood();
            }
            unlink($file_db);
        }
        return $data_return;
    }
//###################################################### BLOCK OF IMPORT END ######################################################//
}
