<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fas fa-university"></i> Data
			<small>Setting Perusahaan</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i>
					Dashboard</a></li>
			<li class="active"><i class="fas fa-university"></i> Setting Perusahaan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<!-- <div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-university"></i> Data Setting Perusahaan</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div> -->
					<div class="box-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a onclick="sejarah()" href="#sejarah" data-toggle="tab"><i class="fas fa-book-open"></i> Sejarah, Visi Misi</a></li>
								<li><a onclick="berita()" href="#berita" data-toggle="tab"><i class="fas fa-newspaper"></i> Berita</a></li>
								<li><a onclick="struktur()" href="#struktur" data-toggle="tab"><i class="fas fa-sitemap"></i> Struktur Organisasi</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="sejarah">
									<form id="form_sejarah">
										<div class="box-body">
											<fieldset>
												<input type="hidden" name="id" value="<?=$comp['id']?>">
												<legend class="legend"><i class="fa fa-book"></i> Sejarah Perusahaan
												</legend>
												<textarea class="textarea" name="sejarah" id="sejarah"
													placeholder="Tuliskan Sejarah Disini ..." required="required"
													style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=$comp['sejarah']?></textarea>
											</fieldset>
											<fieldset>
												<legend class="legend"><i class="fa fa-book"></i> Visi Perusahaan
												</legend>
												<textarea class="textarea" name="visi" id="visi"
													placeholder="Tuliskan Visi Disini ..." required="required"
													style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=$comp['visi']?></textarea>
											</fieldset>
											<fieldset>
												<legend class="legend"><i class="fa fa-book"></i> Misi Perusahaan
												</legend>
												<textarea class="textarea" name="misi" id="misi"
													placeholder="Tuliskan misi Disini ..." required="required"
													style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=$comp['misi']?></textarea>
											</fieldset>
											<fieldset>
												<legend class="legend"><i class="fa fa-book"></i> Moto Perusahaan
												</legend>
												<textarea class="textarea" name="moto" id="moto"
													placeholder="Tuliskan moto Disini ..." required="required"
													style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=$comp['moto']?></textarea>
											</fieldset>
										</div>
										<div class="box-footer">
											<div class="pull-right">
												<button type="submit" id="btn_add" class="btn btn-success"><i
														class="fa fa-floppy-o"></i> Simpan</button>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="berita">
									<div class="box box-warning">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="far fa-newspaper"></i> Data Berita</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="reload_table('table_data')"
													data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse"
													data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="col-md-12">
													<div class="row">
														<div class="col-md-12">
															<div class="pull-left">
																<?php 
																	if (in_array($access['l_ac']['add'], $access['access'])) {
																		echo '<button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add_acc"><i class="fa fa-plus"></i> Tambah Berita</button>';
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
													<div class="collapse" id="add_acc">
														<br>
														<div class="col-md-2"></div>
														<div class="col-md-8">
															<form id="form_add">
																<div class="form-group">
																	<label>Judul</label>
																	<input type="text" placeholder="Masukkan judul Berita" id="data_judul_add" name="judul" class="form-control field" required="required">
																</div>
																<div class="form-group clearfix">
																	<label>Kategori</label>
																	<select class="form-control select2" name="kategori" id="data_kategori_add" required="required" style="width: 100%;"></select>
																</div>
																<div class="form-group clearfix">
																	<label>Isi Berita</label>
																	<textarea class="textarea" name="isi" class="form-control" placeholder="Isi" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
																</div>
																<div class="form-group">
																	<label>Tanggal Posting</label>
																	<div>
																		<div class="has-feedback">
																			<span class="fa fa-calendar form-control-feedback"></span>
																			<input type="text" name="tgl_posting" class="form-control pull-right date-picker" placeholder="Tanggal Posting" readonly="readonly" required="required">
																		</div>
																	</div>
																</div><br>
																<label>Gambar</label>
																<p class="text-muted">File harus bertipe *.jpg atau *.png</p>
																<div class="form-group">
																	<div class="input-group">
																		<label class="input-group-btn">
																			<span class="btn btn-primary">
																				<i class="fas fa-folder-open"></i> Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;">
																			</span>
																		</label>
																		<input type="text" class="form-control" readonly>
																	</div>
																</div>
																<div class="form-group">
																	<button type="button" id="btn_add" onclick="do_add()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	<button type="submit" id="btn_addx" style="display: none;"></button>
																</div>
															</form>
														</div>
													</div><?php } ?>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<table id="table_data"
														class="table table-bordered table-striped table-responsive"
														width="100%">
														<thead>
															<tr>
																<th>No.</th>
																<th>Gambar</th>
																<th>Judul</th>
																<th>Kategori</th>
																<th>Tanggal Posting</th>
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
										<!-- view -->
										<div id="view" class="modal fade" role="dialog">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h2 class="modal-title">Detail Data <b class="text-muted header_data" style="font-size: 12pt"></b></h2>
														<input type="hidden" name="data_id_view">
													</div>
													<div class="modal-body">
														<div class="row">
															<div class="text-center">
																<div class="col-md-12" id="gambar_berita_view">
																</div>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Judul</label>
																	<div class="col-md-6" id="data_judul_view"></div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Tanggal Posting</label>
																	<div class="col-md-6" id="data_tgl_view"></div>
																</div>
																<div class="form-group col-md-12">
																	<label
																		class="col-md-6 control-label">Kategori</label>
																	<div class="col-md-6" id="data_kategori_view"></div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Status</label>
																	<div class="col-md-6" id="data_status_view"></div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Dibuat Tanggal</label>
																	<div class="col-md-6" id="data_create_date_view">
																	</div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Diupdate Tanggal</label>
																	<div class="col-md-6" id="data_update_date_view">
																	</div>
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
														<div class="row">
															<div class="col-md-12">
																<div class="form-group col-md-12">
																	<label>Isi</label>
																	<div id="data_isi_view"></div>
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
														<button type="button" class="close"
															data-dismiss="modal">&times;</button>
														<h2 class="modal-title">Edit Data <b class="text-muted header_data" style="font-size: 12pt"></b></h2>
													</div>
													<form id="form_edit">
														<div class="modal-body">
															<input type="hidden" id="data_id_edit" name="id" value="">
															<div class="form-group">
																<label>Judul</label>
																<input type="text" placeholder="Masukkan Judul" id="data_judul_edit" name="judul" value="" class="form-control" required="required">
															</div>
															<div class="form-group clearfix">
																<label>Kategori</label>
																<select class="form-control select2" name="kategori" id="data_kategori_edit" required="required" style="width: 100%;"></select>
															</div>
															<div class="form-group clearfix">
																<label>Isi Berita</label>
																<textarea class="textarea" name="isi" class="form-control" id="data_isi_edit" placeholder="Isi" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
															</div>
															<div class="form-group">
																<label>Tanggal Posting</label>
																<div>
																	<div class="has-feedback">
																		<span class="fa fa-calendar form-control-feedback"></span>
																		<input type="text" name="tgl_posting" id="tgl_posting_edit" class="form-control pull-right date-picker" placeholder="Tanggal Posting" readonly="readonly" required="required">
																	</div>
																</div>
															</div><br>
															<label>Gambar</label>
															<p class="text-muted">File harus bertipe *.jpg atau *.png </p>
															<div class="form-group">
																<div class="input-group">
																	<label class="input-group-btn">
																		<span class="btn btn-primary">
																			<i class="fas fa-folder-open"></i> Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;">
																		</span>
																	</label>
																	<input type="text" class="form-control" readonly="readonly" id="gambar_edit">
																</div>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
															<button type="submit" id="btn_editx" style="display: none;"></button>
															<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="struktur"><div class="box box-warning">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="far fa-newspaper"></i> Data Struktur</h3>
											<div class="box-tools pull-right">
												<button class="btn btn-box-tool" onclick="reload_table('table_data_s')"
													data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
												<button class="btn btn-box-tool" data-widget="collapse"
													data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
												<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
											</div>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="col-md-12">
													<div class="row">
														<div class="col-md-12">
															<div class="pull-left">
																<?php 
																	if (in_array($access['l_ac']['add'], $access['access'])) {
																		echo '<button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add_s"><i class="fa fa-plus"></i> Tambah Data</button>';
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
													<div class="collapse" id="add_s">
														<br>
														<div class="col-md-2"></div>
														<div class="col-md-8">
															<form id="form_add_s">
																<div class="form-group">
																	<label>Judul</label>
																	<input type="text" placeholder="Masukkan judul Berita" id="data_judul_add" name="judul" class="form-control field" required="required">
																</div>
																<div class="form-group clearfix">
																	<label>Lokasi</label>
																	<select class="form-control select2" name="lokasi" id="data_lokasi_add" required="required" style="width: 100%;"></select>
																</div>
																<div class="form-group clearfix">
																	<label>Keteragan</label>
																	<textarea name="isi" class="form-control" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Keterangan"></textarea>
																</div><br>
																<label>Gambar</label>
																<p class="text-muted">File harus bertipe *.jpg atau *.png</p>
																<div class="form-group">
																	<div class="input-group">
																		<label class="input-group-btn">
																			<span class="btn btn-primary">
																				<i class="fas fa-folder-open"></i> Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;">
																			</span>
																		</label>
																		<input type="text" class="form-control" readonly>
																	</div>
																</div>
																<div class="form-group">
																	<button type="button" id="btn_add_s" onclick="do_add_s()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	<button type="submit" id="btn_add_sx" style="display: none;"></button>
																</div>
															</form>
														</div>
													</div><?php } ?>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<table id="table_data_s" class="table table-bordered table-striped table-responsive" width="100%">
														<thead>
															<tr>
																<th>No.</th>
																<th>Gambar</th>
																<th>Judul</th>
																<th>Lokasi</th>
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
										<!-- view -->
										<div id="view_s" class="modal fade" role="dialog">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h2 class="modal-title">Detail Data <b class="text-muted header_data" style="font-size: 12pt"></b></h2>
														<input type="hidden" name="data_id_view_s">
													</div>
													<div class="modal-body">
														<div class="row">
															<div class="text-center">
																<div class="col-md-12" id="gambar_berita_view_s">
																</div>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Judul</label>
																	<div class="col-md-6" id="data_judul_view_s"></div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Lokasi</label>
																	<div class="col-md-6" id="data_kategori_view_s"></div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Keterangan</label>
																	<div class="col-md-6" id="data_isi_view_s"></div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Status</label>
																	<div class="col-md-6" id="data_status_view_s"></div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Dibuat Tanggal</label>
																	<div class="col-md-6" id="data_create_date_view_s">
																	</div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Diupdate Tanggal</label>
																	<div class="col-md-6" id="data_update_date_view_s">
																	</div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Dibuat Oleh</label>
																	<div class="col-md-6" id="data_create_by_view_s">
																	</div>
																</div>
																<div class="form-group col-md-12">
																	<label class="col-md-6 control-label">Diupdate Oleh</label>
																	<div class="col-md-6" id="data_update_by_view_s">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
															echo '<button type="submit" class="btn btn-info" onclick="edit_modal_s()"><i class="fa fa-edit"></i> Edit</button>';
														}?>
														<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
													</div>
												</div>
											</div>
										</div>
										<div id="edit_s" class="modal fade" role="dialog">
											<div class="modal-dialog modal-md">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h2 class="modal-title">Edit Data <b class="text-muted header_data" style="font-size: 12pt"></b></h2>
													</div>
													<form id="form_edit_s">
														<div class="modal-body">
															<input type="hidden" id="data_id_edit_s" name="id" value="">
															<div class="form-group">
																<label>Judul</label>
																<input type="text" placeholder="Masukkan Judul" id="data_judul_edit_s" name="judul" value="" class="form-control" required="required">
															</div>
															<div class="form-group clearfix">
																<label>Lokasi</label>
																<select class="form-control select2" name="lokasi" id="data_kategori_edit_s" required="required" style="width: 100%;"></select>
															</div>
															<div class="form-group clearfix">
																<label>Keterangan</label>
																<textarea name="isi" class="form-control" id="data_isi_edit_s" placeholder="Keterangan" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
															</div><br>
															<label>Gambar</label>
															<p class="text-muted">File harus bertipe *.jpg atau *.png </p>
															<div class="form-group">
																<div class="input-group">
																	<label class="input-group-btn">
																		<span class="btn btn-primary">
																			<i class="fas fa-folder-open"></i> Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;">
																		</span>
																	</label>
																	<input type="text" class="form-control" readonly="readonly" id="gambar_edit_s">
																</div>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" onclick="do_edit_s()" id="btn_edit_s" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
															<button type="submit" id="btn_edit_sx" style="display: none;"></button>
															<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select = "<?php echo base_url('global_control/select2_global');?>";
	$(document).ready(function () {
		sejarah();
		submitForm('form_sejarah');
	});
	function submitForm(form) {
		$('#' + form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault();
				if (form == 'form_sejarah') {
					do_edit_sejarah()
				} else {
					do_edit()
				}
			}
		})
	}
	function sejarah() {
		var data = { id: 1 };
		var callback = getAjaxData("<?php echo base_url('master/company_profile/view_one')?>", data);
		$('#sejarah').val(callback['sejarah']);
		$('#visi').val(callback['visi']);
		$('#misi').val(callback['misi']);
		$('#moto').val(callback['sejarah']);
	}
	function do_edit_sejarah() {
		if ($("#form_sejarah")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edit_company')?>", null, 'form_sejarah', null, null);
		} else {
			notValidParamx();
		}
	}
