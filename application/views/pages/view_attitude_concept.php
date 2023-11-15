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
        <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-flask"></i> Rancangan
        <small><?php echo $nama; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/concept_sikap');?>"><i class="fa fa-flask"></i> Rancangan Penilaian Sikap</a></li>
        <li class="active"><?php echo $nama; ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border"> 
              <h3 class="box-title"><i class="fa fa-gear"></i> <?php echo $nama; ?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12"> 
                  <?php echo form_open('setting/sync_bobot_s');
                  echo '<input type="hidden" name="kode" value="'.$kode.'">';
                  if (in_array($access['l_ac']['add'], $access['access'])) {
                    echo '<button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add"><i class="fa fa-plus"></i> Tambah Karyawan</button>';
                  }
                  ?>
                  
                  <button class="btn btn-info btn-flat" type="submit" id="save1"><i class="fa fa-refresh"></i> Sync Bobot Sikap</button>
                  <?php echo form_close();
                  if (in_array($access['l_ac']['add'], $access['access'])) {
                  ?>
                  <div class="row">
                        <div class="col-md-12">
                          <div class="collapse" id="add">
                            <br>
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                              <?php 
                              $kry=$this->db->get_where('karyawan',array('status_emp'=>'aktif'))->result();
                              foreach ($tabel as $vx1) {
                                $resx1[$vx1->id_karyawan]=$vx1->id_karyawan;
                              }
                              foreach ($kry as $k) {
                                if (!in_array($k->id_karyawan, $resx1)) {
                                  $op12[$k->id_karyawan]=$k->nama;
                                }else{
                                  $sa[]=1;
                                }
                              }
                              if (!isset($sa) || count($kry) == 0) {
                                echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak Ada Karyawan</div>';
                              }else{
                                if (count($sa) == count($kry)) {
                                  echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Karyawan sudah dimasukkan semua</div>';
                                }else{
                                  echo form_open('setting/add_emp_attd_concept');
                                  echo '<div class="form-group">';
                                  $sel1 = array(NULL);
                                  $ex1 = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Karyawan','required'=>'required','style'=>'width:100%;');
                                  echo form_dropdown('karyawan[]',$op12,$sel1,$ex1);
                                  echo ' 
                                  </div>
                                  <div class="form-group">
                                    <input type="hidden" name="kode" value="'.$kode.'">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                  </div>
                                  '.form_close().'';
                                }
                              }
                              ?>
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
                  if (count($tabel) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?> 
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih NIK untuk melihat dan melakukan perubahan pada partisipan</div>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr class="bg-blue">
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Jumlah Partisipan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($tabel as $l) {
                          $jbt=$this->db->get_where('master_jabatan',array('id_jabatan'=>$l->id_jabatan))->row_array();
                          $lok=$this->db->get_where('master_loker',array('id_loker'=>$l->id_loker))->row_array();
                          echo '<tr>
                            <td>'.$n.'.</td>
                            <td width="20%"><a href="'.base_url('pages/view_attitude_partisipant/'.$kode.'/'.$l->nik).'" data-toggle="tooltip" title="Klik Untuk Edit">'.$l->nik.'</a></td>
                            <td width="20%">'.$l->nama.'</td>
                            <td width="20%">'.$jbt['jabatan'].'</td>
                            <td width="20%">'.$lok['nama'].'</td>
                            <td width="30%">';
                            $jm=array_filter(explode(';', $l->partisipan));
                            echo count($jm);
                            echo ' Partisipan</td>
                            <td class="text-center">';
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
                                        <p>Apakah anda yakin tidak mengikutkan karyawan <b>'.$l->nama.'</b> dalam Rancangan Penilaian Sikap ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('setting/del_emp_attd_concept').'
                                        <input type="hidden" name="idk" value="'.$l->id_karyawan.'">
                                        <input type="hidden" name="kode" value="'.$kode.'">
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
                  <?php 
              } ?>
                </div>
              </div>
            </div>
                  
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 
