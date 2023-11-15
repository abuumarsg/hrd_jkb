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

class Codegenerator{
    
    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function index()
    {
        $this->redirect('not_found');
    }
    
    //===LOGIC BEGIN===//
    //main
    public function differentValueCode($table,$columncode,$columnid,$front,$value_max_where,$column_max_where,$usefor)
    {
        if ($value_max_where == '')
			return null;
		$other_list=['lembur','ijin_pribadi','ijin_dinas','perjanjian','kode_master'];
        $romawi=['01'=>'I','02'=>'II','03'=>'III','04'=>'IV','05'=>'V','06'=>'VI','07'=>'VII','08'=>'VIII','09'=>'IX','10'=>'X','11'=>'XI','12'=>'XII'];
        $query = "SELECT $columncode FROM $table WHERE $columnid = (SELECT MAX($columnid) FROM $table WHERE $column_max_where = '$value_max_where')";
        $qq = $this->CI->db->query($query)->row_array();
        $date=$this->CI->otherfunctions->getDateNow();
        $year_now=date("Y",strtotime($date));
        $month_now=date("m",strtotime($date));
        $y=$this->firstNum(4);
		$date_format=date('Ymd',strtotime($date));
		if (in_array($usefor, $other_list)) {
			$y=$this->firstNum(4);
			$date_format=date('Ymd',strtotime($date));
			if ($qq == NULL) {
				$no=$front.$date_format.$y;
			}else{
				$d_new=date('d',strtotime($date));
				$d_old=$this->getDay($front,$qq[$columncode]);
				if (($d_new != $d_old) || empty($d_old)) {
					$no=$front.$date_format.$y;
				}else{
					$num=substr($qq[$columncode], -4);
					$n1=str_replace($front, '', $qq[$columncode]);
					$date_old=substr($n1, 0, -4);
					$no=$front.$date_old.$this->magicNum($num);
				}
			}
		}else{
			$no=$front.$date_format.$y;
		}
        return $no;
    }
    public function logicGenerator($table,$columncode,$columnid,$front,$usefor,$other_var=null)
    {
        if (empty($table) || empty($columncode) || empty($columnid) || empty($usefor)) 
            return null;
        //condition
        $where_penilaian=['kuisioner','aspek_sikap','c_aspek_sikap','c_output','c_assess','a_aspek_sikap','a_output','a_assess'];
        $other_list=['lembur','ijin_pribadi','ijin_dinas','perjanjian','kode_master'];

        $romawi=['01'=>'I','02'=>'II','03'=>'III','04'=>'IV','05'=>'V','06'=>'VI','07'=>'VII','08'=>'VIII','09'=>'IX','10'=>'X','11'=>'XI','12'=>'XII'];
        $query = "SELECT $columncode FROM $table WHERE $columnid = (SELECT MAX($columnid) FROM $table)";
        $qq = $this->CI->db->query($query)->row_array();
        $date=$this->CI->otherfunctions->getDateNow();
        $year_now=date("Y",strtotime($date));
        $month_now=date("m",strtotime($date));
        if ($usefor == 'kecelakaan_kerja') {
            $y=$this->firstNum(3);
            if ($qq == NULL) {
                $no=$y.'/'.$front.'/'.$romawi[$month_now].'/'.$year_now; 
            }else{
                $tt=explode('/',$qq[$columncode]);
                $bl1=$tt[2];
                $no1=$tt[0];
                $blny=array_search($bl1, $romawi);
                if ($blny != $month_now) {
                    $no=$y.'/'.$front.'/'.$romawi[$month_now].'/'.$year_now; 
                }else{
                    $nn=$this->magicNum($no1);
                    $no=$nn.'/'.$front.'/'.$romawi[$month_now].'/'.$year_now;
                }
            }
        }elseif ($usefor == 'inventaris_karyawan') {
            $y=$this->firstNum(4);
            if ($qq == NULL) {
                $no=$y.'/'.$romawi[$month_now].'/'.$year_now; 
            }else{
                $tt=explode('/',$qq[$columncode]);
                $no1=$tt[0];
                $year_old=$tt[2];
                if ($year_now != $year_old) {
                    $no=$y.'/'.$romawi[$month_now].'/'.$year_now;
                }else{
                    $nn=$this->magicNum($no1);
                    $no=$nn.'/'.$romawi[$month_now].'/'.$year_now;
                }
            }
        }elseif ($usefor == 'mutasi'){
            $y=$this->firstNum(3);
            if ($qq == NULL) {
                $no=$y.'/'.$front.'/HRD-GA/CWM/'.$romawi[$month_now].'/'.$year_now;
            }else{
                $tt=explode('/',$qq[$columncode]);
                $no1=$tt[0];
                $year_old=$tt[5];
                if ($year_now != $year_old) {
                    $no=$y.'/'.$front.'/HRD-GA/CWM/'.$romawi[$month_now].'/'.$year_now;
                }else{
                    $nn=$this->magicNum($no1);
                    $no=$nn.'/'.$front.'/HRD-GA/CWM/'.$romawi[$month_now].'/'.$year_now;
                }
            }

        }elseif (in_array($usefor, $other_list)) {
            $y=$this->firstNum(4);
            $date_format=date('Ymd',strtotime($date));
            if ($qq == NULL) {
                $no=$front.$date_format.$y;
            }else{
                $d_new=date('d',strtotime($date));
                $d_old=$this->getDay($front,$qq[$columncode]);
                if (($d_new != $d_old) || empty($d_old)) {
                    $no=$front.$date_format.$y;
                }else{
                    $num=substr($qq[$columncode], -4);
                    $n1=str_replace($front, '', $qq[$columncode]);
                    $date_old=substr($n1, 0, -4);
                    $no=$front.$date_old.$this->magicNum($num);
                }
            }
        }elseif ($usefor == 'nik') {
            $y=$this->firstNum(4);
            $date_format=date('Ym',strtotime($date));
            if ($qq == NULL) {
                $no=$date_format.$y;
            }else{
                $th=substr($qq[$columncode],0, -6);
                $th1=date('Y',strtotime($date));
                if ($th != $th1) {
                    $no=$date_format.$y;
                }else{
                    $no=$qq[$columncode]+1;
                }
            }
        }elseif (in_array($usefor, $where_penilaian)) {
            if ($qq == NULL) {
                $nox='1'.uniqid();
                $no=$front.md5($nox);
            }else{
                $nox=$qq[$columncode].uniqid();
                $no=$front.md5($nox); 
            }
        }elseif ($usefor == 'table_name') {
            $front=strtolower($front).'_';
            $y=$this->firstNum(1);
            $date_format=date('Ymd',strtotime($date));
            $name_old=$this->getPieceTableName($qq[$columncode],2);
            $d_old=$this->getDayNameTable($name_old);
            if ($qq == NULL) {
                $no=$front.$date_format.$y;
            }else{
                if ($date_format != $d_old) {
                    $no=$front.$date_format.$y;
                }else{
                    $no=$front.($name_old+1);
                }
            }
        }elseif ($usefor == 'nik_jkb') {
            $tgl_lahir=$other_var['tgl_lahir'];
            $tgl_masuk=$other_var['tgl_masuk'];
            $lahir=str_replace('/', null, $tgl_lahir);
            $lhr1 = substr($lahir,0,4);
            $lhr2 = substr($lahir,6,2);
            $masuk=str_replace('/', null, $tgl_masuk);
            $msk1 = substr($masuk,2,2);
            $msk2 = substr($masuk,6,2);
            $no=($lhr1.''.$lhr2.''.$msk1.''.$msk2);
        }elseif ($usefor == 'berlaku_sampai') {
            $status=$other_var['status'];
            $gettanggal=$other_var['tgl_berlaku'];;
            $status = $this->db->query("SELECT berlaku FROM master_surat_perjanjian WHERE kode_perjanjian = '$status'")->result();
            foreach ($status as $s) {
                if(substr($s->berlaku,2,1) == 'H') {
                    $d = substr($s->berlaku,0,1);
                }elseif(substr($s->berlaku,3,1) == 'H'){
                    $d = substr($s->berlaku,0,2);
                }else{
                    $d = null;
                }
                if(substr($s->berlaku,2,1) == 'M') {
                    $g = substr($s->berlaku,0,1);
                }elseif(substr($s->berlaku,3,1) == 'M'){
                    $g = substr($s->berlaku,0,2);
                }else{
                    $g = null;
                }
                if(substr($s->berlaku,2,1) == 'B') {
                    $e = substr($s->berlaku,0,1);
                }elseif(substr($s->berlaku,3,1) == 'B'){
                    $e = substr($s->berlaku,0,2);
                }else{
                    $e = null;
                }
                if(substr($s->berlaku,2,1) == 'T'){
                    $f = substr($s->berlaku,0,1);
                }elseif(substr($s->berlaku,3,1) == 'T'){
                    $f = substr($s->berlaku,0,2);
                }else{
                    $f = null;
                }
            $tanggal = substr($gettanggal,0,2);
            $bulan = substr($gettanggal,3,2);
            $tahun = substr($gettanggal,6,4);
            $tgl = mktime(0, 0, 0, date($bulan)+$e, date($tanggal)+$d+($g*7), date($tahun)+$f);
            $data = date("d/m/Y", $tgl);
            }
        }
        return $no;
    }
    //sub main
    public function getPieceTableName($name,$param)
    {
        if(empty($name) || empty($param)) 
            return null;
        $new_val = null;
        $ex=explode('_', $name);
        $new_val=$ex[$param];
        return $new_val;
    }
    public function getDayNameTable($val)
    {
        if(empty($val)) 
            return null;
        $date=substr($val, 0, 8);
        return $date;
    }
    public function getDay($front,$val)
    {
        if (empty($front) || empty($val)) 
            return null;
        $n1=str_replace($front, '', $val);
        $n2=substr($n1, 0, -4);
        $dt=str_split($n2);
        if (!isset($dt[6]) || !isset($dt[7])) 
            return null;
        $day=$dt[6].$dt[7];
        return $day;
    }
    public function firstNum($val)
    {
        if (empty($val)) 
            return null;
        $zero='%0'.$val.'d';
        $depan=sprintf($zero, 1);
        return $depan;
    }
    public function magicNum($num)
    {
        if (empty($num)) 
            return null;
        $front=str_replace((int)$num, '', $num);
        $front=str_split($front);
        $n_new=$num+1;
        if (strlen($n_new) > strlen((int)$num)) {
          array_pop($front);
          $zero=implode('',$front);
          $new=$zero.$n_new;
        }else{
          $new=implode('',$front).$n_new;
        }
        return $new;
    }
    //===LOGIC END===//

