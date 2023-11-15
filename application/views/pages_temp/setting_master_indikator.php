  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-gear"></i> Setting Master 
        <small>Indikator</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Indokator</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Indikator</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="pull-left">
                        <div class="dropdown">
                          <button class="btn btn-flat btn-info dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-calendar-plus-o"></i> Tambah Ke Agenda 
                          <span class="caret"></span></button>
                          <ul class="dropdown-menu">
                            <?php 
                              $ag=$this->db->query("SELECT * FROM agenda WHERE status = 'aktif' AND keterangan != 'done'")->result();
                              if (count($ag) != 0) {
                                foreach ($ag as $a) {
                                  echo '<li><a href="'.base_url('agenda/create_task/'.$a->kode_agenda).'">'.$a->nama_agenda.'</a></li>';
                                }
                              }else{
                                echo '
                                <li class="dropdown-header"><label class="label label-danger">Agenda Kosong atau Tidak Aktif</label></li>
                                <li><a href="'.base_url('pages/agenda').'">Buat Agenda atau Aktifkan ?</a></li>';
                              }
                            ?>
                          </ul>
                        </div>
                      </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">

                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Klik Indikator untuk melakukan setting</div>
                  <?php 
                  if (!empty($this->session->flashdata('msgsc'))) {
                    echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
                  }elseif (!empty($this->session->flashdata('msgerr'))) {
                    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
                  }

                  if (count($indikator) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Indikator</th>
                        <th>Cara Pengukuran</th>
                        <th>Sumber Data</th>
                        <th>Pelaporan</th>
                        <th>Polarisasi</th>
                        <th>Sifat</th>
                        <th>Penanggung Jawab</th>
                        <th>Bobot</th>
                        <th>Satuan</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($indikator as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="50%"><a href="'.base_url('pages/view_setting_master_indikator/'.$l->kode_indikator).'">'.$l->kpi.'</a></td>
                                  <td width="10%">'.$l->cara_mengukur.'</td>
                                  <td>'.$l->sumber.'</td>
                                  <td>'.$l->periode_pelaporan.'</td>
                                  <td>'.$l->polarisasi.'</td>
                                  <td>'.$l->sifat.'</td>
                                  <td><a href="#kar'.$n.'" data-toggle="collapse">'.$l->penanggungjawab.'</a>';
                                  $kr1=explode(",", $l->id_karyawan);
                                  
                                  echo '<div class="collapse" id="kar'.$n.'">';
                                    foreach ($kr1 as $k1) {
                                      $kr=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$k1'")->row_array();
                                      echo '<i class="fa fa-angle-right"></i> '.$kr['nama'].'<hr>';
                                    }
                                  echo '</div>';
                                  echo '</td>
                                  <td>'.$l->bobot.'</td>
                                  <td>'.$l->satuan.'</td>
                                  <td class="text-center" width="5%">';
                                  if ($l->status == "aktif") {
                                    echo form_open('setting/status_indikator').'
                                    <input type="hidden" name="id" value="'.$l->id_indikator.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>'
                                    .form_close();
                                  }else{
                                    echo form_open('setting/status_indikator').'
                                    <input type="hidden" name="id" value="'.$l->id_indikator.'">
                                    <input type="hidden" name="act" value="aktif">
                                    <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>'
                                    .form_close();
                                  }
                                  echo '</td>
                                </tr>
                                <div id="edt'.$n.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Edit Data</h4>
                                      </div>
                                      <div class="modal-body">
                                        <p class="text-danger">Semua data harus diisi! <br> Kode Layer TIDAK BOLEH SAMA</p>
                                        '.form_open('master/edt_layer').'
                                        <input type="hidden" name="id" value="'.$l->id_indikator.'">
                                        <div class="form-group">
                                          <label>Kode Layer Organisasi</label>
                                          <input type="text" placeholder="Masukkan Kode Layer" name="kode" value="'.$l->kpi.'" class="form-control" required >
                                        </div>
                                        <div class="form-group">
                                          <label>Nama Layer Organisasi</label>
                                          <input type="text" placeholder="Masukkan Nama Layer Organisasi" name="layer" value="'.$l->status.'" class="form-control" required >
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        '.form_close().'
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                      </div>
                                    </div>

                                  </div>
                                </div>
                                <div id="del'.$n.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm modal-danger">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                      </div>
                                      <div class="modal-body text-center">
                                        <p>Apakah anda yakin akan menghapus data indikator tersebut ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('master/del_layer').'
                                        <input type="hidden" name="id" value="'.$l->id_indikator.'">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                      '.form_close().'
                                      </div>
                                    </div>
                                  </div>
                                </div>';
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