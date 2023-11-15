<?php
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-user-clock"></i> Log Karyawan <small class="view_nama_full"><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active view_nama_full">Log Karyawan <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a onclick="log_mutasi()" href="#mutasi" data-toggle="tab"><i class="fas fa-user-cog"></i> Mutasi</a></li>
						<li><a onclick="log_perjanjian()" href="#perjanjian" data-toggle="tab"><i class="fas fa-file-contract"></i> Perjanjian Kerja</a></li>
						<li><a onclick="log_peringatan()" href="#peringatan" data-toggle="tab"><i class="fas fa-exclamation-triangle"></i> Peringatan</a></li>
						<li><a onclick="log_denda()" href="#denda" data-toggle="tab"><i class="fas fa-dollar-sign"></i> Denda</a></li>
						<li><a onclick="log_grade()" href="#grade" data-toggle="tab"><i class="fab fa-glide"></i> Grade</a></li>
						<li><a onclick="log_kecelakaan()" href="#kecelakaan" data-toggle="tab"><i class="fas fa-ambulance"></i> Kecelakaan Kerja</a></li>
						<li><a onclick="log_izin()" href="#izin" data-toggle="tab"><i class="fas fa-calendar-times"></i> Izin & Cuti</a></li>
						<li><a onclick="log_dinas()" href="#dinas" data-toggle="tab"><i class="fas fa-car"></i> Perjalanan Dinas</a></li>
						<li><a onclick="log_presensi()" href="#presensi" data-toggle="tab"><i class="fa fas fa-tasks fa-fw"></i> Presensi</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="mutasi">
							<?php $this->load->view('_partial/_view_log_emp_mutasi'); ?>
						</div>
						<div class="tab-pane" id="perjanjian">
							<?php $this->load->view('_partial/_view_log_emp_perjanjian'); ?>
						</div>
						<div class="tab-pane" id="peringatan">
							<?php $this->load->view('_partial/_view_log_emp_peringatan'); ?>
						</div>
						<div class="tab-pane" id="denda">
							<?php $this->load->view('_partial/_view_log_emp_denda'); ?>
						</div>
						<div class="tab-pane" id="grade">
							<?php $this->load->view('_partial/_view_log_emp_grade'); ?>
						</div>
						<div class="tab-pane" id="kecelakaan">
							<?php $this->load->view('_partial/_view_log_emp_kecelakaan'); ?>
						</div>
						<div class="tab-pane" id="izin">
							<?php $this->load->view('_partial/_view_log_emp_izin'); ?>
						</div>
						<div class="tab-pane" id="dinas">
							<?php $this->load->view('_partial/_view_log_emp_dinas'); ?>
						</div>
						<div class="tab-pane" id="presensi">
							<?php $this->load->view('_partial/_view_log_emp_presensi'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
