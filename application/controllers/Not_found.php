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

class Not_found extends CI_Controller
{
	public function __construct() 
	{ 
		parent::__construct();
	}
	function not_found(){
		$this->load->view('admin_tem/header');
		$this->load->view('not_found');
		$this->load->view('admin_tem/footer');
	}
}