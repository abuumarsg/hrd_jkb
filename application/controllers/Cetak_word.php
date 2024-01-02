<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_word extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
        if (isset($_SESSION['adm'])) {
            $this->admin = $_SESSION['adm']['id'];  
        }else{
            $this->admin = $_SESSION['emp']['id'];  
            // redirect('auth');
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
        $this->dir=$_SERVER['DOCUMENT_ROOT'].'/asset/img/loo.png';
    }
	public function index()
	{
		$this->redirect('not_found');
	}
	public function cetak_mutasi(){
        $id=$this->uri->segment(3);
        $nik=$this->uri->segment(4);
        $user = $this->dtroot;
		$datax=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getMutasi($id));
		if (!isset($datax)){
            redirect('not_found');
        }else{
            if ($datax['no_sk'] != null){
                if($datax['nama_file'] == null){
                    $this->messages->sessError('Template Tidak Tersedia.');
                    redirect('pages/view_mutasi/'.$nik);
                }else{
                	$data=[
                		'name_file'=>'SURAT '.$datax['nama_status'].' '.$datax['nama_karyawan'],
                		'data'=>['dicetak'=>$user['adm']['nama'],
                                'tgl_dicetak'=>$this->formatter->getDateTimeMonthFormatUser($this->date),
                                'no_sk'=>$datax['no_sk'],
                				'nama'=>$datax['nama_karyawan'],
                				'nip'=>$datax['nik_karyawan'],
                				'jabatan_saat_ini'=>$datax['nama_jabatan'],
                                'jabatan_baru'=>$datax['nama_jabatan_baru'],
                				'bagian'=>$datax['nama_bagian'],
                				'lokasi_baru'=>$datax['nama_loker_baru'],
                				'lama_percobaan'=>$datax['lama_percobaan'].' ('.$this->formatter->kataTerbilang($datax['lama_percobaan']).') Bulan',
                				'keterangan_html'=>$this->formatter->getValHastagPrint($datax['keterangan']),
                				'tanggal_berlaku'=>$this->formatter->getDateMonthFormatUser($datax['tgl_berlaku']),
                				'tanggal_sk'=>$this->formatter->getDateMonthFormatUser($datax['tgl_sk']),
                				'nama_mengetahui'=>$datax['nama_mengetahui'],
                				'jabatan_mengetahui'=>$datax['jbt_mengetahui'],
                				'nama_menyetujui'=>$datax['nama_menyetujui'],
                				'jabatan_menyetujui'=>$datax['jbt_menyetujui'],
                				'alamat_menyetujui'=>$datax['jalan'].' '.$datax['desa'].' '.$datax['kecamatan'].' '.$datax['kabupaten'],
                			]
                	];
        			$this->wordgenerator->genDoc($data,$datax['nama_file']);
                }
            }else{
            	redirect('not_found');
            }
        }
	}
	public function cetak_perjanjian(){
		$id=$this->uri->segment(3);
        $nik=$this->uri->segment(4);
		$datax=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getPerjanjianKerja($id));
		if (!isset($datax)){
            redirect('not_found');
        }else{
            if ($datax['no_sk_baru'] != null){
                if($datax['nama_file'] == null){
                    $this->messages->sessError('Template Tidak Tersedia.');
                    redirect('pages/view_perjanjian_kerja/'.$nik);
                }else{
                    $tanggal=substr($datax['tgl_sk_baru'],8,2);
                    $bulan=substr($datax['tgl_sk_baru'],5,2);
                    $tahun=substr($datax['tgl_sk_baru'],0,4);
                    $lama_kontrak = $this->otherfunctions->hitungBulan($datax['tgl_berlaku_baru'],$datax['berlaku_sampai_baru']);
                    $lama_kontrak_terbilang = $this->formatter->kataTerbilang($lama_kontrak);
                    if($datax['jenis_gaji'] == 'matrix'){
                        $gaji_pokok = $datax['gapok'];
                    }else{
                        $gaji_pokok = $datax['gaji_non_matrix'];
                    }
                	$data=[
                		'name_file'=>'SURAT '.$datax['nama_status_baru'].' '.$datax['nama_karyawan'],
                		'data'=>[
                            'tgl_dicetak'=>$this->formatter->getDateTimeMonthFormatUser($this->date),
                            'tanggal_sekarang'=>$this->formatter->getDateMonthFormatUser($this->date),
                            'dicetak'=>$this->dtroot['adm']['nama'],
                            'no_sk'=>$datax['no_sk_baru'],
                            'hari'=>$this->formatter->getNameOfDay($tanggal),
                            'tanggal'=>$tanggal,
                            'bulan'=>$this->formatter->getNameOfMonth($bulan),
                            'tahun'=>$tahun,
                            'tanggal_sk'=>$this->formatter->getDateMonthFormatUser($datax['tgl_sk_baru']),
                            'nama_mengetahui'=>$datax['nama_mengetahui'],
                            'jabatan_mengetahui'=>$datax['jbt_mengetahui'],
                            'alamat_mengetahui'=>$datax['jalan_atasan'].', '.$datax['desa_atasan'].', '.$datax['kecamatan_atasan'].', '.$datax['kabupaten_atasan'].', '.$datax['provinsi_atasan'].', '.$datax['pos_atasan'],
                            'nip'=>$datax['nik'],
                            'nama'=>$datax['nama_karyawan'],
                            'jabatan'=>$datax['nama_jabatan'],
                            'nama_bagian'=>$datax['nama_bagian'],
                            'nama_loker'=>$datax['nama_loker'],
                            'tanggal_lahir'=>$datax['tempat_lahir'].', '.$this->formatter->getDateMonthFormatUser($datax['tgl_lahir']),
                            'alamat'=>$datax['alamat_asal_jalan'].', '.$datax['alamat_asal_desa'].', '.$datax['alamat_asal_kecamatan'].', '.$datax['alamat_asal_kabupaten'].', '.$datax['alamat_asal_provinsi'].', '.$datax['alamat_asal_pos'],
                            'lama_kontrak'=>$lama_kontrak.' ('.$lama_kontrak_terbilang.') Bulan',
                            'tanggal_kontrak'=>$this->formatter->getDateMonthFormatUser($datax['tgl_berlaku_baru']),
                            'tanggal_selesai'=>$this->formatter->getDateMonthFormatUser($datax['berlaku_sampai_baru']),
                            'jabatan_mengetahui'=>$datax['jbt_mengetahui'],
                            'gaji'=>$this->formatter->getFormatMoneyUser($gaji_pokok),
                            'gaji_terbilang'=>$this->formatter->kataTerbilang($gaji_pokok),
                            'tanggal_masuk'=>$this->formatter->getDateMonthFormatUser($datax['tgl_masuk']),
                            'menyetujui'=>$datax['nama_menyetujui'],
                            'jabatan_menyetujui'=>$datax['jbt_menyetujui'],
                        ]
                	];
        			$this->wordgenerator->genDoc($data,$datax['nama_file']);
                }
            }else{
            	redirect('not_found');
            }
        }
	}
	public function cetak_peringatan(){
		$id=$this->uri->segment(3);
        $nik=$this->uri->segment(4);
		$datax=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getPeringatanKerja($id));
		if (!isset($datax)){
            redirect('not_found');
        }else{
            if ($datax['no_sk'] != null){
                if($datax['nama_file'] == null){
                    $this->messages->sessError('Template Tidak Tersedia.');
                    redirect('pages/view_peringatan/'.$nik);
                }else{
                	$data=[
                		'name_file'=>'SURAT '.$datax['nama_status_baru'].' '.$datax['nama_karyawan'],
                		'data'=>['tgl_dicetak'=>$this->formatter->getDateTimeMonthFormatUser($this->date),
                            'dicetak'=>$this->dtroot['adm']['nama'],
                            'no_sk'=>$datax['no_sk'],
                            'nama'=>$datax['nama_karyawan'],
                            'nik'=>$datax['nik_karyawan'],
                            'jabatan'=>$datax['nama_jabatan'],
                            'loker'=>$datax['nama_loker'],
                            // 'pelanggaran_html'=>$datax['pelanggaran'],
                            // 'sanksi_html'=>$datax['keterangan'],
                            'pelanggaran_html'=>$this->formatter->getValHastagPrint($datax['pelanggaran']),
                            'sanksi_html'=>$this->formatter->getValHastagPrint($datax['keterangan']),
                            'tanggal_berlaku'=>$this->formatter->getDateMonthFormatUser($datax['tgl_berlaku']),
                            'tanggal_sk'=>$this->formatter->getDateMonthFormatUser($datax['tgl_sk']),
                            'mengetahui'=>$datax['nama_mengetahui'],
                            'jabatan_mengetahui'=>$datax['jbt_mengetahui'],
                            'menyetujui'=>$datax['nama_menyetujui'],
                            'jabatan_menyetujui'=>$datax['jbt_menyetujui'],
                            'dibuat'=>$datax['nama_dibuat'],
                            'jabatan_buat'=>$datax['jbt_dibuat'],
                        ]
                	];
        			$this->wordgenerator->genDoc($data,$datax['nama_file']);
                }
            }else{
            	redirect('not_found');
            }
        }
	}
	public function cetak_grade(){
		$id=$this->uri->segment(3);
        $nik=$this->uri->segment(4);
		$datax=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getGradeKaryawan($id));
		if (!isset($datax)){
            redirect('not_found');
        }else{
            if ($datax['no_sk'] != null){
                if($datax['nama_file'] == null){
                    $this->messages->sessError('Template Tidak Tersedia.');
                    redirect('pages/view_grade/'.$nik);
                }else{
                	$data=[
                		'name_file'=>'SURAT GRADE '.$datax['nama_grade_baru'].'('.$datax['nama_loker_grade'].') '.$datax['nama_karyawan'],
                		'data'=>['no_sk'=>$datax['no_sk'],
                            'nama'=>$datax['nama_karyawan'],
                            'nik'=>$datax['nik_karyawan'],
                            'grade_baru'=>$datax['nama_grade_baru'].' ('.$datax['nama_loker_grade'].')',
                            ]
                	];
        			$this->wordgenerator->genDoc($data,$datax['nama_file']);
                }
            }else{
            	redirect('not_found');
            }
        }
	}
    public function cetak_kecelakaan_kerja(){
        $id=$this->uri->segment(3);
        $nik=$this->uri->segment(4);
        $datax=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getKecelakaanKerjaKaryawan($id));
        if (!isset($datax)){
            redirect('not_found');
        }else{
            if ($datax['no_sk'] != null){
                if($datax['nama_file'] == null){
                    $this->messages->sessError('Template Tidak Tersedia.');
                    redirect('pages/view_kecelakaan_kerja/'.$nik);
                }else{
                    $data=[
                        'name_file'=>'SURAT KECELAKAAN KERJA '.$datax['nama_kategori_kecelakaan'].' '.$datax['nama_karyawan'],
                        'data'=>['tgl_dicetak'=>$this->formatter->getDateTimeMonthFormatUser($this->date),
                            'dicetak'=>$this->dtroot['adm']['nama'],
                            'no_sk'=>$datax['no_sk'],
                            'nama'=>$datax['nama_karyawan'],
                            'nik'=>$datax['nik_karyawan'],
                            'kategori'=>$datax['nama_kategori_kecelakaan'],
                            'nama_rs'=>$datax['nama_rs'],
                            'alamat_rs'=>$datax['alamat_rs'],
                            'mengetahui'=>$datax['nama_mengetahui'],
                            'jabatan_mengetahui'=>$datax['jbt_mengetahui'],
                            'no_bpjs_tk'=>$datax['bpjs_tk'],
                            'tanggal'=>$this->formatter->getDateMonthFormatUser($datax['tgl']),
                            'jam'=>$this->formatter->getTimeFormatUser($datax['jam']),
                            'lokasi'=>$datax['nama_loker_kejadian'],
                            'tanggal_cetak'=>$this->formatter->getDateMonthFormatUser($datax['tgl_cetak']),
                            'tembusan'=>$datax['tembusan'],
                        ]
                    ];
                    $this->wordgenerator->genDoc($data,$datax['nama_file']);
                }
            }else{
                redirect('not_found');
            }
        }
    }
    public function cetak_kta(){
        $id=$this->uri->segment(3);
        $datax=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getKaryawanNonAktif($id));
        if (!isset($datax)){
            redirect('not_found');
        }else{
            if ($datax['no_sk'] != null){
                if($datax['nama_file'] == null){
                    $this->messages->sessError('Template Tidak Tersedia.');
                    redirect('pages/data_karyawan_non_aktif/');
                }else{
                    $jalan=isset($datax['alamat_asal_jalan'])?$datax['alamat_asal_jalan'].', ':null;
                    $desa=isset($datax['alamat_asal_desa'])?$datax['alamat_asal_desa'].', ':null;
                    $kec=isset($datax['alamat_asal_kecamatan'])?$datax['alamat_asal_kecamatan'].', ':null;
                    $kab=isset($datax['alamat_asal_kabupaten'])?$datax['alamat_asal_kabupaten'].', ':null;
                    $prov=isset($datax['alamat_asal_provinsi'])?$datax['alamat_asal_provinsi'].', ':null;
                    $data=[
                        'name_file'=>'SURAT KETERANGAN KERJA '.$datax['nama_karyawan'],
                        'data'=>['tgl_dicetak'=>$this->formatter->getDateTimeMonthFormatUser($this->date),
                            'dicetak'=>$this->dtroot['adm']['nama'],
                            'no_sk'=>$datax['no_sk'],
                            'nama'=>$datax['nama_karyawan'],
                            'nik'=>$datax['nik'],
                            'jabatan'=>$datax['nama_jabatan'],
                            'ttl'=>$datax['tempat_lahir'].', '.$this->formatter->getDateMonthFormatUser($datax['tgl_lahir']),
                            'alamat'=>$jalan.$desa.$kec.$kab.$prov,
                            'tanggal_masuk'=>$this->formatter->getDateMonthFormatUser($datax['tgl_masuk']),
                            'tanggal_keluar'=>$this->formatter->getDateMonthFormatUser($datax['tgl_keluar']),
                            'tanggal_berlaku'=>$this->formatter->getDateMonthFormatUser($datax['tgl_berlaku']),
                            'alasan'=>$datax['nama_alasan'],
                            'tanggal_sk'=>$this->formatter->getDateMonthFormatUser($datax['tgl_sk']),
                            'nama_mengetahui'=>$datax['nama_mengetahui'],
                            'jabatan_mengetahui'=>$datax['jbt_mengetahui'],
                        ]
                    ];
                    $this->wordgenerator->genDoc($data,$datax['nama_file']);
                }
            }else{
                redirect('not_found');
            }
        }
    }
    public function cetak_exit_interview(){
        $id=$this->uri->segment(3);
        $datax=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getListExitInterviewID($id));
        if (!isset($datax)){
            redirect('not_found');
        }else{
            if ($datax['id_karyawan'] != null){
                if($datax['nama_file'] == null){
                    $this->messages->sessError('Template Tidak Tersedia.');
                    redirect('pages/data_karyawan_non_aktif/');
                }else{
                    $data=[
                        'name_file'=>'SURAT EXIT INTERVIEW '.$datax['nama_karyawan'],
                        'data'=>['tgl_dicetak'=>$this->formatter->getDateTimeMonthFormatUser($this->date),
                            'dicetak'=>$this->dtroot['adm']['nama'],
                            'nama_karyawan'=>$datax['nama_karyawan'],
                            'nik_karyawan'=>$datax['nik'],
                            'jabatan_karyawan'=>$datax['nama_jabatan'],
                            'tanggal'=>$this->formatter->getDateMonthFormatUser($this->date),
                            'tanggal_masuk'=>$this->formatter->getDateMonthFormatUser($datax['tgl_masuk']),
                            'tanggal_keluar'=>$this->formatter->getDateMonthFormatUser($datax['tgl_keluar']),
                            'alasan_keluar'=>$datax['nama_alasan'],
                            'setelah'=>$datax['setelah'],
                            'posisi'=>$datax['posisi'],
                            'tertarik'=>$datax['tertarik'],
                            'kompensasi'=>$datax['kompensasi'],
                            'penilaian'=>$this->otherfunctions->getRadio($datax['penilaian']),
                            'alasan'=>$datax['alasan'],
                            'lingkungan'=>$datax['lingkungan'],
                            'support'=>$datax['support'],
                            'pelatihan'=>$datax['pelatihan'],
                            'saran'=>$datax['saran'],
                            'kota'=>'Karangjati',
                        ]
                    ];
                    $this->wordgenerator->genDoc($data,$datax['nama_file']);
                }
            }else{
                redirect('not_found');
            }
        }
    }
	public function cetak_izin(){
        $id=$this->uri->segment(3);
        $user = $this->dtroot;
		$datax=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getIzinCuti($id));
		if (!isset($datax)){
            redirect('not_found');
        }else{
            if ($datax['kode_izin_cuti'] != null){
                if($datax['nama_file'] == null){
                    $this->messages->sessError('Template Tidak Tersedia.');
                    redirect('kpages/data_izin_cuti');
                }else{
                    $lama_izin=$this->otherfunctions->hitungHari($datax['tgl_mulai'],$datax['tgl_selesai']);
                    $jenis_izin_cuti=$this->model_master->getMasterIzinJenis($datax['jenis'])['jenis'];
                	$data=[
                		'name_file'=>'SURAT Permohonan Izin '.$datax['nama_karyawan'],
                		'data'=>['dicetak'=>$user['adm']['nama'],
                                'tgl_dicetak'=>$this->formatter->getDateTimeMonthFormatUser($this->date),
                                'nik_karyawan'=>$datax['nik_karyawan'],
                                'nama_karyawan'=>$datax['nama_karyawan'],
                                'jabatan_karyawan'=>$datax['nama_jabatan'],
                                'bagian_karyawan'=>$datax['nama_bagian'],
                                'jenis'=>$this->otherfunctions->getIzinCuti($jenis_izin_cuti),
                                'lama_izin'=>$lama_izin,
                                'terbilang'=>$this->formatter->kataTerbilang($lama_izin),
                                'tgl_mulai'=>$this->formatter->getDayDateFormatUserId($datax['tgl_mulai']),
                                'tgl_selesai'=>$this->formatter->getDayDateFormatUserId($datax['tgl_selesai']),
                                'jam_mulai'=>$this->formatter->timeFormatUser($datax['jam_mulai']).' WIB',
                                'jam_selesai'=>$this->formatter->timeFormatUser($datax['jam_selesai']).' WIB',
                                'keperluan'=>$datax['alasan'],
                                'alamat'=>$datax['keterangan'],
                                // 'alamat'=>'Karangjati',
                                'tgl_surat'=>$this->formatter->getDateMonthFormatUser($datax['tgl_mulai']),
                                'nama_mengetahui'=>$datax['nama_mengetahui'],
                                'nama_menyetujui_1'=>$datax['nama_menyetujui'],
                                'nama_menyetujui_2'=>$datax['nama_menyetujui_2'],
                                'jabatan_mengetahui'=>$datax['jbt_mengetahui'],
                                'jabatan_menyetutui_1'=>$datax['jbt_menyetujui'],
                                'jabatan_menyetujui_2'=>$datax['jbt_menyetujui_2'],
                			]
                	];
                    $this->wordgenerator->genDoc($data,$datax['nama_file']);
                }
            }else{
            	redirect('not_found');
            }
        }
	}
	public function biodata_umum(){
        $nik_e=$this->uri->segment(3);
        $nik=$this->codegenerator->decryptChar($nik_e);
        $user = $this->dtroot;
		$emp=$this->model_karyawan->getEmployeeNik($nik);
		if (!isset($emp)){
            redirect('not_found');
        }else{
            $temp=$this->model_master->cekDokumen('DOC_KAR');
            if(empty($temp)){
                $this->messages->sessError('Template Tidak Tersedia.');
                redirect('pages/view_employee/'.$nik_e);
            }else{
                $alamat_asal = (!empty($emp['alamat_asal_jalan'])?$emp['alamat_asal_jalan'].', ':null).(!empty($emp['alamat_asal_desa'])?$emp['alamat_asal_desa'].', ':null).(!empty($emp['alamat_asal_kecamatan'])?$emp['alamat_asal_kecamatan'].', ':null).(!empty($emp['alamat_asal_kabupaten'])?$emp['alamat_asal_kabupaten'].', ':null).(!empty($emp['alamat_asal_provinsi'])?$emp['alamat_asal_provinsi'].', ':null);
                $alamat_skrg=(!empty($emp['alamat_sekarang_jalan'])?$emp['alamat_sekarang_jalan'].', ':null).(!empty($emp['alamat_sekarang_desa'])?$emp['alamat_sekarang_desa'].', ':null).(!empty($emp['alamat_sekarang_kecamatan'])?$emp['alamat_sekarang_kecamatan'].', ':null).(!empty($emp['alamat_sekarang_kabupaten'])?$emp['alamat_sekarang_kabupaten'].', ':null).(!empty($emp['alamat_sekarang_provinsi'])?$emp['alamat_sekarang_provinsi'].', ':null);
			    $jumlah_anak = $this->db->get_where('karyawan_anak',['nik'=>$nik])->num_rows();
                $maxJenjanng = $this->model_karyawan->pendidikan_max($nik);
                $mjjang = $this->otherfunctions->getEducate($maxJenjanng['jenjang_pendidikan']);
                $data=[
                    'name_file' =>$temp['nama'].' '.$emp['nama'],
                    'data'=>['dicetak'      =>$user['adm']['nama'],
                            'tgl_dicetak'   =>$this->formatter->getDateTimeMonthFormatUser($this->date),
                            'nama'          => $emp['nama'],
                            'nik'           => $emp['nik'],
                            'alamat_asal'   => (!empty($alamat_asal) ? $alamat_asal : ' - '),
                            'alamat_sekarang'=> (!empty($alamat_skrg) ? $alamat_skrg : ' - '),
                            'pos_asal'      => $emp['alamat_asal_pos'],
                            'pos'           => $emp['alamat_sekarang_pos'],
                            'id_finger'     => (!empty($emp['id_finger']) ? $emp['id_finger'] : ' - '),
                            'no_ktp'        => (!empty($emp['no_ktp']) ? $emp['no_ktp'] : ' - '),
                            'agama'         => (!empty($emp['agama']) ? $this->otherfunctions->getReligion($emp['agama']) : ' - '),
                            'golongan_darah'=> (!empty($emp['gol_darah']) ? $this->otherfunctions->getBlood($emp['gol_darah']) : ' - '),
                            'tinggi_badan'  => (!empty($emp['tinggi_badan']) ? $emp['tinggi_badan'] : ' - '),
                            'berat_badan'   => (!empty($emp['berat_badan']) ? $emp['berat_badan'] : ' - '),
                            'email'         => (!empty($emp['email']) ? $emp['email'] : ' - '),
                            'jenis_kelamin' => (!empty($emp['kelamin']) ? $this->otherfunctions->getGender($emp['kelamin']) : ' - '),
                            'tempat_lahir'  => (!empty($emp['tempat_lahir']) ? $emp['tempat_lahir'] : ' - '),
                            'tanggal_lahir' => (!empty($emp['tgl_lahir']) ? $this->formatter->getDateMonthFormatUser($emp['tgl_lahir']) : ' - '),
                            'no_ponsel'     => (!empty($emp['no_hp']) ? $emp['no_hp'] : ' - '),
                            'nama_bank'     => (!empty($emp['nama_bank']) ? $emp['nama_bank'] : ' - '),
                            'rekening'      => (!empty($emp['rekening']) ? $emp['rekening'] : ' - '),
                            'npwp'          => (!empty($emp['npwp']) ? $emp['npwp'] : ' - '),
                            'bpjs_kes'      => (!empty($emp['bpjskes']) ? $emp['bpjskes'] : ' - '),
                            'bpjs_tk'       => (!empty($emp['bpjstk']) ? $emp['bpjstk'] : ' - '),
                            'no_hp_ayah'    => (!empty($emp['no_hp_ayah']) ? $emp['no_hp_ayah'] : ' - '),
                            'no_hp_ibu'     => (!empty($emp['no_hp_ibu']) ? $emp['no_hp_ibu'] : ' - '),
                            'no_hp_pasangan'=> (!empty($emp['no_hp_pasangan']) ? $emp['no_hp_pasangan'] : ' - '),
                            'nama_ibu'      => (!empty($emp['nama_ibu']) ? $emp['nama_ibu'] : ' - '),
                            'nama_ayah'     => (!empty($emp['nama_ayah']) ? $emp['nama_ayah'] : ' - '),
                            'status_nikah'  => (!empty($emp['status_nikah']) ? $emp['status_nikah'] : ' - '),
                            'nama_pasangan' => (!empty($emp['nama_pasangan']) ? $emp['nama_pasangan'] : ' - '),
                            'tanggal_masuk' => $this->formatter->getDateMonthFormatUser($emp['tgl_masuk']),
                            'status_karyawan'=> $emp['nama_status'],
                            'jabatan'       => $emp['nama_jabatan'],
                            'level_jabatan' => $emp['nama_level_jabatan'],
                            'lokasi_kerja'  => $emp['nama_loker'],
                            // 'foto'          => $emp['foto'],
                            'jumlah_anak'   =>(!empty($jumlah_anak) || ($jumlah_anak != 0) ? $jumlah_anak.' Anak': ' - '),
                            'jenjang'       => (!empty($maxJenjanng['jenjang_pendidikan']) ? $mjjang: ' - '),
                            'universitas'   => (!empty($maxJenjanng['nama_sekolah']) ? $maxJenjanng['nama_sekolah']: '- '),
                            'jurusan'       => (!empty($maxJenjanng['jurusan']) ? $maxJenjanng['jurusan'] : ' - '),
                    ],
                ];
                $this->wordgenerator->genDoc($data,$temp['file']);
            }
        }
	}
	public function cetak_perdin(){
        $noSK        = $this->uri->segment(3);
		$noPerDin    = $this->codegenerator->decryptChar($noSK);
		$user        = $this->dtroot;
		$perDinTrans = $this->model_karyawan->getPerjalananDinasKodeSK($noPerDin);
		$datax       = $this->otherfunctions->convertResultToRowArray($perDinTrans);
        $karyawan=[];
        foreach ($perDinTrans as $tr) {
            $karyawan[]=$tr->id_karyawan;
        }
		if (!isset($datax)){
            redirect('not_found');
        }else{
            $temp=$this->model_master->cekDokumen('DOC_PERDIN');
            if(empty($temp)){
                $this->messages->sessError('Template Tidak Tersedia.');
                redirect('pages/data_perjalanan_dinas');
            }else{
                $tujuan=($datax['plant']=='plant')?$datax['nama_plant_tujuan']:$datax['lokasi_tujuan'];
                $kendaraan=($datax['kendaraan']=='KPD0001') ? $datax['nama_kendaraan_j'].' ('.$this->otherfunctions->getKendaraanUmum($datax['nama_kendaraan']).')' : $datax['nama_kendaraan_j'];
                $valKendaraan='';
                if($datax['validasi_ac'] == 1 || $datax['validasi_ac'] == 0){
                    $valKendaraan.=($datax['val_kendaraan']=='KPD0001') ? $datax['val_nama_kendaraan_j'].' ('.$this->otherfunctions->getKendaraanUmum($datax['val_kendaraan_umum']).')' : $datax['val_nama_kendaraan_j'];
                }
                $data_akun='';
                $nAkun=0;
                $dataKodeAkun=$this->model_karyawan->getKodeAkunNoSK($noPerDin);
                if(count($dataKodeAkun) > 0){
                    foreach ($dataKodeAkun as $aa) {
                        $nAkun=$nAkun+$aa->nominal;
                    }
                    $data_akun.=$this->otherfunctions->getKodeAKunViewPrint($dataKodeAkun);
                }
                $nama_penginapan = (empty($datax['nama_penginapan'])?null:$this->otherfunctions->getPenginapan($datax['nama_penginapan']));
                $val_nama_penginapan = (empty($datax['val_penginapan'])?null:$this->otherfunctions->getPenginapan($datax['val_penginapan']));
                $data=[
                    'name_file'=>'Surat Perjalanan Dinas '.$datax['no_sk'],
                    'data'=>['dicetak'=>$user['adm']['nama'],
                            'tgl_dicetak'       =>$this->otherfunctions->checkContentLetter($this->formatter->getDateTimeMonthFormatUser($this->date)),
                            'no_perdin'         =>$this->otherfunctions->checkContentLetter($datax['no_sk']),
                            'tanggal_berangkat' =>$this->otherfunctions->checkContentLetter($this->formatter->getDateTimeMonthFormatUser($datax['tgl_berangkat'])),
                            'tanggal_selesai'   =>$this->otherfunctions->checkContentLetter($this->formatter->getDateTimeMonthFormatUser($datax['tgl_sampai'])),
                            'plant_asal'        =>$this->otherfunctions->checkContentLetter($datax['nama_plant_asal']),
                            'tujuan'            =>$this->otherfunctions->checkContentLetter($tujuan),
                            'kendaraan'         =>$this->otherfunctions->checkContentLetter($kendaraan),
                            'menginap'          =>$this->otherfunctions->checkContentLetter($nama_penginapan),
                            'tugas'             =>$this->otherfunctions->checkContentLetter($datax['tugas']),
                            'karyawan'          =>$this->otherfunctions->checkContentLetter($this->otherfunctions->getKaryawanViewPrint($karyawan)),
                            'kendaraan_val'     =>$this->otherfunctions->checkContentLetter($valKendaraan),
                            'penginapan_val'    =>$this->otherfunctions->checkContentLetter($val_nama_penginapan),
                            'total_akomodasi'   =>$this->otherfunctions->checkContentLetter($this->formatter->getFormatMoneyUser($datax['val_nominal_bbm']+$datax['val_nominal_penginapan'])),
                            'kode_akun'         =>$this->otherfunctions->checkContentLetter($data_akun),
                            'total_kode_akun'   =>$this->otherfunctions->checkContentLetter($this->formatter->getFormatMoneyUser($nAkun)),
                            'nama_mengetahui'   =>$this->otherfunctions->checkContentLetter($datax['nama_mengetahui']),
                            'nama_menyetujui'   =>$this->otherfunctions->checkContentLetter($datax['nama_menyetujui']),
                            'jabatan_mengetahui'=>$this->otherfunctions->checkContentLetter($datax['jbt_mengetahui']),
                            'jabatan_menyetujui'=>$this->otherfunctions->checkContentLetter($datax['jbt_menyetujui']),
                        ]
                ];
                $this->wordgenerator->genDoc($data,$temp['file']);
            }
        }
	}
	public function cetak_lembur(){
        $noSpl        = $this->uri->segment(3);
		$user        = $this->dtroot;
		$lemburTrans = $this->model_karyawan->getLemburTrans($noSpl);
        $datax       = $this->otherfunctions->convertResultToRowArray($lemburTrans);
        $karyawan=[];
        foreach ($lemburTrans as $lt) {
            $karyawan[]=$lt->id_karyawan;
        }
		if (!isset($datax)){
            redirect('not_found');
        }else{
            $temp=$this->model_master->cekDokumen('DOC_LEMBUR');
            if(empty($temp)){
                $this->messages->sessError('Template Tidak Tersedia.');
                redirect('pages/data_lembur');
            }else{
                if($datax['tgl_mulai'] == $datax['tgl_selesai']){
                    $hari = $this->formatter->getNameOfDay($datax['tgl_mulai']);
                    $tanggal = $this->formatter->getDateMonthFormatUser($datax['tgl_mulai']);
                }else{
                    $hari = $this->formatter->getNameOfDay($datax['tgl_mulai']).' sampai '.$this->formatter->getNameOfDay($datax['tgl_selesai']);
                    $tanggal = $this->formatter->getDateMonthFormatUser($datax['tgl_mulai']).' sampai '.$this->formatter->getDateMonthFormatUser($datax['tgl_selesai']);
                }
                $jam = $this->formatter->timeFormatUser($datax['jam_mulai']).' WIB sampai '.$this->formatter->timeFormatUser($datax['jam_selesai']).' WIB';
                $data=[
                    'name_file'=>'Surat Perintah Lembur '.$datax['no_lembur'],
                    'data'=>[
                        'no_spl'=>$this->otherfunctions->checkContentLetter($datax['no_lembur']),
                        'karyawan'=>$this->otherfunctions->checkContentLetter($this->otherfunctions->getKaryawanViewPrint($karyawan)),
                        'hari'=>$this->otherfunctions->checkContentLetter($hari),
                        'jam'=>$this->otherfunctions->checkContentLetter($jam),
                        'tanggal'=>$this->otherfunctions->checkContentLetter($tanggal),
                        'kegiatan'=>$this->otherfunctions->checkContentLetter($datax['keterangan']),
                        'jenis_lembur'=>$this->otherfunctions->checkContentLetter($this->otherfunctions->getJenisLemburKey($datax['jenis_lembur'])),
                        'kode_customer'=>$this->otherfunctions->checkContentLetter($datax['kode_customer']),
                        'status'=>$this->otherfunctions->checkContentLetter($this->otherfunctions->getStatusIzinRekap($datax['validasi'])),
                        'nama_mengetahui'=>$this->otherfunctions->checkContentLetter($datax['nama_ketahui']),
                        'jabatan_mengetahui'=>$this->otherfunctions->checkContentLetter($datax['jbt_ketahui']),
                        'nama_diperiksa'=>$this->otherfunctions->checkContentLetter($datax['nama_periksa']),
                        'jabatan_diperiksa'=>$this->otherfunctions->checkContentLetter($datax['jbt_periksa']),
                        'nama_pemberi_tugas'=>$this->otherfunctions->checkContentLetter($datax['nama_buat_trans']),
                        'jabatan_pemberi_tugas'=>$this->otherfunctions->checkContentLetter($datax['jbt_buat']),
                    ],
                ];
                $this->wordgenerator->genDoc($data,$temp['file']);
            }
        }
	}
	public function cek_template()
	{
		if (!$this->input->is_ajax_request()) 
		redirect('not_found');
		$usage=$this->uri->segment(3);
		$param=$this->uri->segment(4);
        $id=$this->input->post('id');
		if ($usage == null) {
			echo json_encode($this->messages->notValidParam());
		}else{
			if ($usage == 'lembur') {
			    if ($param == 'cek') {
                    $dtx=$this->model_master->cekDokumen('DOC_LEMBUR');
                    $temp=($dtx == null)?'false':'true';
                    $datax=['temp'=>$temp,];
                    echo json_encode($datax);
                }else{
                    echo json_encode($this->messages->customFailure('Template Tidak Tersedia'));
                }
			}else{
				echo json_encode($this->messages->notValidParam());
			}
		}
	}
}

/* End of file Cetak_word.php */
/* Location: ./application/controllers/Cetak_word.php */