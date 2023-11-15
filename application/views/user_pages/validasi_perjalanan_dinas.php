<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fas fa-car"></i> Data
			<small>Perjalanan Dinas</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fas fa-car"></i> Perjalanan Dinas</li>
		</ol>
	</section>
	<section class="content">
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
								<div class="col-md-4">
									<div class="form-group">
										<label>Pilih Bagian</label>
										<select class="form-control select2" id="bagian_export" name="bagian_export" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Pilih Lokasi Kerja</label>
										<select class="form-control select2" id="unit_export" name="unit_export" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Tanggal</label>
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" name="tanggal_filter" id="tanggal_filter" class="form-control pull-right date-range-notime" placeholder="Tanggal" readonly="readonly">
									</div>									
								</div>
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
						<h3 class="box-title"><i class="fas fa-car"></i> Data Perjalanan Dinas</h3>
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
									// if (in_array($access['l_ac']['add'], $access['access'])) {
									// echo '<a href="#tambah" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success "><i class="fa fa-plus"></i> Tambah Perjalanan Dinas</a> ';}
									if (in_array($access['l_ac']['rkp'], $access['access'])) {
										echo '<button type="button" onclick="rekap()" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';}
									if (in_array($access['l_ac']['add'], $access['access'])) { ?>
									<div id="tambah" class="collapse">
										<br>
										<div class="box box-success">
											<div class="box-header with-border">
												<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Perjalanan Dinas</h3>
											</div>
											<form id="form_add" class="form-horizontal">
												<div class="box-body">
													<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-sm-3 control-label">Nomor Perjalanan Dinas</label>
															<div class="col-sm-8">
																<input type="text" name="no_sk" id="no_sk_add" class="form-control" placeholder="Nomor SK" required="required">
															</div>
															<div class="col-sm-1">
																<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
															</div>
													</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Pilih Karyawan</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="id_karyawan[]" id="karyawan_add" required="required" style="width: 100%;" multiple="multiple"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Tanggal Berangkat - Tanggal Sampai</label>
															<div class="col-sm-9">
																<div class="has-feedback">
																	<span class="fa fa-calendar form-control-feedback"></span>
																	<input type="text" name="tanggal" class="form-control pull-right dateRangeNoSecond" placeholder="Tanggal Berangkat" readonly="readonly">
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Tanggal Pulang</label>
															<div class="col-sm-9">
																<div class="has-feedback">
																	<span class="fa fa-calendar form-control-feedback"></span>
																	<input type="text" name="tanggal_pulang" class="form-control pull-right datetimepicker" placeholder="Tanggal Pulang" readonly="readonly">
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Pilih Plant Asal</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="plant_asal" id="data_plant_asal_add" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Tujuan</label>
															<div class="col-sm-9">
																<?php
																$tujuan[null] = 'Pilih Data';
																$sel1 = [null];
																$exsel1 = array('class'=>'form-control select2','placeholder'=>'Tujuan','id'=>'tujuan','required'=>'required','style'=>'width:100%','onchange'=>'tujuanPD(this.value)');
																echo form_dropdown('tujuan',$tujuan,$sel1,$exsel1);
																?>
															</div>
														</div>
														<div class="form-group" id="tujuan_plant" style="display:none;">
															<label class="col-sm-3 control-label">Pilih Plant Tujuan</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="plant_tujuan" id="data_plant_tujuan_add" style="width: 100%;"></select>
															</div>
														</div>
														<div id="tujuan_non_plant" style="display:none;">
															<div class="form-group">
																<label class="col-sm-3 control-label">Lokasi Tujuan</label>
																<div class="col-sm-9">
																	<input type="text" name="lokasi_tujuan" id="data_lokasi_tujuan_add" class="form-control pull-right" placeholder="Lokasi Tujuan">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Estimasi Jarak</label>
																<div class="col-sm-9">
																	<input type="number" nim="0" name="jarak" id="data_jarak_add" class="form-control pull-right" placeholder="Estimasi Jarak">
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Kendaraan</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="kendaraan" id="data_kendaraan_add" onchange="kendaraanPD(this.value)" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Jumlah Kendaraan</label>
															<div class="col-sm-9">
																<input type="number" nim="0" name="jum_kendaraan" id="data_jum_ken_add" class="form-control pull-right" placeholder="Jumlah Kendaraan">
															</div>
														</div>
														<div class="form-group" id="nama_kendaraan_umum" style="display:none;">
															<label class="col-sm-3 control-label">Nama Kendaraan Umum</label>
															<div class="col-sm-9">
																<?php
																$kendaraan_umum[null] = 'Pilih Data';
																$sel = [null];
																$exsel = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'data_kendaraan_umum_add','required'=>'required','style'=>'width:100%');
																echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel,$exsel);
																?>
															</div>
														</div>
														<div id="nominal_bbm" style="display:none;">
															<!-- <div class="form-group">
																<label class="col-sm-3 control-label">Nominal BBM</label>
																<div class="col-sm-9">
																	<input type="text" name="nominal_bbm" id="data_bbm_add" class="form-control pull-right input-money" placeholder="Nominal BBM">
																</div>
															</div> -->
															<!-- <div class="form-group">
																<label class="col-sm-3 control-label">Diberikan Kepada</label>
																<div class="col-sm-9">
																	<select class="form-control select2" name="diberi_kepada" id="diberi_kepada_karyawan" style="width: 100%;"></select>
																</div>
															</div> -->
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-sm-3 control-label" style="vertical-align: middle;">Menginap</label>
															<div class="col-sm-9">
																<span id="menginap_off" style="font-size: 20px;" onclick="menginapKlik();"><i class="far fa-square" aria-hidden="true"></i></span>
																<span id="menginap_on" style="display: none; font-size: 20px;" onclick="menginapKlik();"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
																<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist Jika Menginap)</b></span>
																<input type="hidden" name="menginap">
															</div>
														</div>
														<div id="penginapan_add" style="display: none;">
															<div class="form-group">
																<label class="col-sm-3 control-label">Penginapan</label>
																<div class="col-sm-9">
																	<?php
																	$penginapan[null] = 'Pilih Data';
																	$sel2 = [null];
																	$exsel2 = array('class'=>'form-control select2','placeholder'=>'penginapan','id'=>'data_penginapan_add','style'=>'width:100%');
																	echo form_dropdown('penginapan',$penginapan,$sel2,$exsel2);
																	?>
																</div>
															</div>
															<div class="form-group" id="nominal_penginapan" style="display:none;">
																<label class="col-sm-3 control-label">Biaya Penginapan</label>
																<div class="col-sm-9">
																	<input type="text" name="nominal_penginapan" id="data_penginapan_add" class="form-control pull-right input-money" placeholder="Nominal Penginapan">
																</div>
															</div>
														</div>
														<!-- <div class="form-group">
															<label class="col-sm-3 control-label">Tunjangan</label>
															<div class="col-sm-9">
																<select class="form-control select2" multiple="multiple" name="tunjangan[]" id="data_tunjangan_add" style="width: 100%;"></select>
															</div>
														</div> -->
														<div class="form-group">
															<label class="col-sm-3 control-label">Tugas</label>
															<div class="col-sm-9">
																<textarea name="tugas" class="form-control" required="required" placeholder="Keterangan Tugas Perjalanan Dinas"></textarea>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Mengetahui</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="mengetahui" id="data_mengetahui_add" required="required" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Menyetujui</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="menyetujui" id="data_menyetujui_add" required="required" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Dibuat Oleh</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="dibuat" id="data_dibuat_add" required="required" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Keterangan </label>
															<div class="col-sm-9">
																<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<button type="button" class="btn btn-warning" id="btn_tunjangan" href="javascript:void(0)" onclick="view_tunjangan()"><i class="fa fa-eye"></i> Lihat Detail Tunjangan</button>
													</div>
												</div>
												<div class="box-footer">
													<div class="pull-right">
														<button type="submit" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
								Data yang ditampilkan adalah per transaksi perjalanan dinas.<br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail Peringatan maupun melakukan update pada data Perjalanan Dinas</div>
								<table id="table_data_trans" class="table table-bordered table-striped" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nomor</th>
											<th>Tanggal Perjalanan Dinas</th>
											<th>Dari</th>
											<th>Tujuan</th>
											<th>Jumlah</th>
											<th>Tanggal</th>
											<th>Status Validasi</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<!-- <div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active"><a onclick="per_trans()" href="#per_trans" data-toggle="tab"><i class="fa fa-edit"></i> Per Transaksi</a></li>
										<li><a onclick="tableData('all')" href="#per_kar" data-toggle="tab"><i class="fas fa-car"></i> Per Karyawan</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane" id="per_kar">
											<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail Peringatan maupun melakukan update pada data Perjalanan Dinas</div>
											<table id="table_data" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>NIK</th>
														<th>Nama</th>
														<th>Nomor</th>
														<th>Tanggal Perjalanan Dinas</th>
														<th>Tujuan</th>
														<th>Jumlah Data</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										<div class="tab-pane active" id="per_trans">
											<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
											Data yang ditampilkan adalah per transaksi perjalanan dinas.<br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail Peringatan maupun melakukan update pada data Perjalanan Dinas</div>
											<table id="table_data_trans" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>Nomor</th>
														<th>Tanggal Perjalanan Dinas</th>
														<th>Dari</th>
														<th>Tujuan</th>
														<th>Jumlah</th>
														<th>Tanggal</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div> -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
