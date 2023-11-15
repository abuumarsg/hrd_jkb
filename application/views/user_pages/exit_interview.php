<?php
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-clipboard-list"></i> Form Exit Interview <small class="view_nama_full"><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active view_nama_full">Form Exit Interview <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-clipboard-list"></i> Data Seluruh Exit Interview  <small><?php echo $profile['nama'];?></small></h3>
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
								<form id="form_add" class="form-horizontal">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<input type="hidden" name="id_karyawan" id="id_karyawan" value="<?=$profile['id_karyawan'];?>">
												<div class="form-group">
													<label class="col-sm-3 control-label">Tanggal Keluar</label>
													<div class="col-sm-9">
														<div class="has-feedback">
															<span class="fa fa-calendar form-control-feedback"></span>
															<input type="text" name="tgl_keluar" class="form-control pull-right date-picker" placeholder="Tanggal Keluar" readonly="readonly" required="required">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-sm-3 control-label">Alasan Keluar</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="alasan_keluar" id="data_alasan_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
										<h3 class="text-danger">Tentang Anda</h3>
											<div class="form-group">
												<label class="col-sm-3 control-label">Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?</label>
												<div class="col-sm-9">
													<textarea name="setelah" class="form-control" placeholder="Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?" required="required"></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-sm-3 control-label">Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?</label>
												<div class="col-sm-9">
													<textarea name="posisi" class="form-control" placeholder="Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?" required="required"></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-sm-3 control-label">Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?</label>
												<div class="col-sm-9">
													<textarea name="tertarik" class="form-control" placeholder="Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?" required="required"></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-sm-3 control-label">Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?</label>
												<div class="col-sm-9">
													<textarea name="kompensasi" class="form-control" placeholder="Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?" required="required"></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-12">
										<h3 class="text-danger">Tentang Perusahaan</h3>
											<div class="form-group">
												<label class="col-sm-3 control-label">Bagaimana penilaian anda tentang sistem kerja di perusahaan ini ?</label>
												<div class="col-sm-9">
												<?php
													foreach ($radio as $rad => $r) {
														echo '<input type="radio" name="penilaian" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
													}
												?><br><br>
													<textarea name="alasan" class="form-control" placeholder="Alasan" required="required"></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-sm-3 control-label">Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?</label>
												<div class="col-sm-9">
													<textarea name="lingkungan" class="form-control" placeholder="Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?" required="required"></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-sm-3 control-label">Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?</label>
												<div class="col-sm-9">
													<textarea name="support" class="form-control" placeholder="Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?" required="required"></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-sm-3 control-label">Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?</label>
												<div class="col-sm-9">
													<textarea name="pelatihan" class="form-control" placeholder="Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?" required="required"></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-sm-3 control-label">Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?</label>
												<div class="col-sm-9">
													<textarea name="saran" class="form-control" placeholder="Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?" required="required"></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-11">
											<div class="form-group">
												<button type="submit" id="simpan" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>NIK</th>
									<th>Nama</th>
									<th>Jabatan</th>
									<th>Tanggal Masuk</th>
									<th>Tanggal Keluar</th>
									<th>Alasan</th>
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

