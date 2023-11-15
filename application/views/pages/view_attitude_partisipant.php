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
        <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-gear"></i> Rancangan
        <small><?php echo $nama; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/concept_sikap');?>"><i class="fa fa-flask"></i> Rancangan Penilaian Sikap</a></li>
        <li><a href="<?php echo base_url('pages/view_concept_sikap/'.$kode);?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
        <li class="active"> Partisipan <?php echo $emp['nama']; ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Partisipan <?php echo $emp['nama']; ?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
                  <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add"><i class="fa fa-plus"></i> Tambah Partisipan</button>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="collapse" id="add">
                        <br>
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                          <?php 
                          $pr=array_filter(explode(';', $tabel['partisipan']));
                          /*Diri
                          $kry=$this->db->get_where('karyawan',array('status_emp'=>'aktif'))->result();*/
                          $nk=$emp['nik'];
                          $kry=$this->db->query("SELECT id_karyawan,nama FROM karyawan WHERE status_emp = 'aktif' AND nik != '$nk'")->result();
                          if (count($pr) > 0) {
                            foreach ($pr as $p) {
                              $p1=explode(':', $p);
                              $resx1[$p1[1]]=$p1[1];
                            }
                            foreach ($kry as $k) {
                              if (!in_array($k->id_karyawan, $resx1)) {
                                $op12[$k->id_karyawan]=$k->nama;
                              }else{
                                $sa[]=1;
                              }
                            }
                          }else{
                            foreach ($kry as $k) {
                                $op12[$k->id_karyawan]=$k->nama;
                            }
                          }
                          if (isset($sa)) {
                            if (count($sa) == count($kry)) {
                              echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Karyawan sudah dimasukkan semua</div>';
                            }else{
                              echo form_open('setting/add_part_attd_concept');
                              echo '<div class="form-group">
                              <label>Pilih Karyawan</label>';
                              $sel1 = array(NULL);
                              $ex1 = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Karyawan','required'=>'required','style'=>'width:100%;');
                              echo form_dropdown('karyawan[]',$op12,$sel1,$ex1);
                              echo ' 
                              </div>
                              <div class="form-group">
                              <label>Sebagai</label>';
                              $op=array(NULL=>'Pilih Opsi','ATS'=>'Atasan','BWH'=>'Bawahan','RKN'=>'Rekan Kerja');
                              $sel = array(NULL);
                              $ex = array('class'=>'form-control select2','required'=>'required','style'=>'width:100%;');
                              echo form_dropdown('opsi',$op,$sel,$ex);
                              echo ' 
                              </div>
                              <div class="form-group">
                              <input type="hidden" name="idk" value="'.$emp['id_karyawan'].'">
                              <input type="hidden" name="kode" value="'.$kode.'">
                              <input type="hidden" name="nik" value="'.$emp['nik'].'">
                              <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                              </div>
                              '.form_close();
                            }
                          }else{
                              echo form_open('setting/add_part_attd_concept');
                              echo '<div class="form-group">
                              <label>Pilih Karyawan</label>';
                              $sel1 = array(NULL);
                              $ex1 = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Karyawan','required'=>'required','style'=>'width:100%;');
                              echo form_dropdown('karyawan[]',$op12,$sel1,$ex1);
                              echo ' 
                              </div>
                              <div class="form-group">
                              <label>Sebagai</label>';
                              $op=array(NULL=>'Pilih Opsi','ATS'=>'Atasan','BWH'=>'Bawahan','RKN'=>'Rekan Kerja');
                              $sel = array(NULL);
                              $ex = array('class'=>'form-control select2','required'=>'required','style'=>'width:100%;');
                              echo form_dropdown('opsi',$op,$sel,$ex);
                              echo ' 
                              </div>
                              <div class="form-group">
                              <input type="hidden" name="idk" value="'.$emp['id_karyawan'].'">
                              <input type="hidden" name="kode" value="'.$kode.'">
                              <input type="hidden" name="nik" value="'.$emp['nik'].'">
                              <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                              </div>
                              '.form_close();
                            
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
                  if (count($pr) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                   echo form_open('setting/del_many_part_attd');
                    
                  ?>
                  <!--
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label>
                    <ul>
                      <li>Pilih Jabatan untuk menentukan bobot setiap indikator</li>
                      <li>Bobot Akhir Penilaian hanya dapat di kaitkan melalui <a href="<?php echo base_url('pages/master_level_jabatan');?>">Master Level Jabatan</a></li>
                      <li>Kaitkan Rancangan Penilaian dengan Agenda melalui menu <a href="<?php echo base_url('pages/agenda');?>">Agenda Penilaian > Daftar Agenda</a></li>
                    </ul>
                  </div>-->
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <table class="table table-condensed">
                        <?php 
                          if ($tabel['bobot_ats'] != 0) {
                            echo '<tr>
                                    <th class="bg-orange text-center">Bobot Atasan</th>
                                    <td class="text-center"><b>'.$tabel['bobot_ats'].'%</b></td>
                                  </tr>';
                          }
                          if ($tabel['bobot_bwh'] != 0) {
                            echo '<tr>
                                    <th class="bg-orange text-center">Bobot Bawahan</th>
                                    <td class="text-center"><b>'.$tabel['bobot_bwh'].'%</b></td>
                                  </tr>';
                          }
                          if ($tabel['bobot_rkn'] != 0) {
                            echo '<tr>
                                    <th class="bg-orange text-center">Bobot Rekan</th>
                                    <td class="text-center"><b>'.$tabel['bobot_rkn'].'%</b></td>
                                  </tr>';
                          }
                        ?>
                      </table>
                    </div>
                  </div>
                  <div  style="overflow:auto; max-height: 600px;">
                  <table class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr class="bg-blue">
                        <th>No.</th>
                        <th><?php if (in_array($access['l_ac']['del'], $access['access'])) {
                          echo '<label class="contx" data-toggle="tooltip" title="Pilih Semua Partisipan">NIK
                            <input type="checkbox" id="all_part">
                            <span class="checkmark"></span>
                          </label>';
                        }else{
                          echo 'NIK';
                        }?>  
                        </th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Sebagai</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        
                        foreach ($pr as $a) {
                          $b=explode(':', $a);
                          $e=$this->db->get_where('karyawan',array('id_karyawan'=>$b[1]))->row_array();
                          $jbt=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$e['jabatan']))->row_array();
                          $lok=$this->db->get_where('master_loker',array('kode_loker'=>$e['unit']))->row_array();
                          if ($b[0] == "ATS") {
                            $sb="Atasan";
                          }elseif ($b[0] == "BWH") {
                            $sb="Bawahan";
                          }elseif ($b[0] == "RKN"){
                            $sb="Rekan Kerja";
                          }else{
                            $sb="Diri Sendiri";
                          }
                          echo '<tr>
                            <td width="4%">'.$n.'.</td>
                            <td>';
                            if ($b[0] == "DRI") {
                              echo '<label class="contx" data-toggle="tooltip" title="Tidak Dapat Dihapus"><i class="fa fa-lock" style="color:red;"></i> '.$e['nik'].'</label>';
                            }else{
                              if (in_array($access['l_ac']['del'], $access['access'])) {
                                echo '<label class="contx">'.$e['nik'].'
                                  <input type="checkbox" name="part['.$n.']" value="'.$a.'" id="part'.$n.'">
                                  <span class="checkmark"></span>
                                </label>';
                              }else{
                                echo $e['nik'];
                              }
                            } 
                            echo '</td>
                            <td>'.$e['nama'].'</td>
                            <td>'.$jbt['jabatan'].'</td>
                            <td>'.$lok['nama'].'</td>
                            <td>'.$sb.'</td>
                          </tr>
                          ';
                          $n++; 
                        }
                        
                      ?>
                    </tbody>
                  </table>
                </div>
                  <?php 
                  
                } ?>
                </div>
              </div>
            </div>
            <?php 
            if (count($tabel) != 0) {
              if (in_array($access['l_ac']['del'], $access['access'])) {              
            ?>

            <div class="box-footer">
              <input type="hidden" name="kode" value="<?php echo $kode;?>">
              <input type="hidden" name="nik" value="<?php echo $emp['nik'];?>">
              <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-check-square"></i> Hapus Pilihan</button>
              <?php echo form_close();?>
            </div>
            <?php }} ?>
          </div>
        </div>
      </div>
    </section>
  </div> 
