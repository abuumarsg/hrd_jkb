<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fas fa-tasks fa-fw"></i> Data
			<small> Presensi</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fa fas fa-tasks fa-fw"></i> Presensi</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#presensi_harian" onclick="refreshDataHarian()" data-toggle="tab">Data Presensi Harian</a></li>
						<?php if (in_array($access['l_ac']['up_presensi'], $access['access'])) { ?>
							<li><a href="#data_presensi" onclick="tableData('all')" data-toggle="tab">Data Presensi</a></li>
						<?php } ?>
						<li><a href="#log_presensi" onclick="refreshDataLog()" data-toggle="tab">Data Log Presensi</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="presensi_harian">
							<div class="row">
								<div class="col-md-12">
									<div class="box box-primary">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fas fa-search fa-fw"></i> Filter Pencarian</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div style="padding-top: 20px;">
											<div class="box-body">
												<form id="form_filter_harian">
													<input type="hidden" name="usage" id="usage" value="all">
													<input type="hidden" name="mode" id="mode" value="">
													<!-- <input type="hidden" name="param" id="param" value=""> -->
													<div class="col-md-4">
														<div class="">
															<label>Pilih Bagian</label>
															<select class="form-control select2" id="bagian_harian_filter" name="bagian_filter" style="width: 100%;"></select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="">
															<label>Tanggal</label>
															<div class="has-feedback">
																<span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" class="form-control date-range-notime" id="tanggal_harian_filter" name="tanggal" placeholder="Tanggal">
															</div>
														</div>
													</div>
													<div class="col-md-4">
														<!-- <div class="">
															<label>Pilih Nama Karyawan</label>
															<select class="form-control select2" id="karyawan_filter" name="karyawan" style="width: 100%;"></select>
														</div> -->
														<div class="form-group">
															<label>Pilih Lokasi Kerja</label>
															<select class="form-control select2" id="lokasi_harian_filter" name="lokasi" style="width: 100%;"></select>
														</div>
													</div>
												</form>
											</div>
											<div class="box-footer">
												<div class="col-md-12">
													<div class="pull-right">
														<button type="button" onclick="tablePresensiHarian('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="box box-info">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fa fas fa-tasks fa-fw"></i> Data Presensi Harian</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="tablePresensiHarian('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div class="box-body">
											<div id="accordion">
												<div class="panel">
													<?php
													if (in_array($access['l_ac']['up_presensi'], $access['access'])) {
														if (in_array($access['l_ac']['add'], $access['access'])) {
															echo '<a href="#tambah" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Tambah Presensi</a> '; }
														// if (in_array($access['l_ac']['imp'], $access['access'])) {
														// 	echo '<input type="hidden" name="expresensi" value="ex">
														// 	<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import" style="margin-right: 4px;"><i class="fas fa-cloud-upload-alt"></i> Import</button>'; }
														if (in_array($access['l_ac']['exp'], $access['access'])) {
															echo '<div class="dropdown" style="float: left;margin-left: 5px;">
																<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-file-excel"></i> Export Data <span class="caret"></span>
																</button>
																<ul class="dropdown-menu">
																	<li><a onclick="rekap()">Rekap Presensi</a></li>
																	<li><a onclick="rekap_bulanan()">Rekap Presensi Bulanan</a></li>
																	<li><a onclick="absensi_harian()">Cetak Absensi Harian</a></li>
																</ul>
															</div>&nbsp;';
														}
														if (in_array($access['l_ac']['imp'], $access['access'])) {
															echo '<div class="dropdown" style="float: left;margin-left: 5px;">
																<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-refresh"></i> Sinkron Data <span class="caret"></span>
																</button>
																<ul class="dropdown-menu">
																	<li><a data-toggle="modal" data-target="#sync" aria-expanded="false" aria-controls="import" id="button_import_log" style="margin-right: 4px;"> Sinkron dengan Log Presensi</a></li>
																	<li><a data-toggle="modal" data-target="#sync_jdwl" id="button_import_jadwal" aria-expanded="false" aria-controls="import" style="margin-right: 4px;">Sinkron dengan Jadwal</a></li>
																</ul>
															</div>&nbsp;';
														}
														if (in_array($access['l_ac']['imp'], $access['access'])) {
															// echo '<input type="hidden" name="expresensi" value="ex">
															// <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#sync" aria-expanded="false" aria-controls="import" id="button_import_log" style="margin-right: 4px;"><i class="fas fa-refresh"></i> Syncronize Data</button>'; 
															// echo '<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#sync_jdwl" id="button_import_jadwal" aria-expanded="false" aria-controls="import" style="margin-right: 4px;"><i class="fas fa-refresh"></i> Syncronize Dengan Jadwal</button>'; 
														}
														if(in_array($access['l_ac']['imp'], $access['access'])) { ?>
															<div class="modal fade" id="import" role="dialog">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
																			<h4 class="modal-title">Import Data Dari Excel</h4>
																		</div>
																		<form id="form_import" action="#">
																			<div class="modal-body">
																				<div class="callout callout-info text-left">
																					<b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
																					<ul>
																						<li>Pastikan cell pada file Excel tidak ada yang di <b>MERGE</b>, jika ada yang di <b>MERGE</b> harap melakukan <b style="color: red">UNMERGE</b> terlebih dahulu!</li>
																						<li>Anda <b>HARUS</b> memilih mesin yang sesuai dengan file Excel!</li>
																					</ul>
																				</div>
																				<div class="form-group">
																					<label>Pilih Tanggal</label>
																					<div class="has-feedback">
																						<span class="fa fa-calendar form-control-feedback"></span>
																						<input type="text" class="form-control date-range-notime" id="tanggal_import" name="tanggal_import" placeholder="Tanggal">
																					</div>
																				</div>
																				<!-- <div class="form-group">
																					<label>Pilih Bagian</label>
																					<select class="form-control select2" id="bagian_import" name="bagian_import" required="required" style="width: 100%;"></select>
																				</div> -->
																				<p class="text-muted text-center">File Data Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
																				<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
																				<span class="input-group-btn text-center">
																					<div class="fileUpload btn btn-warning btn-flat">
																						<span><i class="fa fa-folder-open text-center"></i> Pilih File</span>
																						<input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
																					</div>
																				</span>  
																				<div class="clearfix text-center">
																					<div class="col-md-6">
																						<div class="radio icheck">
																							<label>
																								<input type="radio" name="kode_mesin" value="1" required="required"> Mesin 1 (Solution)
																							</label>
																						</div>
																					</div>
																					<div class="col-md-6">
																						<div class="radio icheck">
																							<label>
																								<input type="radio" name="kode_mesin" value="2" required="required"> Mesin 2 (FP2300)
																							</label>
																						</div>
																					</div>
																				</div>                    
																			</div> 
																			<div class="modal-footer">
																				<div id="progress2" style="float: left;"></div>
																				<button class="btn btn-primary all_btn_import" id="save" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
																				<button id="savex" type="submit" style="display: none;"></button>
																				<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
																			</div>
																		</form>
																	</div>
																</div>
															</div>
															<div class="modal fade" id="sync" role="dialog">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
																			<h4 class="modal-title">Syncronize Data Dari Log Presensi</h4>
																		</div>
																		<form id="form_sync" action="#">
																			<div class="modal-body">
																				<div class="callout callout-info text-left">
																					<b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
																					<ul>
																						<li>Pastikan data sudah di Import melalui Log Presensi untuk men-Syncronize Data Presensi</li>
																					</ul>
																				</div>
																				<div class="form-group">
																					<label>Pilih Lokasi Kerja</label>
																					<select class="form-control select2" id="lokasi_sync" name="lokasi" onchange="get_selet_emp(this.value)" style="width: 100%;"></select>
																				</div><hr>
																				<div class="row">
																					<div class="col-md-12">
																						<div class="col-md-6">
																							<label class="pull-left" style="vertical-align: middle;">
																								<span id="kar_off" style="font-size: 14pt;"><i class="far fa-square" aria-hidden="true"></i></span>
																								<span id="kar_on" style="display: none; font-size: 14pt;"><i class="far fa-check-square" aria-hidden="true"></i></span>
																								<span style="padding-bottom: 9px;vertical-align: middle;"><b>  Berdasarkan Karyawan</b></span>
																								<input type="hidden" name="all_kar">
																							</label>
																						</div>
																						<div class="col-md-6">
																							<label class="pull-left" style="vertical-align: middle;">
																								<span id="lev_off" style="font-size: 14pt;"><i class="far fa-square" aria-hidden="true"></i></span>
																								<span id="lev_on" style="display: none; font-size: 14pt;"><i class="far fa-check-square" aria-hidden="true"></i></span>
																								<span style="padding-bottom: 9px;vertical-align: middle;"><b>  Berdasarkan Level</b></span>
																								<input type="hidden" name="all_lev">
																							</label>
																						</div>
																					</div>
																				</div><hr>
																				<div class="form-group" id="karyawan_sync" style="display:none;">
																					<label style="vertical-align: middle;">Pilih Karyawan</label>
																					<label class="pull-right" style="vertical-align: middle;">
																						<span id="karx_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
																						<span id="karx_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>
																						<span style="padding-bottom: 9px;vertical-align: middle;"><b> Semua Karyawan Lokasi Tersebut</b></span>
																						<input type="hidden" name="all_karyawan">
																					</label>
																					<div id="karyawan_div">
																						<select class="form-control select2" id="data_karyawan_sync" multiple="multiple" name="karyawan[]" style="width: 100%;"></select>
																					</div>
																				</div>
																				<div class="form-group" id="level_sync" style="display:none;">
																					<label style="vertical-align: middle;">Pilih Level</label>
																					<label class="pull-right" style="vertical-align: middle;">
																						<span id="hari_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
																						<span id="hari_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>
																						<span style="padding-bottom: 9px;vertical-align: middle;"><b> Semua Level</b></span>
																						<input type="hidden" name="all_level">
																					</label>
																					<!-- <label>Pilih Level</label> -->
																					<div id="departemen_div">
																						<select class="form-control select2" id="departemen_sync" multiple="multiple" name="level[]" style="width: 100%;"></select>
																					</div>
																				</div>
																				<div class="form-group">
																					<label>Pilih Tanggal</label>
																					<div class="has-feedback">
																						<span class="fa fa-calendar form-control-feedback"></span>
																						<input type="text" class="form-control date-range-notime" id="tanggal_sync" name="tanggal_sync" placeholder="Tanggal">
																					</div>
																					<small class="text-muted"><font color="red">* Pastikan anda pilih rentang waktu masksimal <b>"3 HARI"</b> Jika lebih dari itu maka sistem akan membutuhkan waktu yang lebih lama untuk memproses data</font></small>
																				</div>                 
																			</div> 
																			<div class="modal-footer">
																				<div id="progressSync" style="float: left;"></div>
																				<button type="button" class="btn btn-success" onclick="syncData()"><i class="fa fa-refresh"></i> Sync Data</button>
																				<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
																			</div>
																		</form>
																	</div>
																</div>
															</div>
															<div class="modal fade" id="sync_jdwl" role="dialog">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
																			<h4 class="modal-title">Syncronize Data Dari Presensi Dengan Jadwal Kerja</h4>
																		</div>
																		<form id="form_sync_jdwl" action="#">
																			<div class="modal-body">
																				<div class="callout callout-info text-left">
																					<b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
																					<ul>
																						<li>Data yang akan di syncronize dengan jadwal adalah data presensi karyawan yang tidak melakukan presensi, atau karena hal lain</li>
																					</ul>
																				</div>
																				<div class="form-group">
																					<label>Pilih Karyawan</label>
																					<select class="form-control select2" id="karyawan_sync_jdwl" name="karyawan[]" multiple="multiple" style="width: 100%;"></select>
																				</div>
																				<div class="form-group">
																					<label>Pilih Tanggal</label>
																					<div class="has-feedback">
																						<span class="fa fa-calendar form-control-feedback"></span>
																						<input type="text" class="form-control date-range-notime" id="tanggal_sync_jdwl" name="tanggal" placeholder="Tanggal">
																					</div>
																				</div>                 
																			</div> 
																			<div class="modal-footer">
																				<div id="progressSyncJadwal" style="float: left;"></div>
																				<button type="button" class="btn btn-success" onclick="syncDataJadwal()"><i class="fa fa-refresh"></i> Sync Data</button>
																				<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
																			</div>
																		</form>
																	</div>
																</div>
															</div>
														<?php }  if (in_array($access['l_ac']['add'], $access['access'])) { ?>
															<div id="tambah" class="collapse">
																<br>
																<div class="box box-success">
																	<div class="box-header with-border">
																		<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Presensi Baru</h3>
																	</div>
																	<form id="form_add" class="form-horizontal">
																		<div class="box-body">
																			<div class="row">
																				<div class="col-md-2"></div>
																				<div class="col-md-8">
																					<div class="form-group">
																						<label>Karyawan</label>
																						<select class="form-control select2" id="karyawan_add" name="id_karyawan" style="width: 100%;"></select>
																					</div>
																					<div class="form-group">
																						<label>Tanggal</label>
																						<div class="has-feedback">
																							<span class="fa fa-calendar form-control-feedback"></span>
																							<input type="text" name="tanggal" id="tanggal_add" class="form-control date-picker" placeholder="Tanggal">
																						</div>
																						<span id="div_span_cek_jadwal"></span>
																					</div>
																					<div class="form-group" style="display:none;" id="div_kode_shift">
																						<label>Shift</label>
																							<select class="form-control select2" name="kode_master_shift" id="data_kode_master_shift_add" style="width: 100%;"></select>
																					</div>
																					<div class="col-md-6" style="padding-left: 0px;">
																						<div class="form-group" style="margin-right: 5px;">
																							<label>Jam Mulai</label>
																							<div class="input-group bootstrap-timepicker">
																								<div class="input-group-addon">
																									<i class="fa fa-clock-o"></i>
																								</div>
																								<input type="text" name="jam_mulai" id="data_mulai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai" required="required">
																							</div>
																						</div>
																					</div>
																					<div class="col-md-6" style="padding-right: 0px;">
																						<div class="form-group" style="margin-left: 5px;">
																							<label>Jam Selesai</label>
																							<div class="input-group bootstrap-timepicker">
																								<div class="input-group-addon">
																									<i class="fa fa-clock-o"></i>
																								</div>
																								<input type="text" name="jam_selesai" id="data_selesai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Selesai" required="required">
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="box-footer">
																			<div class="pull-right">
																				<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																			</div>
																		</div>
																	</form>
																</div>
															</div>
														<?php } 
													} ?>
													<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#cetak_presensi" aria-expanded="false" aria-controls="cetak" style="margin-right: 4px;"><i class="fas fa-print"></i> Cetak Presensi</button>
													<div class="modal fade" id="cetak_presensi" role="dialog">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close all_btn_cetak" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title">Cetak Data Presensi</h4>
																</div>
																<form id="form_cetak_presensi" action="#">
																	<div class="modal-body">
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Tanggal</label>
																					<div class="has-feedback">
																						<span class="fa fa-calendar form-control-feedback"></span>
																						<input type="text" class="form-control date-range-notime" id="tanggal_log_filter" name="tanggal" placeholder="Tanggal" required="required">
																						<span class="text-muted" style="font-size:11pt;color:red;"><small>Harap Pilih Rentang Tanggal Maksimal 31 Hari</small> </span>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Pilih Bagian</label>
																					<select class="form-control select2" id="bagian_presensi_cetak" name="bagian" style="width: 100%;" required="required"></select>
																				</div>
																			</div>
																			<br>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Pilih Karyawan</label>
																					<select class="form-control select2" id="karyawan_presensi_cetak" name="karyawan[]" style="width: 100%;" multiple="multiple" required="required"></select>
																				</div>
																			</div>
																			<div class="col-md-12" style="display: none;">
																				<div class="form-group">
																					<label class="pull-left" style="vertical-align: middle;">
																						<span id="head_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
																						<span id="head_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>
																						<span style="padding-bottom: 9px;vertical-align: middle;"><b> Jangan Tampilkan Header</b></span>
																						<input type="hidden" name="header" id="all_header">
																					</label>
																				</div>
																			</div>
																		</div>
																	</div> 
																</form>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-warning" onclick="do_cetak_presensi()"><i class="fas fa-file-pdf fa-fw"></i>Cetak PDF</button>
																		<button type="button" class="btn btn-default all_btn_importlog" data-dismiss="modal">Kembali</button>
																	</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
                                             	<div id="show_notif"></div>
													<?php $this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
														$days = $this->formatter->getDayDateFormatUserId($this->date);
													?>
													<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Petunjuk</label><br>- List Default menampilkan presensi Pada Hari <b><?=$days; ?></b><br>
													- Untuk Toleransi Absensi 3 jam, jadi kalau jadwal jam masuk 08.00 WIB, maka absen akan diakui jam masuk mulai rentang jam 05.00 WIB sampai jam 08.00 WIB. Berlaku untuk semua Sinkron Log Presensi.
													</div>
													<table id="table_presensi_harian" class="table table-bordered table-striped data-table" width="100%">
														<thead>
															<tr>
															<th>No.</th>
															<th>Nama</th>
															<th>Jabatan</th>
															<th>Tanggal</th>
															<th>Jam Masuk</th>
															<th>Jam Keluar</th>
															<th>Jumlah Jam kerja</th>
															<th>Shift</th>
															<th>Jadwal Kerja</th>
															<th>Ijin / Cuti</th>
															<th>Lembur</th>
															<th>Over</th>
															<th>Terlambat / Pulang Awal</th>
															<th>Hari Libur</th>
															<th>Aksi</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>						
						</div>
						<div class="tab-pane" id="data_presensi">
							<div class="row">
								<div class="col-md-12">
									<div class="box box-primary">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fas fa-search fa-fw"></i> Filter Pencarian</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div style="padding-top: 20px;">
											<div class="box-body">
												<form id="form_filter">
													<input type="hidden" name="usage" id="usage" value="all">
													<input type="hidden" name="mode" id="mode" value="">
													<!-- <input type="hidden" name="param" id="param" value=""> -->
													<div class="col-md-4">
														<div class="">
															<label>Pilih Bagian</label>
															<select class="form-control select2" id="bagian_filter" name="bagian" style="width: 100%;"></select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="">
															<label>Tanggal</label>
															<div class="has-feedback">
																<span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" class="form-control date-range-notime" id="tanggal_filter" name="tanggal" placeholder="Tanggal">
															</div>
														</div>
													</div>
													<div class="col-md-4">
														<!-- <div class="">
															<label>Pilih Nama Karyawan</label>
															<select class="form-control select2" id="karyawan_filter" name="karyawan" style="width: 100%;"></select>
														</div> -->
														<div class="form-group">
															<label>Pilih Lokasi Kerja</label>
															<select class="form-control select2" id="lokasi_filter" name="lokasi" style="width: 100%;"></select>
														</div>
													</div>
												</form>
											</div>
											<div class="box-footer">
												<div class="col-md-12">
													<div class="pull-right">
														<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="box box-info">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fa fas fa-tasks fa-fw"></i> Data Presensi</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="tableData('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="col-md-12">
													<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) Untuk melihat detail data Presensi karyawan yang Anda pilih</div>
													<table id="table_data" class="table table-bordered table-striped" width="100%">
														<thead>
															<tr>
																<th>No</th>
																<th>Nik</th>
																<th>Nama</th>
																<th>Jabatan</th>
																<th>Bagian</th>
																<th>Lokasi Kerja</th>
																<th>Jumlah Data</th>
																<th>Aksi</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="log_presensi">
							<div class="row">
								<div class="col-md-12">
									<div class="box box-primary">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fas fa-search fa-fw"></i> Filter Pencarian Log</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div style="padding-top: 20px;">
											<div class="box-body">
												<form id="form_filter_log">
													<input type="hidden" name="usage" id="usagelog" value="all">
													<input type="hidden" name="mode" id="mode" value="">
													<!-- <input type="hidden" name="param" id="param" value=""> -->
													<div class="col-md-4">
														<div class="">
															<label>Pilih Bagian</label>
															<select class="form-control select2" id="bagian_log_filter" name="bagian_filter" style="width: 100%;"></select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="">
															<label>Tanggal</label>
															<div class="has-feedback">
																<span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" class="form-control date-range-notime" id="tanggal_log_filter" name="tanggal" placeholder="Tanggal">
															</div>
														</div>
													</div>
													<div class="col-md-4">
														<!-- <div class="">
															<label>Pilih Nama Karyawan</label>
															<select class="form-control select2" id="karyawan_filter" name="karyawan" style="width: 100%;"></select>
														</div> -->
														<div class="form-group">
															<label>Pilih Lokasi Kerja</label>
															<select class="form-control select2" id="lokasi_log_filter" name="lokasi" style="width: 100%;"></select>
														</div>
													</div>
												</form>
											</div>
											<div class="box-footer">
												<div class="col-md-12">
													<div class="pull-right">
														<button type="button" onclick="logPresensi('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="box box-info">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fa fas fa-tasks fa-fw"></i> Data Log Presensi</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="logPresensi('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div class="box-body">
											<div id="accordion">
												<div class="panel">
													<?php if (in_array($access['l_ac']['up_presensi'], $access['access'])) {
														if (in_array($access['l_ac']['imp'], $access['access'])) {
															echo '<input type="hidden" name="expresensi" value="ex">
															<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import_log" aria-expanded="false" aria-controls="import" style="margin-right: 4px;"><i class="fas fa-cloud-upload-alt"></i> Import</button>'; 
														} 
													} ?>
													<button class="btn btn-warning" type="button" data-toggle="modal" data-target="#cetak_log" aria-expanded="false" aria-controls="cetak" style="margin-right: 4px;"><i class="fas fa-print"></i> Cetak</button>
													<?php if(in_array($access['l_ac']['imp'], $access['access'])) { ?>
														<div class="modal fade" id="import_log" role="dialog">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<div class="modal-header">
																		<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
																		<h4 class="modal-title">Import Data Dari Excel</h4>
																	</div>
																	<form id="form_import_log" action="#">
																		<div class="modal-body">
																			<div class="callout callout-info text-left">
																				<b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
																				<ul>
																					<li>Pastikan cell pada file Excel tidak ada yang di <b>MERGE</b>, jika ada yang di <b>MERGE</b> harap melakukan <b style="color: red">UNMERGE</b> terlebih dahulu!</li>
																					<li>Anda <b>HARUS</b> memilih mesin yang sesuai dengan file Excel!</li>
																					<li>Type File <b>**.dat</b> merupakan file hasil download dari <a href="http://solutioncloud.co.id/" target="_blank">solutioncloud.co.id</a> dan bisa langsung di import data log tersebut</li>
																					<li><a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"> Lihat Serial Number Mesin Absensi Solution</a></li>
																					<div class="collapse" id="collapseExample">
																						<div class="card card-body">
																							<?php
																								echo '<table width="100%" border="1">
																									<thead>
																										<tr>
																											<th style="padding:7px;">No.</th>
																											<th style="padding:7px;">Serial Number</th>
																											<th style="padding:7px;">Password</th>
																											<th style="padding:7px;">Lokasi</th>
																										</tr>
																									</thead>
																									<tbody>';
																									$no=1;
																									if(!empty($mesin_absen)){
																										foreach ($mesin_absen as $ma) {
																										echo '<tr>
																												<td style="padding:7px;">'.$no.'.</td>
																												<td style="padding:7px;">'.$ma->no.'</td>
																												<td style="padding:7px;">'.$ma->pass.'</td>
																												<td style="padding:7px;">'.$ma->keterangan.'</td>
																											</tr>';
																											$no++;
																										}
																									}
																									echo '</tbody>
																								</table>'
																							?>
																						</div>
																					</div>
																					<li>Type File <b>**.txt</b> merupakan file hasil export dari mesin absensi FP2300 di Gembongan</li>
																				</ul>
																			</div>
																			<p class="text-muted text-center">File Data Presensi harus tipe *.xlsx, *.dat, dan *.txt</p>
																			<input id="uploadFilexlog" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
																			<span class="input-group-btn text-center">
																				<div class="fileUpload btn btn-warning btn-flat">
																					<span><i class="fa fa-folder-open text-center"></i> Pilih File</span>
																					<input id="uploadBtnxlog" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnxlog','#uploadFilexlog','#savelog')" />
																				</div>
																			</span>  
																			<div class="clearfix text-center">
																				<div class="col-md-3">
																					<div class="radio icheck">
																						<label>
																							<input type="radio" name="kode_mesin" value="1" required="required"> Mesin 1 (Solution)
																						</label>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="radio icheck">
																						<label>
																							<input type="radio" name="kode_mesin" value="2" required="required"> Mesin 2 (FP2300)
																						</label>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="radio icheck">
																						<label>
																							<input type="radio" name="kode_mesin" value="dat" required="required"> Type *.dat
																						</label>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="radio icheck">
																						<label>
																							<input type="radio" name="kode_mesin" value="txt" required="required"> Type *.txt
																						</label>
																					</div>
																				</div>
																			</div>                    
																		</div> 
																		<div class="modal-footer">
																			<div id="progress2log" style="float: left;"></div>
																			<button class="btn btn-primary all_btn_importlog" id="savelog" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
																			<button id="savexlog" type="submit" style="display: none;"></button>
																			<button type="button" class="btn btn-default all_btn_importlog" data-dismiss="modal">Kembali</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													<?php } ?>
													<div class="modal fade" id="cetak_log" role="dialog">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close all_btn_cetak" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title">Cetak Data Log Presensi</h4>
																</div>
																<form id="form_cetak_log" action="#">
																	<div class="modal-body">
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Tanggal</label>
																					<div class="has-feedback">
																						<span class="fa fa-calendar form-control-feedback"></span>
																						<input type="text" class="form-control date-range-notime" id="tanggal_log_filter" name="tanggal" placeholder="Tanggal" required="required">
																					</div>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Pilih Bagian</label>
																					<select class="form-control select2" id="bagian_log_cetak" name="bagian" style="width: 100%;" required="required"></select>
																				</div>
																			</div>
																			<br>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Pilih Karyawan</label>
																					<select class="form-control select2" id="karyawan_log_cetak" name="karyawan[]" style="width: 100%;" multiple="multiple" required="required"></select>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<div class="form-group">
																					<label class="pull-left" style="vertical-align: middle;">
																						<span id="head_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
																						<span id="head_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>
																						<span style="padding-bottom: 9px;vertical-align: middle;"><b> Jangan Tampilkan Header</b></span>
																						<input type="hidden" name="header" id="all_header">
																					</label>
																				</div>
																			</div>
																		</div>
																	</div> 
																</form>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-warning" onclick="do_cetak_log()"><i class="fas fa-file-pdf fa-fw"></i>Cetak PDF</button>
																		<button type="button" class="btn btn-default all_btn_importlog" data-dismiss="modal">Kembali</button>
																	</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<?php $this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
														$days = $this->formatter->getDayDateFormatUserId($this->date);
													?>
													<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Petunjuk</label><br>List Default menampilkan data 1 Bulan Mulai <b><?= $days; ?></b> Sampai 1 Bulan sebelumnya</div>
													<table id="table_presensi_log" class="table table-bordered table-striped data-table" width="100%">
														<thead>
															<tr>
															<th>No.</th>
															<th>ID Finger</th>
															<th>Nama</th>
															<th>Jabatan</th>
															<th>Tanggal</th>
															<th>Jam</th>
															<th>Aksi</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
