<?php
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-calendar-times"></i> Data Izin & Cuti <small class="view_nama_full"><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active view_nama_full">Data Izin & Cuti <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-calendar-times"></i> Data Seluruh Izin Cuti  <small><?php echo $profile['nama'];?></small></h3>
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
								<div class="pull-right" style="font-size: 8pt;">
									<?php 
										echo '<button class="btn btn-warning pull-left" type="button" id="btn_sisa_cuti"><i class="fa fa-eye"></i> Lihat Sisa Cuti</button>';
									?>
								</div>
							</div><br><br>
						</div>
						<div class="collapse" id="add">
							<div class="col-md-12">
								<form id="form_add">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Nomor Izin/Cuti</label>
													<div class="col-sm-9">
														<input type="hidden" name="id_karyawan_cuti" id="id_karyawan_cuti" value="<?php echo $profile['id_karyawan']?>">
														<input type="text" placeholder="Masukkan Nomor Izin/Cuti" name="no_cuti" id="data_kode_add" class="form-control" value="<?php echo $this->codegenerator->kodeIzinCuti(); ?>" required="required" readonly="readonly">
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Jenis Izin/Cuti</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="jenis_cuti" id="data_jenis_cuti_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Tanggal Mulai - Selesai</label>
													<div class="col-sm-9">
														<div class="has-feedback">
															<span class="fa fa-calendar form-control-feedback"></span>
															<input type="text" name="tanggal" class="form-control pull-right date-range-30" placeholder="Tanggal Cuti" id="tanggal_izin_add" readonly="readonly" required="required">
														</div>
														<span id="div_span_tgl"></span>
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Menyetujui</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="menyetujui" id="data_menyetujui_izin_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Menyetujui 2</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="menyetujui2" id="data_menyetujui2_izin_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<!-- <div class="form-group">
													<label class="col-sm-3 control-label">SKD Dibayar</label>
													<div class="col-sm-9">
														<a id="skd_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
														<a id="skd_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
														<input type="hidden" name="skd" id="skd_add" class="form-control">
													</div>
												</div> -->
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Mengetahui</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="mengetahui" id="data_mengetahui_izin_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Alasan Izin/Cuti</label>
													<div class="col-sm-9">
														<textarea name="alasan_cuti" class="form-control" placeholder="Alasan Cuti" required="required"></textarea>
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Keterangan</label>
													<div class="col-sm-9">
														<textarea name="keterangan_cuti" class="form-control" placeholder="Keterangan"></textarea>
													</div>
												</div>
												<span id="div_span_tgl_izin"></span><br>
												<span id="div_span_sisa_cuti"></span>
												<span id="div_span_notif_min_cuti"></span>
												<div class="form-group">
													<button type="submit" id="btn_save" disable="disable" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>NO Izin/Cuti</th>
									<th>Jenis</th>
									<th>Nama Jenis</th>
									<th>Tanggal Mulai</th>
									<th>Tanggal Selesai</th>
									<th>Alasan</th>
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
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Izin/Cuti</label>
							<div class="col-md-6" id="data_no_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jenis Izin/Cuti</label>
							<div class="col-md-6" id="data_jenis_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mulai</label>
							<div class="col-md-6" id="data_mulai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Selesai</label>
							<div class="col-md-6" id="data_selesai_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">SKD Dibayar</label>
							<div class="col-md-6" id="data_skd_view"></div>
						</div> -->
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alasan</label>
							<div class="col-md-6" id="data_alasan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui 2</label>
							<div class="col-md-6" id="data_menyetujui2_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Izin / Cuti</label>
							<div class="col-md-6" id="data_validasi_view">
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
							<div class="form-group clearfix">
								<label>NO Izin/Cuti</label>
								<input type="text" placeholder="Masukkan NO Izin/Cuti" id="data_no_edit" name="no_cuti" value="" class="form-control" required="required">
							</div>
							<div class="form-group clearfix">
								<label>Jenis Izin/Cuti</label>
								<select class="form-control select2" name="jenis_cuti" id="data_jenis_edit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Menyetujui</label>
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit" required="required" style="width: 100%;"></select>
								<input type="text" id="hide_text_edit" class="hidex-text">
							</div>
							<div class="form-group clearfix">
								<label>Menyetujui 2</label>
								<select class="form-control select2" name="menyetujui2" id="data_menyetujui2_edit" required="required" style="width: 100%;"></select>
								<input type="text" id="hide_text_edit" class="hidex-text">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label>Mengetahui</label>
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit" required="required" style="width: 100%;"></select>
								<input type="text" id="hide_text_edit" class="hidex-text">
							</div>
							<!-- <div class="form-group clearfix">
								<label>SKD Dibayar</label><br>
								<a id="skd_no_edit" style="font-size: 16pt;"><i class="far fa-square"></i></a>
								<a id="skd_yes_edit" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
								<input type="hidden" name="skd" id="skd_edit" class="form-control" placeholder="SKD Dibayar" readonly>
							</div> -->
							<div class="form-group clearfix">
								<label>Tanggal Mulai - Selesai</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tanggal" class="form-control pull-right date-range-30" id="data_tgl_cuti_edit" value="" placeholder="Tanggal Cuti" readonly="readonly" required="required">
								</div>
								<span id="div_span_tgl_edit"></span>
							</div>
							<div class="form-group clearfix">
								<label>Alasan</label>
								<textarea class="form-control" name="alasan" id="data_alasan_edit" placeholder="Alasan" required="required"></textarea>
							</div>
							<div class="form-group clearfix">
								<label>Keterangan</label>
								<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan"></textarea>
							</div>
							<span id="div_span_tgl_izin_edit"></span><br>
							<span id="div_span_sisa_cuti_edit"></span>
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
<div id="sisa_cuti" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Sisa Cuti</h2>
			</div>
			<form id="form_reset" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p>Sisa Cuti Anda <?php echo $profile['sisa_cuti'];?> Hari</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="slip_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Izin Cuti</h4>
			</div>
			<input type="hidden" id="data_id_izin" name="data_id_izin">
			<div class="modal-body text-center">
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-primary" onclick="do_print_slip('word')"><i class="fas fa-file-word fa-fw"></i>WORD</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_print_slip('pdf')"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="view_izin_cuti_karyawan";
	var column="id_izin_cuti";
	$(document).ready(function(){
		refreshCode();
		submitForm('form_add');
		submitForm('form_edit');
		getSelect2("<?php echo base_url('kemp/view_izin_cuti/izincuti')?>",'data_jenis_cuti_add');
		getSelect2("<?php echo base_url('kemp/view_izin_cuti/employee')?>",'data_mengetahui_izin_add,#data_menyetujui_izin_add,#data_menyetujui2_izin_add');
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/view_izin_cuti/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
				{   targets: 2,
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
				{   targets: 8,
					width: '7%',
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
		$('#data_jenis_cuti_add, #tanggal_izin_add').change(function(){
			var jc = $('#data_jenis_cuti_add').val();
			var idk = $('#id_karyawan_cuti').val();
			var tgl = $('#tanggal_izin_add').val();
			var datax = {jenis: jc,tanggal: tgl,id_kar: idk};
			var tgl_izin=getAjaxData("<?php echo base_url('kemp/tanggalIzin')?>",datax);
			var tgl_ini=getAjaxData("<?php echo base_url('kemp/cekTanggalIzin')?>",datax);
			var sisacuti=getAjaxData("<?php echo base_url('kemp/cekSisaCuti')?>",datax);
			var minCuti=getAjaxData("<?php echo base_url('kemp/minCuti')?>",datax);
			if (tgl_izin['jenis'] == 'C') {
				if (tgl_izin['hari'] > tgl_izin['maksimal']) {
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','red');
					var value1 = 1;
				}else{
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','green');
					var value1 = 2;
				}
			}else{
				if (tgl_izin['maksimal'] < tgl_izin['hari']) {
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','red');
					var value1 = 3;
				}else{
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','green');
					var value1 = 4;
				}
				$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
				// var value1 = 5;
			};
			if (tgl_ini['cek'] > 0) {
				$('#div_span_tgl_izin').html(tgl_ini['msg']).css('color','red');
				var value2 = 6;
			}else{
				$('#div_span_tgl_izin').html(tgl_ini['msg']).css('color','green');
				var value2 = 7;
			};
			if (tgl_izin['jenis'] == 'C' && sisacuti['potong_cuti'] == '1') {
				if (sisacuti['sisa_cuti'] >= sisacuti['hari']) {
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
					var value3 = 8;
				}else{
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','red');
					var value3 = 9;
				}
			}else if (tgl_izin['jenis'] == 'C' && sisacuti['potong_cuti'] != '1') {
				$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
				var value3 = 10;
			}
			if (minCuti['msg'] == '' || minCuti['msg'] == null) {
				$('#div_span_notif_min_cuti').html('').css('color','green');
				var value4 = 11;
			}else{
				$('#div_span_notif_min_cuti').html(minCuti['msg']).css('color','red');
				var value4 = 12;
			}
			if(value1 == '1' || value1 == '3' || value2 == '6' || value3 == '9' || value4 == '12'){
				$('#btn_save').prop('disabled', true);
			}else if(value1 == '2' || value1 == '4' || value2 == '7' || value3 == '8' || value3 == '10' || value4 == '11'){
				$('#btn_save').prop('disabled', false);
			}
			// console.log('value1 : '+value1);
			// console.log('value2 : '+value2);
			// console.log('value3 : '+value3);
			// console.log('value4 : '+value4);
		})
		$('#btn_sisa_cuti').click(function() {
			$('#sisa_cuti').modal('show');
		})
		$('#skd_no').click(function(){
			$('#skd_no').hide();
			$('#skd_yes').show();
			$('#skd_add').val('1');
		})
		$('#skd_yes').click(function(){
			$('#skd_yes').hide();
			$('#skd_no').show();
			$('#skd_add').val('0');
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
		$('#data_jenis_cuti_add').val('').trigger('change');
		$('#data_mengetahui_izin_add').val('<?php echo base_url('kemp/view_izin_cuti/refresh_mengetahui')?>').trigger('change');
	}
	function view_modal(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('kemp/view_izin_cuti/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nomor']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_no_view').html(callback['nomor']);
		$('#data_jenis_view').html(callback['jenis_cuti']);
		$('#data_mulai_view').html(callback['tanggal_mulai']);
		$('#data_selesai_view').html(callback['tanggal_selesai']);
		$('#data_skd_view').html(callback['skd']);
		$('#data_alasan_view').html(callback['alasan']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
		$('#data_menyetujui2_view').html(callback['menyetujui2']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_validasi_view').html(callback['validasi']);
		var status = callback['e_validasi'];
		if(status==2){
			$('#tombol_edit').show();
		}else{
			$('#tombol_edit').hide();
		}
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function edit_modal() {
		$('#skd_no_edit').click(function(){
			$('#skd_no_edit').hide();
			$('#skd_yes_edit').show();
			$('#skd_edit').val('1');
		})
		$('#skd_yes_edit').click(function(){
			$('#skd_yes_edit').hide();
			$('#skd_no_edit').show();
			$('#skd_edit').val('0');
		})
		getSelect2("<?php echo base_url('kemp/view_izin_cuti/izincuti')?>",'data_jenis_edit');
		getSelect2("<?php echo base_url('kemp/view_izin_cuti/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit,#data_menyetujui2_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('kemp/view_izin_cuti/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_nik_edit').val(callback['nik']);
		$('#data_id_edit').val(callback['id']);
		$('#data_idk_edit').val(callback['id_karyawan']);
		$('#data_nama_edit').val(callback['nama']);
		$('#data_no_edit').val(callback['nomor']);
		$('#data_alasan_edit').val(callback['alasan']);
		$('#data_keterangan_edit').val(callback['keterangan']);
		$('#data_jenis_edit').val(callback['e_jenis_cuti']).trigger('change');
		$('#data_mengetahui_edit').val(callback['emengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['emenyetujui']).trigger('change');
		$('#data_menyetujui2_edit').val(callback['emenyetujui2']).trigger('change');
		$("#data_tgl_cuti_edit").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
		$("#data_tgl_cuti_edit").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
		var skd=callback['e_skd'];
		if (skd==1){
			$('#skd_no_edit').hide();
			$('#skd_yes_edit').show();
		}else{
			$('#skd_yes_edit').hide();
			$('#skd_no_edit').show();
		}
		$('#data_jenis_edit, #data_tgl_cuti_edit').change(function(){
			var id = $('#data_id_edit').val();
			var jc = $('#data_jenis_edit').val();
			var idk = $('#data_idk_edit').val();
			var tgl = $('#data_tgl_cuti_edit').val();
			var datax={jenis: jc,tanggal: tgl,id:id,id_kar: idk,};
			var sisacuti=getAjaxData("<?php echo base_url('kemp/cekSisaCuti')?>",datax);
			var tgl_izin=getAjaxData("<?php echo base_url('kemp/tanggalIzin')?>",datax);
			if (tgl_izin['jenis'] == 'C') {
				if (tgl_izin['hari'] > 2) {
					$('#div_span_tgl_edit').html(tgl_izin['msg']).css('color','red');
					$('#btn_save_edit').attr('disabled','disabled');
				}else{
					$('#div_span_tgl_edit').html(tgl_izin['msg']).css('color','green');
					$('#btn_save_edit').removeAttr('disabled','disabled');   
				}
			}else{
				if (tgl_izin['maksimal'] < tgl_izin['hari']) {
					$('#div_span_tgl_edit').html(tgl_izin['msg']).css('color','red');
					$('#btn_save_edit').attr('disabled','disabled');
				}else{
					$('#div_span_tgl_edit').html(tgl_izin['msg']).css('color','green');
					$('#btn_save_edit').removeAttr('disabled','disabled'); 
				}  
				$('#div_span_sisa_cuti_edit').html(sisacuti['msg']).css('color','red');
				$('#btn_save_edit').attr('disabled','disabled');
			}	
			if (tgl_izin['jenis'] == 'C') {
				if (sisacuti['sisa_cuti'] >= sisacuti['hari']) {
					$('#div_span_sisa_cuti_edit').html(sisacuti['msg']).css('color','green');
					$('#btn_save_edit').removeAttr('disabled','disabled'); 
				}else{
					$('#div_span_sisa_cuti_edit').html(sisacuti['msg']).css('color','red');
					$('#btn_save_edit').attr('disabled','disabled');
				}
			}
			// var tgl_ini=getAjaxData("<?php //echo base_url('kemp/cekTanggalIzin')?>",datax);
			// if (tgl_ini['cek'] > 0) {
			// 	$('#div_span_tgl_izin_edit').html(tgl_ini['msg']).css('color','red');
			// 	$('#btn_save_edit').attr('disabled','disabled');
			// }else{
			// 	$('#div_span_tgl_izin_edit').html(tgl_ini['msg']).css('color','green');
			// 	$('#btn_save_edit').removeAttr('disabled','disabled'); 
			// }
		})
	}
	function delete_modal(id) {
		var table="data_izin_cuti_karyawan";
		var column="id_izin_cuti";
		var data={id_izin_cuti:id};
		$('#delete').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/view_izin_cuti/view_one')?>",data);
		$('#delete #data_id_delete').val(callback['id']);
		$('#delete #data_column_delete').val(column);
		$('#delete #data_table_delete').val(table);
		$('#delete .header_data').html(callback['nomor']);
	}
  	function do_delete(){
		var data_table={stt_del:0};
		var table = $('#data_table_delete').val();
		var where={id_izin_cuti:$('#data_id_delete').val()};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",'delete',datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
  	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/edit_izin_cuti')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/add_izin_cuti')?>",null,'form_add',null,null);
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
	// function do_print(id) {
	// 	window.location.href = "<?php //echo base_url('cetak_word/cetak_izin/')?>" + id;
	// }
	function do_print(id) { 
		$('#slip_mode').modal('show');
		$('input[name="data_id_izin"]').val(id);
	}
	function do_print_slip(kode) {
		var id = $('input[name="data_id_izin"]').val();
		if(kode == 'word'){
			window.location.href = "<?php echo base_url('cetak_word/cetak_izin/')?>"+id;
		} else {
			$.redirect("<?php echo base_url('kpages/cetak_izin'); ?>", { id_izin : id, }, "POST", "_blank");
		}
	} 
</script> 