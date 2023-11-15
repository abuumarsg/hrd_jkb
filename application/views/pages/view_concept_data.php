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
        <li><a href="<?php echo base_url('pages/concept');?>"><i class="fa fa-flask"></i> Rancangan Penilaian Output</a></li>
        <li class="active"><?php echo $nama; ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar <?php echo $nama; ?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php if (in_array($access['l_ac']['add'], $access['access'])) {
                    echo '<button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#addc"><i class="fa fa-plus"></i> Tambah Jabatan</button>';
                  }?>
                  <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Konsolidasi<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Konsolidasi
                      </div>
                  <?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
                  <div class="collapse" id="addc">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="col-md-12">
                          <br>
                          <div class="box box-success">
                            <div class="box-header with-border">
                              <h3 class="box-title"><i class="fa fa-briefcase"></i> Tambah Jabatan</h3>
                            </div>
                            <div class="box-body" style="overflow-y:scroll; height: 500px;">
                              <?php 
                              if (count($jabatan) == 0) {
                                echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                              }else{
                                echo form_open('setting/edt_avl_config');
                              ?> 
                              <table class="table table-bordered table-striped table-hover">
                                <thead>
                                  <tr class="bg-blue">
                                    <th>No.</th>
                                    <th width="30%">
                                      <label class="contx" data-toggle="tooltip" title="Pilih Semua Jabatan">Nama Jabatan
                                        <input type="checkbox" id="all_jbt">
                                        <span class="checkmark"></span>
                                      </label>
                                    </th>
                                    <th>
                                      <label class="contx" data-toggle="tooltip" title="Pilih Semua Konsolidasi">Konsolidasi
                                        <input type="checkbox" id="all_kons">
                                        <span class="checkmark"></span>
                                      </label>
                                    </th>
                                    <th>Karyawan</th>
                                    <th>
                                      <label class="contx" data-toggle="tooltip" title="Pilih Semua Indikator">Indikator
                                        <input type="checkbox" id="all_ind">
                                        <span class="checkmark"></span>
                                      </label>
                                    </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $n=1;
                                    foreach ($concept_data as $dx) {
                                      $resx[$dx->id_jabatan]=$dx->id_jabatan;
                                      if ($dx->id_sub != NULL) {
                                        $res_s[$dx->id_jabatan][$dx->id_sub]=$dx->id_sub;
                                      }
                                    }
                                    foreach ($jabatan as $l) {
                                      $nmj=$l->kode_jabatan;
                                      $sub=$this->db->get_where('master_sub_jabatan',array('kode_jabatan'=>$nmj,'status'=>'aktif'))->result();
                                      if (count($sub) != 0 && isset($res_s[$l->id_jabatan])) {
                                        
                                        foreach ($sub as $sb) {
                                          if (!in_array($sb->id_sub, $res_s[$l->id_jabatan])) {
                                          $ky=$this->db->query("SELECT id_karyawan,nama FROM karyawan WHERE jabatan = '$nmj' AND kode_sub = '$sb->kode_sub'")->result();
                                          if (count($ky) > 0) {
                                            echo '<tr>
                                              <td width="3%">'.$n.'.</td>
                                              <td width="10%">
                                              <label class="contx">'.$l->jabatan;
                                              /*
                                              if (count($ky) != 0) {
                                                 echo '<input type="checkbox" id="jbt'.$n.'" name="jbt[]" value="'.$l->id_jabatan.'">';
                                              }   */
                                                echo '<input type="checkbox" id="jbt'.$n.'" name="jbt[]" value="'.$l->id_jabatan.'">
                                                <span class="checkmark"></span>
                                              </label>
                                              <input type="hidden" name="sub['.$l->id_jabatan.'][]" value="'.$sb->id_sub.'">  
                                              <label class="label label-info">Sub Jabatan <b style="color:red">'.$sb->nama_sub.'</b></label>
                                              </td>
                                              <td>';
                                              if (count($ky) != 0) {
                                                echo '
                                                <label class="contx" data-toggle="tooltip" title="Centang Jika Jabatan Ini Terkena Konsolidasi">Konsolidasi
                                                  <input type="checkbox" id="kons'.$n.'" name="konsolidasi['.$sb->id_sub.']" value="1">
                                                  <span class="checkmark"></span>
                                                </label>';
                                              }
                                              echo '</td>
                                              <td width="20%">';
                                              if (count($ky) == 0) {
                                                echo '<label class="label label-danger">Tidak Ada Karyawan</label>';
                                              }else{
                                                echo '<select class="form-control select2" name="emp['.$sb->id_sub.'][]" multiple="multiple" data-placeholder="Pilih Karyawan" style="width: 95%;" title="Kosongkan Jika Semua Karyawan">';
                                                foreach ($ky as $k) {
                                                  echo '<option value="'.$k->id_karyawan.'"> '.$k->nama.'</option>';
                                                }
                                                echo '</select>';
                                              }
                                              echo '</td>
                                              <td width="60%">';
                                              $kt=$l->kode_kategori;
                                              if (count($ky) == 0) {
                                                echo '<label class="label label-danger">Tidak Ada Karyawan</label>';
                                              }else{
                                                if (count($ind) == 0) {
                                                  echo '<label class="label label-warning">Tidak Ada Indikator Untuk Kategori Jabatan Tersebut</label>';
                                                }else{
                                                  echo '<div class="row">
                                                  <div class="col-md-12" style="overflow : scroll; max-height:300px;">
                                                  <table class="table table-bordered table-striped table-hover">
                                                          <thead>
                                                            <tr class="bg-green">
                                                              <th>
                                                                <label class="contx" data-toggle="tooltip" title="Pilih Semua Indikator Jabatan '.$sb->nama_sub.'">Indikator
                                                                  <input type="checkbox" id="all_indc'.$n.'">
                                                                  <span class="checkmark"></span>
                                                                </label>
                                                              </th>
                                                            </tr>
                                                          </thead>
                                                          <tbody>';
                                                  $na1=1;
                                                  foreach ($ind[$sb->kode_sub] as $i1) {
                                                      echo '<tr>
                                                      <td width="3%">
                                                        <label class="contx">'.$i1->kpi.'
                                                          <input type="checkbox" id="ind'.$n.''.$na1.'" name="indikator['.$sb->id_sub.'][]" value="'.$i1->kode_indikator.'">
                                                          <span class="checkmark"></span>
                                                        </label>
                                                      </td>
                                                      </tr>';
                                                    $na1++;
                                                  }
                                                  echo '</tbody>
                                                  </table>
                                                  </div>
                                                  </div>';
                                                }
                                              }
                                              
                                              echo '</td>
                                            </tr>';
                                            $n++;
                                          }
                                        }
                                      }
                                    }
                                      if (!in_array($l->id_jabatan, $resx)) {
                                        
                                      
                                        $ky=$this->db->query("SELECT id_karyawan,nama FROM karyawan WHERE jabatan = '$nmj' AND kode_sub IS NULL")->result();
                                        if (count($ky) > 0) {
                                          echo '<tr>
                                              <td width="3%">'.$n.'.</td>
                                              <td width="10%">
                                              <label class="contx">'.$l->jabatan;
                                              if (count($ky) != 0) {
                                                 echo '<input type="checkbox" id="jbt'.$n.'" name="jbt[]" value="'.$l->id_jabatan.'">';
                                              }   
                                                echo '
                                                <span class="checkmark"></span>
                                              </label>
                                              </td>
                                              <td>';
                                              if (count($ky) != 0) {
                                                echo '
                                                <label class="contx" data-toggle="tooltip" title="Centang Jika Jabatan Ini Terkena Konsolidasi">Konsolidasi
                                                  <input type="checkbox" id="kons'.$n.'" name="konsolidasi['.$l->id_jabatan.']" value="1">
                                                  <span class="checkmark"></span>
                                                </label>';
                                              }
                                              echo '</td>
                                              <td width="20%">';
                                              if (count($ky) == 0) {
                                                echo '<label class="label label-danger">Tidak Ada Karyawan</label>';
                                              }else{
                                                echo '<select class="form-control select2" name="emp['.$l->id_jabatan.'][]" multiple="multiple" data-placeholder="Pilih Karyawan" style="width: 95%;" title="Kosongkan Jika Semua Karyawan">';
                                                foreach ($ky as $k) {
                                                  echo '<option value="'.$k->id_karyawan.'"> '.$k->nama.'</option>';
                                                }
                                                echo '</select>';
                                              }
                                              echo '</td>
                                              <td width="60%">';
                                              $kt=$l->kode_kategori;
                                              if (count($ky) == 0) {
                                                echo '<label class="label label-danger">Tidak Ada Karyawan</label>';
                                              }else{
                                                if (count($ind) == 0) {
                                                  echo '<label class="label label-warning">Tidak Ada Indikator Untuk Kategori Jabatan Tersebut</label>';
                                                }else{
                                                  echo '<div class="row">
                                                  <div class="col-md-12" style="overflow : scroll; max-height:300px;">
                                                  <table class="table table-bordered table-striped table-hover">
                                                          <thead>
                                                            <tr class="bg-green">
                                                              <th>
                                                                <label class="contx" data-toggle="tooltip" title="Pilih Semua Indikator Jabatan '.$l->jabatan.'">Indikator
                                                                  <input type="checkbox" id="all_indc'.$n.'">
                                                                  <span class="checkmark"></span>
                                                                </label>
                                                              </th>
                                                            </tr>
                                                          </thead>
                                                          <tbody>';
                                                  $na=1;
                                                  foreach ($ind[$l->id_jabatan] as $i) {
                                                      echo '<tr>
                                                      <td width="3%">
                                                        <label class="contx">'.$i->kpi.'
                                                          <input type="checkbox" id="ind'.$n.''.$na.'" name="indikator['.$l->id_jabatan.'][]" value="'.$i->kode_indikator.'">
                                                          <span class="checkmark"></span>
                                                        </label>
                                                      </td>
                                                      </tr>';
                                                    $na++;
                                                  }
                                                  echo '</tbody>
                                                  </table>
                                                  </div>
                                                  </div>';
                                                }
                                              }
                                              
                                              echo '</td>
                                            </tr>';
                                            $n++; 
                                          }
                                      
                                      }
                                    }
                                    
                                  ?>
                                </tbody>
                              </table>
                            </div>
                            <div class="box-footer">
                              <div class="form-group pull-right">
                                <input type="hidden" name="kode" value="<?php echo $kode;?>">
                                <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                                <button class="btn btn-danger" type="reset" onclick="function myFunction() {location.reload();}"><i class="fa fa-refresh"></i> Reset</button>
                                <button class="btn btn-success" type="submit" id="save"><i class="fa fa-floppy-o"></i> Simpan</button>
                              </div>
                              <?php 
                                echo form_close();
                              } ?>
                            </div>
                          </div>
                          
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
                  if (count($concept_data) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label>
                    <ul>
                      <li>Pilih Jabatan untuk menentukan bobot setiap indikator</li>
                      <li>Bobot Akhir Penilaian hanya dapat di edit melalui <a href="<?php echo base_url('pages/master_level_jabatan');?>">Master Level Jabatan</a></li>
                      <li>Kaitkan Rancangan Penilaian dengan Agenda melalui menu <a href="<?php echo base_url('pages/agenda');?>">Agenda Penilaian > Daftar Agenda</a></li>
                    </ul>
                  </div>
                  <table id="example1" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Jabatan</th>
                        <th>Konsolidasi</th>
                        <th>Jumlah Indikator</th>
                        <th>Jumlah Karyawan</th>
                        <th>Jumlah Bobot Indikator</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        if (in_array($access['l_ac']['edt'], $access['access'])) {
                          $attr="type='submit'";
                        }else{
                          $attr='type="button" data-toggle="tooltip" title="Tidak Diizinkan"';
                        }
                        foreach ($concept_data as $d) {
                          $res[$d->id_jabatan]=$d->id_jabatan;
                          if ($d->id_sub != NULL) {
                            $res_s[$d->id_jabatan][$d->id_sub]=$d->id_sub;
                          }
                          
                        }

                        foreach ($res as $r) {
                          if (isset($res_s[$r])) {
                            foreach ($res_s[$r] as $sub) {
                              $data=$this->db->get_where($tabel,array('id_sub'=>$sub))->result();
                              foreach ($data as $dt) {
                                $dta[$sub][$dt->kode_indikator]=$dt->kode_indikator;
                                $dtak[$sub][$dt->id_karyawan]=$dt->id_karyawan;
                                $nama_s[$sub][$dt->sub]=$dt->sub;
                              }
                              $dtab[$sub]=array();
                              foreach ($dta[$sub] as $dta1) {
                                $data1=$this->db->get_where($tabel,array('id_sub'=>$sub,'kode_indikator'=>$dta1))->row_array();
                                array_push($dtab[$sub], $data1['bobot']);
                              }
                              $data2=$this->db->get_where($tabel,array('id_sub'=>$sub,'konsolidasi'=>'1'))->num_rows();
                              
                              
                              $nmj=$this->db->get_where('master_jabatan',array('id_jabatan'=>$r))->row_array();
                              $lv=$this->db->get_where('master_level_jabatan',array('kode_level'=>$nmj['kode_level']))->row_array();
                              echo '<tr>
                              <td width="5%">'.$n.'.</td>
                              <td><a href="'.base_url('pages/view_concept_setting/'.$kode.'/'.$r.'/'.$sub).'">'.$nmj['jabatan'];
                              if ($data2 > 0) {
                                echo ' <label class="label label-success">Konsolidasi</label>';
                              }
                              echo '<br><label class="label label-info">Sub Jabatan <b style="color:red">'.implode('', $nama_s[$sub]).'</a></td>
                              <td class="text-center">';
                              
                              echo form_open('setting/c_konsolidasi');
                              if ($data2 > 0) {
                                echo '<input type="hidden" name="id" value="'.$r.'">
                                <input type="hidden" name="sub" value="'.$sub.'">
                                <input type="hidden" name="kode" value="'.$kode.'">
                                <input type="hidden" name="tabel" value="'.$tabel.'">
                                <input type="hidden" name="act" value="0">
                                <button '.$attr.' class="stat scc" data-toggle="tooltip" data-placement="left" title="Konsolidasi"><i class="fa fa-toggle-on"></i></button>';
                              }else{
                                echo '<input type="hidden" name="id" value="'.$r.'">
                                <input type="hidden" name="sub" value="'.$sub.'">
                                <input type="hidden" name="kode" value="'.$kode.'">
                                <input type="hidden" name="tabel" value="'.$tabel.'">
                                <input type="hidden" name="act" value="1">
                                <button '.$attr.' class="stat err" data-toggle="tooltip" data-placement="left" title="Tidak Konsolidasi"><i class="fa fa-toggle-off"></i></button>';
                              }

                              echo form_close();
                              echo '</td>
                              <td>'.count($dta[$sub]).' Indikator</td>
                              <td>'.count($dtak[$sub]).' Karyawan</td>
                              <td>'.array_sum($dtab[$sub]).'%';
                              if (array_sum($dtab[$sub]) < 100 || array_sum($dtab[$sub]) > 100) {
                                echo ' <i class="fa fa-times-circle" data-toggle="tooltip" data-placement="left" title="Bobot Kurang atau Lebih dari 100%" style="color:red;"></i>';
                              }else{
                                echo ' <i class="fa fa-check-circle" data-toggle="tooltip" data-placement="left" title="Bobot Sudah 100%" style="color:green;"></i>';
                              }
                              echo '</td>
                                <td class="text-center">';
                                if (in_array($access['l_ac']['del'], $access['access'])) {
                                  echo '<a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="left"  title="Hapus Data"></i></a>';
                                }else{
                                  echo '<label class="label label-danger">Tidak Diizinkan</label>';
                                } 
                                echo '</td></tr>';
                                if (in_array($access['l_ac']['del'], $access['access'])) {
                                  echo '<div id="del'.$n.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-sm modal-danger">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                          </div>
                                          <div class="modal-body text-center">
                                            <p>Apakah anda yakin akan menghapus jabatan <b>'.$nmj['jabatan'].'</b> dan seluruh data karyawan maupun Indikator dalam jabatan tersebut?</p>
                                          </div>
                                          <div class="modal-footer">
                                          '.form_open('setting/del_jabatan_concept').'
                                            <input type="hidden" name="sub" value="'.$sub.'">
                                            <input type="hidden" name="id" value="'.$r.'"> 
                                            <input type="hidden" name="kode" value="'.$kode.'">
                                            <input type="hidden" name="tabel" value="'.$tabel.'">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                          '.form_close().'
                                          </div>
                                        </div>
                                      </div>
                                    </div>';
                                }
                            }
                          }else{
                            $data=$this->db->get_where($tabel,array('id_jabatan'=>$r))->result();
                            foreach ($data as $dt) {
                              $dta[$r][$dt->kode_indikator]=$dt->kode_indikator;
                              $dtak[$r][$dt->id_karyawan]=$dt->id_karyawan;
                            }
                            $dtab[$r]=array();
                            foreach ($dta[$r] as $dta1) {
                              $data1=$this->db->get_where($tabel,array('id_jabatan'=>$r,'kode_indikator'=>$dta1))->row_array();
                              array_push($dtab[$r], $data1['bobot']);
                            }
                            $data2=$this->db->get_where($tabel,array('id_jabatan'=>$r,'konsolidasi'=>'1'))->num_rows();
                            
                            
                            $nmj=$this->db->get_where('master_jabatan',array('id_jabatan'=>$r))->row_array();
                            $lv=$this->db->get_where('master_level_jabatan',array('kode_level'=>$nmj['kode_level']))->row_array();
                            echo '<tr>
                            <td width="5%">'.$n.'.</td>
                            <td><a href="'.base_url('pages/view_concept_setting/'.$kode.'/'.$r).'">'.$nmj['jabatan'];
                              if ($data2 > 0) {
                                echo ' <label class="label label-success">Konsolidasi</label>';
                              }
                            echo '</a></td>
                            <td class="text-center">';
                            echo form_open('setting/c_konsolidasi');
                            if ($data2 > 0) {
                              echo '<input type="hidden" name="id" value="'.$r.'">
                              <input type="hidden" name="kode" value="'.$kode.'">
                              <input type="hidden" name="tabel" value="'.$tabel.'">
                              <input type="hidden" name="act" value="0">
                              <button '.$attr.' class="stat scc" data-toggle="tooltip" data-placement="left" title="Konsolidasi"><i class="fa fa-toggle-on"></i></button>';
                            }else{
                              echo '<input type="hidden" name="id" value="'.$r.'">
                              <input type="hidden" name="kode" value="'.$kode.'">
                              <input type="hidden" name="tabel" value="'.$tabel.'">
                              <input type="hidden" name="act" value="1">
                              <button '.$attr.' class="stat err" data-toggle="tooltip" data-placement="left" title="Tidak Konsolidasi"><i class="fa fa-toggle-off"></i></button>';
                            }
                            echo form_close();
                            echo '</td>
                            <td>'.count($dta[$r]).' Indikator</td>
                            <td>'.count($dtak[$r]).' Karyawan</td>
                            <td>'.array_sum($dtab[$r]).'%';
                            if (array_sum($dtab[$r]) < 100 || array_sum($dtab[$r]) > 100) {
                              echo ' <i class="fa fa-times-circle" data-toggle="tooltip" data-placement="left" title="Bobot Kurang atau Lebih dari 100%" style="color:red;"></i>';
                            }else{
                              echo ' <i class="fa fa-check-circle" data-toggle="tooltip" data-placement="left" title="Bobot Sudah 100%" style="color:green;"></i>';
                            }
                            echo '</td>
                              <td class="text-center">';
                              if (in_array($access['l_ac']['del'], $access['access'])) {
                                echo '<a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-placement="left"  data-toggle="tooltip" title="Hapus Data"></i></a>';
                              }else{
                                echo '<label class="label label-danger">Tidak Diizinkan</label>';
                              }
                              echo '</td></tr>';
                              if (in_array($access['l_ac']['del'], $access['access'])) {
                                echo '<div id="del'.$n.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-danger">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                        </div>
                                        <div class="modal-body text-center">
                                          <p>Apakah anda yakin akan menghapus jabatan <b>'.$nmj['jabatan'].'</b> dan seluruh data karyawan maupun Indikator dalam jabatan tersebut?</p>
                                        </div>
                                        <div class="modal-footer">
                                        '.form_open('setting/del_jabatan_concept').'
                                          <input type="hidden" name="id" value="'.$r.'">
                                          <input type="hidden" name="kode" value="'.$kode.'">
                                          <input type="hidden" name="tabel" value="'.$tabel.'">
                                          <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                              }
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
