<?php
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
$color = $this->otherfunctions->getSkinColorText($adm['skin']);
?>
<style type="text/css">
	.wordwrap {
		white-space: pre-wrap;
		white-space: -moz-pre-wrap;
		white-space: -pre-wrap;
		white-space: -o-pre-wrap;
		word-wrap: break-word;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fa fas fa-user-cog"></i> Mutasi Jabatan
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i>
					Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_mutasi');?>"><i class="fa fas fa-user-cog"></i> Data Mutasi</a>
			</li>
			<li class="active">Profile <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-success">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle view_photo" width="100px"
							data-source-photo="<?php echo base_url($foto); ?>" src="<?php echo base_url($foto); ?>"
							alt="User profile picture">
						<h3 class="profile-username text-center"><?php echo $profile['nama']; ?></h3>
						<?php $grade=isset($profile['nama_grade'])?'<label class="label label-primary text-center">'.$profile['nama_grade'].' ('.$profile['nama_loker_grade'].')</label>':'<label class="label label-danger text-center">Tidak Punya Grade</label>'; ?>
						<p class="text-center"><?php echo $grade; ?></p>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b>Tanggal Masuk</b> <label
									class="pull-right label label-info"><?php echo $this->formatter->getDateMonthFormatUser($profile['tgl_masuk']); ?></label>
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
							<li class="list-group-item clearfix">
								<b>Level Jabatan</b>
								<?php
								if ($profile['nama_level_jabatan'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Level Jabatan</label>';
								}else{
									echo '<label class="pull-right label label-success wordwrap">'.$profile['nama_level_jabatan'].'</label>';
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
										<td><?php echo ucwords($profile['nik']);?> <label
												class="label label-info">Username</label></td>
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
												$alamat=(!empty($profile['alamat_asal_jalan'])?$profile['alamat_asal_jalan'].', ':null).(!empty($profile['alamat_asal_desa'])?$profile['alamat_asal_desa'].', ':null).(!empty($profile['alamat_asal_kecamatan'])?$profile['alamat_asal_kecamatan'].', ':null).(!empty($profile['alamat_asal_kabupaten'])?$profile['alamat_asal_kabupaten'].', ':null).(!empty($profile['alamat_asal_provinsi'])?$profile['alamat_asal_provinsi'].', ':null);
												echo ucwords($alamat).'<br>Kode Pos : ';
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
						<h3 class="box-title"><i class="fa fas fa-user-cog"></i> Data Seluruh Mutasi
							<small><?php echo $profile['nama'];?></small></h3>
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
						<div class="row">
							<div class="col-md-12">
								<div class="pull-left" style="font-size: 8pt;">
									<?php 
										echo form_open('rekap/data_mutasi');
										if (in_array($access['l_ac']['add'], $access['access'])) {
											echo '<button class="btn btn-success" type="button" id="btn_tambah" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data</button>';
										}
										if (in_array($access['l_ac']['rkp'], $access['access'])) {
											echo '<input type="hidden" name="mode" value="view">
											<input type="hidden" name="nik" value="'.$profile['nik'].'">';
											echo '<button type="submit" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';
										}
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
										<p class="text-danger">Semua data harus diisi!</p>
											<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
										<div class="row">
											<input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan']?>">
											<input type="hidden" name="jabatan" value="<?php echo $profile['jabatan']?>">
											<input type="hidden" name="lokasi_asal" value="<?php echo $profile['loker']?>">
											<div class="col-md-6">
												<div class="form-group clearfix">
													<label>Nomor SK</label>
													<div class="row">
														<div class="col-sm-11">
															<input type="text" placeholder="Masukkan Nomor Sk" name="no_sk" id="data_kode_add" class="form-control" value="<?php echo $this->codegenerator->kodeSkMutasi(); ?>" required="required" style="vertical-align: middle;">
														</div>
														<div class="col-sm-1">
															<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
														</div>
													</div>
												</div>
												<div class="form-group clearfix">
													<label>Tanggal SK</label>
													<div class="has-feedback">
														<span class="fa fa-calendar form-control-feedback"></span>
														<input type="text" name="tgl_sk"
															class="form-control pull-right date-picker"
															placeholder="Tanggal SK" readonly="readonly"
															required="required">
													</div>
												</div>
												<div class="form-group clearfix">
													<label>Tanggal Berlaku</label>
													<div class="has-feedback">
														<span class="fa fa-calendar form-control-feedback"></span>
														<input type="text" name="tgl_berlaku"
															class="form-control pull-right  date-picker"
															placeholder="Tanggal Berlaku" readonly="readonly"
															required="required">
													</div>
												</div>
												<div class="form-group clearfix">
													<label id="jabatan_baru">Jabatan Baru</label>
													<select class="form-control select2" name="jabatan_baru"
														id="data_jabatan_add" required="required"
														onchange="cekjabatan(this.value)" style="width: 100%;"></select>
													<div class="text-danger" id="notif_jabatan" style="font-size: 9pt;">
													</div>
												</div>
												<div class="form-group clearfix">
													<label>Lokasi Baru</label>
													<select class="form-control select2" name="lokasi_baru"
														id="data_lokasi_add" required="required"
														style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Status Perubahan</label>
													<select class="form-control select2" name="status_mutasi"
														id="data_status_add" required="required"
														style="width: 100%;"></select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group clearfix">
													<label>Masa Percobaan (Bulan)</label>
													<input type="number" step="0.1" required="required" max="100"
														name="lama_percobaan" class="form-control"
														placeholder="Masa Percobaan">
												</div>
												<div class="form-group clearfix">
													<label>Mengetahui</label>
													<select class="form-control select2" name="mengetahui"
														id="data_mengetahui_add" required="required"
														style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Menyetujui</label>
													<select class="form-control select2" name="menyetujui"
														id="data_menyetujui_add" required="required"
														style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Keterangan (Target)</label>
													<textarea name="keterangan" class="form-control"
														placeholder="Target, indikator penilaian masa evaluasi"></textarea>
												</div>
											</div>
										</div>
										<div class="form-group">
											<button type="submit" id="btn_add" class="btn btn-success pull-right"><i
													class="fa fa-floppy-o"></i> Simpan</button>
										</div>
										<hr>
									</div>
								</form>
							</div>
						</div>
						<?php } ?>
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Nama</th>
									<th>NO SK</th>
									<th>Tanggal SK</th>
									<th>Status</th>
									<th>Jabatan Baru</th>
									<th>Lokasi Baru</th>
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
							<label class="col-md-6 control-label">Nomor SK</label>
							<div class="col-md-6" id="data_nosk_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal SK</label>
							<div class="col-md-6" id="data_tglsk_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Berlaku</label>
							<div class="col-md-6" id="data_tglberlaku_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Mutasi</label>
							<div class="col-md-6" id="data_statusmutasi_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Masa Percobaan</label>
							<div class="col-md-6" id="data_percobaan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_view"></div>
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
				</div>
				<div class="row">
					<div class="form-group col-md-12">
						<div class="form-group col-md-12">
							<label class="col-md-3 control-label">Keterangan (Target)</label>
							<div class="col-md-9" id="data_keterangan_view"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-danger">
							<div class="panel-heading bg-red"><b>Status Lama</b></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Jabatan Lama</label>
									<div class="col-md-6" id="data_jabatanlama_view"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Lokasi Lama</label>
									<div class="col-md-6" id="data_lokasiasal_view"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-success">
							<div class="panel-heading bg-green"><b>Status Baru</b></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Jabatan Baru</label>
									<div class="col-md-6" id="data_jabatanbaru_view"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Lokasi Baru</label>
									<div class="col-md-6" id="data_lokasibaru_view"></div>
								</div>
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
<?php if (in_array($access['l_ac']['edt'], $access['access'])) {?>
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
								<label>NO SK</label>
								<input type="text" placeholder="Masukkan NO SK" id="data_nosk_edit" name="no_sk"
									value="" class="form-control" required="required">
							</div>
							<div class="form-group clearfix">
								<label>Tanggal SK</label>
								<div>
									<div class="has-feedback">
										<span class="fa fa-calendar form-control-feedback"></span>
										<input type="text" id="data_tglsk_edit" value="" name="tgl_sk"
											class="form-control pull-right date-picker" placeholder="Tanggal Berlaku"
											readonly="readonly">
									</div>
								</div>
							</div>
							<div class="form-group clearfix">
								<label>Tanggal Berlaku</label>
								<div>
									<div class="has-feedback">
										<span class="fa fa-calendar form-control-feedback"></span>
										<input type="text" id="data_tglberlaku_edit" value="" name="tgl_berlaku"
											class="form-control pull-right date-picker" placeholder="Tanggal Berlaku"
											readonly="readonly">
									</div>
								</div>
							</div>
							<div class="form-group clearfix">
								<label>NIK</label>
								<input type="text" placeholder="Masukkan Nama Bagian" id="data_nik_edit" name="nik"
									value="" class="form-control" required="required" disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>Nama</label>
								<input type="text" placeholder="Masukkan Nama Bagian" id="data_nama_edit" name="nama"
									value="" class="form-control" required="required" disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>Jabatan Lama</label>
								<input type="text" placeholder="Masukkan Nama Bagian" id="data_jabatanlama_edit"
									name="jabatan_lama" value="" class="form-control" required="required"
									disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>Lokasi Lama</label>
								<input type="text" placeholder="Masukkan Lokasi Asal" id="data_lokasiasal_edit"
									name="lokasi_asal" value="" class="form-control" required="required"
									disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>Status Mutasi</label>
								<select class="form-control select2" name="status_mutasi" id="data_statusmutasi_edit"
									required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label id="jabatan_baru_edit">Jabatan Baru</label>
								<select class="form-control select2" name="jabatan_baru" id="data_jabatanbaru_edit"
									required="required" onchange="cekjabatanedit(this.value)"
									style="width: 100%;"></select>
								<div class="text-danger" id="notif_jabatan_edit" style="font-size: 9pt;"></div>
							</div>
							<div class="form-group clearfix">
								<label>Lokasi Baru</label>
								<select class="form-control select2" name="lokasi_baru" id="data_lokasibaru_edit"
									required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Masa Percobaan (Bulan)</label>
								<input type="number" step="0.1" required="required" max="100" name="lama_percobaan"
									id="data_percobaan_edit" class="form-control" placeholder="Masa Percobaan">
							</div>
							<div class="form-group clearfix">
								<label>Mengetahui</label>
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit"
									required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Menyetujui</label>
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit"
									required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Keterangan (Target)</label>
								<textarea class="form-control" name="keterangan" id="data_keterangan_edit"
									placeholder="Target, indikator penilaian masa evaluasi"></textarea>
							</div>
						</div>
					</div>
				</div>
			<div class="modal-footer">
				<button type="submit" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i>
					Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select = "<?php echo base_url('global_control/select2_global');?>";
	var table = "data_mutasi_jabatan";
	var column = "id_mutasi";
	$(document).ready(function () {
		submitForm('form_add');
		submitForm('form_edit');
		refreshCode();
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('employee/view_mutasi_jabatan/view_all/'.$this->uri->segment(3))?>",
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
					width: '15%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 7,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 8,
					width: '7%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 9,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
		$('#btn_tambah').click(function () {
			select_data('data_lokasi_add', url_select, 'master_loker', 'kode_loker', 'nama');
			select_data('data_status_add', url_select, 'master_mutasi', 'kode_mutasi', 'nama');
			getSelect2("<?php echo base_url('master/master_jabatan/nama_jabatan')?>", 'data_jabatan_add');
			getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",
				'data_mengetahui_add,#data_menyetujui_add');
			unsetoption('data_jabatan_add', ['JBT01', 'JBT02', 'JBT03']);
		})
	});
	function refreshCode() {
		kode_generator("<?php echo base_url('employee/mutasi_jabatan/kode');?>", 'data_kode_add');
	}
	function resetselectAdd() {
		$('#data_jabatan_add').val('').trigger('change');
		$('#data_lokasi_add').val('').trigger('change');
		$('#data_status_add').val('').trigger('change');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
	}
	function view_modal(id) {
		var data = {
			id_mutasi: id
		};
		var callback = getAjaxData("<?php echo base_url('employee/view_mutasi_jabatan/view_one')?>", data);
		$('#view').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nosk_view').html(callback['no_sk']);
		$('#data_tglsk_view').html(callback['tgl_sk']);
		$('#data_tglberlaku_view').html(callback['tgl_berlaku']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_lokasiasal_view').html(callback['lokasi_asal']);
		$('#data_lokasibaru_view').html(callback['vlokasi_baru']);
		$('#data_statusmutasi_view').html(callback['vstatus_mutasi']);
		$('#data_jabatanlama_view').html(callback['jabatan_lama']);
		$('#data_jabatanbaru_view').html(callback['vjabatan_baru']);
		$('#data_mengetahui_view').html(callback['vmengetahui']);
		$('#data_percobaan_view').html(callback['vpercobaan']);
		$('#data_menyetujui_view').html(callback['vmenyetujui']);
		$('#data_keterangan_view').html(callback['vketerangan']);
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
		select_data('data_lokasibaru_edit', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('data_statusmutasi_edit', url_select, 'master_mutasi', 'kode_mutasi', 'nama');
		getSelect2("<?php echo base_url('master/master_jabatan/nama_jabatan')?>", 'data_jabatanbaru_edit');
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",
			'data_mengetahui_edit,#data_menyetujui_edit');
		unsetoption('data_jabatanbaru_edit', ['JBT01', 'JBT02', 'JBT03']);
		var id = $('input[name="data_id_view"]').val();
		var data = {
			id_mutasi: id
		};
		var callback = getAjaxData("<?php echo base_url('employee/view_mutasi_jabatan/view_one')?>", data);
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		}, 600);
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_nosk_edit').val(callback['no_sk']);
		$('#data_tglsk_edit').val(callback['tgl_sk_e']);
		$('#data_tglberlaku_edit').val(callback['tgl_berlaku_e']);
		$('#data_idk_edit').val(callback['id_karyawan']);
		$('#data_nik_edit').val(callback['nik']);
		$('#data_nama_edit').val(callback['nama']);
		$('#data_lokasiasal_edit').val(callback['vlokasi_asal']);
		$('#data_lokasibaru_edit').val(callback['lokasi_baru']).trigger('change');
		$('#data_statusmutasi_edit').val(callback['status_mutasi']).trigger('change');
		$('#data_jabatanlama_edit').val(callback['vjabatan_lama']);
		$('#data_jabatanbaru_edit').val(callback['jabatan_baru']).trigger('change');
		$('#data_percobaan_edit').val(callback['percobaan']);
		$('#data_mengetahui_edit').val(callback['mengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['menyetujui']).trigger('change');
		$('#data_keterangan_edit').val(callback['keterangan']);
		// addValueEditor('data_keterangan_edit',callback['keterangan']);
	}
	function delete_modal(id) {
		var data = {
			id_mutasi: id
		};
		var callback = getAjaxData("<?php echo base_url('employee/view_mutasi_jabatan/view_one')?>", data);
		var datax = {
			table: table,
			column: column,
			id: id,
			nama: callback['nama']
		};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>", 'modal_delete_partial', datax, 'delete');
	}
	function do_status(id, data) {
		var data_table = {
			status: data
		};
		var where = {
			id_mutasi: id
		};
		var datax = {
			table: table,
			where: where,
			data: data_table
		};
		submitAjax("<?php echo base_url('global_control/change_status')?>", null, datax, null, null, 'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function submitForm(form) {
		$('#' + form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault();
				if (form == 'form_add') {
					do_add()
				} else {
					do_edit()
				}
			}
		})
	}
	function do_edit() {
		if ($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/edit_mutasi')?>", 'edit', 'form_edit', null, null);
			$('#table_data').DataTable().ajax.reload();
		} else {
			notValidParamx();
		}
	}
	function do_add() {
		if ($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_mutasi')?>", null, 'form_add', null, null);
			$('#table_data').DataTable().ajax.reload(function () {
				Pace.restart();
			});
			$('#form_add')[0].reset();
			refreshCode();
			resetselectAdd();
		} else {
			notValidParamx();
		}
	}
	function do_print(id) {
		var nik = "<?php echo $this->uri->segment(3)?>";
		window.location.href = "<?php echo base_url('cetak_word/cetak_mutasi/')?>" + id + '/' + nik;
	}
	function cekjabatan(data_jabatan_add) {
		var jabatan = $('#data_jabatan_add').val();
		var id_karyawan = $('#id_karyawan').val();
		var data = {
			jabatan: jabatan,
			id_karyawan: id_karyawan
		};
		var callback = getAjaxData("<?php echo base_url('employee/mutasi_jabatan/cekjabatan')?>", data);
		if (callback['val'] == 'true') {
			$('#data_jabatan_add').css('border-color', '#00A65A');
			$('#notif_jabatan').html('Jabatan Tersedia');
			$('#notif_jabatan').css('color', '#00A65A');
			$('#jabatan_baru').css('color', '#00A65A');
			$('#btn_add').removeAttr('disabled', 'disabled');
		} else {
			$('#data_jabatan_add').css('border-color', '#DD4B39');
			$('#notif_jabatan').html('Jabatan Sudah Ada yang manjabat');
			$('#notif_jabatan').css('color', '#DD4B39');
			$('#jabatan_baru').css('color', '#DD4B39');
			$('#btn_add').attr('disabled', 'disabled');
		}
	}
	function cekjabatanedit(data_jabatanbaru_edit) {
		var jabatan = $('#data_jabatanbaru_edit').val();
		var id_karyawan = $('#id_karyawan').val();
		var data = {
			jabatan: jabatan,
			id_karyawan: id_karyawan
		};
		var callback = getAjaxData("<?php echo base_url('employee/mutasi_jabatan/cekjabatan')?>", data);
		if (callback['val'] == 'true') {
			$('#data_jabatanbaru_edit').css('border-color', '#00A65A');
			$('#notif_jabatan_edit').html('Jabatan Tersedia');
			$('#notif_jabatan_edit').css('color', '#00A65A');
			$('#jabatan_baru_edit').css('color', '#00A65A');
			$('#btn_edit').removeAttr('disabled', 'disabled');
		} else {
			$('#data_jabatanbaru_edit').css('border-color', '#DD4B39');
			$('#notif_jabatan_edit').html('Jabatan Sudah Ada yang manjabat');
			$('#notif_jabatan_edit').css('color', '#DD4B39');
			$('#jabatan_baru_edit').css('color', '#DD4B39');
			$('#btn_edit').attr('disabled', 'disabled');
		}
	}
</script>
