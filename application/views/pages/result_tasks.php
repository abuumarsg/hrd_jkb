  <div class="content-wrapper">
    <?php 
    if (!empty($this->session->flashdata('msgsc'))) {
      echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
    }elseif (!empty($this->session->flashdata('msgerr'))) {
      echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
    }
    ?>
    <section class="content-header">
      <h1>
       <i class="fa fa-tasks"></i> Daftar Penilaian Output (Target)
        <small>Hasil Nilai</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Daftar Agenda Penilaian Output (Target)</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Agenda</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($agd) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan, Jika Anda ingin melihat riwayat penilaian maka dapat Anda lihat pada menu <a href="'.base_url('pages/log_agenda').'">Agenda Penilaian > Log Agenda</a></div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Agenda untuk melihat partisipan Penilaian Kinerja</div>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Agenda</th>
                        <th>Progress</th>
                        <th>Tanggal Agenda</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($agd as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="15%"><a href="'.base_url('pages/result_tasks_value/'.$l->kode_agenda).'">'.$l->nama_agenda.' <br>
                                  <label class="label label-primary">'.$l->tahun.'</label>';
                                  if ($l->semester == 1) {
                                    echo '<label class="label label-info">Semester 1</label>';
                                  }else{
                                    echo '<label class="label label-success">Semester 2</label>';

                                  }
                                  echo '</a></td>
                                  <td width="30%">';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
                                  }else{
                                    $nmtb1=$l->tabel_agenda;
                                    $dto=$this->db->query("SELECT * FROM $nmtb1 WHERE nilai_out != 0")->num_rows();
                                    $dtt1=$this->db->query("SELECT * FROM $nmtb1")->num_rows();
                                    $tdto=$dto;
                                    $jm1= ($tdto/($dtt1*1))*100;
                                    $jm=number_format($jm1,2);
                                    /*
                                    if ($jm == 100) {
                                      $dtt=array('keterangan'=>"done");
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('agenda',$dtt);
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('log_agenda',$dtt);
                                    }else{
                                      $dtt=array('keterangan'=>"progress");
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('agenda',$dtt);
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('log_agenda',$dtt);
                                    }
                                    */
                                    echo '<div class="progress active">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="'.$jm.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$jm.'%">
                                          '.$jm.'%
                                        </div>
                                      </div>';
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%"><label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal"><i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal"><i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->tgl_selesai).' WIB</label></td>
                                  <td width="10%">';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
                                  }elseif ($l->keterangan == "progress") {
                                    echo '<label class="label label-warning">Proses Entry Data</label>';
                                  }else{
                                    echo '<label class="label label-success">Semua Data Selesai Diisi</label>';
                                  }
                                  echo '<br>';
                                  if (date("Y-m-d H:i:s",strtotime($l->tgl_selesai)) < date("Y-m-d H:i:s",strtotime($tgl))) {
                                    echo '<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
                                  }elseif ((date("Y-m-d H:i:s",strtotime($l->tgl_mulai)) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($l->tgl_selesai)) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
                                    echo '<label class="label label-info">Agenda Sedang Berlangsung</label>';
                                  }
                                  echo '</td>
                                </tr>';
                          $n++;      
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