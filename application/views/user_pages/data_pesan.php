<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="far fa-envelope"></i> Data Pesan <small class="view_nama_full"><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active view_nama_full">Data Pesan <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="far fa-envelope"></i> Data Seluruh Pesan  <small><?php echo $profile['nama'];?></small></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-left" style="font-size: 8pt;">
									<?php 
										echo '<button class="btn btn-success" type="button" id="btn_tambah" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data</button>';
									?>
								</div>
							</div><br><br>
						</div>
						<div class="collapse" id="add">
							<div class="col-md-12">
								<form id="form_add"><br><br>
									<div class="col-md-2"></div>
									<div class="col-md-8">
										<p class="text-danger">Pesan yang anda kirim bersifat Pribadi dan akan langsung terkirim kepada Admin!</p>
										<div class="col-md-6">
											<input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
											<div class="form-group">
												<label>Judul / Subjek</label>
												<input type="text" placeholder="Masukkan Judul / Subjek Pesan Anda" name="judul" class="form-control" required="required" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Jenis</label>
                             					<select class="form-control select2" name="jenis_pesan" id="data_jenis_pesan_add" style="width: 100%;"></select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-6">
												<label>Pilih Admin Yang Dapat Menerima Pesan</label>
											</div>
											<div class="col-md-6">
												<span id="adm_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
												<span id="adm_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
												<span style="padding-bottom: 9px;vertical-align: middle;"><b>Pilih Semua Admin</b></span>
												<input type="hidden" name="all_adm" id="adm">
											</div>
											<div class="col-md-12" id="div_kar">
												<select class="form-control select2" name="admin[]" id="data_admin_add" multiple="multiple" style="width: 100%;"></select>
											</div>
										</div><br><br><br><br><br><br><br><br>
										<div class="col-md-12">
											<div class="form-group">
												<label>Isi Pesan Anda</label>
												<textarea class="textarea" name="isi" placeholder="Isi Pesan Anda Disini ..." required="required" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
											</div>
										</div>
										<div class="col-md-12">
											<button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Kirim Pesan</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>NIK</th>
									<th>Nama</th>
									<th>Nama Jenis</th>
									<th>Judul</th>
									<th>Status</th>
									<th>Tanggal</th>
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
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Judul</label>
							<div class="col-md-6" id="data_judul_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jenis Pesan</label>
							<div class="col-md-6" id="data_jenis_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Isi Pesan</label>
							<div class="col-md-6" id="data_isi_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Pesan</label>
							<div class="col-md-6" id="data_status_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Ditujukan Kepada</label>
							<div class="col-md-6" id="data_ditujukan_view"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-info" onclick="edit_modal()" style="display:none;" id="tombol_edit"><i class="fa fa-edit"></i> Edit</button>
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
					<div class="row">
						<div class="col-md-6">
							<input type="hidden" id="data_id_edit" name="id" value="">
							<input type="hidden" id="data_idk_edit" name="id_karyawan" value="">
							<div class="form-group clearfix">
								<label>NIK</label>
								<input type="text" placeholder="Masukkan NIK" id="data_nik_edit" name="nik" value="" class="form-control" required="required" disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>Nama</label>
								<input type="text" placeholder="Masukkan Nama" id="data_nama_edit" name="nama" value="" class="form-control" required="required" disabled="disabled">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label>Judul Pesan</label>
								<input type="text" placeholder="Masukkan Judul Pesan" id="data_judul_edit" name="judul" value="" class="form-control" required="required">
							</div>
							<div class="form-group clearfix">
								<label>Jenis Pesan</label>
								<select class="form-control select2" name="jenis" id="data_jenis_edit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Admin Yg menerima Pesan</label>
								<select class="form-control select2" name="admin[]" id="data_admin_edit" multiple="multiple" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group clearfix">
								<label>ISI</label>
								<textarea class="textarea" name="isi" id="data_isi_edit" placeholder="Keterangan" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="btn_save_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- delete -->
