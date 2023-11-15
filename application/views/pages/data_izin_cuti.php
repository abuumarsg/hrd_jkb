<div class="content-wrapper">
	<section class="content-header">
      <h1>
        <i class="fas fa-calendar-times"></i> Data
        <small>Izin / Cuti</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active"><i class="fas fa-calendar-times"></i> Izin / Cuti</li>
      </ol>
    </section>
    <section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#izin_harian" onclick="tableDataHarian('all')" data-toggle="tab">Data Izin Harian</a></li>
						<li><a href="#data_izin" onclick="tabIzinAll('all')" data-toggle="tab">Data Izin</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="izin_harian">
							<div class="row">
								<div class="col-md-12">
									<div class="box box-primary">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="tableDataHarian('all')" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div style="padding-top: 10px;">
											<form id="form_filter_harian">
												<input type="hidden" name="param" value="all">
												<input type="hidden" name="mode" value="data">
												<div class="box-body">
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label>Pilih Bagian</label>
																<select class="form-control select2" id="bagian_harian" name="bagian_harian" style="width: 100%;"></select>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label>Pilih Lokasi Kerja</label>
																<select class="form-control select2" id="unit_harian" name="unit_harian" style="width: 100%;"></select>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label>Tanggal</label>
																<div class="has-feedback">
																	<span class="fa fa-calendar form-control-feedback"></span>
																	<input type="text" class="form-control date-range-notime" id="tanggal_harian_filter" name="tanggal" placeholder="Tanggal">
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-2"></div>
														<div class="col-md-4">
															<div class="form-group">
																<label>Status Validasi</label>
																<?php
																	$yss = $this->otherfunctions->getStatusIzinListRekap();
																	$yss[null] = 'Pilih Data';
																	$sel2 = array(null);
																	$ex2 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'status_validasi');
																	echo form_dropdown('status_validasi',$yss,$sel2,$ex2);
																?>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label>Jenis Izin/Cuti</label>
																<select class="form-control select2" name="jenis_cuti" required="required" id="data_jenis_cuti_ser" style="width: 100%;"></select>
															</div>
														</div>
													</div>
												</div>
												<div class="box-footer">
													<div class="col-md-12">
														<div class="pull-right">
															<button type="button" onclick="tableDataHarian('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
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
									<div class="box box-info">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fas fa-calendar-times"></i> Data Izin / Cuti</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="tableDataHarian('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div class="box-body">
											<div id="accordion">
												<div class="panel">
													<?php if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<button href="#tambah_cuti" data-toggle="collapse" id="btn_tambah_cuti" data-parent="#accordion" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Tambah Izin / Cuti</button> ';}
													if (in_array($access['l_ac']['rst_cuti'], $access['access'])) {
														echo '<button id="btn_reset" class="btn btn-danger" style="margin-left: 5px;float: left;"><i class="fas fa-sync"></i> Setting Sisa Cuti</button>';}
													echo '<button id="btn_view_sisa" onclick="view_sisa_cuti()" class="btn btn-info" style="margin-left: 5px;float: left;"><i class="fas fa-eye"></i> Lihat Sisa Cuti</button>';
													if (in_array($access['l_ac']['rkp'], $access['access'])) {
														echo '<div class="dropdown" style="float: left;margin-left: 5px;">
															<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-print fa-fw"></i> Cetak Data <span class="caret"></span></button>
															<ul class="dropdown-menu">
																<li><a onclick="rekap_harian()"><i class="fa fa-file-excel-o"></i> Export Data</a></li>
																<li><a onclick="cetak_kartu()"><i class="fa fa-print fa-fw"></i> Cetak Kartu Monitor Absensi</a></li>
															</ul>
														</div>';
													}
													?>
													<?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
														<div id="tambah_cuti" class="collapse">
														<br>
														<br>
														<br>
															<div class="box box-success">
																<div class="box-header with-border">
																	<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Izin/Cuti</h3>
																</div>
																<form id="form_add_cuti" class="form-horizontal">
																<div class="box-body">
																	<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group">
																			<input type="hidden" id="id_karyawan_cuti" name="id_karyawan_cuti" value="">
																				<label class="col-sm-3 control-label">Nomor Izin/Cuti</label>
																				<div class="col-sm-8">
																					<input type="text" name="no_cuti" id="no_cuti_add" class="form-control" placeholder="Nomor Cuti" readonly="readonly">
																				</div>
																				<div class="col-sm-1">
																					<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">NIK</label>
																				<div class="col-sm-7">
																					<input type="text" name="nik_cuti" id="nik_cuti" class="form-control" placeholder="Nomor Induk Karyawan" required="required" readonly="readonly">
																				</div>
																				<div class="col-sm-1">
																					<button type="button" class="btn btn-default btn-sm" onclick="pilih_karyawan_cuti()">
																					<i class ="fa fa-plus"></i></button>
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Nama Karyawan</label>
																				<div class="col-sm-9">
																					<input type="text" name="nama_cuti" id="nama_cuti" class="form-control" placeholder="Nama Karyawan" readonly="readonly">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Jabatan</label>
																					<input type="hidden" name="jabatan_cuti" id="jabatan_cuti">
																				<div class="col-sm-9">
																					<input type="text" name="nama_jabatan_cuti" id="nama_jabatan_cuti" class="form-control" placeholder="Jabatan Asal Karyawan" readonly="readonly">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Lokasi</label>
																					<input type="hidden" name="lokasi_asal_cuti" id="kode_lokasi_cuti">
																				<div class="col-sm-9">
																					<input type="text" name="nama_lokasi_cuti" id="nama_lokasi_cuti" class="form-control" placeholder="Lokasi Asal Karyawan" readonly="readonly">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Tanggal Mulai - Selesai</label>
																				<div class="col-sm-9">
																					<div class="has-feedback">
																					<span class="fa fa-calendar form-control-feedback"></span>
																						<input type="text" name="tanggal" id="tanggal_izin_add" class="form-control pull-right date-range-30" placeholder="Tanggal Cuti" required="required" readonly="readonly">
																					</div>
																					<span id="div_span_tgl"></span>
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Jenis Izin/Cuti</label>
																				<div class="col-sm-9">
																					<select class="form-control select2" name="jenis_cuti" required="required" id="data_jenis_cuti_add" style="width: 100%;"></select>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<!-- <div class="form-group">
																			<label class="col-sm-3 control-label">SKD Dibayar</label>
																			<div class="col-sm-9">
																				<a id="skd_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
																				<a id="skd_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
																				<input type="hidden" name="skd" id="skd_add" class="form-control">
																			</div>
																		</div> -->
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Mengetahui</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="mengetahui" required="required" id="data_mengetahui_add"  style="width: 100%;"></select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Menyetujui</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="menyetujui" required="required" id="data_menyetujui_add"  style="width: 100%;"></select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Menyetujui 2</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="menyetujui2" required="required" id="data_menyetujui2_add"  style="width: 100%;"></select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Alasan Izin/Cuti</label>
																			<div class="col-sm-9">
																				<textarea name="alasan_cuti" class="form-control" required="required" placeholder="Alasan Izin/Cuti"></textarea>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Keterangan</label>
																			<div class="col-sm-9">
																				<textarea name="keterangan_cuti" class="form-control" placeholder="Keterangan"></textarea>
																				<span id="div_span_tgl_izin"></span><br>
																				<span id="div_span_sisa_cuti"></span>
																				<span id="div_span_notif_min_cuti"></span>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="box-footer">
																	<div class="pull-right">
																		<button type="submit" id="btn_save" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	</div>
																</div>
															</form>
															</div>
														</div>
													<?php } ?>
												</div>
											</div><br>
											<div class="row">
												<div class="col-md-12">
													<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail izin, memvalidasi, maupun melakukan update pada data izin karyawan</div>
													<table id="table_data_harian" class="table table-bordered table-striped" width="100%">
														<thead>
															<tr>
																<th>No</th>
																<th>No Izin</th>
																<th>Nama</th>
																<th>Jabatan</th>
																<th>Tanggal Izin</th>
																<th>Jenis Izin/Cuti</th>
																<th>Validasi</th>
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
						<div class="tab-pane" id="data_izin">
							<div class="row">
								<div class="col-md-12">
									<div class="box box-primary">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div style="padding-top: 10px;">
											<form id="form_filter">
												<input type="hidden" name="param" value="all">
												<input type="hidden" name="mode" value="data">
												<div class="box-body">
													<div class="col-md-6">
														<div class="form-group">
															<label>Pilih Bagian</label>
															<select class="form-control select2" id="bagian_export" name="bagian_export" style="width: 100%;"></select>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>Pilih Lokasi Kerja</label>
															<select class="form-control select2" id="unit_export" name="unit_export" style="width: 100%;"></select>
														</div>
													</div>
													<!-- <div class="col-md-6">
														<div class="form-group">
															<label>Bulan</label>
															<select class="form-control select2" id="bulan_export" name="bulan_export" style="width: 100%;">
																<option></option>
																<?php
																// for ($i=1; $i <= 12; $i++) { 
																// 	echo '<option value="'.$this->formatter->zeroPadding($i).'">'.$this->formatter->getNameOfMonth($i).'</option>';
																// }
																?>
															</select>
														</div>
														<div class="form-group">
															<label>Tahun</label>
															<select class="form-control select2" id="tahun_export" name="tahun_export" style="width: 100%;">
																<option></option>
																<?php
																// $year = $this->formatter->getYear();
																// foreach ($year as $yk => $yv) {
																// 	echo '<option value="'.$yk.'">'.$yv.'</option>';
																// }
																?>
															</select>
														</div>
													</div> -->
												</div>
												<div class="box-footer">
													<div class="col-md-12">
														<div class="pull-right">
															<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
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
									<div class="box box-info">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fas fa-calendar-times"></i> Data Izin / Cuti</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div class="box-body">
											<!-- <div id="accordion">
												<div class="panel">
													<?php if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<button href="#tambah_cuti" data-toggle="collapse" id="btn_tambah_cuti" data-parent="#accordion" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Tambah Izin / Cuti</button> ';}
													if (in_array($access['l_ac']['rst_cuti'], $access['access'])) {
														echo '<button id="btn_reset" class="btn btn-danger" style="margin-left: 5px;float: left;"><i class="fas fa-sync"></i> Setting Sisa Cuti</button>';}
													echo '<button id="btn_view_sisa" onclick="view_sisa_cuti()" class="btn btn-info" style="margin-left: 5px;float: left;"><i class="fas fa-eye"></i> Lihat Sisa Cuti</button>';
													if (in_array($access['l_ac']['rkp'], $access['access'])) {
														echo '<div class="dropdown" style="float: left;margin-left: 5px;">
															<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-print fa-fw"></i> Cetak Data <span class="caret"></span></button>
															<ul class="dropdown-menu">
																<li><a onclick="rekap()"><i class="fa fa-file-excel-o"></i> Export Data</a></li>
																<li><a onclick="cetak_kartu()"><i class="fa fa-print fa-fw"></i> Cetak Kartu Monitor Absensi</a></li>
															</ul>
														</div>';
													}
													?>
													<?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
														<div id="tambah_cuti" class="collapse">
														<br>
														<br>
														<br>
															<div class="box box-success">
																<div class="box-header with-border">
																	<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Izin/Cuti</h3>
																</div>
																<form id="form_add_cuti" class="form-horizontal">
																<div class="box-body">
																	<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group">
																			<input type="hidden" id="id_karyawan_cuti" name="id_karyawan_cuti" value="">
																				<label class="col-sm-3 control-label">Nomor Izin/Cuti</label>
																				<div class="col-sm-8">
																					<input type="text" name="no_cuti" id="no_cuti_add" class="form-control" placeholder="Nomor Cuti">
																				</div>
																				<div class="col-sm-1">
																					<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">NIK</label>
																				<div class="col-sm-7">
																					<input type="text" name="nik_cuti" id="nik_cuti" class="form-control" placeholder="Nomor Induk Karyawan" required="required" readonly="readonly">
																				</div>
																				<div class="col-sm-1">
																					<button type="button" class="btn btn-default btn-sm" onclick="pilih_karyawan_cuti()">
																					<i class ="fa fa-plus"></i></button>
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Nama Karyawan</label>
																				<div class="col-sm-9">
																					<input type="text" name="nama_cuti" id="nama_cuti" class="form-control" placeholder="Nama Karyawan" readonly="readonly">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Jabatan</label>
																					<input type="hidden" name="jabatan_cuti" id="jabatan_cuti">
																				<div class="col-sm-9">
																					<input type="text" name="nama_jabatan_cuti" id="nama_jabatan_cuti" class="form-control" placeholder="Jabatan Asal Karyawan" readonly="readonly">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Lokasi</label>
																					<input type="hidden" name="lokasi_asal_cuti" id="kode_lokasi_cuti">
																				<div class="col-sm-9">
																					<input type="text" name="nama_lokasi_cuti" id="nama_lokasi_cuti" class="form-control" placeholder="Lokasi Asal Karyawan" readonly="readonly">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Tanggal Mulai - Selesai</label>
																				<div class="col-sm-9">
																					<div class="has-feedback">
																					<span class="fa fa-calendar form-control-feedback"></span>
																						<input type="text" name="tanggal" id="tanggal_izin_add" class="form-control pull-right date-range" placeholder="Tanggal Cuti" required="required" readonly="readonly">
																					</div>
																					<span id="div_span_tgl"></span>
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Jenis Izin/Cuti</label>
																				<div class="col-sm-9">
																					<select class="form-control select2" name="jenis_cuti" required="required" id="data_jenis_cuti_add" style="width: 100%;"></select>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="form-group">
																			<label class="col-sm-3 control-label">SKD Dibayar</label>
																			<div class="col-sm-9">
																				<a id="skd_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
																				<a id="skd_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
																				<input type="hidden" name="skd" id="skd_add" class="form-control">
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Mengetahui</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="mengetahui" required="required" id="data_mengetahui_add"  style="width: 100%;"></select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Menyetujui</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="menyetujui" required="required" id="data_menyetujui_add"  style="width: 100%;"></select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Menyetujui 2</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="menyetujui2" required="required" id="data_menyetujui2_add"  style="width: 100%;"></select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Alasan Izin/Cuti</label>
																			<div class="col-sm-9">
																				<textarea name="alasan_cuti" class="form-control" required="required" placeholder="Alasan Izin/Cuti"></textarea>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Keterangan</label>
																			<div class="col-sm-9">
																				<textarea name="keterangan_cuti" class="form-control" placeholder="Keterangan"></textarea>
																				<span id="div_span_tgl_izin"></span><br>
																				<span id="div_span_sisa_cuti"></span>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="box-footer">
																	<div class="pull-right">
																		<button type="submit" id="btn_save" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	</div>
																</div>
															</form>
															</div>
														</div>
													<?php } ?>
												</div>
											</div><br> -->
											<div class="row">
												<div class="col-md-12">
													<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail izin, memvalidasi, maupun melakukan update pada data izin karyawan</div>
													<table id="table_data" class="table table-bordered table-striped" width="100%">
														<thead>
															<tr>
																<th>No</th>
																<th>NIK</th>
																<th>Nama</th>
																<th>Jabatan</th>
																<th>Bagian</th>
																<th>Lokasi Kerja</th>
																<th>Tanggal Pengajuan</th>
																<th>Jumlah Izin/Cuti</th>
																<th>Validasi</th>
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
<div class="modal modal-default fade" id="modal_pilih_karyawan_cuti" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h2 class="modal-title">Pilih Karyawan</h2>
		    </div>
			<div class="modal-body">
					<table id="table_pilih_cuti" class="table table-bordered table-striped table-responsive" width="100%">
						<thead>
							<tr>
								<th width="7%">NO</th>
								<th width="25%">NIK</th>
								<th width="25%">Nama Karyawan</th>
								<th width="25%">Jabatan</th>
								<th>Lokasi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
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
          <div class="col-md-2">
          	<img class="profile-user-img img-responsive img-circle view_photo" id="data_foto_view" data-source-photo="" src="" alt="User profile picture" style="width: 100%;">
          </div>
          <div class="col-md-5">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">NIK</label>
              <div class="col-md-6" id="data_nik_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama</label>
              <div class="col-md-6" id="data_nama_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Lokasi</label>
              <div class="col-md-6" id="data_loker_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jabatan</label>
              <div class="col-md-6" id="data_jabatan_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Bagian</label>
              <div class="col-md-6" id="data_bagian_view"></div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Status</label>
              <div class="col-md-6" id="data_status_view">
              
              </div>
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
              <div class="col-md-6" id="data_create_by_view">
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Oleh</label>
              <div class="col-md-6" id="data_update_by_view">
              </div>
            </div>
          </div>
        </div>
        <hr>
        	<h4 style="text-align: center;"><b>Daftar Izin Cuti</b></h4>
        <div class="row">
        	<div class="col-md-12">
        		<div id="data_tabel_view"></div>
        	</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>
