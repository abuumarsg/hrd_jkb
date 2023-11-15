  <div class="content-wrapper">
    <div class="alert alert-info">
      <i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian Output (Target) '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' Semester '.$agd['semester'].'</b>';
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
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan Penilaian Output (Target)
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/result_tasks');?>"><i class="fa fa-table"></i> Daftar Agenda Penilaian Output (Target)</a></li>
        <li class="active">Daftar Karyawan Penilaian Output (Target)</li>
      </ol>
    </section> 
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Penilaian Output (Target)</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  $idk=array();
                  foreach ($data as $k => $a) {
                    foreach ($a['penilai'] as $pn) {
                      if ($pn['kode_penilai'] == 'P1' || $pn['kode_penilai'] == 'P2') {
                        if (in_array($me, $a['atasan'])) {
                          array_push($idk, $k);
                        }
                      }elseif ($pn['kode_penilai'] == "P4" && $pn['kode_penilai'] != 'P1' && $pn['kode_penilai'] != 'P2' && $pn['kode_penilai'] != 'P3') {
                        $pp=array_filter(explode(';', $pn['id_penilai']));
                        if (in_array($me, $pp)) {
                          array_push($idk, $k);
                        }
                      }
                    }
                  }
                  if (count($data) == 0 || count($idk) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih NIK untuk melakukan input nilai KPI Output</div>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>NIK</th> 
                        <th>Nama Karyawan</th> 
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Progress</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                          $kr=array_values(array_unique($idk));
                          $n1=1;
                          foreach ($data as $kk => $vv) {
                            if (in_array($kk, $idk) && $kk != $me) {
                              $datt=$this->db->get_where($nmtb,array('id_karyawan'=>$kk))->result();
                              $all[$kk]=array();
                              $nall[$kk]=array();
                              foreach ($vv['penilai'] as $vvp1) {
                                if ($vvp1['kode_penilai'] == 'P1' || $vvp1['kode_penilai'] == 'P2') {
                                    if (in_array($me, $vv['atasan'])) {
                                      array_push($all[$kk], 1);
                                    }
                                }
                                if ($vvp1['kode_penilai'] == 'P4') {
                                    $pen=array_filter(explode(';', $vvp1['id_penilai']));
                                    if (in_array($me, $pen)) {
                                      array_push($all[$kk], 1);
                                    }
                                }
                              }
                              $idj[$kk]=$vv['id_jabatan'];
                              $jbtan[$kk]=$this->db->query("SELECT kode_periode FROM master_jabatan WHERE id_jabatan = '$idj[$kk]'")->row_array();
                              if ($jbtan[$kk]['kode_periode'] == 'BLN') {
                                $jumx[$kk]=count($all[$kk])*6;
                              }else{
                                $jumx[$kk]=count($all[$kk])*1;
                              }
                              foreach ($vv['nilai'] as $vvn) {
                                foreach ($vvn as $vvn1) {
                                  if ($vvn1 == $me) {
                                    array_push($nall[$kk], 1);
                                  }
                                }
                              }
                              
                              echo '<tr>
                                  <td width="3%">'.$n1.'.</td>
                                  <td><a href="'.base_url('kpages/report_value/'.$nmtb.'/'.$kk).'">';
                                  if ($jumx[$kk] == count($nall[$kk])) {
                                    echo '<i class="fa fa-check-circle text-green" data-toggle="tooltip" title="Selesai"></i> ';
                                  }elseif ($jumx[$kk] > count($nall[$kk]) && count($nall[$kk]) > 0) {
                                    echo '<i class="fa fa-refresh fa-spin text-yellow" data-toggle="tooltip" title="Belum Selesai"></i> ';
                                  }else{
                                    echo '<i class="fa fa-times-circle text-red" data-toggle="tooltip" title="Belum Menilai"></i> ';
                                  }
                                    echo $vv['nik'].'</a></td>
                                    <td>'.$vv['nama'].'</td>
                                    <td>'.$vv['jabatan'].'</td>
                                    <td>'.$vv['loker'].'</td>';
                                    echo '<td>';
                                    $jm1= (count($nall[$kk])/($jumx[$kk]))*100;
                                    $jm=number_format($jm1,2);
                                    echo '<div class="progress active">
                                        <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'.$jm.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$jm.'%">
                                          <b class="text-black">'.$jm.'%</b>
                                        </div>
                                      </div>';
                                    echo '</td>
                                    <td class="text-center">';
                                    if ($jumx[$kk] == count($nall[$kk])) {
                                      echo '<label class="label label-success">Sudah Selesai</label>';
                                    }elseif ($jumx[$kk] > count($nall[$kk]) && count($nall[$kk]) > 0) {
                                      echo '<label class="label label-warning">Belum Selesai</label>';
                                    }else{
                                      echo '<label class="label label-danger">Belum Menilai</label>';
                                    }
                                    echo '</td>
                                  </tr>';
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