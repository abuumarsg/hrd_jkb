<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fa-gears"></i> Setting Aplikasi
			<small>Backup Restore Database</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Backup Restore Database</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#data_backup" onclick="table_data()" data-toggle="tab"><i class="fas fa-download"></i> Backup Database</a></li>
						<li><a href="#data_restore" onclick="table_data_restore()" data-toggle="tab"><i class="fas fa-upload"></i> Restore Database</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="data_backup">
							<div class="row">
								<div class="col-md-12">
									<div class="box-body">
										<div id="accordion">
											<div class="panel">
												<button href="#backup" data-toggle="collapse" data-parent="#accordion" class="btn btn-success" style="float: left;"><i class="fas fa-download"></i> Backup</button>
												<div class="box-tools pull-right">
													<button class="btn btn-box-tool" onclick="table_data()" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												</div>
												<br><br>
												<div id="backup" class="collapse">
													<div class="col-md-2"></diV>
													<div class="col-md-8">
														<div class="box">
															<div class="box-header with-border">
																<h3 class="box-title"><i class="fas fa-download"></i> Backup Database</h3>
															</div>
															<form id="form_backup" class="form-horizontal">
																<div class="box-body">
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Nama</label>
																				<div class="col-sm-9">
																					<input type="text" name="nama" class="form-control" placeholder="Nama" required="required" id="nama_db">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-sm-3 control-label">Pilih Jenis</label>
																				<div class="col-sm-9">
																					<select class="form-control select2" name="jenis" id="data_jenis_db" style="width: 100%;" required="required">
																						<option value="">Pilih Data</option>
																						<option value="custom">Custom</option>
																						<option value="kebutuhan">Keperluan Penggajian</option>
																						<option value="hasil">Hasil Penggajian</option>
																					</select>
																				</div>
																			</div>
																			<div class="form-group" id="custom_db" style="display:none;">
																				<label class="col-sm-3 control-label">Pilih Table Database</label>
																				<div class="col-sm-9">
																					<select class="form-control select2" name="tabel[]" multiple="multiple" id="data_tabel_db" style="width: 100%;">
																						<?php
																							echo '<option></option>';
																							echo '<option value="all">Pilih Semua</option>';
																							foreach ($list_table as $keylt => $vallt) {
																								echo '<option value="'.$vallt.'">'.$vallt.'</option>';
																							}
																						?>
																					</select>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="box-footer">
																	<div class="pull-right">
																		<button type="button" class="btn btn-success" onclick="backup()" title="Backup"><i class="fas fa-download"></i> Backup</button>
																	</div>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>Nama</b> Untuk mendownload file</div>
												<table id="table_data" class="table table-bordered table-striped" width="100%">
													<thead>
														<tr>
															<th>No</th>
															<th>Nama</th>
															<th>File</th>
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
						<div class="tab-pane" id="data_restore">
							<div class="row">
								<div class="col-md-12">
									<div class="box-body">
										<div id="accordion">
											<div class="panel">
												<button href="#restore" data-toggle="collapse" data-parent="#accordion" class="btn btn-info" style="float: left;"><i class="fas fa-upload"></i> Restore</button>
												<div class="box-tools pull-right">
													<button class="btn btn-box-tool" onclick="table_data_restore()" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												</div>
												<br><br>
												<div id="restore" class="collapse">
													<div class="col-md-2"></diV>
													<div class="col-md-8">
														<div class="box">
															<div class="box-header with-border">
																<h3 class="box-title"><i class="fas fa-download"></i> Restore Database</h3>
															</div>
															<form id="form_restore" class="form-horizontal">
																<div class="box-body">
																	<div class="row">
																		<div class="col-md-12">
																			<div class="col-md-1"></div>
																			<div class="col-md-10">
																				<div class="form-group">
																					<label>Nama</label>
																						<input type="text" name="nama" class="form-control" placeholder="Nama" required="required" id="nama_db_restore">
																				</div>
																				<p class="text-muted">File harus bertipe *.sql</p>
																				<div class="form-group">
																					<div class="input-group">
																						<label class="input-group-btn">
																							<span class="btn btn-primary"> 
																								<i class="fas fa-folder-open"></i> Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;" onchange="checkFile('#BSbtnsuccess',null,null,event)">
																							</span>
																						</label>
																						<input type="text" id="input_restore" class="form-control" readonly="readonly">
																						<!-- <input type="file" name="file" class="form-control"> -->
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="box-footer">
																	<div class="pull-right">
																		<button type="submit" class="btn btn-success" title="Restore"><i class="fas fa-upload"></i> Restore</button>
																	</div>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
													<ul>
														<li>Pilih <b>Nama</b> Untuk mendownload file
														</li>
														<li>Untuk Merestore data pada kolom aksi klik tombol <button type="button" class="btn btn-success btn-sm"><i class="fas fa-upload"></i></button> 
														</li>
													</ul>
												</div>
												<table id="table_data_restore" class="table table-bordered table-striped" width="100%">
													<thead>
														<tr>
															<th>No</th>
															<th>Nama</th>
															<th>File</th>
															<th>Status Restore</th>
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
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_name_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">File</label>
							<div class="col-md-6" id="data_kode_view"></div>
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
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit">
					<input type="hidden" id="data_id_edit" name="id" value="">
					<input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
					<div class="form-group">
						<label>Nama</label>
						<input type="text" placeholder="Masukkan Nama" id="data_name_edit" name="nama" value=""
							class="form-control" required="required">
					</div>
					<div class="form-group">
						<label>File</label>
						<input type="text" placeholder="Masukkan Kode" id="data_kode_edit" name="kode" value=""class="form-control" required="required" readonly="readonly">
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i
						class="fa fa-floppy-o"></i> Simpan</button>
				<button type="submit" id="btn_editx" style="display: none;"></button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
			</form>
		</div>

	</div>
