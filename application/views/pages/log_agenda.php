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
       <i class="fa fa-calendar"></i> Log Agenda 
        <small>Penilaian</small>
      </h1> 
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Daftar Agenda Penilaian</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Log Agenda Output (Target)</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($log_agd) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Agenda</th>
                        <th>Nilai</th>
                        <th>Progress</th>
                        <th>Tanggal Agenda</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($log_agd as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="15%">'.$l->nama_agenda.'<br>
                                  <label class="label label-primary">'.$l->tahun.'</label>';
                                  if ($l->semester == 1) {
                                    echo '<label class="label label-info">Semester 1</label>';
                                  }else{
                                    echo '<label class="label label-success">Semester 2</label>';

                                  }
                                  echo '</td>
                                  <td class="text-center">';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
                                  }else{
                                    echo '<button data-toggle="collapse" class="btn btn-flat btn-block btn-primary btn-sm" data-target="#lk'.$n.'"><i class="fa fa-eye"></i> Lihat Nilai</button>
                                    <div id="lk'.$n.'" class="collapse">
                                      <br>
                                      <p><a href="'.base_url('pages/result_log_output/'.$l->kode_agenda).'" class="btn btn-flat btn-block btn-success btn-sm"><i class="fa fa-eye"></i> Nilai Output</a></p>
                                      <p><a href="'.base_url('pages/result_group').'" class="btn btn-flat btn-block btn-success btn-sm"><i class="fa fa-eye"></i> Nilai Akhir</a></p>
                                    </div>
                                  </td>';
                                  }
                                  echo '<td width="30%">';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
                                  }else{
                                    $nmtb1=$l->tabel_agenda;
                                    $dto=$this->db->query("SELECT * FROM $nmtb1 WHERE nilai_out != 0")->num_rows();
                                    $dto2=$this->db->query("SELECT * FROM $nmtb1 WHERE nilai_tc != 0")->num_rows();
                                    $dtt1=$this->db->query("SELECT * FROM $nmtb1")->num_rows();
                                    $tdto=$dto+$dto2;
                                    $jm1= ($tdto/($dtt1*2))*100;
                                    $jm=number_format($jm1,2);
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
                                    echo '<div class="progress active">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="'.$jm.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$jm.'%">
                                          '.$jm.'%
                                        </div>
                                      </div>';
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%"><label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal"><i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal"><i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->tgl_selesai).' WIB</label></td>
                                  <td>';
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
                                  <td class="text-center" width="10%">';
                                  if (in_array($access['l_ac']['del'], $access['access'])) {
                                    echo '<a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a>';
                                  }else{
                                    echo '<label class="label label-danger">Tidak Diizinkan</label>';
                                  }  
                                  echo '</td>
                                </tr>';
                                if (in_array($access['l_ac']['del'], $access['access'])) {
                                  echo '<div id="del'.$n.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm modal-danger">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                      </div>
                                      <div class="modal-body text-center">
                                        <p>Apakah anda yakin akan menghapus data Agenda Output (Target) dengan nama <b>'.$l->nama_agenda.'</b> ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('agenda/del_log_agenda').'
                                        <input type="hidden" name="id" value="'.$l->id_agenda.'">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                      '.form_close().'
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                                }
                                
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
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Log Agenda Sikap (360°)</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($log_attd_agd) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Agenda</th>
                        <th>Nilai</th>
                        <th>Progress</th>
                        <th>Tanggal Agenda</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n1=1;
                        foreach ($log_attd_agd as $l1) {
                          echo '<tr>
                                  <td width="3%">'.$n1.'.</td>
                                  <td width="15%">'.$l1->nama_agenda.'<br>
                                  <label class="label label-primary">'.$l1->tahun.'</label>';
                                  if ($l1->semester == 1) {
                                    echo '<label class="label label-info">Semester 1</label>';
                                  }else{
                                    echo '<label class="label label-success">Semester 2</label>';

                                  }
                                  echo '</td>
                                  <td class="text-center">';
                                  if ($l1->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Rancangan Sikap Belum Dimasukkan</label>';
                                  }else{
                                    echo '<button data-toggle="collapse" class="btn btn-flat btn-block btn-primary btn-sm" data-target="#lk1'.$n1.'"><i class="fa fa-eye"></i> Lihat Nilai</button>
                                    <div id="lk1'.$n1.'" class="collapse">
                                      <br>
                                      <p><a href="'.base_url('pages/result_log_sikap/'.$l1->kode_agenda).'" class="btn btn-flat btn-block btn-success btn-sm"><i class="fa fa-eye"></i> Nilai Sikap</a></p>
                                      <p><a href="'.base_url('pages/result_group').'" class="btn btn-flat btn-block btn-success btn-sm"><i class="fa fa-eye"></i> Nilai Akhir</a></p>
                                    </div>
                                  </td>';
                                  }
                                  echo '<td width="30%">';
                                  if ($l1->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Rancangan Sikap Belum Dimasukkan</label>';
                                  }else{
                                    $nmtba1=$l1->tabel_agenda;
                                    $dtoa=$this->db->query("SELECT * FROM $nmtba1 WHERE nilai_akhir != 0")->num_rows();
                                    $dtta1=$this->db->query("SELECT * FROM $nmtba1")->num_rows();
                                    $jm1a= ($dtoa/($dtta1))*100;
                                    $jma=number_format($jm1a,2);
                                    if ($jma == 100) {
                                      $dtta=array('keterangan'=>"done");
                                      $this->db->where('kode_agenda',$l1->kode_agenda);
                                      $this->db->update('attd_agenda',$dtta);
                                      $this->db->where('kode_agenda',$l1->kode_agenda);
                                      $this->db->update('log_attd_agenda',$dtta);
                                    }else{
                                      $dtta=array('keterangan'=>"progress");
                                      $this->db->where('kode_agenda',$l1->kode_agenda);
                                      $this->db->update('attd_agenda',$dtta);
                                      $this->db->where('kode_agenda',$l1->kode_agenda);
                                      $this->db->update('log_attd_agenda',$dtta);
                                    }
                                    echo '<div class="progress active">
                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$jma.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$jma.'%">
                                          '.$jma.'%
                                        </div>
                                      </div>';
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%"><label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal"><i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($l1->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal"><i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($l1->tgl_selesai).' WIB</label></td>
                                  <td>';
                                  if ($l1->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Rancangan Sikap Belum Dimasukkan</label>';
                                  }elseif ($l1->keterangan == "progress") {
                                    echo '<label class="label label-warning">Proses Entry Data</label>';
                                  }else{
                                    echo '<label class="label label-success">Semua Data Selesai Diisi</label>';
                                  }
                                  echo '<br>';
                                  if (date("Y-m-d H:i:s",strtotime($l1->tgl_selesai)) < date("Y-m-d H:i:s",strtotime($tgl))) {
                                    echo '<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
                                  }elseif ((date("Y-m-d H:i:s",strtotime($l1->tgl_mulai)) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($l1->tgl_selesai)) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
                                    echo '<label class="label label-info">Agenda Sedang Berlangsung</label>';
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%">';
                                  if (in_array($access['l_ac']['del'], $access['access'])) {
                                    echo '<a href="#dela'.$n1.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a>';
                                  }else{
                                    echo '<label class="label label-danger">Tidak Diizinkan</label>';
                                  }
                                  echo '</td>
                                </tr>';
                                if (in_array($access['l_ac']['del'], $access['access'])) {
                                  echo '<div id="dela'.$n1.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm modal-danger">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                      </div>
                                      <div class="modal-body text-center">
                                        <p>Apakah anda yakin akan menghapus data Agenda Sikap (360°) dengan nama <b>'.$l1->nama_agenda.'</b> ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('agenda/del_log_attd_agenda').'
                                        <input type="hidden" name="id" value="'.$l1->id_agenda.'">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                      '.form_close().'
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                                }
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