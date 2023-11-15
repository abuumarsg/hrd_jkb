<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="BPR Weleri Makmur, BPRWM, Sistem Penilaian Kerja">
	<meta name="author" content="Galeh Fatma Eko Ardiansa">
	<title><?php echo $this->otherfunctions->titlePages($this->uri->segment(2)); ?> HRD Management System HSOFT </title>
	<meta name="theme-color" content="#131c5b">
	<link rel="icon" href="<?php echo base_url('asset/img/favicon.png');?>" type="image/png">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.css');?>">
	<link rel="stylesheet"
		href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/Ionicons/css/ionicons.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/vendor/fontawesome5/css/all.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/dist/css/AdminLTE.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/plugins/iCheck/square/blue.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/plugins/timepicker/bootstrap-timepicker.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/plugins/viewerjs/dist/viewer.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/dist/css/skins/_all-skins.css');?>">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/morris.js/morris.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/jvectormap/jquery-jvectormap.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-daterangepicker/daterangepicker.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/datatables.net-bs/css/dataTables.bootstrap.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/bower_components/select2/dist/css/select2.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/plugins/pace/pace.css');?>">
	<link href="<?php echo base_url('asset/vendor/toastr/toastr.min.css');?>" rel="stylesheet" media="all">
	<link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
	<link href="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.css');?>" rel="stylesheet" media="all">
	<link href="<?php echo base_url('asset/vendor/emoji/dist/emoji.min.css');?>" rel="stylesheet" media="all">
	<link href="<?php echo base_url('asset/vendor/toastr/toastr.min.css');?>" rel="stylesheet" media="all">
	<link href="<?php echo base_url('asset/vendor/iconpicker/dist/css/fontawesome-iconpicker.min.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('asset/plugins/JsTree/dist/themes/default/style.min.css');?>" rel="stylesheet">
	<link href="<?php echo base_url('asset/dist/css/animated.css');?>" rel="stylesheet">
	<style type="text/css">
		.berita-user-img {
		margin: 0 auto;
		max-width: 400px;
		padding: 3px;
		border: 3px solid #d2d6de;
		display: block;
			margin-left: auto;
			margin-right: auto;
		padding-top: 50;
		border-radius: 5px 5px 5px 5px;
		}
		.fileUpload {
			position: relative;
			overflow: hidden;
			margin: 10px;
		}
		.fileUpload input.upload {
			position: absolute;
			top: 0;
			right: 0;
			margin: 0;
			padding: 0;
			font-size: 20px;
			cursor: pointer;
			opacity: 0;
			filter: alpha(opacity=0);
		}
		.loader {
			margin-top: -20px;
			margin-bottom: 50px;
		}
		.loader span {
			width: 30px;
			height: 30px;
			border-radius: 50%;
			display: inline-block;
			position: absolute;
			left: 50%;
			margin-left: -10px;
			-webkit-animation: 3s infinite linear;
			-moz-animation: 3s infinite linear;
			-o-animation: 3s infinite linear;
		}
		.loader span:nth-child(2) {
			background: #009dff;
			-webkit-animation: kiri 2s infinite linear;
			-moz-animation: kiri 2s infinite linear;
			-o-animation: kiri 2s infinite linear;
		}
		.loader span:nth-child(3) {
			background: #F1C40F;
			z-index: 100;
		}
		.loader span:nth-child(4) {
			background: #2FCC71;
			-webkit-animation: kanan 2s infinite linear;
			-moz-animation: kanan 2s infinite linear;
			-o-animation: kanan 2s infinite linear;
		}
		@-webkit-keyframes kanan {
			0% {
				-webkit-transform: translateX(40px);
			}
			50% {
				-webkit-transform: translateX(-40px);
			}
			100% {
				-webkit-transform: translateX(40px);
				z-index: 200;
			}
		}
		@-moz-keyframes kanan {
			0% {
				-moz-transform: translateX(40px);
			}

			50% {
				-moz-transform: translateX(-40px);
			}

			100% {
				-moz-transform: translateX(40px);
				z-index: 200;
			}
		}
		@-o-keyframes kanan {
			0% {
				-o-transform: translateX(40px);
			}

			50% {
				-o-transform: translateX(-40px);
			}

			100% {
				-o-transform: translateX(40px);
				z-index: 200;
			}
		}
		@-webkit-keyframes kiri {
			0% {
				-webkit-transform: translateX(-40px);
				z-index: 200;
			}

			50% {
				-webkit-transform: translateX(40px);
			}

			100% {
				-webkit-transform: translateX(-40px);
			}
		}
		@-moz-keyframes kiri {
			0% {
				-moz-transform: translateX(-40px);
				z-index: 200;
			}

			50% {
				-moz-transform: translateX(40px);
			}

			100% {
				-moz-transform: translateX(-40px);
			}
		}
		@-o-keyframes kiri {
			0% {
				-o-transform: translateX(-40px);
				z-index: 200;
			}

			50% {
				-o-transform: translateX(40px);
			}

			100% {
				-o-transform: translateX(-40px);
			}
		}
	</style>
