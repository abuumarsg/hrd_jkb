<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Penggajian Harian
			<small>Data penggajian Harian</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard'); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active">Data Penggajian Harian</li>
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
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="">
											<label>Pilih Lokasi</label>
											<select class="form-control select2" id="lokasi_kerja_ser" name="lokasi" style="width: 100%;"></select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="">
											<label>Pilih Bagian</label>
											<select class="form-control select2" name="bagian" id="bagian_ser" style="width: 100%;"></select>
										</div>
									</div>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-4">
										<div class="form-group">
											<label>Minggu</label>
											<?php
											$minggu_ser = $this->otherfunctions->listWeek();
											$selm_ser = array();
											$exm_ser = array('class' => 'form-control select2', 'id' => 'minggu_ser', 'style' => 'width:100%;', 'required' => 'required');
											echo form_dropdown('minggu', $minggu_ser, $selm_ser, $exm_ser);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Bulan</label>
											<?php
											$bulan_ser = $this->formatter->getMonth();
											$sel_ser = array(date('m'));
											$ex_ser = array('class' => 'form-control select2', 'id' => 'bulan_ser', 'style' => 'width:100%;', 'required' => 'required');
											echo form_dropdown('bulan', $bulan_ser, $sel_ser, $ex_ser);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Tahun</label>
											<?php
											$tahun_ser = $this->formatter->getYear();
											$sels_ser = array(date('Y'));
											$exs_ser = array('class' => 'form-control select2', 'id' => 'tahun_ser', 'style' => 'width:100%;', 'required' => 'required');
											echo form_dropdown('tahun', $tahun_ser, $sels_ser, $exs_ser);
											?>
										</div>
									</div>
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
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data Penggajian Harian</h3>
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
													<!-- <?php
															// if (in_array($access['l_ac']['add'], $access['access'])) {
															// 	echo '<button id="btn_tambah" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Tambah Data</button>';
															// 	echo '<button class="btn btn-primary" onclick="log_confirm()" style="margin-left: 5px;float: left;"><i class="fas fa-check"></i> Selesai Payroll</button>';
															// }
															?>
													<div class="dropdown" style="float: left;margin-left: 5px;">
														<button class="btn btn-warning dropdown-toggle" type="button"
															data-toggle="dropdown"><i class="fa fa-print fa-fw"></i>
															Cetak
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a onclick="print_slip()">Slip Gaji</a></li>
															<li><a onclick="rekap_gaji()">Rekap</a></li>
														</ul>
													</div> 
														<button id="btn_tambah" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Tambah Data</button>-->
													<?php
													if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '
														<button class="btn btn-success" href="#add" data-toggle="collapse" id="btn_tambahx" data-parent="#accordion" style="margin-left: 5px;float: left;"><i class="fas fa-plus"></i> Tambah Data</button>';
														echo '<button class="btn btn-primary" onclick="log_confirm()" style="margin-left: 5px;float: left;"><i class="fas fa-check"></i> Selesai Payroll</button>';
													}
													?>
													<div class="dropdown" style="float: left;margin-left: 5px;">
														<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-print fa-fw"></i> Cetak <span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a onclick="do_print_slip()">Slip Gaji</a></li>
															<li><a onclick="rekap_gaji()">Rekap Gaji</a></li>
															<li><a onclick="rekap_bagian()">Rekapitulasi Gaji Bagian</a></li>
															<li><a onclick="rekap_tanda_terima()">Cetak Tanda Terima</a></li>
														</ul>
													</div>
													<button class="btn btn-info" href="#sync" data-toggle="collapse" id="btn_tambahu" data-parent="#accordion" style="margin-left: 5px;float: left;"><i class="fas fa-refresh"></i> Sync Data</button>
													<button class="btn btn-danger" href="#data_lain" onclick="insertDataLain()" data-toggle="collapse" data-parent="#accordion" style="margin-left: 5px;float: left;"><i class="fas fa-share-square"></i> Insert Data Lain</button>
												</div>
												<div class="pull-right" style="font-size: 8pt;">
												</div>
											</div>
										</div>
										<div id="add" class="collapse"><br>
											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title"><i class="fas fa-plus"></i> Tambah Data Penggajian Harian</h3>
												</div>
												<form id="form_add" class="form-horizontal">
												<div class="box-body">
													<div class="row">
														<div class="col-md-12">
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<input type="hidden" name="usage" value="insert">
																<div class="form-group">
																	<label>Bagian</label>
																	<select class="form-control select2" id="bagian_add" name="bagian" style="width: 100%;"></select>
																</div>
																<div class="form-group">
																	<label>Lokasi Kerja</label>
																	<select class="form-control select2" id="lokasi_kerja_add" name="lokasi" style="width: 100%;"></select>
																</div>
																<div class="form-group">
																	<label>Tanggal Mulai - Selesai</label>
																	<input type="text" name="tanggal" id="tanggal_payroll_add" class="form-control date-range-notime" placeholder="Tanggal Penggajian" required="required" readonly="readonly">
																</div>
																<div class="form-group">
																	<label>Minggu</label>
																	<?php
																	$minggu_add = $this->otherfunctions->listWeek();
																	$selm_add = array();
																	$exm_add = array('class' => 'form-control select2', 'id' => 'minggu_add', 'style' => 'width:100%;', 'required' => 'required');
																	echo form_dropdown('minggu', $minggu_add, $selm_add, $exm_add);
																	?>
																</div>
																<div class="form-group">
																	<label>Bulan</label>
																	<?php
																	$bulan_add = $this->formatter->getMonth();
																	$sel_add = array(date('m'));
																	$ex_add = array('class' => 'form-control select2', 'id' => 'bulan_add', 'style' => 'width:100%;', 'required' => 'required');
																	echo form_dropdown('bulan', $bulan_add, $sel_add, $ex_add);
																	?>
																</div>
																<div class="form-group">
																	<label>Tahun</label>
																	<?php
																	$tahun_add = $this->formatter->getYear();
																	$sels_add = array(date('Y'));
																	$exs_add = array('class' => 'form-control select2', 'id' => 'tahun_add', 'style' => 'width:100%;', 'required' => 'required');
																	echo form_dropdown('tahun', $tahun_add, $sels_add, $exs_add);
																	?>
																</div>
																<hr>
																<div style="text-align: center;"><label>Data Pendukung</label></div>
																<div class="row">
																	<div class="col-md-6">
																		<input type="checkbox" class="check icheck-class" id="bpjs_add"> Data BPJS<br>
																	</div>
																	<input type="hidden" name="bpjs" id="bpjs_hidden" value="">
																	<div class="col-md-6">
																		<input type="checkbox" class="check icheck-class" id="data_lain_add"> Lain-Lain<br>
																	</div>
																	<input type="hidden" name="data_lain" id="data_lain_hidden" value="">
																</div>
															</div>
														</div>
													</div>
												</div><hr>
												<div class="box-footer">
													<div id="progressAdd" style="float: left;"></div>
													<div class="pull-right">
														<button type="button" onclick="do_add()" id="btn_edit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
													</div>
												</div>
												</form>
											</div>
										</div>
										<div id="sync" class="collapse"><br>
											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title"><i class="fas fa-refresh"></i> Syncronize Data Penggajian Harian</h3>
												</div>
												<form id="form_sync" class="form-horizontal">
													<div class="box-body">
														<input type="hidden" name="usage" value="data">
														<div class="row">
															<div class="col-md-12">
																<div class="col-md-2"></div>
																<div class="col-md-8">
																	<div class="form-group">
																		<label>Bagian</label>
																		<select class="form-control select2" id="bagian_sync" name="bagian" style="width: 100%;"></select>
																	</div>
																	<div class="form-group">
																		<label>Lokasi Kerja</label>
																		<select class="form-control select2" id="lokasi_kerja_sync" name="lokasi" style="width: 100%;"></select>
																	</div>
																	<div class="form-group">
																		<label>Minggu</label>
																		<?php
																		$minggu_sync = $this->otherfunctions->listWeek();
																		$selm_sync = array();
																		$exm_sync = array('class' => 'form-control select2', 'id' => 'minggu_sync', 'style' => 'width:100%;', 'required' => 'required');
																		echo form_dropdown('minggu', $minggu_sync, $selm_sync, $exm_sync);
																		?>
																	</div>
																	<div class="form-group">
																		<label>Bulan</label>
																		<?php
																		$bulan_sync = $this->formatter->getMonth();
																		$sel_sync = array(date('m'));
																		$ex_sync = array('class' => 'form-control select2', 'id' => 'bulan_sync', 'style' => 'width:100%;', 'required' => 'required');
																		echo form_dropdown('bulan', $bulan_sync, $sel_sync, $ex_sync);
																		?>
																	</div>
																	<div class="form-group">
																		<label>Tahun</label>
																		<?php
																		$tahun_sync = $this->formatter->getYear();
																		$sels_sync = array(date('Y'));
																		$exs_sync = array('class' => 'form-control select2', 'id' => 'tahun_sync', 'style' => 'width:100%;', 'required' => 'required');
																		echo form_dropdown('tahun', $tahun_sync, $sels_sync, $exs_sync);
																		?>
																	</div>
																	<div class="form-group">
																		<label>Pilih Karyawan</label>
																		<select class="form-control select2" name="karyawan[]" id="sync_karyawan" multiple="multiple" style="width: 100%;"></select>
																	</div>
																	<input type="checkbox" class="check icheck-class" id="bpjs_sync"> Data BPJS<br>
																	<input type="hidden" name="bpjs" id="bpjs_hidden_sync" value="bpjs">
																	<input type="checkbox" class="check icheck-class" id="data_lain_sync"> Data Lain<br>
																	<input type="hidden" name="data_lain" id="data_lain_hidden_sync" value="data_lain">
																</div>
															</div>
														</div>
													</div>
													<div class="box-footer">
														<div id="loading_progress_sync" style="float: left;"></div>
														<div class="pull-right">
															<button type="button" onclick="do_sync_data()" class="btn btn-success"><i class="fas fa-refresh"></i> Sync Data</button>
														</div>
													</div>
												</form>
											</div>
										</div>
										<div id="data_lain" class="collapse"><br>
											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title"><i class="fa fa-edit fa-fw"></i> Insert Data Pendukung Lain</h3>
												</div>
												<form id="form_data_lain" class="form-horizontal">
													<div class="box-body"><div class="col-md-12">
														<div class="col-md-12">
															<div class="col-md-6">
																<div class="form-group">
																	<label class="col-sm-3 control-label">Bagian</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" id="bagian_ins" name="bagian" style="width: 100%;"></select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Lokasi Kerja</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" id="lokasi_kerja_ins" name="lokasi" style="width: 100%;"></select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Minggu</label>
																	<div class="col-sm-9">
																		<?php
																			$minggu_ins = $this->otherfunctions->listWeek();
																			$selm_ins = array();
																			$exm_ins = array('class' => 'form-control select2', 'id' => 'minggu_ins', 'style' => 'width:100%;', 'required' => 'required');
																			echo form_dropdown('minggu', $minggu_ins, $selm_ins, $exm_ins);
																		?>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label class="col-sm-3 control-label">Bulan</label>
																	<div class="col-sm-9">
																		<?php
																			$bulan_ins = $this->formatter->getMonth();
																			$sel_ins = array(date('m'));
																			$ex_ins = array('class' => 'form-control select2', 'id' => 'bulan_ins', 'style' => 'width:100%;', 'required' => 'required');
																			echo form_dropdown('bulan', $bulan_ins, $sel_ins, $ex_ins);
																		?>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Tahun</label>
																	<div class="col-sm-9">
																	<?php
																		$tahun_ins = $this->formatter->getYear();
																		$sels_ins = array(date('Y'));
																		$exs_ins = array('class' => 'form-control select2', 'id' => 'tahun_ins', 'style' => 'width:100%;', 'required' => 'required');
																		echo form_dropdown('tahun', $tahun_ins, $sels_ins, $exs_ins);
																	?>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Pilih Karyawan</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="karyawan[]" id="karyawan_ins" multiple="multiple" style="width: 100%;"></select>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div id="tabel_end_proses"> </div>
															<br>
															<button type="button" class="btn btn-success" onclick="myFunction()"><i class="fa fa-plus"></i> Add</button>
															<button type="button" class="btn btn-danger" onclick="deleterow()"><i class="fa fa-trash"></i> Delete</button>
														</div>
													</div>
													</div>
													<div class="box-footer">
														<div id="loading_progress_ins" style="float: left;"></div>
														<div class="pull-right">
															<button type="button" onclick="do_insert_lainnya()" class="btn btn-success"><i class="fa fa-edit fa-fw"></i> Simpan</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
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
						<div class="row">
							<div class="col-md-12">
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>NIK Karyawan</th>
											<th>Nama Karyawan</th>
											<th>Jabatan</th>
											<th>Grade</th>
											<th>Tanggal Masuk</th>
											<th>Masa Kerja</th>
											<th>Gaji Pokok (Rp)</th>
											<th>Presensi</th>
											<th>Lainnya</th>
											<th>Nominal Lainnya (Rp)</th>
											<th>Keterangan Lainnya</th>
											<th>Gaji Diterima (Rp)</th>
											<th>Jam Lembur</th>
											<th>Gaji Lembur (Rp)</th>
											<th>Gaji Bersih (Rp)</th>
											<th>Nomor Rekening</th>
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
					<div class="col-md-12">
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
							<!-- <div class="form-group col-md-12">
								<div id="data_pph_view"></div>
							</div> -->
						</div>
					</div>
					<hr>
					<div class="col-md-12">
						<div class="col-md-6">
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
								<label class="col-md-6 control-label">Sistem Penggajian</label>
								<div class="col-md-6" id="data_sistem_view"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Minggu</label>
								<div class="col-md-6" id="data_minggu_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Bulan</label>
								<div class="col-md-6" id="data_bulan_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Tahun</label>
								<div class="col-md-6" id="data_tahun_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Gaji Pokok</label>
								<div class="col-md-6" id="data_gaji_pokok_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Hari Kerja</label>
								<div class="col-md-6" id="data_hari_kerja_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Gaji</label>
								<div class="col-md-6" id="data_gaji_diterima_view"></div>
							</div>
							<div class="form-group col-md-12">
								<div id="data_gaji_bersih_view"></div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div style="text-align: center;">
							<h3>Detail Data</h3>
						</div>
						<div class="col-md-12">
							<div id="data_lembur_view"></div>
						</div>
						<br>
						<div class="col-md-12">
							<div id="data_bpjs_view"></div>
						</div>
						<br>
						<br>
						<div class="col-md-12">
							<div id="data_lainnya_view"></div>
						</div>
					</div>
				</div>
				<!-- <div class="row">
					<div class="col-md-12">
						<div id="data_tabel_view"></div>
					</div>
				</div> -->
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="button" id="btn_edit_view" class="btn btn-primary" onclick="edit_modal()"><i class="fa fa-edit"></i> Penyesuaian Lembur</button>';
				} ?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<div id="log" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Selesai</h4>
			</div>
			<div class="modal-body">
				<form id="form_log">
					<input type="hidden" name="usage" value="pindah">
					<p class="text-center">Yakin Simpan Data?<br>Data Tidak akan bisa di update lagi.</p>
					<div class="form-group">
						<label>Bagian</label>
						<select class="form-control select2" id="bagian_log" name="bagian" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Lokasi Kerja</label>
						<select class="form-control select2" id="lokasi_kerja_log" name="lokasi" style="width: 100%;"></select>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Minggu</label>
								<?php
								$minggu_log = $this->otherfunctions->listWeek();
								$selm_log = array();
								$exm_log = array('class' => 'form-control select2', 'id' => 'minggu_log', 'style' => 'width:100%;', 'required' => 'required');
								echo form_dropdown('minggu', $minggu_log, $selm_log, $exm_log);
								?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Bulan</label>
								<?php
								$bulan_log = $this->formatter->getMonth();
								$sel_log = array(date('m'));
								$ex_log = array('class' => 'form-control select2', 'id' => 'bulan_log', 'style' => 'width:100%;', 'required' => 'required');
								echo form_dropdown('bulan', $bulan_log, $sel_log, $ex_log);
								?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Tahun</label>
								<?php
								$tahun_log = $this->formatter->getYear();
								$sels_log = array(date('Y'));
								$exs_log = array('class' => 'form-control select2', 'id' => 'tahun_log', 'style' => 'width:100%;', 'required' => 'required');
								echo form_dropdown('tahun', $tahun_log, $sels_log, $exs_log);
								?>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div id="progressLog" style="float: left;"></div>
				<button type="button" onclick="do_log()" class="btn btn-primary"><i class="fas fa-check"></i>
					Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<!-- <?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
	<div id="add" class="modal fade" role="dialog">
		<div class="modal-dialog modal-md modal-default">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Tambah Data Penggajian Baru</h2>
				</div>
				<div class="modal-body">
					<form id="form_add">
						<input type="hidden" name="usage" value="insert">
						<div class="form-group">
							<label>Bagian</label>
							<select class="form-control select2" id="bagian_add" name="bagian" style="width: 100%;"></select>
						</div>
						<div class="form-group">
							<label>Lokasi Kerja</label>
							<select class="form-control select2" id="lokasi_kerja_add" name="lokasi" style="width: 100%;"></select>
						</div>
						<div class="form-group">
							<label>Tanggal Mulai - Selesai</label>
							<input type="text" name="tanggal" id="tanggal_payroll_add" class="form-control date-range-notime" placeholder="Tanggal Penggajian" required="required" readonly="readonly">
						</div>
						<div class="form-group">
							<label>Minggu</label>
							<?php
							$minggu_add = $this->otherfunctions->listWeek();
							$selm_add = array();
							$exm_add = array('class' => 'form-control select2', 'id' => 'minggu_add', 'style' => 'width:100%;', 'required' => 'required');
							echo form_dropdown('minggu', $minggu_add, $selm_add, $exm_add);
							?>
						</div>
						<div class="form-group">
							<label>Bulan</label>
							<?php
							$bulan_add = $this->formatter->getMonth();
							$sel_add = array(date('m'));
							$ex_add = array('class' => 'form-control select2', 'id' => 'bulan_add', 'style' => 'width:100%;', 'required' => 'required');
							echo form_dropdown('bulan', $bulan_add, $sel_add, $ex_add);
							?>
						</div>
						<div class="form-group">
							<label>Tahun</label>
							<?php
							$tahun_add = $this->formatter->getYear();
							$sels_add = array(date('Y'));
							$exs_add = array('class' => 'form-control select2', 'id' => 'tahun_add', 'style' => 'width:100%;', 'required' => 'required');
							echo form_dropdown('tahun', $tahun_add, $sels_add, $exs_add);
							?>
						</div>
						<hr>
						<div style="text-align: center;"><label>Data Pendukung</label></div>
						<div class="row">
							<div class="col-md-6">
								<input type="checkbox" class="check icheck-class" id="bpjs_add"> Data BPJS<br>
							</div>
							<input type="hidden" name="bpjs" id="bpjs_hidden" value="bpjs">
							<div class="col-md-6">
								<input type="checkbox" class="check icheck-class" id="data_lain_add"> Lain-Lain<br>
							</div>
							<input type="hidden" name="data_lain" id="data_lain_hidden" value="data_lain">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<div id="progressAdd" style="float: left;"></div>
					<button type="button" onclick="do_add()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
