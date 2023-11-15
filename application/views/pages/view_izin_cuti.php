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
			<i class="fas fa-calendar-times"></i> Data Izin Cuti
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_izin_cuti');?>"><i class="fas fa-calendar-times"></i> Data Izin Cuti</a></li>
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
								} ?>
							</li>
							<li class="list-group-item clearfix">
								<b>Level Jabatan</b>
								<?php if ($profile['nama_level_jabatan'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Level Jabatan</label>';
								}else{
									echo '<label class="pull-right label label-success wordwrap">'.$profile['nama_level_jabatan'].'</label>';
								} ?>
							</li>
							<li class="list-group-item">
								<b>Lokasi Kerja</b><?php
								if ($profile['nama_loker'] == "") {
									echo '<label class="pull-right label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
								}else{
									echo '<label class="pull-right label label-success">'.$profile['nama_loker'].'</label>';
								} ?>
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
										<th>Sisa Cuti</th>
										<td><?php if ($profile['sisa_cuti'] == "") {
											echo '<label class="label label-danger text-center">Tidak Hak Cuti</label>';
										}else{
											echo $profile['sisa_cuti'].' Hari';
										}?>
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
									echo form_open('rekap/export_izin_cuti');
									if (in_array($access['l_ac']['add'], $access['access'])) {
										echo '<button class="btn btn-success" type="button" id="btn_tambah" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data</button>';
									}
									echo '<button class="btn btn-info" type="button" id="btn_sisa_cuti"><i class="fa fa-eye"></i> Lihat Sisa Cuti</button> ';
									if (in_array($access['l_ac']['rkp'], $access['access'])) {
										echo '<input type="hidden" name="nik" value="'.$profile['nik'].'">';
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
									<div class="row">
										<div class="col-md-12">
										<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
											<div class="col-md-1"></div>
											<div class="col-md-5">
												<input type="hidden" name="id_karyawan_cuti" id="id_karyawan_cuti" value="<?php echo $profile['id_karyawan']?>">
												<div class="form-group clearfix">
													<label>Nomor Izin/Cuti</label>
													<div class="row">
														<div class="col-sm-11">
															<input type="text" placeholder="Masukkan Nomor Izin/Cuti" name="no_cuti" id="data_kode_add" class="form-control" value="<?php echo $this->codegenerator->kodeIzinCuti(); ?>" required="required">
														</div>
														<div class="col-sm-1">
															<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
														</div>
													</div>
												</div>
												<div class="form-group clearfix">
													<label>Tanggal Mulai - Selesai</label>
													<div class="has-feedback">
														<span class="fa fa-calendar form-control-feedback"></span>
														<input type="text" name="tanggal" id="tanggal_izin_add" class="form-control pull-right date-range" placeholder="Tanggal Cuti" readonly="readonly" required="required">
													</div>
													<span id="div_span_tgl"></span>
												</div>
												<div class="form-group clearfix">
													<label>Jenis Izin/Cuti</label>
													<select class="form-control select2" name="jenis_cuti" id="data_jenis_cuti_add" required="required" style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Menyetujui</label>
													<select class="form-control select2" name="menyetujui" id="data_menyetujui_add" required="required" style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Menyetujui 2</label>
													<select class="form-control select2" name="menyetujui2" id="data_menyetujui2_add" required="required" style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Mengetahui</label>
													<select class="form-control select2" name="mengetahui" id="data_mengetahui_add" required="required" style="width: 100%;"></select>
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group clearfix">
													<label>SKD Dibayar</label><br>
													<a id="skd_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
													<a id="skd_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
													<input type="hidden" name="skd" id="skd_add" class="form-control" placeholder="SKD Dibayar" readonly>
												</div>
												<div class="form-group clearfix">
													<label>Alasan Izin/Cuti</label>
													<textarea name="alasan_cuti" class="form-control" placeholder="Alasan Cuti" required="required"></textarea>
												</div>
												<div class="form-group clearfix">
													<label>Keterangan</label>
													<textarea name="keterangan_cuti" class="form-control" placeholder="Keterangan"></textarea>
													<span id="div_span_tgl_izin"></span><br>
													<span id="div_span_sisa_cuti"></span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-1"></div>
											<div class="col-md-5"></div>
											<div class="col-md-5">
												<div class="form-group">
													<button type="submit" id="btn_save" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div><?php } ?>
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>NO Izin/Cuti</th>
									<th>Jenis</th>
									<th>Nama Jenis</th>
									<th>Tanggal Mulai</th>
									<th>Tanggal Selesai</th>
									<th>Status Validasi</th>
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
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">SKD Dibayar</label>
							<div class="col-md-6" id="data_skd_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alasan</label>
							<div class="col-md-6" id="data_alasan_view"></div>
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
							<label class="col-md-6 control-label">Menyetujui 2</label>
							<div class="col-md-6" id="data_menyetujui2_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
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
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" id="btn_edit_view" class="btn btn-info" onclick="edit_modal()" style="display:none:"><i class="fa fa-edit"></i> Edit</button>';
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
								<label>Tanggal Mulai - Selesai</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tanggal" class="form-control pull-right date-range" id="data_tgl_cuti_edit" value="" placeholder="Tanggal Cuti" readonly="readonly" required="required">
								</div>
							</div>
							<input type="hidden" name="tanggal_old" class="form-control pull-right date-range" id="data_tgl_cuti_old" value="" placeholder="Tanggal Cuti" readonly="readonly" required="required">
							<div class="form-group clearfix">
								<label>Jenis Izin/Cuti</label>
								<select class="form-control select2" name="jenis_cuti" id="data_jenis_edit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Menyetujui</label>
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Menyetujui 2</label>
								<select class="form-control select2" name="menyetujui2" id="data_menyetujui2_edit" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label>Mengetahui</label>
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit" required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>SKD Dibayar</label><br>
								<a id="skd_no_edit" style="font-size: 16pt;"><i class="far fa-square"></i></a>
								<a id="skd_yes_edit" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
								<input type="hidden" name="skd" id="skd_edit" class="form-control" placeholder="SKD Dibayar" readonly>
							</div>
							<div class="form-group clearfix">
								<label>Alasan</label>
								<textarea class="form-control" name="alasan" id="data_alasan_edit" placeholder="Alasan" required="required"></textarea>
							</div>
							<div class="form-group clearfix">
								<label>Keterangan</label>
								<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="_view" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="m_need" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_need">
				<div class="modal-body text-center">
					<input type="hidden" id="data_id_need" name="id">
					<input type="hidden" id="data_idk_need" name="id_kar">
					<input type="hidden" id="data_jenis_need" name="jenis">
					<p>Mohon Validasi Izin / Cuti Karyawan atas nama <b id="data_name_need" class="header_data"></b> berikut !!</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(2,0,'m_need')" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
				<button type="button" onclick="do_validasi(2,1,'m_need')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
			</div>
		</div>
	</div>
</div>
<div id="m_yes" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_yes">
				<div class="modal-body text-center">
					<input type="hidden" id="data_id_yes" name="id">
					<input type="hidden" id="data_idk_yes" name="id_kar">
					<input type="hidden" id="data_jenis_yes" name="jenis">
					<p>Apakah Anda yakin akan mengubah status izin / cuti dari <b class="text-green">DiIzinkan</b> menjadi <b class="text-red">Tidak Diizinkan</b></b> atas nama karyawan <b id="data_name_yes" class="header_data"></b> ??</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(1,0,'m_yes')" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="m_no" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_no">
				<div class="modal-body text-center">
					<input type="hidden" id="data_id_no" name="id">
					<input type="hidden" id="data_idk_no" name="id_kar">
					<input type="hidden" id="data_jenis_no" name="jenis">
					<p>Apakah Anda yakin akan mengubah status izin / cuti dari <b class="text-red">Tidak Diizinkan</b> menjadi <b class="text-green">DiIzinkan</b></b> atas nama karyawan <b id="data_name_no" class="header_data"></b> ??</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(0,1,'m_no')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
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
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak SPL</h4>
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
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_izin_cuti_karyawan";
	var column="id_izin_cuti";
	$(document).ready(function(){
		refreshCode();
		submitForm('form_add');
		submitForm('form_edit');
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
		getSelect2("<?php echo base_url('presensi/izin_cuti/izincuti')?>",'data_jenis_cuti_add');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add,#data_menyetujui2_add');
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/view_izin_cuti/view_all/'.$this->uri->segment(3))?>",
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
			{   targets: 8,
				width: '6%',
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
			var tgl_ini=getAjaxData("<?php echo base_url('presensi/cekTanggalIzin')?>",datax);
			if (tgl_ini['cek'] > 0) {
				$('#div_span_tgl_izin').html(tgl_ini['msg']).css('color','red');
				$('#btn_save').prop('disabled', true);
			}else{
				$('#div_span_tgl_izin').html(tgl_ini['msg']).css('color','green');
				$('#btn_save').prop('disabled', false); 
			};
			var sisacuti=getAjaxData("<?php echo base_url('presensi/cekSisaCuti')?>",datax);
			var tgl_izin=getAjaxData("<?php echo base_url('presensi/tanggalIzin')?>",datax);
			if (tgl_izin['jenis'] == 'C') {
				if (tgl_izin['hari'] > tgl_izin['maksimal']) {
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}else{
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','green');
					$('#btn_save').prop('disabled', false);
				}
			}else{
				if (tgl_izin['maksimal'] < tgl_izin['hari']) {
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}else{
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
				}
				$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
				$('#btn_save').prop('disabled', false); 
			};
			if (tgl_izin['jenis'] == 'C' && sisacuti['potong_cuti'] == '1') {
				if (sisacuti['sisa_cuti'] >= sisacuti['hari']) {
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
				}else{
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}
			}else if (tgl_izin['jenis'] == 'C' && sisacuti['potong_cuti'] != '1') {
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
			}
		})
		$('#btn_sisa_cuti').click(function() {
			$('#sisa_cuti').modal('show');
		})
	});
	function modal_need(id) {
		var data={id_izin_cuti:id};
		$('#m_need').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);
		$('#m_need #data_id_need').val(callback['id']);
		$('#m_need #data_idk_need').val(callback['id_karyawan']);
		$('#m_need #data_jenis_need').val(callback['nama_jenis_ic']);
		$('#m_need .header_data').html(callback['nama']);
	}
	function modal_yes(id) {
		var data={id_izin_cuti:id};
		$('#m_yes').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);
		$('#m_yes #data_id_yes').val(callback['id']);
		$('#m_yes #data_idk_yes').val(callback['id_karyawan']);
		$('#m_yes #data_jenis_yes').val(callback['nama_jenis_ic']);
		$('#m_yes .header_data').html(callback['nama']);
	}
	function modal_no(id) {
		var data={id_izin_cuti:id};
		$('#m_no').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);
		$('#m_no #data_id_no').val(callback['id']);
		$('#m_no #data_idk_no').val(callback['id_karyawan']);
		$('#m_no #data_jenis_no').val(callback['nama_jenis_ic']);
		$('#m_no .header_data').html(callback['nama']);
	}
  	function do_validasi(data,val,form){
		if(data==2){
			var id = $('#data_id_need').val();
			var idk = $('#data_idk_need').val();
			var jenis = $('#data_jenis_need').val();
		}else if(data==1){
			var id = $('#data_id_yes').val();
			var idk = $('#data_idk_yes').val();
			var jenis = $('#data_jenis_yes').val();
		}else if(data==0){
			var id = $('#data_id_no').val();
			var idk = $('#data_idk_no').val();
			var jenis = $('#data_jenis_no').val();
		}
		var datax={id_izin_cuti:id,id_k:idk,validasi_db:data,validasi:val,jenis_db:jenis};
		submitAjax("<?php echo base_url('presensi/validasi_izin')?>",form,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
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
	function refreshCode() {
		kode_generator("<?php echo base_url('presensi/izin_cuti/kode');?>",'data_kode_add');
	}
	function resetselectAdd() {
		$('#data_jenis_cuti_add').val('').trigger('change');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
	}
	function view_modal(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);  
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
		// var valid=callback['e_validasi'];
		// if (valid==2){
		// 	$('#btn_edit_view').show();
		// }else{
		// 	$('#btn_edit_view').hide();
		// }
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
		getSelect2("<?php echo base_url('presensi/izin_cuti/izincuti')?>",'data_jenis_edit');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit,#data_menyetujui2_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data); 
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
		$('#data_jenis_edit').val(callback['e_jenis_cuti']).trigger('change');
		$('#data_mengetahui_edit').val(callback['emengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['emenyetujui']).trigger('change');
		$('#data_menyetujui2_edit').val(callback['emenyetujui2']).trigger('change');
		// addValueEditor('data_alasan_edit',callback['e_alasan']);
		// addValueEditor('data_keterangan_edit',callback['eketerangan']);
		$("#data_tgl_cuti_edit").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
		$("#data_tgl_cuti_edit").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
		$("#data_tgl_cuti_old").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
		$("#data_tgl_cuti_old").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
		var skd=callback['e_skd'];
		if (skd==1){
			$('#skd_no_edit').hide();
			$('#skd_yes_edit').show();
		}else{
			$('#skd_yes_edit').hide();
			$('#skd_no_edit').show();
		}
	}
	function delete_modal(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_izin_cuti/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_izin_cuti:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/edit_izin_cuti')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/add_izin_cuti')?>",null,'form_add',null,null);
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
	// 	window.location.href = "<?php //echo base_url('cetak_word/cetak_izin/')?>"+id;
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
			$.redirect("<?php echo base_url('pages/cetak_izin'); ?>", { id_izin : id, }, "POST", "_blank");
		}
	} 
</script> 