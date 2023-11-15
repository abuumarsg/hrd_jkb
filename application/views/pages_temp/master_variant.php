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
        <i class="fa fa-database"></i> Master Data 
        <small>Variant Jabatan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Data Variant Jabatan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-briefcase"></i> Daftar Variant Jabatan</h3>
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
                        <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Variant Jabatan</button>
                      </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                    </div>
                  </div>
                    <div class="collapse" id="add">
                      <br>
                      <div class="col-md-12">
                        <div class="col-md-6 pull-left">
                          <?php echo form_open('master/add_variant');?>
                          <p class="text-danger">Semua data harus diisi! <br> Kode Variant Jabatan TIDAK BOLEH SAMA</p>
                          <div class="form-group">
                            <label>Kode Variant Jabatan</label>
                            <input placeholder="Masukkan Kode Variant Jabatan" name="kode" class="form-control" required >
                          </div>
                          <div class="form-group">
                            <label>Nama Variant Jabatan</label>
                            <textarea class="form-control" placeholder="Masukkan Nama Variant Jabatan" name="nama" required></textarea>
                          </div>
                          <div class="form-group">
                            <label>Pilih Jabatan</label>
                            <select class="select2 form-control" style="width: 100%;" name="jabatan">
                              <?php
                                $data1=$this->db->query("SELECT * FROM master_jabatan WHERE status = 'aktif'")->result();
                                foreach ($data1 as $d1) {
                                  echo '<option value="'.$d1->kode_jabatan.'">'.$d1->jabatan.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Bertanggung Jawab Ke</label>
                            <select class="select2 form-control" style="width: 100%;" name="induk">
                              <option value="null">Belum Ada Variant</option>
                              <?php
                                $data=$this->db->query("SELECT * FROM master_variant_jabatan WHERE status = 'aktif'")->result();
                                foreach ($data as $d) {
                                  echo '<option value="'.$d->kode_variant.'">'.$d->variant.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Lokasi Kerja</label>
                            <select class="select2 form-control" style="width: 100%;" name="loker">
                              <option value="null">Belum Ada Lokasi</option>
                              <?php
                                $data1=$this->db->query("SELECT * FROM master_loker WHERE status = 'aktif'")->result();
                                foreach ($data1 as $d2) {
                                  echo '<option value="'.$d2->kode_loker.'">'.$d2->nama.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6 pull-right">
                          <label>Pilih Bawahan</label>
                          <table class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>Pilih</th>
                                <th>Kode</th>
                                <th>Nama Variant</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $daa=$this->db->get_where('master_variant_jabatan',array('status'=>'aktif'))->result();
                                foreach ($daa as $v) {
                                  echo '<tr>
                                    <td width="5%"><input type="checkbox" name="bawahan[]" value="'.$v->kode_variant.'"></td>
                                    <td width="10%">'.$v->kode_variant.'</td>
                                    <td width="50%">'.$v->variant.'</td>
                                  </tr>';
                                }
                              ?>
                            </tbody> 
                          </table>
                          <div class="form-group">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                          </div>
                          <?php echo form_close();?>
                        </div>
                      </div>  
                    </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($variant) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Klik Kode Variant untuk melihat daftar bawahan pada variant jabatan tersebut</div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Variant</th>
                        <th>Nama Variant</th>
                        <th>Jabatan</th>
                        <th>Bertanggung Jawab Ke</th>
                        <th>Lokasi Kerja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($variant as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="15%"><a href="#vw'.$n.'" data-toggle="modal">'.$l->kode_variant.'</a>
                                    <div id="vw'.$n.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Daftar Bawahan '.$l->variant.'</h4>
                                          </div>
                                          <div class="modal-body">
                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                              <thead>
                                                <tr>
                                                  <th>Kode</th>
                                                  <th>Nama Variant</th>
                                                </tr>
                                              </thead>
                                              <tbody>';
                                              $bwh=explode(";", $l->bawahan);
                                              foreach ($bwh as $b1) {
                                                $daa=$this->db->get_where('master_variant_jabatan',array('status'=>'aktif','kode_variant'=>$b1))->row_array();
                                                    echo '<tr>
                                                      <td width="10%">'.$daa['kode_variant'].'</td>
                                                      <td width="50%">'.$daa['variant'].'</td>
                                                    </tr>';
                                              }
                                              echo '</tbody> 
                                            </table>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                          </div>
                                        </div>

                                      </div>
                                    </div>
                                  </td>
                                  <td>'.$l->variant.'</td>
                                  <td>';
                                  $dt=$this->db->query("SELECT * FROM master_jabatan WHERE kode_jabatan = ?",array($l->kode_jabatan))->row_array();
                                  if ($dt != "") {
                                    echo $dt['jabatan'];
                                  }else{
                                    echo '<label class="label label-danger">Jabatan Tidak Ditemukan</label>';
                                  }
                                  echo '</td>
                                  <td>';
                                  $dd=$this->db->get_where('master_variant_jabatan',array('kode_variant'=>$l->induk_jabatan,'status'=>'aktif'))->row_array();
                                  if ($dd != "") {
                                    echo $dd['variant'];
                                  }else{
                                    echo '<label class="label label-danger">Tidak Ada Tanggung Jawab</label>';
                                  }
                                  echo '</td>
                                  <td>';
                                  $dd1=$this->db->get_where('master_loker',array('kode_loker'=>$l->kode_loker,'status'=>'aktif'))->row_array();
                                  if ($dd1 != "") {
                                    echo $dd1['nama'];
                                  }else{
                                    echo '<label class="label label-danger">Belum Ada Lokasi</label>';
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%">';
                                  if ($l->status == "aktif") {
                                    echo form_open('master/status_variant').'
                                    <input type="hidden" name="id" value="'.$l->id_variant.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>'
                                    .form_close();
                                  }else{
                                    echo form_open('master/status_variant').'
                                    <input type="hidden" name="id" value="'.$l->id_variant.'">
                                    <input type="hidden" name="act" value="aktif">
                                    <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>'
                                    .form_close();
                                  }
                                  echo '</td>
                                  <td width="10%">
                                    <a href="#edt'.$n.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data"></i></a>
                                    <a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a>
                                  </td>
                                </tr>
                                <div id="edt'.$n.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Edit Data '.$l->kode_variant.'</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="row">
                                              <div class="col-md-12">
                                                '.form_open('master/edt_variant').'
                                                <p class="text-danger">Semua data harus diisi! <br> Kode Variant TIDAK BOLEH SAMA</p>
                                                <input type="hidden" name="id" value="'.$l->id_variant.'">
                                                <input type="hidden" name="kode_old" value="'.$l->kode_variant.'">
                                                <div class="form-group">
                                                  <label>Kode Variant Jabatan</label>
                                                  <input placeholder="Masukkan Kode Variant Jabatan" name="kode" value="'.$l->kode_variant.'" class="form-control" required >
                                                </div>
                                                <div class="form-group">
                                                  <label>Nama Variant Jabatan</label>
                                                  <textarea class="form-control" placeholder="Masukkan Nama Variant Jabatan" name="nama" required>'.$l->variant.'</textarea>
                                                </div>
                                                <div class="form-group">
                                                  <label>Pilih Jabatan</label>
                                                  <select class="select2 form-control" style="width: 100%;" name="jabatan">';
                                                      $dat1=$this->db->query("SELECT * FROM master_jabatan WHERE status = 'aktif'")->result();
                                                      foreach ($dat1 as $da1) {
                                                        if ($da1->kode_jabatan == $l->kode_jabatan) {
                                                          echo '<option value="'.$da1->kode_jabatan.'" selected>'.$da1->jabatan.'</option>';
                                                        }else{
                                                          echo '<option value="'.$da1->kode_jabatan.'">'.$da1->jabatan.'</option>';
                                                        }
                                                      }
                                                  echo '</select>
                                                </div>
                                                <div class="form-group">
                                                  <label>Bertanggung Jawab Ke</label>
                                                  <select class="select2 form-control" style="width: 100%;" name="induk">
                                                    <option value="null">Belum Ada Variant</option>';
                                                      $dat2=$this->db->query("SELECT * FROM master_variant_jabatan WHERE status = 'aktif'")->result();
                                                      foreach ($dat2 as $d2) {
                                                        if ($d2->kode_variant == $l->induk_jabatan) {
                                                          echo '<option value="'.$d2->kode_variant.'" selected>'.$d2->variant.'</option>';
                                                        }else{
                                                          echo '<option value="'.$d2->kode_variant.'">'.$d2->variant.'</option>';
                                                        }
                                                      }
                                                  echo '</select>
                                                </div>
                                                <div class="form-group">
                                                  <label>Lokasi Kerja</label>
                                                  <select class="select2 form-control" style="width: 100%;" name="loker">
                                                    <option value="null">Belum Ada Lokasi</option>';
                                                      $dat3=$this->db->query("SELECT * FROM master_loker WHERE status = 'aktif'")->result();
                                                      foreach ($dat3 as $d3) {
                                                        if ($d3->kode_loker == $l->kode_loker) {
                                                          echo '<option value="'.$d3->kode_loker.'" selected>'.$d3->nama.'</option>';
                                                        }else{
                                                          echo '<option value="'.$d3->kode_loker.'">'.$d3->nama.'</option>';
                                                        }
                                                      }
                                                  echo '</select>
                                                </div>
                                                <div class="form-group">
                                                  <label>Pilih Bawahan</label><br>';
                                                        $daa1=$this->db->get_where('master_variant_jabatan',array('status'=>'aktif'))->result();
                                                        foreach ($daa1 as $v1) {
                                                          echo '<label class="checkbox-inline">
                                                          <input type="checkbox" name="bawahan[]" value="'.$v1->kode_variant.'" ';
                                                          $bw=explode(";", $l->bawahan);
                                                          foreach ($bw as $b) {
                                                            if ($b == $v1->kode_variant) {
                                                              echo 'checked';
                                                            }
                                                          }
                                                          echo '>  '.$v1->variant.'</label><br>';
                                                        }
                                                    echo '
                                                </div>
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
                                        <p>Apakah anda yakin akan menghapus data variant dengan nama <b>'.$l->variant.'</b> ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('master/del_variant').'
                                        <input type="hidden" name="id" value="'.$l->id_variant.'">
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