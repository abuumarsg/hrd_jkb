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
			<i class="fa fas fa-calendar-plus fa-fw"></i> Lembur
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_lembur');?>"><i class="fa fas fa-calendar-plus fa-fw"></i> Data Lembur</a></li>
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
						<h3 class="box-title"><i class="fa fas fa-calendar-plus fa-fw"></i> Seluruh Data Lembur  <small><?php echo $profile['nama'];?></small></h3>
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
									echo form_open('rekap/export_data_lembur_kar');
										if (in_array($access['l_ac']['add'], $access['access'])) {
											// echo '<button class="btn btn-success" type="button" id="btn_tambah" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data</button>';
										}
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
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Nomor SPL</label>
													<div class="col-sm-8">
														<input type="hidden" name="karyawan" value="<?php echo $profile['id_karyawan']?>">
														<input type="text" name="kode_spl" id="kode_spl_add" class="form-control" placeholder="Nomor SPL" required="required">
													</div>
													<div class="col-sm-1">
														<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Tanggal Mulai - Selesai</label>
													<div class="col-sm-9">
														<div class="has-feedback">
															<span class="fa fa-calendar form-control-feedback"></span>
															<input type="text" name="tanggal" class="form-control pull-right date-range" placeholder="Tanggal Lembur" readonly="readonly">
														</div>
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Dibuat Oleh</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="id_dibuat" id="id_dibuat_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Diperiksa Oleh</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="id_diperiksa" id="id_diperiksa_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Diketahui Oleh</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="id_diketahui" id="id_diketahui_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Tanggal Buat</label>
													<div class="col-sm-9">
														<div class="has-feedback">
															<span class="fa fa-calendar form-control-feedback"></span>
															<input type="text" name="tgl_buat" id="tgl_buat_add" class="form-control pull-right date-picker" placeholder="Tanggal Dibuat" readonly="readonly">
														</div>
													</div>
												</div>
												<!-- <div class="form-group clearfix">
													<label class="col-sm-3 control-label">Potong Jam Istirahat</label>
													<div class="col-sm-9">
														<a id="potong_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
														<a id="potong_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
														<input type="hidden" name="jam_istirahat" id="potong_add" class="form-control" placeholder="Potong Jam Istirahat" readonly>
													</div>
												</div> -->
												<div class="form-group clearfix">
													<label class="col-sm-3 control-label">Keterangan</label>
													<div class="col-sm-9">
														<textarea name="keterangan" id="keterangan_add" class="form-control" placeholder="Keterangan"></textarea>
													</div>
												</div>
												<div class="form-group">
													<button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<?php } ?>
						<style>
							th, td { white-space: nowrap; }
						</style>
						<table id="table_data" class="table table-bordered table-striped data-table t3 row-border order-column" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Nomor SPL</th>
									<th>Tanggal Mulai</th>
									<th>Jam Mulai</th>
									<th>Tanggal Selesai</th>
									<th>Jam Selesai</th>
									<!-- <th>Dibuat Oleh</th> -->
									<!-- <th>Diperiksa Oleh</th> -->
									<!-- <th>Diketahui Oleh</th> -->
									<th>Tanggal Buat</th>
									<th>Jumlah Jam lembur</th>
									<!-- <th>Potongan Jam Istirahat</th> -->
									<!-- <th>Keterangan</th> -->
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
				<h2 class="modal-title">Detail Data Lembur <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor SPL</label>
							<div class="col-md-6" id="data_nospl_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Mulai</label>
							<div class="col-md-6" id="data_tglmulai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Selesai</label>
							<div class="col-md-6" id="data_tglselesai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_dibuat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diperiksa Oleh</label>
							<div class="col-md-6" id="data_diperiksa_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diketahui Oleh</label>
							<div class="col-md-6" id="data_diketahui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Buat</label>
							<div class="col-md-6" id="data_tgl_buat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Jam Lembur</label>
							<div class="col-md-6" id="data_jam_lebur_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Potong Jam Istirahat</label>
							<div class="col-md-6" id="data_jam_istirahat_view"></div>
						</div> -->
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kode Customer</label>
							<div class="col-md-6" id="data_kode_customer_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_view"><b class="text-success"></b></div>
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
							<div class="col-md-6" id="data_create_by_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_view"></div>
						</div>
					</div>
				</div>
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
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data Lembur <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit">
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" id="data_id_edit" name="id" value="">
							<input type="hidden" id="data_idk_edit" name="id_karyawan" value="">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Nomor SPL</label>
									<input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan']?>">
									<input type="text" name="kode_spl" id="kode_spl_edit" class="form-control" placeholder="Nomor SPL" readonly>
								</div>
								<div class="form-group">
									<label class="control-label">Tanggal Mulai - Selesai</label>
									<div class="has-feedback">
										<span class="fa fa-calendar form-control-feedback"></span>
										<input type="text" name="tanggal" id="data_tanggal_edit" class="form-control pull-right date-range" placeholder="Tanggal Lembur" readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Dibuat Oleh</label>
									<select class="form-control select2" name="id_dibuat" id="id_dibuat_edit" style="width: 100%;"></select>
								</div>
								<div class="form-group">
									<label class="control-label">Diperiksa Oleh</label>
									<select class="form-control select2" name="id_diperiksa" id="id_diperiksa_edit" style="width: 100%;"></select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Diketahui Oleh</label>
									<select class="form-control select2" name="id_diketahui" id="id_diketahui_edit" style="width: 100%;"></select>
								</div>
								<div class="form-group">
									<label class="control-label">Tanggal Buat</label>
									<div class="has-feedback">
										<span class="fa fa-calendar form-control-feedback"></span>
										<input type="text" name="tgl_buat" id="tgl_buat_edit" class="form-control pull-right date-picker" placeholder="Tanggal Buat" readonly>
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="control-label">Potong Jam Istirahat</label><br>
									<a id="potong_no_edit" style="font-size: 16pt;"><i class="far fa-square"></i></a>
									<a id="potong_yes_edit" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
									<input type="hidden" name="jam_istirahat" id="potong_edit" class="form-control" placeholder="Potong Jam Istirahat" readonly>
								</div> -->
								<div class="form-group">
									<label class="control-label">Keterangan</label>
									<textarea name="keterangan" id="keterangan_edit" class="form-control" placeholder="Keterangan"></textarea>
								</div>
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
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_lembur";
	var column="id_data_lembur";
	$(document).ready(function(){
		refreshCode();
		submitForm('form_add');
		submitForm('form_edit');
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/view_lembur_kar/view_all/'.$this->uri->segment(3))?>",
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
			{   targets: 2,
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
		$('#btn_tambah').click(function(){
			getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_dibuat_add'); 
			getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_diperiksa_add'); 
			getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_diketahui_add');
		})
		$('#potong_no').click(function(){
			$('#potong_no').hide();
			$('#potong_yes').show();
			$('#potong_add').val('1');
		})
		$('#potong_yes').click(function(){
			$('#potong_yes').hide();
			$('#potong_no').show();
			$('#potong_add').val('0');
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
		kode_generator("<?php echo base_url('presensi/data_lembur/kode');?>",'kode_spl_add');
	}
	function resetselectAdd() {
		$('#id_dibuat_add').val('').trigger('change');
		$('#id_diperiksa_add').val('').trigger('change');
		$('#id_diketahui_add').val('').trigger('change');
	}
	function view_modal(id) {
		var data={id_data_lembur:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_lembur_kar/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nomor']);
		$('#data_nospl_view').html(callback['nomor']);
		$('#data_tglmulai_view').html(callback['tanggal_mulai']);
		$('#data_tglselesai_view').html(callback['tanggal_selesai']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_dibuat_view').html(callback['dibuat_oleh']);
		$('#data_diperiksa_view').html(callback['diperiksa_oleh']);
		$('#data_diketahui_view').html(callback['diketahui_oleh']);
		$('#data_tgl_buat_view').html(callback['tanggal_buat']);
		$('#data_jam_lebur_view').html(callback['jumlah_lembur']);
		var potong = callback['potong_jam'];
		if(potong==1){
			var potong_jam_istirahat = '<span class="text-success">Dipotong Jam Istirahat</span>';
		}else{
			var potong_jam_istirahat = '<span class="text-danger">Tidak dipotong Jam Istirahat</span>';
		}
		$('#data_jam_istirahat_view').html(potong_jam_istirahat);
		$('#data_kode_customer_view').html(callback['kode_customer']);
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
	}
	function edit_modal() {
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_dibuat_edit'); 
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_diperiksa_edit'); 
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_diketahui_edit');
		$('#potong_no_edit').click(function(){
			$('#potong_no_edit').hide();
			$('#potong_yes_edit').show();
			$('#potong_edit').val('1');
		})
		$('#potong_yes_edit').click(function(){
			$('#potong_yes_edit').hide();
			$('#potong_no_edit').show();
			$('#potong_edit').val('0');
		})
		var id = $('input[name="data_id_view"]').val();
		var data={id_data_lembur:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_lembur_kar/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#kode_spl_edit').val(callback['nomor']);
		$('#id_dibuat_edit').val(callback['e_dibuat']).trigger('change');
		$('#id_diperiksa_edit').val(callback['e_diperiksa']).trigger('change');
		$('#id_diketahui_edit').val(callback['e_diketahui']).trigger('change');
		$('#tgl_buat_edit').val(callback['e_tgl_buat']);
		$('#keterangan_edit').val(callback['e_keterangan']);
		// addValueEditor('keterangan_edit',callback['e_keterangan']);
		$("#data_tanggal_edit").data('daterangepicker').setStartDate(callback['e_tanggal_mulai']);
		$("#data_tanggal_edit").data('daterangepicker').setEndDate(callback['e_tanggal_selesai']);
		var potong=callback['jam_istirahat_edit'];
		if (potong==1){
			$('#potong_no_edit').hide();
			$('#potong_yes_edit').show();
		}else{
			$('#potong_yes_edit').hide();
			$('#potong_no_edit').show();
		}
	}
	function delete_modal(id) {
		var data={id_data_lembur:id};
		var callback=getAjaxData("<?php echo base_url('presensi/view_lembur_kar/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_data_lembur:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/edit_lembur')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/add_lembur')?>",null,'form_add',null,null);
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
</script> 