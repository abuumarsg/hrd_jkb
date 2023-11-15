<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Master Data
			<small>Master Jaminan BPJS</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Master Jaminan BPJS</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Master Jaminan BPJS</h3>
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
												echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Jaminan BPJS</button>';
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
										<div class="callout callout-info">
											<label><i class="fa fa-info-circle"></i> Bantuan</label>
											<br>Pilih <b>Data BPJS Lama</b> untuk menduplicat data,jika tidak ingin maka lewati saja.
										</div>
										<input type="hidden" id="key_btn_tambah" value="0">
										<br>
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<form id="form_add">
												<div class="form-group">
													<label>Kode BPJS</label>
													<input type="text" placeholder="Masukkan Kode Periode Penggajian" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
												</div>
												<div class="form-group">
													<label>Data BPJS Lama</label>
													<select class="form-control select2" name="bpjs_lama" id="data_bpjs_lama_add" style="width: 100%;"></select>
												</div>
												<div class="form-group">
													<label>Nama BPJS</label>
													<input type="text" placeholder="Masukkan Nama Periode Penggajian" id="data_name_add" name="nama" class="form-control field" required="required">
												</div>
												<div class="form-group">
													<label>Inisial BPJS</label>
													<input type="text" placeholder="Masukkan Nama Periode Penggajian" id="data_inisial_add" name="inisial" class="form-control field" required="required">
												</div>
												<div class="form-group">
													<label>BPJS Dari Perusahaan ( % )</label>
													<input type="number" placeholder="Masukkan Nominal Tunjangan" id="data_bpjs_perusahaan_add" name="bpjs_perusahaan" min="0" value="0" step="0.01" class="form-control field" required="required">
												</div>
												<div class="form-group">
													<label>BPJS Dari Karyawan ( % )</label>
													<input type="number" placeholder="Masukkan Nominal Tunjangan" id="data_bpjs_karyawan_add" name="bpjs_karyawan" min="0" value="0" step="0.01" class="form-control field" required="required">
												</div>
												<!-- <div class="form-group">
													<label>Tahun</label>
													<select class="form-control select2" name="tahun" id="data_tahun_add" style="width: 100%;">
														<?php
														// $year = $this->formatter->getYear();
														// echo '<option></option>';
														// foreach ($year as $key => $value) {
														// 	echo '<option value="'.$value.'">'.$value.'</option>';
														// }
														?>
													</select>
												</div> -->
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
								<div class="callout callout-info"><label><i class="fas fa-exclamation-triangle"></i> Peringatan</label><br>
									Harap Sesuaikan Inisial BPJS sesuai dengan yang disarankan Sistem
									<ul>
										<li>Untuk Jaminan BPJS "JAMINAN KECELAKAAN KERJA TINGKAT RESIKO SEDANG" harap menggunakan inisial <b>JKK-RS</b>.</li>
										<li>Untuk Jaminan BPJS "JAMINAN KESEHATAN" harap menggunakan inisial <b>JKES</b>.</li>
										<li>Untuk Jaminan BPJS "JAMINAN PENSIUN" harap menggunakan inisial <b>JPNS</b>.</li>
										<li>Untuk Jaminan BPJS "JAMINAN HARI TUA" harap menggunakan inisial <b>JHT</b>.</li>
										<li>Untuk Jaminan BPJS "JAMINAN KEMATIAN" harap menggunakan inisial <b>JKM</b>.</li>
									</ul>
								</div>
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>Kode BPJS</th>
											<th>Nama</th>
											<th>Inisial</th>
											<th>BPJS Perusahaan</th>
											<th>BPJS Karyawan</th>
											<!-- <th>Tahun</th> -->
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
							<div class="col-md-6" id="data_name_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Inisial</label>
							<div class="col-md-6" id="data_inisial_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">BPJS Dari Perusahaan</label>
							<div class="col-md-6" id="data_bpjs_perusahaan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">BPJS Dari Karyawan</label>
							<div class="col-md-6" id="data_bpjs_karyawan_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tahun</label>
							<div class="col-md-6" id="data_tahun_view"></div>
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
						<input type="text" placeholder="Masukkan Kode Periode Penggajian" id="data_kode_edit" name="kode" class="form-control" required="required" value="" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Nama BPJS</label>
						<input type="text" placeholder="Masukkan Nama Periode Penggajian" id="data_name_edit" name="nama" class="form-control field" required="required">
					</div>
					<div class="form-group">
						<label>Inisial BPJS</label>
						<input type="text" placeholder="Masukkan Nama Periode Penggajian" id="data_inisial_edit" name="inisial" class="form-control field" required="required">
					</div>
					<div class="form-group">
						<label>BPJS Dari Perusahaan ( % )</label>
						<input type="number" placeholder="Masukkan Nominal Tunjangan" id="data_bpjs_perusahaan_edit" name="bpjs_perusahaan" min="0" value="0" step="0.01" class="form-control field" required="required">
					</div>
					<div class="form-group">
						<label>BPJS Dari Karyawan ( % )</label>
						<input type="number" placeholder="Masukkan Nominal Tunjangan" id="data_bpjs_karyawan_edit" name="bpjs_karyawan" min="0" value="0" step="0.01" class="form-control field" required="required">
					</div>
					<!-- <div class="form-group">
						<label>Tahun</label>
						<select class="form-control select2" name="tahun" id="data_tahun_edit" style="width: 100%;">
							<?php
							// $year = $this->formatter->getYear();
							// echo '<option></option>';
							// foreach ($year as $key => $value) {
							// 	echo '<option value="'.$value.'">'.$value.'</option>';
							// }
							?>
						</select>
					</div> -->
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
  var table="master_bpjs";
  var column="id_bpjs";
  $(document).ready(function(){
  	refreshCode();
  	$('#table_data').DataTable( {
  		ajax: {
  			url: "<?php echo base_url('master/master_bpjs/view_all/')?>",
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
      ],
      drawCallback: function( settings ) {

      }
   });
  	$('#btn_tambah').click(function(){
  		var key = $('#key_btn_tambah').val();
  		if(key == 0){
  			refreshCode();
  			select_data('data_bpjs_lama_add',url_select,'master_bpjs','id_bpjs','nama');
  			$('#data_bpjs_lama_add').val(null).trigger('change');
  			$('#key_btn_tambah').val('1');
  		}else { $('#key_btn_tambah').val('0'); }
  	})
  	$('#data_bpjs_lama_add').change(function(){
  		var id = this.value;
  		if(id != ''){
  			var data={id_bpjs:id, mode: 'edit'};
  			var callback=getAjaxData("<?php echo base_url('master/master_bpjs/view_one')?>",data);  

  			$('#data_name_add').val(callback['nama']);
  			$('#data_inisial_add').val(callback['inisial']);
  			$('#data_bpjs_perusahaan_add').val(callback['bpjs_perusahaan']);
  			$('#data_bpjs_karyawan_add').val(callback['bpjs_karyawan']);
  			$('#data_tahun_add').val(callback['tahun']).trigger('change');
  		}else{
  			$('#data_name_add').val('');
  			$('#data_inisial_add').val('');
  			$('#data_bpjs_perusahaan_add').val('0');
  			$('#data_bpjs_karyawan_add').val('0');
  			$('#data_tahun_add').val('').trigger('change');
  		}
  	})
  	$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  });
  function refreshCode() {
  	kode_generator("<?php echo base_url('master/master_bpjs/kode');?>",'data_kode_add');
  }

  function view_modal(id) {
  	var data={id_bpjs:id, mode: 'view'};
  	var callback=getAjaxData("<?php echo base_url('master/master_bpjs/view_one')?>",data);  
  	$('#view').modal('show');
  	$('.header_data').html(callback['nama']);

  	$('#data_kode_view').html(callback['kode']);
  	$('#data_name_view').html(callback['nama']);
  	$('#data_inisial_view').html(callback['inisial']);
  	$('#data_bpjs_perusahaan_view').html(callback['bpjs_perusahaan']);
  	$('#data_bpjs_karyawan_view').html(callback['bpjs_karyawan']);
  	$('#data_tahun_view').html(callback['tahun']).trigger('change');

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
  	var data={id_bpjs:id, mode: 'edit'};
  	var callback=getAjaxData("<?php echo base_url('master/master_bpjs/view_one')?>",data); 
  	$('#view').modal('toggle');
  	setTimeout(function () {
  		$('#edit').modal('show');
  	},600); 
  	$('.header_data').html(callback['nama']);
  	$('#data_id_edit').val(callback['id']);

  	$('#data_kode_edit_old').val(callback['kode']);
  	$('#data_kode_edit').val(callback['kode']);
  	$('#data_name_edit').val(callback['nama']);
  	$('#data_inisial_edit').val(callback['inisial']);
  	$('#data_bpjs_perusahaan_edit').val(callback['bpjs_perusahaan']);
  	$('#data_bpjs_karyawan_edit').val(callback['bpjs_karyawan']);
  	$('#data_tahun_edit').val(callback['tahun']).trigger('change');

  }
  function delete_modal(id) {
  	var data={id_bpjs:id, mode: 'view'};
  	var callback=getAjaxData("<?php echo base_url('master/master_bpjs/view_one')?>",data);
  	var datax={table:table,column:column,id:id,nama:callback['nama']};
  	loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
  	var data_table={status:data};
  	var where={id_bpjs:id};
  	var datax={table:table,where:where,data:data_table};
  	submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
  	$('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
  	if($("#form_edit")[0].checkValidity()) {
  		submitAjax("<?php echo base_url('master/edt_bpjs')?>",'edit','form_edit',null,null);
  		$('#table_data').DataTable().ajax.reload();
  	}else{
  		notValidParamx();
  	} 
  }
  function do_add(){
  	if($("#form_add")[0].checkValidity()) {
  		submitAjax("<?php echo base_url('master/add_bpjs')?>",null,'form_add',"<?php echo base_url('master/master_bpjs/kode');?>",'data_kode_add');
  		$('#table_data').DataTable().ajax.reload(function(){
  			Pace.restart();
  		});
  		$('#form_add')[0].reset();
  		select_data('data_bpjs_lama_add',url_select,'master_bpjs','id_bpjs','nama');
  		$('#data_tahun_add').val('').trigger('change');
  		refreshCode();
  	}else{
  		notValidParamx();
  	} 
  }
</script>