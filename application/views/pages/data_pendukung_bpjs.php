		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Master BPJS</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<form id="form_filter_bpjs">
									<div class="box-body">
										<div class="col-md-3">
											<div class="form-group">
												<label>Bagian</label>
												<select class="form-control select2" id="bagian_filter" name="bagian" style="width: 100%;"></select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Lokasi Kerja</label>
												<select class="form-control select2" id="lokasi_filter" name="lokasi" style="width: 100%;"></select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Bulan</label>
												<?php
													$bulan_ser = $this->formatter->getMonth();
													$sel_ser = array(date('m'));
													$ex_ser = array('class'=>'form-control select2', 'id'=>'bulan_ser', 'style'=>'width:100%;','required'=>'required');
													echo form_dropdown('bulan',$bulan_ser,$sel_ser,$ex_ser);
												?>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Tahun</label>
												<?php
													$tahun_ser = $this->formatter->getYear();
													$sels = array(date('Y'));
													$exs = array('class'=>'form-control select2', 'id'=>'tahun_ser', 'style'=>'width:100%;','required'=>'required');
													echo form_dropdown('tahun',$tahun_ser,$sels,$exs);
												?>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<button type="button" onclick="refresh_tabel('bpjs','search')" class="btn btn-success pull-right"><i class="fa fa-eye"></i> Lihat Data</button>
									</div>
								</form>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="pull-left">
											<?php 
											if (in_array($access['l_ac']['add'], $access['access'])) {
												echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right:5px;"><i class="fa fa-plus"></i> Tambah BPJS</button>';
											}
											if (in_array($access['l_ac']['imp'], $access['access'])) {
												echo '<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import" style="margin-right: 2px;"><i class="fas fa-cloud-upload-alt"></i> Import</button>';
											} 
											?>
											<?php 
											if (in_array($access['l_ac']['rkp'], $access['access'])) { 
												?>
												<div class="btn-group">
													<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export
														<span class="fa fa-caret-down"></span>
													</button>
													<ul class="dropdown-menu">
														<li><a href="<?php echo base_url('rekap/export_template_bpjs_karyawan');?>">Export Template</a></li>
														<li><a onclick="rekap()">Export Data BPJS Karyawan</a></li>
													</ul>
												</div>
											<?php }
											if (in_array($access['l_ac']['gnr'], $access['access'])) { 
												?>
												<div class="btn-group">
													<button type="button" class="btn btn-info dropdown-toggle" data-toggle="modal"  data-target="#generate_sistem"><i class="fas fa-refresh"></i> Generate BPJS </button>
												</div>
												<!-- <div class="btn-group">
													<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Generate BPJS
														<span class="fa fa-caret-down"></span>
													</button>
													<ul class="dropdown-menu">
														<li><a data-toggle="modal" data-target="#generate_sistem"> Generate Dari Master</a></li>
														<li><a data-toggle="modal" data-target="#generate_data"> Generate Dari Data Sebelumnya</a></li>
													</ul>
												</div> -->
											<?php } ?>
										</div>
										<div class="pull-right" style="font-size: 8pt;">
											<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
											<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
										</div>
									</div>
								</div>
								<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
									<div class="collapse" id="add">
										<input type="hidden" id="key_btn_tambah" value="0">
										<br>
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<form id="form_add">
												<div class="form-group">
													<label>Kode BPJS</label>
													<input type="text" placeholder="Masukkan Kode BPJS Karyawan" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
												</div>
												<div class="form-group">
													<label>Karyawan</label>
													<select class="form-control select2" name="karyawan" id="data_karyawan_add" style="width: 100%;" required="required">
														<option></option>
														<?php
														$emp = $this->model_karyawan->getEmployeeAllActive();
														foreach ($emp as $e) {
															$nama_jabatan = $e->nama_jabatan;
															if(empty($e->nama_jabatan)){
																$nama_jabatan = 'Tidak Ada Jabtan';
															}
															echo '<option value="'.$e->id_karyawan.'">'.$e->nama.' ('.$nama_jabatan.')</option>';
														}

														?>
													</select>
												</div>
												<div class="form-group">
													<label>BPJS Jaminan Hari Tua (JHT)</label>
													<input type="text" id="data_jht_add" name="jht" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
												</div>
												<div class="form-group">
													<label>BPJS Jaminan Kecelakaan Kerja (JKK)</label>
													<input type="text" id="data_jkk_add" name="jkk" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
												</div>
												<div class="form-group">
													<label>BPJS Jaminan Kematian (JKM)</label>
													<input type="text" id="data_jkm_add" name="jkm" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
												</div>
												<div class="form-group">
													<label>BPJS Jaminan Pensiun (JPNS)</label>
													<input type="text" id="data_jpns_add" name="jpns" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
												</div>
												<div class="form-group">
													<label>BPJS Jaminan Kesehatan (JKES)</label>
													<input type="text" id="data_jkes_add" name="jkes" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
												</div>
												<div class="form-group">
													<label>Bulan</label>
													<select class="form-control select2" name="bulan" id="data_bulan_add" style="width: 100%;" required="required">
														<?php
															$bulan = $this->formatter->getMonth();
															echo '<option></option>';
															foreach ($bulan as $bdf => $vdf) {
																echo '<option value="'.$bdf.'">'.$vdf.'</option>';
															}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Tahun</label>
													<select class="form-control select2" name="tahun" id="data_tahun_add" style="width: 100%;" required="required">
														<?php
														$year = $this->formatter->getYear();
														echo '<option></option>';
														foreach ($year as $key => $value) {
															echo '<option value="'.$value.'">'.$value.'</option>';
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
												</div>
											</form>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<!-- Data Begin Here -->
								<div class="callout callout-info"><label><i class="fas fa-exclamation-triangle"></i> Peringatan</label><br>Jika ada data baru yang sama, harap matikan status data lama.</div>
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>Kode</th>
											<th>Karyawan</th>
											<th>Gaji Perhitungan BPJS KES</th>
											<th>Gaji Perhitungan BPJS TK</th>
											<th>Jaminan Hari Tua (<?=$jht?>)%</th>
											<th>Jaminan Kecelakaan Kerja (<?=$jkk?>)%</th>
											<th>Jaminan Kematian (<?=$jkm?>)%</th>
											<th>Jaminan Pensiun (<?=$jpns?>)%</th>
											<th>Jaminan Kesehatan (<?=$jkes?>)%</th>
											<th>Bulan</th>
											<th>Tahun</th>
											<th>Tanggal</th>
											<th>Status</th> 
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
<?php if (in_array($access['l_ac']['imp'], $access['access'])) { ?>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="modal fade" id="import" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Import Data Dari Excel</h4>
						</div>
						<form id="form_import" action="#">
							<div class="modal-body">
								<div class="row">
									<div class="col-md-6">
										<p><b>Generate Untuk :</b></p>
									</div>
									<div class="col-md-6"></div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Pilih Bulan</label>
											<select class="form-control select2" name="bulan_for[]" id="data_bulan_for" style="width: 100%;" required="required" multiple="multiple">
												<?php
												$bulan_for = $this->formatter->getMonth();
												echo '<option value="all">Pilih Semua</option>';
												foreach ($bulan_for as $buf => $valf) {
													echo '<option value="'.$buf.'">'.$valf.'</option>';
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Pilih Tahun</label>
											<select class="form-control select2" name="tahun_for" id="data_tahun_for" style="width: 100%;" required="required">
												<?php
												$years_for = $this->formatter->getYear();
												echo '<option></option>';
												foreach ($years_for as $keyf => $valuef) {
													echo '<option value="'.$valuef.'">'.$valuef.'</option>';
												}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row text-center">
									<div class="col-md-12">
										<p style="color:red;">File Data Template Import Perhitungan BPJS harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
										<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
										<span class="input-group-btn">
											<div class="fileUpload btn btn-warning btn-flat">
												<span><i class="fa fa-folder-open"></i> Pilih File</span>
												<input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
											</div>
										</span>                              
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div id="progress2" style="float: left;"></div>
								<button class="btn btn-flat btn-primary all_btn_import" id="save" type="button" disabled><i class="fa fa-check-circle"></i> Upload</button>
								<button id="savex" type="submit" style="display: none;"></button>
								<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!-- GENERATE BPJS SISTEM -->
<div class="modal fade" id="generate_sistem" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Generate Data BPJS dari master</h4>
			</div>
			<form id="form_generate_sistem" action="#">
				<div class="modal-body">
					<!-- <div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Pilih Tahun</label>
								<select class="form-control select2" name="tahun" id="data_tahun_generate_master" style="width: 100%;" required="required">
									<?php
									// $years = $this->formatter->getYear();
									// echo '<option></option>';
									// foreach ($years as $keys => $values) {
									// 	echo '<option value="'.$values.'">'.$values.'</option>';
									// }
									?>
								</select>
							</div>
						</div>
					</div> -->
					<div class="row">
						<div class="col-md-6">
							<p><b>Generate Untuk :</b></p>
						</div>
						<div class="col-md-6"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Pilih Bulan</label>
								<select class="form-control select2" name="bulan_for[]" id="data_bulan_for" style="width: 100%;" required="required" multiple="multiple">
									<?php
									$bulan_for = $this->formatter->getMonth();
									echo '<option value="all">Pilih Semua</option>';
									foreach ($bulan_for as $buf => $valf) {
										echo '<option value="'.$buf.'">'.$valf.'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Pilih Tahun</label>
								<select class="form-control select2" name="tahun_for" id="data_tahun_for" style="width: 100%;" required="required">
									<?php
									$years_for = $this->formatter->getYear();
									echo '<option></option>';
									foreach ($years_for as $keyf => $valuef) {
										echo '<option value="'.$valuef.'">'.$valuef.'</option>';
									}
									?>
								</select>
							</div>
						</div>
					</div>
				</div> 
				<div class="modal-footer">
					<div id="progress2" style="float: left;"></div>
					<button class="btn btn-primary all_btn_import" type="button" onclick="do_generate_system()" style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Generate</button>
					<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- GENERATE BPJS DATA DATA SEBELUMNYA -->
<!-- <div class="modal fade" id="generate_data" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Generate Data BPJS dari Data Sebelumnya</h4>
			</div>
			<form id="form_generate_data" action="#">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Pilih Bulan</label>
								<select class="form-control select2" name="bulan" id="bulan_data" style="width: 100%;" required="required">
									<?php
									// $bData = $this->formatter->getMonth();
									// echo '<option></option>';
									// foreach ($bData as $bkey => $vkey) {
									// 	echo '<option value="'.$bkey.'">'.$vkey.'</option>';
									// }
									?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Pilih Tahun</label>
								<select class="form-control select2" name="tahun" id="tahun_data" style="width: 100%;" required="required">
									<?php
									// $yData = $this->formatter->getYear();
									// echo '<option></option>';
									// foreach ($yData as $ykey => $yval) {
									// 	echo '<option value="'.$yval.'">'.$yval.'</option>';
									// }
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<p><b>Generate Untuk :</b></p>
						</div>
						<div class="col-md-6"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Pilih Bulan</label>
								<select class="form-control select2" name="bulan_for" style="width: 100%;" required="required">
									<?php
									// $bData_for = $this->formatter->getMonth();
									// echo '<option></option>';
									// foreach ($bData_for as $bdf => $vdf) {
									// 	echo '<option value="'.$bdf.'">'.$vdf.'</option>';
									// }
									?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Pilih Tahun</label>
								<select class="form-control select2" name="tahun_for" style="width: 100%;" required="required">
									<?php
									// $yData_for = $this->formatter->getYear();
									// echo '<option></option>';
									// foreach ($yData_for as $ktf => $vtf) {
									// 	echo '<option value="'.$vtf.'">'.$vtf.'</option>';
									// }
									?>
								</select>
							</div>
						</div>
					</div>
				</div> 
				<div class="modal-footer">
					<div id="progress2" style="float: left;"></div>
					<button class="btn btn-primary all_btn_import" type="button" onclick="do_generate_data()" style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Generate</button>
					<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div> -->
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
							<label class="col-md-6 control-label">Kode BPJS</label>
							<div class="col-md-6" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_karyawan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jaminan Hari Tua</label>
							<div class="col-md-6" id="data_jht_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jaminan Kecelakaan Kerja</label>
							<div class="col-md-6" id="data_jkk_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jaminan Kematian</label>
							<div class="col-md-6" id="data_jkm_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jaminan Pensiun</label>
							<div class="col-md-6" id="data_jpns_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jaminan Kesehatan</label>
							<div class="col-md-6" id="data_jkes_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bulan</label>
							<div class="col-md-6" id="data_bulan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tahun</label>
							<div class="col-md-6" id="data_tahun_view"></div>
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
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit">
				<div class="modal-body">
					<input type="hidden" id="data_id_edit" name="id" value="">
					<input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
					<div class="form-group">
						<label>Kode BPJS</label>
						<input type="text" placeholder="Masukkan Kode BPJS Karyawan" id="data_kode_edit" name="kode" class="form-control" required="required" value="" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Karyawan</label>
						<select class="form-control select2" name="karyawan" id="data_karyawan_edit" style="width: 100%;">
							<option></option>
							<?php
							$emp = $this->model_karyawan->getEmployeeAllActive();
							foreach ($emp as $e) {
								$nama_jabatan = $e->nama_jabatan;
								if(empty($e->nama_jabatan)){
									$nama_jabatan = 'Tidak Ada Jabtan';
								}
								echo '<option value="'.$e->id_karyawan.'">'.$e->nama.' ('.$nama_jabatan.')</option>';
							}

							?>
						</select>
					</div>
					<div class="form-group">
						<label>BPJS Jaminan Hari Tua (JHT)</label>
						<input type="text" id="data_jht_edit" name="jht" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
					</div>
					<div class="form-group">
						<label>BPJS Jaminan Kecelakaan Kerja (JKK)</label>
						<input type="text" id="data_jkk_edit" name="jkk" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
					</div>
					<div class="form-group">
						<label>BPJS Jaminan Kematian (JKM)</label>
						<input type="text" id="data_jkm_edit" name="jkm" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
					</div>
					<div class="form-group">
						<label>BPJS Jaminan Pensiun (JPNS)</label>
						<input type="text" id="data_jpns_edit" name="jpns" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
					</div>
					<div class="form-group">
						<label>BPJS Jaminan Kesehatan (JKES)</label>
						<input type="text" id="data_jkes_edit" name="jkes" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
					</div>
					<div class="form-group">
						<label>Bulan</label>
						<select class="form-control select2" name="bulan" id="data_bulan_edit" style="width: 100%;" required="required">
							<?php
								$bulan = $this->formatter->getMonth();
								echo '<option></option>';
								foreach ($bulan as $bdf => $vdf) {
									echo '<option value="'.$bdf.'">'.$vdf.'</option>';
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Tahun</label>
						<select class="form-control select2" name="tahun" id="data_tahun_edit" style="width: 100%;">
							<?php
							$year = $this->formatter->getYear();
							echo '<option></option>';
							foreach ($year as $key => $value) {
								echo '<option value="'.$value.'">'.$value.'</option>';
							}
							?>
						</select>
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
<?php $this->load->view('_partial/_loading') ?>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_bpjs";
	var column="id_k_bpjs";
	$(document).ready(function(){
		$('#import').modal({
			show: false,
			backdrop: 'static',
			keyboard: false
		}) 
		$('#save').click(function(){
			$('.all_btn_import').attr('disabled','disabled');
			$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Menunggu, data sedang di upload....')
			setTimeout(function () {
				$('#savex').click();
			},1000);
		})
		$('#form_import').submit(function(e){
			e.preventDefault();
			var data_add = new FormData(this);
			var urladd = "<?php echo base_url(); ?>rekap/import_data_bpjs";
			submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
		});
		refreshCode();
      	refresh_tabel('bpjs','all');
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_filter');
		select_data('lokasi_filter',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_filter',['BAG001','BAG002']);
	});
   	function refresh_tabel(kode,usage) {
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('cpayroll/data_bpjs/view_all/')?>",
				type: 'POST',
				data:{
					form:$('#form_filter_bpjs').serialize(),
					access:"<?php echo base64_encode(serialize($access));?>",
					usage:usage,
					}
			},
			scrollX: true,
         	bDestroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 13,
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 14, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('cpayroll/data_bpjs/kode');?>",'data_kode_add');
	}
	function view_modal(id) { 
		$('#view div div div.modal-body div div div div').html('');
		$('#view').modal('show')
		var data={id_k_bpjs:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_bpjs/view_one')?>",data); ;
		$('.header_data').html(callback['nama']);

		$('#data_kode_view').html(callback['kode']);
		$('#data_karyawan_view').html(callback['nama']);
		$('#data_jht_view').html(callback['jht']);
		$('#data_jkk_view').html(callback['jkk']);
		$('#data_jkm_view').html(callback['jkm']);
		$('#data_jpns_view').html(callback['jpns']);
		$('#data_jkes_view').html(callback['jkes']);
		$('#data_bulan_view').html(callback['bulan']);
		$('#data_tahun_view').html(callback['tahun']);

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
	}
	function edit_modal() {
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		var id = $('input[name="data_id_view"]').val();
		var data={id_k_bpjs:id, mode: 'edit'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_bpjs/view_one')?>",data); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);

		$('#data_kode_edit_old').val(callback['kode']);
		$('#data_kode_edit').val(callback['kode']);
		$('#data_karyawan_edit').val(callback['id_karyawan']).trigger('change');
		$('#data_jht_edit').val(callback['jht']);
		$('#data_jkk_edit').val(callback['jkk']);
		$('#data_jkm_edit').val(callback['jkm']);
		$('#data_jpns_edit').val(callback['jpns']);
		$('#data_jkes_edit').val(callback['jkes']);

		$('#data_tahun_edit').val(callback['tahun']).trigger('change');
		$('#data_bulan_edit').val(callback['ebulan']).trigger('change');

	}
	function delete_modal(id) {
		var data={id_k_bpjs:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_bpjs/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
		$('#data_form_table').val('#table_data');
	}
	/*doing db transaction*/
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_k_bpjs:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/edt_data_bpjs')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/add_data_bpjs')?>",null,'form_add',"<?php echo base_url('cpayroll/data_bpjs/kode');?>",'data_kode_add');
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			$('#data_karyawan_add').val('').trigger('change');
			$('#data_tahun_add').val('').trigger('change');
			refreshCode();
		}else{
			notValidParamx();
		} 
	}
   	function rekap() {
		var data=$('#form_filter_bpjs').serialize();
      	window.location.href = "<?php echo base_url('rekap/export_data_bpjs_karyawan')?>?"+data;
   	}
   function checkFile(idf,idt,btnx) {
      var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
      pathFile(idf,idt,fext,btnx);
   }
	function do_generate_system(){
		$('#generate_sistem').modal('hide');
		show_loader();
		// var tahun = $('#data_tahun_generate_master').val();
		$('#loading_progress div div div p b').html('Mohon tunggu, sedang mengenerate BPJS dari master...');
		var data={status: 1};
		var cek_ready=getAjaxData("<?php echo base_url('master/generateBPJS/cekData')?>",data);  
		if(cek_ready['msg'] == 'ada_data'){
			submitAjax("<?php echo base_url('master/generateBPJS/data')?>", null,'form_generate_sistem',null,null);
			reload_table('table_data');
			$('#loading_progress').modal('hide');
		} else if(cek_ready['msg'] == 'kosong'){
			$('#loading_progress').modal('hide');
			submitAjax("<?php echo base_url('master/generateBPJS/notif')?>",null,null,null,null,'status');
		}else{
			$('#loading_progress').modal('hide');
		}
	} 
	function do_generate_data(){
		$('#generate_sistem').modal('hide');
		show_loader();
		var bulan = $('#bulan_data').val();
		var tahun = $('#tahun_data').val();
		$('#loading_progress div div div p b').html('Mohon tunggu, sedang mengenerate BPJS dari data sebelumnya...');
		var data={tahun:tahun, bulan:bulan};
		var cek_ready=getAjaxData("<?php echo base_url('cpayroll/generateBPJS/cekData')?>",data);  
		if(cek_ready['msg'] == 'ada_data'){
			submitAjax("<?php echo base_url('cpayroll/generateBPJS/data')?>", null,'form_generate_data',null,null);
			reload_table('table_data');
			$('#loading_progress').modal('hide');
		} else if(cek_ready['msg'] == 'kosong'){
			$('#loading_progress').modal('hide');
			submitAjax("<?php echo base_url('cpayroll/generateBPJS/notifDataBPJS')?>",null,null,null,null,'status');
		}else{
			$('#loading_progress').modal('hide');
		}
	} 
</script>