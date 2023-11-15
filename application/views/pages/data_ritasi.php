<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Penggajian
			<small>Data Ritasi</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Data Ritasi</li>
		</ol>
	</section>
	<section class="content">
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
															<span class="fa fa-caret-down"></span></button>
															<ul class="dropdown-menu">
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
													<div class="col-md-2"></div>
													<div class="col-md-8">
														<form id="form_add">
															<div class="form-group">
																<label>Karyawan</label>
																<select class="form-control select2" name="karyawan" id="data_karyawan_add" style="width: 100%;"></select>
															</div>
															<div class="form-group">
																<label>Jumlah RIT</label>
																<input type="number" placeholder="Masukkan RIT" id="data_rit_add" name="rit" class="form-control field" required="required">
															</div>
															<div class="form-group">
																<label>Nominal</label>
																<input type="text" placeholder="Masukkan nominal" id="data_nominal_add" name="nominal" class="form-control field input-money" required="required">
															</div>
															<div class="form-group">
																<label>Pilih Periode Penggajian</label>
																<select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;">
																	<?php
																	$periode = $this->model_master->getListPeriodePenggajian();
																	echo '<option></option>';
																	foreach ($periode as $p) {
																		echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
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
												<th>Jumlah RIT</th>
												<th>Nominal</th>
												<th>Periode</th>
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
		</section>
	</div> 
	<div class="modal fade" id="import" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content text-center">
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
							</ul>
						</div>
						<div class="form-group">
							<label>Pilih Periode Penggajian</label>
							<select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;">
								<?php
									$periode = $this->model_master->getListPeriodePenggajian();
									echo '<option></option>';
									foreach ($periode as $p) {
										echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
									}
								?>
							</select>
						</div>
						<p class="text-muted">File Data Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
						<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
						<span class="input-group-btn">
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
								<label class="col-md-6 control-label">Jumlah Rit</label>
								<div class="col-md-6" id="data_rit_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Nominal</label>
								<div class="col-md-6" id="data_nominal_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Periode</label>
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
						echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
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
						<input type="hidden" id="data_id_edit" name="id" value="">
						<!-- <input type="hidden" id="data_kode_edit_old" name="kode_old" value=""> -->
						<div class="form-group">
							<label>RIT</label>
							<input type="number" placeholder="Masukkan RIT" id="data_rit_edit" name="rit" value="" class="form-control" required="required">
						</div>
						<div class="form-group">
							<label>Nominal</label>
							<input type="text" placeholder="Masukkan Nominal" id="data_nominal_edit" name="nominal" value="" class="form-control input-money" required="required">
						</div>
						<div class="form-group">
							<label>Pilih Periode Penggajian</label>
							<select class="form-control select2" name="periode" id="data_periode_edit" style="width: 100%;">
								<?php
									$periode = $this->model_master->getListPeriodePenggajian();
									echo '<option></option>';
									foreach ($periode as $p) {
										echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
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
	<div id="modal_delete_partial"></div>
	<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script type="text/javascript">
		var url_select="<?php echo base_url('global_control/select2_global');?>";
		var table="data_ritasi";
		var column="id_ritasi";
		$(document).ready(function(){
			$('#table_data').DataTable( {
				ajax: {
					url: "<?php echo base_url('master/data_ritasi/view_all/')?>",
					type: 'POST',
					data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
				},
				scrollX: true,
				columnDefs: [
				{   targets: 0, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+(meta.row+1)+'.</center>';
					}
				},
				{   targets: 7,
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
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
			$('#btn_tambah').click(function(){
				var key = $('#key_btn_tambah').val();
				if(key == 0){
					select_data('data_karyawan_add',url_select,'karyawan','id_karyawan','nama');
					$('#data_karyawan_add').val([]).trigger('change');
					$('#key_btn_tambah').val('1');
				}else { $('#key_btn_tambah').val('0'); }
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
		function view_modal(id) {
			var data={id_ritasi:id, mode: 'view'};
			var callback=getAjaxData("<?php echo base_url('master/data_ritasi/view_one')?>",data);  
			$('#view').modal('show');
			$('.header_data').html(callback['nama']);

			$('#data_nik_view').html(callback['nik']);
			$('#data_name_view').html(callback['nama']);
			$('#data_rit_view').html(callback['rit']);
			$('#data_nominal_view').html(callback['nominal']);
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
			$('#data_periode_edit').val(callback['periode']).trigger('change');
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
	</script>