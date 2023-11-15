<?php

/**
 * GFEACORP - Web Developer
 *
 * @package  Codeigniter
 * @author   Galeh Fatma Eko Ardiansa <galeh.fatma@gmail.com>
 */

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?php echo $this->otherfunctions->companyProfile()['meta_description'];?>">
  <meta name="author" content="<?php echo $this->otherfunctions->companyProfile()['meta_author'];?>">
  <title><?php 
  echo $this->otherfunctions->titlePages($this->uri->segment(2));
  ?> HRD Management System HSOFT </title>
  <meta name="theme-color" content="#131c5b">
  <link rel="icon" href="<?php echo base_url();?>asset/img/favicon.png" type="image/png">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/Ionicons/css/ionicons.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/AdminLTE.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/iCheck/square/blue.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/skins/_all-skins.min.css');?>">
  <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Kaushan Script" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
  <link href="<?php echo base_url('asset/vendor/toastr/toastr.min.css');?>" rel="stylesheet" media="all">
  <link href="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.css');?>" rel="stylesheet" media="all">
  <link href="<?php echo base_url('asset/vendor/emoji/dist/emoji.min.css');?>" rel="stylesheet" media="all">
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/pace/pace.css');?>">
  <link href="<?php echo base_url('asset/vendor/toastr/toastr.min.css');?>" rel="stylesheet" media="all">
  <link rel="stylesheet" href="<?php echo base_url('asset/vendor/fontawesome5/css/all.min.css');?>">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="logoa">
    <a href="<?php echo $this->otherfunctions->companyClientProfile()['website'];?>" target="blank"><img width="300px" src="<?php echo base_url('asset/img/logo.png'); ?>">
      
    </a>
    <p>HRD Management System</p>
  </div>
