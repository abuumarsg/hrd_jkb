<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Formatter
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

class Formatter {
    
    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
    }
	//count work time
	public function getWorkTime($s)
    {
        if(empty($s)) 
            return null;
        $e=gmdate("Y-m-d", time() + 3600*(7));
        $diff = abs(strtotime($s) - strtotime($e));
        $tahun = floor($diff / (365*60*60*24));
        $bulan = floor(($diff - $tahun * 365*60*60*24) / (30*60*60*24));
        $hari = floor(($diff - $tahun * 365*60*60*24 - $bulan*30*60*60*24)/ (60*60*24));
        if ($tahun == 0) {
            $tahun=NULL;
        }else{
            if ($bulan != 0 || $hari != 0) {
                $tahun=$tahun.' Tahun, ';
            }else{
                $tahun=$tahun.' Tahun.';
            }
        }
        if ($bulan == 0) {
            $bulan=NULL;
        }else{
            if ($tahun != 0 || $hari != 0) {
                $bulan=$bulan.' Bulan, ';
            }else{
                $bulan=$bulan.' Bulan.';
            }
        }
        if ($hari == 0) {
            $hari=NULL;
        }else{
            $hari=$hari.' Hari.';
        }
        $work_t=$tahun.$bulan.$hari;
        return $work_t;
    }

	//penilaian
	public function getDateYearPeriode($start,$end,$year,$date_batas)
	{
		if (empty($start) || empty($end) || empty($year) || empty($date_batas)) 
			return null;

		$pack=[];
		$pack1=[];
		$bulan=$this->getMonth();
		$x=1;
		for ($i=1; $i <=12 ; $i++) { 
			if ($start > $end || $start == $end) {
				if ($i >= $start) {
					array_push($pack,($year-1).'-'.$this->CI->otherfunctions->addFrontZero($i).'-'.$date_batas);
				}
				if ($i<=$end) {
					array_push($pack1,$year.'-'.$this->CI->otherfunctions->addFrontZero($i).'-'.$date_batas);
				}  
			}else{
				if ($i >= $start && $i <= $end) {
					array_push($pack,$year.'-'.$this->CI->otherfunctions->addFrontZero($i).'-'.$date_batas);
				}
			}
		}
		$pack=array_merge($pack,$pack1);
		
		return $pack;
	}
    public function getNumberFloat($val,$count = 2)
    {
        if ($val == '')
            return 0;
        $new_val=null;
        if (!empty(strpos($val, '.'))) {
            $new_val=number_format($val,$count,',',',');
        }else{
            $new_val=$val;
        }
        return $new_val;
    }
	public function getNilaiOut($nilai)
	{
        if(empty($nilai)) 
            return null;
		$nilai=str_replace('{', '', $nilai);
		$nilai=str_replace('}', '', $nilai);
		$nil=explode(':', $nilai);
		$nil_baru=$nil[1];
		return $nil_baru;
	}
	public function getPartisipanUserNilaiOut($nilai)
	{
        if(empty($nilai)) 
            return null;
		$nilai=str_replace('{', '', $nilai);
		$nilai=str_replace('}', '', $nilai);
		$nil=explode(':', $nilai);
		$nil2=explode(' ', $nil[0]);
		if ($nil2[0] == 'KAR') {
			$par_baru=$nil2[1];
		}else{
			$par_baru=NULL;
		}
		return $par_baru;
	}
	public function getPartisipanSikapFirst($p)
	{
        if(empty($p)) 
            return null;
		$p=explode(':', $p);
		return $p[0];
	}
	public function getPartisipanSikapEnd($p)
	{
        if(empty($p)) 
            return null;
		$p=explode(':', $p);
		return $p[1];
	}
	//date format
    public function getMonth()
    {
        $month=[
            '01'=>'Januari',
            '02'=>'Februari',
            '03'=>'Maret',
            '04'=>'April',
            '05'=>'Mei',
            '06'=>'Juni',
            '07'=>'Juli',
            '08'=>'Agustus',
            '09'=>'September',
            '10'=>'Oktober',
            '11'=>'November',
            '12'=>'Desember',
        ];
        return $month;
    }
    public function getNameOfMonthByPeriode($start,$end,$year)
    {
        if (empty($start) || empty($end)) 
            return null;
        $pack=[];
        $pack1=[];
        $bulan=$this->getMonth();
        $x=1;
        for ($i=1; $i <=12 ; $i++) { 
            if ($start > $end || $start == $end) {
                if ($i >= $start) {
                    array_push($pack,$bulan[$this->CI->otherfunctions->addFrontZero($i)].' - '.$year);
                }
                if ($i<=$end) {
                    array_push($pack1,$bulan[$this->CI->otherfunctions->addFrontZero($i)].' - '.($year+1));
                }               
            }else{
                if ($i >= $start && $i <= $end) {
                    array_push($pack,$bulan[$this->CI->otherfunctions->addFrontZero($i)]);
                }
            }
        }
        $pack=array_merge($pack,$pack1);
        return $pack;
    }
    public function getQuartalList()
    {
        $var=['1'=>'Quartal 1','2'=>'Quartal 2','3'=>'Quartal 3'];
        return $var;
    }
    public function getQuartal($key)
    {
        return $this->CI->otherfunctions->getVarFromArrayKey($key,$this->getQuartalList());
    }
    public function getYear()
    {
        $first=2017;
        $end=date('Y', strtotime(date('Y',strtotime($this->CI->otherfunctions->getDateNow())) . ' +2 year'));
        $date=range($first,$end);
        $year=[];
        foreach ($date as $d) {
            $year[$d]=$d;
        }
        return $year;
    }
    public function getYearAll()
    {
        $first=1990;
        $end=date('Y', strtotime(date('Y',strtotime($this->CI->otherfunctions->getDateNow())) . ' +2 year'));
        $date=range($first,$end);
        $year=[];
        foreach ($date as $d) {
            $year[$d]=$d;
        }
        return $year;
    }
    public function getDateFromRange($val,$param,$time='yes')
    {
        if(empty($val) || empty($param)) 
            return null;
        $ex=explode(' - ',$val);
        $new_val=null;
        if (!isset($ex))
            return null;
        if($time=='yes'){
            if ($param == 'start') {
                $new_val=$this->getDateTimeFormatDb($ex[0]);
            }elseif ($param == 'end') {
                $new_val=$this->getDateTimeFormatDb($ex[1]);
            }else{
                return null;
            }
        }else{
            if ($param == 'start') {
                $new_val=$this->getDateFormatDb($ex[0]);
            }elseif ($param == 'end') {
                $new_val=$this->getDateFormatDb($ex[1]);
            }else{
                return null;
            }
        }
        return $new_val;
    }
	// public function getDateFormatDb($date)
	// {
    //     if(empty($date)) 
    //         return null;
	// 	$date=explode('/', $date);
    //     // if ($date[0] > 12) {
    //         $new_date=$date[2].'-'.$date[1].'-'.$date[0];
    //     // }else{
    //         // $new_date=$date[2].'-'.$date[0].'-'.$date[1];
    //     // }		
	// 	return $new_date;
	// }
	public function getDateFormatDb($date)
	{
		if(empty($date)) 
			return null;
		if (!empty(strpos($date, '/'))) {
			$date=explode('/', $date);
			$date=$date[2].'-'.$date[1].'-'.$date[0];
		}elseif (!empty(strpos($date, '-'))) {
			$date=date('Y-m-d',strtotime($date));
		}		
		return $date;
	}
	public function getDateFormatUser($date)
	{
        if(empty($date)) 
            return null;
		$date=explode('-', date('Y-m-d',strtotime($date)));
		$new_date=$date[2].'/'.$date[1].'/'.$date[0];
		return $new_date;
	}
	public function getDateMonthFormatUser($date)
	{
        if(empty($date)) 
            return null;
		$date1=explode('-', date('Y-m-d',strtotime($date)));
		$new_date=$date1[2].' '.$this->getNameOfMonth($date1[1]).' '.$date1[0];
		return $new_date;
	}
	public function getDayDateFormatUserId($date)
	{
        if(empty($date)) 
            return null;
		$date1=explode('-', date('Y-m-d',strtotime($date)));
		$new_date=$this->getNameOfDay($date).', '.$date1[2].' '.$this->getNameOfMonth($date1[1]).' '.$date1[0];
		return $new_date;
	}
	public function getDateTimeFormatDb($datetime)
	{
        if(empty($datetime)) 
            return null;
		$datetime=explode(' ', $datetime);
		$date=explode('/', $datetime[0]);
		if (isset($datetime[1])) {
			$time=$datetime[1];
		}else{
			$time='00:00:00';
		}
		$new_datetime=$date[2].'-'.$date[1].'-'.$date[0].' '.$time;
		return $new_datetime;
	}
	public function getDateTimeFormatUser($datetime,$second=null)
	{
        if(empty($datetime)) 
            return null;
        if(empty($second)){
            $datetime=explode(' ', date('Y-m-d H:i:s',strtotime($datetime)));
        }else{
            $datetime=explode(' ', date('Y-m-d H:i',strtotime($datetime)));
        }
		$date=explode('-', $datetime[0]);
		if (isset($datetime[1])) {
			$time=$datetime[1];
		}else{
            if(empty($second)){
                $time='00:00:00';                
            }else{
                $time='00:00';
            }
		}
		$new_datetime=$date[2].'/'.$date[1].'/'.$date[0].' '.$time;
		return $new_datetime;
	}
	public function getDateTimeMonthFormatUser($datetime,$second=null)
	{
        if(empty($datetime)) 
            return null;
		// $datetime=explode(' ', date('Y-m-d H:i:s',strtotime($datetime)));
        if(empty($second)){
            $datetime=explode(' ', date('Y-m-d H:i:s',strtotime($datetime)));
        }else{
            $datetime=explode(' ', date('Y-m-d H:i',strtotime($datetime)));
        }
		$date=explode('-', $datetime[0]);
		if (isset($datetime[1])) {
			$time=$datetime[1];
		}else{
            if(empty($second)){
                $time='00:00:00';                
            }else{
                $time='00:00';
            }
		}
		$new_datetime=$date[2].' '.$this->getNameOfMonth($date[1]).' '.$date[0].' '.$time;
		return $new_datetime;
	}
	public function getNameOfDay($date){
        if(empty($date)) 
            return null;
        $name_day = date('l',strtotime($date));
        $day = '';
        $day = ($name_day=='Sunday')?'Minggu':$day;
        $day = ($name_day=='Monday')?'Senin':$day;
        $day = ($name_day=='Tuesday')?'Selasa':$day;
        $day = ($name_day=='Wednesday')?'Rabu':$day;
        $day = ($name_day=='Thursday')?'Kamis':$day;
        $day = ($name_day=='Friday')?'Jumat':$day;
        $day = ($name_day=='Saturday')?'Sabtu':$day;
        return $day;
    }
    public function getNameOfDayNumber($date){
        if(empty($date)) 
            return null;
        $name_day = date('l',strtotime($date));
        $day = '';
        $day = ($name_day=='Sunday')?7:$day;
        $day = ($name_day=='Monday')?1:$day;
        $day = ($name_day=='Tuesday')?2:$day;
        $day = ($name_day=='Wednesday')?3:$day;
        $day = ($name_day=='Thursday')?4:$day;
        $day = ($name_day=='Friday')?5:$day;
        $day = ($name_day=='Saturday')?6:$day;
        return $day;
    }
    public function getNameOfMonth($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = strtolower(trim($inputmonth));
        switch($month){
            case '1' : $return = 'Januari'; break;
            case '01' : $return = 'Januari'; break;
            case 'januari' : $return = 'Januari'; break;
            case 'january' : $return = 'Januari'; break;
            case '2' : $return = 'Februari'; break;
            case '02' : $return = 'Februari'; break;
            case 'februari' : $return = 'Februari'; break;
            case 'february' : $return = 'Februari'; break;
            case '3' : $return = 'Maret'; break;
            case '03' : $return = 'Maret'; break;
            case 'maret' : $return = 'Maret'; break;
            case 'march' : $return = 'Maret'; break;
            case '4' : $return = 'April'; break;
            case '04' : $return = 'April'; break;
            case 'april' : $return = 'April'; break;
            case '5' : $return = 'Mei'; break;
            case '05' : $return = 'Mei'; break;
            case 'may' : $return = 'Mei'; break;
            case '6' : $return = 'Juni'; break;
            case '06' : $return = 'Juni'; break;
            case 'juni' : $return = 'Juni'; break;
            case 'june' : $return = 'Juni'; break;
            case '7' : $return = 'Juli'; break;
            case '07' : $return = 'Juli'; break;
            case 'juli' : $return = 'Juli'; break;
            case 'july' : $return = 'Juli'; break;
            case '8' : $return = 'Agustus'; break;
            case '08' : $return = 'Agustus'; break;
            case 'agt' : $return = 'Agustus'; break;
            case 'agu' : $return = 'Agustus'; break;
            case 'aug' : $return = 'Agustus'; break;
            case 'agustus' : $return = 'Agustus'; break;
            case 'august' : $return = 'Agustus'; break;
            case '9' : $return = 'September'; break;
            case '09' : $return = 'September'; break;
            case 'sep' : $return = 'September'; break;
            case 'sept' : $return = 'September'; break;
            case 'september' : $return = 'September'; break;
            case '10' : $return = 'Oktober'; break;
            case 'oct' : $return = 'Oktober'; break;
            case 'oktober' : $return = 'Oktober'; break;
            case 'october' : $return = 'Oktober'; break;
            case '11' : $return = 'November'; break;
            case 'nov' : $return = 'November'; break;
            case 'nopember' : $return = 'November'; break;
            case 'november' : $return = 'November'; break;
            case '12' : $return = 'Desember'; break;
            case 'dec' : $return = 'Desember'; break;
            case 'desember' : $return = 'Desember'; break;
            case 'december' : $return = 'Desember'; break;
            default : $return = $inputmonth; break;
        }
        return $return;
    }
    public function getFormatMoneyUser($var,$par=0){
        if($var == '')
            return 'Rp. '.number_format(0,$par,',','.');
        $var='Rp. '.number_format($var,$par,',','.');
        return $var;
    }
    public function getFormatMoneyUserReq($var){
        if($var == '')
            return number_format(0,0,',','.');
        $varx=(is_numeric($var) ? number_format($var,0,',','.') : $var);
        return $varx;
    }
    public function getFormatMoneyDb($var){
        if(empty($var))
            return null;
        $var=strtoupper($var);
        $var= str_replace('RP. ', '', $var);
        $var= str_replace('.', '', $var);
        $var= str_replace(',', '.', $var);
        return $var;
    }
    public function timeFormatUser($var)
    {
        if(empty($var))
            return $this->CI->otherfunctions->getMark();
        $var=substr($var,0, -3);
        return $var;
    }
    public function timeFormatDb($var)
    {
        if(empty($var))
            return null;
        $var=$var.':00';
        return $var;
    }
    // public function getCountDateRange($start,$end)
    // {
    //     if (empty($start) || empty($end))
    //         return null;
    //     $pack=[];
    //     if (!empty(strpos($start, '/'))) {
    //         $start=date_create($this->getDateFormatDb($start));
    //     }
    //     if (!empty(strpos($end, '/'))) {
    //         $end=date_create($this->getDateFormatDb($end));
    //     }
    //     $diff=date_diff($start,$end);
    //     $diff=$diff->format('%a');
    //     $tahun = ($diff / 365) ; 
    //     $tahun = floor($tahun);
    //     $bulan = ($diff % 365) / 30.5; 
    //     $bulan = floor($bulan);
    //     $hari = ($diff % 365) % 30.5;
    //     $pack=['tahun'=>$tahun,'bulan'=>$bulan,'hari'=>$hari];
    //     return $pack;
    // }
    public function getCountDateRange($start,$end)
    {
        if (empty($start) || empty($end))
            return null;
        $pack=[];
        if (!empty(strpos($start, '/'))) {
            $start=date_create($this->getDateFormatDb($start));
        }
        if (!empty(strpos($end, '/'))) {
            $end=date_create($this->getDateFormatDb($end));
        }
        $bulan=abs((date('Y', strtotime($end)) - date('Y', strtotime($start)))*12 + (date('m', strtotime($end)) - date('m', strtotime($start))));
        $start=date_create($start);
        $end=date_create($end);
        $diff=date_diff($start,$end);
         
        $diffx=$diff->format('%a');
        $tahun = ($diffx / 365) ; 
        $tahun = floor($tahun);
        $minggu= floor(($diffx+1) / 7); 
        $bulan_pay = floor($diff->format('%y') * 12 + $diff->format('%m'));
        $hari = ($diffx % 365) % 60.5;
        // $hari = ($diffx % 365) % 30.5;
        $pack=['tahun'=>$tahun,'bulan'=>$bulan,'bulan_pay'=>$bulan_pay,'hari'=>$hari,'minggu'=>$minggu];
        return $pack;
    }
    public function getNameOfMonthByPeriodeNum($start,$end,$year)
    {
        if (empty($start) || empty($end)) 
            return null;
        $pack=[];
        $pack1=[];
        $bulan=$this->getMonth();
        $x=1;
        for ($i=1; $i <=12 ; $i++) { 
            if ($start > $end || $start == $end) {
                if ($i >= $start) {
                    array_push($pack,$this->CI->otherfunctions->addFrontZero($i));
                }
                if ($i<=$end) {
                    array_push($pack1,$this->CI->otherfunctions->addFrontZero($i));
                }               
            }else{
                if ($i >= $start && $i <= $end) {
                    array_push($pack,$this->CI->otherfunctions->addFrontZero($i));
                }
            }
        }
        $pack=array_merge($pack,$pack1);
        return $pack;
    }
    public function limit_words($string, $word_limit=10)
    {
        $words = explode(" ",$string);
        return implode(" ",array_splice($words,0,$word_limit));
    }
    public function changeNilaiCustom($val,$custom = 0)
    {
        if(empty($val))
            return $custom;
        if($val>0){
            $new_val = $this->CI->formatter->getNumberFloat($val);
        }else{
            $new_val = $custom;
        }
        return $new_val;
    }
    public function zeroPadding($val)
    {
        $new_val = sprintf("%02d", $val);
        return $new_val;
    }
    public function getValHastagPrint($val){
        if(empty($val))
            return null;
        if (!empty($val)) {
            $ex1=explode('#',$val);
            if (isset($ex1)) {
                $new_val1=[];
                $no1=1;
                foreach ($ex1 as $exp1) {
                    $new_val1[$no1]=$no1.'. '.$exp1;
                    $no1++;
                }
                $new_val=implode('<w:br/>',$new_val1);
            }else{
                $new_val='1.'.$new_val;
            }
        }
        return $new_val;
    }
    public function getValHastagView($val){
        if(empty($val))
            return null;
        if (!empty($val)) {
            $ex1=explode('#',$val);
            if (isset($ex1)) {
                $new_val1=[];
                $no1=1;
                foreach ($ex1 as $exp1) {
                    $new_val1[$no1]=$no1.'. '.$exp1;
                    $no1++;
                }
                $new_val=implode('<br>',$new_val1);
            }else{
                $new_val='1.'.$new_val;
            }
        }
        return $new_val;
    }
    public function kataTerbilang($input_number){
        $input_number = abs($input_number);
        $number = ["", "satu", "dua", "tiga", "empat", "lima",
            "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp = "";

        if ($input_number < 12) {
            $temp = " " . $number[$input_number];
        } else if ($input_number < 20) {
            $temp = $this->kataTerbilang($input_number - 10) . " ".'belas';
        } else if ($input_number < 100) {
            $temp = $this->kataTerbilang($input_number / 10) . " ".'puluh' . $this->kataTerbilang($input_number % 10);
        } else if ($input_number < 200) {
            $temp = " ".'seratus' . $this->kataTerbilang($input_number - 100);
        } else if ($input_number < 1000) {
            $temp = $this->kataTerbilang($input_number / 100) . " ".'ratus' . $this->kataTerbilang($input_number % 100);
        } else if ($input_number < 2000) {
            $temp = " ".'seribu' . $this->kataTerbilang($input_number - 1000);
        } else if ($input_number < 1000000) {
            $temp = $this->kataTerbilang($input_number / 1000) . " ".'ribu' . $this->kataTerbilang($input_number % 1000);
        } else if ($input_number < 1000000000) {
            $temp = $this->kataTerbilang($input_number / 1000000) . " ".'juta' . $this->kataTerbilang($input_number % 1000000);
        } else if ($input_number < 1000000000000) {
            $temp = $this->kataTerbilang($input_number / 1000000000) . " ".'milyar' . $this->kataTerbilang(fmod($input_number, 1000000000));
        } else if ($input_number < 1000000000000000) {
            $temp = $this->kataTerbilang($input_number / 1000000000000) . " ".'trilyun' . $this->kataTerbilang(fmod($input_number, 1000000000000));
        }
        return ucwords($temp);
    }
    public function getTimeFormatUser($time, $timezone = null)
    {
        if(empty($time))
            return null;
        $zone = (!empty($timezone)) ? ' '.$timezone : '';
        $new_val = substr($time,0,-3);
        return $new_val.$zone;
    }
    public function dateLoop($s,$e)
    {
        $date = $s;
        while ($date <= $e)
        {
            $m=date('m',strtotime($date));
            $d=date('d',strtotime($date));
            $y=date('Y',strtotime($date));
            $period[$y][$m][$d]=$d;
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }
        return $period;
    }
	public function dateLoopFull($s,$e)
    {
        $date = $s;
        while ($date <= $e)
        {
            $period[$date]=$date;
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }
        return $period;
	}
    public function dateLoopJkb($s,$e,$shift=null)
    {
        $date = $s;
        while ($date <= $e)
        {
            $m=date('m',strtotime($date));
            $d=date('d',strtotime($date));
            $y=date('Y',strtotime($date));
            if (isset($shift)) {
                foreach ($shift as $kode_shift => $day) {
                    if (in_array($this->getNameOfDayNumber($date),$day)) {
                        $period[$y][$m][$d]=$kode_shift;
                    }
                }
            }else{
                $period[$y][$m][$d]=$d;
            }
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }
        return $period;
    }
    public function getPeriodeJadwal($range,$shift)
    {
        if (empty($range))
            return null;
        $ex=explode(' - ',$range);
        if (!isset($ex))
            return null;
        $start_date=$this->getDateTimeFormatDb($ex[0]);
        $end_date=$this->getDateTimeFormatDb($ex[1]);
        $periode=$this->dateLoopJkb($start_date,$end_date,$shift);
        return $periode;
    }
    public function getCols2($x)
    {
        $cols=[];
        $j=1;
        for ($i=1; $i <=31 ; $i++) { 
            if ($i < 10) {
                $i='0'.$i; 
            }
            $cols[$i]='tgl_'.$j;
            $j++;
        }
        return $cols[$x];
    }
    public function getDateCols($x)
    {
        $cols=[];
        $j=1;
        for ($i=1; $i <=31 ; $i++) { 
            if ($i < 10) {
                $i='0'.$i;
            }
            $cols['tgl_'.$j]=$i;
            $j++;
        }
        return $cols[$x];
    }
    public function getColumn($val,$param)
    {
        $cols=[];
        $j=1;
        for ($i=1; $i <=31 ; $i++) { 
            if ($i < 10) {
                $i='0'.$i; 
            }
            $cols[$i]=$param.$j;
            $j++;
        }
        return $cols[$val];
    }
    public function getTimeDb($time)
    {
        $time=str_replace('.', ':', $time);
        $time=date('H:i:s',strtotime($time));
        return $time;
    }
    public function difHalfTime($s,$e)
    {
        $s=strtotime($s);
        $e=strtotime($e);
        $dif=($e-$s);
        if ($e<$s) {
            $half=((($dif/60)/60)+24)/2;
        }else{
            $half=((($dif/60)/60))/2;
        }
        return $half;
    }
    public function jam($time,$int,$op)
    {
        if ($op == '-') {
           $jam=strtotime($time) - (60*60*$int); 
        }else{
            $jam=strtotime($time) + (60*60*$int);
        }
        return date('H:i:s',$jam);
    }
    public function formatDateTimeForDb($inputdatetime)
    {
        if(empty($inputdatetime)) 
            return null;
        $datetime = explode('/',trim($inputdatetime));
        if(count($datetime) > 1)
            $inputdatetime = str_replace ("/", "-", $inputdatetime);
        $datetime = explode(' ',trim($inputdatetime));
        $return = null;
        if(strlen($datetime[0]) > 9){
            $date = explode('-',trim($datetime[0]));
            if(strlen($date[1]) > 2) 
                if(strlen($date[0])>3)//ex:2014-Jan-15
                    $return = $date[0]."-".$this->getNameOfMonth($date[1])."-".$this->getTwoDigit($date[2]).(isset($datetime[1]) ? " ".$datetime[1]:"");
                else//ex:15-Jan-2014
                    $return = $date[2]."-".$this->getNameOfMonth($date[1])."-".$this->getTwoDigit($date[0]).(isset($datetime[1]) ? " ".$datetime[1]:"");
            else{ 
                if(strlen($date[0])>3)//ex:2014-01-15
                    $return = $inputdatetime;
                else//ex:15-01-2014
                    $return = $date[2]."-".$date[1]."-".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
            }
        }else{
            if(strlen($datetime[0]) > 3){ //ex: 2014 Jan 15 | 2014 01 15
                $return = $datetime[0]."-".$this->getNameOfMonth($datetime[1])."-".$this->getTwoDigit($datetime[2]).(isset($datetime[3]) ? " ".$datetime[3]:"");
            }else{ //ex: 15 Jan 2014 | 15 01 2014
                $return = $datetime[2]."-".$this->getNameOfMonth($datetime[1])."-".$this->getTwoDigit($datetime[0]).(isset($datetime[3]) ? " ".$datetime[3]:"");
            }
        }
        return $return;
    }
    public function getTwoDigit($input_number){
        $return = "00";
        if(strlen(trim($input_number)) == 1){
            $return = "0".$input_number;
        }else{
            $return = substr($input_number, 0, 2);
        }
        return $return;
    }
    public function formatDatePresensiForDb($date)
    {
        $ret=null;
        if (empty($date))
            return $ret;
        $date=explode('/',$date);
        if (isset($date[0]) && isset($date[1]) && isset($date[2])) {
            if ($date[1] < 10 ) {
                $date[1]='0'.$date[1];
            }
            if ($date[0] < 10 ) {
                $date[0]='0'.$date[0];
            }
            $ret=$date[2].'-'.$date[0].'-'.$date[1];
        }
        return $ret;
    }
    public function formatDateImportPresensiString($date)
    {
        $ret=null;
        if (empty($date))
            return $ret;
        $date=explode('-',$date);
        if (isset($date[0]) && isset($date[1]) && isset($date[2])) {
            $month=$this->getNameOfMonthRekap($date[1]);
            // if ($month < 10 ) {
            //     $month=str_replace('0','',$month);
            // }
            if ($date[0] < 10 ) {
                $date[0]=str_replace('0','',$date[0]);
            }
            $ret=$month.'/'.$date[0].'/'.$date[2];
        }
        return $ret;
    }
    public function getNameOfMonthRekap($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = $inputmonth;
        switch($month){
            case 'jan' : $return = '1'; break;
            case 'Jan' : $return = '1'; break;
            case 'feb' : $return = '2'; break;
            case 'Feb' : $return = '2'; break;
            case 'mar' : $return = '3'; break;
            case 'Mar' : $return = '3'; break;
            case 'apr' : $return = '4'; break;
            case 'Apr' : $return = '4'; break;
            case 'may' : $return = '5'; break;
            case 'May' : $return = '5'; break;
            case 'Mei' : $return = '5'; break;
            case 'jun' : $return = '6'; break;
            case 'Jun' : $return = '6'; break;
            case 'jul' : $return = '7'; break;
            case 'Jul' : $return = '7'; break;
            case 'agt' : $return = '8'; break;
            case 'Agt' : $return = '8'; break;
            case 'agu' : $return = '8'; break;
            case 'Aug' : $return = '8'; break;
            case 'aug' : $return = '8'; break;
            case 'sep' : $return = '9'; break;
            case 'Sep' : $return = '9'; break;
            case 'sept' : $return = '9'; break;
            case 'oct' : $return = '10'; break;
            case 'Oct' : $return = '10'; break;
            case 'nov' : $return = '11'; break;
            case 'Nov' : $return = '11'; break;
            case 'dec' : $return = '12'; break;
            case 'Dec' : $return = '12'; break;
            // default : $return = $inputmonth; break;
        }
        return $return;
    }
    public function getFormatManyDays($val_db)
    {
        //val_db bisa bertipe array dan struktur data data;data;data;
        if(empty($val_db))
            return null;
        if (is_array($val_db)) {
            $d=$val_db;
        }else {
            $d=explode(';', $val_db);
        }
        $sub='';
        $hari=$this->CI->otherfunctions->getHariList();
        $c=1;
        if (isset($d)) {
            $dif=[];$full=[];
            foreach ($d as $v_d) {
                if (isset($hari[$v_d])) {
                    $sub.=$hari[$v_d];
                    $dif[$v_d]=$hari[$v_d];
                    if ($c < count($d)) {
                        $sub.=', ';
                    }
                } 
                if (isset($hari[$c])) {
                    $full[$c]=$hari[$c];
                }
                $c++;
            }
            $missing = array_diff($full,$dif);
            if (count($missing) == 0) {
                if (isset($hari[min($d)]) && isset($hari[max($d)])) {
                    $sub=$hari[min($d)].' - '.$hari[max($d)];
                }
            }
        }
        return $sub;
    }
    public function convertJamtoDecimal($time)
    {
        if(empty($time))
            return null;
        $jam=$this->CI->otherfunctions->getDataExplode($time,':','start');
        $menit=$this->CI->otherfunctions->getDataExplode($time,':','end');
        $new_decimal = $menit/60;
        $val = $jam+$new_decimal;
        return $val;
    }
    public function convertDecimaltoJam($dec)
    {
        // start by converting to seconds
        $seconds = ($dec * 3600);
        // we're given hours, so let's get those the easy way
        $hours = floor($dec);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // calculate minutes left
        $minutes = floor($seconds / 60);
        // remove those from seconds as well
        $seconds -= $minutes * 60;
        // return the time formatted HH:MM:SS
        return $this->addZeroInTime($hours).":".$this->addZeroInTime($minutes);//.":".$this->addZeroInTime($seconds);
    }
    // lz = leading zero
    function addZeroInTime($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }
	public function getDateFormatDbTimeToNoTime($datetime)
	{
		if(empty($datetime)) 
			return null;
		$datetime=explode(' ', $datetime);
		$new_datetime=$datetime[0];
		return $new_datetime;
    }
    public function cekValueTanggal($month,$year)
    {
		if(empty($month) || empty($year)) 
			return null;
        $date=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        return $date;
    }
    public function getClockUserAngka($data,$limit=2)
    {   
        if ($data != NULL) {
            $jam=explode(':', $data);
            $jamx=(float)$jam[0]+($jam[1]/60);
            $jamx=number_format($jamx,$limit);
        }else{
            $jamx=0;
        }
        return $jamx;
    }
    public function getDayMonthYears($date)
    {
        if(empty($date))
            return null;
        $tahun=$this->CI->otherfunctions->getDataExplode($date,'-','start');
        $bulan=$this->CI->otherfunctions->getDataExplode($date,'-','end');
        $hari=$this->CI->otherfunctions->getDataExplode($date,'-','3');
        $val = [
            'hari'=>$hari,
            'bulan'=>$bulan,
            'tahun'=>$tahun,
        ];
        return $val;
    }
    public function getTimeFormatUserFull($time, $timezone = null)
    {
        if(empty($time))
            return null;
        $zone = (!empty($timezone)) ? ' '.$timezone : '';
        $new_val = $time;
        return $new_val.$zone;
    }
    public function countLibur($month,$year)
    {
        $tgl_akhir = $this->cekValueTanggal($month,$year);
		$tgl_mulai = $year.'-'.$month.'-01';
		$tgl_selesai = $year.'-'.$month.'-'.$tgl_akhir;
        $hari_minggu = 0;
        $hari_libur = 0;
		$start=$tgl_mulai;
		while ($start <= $tgl_selesai)
		{
            $libur =  $this->CI->otherfunctions->checkHariLiburActive($start);
            if(!empty($libur) && $libur == 'Minggu'){
                $hari_minggu+=1;
            }elseif(!empty($libur) && $libur != 'Minggu'){
                $hari_libur+=1;
            }
			$start = date('Y-m-d', strtotime($start . ' +1 day'));
		}
        $data = ['minggu'=>$hari_minggu, 'libur'=>$hari_libur];
        $libur = $hari_minggu+$hari_libur;
        return $libur;
    }
    public function getDayMonthYearsHourMinute($datetime)
    {
        if(empty($datetime))
            return null;
        $date=$this->CI->otherfunctions->getDataExplode($datetime,' ','start');
        $time=$this->CI->otherfunctions->getDataExplode($datetime,' ','end');
        $tahun=$this->CI->otherfunctions->getDataExplode($date,'-','start');
        $bulan=$this->CI->otherfunctions->getDataExplode($date,'-','end');
        $hari=$this->CI->otherfunctions->getDataExplode($date,'-','3');
        $jam=$this->CI->otherfunctions->getDataExplode($time,':','start');
        $menit=$this->CI->otherfunctions->getDataExplode($time,':','end');
        $detik=$this->CI->otherfunctions->getDataExplode($time,':','3');
        $val = [
            'hari'=>$hari,
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'jam'=>$jam,
            'menit'=>$menit,
            'detik'=>$detik,
        ];
        return $val;
    }
}
?>