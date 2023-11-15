  <div class="content-wrapper">
    <div class="alert alert-info" id="alert-danger">
      <i class="fa fa-calendar"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian Sikap (360°) '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' Semester '.$agd['semester'].'</b>';
      }
      ?>
    </div>
    <?php 
    if (!empty($this->session->flashdata('msgsc'))) {
      echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
    }elseif (!empty($this->session->flashdata('msgerr'))) {
      echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
    }
    ?>
    <section class="content-header">
      <h1>
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Partisipan <?php echo $nama;?><br>
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/attitude_tasks');?>"><i class="fa fa-table"></i> Daftar Agenda</a></li>
        <li><a href="<?php echo base_url('kpages/input_attitude_tasks_value/'.$kode);?>"><i class="fa fa-table"></i> Daftar Karyawan</a></li>
        <li class="active">Daftar Partisipan <?php echo $nama;?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Partisipan <?php echo $nama;?></h3>
              <div class="box-tools pull-right">
                
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($tabel) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Karyawan untuk melakukan Input Penilaian Kinerja</div>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Sebagai</th>
                        <th>Jumlah Kuisioner</th>
                        <th>Keterangan</th>
                        <th class="text-center">Nilai Total Kuisioner</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n1=1;
                        foreach ($tabel as $t) {
                          $part[$t->partisipan]=$t->partisipan;
                          $ats1[$t->nilai_ats]=$t->nilai_ats;
                          $bwh1[$t->nilai_bwh]=$t->nilai_bwh;
                          $rkn1[$t->nilai_rkn]=$t->nilai_rkn;
                          $dri1[$t->nilai_dri]=$t->nilai_dri;
                        }
                        $prt1=implode('', $part);
                        $ats=implode('', $ats1);
                        $bwh=implode('', $bwh1);
                        $rkn=implode('', $rkn1);
                        $dri=implode('', $dri1);
                        $prt=array_filter(explode(';', $prt1));
                        if (count($tabel) != 0) {
                          $z1=1;
                          foreach ($prt as $p) {
                              $p1=explode(':', $p);
                              if ($p1[0] == 'ATS') {
                                $sbg="Atasan";
                              }elseif ($p1[0] == 'BWH') {
                                $sbg="Bawahan";
                              }elseif ($p1[0] == 'DRI') {
                                $sbg="Diri Sendiri";
                              }else{
                                $sbg="Rekan Kerja";
                              }
                              $idk=$p1[1];
                              $kry=$this->db->query("SELECT nama,jabatan,unit FROM karyawan WHERE id_karyawan = '$idk'")->row_array();
                              $jbt=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$kry['jabatan']))->row_array();
                              $lok=$this->db->get_where('master_loker',array('kode_loker'=>$kry['unit']))->row_array();

                              if (isset($nilai[$p])) {
                                foreach ($nilai[$p] as $np) {
                                  if ($np != 0) {
                                    $cn[$p][]=$np;
                                  }                                
                                }

                                $s[$p]=1;
                                
                                foreach ($nilai[$p] as $np) {
                                  if ($np != 0) {
                                    $naa[$p][$s[$p]]=$np;
                                  }
                                  $s[$p]++;
                                }
                              }
                              

                              if (isset($naa[$p])) {
                                $nn[$p]=array_sum($naa[$p])/count($naa[$p]);
                              }else{
                                $nn[$p]=0;
                              }
                              echo $p.'='.$nn[$p];
                              echo '<tr>
                                    <td width="3%">'.$n1.'.</td>
                                    <td><a href="'.base_url('pages/input_attitude_value/'.$kode.'/'.$id_k.'/'.$p).'">';
                                    if ($nn[$p] == 0) {
                                      echo '<i class="fa fa-times-circle text-red"></i> ';
                                    }else{
                                      if (isset($cn)) {
                                        if (count($cn[$p]) > 0 && count($cn[$p]) < count($nilai[$p])) {
                                          echo '<i class="fa fa-refresh fa-spin text-orange"></i> ';
                                        }else{
                                          echo '<i class="fa fa-check-circle text-green"></i> ';
                                        }
                                      }else{
                                        echo '<i class="fa fa-check-circle text-green"></i> ';
                                      }
                                    }
                                    echo $kry['nama'].'</a></td>
                                    <td>'.$jbt['jabatan'].'</td>
                                    <td>'.$lok['nama'].'</td>
                                    <td class="text-center">';
                                    if ($p1[0] == "DRI") {
                                      echo '<i class="fa fa-user text-blue"></i> ';
                                    }
                                    echo $sbg.'</td>
                                    <td>'.count($tabel).' Kuisioner</td>
                                    <td class="text-center">';
                                    if ($nn[$p] == 0) {
                                      echo '<label class="label label-danger">Belum Menilai</label>';
                                    }else{
                                      if (isset($cn)) {
                                        if (count($cn[$p]) > 0 && count($cn[$p]) < count($nilai[$p])) {
                                          echo '<label class="label label-warning">Belum Selesai</label>';
                                        }else{
                                          echo '<label class="label label-success">Selesai</label>';
                                        }
                                      }else{
                                        echo '<label class="label label-success">Selesai</label>';
                                      }
                                    }
                                    echo '</td>
                                    <td class="text-center">'.number_format($nn[$p],2,',',',').'</td>
                                    </tr>';
                            $z1++;
                            $n1++;
                          }
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