</div>
<div id="view_modal_tunjangan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_compare" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Tunjangan Perjalanan Dinas</h2>
			</div>
			<div class="modal-body">
				<div class="row" id="total_tunjangan" style="display:none;">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kendaraan</label>
							<div class="col-md-6" id="data_kendaraan_all"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal Per Kendaraan</label>
							<div class="col-md-6" id="nominal_bbm_per_ken"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Kendaraan</label>
							<div class="col-md-6" id="jumlah_kendaraan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal BBM</label>
							<div class="col-md-6" id="nominal_bbm_all"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Penginapan</label>
							<div class="col-md-6" id="data_tunjangan_all"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal Penginapan</label>
							<div class="col-md-6" id="nominal_tunjangan_all"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_tunjangan"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
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
							<label class="col-md-6 control-label">Lokasi</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
					</div>
					<div class="col-md-6">
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
<div id="view_u" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_kode_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Perjalanan Dinas</label>
							<div class="col-md-6" id="data_nosk_view"></div>
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
							<label class="col-md-6 control-label">Dibuat</label>
							<div class="col-md-6" id="data_dibuat_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_trans"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_trans"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_trans"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_trans">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_trans">
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Berangkat</label>
							<div class="col-md-6" id="data_berangkat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Sampai</label>
							<div class="col-md-6" id="data_sampai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Pulang</label>
							<div class="col-md-6" id="data_pulang_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Plant Asal</label>
							<div class="col-md-6" id="data_asal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tujuan</label>
							<div class="col-md-6" id="data_tujuan_view"></div>
						</div>
						<div class="form-group col-md-12" id="jarak_view">
							<label class="col-md-6 control-label">Jarak</label>
							<div class="col-md-6" id="data_jarak_view"></div>
						</div>	
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kendaraan</label>
							<div class="col-md-6" id="data_kedaraan_view"></div>
						</div>				
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Kendaraan</label>
							<div class="col-md-6" id="data_jumlah_kendaraan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal Per Kendaraan</label>
							<div class="col-md-6" id="data_nominal_per_ken_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal BBM</label>
							<div class="col-md-6" id="data_nominal_bbm_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal Penginapan</label>
							<div class="col-md-6" id="data_nominal_penginapan_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menginap</label>
							<div class="col-md-6" id="data_menginap_view"></div>
						</div> -->
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Penginapan</label>
							<div class="col-md-6" id="data_nama_penginapan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tugas</label>
							<div class="col-md-6" id="data_tugas_view"></div>
						</div>	
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>						
					</div>
				</div>
				<div id="tabel_tunjangan"></div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal_u()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<?php if (in_array($access['l_ac']['edt'], $access['access'])) { ?>
	<div id="edit_u" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
				</div>
				<form id="form_edit">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<input type="hidden" id="data_id_edit" name="id" value="">
									<!-- <input type="hidden" id="data_idk_edit" name="id_karyawan[]" value=""> -->
									<div class="form-group clearfix">
										<label>NO Surat</label>
										<input type="text" placeholder="Masukkan NO Surat" id="data_nosk_edit" name="no_sk" value="" class="form-control" required="required">
									</div>
									<div class="form-group clearfix">
										<label>Tanggal Berangkat - Tanggal Kembali</label>
										<input type="text" name="tanggal" id="data_tanggal_edit" class="form-control pull-right dateRangeNoSecond" placeholder="Tanggal" readonly="readonly" required="required">
									</div>
									<div class="form-group clearfix">
										<label>Tanggal Pulang</label>
										<div class="has-feedback">
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" name="tanggal_pulang" id="data_tanggal_pulang_edit" class="form-control pull-right datetimepicker" placeholder="Tanggal Pulang" readonly="readonly">
										</div>
									</div>
									<div class="form-group clearfix">
										<label>Karyawan</label>
										<select class="form-control select2" name="id_karyawan[]" id="karyawan_edit_trans" required="required" style="width: 100%;" multiple="multiple"></select>
									</div>
									<!-- <select class="form-control select2" type="hidden" name="karyawan_old[]" id="karyawan_old" style="width: 100%;" multiple="multiple"></select> -->
									<input type="hidden" name="karyawan_old" id="karyawan_old">
									<input type="hidden" name="no_sk_old" id="no_sk_old">
									<div class="form-group clearfix">
										<label>Pilih Plant Asal</label>
										<select class="form-control select2" name="plant_asal" id="data_plant_asal_edit" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Tujuan</label>
										<?php
										$tujuan[null] = 'Pilih Data';
										$sel1 = [null];
										$exsel1 = array('class'=>'form-control select2','placeholder'=>'Tujuan','id'=>'tujuan_edit','required'=>'required','style'=>'width:100%','onchange'=>'tujuanPDEdit(this.value)');
										echo form_dropdown('tujuan',$tujuan,$sel1,$exsel1);
										?>
									</div>
									<div class="form-group clearfix" id="tujuan_plant_edit" style="display:none;">
										<label>Pilih Plant Tujuan</label>
										<select class="form-control select2" name="plant_tujuan" id="data_plant_tujuan_edit" style="width: 100%;"></select>
									</div>
									<div id="tujuan_non_plant_edit" style="display:none;">
										<div class="form-group clearfix">
											<label>Lokasi Tujuan</label>
											<input type="text" name="lokasi_tujuan" id="data_lokasi_tujuan_edit" class="form-control pull-right" placeholder="Lokasi Tujuan">
										</div>
										<div class="form-group clearfix">
											<label>Estimasi Jarak</label>
											<input type="number" nim="0" name="jarak" id="data_jarak_edit" class="form-control pull-right" placeholder="Estimasi Jarak">
										</div>
									</div>
									<div class="form-group clearfix">
										<label>Kendaraan</label>
										<select class="form-control select2" name="kendaraan" id="data_kendaraan_edit" onchange="kendaraanPDEdit(this.value)" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Jumlah Kendaraan</label>
											<input type="number" nim="0" name="jum_kendaraan" id="data_jum_ken_edit" class="form-control pull-right" placeholder="Jumlah Kendaraan">
									</div>
									<div class="form-group" id="nama_kendaraan_umum_edit" style="display:none;">
										<label>Nama Kendaraan Umum</label>
										<?php
										$kendaraan_umum[null] = 'Pilih Data';
										$sel2 = [null];
										$exsel2 = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'data_kendaraan_umum_edit','required'=>'required','style'=>'width:100%');
										echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel2,$exsel2);
										?>
									</div>
									<div id="nominal_bbm_edit" style="display:none;">
										<!-- <div class="form-group">
											<label>Nominal BBM</label>
												<input type="text" name="nominal_bbm" id="data_nominal_bbm_edit" class="form-control pull-right input-money" placeholder="Nominal BBM">
										</div> -->
										<!-- <div class="form-group">
											<label>Diberikan Kepada</label>
											<div class="col-sm-9">
												<select class="form-control select2" name="diberi_kepada" id="diberi_kepada_karyawan" style="width: 100%;"></select>
											</div>
										</div> -->
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group clearfix">
										<label style="vertical-align: middle;">Menginap</label><br>
										<span id="menginap_off_edit" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
										<span id="menginap_on_edit" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
										<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist Jika Menginap)</b></span>
										<input type="hidden" name="menginap" id="menginap_edit">
									</div>
									<div class="form-group" id="penginapan_edit_div" style="display: none;">
										<label>Penginapan</label>
										<?php
										$penginapan[null] = 'Pilih Data';
										$sel2 = [null];
										$exsel2 = array('class'=>'form-control select2','placeholder'=>'penginapan','id'=>'data_penginapan_edit','style'=>'width:100%');
										echo form_dropdown('penginapan',$penginapan,$sel2,$exsel2);
										?>
									</div>
									<div id="nominal_penginapan_edit" style="display:none;">
										<div class="form-group">
											<label>Nominal Penginapan</label>
												<input type="text" name="nominal_penginapan" id="data_nominal_penginapan_edit" class="form-control pull-right input-money" placeholder="Nominal Penginapan">
										</div>
										<!-- <div class="form-group">
											<label>Diberikan Kepada</label>
											<div class="col-sm-9">
												<select class="form-control select2" name="diberi_kepada" id="diberi_kepada_karyawan" style="width: 100%;"></select>
											</div>
										</div> -->
									</div>
									<!-- <div class="form-group clearfix">
										<label>Tunjangan</label>
										<select class="form-control select2" multiple="multiple" name="tunjangan[]" id="data_tunjangan_edit" style="width: 100%;"></select>
									</div> -->
									<div class="form-group clearfix">
										<label>Mengetahui</label>
										<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Menyetujui</label>
										<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Dibuat</label>
										<select class="form-control select2" name="dibuat" id="data_dibuat_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Tugas</label>
										<textarea class="form-control" name="tugas" id="data_tugas_edit" required="required" placeholder="Pelanggaran"></textarea>
									</div>
									<div class="form-group clearfix">
										<label>Keterangan (Sanksi)</label>
										<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan" required="required"></textarea>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<button type="button" class="btn btn-warning" id="btn_tunjangan_edit" onclick="view_tunjangan_edit()"><i class="fa fa-eye"></i> Lihat Detail Tunjangan</button>
								</div>
							</div>
							<br><hr>
							<div id="total_tunjangan_edit" style="display:none;">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Kendaraan</label>
											<div class="col-md-6" id="data_kendaraan_all_edit"></div>
										</div><br>
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Jumlah Kendaraan</label>
											<div class="col-md-6" id="nominal_jum_ken_edit"></div>
										</div><br>
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Nominal BBM Per Kendaraan</label>
											<div class="col-md-6" id="nominal_per_ken_edit"></div>
										</div><br>
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Nominal BBM</label>
											<div class="col-md-6" id="nominal_bbm_all_edit"></div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Penginapan</label>
											<div class="col-md-6" id="data_tunjangan_all_edit"></div>
										</div><br>
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Nominal Penginapan</label>
											<div class="col-md-6" id="nominal_tunjangan_all_edit"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="data_detail" id="tabel_tunjangan_edit"></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php } ?>
