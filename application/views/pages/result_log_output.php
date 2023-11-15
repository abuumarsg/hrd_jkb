  <div class="content-wrapper">
    <div class="alert alert-info">
      <i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian KPI Output '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' Semester '.$agd['semester'].'</b>';
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
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan Penilaian Output(Target)
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/log_agenda');?>"><i class="fa fa-calendar"></i> Daftar Log Agenda</a></li>
        <li class="active">Daftar Hasil Penilaian</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Penilaian Output(Target)</h3>
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
                    if ($agd['semester'] == 0) {
                      $per="Januari - Desember Tahun ".$agd['tahun'];
                    }elseif ($agd['semester'] == 1) {
                      $per="Januari - Juni Tahun ".$agd['tahun'];
                    }else{
                      $per="Juli - Desember Tahun ".$agd['tahun'];
                    }
                    $data=array('data'=>$tabel,'periode'=>$agd['nama_agenda'].' '.$per);
                  ?>
                  <div class="row">
                    <div class="col-md-12">
                      <?php if(in_array($access['l_ac']['rkp'], $access['access'])){?>
                      <div class="pull-left">
                        <?php echo form_open('rekap/rekap_nilai_output_log');?>
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($data));?>">
                        <input type="hidden" name="th" value="<?php echo $agd['tahun']; ?>">
                        <input type="hidden" name="smt" value="<?php echo $agd['semester']; ?>">
                        <button type="submit" class="btn btn-flat btn-warning"><i class="fa fa-cloud-download"></i> Rekap Nilai</button>
                        <?php echo form_close();?>
                      </div><?php }if(in_array($access['l_ac']['prn'], $access['access'])){?>
                      <div class="pull-left">
                        <?php echo form_open('pages/print_page');?>
                        <input type="hidden" name="page" value="<?php echo "result_tasks";?>">
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($data));?>">
                        <button type="submit" class="btn btn-flat btn-info"><i class="fa fa-print"></i> Print</button>
                        <?php echo form_close();?>
                      </div><?php } ?>
                    </div>
                  </div>
                  <br>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Karyawan untuk melihat Rapor Penilaian Kinerja</div>
                  <table id="example1" class="table table-bordered table-striped  table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Jumlah Indikator</th>
                        <th class="text-center">Nilai Target</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n1=1;
                        foreach ($tabel as $k => $l) {
                          echo '<tr>
                                    <td width="3%">'.$n1.'.</td>
                                    <td><a href="'.base_url('pages/log_report_output/'.$nmtb.'/'.$k).'">';
                                    $con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
                                    foreach ($con as $c) {
                                      if (number_format($l['nilai'],2) >= $c->awal && number_format($l['nilai'],2) <= $c->akhir) {
                                        if ($c->warna != NULL) {
                                          echo '<i class="fa fa-circle" style="color:'.$c->warna.'"></i> ';
                                        }
                                      }
                                    }
                                    echo $l['nik'].'</a></td>
                                    <td>'.$l['nama'].'</td>
                                    <td>'.$l['jabatan'].'</td>
                                    <td>'.$l['loker'].'</td>
                                    <td>'.$l['ind'].' Indikator</td>
                                    <td class="text-center">'.number_format($l['nilai'],2,',',',').'</td>
                                    </tr>';
                                    $n1++;
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