<?php if (in_array($access['l_ac']['rst_cuti'], $access['access'])) { ?>
<div id="reset" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Setting Sisa Cuti</h2>
			</div>
			<form id="form_reset" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p>Pilih karyawan dan masukkan jumlah cuti karyawan untuk mengatur jumlah cuti karyawan ataupun untuk mereset sisa cuti.</p>
							<div class="form-group clearfix">
								<div class="col-md-12">
									<label>Karyawan</label>
										<span style="padding-bottom: 9px;vertical-align: middle;" class="pull-right"><b>&nbsp;&nbsp;&nbsp;Semua Karyawan</b></span>
										<span id="kar_off" style="font-size: 20px;" onclick="karKlik();"><i class="far fa-square pull-right" aria-hidden="true"></i></span>
										<span id="kar_on" style="display: none; font-size: 20px;" onclick="karKlik();"><i class="far fa-check-square pull-right" aria-hidden="true"></i></span>
										<input type="hidden" name="kar_sisa">
										<select class="form-control select2" name="karyawan_reset[]" id="data_karyawan_reset" multiple="multiple" required="required" style="width: 100%;max-height: 40%;"></select>
								</div>
							</div>
							<div class="form-group clearfix">
								<div class="col-md-12">
									<label>Jumlah Cuti</label>
										<input type="number" placeholder="Masukkan Jumlah Cuti" name="jumlah_cuti" id="jumlah_cuti" class="form-control" required="required">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="btn_reset_save" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>
<div id="cetak_kartu" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Cetak Kartu Monitor Absensi Karyawan</h2>
			</div>
			<form id="form_kartu" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group clearfix">
								<div class="col-md-12">
									<label>Karyawan</label>
									<span style="padding-bottom: 9px;vertical-align: middle;" class="pull-right"><b>&nbsp;&nbsp;&nbsp;Semua Karyawan</b></span>
									<span id="kartu_off" style="font-size: 20px;" onclick="kartuKlik();"><i class="far fa-square pull-right" aria-hidden="true"></i></span>
									<span id="kartu_on" style="display: none; font-size: 20px;" onclick="kartuKlik();"><i class="far fa-check-square pull-right" aria-hidden="true"></i></span>
									<input type="hidden" name="kartu_sisa">
									<select class="form-control select2" name="karyawan_kartu[]" id="data_karyawan_kartu" multiple="multiple" required="required" style="width: 100%;max-height: 40%;"></select>
								</div>
							</div>
							<div class="form-group clearfix">
								<div class="col-md-12">
									<label>Tahun</label>
									<select class="form-control select2" id="tahun_monitor" name="tahun_monitor" style="width: 100%;">
										<option></option>
										<?php
										$year = $this->formatter->getYear();
										foreach ($year as $yk => $yv) {
											echo '<option value="'.$yk.'">'.$yv.'</option>';
										}?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="do_kartu()" class="btn btn-success"><i class="fa fa-print fa-fw"></i> Cetak</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="view_sisa_cuti" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Sisa Cuti</h2>
			</div>
			<form id="form_reset" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div id="lihat_sisa_cuti"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="m_need" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_need">
				<div class="modal-body text-center">
					<input type="hidden" id="data_id_need" name="id">
					<input type="hidden" id="data_idk_need" name="id_kar">
					<input type="hidden" id="data_jenis_need" name="jenis">
					<p>Mohon Validasi Izin / Cuti Karyawan atas nama <b id="data_name_need" class="header_data"></b> berikut !!</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(2,0,'m_need')" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
				<button type="button" onclick="do_validasi(2,1,'m_need')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
			</div>
		</div>
	</div>
</div>
<div id="m_yes" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_yes">
				<div class="modal-body text-center">
					<input type="hidden" id="data_id_yes" name="id">
					<input type="hidden" id="data_idk_yes" name="id_kar">
					<input type="hidden" id="data_jenis_yes" name="jenis">
					<p>Apakah Anda yakin akan mengubah status izin / cuti dari <b class="text-green">DiIzinkan</b> menjadi <b class="text-red">Tidak Diizinkan</b></b> atas nama karyawan <b id="data_name_yes" class="header_data"></b> ??</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(1,0,'m_yes')" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="m_no" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_no">
				<div class="modal-body text-center">
					<input type="hidden" id="data_id_no" name="id">
					<input type="hidden" id="data_idk_no" name="id_kar">
					<input type="hidden" id="data_jenis_no" name="jenis">
					<p>Apakah Anda yakin akan mengubah status izin / cuti dari <b class="text-red">Tidak Diizinkan</b> menjadi <b class="text-green">DiIzinkan</b></b> atas nama karyawan <b id="data_name_no" class="header_data"></b> ??</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(0,1,'m_no')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- view -->
<div id="view_harian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view_harian">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_harian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_harian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Izin/Cuti</label>
							<div class="col-md-6" id="data_no_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jenis Izin/Cuti</label>
							<div class="col-md-6" id="data_jenis_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mulai</label>
							<div class="col-md-6" id="data_mulai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Selesai</label>
							<div class="col-md-6" id="data_selesai_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">SKD Dibayar</label>
							<div class="col-md-6" id="data_skd_view"></div>
						</div> -->
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alasan</label>
							<div class="col-md-6" id="data_alasan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui 2</label>
							<div class="col-md-6" id="data_menyetujui2_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_harian">
							</div>
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
							<div class="col-md-6" id="data_create_by_harian">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_harian">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" id="btn_edit_view" class="btn btn-info" onclick="edit_modal_harian()" style="display:none:"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit -->
<div id="edit_harian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<input type="hidden" id="data_id_edit" name="id" value="">
							<input type="hidden" id="data_idk_edit" name="id_karyawan" value="">
							<div class="form-group clearfix">
								<label>NIK</label>
								<input type="text" placeholder="Masukkan NIK" id="data_nik_edit" name="nik" value="" class="form-control" required="required" disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>Nama</label>
								<input type="text" placeholder="Masukkan Nama" id="data_nama_edit" name="nama" value="" class="form-control" required="required" disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>NO Izin/Cuti</label>
								<input type="text" placeholder="Masukkan NO Izin/Cuti" id="data_no_edit" name="no_cuti" value="" class="form-control" required="required">
							</div>
							<div class="form-group clearfix">
								<label>Tanggal Mulai - Selesai</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tanggal" class="form-control date-range-30" id="data_tgl_cuti_edit" value="" placeholder="Tanggal Cuti" readonly="readonly" required="required">
								</div>
							</div>
							<input type="hidden" name="tanggal_old" class="form-control pull-right date-range-30" id="data_tgl_cuti_old" value="" placeholder="Tanggal Cuti" readonly="readonly" required="required">
							<div class="form-group clearfix">
								<label>Jenis Izin/Cuti</label>
								<select class="form-control select2" name="jenis_cuti" id="data_jenis_edit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Menyetujui</label>
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Menyetujui 2</label>
								<select class="form-control select2" name="menyetujui2" id="data_menyetujui2_edit" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label>Mengetahui</label>
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit" required="required" style="width: 100%;"></select>
							</div>
							<!-- <div class="form-group clearfix">
								<label>SKD Dibayar</label><br>
								<a id="skd_no_edit" style="font-size: 16pt;"><i class="far fa-square"></i></a>
								<a id="skd_yes_edit" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
								<input type="hidden" name="skd" id="skd_edit" class="form-control" placeholder="SKD Dibayar" readonly>
							</div> -->
							<div class="form-group clearfix">
								<label>Alasan</label>
								<textarea class="form-control" name="alasan" id="data_alasan_edit_harian" placeholder="Alasan" required="required"></textarea>
							</div>
							<div class="form-group clearfix">
								<label>Keterangan</label>
								<textarea class="form-control" name="keterangan" id="data_keterangan_edit_harian" placeholder="Keterangan"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="bt_edit_harian" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="slip_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Izin Cuti</h4>
			</div>
			<input type="hidden" id="data_id_izin" name="data_id_izin">
			<div class="modal-body text-center">
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-primary" onclick="do_print_slip('word')"><i class="fas fa-file-word fa-fw"></i>WORD</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_print_slip('pdf')"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<div id="delete_harian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_harian">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete_harian" name="column">
					<input type="hidden" id="data_id_delete_harian" name="id">
					<input type="hidden" id="data_table_delete_harian" name="table">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete_harian" class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_harian()" class="btn btn-primary"><i class="fa fa-trash"></i>
					Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_izin_cuti_karyawan";
	var column="id_izin_cuti";
	$(document).ready(function(){
		refreshCode();
		resetselectAdd();
		refreshDataHarian('all');
		submitForm('form_add_cuti');
		submitForm('form_reset');
		submitForm('form_edit');
		$('#skd_no').click(function(){
			$('#skd_no').hide();
			$('#skd_yes').show();
			$('#skd_add').val('1');
		})
		$('#skd_yes').click(function(){
			$('#skd_yes').hide();
			$('#skd_no').show();
			$('#skd_add').val('0');
		})
		$('#data_jenis_cuti_add, #tanggal_izin_add, #id_karyawan_cuti').change(function(){
			var jc = $('#data_jenis_cuti_add').val();
			var idk = $('#id_karyawan_cuti').val();
			var tgl = $('#tanggal_izin_add').val();
			var datax = {jenis: jc,tanggal: tgl,id_kar: idk};
			var tgl_ini=getAjaxData("<?php echo base_url('presensi/cekTanggalIzin')?>",datax);
			if (tgl_ini['cek'] > 0) {
				$('#div_span_tgl_izin').html(tgl_ini['msg']).css('color','red');
				$('#btn_save').prop('disabled', true);
			}else{
				$('#div_span_tgl_izin').html(tgl_ini['msg']).css('color','green');
				$('#btn_save').prop('disabled', false); 
			};
			var sisacuti=getAjaxData("<?php echo base_url('presensi/cekSisaCuti')?>",datax);
			var tgl_izin=getAjaxData("<?php echo base_url('presensi/tanggalIzin')?>",datax);
			if (tgl_izin['jenis'] == 'C') {
				if (tgl_izin['hari'] > tgl_izin['maksimal']) {
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}else{
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','green');
					$('#btn_save').prop('disabled', false);
				}
			}else{
				if (tgl_izin['maksimal'] < tgl_izin['hari']) {
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}else{
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
				}
				$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
				$('#btn_save').prop('disabled', false); 
			};
			if (tgl_izin['jenis'] == 'C' && sisacuti['potong_cuti'] == '1') {
				if (sisacuti['sisa_cuti'] >= sisacuti['hari']) {
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
				}else{
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}
			}else if (tgl_izin['jenis'] == 'C' && sisacuti['potong_cuti'] != '1') {
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
			}
			var minCuti=getAjaxData("<?php echo base_url('presensi/minCuti')?>",datax);
			if (minCuti['msg'] == '' || minCuti['msg'] == null) {
				$('#div_span_notif_min_cuti').html('').css('color','green');
				$('#btn_save').prop('disabled', false);
			}else{
				$('#div_span_notif_min_cuti').html(minCuti['msg']).css('color','red');
				$('#btn_save').prop('disabled', true);
			}
		})
		$('#kar_off').click(function(){
			$('#kar_off').hide();
			$('#kar_on').show();
			$('input[name="kar_sisa"]').val('1');
		})
		$('#kar_on').click(function(){
			$('#kar_off').show();
			$('#kar_on').hide();
			$('input[name="kar_sisa"]').val('0');
		})
		$('#kartu_off').click(function(){
			$('#kartu_off').hide();
			$('#kartu_on').show();
			$('input[name="kartu_sisa"]').val('1');
		})
		$('#kartu_on').click(function(){
			$('#kartu_off').show();
			$('#kartu_on').hide();
			$('input[name="kartu_sisa"]').val('0');
		})
		getSelect2("<?php echo base_url('presensi/izin_cuti/izincuti')?>",'data_jenis_cuti_ser');
	});
	function karKlik(){		
		var name = $('input[name="kar_sisa"]').val();
		if(name == 1) {
			var data={val:name};
			var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/kar_reset_val_null')?>",data);
			$('#data_karyawan_reset').val(callback).trigger('change');
		}else {
			var data={val:name};
			var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/kar_reset')?>",data);
			var text = "";
			var i;
			var selectedValues = new Array();
			for (i = 0; i < callback.length; i++) {
				selectedValues[i] = callback[i];
			} 
			$('#data_karyawan_reset').val(selectedValues).trigger('change');
		}
	}
	function kartuKlik(){		
		var name = $('input[name="kartu_sisa"]').val();
		if(name == 1) {
			var data={val:name};
			var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/kar_reset_val_null')?>",data);
			$('#data_karyawan_kartu').val(callback).trigger('change');
		}else {
			var data={val:name};
			var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/kar_reset')?>",data);
			var text = "";
			var i;
			var selectedValues = new Array();
			for (i = 0; i < callback.length; i++) {
				selectedValues[i] = callback[i];
			} 
			$('#data_karyawan_kartu').val(selectedValues).trigger('change');
		}
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				if(form=='form_add_cuti'){
					do_add()
				}else if(form=='form_edit'){
					do_edit()
				}else{
					do_reset()
				}
			}
		})
	}
	// ================================================= IZIN HARIAN ===================================================//
	function refreshDataHarian(kode){
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_harian');
		select_data('unit_harian',url_select,'master_loker','kode_loker','nama','placeholder');
		getSelect2("<?php echo base_url('presensi/izin_cuti/izincuti')?>",'data_jenis_cuti_add');
		getSelect2("<?php echo base_url('employee/emp_part_jabatan_grade/grade')?>",'data_grade_baru_add');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add,#data_menyetujui2_add,#data_karyawan_reset,#data_karyawan_kartu');
		// getSelect2("<?php //echo base_url('presensi/data_presensi/employee')?>",'data_karyawan_reset,#data_karyawan_kartu');
		$('#btn_reset').click(function() {
			$('#reset').modal('show');
		});
		tableDataHarian(kode);
	}
	function tableDataHarian(kode){
		if(kode=='all'){
			var datax = {kode:kode,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var data = $('#form_filter_harian').serialize();
			var datax = {form:data,kode:kode,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data_harian').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/izin_cuti_harian/view_all/')?>",
				type: 'POST',
				data:datax
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
			{   targets: 1,
				width: '10%',
				render: function ( data, type, full, meta ) {
					// return '<a href="<?php base_url()?>view_izin_cuti/'+full[10]+'">' +data+'</a>';
					return data;
				}
			},
			{   targets: 7, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function view_modal_harian(id) {
		// alert(id);
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);  
		$('#view_harian').modal('show');
		$('.header_data').html(callback['nomor']);
		$('#data_nik_harian').html(callback['nik']);
		$('#data_nama_harian').html(callback['nama']);
		$('#data_no_view').html(callback['nomor']);
		$('#data_jenis_view').html(callback['jenis_cuti']);
		$('#data_mulai_view').html(callback['tanggal_mulai']);
		$('#data_selesai_view').html(callback['tanggal_selesai']);
		$('#data_skd_view').html(callback['skd']);
		$('#data_alasan_view').html(callback['alasan']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
		$('#data_menyetujui2_view').html(callback['menyetujui2']);
		$('#data_keterangan_view').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		var val_ac = callback['e_validasi'];
		if (val_ac == 2 || val_ac == 0){
			$('#btn_edit_view').show();
		}else{
			$('#btn_edit_view').hide();
		}
		$('#data_status_harian').html(statusval);
		$('#data_create_date_harian').html(callback['create_date']+' WIB');
		$('#data_update_date_harian').html(callback['update_date']+' WIB');
		$('input[name="data_id_view_harian"]').val(callback['id']);
		$('#data_create_by_harian').html(callback['nama_buat']);
		$('#data_update_by_harian').html(callback['nama_update']);
	}
	function edit_modal_harian() {
		$('#skd_no_edit').click(function(){
			$('#skd_no_edit').hide();
			$('#skd_yes_edit').show();
			$('#skd_edit').val('1');
		})
		$('#skd_yes_edit').click(function(){
			$('#skd_yes_edit').hide();
			$('#skd_no_edit').show();
			$('#skd_edit').val('0');
		})
		getSelect2("<?php echo base_url('presensi/izin_cuti/izincuti')?>",'data_jenis_edit');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit,#data_menyetujui2_edit');
		var id = $('input[name="data_id_view_harian"]').val();
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data); 
		$('#view_harian').modal('toggle');
		setTimeout(function () {
			$('#edit_harian').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_nik_edit').val(callback['nik']);
		$('#data_id_edit').val(callback['id']);
		$('#data_idk_edit').val(callback['id_karyawan']);
		$('#data_nama_edit').val(callback['nama']);
		$('#data_no_edit').val(callback['nomor']);
		$('#data_jenis_edit').val(callback['e_jenis_cuti']).trigger('change');
		$('#data_mengetahui_edit').val(callback['emengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['emenyetujui']).trigger('change');
		$('#data_menyetujui2_edit').val(callback['emenyetujui2']).trigger('change');
		$("#data_tgl_cuti_edit").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
		$("#data_tgl_cuti_edit").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
		$("#data_tgl_cuti_old").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
		$("#data_tgl_cuti_old").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
		$('#data_alasan_edit_harian').val(callback['alasan']);
		$('#data_keterangan_edit_harian').val(callback['keterangan']);
		var skd=callback['e_skd'];
		if (skd==1){
			$('#skd_no_edit').hide();
			$('#skd_yes_edit').show();
		}else{
			$('#skd_yes_edit').hide();
			$('#skd_no_edit').show();
		}
	}
	function modal_need(id) {
		var data={id_izin_cuti:id};
		$('#m_need').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);
		$('#m_need #data_id_need').val(callback['id']);
		$('#m_need #data_idk_need').val(callback['id_karyawan']);
		$('#m_need #data_jenis_need').val(callback['nama_jenis_ic']);
		$('#m_need .header_data').html(callback['nama']);
	}
	function modal_yes(id) {
		var data={id_izin_cuti:id};
		$('#m_yes').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);
		$('#m_yes #data_id_yes').val(callback['id']);
		$('#m_yes #data_idk_yes').val(callback['id_karyawan']);
		$('#m_yes #data_jenis_yes').val(callback['nama_jenis_ic']);
		$('#m_yes .header_data').html(callback['nama']);
	}
	function modal_no(id) {
		var data={id_izin_cuti:id};
		$('#m_no').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);
		$('#m_no #data_id_no').val(callback['id']);
		$('#m_no #data_idk_no').val(callback['id_karyawan']);
		$('#m_no #data_jenis_no').val(callback['nama_jenis_ic']);
		$('#m_no .header_data').html(callback['nama']);
	}
  	function do_validasi(data,val,form){
		if(data==2){
			var id = $('#data_id_need').val();
			var idk = $('#data_idk_need').val();
			var jenis = $('#data_jenis_need').val();
		}else if(data==1){
			var id = $('#data_id_yes').val();
			var idk = $('#data_idk_yes').val();
			var jenis = $('#data_jenis_yes').val();
		}else if(data==0){
			var id = $('#data_id_no').val();
			var idk = $('#data_idk_no').val();
			var jenis = $('#data_jenis_no').val();
		}
		var datax={id_izin_cuti:id,id_k:idk,validasi_db:data,validasi:val,jenis_db:jenis};
		submitAjax("<?php echo base_url('presensi/validasi_izin')?>",form,datax,null,null,'status');
		$('#table_data_harian').DataTable().ajax.reload(function (){
			Pace.restart();
		});
  	}
	function do_print(id) { 
		$('#slip_mode').modal('show');
		$('input[name="data_id_izin"]').val(id);
	}
	function do_print_slip(kode) {
		var id = $('input[name="data_id_izin"]').val();
		if(kode == 'word'){
			window.location.href = "<?php echo base_url('cetak_word/cetak_izin/')?>"+id;
		} else {
			$.redirect("<?php echo base_url('pages/cetak_izin'); ?>", { id_izin : id, }, "POST", "_blank");
		}
	} 
	function delete_modal_harian(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/view_one')?>",data);
		$('#data_column_delete_harian').val('id_izin_cuti');
		$('#data_id_delete_harian').val(id);
		$('#data_table_delete_harian').val('data_izin_cuti_karyawan');
		$('#data_name_delete_harian').html(callback['nama']);
		$('#delete_harian').modal('show');
	}
	function do_delete_harian(){
		submitAjax("<?php echo base_url('global_control/delete')?>",'delete_harian','form_delete_harian',null,null);
		$('#table_data_harian').DataTable().ajax.reload(function (){
		Pace.restart();
		});
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/edit_izin_cuti')?>",'edit_harian','form_edit',null,null);
			$('#table_data_harian').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	// ================================================= IZIN SEMUA ===================================================//
	function tabIzinAll(kode){	
		tableData('all');	
		refreshData();
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
	}
	function tableData(kode) {
		$('input[name="param"').val(kode);
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_export').val();
			var unit = $('#unit_export').val();
			var bulan = $('#bulan_export').val();
			var tahun = $('#tahun_export').val();
			var datax = {param:'search',bagian:bagian,unit:unit,bulan:bulan,tahun:tahun,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/izin_cuti/view_all/')?>",
				type: 'POST',
				data:datax
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
			{   targets: 1,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<a href="<?php base_url()?>view_izin_cuti/'+full[10]+'">' +data+'</a>';
				}
			},
			{   targets: 9, 
				width: '7%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function pilih_karyawan_cuti() {
		$('#modal_pilih_karyawan_cuti').modal('toggle');
		$('#modal_pilih_karyawan_cuti .header_data').html('Pilih Karyawan');
		$('#table_pilih_cuti').DataTable( {
			ajax: "<?php echo base_url('presensi/pilih_k_cuti')?>",
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 4,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('presensi/izin_cuti/kode');?>",'no_cuti_add');
	}
	function refreshData() {
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
		select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export',['BAG001','BAG002']);
	}
	function resetselectAdd() {
		$('#data_jenis_cuti_add').val('').trigger('change');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
	}
	function resetselectAddModalReset() {
		$('#data_karyawan_reset').val('').trigger('change');
		$('#jumlah_cuti').val('');
	}
	function view_modal(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_bagian_view').html(callback['bagian']);
		$('#data_foto_view').attr('src',callback['foto']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
		$('#data_tabel_view').html(callback['tabel_izin']);
	}
	function delete_modal(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/view_one')?>",data);
		var datax={table:table,column:'id_karyawan',id:callback['id_karyawan'],nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_add(){
		if($("#form_add_cuti")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/add_izin_cuti')?>",null,'form_add_cuti',null,null);
			$('#table_data_harian').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add_cuti')[0].reset();
			refreshCode();
			resetselectAdd();
		}else{
			notValidParamx();
		} 
	}
	function do_reset(){
		if($("#form_reset")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/reset_izin_cuti')?>",'reset','form_reset',null,null);
			$('#table_data_harian').DataTable().ajax.reload();resetselectAddModalReset();
		}else{
			notValidParamx();
		} 
	}
	function do_kartu(){
		var karyawan = $('#data_karyawan_kartu').val();
		var tahun = $('#tahun_monitor').val();
		if(karyawan != "" && tahun != "") {
			$.redirect("<?php echo base_url('pages/monitor_presensi'); ?>", 
			{
				data_filter: $('#form_kartu').serialize()
			},
			"POST", "_blank");
		}else{
			submitAjax("<?php echo base_url('cpayroll/cek_data_payroll_notif/kartu')?>",null,null,null,null,'status');
		} 
	}
	$(document).on('click','.pilih_cuti',function(){
		document.getElementById("id_karyawan_cuti").value = $(this).attr('data-id_karyawan_cuti');
		document.getElementById("nik_cuti").value = $(this).attr('data-nik_cuti');
		document.getElementById("nama_cuti").value = $(this).attr('data-nama_cuti');
		document.getElementById("jabatan_cuti").value = $(this).attr('data-jabatan_cuti');
		document.getElementById("nama_jabatan_cuti").value = $(this).attr('data-nama_jabatan_cuti');
		document.getElementById("kode_lokasi_cuti").value = $(this).attr('data-kode_lokasi_cuti');
		document.getElementById("nama_lokasi_cuti").value = $(this).attr('data-nama_lokasi_cuti');
		$('#modal_pilih_karyawan_cuti').modal('hide');
	});
	function rekap() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/export_izin_cuti')?>?"+data;
	}
	function rekap_harian() {
		var data=$('#form_filter_harian').serialize();
		window.location.href = "<?php echo base_url('rekap/export_izin_cuti_harian')?>?"+data;
	}
	function cetak_kartu() {
		$('#cetak_kartu').modal('show');
	}
	function view_sisa_cuti() {
		$('#view_sisa_cuti').modal('show');
		var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/view_sisa_cuti')?>");
		$('#lihat_sisa_cuti').html(callback['tabel_sisa_cuti']);
	}
</script> 