// ========================================== Berita ===================================================//
	function berita() {
		var table = "data_berita";
		var column = "id_berita";
		var url_select = "<?php echo base_url('global_control/select2_global');?>";
		select_data('data_kategori_add', url_select, 'master_kategori_berita', 'id_kategori', 'nama');
		$('#table_data').DataTable().destroy();
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('master/data_berita/view_all/')?>",
				type: 'POST',
				data: {
					access: "<?php echo base64_encode(serialize($access));?>"
				}
			},
			scrollX: true,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 1,
					width: '30%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 2,
					width: '20%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 4,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 7,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
		$('#form_add').submit(function (e) {
			e.preventDefault();
			var data = new FormData(this);
			var urlx = "<?php echo base_url('master/add_berita'); ?>";
			submitAjaxFile(urlx, data, null, null, null);
		});
		$('#form_edit').submit(function (e) {
			e.preventDefault();
			var data = new FormData(this);
			var urlx = "<?php echo base_url('master/edit_berita'); ?>";
			submitAjaxFile(urlx, data, '#edit', null, null);
		});
	};

	function view_modal(id) {
		var data = {
			id_berita: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_berita/view_one')?>", data);
		$('#view').modal('show');
		$('.header_data').html(callback['judul']);
		$('#data_judul_view').html(callback['judul']);
		$('#data_tgl_view').html(callback['tgl_posting']);
		$('#data_kategori_view').html(callback['nama_kategori']);
		$('#data_isi_view').html(callback['isi']);
		$('#gambar_berita_view').html(callback['gambar']);
		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date'] + ' WIB');
		$('#data_update_date_view').html(callback['update_date'] + ' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}

	function edit_modal() {
		select_data('data_kategori_edit', url_select, 'master_kategori_berita', 'id_kategori', 'nama');
		var id = $('input[name="data_id_view"]').val();
		var data = {
			id_berita: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_berita/view_one')?>", data);
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		}, 600);
		$('.header_data').html(callback['judul']);
		$('#data_id_edit').val(callback['id']);
		$('#data_judul_edit').val(callback['judul']);
		$('#data_kategori_edit').val(callback['e_kategori']).trigger('change');
		$('#data_isi_edit').val(callback['isi']);
		$('#tgl_posting_edit').val(callback['e_tgl']);
		$('#gambar_edit').val(callback['e_gambar']);
	}

	function delete_modal(id) {
		var data = {
			id_berita: id
		};
		var col_file = 'gambar';
		var callback = getAjaxData("<?php echo base_url('master/data_berita/view_one')?>", data);
		var table = "data_berita";
		var column = "id_berita";
		var datax = {
			table: table,
			column: column,
			id: id,
			nama: callback['nama'],
			col_file: col_file,
			val_file: callback['e_gambar'],
		};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}

	function do_status(id, data) {
		var table = "data_berita";
		var column = "id_berita";
		var data_table = {
			status: data
		};
		var where = {
			id_berita: id
		};
		var datax = {
			table: table,
			where: where,
			data: data_table
		};
		submitAjax("<?php echo base_url('global_control/change_status')?>", null, datax, null, null, 'status');
		$('#table_data').DataTable().ajax.reload(function () {
			Pace.restart();
		});
	}
	function do_edit() {
		if ($("#form_edit")[0].checkValidity()) {
			$('#btn_editx').click();
		} else {
			notValidParamx();
		}
	}
	function do_add() {
		if ($("#form_add")[0].checkValidity()) {
			$('#btn_addx').click();
			$('#form_add')[0].reset();
		} else {
			notValidParamx();
		}
	}
	$(function () {
		$(document).on('change', ':file', function () {
			var input = $(this),
				numFiles = input.get(0).files ? input.get(0).files.length : 1,
				label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [numFiles, label]);
		});
		$(document).ready(function () {
			$(':file').on('fileselect', function (event, numFiles, label) {

				var input = $(this).parents('.input-group').find(':text'),
					log = numFiles > 1 ? numFiles + ' files selected' : label;

				if (input.length) {
					input.val(log);
				} else {
					if (log) alert(log);
				}
			});
		});
	});
	//============================== STRUKTUR ORGANISASI ==================================================//
	function struktur() {
		var table = "data_struktur";
		var column = "id";
		var url_select = "<?php echo base_url('global_control/select2_global');?>";
		select_data('data_lokasi_add', url_select, 'master_loker', 'kode_loker', 'nama');
		$('#table_data_s').DataTable().destroy();
		$('#table_data_s').DataTable({
			ajax: {
				url: "<?php echo base_url('master/data_struktur/view_all/')?>",
				type: 'POST',
				data: {
					access: "<?php echo base64_encode(serialize($access));?>"
				}
			},
			scrollX: true,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 1,
					width: '30%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 2,
					width: '20%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 4,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 6,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
		$('#form_add_s').submit(function (e) {
			e.preventDefault();
			var data = new FormData(this);
			var urlx = "<?php echo base_url('master/add_struktur'); ?>";
			submitAjaxFile(urlx, data, null, null, null, 'table_data_s');
		});
		$('#form_edit_s').submit(function (e) {
			e.preventDefault();
			var data = new FormData(this);
			var urlx = "<?php echo base_url('master/edit_struktur'); ?>";
			submitAjaxFile(urlx, data, '#edit_s', null, null, null,'table_data_s');
		});
	};

	function view_modal_s(id) {
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_struktur/view_one')?>", data);
		$('#view_s').modal('show');
		$('.header_data').html(callback['judul']);
		$('#data_judul_view_s').html(callback['judul']);
		$('#data_kategori_view_s').html(callback['nama_lokasi']);
		$('#data_isi_view_s').html(callback['isi']);
		$('#gambar_berita_view_s').html(callback['gambar']);
		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view_s').html(statusval);
		$('#data_create_date_view_s').html(callback['create_date'] + ' WIB');
		$('#data_update_date_view_s').html(callback['update_date'] + ' WIB');
		$('input[name="data_id_view_s"]').val(callback['id']);
		$('#data_create_by_view_s').html(callback['nama_buat']);
		$('#data_update_by_view_s').html(callback['nama_update']);
	}

	function edit_modal_s() {
		select_data('data_kategori_edit_s', url_select, 'master_loker', 'kode_loker', 'nama');
		var id = $('input[name="data_id_view_s"]').val();
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('master/data_struktur/view_one')?>", data);
		$('#view_s').modal('toggle');
		setTimeout(function () {
			$('#edit_s').modal('show');
		}, 600);
		$('.header_data').html(callback['judul']);
		$('#data_id_edit_s').val(callback['id']);
		$('#data_judul_edit_s').val(callback['judul']);
		$('#data_kategori_edit_s').val(callback['e_lokasi']).trigger('change');
		$('#data_isi_edit_s').val(callback['isi']);
		$('#gambar_edit_s').val(callback['e_gambar']);
	}

	function delete_modal_s(id) {
		var data = {
			id: id
		};
		var table = "data_struktur";
		var column = "id";
		var col_file = 'gambar';
		var callback = getAjaxData("<?php echo base_url('master/data_struktur/view_one')?>", data);
		var datax = {
			table: table,
			column: column,
			id: id,
			nama: callback['nama'],
			col_file: col_file,
			val_file: callback['e_gambar'],
			table_view:"table_data_s",
		};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>", 'modal_delete_partial', datax, 'delete');
	}

	function do_status_s(id, data) {
		var table = "data_struktur";
		var column = "id";
		var data_table = {
			status: data
		};
		var where = {
			id: id
		};
		var datax = {
			table: table,
			where: where,
			data: data_table
		};
		submitAjax("<?php echo base_url('global_control/change_status')?>", null, datax, null, null, 'status');
		$('#table_data_s').DataTable().ajax.reload(function () {
			Pace.restart();
		});
	}
	function do_edit_s() {
		if ($("#form_edit_s")[0].checkValidity()) {
			$('#btn_edit_sx').click();
		} else {
			notValidParamx();
		}
	}
	function do_add_s() {
		if ($("#form_add_s")[0].checkValidity()) {
			$('#btn_add_sx').click();
			$('#form_add_s')[0].reset();
		} else {
			notValidParamx();
		}
	}

</script>
