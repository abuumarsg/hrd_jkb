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

class Rekap extends CI_Controller
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
			'level'=>$dtroot['admin']['level'],
			'id_karyawan'=>$dtroot['admin']['id_karyawan'],
			'create'=>$dtroot['admin']['create_date'],
			'update'=>$dtroot['admin']['update_date'],
			'login'=>$dtroot['admin']['last_login'],
		);
		$this->dtroot=$datax;
		$this->dir=$_SERVER['DOCUMENT_ROOT'].'/asset/img/loo.png';
		$this->max_range=$this->otherfunctions->poin_max_range();
		$this->max_month=$this->otherfunctions->column_value_max_range();
	}
	public function index(){
		redirect('pages/dashboard');
	}
// ================================================================ NEW CODE =======================================================//
 	public function export_data_karyawan()
 	{
		$mode = $this->input->get('mode');
		if($mode == 'data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export');
			$unit = $this->input->get('unit_export');
			$bulan = $this->input->get('bulan_export');
			$tahun = $this->input->get('tahun_export');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
			$getdata=$this->model_karyawan->getListKaryawan('search',$where);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Karyawan",
				'subject'=>"Rekap Data Karyawan",
				'description'=>"Rekap Data Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Karyawan",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$awal  = date_create($d->tgl_masuk);
			$akhir = date_create();
			$lama_kerja  = date_diff($awal, $akhir);
			$lahir  = date_create($d->tgl_lahir);
			$usia  = date_diff($lahir, $akhir);
			$body[$row]=[($row-1), $d->id_finger, $d->nik, $d->nama, $d->nama_jabatan,
					$d->nama_level_jabatan,
					$d->nama_bagian,
					$d->nama_level_struktur,
					$d->nama_loker,	$d->tempat_lahir, $this->formatter->getDateFormatUser($d->tgl_lahir),$usia->y.' Tahun',$d->no_hp,
					!empty	($d->status_pajak)?$this->otherfunctions->getStatusPajak($d->status_pajak):null, 
					!empty	($d->npwp)?$d->npwp:null, 
					!empty($d->agama)?$this->otherfunctions->getReligion($d->agama):null, 
					!empty($d->kelamin)?$this->otherfunctions->getGender($d->kelamin):null, 
					!empty($d->nama_grade)?$d->nama_grade.' ('.$d->nama_loker_grade.')':null, $d->npwp, $d->email, ($d->gol_darah=='a'||$d->gol_darah=='b'||$d->gol_darah=='ab'||$d->gol_darah=='o')?$this->otherfunctions->getBlood($d->gol_darah):null, $d->rekening, $d->nama_akun_bank, $d->bpjskes, $d->bpjstk, $d->no_ktp, $d->berat_badan, $d->tinggi_badan, !empty($d->baju)?$this->otherfunctions->getUkuranBaju($d->baju):null, $d->sepatu, $d->pendidikan, $d->universitas, $d->jurusan, $d->nama_status_karyawan, $d->tgl_masuk,
					$lama_kerja->y.' Tahun, '.$lama_kerja->m.' Bulan',
					$d->alamat_asal_jalan, $d->alamat_asal_desa, $d->alamat_asal_kecamatan, $d->alamat_asal_kabupaten, $d->alamat_asal_provinsi, $d->alamat_asal_pos, $d->alamat_sekarang_jalan, $d->alamat_sekarang_desa, $d->alamat_sekarang_kecamatan, $d->alamat_sekarang_kabupaten, $d->alamat_sekarang_provinsi, $d->alamat_sekarang_pos, $d->nama_ibu, $d->nama_ayah, !empty($d->status_nikah)?$this->otherfunctions->getStatusNikah($d->status_nikah):null, $d->nama_pasangan, $d->tanggal_lahir_pasangan, $d->no_hp_pasangan, $d->no_hp_ibu, $d->no_hp_ayah, $d->jumlah_anak,
				];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'ID Finger', 'NIK', 'Nama Karyawan', 'Jabatan',
				'Level Jabatan','Bagian','Level Struktur',
				'Lokasi Kerja', 'Tempat Lahir', 'Tanggal Lahir', 'Usia', 'NO HP', 'Status Pajak', 'NPWP', 'Agama', 'Jenis Kelamin', 'Grade', 'NO NPWP', 'Email', 'Golongan Darah', 'No Rekening', 'Nama Bank', 'BPJS-KES', 'BPJS-TK', 'NO KTP', 'Berat Badan', 'Tinggi Badan', 'Ukuran Baju', 'Ukuran Sepatu', 'Pendidikan', 'Universitas/Sekolah', 'Jurusan', 'Status Karyawan', 'Tanggal Masuk', 'Lama Bekerja', 'Alamat Asal Jalan', 'Alamat Asal Desa', 'Alamat Asal Kecamatan', 'Alamat Asal Kabupaten', 'Alamat Asal Provinsi', 'Alamat Asal Kode Pos', 'Alamat Sekarang Jalan', 'Alamat Sekarang Desa', 'Alamat Sekarang Kecamatan', 'Alamat Sekarang Kabupaten', 'Alamat Sekarang Provinsi', 'Alamat Sekarang Kode Pos', 'Ibu Kandung', 'Ayah Kandung', 'Status Nikah', 'Nama Suami/Istri', 'Tanggal Lahir Suami/Istri', 'NO HP Suami/Istri', 'NO HP Ibu', 'NO HP Ayah', 'Jumlah Anak',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
 		//data tambahan
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $d->nama_ayah, $d->tempat_lahir_ayah.', '.$this->formatter->getDateFormatUser($d->tanggal_lahir_ayah), (!empty($d->pendidikan_terakhir_ayah))?$this->otherfunctions->getEducate($d->pendidikan_terakhir_ayah):$d->pendidikan_terakhir_ayah, $d->no_hp_ayah, $d->alamat_ayah, $d->desa_ayah, $d->kecamatan_ayah, $d->kabupaten_ayah, $d->provinsi_ayah, $d->kode_pos_ayah, $d->nama_ibu, $d->tempat_lahir_ibu.', '.$this->formatter->getDateFormatUser($d->tanggal_lahir_ibu), (!empty($d->pendidikan_terakhir_ibu))?$this->otherfunctions->getEducate($d->pendidikan_terakhir_ibu):$d->pendidikan_terakhir_ibu, $d->no_hp_ibu, $d->alamat_ibu, $d->desa_ibu, $d->kecamatan_ibu, $d->kabupaten_ibu, $d->provinsi_ibu, $d->kode_pos_ibu, $d->nama_pasangan, $d->tempat_lahir_pasangan.', '.$this->formatter->getDateFormatUser($d->tanggal_lahir_pasangan), (!empty($d->pendidikan_terakhir_pasangan))?$this->otherfunctions->getEducate($d->pendidikan_terakhir_pasangan):$d->pendidikan_terakhir_pasangan, $d->no_hp_pasangan, $d->alamat_pasangan, $d->desa_pasangan, $d->kecamatan_pasangan, $d->kabupaten_pasangan, $d->provinsi_pasangan, $d->kode_pos_pasangan,];
			$row++;
		}
		$sheet[1]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Keluarga',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'Nama Ayah', 'TTL Ayah', 'Pendidikan Ayah', 'No HP Ayah', 'Alamat', 'Desa','Kecamatan','Kabupaten','Provinsi','Kode Pos','Nama Ibu','TTL Ibu','Pendidikan Ibu','No HP Ibu','Alamat','Desa','Kecamatan','Kabupaten','Provinsi','Kode Pos','Nama Suami/Istri','TTL Pasangan','Pendidikan Suami/Istri','NO HP Suami/Istri','Alamat', 'Desa','Kecamatan','Kabupaten','Provinsi','Kode Pos',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_anak=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_anak=$this->model_karyawan->getListAnak($d->nik);
			$row_a=2;
			$noa='';
			$anak='';
			$kelamin='';
			$ttl='';
			$pend_anak='';
			$nohp='';
			foreach ($data_anak as $aa) {
				$max_anak=count($data_anak);
				$body_anak[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_anak > ($row_a-1))?"\n":''),
					$anak.=$aa->nama_anak.(($max_anak > ($row_a-1))?"\n":''),
					$kelamin.=$this->otherfunctions->getGender($aa->kelamin_anak).(($max_anak > ($row_a-1))?"\n":''),
					$ttl.=$aa->tempat_lahir_anak.', '.$this->formatter->getDateFormatUser($aa->tanggal_lahir_anak).(($max_anak > ($row_a-1))?"\n":''),
					$pend_anak.=$this->otherfunctions->getEducate($aa->pendidikan_anak).(($max_anak > ($row_a-1))?"\n":''),
					$nohp.=$aa->no_telp.(($max_anak > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $anak, $kelamin, $ttl, $pend_anak, $nohp,
			];
			$row++;
		}
		$sheet[2]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Anak',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Anak', 'Jenis Kelamin', 'Tempat Tanggal Lahir', 'Pendidikan', 'NO HP']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_saudara=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_saudara=$this->model_karyawan->saudara($d->nik);
			$row_a=2;
			$noa='';
			$saudara='';
			$kelamin='';
			$ttl='';
			$pend_saudara='';
			$nohp='';
			foreach ($data_saudara as $aa) {
				$max_saudara=count($data_saudara);
				$body_saudara[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_saudara > ($row_a-1))?"\n":''),
					$saudara.=$aa->nama_saudara.(($max_saudara > ($row_a-1))?"\n":''),
					$kelamin.=$this->otherfunctions->getGender($aa->jenis_kelamin_saudara).(($max_saudara > ($row_a-1))?"\n":''),
					$ttl.=$aa->tempat_lahir_saudara.', '.$this->formatter->getDateFormatUser($aa->tanggal_lahir_saudara).(($max_saudara > ($row_a-1))?"\n":''),
					$pend_saudara.=$this->otherfunctions->getEducate($aa->pendidikan_saudara).(($max_saudara > ($row_a-1))?"\n":''),
					$nohp.=$aa->no_telp_saudara.(($max_saudara > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $saudara, $kelamin, $ttl, $pend_saudara, $nohp,
			];
			$row++;
		}
		$sheet[3]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Saudara',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Saudara', 'Jenis Kelamin', 'Tempat Tanggal Lahir', 'Pendidikan', 'NO HP']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_pendidikan=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_pendidikan=$this->model_karyawan->pendidikan($d->nik);
			$row_a=2;
			$noa='';
			$jenjang_pendidikan='';
			$nama_sekolah='';
			$jurusan='';
			$fakultas='';
			$tahun_masuk='';
			$tahun_keluar='';
			$alamat_sekolah='';
			foreach ($data_pendidikan as $pd) {
				$max_pendidikan=count($data_pendidikan);
				$body_pendidikan[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_pendidikan > ($row_a-1))?"\n":''),
					$jenjang_pendidikan.=$pd->jenjang_pendidikan.(($max_pendidikan > ($row_a-1))?"\n":''),
					$nama_sekolah.=$pd->nama_sekolah.(($max_pendidikan > ($row_a-1))?"\n":''),
					$jurusan.=$pd->jurusan.(($max_pendidikan > ($row_a-1))?"\n":''),
					$fakultas.=$pd->fakultas.(($max_pendidikan > ($row_a-1))?"\n":''),
					$tahun_masuk.=$this->formatter->getDateFormatUser($pd->tahun_masuk).(($max_pendidikan > ($row_a-1))?"\n":''),
					$tahun_keluar.=$this->formatter->getDateFormatUser($pd->tahun_keluar).(($max_pendidikan > ($row_a-1))?"\n":''),
					$alamat_sekolah.=$pd->alamat_sekolah.(($max_pendidikan > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $jenjang_pendidikan, $nama_sekolah, $jurusan, $fakultas, $tahun_masuk, $tahun_keluar, $alamat_sekolah,
			];
			$row++;
		}
		$sheet[4]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Pendidikan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Jenjang Pendidikan', 'Sekolah/Universitas', 'Jurusan', 'Fakultas', 'Tanggal Masuk','Tanggal Keluar','Alamat Pendidikan']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_pnf=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_pnf=$this->model_karyawan->pnf($d->nik);
			$row_a=2;
			$noa='';
			$nama_pnf='';
			$tanggal_masuk_pnf='';
			$sertifikat_pnf='';
			$nama_lembaga_pnf='';
			$alamat_pnf='';
			$keterangan_pnf='';
			foreach ($data_pnf as $pnf) {
				$max_pnf=count($data_pnf);
				$body_pnf[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_pnf > ($row_a-1))?"\n":''),
					$nama_pnf.=$pnf->nama_pnf.(($max_pnf > ($row_a-1))?"\n":''),
					$tanggal_masuk_pnf.=$this->formatter->getDateFormatUser($pnf->tanggal_masuk_pnf).(($max_pnf > ($row_a-1))?"\n":''),
					$sertifikat_pnf.=$pnf->sertifikat_pnf.(($max_pnf > ($row_a-1))?"\n":''),
					$nama_lembaga_pnf.=$pnf->nama_lembaga_pnf.(($max_pnf > ($row_a-1))?"\n":''),
					$alamat_pnf.=$pnf->alamat_pnf.(($max_pnf > ($row_a-1))?"\n":''),
					$keterangan_pnf.=$pnf->keterangan_pnf.(($max_pnf > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $nama_pnf, $tanggal_masuk_pnf, $sertifikat_pnf, $nama_lembaga_pnf, $alamat_pnf, $keterangan_pnf,
			];
			$row++;
		}
		$sheet[5]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Pendidikan Non Formal',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Pendidikan', 'Tanggal Masuk', 'Sertifikat', 'Nama Lembaga', 'Alamat Pendidikan', 'Keterangan',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_award=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_award=$this->model_karyawan->penghargaan($d->nik);
			$row_a=2;
			$noa='';
			$nama_penghargaan='';
			$tahun='';
			$peringkat='';
			$yg_menetapkan='';
			$penyelenggara='';
			$keterangan='';
			foreach ($data_award as $awd) {
				$max_award=count($data_award);
				$body_award[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_award > ($row_a-1))?"\n":''),
					$nama_penghargaan.=$awd->nama_penghargaan.(($max_award > ($row_a-1))?"\n":''),
					$tahun.=$this->formatter->getDateFormatUser($awd->tahun).(($max_award > ($row_a-1))?"\n":''),
					$peringkat.=$awd->peringkat.(($max_award > ($row_a-1))?"\n":''),
					$yg_menetapkan.=$awd->yg_menetapkan.(($max_award > ($row_a-1))?"\n":''),
					$penyelenggara.=$awd->penyelenggara.(($max_award > ($row_a-1))?"\n":''),
					$keterangan.=$awd->keterangan.(($max_award > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $nama_penghargaan, $tahun, $peringkat, $yg_menetapkan, $penyelenggara, $keterangan,
			];
			$row++;
		}
		$sheet[6]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Penghargaan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Penghargaan', 'Tanggal', 'Peringkat', 'Yang Menetapkan', 'Penyelanggara', 'Keterangan',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_org=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_org=$this->model_karyawan->organisasi($d->nik);
			$row_a=2;
			$noa='';
			$nama_organisasi='';
			$tahun_masuk='';
			$tahun_keluar='';
			$jabatan_org='';
			foreach ($data_org as $org) {
				$max_org=count($data_org);
				$body_org[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_org > ($row_a-1))?"\n":''),
					$nama_organisasi.=$org->nama_organisasi.(($max_org > ($row_a-1))?"\n":''),
					$tahun_masuk.=$this->formatter->getDateFormatUser($org->tahun_masuk).(($max_org > ($row_a-1))?"\n":''),
					$tahun_keluar.=$this->formatter->getDateFormatUser($org->tahun_keluar).(($max_org > ($row_a-1))?"\n":''),
					$jabatan_org.=$org->jabatan_org.(($max_org > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $nama_organisasi, $tahun_masuk, $tahun_keluar, $jabatan_org,
			];
			$row++;
		}
		$sheet[7]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Organisasi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Organisasi', 'Tanggal Masuk', 'Tanggal Keluar', 'Jabatan Dalam Organisasi',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_bahasa=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_bahasa=$this->model_karyawan->bahasa($d->nik);
			$row_a=2;
			$noa='';
			$bahasa='';
			$membaca='';
			$menulis='';
			$berbicara='';
			$mendengar='';
			foreach ($data_bahasa as $bhs) {
				$max_bahasa=count($data_bahasa);
				$body_bahasa[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_bahasa > ($row_a-1))?"\n":''),
					$bahasa.=$this->otherfunctions->getBahasa($bhs->bahasa).(($max_bahasa > ($row_a-1))?"\n":''),
					$membaca.=$this->otherfunctions->getRadio($bhs->membaca).(($max_bahasa > ($row_a-1))?"\n":''),
					$menulis.=$this->otherfunctions->getRadio($bhs->menulis).(($max_bahasa > ($row_a-1))?"\n":''),
					$berbicara.=$this->otherfunctions->getRadio($bhs->berbicara).(($max_bahasa > ($row_a-1))?"\n":''),
					$mendengar.=$this->otherfunctions->getRadio($bhs->mendengar).(($max_bahasa > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $bahasa, $membaca, $menulis, $berbicara, $mendengar,
			];
			$row++;
		}
		$sheet[8]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Penguasaan Bahasa',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Bahasa', 'Membaca', 'Menulis', 'Berbicara', 'Mendengar']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		// Riwayat pekerjaan
		$body=[];
		$body_mutasi=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_mutasi=$this->model_karyawan->getListMutasiNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$tgl_mut='';
			$status_mut='';
			$jab_baru_mut='';
			$lok_baru_mut='';
			foreach ($data_mutasi as $aa) {
				$max_mut=count($data_mutasi);
				$body_mutasi[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).' - '.$aa->no_sk.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_mut.=$this->formatter->getDateMonthFormatUser($aa->tgl_sk).(($max_mut > ($row_mut-1))?"\n":''),
					$status_mut.=$aa->nama_status.(($max_mut > ($row_mut-1))?"\n":''),
					$jab_baru_mut.=$aa->nama_jabatan_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$lok_baru_mut.=$aa->nama_loker_baru.(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $tgl_mut, $status_mut, $jab_baru_mut, $lok_baru_mut,
			];
			$row++;
		}
		$sheet[9]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Mutasi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','NO Surat', 'Tanggal Surat', 'Status', 'Jabatan Baru','Lokasi Baru',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_perjanjian=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_perjanjian=$this->model_karyawan->getListPerjanjianKerjaNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$no_sk='';
			$tgl_sk='';
			$n_status='';
			$tgl_brlk='';
			$tgl_smp='';
			foreach ($data_perjanjian as $aa) {
				$max_mut=count($data_perjanjian);
				$body_perjanjian[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).(($max_mut > ($row_mut-1))?"\n":''),
					$no_sk.=$aa->no_sk_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_sk.=$this->formatter->getDateMonthFormatUser($aa->tgl_sk_baru).(($max_mut > ($row_mut-1))?"\n":''),
					$n_status.=$aa->nama_status_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_brlk.=$this->formatter->getDateMonthFormatUser($aa->tgl_berlaku_baru).(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_smp.=$this->formatter->getDateMonthFormatUser($aa->berlaku_sampai_baru).(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $no_sk, $tgl_sk, $n_status, $tgl_brlk, $tgl_smp,
			];
			$row++;
		}
		$sheet[10]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Perjanjian Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','No', 'NO Surat', 'Tanggal Surat', 'Status', 'Tanggal Berlaku','Berlaku Sampai']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_peringatan=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_peringatan=$this->model_karyawan->getListPeringatanNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$no_sk='';
			$tgl_sk='';
			$n_status='';
			$tgl_brlk='';
			$tgl_smp='';
			foreach ($data_peringatan as $aa) {
				$max_mut=count($data_peringatan);
				$body_peringatan[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).(($max_mut > ($row_mut-1))?"\n":''),
					$no_sk.=$aa->no_sk.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_sk.=$this->formatter->getDateMonthFormatUser($aa->tgl_sk).(($max_mut > ($row_mut-1))?"\n":''),
					$n_status.=$aa->nama_status_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_brlk.=$this->formatter->getDateMonthFormatUser($aa->tgl_berlaku).(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_smp.=$this->formatter->getDateMonthFormatUser($aa->berlaku_sampai).(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $no_sk, $tgl_sk, $n_status, $tgl_brlk, $tgl_smp,
			];
			$row++;
		}
		$sheet[11]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Peringatan Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','No', 'NO Surat', 'Tanggal Surat', 'Status', 'Tanggal Berlaku','Berlaku Sampai']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_grade=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_grade=$this->model_karyawan->getListGradeNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$no_sk='';
			$tgl_sk='';
			$n_status='';
			$tgl_brlk='';
			foreach ($data_grade as $aa) {
				$max_mut=count($data_grade);
				$body_grade[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).(($max_mut > ($row_mut-1))?"\n":''),
					$no_sk.=$aa->no_sk.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_sk.=$this->formatter->getDateMonthFormatUser($aa->tgl_sk).(($max_mut > ($row_mut-1))?"\n":''),
					$n_status.=$aa->nama_grade_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_brlk.=$this->formatter->getDateMonthFormatUser($aa->tgl_berlaku).(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $no_sk, $tgl_sk, $n_status, $tgl_brlk,
			];
			$row++;
		}
		$sheet[12]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Grade Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','No', 'NO Surat', 'Tanggal Surat', 'Status', 'Tanggal Berlaku']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_kecelakaan=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_kecelakaan=$this->model_karyawan->getListKecelakaanKerjaNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$no_sk='';
			$tgl_sk='';
			$n_status='';
			$tgl_brlk='';
			foreach ($data_kecelakaan as $aa) {
				$max_mut=count($data_kecelakaan);
				$body_kecelakaan[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).(($max_mut > ($row_mut-1))?"\n":''),
					$no_sk.=$aa->no_sk.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_sk.=$this->formatter->getDateMonthFormatUser($aa->tgl).(($max_mut > ($row_mut-1))?"\n":''),
					$n_status.=$aa->nama_kategori_kecelakaan.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_brlk.=$aa->nama_rs.(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $no_sk, $tgl_sk, $n_status, $tgl_brlk,
			];
			$row++;
		}
		$sheet[13]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Kecelakaan Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','No', 'NO Surat', 'Tanggal Surat', 'Kategori Kecelakaan', 'Rumah Sakit']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
 	}
	public function export_data_karyawan_thr()
 	{
		$param = $this->input->get('param');
		$usage=($param == 'all')?'all':'search';
		$bagian = $this->input->get('bagian_export');
		$unit = $this->input->get('unit_export');
		$bulan = $this->input->get('bulan_export');
		$tahun = $this->input->get('tahun_export');
		$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
		$getdata=$this->model_karyawan->getListKaryawan('search',$where);
		$user = $this->dtroot;
		$data['properties']=[
			'title'=>"Rekap Data Karyawan",
			'subject'=>"Rekap Data Karyawan",
			'description'=>"Rekap Data Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Rekap, Export, Karyawan",
			'category'=>"Export",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		// foreach ($getdata as $d) {
		// 	$awal  = date_create($d->tgl_masuk);
		// 	$akhir = date_create();
		// 	$lama_kerja  = date_diff($awal, $akhir);
		// 	$body[$row]=[
		// 		($row-1), 
		// 		$d->nama,
		// 		$d->nama_jabatan,
		// 		$d->nama_loker,
		// 		$this->formatter->getDateFormatUser($d->tgl_masuk),
		// 		$lama_kerja->y.' Tahun, '.$lama_kerja->m.' Bulan',
		// 		$d->gaji,
		// 		$d->gaji_bpjs,
		// 		$d->gaji_bpjs_tk,
		// 		$d->rekening,
		// 	];
		// 	$row++;
		// }
		$body_group=[];
		if ($getdata){
			foreach ($getdata as $d) {
				$awal  = date_create($d->tgl_masuk);
				$akhir = date_create();
				$lama_kerja  = date_diff($awal, $akhir);
				$keys = $d->nama_bagian.' ('.$d->nama_loker.')';
				$body_group[$keys][]=[
					// ($row-1), 
					$d->nama,
					$d->nama_jabatan,
					$d->nama_loker,
					$this->formatter->getDateFormatUser($d->tgl_masuk),
					$lama_kerja->y.' Tahun, '.$lama_kerja->m.' Bulan',
					floor($d->gaji),
					floor($d->gaji_bpjs),
					floor($d->gaji_bpjs_tk),
					$d->rekening,
				];
				$row++;
			}
		}
		if ($body_group){
			$row=2;
			foreach ($body_group as $nama_d => $val) {
				$count_dept=count($val);
				if ($val) {
					$row_dept=0;
					foreach ($val as $v){
						if ($row==2) {
							array_push($body,['#',$nama_d]);
							$body[2]=$body[0];
							unset($body[0]);
							$row++;
						}elseif (($count_dept-$row_dept) == $count_dept) {
							array_push($body,['#',$nama_d]);
							$row++;
						}
						$body[$row]=$v;
						$row++;
						$row_dept++;
					}
				}
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'Nama', 'Jabatan', 'Lokasi Kerja', 'Tanggal Masuk', 'Masa Kerja', 'Gaji Pokok', 'Gaji BPJS KES', 'Gaji BPJS TK', 'No Rekening',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	function export_template_karyawan(){
		$data['properties']=[
			'title'=>"Template Import Data Karyawan",
			'subject'=>"Template Import Data Karyawan",
			'description'=>"Template Import Data Karyawan HSOFT JKB",
			'keywords'=>"Template, Export, Template Karyawan",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'NIK','Fingerprint Code','Nama Karyawan','Tempat Lahir','Tanggal Lahir (yyyy-mm-dd)','Nomor Ponsel','Status Pajak','Agama','Jenis Kelamin (l/p)','Kode Jabatan (Lihat Master Jabatan)','Kode Lokasi Kerja (Lihat Master Lokasi Kerja)','Kode Grade (Lihat Master Grade)','Nomor NPWP','Email','Golongan Darah','Nama Bank','Nomor Rekening','Nomor BPJS-KES','Nomor BPJS-TK','Nomor KTP','Berat Badan','Tinggi Badan','Kode Status Karyawan (Master Status Karyawan)','Tanggal Masuk (yyyy-mm-dd)','Alamat Asal (Jalan)','Alamat Asal (Desa)','Alamat Asal (Kecamatan)','Alamat Asal (Kabupaten/Kota)','Alamat Asal (Provinsi)','Alamat Asal (Kode Pos)','Alamat Sekarang (Jalan)','Alamat Sekarang (Desa)','Alamat Sekarang (Kecamatan)','Alamat Sekarang (Kabupaten/Kota)','Alamat Sekarang (Provinsi)','Alamat Sekarang (Kode Pos)','Nama Ayah','Tempat Lahir Ayah','Tanggal Lahir Ayah (yyyy-mm-dd)','Pendidikan Ayah','No Ponsel Ayah','Alamat Ayah (Jalan)','Alamat Ayah (Desa)','Alamat Ayah (Kecamatan)','Alamat Ayah (Kabupaten)','Alamat Ayah (Provinsi)','Alamat Ayah (Kode Pos)','Nama Ibu','Tempat Lahir Ibu','Tanggal Lahir Ibu (yyyy-mm-dd)','Pendidikan Ibu','No Ponsel Ibu','Alamat Ibu (Jalan)','Alamat Ibu (Desa)','Alamat Ibu (Kecamatan)','Alamat Ibu (Kabupaten)','Alamat Ibu (Provinsi)','Alamat Ibu (Kode Pos)','Nama Suami/Istri','Tempat Lahir Suami/Istri','Tanggal Lahir Suami/Istri (yyyy-mm-dd)','Pendidikan Suami/Istri','No Ponsel Suami/Istri','Alamat Suami/Istri (Jalan)','Alamat Suami/Istri (Desa)','Alamat Suami/Istri (Kecamatan)','Alamat Suami/Istri (Kabupaten)','Alamat Suami/Istri (Provinsi)','Alamat Suami/Istri (Kode Pos)','Status Nikah','Pendidikan Terakhir'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	public function biodata_lengkap()
 	{
        $nik_e=$this->uri->segment(3);
		$nik=$this->codegenerator->decryptChar($nik_e);
		$emp=$this->model_karyawan->getEmployeeNik($nik);
		echo '<pre>';
		print_r($emp);
		// $getdata=$this->model_karyawan->getListDataPerjanjianKerja('search',$where,'rekap');
		// $user = $this->dtroot;
		// $data['properties']=[
		// 	'title'=>"Rekap Data Perjanjian Kerja",
		// 	'subject'=>"Rekap Data Perjanjian Kerja",
		// 	'description'=>"Rekap Data Perjanjian Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
		// 	'keywords'=>"Rekap, Export, Perjanjian, Perjanjian Kerja",
		// 	'category'=>"Export",
		// ];
		// $body=[];
		// $row_body=2;
		// $row=$row_body;
		// foreach ($getdata as $d) {
		// 	$nextData=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getPerjanjianKerja($d->id_p_kerja));
		// 	$jbt_mengetahui=(!empty($nextData['jbt_mengetahui'])) ? ' - '.$nextData['jbt_mengetahui'] : null;
		// 	$jbt_menyetujui=(!empty($nextData['jbt_menyetujui'])) ? ' - '.$nextData['jbt_menyetujui'] : null;
		// 	$tgl_sampai_baru = (!empty($d->berlaku_sampai_baru))? ' - '.$this->formatter->getDateMonthFormatUser($d->berlaku_sampai_baru) : null;
		// 	$tgl_sampai_lama = (!empty($d->berlaku_sampai_lama))? ' - '.$this->formatter->getDateMonthFormatUser($d->berlaku_sampai_lama) : null;

		// 	$body[$row]=[
		// 		($row-1),
		// 		$d->nik,
		// 		$d->nama_karyawan,
		// 		$d->no_sk_baru,
		// 		$this->formatter->getDateMonthFormatUser($d->tgl_sk_baru),
		// 		$d->nama_status_baru,
		// 		$this->formatter->getDateMonthFormatUser($d->tgl_berlaku_baru).$tgl_sampai_baru,
		// 		$d->nama_status_lama,
		// 		$d->no_sk_lama,
		// 		$this->formatter->getDateMonthFormatUser($d->tgl_berlaku_lama).$tgl_sampai_lama,
		// 		$nextData['nama_mengetahui'].$jbt_mengetahui,
		// 		$nextData['nama_menyetujui'].$jbt_menyetujui,
		// 		$d->keterangan
		// 	];
		// 	$row++;
		// }
		// $sheet[0]=[
		// 	'range_huruf'=>3,
		// 	'sheet_title'=>'Data Perjanjian Kerja',
		// 	'head'=>[
		// 		'row_head'=>1,
		// 		'data_head'=>[
		// 			'No',
		// 			'NIK',
		// 			'Nama',
		// 			'Nomor SK',
		// 			'Tanggal SK',
		// 			'Perjanjian',
		// 			'Tanggal Berlaku',
		// 			'Perjanjian Lama',
		// 			'Nomor SK Lama',
		// 			'Tanggal SK Lama',
		// 			'Mengetahui',
		// 			'Menyetujui',
		// 			'Keterangan'
		// 		],
		// 	],
		// 	'body'=>[
		// 		'row_body'=>$row_body,
		// 		'data_body'=>$body
		// 	],
		// ];
		// $data['data']=$sheet;
		// $this->rekapgenerator->genExcel($data);
 	}
	public function import_data_karyawan()
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
				'table'=>'karyawan',
				'column_code'=>'nik',
				// 'usage'=>'',
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				//urutan sama dengan export
				'column'=>[
					0=>'nik',1=>'id_finger',2=>'nama',3=>'tempat_lahir',4=>'tgl_lahir',5=>'no_hp',6=>'status_pajak',7=>'agama',8=>'kelamin',9=>'jabatan',10=>'loker',11=>'grade',12=>'npwp',13=>'email',14=>'gol_darah',15=>'nama_bank',16=>'rekening',17=>'bpjskes',18=>'bpjstk',19=>'no_ktp',20=>'berat_badan',21=>'tinggi_badan',22=>'status_karyawan',23=>'tgl_masuk',24=>'alamat_asal_jalan',25=>'alamat_asal_desa',26=>'alamat_asal_kecamatan',27=>'alamat_asal_kabupaten',28=>'alamat_asal_provinsi',29=>'alamat_asal_pos',30=>'alamat_sekarang_jalan',31=>'alamat_sekarang_desa',32=>'alamat_sekarang_kecamatan',33=>'alamat_sekarang_kabupaten',34=>'alamat_sekarang_provinsi',35=>'alamat_sekarang_pos',35=>'nama_ayah',36=>'tempat_lahir_ayah',37=>'tanggal_lahir_ayah',38=>'pendidikan_terakhir_ayah',39=>'no_hp_ayah',40=>'alamat_ayah',41=>'desa_ayah',42=>'kecamatan_ayah',43=>'kabupaten_ayah',44=>'provinsi_ayah',45=>'kode_pos_ayah',46=>'nama_ibu',47=>'tempat_lahir_ibu',48=>'tanggal_lahir_ibu',49=>'pendidikan_terakhir_ibu',50=>'no_hp_ibu',51=>'alamat_ibu',52=>'desa_ibu',53=>'kecamatan_ibu',54=>'kabupaten_ibu',55=>'provinsi_ibu',56=>'kode_pos_ibu',57=>'nama_pasangan',58=>'tempat_lahir_pasangan',59=>'tanggal_lahir_pasangan',60=>'pendidikan_terakhir_pasangan',61=>'no_hp_pasangan',62=>'alamat_pasangan',63=>'desa_pasangan',64=>'kecamatan_pasangan',65=>'kabupaten_pasangan',66=>'provinsi_pasangan',67=>'kode_pos_pasangan',68=>'status_nikah',69=>'pendidikan',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	public function data_mutasi(){
		$mode = $this->input->get('mode');
		if($mode == 'data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export');
			$unit = $this->input->get('unit_export');
			$bulan = $this->input->get('bulan_export');
			$tahun = $this->input->get('tahun_export');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
			$getdata=$this->model_karyawan->getListDataMutasi('search',$where,'rekap');
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Mutasi Karyawan",
				'subject'=>"Rekap Data Mutasi Karyawan",
				'description'=>"Rekap Data Mutasi Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Mutasi, Mutasi Karyawan",
				'category'=>"Export",
			];
		}else{
	 		$nik = $this->input->post('nik');
	 		$where = ['param'=>'nik','nik'=>$nik];
	 		$getdata=$this->model_karyawan->getListDataMutasi('search',$where,'rekap');
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Mutasi Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Mutasi Kerja",
				'description'=>"Rekap Data Mutasi Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Mutasi, Mutasi Kerja",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$jbt_mengetahui=(!empty($d->jbt_mengetahui)) ? ' - '.$d->jbt_mengetahui : null;
			$jbt_menyetujui=(!empty($d->jbt_menyetujui)) ? ' - '.$d->jbt_menyetujui : null;
			$body[$row]=[
				($row-1),
				$d->nik_karyawan,
				$d->nama_karyawan,
				$d->no_sk,
				$this->formatter->getDateMonthFormatUser($d->tgl_sk),
				$d->nama_status,
				$d->nama_jabatan,
				$d->nama_jabatan_baru,
				$d->nama_loker,
				$d->nama_loker_baru,
				$d->lama_percobaan,
				$d->nama_mengetahui.$jbt_mengetahui,
				$d->nama_menyetujui.$jbt_menyetujui,
				$d->keterangan,
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>"Data_Mutasi",
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NIK','Nama','Nomor SK','Tanggal SK','Status','Jabatan Lama','Jabatan baru','Lokasi Lama','Lokasi Baru','Masa Percobaan','Mengetahui','Menyetujui','Keterangan',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	public function export_perjanjian_kerja()
 	{
		$mode = $this->input->get('mode');
		if($mode=='data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export');
			$unit = $this->input->get('unit_export');
			$bulan = $this->input->get('bulan_export');
			$tahun = $this->input->get('tahun_export');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
			$getdata=$this->model_karyawan->getListDataPerjanjianKerja('search',$where,'rekap');
			$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Perjanjian Kerja",
				'subject'=>"Rekap Data Perjanjian Kerja",
				'description'=>"Rekap Data Perjanjian Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Perjanjian, Perjanjian Kerja",
				'category'=>"Export",
			];
		}else{
	 		$nik = $this->input->post('nik');
	 		$where = ['param'=>'nik','nik'=>$nik];
	 		$getdata=$this->model_karyawan->getListDataPerjanjianKerja('search',$where,'rekap');
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Mutasi Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Mutasi Kerja",
				'description'=>"Rekap Data Mutasi Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Mutasi, Mutasi Kerja",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$nextData=$this->otherfunctions->convertResultToRowArray($this->model_karyawan->getPerjanjianKerja($d->id_p_kerja));
			$jbt_mengetahui=(!empty($nextData['jbt_mengetahui'])) ? ' - '.$nextData['jbt_mengetahui'] : null;
			$jbt_menyetujui=(!empty($nextData['jbt_menyetujui'])) ? ' - '.$nextData['jbt_menyetujui'] : null;
			$tgl_sampai_baru = (!empty($d->berlaku_sampai_baru))? ' - '.$this->formatter->getDateMonthFormatUser($d->berlaku_sampai_baru) : null;
			$tgl_sampai_lama = (!empty($d->berlaku_sampai_lama))? ' - '.$this->formatter->getDateMonthFormatUser($d->berlaku_sampai_lama) : null;

			$body[$row]=[
				($row-1),
				$d->nik,
				$d->nama_karyawan,
				$d->no_sk_baru,
				$this->formatter->getDateMonthFormatUser($d->tgl_sk_baru),
				$d->nama_status_baru,
				$this->formatter->getDateMonthFormatUser($d->tgl_berlaku_baru).$tgl_sampai_baru,
				$d->nama_status_lama,
				$d->no_sk_lama,
				$this->formatter->getDateMonthFormatUser($d->tgl_berlaku_lama).$tgl_sampai_lama,
				$nextData['nama_mengetahui'].$jbt_mengetahui,
				$nextData['nama_menyetujui'].$jbt_menyetujui,
				$d->keterangan
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Perjanjian Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Nomor SK',
					'Tanggal SK',
					'Perjanjian',
					'Tanggal Berlaku',
					'Perjanjian Lama',
					'Nomor SK Lama',
					'Tanggal SK Lama',
					'Mengetahui',
					'Menyetujui',
					'Keterangan'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
 	}
 	public function export_data_peringatan()
 	{
		$mode = $this->input->get('mode');
		if($mode=='data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export');
			$unit = $this->input->get('unit_export');
			$bulan = $this->input->get('bulan_export');
			$tahun = $this->input->get('tahun_export');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
			$getdata=$this->model_karyawan->getListDataPeringatan('search',$where,'rekap');
			$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Peringatan Kerja",
				'subject'=>"Rekap Data Peringatan Kerja",
				'description'=>"Rekap Data Peringatan Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Peringatan, Peringatan Kerja",
				'category'=>"Export",
			];
		}else{
			$nik = $this->input->post('nik');
	 		$where = ['param'=>'nik','nik'=>$nik];
	 		$getdata=$this->model_karyawan->getListDataPeringatan('search',$where,'rekap');
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Peringatan Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Peringatan Kerja",
				'description'=>"Rekap Data Peringatan Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Peringatan, Peringatan Kerja",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$jbt_mengetahui=(!empty($d->jbt_mengetahui)) ? ' - '.$d->jbt_mengetahui : null;
			$jbt_menyetujui=(!empty($d->jbt_menyetujui)) ? ' - '.$d->jbt_menyetujui : null;
			$tgl_sampai = (!empty($d->berlaku_sampai))? ' - '.$this->formatter->getDateMonthFormatUser($d->berlaku_sampai) : null;

			$body[$row]=[
				($row-1),
				$d->nik_karyawan,
				$d->nama_karyawan,
				$d->nama_loker,
				$d->nama_jabatan,
				$d->no_sk,
				$this->formatter->getDateMonthFormatUser($d->tgl_sk),
				$d->nama_status_baru,
				$this->formatter->getDateMonthFormatUser($d->tgl_berlaku).$tgl_sampai,
				$d->nama_mengetahui.$jbt_mengetahui,
				$d->nama_menyetujui.$jbt_menyetujui,
				$d->keterangan
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Peringatan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Lokasi Kerja',
					'Jabatan',
					'Nomor SK',
					'Tanggal SK',
					'Peringatan',
					'Tanggal Berlaku',
					'Mengetahui',
					'Menyetujui',
					'Keterangan'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
 	}
 	public function search_data_grade()
 	{
		$mode = $this->input->get('mode');
		if($mode=='data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export');
			$unit = $this->input->get('unit_export');
			$bulan = $this->input->get('bulan_export');
			$tahun = $this->input->get('tahun_export');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
			$getdata=$this->model_karyawan->getListGrade('search',$where,'rekap');
			$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Grade Karyawan",
				'subject'=>"Rekap Data Grade Karyawan",
				'description'=>"Rekap Data Grade Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Grade, Grade Karyawan",
				'category'=>"Export",
			];
		}else{
			$nik = $this->input->post('nik');
	 		$where = ['param'=>'nik','nik'=>$nik];
	 		$getdata=$this->model_karyawan->getListGrade('search',$where,'rekap');
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Grade Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Grade Kerja",
				'description'=>"Rekap Data Grade Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Grade, Grade Kerja",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$jbt_mengetahui=(!empty($d->jbt_mengetahui)) ? ' - '.$d->jbt_mengetahui : null;
			$jbt_menyetujui=(!empty($d->jbt_menyetujui)) ? ' - '.$d->jbt_menyetujui : null;

			$body[$row]=[
				($row-1),
				$d->nik_karyawan,
				$d->nama_karyawan,
				$d->no_sk,
				$this->formatter->getDateMonthFormatUser($d->tgl_sk),
				$this->formatter->getDateMonthFormatUser($d->tgl_berlaku),
				$d->nama_grade_lama.' ('.$d->nama_loker_grade_lama.')',
				$d->nama_grade_baru.' ('.$d->nama_loker_grade.')',
				$d->nama_mengetahui.$jbt_mengetahui,
				$d->nama_menyetujui.$jbt_menyetujui,
				$d->keterangan
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Grade',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Nomor SK',
					'Tanggal SK',
					'Tanggal Berlaku',
					'Grade Sebelumnya',
					'Grade Baru',
					'Mengetahui',
					'Menyetujui',
					'Keterangan'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
 	}
 	public function view_kecelakaan_kerja()
 	{
		$mode = $this->input->get('mode');
		if($mode == 'data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export');
			$unit = $this->input->get('unit_export');
			$bulan = $this->input->get('bulan_export');
			$tahun = $this->input->get('tahun_export');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
			$getdata=$this->model_karyawan->getListKecelakaanKerja('search',$where,'rekap');
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Grade Karyawan",
				'subject'=>"Rekap Data Grade Karyawan",
				'description'=>"Rekap Data Grade Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Grade, Grade Karyawan",
				'category'=>"Export",
			];
		}else{
	 		$nik = $this->input->post('nik');
	 		$where = ['param'=>'nik','nik'=>$nik];
	 		$getdata=$this->model_karyawan->getListKecelakaanKerja('search',$where,'rekap');
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Peringatan Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Peringatan Kerja",
				'description'=>"Rekap Data Peringatan Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Peringatan, Peringatan Kerja",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$jbt_mengetahui=(!empty($d->jbt_mengetahui)) ? ' - '.$d->jbt_mengetahui : null;
			$jbt_menyatakan=(!empty($d->jbt_menyatakan)) ? ' - '.$d->jbt_menyatakan : null;
			$jbt_saksi_1=(!empty($d->jbt_saksi_1)) ? ' - '.$d->jbt_saksi_1 : null;
			$jbt_saksi_2=(!empty($d->jbt_saksi_2)) ? ' - '.$d->jbt_saksi_2 : null;
			$jbt_penanggungjawab=(!empty($d->jbt_penanggungjawab)) ? ' - '.$d->jbt_penanggungjawab : null;

			$body[$row]=[
				($row-1),
				$d->nik_karyawan, $d->nama_karyawan, $d->nama_jabatan, $d->nama_loker, $d->no_sk, $this->formatter->getDateTimeMonthFormatUser($d->tgl.' '.$d->jam), $d->nama_loker_kejadian, $d->nama_kategori_kecelakaan, $d->nama_rumahsakit, $d->kejadian, $d->alat, $d->bagian_tubuh, $d->nama_mengetahui.$jbt_mengetahui, $d->nama_menyatakan.$jbt_menyatakan, $d->nama_saksi_1.$jbt_saksi_1, $d->nama_saksi_2.$jbt_saksi_2, $d->nama_penanggungjawab.$jbt_penanggungjawab, $this->formatter->getDateMonthFormatUser($d->tgl_cetak), $d->tembusan, $d->keterangan,
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Kecelakaan Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'Nomor Kecelakaan', 'Tanggal Kejadian', 'Lokasi kejadian', 'Kategori Kecelakaan', 'Rumah Sakit', 'Kronologi Kejadian', 'Alat', 'Bagian Tubuh Terluka', 'Mengetahui', 'Menyatakan', 'Saksi 1', 'Saksi 2', 'Penanggung Jawab', 'Tanggal Cetak', 'Tembusan', 'Keterangan',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
 	}
 	public function view_karyawan_non_aktif()
 	{
 		$param = $this->input->get('param');
		$usage=($param == 'all')?'all':'search';
		$bagian = $this->input->get('bagian_export');
		$unit = $this->input->get('unit_export');
		$bulan = $this->input->get('bulan_export');
		$tahun = $this->input->get('tahun_export');
		$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
		$getdata_non=$this->model_karyawan->getListKaryawanNonAktif('search',$where);
 		$user = $this->dtroot;
		$data['properties']=[
			'title'=>"Rekap Data Karyawan Tidak Aktif",
			'subject'=>"Rekap Data Karyawan Tidak Aktif",
			'description'=>"Rekap Data Karyawan Tidak Aktif HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Rekap, Export, Tidak Aktif, Karyawan Tidak Aktif",
			'category'=>"Export",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata_non as $d) {
			$jbt_mengetahui=(!empty($d->jbt_mengetahui)) ? ' - '.$d->jbt_mengetahui : null;
			$jbt_menyetujui=(!empty($d->jbt_menyetujui)) ? ' - '.$d->jbt_menyetujui : null;

			$body[$row]=[
				($row-1),
				$d->nik_karyawan,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$d->nama_loker,
				$d->no_sk,
				$this->formatter->getDateMonthFormatUser($d->tgl_sk),
				$this->formatter->getDateMonthFormatUser($d->tgl_berlaku),
				$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
				$this->formatter->getDateMonthFormatUser($d->tgl_keluar),
				$this->model_master->getAlasanKeluarKode($d->alasan)['nama'],
				$d->nama_mengetahui.$jbt_mengetahui,
				$d->nama_menyetujui.$jbt_menyetujui,
				$d->keterangan
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Karyawan Non Aktif',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Jabatan',
					'Lokasi Kerja',
					'No SK',
					'Tanggal SK',
					'Tanggal Berlaku',
					'Tanggal Masuk',
					'Tanggal Keluar',
					'Alasan Keluar',
					'Mengetahui',
					'Menyetujui',
					'Keterangan'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$getdata=$this->model_karyawan->getListKaryawanNonAktifAll('search',$where);
		foreach ($getdata as $d) {
			$awal  = date_create($d->tgl_masuk);
			$akhir = date_create();
			$lama_kerja  = date_diff($awal, $akhir);
			$lahir  = date_create($d->tgl_lahir);
			$usia  = date_diff($lahir, $akhir);
			$body[$row]=[
					($row-1), $d->id_finger, $d->nik, $d->nama,$d->nama_jabatan,
					$d->nama_level_jabatan,$d->nama_bagian,$d->nama_level_struktur,
					$d->nama_loker,	$d->tempat_lahir, $this->formatter->getDateFormatUser($d->tgl_lahir),$usia->y.' Tahun',$d->no_hp,
					!empty	($d->status_pajak)?$this->otherfunctions->getStatusPajak($d->status_pajak):null, 
					!empty($d->agama)?$this->otherfunctions->getReligion($d->agama):null, 
					!empty($d->kelamin)?$this->otherfunctions->getGender($d->kelamin):null, 
					!empty($d->nama_grade)?$d->nama_grade.' ('.$d->nama_loker_grade.')':null, $d->npwp, $d->email, ($d->gol_darah=='a'||$d->gol_darah=='b'||$d->gol_darah=='ab'||$d->gol_darah=='o')?$this->otherfunctions->getBlood($d->gol_darah):null, $d->rekening, $d->nama_akun_bank, $d->bpjskes, $d->bpjstk, $d->no_ktp, $d->berat_badan, $d->tinggi_badan, !empty($d->baju)?$this->otherfunctions->getUkuranBaju($d->baju):null, $d->sepatu, $d->pendidikan, $d->universitas, $d->jurusan, $d->nama_status_karyawan, $d->tgl_masuk,
					$lama_kerja->y.' Tahun, '.$lama_kerja->m.' Bulan',
					$d->alamat_asal_jalan, $d->alamat_asal_desa, $d->alamat_asal_kecamatan, $d->alamat_asal_kabupaten, $d->alamat_asal_provinsi, $d->alamat_asal_pos, $d->alamat_sekarang_jalan, $d->alamat_sekarang_desa, $d->alamat_sekarang_kecamatan, $d->alamat_sekarang_kabupaten, $d->alamat_sekarang_provinsi, $d->alamat_sekarang_pos, $d->nama_ibu, $d->nama_ayah, !empty($d->status_nikah)?$this->otherfunctions->getStatusNikah($d->status_nikah):null, $d->nama_pasangan, $d->tanggal_lahir_pasangan, $d->no_hp_pasangan, $d->no_hp_ibu, $d->no_hp_ayah, $d->jumlah_anak,
				];
			$row++;
		}
		$sheet[1]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'ID Finger', 'NIK', 'Nama Karyawan', 'Jabatan',
				'Level Jabatan','Nama Bagian','Level Struktur',
				'Lokasi Kerja', 'Tempat Lahir', 'Tanggal Lahir', 'Usia', 'NO HP', 'Status Pajak', 'Agama', 'Jenis Kelamin', 'Grade', 'NO NPWP', 'Email', 'Golongan Darah', 'No Rekening', 'Nama Bank', 'BPJS-KES', 'BPJS-TK', 'NO KTP', 'Berat Badan', 'Tinggi Badan', 'Ukuran Baju', 'Ukuran Sepatu', 'Pendidikan', 'Universitas/Sekolah', 'Jurusan', 'Status Karyawan', 'Tanggal Masuk', 'Lama Bekerja', 'Alamat Asal Jalan', 'Alamat Asal Desa', 'Alamat Asal Kecamatan', 'Alamat Asal Kabupaten', 'Alamat Asal Provinsi', 'Alamat Asal Kode Pos', 'Alamat Sekarang Jalan', 'Alamat Sekarang Desa', 'Alamat Sekarang Kecamatan', 'Alamat Sekarang Kabupaten', 'Alamat Sekarang Provinsi', 'Alamat Sekarang Kode Pos', 'Ibu Kandung', 'Ayah Kandung', 'Status Nikah', 'Nama Suami/Istri', 'Tanggal Lahir Suami/Istri', 'NO HP Suami/Istri', 'NO HP Ibu', 'NO HP Ayah', 'Jumlah Anak',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
 		//data tambahan
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $d->nama_ayah, $d->tempat_lahir_ayah.', '.$this->formatter->getDateFormatUser($d->tanggal_lahir_ayah), (!empty($d->pendidikan_terakhir_ayah))?$this->otherfunctions->getEducate($d->pendidikan_terakhir_ayah):$d->pendidikan_terakhir_ayah, $d->no_hp_ayah, $d->alamat_ayah, $d->desa_ayah, $d->kecamatan_ayah, $d->kabupaten_ayah, $d->provinsi_ayah, $d->kode_pos_ayah, $d->nama_ibu, $d->tempat_lahir_ibu.', '.$this->formatter->getDateFormatUser($d->tanggal_lahir_ibu), (!empty($d->pendidikan_terakhir_ibu))?$this->otherfunctions->getEducate($d->pendidikan_terakhir_ibu):$d->pendidikan_terakhir_ibu, $d->no_hp_ibu, $d->alamat_ibu, $d->desa_ibu, $d->kecamatan_ibu, $d->kabupaten_ibu, $d->provinsi_ibu, $d->kode_pos_ibu, $d->nama_pasangan, $d->tempat_lahir_pasangan.', '.$this->formatter->getDateFormatUser($d->tanggal_lahir_pasangan), (!empty($d->pendidikan_terakhir_pasangan))?$this->otherfunctions->getEducate($d->pendidikan_terakhir_pasangan):$d->pendidikan_terakhir_pasangan, $d->no_hp_pasangan, $d->alamat_pasangan, $d->desa_pasangan, $d->kecamatan_pasangan, $d->kabupaten_pasangan, $d->provinsi_pasangan, $d->kode_pos_pasangan,];
			$row++;
		}
		$sheet[2]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Keluarga',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'Nama Ayah', 'TTL Ayah', 'Pendidikan Ayah', 'No HP Ayah', 'Alamat', 'Desa','Kecamatan','Kabupaten','Provinsi','Kode Pos','Nama Ibu','TTL Ibu','Pendidikan Ibu','No HP Ibu','Alamat','Desa','Kecamatan','Kabupaten','Provinsi','Kode Pos','Nama Suami/Istri','TTL Pasangan','Pendidikan Suami/Istri','NO HP Suami/Istri','Alamat', 'Desa','Kecamatan','Kabupaten','Provinsi','Kode Pos',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_anak=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_anak=$this->model_karyawan->getListAnak($d->nik);
			$row_a=2;
			$noa='';
			$anak='';
			$kelamin='';
			$ttl='';
			$pend_anak='';
			$nohp='';
			foreach ($data_anak as $aa) {
				$max_anak=count($data_anak);
				$body_anak[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_anak > ($row_a-1))?"\n":''),
					$anak.=$aa->nama_anak.(($max_anak > ($row_a-1))?"\n":''),
					$kelamin.=$this->otherfunctions->getGender($aa->kelamin_anak).(($max_anak > ($row_a-1))?"\n":''),
					$ttl.=$aa->tempat_lahir_anak.', '.$this->formatter->getDateFormatUser($aa->tanggal_lahir_anak).(($max_anak > ($row_a-1))?"\n":''),
					$pend_anak.=$this->otherfunctions->getEducate($aa->pendidikan_anak).(($max_anak > ($row_a-1))?"\n":''),
					$nohp.=$aa->no_telp.(($max_anak > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $anak, $kelamin, $ttl, $pend_anak, $nohp,
			];
			$row++;
		}
		$sheet[3]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Anak',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Anak', 'Jenis Kelamin', 'Tempat Tanggal Lahir', 'Pendidikan', 'NO HP']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_saudara=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_saudara=$this->model_karyawan->saudara($d->nik);
			$row_a=2;
			$noa='';
			$saudara='';
			$kelamin='';
			$ttl='';
			$pend_saudara='';
			$nohp='';
			foreach ($data_saudara as $aa) {
				$max_saudara=count($data_saudara);
				$body_saudara[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_saudara > ($row_a-1))?"\n":''),
					$saudara.=$aa->nama_saudara.(($max_saudara > ($row_a-1))?"\n":''),
					$kelamin.=$this->otherfunctions->getGender($aa->jenis_kelamin_saudara).(($max_saudara > ($row_a-1))?"\n":''),
					$ttl.=$aa->tempat_lahir_saudara.', '.$this->formatter->getDateFormatUser($aa->tanggal_lahir_saudara).(($max_saudara > ($row_a-1))?"\n":''),
					$pend_saudara.=$this->otherfunctions->getEducate($aa->pendidikan_saudara).(($max_saudara > ($row_a-1))?"\n":''),
					$nohp.=$aa->no_telp_saudara.(($max_saudara > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $saudara, $kelamin, $ttl, $pend_saudara, $nohp,
			];
			$row++;
		}
		$sheet[4]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Saudara',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Saudara', 'Jenis Kelamin', 'Tempat Tanggal Lahir', 'Pendidikan', 'NO HP']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_pendidikan=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_pendidikan=$this->model_karyawan->pendidikan($d->nik);
			$row_a=2;
			$noa='';
			$jenjang_pendidikan='';
			$nama_sekolah='';
			$jurusan='';
			$fakultas='';
			$tahun_masuk='';
			$tahun_keluar='';
			$alamat_sekolah='';
			foreach ($data_pendidikan as $pd) {
				$max_pendidikan=count($data_pendidikan);
				$body_pendidikan[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_pendidikan > ($row_a-1))?"\n":''),
					$jenjang_pendidikan.=$pd->jenjang_pendidikan.(($max_pendidikan > ($row_a-1))?"\n":''),
					$nama_sekolah.=$pd->nama_sekolah.(($max_pendidikan > ($row_a-1))?"\n":''),
					$jurusan.=$pd->jurusan.(($max_pendidikan > ($row_a-1))?"\n":''),
					$fakultas.=$pd->fakultas.(($max_pendidikan > ($row_a-1))?"\n":''),
					$tahun_masuk.=$this->formatter->getDateFormatUser($pd->tahun_masuk).(($max_pendidikan > ($row_a-1))?"\n":''),
					$tahun_keluar.=$this->formatter->getDateFormatUser($pd->tahun_keluar).(($max_pendidikan > ($row_a-1))?"\n":''),
					$alamat_sekolah.=$pd->alamat_sekolah.(($max_pendidikan > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $jenjang_pendidikan, $nama_sekolah, $jurusan, $fakultas, $tahun_masuk, $tahun_keluar, $alamat_sekolah,
			];
			$row++;
		}
		$sheet[5]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Pendidikan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Jenjang Pendidikan', 'Sekolah/Universitas', 'Jurusan', 'Fakultas', 'Tanggal Masuk','Tanggal Keluar','Alamat Pendidikan']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_pnf=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_pnf=$this->model_karyawan->pnf($d->nik);
			$row_a=2;
			$noa='';
			$nama_pnf='';
			$tanggal_masuk_pnf='';
			$sertifikat_pnf='';
			$nama_lembaga_pnf='';
			$alamat_pnf='';
			$keterangan_pnf='';
			foreach ($data_pnf as $pnf) {
				$max_pnf=count($data_pnf);
				$body_pnf[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_pnf > ($row_a-1))?"\n":''),
					$nama_pnf.=$pnf->nama_pnf.(($max_pnf > ($row_a-1))?"\n":''),
					$tanggal_masuk_pnf.=$this->formatter->getDateFormatUser($pnf->tanggal_masuk_pnf).(($max_pnf > ($row_a-1))?"\n":''),
					$sertifikat_pnf.=$pnf->sertifikat_pnf.(($max_pnf > ($row_a-1))?"\n":''),
					$nama_lembaga_pnf.=$pnf->nama_lembaga_pnf.(($max_pnf > ($row_a-1))?"\n":''),
					$alamat_pnf.=$pnf->alamat_pnf.(($max_pnf > ($row_a-1))?"\n":''),
					$keterangan_pnf.=$pnf->keterangan_pnf.(($max_pnf > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $nama_pnf, $tanggal_masuk_pnf, $sertifikat_pnf, $nama_lembaga_pnf, $alamat_pnf, $keterangan_pnf,
			];
			$row++;
		}
		$sheet[6]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Pendidikan Non Formal',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Pendidikan', 'Tanggal Masuk', 'Sertifikat', 'Nama Lembaga', 'Alamat Pendidikan', 'Keterangan',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_award=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_award=$this->model_karyawan->penghargaan($d->nik);
			$row_a=2;
			$noa='';
			$nama_penghargaan='';
			$tahun='';
			$peringkat='';
			$yg_menetapkan='';
			$penyelenggara='';
			$keterangan='';
			foreach ($data_award as $awd) {
				$max_award=count($data_award);
				$body_award[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_award > ($row_a-1))?"\n":''),
					$nama_penghargaan.=$awd->nama_penghargaan.(($max_award > ($row_a-1))?"\n":''),
					$tahun.=$this->formatter->getDateFormatUser($awd->tahun).(($max_award > ($row_a-1))?"\n":''),
					$peringkat.=$awd->peringkat.(($max_award > ($row_a-1))?"\n":''),
					$yg_menetapkan.=$awd->yg_menetapkan.(($max_award > ($row_a-1))?"\n":''),
					$penyelenggara.=$awd->penyelenggara.(($max_award > ($row_a-1))?"\n":''),
					$keterangan.=$awd->keterangan.(($max_award > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $nama_penghargaan, $tahun, $peringkat, $yg_menetapkan, $penyelenggara, $keterangan,
			];
			$row++;
		}
		$sheet[7]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Penghargaan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Penghargaan', 'Tanggal', 'Peringkat', 'Yang Menetapkan', 'Penyelanggara', 'Keterangan',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_org=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_org=$this->model_karyawan->organisasi($d->nik);
			$row_a=2;
			$noa='';
			$nama_organisasi='';
			$tahun_masuk='';
			$tahun_keluar='';
			$jabatan_org='';
			foreach ($data_org as $org) {
				$max_org=count($data_org);
				$body_org[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_org > ($row_a-1))?"\n":''),
					$nama_organisasi.=$org->nama_organisasi.(($max_org > ($row_a-1))?"\n":''),
					$tahun_masuk.=$this->formatter->getDateFormatUser($org->tahun_masuk).(($max_org > ($row_a-1))?"\n":''),
					$tahun_keluar.=$this->formatter->getDateFormatUser($org->tahun_keluar).(($max_org > ($row_a-1))?"\n":''),
					$jabatan_org.=$org->jabatan_org.(($max_org > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $nama_organisasi, $tahun_masuk, $tahun_keluar, $jabatan_org,
			];
			$row++;
		}
		$sheet[8]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Organisasi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Nama Organisasi', 'Tanggal Masuk', 'Tanggal Keluar', 'Jabatan Dalam Organisasi',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_bahasa=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_bahasa=$this->model_karyawan->bahasa($d->nik);
			$row_a=2;
			$noa='';
			$bahasa='';
			$membaca='';
			$menulis='';
			$berbicara='';
			$mendengar='';
			foreach ($data_bahasa as $bhs) {
				$max_bahasa=count($data_bahasa);
				$body_bahasa[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_bahasa > ($row_a-1))?"\n":''),
					$bahasa.=$this->otherfunctions->getBahasa($bhs->bahasa).(($max_bahasa > ($row_a-1))?"\n":''),
					$membaca.=$this->otherfunctions->getRadio($bhs->membaca).(($max_bahasa > ($row_a-1))?"\n":''),
					$menulis.=$this->otherfunctions->getRadio($bhs->menulis).(($max_bahasa > ($row_a-1))?"\n":''),
					$berbicara.=$this->otherfunctions->getRadio($bhs->berbicara).(($max_bahasa > ($row_a-1))?"\n":''),
					$mendengar.=$this->otherfunctions->getRadio($bhs->mendengar).(($max_bahasa > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $noa, $bahasa, $membaca, $menulis, $berbicara, $mendengar,
			];
			$row++;
		}
		$sheet[9]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Penguasaan Bahasa',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja', 'No.', 'Bahasa', 'Membaca', 'Menulis', 'Berbicara', 'Mendengar']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		// Riwayat pekerjaan
		$body=[];
		$body_mutasi=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_mutasi=$this->model_karyawan->getListMutasiNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$tgl_mut='';
			$status_mut='';
			$jab_baru_mut='';
			$lok_baru_mut='';
			foreach ($data_mutasi as $aa) {
				$max_mut=count($data_mutasi);
				$body_mutasi[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).' - '.$aa->no_sk.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_mut.=$this->formatter->getDateMonthFormatUser($aa->tgl_sk).(($max_mut > ($row_mut-1))?"\n":''),
					$status_mut.=$aa->nama_status.(($max_mut > ($row_mut-1))?"\n":''),
					$jab_baru_mut.=$aa->nama_jabatan_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$lok_baru_mut.=$aa->nama_loker_baru.(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $tgl_mut, $status_mut, $jab_baru_mut, $lok_baru_mut,
			];
			$row++;
		}
		$sheet[10]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Mutasi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','NO Surat', 'Tanggal Surat', 'Status', 'Jabatan Baru','Lokasi Baru',]
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_perjanjian=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_perjanjian=$this->model_karyawan->getListPerjanjianKerjaNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$no_sk='';
			$tgl_sk='';
			$n_status='';
			$tgl_brlk='';
			$tgl_smp='';
			foreach ($data_perjanjian as $aa) {
				$max_mut=count($data_perjanjian);
				$body_perjanjian[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).(($max_mut > ($row_mut-1))?"\n":''),
					$no_sk.=$aa->no_sk_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_sk.=$this->formatter->getDateMonthFormatUser($aa->tgl_sk_baru).(($max_mut > ($row_mut-1))?"\n":''),
					$n_status.=$aa->nama_status_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_brlk.=$this->formatter->getDateMonthFormatUser($aa->tgl_berlaku_baru).(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_smp.=$this->formatter->getDateMonthFormatUser($aa->berlaku_sampai_baru).(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $no_sk, $tgl_sk, $n_status, $tgl_brlk, $tgl_smp,
			];
			$row++;
		}
		$sheet[11]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Perjanjian Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','No', 'NO Surat', 'Tanggal Surat', 'Status', 'Tanggal Berlaku','Berlaku Sampai']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_peringatan=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_peringatan=$this->model_karyawan->getListPeringatanNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$no_sk='';
			$tgl_sk='';
			$n_status='';
			$tgl_brlk='';
			$tgl_smp='';
			foreach ($data_peringatan as $aa) {
				$max_mut=count($data_peringatan);
				$body_peringatan[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).(($max_mut > ($row_mut-1))?"\n":''),
					$no_sk.=$aa->no_sk.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_sk.=$this->formatter->getDateMonthFormatUser($aa->tgl_sk).(($max_mut > ($row_mut-1))?"\n":''),
					$n_status.=$aa->nama_status_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_brlk.=$this->formatter->getDateMonthFormatUser($aa->tgl_berlaku).(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_smp.=$this->formatter->getDateMonthFormatUser($aa->berlaku_sampai).(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $no_sk, $tgl_sk, $n_status, $tgl_brlk, $tgl_smp,
			];
			$row++;
		}
		$sheet[12]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Peringatan Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','No', 'NO Surat', 'Tanggal Surat', 'Status', 'Tanggal Berlaku','Berlaku Sampai']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_grade=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_grade=$this->model_karyawan->getListGradeNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$no_sk='';
			$tgl_sk='';
			$n_status='';
			$tgl_brlk='';
			foreach ($data_grade as $aa) {
				$max_mut=count($data_grade);
				$body_grade[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).(($max_mut > ($row_mut-1))?"\n":''),
					$no_sk.=$aa->no_sk.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_sk.=$this->formatter->getDateMonthFormatUser($aa->tgl_sk).(($max_mut > ($row_mut-1))?"\n":''),
					$n_status.=$aa->nama_grade_baru.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_brlk.=$this->formatter->getDateMonthFormatUser($aa->tgl_berlaku).(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $no_sk, $tgl_sk, $n_status, $tgl_brlk,
			];
			$row++;
		}
		$sheet[13]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Grade Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','No', 'NO Surat', 'Tanggal Surat', 'Status', 'Tanggal Berlaku']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$body=[];
		$body_kecelakaan=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_kecelakaan=$this->model_karyawan->getListKecelakaanKerjaNik($d->nik);
			$row_mut=2;
			$no_mut='';
			$no_sk='';
			$tgl_sk='';
			$n_status='';
			$tgl_brlk='';
			foreach ($data_kecelakaan as $aa) {
				$max_mut=count($data_kecelakaan);
				$body_kecelakaan[$row_mut]=[
					($row_mut-1),
					$no_mut.=($row_mut-1).(($max_mut > ($row_mut-1))?"\n":''),
					$no_sk.=$aa->no_sk.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_sk.=$this->formatter->getDateMonthFormatUser($aa->tgl).(($max_mut > ($row_mut-1))?"\n":''),
					$n_status.=$aa->nama_kategori_kecelakaan.(($max_mut > ($row_mut-1))?"\n":''),
					$tgl_brlk.=$aa->nama_rs.(($max_mut > ($row_mut-1))?"\n":''),
				];
				$row_mut++;
			}
			$body[$row]=[
				($row-1),
				$d->nik, $d->nama, $d->nama_jabatan, $d->nama_loker, $no_mut, $no_sk, $tgl_sk, $n_status, $tgl_brlk,
			];
			$row++;
		}
		$sheet[14]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Kecelakaan Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'Nama', 'Jabatan', 'Lokasi Kerja','No', 'NO Surat', 'Tanggal Surat', 'Kategori Kecelakaan', 'Rumah Sakit']
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
 	}
 	function export_template_presensi(){
		$data['properties']=[
			'title'=>"Template Import Data Presensi",
			'subject'=>"Template Import Data Presensi",
			'description'=>"Template Import Data Presensi HSOFT JKB",
			'keywords'=>"Template, Export, Template Presensi",
			'category'=>"Template",
		];

		$body=[];
		$row_body=2;
		$row=$row_body;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Presensi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Jabatan',
					'Lokasi Kerja',
					'No SK',
					'Tanggal SK',
					'Tanggal Berlaku',
					'Tanggal Masuk',
					'Tanggal Keluar',
					'Alasan Keluar',
					'Mengetahui',
					'Menyetujui',
					'Keterangan'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	function export_izin_cuti(){
		$mode = $this->input->get('mode');
		if($mode == 'data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export');
			$unit = $this->input->get('unit_export');
			$bulan = $this->input->get('bulan_export');
			$tahun = $this->input->get('tahun_export');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tahun'=>$tahun,'bulan'=>$bulan];
			$getdata=$this->model_karyawan->getLisIzinCuti('search',$where,'rekap');
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Izin Cuti Karyawan",
				'subject'=>"Rekap Data Izin Cuti Karyawan",
				'description'=>"Rekap Data Izin Cuti Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Izin Cuti, Izin Cuti Karyawan",
				'category'=>"Export",
			];
		}else{
	 		$nik = $this->input->post('nik');
	 		$where = ['param'=>'nik','nik'=>$nik];
	 		$getdata=$this->model_karyawan->getLisIzinCuti('search',$where,'rekap');
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Izin Cuti Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Izin Cuti Kerja",
				'description'=>"Rekap Data Izin Cuti Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Izin Cuti, Izin Cuti Kerja",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$body[$row]=[
				($row-1),
				$d->nik_karyawan,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$d->nama_loker,
				$d->kode_izin_cuti,
				$d->nama_izin.' ('.$this->otherfunctions->getIzinCuti($d->jenis_izin).')',
				$this->formatter->timeFormatUser($d->jam_mulai),
				$this->formatter->getDateFormatUser($d->tgl_mulai),
				$this->formatter->timeFormatUser($d->jam_selesai),
				$this->formatter->getDateFormatUser($d->tgl_selesai),
				$d->alasan,
				($d->skd_dibayar=1)?('SKD Dibayar'):('SKD Tidak Dibayar'),
				$d->keterangan
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Izin Cuti',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Jabatan',
					'Lokasi Kerja',
					'Kode Izin/Cuti',
					'Jenis',
					'Jam Mulai',
					'Tanggal Mulai',
					'Jam Selesai',
					'Tanggal Selesai',
					'Alasan',
					'SKD Dibayar',
					'Keterangan'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	function export_izin_cuti_harian(){
	   $param = $this->input->get('param');
	   $mode = $this->input->get('mode');
	   $bagian_harian = $this->input->get('bagian_harian');
	   $unit_harian = $this->input->get('unit_harian');
	   $tanggal = $this->input->get('tanggal');
	   $status_validasi = $this->input->get('status_validasi');
	   $jenis_cuti = $this->input->get('jenis_cuti');
	   if($mode == 'data'){
			$bagian=(!empty($bagian_harian)) ? $bagian_harian: null;	
			$unit=(!empty($unit_harian)) ? $unit_harian : null;
			$status_validasi=(!empty($status_validasi) && isset($status_validasi)) ? $status_validasi : null;
			$jenis_cuti=(!empty($jenis_cuti) && isset($jenis_cuti)) ? $jenis_cuti : null;
			$tanggalx=(!empty($tanggal)) ? $tanggal : null;
			$bagianx=(!empty($bagian)) ? ["jbt.kode_bagian" => $bagian] : [];	
			$unitx=(!empty($unit)) ? ["h.loker" => $unit] : [];
			$status_validasix=(!empty($status_validasi) && isset($status_validasi)) ? ["a.validasi" => $status_validasi] : [];
			$jenis_cutix=(!empty($jenis_cuti) && isset($jenis_cuti)) ? ["a.jenis" => $jenis_cuti] : [];
			$where = array_merge($bagianx,$unitx,$status_validasix,$jenis_cutix);
			$getdata=$this->model_karyawan->getIzinCutiHarianNew($where,$bagian,$tanggalx);
			$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Izin Cuti Karyawan",
				'subject'=>"Rekap Data Izin Cuti Karyawan",
				'description'=>"Rekap Data Izin Cuti Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Izin Cuti, Izin Cuti Karyawan",
				'category'=>"Export",
			];
	   }
	   $body=[];
	   $row_body=2;
	   $row=$row_body;
	   foreach ($getdata as $d) {
		   $body[$row]=[
			   ($row-1),
			   $d->nik_karyawan,
			   $d->nama_karyawan,
			   $d->nama_jabatan,
			   $d->nama_loker,
			   $d->kode_izin_cuti,
			   $d->nama_izin.' ('.$this->otherfunctions->getIzinCuti($d->jenis_izin).')',
			   $this->formatter->timeFormatUser($d->jam_mulai),
			   $this->formatter->getDateFormatUser($d->tgl_mulai),
			   $this->formatter->timeFormatUser($d->jam_selesai),
			   $this->formatter->getDateFormatUser($d->tgl_selesai),
			   $d->alasan,
			   ($d->skd_dibayar=1)?('SKD Dibayar'):('SKD Tidak Dibayar'),
			   $d->keterangan
		   ];
		   $row++;
	   }
	   $sheet[0]=[
		   'range_huruf'=>3,
		   'sheet_title'=>'Data Izin Cuti',
		   'head'=>[
			   'row_head'=>1,
			   'data_head'=>[
				   'No',
				   'NIK',
				   'Nama',
				   'Jabatan',
				   'Lokasi Kerja',
				   'Kode Izin/Cuti',
				   'Jenis',
				   'Jam Mulai',
				   'Tanggal Mulai',
				   'Jam Selesai',
				   'Tanggal Selesai',
				   'Alasan',
				   'SKD Dibayar',
				   'Keterangan'
			   ],
		   ],
		   'body'=>[
			   'row_body'=>$row_body,
			   'data_body'=>$body
		   ],
	   ];
	   $data['data']=$sheet;
	   $this->rekapgenerator->genExcel($data);
   }
	//============================================ DATA LEMBUR ========================================================
 	function export_data_lembur_kar(){
		$mode = $this->input->get('mode');
		if($mode == 'data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export_kar');
			$unit = $this->input->get('unit_export_kar');
			$tanggal = $this->input->get('tanggal_filter_kar');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal];
			$getdata=$this->model_karyawan->getListLembur('search',$where,'rekap');
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Lembur Karyawan",
				'subject'=>"Rekap Data Lembur Karyawan",
				'description'=>"Rekap Data Lembur Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Lembur, Lembur Karyawan",
				'category'=>"Export",
			];
		}else{
	 		$nik = $this->input->post('nik');
	 		$where = ['param'=>'nik','nik'=>$nik];
	 		$getdata=$this->model_karyawan->getListLembur('search',$where,'rekap');
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Lembur Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Lembur Kerja",
				'description'=>"Rekap Data Lembur Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Lembur, Lembur Kerja",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$body[$row]=[
				($row-1),
				$d->nik_karyawan,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$d->nama_loker,
				$d->no_lembur,
				(isset($d->tgl_mulai)?$this->formatter->getDateMonthFormatUser($d->tgl_mulai):null).' '.
				(isset($d->jam_mulai)?$this->formatter->timeFormatUser($d->jam_mulai):null),
				(isset($d->tgl_selesai)?$this->formatter->getDateMonthFormatUser($d->tgl_selesai):null).' '.
				(isset($d->jam_selesai)?$this->formatter->timeFormatUser($d->jam_selesai):null),
				$d->jumlah_lembur.' Jam',
				($d->potong_jam=1)?('Potong Jam Istirahat'):('Tidak Potong Jam Istirahat'),
				$this->formatter->getDateMonthFormatUser($d->tgl_buat),
				(isset($d->dibuat_oleh))?$d->nama_buat.' ('.$d->jbt_buat.')':null,
				(isset($d->diperiksa_oleh))?$d->nama_periksa.' ('.$d->jbt_periksa.')':null,
				(isset($d->diketahui_oleh))?$d->nama_ketahui.' ('.$d->jbt_ketahui.')':null,
				$d->keterangan,
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Rencana Lembur',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Jabatan',
					'Lokasi Kerja',
					'No Lembur',
					'Tanggal Mulai',
					'Tanggal Selesai',
					'Jumlah Lembur',
					'Potong Jam Istirahat',
					'Tanggal Dibuat',
					'Dibuat Oleh',
					'Diperiksa Oleh',
					'Diketahui Oleh',
					'Keterangan'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	function export_data_lembur(){
		$param = $this->input->get('param');
		$usage=($param == 'all')?'all':'search';
		$bagian = $this->input->get('bagian_export');
		$tanggal = $this->input->get('tanggal_filter');
		$unit = $this->input->get('unit_export');
		$status_validasi = $this->input->get('status_validasi');
		$potong_jam = $this->input->get('potong_jam');
		$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal,'status_validasi'=>$status_validasi,'potong_jam'=>$potong_jam];
		$getdata=$this->model_karyawan->getListLemburTrans('search',$where,'rekap');
		$user = $this->dtroot;
		$data['properties']=[
			'title'=>"Rekap Data Rencana Lembur Karyawan",
			'subject'=>"Rekap Data Rencana Lembur Karyawan",
			'description'=>"Rekap Data Rencana Lembur Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Rekap, Export, Lembur, Lembur Karyawan",
			'category'=>"Export",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$tgl_pre = $this->model_karyawan->getListPresensiId(null,['pre.id_karyawan'=>$d->id_karyawan,'pre.tanggal'=>$d->tgl_mulai],null,'row');
			$body[$row]=[
				($row-1),
				$d->no_lembur,
				$d->jum_no.' Karyawan',
				(isset($d->tgl_mulai)?$this->formatter->getDateMonthFormatUser($d->tgl_mulai):null).' '.
				(isset($d->jam_mulai)?$this->formatter->timeFormatUser($d->jam_mulai):null),
				(isset($d->tgl_selesai)?$this->formatter->getDateMonthFormatUser($d->tgl_selesai):null).' '.
				(isset($d->jam_selesai)?$this->formatter->timeFormatUser($d->jam_selesai):null),
				$d->jumlah_lembur.' Jam',
				$this->otherfunctions->getStatusIzinRekap($d->validasi),
				(isset($d->val_tgl_mulai)?$this->formatter->getDateTimeMonthFormatUser($d->val_tgl_mulai,'ya'):null),
				(isset($d->val_tgl_selesai)?$this->formatter->getDateTimeMonthFormatUser($d->val_tgl_selesai,'ya'):null),
				$d->val_jumlah_lembur.' Jam',
				$d->val_potong_jam.' Jam',
				(isset($d->val_catatan)?$d->val_catatan:null),
				// ($d->potong_jam=1)?('Potong Jam Istirahat'):('Tidak Potong Jam Istirahat'),
				$this->formatter->getDateMonthFormatUser($d->tgl_buat),
				(isset($d->dibuat_oleh))?$d->nama_buat.' ('.$d->jbt_buat.')':null,
				(isset($d->diperiksa_oleh))?$d->nama_periksa.' ('.$d->jbt_periksa.')':null,
				(isset($d->diketahui_oleh))?$d->nama_ketahui.' ('.$d->jbt_ketahui.')':null,
				$d->keterangan,
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Lembur Per Transaksi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'No Lembur',
					'Jumlah Karyawan Yg Lembur',
					'Tanggal Mulai',
					'Tanggal Selesai',
					'Jumlah Lembur',
					'Status Lembur',
					'Validasi Tanggal Mulai',
					'Validasi Tanggal Selesai',
					// 'PRESENSI Mulai',
					// 'PRESENSI Selesai',
					'Validasi Jumlah Lembur',
					'Validasi Potong Jam',
					'Catatan Validasi',
					// 'Potong Jam Istirahat',
					'Tanggal Dibuat',
					'Dibuat Oleh',
					'Diperiksa Oleh',
					'Diketahui Oleh',
					'Keterangan'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
 		//data tambahan
		$body=[];
		$body_anak=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$data_nosk=$this->model_karyawan->getLemburTrans($d->no_lembur);
			$row_a=2;
			$noa='';
			$jabatan='';
			$loker='';
			$nama='';
			$nik='';
			$PresensiMulai='';
			$PresensiSelesai='';
			foreach ($data_nosk as $aa) {
				$tglPresensiMulai=null;
				$tglPresensiSelesai=null;
				if(!empty($aa->tanggal_pre) && !empty($aa->jam_mulai_pre)){
					$tglPresensiMulai = $this->formatter->getDateMonthFormatUser($aa->tanggal_pre).' '.$this->formatter->timeFormatUser($aa->jam_mulai_pre);
				}
				if(!empty($aa->tanggal_pre) && !empty($aa->jam_selesai_pre)){
					$tglPresensiSelesai = $this->formatter->getDateMonthFormatUser($aa->tanggal_pre).' '.$this->formatter->timeFormatUser($aa->jam_selesai_pre);
				}
				$max_nosk=count($data_nosk);
				$body_nosk[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_nosk > ($row_a-1))?"\n":''),
					$jabatan.=$aa->nama_jabatan.(($max_nosk > ($row_a-1))?"\n":''),
					$loker.=$aa->nama_loker.(($max_nosk > ($row_a-1))?"\n":''),
					$nama.=$aa->nama_karyawan.(($max_nosk > ($row_a-1))?"\n":''),
					$nik.=$aa->nik_karyawan.(($max_nosk > ($row_a-1))?"\n":''),
					$PresensiMulai.=$tglPresensiMulai.(($max_nosk > ($row_a-1))?"\n":''),
					$PresensiSelesai.=$tglPresensiSelesai.(($max_nosk > ($row_a-1))?"\n":''),
				];
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->no_lembur,
				(isset($d->tgl_mulai)?$this->formatter->getDateMonthFormatUser($d->tgl_mulai):null).' '.
				(isset($d->jam_mulai)?$this->formatter->timeFormatUser($d->jam_mulai):null),
				(isset($d->tgl_selesai)?$this->formatter->getDateMonthFormatUser($d->tgl_selesai):null).' '.
				(isset($d->jam_selesai)?$this->formatter->timeFormatUser($d->jam_selesai):null),
				$d->jumlah_lembur.' Jam',
				$noa, $nik, $nama, $jabatan, $loker,$PresensiMulai,$PresensiSelesai,
			];
			$row++;
		}
		$sheet[1]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Karyawan Lembur',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No.', 
					'No Lembur',
					'Tanggal Mulai',
					'Tanggal Selesai',
					'Jumlah Lembur',
					'No',
					'NIK',
					'Nama',
					'Jabatan',
					'Lokasi Kerja',
					'Presensi Masuk',
					'Presensi Keluar',
				],
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	public function export_data_lembur_perhitungan(){
		$param = $this->input->get('param');
		$usage=($param == 'all')?'all':'search';
		$bagian = $this->input->get('bagian_export');
		$tanggal = $this->input->get('tanggal_filter');
		$unit = $this->input->get('unit_export');
		$status_validasi = $this->input->get('status_validasi');
		$potong_jam = $this->input->get('potong_jam');
		$bagianx=(!empty($bagian)) ? ["d.kode_bagian" => $bagian] : [];	
		$unitx=(!empty($unit)) ? ["b.loker" => $unit] : [];
		$status_validasix=(!empty($status_validasi)) ? ["a.validasi" => $status_validasi] : [];
		$potong_jamx=(!empty($potong_jam)) ? ["a.status_potong" => $potong_jam] : [];
		$where = array_merge($bagianx,$unitx,$status_validasix,$potong_jamx);
		$getdata=$this->model_karyawan->getDataLemburAll($where, $bagian, $tanggal);
		$dataPerKaryawan = [];
		$user = $this->dtroot;
		$data['properties']=[
			'title'=>"Rekap Data Lembur & Perhitungan Karyawan",
			'subject'=>"Rekap Data Lembur & Perhitungan Karyawan",
			'description'=>"Rekap Data Lembur & Perhitungan Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Rekap, Export, Lembur, Lembur Karyawan",
			'category'=>"Export",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$jumlah_lembur = 0;
		$val_potong_jam = 0;
		$jam_lembur = 0;
		$nominal_lembur = 0;
		foreach ($getdata as $d) {
			$jam_lembur_real	= $this->formatter->convertJamtoDecimal($d->val_jumlah_lembur);
			$jam_potong_lembur	= $this->formatter->convertJamtoDecimal($d->val_potong_jam);
			$jam_lemburx		= ($jam_lembur_real - $jam_potong_lembur);
			$jam_lembur			= $this->formatter->convertDecimaltoJam($jam_lemburx);
			$nominalLembur		= $this->payroll->getNominalLemburDateNew($d->id_karyawan, $d->tgl_mulai, $d->jenis_lembur, $d->val_jumlah_lembur, $d->val_potong_jam);
			$nomLembur			= $this->otherfunctions->nonPembulatan($nominalLembur['nominal']);
			$dataPerKaryawan[$d->id_karyawan][] = [
				'nama_karyawan'=>$d->nama_karyawan,
				'nama_jabatan'=>$d->nama_jabatan,
				'nama_loker'=>$d->nama_loker,
				'jumlah_lembur'=>$d->jumlah_lembur,
				'val_potong_jam'=>$d->val_potong_jam,
				'jam_lembur'=>$jam_lembur,
				'nominal_lembur'=>$nomLembur,
			];
			$body[$row]=[
				($row-1),
				(isset($d->tgl_mulai)?$this->formatter->getDateMonthFormatUser($d->tgl_mulai):null),
				(isset($d->tgl_mulai)?$this->formatter->getNameOfDay($d->tgl_mulai):null),
				$d->nama_karyawan,
				$d->kode_customer,
				$d->keterangan,
				$this->formatter->timeFormatUser($d->jam_mulai),
				$this->formatter->timeFormatUser($d->jam_selesai),
				$d->jumlah_lembur,
				$d->val_potong_jam,
				$jam_lembur,
				$nominalLembur['ekuivalen'],
				$this->formatter->getFormatMoneyUserReq($nomLembur),
				$this->otherfunctions->getStatusIzinRekap($d->validasi),
				$d->nama_jabatan,
				$d->nama_loker,
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Lembur & Perhitungan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'Tanggal',
					'Hari',
					'Nama',
					'Nama/Kode Proyek',
					'Keterangan',
					'Mulai Lembur',
					'Selesai Lembur',
					'Jumlah Jam Lembur',
					'Koreksi Jam'."\n".'(Satuan menit)',
					'Jumlah Jam Lembur'."\n".'Setelah Dikoreksi',
					'Ekuivalen Lembur',
					'Rp Lembur',
					'Status Validasi',
					'Jabatan',
					'Plant',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$ddd = [];
		foreach ($dataPerKaryawan as $id_karyawan => $v) {
			$jumLem = 0;
			$valPotongJam = 0;
			$jamLembur = 0;
			$nominalLembur = 0;
			foreach ($v as $key => $val) {
				$jumlah_lembur = (!empty($val['jumlah_lembur'])) ? $this->formatter->convertJamtoDecimal($val['jumlah_lembur']) : 0;
				$val_potong_jam = (!empty($val['val_potong_jam'])) ? $this->formatter->convertJamtoDecimal($val['val_potong_jam']) : 0;
				$jam_lembur = (!empty($val['jam_lembur'])) ? $this->formatter->convertJamtoDecimal($val['jam_lembur']) : 0;
				$nominal_lembur = (!empty($val['nominal_lembur'])) ? $val['nominal_lembur'] : 0;
				$nom_Lembur = $nominalLembur += $nominal_lembur;
				$ddd[$id_karyawan] = [
					'nama_karyawan'=>$val['nama_karyawan'],
					'nama_jabatan'=>$val['nama_jabatan'],
					'nama_loker'=>$val['nama_loker'],
					'jumlah_lembur'=>$jumLem += $jumlah_lembur,
					'val_potong_jam'=>$valPotongJam += $val_potong_jam,
					'jam_lembur'=>$jamLembur += $jam_lembur,
					'nominal_lembur'=>$this->formatter->getFormatMoneyUserReq($nom_Lembur),
				];
			}
		}
		//data tambahan
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($ddd as $d => $dtx) {
			$body[$row]=[
				($row-1),
				$dtx['nama_karyawan'],
				$dtx['nama_jabatan'],
				$dtx['nama_loker'],
				$dtx['jumlah_lembur'],
				$dtx['val_potong_jam'],
				$dtx['jam_lembur'],
				$dtx['nominal_lembur'],
			];
			$row++;
		}
		$sheet[1]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Per Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.','Nama Karyawan', 'Jabatan','Lokasi Kerja','Jumlah Lembur','Potongan Jam Istirahat','Jam Lembur','Nominal Lembur'
				],
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	//============================================ PERJALANAN DINAS ========================================================
 	function data_perjalanan_dinas_trans(){
		$param = $this->input->get('param');
		$usage=($param == 'all')?'all':'search';
		$bagian = $this->input->get('bagian_export');
		$tanggal = $this->input->get('tanggal_filter');
		$unit = $this->input->get('unit_export');
		$where = ['param'=>'search','bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal];
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
		$getdata=$this->model_karyawan->getPerjalananDinasPerTransaksi($aksesAdmin,'search',$where,'rekap');
		$user = $this->dtroot;
		$data['properties']=[
			'title'=>"Rekap Data Perjalanan Dinas Karyawan",
			'subject'=>"Rekap Data Perjalanan Dinas Karyawan",
			'description'=>"Rekap Data Perjalanan Dinas Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Rekap, Export, Perjalanan Dinas, Data Perjalanan Dinas",
			'category'=>"Export",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$nPenginapan=0;
		$nBBM=0;
		$nValBBM=0;
		$nValPenginapan=0;
		$nAkomodasi=0;
		$nValAkomodasi=0;
		$nAkunTotal=0;
		foreach ($getdata as $d) {
			$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
			$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
			// $jbt_dibuat=($d->jbt_dibuat != null) ? ' - '.$d->jbt_dibuat : null;
			$dataKodeAkun=$this->model_karyawan->getKodeAkunNoSK($d->no_sk);
			$row_a=2;
			$noa='';
			$kodeAkun='';
			$nominalAkun='';
			$ketAkun='';
			$nAkun=0;
			foreach ($dataKodeAkun as $aa) {
				$max_nosk=count($dataKodeAkun);
				$body_nosk[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_nosk > ($row_a-1))?"\n":''),
					$kodeAkun.=$aa->kode_akun.' ('.$aa->nama_akun.')'.(($max_nosk > ($row_a-1))?"\n":''),
					$nominalAkun.=$this->formatter->getFormatMoneyUser($aa->nominal).(($max_nosk > ($row_a-1))?"\n":''),
					$ketAkun.=$aa->keterangan.(($max_nosk > ($row_a-1))?"\n":''),
				];
				$nAkun=$nAkun+$aa->nominal;
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->no_sk,
				$d->jum.' Karyawan',
				(isset($d->tgl_berangkat)?$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,'ya'):null).' - '.
				(isset($d->tgl_sampai)?$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,'ya'):null),
				$d->nama_plant_asal,
				(($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan),
				(isset($d->jarak)?$d->jarak.' KM':null),
				($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j,
				(isset($d->jumlah_kendaraan)?$d->jumlah_kendaraan.' Kendaraan':null),
				(isset($d->nominal_bbm)?$this->formatter->getFormatMoneyUser($d->nominal_bbm):null),
				(($d->menginap==1)?'Menginap':'Tidak Menginap'),
				(!empty($d->nama_penginapan)?$this->otherfunctions->getPenginapan($d->nama_penginapan):null),
				(isset($d->nominal_penginapan)?$this->formatter->getFormatMoneyUser($d->nominal_penginapan):null),
				$d->tugas,
				$d->keterangan,
				$this->otherfunctions->getStatusIzinRekap($d->validasi_ac),
				(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:null,
				(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:null,
				(!empty($d->nama_dibuat)) ? $d->nama_dibuat:'Admin',
				($d->val_kendaraan=='KPD0001')?$d->nama_val_kendaraan.' ('.$this->otherfunctions->getKendaraanUmum($d->val_kendaraan_umum).')':$d->nama_val_kendaraan,
				(isset($d->val_jum_kendaraan)?$d->val_jum_kendaraan.' Kendaraan':null),
				(isset($d->val_nominal_bbm)?$this->formatter->getFormatMoneyUser($d->val_nominal_bbm):null),
				(($d->val_menginap==1)?'Menginap':'Tidak Menginap'),
				(!empty($d->val_nama_penginapan)?$this->otherfunctions->getPenginapan($d->val_nama_penginapan):null),
				(isset($d->val_nominal_penginapan)?$this->formatter->getFormatMoneyUser($d->val_nominal_penginapan):null),
				$this->formatter->getFormatMoneyUser($d->nominal_bbm+$d->nominal_penginapan),
				$this->formatter->getFormatMoneyUser($d->val_nominal_bbm+$d->val_nominal_penginapan),
				$this->otherfunctions->getStatusPerdinRekap($d->status_pd),
				$noa,
				$kodeAkun,
				$nominalAkun,
				$ketAkun,
			];
			$nPenginapan=$nPenginapan+$d->nominal_penginapan;
			$nBBM=$nBBM+$d->nominal_bbm;
			$nValBBM=$nValBBM+$d->val_nominal_bbm;
			$nValPenginapan=$nValPenginapan+$d->val_nominal_penginapan;
			$nAkomodasi=$nAkomodasi+($d->nominal_bbm+$d->nominal_penginapan);
			$nValAkomodasi=$nValAkomodasi+($d->val_nominal_bbm+$d->val_nominal_penginapan);
			$nAkunTotal=$nAkunTotal+$nAkun;
			$row++;
		}
		$data_awal_null=[null,'TOTAL',null,null,null,null,null,null,null,$this->formatter->getFormatMoneyUser($nBBM),null,null,$this->formatter->getFormatMoneyUser($nPenginapan),null,null,null,null,null,null,null,null,$this->formatter->getFormatMoneyUser($nValBBM),null,null,$this->formatter->getFormatMoneyUser($nValPenginapan),$this->formatter->getFormatMoneyUser($nAkomodasi),$this->formatter->getFormatMoneyUser($nValAkomodasi),null,null,null,$this->formatter->getFormatMoneyUser($nAkunTotal)];
		$body[count($getdata)+3]=array_merge($data_awal_null);
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Per Transaksi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No', 'No Perjalanan Dinas', 'Jumlah Karyawan', 'Tanggal', 'Dari', 'Tujuan', 'Jarak', 'Kendaraan', 'Jumlah Kendaraan', 'Nominal BBM', 'Menginap', 'Penginapan', 'Nominal Penginapan', 'Tugas', 'Keterangan', 'Status Validasi', 'Mengetahui', 'Menyetujui', 'Dibuat', 'Validasi Kendaraan', 'Validasi Jumlah kendaraan', 'Validasi Nominal BBM', 'Validasi Menginap', 'Validasi Penginapan', 'Validasi Nominal Penginapan', 'Total Akomodasi', 'Total Akomodasi Setelah Di Validasi','Status Perjalanan Dinas','No','Kode Akun','Nominal','Keterangan Kode Akun',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
 		//data tambahan
		$body=[];
		$body_anak=[];
		$row_body=2;
		$row=$row_body;
		$nominalT1=0;
		$nominalT2=0;
		$nominalT3=0;
		$nominalT4=0;
		$nominalT5=0;
		$nominalTotal=0;
		foreach ($getdata as $d) {
			$data_nosk=$this->model_karyawan->getPerjalananDinasKodeSK($d->no_sk);
			$row_a=2;
			$noa=''; $nik=''; $nama=''; $jabatan=''; $bagian=''; $loker=''; $tun1=''; $tun2=''; $tun3=''; $tun4=''; $tun5=''; $total=''; $nT1=0; $nT2=0; $nT3=0; $nT4=0; $nT5=0; $nTotal=0;
			foreach ($data_nosk as $aa) {
				$max_nosk=count($data_nosk);
				$t1=$this->otherfunctions->getDataExplode($aa->besar_tunjangan,';','start');
				$t2=$this->otherfunctions->getDataExplode($aa->besar_tunjangan,';','end');
				$t3=$this->otherfunctions->getDataExplode($aa->besar_tunjangan,';',3);
				$t4=$this->otherfunctions->getDataExplode($aa->besar_tunjangan,';',4);
				$t5=$this->otherfunctions->getDataExplode($aa->besar_tunjangan,';',5);
				$body_nosk[$row_a]=[
					($row_a-1),
					$noa.=($row_a-1).(($max_nosk > ($row_a-1))?"\n":''),
					$nik.=$aa->nik_karyawan.(($max_nosk > ($row_a-1))?"\n":''),
					$nama.=$aa->nama_karyawan.(($max_nosk > ($row_a-1))?"\n":''),
					$jabatan.=$aa->nama_jabatan.(($max_nosk > ($row_a-1))?"\n":''),
					$bagian.=$aa->nama_bagian.(($max_nosk > ($row_a-1))?"\n":''),
					$loker.=$aa->nama_loker.(($max_nosk > ($row_a-1))?"\n":''),
					$tun1.=$this->formatter->getFormatMoneyUser($t1).(($max_nosk > ($row_a-1))?"\n":''),
					$tun2.=$this->formatter->getFormatMoneyUser($t2).(($max_nosk > ($row_a-1))?"\n":''),
					$tun3.=$this->formatter->getFormatMoneyUser($t3).(($max_nosk > ($row_a-1))?"\n":''),
					$tun4.=$this->formatter->getFormatMoneyUser($t4).(($max_nosk > ($row_a-1))?"\n":''),
					$tun5.=$this->formatter->getFormatMoneyUser($t5).(($max_nosk > ($row_a-1))?"\n":''),
					$total.=$this->formatter->getFormatMoneyUser($t1+$t2+$t3+$t4+$t5).(($max_nosk > ($row_a-1))?"\n":''),
					// $total.=$aa->total_tunjangan.(($max_nosk > ($row_a-1))?"\n":''),
				];
				$nT1=$nT1+$t1;
				$nT2=$nT2+$t2;
				$nT3=$nT3+$t3;
				$nT4=$nT4+$t4;
				$nT5=$nT5+$t5;
				$nTotal=$nTotal+($t1+$t2+$t3+$t4+$t5);
				$row_a++;
			}
			$body[$row]=[
				($row-1),
				$d->no_sk,
				(isset($d->tgl_berangkat)?$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,'ya'):null).' - '.
				(isset($d->tgl_sampai)?$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,'ya'):null),
				$d->nama_plant_asal,
				(($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan),
				$noa, $nik, $nama, $jabatan, $bagian, $loker,
				$tun1, $tun2, $tun3, $tun4, $tun5, $total,
			];
			$nominalT1=$nominalT1+$nT1;
			$nominalT2=$nominalT2+$nT2;
			$nominalT3=$nominalT3+$nT3;
			$nominalT4=$nominalT4+$nT4;
			$nominalT5=$nominalT5+$nT5;
			$nominalTotal=$nominalTotal+$nTotal;
			$row++;
		}
		$data_awal_null=[null,'TOTAL',null,null,null,null,null,null,null,null,null,$this->formatter->getFormatMoneyUser($nominalT1),$this->formatter->getFormatMoneyUser($nominalT2),$this->formatter->getFormatMoneyUser($nominalT3),$this->formatter->getFormatMoneyUser($nominalT4),$this->formatter->getFormatMoneyUser($nominalT5),$this->formatter->getFormatMoneyUser($nominalTotal)];
		$body[count($getdata)+3]=$data_awal_null;
		$sheet[1]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Per Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.','Nomor', 'Tanggal','Dari','Tujuan','No','NIK','Nama','Jabatan','Bagian','Lokasi Kerja',
					$this->model_master->getKategoriDinasKode('KAPD0003')['nama'],
					$this->model_master->getKategoriDinasKode('KAPD0004')['nama'],
					$this->model_master->getKategoriDinasKode('KAPD0005')['nama'],
					$this->model_master->getKategoriDinasKode('KAPD0006')['nama'],
					$this->model_master->getKategoriDinasKode('KAPD0002')['nama'],
					'TOTAL',
				],
			],
			'body'=>[
				'row_body'=>$row,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	function data_perjalanan_dinas(){
		$mode = $this->input->get('mode');
		if($mode == 'data'){
			$param = $this->input->get('param');
			$usage=($param == 'all')?'all':'search';
			$bagian = $this->input->get('bagian_export');
			$unit = $this->input->get('unit_export');
			$tanggal = $this->input->get('tanggal_filter');
			$where = ['param'=>$usage,'bagian'=>$bagian,'unit'=>$unit,'tanggal'=>$tanggal];
			$getdata=$this->model_karyawan->getListDataPerjalananDinas('search',$where,'rekap');
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Perjalanan Dinas Karyawan",
				'subject'=>"Rekap Data Perjalanan Dinas Karyawan",
				'description'=>"Rekap Data Perjalanan Dinas Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Perjalanan Dinas, Perjalanan Dinas Karyawan",
				'category'=>"Export",
			];
		}else{
	 		$nik = $this->input->post('nik');
	 		$where = ['param'=>'nik','nik'=>$nik];
	 		$getdata=$this->model_karyawan->getListDataPerjalananDinas('search',$where,'rekap');
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Perjalanan Dinas Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Perjalanan Dinas Kerja",
				'description'=>"Rekap Data Perjalanan Dinas Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Perjalanan Dinas, Perjalanan Dinas Kerja",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$jbt_mengetahui=($d->jbt_mengetahui != null) ? ' - '.$d->jbt_mengetahui : null;
			$jbt_menyetujui=($d->jbt_menyetujui != null) ? ' - '.$d->jbt_menyetujui : null;
			$t1=$this->otherfunctions->getDataExplode($d->besar_tunjangan,';','start');
			$t2=$this->otherfunctions->getDataExplode($d->besar_tunjangan,';','end');
			$t3=$this->otherfunctions->getDataExplode($d->besar_tunjangan,';',3);
			$t4=$this->otherfunctions->getDataExplode($d->besar_tunjangan,';',4);
			$t5=$this->otherfunctions->getDataExplode($d->besar_tunjangan,';',5);
			$total=$t1+$t2+$t3+$t4+$t5;
			$body[$row]=[
				($row-1),
				$d->no_sk,$d->nik_karyawan, $d->nama_karyawan, $d->nama_jabatan, $d->nama_bagian, $d->nama_loker,
				(isset($d->tgl_berangkat)?$this->formatter->getDateTimeMonthFormatUser($d->tgl_berangkat,'ya'):null).' - '.
				(isset($d->tgl_sampai)?$this->formatter->getDateTimeMonthFormatUser($d->tgl_sampai,'ya'):null),
				$d->nama_plant_asal,
				(($d->plant=='plant')?$d->nama_plant_tujuan:$d->lokasi_tujuan),
				(isset($d->jarak)?$d->jarak.' KM':null),
				($d->kendaraan=='KPD0001')?$d->nama_kendaraan_j.' ('.$this->otherfunctions->getKendaraanUmum($d->nama_kendaraan).')':$d->nama_kendaraan_j,
				(isset($d->jumlah_kendaraan)?$d->jumlah_kendaraan.' Kendaraan':null),
				(isset($d->nominal_bbm)?$this->formatter->getFormatMoneyUser($d->nominal_bbm):null),
				(($d->menginap==1)?'Menginap':'Tidak Menginap'),
				(!empty($d->nama_penginapan)?$this->otherfunctions->getPenginapan($d->nama_penginapan):null),
				(isset($d->nominal_penginapan)?$this->formatter->getFormatMoneyUser($d->nominal_penginapan):null),
				$d->tugas,
				$d->keterangan,
				$this->otherfunctions->getStatusIzinRekap($d->validasi_ac),
				(!empty($d->nama_mengetahui)) ? $d->nama_mengetahui.$jbt_mengetahui:null,
				(!empty($d->nama_menyetujui)) ? $d->nama_menyetujui.$jbt_menyetujui:null,
				(!empty($d->nama_dibuat)) ? $d->nama_dibuat:'Admin',
				($d->val_kendaraan=='KPD0001')?$d->nama_val_kendaraan.' ('.$this->otherfunctions->getKendaraanUmum($d->val_kendaraan_umum).')':$d->nama_val_kendaraan,
				(isset($d->val_jumlah_kendaraan)?$d->val_jumlah_kendaraan.' Kendaraan':null),
				(isset($d->val_nominal_bbm)?$this->formatter->getFormatMoneyUser($d->val_nominal_bbm):null),
				(($d->val_menginap==1)?'Menginap':'Tidak Menginap'),
				(!empty($d->val_nama_penginapan)?$this->otherfunctions->getPenginapan($d->val_nama_penginapan):null),
				(isset($d->val_nominal_penginapan)?$this->formatter->getFormatMoneyUser($d->val_nominal_penginapan):null),
				$t1,$t2,$t3,$t4,$t5,$total,
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Lembur Per Transaksi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No', 'No Perjalanan Dinas',
					'NIK',
					'Nama',
					'Jabatan',
					'Bagian',
					'Lokasi Kerja', 'Tanggal', 'Dari', 'Tujuan', 'Jarak', 'Kendaraan', 'Jumlah Kendaraan', 'Nominal BBM', 'Menginap', 'Penginapan', 'Nominal Penginapan', 'Tugas', 'Keterangan', 'Status Validasi', 'Mengetahui', 'Menyetujui', 'Dibuat', 'Validasi Kendaraan', 'Validasi Jumlah kendaraan', 'Validasi Nominal BBM', 'Validasi Menginap', 'Validasi Penginapan', 'Validasi Nominal Penginapan',
					$this->model_master->getKategoriDinasKode('KAPD0003')['nama'],
					$this->model_master->getKategoriDinasKode('KAPD0004')['nama'],
					$this->model_master->getKategoriDinasKode('KAPD0005')['nama'],
					$this->model_master->getKategoriDinasKode('KAPD0006')['nama'],
					$this->model_master->getKategoriDinasKode('KAPD0002')['nama'],
					'TOTAL', 
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	function export_template_presensi_all(){
			$data['properties']=[
				'title'=>"Template Import Data Presensi",
				'subject'=>"Template Import Data Presensi",
				'description'=>"Template Import Data Presensi HSOFT JKB",
				'keywords'=>"Template, Export, Template Presensi",
				'category'=>"Template",
			];
			$body=[];
			$row_body=2;
			$row=$row_body;
			$sheet[0]=[
				'range_huruf'=>3,
				'sheet_title'=>'Template Data Presensi',
				'head'=>[
					'row_head'=>1,
					'data_head'=>[
						'Id Finger',
						'Id Karyawan',
						'NIK',
						'Kode Jabatan',
						'Kode Shift',
						'Tanggal (yyyy/MM/dd)',
						'Jam Masuk (hh:mm)',
						'Jam Keluar (hh:mm)',
						'Status (0/1)',
					],
				],
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
			];
			$data['data']=$sheet;
			$this->rekapgenerator->genExcel($data);
	}
	function export_template_presensi_one(){
		$data['properties']=[
			'title'=>"Template Import Data Presensi",
			'subject'=>"Template Import Data Presensi",
			'description'=>"Template Import Data Presensi HSOFT JKB",
			'keywords'=>"Template, Export, Template Presensi",
			'category'=>"Template",
		];

		$body=[];
		$row_body=2;
		$row=$row_body;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Presensi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'Id Finger',
					'Id Karyawan',
					'NIK',
					'Kode Jabatan',
					'Kode Shift',
					'Tanggal (yyyy/MM/dd)',
					'Jam Masuk (hh:mm)',
					'Jam Keluar (hh:mm)',
					'Status (0/1)',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_presensi()
	{
		$mode = $this->input->get('mode');
		if($mode == 'data'){
			$post_form = [
				'param'=>'all',
				'usage' =>$this->input->get('usage'),
				'mode' =>$this->input->get('mode'),
				'bagian' =>$this->input->get('bagian'),
				'karyawan' =>$this->input->get('karyawan'),
				'tanggal' =>$this->input->get('tanggal'),
			];
			$getdata=$this->model_karyawan->getListPresensi('no_group',$post_form);
			$data['properties']=[
				'title'=>"Rekap Data Presensi",
				'subject'=>"Rekap Data Presensi",
				'description'=>"Rekap Data Presensi HSOFT JKB",
				'keywords'=>"Export, Rekap, Rekap Presensi",
				'category'=>"Rekap",
			];
		}else{
	 		$nik = $this->input->post('nik');
	 		$where = ['usage' =>$this->input->get('usage'),
				'mode' =>$this->input->get('mode'),
				'param'=>'nik',
				'nik'=>$nik];
	 		$getdata=$this->model_karyawan->getListPresensi('no_group',$where);
	 		$emp = $this->model_karyawan->getEmployeeNik($nik);
	 		$user = $this->dtroot;
	 		$data['properties']=[
				'title'=>"Rekap Data Rencana Lembur Kerja - ".$emp['nama']." - ".$emp['nik'],
				'subject'=>"Rekap Data Presensi",
				'description'=>"Rekap Data Rencana Lembur Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Export, Rekap, Rekap Presensi",
				'category'=>"Rekap",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$emp = $this->model_karyawan->getEmpID($d->id_karyawan);
			$izin = $this->model_karyawan->checkIzinCuti($d->tanggal);
			$cekjadwal = $this->model_karyawan->cekJadwalKerjaIdDate($d->id_karyawan,$d->tanggal);
			$body[$row]=[
				($row-1),
				$emp['nik'],
				$emp['nama'],
				$emp['nama_jabatan'],
				$emp['nama_bagian'],
				$emp['nama_loker'],
				$this->formatter->getDateMonthFormatUser($d->tanggal),
				$d->no_spl,
				$izin['kode_izin_cuti'],
				$this->formatter->getTimeFormatUser($d->jam_mulai,'WIB'),
				$this->formatter->getTimeFormatUser($d->jam_selesai,'WIB'),
				$this->otherfunctions->getRangeTime($d->jam_mulai,$d->jam_selesai),
				(isset($cekjadwal['nama_shift'])?$cekjadwal['nama_shift'].' ['.$this->formatter->getTimeFormatUser($cekjadwal['jam_mulai']).' - '.$this->formatter->getTimeFormatUser($cekjadwal['jam_selesai']).']':null),
				(isset($cekjadwal['jam_selesai'])?$this->otherfunctions->getRangeTime($cekjadwal['jam_selesai'],$d->jam_selesai):null),
				$izin['nama_izin'],
				$this->model_karyawan->cekLemburId($d->nik,$d->tanggal),
				$this->model_master->getHariLiburKode($d->kode_hari_libur)['nama']
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Presensi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Jabatan',
					'Bagian',
					'Lokasi Kerja',
					'Tanggal Presensi',
					'Nomor SPL',
					'Nomor Ijin',
					'Jam Masuk',
					'Jam Keluar',
					'Jumlah Jam Kerja',
					'Jadwal Kerja',
					'Over',
					'Ijin/Cuti',
					'Lembur',
					'Hari Libur'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//=============================================== REKAP PRESENSI BULANAN ==================================================================
	public function rekap_presensi_bulan()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$bagian = $data_filter['bagian_filter'];
		$tanggalx = $data_filter['tanggal'];
		$lokasi = $data_filter['lokasi'];
		$tanggal_mulai = $this->formatter->getDateFromRange($tanggalx,'start','no');
		$tanggal_selesai = $this->formatter->getDateFromRange($tanggalx,'end','no');
		$mulai = $this->formatter->getDateMonthFormatUser($tanggal_mulai);
		$selesai = $this->formatter->getDateMonthFormatUser($tanggal_selesai);
		$lokasix=(!empty($lokasi)) ? ["emp.loker" => $lokasi] : [];
		$bagianx=(!empty($bagian)) ? ["jbt.kode_bagian" => $bagian] : [];	
		$whereKar = array_merge($lokasix,$bagianx);
		$employee = $this->model_karyawan->getEmployeeWhere($whereKar);
		$date_loop=$this->formatter->dateLoopFull($tanggal_mulai,$tanggal_selesai);
		$masterIzin = $this->model_master->getMasterIzin(null,['a.jenis'=>'I']);
		$data['properties']=[
			'title'=>'Rekap Presensi Bulanan '.$mulai.' - '.$selesai,
			'subject'=>'Rekap Presensi Bulanan',
			'description'=>'Rekap Presensi Bulanan HSOFT JKB',
			'keywords'=>'Export, Rekap, Rekap Presensi Bulanan',
			'category'=>'Rekap',
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if(!empty($employee)){
			foreach ($employee as $d) {
				$hariKerja = $this->payroll->getHariKerjaKar($d->tgl_masuk);
				$pre=$this->otherfunctions->getDataPresensiIzinCuti($d->id_karyawan,$tanggal_mulai,$tanggal_selesai);
				$body_1 = [
					($row-1),
					$d->nik,
					$d->nama,
					$d->nama_jabatan,
					$d->nama_bagian,
					$d->nama_loker,
					$hariKerja,
					($pre['presensi']['presensi_hadir'] == 0)?null:$pre['presensi']['presensi_hadir'],
					($pre['presensi']['alpa'] == 0)?null:$pre['presensi']['alpa'],
					($pre['presensi']['countCuti'] == 0)?null:$pre['presensi']['countCuti'],
					($pre['presensi']['terlambat'] == 0)?null:$pre['presensi']['terlambat'],
					($pre['presensi']['plgcepat'] == 0)?null:$pre['presensi']['plgcepat'],
					($pre['presensi']['notFingerIn'] == 0)?null:$pre['presensi']['notFingerIn'],
					($pre['presensi']['notFingerOut'] == 0)?null:$pre['presensi']['notFingerOut'],
					($pre['presensi']['libur'] == 0)?null:$pre['presensi']['libur'],
				];
				$bodyMaster = [];
				foreach ($masterIzin as $mi) {
					foreach ($pre['izin'] as $key => $value) {
						if($key == $mi->kode_master_izin){
							$bodyMaster[] = ($value == 0)?null:$value;
						}
					}
				}
				$data_body = array_merge($body_1,$bodyMaster);
				$body[$row]=$data_body;
				$row++;
			}
		}
		$headMaster = [];
		foreach ($masterIzin as $mi) {
			$headMaster[] = $mi->nama;
		}
		$data_head = ['No', 'NIK', 'Nama', 'Jabatan', 'Bagian', 'Lokasi Kerja', 'Hari Kerja', 'Presensi', 'Alpa', 'Cuti', 'Terlambat', 'Pulang Cepat', 'Tidak Finger Masuk', 'Tidak Finger Pulang', 'Libur'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Presensi Bulanan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>array_merge($data_head,$headMaster),
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	//================================================ DATA JADWAL KERJA ======================================================
	public function export_data_jadwal_kerja()
	{
		$post_form = [
			'usage'=>$this->input->get('usage'),
			'mode'=>$this->input->get('mode'),
			'bagian'=>$this->input->get('bagian'),
			'lokasi'=>$this->input->get('lokasi'),
			'bulan'=>$this->input->get('bulan'),
			'tahun'=>$this->input->get('tahun'),
		];
		$data['properties']=[
			'title'=>"Rekap Data Jadwal Kerja",
			'subject'=>"Rekap Data Jadwal Kerja",
			'description'=>"Rekap Data Jadwal Kerja HSOFT JKB",
			'keywords'=>"Export, Rekap, Rekap Jadwal Kerja",
			'category'=>"Rekap",
		];

		$body=[];
		$row_body=2;
		$row=$row_body;
		$getdata = $this->model_karyawan->getListJdwalKerja($post_form);
		foreach ($getdata as $d) {
			$body_1 = [
				($row-1),
				$d->nik,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$d->nama_bagian,
				$d->nama_loker,
				$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun
			];
			$body_2 = [];
			for ($i_body=1; $i_body <= 31; $i_body++) { 
				$tgl = 'tgl_'.$i_body;
				$jam_mulai=$this->model_master->getMasterShiftKode($d->$tgl)['jam_mulai'];
				$jam_selesai=$this->model_master->getMasterShiftKode($d->$tgl)['jam_selesai'];
				$body_2[] = (!empty($tgl)?$this->model_master->getMasterShiftKode($d->$tgl)['nama'].' ('.$this->formatter->getTimeFormatUser($jam_mulai).' - '.$this->formatter->getTimeFormatUser($jam_selesai).')':null);
			}
			$data_body = array_merge($body_1,$body_2);
			$body[$row]=$data_body;
			$row++;
		}
		for ($i=1; $i <= 31; $i++) { 
			$head_2[] = 'Tanggal '.$this->formatter->zeroPadding($i);
		}
		$head_1 = ['No', 'NIK', 'Nama', 'Jabatan', 'Bagian', 'Lokasi Kerja', 'Bulan Tahun'];
		$data_head = array_merge($head_1,$head_2);
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Presensi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	function export_template_jadwal(){
		$post_form = [
			'usage'=>'search',//$this->input->get('usage'),
			'mode'=>$this->input->get('mode'),
			'bagian'=>$this->input->get('bagian'),
			'lokasi'=>$this->input->get('lokasi'),
			'bulan'=>$this->input->get('bulan'),
			'tahun'=>$this->input->get('tahun'),
		];
		$data['properties']=[
			'title'=>"Template Import Jadwal Kerja Karyawan",
			'subject'=>"Template Import Jadwal Kerja Karyawan",
			'description'=>"Template Import Jadwal Kerja Karyawan HSOFT JKB",
			'keywords'=>"Template, Export, Template Jadwal Kerja Karyawan",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$order = ['kolom'=>'emp.nama','value'=>'ASC'];
		$getdata = $this->model_karyawan->getListJdwalKerja($post_form,null,'emp.nama',$order);
		foreach ($getdata as $d) {
			$body_1 = [
				($row-1),
				$d->nik,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$d->nama_bagian,
				$d->nama_loker,
				null,
				null,
			];
			$body_2 = [];
			for ($i_body=1; $i_body <= 31; $i_body++) { 
				$tgl = 'tgl_'.$i_body;
				$body_2[] = null;
			}
			$data_body = array_merge($body_1,$body_2);
			$body[$row]=$data_body;
			$row++;
		}
		for ($i=1; $i <= 31; $i++) { 
			$head_2[] = 'Tanggal '.$i;
		}
		$head_1 = ['No', 'NIK', 'Nama', 'Jabatan', 'Bagian', 'Lokasi Kerja', 'Bulan', 'Tahun'];
		$data_head = array_merge($head_1,$head_2);
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Import Jadwal Kerja',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$body_shift=[];
		$row_bodyx=2;
		$rowx=$row_bodyx;
		$dataShift = $this->model_master->getListMasterShift();
		foreach ($dataShift as $d) {
			$body_shift[$rowx]=[
				($row-1),
				$d->kode_master_shift,
				$d->nama,
				$d->jam_mulai,
				$d->jam_selesai,
			];
			$rowx++;
		}
		$data_awal_null=[null,'Pada Kolom Tanggal 1 s/d Tanggal 31 pada Sheet "Template Import Jadwal Kerja" di isi KODE SHIFT '."\n".'yang dapat dilihat pada Sheet "Petunjuk Kode Shift" ini.'."\n".'Pada Kolom "Bulan" diisi 2 angka contoh : (09, 10, dll).'."\n".'Pada Kolom "Tahun" diisi 4 angka contoh : (2009, 2020, dll).'];
		$body_shift[count($dataShift)+3]=$data_awal_null;
		$sheet[1]=[
			'range_huruf'=>3,
			'sheet_title'=>'Petunjuk Kode Shift',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'Kode Shift',
					'Nama Shift',
					'Jam Mulai',
					'Jam Selesai',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body_shift
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function import_jadwal_kerja()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			$sheet[0]=[
				'range_huruf'=>0,
				'row'=>2,
				'table'=>'data_jadwal_kerja',
				'column_code'=>'id_karyawan',
				'usage'=>'data_jadwal_kerja',
				'admin'=>$this->admin,
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'column'=>[
					1=>'nik',6=>'bulan',7=>'tahun',	8=>'tgl_1', 9=>'tgl_2', 10=>'tgl_3', 11=>'tgl_4', 12=>'tgl_5', 13=>'tgl_6', 14=>'tgl_7', 15=>'tgl_8', 16=>'tgl_9', 17=>'tgl_10', 18=>'tgl_11', 19=>'tgl_12', 20=>'tgl_13', 21=>'tgl_14', 22=>'tgl_15', 23=>'tgl_16', 24=>'tgl_17', 25=>'tgl_18', 26=>'tgl_19', 27=>'tgl_20', 28=>'tgl_21', 29=>'tgl_22', 30=>'tgl_23', 31=>'tgl_24', 32=>'tgl_25', 33=>'tgl_26', 34=>'tgl_27', 35=>'tgl_28', 36=>'tgl_29', 37=>'tgl_30', 38=>'tgl_31',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	//============================================================= GRADE ==============================================================
 	function export_template_grade(){
		$data['properties']=[
			'title'=>"Template Import Master Grade",
			'subject'=>"Template Import Master Grade",
			'description'=>"Template Import Master Grade HSOFT JKB",
			'keywords'=>"Template, Export, Template Karyawan",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$getData = $this->model_master->getListGrade();
		foreach ($getData as $d) {
			$body_1 = [
				($row-1),
				$d->kode_induk_grade,
				$d->kode_grade,
				$d->nama,
				$d->gapok,
				$d->kode_dokumen,
				$d->kode_loker,
				// $d->tahun,
				$d->nama_loker,
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Master Grade',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','Kode Induk Grade','Kode Grade','Nama Grade','Gaji Pokok','Kode Dokumen (Lihat Master Dokumen)','Kode Lokasi (Lihat Master Lokasi)','Nama Lokasi',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
// ============================================== DATA PENGGAJIAN ===================================================
 	function export_template_ritasi(){
		$data['properties']=[
			'title'=>"Template Import Data Ritasi",
			'subject'=>"Template Import Data Ritasi",
			'description'=>"Template Import Data Ritasi HSOFT JKB",
			'keywords'=>"Template, Export, Data Ritasi",
			'category'=>"Template",
		];
		$body=[];
		$karyawan=$this->model_karyawan->getEmployeeAllActiveFilter();
		// echo '<pre>';
		// print_r($karyawan);
		$kary = [];
		if(!empty($karyawan)){
			foreach ($karyawan as $d) {
				if(stripos($d->nama_jabatan, 'driver') !== FALSE){
					$kary[$d->nik]=$d->nama;//.' - '.$d->nama_jabatan.' - '.$d->nama_loker;
				}
			}
		}
		// print_r($kary);
		$row_body=2;
		$row=$row_body;
		if(!empty($kary)){
			foreach ($kary as $nik => $nama) {
				$body[$row]=[
					($row-1),
					$nama,
					$nik,
					null,
					null,
				];
				$row++;
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Ritasi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NAMA KARYAWAN','NIK','TOTAL RUPIAH PPN','TOTAL RUPIAH NON-PPN','TOTAL RITASI PPN','TOTAL RITASI NON-PPN','KETERANGAN',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_data_ritasi()
	{
		$minggu = $this->input->post('minggu');
		$periode = $this->input->post('periode');
		$where = (!empty($minggu)) ? ['a.kode_periode_penggajian'=>$periode,'a.minggu'=>$minggu] : ['a.kode_periode_penggajian'=>$periode];
		$getdata = $this->model_master->getListDataRitasi($where);
		$data['properties']=[
			'title'=>'Cetak Data Ritasi',
			'subject'=>'Cetak Data Ritasi',
			'description'=>'Cetak Data Ritasi HSOFT JKB',
			'keywords'=>"Export, Rekap, Cetak Data Ritasi",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			if($d->validasi==2){
				$validasi = 'Perlu Validasi';
			}elseif($d->validasi==1){
				$validasi = 'Diizinkan';
			}elseif($d->validasi==0){
				$validasi = 'Tidak DIizinkan';
			}
			$body_1 = [
				($row-1),
				$d->nik,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$d->nama_periode,
				(!empty($d->minggu)?$this->otherfunctions->getlistWeekRitasi($d->minggu):null),
				$d->rit,
				$this->formatter->getFormatMoneyUser($d->nominal),
				$d->rit_non_ppn,
				$this->formatter->getFormatMoneyUser($d->nominal_non_ppn),
				$d->keterangan,
				$validasi,
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}
		$data_head = ['No', 'NIK', 'Nama', 'Jabatan', 'Periode', 'Minggu', 'RIT', 'Nominal', 'RIT Non PPn','Nominal Non PPn','Keterangan', 'Validasi'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Ritasi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}

//------------------------------------------------------------------------------------------------------//
//Rekap Data BPJS Karyawan
 	function export_template_bpjs_karyawan(){
		$data['properties']=[
			'title'=>"Template Import Data BPJS Karyawan",
			'subject'=>"Template Import Data BPJS Karyawan",
			'description'=>"Template Import Data BPJS Karyawan HSOFT JKB",
			'keywords'=>"Template, Export, Template BPJS Karyawan",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$dataMasterBPJS=$this->model_master->getListBpjs(null,'active');
		$dataEmpAktif=$this->model_karyawan->getEmployeeAllActive();
		foreach ($dataEmpAktif as $d) {
			$emp_single = $this->model_karyawan->getEmployeeId($d->id_karyawan);
			$karyawan = $this->payroll->cekDataBPJSEmp($emp_single,$dataMasterBPJS,null,null);
			$body[$row]=[
				($row-1),
				$d->nik,
				$d->nama,
				$d->nama_jabatan,
				$d->nama_loker,
				$this->formatter->getFormatMoneyUser($karyawan['gaji_bpjs']),
				$this->formatter->getFormatMoneyUser($karyawan['jht']),
				$this->formatter->getFormatMoneyUser($karyawan['jkk']),
				$this->formatter->getFormatMoneyUser($karyawan['jkm']),
				$this->formatter->getFormatMoneyUser($karyawan['jpns']),
				$this->formatter->getFormatMoneyUser($karyawan['jkes']),
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data BPJS Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NIK','Nama','Jabatan','Lokasi Kerja','Gaji Perhitungan BPJS','Jaminan Hari Tua (JHT)','Jaminan Kecelakaan Kerja (JKK)','Jaminan Kematian (JKM)','Jaminan Pensiun (JPNS)','Jaminan Kesehatan (JKES)',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function import_data_bpjs()
	{
		if (!$this->input->is_ajax_request()) 
		   	redirect('not_found');

			$data['properties']=[
				'post'=>'file',
				'data_post'=>$this->input->post('file', TRUE),
			];
			$sheet[0]=[
				'range_huruf'=>0,
				'row'=>2,
				'table'=>'data_bpjs',
				'column_code'=>'id_karyawan',
				'usage'=>'import_bpjs_karyawan',
				'bulan'=>$this->input->post('bulan_for'),
				'tahun'=>$this->input->post('tahun_for'),
				'admin'=>$this->admin,
				'column_properties'=>$this->model_global->getCreateProperties($this->admin),
				'column'=>[
					1=>'nik',6=>'jht',7=>'jkk',8=>'jkm',9=>'jpns',10=>'jkes',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	public function export_data_bpjs_karyawan()
	{
		$data['properties']=[
			'title'=>"Rekap Data BPJS Karyawan",
			'subject'=>"Rekap Data BPJS Karyawan",
			'description'=>"Rekap Data BPJS Karyawan - Direkap Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Rekap Data BPJS Karyawan, Data BPJS Karyawan",
			'category'=>"Rekap",
		];
		$bagianx = $this->input->get('bagian');
		$lokasix = $this->input->get('lokasi');
		$bulanx = $this->input->get('bulan');
		$tahunx = $this->input->get('tahun');
		$bagian = (isset($bagianx) && !empty($bagianx)) ? ["jbt.kode_bagian" => $bagianx] : [];
		$lokasi = (isset($lokasix) && !empty($lokasix)) ? ["emp.loker" =>$lokasix] : [];
		$bulan = (isset($bulanx) && !empty($bulanx))?['a.bulan'=>$bulanx]:[];
		$tahun = (isset($tahunx) && !empty($tahunx))?['a.tahun'=>$tahunx]:[];
		$where = array_merge($lokasi,$bagian,$bulan,$tahun);
		$datax=$this->model_payroll->getListBpjsEmp($where);
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($datax as $d) {
			$body[$row]=[
				($row-1),
				$d->nik,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$this->formatter->getFormatMoneyUser($d->jht),
				$this->formatter->getFormatMoneyUser($d->jkk),
				$this->formatter->getFormatMoneyUser($d->jkm),
				$this->formatter->getFormatMoneyUser($d->jpns),
				$this->formatter->getFormatMoneyUser($d->jkes),
				$d->tahun,
			];
			$row++;
		}

		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'NAMA', 'JABATAN', 'JAMINAN HARI TUA', 'JAMINAN KESELAMATAN KERJA', 'JAMINAN KEMATIAN', 'JAMINAN PENSIUN', 'JAMINAN KESEHATAN', 'TAHUN'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];

		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//=================================================== GAJI_BULANAN =====================================================================
	public function export_log_data_gaji()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}		
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(isset($data_filter['periode'])){
			$form_filter['a.kode_periode'] = $data_filter['periode'];
		}
		if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
			$form_filter['a.kode_bagian'] = $data_filter['bagian'];
		}
		if($data_filter['usage'] == 'log'){
			$title_usage = 'Rekap Log Payroll';
		}else{
			$title_usage = 'Rekap Payroll';
		}
		$post_form = $form_filter;
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if($data_filter['usage'] == 'data'){
			$getdata = $this->model_payroll->getDataPayroll($post_form);
		}elseif($data_filter['usage'] == 'log'){
			$getdata = $this->model_payroll->getDataLogPayroll($post_form);
		}
		$total_gaji=0;
		$total_denda=0;
		$total_lain=0;
		foreach ($getdata as $d) {
			$body_1 = [
				($row-1),
				$d->nik,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$d->nama_bagian,
				$d->nama_loker,
				$this->formatter->getDateMonthFormatUser($d->tgl_masuk),
				$d->masa_kerja,
				$this->formatter->getFormatMoneyUser($d->gaji_pokok),
				$this->formatter->getFormatMoneyUser($d->tunjangan_tetap),
				$this->formatter->getFormatMoneyUser($d->tunjangan_non),
				$this->formatter->getFormatMoneyUser($d->insentif),
				$this->formatter->getFormatMoneyUser($d->ritasi),
				$this->formatter->getFormatMoneyUser($d->uang_makan),
				$this->formatter->getFormatMoneyUser(($d->pot_tidak_masuk+$d->n_terlambat+$d->n_izin+$d->n_iskd+$d->n_imp)),
				$this->formatter->getFormatMoneyUser(($d->bpjs_jht+$d->bpjs_jkk+$d->bpjs_jkm)),
				$this->formatter->getFormatMoneyUser($d->bpjs_pen),
				$this->formatter->getFormatMoneyUser($d->bpjs_kes),
				$this->formatter->getFormatMoneyUser($d->angsuran),
				$d->angsuran_ke,
				$this->formatter->getFormatMoneyUser($d->nominal_denda),
				$d->angsuran_ke_denda,
				$this->payroll->getDataLainNamaNominalView($d->data_lain, $d->data_lain_nama, $d->nominal_lain,true),
				$this->formatter->getFormatMoneyUser($d->gaji_bersih),
				$d->no_rek,
			];
			$total_lain +=$this->payroll->getNominalDataLainNama($d->nominal_lain);
			$total_gaji+=$d->gaji_bersih;
			$total_denda+=$d->nominal_denda;
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}
		$data_total=[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,$this->formatter->getFormatMoneyUser($total_denda),null,$this->formatter->getFormatMoneyUser($total_lain),$this->formatter->getFormatMoneyUser($total_gaji),null,];
		$body[count($getdata)+3]=$data_total;

		$data_head = ['No', 'NIK', 'Nama', 'Jabatan', 'Bagian', 'Lokasi Kerja', 'Tanggal Masuk', 'Masa kerja', 'Gaji Pokok','Tunjangan Tetap','Tunjangan Tidak Tetap', 'Insentif', 'Ritasi', 'Uang Makan', 'Pot Tdk Masuk', 'BPJS TK - JHT,JKK,JKM', 'BPJS Pensiun', 'BPJS Kesehatan', 'Angsuran', 'Angsuran Ke', 'Denda', 'Angsuran Denda Ke','Nominal & Nama Lainnya', 'Penerimaan', 'No. Rek'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_gaji_transfer_bank()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}	
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(isset($data_filter['periode'])){
			$form_filter['a.kode_periode'] = $data_filter['periode'];
		}
		if (isset($data_filter['bagian'])){
			if ($data_filter['bagian'] == 'all'){
				unset($data_filter['bagian']);
			}
		}
		if(isset($data_filter['bagian'])){
			
			if (is_array($data_filter['bagian'])){
				$bg='';
				$c_lv=1;
				foreach ($data_filter['bagian'] as $bag) {
					if ($bag != 'all'){
						$bg.="a.kode_bagian='".$bag."'";
						if (count($data_filter['bagian']) > $c_lv) {
							$bg.=' OR ';
						}
					}
					$c_lv++;
				}
				if ($bg != ''){
					$form_filter['bagian_multi']="(".$bg.")";
				}
			}else{
				$form_filter['a.kode_bagian'] = $data_filter['bagian'];
			}
		}
		if($data_filter['usage'] == 'log'){
			$title_usage = 'Rekap Transfer Bank';
		}else{
			$title_usage = 'Rekap Transfer Bank';
		}
		$post_form = $form_filter;
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if($data_filter['usage'] == 'data'){
			$getdata = $this->model_payroll->getDataPayroll($post_form);
		}elseif($data_filter['usage'] == 'log'){
			$getdata = $this->model_payroll->getDataLogPayroll($post_form);
		}
		$body_group=[];
		if ($getdata){
			foreach ($getdata as $d) {
				$body_group[$d->nama_bagian][]=[
					$d->no_rek,
					floor($d->gaji_bersih),
					$d->nik,
					$d->nama_karyawan,
					null,
				];
				$row++;
			}
		}
		if ($body_group){
			$row=2;
			foreach ($body_group as $nama_d => $val) {
				$count_dept=count($val);
				if ($val) {
					$row_dept=0;
					foreach ($val as $v){
						if ($row==2) {
							array_push($body,['#',$nama_d]);
							$body[2]=$body[0];
							unset($body[0]);
							$row++;
						}elseif (($count_dept-$row_dept) == $count_dept) {
							array_push($body,['#',$nama_d]);
							$row++;
						}
						$body[$row]=$v;
						$row++;
						$row_dept++;
					}
				}
			}
		}
		$data_head = ['No Rekening', 'Jumlah Transfer', 'NIP', 'NAMA', 'Dept'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_penggajian_bulanan_bagian()
	{	
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}	
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		if($data_filter['usage'] == 'log'){
			$title_usage = 'Log Rekapitulasi Gaji Bulanan';
		}else{
			$title_usage = 'Rekapitulasi Gaji Bulanan';
		}
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if($data_filter['usage'] == 'log'){
			$getdata = $this->model_payroll->getRekapitulasiDataLogPayrollBulanan($form_filter);
		}else{
			$getdata = $this->model_payroll->getRekapitulasiDataPayrollBulanan($form_filter);
		}
		$total=0;
		$tPer=[];
		if(!empty($getdata) || isset($getdata)){
			$no = 1;
			$total_gaji_pokok=0;
			$total_tunjangan_tetap=0;
			$total_tunjangan_non=0;
			$total_ritasi=0;
			$total_uang_makan=0;
			$total_pot_tidak_masuk=0;
			$total_bpjs_jht=0;
			$total_bpjs_pen=0;
			$total_bpjs_kes=0;
			$total_angsuran=0;
			$total_gaji_pokok=0;
			$total_penerimaan=0;
			$total_penambah=0;
			$total_pengurang=0;
			foreach ($getdata as $d) {
				$data_gaji_all = $this->model_payroll->getRekapitulasiDataPayrollBulananAll(['a.kode_periode'=>$d->kode_periode,'a.kode_bagian'=>$d->kode_bagian]);
				$penambah = 0;
				$pengurang = 0;
				foreach ($data_gaji_all as $dd) {
					$dtx = $this->payroll->getDataLainRekapitulasi($dd->data_lain, $dd->data_lain_nama, $dd->nominal_lain);
					$penambah+=$dtx['penambah'];
					$pengurang+=$dtx['pengurang'];
				}
				$body[$row]=[
					($row-1),
					$d->nama_bagian,
					$d->nama_loker,
					$this->formatter->getFormatMoneyUser($d->jumlah_gaji_pokok),
					$this->formatter->getFormatMoneyUser($d->jumlah_tunjangan_tetap),
					$this->formatter->getFormatMoneyUser($d->jumlah_tunjangan_non),
					$this->formatter->getFormatMoneyUser($d->jumlah_ritasi),
					$this->formatter->getFormatMoneyUser($d->jumlah_uang_makan),
					$this->formatter->getFormatMoneyUser(($d->jumlah_pot_tidak_masuk+$d->jumlah_terlambat+$d->jumlah_izin+$d->jumlah_iskd+$d->jumlah_imp)),
					$this->formatter->getFormatMoneyUser($d->jumlah_bpjs_jht),
					$this->formatter->getFormatMoneyUser($d->jumlah_bpjs_pen),
					$this->formatter->getFormatMoneyUser($d->jumlah_bpjs_kes),
					$this->formatter->getFormatMoneyUser($d->jumlah_angsuran),
					$this->formatter->getFormatMoneyUser($penambah),
					$this->formatter->getFormatMoneyUser($pengurang),
					$this->formatter->getFormatMoneyUser($d->jumlah_penerimaan),
					$d->nama_periode
				];
				$total_gaji_pokok+=$d->jumlah_gaji_pokok;
				$total_tunjangan_tetap+=$d->jumlah_tunjangan_tetap;
				$total_tunjangan_non+=$d->jumlah_tunjangan_non;
				$total_ritasi+=$d->jumlah_ritasi;
				$total_uang_makan+=$d->jumlah_uang_makan;
				$total_pot_tidak_masuk+=($d->jumlah_pot_tidak_masuk+$d->jumlah_terlambat+$d->jumlah_izin+$d->jumlah_iskd+$d->jumlah_imp);
				$total_bpjs_jht+=$d->jumlah_bpjs_jht;
				$total_bpjs_pen+=$d->jumlah_bpjs_pen;
				$total_bpjs_kes+=$d->jumlah_bpjs_kes;
				$total_angsuran+=$d->jumlah_angsuran;
				$total_penerimaan+=$d->jumlah_penerimaan;
				$total_penambah+=$penambah;
				$total_pengurang+=$pengurang;
				$row++;
			}
			$data_head=['No', 'Bagian', 'Lokasi','Gaji Pokok','Tunjangan Tetap','Tunjangan Tidak Tetap','Ritasi','Uang Makan','Potongan Tidak Masuk','BPJS TK','Jaminan Pensiun','BPJS KES','Angsuran','Lain Penambah','Lain Pengurang', 'Penerimaan', 'Periode'];
			$body[count($getdata)+3]=[null,'TOTAL',null,$this->formatter->getFormatMoneyUser($total_gaji_pokok),$this->formatter->getFormatMoneyUser($total_tunjangan_tetap),$this->formatter->getFormatMoneyUser($total_tunjangan_non),$this->formatter->getFormatMoneyUser($total_ritasi),$this->formatter->getFormatMoneyUser($total_uang_makan),$this->formatter->getFormatMoneyUser($total_pot_tidak_masuk),$this->formatter->getFormatMoneyUser($total_bpjs_jht),$this->formatter->getFormatMoneyUser($total_bpjs_pen),$this->formatter->getFormatMoneyUser($total_bpjs_kes),$this->formatter->getFormatMoneyUser($total_angsuran),$this->formatter->getFormatMoneyUser($total_penambah),$this->formatter->getFormatMoneyUser($total_pengurang),$this->formatter->getFormatMoneyUser($total_penerimaan)];
			$sheet[0]=[
				'range_huruf'=>3,
				'sheet_title'=>$title_usage,
				'head'=>[
					'row_head'=>1,
					'data_head'=>$data_head,
				],
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
			];
			$data['data']=$sheet;
			$this->rekapgenerator->genExcel($data);
		}
	}
	public function export_rekapitulasi()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);

		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}	
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }

		$post_form = $form_filter;
		$data['properties']=[
			'title'=>"Rekapitulasi Payroll",
			'subject'=>"Rekapitulasi Payroll",
			'description'=>"Rekapitulasi Payroll HSOFT JKB",
			'keywords'=>"Export, Rekap, Rekapitulasi Payroll",
			'category'=>"Rekap",
		];

		$body=[];
		$row_body=2;
		$row=$row_body;

		$loker = $this->model_payroll->getDataLogPayroll(null, 0, 'a.kode_loker');
		$get_loker = [];
		$datax = [];
		foreach ($loker as $l) {
			$get_loker[] = $l->kode_loker;
		}
		
		foreach ($get_loker as $gk => $gv) {
			$data_log = $this->model_payroll->getDataLogPayroll(['a.kode_loker'=>$gv]);
			$nama_loker = $this->otherfunctions->convertResultToRowArray($this->model_master->getLokerKode($gv));
			$keterangan = $nama_loker['nama'];
			$gaji_pokok = 0;
			$insentif = 0;
			$ritasi = 0;
			$uang_makan = 0;
			$pot = 0;
			$bpjs_tk = 0;
			$jam_pns = 0;
			$jam_kes = 0;
			$piutang = 0;
			$penerima = 0;

			foreach ($data_log as $d) {
				$gaji_pokok += $d->gaji_pokok;
				$insentif += $d->insentif;
				$ritasi += $d->ritasi;
				$uang_makan += $d->uang_makan;
				$pot += $d->pot_tidak_masuk;
				$bpjs_tk += ($d->bpjs_jht+$d->bpjs_jkk+$d->bpjs_jkm);
				$jam_pns += $d->bpjs_pen;
				$jam_kes += $d->bpjs_kes;
				$piutang += $d->angsuran;
				$penerima += $d->gaji_bersih;
			}
			$datax[$gv] = [
				'keterangan'=>$keterangan,
				'gaji_pokok'=>$gaji_pokok,
				'insentif'=>$insentif,
				'ritasi'=>$ritasi,
				'uang_makan'=>$uang_makan,
				'pot'=>$pot,
				'bpjs_tk'=>$bpjs_tk,
				'jam_pns'=>$jam_pns,
				'jam_kes'=>$jam_kes,
				'piutang'=>$piutang,
				'penerima'=>$penerima
			];
		}
		foreach ($datax as $dk => $dv) {
			$body_1 = [
				($row-1),
				$dv['keterangan'],
				$this->formatter->getFormatMoneyUser($dv['gaji_pokok']),
				$this->formatter->getFormatMoneyUser($dv['insentif']),
				$this->formatter->getFormatMoneyUser($dv['ritasi']),
				$this->formatter->getFormatMoneyUser($dv['uang_makan']),
				$this->formatter->getFormatMoneyUser($dv['pot']),
				$this->formatter->getFormatMoneyUser($dv['bpjs_tk']),
				$this->formatter->getFormatMoneyUser($dv['jam_pns']),
				$this->formatter->getFormatMoneyUser($dv['jam_kes']),
				$this->formatter->getFormatMoneyUser($dv['piutang']),
				$this->formatter->getFormatMoneyUser($dv['penerima']),
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}

		$data_head = ['No', 'KETERANGAN', 'GAJI POKOK', 'INSENTIF', 'RITASI', 'UANG MAKAN', 'POT TDK MASUK', 'BPJS TK - JHT,JKK,JKM', 'JAMINAN PENSIUN', 'BPJS KES', 'PIUTANG', 'PENERIMAAN'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekapitulasi Payroll',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}

	public function export_pph()
	{
		$post_form = [
			'a.kode_periode'=>$this->codegenerator->decryptChar($this->input->get('kode_periode')),
		];
		$data['properties']=[
			'title'=>"Rekap PPH-21",
			'subject'=>"Rekap PPH-21",
			'description'=>"Rekap PPH-21 HSOFT JKB",
			'keywords'=>"Export, Rekap, Rekap PPH-21",
			'category'=>"Rekap",
		];

		$body=[];
		$row_body=2;
		$row=$row_body;
		if(empty($post_form['a.kode_periode'])){
			$getdata = $this->model_payroll->getDataLogPayroll();
		}else{
			$getdata = $this->model_payroll->getDataLogPayroll($post_form);
		}
		foreach ($getdata as $d) {
			$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
			$tunjangan = $this->payroll->getTUnjangan($d->id_karyawan);
			$tunjangan_pph_nominal = $this->payroll->getTunjanganNominalPPH($tunjangan);
		// $tunjangan_tetap = $this->getTunjanganNominalTetap($tunjangan);
			$bpjs_p_jkk = $this->payroll->getBpjsBayarPerusahaan($d->gaji_pokok, 'jkk');
			$bpjs_p_jkm = $this->payroll->getBpjsBayarPerusahaan($d->gaji_pokok, 'jkm');
			$bpjs_p_jkes = $this->payroll->getBpjsBayarPerusahaan($d->gaji_pokok, 'jkes');
		// $bruto_bulan = $d->gaji_pokok+$tunjangan_tetap+$bpjs_p_jkk+$bpjs_p_jkm+$bpjs_p_jkes;
			$bruto_bulan = $this->payroll->getBrutoBulan($d->id_karyawan,$d->gaji_pokok,$tunjangan);
			$bruto_tahun = ($bruto_bulan*12);
			$getbiaya_jabatan = $this->payroll->getBiayaJabatan($d->id_karyawan,$d->gaji_pokok,$tunjangan);
			$biaya_jabatan = $getbiaya_jabatan['biaya_hasil'];
			$bpjs_p_jht = $this->payroll->getBpjsBayarPerusahaan($d->gaji_pokok, 'jht');
			$bpjs_k_jht = $this->payroll->getBpjs($d->id_karyawan)['jht'];
			$iuran_k_pensiun = $this->payroll->getIuranPensiun($d->id_karyawan,$d->gaji_pokok);
			$jml_pengurangan = ($biaya_jabatan+$bpjs_k_jht+$iuran_k_pensiun);

			$netto_bulan = $bruto_bulan-$jml_pengurangan;
			$netto_tahun = $netto_bulan*12;
			$kena_pajak_tahun = $this->payroll->getPajakPertahun($netto_bulan,$emp['status_pajak']);
			$ptkp = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPtkp(['status_ptk'=>$emp['status_pajak']]));
			$layer =$this->payroll->getLayerPPH($kena_pajak_tahun);
			$pph = $this->payroll->getPPHPertahun($layer,$emp['npwp']);

			if(empty($emp['npwp'])){
				$get_pph = $pph['plus_npwp'];
			}else{
				$get_pph = $pph['pph_bulan'];
			}
			$pph_layer = $this->model_master->getListPph();
 			$pph_layer1 =json_decode(json_encode($pph_layer[0]), true);
			$layer1 = ($kena_pajak_tahun-($pph_layer1['dari']))*($pph_layer1['tarif']/100);
			if($layer1<0){
				$layer1 = 'Rp. 0,00';
			}else{
				$layer1 = $this->formatter->getFormatMoneyUser($layer1);
			}

 			$pph_layer2 = json_decode(json_encode($pph_layer[1]), true);
			$layer2 = ($kena_pajak_tahun-($pph_layer2['dari']-1))*($pph_layer2['tarif']/100);
			if($layer2<0){
				$layer2 = 'Rp. 0,00';
			}else{
				$layer2 = $this->formatter->getFormatMoneyUser($layer2);
			}

 			$pph_layer3 = json_decode(json_encode($pph_layer[2]), true);
			$layer3 = ($kena_pajak_tahun-($pph_layer3['dari']-1))*($pph_layer3['tarif']/100);
			if($layer3<0){
				$layer3 = 'Rp. 0,00';
			}else{
				$layer3 = $this->formatter->getFormatMoneyUser($layer3);
			}

 			$pph_layer4 = json_decode(json_encode($pph_layer[3]), true);
			$layer4 = ($kena_pajak_tahun-($pph_layer4['dari']-1))*($pph_layer4['tarif']/100);
			if($layer4<0){
				$layer4 = 'Rp. 0,00';
			}else{
				$layer4 = $this->formatter->getFormatMoneyUser($layer4);
			}

			$body_1 = [
				($row-1),
				$d->nama_karyawan,
				$emp['status_pajak'],
				$this->formatter->getFormatMoneyUser($d->gaji_pokok),
				$this->formatter->getFormatMoneyUser($tunjangan_pph_nominal),
				$this->formatter->getFormatMoneyUser($bpjs_p_jkk),
				$this->formatter->getFormatMoneyUser($bpjs_p_jkm),
				$this->formatter->getFormatMoneyUser($bpjs_p_jkes),
				$this->formatter->getFormatMoneyUser($bruto_bulan),
				$this->formatter->getFormatMoneyUser($bruto_tahun),
				$this->formatter->getFormatMoneyUser($getbiaya_jabatan['biaya_asli']),
				$this->formatter->getFormatMoneyUser($getbiaya_jabatan['biaya_hasil']),
				$this->formatter->getFormatMoneyUser($bpjs_p_jht),
				$this->formatter->getFormatMoneyUser($bpjs_k_jht),
				$this->formatter->getFormatMoneyUser($iuran_k_pensiun),
				$this->formatter->getFormatMoneyUser($jml_pengurangan),
				$this->formatter->getFormatMoneyUser($netto_bulan),
				$this->formatter->getFormatMoneyUser($netto_tahun),
				$this->formatter->getFormatMoneyUser($kena_pajak_tahun),
				$layer1,
				$layer2,
				$layer3,
				$layer4,
				$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($pph['pph_tahun'])),
				$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($pph['pph_bulan'])),
				$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($pph['plus_npwp'])),
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}

		$data_head = [
			'No', 
			'Nama', 
			'Status PTKP', 
			'Gaji Pokok', 
			'Tunjangan Tetap', 
			'Jaminan Kecelakaan Kerja ( Perusahaan )', 
			'Jaminan Kematian ( Perusahaan )', 
			'BPJS Kesehatan ( Perusahaan )', 
			'Penghasilan Bruto ( Sebulan )', 
			'Penghasilan Bruto ( Setahun )', 
			'Biaya Jabatan', 
			'Biaya Jabatan Yang Dikurangi', 
			'Jaminan Hari Tua ( Perusahaan )', 
			'Jaminan Hari Tua ( Pekerja )', 
			// 'Iuran Pensiun ( Perusahaan )', 
			'Iuran Pensiun ( Pekerja )', 'Jumlah Pengurangan', 'Penghasilan Netto ( Sebulan )', 'Penghasilan Netto ( Setahun )','Penghasilan Kena Pajak ( Setahun )','5%-Layer 1','15%-Layer 1','25%-Layer 1','30%-Layer 1','PPH Setahun','PPH Sebulan','Tidak Punya NPWP + 20%'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap PPH-21',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}

//=================================================== GAJI_LEMBUR =====================================================================
	public function export_data_penggajian_lembur()
	{	
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}	
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(isset($data_filter['periode'])){
			$form_filter['a.kode_periode'] = $data_filter['periode'];
		}
		// if(!empty($data_filter['bagian']) && $data_filter['bagian'] != 'all'){
		// 	$form_filter['a.kode_bagian'] = $data_filter['bagian'];
		// }
		$where="a.create_by='".$this->admin."'";
		if ($level <= 2){
			$where="";
		}
		if(!empty($data_filter['bagian'])){
			if(in_array('all',$data_filter['bagian'])){
				// if ($level <= 2){
				// 	$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
				// }else{
				$where.=" AND a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
				// }
			}else{
				$c_lv=1;
				foreach ($data_filter['bagian'] as $key => $bag) {
					$where.=" AND a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."' AND a.kode_bagian='".$bag."'";
					if (count($data_filter['bagian']) > $c_lv) {
						$where.=' OR ';
					}
					$c_lv++;
				}
			}
		}
		if($data_filter['usage'] == 'log'){
			$title_usage = 'Rekap Log Lembur';
		}else{
			$title_usage = 'Rekap Lembur';
		}
		$post_form = $form_filter;
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if($data_filter['usage'] == 'log'){
			$getdata = $this->model_payroll->getDataLogPayrollLembur($post_form);
		}else{
			$getdata = $this->model_payroll->getDataPayrollLemburExcel($where);
		}
		$jumlah_data = 0;
		$tgl_mulai=null;
		$tgl_selesai=null;
		$total_gaji=0;
		$jam_hari_biasa=0;
		$nominal_hari_biasa=0;
		$jam_hari_libur=0;
		$nominal_hari_libur=0;
		$jam_hari_libur_pendek=0;
		$nominal_hari_libur_pendek=0;
		$ekuivalen=0;
		$total_jam_lembur=0;
		foreach ($getdata as $d) {
			if($d->gaji_terima > 0){
				$jumlah_data +=1;
				$tgl_mulai=$d->tgl_mulai;
				$tgl_selesai=$d->tgl_selesai;
				$yy=11;
				$data_lembur=[];
				while ($d->tgl_mulai <= $d->tgl_selesai)
				{
					$d_lembur=$this->model_karyawan->getDataLemburDate($d->id_karyawan, $d->tgl_mulai);
					if (count($d_lembur) > 0) {
						$lembur_kar='';
						$row_lem=1;
						foreach ($d_lembur as $dl) {
							$max_lem=count($d_lembur);
							$jam_lembur_real=$this->formatter->convertJamtoDecimal($dl->val_jumlah_lembur);
							$jam_potong_lembur=$this->formatter->convertJamtoDecimal($dl->val_potong_jam);
							$jam_lembur			= ($jam_lembur_real - $jam_potong_lembur);
							// $lll=$this->formatter->convertDecimaltoJam($lamaLembur);
							// $jam_lembur = $this->formatter->convertJamtoDecimal($dl->jumlah_lembur);
							$lama_lembur = 14;
							$lama_lembur_real = $this->formatter->convertJamtoDecimal($dl->val_jumlah_lembur);
							if($d->tgl_mulai == $dl->tgl_mulai && $lama_lembur_real < $lama_lembur){
								$nominalLembur=$this->payroll->getNominalLemburDate($d->id_karyawan, $d->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
								$nl=$this->otherfunctions->nonPembulatan($nominalLembur);
								$lembur_kar.=$jam_lembur." Jam | ".$this->formatter->getFormatMoneyUser($nl).' | '.$dl->kode_customer.' | '.$dl->keterangan.(($max_lem > ($row_lem))?"\n":'');
							}elseif($d->tgl_mulai == $dl->tgl_mulai && $lama_lembur_real >= $lama_lembur){
								$date_loop=$this->formatter->dateLoopFull($dl->tgl_mulai,$dl->tgl_selesai);
								if(in_array($d->tgl_mulai, $date_loop)){
									$nominalLembur=$this->payroll->getNominalLemburDate($d->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
									$nl=$this->otherfunctions->nonPembulatan($nominalLembur);
									$lembur_kar.=$jam_lembur." Jam | ".$this->formatter->getFormatMoneyUser($nl)."\n";
									$lembur_kar.='('.$this->formatter->getDateFormatUser($dl->tgl_mulai).' - '.$this->formatter->getDateFormatUser($dl->tgl_selesai).')';
								}
							}
							$row_lem++;
						}
						$data_lembur[$yy] = $lembur_kar;
					}else{
						$data_lembur[$yy]='';
					}			
					$d->tgl_mulai = date('Y-m-d', strtotime($d->tgl_mulai . ' +1 day'));
					$yy++;
				}
				$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
				$body1=[($row-1),$emp['nik'],$d->nama_karyawan,$d->nama_jabatan,$d->nama_bagian,$d->nama_loker,$this->formatter->getDateMonthFormatUser($d->tgl_masuk),$d->masa_kerja,$d->nama_periode.' ('.$this->formatter->getDateFormatUser($tgl_mulai).' - '.$this->formatter->getDateFormatUser($tgl_selesai).')',$this->formatter->getFormatMoneyUser($d->gaji_pokok),];
				$body2=[$d->jam_biasa,$this->formatter->getFormatMoneyUser($d->nominal_biasa),$d->jam_libur_pendek,$this->formatter->getFormatMoneyUser($d->nominal_libur_pendek),$d->jam_libur,$this->formatter->getFormatMoneyUser($d->nominal_libur),$d->ekuivalen,$d->total_jam,$this->formatter->getFormatMoneyUser($d->gaji_terima),$d->no_rekening];
				$total_gaji+=$d->gaji_terima;
				$jam_hari_biasa+=$d->jam_biasa;
				$nominal_hari_biasa+=$d->nominal_biasa;
				$jam_hari_libur+=$d->jam_libur;
				$nominal_hari_libur+=$d->nominal_libur;
				$jam_hari_libur_pendek+=$d->jam_libur_pendek;
				$nominal_hari_libur_pendek+=$d->nominal_libur_pendek;
				$ekuivalen+=$d->ekuivalen;
				$total_jam_lembur+=$d->total_jam;
				$data_body = array_merge($body1,$data_lembur,$body2);
				$body[$row]=$data_body;
				$row++;
			}
		}
		$tgl_lembur=[];
		$data_tgl_null=[];
		$nn=11;
		while ($tgl_mulai <= $tgl_selesai)
		{
			$tgl_lembur[$nn]=$this->formatter->getNameOfDay($tgl_mulai).', '.$this->formatter->getDateMonthFormatUser($tgl_mulai)."\n".'Jam Lembur | Rupiah | Kode Proyek | Keterangan';
			$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
			$data_tgl_null[$nn]=null;
			$nn++;
		}
		$head1=['No', 'NIK', 'Nama', 'Jabatan', 'Bagian', 'Lokasi Kerja', 'Tanggal Masuk', 'Masa kerja', 'Nama Periode', 'Gaji Pokok'];
		$head2=['Jam Hari Biasa', 'Nominal Hari Biasa', 'Jam Istirahat', 'Nominal Istirahat','Jam Hari Libur', 'Nominal Hari Libur', 'Ekuivalen', 'Total Jam Lembur', 'Gaji Diterima', 'No. Rek'];
		$data_head = array_merge($head1,$tgl_lembur,$head2);
		$data_awal_null=[null,'TOTAL',null,null,null,null,null,null,null,null];
		$data_total=[$jam_hari_biasa,$this->formatter->getFormatMoneyUser($nominal_hari_biasa),$jam_hari_libur_pendek,$this->formatter->getFormatMoneyUser($nominal_hari_libur_pendek),$jam_hari_libur,$this->formatter->getFormatMoneyUser($nominal_hari_libur),$ekuivalen,$total_jam_lembur,$this->formatter->getFormatMoneyUser($total_gaji),null];
		$body[$jumlah_data+3]=array_merge($data_awal_null,$data_tgl_null,$data_total);
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_penggajian_lembur_bagian_old()
	{	
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$where="a.create_by='".$this->admin."'";
		if ($level <= 2){
			$where="";
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		$log=false;
		if($data_filter['usage'] == 'log'){
			$log=true;
			$title_usage = 'Log Rekapitulasi';
		}else{
			$title_usage = 'Rekapitulasi Lembur Bagian';
		}
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		if (count($data_filter['bagian']) <= 0){
			$data_filter['bagian']=['all'];
		}
		if(!empty($data_filter['bagian'])){
			if(in_array('all',$data_filter['bagian'])){
				if ($where){
					$where.=" AND ";
				}
				$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
			}else{
				$c_lv=1;
				foreach ($data_filter['bagian'] as $key => $bag) {
					if ($where){
						$where.=" AND ";
					}
					$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."' AND a.kode_bagian='".$bag."'";
					if (count($data_filter['bagian']) > $c_lv) {
						$where.=' OR ';
					}
					$c_lv++;
				}
			}
		}
		$body=[];
		$row_body=5;
		$row=$row_body;
		// echo '<pre>';
		// if($data_filter['usage'] == 'log'){
		// 	$getdata = $this->model_payroll->getRekapitulasiDataLogPayrollLembur($form_filter);
		// }else{
		// 	$getdata = $this->model_payroll->getDataPayrollLemburExcel($where);
		// }
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$data_filter['periode']]));
		$total=0;
		$tPer=[];
		$data_lembur=[];
		$tPerx=[];
		$data_lemburx=[];
		$jumlahData = 3;
		$jumlahTotal = 0;
		$lemburAwal = 0;
		$lemburAkhir = 0;
		if(in_array('all',$data_filter['bagian'])){
			$dataFilter = [];
			// $bagian = $this->model_master->getListBagian(true);
			// foreach ($bagian as $d) {
			// 	$dataFilter[$d->id_bagian] = $d->kode_bagian;
			// }
			$bagian = $this->model_payroll->getBagianFromPeriode($data_filter['periode'],null,$log);
			foreach (array_filter($bagian) as $bkey) {
				$dataFilter[$bkey->kode_bagian] = $bkey->kode_bagian;
			}
			$data_filter['bagian'] = $dataFilter;
		}
		// echo '<pre>';
		$bulanM=$this->otherfunctions->getDataExplode($periode['tgl_mulai'],'-','end');
		$bulanDepan=$this->otherfunctions->getDataExplode($periode['tgl_selesai'],'-','end');
		$tahunM=$this->otherfunctions->getDataExplode($periode['tgl_mulai'],'-','start');
		$tahunDepan=$this->otherfunctions->getDataExplode($periode['tgl_selesai'],'-','start');
		foreach ($data_filter['bagian'] as $key => $bag) {
			$jumlahData+=1;
			$d_lembur=$this->model_karyawan->getDataLemburBagianMonth($bag, $bulanM, $tahunM);
			if (!empty($d_lembur)) {
				if (count($d_lembur) > 0) {
					$totalPer=0;
					foreach ($d_lembur as $dl) {
						if($dl->kode_bagian == $bag && $dl->tgl_mulai >= $periode['tgl_mulai']){
							$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
							$totalPer+=($nominalLembur);
						}
					}
					$nl=$totalPer;
					$data_lembur[5]=$this->formatter->getFormatMoneyUser($nl);
					$tPer[5]=$totalPer;
					$lemburAwal+=$totalPer;
					$totalBaris=$totalPer;
				}else{
					$data_lembur[5]='';
					$totalBaris=0;
				}
			}else{
				$data_lembur[5]='';
				$totalBaris=0;
			}
			$d_lemburx=$this->model_karyawan->getDataLemburBagianMonth($bag, $bulanDepan, $tahunDepan);
			if (!empty($d_lemburx)) {
				if (count($d_lemburx) > 0) {
					$totalPerx=0;
					foreach ($d_lemburx as $dlx) {
						if($dlx->kode_bagian == $bag && $dlx->tgl_mulai <= $periode['tgl_selesai']){
							$nominalLemburx=$this->payroll->getNominalLemburDate($dlx->id_karyawan, $dlx->tgl_mulai, $dlx->jenis_lembur, $dlx->val_jumlah_lembur, $dlx->val_potong_jam);
							$totalPerx+=($nominalLemburx);
						}
					}
					$nl=$totalPerx;
					$data_lemburx[6]=$this->formatter->getFormatMoneyUser($nl);
					$tPer[6]=$totalPerx;
					$lemburAkhir+=$totalPerx;
					$totalBarisx=$totalPerx;
				}else{
					$data_lemburx[6]='';
					$totalBarisx=0;
				}
			}else{
				$data_lemburx[6]='';
				$totalBarisx=0;
			}
			$body1=[
				($row-4),
				$this->model_master->getBagianKode($bag)['nama'],
				$this->model_master->getBagianKode($bag)['nama_loker'],
				$periode['nama'].' ('.$this->formatter->getDateFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateFormatUser($periode['tgl_selesai']).')',
				$this->formatter->getFormatMoneyUser($totalBaris+$totalBarisx),
			];
			// print_r($data_lembur);echo '<br>';
			$jumlahTotal+=array_sum($tPer);
			$data_body = array_merge($body1,$data_lembur,$data_lemburx);
			$body[$row]=$data_body;
			$row++;
		}
		$head_data_lembur[5]='Jumlah'."\n".$this->formatter->getNameOfMonth($bulanM);
		$head_data_lembur[6]='Jumlah'."\n".$this->formatter->getNameOfMonth($bulanDepan);
		$head1=['No', 'Bagian', 'Lokasi', 'Periode', 'Total'];
		$data_head = array_merge($head1,$head_data_lembur);
		$data_awal_null=[null,'TOTAL',null,null,$this->formatter->getFormatMoneyUser($lemburAwal+$lemburAkhir),$this->formatter->getFormatMoneyUser($lemburAwal),$this->formatter->getFormatMoneyUser($lemburAkhir)];
		$body[1]=['REKAPITULASI PENGGAJIAN LEMBUR PERIODE '.$this->formatter->getDateMonthFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateMonthFormatUser($periode['tgl_selesai'])];
		$body[$jumlahData+3]=$data_awal_null;
		$body[$jumlahData+5]=[null,'Mengetahui',null,'Menyetujui',null,'Dibuat'];
		$body[$jumlahData+4]=[null,null,null,null,null,'Karangjati, '.date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y')];
		$body[$jumlahData+9]=[null,$this->model_karyawan->getEmployeeId($data_filter['karyawan_bagian_mengetahui'])['nama'],null,$this->model_karyawan->getEmployeeId($data_filter['karyawan_bagian_menyetujui'])['nama'],null,'ADMIN HR'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head_merge'=>[
				'row_head'=>4,
				'data_head'=>$data_head,
				'max_merge'=>1,
				'merge_1'=>'A1:G1',
				'merge_2'=>'F'.($jumlahData+4).':G'.($jumlahData+4),
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_penggajian_lembur_bagian()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$where="a.create_by='".$this->admin."'";
		if ($level <= 2){
			$where="";
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		$log=false;
		if($data_filter['usage'] == 'log'){
			$log=true;
			$title_usage = 'Log Rekapitulasi';
		}else{
			$title_usage = 'Rekapitulasi Lembur Bagian';
		}
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		if (count($data_filter['bagian']) <= 0){
			$data_filter['bagian']=['all'];
		}
		if(!empty($data_filter['bagian'])){
			if(in_array('all',$data_filter['bagian'])){
				if ($where){
					$where.=" AND ";
				}
				$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
			}else{
				$c_lv=1;
				foreach ($data_filter['bagian'] as $key => $bag) {
					if ($where){
						$where.=" AND ";
					}
					$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."' AND a.kode_bagian='".$bag."'";
					if (count($data_filter['bagian']) > $c_lv) {
						$where.=' OR ';
					}
					$c_lv++;
				}
			}
		}
		$body=[];
		$row_body=5;
		$row=$row_body;
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$data_filter['periode']]));
		$total=0;
		$tPer=[];
		$data_lembur=[];
		$tPerx=[];
		$data_lemburx=[];
		$jumlahData = 3;
		$jumlahTotal = 0;
		$lemburAwal = 0;
		$lemburAkhir = 0;
		if(in_array('all',$data_filter['bagian'])){
			$dataFilter = [];
			$bagian = $this->model_payroll->getBagianFromPeriode($data_filter['periode'],null,$log);
			foreach (array_filter($bagian) as $bkey) {
				$dataFilter[$bkey->kode_bagian] = $bkey->kode_bagian;
			}
			$data_filter['bagian'] = $dataFilter;
		}
		$bulanM=$this->otherfunctions->getDataExplode($periode['tgl_mulai'],'-','end');
		$bulanDepan=$this->otherfunctions->getDataExplode($periode['tgl_selesai'],'-','end');
		$tahunM=$this->otherfunctions->getDataExplode($periode['tgl_mulai'],'-','start');
		$tahunDepan=$this->otherfunctions->getDataExplode($periode['tgl_selesai'],'-','start');
		if($bulanM == $bulanDepan){
			if($level != 0){
				$kar = $this->model_karyawan->getEmployeeId($this->dtroot['adm']['id_karyawan'], true);
				$petugas = $this->model_master->getListPetugasLemburWhere(['a.kode_petugas'=>$kar['jabatan'], 'a.status'=>'1'], null, 1,'a.update_date desc', true);
				if(!empty($petugas['id_karyawan'])){
					$idKar=$this->otherfunctions->getDataExplode($petugas['id_karyawan'],';','all');
					$or_id='';
					$c_lv=1;
					foreach ($idKar as $kl => $idKaryawan) {
						$or_id.="lem.id_karyawan='".$idKaryawan."'";
						if (count($idKar) > $c_lv) {
							$or_id.=' OR ';
						}
						$c_lv++;
					}
					$where="(".$or_id.")";
					foreach ($data_filter['bagian'] as $key => $bag) {
						$jumlahData+=1;
						$d_lembur=$this->model_karyawan->getDataLemburBagianIDKarMonth($where, $bulanM, $tahunM);
						if (!empty($d_lembur)) {
							if (count($d_lembur) > 0) {
								$totalPer=0;
								foreach ($d_lembur as $dl) {
									if($dl->kode_bagian == $bag && $dl->tgl_mulai >= $periode['tgl_mulai']){
										$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
										$totalPer+=($nominalLembur);
									}
								}
								$nl=$totalPer;
								$data_lembur[5]=$this->formatter->getFormatMoneyUser($nl);
								$tPer[5]=$totalPer;
								$lemburAwal+=$totalPer;
								$totalBaris=$totalPer;
							}else{
								$data_lembur[5]='';
								$totalBaris=0;
							}
						}else{
							$data_lembur[5]='';
							$totalBaris=0;
						}
						$body1=[
							($row-4),
							$this->model_master->getBagianKode($bag)['nama'],
							$this->model_master->getBagianKode($bag)['nama_loker'],
							$periode['nama'].' ('.$this->formatter->getDateFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateFormatUser($periode['tgl_selesai']).')',
							$this->formatter->getFormatMoneyUser($totalBaris),
						];
						$jumlahTotal+=array_sum($tPer);
						$data_body = array_merge($body1,$data_lembur);
						$body[$row]=$data_body;
						$row++;
					}
				}
			}else{
				foreach ($data_filter['bagian'] as $key => $bag) {
					$jumlahData+=1;
					$d_lembur=$this->model_karyawan->getDataLemburBagianMonth($bag, $bulanM, $tahunM);
					if (!empty($d_lembur)) {
						if (count($d_lembur) > 0) {
							$totalPer=0;
							foreach ($d_lembur as $dl) {
								if($dl->kode_bagian == $bag && $dl->tgl_mulai >= $periode['tgl_mulai']){
									$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
									$totalPer+=($nominalLembur);
								}
							}
							$nl=$totalPer;
							$data_lembur[5]=$this->formatter->getFormatMoneyUser($nl);
							$tPer[5]=$totalPer;
							$lemburAwal+=$totalPer;
							$totalBaris=$totalPer;
						}else{
							$data_lembur[5]='';
							$totalBaris=0;
						}
					}else{
						$data_lembur[5]='';
						$totalBaris=0;
					}
					$body1=[
						($row-4),
						$this->model_master->getBagianKode($bag)['nama'],
						$this->model_master->getBagianKode($bag)['nama_loker'],
						$periode['nama'].' ('.$this->formatter->getDateFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateFormatUser($periode['tgl_selesai']).')',
						$this->formatter->getFormatMoneyUser($totalBaris),
					];
					$jumlahTotal+=array_sum($tPer);
					$data_body = array_merge($body1,$data_lembur);
					$body[$row]=$data_body;
					$row++;
				}
			}
			$head_data_lembur[5]='Jumlah'."\n".$this->formatter->getNameOfMonth($bulanM);
			$head1=['No', 'Bagian', 'Lokasi', 'Periode', 'Total'];
			$data_head = array_merge($head1,$head_data_lembur);
			$data_awal_null=[null,'TOTAL',null,null,$this->formatter->getFormatMoneyUser($lemburAwal),$this->formatter->getFormatMoneyUser($lemburAwal)];
		}else{
			if($level != 0){
				$kar = $this->model_karyawan->getEmployeeId($this->dtroot['adm']['id_karyawan'], true);
				$petugas = $this->model_master->getListPetugasLemburWhere(['a.kode_petugas'=>$kar['jabatan'], 'a.status'=>'1'], null, 1,'a.update_date desc', true);
				if(!empty($petugas['id_karyawan'])){
					$idKar=$this->otherfunctions->getDataExplode($petugas['id_karyawan'],';','all');
					$or_id='';
					$c_lv=1;
					foreach ($idKar as $kl => $idKaryawan) {
						$or_id.="lem.id_karyawan='".$idKaryawan."'";
						if (count($idKar) > $c_lv) {
							$or_id.=' OR ';
						}
						$c_lv++;
					}
					$where="(".$or_id.")";
					foreach ($data_filter['bagian'] as $key => $bag) {
						$jumlahData+=1;
						$d_lembur=$this->model_karyawan->getDataLemburBagianIDKarMonth($where, $bulanM, $tahunM);
						if (!empty($d_lembur)) {
							if (count($d_lembur) > 0) {
								$totalPer=0;
								foreach ($d_lembur as $dl) {
									if($dl->kode_bagian == $bag && $dl->tgl_mulai >= $periode['tgl_mulai']){
										$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
										$totalPer+=($nominalLembur);
									}
								}
								$nl=$totalPer;
								$data_lembur[5]=$this->formatter->getFormatMoneyUser($nl);
								$tPer[5]=$totalPer;
								$lemburAwal+=$totalPer;
								$totalBaris=$totalPer;
							}else{
								$data_lembur[5]='';
								$totalBaris=0;
							}
						}else{
							$data_lembur[5]='';
							$totalBaris=0;
						}
						$d_lemburx=$this->model_karyawan->getDataLemburBagianIDKarMonth($where, $bulanDepan, $tahunDepan);
						if (!empty($d_lemburx)) {
							if (count($d_lemburx) > 0) {
								$totalPerx=0;
								foreach ($d_lemburx as $dlx) {
									if($dlx->kode_bagian == $bag && $dlx->tgl_mulai <= $periode['tgl_selesai']){
										$nominalLemburx=$this->payroll->getNominalLemburDate($dlx->id_karyawan, $dlx->tgl_mulai, $dlx->jenis_lembur, $dlx->val_jumlah_lembur, $dlx->val_potong_jam);
										$totalPerx+=($nominalLemburx);
									}
								}
								$nl=$totalPerx;
								$data_lemburx[6]=$this->formatter->getFormatMoneyUser($nl);
								$tPer[6]=$totalPerx;
								$lemburAkhir+=$totalPerx;
								$totalBarisx=$totalPerx;
							}else{
								$data_lemburx[6]='';
								$totalBarisx=0;
							}
						}else{
							$data_lemburx[6]='';
							$totalBarisx=0;
						}
						$body1=[
							($row-4),
							$this->model_master->getBagianKode($bag)['nama'],
							$this->model_master->getBagianKode($bag)['nama_loker'],
							$periode['nama'].' ('.$this->formatter->getDateFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateFormatUser($periode['tgl_selesai']).')',
							$this->formatter->getFormatMoneyUser($totalBaris+$totalBarisx),
						];
						$jumlahTotal+=array_sum($tPer);
						$data_body = array_merge($body1,$data_lembur,$data_lemburx);
						$body[$row]=$data_body;
						$row++;
					}
				}
			}else{
				foreach ($data_filter['bagian'] as $key => $bag) {
					$jumlahData+=1;
					$d_lembur=$this->model_karyawan->getDataLemburBagianMonth($bag, $bulanM, $tahunM);
					if (!empty($d_lembur)) {
						if (count($d_lembur) > 0) {
							$totalPer=0;
							foreach ($d_lembur as $dl) {
								if($dl->kode_bagian == $bag && $dl->tgl_mulai >= $periode['tgl_mulai']){
									$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
									$totalPer+=($nominalLembur);
								}
							}
							$nl=$totalPer;
							$data_lembur[5]=$this->formatter->getFormatMoneyUser($nl);
							$tPer[5]=$totalPer;
							$lemburAwal+=$totalPer;
							$totalBaris=$totalPer;
						}else{
							$data_lembur[5]='';
							$totalBaris=0;
						}
					}else{
						$data_lembur[5]='';
						$totalBaris=0;
					}
					$d_lemburx=$this->model_karyawan->getDataLemburBagianMonth($bag, $bulanDepan, $tahunDepan);
					if (!empty($d_lemburx)) {
						if (count($d_lemburx) > 0) {
							$totalPerx=0;
							foreach ($d_lemburx as $dlx) {
								if($dlx->kode_bagian == $bag && $dlx->tgl_mulai <= $periode['tgl_selesai']){
									$nominalLemburx=$this->payroll->getNominalLemburDate($dlx->id_karyawan, $dlx->tgl_mulai, $dlx->jenis_lembur, $dlx->val_jumlah_lembur, $dlx->val_potong_jam);
									$totalPerx+=($nominalLemburx);
								}
							}
							$nl=$totalPerx;
							$data_lemburx[6]=$this->formatter->getFormatMoneyUser($nl);
							$tPer[6]=$totalPerx;
							$lemburAkhir+=$totalPerx;
							$totalBarisx=$totalPerx;
						}else{
							$data_lemburx[6]='';
							$totalBarisx=0;
						}
					}else{
						$data_lemburx[6]='';
						$totalBarisx=0;
					}
					$body1=[
						($row-4),
						$this->model_master->getBagianKode($bag)['nama'],
						$this->model_master->getBagianKode($bag)['nama_loker'],
						$periode['nama'].' ('.$this->formatter->getDateFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateFormatUser($periode['tgl_selesai']).')',
						$this->formatter->getFormatMoneyUser($totalBaris+$totalBarisx),
					];
					$jumlahTotal+=array_sum($tPer);
					$data_body = array_merge($body1,$data_lembur,$data_lemburx);
					$body[$row]=$data_body;
					$row++;
				}
			}
			$head_data_lembur[5]='Jumlah'."\n".$this->formatter->getNameOfMonth($bulanM);
			$head_data_lembur[6]='Jumlah'."\n".$this->formatter->getNameOfMonth($bulanDepan);
			$head1=['No', 'Bagian', 'Lokasi', 'Periode', 'Total'];
			$data_head = array_merge($head1,$head_data_lembur);
			$data_awal_null=[null,'TOTAL',null,null,$this->formatter->getFormatMoneyUser($lemburAwal+$lemburAkhir),$this->formatter->getFormatMoneyUser($lemburAwal),$this->formatter->getFormatMoneyUser($lemburAkhir)];
		}
		$body[1]=['REKAPITULASI PENGGAJIAN LEMBUR PERIODE '.$this->formatter->getDateMonthFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateMonthFormatUser($periode['tgl_selesai'])];
		$body[$jumlahData+3]=$data_awal_null;
		$body[$jumlahData+5]=[null,'Mengetahui',null,'Menyetujui',null,'Dibuat'];
		$body[$jumlahData+4]=[null,null,null,null,null,'Karangjati, '.date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y')];
		$body[$jumlahData+9]=[null,$this->model_karyawan->getEmployeeId($data_filter['karyawan_bagian_mengetahui'])['nama'],null,$this->model_karyawan->getEmployeeId($data_filter['karyawan_bagian_menyetujui'])['nama'],null,'ADMIN HR'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head_merge'=>[
				'row_head'=>4,
				'data_head'=>$data_head,
				'max_merge'=>1,
				'merge_1'=>'A1:G1',
				'merge_2'=>'F'.($jumlahData+4).':G'.($jumlahData+4),
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		// echo '<pre>';
		// print_r($data);
		$this->rekapgenerator->genExcel($data);
	}
	public function export_penggajian_lembur_jabatan()
	{	
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$where="a.create_by='".$this->admin."'";
		if ($level <= 2){
			$where="";
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		$log=false;
		if($data_filter['usage'] == 'log'){
			$log=true;
			$title_usage = 'Log Rekapitulasi';
		}else{
			$title_usage = 'Rekapitulasi Lembur Jabatan';
		}
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		if(!empty($data_filter['jabatan'])){
			if(in_array('all',$data_filter['jabatan'])){
				if ($where){
					$where.=' AND ';
				}
				$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
			}else{
				$c_lv=1;
				foreach ($data_filter['jabatan'] as $key => $jab) {
					if ($where){
						$where.=' AND ';
					}
					$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."' AND a.kode_jabatan='".$jab."'";
					if (count($data_filter['jabatan']) > $c_lv) {
						$where.=' OR ';
					}
					$c_lv++;
				}
			}
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		// if($data_filter['usage'] == 'log'){
		// 	$getdata = $this->model_payroll->getRekapitulasiDataLogPayrollLembur($form_filter);
		// }else{
		// 	$getdata = $this->model_payroll->getDataPayrollLemburExcel($where);
		// }
		$periode = $this->otherfunctions->convertResultToRowArray($this->model_master->getListPeriodeLembur(['a.kode_periode_lembur'=>$data_filter['periode']]));
		$total=0;
		$tPer=[];
		$data_lembur=[];
		$tPerx=[];
		$data_lemburx=[];
		$jumlahData = 0;
		$jumlahTotal = 0;
		$lemburAwal = 0;
		$lemburAkhir = 0;
		if(in_array('all',$data_filter['jabatan'])){
			$dataFilter = [];
			// $jabatan = $this->model_master->getListJabatan(true);
			// foreach ($jabatan as $d) {
			// 	$dataFilter[$d->id_jabatan] = $d->kode_jabatan;
			// }
			$jabatan = $this->model_payroll->getJabatanFromPeriode($data_filter['periode'],null,$log);
			foreach (array_filter($jabatan) as $bkey) {
				$dataFilter[$bkey->kode_jabatan] = $bkey->kode_jabatan;
			}
			$data_filter['jabatan'] = $dataFilter;
		}
		foreach ($data_filter['jabatan'] as $key => $bag) {
			$jumlahData+=1;
			$bulanM=$this->otherfunctions->getDataExplode($periode['tgl_mulai'],'-','end');
			$bulanDepan=$this->otherfunctions->getDataExplode($periode['tgl_selesai'],'-','end');
			$tahunM=$this->otherfunctions->getDataExplode($periode['tgl_mulai'],'-','start');
			$tahunDepan=$this->otherfunctions->getDataExplode($periode['tgl_selesai'],'-','start');
			$d_lembur=$this->model_karyawan->getDataLemburJabatanMonth($bag, $bulanM, $tahunM);
			if (!empty($d_lembur)) {
				if (count($d_lembur) > 0) {
					$totalPer=0;
					foreach ($d_lembur as $dl) {
						if($dl->kode_jabatan == $bag && $dl->tgl_mulai >= $periode['tgl_mulai']){
							$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
							$totalPer+=$nominalLembur;
							$nl=($totalPer);
							$data_lembur[5]=$this->formatter->getFormatMoneyUser($nl);
						}
					}
					$tPer[5]=$totalPer;
					$lemburAwal+=$totalPer;
					$totalBaris=$totalPer;
				}else{
					$data_lembur[5]='';
					$totalBaris=0;
				}
			}else{
				$data_lembur[5]='';
				$totalBaris=0;
			}
			$d_lemburx=$this->model_karyawan->getDataLemburJabatanMonth($bag, $bulanDepan, $tahunDepan);
			if (!empty($d_lemburx)) {
				if (count($d_lemburx) > 0) {
					$totalPerx=0;
					foreach ($d_lemburx as $dlx) {
						if($dlx->kode_jabatan == $bag && $dlx->tgl_mulai <= $periode['tgl_selesai']){
							$nominalLembur=$this->payroll->getNominalLemburDate($dlx->id_karyawan, $dlx->tgl_mulai, $dlx->jenis_lembur, $dlx->val_jumlah_lembur, $dlx->val_potong_jam);
							$totalPerx+=$nominalLembur;
							$nl=($totalPerx);
							$data_lemburx[6]=$this->formatter->getFormatMoneyUser($nl);
						}
					}
					$tPer[6]=$totalPerx;
					$lemburAkhir+=$totalPerx;
					$totalBarisx=$totalPerx;
				}else{
					$data_lemburx[6]='';
					$totalBarisx=0;
				}
			}else{
				$data_lemburx[6]='';
				$totalBarisx=0;
			}
			$body1=[
				($row-1),
				$this->model_master->getJabatanKodeRow($bag)['nama'],
				$this->model_master->getJabatanKodeRow($bag)['nama_lokasi'],
				$periode['nama'].' ('.$this->formatter->getDateFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateFormatUser($periode['tgl_selesai']).')',
				$this->formatter->getFormatMoneyUser($totalBaris+$totalBarisx),
			];
			$jumlahTotal+=array_sum($tPer);
			$data_body = array_merge($body1,$data_lembur,$data_lemburx);
			$body[$row]=$data_body;
			$row++;
			$jumlahData++;
		}
		$head_data_lembur[5]='Jumlah'."\n".$this->formatter->getNameOfMonth($bulanM);
		$head_data_lembur[6]='Jumlah'."\n".$this->formatter->getNameOfMonth($bulanDepan);
		$head1=['No', 'Jabatan', 'Lokasi', 'Periode', 'Total'];
		$data_head = array_merge($head1,$head_data_lembur);
		$data_awal_null=[null,'TOTAL',null,null,$this->formatter->getFormatMoneyUser($jumlahTotal),$this->formatter->getFormatMoneyUser($lemburAwal),$this->formatter->getFormatMoneyUser($lemburAkhir)];
		$body[$row+1]=$data_awal_null;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_gaji_lembur_transfer_bank()
	{
		parse_str($this->input->post('data_filter'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$where="a.create_by='".$this->admin."'";
		if ($level <= 2){
			$where="";
		}
		if(!empty($data_filter['bagian'])){
			if(in_array('all',$data_filter['bagian'])){
				if ($where){
					$where.=" AND ";
				}
				$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
			}else{
				if (!is_array($data_filter['bagian'])){
					if ($where){
						$where.=" AND ";
					}
					$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."' AND a.kode_bagian='".$data_filter['bagian']."'";
				}else{
					$bg='';
					$c_lv=1;
					foreach ($data_filter['bagian'] as $key => $bag) {
						$bg.="a.kode_bagian='".$bag."'";
						if (count($data_filter['bagian']) > $c_lv) {
							$bg.=' OR ';
						}
						$c_lv++;
					}
					
					if ($where){
						$where.=" AND ";
					}
					$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
					if ($bg != ''){
						$where.=" AND (".$bg.")";
					}	
				}
			}
		}else{
			if ($where){
				$where.=" AND ";
			}
			$where.="a.kode_master_penggajian='BULANAN' AND a.kode_periode='".$data_filter['periode']."'";
		}
		if($data_filter['usage'] == 'log'){
			$title_usage = 'Rekap Log Lembur Transfer Bank';
		}else{
			$title_usage = 'Rekap Lembur Transfer Bank';
		}
		$post_form = $data_filter;
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		$body=[];
		$body_group=[];
		$row_body=2;
		$row=$row_body;
		if($data_filter['usage'] == 'data'){
			$getdata = $this->model_payroll->getDataPayrollLemburExcel($where);
		}elseif($data_filter['usage'] == 'log'){
			$getdata = $this->model_payroll->getDataLogPayrollLembur($where);
		}
		foreach ($getdata as $d) {
			if($d->gaji_terima > 0){
				$body_group[$d->nama_bagian][]=[
					$d->no_rekening,
					floor($d->gaji_terima),
					$d->nik,
					$d->nama_karyawan,
					null,
				];
				$row++;
			}
		}
		if ($body_group){
			$row=2;
			foreach ($body_group as $nama_d => $val) {
				$count_dept=count($val);
				if ($val) {
					$row_dept=0;
					foreach ($val as $v){
						if ($row==2) {
							array_push($body,['#',$nama_d]);
							$body[2]=$body[0];
							unset($body[0]);
							$row++;
						}elseif (($count_dept-$row_dept) == $count_dept) {
							array_push($body,['#',$nama_d]);
							$row++;
						}
						$body[$row]=$v;
						$row++;
						$row_dept++;
					}
				}
			}
		}
		$data_head = ['No Rekening', 'Jumlah Transfer', 'NIP', 'NAMA', 'Dept'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_rekapitulasi_lembur()
	{
		$data_filter = [];
		parse_str($this->input->post('data_filter_bagian'), $data_filter);
		$level=((isset($this->dtroot['adm']['level']))?$this->dtroot['adm']['level']:null);
		if ($level > 2){
			$form_filter['a.create_by'] = $this->admin;
		}
		$form_filter['a.kode_master_penggajian'] = 'BULANAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }

		$post_form = $form_filter;
		$data['properties']=[
			'title'=>"Rekapitulasi Lembur",
			'subject'=>"Rekapitulasi Lembur",
			'description'=>"Rekapitulasi Lembur HSOFT JKB",
			'keywords'=>"Export, Rekap, Rekapitulasi Lembur",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$id_admin = $this->admin;
		$loker = $this->model_payroll->getDataLogPayrollLembur($post_form, 0, 'a.kode_loker');
		$get_loker = [];
		$datax = [];
		foreach ($loker as $l) {
			$get_loker[] = $l->kode_loker;
		}
		foreach ($get_loker as $gk => $gv) {
			$post_form['a.kode_loker'] = $gv;
			$data_log = $this->model_payroll->getDataLogPayrollLembur($post_form);
			$nama_loker = $this->otherfunctions->convertResultToRowArray($this->model_master->getLokerKode($gv));
			$keterangan = $nama_loker['nama'];
			$gaji_pokok = 0;
			$upah = 0;
			$jam_biasa = 0;
			$nominal_biasa = 0;
			$jam_libur = 0;
			$nominal_libur = 0;
			$jam_libur_pendek = 0;
			$nominal_libur_pendek = 0;
			$penerima = 0;
			foreach ($data_log as $d) {
				$gaji_pokok += $d->gaji_pokok;
				$upah += $d->upah;
				$jam_biasa += $d->jam_biasa;
				$nominal_biasa += $d->nominal_biasa;
				$jam_libur += $d->jam_libur;
				$nominal_libur += $d->nominal_libur;
				$jam_libur_pendek += $d->jam_libur_pendek;
				$nominal_libur_pendek += $d->nominal_libur_pendek;
				$penerima += $d->gaji_terima;
			}
			$datax[$gv] = [
				'keterangan'=>$keterangan,
				'gaji_pokok'=>$gaji_pokok,
				'upah'=>$upah,
				'jam_biasa'=>$jam_biasa,
				'nominal_biasa'=>$nominal_biasa,
				'jam_libur'=>$jam_libur,
				'nominal_libur'=>$nominal_libur,
				'jam_libur_pendek'=>$jam_libur_pendek,
				'nominal_libur_pendek'=>$nominal_libur_pendek,
				'penerima'=>$penerima
			];
		}
		foreach ($datax as $dk => $dv) {
			$body_1 = [
				($row-1),
				$dv['keterangan'],
				$this->formatter->getFormatMoneyUser($dv['gaji_pokok']),
				$this->formatter->getFormatMoneyUser($dv['upah']),
				$dv['jam_biasa'],
				$this->formatter->getFormatMoneyUser($dv['nominal_biasa']),
				$dv['jam_libur'],
				$this->formatter->getFormatMoneyUser($dv['nominal_libur']),
				$dv['jam_libur_pendek'],
				$this->formatter->getFormatMoneyUser($dv['nominal_libur_pendek']),
				$this->formatter->getFormatMoneyUser($dv['penerima']),
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}
		$data_head = ['No', 'Keterangan', 'Gaji Pokok', 'Upah', 'Jam Hari Biasa', 'Nominal Hari Biasa', 'Jam Hari Libur', 'Nominal Hari Libur', 'Jam Libur Pendek', 'Nominal Libur Pendek', 'Gaji Diterima'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekapitulasi Lembur',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_presensi_view()
	{
		$mode = $this->input->post('mode');
		$usage = $this->input->post('usage');
		$tanggal = $this->input->post('tanggal');
		$nik = $this->input->post('nik');

		$tanggal_mulai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'start')));
		$tanggal_selesai = date('Y-m-d',strtotime($this->formatter->getDateFromRange($tanggal,'end')));
		if($usage == 'all'){
			$tanggal_selesai = date('Y-m-d');
			$tanggal_mulai = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_selesai)));
		}

		$emp = $this->model_karyawan->getEmployeeNik($nik);
		$id = $emp['id_karyawan'];
		$user = $this->dtroot;
		$data['properties']=[
			'title'=>"Rekap Data Rencana Lembur Kerja - ".$emp['nama']." - ".$emp['nik'],
			'subject'=>"Rekap Data Presensi",
			'description'=>"Rekap Data Rencana Lembur Kerja HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Presensi",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		while (strtotime($tanggal_selesai)>=strtotime($tanggal_mulai)){
			$libur = $this->otherfunctions->checkHariLibur($tanggal_selesai);
			$cek_data = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getListPresensiId(null, ['pre.id_karyawan'=>$emp['id_karyawan'],'pre.tanggal'=>$tanggal_selesai]));
			if(empty($cek_data)){
				$xno_spl = '';
				$xno_ijin = '';
				$xjam_masuk = '';
				$xjam_keluar = '';
				$xjml_jam = '';
				$xjdwal_kerja = '';
				$xover = '';
				$xijin = '';
				$xlembur = '';
				$xhari_libur = $libur;
			}else{
				$newdata = $this->model_karyawan->getListPresensiId(null, ['pre.id_karyawan'=>$id,'pre.tanggal'=>$tanggal_selesai]);
				foreach ($newdata as $d) {
					$cekjadwal = $this->model_karyawan->cekJadwalKerjaIdDate($id,$tanggal_selesai);
					if(empty($cekjadwal['nama_shift'])){
						$jadwal_jam_kerja = '';
						$over = '';
					}else{
						$nama_shift = (empty($cekjadwal['nama_shift'])) ? '' : $cekjadwal['nama_shift'];
						$jam_mulai = (empty($cekjadwal['jam_mulai'])) ? '' : $cekjadwal['jam_mulai'];
						$jam_selesai = (empty($cekjadwal['jam_selesai'])) ? '' : $cekjadwal['jam_selesai'];
						$jadwal_jam_kerja = $nama_shift.' ['.$jam_mulai.' - '.$jam_selesai.']';
						$over = $this->otherfunctions->getInterval($jam_selesai,$d->jam_selesai);
						$over = $over->h.'jam '.$over->i.'menit';
					}

					$ijin_cuti = $this->model_karyawan->getIzinCuti(null, ['a.id_karyawan'=>$id,'a.tgl_mulai <='=>$tanggal_selesai,'a.tgl_selesai >='=>$tanggal_selesai]);
					$ijin_cuti =  $this->otherfunctions->convertResultToRowArray($ijin_cuti);
					$lembur = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getLembur(null, ['a.id_karyawan'=>$id,'a.tgl_mulai '=>$tanggal_selesai]));
					if(!empty($lembur)){
						$lembur = (empty($lembur['jumlah_lembur'])) ? '' : (int)explode(":",$lembur['jumlah_lembur'])[0];
					}
					if(!empty($libur)){
						$lembur = $this->otherfunctions->getIntervalJam($d->jam_mulai,$d->jam_selesai);
					}

					$xno_spl = $d->no_spl;
					$xno_ijin = (empty($ijin_cuti['kode_izin_cuti'])) ? '' : $ijin_cuti['kode_izin_cuti'];
					$xjam_masuk = $this->formatter->getTimeFormatUser($d->jam_mulai,'WIB');
					$xjam_keluar = $this->formatter->getTimeFormatUser($d->jam_selesai,'WIB');
					$xjml_jam = $this->otherfunctions->getIntervalJam($d->jam_mulai,$d->jam_selesai);
					$xjdwal_kerja = $jadwal_jam_kerja;
					$xover = $over;
					$xijin = (empty($ijin_cuti['nama_jenis_izin'])) ? '' : $ijin_cuti['nama_jenis_izin'];
					$xlembur = $lembur;
					$xhari_libur = $libur;
				}
			}
			$body[$row]=[
				($row-1),
				$emp['nama'],
				$emp['nama'],
				$emp['nama_jabatan'],
				$emp['nama_bagian'],
				$emp['nama_loker'],
				$this->formatter->getDateMonthFormatUser($tanggal_selesai),
				$xno_spl,
				$xno_ijin,
				$xjam_masuk,
				$xjam_keluar,
				$xjml_jam,
				$xjdwal_kerja,
				$xover,
				$xijin,
				$xlembur,
				$xhari_libur
			];
			$row++;

			$tanggal_selesai = mktime(0,0,0,date("m",strtotime($tanggal_selesai)),date("d",strtotime($tanggal_selesai))-1,date("Y",strtotime($tanggal_selesai)));
			$tanggal_selesai=date("Y-m-d", $tanggal_selesai);
		}
		
		
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Presensi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'NIK',
					'Nama',
					'Jabatan',
					'Bagian',
					'Lokasi Kerja',
					'Tanggal Presensi',
					'Nomor SPL',
					'Nomor Ijin',
					'Jam Masuk',
					'Jam Keluar',
					'Jumlah Jam Kerja',
					'Jadwal Kerja',
					'Over',
					'Ijin/Cuti',
					'Lembur',
					'Hari Libur'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}

	public function export_gaji_lembur_perhitungan()
	{
		parse_str($this->input->post('data_filter'), $data_filter);	
		$periode = $this->model_payroll->getListPeriodeLembur(['a.kode_periode_lembur'=>$data_filter['periode']], true);
		$detailPeriode = $this->model_payroll->getListPeriodeLemburDetail(['a.kode_periode_lembur'=>$data_filter['periode']]);
		$mulai = $periode['tgl_mulai'];
		$selesai = $periode['tgl_selesai'];
	   	$where ='';
		// echo '<pre>';
		if(!empty($data_filter['bagian'])){
			if($data_filter['bagian'] == 'all'){
				$bagianx = [];
				if(!empty($detailPeriode)){
					foreach ($detailPeriode as $d) {
						$bagian = $this->otherfunctions->getDataExplode($d->id_bagian,';','all');
						if(!empty($bagian)){
							foreach ($bagian as $key => $idBagian) {
								$bag = $this->model_master->getBagianRow($idBagian);
								$bagianx[$bag['kode_bagian']] = $bag['nama'];
							}
						}
					}
				}
				$or_lv='';
				$c_lv=1;
				foreach ($bagianx as $kode => $nama) {
					$or_lv.="jab.kode_bagian='".$kode."'";
					if (count($bagianx) > $c_lv) {
						$or_lv.=' OR ';
					}
					$c_lv++;
				}
				$where = "a.validasi = '1' AND a.status_potong = '1' AND a.val_jumlah_lembur IS NOT NULL AND a.val_potong_jam IS NOT NULL AND a.jenis_lembur IS NOT NULL and (".$or_lv.")";
				// $where="emp.loker = '".$lokasi."' and (".$or_lv.")";
				// print_r($where);
			}else{
				$where = "jab.kode_bagian='".$data_filter['bagian']."' AND a.validasi = '1' AND a.status_potong = '1' AND a.val_jumlah_lembur IS NOT NULL AND a.val_potong_jam IS NOT NULL AND a.jenis_lembur IS NOT NULL";
			}
		}
		$getdata=$this->model_karyawan->getDataLemburAllWhere($where, $mulai, $selesai);
		// print_r($getdata);
		// print_r($periode);
		// print_r($detailPeriode);
		// print_r($bagianx);
		$user = $this->dtroot;
		$data['properties']=[
			'title'=>"Rekap Data Lembur & Perhitungan Karyawan",
			'subject'=>"Rekap Data Lembur & Perhitungan Karyawan",
			'description'=>"Rekap Data Lembur & Perhitungan Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Rekap, Export, Lembur, Lembur Karyawan",
			'category'=>"Export",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if(!empty($data_filter['periode']) && !empty($data_filter['bagian']) && !empty($getdata)){
			foreach ($getdata as $d) {
				$jam_lembur_real	= $this->formatter->convertJamtoDecimal($d->val_jumlah_lembur);
				$jam_potong_lembur	= $this->formatter->convertJamtoDecimal($d->val_potong_jam);
				$jam_lemburx		= ($jam_lembur_real - $jam_potong_lembur);
				$jam_lembur			= $this->formatter->convertDecimaltoJam($jam_lemburx);
				$nominalLembur		= $this->payroll->getNominalLemburDateNew($d->id_karyawan, $d->tgl_mulai, $d->jenis_lembur, $d->val_jumlah_lembur, $d->val_potong_jam);
				$nomLembur			= $this->otherfunctions->nonPembulatan($nominalLembur['nominal']);
				$body[$row]=[
					($row-1),
					(isset($d->tgl_mulai)?$this->formatter->getDateMonthFormatUser($d->tgl_mulai):null),
					(isset($d->tgl_mulai)?$this->formatter->getNameOfDay($d->tgl_mulai):null),
					$d->nama_karyawan,
					$d->kode_customer,
					$d->keterangan,
					$this->formatter->timeFormatUser($d->jam_mulai),
					$this->formatter->timeFormatUser($d->jam_selesai),
					$d->jumlah_lembur,
					$d->val_potong_jam,
					$jam_lembur,
					$nominalLembur['ekuivalen'],
					$this->formatter->getFormatMoneyUserReq($nomLembur),
					$this->otherfunctions->getStatusIzinRekap($d->validasi),
					$d->nama_jabatan,
					$d->nama_loker,
				];
				$row++;
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Lembur & Perhitungan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No',
					'Tanggal',
					'Hari',
					'Nama',
					'Nama/Kode Proyek',
					'Keterangan',
					'Mulai Lembur',
					'Selesai Lembur',
					'Jumlah Jam Lembur',
					'Koreksi Jam'."\n".'(Satuan menit)',
					'Jumlah Jam Lembur'."\n".'Setelah Dikoreksi',
					'Ekuivalen Lembur',
					'Rp Lembur',
					'Status Validasi',
					'Jabatan',
					'Plant',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
   	}
	public function export_data_pendukung_lain()
	{
		$data['properties']=[
			'title'=>'Rekap Data Pendukung Lain',
			'subject'=>'Rekap Data Pendukung Lain',
			'description'=>"Rekap Data Pendukung Lain HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data Pendukung Lain",
			'category'=>"Rekap",
		];

		$body=[];
		$row_body=2;
		$row=$row_body;

		$datax = $this->model_payroll->getListDataPendukungLain(null,null,null,'a.id_karyawan asc');
		foreach ($datax as $d) {
			$emp_data = $this->model_karyawan->getEmployeeId($d->id_karyawan);
			$body_1 = [
				($row-1),
				$emp_data['nik'],
				$emp_data['nama'],
				$emp_data['nama_jabatan'],
				$emp_data['bagian'],
				$emp_data['nama_loker'],
				$emp_data['nama_grade'],
				$d->nominal,
				ucwords($d->sifat),
				$this->otherfunctions->getYesNo($d->hallo),
				ucwords($d->keterangan),
				$d->tahun,
				$d->nama_periode,
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}

		$data_head = ['No', 'NIK', 'Nama', 'Jabatan', 'Bagian', 'Lokasi', 'Grade', 'Nominal', 'Sifat', 'Keterangan','Tahun','Periode Penggajian'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Pendukung Lain',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_template_data_pendukung_lain()
	{
		$data['properties']=[
			'title'=>'Template Data Pendukung Lain',
			'subject'=>'Template Data Pendukung Lain',
			'description'=>"Template Data Pendukung Lain HSOFT JKB",
			'keywords'=>"Export, Template, Template Data Pendukung Lain",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$data_head = ['No', 'NIK', 'Nama Karyawan', 'Nama Pendukung Lain', 'Nominal', 'Sifat'."\n".'(penambah/pengurang)', 'Kartu Hallo (1/0)', 'Keterangan'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Pendukung Lain',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}

	public function export_data_insentif()
	{
		$data['properties']=[
			'title'=>'Rekap Data Insentif Karyawan',
			'subject'=>'Rekap Data Insentif Karyawan',
			'description'=>"Rekap Data Insentif Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data Insentif Karyawan",
			'category'=>"Rekap",
		];

		$body=[];
		$row_body=2;
		$row=$row_body;

		$data_selet_ins = $this->model_master->getListInsentif();
		$emp_ins = [];
		foreach ($data_selet_ins as $si) {
			$emp_ins[] = $si->id_karyawan; 
		}
		$emp_ins = array_values(array_unique(explode(";",implode(";",$emp_ins))));
		$emp_insx = [];
		foreach ($emp_ins as $ekey => $evalue) {
			$data_empx = $this->model_karyawan->getEmployeeId($evalue);
			$emp_insx[$evalue] = $data_empx['nama'];
		}
		natcasesort($emp_insx);

		foreach ($emp_insx as $ik => $iv) {
			$id_karyawan = $ik;
			$data_emp = $this->model_karyawan->getEmployeeId($id_karyawan);
			foreach ($data_selet_ins as $si) {
				$send_ins = $this->otherfunctions->convertResultToRowArray($this->model_master->getInsentifWhere(['id_insentif'=>$si->id_insentif]));
				$get_ins = $this->payroll->getInsentifPerId($send_ins,$id_karyawan);
				if(!empty($get_ins)){
					$body_1 = [
						($row-1),
						$data_emp['nik'],
						$data_emp['nama'],
						$data_emp['nama_jabatan'],
						$data_emp['bagian'],
						$data_emp['nama_loker'],
						$data_emp['nama_grade'],
						$get_ins['nama'],
						$get_ins['nominal'],
						$get_ins['tahun'],
					];
					$data_body = $body_1;
					$body[$row]=$data_body;
					$row++;
				}
			}
		}

		$data_head = ['No', 'NIK', 'Nama', 'Jabatan', 'Bagian', 'Lokasi', 'Grade', 'Nama Insentif', 'Nominal Insentif', 'Tahun'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Insentif Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}

//=================================================== GAJI_HARIAN =====================================================================
	public function export_log_data_gaji_harian()
	{
		$id_admin = $this->admin;
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		if($data_filter['usage'] == 'log'){
			$title_usage = 'Rekap Log Payroll Harian';
		}else{
			$title_usage = 'Rekap Payroll Harian';
		}
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		// echo '<pre>';
		// print_r($form_filter);
		if(!empty($data_filter['bagian'])){ $form_filter['a.kode_bagian'] = $data_filter['bagian']; }
		if(!empty($data_filter['lokasi'])){ $form_filter['a.kode_loker'] = $data_filter['lokasi']; }
		if(!empty($data_filter['minggu'])){ $form_filter['a.minggu'] = $data_filter['minggu']; }
		if(!empty($data_filter['bulan'])){ $form_filter['a.bulan'] = $data_filter['bulan']; }
		if(!empty($data_filter['tahun'])){ $form_filter['a.tahun'] = $data_filter['tahun']; }
		$post_form = $form_filter;
		$body=[];
		$row_body=2;
		$row=$row_body;
		if($data_filter['usage'] == 'data'){
			$getdata = $this->model_payroll->getDataPayrollHarian($post_form);
		}elseif($data_filter['usage'] == 'log'){
			$getdata = $this->model_payroll->getDataLogPayrollHarian($post_form);
		}
		$tgl_mulai=null;
		$tgl_selesai=null;
		$total_gaji=0;
		$total_gaji_lembur=0;
		$total_gaji_harian=0;
		$jam_hari_biasa=0;
		$nominal_hari_biasa=0;
		$jam_hari_libur=0;
		$nominal_hari_libur=0;
		$jam_hari_libur_pendek=0;
		$nominal_hari_libur_pendek=0;
		$ekuivalen=0;
		$total_jam_lembur=0;
		$total_pengurang_lain=0;
		$total_penambah_lain=0;
		$total_pengurang_hallo=0;
		$total_penambah_hallo=0;
		foreach ($getdata as $d) {
			$tgl_mulai=$d->tgl_mulai;
			$tgl_selesai=$d->tgl_selesai;
			$yy=11;
			$data_lembur=[];
			while ($d->tgl_mulai <= $d->tgl_selesai)
			{
				$d_lembur=$this->model_karyawan->getDataLemburDate($d->id_karyawan, $d->tgl_mulai);
				$d_pre=$this->model_karyawan->checkPresensiEmpDate($d->id_karyawan, $d->tgl_mulai);
				$presensi = 'Hadir';
				if(((!empty($d_pre['jam_mulai']) && !empty($d_pre['jam_selesai'])) && ($d_pre['jam_mulai'] != "00:00:00" && $d_pre['jam_selesai'] != "00:00:00"))){
					$libur =  $this->otherfunctions->checkHariLiburActive($d->tgl_mulai);
					if(isset($libur) || !empty($libur)){
						// $presensi_libur[] = $tgl_mulai;
						$presensi='';
					}
				}else{
					$presensi='';
				}
				if (!empty($d_lembur)) {
					if (count($d_lembur) > 0) {
						$max_lem = count($d_lembur);
						$row_lem = 1;
						foreach ($d_lembur as $dl) {
							$jam_lembur=$this->formatter->convertJamtoDecimal($dl->jumlah_lembur);
							if($d->tgl_mulai = $dl->tgl_mulai){
								$nominalLembur=$this->payroll->getNominalLemburDate($d->id_karyawan, $d->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
								$nl=$this->otherfunctions->nonPembulatan($nominalLembur);
								$data_lembur[$yy]=$presensi.' | '.$jam_lembur." Jam | ".$this->formatter->getFormatMoneyUser($nl).' | '.$dl->kode_customer.' | '.$dl->keterangan.(($max_lem > ($row_lem))?"\n":'');
							}
							$row_lem++;
						}
					}else{
						$data_lembur[$yy]=$presensi;
					}
				}else{
					$data_lembur[$yy]=$presensi;
				}			
				$d->tgl_mulai = date('Y-m-d', strtotime($d->tgl_mulai . ' +1 day'));
				$yy++;
			}
			$pengurang_lainx=0;
			$penambah_lainx=0;
			$pengurang_lainx_hallo=0;
			$penambah_lainx_hallo=0;
			if(!empty($d->data_lain)){
				if (strpos($d->data_lain, ';') !== false) {
					$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
					$dHallo = $this->otherfunctions->getDataExplode($d->data_lain_hallo,';','all');
					$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
					foreach ($dLain as $key => $value) {
						if($value == 'pengurang'){
							if($dHallo[$key] == '1'){
								$pengurang_lainx_hallo += $nLain[$key];
							}else{
								$pengurang_lainx += $nLain[$key];
							}
						}else{
							if($dHallo[$key] == '1'){
								$penambah_lainx_hallo += $nLain[$key];
							}else{
								$penambah_lainx += $nLain[$key];
							}
						}
					}
				}else{
					if($d->data_lain == 'pengurang'){
						if($d->data_lain_hallo == '1'){
							$pengurang_lainx_hallo += $d->nominal_lain;
						}else{
							$pengurang_lainx += $d->nominal_lain;
						}
					}else{
						if($d->data_lain_hallo == '1'){
							$penambah_lainx_hallo += $d->nominal_lain;
						}else{
							$penambah_lainx += $d->nominal_lain;
						}
					}
				}
			}
			$pengurang_lain = $pengurang_lainx;
			$penambah_lain = $penambah_lainx;
			$pengurang_hallo = $pengurang_lainx_hallo;
			$penambah_hallo = $penambah_lainx_hallo;
			$total_pengurang_lain += $pengurang_lainx;
			$total_penambah_lain += $penambah_lainx;
			$total_pengurang_hallo += $pengurang_lainx_hallo;
			$total_penambah_hallo += $penambah_lainx_hallo;
			$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
			$body1=[($row-1),$emp['nik'],$d->nama_karyawan,$d->nama_jabatan,$d->nama_bagian,$d->nama_loker,$this->formatter->getDateMonthFormatUser($d->tgl_masuk),$d->masa_kerja,' ('.$this->formatter->getDateFormatUser($tgl_mulai).' - '.$this->formatter->getDateFormatUser($tgl_selesai).')',];
			$body2=[
				$this->formatter->getFormatMoneyUser($pengurang_lain),
				$this->formatter->getFormatMoneyUser($penambah_lain),
				$this->formatter->getFormatMoneyUser($d->gaji_pokok),
				$d->presensi.' Hari',
				$d->jam_biasa,$this->formatter->getFormatMoneyUser($d->nominal_biasa),
				$d->jam_libur_pendek,
				$this->formatter->getFormatMoneyUser($d->nominal_libur_pendek),
				$d->jam_libur,
				$this->formatter->getFormatMoneyUser($d->nominal_libur),
				$d->ekuivalen,
				$d->total_jam,
				$this->formatter->getFormatMoneyUser($d->gaji_lembur),
				$this->formatter->getFormatMoneyUser($d->presensi*$d->gaji_pokok),
				$this->formatter->getFormatMoneyUser($d->gaji_bersih),$d->no_rek
			];
			$total_gaji+=$d->gaji_bersih;
			$total_gaji_lembur+=$d->gaji_lembur;
			$total_gaji_harian+=($d->presensi*$d->gaji_pokok);
			$jam_hari_biasa+=$d->jam_biasa;
			$nominal_hari_biasa+=$d->nominal_biasa;
			$jam_hari_libur+=$d->jam_libur;
			$nominal_hari_libur+=$d->nominal_libur;
			$jam_hari_libur_pendek+=$d->jam_libur_pendek;
			$nominal_hari_libur_pendek+=$d->nominal_libur_pendek;
			$ekuivalen+=$d->ekuivalen;
			$total_jam_lembur+=$d->total_jam;
			$data_body = array_merge($body1,$data_lembur,$body2);
			$body[$row]=$data_body;
			$row++;
		}
		//$this->formatter->getFormatMoneyUser($d->upah)$d->jam_libur_pendek,$this->formatter->getFormatMoneyUser($d->nominal_libur_pendek)
		$tgl_lembur=[];
		$data_tgl_null=[];
		$nn=11;
		while ($tgl_mulai <= $tgl_selesai)
		{
			$tgl_lembur[$nn]=$this->formatter->getNameOfDay($tgl_mulai).', '.$this->formatter->getDateMonthFormatUser($tgl_mulai)."\n".'Presensi | Jam Lembur | Rupiah | Kode Proyek | Keterangan';
			$tgl_mulai = date('Y-m-d', strtotime($tgl_mulai . ' +1 day'));
			$data_tgl_null[$nn]=null;
			$nn++;
		}
		$head1=['No', 'NIK', 'Nama', 'Jabatan', 'Bagian', 'Lokasi Kerja', 'Tanggal Masuk', 'Masa kerja', 'Nama Periode'];
		$head2=[ 'Pengurang Lain','Penambah Lain','Besaran Gaji', 'Presensi', 'Lembur Hari Biasa', 'Nominal Hari Biasa', 'Lembur Jam Istirahat', 'Nominal Istirahat','Lembur Hari Libur', 'Nominal Hari Libur', 'Ekuivalen', 'Total Jam Lembur', 'Gaji Lembur', 'Gaji Harian', 'Gaji Diterima', 'No. Rek'];
		$data_head = array_merge($head1,$tgl_lembur,$head2);
		$data_awal_null=[null,null,null,null,null,null,null,null,null];
		$data_total=[$this->formatter->getFormatMoneyUser($total_pengurang_lain),$this->formatter->getFormatMoneyUser($total_penambah_lain),null,null,$jam_hari_biasa,$this->formatter->getFormatMoneyUser($nominal_hari_biasa),$jam_hari_libur_pendek,$this->formatter->getFormatMoneyUser($nominal_hari_libur_pendek),$jam_hari_libur,$this->formatter->getFormatMoneyUser($nominal_hari_libur),$ekuivalen,$total_jam_lembur,$this->formatter->getFormatMoneyUser($total_gaji_lembur),$this->formatter->getFormatMoneyUser($total_gaji_harian),$this->formatter->getFormatMoneyUser($total_gaji),null];
		$body[count($getdata)+3]=array_merge($data_awal_null,$data_tgl_null,$data_total);
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_penggajian_harian_bagian()
	{	
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		if(!empty($data_filter['bagian'])){ $form_filter['a.kode_bagian'] = $data_filter['bagian']; }
		if(!empty($data_filter['lokasi'])){ $form_filter['a.kode_loker'] = $data_filter['lokasi']; }
		if(!empty($data_filter['minggu'])){ $form_filter['a.minggu'] = $data_filter['minggu']; }
		if(!empty($data_filter['bulan'])){ $form_filter['a.bulan'] = $data_filter['bulan']; }
		if(!empty($data_filter['tahun'])){ $form_filter['a.tahun'] = $data_filter['tahun']; }
		if($data_filter['usage'] == 'data'){
			$data_gaji = $this->model_payroll->getDataPayrollHarianNew($form_filter, null, null, 'a.tgl_masuk asc');
		}else{
			$data_gaji = $this->model_payroll->getDataLogPayrollHarian($form_filter, null, null, 'a.tgl_masuk asc');
		}
		$form_filter['a.create_by'] = $this->admin;
		$form_filter['a.kode_master_penggajian'] = 'HARIAN';
		if(!empty($data_filter['periode'])){ $form_filter['a.kode_periode'] = $data_filter['periode']; }
		if($data_filter['usage'] == 'log'){
			$title_usage = 'Log Rekapitulasi Gaji Harian';
		}else{
			$title_usage = 'Rekapitulasi Gaji Harian';
		}
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$total_gaji=0;
		$total_gaji_lembur=0;
		$total_gaji_harian=0;
		$ekuivalen=0;
		$total_jam_lembur=0;
		$total_tk=0;
		$total_kes=0;
		$total_gapok=0;
		$total_tambah=0;
		$total_kurang=0;
		$periode='UNKNOWN';
		$nama_bagian='UNKNOWN';
		$nama_loker='UNKNOWN';
		if(!empty($data_gaji) || isset($data_gaji)){
			foreach ($data_gaji as $d) {
				$periode='('.$this->formatter->getDateFormatUser($d->tgl_mulai).' - '.$this->formatter->getDateFormatUser($d->tgl_selesai).')';
				$nama_bagian=$d->nama_bagian;
				$nama_loker=$d->nama_loker;
				$other = $this->payroll->getDataLainRekapitulasi($d->data_lain, $d->keterangan_lain, $d->nominal_lain);
				$total_tk+=($d->jht+$d->jkk+$d->jkm);
				$total_gapok+=($d->gaji_pokok);
				$total_kes+=($d->jkes);
				$total_gaji+=$d->gaji_bersih;
				$total_gaji_lembur+=$d->gaji_lembur;
				$total_gaji_harian+=($d->presensi*$d->gaji_pokok);
				$ekuivalen+=$d->ekuivalen;
				$total_jam_lembur+=$d->total_jam;
				$total_tambah+=$other['penambah'];
				$total_kurang+=$other['pengurang'];
			}
			$data_body = $body1=[
				($row-1),
				$nama_bagian,
				$nama_loker,
				$this->formatter->getFormatMoneyUser($total_gaji_harian),
				$this->formatter->getFormatMoneyUser($total_gaji_lembur),
				$this->formatter->getFormatMoneyUser($total_tk),
				$this->formatter->getFormatMoneyUser($total_kes),
				$this->formatter->getFormatMoneyUser($total_tambah),
				$this->formatter->getFormatMoneyUser($total_kurang),
				$this->formatter->getFormatMoneyUser($total_gaji),
				$periode
			];
			$body[$row]=$data_body;
			$data_head =['No', 'Bagian', 'Lokasi', 'Gaji Harian', 'Gaji Hari Libur', 'BPJS TK', 'BPJS KES', 'Penambah Lain', 'Pengurang Lain', 'Gaji Di terima', 'Periode'];
			$data_awal_null=[
				null,'TOTAL',null,
				$this->formatter->getFormatMoneyUser($total_gaji_harian),
				$this->formatter->getFormatMoneyUser($total_gaji_lembur),
				$this->formatter->getFormatMoneyUser($total_tk),
				$this->formatter->getFormatMoneyUser($total_kes),
				$this->formatter->getFormatMoneyUser($total_tambah),
				$this->formatter->getFormatMoneyUser($total_kurang),
				$this->formatter->getFormatMoneyUser($total_gaji)
			];
			$body[count($data_gaji)+1]=$data_awal_null;
			$sheet[0]=[
				'range_huruf'=>3,
				'sheet_title'=>$title_usage,
				'head'=>[
					'row_head'=>1,
					'data_head'=>$data_head,
				],
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
			];
			$data['data']=$sheet;
			$this->rekapgenerator->genExcel($data);
		}
	}
	public function export_template_grade_pd_old()
	{	
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$title_usage = 'Grade Perjalanan Dinas';
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$getdata = $this->model_master->getDataGradePerDin(null,['kolom'=>'d.nama','value'=>'ASD','kolom2'=>'a.nama']);
		foreach ($getdata as $d) {
			$body1=[($row-1),
				$d->kode_grade,
				$d->nama,
				$d->nama_loker,
				$d->kode_kategori,
				$d->nama_kategori,
				// $kode_kategori,
				// $this->model_master->getKategoriDinasKode($kode_kategori)['nama'],
				null,
				null,
				null,
				// $d->tempat,
				// $d->nominal,
				// $d->keterangan,
			];
			$data_body = $body1;
			$body[$row]=$data_body;
			$row++;
		}
		$head1=['No', 'Kode Grade', 'Nama Grade', 'Lokasi Grade', 'Kode Kategori', 'Nama Kategori Tunjangan', 'Tempat (untuk fasilitas penginapan)'."\n".'(mess / hotel)', 'Nominal', 'Keterangan',];
		$data_head = $head1;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_template_grade_pd()
	{	
		$data_filter = [];
		parse_str($this->input->post('data_filter'), $data_filter);
		$title_usage = 'Grade Perjalanan Dinas';
		$data['properties']=[
			'title'=>$title_usage,
			'subject'=>$title_usage,
			'description'=>$title_usage." HSOFT JKB",
			'keywords'=>"Export, Rekap, ".$title_usage,
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$getdata = $this->model_master->getListGrade(['kolom'=>'c.nama','value'=>'ASD','kolom2'=>'a.nama']);
		foreach ($getdata as $d) {
			$body1=[
				($row-1),
				$d->kode_grade,
				$d->nama,
				$d->nama_loker,
				null,
				null,
				null,
				null,
				null,
			];
			$data_body = $body1;
			$body[$row]=$data_body;
			$row++;
		}
		$head1=['No', 'Kode Grade', 'Nama Grade', 'Lokasi Grade', 'Tempat (untuk fasilitas penginapan)'."\n".'(mess / hotel)', 'Nominal', 'Nominal Lain'."\n".'(Optional)', 'Nominal Non Plant'."\n".'(Optional)', 'Keterangan',];
		$data_head = $head1;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$title_usage,
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
 	function exportTemplateGajiNonMatrix(){
		$data['properties']=[
			'title'=>"Template Import Gaji Non Matrix",
			'subject'=>"Template Import Gaji Non Matrix",
			'description'=>"Template Import Gaji Non Matrix HSOFT JKB",
			'keywords'=>"Template, Export, Template Karyawan",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$where = ['param'=>'all','bagian'=>null,'unit'=>null,'tahun'=>null,'bulan'=>null];
		$getData=$this->model_karyawan->getListKaryawan('search',$where);
		foreach ($getData as $d) {
			$body_1 = [
				($row-1),
				$d->nik,
				$d->nama,
				$d->nama_jabatan,
				$d->nama_loker,
				$d->gaji,
				// $this->formatter->getFormatMoneyUser($d->gaji),
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Import Gaji Non Matrix',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NIK','Nama','Jabatan','Lokasi Kerja','Gaji Pokok',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function importGajiNonMatrix()
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
				'table'=>'karyawan',
				'column_code'=>'nik',
				'usage'=>'importGajiNonMatrix',
				'column_properties'=>$this->model_global->getUpdateProperties($this->admin),
				'column'=>[
					1=>'nik',5=>'gaji',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
 	function exportTemplateGajibpjs(){
		$data['properties']=[
			'title'=>"Template Import Gaji BPJS",
			'subject'=>"Template Import Gaji BPJS",
			'description'=>"Template Import Gaji BPJS HSOFT JKB",
			'keywords'=>"Template, Export, Template Karyawan",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$where = ['param'=>'all','bagian'=>null,'unit'=>null,'tahun'=>null,'bulan'=>null];
		$getData=$this->model_karyawan->getListKaryawan('search',$where);
		foreach ($getData as $d) {
			$body_1 = [
				($row-1),
				$d->nik,
				$d->nama,
				$d->nama_jabatan,
				$d->nama_loker,
				$d->gaji_bpjs,
				$d->gaji_bpjs_tk,
				// $this->formatter->getFormatMoneyUser($d->gaji_bpjs),
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Import Gaji BPJS',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NIK','Nama','Jabatan','Lokasi Kerja','Gaji BPJS KES','Gaji BPJS TK',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function importGajiBpjs()
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
				'table'=>'karyawan',
				'column_code'=>'nik',
				'usage'=>'importGajiBpjs',
				'column_properties'=>$this->model_global->getUpdateProperties($this->admin),
				'column'=>[
					1=>'nik',5=>'gaji_bpjs',6=>'gaji_bpjs_tk',
				],
			];
			$data['data']=$sheet;
			$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
 	function exportTemplateWFH(){
		$usage=$this->uri->segment(3);
		$data['properties']=[
			'title'=>"Template Import WFH",
			'subject'=>"Template Import WFH",
			'description'=>"Template Import WFH HSOFT JKB",
			'keywords'=>"Template, Export, Template Karyawan",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$where = ['param'=>'all','bagian'=>null,'unit'=>null,'tahun'=>null,'bulan'=>null];
		$getData=$this->model_karyawan->getListKaryawan('search',$where);
		if($usage == 'data'){
			foreach ($getData as $d) {
				$body_1 = [
					($row-1),
					$d->nik,
					$d->nama,
					$d->nama_jabatan,
					$d->nama_loker,
					($d->hari_kerja_wfh != null) ? $d->hari_kerja_wfh.' Hari' : null,
					($d->hari_kerja_non_wfh != null) ? $d->hari_kerja_non_wfh.' Hari' : null,
					($d->wfh != null) ? $this->otherfunctions->getWFH($d->wfh) : null,
				];
				$data_body = $body_1;
				$body[$row]=$data_body;
				$row++;
			}
			$sheet_title = 'Data Hari Kerja WFH';
			$jenis_kerja_wf = null;
		}else{
			foreach ($getData as $d) {
				$body_1 = [
					($row-1),
					$d->nik,
					$d->nama,
					$d->nama_jabatan,
					$d->nama_loker,
					null,
					null,
					null,
				];
				$data_body = $body_1;
				$body[$row]=$data_body;
				$row++;
			}
			$sheet_title = 'Template Import WFH';
			$jenis_kerja_wf = ' (wfh / non_wfh)';
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>$sheet_title,
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NIK','Nama','Jabatan','Lokasi Kerja','Hari Kerja WFH','Hari Kerja Non WFH','Jenis Kerja'.$jenis_kerja_wf,
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function importWFH()
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
			'table'=>'karyawan',
			'column_code'=>'nik',
			'usage'=>'wfh',
			'column_properties'=>$this->model_global->getUpdateProperties($this->admin),
			'column'=>[
				1=>'nik',5=>'hari_kerja_wfh',6=>'hari_kerja_non_wfh',7=>'wfh',
			],
		];
		$data['data']=$sheet;
		$datax=$this->rekapgenerator->importFileExcel($data);
		echo json_encode($datax);
	}
	public function export_data_presensi_pa()
	{
		$data['properties']=[
			'title'=>"Rekap Data Presensi",
			'subject'=>"Rekap Data Presensi",
			'description'=>"Rekap Data Presensi,Rekap Data Presensi Karyawan",
			'keywords'=>"Rekap Data Presensi,Presensi",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$datax=$this->model_presensi->getListPresensi(true);
		foreach ($datax as $k=>$d) {
			$body[$row_body]=[
				($k+1).'.',(((!empty($d->nama_periode)) ? $d->nama_periode : 'Unknown').' - '.$d->tahun),$d->id_finger,$d->nik,$d->nama,$d->nama_jabatan,$d->nama_bagian,$d->nama_loker,$d->ijin,$d->telat,$d->mangkir,$d->sp
			];
			$row_body++;
		}

		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Presensi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No.','PERIODE - TAHUN','ID FINGER','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','LOKASI KERJA','IJIN','TERLAMBAT','BOLOS','SP'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];

		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function exportAspekSikap()
	{
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$id_log = $this->admin;
		$getJabatan = $this->model_karyawan->getEmployeeId($id_log);
		$kode_jabatan = $getJabatan['jabatan'];
		$posisi  = ["ATS","BWH","RKN","DRI"];
		$max_for = 4;
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
		echo '<pre>';
		$data_pick=[];
		if (isset($pack['partisipan'])) {
			foreach ($pack['partisipan'] as $k_par => $v_par) {						
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
				print_r($emp);
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
	public function data_transaksi_non_karyawan(){
		$mode = $this->input->get('mode');
		if($mode == 'data'){
			$bulan = $this->input->get('bulan_export');
			$tahun = $this->input->get('tahun_export');
			if(empty($bulan) && empty($tahun)){
				$getdata=$this->model_karyawan->getListTransaksiNonKaryawan(null,false);
			}else{
				if(!empty($bulan) && empty($tahun)){
					$where = ['MONTH(a.tanggal)'=>$bulan];
				}elseif(empty($bulan) && !empty($tahun)){
					$where = ['YEAR(a.tanggal)'=>$tahun];
				}elseif(!empty($bulan) && !empty($tahun)){
					$where = ['MONTH(a.tanggal)'=>$bulan,'YEAR(a.tanggal)'=>$tahun];
				}else{
					$where = ['MONTH(a.tanggal)'=>$bulan,'YEAR(a.tanggal)'=>$tahun];
				}
				$getdata=$this->model_karyawan->getListTransaksiNonKaryawan($where,false);
			}
	 		$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Transaksi Non Karyawan",
				'subject'=>"Rekap Transaksi Non Karyawan",
				'description'=>"Rekap Transaksi Non Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Mutasi, Mutasi Karyawan",
				'category'=>"Export",
			];
		}else{
			$nik = $this->input->post('nik');
			$getdata = $this->model_karyawan->getListTransaksiNonKaryawan(['non.nik'=>$nik]);
			$nonkar = $this->model_karyawan->getListTransaksiNonKaryawan(['non.nik'=>$nik],true);
			$user = $this->dtroot;
			$data['properties']=[
				'title'=>"Rekap Data Transaksi Non Karyawan - ".$nonkar['nama_non']." - ".$nonkar['nik'],
				'subject'=>"Rekap Data Transaksi Non Karyawan",
				'description'=>"Rekap Data Transaksi Non Karyawan HSOFT JKB - Dicetak Oleh : ".$user['adm']['nama'],
				'keywords'=>"Rekap, Export, Mutasi, Transaksi Non Karyawan",
				'category'=>"Export",
			];
		}
		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$jbt_mengetahui=(!empty($d->jbt_mengetahui)) ? ' - '.$d->jbt_mengetahui : null;
			$jbt_menyetujui=(!empty($d->jbt_menyetujui)) ? ' - '.$d->jbt_menyetujui : null;
			$body[$row]=[
				($row-1),
				$d->nik,
				$d->nama_non,
				$d->nomor,
				$this->formatter->getDateMonthFormatUser($d->tanggal),
				$d->kegiatan,
				$this->formatter->getFormatMoneyUser($d->biaya),
				$d->nama_mengetahui.$jbt_mengetahui,
				$d->nama_menyetujui.$jbt_menyetujui,
				$d->keterangan,
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>"Data_Mutasi",
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NIK','Nama','Nomor','Tanggal','Kegiatan','Biaya','Mengetahui','Menyetujui','Keterangan',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	//================================================ DATA PPH 21 NON KARYAWAN =================================================//
	
	public function rekap_data_pph_21_non()
	{
		$bulanx = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksix = $this->input->post('koreksi');
		$bulan = (empty($bulanx))?date('m'):$bulanx;
		$koreksi = (empty($koreksix))?'0':$koreksix;
		$data_pph = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPphNon(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.koreksi'=>$koreksi]));
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Non Karyawan Bulan '.$data_pph['bulan'].' ( '.$data_pph['tahun'].' ) Pembetulan '.$this->otherfunctions->getNumberToAbjad($koreksi),
			'subject'=>'Rekap Data PPH-21 Non Karyawan',
			'description'=>"Rekap Data PPH-21 Non Karyawan HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21 Non Karyawan",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=6;
		$row=$row_body;
		$total_biaya = 0;
		$total_premi = 0;
		$total_tunjangan_pph = 0;
		$total_thr = 0;
		$total_jumlah = 0;
		$total_bruto_sebulan = 0;
		$total_netto_sebulan = 0;
		$total_pkp = 0;
		$total_pph_setahun = 0;
		$total_netto_sd_bulanIni = 0;
		$total_pph_sd_bulanIni = 0;
		$total_pph_sd_bulanLalu = 0;
		$total_pph_dipotong = 0;
		$total_upah_bulan_ini = 0;
		$datax = $this->model_payroll->getListDataPenggajianPphNon(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.koreksi'=>$koreksi]);
		foreach ($datax as $d) {
			$netto_sd_bulanIni = 0;
			$pph_sd_bulanIni = 0;
			$pph_sd_bulanLalu = 0;
			$bulan =($bulan<10)?str_replace('0','',$bulan):$bulan;
			for ($i=1; $i <= $bulan; $i++) { 
				$bulanx =($i<10)?'0'.$i:$i;
				$dataPPh = $this->model_payroll->getListDataPenggajianPphNon(['a.nik'=>$d->nik,'a.bulan'=>$bulanx,'a.tahun'=>$tahun,],null,null,null,'max',true);
				$netto_sd_bulanIni += $dataPPh['netto_sebulan'];
				$pph_sd_bulanIni += $dataPPh['pph_setahun'];
			}
			for ($ix=1; $ix < $bulan; $ix++) { 
				$bulany =($ix<10)?'0'.$ix:$ix;
				$dataPPhx = $this->model_payroll->getListDataPenggajianPphNon(['a.nik'=>$d->nik,'a.bulan'=>$bulany,'a.tahun'=>$tahun,],null,null,null,'max',true);
				$pph_sd_bulanLalu += $dataPPhx['pph_setahun'];
			}
			$total_biaya+=$d->biaya;
			$total_premi+=$d->premi;
			$total_tunjangan_pph+=$d->tunjangan_pph;
			$total_thr+=$d->thr;
			$total_jumlah+=($d->biaya+$d->premi);//+$d->thr+$d->tunjangan_pph);
			$total_bruto_sebulan+=$d->bruto_sebulan;
			$total_netto_sebulan+=$d->netto_sebulan;
			$total_pkp+=$d->pkp;
			$total_pph_setahun+=$d->pph_setahun;
			$total_netto_sd_bulanIni+=$netto_sd_bulanIni;
			$total_pph_sd_bulanIni+=$pph_sd_bulanIni;
			$total_pph_sd_bulanLalu+=$pph_sd_bulanLalu;
			$total_pph_dipotong+=$d->pph_dipotong;
			$total_upah_bulan_ini+=$d->upah_bulan_ini;
			$body_1 = [
				($row-5),
				$d->nik,
				$d->nama,
				$d->alamat,
				$d->no_telp,
				$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
				$this->otherfunctions->getNumberToAbjad($koreksi),
				$d->jenis,
				$d->jenis_pajak,
				$d->status_pajak,
				$d->npwp,
				$this->otherfunctions->getJenisPerhitunganPajakKey($d->perhitungan_pajak),
				$this->otherfunctions->pembulatanFloor($d->biaya),
				$this->otherfunctions->pembulatanFloor($d->tunjangan_pph),
				// $this->otherfunctions->pembulatanFloor($d->thr),
				$this->otherfunctions->pembulatanFloor(($d->biaya+$d->tunjangan_pph)),// + $d->thr+$d->tunjangan_pph)),
				$this->otherfunctions->pembulatanFloor(($d->biaya+$d->tunjangan_pph)/2),
				$this->otherfunctions->pembulatanFloor($d->pph_setahun),
				// $this->otherfunctions->pembulatanFloor($d->netto_sebulan),
				// $this->otherfunctions->pembulatanFloor($d->bruto_sebulan),
				$this->otherfunctions->pembulatanFloor($netto_sd_bulanIni),
				$this->otherfunctions->pembulatanFloor($pph_sd_bulanIni),
				$this->otherfunctions->pembulatanFloor($pph_sd_bulanLalu),
				$this->otherfunctions->pembulatanFloor($d->pkp),
				$this->otherfunctions->pembulatanFloor($d->pph_setahun),
				$this->otherfunctions->pembulatanFloor($d->pph_dipotong),
				$this->otherfunctions->pembulatanFloor($d->upah_bulan_ini),
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}
		$data_total = [
			null, null, 'TOTAL',null,null,null,null,null,null,null,null,null,
			$this->otherfunctions->pembulatanFloor($total_biaya),
			$this->otherfunctions->pembulatanFloor($total_tunjangan_pph),
			// $this->otherfunctions->pembulatanFloor($total_thr),
			$this->otherfunctions->pembulatanFloor($total_jumlah),
			$this->otherfunctions->pembulatanFloor($total_jumlah/2),
			$this->otherfunctions->pembulatanFloor($total_pph_setahun),
			// $this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
			// $this->otherfunctions->pembulatanFloor($total_netto_sebulan),
			$this->otherfunctions->pembulatanFloor($total_netto_sd_bulanIni),
			$this->otherfunctions->pembulatanFloor($total_pph_sd_bulanIni),
			$this->otherfunctions->pembulatanFloor($total_pph_sd_bulanLalu),
			$this->otherfunctions->pembulatanFloor($total_pkp),
			$this->otherfunctions->pembulatanFloor($total_pph_setahun),
			$this->otherfunctions->pembulatanFloor($total_pph_dipotong),
			$this->otherfunctions->pembulatanFloor($total_upah_bulan_ini),
		];
		$body[count($datax)+7]=$data_total;
		$jumData = count($datax)+7;	
		$body[1]=['LAPORAN UPAH NON KARYAWAN DAN PERHITUNGAN PPH21'];
		$body[2]=['Bulan '.strtoupper($this->formatter->getNameOfMonth($bulan)).' '.$tahun];
		$body[3]=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X'];
		$body[5]=[null,null,null,null,null,null,null,null,null,null,null,null,null,null,'(M+N)','50% * O',null,null,null,null,null,null,null,('M-W')];
		$data_head = [
			'No',
			'NIK',
			'Nama',
			'Alamat',
			'No Telp',
			'Bulan - Tahun',
			'Pembetulan',
			'Kode Pajak',
			'Jenis Pajak',
			'Status Pajak',
			'NPWP',
			'Perhitungan Pajak',
			'Upah',
			'Tunjangan PPH',
			// 'THR',
			'Bruto Sebulan',
			'Netto Sebulan',
			'PPh',
			// 'Bruto Sebulan',
			'Akumulasi Netto s.d Bulan berjalan',
			'PPh s.d bulan berjalan',
			'Akumulasi PPh s.d Bulan Lalu',
			'PKP Sebulan',
			'PPh Bulan Ini',
			'PPh yang dipotong',
			'Upah yang dibayarkan bulan ini',
		];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data PPH-21 Non Karyawan',
			// 'head'=>[
			// 	'row_head'=>2,
			// 	'data_head'=>$data_head,
			// ],
			'head_merge'=>[
				'abjadTop'=>true,
				'jumData'=>[ 
					'1'=>'M4:X'.$jumData
				],
				'row_head'=>4,
				'data_head'=>$data_head,
				'max_merge'=>2,
				'merge_1'=>'A1:D1',
				'merge_2'=>'A2:D2',
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_non_all()
	{
		$sheet = [];
		$data_head = [
			'No',
			'NIK',
			'Nama',
			'Alamat',
			'No Telp',
			'Bulan - Tahun',
			'Pembetulan',
			'Kode Pajak',
			'Jenis Pajak',
			'Status Pajak',
			'NPWP',
			'Perhitungan Pajak',
			'Upah',
			'Tunjangan PPH',
			// 'THR',
			'Bruto Sebulan',
			'PPh',
			'Netto Sebulan',
			// 'Bruto Sebulan',
			'Akumulasi Netto s.d Bulan berjalan',
			'PPh s.d bulan berjalan',
			'Akumulasi PPh s.d Bulan Lalu',
			'PKP Sebulan',
			'PPh Bulan Ini',
			'PPh yang dipotong',
			'Upah yang dibayarkan bulan ini',
		];
		for ($bulanx=1; $bulanx < 13; $bulanx++) { 
			$bulan = ($bulanx<10)?'0'.$bulanx:$bulanx;
			$tahun = $this->input->post('tahun');
			$koreksix = $this->input->post('koreksi');
			$koreksi = (empty($koreksix))?'0':$koreksix;
			$data_pph = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPphNon(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.koreksi'=>$koreksi]));
			$data['properties']=[
				'title'=>'Rekap Data PPH-21 Non Karyawan Bulanan Tahun '.$tahun.' Pembetulan '.$this->otherfunctions->getNumberToAbjad($koreksi),
				'subject'=>'Rekap Data PPH-21 Non Karyawan',
				'description'=>"Rekap Data PPH-21 Non Karyawan HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
				'keywords'=>"Export, Rekap, Rekap Data PPH-21 Non Karyawan",
				'category'=>"Rekap",
			];
			$body=[];
			$row_body=6;
			$row=$row_body;
			$total_biaya = 0;
			$total_premi = 0;
			$total_tunjangan_pph = 0;
			$total_thr = 0;
			$total_jumlah = 0;
			$total_bruto_sebulan = 0;
			$total_netto_sebulan = 0;
			$total_pkp = 0;
			$total_pph_sebulan = 0;
			$total_netto_sd_bulanIni = 0;
			$total_pph_sd_bulanIni = 0;
			$total_pph_sd_bulanLalu = 0;
			$total_pph_dipotong = 0;
			$total_upah_bulan_ini = 0;
			$datax = $this->model_payroll->getListDataPenggajianPphNon(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.koreksi'=>$koreksi]);
			foreach ($datax as $d) {
				$netto_sd_bulanIni = 0;
				$pph_sd_bulanIni = 0;
				$pph_sd_bulanLalu = 0;
				$bulan =($bulan<10)?str_replace('0','',$bulan):$bulan;
				for ($i=1; $i <= $bulan; $i++) { 
					$bulanx =($i<10)?'0'.$i:$i;
					$dataPPh = $this->model_payroll->getListDataPenggajianPphNon(['a.nik'=>$d->nik,'a.bulan'=>$bulanx,'a.tahun'=>$tahun,],null,null,null,'max',true);
					$netto_sd_bulanIni += $dataPPh['netto_sebulan'];
					$pph_sd_bulanIni += $dataPPh['pph_sebulan'];
				}
				for ($ix=1; $ix < $bulan; $ix++) { 
					$bulany =($ix<10)?'0'.$ix:$ix;
					$dataPPhx = $this->model_payroll->getListDataPenggajianPphNon(['a.nik'=>$d->nik,'a.bulan'=>$bulany,'a.tahun'=>$tahun,],null,null,null,'max',true);
					$pph_sd_bulanLalu += $dataPPhx['pph_sebulan'];
				}
				$total_netto_sd_bulanIni+=$netto_sd_bulanIni;
				$total_pph_sd_bulanIni+=$pph_sd_bulanIni;
				$total_pph_sd_bulanLalu+=$pph_sd_bulanLalu;
				$total_biaya+=$d->biaya;
				$total_premi+=$d->premi;
				$total_tunjangan_pph+=$d->tunjangan_pph;
				$total_thr+=$d->thr;
				$total_jumlah+=($d->biaya+$d->thr+$d->premi+$d->tunjangan_pph);
				$total_bruto_sebulan+=$d->bruto_sebulan;
				$total_netto_sebulan+=$d->netto_sebulan;
				$total_pkp+=$d->pkp;
				$total_pph_sebulan+=$d->pph_sebulan;
				$total_pph_dipotong+=$d->pph_dipotong;
				$total_upah_bulan_ini+=$d->upah_bulan_ini;
				$body_1 = [
					($row-5),
					$d->nik,
					$d->nama,
					$d->alamat,
					$d->no_telp,
					$this->formatter->getNameOfMonth($d->bulan).' '.$d->tahun,
					$this->otherfunctions->getNumberToAbjad($koreksi),
					$d->jenis,
					$d->jenis_pajak,
					$d->status_pajak,
					$d->npwp,
					$this->otherfunctions->getJenisPerhitunganPajakKey($d->perhitungan_pajak),
					$this->otherfunctions->pembulatanFloor($d->biaya),
					$this->otherfunctions->pembulatanFloor($d->tunjangan_pph),
					// $this->otherfunctions->pembulatanFloor($d->thr),
					$this->otherfunctions->pembulatanFloor(($d->biaya+$d->tunjangan_pph)),// + $d->thr+$d->tunjangan_pph)),
					$this->otherfunctions->pembulatanFloor(($d->biaya+$d->tunjangan_pph)/2),
					$this->otherfunctions->pembulatanFloor($d->pph_sebulan),
					// $this->otherfunctions->pembulatanFloor($d->netto_sebulan),
					// $this->otherfunctions->pembulatanFloor($d->bruto_sebulan),
					$this->otherfunctions->pembulatanFloor($netto_sd_bulanIni),
					$this->otherfunctions->pembulatanFloor($pph_sd_bulanIni),
					$this->otherfunctions->pembulatanFloor($pph_sd_bulanLalu),
					$this->otherfunctions->pembulatanFloor($d->pkp),
					$this->otherfunctions->pembulatanFloor($d->pph_sebulan),
					$this->otherfunctions->pembulatanFloor($d->pph_dipotong),
					$this->otherfunctions->pembulatanFloor($d->upah_bulan_ini),
				];
				$data_body = $body_1;
				$body[$row]=$data_body;
				$row++;
			}
			$data_total = [
				null, null, 'TOTAL',null,null,null,null,null,null,null,null,null,
				$this->otherfunctions->pembulatanFloor($total_biaya),
				$this->otherfunctions->pembulatanFloor($total_tunjangan_pph),
				// $this->otherfunctions->pembulatanFloor($total_thr),
				$this->otherfunctions->pembulatanFloor($total_jumlah),
				$this->otherfunctions->pembulatanFloor($total_jumlah/2),
				$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
				// $this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
				// $this->otherfunctions->pembulatanFloor($total_netto_sebulan),
				$this->otherfunctions->pembulatanFloor($total_netto_sd_bulanIni),
				$this->otherfunctions->pembulatanFloor($total_pph_sd_bulanIni),
				$this->otherfunctions->pembulatanFloor($total_pph_sd_bulanLalu),
				$this->otherfunctions->pembulatanFloor($total_pkp),
				$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
				$this->otherfunctions->pembulatanFloor($total_pph_dipotong),
				$this->otherfunctions->pembulatanFloor($total_upah_bulan_ini),
			];
			// $body[count($datax)+3]=$data_total;
			$body[count($datax)+7]=$data_total;
			$jumData = count($datax)+7;	
			$body[1]=['LAPORAN UPAH NON KARYAWAN DAN PERHITUNGAN PPH21'];
			$body[2]=['Bulan '.strtoupper($this->formatter->getNameOfMonth($bulan)).' '.$tahun];
			$body[3]=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X'];
			$body[5]=[null,null,null,null,null,null,null,null,null,null,null,null,null,null,'(M+N)','50% * O',null,null,null,null,null,null,null,('M-W')];
			$sheet[($bulanx-1)]=[
				'range_huruf'=>3,
				'sheet_title'=>strtoupper($this->formatter->getNameOfMonth($bulan)).' '.$tahun,
				// 'head'=>[
				// 	'row_head'=>1,
				// 	'data_head'=>$data_head,
				// ],
				'head_merge'=>[
					'abjadTop'=>true,
					'jumData'=>[ 
						'1'=>'M4:X'.$jumData
					],
					'row_head'=>4,
					'data_head'=>$data_head,
					'max_merge'=>2,
					'merge_1'=>'A1:D1',
					'merge_2'=>'A2:D2',
				],
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
			];
		}
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_non_tahunan()
	{
		$tahun = $this->input->post('tahun');
		$nonKar=$this->model_karyawan->getListNonKaryawan();
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Non Karyawan Tahun '.$tahun,
			'subject'=>'Rekap Data PPH-21 Non Karyawan',
			'description'=>"Rekap Data PPH-21 Non Karyawan HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21 Non Karyawan",
			'category'=>"Rekap",
		];
		$body=[];
		$bodyx=[];
		$row_body=6;
		$row=$row_body;
		$total_biaya=0;
		$total_premi=0;
		$total_tunjangan_pph=0;
		$total_thr=0;
		$total_jumlah=0;
		$total_bruto_sebulan=0;
		$total_netto_sebulan=0;
		$total_pkp=0;
		$total_pph_sebulan=0;
		$total_pph_dipotong = 0;
		$total_upah_bulan_ini = 0;
		foreach ($nonKar as $d) {
			$dataPPh = $this->model_payroll->getListDataPenggajianPphNon(['a.nik'=>$d->nik,'a.tahun'=>$tahun,],null,null,null,'max');
			// echo '<pre>';
			// print_r($dataPPh);
			if(!empty($dataPPh)){
				$biaya=0;
				$premi=0;
				$tunjangan_pph=0;
				$thr=0;
				$jumlah=0;
				$bruto_sebulan=0;
				$netto_sebulan=0;
				$pkp=0;
				$pph_sebulan=0;
				$pph_dipotong=0;
				$upah_bulan_ini=0;
				foreach ($dataPPh as $p) {
					$biaya+=$p->biaya;
					$premi+=$p->premi;
					$tunjangan_pph+=$p->tunjangan_pph;
					$thr+=$p->thr;
					$jumlah+=($p->biaya+$p->thr+$p->premi+$p->tunjangan_pph);
					$bruto_sebulan+=$p->bruto_sebulan;
					$netto_sebulan+=$p->netto_sebulan;
					$pkp+=$p->pkp;
					$pph_sebulan+=$p->pph_sebulan;
					$pph_dipotong+=$p->pph_dipotong;
					$upah_bulan_ini+=$p->upah_bulan_ini;
				}
				$total_biaya+=$biaya;
				$total_premi+=$premi;
				$total_tunjangan_pph+=$tunjangan_pph;
				$total_thr+=$thr;
				$total_jumlah+=$jumlah;
				$total_bruto_sebulan+=$bruto_sebulan;
				$total_netto_sebulan+=$netto_sebulan;
				$total_pkp+=$pkp;
				$total_pph_sebulan+=$pph_sebulan;
				$total_pph_dipotong+=$pph_dipotong;
				$total_upah_bulan_ini+=$upah_bulan_ini;
				$body_1 = [
					($row-5),
					$d->nik,
					$d->nama,
					$d->alamat,
					$d->no_telp,
					$tahun,
					null, // $this->otherfunctions->getNumberToAbjad($koreksi),
					$d->jenis,
					$d->nama_jenis,
					$d->status_pajak,
					$d->npwp,
					// $this->otherfunctions->pembulatanFloor($biaya),
					// $this->otherfunctions->pembulatanFloor($premi),
					// $this->otherfunctions->pembulatanFloor($thr),
					// $this->otherfunctions->pembulatanFloor($jumlah),
					// $this->otherfunctions->pembulatanFloor($tunjangan_pph),
					// $this->otherfunctions->pembulatanFloor($bruto_sebulan),
					// $this->otherfunctions->pembulatanFloor($netto_sebulan),
					// $this->otherfunctions->pembulatanFloor($pkp),
					// $this->otherfunctions->pembulatanFloor($pph_sebulan),
					// $this->otherfunctions->pembulatanFloor($tunjangan_pph),
					$this->otherfunctions->pembulatanFloor($biaya),
					$this->otherfunctions->pembulatanFloor($tunjangan_pph),
					$this->otherfunctions->pembulatanFloor(($biaya+$tunjangan_pph)),
					$this->otherfunctions->pembulatanFloor($pph_sebulan),
					$this->otherfunctions->pembulatanFloor(($biaya+$tunjangan_pph)/2),
					// $this->otherfunctions->pembulatanFloor($bruto_sebulan),
					// $this->otherfunctions->pembulatanFloor($netto_sebulan),
					$this->otherfunctions->pembulatanFloor($pkp),
					$this->otherfunctions->pembulatanFloor($pph_sebulan),
					$this->otherfunctions->pembulatanFloor($pph_dipotong),
					$this->otherfunctions->pembulatanFloor($upah_bulan_ini),
				];
				$data_body = $body_1;
				$body[$row]=$data_body;
				$bodyx=$data_body;
				$row++;
			}
		}
		$data_total = [
			null, null, 'TOTAL',null,null,null,null,null,null,null,null,
			// $this->otherfunctions->pembulatanFloor($total_biaya),
			// $this->otherfunctions->pembulatanFloor($total_premi),
			// // $this->otherfunctions->pembulatanFloor($total_tunjangan_pph),
			// $this->otherfunctions->pembulatanFloor($total_thr),
			// $this->otherfunctions->pembulatanFloor($total_jumlah),
			// $this->otherfunctions->pembulatanFloor($total_tunjangan_pph),
			// $this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
			// $this->otherfunctions->pembulatanFloor($total_netto_sebulan),
			// $this->otherfunctions->pembulatanFloor($total_pkp),
			// $this->otherfunctions->pembulatanFloor($total_pph_sebulan),
			// $this->otherfunctions->pembulatanFloor($total_tunjangan_pph),
			$this->otherfunctions->pembulatanFloor($total_biaya),
			$this->otherfunctions->pembulatanFloor($total_tunjangan_pph),
			// $this->otherfunctions->pembulatanFloor($total_thr),
			$this->otherfunctions->pembulatanFloor($total_jumlah),
			$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
			$this->otherfunctions->pembulatanFloor($total_jumlah/2),
			// $this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
			// $this->otherfunctions->pembulatanFloor($total_netto_sebulan),
			// $this->otherfunctions->pembulatanFloor($total_netto_sd_bulanIni),
			// $this->otherfunctions->pembulatanFloor($total_pph_sd_bulanIni),
			// $this->otherfunctions->pembulatanFloor($total_pph_sd_bulanLalu),
			$this->otherfunctions->pembulatanFloor($total_pkp),
			$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
			$this->otherfunctions->pembulatanFloor($total_pph_dipotong),
			$this->otherfunctions->pembulatanFloor($total_upah_bulan_ini),
		];
		// $body[count($body)+7]=$data_total;
		$body[count($body)+7]=$data_total;
		$jumData = count($body)+7;	
		$body[1]=['LAPORAN UPAH NON KARYAWAN DAN PERHITUNGAN PPH21'];
		$body[2]=['TAHUN '.$tahun];
		$body[3]=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T'];
		$body[5]=[null,null,null,null,null,null,null,null,null,null,null,null,null,'(L+M)',null,'50% * O',null,null,null,('L-S')];
		$data_head = [
			'No',
			'NIK',
			'Nama',
			'Alamat',
			'No Telp',
			'Tahun',
			'Pembetulan',
			'Kode Pajak',
			'Jenis Pajak',
			'Status Pajak',
			'NPWP',
			'Upah',
			'Tunjangan PPh',
			// 'THR',
			'Bruto Setahun',
			'PPh',
			'Netto Setahun',
			// 'Jumlah',
			// 'PPh',
			// 'Bruto Setahun',
			// 'Netto Setahun',
			// 'PKP Setahun',
			// 'PPh Setahun',
			// 'Tunjangan PPh',
			'PKP Setahun',
			'PPh Setahun',
			'PPh yang dipotong',
			'Upah yang dibayarkan Setahun',
		];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data PPH-21 Non Karyawan',
			// 'head'=>[
			// 	'row_head'=>1,
			// 	'data_head'=>$data_head,
			// ],
			'head_merge'=>[
				'abjadTop'=>true,
				'jumData'=>[ 
					'1'=>'L4:T'.$jumData
				],
				'row_head'=>4,
				'data_head'=>$data_head,
				'max_merge'=>2,
				'merge_1'=>'A1:D1',
				'merge_2'=>'A2:D2',
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_bp_final()
	{
		$bulanx = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksix = $this->input->post('koreksi');
		$bulan = (empty($bulanx))?date('m'):$bulanx;
		$koreksi = (empty($koreksix))?'0':$koreksix;
		$data_pph = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPphNon(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.koreksi'=>$koreksi]));
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Bulan '.$data_pph['bulan'].' ( '.$data_pph['tahun'].' ) Pembetulan '.$this->otherfunctions->getNumberToAbjad($koreksi),
			'subject'=>'Rekap Data PPH-21',
			'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$datax = $this->model_payroll->getListDataPenggajianPphNon(['a.bulan'=>$bulan,'a.tahun'=>$tahun]);
		foreach ($datax as $d) {
			$body_1 = [
				($row-1),
				$d->bulan,
				$d->tahun,
				$this->otherfunctions->getNumberToAbjad($koreksi),
				null,
				$d->npwp,
				$d->nik,
				$d->nama,
				$d->alamat,
				// $d->no_telp,
				$d->jenis,
				// $d->jenis_pajak,
				// $d->status_pajak,
				// $this->formatter->getFormatMoneyUserReq($d->bruto_sebulan),
				ceil($d->bruto_setahun),
				// ceil($d->netto_sebulan),
				// ceil($d->netto_setahun),
				ceil($d->pajak_setahun),
				ceil($d->pph_setahun),
				// ceil($d->pph_sebulan),
				// ceil($d->pph_npwp),
				null,
				null,
				null,
			];
			$data_body = $body_1;
			$body[$row]=$data_body;
			$row++;
		}
		$data_head = [
			'No',
			'Masa Pajak',
			'Tahun Pajak',
			'Pembetulan',
			'Nomor Bukti Potong',
			'NPWP',
			'NIK',
			'Nama',
			'Alamat',
			'Kode Pajak',
			'Jumlah Bruto',
			'Tarif',
			'Jumlah PPh',
			'NPWP Pemotong',
			'Nama Pemotong',
			'Tanggal Bukti Potong',
		];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'PPH-21 Non Karyawan BP Final',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$data_head,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_template_pph_kode_akun()
	{
		$data['properties']=[
			'title'=>"Template Import Data Kode Akun PPh",
			'subject'=>"Template Import Data Kode Akun PPh",
			'description'=>"Template Import Data Kode Akun PPh HSOFT JKB",
			'keywords'=>"Template, Export, Template Kode Akun PPh",
			'category'=>"Template",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Data Kode Akun PPh',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'NIK','Nama', 'Tanggal (yyyy-mm-dd)','No. Bukti','No. Akun','Nama Akun','Catatan','Nilai Bayar','Nama Proyek','No. Proyek',
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	//===================================================== DATA PPH 21 ========================================================//
	
	public function rekap_data_pph_21()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$data_pph_other = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]));
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Periode '.$this->formatter->getNameOfMonth($bulan).' '.$tahun.' Pembetulan '.$this->otherfunctions->getNumberToAbjad($koreksi),
			'subject'=>'Rekap Data PPH-21',
			'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21",
			'category'=>"Rekap",
		];
		$body=[];
		$bodyx=[];
		$row_body=7;
		$row=$row_body;
		$datax = $this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]);
		if(!empty($datax)){
			$gaji_pokok = 0;
			$total_gaji = 0;
			$total_tunjangan = 0;
			$total_uang_makan = 0;
			$total_ritasi = 0;
			$total_lembur = 0;
			$total_perjalanan_dinas = 0;
			$total_kode_akun = 0;
			$total_bonus = 0;
			$total_thr = 0;
			$total_pesangon = 0;
			$total_pph_pesangon = 0;
			$total_premi_asuransi = 0;
			$total_pengurang_lain = 0;
			$total_penambah_lain = 0;
			$total_pengurang_hallo = 0;
			$total_penambah_hallo = 0;
			$total_bpjs_jkk_perusahaan = 0;
			$total_bpjs_jkm_perusahaan = 0;
			$total_bpjs_jht_perusahaan = 0;
			$total_bpjs_kes_perusahaan = 0;
			$total_iuran_pensiun_perusahaan = 0;
			$total_bpjs_jht_pekerja = 0;
			$total_bpjs_jkk_pekerja = 0;
			$total_bpjs_jkm_pekerja = 0;
			$total_bpjs_kes_pekerja = 0;
			$total_iuran_pensiun_pekerja = 0;
			$total_hutang = 0;
			$total_pot_tidak_masuk = 0;
			$total_nominal_denda = 0;
			$total_yg_diterima = 0;
			$total_bruto_setahun = 0;
			$total_biaya_jabatan = 0;
			$total_netto_setahun = 0;
			$total_ptkp = 0;
			$total_pkp = 0;
			$total_pph_setahun = 0;
			$total_pajak_setahun = 0;
			$total_bruto_sebulan = 0;
			$total_netto_sebulan = 0;
			$total_pph_sebulan = 0;
			$total_ptkp_sebulan = 0;
			$total_pkp_sebulan = 0;
			$total_jumlahxx = 0;
			$total_pph_tunjangan = 0;
			foreach ($datax as $d) {
				$gaji_pokok += $d->gaji_pokok;
				$total_gaji += $d->gaji_pokok;
				$total_tunjangan += $d->tunjangan;
				$total_uang_makan += $d->uang_makan;
				$total_ritasi += $d->ritasi;
				$total_lembur += $d->lembur;
				$total_perjalanan_dinas += $d->perjalanan_dinas;
				$total_kode_akun += $d->kode_akun;
				$total_bonus += $d->bonus;
				$total_thr += $d->thr;
				$total_pesangon += $d->pesangon;
				$total_pph_pesangon += $d->pph_pesangon;
				$total_bpjs_jkk_perusahaan += $d->bpjs_jkk_perusahaan;
				$total_bpjs_jkm_perusahaan += $d->bpjs_jkm_perusahaan;
				$total_bpjs_jht_perusahaan += $d->bpjs_jht_perusahaan;
				$total_bpjs_kes_perusahaan += $d->bpjs_kes_perusahaan;
				$total_iuran_pensiun_perusahaan += $d->iuran_pensiun_perusahaan;
				$total_bpjs_jht_pekerja += $d->bpjs_jht_pekerja;
				$total_bpjs_jkk_pekerja += $d->bpjs_jkk_pekerja;
				$total_bpjs_jkm_pekerja += $d->bpjs_jkm_pekerja;
				$total_bpjs_kes_pekerja += $d->bpjs_kes_pekerja;
				$total_iuran_pensiun_pekerja += $d->iuran_pensiun_pekerja;
				$total_hutang += $d->hutang;
				$total_pot_tidak_masuk += ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
				$total_nominal_denda += $d->nominal_denda;
				$total_yg_diterima += $d->yg_diterima;
				$total_bruto_setahun += $d->bruto_setahun;
				$total_biaya_jabatan += $d->biaya_jabatan;
				$total_netto_setahun += $d->netto_setahun;
				$total_ptkp += $d->ptkp;
				$total_pkp += $d->pkp;
				$total_pph_setahun += $d->pph_setahun;
				$total_pajak_setahun += $d->pajak_setahun;
				$total_bruto_sebulan += $d->bruto_sebulan;
				$total_netto_sebulan += $d->netto_sebulan;
				$total_pph_sebulan += $d->pph_sebulan;
				$total_ptkp_sebulan += ($d->ptkp/12);
				$total_pkp_sebulan += ($d->pkp/12);
				$total_premi_asuransi += $d->premi_asuransi;
				$total_pph_tunjangan += $d->pph_tunjangan;
				// $total_jumlahxx += ($d->gaji_pokok-$d->thr);
				$total_jumlahxx += ($d->gaji_pokok+$d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$d->bonus+$d->thr);
				$body_1 = [
					($row-6),
					$d->nik,
					$this->model_karyawan->getKTPEmployee($d->nik),
					$d->nama_karyawan,
					$d->no_npwp,
					$d->nama_jabatan,
					$d->nama_bagian,
					$d->nama_loker,
					$d->nama_grade,
					$d->status_ptkp,
					$this->otherfunctions->getNumberToAbjad($d->koreksi),
					$this->otherfunctions->pembulatanFloor($d->gaji_pokok),
				];
				$body_2=[];
				$valTunjangan = $this->otherfunctions->getDataExplode($d->tunjangan_val,';','all');
				$masterIndukTunjangan = $this->model_master->getIndukTunjanganWhere(null,'a.sifat','DESC');
				foreach ($masterIndukTunjangan as $key_it) {
					$dtun='';
					if(!empty($valTunjangan)){
						foreach ($valTunjangan as $key_tun => $val_tun) {
							$kode_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','start');
							$nominal_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','end');
							$induk=$this->model_master->getListTunjangan(['a.kode_tunjangan'=>$kode_tunjangan],1,true);
							$nominal_t = $this->otherfunctions->pembulatanFloor($nominal_tunjangan);
							if($key_it->kode_induk_tunjangan == $induk['kode_induk_tunjangan']){
								$dtun.=$nominal_t;
							}
						}
					}
					$body_2[]=$dtun;
				}
				$body_3 = [
					$this->otherfunctions->pembulatanFloor($d->tunjangan),
					$this->otherfunctions->pembulatanFloor($d->uang_makan),
					$this->otherfunctions->pembulatanFloor($d->ritasi),
					$this->otherfunctions->pembulatanFloor($d->lembur),
					$this->otherfunctions->pembulatanFloor($d->perjalanan_dinas),
					$this->otherfunctions->pembulatanFloor($d->kode_akun),
					$this->otherfunctions->pembulatanFloor($d->bonus),
					$this->otherfunctions->pembulatanFloor($d->thr),
					$this->otherfunctions->pembulatanFloor($d->gaji_pokok+$d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$d->bonus+$d->thr),
				];
				$pengurang_lainx=0;
				$penambah_lainx=0;
				$pengurang_lainx_hallo=0;
				$penambah_lainx_hallo=0;
				if(!empty($d->data_lain)){
					if (strpos($d->data_lain, ';') !== false) {
						$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
						$dHallo = $this->otherfunctions->getDataExplode($d->data_lain_hallo,';','all');
						$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
						foreach ($dLain as $key => $value) {
							if($value == 'pengurang'){
								if($dHallo[$key] == '1'){
									$pengurang_lainx_hallo += $nLain[$key];
								}else{
									$pengurang_lainx += $nLain[$key];
								}
							}else{
								if($dHallo[$key] == '1'){
									$penambah_lainx_hallo += $nLain[$key];
								}else{
									$penambah_lainx += $nLain[$key];
								}
							}
						}
					}else{
						if($d->data_lain == 'pengurang'){
							if($d->data_lain_hallo == '1'){
								$pengurang_lainx_hallo += $d->nominal_lain;
							}else{
								$pengurang_lainx += $d->nominal_lain;
							}
						}else{
							if($d->data_lain_hallo == '1'){
								$penambah_lainx_hallo += $d->nominal_lain;
							}else{
								$penambah_lainx += $d->nominal_lain;
							}
						}
					}
				}
				$pengurang_lain = $pengurang_lainx;
				$penambah_lain = $penambah_lainx;
				$pengurang_hallo = $pengurang_lainx_hallo;
				$penambah_hallo = $penambah_lainx_hallo;
				$total_pengurang_lain += $pengurang_lainx;
				$total_penambah_lain += $penambah_lainx;
				$total_pengurang_hallo += $pengurang_lainx_hallo;
				$total_penambah_hallo += $penambah_lainx_hallo;
				$body_4 = [
					$this->otherfunctions->pembulatanFloor($d->bpjs_jkk_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jkm_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->premi_asuransi),
					$this->otherfunctions->pembulatanFloor($d->bpjs_kes_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->pph_tunjangan),
					$this->otherfunctions->pembulatanFloor($penambah_lain),
					$this->otherfunctions->pembulatanFloor(($d->bpjs_jkk_perusahaan+$d->bpjs_jkm_perusahaan+$penambah_lain+$d->premi_asuransi+$d->bpjs_kes_perusahaan+$d->pph_tunjangan)),
				];
				$potonganTidakKerja = ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
				$body_5 = [
					$this->otherfunctions->pembulatanFloor($d->bpjs_kes_pekerja),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jkk_pekerja),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jkm_pekerja),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jht_pekerja),
					$this->otherfunctions->pembulatanFloor($d->iuran_pensiun_pekerja),
					$this->otherfunctions->pembulatanFloor($d->hutang),
					$this->otherfunctions->pembulatanFloor($potonganTidakKerja),
					$this->otherfunctions->pembulatanFloor($pengurang_hallo),
					$this->otherfunctions->pembulatanFloor($d->nominal_denda),
					$this->otherfunctions->pembulatanFloor($pengurang_lain),
				];
				$body_6 = [
					$this->otherfunctions->pembulatanFloor($d->yg_diterima),
					$this->otherfunctions->pembulatanFloor($d->bruto_sebulan),
					$this->otherfunctions->pembulatanFloor($d->bruto_setahun),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jht_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->iuran_pensiun_perusahaan),
					$this->otherfunctions->pembulatanFloor(($d->bruto_sebulan+$d->bpjs_jht_perusahaan+$d->iuran_pensiun_perusahaan)),
					$this->otherfunctions->pembulatanFloor($d->biaya_jabatan),
					$this->otherfunctions->pembulatanFloor($d->netto_sebulan),
					$this->otherfunctions->pembulatanFloor($d->netto_setahun),
					$this->otherfunctions->pembulatanFloor(($d->ptkp/12)),
					// $this->otherfunctions->pembulatanFloor($d->ptkp),
					$this->otherfunctions->pembulatanFloor(($d->pkp/12)),
					// $this->otherfunctions->pembulatanFloor($d->pkp),
					$this->otherfunctions->pembulatanFloor($d->pph_sebulan),
					$this->otherfunctions->pembulatanFloor($d->pph_setahun),
					// $this->otherfunctions->pembulatanFloor($d->pajak_setahun),
					null,// 'PPH 21 YANG DIBAYAR',
					null,// 'PPH 21 YANG DIPOTONG',
					// null,// 'Tunjangan PPh ',
					$this->otherfunctions->pembulatanFloor($d->pph_tunjangan),
					$this->otherfunctions->pembulatanFloor($d->pesangon),
					$this->otherfunctions->pembulatanFloor($d->pph_pesangon),
				];
				$data_body = array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
				$bodyx[] = array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
				$body[$row]=$data_body;
				$row++;
			}
		}
		$data_awal_null = [null,'TOTAL',null,null,null,null,null,null,null,null,null,$this->otherfunctions->pembulatanFloor($total_gaji),];
		$data_total = [
			$this->otherfunctions->pembulatanFloor($total_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_uang_makan),
			$this->otherfunctions->pembulatanFloor($total_ritasi),
			$this->otherfunctions->pembulatanFloor($total_lembur),
			$this->otherfunctions->pembulatanFloor($total_perjalanan_dinas),
			$this->otherfunctions->pembulatanFloor($total_kode_akun),
			$this->otherfunctions->pembulatanFloor($total_bonus),
			$this->otherfunctions->pembulatanFloor($total_thr),
			$this->otherfunctions->pembulatanFloor($total_jumlahxx),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_premi_asuransi),
			$this->otherfunctions->pembulatanFloor($total_bpjs_kes_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_pph_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_penambah_lain),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan+$total_bpjs_jkm_perusahaan+$total_penambah_lain+$total_premi_asuransi+$total_bpjs_kes_perusahaan+$total_pph_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_kes_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jht_pekerja),
			$this->otherfunctions->pembulatanFloor($total_iuran_pensiun_pekerja),
			$this->otherfunctions->pembulatanFloor($total_hutang),
			$this->otherfunctions->pembulatanFloor($total_pot_tidak_masuk),
			$this->otherfunctions->pembulatanFloor($total_pengurang_hallo),
			$this->otherfunctions->pembulatanFloor($total_nominal_denda),
			$this->otherfunctions->pembulatanFloor($total_pengurang_lain),
			$this->otherfunctions->pembulatanFloor($total_yg_diterima),
			$this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
			$this->otherfunctions->pembulatanFloor($total_bruto_setahun),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jht_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_iuran_pensiun_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bruto_sebulan+$total_bpjs_jht_perusahaan+$total_iuran_pensiun_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_biaya_jabatan),
			$this->otherfunctions->pembulatanFloor($total_netto_sebulan),
			$this->otherfunctions->pembulatanFloor($total_netto_setahun),
			$this->otherfunctions->pembulatanFloor($total_ptkp_sebulan),
			// $this->otherfunctions->pembulatanFloor($total_ptkp),
			$this->otherfunctions->pembulatanFloor($total_pkp_sebulan),
			// $this->otherfunctions->pembulatanFloor($total_pkp),
			$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
			$this->otherfunctions->pembulatanFloor($total_pph_setahun),
			// $this->otherfunctions->pembulatanFloor($total_pajak_setahun),
			null,null,
			// null,
			$this->otherfunctions->pembulatanFloor($total_pph_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_pesangon),
			$this->otherfunctions->pembulatanFloor($total_pph_pesangon),
		];
		$body[count($bodyx)+8]=array_merge($data_awal_null,$data_total);
		$dataHeadFirst = [
			'No',
			'NIK = NIP',
			'No.ID = No.KTP',
			'Nama',
			'NPWP',
			'Jabatan',
			'Bagian',
			'Lokasi',
			'Grade',
			'Status PTKP',
			'Koreksi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus/Komisi',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','Premi Asuransi','Kesehatan (BPJS)','Tunjangan PPh','Lainnya','Sub Total Penambahan',
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'Kesehatan (BPJS)','JKK','JKM','JHT','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Sebulan',
			'Penghasilan Bruto disetahunkan',
			'TUNJANGAN JHT',
			'TUNJANGAN Jaminan Pensiun',
			'TOTAL PENDAPATAN KARYAWAN SEBULAN',
			'Biaya Jabatan',
			'Penghasilan Netto Sebulan',
			'Penghasilan Netto disetahunkan',
			'PTKP Sebulan',
			// 'PTKP disetahunkan',
			'PKP Sebulan',
			// 'PKP disetahunkan',
			'PPH 21 Sebulan',
			'PPH 21 disetahunkan',
			// 'Pajak disetahunkan',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'Pesangon',
			'PPh Pesangon',
		];
		$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
		$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);
		$body[1]=['LAPORAN PENGHASILAN DAN PERHITUNGAN PPH21'];
		$body[2]=['BULAN '.strtoupper($this->formatter->getNameOfMonth($bulan)).' '.$tahun];
		$body[3]=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD'];
		$body[6]=[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
		'SUM(L:T)',
		null,null,null,null,null,null,
		'SUM(V:AA)',
		null,null,null,null,null,null,null,null,null,null,
		'SUM(L:O) - SUM(AC:AK)',
		'U+AB-AL',
		// 'AN * 12',
		null,
		null,null,
		'AN+AP+AQ',
		'5% * AN',
		'AN-AS-AF-AG',
		'AT * 12',
		null,null,null,null,null,null,null,null,null,null,null,null];
		$jumData = count($bodyx)+8;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data PPH-21',
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
			'head_merge'=>[
				'abjadTop'=>true,
				'jumData'=>[
					'1'=>'AC4:AL'.$jumData,
					'2'=>'AP4:AR'.$jumData,
					'3'=>'BC4:BD'.$jumData,
					'4'=>'V4:AB'.$jumData,
					'5'=>'AM4:AO'.$jumData,
					'6'=>'AS4:BB'.$jumData,
					'7'=>'L4:U'.$jumData,
					],
				'row_head'=>4,
				'row_head_2'=>5,
				'data_head_1'=>$data_head_1,
				'data_head_2'=>$data_head,
				'max_merge'=>43,
				'merge_1'=>'A4:A5',
				'merge_2'=>'B4:B5',
				'merge_3'=>'C4:C5',
				'merge_4'=>'D4:D5',
				'merge_5'=>'E4:E5',
				'merge_6'=>'F4:F5',
				'merge_7'=>'G4:G5',
				'merge_8'=>'H4:H5',
				'merge_9'=>'I4:I5',
				'merge_10'=>'J4:J5',
				'merge_11'=>'K4:K5',
				'merge_12'=>'L4:L5',
				'merge_13'=>'M4:M5',
				'merge_14'=>'N4:N5',
				'merge_15'=>'O4:O5',
				'merge_16'=>'P4:P5',
				'merge_17'=>'Q4:Q5',
				'merge_18'=>'R4:R5',
				'merge_19'=>'S4:S5',
				'merge_20'=>'T4:T5',
				'merge_21'=>'U4:U5',
				'merge_22'=>'V4:AB4',
				'merge_23'=>'AC4:AL4',
				'merge_24'=>'AM4:AM5',
				'merge_25'=>'AN4:AN5',
				'merge_26'=>'AO4:AO5',
				'merge_27'=>'AP4:AP5',
				'merge_28'=>'AQ4:AQ5',
				'merge_29'=>'AR4:AR5',
				'merge_30'=>'AS4:AS5',
				'merge_31'=>'AT4:AT5',
				'merge_32'=>'AU4:AU5',
				'merge_33'=>'AV4:AV5',
				'merge_34'=>'AW4:AW5',
				'merge_35'=>'AX4:AX5',
				'merge_36'=>'AY4:AY5',
				'merge_37'=>'AZ4:AZ5',
				'merge_38'=>'BA4:BA5',
				'merge_39'=>'BB4:BB5',
				'merge_40'=>'BC4:BC5',
				'merge_41'=>'BD4:BD5',
				// 'merge_42'=>'BE4:BE5',
				// 'merge_43'=>'BF4:BF5',
				// 'merge_44'=>'BG4:BG5',
				'merge_42'=>'A1:D1',
				'merge_43'=>'A2:D2',
			]
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_all()
	{
		$sheet = [];
		$dataHeadFirst = [
			'No',
			'NIK = NIP',
			'No.ID = No.KTP',
			'Nama',
			'NPWP',
			'Jabatan',
			'Bagian',
			'Lokasi',
			'Grade',
			'Status PTKP',
			'Koreksi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus/Komisi',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','Premi Asuransi','Kesehatan (BPJS)','Tunjangan PPh','Lainnya','Sub Total Penambahan',
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'Kesehatan (BPJS)','JKK','JKM','JHT','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Sebulan',
			'Penghasilan Bruto Disetahunkan',
			'TUNJANGAN JHT',
			'TUNJANGAN Jaminan Pensiun',
			'TOTAL PENDAPATAN KARYAWAN',
			'Biaya Jabatan',
			'Penghasilan Netto Sebulan',
			'Penghasilan Netto Disetahunkan',
			'PTKP Sebulan',
			// 'PTKP Disetahunkan',
			'PKP Sebulan',
			// 'PKP Disetahunkan',
			'PPH 21 Sebulan',
			'PPH 21 Disetahunkan',
			// 'Pajak Disetahunkan',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'Pesangon',
			'PPh Pesangon',
		];
		for ($bulanx=1; $bulanx < 13; $bulanx++) { 
			$bulan = ($bulanx<10)?'0'.$bulanx:$bulanx;
			$tahun = $this->input->post('tahun');
			$koreksi = $this->input->post('koreksi');
			$data_pph_other = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]));
			$data['properties']=[
				'title'=>'Rekap Data PPH-21 Periode '.$tahun,
				'subject'=>'Rekap Data PPH-21',
				'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
				'keywords'=>"Export, Rekap, Rekap Data PPH-21",
				'category'=>"Rekap",
			];
			$body=[];
			$bodyx=[];
			$row_body=7;
			$row=$row_body;
			// $karyawan = $this->model_karyawan->getEmployeeAllActive();
			$karyawan = $this->otherfunctions->getAllKaryawanYear($tahun);
			$gaji_pokok = 0;
			$total_gaji = 0;
			$total_tunjangan = 0;
			$total_uang_makan = 0;
			$total_ritasi = 0;
			$total_lembur = 0;
			$total_perjalanan_dinas = 0;
			$total_kode_akun = 0;
			$total_bonus = 0;
			$total_thr = 0;
			$total_pesangon = 0;
			$total_pph_pesangon = 0;
			// $tt = [];
			$total_pengurang_lain = 0;
			$total_penambah_lain = 0;
			$total_pengurang_hallo = 0;
			$total_penambah_hallo = 0;
			$total_bpjs_jkk_perusahaan = 0;
			$total_bpjs_jkm_perusahaan = 0;
			$total_bpjs_jht_perusahaan = 0;
			$total_bpjs_kes_perusahaan = 0;
			$total_iuran_pensiun_perusahaan = 0;
			$total_bpjs_jht_pekerja = 0;
			$total_bpjs_jkk_pekerja = 0;
			$total_bpjs_jkm_pekerja = 0;
			$total_bpjs_kes_pekerja = 0;
			$total_iuran_pensiun_pekerja = 0;
			$total_hutang = 0;
			$total_pot_tidak_masuk = 0;
			$total_nominal_denda = 0;
			$total_yg_diterima = 0;
			$total_bruto_setahun = 0;
			$total_biaya_jabatan = 0;
			$total_netto_setahun = 0;
			$total_ptkp = 0;
			$total_pkp = 0;
			$total_pph_setahun = 0;
			$total_pajak_setahun = 0;
			$total_bruto_sebulan = 0;
			$total_netto_sebulan = 0;
			$total_pph_sebulan = 0;
			$total_ptkp_sebulan = 0;
			$total_pkp_sebulan = 0;
			$total_premi_asuransi = 0;
			$total_pph_tunjangan = 0;
			$total_jumlahxx = 0;
			$body_2=[];
			foreach ($karyawan as $k) {
				$datax = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>$k->nik,'a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]);
				if(!empty($datax)){
					foreach ($datax as $d) {
						$gaji_pokok += $d->gaji_pokok;
						$total_gaji += $d->gaji_pokok;
						$total_tunjangan += $d->tunjangan;
						$total_uang_makan += $d->uang_makan;
						$total_ritasi += $d->ritasi;
						$total_lembur += $d->lembur;
						$total_perjalanan_dinas += $d->perjalanan_dinas;
						$total_kode_akun += $d->kode_akun;
						$total_bonus += $d->bonus;
						$total_thr += $d->thr;
						// $total_jumlahxx += ($d->gaji_pokok-$d->thr);
						$total_jumlahxx += ($d->gaji_pokok+$d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$d->bonus+$d->thr);
						$total_pesangon += $d->pesangon;
						$total_pph_pesangon += $d->pph_pesangon;
						$total_bpjs_jkk_perusahaan += $d->bpjs_jkk_perusahaan;
						$total_bpjs_jkm_perusahaan += $d->bpjs_jkm_perusahaan;
						$total_bpjs_jht_perusahaan += $d->bpjs_jht_perusahaan;
						$total_bpjs_kes_perusahaan += $d->bpjs_kes_perusahaan;
						$total_iuran_pensiun_perusahaan += $d->iuran_pensiun_perusahaan;
						$total_bpjs_jht_pekerja += $d->bpjs_jht_pekerja;
						$total_bpjs_jkk_pekerja += $d->bpjs_jkk_pekerja;
						$total_bpjs_jkm_pekerja += $d->bpjs_jkm_pekerja;
						$total_bpjs_kes_pekerja += $d->bpjs_kes_pekerja;
						$total_iuran_pensiun_pekerja += $d->iuran_pensiun_pekerja;
						$total_premi_asuransi += $d->premi_asuransi;
						$total_hutang += $d->hutang;
						$total_pot_tidak_masuk += ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
						$total_nominal_denda += $d->nominal_denda;
						$total_yg_diterima += $d->yg_diterima;
						$total_bruto_setahun += $d->bruto_setahun;
						$total_biaya_jabatan += $d->biaya_jabatan;
						$total_netto_setahun += $d->netto_setahun;
						$total_ptkp += $d->ptkp;
						$total_pkp += $d->pkp;
						$total_pph_setahun += $d->pph_setahun;
						$total_pajak_setahun += $d->pajak_setahun;
						$total_bruto_sebulan += $d->bruto_sebulan;
						$total_netto_sebulan += $d->netto_sebulan;
						$total_pph_sebulan += $d->pph_sebulan;
						$total_ptkp_sebulan += ($d->ptkp/12);
						$total_pkp_sebulan += ($d->pkp/12);
						$total_pph_tunjangan += $d->pph_tunjangan;
						$body_1 = [
							($row-6),
							$d->nik,
							$this->model_karyawan->getKTPEmployee($d->nik),
							$d->nama_karyawan,
							$d->no_npwp,
							$d->nama_jabatan,
							$d->nama_bagian,
							$d->nama_loker,
							$d->nama_grade,
							$d->status_ptkp,
							$this->otherfunctions->getNumberToAbjad($d->koreksi),
							$this->otherfunctions->pembulatanFloor($d->gaji_pokok),
						];
						$body_3 = [
							$this->otherfunctions->pembulatanFloor($d->tunjangan),
							$this->otherfunctions->pembulatanFloor($d->uang_makan),
							$this->otherfunctions->pembulatanFloor($d->ritasi),
							$this->otherfunctions->pembulatanFloor($d->lembur),
							$this->otherfunctions->pembulatanFloor($d->perjalanan_dinas),
							$this->otherfunctions->pembulatanFloor($d->kode_akun),
							$this->otherfunctions->pembulatanFloor($d->bonus),
							$this->otherfunctions->pembulatanFloor($d->thr),
							$this->otherfunctions->pembulatanFloor($d->gaji_pokok+$d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$d->bonus+$d->thr),
						];
						$pengurang_lainx=0;
						$penambah_lainx=0;
						$pengurang_lainx_hallo=0;
						$penambah_lainx_hallo=0;
						if(!empty($d->data_lain)){
							if (strpos($d->data_lain, ';') !== false) {
								$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
								$dHallo = $this->otherfunctions->getDataExplode($d->data_lain_hallo,';','all');
								$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
								foreach ($dLain as $key => $value) {
									if($value == 'pengurang'){
										if($dHallo[$key] == '1'){
											$pengurang_lainx_hallo += $nLain[$key];
										}else{
											$pengurang_lainx += $nLain[$key];
										}
									}else{
										if($dHallo[$key] == '1'){
											$penambah_lainx_hallo += $nLain[$key];
										}else{
											$penambah_lainx += $nLain[$key];
										}
									}
								}
							}else{
								if($d->data_lain == 'pengurang'){
									if($d->data_lain_hallo == '1'){
										$pengurang_lainx_hallo += $d->nominal_lain;
									}else{
										$pengurang_lainx += $d->nominal_lain;
									}
								}else{
									if($d->data_lain_hallo == '1'){
										$penambah_lainx_hallo += $d->nominal_lain;
									}else{
										$penambah_lainx += $d->nominal_lain;
									}
								}
							}
						}
						$pengurang_lain = $pengurang_lainx;
						$penambah_lain = $penambah_lainx;
						$pengurang_hallo = $pengurang_lainx_hallo;
						$penambah_hallo = $penambah_lainx_hallo;
						$total_pengurang_lain += $pengurang_lainx;
						$total_penambah_lain += $penambah_lainx;
						$total_pengurang_hallo += $pengurang_lainx_hallo;
						$total_penambah_hallo += $penambah_lainx_hallo;
						$body_4 = [
							$this->otherfunctions->pembulatanFloor($d->bpjs_jkk_perusahaan),
							$this->otherfunctions->pembulatanFloor($d->bpjs_jkm_perusahaan),
							$this->otherfunctions->pembulatanFloor($d->premi_asuransi),
							$this->otherfunctions->pembulatanFloor($d->bpjs_kes_perusahaan),
							$this->otherfunctions->pembulatanFloor($d->pph_tunjangan),
							// null,// 'PPh',
							$this->otherfunctions->pembulatanFloor($penambah_lain),
							$this->otherfunctions->pembulatanFloor(($d->bpjs_jkk_perusahaan+$d->bpjs_jkm_perusahaan+$penambah_lain+$d->premi_asuransi+$d->bpjs_kes_perusahaan+$d->pph_tunjangan)),
						];
						$potonganTidakKerja = ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
						$body_5 = [
							$this->otherfunctions->pembulatanFloor($d->bpjs_kes_pekerja),
							$this->otherfunctions->pembulatanFloor($d->bpjs_jkk_pekerja),
							$this->otherfunctions->pembulatanFloor($d->bpjs_jkm_pekerja),
							$this->otherfunctions->pembulatanFloor($d->bpjs_jht_pekerja),
							$this->otherfunctions->pembulatanFloor($d->iuran_pensiun_pekerja),
							$this->otherfunctions->pembulatanFloor($d->hutang),
							$this->otherfunctions->pembulatanFloor($potonganTidakKerja),
							$this->otherfunctions->pembulatanFloor($pengurang_hallo),
							$this->otherfunctions->pembulatanFloor($d->nominal_denda),
							$this->otherfunctions->pembulatanFloor($pengurang_lain),
						];
						$body_6 = [
							$this->otherfunctions->pembulatanFloor($d->yg_diterima),
							$this->otherfunctions->pembulatanFloor($d->bruto_sebulan),
							$this->otherfunctions->pembulatanFloor($d->bruto_setahun),
							$this->otherfunctions->pembulatanFloor($d->bpjs_jht_perusahaan),
							$this->otherfunctions->pembulatanFloor($d->iuran_pensiun_perusahaan),
							$this->otherfunctions->pembulatanFloor(($d->bruto_sebulan+$d->bpjs_jht_perusahaan+$d->iuran_pensiun_perusahaan)),
							$this->otherfunctions->pembulatanFloor($d->biaya_jabatan),
							$this->otherfunctions->pembulatanFloor($d->netto_sebulan),
							$this->otherfunctions->pembulatanFloor($d->netto_setahun),
							$this->otherfunctions->pembulatanFloor(($d->ptkp/12)),
							// $this->otherfunctions->pembulatanFloor($d->ptkp),
							$this->otherfunctions->pembulatanFloor(($d->pkp/12)),
							// $this->otherfunctions->pembulatanFloor($d->pkp),
							$this->otherfunctions->pembulatanFloor($d->pph_sebulan),
							$this->otherfunctions->pembulatanFloor($d->pph_setahun),
							// $this->otherfunctions->pembulatanFloor($d->pajak_setahun),
							null,// 'PPH 21 YANG DIBAYAR',
							null,// 'PPH 21 YANG DIPOTONG',
							// null,// 'Tunjangan PPh ',
							$this->otherfunctions->pembulatanFloor($d->pph_tunjangan),
							$this->otherfunctions->pembulatanFloor($d->pesangon),
							$this->otherfunctions->pembulatanFloor($d->pph_pesangon),
						];
						$bodyx[] = array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
						$data_body = array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
						$body[$row]=$data_body;
						$row++;
					}
				}
			}
			$data_awal_null = [null,'TOTAL',null,null,null,null,null,null,null,null,null,$this->otherfunctions->pembulatanFloor($total_gaji),];
			$data_total = [
				$this->otherfunctions->pembulatanFloor($total_tunjangan),
				$this->otherfunctions->pembulatanFloor($total_uang_makan),
				$this->otherfunctions->pembulatanFloor($total_ritasi),
				$this->otherfunctions->pembulatanFloor($total_lembur),
				$this->otherfunctions->pembulatanFloor($total_perjalanan_dinas),
				$this->otherfunctions->pembulatanFloor($total_kode_akun),
				$this->otherfunctions->pembulatanFloor($total_bonus),
				$this->otherfunctions->pembulatanFloor($total_thr),
				$this->otherfunctions->pembulatanFloor($total_jumlahxx),
				$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan),
				$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_perusahaan),
				$this->otherfunctions->pembulatanFloor($total_premi_asuransi),
				$this->otherfunctions->pembulatanFloor($total_bpjs_kes_perusahaan),
				$this->otherfunctions->pembulatanFloor($total_pph_tunjangan),
				// null, 
				$this->otherfunctions->pembulatanFloor($total_penambah_lain),
				$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan+$total_bpjs_jkm_perusahaan+$total_penambah_lain+$total_premi_asuransi+$total_bpjs_kes_perusahaan+$total_pph_tunjangan),
				$this->otherfunctions->pembulatanFloor($total_bpjs_kes_pekerja),
				$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_pekerja),
				$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_pekerja),
				$this->otherfunctions->pembulatanFloor($total_bpjs_jht_pekerja),
				$this->otherfunctions->pembulatanFloor($total_iuran_pensiun_pekerja),
				$this->otherfunctions->pembulatanFloor($total_hutang),
				$this->otherfunctions->pembulatanFloor($total_pot_tidak_masuk),
				$this->otherfunctions->pembulatanFloor($total_pengurang_hallo),
				$this->otherfunctions->pembulatanFloor($total_nominal_denda),
				$this->otherfunctions->pembulatanFloor($total_pengurang_lain),
				$this->otherfunctions->pembulatanFloor($total_yg_diterima),
				$this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
				$this->otherfunctions->pembulatanFloor($total_bruto_setahun),
				$this->otherfunctions->pembulatanFloor($total_bpjs_jht_perusahaan),
				$this->otherfunctions->pembulatanFloor($total_iuran_pensiun_perusahaan),
				$this->otherfunctions->pembulatanFloor($total_bruto_sebulan+$total_bpjs_jht_perusahaan+$total_iuran_pensiun_perusahaan),
				$this->otherfunctions->pembulatanFloor($total_biaya_jabatan),
				$this->otherfunctions->pembulatanFloor($total_netto_sebulan),
				$this->otherfunctions->pembulatanFloor($total_netto_setahun),
				$this->otherfunctions->pembulatanFloor($total_ptkp_sebulan),
				// $this->otherfunctions->pembulatanFloor($total_ptkp),
				$this->otherfunctions->pembulatanFloor($total_pkp_sebulan),
				// $this->otherfunctions->pembulatanFloor($total_pkp),
				$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
				$this->otherfunctions->pembulatanFloor($total_pph_setahun),
				// $this->otherfunctions->pembulatanFloor($total_pajak_setahun),
				null,null,
				// null,
				$this->otherfunctions->pembulatanFloor($total_pph_tunjangan),
				$this->otherfunctions->pembulatanFloor($total_pesangon),
				$this->otherfunctions->pembulatanFloor($total_pph_pesangon),
			];
			$body[count($bodyx)+8]=array_merge($data_awal_null,$data_total);
			$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
			$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);
			$body[1]=['LAPORAN PENGHASILAN DAN PERHITUNGAN PPH21'];
			$body[2]=['BULAN '.strtoupper($this->formatter->getNameOfMonth($bulan)).' '.$tahun];
			$body[3]=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD'];
			$body[6]=[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
			'SUM(L:T)',
			null,null,null,null,null,null,
			'SUM(V:AA)',
			null,null,null,null,null,null,null,null,null,null,
			'SUM(L:O) - SUM(AC:AK)',
			'U+AB-AL',
			// 'AN * 12',
			null,
			null,null,
			'AN+AP+AQ',
			'5% * AN',
			'AN-AS-AF-AG',
			'AT * 12',
			null,null,null,null,null,null,null,null,null,null,null,null];
			$jumData = count($bodyx)+8;
			$sheet[($bulanx-1)]=[
				'range_huruf'=>3,
				'sheet_title'=>$this->formatter->getNameOfMonth($bulan).' '.$tahun,
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
				'head_merge'=>[
					'abjadTop'=>true,
					'jumData'=>[
						'1'=>'AC4:AL'.$jumData,
						'2'=>'AP4:AR'.$jumData,
						'3'=>'BC4:BD'.$jumData,
						'4'=>'V4:AB'.$jumData,
						'5'=>'AM4:AO'.$jumData,
						'6'=>'AS4:BB'.$jumData,
						'7'=>'L4:U'.$jumData,
						],
					'row_head'=>4,
					'row_head_2'=>5,
					'data_head_1'=>$data_head_1,
					'data_head_2'=>$data_head,
					'max_merge'=>43,
					'merge_1'=>'A4:A5',
					'merge_2'=>'B4:B5',
					'merge_3'=>'C4:C5',
					'merge_4'=>'D4:D5',
					'merge_5'=>'E4:E5',
					'merge_6'=>'F4:F5',
					'merge_7'=>'G4:G5',
					'merge_8'=>'H4:H5',
					'merge_9'=>'I4:I5',
					'merge_10'=>'J4:J5',
					'merge_11'=>'K4:K5',
					'merge_12'=>'L4:L5',
					'merge_13'=>'M4:M5',
					'merge_14'=>'N4:N5',
					'merge_15'=>'O4:O5',
					'merge_16'=>'P4:P5',
					'merge_17'=>'Q4:Q5',
					'merge_18'=>'R4:R5',
					'merge_19'=>'S4:S5',
					'merge_20'=>'T4:T5',
					'merge_21'=>'U4:U5',
					'merge_22'=>'V4:AB4',
					'merge_23'=>'AC4:AL4',
					'merge_24'=>'AM4:AM5',
					'merge_25'=>'AN4:AN5',
					'merge_26'=>'AO4:AO5',
					'merge_27'=>'AP4:AP5',
					'merge_28'=>'AQ4:AQ5',
					'merge_29'=>'AR4:AR5',
					'merge_30'=>'AS4:AS5',
					'merge_31'=>'AT4:AT5',
					'merge_32'=>'AU4:AU5',
					'merge_33'=>'AV4:AV5',
					'merge_34'=>'AW4:AW5',
					'merge_35'=>'AX4:AX5',
					'merge_36'=>'AY4:AY5',
					'merge_37'=>'AZ4:AZ5',
					'merge_38'=>'BA4:BA5',
					'merge_39'=>'BB4:BB5',
					'merge_40'=>'BC4:BC5',
					'merge_41'=>'BD4:BD5',
					// 'merge_42'=>'BE4:BE5',
					// 'merge_43'=>'BF4:BF5',
					// 'merge_44'=>'BG4:BG5',
					'merge_42'=>'A1:D1',
					'merge_43'=>'A2:D2',
				]
			];
		}
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_tahunan()
	{		
		$dataHeadFirst = [
			'No',
			'NIK = NIP',
			'No.ID = No.KTP',
			'Nama',
			'NPWP',
			'Jabatan',
			'Bagian',
			'Lokasi',
			'Grade',
			'Status PTKP',
			'Koreksi',
			'Gaji Pokok YtD',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan YtD',
			'Uang Makan YtD',
			'Ritasi YtD',
			'Lembur YtD',
			'Perjalanan Dinas YtD',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll YtD',
			'Bonus/Komisi YtD',
			'THR YtD',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','Premi Asuransi','Kesehatan (BPJS)','Tunjangan PPh','Lainnya','Sub Total Penambahan',
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'Kesehatan (BPJS)','JKK','JKM','JHT','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto YtD',
			'Penghasilan Bruto Setahun',
			'TUNJANGAN JHT',
			'TUNJANGAN Jaminan Pensiun',
			'TOTAL PENDAPATAN'."\n".'KARYAWAN YtD',
			'TOTAL PENGHASILAN'."\n".'BRUTO SETAHUN',
			'Biaya Jabatan',
			'Penghasilan Netto YtD',
			'Penghasilan Netto Setahun',
			'PTKP YtD',
			'PTKP Setahun',
			'PKP YtD',
			'PKP Setahun',
			'PPH 21 YtD',
			'PPH 21 Setahun',
			'Pajak Setahun',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'Pesangon',
			'PPh Pesangon',
		];
		$tahun = $this->input->post('tahun');
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Tahun '.$tahun,
			'subject'=>'Rekap Data PPH-21',
			'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21",
			'category'=>"Rekap",
		];
		$body=[];
		$bodyx=[];
		$row_body=7;
		$row=$row_body;
		$karyawan = $this->otherfunctions->getAllKaryawanYear($tahun);
		$gaji_pokok = 0;
		$total_gaji = 0;
		$total_tunjangan = 0;
		$total_uang_makan = 0;
		$total_ritasi = 0;
		$total_lembur = 0;
		$total_perjalanan_dinas = 0;
		$total_kode_akun = 0;
		$total_bonus = 0;
		$total_thr = 0;
		$total_pesangon = 0;
		$total_pph_pesangon = 0;
		// $tt = [];
		$total_pengurang_lain = 0;
		$total_penambah_lain = 0;
		$total_pengurang_hallo = 0;
		$total_penambah_hallo = 0;
		$total_bpjs_jkk_perusahaan = 0;
		$total_bpjs_jkm_perusahaan = 0;
		$total_bpjs_jht_perusahaan = 0;
		$total_bpjs_kes_perusahaan = 0;
		$total_iuran_pensiun_perusahaan = 0;
		$total_bpjs_jht_pekerja = 0;
		$total_bpjs_jkk_pekerja = 0;
		$total_bpjs_jkm_pekerja = 0;
		$total_bpjs_kes_pekerja = 0;
		$total_iuran_pensiun_pekerja = 0;
		$total_hutang = 0;
		$total_pot_tidak_masuk = 0;
		$total_nominal_denda = 0;
		$total_yg_diterima = 0;
		$total_bruto_setahun = 0;
		$total_biaya_jabatan = 0;
		$total_netto_setahun = 0;
		$total_ptkp = 0;
		$total_pkp = 0;
		$total_pph_setahun = 0;
		$total_pajak_setahun = 0;
		$total_bruto_sebulan = 0;
		$total_netto_sebulan = 0;
		$total_pph_sebulan = 0;
		$total_ptkp_sebulan = 0;
		$total_pkp_sebulan = 0;
		$total_premi_asuransi = 0;
		$total_pph_tunjangan = 0;
		$total_jumlahxx = 0;
		$body_2=[];
		$bulan = date('m');
		$thisYear=date('Y');
		if ($tahun < $thisYear){
			$bulan=12;
		}
		foreach ($karyawan as $d) {
			$dataPPh = [];
			$maxBulan = 0;
			for ($bln=1; $bln <= $bulan; $bln++) { 
				$dtpph = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>$d->nik,'a.tahun'=>$tahun,'a.bulan'=>$bln],null,null,null,'max',true);
				if(!empty($dtpph)){
					$dataPPh[$bln] = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>$d->nik,'a.tahun'=>$tahun,'a.bulan'=>$bln],null,null,null,'max',true);
					$maxBulan = $bln;
				}
			}
			if(!empty($dataPPh)){
				$gaji_pokok = 0;
				$tt = [];
				$tunjangan = 0;
				$uang_makan = 0;
				$ritasi = 0;
				$lembur = 0;
				$perjalanan_dinas = 0;
				$kode_akun = 0;
				$pengurang_lain = 0;
				$penambah_lain = 0;
				$pengurang_hallo = 0;
				$penambah_hallo = 0;
				$bpjs_jkk_perusahaan = 0;
				$bpjs_jkm_perusahaan = 0;
				$bpjs_jht_perusahaan = 0;
				$bpjs_kes_perusahaan = 0;
				$iuran_pensiun_perusahaan = 0;
				$bpjs_jht_pekerja = 0;
				$bpjs_jkk_pekerja = 0;
				$bpjs_jkm_pekerja = 0;
				$bpjs_kes_pekerja = 0;
				$iuran_pensiun_pekerja = 0;
				$hutang = 0;
				$pot_tidak_masuk = 0;
				$nominal_denda = 0;
				$yg_diterima = 0;
				$bruto_setahun = 0;
				$bruto_sebulan = 0;
				$netto_sebulan = 0;
				$biaya_jabatan = 0;
				$netto_setahun = 0;
				$ptkp = '';
				$pkp = 0;
				$pph_setahun = 0;
				$pajak_setahun = 0;
				$bonus = 0;
				$thr = 0;
				$premi_asuransi = 0;
				$pph_tunjangan = 0;
				$pesangon = 0;
				$pph_pesangon = 0;
				$no_npwp = '';
				$jumlah_x = 0;
				$jumdata = 0;
				foreach ($dataPPh as $val) {
					$jumdata += 1;
					$gaji_pokok += $val['gaji_pokok'];
					$total_gaji += $val['gaji_pokok'];
					$total_tunjangan += $val['tunjangan'];
					$total_uang_makan += $val['uang_makan'];
					$total_ritasi += $val['ritasi'];
					$total_lembur += $val['lembur'];
					$total_perjalanan_dinas += $val['perjalanan_dinas'];
					$total_kode_akun += $val['kode_akun'];
					$total_bonus += $val['bonus'];
					$total_thr += $val['thr'];
					$total_pesangon += $val['pesangon'];
					$total_pph_pesangon += $val['pph_pesangon'];
					$total_bpjs_jkk_perusahaan += $val['bpjs_jkk_perusahaan'];
					$total_bpjs_jkm_perusahaan += $val['bpjs_jkm_perusahaan'];
					$total_bpjs_jht_perusahaan += $val['bpjs_jht_perusahaan'];
					$total_bpjs_kes_perusahaan += $val['bpjs_kes_perusahaan'];
					$total_iuran_pensiun_perusahaan += $val['iuran_pensiun_perusahaan'];
					$total_bpjs_jht_pekerja += $val['bpjs_jht_pekerja'];
					$total_bpjs_jkk_pekerja += $val['bpjs_jkk_pekerja'];
					$total_bpjs_jkm_pekerja += $val['bpjs_jkm_pekerja'];
					$total_bpjs_kes_pekerja += $val['bpjs_kes_pekerja'];
					$total_iuran_pensiun_pekerja += $val['iuran_pensiun_pekerja'];
					$total_premi_asuransi += $val['premi_asuransi'];
					$total_pph_tunjangan += $val['pph_tunjangan'];
					$total_hutang += $val['hutang'];
					$total_pot_tidak_masuk += ($val['pot_tidak_kerja']+$val['pot_tidak_masuk']);
					$total_nominal_denda += $val['nominal_denda'];
					$total_yg_diterima += $val['yg_diterima'];
					// $total_bruto_setahun += $val['bruto_setahun'];
					$total_biaya_jabatan += $val['biaya_jabatan'];
					// $total_netto_setahun += $val['netto_setahun'];
					$total_ptkp += $val['ptkp'];
					$total_pkp += $val['pkp'];
					$total_pph_setahun += $val['pph_setahun'];
					$total_pajak_setahun += $val['pajak_setahun'];
					$total_bruto_sebulan += $val['bruto_sebulan'];
					$total_netto_sebulan += $val['netto_sebulan'];
					$total_pph_sebulan += $val['pph_sebulan'];
					$total_ptkp_sebulan += ($val['ptkp']/12);
					$total_pkp_sebulan += ($val['pkp']/12);
					$total_jumlahxx += ($val['gaji_pokok']+$val['tunjangan']+$val['uang_makan']+$val['ritasi']+$val['lembur']+$val['perjalanan_dinas']+$val['kode_akun']+$val['bonus']+$val['thr']);
					$jumlah_x += ($val['gaji_pokok']+$val['tunjangan']+$val['uang_makan']+$val['ritasi']+$val['lembur']+$val['perjalanan_dinas']+$val['kode_akun']+$val['bonus']+$val['thr']);
					$tunjangan +=$val['tunjangan'];
					$uang_makan +=$val['uang_makan'];
					$ritasi +=$val['ritasi'];
					$lembur +=$val['lembur'];
					$perjalanan_dinas +=$val['perjalanan_dinas'];
					$kode_akun +=$val['kode_akun'];
					$bonus +=$val['bonus'];
					$thr +=$val['thr'];
					$premi_asuransi +=$val['premi_asuransi'];
					$pph_tunjangan +=$val['pph_tunjangan'];
					$pesangon +=$val['pesangon'];
					$pph_pesangon +=$val['pph_pesangon'];
					$pengurang_lainx=0;
					$penambah_lainx=0;
					$pengurang_lainx_hallo=0;
					$penambah_lainx_hallo=0;
					if(!empty($val['data_lain'])){
						if (strpos($val['data_lain'], ';') !== false) {
							$dLain = $this->otherfunctions->getDataExplode($val['data_lain'],';','all');
							$dHallo = $this->otherfunctions->getDataExplode($val['data_lain_hallo'],';','all');
							$nLain = $this->otherfunctions->getDataExplode($val['nominal_lain'],';','all');
							foreach ($dLain as $key => $value) {
								if($value == 'pengurang'){
									if($dHallo[$key] == '1'){
										$pengurang_lainx_hallo += $nLain[$key];
									}else{
										$pengurang_lainx += $nLain[$key];
									}
								}else{
									if($dHallo[$key] == '1'){
										$penambah_lainx_hallo += $nLain[$key];
									}else{
										$penambah_lainx += $nLain[$key];
									}
								}
							}
						}else{
							if($val['data_lain'] == 'pengurang'){
								if($val['data_lain_hallo'] == '1'){
									$pengurang_lainx_hallo += $val['nominal_lain'];
								}else{
									$pengurang_lainx += $val['nominal_lain'];
								}
							}else{
								if($val['data_lain_hallo'] == '1'){
									$penambah_lainx_hallo += $val['nominal_lain'];
								}else{
									$penambah_lainx += $val['nominal_lain'];
								}
							}
						}
					}
					$pengurang_lain += $pengurang_lainx;
					$penambah_lain += $penambah_lainx;
					$pengurang_hallo += $pengurang_lainx_hallo;
					$penambah_hallo += $penambah_lainx_hallo;
					$total_pengurang_lain += $pengurang_lainx;
					$total_penambah_lain += $penambah_lainx;
					$total_pengurang_hallo += $pengurang_lainx_hallo;
					$total_penambah_hallo += $penambah_lainx_hallo;
					$bpjs_jkk_perusahaan +=$val['bpjs_jkk_perusahaan'];
					$bpjs_jkm_perusahaan +=$val['bpjs_jkm_perusahaan'];
					$bpjs_jht_perusahaan +=$val['bpjs_jht_perusahaan'];
					$bpjs_kes_perusahaan +=$val['bpjs_kes_perusahaan'];
					$iuran_pensiun_perusahaan +=$val['iuran_pensiun_perusahaan'];
					$bpjs_jht_pekerja +=$val['bpjs_jht_pekerja'];
					$bpjs_jkk_pekerja +=$val['bpjs_jkk_pekerja'];
					$bpjs_jkm_pekerja +=$val['bpjs_jkm_pekerja'];
					$bpjs_kes_pekerja +=$val['bpjs_kes_pekerja'];
					$iuran_pensiun_pekerja +=$val['iuran_pensiun_pekerja'];
					$hutang +=$val['hutang'];
					$pot_tidak_masuk +=($val['pot_tidak_kerja']+$val['pot_tidak_masuk']);
					$nominal_denda +=$val['nominal_denda'];
					$yg_diterima +=$val['yg_diterima'];
					$bruto_sebulan += $val['bruto_sebulan'];
					$netto_sebulan += $val['netto_sebulan'];
					$bruto_setahun +=$val['bruto_setahun'];
					$biaya_jabatan +=$val['biaya_jabatan'];
					$netto_setahun +=$val['netto_setahun'];
					$ptkp =$val['ptkp'];
					$pkp +=$val['pkp'];
					$pph_setahun +=$val['pph_setahun'];
					$pajak_setahun +=$val['pajak_setahun'];
					$no_npwp =$val['no_npwp'];
				}
				if($jumdata < 12){
					$kurangBulan = 12 - $jumdata;
				}else{
					$kurangBulan = 0;
				}
				// print_r($jumdata);
				$bruto_tahun = ($dataPPh[$maxBulan]['bruto_sebulan']*$kurangBulan)+$bruto_sebulan;
				$netto_tahun = ($dataPPh[$maxBulan]['netto_sebulan']*$kurangBulan)+$netto_sebulan;
				$total_bruto_setahun += $bruto_tahun;
				$total_netto_setahun += $netto_tahun;
				$body_1 = [
					($row-6),
					$d->nik,
					$this->model_karyawan->getKTPEmployee($d->nik),
					$d->nama_karyawan,
					$d->no_npwp,
					$d->nama_jabatan,
					$d->nama_bagian,
					$d->nama_loker,
					$d->nama_grade,
					$d->status_ptkp,
					null,//$this->otherfunctions->getNumberToAbjad($d->koreksi),
					$this->otherfunctions->pembulatanFloor($gaji_pokok),
				];
				$body_3 = [
					$this->otherfunctions->pembulatanFloor($tunjangan),
					$this->otherfunctions->pembulatanFloor($uang_makan),
					$this->otherfunctions->pembulatanFloor($ritasi),
					$this->otherfunctions->pembulatanFloor($lembur),
					$this->otherfunctions->pembulatanFloor($perjalanan_dinas),
					$this->otherfunctions->pembulatanFloor($kode_akun),
					$this->otherfunctions->pembulatanFloor($bonus),
					$this->otherfunctions->pembulatanFloor($thr),
					$this->otherfunctions->pembulatanFloor($jumlah_x),
					$this->otherfunctions->pembulatanFloor($bpjs_jkk_perusahaan),
					$this->otherfunctions->pembulatanFloor($bpjs_jkm_perusahaan),
					$this->otherfunctions->pembulatanFloor($premi_asuransi),
					$this->otherfunctions->pembulatanFloor($bpjs_kes_perusahaan),
					$this->otherfunctions->pembulatanFloor($pph_tunjangan),
					// null,
					$this->otherfunctions->pembulatanFloor($penambah_lain),
					$this->otherfunctions->pembulatanFloor($bpjs_jkk_perusahaan+$bpjs_jkm_perusahaan+$penambah_lain+$premi_asuransi+$bpjs_kes_perusahaan+$pph_tunjangan),
					$this->otherfunctions->pembulatanFloor($bpjs_kes_pekerja),
					$this->otherfunctions->pembulatanFloor($bpjs_jkk_pekerja),
					$this->otherfunctions->pembulatanFloor($bpjs_jkm_pekerja),
					$this->otherfunctions->pembulatanFloor($bpjs_jht_pekerja),
					$this->otherfunctions->pembulatanFloor($iuran_pensiun_pekerja),
					$this->otherfunctions->pembulatanFloor($hutang),
					$this->otherfunctions->pembulatanFloor($pot_tidak_masuk),
					$this->otherfunctions->pembulatanFloor($pengurang_hallo),
					$this->otherfunctions->pembulatanFloor($nominal_denda),
					$this->otherfunctions->pembulatanFloor($pengurang_lain),
					$this->otherfunctions->pembulatanFloor($yg_diterima),
					$this->otherfunctions->pembulatanFloor($bruto_sebulan),
					$this->otherfunctions->pembulatanFloor($bruto_tahun),
					// $this->otherfunctions->pembulatanFloor($bruto_setahun),
					$this->otherfunctions->pembulatanFloor($bpjs_jht_perusahaan),
					$this->otherfunctions->pembulatanFloor($iuran_pensiun_perusahaan),
					$this->otherfunctions->pembulatanFloor(($bruto_sebulan)+$bpjs_jht_perusahaan+$iuran_pensiun_perusahaan),
					$this->otherfunctions->pembulatanFloor(($bruto_tahun)+$bpjs_jht_perusahaan+$iuran_pensiun_perusahaan),
					$this->otherfunctions->pembulatanFloor($biaya_jabatan),
					$this->otherfunctions->pembulatanFloor($netto_sebulan),
					$this->otherfunctions->pembulatanFloor($netto_tahun),
					// $this->otherfunctions->pembulatanFloor($netto_setahun),
					$this->otherfunctions->pembulatanFloor($ptkp/12),
					$this->otherfunctions->pembulatanFloor($ptkp),
					$this->otherfunctions->pembulatanFloor($pkp/12),
					$this->otherfunctions->pembulatanFloor($pkp),
					$this->otherfunctions->pembulatanFloor($pph_setahun/12),
					$this->otherfunctions->pembulatanFloor($pph_setahun),
					$this->otherfunctions->pembulatanFloor($pajak_setahun),
					null,
					null,
					// null,
					$this->otherfunctions->pembulatanFloor($pph_tunjangan),
					$this->otherfunctions->pembulatanFloor($pesangon),
					$this->otherfunctions->pembulatanFloor($pph_pesangon),
				];
				$data_body = array_merge($body_1,$body_3);
				$bodyx[] = array_merge($body_1,$body_3);
				$body[$row]=$data_body;
				$row++;
			}
		}
		$data_awal_null = [null,'TOTAL',null,null,null,null,null,null,null,null,null,$this->otherfunctions->pembulatanFloor($total_gaji),];
		$data_total = [
			$this->otherfunctions->pembulatanFloor($total_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_uang_makan),
			$this->otherfunctions->pembulatanFloor($total_ritasi),
			$this->otherfunctions->pembulatanFloor($total_lembur),
			$this->otherfunctions->pembulatanFloor($total_perjalanan_dinas),
			$this->otherfunctions->pembulatanFloor($total_kode_akun),
			$this->otherfunctions->pembulatanFloor($total_bonus),
			$this->otherfunctions->pembulatanFloor($total_thr),
			$this->otherfunctions->pembulatanFloor($total_jumlahxx),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_premi_asuransi),
			$this->otherfunctions->pembulatanFloor($total_bpjs_kes_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_pph_tunjangan),
			// null, 
			$this->otherfunctions->pembulatanFloor($total_penambah_lain),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan+$total_bpjs_jkm_perusahaan+$total_penambah_lain+$total_premi_asuransi+$total_bpjs_kes_perusahaan+$total_pph_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_kes_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jht_pekerja),
			$this->otherfunctions->pembulatanFloor($total_iuran_pensiun_pekerja),
			$this->otherfunctions->pembulatanFloor($total_hutang),
			$this->otherfunctions->pembulatanFloor($total_pot_tidak_masuk),
			$this->otherfunctions->pembulatanFloor($total_pengurang_hallo),
			$this->otherfunctions->pembulatanFloor($total_nominal_denda),
			$this->otherfunctions->pembulatanFloor($total_pengurang_lain),
			$this->otherfunctions->pembulatanFloor($total_yg_diterima),
			$this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
			$this->otherfunctions->pembulatanFloor($total_bruto_setahun),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jht_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_iuran_pensiun_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bruto_sebulan+$total_bpjs_jht_perusahaan+$total_iuran_pensiun_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bruto_setahun+$total_bpjs_jht_perusahaan+$total_iuran_pensiun_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_biaya_jabatan),
			$this->otherfunctions->pembulatanFloor($total_netto_sebulan),
			$this->otherfunctions->pembulatanFloor($total_netto_setahun),
			$this->otherfunctions->pembulatanFloor($total_ptkp_sebulan),
			$this->otherfunctions->pembulatanFloor($total_ptkp),
			$this->otherfunctions->pembulatanFloor($total_pkp_sebulan),
			$this->otherfunctions->pembulatanFloor($total_pkp),
			$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
			$this->otherfunctions->pembulatanFloor($total_pph_setahun),
			$this->otherfunctions->pembulatanFloor($total_pajak_setahun),
			null,null,
			//null,
			$this->otherfunctions->pembulatanFloor($total_pph_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_pesangon),
			$this->otherfunctions->pembulatanFloor($total_pph_pesangon),
		];
		$body[count($bodyx)+8]=array_merge($data_awal_null,$data_total);
		$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
		$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);	
		$body[1]=['LAPORAN PENGHASILAN DAN PERHITUNGAN PPH21'];
		$bulanx = date('m');
		if ($tahun < $thisYear){
			$bulanx=12;
		}
		$body[2]=['JANUARI s.d '.strtoupper($this->formatter->getNameOfMonth($bulanx)).' '.$tahun];
		$body[3]=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH',];
		$body[6]=[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
		'SUM(L:T)',
		null,null,null,null,null,null,
		'SUM(V:AA)',
		null,null,null,null,null,null,null,null,null,null,
		'SUM(L:O) - SUM(AC:AK)',
		'U+AB-AL',
		// 'KOLOM AN * 12',
		null,null,null,
		'AN+AP+AQ',
		'AO+AP+AQ',
		'5% * AN',
		'AN-AS-AF-AG',
		'AT * 12',
		null,null,null,null,null,null,null,null,null,null,null,null];
		$jumData = count($bodyx)+8;	
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data PPh 21 Tahun '.$tahun,
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
			'head_merge'=>[
				'abjadTop'=>true,
				'jumData'=>[
					'1'=>'AC4:AL'.$jumData,
					'2'=>'AP4:AS'.$jumData,
					'3'=>'BG4:BH'.$jumData,
					'4'=>'V4:AB'.$jumData,
					'5'=>'AM4:AO'.$jumData,
					'6'=>'AT4:BF'.$jumData,
					'7'=>'L4:U'.$jumData,
					],
				'row_head'=>4,
				'row_head_2'=>5,
				'data_head_1'=>$data_head_1,
				'data_head_2'=>$data_head,
				'max_merge'=>47,
				'merge_1'=>'A4:A5',
				'merge_2'=>'B4:B5',
				'merge_3'=>'C4:C5',
				'merge_4'=>'D4:D5',
				'merge_5'=>'E4:E5',
				'merge_6'=>'F4:F5',
				'merge_7'=>'G4:G5',
				'merge_8'=>'H4:H5',
				'merge_9'=>'I4:I5',
				'merge_10'=>'J4:J5',
				'merge_11'=>'K4:K5',
				'merge_12'=>'L4:L5',
				'merge_13'=>'M4:M5',
				'merge_14'=>'N4:N5',
				'merge_15'=>'O4:O5',
				'merge_16'=>'P4:P5',
				'merge_17'=>'Q4:Q5',
				'merge_18'=>'R4:R5',
				'merge_19'=>'S4:S5',
				'merge_20'=>'T4:T5',
				'merge_21'=>'U4:U5',
				'merge_22'=>'V4:AB4',
				'merge_23'=>'AC4:AL4',
				'merge_24'=>'AM4:AM5',
				'merge_25'=>'AN4:AN5',
				'merge_26'=>'AO4:AO5',
				'merge_27'=>'AP4:AP5',
				'merge_28'=>'AQ4:AQ5',
				'merge_29'=>'AR4:AR5',
				'merge_30'=>'AS4:AS5',
				'merge_31'=>'AT4:AT5',
				'merge_32'=>'AU4:AU5',
				'merge_33'=>'AV4:AV5',
				'merge_34'=>'AW4:AW5',
				'merge_35'=>'AX4:AX5',
				'merge_36'=>'AY4:AY5',
				'merge_37'=>'AZ4:AZ5',
				'merge_38'=>'BA4:BA5',
				'merge_39'=>'BB4:BB5',
				'merge_40'=>'BC4:BC5',
				'merge_41'=>'BD4:BD5',
				'merge_42'=>'BE4:BE5',
				'merge_43'=>'BF4:BF5',
				'merge_44'=>'BG4:BG5',
				'merge_45'=>'BH4:BH5',
				'merge_46'=>'A1:D1',
				'merge_47'=>'A2:D2',
			]
		];
		$data['data']=$sheet;
		// echo '<pre>';
		// print_r($data);
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_tahunan_old_2()
	{		
		$dataHeadFirst = [
			'No',
			'NIK = NIP',
			'No.ID = No.KTP',
			'Nama',
			'NPWP',
			'Jabatan',
			'Bagian',
			'Lokasi',
			'Grade',
			'Status PTKP',
			'Koreksi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus/Komisi',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','Lainnya','Premi Asuransi','Kesehatan (BPJS)','Tunjangan PPh','Sub Total Penambahan',
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'Kesehatan (BPJS)','JKK','JKM','JHT','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Sebulan',
			'Penghasilan Bruto Setahun',
			'TUNJANGAN JHT',
			'TUNJANGAN Jaminan Pensiun',
			'TOTAL PENDAPATAN KARYAWAN',
			'Biaya Jabatan',
			'Penghasilan Netto Sebulan',
			'Penghasilan Netto Setahun',
			'PTKP Sebulan',
			'PTKP Setahun',
			'PKP Sebulan',
			'PKP Setahun',
			'PPH 21 Sebulan',
			'PPH 21 Setahun',
			'Pajak Setahun',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'Pesangon',
			'PPh Pesangon',
		];
		$tahun = $this->input->post('tahun');
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Tahun '.$tahun,
			'subject'=>'Rekap Data PPH-21',
			'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21",
			'category'=>"Rekap",
		];
		$body=[];
		$bodyx=[];
		$row_body=5;
		$row=$row_body;
		// $karyawan = $this->model_karyawan->getEmployeeAllActive();
		$karyawan = $this->otherfunctions->getAllKaryawanYear($tahun);
		$gaji_pokok = 0;
		$total_gaji = 0;
		$total_tunjangan = 0;
		$total_uang_makan = 0;
		$total_ritasi = 0;
		$total_lembur = 0;
		$total_perjalanan_dinas = 0;
		$total_kode_akun = 0;
		$total_bonus = 0;
		$total_thr = 0;
		$total_pesangon = 0;
		$total_pph_pesangon = 0;
		// $tt = [];
		$total_pengurang_lain = 0;
		$total_penambah_lain = 0;
		$total_pengurang_hallo = 0;
		$total_penambah_hallo = 0;
		$total_bpjs_jkk_perusahaan = 0;
		$total_bpjs_jkm_perusahaan = 0;
		$total_bpjs_jht_perusahaan = 0;
		$total_bpjs_kes_perusahaan = 0;
		$total_iuran_pensiun_perusahaan = 0;
		$total_bpjs_jht_pekerja = 0;
		$total_bpjs_jkk_pekerja = 0;
		$total_bpjs_jkm_pekerja = 0;
		$total_bpjs_kes_pekerja = 0;
		$total_iuran_pensiun_pekerja = 0;
		$total_hutang = 0;
		$total_pot_tidak_masuk = 0;
		$total_nominal_denda = 0;
		$total_yg_diterima = 0;
		$total_bruto_setahun = 0;
		$total_biaya_jabatan = 0;
		$total_netto_setahun = 0;
		$total_ptkp = 0;
		$total_pkp = 0;
		$total_pph_setahun = 0;
		$total_pajak_setahun = 0;
		$total_bruto_sebulan = 0;
		$total_netto_sebulan = 0;
		$total_pph_sebulan = 0;
		$total_ptkp_sebulan = 0;
		$total_pkp_sebulan = 0;
		$total_premi_asuransi = 0;
		$total_jumlahxx = 0;
		$body_2=[];
		foreach ($karyawan as $d) {
			$dataPPh = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>$d->nik,'a.tahun'=>$tahun],null,null,null,'max');
			if(!empty($dataPPh)){
				$gaji_pokok = 0;
				$tt = [];
				$tunjangan = 0;
				$uang_makan = 0;
				$ritasi = 0;
				$lembur = 0;
				$perjalanan_dinas = 0;
				$kode_akun = 0;
				$pengurang_lain = 0;
				$penambah_lain = 0;
				$pengurang_hallo = 0;
				$penambah_hallo = 0;
				$bpjs_jkk_perusahaan = 0;
				$bpjs_jkm_perusahaan = 0;
				$bpjs_jht_perusahaan = 0;
				$bpjs_kes_perusahaan = 0;
				$iuran_pensiun_perusahaan = 0;
				$bpjs_jht_pekerja = 0;
				$bpjs_jkk_pekerja = 0;
				$bpjs_jkm_pekerja = 0;
				$bpjs_kes_pekerja = 0;
				$iuran_pensiun_pekerja = 0;
				$hutang = 0;
				$pot_tidak_masuk = 0;
				$nominal_denda = 0;
				$yg_diterima = 0;
				$bruto_setahun = 0;
				$bruto_sebulan = 0;
				$netto_sebulan = 0;
				$biaya_jabatan = 0;
				$netto_setahun = 0;
				$ptkp = '';
				$pkp = 0;
				$pph_setahun = 0;
				$pajak_setahun = 0;
				$bonus = 0;
				$thr = 0;
				$premi_asuransi = 0;
				$pesangon = 0;
				$pph_pesangon = 0;
				$no_npwp = '';
				$jumlah_x = 0;
				foreach ($dataPPh as $p) {
					$gaji_pokok += $p->gaji_pokok;
					$total_gaji += $p->gaji_pokok;
					$total_tunjangan += $p->tunjangan;
					$total_uang_makan += $p->uang_makan;
					$total_ritasi += $p->ritasi;
					$total_lembur += $p->lembur;
					$total_perjalanan_dinas += $p->perjalanan_dinas;
					$total_kode_akun += $p->kode_akun;
					$total_bonus += $p->bonus;
					$total_thr += $p->thr;
					$total_pesangon += $p->pesangon;
					$total_pph_pesangon += $p->pph_pesangon;
					$total_bpjs_jkk_perusahaan += $p->bpjs_jkk_perusahaan;
					$total_bpjs_jkm_perusahaan += $p->bpjs_jkm_perusahaan;
					$total_bpjs_jht_perusahaan += $p->bpjs_jht_perusahaan;
					$total_bpjs_kes_perusahaan += $p->bpjs_kes_perusahaan;
					$total_iuran_pensiun_perusahaan += $p->iuran_pensiun_perusahaan;
					$total_bpjs_jht_pekerja += $p->bpjs_jht_pekerja;
					$total_bpjs_jkk_pekerja += $p->bpjs_jkk_pekerja;
					$total_bpjs_jkm_pekerja += $p->bpjs_jkm_pekerja;
					$total_bpjs_kes_pekerja += $p->bpjs_kes_pekerja;
					$total_iuran_pensiun_pekerja += $p->iuran_pensiun_pekerja;
					$total_premi_asuransi += $p->premi_asuransi;
					$total_hutang += $p->hutang;
					$total_pot_tidak_masuk += ($p->pot_tidak_kerja+$p->pot_tidak_masuk);
					$total_nominal_denda += $p->nominal_denda;
					$total_yg_diterima += $p->yg_diterima;
					$total_bruto_setahun += $p->bruto_setahun;
					$total_biaya_jabatan += $p->biaya_jabatan;
					$total_netto_setahun += $p->netto_setahun;
					$total_ptkp += $p->ptkp;
					$total_pkp += $p->pkp;
					$total_pph_setahun += $p->pph_setahun;
					$total_pajak_setahun += $p->pajak_setahun;
					$total_bruto_sebulan += $p->bruto_sebulan;
					$total_netto_sebulan += $p->netto_sebulan;
					$total_pph_sebulan += $p->pph_sebulan;
					$total_ptkp_sebulan += ($p->ptkp/12);
					$total_pkp_sebulan += ($p->pkp/12);
					// $total_jumlahxx += ($p->gaji_pokok-$p->thr);
					// $jumlah_x += ($p->gaji_pokok-$p->thr);
					$total_jumlahxx += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
					$jumlah_x += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
					// $gaji_pokok +=$p->gaji_pokok;
					$tunjangan +=$p->tunjangan;
					$uang_makan +=$p->uang_makan;
					$ritasi +=$p->ritasi;
					$lembur +=$p->lembur;
					$perjalanan_dinas +=$p->perjalanan_dinas;
					$kode_akun +=$p->kode_akun;
					$bonus +=$p->bonus;
					$thr +=$p->thr;
					$premi_asuransi +=$p->premi_asuransi;
					$pesangon +=$p->pesangon;
					$pph_pesangon +=$p->pph_pesangon;
					$pengurang_lainx=0;
					$penambah_lainx=0;
					$pengurang_lainx_hallo=0;
					$penambah_lainx_hallo=0;
					if(!empty($p->data_lain)){
						if (strpos($p->data_lain, ';') !== false) {
							$dLain = $this->otherfunctions->getDataExplode($p->data_lain,';','all');
							$dHallo = $this->otherfunctions->getDataExplode($p->data_lain_hallo,';','all');
							$nLain = $this->otherfunctions->getDataExplode($p->nominal_lain,';','all');
							foreach ($dLain as $key => $value) {
								if($value == 'pengurang'){
									if($dHallo[$key] == '1'){
										$pengurang_lainx_hallo += $nLain[$key];
									}else{
										$pengurang_lainx += $nLain[$key];
									}
								}else{
									if($dHallo[$key] == '1'){
										$penambah_lainx_hallo += $nLain[$key];
									}else{
										$penambah_lainx += $nLain[$key];
									}
								}
							}
						}else{
							if($p->data_lain == 'pengurang'){
								if($p->data_lain_hallo == '1'){
									$pengurang_lainx_hallo += $p->nominal_lain;
								}else{
									$pengurang_lainx += $p->nominal_lain;
								}
							}else{
								if($p->data_lain_hallo == '1'){
									$penambah_lainx_hallo += $p->nominal_lain;
								}else{
									$penambah_lainx += $p->nominal_lain;
								}
							}
						}
					}
					$pengurang_lain += $pengurang_lainx;
					$penambah_lain += $penambah_lainx;
					$pengurang_hallo += $pengurang_lainx_hallo;
					$penambah_hallo += $penambah_lainx_hallo;
					$total_pengurang_lain += $pengurang_lainx;
					$total_penambah_lain += $penambah_lainx;
					$total_pengurang_hallo += $pengurang_lainx_hallo;
					$total_penambah_hallo += $penambah_lainx_hallo;
					$bpjs_jkk_perusahaan +=$p->bpjs_jkk_perusahaan;
					$bpjs_jkm_perusahaan +=$p->bpjs_jkm_perusahaan;
					$bpjs_jht_perusahaan +=$p->bpjs_jht_perusahaan;
					$bpjs_kes_perusahaan +=$p->bpjs_kes_perusahaan;
					$iuran_pensiun_perusahaan +=$p->iuran_pensiun_perusahaan;
					$bpjs_jht_pekerja +=$p->bpjs_jht_pekerja;
					$bpjs_jkk_pekerja +=$p->bpjs_jkk_pekerja;
					$bpjs_jkm_pekerja +=$p->bpjs_jkm_pekerja;
					$bpjs_kes_pekerja +=$p->bpjs_kes_pekerja;
					$iuran_pensiun_pekerja +=$p->iuran_pensiun_pekerja;
					$hutang +=$p->hutang;
					$pot_tidak_masuk +=($p->pot_tidak_kerja+$p->pot_tidak_masuk);
					$nominal_denda +=$p->nominal_denda;
					$yg_diterima +=$p->yg_diterima;
					$bruto_sebulan += $p->bruto_sebulan;
					$netto_sebulan += $p->netto_sebulan;
					$bruto_setahun +=$p->bruto_setahun;
					$biaya_jabatan +=$p->biaya_jabatan;
					$netto_setahun +=$p->netto_setahun;
					$ptkp =$p->ptkp;
					$pkp +=$p->pkp;
					$pph_setahun +=$p->pph_setahun;
					$pajak_setahun +=$p->pajak_setahun;
					$no_npwp =$p->no_npwp;
				}
				$body_1 = [
					($row-4),
					$d->nik,
					$d->no_ktp,
					$d->nama_karyawan,
					$d->no_npwp,
					$d->nama_jabatan,
					$d->nama_bagian,
					$d->nama_loker,
					$d->nama_grade,
					$d->status_ptkp,
					null,//$this->otherfunctions->getNumberToAbjad($d->koreksi),
					$this->otherfunctions->pembulatanFloor($gaji_pokok),
				];
				$body_3 = [
					$this->otherfunctions->pembulatanFloor($tunjangan),
					$this->otherfunctions->pembulatanFloor($uang_makan),
					$this->otherfunctions->pembulatanFloor($ritasi),
					$this->otherfunctions->pembulatanFloor($lembur),
					$this->otherfunctions->pembulatanFloor($perjalanan_dinas),
					$this->otherfunctions->pembulatanFloor($kode_akun),
					$this->otherfunctions->pembulatanFloor($bonus),
					$this->otherfunctions->pembulatanFloor($thr),
					$this->otherfunctions->pembulatanFloor($jumlah_x),
					$this->otherfunctions->pembulatanFloor($bpjs_jkk_perusahaan),
					$this->otherfunctions->pembulatanFloor($bpjs_jkm_perusahaan),
					$this->otherfunctions->pembulatanFloor($penambah_lain),
					$this->otherfunctions->pembulatanFloor($premi_asuransi),
					$this->otherfunctions->pembulatanFloor($bpjs_kes_perusahaan),
					null,
					$this->otherfunctions->pembulatanFloor($bpjs_jkk_perusahaan+$bpjs_jkm_perusahaan+$penambah_lain+$premi_asuransi+$bpjs_kes_perusahaan),
					$this->otherfunctions->pembulatanFloor($bpjs_kes_pekerja),
					$this->otherfunctions->pembulatanFloor($bpjs_jkk_pekerja),
					$this->otherfunctions->pembulatanFloor($bpjs_jkm_pekerja),
					$this->otherfunctions->pembulatanFloor($bpjs_jht_pekerja),
					$this->otherfunctions->pembulatanFloor($iuran_pensiun_pekerja),
					$this->otherfunctions->pembulatanFloor($hutang),
					$this->otherfunctions->pembulatanFloor($pot_tidak_masuk),
					$this->otherfunctions->pembulatanFloor($pengurang_hallo),
					$this->otherfunctions->pembulatanFloor($nominal_denda),
					$this->otherfunctions->pembulatanFloor($pengurang_lain),
					$this->otherfunctions->pembulatanFloor($yg_diterima),
					$this->otherfunctions->pembulatanFloor($bruto_sebulan),
					$this->otherfunctions->pembulatanFloor($bruto_sebulan*12),
					// $this->otherfunctions->pembulatanFloor($bruto_setahun),
					$this->otherfunctions->pembulatanFloor($bpjs_jht_perusahaan),
					$this->otherfunctions->pembulatanFloor($iuran_pensiun_perusahaan),
					$this->otherfunctions->pembulatanFloor(($bruto_sebulan*12)+$bpjs_jht_perusahaan+$iuran_pensiun_perusahaan),
					$this->otherfunctions->pembulatanFloor($biaya_jabatan),
					$this->otherfunctions->pembulatanFloor($netto_sebulan),
					$this->otherfunctions->pembulatanFloor($netto_sebulan*12),
					// $this->otherfunctions->pembulatanFloor($netto_setahun),
					$this->otherfunctions->pembulatanFloor($ptkp/12),
					$this->otherfunctions->pembulatanFloor($ptkp),
					$this->otherfunctions->pembulatanFloor($pkp/12),
					$this->otherfunctions->pembulatanFloor($pkp),
					$this->otherfunctions->pembulatanFloor($pph_setahun/12),
					$this->otherfunctions->pembulatanFloor($pph_setahun),
					$this->otherfunctions->pembulatanFloor($pajak_setahun),
					null,
					null,
					null,
					$this->otherfunctions->pembulatanFloor($pesangon),
					$this->otherfunctions->pembulatanFloor($pph_pesangon),
				];
				$data_body = array_merge($body_1,$body_3);
				$bodyx[] = array_merge($body_1,$body_3);
				$body[$row]=$data_body;
				$row++;
			}
		}
		$data_awal_null = [null,'TOTAL',null,null,null,null,null,null,null,null,null,$this->otherfunctions->pembulatanFloor($total_gaji),];
		$data_total = [
			$this->otherfunctions->pembulatanFloor($total_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_uang_makan),
			$this->otherfunctions->pembulatanFloor($total_ritasi),
			$this->otherfunctions->pembulatanFloor($total_lembur),
			$this->otherfunctions->pembulatanFloor($total_perjalanan_dinas),
			$this->otherfunctions->pembulatanFloor($total_kode_akun),
			$this->otherfunctions->pembulatanFloor($total_bonus),
			$this->otherfunctions->pembulatanFloor($total_thr),
			$this->otherfunctions->pembulatanFloor($total_jumlahxx),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_penambah_lain),
			$this->otherfunctions->pembulatanFloor($total_premi_asuransi),
			$this->otherfunctions->pembulatanFloor($total_bpjs_kes_perusahaan),
			null, 
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan+$total_bpjs_jkm_perusahaan+$total_penambah_lain+$total_premi_asuransi+$total_bpjs_kes_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_kes_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jht_pekerja),
			$this->otherfunctions->pembulatanFloor($total_iuran_pensiun_pekerja),
			$this->otherfunctions->pembulatanFloor($total_hutang),
			$this->otherfunctions->pembulatanFloor($total_pot_tidak_masuk),
			$this->otherfunctions->pembulatanFloor($total_pengurang_hallo),
			$this->otherfunctions->pembulatanFloor($total_nominal_denda),
			$this->otherfunctions->pembulatanFloor($total_pengurang_lain),
			$this->otherfunctions->pembulatanFloor($total_yg_diterima),
			$this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
			$this->otherfunctions->pembulatanFloor($total_bruto_setahun),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jht_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_iuran_pensiun_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bruto_sebulan+$total_bpjs_jht_perusahaan+$total_iuran_pensiun_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_biaya_jabatan),
			$this->otherfunctions->pembulatanFloor($total_netto_sebulan),
			$this->otherfunctions->pembulatanFloor($total_netto_setahun),
			$this->otherfunctions->pembulatanFloor($total_ptkp_sebulan),
			$this->otherfunctions->pembulatanFloor($total_ptkp),
			$this->otherfunctions->pembulatanFloor($total_pkp_sebulan),
			$this->otherfunctions->pembulatanFloor($total_pkp),
			$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
			$this->otherfunctions->pembulatanFloor($total_pph_setahun),
			$this->otherfunctions->pembulatanFloor($total_pajak_setahun),
			null,null,null,
			$this->otherfunctions->pembulatanFloor($total_pesangon),
			$this->otherfunctions->pembulatanFloor($total_pph_pesangon),
		];
		$body[count($bodyx)+6]=array_merge($data_awal_null,$data_total);
		$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
		$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);	
		$body[1]=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG',];
		$body[4]=[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
		'SUM(L:T)',
		null,null,null,null,null,null,
		'SUM(V:AA)',
		null,null,null,null,null,null,null,null,null,null,
		'SUM(L:O) - SUM(AC:AK)',
		'JUMLAH KOLOM U+AB-AL',
		'KOLOM AN * 12',
		null,null,
		'JUMLAH KOLOM AN+AP+AQ',
		'5% DARI PENGHASILAN BRUTO',
		'KOLOM AN-AS-AF-AG',
		'KOLOM AT * 12',
		null,null,null,null,null,null,null,null,null,null,null,null];
		$jumData = count($bodyx)+6;	
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data PPh 21 Tahun '.$tahun,
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
			'head_merge'=>[
				'abjadTop'=>true,
				'jumData'=>[
					'1'=>'AC2:AL'.$jumData,
					'2'=>'AP2:AR'.$jumData,
					'3'=>'BF2:BG'.$jumData,
					'4'=>'V2:AB'.$jumData,
					'5'=>'AM2:AO'.$jumData,
					'6'=>'AS2:BE'.$jumData,
					'7'=>'L2:U'.$jumData,
					],
				'row_head'=>2,
				'row_head_2'=>3,
				'data_head_1'=>$data_head_1,
				'data_head_2'=>$data_head,
				'max_merge'=>44,
				'merge_1'=>'A2:A3',
				'merge_2'=>'B2:B3',
				'merge_3'=>'C2:C3',
				'merge_4'=>'D2:D3',
				'merge_5'=>'E2:E3',
				'merge_6'=>'F2:F3',
				'merge_7'=>'G2:G3',
				'merge_8'=>'H2:H3',
				'merge_9'=>'I2:I3',
				'merge_10'=>'J2:J3',
				'merge_11'=>'K2:K3',
				'merge_12'=>'L2:L3',
				'merge_13'=>'M2:M3',
				'merge_14'=>'N2:N3',
				'merge_15'=>'O2:O3',
				'merge_16'=>'P2:P3',
				'merge_17'=>'Q2:Q3',
				'merge_18'=>'R2:R3',
				'merge_19'=>'S2:S3',
				'merge_20'=>'T2:T3',
				'merge_21'=>'U2:U3',
				'merge_22'=>'V2:AB2',
				'merge_23'=>'AC2:AL2',
				'merge_24'=>'AM2:AM3',
				'merge_25'=>'AN2:AN3',
				'merge_26'=>'AO2:AO3',
				'merge_27'=>'AP2:AP3',
				'merge_28'=>'AQ2:AQ3',
				'merge_29'=>'AR2:AR3',
				'merge_30'=>'AS2:AS3',
				'merge_31'=>'AT2:AT3',
				'merge_32'=>'AU2:AU3',
				'merge_33'=>'AV2:AV3',
				'merge_34'=>'AW2:AW3',
				'merge_35'=>'AX2:AX3',
				'merge_36'=>'AY2:AY3',
				'merge_37'=>'AZ2:AZ3',
				'merge_38'=>'BA2:BA3',
				'merge_39'=>'BB2:BB3',
				'merge_40'=>'BC2:BC3',
				'merge_41'=>'BD2:BD3',
				'merge_42'=>'BE2:BE3',
				'merge_43'=>'BF2:BF3',
				'merge_44'=>'BG2:BG3',
			]
		];
		$data['data']=$sheet;
		// print_r($data);
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_bagian()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$bagian = $this->input->post('bagian');
		$all_bag = $this->input->post('all_bag');
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Periode '.$this->formatter->getNameOfMonth($bulan).' '.$tahun,
			'subject'=>'Rekap Data PPH-21',
			'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=3;
		$row=$row_body;
		$datax=[];
		if($all_bag == 1){
			$dBagian = $this->model_master->getListBagian(true);
			foreach ($dBagian as $bg) {
				$datax[] = $this->model_payroll->getListDataPenggajianPph(['a.kode_bagian'=>$bg->kode_bagian,'a.bulan'=>$bulan,'a.tahun'=>$tahun],null,null,null,'max');
			}
		}else{
			foreach ($bagian as $bag) {
				$datax[] = $this->model_payroll->getListDataPenggajianPph(['a.kode_bagian'=>$bag,'a.bulan'=>$bulan,'a.tahun'=>$tahun],null,null,null,'max');
			}
		}
		$data_bodyx = [];
		if(!empty($datax)){
			$total_gaji = 0;
			$total_tunjangan = 0;
			$total_uang_makan = 0;
			$total_ritasi = 0;
			$total_lembur = 0;
			$total_perjalanan_dinas = 0;
			$total_bonus = 0;
			$total_thr = 0;
			$total_kode_akun = 0;
			$total_bpjs_jkk_perusahaan = 0;
			$total_bpjs_jkm_perusahaan = 0;
			$total_bpjs_jht_perusahaan = 0;
			$total_bpjs_kes_perusahaan = 0;
			$total_iuran_pensiun_perusahaan = 0;
			$total_pengurang_lain = 0;
			$total_penambah_lain = 0;
			$total_pengurang_hallo = 0;
			$total_penambah_hallo = 0;
			$total_bpjs_jht_pekerja = 0;
			$total_bpjs_jkk_pekerja = 0;
			$total_bpjs_jkm_pekerja = 0;
			$total_bpjs_kes_pekerja = 0;
			$total_iuran_pensiun_pekerja = 0;
			$total_hutang = 0;
			$total_pot_tidak_masuk = 0;
			$total_nominal_denda = 0;
			$total_yg_diterima = 0;
			$total_bruto_setahun = 0;
			$total_biaya_jabatan = 0;
			$total_netto_setahun = 0;
			$total_ptkp = 0;
			$total_pkp = 0;
			$total_pph_setahun = 0;
			$total_pajak_setahun = 0;
			$total_bruto_sebulan = 0;
			$total_netto_sebulan = 0;
			$total_pph_sebulan = 0;
			$total_ptkp_sebulan = 0;
			$total_pkp_sebulan = 0;
			$total_jumlahxx = 0;
			foreach ($datax as $key => $bagianx) {
				$gaji_pokok = 0;
				$tunjangan = 0;
				$uang_makan = 0;
				$ritasi = 0;
				$lembur = 0;
				$perjalanan_dinas = 0;
				$kode_akun = 0;
				$bonus = 0;
				$thr = 0;
				$pengurang_lain = 0;
				$penambah_lain = 0;
				$pengurang_hallo = 0;
				$penambah_hallo = 0;
				$bpjs_jkk_perusahaan = 0;
				$bpjs_jkm_perusahaan = 0;
				$bpjs_jht_perusahaan = 0;
				$bpjs_kes_perusahaan = 0;
				$iuran_pensiun_perusahaan = 0;
				$bpjs_jht_pekerja = 0;
				$bpjs_jkk_pekerja = 0;
				$bpjs_jkm_pekerja = 0;
				$bpjs_kes_pekerja = 0;
				$iuran_pensiun_pekerja = 0;
				$hutang = 0;
				$pot_tidak_masuk = 0;
				$nominal_denda = 0;
				$yg_diterima = 0;
				$bruto_setahun = 0;
				$biaya_jabatan = 0;
				$netto_setahun = 0;
				$ptkp = 0;
				$pkp = 0;
				$pph_setahun = 0;
				$pajak_setahun = 0;
				$bruto_sebulan = 0;
				$netto_sebulan = 0;
				$pph_sebulan = 0;
				$jumlahxx = 0;
				$no_npwp = '';
				foreach ($bagianx as $p) {
					$gaji_pokok += $p->gaji_pokok;
					$total_gaji += $p->gaji_pokok;
					$total_tunjangan += $p->tunjangan;
					$total_uang_makan += $p->uang_makan;
					$total_ritasi += $p->ritasi;
					$total_lembur += $p->lembur;
					$total_perjalanan_dinas += $p->perjalanan_dinas;
					$total_bonus += $p->bonus;
					$total_thr += $p->thr;
					$total_kode_akun += $p->kode_akun;
					$total_bpjs_jkk_perusahaan += $p->bpjs_jkk_perusahaan;
					$total_bpjs_jkm_perusahaan += $p->bpjs_jkm_perusahaan;
					$total_bpjs_jht_perusahaan += $p->bpjs_jht_perusahaan;
					$total_bpjs_kes_perusahaan += $p->bpjs_kes_perusahaan;
					$total_iuran_pensiun_perusahaan += $p->iuran_pensiun_perusahaan;
					$total_bpjs_jht_pekerja += $p->bpjs_jht_pekerja;
					$total_bpjs_jkk_pekerja += $p->bpjs_jkk_pekerja;
					$total_bpjs_jkm_pekerja += $p->bpjs_jkm_pekerja;
					$total_bpjs_kes_pekerja += $p->bpjs_kes_pekerja;
					$total_iuran_pensiun_pekerja += $p->iuran_pensiun_pekerja;
					$total_hutang += $p->hutang;
					$total_pot_tidak_masuk += ($p->pot_tidak_kerja+$p->pot_tidak_masuk);
					$total_nominal_denda += $p->nominal_denda;
					$total_yg_diterima += $p->yg_diterima;
					$total_bruto_setahun += $p->bruto_setahun;
					$total_biaya_jabatan += $p->biaya_jabatan;
					$total_netto_setahun += $p->netto_setahun;
					$total_ptkp += $p->ptkp;
					$total_pkp += $p->pkp;
					$total_pph_setahun += $p->pph_setahun;
					$total_pajak_setahun += $p->pajak_setahun;
					$total_bruto_sebulan += $p->bruto_sebulan;
					$total_netto_sebulan += $p->netto_sebulan;
					$total_pph_sebulan += $p->pph_sebulan;
					$total_ptkp_sebulan += ($p->ptkp/12);
					$total_pkp_sebulan += ($p->pkp/12);
					// $total_jumlahxx += ($p->gaji_pokok-$p->thr);
					$total_jumlahxx += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
					$body_1 = [
						($row-4),
						$p->nama_bagian,
						$p->nama_loker,
						ceil($gaji_pokok),
					];
					$tunjangan +=$p->tunjangan;
					$uang_makan +=$p->uang_makan;
					$ritasi +=$p->ritasi;
					$lembur +=$p->lembur;
					$perjalanan_dinas +=$p->perjalanan_dinas;
					$bonus +=$p->bonus;
					$thr +=$p->thr;
					$kode_akun +=$p->kode_akun;
					// $jumlahxx += ($p->gaji_pokok-$p->thr);
					$jumlahxx += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
					$pengurang_lainx=0;
					$penambah_lainx=0;
					$pengurang_lainx_hallo=0;
					$penambah_lainx_hallo=0;
					if(!empty($p->data_lain)){
						if (strpos($p->data_lain, ';') !== false) {
							$dLain = $this->otherfunctions->getDataExplode($p->data_lain,';','all');
							$dHallo = $this->otherfunctions->getDataExplode($p->data_lain_hallo,';','all');
							$nLain = $this->otherfunctions->getDataExplode($p->nominal_lain,';','all');
							foreach ($dLain as $key => $value) {
								if($value == 'pengurang'){
									if($dHallo[$key] == '1'){
										$pengurang_lainx_hallo += $nLain[$key];
									}else{
										$pengurang_lainx += $nLain[$key];
									}
								}else{
									if($dHallo[$key] == '1'){
										$penambah_lainx_hallo += $nLain[$key];
									}else{
										$penambah_lainx += $nLain[$key];
									}
								}
							}
						}else{
							if($p->data_lain == 'pengurang'){
								if($p->data_lain_hallo == '1'){
									$pengurang_lainx_hallo += $p->nominal_lain;
								}else{
									$pengurang_lainx += $p->nominal_lain;
								}
							}else{
								if($p->data_lain_hallo == '1'){
									$penambah_lainx_hallo += $p->nominal_lain;
								}else{
									$penambah_lainx += $p->nominal_lain;
								}
							}
						}
					}
					$pengurang_lain = $pengurang_lainx;
					$penambah_lain = $penambah_lainx;
					$pengurang_hallo = $pengurang_lainx_hallo;
					$penambah_hallo = $penambah_lainx_hallo;
					$total_pengurang_lain += $pengurang_lainx;
					$total_penambah_lain += $penambah_lainx;
					$total_pengurang_hallo += $pengurang_lainx_hallo;
					$total_penambah_hallo += $penambah_lainx_hallo;
					$bpjs_jkk_perusahaan +=$p->bpjs_jkk_perusahaan;
					$bpjs_jkm_perusahaan +=$p->bpjs_jkm_perusahaan;
					$bpjs_jht_perusahaan +=$p->bpjs_jht_perusahaan;
					$bpjs_kes_perusahaan +=$p->bpjs_kes_perusahaan;
					$iuran_pensiun_perusahaan +=$p->iuran_pensiun_perusahaan;
					$bpjs_jht_pekerja +=$p->bpjs_jht_pekerja;
					$bpjs_jkk_pekerja +=$p->bpjs_jkk_pekerja;
					$bpjs_jkm_pekerja +=$p->bpjs_jkm_pekerja;
					$bpjs_kes_pekerja +=$p->bpjs_kes_pekerja;
					$iuran_pensiun_pekerja +=$p->iuran_pensiun_pekerja;
					$hutang +=$p->hutang;
					$pot_tidak_masuk +=($p->pot_tidak_kerja+$p->pot_tidak_masuk);
					$nominal_denda +=$p->nominal_denda;
					$yg_diterima +=$p->yg_diterima;
					$bruto_sebulan +=$p->bruto_sebulan;
					$bruto_setahun +=$p->bruto_setahun;
					$biaya_jabatan +=$p->biaya_jabatan;
					$netto_sebulan +=$p->netto_sebulan;
					$netto_setahun +=$p->netto_setahun;
					$ptkp +=$p->ptkp;
					$pkp +=$p->pkp;
					$pph_sebulan +=$p->pph_sebulan;
					$pph_setahun +=$p->pph_setahun;
					$pajak_setahun +=$p->pajak_setahun;
					$no_npwp =$p->no_npwp;
					$body_3 = [
						ceil($tunjangan),
						ceil($uang_makan),
						ceil($ritasi),
						ceil($lembur),
						ceil($perjalanan_dinas),
						ceil($kode_akun),
						ceil($bonus),
						ceil($thr),
						ceil($jumlahxx),
						ceil($bpjs_jkk_perusahaan),
						ceil($bpjs_jkm_perusahaan),
						null,
						ceil($bpjs_jht_perusahaan),
						ceil($bpjs_kes_perusahaan),
						ceil($iuran_pensiun_perusahaan),
						null,
						ceil($penambah_lain),
						ceil($bpjs_jht_pekerja),
						ceil($bpjs_jkk_pekerja),
						ceil($bpjs_jkm_pekerja),
						ceil($bpjs_kes_pekerja),
						ceil($iuran_pensiun_pekerja),
						ceil($hutang),
						ceil($pot_tidak_masuk),
						ceil($pengurang_hallo),
						ceil($nominal_denda),
						ceil($pengurang_lain),
						ceil($yg_diterima),
						ceil($bruto_sebulan),
						ceil($bruto_setahun),
						ceil($biaya_jabatan),
						ceil($netto_sebulan),
						ceil($netto_setahun),
						ceil(($ptkp/12)),
						ceil($ptkp),
						ceil(($pkp/12)),
						ceil($pkp),
						ceil($pph_sebulan),
						ceil($pph_setahun),
						ceil($pajak_setahun),
						null,
						null,
						null,
						$no_npwp,
					];
				// $tunjangan_t[$p->kode_bagian] = $tunjanganx;
				$data_bodyx[$p->kode_bagian] = array_merge($body_1,$body_3);
				$row++;
				}
			}
		}
		$xx=3;
		foreach ($data_bodyx as $dx) {
			$body[$xx]=$dx;
			$xx++;
		}
		$data_awal_null = [null,'TOTAL',null,ceil($total_gaji)];
		$data_total = [
			ceil($total_tunjangan),
			ceil($total_uang_makan),
			ceil($total_ritasi),
			ceil($total_lembur),
			ceil($total_perjalanan_dinas),
			ceil($total_kode_akun),
			ceil($total_bonus),
			ceil($total_thr),
			ceil($total_jumlahxx),
			ceil($total_bpjs_jkk_perusahaan),
			ceil($total_bpjs_jkm_perusahaan),
			null, 
			ceil($total_bpjs_jht_perusahaan),
			ceil($total_bpjs_kes_perusahaan),
			ceil($total_iuran_pensiun_perusahaan),
			null, 
			ceil($total_penambah_lain),
			ceil($total_bpjs_jht_pekerja),
			ceil($total_bpjs_jkk_pekerja),
			ceil($total_bpjs_jkm_pekerja),
			ceil($total_bpjs_kes_pekerja),
			ceil($total_iuran_pensiun_pekerja),
			ceil($total_hutang),
			ceil($total_pot_tidak_masuk),
			ceil($total_pengurang_hallo),
			ceil($total_nominal_denda),
			ceil($total_pengurang_lain),
			ceil($total_yg_diterima),
			ceil($total_bruto_sebulan),
			ceil($total_bruto_setahun),
			ceil($total_biaya_jabatan),
			ceil($total_netto_sebulan),
			ceil($total_netto_setahun),
			ceil($total_ptkp_sebulan),
			ceil($total_ptkp),
			ceil($total_pkp_sebulan),
			ceil($total_pkp),
			ceil($total_pph_sebulan),
			ceil($total_pph_setahun),
			ceil($total_pajak_setahun),
		];
		$body[count($data_bodyx)+4]=array_merge($data_awal_null,$data_total);
		$dataHeadFirst = [
			'No',
			'Bagian',
			'Lokasi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','PPh','JHT','Kesehatan (BPJS)','Jaminan Pensiun','Premi Asuransi','Lainnya'
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'JHT','JKK','JKM','Kesehatan (BPJS)','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Sebulan',
			'Penghasilan Bruto Setahun',
			'Biaya Jabatan',
			'Penghasilan Netto Sebulan',
			'Penghasilan Netto Setahun',
			'PTKP Sebulan',
			'PTKP Setahun',
			'PKP Sebulan',
			'PKP Setahun',
			'PPH 21 Sebulan',
			'PPH 21 Setahun',
			'Pajak Setahun',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'NPWP',
		];
		$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
		$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);
		
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data PPH-21',
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
			'head_merge'=>[
				'row_head'=>1,
				'row_head_2'=>2,
				'data_head_1'=>$data_head_1,
				'data_head_2'=>$data_head,
				'max_merge'=>40,
				'merge_1'=>'A1:A2',
				'merge_2'=>'B1:B2',
				'merge_3'=>'C1:C2',
				'merge_4'=>'D1:D2',
				'merge_5'=>'E1:E2',
				'merge_6'=>'F1:F2',
				'merge_7'=>'G1:G2',
				'merge_8'=>'H1:H2',
				'merge_9'=>'I1:I2',
				'merge_10'=>'J1:J2',
				'merge_11'=>'K1:K2',
				'merge_12'=>'L1:L2',
				'merge_13'=>'M1:M2',
				'merge_14'=>'N1:U1',
				'merge_15'=>'V1:AD1',
				'merge_16'=>'AE1:AE2',
				'merge_17'=>'AF1:AF2',
				'merge_18'=>'AG1:AG2',
				'merge_19'=>'AH1:AH2',
				'merge_20'=>'AI1:AI2',
				'merge_21'=>'AB1:AK1',
				'merge_22'=>'AL1:AL2',
				'merge_23'=>'AM1:AM2',
				'merge_24'=>'AN1:AN2',
				'merge_25'=>'AO1:AO2',
				'merge_26'=>'AP1:AP2',
				'merge_27'=>'AQ1:AQ2',
				'merge_28'=>'AR1:AR2',
				'merge_29'=>'AS1:AS2',
				'merge_30'=>'AT1:AT2',
				'merge_31'=>'AU1:AU2',
				'merge_32'=>'AV1:AV2',
				'merge_33'=>'AW1:AW2',
				'merge_34'=>'AX1:AX2',
				'merge_35'=>'AY1:AY2',
				'merge_36'=>'AZ1:AZ2',
				'merge_37'=>'BA1:BA2',
				'merge_38'=>'BB1:BB2',
				'merge_39'=>'BC1:BC2',
				'merge_40'=>'BD1:BD2',
			]
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_bagian_all()
	{
		$sheet = [];
		$dataHeadFirst = [
			'No',
			'Bagian',
			'Lokasi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','PPh','JHT','Kesehatan (BPJS)','Jaminan Pensiun','Premi Asuransi','Lainnya'
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'JHT','JKK','JKM','Kesehatan (BPJS)','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Sebulan',
			'Penghasilan Bruto Setahun',
			'Biaya Jabatan',
			'Penghasilan Netto Sebulan',
			'Penghasilan Netto Setahun',
			'PTKP Sebulan',
			'PTKP Setahun',
			'PKP Sebulan',
			'PKP Setahun',
			'PPH 21 Sebulan',
			'PPH 21 Setahun',
			'Pajak Setahun',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'NPWP',
		];
		for ($bulanx=1; $bulanx < 13; $bulanx++) { 
			$bulan = ($bulanx<10)?'0'.$bulanx:$bulanx;
			$tahun = $this->input->post('tahun');
			$bagian = $this->input->post('bagian');
			$all_bag = $this->input->post('all_bag');
			$data['properties']=[
				'title'=>'Rekap Data PPH-21 Periode '.$tahun,
				'subject'=>'Rekap Data PPH-21',
				'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
				'keywords'=>"Export, Rekap, Rekap Data PPH-21",
				'category'=>"Rekap",
			];
			$body=[];
			$row_body=3;
			$row=$row_body;
			$datax=[];
			if($all_bag == 1){
				$dBagian = $this->model_master->getListBagian(true);
				foreach ($dBagian as $bg) {
					$datax[] = $this->model_payroll->getListDataPenggajianPph(['a.kode_bagian'=>$bg->kode_bagian,'a.bulan'=>$bulan,'a.tahun'=>$tahun],null,null,null,'max');
				}
			}else{
				foreach ($bagian as $bag) {
					$datax[] = $this->model_payroll->getListDataPenggajianPph(['a.kode_bagian'=>$bag,'a.bulan'=>$bulan,'a.tahun'=>$tahun],null,null,null,'max');
				}
			}
			$data_bodyx = [];
			if(!empty($datax)){
				$total_gaji = 0;
				$total_tunjangan = 0;
				$total_uang_makan = 0;
				$total_ritasi = 0;
				$total_lembur = 0;
				$total_perjalanan_dinas = 0;
				$total_kode_akun = 0;
				$total_bonus = 0;
				$total_thr = 0;
				$total_jumlahxx = 0;
				$total_bpjs_jkk_perusahaan = 0;
				$total_bpjs_jkm_perusahaan = 0;
				$total_bpjs_jht_perusahaan = 0;
				$total_bpjs_kes_perusahaan = 0;
				$total_iuran_pensiun_perusahaan = 0;
				$total_pengurang_lain = 0;
				$total_penambah_lain = 0;
				$total_pengurang_hallo = 0;
				$total_penambah_hallo = 0;
				$total_bpjs_jht_pekerja = 0;
				$total_bpjs_jkk_pekerja = 0;
				$total_bpjs_jkm_pekerja = 0;
				$total_bpjs_kes_pekerja = 0;
				$total_iuran_pensiun_pekerja = 0;
				$total_hutang = 0;
				$total_pot_tidak_masuk = 0;
				$total_nominal_denda = 0;
				$total_yg_diterima = 0;
				$total_bruto_setahun = 0;
				$total_biaya_jabatan = 0;
				$total_netto_setahun = 0;
				$total_ptkp = 0;
				$total_pkp = 0;
				$total_pph_setahun = 0;
				$total_pajak_setahun = 0;
				$total_bruto_sebulan = 0;
				$total_netto_sebulan = 0;
				$total_pph_sebulan = 0;
				$total_ptkp_sebulan = 0;
				$total_pkp_sebulan = 0;
				$tunjangan_t = [];
				foreach ($datax as $key => $bagianx) {
					$gaji_pokok = 0;
					$tt = [];
					$tunjangan = 0;
					$uang_makan = 0;
					$ritasi = 0;
					$lembur = 0;
					$perjalanan_dinas = 0;
					$kode_akun = 0;
					$bonus=0;
					$thr=0;
					$jumlahxx=0;
					$pengurang_lain = 0;
					$penambah_lain = 0;
					$pengurang_hallo = 0;
					$penambah_hallo = 0;
					$bpjs_jkk_perusahaan = 0;
					$bpjs_jkm_perusahaan = 0;
					$bpjs_jht_perusahaan = 0;
					$bpjs_kes_perusahaan = 0;
					$iuran_pensiun_perusahaan = 0;
					$bpjs_jht_pekerja = 0;
					$bpjs_jkk_pekerja = 0;
					$bpjs_jkm_pekerja = 0;
					$bpjs_kes_pekerja = 0;
					$iuran_pensiun_pekerja = 0;
					$hutang = 0;
					$pot_tidak_masuk = 0;
					$nominal_denda = 0;
					$yg_diterima = 0;
					$bruto_setahun = 0;
					$biaya_jabatan = 0;
					$netto_setahun = 0;
					$ptkp = 0;
					$pkp = 0;
					$pph_setahun = 0;
					$pajak_setahun = 0;
					$bruto_sebulan = 0;
					$netto_sebulan = 0;
					$pph_sebulan = 0;
					$no_npwp = '';
					foreach ($bagianx as $p) {
						$gaji_pokok += $p->gaji_pokok;
						$total_gaji += $p->gaji_pokok;
						$total_tunjangan += $p->tunjangan;
						$total_uang_makan += $p->uang_makan;
						$total_ritasi += $p->ritasi;
						$total_lembur += $p->lembur;
						$total_perjalanan_dinas += $p->perjalanan_dinas;
						$total_kode_akun += $p->kode_akun;
						$total_bonus += $p->bonus;
						$total_thr += $p->thr;
						$total_bpjs_jkk_perusahaan += $p->bpjs_jkk_perusahaan;
						$total_bpjs_jkm_perusahaan += $p->bpjs_jkm_perusahaan;
						$total_bpjs_jht_perusahaan += $p->bpjs_jht_perusahaan;
						$total_bpjs_kes_perusahaan += $p->bpjs_kes_perusahaan;
						$total_iuran_pensiun_perusahaan += $p->iuran_pensiun_perusahaan;
						$total_bpjs_jht_pekerja += $p->bpjs_jht_pekerja;
						$total_bpjs_jkk_pekerja += $p->bpjs_jkk_pekerja;
						$total_bpjs_jkm_pekerja += $p->bpjs_jkm_pekerja;
						$total_bpjs_kes_pekerja += $p->bpjs_kes_pekerja;
						$total_iuran_pensiun_pekerja += $p->iuran_pensiun_pekerja;
						$total_hutang += $p->hutang;
						$total_pot_tidak_masuk += ($p->pot_tidak_kerja+$p->pot_tidak_masuk);
						$total_nominal_denda += $p->nominal_denda;
						$total_yg_diterima += $p->yg_diterima;
						$total_bruto_setahun += $p->bruto_setahun;
						$total_biaya_jabatan += $p->biaya_jabatan;
						$total_netto_setahun += $p->netto_setahun;
						$total_ptkp += $p->ptkp;
						$total_pkp += $p->pkp;
						$total_pph_setahun += $p->pph_setahun;
						$total_pajak_setahun += $p->pajak_setahun;
						$total_bruto_sebulan += $p->bruto_sebulan;
						$total_netto_sebulan += $p->netto_sebulan;
						$total_pph_sebulan += $p->pph_sebulan;
						$total_ptkp_sebulan += ($p->ptkp/12);
						$total_pkp_sebulan += ($p->pkp/12);
						// $total_jumlahxx += ($p->gaji_pokok-$p->thr);
						$total_jumlahxx += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
						$body_1 = [
							($row-4),
							$p->nama_bagian,
							$p->nama_loker,
							ceil($gaji_pokok),
						];
						$dTunjangan=[];
						$valTunjangan = $this->otherfunctions->getDataExplode($p->tunjangan_val,';','all');
						$masterIndukTunjangan = $this->model_master->getIndukTunjanganWhere(null,'a.sifat','DESC');
						foreach ($masterIndukTunjangan as $key_it) {
							$dtun='';
							if(!empty($valTunjangan)){
								foreach ($valTunjangan as $key_tun => $val_tun) {
									$kode_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','start');
									$nominal_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','end');
									$induk=$this->model_master->getListTunjangan(['a.kode_tunjangan'=>$kode_tunjangan],1,true);
									if($key_it->kode_induk_tunjangan == $induk['kode_induk_tunjangan']){
										$dtun.=$nominal_tunjangan;
									}
								}
							}
							$dTunjangan[]=$dtun;
						}
						$tt[]=$dTunjangan;
						$tunjangan +=$p->tunjangan;
						$uang_makan +=$p->uang_makan;
						$ritasi +=$p->ritasi;
						$lembur +=$p->lembur;
						$perjalanan_dinas +=$p->perjalanan_dinas;
						$kode_akun +=$p->kode_akun;
						$bonus+=$p->bonus;
						$thr+=$p->thr;
						// $jumlahxx += ($p->gaji_pokok-$p->thr);
						$jumlahxx += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
						$pengurang_lainx=0;
						$penambah_lainx=0;
						$pengurang_lainx_hallo=0;
						$penambah_lainx_hallo=0;
						if(!empty($p->data_lain)){
							if (strpos($p->data_lain, ';') !== false) {
								$dLain = $this->otherfunctions->getDataExplode($p->data_lain,';','all');
								$dHallo = $this->otherfunctions->getDataExplode($p->data_lain_hallo,';','all');
								$nLain = $this->otherfunctions->getDataExplode($p->nominal_lain,';','all');
								foreach ($dLain as $key => $value) {
									if($value == 'pengurang'){
										if($dHallo[$key] == '1'){
											$pengurang_lainx_hallo += $nLain[$key];
										}else{
											$pengurang_lainx += $nLain[$key];
										}
									}else{
										if($dHallo[$key] == '1'){
											$penambah_lainx_hallo += $nLain[$key];
										}else{
											$penambah_lainx += $nLain[$key];
										}
									}
								}
							}else{
								if($p->data_lain == 'pengurang'){
									if($p->data_lain_hallo == '1'){
										$pengurang_lainx_hallo += $p->nominal_lain;
									}else{
										$pengurang_lainx += $p->nominal_lain;
									}
								}else{
									if($p->data_lain_hallo == '1'){
										$penambah_lainx_hallo += $p->nominal_lain;
									}else{
										$penambah_lainx += $p->nominal_lain;
									}
								}
							}
						}
						$pengurang_lain = $pengurang_lainx;
						$penambah_lain = $penambah_lainx;
						$pengurang_hallo = $pengurang_lainx_hallo;
						$penambah_hallo = $penambah_lainx_hallo;
						$total_pengurang_lain += $pengurang_lainx;
						$total_penambah_lain += $penambah_lainx;
						$total_pengurang_hallo += $pengurang_lainx_hallo;
						$total_penambah_hallo += $penambah_lainx_hallo;
						$bpjs_jkk_perusahaan +=$p->bpjs_jkk_perusahaan;
						$bpjs_jkm_perusahaan +=$p->bpjs_jkm_perusahaan;
						$bpjs_jht_perusahaan +=$p->bpjs_jht_perusahaan;
						$bpjs_kes_perusahaan +=$p->bpjs_kes_perusahaan;
						$iuran_pensiun_perusahaan +=$p->iuran_pensiun_perusahaan;
						$bpjs_jht_pekerja +=$p->bpjs_jht_pekerja;
						$bpjs_jkk_pekerja +=$p->bpjs_jkk_pekerja;
						$bpjs_jkm_pekerja +=$p->bpjs_jkm_pekerja;
						$bpjs_kes_pekerja +=$p->bpjs_kes_pekerja;
						$iuran_pensiun_pekerja +=$p->iuran_pensiun_pekerja;
						$hutang +=$p->hutang;
						$pot_tidak_masuk +=($p->pot_tidak_kerja+$p->pot_tidak_masuk);
						$nominal_denda +=$p->nominal_denda;
						$yg_diterima +=$p->yg_diterima;
						$no_npwp =$p->no_npwp;
						$bruto_sebulan +=$p->bruto_sebulan;
						$bruto_setahun +=$p->bruto_setahun;
						$biaya_jabatan +=$p->biaya_jabatan;
						$netto_sebulan +=$p->netto_sebulan;
						$netto_setahun +=$p->netto_setahun;
						$ptkp +=$p->ptkp;
						$pkp +=$p->pkp;
						$pph_sebulan +=$p->pph_sebulan;
						$pph_setahun +=$p->pph_setahun;
						$pajak_setahun +=$p->pajak_setahun;
						$tunjanganx = [];
						foreach ($tt as $k_y => $v_e) {
							foreach ($v_e as $k_id => $v_id) {
								$v_id = ($v_id != null)?$v_id:0;
								if (isset($tunjanganx[$k_id])){
									$tunjanganx[$k_id] += $v_id;
								}else{
									$tunjanganx[$k_id] = $v_id;
								}						
							}
						}
						$body_3 = [
							ceil($tunjangan),
							ceil($uang_makan),
							ceil($ritasi),
							ceil($lembur),
							ceil($perjalanan_dinas),
							ceil($kode_akun),
							ceil($bonus),
							ceil($thr),
							ceil($jumlahxx),
							ceil($bpjs_jkk_perusahaan),
							ceil($bpjs_jkm_perusahaan),
							null,
							ceil($bpjs_jht_perusahaan),
							ceil($bpjs_kes_perusahaan),
							ceil($iuran_pensiun_perusahaan),
							null,
							ceil($penambah_lain),
							ceil($bpjs_jht_pekerja),
							ceil($bpjs_jkk_pekerja),
							ceil($bpjs_jkm_pekerja),
							ceil($bpjs_kes_pekerja),
							ceil($iuran_pensiun_pekerja),
							ceil($hutang),
							ceil($pot_tidak_masuk),
							ceil($pengurang_hallo),
							ceil($nominal_denda),
							ceil($pengurang_lain),
							ceil($yg_diterima),
							ceil($bruto_sebulan),
							ceil($bruto_setahun),
							ceil($biaya_jabatan),
							ceil($netto_sebulan),
							ceil($netto_setahun),
							ceil(($ptkp/12)),
							ceil($ptkp),
							ceil(($pkp/12)),
							ceil($pkp),
							ceil($pph_sebulan),
							ceil($pph_setahun),
							ceil($pajak_setahun),
							null,
							null,
							null,
							$no_npwp,
						];
					// $tunjangan_t[$p->kode_bagian] = $tunjanganx;
					$data_bodyx[$p->kode_bagian] = array_merge($body_1,$body_3);
					$row++;
					}
				}
			}
			$xx=3;
			foreach ($data_bodyx as $dx) {
				$body[$xx]=$dx;
				$xx++;

			}
			$data_awal_null = [null,'TOTAL',null,ceil($total_gaji)];
			$data_total = [
				ceil($total_tunjangan),
				ceil($total_uang_makan),
				ceil($total_ritasi),
				ceil($total_lembur),
				ceil($total_perjalanan_dinas),
				ceil($total_kode_akun),
				ceil($total_bonus),
				ceil($total_thr),
				ceil($total_jumlahxx),
				ceil($total_bpjs_jkk_perusahaan),
				ceil($total_bpjs_jkm_perusahaan),
				null, 
				ceil($total_bpjs_jht_perusahaan),
				ceil($total_bpjs_kes_perusahaan),
				ceil($total_iuran_pensiun_perusahaan),
				null, 
				ceil($total_penambah_lain),
				ceil($total_bpjs_jht_pekerja),
				ceil($total_bpjs_jkk_pekerja),
				ceil($total_bpjs_jkm_pekerja),
				ceil($total_bpjs_kes_pekerja),
				ceil($total_iuran_pensiun_pekerja),
				ceil($total_hutang),
				ceil($total_pot_tidak_masuk),
				ceil($total_pengurang_hallo),
				ceil($total_nominal_denda),
				ceil($total_pengurang_lain),
				ceil($total_yg_diterima),
				ceil($total_bruto_sebulan),
				ceil($total_bruto_setahun),
				ceil($total_biaya_jabatan),
				ceil($total_netto_sebulan),
				ceil($total_netto_setahun),
				ceil($total_ptkp_sebulan),
				ceil($total_ptkp),
				ceil($total_pkp_sebulan),
				ceil($total_pkp),
				ceil($total_pph_sebulan),
				ceil($total_pph_setahun),
				ceil($total_pajak_setahun),
			];
			$body[count($data_bodyx)+4]=array_merge($data_awal_null,$data_total);
			$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
			$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);			
			$sheet[($bulanx-1)]=[
				'range_huruf'=>3,
				'sheet_title'=>$this->formatter->getNameOfMonth($bulan).' '.$tahun,
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
				'head_merge'=>[
					'row_head'=>1,
					'row_head_2'=>2,
					'data_head_1'=>$data_head_1,
					'data_head_2'=>$data_head,
					'max_merge'=>40,
					'merge_1'=>'A1:A2',
					'merge_2'=>'B1:B2',
					'merge_3'=>'C1:C2',
					'merge_4'=>'D1:D2',
					'merge_5'=>'E1:E2',
					'merge_6'=>'F1:F2',
					'merge_7'=>'G1:G2',
					'merge_8'=>'H1:H2',
					'merge_9'=>'I1:I2',
					'merge_10'=>'J1:J2',
					'merge_11'=>'K1:K2',
					'merge_12'=>'L1:L2',
					'merge_13'=>'M1:M2',
					'merge_14'=>'N1:U1',
					'merge_15'=>'V1:AD1',
					'merge_16'=>'AE1:AE2',
					'merge_17'=>'AF1:AF2',
					'merge_18'=>'AG1:AG2',
					'merge_19'=>'AH1:AH2',
					'merge_20'=>'AI1:AI2',
					'merge_21'=>'AB1:AK1',
					'merge_22'=>'AL1:AL2',
					'merge_23'=>'AM1:AM2',
					'merge_24'=>'AN1:AN2',
					'merge_25'=>'AO1:AO2',
					'merge_26'=>'AP1:AP2',
					'merge_27'=>'AQ1:AQ2',
					'merge_28'=>'AR1:AR2',
					'merge_29'=>'AS1:AS2',
					'merge_30'=>'AT1:AT2',
					'merge_31'=>'AU1:AU2',
					'merge_32'=>'AV1:AV2',
					'merge_33'=>'AW1:AW2',
					'merge_34'=>'AX1:AX2',
					'merge_35'=>'AY1:AY2',
					'merge_36'=>'AZ1:AZ2',
					'merge_37'=>'BA1:BA2',
					'merge_38'=>'BB1:BB2',
					'merge_39'=>'BC1:BC2',
					'merge_40'=>'BD1:BD2',
				]
			];
		}
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_bagian_tahunan()
	{
		$dataHeadFirst = [
			'No',
			'Bagian',
			'Lokasi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','PPh','JHT','Kesehatan (BPJS)','Jaminan Pensiun','Premi Asuransi','Lainnya'
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'JHT','JKK','JKM','Kesehatan (BPJS)','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Setahun',
			'Biaya Jabatan',
			'Penghasilan Netto Setahun',
			'PTKP Setahun',
			'PKP Setahun',
			'PPH 21 Setahun',
			'Pajak Setahun',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'NPWP',
		];
		$tahun = $this->input->post('tahun');
		$bagian = $this->input->post('bagian');
		$all_bag = $this->input->post('all_bag');
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Periode '.$tahun,
			'subject'=>'Rekap Data PPH-21',
			'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=3;
		$row=$row_body;
		$datax=[];
		if($all_bag == 1){
			$dBagian = $this->model_master->getListBagian(true);
			foreach ($dBagian as $bg) {
				$datax[] = $this->model_payroll->getListDataPenggajianPph(['a.kode_bagian'=>$bg->kode_bagian,'a.tahun'=>$tahun],null,null,null,'max');
			}
		}else{
			foreach ($bagian as $bag) {
				$datax[] = $this->model_payroll->getListDataPenggajianPph(['a.kode_bagian'=>$bag,'a.tahun'=>$tahun],null,null,null,'max');
			}
		}
		$data_bodyx = [];
		if(!empty($datax)){
			$total_gaji = 0;
			$total_tunjangan = 0;
			$total_uang_makan = 0;
			$total_ritasi = 0;
			$total_lembur = 0;
			$total_perjalanan_dinas = 0;
			$total_kode_akun = 0;
			$total_bonus = 0;
			$total_thr = 0;
			$total_jumlahxx = 0;
			$total_bpjs_jkk_perusahaan = 0;
			$total_bpjs_jkm_perusahaan = 0;
			$total_bpjs_jht_perusahaan = 0;
			$total_bpjs_kes_perusahaan = 0;
			$total_iuran_pensiun_perusahaan = 0;
			$total_pengurang_lain = 0;
			$total_penambah_lain = 0;
			$total_pengurang_hallo = 0;
			$total_penambah_hallo = 0;
			$total_bpjs_jht_pekerja = 0;
			$total_bpjs_jkk_pekerja = 0;
			$total_bpjs_jkm_pekerja = 0;
			$total_bpjs_kes_pekerja = 0;
			$total_iuran_pensiun_pekerja = 0;
			$total_hutang = 0;
			$total_pot_tidak_masuk = 0;
			$total_nominal_denda = 0;
			$total_yg_diterima = 0;
			$total_bruto_setahun = 0;
			$total_biaya_jabatan = 0;
			$total_netto_setahun = 0;
			$total_ptkp = 0;
			$total_pkp = 0;
			$total_pph_setahun = 0;
			$total_pajak_setahun = 0;
			$tunjangan_t = [];
			foreach ($datax as $key => $bagianx) {
				$gaji_pokok = 0;
				$tt = [];
				$tunjangan = 0;
				$uang_makan = 0;
				$ritasi = 0;
				$lembur = 0;
				$perjalanan_dinas = 0;
				$kode_akun = 0;
				$bonus = 0;
				$thr = 0;
				$jumlahxx = 0;
				$pengurang_lain = 0;
				$penambah_lain = 0;
				$pengurang_hallo = 0;
				$penambah_hallo = 0;
				$bpjs_jkk_perusahaan = 0;
				$bpjs_jkm_perusahaan = 0;
				$bpjs_jht_perusahaan = 0;
				$bpjs_kes_perusahaan = 0;
				$iuran_pensiun_perusahaan = 0;
				$bpjs_jht_pekerja = 0;
				$bpjs_jkk_pekerja = 0;
				$bpjs_jkm_pekerja = 0;
				$bpjs_kes_pekerja = 0;
				$iuran_pensiun_pekerja = 0;
				$hutang = 0;
				$pot_tidak_masuk = 0;
				$nominal_denda = 0;
				$yg_diterima = 0;
				$bruto_setahun = 0;
				$biaya_jabatan = 0;
				$netto_setahun = 0;
				$ptkp = 0;
				$pkp = 0;
				$pph_setahun = 0;
				$pajak_setahun = 0;
				$no_npwp = '';
				foreach ($bagianx as $p) {
					$gaji_pokok += $p->gaji_pokok;
					$total_gaji += $p->gaji_pokok;
					$total_tunjangan += $p->tunjangan;
					$total_uang_makan += $p->uang_makan;
					$total_ritasi += $p->ritasi;
					$total_lembur += $p->lembur;
					$total_perjalanan_dinas += $p->perjalanan_dinas;
					$total_kode_akun += $p->kode_akun;
					$total_bonus += $p->bonus;
					$total_thr += $p->thr;
					// $total_jumlahxx += ($p->gaji_pokok-$p->thr);
					$total_jumlahxx += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
					$total_bpjs_jkk_perusahaan += $p->bpjs_jkk_perusahaan;
					$total_bpjs_jkm_perusahaan += $p->bpjs_jkm_perusahaan;
					$total_bpjs_jht_perusahaan += $p->bpjs_jht_perusahaan;
					$total_bpjs_kes_perusahaan += $p->bpjs_kes_perusahaan;
					$total_iuran_pensiun_perusahaan += $p->iuran_pensiun_perusahaan;
					$total_bpjs_jht_pekerja += $p->bpjs_jht_pekerja;
					$total_bpjs_jkk_pekerja += $p->bpjs_jkk_pekerja;
					$total_bpjs_jkm_pekerja += $p->bpjs_jkm_pekerja;
					$total_bpjs_kes_pekerja += $p->bpjs_kes_pekerja;
					$total_iuran_pensiun_pekerja += $p->iuran_pensiun_pekerja;
					$total_hutang += $p->hutang;
					$total_pot_tidak_masuk += ($p->pot_tidak_kerja+$p->pot_tidak_masuk);
					$total_nominal_denda += $p->nominal_denda;
					$total_yg_diterima += $p->yg_diterima;
					$total_bruto_setahun += $p->bruto_setahun;
					$total_biaya_jabatan += $p->biaya_jabatan;
					$total_netto_setahun += $p->netto_setahun;
					$total_ptkp += $p->ptkp;
					$total_pkp += $p->pkp;
					$total_pph_setahun += $p->pph_setahun;
					$total_pajak_setahun += $p->pajak_setahun;
					$body_1 = [
						($row-6),
						$p->nama_bagian,
						$p->nama_loker,
						ceil($gaji_pokok),
					];
					$tunjangan +=$p->tunjangan;
					$uang_makan +=$p->uang_makan;
					$ritasi +=$p->ritasi;
					$lembur +=$p->lembur;
					$perjalanan_dinas +=$p->perjalanan_dinas;
					$kode_akun +=$p->kode_akun;
					$bonus +=$p->bonus;
					$thr +=$p->thr;
					// $jumlahxx += ($p->gaji_pokok-$p->thr);
					$jumlahxx += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
					$pengurang_lainx=0;
					$penambah_lainx=0;
					$pengurang_lainx_hallo=0;
					$penambah_lainx_hallo=0;
					if(!empty($p->data_lain)){
						if (strpos($p->data_lain, ';') !== false) {
							$dLain = $this->otherfunctions->getDataExplode($p->data_lain,';','all');
							$dHallo = $this->otherfunctions->getDataExplode($p->data_lain_hallo,';','all');
							$nLain = $this->otherfunctions->getDataExplode($p->nominal_lain,';','all');
							foreach ($dLain as $key => $value) {
								if($value == 'pengurang'){
									if($dHallo[$key] == '1'){
										$pengurang_lainx_hallo += $nLain[$key];
									}else{
										$pengurang_lainx += $nLain[$key];
									}
								}else{
									if($dHallo[$key] == '1'){
										$penambah_lainx_hallo += $nLain[$key];
									}else{
										$penambah_lainx += $nLain[$key];
									}
								}
							}
						}else{
							if($p->data_lain == 'pengurang'){
								if($p->data_lain_hallo == '1'){
									$pengurang_lainx_hallo += $p->nominal_lain;
								}else{
									$pengurang_lainx += $p->nominal_lain;
								}
							}else{
								if($p->data_lain_hallo == '1'){
									$penambah_lainx_hallo += $p->nominal_lain;
								}else{
									$penambah_lainx += $p->nominal_lain;
								}
							}
						}
					}
					$pengurang_lain = $pengurang_lainx;
					$penambah_lain = $penambah_lainx;
					$pengurang_hallo = $pengurang_lainx_hallo;
					$penambah_hallo = $penambah_lainx_hallo;
					$total_pengurang_lain += $pengurang_lainx;
					$total_penambah_lain += $penambah_lainx;
					$total_pengurang_hallo += $pengurang_lainx_hallo;
					$total_penambah_hallo += $penambah_lainx_hallo;
					$bpjs_jkk_perusahaan +=$p->bpjs_jkk_perusahaan;
					$bpjs_jkm_perusahaan +=$p->bpjs_jkm_perusahaan;
					$bpjs_jht_perusahaan +=$p->bpjs_jht_perusahaan;
					$bpjs_kes_perusahaan +=$p->bpjs_kes_perusahaan;
					$iuran_pensiun_perusahaan +=$p->iuran_pensiun_perusahaan;
					$bpjs_jht_pekerja +=$p->bpjs_jht_pekerja;
					$bpjs_jkk_pekerja +=$p->bpjs_jkk_pekerja;
					$bpjs_jkm_pekerja +=$p->bpjs_jkm_pekerja;
					$bpjs_kes_pekerja +=$p->bpjs_kes_pekerja;
					$iuran_pensiun_pekerja +=$p->iuran_pensiun_pekerja;
					$hutang +=$p->hutang;
					$pot_tidak_masuk +=($p->pot_tidak_kerja+$p->pot_tidak_masuk);
					$nominal_denda +=$p->nominal_denda;
					$yg_diterima +=$p->yg_diterima;
					$bruto_setahun +=$p->bruto_setahun;
					$biaya_jabatan +=$p->biaya_jabatan;
					$netto_setahun +=$p->netto_setahun;
					$ptkp =$p->ptkp;
					$pkp +=$p->pkp;
					$pph_setahun +=$p->pph_setahun;
					$pajak_setahun +=$p->pajak_setahun;
					$no_npwp =$p->no_npwp;
					$body_3 = [
						ceil($tunjangan),
						ceil($uang_makan),
						ceil($ritasi),
						ceil($lembur),
						ceil($perjalanan_dinas),
						ceil($kode_akun),
						ceil($bonus),
						ceil($thr),
						ceil($jumlahxx),
						ceil($bpjs_jkk_perusahaan),
						ceil($bpjs_jkm_perusahaan),
						null,
						ceil($bpjs_jht_perusahaan),
						ceil($bpjs_kes_perusahaan),
						ceil($iuran_pensiun_perusahaan),
						null,
						ceil($penambah_lain),
						ceil($bpjs_jht_pekerja),
						ceil($bpjs_jkk_pekerja),
						ceil($bpjs_jkm_pekerja),
						ceil($bpjs_kes_pekerja),
						ceil($iuran_pensiun_pekerja),
						ceil($hutang),
						ceil($pot_tidak_masuk),
						ceil($pengurang_hallo),
						ceil($nominal_denda),
						ceil($pengurang_lain),
						ceil($yg_diterima),
						ceil($bruto_setahun),
						ceil($biaya_jabatan),
						ceil($netto_setahun),
						ceil($ptkp),
						ceil($pkp),
						ceil($pph_setahun),
						ceil($pajak_setahun),
						null,
						null,
						null,
						$no_npwp,
					];
				$data_bodyx[$p->kode_bagian] = array_merge($body_1,$body_3);
				$row++;
				}
			}
		}
		$xx=3;
		foreach ($data_bodyx as $dx) {
			$body[$xx]=$dx;
			$xx++;
		}
		$data_awal_null = [null,'TOTAL',null,ceil($total_gaji)];
		$data_total = [
			ceil($total_tunjangan),
			ceil($total_uang_makan),
			ceil($total_ritasi),
			ceil($total_lembur),
			ceil($total_perjalanan_dinas),
			ceil($total_kode_akun),
			ceil($total_bonus),
			ceil($total_thr),
			ceil($total_jumlahxx),
			ceil($total_bpjs_jkk_perusahaan),
			ceil($total_bpjs_jkm_perusahaan),
			null,
			ceil($total_bpjs_jht_perusahaan),
			ceil($total_bpjs_kes_perusahaan),
			ceil($total_iuran_pensiun_perusahaan),
			null,
			ceil($total_penambah_lain),
			ceil($total_bpjs_jht_pekerja),
			ceil($total_bpjs_jkk_pekerja),
			ceil($total_bpjs_jkm_pekerja),
			ceil($total_bpjs_kes_pekerja),
			ceil($total_iuran_pensiun_pekerja),
			ceil($total_hutang),
			ceil($total_pot_tidak_masuk),
			ceil($total_pengurang_hallo),
			ceil($total_nominal_denda),
			ceil($total_pengurang_lain),
			ceil($total_yg_diterima),
			ceil($total_bruto_setahun),
			ceil($total_biaya_jabatan),
			ceil($total_netto_setahun),
			ceil($total_ptkp),
			ceil($total_pkp),
			ceil($total_pph_setahun),
			ceil($total_pajak_setahun),
		];
		$body[count($data_bodyx)+4]=array_merge($data_awal_null,$data_total);
		$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
		$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);
		$sheet[0]=[
				'range_huruf'=>3,
				'sheet_title'=>'Data PPh 21 Tahun '.$tahun,
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
				'head_merge'=>[
					'row_head'=>1,
					'row_head_2'=>2,
					'data_head_1'=>$data_head_1,
					'data_head_2'=>$data_head,
					'max_merge'=>40,
					'merge_1'=>'A1:A2',
					'merge_2'=>'B1:B2',
					'merge_3'=>'C1:C2',
					'merge_4'=>'D1:D2',
					'merge_5'=>'E1:E2',
					'merge_6'=>'F1:F2',
					'merge_7'=>'G1:G2',
					'merge_8'=>'H1:H2',
					'merge_9'=>'I1:I2',
					'merge_10'=>'J1:J2',
					'merge_11'=>'K1:K2',
					'merge_12'=>'L1:L2',
					'merge_13'=>'M1:M2',
					'merge_14'=>'N1:U1',
					'merge_15'=>'V1:AD1',
					'merge_16'=>'AE1:AE2',
					'merge_17'=>'AF1:AF2',
					'merge_18'=>'AG1:AG2',
					'merge_19'=>'AH1:AH2',
					'merge_20'=>'AI1:AI2',
					'merge_21'=>'AB1:AK1',
					// 'merge_22'=>'AK1:AK2',
					'merge_22'=>'AL1:AL2',
					'merge_23'=>'AM1:AM2',
					'merge_24'=>'AN1:AN2',
					'merge_25'=>'AO1:AO2',
					'merge_26'=>'AP1:AP2',
					'merge_27'=>'AQ1:AQ2',
					'merge_28'=>'AR1:AR2',
					'merge_29'=>'AS1:AS2',
					'merge_30'=>'AT1:AT2',
					'merge_31'=>'AU1:AU2',
					'merge_32'=>'AV1:AV2',
					'merge_33'=>'AW1:AW2',
					'merge_34'=>'AX1:AX2',
					'merge_35'=>'AY1:AY2',
					'merge_36'=>'AZ1:AZ2',
					'merge_37'=>'BA1:BA2',
					'merge_38'=>'BB1:BB2',
					'merge_39'=>'BC1:BC2',
					'merge_40'=>'BD1:BD2',
				],
			];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_bp_final_karyawan()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$data['properties']=[
			'title'=>"Rekap BP FINAL Karyawan",
			'subject'=>"Rekap BP FINAL Karyawan",
			'description'=>"Rekap BP FINAL Karyawan",
			'keywords'=>"Rekap BP FINAL Karyawan, BP FINAL Karyawan",
			'category'=>"Rekap",
		];
		$body=[];
		$datax = $this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]);
		$row_body=2;
		$row=$row_body;
		foreach ($datax as $d) {
			if($d->pph_sebulan > 0){
				$body[$row]=[
					$d->bulan,
					$d->tahun,
					$d->koreksi,
					$d->no_npwp,
					$d->nama_karyawan,
					null,
					ceil($d->bruto_sebulan),
					ceil($d->pph_sebulan),
					null,
				];
				$row++;
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'BP FINAL Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['Masa Pajak', 'Tahun Pajak', 'Pembetulan', 'NPWP', 'Nama', 'Kode Pajak', 'Jumlah Bruto', 'Jumlah PPh', 'Kode Negara',],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_bp_final_pesangon()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$data['properties']=[
			'title'=>"Rekap BP FINAL Pesangon Karyawan",
			'subject'=>"Rekap BP FINAL Pesangon Karyawan",
			'description'=>"Rekap BP FINAL Pesangon Karyawan",
			'keywords'=>"Rekap BP FINAL Pesangon Karyawan, BP FINAL Pesangon Karyawan",
			'category'=>"Rekap",
		];
		$body=[];
		$datax = $this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]);
		$row_body=2;
		$row=$row_body;
		foreach ($datax as $d) {
			if($d->pesangon > 0){
				$alamat = $this->model_karyawan->getEmployeeNik($d->nik)['alamat_asal_jalan'];
				$body[$row]=[
					$d->bulan,
					$d->tahun,
					$d->koreksi,
					null,
					$d->no_npwp,
					$d->nama_karyawan,
					$alamat,
					null,
					ceil($d->pesangon),
					null,
					ceil($d->pph_pesangon),
					null,
					null,
					null,
				];
				$row++;
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'BP FINAL Pesangon Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['Masa Pajak', 'Tahun Pajak', 'Pembetulan', 'Nomor Bukti Potong', 'NPWP', 'Nama', 'Alamat', 'Kode Pajak', 'Jumlah Bruto', 'Tarif', 'Jumlah PPh', 'NPWP Pemotong', 'Nama Pemotong', 'Tanggal Bukti Potong'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		// echo '<pre>';
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
		// print_r($data);
	}
	public function rekap_data_1721_bp_a1()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$data['properties']=[
			'title'=>"Rekap Bukti Potong 1721-A1",
			'subject'=>"Rekap Bukti Potong 1721-A1",
			'description'=>"Rekap Bukti Potong 1721-A1",
			'keywords'=>"Rekap Bukti Potong 1721-A1, Bukti Potong 1721-A1",
			'category'=>"Rekap",
		];
		$body=[];
		// $datax = $this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]);
		$row_body=2;
		$row=$row_body;
		$getData = [];
		$datapph = $this->model_payroll->getPPH1721A1(['a.tahun'=>$tahun],null,null,null,'max');
		foreach ($datapph as $d) {
			$getData[$d->nik]=$this->model_payroll->getPPH1721A1(['a.tahun'=>$tahun,'a.nik'=>$d->nik],null,null,null,'max');
		}
		foreach ($getData as $nik => $datapx) {
			$gaji_pokok = 0;
			$tunjangan_lainnya = 0;
			$premi_asuransi = 0;
			$bonusthr = 0;
			$bruto_sebulan = 0;
			$netto_sebulan = 0;
			$biaya_jabatan = 0;
			$potongan = 0;
			$pkp = 0;
			$pph = 0;
			foreach ($datapx as $d) {
				$gaji_pokok += $d->gaji_pokok;
				$pengurang_lain = 0;
				$penambah_lain = 0;
				if(!empty($d->data_lain)){
					$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
					$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
					foreach ($dLain as $key => $value) {
						if($value == 'pengurang'){
							$pengurang_lain += $nLain[$key];
						}else{
							$penambah_lain += $nLain[$key];
						}
					}
				}
				$tunjangan_lainnya +=($d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$penambah_lain);
				$premi_asuransi +=($d->bpjs_jkk_perusahaan+$d->bpjs_jkm_perusahaan+$d->bpjs_kes_perusahaan+$d->premi_asuransi);
				$bonusthr +=($d->bonus+$d->thr);
				$bruto_sebulan += $d->bruto_sebulan;
				$biaya_jabatan += $d->biaya_jabatan;
				// $potongan += ($d->bpjs_jht_pekerja+$d->bpjs_jkk_pekerja+$d->bpjs_jkm_pekerja+$d->bpjs_kes_pekerja+$d->iuran_pensiun_pekerja+$d->hutang+$d->pot_tidak_masuk+$d->nominal_denda+$pengurang_lain);
				// $netto_sebulan += $d->netto_sebulan;
				$potongan += ($d->bpjs_jht_pekerja+$d->iuran_pensiun_pekerja);
				$netto_sebulan += $d->bruto_sebulan-($d->biaya_jabatan+$d->bpjs_jht_pekerja+$d->iuran_pensiun_pekerja);
				$ptkp = $d->ptkp;
				$pkp += ($d->pkp/12);
				$pph += $d->pph_sebulan;
			}
			$kar = $this->model_karyawan->getEmployeeNik($nik);
			if(!empty($kar)){
				$periodePPH = $this->payroll->getBulanMasukKeryawan($kar['tgl_masuk'],$nik);
				$pos = strpos($kar['status_pajak'], '/');
				if ($pos == true) {
					$stt_ptkp = $this->otherfunctions->getDataExplode($kar['status_pajak'],'/','start');
					$val_ptkp = $this->otherfunctions->getDataExplode($kar['status_pajak'],'/','end');
				}else{
					$stt_ptkp = null;
					$val_ptkp = null;
				}
				$body[$row]=[
					$bulan,
					$tahun,
					null,
					null,
					$periodePPH['awal'],
					$periodePPH['akhir'],
					$kar['npwp'],
					$nik,
					$kar['nama'],
					$kar['alamat_asal_jalan'],
					($kar['kelamin']=='l')?'M':'F',
					$stt_ptkp,
					$val_ptkp,
					$kar['nama_jabatan'],
					'N',
					null,
					null,
					ceil($gaji_pokok),
					null,
					ceil($tunjangan_lainnya),
					null,
					ceil($premi_asuransi),
					null,
					ceil($bonusthr),
					ceil($bruto_sebulan),
					ceil($biaya_jabatan),
					ceil($potongan),
					ceil($biaya_jabatan+$potongan),
					ceil($netto_sebulan),
					0,
					ceil($netto_sebulan),
					ceil($ptkp),
					ceil($pkp),
					null,
					null,
					ceil($pph),
					null,
					null,
					'012528840513001',
					'CV.Jati Kencana',
					null,
				];
				$row++;
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Bukti Potong 1721-A1',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'Masa Pajak', 'Tahun Pajak', 'Pembetulan', 'Nomor Bukti Potong', 'Masa Perolehan Awal', 'Masa Perolehan Akhir', 'NPWP', 'NIK', 'Nama', 'Alamat', 'Jenis Kelamin', 'Status PTKP', 'Jumlah Tanggungan', 'Nama Jabatan', 'WP Luar Negeri', 'Kode Negara', 'Kode Pajak', 'Gaji Pokok', 'Tunjangan PPh', 'Tunjangan Lainnya, Uang Lembur & Sebagiannya', 'Honorarium & Imbalan Lain Sejenisnya', 'Premi Asuransi yg Dibayar pemberi kerja', 'Penerimaan dalam bentuk Natura atau Kenikmatan yang dikenakan Pemotongan PPh 21', 'Tantiem, Bonus, Gratifikasi, Jasa Produksi & THR', 'Jumlah Penghasilan Bruto', 'Biaya Jabatan', 'Iuran Pensiun atau Iuran JHT', 'Jumlah Pengurangan', 'Jumlah Penghasilan Neto', 'Penghasilan Neto Masa Sebelumnya', 'Jumlah Penghasilan Neto untuk PPh21', 'Penghasilan Tdk Kena Pajak Setahun/Disetahunkan', 'PTKP', 'PPh 21 ats Penghasilan Kena Pajak Setahun', 'PPh 21 yang telah dipotong Masa Sebelumnya', 'PPh 21 Terutang', 'PPh 21 dan PPh 26 yg dipotong/dilunasi', 'Status Pindah', 'NPWP Pemotong', 'Nama Pemotong', 'Tanggal Bukti Potong',
				],

			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		// echo '<pre>';
		$data['data']=$sheet;
		// print_r($data);
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_old()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksi = $this->input->post('koreksi');
		$data_pph_other = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]));
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Periode '.$this->formatter->getNameOfMonth($bulan).' '.$tahun.' Pembetulan '.$this->otherfunctions->getNumberToAbjad($koreksi),
			'subject'=>'Rekap Data PPH-21',
			'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21",
			'category'=>"Rekap",
		];
		$body=[];
		$bodyx=[];
		$row_body=3;
		$row=$row_body;
		$datax = $this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]);
		if(!empty($datax)){
			$gaji_pokok = 0;
			$total_gaji = 0;
			$total_tunjangan = 0;
			$total_uang_makan = 0;
			$total_ritasi = 0;
			$total_lembur = 0;
			$total_perjalanan_dinas = 0;
			$total_kode_akun = 0;
			$total_bonus = 0;
			$total_thr = 0;
			$total_pesangon = 0;
			$total_pph_pesangon = 0;
			$total_premi_asuransi = 0;
			$total_pengurang_lain = 0;
			$total_penambah_lain = 0;
			$total_pengurang_hallo = 0;
			$total_penambah_hallo = 0;
			$total_bpjs_jkk_perusahaan = 0;
			$total_bpjs_jkm_perusahaan = 0;
			$total_bpjs_jht_perusahaan = 0;
			$total_bpjs_kes_perusahaan = 0;
			$total_iuran_pensiun_perusahaan = 0;
			$total_bpjs_jht_pekerja = 0;
			$total_bpjs_jkk_pekerja = 0;
			$total_bpjs_jkm_pekerja = 0;
			$total_bpjs_kes_pekerja = 0;
			$total_iuran_pensiun_pekerja = 0;
			$total_hutang = 0;
			$total_pot_tidak_masuk = 0;
			$total_nominal_denda = 0;
			$total_yg_diterima = 0;
			$total_bruto_setahun = 0;
			$total_biaya_jabatan = 0;
			$total_netto_setahun = 0;
			$total_ptkp = 0;
			$total_pkp = 0;
			$total_pph_setahun = 0;
			$total_pajak_setahun = 0;
			$total_bruto_sebulan = 0;
			$total_netto_sebulan = 0;
			$total_pph_sebulan = 0;
			$total_ptkp_sebulan = 0;
			$total_pkp_sebulan = 0;
			$total_jumlahxx = 0;
			foreach ($datax as $d) {
				$gaji_pokok += $d->gaji_pokok;
				$total_gaji += $d->gaji_pokok;
				$total_tunjangan += $d->tunjangan;
				$total_uang_makan += $d->uang_makan;
				$total_ritasi += $d->ritasi;
				$total_lembur += $d->lembur;
				$total_perjalanan_dinas += $d->perjalanan_dinas;
				$total_kode_akun += $d->kode_akun;
				$total_bonus += $d->bonus;
				$total_thr += $d->thr;
				$total_pesangon += $d->pesangon;
				$total_pph_pesangon += $d->pph_pesangon;
				$total_bpjs_jkk_perusahaan += $d->bpjs_jkk_perusahaan;
				$total_bpjs_jkm_perusahaan += $d->bpjs_jkm_perusahaan;
				$total_bpjs_jht_perusahaan += $d->bpjs_jht_perusahaan;
				$total_bpjs_kes_perusahaan += $d->bpjs_kes_perusahaan;
				$total_iuran_pensiun_perusahaan += $d->iuran_pensiun_perusahaan;
				$total_bpjs_jht_pekerja += $d->bpjs_jht_pekerja;
				$total_bpjs_jkk_pekerja += $d->bpjs_jkk_pekerja;
				$total_bpjs_jkm_pekerja += $d->bpjs_jkm_pekerja;
				$total_bpjs_kes_pekerja += $d->bpjs_kes_pekerja;
				$total_iuran_pensiun_pekerja += $d->iuran_pensiun_pekerja;
				$total_hutang += $d->hutang;
				$total_pot_tidak_masuk += ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
				$total_nominal_denda += $d->nominal_denda;
				$total_yg_diterima += $d->yg_diterima;
				$total_bruto_setahun += $d->bruto_setahun;
				$total_biaya_jabatan += $d->biaya_jabatan;
				$total_netto_setahun += $d->netto_setahun;
				$total_ptkp += $d->ptkp;
				$total_pkp += $d->pkp;
				$total_pph_setahun += $d->pph_setahun;
				$total_pajak_setahun += $d->pajak_setahun;
				$total_bruto_sebulan += $d->bruto_sebulan;
				$total_netto_sebulan += $d->netto_sebulan;
				$total_pph_sebulan += $d->pph_sebulan;
				$total_ptkp_sebulan += ($d->ptkp/12);
				$total_pkp_sebulan += ($d->pkp/12);
				$total_premi_asuransi += $d->premi_asuransi;
				// $total_jumlahxx += ($d->gaji_pokok-$d->thr);
				$total_jumlahxx += ($d->gaji_pokok+$d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$d->bonus+$d->thr);
				$body_1 = [
					($row-2),
					$d->nik,
					$d->nama_karyawan,
					$d->nama_jabatan,
					$d->nama_bagian,
					$d->nama_loker,
					$d->nama_grade,
					// $this->otherfunctions->nonPembulatan($d->gaji_pokok),
					// $this->formatter->getDateMonthFormatUser($d->tgl_masuk),
					// $d->masa_kerja,
					$d->status_ptkp,
					$this->otherfunctions->getNumberToAbjad($d->koreksi),
					ceil($d->gaji_pokok),
				];
				$body_2=[];
				$valTunjangan = $this->otherfunctions->getDataExplode($d->tunjangan_val,';','all');
				$masterIndukTunjangan = $this->model_master->getIndukTunjanganWhere(null,'a.sifat','DESC');
				foreach ($masterIndukTunjangan as $key_it) {
					$dtun='';
					if(!empty($valTunjangan)){
						foreach ($valTunjangan as $key_tun => $val_tun) {
							$kode_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','start');
							$nominal_tunjangan = $this->otherfunctions->getDataExplode($val_tun,':','end');
							$induk=$this->model_master->getListTunjangan(['a.kode_tunjangan'=>$kode_tunjangan],1,true);
							$nominal_t = ceil($nominal_tunjangan);
							if($key_it->kode_induk_tunjangan == $induk['kode_induk_tunjangan']){
								$dtun.=$nominal_t;
							}
						}
					}
					$body_2[]=$dtun;
				}
				// $tt[]=$body_2;
				$body_3 = [
					ceil($d->tunjangan),
					ceil($d->uang_makan),
					ceil($d->ritasi),
					ceil($d->lembur),
					ceil($d->perjalanan_dinas),
					ceil($d->kode_akun),
					ceil($d->bonus),
					ceil($d->thr),
					// ceil($d->gaji_pokok-$d->thr),
					ceil($d->gaji_pokok+$d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$d->bonus+$d->thr),
				];
				$pengurang_lainx=0;
				$penambah_lainx=0;
				$pengurang_lainx_hallo=0;
				$penambah_lainx_hallo=0;
				if(!empty($d->data_lain)){
					if (strpos($d->data_lain, ';') !== false) {
						$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
						$dHallo = $this->otherfunctions->getDataExplode($d->data_lain_hallo,';','all');
						$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
						foreach ($dLain as $key => $value) {
							if($value == 'pengurang'){
								if($dHallo[$key] == '1'){
									$pengurang_lainx_hallo += $nLain[$key];
								}else{
									$pengurang_lainx += $nLain[$key];
								}
							}else{
								if($dHallo[$key] == '1'){
									$penambah_lainx_hallo += $nLain[$key];
								}else{
									$penambah_lainx += $nLain[$key];
								}
							}
						}
					}else{
						if($d->data_lain == 'pengurang'){
							if($d->data_lain_hallo == '1'){
								$pengurang_lainx_hallo += $d->nominal_lain;
							}else{
								$pengurang_lainx += $d->nominal_lain;
							}
						}else{
							if($d->data_lain_hallo == '1'){
								$penambah_lainx_hallo += $d->nominal_lain;
							}else{
								$penambah_lainx += $d->nominal_lain;
							}
						}
					}
				}
				$pengurang_lain = $pengurang_lainx;
				$penambah_lain = $penambah_lainx;
				$pengurang_hallo = $pengurang_lainx_hallo;
				$penambah_hallo = $penambah_lainx_hallo;
				$total_pengurang_lain += $pengurang_lainx;
				$total_penambah_lain += $penambah_lainx;
				$total_pengurang_hallo += $pengurang_lainx_hallo;
				$total_penambah_hallo += $penambah_lainx_hallo;
				$body_4 = [
					ceil($d->bpjs_jkk_perusahaan),
					ceil($d->bpjs_jkm_perusahaan),
					// 'PPh',
					null,
					ceil($d->bpjs_jht_perusahaan),
					ceil($d->bpjs_kes_perusahaan),
					ceil($d->iuran_pensiun_perusahaan),
					ceil($d->premi_asuransi),
					ceil($penambah_lain)
				];
				$potonganTidakKerja = ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
				$body_5 = [
					ceil($d->bpjs_jht_pekerja),
					ceil($d->bpjs_jkk_pekerja),
					ceil($d->bpjs_jkm_pekerja),
					ceil($d->bpjs_kes_pekerja),
					ceil($d->iuran_pensiun_pekerja),
					ceil($d->hutang),
					ceil($potonganTidakKerja),
					ceil($pengurang_hallo),
					ceil($d->nominal_denda),
					ceil($pengurang_lain),
				];
				$body_6 = [
					ceil($d->yg_diterima),
					ceil($d->bruto_sebulan),
					ceil($d->bruto_setahun),
					ceil($d->biaya_jabatan),
					ceil($d->pesangon),
					ceil($d->pph_pesangon),
					ceil($d->netto_sebulan),
					ceil($d->netto_setahun),
					ceil(($d->ptkp/12)),
					ceil($d->ptkp),
					ceil(($d->pkp/12)),
					ceil($d->pkp),
					ceil($d->pph_sebulan),
					ceil($d->pph_setahun),
					ceil($d->pajak_setahun),
					null,
					null,
					null,
					// 'PPH 21 YANG DIBAYAR',
					// 'PPH 21 YANG DIPOTONG',	
					// 'Tunjangan PPh ',
					$d->no_npwp,
				];
				$data_body = array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
				$bodyx[] = array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
				$body[$row]=$data_body;
				$row++;
			}
		}
		$data_awal_null = [null,'TOTAL',null,null,null,null,null,null,null,ceil($total_gaji),];
		$data_total = [
			ceil($total_tunjangan),
			ceil($total_uang_makan),
			ceil($total_ritasi),
			ceil($total_lembur),
			ceil($total_perjalanan_dinas),
			ceil($total_kode_akun),
			ceil($total_bonus),
			ceil($total_thr),
			ceil($total_jumlahxx),
			ceil($total_bpjs_jkk_perusahaan),
			ceil($total_bpjs_jkm_perusahaan),
			null, 
			ceil($total_bpjs_jht_perusahaan),
			ceil($total_bpjs_kes_perusahaan),
			ceil($total_iuran_pensiun_perusahaan),
			ceil($total_premi_asuransi),
			ceil($total_penambah_lain),
			ceil($total_bpjs_jht_pekerja),
			ceil($total_bpjs_jkk_pekerja),
			ceil($total_bpjs_jkm_pekerja),
			ceil($total_bpjs_kes_pekerja),
			ceil($total_iuran_pensiun_pekerja),
			ceil($total_hutang),
			ceil($total_pot_tidak_masuk),
			ceil($total_pengurang_hallo),
			ceil($total_nominal_denda),
			ceil($total_pengurang_lain),
			ceil($total_yg_diterima),
			ceil($total_bruto_sebulan),
			ceil($total_bruto_setahun),
			ceil($total_biaya_jabatan),
			ceil($total_pesangon),
			ceil($total_pph_pesangon),
			ceil($total_netto_sebulan),
			ceil($total_netto_setahun),
			ceil($total_ptkp_sebulan),
			ceil($total_ptkp),
			ceil($total_pkp_sebulan),
			ceil($total_pkp),
			ceil($total_pph_sebulan),
			ceil($total_pph_setahun),
			ceil($total_pajak_setahun),
		];
		$body[count($bodyx)+4]=array_merge($data_awal_null,$data_total);
		$dataHeadFirst = [
			'No',
			'NIK = NIP',
			// 'No.ID = No.KTP',
			'Nama',
			'Jabatan',
			'Bagian',
			'Lokasi',
			'Grade',
			'Status PTKP',
			'Koreksi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus/Komisi',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','PPh','JHT','Kesehatan (BPJS)','Jaminan Pensiun','Premi Asuransi','Lainnya'
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'JHT','JKK','JKM','Kesehatan (BPJS)','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Sebulan',
			'Penghasilan Bruto Setahun',
			'Biaya Jabatan',
			'Pesangon',
			'PPh Pesangon',
			'Penghasilan Netto Sebulan',
			'Penghasilan Netto Setahun',
			'PTKP Sebulan',
			'PTKP Setahun',
			'PKP Sebulan',
			'PKP Setahun',
			'PPH 21 Sebulan',
			'PPH 21 Setahun',
			'Pajak Setahun',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'NPWP',
		];
		$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
		$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data PPH-21',
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
			'head_merge'=>[
				'row_head'=>1,
				'row_head_2'=>2,
				'data_head_1'=>$data_head_1,
				'data_head_2'=>$data_head,
				'max_merge'=>40,
				'merge_1'=>'A1:A2',
				'merge_2'=>'B1:B2',
				'merge_3'=>'C1:C2',
				'merge_4'=>'D1:D2',
				'merge_5'=>'E1:E2',
				'merge_6'=>'F1:F2',
				'merge_7'=>'G1:G2',
				'merge_8'=>'H1:H2',
				'merge_9'=>'I1:I2',
				'merge_10'=>'J1:J2',
				'merge_11'=>'K1:K2',
				'merge_12'=>'L1:L2',
				'merge_13'=>'M1:M2',
				'merge_14'=>'N1:N2',
				'merge_15'=>'O1:O2',
				'merge_16'=>'P1:P2',
				'merge_17'=>'Q1:Q2',
				'merge_18'=>'R1:R2',
				'merge_19'=>'S1:S2',
				'merge_20'=>'T1:AA1',
				'merge_21'=>'AB1:AK1',
				// 'merge_22'=>'AK1:AK2',
				'merge_22'=>'AL1:AL2',
				'merge_23'=>'AM1:AM2',
				'merge_24'=>'AN1:AN2',
				'merge_25'=>'AO1:AO2',
				'merge_26'=>'AP1:AP2',
				'merge_27'=>'AQ1:AQ2',
				'merge_28'=>'AR1:AR2',
				'merge_29'=>'AS1:AS2',
				'merge_30'=>'AT1:AT2',
				'merge_31'=>'AU1:AU2',
				'merge_32'=>'AV1:AV2',
				'merge_33'=>'AW1:AW2',
				'merge_34'=>'AX1:AX2',
				'merge_35'=>'AY1:AY2',
				'merge_36'=>'AZ1:AZ2',
				'merge_37'=>'BA1:BA2',
				'merge_38'=>'BB1:BB2',
				'merge_39'=>'BC1:BC2',
				'merge_40'=>'BD1:BD2',
			]
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_all_old()
	{
		$sheet = [];
		$dataHeadFirst = [
			'No',
			'NIK',
			'Nama',
			'Jabatan',
			'Bagian',
			'Lokasi',
			'Grade',
			'Status PTKP',
			'Koreksi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus/Komisi',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','PPh','JHT','Kesehatan (BPJS)','Jaminan Pensiun','Premi Asuransi','Lainnya'
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'JHT','JKK','JKM','Kesehatan (BPJS)','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Sebulan',
			'Penghasilan Bruto Setahun',
			'Biaya Jabatan',
			'Pesangon',
			'PPh Pesangon',
			'Penghasilan Netto Sebulan',
			'Penghasilan Netto Setahun',
			'PTKP Sebulan',
			'PTKP Setahun',
			'PKP Sebulan',
			'PKP Setahun',
			'PPH 21 Sebulan',
			'PPH 21 Setahun',
			'Pajak Setahun',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'NPWP',
		];
		for ($bulanx=1; $bulanx < 13; $bulanx++) { 
			$bulan = ($bulanx<10)?'0'.$bulanx:$bulanx;
			$tahun = $this->input->post('tahun');
			$koreksi = $this->input->post('koreksi');
			$data_pph_other = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPph(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]));
			$data['properties']=[
				'title'=>'Rekap Data PPH-21 Periode '.$tahun,
				'subject'=>'Rekap Data PPH-21',
				'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
				'keywords'=>"Export, Rekap, Rekap Data PPH-21",
				'category'=>"Rekap",
			];
			$body=[];
			$bodyx=[];
			$row_body=3;
			$row=$row_body;
			// $karyawan = $this->model_karyawan->getEmployeeAllActive();
			$karyawan = $this->otherfunctions->getAllKaryawanYear($tahun);
			$gaji_pokok = 0;
			$total_gaji = 0;
			$total_tunjangan = 0;
			$total_uang_makan = 0;
			$total_ritasi = 0;
			$total_lembur = 0;
			$total_perjalanan_dinas = 0;
			$total_kode_akun = 0;
			$total_bonus = 0;
			$total_thr = 0;
			$total_pesangon = 0;
			$total_pph_pesangon = 0;
			// $tt = [];
			$total_pengurang_lain = 0;
			$total_penambah_lain = 0;
			$total_pengurang_hallo = 0;
			$total_penambah_hallo = 0;
			$total_bpjs_jkk_perusahaan = 0;
			$total_bpjs_jkm_perusahaan = 0;
			$total_bpjs_jht_perusahaan = 0;
			$total_bpjs_kes_perusahaan = 0;
			$total_iuran_pensiun_perusahaan = 0;
			$total_bpjs_jht_pekerja = 0;
			$total_bpjs_jkk_pekerja = 0;
			$total_bpjs_jkm_pekerja = 0;
			$total_bpjs_kes_pekerja = 0;
			$total_iuran_pensiun_pekerja = 0;
			$total_hutang = 0;
			$total_pot_tidak_masuk = 0;
			$total_nominal_denda = 0;
			$total_yg_diterima = 0;
			$total_bruto_setahun = 0;
			$total_biaya_jabatan = 0;
			$total_netto_setahun = 0;
			$total_ptkp = 0;
			$total_pkp = 0;
			$total_pph_setahun = 0;
			$total_pajak_setahun = 0;
			$total_bruto_sebulan = 0;
			$total_netto_sebulan = 0;
			$total_pph_sebulan = 0;
			$total_ptkp_sebulan = 0;
			$total_pkp_sebulan = 0;
			$total_premi_asuransi = 0;
			$total_jumlahxx = 0;
			$body_2=[];
			foreach ($karyawan as $k) {
				$datax = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>$k->nik,'a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]);
				if(!empty($datax)){
					foreach ($datax as $d) {
						$gaji_pokok += $d->gaji_pokok;
						$total_gaji += $d->gaji_pokok;
						$total_tunjangan += $d->tunjangan;
						$total_uang_makan += $d->uang_makan;
						$total_ritasi += $d->ritasi;
						$total_lembur += $d->lembur;
						$total_perjalanan_dinas += $d->perjalanan_dinas;
						$total_kode_akun += $d->kode_akun;
						$total_bonus += $d->bonus;
						$total_thr += $d->thr;
						// $total_jumlahxx += ($d->gaji_pokok-$d->thr);
						$total_jumlahxx += ($d->gaji_pokok+$d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$d->bonus+$d->thr);
						$total_pesangon += $d->pesangon;
						$total_pph_pesangon += $d->pph_pesangon;
						$total_bpjs_jkk_perusahaan += $d->bpjs_jkk_perusahaan;
						$total_bpjs_jkm_perusahaan += $d->bpjs_jkm_perusahaan;
						$total_bpjs_jht_perusahaan += $d->bpjs_jht_perusahaan;
						$total_bpjs_kes_perusahaan += $d->bpjs_kes_perusahaan;
						$total_iuran_pensiun_perusahaan += $d->iuran_pensiun_perusahaan;
						$total_bpjs_jht_pekerja += $d->bpjs_jht_pekerja;
						$total_bpjs_jkk_pekerja += $d->bpjs_jkk_pekerja;
						$total_bpjs_jkm_pekerja += $d->bpjs_jkm_pekerja;
						$total_bpjs_kes_pekerja += $d->bpjs_kes_pekerja;
						$total_iuran_pensiun_pekerja += $d->iuran_pensiun_pekerja;
						$total_premi_asuransi += $d->premi_asuransi;
						$total_hutang += $d->hutang;
						$total_pot_tidak_masuk += ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
						$total_nominal_denda += $d->nominal_denda;
						$total_yg_diterima += $d->yg_diterima;
						$total_bruto_setahun += $d->bruto_setahun;
						$total_biaya_jabatan += $d->biaya_jabatan;
						$total_netto_setahun += $d->netto_setahun;
						$total_ptkp += $d->ptkp;
						$total_pkp += $d->pkp;
						$total_pph_setahun += $d->pph_setahun;
						$total_pajak_setahun += $d->pajak_setahun;
						$total_bruto_sebulan += $d->bruto_sebulan;
						$total_netto_sebulan += $d->netto_sebulan;
						$total_pph_sebulan += $d->pph_sebulan;
						$total_ptkp_sebulan += ($d->ptkp/12);
						$total_pkp_sebulan += ($d->pkp/12);
						$body_1 = [
							($row-2),
							$d->nik,
							$d->nama_karyawan,
							$d->nama_jabatan,
							$d->nama_bagian,
							$d->nama_loker,
							$d->nama_grade,
							// $this->formatter->getDateMonthFormatUser($d->tgl_masuk),
							// $d->masa_kerja,
							$d->status_ptkp,
							$this->otherfunctions->getNumberToAbjad($d->koreksi),
							ceil($d->gaji_pokok),
						];
						$body_3 = [
							ceil($d->tunjangan),
							ceil($d->uang_makan),
							ceil($d->ritasi),
							ceil($d->lembur),
							ceil($d->perjalanan_dinas),
							ceil($d->kode_akun),
							ceil($d->bonus),
							ceil($d->thr),
							// ceil($d->gaji_pokok-$d->thr),
							ceil($d->gaji_pokok+$d->tunjangan+$d->uang_makan+$d->ritasi+$d->lembur+$d->perjalanan_dinas+$d->kode_akun+$d->bonus+$d->thr),
						];
						$pengurang_lainx=0;
						$penambah_lainx=0;
						$pengurang_lainx_hallo=0;
						$penambah_lainx_hallo=0;
						if(!empty($d->data_lain)){
							if (strpos($d->data_lain, ';') !== false) {
								$dLain = $this->otherfunctions->getDataExplode($d->data_lain,';','all');
								$dHallo = $this->otherfunctions->getDataExplode($d->data_lain_hallo,';','all');
								$nLain = $this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
								foreach ($dLain as $key => $value) {
									if($value == 'pengurang'){
										if($dHallo[$key] == '1'){
											$pengurang_lainx_hallo += $nLain[$key];
										}else{
											$pengurang_lainx += $nLain[$key];
										}
									}else{
										if($dHallo[$key] == '1'){
											$penambah_lainx_hallo += $nLain[$key];
										}else{
											$penambah_lainx += $nLain[$key];
										}
									}
								}
							}else{
								if($d->data_lain == 'pengurang'){
									if($d->data_lain_hallo == '1'){
										$pengurang_lainx_hallo += $d->nominal_lain;
									}else{
										$pengurang_lainx += $d->nominal_lain;
									}
								}else{
									if($d->data_lain_hallo == '1'){
										$penambah_lainx_hallo += $d->nominal_lain;
									}else{
										$penambah_lainx += $d->nominal_lain;
									}
								}
							}
						}
						$pengurang_lain = $pengurang_lainx;
						$penambah_lain = $penambah_lainx;
						$pengurang_hallo = $pengurang_lainx_hallo;
						$penambah_hallo = $penambah_lainx_hallo;
						$total_pengurang_lain += $pengurang_lainx;
						$total_penambah_lain += $penambah_lainx;
						$total_pengurang_hallo += $pengurang_lainx_hallo;
						$total_penambah_hallo += $penambah_lainx_hallo;
						$body_4 = [
							ceil($d->bpjs_jkk_perusahaan),
							ceil($d->bpjs_jkm_perusahaan),
							null,
							ceil($d->bpjs_jht_perusahaan),
							ceil($d->bpjs_kes_perusahaan),
							ceil($d->iuran_pensiun_perusahaan),
							ceil($d->premi_asuransi),
							ceil($penambah_lain)
						];
						$potonganTidakKerja = ($d->pot_tidak_kerja+$d->pot_tidak_masuk);
						$body_5 = [
							ceil($d->bpjs_jht_pekerja),
							ceil($d->bpjs_jkk_pekerja),
							ceil($d->bpjs_jkm_pekerja),
							ceil($d->bpjs_kes_pekerja),
							ceil($d->iuran_pensiun_pekerja),
							ceil($d->hutang),
							ceil($potonganTidakKerja),
							ceil($pengurang_hallo),
							ceil($d->nominal_denda),
							ceil($pengurang_lain),
						];
						$body_6 = [
							ceil($d->yg_diterima),
							ceil($d->bruto_sebulan),
							ceil($d->bruto_setahun),
							ceil($d->biaya_jabatan),
							ceil($d->pesangon),
							ceil($d->pph_pesangon),
							ceil($d->netto_sebulan),
							ceil($d->netto_setahun),
							ceil(($d->ptkp/12)),
							ceil($d->ptkp),
							ceil(($d->pkp/12)),
							ceil($d->pkp),
							ceil($d->pph_sebulan),
							ceil($d->pph_setahun),
							ceil($d->pajak_setahun),
							null,
							null,
							null,
							// 'PPH 21 YANG DIBAYAR',
							// 'PPH 21 YANG DIPOTONG',	
							// 'Tunjangan PPh ',
							$d->no_npwp,
						];
						$bodyx[] = array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
						$data_body = array_merge($body_1,$body_3,$body_4,$body_5,$body_6);
						$body[$row]=$data_body;
						$row++;
					}
				}
			}
			$data_awal_null = [null,'TOTAL',null,null,null,null,null,null,null,ceil($total_gaji),];
			$data_total = [
				ceil($total_tunjangan),
				ceil($total_uang_makan),
				ceil($total_ritasi),
				ceil($total_lembur),
				ceil($total_perjalanan_dinas),
				ceil($total_kode_akun),
				ceil($total_bonus),
				ceil($total_thr),
				ceil($total_jumlahxx),
				ceil($total_bpjs_jkk_perusahaan),
				ceil($total_bpjs_jkm_perusahaan),
				ceil($total_premi_asuransi),
				ceil($total_bpjs_jht_perusahaan),
				ceil($total_bpjs_kes_perusahaan),
				ceil($total_iuran_pensiun_perusahaan),
				null, 
				ceil($total_penambah_lain),
				ceil($total_bpjs_jht_pekerja),
				ceil($total_bpjs_jkk_pekerja),
				ceil($total_bpjs_jkm_pekerja),
				ceil($total_bpjs_kes_pekerja),
				ceil($total_iuran_pensiun_pekerja),
				ceil($total_hutang),
				ceil($total_pot_tidak_masuk),
				ceil($total_pengurang_hallo),
				ceil($total_nominal_denda),
				ceil($total_pengurang_lain),
				ceil($total_yg_diterima),
				ceil($total_bruto_sebulan),
				ceil($total_bruto_setahun),
				ceil($total_biaya_jabatan),
				ceil($total_pesangon),
				ceil($total_pph_pesangon),
				ceil($total_netto_sebulan),
				ceil($total_netto_setahun),
				ceil($total_ptkp_sebulan),
				ceil($total_ptkp),
				ceil($total_pkp_sebulan),
				ceil($total_pkp),
				ceil($total_pph_sebulan),
				ceil($total_pph_setahun),
				ceil($total_pajak_setahun),
			];
			$body[count($bodyx)+4]=array_merge($data_awal_null,$data_total);
			$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
			$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);
			$sheet[($bulanx-1)]=[
				'range_huruf'=>3,
				'sheet_title'=>$this->formatter->getNameOfMonth($bulan).' '.$tahun,
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
				'head_merge'=>[
					'row_head'=>1,
					'row_head_2'=>2,
					'data_head_1'=>$data_head_1,
					'data_head_2'=>$data_head,
					'max_merge'=>40,
					'merge_1'=>'A1:A2',
					'merge_2'=>'B1:B2',
					'merge_3'=>'C1:C2',
					'merge_4'=>'D1:D2',
					'merge_5'=>'E1:E2',
					'merge_6'=>'F1:F2',
					'merge_7'=>'G1:G2',
					'merge_8'=>'H1:H2',
					'merge_9'=>'I1:I2',
					'merge_10'=>'J1:J2',
					'merge_11'=>'K1:K2',
					'merge_12'=>'L1:L2',
					'merge_13'=>'M1:M2',
					'merge_14'=>'N1:N2',
					'merge_15'=>'O1:O2',
					'merge_16'=>'P1:P2',
					'merge_17'=>'Q1:Q2',
					'merge_18'=>'R1:R2',
					'merge_19'=>'S1:S2',
					'merge_20'=>'T1:AA1',
					'merge_21'=>'AB1:AK1',
					'merge_22'=>'AL1:AL2',
					'merge_23'=>'AM1:AM2',
					'merge_24'=>'AN1:AN2',
					'merge_25'=>'AO1:AO2',
					'merge_26'=>'AP1:AP2',
					'merge_27'=>'AQ1:AQ2',
					'merge_28'=>'AR1:AR2',
					'merge_29'=>'AS1:AS2',
					'merge_30'=>'AT1:AT2',
					'merge_31'=>'AU1:AU2',
					'merge_32'=>'AV1:AV2',
					'merge_33'=>'AW1:AW2',
					'merge_34'=>'AX1:AX2',
					'merge_35'=>'AY1:AY2',
					'merge_36'=>'AZ1:AZ2',
					'merge_37'=>'BA1:BA2',
					'merge_38'=>'BB1:BB2',
					'merge_39'=>'BC1:BC2',
					'merge_40'=>'BD1:BD2',
				]
			];
		}
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function rekap_data_pph_21_tahunan_old()
	{
		$dataHeadFirst = [
			'No',
			'NIK',
			'Nama',
			'Jabatan',
			'Bagian',
			'Lokasi',
			'Grade',
			'Status PTKP',
			'Koreksi',
			'Gaji Pokok',
		];
		$dataHeadMiddle = [
			'Jumlah Tunjangan',
			'Uang Makan',
			'Ritasi',
			'Lembur',
			'Perjalanan Dinas',
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'Bonus/Komisi',
			'THR',
			'JUMLAH',
		];
		$penambah = [
			'JKK','JKM','PPh','JHT','Kesehatan (BPJS)','Jaminan Pensiun','Premi Asuransi','Lainnya'
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'JHT','JKK','JKM','Kesehatan (BPJS)','Iuran Pensiun', 'Piutang Karyawan','Koreksi Absen','HALLO','Denda','Lainnya'
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'YANG DITERIMA',
			'Penghasilan Bruto Sebulan',
			'Penghasilan Bruto Setahun',
			'Biaya Jabatan',
			'Pesangon',
			'PPh Pesangon',
			'Penghasilan Netto Sebulan',
			'Penghasilan Netto Setahun',
			'PTKP Sebulan',
			'PTKP Setahun',
			'PKP Sebulan',
			'PKP Setahun',
			'PPH 21 Sebulan',
			'PPH 21 Setahun',
			'Pajak Setahun',
			'PPH 21 YANG DIBAYAR',
			'PPH 21 YANG DIPOTONG',	
			'Tunjangan PPh ',
			'NPWP',
		];
		$tahun = $this->input->post('tahun');
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Tahun '.$tahun,
			'subject'=>'Rekap Data PPH-21',
			'description'=>"Rekap Data PPH-21 HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21",
			'category'=>"Rekap",
		];
		$body=[];
		$bodyx=[];
		$row_body=3;
		$row=$row_body;
		// $karyawan = $this->model_karyawan->getEmployeeAllActive();
		$karyawan = $this->otherfunctions->getAllKaryawanYear($tahun);
		$gaji_pokok = 0;
		$total_gaji = 0;
		$total_tunjangan = 0;
		$total_uang_makan = 0;
		$total_ritasi = 0;
		$total_lembur = 0;
		$total_perjalanan_dinas = 0;
		$total_kode_akun = 0;
		$total_bonus = 0;
		$total_thr = 0;
		$total_pesangon = 0;
		$total_pph_pesangon = 0;
		// $tt = [];
		$total_pengurang_lain = 0;
		$total_penambah_lain = 0;
		$total_pengurang_hallo = 0;
		$total_penambah_hallo = 0;
		$total_bpjs_jkk_perusahaan = 0;
		$total_bpjs_jkm_perusahaan = 0;
		$total_bpjs_jht_perusahaan = 0;
		$total_bpjs_kes_perusahaan = 0;
		$total_iuran_pensiun_perusahaan = 0;
		$total_bpjs_jht_pekerja = 0;
		$total_bpjs_jkk_pekerja = 0;
		$total_bpjs_jkm_pekerja = 0;
		$total_bpjs_kes_pekerja = 0;
		$total_iuran_pensiun_pekerja = 0;
		$total_hutang = 0;
		$total_pot_tidak_masuk = 0;
		$total_nominal_denda = 0;
		$total_yg_diterima = 0;
		$total_bruto_setahun = 0;
		$total_biaya_jabatan = 0;
		$total_netto_setahun = 0;
		$total_ptkp = 0;
		$total_pkp = 0;
		$total_pph_setahun = 0;
		$total_pajak_setahun = 0;
		$total_bruto_sebulan = 0;
		$total_netto_sebulan = 0;
		$total_pph_sebulan = 0;
		$total_ptkp_sebulan = 0;
		$total_pkp_sebulan = 0;
		$total_premi_asuransi = 0;
		$total_jumlahxx = 0;
		$body_2=[];
		foreach ($karyawan as $d) {
			$dataPPh = $this->model_payroll->getListDataPenggajianPph(['a.nik'=>$d->nik,'a.tahun'=>$tahun],null,null,null,'max');
			if(!empty($dataPPh)){
				$gaji_pokok = 0;
				$tt = [];
				$tunjangan = 0;
				$uang_makan = 0;
				$ritasi = 0;
				$lembur = 0;
				$perjalanan_dinas = 0;
				$kode_akun = 0;
				$pengurang_lain = 0;
				$penambah_lain = 0;
				$pengurang_hallo = 0;
				$penambah_hallo = 0;
				$bpjs_jkk_perusahaan = 0;
				$bpjs_jkm_perusahaan = 0;
				$bpjs_jht_perusahaan = 0;
				$bpjs_kes_perusahaan = 0;
				$iuran_pensiun_perusahaan = 0;
				$bpjs_jht_pekerja = 0;
				$bpjs_jkk_pekerja = 0;
				$bpjs_jkm_pekerja = 0;
				$bpjs_kes_pekerja = 0;
				$iuran_pensiun_pekerja = 0;
				$hutang = 0;
				$pot_tidak_masuk = 0;
				$nominal_denda = 0;
				$yg_diterima = 0;
				$bruto_setahun = 0;
				$biaya_jabatan = 0;
				$netto_setahun = 0;
				$ptkp = '';
				$pkp = 0;
				$pph_setahun = 0;
				$pajak_setahun = 0;
				$bonus = 0;
				$thr = 0;
				$premi_asuransi = 0;
				$pesangon = 0;
				$pph_pesangon = 0;
				$no_npwp = '';
				$jumlah_x = 0;
				foreach ($dataPPh as $p) {
					$gaji_pokok += $p->gaji_pokok;
					$total_gaji += $p->gaji_pokok;
					$total_tunjangan += $p->tunjangan;
					$total_uang_makan += $p->uang_makan;
					$total_ritasi += $p->ritasi;
					$total_lembur += $p->lembur;
					$total_perjalanan_dinas += $p->perjalanan_dinas;
					$total_kode_akun += $p->kode_akun;
					$total_bonus += $p->bonus;
					$total_thr += $p->thr;
					$total_pesangon += $p->pesangon;
					$total_pph_pesangon += $p->pph_pesangon;
					$total_bpjs_jkk_perusahaan += $p->bpjs_jkk_perusahaan;
					$total_bpjs_jkm_perusahaan += $p->bpjs_jkm_perusahaan;
					$total_bpjs_jht_perusahaan += $p->bpjs_jht_perusahaan;
					$total_bpjs_kes_perusahaan += $p->bpjs_kes_perusahaan;
					$total_iuran_pensiun_perusahaan += $p->iuran_pensiun_perusahaan;
					$total_bpjs_jht_pekerja += $p->bpjs_jht_pekerja;
					$total_bpjs_jkk_pekerja += $p->bpjs_jkk_pekerja;
					$total_bpjs_jkm_pekerja += $p->bpjs_jkm_pekerja;
					$total_bpjs_kes_pekerja += $p->bpjs_kes_pekerja;
					$total_iuran_pensiun_pekerja += $p->iuran_pensiun_pekerja;
					$total_premi_asuransi += $p->premi_asuransi;
					$total_hutang += $p->hutang;
					$total_pot_tidak_masuk += ($p->pot_tidak_kerja+$p->pot_tidak_masuk);
					$total_nominal_denda += $p->nominal_denda;
					$total_yg_diterima += $p->yg_diterima;
					$total_bruto_setahun += $p->bruto_setahun;
					$total_biaya_jabatan += $p->biaya_jabatan;
					$total_netto_setahun += $p->netto_setahun;
					$total_ptkp += $p->ptkp;
					$total_pkp += $p->pkp;
					$total_pph_setahun += $p->pph_setahun;
					$total_pajak_setahun += $p->pajak_setahun;
					$total_bruto_sebulan += $p->bruto_sebulan;
					$total_netto_sebulan += $p->netto_sebulan;
					$total_pph_sebulan += $p->pph_sebulan;
					$total_ptkp_sebulan += ($p->ptkp/12);
					$total_pkp_sebulan += ($p->pkp/12);
					// $total_jumlahxx += ($p->gaji_pokok-$p->thr);
					// $jumlah_x += ($p->gaji_pokok-$p->thr);
					$total_jumlahxx += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
					$jumlah_x += ($p->gaji_pokok+$p->tunjangan+$p->uang_makan+$p->ritasi+$p->lembur+$p->perjalanan_dinas+$p->kode_akun+$p->bonus+$p->thr);
					// $gaji_pokok +=$p->gaji_pokok;
					$tunjangan +=$p->tunjangan;
					$uang_makan +=$p->uang_makan;
					$ritasi +=$p->ritasi;
					$lembur +=$p->lembur;
					$perjalanan_dinas +=$p->perjalanan_dinas;
					$kode_akun +=$p->kode_akun;
					$bonus +=$p->bonus;
					$thr +=$p->thr;
					$premi_asuransi +=$p->premi_asuransi;
					$pesangon +=$p->pesangon;
					$pph_pesangon +=$p->pph_pesangon;
					$pengurang_lainx=0;
					$penambah_lainx=0;
					$pengurang_lainx_hallo=0;
					$penambah_lainx_hallo=0;
					if(!empty($p->data_lain)){
						if (strpos($p->data_lain, ';') !== false) {
							$dLain = $this->otherfunctions->getDataExplode($p->data_lain,';','all');
							$dHallo = $this->otherfunctions->getDataExplode($p->data_lain_hallo,';','all');
							$nLain = $this->otherfunctions->getDataExplode($p->nominal_lain,';','all');
							foreach ($dLain as $key => $value) {
								if($value == 'pengurang'){
									if($dHallo[$key] == '1'){
										$pengurang_lainx_hallo += $nLain[$key];
									}else{
										$pengurang_lainx += $nLain[$key];
									}
								}else{
									if($dHallo[$key] == '1'){
										$penambah_lainx_hallo += $nLain[$key];
									}else{
										$penambah_lainx += $nLain[$key];
									}
								}
							}
						}else{
							if($p->data_lain == 'pengurang'){
								if($p->data_lain_hallo == '1'){
									$pengurang_lainx_hallo += $p->nominal_lain;
								}else{
									$pengurang_lainx += $p->nominal_lain;
								}
							}else{
								if($p->data_lain_hallo == '1'){
									$penambah_lainx_hallo += $p->nominal_lain;
								}else{
									$penambah_lainx += $p->nominal_lain;
								}
							}
						}
					}
					$pengurang_lain += $pengurang_lainx;
					$penambah_lain += $penambah_lainx;
					$pengurang_hallo += $pengurang_lainx_hallo;
					$penambah_hallo += $penambah_lainx_hallo;
					$total_pengurang_lain += $pengurang_lainx;
					$total_penambah_lain += $penambah_lainx;
					$total_pengurang_hallo += $pengurang_lainx_hallo;
					$total_penambah_hallo += $penambah_lainx_hallo;
					$bpjs_jkk_perusahaan +=$p->bpjs_jkk_perusahaan;
					$bpjs_jkm_perusahaan +=$p->bpjs_jkm_perusahaan;
					$bpjs_jht_perusahaan +=$p->bpjs_jht_perusahaan;
					$bpjs_kes_perusahaan +=$p->bpjs_kes_perusahaan;
					$iuran_pensiun_perusahaan +=$p->iuran_pensiun_perusahaan;
					$bpjs_jht_pekerja +=$p->bpjs_jht_pekerja;
					$bpjs_jkk_pekerja +=$p->bpjs_jkk_pekerja;
					$bpjs_jkm_pekerja +=$p->bpjs_jkm_pekerja;
					$bpjs_kes_pekerja +=$p->bpjs_kes_pekerja;
					$iuran_pensiun_pekerja +=$p->iuran_pensiun_pekerja;
					$hutang +=$p->hutang;
					$pot_tidak_masuk +=($p->pot_tidak_kerja+$p->pot_tidak_masuk);
					$nominal_denda +=$p->nominal_denda;
					$yg_diterima +=$p->yg_diterima;
					$bruto_setahun +=$p->bruto_setahun;
					$biaya_jabatan +=$p->biaya_jabatan;
					$netto_setahun +=$p->netto_setahun;
					$ptkp =$p->ptkp;
					$pkp +=$p->pkp;
					$pph_setahun +=$p->pph_setahun;
					$pajak_setahun +=$p->pajak_setahun;
					$no_npwp =$p->no_npwp;
				}
				$body_1 = [
					($row-2),
					$d->nik,
					$d->nama,
					$d->nama_jabatan,
					$d->bagian,
					$d->nama_loker,
					$d->nama_grade,
					$d->status_pajak,
					null,
					ceil($gaji_pokok),
				];
				$body_3 = [
					ceil($tunjangan),
					ceil($uang_makan),
					ceil($ritasi),
					ceil($lembur),
					ceil($perjalanan_dinas),
					ceil($kode_akun),
					ceil($bonus),
					ceil($thr),
					ceil($jumlah_x),
					ceil($bpjs_jkk_perusahaan),
					ceil($bpjs_jkm_perusahaan),
					null,
					ceil($bpjs_jht_perusahaan),
					ceil($bpjs_kes_perusahaan),
					ceil($iuran_pensiun_perusahaan),
					ceil($premi_asuransi),
					ceil($penambah_lain),
					ceil($bpjs_jht_pekerja),
					ceil($bpjs_jkk_pekerja),
					ceil($bpjs_jkm_pekerja),
					ceil($bpjs_kes_pekerja),
					ceil($iuran_pensiun_pekerja),
					ceil($hutang),
					ceil($pot_tidak_masuk),
					ceil($pengurang_hallo),
					ceil($nominal_denda),
					ceil($pengurang_lain),
					ceil($yg_diterima),
					ceil($bruto_setahun/12),
					ceil($bruto_setahun),
					ceil($biaya_jabatan),
					ceil($pesangon),
					ceil($pph_pesangon),
					ceil($netto_setahun/12),
					ceil($netto_setahun),
					ceil($ptkp/12),
					ceil($ptkp),
					ceil($pkp/12),
					ceil($pkp),
					ceil($pph_setahun/12),
					ceil($pph_setahun),
					ceil($pajak_setahun),
					null,
					null,
					null,
					$no_npwp,
				];
				$data_body = array_merge($body_1,$body_3);
				$bodyx[] = array_merge($body_1,$body_3);
				$body[$row]=$data_body;
				$row++;
			}
		}
		$data_awal_null = [null,'TOTAL',null,null,null,null,null,null,null,ceil($total_gaji),];
		$data_total = [
			ceil($total_tunjangan),
			ceil($total_uang_makan),
			ceil($total_ritasi),
			ceil($total_lembur),
			ceil($total_perjalanan_dinas),
			ceil($total_kode_akun),
			ceil($total_bonus),
			ceil($total_thr),
			ceil($total_jumlahxx),
			ceil($total_bpjs_jkk_perusahaan),
			ceil($total_bpjs_jkm_perusahaan),
			ceil($total_premi_asuransi),
			ceil($total_bpjs_jht_perusahaan),
			ceil($total_bpjs_kes_perusahaan),
			ceil($total_iuran_pensiun_perusahaan),
			null, 
			ceil($total_penambah_lain),
			ceil($total_bpjs_jht_pekerja),
			ceil($total_bpjs_jkk_pekerja),
			ceil($total_bpjs_jkm_pekerja),
			ceil($total_bpjs_kes_pekerja),
			ceil($total_iuran_pensiun_pekerja),
			ceil($total_hutang),
			ceil($total_pot_tidak_masuk),
			ceil($total_pengurang_hallo),
			ceil($total_nominal_denda),
			ceil($total_pengurang_lain),
			ceil($total_yg_diterima),
			ceil($total_bruto_sebulan),
			ceil($total_bruto_setahun),
			ceil($total_biaya_jabatan),
			ceil($total_pesangon),
			ceil($total_pph_pesangon),
			ceil($total_netto_sebulan),
			ceil($total_netto_setahun),
			ceil($total_ptkp_sebulan),
			ceil($total_ptkp),
			ceil($total_pkp_sebulan),
			ceil($total_pkp),
			ceil($total_pph_sebulan),
			ceil($total_pph_setahun),
			ceil($total_pajak_setahun),
		];
		$body[count($bodyx)+4]=array_merge($data_awal_null,$data_total);
		$data_head = array_merge($dataHeadFirst,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
		$data_head_1 = array_merge($dataHeadFirst,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);		
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data PPh 21 Tahun '.$tahun,
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
			'head_merge'=>[
				'row_head'=>1,
				'row_head_2'=>2,
				'data_head_1'=>$data_head_1,
				'data_head_2'=>$data_head,
				'max_merge'=>40,
				'merge_1'=>'A1:A2',
				'merge_2'=>'B1:B2',
				'merge_3'=>'C1:C2',
				'merge_4'=>'D1:D2',
				'merge_5'=>'E1:E2',
				'merge_6'=>'F1:F2',
				'merge_7'=>'G1:G2',
				'merge_8'=>'H1:H2',
				'merge_9'=>'I1:I2',
				'merge_10'=>'J1:J2',
				'merge_11'=>'K1:K2',
				'merge_12'=>'L1:L2',
				'merge_13'=>'M1:M2',
				'merge_14'=>'N1:N2',
				'merge_15'=>'O1:O2',
				'merge_16'=>'P1:P2',
				'merge_17'=>'Q1:Q2',
				'merge_18'=>'R1:R2',
				'merge_19'=>'S1:S2',
				'merge_20'=>'T1:AA1',
				'merge_21'=>'AB1:AK1',
				// 'merge_22'=>'AK1:AK2',
				'merge_22'=>'AL1:AL2',
				'merge_23'=>'AM1:AM2',
				'merge_24'=>'AN1:AN2',
				'merge_25'=>'AO1:AO2',
				'merge_26'=>'AP1:AP2',
				'merge_27'=>'AQ1:AQ2',
				'merge_28'=>'AR1:AR2',
				'merge_29'=>'AS1:AS2',
				'merge_30'=>'AT1:AT2',
				'merge_31'=>'AU1:AU2',
				'merge_32'=>'AV1:AV2',
				'merge_33'=>'AW1:AW2',
				'merge_34'=>'AX1:AX2',
				'merge_35'=>'AY1:AY2',
				'merge_36'=>'AZ1:AZ2',
				'merge_37'=>'BA1:BA2',
				'merge_38'=>'BB1:BB2',
				'merge_39'=>'BC1:BC2',
				'merge_40'=>'BD1:BD2',
			]
		];
		$data['data']=$sheet;
		// print_r($data);
		$this->rekapgenerator->genExcel($data);
	}
	//================================================ DATA PPH 21 KARYAWAN HARIAN =================================================//	
	public function rekap_data_pph_21_harian()
	{
		$bulanx = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$koreksix = $this->input->post('koreksi');
		$bulan = (empty($bulanx))?date('m'):$bulanx;
		$koreksi = (empty($koreksix))?'0':$koreksix;
		$data_pph = $this->otherfunctions->convertResultToRowArray($this->model_payroll->getListDataPenggajianPphHarian(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'a.koreksi'=>$koreksi]));
		$data['properties']=[
			'title'=>'Rekap Data PPH-21 Karyawan Harian Bulan '.$data_pph['bulan'].' ( '.$data_pph['tahun'].' ) Pembetulan '.$this->otherfunctions->getNumberToAbjad($koreksi),
			'subject'=>'Rekap Data PPH-21 Karyawan Harian',
			'description'=>"Rekap Data PPH-21 Karyawan Harian HSOFT JKB - Dicetak Oleh : ".$this->dtroot['adm']['nama'],
			'keywords'=>"Export, Rekap, Rekap Data PPH-21 Karyawan Harian",
			'category'=>"Rekap",
		];		
		$body=[];
		$bodyx=[];
		$row_body=7;
		$row=$row_body;
		$datax = $this->model_payroll->getListDataPenggajianPphHarian(['a.bulan'=>$bulan,'a.tahun'=>$tahun,'koreksi'=>$koreksi]);
		if(!empty($datax)){
			$total_gaji_1 = 0;
			$total_gaji_2 = 0;
			$total_gaji_3 = 0;
			$total_gaji_4 = 0;
			$total_gaji_5 = 0;
			$total_lembur_1 = 0;
			$total_lembur_2 = 0;
			$total_lembur_3 = 0;
			$total_lembur_4 = 0;
			$total_lembur_5 = 0;
			$total_kode_akun = 0;
			$total_thr = 0;
			$total_gaji_diterima = 0;
			$total_bpjs_jkk_perusahaan = 0;
			$total_bpjs_jkm_perusahaan = 0;
			$total_pph_tunjangan = 0;
			$total_premi_asuransi = 0;
			$total_bpjs_jht_perusahaan = 0;
			$total_bpjs_pen_perusahaan = 0;
			$total_bpjs_kes_perusahaan = 0;
			$total_penambah_lain = 0;
			$total_bpjs_kes_pekerja = 0;
			$total_bpjs_pen_pekerja = 0;
			$total_bpjs_jht_pekerja = 0;
			$total_pengurang_lain = 0;
			$total_bruto_sebulan = 0;
			$total_pesangon = 0;
			$total_netto_sebulan = 0;
			$total_presensi = 0;
			$total_ptkp = 0;
			$total_pkp = 0;
			$total_pph_sebulan = 0;
			foreach ($datax as $d) {
				$total_gaji_1 += $d->gaji_1;
				$total_gaji_2 += $d->gaji_2;
				$total_gaji_3 += $d->gaji_3;
				$total_gaji_4 += $d->gaji_4;
				$total_gaji_5 += $d->gaji_5;
				$total_lembur_1 += $d->lembur_1;
				$total_lembur_2 += $d->lembur_2;
				$total_lembur_3 += $d->lembur_3;
				$total_lembur_4 += $d->lembur_4;
				$total_lembur_5 += $d->lembur_5;
				$total_kode_akun += $d->kode_akun;
				$total_thr += $d->thr;
				$total_gaji_diterima += $d->gaji_diterima;
				$total_bpjs_jkk_perusahaan += $d->bpjs_jkk_perusahaan;
				$total_bpjs_jkm_perusahaan += $d->bpjs_jkm_perusahaan;
				$total_pph_tunjangan += $d->pph_tunjangan;
				$total_premi_asuransi += $d->premi_asuransi;
				$total_bpjs_jht_perusahaan += $d->bpjs_jht_perusahaan;
				$total_bpjs_pen_perusahaan += $d->bpjs_pen_perusahaan;
				$total_bpjs_kes_perusahaan += $d->bpjs_kes_perusahaan;
				$total_penambah_lain += $d->penambah_lain;
				$total_bpjs_kes_pekerja += $d->bpjs_kes_pekerja;
				$total_bpjs_pen_pekerja += $d->bpjs_pen_pekerja;
				$total_bpjs_jht_pekerja += $d->bpjs_jht_pekerja;
				$total_pengurang_lain += $d->pengurang_lain;
				$total_bruto_sebulan += $d->bruto_sebulan;
				$total_pesangon += $d->pesangon;
				$total_netto_sebulan += $d->netto_sebulan;
				$total_presensi += $d->presensi;
				$total_ptkp += $d->ptkp;
				$total_pkp += $d->pkp;
				$total_pph_sebulan += $d->pph_sebulan;
				$body_1 = [
					$row-6,
					$d->nik,
					$d->no_ktp,
					$d->nama_karyawan,
					$d->npwp,
					$d->nama_jabatan,
					$d->nama_bagian,
					$d->nama_loker,
					$d->nama_grade,
					$d->status_pajak,
					$this->otherfunctions->getNumberToAbjad($d->koreksi),
					$this->otherfunctions->pembulatanFloor($d->gaji_1),
					$this->otherfunctions->pembulatanFloor($d->gaji_2),
					$this->otherfunctions->pembulatanFloor($d->gaji_3),
					$this->otherfunctions->pembulatanFloor($d->gaji_4),
					$this->otherfunctions->pembulatanFloor($d->gaji_5),
					$this->otherfunctions->pembulatanFloor($d->lembur_1),
					$this->otherfunctions->pembulatanFloor($d->lembur_2),
					$this->otherfunctions->pembulatanFloor($d->lembur_3),
					$this->otherfunctions->pembulatanFloor($d->lembur_4),
					$this->otherfunctions->pembulatanFloor($d->lembur_5),
				];
				$body_2 = [
					$this->otherfunctions->pembulatanFloor($d->kode_akun),
					$this->otherfunctions->pembulatanFloor($d->thr),
					$this->otherfunctions->pembulatanFloor($d->gaji_diterima),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jkk_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jkm_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->pph_tunjangan),
					$this->otherfunctions->pembulatanFloor($d->premi_asuransi),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jht_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->bpjs_pen_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->bpjs_kes_perusahaan),
					$this->otherfunctions->pembulatanFloor($d->penambah_lain),
					$this->otherfunctions->pembulatanFloor($d->bpjs_kes_pekerja),
					$this->otherfunctions->pembulatanFloor($d->bpjs_pen_pekerja),
					$this->otherfunctions->pembulatanFloor($d->bpjs_jht_pekerja),
					$this->otherfunctions->pembulatanFloor($d->pengurang_lain),
				];
				$body_3 = [
					$this->otherfunctions->pembulatanFloor($d->bruto_sebulan),
					$this->otherfunctions->pembulatanFloor($d->pesangon),
					$this->otherfunctions->pembulatanFloor($d->netto_sebulan),
					$this->otherfunctions->pembulatanFloor($d->status_pajak),
					$this->otherfunctions->pembulatanFloor($d->presensi),
					$this->otherfunctions->pembulatanFloor($d->ptkp),
				];
				$body_4 = [
					$this->otherfunctions->pembulatanFloor($d->pkp),
					$this->otherfunctions->pembulatanFloor($d->pph_sebulan),
					0,
					0,
				];
				$data_body = array_merge($body_1,$body_2,$body_3,$body_4);
				$bodyx[] = array_merge($body_1,$body_2,$body_3,$body_4);
				$body[$row]=$data_body;
				$row++;
			}
		}
		$data_awal_null = [null,'TOTAL',null,null,null,null,null,null,null,null,null,];
		$data_total = [
			$this->otherfunctions->pembulatanFloor($total_gaji_1),
			$this->otherfunctions->pembulatanFloor($total_gaji_2),
			$this->otherfunctions->pembulatanFloor($total_gaji_3),
			$this->otherfunctions->pembulatanFloor($total_gaji_4),
			$this->otherfunctions->pembulatanFloor($total_gaji_5),
			$this->otherfunctions->pembulatanFloor($total_lembur_1),
			$this->otherfunctions->pembulatanFloor($total_lembur_2),
			$this->otherfunctions->pembulatanFloor($total_lembur_3),
			$this->otherfunctions->pembulatanFloor($total_lembur_4),
			$this->otherfunctions->pembulatanFloor($total_lembur_5),
			$this->otherfunctions->pembulatanFloor($total_kode_akun),
			$this->otherfunctions->pembulatanFloor($total_thr),
			$this->otherfunctions->pembulatanFloor($total_gaji_diterima),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkk_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jkm_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_pph_tunjangan),
			$this->otherfunctions->pembulatanFloor($total_premi_asuransi),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jht_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_pen_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_bpjs_kes_perusahaan),
			$this->otherfunctions->pembulatanFloor($total_penambah_lain),
			$this->otherfunctions->pembulatanFloor($total_bpjs_kes_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_pen_pekerja),
			$this->otherfunctions->pembulatanFloor($total_bpjs_jht_pekerja),
			$this->otherfunctions->pembulatanFloor($total_pengurang_lain),
			$this->otherfunctions->pembulatanFloor($total_bruto_sebulan),
			$this->otherfunctions->pembulatanFloor($total_pesangon),
			$this->otherfunctions->pembulatanFloor($total_netto_sebulan),
			null,
			$this->otherfunctions->pembulatanFloor($total_presensi),
			$this->otherfunctions->pembulatanFloor($total_ptkp),
			$this->otherfunctions->pembulatanFloor($total_pkp),
			$this->otherfunctions->pembulatanFloor($total_pph_sebulan),
		];
		$body[count($bodyx)+9]=array_merge($data_awal_null,$data_total);
		$dataHeadFirst = [
			'No',
			'NIK = NIP',
			'No ID = No KTP',
			'Nama',
			'NPWP',
			'Jabatan',
			'Bagian',
			'Lokasi',
			'Grade',
			'Status PTKP',
			'Koreksi',
		];
		$gajiPeriode = [
			'MINGGU 1','MINGGU 2','MINGGU 3','MINGGU 4','MINGGU 5',
		];
		$gajiPeriodex = array_merge(['GAJI PERIODE'],$gajiPeriode);
		unset($gajiPeriodex[1]);
		$lemburPeriode = [
			'MINGGU 1','MINGGU 2','MINGGU 3','MINGGU 4','MINGGU 5',
		];
		$lemburPeriodex = array_merge(['LEMBUR PERIODE'],$lemburPeriode);
		unset($lemburPeriodex[1]);
		$dataHeadMiddle = [
			'UM, Insentif/ Tambahan'."\n".'Tugas Luar Kota, dll',
			'THR',
			'YANG DITERIMA',
		];
		$penambah = [
			'JKK','JKM','PPh','Premi Asuransi','Kesehatan (BPJS)','Lainnya',
		];
		$penambahx = array_merge(['PENAMBAH'],$penambah);
		unset($penambahx[1]);
		$pengurang = [
			'Potongan BPJS Kesehatan','Iuran Pensiun', 'Potongan JHT','Lainnya',
		];
		$pengurangx = array_merge(['POTONGAN'],$pengurang);
		unset($pengurangx[1]);
		$dataHeadEnd = [
			'Penghasilan Bruto',
			'JHT',
			'Jaminan Pensiun',
			'Pesangon',
			'Penghasilan Netto',
			'Status Pajak',
			'Hari Kerja',
			'PTKP',
			'PKP',
			'PPH 21',
			'Setor',
			'Rumus Tunjangan PPh',
		];
		$data_head = array_merge($dataHeadFirst,$gajiPeriode,$lemburPeriode,$dataHeadMiddle,$penambah,$pengurang,$dataHeadEnd);
		$data_head_1 = array_merge($dataHeadFirst,$gajiPeriodex,$lemburPeriodex,$dataHeadMiddle,$penambahx,$pengurangx,$dataHeadEnd);
		$body[1]=['LAPORAN UPAH KARYAWAN MINGGUAN DAN PERHITUNGAN PPH21'];
		$body[2]=['BULAN '.strtoupper($this->formatter->getNameOfMonth($bulan)).' '.$tahun];
		$body[3]=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT'];
		$body[6]=[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'SUM(L:W)',null,null,null,null,null,null,null,null,null,null,'SUM(X:AD)',null,null,null,'(AI-AF-AG-AH)',null,null,null,null,null,null,null,];
		$jumData = count($bodyx)+9;
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data PPH-21 Harian',
				'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
			'head_merge'=>[
				'abjadTop'=>true,
				'jumData'=>[
					'1'=>'AE4:AH'.$jumData,
					'8'=>'L4:X'.$jumData,
					'9'=>'Y4:AD'.$jumData,
					'10'=>'AI4:AT'.$jumData,
					],
				'row_head'=>4,
				'row_head_2'=>5,
				'data_head_1'=>$data_head_1,
				'data_head_2'=>$data_head,
				'max_merge'=>32,
				'merge_1'=>'A4:A5',
				'merge_2'=>'B4:B5',
				'merge_3'=>'C4:C5',
				'merge_4'=>'D4:D5',
				'merge_5'=>'E4:E5',
				'merge_6'=>'F4:F5',
				'merge_7'=>'G4:G5',
				'merge_8'=>'H4:H5',
				'merge_9'=>'I4:I5',
				'merge_10'=>'J4:J5',
				'merge_11'=>'K4:K5',
				'merge_12'=>'L4:P4',
				'merge_13'=>'Q4:U4',
				'merge_14'=>'V4:V5',
				'merge_15'=>'W4:W5',
				'merge_16'=>'X4:X5',
				'merge_17'=>'Y4:AD4',
				'merge_18'=>'AE4:AH4',
				'merge_19'=>'AI4:AI5',
				'merge_20'=>'AJ4:AJ5',
				'merge_21'=>'AK4:AK5',
				'merge_22'=>'AL4:AL5',
				'merge_23'=>'AM4:AM5',
				'merge_24'=>'AN4:AN5',
				'merge_25'=>'AO4:AO5',
				'merge_26'=>'AP4:AP5',
				'merge_27'=>'AQ4:AQ5',
				'merge_28'=>'AR4:AR5',
				'merge_29'=>'AS4:AS5',
				'merge_30'=>'AT4:AT5',
				'merge_31'=>'A1:D1',
				'merge_32'=>'A2:D2',
			]
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//=========================================================================================================================================//
//=============================== PENILAIAN KINERJA ===================================================
//Rekap Data Assessr
	public function export_data_assessor()
	{
		$data['properties']=[
			'title'=>"Rekap Data Assessor",
			'subject'=>"Rekap Data Assessor",
			'description'=>"Rekap Data Assessor",
			'keywords'=>"Rekap Data Assessor, Data Assessor",
			'category'=>"Rekap",
		];

		$body=[];
		$datax=$this->model_karyawan->getAssessorAll();
		$row_body=2;
		$row=$row_body;
		foreach ($datax as $d) {
			if($d->kelamin == 'l'){
				$kelamin = 'Laki-laki';
			}else{
				$kelamin = 'Perempuan';
			}
			$ttl = '';
			if($d->tempat_lahir !='' || $d->tgl_lahir !=''){
				if($d->tempat_lahir != NULL){
					$ttl .= $d->tempat_lahir.', ';
				} 
				$ttl .= $this->formatter->getDateMonthFormatUser($d->tgl_lahir);
			}else{
				$ttl .= 'Unknown';
			}
			$body[$row]=[
				($row-1),
				$d->nik,
				$d->nama,
				$d->nama_jabatan,
				$d->bagian,
				$d->nama_departement,
				$d->nama_loker,
				$d->nama_rank,
				(($d->tgl_masuk)?$this->formatter->getDateMonthFormatUser($d->tgl_masuk):'Unknown'),
				$d->nama_status,
				$d->email,
				$d->id_finger,
				$d->no_ktp,
				$kelamin,
				$ttl,
				$d->no_hp,
				$d->poin,
				(($d->status)?'Aktif':'Non-Aktif')
			];
			$row++;
		}

		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Assessor',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.', 'NIK', 'NAMA', 'JABATAN', 'BAGIAN', 'DEPARTEMENT', 'LOKASI KERJA', 'RANK', 'TANGGAL MASUK', 'STATUS PEGAWAI', 'EMAIL', 'ID FINGER', 'NOMOR KTP', 'JENIS KELAMIN', 'TEMPAT TANGGAL LAHIR', 'NOMOR PONSEL', 'POIN', 'STATUS ASSESSOR'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];

		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//------------------------------------------------------------------------------------------------------//
//Rekap Penilaian Sikap
	function rekap_partisipan_sikap(){
		$tahun=$this->input->post('tahunx');
		$periode=$this->input->post('periodex');
		$datax=$this->codegenerator->decryptChar($this->input->post('data'));
		if (!isset($datax)) {
			$this->messages->notValidParam();  
			redirect('pages/view_employee_result_sikap');
		}else{
			if($datax !== null){
				$data['properties']=[
					'title'=>"Rekap Partisipan Sikap ".$periode.' - '.$tahun,
					'subject'=>"Rekap Partisipan Belum Menilai Agenda Sikap",
					'description'=>"Rekap Partisipan Belum Menilai Agenda Sikap ".$periode.' - '.$tahun.', WRITTEN BY HSOFT SYSTEM',
					'keywords'=>"Rekap Data, Rekap Nilai",
					'category'=>"Rekap",
				];
				$body=[];
				$row_body=2;
				$row=$row_body;
				$no=1;
				foreach ($datax['partisipan'] as $k) {
					$body[$row]=[$no,$k['nik'],$k['nama'],$k['nama_jabatan'],$k['bagian'],$k['nama_departemen'],$k['nama_loker'],$k['belum_menilai']];
					$row++;
					$no++;
				}
				$sheet[0]=[
					'range_huruf'=>3,
					'sheet_title'=>'Rekap Partisipan Belum Menilai',
					'head'=>[
						'row_head'=>1,
						'data_head'=>[
							'NO','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA','BELUM MENILAI'],
					],
					'body'=>[
						'row_body'=>$row_body,
						'data_body'=>$body
					],
				];
				$data['data']=$sheet;
				$this->rekapgenerator->genExcel($data);
			}
		}
	}
	function rekap_nilai_sikap(){
		$tahun=$this->input->post('tahun');
		$periode=$this->input->post('periode');
		$datax=$this->codegenerator->decryptChar($this->input->post('data'));
		
		$data['properties']=[
			'title'=>"Rekap Penilaian Sikap ".$periode.' - '.$tahun,
			'subject'=>"Rekap Nilai Agenda Sikap",
			'description'=>"Rekap Data Penilaian Agenda Sikap ".$periode.' - '.$tahun.', WRITTEN BY HSOFT SYSTEM',
			'keywords'=>"Rekap Data, Rekap Nilai",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if (!empty($datax)) {
			$no=1;
			foreach ($datax['rekap'] as $k) {
				$body[$row]=[
					$no,$k['nik'],$k['nama'],$k['nama_jabatan'],$k['bagian'],$k['nama_departemen'],$k['nama_loker'],
					$this->formatter->getNumberFloat($k['nilai_ats'],2,'en'),
					$this->formatter->getNumberFloat($k['nilai_rkn'],2,'en'),
					$this->formatter->getNumberFloat($k['nilai_bwh'],2,'en'),
					$this->formatter->getNumberFloat($k['nilai_akhir'],2,'en'),
					$this->formatter->getNumberFloat($k['nilai_kalibrasi'],2,'en')
				];
				$row++;
				$no++;
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Nilai Sikap',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'NO','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA','NILAI ATASAN','NILAI REKAN','NILAI BAWAHAN','NILAI ASLI','NILAI KALIBRASI'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
				'numeric'=>[7,8],
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	function rekap_nilai_sikap_kuisioner(){
		$access = $this->codegenerator->decryptChar($this->uri->segment(4));
		$kode = $this->codegenerator->decryptChar($this->uri->segment(3));
		parse_str($_SERVER['QUERY_STRING'], $post_form);
		$datax=$this->model_agenda->ResultSikapKuisioner($kode,$post_form);
		$data['properties']=[
			'title'=>"Rekap Penilaian Sikap Kuisioner",
			'subject'=>"Rekap Kuisioner Agenda Sikap",
			'description'=>"Rekap Data Penilaian Agenda Sikap Kuisioner, WRITTEN BY HSOFT SYSTEM",
			'keywords'=>"Rekap Data, Rekap Kuisioner",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$row=$row_body;
		if (!empty($datax)) {
			$no=1;
			foreach ($datax as $k) {
				if (isset($k)) {
					foreach ($k as $val) {
						$body[$row]=[
							$no,
							$val['nik'],
							$val['nama'],
							$val['jabatan'],
							$val['bagian'],
							$val['departement'],
							$val['loker'],
							$val['aspek'],
							$val['kuisioner'],
							$val['penilai'],
							$val['keterangan'],
							$this->formatter->getNumberFloat($val['nilai_atas'],2,'en'),
							$this->formatter->getNumberFloat($val['nilai_bawah'],2,'en'),
							$this->formatter->getNumberFloat($val['nilai_rekan'],2,'en'),
							$this->formatter->getNumberFloat($val['nilai_akhir'],2,'en'),
						];
						$row++;
						$no++;
					}
				}
			}
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Nilai Sikap Kuisioner',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'NO','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA','ASPEK SIKAP','KUISIONER','PENILAI','KETERANGAN','NILAI ATASAN','NILAI BAWAHAN','NILAI REKAN','NILAI AKHIR'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
				'numeric'=>[11,12,13,14],
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//------------------------------------------------------------------------------------------------------//
//Rekap Penilaian KPI
	public function rekap_nilai_output(){
		$data['properties']=[
			'title'=>"Rekap Hasil Penilaian KPI",
			'subject'=>"Rekap Hasil Penilaian KPI",
			'description'=>"Rekap Hasil Penilaian KPI HSOFT KUBOTA",
			'keywords'=>"Rekap Data, Rekap Nilai, Rekap Nilai KPI, Rekap KPI",
			'category'=>"Rekap",
		];
		$body=[];
		$datax=$this->codegenerator->decryptChar($this->input->post('data'));
		$periode = $this->model_agenda->getAgendaKpiKode($this->codegenerator->decryptChar($datax['periode']));
		$row_body=2;
		$row=$row_body;
		foreach ($datax['rekap'] as $v) {
			$arr_body_start=[($row-1),$v['nik'],$v['nama'],$v['jabatan'],$v['bagian'],$v['departemen'],$v['loker']];
			$arr_body_month=[];
			for ($i=0; $i < $this->max_month ; $i++) { 
				if (isset($v[$i])) {
					$arr_body_month[$i]=$this->formatter->getNumberFloat($v[$i],2,'en');
				}else{
					$arr_body_month[$i]=0;
				}
			}
			$arr_body_end=[$this->formatter->getNumberFloat($v['nilai'],2,'en'),$v['huruf'],$v['periode']];
			$body[$row]=array_merge($arr_body_start,$arr_body_month,$arr_body_end);
			$row++;
		}
		$arr_head_start=['No','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA'];
		$arr_head_month=[];
		$periode = $this->formatter->getNameOfMonthByPeriode($periode['start'],$periode['end'],$periode['tahun']);
		foreach ($periode as $pkey => $pval) {
			$arr_head_month[$pkey]=$pval;
		}
		$arr_head_end=['NILAI','HURUF','PERIODE'];
		$numer=[];
		for ($i=7; $i <=($this->max_month+7) ; $i++) { 
			array_push($numer,$i);
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Nilai KPI',
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
	public function rekap_partisipan_output()
	{
		$data['properties']=[
			'title'=>"Rekap Partisipan Belum Menilai KPI",
			'subject'=>"Rekap Partisipan Belum Menilai KPI",
			'description'=>"Rekap Partisipan Belum Menilai KPI HSOFT KUBOTA",
			'keywords'=>"Rekap Data, Rekap Partisipan Belum Menilai, Rekap Partisipan KPI, Rekap KPI",
			'category'=>"Rekap",
		];
		$body=[];
		$datax=$this->codegenerator->decryptChar($this->input->post('data'));
		$row_body=2;
		$row=$row_body;
		$pertisipan='';
		foreach ($datax['data'] as $k => $v) {
			$body[$row]=[($row-1),$v['nik'],$v['nama'],$v['jabatan'],$v['bagian'],$v['departemen'],$v['loker'],$v['nama_dinilai'],$v['jabatan_dinilai']];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Partisipan KPI',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA','NAMA KARYAWAN DINILAI','JABATAN KARYAWAN DINILAI'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//------------------------------------------------------------------------------------------------------//
//Rekap Konsep KPI
	public function export_template_data_konsep_kpi()
	{
		$data['properties']=[
			'title'=>"Template Rancangan KPI",
			'subject'=>"Template Rancangan KPI",
			'description'=>"Template Rancangan KPI Untuk Karyawan",
			'keywords'=>"Template Rancangan KPI, Rancangan KPI",
			'category'=>"Template",
		];

		$kode_konsep = $this->codegenerator->decryptChar($this->uri->segment(3));
		$access = $this->codegenerator->decryptChar($this->uri->segment(4));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
		$body=[];
		$table = $this->model_concept->getKonsepKpiKode($kode_konsep)['nama_tabel'];
		$datax=$this->model_concept->openTableViewConceptKpiJabatan($table,null,'none',$filter);
		$row_body=2;
		foreach ($datax as $d) {
			$target = (!empty($d->target)) ? $d->target : 0;
			$bobot = (!empty($d->bobot)) ? $d->bobot : 0;
			$body[$row_body]=[
				($row_body-1),
				$d->nik,
				$d->nama_karyawan,
				$d->kode_jabatan,
				$d->nama_jabatan,
				$d->kode_kpi,
				$d->kpi,
				$d->detail_rumus,
				$d->sifat,
				$d->unit,
				$d->nama_rumus, 
				$d->sumber_data,
				$target,
				$bobot,
				$d->kode_penilai,
				$d->penilai,
			];
			$row_body++;
		}
		$row_karyawan=2;
		$datax_karyawan=$this->model_karyawan->getEmployeeAllActive(null,$filter);
		foreach ($datax_karyawan as $dk) {
			$body_karyawan[$row_karyawan]=[
				($row_karyawan-1),
				$dk->nik,
				$dk->nama,
				$dk->nama_jabatan,
				$dk->id_karyawan,
			];
			$row_karyawan++;
		}
		$row_kode=2;
		$body_kode=[
			2=>[1,'P1','Atasan Langsung'],
			3=>[2,'P2','Administrator'],
			4=>[3,'P3','Pilih User'],
		];
		$arr_tm=['No.','NIK','NAMA KARYAWAN','KODE JABATAN','NAMA JABATAN', 'KODE KPI', 'KPI', 'DETAIL RUMUS', 'SIFAT', 'UNIT', 'CARA MENGHITUNG', 'SUMBER DATA', 'TARGET', 'BOBOT', 'KODE PENILAI','PENILAI'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Rancangan KPI',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$arr_tm,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$sheet[1]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.','NIK','NAMA KARYAWAN','NAMA JABATAN', 'ID KARYAWAN'],
			],
			'body'=>[
				'row_body'=>$row_karyawan,
				'data_body'=>$body_karyawan
			],
		];
		$sheet[2]=[
			'range_huruf'=>3,
			'sheet_title'=>'Kode Penilai',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.','KODE PENILAI','KETERANGAN'],
			],
			'body'=>[
				'row_body'=>$row_kode,
				'data_body'=>$body_kode
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_template_detail_konsep_kpi()
	{
		$data['properties']=[
			'title'=>"Template Rancangan KPI",
			'subject'=>"Template Rancangan KPI",
			'description'=>"Template Rancangan KPI Untuk Karyawan",
			'keywords'=>"Template Rancangan KPI, Rancangan KPI",
			'category'=>"Template",
		];

		$kode_konsep = $this->codegenerator->decryptChar($this->uri->segment(3));
		$kode_jabatan = $this->codegenerator->decryptChar($this->uri->segment(4));
		$access = $this->codegenerator->decryptChar($this->uri->segment(5));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
		$body=[];
		$table = $this->model_concept->getKonsepKpiKode($kode_konsep)['nama_tabel'];
		$datax=$this->model_concept->openTableViewConceptKpiJabatan($table,$kode_jabatan,'none',$filter);
		$row_body=2;
		$row=$row_body;
		foreach ($datax as $d) {
			$target = (!empty($d->target)) ? $d->target : 0;
			$bobot = (!empty($d->bobot)) ? $d->bobot : 0;
			$body[$row]=[
				($row-1),				
				$d->nik,
				$d->nama_karyawan,
				$d->kode_jabatan,
				$d->nama_jabatan,
				$d->kode_kpi,
				$d->kpi,
				$d->detail_rumus,
				$d->sifat,
				$d->unit,
				$d->nama_rumus, 
				$d->sumber_data,
				$target,
				$bobot,
				$d->kode_penilai,
				$d->penilai,
			];
			$row++;
		}
		$row_karyawan=2;
		$datax_karyawan=$this->model_karyawan->getEmployeeAllActive(null,$filter);
		foreach ($datax_karyawan as $dk) {
			$body_karyawan[$row_karyawan]=[
				($row_karyawan-1),
				$dk->nik,
				$dk->nama,
				$dk->nama_jabatan,
				$dk->id_karyawan,
			];
			$row_karyawan++;
		}
		$row_kode=2;
		$body_kode=[
			2=>[1,'P1','Atasan Langsung'],
			3=>[2,'P2','Administrator'],
			4=>[3,'P3','Pilih User'],
		];
		$arr_tm=['No.','NIK','NAMA KARYAWAN','KODE JABATAN','NAMA JABATAN', 'KODE KPI', 'KPI', 'DETAIL RUMUS', 'SIFAT', 'UNIT', 'CARA MENGHITUNG', 'SUMBER DATA', 'TARGET', 'BOBOT', 'KODE PENILAI','PENILAI'];
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Template Rancangan KPI',
			'head'=>[
				'row_head'=>1,
				'data_head'=>$arr_tm,
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$sheet[1]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Karyawan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.','NIK','NAMA KARYAWAN','NAMA JABATAN', 'ID KARYAWAN'],
			],
			'body'=>[
				'row_body'=>$row_karyawan,
				'data_body'=>$body_karyawan
			],
		];
		$sheet[2]=[
			'range_huruf'=>3,
			'sheet_title'=>'Kode Penilai',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.','KODE PENILAI','KETERANGAN'],
			],
			'body'=>[
				'row_body'=>$row_kode,
				'data_body'=>$body_kode
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_data_konsep_kpi()
	{
		$data['properties']=[
			'title'=>"Rekap Data Rancangan KPI",
			'subject'=>"Rekap Data Rancangan KPI",
			'description'=>"Rekap Data Rancangan KPI",
			'keywords'=>"Rekap Data Rancangan KPI, Data Rancangan KPI",
			'category'=>"Rekap",
		];

		$kode_konsep = $this->codegenerator->decryptChar($this->uri->segment(3));
		$access = $this->codegenerator->decryptChar($this->uri->segment(4));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;		
		$body=[];
		$table = $this->model_concept->getKonsepKpiKode($kode_konsep)['nama_tabel'];
		$datax=$this->model_concept->openTableViewConceptKpiJabatan($table,null,'none',$filter);
		$row_body=2;
		$row=$row_body;
		$data_row='';
		$data_head='';
		for ($i=1; $i <= $this->max_range ; $i++) { 
			$data_row.='poin_'.$i.','.'satuan_'.$i.',';
			$data_head.='POIN '.$i.','.'SATUAN '.$i.',';
		}
		$data_row=array_filter(explode(',', $data_row));
		$data_head=array_filter(explode(',', $data_head));
		foreach ($datax as $d) {
			$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
			if($d->kode_penilai == 'P1'){
				$getAtasan = $this->model_karyawan->getEmployeeAtasan($d->id_karyawan);
				$nameEmp = [];
				foreach ($getAtasan as $k => $v) {
					$getEmp = $this->model_karyawan->getEmployeeId($v);
					$nameEmp[] = $getEmp['nama'];
				}
				$penilai = implode(', ', $nameEmp);
			}elseif($d->kode_penilai == 'P2'){
				$getAdmin = $this->model_admin->getListAdmin();
				$penilai = implode(', ', $getAdmin);
			}elseif($d->kode_penilai == 'P3'){
				$getOther = $this->otherfunctions->getParseOneLevelVar($d->penilai);
				$nameEmp = [];
				foreach ($getOther as $k => $v) {
					$getEmp = $this->model_karyawan->getEmployeeId($v);
					$nameEmp[] = $getEmp['nama'];
				}
				$penilai = implode('; ', $nameEmp);
			}
			$sub_row1=[
				($row-1), 
				$emp['nik'], 
				$emp['nama'], 
				$emp['nama_jabatan'], 
				$emp['bagian'], 
				$emp['nama_loker'], 
				$d->kode_kpi, 
				$d->kpi, 
				$d->detail_rumus, 
				$d->nama_rumus, 
				$this->otherfunctions->getKaitanNilai($d->kaitan), 
				$d->unit, 
				$this->otherfunctions->getJenisSatuan($d->jenis_satuan), 
				$this->otherfunctions->getSifatKpi($d->sifat),
				$d->sumber_data
			]; 
			$sub_row2=[];
			foreach ($data_row as $d_r) {
				array_push($sub_row2, $d->$d_r);
			}
			$sub_row3=[$d->bobot, $d->target, (!empty($d->kode_penilai)?$this->otherfunctions->getPenilai($d->kode_penilai):null), $penilai]; 

			$body[$row]=array_merge($sub_row1,$sub_row2,$sub_row3);
			$row++;
		}
		$sub_head1=['No.','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','LOKASI KERJA','KODE KPI','KPI','DETAIL RUMUS','CARA MENGHITUNG','KAITAN','UNIT','JENIS SATUAN','SIFAT','SUMBER DATA'];
		$sub_head2=$data_head;
		$sub_head3=['BOBOT', 'TARGET', 'KODE PENILAI', 'NAMA PENILAI']; 
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Rancangan KPI',
			'head'=>[
				'row_head'=>1,
				'data_head'=>array_merge($sub_head1,$sub_head2,$sub_head3),
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_detail_konsep_kpi()
	{
		$data['properties']=[
			'title'=>"Rekap Data Rancangan KPI",
			'subject'=>"Rekap Data Rancangan KPI",
			'description'=>"Rekap Data Rancangan KPI",
			'keywords'=>"Rekap Data Rancangan KPI, Data Rancangan KPI",
			'category'=>"Rekap",
		];

		$kode_konsep = $this->codegenerator->decryptChar($this->uri->segment(3));
		$kode_jabatan = $this->codegenerator->decryptChar($this->uri->segment(4));
		$access = $this->codegenerator->decryptChar($this->uri->segment(5));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;		
		$body=[];
		$table = $this->model_concept->getKonsepKpiKode($kode_konsep)['nama_tabel'];
		$datax=$this->model_concept->openTableViewConceptKpiJabatan($table,$kode_jabatan,'none',$filter);
		$row_body=2;
		$row=$row_body;
		$data_row='';
		$data_head='';
		for ($i=1; $i <=$this->max_range ; $i++) { 
			$data_row.='poin_'.$i.','.'satuan_'.$i.',';
			$data_head.='POIN '.$i.','.'SATUAN '.$i.',';
		}
		$data_row=array_filter(explode(',', $data_row));
		$data_head=array_filter(explode(',', $data_head));
		foreach ($datax as $d) {
			$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
			if($d->kode_penilai == 'P1'){
				$getAtasan = $this->model_karyawan->getEmployeeAtasan($d->id_karyawan);
				$nameEmp = [];
				foreach ($getAtasan as $k => $v) {
					$getEmp = $this->model_karyawan->getEmployeeId($v);
					$nameEmp[] = $getEmp['nama'];
				}
				$penilai = implode(', ', $nameEmp);
			}elseif($d->kode_penilai == 'P2'){
				$getAdmin = $this->model_admin->getListAdmin();
				$penilai = implode(', ', $getAdmin);
			}elseif($d->kode_penilai == 'P3'){
				$getOther = $this->otherfunctions->getParseOneLevelVar($d->penilai);
				$nameEmp = [];
				foreach ($$getOther as $k => $v) {
					$getEmp = $this->model_karyawan->getEmployeeId($v);
					$nameEmp[] = $getEmp['nama'];
				}
				$penilai = implode(', ', $nameEmp);
			}
			$sub_row1=[($row-1), 
				$emp['nik'], 
				$emp['nama'], 
				$emp['nama_jabatan'], 
				$emp['bagian'], 
				$emp['nama_loker'], 
				$d->kode_kpi, 
				$d->kpi, 
				$d->detail_rumus, 
				$d->nama_rumus, 
				$this->otherfunctions->getKaitanNilai($d->kaitan), 
				$d->unit, 
				$this->otherfunctions->getJenisSatuan($d->jenis_satuan), 
				$this->otherfunctions->getSifatKpi($d->sifat),
				$d->sumber_data, 
			]; 
			$sub_row2=[];
			foreach ($data_row as $d_r) {
				array_push($sub_row2, $d->$d_r);
			}
			$sub_row3=[$d->bobot, $d->target, $this->otherfunctions->getPenilai($d->kode_penilai), $penilai]; 

			$body[$row]=array_merge($sub_row1,$sub_row2,$sub_row3);
			$row++;
		}
		$sub_head1=['No.','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','LOKASI KERJA','KODE KPI','KPI','DETAIL RUMUS','CARA MENGHITUNG','KAITAN','UNIT','JENIS SATUAN','SIFAT','SUMBER DATA'];
		$sub_head2=$data_head;
		$sub_head3=['BOBOT', 'TARGET', 'KODE PENILAI', 'NAMA PENILAI']; 

		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Rancangan KPI',
			'head'=>[
				'row_head'=>1,
				'data_head'=>array_merge($sub_head1,$sub_head2,$sub_head3),
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//------------------------------------------------------------------------------------------------------//
//Rekap Input KPI
	public function export_input_kpi()
	{
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$id_kar=$this->uri->segment(4);
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
		$datax=$this->model_agenda->openTableAgendaIdEmployee($getAgenda['nama_tabel'],$id_kar);
		if ($id_kar == 'all') {
			$dtroot=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
			$l_acc=$this->otherfunctions->getYourAccess($this->admin);
			$filter=(in_array('FTR', $l_acc))?$dtroot['kode_bagian']:0;
			$post_form=[];
			if (!empty($filter)) {
				$post_form['bagian_filter']=$filter;
			}
			$datax=$this->model_agenda->openTableAgenda($getAgenda['nama_tabel'],$post_form);
		}
		
		$row_body=2;
		$row=$row_body;
		foreach ($datax as $d) {
			for ($i=1; $i <= $this->max_month; $i++) { 
				$pnx = 'pn'.$i;
				$val_e[$i] = $ket_n;
				$get_sel = $this->otherfunctions->getParseVar($d->$pnx);
				if(!empty($get_sel)){
					foreach ($get_sel as $gk => $gv) {
						if($gk=='A_'.$this->admin){
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
				$arr_start=[($row-1),$d->nik,$d->nama,$d->nama_jabatan,$d->bagian,$d->nama_loker,$d->kode_kpi,$d->kpi,$c_menghitung,$this->otherfunctions->getJenisSatuan($d->jenis_satuan),$d->sumber_data,$d->unit,$d->detail_rumus,$d->target,$d->bobot];
				$arr_end=[];
				for ($i=1; $i <= $this->max_month ; $i++) { 
					$arr_end[$i]=(isset($val_e[$i]))?$val_e[$i]:$ket_n;
				}
				$body[$row]=array_merge($arr_start,$arr_end);
			}elseif($usage == 'rekap'){				
				$arr_start=[($row-1),$d->nik,$d->nama,$d->nama_jabatan,$d->bagian,$d->nama_loker,$d->kode_kpi,$d->kpi,$c_menghitung,$this->otherfunctions->getJenisSatuan($d->jenis_satuan),$d->sumber_data,$d->unit,$d->detail_rumus,$d->target,$d->bobot.'%'];
				$arr_end=[];
				for ($i=1; $i <= $this->max_month ; $i++) { 
					$arr_end[$i]=(isset($val_e[$i]))?$val_e[$i]:$ket_n;
				}
				$body[$row]=array_merge($arr_start,$arr_end);
			}
			$row++;
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
						'No.','NIK','NAMA','JABATAN','BAGIAN','LOKASI KERJA','KODE KPI','KPI','CARA MENGHITUNG','JENIS SATUAN','SUMBER DATA','UNIT','DETAIL RUMUS','TARGET','BOBOT (%)'],
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
						'No.','NIK','NAMA','JABATAN','BAGIAN','LOKASI KERJA','KODE KPI','KPI','CARA MENGHITUNG','JENIS SATUAN','JENIS','UNIT','DEFINISI','TARGET','BOBOT'],
				],
				'body'=>[
					'row_body'=>$row_body,
					'data_body'=>$body
				],
			];
		}
		$head_merge = array_merge($sheet[0]['head']['data_head'],$periodex);
		$sheet[0]['head']['data_head'] = $head_merge;
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//------------------------------------------------------------------------------------------------------//
//Rekap Hasil PA
	public function export_pa_tahunan()
	{
		if(empty($this->uri->segment(3))){
			redirect('not_found');
		}
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$data['properties']=[
			'title'=>"Rekap Data Hasil PA Tahun ".$kode['tahun'],
			'subject'=>"Rekap Data Hasil PA Tahun ".$kode['tahun'],
			'description'=>"Rekap Data Hasil PA Tahun ".$kode['tahun'].",Rekap Data Hasil PA Karyawan",
			'keywords'=>"Rekap Data Hasil PA Tahun ".$kode['tahun'].",Hasil PA Tahun ".$kode['tahun'],
			'category'=>"Rekap",
		];
		parse_str($_SERVER['QUERY_STRING'], $post_form);
		$access=$this->codegenerator->decryptChar($this->uri->segment(4));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
		if (!empty($filter)) {
			$post_form['bagian_filter']=$filter;
		}
		$body=[];
		$row_body=2;
		$datax=$this->model_agenda->getListRaportTahunanHistory($kode,$post_form);
		$data_p=$this->model_master->getListPeriodePenilaian(1);
		if(isset($datax)){
			foreach ($datax as $k=>$d) {
				$auto_rank='';
				if($d->auto_rank_up_old){
					$auto_rank.=' [Rank Up Otomatis]';
				}
				$ar1=[
					($k+1).'.',
					$d->nik,
					$d->nama,
					$d->nama_jabatan,
					$d->nama_bagian,
					$d->nama_departement,
					$d->nama_loker,
					// (($d->nama_rank_old)?$d->nama_rank_old.$auto_rank:'Unknown'),
					// $this->formatter->getNumberFloat($d->poin_old_tahun,2,'en'),
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
				// $ar3=[
				// 	$this->formatter->getNumberFloat(($d->poin_now_tahun+$d->poin_old_tahun),2,'en'),
				// 	$this->formatter->getNumberFloat($d->max_poin_rank_up,2,'en'),
				// 	(($d->nama_rank)?$d->nama_rank:'Unknown'),
				// 	($d->sisa < 0)?$this->formatter->getNumberFloat(($d->poin_now_tahun+$d->poin_old_tahun),2,'en'):$this->formatter->getNumberFloat($d->sisa,2,'en'),
				// ];
				$body[$row_body]=array_merge($ar1,$ar2);
				$row_body++;
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
		$col_numeric=array_merge($col_numeric);
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
	public function export_pa_insentif()
	{
		if(empty($this->uri->segment(3))){
			redirect('not_found');
		}
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		$data['properties']=[
			'title'=>"Rekap Data Hasil PA (Insentif) ".$kode['tahun'],
			'subject'=>"Rekap Data Hasil PA (Insentif) ".$kode['tahun'],
			'description'=>"Rekap Data Hasil PA (Insentif) ".$kode['tahun'].",Rekap Data Hasil PA Karyawan",
			'keywords'=>"Rekap Data Hasil PA (Insentif) ".$kode['tahun'].",Hasil PA (Insentif) ".$kode['tahun'],
			'category'=>"Rekap",
		];
		parse_str($_SERVER['QUERY_STRING'], $post_form);
		$access=$this->codegenerator->decryptChar($this->uri->segment(4));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
		if (!empty($filter)) {
			$post_form['bagian_filter']=$filter;
		}
		$body=[];
		$row_body=2;
		$datax=$this->model_agenda->getListEmployeeInsentif($kode,$post_form);
		$data_p=$this->model_master->getListPeriodePenilaian(1);
		if(isset($datax)){
			$no=1;
			foreach ($datax as $k=>$d) {
				$ar1=[
					$no.'.',
					$d[1],
					$d[2],
					$d[3],
					$d[4],
					$d[5],
					$d[6]
				];
				$ar2=[];
				if (isset($data_p)) {
					$cn=1;
					foreach ($data_p as $dp){
						if (isset($d[$dp->kode_periode])) {
							$ar2[$cn]=$this->formatter->getNumberFloat($d[$dp->kode_periode],2,'en');
							$cn++;
						}
					}
				}
				$ar3=[
					$this->formatter->getNumberFloat($d[7],2,'en'),
					$d[8]
				];
				$body[$row_body]=array_merge($ar1,$ar2,$ar3);
				$row_body++;
				$no++;
			}
		}
		$head_arr1=[
			'No.','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA'
		];
		$head_arr2=[];
		if (isset($data_p)) {
			foreach ($data_p as $dp){
				$head_arr2[]=(($dp->nama)?$dp->nama.' ('.$kode['tahun'].')':'Unknown');
			}
		}
		$head_arr3=[
			'RATA - RATA','HURUF'
		];
		$max_per=count($head_arr2)+6;
		$col_numeric=[];
		for ($i=1; $i <= count($head_arr2); $i++) { 
			array_push($col_numeric,($i+6));
		}
		$col_numeric=array_merge($col_numeric,[($max_per+1)]);
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Hasil PA (Insentif)',
			'head'=>[
				'row_head'=>1,
				'data_head'=>array_merge($head_arr1,$head_arr2,$head_arr3),
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
	public function export_pa_periode(){
		$kode=$this->codegenerator->decryptChar($this->uri->segment(3));
		parse_str($_SERVER['QUERY_STRING'], $post_form);
		$access=$this->codegenerator->decryptChar($this->uri->segment(4));
		$filter=(in_array('FTR',$access['access']))?$access['kode_bagian']:0;
		if (!empty($filter)) {
			$post_form['bagian_filter']=$filter;
		}
		$perth=((isset($post_form['nama_periode']))?$post_form['nama_periode']:'Unknown');
		$data['properties']=[
			'title'=>"Rekap Data Hasil PA ".$perth,
			'subject'=>"Rekap Data Hasil PA ".$perth,
			'description'=>"Rekap Data Hasil PA ".$perth.",Rekap Data Hasil PA Karyawan",
			'keywords'=>"Rekap Data Hasil PA ".$perth.",Hasil PA ".$perth,
			'category'=>"Rekap",
		];
		$body=[];
		$datax = $this->model_agenda->getListEmployeeReportGroup($kode,$post_form);
		$row_body=2;
		$row=$row_body;
		
		
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
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Hasil PA',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'NO.','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMEN','LOKASI KERJA','NILAI KPI OUTPUT','NILAI ASPEK SIKAP 360°','NILAI TOTAL','KEDISIPLINAN','NILAI '.$perth,'HURUF','PERIODE'],
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
//------------------------------------------------------------------------------------------------------//
//Rekap Data Presensi
	public function export_data_presensi()
	{
		$data['properties']=[
			'title'=>"Rekap Data Presensi",
			'subject'=>"Rekap Data Presensi",
			'description'=>"Rekap Data Presensi,Rekap Data Presensi Karyawan",
			'keywords'=>"Rekap Data Presensi,Presensi",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$datax=$this->model_presensi->getListPresensi(true);
		foreach ($datax as $k=>$d) {
			$body[$row_body]=[
				($k+1).'.',(((!empty($d->nama_periode)) ? $d->nama_periode : 'Unknown').' - '.$d->tahun),$d->id_finger,$d->nik,$d->nama,$d->nama_jabatan,$d->nama_bagian,$d->nama_loker,$d->ijin,$d->telat,$d->mangkir,$d->sp
			];
			$row_body++;
		}

		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Presensi',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No.','PERIODE - TAHUN','ID FINGER','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','LOKASI KERJA','IJIN','TERLAMBAT','BOLOS','SP'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];

		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}

//------------------------------------------------------------------------------------------------------//
//Rekap Data Leaderboard
	public function export_data_leaderboard()
	{
		$data['properties']=[
			'title'=>"Rekap Data Leaderboard",
			'subject'=>"Rekap Data Leaderboard",
			'description'=>"Rekap Data Leaderboard,Rekap Data Leaderboard Karyawan",
			'keywords'=>"Rekap Data Leaderboard,Leaderboard",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$dtroot=$this->otherfunctions->convertResultToRowArray($this->model_admin->getAdmin($this->admin));
		$l_acc=$this->otherfunctions->getYourAccess($this->admin);
		$filter=(in_array('FTR', $l_acc))?$dtroot['kode_bagian']:0;
		$datax=$this->model_karyawan->getEmployeeAllActive(['column'=>'emp.poin','param'=>'DESC'],$filter);
		foreach ($datax as $k=>$d) { 
			$poin_old=(($d->poin - $d->poin_now) < 0)?$d->poin_old:($d->poin - $d->poin_now);
			$poin=($d->poin)?$d->poin:($poin_old);
			$auto_rank='';
			if($d->auto_rank_up_old){
				$auto_rank.=' [Rank Up Otomatis]';
			}
			$body[$row_body]=[
				($k+1).'.',
				$d->nik,
				$d->nama,
				$d->nama_jabatan,
				$d->bagian,
				$d->nama_departement,
				$d->nama_loker,
				$this->otherfunctions->getMasaKerja($d->tgl_masuk,$this->otherfunctions->getDateNow()),
				$d->nama_rank_old.$auto_rank,
				$this->formatter->getNumberFloat($poin_old,2,'en'),						
				$d->nama_rank,
				$this->formatter->getNumberFloat($d->poin_now,2,'en'),						
				$this->formatter->getNumberFloat($poin,2,'en'),						
			];
			$row_body++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Leaderboard',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'No.','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','DEPARTEMENT','LOKASI KERJA','MASA KERJA','RANK SEBELUMNYA','POIN SEBELUMNYA','RANK SAAT INI','POIN SAAT INI','POIN AKHIR'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
				'numeric'=>[9,11,12],
			],
		];

		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
//------------------------------------------------------------------------------------------------------//
//Rekap Data Reward
	public function export_data_reward()
	{
		$table=$this->codegenerator->decryptChar($this->uri->segment(3));
		if (empty($table)) {
			$this->messages->sessNotValidParam();
			redirect('not_found');	
		}
		$data['properties']=[
			'title'=>"Rekap Data Reward",
			'subject'=>"Rekap Data Reward",
			'description'=>"Rekap Data Reward,Rekap Data Reward Karyawan",
			'keywords'=>"Rekap Data Reward,Reward",
			'category'=>"Rekap",
		];
		$body=[];
		$row_body=2;
		$datax=$this->model_agenda->openTableAgenda($table);
		foreach ($datax as $k=>$d) { 
			$detail='';
			$parse=$this->otherfunctions->getParseVar($d->kode_reward);
			if (isset($parse)) {
				$count=1;
			 	foreach ($parse as $k_rw => $v_rw) {
			 		$data_rw=$this->model_master->getRewardKode($k_rw);
			 		if (isset($data_rw)) {
			 			$detail.=$data_rw['nama'].' ['.$v_rw.']';
			 			if ($count < count($parse)) {
			 				$detail.=', ';
			 			}
			 		}
			 		$count++;
			 	}
			} 
			$body[$row_body]=[
				($k+1).'.',
				$d->nik,
				$d->nama,
				$d->nama_jabatan,
				$d->bagian,
				$d->nama_loker,
				$detail,
				$d->na,
			];
			$row_body++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Rekap Data Reward',
			'head'=>[
				'row_head'=>1,
				'data_head'=>['No.','NIK','NAMA KARYAWAN','JABATAN','BAGIAN','LOKASI KERJA','DETAIL REWARD','TOTAL POIN'],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body,
				'numeric'=>[7],
			],
		];

		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
	public function export_detail_peringatan()
	{
		$nik=$this->codegenerator->decryptChar($this->uri->segment(3));
		$nama=$this->codegenerator->decryptChar($this->uri->segment(4));
		$getdata=$this->model_karyawan->exportPeringatan('search',['nik'=>$nik]);
 		$user = $this->dtroot;
		$data['properties']=[
			'title'=>"Rekap Data Surat Peringatan ".$nama,
			'subject'=>"Rekap Data Surat Peringatan",
			'description'=>"Rekap Data Surat Peringatan HSOFT PT. Kubota Indonesia",
			'keywords'=>"Rekap, Export, Peringatan, Surat Peringatan",
			'category'=>"Export",
		];

		$body=[];
		$row_body=2;
		$row=$row_body;
		foreach ($getdata as $d) {
			$jbt_mengetahui=(!empty($d->jbt_mengetahui)) ? ' ('.$d->jbt_mengetahui.')' : null;
			$jbt_menyetujui=(!empty($d->jbt_menyetujui)) ? ' ('.$d->jbt_menyetujui.')' : null;
			$tgl_sampai = (!empty($d->berlaku_sampai))? ' - '.$this->formatter->getDateMonthFormatUser($d->berlaku_sampai) : null;
			$body[$row]=[
				($row-1).'.',
				$d->nik_karyawan,
				$d->nama_karyawan,
				$d->nama_jabatan,
				$d->nama_loker,
				$d->no_sk,
				$this->formatter->getDateMonthFormatUser($d->tgl_sk),
				$d->nama_status_baru,
				$this->formatter->getDateMonthFormatUser($d->tgl_berlaku).$tgl_sampai,
				$d->poin_pengurang,
				$d->nama_mengetahui.$jbt_mengetahui,
				$d->nama_menyetujui.$jbt_menyetujui,
				$d->keterangan
			];
			$row++;
		}
		$sheet[0]=[
			'range_huruf'=>3,
			'sheet_title'=>'Data Peringatan',
			'head'=>[
				'row_head'=>1,
				'data_head'=>[
					'NO.',
					'NIK',
					'NAMA KARYAWAN',
					'JABATAN',
					'LOKASI KERJA',
					'NOMOR SURAT',
					'TANGGAL SURAT',
					'DETAIL PERINGATAN',
					'TANGGAL BERLAKU',
					'POIN PENGURANG',
					'MENGETAHUI',
					'MENYETUJUI',
					'KETERANGAN'
				],
			],
			'body'=>[
				'row_body'=>$row_body,
				'data_body'=>$body
			],
		];
		$data['data']=$sheet;
		$this->rekapgenerator->genExcel($data);
	}
}
