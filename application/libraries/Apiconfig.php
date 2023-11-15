<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


/**
    * Code From GFEACORP.
    * Web Developer
    * @author      Galeh Fatma Eko Ardiansa S.Kom
    * @type        Library
    * @package     bogowonto/Apiconfig
    * @copyright   Copyright (c) 2020 GFEACORP
    * @version     1.0, 24 Aug 2020
    * Email        galeh.fatma@gmail.com
    * Phone        (+62) 85852924304
    * ==========// HAK CIPTA DILINDUNGI! //==========
*/


class Apiconfig
{
    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->date=$this->CI->otherfunctions->getDateNow();
    }

    //========================================== BLOCK CHANGE ==========================================//

    public function send_data($url,$data=null,$method='get')
    {
        if ($method == 'post') {
            $data['access_key']=API_KEY_HSOFT;
            $data = http_build_query($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url.'?access_key='.API_KEY_HSOFT.$this->parsing_data($data));
        }
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function parsing_data($data)
    {
        $ret=null;
        if ($data) {
            $ret='&';
            $c=1;
            foreach ($data as $key => $value) {
                $ret.=$key.'='.$value;
                if ($c < count($data)) {
                    $ret.='&';
                }
            }
        }
        return $ret;
    }
}
