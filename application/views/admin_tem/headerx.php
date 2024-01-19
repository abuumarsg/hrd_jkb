<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?php echo $this->otherfunctions->companyProfile()['meta_description'];?>">
  <meta name="author" content="<?php echo $this->otherfunctions->companyProfile()['meta_author'];?>">
  <title><?php 
  echo $this->otherfunctions->titlePages($this->uri->segment(2));
  ?> HRD Management System HSOFT </title>
  <meta name="theme-color" content="#131c5b">
  <link rel="icon" href="<?php echo base_url('asset/img/favicon.png');?>" type="image/png">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/vendor/fontawesome5/css/all.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/Ionicons/css/ionicons.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/sweetalert2/dist/sweetalert2.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/AdminLTE.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/iCheck/square/blue.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/viewerjs/dist/viewer.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/timepicker/bootstrap-timepicker.min.css');?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/morris.js/morris.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/jvectormap/jquery-jvectormap.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-daterangepicker/daterangepicker.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/datatables.net-bs/css/dataTables.bootstrap.css');?>">
  <link rel="stylesheet" href=" https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.bootstrap.min.css">
 
  <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/skins/_all-skins.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/select2/dist/css/select2.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/plugins/pace/pace.css');?>">
  <link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
  <link href="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.css');?>" rel="stylesheet" media="all">
  <link href="<?php echo base_url('asset/vendor/emoji/dist/emoji.min.css');?>" rel="stylesheet" media="all">
  <link href="<?php echo base_url('asset/vendor/toastr/toastr.min.css');?>" rel="stylesheet" media="all">
  <link href="<?php echo base_url('asset/vendor/iconpicker/dist/css/fontawesome-iconpicker.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('asset/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('asset/plugins/JsTree/dist/themes/default/style.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('asset/dist/css/animated.css');?>" rel="stylesheet">
  <style type="text/css">
    .fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
  }
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
.loader{
  margin-top: -20px;
  margin-bottom: 50px;
}
.loader span{
  width:30px;
  height:30px;
  border-radius:50%;
  display:inline-block;
  position:absolute;
  left:50%;
  margin-left:-10px;
  -webkit-animation:3s infinite linear;
  -moz-animation:3s infinite linear;
  -o-animation:3s infinite linear;
  
}
.toast-top-center {top: 15%;margin: 0 auto;}

.loader span:nth-child(2){
  background:#009dff;
  -webkit-animation:kiri 2s infinite linear;
  -moz-animation:kiri 2s infinite linear;
  -o-animation:kiri 2s infinite linear;
  
}
.loader span:nth-child(3){
  background:#F1C40F;
  z-index:100;
}
.loader span:nth-child(4){
  background:#2FCC71;
  -webkit-animation:kanan 2s infinite linear;
  -moz-animation:kanan 2s infinite linear;
  -o-animation:kanan 2s infinite linear;
}


@-webkit-keyframes kanan {
    0% {-webkit-transform:translateX(40px);
    }
   
  50%{-webkit-transform:translateX(-40px);
  }
  
  100%{-webkit-transform:translateX(40px);
  z-index:200;
  }
}
@-moz-keyframes kanan {
    0% {-moz-transform:translateX(40px);
    }
   
  50%{-moz-transform:translateX(-40px);
  }
  
  100%{-moz-transform:translateX(40px);
  z-index:200;
  }
}
@-o-keyframes kanan {
    0% {-o-transform:translateX(40px);
    }
   
  50%{-o-transform:translateX(-40px);
  }
  
  100%{-o-transform:translateX(40px);
  z-index:200;
  }
}




@-webkit-keyframes kiri {
     0% {-webkit-transform:translateX(-40px);
  z-index:200;
    }
  50%{-webkit-transform:translateX(40px);
  }
  100%{-webkit-transform:translateX(-40px);
  }
}

