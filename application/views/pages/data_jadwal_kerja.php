<style type="text/css">
th, td { 
	white-space: nowrap; 
}
table#table_data thead tr th, table#table_data tbody tr td, table.DTFC_Cloned thead tr th, table.DTFC_Cloned tbody tr td{
	white-space: pre;
}
table.DTFC_Cloned tbody{
	overflow: hidden;
}
/*.DTFC_LeftBodyLiner{overflow-y:unset !important}*/
.DTFC_RightBodyLiner{overflow-y:unset !important}

.dark-mode .DTFC_RightBodyWrapper,.dark-mode .DTFC_LeftBodyWrapper{
	border-style: solid;
	border-width: 1px;
}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-calendar-check fa-fw"></i> Data
			<small>Jadwal Kerja</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fa far fa-calendar-check fa-fw"></i> Jadwal kerja</li>
		</ol>
	</section>
	<section class="content">
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
								<div class="col-md-6">
									<div class="form-group">
										<label>Pilih Bagian</label>
										<select class="form-control select2" id="bagian_filter" name="bagian" style="width: 100%;"></select>
									</div>
									<div class="form-group">
										<label>Pilih Unit</label>
										<select class="form-control select2" id="lokasi_filter" name="lokasi" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Bulan</label>
										<?php
											$bulan_ser = $this->formatter->getMonth();
											$sel_ser = array(date('m'));
											$ex_ser = array('class'=>'form-control select2', 'id'=>'bulan_filter', 'style'=>'width:100%;','required'=>'required');
											echo form_dropdown('bulan',$bulan_ser,$sel_ser,$ex_ser);
										?>
									</div>
									<div class="form-group">
										<label>Tahun</label>
										<?php
											$tahun_ser = $this->formatter->getYear();
											$sels = array(date('Y'));
											$exs = array('class'=>'form-control select2', 'id'=>'tahun_filter', 'style'=>'width:100%;','required'=>'required');
											echo form_dropdown('tahun',$tahun_ser,$sels,$exs);
										?>
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
						<h3 class="box-title"><i class="fa far fa-calendar-check fa-fw"></i> Data Jadwal Kerja</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="tableData('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div id="accordion">
							<div class="panel">
								<?php 
								if (in_array($access['l_ac']['add'], $access['access'])) {
									echo '<a href="#tambah" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success "><i class="fa fa-plus"></i> Tambah Jadwal Kerja</a> ';
									echo '<a href="#tambah_group" data-toggle="collapse" id="btn_tambah_group" data-parent="#accordion" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Jadwal Kerja Group</a> '; }
								if (in_array($access['l_ac']['exp'], $access['access'])) {
									echo '<input type="hidden" name="param" value="all">';
									echo '<button type="button" onclick="rekap()" class="btn btn-warning" id="btn_print_excel"><i class="fa fa-file-excel-o"></i> Cetak Data</button>'; }
								if (in_array($access['l_ac']['rkp'], $access['access'])) { ?>
									<div class="btn-group">
										<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> EXIM Jadwal
											<span class="fa fa-caret-down"></span>
										</button>
										<ul class="dropdown-menu">
											<li><a onclick="export_template_jadwal()">Export Template Jadwal</a></li>
											<li><a onclick="import_jadwal()">Import Jadwal</a></li>
										</ul>
									</div>
								<?php }
								if (in_array($access['l_ac']['add'], $access['access'])) { 
									echo '<a href="#tambah_custom_group" data-toggle="collapse" title="Tambah Jadwal Custom Group" id="btn_tambah_custom_group" data-parent="#accordion" class="btn btn-danger pull-right"><i class="fas fa-users"></i></a> ';
									echo '<a href="#tambah_custom" data-toggle="collapse" title="Tambah Jadwal Custom" id="btn_tambah_custom" data-parent="#accordion" class="btn btn-info pull-right"><i class="fas fa-user-plus"></i></a> ';
								?>
								<div id="tambah" class="collapse">
									<br>
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Jadwal Kerja</h3>
									</div>
									<form id="form_add" class="form-horizontal">
										<div class="box-body">
											<div class="row">
												<div class="col-md-10">
													<div class="form-group">
														<label class="col-sm-3 control-label">Jabatan</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="kode_jabatan" id="data_kode_jabatan_add" style="width: 100%;" required="required" onchange="get_selet_emp(this.value)"></select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Pilih Karyawan</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="id_karyawan[]" id="karyawan_add" required="required" style="width: 100%;" multiple="multiple"></select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Shift</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="kode_master_shift[]" id="data_kode_master_shift_add" multiple="multiple" style="width: 100%;" required="required"></select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Tanggal Berlaku</label>
														<div class="col-sm-9">
															<div class="has-feedback">
																<span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" name="tgl_berlaku" id="data_tgl_berlaku_add" class="form-control pull-right date-range-notime" placeholder="Tanggal Berlaku" required="required" readonly>
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
								<div id="tambah_group" class="collapse">
									<br>
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Jadwal Kerja Group</h3>
									</div>
									<form id="form_group_add" class="form-horizontal">
										<div class="box-body">
											<div class="row">
												<div class="col-md-10">
													<div class="form-group">
														<label class="col-sm-3 control-label">Karyawan</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="id_karyawan[]" id="data_id_karyawan_group_add" multiple="multiple" style="width: 100%;" required="required"></select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Shift</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="kode_master_shift[]" id="data_kode_master_shift_group_add" multiple="multiple" style="width: 100%;" required="required"></select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Tanggal Berlaku</label>
														<div class="col-sm-9">
															<div class="has-feedback">
																<span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" name="tgl_berlaku" id="tgl_berlaku_group_add" class="form-control pull-right date-range-notime" placeholder="Tanggal Berlaku" required="required" readonly>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label"></label>
														<div class="col-sm-9">
															<input type="checkbox" name="mode" id="data_mode_add"> <b>Abaikan Jika Sudah Ada (Tidak memperbarui data yang sudah ada)</b><br>
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
								<div id="tambah_custom" class="collapse">
									<br>
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Jadwal Kerja Custom</h3>
									</div>
									<form id="form_group_add_c" class="form-horizontal">
										<div class="box-body">
											<div class="row">
												<div class="col-md-10">
													<div class="form-group">
														<label class="col-sm-3 control-label">Jabatan</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="kode_jabatan" id="data_kode_jabatan_add_c" style="width: 100%;" required="required" onchange="get_selet_emp_c(this.value)"></select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Pilih Karyawan</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="id_karyawan[]" id="karyawan_add_c" required="required" style="width: 100%;" multiple="multiple"></select>
														</div>
													</div>
													<div class="form-group clearfix">
														<label class="col-md-3 control-label">Shift</label>
														<div class="col-md-9">
															<select class="form-control select2" id="shift_c" name="kode_master_shift[]" required="required" style="width: 100%;">
																<option value="">Pilih Data</option>
																<option value="CSTM">CUSTOM</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Tanggal Berlaku</label>
														<div class="col-sm-9">
															<div class="has-feedback">
																<span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" name="tgl_berlaku" id="data_tgl_berlaku_add_c" class="form-control pull-right date-range-notime" placeholder="Tanggal Berlaku" required="required" readonly>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Jam Masuk</label>
														<div class="col-sm-9">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="jam_masuk" id="data_mulai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Masuk" required="required">
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Istirahat Mulai</label>
														<div class="col-sm-9">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="istirahat_mulai" id="data_istirahat_mulai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai Istirahat" required="required">
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Istirahat Selesai</label>
														<div class="col-sm-9">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="istirahat_selesai" id="data_istirahat_selesai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Istirahat Selesai" required="required">
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Jam Pulang</label>
														<div class="col-sm-9">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="jam_pulang" id="data_pulang_add" class="time-picker form-control field" placeholder="Tetapkan Jam Pulang" required="required">
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
								<div id="tambah_custom_group" class="collapse">
									<br>
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Jadwal Kerja Custom Group</h3>
									</div>
									<form id="form_group_add_cg" class="form-horizontal">
										<div class="box-body">
											<div class="row">
												<div class="col-md-10">
													<div class="form-group">
														<label class="col-sm-3 control-label">Karyawan</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="id_karyawan[]" id="data_id_karyawan_group_add_cg" multiple="multiple" style="width: 100%;" required="required"></select>
														</div>
													</div>
													<div class="form-group clearfix">
														<label class="col-md-3 control-label">Shift</label>
														<div class="col-md-9">
															<select class="form-control select2" id="shift_cg" name="kode_master_shift[]" required="required" style="width: 100%;">
																<option value="">Pilih Data</option>
																<option value="CSTM">CUSTOM</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Tanggal Berlaku</label>
														<div class="col-sm-9">
															<div class="has-feedback">
																<span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" name="tgl_berlaku" id="tgl_berlaku_group_add_cg" class="form-control pull-right date-range-notime" placeholder="Tanggal Berlaku" required="required" readonly>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Jam Masuk</label>
														<div class="col-sm-9">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="jam_masuk" id="data_mulai_add_cg" class="time-picker form-control field" placeholder="Tetapkan Jam Masuk" required="required">
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Istirahat Mulai</label>
														<div class="col-sm-9">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="istirahat_mulai" id="data_istirahat_mulai_add_cg" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai Istirahat" required="required">
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Istirahat Selesai</label>
														<div class="col-sm-9">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="istirahat_selesai" id="data_istirahat_selesai_add_cg" class="time-picker form-control field" placeholder="Tetapkan Jam Istirahat Selesai" required="required">
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Jam Pulang</label>
														<div class="col-sm-9">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="jam_pulang" id="data_pulang_add_cg" class="time-picker form-control field" placeholder="Tetapkan Jam Pulang" required="required">
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
								</div> <?php }?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
									<ul>
										<li>
											<b>List Default TIDAK menampilkan data</b>, Silahkan gunakan <b>Filter Pencarian</b> diatas untuk mencari data yang diinginkan.
										</li>
										<li>
											Pilih pada <b>Tanggal</b> untuk melihat detail <b>Shift</b> maupun melakukan update <b>Shift</b> pada tanggal atau hari yang dipilih.
										</li>
										<li>
											Pilih pada tombol <button class="btn btn-info btn-sm"><i class="fa fa-info-circle"></i></button> di kolom <b>Aksi</b> untuk melihat detail <b>Shift</b> maupun melakukan update <b>Shift</b> pada Karyawan yang dipilih.
										</li>
										<!-- <li> <?php $this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7)); ?>
											Data yang ditampilkan adalah Bulan ini <b><?php echo substr($this->formatter->getDateMonthFormatUser($this->date),3); ?></b>, untuk melihat data jadwal kerja lainnya silahakn filter pada <b>Form Filter Pencarian</b> diatas.
										</li> -->
									</ul>
								</div>
								<table id="table_data" class="table table-bordered table-striped" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama</th>
											<th>Jabatan</th>
											<th>Bagian</th>
											<?php
											for ($i=1; $i <=31 ; $i++) { 
												echo '<th>'.$this->formatter->zeroPadding($i).'</th>';
											} ?>
											<th style="width: 200px;">Bulan dan Tahun</th>
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
							<label class="col-md-6 control-label">Bulan</label>
							<div class="col-md-6" id="data_bulan_view"></div>
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
						<h2 class="modal-title">Jadwal Bulan <b class="text-muted header_data_bulan"></b></h2>
						<div id="data_tabel_view">
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
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_all_edit">
				<input type="hidden" name="id_karyawan" id="data_id_karyawan_edit">
				<input type="hidden" name="data_tgl_batas_edit" id="data_tgl_batas_edit">
				<input type="hidden" id="data_bulan_plain_edit" name="bulan">
				<input type="hidden" id="data_tahun_plain_edit" name="tahun">
				<input type="hidden" name="data_id_edit">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">NIK</label>
								<div class="col-md-6" id="data_nik_edit"></div>
							</div>
							<br>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Nama</label>
								<div class="col-md-6" id="data_nama_edit"></div>
							</div>
							<br>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Jabatan</label>
								<div class="col-md-6" id="data_jabatan_edit"></div>
							</div>
							<br>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Bulan</label>
								<div class="col-md-6" id="data_bulan_edit"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Status</label>
								<div class="col-md-6" id="data_status_edit"></div>
							</div>
							<br>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Dibuat Tanggal</label>
								<div class="col-md-6" id="data_create_date_edit"></div>
							</div>
							<br>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Diupdate Tanggal</label>
								<div class="col-md-6" id="data_update_date_edit"></div>
							</div>
							<br>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Dibuat Oleh</label>
								<div class="col-md-6" id="data_create_by_edit"></div>
							</div>
							<br>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Diupdate Oleh</label>
								<div class="col-md-6" id="data_update_by_edit"></div>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<h2 class="modal-title">Jadwal Bulan <b class="text-muted header_data_bulan"></b></h2>
							<div id="data_tabel_edit">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- view shift one-->
