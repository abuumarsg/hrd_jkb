<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fa-gears"></i> Setting Aplikasi
			<small>Setting Tanggal Update</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active">Setting Tanggal Update</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-calendar-check-o"></i> Tanggal Update Data Karyawan</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip"
								title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
								title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i
									class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Tanggal Mulai</th>
									<th>Tanggal Selesai</th>
									<th>Karyawan</th>
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
							<label class="col-md-6 control-label">Tanggal Mulai</label>
							<div class="col-md-6" id="data_start_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Selesai</label>
							<div class="col-md-6" id="data_end_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Karyawan</label>
							<div class="col-md-6" id="data_karyawan_view"></div>
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
					<input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
					<div class="form-group">
						<label>Tanggal</label>
						<div class="has-feedback">
							<span class="fa fa-calendar form-control-feedback"></span>
							<input type="text" placeholder="Tanggal" id="data_date_edit" name="date" value="" class="form-control date-range" required="required">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label>Pilih Karyawan</label>
						</div>
						<div class="col-md-6">
							<span id="kary_off_edit" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
							<span id="kary_on_edit" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
							<span style="padding-bottom: 9px;vertical-align: middle;"><b>Pilih Semua Karyawan</b></span>
							<input type="hidden" name="all_kary" id="kary_edit">
						</div>
						<div class="col-md-12" id="div_kar_edit">
							<select class="form-control select2" name="karyawan[]" multiple="multiple" id="data_karyawan_edit" style="width: 100%;"></select>
						</div><br><br><br><br>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="do_edit()" class="btn btn-success" id="btn_edit"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal modal-default fade" id="cek_karyawan" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title text-center">Lihat Detail Peserta</h4>
			</div>
			<div class="modal-body">
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<table id="table_view" class="table table-bordered" width="100%">
								<thead>
									<tr>
										<th class="nowrap">No.</th>
										<th class="nowrap">Nama</th>
										<th class="nowrap">Jabatan</th>
									</tr>
								</thead>
								<tbody id="body_view"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Tutup</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var table="master_tgl_update_data";
	var column="id_date";
	$(document).ready(function(){
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_tgl_update_data/view_all/')?>",
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
				{   targets: 6, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
			]
		});
		$('#kary_off_edit').click(function(){
			$('#kary_off_edit').hide();
			$('#kary_on_edit').show();
			$('input[name="all_kary"]').val('1');
			$('#data_karyawan_edit').removeAttr('required');
			$('#div_kar_edit').hide();
			$('#data_karyawan_edit').val('').trigger('change');
		})
		$('#kary_on_edit').click(function(){
			$('#kary_off_edit').show();
			$('#kary_on_edit').hide();
			$('input[name="all_kary"]').val('0');
			$('#data_karyawan_edit').attr('required','required');
			$('#div_kar_edit').show();
		})
	});
	function view_modal(id) {
		var data={id_date:id};
		var callback=getAjaxData("<?php echo base_url('master/master_tgl_update_data/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html('Tanggal Update Data Karyawan');
		$('#data_start_view').html(callback['tgl_mulai']);
		$('#data_end_view').html(callback['tgl_selesai']);
		$('#data_karyawan_view').html(callback['karyawan']);
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
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",'data_karyawan_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_date:id};
		var callback=getAjaxData("<?php echo base_url('master/master_tgl_update_data/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},500); 
		$('.header_data').html('Tanggal Update Data Karyawan');
		$('#data_id_edit').val(callback['id']);
		$("#data_date_edit").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
		$("#data_date_edit").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
		$('#data_karyawan_edit').val(callback['e_karyawan']);
		var c_kar = callback['count_kar'];
		var j_kar = callback['jum_kar'];
		if (c_kar == j_kar){
			$('#kary_off_edit').hide();
			$('#kary_on_edit').show();
			$('input[name="all_kary"]').val('1');
			$('#data_karyawan_edit').removeAttr('required');
			$('#div_kar_edit').hide();
			$('#data_karyawan_edit').val('').trigger('change');
		}
		form_property();
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_date:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edt_up_date_emp')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
		}else{
			notValidParamx();
		} 
	}
  	function view_modal_karyawan(id) {
		$('#table_view').DataTable().destroy();
		var datatab = {id_date:id};
		var calltable=getAjaxData("<?php echo base_url('master/master_tgl_update_data/view_karyawan')?>",datatab);
		$('#body_view').html(calltable['table']);
		$('#table_view').DataTable({
			scrollX: true
		});
		setTimeout(function () {
			$('#table_view').DataTable().destroy();
			$('#table_view').DataTable({
				scrollX: true
			});
		},600); 
		$('#cek_karyawan').modal('show');
  	}
</script>