</div>
<!-- view -->
<div id="view_restore" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_restore">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_name_restore"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">File</label>
							<div class="col-md-6" id="data_kode_restore"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_restore">

							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_restore"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_restore"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_restore">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_restore">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal_restore()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<!-- edit restore-->
<div id="edit_restore" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit_restore">
					<input type="hidden" id="data_id_edit_restore" name="id" value="">
					<div class="form-group">
						<label>Nama</label>
						<input type="text" placeholder="Masukkan Nama" id="data_name_edit_restore" name="nama" value=""
							class="form-control" required="required">
					</div>
					<div class="form-group">
						<label>File</label>
						<input type="text" placeholder="Masukkan Kode" id="data_kode_edit_restore" name="kode" value=""class="form-control" readonly="readonly">
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_edit_restore()" class="btn btn-success"><i
						class="fa fa-floppy-o"></i> Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
			</form>
		</div>

	</div>
</div>
<div id="view_restore_end" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Status Restore <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_restore">
			</div>
			<div class="modal-body">
				<form id="form_do_restore">
				<input type="hidden" id="data_id_do_restore" name="id" value="">
				<!-- <div class="form-group">
					<label>Nama</label>
					<input type="text" placeholder="Masukkan Nama" id="data_name_do_restore" name="nama" value=""
						class="form-control" readonly="readonly">
				</div>
				<div class="form-group">
					<label>File</label>
					<input type="text" placeholder="Masukkan Kode" id="data_kode_do_restore" name="kode" value=""class="form-control" readonly="readonly">
				</div> -->
				<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
					<ul>
						<li><b>Menambah Data ke Database</b> akan menambah data dari file backup dan <b>Menghapus</b> data yang di Database
						</li>
						<li><b>Memperbarui Data di Database</b> akan memperbarui data yang sudah ada di Database dengan data dari file Backup  
						</li>
					</ul>
				</div>
				<div class="form-group">
					<label>Pilih Status Restore</label>
					<select class="form-control select2" name="param" id="param_restore" style="width: 100%;">
						<option value="">Pilih Data</option>
						<option value="insert">Menambah Data ke Database</option>
						<option value="update">Memperbarui Data di Database</option>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_status_restore()" class="btn btn-success"><i
						class="fa fa-upload"></i> Restore</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- delete -->
