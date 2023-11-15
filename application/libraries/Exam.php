<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Library Exam
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

class Exam {
	
	protected $CI;
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function index()
	{
		$this->redirect('not_found');
	}
//partisipan begin
	public function getPartisipanColumnTable($partisipan)
	{
		if(empty($partisipan))
			return null;
		$unpack=$this->CI->otherfunctions->getParseVar($partisipan);
		$list_par=[];
		if (isset($unpack)) {
			foreach ($unpack as $k_u => $v_u) {
				array_push($list_par,$k_u);
			}
		}
		$list_par=array_unique($list_par);
		return $list_par;
	}
	public function getWhatIsPartisipan($usage,$attr=null)
	{
		if(empty($usage))
			return null;
		$new_val=null;
		$usage=strtolower($usage);
		$usage=strtoupper($usage);
		if ($usage == 'ATS' && $attr != 'rekap') {
			$new_val=((!isset($attr['icon']))?'<i class="fa fa-user-check scc"></i> ':'').'Atasan';
		}elseif ($usage == 'BWH') {
			$new_val='Bawahan';
		}elseif ($usage == 'RKN') {
			$new_val='Rekan';
		}elseif ($usage == 'DRI') {
			$new_val='Diri Sendiri';
		}elseif ($usage == 'ATASAN') {
			$new_val='ATS';
		}elseif ($usage == 'BAWAHAN') {
			$new_val='BWH';
		}elseif ($usage == 'REKAN') {
			$new_val='RKN';
		}elseif ($usage == 'DIRI SENDIRI') {
			$new_val='DRI';
		}elseif ($usage == 'ATS' && $attr == 'rekap') {
			$new_val='Atasan';
		}else{
			$new_val=null;
		}
		return $new_val;
	}
	public function getWhatColPartisipan($usage,$usefor)
	{
		if(empty($usage)||empty($usefor)) 
			return null;
		$new_val=null;
		$usefor=strtolower($usefor);
		$usage=strtolower($usage);
		$usage=strtoupper($usage);
		if ($usage == 'ATS' || $usage == 'ATASAN') {
			$new_val=$usefor.'_atas';
		}elseif ($usage == 'BWH' || $usage == 'BAWAHAN') {
			$new_val=$usefor.'_bawah';
		}elseif ($usage == 'RKN' || $usage == 'REKAN') {
			$new_val=$usefor.'_rekan';
		}elseif ($usage == 'DRI' || $usage == 'DIRI SENDIRI') {
			$new_val=$usefor.'_diri';
		}else{
			$new_val=null;
		}
		return $new_val;
	}
	public function addPartisipanPiece($val,$usage)
	{
		if(empty($val) || empty($usage))
			return null;
		$new_val = $usage.':'.$val;
		return $new_val;
	}
	public function addPartisipanDb($val,$usage,$old)
	{
		if(empty($val) || empty($usage))
			return null;
		if (empty($old)) {
			$ex=[];
		}else{
			$ex=explode(';', $old);
		}
		foreach ($val as $v) {
			$new=$this->addPartisipanPiece($v,$usage);
			array_push($ex, $new);
		}
		$ex=array_unique($ex);
		sort($ex);
		$new_val=implode(';', $ex);
		return $new_val;
	}
	public function delPartisipantDb($val,$old)
	{
		if(empty($val))
			return 'null';
		if (empty($old)) {
			$ex=[];
		}else{
			$ex=explode(';', $old);
		}
		if(is_array($val)){
			foreach ($val as $v) {
				if(in_array($v, $ex)){
					$key=array_search($v, $ex);
					unset($ex[$key]);
				}
			}
		}else{
			if(in_array($val, $ex)){
				$key=array_search($val, $ex);
				unset($ex[$key]);
			}
		}		
		$ex=array_unique($ex);
		sort($ex);
		$new_val=implode(';', $ex);
		return $new_val;
	}
	public function delPartisipantDbId($id,$old)
	{
		if(empty($id))
			return 'null';
		if (empty($old)) {
			$ex=[];
		}else{
			$ex=$this->getPartisipantKode($old);
		}
		$new_val=null;
		if (isset($ex)) {
			$ex=array_filter($ex);
			$pack=[];
			foreach ($ex as $k_e =>$v_e) {
				if (is_array($id)) {
					if (!in_array($k_e,$id)) {
						array_push($pack,$v_e.':'.$k_e);
					}
				}else{
					if ($k_e != $id) {
						array_push($pack,$v_e.':'.$k_e);
					}
				}				
			}
			sort($pack);
			$new_val=implode(';', $pack);
		}		
		return $new_val;
	}
	public function delSubBobotAtasanDb($val,$old)
	{
		if(empty($val))
			return null;
		if (empty($old)) {
			$ex=[];
		}else{
			$ex=$this->getPartisipantId($old);
		}
		foreach ($val as $v) {
			$id=$this->getPartisipantPiece($v,'end');
			if(isset($ex[$id])){
				unset($ex[$id]);
			}
		}
		$ex=array_unique($ex);
		$pack=[];
		foreach ($ex as $k_e =>$v_e) {
			array_push($pack,$k_e.':'.$v_e);
		}
		sort($pack);
		$new_val=implode(';', $pack);
		return ($new_val == '')?null:$new_val;
	}
// public function getDetailPartisipan($table,$id)
// {
//     if (empty($table) || empty($id)) 
//         return null;
//     $new_val=null;
//     $detail=\app\models\HTDinamictableagendasikap::getPartisipanAndDetail($table,$id);
//     if (isset($detail[$id])) {
//         $ex=explode(';',$detail[$id]);
//         foreach ($ex as $e) {
//             $var=explode(':',$e);
//             $new_val[$var[0]]=$var[1];
//         }
//     }
//     return $new_val;
// }
// public function getWhoIsAssessor($table,$id)
// {
//     if (empty($table) || empty($id)) 
//         return null;
//     $new_val=[];
//     $list_partisipan=\app\models\HTDinamictableagendasikap::getPartisipanAndId($table);
//     foreach ($list_partisipan as $idk => $par) {
//         $piece=$this->getPartisipantKode($par);
//         if (!empty($piece)) {
//             foreach ($piece as $p) {
//                 if (isset($p[$id])) {
//                     $var=$p[$id].':'.$idk;
//                     array_push($new_val,$var);
//                 }
//             }
//         }
//     }
//     if (count($new_val) > 0) {
//         $new_val = array_values(array_unique($new_val));
//     }else{
//         $new_val=null;
//     }
//     return $new_val;
// }
	public function getWhoYou($val)
	{
		if (empty($val)) 
			return null;
		$new_val=[];
		foreach ($val as $x) {
			$ex=explode(':',$x);
			if ($ex[0] == 'ATS') {
				$var='Atasan';
			}elseif ($ex[0] == 'BWH') {
				$var='Bawahan';
			}elseif ($ex[0] == 'RKN') {
				$var='Rekan';
			}else {
				$var='Diri Sendiri';
			}
			$new_val[$ex[1]]=$var;
		}
		return $new_val;
	}
	public function getPartisipantDb($a,$b,$r)
	{
		$bag=[];
		if (!empty($a)) {
			array_push($bag, $a);
		}
		if (!empty($b)) {
			array_push($bag, $b);
		}
		if (!empty($r)) {
			array_push($bag, $r);
		}
		if (empty($bag))
			return null;
		$new_val=implode(';', $bag);
		return $new_val;
	}
	public function getPartisipantPack($val,$usage)
	{
		if(empty($val) || empty($usage)) 
			return null;
		$new_val = null;
		$bag=[];
		if ($usage == 'atasan') {
			foreach ($val as $v) {
				$x='ATS:'.$v;
				array_push($bag, $x);
			} 
		}elseif ($usage == 'bawahan') {
			foreach ($val as $v) {
				$x='BWH:'.$v;
				array_push($bag, $x);
			}
		}elseif ($usage == 'rekan') {
			foreach ($val as $v) {
				$x='RKN:'.$v;
				array_push($bag, $x);
			}
		}else{
			$bag=null;
		}
		if (empty($bag)) 
			return null;
		$new_val=implode(';', $bag);
		return $new_val;
	}
	public function getPartisipantSort($val,$usage)
	{
		if(empty($val) || empty($usage)) 
			return null;
		$new_val = null;
		$ex=explode(',', $val);
		sort($ex);
		if ($usage == 'code') {
			$new_val = $ex;
		}else{
			$new_val = null;
		}
		$new_val = implode(',', $new_val);
		return $new_val;
	}
	public function getPartisipantPiece($val,$usage)
	{
		if(empty($val) || empty($usage)) 
			return null;
		$new_val = null;
		$ex=explode(':', $val);
		if ($usage == 'front') {
			$new_val=$ex[0];
		}elseif ($usage == 'end') {
			$new_val=$ex[1];
		}else{
			$new_val=null;
		}
		return $new_val;
	}
	public function getPartisipantId($val)
	{
		if(empty($val)) 
			return null;
		$new_val = null;
		$bag=[];
		$ex=explode(';', $val);
		foreach ($ex as $e) {
			$x=$this->getPartisipantPiece($e,'front');
			$y=$this->getPartisipantPiece($e,'end');
			$bag[$x]=$y;
		}
		$new_val=$bag;
		return $new_val;
	}
	public function getPartisipantKode($val)
	{
		if(empty($val)) 
			return null;
		$new_val = null;
		$bag=[];
		$ex=explode(';', $val);
		foreach ($ex as $e) {
			$x=$this->getPartisipantPiece($e,'front');
			$y=$this->getPartisipantPiece($e,'end');
			$bag[$y]=$x;
		}
		$new_val=$bag;
		return $new_val;
	}
	public function getPartisipantOnlyId($val)
	{
		if(empty($val)) 
			return null;
		$new_val = null;
		$bag=[];
		$ex=explode(';', $val);
		foreach ($ex as $e) {
			$y=$this->getPartisipantPiece($e,'end');
			array_push($bag, $y);
		}
		$new_val=$bag;
		return $new_val;
	}
	public function getPartisipantStatusList($par,$val)
	{
    //val is array value
		if(empty($par) || empty($val)) 
			return null;
		$sebagai=$this->getPartisipanColumnTable($par);
		$all=$this->getPartisipantKode($par);
		$pack=[];

		if (isset($all)) {
			$pack['count_all']=0;
			$pack['count_unfinish']=0;
			$pack['count_done']=0;
			foreach ($all as $sb=>$a) {
				$d_e=$this->CI->model_karyawan->getEmployeeId($sb);
				$pack['partisipan'][$a][$sb]=((isset($d_e['nama']))?$d_e['nama']:null);
				$pack['unfinish'][$a][$sb]=((isset($d_e['nama']))?$d_e['nama']:null);
				$pack['count_all']=$pack['count_all']+1;
				$pack['count_unfinish']=$pack['count_unfinish']+1;
			}
		}        
		

		if (isset($sebagai)) {
			foreach ($sebagai as $sbg) {
				if (isset($val[$sbg])) {
					$done=[];
					foreach ($val[$sbg] as $k_asp => $kuis) {
						if (isset($kuis)) {
							foreach ($kuis as $k_kuis => $nilai) {
								$na=$this->CI->otherfunctions->getParseVar($nilai);
								if (isset($na)) {
									foreach ($na as $k_n => $v_n) {
										array_push($done,$k_n);
									}
								}
							}
						}
					}
					if (isset($done)) {
						$pack['done'][$sbg]=[];
						$done=array_unique(array_filter($done));
						foreach ($done as $dn) {
							$pack['done'][$sbg][$dn]=((isset($pack['partisipan'][$sbg][$dn]))?$pack['partisipan'][$sbg][$dn]:$this->CI->otherfunctions->getMark());
							$pack['count_done']=$pack['count_done']+1;
							if (isset($pack['unfinish'][$sbg])) {
								unset($pack['unfinish'][$sbg][$dn]);
								$pack['count_unfinish']=$pack['count_unfinish']-1;
							}
						}
					}
				}
			}
		}
		return $pack;
	}
	public function delNilaiPartisipantDb($val,$db,$bobot)
    {
        if(empty($val) || empty($db))
            return null;        
        $pack=[];
        foreach ($db as $d) {
            $col_ats=$this->getWhatColPartisipan('ATS','nilai');
            $col_bwh=$this->getWhatColPartisipan('BWH','nilai');
            $col_rkn=$this->getWhatColPartisipan('RKN','nilai');
            $k_col_ats=$this->getWhatColPartisipan('ATS','keterangan');
            $k_col_bwh=$this->getWhatColPartisipan('BWH','keterangan');
            $k_col_rkn=$this->getWhatColPartisipan('RKN','keterangan');
            if (!empty($d->$col_ats)) {
                $pack[$d->kode_kuisioner][$col_ats]=$d->$col_ats;   
            }
            if (!empty($d->$col_bwh)) {
                $pack[$d->kode_kuisioner][$col_bwh]=$d->$col_bwh;   
            } 
            if (!empty($d->$col_rkn)) {
                $pack[$d->kode_kuisioner][$col_rkn]=$d->$col_rkn;   
            }   
            if (!empty($d->$k_col_ats)) {
                $pack[$d->kode_kuisioner][$k_col_ats]=$d->$k_col_ats;   
            }
            if (!empty($d->$k_col_bwh)) {
                $pack[$d->kode_kuisioner][$k_col_bwh]=$d->$k_col_bwh;   
            } 
            if (!empty($d->$k_col_rkn)) {
                $pack[$d->kode_kuisioner][$k_col_rkn]=$d->$k_col_rkn;   
            } 
        }
        $del=[];
        $k_del=[];
        foreach ($val as $v) {
            $v1=explode(':', $v);
            if (isset($v1[0]) && isset($v1[1])) {
                $col=$this->getWhatColPartisipan($v1[0],'nilai');
                $k_col=$this->getWhatColPartisipan($v1[0],'keterangan');
                $del[$col][]=$v1[1];                                
                $k_del[$k_col][]=$v1[1];                                
            }
        }
        $bag=[];
        if (isset($pack)) {
            foreach ($pack as $id_kuis => $val) {
                if (isset($val['nilai_atas']) && isset($del['nilai_atas'])) {
                    $plain_ats=$this->delNilaiDb($del['nilai_atas'],$val['nilai_atas']);
                    $rata_ats=$this->getNilaiAverage($plain_ats);
                    $bag[$id_kuis]['nilai_atas']=(($plain_ats == '')?null:$plain_ats);
                    $bag[$id_kuis]['rata_atas']=(($rata_ats == '')?null:$rata_ats);
                    if (isset($val['keterangan_atas']) && isset($k_del['keterangan_atas'])) {
                        $ket_ats=$this->delNilaiDb($k_del['keterangan_atas'],$val['keterangan_atas']);
                        $bag[$id_kuis]['keterangan_atas']=(($ket_ats == '')?null:$ket_ats);
					}
					if (isset($bobot['ats'])) {
						$na_ats=$rata_ats*$this->hitungBobot($bobot['ats']);
						$bag[$id_kuis]['na_atas']=(($na_ats=='')?null:$na_ats);
					}
                }
                if (isset($val['nilai_bawah']) && isset($del['nilai_bawah'])) {
                    $plain_bwh=$this->delNilaiDb($del['nilai_bawah'],$val['nilai_bawah']);
                    $rata_bwh=$this->getNilaiAverage($plain_bwh);
                    $bag[$id_kuis]['nilai_bawah']=(($plain_bwh == '')?null:$plain_bwh);
                    $bag[$id_kuis]['rata_bawah']=(($rata_bwh == '')?null:$rata_bwh);
                    if (isset($val['keterangan_bawah']) && isset($k_del['keterangan_bawah'])) {
                        $ket_bwh=$this->delNilaiDb($k_del['keterangan_bawah'],$val['keterangan_bawah']);
                        $bag[$id_kuis]['keterangan_bawah']=(($ket_bwh == '')?null:$ket_bwh);
					}
					if (isset($bobot['bwh'])) {
						$na_bwh=$rata_bwh*$this->hitungBobot($bobot['bwh']);
						$bag[$id_kuis]['na_bawah']=(($na_bwh=='')?null:$na_bwh);
					}
                }
                if (isset($val['nilai_rekan']) && isset($del['nilai_rekan'])) {
                    $plain_rkn=$this->delNilaiDb($del['nilai_rekan'],$val['nilai_rekan']);
                    $rata_rkn=$this->getNilaiAverage($plain_rkn);
                    $bag[$id_kuis]['nilai_rekan']=(($plain_rkn == '')?null:$plain_rkn);
                    $bag[$id_kuis]['rata_rekan']=(($rata_rkn == '')?null:$rata_rkn);
                    if (isset($val['keterangan_rekan']) && isset($k_del['keterangan_rekan'])) {
                        $ket_rkn=$this->delNilaiDb($k_del['keterangan_rekan'],$val['keterangan_rekan']);
                        $bag[$id_kuis]['keterangan_rekan']=(($ket_rkn == '')?null:$ket_rkn);
					}
					if (isset($bobot['rkn'])) {
						$na_rkn=$rata_rkn*$this->hitungBobot($bobot['rkn']);
						$bag[$id_kuis]['na_rekan']=(($na_rkn=='')?null:$na_rkn);
					}
                }
            }
        }
        return $bag;
    }
    public function editNilaiPartisipantDbOne($id,$sebagai_new,$sebagai_old,$db,$bobot)
    {
        if(empty($id) || empty($db) || empty($sebagai_new) || empty($sebagai_old) || empty($bobot))
            return null;    
        $pack=[];
        foreach ($db as $d) {
            $col_ats=$this->getWhatColPartisipan('ATS','nilai');
            $col_bwh=$this->getWhatColPartisipan('BWH','nilai');
            $col_rkn=$this->getWhatColPartisipan('RKN','nilai');
            $k_col_ats=$this->getWhatColPartisipan('ATS','keterangan');
            $k_col_bwh=$this->getWhatColPartisipan('BWH','keterangan');
            $k_col_rkn=$this->getWhatColPartisipan('RKN','keterangan');
            $pack[$d->kode_kuisioner][$col_ats]=$d->$col_ats;   
            $pack[$d->kode_kuisioner][$col_bwh]=$d->$col_bwh;   
            $pack[$d->kode_kuisioner][$col_rkn]=$d->$col_rkn;  
            $pack[$d->kode_kuisioner][$k_col_ats]=$d->$k_col_ats;   
            $pack[$d->kode_kuisioner][$k_col_bwh]=$d->$k_col_bwh;   
			$pack[$d->kode_kuisioner][$k_col_rkn]=$d->$k_col_rkn;
        }
        $bag=[];
        if (isset($pack)) {
            $val_old=[];
            $ket_old=[];
            foreach ($pack as $id_kuis => $val) {
                if ($sebagai_old == 'ATS') {
                    $val_old=$this->searchNilaiSikap($id,$val['nilai_atas']);
                    $ket_old=$this->searchNilaiSikap($id,$val['keterangan_atas']);
                }elseif ($sebagai_old == 'BWH') {
                    $val_old=$this->searchNilaiSikap($id,$val['nilai_bawah']);
                    $ket_old=$this->searchNilaiSikap($id,$val['keterangan_bawah']);
                }elseif ($sebagai_old == 'RKN') {
                    $val_old=$this->searchNilaiSikap($id,$val['nilai_rekan']);
                    $ket_old=$this->searchNilaiSikap($id,$val['keterangan_rekan']);
				}

                if ($sebagai_new == 'ATS' && (count($val_old) > 0)) {
                    $plain_ats=$this->addNilaiDb($val_old,$val['nilai_atas']);
                    $ket_ats=$this->addNilaiDb($ket_old,$val['keterangan_atas']);
                    $rata_ats=$this->getNilaiAverage($plain_ats);
                    $bag[$id_kuis]['nilai_atas']=$plain_ats;
                    $bag[$id_kuis]['rata_atas']=$rata_ats;
					$bag[$id_kuis]['keterangan_atas']=$ket_ats;
					if (isset($bobot['ats'])) {
						$na_ats=$rata_ats*$this->hitungBobot($bobot['ats']);
						$bag[$id_kuis]['na_atas']=(($na_ats=='')?null:$na_ats);
					}
                }elseif ($sebagai_new == 'BWH' && (count($val_old) > 0)) {
                    $plain_bwh=$this->addNilaiDb($val_old,$val['nilai_bawah']);
                    $ket_bwh=$this->addNilaiDb($ket_old,$val['keterangan_bawah']);
                    $rata_bwh=$this->getNilaiAverage($plain_bwh);
                    $bag[$id_kuis]['nilai_bawah']=$plain_bwh;
                    $bag[$id_kuis]['rata_bawah']=$rata_bwh;
					$bag[$id_kuis]['keterangan_bawah']=$ket_bwh;
					if (isset($bobot['bwh'])) {
						$na_bwh=$rata_bwh*$this->hitungBobot($bobot['bwh']);
						$bag[$id_kuis]['na_bawah']=(($na_bwh=='')?null:$na_bwh);
					}
                }elseif ($sebagai_new == 'RKN' && (count($val_old) > 0)) {
                    $plain_rkn=$this->addNilaiDb($val_old,$val['nilai_rekan']);
                    $ket_rkn=$this->addNilaiDb($ket_old,$val['keterangan_rekan']);
                    $rata_rkn=$this->getNilaiAverage($plain_rkn);
                    $bag[$id_kuis]['nilai_rekan']=$plain_rkn;
                    $bag[$id_kuis]['rata_rekan']=$rata_rkn;
					$bag[$id_kuis]['keterangan_rekan']=$ket_rkn;
					if (isset($bobot['rkn'])) {
						$na_rkn=$rata_rkn*$this->hitungBobot($bobot['rkn']);
						$bag[$id_kuis]['na_rekan']=(($na_rkn=='')?null:$na_rkn);
					}
                }
            }
        }
        return $bag;
	}
	public function searchNilaiSikap($id,$db)
    {
        if (empty($id) || empty($db))
            return [];
        $old=$this->getNilaiSikapWithId2($db);
        $ret=[];
        if (isset($old)) {
            if (isset($old[$id])) {
                $ret[$id]=$old[$id];
            }
        }
        return $ret;
	}
	public function getNilaiSikapWithId2($val)
    {
        if(empty($val)) 
            return null;
        $new_val = null;
        $bag=[];
        $ex=explode(';', $val);
        foreach ($ex as $e) {
            $var=explode(':',$e);
            $bag[$var[0]]=$var[1];
        }
        $new_val=$bag;
        return $new_val;
	}
	public function delNilaiDb($val,$old)
    {
        //$val is [id=>nilai]
        if(empty($val))
            return null;
        if (empty($old)) {
            $ex=[];
        }else{
            $ex=$this->getNilaiSikapWithId2($old);
        }
        foreach ($val as $v) {
            if(isset($ex[$v])){
                unset($ex[$v]);
            }
        }
        $pack=[];
        if (isset($ex)) {
            foreach ($ex as $key => $value) {
                array_push($pack, $key.':'.$value);
            }
        }
        $pack=array_unique($pack);
        sort($pack);
        $new_val=implode(';', $pack);
        return ($new_val != '')?$new_val:null;
    }
    public function addNilaiDb($val,$old)
    {
        //$val is [id=>nilai]
        if(empty($val))
            return null;
        if (empty($old)) {
            $ex=[];
        }else{
            $ex=$this->getNilaiSikapWithId2($old);
        }
        $pack=[];
        foreach ($val as $ke=>$v) {
            $ex[$ke]=$v;
        }
        if (isset($ex)) {
            foreach ($ex as $key => $value) {
                array_push($pack, $key.':'.$value);
            }
        }
        $pack=array_unique($pack);
        sort($pack);
        $new_val=implode(';', $pack);
        return ($new_val != '')?$new_val:null;
	}
//partisipan end
//bobot sikap
	public function getBobotData($code)
	{
		$new_val = ['bobot_ats'=>0,'bobot_bwh'=>0,'bobot_rkn'=>0];
		if(empty($code)) 
			return $new_val;
		$d=$this->CI->model_master->getBobotSikapKode($code);
		$new_val = ['bobot_ats'=>$d['atasan'],'bobot_bwh'=>$d['bawahan'],'bobot_rkn'=>$d['rekan']];
		return $new_val;
	}
	public function getBobotCode($val)
	{
		if(empty($val)) 
			return null;
		$new_val = null;
		$bag=[];
		$ex=explode(';', $val);
		foreach ($ex as $e) {
			array_push($bag, $this->getPartisipantPiece($e,'front'));          
		} 
		$bag=array_unique(array_filter($bag));
		$bag=implode(';', $bag);
		$bag=$this->getPartisipantSort($bag,'code');
		return $bag;
	}
	public function getColsBobotSikap($kode)
	{
		if(empty($kode)) 
			return null;
		$all=array('ATS','BWH','RKN');
		$ex=explode(';', $kode);
		foreach ($ex as $e) {
			if (in_array($e, $all)) {
				$pos = array_search($e, $all);
				unset($all[$pos]);
			}
		}
		$new=[];

		if (isset($all)) {
			foreach ($all as $a) {
				if ($a == 'ATS') {
					array_push($new, 'atasan');
				}
				if ($a == 'BWH') {
					array_push($new, 'bawahan');
				}
				if ($a == 'RKN') {
					array_push($new, 'rekan');
				}
			}
		}
		return $new;
	}
//kompetensi
	public function getListJenisAspekKompetensi()
	{
		$pack=[
			'functional'=>'Functional Competency',
			'technical'=>'Technical Competency',
			'profesional'=>'Profesional Competency',
		];
	}
	public function getJenisAspekKompetensiTipeJabatan($tipe)
	{
		$pack=[];
		if (empty($tipe) || (($tipe != 'GOL1') || ($tipe != 'GOL2')))
			return $pack;
		$pack=$this->getListJenisAspekKompetensi();
		if ($tipe == 'GOL1') {
			unset($pack['technical']);
		}elseif($tipe == 'GOL2'){
			unset($pack['profesional']);
		}
		return $pack;
	}
//===BLOCK CHANGE===//

//penilaian begin
	public function hitungBobot($bobot)
	{
		if (empty($bobot))
			return 0;
		return $bobot/100;
	}
	public function hitungAverageArray($arr)
	{
		if (empty($arr))
			return 0;
		return array_sum($arr)/count($arr);
	}
	public function addEditValueOneLevelDb($val,$old)
	{
		if ($val == '') 
			return $old;
		if (empty($old)) 
			return $val;
		$bag=$this->CI->otherfunctions->getParseOneLevelVar($old);
		if (isset($bag) && (count($bag) > 0)) {
			if (is_array($val)) {
				foreach ($val as $v) {
					array_push($bag,$val);
				}
			}else{
				array_push($bag,$val);
			}						
		}
		$new_val=implode(';', array_unique(array_filter($bag)));		
		return $new_val;
	}
	public function addEditValueWithIdDb($id,$val,$old)
	{
		if (empty($id) || empty($val)) 
			return $old;
		$new_val=null;
		$bag=$this->CI->otherfunctions->getParseVar($old);
		$bag[$id]=$val;
		$new_val=$this->packValue($bag);
		return $new_val;
	}
	public function deleteValueWithIdDb($id,$old)
	{
		if (empty($id)) 
			return null;
		$new_val=null;
		$bag=$this->CI->otherfunctions->getParseVar($old);
		if (isset($bag)) {
			if (isset($bag[$id])) {
				unset($bag[$id]);
			}
		}
		$new_val=$this->packValue($bag);
		return $new_val;    
	}
	public function packValue($array)
	{
		if (!isset($array)) 
			return null;
		$pack=[];
		foreach ($array as $key => $value) {
			array_push($pack,($key.':'.$value));
		}
		$new_val=implode(';', array_unique(array_filter($pack)));
		return $new_val;
	}
	public function getNilaiSikapWithId($val,$id)
	{
		if(empty($val)) 
			return null;
		$new_val = null;
		$bag=[];
		$ex=explode(';', $val);
		$ex=array_filter($ex);
		foreach ($ex as $e) {
			$var=explode(':',$e);
			$bag[$var[0]]=$var[1];
		}
		if (!isset($bag[$id]))
			return null;
		$new_val=$bag[$id];
		return $new_val;
	}
	public function getNilaiPack($val,$id,$pack)
	{
		if(empty($val) || empty($id)) 
			return null; 
		$bag=[];
		$new_val=null;
		$old_val=[];
		$var=$id.':'.$val;
		if ($pack == null) {
			array_push($bag,$var);
		}else{
			$ex=explode(';',$pack);
			$box=[];
			foreach ($ex as $e) {
				array_push($bag,$e);
				$piece=explode(':',$e);
				$box[$piece[0]]=$piece[1];
				if ($piece[0] == $id) {
					$old_val[]=$id.':'.$box[$id];  
				}
			}
			if (count($old_val) > 0) {
				foreach ($old_val as $old) {
					$pos=array_search($old,$bag);
					if (isset($bag[$pos])) {
						unset($bag[$pos]);
					}
				}
			}
			array_push($bag,$var);
		}
		$new_val=implode(';',array_unique($bag));
		return $new_val;
	}
	public function getNilaiAverage($val,$kpi = false)
	{
		if($val == '') 
			return null; 
		$new_val=null;
		$bag=[];
		$ex=explode(';',$val);
		foreach ($ex as $e) {
			$piece=explode(':',$e);
			$bag[$piece[0]]=$piece[1];
		}
		if (count($bag) == 0) 
			return null;
		$new_val=array_sum($bag)/count($bag);
		if ($kpi) {
			$new_val=end($bag);
		}
		return $new_val;
	}
	public function getNilaiSum($val,$kpi = false)
	{
		if($val == '') 
			return null; 
		$new_val=null;
		$bag=[];
		$ex=explode(';',$val);
		foreach ($ex as $e) {
			$piece=explode(':',$e);
			if (isset($piece[1]) && isset($piece[0])) {
				if ($piece[1] != '' && $piece[1] != null) {
					$bag[$piece[0]]=$piece[1];
				}				
			}			
		}
		if (count($bag) == 0) 
			return null;
		$new_val=array_sum($bag);
		if ($kpi) {
			$new_val=end($bag);
		}
		return $new_val;
	}
	//table name
	public function getMaxRow($column,$table,$columnid)
	{
		$query = "SELECT $column FROM $table WHERE $columnid = (SELECT MAX($columnid) FROM $table)";
		$qq = $this->CI->db->query($query)->row_array();
		return $qq[$column];
	}
	public function getPieceTableName($name)
	{
		if(empty($name)) 
			return null;
		$new_val = null;
		$ex=explode('_', $name);
		$new_val=$ex[2];
		return $new_val;
	}
	public function getDayNameTable($val)
	{
		if(empty($val)) 
			return null;
		$date=substr($val, 0, 8);
		return $date;
	}
	public function getNameTable($usefor)
	{
		$date_now=date('Ymd');
		if ($usefor == 'concept_sikap') {
			$table_name=$this->getMaxRow('nama_tabel','concept_sikap','id_c_sikap');
			$piece=$this->getPieceTableName($table_name);
			$day=$this->getDayNameTable($piece);
			if ($day != $date_now) {
				$name='concept_sikap_'.$date_now.'1';
			}else{
				$name='concept_sikap_'.($piece+1);
			}
		}elseif ($usefor == 'concept_kpi') {
			$table_name=$this->getMaxRow('nama_tabel','concept_kpi','id_c_kpi');
			$piece=$this->getPieceTableName($table_name);
			$day=$this->getDayNameTable($piece);
			if ($day != $date_now) {
				$name='concept_kpi_'.$date_now.'1';
			}else{
				$name='concept_kpi_'.($piece+1);
			}
		}elseif ($usefor == 'concept_kompetensi') {
			$table_name=$this->getMaxRow('nama_tabel','concept_kompetensi','id_c_kompetensi');
			$piece=$this->getPieceTableName($table_name);
			$day=$this->getDayNameTable($piece);
			if ($day != $date_now) {
				$name='concept_kompetensi_'.$date_now.'1';
			}else{
				$name='concept_kompetensi_'.($piece+1);
			}
		}elseif ($usefor == 'agenda_kompetensi') {
			$table_name=$this->getMaxRow('nama_tabel','agenda_kompetensi','id_a_kompetensi');
			$piece=$this->getPieceTableName($table_name);
			$day=$this->getDayNameTable($piece);
			if ($day != $date_now) {
				$name='agenda_kompetensi_'.$date_now.'1';
			}else{
				$name='agenda_kompetensi_'.($piece+1);
			}
		}elseif ($usefor == 'agenda_sikap') {
			$table_name=$this->getMaxRow('nama_tabel','agenda_sikap','id_a_sikap');
			$piece=$this->getPieceTableName($table_name);
			$day=$this->getDayNameTable($piece);
			if ($day != $date_now) {
				$name='agenda_sikap_'.$date_now.'1';
			}else{
				$name='agenda_sikap_'.($piece+1);
			}
		}elseif ($usefor == 'agenda_kpi') {
			$table_name=$this->getMaxRow('nama_tabel','agenda_kpi','id_a_kpi');
			$piece=$this->getPieceTableName($table_name);
			$day=$this->getDayNameTable($piece);
			if ($day != $date_now) {
				$name='agenda_kpi_'.$date_now.'1';
			}else{
				$name='agenda_kpi_'.($piece+1);
			}
		}elseif ($usefor == 'agenda_reward') {
			$table_name=$this->getMaxRow('nama_tabel','agenda_reward','id_a_reward');
			$piece=$this->getPieceTableName($table_name);
			$day=$this->getDayNameTable($piece);
			if ($day != $date_now) {
				$name='agenda_reward_'.$date_now.'1';
			}else{
				$name='agenda_reward_'.($piece+1);
			}
		}else{
			$name=NULL;
		}
		return $name;
	}
//kpi
	public function getNilaiPackRemove($val,$id,$pack)
	{
		if(empty($id) && ($val == 'null' || $val == '')) 
			return null; 
		$new_val=null;
		$old_val=[];
		$co = 0;
		$var = null;
		if(is_numeric($val)){
			$var=$id.':'.$val;
		}
		if ($pack == null) {
			array_push($old_val,$var);
		}else{
			$ex=explode(';',$pack);
			$box=[];
			foreach ($ex as $e) {
				$piece=explode(':',$e);
				$box[$piece[0]]=$piece[1];
				if ($piece[0] == $id) {
					$old_val[]=$var;
					$co++;
				}else{
					$old_val[]=$piece[0].':'.$piece[1];
				}
			}
			if($co<1){
				array_push($old_val,$var);
			}
		}
		$new_val=implode(';',array_values(array_filter(array_unique($old_val))));
		return $new_val;
	}
	public function getPartisipanBlmNilai($penilai,$pn)
	{
		if($penilai==null)
			return null;
		$pen_val = $this->CI->otherfunctions->getParseOneLevelVar($penilai);
		if($pn==null){
			$pn_val[] = '';
		}else{
			$unpackpn = $this->CI->otherfunctions->getParseVar($pn);
			foreach ($unpackpn as $key => $value) {
				$pn_val[] = $key;
			}
		}
		$val = array_merge($pen_val,$pn_val);
		$val = array_count_values($val);
		foreach ($val as $v_key => $v_val) {
			if($v_val>1){
				$new_val[] = null;
			}elseif($v_val<2){
				if($v_key!='A_1')
					$new_val[] = $v_key;
			}
		}
		return array_values(array_filter($new_val));
	}
	public function getNilaiAverageDone($val,$bobot)
	{
		if($val == '' || empty($bobot)) 
			return null; 
		$new_val=null;
		$new_val=($val*$bobot)/100;
		return $new_val;
	}
	public function getUserPenilai($val,$id_log)
	{
		if(empty($val) || empty($id_log))
			return null;
		$new_val = false;
		$bag=explode(';',$val);
		foreach ($bag as $b) {
			if($id_log==$b)
				$new_val = true;
		}
		return $new_val;
	}
	public function cekPenilai($id_karyawan,$table,$id_log)
	{
		if(empty($id_karyawan) || empty($table))
			return null;
		$val=false;
		$cek = $this->CI->model_agenda->openTableAgendaIdEmployee($table,$id_karyawan);
		$user = $this->CI->model_karyawan->getEmployeeId($id_log);
		if (count($cek) > 0) {
			foreach ($cek as $ck) {
				$atasan = $this->CI->model_master->getAtasan($ck->kode_jabatan);
				if($ck->kode_penilai=='P1'){
					if($atasan==$user['jabatan'])
						$val = true;
				}elseif($ck->kode_penilai=='P3'){
					$parseId = $this->CI->otherfunctions->getParseOneLevelVar($ck->penilai);
					if (is_array($parseId)) {
						if (in_array($id_log,$parseId)) {
							$val = true;
						}
					}
				}
			}			
		}		
		return $val;
	}
	public function mergeArray($value)
	{
		$val=$value;
		$ival=implode(',',$val);
		$new_val = explode("," ,$ival);
		$new_val=array_filter($new_val);
		$new_val=array_values($new_val);
		return $new_val;
	}