<div id="m_need" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Perjalanan Dinas</h4>
			</div>
			<form id="form_need">
				<div class="modal-body text-center">
					<input type="hidden" id="data_idk_need" name="id_kar">
					<input type="hidden" id="data_id_need" name="id">
					<input type="hidden" id="data_jenis_need" name="jenis">
					<p>Mohon Validasi Perjalanan Dinas Karyawan dengan Nomor <b id="data_name_need" class="header_data"></b> berikut !!</p>
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
				<h4 class="modal-title text-center">Validasi Perjalanan Dinas</h4>
			</div>
			<form id="form_yes">
				<div class="modal-body text-center">
					<input type="hidden" id="data_idk_yes" name="id_kar">
					<input type="hidden" id="data_id_yes" name="id">
					<input type="hidden" id="data_jenis_yes" name="jenis">
					<p>Apakah Anda yakin akan mengubah status Perjalanan Dinas dari <b class="text-green">DiIzinkan</b> menjadi <b class="text-red">Tidak Diizinkan</b></b> dengan Nomor <b id="data_name_yes" class="header_data"></b> ??</p>
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
				<h4 class="modal-title text-center">Validasi Perjalanan Dinas</h4>
			</div>
			<form id="form_no">
				<div class="modal-body text-center">
					<input type="hidden" id="data_idk_no" name="id_kar">
					<input type="hidden" id="data_id_no" name="id">
					<input type="hidden" id="data_jenis_no" name="jenis">
					<p>Apakah Anda yakin akan mengubah status Perjalanan Dinas dari <b class="text-red">Tidak Diizinkan</b> menjadi <b class="text-green">DiIzinkan</b></b> dengan Nomor <b id="data_name_no" class="header_data"></b> ??</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(0,1,'m_no')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_perjalanan_dinas";
	var column="id_pd";
	$(document).ready(function(){
		per_trans();
		tableData('all');
		refreshCode();
		refreshData();
		submitForm('form_add');
		submitForm('form_edit');
		$('#menginap_off').click(function(){
			$('#menginap_off').hide();
			$('#menginap_on').show();
			$('input[name="menginap"]').val('1');
		})
		$('#menginap_on').click(function(){
			$('#menginap_off').show();
			$('#menginap_on').hide();
			$('input[name="menginap"]').val('0');
		})
		select_data('data_plant_asal_add',url_select,'master_loker','kode_loker','nama');
		select_data('data_plant_tujuan_add',url_select,'master_loker','kode_loker','nama');
		select_data('data_kendaraan_add',url_select,'master_pd_kendaraan','kode','nama');
		getSelect2("<?php echo base_url('kemp/val_perjalanan_dinas/employee')?>",'data_mengetahui_add,#data_menyetujui_add,#data_dibuat_add,#karyawan_add');
		getSelect2("<?php echo base_url('kemp/val_perjalanan_dinas/pilihtunjangan')?>",'data_tunjangan_add');
    	$('#karyawan_add').change(function(){
         	var kar = $('#karyawan_add').val();
			var data={kary:kar};
			var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/cek_jumlah_karyawan')?>",data);
			var cb=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/diberikan_karyawan')?>",data);
			if (callback['val'] == 1) {
				$('#nominal_bbm').hide();
				$('#nominal_penginapan').hide();
			}else{
				$('#nominal_bbm').show();
				$('#nominal_penginapan').show();
				$('#diberi_kepada_karyawan').val(cb).trigger('change');
			}
      	})
    	$('#karyawan_edit_trans').change(function(){
         	var kr = $('#karyawan_edit_trans').val();
			var data={kary:kr};
			var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/cek_jumlah_karyawan')?>",data);
			if (callback['val'] == 1) {
				$('#nominal_bbm_edit').hide();
				$('#nominal_penginapan_edit').hide();
			}else{
				$('#nominal_bbm_edit').show();
				$('#nominal_penginapan_edit').show();
			}
      	})
	});
	function view_tunjangan() {
		var data=$('#form_add').serialize();
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_tunjangan')?>",{search:data});  
		$('#view_modal_tunjangan').modal('show');
		$('#data_tabel_tunjangan').html(callback['tabel']);
		$('#jumlah_kendaraan').html(callback['jum_ken']+' Kendaraan');
		$('#nominal_bbm_per_ken').html(callback['nominal_bbm_per_ken']);
		$('#nominal_bbm_all').html(callback['nominal_bbm_all']);
		$('#data_kendaraan_all').html(callback['nama_kendaraan_all']);
		$('#nominal_tunjangan_all').html(callback['nominal_tunjangan_all']);
		$('#data_tunjangan_all').html(callback['nama_tunjangan_all']);
		var jumlah = callback['jumlah'];
		if (jumlah == 1){
			$('#total_tunjangan').hide();
		}else{
			$('#total_tunjangan').show();
		}
	}
	function menginapKlik(){		
		var name = $('input[name="menginap"]').val();
		if(name == 0) {
			$('#penginapan_add').show();
			$('#data_penginapan_add').attr('required','required');
		}else {
			$('#penginapan_add').hide();
			$('#data_penginapan_add').removeAttr('required');
			$('#data_penginapan_add').val('').trigger('change');
		}
	}
	function refreshData() {
		getSelect2("<?php echo base_url('kemp/val_perjalanan_dinas//nama_bagian')?>",'bagian_export');
		select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export',['BAG001','BAG002']);
	}
	function resetselectAdd() {
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/refreshkaryawan')?>",'karyawan_add');
		$('#tujuan').val('').trigger('change');
		$('#data_plant_asal_add').val('').trigger('change');
		$('#data_plant_tujuan_add').val('').trigger('change');
		$('#data_lokasi_tujuan_add').val('').trigger('change');
		$('#data_kendaraan_add').val('').trigger('change');
		$('#data_kendaraan_umum_add').val('').trigger('change');
		$('#data_penginapan_add').val('').trigger('change');
		getSelect2("<?php echo base_url('kemp/val_perjalanan_dinas/refreshtunjangan')?>",'data_tunjangan_add');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
		$('#data_dibuat_add').val('').trigger('change');
	}
	function tableData(kode) {
		$('input[name="param"').val(kode);
		$('#table_data').DataTable().destroy();
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_export').val();
			var unit = $('#unit_export').val();
			var tanggal = $('#tanggal_filter').val();
			var datax = {param:'search',bagian:bagian,unit:unit,tanggal:tanggal,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/val_perjalanan_dinas/view_all/')?>",
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
			{   targets: 1,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<a href="<?php base_url()?>view_perjalanan_dinas/'+full[8]+'">' +data+'</a>';
				}
			},
			{   targets: 6,
				width: '15%',
				render: function ( data, type, full, meta ) {
					return ''+data+' Data Perjalanan Dinas';
				}
			},
			{   targets: 7, 
				width: '7%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});		
	}
	function pilih_karyawan() {
		$('#modal_pilih_karyawan').modal('toggle');
		$('#modal_pilih_karyawan .header_data').html('Pilih Karyawan');
		$('#table_pilih').DataTable( {
			ajax: "<?php echo base_url('employee/pilih_k_mutasi')?>",
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('kemp/val_perjalanan_dinas/kode');?>",'no_sk_add');
	}
	function view_modal(id) {
		var data={id_pd:id};
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_peringatanasal_view').html(callback['status_lama']);
		$('#data_peringatanbaru_view').html(callback['status_baru']);
		$('#data_keterangan_view').html(callback['keterangan']);
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
		$('#data_tabel_view').html(callback['tabel']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_loker_view').html(callback['loker']);
	}
	function delete_modal(id) {
		var data={id_pd:id};
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_one')?>",data);
		var datax={table:table,column:'id_karyawan',id:callback['id_karyawan'],nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			}else{
				e.preventDefault(); 
				if(form=='form_add'){
					do_add()
				}else{
					do_edit()
				}
			}
		})
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/add_perjalanan_dinas')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#table_data_trans').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			refreshCode();
			resetselectAdd();
		}else{
			notValidParamx();
		} 
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/edit_perjalanan_dinas')?>",'edit_u','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
			$('#table_data_trans').DataTable().ajax.reload();
		}else{
			refreshCode();
			notValidParamx();
		} 
	}
	function rekap() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/val_perjalanan_dinas')?>?"+data;
	}
	function kendaraanPD(f) {
		setTimeout(function () {
			var name = $('#data_kendaraan_add').val();
			if(name == 'KPD0001') {
				$('#nama_kendaraan_umum').show();
				$('#data_kendaraan_umum_add').attr('required','required');
			}else {
				$('#nama_kendaraan_umum').hide();
				$('#data_kendaraan_umum_add').removeAttr('required');
				$('#data_kendaraan_umum_add').val('');
			}
		},100);
	}
	function tujuanPD(f) {
		setTimeout(function () {
			var name = $('#tujuan').val();
			if(name == 'plant') {
				$('#tujuan_plant').show();
				$('#tujuan_non_plant').hide();
				$('#data_plant_tujuan_add').attr('required','required');
				$('#data_lokasi_tujuan_add').removeAttr('required');
				$('#data_jarak_add').removeAttr('required');
				$('#data_jarak_add').val('');
			}else if(name == 'non_plant') {
				$('#tujuan_plant').hide();
				$('#tujuan_non_plant').show();
				$('#data_plant_tujuan_add').removeAttr('required');
				$('#data_lokasi_tujuan_add').attr('required','required');
				$('#data_jarak_add').attr('required','required');
			}else{
				$('#tujuan_plant').hide();
				$('#tujuan_non_plant').hide();
				$('#data_plant_tujuan_add').removeAttr('required');
				$('#data_lokasi_tujuan_add').removeAttr('required');
				$('#data_jarak_add').removeAttr('required');
			}
		},100);
	}
	$(document).on('click', '.pilih', function (e1) {
		$("#id_karyawan").val($(this).data('id_karyawan'));
		$("#nik").val($(this).data('nik'));
		$("#nama").val($(this).data('nama'));
		$("#jabatan").val($(this).data('jabatan'));
		$("#nama_jabatan").val($(this).data('nama_jabatan'));
		$('#modal_pilih_karyawan').modal('hide');
	});
	function per_trans(){
		var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		$('#table_data_trans').DataTable().destroy();
		$('#table_data_trans').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/val_perjalanan_dinas/view_all_trans/')?>",
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
			{   targets: 1,
				width: '15%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 6, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 8, 
				width: '7%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});		
	}
	function view_modal_u(kode) {
		var data={no_sk:kode};
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_one_trans')?>",data);
		$('#view_u').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('input[name="data_kode_view"]').val(callback['no_sk']);
		$('#data_nosk_view').html(callback['no_sk']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_berangkat_view').html(callback['tanggal_berangkat']);
		$('#data_sampai_view').html(callback['tanggal_sampai']);
		$('#data_pulang_view').html(callback['tanggal_pulang']);
		$('#data_asal_view').html(callback['asal']);
		$('#data_tujuan_view').html(callback['tujuan']);
		$('#data_kedaraan_view').html(callback['kendaraan']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
		$('#data_dibuat_view').html(callback['dibuat']);
		$('#data_tugas_view').html(callback['tugas']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_nama_penginapan_view').html(callback['nama_penginapan']);
		$('#data_jarak_view').html(callback['jarak']);
		$('#data_nominal_bbm_view').html(callback['nominal_bbm']);
		$('#data_nominal_penginapan_view').html(callback['nominal_penginapan']);
		$('#data_jumlah_kendaraan_view').html(callback['jumlah_kendaraan']);
		$('#data_nominal_per_ken_view').html(callback['nominal_per_ken']);
		var menginap = callback['menginap'];
		var mengipal_val=(menginap==1)?'<b class="text-success">Menginap</b>':'<b class="text-danger">Tidak Menginap</b>';
		$('#data_menginap_view').html(mengipal_val);
		$('#tabel_tunjangan').html(callback['tabel_tunjangan']);
		var status = callback['status'];
		var statusval =(status==1)?'<b class="text-success">Aktif</b>':'<b class="text-danger">Tidak Aktif</b>';
		$('#data_status_trans').html(statusval);
		$('#data_create_date_trans').html(callback['create_date']+' WIB');
		$('#data_update_date_trans').html(callback['update_date']+' WIB');
		$('input[name="data_id_trans"]').val(callback['id']);
		$('#data_create_by_trans').html(callback['nama_buat']);
		$('#data_update_by_trans').html(callback['nama_update']);
	}
	function edit_modal_u() {
		// refreshCode();
		$('#menginap_off_edit').click(function(){
			$('#menginap_off_edit').hide();
			$('#menginap_on_edit').show();
			$('#menginap_edit').val('1');
			$('#penginapan_edit_div').show();
			$('#data_penginapan_edit').attr('required','required');
		})
		$('#menginap_on_edit').click(function(){
			$('#menginap_off_edit').show();
			$('#menginap_on_edit').hide();
			$('#menginap_edit').val('0');
			$('#penginapan_edit_div').hide();
			$('#data_penginapan_edit').removeAttr('required');
			$('#data_penginapan_edit').val('').trigger('change');
		})
		select_data('data_plant_asal_edit',url_select,'master_loker','kode_loker','nama');
		select_data('data_plant_tujuan_edit',url_select,'master_loker','kode_loker','nama');
		select_data('data_kendaraan_edit',url_select,'master_pd_kendaraan','kode','nama');
		getSelect2("<?php echo base_url('kemp/val_perjalanan_dinas/pilihtunjangan')?>",'data_tunjangan_edit');
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit,#data_dibuat_edit,#karyawan_edit_trans');
		var kode = $('input[name="data_kode_view"]').val();
		var data={no_sk:kode};
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_one_trans')?>",data); 
		$('#view_u').modal('toggle');
		setTimeout(function () {
			$('#edit_u').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		// $('#data_idk_edit').val(callback['id_karyawan']);
		$('#karyawan_edit_trans').val(callback['e_karyawan']).trigger('change');
		$('#data_nosk_edit').val(callback['no_sk']);
		$('#karyawan_old').val(callback['e_karyawan']).trigger('change');
		$('#no_sk_old').val(callback['no_sk']);
		$('#data_tglsk_edit').val(callback['etgl_sk']);
		$('#tujuan_edit').val(callback['plant']).trigger('change');
		$('#data_kendaraan_edit').val(callback['e_kendaraan']).trigger('change');
		$('#data_plant_asal_edit').val(callback['e_plant_asal']).trigger('change');
		$('#data_plant_tujuan_edit').val(callback['e_plant_tujuan']).trigger('change');
		$('#data_lokasi_tujuan_edit').val(callback['e_lokasi']);
		$('#data_jarak_edit').val(callback['e_jarak']);
		$('#data_kendaraan_umum_edit').val(callback['e_kendaraan_umum']).trigger('change');
		$('#data_tunjangan_edit').val(callback['e_tunjangan']).trigger('change');
		$("#data_tanggal_edit").data('daterangepicker').setStartDate(callback['e_tanggal_mulai']);
		$("#data_tanggal_edit").data('daterangepicker').setEndDate(callback['e_tanggal_selesai']);
		$('#data_tanggal_pulang_edit').val(callback['e_tanggal_pulang']);
		$('#data_mengetahui_edit').val(callback['e_mengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['e_menyetujui']).trigger('change');
		$('#data_dibuat_edit').val(callback['e_dibuat']).trigger('change');
		$('#data_tugas_edit').val(callback['e_tugas']);
		$('#data_keterangan_edit').val(callback['e_keterangan']);
		$('#data_nominal_bbm_edit').val(callback['nominal_bbm']);
		$('#data_jum_ken_edit').val(callback['jumlah_kendaraan_edit']);
		$('#data_nominal_penginapan_edit').val(callback['nominal_penginapan']);
		$('#tabel_tunjangan_edit').val(callback['tabel_tunjangan_edit']);
		var menginap = callback['menginap'];
		if(menginap==1){
			$('#menginap_on_edit').show();
			$('#menginap_off_edit').hide();
			$('#penginapan_edit_div').show();
			$('#data_penginapan_edit').attr('required','required');
			$('#data_penginapan_edit').val(callback['e_nama_penginapan']).trigger('change');
		}else{
			$('#menginap_on_edit').hide();
			$('#menginap_off_edit').show();
			$('#penginapan_edit_div').hide();
			$('#data_penginapan_edit').removeAttr('required');
			$('#data_penginapan_edit').val('').trigger('change');
			// $('#nominal_penginapan_edit').hide();
		}
		// $('#nominal_bbm_all_edit').html(callback['nominal_bbm']);
		// $('#data_kendaraan_all_edit').html(callback['kendaraan']);
		// $('#nominal_tunjangan_all_edit').html(callback['nominal_penginapan']);
		// $('#data_tunjangan_all_edit').html(callback['nama_penginapan']);
		// var jumlah = callback['jumlah'];
		// if (jumlah == 2){
		// 	$('#total_tunjangan_edit').hide();
		// }else{
		// 	$('#total_tunjangan_edit').show();
		// }
	}
	function kendaraanPDEdit(f) {
		setTimeout(function () {
			var name = $('#data_kendaraan_edit').val();
			if(name == 'KPD0001') {
				$('#nama_kendaraan_umum_edit').show();
				$('#data_kendaraan_umum_edit').attr('required','required');
			}else {
				$('#nama_kendaraan_umum_edit').hide();
				$('#data_kendaraan_umum_edit').removeAttr('required');
			}
		},100);
	}
	function tujuanPDEdit(f) {
		setTimeout(function () {
			var name = $('#tujuan_edit').val();
			if(name == 'plant') {
				$('#tujuan_plant_edit').show();
				$('#tujuan_non_plant_edit').hide();
				$('#data_plant_tujuan_edit').attr('required','required');
				$('#data_lokasi_tujuan_edit').removeAttr('required');
				$('#data_jarak_edit').removeAttr('required');
				$('#data_jarak_edit').val('');
			}else if(name == 'non_plant') {
				$('#tujuan_plant_edit').hide();
				$('#tujuan_non_plant_edit').show();
				$('#data_plant_tujuan_edit').removeAttr('required');
				$('#data_lokasi_tujuan_edit').attr('required','required');
				$('#data_jarak_edit').attr('required','required');
			}else{
				$('#tujuan_plant_edit').hide();
				$('#tujuan_non_plant_edit').hide();
				$('#data_plant_tujuan_edit').removeAttr('required');
				$('#data_lokasi_tujuan_edit').removeAttr('required');
				$('#data_jarak_edit').removeAttr('required');
			}
		},100);
	}
	function jstreeLoad(datax,usage) {
		$.each(datax, function (index, value) {
			$('#'+value).on('ready.jstree', function () {
				$("#"+value).jstree("open_all");
			});
			$('#'+value).on("changed.jstree", function (e, data) {			
				var checked_ids = [data.selected];
				var undetermined=$('#'+value).jstree().get_undetermined();
				undetermined = jQuery.grep(undetermined, function( a ) {
					return a != 0;
				});
				if (undetermined.length > 0) {
					checked_ids.push(undetermined);
				}
				$('#'+value+'_'+usage).val(checked_ids);
			});
		});
	}
	function view_tunjangan_edit() {
		var data=$('#form_edit').serialize();
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_tunjangan')?>",{search:data});  
		$('#tabel_tunjangan_edit').html(callback['tabel']);
		$('#tabel_tunjangan_edit').slideToggle('slow');
		$('#nominal_bbm_all_edit').html(callback['nominal_bbm_all']);
		$('#data_kendaraan_all_edit').html(callback['nama_kendaraan_all']);
		$('#nominal_tunjangan_all_edit').html(callback['nominal_tunjangan_all']);
		$('#data_tunjangan_all_edit').html(callback['nama_tunjangan_all']);
		$('#nominal_jum_ken_edit').html(callback['jum_ken']);
		$('#nominal_per_ken_edit').html(callback['nominal_bbm_per_ken']);
		var jumlah = callback['jumlah'];
		if (jumlah == 1){
			$('#total_tunjangan_edit').hide();
		}else{
			$('#total_tunjangan_edit').show();
		}
	}
	function delete_modal_u(id) {
		var data={no_sk:id};
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_one_trans')?>",data);
		var datax={table:table,column:'no_sk',id:callback['no_sk'],nama:callback['no_sk'],table_view:'#table_data_trans'};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function modal_need(id) {
		var data={no_sk:id};
		$('#m_need').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_one_trans')?>",data);
		$('#m_need #data_id_need').val(callback['no_sk']);
		// $('#m_need #data_idk_need').val(callback['id_karyawan']);
		// $('#m_need #data_jenis_need').val(callback['nama_jenis_ic']);
		$('#m_need .header_data').html(callback['no_sk']);
	}
	function modal_yes(id) {
		var data={no_sk:id};
		$('#m_yes').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_one_trans')?>",data);
		$('#m_yes #data_id_yes').val(callback['no_sk']);
		// $('#m_yes #data_idk_yes').val(callback['id_karyawan']);
		// $('#m_yes #data_jenis_yes').val(callback['nama_jenis_ic']);
		$('#m_yes .header_data').html(callback['no_sk']);
	}
	function modal_no(id) {
		var data={no_sk:id};
		$('#m_no').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/val_perjalanan_dinas/view_one_trans')?>",data);
		$('#m_no #data_id_no').val(callback['no_sk']);
		// $('#m_no #data_idk_no').val(callback['id_karyawan']);
		// $('#m_no #data_jenis_no').val(callback['nama_jenis_ic']);
		$('#m_no .header_data').html(callback['no_sk']);
	}
  	function do_validasi(data,val,form){
		if(data==2){
			var id = $('#data_id_need').val();
			// var idk = $('#data_idk_need').val();
			// var jenis = $('#data_jenis_need').val();
		}else if(data==1){
			var id = $('#data_id_yes').val();
			// var idk = $('#data_idk_yes').val();
			// var jenis = $('#data_jenis_yes').val();
		}else if(data==0){
			var id = $('#data_id_no').val();
			// var idk = $('#data_idk_no').val();
			// var jenis = $('#data_jenis_no').val();
		}
		// var datax={no_sk:id,id_k:idk,validasi_db:data,validasi:val,jenis_db:jenis};
		var datax={no_sk:id,validasi_db:data,validasi:val,};
		submitAjax("<?php echo base_url('kemp/change_status_perjalanan_dinas')?>",form,datax,null,null,'status');
		$('#table_data_trans').DataTable().ajax.reload();
  	}
</script> 
