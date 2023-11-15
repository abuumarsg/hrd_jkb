  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Master Data 
        <small>Level Jabatan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Data Level Jabatan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Level Jabatan</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Level</button>
                  
                    <div class="collapse" id="add">
                      <br>
                      <div class="col-md-4">
                        <?php echo form_open('master/add_level_jabatan');?>
                        <p class="text-danger">Semua data harus diisi! <br> Kode Level TIDAK BOLEH SAMA</p>
                        <div class="form-group">
                          <label>Kode Level Jabatan</label>
                          <input type="text" placeholder="Masukkan Kode Level Jabatan" name="kode" class="form-control" required >
                        </div>
                        <div class="form-group">
                          <label>Nama Level Jabatan</label>
                          <input type="text" placeholder="Masukkan Nama Level Jabatan" name="level" class="form-control" required >
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
                        <th>Kode Level Jabatan</th>
                        <th>Level Jabatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($level as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="17%">'.$l->kode_level.'</td>
                                  <td>'.$l->level_jabatan.'</td>
                                  <td class="text-center" width="10%">';
                                  if ($l->status == "aktif") {
                                    echo form_open('master/status_level_jabatan').'
                                    <input type="hidden" name="id" value="'.$l->id_level.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>'
                                    .form_close();
                                  }else{
                                    echo form_open('master/status_level_jabatan').'
                                    <input type="hidden" name="id" value="'.$l->id_level.'">
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
                                        <h4 class="modal-title text-center">Edit Data '.$l->level_jabatan.'</h4>
                                      </div>
                                      <div class="modal-body">
                                        <p class="text-danger">Semua data harus diisi! <br> Kode Level TIDAK BOLEH SAMA</p>
                                        '.form_open('master/edt_level_jabatan').'
                                        <input type="hidden" name="id" value="'.$l->id_level.'">
                                        <input type="hidden" name="kode_old" value="'.$l->kode_level.'">
                                        <div class="form-group">
                                          <label>Kode Level Jabatan</label>
                                          <input type="text" placeholder="Masukkan Kode Level Jabatan" name="kode" value="'.$l->kode_level.'" class="form-control" required >
                                        </div>
                                        <div class="form-group">
                                          <label>Nama Level Jabatan</label>
                                          <input type="text" placeholder="Masukkan Nama Level Jabatan" name="level" value="'.$l->level_jabatan.'" class="form-control" required >
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
                                        <p>Apakah anda yakin akan menghapus data level jabatan dengan nama <b>'.$l->level_jabatan.'</b> ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('master/del_level_jabatan').'
                                        <input type="hidden" name="id" value="'.$l->id_level.'">
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