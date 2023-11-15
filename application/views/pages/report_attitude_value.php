<?php $link=ucfirst($this->uri->segment(2));
$id=$this->uri->segment(4);
?> 
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-file-text"></i> Raport Sikap (360°)
      <small><?php echo $profile['nama'];?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url('pages/result_attitude_tasks');?>"><i class="fa fa-table"></i> Daftar Agenda</a></li>
      <li><a href="<?php echo base_url('pages/result_attitude_tasks_value/'.$agd['kode_agenda']);?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
      <li class="active">Raport <?php echo $profile['nama'];?></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url($profile['foto']); ?>" alt="User profile picture">

            <h3 class="profile-username text-center"><?php echo ucwords($profile['nama']); ?></h3>

            <p class="text-muted text-center"><?php 
            if ($jabatan == "") {
              echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
            }else{
             echo $jabatan; 
            }
            ?></p>

            <ul class="list-group list-group-unbordered"> 
              <li class="list-group-item">
                <b>Terdaftar Sejak</b> <label class="pull-right label label-primary"><?php echo $this->formatter->getDateMonthFormatUser($profile['tgl_masuk']); ?></label>
              </li>
              <li class="list-group-item">
                <b>Agenda</b> <label class="pull-right label label-success"><?php echo $agd['nama_agenda']; ?></label>
              </li>
              <li class="list-group-item">
                <b>Tahun</b> <label class="pull-right label label-info"><?php echo $agd['tahun'];?></label>
              </li>
              <li class="list-group-item">
                <b>Semester</b> <label class="pull-right label label-warning"><?php echo 'Semester '.$agd['semester']; ?></label>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab">Informasi Umum</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="info">
              <div class="row">
                <div class="col-md-12">
                  <table class='table table-bordered table-striped table-hover'>
                    <tr>
                      <th>Nama Lengkap</th>
                      <td><?php echo ucwords($profile['nama']);?></td>
                    </tr>
                    <tr>
                      <th>Email</th>
                      <td><?php 
                      if ($profile['email'] == "") {
                        echo '<label class="label label-danger">Email Tidak Ada</label>';
                      }else{
                        echo $profile['email'];
                      }
                       ?></td>
                    </tr>
                    <tr>
                      <th>Username</th>
                      <td><?php echo $profile['nik'];?></td>
                    </tr>
                    <tr>
                      <th>Jenis Kelamin</th>
                      <td><?php 
                      if($profile['kelamin'] == 'Pria'){
                        echo '<i class="fa fa-male" style="color:blue"></i> Laki-laki';
                      }else{
                        echo '<i class="fa fa-female" style="color:#ff00a5"></i> Perempuan';
                      }?></td>
                    </tr>
                    <tr> 
                      <th>Jabatan</th>
                      <td><?php 
                      if ($jabatan == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
                      }else{
                       echo $jabatan; 
                      }?></td>
                    </tr>   
                    <tr>
                      <th>Lokasi Kerja</th>
                      <td><?php 
                      if ($loker == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
                      }else{
                       echo $loker; 
                      }?></td>
                    </tr> 
                  </table>
                </div>
              </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-file-text-o text-red"></i> Rapor Nilai Sikap (360°)</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body" style="overflow: scroll;">
          <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Aspek Sikap untuk melihat keterangan lebih lengkap</div>
          <table class="table table-striped table-hover">
            <thead>
              <tr class="bg-green">
                <th>No.</th>
                <th>Nama Aspek Sikap</th>
                <th>Bobot</th>
                <?php
                  $pa=explode(';', $part);
                  foreach ($pa as $pe) {
                    if ($pe == "ATS") {
                      echo '<th colspan="2" class="text-center">Nilai Atasan<br>('.$b_ats.'%)</th>';
                    }
                    if ($pe == "RKN") {
                      echo '<th colspan="2" class="text-center">Nilai Rekan<br>('.$b_rkn.'%)</th>';
                    }
                    if ($pe == "BWH") {
                      echo '<th colspan="2" class="text-center">Nilai Bawahan<br>('.$b_bwh.'%)</th>';
                    }
                    /*
                    if ($pe == "DRI") {
                      echo '<th colspan="2" class="text-center">Nilai Sendiri<br>(0%)</th>';
                    }*/
                  }
                ?>
              </tr>
              <tr>
                <th class="bg-green" colspan="3"></th>
                
                <?php
                  foreach ($pa as $pe) {
                    if ($pe == "ATS") {
                      echo '<th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>';
                    }
                    if ($pe == "RKN") {
                      echo '<th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>';
                    }
                    if ($pe == "BWH") {
                      echo '<th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>';
                    }
                    /*
                    if ($pe == "DRI") {
                      echo '<th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>';
                    }*/
                  }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
                $n=1; 
                foreach ($asp as $a) {
                  $dt=$this->db->get_where('master_aspek_sikap',array('kode_aspek'=>$a))->row_array();
                  $bb=implode('',array_unique($bobot[$a]));
                  $bbt=($bb/100);

                  $n1=1;
                  foreach ($rt_ats[$a] as $aa) {
                    if ($aa != 0) {
                      $sa[$n1]=$aa;
                    }
                    $n1++;
                  }
                  $n2=1;
                  foreach ($rt_bwh[$a] as $ab) {
                    if ($ab != 0) {
                      $sb[$n2]=$ab;
                    }
                    $n2++;
                  }
                  $n3=1;
                  foreach ($rt_rkn[$a] as $ar) {
                    if ($ar != 0) {
                      $sr[$n3]=$ar;
                    }
                    $n3++;
                  }
                  $n4=1;
                  foreach ($n_dri[$a] as $ad) {
                    if ($ad != 0) {
                      $sd[$n4]=$ad;
                    }
                    $n4++;
                  }
                  if (isset($sa)) {
                    $r_atas=array_sum($rt_ats[$a])/count($sa);
                    $atas[$n]=$r_atas*$bbt;

                  }else{
                    $atas[$n]=0;
                    $r_atas=0;
                  }
                  if (isset($sb)) {
                    $r_bawah=array_sum($rt_bwh[$a])/count($sb);
                    $bawah[$n]=$r_bawah*$bbt;
                  }else{
                    $bawah[$n]=0;
                    $r_bawah=0;
                  }
                  if (isset($sr)) {
                    $r_rekan=array_sum($rt_rkn[$a])/count($sr);
                    $rekan[$n]=$r_rekan*$bbt;

                  }else{
                    $rekan[$n]=0;
                    $r_rekan=0;
                  }
                  if (isset($sd)) {
                    $r_diri=array_sum($n_dri[$a])/count($sd);
                    $diri[$n]=$r_diri*$bbt;

                  }else{
                    $diri[$n]=0;
                    $r_diri=0;
                  }
                  
                  echo '<tr>
                    <td>'.$n.'.</td>
                    <td><a href="'.base_url('pages/report_detail_attitude/'.$agd['kode_agenda'].'/'.$a.'/'.$id).'" data-toggle="tooltip" title="Klik Untuk Detail">'.$dt['nama_aspek'].'</a></td>
                    <td>'.$bb.'%</td>';
                    foreach ($pa as $pe1) {
                      if ($pe1 == "ATS") {
                        echo '<td class="text-center">'.number_format($r_atas,2,',',',').'</td>
                        <td class="text-center bg-warning">'.number_format($atas[$n],2,',',',').'</td>';
                        $na[$a][]=$atas[$n];
                      }
                      if ($pe1 == "RKN") {
                        echo '<td class="text-center">'.number_format($r_rekan,2,',',',').'</td>
                        <td class="text-center bg-warning">'.number_format($rekan[$n],2,',',',').'</td>';
                        $na[$a][]=$rekan[$n];
                      }
                      if ($pe1 == "BWH") {
                        echo '<td class="text-center">'.number_format($r_bawah,2,',',',').'</td>
                        <td class="text-center bg-warning">'.number_format($bawah[$n],2,',',',').'</td>';
                        $na[$a][]=$bawah[$n];
                      }
                      /*
                      if ($pe1 == "DRI") {
                        echo '<td class="text-center">'.number_format($r_diri,2,',',',').'</td>
                        <td class="text-center bg-warning">'.number_format($diri[$n],2,',',',').'</td>';
                      }*/
                    }
                    $s_na=array_sum($na[$a]);
                  echo '
                  </tr>';
                  $n++;
                }
                echo '<tr>
                  <td colspan="3" class="text-center bg-aqua"><b>Nilai Total</b></td>';
                  foreach ($pa as $pe2) {
                    if ($pe2 == "ATS") {
                      $s_ats1=array_sum($atas);
                      echo '<td class="text-center bg-aqua"></td>
                      <td class="text-center bg-warning"><b>'.number_format($s_ats1,2,',',',').'</b></td>';
                    }
                    if ($pe2 == "RKN") {
                      $s_rkn1=array_sum($rekan);
                      echo '<td class="text-center bg-aqua"></td>
                      <td class="text-center bg-warning"><b>'.number_format($s_rkn1,2,',',',').'</b></td>';
                    }
                    if ($pe2 == "BWH") {
                      $s_bwh1=array_sum($bawah);
                      echo '<td class="text-center bg-aqua"></td>
                      <td class="text-center bg-warning"><b>'.number_format($s_bwh1,2,',',',').'</b></td>';
                    }
                    /*
                    if ($pe2 == "DRI") {
                      $s_dri1=array_sum($diri);
                      echo '<td class="text-center bg-aqua"></td>
                      <td class="text-center bg-warning"><b>'.number_format($s_dri1,2,',',',').'</b></td>';
                    }*/
                  }
                  echo '
                </tr>
                <tr>
                  <td colspan="3" class="text-center bg-aqua"></td>';
                  foreach ($pa as $pe2) {
                    if ($pe2 == "ATS") {
                      $s_ats=array_sum($atas)*($b_ats/100);
                      echo '<td class="text-center bg-navy">Nilai x '.$b_ats.'%</td>
                      <td class="text-center bg-yellow"><b>'.number_format($s_ats,2,',',',').'</b></td>';
                      $nb[]=$s_ats;
                    }
                    if ($pe2 == "RKN") {
                      $s_rkn=array_sum($rekan)*($b_rkn/100);
                      echo '<td class="text-center bg-navy">Nilai x '.$b_rkn.'%</td>
                      <td class="text-center bg-yellow"><b>'.number_format($s_rkn,2,',',',').'</b></td>';
                      $nb[]=$s_rkn;
                    }
                    if ($pe2 == "BWH") {
                      $s_bwh=array_sum($bawah)*($b_bwh/100);
                      echo '<td class="text-center bg-navy">Nilai x '.$b_bwh.'%</td>
                      <td class="text-center bg-yellow"><b>'.number_format($s_bwh,2,',',',').'</b></td>';
                      $nb[]=$s_bwh;
                    }
                    /*
                    if ($pe2 == "DRI") {
                      $s_dri=array_sum($diri)*0;
                      echo '<td class="text-center bg-navy">Nilai x 0%</td>
                      <td class="text-center bg-yellow"><b>'.number_format($s_dri,2,',',',').'</b></td>';
                    }*/
                  }
                echo '
                
                </tr>
                <tr>
                <td style="font-size:16pt" class="bg-blue text-center" ';
                  $col=2;
                  foreach ($pa as $pe3) {
                    if ($pe3 == "ATS") {
                      $col=$col+2;
                    }
                    if ($pe3 == "BWH") {
                      $col=$col+2;
                    }
                    if ($pe3 == "RKN") {
                      $col=$col+2;
                    }
                    /*
                    if ($pe3 == "DRI") {
                      $col=$col+2;
                    }*/
                  }
                  echo 'colspan="'.$col.'"><b>Nilai Akhir</b></td><td class="text-center bg-red" style="font-size:16pt">'.number_format(array_sum($nb),2,',',',').'</td>
                </tr>';
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
</div>