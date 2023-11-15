<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fas fa-user-alt-slash"></i> Data
			<small>Karyawan Non Aktif & Form Exit Interview</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fas fa-user-alt-slash"></i> Karyawan Non Aktif</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a onclick="non_aktif()" href="#non_aktif" data-toggle="tab"><i class="fas fa-user-alt-slash"></i> Data Karyawan Non Aktif</a></li>
							<li><a onclick="exit_view('all')" href="#exit_view" data-toggle="tab"><i class="fa fa-edit"></i> Data Exit Interview</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="non_aktif">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
									<div class="box-tools pull-right">
										<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
										<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
										<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
									</div>
								</div>
								<div style="padding-top: 1px;">
									<form id="form_filter">
										<input type="hidden" name="param" value="all">
										<input type="hidden" name="mode" value="data">
										<div class="box-body">
											<div class="col-md-6">
												<div class="form-group">
													<label>Pilih Bagian</label>
													<select class="form-control select2" id="bagian_export" name="bagian_export" style="width: 100%;"></select>
												</div>
												<div class="form-group">
													<label>Pilih Lokasi Kerja</label>
													<select class="form-control select2" id="unit_export" name="unit_export" style="width: 100%;"></select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Bulan</label>
													<select class="form-control select2" id="bulan_export" name="bulan_export" style="width: 100%;">
														<option></option>
														<?php
														for ($i=1; $i <= 12; $i++) { 
															echo '<option value="'.$this->formatter->zeroPadding($i).'" '.$select.'>'.$this->formatter->getNameOfMonth($i).'</option>';
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Tahun</label>
													<select class="form-control select2" id="tahun_export" name="tahun_export" style="width: 100%;">
														<option></option>
														<?php
														$year = $this->formatter->getYear();
														foreach ($year as $yk => $yv) {
															echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>';
														}
														?>
													</select>
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
								<div class="row">
									<div class="col-md-12">
										<!-- <div class="box"> -->
											<!-- <div class="box-header with-border">
												<h3 class="box-title"><i class="fas fa-user-alt-slash"></i> Data Karyawan Non Aktif</h3>
												<div class="box-tools pull-right">
													<button class="btn btn-box-tool" onclick="tableData('all');" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
													<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
													<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
												</div>
											</div> -->
											<div class="box-body">
												<div class="row">
													<div class="col-md-12"><hr>
														<div class="box-header with-border">
															<h3 class="box-title"><i class="fas fa-user-alt-slash"></i> Data Karyawan Non Aktif</h3>
															<div class="box-tools pull-right">
																<button class="btn btn-box-tool" onclick="tableData('all');" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
																<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
																<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
															</div>
														</div>
														<br>
														<?php 
															if (in_array($access['l_ac']['rkp'], $access['access'])) {
																echo '<button type="button" onclick="rekap_data()" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button> ';
															}
														?>
														<br>
														<br>
														<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail Karyawan Non Aktif</div>
														<table id="table_data" class="table table-bordered table-striped" width="100%">
															<thead>
																<tr>
																	<th>No</th>
																	<th>NIK</th>
																	<th>Nama</th>
																	<th>Jabatan</th>
																	<th>Lokasi</th>
																	<th>Tanggal Masuk</th>
																	<th>Tanggal Keluar</th>
																	<th>Jumlah</th>
																	<th>Aksi</th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										<!-- </div> -->
									</div>
								</div>
							</div>
							<div class="tab-pane" id="exit_view">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data Exit Interview</h3>
									<div class="box-tools pull-right">
										<button class="btn btn-box-tool" onclick="exit_view('all')" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
										<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
										<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
									</div>
								</div>
								<div style="padding-top: 10px;">
									<form id="form_filter">
										<input type="hidden" name="param_exit" value="all">
										<input type="hidden" name="mode_exit" value="data">
										<div class="box-body">
											<div class="col-md-6">
												<div class="form-group">
													<label>Pilih Bagian</label>
													<select class="form-control select2" id="bagian_exit" name="bagian_exit" style="width: 100%;"></select>
												</div>
												<div class="form-group">
													<label>Pilih Lokasi Kerja</label>
													<select class="form-control select2" id="unit_exit" name="unit_exit" style="width: 100%;"></select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Bulan</label>
													<select class="form-control select2" id="bulan_exit" name="bulan_exit" style="width: 100%;">
														<option></option>
														<?php
														for ($i=1; $i <= 12; $i++) { 
															echo '<option value="'.$this->formatter->zeroPadding($i).'" '.$select.'>'.$this->formatter->getNameOfMonth($i).'</option>';
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Tahun</label>
													<select class="form-control select2" id="tahun_exit" name="tahun_exit" style="width: 100%;">
														<option></option>
														<?php
														$year = $this->formatter->getYear();
														foreach ($year as $yk => $yv) {
															echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>';
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="box-footer">
											<div class="col-md-12">
												<div class="pull-right">
													<button type="button" onclick="tableDataExit('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fa fa-edit"></i> Data Form Exit Interview</h3>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="col-md-12">
													<div class="box-body">
														<div class="row">
															<div class="col-md-12">
																<div class="pull-left">
																	<?php if (in_array($access['l_ac']['add'], $access['access'])) {
																	echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Form Exit Interview</button>';
																	}?>
																</div>
															</div>
															<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
															<div class="collapse" id="add">
																<br> <br><br>
																<form id="form_add" class="form-horizontal">
																	<div class="row">
																		<div class="col-md-12">
																			<div class="col-md-6">
																				<div class="form-group">
																					<input type="hidden" name="id_karyawan" id="id_karyawan">
																					<label class="col-sm-3 control-label">NIK</label>
																					<div class="col-sm-7">
																						<input type="text" name="nik" id="nik" class="form-control" placeholder="Nomor Induk Karyawan" required="required" readonly="readonly">
																					</div>
																					<div class="col-sm-1">
																						<button type="button" class="btn btn-default btn-sm" onclick="pilih_karyawan()">
																						<i class ="fa fa-plus"></i></button>
																					</div>
																				</div>
																				<div class="form-group">
																					<label class="col-sm-3 control-label">Nama Karyawan</label>
																					<div class="col-sm-9">
																						<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Karyawan" readonly="readonly">
																					</div>
																				</div>
																				<div class="form-group">
																					<label class="col-sm-3 control-label">Jabatan</label>
																					<input type="hidden" name="jabatan" id="jabatan" class="form-control" placeholder="Kode Jabatan" readonly>
																					<div class="col-sm-9">
																						<input type="text" name="nama_jabatan" id="nama_jabatan" class="form-control" placeholder="Jabatan Karyawan" readonly="readonly">
																					</div>
																				</div>
																				<div class="form-group">
																					<label class="col-sm-3 control-label">Lokasi</label>
																					<input type="hidden" name="lokasi_asal" id="kode_lokasi" class="form-control" placeholder="Kode Lokasi" readonly>
																					<div class="col-sm-9">
																						<input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control" placeholder="Lokasi Karyawan" required="required" readonly="readonly">
																					</div>
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label class="col-sm-3 control-label">Tanggal Masuk</label>
																					<div class="col-sm-9">
																						<div class="has-feedback">
																							<span class="fa fa-calendar form-control-feedback"></span>
																							<input type="text" name="tgl_masuk" id="tgl_masuk" class="form-control pull-right" placeholder="Tanggal Masuk" readonly="readonly">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<label class="col-sm-3 control-label">Tanggal Keluar</label>
																					<div class="col-sm-9">
																						<div class="has-feedback">
																							<span class="fa fa-calendar form-control-feedback"></span>
																							<input type="text" name="tgl_keluar" class="form-control pull-right date-picker" placeholder="Tanggal Keluar" readonly="readonly" required="required">
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<label class="col-sm-3 control-label">Alasan Keluar</label>
																					<div class="col-sm-9">
																						<select class="form-control select2" name="alasan_keluar" id="data_alasan_add" required="required" style="width: 100%;"></select>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																		<h3 class="text-danger">Tentang Anda</h3>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?</label>
																				<div class="col-sm-9">
																					<textarea name="setelah" class="form-control" placeholder="Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?</label>
																				<div class="col-sm-9">
																					<textarea name="posisi" class="form-control" placeholder="Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?</label>
																				<div class="col-sm-9">
																					<textarea name="tertarik" class="form-control" placeholder="Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?</label>
																				<div class="col-sm-9">
																					<textarea name="kompensasi" class="form-control" placeholder="Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																		<h3 class="text-danger">Tentang Perusahaan</h3>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Bagaimana penilaian anda tentang sistem kerja di perusahaan ini ?</label>
																				<div class="col-sm-9">
																				<?php
																					foreach ($radio as $rad => $r) {
																						echo '<input type="radio" name="penilaian" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
																					}
																				?><br><br>
																					<textarea name="alasan" class="form-control" placeholder="Alasan" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?</label>
																				<div class="col-sm-9">
																					<textarea name="lingkungan" class="form-control" placeholder="Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?</label>
																				<div class="col-sm-9">
																					<textarea name="support" class="form-control" placeholder="Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?</label>
																				<div class="col-sm-9">
																					<textarea name="pelatihan" class="form-control" placeholder="Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?</label>
																				<div class="col-sm-9">
																					<textarea name="saran" class="form-control" placeholder="Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?" required="required"></textarea>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-11">
																			<div class="form-group">
																				<button type="submit" id="simpan" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
																			</div>
																		</div>
																	</div>
																</form>
															</div>
														<?php } ?>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="box-body">
														<!-- <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail Form Exit Interview Karyawan</div> -->
														<table id="table_data_exit" class="table table-bordered table-striped" width="100%">
															<thead>
																<tr>
																	<th>No</th>
																	<th>NIK</th>
																	<th>Nama</th>
																	<th>Jabatan</th>
																	<th>Lokasi</th>
																	<th>Tanggal Masuk</th>
																	<th>Tanggal Keluar</th>
																	<th>Alasan</th>
																	<th>Tanggal</th>
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
		</div>
	</section>	
</div>
<!-- MODAL PILIH KARYAWAN -->
<div class="modal modal-default fade" id="modal_pilih_karyawan" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h2 class="modal-title">Pilih Karyawan</h2>
		    </div>
			<div class="modal-body">
					<table id="table_pilih" class="table table-bordered table-striped table-responsive" width="100%">
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

<!-- view -->
<div id="view" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
				<input type="hidden" id="data_id_karyawan_view" name="data_id_karyawan_view">
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
							<label class="col-md-6 control-label">Status Karyawan</label>
							<div class="col-md-6" id="data_status_karyawan_view"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<p class="text-center">Data Karyawan Non Aktif</p>
						<div id="data_tabel_view">
							<table id="table_view" class="table table-bordered" width="100%">
								<thead>
									<tr>
										<th class="nowrap">No.</th>
										<th class="nowrap">Nama</th>
										<th class="nowrap">Tanggal Masuk</th>
										<th class="nowrap">Tanggal Keluar</th>
										<th class="nowrap">Alasan Keluar</th>
										<th class="nowrap">Mengetahui</th>
										<th class="nowrap">Menyetujui</th>
										<th class="nowrap">Aksi</th>
									</tr>
								</thead>
								<tbody id="body_view"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="buttom" class="btn btn-success" onclick="aktifkan()"><i class="fa fa-user"></i> Aktifkan Karyawan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="view_exit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_exit">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi</label>
							<div class="col-md-6" id="data_loker_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alasan Keluar</label>
							<div class="col-md-6" id="data_alasan_keluar_exit"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_exit">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_exit">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Karyawan</label>
							<div class="col-md-6" id="data_status_karyawan_exit"></div>
						</div>
					</div>
				</div><hr>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped">
							<tr>
								<th width="40%">Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?</th>
								<td id="data_setelah_exit"></td>
							</tr>
							<tr>
								<th width="40%">Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?</th>
								<td id="data_posisi_exit"></td>
							</tr>
							<tr>
								<th width="40%">Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?</th>
								<td id="data_tertarik_exit"></td>
							</tr>
							<tr>
								<th width="40%">Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?</th>
								<td id="data_kompensasi_exit"></td>
							</tr>
							<tr>
								<th width="40%">Bagaimana penilaian anda tentang sistem kerja di perusahaan ini ?</th>
								<td>
									<table>
										<tr>
											<th width="30%">Penilaian</th>
											<td id="data_penilaian_exit"></td>
										</tr>
										<tr>
											<th width="30%">Alasan</th>
											<td id="data_alasan_exit"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<th width="40%">Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?</th>
								<td id="data_lingkungan_exit"></td>
							</tr>
							<tr>
								<th width="40%">Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?</th>
								<td id="data_support_exit"></td>
							</tr>
							<tr>
								<th width="40%">Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?</th>
								<td id="data_pelatihan_exit"></td>
							</tr>
							<tr>
								<th width="40%">Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?</th>
								<td id="data_saran_exit"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal_exit()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="edit_exit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit_exit">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-6">
							<input type="hidden" name="id" id="id_exit" value="">
							<input type="hidden" id="idk_exit" name="id_karyawan" value="">
							<div class="form-group clearfix">
								<label>NIK</label>
								<input type="text" id="nik_exit_edit" name="nik" value="" class="form-control" readonly="readonly">
							</div>
							<div class="form-group clearfix">
								<label>Nama</label>
								<input type="text" id="edit_nama_exit" name="nama" value="" class="form-control" readonly="readonly">
							</div>
							<div class="form-group clearfix">
								<label>Lokasi</label>
								<input type="text" id="edit_loker_exit" name="lokasi" value="" class="form-control" readonly="readonly">
							</div>
							<div class="form-group clearfix">
								<label>Jabatan</label>
								<input type="text" id="edit_jabatan_exit" name="jabatan" value="" class="form-control" readonly="readonly">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label>Alasan Keluar</label>
								<select class="form-control select2" name="alasan_keluar" id="edit_alasan_keluar_exit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Tanggal Masuk</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" id="edit_tgl_masuk_exit" value="" name="tgl_masuk" class="form-control pull-right" placeholder="Tanggal Masuk" readonly="readonly">
								</div>
							</div>
							<div class="form-group clearfix">
								<label>Tanggal Keluar</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" id="edit_tgl_keluar_exit" value="" name="tgl_keluar" class="form-control pull-right date-picker" placeholder="Tanggal Keluar" readonly="readonly">
								</div>
							</div>
						</div>
					</div>
				</div><hr>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped">
							<tr>
								<th width="40%">Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?</th>
								<td><textarea name="setelah" id="edit_setelah_exit" value="" class="form-control" placeholder="Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?</th>
								<td><textarea name="posisi" id="edit_posisi_exit" value="" class="form-control" placeholder="Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?</th>
								<td><textarea name="tertarik" id="edit_tertarik_exit" value="" class="form-control" placeholder="Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?</th>
								<td><textarea name="kompensasi" id="edit_kompensasi_exit" value="" class="form-control" placeholder="Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Bagaimana penilaian anda tentang sistem kerja di perusahaan ini ?</th>
								<td>
									<table>
										<tr>
											<th width="12%">Penilaian</th>
											<td>
												<?php
													foreach ($radio as $rad => $r) {
														echo '<input type="radio" name="penilaian" id="edit_penilaian_exit" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
													}
												?>
											</td>
										</tr>
										<tr>
											<th width="12%">Alasan</th>
											<td><textarea name="alasan" id="edit_alasan_exit" value="" class="form-control" placeholder="Alasan ?" required="required"></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<th width="40%">Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?</th>
								<td><textarea name="lingkungan" id="edit_lingkungan_exit" value="" class="form-control" placeholder="Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?</th>
								<td><textarea name="support" id="edit_support_exit" value="" class="form-control" placeholder="Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?</th>
								<td><textarea name="pelatihan" id="edit_pelatihan_exit" value="" class="form-control" placeholder="Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?</th>
								<td><textarea name="saran" id="edit_saran_exit" value="" class="form-control" placeholder="Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?" required="required"></textarea></td>
							</tr>
						</table>
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
<!-- delete exit interview -->
<div id="delete_exit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_exit">
				<div class="modal-body text-center">
					<input type="hidden" id="data_id_delete_exit" name="id">
					<input type="hidden" name="column" value="id_exit">
					<input type="hidden" name="table" value="data_exit_interview">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_exit()" class="btn btn-primary"><i class="fa fa-trash"></i>
					Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit -->
<div id="aktifkan" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Aktif Data Karyawan <b class="text-muted header_data"></b></h2>
      </div>
      <div class="modal-body">
        <form id="form_aktif">
          <input type="hidden" id="data_id_karyawan_aktif" name="id_karyawan" value="">
          <div class="form-group">
            <label>NIK</label>
            <input type="text" placeholder="NIK" id="data_nik_aktif" name="nik" value="" class="form-control" required="required" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Nama Karyawan</label>
            <input type="text" placeholder="Nama Karyawan" id="data_nama_aktif" name="nama" value="" class="form-control" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Jabatan</label>
            <input type="text" placeholder="Nama Jabatan" id="data_jabatan_aktif" name="nama" value="" class="form-control" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Lokasi Kerja</label>
            <input type="text" placeholder="Nama Lokasi Kerja" id="data_loker_aktif" name="nama" value="" class="form-control" readonly="readonly">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="do_aktifkan()" id="btn_aktif" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
	var table="data_karyawan_tidak_aktif";
	var column="id_kta";
	$(document).ready(function(){
		tableData('all');
		submitForm('form_add');
		submitForm('form_edit_exit');
		refreshData();
		select_data('data_alasan_add',url_select,'master_alasan_keluar','kode_alasan_keluar','nama','placeholder');
	});
	function refreshData() {
      	getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
      	getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_exit');
		select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export',['BAG001','BAG002']);
		unsetoption('bagian_exit',['BAG001','BAG002']);
	}
	function resetselectAddExit() {
		select_data('data_alasan_add',url_select,'master_alasan_keluar','kode_alasan_keluar','nama','placeholder');
	}
	function non_aktif() {
		tableData('all');
	}
	function tableData(kode) {
		$('input[name="param"').val(kode);
		$('#table_data').DataTable().destroy();
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
				url: "<?php echo base_url('employee/karyawan_tidak_aktif/view_all/')?>",
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
					return data;
				}
			},
			{   targets: 8, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function exit_view(kode) {
		$('input[name="param_exit"').val(kode);
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_exit').val();
			var unit = $('#unit_exit').val();
			var bulan = $('#bulan_exit').val();
			var tahun = $('#tahun_exit').val();
			var datax = {param:'search',bagian:bagian,unit:unit,bulan:bulan,tahun:tahun,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data_exit').DataTable().destroy();
		$('#table_data_exit').DataTable( {
			ajax: {
				url: "<?php echo base_url('employee/karyawan_exit_interview/view_all/')?>",
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
					return data;
				}
			},
			{   targets: 9, 
				width: '11%',
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
			ajax: "<?php echo base_url('employee/pilih_k_nonaktif')?>",
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 1,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 2,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 3,
				render: function ( data, type, full, meta ) {
					return data;
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
	$(document).on('click', '.pilih', function (e1) {
		$("#id_karyawan").val($(this).data('id_karyawan'));
		$("#nik").val($(this).data('nik'));
		$("#nama").val($(this).data('nama'));
		$("#jabatan").val($(this).data('jabatan'));
		$("#nama_jabatan").val($(this).data('nama_jabatan'));
		$("#kode_lokasi").val($(this).data('kode_lokasi'));
		$("#nama_lokasi").val($(this).data('nama_lokasi'));
		$("#tgl_masuk").val($(this).data('tgl_masuk'));
		$('#modal_pilih_karyawan').modal('hide');
	});
	function view_modal(id) {
		var data={id_kta:id};
		var callback=getAjaxData("<?php echo base_url('employee/karyawan_tidak_aktif/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_id_karyawan_view').val(callback['id_karyawan']);
		$('#data_nosk_view').html(callback['no_sk']);
		$('#data_tglsk_view').html(callback['tgl_sk']);
		$('#data_tglberlaku_view').html(callback['tgl_berlaku']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_tgl_masuk_view').html(callback['tgl_masuk']);
		$('#data_tgl_keluar_view').html(callback['tgl_keluar']);
		$('#data_alasan_view').html(callback['alasan']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
		$('#data_keterangan_view').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		var status_k = callback['status_karyawan'];
		if(status_k==1){
			var statusKar = '<b class="text-success">Aktif</b>';
		}else if(status_k==2){
			var statusKar = '<b class="text-danger">Karyawan Harus Dinonaktifkan</b>';
		}else{
			var statusKar = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_karyawan_view').html(statusKar);
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
		$('#table_view').DataTable().destroy();
		var datatab = {id_karyawan:callback['id_karyawan'],access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		var calltable=getAjaxData("<?php echo base_url('employee/karyawan_tidak_aktif/tabel_view')?>",datatab);  
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
	function view_modal_exit(id) {
		var data={id_exit:id};
		var callback=getAjaxData("<?php echo base_url('employee/karyawan_exit_interview/view_one')?>",data);  
		$('#view_exit').modal('show');
		$('.header_data').html(callback['nama']);
		// $('#data_nosk_exit').html(callback['no_sk']);
		// $('#data_tglsk_exit').html(callback['tgl_sk']);
		// $('#data_tglberlaku_exit').html(callback['tgl_berlaku']);
		$('#data_nik_exit').html(callback['nik']);
		$('#data_nama_exit').html(callback['nama']);
		$('#data_loker_exit').html(callback['loker']);
		$('#data_jabatan_exit').html(callback['jabatan']);
		$('#data_tgl_masuk_exit').html(callback['tgl_masuk']);
		$('#data_tgl_keluar_exit').html(callback['tgl_keluar']);
		$('#data_alasan_keluar_exit').html(callback['alasan_keluar']);
		$('#data_setelah_exit').html(callback['setelah']);
		$('#data_posisi_exit').html(callback['posisi']);
		$('#data_tertarik_exit').html(callback['tertarik']);
		$('#data_kompensasi_exit').html(callback['kompensasi']);
		$('#data_penilaian_exit').html(callback['penilaian']);
		$('#data_alasan_exit').html(callback['alasan']);
		$('#data_lingkungan_exit').html(callback['lingkungan']);
		$('#data_support_exit').html(callback['support']);
		$('#data_pelatihan_exit').html(callback['pelatihan']);
		$('#data_saran_exit').html(callback['saran']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_exit').html(statusval);
		var status_k = callback['status_karyawan'];
		if(status_k==1){
			var statusKar = '<b class="text-success">Aktif</b>';
		}else if(status_k==2){
			var statusKar = '<b class="text-danger">Karyawan Harus Dinonaktifkan</b>';
		}else{
			var statusKar = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_karyawan_exit').html(statusKar);
		$('#data_create_date_exit').html(callback['create_date']+' WIB');
		$('#data_update_date_exit').html(callback['update_date']+' WIB');
		$('input[name="data_id_exit"]').val(callback['id']);
		$('#data_create_by_exit').html(callback['nama_buat']);
		$('#data_update_by_exit').html(callback['nama_update']);
	}
	function edit_modal_exit() {
		select_data('edit_alasan_keluar_exit',url_select,'master_alasan_keluar','kode_alasan_keluar','nama','placeholder');
		var id = $('input[name="data_id_exit"]').val();
		var data={id_exit:id};
		var callback=getAjaxData("<?php echo base_url('employee/karyawan_exit_interview/view_one')?>",data);
		$('#view_exit').modal('toggle');
		setTimeout(function () {
			$('#edit_exit').modal('show');
		}, 600);
		$('.header_data').html(callback['nama']);
		$('#id_exit').val(callback['id']);
		$('#idk_exit').val(callback['id_karyawan']);
		$('#nik_exit_edit').val(callback['nik']);
		$('#edit_nama_exit').val(callback['nama']);
		$('#edit_loker_exit').val(callback['loker']);
		$('#edit_jabatan_exit').val(callback['jabatan']);
		$('#edit_tgl_keluar_exit').val(callback['etgl_keluar']);
		$('#edit_tgl_masuk_exit').val(callback['etgl_masuk']);
		$('#edit_alasan_keluar_exit').val(callback['alasan_keluar_e']).trigger('change');
		$('#edit_setelah_exit').val(callback['setelah']);
		$('#edit_posisi_exit').val(callback['posisi']);
		$('#edit_tertarik_exit').val(callback['tertarik']);
		$('#edit_kompensasi_exit').val(callback['kompensasi']);
		$('#edit_alasan_exit').val(callback['alasan']);
		$('#edit_lingkungan_exit').val(callback['lingkungan']);
		$('#edit_support_exit').val(callback['support']);
		$('#edit_pelatihan_exit').val(callback['pelatihan']);
		$('#edit_saran_exit').val(callback['saran']);
		var baca=callback['penilaian_e'];
		$('input[id=edit_penilaian_exit][value='+baca+']').iCheck('check');
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				if(form=='form_add'){
					do_add()
				}else{
					do_edit_exit()
				}
			}
		})
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_exit_interview')?>",null,'form_add',null,null);
			$('#table_data_exit').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			resetselectAddExit();
		}else{
			notValidParamx();
		} 
	}
	function do_edit_exit(){
		if($("#form_edit_exit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/edit_exit_interview')?>",'edit_exit','form_edit_exit',null,null);
			$('#table_data_exit').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_edit_exit')[0].reset();
			resetselectAddExit();
		}else{
			notValidParamx();
		} 
	}
	function delete_modal(id) {
		var data={id_kta:id};
		var callback=getAjaxData("<?php echo base_url('employee/karyawan_tidak_aktif/view_one')?>",data);
		var datax={table:table,column:column,id:callback['id_karyawan'],nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function delete_modal_one(id) {
		var data={id_kta:id};
		var callback=getAjaxData("<?php echo base_url('employee/karyawan_tidak_aktif/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_kta:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function do_print(id) {
		window.location.href = "<?php echo base_url('cetak_word/cetak_kta/')?>"+id;
	}
	function rekap_data() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/view_karyawan_non_aktif')?>?"+data;
	}
	function do_print_exit(id) {
		window.location.href = "<?php echo base_url('cetak_word/cetak_exit_interview/')?>"+id;
	}
	function delete_modal_exit(id) {
		var data={id_exit:id};
		var callback=getAjaxData("<?php echo base_url('employee/karyawan_exit_interview/view_one')?>",data);
		$('#delete_exit').modal('show');
		$('#data_name_delete').html(callback['nama']);
		$('#data_id_delete_exit').val(id);
	}
	function do_delete_exit(){
    	submitAjax("<?php echo base_url('global_control/delete')?>",'delete_exit','form_delete_exit',null,null);
		$('#table_data_exit').DataTable().ajax.reload(function(){
			Pace.restart();
		});
	}
	function aktifkan() {
		var id = $('input[name="data_id_karyawan_view"]').val();
		var data={id_karyawan:id};
		var callback=getAjaxData("<?php echo base_url('employee/karyawan_tidak_aktif/view_kar')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
		$('#aktifkan').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_karyawan_aktif').val(callback['id_karyawan']);
		$('#data_nik_aktif').val(callback['nik']);
		$('#data_nama_aktif').val(callback['nama']);
		$('#data_jabatan_aktif').val(callback['jabatan']);
		$('#data_loker_aktif').val(callback['loker']);
	}
	function do_aktifkan(){
		if($("#form_aktif")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/edit_status_emp')?>",null,'form_aktif',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_aktif')[0].reset();
		}else{
			notValidParamx();
		} 
	}
</script> 
