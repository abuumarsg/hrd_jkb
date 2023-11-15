
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Master Pinjaman</h3>
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
												echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Pinjaman</button>';
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
													<label>Kode Pinjaman</label>
													<input type="text" placeholder="Masukkan Kode Pinjaman" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
												</div>
												<div class="form-group">
													<label>Nama Pinjaman</label>
													<input type="text" placeholder="Masukkan Nama Pinjaman" id="data_name_add" name="nama" class="form-control field" required="required">
												</div>
												<div class="form-group">
													<label>Pilih Karyawan</label>
													<select class="form-control select2" name="karyawan" id="data_karyawan_add" onchange="getPeriodeFromKar('add',this.value)" style="width: 100%;"></select>
												</div>
												<div class="form-group">
													<label>Nominal</label>
													<input type="text" placeholder="Masukkan Nominal Tunjangan" id="data_nominal_add" name="nominal" min="0"
														value="Rp. 0" step="0.01" class="form-control field input-money" required="required">
												</div>
												<div class="form-group">
													<label>Angsuran Ke</label>
													<input type="text" placeholder="Angsuran Ke" id="data_angsuran_ke_add" name="angsuran_ke" min="0"
														value="0" class="form-control field input-number" required="required">
												</div>
												<div class="form-group">
													<label>Keterangan</label>
													<textarea name="keterangan" id="data_keterangan_add" class="form-control field" placeholder="Keterangan"></textarea>
												</div>
												<div class="form-group">
													<label>Periode Penggajian</label>
													<select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;">
														<?php
														// $periode = $this->model_master->getListPeriodePenggajian();
														// echo '<option></option>';
														// foreach ($periode as $p) {
														// 	echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
														// }
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
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>Kode Pinjaman</th>
											<th>Nama</th>
											<th>Karyawan</th>
											<th>Nominal</th>
											<th>Angusran Ke</th>
											<th>Periode Penggajian</th>
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
							<label class="col-md-6 control-label">Kode Pinjaman</label>
							<div class="col-md-6" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_name_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Karyawan</label>
							<div class="col-md-6" id="data_karyawan_view"></div>
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
							<label class="col-md-6 control-label">Nominal</label>
							<div class="col-md-6" id="data_nominal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Angsuran Ke</label>
							<div class="col-md-6" id="data_angsuran_ke_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Periode Penggajian</label>
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
						<label>Kode Pinjaman</label>
						<input type="text" placeholder="Masukkan Kode Pinjaman" id="data_kode_edit" name="kode" class="form-control" required="required" value="" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Nama Pinjaman</label>
						<input type="text" placeholder="Masukkan Nama Pinjaman" id="data_name_edit" name="nama" class="form-control field" required="required">
					</div>
					<div class="form-group">
						<label>Pilih Karyawan</label>
						<select class="form-control select2" name="karyawan" id="data_karyawan_edit" style="width: 100%;" onchange="getPeriodeFromKar('edit',this.value)"></select>
					</div>
					<div class="form-group">
						<label>Nominal</label>
						<input type="text" placeholder="Masukkan Nominal Tunjangan" id="data_nominal_edit" name="nominal" min="0" value="Rp. 0" step="0.01" class="form-control field input-money" required="required">
					</div>
					<div class="form-group">
						<label>Angsuran Ke</label>
						<input type="text" placeholder="Angsuran Ke" id="data_angsuran_ke_edit" name="angsuran_ke" min="0" value="0" class="form-control field input-number" required="required">
					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea name="keterangan" id="data_keterangan_edit" class="form-control field" placeholder="Keterangan"></textarea>
					</div>
					<div class="form-group">
						<label>Periode Penggajian</label>
						<select class="form-control select2" name="periode" id="data_periode_edit" style="width: 100%;">
							<?php
							// $periode = $this->model_master->getListPeriodePenggajian();
							// echo '<option></option>';
							// foreach ($periode as $p) {
							// 	echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
							// }
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
	/*wajib diisi*/
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_pinjaman";
	var column="id_pinjaman";
	$(document).ready(function(){
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_pinjaman/view_all/')?>",
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
			{   targets: 8,
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 9, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
		getSelect2("<?php echo base_url('master/master_pinjaman/employee')?>",'data_karyawan_add');
		$('#data_karyawan_add').val([]).trigger('change');
		$('#btn_tambah').click(function(){
			refreshCode();
		})
	});
	function refreshCode() {
		kode_generator("<?php echo base_url('master/master_pinjaman/kode');?>",'data_kode_add');
	}
	function getPeriodeFromKar(usage,value) {
		var data={idkar:value};
		var callback=getAjaxData("<?php echo base_url('master/master_pinjaman/periodeKar')?>",data);
		$('#data_periode_'+usage).html(callback['select']);
	}
  function view_modal(id) {
  	if($('#key_btn_tambah').val() == 1){
  		$('#btn_tambah').click();
  	}
  	var data={id_pinjaman:id, mode: 'view'};
  	var callback=getAjaxData("<?php echo base_url('master/master_pinjaman/view_one')?>",data);  
  	$('#view').modal('show');
  	$('.header_data').html(callback['nama']);
  	$('#data_kode_view').html(callback['kode']);
  	$('#data_name_view').html(callback['nama']);
  	$('#data_nominal_view').html(callback['nominal']);
  	$('#data_angsuran_ke_view').html(callback['angsuran_ke']);
  	$('#data_keterangan_view').html(callback['keterangan']);
  	$('#data_karyawan_view').html(callback['karyawan']);
  	$('#data_jabatan_view').html(callback['jabatan']);
  	$('#data_bagian_view').html(callback['bagian']);
  	$('#data_loker_view').html(callback['loker']);
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
  }
  function edit_modal() {
  	var id = $('input[name="data_id_view"]').val();
  	var data={id_pinjaman:id, mode: 'edit'};
  	var callback=getAjaxData("<?php echo base_url('master/master_pinjaman/view_one')?>",data); 
  	$('#view').modal('toggle');
  	setTimeout(function () {
  		$('#edit').modal('show');
  	},600); 
  	$('.header_data').html(callback['nama']);
  	$('#data_id_edit').val(callback['id']);
  	$('#data_kode_edit_old').val(callback['kode']);
  	$('#data_kode_edit').val(callback['kode']);
  	$('#data_name_edit').val(callback['nama']);
  	$('#data_nominal_edit').val(callback['nominal']);
  	$('#data_angsuran_ke_edit').val(callback['angsuran_ke']);
  	$('#data_keterangan_edit').html(callback['keterangan']);
  	// $('#data_periode_edit').val(callback['periode_e']);
	$('#data_periode_edit').html(callback['periode_e']);

	getSelect2("<?php echo base_url('master/master_pinjaman/employee')?>",'data_karyawan_edit');
  	$('#data_karyawan_edit').val(callback['karyawan']).trigger('change');
 }
 function delete_modal(id) {
 	var data={id_pinjaman:id, mode: 'view'};
 	var callback=getAjaxData("<?php echo base_url('master/master_pinjaman/view_one')?>",data);
 	var datax={table:table,column:column,id:id,nama:callback['nama']};
 	loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
 }
  /*doing db transaction*/
  function do_status(id,data) {
  	var data_table={status:data};
  	var where={id_pinjaman:id};
  	var datax={table:table,where:where,data:data_table};
  	submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
  	$('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
  	if($("#form_edit")[0].checkValidity()) {
  		submitAjax("<?php echo base_url('master/edt_pinjaman')?>",'edit','form_edit',null,null);
  		$('#table_data').DataTable().ajax.reload();
  	}else{
  		notValidParamx();
  	} 

  }
  function do_add(){
  	if($("#form_add")[0].checkValidity()) {
  		submitAjax("<?php echo base_url('master/add_pinjaman')?>",null,'form_add',"<?php echo base_url('master/master_pinjaman/kode');?>",'data_kode_add');
  		$('#table_data').DataTable().ajax.reload(function(){
  			Pace.restart();
  		});
  		$('#form_add')[0].reset();
  		$('#data_karyawan_add').val('').trigger('change');
  		$('#data_periode_add').val('').trigger('change');
  		refreshCode();
  	}else{
  		notValidParamx();
  	} 
  }
</script>