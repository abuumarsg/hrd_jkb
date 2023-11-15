<?php $link=ucfirst($this->uri->segment(2));
$id=$this->uri->segment(4);
?> 
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-file-text"></i> Raport
      <small><?php echo $profile['nama'];?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url('pages/result_tasks');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
      <li><a href="<?php echo base_url('pages/result_tasks_value/'.$agd['kode_agenda']);?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
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
            if ($jabatan['jabatan'] == "") {
              echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
            }else{
             echo $jabatan['jabatan']; 
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
                <b>Tahun</b> <label class="pull-right label label-primary"><?php echo $agd['tahun'];?></label>
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
                      if ($jabatan['jabatan'] == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
                      }else{
                       echo $jabatan['jabatan']; 
                      }?></td>
                    </tr>   
                    <tr>
                      <th>Unit Kerja</th>
                      <td><?php 
                      if ($loker['nama'] == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
                      }else{
                       echo $loker['nama']; 
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
      <?php 
      foreach ($hasil as $hs) {
        $jo[]=$hs->id_jabatan;
        $lo[]=$hs->id_loker;
      }
      $jo2=array_unique($jo);
      $lo2=array_unique($lo);
      $jo1=array_values($jo2);
      $lo1=array_values($lo2);
      if (count($jo1) > count($lo1)) {
        foreach ($lo1 as $lo2) {
          array_push($lo1, $lo2);
        }
      }
      if(count($jo1) < count($lo1)){
        foreach ($jo1 as $jo2) {
          array_push($jo1, $jo2);
        }
      }
        $no=1;
        foreach ($jo1 as $k => $ja) {
            $jabat=$this->db->get_where('master_jabatan',array('id_jabatan'=>$ja))->row_array();
            $loker=$this->db->get_where('master_loker',array('id_loker'=>$lo1[$k]))->row_array();
            $dtd=$this->db->get_where($nmtb,array('id_karyawan'=>$id))->row_array();
            $kode=$dtd['kode_agenda'];
            //$hasil2=$this->db->get_where($nmtb,array('id_jabatan'=>$ja,'id_loker'=>$lo1[$k],'id_karyawan'=>$id))->result();
            $hasil2=$this->db->query("SELECT * FROM $nmtb WHERE id_jabatan = '$ja' AND id_loker = '$lo1[$k]' AND id_karyawan = '$id' ORDER BY urutan ASC")->result();
      ?>

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-file-text-o text-red"></i> Rapor Nilai Output (Target)</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body" style="overflow: scroll;">
          <?php 

            if (count($jo1) > 1) {
                    echo '<div class="col-md-4"></div>
                    <div class="col-md-4">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th class="bg-red">Jabatan</th>
                          <th class="bg-yellow">Lokasi Kerja</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>'.$jabat['jabatan'].'</td>
                          <td>'.$loker['nama'].'</td>
                        </tr>
                      </tbody> 
                    </table>
                    </div>';
                  }
          ?>
          <table id="example2" class="table table-striped table-hover">
            <thead>
              <tr class="bg-green">
                <th>No.</th>
                <th>Indikator</th>
                <th>Bobot</th>
                <th>Penilai</th>
                <th>Sifat</th>
                <th class="text-center">Target</th>
                <th class="text-center">Realisasi</th>
                <?php
                if ($jabat['kode_periode'] == "BLN") {
                  if ($agd['semester'] == 1) {
                    echo '<th class="text-center" colspan="2">Januari</th>
                    <th class="text-center" colspan="2">Februari</th>
                    <th class="text-center" colspan="2">Maret</th>
                    <th class="text-center" colspan="2">April</th>
                    <th class="text-center" colspan="2">Mei</th>
                    <th class="text-center" colspan="2">Juni</th>
                    ';
                  }else{
                    echo '<th class="text-center" colspan="2">Juli</th>
                    <th class="text-center" colspan="2">Agustus</th>
                    <th class="text-center" colspan="2">September</th>
                    <th class="text-center" colspan="2">Oktober</th>
                    <th class="text-center" colspan="2">November</th>
                    <th class="text-center" colspan="2">Desember</th>
                    ';
                  }
                }elseif ($jabat['kode_periode'] == "SMT") {
                  if ($agd['semester'] == 1) {
                    echo '<th class="text-center" colspan="2">Januari - Juni</th>';
                  }else{
                    echo '<th class="text-center" colspan="2">Juli - Desember</th>';
                  }

                }                
                ?>
                <th>Nilai Akhir</th>
              </tr>
              <tr>
                <th class="bg-green" colspan="7"></th>
                <?php 
                if ($jabat['kode_periode'] == "BLN") {
                  echo '<th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>
                        <th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>
                        <th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>
                        <th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>
                        <th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>
                        <th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>';
                }elseif ($jabat['kode_periode'] == "SMT") {
                  echo '<th class="bg-blue text-center">Capaian</th>
                        <th class="bg-yellow text-center">Nilai</th>';
                }    
                ?>
                <th class="bg-green"></th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $n=1;
                $n1[$no]=array();
                $n2[$no]=array();
                $n3[$no]=array();
                $n4[$no]=array();
                $n5[$no]=array();
                $n6[$no]=array();
                $ni[$no]=array();
                foreach ($hasil2 as $h) {
                  array_push($ni[$no], $h->nilai_out);
                  array_push($n1[$no], $h->na1);
                  array_push($n2[$no], $h->na2);
                  array_push($n3[$no], $h->na3);
                  array_push($n4[$no], $h->na4);
                  array_push($n5[$no], $h->na5);
                  array_push($n6[$no], $h->na6);
                  echo '<tr>
                          <td>'.$n.'.</td>
                          <td>'.$h->indikator;
                          if ($h->kaitan == '1') {
                            echo '<br><label class="label label-success">Terkait Data Penunjang</label>';
                          }
                          $pre=$this->db->get_where('dp_presensi',array('kode_agenda'=>$agd['kode_agenda']))->row_array();
                          if ($pre != "") {
                            $dpre=$this->db->get_where($pre['nama_tabel'],array('id_karyawan'=>$h->id_karyawan))->row_array();
                            if ($dpre != "" && $h->kode_indikator == $dpre['kode_indikator']) {
                              echo '<br><label class="label label-warning">Terlambat = '.$dpre['n1'].'</label>';
                              echo '<label class="label label-danger">Tidak Masuk = '.$dpre['n2'].'</label>';
                            }
                          }
                          echo '</td>
                          <td>'.$h->bobot.''.$h->satuan.'</td>
                          <td>';
                          $pn=$this->db->get_where('master_penilai',array('kode_penilai'=>$h->kode_penilai))->row_array();
                          if ($h->kode_penilai == 'P4') {
                            echo 'User';
                          }else{
                            echo $pn['penilai'];
                          }
                          echo '</td>
                          <td>';
                          if ($h->sifat == "1") {
                            echo '<label class="label label-primary">Individu</label>';
                          }elseif ($h->sifat == "2") {
                            echo '<label class="label label-warning">Kolektif</label>';
                          }else{
                            echo '<label class="label label-success">Individu & Kolektif</label>';
                          }
                          echo '</td>

                          <td class="text-center">'.$h->target.'%</td>
                          <td class="text-center">'.$h->realisasi.'</td>';
                          if($jabat['kode_periode'] == "BLN") {
                            $nax[$n]=array($h->na1,$h->na2,$h->na3,$h->na4,$h->na5,$h->na6);
                            $avg_nax[$no][$n]=array_sum($nax[$n])/count($nax[$n]);
                            echo '<td class="text-center">'.number_format($h->nra1,2,',',',').'</td>
                                  <td class="text-center">'.number_format($h->na1,2,',',',').'</td>

                                  <td class="text-center">'.number_format($h->nra2,2,',',',').'</td>
                                  <td class="text-center">'.number_format($h->na2,2,',',',').'</td>

                                  <td class="text-center">'.number_format($h->nra3,2,',',',').'</td>
                                  <td class="text-center">'.number_format($h->na3,2,',',',').'</td>

                                  <td class="text-center">'.number_format($h->nra4,2,',',',').'</td>
                                  <td class="text-center">'.number_format($h->na4,2,',',',').'</td>

                                  <td class="text-center">'.number_format($h->nra5,2,',',',').'</td>
                                  <td class="text-center">'.number_format($h->na5,2,',',',').'</td>

                                  <td class="text-center">'.number_format($h->nra6,2,',',',').'</td>
                                  <td class="text-center">'.number_format($h->na6,2,',',',').'</td>
                                  <td class="text-center bg-success">'.number_format($avg_nax[$no][$n],2,',',',').'</td>';
                          }elseif($jabat['kode_periode'] == "SMT"){
                            $nax[$n]=array($h->na6);
                            $avg_nax[$no][$n]=array_sum($nax[$n])/count($nax[$n]);
                            echo '<td class="text-center">'.number_format($h->nra6,2,',',',').'</td>
                                  <td class="text-center">'.number_format($h->na6,2,',',',').'</td>
                                  <td class="text-center bg-success">'.number_format($avg_nax[$no][$n],2,',',',').'</td>';
                          }
                          echo '
                        </tr>';
                  $n++;
                }
                $ni[$no] = array_filter($ni[$no]);
                if (count($ni[$no]) == 0) {
                  $avg[$no] = 0;
                }else{
                  if (array_sum($n1[$no]) != 0) {
                    $nnn[$no][]=array_sum($n1[$no]);
                  }
                  if (array_sum($n2[$no]) != 0) {
                    $nnn[$no][]=array_sum($n2[$no]);
                  }
                  if (array_sum($n3[$no]) != 0) {
                    $nnn[$no][]=array_sum($n3[$no]);
                  }
                  if (array_sum($n4[$no]) != 0) {
                    $nnn[$no][]=array_sum($n4[$no]);
                  }
                  if (array_sum($n5[$no]) != 0) {
                    $nnn[$no][]=array_sum($n5[$no]);
                  }
                  if (array_sum($n6[$no]) != 0) {
                    $nnn[$no][]=array_sum($n6[$no]);
                  }
                  $avg[$no] = array_sum($avg_nax[$no]);
                }
                echo '<tr>';
                if ($jabat['kode_kategori'] != 'KAT1') {
                  echo '<td colspan="7" class="text-center bg-aqua"><b>Nilai Total</b></td>';

                  if ($jabat['kode_periode'] == "BLN") {
                    if ($agd['semester'] == "1") {
                      echo '<td colspan="1" class="text-center bg-navy">Januari</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n1[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">Februari</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n2[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">Maret</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n3[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">April</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n4[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">Mei</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n5[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">Juni</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n6[$no]),2,',',',').'</td>';
                    }else{
                      echo '<td colspan="1" class="text-center bg-navy">Juli</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n1[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">Agustus</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n2[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">September</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n3[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">Oktober</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n4[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">November</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n5[$no]),2,',',',').'</td>
                      <td colspan="1" class="text-center bg-navy">Desember</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n6[$no]),2,',',',').'</td>';

                    }
                  }elseif ($jabat['kode_periode'] == "SMT") {
                    if ($agd['semester'] == '1') {
                      echo '<td colspan="1" class="text-center bg-navy">Januari - Juni</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n6[$no]),2,',',',').'</td>';
                    }else{
                      echo '<td colspan="1" class="text-center bg-navy">Juli - Desember</td>
                      <td class="text-center bg-info">'.number_format(array_sum($n6[$no]),2,',',',').'</td>';
                    }
                  }
                }else{
                  echo '<td colspan="9" class="text-center bg-aqua"><b>Nilai Total</b></td>';
                }
                
                echo '<td class="bg-red text-center"><b style="font-size:16pt;font-weight:600;">'.number_format($avg[$no],2,',',',').'</b></td>
                  </tr>';
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php 
      $no++;
    } ?>
    </div>
  </div>
</section>
</div>