<div id="modal_delete_partial"></div>
<div id="delete_modal_restore" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_restore">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete" name="column" value="id">
					<input type="hidden" id="data_id_delete" name="id">
					<input type="hidden" id="data_table_delete" name="table" value="data_restore">
          			<input type="hidden" id="data_file_restore" name="file">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete"
							class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_restore()" class="btn btn-primary"><i class="fa fa-trash"></i>
					Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  	var table = "data_backup";
  	var column = "id";
  	$(document).ready(function () {
		table_data();refreshAdd();
		$('#form_restore').submit(function(e){
			e.preventDefault();
			var data = new FormData(this);
			var urlx = "<?php echo base_url('master/import_restore'); ?>";
			submitAjaxFile(urlx,data,null,null,null);
			table_data_restore();
		});
		$('#data_jenis_db').change(function(){
         	var jenis = $('#data_jenis_db').val();
			if (jenis == 'custom') {
				$('#custom_db').show();
				$('#data_tabel_db').prop('required', true); 
			}else{
				$('#custom_db').hide();
				$('#data_tabel_db').prop('required', false); 
			}
      	})
	});
	$(function () {
		$(document).on('change', ':file', function () {
			var input = $(this),
				numFiles = input.get(0).files ? input.get(0).files.length : 1,
				label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [numFiles, label]);
		});
		$(document).ready(function () {
			$(':file').on('fileselect', function (event, numFiles, label) {
				var input = $(this).parents('.input-group').find(':text'),
					log = numFiles > 1 ? numFiles + ' files selected' : label;
				if (input.length) {
					input.val(log);
				} else {
					if (log) alert(log);
				}
			});
		});
	});
   function checkFile(idf,idt,btnx,event) {
      var fext = ['sql'];
      pathFile(idf,idt,fext,btnx);
   }
	function table_data() {
		$('#table_data').DataTable().destroy();
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('master/data_backup/view_all/')?>",
				type: 'POST',
				data: {
					access: "<?php echo base64_encode(serialize($access));?>"
				}
			},
			scrollX: true,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 1,
					width: '15%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 2,
					width: '15%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 3,
					width: '10%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 4,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 5,
					width: '7%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
	}
	function backup() {
		if ($("#form_backup")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/backup_db')?>", null, 'form_backup', null, null);
			table_data();refreshAdd();
		} else {
			notValidParamx();
		}
	}
	function view_modal(id) {
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_backup/view_one')?>", data);
		$('#view').modal('show');
		$('.header_data').html(callback['nama_val']);
		$('#data_kode_view').html(callback['file']);
		$('#data_name_view').html(callback['nama']);
		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date'] + ' WIB');
		$('#data_update_date_view').html(callback['update_date'] + ' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}

	function edit_modal() {
		$('#form_edit')[0].reset();
		var id = $('input[name="data_id_view"]').val();
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_backup/view_one')?>", data);
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		}, 600);
		$('.header_data').html(callback['nama_val']);
		$('#data_id_edit').val(callback['id']);
		$('#data_kode_edit_old').val(callback['file']);
		$('#data_kode_edit').val(callback['file']);
		$('#data_name_edit').val(callback['nama_val']);
	}

	function delete_modal(id) {
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_backup/view_one')?>", data);
		var datax = {
			table: table,
			column: column,
			id: id,
			nama: callback['nama_val'],
			file: callback['file_l'],
		};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>", 'modal_delete_partial', datax, 'delete');
	}
	function do_status(id, data) {
		var data_table = {
			status: data
		};
		var where = {
			id: id
		};
		var datax = {
			table: table,
			where: where,
			data: data_table
		};
		submitAjax("<?php echo base_url('global_control/change_status')?>", null, datax, null, null, 'status');
		table_data();
	}

	function do_edit() {
		if ($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edit_backup_db')?>", 'edit', 'form_edit', null, null);
			table_data();
		} else {
			notValidParamx();
		}
	}
  	function refreshAdd() {
		$('#nama_db').val('');
		$('#data_tabel_db').val('').trigger('change');
	}
	
	// ==================================================== RESTORE =================================================
	function table_data_restore() {
		$('#table_data_restore').DataTable().destroy();
		$('#table_data_restore').DataTable({
			ajax: {
				url: "<?php echo base_url('master/data_restore/view_all/')?>",
				type: 'POST',
				data: {
					access: "<?php echo base64_encode(serialize($access));?>"
				}
			},
			scrollX: true,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 5,
					width: '7%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 6,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
	}
	function view_modal_restore(id) {
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_restore/view_one')?>", data);
		$('#view_restore').modal('show');
		$('.header_data').html(callback['nama_val']);
		$('#data_kode_restore').html(callback['file']);
		$('#data_name_restore').html(callback['nama']);
		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_restore').html(statusval);
		$('#data_create_date_restore').html(callback['create_date'] + ' WIB');
		$('#data_update_date_restore').html(callback['update_date'] + ' WIB');
		$('input[name="data_id_restore"]').val(callback['id']);
		$('#data_create_by_restore').html(callback['nama_buat']);
		$('#data_update_by_restore').html(callback['nama_update']);
	}

	function edit_modal_restore() {
		$('#form_edit_restore')[0].reset();
		var id = $('input[name="data_id_restore"]').val();
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_restore/view_one')?>", data);
		$('#view_restore').modal('toggle');
		setTimeout(function () {
			$('#edit_restore').modal('show');
		}, 600);
		$('.header_data').html(callback['nama_val']);
		$('#data_id_edit_restore').val(callback['id']);
		$('#data_kode_edit_old_restore').val(callback['file']);
		$('#data_kode_edit_restore').val(callback['file']);
		$('#data_name_edit_restore').val(callback['nama_val']);
	}
	function do_edit_restore() {
		if ($("#form_edit_restore")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edit_restore_db')?>", 'edit_restore', 'form_edit_restore', null, null);
			table_data_restore();
		} else {
			notValidParamx();
		}
	}

	function delete_modal_restore(id) {
		var data = { id: id };
		var callback = getAjaxData("<?php echo base_url('master/data_restore/view_one')?>", data);
		$('#delete_modal_restore').modal('show');
		$('#delete_modal_restore .header_data').html(callback['nama_val']);
		$('#delete_modal_restore #data_id_delete').val(callback['id']);
		$('#delete_modal_restore #data_file_restore').val(callback['file_l']);
	}
	function do_delete_restore(){
		submitAjax("<?php echo base_url('global_control/delete')?>",'delete_modal_restore','form_delete_restore',null,null);
		table_data_restore();
	}
	function do_status_restore(id, data) {
		var data_table = {
			status: data
		};
		var where = {
			id: id
		};
		var datax = {
			table: 'data_restore',
			where: where,
			data: data_table
		};
		submitAjax("<?php echo base_url('global_control/change_status')?>", null, datax, null, null, 'status');
		table_data_restore();
	}
	function status_restore(id) {
		var data = {id: id};
		var callback = getAjaxData("<?php echo base_url('master/data_restore/view_one')?>", data);
		$('#view_restore_end').modal('show');
		$('.header_data').html(callback['nama_val']);
		$('#data_id_do_restore').val(callback['id']);
		$('#data_name_do_restore').val(callback['nama_val']);
		$('#data_kode_do_restore').val(callback['file']);
	}
	function do_status_restore(id) {
		if ($("#form_do_restore")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/restore_db')?>", 'view_restore_end', 'form_do_restore', null, null);
			table_data_restore();
		} else {
			notValidParamx();
		}
	}
</script>