    //===BLOCK CHANGE===//

    //===GET MAIN LOGIC BEGIN===//
    // public function kodeAspek()
    // {
    //     return $this->logicGenerator('master_aspek_sikap', 'kode_aspek', 'id_aspek', 'ASP','kode_master');
    // }
    // public function kodeKpi()
    // {
    //     return $this->logicGenerator('master_kpi', 'kode_kpi', 'id_kpi', 'KPI','kode_master');
    // }
    public function kodeAspekKompetensi()
    {
        return $this->logicGenerator('master_aspek_kompetensi', 'kode_aspek', 'id_aspek', 'KPT','kode_master');
    }
	public function kodeKonversiKpi()
	{
		return $this->logicGenerator('master_konversi_kpi', 'kode_konversi_kpi', 'id_konversi_kpi', 'KKPI','kode_master');
	}
	public function kodeKonversiSikap()
	{
		return $this->logicGenerator('master_konversi_sikap', 'kode_konversi_sikap', 'id_konversi_sikap', 'KSKP','kode_master');
	}
	public function kodeKonversiPresensi()
	{
		return $this->logicGenerator('master_konversi_presensi', 'kode_konversi_presensi', 'id_konversi_presensi', 'KPRN','kode_master');
	}
	public function kodeKonversiKuartal()
	{
		return $this->logicGenerator('master_konversi_kuartal', 'kode_konversi_kuartal', 'id_konversi_kuartal', 'KKRL','kode_master');
	}
	public function kodeKonversiTahunan()
	{
		return $this->logicGenerator('master_konversi_tahunan', 'kode_konversi_tahunan', 'id_konversi_tahunan', 'KTHN','kode_master');
	}
	public function kodeKonversiInsentif()
	{
		return $this->logicGenerator('master_konversi_insentif', 'kode_konversi_insentif', 'id_konversi_insentif', 'KINS','kode_master');
	}
	public function kodeKonversiGap()
	{
		return $this->logicGenerator('master_konversi_gap', 'kode_konversi_gap', 'id_konversi_gap', 'KGAP','kode_master');
	}
    public function kodeKuisionerKompetensi()
    {
        return $this->logicGenerator('master_kuisioner_kompetensi', 'kode_k_kompetensi', 'id_k_kompetensi', 'KKPT','kode_master');
    }
    public function kodeReward()
    {
        return $this->logicGenerator('master_reward', 'kode_reward', 'id_reward', 'RWD','kode_master');
    }
    public function kodePeriodePenilaian()
    {
        return $this->logicGenerator('master_periode_penilaian', 'kode_periode', 'id_periode', 'PRD','kode_master');
    }
    public function kodeLoker()
    {
        return $this->logicGenerator('master_loker', 'kode_loker', 'id_loker', 'LOK','kode_master');
    }
    public function kodeJabatan()
    {
        return $this->logicGenerator('master_jabatan', 'kode_jabatan', 'id_jabatan', 'JBT','kode_master');
    }
    public function kodeDepartement()
    {
        return $this->logicGenerator('master_departement', 'kode_departement', 'id_departement', 'DEP','kode_master');
    }
    public function kodeBagian()
    {
        return $this->logicGenerator('master_bagian', 'kode_bagian', 'id_bagian', 'BAG','kode_master');
    }
    public function kodeStatusKaryawan()
    {
        return $this->logicGenerator('master_status_karyawan', 'kode_status', 'id_status_karyawan', 'STK','kode_master');
    }
    public function kodeLevelJabatan()
    {
        return $this->logicGenerator('master_level_jabatan', 'kode_level_jabatan', 'id_level_jabatan', 'LVJBT','kode_master');
    }
    public function kodeLevelStruktur()
    {
        return $this->logicGenerator('master_level_struktur', 'kode_level_struktur', 'id_level_struktur', 'LVSTR','kode_master');
    }
    public function kodeMenu()
    {
        return $this->logicGenerator('master_menu', 'kode_menu', 'id_menu', 'MENU','kode_master');
    }
    public function kodeKuisioner()
    {
        return $this->logicGenerator('master_kuisioner', 'kode_kuisioner', 'id_kuisioner', 'KSR','kode_master');
    }
    public function kodeNotif()
    {
        return $this->logicGenerator('notification', 'kode_notif', 'id_notif', 'NTF','kode_master');
    }
    public function kodeFormAspek()
    {
        return $this->logicGenerator('master_form_aspek', 'kode_form', 'id_form', 'FRM','kode_master');
    }
    public function kodeRank()
    {
        return $this->logicGenerator('master_rank', 'kode_rank', 'id_rank', 'RNK','kode_master');
    }
    public function kodeKonsepKpi()
    {
        return $this->logicGenerator('concept_kpi', 'kode_c_kpi', 'id_c_kpi', 'CKPI','kode_master');
    }
    public function kodeKonsepSikap()
    {
        return $this->logicGenerator('concept_sikap', 'kode_c_sikap', 'id_c_sikap', 'CSKP','kode_master');
    }
    public function kodeKonsepKompetensi()
    {
        return $this->logicGenerator('concept_kompetensi', 'kode_c_kompetensi', 'id_c_kompetensi', 'CKPT','kode_master');
    }
    public function kodeAgendaKpi()
    {
        return $this->logicGenerator('agenda_kpi', 'kode_a_kpi', 'id_a_kpi', 'AKPI','kode_master');
    }
    public function kodeLogAgendaKpi()
    {
        return $this->logicGenerator('log_agenda_kpi', 'kode_l_a_kpi', 'id_l_a_kpi', 'LAKPI','kode_master');
    }
    public function kodeAgendaSikap()
    {
        return $this->logicGenerator('agenda_sikap', 'kode_a_sikap', 'id_a_sikap', 'ASKP','kode_master');
    }
    public function kodeLogAgendaSikap()
    {
        return $this->logicGenerator('log_agenda_sikap', 'kode_l_a_sikap', 'id_l_a_sikap', 'LASKP','kode_master');
    }
    public function kodeAgendaKompetensi()
    {
        return $this->logicGenerator('agenda_kompetensi', 'kode_a_kompetensi', 'id_a_kompetensi', 'AKPT','kode_master');
    }
    public function kodeLogAgendaKompetensi()
    {
        return $this->logicGenerator('log_agenda_kompetensi', 'kode_l_a_kompetensi', 'id_l_a_kompetensi', 'LAKPT','kode_master');
    }
	public function kodeRumus()
	{
		return $this->logicGenerator('master_rumus', 'kode_rumus', 'id_rumus', 'RMS','kode_master');
	}
    public function kodeAgendaReward()
    {
        return $this->logicGenerator('agenda_reward', 'kode_a_reward', 'id_a_reward', 'ARWD','kode_master');
    }
    public function kodeDokumen(){
        return $this->logicGenerator('master_dokumen', 'kode_dokumen', 'id_dokumen', 'DOC','kode_master');
    }
    public function kodeIndukGrade(){
        return $this->logicGenerator('master_induk_grade', 'kode_induk_grade', 'id_induk_grade', 'MGRD','kode_master');
    }
    public function kodeGrade(){
        return $this->logicGenerator('master_grade', 'kode_grade', 'id_grade', 'GRD','kode_master');
    }
    public function kodeMutasiKaryawan(){
        return $this->logicGenerator('master_mutasi', 'kode_mutasi', 'id_m_mutasi', 'MUT','kode_master');
    }
    public function kodeDaftarRS(){
        return $this->logicGenerator('master_daftar_rs', 'kode_master_rs', 'id_master_rs', 'RS','kode_master');
    }
    public function kodeSuratPeringatan(){
        return $this->logicGenerator('master_surat_peringatan', 'kode_sp', 'id_sp', 'SP','kode_master');
    }
    public function kodeKategoriKecelakaan(){
        return $this->logicGenerator('master_kategori_kecelakaan', 'kode_kategori_kecelakaan', 'id_kategori_kecelakaan', 'KKJ','kode_master');
    }
    public function kodeSuratPerjanjian(){
        return $this->logicGenerator('master_surat_perjanjian', 'kode_perjanjian', 'id_perjanjian', 'PKWT','kode_master');
    }
    public function kodeKelompokShift(){
        return $this->logicGenerator('master_kelompok_shift', 'kode_shift', 'id_shift', 'SHF','kode_master');
    }
    public function kodeBank(){
        return $this->logicGenerator('master_bank', 'kode', 'id_bank', 'BANK','kode_master');
    }
    public function kodeAlasanKeluar(){
        return $this->logicGenerator('master_alasan_keluar', 'kode_alasan_keluar', 'id_alasan_keluar', 'ALSN','kode_master');
    }
    public function kodeSistemPenggajian(){
        return $this->logicGenerator('master_sistem_penggajian', 'kode_master_penggajian', 'id_master_penggajian', 'GAJI','kode_master');
    }
    public function kodeHariLibur(){
        return $this->logicGenerator('master_hari_libur', 'kode_hari_libur', 'id_hari_libur', 'LBR','kode_master');
    }
    public function kodeCutiBersama(){
        return $this->logicGenerator('master_cuti_bersama', 'kode', 'id', 'HCB','kode_master');
    }
    public function kodeTarifLembur(){
        return $this->logicGenerator('master_tarif_lembur', 'kode_tarif_lembur', 'id_tarif_lembur', 'TRF','kode_master');
    }
    public function kodeTarifUmk(){
        return $this->logicGenerator('master_tarif_umk', 'kode_tarif_umk', 'id_tarif_umk', 'UMK','kode_master');
    }
    public function kodeMasterIzin(){
        return $this->logicGenerator('master_izin', 'kode_master_izin', 'id_master_izin', 'MIC','kode_master');
    }
    public function kodeMasterShift(){
        return $this->logicGenerator('master_shift', 'kode_master_shift', 'id_master_shift', 'MSFT','kode_master');
    }
    public function kodeSkMutasi(){
        return $this->logicGenerator('data_mutasi_jabatan', 'no_sk', 'id_mutasi', 'MUT','kecelakaan_kerja');
    }
    public function kodePerjanjianKerja(){
        return $this->logicGenerator('data_perjanjian_kerja', 'no_sk_baru', 'create_date', 'PKWT','kecelakaan_kerja');
    }
    public function kodePeringatanKerja(){
        return $this->logicGenerator('data_peringatan_karyawan', 'no_sk', 'id_peringatan', 'SP','kecelakaan_kerja');
    }
    public function kodeDenda(){
        return $this->logicGenerator('data_denda', 'kode', 'id_denda', 'DND','kode_master');
    }
    public function kodeGradeKaryawan(){
        return $this->logicGenerator('data_grade_karyawan', 'no_sk', 'id_grade', 'GRD','kecelakaan_kerja');
    }
    public function kodeKecelakaanKerja(){
        return $this->logicGenerator('data_kecelakaan_kerja', 'no_sk', 'id_kecelakaan', 'TC','kecelakaan_kerja');
    }
    public function kodeKaryawanNonAktif(){
        return $this->logicGenerator('data_karyawan_tidak_aktif', 'no_sk', 'id_kta', 'KNA','kecelakaan_kerja');
    }
    public function kodeIzinCuti(){
        return $this->logicGenerator('data_izin_cuti_karyawan', 'kode_izin_cuti', 'id_izin_cuti', 'KIC','kode_master');
    }
    public function kodeDataLembur(){
        return $this->logicGenerator('data_lembur', 'no_lembur', 'id_data_lembur', 'SPL','kode_master');
    }
    public function kodeDataLemburPlan(){
        return $this->logicGenerator('data_lembur_plan', 'no_lembur', 'id_data_lembur', 'PLAN','kode_master');
    }
    public function kodeLogAgendaReward()
    {
        return $this->logicGenerator('log_agenda_reward', 'kode_l_a_reward', 'id_l_a_reward', 'LARWD','kode_master');
    }
    public function tablenameCSikap($front)
    {
        return $this->logicGenerator('concept_sikap', 'nama_tabel', 'id_c_sikap', $front,'table_name');
    }
    public function tablenameCKpi($front)
    {
        return $this->logicGenerator('concept_kpi', 'nama_tabel', 'id_c_kpi', $front,'table_name');
    }
    public function tablenameASikap($front)
    {
        return $this->logicGenerator('log_agenda_sikap', 'nama_tabel', 'id_l_a_sikap', $front,'table_name');
    }
    public function tablenameAKpi($front)
    {
        return $this->logicGenerator('log_agenda_kpi', 'nama_tabel', 'id_l_a_kpi', $front,'table_name');
    }
    public function nikJkb($t_in,$t_b)
    {
        $other_var=['tgl_masuk'=>$t_in,'tgl_lahir'=>$t_b];
        return $this->logicGenerator('karyawan', 'nik', 'id_karyawan', null,'nik_jkb',$other_var);
    }
    public function tglPerjanjian($t_in,$stt)
    {
        $other_var=['tgl_berlaku'=>$t_in,'status'=>$stt];
        return $this->logicGenerator('data_perjanjian_kerja', 'nik', 'id_p_kerja', null,'berlaku_sampai',$other_var);
    }
    public function kodeKendaraanPD(){
        return $this->logicGenerator('master_pd_kendaraan', 'kode', 'id_pd_kendaraan', 'KPD','kode_master');
    }
    public function kodeBBMPD(){
        return $this->logicGenerator('master_pd_bbm', 'kode', 'id_pd_bbm', 'BBM','kode_master');
    }
    public function kodeKategoriPD(){
        return $this->logicGenerator('master_pd_kategori', 'kode', 'id_pd_kategori', 'KAPD','kode_master');
    }
    public function kodeDKTPD(){
        return $this->logicGenerator('master_pd_detail_kategori', 'kode', 'id_pd_detail', 'DKT','kode_master');
    }
    public function kodePerjalananDinas(){
        return $this->logicGenerator('data_perjalanan_dinas', 'no_sk', 'id_pd', 'PERD','kecelakaan_kerja');
    }
    public function kodeJarakPlant(){
        return $this->logicGenerator('master_pd_jarak_plant', 'kode', 'id_jarak_plant', 'JAP','kode_master');
    }
    public function kodeTransaksiNonKaryawan(){
        return $this->logicGenerator('transaksi_non_karyawan', 'nomor', 'id', 'TRNS','kecelakaan_kerja');
    }
    public function kodeAngsuran(){
        return $this->logicGenerator('data_angsuran', 'kode_angsuran', 'id_angsuran', 'ANG','kode_master');
    }
    //switch code
    public function switchCode($usage)
    {
        if (empty($usage)) 
            return null;
        $var=null;
        switch($usage){
            case 'kpi' : $var = $this->kodeKpi(); break;
            case 'aspek' : $var = $this->kodeAspek(); break;
            case 'loker' : $var = $this->kodeLoker(); break;
        }
        return $var;
    }