<?php } ?> -->
<div id="confirm_ada_data" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Data Sudah Ada</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_ada_data">
					<input type="hidden" name="bagian" id="bagian_ada_data">
					<input type="hidden" name="lokasi" id="lokasi_ada_data">
					<input type="hidden" name="minggu" id="minggu_ada_data">
					<input type="hidden" name="bulan" id="bulan_ada_data">
					<input type="hidden" name="tahun" id="tahun_ada_data">
					<p>Data Penggajian Pada Periode Tersebut Sudah Ada, <br>Data yg sudah anda buat pada periode tersebut harus dihapus terlebih dahulu.<br>Apakah anda yakin ingin menghapus & membuat ulang?</p>
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
<div id="confirm_ada_data_log" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title text-center"><b>Data Sudah Ada Dalam Log Penggajian</b></h3>
			</div>
			<div class="modal-body text-center">
				<form id="form_ada_data">
					<input type="hidden" name="lokasi" id="lokasi_ada_data">
					<input type="hidden" name="bulan" id="bulan_ada_data">
					<input type="hidden" name="tahun" id="tahun_ada_data">
					<input type="hidden" name="usage" value="update">
					<p>Data Penggajian Pada Periode Tersebut Sudah Ada Dalam <b>Log Penggajian</b>.
					</p>
				</form>
			</div>
			<div class="modal-footer">
				<div id="progressAddUpdate" style="float: left;"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="slip_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Slip</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_filter_slip">
					<input type="hidden" name="mode" id="usage_slip_mode" value="">
					<input type="hidden" name="usage" value="data">
					<div class="form-group">
						<label>Pilih Periode Penggajian</label>
						<select class="form-control select2" name="periode" id="data_kode_periode_slip" style="width: 100%;">
							<?php
							$periodew = $this->model_master->getPeriodePenggajianHarian(['a.status_gaji' => 0, 'a.status' => 1, 'a.create_by' => $id_admin, 'a.kode_master_penggajian' => 'HARIAN'], null, 1);
							echo '<option></option>';
							foreach ($periodew as $pw) {
								echo '<option value="' . $pw->kode_periode_penggajian_harian . '">' . $pw->nama . ' (' . $pw->nama_sistem_penggajian . ')</option>';
							}
							?>
						</select>
					</div>
				</form>
				<div class="col-md-12" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_print_slip()"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
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
						<label>Menyetujui</label>
						<select class="form-control select2" name="karyawan_rekap" id="karyawan_rekap" style="width: 100%;"></select>
					</div>
					<label class="pull-left" style="vertical-align: middle;">
						<span id="hari_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
						<span id="hari_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>
						<span style="padding-bottom: 9px;vertical-align: middle;"><b> Tampilkan Mengetahui & Dibuat</b></span>
						<input type="hidden" name="all_level" id="all_level">
					</label>
					<br>
					<div id="div_maker" style="display:none;">
						<div class="form-group">
							<label>Mengetahui</label>
							<select class="form-control select2" name="mengetahui_rekap" id="mengetahui_rekap" style="width: 100%;"></select>
						</div>
						<div class="form-group">
							<label>Dibuat</label>
							<select class="form-control select2" name="dibuat_rekap" id="dibuat_rekap" style="width: 100%;"></select>
						</div>
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
						<select class="form-control select2" name="karyawan_bagian_mengetahui" id="karyawan_bagian_mengetahui" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Menyetujui</label>
						<select class="form-control select2" name="karyawan_bagian_menyetujui" id="karyawan_bagian_menyetujui" style="width: 100%;"></select>
					</div>
				</form>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_rekap_bagian('pdf')"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-primary" onclick="do_rekap_bagian('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
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
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Penyesuaian Lembur <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit">
				<div class="modal-body">
					<legend style="color: green"><i class="fa fa-fw fa-book"></i> Data Lembur</legend>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6">
								<input type="hidden" id="data_id_edit" name="id" value="">
								<input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
								<div class="form-group">
									<label>Jam Lembur Biasa</label>
									<input type="number" step="0.5" placeholder="Masukkan Jam Lembur Biasa" id="data_jam_biasa_edit" name="jam_lembur" value="" class="form-control" required="required">
								</div>
								<div class="form-group">
									<label>Jam Lembur Libur</label>
									<input type="number" step="0.5" placeholder="Masukkan Jam Lembur Libur" id="data_jam_libur_edit" name="jam_lembur_libur" value="" class="form-control" required="required">
								</div>
								<div class="form-group">
									<label>Jam Lembur Istirahat</label>
									<input type="number" step="0.5" placeholder="Masukkan Jam Lembur Istirahat" id="data_jam_libur_pendek_edit" name="jam_lembur_istirahat" value="" class="form-control" required="required">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nominal Lembur Biasa</label>
									<input type="text" placeholder="Masukkan Nominal Lembur Biasa" id="data_nominal_biasa_edit" name="nominal" value="" class="form-control input-money" required="required">
								</div>
								<div class="form-group">
									<label>Nominal Lembur Libur</label>
									<input type="text" placeholder="Masukkan Nominal Lembur Libur" id="data_nominal_libur_edit" name="nominal_libur" value="" class="form-control input-money" required="required">
								</div>
								<div class="form-group">
									<label>Nominal Lembur Istirahat</label>
									<input type="text" placeholder="Masukkan Nominal Lembur Istirahat" id="data_nominal_libur_pendek_edit" name="nominal_istirahat" value="" class="form-control input-money" required="required">
								</div>
							</div>
						</div>
					</div>
					<legend style="color: green"><i class="fa fa-fw fa-book"></i> Data BPJS</legend>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6">
								<div class="form-group">
									<label>Jaminan Hari Tua</label>
									<input type="text" placeholder="Masukkan Jaminan Hari Tua" id="data_jht_edit" name="jht" value="" class="form-control input-money" required="required">
								</div>
								<div class="form-group">
									<label>Jaminan Kecelakaan Kerja</label>
									<input type="text" placeholder="Masukkan Jaminan Kecelakaan Kerja" id="data_jkk_edit" name="jkk" value="" class="form-control input-money" required="required">
								</div>
								<div class="form-group">
									<label>Jaminan Pensiun</label>
									<input type="text" placeholder="Masukkan Jaminan Pensiun" id="data_jpen_edit" name="jpen" value="" class="form-control input-money" required="required">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Jaminan Kematian</label>
									<input type="text" placeholder="Masukkan Jaminan Kematian" id="data_jkm_edit" name="jkm" value="" class="form-control input-money" required="required">
								</div>
								<div class="form-group">
									<label>Jaminan Kesehatan</label>
									<input type="text" placeholder="Masukkan Jaminan Kesehatan" id="data_jkes_edit" name="jkes" value="" class="form-control input-money" required="required">
								</div>
							</div>
						</div>
					</div>
					<!-- <legend style="color: green"><i class="fa fa-fw fa-book"></i> Penambah & Pengurang Lain</legend>
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-info" href="#add_tambah" data-toggle="collapse" data-parent="#accordions" style="margin-left: 5px;float: left;"><i class="fas fa-refresh"></i> Tambah Lainnya</button>
						</div>
						<div class="col-md-12">
							<div id="accordions">
								<div class="panel">
									<div id="add_tambah" class="collapse"><br>
										<div class="box">
											<div class="box-header with-border">
												<h3 class="box-title"><i class="fas fa-refresh"></i> Syncronize Data Penggajian Harian</h3>
											</div>
											<form id="form_data_lain" class="form-horizontal">
												<div class="box-body">
													<p>data</p>
												</div>
												<div class="box-footer">
													<div id="loading_progress_sync" style="float: left;"></div>
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
					</div> -->
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<?php //$this->load->view('_partial/_loading') 
?>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
<script type="text/javascript">
	var url_select = "<?php echo base_url('global_control/select2_global'); ?>";
	var table = "data_penggajian";
	var column = "id_penggajian";
	$(document).ready(function() {
		table_data('all');
		$('#btn_tambah').click(function() {
			$('#add').modal('show');
			$('#data_lain_add').iCheck('check');
			$('#bpjs_add').iCheck('check');
		});
		$('#data_lain_add').on('ifUnchecked', function() {
			$('#data_lain_hidden').val('');
		});
		$('#bpjs_add').on('ifUnchecked', function() {
			$('#bpjs_hidden').val('');
		});
		$('#data_lain_add').on('ifChecked', function() {
			$('#data_lain_hidden').val('data_lain');
		});
		$('#bpjs_add').on('ifChecked', function() {
			$('#bpjs_hidden').val('bpjs');
		});
		refreshData();
		$('#bagian_sync, #lokasi_kerja_sync, #minggu_sync, #bulan_sync, #tahun_sync').change(function() {
			var bagian = $('#bagian_sync').val();
			var lokasi = $('#lokasi_kerja_sync').val();
			var minggu = $('#minggu_sync').val();
			var bulan = $('#bulan_sync').val();
			var tahun = $('#tahun_sync').val();
			var data = {
				bagian: bagian,
				lokasi: lokasi,
				minggu: minggu,
				bulan: bulan,
				tahun: tahun,
			};
			var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/get_karyawan_new/') ?>", data);
			$('#sync_karyawan').html(callback['karyawan']);
		});
		$('#bagian_ins, #lokasi_kerja_ins, #minggu_ins, #bulan_ins, #tahun_ins').change(function() {
			var bagian = $('#bagian_ins').val();
			var lokasi = $('#lokasi_kerja_ins').val();
			var minggu = $('#minggu_ins').val();
			var bulan = $('#bulan_ins').val();
			var tahun = $('#tahun_ins').val();
			var data = {
				bagian: bagian,
				lokasi: lokasi,
				minggu: minggu,
				bulan: bulan,
				tahun: tahun,
			};
			var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/get_karyawan_new/') ?>", data);
			$('#karyawan_ins').html(callback['karyawan']);
		});
	});

	function refreshData() {
		getSelect2("<?php echo base_url('master/master_bagian/get_select2') ?>", 'bagian_add, #bagian_ser, #bagian_log, #bagian_sync, #bagian_ins');
		select_data('lokasi_kerja_add', url_select, 'master_loker', 'kode_loker', 'nama', 'placeholder');
		select_data('lokasi_kerja_ser', url_select, 'master_loker', 'kode_loker', 'nama', 'placeholder');
		select_data('lokasi_kerja_log', url_select, 'master_loker', 'kode_loker', 'nama', 'placeholder');
		select_data('lokasi_kerja_sync', url_select, 'master_loker', 'kode_loker', 'nama', 'placeholder');
		select_data('lokasi_kerja_ins', url_select, 'master_loker', 'kode_loker', 'nama', 'placeholder');
		unsetoption('bagian_add', ['BAG001', 'BAG002']);
		unsetoption('bagian_ser', ['BAG001', 'BAG002']);
		unsetoption('bagian_log', ['BAG001', 'BAG002']);
		unsetoption('bagian_sync', ['BAG001', 'BAG002']);
		unsetoption('bagian_ins', ['BAG001', 'BAG002']);
	}

	function table_data(kode) {
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('cpayroll/data_penggajian_harian/view_all/') ?>",
				type: 'POST',
				data: {
					form: $('#adv_form_filter').serialize(),
					access: "<?php echo $this->codegenerator->encryptChar($access); ?>",
					param: kode,
				}
			},
			fixedColumns: {
				leftColumns: 3,
				rightColumns: 1
			},
			bDestroy: true,
			scrollX: true,
			autoWidth: false,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function(data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 13,
					width: '5%',
					render: function(data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 14,
					width: '10%',
					render: function(data, type, full, meta) {
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
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/view_one') ?>", data);
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
		$('#data_lembur_view').html(callback['lembur']);
		$('#data_bpjs_view').html(callback['data_bpjs']);
		$('#data_lainnya_view').html(callback['data_lainnya']);
		$('#data_hari_kerja_view').html(callback['presensi']);
		$('#data_gaji_diterima_view').html(callback['gaji_diterima']);

		$('#data_gaji_bersih_view').html(callback['total_gaji']);
		$('#data_gaji_pokok_view').html(callback['gaji_pokok']);
		$('#data_pph_view').html(callback['pph']);

		// $('#data_periode_view').html(callback['periode']);
		$('#data_minggu_view').html(callback['minggu_view']);
		$('#data_bulan_view').html(callback['bulan_view']);
		$('#data_tahun_view').html(callback['tahun']);
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
		var bagian = $('#bagian_add').val();
		var lokasi = $('#lokasi_kerja_add').val();
		var tanggal = $('#tanggal_payroll_add').val();
		var minggu = $('#minggu_add').val();
		var bulan = $('#bulan_add').val();
		var tahun = $('#tahun_add').val();
		if (lokasi == null) {
			notValidParamxCustom('Harap Pilih Lokasi !');
		} else if (bagian == null) {
			notValidParamxCustom('Harap Pilih Bagian !');
		} else if (minggu == null) {
			notValidParamxCustom('Harap Pilih Minggu !');
		} else if (bulan == null) {
			notValidParamxCustom('Harap Pilih Bulan !');
		} else if (tahun == null) {
			notValidParamxCustom('Harap Pilih Tahun !');
		} else {
			$('#progressAdd').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Sedang mem-Proses data....');
			$('#progressAdd').show();
			var data = $('#form_add').serialize();
			var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_payroll_harian') ?>", data);
			if (cek_ready['msg'] == 'true') {
				submitAjax("<?php echo base_url('cpayroll/ready_data_payroll_harian') ?>", null, 'form_add', null, null);
				table_data('search');
				$('#progressAdd').hide();
				$('#add').modal('hide');
			} else if (cek_ready['msg'] == 'ada_data') {
				$('#progressAdd').hide();
				$('#add').modal('hide');
				$('#confirm_ada_data').modal('show');
				$('#bagian_ada_data').val(cek_ready['kode_bagian']);
				$('#lokasi_ada_data').val(cek_ready['kode_loker']);
				$('#minggu_ada_data').val(cek_ready['minggu']);
				$('#bulan_ada_data').val(cek_ready['bulan']);
				$('#tahun_ada_data').val(cek_ready['tahun']);
			} else if (cek_ready['msg'] == 'ada_data_log') {
				$('#progressAdd').hide();
				$('#add').modal('hide');
				$('#confirm_ada_data_log').modal('show');
				$('#lokasi_ada_data').val(cek_ready['lokasi']);
				$('#bulan_ada_data').val(cek_ready['bulan']);
				$('#tahun_ada_data').val(cek_ready['tahun']);
			} else {
				$('#progressAdd').hide();
				$('#add').modal('hide');
			}
		}
	}

	function log_confirm() {
		$('#log').modal('show');
	}

	function do_log() {
		var bagian = $('#bagian_log').val();
		var lokasi = $('#lokasi_kerja_log').val();
		var minggu = $('#minggu_log').val();
		var bulan = $('#bulan_log').val();
		var tahun = $('#tahun_log').val();
		if (lokasi == null) {
			notValidParamxCustom('Harap Pilih Lokasi !');
		} else if (bagian == null) {
			notValidParamxCustom('Harap Pilih Bagian !');
		} else if (minggu == null) {
			notValidParamxCustom('Harap Pilih Minggu !');
		} else if (bulan == null) {
			notValidParamxCustom('Harap Pilih Bulan !');
		} else if (tahun == null) {
			notValidParamxCustom('Harap Pilih Tahun !');
		} else {
			var data = {
				bagian: bagian,
				lokasi: lokasi,
				minggu: minggu,
				bulan: bulan,
				tahun: tahun,
			};
			$('#progressLog').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Sedang mem-Proses data....');
			$('#progressLog').show();
			var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_payroll_harian') ?>", data);
			if (cek_ready['msg'] == 'true') {
				$('#progressLog').hide();
				submitAjax("<?php echo base_url('cpayroll/cek_data_payroll_notif/lembur') ?>", null, null, null, null, 'status');
			} else if (cek_ready['msg'] == 'ada_data') {
				submitAjax("<?php echo base_url('cpayroll/send_to_log_harian') ?>", 'log', 'form_log', null, null);
				$('#progressLog').hide();
				$('#table_data').DataTable().ajax.reload(function() {
					Pace.restart();
				});
			}
		}
	}

	function do_delete_ada_data() {
		submitAjax("<?php echo base_url('Cpayroll/del_ada_data_harian') ?>", 'confirm_ada_data', 'form_ada_data', null, null);
		$('#table_data').DataTable().ajax.reload(function() {
			Pace.restart();
		});
	}

	function print_slip() {
		$('#usage_slip_mode').val('export_lembur');
		$('#slip_mode').modal('show');
	}

	function do_print_slip() {
		var bagian = $('#bagian_ser').val();
		var lokasi = $('#lokasi_kerja_ser').val();
		var minggu = $('#minggu_ser').val();
		var bulan = $('#bulan_ser').val();
		var tahun = $('#tahun_ser').val();
		if (lokasi == '') {
			notValidParamxCustom('Harap Pilih Lokasi !');
		} else if (bagian == '') {
			notValidParamxCustom('Harap Pilih Bagian !');
		} else if (minggu == '') {
			notValidParamxCustom('Harap Pilih Minggu !');
		} else if (bulan == '') {
			notValidParamxCustom('Harap Pilih Bulan !');
		} else if (tahun == '') {
			$('#alert').modal('show');
			notValidParamxCustom('Harap Pilih Tahun !');
		} else {
			var data = {
				bagian: bagian,
				lokasi: lokasi,
				minggu: minggu,
				bulan: bulan,
				tahun: tahun,
			};
			var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_payroll_harian') ?>", data);
			// alert(cek_ready['msg']);
			if (cek_ready['msg'] == 'true') {
				submitAjax("<?php echo base_url('cpayroll/cek_data_payroll_notif/lembur') ?>", null, null, null, null, 'status');
			} else if (cek_ready['msg'] == 'ada_data') {
				$.redirect("<?php echo base_url('pages/slip_gaji_harian'); ?>", {
						data_filter: $('#adv_form_filter').serialize()
					},
					"POST", "_blank");
			}
		}
	}

	function rekap_gaji() {
		$('#usage_rekap_mode').val('rekap');
		var lokasi = $('#lokasi_kerja_ser').val();
		var bagian = $('#bagian_ser').val();
		var minggu = $('#minggu_ser').val();
		var bulan = $('#bulan_ser').val();
		var tahun = $('#tahun_ser').val();
		if(lokasi == ''){
			notValidParamxCustom('Harap Pilih Lokasi !');
		}else if(bagian == ''){
			notValidParamxCustom('Harap Pilih Bagian !');
		}else if(minggu == ''){
			notValidParamxCustom('Harap Pilih Minggu !');
		}else if(bulan == ''){
			notValidParamxCustom('Harap Pilih Bulan !');
		}else if(tahun == ''){
			notValidParamxCustom('Harap Pilih Tahun !');
		}else{
			$('#rekap_mode').modal('show');
			getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee') ?>", 'karyawan_rekap, #mengetahui_rekap, #dibuat_rekap');
			$('#hari_off').click(function(){
				$('#hari_off').hide();
				$('#hari_on').show();
				$('input[name="all_level"]').val('1');
				$('#div_maker').show();
			});
			$('#hari_on').click(function(){
				$('#hari_off').show();
				$('#hari_on').hide();
				$('input[name="all_level"]').val('0');
				$('#div_maker').hide();
			});
		}
	}

	function do_rekap(file) {
		var usage = $('#usage_rekap_mode').val();
		var emp = $('#karyawan_rekap').val();
		var jenis = $('#all_level').val();
		var mengetahui = $('#mengetahui_rekap').val();
		var dibuat = $('#dibuat_rekap').val();
		if (usage == '') {
			notValidParamx();
		} else {
			if (usage == 'rekap') {
				if (file == 'pdf') {
					$.redirect("<?php echo base_url('pages/rekap_payroll_harian'); ?>", {
							data_filter: $('#adv_form_filter').serialize(),
							karyawan: emp,
							jenis: jenis,
							mengetahui: mengetahui,
							dibuat: dibuat,
						},
						"POST", "_blank");
				} else {
					$.redirect("<?php echo base_url('rekap/export_log_data_gaji_harian'); ?>", {
							data_filter: $('#adv_form_filter').serialize(),
							karyawan: emp
						},
						"POST", "_blank");
				}
			}
		}
	}

	function rekap_bagian() {
		$('#usage_bagian_mode').val('export_lembur');
		var lokasi = $('#lokasi_kerja_ser').val();
		var bagian = $('#bagian_ser').val();
		var minggu = $('#minggu_ser').val();
		var bulan = $('#bulan_ser').val();
		var tahun = $('#tahun_ser').val();
		if(lokasi == ''){
			notValidParamxCustom('Harap Pilih Lokasi !');
		}else if(bagian == ''){
			notValidParamxCustom('Harap Pilih Bagian !');
		}else if(minggu == ''){
			notValidParamxCustom('Harap Pilih Minggu !');
		}else if(bulan == ''){
			notValidParamxCustom('Harap Pilih Bulan !');
		}else if(tahun == ''){
			notValidParamxCustom('Harap Pilih Tahun !');
		}else{
			$('#bagian_mode').modal('show');
			getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee') ?>", 'karyawan_bagian_mengetahui, #karyawan_bagian_menyetujui');
		}
	}

	function do_rekap_bagian(file) {
		var mengetahui = $('#karyawan_bagian_mengetahui').val();
		var menyetujui = $('#karyawan_bagian_menyetujui').val();
		if (file == 'pdf') {
			$.redirect("<?php echo base_url('pages/rekap_gaji_harian_bagian'); ?>", {
					data_filter: $('#adv_form_filter').serialize(),
					mengetahui: mengetahui,
					menyetujui: menyetujui,
				},
				"POST", "_blank");
		} else {
			$.redirect("<?php echo base_url('rekap/export_penggajian_harian_bagian'); ?>", {
					data_filter: $('#adv_form_filter').serialize(),
					mengetahui: mengetahui,
					menyetujui: menyetujui,
			},
			"POST", "_blank");
		}
	}

	// function do_rekap_bagian(file) {
	// 	var kode_periode = $('#data_periode_adv').val();
	// 	var mengetahui = $('#karyawan_bagian_mengetahui').val();
	// 	var menyetujui = $('#karyawan_bagian_menyetujui').val();
	// 	var data = {
	// 		kode_periode: kode_periode
	// 	};
	// 	var cek_ready = getAjaxData("<?php //echo base_url('cpayroll/cek_data_payroll_harian') ?>", data);
	// 	if (cek_ready['msg'] == 'true') {
	// 		submitAjax("<?php //echo base_url('cpayroll/cek_data_payroll_notif/lembur') ?>", null, null, null, null,
	// 			'status');
	// 	} else if (cek_ready['msg'] == 'ada_data') {
	// 		if (kode_periode == '') {
	// 			$('#alert').modal('show');
	// 		} else {
	// 			if (file == 'pdf') {
	// 				$.redirect("<?php //echo base_url('pages/rekap_gaji_harian_bagian'); ?>", {
	// 						data_filter: $('#adv_form_filter').serialize(),
	// 						mengetahui: mengetahui,
	// 						menyetujui: menyetujui,
	// 					},
	// 					"POST", "_blank");
	// 			} else {
	// 				$.redirect("<?php //echo base_url('rekap/export_penggajian_harian_bagian'); ?>", {
	// 						data_filter: $('#adv_form_filter').serialize(),
	// 						mengetahui: mengetahui,
	// 						menyetujui: menyetujui,
	// 					},
	// 					"POST", "_blank");
	// 			}
	// 		}
	// 	}
	// }

	// function get_bagian_adv(value) {
	// 	var data = {
	// 		kode_periode: value
	// 	};
	// 	var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/bagian/') ?>", data);
	// 	$('#adv_bagian').html(callback['bagian']);
	// }

	// function get_bagian_sync(value) {
	// 	var data = {
	// 		kode_periode: value
	// 	};
	// 	var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/bagian_sync/') ?>", data);
	// 	$('#sync_bagian').html(callback['bagian']);
	// }

	// function get_karyawan_sync(value) {
	// 	var bagian = $('#bagian_sync').val();
	// 	var lokasi = $('#lokasi_kerja_sync').val();
	// 	var data = {
	// 		bagian: bagian,
	// 		lokasi: lokasi,
	// 	};
	// 	var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/get_karyawan_new/') ?>", data);
	// 	$('#sync_karyawan').html(callback['karyawan']);
	// }

	function do_sync_data() {
		show_loader();
		$('#loading_progress_sync div div div p b').html('Mohon Tunggu, Sedang men-Syncronize Data...');
		submitAjax("<?php echo base_url('cpayroll/sync_data_penggajian_harian') ?>", null, 'form_sync', null, null);
		table_data('all');
		$('#bagian_sync').val('').trigger('change');
		$('#lokasi_kerja_sync').val('').trigger('change');
		$('#minggu_sync').val('').trigger('change');
		$('#bulan_sync').val('').trigger('change');
		$('#tahun_sync').val('').trigger('change');
		$('#sync_karyawan').val('').trigger('change');
		$('#loading_progress_sync').modal('hide');
	}

	function edit_modal() {
		var id = $('input[name="data_id_view"]').val();
		var data = {
			id_pay: id
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/view_one') ?>", data);
		$('#view').modal('toggle');
		setTimeout(function() {
			$('#edit').modal('show');
		}, 600);
		$('#data_id_edit').val(callback['id']);
		$('#data_jam_biasa_edit').val(callback['jam_biasa']);
		$('#data_nominal_biasa_edit').val(callback['nominal_biasa']);
		$('#data_jam_libur_pendek_edit').val(callback['jam_libur_pendek']);
		$('#data_nominal_libur_pendek_edit').val(callback['nominal_libur_pendek']);
		$('#data_jam_libur_edit').val(callback['jam_libur']);
		$('#data_nominal_libur_edit').val(callback['nominal_libur']);
		$('#data_jam_lembur_edit').val(callback['total_jam']);
		$('#data_nominal_edit').val(callback['gaji_lembur']);
		$('#data_jht_edit').val(callback['jht']);
		$('#data_jkk_edit').val(callback['jkk']);
		$('#data_jpen_edit').val(callback['jpen']);
		$('#data_jkm_edit').val(callback['jkm']);
		$('#data_jkes_edit').val(callback['jkes']);
	}

	function do_edit() {
		if ($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/edit_penyesuaian_lembur_penggajian_harian') ?>", 'edit', 'form_edit', null, null);
			$('#table_data').DataTable().ajax.reload();
		} else {
			notValidParamx();
		}
	}
	function insertDataLain() {
		var data={no_sk:null};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/insert_data_lain')?>",data);
		$('#tabel_end_proses').html(callback['tabel_end_proses']);
	}
	function myFunction() {
		var data={no_sk:null};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/insert_data_lain')?>",data);
		var table = document.getElementById("myTable");
		var row = table.insertRow(1);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		var cell3 = row.insertCell(2);
		cell1.innerHTML = callback['select'];
		cell2.innerHTML = callback['nama'];
		cell3.innerHTML = callback['nominal'];
	}
	function deleterow() {
		var table = document.getElementById("myTable");
		var row = table.deleteRow(1);
		var cell1 = row.deleteCell(0);
		var cell2 = row.deleteCell(1);
		var cell3 = row.deleteCell(2);
	}
	function do_insert_lainnya() {
		show_loader();
		$('#loading_progress_ins div div div p b').html('Mohon Tunggu, Sedang menperbarui Data...');
		submitAjax("<?php echo base_url('cpayroll/insert_data_pendukung_lain_harian') ?>", null, 'form_data_lain', null, null);
		// table_data('all');
		// $('#bagian_ins').val('').trigger('change');
		// $('#lokasi_kerja_ins').val('').trigger('change');
		// $('#minggu_ins').val('').trigger('change');
		// $('#bulan_ins').val('').trigger('change');
		// $('#tahun_ins').val('').trigger('change');
		// $('#sync_karyawan').val('').trigger('change');
		$('#loading_progress_ins').modal('hide');
	}
	function rekap_tanda_terima() {
		var lokasi = $('#lokasi_kerja_ser').val();
		var bagian = $('#bagian_ser').val();
		var minggu = $('#minggu_ser').val();
		var bulan = $('#bulan_ser').val();
		var tahun = $('#tahun_ser').val();
		if(lokasi == ''){
			notValidParamxCustom('Harap Pilih Lokasi !');
		}else if(bagian == ''){
			notValidParamxCustom('Harap Pilih Bagian !');
		}else if(minggu == ''){
			notValidParamxCustom('Harap Pilih Minggu !');
		}else if(bulan == ''){
			notValidParamxCustom('Harap Pilih Bulan !');
		}else if(tahun == ''){
			notValidParamxCustom('Harap Pilih Tahun !');
		}else{	
			$.redirect("<?php echo base_url('pages/tanda_terima_gaji_harian'); ?>", 
			{
				data_filter: $('#adv_form_filter').serialize()
			},
			"POST", "_blank");
		}
	}
	function do_rekap_ttd() {	
	}
</script>
