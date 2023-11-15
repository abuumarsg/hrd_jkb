<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Penggajian 
			<small>View Detail Periode Harian</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/periode_penggajian_harian');?>"><i class="fa far fa-credit-card"></i> Data Periode</a></li>
			<li class="active">View Detail Periode Harian</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa far fa-credit-card"></i> Daftar Detail <?php echo $nama_periode.' - '.strtoupper($bulan_periode).' - '.$tahun_periode; ?> </h3>
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
												echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Detail</button>';
											}?>
											<input type="hidden" id="key_btn_tambah" value="1">
										</div>
										<div class="pull-right" style="font-size: 8pt;">
											<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
											<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
										</div>
									</div>
								</div>
								<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
									<div class="collapse" id="add">
										<br>
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<form id="form_add">
												<div class="form-group">
													<label>Kode</label>
													<input type="hidden" name="induk_kode" value="<?php echo $this->codegenerator->decryptChar($this->uri->segment(3));?>">
													<input type="text" placeholder="Masukkan Kode" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
												</div>
												<div class="form-group">
													<label>Lokasi Kerja</label>
													<?php 
													if($status_adm == 0){
														?>
														<select class="form-control select2" name="loker" id="data_loker_add" style="width: 100%;" required="required" onchange="get_child('add',this.value)"></select>
														<?php
													}else{
														?>
														<input type="text" placeholder="Lokasi Kerja" class="form-control" required="required" value="<?php echo $nama_loker; ?>" readonly="readonly">
														<input type="hidden" id="data_loker_add" name="loker" value="<?php echo $loker; ?>">
														<?php
													}
													?>
												</div>
												<!-- <div class="form-group">
													<label>Tarif UMK</label>
													<select class="form-control select2" name="umk" id="data_umk_add" style="width: 100%;" required="required"></select>
												</div> -->
												<div class="form-group">
													<label>Pilih Bagian</label>
													<select class="form-control select2" name="bagian[]" id="data_bagian_add" style="width: 100%;" multiple="multiple"></select>
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
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>Kode</th>
											<th>Lokasi</th>
											<!-- <th>UMK</th> -->
											<th>Bagian</th>
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
							<label class="col-md-4 control-label">Kode</label>
							<div class="col-md-8" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-4 control-label">Lokasi</label>
							<div class="col-md-8" id="data_loker_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-4 control-label">UMK</label>
							<div class="col-md-8" id="data_umk_view"></div>
						</div> -->
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
									<th>Nama Bagian</th>
									<th>Level Struktur</th>
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
					<input type="hidden" id="data_induk_kode_edit" name="induk_kode" value="">
					<div class="form-group">
						<label>Kode</label>
						<input type="text" placeholder="Masukkan Kode" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Pilih Lokasi Kerja</label>
						<!-- <select class="form-control select2" name="loker" id="data_loker_edit" style="width: 100%;" required="required"></select> -->
						<?php 
						if($status_adm == 0){
							?>
							<select class="form-control select2" name="loker" id="data_loker_edit" style="width: 100%;" required="required" onchange="get_child('edit',this.value)"></select>
							<?php
						}else{
							?>
							<input type="text" placeholder="Lokasi Kerja" class="form-control" required="required" value="<?php echo $nama_loker; ?>" readonly="readonly">
							<input type="hidden" id="data_loker_edit" name="loker" value="<?php echo $loker; ?>">
							<?php
						}
						?>
					</div>
					<!-- <div class="form-group">
						<label>Tarif UMK</label>
						<select class="form-control select2" name="umk" id="data_umk_edit" style="width: 100%;" required="required"></select>
					</div> -->
					<div class="form-group">
						<label>Pilih Bagian</label>
						<select class="form-control select2" name="bagian[]" id="data_bagian_edit" style="width: 100%;" multiple="multiple"></select>
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
	/*wajib diisi*/
	var table="data_periode_penggajian_harian_detail";
	var column="id_periode_detail";
	$(document).ready(function(){ 
		$('#table_data_detail').DataTable();
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/view_periode_penggajian_harian/view_all/'.$this->uri->segment(3))?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
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
			{   targets: 1,
				width: '15%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			// {   targets: 3,
			// 	visible: false,
			// 	width: '15%',
			// 	render: function ( data, type, full, meta ) {
			// 		return data;
			// 	}
			// },
			{   targets: 5,
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			/*aksi*/
			{   targets: 6, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
		$('#btn_tambah').click(function(){
			var key = $('#key_btn_tambah').val();
			if(key == 1){
				$('#key_btn_tambah').val('0');
				if('<?php echo $status_adm; ?>' == '0'){
					select_data('data_loker_add',url_select,'master_loker','kode_loker','nama');
				}else{
					$('#data_loker_add').val('<?php echo $loker; ?>').trigger('change');
					get_child('add','<?php echo $loker; ?>');
				}
				refreshCode();
			}else { $('#key_btn_tambah').val('1'); }
		})
	});
	function refreshCode() {
		kode_generator("<?php echo base_url('master/view_periode_penggajian_harian/kode');?>",'data_kode_add');
	}
	function get_child(usage,value) {
		var data={kode_loker:value};
		var callback=getAjaxData("<?php echo base_url('master/view_periode_penggajian_harian/child')?>",data);
		$('#data_umk_'+usage).html(callback['umk']);
		$('#data_bagian_'+usage).html(callback['bagian']);
	}
	function view_modal(id) {
		var data={id_periode_detail:id,kode:"<?php echo $this->uri->segment(3); ?>", mode:'view'};
		var callback=getAjaxData("<?php echo base_url('master/view_periode_penggajian_harian/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_kode_view').html(callback['kode']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_umk_view').html(callback['umk']);

		$('#table_data_detail').DataTable().destroy();
		$('#table_data_detail tbody').html(callback['detail']);
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
		var data={id_periode_detail:id, mode:'edit'};
		var callback=getAjaxData("<?php echo base_url('master/view_periode_penggajian_harian/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 

		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_kode_edit_old').val(callback['kode']);
		$('#data_kode_edit').val(callback['kode']);
		$('#data_induk_kode_edit').val(callback['induk_kode']);
		select_data('data_loker_edit',url_select,'master_loker','kode_loker','nama');
		get_child('edit',callback['loker']);
		$('#data_loker_edit').val(callback['loker']).trigger('change');
		$('#data_umk_edit').val(callback['umk']).trigger('change');
		$('#data_bagian_edit').val(callback['bagian']).trigger('change');
	}
	function delete_modal(id) {
		var data={id_periode_detail:id};
		var callback=getAjaxData("<?php echo base_url('master/view_periode_penggajian_harian/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['kode']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	/*doing db transaction*/
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_periode_detail:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edt_periode_detail_harian')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_periode_detail_harian')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			if('<?php echo $status_adm; ?>' == '0'){
				$('#form_add')[0].reset();
				$('#data_loker_add').val([]).trigger('change');
				$('#data_bagian_add').val([]).trigger('change');
				$('#data_umk_add').val('').trigger('change');
			}else{
				$('#data_bagian_add').val([]).trigger('change');
				$('#data_umk_add').val('').trigger('change');
			}
			refreshCode();
		}else{
			notValidParamx();
		} 
	} 
</script>