<div id="view" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_exit">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi</label>
							<div class="col-md-6" id="data_loker_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alasan Keluar</label>
							<div class="col-md-6" id="data_alasan_keluar_exit"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_exit"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_exit">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_exit">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Karyawan</label>
							<div class="col-md-6" id="data_status_karyawan_exit"></div>
						</div>
					</div>
				</div><hr>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped">
							<tr>
								<th width="40%">Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?</th>
								<td id="data_setelah_exit"></td>
							</tr>
							<tr>
								<th width="40%">Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?</th>
								<td id="data_posisi_exit"></td>
							</tr>
							<tr>
								<th width="40%">Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?</th>
								<td id="data_tertarik_exit"></td>
							</tr>
							<tr>
								<th width="40%">Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?</th>
								<td id="data_kompensasi_exit"></td>
							</tr>
							<tr>
								<th width="40%">Bagaimana penilaian anda tentang sistem kerja di perusahaan ini ?</th>
								<td>
									<table>
										<tr>
											<th width="30%">Penilaian</th>
											<td id="data_penilaian_exit"></td>
										</tr>
										<tr>
											<th width="30%">Alasan</th>
											<td id="data_alasan_exit"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<th width="40%">Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?</th>
								<td id="data_lingkungan_exit"></td>
							</tr>
							<tr>
								<th width="40%">Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?</th>
								<td id="data_support_exit"></td>
							</tr>
							<tr>
								<th width="40%">Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?</th>
								<td id="data_pelatihan_exit"></td>
							</tr>
							<tr>
								<th width="40%">Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?</th>
								<td id="data_saran_exit"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-6">
							<input type="hidden" name="id" id="id_exit" value="">
							<input type="hidden" id="idk_exit" name="id_karyawan" value="">
							<div class="form-group clearfix">
								<label>NIK</label>
								<input type="text" id="nik_exit_edit" name="nik" value="" class="form-control" readonly="readonly">
							</div>
							<div class="form-group clearfix">
								<label>Nama</label>
								<input type="text" id="edit_nama_exit" name="nama" value="" class="form-control" readonly="readonly">
							</div>
							<div class="form-group clearfix">
								<label>Lokasi</label>
								<input type="text" id="edit_loker_exit" name="lokasi" value="" class="form-control" readonly="readonly">
							</div>
							<div class="form-group clearfix">
								<label>Jabatan</label>
								<input type="text" id="edit_jabatan_exit" name="jabatan" value="" class="form-control" readonly="readonly">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label>Alasan Keluar</label>
								<select class="form-control select2" name="alasan_keluar" id="edit_alasan_keluar_exit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Tanggal Masuk</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" id="edit_tgl_masuk_exit" value="" name="tgl_masuk" class="form-control pull-right" placeholder="Tanggal Masuk" readonly="readonly">
								</div>
							</div>
							<div class="form-group clearfix">
								<label>Tanggal Keluar</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" id="edit_tgl_keluar_exit" value="" name="tgl_keluar" class="form-control pull-right date-picker" placeholder="Tanggal Keluar" readonly="readonly">
								</div>
							</div>
						</div>
					</div>
				</div><hr>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped">
							<tr>
								<th width="40%">Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?</th>
								<td><textarea name="setelah" id="edit_setelah_exit" value="" class="form-control" placeholder="Apakah yang akan anda lakukan setelah keluar dari CV. Jati Kencana ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?</th>
								<td><textarea name="posisi" id="edit_posisi_exit" value="" class="form-control" placeholder="Bila bekerja, di perusahaan apa anda bekerja dan pada posisi apa ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?</th>
								<td><textarea name="tertarik" id="edit_tertarik_exit" value="" class="form-control" placeholder="Bila bekerja, apa yang membuat anda tertarik dengan pekerjaan tersebut ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?</th>
								<td><textarea name="kompensasi" id="edit_kompensasi_exit" value="" class="form-control" placeholder="Seberapa besar perbedaan kompensasi dan benefit lainnya yang anda dapatkan ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Bagaimana penilaian anda tentang sistem kerja di perusahaan ini ?</th>
								<td>
									<table>
										<tr>
											<th width="12%">Penilaian</th>
											<td>
												<?php
													foreach ($radio as $rad => $r) {
														echo '<input type="radio" name="penilaian" id="edit_penilaian_exit" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
													}
												?>
											</td>
										</tr>
										<tr>
											<th width="12%">Alasan</th>
											<td><textarea name="alasan" id="edit_alasan_exit" value="" class="form-control" placeholder="Alasan ?" required="required"></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<th width="40%">Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?</th>
								<td><textarea name="lingkungan" id="edit_lingkungan_exit" value="" class="form-control" placeholder="Bagaimana kondisi lingkungan kerja anda saat ini secara umum ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?</th>
								<td><textarea name="support" id="edit_support_exit" value="" class="form-control" placeholder="Apakah anda mendapat support dari atasan anda untuk mencapai target kerja ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?</th>
								<td><textarea name="pelatihan" id="edit_pelatihan_exit" value="" class="form-control" placeholder="Apakah anda merasa cukup diberikan pelatihan untuk mendukung kerja anda ?" required="required"></textarea></td>
							</tr>
							<tr>
								<th width="40%">Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?</th>
								<td><textarea name="saran" id="edit_saran_exit" value="" class="form-control" placeholder="Apa saran anda bagi CV. Jati Kencana untuk perbaikan ke depan ?" required="required"></textarea></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
			</form>
		</div>
	</div>
