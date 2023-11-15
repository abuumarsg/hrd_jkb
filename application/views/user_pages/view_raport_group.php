<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-file-text"></i> Raport Akhir
      <small><?php echo ucwords($att[$id]['nama']);?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url('kpages/list_raport_group');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
      <li class="active">Raport Akhir <?php echo ucwords($att[$id]['nama']);
      ?></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle view_photo" src="<?php echo base_url($foto); ?>" alt="User profile picture">

            <h3 class="profile-username text-center"><?php echo ucwords($att[$id]['nama']); ?></h3>

            <p class="text-muted text-center"><?php 
            if ($att[$id]['jabatan'] == "") {
              echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
            }else{
             echo $att[$id]['jabatan']; 
            }
            ?></p>

            <ul class="list-group list-group-unbordered"> 
              <li class="list-group-item">
                <b>Terdaftar Sejak</b> <label class="pull-right label label-primary"><?php echo $this->formatter->getDateMonthFormatUser($tgl_masuk); ?></label>
              </li>
              <li class="list-group-item">
                <b>Periode Agenda</b> <label class="pull-right label label-success"><?php echo $periode; ?></label>
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
                      <td><?php echo ucwords($att[$id]['nama']);?></td>
                    </tr>
                    <tr>
                      <th>Email</th>
                      <td><?php 
                      if ($email == "") {
                        echo '<label class="label label-danger">Email Tidak Ada</label>';
                      }else{
                        echo $email;
                      }
                       ?></td>
                    </tr>
                    <tr>
                      <th>Username / NIK</th>
                      <td><?php echo $nik;?></td>
                    </tr>
                    <tr>
                      <th>Jenis Kelamin</th>
                      <td><?php 
                      if($kelamin == 'Pria'){
                        echo '<i class="fa fa-male" style="color:blue"></i> Laki-laki';
                      }else{
                        echo '<i class="fa fa-female" style="color:#ff00a5"></i> Perempuan';
                      }?></td>
                    </tr>
                    <tr> 
                      <th>Jabatan</th>
                      <td><?php 
                      if ($att[$id]['jabatan'] == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
                      }else{
                       echo $att[$id]['jabatan']; 
                      }?></td>
                    </tr>   
                    <tr>
                      <th>Lokasi Kerja</th>
                      <td><?php 
                      if ($att[$id]['loker']== "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
                      }else{
                       echo $att[$id]['loker']; 
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
          <h3 class="box-title"><i class="fa fa-line-chart"></i> Detail Rapor</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
          <div class="col-md-9">
            <div class="callout callout-success"><label><i class="fa fa-info-circle"></i> Data Raport Akhir</label><br>Ini adalah nilai final Anda di <b>Periode Agenda <?php echo $periode;?></b>.
              <?php 
                if ($att[$id]['validasi_o'] == 0) {
                  echo '<br><label class="label label-warning"><i class="fa fa-warning"></i> WARNING</label> Nilai Output (Target) Belum Divalidasi oleh Admin';
                }
                if ($att[$id]['validasi_s'] == 0) {
                  echo '<br><label class="label label-warning"><i class="fa fa-warning"></i> WARNING</label> Nilai Sikap dan Perilaku Belum Divalidasi oleh Admin';
                }
              ?>
            </div>
            <table class="table table-hover">
              <thead class="bg-black">
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-left">Penilaian</th>
                  <th class="text-center">Bobot Penilaian</th>
                  <th class="text-center">Nilai</th>
                  <th class="text-center">Nilai Akhir</th>
                </tr>
              </thead>
              <tbody class="text-center">
                <tr>
                  <td>1.</td>
                  <td class="text-left">Nilai Target</td>
                  <td><?php echo $bobot[$id]['bobot_out'];?>%</td>
                  <?php
                    if ($att[$id]['validasi_o'] == 0) {
                      $output=0;
                    }else{
                      $output=$att[$id]['nilai_target'];
                    }
                  ?>
                  <td><?php echo number_format($output,2,',',',');?></td>
                  <td><?php 
                  $fb1=($bobot[$id]['bobot_out']/100)*$output; 
                  echo number_format($fb1,2,',',',');
                  ?></td>
                </tr>
                <tr>
                  <td>2.</td>
                  <td class="text-left">Sikap dan Perilaku</td>
                  <td><?php echo $bobot[$id]['bobot_skp'];?>%</td>
                  <?php
                    if ($att[$id]['validasi_s'] == 0) {
                      $sikap=0;
                    }else{
                      if ($att[$id]['nilai_kalibrasi'] != 0) {
                        $sikap=$att[$id]['nilai_kalibrasi'];
                      }else{
                        $sikap=$att[$id]['nilai_sikap'];
                      }
                    }
                  ?>
                  <td><?php echo number_format($sikap,2,',',',');?></td>
                  <td><?php 
                  $fb2=($bobot[$id]['bobot_skp']/100)*$sikap;
                  echo number_format($fb2,2,',',',');
                  ?></td>
                </tr>
                <tr>
                  <td>3.</td>
                  <td class="text-left">Pencapaian OS PT BPR WM</td>
                  <td><?php echo $bobot[$id]['bobot_tc'];?>%</td>
                  <td><?php echo number_format($att[$id]['nilai_corp'],2,',',',');?></td>
                  <td><?php 
                  $fb3=($bobot[$id]['bobot_tc']/100)*$att[$id]['nilai_corp'];
                  echo number_format($fb3,2,',',',');
                  ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-3">
            <?php 
            $con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
            $avg1 = $fb1+$fb2+$fb3;
            ?>
            <table class="table table-bordered text-center">
              <thead>
                <tr>
                  <th class="bg-yellow">Nilai Akhir</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="font-size: 60pt;">
                    <?php 
                    if ($avg1 > 100 || $avg1 < 0) {
                      echo '<i class="fa fa-times-circle" style="color:red"></i>';
                    }else{
                      
                      foreach ($con as $c) {
                        if (number_format($avg1,2) >= $c->awal && number_format($avg1,2) <= $c->akhir) {
                          echo $c->huruf;
                        }
                      }
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <?php
                    foreach ($con as $c2) {
                        if (number_format($avg1,2) >= $c2->awal && number_format($avg1,2) <= $c2->akhir) {
                          echo '<td style="font-size: 20pt;color:#fff;background-color:'.$c2->warna.'">'.number_format($avg1,2,',',',').'<br><b style="font-size:12pt;">'.$c2->nama.'</b></td>';
                        }
                    }
                  ?>
                  
                </tr>
              </tbody>

            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

            <?php 
              if (count($con) == 0) {
                echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
              }else{
            ?>
            <div class="callout callout-info"><label><i class="fa fa-bullhorn"></i> Kategori Nilai</label></div>
            <table class="table table-hover table-bordered">
              <thead class="bg-blue">
                <tr>
                  <th>No.</th>
                  <th>Kategori</th>
                  <th>Rentang Nilai</th>
                  <th>Warna</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $nn=1;
                  foreach ($con as $c1) {
                    echo '<tr>
                      <td width="3%">'.$nn.'.</td>
                      <td>'.$c1->nama.'</td>
                      <td>'.$c1->awal.' - '.number_format($c1->akhir,0).'</td>
                      <td><i class="fa fa-circle" style="font-size:19pt;color:'.$c1->warna.'"></i></td>
                    </tr>';
                    $nn++;
                  }
                ?>
              </tbody>
            </table>
            <?php } ?>
          </div>
        </div>
        </div>
      </div>
      
    </div>
  </div>
</section>
</div>