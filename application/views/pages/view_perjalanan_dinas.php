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
	.data_detail{
		display: none;
		border-style: solid;
		border-width: 1px;
		border-radius: 3px;
		padding: 8px;
		border-color: #7F7F7F;
		overflow: auto;
		max-height: 200px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-car"></i> Perjalanan Dinas
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_perjalanan_dinas');?>"><i class="fas fa-car"></i> Data Perjalanan Dinas</a></li>
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
						<h3 class="box-title"><i class="fas fa-car"></i> Data Seluruh Perjalanan Dinas  <small><?php echo $profile['nama'];?></small></h3>
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
									echo form_open('rekap/data_perjalanan_dinas');
									if (in_array($access['l_ac']['add'], $access['access'])) {
										// echo '<button class="btn btn-success" type="button" id="btn_tambah" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data</button>';
									}
									if (in_array($access['l_ac']['rkp'], $access['access'])) {
										echo '<input type="hidden" name="nik" value="'.$this->codegenerator->decryptChar($this->uri->segment(3)).'">';
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
						<?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
						<div class="collapse" id="add">
							<div class="col-md-12">
								<form id="form_add">
									<div class="col-md-1"></div>
									<div class="col-md-10">
										<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
										<div class="row">
											<div class="col-md-6">
												<input type="hidden" name="id_karyawan[]" value="<?php echo $profile['id_karyawan']?>">
												<div class="form-group clearfix">
													<label>Nomor SK</label>
													<div class="row">
														<div class="col-sm-11">
															<input type="text" placeholder="Masukkan Nomor Sk" name="no_sk" id="no_sk_add" class="form-control" value="" required="required">
														</div>
														<div class="col-sm-1">
															<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
														</div>
													</div>
												</div>
												<div class="form-group clearfix">
													<label>Tanggal Berangkat - Tanggal Sampai</label>
													<input type="text" name="tanggal" class="form-control pull-right dateRangeNoSecond" placeholder="Tanggal" readonly="readonly" required="required">
												</div>
												<div class="form-group clearfix">
													<label>Tanggal Pulang</label>
													<div class="has-feedback">
														<span class="fa fa-calendar form-control-feedback"></span>
														<input type="text" name="tanggal_pulang" class="form-control pull-right datetimepicker" placeholder="Tanggal Pulang" readonly="readonly">
													</div>
												</div>
												<div class="form-group">
													<label>Pilih Plant Asal</label>
													<select class="form-control select2" name="plant_asal" id="data_plant_asal_add" style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Tujuan</label>
														<?php
														$tujuan[null] = 'Pilih Data';
														$sel1 = [null];
														$exsel1 = array('class'=>'form-control select2','placeholder'=>'Tujuan','id'=>'tujuan','required'=>'required','style'=>'width:100%','onchange'=>'tujuanPD(this.value)');
														echo form_dropdown('tujuan',$tujuan,$sel1,$exsel1);
														?>
												</div>
												<div class="form-group clearfix" id="tujuan_plant" style="display:none;">
													<label>Pilih Plant Tujuan</label>
													<select class="form-control select2" name="plant_tujuan" id="data_plant_tujuan_add" style="width: 100%;"></select>
												</div>
												<div id="tujuan_non_plant" style="display:none;">
													<div class="form-group clearfix">
														<label>Lokasi Tujuan</label>
														<input type="text" name="lokasi_tujuan" id="data_lokasi_tujuan_add" class="form-control pull-right" placeholder="Lokasi Tujuan">
													</div>
													<div class="form-group clearfix">
														<label>Estimasi Jarak</label>
														<input type="number" nim="0" name="jarak" id="data_jarak_add" class="form-control pull-right" placeholder="Estimasi Jarak">
													</div>
												</div>
												<div class="form-group clearfix">
													<label>Kendaraan</label>
													<select class="form-control select2" name="kendaraan" id="data_kendaraan_add" onchange="kendaraanPD(this.value)" style="width: 100%;"></select>
												</div>
												<input type="hidden" nim="0" name="jum_kendaraan" id="data_jum_ken_add" class="form-control pull-right" placeholder="Jumlah Kendaraan" value="1">
												<input type="hidden" name="nominal_penginapan" id="data_jum_ken_add" class="form-control pull-right" placeholder="Jumlah Kendaraan">
												<div class="form-group" id="nama_kendaraan_umum" style="display:none;">
													<label>Pilih Kendaraan Umum</label>
													<?php
													$kendaraan_umum[null] = 'Pilih Data';
													$sel = [null];
													$exsel = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'data_kendaraan_umum_add','required'=>'required','style'=>'width:100%');
													echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel,$exsel);
													?>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label style="vertical-align: middle;">Menginap</label><br>
														<span id="menginap_off" style="font-size: 20px;" onclick="menginapKlik();"><i class="far fa-square" aria-hidden="true"></i></span>
														<span id="menginap_on" style="display: none; font-size: 20px;" onclick="menginapKlik();"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
														<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist Jika Menginap)</b></span>
														<input type="hidden" name="menginap">
												</div>
												<div class="form-group" id="penginapan_add" style="display: none;">
													<label>Penginapan</label>
														<?php
														$penginapan[null] = 'Pilih Data';
														$sel2 = [null];
														$exsel2 = array('class'=>'form-control select2','placeholder'=>'penginapan','id'=>'data_penginapan_add','style'=>'width:100%');
														echo form_dropdown('penginapan',$penginapan,$sel2,$exsel2);
														?>
												</div>
												<!-- <div class="form-group clearfix">
													<label>Tunjangan</label>
													<select class="form-control select2" multiple="multiple" name="tunjangan[]" id="data_tunjangan_add" style="width: 100%;"></select>
												</div> -->
												<div class="form-group clearfix">
													<label>Tugas</label>
														<textarea name="tugas" class="form-control" required="required" placeholder="Tugas Tugas Perjalanan Dinas"></textarea>
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
													<label>Dibuat</label>
													<select class="form-control select2" name="dibuat" id="data_dibuat_add" required="required" style="width: 100%;"></select>
												</div>
												<div class="form-group clearfix">
													<label>Keterangan</label>
													<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
												</div>
											</div>
											<div class="col-md-12">
												<button type="button" class="btn btn-warning" id="btn_tunjangan" href="javascript:void(0)" onclick="view_tunjangan()"><i class="fa fa-eye"></i> Lihat Detail Tunjangan</button>
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
									<th>NO Perjalanan Dinas</th>
									<th>Tanggal</th>
									<th>Tujuan</th>
									<th>Kendaraan</th>
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
<div id="view_modal_tunjangan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_compare" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Tunjangan Perjalanan Dinas</h2>
			</div>
			<div class="modal-body">
				<div id="data_tabel_tunjangan"></div>
			</div>
			<div class="modal-footer">
			</div>
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
							<label class="col-md-6 control-label">Nomor Perjalanan Dinas</label>
							<div class="col-md-6" id="data_nosk_view"></div>
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
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Berangkat</label>
							<div class="col-md-6" id="data_berangkat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Sampai</label>
							<div class="col-md-6" id="data_sampai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Pulang</label>
							<div class="col-md-6" id="data_pulang_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Plant Asal</label>
							<div class="col-md-6" id="data_asal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tujuan</label>
							<div class="col-md-6" id="data_tujuan_view"></div>
						</div>
						<div class="form-group col-md-12" id="jarak_view">
							<label class="col-md-6 control-label">Jarak</label>
							<div class="col-md-6" id="data_jarak_view"></div>
						</div>	
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kendaraan</label>
							<div class="col-md-6" id="data_kedaraan_view"></div>
						</div>				
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menginap</label>
							<div class="col-md-6" id="data_menginap_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Penginapan</label>
							<div class="col-md-6" id="data_nama_penginapan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tugas</label>
							<div class="col-md-6" id="data_tugas_view"></div>
						</div>	
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>						
					</div>
				</div>
				<div id="tabel_tunjangan"></div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					// echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
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
									<input type="hidden" id="data_idk_edit" name="id_karyawan[]" value="">
									<div class="form-group clearfix">
										<label>NO SK</label>
										<input type="text" placeholder="Masukkan NO SK" id="data_nosk_edit" name="no_sk" value="" class="form-control" required="required">
									</div>
									<div class="form-group clearfix">
										<label>Tanggal Berangkat - Tanggal Kembali</label>
										<input type="text" name="tanggal" id="data_tanggal_edit" class="form-control pull-right dateRangeNoSecond" placeholder="Tanggal" readonly="readonly" required="required">
									</div>
									<div class="form-group clearfix">
										<label>Tanggal Pulang</label>
										<div class="has-feedback">
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" name="tanggal_pulang" id="data_tanggal_pulang_edit" class="form-control pull-right datetimepicker" placeholder="Tanggal Pulang" readonly="readonly">
										</div>
									</div>
									<div class="form-group clearfix">
										<label>NIK</label>
										<input type="text" placeholder="Masukkan NIK" id="data_nik_edit" name="nik" value="" class="form-control" required="required" disabled="disabled">
									</div>
									<div class="form-group clearfix">
										<label>Nama</label>
										<input type="text" placeholder="Masukkan Nama" id="data_nama_edit" name="nama" value="" class="form-control" disabled="disabled">
									</div>
									<div class="form-group clearfix">
										<label>Pilih Plant Asal</label>
										<select class="form-control select2" name="plant_asal" id="data_plant_asal_edit" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Tujuan</label>
										<?php
										$tujuan[null] = 'Pilih Data';
										$sel1 = [null];
										$exsel1 = array('class'=>'form-control select2','placeholder'=>'Tujuan','id'=>'tujuan_edit','required'=>'required','style'=>'width:100%','onchange'=>'tujuanPDEdit(this.value)');
										echo form_dropdown('tujuan',$tujuan,$sel1,$exsel1);
										?>
									</div>
									<div class="form-group clearfix" id="tujuan_plant_edit" style="display:none;">
										<label>Pilih Plant Tujuan</label>
										<select class="form-control select2" name="plant_tujuan" id="data_plant_tujuan_edit" style="width: 100%;"></select>
									</div>
									<div id="tujuan_non_plant_edit" style="display:none;">
										<div class="form-group clearfix">
											<label>Lokasi Tujuan</label>
											<input type="text" name="lokasi_tujuan" id="data_lokasi_tujuan_edit" class="form-control pull-right" placeholder="Lokasi Tujuan">
										</div>
										<div class="form-group clearfix">
											<label>Estimasi Jarak</label>
											<input type="number" nim="0" name="jarak" id="data_jarak_edit" class="form-control pull-right" placeholder="Estimasi Jarak">
										</div>
									</div>
									<div class="form-group clearfix">
										<label>Kendaraan</label>
										<select class="form-control select2" name="kendaraan" id="data_kendaraan_edit" onchange="kendaraanPDEdit(this.value)" style="width: 100%;"></select>
									</div>
									<input type="hidden" name="jum_kendaraan" id="data_jum_ken_edit" class="form-control pull-right" placeholder="Jumlah Kendaraan" value="1">
									<input type="hidden" name="nominal_penginapan" class="form-control pull-right" placeholder="Jumlah Kendaraan">
									<div class="form-group" id="nama_kendaraan_umum_edit" style="display:none;">
										<label>Nama Kendaraan Umum</label>
										<?php
										$kendaraan_umum[null] = 'Pilih Data';
										$sel2 = [null];
										$exsel2 = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'data_kendaraan_umum_edit','required'=>'required','style'=>'width:100%');
										echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel2,$exsel2);
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group clearfix">
										<label style="vertical-align: middle;">Menginap</label><br>
										<span id="menginap_off_edit" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
										<span id="menginap_on_edit" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
										<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist Jika Menginap)</b></span>
										<input type="hidden" name="menginap" id="menginap_edit">
									</div>
									<div class="form-group" id="penginapan_edit_div" style="display: none;">
										<label>Penginapan</label>
										<?php
										$penginapan[null] = 'Pilih Data';
										$sel2 = [null];
										$exsel2 = array('class'=>'form-control select2','placeholder'=>'penginapan','id'=>'data_penginapan_edit','style'=>'width:100%');
										echo form_dropdown('penginapan',$penginapan,$sel2,$exsel2);
										?>
									</div>
									<!-- <div class="form-group clearfix">
										<label>Tunjangan</label>
										<select class="form-control select2" multiple="multiple" name="tunjangan[]" id="data_tunjangan_edit" style="width: 100%;"></select>
									</div> -->
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
										<label>Tugas</label>
										<textarea class="form-control" name="tugas" id="data_tugas_edit" required="required" placeholder="Pelanggaran"></textarea>
									</div>
									<div class="form-group clearfix">
										<label>Keterangan (Sanksi)</label>
										<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan" required="required"></textarea>
									</div>
								</div>
								<div class="col-md-12">
									<button type="button" class="btn btn-warning" id="btn_tunjangan_edit" onclick="view_tunjangan_edit()"><i class="fa fa-eye"></i> Lihat Detail Tunjangan</button>
								</div><br>
								<div class="col-md-12">
									<div class="data_detail" id="tabel_tunjangan_edit"></div>
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
	var table="data_perjalanan_dinas";
	var column="id_pd";
	$(document).ready(function(){
		refreshCode();
		submitForm('form_add');
		submitForm('form_edit');
		$('#menginap_off').click(function(){
			$('#menginap_off').hide();
			$('#menginap_on').show();
			$('input[name="menginap"]').val('1');
		})
		$('#menginap_on').click(function(){
			$('#menginap_off').show();
			$('#menginap_on').hide();
			$('input[name="menginap"]').val('0');
		})
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/view_perjalanan_dinas/view_all/'.$this->uri->segment(3))?>",
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
			{   targets: 3,
				width: '16%',
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
		select_data('data_plant_asal_add',url_select,'master_loker','kode_loker','nama');
		select_data('data_plant_tujuan_add',url_select,'master_loker','kode_loker','nama');
		select_data('data_kendaraan_add',url_select,'master_pd_kendaraan','kode','nama');
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add,#data_dibuat_add');
		getSelect2("<?php echo base_url('presensi/data_perjalanan_dinas/pilihtunjangan')?>",'data_tunjangan_add');
		$('#jstreeMenuAdd, #jstreeAccessAdd, #jstreeMenuUserAdd, #jstreeAccessUserAdd,#jstreeMenuEdit,#jstreeAccessEdit,#jstreeMenuUserEdit,#jstreeAccessUserEdit').jstree({
			'plugins': ["wholerow", "checkbox"]
		});
		jstreeLoad(['jstreeMenuAdd','jstreeAccessAdd','jstreeMenuUserAdd','jstreeAccessUserAdd'],'add');
		jstreeLoad(['jstreeMenuEdit','jstreeAccessEdit','jstreeMenuUserEdit','jstreeAccessUserEdit'],'edit');
		table_data('table_data',"<?php echo base_url('master/master_user_group/view_all/')?>");
	});
	function menginapKlik(){
		var name = $('input[name="menginap"]').val();
		if(name == 0) {
			$('#penginapan_add').show();
			$('#data_penginapan_add').attr('required','required');
		}else {
			$('#penginapan_add').hide();
			$('#data_penginapan_add').removeAttr('required');
			$('#data_penginapan_add').val('').trigger('change');
		}
	}
	function view_tunjangan() {
		var data=$('#form_add').serialize();
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_tunjangan')?>",{search:data});  
		$('#view_modal_tunjangan').modal('show');
		$('#data_tabel_tunjangan').html(callback['tabel']);
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('presensi/data_perjalanan_dinas/kode');?>",'no_sk_add');
	}
	function resetselectAdd() {
		$('#tujuan').val('').trigger('change');
		$('#data_plant_asal_add').val('').trigger('change');
		$('#data_plant_tujuan_add').val('').trigger('change');
		$('#data_lokasi_tujuan_add').val('').trigger('change');
		$('#data_kendaraan_add').val('').trigger('change');
		$('#data_kendaraan_umum_add').val('').trigger('change');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
		$('#data_dibuat_add').val('').trigger('change');
	}
	function view_modal(id) {
		var data={id_pd:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_perjalanan_dinas/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nosk_view').html(callback['no_sk']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_berangkat_view').html(callback['tanggal_berangkat']);
		$('#data_sampai_view').html(callback['tanggal_sampai']);
		$('#data_pulang_view').html(callback['tanggal_pulang']);
		$('#data_asal_view').html(callback['asal']);
		$('#data_tujuan_view').html(callback['tujuan']);
		$('#data_kedaraan_view').html(callback['kendaraan']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
		$('#data_dibuat_view').html(callback['dibuat']);
		$('#data_tugas_view').html(callback['tugas']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_nama_penginapan_view').html(callback['nama_penginapan']);
		$('#data_jarak_view').html(callback['jarak']);
		var menginap = callback['menginap'];
		var mengipal_val=(menginap==1)?'<b class="text-success">Menginap</b>':'<b class="text-danger">Tidak Menginap</b>';
		$('#data_menginap_view').html(mengipal_val);
		$('#tabel_tunjangan').html(callback['tabel_tunjangan']);
		var status = callback['status'];
		var statusval =(status==1)?'<b class="text-success">Aktif</b>':'<b class="text-danger">Tidak Aktif</b>';
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function edit_modal() {
		refreshCode();
		$('#menginap_off_edit').click(function(){
			$('#menginap_off_edit').hide();
			$('#menginap_on_edit').show();
			$('#menginap_edit').val('1');
			$('#penginapan_edit_div').show();
			$('#data_penginapan_edit').attr('required','required');
		})
		$('#menginap_on_edit').click(function(){
			$('#menginap_off_edit').show();
			$('#menginap_on_edit').hide();
			$('#menginap_edit').val('0');
			$('#penginapan_edit_div').hide();
			$('#data_penginapan_edit').removeAttr('required');
			$('#data_penginapan_edit').val('').trigger('change');
		})
		select_data('data_plant_asal_edit',url_select,'master_loker','kode_loker','nama');
		select_data('data_plant_tujuan_edit',url_select,'master_loker','kode_loker','nama');
		select_data('data_kendaraan_edit',url_select,'master_pd_kendaraan','kode','nama');
		getSelect2("<?php echo base_url('presensi/data_perjalanan_dinas/pilihtunjangan')?>",'data_tunjangan_edit');
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit,#data_dibuat_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_pd:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_perjalanan_dinas/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_idk_edit').val(callback['id_karyawan']);
		$('#data_nosk_edit').val(callback['no_sk']);
		$('#data_tglsk_edit').val(callback['etgl_sk']);
		$('#data_nik_edit').val(callback['nik']);
		$('#data_nama_edit').val(callback['nama']);
		$('#tujuan_edit').val(callback['plant']).trigger('change');
		$('#data_kendaraan_edit').val(callback['e_kendaraan']).trigger('change');
		$('#data_plant_asal_edit').val(callback['e_plant_asal']).trigger('change');
		$('#data_plant_tujuan_edit').val(callback['e_plant_tujuan']).trigger('change');
		$('#data_lokasi_tujuan_edit').val(callback['e_lokasi']);
		$('#data_jarak_edit').val(callback['e_jarak']);
		$('#data_kendaraan_umum_edit').val(callback['e_kendaraan_umum']).trigger('change');
		$('#data_tunjangan_edit').val(callback['e_tunjangan']).trigger('change');
		$("#data_tanggal_edit").data('daterangepicker').setStartDate(callback['e_tanggal_mulai']);
		$("#data_tanggal_edit").data('daterangepicker').setEndDate(callback['e_tanggal_selesai']);
		$('#data_tanggal_pulang_edit').val(callback['e_tanggal_pulang']);
		$('#data_mengetahui_edit').val(callback['e_mengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['e_menyetujui']).trigger('change');
		$('#data_dibuat_edit').val(callback['e_dibuat']).trigger('change');
		$('#data_tugas_edit').val(callback['e_tugas']);
		$('#data_keterangan_edit').val(callback['e_keterangan']);
		$('#tabel_tunjangan_edit').val(callback['tabel_tunjangan_edit']);
		var menginap = callback['menginap'];
		if(menginap==1){
			$('#menginap_on_edit').show();
			$('#menginap_off_edit').hide();
			$('#penginapan_edit_div').show();
			$('#data_penginapan_edit').attr('required','required');
			$('#data_penginapan_edit').val(callback['e_nama_penginapan']).trigger('change');
		}else{
			$('#menginap_on_edit').hide();
			$('#menginap_off_edit').show();
			$('#penginapan_edit_div').hide();
			$('#data_penginapan_edit').removeAttr('required');
			$('#data_penginapan_edit').val('').trigger('change');
		}
	}
	function delete_modal(id) {
		var data={id_pd:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_perjalanan_dinas/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_pd:id};
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
				}else{
					do_edit()
				}
			}
		})
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/edit_perjalanan_dinas')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			refreshCode();
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/add_perjalanan_dinas')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
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
	function kendaraanPD(f) {
		setTimeout(function () {
			var name = $('#data_kendaraan_add').val();
			if(name == 'KPD0001') {
				$('#nama_kendaraan_umum').show();
				$('#data_kendaraan_umum_add').attr('required','required');
			}else {
				$('#nama_kendaraan_umum').hide();
				$('#data_kendaraan_umum_add').removeAttr('required');
			}
		},100);
	}
	function tujuanPD(f) {
		setTimeout(function () {
			var name = $('#tujuan').val();
			if(name == 'plant') {
				$('#tujuan_plant').show();
				$('#tujuan_non_plant').hide();
				$('#data_plant_tujuan_add').attr('required','required');
				$('#data_lokasi_tujuan_add').removeAttr('required');
				$('#data_jarak_add').removeAttr('required');
			}else if(name == 'non_plant') {
				$('#tujuan_plant').hide();
				$('#tujuan_non_plant').show();
				$('#data_plant_tujuan_add').removeAttr('required');
				$('#data_lokasi_tujuan_add').attr('required','required');
				$('#data_jarak_add').attr('required','required');
			}else{
				$('#tujuan_plant').hide();
				$('#tujuan_non_plant').hide();
				$('#data_plant_tujuan_add').removeAttr('required');
				$('#data_lokasi_tujuan_add').removeAttr('required');
				$('#data_jarak_add').removeAttr('required');
			}
		},100);
	}
	function kendaraanPDEdit(f) {
		setTimeout(function () {
			var name = $('#data_kendaraan_edit').val();
			if(name == 'KPD0001') {
				$('#nama_kendaraan_umum_edit').show();
				$('#data_kendaraan_umum_edit').attr('required','required');
			}else {
				$('#nama_kendaraan_umum_edit').hide();
				$('#data_kendaraan_umum_edit').removeAttr('required');
			}
		},100);
	}
	function tujuanPDEdit(f) {
		setTimeout(function () {
			var name = $('#tujuan_edit').val();
			if(name == 'plant') {
				$('#tujuan_plant_edit').show();
				$('#tujuan_non_plant_edit').hide();
				$('#data_plant_tujuan_edit').attr('required','required');
				$('#data_lokasi_tujuan_edit').removeAttr('required');
				$('#data_jarak_edit').removeAttr('required');
				$('#data_jarak_edit').val('');
			}else if(name == 'non_plant') {
				$('#tujuan_plant_edit').hide();
				$('#tujuan_non_plant_edit').show();
				$('#data_plant_tujuan_edit').removeAttr('required');
				$('#data_lokasi_tujuan_edit').attr('required','required');
				$('#data_jarak_edit').attr('required','required');
			}else{
				$('#tujuan_plant_edit').hide();
				$('#tujuan_non_plant_edit').hide();
				$('#data_plant_tujuan_edit').removeAttr('required');
				$('#data_lokasi_tujuan_edit').removeAttr('required');
				$('#data_jarak_edit').removeAttr('required');
			}
		},100);
	}
	function jstreeLoad(datax,usage) {
		$.each(datax, function (index, value) {
			$('#'+value).on('ready.jstree', function () {
				$("#"+value).jstree("open_all");
			});
			$('#'+value).on("changed.jstree", function (e, data) {			
				var checked_ids = [data.selected];
				var undetermined=$('#'+value).jstree().get_undetermined();
				undetermined = jQuery.grep(undetermined, function( a ) {
					return a != 0;
				});
				if (undetermined.length > 0) {
					checked_ids.push(undetermined);
				}
				$('#'+value+'_'+usage).val(checked_ids);
			});
		});
	}
	function view_tunjangan_edit() {
		var data=$('#form_edit').serialize();
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_tunjangan')?>",{search:data});  
		$('#tabel_tunjangan_edit').html(callback['tabel']);
		$('#tabel_tunjangan_edit').slideToggle('slow');
	}
</script> 