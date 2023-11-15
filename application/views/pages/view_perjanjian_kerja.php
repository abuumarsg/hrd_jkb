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
			<i class="fa fas fa-file-contract"></i> Perjanjian Kerja
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_perjanjian_kerja');?>"><i class="fa fas fa-file-contract"></i> Data Perjanjian Kerja</a></li>
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
									echo '<label class="pull-right label label-warning" id="info_status_karyawan">'.$profile['nama_status'].'</label>';
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
							<li class="list-group-item status_emp">
								<p style="color: red;" class="text-center">Karyawan ini Sudah Tidak Aktif</p>
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
								<button type="button" class="btn btn-danger pull-right nonaktifkan" style="display: none;" href="javascript:void(0)" onclick="nonaktifkan()"><i class="fas fa-user-times"></i> Nonaktifkan Karyawan</button>
								<button type="button" class="btn btn-default pull-right aktif_or_not" style="display: none;" href="javascript:void(0)" onclick="resign()"><i class="fas fa-user-times"></i> Resign</button>
								<?php if(substr($profile['nama_status'],0,3)=='KON'){
									echo '<button type="button" class="btn btn-danger pull-right aktif_or_not" style="display: none;" href="javascript:void(0)" onclick="putus_kontrak()"><i class="fas fa-user-times"></i> Putus Kontrak</button>';
								} ?>
								<button type="button" class=" pull-right btn btn-success status_emp" style="display: none;" href="javascript:void(0)" onclick="aktifkan()"><i class="fas fa-user-check"></i> Aktifkan Kembali</button>
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
						<h3 class="box-title"><i class="fa fas fa-file-contract"></i> Data Seluruh Perjanjian Kerja  <small><?php echo $profile['nama'];?></small></h3>
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
									echo form_open('rekap/export_perjanjian_kerja');
									if (in_array($access['l_ac']['add'], $access['access'])) {
										echo '<button class="btn btn-success aktif_or_not" type="button" id="btn_tambah" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data</button>';
									}
									if (in_array($access['l_ac']['rkp'], $access['access'])) {
										echo '<input type="hidden" name="nik" value="'.$this->codegenerator->decryptChar($this->uri->segment(3)).'">';
										echo '<button type="submit" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>'; }
									echo form_close(); ?>
								</div>
								<div class="pull-right" style="font-size: 8pt;">
									<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
									<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
								</div>
							</div>
						</div>
						<?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
						<div class="collapse" id="add">
							<div class="col-md-12 aktif_or_not">
								<form id="form_add">
									<div class="col-md-1"></div>
									<div class="col-md-10">
										<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
										<div class="row">
											<div class="col-md-6">
												<input type="hidden" name="nik" value="<?php echo $profile['nik']?>">
												<input type="hidden" name="no_sk_lama" id="no_sk_lama_add" value="">
												<input type="hidden" name="tgl_sk_lama" id="tgl_sk_lama_add" value="">
												<input type="hidden" name="tgl_berlaku_lama" id="tgl_berlaku_lama_add" value="">
												<input type="hidden" name="tgl_berlaku_sampai_lama" id="tgl_berlaku_sampai_lama_add" value="">
												<input type="hidden" name="status_lama" id="status_lama_add" value="">
												<input type="hidden" name="val" value="non">
												<input type="hidden" name="nonaktif" value="tidak">
												<div class="form-group clearfix">
													<label>Nomor SK</label>
													<div class="row">
														<div class="col-sm-11">
															<input type="text" placeholder="Masukkan Nomor Sk" name="no_sk_baru" id="data_kode_add" class="form-control" value="<?php echo $this->codegenerator->kodePerjanjianKerja(); ?>" required="required">
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
														<input type="text" name="tgl_sk_baru" class="form-control pull-right date-picker" placeholder="Tanggal SK" readonly="readonly" required="required">
													</div>
												</div>
												<div class="form-group clearfix">
													<label>Perjanjian Kerja Baru</label>
													<select class="form-control select2 data_perjanjian_baru" name="perjanjian_baru" id="data_perjanjian_add" onchange="cekKodePerjanjian(this.value)" required="required" style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Status Karyawan Baru</label>
													<select class="form-control select2 status_karyawan" name="status_karyawan" id="status_karyawan" required="required" style="width: 100%;"></select>
                                    				<option></option>
												</div>
												<div class="form-group clearfix">
													<label>Tanggal Berlaku</label>
													<div class="has-feedback">
														<span class="fa fa-calendar form-control-feedback"></span>
														<input type="text" name="tgl_berlaku_baru" id="tgl_berlaku_baru_add" class="form-control pull-right  date-picker" placeholder="Tanggal Berlaku" readonly="readonly" required="required">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group clearfix">
													<label>Berlaku Sampai</label>
													<div class="has-feedback">
														<span class="fa fa-calendar form-control-feedback"></span>
														<input type="text" name="tgl_berlaku_sampai_baru" id="berlaku_sampai_baru_add" class="form-control pull-right date-picker" placeholder="Berlaku Sampai" required="required">
													</div>
												</div>
												<div class="form-group clearfix">
													<label>Mengetahui</label>
													<select class="form-control select2" name="mengetahui" id="data_mengetahui_add" required="required" style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Menyetujui</label>
													<select class="form-control select2" name="menyetujui" id="data_menyetujui_add" required="required" style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Keterangan</label>
													<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
												</div>
												<div class="form-group" id="div_nonaktif" style="display: none;">
													<input type="checkbox" name="nonaktif" value="ya"> Nonaktifkan Karyawan
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
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Nama</th>
									<th>NO SK</th>
									<th>Tanggal SK</th>
									<th>Perjanjian</th>
									<th>Tanggal Berlaku</th>
									<th>Berlaku Sampai</th>
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
<div class="modal fade" id="putus_kontrak" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Putus Kontrak</h4>
			</div>
			<form id="form_putus_kontrak" action="#">
				<div class="modal-body">
					<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
					<div class="row">
						<input type="hidden" name="nik" value="<?php echo $profile['nik']?>">
						<div class="form-group clearfix">
							<label class="col-md-3">Nomor SK</label>
							<div class="col-md-8">
								<input type="text" placeholder="Masukkan Nomor Sk" name="no_sk_baru" id="no_sk_baru_pts" class="form-control" value="" required="required">
							</div>
							<div class="col-sm-1">
								<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
							</div>
						</div>
						<input type="hidden" name="no_sk" id="no_sk_pts" value="">
						<div class="form-group clearfix">
							<label class="col-md-3">Perjanjian Kerja</label>
							<div class="col-md-9">
								<select class="form-control select2" id="pts_kntrk" name="perjanjian_baru" required="required" style="width: 100%;">
									<option value="">Pilih Data</option>
									<option value="PTSP">PUTUS KONTRAK</option>
								</select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-3 control-label">Alasan</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="alasan" id="alasan_keluar_pts" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Tanggal SK</label>
							<div class="col-md-9">
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tgl_sk_baru" class="form-control pull-right date-picker" placeholder="Tanggal SK" readonly="readonly" required="required">
								</div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Tanggal Berlaku</label>
							<div class="col-md-9">
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tgl_berlaku_baru" class="form-control pull-right date-picker" placeholder="Tanggal Berlaku" readonly="readonly" required="required">
								</div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Mengetahui</label>
							<div class="col-md-9">
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_pts" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Menyetujui</label>
							<div class="col-md-9">
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_pts" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Keterangan</label>
							<div class="col-md-9">
								<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3"></label>
							<div class="col-md-9">
								<input type="checkbox" name="nonaktif" value="ya"> Nonaktifkan Karyawan
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="resign" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Resign</h4>
			</div>
			<form id="form_resign" action="#">
				<div class="modal-body">
					<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
					<div class="row">
						<input type="hidden" name="nik" value="<?php echo $profile['nik']?>">
						<div class="form-group clearfix">
							<label class="col-md-3">Nomor SK</label>
							<div class="col-md-8">
								<input type="text" placeholder="Masukkan Nomor Sk" name="no_sk_baru" id="no_sk_baru_rsg" class="form-control" value="" required="required">
							</div>
							<div class="col-sm-1">
								<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
							</div>
						</div>
						<input type="hidden" name="no_sk" id="no_sk_rsg" value="">
						<div class="form-group clearfix">
							<label class="col-md-3">Perjanjian Kerja</label>
							<div class="col-md-9">
								<select class="form-control select2" id="rsg" name="perjanjian_baru" required="required" style="width: 100%;">
									<option value="">Pilih Data</option>
									<option value="RSGN">RESIGN</option>
								</select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-3 control-label">Alasan</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="alasan" id="alasan_keluar_rsg" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Tanggal SK</label>
							<div class="col-md-9">
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tgl_sk_baru" class="form-control pull-right date-picker" placeholder="Tanggal SK" readonly="readonly" required="required">
								</div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Tanggal Berlaku</label>
							<div class="col-md-9">
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tgl_berlaku_baru" class="form-control pull-right  date-picker" placeholder="Tanggal Berlaku" readonly="readonly" required="required">
								</div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Mengetahui</label>
							<div class="col-md-9">
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_rsg" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Menyetujui</label>
							<div class="col-md-9">
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_rsg" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Keterangan</label>
							<div class="col-md-9">
								<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3"></label>
							<div class="col-md-9">
								<input type="checkbox" name="nonaktif" value="ya"> Nonaktifkan Karyawan
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="aktifkan" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Aktifkan Karyawan Non-aktif</h4>
			</div>
			<form id="form_aktifkan" action="#">
				<div class="modal-body">
					<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
					<div class="row">
						<input type="hidden" name="nik" value="<?php echo $profile['nik']?>">
						<input type="hidden" name="val" value="aktif">
						<div class="form-group clearfix">
							<label class="col-md-3">Nomor SK</label>
							<div class="col-md-8">
								<input type="text" placeholder="Masukkan Nomor Sk" name="no_sk_baru" id="no_sk_baru_atf" class="form-control" value="" required="required">
							</div>
							<div class="col-sm-1">
								<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Perjanjian Kerja</label>
							<div class="col-md-9">
								<select class="form-control select2 data_perjanjian_baru" name="perjanjian_baru" id="data_perjanjian_atf" onchange="cekKodePerjanjianAktif(this.value)" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Status Karyawan Baru</label>
							<div class="col-md-9">
								<select class="form-control select2 status_karyawan" name="status_karyawan" id="status_karyawan_atf" required="required" style="width: 100%;"></select>
								<option></option>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Tanggal SK</label>
							<div class="col-md-9">
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tgl_sk_baru" class="form-control pull-right date-picker" placeholder="Tanggal SK" readonly="readonly" required="required">
								</div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Tanggal Berlaku</label>
							<div class="col-md-9">
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tgl_berlaku_baru" class="form-control pull-right date-picker" id="tgl_berlaku_baru_atf" placeholder="Tanggal Berlaku" readonly="readonly" required="required">
								</div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Berlaku Sampai</label>
							<div class="col-md-9">
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tgl_berlaku_sampai_baru" class="form-control pull-right date-picker" id="berlaku_sampai_baru_atf" placeholder="Tanggal Berlaku" readonly="readonly" required="required">
								</div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Mengetahui</label>
							<div class="col-md-9">
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_atf" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Menyetujui</label>
							<div class="col-md-9">
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_atf" required="required" style="width: 100%;"></select>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-md-3">Keterangan</label>
							<div class="col-md-9">
								<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="nonaktifkan" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Nonaktifkan Karyawan</h4>
			</div>
			<form id="form_nonaktifkan" action="#">
				<div class="modal-body">
					<div class="row">
						<input type="hidden" name="nik" value="<?php echo $profile['nik']?>">
						<input type="hidden" name="nonaktif" value="ya">
						<input type="hidden" name="perjanjian_baru" value="PTSP">
						<input type="hidden" name="validasi_nonaktif" value="ya">
						<div class="col-md-1"></div>
						<div class="col-md-10"><p>Apakah Anda Yakin Akan Menonaktifkan Karyawan ini ??</p></div>
						
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger"><i class="fas fa-user-times"></i> Nonaktifkan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
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
							<label class="col-md-6 control-label">Status Karyawan</label>
							<div class="col-md-6" id="data_status_karyawan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Perjanjian Baru</label>
							<div class="col-md-6" id="data_perjanjian3_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Gaji</label>
							<div class="col-md-6" id="data_gaji_view"></div>
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
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-success">
							<div class="panel-heading bg-green"><h4>Data Perjanjian Kerja</h4></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Nomor SK Baru</label>
									<div class="col-md-6" id="data_nosk2_view"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Perjanjian Baru</label>
									<div class="col-md-6" id="data_perjanjian2_view"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Tanggal SK Baru</label>
									<div class="col-md-6" id="data_tglsk2_view"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Tanggal Berlaku Baru</label>
									<div class="col-md-6" id="data_tglberlaku2_view"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Berlaku Sampai Tanggal</label>
									<div class="col-md-6" id="data_berlakusampai2_view"></div>
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
<?php if (in_array($access['l_ac']['edt'], $access['access'])) { ?>
	<div id="edit" class="modal fade" role="dialog">
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
									<div class="form-group clearfix">
										<label>NIK</label>
										<input type="text" placeholder="Masukkan NIK" id="data_nik_edit" name="nik" value="" class="form-control" readonly="readonly">
									</div>
									<div class="form-group clearfix">
										<label>Nama</label>
										<input type="text" placeholder="Masukkan Nama" id="data_nama_edit" name="nama" value="" class="form-control" readonly="readonly">
									</div>
									<input type="hidden" name="date_validasi" id="data_date_validasi" value="">
									<div class="form-group clearfix">
										<label>NO SK</label>
										<input type="text" placeholder="Masukkan NO SK" id="data_nosk2_edit" name="no_sk_baru" value="" class="form-control" required="required">
									</div>
									<div class="form-group clearfix">
										<label>Perjanjian Baru</label>
										<select class="form-control select2" name="perjanjian_baru" id="data_perjanjian2_edit" onchange="cekKodePerjanjianEdit(this.value)" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Status Karyawan</label>
										<select class="form-control select2" name="status_karyawan" id="status_karyawan_edit" required="required" style="width: 100%;"></select>
                                    	<option></option>
									</div>
									<div class="form-group clearfix">
										<label>Tanggal SK</label>
										<div class="has-feedback">
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" id="data_tglsk2_edit" value="" name="tgl_sk_baru" class="form-control pull-right date-picker" placeholder="Tanggal Berlaku" readonly="readonly" required="required">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group clearfix">
										<label>Tanggal Berlaku</label>
										<div class="has-feedback">
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" id="data_tglberlaku2_edit" value="" name="tgl_berlaku_baru" class="form-control pull-right date-picker" placeholder="Tanggal Berlaku" readonly="readonly" required="required">
										</div>
									</div>
									<div class="form-group clearfix">
										<label>Berlaku Sampai</label>
										<div class="has-feedback">
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" id="data_berlakusampai2_edit" value="" name="berlaku_sampai_baru" class="form-control pull-right date-picker" placeholder="Tanggal Berlaku" required="required">
										</div>
									</div>
									<div class="form-group clearfix">
										<label>Besaran Gaji</label>
										<input type="text" name="gaji" id="gaji_edit" class="form-control input-money" placeholder="Gaji Karyawan">
									</div>
									<div class="form-group clearfix">
										<label>Mengetahui</label>
										<select class="form-control select2" name="mengetahui" id="data_mengetahui2_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Menyetujui</label>
										<select class="form-control select2" name="menyetujui" id="data_menyetujui2_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Keterangan</label>
										<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan"></textarea>
									</div>
									<div class="form-group clearfix" id="nonaktif_edit" style="display: none;">
										<input type="checkbox" name="nonaktif" value="ya"> Nonaktifkan Karyawan
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
<div id="modal_kompensasi" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Surat Kompensasi</h4>
			</div>
			<div class="modal-body">
				<form id="form_kompensasi">
					<input type="hidden" name="komp_id" id="komp_id" value="">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Menyetujui 1</label>
								<select class="form-control select2" name="komp_menyetujui_1" id="komp_menyetujui_1" style="width: 100%;"></select>
							</div>
							<div class="form-group">
								<label>Menyetujui 2</label>
								<select class="form-control select2" name="komp_menyetujui_2" id="komp_menyetujui_2" style="width: 100%;"></select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Mengetahui</label>
								<select class="form-control select2" name="komp_mengetahui" id="komp_mengetahui" style="width: 100%;"></select>
							</div>
							<div class="form-group">
								<label>Di Buat Oleh</label>
								<select class="form-control select2" name="komp_dibuat" id="komp_dibuat" style="width: 100%;"></select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Status Perjanjian Baru</label>
								<select class="form-control select2" name="perjanjian_baru" id="kompen_perjanjian_baru" required="required" style="width: 100%;">
									<option value="">Pilih Data</option>
									<option value="DIPERPANJANG">DIPERPANJANG</option>
									<option value="TIDAK DIPERPANJANG">TIDAK DIPERPANJANG</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Pilih Bulan <i><b>(Jika Diperpanjang)</b></i> </label>
								<select class="form-control select2" name="lama_perjanjian_baru" style="width: 100%;">
									<option value="">Pilih Data</option>
									<?php
										for ($i=1; $i < 13; $i++) { 
											echo '<option value="'.$i.'">'.$i.' Bulan</option>';
										}
									?>
								</select>
							</div>
						</div>
					</div>
				</form>
				<!-- <hr> -->
				<div class="col-md-6" style="text-align: center;">
					<!-- <button type="button" class="btn btn-primary" onclick="do_rekap_bagian('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button> -->
				</div>
				<div class="col-md-6" style="text-align: right;">
					<!-- <button type="button" style="text-align: right;" class="btn btn-danger" onclick="print_kompensasi('pdf')"><i class="fas fa-file-pdf fa-fw"></i>Cetak PDF</button> -->
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" style="text-align: right;" class="btn btn-danger" onclick="print_kompensasi('pdf')"><i class="fas fa-file-pdf fa-fw"></i>Cetak PDF</button>
			</div>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_perjanjian_kerja";
	var column="id_p_kerja";
	$(document).ready(function(){
		refreshCode();call_status();
		submitForm('form_add');
		submitForm('form_edit');
		submitForm('form_putus_kontrak');
		submitForm('form_resign');
		submitForm('form_aktifkan');
		submitForm('form_nonaktifkan');
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('employee/view_perjanjian_kerja/view_all/'.$this->uri->segment(3))?>",
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
				width: '15%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
		select_data('data_perjanjian_add',url_select,'master_surat_perjanjian','kode_perjanjian','nama');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add');
		$('#data_perjanjian2_edit, #data_tglberlaku2_edit').change(function(){
			var ij = $('#data_perjanjian2_edit').val();
			var ik = $('#data_tglberlaku2_edit').val();
			var nik = $('#data_nik_edit').val();
			$.ajax({
				method : "POST",
				url   : '<?php echo base_url('employee/tanggal_janji')?>',
				async : false,
				dataType : 'json',
				data: {	tgl_berlaku: ik,
					status: ij,
					nik:nik,
				},
				success : function(data){
					$('#data_berlakusampai2_edit').val(data);
				}
			});
		});
		$('#data_perjanjian_add, #tgl_berlaku_baru_add').change(function(){
			var ij = $('#data_perjanjian_add').val();
			var ik = $('#tgl_berlaku_baru_add').val();
			var nik =$('input[name="nik"]').val();
			$.ajax({
				method : "POST",
				url   : '<?php echo base_url('employee/tanggal_janji')?>',
				async : false,
				dataType : 'json',
				data: {	tgl_berlaku: ik,
					status: ij,
					nik:nik
				},
				success : function(data){
					$('#berlaku_sampai_baru_add').val(data);
				}
			});
		});
	});
	$('#btn_tambah').click(function(){ 
		var id = $('input[name="nik"]').val();
		var data={nik:id};
		var callback=getAjaxData("<?php echo base_url('employee/pilih_k_perjanjian_add')?>",data);
		$('#no_sk_lama_add').val(callback['no_sk_lama']);
		$('#tgl_sk_lama_add').val(callback['tgl_sk_lama']);
		$('#tgl_berlaku_lama_add').val(callback['tgl_berlaku_lama']);
		$('#tgl_berlaku_sampai_lama_add').val(callback['berlaku_sampai_lama']);
		$('#status_lama_add').val(callback['status_lama']);
	})
	function refreshCode() {
		kode_generator("<?php echo base_url('employee/perjanjian_kerja/kode');?>",'data_kode_add,#no_sk_baru_pts,#no_sk_baru_rsg,#no_sk_baru_atf');
		kode_generator("<?php echo base_url('employee/karyawan_tidak_aktif/kode');?>",'no_sk_pts,#no_sk_rsg');
	}
	function resetselectAdd() {
		$('#status_karyawan').val('').trigger('change');
		$('#data_perjanjian_add').val('').trigger('change');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
	}
	function putus_kontrak() {
		refreshCode();  
		setTimeout(function () {
			$('#putus_kontrak').modal('show');
		},600);
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_pts,#data_menyetujui_pts');
   		select_data('alasan_keluar_pts',url_select,'master_alasan_keluar','kode_alasan_keluar','nama');
	}
	function resign() {
		refreshCode();  
		setTimeout(function () {
			$('#resign').modal('show');
		},600);
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_rsg,#data_menyetujui_rsg');
   		select_data('alasan_keluar_rsg',url_select,'master_alasan_keluar','kode_alasan_keluar','nama');
	}
	function aktifkan() {
		refreshCode();
		setTimeout(function () {
			$('#aktifkan').modal('show');
		},600);
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_atf,#data_menyetujui_atf');
		select_data('data_perjanjian_atf',url_select,'master_surat_perjanjian','kode_perjanjian','nama');
		unsetoption('data_perjanjian_atf',['PTSP','RSGN']);
		$('#data_perjanjian_atf, #tgl_berlaku_baru_atf').change(function(){
			var ij = $('#data_perjanjian_atf').val();
			var ik = $('#tgl_berlaku_baru_atf').val();
			var nik =$('input[name="nik"]').val();
			$.ajax({
				method : "POST",
				url   : '<?php echo base_url('employee/tanggal_janji')?>',
				async : false,
				dataType : 'json',
				data: {	tgl_berlaku: ik,
					status: ij,
					nik:nik
				},
				success : function(data){
					$('#berlaku_sampai_baru_atf').val(data);
				}
			});
		})
	}
	function nonaktifkan() {
		refreshCode();
		setTimeout(function () {
			$('#nonaktifkan').modal('show');
		},600);
	}
	function view_modal(id) {
		var data={id_p_kerja:id};
		var callback=getAjaxData("<?php echo base_url('employee/view_perjanjian_kerja/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_status_karyawan_view').html(callback['status_karyawan']);
		$('#data_perjanjian1_view').html(callback['status_lama']);
		$('#data_nosk1_view').html(callback['no_sk_lama']);
		$('#data_tglsk1_view').html(callback['tgl_sk_lama']);
		$('#data_tglberlaku1_view').html(callback['tgl_berlaku_lama']);
		$('#data_berlakusampai1_view').html(callback['berlaku_sampai_lama']);
		$('#data_perjanjian2_view').html(callback['status_baru']);
		$('#data_perjanjian3_view').html(callback['status_baru']);
		$('#data_nosk2_view').html(callback['no_sk_baru']);
		$('#data_tglsk2_view').html(callback['tgl_sk_baru']);
		$('#data_tglberlaku2_view').html(callback['tgl_berlaku_baru']);
		$('#data_berlakusampai2_view').html(callback['berlaku_sampai_baru']);
		$('#data_mengetahui_view').html(callback['mengetahuiv']);
		$('#data_menyetujui_view').html(callback['menyetujuiv']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_gaji_view').html(callback['gaji']);
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
		select_data('data_perjanjian2_edit',url_select,'master_surat_perjanjian','kode_perjanjian','nama');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui2_edit,#data_menyetujui2_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_p_kerja:id};
		var callback=getAjaxData("<?php echo base_url('employee/view_perjanjian_kerja/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_nosk1_edit').val(callback['no_sk_lamav']);
		$('#data_tglsk1_edit').val(callback['tgl_sk_lamav']);
		$('#data_tglberlaku1_edit').val(callback['tgl_berlaku_lamav']);
		$('#data_berlakusampai1_edit').val(callback['berlaku_sampai_lamav']);
		$('#data_perjanjian1_edit').val(callback['perjanjian_lama']);
		$('#data_nik_edit').val(callback['nik']);
		$('#data_nama_edit').val(callback['nama']);
		$('#data_nosk2_edit').val(callback['no_sk_baru']);
		$('#data_tglsk2_edit').val(callback['tgl_sk_baruv']);
		$('#data_tglberlaku2_edit').val(callback['tgl_berlaku_baruv']);
		$('#data_berlakusampai2_edit').val(callback['berlaku_sampai_baruv']);
		$('#status_karyawan_edit').val(callback['status_karyawan_edit']).trigger('change');
		$('#data_perjanjian2_edit').val(callback['perjanjian_baru']).trigger('change');
		$('#data_mengetahui2_edit').val(callback['mengetahui']).trigger('change');
		$('#data_menyetujui2_edit').val(callback['menyetujui']).trigger('change');
		$('#data_keterangan_edit').val(callback['keteranganv']);
		$('#gaji_edit').val(callback['gaji']);
		$('#data_date_validasi').val(callback['date_validasi']);
	// addValueEditor('data_keterangan_edit',callback['keteranganv']);
	}
	function delete_modal(id) {
		var data={id_p_kerja:id};
		var callback=getAjaxData("<?php echo base_url('employee/view_perjanjian_kerja/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_p_kerja:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
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
				}else if(form=='form_putus_kontrak'){
					do_add_kontrak()
				}else if(form=='form_resign'){
					do_add_resign()
				}else if(form=='form_aktifkan'){
					do_add_aktifkan()
				}else if(form=='form_nonaktifkan'){
					do_add_nonaktifkan()
				}else{
					do_edit()
				}
			}
		})
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/edit_perjanjian_kerja')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();call_status();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_perjanjian_kerja')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			refreshCode();resetselectAdd();call_status();
		}else{
			notValidParamx();
		} 
	}
	function do_add_kontrak(){
		if($("#form_putus_kontrak")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_perjanjian_kerja')?>",'putus_kontrak','form_putus_kontrak',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_putus_kontrak')[0].reset();
			refreshCode();resetselectAdd();call_status();
		}else{
			notValidParamx();
		} 
	}
	function do_add_resign(){
		if($("#form_resign")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_perjanjian_kerja')?>",'resign','form_resign',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_resign')[0].reset();
			refreshCode();resetselectAdd();call_status();
		}else{
			notValidParamx();
		} 
	}
	function do_add_aktifkan(){
		if($("#form_aktifkan")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_perjanjian_kerja')?>",'aktifkan','form_aktifkan',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_aktifkan')[0].reset();
			refreshCode();resetselectAdd();call_status();
		}else{
			notValidParamx();
		} 
	}
	function do_add_nonaktifkan(){
		if($("#form_nonaktifkan")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_perjanjian_kerja')?>",'nonaktifkan','form_nonaktifkan',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_nonaktifkan')[0].reset();
			refreshCode();resetselectAdd();call_status();
		}else{
			notValidParamx();
		} 
	}
	function do_print(id) {
		var nik="<?php echo $this->uri->segment(3)?>";
		window.location.href = "<?php echo base_url('cetak_word/cetak_perjanjian/')?>"+id+'/'+nik;
	}
	function cekKodePerjanjian(kode_perjanjian) {
		var status = $('#data_perjanjian_add').val();
		if(status=='PTSP'){
			$('#div_nonaktif').show();
		}else if(status=='RSGN'){
			$('#div_nonaktif').show();
		}else{
			$('#div_nonaktif').hide();
		}
		var data={kode:status};
		var callback=getAjaxData("<?php echo base_url('employee/cek_kode_perjanjian')?>",data);
		$('#status_karyawan').html(callback['status_karyawan']);
	}
	function cekKodePerjanjianEdit(kode_perjanjian) {
		var status = $('#data_perjanjian2_edit').val();
		if(status=='PTSP'){
			$('#nonaktif_edit').show();
		}else if(status=='RSGN'){
			$('#nonaktif_edit').show();
		}else{
			$('#nonaktif_edit').hide();
		}
		var data={kode:status};
		var callback=getAjaxData("<?php echo base_url('employee/cek_kode_perjanjian')?>",data);
		$('#status_karyawan_edit').html(callback['status_karyawan']);
	}
	function cekKodePerjanjianAktif(kode_perjanjian) {
		var status = $('#data_perjanjian_atf').val();
		var data={kode:status};
		var callback=getAjaxData("<?php echo base_url('employee/cek_kode_perjanjian')?>",data);
		$('#status_karyawan_atf').html(callback['status_karyawan']);
	}
	function call_status(id){
		var id = $('input[name="nik"]').val();
		var data={nik:id};
		var callback=getAjaxData("<?php echo base_url('employee/view_perjanjian_kerja/call_status')?>",data);
		$('#info_status_karyawan').html(callback['status_karyawan']);
		var status = callback['aktif_or_not'];
		if(status==1){
			$('.status_emp').hide();
			$('.aktif_or_not').show();
			$('.nonaktifkan').hide();
		}else if(status==2){
			$('.status_emp').hide();
			$('.aktif_or_not').hide();
			$('.nonaktifkan').show();
		}else{
			$('.status_emp').show();
			$('.aktif_or_not').hide();
			$('.nonaktifkan').hide();
		}
	}
	function do_print_konpensasi(id){
		$('#modal_kompensasi').modal('show');
		$('#komp_id').val(id);
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee') ?>", 'komp_menyetujui_1, #komp_menyetujui_2, #komp_mengetahui, #komp_dibuat');
	}
	function print_kompensasi(id){
		var menyetujui_1 = $('#komp_menyetujui_1').val();
		var menyetujui_2 = $('#komp_menyetujui_2').val();
		var mengetahui = $('#komp_mengetahui').val();
		var dibuat = $('#komp_dibuat').val();
		var status = $('#kompen_perjanjian_baru').val();
		$.redirect("<?php echo base_url('pages/cetak_kompensasi'); ?>", { form: $('#form_kompensasi').serialize() }, "POST", "_blank");
	}
</script> 