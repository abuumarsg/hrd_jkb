<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Master Data
			<small>Master Insentif</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Master Insentif</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Master Insentif</h3>
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
											<?php if (in_array($access['l_ac']['add'], $access['access'])) {
												echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Insentif</button>';
											}?>
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
													<label>Kode Insentif</label>
													<input type="text" placeholder="Masukkan Kode Periode Penggajian" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
												</div>
												<div class="form-group">
													<label>Nama Insentif</label>
													<input type="text" placeholder="Masukkan Nama Periode Penggajian" id="data_name_add" name="nama" class="form-control field" required="required">
												</div>
												<div class="form-group">
													<label>Nominal</label>
													<input type="text" placeholder="Masukkan Nominal Tunjangan" id="data_nominal_add" name="nominal" min="0" value="Rp. 0" step="0.01" class="form-control field input-money" required="required">
												</div>
												<div class="form-group">
													<label>Tahun</label>
													<select class="form-control select2" name="tahun" id="data_tahun_add" style="width: 100%;" required="required">
														<?php
														$year = $this->formatter->getYear('2010');
														echo '<option></option>';
														foreach ($year as $key => $value) {
															echo '<option value="'.$value.'">'.$value.'</option>';
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Data Insentif Lama</label>
													<select class="form-control select2" name="old_insentif" id="data_insentif_add" style="width: 100%;" onchange="insentif_lama(this.value,'data_karyawan_add')"></select>
												</div>
												<div class="form-group">
													<label>Pilih Karyawan</label>
													<select class="form-control select2-notclear" name="karyawan[]" id="data_karyawan_add" style="width: 100%;" multiple="multiple" required="required">
														<?php
														echo '<option value="all">Pilih Semua</option>';
														$emp = $this->model_karyawan->getEmployeeAllActive();
														foreach ($emp as $e) {
															echo '<option value="'.$e->id_karyawan.'">'.$e->nama.'( '.$e->nama_jabatan.' )</option>';
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Keterangan</label>
													<textarea name="keterangan" id="data_keterangan_add" class="form-control field" placeholder="Keterngan"></textarea>
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
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>Kode Insentif</th>
											<th>Nama</th>
											<th>Nominal</th>
											<th>Tahun</th>
											<th>Jumlah Karyawan</th>
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
							<label class="col-md-6 control-label">Kode Insentif</label>
							<div class="col-md-6" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_name_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal</label>
							<div class="col-md-6" id="data_nominal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tahun</label>
							<div class="col-md-6" id="data_tahun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
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
					<div class="col-md-12">
						<hr>
						<div style="text-align: center;"><h3>Detail Data</h3></div>
						<table class="table table-bordered table-striped table-responsive" id="table_data_detail" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Jabatan</th>
									<th>Bagian</th>
									<th>Lokasi Kerja</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
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
						<label>Kode Insentif</label>
						<input type="text" placeholder="Masukkan Kode Periode Penggajian" id="data_kode_edit" name="kode" class="form-control" required="required" value="" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Nama Insentif</label>
						<input type="text" placeholder="Masukkan Nama Periode Penggajian" id="data_name_edit" name="nama" class="form-control field" required="required">
					</div>
					<div class="form-group">
						<label>Nominal</label>
						<input type="text" placeholder="Masukkan Nominal Tunjangan" id="data_nominal_edit" name="nominal" min="0" value="Rp. 0" step="0.01" class="form-control field input-money" required="required">
					</div>
					<div class="form-group">
						<label>Tahun</label>
						<select class="form-control select2" name="tahun" id="data_tahun_edit" style="width: 100%;" required="required">
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
						<label>Data Insentif Lama</label>
						<select class="form-control select2" name="old_insentif" id="data_insentif_edit" style="width: 100%;" onchange="insentif_lama(this.value,'data_karyawan_edit')"></select>
					</div>
					<div class="form-group">
						<label>Pilih Karyawan</label>
						<select class="form-control select2-notclear" name="karyawan[]" id="data_karyawan_edit" style="width: 100%;" multiple="multiple" required="required">
							<?php
							echo '<option value="all">Pilih Semua</option>';
							$emp = $this->model_karyawan->getEmployeeAllActive();
							foreach ($emp as $e) {
								echo '<option value="'.$e->id_karyawan.'">'.$e->nama.'( '.$e->nama_jabatan.' )</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea name="keterangan" id="data_keterangan_edit" class="form-control field" placeholder="Keterngan"></textarea>
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

<input type="hidden" id="usage_karyawan_add" value="0">
<input type="hidden" id="usage_karyawan_edit" value="0">
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	/*wajib diisi*/
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="master_insentif";
	var column="id_insentif";
	$(document).ready(function(){
		$('#table_data_detail').DataTable();
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_insentif/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo base64_encode(serialize($access));?>"}
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
			{   targets: 7,
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			/*aksi*/
			{   targets: 8, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
		$('#btn_tambah').click(function(){
			// var key = $('#key_btn_tambah').val();
			// if(key == 0){
				refreshCode();
				select_data('data_insentif_add',url_select,'master_insentif','kode_insentif','nama');
				$('#data_karyawan_add').val([]).trigger('change');
				$('#key_btn_tambah').val('1');
			// }else { $('#key_btn_tambah').val('0'); }
		})

		$('#data_karyawan_add').on('select2:close', function (evt) {
			var uldiv = $(this).siblings('span.select2').find('ul')
			var count = uldiv.find('li').length - 1;
			var all_count = '<?php echo count($emp); ?>';
			if(count == 0){
				$('#data_karyawan_add').val([]).trigger('change');
				$('#reset_karyawan_add').hide();
			}
			if(count > 0){
				$('#reset_karyawan_add').show();
			}
			if(count == all_count){
				$('#check_karyawan_add_on').show();
				$('#check_karyawan_add_off').hide();
			}
			if(count < all_count){
				$('#check_karyawan_add_on').hide();
				$('#check_karyawan_add_off').show();
			}
		});
		$('#data_karyawan_edit').on('select2:close', function (evt) {
			var uldiv = $(this).siblings('span.select2').find('ul')
			var count = uldiv.find('li').length - 1;
			var all_count = '<?php echo count($emp); ?>';
			if(count == 0){
				$('#data_karyawan_edit').val([]).trigger('change');
				$('#reset_karyawan_edit').hide();
			}
			if(count > 0){
				$('#reset_karyawan_edit').show();
			}
			if(count == all_count){
				$('#check_karyawan_edit_on').show();
				$('#check_karyawan_edit_off').hide();
			}
			if(count < all_count){
				$('#check_karyawan_edit_on').hide();
				$('#check_karyawan_edit_off').show();
			}
		});
	});
	function refreshCode() {
		kode_generator("<?php echo base_url('master/master_insentif/kode');?>",'data_kode_add');
	}
	function insentif_lama(kode, send) {
		if(kode != ''){
			var data={kode_insentif:kode};
			var callback=getAjaxData("<?php echo base_url('master/master_insentif/periode_lama')?>",data);  
			if(kode == ''){
				$('#'+send).val([]).trigger('change');
			}else{
				$('#'+send).val(callback).trigger('change');
			}
		}
	}
	function view_modal(id) {
		if($('#key_btn_tambah').val() == 1){
			$('#btn_tambah').click();
		}
		var data={id_insentif:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('master/master_insentif/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_kode_view').html(callback['kode_insentif']);
		$('#data_name_view').html(callback['nama']);
		$('#data_nominal_view').html(callback['nominal']);
		$('#data_tahun_view').html(callback['tahun']);
		$('#data_keterangan_view').html(callback['keterangan']);
  	// $('#data_karyawan_view').html(callback['id_karyawan']);

  	$('#table_data_detail').DataTable().destroy();
  	$('#table_data_detail tbody').html(callback['id_karyawan']);
  	$('#table_data_detail').DataTable();

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
  	var id = $('input[name="data_id_view"]').val();
  	var data={id_insentif:id, mode: 'edit'};
  	var callback=getAjaxData("<?php echo base_url('master/master_insentif/view_one')?>",data); 
  	$('#view').modal('toggle');
  	setTimeout(function () {
  		$('#edit').modal('show');
  	},600); 
  	$('.header_data').html(callback['nama']);
  	$('#data_id_edit').val(callback['id']);
  	$('#data_kode_edit_old').val(callback['kode_insentif']);
  	$('#data_kode_edit').val(callback['kode_insentif']);
  	$('#data_name_edit').val(callback['nama']);
  	$('#data_nominal_edit').val(callback['nominal']);
  	$('#data_keterangan_edit').html(callback['keterangan']);
  	$('#data_tahun_edit').val(callback['tahun']).trigger('change');

  	$('#data_karyawan_edit').val(callback['id_karyawan']).trigger('change');
  	select_data('data_insentif_edit',url_select,'master_insentif','kode_insentif','nama');
  }
  function delete_modal(id) {
  	var data={id_insentif:id, mode: 'view'};
  	var callback=getAjaxData("<?php echo base_url('master/master_insentif/view_one')?>",data);
  	var datax={table:table,column:column,id:id,nama:callback['nama']};
  	loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  /*doing db transaction*/
  function do_status(id,data) {
  	var data_table={status:data};
  	var where={id_insentif:id};
  	var datax={table:table,where:where,data:data_table};
  	submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
  	$('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
  	if($("#form_edit")[0].checkValidity()) {
  		submitAjax("<?php echo base_url('master/edt_insentif')?>",'edit','form_edit',null,null);
  		$('#table_data').DataTable().ajax.reload();
  	}else{
  		notValidParamx();
  	} 
  }
  function do_add(){
  	if($("#form_add")[0].checkValidity()) {
  		submitAjax("<?php echo base_url('master/add_insentif')?>",null,'form_add',"<?php echo base_url('master/master_insentif/kode');?>",'data_kode_add');
  		$('#table_data').DataTable().ajax.reload(function(){
  			Pace.restart();
  		});
  		$('#form_add')[0].reset();
  		$('#data_insentif_add').val(null).trigger('change');
  		$('#data_karyawan_add').val([]).trigger('change');
  		select_data('data_insentif_add',url_select,'master_insentif','kode_insentif','nama');
  		refreshCode();
  	}else{
  		notValidParamx();
  	} 
  }
  function on_checkall(usage) {
  	var usage_add = $('#usage_karyawan_'+usage).val();
  	if(usage_add == '1'){
  		$('#usage_karyawan_'+usage).val('0');
  	}else{
  		$('#usage_karyawan_'+usage).val('1');
  		var data={table: 'karyawan', value: 'id_karyawan'};
  		var callback=getAjaxData("<?php echo base_url('master/master_insentif/check_all')?>",data);
  		$('#data_karyawan_'+usage).val(callback).trigger('change');
  		$('#reset_karyawan_'+usage).show();
  		$('#check_karyawan_'+usage+'_off').hide();
  		$('#check_karyawan_'+usage+'_on').show();
  	}
  }
  function reset_select2_multiple(usage) {
  	$('#check_karyawan_'+usage+'_off').show();
  	$('#check_karyawan_'+usage+'_on').hide();
  	$('#data_karyawan_'+usage).val([]).trigger('change');
  	$('#reset_karyawan_'+usage).hide();
  }
</script>
