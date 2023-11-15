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
        <small>Kuisioner Aspek Sikap <?php echo $nama;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/master_aspek');?>"><i class="fa fa-database"></i> Master Aspek Sikap</a></li>
        <li class="active">Aspek Sikap <?php echo $nama;?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Kuisioner <?php echo $nama;?></h3>
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
                        <?php 
                          if (in_array($access['l_ac']['add'], $access['access'])) {
                            echo '<button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Kuisioner</button> ';
                          }
                          if (count($kuisioner) > 0) {
                        ?>
                        <button class="btn btn-warning btn-flat" type="button" data-toggle="collapse" data-target="#set" aria-expanded="false" aria-controls="import"><i class="fa fa-arrows-v"></i> Set Semua Batasan</button><?php } ?>
                      </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="collapse" id="set">
                      <br>
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <?php echo form_open('master/set_batasan_kuisioner');?>
                        <div class="form-group">
                          <label>Batasan Nilai</label>
                          <div class="row">
                            <div class="col-md-6">
                              <label>Batas Bawah (%)</label>
                              <input type="number" max="100" min="0" placeholder="Masukkan Batas Bawah" name="bawah" class="form-control">
                            </div>
                            <div class="col-md-6">
                              <label>Batas Atas (%)</label>
                              <input type="number" max="100" min="0" placeholder="Masukkan Batas Atas" name="atas" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <input type="hidden" name="kode" value="<?php echo $kode;?>">
                          <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                        </div>
                        <?php echo form_close();?>
                      </div>
                    </div>
                    </div>
                  </div>
                  <?php if (in_array($access['l_ac']['add'], $access['access'])) {?> 

                  <div class="row">
                    <div class="col-md-12">
                      <div class="collapse" id="add">
                      <br>
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <?php echo form_open('master/add_kuisioner');?>
                        <div class="form-group">
                          <label>Kuisioner</label>
                          <textarea class="form-control" name="kuisioner" placeholder="Kuisioner"></textarea>
                        </div>
                        <div class="form-group">
                          <label>Tipe Form Untuk Jabatan</label>
                          <?php
                          $dtf=$this->db->get_where('master_tipe',array('status'=>'aktif'))->result();
                          $op[NULL]='Pilih Tipe';
                          foreach ($dtf as $d) {
                            $op[$d->kode_tipe]=$d->nama_tipe;
                          } 
                          $sel = array(NULL);
                          $ex = array('class'=>'form-control select2','placeholder'=>'Jenis Kelamin','required'=>'required','style'=>'width:100%');
                          echo form_dropdown('tipe',$op,$sel,$ex);
                          ?>
                        </div>
                        <div class="form-group">
                          <label>Batasan Nilai</label>
                          <div class="row">
                            <div class="col-md-6">
                              <label>Batas Bawah (%)</label>
                              <input type="number" max="100" min="0" placeholder="Batas Bawah" name="bawah" class="form-control" value="45">
                            </div>
                            <div class="col-md-6">
                              <label>Batas Atas (%)</label>
                              <input type="number" max="100" min="0" placeholder="Batas Atas" name="atas" class="form-control" value="85">
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <input type="hidden" name="kode" value="<?php echo $kode;?>">
                          <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                        </div>
                        <?php echo form_close();?>
                      </div>
                    </div>
                    </div>
                  </div><?php } ?>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($kuisioner) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kuisioner</th>
                        <th>Keterangan</th>
                        <th>Batasan Nilai</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($kuisioner as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td>'.$l->kuisioner.'<br>';
                                  $dta1=$this->db->get_where('master_tipe',array('kode_tipe'=>$l->kode_tipe))->row_array();
                                  echo '<small><i class="fa fa-check-circle text-green"></i> '.$dta1['nama_tipe'].'</small>';
                                  echo '</td>
                                  <td class="text-center">';
                                  $dta=$this->db->get_where('master_aspek_sikap',array('kode_aspek'=>$l->kode_aspek))->row_array();
                                  echo '<label class="label label-success">Terkait '.$dta['nama_aspek'].'</label>';

                                  echo '</td>
                                  <td class="text-center">'.$l->bawah.'% - '.$l->atas.'%</td>
                                  <td class="text-center" width="10%">'.form_open('master/status_kuisioner').'
                                  <input type="hidden" name="kode" value="'.$l->kode_aspek.'">';
                                  if ($l->status == "aktif") {
                                    echo '<input type="hidden" name="id" value="'.$l->id_kuisioner.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>';
                                  }else{
                                    echo '<input type="hidden" name="id" value="'.$l->id_kuisioner.'">
                                    <input type="hidden" name="act" value="aktif">
                                    <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>';
                                  }
                                  echo form_close().'</td>
                                  <td class="text-center"><label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->update_date).' WIB</label></td>
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
                                  <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Edit Data Kuisioner</h4>
                                      </div>
                                      <div class="modal-body">
                                        '.form_open('master/edt_kuisioner').'
                                        <input type="hidden" name="id" value="'.$l->id_kuisioner.'">
                                        <div class="form-group">
                                          <label>Kuisioner</label>
                                          <textarea class="form-control" name="kuisioner" placeholder="Kuisioner">'.$l->kuisioner.'</textarea>
                                        </div>
                                        <div class="form-group">
                                          <label>Tipe Form Untuk Jabatan</label>';
                                          $dtf1=$this->db->get_where('master_tipe',array('status'=>'aktif'))->result();
                                          $op1[NULL]='Pilih Tipe';
                                          foreach ($dtf1 as $d1) {
                                            $op1[$d1->kode_tipe]=$d1->nama_tipe;
                                          } 
                                          $sel1 = array($l->kode_tipe);
                                          $ex1 = array('class'=>'form-control select2','placeholder'=>'Jenis Kelamin','required'=>'required','style'=>'width:100%');
                                          echo form_dropdown('tipe',$op1,$sel1,$ex1);
                                        echo '</div>
                                        <div class="form-group">
                                          <label>Batasan Nilai</label>
                                          <div class="row">
                                            <div class="col-md-6">
                                              <label>Batas Bawah (%)</label>
                                              <input type="number" max="100" min="0" placeholder="Batas Bawah" name="bawah" class="form-control" value="'.$l->bawah.'">
                                            </div>
                                            <div class="col-md-6">
                                              <label>Batas Atas (%)</label>
                                              <input type="number" max="100" min="0" placeholder="Batas Atas" name="atas" class="form-control" value="'.$l->atas.'">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <input type="hidden" name="kode" value="'.$l->kode_aspek.'">
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
                                        <p>Apakah anda yakin akan menghapus data Kuisioner Tersebut ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('master/del_kuisioner').'
                                        <input type="hidden" name="kode" value="'.$l->kode_aspek.'">
                                        <input type="hidden" name="id" value="'.$l->id_kuisioner.'">
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