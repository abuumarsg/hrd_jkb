
<div class="content-wrapper">
  <?php 
  if (!empty($this->session->flashdata('msgres_fs'))) {
    echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgres_fs').'</div>';
  }elseif (!empty($this->session->flashdata('msgres_fe'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgres_fe').'</div>';
  }elseif (!empty($this->session->flashdata('pass_sc'))) {
    echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('pass_sc').'</div>';
  }elseif (!empty($this->session->flashdata('pass_err'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('pass_err').'</div>';
  }elseif (!empty($this->session->flashdata('pass_same'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('pass_same').'</div>';
  }elseif (!empty($this->session->flashdata('pass_nsame'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('pass_nsame').'</div>';
  }elseif (!empty($this->session->flashdata('dlog_sc'))) {
    echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('dlog_sc').'</div>';
  }elseif (!empty($this->session->flashdata('dlog_err'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('dlog_err').'</div>';
  }elseif (!empty($this->session->flashdata('foto_sc'))) {
    echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('foto_sc').'</div>';
  }elseif (!empty($this->session->flashdata('foto_err'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('foto_err').'</div>';
  }elseif (!empty($this->session->flashdata('foto_type'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('foto_type').'</div>';
  }elseif (!empty($this->session->flashdata('foto_size'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('foto_size').'</div>';
  }elseif (!empty($this->session->flashdata('up_pribadi_sc'))) {
    echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('up_pribadi_sc').'</div>';
  }elseif (!empty($this->session->flashdata('up_pribadi_err'))) {
    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('up_pribadi_err').'</div>';
  }
  ?>
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-user"></i> Profile
      <small><?php echo $profile['nama'];?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Profile <?php echo $profile['nama'];?></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
    <div class="col-md-3">
      <div class="box box-success">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle view_photo" src="<?php echo base_url($profile['foto']); ?>" alt="User profile picture">

          <h3 class="profile-username text-center"><?php echo $profile['nama']; ?></h3>

          <p class="text-muted text-center"><?php
          if ($jabatan['jabatan'] == "") {
            echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
          }else{
           echo $jabatan['jabatan']; 
          }
          ?></p>
          <p class="text-center"><label class="label label-primary text-center"><?php echo $profile['grade']; ?></label></p>

          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Tanggal Masuk</b> <label class="pull-right label label-info"><?php echo $this->formatter->getDateTimeMonthFormatUser($profile['tgl_masuk']); ?></label>
            </li>
            <li class="list-group-item">
              <b>Unit Kerja</b><?php 
              if ($loker['nama'] == "") {
                echo '<label class="pull-right label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
              }else{
               echo '<label class="pull-right label label-success">'.$loker['nama'].'</label>'; 
              }
              ?>
            </li>
            <li class="list-group-item">
              <b>Status Pegawai</b> <label class="pull-right label label-warning"><?php echo $profile['status_pegawai']; ?></label>
            </li>
            <?php
              if ($profile['email_verified'] == 0) {
                if ($profile['email'] == "") {
                  echo '<p class="text-center"><label class="label label-danger text-center">Kamu Tidak Memiliki Email</label></p>';
                }else{
                  echo '<p style="color: red;" class="text-center">Kamu harus verifikasi email</p>';
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
          <?php
          if (!empty($this->session->flashdata('dlog_sc')) || !empty($this->session->flashdata('dlog_err'))) {
            echo '<li><a href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>';
            if (count($up_date) != 0) {
              if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
                echo '<li><a href="#update" data-toggle="tab"><i class="fa fa-edit"></i> Update Informasi</a></li>';
              }
            }
            echo '<li><a href="#foto" data-toggle="tab"><i class="fa fa-camera"></i> Upload Foto</a></li>
            <li><a href="#pass" data-toggle="tab"><i class="fa fa-lock"></i> Ubah Password</a></li>
            <li class="active"><a href="#log" data-toggle="tab"><i class="fa fa-file-text"></i> Riwayat Login</a></li>';
          }elseif (!empty($this->session->flashdata('up_pribadi_sc')) || !empty($this->session->flashdata('up_pribadi_err'))) {
            echo '<li><a href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>';
            if (count($up_date) != 0) {
              if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
                echo '<li class="active"><a href="#update" data-toggle="tab"><i class="fa fa-edit"></i> Update Informasi</a></li>';
              }
            }
            echo '<li><a href="#foto" data-toggle="tab"><i class="fa fa-camera"></i> Upload Foto</a></li>
            <li><a href="#pass" data-toggle="tab"><i class="fa fa-lock"></i> Ubah Password</a></li>
            <li><a href="#log" data-toggle="tab">Riwayat Login</a></li>';
          }elseif (!empty($this->session->flashdata('msgres_fs')) || !empty($this->session->flashdata('msgres_fe')) || !empty($this->session->flashdata('foto_type')) || !empty($this->session->flashdata('foto_size')) || !empty($this->session->flashdata('foto_err')) || !empty($this->session->flashdata('foto_sc'))) {
            echo '<li><a href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>';
            if (count($up_date) != 0) {
              if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
                echo '<li><a href="#update" data-toggle="tab"><i class="fa fa-edit"></i> Update Informasi</a></li>';
              }
            }
            echo '<li class="active"><a href="#foto" data-toggle="tab"><i class="fa fa-camera"></i> Upload Foto</a></li>
            <li><a href="#pass" data-toggle="tab"><i class="fa fa-lock"></i> Ubah Password</a></li>
            <li><a href="#log" data-toggle="tab"><i class="fa fa-file-text"></i> Riwayat Login</a></li>';
          }elseif (!empty($this->session->flashdata('pass_same')) || !empty($this->session->flashdata('pass_nsame')) || !empty($this->session->flashdata('pass_sc')) || !empty($this->session->flashdata('pass_err'))) {
            echo '<li><a href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>';
            if (count($up_date) != 0) {
              if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
                echo '<li><a href="#update" data-toggle="tab"><i class="fa fa-edit"></i> Update Informasi</a></li>';
              }
            }
            echo '<li><a href="#foto" data-toggle="tab"><i class="fa fa-camera"></i> Upload Foto</a></li>
            <li class="active"><a href="#pass" data-toggle="tab"><i class="fa fa-lock"></i> Ubah Password</a></li>
            <li><a href="#log" data-toggle="tab"><i class="fa fa-file-text"></i> Riwayat Login</a></li>';
          }else{
            echo '<li class="active"><a href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>';
            if (count($up_date) != 0) {
              if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
                echo '<li><a href="#update" data-toggle="tab"><i class="fa fa-edit"></i> Update Informasi</a></li>';
              }
            }
            echo '<li><a href="#foto" data-toggle="tab"><i class="fa fa-camera"></i> Upload Foto</a></li>
            <li><a href="#pass" data-toggle="tab"><i class="fa fa-lock"></i> Ubah Password</a></li>
            <li><a href="#log" data-toggle="tab"><i class="fa fa-file-text"></i> Riwayat Login</a></li>';
          }
          ?>

        </ul>
        <div class="tab-content">
          <?php
          if (!empty($this->session->flashdata('msgres_fs')) || !empty($this->session->flashdata('msgres_fe')) || !empty($this->session->flashdata('pass_same')) || !empty($this->session->flashdata('pass_nsame')) || !empty($this->session->flashdata('pass_sc')) || !empty($this->session->flashdata('pass_err')) || !empty($this->session->flashdata('dlog_sc')) || !empty($this->session->flashdata('dlog_err')) || !empty($this->session->flashdata('foto_type')) || !empty($this->session->flashdata('foto_size')) || !empty($this->session->flashdata('foto_err')) || !empty($this->session->flashdata('foto_sc')) || !empty($this->session->flashdata('up_pribadi_sc')) || !empty($this->session->flashdata('up_pribadi_err'))) {
            echo '<div class="tab-pane" id="info">';
          }else{
            echo '<div class="tab-pane active" id="info">';
          }
          ?>
          <table class='table table-bordered table-striped table-hover'>
            <tr>
              <th>Nomor Induk Karyawan</th>
              <td><?php echo ucwords($profile['nik']);?> <label class="label label-info">Username</label></td>
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
              <th>Alamat Asal</th>
              <td><?php
              if ($profile['alamat_asal_jalan'] == NULL || $profile['alamat_asal_jalan'] == "") {
                echo '<label class="label label-danger">Alamat Belum Diinput</label>';
              }else{
                echo ucwords($profile['alamat_asal_jalan']).', '.ucwords($profile['alamat_asal_desa']).', '.ucwords($profile['alamat_asal_kecamatan']).', '.ucwords($profile['alamat_asal_kabupaten']).', '.ucwords($profile['alamat_asal_provinsi']).'<br>Kode Pos : ';
                if ($profile['alamat_asal_pos'] == 0) {
                  echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
                }else{
                  echo ucwords($profile['alamat_asal_pos']);
                }
              }
              ?></td>
            </tr>
            <tr>
              <th>Alamat Sekarang</th>
              <td><?php
              if ($profile['alamat_sekarang_jalan'] == NULL || $profile['alamat_sekarang_jalan'] == "") {
                echo '<label class="label label-danger">Alamat Belum Diinput</label>';
              }else{
                echo ucwords($profile['alamat_sekarang_jalan']).', '.ucwords($profile['alamat_sekarang_desa']).', '.ucwords($profile['alamat_sekarang_kecamatan']).', '.ucwords($profile['alamat_sekarang_kabupaten']).', '.ucwords($profile['alamat_sekarang_provinsi']).' <br>Kode Pos : ';
                if ($profile['alamat_sekarang_pos'] == 0) {
                  echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
                }else{
                  echo ucwords($profile['alamat_sekarang_pos']);
                }
              }
              ?></td>
            </tr>
            <tr>
              <th>Agama</th>
              <td><?php echo ucwords($profile['agama']);?></td>
            </tr>
            <tr>
              <th>Golongan Darah</th>
              <td><?php echo ucwords($profile['gol_darah']);?></td>
            </tr>
            <tr>
              <th>Tinggi | Berat Badan</th>
              <td><?php  
                if ($profile['tinggi_badan'] == 0) {
                  echo '<label class="label label-danger">Tinggi Badan Tidak Ada</label>';
                }else{
                  echo $profile['tinggi_badan'].' Cm';
                }
                echo ' | ';
                if ($profile['berat_badan'] == 0) {
                  echo '<label class="label label-danger">Berat Badan Tidak Ada</label>';
                }else{
                  echo $profile['berat_badan'].' Kg';
                }
                ?></td>
            </tr>
            <tr>
              <th>Email</th>
              <td>
                <?php 
                if ($profile['email'] == NULL) {
                  echo '<label class="label label-danger">Email Tidak Ada</label>';
                }else{
                  if ($profile['email_verified'] == 0) {
                    echo '<a data-toggle="tooltip" title="Email Belum Diverifikasi">'.$profile['email'].'</a>';
                    echo ' <i style="color:red;" class="fa fa-warning"></i>';
                  }else{
                    echo $profile['email'].' <i style="color:green;" data-toggle="tooltip" title="Terverifikasi" class="fa fa-check-circle"></i>';
                  }
                }
                ?></td>
              </tr>
              <tr>
                <th>Jenis Kelamin</th>
                <td><?php 
                if($profile['kelamin'] == 'Pria'){
                  echo '<i class="fa fa-male" style="color:blue"></i> Laki-laki';
                }else{
                  echo '<i class="fa fa-female" style="color:#ff00a5"></i> Perempuan';
                }?>
              </td>
            </tr>
            <tr>
              <th>Tempat Tanggal Lahir</th>
              <td><?php if($profile['tempat_lahir'] != NULL){echo $profile['tempat_lahir'].', ';} echo date('d/m/Y',strtotime($profile['tgl_lahir']));?></td>
            </tr>
            <tr>
              <th>Nomor Ponsel</th>
              <td><?php 
              if ($profile['no_hp'] == NULL) {
                echo '<label class="label label-danger">Nomor Ponsel Tidak Ada</label>';
              }else{
                echo ucwords($profile['no_hp']);
              }
              ?></td>
            </tr>
            <tr>
              <th>Nomor Penting</th>
              <td>
                <table class="table">
                  <tr>
                    <th width="20%">Rekening</th>
                    <td><?php echo ucwords($profile['rekening']);?></td>
                  </tr>
                  <tr>
                    <th>NPWP</th>
                    <td><?php 
                     if ($profile['npwp'] == NULL) {
                      echo '<label class="label label-danger">Tidak Memiliki NPWP</label>';
                    }else{
                     echo ucwords($profile['npwp']);
                    }
                    echo ' <label class="label label-success">'.$profile['status_pajak'].'</label>';
                    ?> 
                    </td>
                  </tr>
                  <tr>
                    <th>BPJS Kesehatan</th>
                    <td><?php
                    if ($profile['bpjskes'] == NULL) {
                      echo '<label class="label label-danger">Tidak Memiliki BPJS Kesehatan</label>';
                    }else{
                     echo ucwords($profile['bpjskes']);
                    }?> 
                    </td>
                  </tr>
                  <tr>
                    <th>BPJS Tenaga Kerja</th>
                    <td><?php 
                     if ($profile['bpjstk'] == NULL) {
                      echo '<label class="label label-danger">Tidak Memiliki BPJS Tenaga Kerja</label>';
                    }else{
                     echo ucwords($profile['bpjstk']);
                    }?> 
                    </td>
                  </tr>
                  <tr>
                    <th>Nomor Ponsel Ibu</th>
                    <td><?php
                    if ($profile['no_hp_ibu'] == NULL) {
                      echo '<label class="label label-danger">Nomor Ponsel Ibu Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['no_hp_ibu']);
                    }?> 
                    </td>
                  </tr>
                  <tr>
                    <th>Nomor Ponsel Ayah</th>
                    <td><?php
                    if ($profile['no_hp_ayah'] == NULL) {
                      echo '<label class="label label-danger">Nomor Ponsel Ayah Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['no_hp_ayah']);
                    }?> 
                    </td>
                  </tr>
                  <tr>
                    <th>Nomor Ponsel Pasangan</th>
                    <td><?php
                    if ($profile['no_hp_pasangan'] == NULL) {
                      echo '<label class="label label-danger">Nomor Ponsel Pasangan Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['no_hp_pasangan']);
                    }?> 
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <th>Pendidikan</th>
              <td>
                <table class="table">
                  <tr>
                    <th width="20%">Jenjang</th>
                    <td><?php echo ucwords($profile['pendidikan']);?></td>
                  </tr>
                  <tr>
                    <th>Universitas</th>
                    <td><?php
                    if ($profile['universitas'] == NULL) {
                      echo '<label class="label label-danger">Universitas Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['universitas']);
                    }?> 
                    </td>
                  </tr>
                  <tr>
                    <th>Jurusan</th>
                    <td><?php 
                     if ($profile['jurusan'] == NULL) {
                      echo '<label class="label label-danger">Jurusan Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['jurusan']);
                    }?> 
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <th>Data Keluarga</th>
              <td>
                <table class="table">
                  <tr>
                    <th width="20%">Ibu Kandung</th>
                    <td><?php 
                     if ($profile['ibu_kandung'] == NULL) {
                      echo '<label class="label label-danger">Data Ibu Kandung Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['ibu_kandung']);
                    }
                    ?> 
                    </td>
                  </tr>
                  <tr>
                    <th>Ayah Kandung</th>
                    <td><?php
                    if ($profile['ayah_kandung'] == NULL) {
                      echo '<label class="label label-danger">Data Ayah Kandung Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['ayah_kandung']);
                    }?> 
                    </td>
                  </tr>
                  <tr>
                    <th>Status Menikah</th>
                    <td><?php 
                     if ($profile['status_nikah'] == NULL) {
                      echo '<label class="label label-danger">Data Status Nikah Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['status_nikah']);
                    }?> 
                    </td>
                  </tr>
                  <tr>
                    <th>Nama Pasangan</th>
                    <td><?php 
                     if ($profile['nama_pasangan'] == NULL) {
                      echo '<label class="label label-danger">Data Nama Pasangan Tidak Ada</label>';
                    }else{
                     echo ucwords($profile['nama_pasangan']);
                    }?> 
                    </td>
                  </tr>
                  <tr>
                    <th>Jumlah Anak</th>
                    <td><?php 
                     if ($profile['jumlah_anak'] == NULL || $profile['jumlah_anak'] == 0) {
                      echo '<label class="label label-danger">Belum Punya Anak</label>';
                    }else{
                     echo ucwords($profile['jumlah_anak']);
                    }?> 
                    </td>
                  </tr>
                  <?php 
                    if ($profile['jumlah_anak'] != NULL || $profile['jumlah_anak'] > 0) {
                      if ($profile['jumlah_anak'] > 3) {
                        $jm=3;
                      }else{
                        $jm=$profile['jumlah_anak'];
                      }
                      for ($i=1; $i <=$jm; $i++) { 
                        $nm='anak_'.$i; 
                        $t='ttl_anak_'.$i; 
                  ?>
                  <tr>
                    <th>Nama Anak Ke- <?php echo $i;?></th>
                    <td><?php echo ucwords($profile[$nm]).'<br><label class="label label-success">Tanggal Lahir : '.date('d/m/Y',strtotime($profile[$t])).'</label>';?> 
                    </td>
                  </tr>
                  <?php }} ?>
                </table>
              </td>
            </tr>    
            <tr>
              <th>Tanggal Daftar</th>
              <td><?php echo date('d/m/Y H:i:s',strtotime($profile['create_date']));?></td>
            </tr>
            <tr>
              <th>Tanggal Update</th>
              <td><?php echo date('d/m/Y H:i:s',strtotime($profile['update_date']));?></td>
            </tr>
            <tr>
              <th>Terakhir Login</th>
              <td><label class="label label-primary"><?php
              if ($profile['last_login'] == "0000-00-00 00:00:00") {
                echo 'Belum Pernah Login';
              }else{
               echo date('d/m/Y H:i:s',strtotime($profile['last_login']));
              }
              ?></label></td>
            </tr>
          </table>
        </div>
          <?php 
          if (count($up_date) != 0) {
            if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
              if (!empty($this->session->flashdata('up_pribadi_sc')) || !empty($this->session->flashdata('up_pribadi_err'))) {
                echo '<div class="tab-pane active" id="update">';
              }else{
                echo '<div class="tab-pane" id="update">';
              }
          ?>
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#pribadi" data-toggle="tab"><i class="fa fa-user"></i> Data Pribadi</a></li>
              <li><a href="#fam" data-toggle="tab"><i class="fa fa-users"></i> Data Keluarga</a></li>
              <li><a href="#edu" data-toggle="tab"><i class="fa fa-mortar-board"></i> Data Pendidikan</a></li>
              <li><a href="#numb" data-toggle="tab"><i class="fa fa-credit-card"></i> Nomor Penting</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="pribadi">
                <?php echo form_open('kemp/edt_pribadi',array('class'=>'form-horizontal'));?>
                <div class="form-group">
                  <label class="col-sm-2 control-label"></label>
                  <div class="col-sm-10">
                    <b class="text-red">Semua Data HARUS DI ISI</b>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nomor KTP</label>
                  <div class="col-sm-10">
                    <input type="number" name="no_ktp" class="form-control" placeholder="Masukkan Nomor KTP" value="<?php echo $profile['no_ktp'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nama</label>
                  <div class="col-sm-10">
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" value="<?php echo $profile['nama'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Tempat Lahir</label>
                  <div class="col-sm-10">
                    <input type="text" name="tempat_lahir" class="form-control" placeholder="Masukkan Tempat Lahir" value="<?php echo $profile['tempat_lahir'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Tanggal Lahir</label>
                  <div class="col-sm-10">
                    <input type="text" name="tanggal_lahir" class="form-control" placeholder="Masukkan Tanggal Lahir" id="datepicker" value="<?php echo date('d/m/Y',strtotime($profile['tgl_lahir']));?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat Asal</label>
                  <div class="col-sm-10">
                    <div class="col-md-6">
                      <label>Nama Jalan</label>
                      <textarea name="alamat_asal_jalan" class="form-control" placeholder="Masukkan Nama Jalan" required><?php echo $profile['alamat_asal_jalan'];?></textarea>
                      <label>Nama Desa</label>
                      <input type="text" name="alamat_asal_desa" class="form-control" placeholder="Masukkan Nama Desa" value="<?php echo $profile['alamat_asal_desa'];?>" required>
                      <label>Nama Kecamatan</label>
                      <input type="text" name="alamat_asal_kecamatan" class="form-control" placeholder="Masukkan Nama Kecamatan" value="<?php echo $profile['alamat_asal_kecamatan'];?>" required>
                    </div>
                    <div class="col-md-6">
                      <label>Nama Kabupaten / Kota</label>
                      <input type="text" name="alamat_asal_kabupaten" class="form-control" placeholder="Masukkan Nama Kabupaten atau Kota" value="<?php echo $profile['alamat_asal_kabupaten'];?>" required>
                      <label>Nama Provinsi</label>
                      <input type="text" name="alamat_asal_provinsi" class="form-control" placeholder="Masukkan Nama Provinsi" value="<?php echo $profile['alamat_asal_provinsi'];?>" required>
                      <label>Kode Pos</label>
                      <input type="text" name="alamat_asal_pos" class="form-control" placeholder="Masukkan Kode Pos" value="<?php echo $profile['alamat_asal_pos'];?>" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat Sekarang</label>
                  <div class="col-sm-10">
                    <div class="col-md-6">
                      <label>Nama Jalan</label>
                      <textarea name="alamat_sekarang_jalan" class="form-control" placeholder="Masukkan Nama Jalan" required><?php echo $profile['alamat_sekarang_jalan'];?></textarea>
                      <label>Nama Desa</label>
                      <input type="text" name="alamat_sekarang_desa" class="form-control" placeholder="Masukkan Nama Desa" value="<?php echo $profile['alamat_sekarang_desa'];?>" required>
                      <label>Nama Kecamatan</label>
                      <input type="text" name="alamat_sekarang_kecamatan" class="form-control" placeholder="Masukkan Nama Kecamatan" value="<?php echo $profile['alamat_sekarang_kecamatan'];?>" required>
                    </div>
                    <div class="col-md-6">
                      <label>Nama Kabupaten / Kota</label>
                      <input type="text" name="alamat_sekarang_kabupaten" class="form-control" placeholder="Masukkan Nama Kabupaten atau Kota" value="<?php echo $profile['alamat_sekarang_kabupaten'];?>" required>
                      <label>Nama Provinsi</label>
                      <input type="text" name="alamat_sekarang_provinsi" class="form-control" placeholder="Masukkan Nama Provinsi" value="<?php echo $profile['alamat_sekarang_provinsi'];?>" required>
                      <label>Kode Pos</label>
                      <input type="text" name="alamat_sekarang_pos" class="form-control" placeholder="Masukkan Kode Pos" value="<?php echo $profile['alamat_sekarang_pos'];?>" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $profile['email'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nomor Ponsel</label>
                  <div class="col-sm-10">
                    <input type="number" name="no_hp" class="form-control" placeholder="Masukkan Tempat Lahir" value="<?php echo $profile['no_hp'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Jenis Kelamin</label>
                  <div class="col-sm-10">
                    <select class="form-control select2" style="width: 100%;" name="kelamin" required>
                      <?php
                      if ($profile['kelamin'] == "Pria") {
                        echo '<option value="Pria" selected>Laki-laki</option>
                        <option value="Wanita">Perempuan</option>';
                      }else{
                        echo '<option value="Pria">Laki-laki</option>
                        <option value="Wanita" selected>Perempuan</option>';
                      }?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-10">
                    <?php 
                    $op = array('Islam'=>'Islam','Kristen Katholik'=>'Kristen Katholik','Kristen Protestan'=>'Kristen Protestan','Hindu'=>'Hindu','Buddha'=>'Buddha','Kong Hu Cu'=>'Kong Hu Cu',);
                    $sel = array($profile['agama']);
                    $ex = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Agama','required'=>'required');
                    echo form_dropdown('agama',$op,$sel,$ex);?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Data Tubuh</label>
                  <div class="col-sm-10">
                    <div class="col-md-4">
                      <label>Golongan Darah</label>
                      <input type="text" name="gol_darah" class="form-control" placeholder="Masukkan Golongan Darah" value="<?php echo $profile['gol_darah'];?>" maxlenght="2" required>
                    </div>
                    <div class="col-md-4">
                      <label>Tinggi Badan (Cm)</label>
                      <input type="number" max="300" name="tinggi" class="form-control" placeholder="Masukkan Tinggi Badan" value="<?php echo $profile['tinggi_badan'];?>" required>
                    </div>
                    <div class="col-md-4">
                      <label>Berat Badan (Kg)</label>
                      <input type="number" name="berat" max="500" class="form-control" placeholder="Masukkan Berat Badan" value="<?php echo $profile['berat_badan'];?>" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
                  </div>
                </div>
                <?php echo form_close();?>
              </div>
              <div class="tab-pane" id="fam">
                <?php echo form_open('kemp/edt_family',array('class'=>'form-horizontal'));?>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Ayah Kandung</label>
                  <div class="col-sm-10">
                    <input type="text" name="ayah_kandung" class="form-control" placeholder="Masukkan Nama Ayah Kandung" value="<?php echo $profile['ayah_kandung'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Ibu Kandung</label>
                  <div class="col-sm-10">
                    <input type="text" name="ibu_kandung" class="form-control" placeholder="Masukkan Nama Ibu Kandung" value="<?php echo $profile['ibu_kandung'];?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Status Menikah</label>
                  <div class="col-sm-10">
                    <?php 
                    $op1= array('Menikah'=>'Menikah','Belum Menikah'=>'Belum Menikah');
                    $sel1 = array($profile['status_nikah']);
                    $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Agama','required'=>'required', 'id'=>'stt_m');
                    echo form_dropdown('status_nikah',$op1,$sel1,$ex1);?>
                    <br>
                    <br>
                    <?php 
                      if ($profile['status_nikah'] == 'Belum Menikah') {
                        echo '<div class="col-sm-12" id="passg" style="display:none;">';
                      }else{
                        echo '<div class="col-sm-12" id="passg">';
                      }
                    ?>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Nama Pasangan</label>
                          <div class="col-sm-9">
                            <input type="text" name="nama_pasangan" class="form-control" placeholder="Masukkan Nama Pasangan" value="<?php echo $profile['nama_pasangan'];?>" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Jumlah Anak</label>
                          <div class="col-sm-9">
                            <input type="number" name="jumlah_anak" class="form-control" placeholder="Masukkan Jumlah Anak" value="<?php echo $profile['jumlah_anak'];?>" required>
                          </div>
                        </div>
                        <?php 
                          for ($i=1; $i <=3 ; $i++) { 
                            echo '<div class="form-group">
                            <label class="col-sm-3 control-label">Data Anak Ke - '.$i.'</label>
                            <div class="col-sm-9">
                              <label>Nama Anak Ke - '.$i.'</label>    
                              <input type="text" name="nama_anak_'.$i.'" class="form-control" placeholder="Nama Anak Ke - '.$i.'" value="'.$profile['anak_'.$i].'">
                              <label>Tanggal Lahir Anak Ke - 1</label> ';
                              if (isset($profile['ttl_anak_'.$i])) {
                                echo '<input type="text" id="datepicker'.$i.'" name="ttl_anak_'.$i.'" class="form-control" placeholder="Masukkan Tanggal Lahir Anak Ke - '.$i.'" value="'.date('d/m/Y',strtotime($profile['ttl_anak_'.$i])).'">
                                ';
                              }else{
                                echo '<input type="text" id="datepicker'.$i.'" name="ttl_anak_'.$i.'" class="form-control" placeholder="Masukkan Tanggal Lahir Anak Ke - '.$i.'">
                                ';
                              }
                              echo '</div>
                            </div>
                            ';
                          }
                        ?>
                         
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                  </div>
                </div>
                <?php echo form_close();?>
              </div>
              <div class="tab-pane" id="edu">
                <?php echo form_open('kemp/edt_edu',array('class'=>'form-horizontal'));?>
                <div class="panel">
                  <div class="panel-heading bg-yellow"><i class="fa fa-mortar-board"></i> Pendidikan Formal</div>
                     
                  <div class="panel-body">
                    <div class="form-group">
                          <label class="col-sm-2 control-label">Jenjang</label>
                          <div class="col-sm-10">
                            <?php 
                            $op2= array('D3'=>'D3','D4'=>'D4','S1'=>'S1','S2'=>'S2','S3'=>'S3');
                            $sel2 = array($profile['pendidikan']);
                            $ex2 = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Jenjang','required'=>'required');
                            echo form_dropdown('jenjang',$op2,$sel2,$ex2);?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Universitas</label>
                          <div class="col-sm-10">
                            <input type="text" name="universitas" class="form-control" placeholder="Masukkan Nama Ibu Kandung" value="<?php echo $profile['universitas'];?>" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Jurusan</label>
                          <div class="col-sm-10">
                            <input type="text" name="jurusan" class="form-control" placeholder="Masukkan Nama Ibu Kandung" value="<?php echo $profile['jurusan'];?>" required>
                          </div>
                        </div>
                  </div>
                </div>
                <div class="panel">
                  <div class="panel-heading bg-green"><i class="fa fa-mortar-board"></i> Pendidikan Non-Formal</div>
                  <div class="panel-body">
                    <?php 
                    if ($profile['organisasi'] != NULL) {
                      $org=explode(';', $profile['organisasi']);
                      $ss=0;
                      for ($ib=1; $ib <=count($org); $ib++) { 
                    ?>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Organisasi - <?php echo $ib;?></label>
                          <div class="col-sm-10">
                            <input type="text" name="organisasi<?php echo $ib;?>" class="form-control" placeholder="Masukkan Organisasi Ke - <?php echo $ib;?>" value="<?php echo $org[$ss];?>">
                          </div>
                        </div>
                        <?php 
                          $ss++;
                        }
                      }else{
                        for ($ib1=1; $ib1 <=3 ; $ib1++) {
                          ?> 
                          <div class="form-group">
                          <label class="col-sm-2 control-label">Organisasi - <?php echo $ib1;?></label>
                          <div class="col-sm-10">
                            <input type="text" name="organisasi<?php echo $ib1;?>" class="form-control" placeholder="Masukkan Organisasi Ke - <?php echo $ib1;?>">
                          </div>
                        </div>
                        <?php }
                      }

                      if ($profile['pendidikan_non_formal'] != NULL) {
                         
                       $pnf=explode(';', $profile['pendidikan_non_formal']);
                      for ($ic=1; $ic <=count($pnf); $ic++) { 
                    ?>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Pendidikan Non-Formal - <?php echo $ic;?></label>
                          <div class="col-sm-10">
                            <input type="text" name="non_f<?php echo $ic;?>" class="form-control" placeholder="Masukkan Pendidikan Non-Formal Ke - <?php echo $ic;?>" value="<?php echo $pnf[$ic];?>" required>
                          </div>
                        </div>
                        <?php }

                        }else{
                          for ($ic=1; $ic <=3 ; $ic++) { 
                          ?>
                            <div class="form-group">
                              <label class="col-sm-2 control-label">Pendidikan Non-Formal - <?php echo $ic;?></label>
                              <div class="col-sm-10">
                                <input type="text" name="non_f<?php echo $ic;?>" class="form-control" placeholder="Masukkan Pendidikan Non-Formal Ke - <?php echo $ic;?>" value="<?php echo $profile['pendidikan_non_formal'];?>" required>
                              </div>
                            </div>
                          <?php 
                          }
                        } ?>
                  </div>
                </div>
                <div class="panel">
                  <div class="panel-heading bg-blue"><i class="fa fa-child"></i> Pendidikan Anak</div>
                  <div class="panel-body">
                    <?php 
                      for ($ia=1; $ia <=3 ; $ia++) {
                        if ($profile['pend_ank_'.$ia] != NULL) {
                          $pn=explode(';', $profile['pend_ank_'.$ia]);
                        
                    ?>
                    <label class="col-sm-12 control-label">Anak Ke - <?php echo $ia;?></label>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Jenjang</label>
                      <div class="col-sm-10">
                        <?php 
                        $op2= array('TK'=>'TK','SD'=>'SD','SMP'=>'SMP','SMA'=>'SMA','D1'=>'D1','D2'=>'D2','D3'=>'D3','D4'=>'D4','S1'=>'S1','S2'=>'S2','S3'=>'S3');
                        $sel2 = array($pn[0]);
                        $ex2= array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Jenjang','required'=>'required');
                        echo form_dropdown('jenjang_an_'.$ia,$op2,$sel2,$ex2);?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Sekolah / Universitas</label>
                      <div class="col-sm-10">
                        <input type="text" name="universitas_an<?php echo $ia;?>" class="form-control" placeholder="Masukkan Nama Sekolah / Universitas" value="<?php echo $pn[1];?>" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Jurusan</label>
                      <div class="col-sm-10">
                        <input type="text" name="jurusan_an<?php echo $ia;?>" class="form-control" placeholder="Masukkan Nama Jurusan" value="<?php echo $pn[2];?>">
                      </div>
                    </div>
                    <?php }else{
                      ?>
                      <label class="col-sm-12 control-label">Anak Ke - <?php echo $ia;?></label>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Jenjang</label>
                      <div class="col-sm-10">
                        <?php 
                        $op2= array(NULL=>'Pilih Jenjang','TK'=>'TK','SD'=>'SD','SMP'=>'SMP','SMA'=>'SMA','D1'=>'D1','D2'=>'D2','D3'=>'D3','D4'=>'D4','S1'=>'S1','S2'=>'S2','S3'=>'S3');
                        $sel2 = array(NULL);
                        $ex2= array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Jenjang','required'=>'required');
                        echo form_dropdown('jenjang_an_'.$ia,$op2,$sel2,$ex2);?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Sekolah / Universitas</label>
                      <div class="col-sm-10">
                        <input type="text" name="universitas_an<?php echo $ia;?>" class="form-control" placeholder="Masukkan Nama Sekolah / Universitas">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Jurusan</label>
                      <div class="col-sm-10">
                        <input type="text" name="jurusan_an<?php echo $ia;?>" class="form-control" placeholder="Masukkan Nama Jurusan">
                      </div>
                    </div>
                      <?php 
                    } 
                  }?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="col-sm-offset-10 col-sm-2">
                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                      </div>
                    </div>
                    <?php echo form_close();?>
                  </div>
                </div> 
              </div>
              <div class="tab-pane" id="numb">
                <?php echo form_open('kemp/edt_numb',array('class'=>'form-horizontal'));?>
                <div class="panel">
                  <div class="panel-heading bg-green"><i class="fa fa-phone"></i> Nomor Ponsel Penting</div>
                  <div class="panel-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor Ponsel Ayah</label>
                      <div class="col-sm-10">
                        <input type="number" name="no_hp_ayah" class="form-control" placeholder="Masukkan Nomor Ponsel Ayah" value="<?php echo $profile['no_hp_ayah'];?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor Ponsel Ibu</label>
                      <div class="col-sm-10">
                        <input type="number" name="no_hp_ibu" class="form-control" placeholder="Masukkan Nomor Ponsel Ibu" value="<?php echo $profile['no_hp_ibu'];?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor Ponsel Pasangan</label>
                      <div class="col-sm-10">
                        <input type="number" name="no_hp_pasangan" class="form-control" placeholder="Masukkan Nomor Ponsel Pasangan" value="<?php echo $profile['no_hp_pasangan'];?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Hubungi Orang Lain</label>
                      <div class="col-sm-10">
                        <label>Hubungi Orang Lain</label>
                        <input type="text" name="orang_lain" class="form-control" placeholder="Masukkan Nama" value="<?php echo $profile['hub_orang_lain'];?>">
                        <label>Nomor Ponsel Orang Lain</label>
                        <input type="number" name="no_hp_orang_lain" class="form-control" placeholder="Masukkan Nomor Ponsel Orang Lain" value="<?php echo $profile['no_hp_orang_lain'];?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="panel">
                  <div class="panel-heading bg-blue"><i class="fa fa-credit-card"></i> Nomor Lainnya</div>
                  <div class="panel-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor Rekening</label>
                      <div class="col-sm-10">
                        <input type="number" name="rekening" class="form-control" placeholder="Masukkan Nomor Rekening" value="<?php echo $profile['rekening'];?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Status Pajak</label>
                      <div class="col-sm-10">
                        <input type="text" name="status_pajak" class="form-control" placeholder="Masukkan Status Pajak" value="<?php echo $profile['status_pajak'];?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor NPWP</label>
                      <div class="col-sm-10">
                        <input type="number" name="npwp" class="form-control" placeholder="Masukkan Nomor NPWP" value="<?php echo $profile['npwp'];?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor BPJS-TK</label>
                      <div class="col-sm-10">
                        <input type="number" name="bpjstk" class="form-control" placeholder="Masukkan Nomor BPJS-TK" value="<?php echo $profile['bpjstk'];?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor BPJS-KES</label>
                      <div class="col-sm-10">
                        <input type="number" name="bpjskes" class="form-control" placeholder="Masukkan Nomor BPJS-KES" value="<?php echo $profile['bpjskes'];?>">
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-10 col-sm-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
                  </div>
                </div>
                <?php echo form_close();?>
              </div>
            </div>
          </div>
          
        </div>
        <?php 
              }
            }
          ?>
        <?php
        if (!empty($this->session->flashdata('msgres_fs')) || !empty($this->session->flashdata('msgres_fe')) || !empty($this->session->flashdata('foto_type')) || !empty($this->session->flashdata('foto_size')) || !empty($this->session->flashdata('foto_err')) || !empty($this->session->flashdata('foto_sc'))) {
          echo '<div class="tab-pane active" id="foto">';
        }else{
          echo '<div class="tab-pane" id="foto">';
        }
        ?>
        <div class="row">
          <div class="col-md-9">
            <?php  echo form_open_multipart('kemp/up_foto',array('class'=>'form-horizontal')); 
            ?>
            <p style="color:red;">File foto harus tipe *.jpg, *.png, *.jpeg, *.gif dan ukuran file foto maksimal 1 MB</p>
            <input id="uploadFile" placeholder="Nama Foto" disabled="disabled" class="form-control" required >
            <span class="input-group-btn">
              <div class="fileUpload btn btn-warning btn-flat">
                <span><i class="fa fa-folder-open"></i> Pilih Foto</span>
                <input id="uploadBtn" type="file" class="upload" name="image"/>
              </div>
              <button class="btn btn-info btn-flat" id="save" type="submit" disabled><i class="fa fa-check-circle"></i> Upload</button>
              
            </span>
            <div>
                <?php 
                echo form_close();
                echo form_open('kemp/res_foto');
                if ($profile['kelamin'] == 'Pria') {
                  if($profile['foto'] != 'asset/img/user-photo/user.png') {
                    echo '<input type="hidden" name="kelamin" value="l">
                    <input type="hidden" name="nik" value="'.$profile['nik'].'">
                    <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-rotate-right"></i> Reset Foto Default</button>';
                  }
                }else{
                  if($profile['foto'] != 'asset/img/user-photo/userf.png') {
                    echo '<input type="hidden" name="kelamin" value="p">
                    <input type="hidden" name="nik" value="'.$profile['nik'].'">
                    <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-rotate-right"></i> Reset Foto Default</button>';
                  }
                }
                echo form_close();
                ?>
              </div>
          </div>
        </div>
        
        </div>
          <?php
          if (!empty($this->session->flashdata('pass_same')) || !empty($this->session->flashdata('pass_nsame')) || !empty($this->session->flashdata('pass_sc')) || !empty($this->session->flashdata('pass_err'))) {
            echo '<div class="tab-pane active" id="pass">';
          }else{
            echo '<div class="tab-pane" id="pass">';
          }
          echo form_open('kemp/up_pass',array('class'=>'form-horizontal'));
          echo '<input type="hidden" name="nik" value="'.$profile['nik'].'">';
          ?>
          <div class="form-group">
            <label class="col-sm-3 control-label">Password Lama</label>

            <div class="col-sm-7">
              <input type="password" name="old_pass" class="form-control" placeholder="Masukkan Password Lama" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Password Baru</label>

            <div class="col-sm-7">
              <p style="color: red;">Password baru kamu tidak boleh sama dengan password lama</p>
              <input type="password" name="password1" id="pass1" class="form-control" placeholder="Password Baru" required onkeyup="checkPass(); return false;">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Ulangi Password Baru</label>
            <div class="col-sm-7">
              <input type="password" name="password2" id="pass2" class="form-control" placeholder="Ulangi Password Baru" required onkeyup="checkPass(); return false;">
              <span id="pesan"></span>
            </div>
          </div>
          <div class="form-group">
              <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" id="svedt" class="btn btn-success btn-flat"><i class="fa fa-floppy-o"></i> Simpan</button>
              </div>
            </div>
          <?php echo form_close();?>
        </div>
        <?php
        if (!empty($this->session->flashdata('dlog_sc')) || !empty($this->session->flashdata('dlog_err'))) {
          echo '<div class="tab-pane active" id="log">';
        }else{
          echo '<div class="tab-pane" id="log">';
        }
        if (empty($log)) {
          echo '<div class="callout callout-danger"><i class="fa fa-info-circle"></i> Riwayat Login Kamu Kosong</div>';
        }else{
          echo '
          <p style="color:red">Riwayat login kamu akan dihapus otomatis selama 6 bulan sekali</p>
          <div class="row">
            <div class="col-md-6">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th width="1%">No.</th>
                    <th width="25%">Tanggal Login</th>
                  </tr>
                </thead>
                <tbody>
                  ';
                  $cn=1;
                  foreach ($log as $lo) {
                  echo '<tr>
                          <td>'.$cn.'.</td>
                          <td>'.date("l, d F Y", strtotime($lo->tgl_login)).' <i style="color:red;" class="fa fa-clock-o"></i> '.date("H:i:s", strtotime($lo->tgl_login)).'</td>
                        </tr>';
                    $cn++;
                  }
                  echo '
                </tbody>
              </table>
              <a href="#delete_log" data-toggle="modal" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i> Hapus Riwayat Login</a>
            </div>
          </div>
          
          <div id="delete_log" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm modal-danger text-center">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Konfirmasi Hapus</h4>
                </div>
                <div class="modal-body">
                  <p>Kamu yakin akan menghapus semua data riwayat login ?</p>
                </div>
                <div class="modal-footer">';
                echo form_open('kemp/del_log');
                echo '
                  <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</a>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                  ';
                echo form_close();
                echo '</div>
              </div>
            </div>
          </div>';
        }
        ?>
      </div>
    </div>
  </div> 
</div>
</div>
</section>
</div>