 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			<i class="fa far fa-credit-card"></i> Penggajian
 			<small>Periode Penggajian</small>
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
 			<li class="active">Periode Penggajian</li>
 		</ol>
 	</section>
 	<section class="content">
 		<div class="row">
 			<div class="col-md-12">
 				<div class="box box-success">
 					<div class="box-header with-border">
 						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Periode Penggajian</h3>
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
													<?php if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<button class="btn btn-success" href="#add" id="btn_tambah" type="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Periode Penggajian</button>

														<button class="btn btn-primary" href="#copy" id="btn_copy" type="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="false" aria-controls="import"><i class="fa fa-copy"></i> Duplicate Data</button>';
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
												<!-- <div class="callout callout-info">
													<label><i class="fa fa-info-circle"></i> Info</label>
													<br>Jika Memilih <b>Sistem Penggjian - Harian</b>, Maka Lembur Akan Otomatis Dihitung Bersama Payroll
												</div> -->
												<form id="form_add">
													<div class="form-group">
														<label>Kode Periode</label>
														<input type="text" placeholder="Masukkan Kode Periode Penggajian" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
													</div>
													<div class="form-group">
														<label>Nama Periode Penggajian</label>
														<input type="text" placeholder="Masukkan Nama Periode Penggajian" id="data_name_add" name="nama" class="form-control field" required="required">
													</div>
													<div class="form-group">
														<label>Tanggal</label>
														<input type="text" placeholder="Masukkan Tanggal Mulai" id="data_mulai_add" name="tanggal" class="form-control field date-range-notime" required="required">
													</div>
													<div class="form-group">
														<label>Pilih Bulan</label>
														<select class="form-control select2" name="bulan" id="data_bulan" style="width: 100%;" required="required">
															<?php
															$bulan_for = $this->formatter->getMonth();
															foreach ($bulan_for as $buf => $valf) {
																echo '<option value="'.$buf.'">'.$valf.'</option>';
															}
															?>
														</select>
													</div>
													<div class="form-group">
														<label>Pilih Tahun</label>
														<?php
															$tahun = $this->formatter->getYear();
															$sels = array(date('Y'));
															$exs = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
															echo form_dropdown('tahun',$tahun,$sels,$exs);
														?>
													</div>
													<div class="form-group">
														<label>Sistem Penggajian</label>
														<select class="form-control select2-notclear" name="sistem_penggajian" id="data_sistem_penggajian_add" style="width: 100%;"></select>
													</div>
													<div class="form-group">
														<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
													</div>
												</form>
											</div>
										</div>
										<div class="collapse" id="copy">
											<br>
											<div class="col-md-2"></div>
											<div class="col-md-8">
												<form id="form_copy">
													<div class="form-group">
														<label>Pilih Periode Penggajian</label>
														<select class="form-control select2" name="kode_periode" id="data_periode_copy" style="width: 100%;">
															<?php
																$periode = $this->model_master->getPeriodePenggajian(['a.status'=>1,'a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN']);
																echo '<option></option>';
																foreach ($periode as $p) {
																	echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
																}
															?>
														</select>
													</div>
													<div class="form-group">
														<label>Nama Periode Penggajian</label>
														<input type="text" placeholder="Masukkan Nama Penggajian Bulanan" id="data_name_copy" name="nama" class="form-control field" required="required">
													</div>
													<div class="form-group">
														<label>Tanggal</label>
														<input type="text" placeholder="Masukkan Tanggal Mulai" id="data_mulai_copy" name="tanggal" class="form-control field date-range-notime" required="required">
													</div>
													<div class="form-group">
														<label>Pilih Bulan</label>
														<select class="form-control select2" name="bulan" id="data_bulan" style="width: 100%;" required="required">
															<?php
															$bulan_copy = $this->formatter->getMonth();
															echo '<option value="">Pilih Data</option>';
															foreach ($bulan_copy as $buf => $valf) {
																echo '<option value="'.$buf.'">'.$valf.'</option>';
															}
															?>
														</select>
													</div>
													<div class="form-group">
														<label>Pilih Tahun</label>
														<?php
															$tahun_copy = $this->formatter->getYear();
															$sels = array(date('Y'));
															$exs = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
															echo form_dropdown('tahun',$tahun_copy,$sels,$exs);
														?>
													</div>
													<div class="form-group">
														<button type="button" onclick="do_copy()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
 											<th>Kode Periode</th>
 											<th>Nama</th>
 											<th>Sistem Penggajian</th>
 											<th>Tanggal Periode</th>
 											<th>Bulan Tahun</th>
 											<th>Jumlah Lokasi</th>
 											<th>Jumlah Bagian</th>
 											<th>Status Selesai</th>
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
 							<label class="col-md-6 control-label">Kode Periode</label>
 							<div class="col-md-6" id="data_kode_view"></div>
 						</div>
 						<div class="form-group col-md-12">
 							<label class="col-md-6 control-label">Nama</label>
 							<div class="col-md-6" id="data_name_view"></div>
 						</div>
 						<div class="form-group col-md-12">
 							<label class="col-md-6 control-label">Tanggal Mulai</label>
 							<div class="col-md-6" id="data_tgl_mulai_view"></div>
 						</div>
 						<div class="form-group col-md-12">
 							<label class="col-md-6 control-label">Tanggal Selesai</label>
 							<div class="col-md-6" id="data_tgl_selesai_view"></div>
 						</div>
 						<div class="form-group col-md-12">
 							<label class="col-md-6 control-label">Bulan Tahun</label>
 							<div class="col-md-6" id="data_bulan_tahun_view"></div>
 						</div>
 						<div class="form-group col-md-12">
 							<label class="col-md-6 control-label">Tanggal Transfer</label>
 							<div class="col-md-6" id="data_tanggal_transfer_view"></div>
 						</div>
 						<div class="form-group col-md-12">
 							<label class="col-md-6 control-label">Total Transfer</label>
 							<div class="col-md-6" id="data_total_transfer_view"></div>
 						</div>
 						<div class="form-group col-md-12">
 							<label class="col-md-6 control-label">Sistem Penggajian</label>
 							<div class="col-md-6" id="data_sistem_penggajian_view"></div>
 						</div>
 						<div class="form-group col-md-12">
 							<label class="col-md-6 control-label">Status Selesai</label>
 							<div class="col-md-6" id="data_status_selesai_view"></div>
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
 						<!-- <div id="data_detail_view" style="overflow-y: auto;height: 300px;"></div> -->
 						<table id="table_view" class="table table-bordered" width="100%">
 							<thead>
 								<tr>
 									<th class="nowrap">No.</th>
 									<th class="nowrap">Lokasi Kerja</th>
 									<th class="nowrap">Bagian</th>
 									<th class="nowrap">Level Struktur</th>
 								</tr>
 							</thead>
 							<tbody id="body_view">
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
 						<label>Kode Periode</label>
 						<input type="text" placeholder="Masukkan Kode Periode Penggajian" id="data_kode_edit" name="kode" class="form-control" required="required" value="" readonly="readonly">
 					</div>
 					<div class="form-group">
 						<label>Nama Periode Penggajian</label>
 						<input type="text" placeholder="Masukkan Nama Periode Penggajian" id="data_name_edit" name="nama" class="form-control field" required="required">
 					</div>
 					<div class="form-group">
 						<label>Tanggal</label>
 						<input type="text" placeholder="Masukkan Tanggal" id="data_tanggal_edit" name="tanggal" class="form-control field date-range-notime" required="required">
 					</div>
					<div class="form-group">
						<label>Pilih Bulan</label>
						<select class="form-control select2" name="bulan" id="data_bulan_edit" style="width: 100%;" required="required">
							<?php
							$bulan_for = $this->formatter->getMonth();
							foreach ($bulan_for as $buf => $valf) {
								echo '<option value="'.$buf.'">'.$valf.'</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Pilih Tahun</label>
						<?php
							$tahun = $this->formatter->getYear();
							$sels = array(date('Y'));
							$exs = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_tahun_edit');
							echo form_dropdown('tahun',$tahun,$sels,$exs);
						?>
					</div>
					<input type="hidden" name="bulan_old" id="data_bulan_old">
					<input type="hidden" name="tahun_old" id="data_tahun_old">
 					<div class="form-group">
 						<label>Sistem Penggajian</label>
 						<select class="form-control select2-notclear" name="sistem_penggajian" id="data_sistem_penggajian_edit" style="width: 100%;"></select>
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
<div id="delete" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete">
				<div class="modal-body text-center">
					<input type="hidden" id="data_kode_delete" name="kode">
					<input type="hidden" id="data_id_delete" name="id">
					<p>Data mungkin terkait dengan data lain.</p>
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
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
  //wajib diisi
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  var table="data_periode_penggajian";
  var column="id_periode_penggajian";
  $(document).ready(function(){
  	$('#table_view').DataTable();
  	refreshCode();
  	$('#table_data').DataTable( {
  		ajax: {
  			url: "<?php echo base_url('master/periode_penggajian/view_all/')?>",
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
  		{   targets: 10,
  			width: '5%',
  			render: function ( data, type, full, meta ) {
  				return '<center>'+data+'</center>';
  			}
  		},
      //aksi
      {   targets: 11, 
      	width: '10%',
      	render: function ( data, type, full, meta ) {
      		return '<center>'+data+'</center>';
      	}
      },
      ]
   });
	select_data('data_sistem_penggajian_add',url_select,'master_sistem_penggajian','kode_master_penggajian','nama');
	select_data('data_sistem_penggajian_edit',url_select,'master_sistem_penggajian','kode_master_penggajian','nama');
  	// $('#btn_tambah').click(function(){
  	// 	var key = $('#key_btn_tambah').val();
  	// 	if(key == 0){
	// 		  unsetoption('data_sistem_penggajian_add',['HARIAN']);
  	// 		$('#data_name_add').val('');
  	// 		$('#data_sistem_penggajian_add').val('BULANAN').trigger('change');
  	// 		refreshCode();
  	// 		$('#key_btn_tambah').val('1');
  	// 	}else { $('#key_btn_tambah').val('0'); }
  	// })
  });
  function refreshCode() {
  	kode_generator("<?php echo base_url('master/periode_penggajian/kode');?>",'data_kode_add');
		$('#data_periode_copy').val('').trigger('change');
		$('#data_name_copy').val('');
		$('#data_mulai_copy').val('');
  }
  function periode_lama(kode, send) {
  	var data={kode_periode_penggajian:kode};
  	var callback=getAjaxData("<?php echo base_url('master/periode_penggajian/periode_lama')?>",data);  
  	if(kode == ''){
  		$('#'+send).val([]).trigger('change');
  	}else{
  		$('#'+send).val(callback).trigger('change');
  	}

  }
  function view_modal(id) {
  	if($('#key_btn_tambah').val() == 1){
  		$('#btn_tambah').click();
  	}
  	var data={id_periode_penggajian:id, mode: 'view'};
  	var callback=getAjaxData("<?php echo base_url('master/periode_penggajian/view_one')?>",data);  
  	$('#view').modal('show');
  	$('.header_data').html(callback['nama']);
  	$('#data_kode_view').html(callback['kode_periode_penggajian']);
  	$('#data_name_view').html(callback['nama']);
  	$('#data_tgl_mulai_view').html(callback['tgl_mulai']);
  	$('#data_tgl_selesai_view').html(callback['tgl_selesai']);
  	$('#data_tanggal_transfer_view').html(callback['tgl_transfer']);
  	$('#data_total_transfer_view').html(callback['total_transfer']);
  	$('#data_sistem_penggajian_view').html(callback['sistem_penggajian']);
  	$('#data_status_selesai_view').html(callback['status_selesai']);
  	$('#data_bulan_tahun_view').html(callback['bulan_tahun']);
  		// $('#data_detail_view').html(callback['detail']);
  		$('#table_view').DataTable().destroy();
  		$('#body_view').html(callback['detail']);

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
  		$('#table_view').DataTable();
  	}
  	function edit_modal() {
  		var id = $('input[name="data_id_view"]').val();
  		var data={id_periode_penggajian:id, mode: 'edit'};
  		var callback=getAjaxData("<?php echo base_url('master/periode_penggajian/view_one')?>",data); 
  		$('#view').modal('toggle');
  		setTimeout(function () {
  			$('#edit').modal('show');
  		},600); 
  		$('.header_data').html(callback['nama']);
  		$('#data_id_edit').val(callback['id']);
  		$('#data_kode_edit_old').val(callback['kode_periode_penggajian']);
  		$('#data_kode_edit').val(callback['kode_periode_penggajian']);
  		$('#data_name_edit').val(callback['nama']);

  		$("#data_tanggal_edit").data('daterangepicker').setStartDate(callback['tgl_mulai']);
  		$("#data_tanggal_edit").data('daterangepicker').setEndDate(callback['tgl_selesai']);

  		$('#data_sistem_penggajian_edit').val(callback['sistem_penggajian']).trigger('change');
  		$('#data_bulan_edit').val(callback['bulan']).trigger('change');
  		$('#data_tahun_edit').val(callback['tahun']).trigger('change');
  		$('#data_bulan_old').val(callback['bulan']);
  		$('#data_tahun_old').val(callback['tahun']);
  	}
  	function delete_modal(id) {
  		// var data={id_periode_penggajian:id, mode: 'view'};
  		// var callback=getAjaxData("<?php //echo base_url('master/periode_penggajian/view_one')?>",data);
  		// var datax={table:table,column:column,id:id,nama:callback['nama']};
  		// loadModalAjax("<?php //echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
		var data={id_periode_penggajian:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('master/periode_penggajian/view_one')?>",data);
		$('#data_kode_delete').val(callback['kode_periode_penggajian']);
		$('#data_id_delete').val(callback['id']);
		$('#data_name_delete').text(callback['nama']);
		$('#delete').modal('show');
  	}
  /*doing db transaction*/
	function do_delete(){
		if($("#form_delete")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/del_periode_penggajian')?>",'delete','form_delete',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
		}else{
			notValidParamx();
		} 
	}
	function do_status(id, data) {
		var data_table = {
			status: data
		};
		var where = {
			id_periode_penggajian: id
		};
		var datax = {
			table: table,
			where: where,
			data: data_table
		};
		submitAjax("<?php echo base_url('global_control/change_status')?>", null, datax, null, null, 'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function do_edit() {
		if ($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edt_periode_penggajian')?>", 'edit', 'form_edit', null, null);
			$('#table_data').DataTable().ajax.reload();
		} else {
			notValidParamx();
		}
	}
	function do_add() {
		if ($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_periode_penggajian')?>", null, 'form_add', "<?php echo base_url('master/periode_penggajian/kode');?>", 'data_kode_add');
			$('#table_data').DataTable().ajax.reload(function () {
				Pace.restart();
			});
			$('#form_add')[0].reset();
			$('#data_sistem_penggajian_add').val('BULANAN').trigger('change');
			refreshCode();
		} else {
			notValidParamx();
		}
	}
 	function do_copy() {
 		if ($("#form_copy")[0].checkValidity()) {
 			submitAjax("<?php echo base_url('master/copy_periode_penggajian')?>", null, 'form_copy',null,null);
 			$('#table_data').DataTable().ajax.reload(function () {
 				Pace.restart();
 			});
 			refreshCode();
 		} else {
 			notValidParamx();
 		}
 	}
</script>