@-moz-keyframes kiri {
     0% {-moz-transform:translateX(-40px);
  z-index:200;
    }
  50%{-moz-transform:translateX(40px);
  }
  100%{-moz-transform:translateX(-40px);
  }
}
@-o-keyframes kiri {
     0% {-o-transform:translateX(-40px);
  z-index:200;
    }
  50%{-o-transform:translateX(40px);
  }
  100%{-o-transform:translateX(-40px);
  }
}
  </style>
</head>
<body class="hold-transition <?php echo $adm['skin'];?> sidebar-mini fixed" 
onload="set_interval()"
onmousemove="reset_interval()"
onclick="reset_interval()"
onkeypress="reset_interval()"
onscroll="reset_interval()">
  <div class="modal fade" id="swprog" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content text-center modal-danger">
        <div class="modal-header">
          <h4 class="modal-title">Progress Loading</h4>
        </div>
        <div class="modal-body">
          <div class="loader">
            <h1><label style="font-size: 25pt;" id="minutes">00</label><label style="font-size: 25pt;">:</label><label style="font-size: 25pt;" id="seconds">00</label></h1>
            <span></span>
            <span></span>
            <span></span>
          </div>
        <b style="color: yellow;">Jangan Refresh Halaman Ini</b>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="about_apps" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content text-center">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title">Tentang <b class="text-muted" style="font-size: 12pt">Aplikasi</b></h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <img class="img-responsive img profile-user-img" style="width: 200px"  src="<?php echo $this->otherfunctions->companyProfile()['logo']; ?>" alt="">
              <p class="text-muted">Software Version <?php echo $this->otherfunctions->companyProfile()['version']; ?></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <h2><b><?php echo $this->otherfunctions->companyProfile()['name']; ?></b></h2>
              <p>
                <?php echo $this->otherfunctions->companyProfile()['description']; ?>
                <br>
                <br>
                <i class="fas fa-map-marker-alt"></i> <?php echo $this->otherfunctions->companyProfile()['address']; ?> <br> <i class="fas fa-phone"></i> <?php echo $this->otherfunctions->companyProfile()['call']; ?> <i class="fas fa-at"></i> <a href="mailto:<?php echo $this->otherfunctions->companyProfile()['email']; ?>"><?php echo $this->otherfunctions->companyProfile()['email']; ?></a> <i class="fab fa-internet-explorer"></i> <a href="<?php echo $this->otherfunctions->companyProfile()['website']; ?>"><?php echo $this->otherfunctions->companyProfile()['website']; ?></a>
              </p>
              <div class="row">
                <div class="col-md-12">
                  <?php echo $this->otherfunctions->companyProfile()['maps']; ?>
                </div>
              </div>
              <small>Copyright &copy; 2017 - <?php echo date('Y').' <a href="'.$this->otherfunctions->companyProfile()['website'].'">'.$this->otherfunctions->companyProfile()['name_office'].'</a> - All rights reserved.';?></small>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
  <div class="wrapper">
    <header class="main-header">
      <a href="#" class="logo">
        <span class="logo-mini fnt"><b><img src="<?php echo base_url('asset/img/hsoftl.png');?>" width="40px"></b></span>
        <span class="logo-lg fnt"><img src="<?php echo base_url('asset/img/hsoftl.png');?>" width="40px"><b> HSOFT</b></span>
      </a>
      <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <!-- <ul class="nav navbar-nav">
        </ul> -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
          <?php if (in_array($adm['access']['l_ac']['tidak_absen_masuk'], $adm['access']['access'])) { ?>
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Tidak Absen Masuk" onclick="refreshNotAbsenIn()">
                <i class="fas fa-user-tag"></i>
                <span class="badge" style="background-color: red" id="count_NotAbsenIn"></span>
              </a>
              <ul class="dropdown-menu" style="max-height: 300px;overflow: auto; width: 440px" id="value_NotAbsenIn">
              </ul>
            </li>
          <?php } ?>
          <?php if (in_array($adm['access']['l_ac']['notif_izin'], $adm['access']['access'])) { ?>
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Izin Cuti Belum Divalidasi" onclick="refreshIzinCuti()">
                <i class="fas fa-calendar-times"></i>
                <span class="badge" style="background-color: red" id="count_IzinCuti"></span>
              </a>
              <ul class="dropdown-menu" style="max-height: 300px;overflow: auto; width: 440px" id="value_IzinCuti">
              </ul>
            </li>
          <?php } ?>
          <?php if (in_array($adm['access']['l_ac']['notif_alpa'], $adm['access']['access'])) { ?>
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Alpa 3 Hari Berturut-turut" onclick="refreshAlpa()">
                <i class="fas fa-user-times"></i>
                <span class="badge" style="background-color: red" id="count_Alpa"></span>
              </a>
              <ul class="dropdown-menu" style="max-height: 300px;overflow: auto; width: 440px" id="value_Alpa">
              </ul>
            </li>
          <?php } ?>
          <?php if (in_array($adm['access']['l_ac']['notif_kuota_lembur'], $adm['access']['access'])) { ?>
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Kuota Lembur" onclick="refreshKuotaLembur()">
                <i class="fas fa-clock"></i>
                <span class="badge" style="background-color: red" id="count_KuotaLembur"></span>
              </a>
              <ul class="dropdown-menu" style="max-height: 300px;overflow: auto; width: 440px" id="value_KuotaLembur">
              </ul>
            </li>
          <?php } ?>
          <?php if (in_array($adm['access']['l_ac']['notif_perjanjian'], $adm['access']['access'])) { ?>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Perjanjian Kerja" onclick="refreshAgree()">
              <i class="fas fa-user-cog"></i>
              <span class="badge" style="background-color: red" id="count_agreement_end"></span>
            </a>
            <ul class="dropdown-menu" style="max-height: 300px;overflow: auto; width: 440px" id="value_agreement_end">
            </ul>
          </li>
          <?php } ?>
          <?php if (in_array($adm['access']['l_ac']['notif_lembur'], $adm['access']['access'])) { ?>
            <input type="hidden" id="id_lembur" value="1">
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Notifikasi Lembur" onclick="refreshLembur()">
              <i class="fa fas fa-calendar-plus fa-fw"></i>
              <span class="badge" style="background-color: red" id="count_lembur_end"></span>
            </a>
            <ul class="dropdown-menu" style="max-height: 300px;overflow: auto; width: 440px" id="value_lembur_end">
            </ul>
          </li>
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Pesan" onclick="refreshMsg()">
                <i class="fas fa-envelope"></i>
                <span class="badge" style="background-color: red" id="count_message_unread"></span>
              </a>
              <ul class="dropdown-menu" style="max-height: 300px;overflow: auto; width: 440px" id="value_message_unread">
              </ul>
            </li>
           <?php
            if ($this->uri->segment(2) == "read_all_notification" || $this->uri->segment(2) == "read_notification" ) {
              echo '<li class="dropdown notifications-menu active" >';
            }else{
              echo '<li class="dropdown notifications-menu">';
            }
            ?>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php
                if (count($adm['notif']) > 0) {
                  echo '<i class="far fa-bell faa-ring animated"></i><span class="label" style="color: red;"><i class="fa fa-circle"></i></span>';
                }else{
                  echo '<i class="far fa-bell"></i>';
                }
              ?>
            </a>
            <ul class="dropdown-menu">
              <?php if (count($adm['notif']) > 0) { ?>
              <li class="header"><?php echo 'Kamu Punya '.count($adm['notif']).' Notifikasi';?></li>
              <li>
                <ul class="menu">
                  <?php 
                    foreach ($adm['notif'] as $not) {
                      if ($not['sifat'] == "1") {
                        echo'<li data-toggle="tooltip" title="Penting">';
                      }else{
                        echo '<li>';
                      }
                      echo '<a href="'.base_url('pages/read_notification/'.$not['kode']).'" title="'.$not['judul'].'">';
                          if ($not['tipe'] == "info") {
                            echo '<i class="fa fa-bullhorn text-aqua"></i> ';
                          }elseif ($not['tipe'] == "warning") {
                            echo '<i class="fa fa-warning text-yellow"></i> ';
                          }else{
                            echo '<i class="fa fa-times-circle text-red"></i> ';
                          } 
                          echo '<b>'.$not['judul'].'</b>';
                          if ($not['sifat'] == "1") {
                            echo '<span class="label pull-right" style="color: red;"><i class="fa fa-dot-circle-o"></i></span>';
                          }
                        echo '</a></li>';
                    }
                  ?>
                </ul>
              </li>
              <?php }else{ echo '<li class="header">Tidak Ada Notifikasi</li>';}?>
              <li class="footer"><a href="<?php echo base_url('pages/read_all_notification');?>">Tampilkan Semua</a></li>
            </ul>
          </li>
          <?php }else{
            echo '<input type="hidden" id="id_lembur" value="0">';
          } ?>
            <li>
              <a class="tgl" id="date_time" style="font-size: 9pt"></a>
            </li>
            <?php
              if ($this->uri->segment(2) == "profile") {
                echo '<li class="dropdown user user-menu active">';
              }else{
                echo '<li class="dropdown user user-menu">';
              }
            ?>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url($adm['foto']);?>" class="user-image" alt="User Image">
            <span class="hidden-xs"><?php echo $adm['nama'];?></span>
          </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">
            <img src="<?php echo base_url($adm['foto']);?>" class="img-circle" alt="User Image">

            <p>
              <?php echo $adm['nama'];?>
              <small>Terdaftar Sejak <?php echo date("d F Y", strtotime($adm['create']));?></small>
            </p>
          </li>
          <li class="user-body">
            <div class="row">
              <div class="col-xs-12 text-center">
                <a href="<?php echo base_url('auth/lock');?>" class="btn btn-info btn-block"><i class="fa fa-lock"></i> Lock Akun</a>
              </div>
            </div>
            <!-- /.row -->
          </li>
          <li class="user-footer">
            <div class="pull-left">
              <a href="<?php echo base_url('pages/profile');?>" class="btn  btn-success"><i class="fa fa-user"></i> Profile</a>
            </div>

            <div class="pull-right">
              <a href="<?php echo base_url('auth/logout');?>" class="btn  btn-danger">Log Out <i class="fas fa-sign-out-alt"></i></a>
            </div>
          </li>
        </ul>
      </li>
      <?php
        $lkn=$this->uri->segment(2);
        if ($lkn == "setting_bobot" || $lkn == "setting_konversi" || $lkn == "setting_update" || $lkn == "setting_access" || $lkn == "setting_menu" || $lkn == "setting_admin" || $lkn == "setting_notifikasi" || $lkn == "setting_root_password" || $lkn == "setting_management_perusahaan" || $lkn == "general_setting" || $lkn == "hari_kerja_wfh") {
          echo '<li class="dropdown notifications-menu active">';
        }else{
          echo '<li class="dropdown notifications-menu">';
        }
        ?>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-gears"></i>
            </a>
            <ul class="dropdown-menu">
              <li class="header"><b><i class="fa fa-gear"></i> Setting Aplikasi</b></li>
              <li>
                <ul class="menu">
                <?php if ($adm['level'] == 0 ||$adm['level'] == 1) { ?>
                  <?php
                  if ($lkn == "general_setting") {
                    echo '<li class="active" title="General Setting">';
                  }else{
                    echo '<li title="General Setting">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/general_setting');?>">
                      <i class="fas fa-cogs"></i> General Setting
                    </a>
                  </li>
                  <?php
                  if ($lkn == "hari_kerja_wfh") {
                    echo '<li class="active" title="Setting Hari Kerja WFH">';
                  }else{
                    echo '<li title="Setting Hari Kerja WFH">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/hari_kerja_wfh');?>">
                      <i class="fas fa-briefcase"></i> Setting Hari Kerja WFH
                    </a>
                  </li>
                  <?php
                  if ($lkn == "setting_bobot") {
                    echo '<li class="active" title="Setting Bobot Sikap" >';
                  }else{
                    echo '<li title="Setting Bobot Sikap">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/setting_bobot');?>">
                      <i class="fa fa-balance-scale"></i> Bobot Sikap
                    </a>
                  </li>

                  <?php
                  if ($lkn == "setting_konversi") {
                    echo '<li class="active" title="Setting Konversi Nilai" >';
                  }else{
                    echo '<li title="Setting Konversi">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/setting_konversi');?>">
                      <i class="fa fa-filter"></i> Konversi Nilai
                    </a>
                  </li>

                  <?php
                  if ($lkn == "setting_update") {
                    echo '<li class="active" title="Setting Tanggal Update Data Karyawan">';
                  }else{
                    echo '<li title="Setting Tanggal Update Data Karyawan">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/setting_update');?>">
                      <i class="fa fa-calendar-check-o"></i> Tanggal Update Data Karyawan
                    </a>
                  </li>

                  <?php
                  if ($adm['level'] == 0) {
                    if ($lkn == "setting_access") {
                      echo '<li class="active" title="Setting Hak Akses">';
                    }else{
                      echo '<li title="Setting Hak Akses">';
                    }
                  ?>
                    <a href="<?php echo base_url('pages/setting_access');?>">
                      <i class="fa fa-lock"></i> Hak Akses
                    </a>
                  </li>

                  <?php
                  }
                  if ($lkn == "setting_user_group") {
                    echo '<li class="active" title="Setting User Group">';
                  }else{
                    echo '<li title="Setting User Group">';
                  }
                  ?>
                  
                    <a href="<?php echo base_url('pages/setting_user_group');?>">
                      <i class="fa fa-users"></i> User Group
                    </a>
                  </li>

                  <?php
                  if ($adm['level'] == 0) {
                    if ($lkn == "setting_menu") {
                      echo '<li class="active" title="Setting Manajemen Menu">';
                    }else{
                      echo '<li title="Setting Manajemen Menu">';
                    }
                  ?>
                    <a href="<?php echo base_url('pages/setting_menu');?>">
                      <i class="fa fa-navicon"></i> Manajemen Menu
                    </a>
                  </li>

                  <?php
                  }
                  if ($lkn == "setting_admin") {
                    echo '<li class="active" title="Setting Manajemen Admin">';
                  }else{
                    echo '<li title="Setting Manajemen Admin">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/setting_admin');?>">
                      <i class="fa fa-user-secret"></i> Manajemen Admin
                    </a>
                  </li>

                  <?php
                  if ($lkn == "setting_notifikasi") {
                    echo '<li class="active" title="Setting Manajemen Notifikasi">';
                  }else{
                    echo '<li title="Setting Manajemen Notifikasi">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/setting_notifikasi');?>">
                      <i class="far fa-bell"></i> Manajemen Notifikasi
                    </a>
                  </li>
                  <?php
                  if (in_array($adm['access']['l_ac']['root_password'], $adm['access']['access'])) {
                    if ($lkn == "setting_root_password") {
                      echo '<li class="active" title="Setting Password">';
                    }else{
                      echo '<li title="Setting Password">';
                    }
                    ?>
                      <a href="<?php echo base_url('pages/setting_root_password');?>">
                        <i class="fa fa-key"></i> Setting Password
                      </a>
                    </li>
                   <?php } ?>
                  <?php
                    // if ($lkn == "setting_cuti") {
                    //   echo '<li class="active" title="Setting Cuti Karyawan">';
                    // }else{
                    //   echo '<li title="Setting Cuti Karyawan">';
                    // }
                    //   echo '<a href="'.base_url('pages/setting_cuti').'">
                    //     <i class="fas fa-wrench"></i> &nbsp;&nbsp;Setting Cuti Karyawan
                    //   </a>
                    // </li>';
                  ?>

                  <?php
                  if ($lkn == "setting_management_perusahaan") {
                    echo '<li class="active" title="Management Perusahaan">';
                  }else{
                    echo '<li title="Management Perusahaan">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/setting_management_perusahaan');?>">
                      <i class="fas fa-university"></i> Management Perusahaan
                    </a>
                  </li>
                  <?php
                  if ($lkn == "backup_restore_database") {
                    echo '<li class="active" title="Backup Restore Database">';
                  }else{
                    echo '<li title="Backup Restore Database">';
                  }
                  ?>
                    <a href="<?php echo base_url('pages/backup_restore_database');?>">
                      <i class="fas fa-refresh"></i> Backup Restore Database
                    </a>
                  </li>
                <?php } ?>
                  <?php 
                    $dt_sx=$adm['skin'];
                    if ($dt_sx == 'skin-blue') {
                      $dt_s='dark-mode';
                      $namex='<i class="fas fa-moon"></i> Dark Mode ';
                    }else{
                      $dt_s='skin-blue';
                      $namex='<i class="fas fa-sun"></i> Normal Mode ';
                    }
                  ?>
                  <li><a href="#" data-skin="<?php echo $dt_s; ?>" id="skinX"><?php echo $namex; ?></a>
                      </li>
                  <li><a href="#about_apps" data-toggle="modal"><i class="fa fa-info-circle"></i> Tentang Aplikasi</a></li>
                </ul>
              </li>
            </ul>
          </li>
      </li>
    </ul>
  </div>
