  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
        Setting Indikator
        <small><?php echo $indi['kode_indikator'];?></small>
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
              <h3 class="box-title"><i class="fa fa-file-text-o"></i> Indikator <?php echo $indi['kode_indikator'];?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (!empty($this->session->flashdata('msgsc'))) {
                    echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
                  }elseif (!empty($this->session->flashdata('msgerr'))) {
                    echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
                  }
                  ?>
                  <table class="table table-hover">
                    <tr>
                      <th>Indikator</th>
                      <td><?php echo $indi['kpi'];?></td>
                    </tr>
                    <tr>
                      <th>Cara Pengukuran</th>
                      <td><?php echo $indi['cara_mengukur'];?></td>
                    </tr>
                    <tr>
                      <th>Sumber Data</th>
                      <td><?php echo $indi['sumber'];?></td>
                    </tr>
                    <tr>
                      <th>Pelaporan</th>
                      <td><?php echo $indi['periode_pelaporan'];?></td>
                    </tr>
                    <tr>
                      <th>Polarisasi</th>
                      <td><?php echo $indi['polarisasi'];?></td>
                    </tr><tr>
                      <th>Sifat</th>
                      <td><?php echo $indi['sifat'];?></td>
                    </tr>
                    <tr>
                      <th>Target</th>
                      <td><?php echo $indi['bobot'];?></td>
                    </tr>
                    <tr>
                      <th>Satuan</th>
                      <td><?php echo $indi['satuan'];?></td>
                    </tr>
                  </table>
                  <label>List Penanggung Jawab</label>
                  <div class="list-group">
                  <?php 
                    $level=explode(",", $indi['penanggungjawab']);
                    $kk=explode(",", $indi['id_karyawan']);
                    $dtl=$this->db->get('master_jabatan')->result();
                    echo '<table class="table table-hover">';
                    $n=1;
                    foreach ($dtl as $l) {
                      echo '<tr>
                                <th width="1%"><input type="checkbox" name="level" value="'.$l->kode_jabatan.'" ';
                                foreach ($level as $lv) {
                                  if ($lv == $l->jabatan) {
                                    echo 'checked';
                                  }
                                }
                                echo '></th>
                                <td><a href="#jb'.$n.'" data-toggle="collapse"> '.$l->jabatan.'</a>';
                                $jb=$l->jabatan;
                                $kar=$this->db->query("SELECT id_karyawan,nama FROM karyawan WHERE jabatan = '$jb'")->result();
                                echo '<div class="collapse" id="jb'.$n.'">
                                  <div class="list-group">';
                                        foreach ($kar as $k) {
                                          echo '<a href="#" class="list-group-item"><label class="checkbox-inline"><input type="checkbox" name="level" value="'.$k->id_karyawan.'" ';
                                          foreach ($kk as $x) {
                                            if ($x == $k->id_karyawan) {
                                              echo 'checked';
                                            }
                                          }
                                          echo '> '.$k->nama.'</label></a>';
                                        }
                                      echo '
                                  </div> 
                                </div>';
                                echo '</td>
                              </tr>';
                      
                      $n++;
                    }
                    echo '</table>';
                  ?>
                  </div>
                  <!--
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
                        <th>Target</th>
                        <th>Satuan</th>
                        <th>Taggal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($indikator as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="30%"><a href="'.base_url('master/view_setting_master_indikator/'.$l->kode_indikator).'">'.$l->kpi.'</a></td>
                                  <td width="30%">'.$l->cara_mengukur.'</td>
                                  <td>'.$l->sumber.'</td>
                                  <td>'.$l->periode_pelaporan.'</td>
                                  <td>'.$l->polarisasi.'</td>
                                  <td>'.$l->sifat.'</td>
                                  <td>'.$l->target.'</td>
                                  <td>'.$l->satuan.'</td>
                                  <td><i class="fa fa-pencil"></i> '.date("d/m/Y",strtotime($l->create_date)).'<br><i class="fa fa-edit"></i> '.date("d/m/Y H:i:s",strtotime($l->update_date)).'</td>
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
                  </table>-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 