</div>
<!-- view -->
<div id="view" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bagian</label>
							<div class="col-md-6" id="data_bagian_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_view"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_view">
							<table id="table_view" class="table table-bordered" width="100%">
								<thead>
									<tr>
										<th class="nowrap">No.</th>
										<th class="nowrap">Tanggal</th>
										<th class="nowrap">Jam Masuk</th>
										<th class="nowrap">Jam Keluar</th>
										<th class="nowrap">Jumlah Jam Kerja</th>
										<th class="nowrap">Jadwal Jam Kerja</th>
										<th class="nowrap">Ijin Cuti</th>
										<th class="nowrap">Lembur</th>
										<th class="nowrap">Over</th>
										<th class="nowrap">Terlabat / Pulang Awal</th>
										<th class="nowrap">Hari Libur</th>
									</tr>
								</thead>
								<tbody id="body_view"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="view_harian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal"><i class="fa fa-times"></i></button>
				<h2 class="modal-title">Detail Data Presensi <b class="text-muted header_data"></b></h2>
				<input type="text" name="data_id_view" readonly="readonly">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_harian_view"><b></b></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_harian_view"><b></b></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal</label>
							<div class="col-md-6" id="data_tgl_presensi_view"><b></b></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jam Masuk</label>
							<div class="col-md-6" id="data_tglmulai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jam Keluar</label>
							<div class="col-md-6" id="data_tglselesai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Jam Kerja</label>
							<div class="col-md-6" id="data_jmljamkerja_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jadwal Kerja</label>
							<div class="col-md-6" id="data_jadwalkerja_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Over</label>
							<div class="col-md-6" id="data_over_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Telambat/Pulang Awal</label>
							<div class="col-md-6" id="data_telat_plg_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Ijin/Cuti</label>
							<div class="col-md-6" id="data_ijincuti_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lembur</label>
							<div class="col-md-6" id="data_lebur_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Hari Libur</label>
							<div class="col-md-6" id="data_libur_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_harian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_harian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_harian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_harian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_harian"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12" style="overflow: auto;">
							<div id="data_tabel_log_view"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['up_presensi'], $access['access'])) {
					if (in_array($access['l_ac']['edt'], $access['access'])) {
						echo '<button type="button" class="btn btn-danger" onclick="edit_shift_modal()"><i class="fa fa-edit"></i> Ganti Shift</button>';
						echo '<button type="button" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
					}
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="view_log" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal"><i class="fa fa-times"></i></button>
				<h2 class="modal-title">Detail Data Log Presensi <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_log_view"><b></b></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_log_view"><b></b></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal</label>
							<div class="col-md-6" id="data_tanggal_log_view"><b></b></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jam</label>
							<div class="col-md-6" id="data_jam_log_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_log"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_log"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_log"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_log"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_log"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<?php if (in_array($access['l_ac']['edt'], $access['access'])) { ?>
<!-- edit -->
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit  Presensi <b class="text-muted nama_kar"></b> <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit">
				<div class="modal-body">
					<input type="hidden" id="data_id_edit" name="id" value="">
					<input type="hidden" id="data_idk_edit" name="id_karyawan" value="">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-6 control-label">Nama</label>
								<div class="col-md-6" id="data_nama_kar_edit"><b></b></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-6 control-label">Tanggal</label>
								<div class="col-md-6" id="data_tanggal_kar_edit"><b></b></div>
							</div>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6" style="padding-left: 0px;">
								<div class="form-group" style="margin-right: 5px;">
									<label>Jam Mulai</label>
									<div class="input-group bootstrap-timepicker">
										<div class="input-group-addon">
											<i class="fas fa-clock"></i>
										</div>
										<input type="text" name="jam_mulai" id="data_mulai_edit" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai" required="required">
									</div>
								</div>
							</div>
							<div class="col-md-6" style="padding-right: 0px;">
								<div class="form-group" style="margin-left: 5px;">
									<label>Jam Selesai</label>
									<div class="input-group bootstrap-timepicker">
										<div class="input-group-addon">
											<i class="fas fa-clock"></i>
										</div>
										<input type="text" name="jam_selesai" id="data_selesai_edit" class="time-picker form-control field" placeholder="Tetapkan Jam Selesai" required="required">
									</div>
								</div>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">
							<input type="checkbox" class="icheck-class" name="dijadikan_alpa" style="padding-top:-5px;"> <b style="font-size:14pt;padding-top:5px;vertical-align: top;">&nbsp;&nbsp;&nbsp;Jadikan Alpha</b>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="edit_shift" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal"><i class="fa fa-times"></i></button>
				<h2 class="modal-title">Ganti Shift <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_ganti">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" id="data_id_ganti" name="id" value="">
							<input type="hidden" id="data_idk_ganti" name="id_karyawan" value="">
                     <input type="hidden" id="data_tanggal_ganti" name="tanggal">
							<div class="form-group">
								<label>Shift Lama</label>
									<select class="form-control select2" name="kode_master_shift_lama" id="data_kode_master_shift_lama" style="width: 100%;"></select>
							</div>
							<div class="form-group">
								<label>Shift Baru</label>
									<select class="form-control select2" name="kode_master_shift_baru" id="data_kode_master_shift_baru" style="width: 100%;"></select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="do_ganti_shift()" id="btn_ganti_shif" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>
<div id="absensi_harian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal"><i class="fa fa-times"></i></button>
				<h3 class="modal-title">Cetak Absensi Harian <b class="text-muted header_data"></b></h3>
			</div>
			<form id="form_absensi_harian">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Tanggal</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tanggal" class="form-control date-picker" placeholder="Tanggal">
								</div>
							</div>
							<div class="form-group">
								<label>Lokasi Kerja</label>
								<select class="form-control select2" id="lokasi_ah" name="lokasi" style="width: 100%;"></select>
							</div>
							<div class="form-group">
								<label>Mengetahui</label>
								<select class="form-control select2" name="mengetahui_ah" id="data_mengetahui_ah" style="width: 100%;"></select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" onclick="do_absensi_harian()"><i class="fas fa-file-pdf fa-fw"></i>Cetak PDF</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_presensi";
	var column="id_karyawan";
	$(document).ready(function(){
		// refreshData();
		realtimeAjax("<?php echo base_url('global_control/get_status_sync')?>");
		// realtimeAjax2("<?php echo base_url('global_control/get_value_sync')?>","button_import_log");
		realtimeAjax2("<?php echo base_url('global_control/get_value_sync')?>","button_import_jadwal");
		$('#hari_off').click(function(){
			$('#hari_off').hide();
			$('#hari_on').show();
			$('input[name="all_level"]').val('1');
			$('#departemen_div').hide();
		})
		$('#hari_on').click(function(){
			$('#hari_off').show();
			$('#hari_on').hide();
			$('input[name="all_level"]').val('0');
			$('#departemen_div').show();
		})
		$('#karx_off').click(function(){
			$('#karx_off').hide();
			$('#karx_on').show();
			$('input[name="all_karyawan"]').val('1');
			$('#karyawan_div').hide();
		})
		$('#karx_on').click(function(){
			$('#karx_off').show();
			$('#karx_on').hide();
			$('input[name="all_karyawan"]').val('0');
			$('#karyawan_div').show();
		})
		$('#kar_off').click(function(){
			$('#kar_off').hide();
			$('#kar_on').show();
			$('#lev_off').show();
			$('#lev_on').hide();
			$('input[name="all_kar"]').val('1');
			$('input[name="all_lev"]').val('0');
			$('#karyawan_sync').show();
			$('#level_sync').hide();
		})
		$('#kar_on').click(function(){
			$('#kar_off').show();
			$('#kar_on').hide();
			$('#lev_off').hide();
			$('#lev_on').show();
			$('input[name="all_kar"]').val('0');
			$('input[name="all_lev"]').val('1');
			$('#karyawan_sync').hide();
			$('#level_sync').show();
		})
		$('#lev_off').click(function(){
			$('#lev_off').hide();
			$('#lev_on').show();
			$('#kar_off').show();
			$('#kar_on').hide();
			$('input[name="all_kar"]').val('0');
			$('input[name="all_lev"]').val('1');
			$('#karyawan_sync').hide();
			$('#level_sync').show();
		})
		$('#lev_on').click(function(){
			$('#lev_off').show();
			$('#lev_on').hide();
			$('#kar_off').hide();
			$('#kar_on').show();
			$('input[name="all_kar"]').val('1');
			$('input[name="all_lev"]').val('0');
			$('#karyawan_sync').show();
			$('#level_sync').hide();
		})
		$('#import').modal({
			show: false,
			backdrop: 'static',
			keyboard: false
		})
		$('#download').click(function(){
			window.location.href = '<?php echo base_url('rekap/export_presensi'); ?>';
		})
		$('#save').click(function(){
	        $('.all_btn_import').attr('disabled','disabled');
	        setTimeout(function () {
	        	$('#savex').click();
	        },1000);
		})
		$('#form_import').submit(function(e){
			e.preventDefault();
	        $('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....');
			$('#progress2').show();
			var data_add = new FormData(this);
			var urladd = "<?php echo base_url('presensi/import_presensi'); ?>";
			submitAjaxFile(urladd,data_add, null, null,'.all_btn_import','table_presensi_harian');
			$('#table_presensi_harian').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#import').modal('hide');
			$('#progress2').hide();
		});
		$('#btn_tambah').click(function(){
			getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_add');
         	getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'data_kode_master_shift_add');
		});
		$('#form_add').validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				do_add()
			}
		})
		$('#msn_1_no').click(function(){
			$('#msn_1_no, #msn_2_yes').hide();
			$('#msn_1_yes, #msn_2_no').show();
			$('#data_kode_mesin_imp').val('1');
		})
		$('#msn_2_no').click(function(){
			$('#msn_2_no, #msn_1_yes').hide();
			$('#msn_2_yes, #msn_1_no').show();
			$('#data_kode_mesin_imp').val('2');
		})
		$('#head_off').click(function(){
			$('#head_off').hide();
			$('#head_on').show();
			$('input[name="header"]').val('1');
		})
		$('#head_on').click(function(){
			$('#head_off').show();
			$('#head_on').hide();
			$('input[name="header"]').val('0');
		})
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_filter');
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_import');
		unsetoption('bagian_import',['BAG001','BAG002']);
		$('#karyawan_add, #tanggal_add').change(function(){
			var idk = $('#karyawan_add').val();
			var tgl = $('#tanggal_add').val();
			var datax = {tanggal: tgl,id_kar: idk};
			var data=getAjaxData("<?php echo base_url('presensi/cekJadwalKerja')?>",datax);
			if (data['cek'] == 'false') {
            $('#div_span_cek_jadwal').html(data['msg']).css('color','red');
            $('#div_kode_shift').show();
				$('#div_span_cek_jadwal').attr('required','required');
			}else{
            $('#div_span_cek_jadwal').html(data['msg']).css('color','red');
            $('#div_kode_shift').hide();
				$('#div_span_cek_jadwal').removeAttr('required','required'); 
			};
		});
      	getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_harian_filter, #bagian_log_cetak, #bagian_presensi_cetak');
      	select_data('lokasi_harian_filter',url_select,'master_loker','kode_loker','nama','placeholder');
      	select_data('lokasi_filter',url_select,'master_loker','kode_loker','nama','placeholder');
      	select_data('lokasi_sync',url_select,'master_loker','kode_loker','nama','placeholder');
      	select_data('departemen_sync',url_select,'master_level_struktur','kode_level_struktur','nama','placeholder');
		unsetoption('departemen_sync',['STR001']);
		tablePresensiHarian('all');
		$('#savelog').click(function(){
	        $('.all_btn_importlog').attr('disabled','disabled');
	        setTimeout(function () {
	        	$('#savexlog').click();
	        },1000);
		})
		$('#form_import_log').submit(function(e){
			e.preventDefault();
	        $('#progress2log').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....');
			$('#progress2log').show();
			var data_add = new FormData(this);
			var urladd = "<?php echo base_url('presensi/import_presensi_log'); ?>";
			submitAjaxFile(urladd,data_add, null, null,'.all_btn_importlog','table_presensi_log');
			$('#table_presensi_log').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#import_log').modal('hide');
			$('#progress2log').hide();
		});
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_sync_jdwl');
		$('#bagian_log_cetak').change(function() {
			var bagian = $('#bagian_log_cetak').val();
			var data = { bagian: bagian };
			var callback = getAjaxData("<?php echo base_url('presensi/data_presensi_harian/get_karyawan_select/') ?>", data);
			$('#karyawan_log_cetak').html(callback['karyawan']);
		});
		$('#bagian_presensi_cetak').change(function() {
			var bagian = $('#bagian_presensi_cetak').val();
			var data = { bagian: bagian };
			var callback = getAjaxData("<?php echo base_url('presensi/data_presensi_harian/get_karyawan_select/') ?>", data);
			$('#karyawan_presensi_cetak').html(callback['karyawan']);
		});
	});
	function refreshData() {
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_filter');
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_filter');
      	select_data('lokasi_filter',url_select,'master_loker','kode_loker','nama','placeholder');
      	select_data('lokasi_harian_filter',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_filter',['BAG001','BAG002']);
	}
	function tableData(kode) {
		$('#usage').val(kode);
		$('#mode').val('data');
		$('#table_data').DataTable().destroy();
		if(kode == 'all'){
			$('#form_filter .select2').val('').trigger('change');
		}
		var data = $('#form_filter').serialize();
		var datax = {form:data,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/data_presensi/view_all/')?>",
				type: 'POST',
				data: datax
			},
			scrollX: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			]
		});
	}
	function rekap() {
		var data=$('#form_filter_harian').serialize();
		window.location.href = "<?php echo base_url('rekap/export_presensi')?>?"+data;
	}
	function rekap_bulanan() {
		$.redirect("<?php echo base_url('rekap/rekap_presensi_bulan'); ?>", {
			data_filter: $('#form_filter_harian').serialize(),
		},
		"POST", "_blank");
	}
	function view_modal(id) {
		$('#table_view').DataTable().destroy();
		var data={id_karyawan:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_presensi/view_one')?>",data);  
		$('.header_data').html(callback['nama']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_jabatan_view').html(callback['nama_jabatan']);
		$('#data_bagian_view').html(callback['bagian']);
		$('#data_loker_view').html(callback['nama_loker']);
		$('#data_status_view').html(callback['status']);
		$('#data_create_date_view').html(callback['create_date']);
		$('#data_update_date_view').html(callback['update_date']);
		$('#data_create_by_view').html(callback['create_by']);
		$('#data_update_by_view').html(callback['update_by']);
		var data_form = $('#form_filter').serialize();
		var datatab = {id_karyawan:callback['id_karyawan'],form: data_form};
		var calltable=getAjaxData("<?php echo base_url('presensi/data_presensi/tabel_view')?>",datatab);  
		$('#body_view').html(calltable['table']);
		$('#table_view').DataTable({
			scrollX: true
		});
		setTimeout(function () {
			$('#table_view').DataTable().destroy();
			$('#table_view').DataTable({
				scrollX: true
			});
		},600); 
		$('#view').modal('show');
  	}
  	function get_selet_emp(kode) {
  		var data={kode_bagian:kode};
  		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur/view_select')?>",data);
  		var text = "";
  		var i;
  		var selectedValues = new Array();
  		for (i = 0; i < callback.length; i++) {
  			selectedValues[i] = callback[i];
  		} 
  		$('#karyawan_add').val(selectedValues).trigger('change');
  	}
  	function checkFile(idf,idt,btnx) {
  		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots', 'dat', 'txt'];
  		pathFile(idf,idt,fext,btnx);
  	}
  	function delete_modal(id) {
  		var data={id_karyawan:id};
  		var callback=getAjaxData("<?php echo base_url('presensi/data_presensi/view_one')?>",data);
  		var datax={table:table,column:column,id:callback['id_karyawan'],nama:callback['nama']};
  		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  	}
	function do_add(){
      submitAjax("<?php echo base_url('presensi/add_presensi')?>",null,'form_add',null,null);
      $('#table_presensi_harian').DataTable().ajax.reload(function (){
        	Pace.restart();
      });
  	}
	function refreshDataHarian() {
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_harian_filter');
		select_data('lokasi_harian_filter',url_select,'master_loker','kode_loker','nama','placeholder');
		tablePresensiHarian('all');
	}
   	function tablePresensiHarian(kode) {
		var data = $('#form_filter_harian').serialize();
		var datax = {form:data,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		$('#table_presensi_harian').DataTable({
			ajax: {
				url: "<?php echo base_url('presensi/data_presensi_harian/view_all/')?>",
				type: 'POST',
				data: datax,
			},
			scrollX: true,
			bDestroy: true,
			scrollCollapse: true,
			fixedColumns:   {
				leftColumns: 3,
				rightColumns: 1
			},
			columnDefs: [
				{   targets: 0, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+(meta.row+1)+'.</center>';
					}
				},
				{   targets: [4,5], 
					width: '5%',
					render: function ( data, type, full, meta ) {
						if (data) {
							return '<center>'+data+'</center>';
						}else{
							return '<center><label class="label label-danger"><i class="fa fa-times"></i> Tidak Scan</label></center>';
						}
					}
				},
				{   targets: [6,7,8,9,10,11,12,13,14],
					width: '7%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
			]
		});
   	}
	function view_modal_harian(id) {
		var data={id_presensi:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_presensi_harian/view_one')?>",data);
		$('#view_harian').modal('show');  
		$('.header_data').html(callback['tgl_presensi']);
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_nama_harian_view').html(callback['nama_karyawan']);
		$('#data_jabatan_harian_view').html(callback['jabatan_karyawan']);
		$('#data_tgl_presensi_view').html(callback['tgl_presensi']);
		$('#data_tglmulai_view').html(callback['tgl_masuk']);
		$('#data_tglselesai_view').html(callback['tgl_selesai']);
		$('#data_jmljamkerja_view').html(callback['jam_kerja']);
		$('#data_jadwalkerja_view').html(callback['jadwal']);
		$('#data_over_view').html(callback['over']);
		$('#data_ijincuti_view').html(callback['ijin_cuti']);
		$('#data_lebur_view').html(callback['lembur']);
		$('#data_libur_view').html(callback['libur']);
		$('#data_telat_plg_view').html(callback['plg_trlmbt']);
		$('#data_status_harian').html(callback['status']);
		$('#data_create_date_harian').html(callback['create_date']);
		$('#data_update_date_harian').html(callback['update_date']);
		$('#data_create_by_harian').html(callback['create_by']);
		$('#data_update_by_harian').html(callback['update_by']);
		$('#data_tabel_log_view').html(callback['data_log']);
	}
	function edit_modal() {
		var id = $('input[name="data_id_view"]').val();
		var data={id_presensi:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_presensi_harian/view_one/')?>",data); 
		// $('.nama_kar').html(callback['nama_karyawan']); 
		// $('.header_data').html(callback['tgl_presensi']); 
		$('#data_nama_kar_edit').html(callback['nama_karyawan']); 
		$('#data_tanggal_kar_edit').html(callback['tgl_presensi']); 
		$('#data_id_edit').val(callback['id']);
		$('#data_idk_edit').val(callback['id_karyawan']);
		$('#data_tanggal_edit').datepicker('setDate', callback['tanggal']);
		$('#data_mulai_edit').timepicker('setTime', callback['jam_mulai']);
		$('#data_selesai_edit').timepicker('setTime', callback['jam_selesai']);
		$('#view_harian').modal('toggle');
		$('#edit').modal('show');
	}
	function do_edit(){
		submitAjax("<?php echo base_url('presensi/edit_presensi_harian')?>",'edit','form_edit',null,null);
		$('#table_presensi_harian').DataTable().ajax.reload(function (){
			Pace.restart();
		});
		$('#form_edit')[0].reset();
	}
	function refreshDataLog() {
      	getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_log_filter');
		select_data('lokasi_log_filter',url_select,'master_loker','kode_loker','nama','placeholder');
		logPresensi('all');
	}
   	function logPresensi(kode) {
		$('#usagelog').val(kode);
		var data = $('#form_filter_log').serialize();
		var datax = {form:data,access:"<?php echo $this->codegenerator->encryptChar($access);?>",flag:'search'};
		$('#table_presensi_log').DataTable({
			ajax: {
				url: "<?php echo base_url('presensi/data_presensi_log/view_all/')?>",
				type: 'POST',
				data: datax,
			},
			scrollX: true,
			bDestroy: true,
			scrollCollapse: true,
			columnDefs: [
				{   targets: 0, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+(meta.row+1)+'.</center>';
					}
				},
				{   targets: 3, 
					width: '25%',
					render: function ( data, type, full, meta ) {
						return data;
					}
				},
				{   targets: 4, 
					width: '19%',
					render: function ( data, type, full, meta ) {
						return data;
					}
				},
				{   targets: 5, 
					width: '12%',
					render: function ( data, type, full, meta ) {
						return data;
					}
				},
				{   targets: 6, 
					width: '3%',
					render: function ( data, type, full, meta ) {
						return data;
					}
				},
			]
		});
   	}
   	function view_modal_log(id) {
      	var data={id_temporari:id};
      	var callback=getAjaxData("<?php echo base_url('presensi/data_presensi_log/view_one')?>",data);
      	$('#view_log').modal('show');  
		$('.header_data').html(callback['tgl_presensi']);
		$('#data_nama_log_view').html(callback['nama_karyawan']);
		$('#data_jabatan_log_view').html(callback['jabatan_karyawan']);
		$('#data_tanggal_log_view').html(callback['tanggal']);
		$('#data_jam_log_view').html(callback['jam']);
		$('#data_status_log').html(callback['status']);
		$('#data_create_date_log').html(callback['create_date']);
		$('#data_update_date_log').html(callback['update_date']);
		$('#data_create_by_log').html(callback['create_by']);
		$('#data_update_by_log').html(callback['update_by']);
	}
	function syncData(){
		$('#progressSync').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, sedang mem-Syncronize data....');
		$('#progressSync').show();
		submitAjax("<?php echo base_url('presensi/sync_data_presensi')?>", 'sync', 'form_sync', null, null);
      	tablePresensiHarian('all');
		$('#progressSync').hide();
		$('#sync').modal('hide');
	}
	function syncDataJadwal(){
		$('#progressSyncJadwal').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, sedang mem-Syncronize data....');
		$('#progressSyncJadwal').show();
		submitAjax("<?php echo base_url('presensi/syncPresensiToJadwal')?>", 'sync_jdwl', 'form_sync_jdwl', null, null);
      	// tablePresensiHarian('all');
		// $('#progressSyncJadwal').hide();
		// $('#sync_jdwl').modal('hide');
	}
	function get_selet_emp(kode) {
		var data={kode_lokasi:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_presensi/view_select')?>",data);
		$('#data_karyawan_sync').html(callback);
	}
	function edit_shift_modal() {
      getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'data_kode_master_shift_lama');
      getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'data_kode_master_shift_baru');
		var id = $('input[name="data_id_view"]').val();
		var data={id_presensi:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_presensi_harian/view_one/')?>",data);  
		$('#data_id_ganti').val(callback['id']);
		$('#data_idk_ganti').val(callback['id_karyawan']);
		$('#data_tanggal_ganti').val(callback['tanggal_db']);
		$('#data_kode_master_shift_lama').val(callback['kode_shift']).trigger('change');
		// $('#data_kode_master_shift_baru').val(callback['ganti_shift']).trigger('change');
		$('#view_harian').modal('toggle');
		setTimeout(function () {
			$('#edit_shift').modal('show');
		},600); 
	}
	function do_ganti_shift(){
		submitAjax("<?php echo base_url('presensi/edit_ganti_shift')?>",'edit_shift','form_ganti',null,null);
		$('#table_presensi_harian').DataTable().ajax.reload(function (){Pace.restart();},false);
		$('#form_ganti')[0].reset();
	}
	function do_cetak_log() {
		var bagian = $('#bagian_log_cetak').val();
		var karyawan = $('#karyawan_log_cetak').val();
		if (bagian == '') {
			notValidParamxCustom('Harap Pilih Bagian !');
		} else if (karyawan == '') {
			notValidParamxCustom('Harap Pilih Karyawan !');
		}else{
			$.redirect("<?php echo base_url('pages/cetak_data_log'); ?>",  { data_filter: $('#form_cetak_log').serialize() }, "POST", "_blank");
		}
	}
	function do_cetak_presensi() {
		var bagian = $('#bagian_presensi_cetak').val();
		var karyawan = $('#karyawan_presensi_cetak').val();
		if (bagian == '') {
			notValidParamxCustom('Harap Pilih Bagian !');
		} else if (karyawan == '') {
			notValidParamxCustom('Harap Pilih Karyawan !');
		}else{
			$.redirect("<?php echo base_url('pages/cetak_data_presensi'); ?>",  { data_filter: $('#form_cetak_presensi').serialize() }, "POST", "_blank");
		}
	}
	function absensi_harian() {
		$('#absensi_harian').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee') ?>", 'data_mengetahui_ah');
      	select_data('lokasi_ah',url_select,'master_loker','kode_loker','nama','placeholder');
	}
	function do_absensi_harian() {
		var lokasi = $('#lokasi_ah').val();
		var mengetahui = $('#data_mengetahui_ah').val();
		if (lokasi == '') {
			notValidParamxCustom('Harap Pilih Lokasi !');
		} else if (mengetahui == '') {
			notValidParamxCustom('Harap Pilih Mengetahui !');
		}else{
			$.redirect("<?php echo base_url('pages/cetak_absensi_harian'); ?>",  { data_filter: $('#form_absensi_harian').serialize() }, "POST", "_blank");
		}
	}

</script>
