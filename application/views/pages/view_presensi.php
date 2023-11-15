<?php
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<style type="text/css">
	.form-control-feedback{
		display: none;
	}
	table.DTFC_Cloned tbody{
		overflow: hidden;
	}
	.DTFC_RightBodyLiner{
		overflow-y:unset !important;
	}
	.DTFC_RightBodyLiner table tbody tr td{
		padding-right: 25px;
	}
	.dark-mode .DTFC_RightBodyWrapper,.dark-mode .DTFC_LeftBodyWrapper{
		border-style: solid;
		border-width: 1px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fa fas fa-tasks fa-fw"></i> Presensi
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_presensi');?>"><i class="fa fas fa-tasks fa-fw"></i> Data Presensi</a></li>
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
						<?php
						$loker_grade = (!empty($profile['nama_grade'])) ? '<label class="label label-primary text-center">'.$profile['nama_grade'].' ('.$profile['nama_loker_grade'].')</label>' : '<label class="label label-danger text-center">Tidak Punya Loker Grade</label>';
						?>
						<p class="text-center"><?php echo $loker_grade; ?></p>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b>Tanggal Masuk</b> <label class="pull-right label label-info"><?php echo $this->formatter->getDateMonthFormatUser($profile['tgl_masuk']); ?></label>
							</li>
							<li class="list-group-item">
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

							<li class="list-group-item">
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
				<div class="box box-primary">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li><a href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="info" style="overflow:auto;">
								<table class='table table-bordered table-striped table-hover'>
									<tr>
										<th>Nomor Induk Karyawan</th>
										<td><?php echo ucwords($profile['nik']);?> <label class="label label-info">Username</label></td>
									</tr>
									<tr>
										<th>ID Finger</th>
										<td><?php
										if ($profile['id_finger'] == NULL) {
											echo '<label class="label label-danger">ID Finger Tidak Ada</label>';
										}else{
											echo $profile['id_finger'];
										}
										?></td>
									</tr>
									<tr>
										<th>Nomor KTP</th>
										<td><?php
										if ($profile['no_ktp'] == NULL) {
											echo '<label class="label label-danger">Nomor KTP Tidak Ada</label>';
										}else{
											echo $profile['no_ktp'];
										}
										?></td>
									</tr>
									<tr>
										<th>Nama Lengkap</th>
										<td><?php echo ucwords($profile['nama']);?></td>
									</tr>
									<tr>
										<th>Alamat Asli</th>
										<td><?php
										if ($profile['alamat_asal_jalan'] == NULL || $profile['alamat_asal_jalan'] == "") {
											echo '<label class="label label-danger">Alamat Belum Diinput</label>';
										}else{
											$alamat_skrg=(!empty($profile['alamat_asal_jalan'])?$profile['alamat_asal_jalan'].', ':null).(!empty($profile['alamat_asal_desa'])?$profile['alamat_asal_desa'].', ':null).(!empty($profile['alamat_asal_kecamatan'])?$profile['alamat_asal_kecamatan'].', ':null).(!empty($profile['alamat_asal_kabupaten'])?$profile['alamat_asal_kabupaten'].', ':null).(!empty($profile['alamat_asal_provinsi'])?$profile['alamat_asal_provinsi'].', ':null);
											echo ucwords($alamat_skrg).' <br>Kode Pos : ';
											if ($profile['alamat_asal_pos'] == 0) {
												echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
											}else{
												echo ucwords($profile['alamat_asal_pos']);
											}
										}  ?>
										</td>
									</tr>
									<tr>
										<th>Alamat Sekarang</th>
										<td><?php
											if ($profile['alamat_sekarang_jalan'] == NULL || $profile['alamat_sekarang_jalan'] == "") {
												echo '<label class="label label-danger">Alamat Belum Diinput</label>';
											}else{
												$jalan_sekarang = (!empty($profile['alamat_sekarang_jalan'])) ? $profile['alamat_sekarang_jalan'] : '';
												$desa_sekarang = (!empty($profile['alamat_sekarang_desa'])) ? ', '.$profile['alamat_sekarang_desa'] : '';
												$kec_sekarang = (!empty($profile['alamat_sekarang_kecamatan'])) ? ', '.$profile['alamat_sekarang_kecamatan'] : '';
												$kab_sekarang = (!empty($profile['alamat_sekarang_kabupaten'])) ? ', '.$profile['alamat_sekarang_kabupaten'] : '';
												$prov_sekarang = (!empty($profile['alamat_sekarang_provinsi'])) ? ', '.$profile['alamat_sekarang_provinsi'] : '';
												echo $jalan_sekarang.$desa_sekarang.$kec_sekarang.$kab_sekarang.$prov_sekarang.' <br>Kode Pos : ';
												if ($profile['alamat_sekarang_pos'] == 0) {
													echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
												}else{
													echo ucwords($profile['alamat_sekarang_pos']);
												}
											}?>
										</td>
									</tr>
									<tr>
										<th>Jabatan Sekarang</th>
										<td><?php if ($profile['nama_jabatan'] == "") {
												echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
											}else{
												echo $profile['nama_jabatan'];
											}?>
										</td>
									</tr>
								</table>  
							</div>
						</div>
					</div>  
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-search fa-fw"></i> Filter Pencarian</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div style="padding-top: 20px;">
						<div class="box-body">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<form id="form_filter">
									<input type="hidden" name="usage" id="usage" value="all">
									<input type="hidden" name="mode" id="mode" value="">
									<div class="">
										<label>Tanggal</label>
										<div class="has-feedback">
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" class="form-control date-range-notime" id="tanggal_filter" name="tanggal" placeholder="Tanggal">
										</div>
									</div>
								</form>
							</div>
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
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fas fa-tasks fa-fw"></i> Seluruh Data Presensi  <small><?php echo $profile['nama'];?></small></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="tableData('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-left" style="font-size: 8pt;">
									<?php
										echo form_open('rekap/export_presensi');
										if (in_array($access['l_ac']['add'], $access['access'])) {
											echo '<button class="btn btn-success" type="button" id="btn_tambah" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data</button>';
										}
										if (in_array($access['l_ac']['rkp'], $access['access'])) {
											echo '<input type="hidden" name="nik" value="'.$profile['nik'].'">';
											// echo '<button type="submit" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';
											echo '<button type="button" class="btn btn-warning" onclick="export_presensi()"><i class="fa fa-file-excel-o"></i> Export Data</button>';
										}
										echo form_close();
									?>
									<?php if(in_array($access['l_ac']['imp'], $access['access'])) { ?>
									<div class="modal fade" id="import" role="dialog">
										<div class="modal-dialog">
											<div class="modal-content text-center">
												<div class="modal-header">
													<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Import Data Dari Excel</h4>
												</div>
												<form id="form_import" action="#">
													<div class="modal-body">
														<p class="text-muted">File Data Template Master Indikator harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
														<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
														<span class="input-group-btn">
															<div class="fileUpload btn btn-warning btn-flat">
																<span><i class="fa fa-folder-open"></i> Pilih File</span>
																<input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
															</div>
														</span> 
														<div class="clearfix">
															<input type="hidden" name="kode_mesin" id="data_kode_mesin_imp" required>
															<div class="clearfix">
																<div class="col-sm-1">
																	<a id="msn_1_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
																	<a id="msn_1_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
																</div>
																<div class="col-sm-11" style="text-align: left;padding-top: 5px;">
																	Mesin 1 (Solution)
																</div>
															</div>
															<div class="clearfix">
																<div class="col-sm-1">
																	<a id="msn_2_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
																	<a id="msn_2_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
																</div>
																<div class="col-sm-11" style="text-align: left;padding-top: 5px;">
																	Mesin 2 (FP2300)
																</div>
															</div>
														</div>                         
													</div> 
													<div class="modal-footer">
														<div id="progress2" style="float: left;"></div>
														<button class="btn btn-primary all_btn_import" id="save" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
														<button id="savex" type="submit" style="display: none;"></button>
														<button type="button" class="btn btn-flat btn-success" id="download"><i class="fas fa-download"></i> Download Template</button>
														<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
								<div class="pull-right" style="font-size: 8pt;">
									<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
									<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
								</div>
							</div>
						</div>
						<?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
						<div id="add" class="collapse">
							<br>
							<div class="box box-success">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Presensi</h3>
								</div>
								<form id="form_add" class="form-horizontal">
									<input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan']; ?>">
									<div class="box-body">
										<div class="row">
											<div class="col-md-2"></div>
											<div class="col-md-8">
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
		            <?php } ?>
		            <style>
		            	th, td { white-space: nowrap; }
			         </style>
			         <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Petunjuk</label><br>
					 List Default menampilkan presensi 2 bulan terakhir<br>Export Default menampilkan 1 bulan terakhir</div>

			         <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
			         	<thead>
			         		<tr>
			         			<th>No.</th>
			         			<th>Tanggal</th>
			         			<th>Jam Masuk</th>
			         			<th>Jam Keluar</th>
			         			<th>Jumlah Jam kerja</th>
			         			<th>Jadwal jam Kerja</th>
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
	</section>
</div>
<!-- view -->
<div id="view" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data Presensi <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
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
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<!-- edit -->
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit  Presensi <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" id="data_id_edit" name="id" value="">
							<input type="hidden" id="data_idk_edit" name="id_karyawan" value="<?php echo $profile['id_karyawan']; ?>">
							<div class="form-group">
								<label>Tanggal</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tanggal" id="data_tanggal_edit" class="form-control date-picker" placeholder="Tanggal">
								</div>
								<span id="div_span_cek_jadwal_edit"></span>
							</div>
							<div class="form-group" style="display:none;" id="div_kode_shift_edit">
								<label>Shift</label>
									<select class="form-control select2" name="kode_master_shift" id="data_kode_master_shift_edit" style="width: 100%;"></select>
							</div>
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
				</div>
				<div class="modal-footer">
					<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
	var column="id_p_karyawan";
	$(document).ready(function(){
		$('#import').modal({
			show: false,
			backdrop: 'static',
			keyboard: false
		})
		$('#download').click(function(){
			window.location.href = '<?php echo base_url('rekap/export_template_presensi_one'); ?>';
		})
		$('#form_import').submit(function(e){
			e.preventDefault();
			var data_add = new FormData(this);
			var urladd = "<?php echo base_url('presensi/import_presensi'); ?>";
			submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
		});
		$('#btn_tambah').click(function(){
			select_data('id_dibuat_add',url_select,'karyawan','id_karyawan','nama','placeholder');
			select_data('id_diperiksa_add',url_select,'karyawan','id_karyawan','nama','placeholder');
			select_data('id_diketahui_add',url_select,'karyawan','id_karyawan','nama','placeholder');
		})
		$('#form_add').validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				do_add()
			}
		})
		$('#form_edit').validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				do_edit()
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
		tableData('all');
        getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'data_kode_master_shift_add');
		$('#tanggal_add').change(function(){
			var tgl = $('#tanggal_add').val();
			var datax = {tanggal: tgl,id_kar: '<?php echo $profile['id_karyawan']; ?>'};
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
		})
	});
	function tableData(kode) {
		$('#usage').val(kode);
		$('#mode').val('data');
		$('#table_data').DataTable().destroy();
	    setTimeout(function () {
			var form = $('#form_filter').serialize();
			var datax = {form:form,id_karyawan: '<?php echo $profile['id_karyawan']; ?>',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
			$('#table_data').DataTable({
				ajax: {
					url: "<?php echo base_url('presensi/view_presensi/view_all/')?>",
					type: 'POST',
					data: datax
				},
				fixedColumns:   {
					leftColumns: 2,
					rightColumns: 1
				},
				scrollCollapse: true,
				scrollX: true,
				autoWidth: false,
				columnDefs: [
				{   targets: 0, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+(meta.row+1)+'.</center>';
					}
				},
				{   targets: 11, 
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				],
				drawCallback: function( settings ) {
					$('[data-toggle="tooltip"]').tooltip();
				}
			});
	    },600); 
	}
	function view_modal(id) {
		var data={id_presensi:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_presensi/view_one/')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['tgl_presensi']);
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_tgl_presensi_view').html(callback['tgl_presensi']);
		$('#data_tglmulai_view').html(callback['tgl_masuk']);
		$('#data_tglselesai_view').html(callback['tgl_selesai']);
		$('#data_jmljamkerja_view').html(callback['jam_kerja']);
		$('#data_jadwalkerja_view').html(callback['jadwal']);
		$('#data_over_view').html(callback['over']);
		$('#data_telat_plg_view').html(callback['plg_trlmbt']);
		$('#data_ijincuti_view').html(callback['ijin_cuti']);
		$('#data_lebur_view').html(callback['lembur']);
		$('#data_libur_view').html(callback['libur']);
		$('#data_status_view').html(callback['status']);
		$('#data_create_date_view').html(callback['create_date']);
		$('#data_update_date_view').html(callback['update_date']);
		$('#data_create_by_view').html(callback['create_by']);
		$('#data_update_by_view').html(callback['update_by']);
	}
	function edit_modal() {
		var id = $('input[name="data_id_view"]').val();
		var data={id_presensi:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_presensi/view_one/')?>",data);  
		$('#data_id_edit').val(callback['id']);
		$('#data_idk_edit').val(callback['id_karyawan']);
		$('#data_tanggal_edit').datepicker('setDate', callback['tanggal']);
		$('#data_mulai_edit').timepicker('setTime', callback['jam_mulai']);
		$('#data_selesai_edit').timepicker('setTime', callback['jam_selesai']);
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
        getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'data_kode_master_shift_edit');
		$('#data_tanggal_edit').change(function(){
			var tgl = $('#data_tanggal_edit').val();
			var id_k = $('#data_idk_edit').val();
			var datax = {tanggal: tgl,id_kar: id_k};
			var data=getAjaxData("<?php echo base_url('presensi/cekJadwalKerja')?>",datax);
			if (data['cek'] == 'false') {
				$('#div_span_cek_jadwal_edit').html(data['msg']).css('color','red');
            	$('#div_kode_shift_edit').show();
				$('#div_kode_shift_edit').attr('required','required');
			}else{
            	$('#div_span_cek_jadwal_edit').html(data['msg']).css('color','red');
            	$('#div_kode_shift_edit').hide();
				$('#div_kode_shift_edit').removeAttr('required','required'); 
			};
		})
	}
	function delete_modal(id) {
		var data={id_presensi:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_presensi/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function checkFile(idf,idt,btnx) {
		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
		pathFile(idf,idt,fext,btnx);
	}
	function do_edit(){
		submitAjax("<?php echo base_url('presensi/edit_presensi_one')?>",'edit','form_edit',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
		$('#form_edit')[0].reset();
	}
	function do_add(){
		submitAjax("<?php echo base_url('presensi/add_presensi')?>",null,'form_add',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
		$('#form_add')[0].reset();
	}
	function export_presensi() {
		var mode = $('#mode').val();
		var tanggal = $('#tanggal_filter').val();
		var nik = "<?php echo $profile['nik']; ?>";
		var usage = 'view';

		$.redirect('<?php echo base_url('rekap/export_presensi_view')?>',
		{
			mode: $('#mode').val(),
			tanggal : $('#tanggal_filter').val(),
			nik : "<?php echo $profile['nik']; ?>",
			usage : $('#usage').val()
		}, "POST", "_blank");
	}

</script> 