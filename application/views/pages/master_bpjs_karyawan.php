<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Master Data
			<small>Master BPJS</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Master BPJS</li>
		</ol>
	</section>
	<section class="content">
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
											if (in_array($access['l_ac']['imp'], $access['access'])) {
												?>
												<div class="row">
													<div class="col-md-2"></div>
													<div class="col-md-8">
														<div class="modal fade" id="import" role="dialog">
															<div class="modal-dialog">
																<div class="modal-content text-center">
																	<div class="modal-header">
																		<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
																		<h4 class="modal-title">Import Data Dari Excel</h4>
																	</div>
																	<form id="form_import" action="#">
																		<div class="modal-body">
																			<p style="color:red;">File Data Template Master Indikator harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
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
											<th>Jaminan Hari Tua</th>
											<th>Jaminan Kecelakaan Kerja</th>
											<th>Jaminan Kematian</th>
											<th>Jaminan Pensiun</th>
											<th>Jaminan Kesehatan</th>
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

<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="master_bpjs_karyawan";
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
         var urladd = "<?php echo base_url(); ?>master/import_k_bpjs";
         submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');

      });
		refreshCode();
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_k_bpjs/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo base64_encode(serialize($access));?>"}
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
			{   targets: 2,
				width: '15%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 3,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 4,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 10,
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 11, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
		$('#btn_tambah').click(function(){

		})
	});

	function refreshCode() {
		kode_generator("<?php echo base_url('master/master_k_bpjs/kode');?>",'data_kode_add');
	}
	function view_modal(id) { 
		$('#view div div div.modal-body div div div div').html('');
		$('#view').modal('show')
		var data={id_k_bpjs:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('master/master_k_bpjs/view_one')?>",data); ;
		$('.header_data').html(callback['nama']);

		$('#data_kode_view').html(callback['kode']);
		$('#data_karyawan_view').html(callback['nama']);
		$('#data_jht_view').html(callback['jht']);
		$('#data_jkk_view').html(callback['jkk']);
		$('#data_jkm_view').html(callback['jkm']);
		$('#data_jpns_view').html(callback['jpns']);
		$('#data_jkes_view').html(callback['jkes']);
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
		$('input[name="data_id_view"]').val(callback['id_karyawan']);
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
		var callback=getAjaxData("<?php echo base_url('master/master_k_bpjs/view_one')?>",data); 
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

	}
	function delete_modal(id) {
		var data={id_k_bpjs:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('master/master_k_bpjs/view_one')?>",data);
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
			submitAjax("<?php echo base_url('master/edt_k_bpjs')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_k_bpjs')?>",null,'form_add',"<?php echo base_url('master/master_k_bpjs/kode');?>",'data_kode_add');
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
      var data={data: null};
      window.location.href = "<?php echo base_url('rekap/export_data_bpjs_karyawan')?>?"+data;
   }

   function checkFile(idf,idt,btnx) {
      var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
      pathFile(idf,idt,fext,btnx);
   }
</script>