    //===GET MAIN LOGIC END===//

    //===BLOCK CHANGE===//

    //===SECURITY BEGIN===//
    public function encryptChar($plain)
    {
        if (empty($plain))
            return null;
        $new_val=base64_encode(serialize($plain));
        return $new_val;
    }
    public function decryptChar($plain)
    {
        if (empty($plain))
            return null;
        if (!@unserialize(base64_decode($plain))) {
			return null;
		}
        $new_val=unserialize(base64_decode($plain));
        return $new_val;
    }
	public function jwtParser($token)
	{
		return json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
	}
    public function genPassword($plain)
    {
        if (empty($plain))
            return null;
        $new_val=hash('sha512', $plain);
        return $new_val;
    }
    public function genToken($length)
    {
        if (empty($length) || !is_numeric($length)) 
            return null;
        $new_val=bin2hex(random_bytes($length).uniqid());
        return $new_val;
    }
    public function matchAdminAuth($uname,$pass)
    {
        if (empty($uname) || empty($pass))
            redirect('auth/login');
        $pass=hash('sha512', $pass);
        $cek=$this->CI->model_admin->adm_cek($uname,$pass);
        if (isset($cek)) {
            $data=$cek;
        }else{
            $data=null;
        }
        return $data;
    }
    public function getPin($length,$use)
    {
        if (empty($length) || empty($use))
            return null;
        if ($use == 'full') {
            $string = '0123456789abcdefghijklmnopqrstuvwxyz';
            $string = $string.strtoupper($string);
        }elseif ($use == 'number') {
            $string = '0123456789';
        }elseif ($use == 'letter') {
            $string = 'abcdefghijklmnopqrstuvwxyz';
            $string = $string.strtoupper($string);
        }else{
            return null;
        }
        $panjang = strlen($string);
        $new_val = '';
        for ($i = 0; $i < $length; $i++) {
            $new_val .= $string[rand(0, $panjang - 1)];
        }
        return $new_val;
    }
    //===SECURITY END===//

/*++++++++++++++++++++++++++++++++++++++++++++= Sourch Putra S. Bud =++++++++++++++++++++++++++++++++++++++++++++ */
/*++++++++++++++++++++++++++++++ date 02/04/2019 	++++++++++++++++++++++++++++++*/
    public function kodeIndukTunjangan(){
        return $this->logicGenerator('master_induk_tunjangan', 'kode_induk_tunjangan', 'id_induk_tunjangan', 'MTJG','kode_master');
    }
    public function kodeTunjangan(){
        return $this->logicGenerator('master_tunjangan', 'kode_tunjangan', 'id_tunjangan', 'TJG','kode_master');
    }
    public function kodePeriodePenggajian(){
        return $this->logicGenerator('data_periode_penggajian', 'kode_periode_penggajian', 'id_periode_penggajian', 'PRP','kode_master');
    }
    public function kodeIntensif(){
        return $this->logicGenerator('master_insentif', 'kode_insentif', 'id_insentif', 'ITS','kode_master');
    }
    public function kodePeriodePenggajianDetail(){
        return $this->logicGenerator('data_periode_penggajian_detail', 'kode_periode_detail', 'id_periode_detail', 'PRPD','kode_master');
    }
    public function kodebpjs(){
        return $this->logicGenerator('master_bpjs', 'kode_bpjs', 'id_bpjs', 'BPJS','kode_master');
    }
    public function kodePinjaman(){
        return $this->logicGenerator('data_pinjaman', 'kode_pinjaman', 'id_pinjaman', 'PNJ','kode_master');
    }
    public function kodeAngsuranDenda(){
        return $this->logicGenerator('data_denda_angsuran', 'kode_angsuran', 'id_angsuran', 'ANGS','kode_master');
    }
    public function kodePtkp(){
        return $this->logicGenerator('master_ptkp', 'kode_ptkp', 'id_ptkp', 'PTKP','kode_master');
    }
    public function kodePph(){
        return $this->logicGenerator('master_pph', 'kode_pph', 'id_pph', 'PPH','kode_master');
    }
    public function kodeTarifJabatan(){
        return $this->logicGenerator('master_tarif_jabatan', 'kode_tarif_jabatan', 'id_tarif_jabatan', 'TJB','kode_master');
    }
    public function kodebpjk(){
        return $this->logicGenerator('data_bpjs', 'kode_k_bpjs', 'id_k_bpjs', 'BPJK','kode_master');
    }
    public function kodePeriodeLembur(){
        return $this->logicGenerator('data_periode_lembur', 'kode_periode_lembur', 'id_periode_lembur', 'PRL','kode_master');
    }
    public function kodePeriodeLemburDetail(){
        return $this->logicGenerator('data_periode_lembur_detail', 'kode_periode_detail', 'id_periode_detail', 'PRLD','kode_master');
    }
    public function kodePendukungLain(){
        return $this->logicGenerator('data_pendukung_lain', 'kode_pen_lain', 'id_pen_lain', 'PLL','kode_master');
    }
    public function kodePetugasPayroll()
    {
        return $this->logicGenerator('master_petugas_payroll', 'kode_petugas_payroll', 'id_petugas_payroll', 'MPP','kode_master');
    }
    public function kodePetugasPayrollLembur()
    {
        return $this->logicGenerator('master_petugas_lembur', 'kode_petugas_lembur', 'id_petugas_lembur', 'MPL','kode_master');
    }
    public function kodePetugasPPH()
    {
        return $this->logicGenerator('master_petugas_pph', 'kode_petugas_pph', 'id_petugas_pph', 'MPPH','kode_master');
    }
    public function kodePeriodePenggajianHarian(){
        return $this->logicGenerator('data_periode_penggajian_harian', 'kode_periode_penggajian_harian', 'id_periode_penggajian_harian', 'PRH','kode_master');
    }
    public function kodePeriodePenggajianHarianDetail(){
        return $this->logicGenerator('data_periode_penggajian_harian_detail', 'kode_periode_detail', 'id_periode_detail', 'PRHD','kode_master');
    }
    public function kodeKuotaLembur(){
        return $this->logicGenerator('master_kuota_lembur', 'kode', 'id_kuota_lembur', 'TKL','kode_master');
    }
    public function kodeMateriSelfLearning(){
        return $this->logicGenerator('learn_materi', 'kode', 'id', 'SLM','kode_master');
    }
    public function kodeSoalSelfLearning(){
        return $this->logicGenerator('learn_soal', 'kode', 'id', 'SOAL','kode_master');
    }
    public function kodeSoalProjectSelfLearning(){
        return $this->logicGenerator('learn_soal_project', 'kode', 'id', 'SOALP','kode_master');
    }
    public function kodeFileSelfLearning(){
        return $this->logicGenerator('learn_file_materi', 'kode', 'id', 'FLX','kode_master');
    }
    public function kodePelatihan(){
        return $this->logicGenerator('learn_keikutsertaan', 'kode_pelatihan', 'id', 'PLT','kode_master');
    }
//========================== PENILAIAN KINERJA =========================
	public function kodeBatasanPoin()
	{
		return $this->logicGenerator('master_jenis_batasan_poin', 'kode_batasan_poin', 'id_batasan_poin', 'BP','kode_master');
	}
	public function kodeKpi()
	{
		return $this->logicGenerator('master_kpi', 'kode_kpi', 'id_kpi', 'KPI','kode_master');
	}
	public function kodeAspek()
	{
		return $this->logicGenerator('master_aspek_sikap', 'kode_aspek', 'id_aspek', 'ASP','kode_master');
	}

}
