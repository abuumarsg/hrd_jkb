<style type="text/css">
	table#table_data thead tr th, table#table_data tbody tr td, table.DTFC_Cloned thead tr th{
		white-space: pre;
	}
	/*table.DTFC_Cloned tbody{
		overflow: hidden;
	}*/
</style>
	<!-- <section class="content"> -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Setting Tunjangan</h3>
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
														echo '<a href="#add" data-toggle="collapse" id="btn_tambah"  data-parent="#accordion" class="btn btn-success" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data Tunjangan</a>';
													}
													if (in_array($access['l_ac']['exp'], $access['access'])) {
														echo '<button type="button" class="btn btn-warning" onclick="model_export()"><i class="fas fa-file-excel-o"></i> Export</button>';
													}
													?>
													
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
												<br>
												<div class="col-md-2"></div>
												<div class="col-md-8">
													<form id="form_add">
														<div class="form-group">
															<label>Kode Induk Tunjangan</label>
															<input type="text" placeholder="Masukkan Kode Induk Tunjangan" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
														</div>
														<div class="form-group">
															<label>Nama Tunjangan</label>
															<input type="text" placeholder="Masukkan Nama Induk Tunjangan" id="data_name_add" name="nama" class="form-control field" required="required">
														</div>
														<div class="form-group">
															<label>Sifat Tunjangan</label>
															<select class="form-control select2" name="sifat" id="data_sifat_add" style="width: 100%;" required="required">
																<option></option>
																<option value="0">Tidak Tetap</option>
																<option value="1">Tetap</option>
															</select>
														</div>
														<div class="form-group">
															<label>Periode Diberikan</label>
															<select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;" required="required">
																<option></option>
																<option value="0">Bersama Gaji Pokok</option>
																<option value="1">Sendiri</option>
															</select>
														</div>
														<div class="form-group">
															<label>Penambahan PPH</label>
															<select class="form-control select2" name="pph" id="data_pph_add" style="width: 100%;" required="required">
																<option></option>
																<option value="0">Tidak</option>
																<option value="1">Ya</option>
															</select>
														</div>
														<div class="form-group">
															<label>Penambahan Upah</label>
															<select class="form-control select2" name="upah" id="data_upah_add" style="width: 100%;" required="required">
																<option></option>
																<option value="0">Tidak</option>
																<option value="1">Ya</option>
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
  											<th>Kode Induk Tunjangan</th>
  											<th>Nama Induk Tunjangan</th>
  											<th>Sifat</th>
  											<th>Periode Diberikan</th>
  											<th>Penambahan PPH</th>
  											<th>Penambahan Upah</th>
  											<th>Data</th>
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
  							<label class="col-md-6 control-label">Kode Induk Tunjangan</label>
  							<div class="col-md-6" id="data_kode_view"></div>
  						</div>
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Nama Induk Tunjangan</label>
  							<div class="col-md-6" id="data_name_view"></div>
  						</div>
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Sifat</label>
  							<div class="col-md-6" id="data_sifat_view"></div>
  						</div>
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Periode Diberikan</label>
  							<div class="col-md-6" id="data_periode_view"></div>
  						</div>
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Penambahan PPH</label>
  							<div class="col-md-6" id="data_pph_view"></div>
  						</div>
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Penambahan Upah</label>
  							<div class="col-md-6" id="data_upah_view"></div>
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
  									<th>Nominal</th>
  									<th>Karyawan</th>
  									<th>Status</th>
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
  			<div class="modal-body">
  				<form id="form_edit">
  					<input type="hidden" id="data_id_edit" name="id" value="">
  					<input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
  					<div class="form-group">
  						<label>Kode Induk Tunjangan</label>
  						<input type="text" placeholder="Masukkan Kode Induk Tunjangan" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
  					</div>
  					<div class="form-group">
  						<label>Nama Induk Tunjangan</label>
  						<input type="text" placeholder="Masukkan Nama Induk Tunjangan" id="data_name_edit" name="nama" value="" class="form-control" required="required">
  					</div>
  					<div class="form-group">
  						<label>Sifat Tunjangan</label>
  						<select class="form-control select2" name="sifat" id="data_sifat_edit" style="width: 100%;" required="required">
  							<option></option>
  							<option value="0">Tidak Tetap</option>
  							<option value="1">Tetap</option>
  						</select>
  					</div>
  					<div class="form-group">
  						<label>Periode Diberikan</label>
  						<select class="form-control select2" name="periode" id="data_periode_edit" style="width: 100%;" required="required">
  							<option></option>
  							<option value="0">Bersama Gaji Pokok</option>
  							<option value="1">Sendiri</option>
  						</select>
  					</div>
  					<div class="form-group">
  						<label>Penambahan PPH</label>
  						<select class="form-control select2" name="pph" id="data_pph_edit" style="width: 100%;" required="required">
  							<option></option>
  							<option value="0">Tidak</option>
  							<option value="1">Ya</option>
  						</select>
  					</div>
  					<div class="form-group">
  						<label>Penambahan Upah</label>
  						<select class="form-control select2" name="upah" id="data_upah_edit" style="width: 100%;" required="required">
  							<option></option>
  							<option value="0">Tidak</option>
  							<option value="1">Ya</option>
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
  /*wajib diisi*/
  var table="master_induk_tunjangan";
  var column="id_induk_tunjangan";
  $(document).ready(function(){
  	$('#table_data_detail').DataTable();
  	refreshCode();
  	$('#table_data').DataTable( {
  		ajax: {
  			url: "<?php echo base_url('master/master_induk_tunjangan/view_all/')?>",
  			type: 'POST',
  			data:{access:"<?php echo base64_encode(serialize($access));?>"}
  		},
  		autoWidth: false,
		scrollX: true,
		bDestroy: true,
		scrollCollapse: true,
		fixedColumns:   {
			leftColumns: 3,
			rightColumns: 1
		},
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
  				 return '<a href="<?php base_url()?>master_tunjangan/'+full[11]+'">'+data+'</a>';
  			}
  		},
  		{   targets: 9,
  			width: '5%',
  			render: function ( data, type, full, meta ) {
  				return '<center>'+data+'</center>';
  			}
  		},
  		/*aksi*/
      {   targets: 10, 
      	width: '10%',
      	render: function ( data, type, full, meta ) {
      		return '<center>'+data+'</center>';
      	}
      },
      ]
   });
  });
  function refreshCode() {
  	kode_generator("<?php echo base_url('master/master_induk_tunjangan/kode');?>",'data_kode_add');
  }
  function view_modal(id) {
  	var data={id_induk_tunjangan:id};
  	var callback=getAjaxData("<?php echo base_url('master/master_induk_tunjangan/view_one')?>",data);  
  	$('#view').modal('show');
  	$('.header_data').html(callback['nama']);
  	$('#data_kode_view').html(callback['kode_induk_tunjangan']);
  	$('#data_name_view').html(callback['nama']);

  	$('#table_data_detail').DataTable().destroy();
  	$('#table_data_detail tbody').html(callback['detail']);
  	$('#table_data_detail').DataTable();
  	var sifat = 'Tidak Tetap';
  	if(callback['sifat'] == '1'){
  		var sifat = 'Tetap';
  	}
  	$('#data_sifat_view').html(sifat);

  	var periode = 'Bersama Gaji Pokok';
  	if(callback['periode'] == '1'){
  		var periode = 'Sendiri';
  	}
  	$('#data_periode_view').html(periode);

  	var pph = 'Tidak';
  	if(callback['pph'] == '1'){
  		var pph = 'Ya';
  	}
  	$('#data_pph_view').html(pph);

  	var upah = 'Tidak';
  	if(callback['upah'] == '1'){
  		var upah = 'Ya';
  	}
  	$('#data_upah_view').html(upah);

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
  	var data={id_induk_tunjangan:id};
  	var callback=getAjaxData("<?php echo base_url('master/master_induk_tunjangan/view_one')?>",data); 
  	$('#view').modal('toggle');
  	setTimeout(function () {
  		$('#edit').modal('show');
  	},600); 
  	$('.header_data').html(callback['nama']);
  	$('#data_id_edit').val(callback['id']);
  	$('#data_kode_edit_old').val(callback['kode_induk_tunjangan']);
  	$('#data_kode_edit').val(callback['kode_induk_tunjangan']);
  	$('#data_name_edit').val(callback['nama']);
  	$('#data_sifat_edit').val(callback['sifat']).trigger('change');
  	$('#data_periode_edit').val(callback['periode']).trigger('change');
  	$('#data_pph_edit').val(callback['pph']).trigger('change');
  	$('#data_upah_edit').val(callback['upah']).trigger('change');
  }
  function delete_modal(id) {
  	var data={id_induk_tunjangan:id};
  	var callback=getAjaxData("<?php echo base_url('master/master_induk_tunjangan/view_one')?>",data);
  	var datax={table:table,column:column,id:id,nama:callback['nama']};
  	loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  /*doing db transaction*/
  function do_status(id,data) {
  	var data_table={status:data};
  	var where={id_induk_tunjangan:id};
  	var datax={table:table,where:where,data:data_table};
  	submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
  	$('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
  	if($("#form_edit")[0].checkValidity()) {
  		submitAjax("<?php echo base_url('master/edt_induk_tunjangan')?>",'edit','form_edit',null,null);
  		$('#table_data').DataTable().ajax.reload();
  	}else{
  		notValidParamx();
  	} 
  }
  function do_add(){
  	if($("#form_add")[0].checkValidity()) {
  		submitAjax("<?php echo base_url('master/add_induk_tunjangan')?>",null,'form_add',"<?php echo base_url('master/master_induk_tunjangan/kode');?>",'data_kode_add');
  		$('#table_data').DataTable().ajax.reload(function(){
  			Pace.restart();
  		});
  		$('#form_add')[0].reset();
  		$('#data_sifat_add').val('').trigger('change');
  		$('#data_periode_add').val('').trigger('change');
  		$('#data_pph_add').val('').trigger('change');
  		refreshCode();
  	}else{
  		notValidParamx();
  	} 
  }
</script>