<div id="edit_shift" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Shift</h2>
			</div>
			<form id="form_one_edit">
				<div class="modal-body">
					<div class="row">
						<input type="hidden" id="data_id_edit_shift" name="id" value="">
						<input type="hidden" id="id_karyawan_edit_shift" name="id_karyawan" value="">
						<input type="hidden" id="data_urut_edit_shift" name="tgl_urut" value="">
						<input type="hidden" id="data_bulan_edit_shift" name="bulan" value="">
						<input type="hidden" id="data_tahun_edit_shift" name="tahun" value="">
						<div class="form-group clearfix col-md-12">
							<label class="col-md-4" style="text-align: right;">Nama karyawan</label>
							<div class="col-md-8" id="data_nama_edit_shift"></div>
						</div>
						<br>
						<div class="form-group clearfix">
							<label class="col-md-4" style="text-align: right;">Jabatan</label>
							<div class="col-md-8" id="data_jabatan_edit_shift"></div>
						</div>
						<br>
						<div class="form-group clearfix">
							<label class="col-md-4" style="text-align: right;">Kode Shift</label>
							<div id="data_kodeshift_edit_shift" class="col-md-8"></div>
						</div>
						<br>
						<div class="form-group clearfix">
							<label class="col-md-4" style="text-align: right;">Nama Shift</label>
							<div id="data_namashift_edit_shift" class="col-md-8"></div>
						</div>
						<br>
						<div class="form-group clearfix">
							<label class="col-md-4" style="text-align: right;">Jam</label>
							<div id="data_jam_edit_shift" class="col-md-8"></div>
						</div>
						<br>
						<div class="form-group clearfix">
							<label class="col-md-4" style="text-align: right;">Tanggal</label>
							<div id="data_tanggal_edit_shift" class="col-md-8"></div>
						</div>
						<br>
						<div class="form-group clearfix">
							<label class="col-md-4" style="text-align: right;">Shift Tanggal <b id="data_tgl_one_view"></b></label>
							<div class="col-md-8">
								<select id="data_kode_master_shift_one_edit" name="kode_master_shift" onchange="shiftCustom(this.value)" class="form-control select2" style="width: 100%;"></select>
							</div>
						</div>
						<br>
						<div id="div_custom" style="display:none;">
							<div class="form-group clearfix">
								<label class="col-sm-4" style="text-align: right;">Jam Masuk</label>
								<div class="col-sm-8">
									<div class="input-group bootstrap-timepicker">
										<div class="input-group-addon">
											<i class="fa fa-clock-o"></i>
										</div>
										<input type="text" name="jam_masuk" id="data_jam_masuk_one" class="time-picker form-control field" placeholder="Tetapkan Jam Masuk" required="required">
									</div>
								</div>
							</div>
							<br>
							<br>
							<div class="form-group clearfix">
								<label class="col-sm-4" style="text-align: right;">Istirahat Mulai</label>
								<div class="col-sm-8">
									<div class="input-group bootstrap-timepicker">
										<div class="input-group-addon">
											<i class="fa fa-clock-o"></i>
										</div>
										<input type="text" name="istirahat_mulai" id="data_jam_istirahat_mulai_one" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai Istirahat" required="required">
									</div>
								</div>
							</div>
							<br>
							<div class="form-group clearfix">
								<label class="col-sm-4" style="text-align: right;">Istirahat Selesai</label>
								<div class="col-sm-8">
									<div class="input-group bootstrap-timepicker">
										<div class="input-group-addon">
											<i class="fa fa-clock-o"></i>
										</div>
										<input type="text" name="istirahat_selesai" id="data_jam_istirahat_selesai_one" class="time-picker form-control field" placeholder="Tetapkan Jam Istirahat Selesai" required="required">
									</div>
								</div>
							</div>
							<br>
							<div class="form-group clearfix">
								<label class="col-sm-4" style="text-align: right;">Jam Pulang</label>
								<div class="col-sm-8">
									<div class="input-group bootstrap-timepicker">
										<div class="input-group-addon">
											<i class="fa fa-clock-o"></i>
										</div>
										<input type="text" name="jam_pulang" id="data_jam_pulang_one" class="time-picker form-control field" placeholder="Tetapkan Jam Pulang" required="required">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php if(in_array($access['l_ac']['imp'], $access['access'])) { ?>
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
							<li>Anda <b>HARUS</b> menggunakan template hasil download template jadwal kerja</li>
						</ul>
					</div>
					<p class="text-muted text-center">File Data Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
					<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
					<span class="input-group-btn text-center">
						<div class="fileUpload btn btn-warning btn-flat">
							<span><i class="fa fa-folder-open text-center"></i> Pilih File</span>
							<input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
						</div>
					</span>
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
<?php } ?>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_jadwal_kerja";
	var column="id_jadwal";
	$(document).ready(function(){
		refreshData();
		getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'data_kode_master_shift_one_edit');
		$('#btn_tambah').click(function(){
         	getSelect2("<?php echo base_url('master/master_jabatan/get_select2')?>",'data_kode_jabatan_add');
			getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_add');
			getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'data_kode_master_shift_add');
			unsetoption('data_kode_master_shift_add',['CSTM']);
		})
		$('#btn_tambah_group').click(function(){
			$('#data_mode_add').iCheck('check');
			getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'data_id_karyawan_group_add');
			getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'data_kode_master_shift_group_add');
			unsetoption('data_kode_master_shift_group_add',['CSTM']);
		})
		$('#btn_tambah_custom').click(function(){
         	getSelect2("<?php echo base_url('master/master_jabatan/get_select2')?>",'data_kode_jabatan_add_c');
			getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_add_c');
		})
		$('#btn_tambah_custom_group').click(function(){
			getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'data_id_karyawan_group_add_cg');
		})
		submitForm('form_add');submitForm('form_group_add');submitForm('form_all_edit');submitForm('form_one_edit');submitForm('form_group_add_c');submitForm('form_group_add_cg');
		tableData('all');
		$('#form_import').submit(function(e){
			e.preventDefault();
	        $('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....');
			$('#progress2').show();
			var data_add = new FormData(this);
			var urladd = "<?php echo base_url('rekap/import_jadwal_kerja'); ?>";
			submitAjaxFile(urladd,data_add, null, null,'.all_btn_import','table_data');
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#import').modal('hide');
			$('#progress2').hide();
		});
		$('#save').click(function(){
	        $('.all_btn_import').attr('disabled','disabled');
	        setTimeout(function () {
	        	$('#savex').click();
	        },1000);
		})
	});
  	function checkFile(idf,idt,btnx) {
  		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
  		pathFile(idf,idt,fext,btnx);
  	}
	function refreshData() {
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_filter');
		select_data('lokasi_filter',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_filter',['BAG001','BAG002']);
	}
	function shiftCustom(f) {
		setTimeout(function () {
			var name = $('#data_kode_master_shift_one_edit').val();
			if(name == 'CSTM') {
				$('#div_custom').show();
				$('#data_jam_masuk_one').attr('required','required');
				$('#data_jam_istirahat_mulai_one').attr('required','required');
				$('#data_jam_istirahat_selesai_one').attr('required','required');
				$('#data_jam_pulang_one').attr('required','required');
			}else {
				$('#div_custom').hide();
				$('#data_jam_masuk_one').removeAttr('required');
				$('#data_jam_istirahat_mulai_one').removeAttr('required');
				$('#data_jam_istirahat_selesai_one').removeAttr('required');
				$('#data_jam_pulang_one').removeAttr('required');
			}
		},100);
	}
	function tableData(kode) {
		$('#usage').val(kode);
		$('#mode').val('data');
		$('#table_data').DataTable().destroy();
		if(kode == 'all'){ $('#form_filter .select2').val('').trigger('change'); }
		var data = $('#form_filter').serialize();
		var datax = {form:data,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		$('#table_data').DataTable({
			scrollX:        true,
			scrollCollapse: true,
			fixedColumns:   {
				leftColumns: 0,
				rightColumns: 1
			},
			ajax: {
				url: "<?php echo base_url('presensi/data_jadwal_kerja/view_all/')?>",
				type: 'POST',
				data: datax
			},
			fixedColumns:   {
				leftColumns: 3,
				rightColumns: 1
			},
			scrollX: true,
			autoWidth: false,
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
	function get_selet_emp(kode) {
		var data={kode_jabatan:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_jadwal_kerja/view_select')?>",data);
		var text = "";
		var i;
		var selectedValues = new Array();
		for (i = 0; i < callback.length; i++) {
			selectedValues[i] = callback[i];
		} 
		$('#karyawan_add').val(selectedValues).trigger('change');
	}
	function get_selet_emp_c(kode) {
		var data={kode_jabatan:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_jadwal_kerja/view_select')?>",data);
		var text = "";
		var i;
		var selectedValues = new Array();
		for (i = 0; i < callback.length; i++) {
			selectedValues[i] = callback[i];
		} 
		$('#karyawan_add_c').val(selectedValues).trigger('change');
	}
	function rekap() {
		$('#usage').val('search');
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/export_data_jadwal_kerja')?>?"+data;
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				if(form=='form_add'){
					do_add()
				}else if(form=='form_group_add'){
					do_group_add()
				}else if(form=='form_all_edit'){
					do_all_edit()
				}else if(form=='form_one_edit'){
					do_one_edit()
				}else if(form=='form_group_add_c'){
					do_add_c()
				}else if(form=='form_group_add_cg'){
					do_add_cg()
				}else{
					notValidParamx();
				}
			}
		})
	}
	function view_shift(id,tgl) {
		if(tgl < 10){
			var tgl2=tgl;
		}else{
			var tgl2=tgl;
		}
		var data={id_jadwal:id,tgl:tgl2};
		var callback=getAjaxData("<?php echo base_url('presensi/data_jadwal_kerja/date_one')?>",data);
		$('#edit_shift').modal('toggle');
		$('#data_tgl_one_view').html(tgl);
		$('#data_id_edit_shift').val(callback['id']);
		$('#id_karyawan_edit_shift').val(callback['id_karyawan']);
		$('#data_urut_edit_shift').val(callback['tgl_urut']);
		$('#data_bulan_edit_shift').val(callback['bulan']);
		$('#data_tahun_edit_shift').val(callback['tahun']);
		$('#data_nama_edit_shift').html(callback['nama_karyawan']);
		$('#data_jabatan_edit_shift').html(callback['jabatan']);
		$('#data_kodeshift_edit_shift').html(callback['kode_shift']);
		$('#data_namashift_edit_shift').html(callback['shift']);
		$('#data_jam_edit_shift').html(callback['jam']);
		$('#data_tanggal_edit_shift').html(callback['tgl_jadwal']);
		$('#data_kode_master_shift_one_edit').val(callback['tgl_value']).trigger('change');
		$('#data_jam_masuk_one').val(callback['mulai']);
		$('#data_jam_istirahat_mulai_one').val(callback['i_mulai']);
		$('#data_jam_istirahat_selesai_one').val(callback['i_selesai']);
		$('#data_jam_pulang_one').val(callback['selesai']);
	}
	function view_modal(id) {
		var data={id_jadwal:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_jadwal_kerja/view_one')?>",data);
		$('.header_data').html(callback['nama_karyawan']);
		$('.header_data_bulan').html(callback['tgl_presensi']);
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_id_karyawan_edit').val(callback['id_karyawan']);
		$('#data_nik_view').html(callback['nik_karyawan']);
		$('#data_nama_view').html(callback['nama_karyawan']);
		$('#data_jabatan_view').html(callback['jabatan_karyawan']);
		$('#data_bulan_view').html(callback['tgl_presensi']);
		$('#data_status_view').html(callback['status']);
		$('#data_create_date_view').html(callback['create_date']);
		$('#data_update_date_view').html(callback['update_date']);
		$('#data_create_by_view').html(callback['create_by']);
		$('#data_update_by_view').html(callback['update_by']);
		$('#data_tabel_view').html(callback['table']);
		$('#view').modal('toggle');
		$('[data-toggle="tooltip"]').tooltip();
	}
	function edit_modal(id) {
		var id = $('input[name="data_id_view"]').val();
		var data={id_jadwal:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_jadwal_kerja/view_one')?>",data);  
		$('#view').modal('toggle');
		$('.header_data').html(callback['nama_karyawan']);
		$('.header_data_bulan').html(callback['tgl_presensi']);
		$('input[name="data_id_edit"]').val(callback['id']);
		$('#data_id_karyawan_edit').val(callback['id_karyawan']);
		$('#data_tgl_batas_edit').val(callback['tgl_batas']);
		$('#data_nik_edit').html(callback['nik_karyawan']);
		$('#data_nama_edit').html(callback['nama_karyawan']);
		$('#data_bulan_plain_edit').val(callback['bulan_plain']);
		$('#data_tahun_plain_edit').val(callback['tahun_plain']);
		$('#data_jabatan_edit').html(callback['jabatan_karyawan']);
		$('#data_bulan_edit').html(callback['tgl_presensi']);
		$('#data_status_edit').html(callback['status']);
		$('#data_create_date_edit').html(callback['create_date']);
		$('#data_update_date_edit').html(callback['update_date']);
		$('#data_create_by_edit').html(callback['create_by']);
		$('#data_update_by_edit').html(callback['update_by']);
		$('#data_tabel_edit').html(callback['table_edit']);
		$('.select2').select2();
		setTimeout(function () {
			$('#edit').modal('toggle');
		},600); 
		// $('[data-toggle="tooltip"]').tooltip();
		$('.select2').select2({
			placeholder:'Pilih Data',
			allowClear: true
		});
	}
	function shiftCustomEdit(i) {
		var name = $('#id_master_shift'+i+'').val();
		if(name == 'CSTM') {
			$('.time-picker2').timepicker({
				showInputs: false,
				showMeridian: false,
				showSeconds: false,
				minuteStep: 1,
			}).attr('readonly', 'readonly').css('cursor','pointer');
			$('#table_custom_shift'+i+'').show();
		}else {
			$('#table_custom_shift'+i+'').hide();
			$('#jam_masuk_one'+i+'').removeAttr('required');
			$('#jam_i_in_one'+i+'').removeAttr('required');
			$('#jam_i_out_one'+i+'').removeAttr('required');
			$('#jam_pulang_one'+i+'').removeAttr('required');
		}
	}
	function delete_modal(id) {
		var data={id_jadwal:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_jadwal_kerja/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama_karyawan']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_add(){
		submitAjax("<?php echo base_url('presensi/add_jadwal_kerja')?>",null,'form_add',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
		$('#data_kode_jabatan_add').val('').trigger('change');
		getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/refresh_shift')?>",'data_kode_master_shift_add');
	}
	function do_group_add(){
		submitAjax("<?php echo base_url('presensi/add_group_jadwal_kerja')?>",null,'form_group_add',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
		getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/refresh_shift')?>",'data_kode_master_shift_group_add');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/refreshkaryawan')?>",'data_id_karyawan_group_add');
	}
	function do_add_c(){
		submitAjax("<?php echo base_url('presensi/add_jadwal_kerja')?>",null,'form_group_add_c',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
		$('#data_kode_jabatan_add_c').val('').trigger('change');
		$('#data_mulai_add').val('');
		$('#data_istirahat_mulai_add').val('');
		$('#data_istirahat_selesai_add').val('');
		$('#data_pulang_add').val('');
	}
	function do_add_cg(){
		submitAjax("<?php echo base_url('presensi/add_group_jadwal_kerja')?>",null,'form_group_add_cg',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/refreshkaryawan')?>",'data_id_karyawan_group_add_cg');
		$('#data_mulai_add_cg').val('');
		$('#data_istirahat_mulai_add_cg').val('');
		$('#data_istirahat_selesai_add_cg').val('');
		$('#data_pulang_add_cg').val('');
	}
	function do_all_edit(){
		submitAjax("<?php echo base_url('presensi/edit_all_jadwal_kerja')?>",'edit','form_all_edit',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	// $('#form_all_edit')[0].reset();
	}
	function do_one_edit(){
		submitAjax("<?php echo base_url('presensi/edit_one_jadwal_kerja')?>",'edit_shift','form_one_edit',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	// $('#form_one_edit')[0].reset();
	}
	function export_template_jadwal() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/export_template_jadwal')?>?"+data;
	}
	function import_jadwal() {
		$('#import').modal('show');
	}
</script>