</nav>
</header>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
    // var lembur = 
    var count_agreement_end=$('#count_agreement_end');    
    var value_agreement_end=$('#value_agreement_end');
    var count_lembur_end=$('#id_lembur').val();
    $(document).ready(function() {
      refreshAgree();refreshMsg();refreshKuotaLembur();refreshAlpa();refreshIzinCuti();
      // alert(count_lembur_end);
      if(count_lembur_end == 1){
        refreshLembur();
      }
    });
    function refreshAlpa() {
        var callback=getAjaxData('<?php echo base_url('presensi/data_presensi/getAlpa3times'); ?>',null);
        if (callback['count'] > 0) {
            $('#count_Alpa').html(callback['count']);
        }else{
            $('#count_Alpa').html('');
        }
        $('#value_Alpa').html(callback['value']);
    }
    function refreshNotAbsenIn() {
        var callback=getAjaxData('<?php echo base_url('presensi/data_presensi/getNotAbsenIn'); ?>',null);
        if (callback['count'] > 0) {
            $('#count_NotAbsenIn').html(callback['count']);
        }else{
            $('#count_NotAbsenIn').html('');
        }
        $('#value_NotAbsenIn').html(callback['value']);
    }
    function refreshIzinCuti() {
        var callback=getAjaxData('<?php echo base_url('presensi/izin_cuti_harian/notYetValidate'); ?>',null);
        if (callback['count'] > 0) {
            $('#count_IzinCuti').html(callback['count']);
        }else{
            $('#count_IzinCuti').html('');
        }
        $('#value_IzinCuti').html(callback['value']);
    }
    function refreshKuotaLembur() {
        var callback=getAjaxData('<?php echo base_url('master/master_kuota_lembur/kuota_lembur'); ?>',null);
        if (callback['count'] > 0) {
            $('#count_KuotaLembur').html(callback['count']);
        }else{
            $('#count_KuotaLembur').html('');
        }
        $('#value_KuotaLembur').html(callback['value']);
    }
    function refreshAgree() {
        var callback=getAjaxData('<?php echo base_url('employee/perjanjian_kerja/getagreementend'); ?>',null);
        if (callback['count'] > 0) {
            $('#count_agreement_end').html(callback['count']); 
        }else{
            $('#count_agreement_end').html('');
        }
        $('#value_agreement_end').html(callback['value']);
    }
    function refreshLembur() {
        var cbx=getAjaxData('<?php echo base_url('presensi/data_lembur_trans/getLemburend'); ?>',null);
        if (cbx['count'] > 0) {
            $('#count_lembur_end').html(cbx['count']); 
        }else{
            $('#count_lembur_end').html('');
        }
        $('#value_lembur_end').html(cbx['value']);
    }
    function refreshMsg() {
        var callback=getAjaxData('<?php echo base_url('employee/data_pesan/getmessageunread'); ?>',null);
        if (callback['count'] > 0) {
            $('#count_message_unread').html(callback['count']); 
        }else{
            $('#count_message_unread').html('');
        }
        $('#value_message_unread').html(callback['value']);
    }
</script>