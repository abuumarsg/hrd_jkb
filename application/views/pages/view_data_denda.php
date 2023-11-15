<?php
 	$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
	$color = $this->otherfunctions->getSkinColorText($adm['skin']);
?>
<style type="text/css">
	.wordwrap { 
	   white-space: pre-wrap;      /* CSS3 */   
	   white-space: -moz-pre-wrap; /* Firefox */    
	   white-space: -pre-wrap;     /* Opera <7 */   
	   white-space: -o-pre-wrap;   /* Opera 7 */    
	   word-wrap: break-word;      /* IE */
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-dollar-sign"></i> Denda Karyawan
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_peringatan');?>"><i class="fas fa-dollar-sign"></i> Data Denda Karyawan</a></li>
			<li class="active">Profile <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-success">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle view_photo" width="100px" data-source-photo="<?php echo base_url($foto); ?>" src="<?php echo base_url($foto); ?>" alt="User profile picture">
						<h3 class="profile-username text-center"><?php echo $profile['nama']; ?></h3>
						<?php $grade=isset($profile['nama_grade'])?'<label class="label label-primary text-center">'.$profile['nama_grade'].' ('.$profile['nama_loker_grade'].')</label>':'<label class="label label-danger text-center">Tidak Punya Grade</label>'; ?>
						<p class="text-center"><?php echo $grade; ?></p>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b>Tanggal Masuk</b> <label class="pull-right label label-info"><?php echo $this->formatter->getDateMonthFormatUser($profile['tgl_masuk']); ?></label>
							</li>
							<li class="list-group-item">
								<b>Status Karyawan</b>
								<?php
								if ($profile['nama_status'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Status</label>';
								}else{
									echo '<label class="pull-right label label-warning">'.$profile['nama_status'].'</label>';
								}?>
							</li>
							<li class="list-group-item">
								<b>Level Jabatan</b>
								<?php
								if ($profile['nama_level_jabatan'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Level Jabatan</label>';
								}else{
									echo '<label class="pull-right label label-success">'.$profile['nama_level_jabatan'].'</label>';
								}
								?>
							</li>
							<li class="list-group-item">
								<b>Lokasi Kerja</b><?php
								if ($profile['nama_loker'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
								}else{
									echo '<label class="pull-right label label-success">'.$profile['nama_loker'].'</label>';
								}
								?>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li><a href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="info" style="overflow:auto;">
								<table class='table table-bordered table-striped table-hover'>
									<tr>
										<th>Nomor Induk Karyawan</th>
										<td><?php echo ucwords($profile['nik']);?> <label class="label label-info">Username</label></td>
									</tr>
									<tr>
										<th>ID Finger</th>
										<td><?php
										if ($profile['id_finger'] == NULL) {
											echo '<label class="label label-danger">ID Finger Tidak Ada</label>';
										}else{
											echo $profile['id_finger'];
										}
										?></td>
									</tr>
									<tr>
										<th>Nomor KTP</th>
										<td><?php
										if ($profile['no_ktp'] == NULL) {
											echo '<label class="label label-danger">Nomor KTP Tidak Ada</label>';
										}else{
											echo $profile['no_ktp'];
										}
										?></td>
									</tr>
									<tr>
										<th>Nama Lengkap</th>
										<td><?php echo ucwords($profile['nama']);?></td>
									</tr>
									<tr>
										<th>Alamat Asli</th>
										<td><?php
										if ($profile['alamat_asal_jalan'] == NULL || $profile['alamat_asal_jalan'] == "") {
											echo '<label class="label label-danger">Alamat Belum Diinput</label>';
										}else{
											$alamat_skrg=(!empty($profile['alamat_asal_jalan'])?$profile['alamat_asal_jalan'].', ':null).(!empty($profile['alamat_asal_desa'])?$profile['alamat_asal_desa'].', ':null).(!empty($profile['alamat_asal_kecamatan'])?$profile['alamat_asal_kecamatan'].', ':null).(!empty($profile['alamat_asal_kabupaten'])?$profile['alamat_asal_kabupaten'].', ':null).(!empty($profile['alamat_asal_provinsi'])?$profile['alamat_asal_provinsi'].', ':null);
											echo ucwords($alamat_skrg).' <br>Kode Pos : ';
											if ($profile['alamat_asal_pos'] == 0) {
												echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
											}else{
												echo ucwords($profile['alamat_asal_pos']);
											}
										}  ?>
										</td>
									</tr>
									<tr>
										<th>Alamat Sekarang</th>
										<td><?php
										if ($profile['alamat_sekarang_jalan'] == NULL || $profile['alamat_sekarang_jalan'] == "") {
											echo '<label class="label label-danger">Alamat Belum Diinput</label>';
										}else{
											$alamat_skrg=(!empty($profile['alamat_sekarang_jalan'])?$profile['alamat_sekarang_jalan'].', ':null).(!empty($profile['alamat_sekarang_desa'])?$profile['alamat_sekarang_desa'].', ':null).(!empty($profile['alamat_sekarang_kecamatan'])?$profile['alamat_sekarang_kecamatan'].', ':null).(!empty($profile['alamat_sekarang_kabupaten'])?$profile['alamat_sekarang_kabupaten'].', ':null).(!empty($profile['alamat_sekarang_provinsi'])?$profile['alamat_sekarang_provinsi'].', ':null);
											echo ucwords($alamat_skrg).' <br>Kode Pos : ';
											if ($profile['alamat_sekarang_pos'] == 0) {
												echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
											}else{
												echo ucwords($profile['alamat_sekarang_pos']);
											}
										}  ?>
										</td>
									</tr>
									<tr>
										<th>Jabatan Sekarang</th>
										<td><?php if ($profile['nama_jabatan'] == "") {
											echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
										}else{
											echo $profile['nama_jabatan'];
										}?>
										</td>
									</tr>
									<tr>
										<th>Lokasi Kerja</th>
										<td><?php
										if ($profile['nama_loker'] == "") {
											echo '<label class="pull-right label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
										}else{
											echo $profile['nama_loker'];
										} ?>
										</td>
									</tr>
								</table>	
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-dollar-sign"></i> Data Seluruh Denda <small><?php echo $profile['nama'];?></small></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data','table_data_non')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a onclick="peringatan();" id="btn_info" href="#peringatan" data-toggle="tab"><i class="fas fa-exclamation-circle"></i> Data Denda Peringatan</a></li>
								<li><a onclick="non();" href="#non" data-toggle="tab"><i class="fas fa-exclamation"></i> Data Denda Non Peringatan</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="peringatan" style="overflow:auto;">
									<div class="row">
										<div class="col-md-12">
											<div class="pull-left" style="font-size: 8pt;">
												<?php 
												echo form_open('rekap/view_peringatan_karyawan');
												if (in_array($access['l_ac']['rkp'], $access['access'])) {
													echo '<input type="hidden" name="nik" value="'.$this->codegenerator->decryptChar($this->uri->segment(3)).'">';
													echo '<button type="submit" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>'; }
												echo form_close(); 
												?>
											</div>
											<div class="pull-right" style="font-size: 8pt;">
												<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
												<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
											</div>
										</div>
									</div>
									<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
										<thead>
											<tr>
												<th>No.</th>
												<th>Nama</th>
												<th>Kode Denda</th>
												<th>No SK Peringatan</th>
												<th>Total Denda</th>
												<th>Diangsur</th>
												<th>Sudah Diangsur</th>
												<th>Saldo Denda</th>
												<th>Tanggal</th>
												<th>Status</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="non" style="overflow:auto;">
									<div class="row">
										<div class="col-md-12">
											<div class="pull-left" style="font-size: 8pt;">
												<?php 
												echo form_open('rekap/view_peringatan_karyawan');
												if (in_array($access['l_ac']['add'], $access['access'])) {
													echo '<button class="btn btn-success" type="button" id="btn_tambah" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data</button>'; }
												if (in_array($access['l_ac']['rkp'], $access['access'])) {
													echo '<input type="hidden" name="nik" value="'.$this->codegenerator->decryptChar($this->uri->segment(3)).'">';
													echo '<button type="submit" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>'; }
												echo form_close(); 
												?>
											</div>
											<div class="pull-right" style="font-size: 8pt;">
												<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
												<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
											</div>
										</div>
									</div>
									<?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
									<div class="collapse" id="add">
										<div class="col-md-12">
											<form id="form_add">
												<div class="col-md-1"></div>
												<div class="col-md-10">
													<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
													<div class="row">
														<div class="col-md-6">
															<input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan']?>">
															<div class="form-group clearfix">
																<label>Nomor Denda</label>
																<div class="row">
																	<div class="col-sm-11">
																		<input type="text" placeholder="Masukkan Nomor Sk" name="kode" id="kode_denda_add" class="form-control" value="<?php echo $this->codegenerator->kodePeringatanKerja();?>" required="required">
																	</div>
																	<div class="col-sm-1">
																		<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
																	</div>
																</div>
															</div>
															<div class="form-group clearfix">
																<label>Tanggal Denda</label>
																<div class="has-feedback">
																	<span class="fa fa-calendar form-control-feedback"></span>
																	<input type="text" name="tgl_denda" class="form-control pull-right date-picker" placeholder="Tanggal Denda" readonly="readonly" required="required">
																</div>
															</div>
															<div class="form-group">
																<div class="col md-12">
																	<label>Besaran Denda</label>
																	<input type="text" name="total_denda" id="besaran_denda" class="form-control input-money" placeholder="Besaran Denda">
																</div>
																<div class="col md-12">
																	<label>Jumlah Angsuran (Berapa Kali)</label>
																	<input type="number" min="0" name="angsuran" id="angsuran" class="form-control" placeholder="Berapa Kali Angsuran">
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group clearfix">
																<label>Mengetahui</label>
																<select class="form-control select2" name="mengetahui" id="data_mengetahui_add" required="required" style="width: 100%;"></select>
															</div>
															<div class="form-group clearfix">
																<label>Menyetujui</label>
																<select class="form-control select2" name="menyetujui" id="data_menyetujui_add" required="required" style="width: 100%;"></select>
															</div>
															<div class="form-group clearfix">
																<label>Dibuat</label>
																<select class="form-control select2" name="dibuat" id="data_dibuat_add" required="required" style="width: 100%;"></select>
															</div>
															<div class="form-group clearfix">
																<label>Keterangan</label>
																<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
															</div>
														</div>
													</div>
													<div class="form-group">
														<button type="submit" id="btn_add" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
													</div>
													<hr>
												</div>
											</form>
										</div>
									</div><?php } ?>
									<table id="table_data_non" class="table table-bordered table-striped data-table" width="100%">
										<thead>
											<tr>
												<th>No.</th>
												<th>Nama</th>
												<th>Kode Denda</th>
												<th>Total Denda</th>
												<th>Diangsur</th>
												<th>Sudah Diangsur</th>
												<th>Saldo Denda</th>
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
		</div>
	</section>
</div>
<!-- view peringatan-->
<div id="view_per" class="modal fade" role="dialog">
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
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Denda</label>
							<div class="col-md-6" id="data_tgl_denda_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Denda</label>
							<div class="col-md-6" id="data_jumlah_denda_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Angsuran</label>
							<div class="col-md-6" id="data_jumlah_angsuran_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat</label>
							<div class="col-md-6" id="data_dibuat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
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
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_per"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
//echo '<button type="submit" class="btn btn-info" onclick="edit_modal_per()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- view non-->
<div id="view_non" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_non">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Denda</label>
							<div class="col-md-6" id="data_tgl_denda_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Denda</label>
							<div class="col-md-6" id="data_jumlah_denda_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Angsuran</label>
							<div class="col-md-6" id="data_jumlah_angsuran_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat</label>
							<div class="col-md-6" id="data_dibuat_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_non"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_non">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_non">
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_non"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" id="simpan_non" onclick="edit_modal_non()" style="display:none;"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<!-- edit -->
<?php if (in_array($access['l_ac']['edt'], $access['access'])) { ?>
	<div id="edit_non" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
				</div>
				<div class="modal-body">
					<div class="row">
						<form id="form_edit">
							<div class="col-md-12">
								<div class="col-md-6">
									<input type="hidden" id="data_id_edit" name="id" value="">
									<input type="hidden" id="data_idk_edit" name="id_karyawan" value="">
									<div class="form-group clearfix">
										<label>Nomor Denda</label>
										<input type="text" placeholder="Masukkan Nomor Denda" id="data_nodenda_edit" name="no_denda" value="" class="form-control" required="required">
									</div>
									<div class="form-group clearfix">
										<label>NIK</label>
										<input type="text" placeholder="Masukkan NIK" id="data_nik_edit" name="nik" value="" class="form-control" readonly="readonly" required="required" disabled="disabled">
									</div>
									<div class="form-group clearfix">
										<label>Nama</label>
										<input type="text" placeholder="Masukkan Nama" id="data_nama_edit" name="nama" value="" class="form-control" readonly="readonly" disabled="disabled">
									</div>
									<div class="form-group clearfix">
										<label>Tanggal Denda</label>
										<div>
											<div class="has-feedback">
												<span class="fa fa-calendar form-control-feedback"></span>
												<input type="text" id="data_tgl_denda_edit" value="" name="tgl_denda" class="form-control pull-right date-picker" placeholder="Tanggal Berlaku" readonly="readonly" required="required">
											</div>
										</div>
									</div>
									<div class="form-group clearfix">
										<label>Besaran Denda</label>
										<input type="text" name="besaran_denda" id="besaran_denda_edit" class="form-control input-money" placeholder="Besaran Denda">
									</div>
									<div class="form-group clearfix">
										<label>Jumlah Angsuran (Berapa Kali)</label>
										<input type="number" min="0" name="angsuran_edit" id="angsuran_edit" class="form-control" placeholder="Berapa Kali Angsuran">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group clearfix">
										<label>Mengetahui</label>
										<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Menyetujui</label>
										<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Dibuat</label>
										<select class="form-control select2" name="dibuat" id="data_dibuat_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Keterangan</label>
										<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan" required="required"></textarea>
									</div>
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
	</div>
<?php } ?>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_denda";
	var column="id_denda";
	$(document).ready(function(){
		refreshCode();
		submitForm('form_add');
		submitForm('form_edit');
		peringatan();
		non();
	});
	function peringatan(){
		$('#table_data').DataTable().destroy();
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('employee/view_data_denda/view_all/'.$this->uri->segment(3))?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
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
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 9,
				width: '7%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 10, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function non(){
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add,#data_dibuat_add');
		$('#table_data_non').DataTable().destroy();
		$('#table_data_non').DataTable( {
			ajax: {
				url: "<?php echo base_url('employee/view_data_denda/view_all_non/'.$this->uri->segment(3))?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
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
			{   targets: 7,
				width: '5%',
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
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('employee/data_denda/kode_denda');?>",'kode_denda_add');
	}
	function resetselectAdd() {
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
		$('#data_dibuat_add').val('').trigger('change');
	}
	function view_modal_per(id) {
		var data={id_denda:id};
		var callback=getAjaxData("<?php echo base_url('employee/view_data_denda/view_one_per')?>",data);  
		$('#view_per').modal('show');
		$('.header_data').html(callback['kode']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_tgl_denda_view').html(callback['tgl_denda']);
		$('#data_jumlah_denda_view').html(callback['jumlah_denda']);
		$('#data_jumlah_angsuran_view').html(callback['jumlah_angsuran']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
		$('#data_dibuat_view').html(callback['dibuat']);
		$('#data_keterangan_view').html(callback['keterangan']);
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
		$('#data_tabel_per').html(callback['tabel_per']);
	}
	function view_modal_non(id) {
		var data={id_denda:id};
		var callback=getAjaxData("<?php echo base_url('employee/view_data_denda/view_one_non')?>",data);  
		$('#view_non').modal('show');
		$('.header_data').html(callback['kode']);
		$('#data_nik_non').html(callback['nik']);
		$('#data_nama_non').html(callback['nama']);
		$('#data_loker_non').html(callback['loker']);
		$('#data_jabatan_non').html(callback['jabatan']);
		$('#data_tgl_denda_non').html(callback['tgl_denda']);
		$('#data_jumlah_denda_non').html(callback['jumlah_denda']);
		$('#data_jumlah_angsuran_non').html(callback['jumlah_angsuran']);
		$('#data_mengetahui_non').html(callback['mengetahui']);
		$('#data_menyetujui_non').html(callback['menyetujui']);
		$('#data_dibuat_non').html(callback['dibuat']);
		$('#data_keterangan_non').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_non').html(statusval);
		$('#data_create_date_non').html(callback['create_date']+' WIB');
		$('#data_update_date_non').html(callback['update_date']+' WIB');
		$('input[name="data_id_non"]').val(callback['id']);
		$('#data_create_by_non').html(callback['nama_buat']);
		$('#data_update_by_non').html(callback['nama_update']);
		$('#data_tabel_non').html(callback['tabel_non']);
		var smpn = callback['tabel_non'];
		if(smpn==''){
			$('#simpan_non').show();
		}else{
			$('#simpan_non').hide();
		}
	}
	function edit_modal_non() {
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit,#data_dibuat_edit');
		var id = $('input[name="data_id_non"]').val();
		var data={id_denda:id};
		var callback=getAjaxData("<?php echo base_url('employee/view_data_denda/view_one_non')?>",data); 
		$('#view_non').modal('toggle');
		setTimeout(function () {
			$('#edit_non').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_idk_edit').val(callback['id_karyawan']);
		$('#data_nodenda_edit').val(callback['kode']);
		$('#data_nik_edit').val(callback['nik']);
		$('#data_nama_edit').val(callback['nama']);
		$('#data_tgl_denda_edit').val(callback['tgl_denda_e']);
		$('#besaran_denda_edit').val(callback['jumlah_denda']);
		$('#angsuran_edit').val(callback['jumlah_angsuran_e']);
		$('#data_mengetahui_edit').val(callback['emengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['emenyetujui']).trigger('change');
		$('#data_dibuat_edit').val(callback['edibuat']).trigger('change');
		$('#data_keterangan_edit').val(callback['eketerangan']);
// addValueEditor('data_pelanggaran_edit',callback['epelanggaran']);
// addValueEditor('data_keterangan_edit',callback['eketerangan']);
	}
	function delete_modal(id) {
		var data={id_peringatan:id};
		var callback=getAjaxData("<?php echo base_url('employee/view_peringatan_karyawan/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_denda:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
		$('#table_data_non').DataTable().ajax.reload();
	}
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
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/edit_denda')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
			$('#table_data_non').DataTable().ajax.reload();
		}else{
			refreshCode();
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_denda')?>",null,'form_add',null,null);
			$('#table_data_non').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			refreshCode();
			resetselectAdd();
		}else{
			notValidParamx();
		} 
	}
	function do_print(id) {
		var nik="<?php echo $this->uri->segment(3)?>";
		window.location.href = "<?php echo base_url('cetak_word/cetak_peringatan/')?>"+id+'/'+nik;
	}
</script> 