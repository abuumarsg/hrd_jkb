	<!-- <section class="content"> -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data Ritasi</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<form id="form_filter">
								<div class="col-md-12">
									<div class="col-md-5">
										<div class="form-group">
											<label>Minggu</label>
											<?php
												$minggu_add = $this->otherfunctions->listWeekRitasi();
												$selm_add = array();
												$exm_add = array('class' => 'form-control select2', 'id' => 'minggu_search', 'style' => 'width:100%;', 'required' => 'required');
												echo form_dropdown('minggu', $minggu_add, $selm_add, $exm_add);
											?>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label>Periode Penggajian</label>
												<select class="form-control select2" name="periode" id="data_periode_cari" style="width: 100%;">
													<?php
														// $periode = $this->model_master->getListPeriodePenggajian('active',['a.status_gaji'=>'1']);
														// status gaji selesai
														// $wherex=['a.status_gaji'=>0,'a.status'=>1,'a.kode_master_penggajian'=>'BULANAN'];
														$wherex=['a.status'=>1,'a.kode_master_penggajian'=>'BULANAN'];
														$periode = $this->model_master->getPeriodePenggajian($wherex);
														echo '<option></option>';
														foreach ($periode as $p) {
															echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
														}
													?>
												</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="pull-left" style="padding-top:26px">
												<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
											</div>
										</div>
									</div>
								</div>
							</div><hr>
						</form>
						<div class="row">
							<div class="col-md-12">
								<div id="accordion">
									<div class="panel">
										<div class="row">
											<div class="col-md-12">
												<div class="pull-left">
													<?php 
													if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<a href="#add" data-toggle="collapse" id="btn_tambah"  data-parent="#accordion" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data Ritasi</a>';
													}
													?>
													<div class="btn-group">
														<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export & Import
															<span class="fa fa-caret-down"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a onclick="cetakData()">Export Data Ritasi</a></li>
															<li><a href="<?php echo base_url('rekap/export_template_ritasi');?>">Export Template Ritasi</a></li>
															<li><a data-toggle="modal" data-target="#import">Import Data Ritasi</a></li>
														</ul>
													</div>
												</div>
												<div class="pull-right" style="font-size: 8pt;">
													<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
													<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
												</div>
											</div>
										</div>
										<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
											<input type="hidden" id="key_btn_tambah" value="0">
											<div class="collapse" id="add">
												<form id="form_add">
													<div class="col-md-12">
														<div class="callout callout-info text-left">
															<b><i class="fa fa-bullhorn"></i> Perhatian</b><br>
															<ul>
																<li>Jika anda menambahkan data baru untuk Nama Karyawan di Minggu dan Periode yang sama, maka data akan di ganti dengan data baru yang baru di input.</li>
																<li>Mohon teliti sebelum menyimpan data, karena data yang sudah disimpan tidak dapat diubah periode gaji maupun periode ritasi.</li>
																<li>Data Ritasi yang sudah <b>DIVALIDASI</b> Tidak dapat diubah</li>
															</ul>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label>Karyawan</label>
																<select class="form-control select2" name="karyawan" id="data_karyawan_add" style="width: 100%;"></select>
															</div>
															<div class="form-group">
																<label>Jumlah RIT PPN</label>
																<input type="number" placeholder="Masukkan RIT PPN" id="data_rit_add" name="rit" class="form-control field" required="required">
															</div>
															<div class="form-group">
																<label>Nominal PPN</label>
																<input type="text" placeholder="Masukkan nominal PPN" id="data_nominal_add" name="nominal" class="form-control field input-money" required="required">
															</div>
															<div class="form-group">
																<label>Jumlah RIT NON-PPN</label>
																<input type="number" placeholder="Masukkan RIT NON-PPN" id="data_rit_non_add" name="rit_non" class="form-control field" required="required">
															</div>
															<div class="form-group">
																<label>Nominal NON-PPN</label>
																<input type="text" placeholder="Masukkan nominal NON-PPN" id="data_nominal_non_add" name="nominal_non" class="form-control field input-money" required="required">
															</div>
															<!-- <small class="text-muted"><font color="red">Jika anda memilih Periode yang sama dengan periode yang telah dibuat sebelumnya, maka data akan otomatis terakumulasi dengan data yang baru.</font></small><br><br> -->
														</div>
														<div class="col-md-6">
															<!-- <div class="form-group">
																<label>Tanggal</label>
																<div class="has-feedback">
																	<span class="fa fa-calendar form-control-feedback"></span>
																	<input type="text" class="form-control date-range-notime" id="tanggal_filter_kar" name="tanggal_filter_kar" placeholder="Tanggal">
																</div>
															</div> -->
															<div class="form-group">
																<label>Minggu</label>
																<?php
																	$minggu_add = $this->otherfunctions->listWeekRitasi();
																	$selm_add = array();
																	$exm_add = array('class' => 'form-control select2', 'id' => 'minggu_add', 'style' => 'width:100%;', 'required' => 'required');
																	echo form_dropdown('minggu', $minggu_add, $selm_add, $exm_add);
																?>
															</div>
															<!-- <div class="form-group">
																<label>Tanggal Mulai - Selesai</label>
																<input type="text" name="tanggal" id="tanggal_ritasi_add" class="form-control date-range-notime" placeholder="Tanggal Ritasi" required="required" readonly="readonly">
															</div> -->
															<div class="form-group">
																<label>Pilih Periode Penggajian</label>
																<select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;">
																	<?php
																	$wherexx=['a.status_gaji'=>0,'a.status'=>1,'a.kode_master_penggajian'=>'BULANAN'];
																	$periodexx = $this->model_master->getPeriodePenggajian($wherexx);
																	echo '<option></option>';
																	foreach ($periodexx as $p) {
																		echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
																	}
																	?>
																</select>
															</div>
															<div class="form-group">
																<label>Keterangan</label>
																<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
															</div>
														</div>
													</form>
												</div><hr>
												<div class="col-md-12">
													<div class="form-group">
														<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
													</div><br><br>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>NIK</th>
											<th>Nama</th>
											<th>RUPIAH PPN</th>
											<th>RUPIAH NON-PPN</th>
											<th>RITASI PPN</th>
											<th>RITASI NON-PPN</th>
											<!-- <th>Keterangan</th> -->
											<th>Minggu</th>
											<th>Periode</th>
											<th>Validasi</th>
											<!-- <th>Tanggal</th> -->
											<!-- <th>Status</th>  -->
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
	<!-- </section> -->
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
								<li>Gunakan File Template Excel Data Ritasi yang telah anda Download dari <b>"Export Template Ritasi"</b></li>
								<li>Jika anda menambahkan data baru untuk nama dan periode yang sama, maka data akan di ganti dengan data baru yang baru di input.</li>
								<li>Data Ritasi yang sudah <b>DIVALIDASI</b> Tidak dapat diubah</li>
							</ul>
						</div>
						<div class="form-group">
							<label>Minggu</label>
							<?php
								$minggu_imp = $this->otherfunctions->listWeekRitasi();
								$selm_imp = array();
								$exm_imp = array('class' => 'form-control select2', 'id' => 'minggu_imp', 'style' => 'width:100%;', 'required' => 'required');
								echo form_dropdown('minggu', $minggu_imp, $selm_imp, $exm_imp);
							?>
						</div>
						<div class="form-group">
							<label>Pilih Periode Penggajian</label>
							<select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;">
								<?php
								$wherex=['a.status_gaji'=>0,'a.status'=>1,'a.kode_master_penggajian'=>'BULANAN'];
								$periode = $this->model_master->getPeriodePenggajian($wherex);
								echo '<option></option>';
								foreach ($periode as $p) {
									echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
								}
								?>
							</select>
						</div>
						<p class="text-muted text-center">File Data Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
						<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
						<span class="input-group-btn text-center">
							<div class="fileUpload btn btn-warning btn-flat">
								<span><i class="fa fa-folder-open"></i> Pilih File</span>
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
								<label class="col-md-6 control-label">Jumlah Rit PPN</label>
								<div class="col-md-6" id="data_rit_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Nominal PPN</label>
								<div class="col-md-6" id="data_nominal_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Jumlah Rit NON-PPN</label>
								<div class="col-md-6" id="data_rit_non_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Nominal NON-PPN</label>
								<div class="col-md-6" id="data_nominal_non_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Minggu</label>
								<div class="col-md-6" id="data_minggu_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Periode</label>
								<div class="col-md-6" id="data_periode_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Keterangan</label>
								<div class="col-md-6" id="data_keterangan_view"></div>
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
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div id="data_tabel_view"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
						echo '<button type="submit" class="btn btn-info" id="btn_edit_view" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
					}?>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
	<div id="edit" class="modal fade" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
				</div>
				<form id="form_edit">
					<div class="modal-body">
						<div class="callout callout-info text-left">
							<b><i class="fa fa-bullhorn"></i> Perhatian</b><br>
							<ul>
								<li>Jika anda menambahkan data baru untuk Nama Karyawan di Minggu dan Periode yang sama, maka data akan di ganti dengan data baru yang baru di input.</li>
							</ul>
						</div>
						<input type="hidden" id="data_id_edit" name="id" value="">
						<div class="form-group">
							<label>RIT PPN</label>
							<input type="number" placeholder="Masukkan RIT PPN" id="data_rit_edit" name="rit" value="" class="form-control" required="required">
						</div>
						<div class="form-group">
							<label>Nominal PPN</label>
							<input type="text" placeholder="Masukkan Nominal PPN" id="data_nominal_edit" name="nominal" value="" class="form-control input-money" required="required">
						</div>
						<div class="form-group">
							<label>RIT NON-PPN</label>
							<input type="number" placeholder="Masukkan RIT NON-PPN" id="data_rit_non_edit" name="rit_non" value="" class="form-control" required="required">
						</div>
						<div class="form-group">
							<label>Nominal NON-PPN</label>
							<input type="text" placeholder="Masukkan Nominal NON-PPN" id="data_nominal_non_edit" name="nominal_non" value="" class="form-control input-money" required="required">
						</div>
						<!-- <div class="form-group">
							<label>Minggu</label>
							<?php
							// 	$minggu_add = $this->otherfunctions->listWeekRitasi();
							// 	$selm_add = array();
							// 	$exm_add = array('class' => 'form-control select2', 'id' => 'minggu_edit', 'style' => 'width:100%;', 'required' => 'required');
							// 	echo form_dropdown('minggu', $minggu_add, $selm_add, $exm_add);
							// ?>
						</div>
						<div class="form-group">
							<label>Pilih Periode Penggajian</label>
							<select class="form-control select2" name="periode" id="data_periode_edit" style="width: 100%;">
								<?php
									// $periode = $this->model_master->getListPeriodePenggajian();
									// echo '<option></option>';
									// foreach ($periode as $p) {
									// 	echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
									// }
								?>
							</select>
						</div> -->
						<div class="form-group">
							<label>Keterangan</label>
							<textarea name="keterangan" id="keterangan_edit" class="form-control" placeholder="Keterangan"></textarea>
						</div>
						<!-- <small class="text-muted"><font color="red">Jika anda memilih Periode yang sama dengan periode yang telah dibuat sebelumnya, maka data akan otomatis terakumulasi dengan data yang baru.</font></small><br> -->
					</div>
					<div class="modal-footer">
						<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php if (in_array($access['l_ac']['val_lembur'], $access['access'])) { ?>
		<div id="m_need" class="modal fade" role="dialog">
			<div class="modal-dialog modal-sm modal-default">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-center">Validasi Ritasi</h4>
					</div>
					<form id="form_need">
						<div class="modal-body text-center">
							<input type="hidden" id="data_id_need" name="id">
							<input type="hidden" id="data_idk_need" name="id_kar">
							<input type="hidden" id="data_jenis_need" name="jenis">
							<p>Mohon Validasi Ritasi Karyawan atas nama <b id="data_name_need" class="header_data"></b> <span id="data_ket_need"></span> berikut !!</p>
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
						<h4 class="modal-title text-center">Validasi Ritasi</h4>
					</div>
					<form id="form_yes">
						<div class="modal-body text-center">
							<input type="hidden" id="data_id_yes" name="id">
							<input type="hidden" id="data_idk_yes" name="id_kar">
							<p>Apakah Anda yakin akan mengubah status Ritasi dari <b class="text-green">DiIzinkan</b> menjadi <b class="text-red">Tidak Diizinkan</b></b> atas nama karyawan <b id="data_name_yes" class="header_data"></b> <span id="data_ket_yes"></span>??</p>
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
						<h4 class="modal-title text-center">Validasi Ritasi</h4>
					</div>
					<form id="form_no">
						<div class="modal-body text-center">
							<input type="hidden" id="data_id_no" name="id">
							<input type="hidden" id="data_idk_no" name="id_kar">
							<input type="hidden" id="data_jenis_no" name="jenis">
							<p>Apakah Anda yakin akan mengubah status Ritasi dari <b class="text-red">Tidak Diizinkan</b> menjadi <b class="text-green">DiIzinkan</b></b> atas nama karyawan <b id="data_name_no" class="header_data"></b> <span id="data_ket_no"></span> ??</p>
						</div>
					</form>
					<div class="modal-footer">
						<button type="button" onclick="do_validasi(0,1,'m_no')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div id="modal_delete_partial"></div>
	<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script type="text/javascript">
		var url_select="<?php echo base_url('global_control/select2_global');?>";
		var table="data_ritasi";
		var column="id_ritasi";
		$(document).ready(function(){
			tableData('all');
			$('#btn_tambah').click(function(){
				getSelect2("<?php echo base_url('master/master_pinjaman/employee')?>",'data_karyawan_add');
				// var key = $('#key_btn_tambah').val();
				// if(key == 0){
				// 	select_data('data_karyawan_add',url_select,'karyawan','id_karyawan','nama');
				// 	$('#data_karyawan_add').val([]).trigger('change');
				// 	$('#key_btn_tambah').val('1');
				// }else { $('#key_btn_tambah').val('0'); }
			})
			$('#save').click(function(){
				$('.all_btn_import').attr('disabled','disabled');
				$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....')
				setTimeout(function () {
					$('#savex').click();
				},1000);
			})
			$('#form_import').submit(function(e){
				e.preventDefault();
				var data_add = new FormData(this);
				var urladd = "<?php echo base_url('master/import_ritasi'); ?>";
				submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
				$('#table_data').DataTable().ajax.reload(function (){
					Pace.restart();
				});
			});
		});
		function tableData(kode) {
			var periode = $('#data_periode_cari').val();
			var minggu = $('#minggu_search').val();
			$('#table_data').DataTable( {
				ajax: {
					url: "<?php echo base_url('master/data_ritasi/view_all/')?>",
					type: 'POST',
					data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",periode:periode,minggu:minggu,kode:kode}
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
					width: '9%',
					render: function ( data, type, full, meta ) {
						return data;
					}
				},
				{   targets: 9,
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 10, 
					width: '7%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				]
			});
		}
		function view_modal(id) {
			var data={id_ritasi:id, mode: 'view'};
			var callback=getAjaxData("<?php echo base_url('master/data_ritasi/view_one')?>",data);  
			$('#view').modal('show');
			$('.header_data').html(callback['nama']);
			$('#data_nik_view').html(callback['nik']);
			$('#data_name_view').html(callback['nama']);
			$('#data_rit_view').html(callback['rit']);
			$('#data_nominal_view').html(callback['nominal']);
			$('#data_rit_non_view').html(callback['rit_non']);
			$('#data_nominal_non_view').html(callback['nominal_non']);
			$('#data_periode_view').html(callback['periode']);
			$('#data_minggu_view').html(callback['minggu']);
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
			$('#data_status_view').html(statusval);
			$('#data_create_date_view').html(callback['create_date']+' WIB');
			$('#data_update_date_view').html(callback['update_date']+' WIB');
			$('input[name="data_id_view"]').val(callback['id']);
			$('#data_create_by_view').html(callback['nama_buat']);
			$('#data_update_by_view').html(callback['nama_update']);
			$('#data_tabel_view').html(callback['tabel']);
		}
		function edit_modal() {
			var id = $('input[name="data_id_view"]').val();
			var data={id_ritasi:id, mode: 'edit'};
			var callback=getAjaxData("<?php echo base_url('master/data_ritasi/view_one')?>",data); 
			$('#view').modal('toggle');
			setTimeout(function () {
				$('#edit').modal('show');
			},600); 
			$('.header_data').html(callback['nama']+' ('+callback['nik']+')');
			$('#data_id_edit').val(callback['id']);
			$('#data_rit_edit').val(callback['rit']);
			$('#data_nominal_edit').val(callback['nominal']);
			$('#data_rit_non_edit').val(callback['rit_non']);
			$('#data_nominal_non_edit').val(callback['nominal_non']);
			$('#minggu_edit').val(callback['e_minggu']).trigger('change');
			$('#data_periode_edit').val(callback['periode']).trigger('change');
			$('#keterangan_edit').val(callback['eketerangan']);
		}
		function delete_modal(id) {
			var data={id_ritasi:id};
			var callback=getAjaxData("<?php echo base_url('master/data_ritasi/view_one')?>",data);
			var datax={table:table,column:column,id:id,nama:callback['nama']};
			loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
		}
		function do_status(id,data) {
			var data_table={status:data};
			var where={id_ritasi:id};
			var datax={table:table,where:where,data:data_table};
			submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
			$('#table_data').DataTable().ajax.reload();
		}
		function do_edit(){
			if($("#form_edit")[0].checkValidity()) {
				submitAjax("<?php echo base_url('master/edt_ritasi')?>",'edit','form_edit',null,null);
				$('#table_data').DataTable().ajax.reload();
			}else{
				notValidParamx();
			} 
		}
		function do_add(){
			if($("#form_add")[0].checkValidity()) {
				submitAjax("<?php echo base_url('master/add_ritasi')?>",null,'form_add',null,null);
				$('#table_data').DataTable().ajax.reload(function (){
					Pace.restart();
				});
				$('#form_add')[0].reset();
				$('#data_karyawan_add').val('').trigger('change');
				$('#data_periode_add').val('').trigger('change');
			}else{
				notValidParamx();
			} 
		} 
		function checkFile(idf,idt,btnx) {
			var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
			pathFile(idf,idt,fext,btnx);
		}
		function modal_need(id) {
			var data={id_ritasi:id};
			$('#m_need').modal('toggle');
			var callback=getAjaxData("<?php echo base_url('master/data_ritasi/view_one')?>",data);
			$('#m_need #data_id_need').val(callback['id']);
			$('#m_need #data_idk_need').val(callback['id_karyawan']);
			$('#m_need .header_data').html(callback['nama']);
			$('#data_ket_need').html(callback['minggu_periode']);
		}
		function modal_yes(id) {
			var data={id_ritasi:id};
			$('#m_yes').modal('toggle');
			var callback=getAjaxData("<?php echo base_url('master/data_ritasi/view_one')?>",data);
			$('#m_yes #data_id_yes').val(callback['id']);
			$('#m_yes #data_idk_yes').val(callback['id_karyawan']);
			$('#m_yes .header_data').html(callback['nama']);
			$('#data_ket_yes').html(callback['minggu_periode']);
		}
		function modal_no(id) {
			var data={id_ritasi:id};
			$('#m_no').modal('toggle');
			var callback=getAjaxData("<?php echo base_url('master/data_ritasi/view_one')?>",data);
			$('#m_no #data_id_no').val(callback['id']);
			$('#m_no #data_idk_no').val(callback['id_karyawan']);
			$('#m_no .header_data').html(callback['nama']);
			$('#data_ket_no').html(callback['minggu_periode']);
		}
		function do_validasi(data,val,form){
			if(data==2){
				var id = $('#data_id_need').val();
				var idk = $('#data_idk_need').val();
			}else if(data==1){
				var id = $('#data_id_yes').val();
				var idk = $('#data_idk_yes').val();
			}else if(data==0){
				var id = $('#data_id_no').val();
				var idk = $('#data_idk_no').val();
			}
			var datax={id_ritasi:id,id_karyawan:idk,validasi_db:data,validasi:val};
			submitAjax("<?php echo base_url('master/validasi_ritasi')?>",form,datax,null,null,'status');
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
		}
		function cetakData(){
			var periode = $('#data_periode_cari').val();
			var minggu = $('#minggu_search').val();
			$.redirect("<?php echo base_url('rekap/export_data_ritasi'); ?>", {
				periode:periode,
				minggu:minggu,
				form: $('#form_filter').serialize(),
			}, "POST");
			// $.redirect("<?php //echo base_url('rekap/export_data_ritasi'); ?>", {
			// 	periode:periode,
			// 	minggu:minggu,
			// 	form: $('#form_filter').serialize(),
			// }, "POST", "_blank");
		}
	</script>