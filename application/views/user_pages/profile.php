<?php
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" href="<?php echo base_url('pages/data_karyawan'); ?>"><i
					class="fa fa-chevron-circle-left "></i></a>
			<i class="fa fa-user"></i> Profile <small class="view_nama_full"><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active view_nama_full">Profile <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-success">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle view_photo"
							data-source-photo="<?php echo base_url($foto);?>" src="<?php echo base_url($foto); ?>"
							id="fotop" alt="User profile picture">
						<h3 class="profile-username text-center view_nama_full"><?php echo $profile['nama']; ?></h3>
						<p class="text-muted text-center view_namajabatan">
							<?php
								if ($profile['nama_jabatan'] == "") {
									echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
								}else{
									echo $profile['nama_jabatan'];
								}
							?>
						</p>
						<?php if($profile['nama_grade'] == ""){
							echo '<p class="text-center"><label class="label label-danger text-center">Tidak Punya Grade</label></p>';
						}else{
							echo '<p class="text-center"><label class="label label-primary text-center view_grade">'.$profile['nama_grade'].' ('.$profile['nama_loker_grade'].')</label></p>';
						} ?>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b>Tanggal Masuk</b><label class="label label-info view_tglmasuk wordwrap pull-right"><?php echo date('d F Y',strtotime($profile['tgl_masuk'])); ?></label>
							</li>
							<li class="list-group-item clearfix">
								<b>Status Karyawan</b>
								<?php if($profile['nama_status'] == ""){
									echo '<label class="label label-danger pull-right">Belum Ada Status Karyawan</label>';
								}else{
									echo '<label class="pull-right label label-warning view_stkaryawan">'.$profile['nama_status'].'</label>';
								} ?>
							</li>
							<li class="list-group-item clearfix">
								<b>Level Jabatan</b>
								<?php
									if ($profile['nama_level_jabatan'] == "") {
										echo '<label class="pull-right label label-danger view_lvljabatan">Tidak Punya Level Jabatan</label>';
									}else{
										echo '<br><p class="view_lvljabatan" style="width:100%; font-size:12px; text-align:right;">'.$profile['nama_level_jabatan'].'</p>';
									}
								?>
							</li>
							<li class="list-group-item clearfix">
								<b>Bagian Jabatan</b>
								<?php if($profile['nama_bagian'] == ""){
										echo '<label class="label label-danger pull-right">Belum Ada Bagian</label>';
									}else{
										echo '<br><label class="pull-right label label-info view_bagian">'.$profile['nama_bagian'].'</label>';
									} 
								?>
							</li>
							<li class="list-group-item clearfix">
								<b>Lokasi Kerja</b><br>
									<?php
									if ($profile['nama_loker'] == "") {
										echo '<label class="pull-right label label-danger view_loker">Tidak Punya Lokasi Kerja</label>';
									}else{
										echo '<label class="pull-right label label-primary view_loker">'.$profile['nama_loker'].'</label>';
									}
								?>
							</li>
							<?php
								if ($profile['email_verified'] == 0) {
									if ($profile['email'] == "") {
										echo '<p class="text-center"><label class="label label-danger text-center">Anda Tidak Memiliki Email</label></p>';
									}else{
										echo '<p style="color: red;" class="text-center">Anda Harus harus verifikasi email</p>';
									}
								} 
							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a id="btn_info" onclick="data_info()" href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>
						<?php
							if($update){
								if (isset($up_date)) {
									if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
										echo '<li><a onclick="update_fo()" href="#update" data-toggle="tab"><i class="fa fa-edit"></i> Update Informasi</a></li>';
									}
								}
							}
						?>
						<li><a href="#update_nomor" data-toggle="tab"><i class="fab fa-whatsapp-square"></i> Update Nomor HP</a></li>
						<li><a onclick="foto_fo()" href="#foto" data-toggle="tab"><i class="fa fa-camera"></i> Upload Foto</a></li>
						<li><a onclick="pass_fo()" href="#pass" data-toggle="tab"><i class="fa fa-lock"></i> Ubah Password</a></li>
						<li><a onclick="log_fo()" href="#log" data-toggle="tab"><i class="fa fa-file-text"></i> Riwayat Login</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="info">
							<?php $this->load->view('_partial/_view_employee_info_fo'); ?>
						</div>
						<?php
							if (isset($up_date)) {
								if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
									echo '<div class="tab-pane" id="update">';
										$this->load->view('_partial/_view_employee_update_fo');
									echo '</div>';
								}
							}
						?>
						<div class="tab-pane" id="update_nomor">
						   <div class="row">
							  <form id="form_update_hp">
   								<input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
								 <div class="form-group clearfix">
									<label class="col-sm-2 control-label">No Handphone / Whatsapp</label>
									<div class="col-sm-10">
									   <input type="text" name="nomor" class="form-control" placeholder="Username" value="<?php echo $profile['no_hp'];?>">
									</div>
								 </div>
								 <div class="form-group clearfix">
									<div class="col-sm-offset-2 col-sm-10">
									   <button type="button" onclick="do_update_hp()" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
									</div>
								 </div>
							  </form>
						   </div>
						</div>
						<div class="tab-pane" id="foto">
							<?php $this->load->view('_partial/_view_employee_foto_fo'); ?>
						</div>
						<div class="tab-pane" id="pass">
							<?php $this->load->view('_partial/_view_employee_pass_fo'); ?>
						</div>
						<div class="tab-pane" id="log">
							<?php $this->load->view('_partial/_view_employee_log_fo'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript" src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   function do_update_hp(){
      if($("#form_update_hp")[0].checkValidity()) {
         submitAjax("<?php echo base_url('kemp/up_data_nomor')?>",null,'form_update_hp',null);
      }else{
         notValidParamx();
      } 
   }
</script>