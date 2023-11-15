<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * Code From GFEACORP.
     * Web Developer
     * @author      Galeh Fatma Eko Ardiansa
     * @package     Library Rumus
     * @copyright   Copyright (c) 2018 GFEACORP
     * @version     1.0, 1 September 2018
     * Email        galeh.fatma@gmail.com
     * Phone        (+62) 85852924304
     */

class Rumus {
	
	protected $CI;
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function index()
	{
		$this->redirect('not_found');
	}
	public function rumus_sum($val)
	{
		if (empty($val)) 
			return 0;
		$arr=(is_array($val))?true:false;
		$new_val=($arr)?array_sum($val):$val;
		return $new_val;
	}
	public function rumus_avg($val)
	{
		if (empty($val)) 
			return 0;
		if (empty(count($val))) 
			return 0;
		$pbg=0;
		foreach ($val as $vl) {
			if (is_numeric($vl) && !empty($vl)) {
				$pbg++;
			}
		}
		$new_val=((empty($pbg))?0:(array_sum($val)/$pbg));
		return $new_val;
	}
	public function rumus_prosentase($available,$all)
	{
		if (empty($all)) 
			return 0;
		return ($available/$all)*100;
	}
	public function rumus_prosentase_negatif($available,$all,$max)
	{
		if ($max == '') {
			return 0;
		}else{
			if (($all-$max) == 0) {
				return 0;
			}else{
				return (($available-$max)/($all-$max))*100;
			}			
		}		
	}
}