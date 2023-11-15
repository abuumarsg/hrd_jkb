  <div class="content-wrapper">
    <?php 
    if (!empty($this->session->flashdata('msgsc'))) {
      echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
    }elseif (!empty($this->session->flashdata('msgerr'))) {
      echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
    }elseif (!empty($this->session->flashdata('msgwrx'))) {
      echo '<div id="al" class="modal fade" role="dialog">
      <div class="modal-dialog modal-warning">
      <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title text-center"><i class="fa fa-bullhorn"></i> Pemberitahuan</h4>
      </div>
      <div class="modal-body">
      <p>Karyawan Berikut Ini Belum Mempunyai Partisipan :</p>
      <ol>';
      foreach ($this->session->flashdata('msgwrx') as $ale) {
        echo '<li>[<a href="'.base_url('pages/view_employee/'.$ale['nik']).'" target="blank">'.$ale['nik'].'</a>]'.$ale['nama'].'</li>';
      }
      echo '</ol>
      <p>Silahkan Cek Pada : 
        <ul>
          <li>Menu <b><a href="'.base_url('pages/master_jabatan').'" target="blank">Master Data > Master Jabatan</a></b> Untuk menentukan atasan dari Karyawan tersebut.</li>
          <li>Menu <b><a href="'.base_url('pages/employee').'" target="blank">Karyawan > Data Karyawan</a></b> Untuk menentukan jabatan dari Karyawan tersebut.</li>
        </ul>
      </p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
      </div>
      </div>
      </div>';
    }
    ?>
    <section class="content-header">
      <h1>
        <i class="fa fa-flask"></i> Rancangan 
        <small>Penilaian Sikap</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Rancangan Penilaian Sikap</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Rancangan Penilaian Sikap</h3>
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
                        <?php if(in_array($access['l_ac']['add'], $access['access'])){?>
                        <button class="btn btn-primary btn-flat" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-gears"></i> Rancangan Manual</button>
                        <a href="<?php echo base_url('setting/generate_new_a_concept');?>" id="save2" class="btn btn-danger btn-flat"><i class="fa fa-qrcode"></i> Generate Nama Otomatis</a>
                      <?php } ?>
                      </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                    </div>
                  </div>
                    <?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
                    <div class="collapse" id="add">
                      <br>
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <?php echo form_open('setting/add_a_concept');?>
                        <p class="text-danger">Semua data harus diisi!</p>
                        <div class="form-group">
                          <label>Nama Rancangan Sikap</label>
                          <input type="text" placeholder="Masukkan Nama Rancangan Sikap" name="nama" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                          <button type="submit" id="save1" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                        </div>
                        <?php echo form_close();?>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">

                  <?php 
                  if (count($attd) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Rancangan Sikap untuk melakukan setting Rancangan Penilaian Sikap</div>
                  
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Rancangan Sikap</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($attd as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td><a href="'.base_url('pages/view_concept_sikap/'.$l->kode_c_sikap).'">'.$l->nama.'</a></td>
                                  <td class="text-center"><label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Update Data Tanggal"><i class="fa fa-edit"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->update_date).' WIB</td>
                                  <td class="text-center">';
                                  if ($l->nama_tabel != NULL || $l->nama_tabel != '') {
                                    echo '<label class="label label-success">Data Sudah Ada</label>';
                                  }else{
                                    echo '<label class="label label-danger">Data Belum Ada</label>';
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%">';
                                  if ($l->status == "aktif") {
                                    echo form_open('setting/status_a_concept').'
                                    <input type="hidden" name="id" value="'.$l->id_c_sikap.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button '.$access['b_stt'].' class="stat scc"><i class="fa fa-toggle-on"></i></button>'
                                    .form_close();
                                  }else{
                                    echo form_open('setting/status_a_concept').'
                                    <input type="hidden" name="id" value="'.$l->id_c_sikap.'">
                                    <input type="hidden" name="act" value="aktif">
                                    <button '.$access['b_stt'].' class="stat err"><i class="fa fa-toggle-off"></i></button>'
                                    .form_close();
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%">';
                                    if (in_array($access['l_ac']['edt'], $access['access'])) {
                                      echo '<a href="#edt'.$n.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data"></i></a> ';
                                    }
                                    if (in_array($access['l_ac']['del'], $access['access'])) {
                                      echo '<a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a>';
                                    }
                                    echo $access['n_all'];
                                  echo '</td>
                                </tr>';
                                if (in_array($access['l_ac']['edt'], $access['access'])) {
                                  echo '<div id="edt'.$n.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Edit Data '.$l->nama.'</h4>
                                      </div>
                                      <div class="modal-body">
                                        '.form_open('setting/edt_a_concept').'
                                        <input type="hidden" name="id" value="'.$l->id_c_sikap.'">
                                        <div class="form-group">
                                          <label>Nama Rancangan Output</label>
                                          <input type="text" placeholder="Masukkan Nama Setting" name="nama" value="'.$l->nama.'" class="form-control" required="required">
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
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
                                        <p>Apakah anda yakin akan menghapus data rancangan penilaian output dengan nama <b>'.$l->nama.'</b> ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('setting/del_a_concept').'
                                        <input type="hidden" name="id" value="'.$l->id_c_sikap.'">
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