<div id="delete" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete" name="column">
					<input type="hidden" id="data_id_delete" name="id">
					<input type="hidden" id="data_table_delete" name="table">
					<p>Apakah anda yakin akan menghapus data dengan nomor <b id="data_name_delete" class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete()" class="btn btn-primary"><i class="fa fa-trash"></i>
					Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_pesan";
	var column="id_pesan";
	$(document).ready(function(){
		refreshCode();
		submitForm('form_add');
		submitForm('form_edit');
      	select_data('data_jenis_pesan_add',url_select,'master_jenis_pesan','kode','nama');
      	getSelect2("<?php echo base_url('kemp/data_pesan/nama_admin')?>",'data_admin_add');
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/data_pesan/view_all/'.$this->codegenerator->encryptChar($profile['id_karyawan']))?>",
				type: 'POST',
				data:{ }
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
				{   targets: 5,
					width: '7%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 6,
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 7, 
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
			]
		});
		$('#adm_off').click(function(){
			$('#adm_off').hide();
			$('#adm_on').show();
			$('input[name="all_adm"]').val('1');
			$('#data_admin_add').removeAttr('required');
			$('#div_kar').hide();
			$('#data_admin_add').val('').trigger('change');
		})
		$('#adm_on').click(function(){
			$('#adm_off').show();
			$('#adm_on').hide();
			$('input[name="all_adm"]').val('0');
			$('#data_admin_add').attr('required','required');
			$('#div_kar').show();
		})
	});
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				if(form=='form_add'){
					do_add()
				}else{
					do_edit()
				}
			}
		})
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('kemp/view_izin_cuti/kode');?>",'data_kode_add');
	}
	function resetselectAdd() {
		$('#data_jenis_pesan_add').val('').trigger('change');
		$('#data_admin_add').val('').trigger('change');
	}
	function view_modal(id) {
		var data={id_pesan:id};
		var callback=getAjaxData("<?php echo base_url('kemp/data_pesan/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['judul']);
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_nik_view').html(callback['nik_karyawan']);
		$('#data_nama_view').html(callback['nama_karyawan']);
		$('#data_judul_view').html(callback['judul']);
		$('#data_jenis_view').html(callback['nama_jenis']);
		$('#data_isi_view').html(callback['isi']);
		$('#data_ditujukan_view').html(callback['ditujukan']);
		$('#data_status_view').html(callback['status']);
		var status = callback['e_status'];
		if(status==1){
			$('#tombol_edit').show();
		}else{
			$('#tombol_edit').hide();
		}
		$('#data_create_date_view').html(callback['create_date']+' WIB');
	}
	function edit_modal() {
      	select_data('data_jenis_edit',url_select,'master_jenis_pesan','kode','nama');
      	getSelect2("<?php echo base_url('kemp/data_pesan/nama_admin')?>",'data_admin_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_pesan:id};
		var callback=getAjaxData("<?php echo base_url('kemp/data_pesan/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['judul']);
		$('#data_nik_edit').val(callback['nik_karyawan']);
		$('#data_id_edit').val(callback['id']);
		$('#data_idk_edit').val(callback['id_karyawan']);
		$('#data_nama_edit').val(callback['nama_karyawan']);
		$('#data_judul_edit').val(callback['judul']);
		// $('#data_isi_edit').val(callback['isi']);
		addValueEditor('data_isi_edit',callback['isi']);
		$('#data_jenis_edit').val(callback['e_jenis_pesan']).trigger('change');
		$('#data_admin_edit').val(callback['e_admin']).trigger('change');
	}
	function delete_modal(id) {
		var table="data_pesan";
		var column="id_pesan";
		var data={id_pesan:id};
		$('#delete').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/data_pesan/view_one')?>",data);
		$('#delete #data_id_delete').val(callback['id']);
		$('#delete #data_column_delete').val(column);
		$('#delete #data_table_delete').val(table);
		$('#delete .header_data').html(callback['judul']);
	}
  	function do_delete(){
		var data_table={del_fo:0};
		var table = $('#data_table_delete').val();
		var where={id_pesan:$('#data_id_delete').val()};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",'delete',datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
  	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/edit_pesan')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/add_pesan')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			refreshCode();
			resetselectAdd()
		}else{
			notValidParamx();
		} 
	}
	function do_print(id) {
		window.location.href = "<?php echo base_url('cetak_word/cetak_izin/')?>" + id;
	}
</script> 