  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Master Data 
        <small>Status Kepegawaian</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Status Kepegawaian</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-briefcase"></i> Daftar Status Kepegawaian</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Status Kepegawaian</button>
                    <div class="collapse" id="add">
                      <br>
                      <div class="col-md-6">
                        <?php echo form_open('master/add_status_pegawai');?>
                        <p class="text-danger">Semua data harus diisi! <br> Kode Status Kepegawaian TIDAK BOLEH SAMA</p>
                        <div class="form-group">
                          <label>Kode Status Kepegawaian</label>
                          <input type="text" placeholder="Masukkan Kode Status Kepegawaian" name="kode" class="form-control" required >
                        </div>
                        <div class="form-group">
                          <label>Nama Status Kepegawaian</label>
                          <input type="text" placeholder="Masukkan Nama Status Kepegawaian" name="status_pegawai" class="form-control" required >
                        </div>
                        <div class="form-group">
                          <label>Masa Habis Kerja</label>
                          <input type="number" placeholder="Masukkan Masa Habis Kerja" name="masa" class="form-control" required >
                          <div class="row">
                            <div class="col-xs-4">
                              <div class="radio icheck">
                                <label>
                                  <input type="radio" name="masahbs" value="Hari" required> Hari
                                </label>
                              </div>
                            </div>
                            <div class="col-xs-4">
                              <div class="radio icheck">
                                <label>
                                  <input type="radio" name="masahbs" value="Minggu" required> Minggu
                                </label>
                              </div>
                            </div>
                            <div class="col-xs-4">
                              <div class="radio icheck">
                                <label>
                                  <input type="radio" name="masahbs" value="Bulan" required> Bulan
                                </label>
                              </div>
                            </div>
                            <div class="col-xs-4">
                              <div class="radio icheck">
                                <label>
                                  <input type="radio" name="masahbs" value="Tahun" required checked> Tahun
                                </label>
                              </div>
                            </div>
                            <div class="col-xs-4">
                              <div class="radio icheck">
                                <label>
                                  <input type="radio" name="masahbs" value="Umur" required> Umur
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>Gaji Pokok (%)</label>
                          <input type="number" placeholder="Masukkan Berapa % Gaji Pokoknya" name="gaji" class="form-control" required max="100">
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                        </div>
                        <?php echo form_close();?>
                      </div>
                    </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (!empty($this->session->flashdata('msgsc'))) {
                    echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
                  }elseif (!empty($this->session->flashdata('msgerr'))) {
                    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
                  }
                  ?>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Status Kepegawaian</th>
                        <th>Nama Status Kepegawaian</th>
                        <th>Masa Habis Kerja</th>
                        <th>Gaji Pokok (%)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($stat as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="20%">'.$l->kode_status.'</td>
                                  <td>'.$l->nama.'</td>
                                  <td>'.$l->masa_habis.'</td>
                                  <td>'.$l->gaji_pokok.'</td>
                                  <td class="text-center" width="10%">';
                                  if ($l->status == "aktif") {
                                    echo form_open('master/status_status_pegawai').'
                                    <input type="hidden" name="id" value="'.$l->id_status_pegawai.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>'
                                    .form_close();
                                  }else{
                                    echo form_open('master/status_status_pegawai').'
                                    <input type="hidden" name="id" value="'.$l->id_status_pegawai.'">
                                    <input type="hidden" name="act" value="aktif">
                                    <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>'
                                    .form_close();
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%">
                                    <a href="#edt'.$n.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data"></i></a>
                                    <a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a>
                                  </td>
                                </tr>
                                <div id="edt'.$n.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Edit Data '.$l->nama.'</h4>
                                      </div>
                                      <div class="modal-body">
                                        <p class="text-danger">Semua data harus diisi! <br> Kode Level TIDAK BOLEH SAMA</p>
                                        '.form_open('master/edt_status_pegawai').'
                                        <input type="hidden" name="id" value="'.$l->id_status_pegawai.'">
                                        <input type="hidden" name="kode_old" value="'.$l->kode_status.'">
                                        <div class="form-group">
                                          <label>Kode Status Kepegawaian</label>
                                          <input placeholder="Masukkan Kode Status Kepegawaian" name="kode" value="'.$l->kode_status.'" class="form-control" required >
                                        </div>
                                        <div class="form-group">
                                          <label>Nama Status Kepegawaian</label>
                                          <input placeholder="Masukkan Nama Status Kepegawaian" name="status_pegawai" value="'.$l->nama.'" class="form-control" required >
                                        </div>
                                        <div class="col-xs-4">
                                          <div class="radio icheck">
                                            <label>
                                              <input type="radio" name="masahbs" value="Umur" required> Umur
                                            </label>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        
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
                                        <p>Apakah anda yakin akan menghapus data status kepegawaian dengan nama <b>'.$l->nama.'</b> ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('master/del_status_pegawai').'
                                        <input type="hidden" name="id" value="'.$l->id_status_pegawai.'">
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
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 