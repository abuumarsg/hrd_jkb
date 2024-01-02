<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Master Data
			<small>Master Petugas Payroll</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Master Petugas Payroll</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Master Petugas Payroll</h3>
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
											<?php if (in_array($access['l_ac']['petugas_payroll'], $access['access'])) {
												echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah</button>';
											}?>
										</div>
										<div class="pull-right" style="font-size: 8pt;">
											<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
											<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
										</div>
									</div>
								</div>
								<?php if(in_array($access['l_ac']['petugas_payroll'], $access['access'])){?>
									<div class="collapse" id="add">
										<!-- <div class="callout callout-info">
											<label><i class="fa fa-info-circle"></i> Bantuan</label>
											<br>Jika tidak ada nominal pada <b>Kena Pajak Dari</b> dan <b>Kena Pajak Sampai</b>, harap di isi dengan nilai 0.
										</div> -->
										<input type="hidden" id="key_btn_tambah" value="0">
										<br>
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<form id="form_add">
												<div class="form-group">
													<label>Kode</label>
													<input type="text" placeholder="Masukkan Kode" id="data_kode_add" name="kode" maxlength="20" class="form-control" required="required" value="" readonly="readonly">
												</div>
												<div class="form-group">
													<label>Jabatan Petugas Payroll</label>
													<select class="form-control select2" name="petugas" id="data_petugas_add" style="width: 100%;" required="required">
														<?php
														$jbt_ptg = $this->model_master->getListJabatanWhere(['a.kode_level !='=>null], null, 1,'mls.squence asc, a.nama asc');
														foreach ($jbt_ptg as $jp) {
															echo '<option value='.$jp->kode_jabatan.'>'.$jp->nama.'( '.$jp->nama_loker.' )</option>';
														}
														?>
													</select>
												</div>
												<!-- <div class="form-group">
													<?php
														$pesertax = '';
														$pesertax.='<option value="all">Pilih Semua</option>';
														$pesertax.='<option value="all">Pilih Semua</option>';
														// $jbt_ptg = $this->model_master->getListJabatanWhere(null, null, 1,'mls.squence asc, a.nama asc');
														foreach ($jbt_ptg as $jp2) {
															$pesertax.='<option value='.$jp2->kode_jabatan.'>'.$jp2->nama.' ('.$jp2->nama_loker.')</option>';
														}
													?>
													<label>Jabatan Peserta Payroll</label>
													<select class="form-control select2" name="peserta[]" id="data_peserta_add" style="width: 100%;" multiple="multiple" required="required">
														<?php echo $pesertax;?>
													</select>
												</div> -->
												<div class="form-group">
													<label>Karyawan</label>
													<select class="form-control select2" name="id_karyawan[]" id="karyawan_add" required="required" multiple="multiple" style="width: 100%;"></select>
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
											<th>Kode</th>
											<th>Petugas</th>
											<th>Jumlah Peserta</th>
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
							<label class="col-md-6 control-label">Kode</label>
							<div class="col-md-6" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Petugas</label>
							<div class="col-md-6" id="data_petugas_view"></div>
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
				<?php if (in_array($access['l_ac']['petugas_payroll'], $access['access'])) {
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
						<label>Kode</label>
						<input type="text" placeholder="Masukkan Kode" id="data_kode_edit" name="kode" maxlength="20" class="form-control" required="required" value="" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Petugas Payroll</label>
						<select class="form-control select2" name="petugas" id="data_petugas_edit" style="width: 100%;" required="required">
							<option>Pilih Semua</option>
							<?php
							// $jbt_ptg = $this->model_master->getListJabatanWhere(null, null, 1,'mls.squence asc, a.nama asc');
							foreach ($jbt_ptg as $jp) {
								echo '<option value='.$jp->kode_jabatan.'>'.$jp->nama.'( '.$jp->nama_loker.' )</option>';
							}
							?>
						</select>
					</div>
					<!-- <div class="form-group">
						<label>Peserta Payroll</label>
						<select class="form-control select2" name="peserta[]" id="data_peserta_edit" style="width: 100%;" multiple="multiple" required="required">
							<option value="all">Pilih Semua</option>
							<?php
															// $jbt_ptg = $this->model_master->getListJabatanWhere(null, null, 1,'mls.squence asc, a.nama asc');
							foreach ($jbt_ptg as $jp2) {
								echo '<option value='.$jp2->kode_jabatan.'>'.$jp2->nama.'( '.$jp2->nama_loker.' )</option>';
							}
							?>
						</select>
					</div> -->
					<div class="form-group">
						<label>Karyawan</label>
						<select class="form-control select2" name="id_karyawan[]" id="karyawan_edit" required="required" multiple="multiple" style="width: 100%;"></select>
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
	var table="master_petugas_payroll";
	var column="id_petugas_payroll";
	$(document).ready(function(){
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_petugas_payroll/view_all/')?>",
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
			if(key == 0){
				/*code in here*/
				$('#form_add')[0].reset();
				$('#data_petugas_add').val('').trigger('change');
				$('#data_peserta_add').val('').trigger('change');
				/*code in here*/
				refreshCode();
				$('#key_btn_tambah').val('1');
			}else { $('#key_btn_tambah').val('0'); }
			getSelect2("<?php echo base_url('master/master_petugas_payroll/select_employee')?>",'karyawan_add');
		})
	});
	function refreshCode() {
		kode_generator("<?php echo base_url('master/master_petugas_payroll/kode');?>",'data_kode_add');
	}
	function view_modal(id) {
		if($('#key_btn_tambah').val() == 1){
			$('#btn_tambah').click();
		}
		var data={id_petugas_payroll:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('master/master_petugas_payroll/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['petugas']);
		$('#data_kode_view').html(callback['kode']);
		$('#data_petugas_view').html(callback['petugas']);

		$('#table_data_detail').DataTable().destroy();
		$('#table_data_detail tbody').html(callback['peserta']);
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
			getSelect2("<?php echo base_url('master/master_petugas_payroll/select_employee')?>",'karyawan_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_petugas_payroll:id, mode: 'edit'};
		var callback=getAjaxData("<?php echo base_url('master/master_petugas_payroll/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_kode_edit_old').val(callback['kode']);
		$('#data_kode_edit').val(callback['kode']);
		$('#data_petugas_edit').val(callback['petugas']).trigger('change');
		// $('#data_peserta_edit').val(callback['peserta']).trigger('change');
		$('#karyawan_edit').val(callback['peserta']).trigger('change');

	}
	function delete_modal(id) {
		var data={id_petugas_payroll:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('master/master_petugas_payroll/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['petugas']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	/*doing db transaction*/
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_petugas_payroll:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edt_petugas_payroll')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 

	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_petugas_payroll')?>",null,'form_add',"<?php echo base_url('master/master_pph/kode');?>",'data_kode_add');
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
  		// $('#form_add')[0].reset();
  		// $('#data_petugas_add').val('').trigger('change');
  		// $('#data_peserta_add').val('').trigger('change');
  		refreshCode();
  	}else{
  		notValidParamx();
  	} 
  }
</script>