<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" href="<?php echo base_url('pages/data_hasil_group'); ?>"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_hasil_group');?>"><i class="fa fa-calendar"></i> Daftar Agenda Gabungan</a></li>
			<li class="active">Daftar Karyawan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div style="padding-top: 10px;">
						<form id="form_filter">
							<input type="hidden" name="usage" id="usage" value="all">
							<input type="hidden" name="mode" id="mode" value="">
							<div class="box-body">
								<div class="col-md-3">
									<div class="">
										<label>Pilih Level Jabatan</label>
										<select class="form-control select2" id="level_jabatan_filter" name="level_jabatan_filter[]" multiple="multiple" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="">
										<label>Pilih Departement</label>
										<select class="form-control select2" id="departemen_filter" name="departemen_filter" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="">
										<label>Pilih Bagian</label>
										<select class="form-control select2" id="bagian_filter" name="bagian_filter" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="">
										<label>Pilih Lokasi Kerja</label>
										<select class="form-control select2" id="loker_filter" name="loker_filter" style="width: 100%;"></select>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<div class="col-md-12">
									<div class="pull-right">
										<button type="button" onclick="tableDataResult('search')" id="btn_search" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a id="btn_info" href="#result" data-toggle="tab" onclick="tableDataResult('all')"><i class="fa fa-line-chart"></i> Hasil Raport Tahunan</a></li>
						<!-- <li><a onclick="tableDataInsentif()" href="#insentif" data-toggle="tab"><i class="fa fa-money"></i> Insentif</a></li> -->
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="result">
							<?php $this->load->view('_partial/_view_employee_result_tahunan') ?>
						</div>
						<!-- <div class="tab-pane" id="insentif"> -->
							<?php //$this->load->view('_partial/_view_employee_result_insentif') ?>
						<!-- </div> -->
					</div>
				</div>
			</div>
		</div>
	</section>
</div> 