</div>
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
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_exit_interview";
	var column="id_exit";
	$(document).ready(function(){
		submitForm('form_add');
		submitForm('form_edit');
		select_data('data_alasan_add',url_select,'master_alasan_keluar','kode_alasan_keluar','nama','placeholder');
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/data_exit_interview/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
				{   targets: 6,
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 7,
					width: '7%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 8, 
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
			]
		});
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
	function resetselectAdd() {
		$('#data_alasan_add').val('').trigger('change');
	}
	function view_modal(id) {
		var data={id_exit:id};
		var callback=getAjaxData("<?php echo base_url('kemp/data_exit_interview/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_nik_exit').html(callback['nik']);
		$('#data_nama_exit').html(callback['nama']);
		$('#data_loker_exit').html(callback['loker']);
		$('#data_jabatan_exit').html(callback['jabatan']);
		$('#data_tgl_masuk_exit').html(callback['tgl_masuk']);
		$('#data_tgl_keluar_exit').html(callback['tgl_keluar']);
		$('#data_alasan_keluar_exit').html(callback['alasan_keluar']);
		$('#data_setelah_exit').html(callback['setelah']);
		$('#data_posisi_exit').html(callback['posisi']);
		$('#data_tertarik_exit').html(callback['tertarik']);
		$('#data_kompensasi_exit').html(callback['kompensasi']);
		$('#data_penilaian_exit').html(callback['penilaian']);
		$('#data_alasan_exit').html(callback['alasan']);
		$('#data_lingkungan_exit').html(callback['lingkungan']);
		$('#data_support_exit').html(callback['support']);
		$('#data_pelatihan_exit').html(callback['pelatihan']);
		$('#data_saran_exit').html(callback['saran']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_exit').html(statusval);
		var status_k = callback['status_karyawan'];
		if(status_k==1){
			var statusKar = '<b class="text-success">Aktif</b>';
		}else if(status_k==2){
			var statusKar = '<b class="text-danger">Karyawan Harus Dinonaktifkan</b>';
		}else{
			var statusKar = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_karyawan_exit').html(statusKar);
		$('#data_create_date_exit').html(callback['create_date']+' WIB');
		$('#data_update_date_exit').html(callback['update_date']+' WIB');
		$('input[name="data_id_exit"]').val(callback['id']);
		$('#data_create_by_exit').html(callback['nama_buat']);
		$('#data_update_by_exit').html(callback['nama_update']);
	}
	function edit_modal() {
		select_data('edit_alasan_keluar_exit',url_select,'master_alasan_keluar','kode_alasan_keluar','nama','placeholder');
		var id = $('input[name="data_id_exit"]').val();
		var data={id_exit:id};
		var callback=getAjaxData("<?php echo base_url('kemp/data_exit_interview/view_one')?>",data);
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		}, 600);
		$('.header_data').html(callback['nama']);
		$('#id_exit').val(callback['id']);
		$('#idk_exit').val(callback['id_karyawan']);
		$('#nik_exit_edit').val(callback['nik']);
		$('#edit_nama_exit').val(callback['nama']);
		$('#edit_loker_exit').val(callback['loker']);
		$('#edit_jabatan_exit').val(callback['jabatan']);
		$('#edit_tgl_keluar_exit').val(callback['etgl_keluar']);
		$('#edit_tgl_masuk_exit').val(callback['etgl_masuk']);
		$('#edit_alasan_keluar_exit').val(callback['alasan_keluar_e']).trigger('change');
		$('#edit_setelah_exit').val(callback['setelah']);
		$('#edit_posisi_exit').val(callback['posisi']);
		$('#edit_tertarik_exit').val(callback['tertarik']);
		$('#edit_kompensasi_exit').val(callback['kompensasi']);
		$('#edit_alasan_exit').val(callback['alasan']);
		$('#edit_lingkungan_exit').val(callback['lingkungan']);
		$('#edit_support_exit').val(callback['support']);
		$('#edit_pelatihan_exit').val(callback['pelatihan']);
		$('#edit_saran_exit').val(callback['saran']);
		var baca=callback['penilaian_e'];
		$('input[id=edit_penilaian_exit][value='+baca+']').iCheck('check');
	}
	function delete_modal(id) {
		var table="data_exit_interview";
		var column="id_exit";
		var data={id_exit:id};
		$('#delete').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/data_exit_interview/view_one')?>",data);
		$('#delete #data_id_delete').val(callback['id']);
		$('#delete #data_column_delete').val(column);
		$('#delete #data_table_delete').val(table);
		$('#delete .header_data').html(callback['nama']);
	}
  	function do_delete(){
		var data_table={stt_del:0};
		var table = $('#data_table_delete').val();
		var where={id_exit:$('#data_id_delete').val()};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",'delete',datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
  	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/edit_exit_interview')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/add_exit_interview')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			resetselectAdd()
		}else{
			notValidParamx();
		} 
	}
	function do_print(id) {
		window.location.href = "<?php echo base_url('cetak_word/cetak_exit_interview/')?>" + id;
	}
</script> 