<style type="text/css">
	table#table_data thead tr th, table#table_data tbody tr td, table.DTFC_Cloned thead tr th{
		white-space: pre;
	}
	table.DTFC_Cloned tbody{
		overflow: hidden;
	}
</style><div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fas fa-user-clock"></i> Data PPH-21
			<small>HARIAN</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Data PPH-21 HARIAN</li>
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
								<div class="col-md-2">
								</div>
								<div class="col-md-8">
									<div class="col-md-4">
										<div class="form-group">
											<label>Pilih Bulan</label>
											<select class="form-control select2" name="bulan" id="search_bulan" style="width: 100%;" required="required">
												<?php
												$bulan_copy = $this->formatter->getMonth();
												echo '<option value="">Pilih Data</option>';
												foreach ($bulan_copy as $buf => $valf) {
													echo '<option value="'.$buf.'">'.$valf.'</option>';
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Pilih Tahun</label>
											<?php
												$tahun_copy = $this->formatter->getYear();
												$sels = array(date('Y'));
												$exs = array('class'=>'form-control select2', 'id'=>'search_tahun', 'style'=>'width:100%;','required'=>'required');
												echo form_dropdown('tahun',$tahun_copy,$sels,$exs);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Pembetulan</label>
											<?php
												$koreksi_copy = $this->otherfunctions->getNumberToAbjadList();
												$selsx = array('Pilih Data');
												$exsx = array('class'=>'form-control select2', 'id'=>'search_koreksi', 'style'=>'width:100%;','required'=>'required');
												echo form_dropdown('koreksi',$koreksi_copy,$selsx,$exsx);
											?>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="box-footer">
							<div class="col-md-12">
								<div class="pull-right">
									<button type="button" onclick="table_filter()" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data PPH-21 HARIAN</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
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
														echo '<button href="#add" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Tambah PPh 21</button> ';
													}
													if (in_array('EXP', $access['access'])) {
														echo '<div class="btn-group">
															<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" style="margin-left: 5px;float: left;"><i class="fas fa-file-excel-o"></i> Export
															<span class="fa fa-caret-down"></span></button>
															<ul class="dropdown-menu">
															<li><a onclick="model_export()">Export Data</a></li>
															</ul>
														</div>';
													}
												// <li><a onclick="model_export_bagian()">Export Data Bagian</a></li>
												// <li><a onclick="model_export_bp_final()">Export Data BP Final Karyawan</a></li>
												// <li><a onclick="model_export_bp_final_pesangon()">Export Data BP Final Pesangon</a></li>
												// <li><a onclick="model_export_1721_bp_a1()">Export Data 1721 BP A1</a></li>
													if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<button href="#insert" data-toggle="collapse" id="btn_penunjang" data-parent="#accordion" onclick="insertPenunjang()" class="btn btn-info" style="margin-left: 5px;float: right;"><i class="fa fa-plus"></i> Insert Penunjang</button> ';
													}
													?>
												</div>
												<div class="pull-right" style="font-size: 8pt;">
													<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
													<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
												</div>
											</div>
										</div>
										<div id="add" class="collapse"><br>
											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title"><i class="fas fa-plus"></i> Tambah Data PPh 21</h3>
												</div>
												<form id="form_add" class="form-horizontal">
													<div class="box-body">
														<div class="row">
															<div class="col-md-12">
																<div class="col-md-1"></div>
																<div class="col-md-10">
																	<div class="col-md-4">
																		<div class="form-group">
																			<label>Pilih Bulan</label>
																			<select class="form-control select2" name="bulan" id="add_bulan" style="width: 100%;" required="required">
																				<?php
																				$bulan_copy = $this->formatter->getMonth();
																				echo '<option value="">Pilih Data</option>';
																				foreach ($bulan_copy as $buf => $valf) {
																					echo '<option value="'.$buf.'">'.$valf.'</option>';
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group">
																			<label>Pilih Tahun</label>
																			<?php
																				$tahun_copy = $this->formatter->getYear();
																				$sels = array(date('Y'));
																				$exs = array('class'=>'form-control select2', 'id'=>'add_tahun', 'style'=>'width:100%;','required'=>'required');
																				echo form_dropdown('tahun',$tahun_copy,$sels,$exs);
																			?>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group">
																			<label>Pembetulan</label>
																			<?php
																				$koreksi_copy = $this->otherfunctions->getNumberToAbjadList();
																				$selsx = array('Pilih Data');
																				$exsx = array('class'=>'form-control select2', 'id'=>'add_koreksi', 'style'=>'width:100%;','required'=>'required');
																				echo form_dropdown('koreksi',$koreksi_copy,$selsx,$exsx);
																			?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="box-footer">
														<div id="progress2" style="float: left;"></div>
														<div class="pull-right">
															<button type="button" onclick="do_add()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Tambah Data</button>
														</div>
													</div>
												</form>
											</div>
										</div>
										<div id="insert" class="collapse"><br>
											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title"><i class="fas fa-plus"></i> Insert Penunjang Data PPh 21 Harian</h3>
												</div>
												<form id="form_penunjang">
													<div class="row">
														<div class="col-md-12">
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Pilih Bulan</label>
																		<select class="form-control select2" name="bulan" id="pen_bulanx" style="width: 100%;" required="required">
																			<?php
																			$bulan_p = $this->formatter->getMonth();
																			echo '<option value="">Pilih Data</option>';
																			echo '<option value="all_month">Semua Bulan</option>';
																			foreach ($bulan_p as $bup => $valp) {
																				echo '<option value="'.$bup.'">'.$valp.'</option>';
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Pilih Tahun</label>
																		<?php
																			$tahun_p = $this->formatter->getYear();
																			$selpx = array(date('Y'));
																			$expx = array('class'=>'form-control select2', 'id'=>'pen_tahunx', 'style'=>'width:100%;','required'=>'required');
																			echo form_dropdown('tahun',$tahun_p,$selpx,$expx);
																		?>
																	</div>
																</div>
																<div class="col-md-12">
																	<div class="form-group">
																		<label>Karyawan</label>
																		<select class="form-control select2" id="karyawan_pen" name="karyawan[]" multiple="multiple" style="width: 100%;"></select>
																	</div>
																	<div id="tabel_end_proses"> </div>
																	<br>
																	<button type="button" class="btn btn-success" onclick="myFunction()"><i class="fa fa-plus"></i> Add</button>
																	<button type="button" class="btn btn-danger" onclick="deleterow()"><i class="fa fa-trash"></i> Delete</button>
																</div>
															</div>
														</div>
													</div>
													<div class="box-footer">
														<button type="button" onclick="do_add_penunjang()" id="btn_edit_end" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
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
								<?php $this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
									$days = $this->formatter->getDateMonthFormatUser($this->date);
								?>
								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
								<ul>
									<li>List Default menampilkan Data PPh 21 Harian Pada Bulan <b><?=substr($days,3); ?></b>,</li>
								</div>
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th rowspan="2">No.</th>
											<th rowspan="2">NIK Karyawan</th>
											<th rowspan="2">Nama Karyawan</th>
											<th rowspan="2">Jabatan</th>
											<th rowspan="2">Bagian</th>
											<th class="text-center" style="background-color:yellow;" colspan="5">GAJI DALAM PERIODE</th>
											<th class="text-center" style="background-color:blue;" colspan="5"><span style="color:white;">LEMBUR DALAM PERIODE</span></th>
											<th rowspan="2">UM, Insentif/ Tambahan<br>Tugas Luar Kota, dll (Rp)</th>
											<th rowspan="2">THR (Rp)</th>
											<th rowspan="2">YANG DITERIMA (Rp)</th>
											<th class="text-center" style="background-color:green;" colspan="8"><span style="color:white;">PENAMBAH</span></th>
											<th class="text-center" style="background-color:red;" colspan="4"><span style="color:white;">PENGURANG</span></th>
											<th rowspan="2">Penghasilan Bruto (Rp)</th>
											<th rowspan="2">Pesangon (Rp)</th>
											<th rowspan="2">Penghasilan Netto (Rp)</th>
											<th rowspan="2">Status Pajak</th>
											<th rowspan="2">Hari Kerja</th>
											<th rowspan="2">PTKP (Rp)</th>
											<th rowspan="2">PKP (Rp)</th>
											<th rowspan="2">PPH 21 (Rp)</th>
											<th rowspan="2">Setor (Rp)</th>
											<th rowspan="2">Rumus Tunjangan PPh</th>
											<th rowspan="2">Tanggal</th>
											<th rowspan="2">Aksi</th>
										</tr>
										<tr>
											<th>MINGGU 1 (Rp)</th>
											<th>MINGGU 2 (Rp)</th>
											<th>MINGGU 3 (Rp)</th>
											<th>MINGGU 4 (Rp)</th>
											<th>MINGGU 5 (Rp)</th>
											<th>MINGGU 1 (Rp)</th>
											<th>MINGGU 2 (Rp)</th>
											<th>MINGGU 3 (Rp)</th>
											<th>MINGGU 4 (Rp)</th>
											<th>MINGGU 5 (Rp)</th>
											<th>JKK (Rp)</th>
											<th>JKM	(Rp)</th>
											<th>PPh	(Rp)</th>
											<th>Premi (Rp)</th>
											<th>JHT	(Rp)</th>
											<th>Jaminan Pensiun (Rp)</th>
											<th>BPJS Kesehatan (Rp)</th>
											<th>Penambah Lain (Rp)</th>
											<th>Potongan BPJS<br>Kesehatan (Rp)</th>
											<th>Iuran Pensiun</th>
											<th>Potongan JHT</th>
											<th>Pengurang Lain (Rp)</th>
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
							<label class="col-md-6 control-label">Bagian</label>
							<div class="col-md-6" id="data_bagian_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Grade</label>
							<div class="col-md-6" id="data_grade_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">No. NPWP</label>
							<div class="col-md-6" id="data_npwp_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Masuk</label>
							<div class="col-md-6" id="data_tanggal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Masa Kerja</label>
							<div class="col-md-6" id="data_masa_view"></div>
						</div> -->
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status PTKP</label>
							<div class="col-md-6" id="data_ptkp_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Pembetulan</label>
							<div class="col-md-6" id="data_koreksi_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Gaji Harian</label>
							<div class="col-md-6" id="data_gaji_pokok_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">UM, Insentif/ Tambahan Tugas Luar Kota, dll (Rp)</label>
							<div class="col-md-6" id="data_kode_akun_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Periode Penggajian</label>
							<div class="col-md-6" id="data_periode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Sistem Penggajian</label>
							<div class="col-md-6" id="data_sistem_view"></div>
						</div> -->
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bulan</label>
							<div class="col-md-6" id="data_bulan_prd_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tahun</label>
							<div class="col-md-6" id="data_tahun_prd_view"></div>
                  		</div>
						<div class="form-group col-md-12">
                     		<hr>
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
					</div>
					<div class="col-md-12"><hr></div>
					<div class="col-md-6">
						<table class="table table-bordered table-striped table-responsive" width="100%" border="1">
							<tr>
								<th colspan="2" style="text-align: center;">PENAMBAH</th>
							</tr>
							<tr>
								<th>JKK Perusahaan</th>
								<td><div id="data_bpjs_jkk_perusahaan_view"></div></td>
							</tr>
							<tr>
								<th>JKM Perusahaan</th>
								<td><div id="data_bpjs_jkm_perusahaan_view"></div></td>
							</tr>
							<tr>
								<th>PPh</th>
								<td><div id="data_bpjs_pph_view"></div></td>
							</tr>
							<tr>
								<th>JHT Perusahaan</th>
								<td><div id="data_bpjs_jht_perusahaan_view"></div></td>
							</tr>
							<tr>
								<th>BPJS Kesehatan Perusahaan</th>
								<td><div id="data_bpjs_kes_perusahaan_view"></div></td>
							</tr>
							<tr>
								<th>Jaminan Pensiun Perusahaan</th>
								<td><div id="data_iuran_pensiun_perusahaan_view"></div></td>
							</tr>
							<tr>
								<th>Premi Asuransi</th>
								<td><div id="data_premi_asuransi_view"></div></td>
							</tr>
							<tr>
								<th>Lainnya</th>
								<td><div id="data_lainnya_view"></div></td>
							</tr>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table table-bordered table-striped table-responsive" width="100%" border="1">
							<tr>
								<th colspan="2" style="text-align: center;">POTONGAN</th>
							</tr>
							<tr>
								<th>JHT Pekerja</th>
								<td>
									<div id="data_bpjs_jht_pekerja_view"></div>
								</td>
							</tr>
							<tr>
								<th>JKK Pekerja</th>
								<td>
									<div id="data_bpjs_jkk_pekerja_view"></div>
								</td>
							</tr>
							<tr>
								<th>BPJS JKM Pekerja</th>
								<td>
									<div id="data_bpjs_jkm_pekerja_view"></div>
								</td>
							</tr>
							<tr>
								<th>BPJS Kesehatan Pekerja</th>
								<td>
									<div id="data_bpjs_kes_pekerja_view"></div>
								</td>
							</tr>
							<tr>
								<th>Iuran Pensiun Pekerja</th>
								<td>
									<div id="data_iuran_pensiun_pekerja_view"></div>
								</td>
							</tr>
							<!-- <tr>
								<th>Piutang Pekerja</th>
								<td>
									<div id="data_piutang_view"></div>
								</td>
							</tr>
							<tr>
								<th>Koreksi Absen</th>
								<td>
									<div id="data_koreksi_absen_view"></div>
								</td>
							</tr>
							<tr>
								<th>Denda</th>
								<td>
									<div id="data_denda_view"></div>
								</td>
							</tr> -->
							<tr>
								<th>Potongan Lainnya</th>
								<td>
									<div id="data_potongan_lain_view"></div>
								</td>
							</tr>
						</table>
               </div>
					<div class="col-md-12">
						<hr>
						<table class="table table-bordered table-striped table-responsive" width="100%">
							<tr>
								<th>YANG DITERIMA</th>
								<td>
									<div id="data_yg_diterima_view"></div>
								</td>
							</tr>
							<tr>
								<th>Penghasilan Bruto Sebulan</th>
								<td>
									<div id="data_bruto_sebulan_view"></div>
								</td>
							</tr>
							<tr>
								<th>Penghasilan Bruto Setahun</th>
								<td>
									<div id="data_bruto_setahun_view"></div>
								</td>
							</tr>
							<!-- <tr>
								<th>Biaya Jabatan</th>
								<td>
									<div id="data_biaya_jabatan_view"></div>
								</td>
							</tr> -->
							<tr>
								<th>Penghasilan Netto Sebulan</th>
								<td>
									<div id="data_netto_sebulan_view"></div>
								</td>
							</tr>
							<tr>
								<th>Penghasilan Netto Setahun</th>
								<td>
									<div id="data_netto_setahun_view"></div>
								</td>
							</tr>
							<tr>
								<th>PTKP Sebulan</th>
								<td>
									<div id="data_ptkp_sebulan_view"></div>
								</td>
							</tr>
							<tr>
								<th>PTKP Setahun</th>
								<td>
									<div id="data_ptkp_setahun_view"></div>
								</td>
							</tr>
							<tr>
								<th>PKP Sebulan</th>
								<td>
									<div id="data_pkp_sebulan_view"></div>
								</td>
							</tr>
							<tr>
								<th>PKP Setahun</th>
								<td>
									<div id="data_pkp_setahun_view"></div>
								</td>
							</tr>
							<tr>
								<th>PPH 21 Sebulan</th>
								<td>
									<div id="data_pph21sebulan_view"></div>
								</td>
							</tr>
							<tr>
								<th>PPH 21 Setahun</th>
								<td>
									<div id="data_pph21setahun_view"></div>
								</td>
							</tr>
							<tr>
								<th>Pajak Setahun</th>
								<td>
									<div id="data_pajak_setahun_view"></div>
								</td>
							</tr>
							<tr>
								<th>PPH 21 YANG DIBAYAR</th>
								<td>
									<div id="data_pph21_dibayar_view"></div>
								</td>
							</tr>
							<tr>
								<th>PPH 21 YANG DIPOTONG</th>
								<td>
									<div id="data_pph21_dipotong_view"></div>
								</td>
							</tr>
							<tr>
								<th>Tunjangan PPh </th>
								<td>
									<div id="data_tunjangan_pph_view"></div>
								</td>
							</tr>
							<!-- <tr>
								<th>NPWP</th>
								<td>
									<div id="data_npwp_view"></div>
								</td>
							</tr> -->
						</table>
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
<div id="confirm_ada_data" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Data Sudah Ada</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_ada_data">
					<input type="hidden" name="bulan" id="bulan_ada_data">
					<input type="hidden" name="tahun" id="tahun_ada_data">
					<input type="hidden" name="koreksi" id="koreksi_ada_data">
					<p>Data PPh 21 Pada Bulan & Tahun Tersebut Sudah Ada, <br>Data yg sudah anda buat pada Bulan & Tahun
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
<div id="alert" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-exclamation-triangle"></i> Alert!</h4>
			</div>
			<div class="modal-body text-center">
				<p>Silahkan Pilih Periode!</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<!-- <div id="rekap_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Mode</h4>
			</div>
			<div class="modal-body">
				<form id="form_print" class="form-horizontal">
					<div class="row">
						<div class="col-md-12">
							<h4 class="text-center"><b>Pilih Tipe Rekap</b></h4>
							<div class="clearfix text-center">
								<div class="col-md-6">
									<span id="bulan_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
									<span id="bulan_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
									<span style="padding-bottom: 9px;vertical-align: middle;"><b>BULANAN</b></span>
								</div>
								<div class="col-md-6">
									<span id="tahun_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
									<span id="tahun_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
									<span style="padding-bottom: 9px;vertical-align: middle;"><b>TAHUNAN</b></span>
								</div>
								<input type="hidden" name="tipe_rekap" id="tipe_rekapx">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div id="format_bulan" style="display:none">
								<div class="form-group">
									<label>Pilih Bulan</label>
									<select class="form-control select2" name="bulan" id="search_bulanx" style="width: 100%;" required="required">
										<?php
										$bulan_copyx = $this->formatter->getMonth();
										echo '<option value="">Pilih Data</option>';
										echo '<option value="all_month">Semua Bulan</option>';
										foreach ($bulan_copyx as $bufx => $valfx) {
											echo '<option value="'.$bufx.'">'.$valfx.'</option>';
										}
										?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<label>Pilih Tahun</label>
								<?php
									$tahun_copyx = $this->formatter->getYear();
									$selsx = array(date('Y'));
									$exsx = array('class'=>'form-control select2', 'id'=>'search_tahunx', 'style'=>'width:100%;','required'=>'required');
									echo form_dropdown('tahun',$tahun_copyx,$selsx,$exsx);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<label>Pembetulan</label>
								<?php
									$koreksi_exp = $this->otherfunctions->getNumberToAbjadList();
									$selsxp = array('Pilih Data');
									$exsxp = array('class'=>'form-control select2', 'id'=>'export_koreksi', 'style'=>'width:100%;','required'=>'required');
									echo form_dropdown('koreksi',$koreksi_exp,$selsxp,$exsxp);
								?>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right" style="text-align: right;">
					<button type="button" class="btn btn-primary" onclick="do_rekap('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
			</div>
		</div>
	</div>
</div> -->
<div id="rekap_mode_new" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Mode</h4>
			</div>
			<div class="modal-body">
				<form id="form_print" class="form-horizontal">
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<label>Pilih Bulan</label>
								<select class="form-control select2" name="bulan" id="search_bulanx" style="width: 100%;" required="required">
									<?php
									$bulan_copyx = $this->formatter->getMonth();
									echo '<option value="">Pilih Data</option>';
									foreach ($bulan_copyx as $bufx => $valfx) {
										echo '<option value="'.$bufx.'">'.$valfx.'</option>';
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<label>Pilih Tahun</label>
								<?php
									$tahun_copyx = $this->formatter->getYear();
									$selsx = array(date('Y'));
									$exsx = array('class'=>'form-control select2', 'id'=>'search_tahunx', 'style'=>'width:100%;','required'=>'required');
									echo form_dropdown('tahun',$tahun_copyx,$selsx,$exsx);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<label>Pembetulan</label>
								<?php
									$koreksi_exp = $this->otherfunctions->getNumberToAbjadList();
									$selsxp = array('Pilih Data');
									$exsxp = array('class'=>'form-control select2', 'id'=>'export_koreksi', 'style'=>'width:100%;','required'=>'required');
									echo form_dropdown('koreksi',$koreksi_exp,$selsxp,$exsxp);
								?>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right" style="text-align: right;">
					<button type="button" class="btn btn-primary" onclick="do_rekap('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="rekap_mode_bagian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap PPh 21 Per Bagian</h4>
			</div>
			<div class="modal-body">
				<form id="form_print_bagian" class="form-horizontal">
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6">
								<label>Pilih Bagian</label>
							</div>
							<div class="col-md-6">
								<span id="bag_off_edit" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
								<span id="bag_on_edit" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
								<span style="padding-bottom: 9px;vertical-align: middle;"><b>Pilih Semua Bagian</b></span>
								<input type="hidden" name="all_bag" id="bag_edit">
							</div>
							<div class="col-md-12" id="div_bag_edit">
									<select class="form-control select2" name="bagian[]" id="search_bagiany" multiple="multiple" style="width: 100%;"></select>
							</div><br><br><br><br>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h4 class="text-center"><b>Pilih Tipe Rekap</b></h4>
							<div class="clearfix text-center">
								<div class="col-md-6">
									<span id="bulan_offy" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
									<span id="bulan_ony" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
									<span style="padding-bottom: 9px;vertical-align: middle;"><b>BULANAN</b></span>
								</div>
								<div class="col-md-6">
									<span id="tahun_offy" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
									<span id="tahun_ony" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
									<span style="padding-bottom: 9px;vertical-align: middle;"><b>TAHUNAN</b></span>
								</div>
								<input type="hidden" name="tipe_rekapy" id="tipe_rekapy">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-12">
								<div class="col-md-12">
									<div id="format_bulany" style="display:none">
										<div class="form-group">
											<label>Pilih Bulan</label>
											<select class="form-control select2" name="bulan" id="search_bulany" style="width: 100%;" required="required">
												<?php
												$bulan_copyy = $this->formatter->getMonth();
												echo '<option value="">Pilih Data</option>';
												echo '<option value="all_month">Semua Bulan</option>';
												foreach ($bulan_copyy as $bufy => $valfy) {
													echo '<option value="'.$bufy.'">'.$valfy.'</option>';
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-12">
								<div class="col-md-12">
									<div class="form-group">
										<label>Pilih Tahun</label>
										<?php
											$tahun_copyy = $this->formatter->getYear();
											$selsy = array(date('Y'));
											$exsy = array('class'=>'form-control select2', 'id'=>'search_tahuny', 'style'=>'width:100%;','required'=>'required');
											echo form_dropdown('tahun',$tahun_copyy,$selsy,$exsy);
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right" style="text-align: right;">
					<button type="button" class="btn btn-primary" onclick="do_rekap_bagian('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var jumlah_induk_t = <?=count($indukTunjanganTetap)?>; 
	var jumlah_induk = <?=count($indukTunjanganNon)?>; 
	$(document).ready(function(){
		var url_select="<?php echo base_url('global_control/select2_global');?>";
		select_data('data_sistem_penggajian_filter',url_select,'master_sistem_penggajian','kode_master_penggajian','nama');
		var bulan = "<?=date('m')?>";
		var tahun = "<?=date('Y')?>";
		table_data(bulan, tahun);
		$('#bulan_off').click(function(){
			$('#bulan_off').hide();
			$('#bulan_on').show();
			$('#tahun_on').hide();
			$('#tahun_off').show();
		    $('#format_bulan').show();
			$('input[name="tipe_rekap"]').val('1');
		})
		$('#bulan_on').click(function(){
			$('#bulan_off').show();
			$('#bulan_on').hide();
			$('#tahun_off').hide();
			$('#tahun_on').show();
		    $('#format_bulan').hide();
			$('input[name="tipe_rekap"]').val('0');
		});
		$('#tahun_off').click(function(){
			$('#tahun_off').hide();
			$('#tahun_on').show();
			$('#bulan_on').hide();
			$('#bulan_off').show();
		    $('#format_bulan').hide();
			$('input[name="tipe_rekap"]').val('0');
		});
		$('#tahun_on').click(function(){
			$('#tahun_off').show();
			$('#tahun_on').hide();
			$('#bulan_off').hide();
			$('#bulan_on').show();
		    $('#format_bulan').show();
			$('input[name="tipe_rekap"]').val('1');
		});
		$('#bulan_offy').click(function(){
			$('#bulan_offy').hide();
			$('#bulan_ony').show();
			$('#tahun_ony').hide();
			$('#tahun_offy').show();
		    $('#format_bulany').show();
			$('input[name="tipe_rekapy"]').val('1');
		})
		$('#bulan_ony').click(function(){
			$('#bulan_offy').show();
			$('#bulan_ony').hide();
			$('#tahun_offy').hide();
			$('#tahun_ony').show();
		    $('#format_bulany').hide();
			$('input[name="tipe_rekapy"]').val('0');
		});
		$('#tahun_offy').click(function(){
			$('#tahun_offy').hide();
			$('#tahun_ony').show();
			$('#bulan_ony').hide();
			$('#bulan_offy').show();
		    $('#format_bulany').hide();
			$('input[name="tipe_rekapy"]').val('0');
		});
		$('#tahun_ony').click(function(){
			$('#tahun_offy').show();
			$('#tahun_ony').hide();
			$('#bulan_offy').hide();
			$('#bulan_ony').show();
		    $('#format_bulany').show();
			$('input[name="tipe_rekapy"]').val('1');
		});
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'search_bagiany');
		$('#bag_off_edit').click(function(){
			$('#bag_off_edit').hide();
			$('#bag_on_edit').show();
			$('input[name="all_bag"]').val('1');
			$('#search_bagiany').removeAttr('required');
			$('#div_bag_edit').hide();
			$('#search_bagiany').val('').trigger('change');
		});
		$('#bag_on_edit').click(function(){
			$('#bag_off_edit').show();
			$('#bag_on_edit').hide();
			$('input[name="all_bag"]').val('0');
			$('#search_bagiany').attr('required','required');
			$('#div_bag_edit').show();
		});
		$('#pen_bulanx, #pen_tahunx').change(function(){
			var bl = $('#pen_bulanx').val();
			var th = $('#pen_tahunx').val();
			var data = {bulan:bl,tahun:th};
			var callback = getAjaxData("<?php echo base_url('cpayroll/data_pph_21_harian/karyawan/')?>", data);
			$('#karyawan_pen').html(callback['karyawan']);
		});
	});
   function change_option(kode) {
      if(kode == 1){
		   $('#format_bulan').show();
      }else{
		   $('#format_bulan').show();
      }
   }
	function table_filter() {
		var bulan = $('#search_bulan').val();
		var tahun = $('#search_tahun').val();
		table_data(bulan,tahun);
	}
	function table_data(bulan, tahun) {
		var koreksi = $('#search_koreksi').val();
		$('#table_data').DataTable().destroy();
		setTimeout(function () {
			$('#table_data').DataTable( {
				ajax: {
					url: "<?php echo base_url('cpayroll/data_pph_21_harian/view_all/')?>",
					type: 'POST',
					data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",bulan:bulan,tahun:tahun,koreksi:koreksi}
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
				{   targets: 40,
				// {   targets: 38+jumlah_induk_t+jumlah_induk,
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				]
			});
		},600); 
	}
	function refreshData() {
		$('#data_periode_filter').val('').trigger('change');
		$('#data_sistem_penggajian_filter').val('').trigger('change');
		setTimeout(function () {
			table_filter()
		},600); 
	}
	function view_modal(id) {
		var data={id_p_pph:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pph_21_harian/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama_karyawan']+' - '+callback['bulan']+' '+callback['tahun']+'');
		$('input[name="data_id_view"]').val(callback['id']);

		$('#data_nik_view').html(callback['nik']);
		$('#data_name_view').html(callback['nama_karyawan']);
		$('#data_jabatan_view').html(callback['kode_jabatan']);
		$('#data_bagian_view').html(callback['kode_bagian']);
		$('#data_loker_view').html(callback['kode_loker']);
		$('#data_grade_view').html(callback['kode_grade']);
		$('#data_gaji_pokok_view').html(callback['gaji_pokok']);
		$('#data_sistem_view').html(callback['nama_sistem_penggajian']);
		$('#data_tanggal_view').html(callback['tgl_masuk']);
		$('#data_masa_view').html(callback['masa_kerja']);
		$('#data_periode_view').html(callback['nama_periode']+' ( '+callback['nama_sistem_penggajian']+' )');
		$('#data_npwp_view').html(callback['no_npwp']);
		$('#data_ptkp_view').html(callback['status_ptkp']);
		$('#data_tunjangan_view').html(callback['tunjangan']);
		$('#data_uang_makan_view').html(callback['uang_makan']);
		$('#data_ritasi_view').html(callback['ritasi']);
		$('#data_lembur_view').html(callback['lembur']);
		$('#data_perdin_view').html(callback['perdin']);
		$('#data_kode_akun_view').html(callback['kode_akun']);
		$('#data_koreksi_view').html(callback['koreksi']);
		$('#data_bpjs_jkk_perusahaan_view').html(callback['bpjs_jkk_perusahaan']);
		$('#data_bpjs_jkm_perusahaan_view').html(callback['bpjs_jkm_perusahaan']);
		$('#data_bpjs_jht_perusahaan_view').html(callback['bpjs_jht_perusahaan']);
		$('#data_bpjs_kes_perusahaan_view').html(callback['bpjs_kes_perusahaan']);
		$('#data_iuran_pensiun_perusahaan_view').html(callback['iuran_pensiun_perusahaan']);
		$('#data_bpjs_pph_view').html(callback['bpjs_pph']);
		$('#data_premi_asuransi_view').html(callback['premi_asuransi']);
		$('#data_lainnya_view').html(callback['tambah_lainnya']);
		$('#data_bruto_sebulan_view').html(callback['bruto_sebulan']);
		$('#data_bruto_setahun_view').html(callback['bruto_setahun']);
		$('#data_biaya_jabatan_view').html(callback['biaya_jabatan']);
		$('#data_bpjs_jht_pekerja_view').html(callback['bpjs_jht_pekerja']);
		$('#data_iuran_pensiun_pekerja_view').html(callback['iuran_pensiun_pekerja']);
		$('#data_bpjs_jkk_pekerja_view').html(callback['bpjs_jkk_pekerja']);
		$('#data_bpjs_jkm_pekerja_view').html(callback['bpjs_jkm_pekerja']);
		$('#data_bpjs_kes_pekerja_view').html(callback['bpjs_kes_pekerja']);
		$('#data_piutang_view').html(callback['piutang']);
		$('#data_koreksi_absen_view').html(callback['koreksi_absen']);
		$('#data_denda_view').html(callback['denda']);
		$('#data_potongan_lain_view').html(callback['potongan_lain']);
		$('#data_jml_pengurang_view').html(callback['jml_pengurang']);
		// $('#data_netto_sebulan_view').html(callback['netto_sebulan']);
		// $('#data_netto_setahun_view').html(callback['netto_setahun']);
		// $('#data_pajak_setahun_view').html(callback['pajak_setahun']);
		$('#data_pph_setahun_view').html(callback['pph_setahun']);
		$('#data_pph_sebulan_view').html(callback['pph_sebulan']);
		$('#data_bulan_prd_view').html(callback['bulan']);
		$('#data_tahun_prd_view').html(callback['tahun']);
		$('#data_yg_diterima_view').html(callback['yg_diterima']);
		$('#data_bruto_sebulan_view').html(callback['bruto_sebulan']);
		$('#data_bruto_setahun_view').html(callback['bruto_setahun']);
		$('#data_biaya_jabatan_view').html(callback['biaya_jabatan']);
		$('#data_netto_sebulan_view').html(callback['netto_sebulan']);
		$('#data_netto_setahun_view').html(callback['netto_setahun']);
		$('#data_ptkp_sebulan_view').html(callback['ptkp_sebulan']);
		$('#data_ptkp_setahun_view').html(callback['ptkp_setahun']);
		$('#data_pkp_sebulan_view').html(callback['pkp_sebulan']);
		$('#data_pkp_setahun_view').html(callback['pkp_setahun']);
		$('#data_pph21sebulan_view').html(callback['pph21sebulan']);
		$('#data_pph21setahun_view').html(callback['pph21setahun']);
		$('#data_pajak_setahun_view').html(callback['pajak_setahun']);
		$('#data_pph21_dibayar_view').html(callback['pph21_dibayar']);
		$('#data_pph21_dipotong_view').html(callback['pph21_dipotong']);
		$('#data_tunjangan_pph_view').html(callback['tunjangan_pph']);

		$('#data_periode_view').html(callback['periode']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function do_add() {
		$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, sedang memproses data....');
		$('#progress2').show();
		var data = $('#form_add').serialize();
		var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_pph_harian')?>", data);
		if (cek_ready['msg'] == 'true') {
			submitAjax("<?php echo base_url('cpayroll/ready_data_pph_harian')?>", null, 'form_add', null, null);
			reload_table('table_data');
			$('#progress2').hide();
		} else if (cek_ready['msg'] == 'ada_data') {
			$('#progress2').hide();
			$('#confirm_ada_data').modal('show');
			$('#bulan_ada_data').val(cek_ready['bulan']);
			$('#tahun_ada_data').val(cek_ready['tahun']);
			$('#koreksi_ada_data').val(cek_ready['koreksi']);
		} else {
			$('#progress2').hide();
		}
	}
	function do_delete_ada_data() {
		submitAjax("<?php echo base_url('Cpayroll/del_ada_data_pph_harian')?>", 'confirm_ada_data', 'form_ada_data', null, null);
		$('#table_data').DataTable().ajax.reload(function () {
			Pace.restart();
		});
	}
	// function model_export() {
	// 	$('#rekap_mode').modal('show');
	// }
	function model_export() {
		$('#rekap_mode_new').modal('show');
	}
	function model_export_bagian() {
		$('#rekap_mode_bagian').modal('show');
	}

	function do_rekap(file) {
		var bulan = $('#search_bulanx').val();
		var tahun = $('#search_tahunx').val();
		var koreksi = $('#export_koreksi').val();
		if(file == 'excel'){
			$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_harian/"; ?>', 
			{
				bulan: bulan,
				tahun: tahun,
				koreksi: koreksi,
			}, "POST", "_blank");
		}
	}
	function do_rekap_bagian(file) {
		var tipe = $('#tipe_rekapy').val();
		var bagian = $('#search_bagiany').val();
		var bulan = $('#search_bulany').val();
		var tahun = $('#search_tahuny').val();
		var all_bag = $('#bag_edit').val();
		if(tipe == '1'){
			if(bulan == 'all_month'){
				if(file == 'excel'){
					$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_bagian_all/"; ?>', 
					{
						all_bag: all_bag,
						bagian: bagian,
						tipe: tipe,
						bulan: bulan,
						tahun: tahun,
					}, "POST", "_blank");
				}
			}else{
				if(file == 'excel'){
					$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_bagian/"; ?>', 
					{
						all_bag: all_bag,
						bagian: bagian,
						tipe: tipe,
						bulan: bulan,
						tahun: tahun,
					}, "POST", "_blank");
				}
			}
		}else{
			if(file == 'excel'){
				$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_bagian_tahunan/"; ?>', 
				{
					all_bag: all_bag,
					bagian: bagian,
					tipe: tipe,
					bulan: bulan,
					tahun: tahun,
				}, "POST", "_blank");
			}
		}
	}
	function model_export_bp_final() {
		var bulan = $('#search_bulan').val();
		var tahun = $('#search_tahun').val();
		var koreksi = $('#search_koreksi').val();
		$.redirect('<?php echo base_url()."rekap/rekap_data_bp_final_karyawan/"; ?>', 
		{
			bulan: bulan,
			tahun: tahun,
			koreksi: koreksi,
		}, "POST", "_blank");
	}
	function model_export_bp_final_pesangon() {
		var bulan = $('#search_bulan').val();
		var tahun = $('#search_tahun').val();
		var koreksi = $('#search_koreksi').val();
		$.redirect('<?php echo base_url()."rekap/rekap_data_bp_final_pesangon/"; ?>', 
		{
			bulan: bulan,
			tahun: tahun,
			koreksi: koreksi,
		}, "POST", "_blank");
	}
	function model_export_1721_bp_a1() {
		var bulan = $('#search_bulan').val();
		var tahun = $('#search_tahun').val();
		var koreksi = $('#search_koreksi').val();
		$.redirect('<?php echo base_url()."rekap/rekap_data_1721_bp_a1/"; ?>', 
		{
			bulan: bulan,
			tahun: tahun,
			koreksi: koreksi,
		}, "POST", "_blank");
	}
	function insertPenunjang() {
		var data={no_sk:null};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pph_21/insert_penunjang')?>",data);
		$('#tabel_end_proses').html(callback['tabel_end_proses']);
	}
	function myFunction() {
		var data={no_sk:null};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pph_21/insert_penunjang')?>",data);
		var table = document.getElementById("myTable");
		var row = table.insertRow(1);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		cell1.innerHTML = callback['select'];
		cell2.innerHTML = callback['nominal'];
	}
	function deleterow() {
		var table = document.getElementById("myTable");
		var row = table.deleteRow(1);
		var cell1 = row.deleteCell(0);
		var cell2 = row.deleteCell(1);
	}
  	function do_add_penunjang(){
		if($("#form_penunjang")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/add_penunjang_harian')?>",null,'form_penunjang',null,null);
			$('#table_data_trans').DataTable().ajax.reload();
		}else{
			notValidParamx();
		}
  	}
</script>