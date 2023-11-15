<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
    * Code From GFEACORP.
    * Web Developer
    * @author      Galeh Fatma Eko Ardiansa S.Kom
    * @type        Library
    * @package     master_api_ci/APIMessages
    * @copyright   Copyright (c) 2019 GFEACORP
    * @version     1.0, 29 Dec 2019
    * Email        galeh.fatma@gmail.com
    * Phone        (+62) 85852924304
    * ==========// HAK CIPTA DILINDUNGI! //==========
*/
use RestServer\RestController;
use RestServer\Format;

class APIMessages
{
    protected $CI;
	public function __construct()
	{
		$this->CI =& get_instance();
	}

    //========================================== BLOCK CHANGE ==========================================//

    public function good_data($data)
    {
        return $this->CI->response( ['status' => true,'data' => $data], 200);
    }
    public function failure()
    {
        return $this->CI->response( ['status' => false,'message' => 'Sorry, Bad Request'], 400);
    }
    public function not_found()
    {
        return $this->CI->response( ['status' => false,'message' => 'Sorry, Your request not found'], 404);
    }
}