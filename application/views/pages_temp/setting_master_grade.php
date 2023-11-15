  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Master Data 
        <small>Grade</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Grade</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Grade</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Grade</button>
                    <div class="collapse" id="add">
                      <br>
                      <div class="col-md-4">
                        <?php echo form_open('master/add_grade');?>
                        <p class="text-danger">Semua data harus diisi! <br> Kode grade TIDAK BOLEH SAMA</p>
                        <div class="form-group">
                          <label>Kode grade Organisasi</label>
                          <input type="text" placeholder="Masukkan Kode grade" name="kode" class="form-control" required >
                        </div>
                        <div class="form-group">
                          <label>Nama grade Organisasi</label>
                          <input type="text" placeholder="Masukkan Nama grade Organisasi" name="grade" class="form-control" required >
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
                        <th>Kode Grade</th>
                        <th>Grade / Level</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($grade as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="17%">'.$l->kode_grade.'</td>
                                  <td>'.$l->grade.'</td>
                                  <td class="text-center" width="10%">';
                                  if ($l->status == "aktif") {
                                    echo form_open('master/status_grade').'
                                    <input type="hidden" name="id" value="'.$l->id_grade.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>'
                                    .form_close();
                                  }else{
                                    echo form_open('master/status_grade').'
                                    <input type="hidden" name="id" value="'.$l->id_grade.'">
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
                                        <h4 class="modal-title text-center">Edit Data '.$l->grade.'</h4>
                                      </div>
                                      <div class="modal-body">
                                        <p class="text-danger">Semua data harus diisi! <br> Kode grade TIDAK BOLEH SAMA</p>
                                        '.form_open('master/edt_grade').'
                                        <input type="hidden" name="id" value="'.$l->id_grade.'">
                                        <input type="hidden" name="kode_old" value="'.$l->kode_grade.'">
                                        <div class="form-group">
                                          <label>Kode grade Organisasi</label>
                                          <input type="text" placeholder="Masukkan Kode grade" name="kode" value="'.$l->kode_grade.'" class="form-control" required >
                                        </div>
                                        <div class="form-group">
                                          <label>Nama grade Organisasi</label>
                                          <input type="text" placeholder="Masukkan Nama grade Organisasi" name="grade" value="'.$l->grade.'" class="form-control" required >
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
                                        <p>Apakah anda yakin akan menghapus data grade organisasi dengan nama <b>'.$l->grade.'</b> ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('master/del_grade').'
                                        <input type="hidden" name="id" value="'.$l->id_grade.'">
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