	public function mergeArrayZero($value)
	{
		$val=$value;
		$ival=implode(',',$val);
		$new_val = explode("," ,$ival);
		$new_val=array_filter($new_val, 'strlen');
		$new_val=array_values($new_val);
		return $new_val;
	}
	public function mergeArrayNull($value)
	{
		$val=$value;
		$ival=implode(',',$val);
		$new_val = explode("," ,$ival);
		return $new_val;
	}
	public function cekJenisSatuan($id_task,$val_poin,$tabel)
	{
		$cek = $this->CI->model_agenda->openTableAgendaId($tabel,$id_task);
		foreach ($cek as $c) {
			if($c->jenis_satuan==0){
				$val = $val_poin;
			}elseif($c->jenis_satuan==1){
				if($c->sifat=='MAX'){
					if($val_poin>=$c->satuan_4){
						$val = $c->poin_4;
					}elseif($val_poin>=$c->satuan_3 && $val_poin<$c->satuan_4){
						$val = $c->poin_3;
					}elseif($val_poin>=$c->satuan_2 && $val_poin<$c->satuan_3){
						$val = $c->poin_2;
					}else{
						$val = $c->poin_1;
					}
				}elseif($c->sifat=='MIN') {
					if($val_poin>=$c->satuan_1){
						$val = $c->poin_1;
					}elseif($val_poin>=$c->satuan_2 && $val_poin<$c->satuan_1){
						$val = $c->poin_2;
					}elseif($val_poin>=$c->satuan_3 && $val_poin<$c->satuan_2){
						$val = $c->poin_3;
					}else{
						$val = $c->poin_4;
					}
				}
			}
		}
		return $val;
	}
	public function cekJenisSatuanBo($id_task,$val_poin,$tabel)
	{
		if($id_task!=''){
			if($val_poin!=''){
				$cek = $this->CI->model_agenda->openTableAgendaId($tabel,$id_task);
				foreach ($cek as $c) {
					if($c->jenis_satuan==0){
						$val = $val_poin;
					}elseif($c->jenis_satuan==1){
						if($c->sifat=='MAX'){
							if($val_poin>=$c->satuan_4){
								$val = $c->poin_4;
							}elseif($val_poin>=$c->satuan_3 && $val_poin<$c->satuan_4){
								$val = $c->poin_3;
							}elseif($val_poin>=$c->satuan_2 && $val_poin<$c->satuan_3){
								$val = $c->poin_2;
							}else{
								$val = $c->poin_1;
							}
						}elseif($c->sifat=='MIN') {
							if($val_poin>=$c->satuan_1){
								$val = $c->poin_1;
							}elseif($val_poin>=$c->satuan_2 && $val_poin<$c->satuan_1){
								$val = $c->poin_2;
							}elseif($val_poin>=$c->satuan_3 && $val_poin<$c->satuan_2){
								$val = $c->poin_3;
							}else{
								$val = $c->poin_4;
							}
						}
					}
				}
			}else{
				$val = 0;
			}
		}else{
			$val = null;
		}
		return $val;
	}
	public function changeJenisSatuanAll($id_task,$pn,$tabel)
	{
		if(empty($pn)){
			$new_val = null;
		}else{
			$pack=[];
			$get = $this->CI->otherfunctions->getParseVar($pn);
			foreach ($get as $idx => $nix) {
				$cek = $this->CI->model_agenda->openTableAgendaId($tabel,$id_task);
				foreach ($cek as $c) {
					if($c->sifat=='MAX'){
						if($nix>=$c->satuan_4){
							$val = $c->poin_4;
						}elseif($nix>=$c->satuan_3 && $nix<$c->satuan_4){
							$val = $c->poin_3;
						}elseif($nix>=$c->satuan_2 && $nix<$c->satuan_3){
							$val = $c->poin_2;
						}else{
							$val = $c->poin_1;
						}
					}elseif($c->sifat=='MIN') {
						if($nix>=$c->satuan_1){
							$val = $c->poin_1;
						}elseif($nix>=$c->satuan_2 && $nix<$c->satuan_1){
							$val = $c->poin_2;
						}elseif($nix>=$c->satuan_3 && $nix<$c->satuan_2){
							$val = $c->poin_3;
						}else{
							$val = $c->poin_4;
						}
					}
				}
				array_push($pack,($idx.':'.$val));
			}
			$new_val=implode(';', $pack);
		}
		return $new_val;
	}
	public function changeJenisSatuanAllBo($id_task,$pn,$tabel)
	{
		if(empty($pn)){
			$new_val = null;
		}else{
			$pack=[];
			$get = $this->CI->otherfunctions->getParseVar($pn);
			foreach ($get as $idx => $nix) {
				if($nix!=''){
					$val=0;
					$cek = $this->CI->model_agenda->openTableAgendaId($tabel,$id_task);
					foreach ($cek as $c) {
						if($c->sifat=='MAX'){
							if ($nix <=$c->satuan_1) {
								$val=$c->poin_1;
							}elseif ($nix >=$c->satuan_1 && $nix < $c->satuan_2 && $c->satuan_1 != '') {
								$val=$c->poin_2;
							}elseif ($nix >=$c->satuan_2 && $nix < $c->satuan_3 && $c->satuan_2 != '') {
								$val=$c->poin_3;
							}elseif ($nix >=$c->satuan_3 && $nix < $c->satuan_4 && $c->satuan_3 != '') {
								$val=$c->poin_4;
							}elseif ($nix >=$c->satuan_4 && $nix <= $c->satuan_5 && $c->satuan_4 != '') {
								$val=$c->poin_5;
							}elseif ($nix >=$c->satuan_5 && $c->satuan_5 != '') {
								$val=$c->poin_5;
							}else{
								if ($nix >=$c->satuan_1 && $c->satuan_2 == '') {
									$val=$c->poin_1;
								}elseif ($nix >=$c->satuan_2 && $c->satuan_3 == '') {
									$val=$c->poin_2;
								}elseif ($nix >=$c->satuan_3 && $c->satuan_4 == '') {
									$val=$c->poin_3;
								}elseif ($nix >=$c->satuan_4 && $c->satuan_5 == '') {
									$val=$c->poin_4;
								}
							}
							// if($nix>=$c->satuan_4){
							// 	$val = $c->poin_4;
							// }elseif($nix>=$c->satuan_3 && $nix<$c->satuan_4){
							// 	$val = $c->poin_3;
							// }elseif($nix>=$c->satuan_2 && $nix<$c->satuan_3){
							// 	$val = $c->poin_2;
							// }else{
							// 	$val = $c->poin_1;
							// }
						}elseif($c->sifat=='MIN') {
							if ($nix >=$c->satuan_1) {
								$val=$c->poin_1;
							}elseif ($nix >=$c->satuan_2 && $nix <= $c->satuan_1 && $c->satuan_1 != '') {
								$val=$c->poin_2;
							}elseif ($nix >=$c->satuan_3 && $nix < $c->satuan_2 && $c->satuan_2 != '') {
								$val=$c->poin_3;
							}elseif ($nix >=$c->satuan_4 && $nix < $c->satuan_3 && $c->satuan_3 != '') {
								$val=$c->poin_4;
							}elseif ($nix >=$c->satuan_5 && $nix < $c->satuan_4 && $c->satuan_4 != '') {
								$val=$c->poin_5;
							}elseif ($nix <=$c->satuan_5 && $c->satuan_5 != '') {
								$val=$c->poin_5;
							}else{
								if ($nix <=$c->satuan_1 && $c->satuan_2 == '') {
									$val=$c->poin_1;
								}elseif ($nix <=$c->satuan_2 && $c->satuan_3 == '') {
									$val=$c->poin_2;
								}elseif ($nix <=$c->satuan_3 && $c->satuan_4 == '') {
									$val=$c->poin_3;
								}elseif ($nix <=$c->satuan_4 && $c->satuan_5 == '') {
									$val=$c->poin_4;
								}
							}
							// if($nix>=$c->satuan_1){
							// 	$val = $c->poin_1;
							// }elseif($nix>=$c->satuan_2 && $nix<$c->satuan_1){
							// 	$val = $c->poin_2;
							// }elseif($nix>=$c->satuan_3 && $nix<$c->satuan_2){
							// 	$val = $c->poin_3;
							// }else{
							// 	$val = $c->poin_4;
							// }
						}
					}
					array_push($pack,($idx.':'.$val));
				}else{
					array_push($pack,($idx.':0'));
				}
			}
			$new_val=implode(';', $pack);
		}
		return $new_val;
	}
	public function coreConversiKpi($nilai,$data)
	{
		if ($nilai == '' && empty($data)) 
			return 0;
		$val=0;
		if ($data['jenis_satuan'] == 0) {
			for ($i=1; $i <= $this->CI->otherfunctions->poin_max_range() ; $i++) { 
				$data['satuan_'.$i]=$data['poin_'.$i];
			}
		}
		// if($data['sifat']=='MIN'){
		// 	if ($nilai <=$data['satuan_1']) {
		// 		$val=$data['poin_1'];
		// 	}elseif ($nilai >=$data['satuan_1'] && $nilai <= $data['satuan_2'] && ($data['satuan_1'] != '')) {
		// 		$val=$data['poin_2'];
		// 	}elseif ($nilai >=$data['satuan_2'] && $nilai <= $data['satuan_3'] && ($data['satuan_2'] != '')) {
		// 		$val=$data['poin_3'];
		// 	}elseif ($nilai >=$data['satuan_3'] && $nilai <= $data['satuan_4'] && ($data['satuan_3'] != '')) {
		// 		$val=$data['poin_4'];
		// 	}elseif ($nilai >=$data['satuan_4'] && $nilai <= $data['satuan_5'] && ($data['satuan_4'] != '')) {
		// 		$val=$data['poin_5'];
		// 	}elseif ($nilai >=$data['satuan_5'] && $nilai <= $data['satuan_6'] && ($data['satuan_5'] != '')) {
		// 		$val=$data['poin_6'];
		// 	}elseif ($nilai >=$data['satuan_6'] && $nilai <= $data['satuan_7'] && ($data['satuan_6'] != '')) {
		// 		$val=$data['poin_7'];
		// 	}elseif ($nilai >=$data['satuan_7'] && $nilai <= $data['satuan_8'] && ($data['satuan_7'] != '')) {
		// 		$val=$data['poin_8'];
		// 	}elseif ($nilai >=$data['satuan_8'] && $nilai <= $data['satuan_9'] && ($data['satuan_8'] != '')) {
		// 		$val=$data['poin_9'];
		// 	}elseif ($nilai >=$data['satuan_9'] && $nilai <= $data['satuan_10'] && ($data['satuan_9'] != '')) {
		// 		$val=$data['poin_10'];
		// 	}elseif ($nilai >=$data['satuan_10'] && ($data['satuan_10'] != '')) {
		// 		$val=$data['poin_10'];
		// 	}else{
		// 		if ($nilai >=$data['satuan_1'] && ($data['satuan_2'] == '')) {
		// 			$val=$data['poin_1'];
		// 		}elseif($nilai >=$data['satuan_2'] && ($data['satuan_3'] == '')) {
		// 			$val=$data['poin_2'];
		// 		}elseif($nilai >=$data['satuan_3'] && ($data['satuan_4'] == '')) {
		// 			$val=$data['poin_3'];
		// 		}elseif($nilai >=$data['satuan_4'] && ($data['satuan_5'] == '')) {
		// 			$val=$data['poin_4'];
		// 		}elseif($nilai >=$data['satuan_5'] && ($data['satuan_6'] == '')) {
		// 			$val=$data['poin_5'];
		// 		}elseif($nilai >=$data['satuan_6'] && ($data['satuan_7'] == '')) {
		// 			$val=$data['poin_6'];
		// 		}elseif($nilai >=$data['satuan_7'] && ($data['satuan_8'] == '')) {
		// 			$val=$data['poin_7'];
		// 		}elseif($nilai >=$data['satuan_8'] && ($data['satuan_9'] == '')) {
		// 			$val=$data['poin_8'];
		// 		}elseif($nilai >=$data['satuan_9'] && ($data['satuan_10'] == '')) {
		// 			$val=$data['poin_9'];
		// 		}
		// 	}
		// }elseif($data['sifat']=='MAX') {
			if ($nilai >=$data['satuan_1']) {
				$val=$data['poin_1'];
			}elseif ($nilai > $data['satuan_2'] && $nilai <= $data['satuan_1'] && ($data['satuan_1'] != '' && $data['satuan_2'] != '')) {
				$val=$data['poin_1'];
			}elseif ($nilai > $data['satuan_3'] && $nilai <= $data['satuan_2'] && ($data['satuan_2'] != '' && $data['satuan_3'] != '')) {
				$val=$data['poin_2'];
			}elseif ($nilai > $data['satuan_4'] && $nilai <= $data['satuan_3'] && ($data['satuan_3'] != '' && $data['satuan_4'] != '')) {
				$val=$data['poin_3'];
			}elseif ($nilai > $data['satuan_5'] && $nilai <= $data['satuan_4'] && ($data['satuan_4'] != '' && $data['satuan_5'] != '')) {
				$val=$data['poin_4'];
			}elseif ($nilai > $data['satuan_6'] && $nilai <= $data['satuan_5'] && ($data['satuan_5'] != '' && $data['satuan_6'] != '')) {
				$val=$data['poin_5'];
			}elseif ($nilai > $data['satuan_7'] && $nilai <= $data['satuan_6'] && ($data['satuan_6'] != '' && $data['satuan_7'] != '')) {
				$val=$data['poin_6'];
			}elseif ($nilai > $data['satuan_8'] && $nilai <= $data['satuan_7'] && ($data['satuan_7'] != '' && $data['satuan_8'] != '')) {
				$val=$data['poin_7'];
			}elseif ($nilai > $data['satuan_9'] && $nilai <= $data['satuan_8'] && ($data['satuan_8'] != '' && $data['satuan_9'] != '')) {
				$val=$data['poin_8'];
			}elseif ($nilai > $data['satuan_10'] && $nilai <= $data['satuan_9'] && ($data['satuan_9'] != '' && $data['satuan_10'] != '')) {
				$val=$data['poin_9'];
			}elseif ($nilai <=$data['satuan_10'] && ($data['satuan_10'] != '')) {
				$val=$data['poin_10'];
			}else{
				if ($nilai <=$data['satuan_1'] && ($data['satuan_2'] == '')) {
					$val=$data['poin_1'];
				}elseif($nilai <=$data['satuan_2'] && ($data['satuan_3'] == '')) {
					$val=$data['poin_2'];
				}elseif($nilai <=$data['satuan_3'] && ($data['satuan_4'] == '')) {
					$val=$data['poin_3'];
				}elseif($nilai <=$data['satuan_4'] && ($data['satuan_5'] == '')) {
					$val=$data['poin_4'];
				}elseif ($nilai <=$data['satuan_5'] && ($data['satuan_6'] == '')) {
					$val=$data['poin_5'];
				}elseif ($nilai <=$data['satuan_6'] && ($data['satuan_7'] == '')) {
					$val=$data['poin_6'];
				}elseif ($nilai <=$data['satuan_7'] && ($data['satuan_8'] == '')) {
					$val=$data['poin_7'];
				}elseif ($nilai <=$data['satuan_8'] && ($data['satuan_9'] == '')) {
					$val=$data['poin_8'];
				}elseif ($nilai <=$data['satuan_9'] && ($data['satuan_10'] == '')) {
					$val=$data['poin_9'];
				}elseif ($nilai <=$data['satuan_10'] && ($data['satuan_10'] == '')) {
					$val=$data['poin_10'];
				}elseif ($nilai <=$data['satuan_9'] && ($data['satuan_9'] == '')) {
					$val=$data['poin_9'];
				}elseif ($nilai <=$data['satuan_8'] && ($data['satuan_8'] == '')) {
					$val=$data['poin_8'];
				}elseif ($nilai <=$data['satuan_7'] && ($data['satuan_7'] == '')) {
					$val=$data['poin_7'];
				}elseif ($nilai <=$data['satuan_6'] && ($data['satuan_6'] == '')) {
					$val=$data['poin_6'];
				}elseif ($nilai <=$data['satuan_5'] && ($data['satuan_5'] == '')) {
					$val=$data['poin_5'];
				}elseif($nilai <=$data['satuan_4'] && ($data['satuan_5'] == '')) {
					$val=$data['poin_4'];
				}elseif($nilai <=$data['satuan_3'] && ($data['satuan_4'] == '')) {
					$val=$data['poin_3'];
				}elseif($nilai <=$data['satuan_2'] && ($data['satuan_3'] == '')) {
					$val=$data['poin_2'];
				}elseif($nilai <=$data['satuan_1'] && ($data['satuan_2'] == '')) {
					$val=$data['poin_1'];
				}
			}
		// }
		return $val;
	}
	public function getCapaianNilai($pnx,$idlog,$bobot)
	{
		if(empty($pnx)){
			$var = [
				'capaian'=>0,
				'poin'=>0,
				'nilai'=>0
			];
		}else{
			$get = $this->CI->otherfunctions->getParseVar($pnx);
			foreach ($get as $k => $v) {
				if($k==$idlog){
					$var = [
						'capaian'=>$v,
						'poin'=>$v,
						'nilai'=>(($v*$bobot)/100)
					];
				}
			}
		}
		return $var;
	}
	public function getCapaianNilaiAll($pnx,$bobot)
	{
		if(empty($pnx)){
			$var = [
				'capaian'=>0,
				'poin'=>0,
				'nilai'=>0
			];
		}else{
			$get = $this->CI->otherfunctions->getParseVar($pnx);
			foreach ($get as $k => $v) {
				$var = [
					'capaian'=>$v,
					'poin'=>$v,
					'nilai'=>(($v*$bobot)/100)
				];
			}
		}
		return $var;
	}
	public function getNilaiAll($pnx,$bobot)
	{   

		if(empty($pnx)){
			$new_val = 0;
		}else{
			$val = 0;
			$get = $this->CI->otherfunctions->getParseVar($pnx);
			foreach ($get as $k => $v) {
				$val += $v;
			}
			$new_val = (($val*$bobot)/100);
		}
		return $new_val;
	}
	public function countSumloopEmp($val,$idlog,$jml=false)
	{
		$jum=0;
		$cekId = $this->CI->otherfunctions->getParseVar($val);
		// print_r($val);
		// print_r($cekId);		
		if (is_array($cekId) || is_object($cekId)){
			foreach ($cekId as $x => $r)
			{
				if($idlog==$x){
					$jum=1;
					// $jml++;
				}
			}
		}
		// print_r($idlog);	echo '<br>';
		// print_r($jum);	echo '<br>';
		return $jum;
	}
	public function getValueProgressAgendaFo($table,$datePeriode,$kodeAtasan,$idlog)
	{
		$ttl_emp = 0;
		$ttl_emp_all = 0;
		$getemp=$this->CI->model_agenda->openTableAgenda($table);
		// print_r($getemp);	
		foreach ($getemp as $g) {
			if($g->kode_penilai=='P1'){
				$getAtasan=$this->CI->model_master->getAtasan($g->kode_jabatan);
				// print_r($getAtasan);
				// echo $getAtasan.' == '.$kodeAtasan.'<br>';
				if($getAtasan==$kodeAtasan){
					$ttl_emp_all++;
					if($datePeriode==0){
						$ttl_emp += $this->CI->exam->countSumloopEmp($g->pn1,$idlog,$ttl_emp);
					}elseif($datePeriode==1){
						$ttl_emp += $this->CI->exam->countSumloopEmp($g->pn2,$idlog,$ttl_emp);
					}elseif($datePeriode==2){
						$ttl_emp += $this->CI->exam->countSumloopEmp($g->pn3,$idlog,$ttl_emp);
					}elseif($datePeriode==3){
						$ttl_emp += $this->CI->exam->countSumloopEmp($g->pn4,$idlog,$ttl_emp);
					}
				}
			}elseif ($g->kode_penilai=='P3') {
				$cek_penilai = $this->CI->exam->cekPenilai($g->id_task,$table,$idlog);
				if($cek_penilai){
					$ttl_emp_all++;
					if($datePeriode==0){
						$ttl_emp += $this->CI->exam->countSumloopEmp($g->pn1,$idlog,$ttl_emp);
					}elseif($datePeriode==1){
						$ttl_emp += $this->CI->exam->countSumloopEmp($g->pn2,$idlog,$ttl_emp);
					}elseif($datePeriode==2){
						$ttl_emp += $this->CI->exam->countSumloopEmp($g->pn3,$idlog,$ttl_emp);
					}elseif($datePeriode==3){
						$ttl_emp += $this->CI->exam->countSumloopEmp($g->pn4,$idlog,$ttl_emp);
					}
				}
			}
		}
		// print_r($ttl_emp);echo '<br>';
		// print_r($ttl_emp_all);echo '<br>';
		// $jm1= ($ttl_emp/$ttl_emp_all)*100;
		// print_r($jm1);echo '<br>';

		if($ttl_emp_all>0){
			$jm1= ($ttl_emp/$ttl_emp_all)*100;
		}else{
			$jm1= 0;
		}
		
		return $jm1;
	}
	public function rumusProsentase($target,$capaian)
	{
		if (empty($target) && empty($capaian)) 
            return 0;
        if (empty($target) && !empty($capaian)) 
            return 100;
        $val=(100-(($capaian/$target)*100));
		$val=($val<0)?0:$val;
        return $val;
	}
	public function rangeInsentif($all_value,$nilai)
	{
		$new_val=null;
		if (empty($all_value)) 
			return $new_val;
		$ranges=[];
		$data_prosen=$this->CI->model_master->getListKonversiInsentif(1);
		if (isset($data_prosen)) {
			$start=1;
			foreach ($data_prosen as $dp) {
				$prosen=number_format(($dp->prosentase/100),1);
				$jumlah=number_format((count($all_value) * $prosen),0);
				for ($i=0; $i < $jumlah; $i++) { 
					$ranges[$dp->huruf][]=$start;
					$start++;
				}				
			}
		}
		$all_value=$this->CI->otherfunctions->arraySortIndex($all_value,0,'DESC');
		$count=1;
		foreach ($all_value as $key => $value) {
			foreach ($ranges as $huruf => $range) {
				if (in_array($count,$range) && $value == $nilai) {
					$new_val=$huruf;
				}
			}
			$count++;
		}
		// echo "<pre>";
		// print_r ($ranges);
		// print_r ($all_value);
		// echo array_sum($ranges);
		// echo "</pre>";
		
		// $min=($min == '')?0:$min;
		// $max=($max == '')?0:$max;
		// $nilai=($nilai == '')?0:$nilai;
		// $nilai=$this->CI->formatter->getNumberFloat($nilai,2,'en');
		// $ret=[];
		// $mid=$max-$min;
		// $data_prosen=$this->CI->model_master->getListKonversiInsentif(1);		
		// if (isset($data_prosen)) {
		// 	$end=$max;
		// 	foreach ($data_prosen as $dp) {
		// 		$mp=number_format($mid*($dp->prosentase/100),2);
		// 		$start=number_format($end,2)-$mp;
		// 		$ret[$dp->huruf]=['start'=>number_format(($start-0.01),2),'end'=>(number_format($end,2))];
		// 		$end=$start;
		// 	}
		// }
		// if (count($ret) > 0) {
		// 	foreach ($ret as $huruf => $range) {
		// 		if ($nilai >= number_format($range['start'],2) && $nilai <= number_format($range['end'],2)) {
		// 			$new_val=$huruf;
		// 		}
		// 	}
		// }
		return $new_val;		
	}
	public function bobot_tertimbang($arr_bobot,$bobot,$navl)
	{
		if (is_null($arr_bobot) || is_null($bobot))
			return false;
		$pyb=(array_sum($arr_bobot));
		if ($pyb != 0){
			$ret=number_format((($bobot/$pyb)*100),2);
		}else{
			$ret=$bobot;
		}
		if ($navl) {
			$ret=0;
		}
		$ret=$this->CI->formatter->getNumberFloat($ret);
		return $ret;
	}
	public function convertComma($num)
	{
		if (strpos($num,',')) {
			$num=(float)(str_replace(',','.',$num));
		}
		return $num;
	}
	
}