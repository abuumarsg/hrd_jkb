<?php
	$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<style type="text/css">
	.wordwrap { 
	   white-space: pre-wrap;      /* CSS3 */   
	   white-space: -moz-pre-wrap; /* Firefox */    
	   white-space: -pre-wrap;     /* Opera <7 */   
	   white-space: -o-pre-wrap;   /* Opera 7 */    
	   word-wrap: break-word;      /* IE */
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-user-alt-slash"></i> Karyawan Non Aktif
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_karyawan_non_aktif');?>"><i class="fas fa-user-alt-slash"></i> Karyawan Non Aktif</a></li>
			<li class="active">Profile <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-success">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle view_photo" width="100px" data-source-photo="<?php echo base_url($foto); ?>" src="<?php echo base_url($foto); ?>" alt="User profile picture">
						<h3 class="profile-username text-center"><?php echo $profile['nama']; ?></h3>
						<?php $grade=isset($profile['nama_grade'])?'<label class="label label-primary text-center">'.$profile['nama_grade'].' ('.$profile['nama_loker_grade'].')</label>':'<label class="label label-danger text-center">Tidak Punya Grade</label>'; ?>
						<p class="text-center"><?php echo $grade; ?></p>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item clearfix">
								<b>Tanggal Masuk</b> <label class="pull-right label label-info"><?php echo $this->formatter->getDateMonthFormatUser($profile['tgl_masuk']); ?></label>
							</li>
							<li class="list-group-item clearfix">
								<b>Status Karyawan</b>
								<?php
								if ($profile['nama_status'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Status</label>';
								}else{
									echo '<label class="pull-right label label-warning">'.$profile['nama_status'].'</label>';
								}?>
							</li>
							<li class="list-group-item clearfix">
								<b>Level Jabatan</b>
								<?php
								if ($profile['nama_level_jabatan'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Level Jabatan</label>';
								}else{
									echo '<label class="pull-right label label-success wordwrap">'.$profile['nama_level_jabatan'].'</label>';
								}
								?>
							</li>

							<li class="list-group-item clearfix">
								<b>Lokasi Kerja</b><?php
								if ($profile['nama_loker'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
								}else{
									echo '<label class="pull-right label label-success">'.$profile['nama_loker'].'</label>';
								}
								?>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="box">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a onclick="data_info_non()" id="btn_info" href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>
							<li><a onclick="informasi_lain()" id="btn_other" href="#other" data-toggle="tab"><i class="fas fa-user-tag"></i> Informasi Tambahan</a></li>
							<li><a onclick="riwayat_kerja()" id="btn_work" href="#work" data-toggle="tab"><i class="fas fa-briefcase"></i> Riwayat Pekerjaan</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="info">
              					<?php $this->load->view('_partial/_view_employee_info_non'); ?>
							</div>
							<div class="tab-pane" id="other">
              					<?php $this->load->view('_partial/_view_employee_other'); ?>
							</div>
							<div class="tab-pane" id="work">
              					<?php $this->load->view('_partial/_view_employee_work'); ?>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</section>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>