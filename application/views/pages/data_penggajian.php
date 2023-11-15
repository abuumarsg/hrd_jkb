<style type="text/css">
	table#table_data thead tr th,
	table#table_data tbody tr td,
	table.DTFC_Cloned thead tr th {
		white-space: pre;
	}

	table.DTFC_Cloned tbody {
		overflow: hidden;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Penggajian
			<small>Data penggajian</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active">Data Penggajian</li>
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
					<div class="box-body">
						<form id="adv_form_filter">
							<input type="hidden" name="usage" value="data">
							<div class="col-md-1">
							</div>
							<div class="col-md-5">
								<div class="">
									<label>Pilih Periode</label>
									<select class="form-control select2" name="periode" id="data_periode_adv"
										style="width: 100%;" onchange="get_bagian_adv(this.value, 'adv_bagian')">
										<?php
												echo '<option></option>';
												foreach ($periode as $p) {
													echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
												}
											?>
									</select>
								</div>
							</div>
							<div class="col-md-5">
								<div class="">
									<label>Pilih Bagian</label>
									<select class="form-control select2" name="bagian" id="adv_bagian" style="width: 100%;"></select>
								</div>
							</div>
						</form>
					</div>
					<div class="box-footer">
						<div class="col-md-12">
							<div class="pull-right">
								<button type="button" onclick="table_data('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data Penggajian</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="table_data('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div id="accordion">
									<div class="panel">
										<div class="row">
											<div class="col-md-12">
												<div class="pull-left">
													<?php 
													if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<button id="btn_tambah" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Proses Payroll</button>';
														echo '<button class="btn btn-primary" onclick="log_confirm()" style="margin-left: 5px;float: left;"><i class="fas fa-check"></i> Selesai Payroll</button>';
													}
													?>
													<!-- <div class="dropdown" style="float: left;margin-left: 5px;">
														<button class="btn btn-warning dropdown-toggle" type="button"
															data-toggle="dropdown"><i class="fa fa-print fa-fw"></i>
															Cetak
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a onclick="print_slip()">Slip Gaji</a></li>
															<li><a onclick="rekap_gaji()">Rekap</a></li>
														</ul>
													</div> -->
													<div class="dropdown" style="float: left;margin-left: 5px;">
														<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-print fa-fw"></i> Cetak <span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a onclick="do_print_slip()">Slip Gaji</a></li>
															<li><a onclick="rekap_gaji()">Rekap Gaji</a></li>
															<li><a onclick="rekap_bagian()">Rekapitulasi Gaji Bagian</a></li>
															<li><a onclick="rekap_tanda_terima()">Cetak Tanda Terima</a></li>
															<li><a onclick="rekap_transfer()">Cetak Transfer Bank</a></li>
															<!-- <li><a onclick="do_rekap_bagian()">Rekapitulasi Gaji Bagian</a></li> -->
														</ul>
													</div>
													<?php if (in_array('SNC', $access['access'])) { ?>
													<button class="btn btn-info" href="#sync" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" style="margin-left: 5px;float: left;"><i class="fas fa-refresh"></i> Sync Data</button>
													<?php } ?>
												</div>
												<div class="pull-right" style="font-size: 8pt;">
												</div>
											</div>
										</div>
										<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
										<div id="add" class="modal fade" role="dialog">
											<div class="modal-dialog modal-md">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close"
															data-dismiss="modal">&times;</button>
														<h2 class="modal-title">Tambah Data Penggajian Baru</h2>
													</div>
													<form id="form_add">
														<div class="modal-body">
															<div class="form-group">
																<label>Pilih Periode Penggajian</label>
																<select class="form-control select2" name="kode_periode"
																	id="data_periode_add" style="width: 100%;">
																	<?php
																	
																	// $periode = $this->model_master->getPeriodePenggajian(['a.status_gaji'=>0,'a.status'=>1],null,1);
																	echo '<option></option>';
																	foreach ($periode as $p) {
																		echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
																	}
																	?>
																</select>
															</div>
															<hr>
															<div style="text-align: center;"><label>Data Pendukung</label></div>
															<div class="row">
																<div class="col-md-6">
																	<input type="checkbox" id="data_ritasi_add"> Data Ritasi<br>
																	<input type="checkbox" id="data_insentif_add"> Data Insentif<br>
																	<input type="checkbox" id="data_bpjs_add"> Data BPJS<br>
																</div>
																<div class="col-md-6">
																	<input type="checkbox" id="data_pinjaman_add"> Data Pinjaman<br>
																	<input type="checkbox" id="data_denda_add"> Data Denda<br>
																	<!-- <input type="checkbox" id="data_tdk_tetap_add"> Data Tunjangan Tidak Tetap<br> -->
																	<input type="checkbox" id="data_lain_add"> Lain-Lain<br>
																</div>
																<input type="hidden" name="data_ritasi" id="data_ritasi_hidden" value="data_ritasi">
																<input type="hidden" name="data_insentif" id="data_insentif_hidden" value="data_insentif">
																<input type="hidden" name="data_bpjs" id="data_bpjs_hidden" value="data_bpjs">
																<input type="hidden" name="data_pinjaman" id="data_pinjaman_hidden" value="data_pinjaman">
																<input type="hidden" name="data_denda" id="data_denda_hidden" value="data_denda">
																<!-- <input type="hidden" name="data_tdk_tetap" id="data_tdk_tetap_hidden" value="data_tdk_tetap"> -->
																<input type="hidden" name="data_lain" id="data_lain_hidden" value="data_lain">
															</div>
															<hr>
															<!-- <div class="form-group">
																<label>Pilih Metode BPJS</label>
																<select class="form-control select2" name="metode_bpjs" id="data_metode_bpjs_add" style="width: 100%;" required="required">
																	<option></option>
																	<option value="persentase">Persentase</option>
																	<option value="nominal">Nominal</option>
																</select>
															</div> -->
														</div>
														<div class="modal-footer">
															<div id="progress2" style="float: left;"></div>
															<button type="button" onclick="do_add()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
															<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
														</div>
													</form>
												</div>
											</div>
										</div>
										<?php } ?>
										<div id="sync" class="collapse"><br>
											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title"><i class="fas fa-refresh"></i> Syncronize Data Penggajian Bulanan</h3>
												</div>
												<form id="form_sync" class="form-horizontal">
													<div class="box-body">
														<input type="hidden" name="usage" value="data">
														<div class="row">
															<div class="col-md-12">
																<div class="col-md-1"></div>
																<div class="col-md-10">
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Pilih Periode</label>
																		<div class="col-sm-9">
																			<select class="form-control select2" name="periode" id="data_periode_sync" style="width: 100%;" onchange="get_bagian_sync(this.value)">
																				<?php
																				
																				echo '<option></option>';
																				foreach ($periode as $p) {
																					echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Pilih Bagian</label>
																		<div class="col-sm-9">
																			<select class="form-control select2" name="bagian" id="sync_bagian" style="width: 100%;" onchange="get_karyawan_sync(this.value)"></select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Pilih Karyawan</label>
																		<div class="col-sm-9">
																			<select class="form-control select2" name="karyawan[]" id="sync_karyawan" multiple="multiple" style="width: 100%;"></select>
																		</div>
																	</div>
																	<div class="form-group">
																		<div class="col-sm-3"></div>
																		<div class="col-sm-9">
																			<input type="checkbox" id="data_bpjs_sync"> Data BPJS<br>
																		</div>
																	</div>
																	<input type="hidden" name="data_bpjs" id="data_bpjs_hidden_sync" value="data_bpjs">
																	<!-- <div class="form-group">
																		<label class="col-sm-3 control-label">Pilih Metode BPJS</label>
																		<div class="col-sm-9">
																			<select class="form-control select2" name="metode_bpjs" id="data_metode_bpjs_sync" style="width: 100%;" required="required">
																				<option></option>
																				<option value="persentase">Persentase</option>
																				<option value="nominal">Nominal</option>
																			</select>
																		</div>
																	</div> -->
																</div>
															</div>
														</div>
													</div>
													<div class="box-footer">
														<div class="pull-right">
															<button type="button" onclick="do_sync_data()" class="btn btn-success"><i class="fas fa-refresh"></i> Sync Data</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="callout callout-info text-left">
									<b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
									<ul>
										<li><b>List Default TIDAK menampilkan data</b>, Silahkan gunakan <b>Filter Pencarian</b> diatas untuk mencari data yang diinginkan.</li>
									</ul>
								</div>
								<table id="table_data" class="table table-bordered table-striped table-responsive"
									width="100%">
									<thead>
										<tr>
											<th rowspan="2">No.</th>
											<th rowspan="2">NIK Karyawan</th>
											<th rowspan="2">Nama Karyawan</th>
											<th rowspan="2">Jabatan</th>
											<th rowspan="2">Grade</th>
											<th rowspan="2">Tanggal Masuk</th>
											<th rowspan="2">Masa Kerja</th>
											<th rowspan="2">Gaji Pokok (Rp)</th>
											<?php
												echo '<th class="text-center" style="background-color:green;" colspan="'.count($indukTunjanganTetap).'">DATA TUNJANGAN TETAP</th>';
											?>
											<?php
												echo '<th class="text-center" style="background-color:yellow;" colspan="'.count($indukTunjanganNon).'">DATA TUNJANGAN TIDAK TETAP</th>';
											?>
											<th rowspan="2">Insentif (Rp)</th>
											<th rowspan="2">Ritasi (Rp)</th>
											<th rowspan="2">Uang Makan (Rp)</th>
											<th rowspan="2">Potongan Tidak Masuk (Rp)</th>
											<th rowspan="2">BPJS JHT (Rp)</th>
											<!-- <th rowspan="2">BPJS JKK (Rp)</th>
											<th rowspan="2">BPJS JKM (Rp)</th> -->
											<th rowspan="2">BPJS JPEN (Rp)</th>
											<th rowspan="2">BPJS JKES (Rp)</th>
											<th rowspan="2">Angsuran (Rp)</th>
											<!-- <th rowspan="2">Angsuran Ke</th> -->
											<th rowspan="2">Denda (Rp)</th>
											<th rowspan="2">Angsuran Denda Ke</th>
											<th class="text-center" colspan="3" style="background-color:yellow;">Pendukung Lainnya</th>
											<!-- <th rowspan="2">Jenis Lainnya</th>
											<th rowspan="2">Nominal Lainnya (Rp)</th>
											<th rowspan="2">Keterangan Lainnya</th> -->
											<th rowspan="2">Gaji Bersih (Rp)</th>
											<th rowspan="2">Nomor Rekening</th>
											<!-- <th rowspan="2">Tanggal</th> -->
											<th rowspan="2">Tanggal</th>
											<th rowspan="2">Aksi</th>
										</tr>
										<tr>
											<?php
												foreach ($indukTunjanganTetap as $key_tt) {
													echo '<th>'.ucwords(strtolower(str_replace('TUNJANGAN','',$key_tt->nama))).' (Rp)</th>';
												}
											?>
											<?php
												foreach ($indukTunjanganNon as $key_it) {
													echo '<th>'.ucwords(strtolower(str_replace('TUNJANGAN','',$key_it->nama))).' (Rp)</th>';
												}
											?>
											<th>Jenis</th>
											<th>Nama</th>
											<th>Nominal (Rp)</th>
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
							<div class="col-md-6" id="data_name_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Grade</label>
							<div class="col-md-6" id="data_grade_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bagian</label>
							<div class="col-md-6" id="data_bagian_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Gaji Pokok</label>
							<div class="col-md-6" id="data_gaji_pokok_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Sistem Penggajian</label>
							<div class="col-md-6" id="data_sistem_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Masuk</label>
							<div class="col-md-6" id="data_tanggal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Masa Kerja</label>
							<div class="col-md-6" id="data_masa_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Rekening</label>
							<div class="col-md-6" id="data_rekening_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Periode Penggajian</label>
							<div class="col-md-6" id="data_periode_view"></div>
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
							<div class="col-md-6" id="data_create_by_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<div id="data_gaji_bersih_view"></div>
						</div>
						<div class="form-group col-md-12">
							<div id="data_pph_view"></div>
						</div>
					</div>
					<div class="col-md-12">
						<div style="text-align: center;">
							<h3>Detail Data</h3>
						</div>
						<div style="overflow-y: auto;height: 300px;">
							<div class="col-md-6">
								<div id="data_penambah_view"></div>
							</div>
							<div class="col-md-6">
								<div id="data_pengurang_view"></div>
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
<div id="log" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Selesai</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_log">
					<input type="hidden" name="usage" value="pindah">
					<p>Yakin Simpan Data?<br>Data Tidak akan bisa di update lagi.</p>
					<div class="form-group">
						<label>Pilih Periode Penggajian</label>
						<select class="form-control select2" name="kode_periode" id="data_periode_selesai"
							style="width: 100%;">
							<?php
							
							echo '<option></option>';
							foreach ($periode as $p) {
								echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
							}
							?>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_log()" class="btn btn-primary"><i class="fas fa-check"></i> Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="confirm_ada_data" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Data Sudah Ada</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_ada_data">
					<input type="hidden" name="kode_periode" id="kode_periode_ada_data">
					<p>Data Penggajian Pada Periode Tersebut Sudah Ada, <br>Data yg sudah anda buat pada periode
						tersebut harus dihapus terlebih dahulu.<br>Apakah anda yakin ingin menghapus & membuat ulang?
					</p>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_ada_data()" class="btn btn-danger"><i class="fas fa-trash"></i>
					Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="rekap_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Mode</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_filter">
					<input type="hidden" name="mode" id="usage_rekap_mode" value="">
					<input type="hidden" name="usage" value="data">
					<div class="form-group">
						<label>Pilih Yang Menyetujui</label>
						<select class="form-control select2" name="karyawan_rekap" id="karyawan_rekap"
							style="width: 100%;"></select>
					</div>
				</form>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_rekap('pdf')"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-primary" onclick="do_rekap('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<div id="bagian_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Rekap Per Bagian</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_bagian">
					<input type="hidden" name="mode" id="usage_bagian_mode" value="">
					<input type="hidden" name="usage" value="data">
					<div class="form-group">
						<label>Pilih Yang Mengetahui</label>
						<select class="form-control select2" name="karyawan_bagian_mengetahui"
							id="karyawan_bagian_mengetahui" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Menyetujui</label>
						<select class="form-control select2" name="karyawan_bagian_menyetujui"
							id="karyawan_bagian_menyetujui" style="width: 100%;"></select>
					</div>
				</form>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_rekap_bagian('pdf')"><i
							class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-primary" onclick="do_rekap_bagian('excel')"><i
							class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<div id="alert" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-exclamation-triangle"></i> Alert!</h4>
			</div>
			<div class="modal-body text-center">
				<p>Data Tidak Ditemukan!</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="rekap_tanda_terima" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Tanda Terima</h4>
			</div>
			<div class="modal-body">
				<form id="form_tanda">
					<input type="hidden" name="mode" id="usage_tanda_mode" value="">
					<input type="hidden" name="usage" value="data">
					<div class="form-group">
						<label>Pilih Periode</label>
							<select class="form-control select2" name="periode" id="data_periode_tanda" style="width: 100%;" onchange="get_bagian_adv(this.value,'bagian_tanda')">
								<?php
									echo '<option></option>';
									foreach ($periode as $p) {
										echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
									}
								?>
							</select>
					</div>
					<div class="form-group">
						<label>Pilih Bagian</label>
						<select class="form-control select2" name="bagian[]" multiple="multiple" id="bagian_tanda" style="width: 100%;"></select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right">
					<button type="button" class="btn btn-danger" onclick="do_rekap_ttd()"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="rekap_transfer_bank" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Transfer Bank</h4>
			</div>
			<div class="modal-body">
				<form id="form_transfer_bank">
					<input type="hidden" name="mode" id="usage_transfer_bank_mode" value="">
					<input type="hidden" name="usage" value="data">
					<div class="form-group">
						<label>Pilih Periode</label>
						<select class="form-control select2" name="periode" id="data_periode_jab" style="width: 100%;" onchange="get_bagian_adv(this.value,'data_bagian_rekap_transfer_bank')">
						<?php
							
							echo '<option></option>';
							foreach ($periode as $p) {
								echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
							}
						?>
						</select>
					</div>
					<div class="form-group">
						<label>Pilih Bagian</label>
						<select class="form-control select2" name="bagian[]" multiple="multiple" id="data_bagian_rekap_transfer_bank" style="width: 100%;"></select>
					</div>
				</form>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right">
      				<button type="button" class="btn btn-primary" onclick="do_rekap_transfer_bank('excel')"><i class="fas fa-file-excel fa-fw"></i>Cetak Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('_partial/_loading') ?>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select = "<?php echo base_url('global_control/select2_global');?>";
	var table = "data_penggajian";
	var column = "id_penggajian";
	var jumlah_induk_t = <?=count($indukTunjanganTetap)?>; 
	var jumlah_induk = <?=count($indukTunjanganNon)?>; 
	$(document).ready(function () {
		table_data('all');
		$('#data_ritasi_add').on('ifChecked', function () {
			$('#data_ritasi_hidden').val('data_ritasi');
		});
		$('#data_insentif_add').on('ifChecked', function () {
			$('#data_insentif_hidden').val('data_insentif');
		});
		$('#data_bpjs_add').on('ifChecked', function () {
			$('#data_bpjs_hidden').val('data_bpjs');
			$('#data_metode_bpjs_add').parent().show();
			$('#data_metode_bpjs_add').val('nominal').trigger('change');
		});
		$('#data_pinjaman_add').on('ifChecked', function () {
			$('#data_pinjaman_hidden').val('data_pinjaman');
		});
		$('#data_denda_add').on('ifChecked', function () {
			$('#data_denda_hidden').val('data_denda');
		});
		$('#data_lain_add').on('ifChecked', function () {
			$('#data_lain_hidden').val('data_lain');
		});
		$('#data_tdk_tetap_add').on('ifChecked', function () {
			$('#data_tdk_tetap_hidden').val('data_tdk_tetap');
		});

		$('#data_ritasi_add').on('ifUnchecked', function () {
			$('#data_ritasi_hidden').val('');
		});
		$('#data_insentif_add').on('ifUnchecked', function () {
			$('#data_insentif_hidden').val('');
		});
		$('#data_bpjs_add').on('ifUnchecked', function () {
			$('#data_bpjs_hidden').val('');
			$('#data_metode_bpjs_add').val('').trigger('change');
			$('#data_metode_bpjs_add').parent().hide();
		});
		$('#data_pinjaman_add').on('ifUnchecked', function () {
			$('#data_pinjaman_hidden').val('');
		});
		$('#data_denda_add').on('ifUnchecked', function () {
			$('#data_denda_hidden').val('');
		});
		$('#data_lain_add').on('ifUnchecked', function () {
			$('#data_lain_hidden').val('');
		});
		$('#data_tdk_tetap_add').on('ifUnchecked', function () {
			$('#data_tdk_tetap_hidden').val('');
		});
		$('#data_bpjs_sync').iCheck('check');
		$('#data_bpjs_sync').on('ifChecked', function () {
			$('#data_bpjs_hidden_sync').val('data_bpjs');
			$('#data_metode_bpjs_sync').parent().show();
			$('#data_metode_bpjs_sync').val('nominal').trigger('change');
		});
		$('#data_bpjs_sync').on('ifUnchecked', function () {
			$('#data_bpjs_hidden_sync').val('');
			$('#data_metode_bpjs_sync').val('').trigger('change');
			$('#data_metode_bpjs_sync').parent().hide();
		});

		$('#data_metode_bpjs_add').val('nominal').trigger('change');
		var kode_periode = $('#data_periode_add').val();
		// table_data(kode_periode);
		$('#btn_tambah').click(function () {
			$('#add').modal('show');
			$('#data_ritasi_add').iCheck('check');
			// $('#data_insentif_add').iCheck('check');
			$('#data_bpjs_add').iCheck('check');
			$('#data_pinjaman_add').iCheck('check');
			// $('#data_denda_add').iCheck('check');
			$('#data_lain_add').iCheck('check');
			$('#data_tdk_tetap_add').iCheck('check');
		})
	});

	function table_data(kode) {
		if (kode == 'all') {
			var datax = {
				param: 'all',
				access: "<?php echo $this->codegenerator->encryptChar($access);?>",
				sistem_penggajian: 'BULANAN'
			};
		} else {
			var periode = $('#data_periode_adv').val();
			var bagian = $('#adv_bagian').val();
			var datax = {
				param: 'search',
				bagian: bagian,
				periode: periode,
				access: "<?php echo $this->codegenerator->encryptChar($access);?>",
				sistem_penggajian: 'BULANAN'
			};
		}
		$('#table_data').DataTable().destroy();
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('Cpayroll/data_penggajian/view_all/')?>",
				type: 'POST',
				data: datax,
			},
			fixedColumns: {
				leftColumns: 3,
				rightColumns: 1
			},
			scrollX: true,
			autoWidth: false,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				// {
				// 	targets: 27,
				// 	width: '5%',
				// 	render: function (data, type, full, meta) {
				// 		return '<center>' + data + '</center>';
				// 	}
				// },
				{
					targets: 24+jumlah_induk_t+jumlah_induk,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
	}

	function view_modal(id) {
		var data = {
			id_pay: id,
			mode: 'view'
		};
		var callback = getAjaxData("<?php echo base_url('Cpayroll/data_penggajian/view_one')?>", data);
		$('#view').modal('show');
		$('.header_data').html(callback['nama_karyawan']);
		$('input[name="data_id_view"]').val(callback['id']);

		$('#data_nik_view').html(callback['nik']);
		$('#data_name_view').html(callback['nama_karyawan']);

		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_grade_view').html(callback['grade']);
		$('#data_bagian_view').html(callback['bagian']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_sistem_view').html(callback['sistem']);
		$('#data_tanggal_view').html(callback['tanggal']);
		$('#data_masa_view').html(callback['masa']);
		$('#data_rekening_view').html(callback['rekening']);
		$('#data_penambah_view').html(callback['penambah']);
		$('#data_pengurang_view').html(callback['pengurang']);
		$('#data_gaji_bersih_view').html(callback['total_gaji']);
		$('#data_gaji_pokok_view').html(callback['gaji_pokok']);
		$('#data_pph_view').html(callback['pph']);

		$('#data_periode_view').html(callback['periode']);
		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date'] + ' WIB');
		$('#data_update_date_view').html(callback['update_date'] + ' WIB');
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}

	function do_add() {
		var kode_periode = $('#data_periode_add').val();
		var data_bpjs = $('#data_bpjs_hidden').val();
		var metode_bpjs = $('#data_metode_bpjs_add').val();

		if (kode_periode == '') {
			notValidParamxCustom('Harap Pilih Periode!');
		} else {
			if (data_bpjs == '') {
				do_go_add(kode_periode);
			} else {
				if (metode_bpjs == '') {
					notValidParamxCustom('Harap Pilih Metode BPJS!');
				} else {
					do_go_add(kode_periode);
				}
			}
		}
	}

	function do_go_add(kode_periode) {
		// show_loader();
		$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, sedang memproses data....');
		// $('#loading_progress div div div p b').html(' Mohon tunggu, sedang menyiapkan halaman . . .');
		$('#progress2').show();
		var data = $('#form_add').serialize();
		var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_payroll')?>", data);
		if (cek_ready['msg'] == 'true') {
			submitAjax("<?php echo base_url('cpayroll/ready_data_payroll')?>", null, 'form_add', null, null);
			reload_table('table_data');
			$('#add').modal('hide');
			// $('#loading_progress').modal('hide');
			$('#progress2').hide();
			$('#data_kode_periode_hidden').val(kode_periode);
		} else if (cek_ready['msg'] == 'ada_data') {
			// $('#loading_progress').modal('hide');
			$('#add').modal('hide');
			$('#progress2').hide();
			$('#confirm_ada_data').modal('show');
			$('#kode_periode_ada_data').val(cek_ready['kode_periode']);
		} else {
			// $('#loading_progress').modal('hide');
			$('#progress2').hide();
			$('#add').modal('hide');
		}
	}

	function log_confirm() {
		$('#log').modal('show');
	}

	function do_log() {
		var kode_periode = $('#data_periode_selesai').val();
		var data = {
			kode_periode: kode_periode
		};
		var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_payroll')?>", data);
		if (cek_ready['msg'] == 'true') {
			submitAjax("<?php echo base_url('cpayroll/cek_data_payroll_notif/lembur')?>", null, null, null, null,
			'status');
		} else if (cek_ready['msg'] == 'ada_data') {
			submitAjax("<?php echo base_url('Cpayroll/send_to_log')?>", 'log', 'form_log', null, null);
			$('#table_data').DataTable().ajax.reload(function () {
				Pace.restart();
			});
			var data = {
				kode: 'tukang intip :v'
			};
			var callback = getAjaxData("<?php echo base_url('Cpayroll/getUpatePeriode')?>", data);
			$('#data_periode_add').html(callback);
		}
	}

	function do_delete_ada_data() {
		submitAjax("<?php echo base_url('Cpayroll/del_ada_data')?>", 'confirm_ada_data', 'form_ada_data', null, null);
		$('#table_data').DataTable().ajax.reload(function () {
			Pace.restart();
		});
	}
	
	function do_print_slip() {
		var kode_periode = $('#data_periode_adv').val();
		var kode_bagian = $('#adv_bagian').val();
		if (kode_periode == '') {
			$('#alert').modal('show');
		} else {
			var data = {
				kode_periode: kode_periode,
				kode_bagian: kode_bagian
			};
			var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_payroll')?>", data);
			if (cek_ready['msg'] == 'true') {
				submitAjax("<?php echo base_url('cpayroll/cek_data_payroll_notif/lembur')?>", null, null, null, null,
					'status');
			} else if (cek_ready['msg'] == 'ada_data') {
				$.redirect("<?php echo base_url('pages/slip_gaji'); ?>", {
						data_filter: $('#adv_form_filter').serialize()
					},
					"POST", "_blank");
			}
		}
	}

	function rekap_gaji() {
		$('#usage_rekap_mode').val('rekap');
		$('#rekap_mode').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>", 'karyawan_rekap');
	}

	function do_rekap(file) {
		var mode = $('#usage_rekap_mode').val();
		var emp = $('#karyawan_rekap').val();
		var kode_periode = $('#data_periode_adv').val();
		var sistem_penggajian = "BULANAN";
		if (mode == '' || kode_periode == '') {
			notValidParamx();
		} else {
			if (mode == 'rekap') {
				if (file == 'pdf') {
					$.redirect("<?php echo base_url('pages/rekap_payroll'); ?>", {
							data_filter: $('#adv_form_filter').serialize(),
							karyawan: emp,
						},
						"POST", "_blank");
				} else {
					$.redirect("<?php echo base_url('rekap/export_log_data_gaji'); ?>", {
							data_filter: $('#adv_form_filter').serialize(),
							karyawan: emp,
						},
						"POST", "_blank");
				}
			}
		}
	}

	function rekap_bagian() {
		$('#usage_bagian_mode').val('export_lembur');
		$('#bagian_mode').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'karyawan_bagian_mengetahui, #karyawan_bagian_menyetujui');
	}

	function do_rekap_bagian(file) {
		var kode_periode = $('#data_periode_adv').val();
		var mengetahui = $('#karyawan_bagian_mengetahui').val();
		var menyetujui = $('#karyawan_bagian_menyetujui').val();
		var data = {
			kode_periode: kode_periode
		};
		var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_payroll')?>", data);
		if (cek_ready['msg'] == 'true') {
			submitAjax("<?php echo base_url('cpayroll/cek_data_payroll_notif/lembur')?>", null, null, null, null,
			'status');
		} else if (cek_ready['msg'] == 'ada_data') {
			if (kode_periode == '') {
				$('#alert').modal('show');
			} else {
				if (file == 'pdf') {
					$.redirect("<?php echo base_url('pages/rekap_gaji_bulanan_bagian'); ?>", 
					{
						data_filter: $('#adv_form_filter').serialize(),
						mengetahui:mengetahui,
						menyetujui:menyetujui,
					},
					"POST", "_blank");
				} else {
					$.redirect("<?php echo base_url('rekap/export_penggajian_bulanan_bagian'); ?>", 
					{
						data_filter: $('#adv_form_filter').serialize(),
						mengetahui:mengetahui,
						menyetujui:menyetujui,
					},
					"POST", "_blank");
				}
			}
		}
	}

	function get_bagian_adv(value, id) {
		var data = {
			kode_periode: value
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian/bagian/')?>", data);
		$('#'+id).html(callback['bagian']);
	}

	function get_bagian_sync(value) {
		var data = {
			kode_periode: value
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian/bagian_sync/')?>", data);
		$('#sync_bagian').html(callback['bagian']);
	}

	function get_karyawan_sync(value) {
		var periode = $('#data_periode_sync').val();
		var data = {
			kode_periode: periode,
			kode_bagian: value
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian/karyawan/')?>", data);
		$('#sync_karyawan').html(callback['karyawan']);
	}
	function do_sync_data() {
		show_loader();
		$('#loading_progress div div div p b').html('Mohon Tunggu, Sedang men-Syncronize Data...');
		submitAjax("<?php echo base_url('cpayroll/sync_data_penggajian')?>", null, 'form_sync', null, null);
		table_data('all');
		$('#data_periode_sync').val('').trigger('change');
		$('#sync_bagian').val('').trigger('change');
		$('#sync_karyawan').val('').trigger('change');
		$('#loading_progress').modal('hide');
	}
	function rekap_tanda_terima() {
		$('#usage_bagian_mode').val('export_lembur');
		$('#rekap_tanda_terima').modal('show');
	}
	function rekap_transfer() {
		$('#rekap_transfer_bank').modal('show');
	}
	function do_rekap_ttd() {		
		$.redirect("<?php echo base_url('pages/tanda_terima_gaji'); ?>", 
		{
			data_filter: $('#form_tanda').serialize()
		},
		"POST", "_blank");
	}
	function do_rekap_transfer_bank(){
		$.redirect("<?php echo base_url('rekap/export_gaji_transfer_bank'); ?>", 
		{
			data_filter: $('#form_transfer_bank').serialize(),
		},
		"POST", "_blank");
	}
</script>