</head>
<body class="hold-transition skin-blue sidebar-mini fixed" onload="set_interval()" onmousemove="reset_interval()"
	onclick="reset_interval()" onkeypress="reset_interval()" onscroll="reset_interval()">
	<div class="modal fade" id="swprog" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content text-center modal-danger">
				<div class="modal-header">
					<h4 class="modal-title">Progress Loading</h4>
				</div>
				<div class="modal-body">
					<div class="loader">
						<h1>
							<label style="font-size: 25pt;" id="minutes">00</label>
							<label style="font-size: 25pt;">:</label>
							<label style="font-size: 25pt;" id="seconds">00</label>
						</h1>
						<span></span>
						<span></span>
						<span></span>
					</div>
					<b style="color: yellow;">Jangan Refresh Halaman Ini</b>
				</div>
			</div>
		</div>
	</div>
	<div class="wrapper">
		<header class="main-header">
			<a href="#" class="logo">
				<span class="logo-mini fnt"><b><img src="<?php echo base_url('asset/img/hsoftl.png');?>"
							width="40px"></b></span>
				<span class="logo-lg fnt"><img src="<?php echo base_url('asset/img/hsoftl.png');?>" width="40px"><b>
						HSOFT</b></span>
			</a>
			<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li>
							<a class="tgl" id="date_time"></a>
						</li>
							<?php
								if ($this->uri->segment(2) == "data_pesan") {
									echo '<li class="active">';
								}else{
									echo '<li>';
								}
							?>
							<a href="<?=base_url('kpages/data_pesan')?>" title="Pesan"><i class="far fa-envelope"></i></a>
						</li>
						<?php
								if ($this->uri->segment(2) == "read_all_notification" || $this->uri->segment(2) == "read_notification" ) {
									echo '<li class="dropdown notifications-menu active" >';
								}else{
									echo '<li class="dropdown notifications-menu">';
								}
							?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php
									if (count($adm['notif']) > 0) {
										echo '<i class="far fa-bell faa-ring animated"></i><span class="label" style="color: red;"><i class="fa fa-circle"></i></span>';
									}else{
										echo '<i class="far fa-bell"></i>';
									}
								?>
						</a>
						<ul class="dropdown-menu">
							<?php if (count($adm['notif']) > 0) { ?>
							<li class="header"><?php echo 'Anda Punya '.count($adm['notif']).' Notifikasi';?></li>
							<li>
								<ul class="menu">
									<?php 
										foreach ($adm['notif'] as $not) {
											if ($not['sifat'] == "1") {
												echo'<li data-toggle="tooltip" title="Penting">';
											}else{
												echo '<li>';
											}
											echo '<a href="'.base_url('kpages/read_notification/'.$not['kode']).'" title="'.$not['judul'].'">';
											if ($not['tipe'] == "info") {
												echo '<i class="fa fa-bullhorn text-aqua"></i> ';
											}elseif ($not['tipe'] == "warning") {
												echo '<i class="fa fa-warning text-yellow"></i> ';
											}else{
												echo '<i class="fa fa-times-circle text-red"></i> ';
											} 
											echo '<b>'.$not['judul'].'</b>';
											if ($not['sifat'] == "1") {
												echo '<span class="label pull-right" style="color: red;"><i class="fa fa-dot-circle-o"></i></span>';
											}
											echo '</a></li>';
										}
										?>
								</ul>
							</li>
							<?php }else{ echo '<li class="header">Tidak Ada Notifikasi</li>';}?>
							<li class="footer"><a
									href="<?php echo base_url('kpages/read_all_notification');?>">Tampilkan Semua</a>
							</li>
						</ul>
						</li>
						<?php
							if ($this->uri->segment(2) == "profile") {
								echo '<li class="dropdown user user-menu active">';
							}else{
								echo '<li class="dropdown user user-menu">';
							}
						?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo base_url($adm['foto']);?>" class="user-image" alt="User Image">
							<span class="hidden-xs"><?php echo $adm['nama'];?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="user-header">
								<img src="<?php echo base_url($adm['foto']);?>" class="img-circle" alt="User Image">
								<p>
									<?php echo $adm['nama1'];?><small>Terdaftar Sejak
										<?php echo date("d F Y", strtotime($adm['create']));?></small>
								</p>
							</li>
							<li class="user-footer">
								<div class="pull-left">
									<a href="<?php echo base_url('kpages/profile');?>"
										class="btn btn-flat btn-success"><i class="fa fa-user"></i> Profile</a>
								</div>
								<div class="pull-right">
									<a href="<?php echo base_url('auth/logout');?>" class="btn btn-flat btn-danger">Log	Out <i class="fas fa-sign-out-alt"></i></a>
								</div>
							</li>
						</ul>
						</li>
						<li class="dropdown notifications-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-gears"></i>
							</a>
							<ul class="dropdown-menu">
								<li class="header"><b><i class="fa fa-gear"></i> Setting Aplikasi</b></li>
								<li>
									<ul class="menu">
										<?php 
											$dt_sx=$adm['skin'];
											if ($dt_sx == 'skin-blue') {
												$dt_s='dark-mode';
												$namex='<i class="fas fa-moon"></i> Dark Mode ';
											}else{
												$dt_s='skin-blue';
												$namex='<i class="fas fa-sun"></i> Normal Mode ';
											}
										?>
										<li><a href="#" data-skin="<?php echo $dt_s; ?>"
												id="skinX"><?php echo $namex; ?></a></li>
										<li><a href="#about_apps" data-toggle="modal"><i class="fa fa-info-circle"></i>
												Tentang Aplikasi</a></li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		<div class="modal fade" id="about_apps" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content text-center">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title">Tentang <b class="text-muted" style="font-size: 12pt">Aplikasi</b></h2>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<img class="img-responsive img profile-user-img" style="width: 200px"
									src="<?php echo $this->otherfunctions->companyProfile()['logo']; ?>" alt="">
								<p class="text-muted">Software Version
									<?php echo $this->otherfunctions->companyProfile()['version']; ?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-center">
								<h2><b><?php echo $this->otherfunctions->companyProfile()['name']; ?></b></h2>
								<p>
									<?php echo $this->otherfunctions->companyProfile()['description']; ?>
									<br>
									<br>
									<i class="fas fa-map-marker-alt"></i>
									<?php echo $this->otherfunctions->companyProfile()['address']; ?> <br> <i class="fas fa-phone"></i>
									<?php echo $this->otherfunctions->companyProfile()['call']; ?> <i class="fas fa-at"></i> <a href="mailto:<?php echo $this->otherfunctions->companyProfile()['email']; ?>"><?php echo $this->otherfunctions->companyProfile()['email']; ?></a>
									<i class="fab fa-internet-explorer"></i> <a href="<?php echo $this->otherfunctions->companyProfile()['website']; ?>"><?php echo $this->otherfunctions->companyProfile()['website']; ?></a>
								</p>
								<div class="row">
									<div class="col-md-12">
										<?php echo $this->otherfunctions->companyProfile()['maps']; ?>
									</div>
								</div>
								<small>Copyright &copy; 2018 -
									<?php echo date('Y').' <a href="'.$this->otherfunctions->companyProfile()['website'].'">'.$this->otherfunctions->companyProfile()['name_office'].'</a> - All rights reserved.';?>
								</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
