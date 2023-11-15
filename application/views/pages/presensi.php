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
        <i class="fa fa-calendar-check-o"></i> Data Presensi 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Daftar Data Presensi</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar-check-o"></i> Daftar Data Presensi</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <?php if (count($presensi) > 0) { ?>
              <div class="row">
                <div class="col-md-12">
                  <div class="text-center" style="font-size: 8pt;">
                    <p><i class="fa fa-unlock stat" style="color: grey;"></i> Data Tidak Terkunci  <i class="fa fa-lock stat err"></i> Data Terkunci</p>
                  </div>
                </div>
              </div> 
              <?php } ?>
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($presensi) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan, Data Presensi Hanya dapat dibuat jika anda membuat Agenda Baru pada menu <a href="'.base_url('pages/agenda').'">Agenda Penilaian > Daftar Agenda</a></div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Data untuk melakukan Input atau Edit Data Presensi</div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Data</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th>Status Data</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($presensi as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="15%"><a href="'.base_url('pages/view_presensi/'.$l->kode_presensi).'">'.$l->nama_presensi.'</a> ';
                                  if ($l->edit == 0) {
                                    echo '<i class="fa fa-lock" style="color:red;"></i>';
                                  }else{
                                    echo '<i class="fa fa-unlock" style="color:grey;"></i>';
                                  }
                                  echo '<br>';
                                  if ($l->semester == 1) {
                                    echo '<label class="label label-info">Semester 1</label>';
                                  }else{
                                    echo '<label class="label label-success">Semester 2</label>';

                                  }
                                  echo '
                                  <td class="text-center" width="15%">';
                                  if ($l->kode_agenda == NULL) {
                                    echo '<label class="label label-danger">Data Presensi Tidak Berkaitan Dengan Agenda</label><br>';
                                  }else{
                                    $dta=$this->db->get_where('log_agenda',array('kode_agenda'=>$l->kode_agenda))->row_array();
                                    if ($dta == "") {
                                      echo '<label class="label label-danger">Data Presensi Tidak Berkaitan Dengan Agenda</label><br>';
                                    }else{
                                      echo '<p><i class="fa fa-check-circle text-success"></i> Data Presensi Terkait dengan '.$dta['nama_agenda'].' Semester '.$dta['semester'].'</p>';
                                    }
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%"><label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->update_date).' WIB</label></td>
                                  <td class="text-center" width="10%">';
                                  $tb=$l->nama_tabel;
                                  $dat=$this->db->get($tb)->num_rows();
                                  if ($dat == 0) {
                                    $st='<label class="label label-danger">Data Masih Kosong</label>';
                                  }else{
                                    $st='<label class="label label-success">Data Terisi</label>';
                                  }
                                  echo $st.'</td>
                                  <td class="text-center" width="10%">';
                                    if (in_array($access['l_ac']['edt'], $access['access'])) {
                                      if ($l->kode_agenda != NULL) {
                                        $dta=$this->db->get_where('log_agenda',array('kode_agenda'=>$l->kode_agenda))->row_array();
                                        if ($dta != "") {
                                          if ($l->edit == 0) {
                                            echo '<a href="#lc'.$n.'" data-toggle="modal" class="btn btn-success"><i class="fa fa-unlock" data-toggle="tooltip" title="Buka Kunci"></i></a> ';
                                          }else{
                                            echo '<a href="#lc'.$n.'" data-toggle="modal" class="btn btn-warning"><i class="fa fa-lock" data-toggle="tooltip" title="Kunci Data"></i></a> ';
                                          }
                                        }else{
                                          $dt1=array('edit'=>'0');
                                          $this->db->where('kode_agenda',$l->kode_agenda);
                                          $this->db->update('dp_presensi',$dt1);
                                        }
                                      }
                                    }
                                    if (in_array($access['l_ac']['del'], $access['access'])) {
                                      echo '<a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a>';
                                    }
                                    echo $access['n_all'];
                                  echo '</td>
                                </tr>';
                                if (in_array($access['l_ac']['edt'], $access['access'])) {
                                  echo '<div id="lc'.$n.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Konfirmasi Kunci Data</h4>
                                      </div>
                                      <div class="modal-body text-center">';
                                        if ($l->edit == 0) {
                                          echo '<p>Apakah anda yakin akan membuka kunci data dengan nama <b>'.$l->nama_presensi.'</b> agar bisa diupdate?</p>';
                                        }else{
                                          echo '<p>Apakah anda yakin akan mengunci data dengan nama <b>'.$l->nama_presensi.'</b> agar TIDAK bisa diupdate?</p>';
                                        }
                                        
                                      echo '</div>
                                      <div class="modal-footer">
                                      '.form_open('presensi/lock_presensi').'
                                        <input type="hidden" name="id" value="'.$l->id_presensi.'">';
                                        if ($l->edit == 0) {
                                          echo '<input type="hidden" name="lock" value="1">
                                          <button type="submit" class="btn btn-success"><i class="fa fa-unlock"></i> Buka Kunci</button>';
                                        }else{
                                          echo '<input type="hidden" name="lock" value="0">
                                          <button type="submit" class="btn btn-danger"><i class="fa fa-lock"></i> Kunci Data</button>';
                                        }
                                        
                                        echo '<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                      '.form_close().'
                                      </div>
                                    </div>
                                  </div>
                                  </div>';
                                }
                                if (in_array($access['l_ac']['del'], $access['access'])) {
                                  echo '<div id="del'.$n.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm modal-danger">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                      </div>
                                      <div class="modal-body text-center">
                                        <p>Apakah anda yakin akan menghapus data presensi dengan nama <b>'.$l->nama_presensi.'</b> dan seluruh data penilaian pada presensi tersebut?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('presensi/del_presensi').'
                                        <input type="hidden" name="id" value="'.$l->id_presensi.'">
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
    </section>
  </div> 