<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-user"></i> Profile
      <small class="view_nama"><?php echo $profile['nama'];?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url('pages/employee');?>"><i class="fa fa-users"></i> Data Karyawan</a></li>
      <li class="active view_nama">Profile <?php echo $profile['nama'];?></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-success">
          <div class="box-body box-profile">
            <?php 
              if(!empty($profile['foto'])){
                $foto = $profile['foto'];
              }else{
                if($profile['kelamin']=='l'){
                  $foto = 'asset/img/user-photo/user.png';
                }elseif($profile['kelamin']=='p'){
                  $foto = 'asset/img/user-photo/userf.png';
                }
              }
            ?>
            <img class="profile-user-img img-responsive img-circle view_photo" data-source-photo="<?php echo base_url($foto);?>" src="<?php echo base_url($foto); ?>" id="fotop" alt="User profile picture">
            <h3 class="profile-username text-center view_nama"><?php echo $profile['nama']; ?></h3>
            <p class="text-muted text-center view_nama_jabatan">
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
            <p class="text-center"><label class="label label-success text-center">GOLONGAN <?=$profile['golongan']?></label></p>
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item clearfix">
                <b>Atasan</b>
                <?php
                  if ($profile['nama_atasan'] == "") {
                    echo '<label class="pull-right label label-danger view_lvljabatan">Tidak Punya Level Jabatan</label>';
                  }else{
                    echo '<br><p class="view_lvljabatan" style="width:100%; font-size:12px; text-align:right;">'.$profile['nama_atasan'].'</p>';
                  }
                ?>
              </li>
              <li class="list-group-item">
                <b>Tanggal Masuk</b><label class="pull-right label label-info view_tglmasuk"><?php echo date('d F Y',strtotime($profile['tgl_masuk'])); ?></label>
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
                  } ?>
                  
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
              <li class="list-group-item clearfix">
                <b>Status Akun Suspend</b><p class="pull-right" id="data_status_sus_view"></p>
              </li>
              <?php
                if ($profile['email_verified'] == 0) {
                  if ($profile['email'] == "") {
                    echo '<p class="text-center"><label class="label label-danger text-center">Karyawan Tidak Memiliki Email</label></p>';
                  }else{
                    echo '<p style="color: red;" class="text-center">Karyawan ini harus verifikasi email</p>';
                  }
                } 
              ?>
            </ul>
            <?php if (in_array($access['l_ac']['rkp'], $access['access'])) { ?>
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fas fa-print"></i> Print
                <span class="fa fa-caret-down"></span></button>
                <ul class="dropdown-menu">
                  <li><a onclick="biodata_umum()">Cetak Biodata Umum (Word)</a></li>
                  <!-- <li><a onclick="biodata_lengkap()">Cetak Biodata Lengkap (Excel)</a></li> -->
                </ul>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a onclick="data_info()" id="btn_info" href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>
            <li><a onclick="pribadi()" href="#update" data-toggle="tab"><i class="fa fa-edit"></i> Update Informasi</a></li>
            <li><a onclick="foto()" href="#foto" data-toggle="tab"><i class="fa fa-camera"></i> Upload Foto</a></li>
            <li><a onclick="pass()" href="#pass" data-toggle="tab"><i class="fa fa-lock"></i> Ubah Password</a></li>
            <li><a onclick="log()" href="#log" data-toggle="tab"><i class="fa fa-file-text"></i> Riwayat Login</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="info">
              <?php
                // echo '<pre>';
                // print_r($profile);
              ?>
              <?php $this->load->view('_partial/_view_employee_info'); ?>
            </div>
            <div class="tab-pane" id="update">
              <?php $this->load->view('_partial/_view_employee_update'); ?>
            </div>
            <div class="tab-pane" id="foto">
              <?php $this->load->view('_partial/_view_employee_foto'); ?>
            </div>
            <div class="tab-pane" id="pass">
              <?php $this->load->view('_partial/_view_employee_pass'); ?>
            </div>
            <div class="tab-pane" id="log">
              <?php $this->load->view('_partial/_view_employee_log'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
     get_data_sus();
  })
  function get_data_sus(){
    var nik = '<?=$profile['nik'];?>';
    var data_kar={nik:nik};
    var callback=getAjaxData("<?php echo base_url('employee/emppribadi')?>",data_kar);
    $('#data_status_sus_view').html(callback['status_suspen']);
  }
  function status_suspen(id,data) {
    var table="karyawan";
    var data_table={status_suspen:data};
    var where={id_karyawan:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    get_data_sus();
  }
	function biodata_umum() {
		var nik = "<?php echo $this->uri->segment(3)?>";
		window.location.href = "<?php echo base_url('cetak_word/biodata_umum/')?>" + nik;
	}
	function biodata_lengkap() {
		var nik = "<?php echo $this->uri->segment(3)?>";
		window.location.href = "<?php echo base_url('rekap/biodata_lengkap/')?>"+nik;
	}
</script>